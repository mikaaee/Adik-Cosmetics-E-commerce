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

        /* Container untuk Profil */
        .container {
            width: 80%;
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }

        h1 {
            font-size: 36px;
            color: #c69c9c;
            margin-bottom: 30px;
            text-align: center;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* Button Edit Profile */
        .btn {
            background-color: #c69c9c;
            color: #fff;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            display: inline-block;
            transition: all 0.3s ease-in-out;
        }

        .btn:hover {
            background-color: #a17777;
            transform: scale(1.05);
        }

        /* Footer */
        footer {
            background-color: #c69c9c;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }

        /* Optional: Styling for header if required */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #c69c9c;
            padding: 20px 50px;
        }

        header .logo {
            display: flex;
            align-items: center;
        }

        header .logo img {
            height: 70px;
            width: auto;
            margin-right: 10px;
        }

        header nav ul {
            display: flex;
            list-style: none;
        }

        header nav ul li {
            margin-left: 30px;
        }

        header nav ul li a {
            color: #fff;
            text-decoration: none;
            word-spacing: 20px;
            font-size: 16px;
            transition: color 0.3s;
        }

        header nav ul li a:hover {
            color: #000;
        }
    </style>

    <div class="container">
        <h1>Your Profile</h1>
        <p>Name: {{ $user['name'] }}</p>
        <p>Email: {{ $user['email'] }}</p>
        <p>Phone: {{ $user['phone'] }}</p>

        <a href="{{ route('user.edit-profile') }}" class="btn btn-primary">Edit Profile</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#c69c9c'
            });
        </script>
    @endif
@endsection
