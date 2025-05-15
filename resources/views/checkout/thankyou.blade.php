@extends('layouts.main')

@section('title', 'Thank You')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
    <div class="thank-you-wrapper">
        <div class="thank-you-card">
            <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" alt="Success Icon" class="thank-you-icon">
            <h2>Thank You for Your Order!</h2>
            <p>We have received your payment of <strong>RM{{ number_format($total, 2) }}</strong>.</p>
            <p>You will receive an email confirmation shortly.</p>
            <p>Your order is being processed and will be shipped soon.</p>
            <a href="{{ route('home') }}" class="btn-home">Back to Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Order Success!',
                text: 'Thank You! Your order has been received.',
                confirmButtonText: 'OK'
            });
        });
    </script>

    <style>
        .thank-you-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
        }

        .thank-you-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .thank-you-icon {
            width: 80px;
            margin-bottom: 20px;
        }

        .thank-you-card h2 {
            color: #7c3d4f;
            margin-bottom: 15px;
        }

        .thank-you-card p {
            margin-bottom: 10px;
            font-size: 1rem;
            color: #444;
        }

        .btn-home {
            display: inline-block;
            margin-top: 20px;
            background-color: #9e5866;
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-home:hover {
            background-color: #7c3d4f;
        }
    </style>
@endsection
