@extends('layouts.main')

@section('title', 'Products in ' . $category['name'])

@section('header')
    @include('partials.header-guest')
@endsection

@section('content')


    <h2>Products in "{{ $category['name'] }}"</h2>

    <div class="product-grid">
        @forelse($products as $product)
            <div class="product-card">
                <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
                <h3>{{ $product['name'] }}</h3>
                <p>RM{{ number_format($product['price'], 2) }}</p>
                <button class="add-to-cart-btn" onclick="showLoginAlert()">
                    <i class="fa fa-cart-plus"></i> Add to Cart
                </button>                
            </div>
        @empty
            <p>No products available in this category.</p>
        @endforelse
    </div>

    {{-- SweetAlert Script --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showLoginAlert() {
            Swal.fire({
                icon: 'info',
                title: 'Login Required',
                text: 'You need to login or register to add items to your cart!',
                showCancelButton: true,
                confirmButtonText: 'Login',
                cancelButtonText: 'Register',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = "{{ route('register') }}";
                }
            });
        }
    </script>
@endsection
