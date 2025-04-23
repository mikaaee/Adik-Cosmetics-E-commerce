{{-- search/results.blade.php --}}
@extends('layouts.main')

@section('title', 'Search Results')

@section('content')
    <h2>Search Results for "{{ $query }}"</h2>

    <div class="product-grid">
        @forelse($filteredProducts as $product)
            <div class="product-card">
                <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
                <h3>{{ $product['name'] }}</h3>
                <p>RM {{ number_format($product['price'], 2) }}</p>
            </div>
        @empty
            <p>No products found for "{{ $query }}".</p>
        @endforelse
    </div>
@endsection
