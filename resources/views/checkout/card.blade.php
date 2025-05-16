@extends('layouts.main')

@section('title', 'Card Payment')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
    <style>
        .payment-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .payment-title {
            font-size: 1.8em;
            color: #7c3d4f;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            color: #7c3d4f;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e3d7da;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1em;
            transition: 0.3s ease;
        }

        .form-control:focus {
            border-color: #9e5866;
            outline: none;
            box-shadow: 0 0 5px rgba(158, 88, 102, 0.2);
        }

        .row-flex {
            display: flex;
            gap: 15px;
        }

        .payment-btn {
            background-color: #9e5866;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 30px;
            font-size: 1.1em;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .payment-btn:hover {
            background-color: #7c3d4f;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        }

        .note {
            font-size: 0.9em;
            color: #777;
            margin-top: 15px;
            text-align: center;
        }
    </style>

    <div class="payment-container">
        <h2 class="payment-title">Enter Your Card Details</h2>

        <form method="POST" action="{{ route('checkout.card.process') }}">
            @csrf

            <label for="card_name" class="form-label">Cardholder Name</label>
            <input type="text" name="card_name" id="card_name" class="form-control" placeholder="e.g. Salmimah Binti Mohammed" required>

            <label for="card_number" class="form-label">Card Number</label>
            <input type="text" name="card_number" id="card_number" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19" required>

            <div class="row-flex">
                <div style="flex: 1;">
                    <label for="expiry" class="form-label">Expiry Date</label>
                    <input type="text" name="expiry" id="expiry" class="form-control" placeholder="MM/YY" required>
                </div>
                <div style="flex: 1;">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="password" name="cvv" id="cvv" class="form-control" maxlength="3" required>
                </div>
            </div>

            <button type="submit" class="payment-btn">Pay Now</button>
        </form>

        <p class="note">This is a demo. No real payment will be charged.</p>
    </div>
@endsection
