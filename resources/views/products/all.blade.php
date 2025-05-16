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
                        <label>
                            <input type="checkbox" class="cat-filter" value="{{ $cat['name'] }}">
                            {{ $cat['name'] }}
                        </label>
                    </li>
                @endforeach
            </ul>
            <button id="reset-filter">Reset Filter</button>
        </aside>

        <!-- Main content with search + product grid -->
        <div class="product-main">
            <input type="text" id="searchInput" placeholder="Search product..." class="search-bar">
            <div class="product-list">
                @foreach ($products as $product)
                    <div class="product-card" data-cat="{{ $product['category'] }}" data-name="{{ strtolower($product['name']) }}">
                        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
                        <h4>{{ $product['name'] }}</h4>
                        <strong>RM{{ number_format($product['price'], 2) }}</strong>
                        <a href="{{ route('products.show', $product['id']) }}" class="btn-view-details">Buy&nbsp;Now</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

<style>
    .product-catalog {
        display: flex;
        gap: 20px;
        padding: 20px;
    }

    .filter-section {
        width: 220px;
        background-color: #f8e8e8;
        border-radius: 10px;
        padding: 20px;
        height: fit-content;
    }

    .filter-section h3 {
        margin-bottom: 10px;
        color: #7c3d4f;
    }

    .filter-section ul {
        list-style: none;
        padding-left: 0;
    }

    .filter-section li {
        margin-bottom: 10px;
    }

    #reset-filter {
        margin-top: 10px;
        padding: 6px 12px;
        background-color: #a87474;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background 0.2s;
    }

    #reset-filter:hover {
        background-color: #8f5c5c;
    }

    .product-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .search-bar {
        padding: 10px 15px;
        width: 100%;
        max-width: 400px;
        border: 1px solid #ccc;
        border-radius: 20px;
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .product-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .product-card {
        background: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        text-align: center;
        transition: transform 0.2s ease;
    }

    .product-card:hover {
        transform: translateY(-3px);
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
    const searchInput = document.getElementById('searchInput');

    function applyFilters() {
        const activeCats = [...checkboxes].filter(x => x.checked).map(x => x.value);
        const keyword = searchInput.value.trim().toLowerCase();

        cards.forEach(card => {
            const catMatch = activeCats.length === 0 || activeCats.includes(card.dataset.cat);
            const nameMatch = card.dataset.name.includes(keyword);
            const show = catMatch && nameMatch;
            card.style.display = show ? '' : 'none';
        });
    }

    checkboxes.forEach(cb => cb.addEventListener('change', applyFilters));
    searchInput.addEventListener('input', applyFilters);

    resetButton.addEventListener('click', () => {
        checkboxes.forEach(cb => cb.checked = false);
        searchInput.value = '';
        applyFilters();
    });
</script>
@endpush
