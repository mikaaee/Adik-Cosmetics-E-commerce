@if (session('user_data'))
    <div class="welcome-message" id="welcomeMessage">
        👋 Welcome, {{ session('user_data')['first_name'] ?? 'User' }}!
    </div>
@endif

<header>
    <div class="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logoac.png') }}" alt="ADIK COSMETICS HOUSE">
        </a>
    </div>

    <nav class="main-nav">
        <ul>
            <li><a href="#">About</a></li>

            <!-- Dropdown Products -->
            <li class="dropdown">
                <a href="#">Products <i class="fa-solid fa-caret-down"></i></a>
                <ul class="dropdown-content">
                    @foreach ($categories as $category)
                        <li><a href="{{ route('category.products', $category['id']) }}">{{ $category['name'] }}</a></li>
                    @endforeach
                </ul>
            </li>

            <li><a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a></li>

            <!-- Dropdown User -->
            <li class="dropdown">
                <a href="#"><i class="fa-solid fa-user"></i></a>
                <ul class="dropdown-content">
                    <li><a href="{{ route('user.profile') }}"><i class="fa-solid fa-id-card"></i> Profile</a></li>
                    <li><a href="{{ route('user.orderHistory') }}"><i class="fa-solid fa-box-archive"></i> Order
                            History</a></li>
                    <li><a href="{{ route('user.address') }}"><i class="fa-solid fa-map-marker-alt"></i> Address</a>
                    </li>
                    <li><a href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                </ul>
            </li>

            <!-- Cart Icon -->
            <div class="cart-icon">
                <a href="{{ route('cart.view') }}">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="cart-count">{{ count(session('cart', [])) }}</span>
                </a>
            </div>

            <div class="right-group">
                <form action="{{ route('search') }}" method="GET" class="search-form">
                    <input type="text" name="query" placeholder="Search product..." required>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>

        </ul>
    </nav>
</header>

{{-- CSS --}}
<style>
    .welcome-message {
        position: fixed;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        background-color: #fff;
        color: #af8585;
        padding: 12px 25px;
        border-radius: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        font-weight: 500;
        font-size: 16px;
        z-index: 1000;
        animation: slideDown 0.4s ease-in-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translate(-50%, -20px);
        }

        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }

    .search-form {
        display: flex;
        align-items: center;
        background-color: white;
        border-radius: 30px;
        padding: 5px 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .search-form input[type="text"] {
        border: none;
        outline: none;
        padding: 8px 10px;
        font-size: 14px;
        border-radius: 30px;
        flex: 1;
    }

    .search-form button {
        background-color: #c69c9c;
        border: none;
        padding: 8px 12px;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        margin-left: 8px;
        transition: background-color 0.3s ease;
    }

    .search-form button:hover {
        background-color: #a17777;
    }
</style>

{{-- JS --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const welcome = document.getElementById('welcomeMessage');
        if (welcome) {
            setTimeout(() => {
                welcome.style.transition = 'opacity 0.5s ease';
                welcome.style.opacity = 0;
                setTimeout(() => welcome.remove(), 500);
            }, 3000);
        }
    });
</script>
