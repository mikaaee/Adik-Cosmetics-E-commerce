@extends('layouts.main')

@section('title', 'Payment Page')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
    <style>
        .payment-container {
            max-width: 800px;
            margin: 30px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .payment-title {
            color: #7c3d4f;
            font-size: 1.8em;
            margin-bottom: 25px;
            font-weight: 600;
            border-bottom: 2px solid #f3e9ec;
            padding-bottom: 10px;
        }

        .form-label {
            display: block;
            font-size: 0.95em;
            margin-bottom: 8px;
            color: #7c3d4f;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e8e8e8;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #9e5866;
            outline: none;
            box-shadow: 0 0 0 3px rgba(158, 88, 102, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%237c3d4f' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
        }

        .payment-btn {
            background-color: #9e5866;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .payment-btn:hover {
            background-color: #874554;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .payment-summary {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #f3e9ec;
        }

        .summary-title {
            color: #7c3d4f;
            font-size: 1.2em;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #5a5a5a;
        }

        .summary-total {
            font-weight: bold;
            font-size: 1.3em;
            color: #7c3d4f;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #ddd;
        }

        @media (max-width: 768px) {
            .payment-container {
                padding: 25px;
                margin: 15px;
            }
            
            .payment-title {
                font-size: 1.5em;
            }
        }

        /* Payment method icons */
        .payment-method-icon {
            width: 24px;
            height: 24px;
            margin-right: 10px;
            vertical-align: middle;
        }
    </style>

    <div class="payment-container">
        <h2 class="payment-title">Select Payment Method</h2>

        <form method="POST" action="{{ route('checkout.handlePayment') }}">
            @csrf

            <div class="form-group">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="" disabled selected>Select your payment method</option>
                    <option value="toyyibpay">ToyyibPay</option>
                    <option value="cod">Cash on Delivery (COD)</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="card">Credit / Debit Card</option>
                </select>
            </div>

            <button type="submit" class="payment-btn">Proceed to Payment</button>
        </form>

        <div class="payment-summary">
            <h3 class="summary-title">Order Summary</h3>
            
            <div class="summary-item">
                <span>Subtotal:</span>
                <span>RM{{ number_format($subtotal, 2) }}</span>
            </div>
            
            <div class="summary-item">
                <span>Shipping:</span>
                <span>RM{{ number_format($shipping_cost, 2) }}</span>
            </div>
            
            <div class="summary-item summary-total">
                <span>Total:</span>
                <span>RM{{ number_format($total, 2) }}</span>
            </div>
        </div>
    </div>
@endsection