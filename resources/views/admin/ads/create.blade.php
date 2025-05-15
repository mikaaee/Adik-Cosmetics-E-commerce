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
        background-color: #000;
        color: #fff;
        padding: 10px 25px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-submit:hover {
        background-color: #444;
    }
</style>

<div class="create-ad-wrapper">
    <h2>Create New Advertisement</h2>

    <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label class="form-label">Ad Title:</label>
        <input type="text" name="title" class="form-control" required>

        <!--<label class="form-label">Upload Image (optional):</label>
        <input type="file" name="image_file" class="form-control" accept="image/*">!-->

        <label class="form-label">Paste Image URL:</label>
        <input type="text" name="image_url" class="form-control">

        <small>this is for testing purpose, will upgrade later</small><br><br>

        <button type="submit" class="btn-submit">SAVE</button>
    </form>
</div>
@endsection
