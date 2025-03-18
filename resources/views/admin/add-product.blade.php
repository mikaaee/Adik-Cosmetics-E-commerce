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

            <!-- Submit -->
            <button type="submit" class="btn-submit">Add Product</button>
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
            })
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
