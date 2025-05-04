@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <h1 class="page-title">Sales Report</h1>

    {{-- Report Filter Form --}}
    <form method="GET" action="{{ route('admin.reports.index') }}" class="report-form">
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}">
        </div>
        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}">
        </div>
        <div class="form-group">
            <label for="status">Order Status:</label>
            <select name="status">
                <option value="">All</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="button-wrapper">
            <button type="submit" class="btn btn-primary small-btn">
                <i class="fas fa-chart-line"></i> Generate
            </button>
            <a href="{{ route('admin.reports.export', request()->query()) }}" class="btn btn-danger small-btn"
                title="Download as PDF">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>


    </form>

    {{-- Display Table --}}
    @if (count($orders) > 0)
        <div class="container">
            <div class="all-products-section">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Order ID</th>
                                    <th>User ID</th>
                                    <th>Status</th>
                                    <th>Total (RM)</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $index => $order)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $order['id'] }}</td>
                                        <td>{{ $order['user_id'] }}</td>
                                        <td>{{ $order['status'] }}</td>
                                        <td>RM {{ number_format($order['total'], 2) }}</td>
                                        <td>{{ $order['created_at'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="text-end mt-3" style="font-weight:bold;">
                        Total Sales: RM {{ number_format($total, 2) }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <p class="text-center mt-4">No data found for selected filters.</p>
    @endif

    <style>
        .report-form {
            display: flex;
            gap: 20px;
            justify-content: center;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;

        }

        .form-group {
            display: flex;
            flex-direction: column;
            min-width: 200px;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .button-wrapper {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-top: 10px;
        }

        .btn.small-btn {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            height: 38px;
            line-height: 1.2;
        }

        .btn.small-btn i {
            font-size: 14px;
        }


        .btn.btn-danger {
            color: #fff;
            background-color: #dc3545;
            border: none;
        }

        .btn.btn-danger:hover {
            background-color: #bb2d3b;
        }


        .table-wrapper {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .custom-table {
            width: 100%;
            max-width: 960px;
            margin-top: 20px auto;
            /* Atau buang terus */
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);

        }

        .custom-table th,
        .custom-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .custom-table thead {
            background-color: #343a40;
            color: #ffffff;
        }

        .custom-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .custom-table tbody tr:hover {
            background-color: #e9ecef;
        }
    </style>
@endsection
