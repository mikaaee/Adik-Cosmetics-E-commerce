@extends('layouts.main')

@section('title', 'Shopping Cart')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
<section class="cart">
    <h1>Your Shopping Cart</h1>

    @if (count($cart))
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $id => $item)
                    <tr class="cart-item">
                        <td class="product-cell">
                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="product-image">
                            <span>{{ $item['name'] }}</span>
                        </td>
                        <td class="price-cell">RM{{ number_format($item['price'], 2) }}</td>
                        <td class="quantity-cell">
                            <div class="quantity-control">
                                <button class="qty-btn minus">−</button>
                                <span class="qty-display">{{ $item['quantity'] }}</span>
                                <button class="qty-btn plus">+</button>
                            </div>
                        </td>
                        <td class="total-cell">RM{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                        <td class="action-cell">
                            <button class="remove-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="cart-summary">
            <div class="subtotal">
                Subtotal: <span class="amount">RM{{ number_format($subtotal ?? 0, 2) }}</span>
            </div>
            <button class="checkout-btn">Proceed to Checkout</button>
        </div>
    @else
        <div class="empty-cart">
            <p>Your cart is empty.</p>
        </div>
    @endif
</section>

<style>
    /* Cart Container */
    .cart {
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    /* Table Styles */
    .cart-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .cart-table th {
        text-align: left;
        padding: 12px;
        background-color: #f5f5f5;
        border-bottom: 2px solid #ddd;
    }

    .cart-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    /* Product Cell */
    .product-cell {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .product-image {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 4px;
    }

    /* Quantity Controls */
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .qty-btn {
        width: 30px;
        height: 30px;
        background: #f0f0f0;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .qty-btn:hover {
        background: #e0e0e0;
    }

    .qty-display {
        min-width: 30px;
        text-align: center;
    }

    /* Remove Button */
    .remove-btn {
        background: none;
        border: none;
        color: #ff4444;
        cursor: pointer;
        font-size: 16px;
        padding: 5px;
    }

    /* Cart Summary */
    .cart-summary {
        text-align: right;
        padding: 20px 0;
    }

    .subtotal {
        font-size: 18px;
        margin-bottom: 15px;
    }

    .amount {
        font-weight: bold;
        color: #e53935;
    }

    .checkout-btn {
        background-color: #c69c9c;
        color: white;
        border: none;
        padding: 12px 24px;
        font-size: 16px;
        border-radius: 20px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .checkout-btn:hover {
        background-color: #b18383;
    }

    /* Empty Cart */
    .empty-cart {
        text-align: center;
        padding: 40px 0;
        color: #666;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity buttons functionality
        document.querySelectorAll('.quantity-control').forEach(control => {
            const minusBtn = control.querySelector('.minus');
            const plusBtn = control.querySelector('.plus');
            const qtyDisplay = control.querySelector('.qty-display');
            
            minusBtn.addEventListener('click', function() {
                let currentQty = parseInt(qtyDisplay.textContent);
                if (currentQty > 1) {
                    qtyDisplay.textContent = currentQty - 1;
                    // In a real implementation, you would update the cart here
                }
            });
            
            plusBtn.addEventListener('click', function() {
                let currentQty = parseInt(qtyDisplay.textContent);
                qtyDisplay.textContent = currentQty + 1;
                // In a real implementation, you would update the cart here
            });
        });
        
        // Remove button functionality
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const cartItem = this.closest('.cart-item');
                cartItem.remove();
                // In a real implementation, you would update the cart here
            });
        });
    });
</script>
@endsection