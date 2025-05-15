<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { color: #2c3e50; }
        .highlight { font-weight: bold; color: #e74c3c; }
    </style>
</head>
<body>
    <h2 class="header">Hi {{ $orderData['user']['first_name'] ?? 'Customer' }},</h2>
    
    <p>Thank you for your purchase! We've received your order and are preparing it for shipment.</p>
    
    <p><strong>Order Total:</strong> <span class="highlight">RM{{ number_format($orderData['total'], 2) }}</span></p>
    
    <p>We'll notify you once your order ships. If you have any questions, please contact our support team.</p>
    
    <p>Stay beautiful! 💖</p>
</body>
</html>