@extends('layouts.main')

@section('title', 'Products in ' . $category['name'])

@section('header')
    @include('partials.header-home')
@endsection

@section('content')

    <h2>Products in "{{ $category['name'] }}"</h2>

    <div class="product-grid">
        @forelse($products as $product)
            <div class="product-card">
                <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
                <h3>{{ $product['name'] }}</h3>
                <p>RM{{ number_format($product['price'], 2) }}</p>

                {{-- TERUS papar butang --}}
                <form action="{{ route('cart.add', $product['id']) }}" method="POST">
                    @csrf
                    <input type="hidden" name="name" value="{{ $product['name'] }}">
                    <input type="hidden" name="price" value="{{ $product['price'] }}">
                    <input type="hidden" name="image_url" value="{{ $product['image_url'] }}">
                    <button type="submit" class="add-to-cart-btn">
                        <i class="fa fa-cart-plus"></i> Add to Cart
                    </button>
                </form>
            </div>
        @empty
            <p>No products available in this category.</p>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succes!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
@endsection

