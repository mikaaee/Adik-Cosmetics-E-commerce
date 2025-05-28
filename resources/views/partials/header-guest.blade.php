<header class="guest-header">
    <div class="guest-header-container">
        <!-- Logo -->
        <div class="logo">
            <a href="{{ route('guest.home') }}" aria-label="Home">
                <img src="{{ asset('images/logoacGREAT.png') }}" alt="ADIK COSMETICS HOUSE" class="logo-img">
            </a>
        </div>

        <!-- Search -->
        <div class="search-box">
            <form action="{{ route('search') }}" method="GET" class="search-form">
                <button type="submit" aria-label="Search">
                    <i class="fa fa-search"></i>
                </button>
                <input type="text" name="query" placeholder="Search product..." required class="search-input">
            </form>
        </div>

        <!-- Auth Links -->
        <div class="auth-buttons">
            <a href="{{ route('login.form') }}" class="auth-link">Login</a>
            <a href="{{ route('register.form') }}" class="auth-link primary">Register</a>
        </div>
    </div>
</header>


<style>
    :root {
        --primary: #f267a1;
        --primary-dark: #d6558c;
        --search-bg: #fdd9e6;
        --search-hover: #fbd1e2;
        --text-color: #333;
    }

    .guest-header {
        background: #fff;
        padding: 16px 5%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 0;
        z-index: 999;
    }

    .guest-header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1300px;
        margin: 0 auto;
        gap: 20px;
    }

    /* Logo */
    .logo-img {
        height: 60px;
        transition: transform 0.3s ease;
    }

    .logo:hover .logo-img {
        transform: scale(1.05);
    }

    /* Search */
    .search-box {
        flex-grow: 1;
        max-width: 400px;
    }

    .search-form {
        display: flex;
        align-items: center;
        background: var(--search-bg);
        padding: 8px 18px;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(242, 103, 161, 0.15);
        transition: all 0.3s ease;
    }

    .search-form:hover,
    .search-form:focus-within {
        background: var(--search-hover);
        box-shadow: 0 0 12px rgba(242, 103, 161, 0.25);
    }

    .search-form button {
        background: none;
        border: none;
        color: var(--primary);
        font-size: 1rem;
        cursor: pointer;
        padding: 0;
    }

    .search-input {
        border: none;
        background: transparent;
        padding: 6px 10px;
        width: 100%;
        font-size: 0.95rem;
        color: var(--text-color);
    }

    .search-input::placeholder {
        color: #666;
    }

    /* Auth Buttons */
    .auth-buttons {
        display: flex;
        gap: 10px;
    }

    .auth-link {
        padding: 8px 16px;
        border-radius: 20px;
        background: #fff;
        color: var(--text-color);
        border: 1px solid #ccc;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .auth-link:hover {
        background: #f5f5f5;
    }

    .auth-link.primary {
        background: var(--primary);
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(242, 103, 161, 0.2);
        border: none;
    }

    .auth-link.primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }
</style>

<script>
    // Logo parallax hover effect
    document.querySelector('.logo').addEventListener('mousemove', (e) => {
        const logo = e.currentTarget.querySelector('img');
        const x = e.clientX - e.currentTarget.getBoundingClientRect().left;
        const y = e.clientY - e.currentTarget.getBoundingClientRect().top;

        const moveX = (x - e.currentTarget.offsetWidth / 2) / 20;
        const moveY = (y - e.currentTarget.offsetHeight / 2) / 20;

        logo.style.transform = `rotateY(${moveX}deg) rotateX(${-moveY}deg) scale(1.05)`;
    });

    document.querySelector('.logo').addEventListener('mouseleave', (e) => {
        const logo = e.currentTarget.querySelector('img');
        logo.style.transform = '';
    });
</script>
