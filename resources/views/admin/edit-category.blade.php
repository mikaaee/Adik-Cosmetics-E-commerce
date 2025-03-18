@extends('layouts.admin')

@section('content')

<div class="add-product-container"> <!-- Reuse class supaya design sama -->
    <h2>Edit Category</h2>

    <!-- ✅ SUCCESS MESSAGE -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- ❌ ERROR VALIDATION MESSAGES -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Edit Category -->
    <form action="{{ route('admin.categories.update', $category['id']) }}" method="POST">
        @csrf
        @method('PUT') <!-- make sure PUT method untuk update -->

        <!-- Category Name -->
        <div class="form-group">
            <label for="category_name">Category Name</label>
            <input type="text" name="category_name" id="category_name" required
                value="{{ $category['category_name'] }}"
                placeholder="Enter category name">
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="category_description">Description</label>
            <textarea name="category_description" id="category_description" rows="4"
                placeholder="Optional description">{{ $category['category_description'] ?? '' }}</textarea>
        </div>

        <!-- Optional Category Image kalau nak enable
        <div class="form-group">
            <label for="category_image">Category Image</label>
            <input type="file" name="category_image" id="category_image" accept="image/*">
        </div> -->

        <!-- Actions -->
        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Update Category
            </button>
            <a href="{{ route('admin.categories') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

@endsection
