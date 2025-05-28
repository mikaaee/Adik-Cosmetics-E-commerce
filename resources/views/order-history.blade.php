@php use Carbon\Carbon; @endphp
@extends('layouts.main')

@section('title', 'Order History')

@section('header')
    @include('partials.header-home')
@endsection

@section('content')
    <div class="orders-page container py-4">
        <h2 class="mb-4">Your Order History</h2>

        @if (count($orders) > 0)
            <div class="table-wrapper">
                <table class="order-table">
                    <thead class="custom-thead">
                        <tr>
                            <th>Order ID</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Shipping</th>
                            <th>Return</th>
                            <th>Total (RM)</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            @php
                                $shipping = strtolower($order['shipping'] ?? 'pending');
                                $shippingColor = match ($shipping) {
                                    'pending' => '#ffc107',
                                    'shipped' => '#17a2b8',
                                    'delivered' => '#6f42c1',
                                    'completed', 'received' => '#28a745',
                                    default => '#6c757d',
                                };
                            @endphp
                            <tr>
                                <td>{{ $order['id'] }}</td>
                                <td>
                                    @if (isset($order['products']) && is_array($order['products']))
                                        <ul style="padding-left: 1rem; margin: 0;">
                                            @foreach ($order['products'] as $product)
                                                <li>{{ $product['name'] ?? '-' }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <em>No items</em>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-status badge-{{ strtolower($order['status']) }}">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        style="background-color: {{ $shippingColor }};
               padding: 6px 12px;
               color: #fff;
               border-radius: 20px;
               font-size: 0.85rem;
               display: inline-block;">
                                        {{ ucfirst($order['shipping'] ?? 'Pending') }}
                                    </span>

                                    @if (strtolower($order['shipping']) === 'shipped')
                                        <form id="confirm-received-form-{{ $order['id'] }}"
                                            action="{{ route('user.markReceived', $order['id']) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="button" onclick="confirmReceived('{{ $order['id'] }}')"
                                                style="background-color: #7c3d4f; color: white; border: none; border-radius: 20px; padding: 5px 12px; font-size: 0.8rem; margin-left: 5px;">
                                                <i class="fas fa-check-circle"></i> Received
                                            </button>
                                        </form>
                                    @endif
                                </td>

                                <td>{{ $order['return_status'] ?? 'None' }}</td>
                                <td>{{ number_format($order['total'], 2) }}</td>
                                <td>{{ Carbon::parse($order['date'])->format('d M Y, h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>You have no orders yet.</p>
        @endif
    </div>

    <style>
        .table-wrapper {
            overflow-x: auto;
            border-radius: 10px;
        }

        .order-table {
            width: 100%;
            max-width: 960px;
            margin: 20px auto;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .order-table th,
        .order-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .custom-thead {
            background-color: #c69c9c !important;
            color: #000;
        }

        .order-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .order-table tbody tr:hover {
            background-color: #e2e6ea;
        }

        .badge-status {
            display: inline-block;
            padding: 6px 12px;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 20px;
            text-transform: capitalize;
            color: #fff;
        }

        .badge-paid {
            background-color: #28a745;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-cancelled {
            background-color: #dc3545;
        }

        .badge-paid {
            background-color: #28a745;
            /* Green */
        }

        .badge-unpaid {
            background-color: #dc3545;
            /* Red */
        }

        .badge-delivered {
            background-color: #17a2b8;
            /* Blue */
        }

        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-completed {
            background-color: #6f42c1;
            /* Purple */
        }

        .badge-cancelled {
            background-color: #6c757d;
            /* Grey */
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmReceived(orderId) {
            Swal.fire({
                title: 'Confirm Received?',
                text: "Click Yes if you've received your order.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, I have received it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('confirm-received-form-' + orderId).submit();
                }
            });
        }
    </script>
@endsection
