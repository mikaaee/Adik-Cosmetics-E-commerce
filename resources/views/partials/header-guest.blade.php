<header>
    <div class="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logoac.png') }}" alt="ADIK COSMETICS HOUSE">
        </a>
    </div>

    <nav>
        <ul>
            <li><a href="{{ route('guest.home') }}"><i class="fa-solid fa-house"></i></a></li>
            <li><a href="{{ route('login.form') }}">Login</a></li>
            <li><a href="{{ route('register.form') }}">Register</a></li>
        </ul>
    </nav>
</header>
