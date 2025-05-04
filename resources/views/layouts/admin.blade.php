<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Adik Cosmetics</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;700&display=swap" rel="stylesheet">

</head>
<style>
    #sidebar {
        transition: all 0.3s ease;
        /* Smooth transition for all properties */
        width: 250px;
        /* Default width when the sidebar is expanded */
        /* Add any other styling for the sidebar */
    }

    #sidebar.minimized {
        width: 80px;
        /* Width when the sidebar is minimized */
    }
</style>

<body>

    <div class="container">

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">Adik Cosmetics AdminHub</div>
            </div>
            <ul>
                <li onclick="location.href='{{ route('admin.dashboard') }}'">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </li>

                <li onclick="location.href='{{ route('admin.categories.index') }}'">
                    <i class="fas fa-folder-open"></i>
                    <span>Manage Categories</span>
                </li>

                <li onclick="location.href='{{ route('admin.products.index') }}'">
                    <i class="fas fa-boxes"></i>
                    <span>Manage Products</span>
                </li>

                <li onclick="location.href='{{ route('admin.manage-orders.index') }}'">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Manage Orders</span>
                </li>

                <li onclick="location.href='{{ route('admin.reports.index') }}'">
                    <i class="fas fa-chart-line"></i>
                    <span>Report</span>
                </li>

                <li onclick="location.href='{{ route('admin.invoices.index') }}'">
                    <i class="fas fa-file-invoice-dollar"></i> 
                    <span>Invoices</span>
                </li>

                <li onclick="location.href='{{ route('admin.ads.index') }}'">
                    <i class="fas fa-bullhorn"></i> 
                    <span>Manage Ads</span>
                </li>

                <li onclick="location.href='{{ route('logout') }}'">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </li>
            </ul>

        </div>

        <!-- Main Section -->
        <div class="main">

            <!-- Header -->
            <div class="header">
                <div class="sidebar-toggle" onclick="toggleSidebar()">
                    &#9776; <!-- Hamburger icon -->
                </div>

                <div class="user-actions">
                    <!-- Notification Icon -->
                    <button class="icon-btn" onclick="showNotifications()">
                        <i class="fas fa-bell"></i>
                        <span class="badge" id="order-badge">0</span>
                    </button>


                    <!-- User Profile -->
                    <div class="user-profile" onclick="toggleProfileMenu()">
                        <i class="fas fa-user-circle" style="font-size: 24px; color: #fff;"></i>
                        <span>{{ session('user_data.name') ?? 'Admin' }}</span>
                        <i class="fas fa-caret-down"></i>
                    </div>
                    <!-- Dropdown Menu -->
                    <div id="profileDropdown" class="profile-dropdown" style="display: none;">
                        <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>

                </div>
            </div>

            <div class="content">
                @yield('content')
            </div>
        </div>
        <!-- Include any global JS here -->

        @yield('scripts') <!-- ✅ Tempat letak script custom per page -->

    </div>

    <!-- Script for Sidebar -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('minimized');
        }

        function showNotifications() {
            Swal.fire({
                title: 'Order Notification',
                text: 'Ada pesanan baru masuk!',
                icon: 'info',
                confirmButtonText: 'View Orders',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('admin.manage-orders.index') }}";
                }
            });
        }

        function fetchNewOrderCount() {
            fetch('/admin/api/new-orders')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('order-badge');
                    badge.innerText = data.count > 0 ? data.count : '';
                });
        }

        setInterval(fetchNewOrderCount, 10000); // setiap 10 saat
        window.onload = fetchNewOrderCount;



        function toggleProfileMenu() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
        }

        // Optional: Hide dropdown kalau klik luar dari profile
        document.addEventListener('click', function(event) {
            const profile = document.querySelector('.user-profile');
            const dropdown = document.getElementById('profileDropdown');
            if (!profile.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>

</body>

</html>
