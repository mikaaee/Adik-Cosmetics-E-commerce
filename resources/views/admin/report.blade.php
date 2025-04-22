@extends('layouts.admin')

@section('content')
    <div class="report-page container">
        <h1 class="page-title">Sales Report ({{ $startDate }} to {{ $endDate }})</h1>

        @if (count($filteredOrders) > 0)
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Product</th>
                        <th>Total Price</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filteredOrders as $order)
                        <tr>
                            <td>{{ $order['customer_name'] }}</td>
                            <td>{{ $order['product_name'] }}</td>
                            <td>${{ number_format($order['total_price'], 2) }}</td>
                            <td>{{ $order['created_at'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Form untuk memilih tarikh dan generate laporan -->
            <div class="report-section">
                <h2>Generate Report</h2>
                <form action="{{ route('admin.reports.index') }}" method="GET">
                    <input type="date" name="start_date" required>
                    <input type="date" name="end_date" required>
                    <button type="submit">Generate Report</button>
                </form>
            </div>
        @else
            <p class="no-orders-msg">No orders found for the selected date range.</p>
        @endif
    </div>
    <style>
        .no-orders-msg {
            text-align: center;
            margin-top: 150px;
            font-size: 1.2rem;
            color: #888;
        }
        .report-section {
            margin-top: 40px;
            text-align: center;
        }
        input[type="date"] {
            margin: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
@endsection
