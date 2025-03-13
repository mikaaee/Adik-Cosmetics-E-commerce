<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Adik Cosmetics</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </li>
                <li onclick="location.href='{{ route('admin.categories') }}'">
                    <i class="fas fa-folder"></i>
                    <span>All Categories</span>
                </li>
                <li onclick="location.href='{{ route('admin.products') }}'">
                    <i class="fas fa-box"></i>
                    <span>All Products</span>
                </li>
                <li onclick="location.href='{{ route('admin.add-category') }}'">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Categories</span>
                </li>
                <li onclick="location.href='{{ route('admin.add-product') }}'">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Products</span>
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
