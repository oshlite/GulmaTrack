<!-- Admin Navbar -->
<nav class="admin-navbar">
    <div class="navbar-container">
        <!-- Brand/Logo -->
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand" style="text-decoration: none;">
            <img src="{{ asset('image/logo.png') }}" alt="GulmaTrack Logo" class="navbar-logo">
            <span>GulmaTrack</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="mobile-toggle" id="adminMobileToggle">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Navigation Menu -->
        <ul class="nav-menu" id="adminNavMenu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-map-location-dot"></i> Wilayah
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i> Galeri
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.drone.index') }}" class="nav-link {{ request()->routeIs('admin.drone*') ? 'active' : '' }}">
                    <i class="fas fa-paper-plane"></i> Drone
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('wilayah') }}" class="nav-link">
                    <i class="fas fa-globe"></i> Publik
                </a>
            </li>
            <li class="nav-item mobile-only">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link logout-mobile">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>

        <!-- Right Section: User Info & Logout -->
        <div class="navbar-right">
            <div class="admin-info">
                <div class="admin-avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <span class="admin-name">{{ Auth::user()->name }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
    /* Poppins Font */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

    :root {
        --primary-color: #128241;
        --secondary-color: #D6DF20;
        --accent-color: #FBA919;
        --light-bg: #f8f9fa;
        --text-color: #333;
        --border-color: #e0e0e0;
        --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);
        --light-color: #ecf0f1;
        --dark-color: #2c3e50;
    }

    /* Admin Navbar Styling - Konsisten dengan Guest Navbar */
    .admin-navbar {
        background: var(--primary-color);
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
        flex-wrap: wrap;
        gap: 20px;
    }

    /* Navbar Brand */
    .navbar-brand {
        font-size: 24px;
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        color: var(--accent-color);
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
        color: var(--accent-color);
    }

    .navbar-logo {
        width: 70px;
        height: 50px;
        object-fit: contain;
        transition: all 0.3s ease;
    }

    .navbar-brand:hover .navbar-logo {
        transform: rotate(1deg) scale(1);
    }

    .navbar-brand span {
        color: var(--accent-color) !important;
        font-family: 'Poppins', sans-serif;
    }

    /* Navigation Menu */
    .nav-menu {
        display: flex;
        list-style: none;
        gap: 2px;
        flex-wrap: nowrap;
        margin: 0;
        align-items: center;
        flex: 0 1 auto;
        justify-content: center;
    }

    .nav-item {
        position: relative;
        white-space: nowrap;
    }

    .mobile-only {
        display: none;
    }

    .nav-link {
        color: var(--light-color);
        text-decoration: none;
        padding: 10px 18px;
        display: block;
        transition: all 0.3s ease;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0.3px;
        position: relative;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
    }

    .nav-link i {
        margin-right: 6px;
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

    /* Dropdown Menu */
    .dropdown {
        position: relative;
    }

    .dropdown-toggle::after {
        font-size: 12px;
        margin-left: 6px;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        background: #0d5c35;
        border-radius: 8px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        min-width: 200px;
        padding: 8px 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        margin-top: 10px;
        z-index: 1001;
    }

    .nav-item.dropdown:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: block;
        color: var(--light-color);
        text-decoration: none;
        padding: 12px 20px;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
    }

    .dropdown-item:hover {
        background: rgba(214, 223, 32, 0.1);
        color: var(--secondary-color);
        padding-left: 24px;
    }

    /* Right Section */
    .navbar-right {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-shrink: 0;
    }

    .admin-info {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: var(--light-color);
        font-size: 13px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        white-space: nowrap;
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
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        flex-shrink: 0;
    }

    .admin-name {
        display: inline;
    }

    /* Logout Button */
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
        font-family: 'Poppins', sans-serif;
        letter-spacing: 0.3px;
        box-shadow: 0 2px 6px rgba(231, 76, 60, 0.3);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .nav-item form {
        margin: 0;
        width: 100%;
    }

    .logout-mobile {
        background: none;
        border: none;
        padding: 12px 18px;
        width: 100%;
        text-align: left;
    }

    .logout-btn:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(231, 76, 60, 0.4);
    }

    .logout-btn i {
        margin-right: 6px;
    }

    /* Mobile Toggle Button */
    .mobile-toggle {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 8px;
        flex-shrink: 0;
    }

    .mobile-toggle:hover {
        color: var(--secondary-color);
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .navbar-container {
            gap: 10px;
        }

        .navbar-brand {
            font-size: 20px;
        }

        .navbar-logo {
            width: 60px;
            height: 45px;
        }

        .mobile-toggle {
            display: block;
            order: 3;
        }

        .nav-menu {
            position: absolute;
            top: 70px;
            left: 0;
            right: 0;
            background: var(--primary-color);
            flex-direction: column;
            gap: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            width: 100%;
            border-bottom: 2px solid var(--secondary-color);
        }

        .nav-menu.active {
            max-height: 500px;
        }

        .nav-item {
            width: 100%;
        }

        .nav-link {
            border-radius: 0;
            padding: 15px 20px;
            font-size: 14px;
        }

        .nav-link::after {
            display: none;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            display: none;
        }

        .dropdown-menu {
            position: static;
            opacity: 0;
            visibility: hidden;
            max-height: 0;
            overflow: hidden;
            background: #0d5c35;
            margin-top: 0;
            box-shadow: none;
            transform: none;
            transition: max-height 0.3s ease;
        }

        .nav-item.dropdown:hover .dropdown-menu,
        .nav-item.dropdown.active .dropdown-menu {
            opacity: 1;
            visibility: visible;
            max-height: 300px;
        }

        .mobile-only {
            display: block;
        }

        .dropdown-item {
            padding-left: 40px;
            font-size: 13px;
        }

        .navbar-right {
            display: none;
        }

        .admin-info {
            display: none;
        }

        .admin-name {
            display: none;
        }

        .logout-btn {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .navbar-brand span {
            display: none;
        }

        .navbar-brand {
            gap: 0;
        }

        .navbar-logo {
            width: 50px;
            height: 40px;
        }

        .nav-link {
            font-size: 13px;
            padding: 12px 16px;
        }

        .admin-info {
            flex-direction: column;
            gap: 8px;
        }

        .admin-name {
            display: none;
        }

        .admin-avatar {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileToggle = document.getElementById('adminMobileToggle');
        const navMenu = document.getElementById('adminNavMenu');

        if (mobileToggle) {
            mobileToggle.addEventListener('click', function() {
                navMenu.classList.toggle('active');
            });
        }

        // Close menu when clicking on a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
            });
        });

        // Dropdown toggle for mobile
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    this.parentElement.classList.toggle('active');
                }
            });
        });
    });
</script>
