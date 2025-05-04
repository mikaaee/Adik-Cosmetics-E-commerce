@extends('layouts.admin')

@section('content')
    <div class="add-product-container"> <!-- Sama class supaya design seragam -->
        <h2>Edit Product</h2>

        <!-- Form Edit Product -->
        <form action="{{ route('admin.products.update', $product['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Product Name -->
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" id="name" required value="{{ old('name', $product['name']) }}"
                    placeholder="Enter product name">
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" required placeholder="Enter product description">{{ old('description', $product['description']) }}</textarea>
            </div>

            <!-- Price -->
            <div class="form-group">
                <label for="price">Price (RM)</label>
                <input type="number" step="0.01" name="price" id="price" required
                    value="{{ old('price', $product['price']) }}" placeholder="Enter product price">
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" required>
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category['category_name'] }}"
                            {{ $product['category'] == $category['category_name'] ? 'selected' : '' }}>
                            {{ $category['category_name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>

            <!-- Current Image Preview -->
            @if ($product['image_url'])
                <div class="form-group">
                    <label>Current Image</label><br>
                    <img src="{{ $product['image_url'] }}" alt="Current Image" width="120">
                </div>
            @endif

            <!-- Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>

        </form>
    </div>
    <!-- SweetAlert2 Script -->
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
