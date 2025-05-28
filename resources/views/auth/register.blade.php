<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- External CSS -->
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="container">
        <div class="left">
            <img src="{{ asset('images/logoacGREAT.png') }}" alt="ADIK COSMETICS HOUSE">
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
                    <div class="form-grid">
                        <div class="input-box">
                            <i class="fa fa-user"></i>
                            <input type="text" name="first_name" placeholder="First Name"
                                value="{{ old('first_name') }}" required>
                        </div>
                        <div class="input-box">
                            <i class="fa fa-user"></i>
                            <input type="text" name="last_name" placeholder="Last Name"
                                value="{{ old('last_name') }}" required>
                        </div>
                        <div class="input-box">
                            <i class="fa fa-envelope"></i>
                            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                                required>
                        </div>
                        <div class="input-box">
                            <i class="fa fa-phone"></i>
                            <input type="text" name="phone" placeholder="Phone Number" value="{{ old('phone') }}"
                                required>
                        </div>
                        <div class="input-box">
                            <i class="fa fa-home"></i>
                            <input type="text" name="address" placeholder="Address" value="{{ old('address') }}"
                                required>
                        </div>
                        <div class="input-box">
                            <i class="fa fa-city"></i>
                            <input type="text" name="city" placeholder="City" value="{{ old('city') }}"
                                required>
                        </div>
                        <div class="input-box">
                            <i class="fa fa-envelope"></i>
                            <input type="text" name="postcode" placeholder="Postcode" value="{{ old('postcode') }}"
                                required>
                        </div>
                        <div class="input-box">
                            <i class="fa fa-globe"></i>
                            <input type="text" name="country" placeholder="Country" value="{{ old('country') }}"
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
                    </div>
                    <button type="submit" class="login-btn">SIGN UP</button>
                    <div class="login-link">
                        Already have an account? <a href="{{ route('login.form') }}">Login here</a>
                    </div>
                </form>
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

        /* === FORM GRID === */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 14px;
            margin-bottom: 14px;
        }

        /* === INPUT BOX === */
        .input-box {
            position: relative;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            padding: 0;
            transition: 0.3s ease;
        }

        .input-box i {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: #c69c9c;
            font-size: 14px;
        }

        .input-box input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            background: transparent;
            color: #333;
        }

        .input-box input::placeholder {
            color: #888;
            font-weight: 500;
        }

        .input-box input:focus {
            outline: none;
            background-color: #fdfdfd;
            box-shadow: 0 0 0 2px #c96c9c33;
        }

        /* === RESPONSIVE: 1 column form on small screen === */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Registration Success!',
                text: '{{ session('success') }}',
                showConfirmButton: true
            });
        </script>
    @endif

</body>

</html>
