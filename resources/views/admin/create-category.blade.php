@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="add-product-container"> <!-- Class boleh reuse sebab styling sama -->
        <h2>Add New Category</h2>

        <!-- Form Add Category -->
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Category Name -->
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" name="category_name" id="category_name" required placeholder="Enter category name">
            </div>

            <!-- Description 
            <div class="form-group">
                <label for="category_description">Description</label>
                <textarea name="category_description" id="category_description" rows="4" placeholder="Optional description"></textarea>
            </div>

             Category Image 
            <div class="form-group">
                <label for="category_image">Category Image</label>
                <input type="file" name="category_image" id="category_image" accept="image/*">
            </div> -->

            <!-- Submit -->
            <button type="submit" class="btn-submit">Add</button>
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
