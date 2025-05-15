@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <div class="product-management">
        <div class="management-header">
            <div class="header-content">
                <h1 class="page-title">Product Management</h1>
                <p class="page-subtitle">Manage your product inventory and promotions</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="add-product-btn">
                <i class="fas fa-plus-circle"></i> Add 
            </a>
        </div>

        <div class="filter-section">
            <form method="GET" action="{{ route('admin.products.index') }}" class="search-filter-form">
                <div class="search-container">
                    <div class="search-input-group">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search products..." class="search-input">
                        <button type="submit" class="search-btn">Search</button>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="filter-options">
                        <div class="promo-filter">
                            <span class="filter-label">Promo Status:</span>
                            <div class="promo-buttons">
                                <a href="{{ route('admin.products.index') }}" 
                                   class="promo-btn {{ request('promo') == null ? 'active' : '' }}">
                                    All Products
                                </a>
                                <a href="{{ route('admin.products.index', array_merge(request()->all(), ['promo' => 'true'])) }}" 
                                   class="promo-btn {{ request('promo') === 'true' ? 'active' : '' }}">
                                    <i class="fas fa-tag"></i> Promo Only
                                </a>
                                <a href="{{ route('admin.products.index', array_merge(request()->all(), ['promo' => 'false'])) }}" 
                                   class="promo-btn {{ request('promo') === 'false' ? 'active' : '' }}">
                                    Regular Only
                                </a>
                            </div>
                        </div>

                        <div class="category-filter">
                            <span class="filter-label">Category:</span>
                            <div class="select-wrapper">
                                <select name="category" class="category-select">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat['category_name'] }}"
                                            {{ request('category') == $cat['category_name'] ? 'selected' : '' }}>
                                            {{ $cat['category_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="product-list-container">
            @if (count($products) > 0)
                <div class="responsive-table">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th class="column-index">No.</th>
                                <th class="column-image">Image</th>
                                <th class="column-name">Product</th>
                                <th class="column-desc">Description</th>
                                <th class="column-category">Category</th>
                                <th class="column-price">Price</th>
                                <th class="column-status">Status</th>
                                <th class="column-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                                <tr>
                                    <td class="column-index">{{ $index + 1 }}</td>
                                    <td class="column-image">
                                        <div class="product-image-container">
                                            <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" 
                                                 class="product-image">
                                            @if (!empty($product['is_promo']) && $product['is_promo'])
                                                <div class="promo-flag">PROMO</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="column-name">
                                        {{ $product['name'] }}
                                    </td>
                                    <td class="column-desc">
                                        <div class="description-text">{{ Str::limit($product['description'], 50) }}</div>
                                    </td>
                                    <td class="column-category">
                                        <span class="category-badge">{{ $product['category'] }}</span>
                                    </td>
                                    <td class="column-price">
                                        RM {{ number_format($product['price'], 2) }}
                                    </td>
                                    <td class="column-status">
                                        @if (!empty($product['is_promo']) && $product['is_promo'])
                                            <span class="status-badge promo">Promo</span>
                                        @else
                                            <span class="status-badge normal">Normal</span>
                                        @endif
                                    </td>
                                    <td class="column-actions">
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.products.edit', $product['id']) }}" 
                                               class="action-btn edit-btn" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product['id']) }}" 
                                                  method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to delete this product?')"
                                                        class="action-btn delete-btn" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-products">
                    <i class="fas fa-box-open no-products-icon"></i>
                    <h3>No products found</h3>
                    <p>Try adjusting your search or filter criteria</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Base Styles */
        .product-management {
            padding: 2rem;
            font-family: 'Gabarito', Arial, Helvetica, sans-serif;
            color: #333;
        }

        /* Header Styles */
        .management-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header-content {
            flex: 1;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .page-subtitle {
            font-size: 1rem;
            color: #7f8c8d;
            margin: 0.5rem 0 0 0;
        }

        .add-product-btn {
            background: linear-gradient(135deg, #4d5268 0%, #000 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(135, 141, 163, 0.3);
        }

        .add-product-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(135, 141, 163, 0.3);
        }

        /* Filter Section Styles */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .search-filter-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .search-container {
            width: 100%;
        }

        .search-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            color: #7f8c8d;
            font-size: 1rem;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            height: 48px;
        }

        .search-input:focus {
            border-color: #4d5268;
            box-shadow: 0 0 0 3px rgba(135, 141, 163, 0.3);
            outline: none;
        }

        .search-btn {
            position: absolute;
            right: 0.5rem;
            background: linear-gradient(135deg, #4d5268 0%, #000 100%);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            cursor: pointer;
            height: calc(100% - 1rem);
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #4d5268 0%, #000 100%);
        }

        .filter-group {
            width: 100%;
        }

        .filter-options {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .promo-filter, .category-filter {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .promo-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .promo-btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            border: 1px solid #ddd;
            color: #333;
            background: white;
        }

        .promo-btn:hover {
            background: #f8f9fa;
            border-color: #ccc;
        }

        .promo-btn.active {
            background: #4d5268;
            color: white;
            border-color: #4d5268;
        }

        .category-filter {
            flex: 1;
            min-width: 200px;
        }

        .select-wrapper {
            position: relative;
        }

        .category-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            appearance: none;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            height: 48px;
        }

        .category-select:focus {
            border-color: #4d5268;
            box-shadow: 0 0 0 3px rgba(135, 141, 163, 0.3);
            outline: none;
        }

        .select-arrow {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #7f8c8d;
        }

        /* Table Styles */
        .product-list-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .responsive-table {
            overflow-x: auto;
        }

        .product-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .product-table th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            text-align: left;
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .product-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .product-table tr:last-child td {
            border-bottom: none;
        }

        .product-table tr:hover td {
            background: #f8f9fa;
        }

        /* Column Specific Styles */
        .column-image {
            width: 80px;
        }

        .product-image-container {
            position: relative;
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .promo-flag {
            position: absolute;
            top: 0;
            right: 0;
            background: #4d5268;
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.4rem;
            border-radius: 0 0 0 4px;
        }

        .column-name {
            font-weight: 600;
            color: #2c3e50;
            min-width: 150px;
        }

        .description-text {
            color: #7f8c8d;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .category-badge {
            background: #e8f4ff;
            color: #4d5268;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-badge {
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-badge.promo {
            background: #e0f7f0;
            color: #2ecc71;
        }

        .status-badge.normal {
            background: #f0f0f0;
            color: #7f8c8d;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            color: white;
        }

        .edit-btn {
            background: #4d5268;
        }

        .edit-btn:hover {
            background: #4d5268;
            transform: translateY(-2px);
        }

        .delete-btn {
            background: #e74c3c;
        }

        .delete-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        /* No Products State */
        .no-products {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
        }

        .no-products-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #ddd;
        }

        .no-products h3 {
            font-size: 1.5rem;
            margin: 0.5rem 0;
            color: #2c3e50;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .management-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .filter-options {
                flex-direction: column;
            }

            .search-input-group {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .search-btn {
                position: static;
                width: 100%;
                justify-content: center;
                height: auto;
                padding: 0.75rem;
            }

            .promo-buttons {
                flex-wrap: wrap;
            }
        }
    </style>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                background: 'white',
                backdrop: `
                    rgba(74, 108, 247, 0.1)
                    url("/images/confetti.gif")
                    left top
                    no-repeat
                `
            });
        </script>
    @endif

    @if ($errors->any()))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops! Something went wrong.',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                background: 'white',
                confirmButtonColor: '#4a6cf7'
            });
        </script>
    @endif
@endsection