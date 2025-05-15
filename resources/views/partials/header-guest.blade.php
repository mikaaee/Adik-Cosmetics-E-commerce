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
<style>
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
