@extends('layouts.main')

@section('title', 'Shipping Page')

@section('header')
    @include('partials.header-home') <!-- Include your header -->
@endsection

@section('content')

    <div class="container">
        <div class="form-section">
            <h2>Shipping Details</h2>
            <p>Delivery address</p>
            <form method="POST" action="{{ route('checkout.submit') }}">
                @csrf

                <div class="input-group">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" value="{{ $userData['first_name'] ?? '' }}" required>
                </div>

                <div class="input-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" value="{{ $userData['last_name'] ?? '' }}" required>
                </div>

                <div class="input-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" value="{{ $userData['phone'] ?? '' }}" required>
                </div>

                <div class="input-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" value="{{ $userData['address'] ?? '' }}" required>
                </div>

                <div class="row">
                    <div class="input-group">
                        <label for="city">City</label>
                        <input type="text" name="city" value="{{ $userData['city'] ?? '' }}" required>
                    </div>

                    <div class="input-group">
                        <label for="postcode">Postcode</label>
                        <input type="text" name="postcode" value="{{ $userData['postcode'] ?? '' }}" required>
                    </div>

                    <div class="input-group">
                        <label for="country">Country</label>
                        <input type="text" name="country" value="{{ $userData['country'] ?? '' }}" required>
                    </div>
                </div>

                <button type="submit">Proceed to Payment</button>
            </form>
        </div>


        <div class="order-summary">
            <h3>Order Summary</h3>
            @foreach ($cart as $item)
                <div class="product">
                    <img src="{{ $item['image_url'] }}" alt="Product Image">
                    <div class="product-details">
                        <p>{{ $item['name'] }}</p>
                        <p>Qty: {{ $item['quantity'] }}</p>
                        <p>Price: RM{{ number_format($item['price'], 2) }}</p>
                    </div>
                </div>
            @endforeach
            <div class="summary">
                <p>Subtotal ({{ count($cart) }} items): <strong>RM{{ number_format($subtotal, 2) }}</strong></p>
                <p>Shipping Cost: <strong>RM{{ number_format($shipping_cost, 2) }}</strong></p>
                <hr>
                <p class="total">Total: <strong>RM{{ number_format($total, 2) }}</strong></p>
            </div>
        </div>
    </div>
    <!-- CSS for checkout page -->
    <style>
        /* Main Layout */
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            max-width: 1000px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
        }

        .form-section {
            flex: 1 1 60%;
            min-width: 300px;
        }

        .order-summary {
            flex: 1 1 35%;
            min-width: 250px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2,
        h3 {
            color: #7c3d4f;
        }

        label {
            display: block;
            font-size: 0.9em;
            color: #7c3d4f;
            margin-bottom: 5px;
        }

        .input-group {
            margin-bottom: 15px;
            flex: 1;
        }

        input {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .row {
            display: flex;
            gap: 15px;
        }

        .full-width {
            width: 100%;
        }

        button {
            background-color: #9e5866;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #874554;
        }

        .product {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .product-details p {
            margin: 2px 0;
        }

        .product img {
            max-width: 80px;
            /* Mengecilkan gambar */
            height: auto;
            object-fit: cover;
            /* Pastikan gambar tidak terdistorsi */
        }

        .summary {
            margin-top: 20px;
        }

        .total {
            font-size: 1.2em;
            color: #7c3d4f;
            font-weight: bold;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                gap: 10px;
            }

            .form-section,
            .order-summary {
                width: 100%;
            }

            .row {
                flex-direction: column;
            }
        }
    </style>
@endsection
