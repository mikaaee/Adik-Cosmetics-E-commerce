<header>
    <div class="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logoac.png') }}" alt="ADIK COSMETICS HOUSE">
        </a>
    </div>

    <nav>
        <ul>
            <li><a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a></li>
            <form action="{{ route('search') }}" method="GET" class="search-form">
                <input type="text" name="query" placeholder="Search product..." required>
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>


            <!-- Dropdown Menu -->
            <li class="dropdown">
                <a href="#"></i> About <i class="fa-solid fa-caret-down"></i></a>
                <ul class="dropdown-content">
                    <li><a href="{{ route('user.profile') }}"><i class="fa-solid fa-id-card"></i> Profile</a></li>
                    <li><a href="{{ route('user.orderHistory') }}"><i class="fa-solid fa-box-archive"></i> Order
                            History</a></li>
                    <li><a href="{{ route('user.address') }}"><i class="fa-solid fa-map-marker-alt"></i> Address</a>
                    </li>
                    <li><a href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i>Logout</a></li>
                </ul>
            </li>
            <!-- Dropdown Products -->
            <li class="dropdown">
                <a href="#">Products <i class="fa-solid fa-caret-down"></i></a>
                <ul class="dropdown-content">
                    @foreach ($categories as $category)
                        <li><a href="{{ route('category.products', $category['id']) }}">{{ $category['name'] }}</a></li>
                    @endforeach
                </ul>
            </li>


            <!-- Cart Icon in header -->
            <div class="cart-icon">
                <a href="{{ route('cart.view') }}">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="cart-count">
                        {{ count(session('cart', [])) }} <!-- Jumlah item dalam cart -->
                    </span>
                </a>
            </div>



        </ul>
    </nav>
</header>
