@extends('layouts.admin')

@section('title', 'Create Ad')

@section('content')
    <style>
        .create-ad-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            font-family: 'Gabarito', sans-serif;
        }

        .form-label {
            font-weight: bold;
            color: #343a40;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            margin-bottom: 20px;
        }

        .btn-submit {
            background-color: #c96c9c;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(201, 108, 156, 0.3);
        }

        .btn-submit:hover {
            background-color: #b45784;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(201, 108, 156, 0.4);
        }
        .preview-img {
            max-height: 80px;
            margin-top: 10px;
            display: none;
            border-radius: 6px;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="create-ad-wrapper">
        <h2>Create New Advertisement</h2>

        <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label class="form-label">Ad Title:</label>
            <input type="text" name="title" class="form-control" required>

            <label class="form-label">Select Image Type:</label>
            <div style="margin-bottom: 15px;">
                <label><input type="radio" name="image_type" value="url" checked onchange="toggleImageInput()"> Use
                    Image URL</label><br>
                <label><input type="radio" name="image_type" value="file" onchange="toggleImageInput()"> Upload Image
                    File</label>
            </div>

            <div id="urlInput">
                <label class="form-label">Paste Image URL:</label>
                <input type="text" name="image_url" class="form-control">
            </div>

            <div id="fileInput" style="display: none;">
                <label class="form-label">Upload Image:</label>
                <input type="file" name="image_file" class="form-control" accept="image/jpeg,image/png,image/jpg"
                    onchange="previewSelectedImage(this)">
                <small style="color: #6c757d;">Only JPEG, JPG, or PNG files under 2MB are allowed.</small><br>
                <img id="previewImage" src="#" alt="Image Preview" class="preview-img">
            </div>

            <button type="submit" class="btn-submit">SAVE</button>
        </form>
    </div>

    <script>
        function toggleImageInput() {
            const type = document.querySelector('input[name="image_type"]:checked').value;
            document.getElementById('urlInput').style.display = (type === 'url') ? 'block' : 'none';
            document.getElementById('fileInput').style.display = (type === 'file') ? 'block' : 'none';
            document.getElementById('previewImage').style.display = 'none';
        }

        function previewSelectedImage(input) {
            const preview = document.getElementById('previewImage');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }

        window.onload = toggleImageInput;
    </script>
@endsection
