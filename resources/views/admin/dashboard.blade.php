@extends('layouts.admin')

@section('content')
    <div class="dashboard container">
        <h1 class="page-title">Dashboard</h1>

        <div class="dashboard-stats">
            {{-- Charts Row --}}
            <div class="stat-row">
                <div class="stat-card chart-card">
                    <h3 style="text-align:center; margin-bottom: 10px;">Order Status</h3>
                    <canvas id="orderStatusChart"></canvas>
                </div>
                <div class="stat-card chart-card">
                    <h3 style="text-align:center; margin-bottom: 10px;">Daily Orders</h3>
                    <canvas id="dailyOrderChart"></canvas>
                </div>
            </div>

            {{-- Statistics Row --}}
            <div class="stat-row">
                <div class="stat-card">
                    <h3>Total Sales</h3>
                    <p>RM{{ number_format($totalSales, 2) }}</p>
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
            width: 100%;
        }

        .dashboard-stats {
            display: block;
            margin-bottom: 30px;
        }

        .stat-row {
            display: flex;
            justify-content: left;
            flex-wrap: wrap;
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

        .chart-card {
            height: 280px;
        }

        .chart-card canvas {
            max-width: 100%;
            height: 180px;
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

            // Bar Chart - Last 7 Days
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
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
