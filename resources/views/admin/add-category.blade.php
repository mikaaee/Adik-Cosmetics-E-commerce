@extends('layouts.admin') <!-- kalau kau ada layout, guna ni -->

@section('content')

    <div class="add-product-container"> <!-- Class boleh reuse sebab styling sama -->
        <h2>Add New Category</h2>

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

        <!-- Form Add Category -->
        <form action="{{ route('admin.store-category') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Category Name -->
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" name="category_name" id="category_name" required placeholder="Enter category name">
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="category_description">Description</label>
                <textarea name="category_description" id="category_description" rows="4" placeholder="Optional description"></textarea>
            </div>

            <!-- Category Image 
            <div class="form-group">
                <label for="category_image">Category Image</label>
                <input type="file" name="category_image" id="category_image" accept="image/*">
            </div> -->

            <!-- Submit -->
            <button type="submit" class="btn-submit">Add Category</button>
        </form>
    </div>

@endsection
