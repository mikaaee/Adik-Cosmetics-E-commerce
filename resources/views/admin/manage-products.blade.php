@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="product-page">
        <h1 class="page-title">Manage Products</h1>
        <h2>All Products</h2>
        <form method="GET" action="{{ route('admin.products.index') }}" class="search-form"
            style="margin-bottom: 20px; position: relative; z-index: 10;">
            <!-- Search Field -->
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                class="search-input" style="width: 300px;">

            <!-- Category Filter -->
            <select name="category" class="form-select" style="width: 200px; margin-left: 10px; border-radius: 15px;">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat['category_name'] }}"
                        {{ request('category') == $cat['category_name'] ? 'selected' : '' }}>
                        {{ $cat['category_name'] }}
                    </option>
                @endforeach
            </select>

            <!-- Search Button -->
            <button type="submit" class="btn btn-primary" style="margin-left: 10px;">
                <i class="fas fa-search"></i> Search
            </button>
        </form>

        <div class="container">

            {{-- All Products List --}}
            <div class="all-products-section">
                <a href="{{ route('admin.products.create') }}" class="add-btn">
                    <i class="fas fa-plus"></i> Add </a>

                @if (count($products) > 0)
                    <div class="table-responsive">
                        <div class="table-wrapper">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Price (RM)</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $index => $product)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}"
                                                    class="table-product-image">
                                            </td>
                                            <td>{{ $product['name'] }}</td>
                                            <td>{{ $product['description'] }}</td>
                                            <td>{{ $product['category'] }}</td>
                                            <td>RM {{ number_format($product['price'], 2) }}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button
                                                        onclick="window.location.href='{{ route('admin.products.edit', $product['id']) }}'"
                                                        class="icon-btn edit-btn" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <form action="{{ route('admin.products.destroy', $product['id']) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                                            class="icon-btn delete-btn" title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <p class="no-product">No products found!</p>
                @endif
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops! Something went wrong.',
                html: `{!! implode('<br>', $errors->all()) !!}`,
            });
        </script>
    @endif
@endsection
