<header>
    <div class="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logoac.png') }}" alt="ADIK COSMETICS HOUSE">
        </a>
    </div>

    <nav>
        <ul>
            <li><a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a></li>
            <li><a href="#"><i class="fa-solid fa-magnifying-glass"></i></a></li>
            <li><a href="#">About</a></li>
            <li><a href="{{ route('logout') }}">Logout</a></li>
        </ul>
    </nav>
</header>
