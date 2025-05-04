<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Helpers\FirebaseHelper;
use Illuminate\Support\Facades\File;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $subtotal = $this->calculateSubtotal($cart);
        $shipping_cost = 8.00;
        $total = $subtotal + $shipping_cost;

        session([
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'total' => $total
        ]);

        $user_id = session('user_data')['uid'] ?? null;
        $userData = [];

        if ($user_id) {
            $response = Http::get("https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/users/{$user_id}");
            $fields = $response['fields'] ?? [];
            $userData = [
                'first_name' => $fields['first_name']['stringValue'] ?? '',
                'last_name' => $fields['last_name']['stringValue'] ?? '',
                'address' => $fields['address']['stringValue'] ?? '',
                'city' => $fields['city']['stringValue'] ?? '',
                'postcode' => $fields['postcode']['stringValue'] ?? '',
                'country' => $fields['country']['stringValue'] ?? '',
                'phone' => $fields['phone']['stringValue'] ?? '',
                'email' => $fields['email']['stringValue'] ?? '',
            ];
            session(['user_email' => $userData['email']]);
        }

        return view('checkout.checkout', compact('cart', 'subtotal', 'shipping_cost', 'total', 'userData'));
    }
    public function process(Request $request)
    {
        \Log::info('✅ Masuk process() dari checkout.blade');

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'postcode' => 'required|string',
            'country' => 'required|string',
        ]);

        // Simpan info shipping ke dalam session
        $shippingInfo = $request->only([
            'first_name',
            'last_name',
            'phone',
            'address',
            'city',
            'postcode',
            'country',
        ]);

        session(['shipping_info' => $shippingInfo]);

        return redirect()->route('checkout.payment');
    }



    public function processPayment(Request $request)
    {
        \Log::info('🧪 Masuk processPayment function');

        $request->validate([
            'card_name' => 'required|string',
            'card_number' => 'required|string',
            'expiry' => 'required|string',
            'cvv' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('checkout')->with('error', 'Troli kosong.');
        }

        $subtotal = session('subtotal');
        $shipping_cost = session('shipping_cost');
        $total = session('total');

        $user = session('user_data');
        $user_email = session('user_email');

        // Auto generate invoice number
        $invoiceNo = 'INV-' . strtoupper(uniqid());

        // Format cart items
        $formattedCart = [];
        foreach ($cart as $item) {
            $formattedCart[] = [
                'mapValue' => [
                    'fields' => [
                        'name' => ['stringValue' => $item['name']],
                        'quantity' => ['integerValue' => (string) $item['quantity']],
                        'price' => ['doubleValue' => (float) $item['price']],
                    ]
                ]
            ];
        }

        $orderData = [
            'invoice_no' => $invoiceNo,
            'user' => $user,
            'items' => $cart,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'total' => $total,
            'date' => now()->format('d M Y'),
        ];
        // ✅ Ensure folder exists
        $invoiceDir = storage_path('app/invoices');
        if (!File::exists($invoiceDir)) {
            File::makeDirectory($invoiceDir, 0755, true); // create folder with permission
        }
        // Generate PDF invoice
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', $orderData);
        $filename = 'invoice_' . $invoiceNo . '.pdf';
        $filepath = storage_path('app/invoices/' . $filename);
        $pdf->save($filepath);

        // Simpan ke Firestore
        $accessToken = FirebaseHelper::getAccessToken();
        $firestorePayload = [
            'fields' => [
                'user_id' => ['stringValue' => $user['uid']],
                'total' => ['doubleValue' => (float) $total],
                'status' => ['stringValue' => 'Paid'],
                'shipping' => ['stringValue' => 'Pending'],
                'return_status' => ['stringValue' => 'None'],
                'created_at' => ['timestampValue' => now()->toIso8601String()],
                'invoice_no' => ['stringValue' => $invoiceNo],
                'invoice_path' => ['stringValue' => $filepath], // save path to Firestore
                'items' => ['arrayValue' => ['values' => $formattedCart]],
            ]
        ];

        $response = Http::withToken($accessToken)
            ->post("https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/orders", $firestorePayload);

        if ($response->failed()) {
            \Log::error('Gagal simpan pesanan ke Firestore', ['response' => $response->body()]);
            return redirect()->back()->with('error', 'Gagal simpan pesanan. Sila cuba lagi.');
        }

        // Kosongkan cart
        session()->forget(['cart', 'subtotal', 'shipping_cost']);

        // Hantar email dengan attachment
        if ($user_email) {
            try {
                Mail::to($user_email)->send(new \App\Mail\OrderConfirmationMail($orderData, $user_email, $filepath));
            } catch (\Exception $e) {
                \Log::error('Gagal hantar emel', ['message' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Emel gagal dihantar: ' . $e->getMessage());
            }
        }
        // Simpan invoice ke Firestore
        $invoicePayload = [
            'fields' => [
                'invoice_no' => ['stringValue' => $invoiceNo],
                'user_id' => ['stringValue' => $user['uid']],
                'total' => ['doubleValue' => (float) $total],
                'file_path' => ['stringValue' => 'invoices/' . $filename],
                'created_at' => ['timestampValue' => now()->toIso8601String()],
            ]
        ];

        Http::withToken($accessToken)->post(
            "https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/invoices",
            $invoicePayload
        );


        return view('checkout.thankyou', ['total' => $total]);
    }

    private function calculateSubtotal($cart)
    {
        return array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function payment()
    {
        $cart = session('cart', []);
        $subtotal = $this->calculateSubtotal($cart);
        $shipping_cost = 8.00;
        $total = $subtotal + $shipping_cost;

        session([
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'total' => $total
        ]);

        return view('checkout.payment', compact('cart', 'subtotal', 'shipping_cost', 'total'));
    }

    public function thankyou()
    {
        return view('checkout.thankyou');
    }
}
