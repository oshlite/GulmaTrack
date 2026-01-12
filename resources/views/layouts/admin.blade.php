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
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .navbar-brand {
            font-size: 25px;
            font-family: 'Poppins';
            font-weight: 800;
            color: #FBA919;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
            color: #FBA919;
        }

        .navbar-brand img {
            width: 85px;
            height: 60px;
            object-fit: contain;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: rotate(1deg) scale(1);
        }

        .navbar-brand span {
            color: #FBA919 !important;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2px;
            flex-wrap: nowrap;
            margin: 0;
        }

        .nav-item {
            position: relative;
            white-space: nowrap;
        }

        .nav-link {
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            transition: all 0.3s ease;
            border-radius: 6px;
            font-size: 14px;
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
            gap: 15px;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #ecf0f1;
            font-size: 14px;
            font-weight: 600;
        }

        .admin-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: 700;
            font-family: 'Poppins';
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .logout-btn {
            padding: 8px 16px;
            background: #E74C3C;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 13px;
            font-family: 'Poppins';
            letter-spacing: 0.3px;
            box-shadow: 0 2px 6px rgba(231, 76, 60, 0.3);
        }

        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.4);
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
        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }

            .navbar-container {
                flex-wrap: wrap;
            }

            .nav-menu {
                gap: 0;
            }

            .nav-link {
                padding: 10px 12px;
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
                            <i class="fas fa-chart-line"></i> Wilayah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.drone.index') }}" class="nav-link {{ request()->routeIs('admin.drone*') ? 'active' : '' }}">
                            <i class="fas fa-cube"></i> Drone
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