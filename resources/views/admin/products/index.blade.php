@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="product-page">
        <div class="container">
            <h1 class="page-title">All Products</h1>

            {{-- Table --}}
            @if (count($products) > 0)
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Price (RM)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}"
                                            class="table-product-image">
                                    </td>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['description'] }}</td>
                                    <td>{{ $product['category'] }}</td>
                                    <td>RM {{ number_format($product['price'], 2) }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Edit Button -->
                                            <button
                                                onclick="window.location.href='{{ route('admin.products.edit', $product['id']) }}'"
                                                class="icon-btn edit-btn" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <form action="{{ route('admin.products.destroy', $product['id']) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this product?')"
                                                    class="icon-btn delete-btn" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="no-product">No products found!</p>
            @endif
        </div>
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

@endsection
