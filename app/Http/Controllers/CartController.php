<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /* ---------- tambah item ---------- */
    public function addToCart(Request $request, $productId)
    {
        // ensure user login
        $user = session('user_data');
        if (!$user || !isset($user['uid'])) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        $cart = session('cart', []);
        // add quantity if item dah ada
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            //new ites
            $cart[$productId] = [
                'name'      => $request->name,
                'price'     => $request->price,
                'image_url' => $request->image_url,
                'quantity'  => 1,
            ];
        }
        session(['cart' => $cart]);
        return back()->with('success', 'Product added to cart!');
    }

    /* ---------- display cart ---------- */
    public function viewCart()
    {
        $cart = session('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return view('cart.index', [
            'cart'     => $cart,
            'subtotal' => $subtotal,
        ]);
    }

    /* ---------- Update Quantity ---------- */
    public function update(Request $request, $productId)
    {
        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, (int)$request->quantity);
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.view');
    }

    /* ---------- Remove Items ---------- */
    public function remove($productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);           // buang terus key tersebut
        session(['cart' => $cart]);

        return redirect()->route('cart.view');
    }
}
