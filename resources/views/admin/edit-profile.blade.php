@extends('layouts.admin')

@section('content')
<div class="edit-profile-container">
    <h2>Edit Profile</h2>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.update-profile') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ $user['name'] ?? '' }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $user['email'] ?? '' }}" required>
        </div>

        <button type="submit">Update Profile</button>
    </form>
</div>
@endsection
