@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="order-page">
        <h1 class="page-title" style="text-align: center">Manage Orders</h1>

        <!-- Filter Status -->
        <form method="GET" action="{{ route('admin.manage-orders.index') }}" class="search-form"
            style="margin-bottom: 20px; position: relative; z-index: 10;">
            <label for="status">Filter by Status:</label>
            <select name="status" class="form-select" style="width: 200px; margin-left: 10px; border-radius: 15px;"
                onchange="this.form.submit()">
                <option value="">All</option>
                <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </form>

        <div class="orders-page">
            @if (count($orders) > 0)
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>User ID</th>
                                    <th>Product</th>
                                    <th>Status</th>
                                    <th>Shipping</th>
                                    <th>Return</th>
                                    <th>Total (RM)</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $index => $order)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $order['user_id'] }}</td>
                                        <td>{{ $order['product'] }}</td>
                                        <td>{{ $order['status'] }}</td>
                                        <td>{{ $order['shipping'] ?? 'Pending' }}</td>
                                        <td>{{ $order['return_status'] ?? 'None' }}</td>
                                        <td>RM {{ number_format($order['total'], 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.manage-orders.update', $order['id']) }}">
                                                @csrf
                                                @method('PUT')
                                                <select name="shipping" onchange="this.form.submit()" class="form-select">
                                                    <option value="">Shipping</option>
                                                    <option value="Shipped">Shipped</option>
                                                    <option value="Delivered">Delivered</option>
                                                </select>
                                                <select name="return_status" onchange="this.form.submit()"
                                                    class="form-select mt-1">
                                                    <option value="">Return</option>
                                                    <option value="Refund">Refund</option>
                                                    <option value="Return">Return</option>
                                                    <option value="None">None</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <p class="no-product">No orders found!</p>
            @endif
        </div>
    </div>

    <!-- SweetAlert2 -->
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
            });
        </script>
    @endif
@endsection
