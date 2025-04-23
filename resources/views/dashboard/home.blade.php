@extends('layouts.main')

@section('title', 'Home')

@include('partials.header-home', ['categories' => $categories])
@section('content')


    {{-- Additional content khusus untuk home user --}}
    <section class="categories">
        <h2>Browse by Categories</h2>

        <div class="category-grid">
            @forelse($categories as $cat)
                <a href="{{ route('category.products', $cat['id']) }}" class="category-card">
                    <h3>{{ $cat['name'] }}</h3>
                </a>
            @empty
                <p>No categories available.</p>
            @endforelse
        </div>
    </section>

@endsection
