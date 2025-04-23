@extends('layouts.main')

@section('title', 'Product Details')

@section('header')
    @include('partials.header-home') <!-- Include header, pastikan layout ada -->
@endsection

@section('content')
    <section class="product-details">
        <!-- Produk Image -->
        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">

        <div class="details">
            <h1>{{ $product['name'] }}</h1>
            <p class="price">RM{{ number_format($product['price'], 2) }}</p>
            <p>{{ $product['description'] }}</p>

            <!-- Quantity selector -->
            <div class="quantity">
                <button class="decrease">-</button>
                <input id="quantity" type="number" value="1" min="1">
                <button class="increase">+</button>
            </div>

            <!-- Add to Cart Button -->
            <form id="addToCartForm" action="{{ route('cart.add', $product['id']) }}" method="POST">
                @csrf
                <input type="hidden" name="name" value="{{ $product['name'] }}">
                <input type="hidden" name="price" value="{{ $product['price'] }}">
                <input type="hidden" name="image_url" value="{{ $product['image_url'] }}">
                <button type="submit" class="add-to-cart">Add to Cart</button>
            </form>
        </div>
    </section>
@endsection

<style>
    /* Product Details Section */
    .product-details {
        display: flex;
        padding: 20px;
        background-color: white;
        margin: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .product-details img {
        max-width: 300px;
        margin-right: 20px;
        border-radius: 8px;
    }

    .product-details .details {
        max-width: 500px;
    }

    .product-details .details h1 {
        font-size: 28px;
        color: #333;
        margin-bottom: 10px;
    }

    .product-details .details .price {
        font-size: 24px;
        color: #c69c9c;
        margin-bottom: 15px;
    }

    .product-details .details p {
        font-size: 16px;
        color: #555;
        margin-bottom: 20px;
    }

    /* Quantity and Add to Cart Section */
    .product-details .quantity {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .product-details .quantity button {
        padding: 8px 15px;
        border: none;
        background-color: #c69c9c;
        color: white;
        cursor: pointer;
        border-radius: 15px;
    }

    .product-details .quantity input {
        width: 60px;
        padding: 5px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 15px;
    }

    .product-details .add-to-cart {
        padding: 12px 20px;
        background-color: #c69c9c;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 15px;
        cursor: pointer;
    }

    .product-details .add-to-cart:hover {
        background-color: #a87474;
    }
</style>




<!-- SweetAlert & kuantiti script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // kuantiti
    const dec = document.querySelector('.decrease'),
        inc = document.querySelector('.increase'),
        qty = document.getElementById('quantity');
    dec.onclick = () => {
        if (qty.value > 1) qty.value--;
    };
    inc.onclick = () => {
        qty.value++;
    };

   

const form = document.getElementById('addToCartForm');
form.addEventListener('submit', e => {
    e.preventDefault();

    // hantar form secara normal
    form.submit();

    // papar toast segera
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Added to cart',
        showConfirmButton: false,
        timer: 1800,
        timerProgressBar: true
    });
});
</script>


