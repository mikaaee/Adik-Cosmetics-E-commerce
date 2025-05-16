@extends('layouts.main')

@section('title', 'Product Details')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
    <style>
        .product-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            display: flex;
            gap: 40px;
        }

        .product-image {
            flex: 1;
            max-width: 500px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .product-image img {
            max-width: 100%;
            max-height: 500px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .product-image img:hover {
            transform: scale(1.02);
        }

        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 2.2rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
            line-height: 1.2;
        }

        .product-price {
            font-size: 1.8rem;
            color: #9e5866;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .product-description {
            font-size: 1.05rem;
            color: #555;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: #f0f0f0;
            color: #333;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background: #e0e0e0;
        }

        .quantity-input {
            width: 70px;
            height: 40px;
            text-align: center;
            margin: 0 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
        }

        .add-to-cart-btn {
            padding: 15px 30px;
            background: #9e5866;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            max-width: 250px;
        }

        .add-to-cart-btn:hover {
            background: #874554;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .add-to-cart-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 900px) {
            .product-container {
                flex-direction: column;
                gap: 30px;
                padding: 20px;
            }

            .product-image {
                max-width: 100%;
            }

            .product-title {
                font-size: 1.8rem;
            }

            .product-price {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .product-container {
                margin: 20px 10px;
                padding: 15px;
            }

            .quantity-control {
                margin-bottom: 20px;
            }

            .add-to-cart-btn {
                padding: 12px 20px;
                font-size: 1rem;
            }
        }
    </style>

    <div class="product-container">
        <div class="product-image">
            <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
        </div>

        <div class="product-info">
            <h1 class="product-title">{{ $product['name'] }}</h1>
            <p class="product-price">RM{{ number_format($product['price'], 2) }}</p>
            <p class="product-description">{{ $product['description'] }}</p>

            <div class="quantity-control">
                <button class="quantity-btn decrease">-</button>
                <input id="quantity" class="quantity-input" type="number" value="1" min="1">
                <button class="quantity-btn increase">+</button>
            </div>

            <form id="addToCartForm" action="{{ route('cart.add', $product['id']) }}" method="POST">
                @csrf
                <input type="hidden" name="name" value="{{ $product['name'] }}">
                <input type="hidden" name="price" value="{{ $product['price'] }}">
                <input type="hidden" name="image_url" value="{{ $product['image_url'] }}">
                <button type="submit" class="add-to-cart-btn">Add to Cart</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Quantity control
        document.querySelector('.decrease').addEventListener('click', () => {
            const qty = document.getElementById('quantity');
            if (qty.value > 1) qty.value--;
        });

        document.querySelector('.increase').addEventListener('click', () => {
            const qty = document.getElementById('quantity');
            qty.value++;
        });

        // Form submission with SweetAlert
        document.getElementById('addToCartForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Submit form normally
            this.submit();
            
            // Show success notification
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Added to cart',
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true,
                background: '#f8f9fa',
                iconColor: '#9e5866'
            });
        });
    </script>
@endsection