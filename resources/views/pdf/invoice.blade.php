<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice_no }}</title>
    <style>
        body { font-family: sans-serif; line-height: 1.4; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2, h4 { color: #9e5866; }
        .summary { margin-top: 20px; }
    </style>
</head>
<body>

    <h2>Adik Cosmetics - Invoice</h2>
    <p><strong>Invoice No:</strong> {{ $invoice_no }}</p>
    <p><strong>Date:</strong> {{ $date }}</p>

    <h4>Customer Details:</h4>
    <p>Name: {{ $user['first_name'] }} {{ $user['last_name'] }}</p>
    <p>Phone: {{ $user['phone'] }}</p>
    <p>Address: {{ $user['address'] }}, {{ $user['postcode'] }} {{ $user['city'] }}, {{ $user['country'] }}</p>

    <h4>Order Items:</h4>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price (RM)</th>
                <th>Subtotal (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['price'], 2) }}</td>
                    <td>{{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Subtotal:</strong> RM{{ number_format($subtotal, 2) }}</p>
        <p><strong>Shipping:</strong> RM{{ number_format($shipping_cost, 2) }}</p>
        <p><strong>Total:</strong> RM{{ number_format($total, 2) }}</p>
    </div>

</body>
</html>
