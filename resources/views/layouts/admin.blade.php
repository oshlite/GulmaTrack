<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | GulmaTrack</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('image/logo3.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/logo3.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('image/logo3.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins'
        }

        :root {
            --primary-color: #128241;
            --secondary-color: #D6DF20;
            --accent-color: #FBA919;
            --light-bg: #f8f9fa;
            --text-color: #333;
            --border-color: #e0e0e0;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins';
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
            padding-top: 70px;
        }

        .admin-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .admin-content {
            flex: 1;
        }

        /* Navbar Styles */
        nav.admin-navbar {
            background: #128241;
            backdrop-filter: blur(100px);
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            border-bottom: 2px solid var(--secondary-color);
            width: 100%;
        }

        .navbar-container {
            max-width: 100%;
            margin: 0;
            padding: 0 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
            flex-wrap: wrap;
        }

        .navbar-brand {
            font-size: 22px;
            font-family: 'Poppins';
            font-weight: 800;
            color: #FBA919;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
            color: #FBA919;
        }

        .navbar-brand img {
            width: 60px;
            height: 45px;
            object-fit: contain;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: rotate(1deg) scale(1);
        }

        .navbar-brand span {
            color: #FBA919 !important;
            font-size: 18px;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2px;
            flex-wrap: nowrap;
            margin: 0;
            align-items: center;
        }

        .nav-item {
            position: relative;
            white-space: nowrap;
        }

        .nav-link {
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px 16px;
            display: block;
            transition: all 0.3s ease;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: var(--secondary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 80%;
        }

        .nav-link:hover {
            color: #ffffff;
        }

        .nav-link.active {
            color: var(--secondary-color);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            color: #ecf0f1;
            font-size: 12px;
            font-weight: 600;
        }

        .admin-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: 700;
            font-family: 'Poppins';
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .logout-btn {
            padding: 8px 14px;
            background: #E74C3C;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 12px;
            font-family: 'Poppins';
            letter-spacing: 0.3px;
            box-shadow: 0 2px 6px rgba(231, 76, 60, 0.3);
            white-space: nowrap;
        }

        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.4);
        }

        .mobile-toggle-admin {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 22px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 6px;
            flex-shrink: 0;
        }

        .mobile-toggle-admin:hover {
            color: var(--secondary-color);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            body {
                font-size: 14px;
            }

            .navbar-container {
                padding: 0 12px;
            }

            .navbar-brand {
                font-size: 18px;
            }

            .navbar-brand img {
                width: 50px;
                height: 40px;
            }

            .navbar-brand span {
                font-size: 16px;
            }

            .nav-link {
                padding: 8px 12px;
                font-size: 11px;
            }

            .admin-info {
                padding: 6px 10px;
                font-size: 11px;
                gap: 8px;
            }

            .admin-avatar {
                width: 28px;
                height: 28px;
            }

            .logout-btn {
                padding: 6px 10px;
                font-size: 11px;
            }
        }

        @media (max-width: 768px) {
            .mobile-toggle-admin {
                display: block !important;
            }

            .navbar-container {
                padding: 0 10px;
            }

            .navbar-brand {
                font-size: 16px;
            }

            .navbar-brand span {
                display: none;
            }

            .navbar-brand img {
                width: 45px;
                height: 35px;
            }

            .nav-menu {
                display: none !important;
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                width: 100vw;
                max-width: 100%;
                flex-direction: column;
                gap: 0;
                margin-top: 0;
                background: #128241;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                z-index: 999;
            }

            .nav-menu.active {
                display: flex !important;
            }

            .nav-item {
                width: 100%;
            }

            .nav-link {
                text-align: left;
                border-radius: 0;
                padding: 12px 14px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                font-size: 12px;
            }

            .nav-link::after {
                display: none;
            }

            .nav-link.active {
                background: rgba(214, 223, 32, 0.1);
                border-left: 4px solid var(--secondary-color);
                padding-left: 10px;
            }

            .navbar-right {
                gap: 8px;
                position: absolute;
                right: 50px;
                top: 10px;
            }

            .admin-info {
                display: none;
            }

            .logout-btn {
                padding: 6px 10px;
                font-size: 11px;
                white-space: nowrap;
            }

            body {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .mobile-toggle-admin {
                font-size: 20px;
            }

            .navbar-container {
                padding: 0 8px;
            }

            .navbar-brand {
                font-size: 14px;
            }

            .navbar-brand img {
                width: 40px;
                height: 30px;
            }

            .nav-link {
                padding: 10px 12px;
                font-size: 11px;
            }

            .navbar-right {
                right: 45px;
                top: 12px;
            }

            .logout-btn {
                padding: 5px 8px;
                font-size: 10px;
            }

            body {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Admin Navbar -->
        <nav class="admin-navbar">
            <div class="navbar-container">
                <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
                    <img src="{{ asset('image/logo.png') }}" alt="GulmaTrack Logo">
                    <span>GulmaTrack</span>
                </a>

                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-map-location-dot"></i> Wilayah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.drone.index') }}" class="nav-link {{ request()->routeIs('admin.drone*') ? 'active' : '' }}">
                            <i class="fas fa-paper-plane"></i> Drone
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
                            <i class="fas fa-images"></i> Galeri
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('wilayah') }}" class="nav-link">
                            <i class="fas fa-globe"></i> Publik
                        </a>
                    </li>
                </ul>

                <div class="navbar-right">
                    <div class="admin-info">
                        <div class="admin-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <span>Administrator</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Admin Content -->
        <div class="admin-content fade-in">
            @yield('content')
        </div>
    </div>

    <script>
        // Smooth scroll for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>