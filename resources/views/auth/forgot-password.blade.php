<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- External CSS -->
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <div class="container">
        <!-- LEFT SIDE -->
        <div class="left">
            <img src="{{ asset('images/logoac.png') }}" alt="ADIK COSMETICS HOUSE">
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
                <p>FORGOT PASSWORD</p>

                @if (session('status'))
                    <p style="color: green;">{{ session('status') }}</p>
                @endif

                @if ($errors->any())
                    <div style="color: red;">
                        <ul style="padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" style="width: 100%;">
                    @csrf

                    <div class="input-box">
                        <i class="fa fa-envelope"></i>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>

                    <button type="submit" class="login-btn">Send Reset Link</button>

                    <div class="login-link">
                        Remember your password? <a href="{{ route('login.form') }}">Login here</a>
                    </div>
                </form>
                
                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: '{{ session('success') }}',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '{{ session('error') }}',
                        });
                    </script>
                @endif

            </div>
        </div>
    </div>
</body>

</html>
