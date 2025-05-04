<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Placed</title>
</head>
<body>
    <h1>New Order Received!</h1>
    <p>Details of the new order:</p>
    <ul>
        <li>User ID: {{ $order['user_id'] }}</li>
        <li>Subtotal: {{ $order['subtotal'] }}</li>
        <li>Shipping Cost: {{ $order['shipping_cost'] }}</li>
        <li>Total: {{ $order['total'] }}</li>
        <li>Order Items: {{ json_encode($order['items']) }}</li>
    </ul>
</body>
</html>
