<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- External CSS -->
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>

    <div class="container">
        <div class="left">
            <h1>ADIK COSMETICS HOUSE</h1>
            <a href="{{ route('register.form') }}" style="text-decoration: none;">
                <h3>REGISTER</h3>
            </a>
            <a href="{{ route('login.form') }}" style="text-decoration: none;">
                <h3>LOGIN</h3>
            </a>

        </div>

        <div class="right">
            <div class="login-box">
                <p>REGISTER</p>

                @if (session('error'))
                    <p style="color:red;">{{ session('error') }}</p>
                @endif

                <form method="POST" action="{{ route('register') }}" style="width: 100%;">
                    @csrf

                    <div class="input-box">
                        <i class="fa fa-user"></i>
                        <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
                    </div>

                    <div class="input-box">
                        <i class="fa fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    </div>

                    <div class="input-box">
                        <i class="fa fa-phone"></i>
                        <input type="text" name="phone" placeholder="Phone Number" value="{{ old('phone') }}"
                            required>
                    </div>

                    <div class="input-box">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="input-box">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="login-btn">SIGN UP</button>
                    <div class="login-link">
                        Already have an account? <a href="{{ route('login.form') }}">Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
