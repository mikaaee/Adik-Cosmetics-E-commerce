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
                                <a href="{{ route('admin.ads.edit', $ad['id']) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> 
                                </a>

                                <form action="{{ route('admin.ads.destroy', $ad['id']) }}" method="POST"
                                    style="display: inline-block;" onsubmit="return confirm('Delete this ad?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">
                                        <i class="fas fa-trash"></i> 
                                    </button>
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
    <style>
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .btn-edit {
            background-color: #f5d2e0;
            color: #b45784;
        }

        .btn-edit:hover {
            background-color: #e3b7cd;
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(180, 87, 132, 0.2);
        }

        .btn-delete {
            background-color: #ffe2e2;
            color: #cc4b4b;
        }

        .btn-delete:hover {
            background-color: #f8bfbf;
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(204, 75, 75, 0.2);
        }
    </style>
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
