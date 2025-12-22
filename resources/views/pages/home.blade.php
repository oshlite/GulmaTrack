@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

<style>
    /* Hero Section */
    .hero-section {
        position: relative;
        width: 100%;
        min-height: 720px; 
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
}

    .hero-content {
        max-width: 960px;
        animation: fadeInUp 0.8s ease;
        padding: 0 24px;
    }

    .hero-content h1 {
        font-size: 64px;
        font-weight: 800;
        margin-bottom: 25px;
        line-height: 1.1;
        text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.4);
        letter-spacing: -0.5px;
    }

    .hero-content p {
        font-size: 22px;
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
    }

    .btn-hero {
        display: inline-block;
        padding: 16px 40px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        font-size: 18px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .btn-hero.primary {
        background-color: #A6CE39;
        color: white;
    }

    .btn-hero.primary:hover {
        background-color: #D6DF20;
        color: #333;
        transform: translateY(-3px);
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
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(255, 255, 255, 0.3);
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
        margin-right: 70px;
        margin-left: 70px;
        margin-bottom: 70px;
    }

    .section-title {
        font-size: 32px;
        color: var(--text-color);
        text-align: center;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .section-subtitle {
        text-align: center;
        color: #666;
        margin-bottom: 40px;
        font-size: 16px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
    }

    .stat-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
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
        border-left-color: #27AE60;
        background: linear-gradient(135deg, rgba(39, 174, 96, 0.05), rgba(39, 174, 96, 0.02));
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
        font-size: 40px;
        margin-bottom: 15px;
    }

    .stat-icon.bersih {
        color: #27AE60;
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
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .stat-value {
        font-size: 42px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 5px;
    }

    .stat-description {
        font-size: 13px;
        color: #999;
    }

    /* Features Section */
    .features-section {
        margin-bottom: 70px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
    }

    .feature-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        text-align: center;
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
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .feature-card:nth-child(1) .feature-icon {
        background-color: rgba(39, 174, 96, 0.1);
        color: #27AE60;
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
        background-color: rgba(41, 128, 185, 0.1);
        color: #2980B9;
    }

    .feature-card:nth-child(6) .feature-icon {
        background-color: rgba(230, 126, 34, 0.1);
        color: #E67E22;
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
        background: linear-gradient(135deg, var(--primary-color) 0%, #0D5C2E 100%);
        padding: 60px 40px;
        border-radius: 12px;
        text-align: center;
        color: white;
        margin-bottom: 70px;
    }

    .cta-section h2 {
        font-size: 36px;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .cta-section p {
        font-size: 18px;
        margin-bottom: 30px;
        opacity: 0.95;
    }

    .cta-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            min-height: 600px;
        }

        .hero-content {
            padding: 0 25px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
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
        font-size: 45px;
        margin-bottom: 20px;
    }

    .stat-icon.bersih {
        color: #27AE60;
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
        padding: 80px 20px;
        max-width: 1400px;
        margin: 0 auto;
        background: #f9f9f9;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .feature-card {
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        text-align: center;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }

    .feature-icon {
        font-size: 56px;
        margin-bottom: 25px;
        display: inline-block;
        width: 80px;
        height: 80px;
        line-height: 80px;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .feature-card:nth-child(1) .feature-icon {
        background-color: rgba(39, 174, 96, 0.1);
        color: #27AE60;
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
        background-color: rgba(41, 128, 185, 0.1);
        color: #2980B9;
    }

    .feature-card:hover .feature-icon {
        transform: scale(1.15) rotate(5deg);
    }

    .feature-title {
        font-size: 22px;
        color: var(--text-color);
        margin-bottom: 15px;
        font-weight: 700;
    }

    .feature-description {
        font-size: 15px;
        color: #666;
        line-height: 1.7;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0D5C2E 100%);
        padding: 80px 40px;
        text-align: center;
        color: white;
        max-width: 1400px;
        margin: 0 auto;
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
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .hero-content h1 {
            font-size: 56px;
        }

        .hero-content p {
            font-size: 20px;
        }

        .btn-hero {
            padding: 14px 35px;
            font-size: 16px;
        }

        .section-title {
            font-size: 36px;
        }

        .stats-section,
        .features-section,
        .cta-section {
            padding: 60px 20px;
        }
    }

    @media (max-width: 768px) {
        .hero-content {
            padding: 0 20px;
        }

        .hero-content h1 {
            font-size: 42px;
            margin-bottom: 20px;
        }

        .hero-content p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .hero-buttons {
            gap: 12px;
        }

        .btn-hero {
            padding: 12px 24px;
            font-size: 15px;
            width: 100%;
            max-width: 280px;
        }

        .section-title {
            font-size: 28px;
        }

        .section-subtitle {
            font-size: 15px;
            margin-bottom: 30px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .stat-card {
            padding: 25px;
        }

        .stat-value {
            font-size: 36px;
        }

        .features-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .feature-card {
            padding: 30px 20px;
        }

        .cta-section {
            padding: 50px 20px;
        }

        .cta-section h2 {
            font-size: 28px;
        }

        .cta-section p {
            font-size: 16px;
        }

        .stats-section,
        .features-section,
        .cta-section {
            padding: 50px 20px;
        }
    }

    @media (max-width: 480px) {
        .hero-section {
            top: 70px;
        }

        .hero-content h1 {
            font-size: 32px;
        }

        .hero-content p {
            font-size: 16px;
        }

        .stat-value {
            font-size: 28px;
        }

        .section-title {
            font-size: 24px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Section - Fullscreen -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Sistem Visualisasi Penyebaran Gulma</h1>
        <p>Monitoring dan analisis penyebaran gulma pada pertanian nanas dan pisang dengan teknologi data interaktif</p>
        <div class="hero-buttons">
            <a href="{{ route('wilayah') }}" class="btn-hero primary">
                <i class="fas fa-map-marker-alt"></i> Lihat Peta
            </a>
            <a href="{{ route('statistik') }}" class="btn-hero secondary">
                <i class="fas fa-chart-bar"></i> Lihat Statistik
            </a>
        </div>
    </div>
</section>

<!-- Scroll Content -->
<div class="scroll-content">

    <!-- Features Section -->
    <section class="features-section">
        <h2 class="section-title">Fitur Unggulan</h2>
        <p class="section-subtitle">Solusi lengkap untuk monitoring dan analisis penyebaran gulma</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-map"></i>
                </div>
                <h3 class="feature-title">Peta Interaktif</h3>
                <p class="feature-description">Visualisasi geografis lengkap dengan detail wilayah produksi yang mudah dijelajahi</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">Dashboard Analitik</h3>
                <p class="feature-description">Analisis mendalam dengan statistik dan tren produksi yang update real-time</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3 class="feature-title">Responsif & Mobile</h3>
                <p class="feature-description">Akses dari perangkat apapun dengan pengalaman pengguna yang sempurna</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-filter"></i>
                </div>
                <h3 class="feature-title">Keamanan Data</h3>
                <p class="feature-description">Sistem keamanan berlapis untuk melindungi data perkebunan yang sensitif</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-download"></i>
                </div>
                <h3 class="feature-title">Sinkronisasi Data</h3>
                <p class="feature-description">Sinkronisasi otomatis dengan berbagai sumber data perkebunan terpercaya</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>Mulai Monitoring Gulma Sekarang</h2>
        <p>Dapatkan insight lengkap tentang penyebaran gulma di area pertanian Anda</p>
        <div class="cta-buttons">
            <a href="{{ route('wilayah') }}" class="btn-hero primary">
                <i class="fas fa-map-location-dot"></i> Jelajahi Wilayah
            </a>
            <a href="{{ route('statistik') }}" class="btn-hero secondary">
                <i class="fas fa-envelope"></i> Lihat Statistik Lengkap
            </a>
        </div>
    </section>
</div>

@endsection