<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GulmaTrack - Sistem Manajemen Hasil Pertanian</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- ===== HEADER ===== -->
    <header>
        <div class="container">
            <nav>
                <a href="/" class="logo">🌾 GulmaTrack</a>
                <ul class="nav-menu">
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="#cerita">Cerita</a></li>
                    <li><a href="#kontak">Kontak</a></li>
                </ul>
                <div class="nav-auth">
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-secondary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                        @endif
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <!-- ===== HERO SECTION ===== -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Kelola Hasil Pertanian Anda dengan Mudah</h1>
                <p>Platform terintegrasi untuk mengelola produksi pertanian, dari perencanaan hingga distribusi. Tingkatkan produktivitas dan transparansi rantai pasokan Anda.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary">Mulai Sekarang</a>
                    <a href="#tentang" class="btn btn-secondary">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TENTANG SECTION ===== -->
    <section class="section" id="tentang">
        <div class="container">
            <div class="section-title">
                <h2>Tentang GulmaTrack</h2>
                <p>Solusi modern untuk manajemen pertanian yang berkelanjutan</p>
            </div>

            <div class="content-section">
                <div class="content-text">
                    <h3>Dimulai dengan Misi Sederhana</h3>
                    <p>GulmaTrack didirikan dengan visi untuk menghadirkan teknologi digital ke sektor pertanian Indonesia. Kami percaya bahwa transparansi dan efisiensi dalam rantai pasokan pertanian dapat meningkatkan kesejahteraan petani dan kualitas produk.</p>
                    <p>Dengan pendekatan terintegrasi, kami membantu petani, pengolah, dan distributor untuk bekerja dalam harmoni, menciptakan siklus produksi yang berkelanjutan dan menguntungkan semua pihak.</p>
                    <a href="#fitur" class="btn btn-primary" style="margin-top: 1rem;">Lihat Fitur Kami</a>
                </div>
                <div class="image-wrapper">
                    <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad576?w=500&h=500&fit=crop" alt="Pertanian Modern">
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FITUR SECTION ===== -->
    <section class="section bg-light" id="fitur">
        <div class="container">
            <div class="section-title">
                <h2>Fitur Unggulan</h2>
                <p>Semua yang Anda butuhkan untuk mengelola pertanian modern</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Dashboard Analytics</h3>
                    <p>Pantau produksi, penjualan, dan performa pertanian Anda dalam satu dashboard yang komprehensif dan mudah dipahami.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🌱</div>
                    <h3>Manajemen Lahan</h3>
                    <p>Kelola data lahan, jenis tanaman, jadwal penanaman, dan pemanenan dengan sistem yang terstruktur dan efisien.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📦</div>
                    <h3>Tracking Produk</h3>
                    <p>Lacak produk dari lahan hingga ke tangan konsumen. Transparansi penuh dalam setiap tahap distribusi.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Kolaborasi Tim</h3>
                    <p>Bekerja sama dengan tim Anda dengan mudah. Bagikan data, komunikasikan rencana, dan koordinasikan aktivitas.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Manajemen Keuangan</h3>
                    <p>Kelola biaya produksi, harga penjualan, dan profit dengan laporan keuangan yang detail dan akurat.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Akses Mobile</h3>
                    <p>Akses platform GulmaTrack kapan saja, di mana saja, melalui perangkat mobile Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== STATS SECTION ===== -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>1000+</h3>
                    <p>Pengguna Aktif</p>
                </div>
                <div class="stat-item">
                    <h3>5000+</h3>
                    <p>Lahan Terpantau</p>
                </div>
                <div class="stat-item">
                    <h3>50+</h3>
                    <p>Jenis Tanaman</p>
                </div>
                <div class="stat-item">
                    <h3>100%</h3>
                    <p>Transparansi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== KEUNGGULAN SECTION ===== -->
    <section class="section">
        <div class="container">
            <div class="content-section">
                <div class="image-wrapper">
                    <img src="https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=500&h=500&fit=crop" alt="Teknologi Pertanian">
                </div>
                <div class="content-text">
                    <h3>Bekerja dalam Harmoni</h3>
                    <p>Sistem kami dirancang untuk memfasilitasi kerja sama yang harmonis antara semua stakeholder dalam rantai pasokan pertanian.</p>
                    <p>Dari petani lokal hingga distributor nasional, semua dapat mengakses informasi real-time yang sama, menciptakan ekosistem yang sehat dan transparan.</p>
                </div>
            </div>

            <div class="content-section">
                <div class="content-text">
                    <h3>Jaminan Kualitas</h3>
                    <p>Dengan pemantauan berkelanjutan sepanjang proses produksi, kami memastikan bahwa setiap produk memenuhi standar kualitas tertinggi.</p>
                    <p>Dari penanganan benih, perawatan tanaman, hingga pemanenan dan pengiriman, setiap langkah dapat dipantau dan didokumentasikan dengan detail.</p>
                </div>
                <div class="image-wrapper">
                    <img src="https://images.unsplash.com/photo-1488459716781-15818c5f0e89?w=500&h=500&fit=crop" alt="Kualitas Produk">
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CERITA SECTION ===== -->
    <section class="section bg-light" id="cerita">
        <div class="container">
            <div class="section-title">
                <h2>Cerita Kesuksesan</h2>
                <p>Kisah petani dan produsen yang sukses menggunakan GulmaTrack</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <h3 style="color: var(--light-green); font-size: 3rem; margin-bottom: 0;">👨‍🌾</h3>
                    <h3>Petani Sukses</h3>
                    <p>"Dengan GulmaTrack, saya bisa memantau 5 lahan saya dari satu dashboard. Produktivitas meningkat 40% dan saya bisa fokus pada kualitas."</p>
                    <p style="font-size: 0.9rem; color: #999; margin-top: 1rem;">- Budi Santoso, Petani Sayuran</p>
                </div>

                <div class="feature-card">
                    <h3 style="color: var(--light-green); font-size: 3rem; margin-bottom: 0;">🏭</h3>
                    <h3>Pengolah Produk</h3>
                    <p>"Manajemen inventori menjadi jauh lebih efisien. Saya tahu kapan stok buah segar siap, tanpa komunikasi berbelit-belit."</p>
                    <p style="font-size: 0.9rem; color: #999; margin-top: 1rem;">- Siti Nurhaliza, Pemilik Pabrik Pengolahan</p>
                </div>

                <div class="feature-card">
                    <h3 style="color: var(--light-green); font-size: 3rem; margin-bottom: 0;">🚚</h3>
                    <h3>Distributor</h3>
                    <p>"Tracking real-time membuat pengiriman lebih cepat dan aman. Pelanggan sangat puas dengan transparansi yang kami berikan."</p>
                    <p style="font-size: 0.9rem; color: #999; margin-top: 1rem;">- Ahmad Wijaya, Pemilik Distributor Makanan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA SECTION ===== -->
    <section class="section" style="background: linear-gradient(135deg, #2D5016 0%, #4CAF50 100%); color: white; text-align: center;">
        <div class="container">
            <h2 style="color: white;">Siap untuk Mengubah Bisnis Pertanian Anda?</h2>
            <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem; margin-bottom: 2rem;">Bergabunglah dengan ribuan pengguna GulmaTrack dan rasakan perbedaannya.</p>
            <a href="{{ route('register') }}" class="btn btn-primary" style="background-color: white; color: #2D5016;">Mulai Gratis Hari Ini</a>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>GulmaTrack</h4>
                    <p>Platform manajemen pertanian terintegrasi untuk membangun ekosistem pertanian yang berkelanjutan dan menguntungkan.</p>
                    <div class="social-links">
                        <a href="#" title="Facebook">f</a>
                        <a href="#" title="Twitter">𝕏</a>
                        <a href="#" title="Instagram">📷</a>
                        <a href="#" title="LinkedIn">in</a>
                    </div>
                </div>

                <div class="footer-section">
                    <h4>Produk</h4>
                    <ul>
                        <li><a href="#fitur">Fitur-Fitur</a></li>
                        <li><a href="#harga">Harga</a></li>
                        <li><a href="#demo">Demo Gratis</a></li>
                        <li><a href="#api">API Documentation</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Perusahaan</h4>
                    <ul>
                        <li><a href="#tentang">Tentang Kami</a></li>
                        <li><a href="#blog">Blog</a></li>
                        <li><a href="#karir">Karir</a></li>
                        <li><a href="#kontak">Hubungi Kami</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Dukungan</h4>
                    <ul>
                        <li><a href="#help">Pusat Bantuan</a></li>
                        <li><a href="#faq">FAQ</a></li>
                        <li><a href="#privacy">Kebijakan Privasi</a></li>
                        <li><a href="#terms">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 GulmaTrack. Semua hak dilindungi. Dibangun dengan ❤️ untuk kemajuan pertanian Indonesia.</p>
            </div>
        </div>
    </footer>
</body>
</html>
