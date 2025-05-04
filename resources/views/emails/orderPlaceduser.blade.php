<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Thank you for your order!</h1>
    <p>Your order has been placed successfully. Here are the details:</p>
    <ul>
        <li>Subtotal: {{ $order['subtotal'] }}</li>
        <li>Shipping Cost: {{ $order['shipping_cost'] }}</li>
        <li>Total: {{ $order['total'] }}</li>
        <li>Order Items: {{ json_encode($order['items']) }}</li>
    </ul>
</body>
</html>
