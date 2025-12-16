@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-book"></i> Tentang GulmaTrack</h1>
    <p>Mengenal lebih jauh tentang platform GIS perkebunan kami</p>
</div>

<div class="container">
    <style>
        .about-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            line-height: 1.8;
        }

        .about-section h2 {
            color: var(--title-color);
            margin-bottom: 20px;
            font-size: 24px;
            border-bottom: 3px solid var(--title-color);
            padding-bottom: 15px;
        }

        .about-section p {
            color: #555;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .about-section ul {
            margin-left: 25px;
            margin-bottom: 15px;
        }

        .about-section ul li {
            margin-bottom: 10px;
            color: #555;
        }

        .vision-mission {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .vm-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            border-top: 5px solid var(--primary-color);
        }

        .vm-card h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 20px;
        }

        .vm-card p {
            color: #666;
            line-height: 1.8;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .feature-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .feature-card h4 {
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #666;
            font-size: 13px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .team-member {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            text-align: center;
            transition: all 0.3s ease;
        }

        .team-member:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .team-avatar {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
        }

        .team-info {
            padding: 20px;
        }

        .team-name {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .team-position {
            color: var(--primary-color);
            font-size: 13px;
            margin-bottom: 10px;
        }

        .team-desc {
            color: #666;
            font-size: 12px;
        }

        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 100%;
            background: var(--primary-color);
        }

        .timeline-item {
            margin-bottom: 30px;
            width: 48%;
        }

        .timeline-item:nth-child(odd) {
            margin-left: 0;
            margin-right: auto;
            text-align: right;
            padding-right: 30px;
        }

        .timeline-item:nth-child(even) {
            margin-left: auto;
            margin-right: 0;
            text-align: left;
            padding-left: 30px;
        }

        .timeline-dot {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 20px;
            background: white;
            border: 4px solid var(--primary-color);
            border-radius: 50%;
            top: 0;
        }

        .timeline-item:nth-child(odd) .timeline-dot {
            right: -30px;
        }

        .timeline-item:nth-child(even) .timeline-dot {
            left: -30px;
        }

        .timeline-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .timeline-year {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 16px;
            margin-bottom: 5px;
        }

        .timeline-desc {
            color: #666;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .timeline::before {
                left: 20px;
            }

            .timeline-item {
                width: 100%;
                padding-left: 60px !important;
                text-align: left !important;
                margin-right: 0 !important;
            }

            .timeline-item:nth-child(odd) .timeline-dot,
            .timeline-item:nth-child(even) .timeline-dot {
                left: -30px !important;
                right: auto !important;
            }
        }

        .technology-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .tech-item {
            background: var(--light-color);
            padding: 15px;
            border-radius: 4px;
            text-align: center;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .tech-item:hover {
            border-color: var(--primary-color);
            background: white;
        }

        .tech-icon {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .tech-name {
            font-weight: 600;
            color: var(--dark-color);
            font-size: 13px;
        }
    </style>

    <!-- Visi dan Misi -->
    <div class="vision-mission">
        <div class="vm-card">
            <h3><i class="fas fa-bullseye"></i> Visi</h3>
            <p>
                Menjadi platform informasi geografis perkebunan yang terdepan dan terpercaya dalam mendukung 
                peningkatan produktivitas dan efisiensi sektor hortikultura di Indonesia melalui teknologi 
                dan inovasi data-driven.
            </p>
        </div>
        <div class="vm-card">
            <h3><i class="fas fa-briefcase"></i> Misi</h3>
            <p>
                Menyediakan sistem informasi yang komprehensif, interaktif, dan mudah digunakan untuk membantu 
                petani, stakeholder, dan pemerintah dalam pengambilan keputusan yang tepat berbasis data geografis 
                dan analisis statistik real-time.
            </p>
        </div>
    </div>

    <!-- Tentang Platform -->
    <div class="about-section">
        <h2><i class="fas fa-leaf"></i> Tentang GulmaTrack</h2>
        <p>
            GulmaTrack adalah sebuah platform Sistem Informasi Geografis (GIS) yang inovatif dan responsif, 
            dirancang khusus untuk mendukung manajemen perkebunan hortikultura Indonesia yang lebih efisien dan 
            data-driven. Platform ini mengintegrasikan teknologi pemetaan geografis dengan sistem manajemen data 
            perkebunan untuk memberikan insight yang actionable kepada para stakeholder.
        </p>
        <p>
            Dengan fitur-fitur yang komprehensif dan antarmuka yang user-friendly, GulmaTrack memudahkan 
            pengguna untuk memantau, menganalisis, dan membuat keputusan strategis berdasarkan data real-time 
            dari berbagai wilayah produksi.
        </p>
    </div>

    <!-- Fitur Utama -->
    <div class="about-section">
        <h2><i class="fas fa-star"></i> Fitur Unggulan</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-map"></i></div>
                <h4>Peta Interaktif</h4>
                <p>Visualisasi geografis lengkap dengan detail wilayah produksi yang mudah dijelajahi</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-bar"></i></div>
                <h4>Dashboard Analitik</h4>
                <p>Analisis mendalam dengan statistik dan tren produksi yang update real-time</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <h4>Responsif & Mobile</h4>
                <p>Akses dari perangkat apapun dengan pengalaman pengguna yang sempurna</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-lock"></i></div>
                <h4>Keamanan Data</h4>
                <p>Sistem keamanan berlapis untuk melindungi data perkebunan yang sensitif</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                <h4>Performa Optimal</h4>
                <p>Loading cepat dan responsif dengan optimasi database terbaik</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-sync"></i></div>
                <h4>Sinkronisasi Data</h4>
                <p>Sinkronisasi otomatis dengan berbagai sumber data perkebunan terpercaya</p>
            </div>
        </div>
    </div>

    <!-- Teknologi yang Digunakan -->
    <div class="about-section">
        <h2><i class="fas fa-tools"></i> Teknologi & Stack</h2>
        <p>GulmaTrack dibangun menggunakan teknologi modern dan best practices terkini:</p>
        <div class="technology-list">
            <div class="tech-item">
                <div class="tech-icon"><i class="fab fa-php"></i></div>
                <div class="tech-name">Laravel PHP</div>
            </div>
            <div class="tech-item">
                <div class="tech-icon"><i class="fas fa-database"></i></div>
                <div class="tech-name">MySQL Database</div>
            </div>
            <div class="tech-item">
                <div class="tech-icon"><i class="fas fa-palette"></i></div>
                <div class="tech-name">Responsive CSS</div>
            </div>
            <div class="tech-item">
                <div class="tech-icon"><i class="fab fa-js-square"></i></div>
                <div class="tech-name">JavaScript</div>
            </div>
            <div class="tech-item">
                <div class="tech-icon"><i class="fas fa-map"></i></div>
                <div class="tech-name">Leaflet.js Maps</div>
            </div>
            <div class="tech-item">
                <div class="tech-icon"><i class="fas fa-chart-bar"></i></div>
                <div class="tech-name">Chart.js Library</div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="about-section">
        <h2><i class="fas fa-history"></i> Sejarah Pengembangan</h2>
        <div class="timeline">
            <div class="timeline-item" style="position: relative;">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <div class="timeline-year">2023 - Q1</div>
                    <div class="timeline-desc">Konsep awal dan riset pasar tentang kebutuhan GIS perkebunan</div>
                </div>
            </div>
            <div class="timeline-item" style="position: relative;">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <div class="timeline-year">2023 - Q3</div>
                    <div class="timeline-desc">Pengembangan prototype dan testing dengan stakeholder</div>
                </div>
            </div>
            <div class="timeline-item" style="position: relative;">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <div class="timeline-year">2024 - Q2</div>
                    <div class="timeline-desc">Peluncuran versi beta dengan integrasi data awal</div>
                </div>
            </div>
            <div class="timeline-item" style="position: relative;">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <div class="timeline-year">2025 - Sekarang</div>
                    <div class="timeline-desc">Versi production dan continuous improvement dengan user feedback</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tim -->
    <div class="about-section">
        <h2><i class="fas fa-users"></i> Tim Pengembang</h2>
        <div class="team-grid">
            <div class="team-member">
                <div class="team-avatar"><i class="fas fa-user-tie"></i></div>
                <div class="team-info">
                    <div class="team-name">Budi Hartono</div>
                    <div class="team-position">Project Lead & Backend Developer</div>
                    <div class="team-desc">Berpengalaman 8 tahun di pengembangan sistem informasi perkebunan</div>
                </div>
            </div>
            <div class="team-member">
                <div class="team-avatar"><i class="fas fa-user-secret"></i></div>
                <div class="team-info">
                    <div class="team-name">Siti Nurhaliza</div>
                    <div class="team-position">Frontend Developer & UX Designer</div>
                    <div class="team-desc">Spesialis dalam UI/UX dengan fokus pada user experience terbaik</div>
                </div>
            </div>
            <div class="team-member">
                <div class="team-avatar"><i class="fas fa-flask"></i></div>
                <div class="team-info">
                    <div class="team-name">Dr. Ahmad Wijaya</div>
                    <div class="team-position">GIS & Data Specialist</div>
                    <div class="team-desc">Ahli dalam sistem informasi geografis dan analisis data perkebunan</div>
                </div>
            </div>
            <div class="team-member">
                <div class="team-avatar"><i class="fas fa-leaf"></i></div>
                <div class="team-info">
                    <div class="team-name">Rina Kusuma</div>
                    <div class="team-position">Agricultural Consultant</div>
                    <div class="team-desc">Konsultan perkebunan dengan pengalaman langsung di lapangan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dukungan -->
    <div class="about-section" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
        <h2 style="color: white; border-bottom-color: rgba(255,255,255,0.3);"><i class="fas fa-handshake"></i> Dukungan & Kolaborasi</h2>
        <p style="color: rgba(255,255,255,0.9);">
            GulmaTrack didukung oleh berbagai instansi pemerintah, universitas terkemuka, dan asosiasi petani 
            yang berkomitmen untuk meningkatkan sektor perkebunan Indonesia. Kami terbuka untuk kolaborasi dan 
            partnership dengan pihak-pihak yang memiliki visi yang sama.
        </p>
        <p style="color: rgba(255,255,255,0.9);">
            Jika Anda tertarik untuk berkolaborasi atau memiliki pertanyaan lebih lanjut, silakan 
            <a href="{{ route('contact') }}" style="color: white; text-decoration: underline; font-weight: 600;">hubungi kami</a>.
        </p>
    </div>
</div>
@endsection
