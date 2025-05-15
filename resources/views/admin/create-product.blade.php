@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="add-product-container">
        <h2>Add New Product</h2>

        <!-- Form -->
        <form action="{{ route('admin.store-product') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Product Name -->
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" id="name" required placeholder="Enter product name">
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" required></textarea>
            </div>

            <!-- Price -->
            <div class="form-group">
                <label for="price">Price (RM)</label>
                <input type="number" step="0.01" name="price" id="price" required>
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category['category_name'] }}">{{ $category['category_name'] }}</option>
                    @endforeach
                </select>
            </div>


            <!-- Image -->
            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" name="image" id="image" accept="image/*" required>
            </div>

            <!-- Promo Checkbox -->
            <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" id="is_promo" name="is_promo" value="1"
                    {{ old('is_promo', $product['is_promo'] ?? false) ? 'checked' : '' }}
                    style="width: 18px; height: 18px; cursor: pointer;">
                <label for="is_promo" style="margin: 0; font-weight: 500; color: #2c3e50;">
                    Mark this product as a <strong>PROMOTION</strong> item
                </label>
            </div>


            <!-- Submit -->
            <button type="submit" class="btn-submit">Add</button>
        </form>
    </div>
    <style>
        input[type="checkbox"] {
            transform: scale(1.1);
            margin-right: 8px;
        }
    </style>
@endsection
