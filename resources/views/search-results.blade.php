@extends('layouts.main') {{-- Kalau kamu guna layout, sesuaikan ikut layout kamu --}}
@section('content')

<section class="products">
    <h2>Hasil Carian: "{{ request('query') }}"</h2>

    <div class="product-grid">
        @forelse($products as $product)
            <div class="product-card">
                <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
                <h3>{{ $product['name'] }}</h3>
                <p>RM{{ number_format($product['price'], 2) }}</p>
                <button class="add-to-cart-btn">
                    <i class="fa fa-cart-plus"></i> Add to Cart
                </button>
            </div>
        @empty
            <p>Tiada produk dijumpai untuk carian tersebut.</p>
        @endforelse
    </div>
</section>

@endsection
