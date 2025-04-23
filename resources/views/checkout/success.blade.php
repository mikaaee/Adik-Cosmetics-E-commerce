@extends('layouts.main')

@section('title', 'Order Success')

@section('content')
<section class="order-success">
    <h1>Your order has been placed successfully!</h1>
    <p>Thank you for shopping with us. You will receive an email confirmation shortly.</p>
    <a href="{{ route('home') }}">Back to Home</a>
</section>
@endsection
