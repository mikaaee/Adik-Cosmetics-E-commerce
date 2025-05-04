@extends('layouts.main')

@section('title', 'Payment Page')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        h2, label {
            color: #7c3d4f;
        }

        label {
            display: block;
            font-size: 0.9em;
            margin-bottom: 6px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        button {
            background-color: #9e5866;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1em;
        }

        button:hover {
            background-color: #874554;
        }

        .summary {
            margin-top: 30px;
            font-size: 1em;
            color: #7c3d4f;
        }

        .summary p {
            margin: 5px 0;
        }

        .summary .total {
            font-weight: bold;
            font-size: 1.2em;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            button {
                padding: 10px;
            }
        }
    </style>

    <div class="container">
        <h2>Payment Details</h2>
        <form method="POST" action="{{ route('checkout.processPayment') }}">
            @csrf

            <div class="form-group">
                <label for="card_name">Name on Card</label>
                <input type="text" name="card_name" placeholder="John Doe" required>
            </div>

            <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" name="card_number" placeholder="xxxx-xxxx-xxxx-xxxx" required>
            </div>

            <div class="form-group">
                <label for="expiry">Expiry Date</label>
                <input type="text" name="expiry" placeholder="MM/YY" required>
            </div>

            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" name="cvv" placeholder="123" required>
            </div>

            <button type="submit">Complete Payment</button>
        </form>

        <div class="summary">
            <hr>
            <p>Subtotal: <strong>RM{{ number_format($subtotal, 2) }}</strong></p>
            <p>Shipping: <strong>RM{{ number_format($shipping_cost, 2) }}</strong></p>
            <p class="total">Total: RM{{ number_format($total, 2) }}</p>
        </div>
    </div>
@endsection
