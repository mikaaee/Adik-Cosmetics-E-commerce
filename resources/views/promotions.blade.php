@extends('layouts.main')

@section('title', 'Promotions')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
    <section class="product-catalog" style="display: flex; gap: 20px;">
        <!-- Sidebar filter -->
        <aside class="filter-section">
            <h3>Filter by</h3>
            <ul>
                <li><strong>Products</strong></li>
                @foreach ($categories as $cat)
                    <li>
                        <label>
                            <input type="checkbox" class="cat-filter" value="{{ $cat['name'] }}">
                            {{ $cat['name'] }}
                        </label>
                    </li>
                @endforeach
            </ul>
            <button id="reset-filter">Reset Filter</button>
        </aside>

        <!-- Product grid -->
        <div class="product-list">
            @foreach ($products as $product)
                <div class="product-card" data-cat="{{ $product['category'] }}">
                    <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
                    <h4>{{ $product['name'] }}</h4>
                    <strong>RM{{ number_format($product['price'], 2) }}</strong>
                    <a href="{{ route('products.show', $product['id']) }}" class="btn-view-details">Buy&nbsp;Now</a>
                </div>
            @endforeach
        </div>
    </section>
@endsection

<style>
    .filter-section {
        padding: 20px;
        background-color: #f8e8e8;
        border-radius: 10px;
        width: 200px;
        height: fit-content;
    }

    .filter-section ul {
        list-style: none;
        padding-left: 0;
    }

    .filter-section li {
        margin-bottom: 8px;
    }

    #reset-filter {
        margin-top: 10px;
        padding: 6px 12px;
        border: none;
        background: #a87474;
        color: white;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
    }

    #reset-filter:hover {
        background: #925b5b;
    }

    .product-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        flex: 1;
    }

    .product-card {
        padding: 15px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .product-card img {
        width: 100%;
        height: 160px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    .btn-view-details {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 14px;
        background: #c69c9c;
        color: #fff;
        text-decoration: none;
        border-radius: 15px;
        font-weight: 600;
        transition: background .25s, transform .2s;
    }

    .btn-view-details:hover {
        background: #a87474;
        transform: translateY(-2px);
    }
</style>

@push('scripts')
    <script>
        const checkboxes = document.querySelectorAll('.cat-filter');
        const cards = document.querySelectorAll('.product-card');
        const resetButton = document.getElementById('reset-filter');

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const active = [...checkboxes].filter(x => x.checked).map(x => x.value);
                cards.forEach(card => {
                    const show = active.length === 0 || active.includes(card.dataset.cat);
                    card.style.display = show ? '' : 'none';
                });
            });
        });

        resetButton.addEventListener('click', () => {
            checkboxes.forEach(cb => cb.checked = false);
            cards.forEach(card => card.style.display = '');
        });
    </script>
@endpush
