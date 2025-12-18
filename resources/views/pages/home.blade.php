@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-leaf"></i> Selamat Datang di GulmaTrack</h1>
    <p>Sistem Informasi Geografis Manajemen perkebunan Hortikultura</p>
</div>

<div class="container">
    <div class="hero-section text-center mb-20" style="margin-bottom: 60px;">
        <p style="font-size: 18px; color: #555; margin-bottom: 30px;">
            GulmaTrack adalah platform interaktif yang menyediakan informasi komprehensif tentang produksi 
            hortikultura (Nanas dan Pisang) di berbagai wilayah. Pantau data real-time, analisis statistik, 
            dan kelola tenaga kerja dengan mudah melalui dashboard terpadu kami.
        </p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('wilayah') }}" class="btn"><i class="fas fa-map-marker-alt"></i> Jelajahi Wilayah</a>
            <a href="{{ route('statistik') }}" class="btn btn-secondary"><i class="fas fa-chart-bar"></i> Lihat Statistik</a>
        </div>
    </div>

    <h2 style="font-size: 28px; margin: 50px 0 30px; text-align: center; color: var(--title-color);">
        Fitur Utama
    </h2>

    <div class="grid">
        <div class="card">
            <h3 style="color: var(--title-color); margin-bottom: 15px; font-size: 20px;"><i class="fas fa-map" style="margin-right: 8px;"></i>Peta Wilayah</h3>
            <p>Visualisasi geografis lengkap dari semua wilayah produksi dengan data terkini. Identifikasi potensi perkebunan di setiap region.</p>
            <a href="{{ route('wilayah') }}" class="btn" style="margin-top: 15px; display: inline-block;">Buka Wilayah</a>
        </div>

        <div class="card">
            <h3 style="color: var(--title-color); margin-bottom: 15px; font-size: 20px;"><i class="fas fa-apple-alt" style="margin-right: 8px;"></i>Data Nanas</h3>
            <p>Pantau produksi, luas lahan, dan produktivitas nanas di berbagai wilayah dengan detail informasi yang akurat.</p>
            <a href="{{ route('nanas') }}" class="btn" style="margin-top: 15px; display: inline-block;">Lihat Data Nanas</a>
        </div>

        <div class="card">
            <h3 style="color: var(--title-color); margin-bottom: 15px; font-size: 20px;"><i class="fas fa-leaf" style="margin-right: 8px;"></i>Data Pisang</h3>
            <p>Akses data lengkap tentang produksi, area tanam, dan hasil panen pisang dengan analisis mendalam.</p>
            <a href="{{ route('pisang') }}" class="btn" style="margin-top: 15px; display: inline-block;">Lihat Data Pisang</a>
        </div>

        <div class="card">
            <h3 style="color: var(--title-color); margin-bottom: 15px; font-size: 20px;"><i class="fas fa-bars" style="margin-right: 8px;"></i>Statistik</h3>
            <p>Analisis mendalam tentang tren produksi, pertumbuhan, dan perbandingan data antar wilayah.</p>
            <a href="{{ route('statistik') }}" class="btn" style="margin-top: 15px; display: inline-block;">Lihat Statistik</a>
        </div>
    </div>

    <div style="background: var(--primary-color);
                color: white; padding: 40px; border-radius: 8px; margin: 50px 0; text-align: center;">
        <h2 style="margin-bottom: 15px; color: var(--secondary-color);">Bergabunglah dengan Platform GulmaTrack</h2>
        <p style="margin-bottom: 20px; font-size: 16px; color: white;">
            Dapatkan akses ke data perkebunan terlengkap dan toolkit manajemen yang powerful untuk mengoptimalkan hasil panen Anda.
        </p>
        <a href="{{ route('contact') }}" class="btn" style="background-color: var(--accent-color); color: white;">
            <i class="fas fa-envelope" style="margin-right: 8px;"></i>Hubungi Kami Sekarang
        </a>
    </div>
</div>
@endsection
