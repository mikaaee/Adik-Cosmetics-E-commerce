@extends('layouts.admin')

@section('content')
    <div class="dashboard container">
        <h1 class="page-title">Dashboard</h1>
        <div style="max-width: 600px; margin: 40px auto;">
            <h3 style="text-align:center">Order Status (Pie Chart)</h3>
            <canvas id="orderStatusChart"></canvas>
        </div>

        <div style="max-width: 700px; margin: 40px auto;">
            <h3 style="text-align:center">Daily Orders (Last 7 Days)</h3>
            <canvas id="dailyOrderChart"></canvas>
        </div>


        <div class="dashboard-stats">
            <div class="stat-row">
                <div class="stat-card">
                    <h3>Total Sales</h3>
                    <p>${{ number_format($totalSales, 2) }}</p>
                </div>
                <div class="stat-card">
                    <h3>Total Customers</h3>
                    <p>{{ $totalCustomers }}</p>
                </div>
            </div>
            <div class="stat-row">
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p>{{ $totalOrders }}</p>
                </div>
            </div>
        </div>


    </div>

    <style>
        .page-title {
            margin-bottom: 30px;
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
        }

        .dashboard {
            display: block;
            /* Pastikan layout dalam dashboard vertical */
            width: 100%;
        }


        .dashboard-stats {
            display: block;
            /* ruang antara kad */
            margin-bottom: 30px;
        }

        .stat-row {
            display: flex;
            justify-content: left;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 250px;
            height: 140px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Pie Chart - Paid vs Pending
            const pieCtx = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['Paid', 'Pending'],
                    datasets: [{
                        data: [{{ $paidOrders ?? 0 }}, {{ $pendingOrders ?? 0 }}],
                        backgroundColor: ['#28a745', '#ffc107']
                    }]
                }
            });

            // Bar Chart - Last 7 days
            const barCtx = document.getElementById('dailyOrderChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($last7Days ?? []) !!},
                    datasets: [{
                        label: 'Orders',
                        data: {!! json_encode($ordersPerDay ?? []) !!},
                        backgroundColor: '#007bff'
                    }]
                }
            });
        });
    </script>
@endsection
