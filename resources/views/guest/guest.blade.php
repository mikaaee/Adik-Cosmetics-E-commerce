@extends('layouts.main')

@section('title', 'Welcome')

@section('header')
    @include('partials.header-guest')
@endsection

@section('content')
    {{-- Additional content khusus untuk guest page (optional) --}}
    {{-- Category Section --}}
    <section class="categories">
        <h2>Browse by Categories</h2>

        <div class="category-grid">
            @forelse($categories as $cat)
                <a href="{{ route('category.guest-products', $cat['id']) }}" class="category-card">
                    <h3>{{ $cat['name'] }}</h3>
                </a>
            @empty
                <p>No categories available.</p>
            @endforelse
        </div>
    </section>
@endsection

