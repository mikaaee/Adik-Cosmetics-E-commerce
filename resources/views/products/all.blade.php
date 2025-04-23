@extends('layouts.main')

@section('title', 'All Products')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
    <section class="product-catalog">
        <!-- Sidebar filter -->
        <aside class="filter-section">
            <h3>Filter by</h3>
            <ul>
                <li><strong>Products</strong></li>
                @foreach ($categories as $cat)
                    <li>
                        <input type="checkbox" class="cat-filter" value="{{ $cat['name'] }}">
                        {{ $cat['name'] }}
                    </li>
                @endforeach
            </ul>
        </aside>

        <!-- Product grid -->
        <div class="product-list">
            @foreach ($products as $product)
                <div class="product-card" data-cat="{{ $product['category'] }}">
                    <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
                    <h4>{{ $product['name'] }}</h4>
                    <strong>RM{{ number_format($product['price'], 2) }}</strong>

                    <!-- Single button: Buy Now → page details -->
                    <a href="{{ route('products.show', $product['id']) }}" class="btn-view-details">Buy&nbsp;Now</a>
                </div>
            @endforeach
        </div>
    </section>
@endsection

    <style>
        /* gaya khusus untuk butang Buy Now */
        .btn-view-details {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 14px;
            background: #c69c9c;
            /* warna utama */
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
        /* simple front‑end filter */
        document.querySelectorAll('.cat-filter').forEach(cb => {
            cb.addEventListener('change', () => {
                const active = [...document.querySelectorAll('.cat-filter:checked')].map(x => x.value);
                document.querySelectorAll('.product-card').forEach(card => {
                    const show = active.length === 0 || active.includes(card.dataset.cat);
                    card.style.display = show ? '' : 'none';
                });
            });
        });
    </script>
@endpush
