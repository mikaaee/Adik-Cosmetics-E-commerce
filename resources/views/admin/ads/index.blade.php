@extends('layouts.admin')

@section('title', 'Manage Ads')

@section('content')
    <div class="orders-page">
        <h2 style="margin-top: 20px;">Manage Ads</h2>

        <a href="{{ route('admin.ads.create') }}" class="btn btn-primary" style="margin-bottom: 20px;">
            <i class="fa fa-plus"></i> Add New Ad
        </a>

        @if (count($ads))
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Image Preview</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ads as $ad)
                        <tr>
                            <td>{{ $ad['title'] }}</td>
                            <td>
                                <img src="{{ $ad['image_url'] }}" alt="Ad Image" style="max-height: 60px;">
                            </td>
                            <td>
                                <form action="{{ route('admin.ads.destroy', $ad['id']) }}" method="POST"
                                    onsubmit="return confirm('Delete this ad?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No ads found.</p>
        @endif
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
        </script>
    @endif

@endsection
