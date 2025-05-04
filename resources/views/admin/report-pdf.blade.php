<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .filters {
            margin-bottom: 20px;
        }

        .filters p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            margin-top: 15px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Sales Report</h1>

    <div class="filters">
        @isset($start)
            <p><strong>Start Date:</strong> {{ $start }}</p>
        @endisset
        @isset($end)
            <p><strong>End Date:</strong> {{ $end }}</p>
        @endisset
        @isset($status)
            <p><strong>Status:</strong> {{ $status }}</p>
        @endisset
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Status</th>
                <th>Total (RM)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order['id'] }}</td>
                    <td>{{ $order['user_id'] ?? '-' }}</td>
                    <td>{{ $order['status'] }}</td>
                    <td>RM {{ number_format($order['total'], 2) }}</td>
                    <td>{{ $order['created_at'] ?? $order['date'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(isset($total))
        <p class="total">Total Sales: RM {{ number_format($total, 2) }}</p>
    @endif
</body>
</html>
