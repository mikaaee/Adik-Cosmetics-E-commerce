<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Adik Cosmetics</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    <div class="container">


        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">Adik Cosmetics AdminHub</div>
            </div>
            <ul>
                <li onclick="location.href='{{ route('admin.dashboard') }}'">
                    <span>Dashboard</span>
                </li>
                <li onclick="location.href='{{ route('admin.categories') }}'">
                    <span>All Categories</span>
                </li>
                <li onclick="location.href='{{ route('admin.products') }}'">
                    <span>All Products</span>
                </li>
                <li onclick="location.href='{{ route('admin.add-category') }}'">
                    <span>Add Categories</span>
                </li>
                <li onclick="location.href='{{ route('admin.add-product') }}'">
                    <span>Add Products</span>
                </li>
                <li onclick="location.href='{{ route('logout') }}'">
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
                        🔔
                        <span class="badge">3</span> <!-- Example badge count -->
                    </button>

                    <!-- User Profile -->
                    <div class="user-profile" onclick="toggleProfileMenu()">
                        <img src="{{ asset('images/admin-profile.jpg') }}" alt="Profile">
                        <span>{{ session('user_data.name') ?? 'Admin' }}</span>
                    </div>
                    
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>

        </div>

    </div>

    <!-- Script for Sidebar -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('minimized');
        }

        function showNotifications() {
            alert("You have new notifications!");
        }

        function toggleProfileMenu() {
            alert("Profile menu clicked! (You can add dropdown here)");
        }
    </script>



</body>

</html>
