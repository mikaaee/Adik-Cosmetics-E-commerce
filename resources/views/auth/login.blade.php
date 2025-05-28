<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Adik Cosmetics</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Link to External CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="container">
        <!-- LEFT SIDE -->
        <div class="left">
            <img src="{{ asset('images/logoacGREAT.png') }}" alt="ADIK COSMETICS HOUSE">
            <a href="{{ route('register.form') }}" style="text-decoration: none;">
                <h3>REGISTER</h3>
            </a>
            <a href="{{ route('login.form') }}" style="text-decoration: none;">
                <h3>LOGIN</h3>
            </a>
        </div>

        <!-- RIGHT SIDE -->
        <div class="right">
            <div class="login-box">
                <p>LOGIN</p>

                {{-- Error Message --}}
                @if (session('error'))
                    <div class="error-message">{{ session('error') }}</div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" style="width: 100%;">
                    @csrf

                    <div class="input-box">
                        <i class="fa fa-user"></i>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    </div>

                    <div class="input-box">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>

                    <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>

                    <button type="submit" class="login-btn">LOGIN</button>
                </form>

                <div class="register-link">
                    New user? <a href="{{ route('register.form') }}">Register here</a>
                </div>
            </div>
        </div>

    </div>
    <style>
        /* Left section with logo + links */
        .left img {
            max-width: 70%;
            height: auto;
            display: block;
            margin: 0 auto 30px;
        }
    </style>

</body>

</html>
