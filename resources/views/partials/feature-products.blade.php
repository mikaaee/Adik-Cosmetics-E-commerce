@extends('layouts.main') {{-- atau layout yang kau guna --}}

@section('content')

<style>
    /* Category Section Styling */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1.5rem;
        margin-top: 20px;
    }

    .category-card {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f7f7f7;
        padding: 30px 20px;
        border-radius: 12px;
        text-decoration: none;
        color: #333;
        font-weight: bold;
        font-size: 1.1rem;
        text-align: center;
        transition: 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .category-card:hover {
        background-color: #e5e5e5;
        transform: translateY(-3px);
    }

    /* Products Grid Styling */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-top: 30px;
    }

    .product-card {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        text-align: center;
    }

    .product-card img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .product-card h3 {
        margin: 10px 0;
        font-size: 1.2rem;
    }

    .product-card p {
        color: #555;
        margin-bottom: 10px;
    }

    .add-to-cart-btn {
        background-color: #000;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s ease;
    }

    .add-to-cart-btn:hover {
        background-color: #333;
    }
</style>

<!-- Category Section -->
<section class="categories">
    <h2>Browse by Categories</h2>

    <div class="category-grid">
        @forelse($allCategories as $cat)
            <a href="{{ route('category.products', $cat->id) }}" class="category-card">
                <h3>{{ $cat->name }}</h3>
            </a>
        @empty
            <p>No categories available.</p>
        @endforelse
    </div>
</section>

<!-- Product Section -->
<section class="products">
    <h2>
        @if(isset($category))
            Products in "{{ $category->name }}"
        @else
            Our Featured Products
        @endif
    </h2>

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
            <p>No products available{{ isset($category) ? ' in this category' : '' }}.</p>
        @endforelse
    </div>
</section>

@endsection
