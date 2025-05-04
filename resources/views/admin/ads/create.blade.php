@extends('layouts.admin')

@section('title', 'Create Ad')

@section('content')
<style>
    .create-ad-wrapper {
        max-width: 600px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        font-family: 'Gabarito', sans-serif;
    }

    .create-ad-wrapper h2 {
        font-size: 26px;
        margin-bottom: 25px;
        color: #000;
        text-align: center;
    }

    .form-label {
        font-weight: bold;
        margin-bottom: 5px;
        color: #343a40;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 15px;
        margin-bottom: 20px;
    }

    .btn-success {
        background-color: #000;
        border: none;
        padding: 10px 25px;
        font-size: 16px;
        border-radius: 6px;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-success:hover {
        background-color: #989898;
    }

    .image-preview {
        margin-top: 15px;
        text-align: center;
    }

    .image-preview img {
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-top: 10px;
    }
</style>

<div class="create-ad-wrapper">
    <h2>Create New Advertisement</h2>

    <form action="{{ route('admin.ads.store') }}" method="POST">
        @csrf

        <div>
            <label for="title" class="form-label">Ad Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div>
            <label for="image_url" class="form-label">Image URL</label>
            <input type="text" name="image_url" class="form-control" id="image_url" required>
        </div>

        <div class="image-preview" id="previewContainer" style="display:none;">
            <label>Preview:</label>
            <img id="imagePreview" src="" alt="Ad Preview">
        </div>

        <div style="text-align: right;">
            <button type="submit" class="btn btn-success">Save Advertisement</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('image_url').addEventListener('input', function () {
        const url = this.value;
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('previewContainer');

        if (url && url.startsWith('http')) {
            preview.src = url;
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    });
</script>
@endsection
