@if (session('user_data'))
    <div class="welcome-message" id="welcomeMessage">
        👋 Welcome, {{ session('user_data')['first_name'] ?? 'User' }}!
    </div>
@endif

<header class="modern-header">
    <div class="header-content">
        <!-- Logo -->
        <div class="logo-container">
            <a href="{{ route('home') }}" class="logo-link">
                <img src="{{ asset('images/logoacGREAT.png') }}" alt="ADIK COSMETICS HOUSE" class="logo-image">
            </a>
        </div>

        <!-- Search -->
        <div class="search-container">
            <form action="{{ route('search') }}" method="GET" class="search-form">
                <input type="text" name="query" placeholder="Search products..." required class="search-input">
                <button type="submit" class="search-button">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Navigation -->
        <nav class="navigation">
            <ul class="nav-list">
                <li><a href="{{ route('about') }}" class="nav-link">About</a></li>
                <li class="dropdown">
                    <a href="#" class="nav-link">Products <i class="fa-solid fa-caret-down"></i></a>
                    <ul class="dropdown-content">
                        @foreach ($categories as $category)
                            <li><a href="{{ route('category.products', $category['id']) }}">{{ $category['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li><a href="{{ route('home') }}" class="nav-link"><i class="fa-solid fa-house"></i></a></li>
                <li class="dropdown">
                    <a href="#" class="nav-link"><i class="fa-solid fa-user"></i></a>
                    <ul class="dropdown-content">
                         <li><a href="{{ route('order.history') }}"><i class="fa-solid fa-box"></i> Order History</a></li>
                        <li><a href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                    </ul>
                </li>
                <li class="cart-item">
                    <a href="{{ route('cart.view') }}" class="nav-link cart-link">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="cart-badge">{{ count(session('cart', [])) }}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Quicksand&display=swap');

    body {
        font-family: 'Quicksand', sans-serif;
    }

    .welcome-message {
        position: fixed;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        background-color: #fff;
        color: #f267a1;
        padding: 10px 25px;
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

    .modern-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        min-height: 80px;
        padding: 10px 0;
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
        flex-wrap: wrap;
    }

    .logo-container {
        margin-right: 24px;
        flex-shrink: 0;
    }

    .logo-image {
        height: 65px;
        transition: transform 0.3s ease;
    }

    .logo-link:hover .logo-image {
        transform: scale(1.05);
    }

    .search-container {
        flex-shrink: 0;
        flex-grow: 0;
        width: auto !important;
        max-width: 300px !important;
        margin-left: 20px;
    }

    .search-form {
        display: flex;
        align-items: center;
        background: #fdd9e6;
        border-radius: 30px;
        padding: 6px 18px;
        box-shadow: 0 4px 10px rgba(242, 103, 161, 0.15);
        transition: all 0.3s ease;
    }

    .search-form:hover,
    .search-form:focus-within {
        background: #fbd1e2;
        box-shadow: 0 0 12px rgba(242, 103, 161, 0.25);
    }

    .search-input {
        border: none;
        background: transparent;
        padding: 6px 10px;
        width: 100%;
        max-width: 200px; 
        font-size: 14px;
        color: #000;
    }

    .search-input::placeholder {
        color: #555;
        opacity: 0.6;
        font-weight: 500;
    }

    .search-button {
        background: none;
        border: none;
        color: #f267a1;
        cursor: pointer;
        font-size: 16px;
    }

    .navigation {
        margin-left: auto;
    }

    .nav-list {
        display: flex;
        gap: 12px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-link {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 14px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 15px;
        transition: all 0.2s ease;
    }

    .nav-link:hover {
        color: #f267a1;
        background: rgba(242, 103, 161, 0.08);
    }

    .dropdown {
        position: relative;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #fff;
        min-width: 160px;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        border-radius: 4px;
        right: 0;
        z-index: 1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-content a {
        color: #000;
        padding: 10px 16px;
        display: block;
        text-decoration: none;
    }

    .dropdown-content a:hover {
        background-color: #f8f8f8;
    }

    .cart-link {
        position: relative;
    }

    .cart-badge {
        background: #f267a1;
        color: white;
        font-size: 11px;
        font-weight: bold;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: -6px;
        right: -6px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .logo-image {
            height: 54px;
        }

        .search-container {
            max-width: 220px;
        }

        .nav-link {
            padding: 6px 10px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .logo-image {
            height: 48px;
        }

        .search-container {
            max-width: 160px;
        }
    }
</style>

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

        const logo = document.querySelector('.logo-link');
        if (logo) {
            logo.addEventListener('mousemove', (e) => {
                const img = logo.querySelector('img');
                const x = e.clientX - logo.getBoundingClientRect().left;
                const y = e.clientY - logo.getBoundingClientRect().top;
                const moveX = (x - logo.offsetWidth / 2) / 15;
                const moveY = (y - logo.offsetHeight / 2) / 15;
                img.style.transform = `translate(${moveX}px, ${moveY}px) scale(1.05)`;
            });

            logo.addEventListener('mouseleave', () => {
                const img = logo.querySelector('img');
                img.style.transform = '';
            });
        }
    });
</script>
