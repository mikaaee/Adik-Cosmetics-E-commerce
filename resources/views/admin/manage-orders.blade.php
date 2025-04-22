@extends('layouts.admin')

@section('content')
    <div class="order-page container">
        <h1 class="page-title">Manage Orders</h1>

        {{-- Display all orders --}}
        @if (count($orders) > 0)
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Customer Name</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Shipping</th>
                            <th>Return/Refund</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $order['customer_name'] }}</td>
                                <td>{{ $order['product_name'] }}</td>
                                <td>{{ $order['status'] }}</td>
                                <td>{{ $order['shipping'] ?? 'Pending' }}</td>
                                <td>{{ $order['return_status'] ?? 'None' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.orders.update', $order['id']) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="dummy" value="1">
                                        <select name="shipping" onchange="this.form.submit()">
                                            <option value="">-- Notify Shipping --</option>
                                            <option value="Shipped">Shipped</option>
                                            <option value="Delivered">Delivered</option>
                                        </select>
                                        <select name="return_status" onchange="this.form.submit()">
                                            <option value="">-- Refund/Return --</option>
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
        @else
            <p class="no-orders-msg">No orders available.</p>
        @endif
    </div>
    <style>
        .page-title {
            margin-bottom: 10px !important;
            margin-top: 0px;
            /* adjust jarak bawah supaya tak terlalu jauh */
        }

        .order-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 200px;
            /* adjust ikut kesesuaian */
            justify-content: center;
        }

        .no-orders-msg {
            text-align: center;
            margin-top: 150px;
            font-size: 1.2rem;
            color: #888;
        }
    </style>

@endsection
