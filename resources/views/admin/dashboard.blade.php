@extends('layouts.admin')

@section('content')
    <div class="dashboard">
        <div class="dashboard-header">
            <h1 class="page-title">Dashboard Overview</h1>
            <div class="header-actions">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="date-filter">
                    <input type="text" id="dateRange" name="date_range" placeholder="Select date range" readonly>
                    <button type="submit" class="apply-btn">Apply</button>
                </form>

            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Summary Cards -->
            <div class="summary-card sales-card">
                <div class="card-content">
                    <div class="metric-header">
                        <h3>Total Sales</h3>
                        <!--<div class="metric-trend up">
                                <i class="trend-icon"></i>
                            </div>-->
                    </div>
                    <p class="metric-value">RM{{ number_format($totalSales, 2) }}</p>
                    <!--<p class="metric-description">All time revenue</p>-->
                </div>
                <div class="card-decoration"></div>
            </div>

            <div class="summary-card customers-card">
                <div class="card-content">
                    <div class="metric-header">
                        <h3>Total Customers</h3>
                        <!--<div class="metric-trend up">
                                <i class="trend-icon"></i>
                            </div>-->
                    </div>
                    <p class="metric-value">{{ $totalCustomers }}</p>
                    <p class="metric-description">Registered users</p>
                </div>
                <div class="card-decoration"></div>
            </div>

            <div class="summary-card orders-card">
                <div class="card-content">
                    <div class="metric-header">
                        <h3>Total Orders</h3>
                        <!--<div class="metric-trend neutral">
                                <i class="trend-icon"></i>
                            </div>-->
                    </div>
                    <p class="metric-value">{{ $totalOrders }}</p>
                    <p class="metric-description">All time orders</p>
                </div>
                <div class="card-decoration"></div>
            </div>

            <!-- Charts Section -->
            <div class="chart-card wide-card">
                <div class="chart-header">
                    <h3>Daily Order Volume</h3>
                </div>
                <canvas id="dailyOrderChart"></canvas>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Order Status</h3>
                </div>
                <canvas id="orderStatusChart"></canvas>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-color paid"></span>
                        <span>Paid ({{ $paidOrders ?? 0 }})</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color pending"></span>
                        <span>Pending ({{ $pendingOrders ?? 0 }})</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --primary: #5a67d8;
            --primary-light: #818cf8;
            --success: #48bb78;
            --warning: #ed8936;
            --danger: #f56565;
            --light: #f7fafc;
            --dark: #2d3748;
            --gray: #a0aec0;
            --gray-light: #edf2f7;
        }

        .dashboard {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        .date-filter {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #fff;
            padding: 12px 18px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            max-width: 100%;
            flex-wrap: wrap;
        }

        .date-filter input[type="text"] {
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #333;
            width: 220px;
            background-color: #fdfdfd;
            transition: border-color 0.2s ease;
        }

        .date-filter input[type="text"]:focus {
            outline: none;
            border-color: #000;
        }

        .apply-btn {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .apply-btn:hover {
            background-color: #222;
            color: #f1f1f1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .apply-btn:active {
            transform: translateY(0);
            box-shadow: none;
        }


        .filter-icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            background-color: var(--gray);
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath d='M487.976 0H24.028C2.71 0-8.047 25.866 7.058 40.971L192 225.941V432c0 7.831 3.821 15.17 10.237 19.662l80 55.98C298.02 518.69 320 507.493 320 487.98V225.941l184.947-184.97C520.021 25.896 509.338 0 487.976 0z'/%3E%3C/svg%3E") no-repeat center;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .card-decoration {
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(90, 103, 216, 0.1) 0%, rgba(90, 103, 216, 0) 100%);
            border-bottom-left-radius: 100%;
        }

        .sales-card .card-decoration {
            background: linear-gradient(135deg, rgba(72, 187, 120, 0.1) 0%, rgba(72, 187, 120, 0) 100%);
        }

        .customers-card .card-decoration {
            background: linear-gradient(135deg, rgba(237, 137, 54, 0.1) 0%, rgba(237, 137, 54, 0) 100%);
        }

        .orders-card .card-decoration {
            background: linear-gradient(135deg, rgba(245, 101, 101, 0.1) 0%, rgba(245, 101, 101, 0) 100%);
        }

        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .metric-header h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray);
            margin: 0;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0.5rem 0;
        }

        .metric-description {
            font-size: 0.875rem;
            color: var(--gray);
            margin: 0;
        }

        .metric-trend {
            display: flex;
            align-items: center;
        }

        .trend-icon {
            display: inline-block;
            width: 16px;
            height: 16px;
        }

        .metric-trend.up .trend-icon {
            background-color: var(--success);
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512'%3E%3Cpath d='M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z'/%3E%3C/svg%3E") no-repeat center;
        }

        .metric-trend.down .trend-icon {
            background-color: var(--danger);
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512'%3E%3Cpath d='M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z'/%3E%3C/svg%3E") no-repeat center;
        }

        .metric-trend.neutral .trend-icon {
            background-color: var(--warning);
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512'%3E%3Cpath d='M288 288H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h256c17.7 0 32-14.3 32-32s-14.3-32-32-32z'/%3E%3C/svg%3E") no-repeat center;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .wide-card {
            grid-column: span 2;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .chart-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .chart-actions {
            display: flex;
            gap: 0.5rem;
        }

        .chart-action-btn {
            padding: 0.25rem 0.75rem;
            background: var(--gray-light);
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            color: var(--gray);
            cursor: pointer;
        }

        .chart-action-btn.active {
            background: var(--primary);
            color: white;
        }

        .chart-legend {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .legend-color {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }

        .legend-color.paid {
            background-color: var(--success);
        }

        .legend-color.pending {
            background-color: var(--warning);
        }

        canvas {
            width: 100% !important;
            height: 250px !important;
        }

        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .wide-card {
                grid-column: span 1;
            }
        }
    </style>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Enhanced Order Status Chart
            const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Pending'],
                    datasets: [{
                        data: [{{ $paidOrders ?? 0 }}, {{ $pendingOrders ?? 0 }}],
                        backgroundColor: ['#48bb78', '#ed8936'],
                        borderWidth: 0,
                        weight: 0.5
                    }]
                },
                options: {
                    cutout: '80%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    maintainAspectRatio: false
                }
            });

            // Enhanced Daily Orders Chart
            const dailyCtx = document.getElementById('dailyOrderChart').getContext('2d');
            new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($last7Days ?? []) !!},
                    datasets: [{
                        label: 'Orders',
                        data: {!! json_encode($ordersPerDay ?? []) !!},
                        backgroundColor: 'rgba(90,103,216,0.1)',
                        borderColor: '#5a67d8',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#5a67d8',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    maintainAspectRatio: false
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#dateRange", {
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: [
                    "{{ request('start_date', now()->subDays(6)->toDateString()) }}",
                    "{{ request('end_date', now()->toDateString()) }}"
                ]
            });
        });
    </script>
@endsection
