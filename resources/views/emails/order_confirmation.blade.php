<h2>Hi {{ $orderData['first_name']['stringValue'] ?? 'Customer' }},</h2>

<p>Thank you for your order!</p>

<p><strong>Order Summary:</strong></p>
@if (isset($orderData['items']) && !empty($orderData['items']))
    <ul>
        @foreach ($orderData['items'] as $item)
            <li>{{ $item['name'] }} (x{{ $item['quantity'] }}) - RM{{ number_format($item['price'], 2) }}</li>
        @endforeach
    </ul>
@else
    <p>No items in this order.</p>
@endif

<p><strong>Total Paid:</strong> RM{{ number_format($orderData['total'], 2) }}</p>
<p>We will notify you once the item is shipped.</p>

<p>Regards,<br>Adik Cosmetics Team</p>
