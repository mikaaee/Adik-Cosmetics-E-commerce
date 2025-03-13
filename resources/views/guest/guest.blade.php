@extends('layouts.main')

@section('title', 'Welcome')

@section('header')
    @include('partials.header-guest')
@endsection

@section('content')
    {{-- Additional content khusus untuk guest page (optional) --}}
@endsection
