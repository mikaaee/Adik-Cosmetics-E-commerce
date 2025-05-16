@extends('layouts.main')

@section('title', 'Order Confirmation - COD')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
    <style>
        .confirmation-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .confirmation-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e8f5e9;
            border-radius: 50%;
            color: #4caf50;
            font-size: 40px;
        }

        .confirmation-title {
            color: #7c3d4f;
            font-size: 2em;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .confirmation-message {
            color: #555;
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .confirmation-highlight {
            color: #9e5866;
            font-weight: 600;
        }

        .confirmation-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            text-align: left;
        }

        .confirmation-cta {
            margin-top: 30px;
        }

        .btn-continue {
            background-color: #9e5866;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-continue:hover {
            background-color: #874554;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .confirmation-container {
                padding: 30px 20px;
                margin: 30px 15px;
            }
            
            .confirmation-title {
                font-size: 1.6em;
            }
            
            .confirmation-message {
                font-size: 1em;
            }
        }
    </style>

    <div class="confirmation-container">
        <div class="confirmation-icon">
            ✓
        </div>
        
        <h1 class="confirmation-title">Order Confirmed!</h1>
        
        <p class="confirmation-message">
            Your order has been successfully placed with <span class="confirmation-highlight">Cash on Delivery</span>.
        </p>
        
        <div class="confirmation-details">
            <p>✔ Our team will contact you shortly to confirm your order details</p>
            <p>✔ Please have the exact amount ready when your delivery arrives</p>
            <p>✔ Delivery time: Within 3-5 business days</p>
        </div>
        
        <p class="confirmation-message">
            Thank you for shopping with us!
        </p>
        
        <div class="confirmation-cta">
            <a href="{{ route('home') }}" class="btn-continue">Continue Shopping</a>
        </div>
    </div>
@endsection