@extends('layouts.main')

@section('content')
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Gabarito', sans-serif;
        }

        body {
            background-color: #f5e6e6;
            color: #333;
        }

        /* Container tengah */
        .container {
            width: 80%;
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 36px;
            color: #c69c9c;
            margin-bottom: 30px;
            text-align: center;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #a17777;
            font-size: 16px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background-color: #fdf6f6;
        }

        input:focus {
            border-color: #c69c9c;
            outline: none;
            background-color: #fff;
        }

        .btn-primary {
            background-color: #c69c9c;
            color: #fff;
            padding: 12px 25px;
            border-radius: 30px;
            border: none;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #a17777;
            transform: scale(1.05);
        }

        /* Footer macam profile.blade.php */
        footer {
            background-color: #c69c9c;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
    </style>
    <div class="container">
        <h1>Edit Profile</h1>
        @if (session('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berjaya!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#c69c9c'
                });
            </script>
        @endif

        <form id="editForm" action="{{ route('user.update-profile') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ $user['name'] }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" value="{{ $user['phone'] }}" required>
            </div>

            <button type="submit" class="btn-primary" id="submitBtn">
                <span id="btnText">Update Profile</span>
                <span id="spinner" style="display:none;">
                    <i class="fas fa-spinner fa-spin"></i> Updating...
                </span>
            </button>
        </form>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastify CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Spinner, Toast & Form Handling -->
    <script>
        const form = document.getElementById('editForm');
        const btn = document.getElementById('submitBtn');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btnText');

        form.addEventListener('submit', function(e) {
            // Show spinner
            btn.disabled = true;
            btnText.style.display = 'none';
            spinner.style.display = 'inline-block';

            // Optional: Toast during update
            Toastify({
                text: "Updating yur profile...",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#c69c9c",
            }).showToast();
        });
    </script>
@endsection
