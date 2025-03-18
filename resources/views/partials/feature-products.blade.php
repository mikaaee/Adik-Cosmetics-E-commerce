<section class="products">
    <h2>Our Featured Products</h2>
    
    <div class="product-grid">
        @forelse($productList as $product)
            <div class="product-card">
                <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
                <h3>{{ $product['name'] }}</h3>
                <p>RM{{ number_format($product['price'], 2) }}</p>
                <button class="add-to-cart-btn">
                    <i class="fa fa-cart-plus"></i> Add to Cart
                </button>
            </div>
        @empty
            <p>No products available.</p>
        @endforelse
    </div>
</section>
