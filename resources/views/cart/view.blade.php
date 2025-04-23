@extends('layouts.main')

@section('title', 'Your Cart')

@section('content')
    <h2>Your Cart</h2>
    <p>Here you can see all the products you've added to your cart.</p>

    <!-- Cart Items Section -->
    <div class="cart-items">
        <!-- Example of cart item -->
        <div class="cart-item">
            <img src="{{ asset('images/product1.jpg') }}" alt="Product Name">
            <h3>Product Name</h3>
            <p>RM99.99</p>
            <button class="remove-from-cart-btn">Remove</button>
        </div>
    </div>

    <!-- Cart Actions -->
    <div class="cart-actions">
        <button class="checkout-btn">Checkout</button>
    </div>
@endsection
