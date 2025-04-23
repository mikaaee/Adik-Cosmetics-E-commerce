@extends('layouts.main')

@section('title', 'Shopping Cart')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
<section class="cart">
    <h1>Your Shopping Cart</h1>

    @if (count($cart))
        <table>
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
                @foreach ($cart as $id => $item)   {{-- $id ialah Firestore document ID --}}
                    <tr>
                        <td>
                            <div class="cart-item">
                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" style="width:80px">
                                <span>{{ $item['name'] }}</span>
                            </div>
                        </td>
                        <td>RM{{ number_format($item['price'], 2) }}</td>

                        <td>
                            <form action="{{ route('cart.update', $id) }}" method="POST" class="qty-form">
                                @csrf
                                @method('PATCH')
                            
                                <button type="button" class="qty-btn minus">‑</button>
                            
                                <input type="number"
                                       name="quantity"
                                       value="{{ $item['quantity'] }}"
                                       min="1"
                                       class="qty-input">
                            
                                <button type="button" class="qty-btn plus">+</button>
                            </form>
                            
                        </td>

                        <td>RM{{ number_format($item['price'] * $item['quantity'], 2) }}</td>

                        <td>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="remove">
                                    <i class="fa fa-trash"></i> </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="cart-summary">
            <h3>
                Subtotal:
                <span id="subtotal">
                    RM{{ number_format($subtotal ?? 0, 2) }}
                </span>
            </h3>
            <form action="{{ route('checkout.submit') }}" method="GET">
                @csrf
                <button type="submit" class="checkout">Proceed to Checkout</button>
            </form>
            
        </div>
    @else
        <p>Your cart is empty.</p>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Untuk setiap form kuantiti
            document.querySelectorAll('.qty-form').forEach(form => {
                const input = form.querySelector('.qty-input');
                const minus = form.querySelector('.minus');
                const plus  = form.querySelector('.plus');
        
                minus.addEventListener('click', () => {
                    let val = parseInt(input.value) || 1;
                    if (val > 1) {
                        input.value = val - 1;
                        form.submit();
                    }
                });
        
                plus.addEventListener('click', () => {
                    let val = parseInt(input.value) || 1;
                    input.value = val + 1;
                    form.submit();
                });
        
                // Kalau user ubah manual
                input.addEventListener('change', () => form.submit());
            });
        });
        </script>
        
</section>
@endsection
