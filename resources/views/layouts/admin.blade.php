<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Adik Cosmetics</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="{{ asset('js/admin.js') }}" defer></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;700&display=swap" rel="stylesheet">
</head>

<style>
    #sidebar {
        transition: all 0.3s ease;
        width: 250px;
    }

    #sidebar.minimized {
        width: 80px;
    }

    /* Sidebar Header */
    .sidebar-header {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 70px;
        padding-top: 10px;
        padding-bottom: 0;
    }

    .sidebar-header .logo {
        width: 110px;
        height: auto;
        transition: all 0.3s ease;
    }

    #sidebar.minimized .sidebar-header {
        height: 60px;
        padding-top: 8px;
    }

    #sidebar.minimized .sidebar-header .logo {
        width: 50px;
    }

    /* Sidebar Menu Spacing */
    .sidebar ul {
        padding-top: 10px;
    }

    .sidebar ul li {
        margin-top: 5px;
    }

    .icon-btn {
        background: none;
        border: none;
        cursor: pointer;
        position: relative;
        margin-right: 15px;
    }

    .badge {
        background-color: red;
        color: black;
        font-size: 12px;
        padding: 2px 6px;
        border-radius: 50%;
        position: absolute;
        top: -5px;
        right: -10px;
    }
</style>

<body>

    <div class="container">

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logoacGREAT.png') }}" alt="ADIK COSMETICS HOUSE" class="logo">
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
                    &#9776;
                </div>

                <div class="user-actions">
                    <!-- Bell Button 
                    <button class="icon-btn" onclick="toggleNotifBox()">
                        <i class="fas fa-bell"></i>
                        <span class="badge" id="order-badge">0</span>
                    </button> -->

                     <!--Dropdown Box 
                    <div id="notif-box"
                        style="
    display: none;
    position: absolute;
    top: 60px;
    right: 80px;
    width: 280px;
    background: #1c1c1c;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 10px;
    z-index: 999;
    max-height: 300px; /* ✅ Tambah ini */
    overflow-y: auto;  /* ✅ Supaya scroll boleh muncul */
    color: white;      /* ✅ Teks putih jika latar hitam */
    ">
                        <ul id="notif-list" style="list-style: none; padding-left: 0; margin: 0;">
                            <li>No new orders.</li>
                        </ul>
                    </div>-->

                    <div class="user-profile" onclick="toggleProfileMenu()">
                        <i class="fas fa-user-circle" style="font-size: 24px; color: #fff;"></i>
                        <span>{{ session('user_data.name') ?? 'Admin' }}</span>
                        <i class="fas fa-caret-down"></i>
                    </div>
                    <div id="profileDropdown" class="profile-dropdown" style="display: none;">
                        <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>

            <div class="content">
                @yield('content')
            </div>
        </div>

        @yield('scripts')
    </div>
</body>

</html>
