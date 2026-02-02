@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

<style>
    /* Hero Section */
    .hero-section {
        position: relative;
        width: 100%;
        min-height: 750px;
        background:
            linear-gradient(135deg, rgba(25,123,64,.65), rgba(13,92,46,.75)),
            url('https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=1600&fit=crop')
            center / cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        overflow: hidden;
        box-shadow: inset 0 -50px 100px rgba(0, 0, 0, 0.2);
        padding: 40px 20px;
        margin-top: -70px;
        padding-top: 110px;
    }

    .hero-content {
        max-width: 960px;
        animation: fadeInUp 0.8s ease;
        padding: 0 24px;
    }

    .hero-content h1 {
        font-size: 56px;
        font-weight: 800;
        margin-bottom: 25px;
        line-height: 1.2;
        text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.4);
        letter-spacing: -0.5px;
    }

    .hero-content p {
        font-size: 20px;
        margin-bottom: 40px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        font-weight: 300;
        line-height: 1.5;
    }

    .hero-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        align-items: center;
    }

    .btn-hero {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px 32px;
        border-radius: 28px;
        text-decoration: none;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        white-space: nowrap;
    }

    .btn-hero.primary {
        background-color: #A6CE39;
        color: white;
    }

    .btn-hero.primary:hover {
        background-color: #D6DF20;
        color: #333;
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(166, 206, 57, 0.5);
    }

    .btn-hero.secondary {
        background-color: transparent;
        color: white;
        border-color: white;
    }

    .btn-hero.secondary:hover {
        background-color: white;
        color: var(--primary-color);
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(255, 255, 255, 0.3);
    }

    .btn-hero.third {
        background-color: white;
        color: var(--primary-color);
        allign-items: center;
    }

    .btn-hero.third:hover {
        background-color: #f0f0f0;
        color: var(--secondary-color);
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(255, 255, 255, 0.4);
        allign-items: center;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Statistics Section */
    .stats-section {
        padding: 60px 40px;
        margin-right: auto;
        margin-left: auto;
        margin-bottom: 70px;
        max-width: 1400px;
    }

    .section-title {
        font-size: 40px;
        color: var(--text-color);
        text-align: center;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .section-subtitle {
        text-align: center;
        color: #666;
        margin-bottom: 50px;
        font-size: 18px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 30px;
    }

    .stat-card {
        background: white;
        padding: 35px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 5px solid var(--primary-color);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .stat-card.bersih {
        border-left-color: #57ce39ff;
        background: linear-gradient(135deg, rgba(18, 130, 65, 0.05), rgba(18, 130, 65, 0.02));
    }

    .stat-card.ringan {
        border-left-color: #F39C12;
        background: linear-gradient(135deg, rgba(243, 156, 18, 0.05), rgba(243, 156, 18, 0.02));
    }

    .stat-card.sedang {
        border-left-color: #E67E22;
        background: linear-gradient(135deg, rgba(230, 126, 34, 0.05), rgba(230, 126, 34, 0.02));
    }

    .stat-card.berat {
        border-left-color: #E74C3C;
        background: linear-gradient(135deg, rgba(231, 76, 60, 0.05), rgba(231, 76, 60, 0.02));
    }

    .stat-icon {
        font-size: 50px;
        margin-bottom: 20px;
    }

    .stat-icon.bersih {
        color: #57ce39ff;
    }

    .stat-icon.ringan {
        color: #F39C12;
    }

    .stat-icon.sedang {
        color: #E67E22;
    }

    .stat-icon.berat {
        color: #E74C3C;
    }

    .stat-label {
        font-size: 15px;
        color: #666;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .stat-value {
        font-size: 48px;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 8px;
    }

    .stat-description {
        font-size: 14px;
        color: #999;
    }

    /* Features Section */
    .features-section {
        margin-bottom: 70px;
        margin-right: 70px;
        margin-left: 70px;
    }

    .features-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .feature-card {
        background: white;
        padding: 45px 35px;
        border-radius: 40px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        text-align: center;
        min-height: 280px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: calc(33.333% - 30px);
        min-width: 300px;
    }

    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .feature-icon {
        font-size: 50px;
        margin-bottom: 20px;
        display: inline-block;
        width: 70px;
        height: 70px;
        line-height: 70px;
        border-radius: 30px;
        transition: all 0.3s ease;
    }

    .feature-card:nth-child(1) .feature-icon {
        background-color: rgba(220, 53, 69, 0.1);
        color: #DC3545;
    }

    .feature-card:nth-child(2) .feature-icon {
        background-color: rgba(230, 126, 34, 0.1);
        color: #E67E22;
    }

    .feature-card:nth-child(3) .feature-icon {
        background-color: rgba(52, 152, 219, 0.1);
        color: #3498DB;
    }

    .feature-card:nth-child(4) .feature-icon {
        background-color: rgba(155, 89, 182, 0.1);
        color: #9B59B6;
    }

    .feature-card:nth-child(5) .feature-icon {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .feature-card:hover .feature-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .feature-title {
        font-size: 20px;
        color: var(--text-color);
        margin-bottom: 12px;
        font-weight: 700;
    }

    .feature-description {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
    }

    /* CTA Section */
    .cta-section {
        background: #128241;
        padding: 80px 40px;
        text-align: center;
        color: white;
        max-width: 1400px;
        margin: 0 auto;
        border-radius: 40px;
    }

    .cta-section h2 {
        font-size: 44px;
        margin-bottom: 25px;
        font-weight: 800;
    }

    .cta-section p {
        font-size: 20px;
        margin-bottom: 40px;
        opacity: 0.95;
    }

    .cta-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
        align-items: center;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .hero-section {
            min-height: 500px;
            padding: 30px 20px;
            padding-top: 90px;
        }

        .hero-content h1 {
            font-size: 48px;
        }

        .hero-content p {
            font-size: 18px;
        }

        .btn-hero {
            padding: 12px 28px;
            font-size: 14px;
        }

        .section-title {
            font-size: 36px;
        }

        .section-subtitle {
            font-size: 16px;
            margin-bottom: 40px;
        }

        .stats-section,
        .features-section,
        .cta-section {
            padding: 60px 30px;
        }

        .stat-card {
            padding: 30px;
        }

        .stat-icon {
            font-size: 45px;
        }

        .stat-value {
            font-size: 42px;
        }

        .feature-card {
            padding: 35px 28px;
            min-height: 250px;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            line-height: 70px;
            font-size: 48px;
        }

        .cta-section h2 {
            font-size: 36px;
        }

        .cta-section p {
            font-size: 18px;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            min-height: 450px;
            padding: 20px 16px;
            padding-top: 80px;
        }

        .hero-content {
            padding: 0 15px;
        }

        .hero-content h1 {
            font-size: 36px;
            margin-bottom: 18px;
        }

        .hero-content p {
            font-size: 16px;
            margin-bottom: 25px;
        }

        .hero-buttons {
            gap: 10px;
            flex-direction: row;
            width: 100%;
            flex-wrap: wrap;
        }

        .btn-hero {
            padding: 11px 20px;
            font-size: 13px;
            width: auto;
            max-width: none;
            margin: 0;
        }

        .section-title {
            font-size: 28px;
        }

        .section-subtitle {
            font-size: 14px;
            margin-bottom: 30px;
        }

        .stats-section,
        .features-section,
        .cta-section {
            padding: 45px 20px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .stat-card {
            padding: 22px;
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .stat-label {
            font-size: 13px;
        }

        .stat-value {
            font-size: 36px;
        }

        .stat-description {
            font-size: 12px;
        }

        .features-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 100%;
        }

        .feature-card {
            padding: 30px;
            min-height: 240px;
            width: calc(50% - 20px);
            min-width: 250px;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            line-height: 60px;
            font-size: 42px;
            margin-bottom: 18px;
        }

        .feature-title {
            font-size: 18px;
            margin-bottom: 12px;
        }

        .feature-description {
            font-size: 13px;
        }

        .cta-section {
            padding: 40px 20px;
        }

        .cta-section h2 {
            font-size: 28px;
            margin-bottom: 18px;
        }

        .cta-section p {
            font-size: 15px;
            margin-bottom: 30px;
        }

        .cta-buttons {
            gap: 12px;
            flex-direction: column;
        }

        .btn-hero {
            width: 100%;
            max-width: 220px;
        }
    }

    @media (max-width: 480px) {
        .hero-section {
            min-height: 380px;
            padding: 15px 12px;
            padding-top: 70px;
        }

        .hero-content {
            padding: 0 10px;
        }

        .hero-content h1 {
            font-size: 28px;
            margin-bottom: 15px;
            line-height: 1.1;
        }

        .hero-content p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .hero-buttons {
            gap: 8px;
            flex-direction: row;
            flex-wrap: wrap;
        }

        .btn-hero {
            padding: 10px 18px;
            font-size: 12px;
            width: auto;
            max-width: none;
        }

        .section-title {
            font-size: 22px;
        }

        .section-subtitle {
            font-size: 12px;
            margin-bottom: 20px;
        }

        .stats-section,
        .features-section,
        .cta-section {
            padding: 30px 15px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .stat-card {
            padding: 18px;
            border-radius: 12px;
        }

        .stat-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 12px;
        }

        .stat-value {
            font-size: 28px;
        }

        .stat-description {
            font-size: 11px;
        }

        .feature-card {
            padding: 18px;
            border-radius: 12px;
        }

        .features-grid {
            grid-template-columns: 1fr;
            gap: 15px;
            max-width: 100%;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            line-height: 50px;
            font-size: 32px;
            margin-bottom: 12px;
        }

        .feature-title {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .feature-description {
            font-size: 12px;
            line-height: 1.5;
        }

        .cta-section {
            padding: 30px 15px;
        }

        .cta-section h2 {
            font-size: 22px;
            margin-bottom: 12px;
        }

        .cta-section p {
            font-size: 13px;
            margin-bottom: 20px;
        }

        .cta-buttons {
            gap: 10px;
            flex-direction: column;
        }

        .btn-hero {
            width: 100%;
            max-width: none;
        }
    }
</style>

<!-- Hero Section - Fullscreen -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Sistem Visualisasi Peta Persebaran Gulma</h1>
        <p>Monitoring dan analisis persebaran gulma pada perkebunan nanas dengan teknologi data peta interaktif, statistik, dan arsip drone</p>
        <div class="hero-buttons">
            <a href="{{ route('wilayah') }}" class="btn-hero third">
                <i class="fas fa-map-marker-alt"></i> Lihat Peta
            </a>
            <a href="{{ route('statistik') }}" class="btn-hero third">
                <i class="fas fa-chart-bar"></i> Lihat Statistik
            </a>
        </div>
    </div>
</section>

<!-- Scroll Content -->
<div class="scroll-content">

    <!-- Features Section -->
    <section class="features-section"><br>
        <h2 class="section-title">Fitur Unggulan</h2>
        <p class="section-subtitle">Solusi lengkap untuk monitoring dan analisis persebaran gulma</p>
        <div class="features-grid">
            <div class="feature-card" onclick="window.location.href='/wilayah'">
                <div class="feature-icon">
                    <i class="fas fa-map"></i>
                </div>
                <h3 class="feature-title">Peta Interaktif</h3>
                <p class="feature-description">Visualisasi geografis lengkap dengan detail wilayah produksi yang mudah dijelajahi</p>
            </div>

            <div class="feature-card" onclick="window.location.href='/statistik'">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">Dashboard Analitik</h3>
                <p class="feature-description">Analisis mendalam dengan statistik dan tren produksi yang update real-time</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="feature-title">Responsif & Mobile</h3>
                <p class="feature-description">Akses dari perangkat apapun dengan pengalaman pengguna yang sempurna</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h3 class="feature-title">Keamanan Data</h3>
                <p class="feature-description">Sistem keamanan berlapis untuk melindungi data perkebunan yang sensitif</p>
            </div>

            <div class="feature-card" onclick="window.location.href='/login'">
                <div class="feature-icon">
                    <i class="fas fa-sync"></i>
                </div>
                <h3 class="feature-title">Sinkronisasi Data</h3>
                <p class="feature-description">Sinkronisasi otomatis dengan berbagai sumber data perkebunan terpercaya</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>Mulai Monitoring Gulma Sekarang</h2>
        <p>Dapatkan insight lengkap tentang persebaran gulma di area perkebunan Anda</p>
        <div class="cta-buttons">
            <a href="{{ route('wilayah') }}" class="btn-hero secondary">
                <i class="fas fa-map-location-dot"></i> Jelajahi Wilayah
            </a>
            <a href="{{ route('statistik') }}" class="btn-hero secondary">
                <i class="fa-solid fa-chart-area"></i> Lihat Statistik Lengkap
            </a>
        </div>
    </section>
</div>

@endsection