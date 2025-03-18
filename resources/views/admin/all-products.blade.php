@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="product-page">
    <div class="container">
        <h1 class="page-title">All Products</h1>

        {{-- Success alert --}}
        @if (session('success'))
            <div class="alert-success" id="success-alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error alert --}}
        @if (session('error'))
            <div class="alert-danger" id="error-alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Table --}}
        @if (count($products) > 0)
            <div class="table-responsive">
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
                                    <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" class="table-product-image">
                                </td>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['description'] }}</td>
                                <td>{{ $product['category'] }}</td>
                                <td>RM {{ number_format($product['price'], 2) }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <!-- Edit Button -->
                                        <button onclick="window.location.href='{{ route('admin.products.edit', $product['id']) }}'" class="icon-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    
                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.products.destroy', $product['id']) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')" class="icon-btn delete-btn" title="Delete">
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
        @else
            <p class="no-product">No products found!</p>
        @endif
    </div>
</div>

<script>
    setTimeout(function() {
        let successAlert = document.getElementById('success-alert');
        let errorAlert = document.getElementById('error-alert');

        if (successAlert) {
            successAlert.style.display = 'none';
        }
        if (errorAlert) {
            errorAlert.style.display = 'none';
        }
    }, 3000); // 3 seconds
</script>

@endsection
