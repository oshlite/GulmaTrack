<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | GulmaTrack</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --title-color: #A6CE39;
            --primary-color: #197B40;
            --secondary-color: #D6DF20;
            --accent-color: #FBA919;
            --accent-light: #25AAE2;
            --dark-color: #197B40;
            --light-color: #f5f5f5;
            --text-color: #333;
            --border-color: #e0e0e0;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Poppins';
            color: var(--text-color);
            background-color: #fff;
            line-height: 1.6;
        }

        /* Navbar Styling */
        nav {
            background: linear-gradient(135deg, var(--dark-color) 0%, #197B40 100%);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: var(--secondary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .logo:hover {
            color: var(--accent-light);
            transform: scale(1.05);
        }

        .logo i {
            font-size: 28px;
            margin-right: 8px;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 5px;
            flex-wrap: wrap;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            color: #ecf0f1;
            text-decoration: none;
            padding: 12px 18px;
            display: block;
            transition: all 0.3s ease;
            border-radius: 4px;
            font-size: 15px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--accent-color);
            transform: translateY(-1px);
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        /* Main Content */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid var(--primary-color);
            animation: slideInDown 0.5s ease;
        }

        .page-header h1 {
            font-size: 42px;
            color: var(--title-color);
            margin-bottom: 10px;
        }

        .page-header p {
            font-size: 16px;
            color: #666;
        }

        /* Footer */
        footer {
            background-color: var(--dark-color);
            color: #ecf0f1;
            padding: 40px 20px;
            margin-top: 60px;
            text-align: center;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: var(--accent-light);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        /* Animations */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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
            .navbar-container {
                flex-direction: column;
                height: auto;
                padding: 10px 20px;
            }

            .logo {
                margin-bottom: 15px;
            }

            .mobile-toggle {
                display: block;
                position: absolute;
                top: 15px;
                right: 20px;
            }

            .nav-menu {
                display: none;
                width: 100%;
                flex-direction: column;
                gap: 5px;
                margin-top: 10px;
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-link {
                text-align: center;
            }

            .page-header h1 {
                font-size: 28px;
            }

            .main-container {
                padding: 20px 15px;
            }
        }

        /* Utility Classes */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        .btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background-color: var(--accent-light);
        }

        .btn-secondary:hover {
            background-color: var(--accent-color);
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .text-center {
            text-align: center;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="logo">
                <i class="fas fa-leaf"></i>
                GulmaTrack
            </a>
            <button class="mobile-toggle" id="mobileToggle">☰</button>
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('wilayah') }}" class="nav-link {{ request()->routeIs('wilayah') ? 'active' : '' }}">Wilayah</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('nanas') }}" class="nav-link {{ request()->routeIs('nanas') ? 'active' : '' }}">Nanas</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pisang') }}" class="nav-link {{ request()->routeIs('pisang') ? 'active' : '' }}">Pisang</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('Kategori') }}" class="nav-link {{ request()->routeIs('Kategori') ? 'active' : '' }}">Kategori</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tenaga-kerja') }}" class="nav-link {{ request()->routeIs('tenaga-kerja') ? 'active' : '' }}">Tenaga Kerja</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('statistik') }}" class="nav-link {{ request()->routeIs('statistik') ? 'active' : '' }}">Statistik</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Tentang</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container fade-in">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="{{ route('about') }}">Tentang Kami</a>
                <a href="{{ route('contact') }}">Hubungi Kami</a>
                <a href="{{ route('statistik') }}">Statistik</a>
                <a href="{{ route('wilayah') }}">Peta Wilayah</a>
            </div>
            <p>&copy; 2025 GulmaTrack. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');

        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => {
                navMenu.classList.toggle('active');
            });

            // Close menu when a link is clicked
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    navMenu.classList.remove('active');
                });
            });
        }

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
