<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8.5pt;
            color: #333;
            line-height: 1.4;
            padding: 0.5cm;
        }

        .header {
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .company-logo {
            max-height: 350px;
            max-width: 300px;
            display: block; 
            margin: 0 auto 10px auto;
        }

        .report-title {
            color: #2c3e50;
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-subtitle {
            color: #7f8c8d;
            font-size: 12pt;
            margin-bottom: 15px;
        }

        .report-meta {
            font-size: 9pt;
            color: #7f8c8d;
            margin-bottom: 15px;
        }

        .filters {
            background-color: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px 40px;
        }

        .filter-item {
            min-width: 150px;
            margin: 0px auto;
        }

        .filter-label {
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-bottom: 3px;
            font-size: 9pt;
        }

        .filter-value {
            color: #333;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9pt;
        }

        th {
            background-color: #e8edf5;
            color: #2c3e50;
            text-align: left;
            padding: 8px 10px;
            font-weight: 600;
            word-break: break-word;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e0e0e0;
            word-break: break-word;
        }

        tr:nth-child(even) {
            background-color: #fefefe;
        }

        .total-row {
            background-color: #e0f7fa !important;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e0e0e0;
            font-size: 8pt;
            color: #7f8c8d;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .status-completed {
            color: #27ae60;
            font-weight: bold;
        }

        .status-pending {
            color: #f39c12;
            font-weight: bold;
        }

        .status-cancelled {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logoacGREAT.png') }}" class="company-logo">
        <div class="report-title">Sales Performance Report</div>
        <!--<div class="report-subtitle">{ config('app.name') }}</div>-->
        <div class="report-meta">
            Generated on: {{ now()->format('F j, Y \a\t H:i') }}<br>
            Report Period: {{ $start ?? 'N/A' }} to {{ $end ?? 'Present' }}
        </div>
    </div>

    <div class="filters">
        <div class="filter-item">
            <span class="filter-label">Total Orders</span>
            <span class="filter-value">{{ count($orders) }}</span>
        </div>
        <div class="filter-item">
            <span class="filter-label">Total Revenue</span>
            <span class="filter-value">RM {{ number_format($total, 2) }}</span>
        </div>
        <div class="filter-item">
            <span class="filter-label">Paid Orders</span>
            <span class="filter-value">{{ $paidCount }}</span>
        </div>
        <div class="filter-item">
            <span class="filter-label">Pending Orders</span>
            <span class="filter-value">{{ $pendingCount }}</span>
        </div>
        @if (!empty($status))
            <div class="filter-item">
                <span class="filter-label">Filtered Status</span>
                <span class="filter-value">{{ ucfirst($status) }}</span>
            </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="22%">Order ID</th>
                <th width="18%">Customer</th>
                <th width="10%">Status</th>
                <th width="15%" class="text-right">Amount (RM)</th>
                <th width="15%">Date</th>
                <th width="15%">Method</th>
            </tr>
        </thead>
        
        <tbody>
            @forelse ($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order['id'] }}</td>
                    <td>{{ $order['user_id'] ?? 'Guest' }}</td>
                    <td>
                        <span class="status-{{ strtolower($order['status']) }}">
                            {{ $order['status'] }}
                        </span>
                    </td>
                    <td class="text-right">{{ number_format($order['total'], 2) }}</td>
                    <td>{{ $order['created_at'] ?? $order['date'] }}</td>
                    <td>{{ $order['payment_method'] ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No sales records found for the selected criteria</td>
                </tr>
            @endforelse

            @if(count($orders) > 0)
                <tr class="total-row">
                    <td colspan="4">Total Sales</td>
                    <td class="text-right">RM {{ number_format($total, 2) }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        CONFIDENTIAL - For internal use only<br>
        {{ config('app.name') }} • {{ now()->format('F Y') }}
    </div>
</body>
</html>
