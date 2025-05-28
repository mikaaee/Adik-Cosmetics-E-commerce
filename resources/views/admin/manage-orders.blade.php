@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="order-page">
        <h1 class="page-title" style="text-align: center">Manage Orders</h1>

        <!-- Filter Status -->
        <form method="GET" action="{{ route('admin.manage-orders.index') }}" class="search-form"
            style="margin-bottom: 20px; position: relative; z-index: 10;">
            <label for="status">Filter by Status:</label>
            <select id="status" name="status" class="form-select"
                style="width: 200px; margin-left: 10px; border-radius: 15px;" onchange="this.form.submit()">
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
                                    @php
                                        $shipping = strtolower($order['shipping'] ?? 'pending');
                                        $shippingBadge = match ($shipping) {
                                            'pending' => 'badge-pending',
                                            'shipped' => 'badge-shipped',
                                            'delivered' => 'badge-delivered',
                                            'received', 'completed' => 'badge-completed',
                                            default => 'badge-default',
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $order['user_id'] }}
                                            @if (!empty($order['user_name']))
                                                ({{ $order['user_name'] }})
                                            @endif
                                        </td>
                                        <td>{{ $order['product'] }}</td>
                                        <td>{{ $order['status'] }}</td>
                                        <td>
                                            <span class="badge-status {{ $shippingBadge }}">
                                                {{ ucfirst($order['shipping'] ?? 'Pending') }}
                                            </span>
                                        </td>
                                        <td>{{ $order['return_status'] ?? 'None' }}</td>
                                        <td>RM {{ number_format($order['total'], 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.manage-orders.update', $order['id']) }}">
                                                @csrf
                                                @method('PUT')
                                                <select name="shipping" class="form-select mt-1">
                                                    <option value="">Shipping</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Shipped">Shipped</option>
                                                    <option value="Delivered">Delivered</option>
                                                    <option value="Received">Received</option>
                                                    <option value="Completed">Completed</option>
                                                </select>
                                                <select name="return_status" class="form-select mt-1">
                                                    <option value="">Return</option>
                                                    <option value="None">None</option>
                                                    <option value="Return">Return</option>
                                                    <option value="Refund">Refund</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary mt-1">Update</button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.orders.destroy', $order['id']) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this order?');"
                                                style="display: inline-block; margin-top: 6px;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm"
                                                    style="background-color: #dc3545; color: white; border: none; padding: 5px 12px; border-radius: 6px;">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
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

    <style>
        .badge-status {
            display: inline-block;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 20px;
            text-transform: capitalize;
            color: #fff;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-shipped {
            background-color: #17a2b8;
        }

        .badge-delivered {
            background-color: #6f42c1;
        }

        .badge-completed {
            background-color: #28a745;
        }

        .badge-default {
            background-color: #6c757d;
        }
    </style>

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
