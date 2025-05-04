@extends('layouts.main')

@section('title', 'Welcome')

@section('header')
    @include('partials.header-guest')
@endsection

@section('hero')
    @include('partials.hero')
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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

        </section>

        <style>
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
                /* fixed 3 per row */
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
        </style>
        <script src="https://kit.fontawesome.com/your_kit_id.js" crossorigin="anonymous"></script>

    @endsection
