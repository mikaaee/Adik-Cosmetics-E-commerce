<!DOCTYPE html>
<html>
<head>
    <title>Debug Ad Submit</title>
</head>
<body>
    <h2>Test Ad Submission (Image Upload & URL)</h2>

    <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Ad Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Upload Image (optional):</label><br>
        <input type="file" name="image_file" accept="image/*"><br><br>

        <label>Or Paste Image URL (optional):</label><br>
        <input type="text" name="image_url"><br><br>

        <small>⚠️ If both are filled, uploaded image will be used.</small><br><br>

        <button type="submit">SUBMIT</button>
    </form>
</body>
</html>
