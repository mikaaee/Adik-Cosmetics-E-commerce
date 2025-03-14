<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Adik Cosmetics</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;700&display=swap" rel="stylesheet">


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
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span> <!-- Example badge count -->
                    </button>

                    <!-- User Profile -->
                    <div class="user-profile" onclick="toggleProfileMenu()">
                        <i class="fas fa-user-circle" style="font-size: 24px; color: #fff;"></i>
                        <span>{{ session('user_data.name') ?? 'Admin' }}</span>
                        <i class="fas fa-caret-down"></i>
                    </div>
                    <!-- Dropdown Menu -->
                    <div id="profileDropdown" class="profile-dropdown">
                        <a href="{{ route('admin.edit-profile') }}"><i class="fas fa-user-edit"></i> Edit Profile</a>
                        <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
            const dropdown = document.getElementById('profileDropdown');
            dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
        }

        // Optional: Hide dropdown kalau klik luar dari profile
        document.addEventListener('click', function(event) {
            const profile = document.querySelector('.user-profile-container');
            const dropdown = document.getElementById('profileDropdown');
            if (!profile.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>



</body>

</html>
