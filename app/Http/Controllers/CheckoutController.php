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
        $shipping_cost = 0.20;
        $total = $subtotal + $shipping_cost;

        session([
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'total' => $total
        ]);

        $user_id = session('user_data')['uid'] ?? null;
        $userData = [];

        // ❗ Paksa refresh Firestore user profile setiap kali buka page checkout
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

        session([
            'user_profile' => $userData,
            'user_email' => $userData['email']
        ]);



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



    public function toyyibpayRedirect()
    {
        $user = session('user_data');
        $total = session('total', 0);

        $billName = 'Adik Cosmetics Order';
        $billDescription = 'Your payment order';
        $billAmount = $total * 100; // sen
        $billTo = $user['first_name'] ?? 'Customer';
        $billEmail = $user['email'] ?? 'guest@example.com';
        $billPhone = $user['phone'] ?? '0123456789';
        $callbackUrl = route('checkout.toyyibpayReturn');

        $response = Http::asForm()->post('https://toyyibpay.com/index.php/api/createBill', [
            'userSecretKey' => env('TOYYIBPAY_USER_SECRET_KEY'),
            'categoryCode' => env('TOYYIBPAY_CATEGORY_CODE'),
            'billName' => $billName,
            'billDescription' => $billDescription,
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => $billAmount,
            'billReturnUrl' => $callbackUrl,
            'billCallbackUrl' => $callbackUrl,
            'billTo' => $billTo,
            'billEmail' => $billEmail,
            'billPhone' => $billPhone,
        ]);
        // TENGOK RESPONSE API
        /*dd([
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);*/


        if ($response->successful() && isset($response[0]['BillCode'])) {
            return redirect("https://toyyibpay.com/{$response[0]['BillCode']}");

        }

        return back()->with('error', 'Failed to redirect to ToyyibPay');
    }
    private function calculateSubtotal($cart)
    {
        return array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function toyyibpayCallback(Request $request)
    {
        \Log::info("📥 MASUK toyyibpayCallback(), method: " . $request->method());
        \Log::info("✅ status_id = " . $request->query('status_id'));

        if ($request->has('status_id') && $request->query('status_id') != 1) {
            return redirect()->route('home')->with('error', 'Your Payment is cancelled.');
        }

        $cart = session('cart', []);
        $subtotal = session('subtotal');
        $shipping_cost = session('shipping_cost');
        $total = session('total');
        $user = session('user_data');
        $user_email = session('user_email');

        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Cart kosong.');
        }

        $orderData = [
            'user' => $user,
            'items' => $cart,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'total' => $total,
            'date' => now()->format('d M Y'),
        ];

        // Simpan ke Firestore
        $accessToken = FirebaseHelper::getAccessToken();
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

        $payload = [
            'fields' => [
                'user_id' => ['stringValue' => $user['uid']],
                'total' => ['doubleValue' => (float) $total],
                'status' => ['stringValue' => 'Paid'],
                'shipping' => ['stringValue' => 'Pending'],
                'return_status' => ['stringValue' => 'None'],
                'created_at' => ['timestampValue' => now()->toIso8601String()],
                'items' => ['arrayValue' => ['values' => $formattedCart]],
            ]
        ];

        Http::withToken($accessToken)->post("https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/orders", $payload);

        // Hantar email confirmation TANPA PDF
        if ($user_email) {
            try {
                Mail::to($user_email)->send(new OrderConfirmationMail($orderData, $user_email));
            } catch (\Exception $e) {
                \Log::error('Fail to send email: ' . $e->getMessage());
            }
        }

        // Kosongkan cart
        session()->forget(['cart', 'subtotal', 'shipping_cost']);

        return view('checkout.thankyou', ['total' => $total]);
    }
    public function payment()
    {
        $cart = session('cart', []);
        $subtotal = $this->calculateSubtotal($cart);
        $shipping_cost = 0.20;
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