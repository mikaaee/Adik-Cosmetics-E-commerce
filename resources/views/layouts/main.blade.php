<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ADIK COSMETICS</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- External CSS -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

    {{-- Dynamic Header --}}
    @yield('header')

    {{-- Nav Role-Based --}}
    <nav>
        <ul>
            @if(session('user_data.role') === 'admin')
                <li><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                <li><a href="{{ route('logout') }}">Logout</a></li>
            @elseif(session('user_data.role') === 'user')
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('logout') }}">Logout</a></li>
            @endif
        </ul>
    </nav>

    <main>
        {{-- Shared Hero Section --}}
        @include('partials.hero')

        {{-- Shared Featured Products --}}
        @include('partials.feature-products')

        {{-- Page Content --}}
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2025 ADIK COSMETICS. All rights reserved.</p>
    </footer>

</body>
</html>
