<nav>
    <div class="navbar-container">
        <a href="{{ route('home') }}" class="logo" style="text-decoration:none;">
            <img src="{{ asset('image/logo.png') }}" alt="GulmaTrack Logo" class="logo-img">
            <span style="color:#FBA919 !important;">GulmaTrack</span>
        </a>

        <button class="mobile-toggle" id="mobileToggle">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="nav-menu" id="navMenu">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Beranda
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('wilayah') }}" class="nav-link {{ request()->routeIs('wilayah') ? 'active' : '' }}">
                    <i class="fas fa-map"></i> Wilayah
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('statistik') }}" class="nav-link {{ request()->routeIs('statistik') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Statistik
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                    <i class="fas fa-sign-in-alt"></i> Admin
                </a>
            </li>
        </ul>
    </div>
</nav>
