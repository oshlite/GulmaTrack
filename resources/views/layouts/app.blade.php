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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins'
        }

        :root {
            --title-color: #A6CE39;
            --primary-color: #128241;
            --secondary-color: #D6DF20;
            --accent-color: #FBA919;
            --accent-light: #FBA919;
            --dark-color: #128241;
            --light-color: #f5f5f5;
            --text-color: #333;
            --border-color: #e0e0e0;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            font-family: 'Poppins';
            color: var(--text-color);
            background-color: #fff;
            line-height: 1.6;
            scroll-behavior: smooth;
            padding-top: 70px;
        }

        /* Navbar Styling - Modern */
        nav {
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

        .logo {
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

        .logo:hover {
            transform: scale(1.05);
        }

        .logo i {
            font-size: 28px;
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--title-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-img {
            width: 80px;
            height: 60px;
            object-fit: contain;
            transition: all 0.3s ease;
        }

        .logo:hover .logo-img {
            transform: rotate(1deg) scale(1);
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
            color: var(--light-color);
        }

        .nav-link.active {
            color: var(--secondary-color);
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 5px;
        }

        .mobile-toggle:hover {
            color: var(--secondary-color);
        }

        /* Main Content */
        .main-container {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
            min-height: auto;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 40px;
            padding: 40px 20px 30px;
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
        .footer-wave {
            width: 100%;
            height: auto;
            position: relative;
            overflow: hidden;
            line-height: 0;
        }

        .footer-wave img {
            width: 100%;
            height: auto;
            display: block;
        }

        footer {
            background-color: var(--dark-color);
            color: #ecf0f1;
            padding: 0px 20px;
            margin-top: 0;
            text-align: center;
            border-top: 0;
            width: 100%;
        }

        .footer-content {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
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
            font-weight: 600;
        }

        .footer-links a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .footer-contact {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icon {
            color: var(--accent-light);
            font-size: 20px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-icon:hover {
            color: var(--secondary-color);
            transform: translateY(-2px);
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
                padding: 0 15px;
            }

            .logo {
                font-size: 20px;
            }

            .mobile-toggle {
                display: block;
            }

            .nav-menu {
                display: none;
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                width: 100vw;
                flex-direction: column;
                gap: 0;
                margin-top: 0;
                background: #128241;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-link {
                text-align: left;
                border-radius: 0;
                padding: 15px 20px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .nav-link::after {
                display: none;
            }

            .nav-link.active {
                background: rgba(214, 223, 32, 0.1);
                border-left: 4px solid var(--secondary-color);
                padding-left: 16px;
            }

            .page-header h1 {
                font-size: 28px;
            }

            body {
                padding-top: 70px;
            }
        }

        /* Utility Classes */
        .container {
            width: 100%;
            max-width: 100%;
            margin: 0;
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
            color: var(--text-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background-color: var(--accent-light);
        }

        .btn-secondary:hover {
            background-color: var(--accent-color);
            color: white;
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
    @include('partials.navbar')

    <!-- Main Content -->
    <div class="main-container fade-in">
        @yield('content')
    </div>

    @include('partials.footer')

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
