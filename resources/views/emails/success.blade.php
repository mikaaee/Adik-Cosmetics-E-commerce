@extends('layouts.main')

@section('title', 'Order Success')

@section('content')
    <div class="container">
        <h2>Your order has been successfully placed!</h2>
        <p>A confirmation email has been sent to your email address.</p>
        <p>Thank you for shopping with us!</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Go to Homepage</a>
    </div>
@endsection
