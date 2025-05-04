@extends('layouts.main')

@section('title', 'Thank You')

@section('content')
    <div class="container" style="max-width: 600px; margin: auto; text-align: center; padding: 40px;">
        <h2 style="color: #7c3d4f;">Thank You for Your Order!</h2>
        <p style="margin-top: 20px;">We have received your payment of <strong>RM{{ number_format($total, 2) }}</strong>.</p>
        <p>You will receive an email confirmation shortly</p>
        <p>Your order is being processed and will be shipped soon.</p>
        <a href="{{ route('home') }}" style="margin-top: 30px; display: inline-block; background-color: #9e5866; color: white; padding: 10px 25px; border-radius: 20px; text-decoration: none;">Back to Home</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Pesanan Berjaya!',
                text: 'Terima kasih! Pesanan anda telah diterima.',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endsection
