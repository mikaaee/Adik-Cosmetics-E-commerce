@extends('layouts.main')

@section('title', 'Home')

@section('header')
    @include('partials.header-home', ['categories' => $categories])
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @include('partials.chatbot', ['userName' => session('user_data')['first_name'] ?? 'there'])
    @if (session('user_data') && session('user_data')['first_name'])
        <div class="welcome-greeting" id="welcomeGreeting">
            👋 Welcome back, <strong>{{ ucfirst(session('user_data')['first_name']) }}</strong>!
        </div>
    @endif

    {{-- Hero Ads --}}
    @include('partials.hero', ['ads' => $ads])

    <section class="categories">
        <h2>Browse by Categories</h2>

        <div class="category-grid">
            @forelse($categories as $cat)
                <a href="{{ route('category.products', $cat['id']) }}" class="category-card">
                    @switch(strtolower($cat['name']))
                        @case('makeup')
                            <i class="fas fa-paint-brush"></i>
                        @break

                        @case('skincare')
                            <i class="fas fa-spa"></i>
                        @break

                        @case('fragrance')
                            <i class="fas fa-spray-can"></i>
                        @break

                        @case('haircare')
                            <i class="fas fa-cut"></i>
                        @break

                        @case('perfume')
                            <i class="fas fa-spray-can-sparkles"></i>
                        @break

                        @case('bodycare')
                            <i class="fas fa-hand-holding-heart"></i>
                        @break

                        @case('henna')
                            <i class="fas fa-hand-dots"></i>
                        @break

                        @default
                            <i class="fas fa-tag"></i>
                    @endswitch
                    <h3>{{ $cat['name'] }}</h3>
                </a>
                @empty
                    <p>No categories available.</p>
                @endforelse
            </div>
        @endsection
    </section>
    <style>
        /* Categories styling remains the same */
        .categories {
            padding: 40px 20px;
            text-align: center;
        }

        .categories h2 {
            font-size: 28px;
            margin-bottom: 30px;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            justify-items: center;
            padding: 0 20px;
        }

        .category-card {
            background: #fff;
            border-radius: 10px;
            padding: 25px 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            width: 100%;
            max-width: 220px;
            text-decoration: none;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .category-card i {
            font-size: 30px;
            margin-bottom: 10px;
            color: #c69c9c;
        }

        .category-card:hover {
            transform: translateY(-5px);
            background-color: #f9f9f9;
        }

        .category-card h3 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        @media (max-width: 992px) {
            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .category-grid {
                grid-template-columns: 1fr;
            }
        }

        .welcome-greeting {
            position: fixed;
            top: 90px;
            left: -100%;
            background: linear-gradient(to right, #fbd3e9, #bb377d);
            color: white;
            padding: 14px 24px;
            font-size: 16px;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            animation: slideInLeft 0.8s forwards;
            font-family: 'Segoe UI', sans-serif;
        }

        @keyframes slideInLeft {
            to {
                left: 20px;
            }
        }
    </style>

    @push('scripts')
        {{-- Meta untuk JS access --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="chatbox-route" content="{{ route('chatbox.ask') }}">

        {{-- External JS --}}
        <script src="{{ asset('js/chatbot.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const greeting = document.getElementById('welcomeGreeting');
                if (greeting) {
                    setTimeout(() => {
                        greeting.style.transition = 'all 0.5s ease';
                        greeting.style.opacity = '0';
                        greeting.style.transform = 'translateX(-100%)';
                    }, 5000); // 5 saat
                }
            });
        </script>
    @endpush
