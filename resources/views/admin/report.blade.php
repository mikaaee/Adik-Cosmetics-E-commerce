@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="report-container">
        <div class="report-header">
            <h1 class="page-title">
                </i>Sales Report
            </h1>
            <div class="report-actions">
                <a href="{{ route('admin.reports.export', request()->query()) }}" class="btn btn-pdf" title="Download PDF">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('admin.reports.exportCsv', request()->query()) }}" class="btn btn-csv"
                    title="Download CSV">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="filter-card">
            <h3 class="filter-title">
                <i class="fas fa-filter me-2"></i>Filter Options
            </h3>
            <form method="GET" action="{{ route('admin.reports.index') }}" class="report-form">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="start_date">Start Date</label>
                        <div class="input-icon">
                            <i class="fas fa-calendar"></i>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="form-control">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="end_date">End Date</label>
                        <div class="input-icon">
                            <i class="fas fa-calendar"></i>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="status">Order Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </div>
                </div>

                <div class="filter-group" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-chart-line me-2"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>


        {{-- Results Section --}}
        <div class="results-card">
            @if (count($orders) > 0)
                <div class="results-header">
                    <div class="results-summary">
                        <span class="badge bg-primary">{{ count($orders) }} orders found</span>
                        <span class="total-sales">Total Sales: <strong>RM {{ number_format($total, 2) }}</strong></span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th class="text-right">Amount (RM)</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $index => $order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>#{{ $order['id'] }}</td>
                                    <td>
                                        @if ($order['user_id'])
                                            User #{{ $order['user_id'] }}
                                        @else
                                            <span class="guest-badge">Guest</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($order['status']) }}">
                                            {{ $order['status'] }}
                                        </span>
                                    </td>
                                    <td class="text-right">RM {{ number_format($order['total'], 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-results">
                    <i class="fas fa-info-circle"></i>
                    <p>No orders found matching your criteria</p>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary">
                        Reset Filters
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Main Container */
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Section */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            color: #2c3e50;
            font-weight: 600;
            margin: 0;
        }

        .report-actions {
            display: flex;
            gap: 10px;
        }

        /* Filter Card */
        .filter-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 25px;
        }

        .filter-title {
            font-size: 16px;
            color: #34495e;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px 40px;
            align-items: end;
        }

        .filter-group {
            margin-bottom: 0;
        }

        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #7f8c8d;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }

        .input-icon input {
            padding-left: 35px;
        }

        /* Results Card */
        .results-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .results-summary {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .total-sales {
            font-size: 16px;
            color: #2c3e50;
        }

        /* Table Styles */
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th {
            background-color: #f8f9fa;
            color: #34495e;
            font-weight: 600;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #eee;
        }

        .report-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .report-table tr:hover {
            background-color: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-shipped {
            background-color: #d4edda;
            color: #155724;
        }

        .status-completed {
            background-color: #e2e3e5;
            color: #383d41;
        }

        /* Guest Badge */
        .guest-badge {
            background: #f0f0f0;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: #666;
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 40px 20px;
            color: #7f8c8d;
        }

        .no-results i {
            font-size: 48px;
            color: #bdc3c7;
            margin-bottom: 15px;
        }

        .no-results p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-outline-primary {
            background-color: transparent;
            border: 1px solid #3498db;
            color: #3498db;
        }

        .btn-outline-primary:hover {
            background-color: #f8f9fa;
        }

        .btn-pdf {
            background-color: #e74c3c;
            color: white;
        }

        .btn-pdf:hover {
            background-color: #c0392b;
        }

        .btn-csv {
            background-color: #27ae60;
            color: white;
        }

        .btn-csv:hover {
            background-color: #219653;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .report-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
