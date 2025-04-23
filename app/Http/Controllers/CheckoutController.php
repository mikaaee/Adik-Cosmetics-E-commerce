<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    // Page checkout
    public function index()
    {
        $cart = session('cart', []);
        $subtotal = $this->calculateSubtotal($cart);
        $shipping_cost = 8.00; // Kos penghantaran tetap

        $total = $subtotal + $shipping_cost; // Jumlah keseluruhan

        return view('checkout.checkout', compact('cart', 'subtotal', 'shipping_cost', 'total'));
    }


    // Submit checkout (create order)
    public function submit(Request $request)
    {
        // Ambil data dari form
        $user_id = Session::get('user_id'); // Ambil Firestore User ID dari session
        $address = $request->input('address');
        $city = $request->input('city');
        $postcode = $request->input('postcode');
        $country = $request->input('country');
        $phone = $request->input('phone');
    
        // Ambil cart items daripada session
        $cart = session('cart', []);
        $order_items = [];
        $total_price = 0;
    
        foreach ($cart as $productId => $item) {  // Akses berdasarkan Firestore document ID (productId)
            // Pastikan produk ada dalam cart
            if (isset($item['id']) && isset($item['name']) && isset($item['price'])) {
                $order_items[] = [
                    'product_id' => $productId, // Gunakan document ID sebagai product_id
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ];
                $total_price += $item['quantity'] * $item['price'];
            }
        }
    
        // Buat data order
        $order_data = [
            'user_id' => $user_id, // Firestore user document ID
            'address' => $address,
            'city' => $city,
            'postcode' => $postcode,
            'country' => $country,
            'phone' => $phone,
            'order_items' => $order_items,
            'total_price' => $total_price,
            'status' => 'pending', // Default status order
            'created_at' => now(),
        ];
    
        // Call Firestore REST API untuk create order
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('FIREBASE_API_KEY'),
        ])->post('https://firestore.googleapis.com/v1/projects/YOUR_PROJECT_ID/databases/(default)/documents/orders', [
                    'fields' => [
                        'user_id' => ['stringValue' => $user_id],
                        'address' => ['stringValue' => $address],
                        'city' => ['stringValue' => $city],
                        'postcode' => ['stringValue' => $postcode],
                        'country' => ['stringValue' => $country],
                        'phone' => ['stringValue' => $phone],
                        'order_items' => ['arrayValue' => $order_items],
                        'total_price' => ['doubleValue' => $total_price],
                        'status' => ['stringValue' => 'pending'],
                        'created_at' => ['timestampValue' => now()],
                    ]
                ]);
    
        // Handle response dari Firestore API
        if ($response->successful()) {
            // Clear cart selepas order berjaya
            session()->forget('cart');
    
            // Redirect atau beri mesej kejayaan
            return redirect()->route('checkout.success');
        } else {
            // Handle error response
            return back()->withErrors('Failed to create order.');
        }
    }
    

    // Success page selepas order berjaya
    public function success()
    {
        return view('checkout.success');
    }

    // Function untuk kira subtotal
    private function calculateSubtotal($cart)
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }
    // Controller Method
    public function showCheckout()
    {
        $cart = session('cart');  // Ambil cart dari session
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];  // Kirakan subtotal
        }

        $shipping_cost = 8.00;  // Kos penghantaran tetap, atau anda boleh kira berdasarkan lokasi
        $total = $subtotal + $shipping_cost;  // Jumlah keseluruhan

        return view('checkout', [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'total' => $total
        ]);
    }

}
