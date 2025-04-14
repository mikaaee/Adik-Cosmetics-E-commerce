<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ADIK COSMETICS</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;700&display=swap" rel="stylesheet">


    <!-- External CSS -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>

    {{-- Dynamic Header --}}
    @if (View::hasSection('header'))
        @yield('header')
    @else
        @include('partials.header-home')
    @endif


    <main>
        @if (Request::is('/') || Request::is('home'))
            {{-- Shared Hero Section --}}
            @include('partials.hero')
            {{-- Shared Featured Products --}}
            @include('partials.feature-products')
        @endif


        {{-- Page Content --}}
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2025 ADIK COSMETICS. All rights reserved.</p>
    </footer>

</body>

</html>
