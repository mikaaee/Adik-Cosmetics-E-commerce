<header>
    <div class="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logoac.png') }}" alt="ADIK COSMETICS HOUSE">
        </a>
    </div>

    <nav class="main-nav">
        <ul>
            <li><a href="{{ route('guest.home') }}"><i class="fa-solid fa-house"></i></a></li>
            <li><a href="{{ route('login.form') }}">Login</a></li>
            <li><a href="{{ route('register.form') }}">Register</a></li>
            <form action="{{ route('search') }}" method="GET" class="search-form">
                <input type="text" name="query" placeholder="Search product..." required>
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </ul>
    </nav>
</header>
