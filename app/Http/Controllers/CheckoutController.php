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
        \Log::info('✅ Enter process() from checkout.blade');
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'postcode' => 'required|string',
            'country' => 'required|string',
        ]);
        session([
            'shipping_info' => $request->only([
                'first_name',
                'last_name',
                'phone',
                'address',
                'city',
                'postcode',
                'country'
            ])
        ]);
        return redirect()->route('checkout.payment');
    }

    public function toyyibpayRedirect()
    {
        $user = session('user_data');
        $total = session('total', 0);
        $billName = 'Adik Cosmetics Order';
        $billAmount = $total * 100;
        $callbackUrl = route('checkout.toyyibpayReturn');
        $response = Http::asForm()->post('https://toyyibpay.com/index.php/api/createBill', [
            'userSecretKey' => env('TOYYIBPAY_USER_SECRET_KEY'),
            'categoryCode' => env('TOYYIBPAY_CATEGORY_CODE'),
            'billName' => $billName,
            'billDescription' => 'Your payment order',
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => $billAmount,
            'billReturnUrl' => $callbackUrl,
            'billCallbackUrl' => $callbackUrl,
            'billTo' => $user['first_name'] ?? 'Customer',
            'billEmail' => $user['email'] ?? 'guest@example.com',
            'billPhone' => $user['phone'] ?? '0123456789',
        ]);
        if ($response->successful() && isset($response[0]['BillCode'])) {
            return redirect("https://toyyibpay.com/{$response[0]['BillCode']}");
        }
        return back()->with('error', 'Failed to redirect to ToyyibPay');
    }
    private function calculateSubtotal($cart)
    {
        return array_reduce($cart, fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0);
    }
    public function toyyibpayCallback(Request $request)
    {
        \Log::info("📥 MASUK toyyibpayCallback(), method: " . $request->method());
        if ($request->query('status_id') != 1) {
            return redirect()->route('home')->with('error', 'Your Payment is cancelled.');
        }
        return $this->finalizeOrder('Paid');
    }
    public function handlePayment(Request $request)
    {
        $method = $request->input('payment_method');
        session(['payment_method' => $method]);
        switch ($method) {
            case 'toyyibpay':
                return redirect()->route('checkout.toyyibpayRedirect');
            case 'card':
                return $this->finalizeOrder('Paid');
            case 'bank_transfer':
                $request->validate(['bank' => 'required|string']);
                session(['selected_bank' => $request->bank]);
                // Set bank transfer as Paid immediately
                return $this->simulateBankTransfer($request, 'Paid');
            case 'cod':
                return $this->finalizeOrder('Unpaid', true);
            default:
                return back()->with('error', 'Invalid payment method selected.');
        }
    }
    public function simulateBankTransfer(Request $request, $status = 'Unpaid')
    {
        $user = session('user_data') ?? [];
        $user_id = $user['uid'] ?? null;
        $user_email = session('user_email') ?? $user['email'] ?? null;

        if (!$user_id) {
            \Log::error('❌ Order gagal: user_id kosong.');
            return redirect()->route('home')->with('error', 'User not found. Please login again.');
        }
        $cart = session('cart', []);
        $subtotal = session('subtotal');
        $shipping_cost = session('shipping_cost');
        $total = session('total');

        $orderData = compact('user', 'cart', 'subtotal', 'shipping_cost', 'total');
        $orderData['bank'] = $request->bank;
        $orderData['date'] = now()->format('d M Y');
        $formattedCart = array_map(function ($item) {
            return [
                'mapValue' => [
                    'fields' => [
                        'name' => ['stringValue' => $item['name']],
                        'quantity' => ['integerValue' => (string) $item['quantity']],
                        'price' => ['doubleValue' => (float) $item['price']],
                    ]
                ]
            ];
        }, array_values($cart));
        $payload = [
            'fields' => [
                'user_id' => ['stringValue' => $user_id],
                'total' => ['doubleValue' => (float) $total],
                'status' => ['stringValue' => $status], // ✅ TERUS PAID
                'shipping' => ['stringValue' => 'Pending'],
                'return_status' => ['stringValue' => 'None'],
                'bank' => ['stringValue' => $request->bank],
                'created_at' => ['timestampValue' => now()->toIso8601String()],
                'items' => ['arrayValue' => ['values' => $formattedCart]],
            ]
        ];
        \Log::info('🛒 Saving order with user_id:', ['uid' => $user_id]);
        \Log::info('🧾 Payload:', $payload);
        $response = Http::withToken(FirebaseHelper::getAccessToken())
            ->post("https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/orders", $payload);
        if (!$response->successful()) {
            \Log::error('❌ Failed to save order to Firestore:', [
                'error' => $response->json(),
                'payload' => $payload,
            ]);
            return redirect()->route('home')->with('error', 'Failed to place order. Please try again.');
        }
        try {
            Mail::to($user_email)->send(new OrderConfirmationMail($orderData, $user_email));
            Mail::to('admin@adikcosmetics.com')->send(new OrderConfirmationMail($orderData, 'admin@adikcosmetics.com'));
        } catch (\Exception $e) {
            \Log::error('Email gagal: ' . $e->getMessage());
        }
        session()->forget(['cart', 'subtotal', 'shipping_cost']);
        return view('checkout.thankyou', ['total' => $total]);
    }
    private function finalizeOrder($status, $isCod = false)
    {
        $user = session('user_data') ?? [];
        $user_id = $user['uid'] ?? null;
        $user_email = session('user_email') ?? $user['email'] ?? null;
        if (!$user_id) {
            \Log::error('❌ Order failed: empty user_id .');
            return redirect()->route('home')->with('error', 'User not found. Please login again.');
        }
        $cart = session('cart', []);
        $subtotal = session('subtotal');
        $shipping_cost = session('shipping_cost');
        $total = session('total');
        $orderData = compact('user', 'cart', 'subtotal', 'shipping_cost', 'total');
        $orderData['date'] = now()->format('d M Y');
        $formattedCart = array_map(function ($item) {
            return [
                'mapValue' => [
                    'fields' => [
                        'name' => ['stringValue' => $item['name']],
                        'quantity' => ['integerValue' => (string) $item['quantity']],
                        'price' => ['doubleValue' => (float) $item['price']],
                    ]
                ]
            ];
        }, array_values($cart)); // 💥 fix critical bug here!
        $payload = [
            'fields' => [
                'user_id' => ['stringValue' => $user_id],
                'total' => ['doubleValue' => (float) $total],
                'status' => ['stringValue' => $status],
                'shipping' => ['stringValue' => 'Pending'],
                'return_status' => ['stringValue' => 'None'],
                'created_at' => ['timestampValue' => now()->toIso8601String()],
                'items' => ['arrayValue' => ['values' => $formattedCart]],
            ]
        ];
        \Log::info('🛒 Saving order with user_id:', ['uid' => $user_id]);
        \Log::info('🧾 Payload:', $payload);
        $response = Http::withToken(FirebaseHelper::getAccessToken())
            ->post("https://firestore.googleapis.com/v1/projects/adikcosmetics-1518b/databases/(default)/documents/orders", $payload);
        if (!$response->successful()) {
            \Log::error('❌ Failed to save order to Firestore:', [
                'error' => $response->json(),
                'payload' => $payload,
            ]);
            return redirect()->route('home')->with('error', 'Failed to place order. Please try again.');
        }
        try {
            Mail::to($user_email)->send(new OrderConfirmationMail($orderData, $user_email));
            Mail::to('admin@adikcosmetics.com')->send(new OrderConfirmationMail($orderData, 'admin@adikcosmetics.com'));
        } catch (\Exception $e) {
            \Log::error('Fail to send email: ' . $e->getMessage());
        }
        session()->forget(['cart', 'subtotal', 'shipping_cost']);
        return $isCod ? view('checkout.cod_success') : view('checkout.thankyou', ['total' => $total]);
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
    public function showPaymentPage()
    {
        $subtotal = session('subtotal', 0);
        $shipping_cost = session('shipping_cost', 0.20);
        $total = $subtotal + $shipping_cost;
        return view('checkout.payment', compact('subtotal', 'shipping_cost', 'total'));
    }
    public function thankyou()
    {
        $total = session('total', 0);
        return view('checkout.thankyou', compact('total'));
    }
}
