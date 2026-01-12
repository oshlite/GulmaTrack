@extends('layouts.app')

@section('title', 'Drone - Perencanaan Pengendalian Gulma')

@section('content')
<!-- Poppins Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
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

    body {
        font-family: 'Poppins';
        padding-top: 0;
    }

    /* Main Content */
    .main-content {
        padding: 40px 30px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Page Header */
    .page-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .page-title {
        font-size: 40px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .page-subtitle {
        font-size: 16px;
        color: #666;
        margin-bottom: 20px;
    }

    .page-description {
        font-size: 15px;
        color: #777;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Cards Grid */
    .drone-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .drone-card {
        background: white;
        border-radius: 16px;
        overflow: visible;
        box-shadow: var(--shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .drone-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-color);
    }

    .card-year {
        position: absolute;
        top: -12px;
        left: 20px;
        background: var(--accent-color);
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 14px;
        z-index: 10;
    }

    .card-header {
        background: white;
        padding: 40px 20px 20px 20px;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
    }

    .card-icon {
        font-size: 80px;
        margin-bottom: 15px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--text-color);
    }

    .card-badges {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 12px;
    }

    .badge {
        display: inline-block;
        background-color: var(--secondary-color);
        color: var(--text-color);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-location {
        background-color: var(--secondary-color);
    }

    .badge-gulma {
        background-color: var(--secondary-color);
    }

    .card-content {
        padding: 25px 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .drone-info {
        margin-bottom: 15px;
    }

    .info-label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 14px;
        color: var(--text-color);
        margin-top: 5px;
        font-weight: 500;
    }

    .drone-location {
        display: none;
    }

    .drone-location-label {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
    }

    .drone-location-value {
        font-size: 14px;
        color: var(--primary-color);
        font-weight: 600;
        margin-top: 3px;
    }

    .drone-date-badge {
        display: inline-block;
        background-color: rgba(212, 223, 32, 0.2);
        color: #5f7a1a;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .drone-percentage {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .percentage-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--primary-color);
        min-width: 50px;
    }

    .percentage-bar {
        flex: 1;
        height: 8px;
        background-color: #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
    }

    .percentage-fill {
        height: 100%;
        width: var(--percentage, 0%);
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .card-footer {
        padding: 20px;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 10px;
    }

    .btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-family: 'Poppins';
        cursor: pointer;
        transition: all 0.3s;
        font-size: 13px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-download {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-download:hover {
        background-color: #0f6334;
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-view {
        background-color: var(--secondary-color);
        color: #333;
    }

    .btn-view:hover {
        background-color: #c5ce1a;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
    }

    .empty-state-text {
        font-size: 15px;
        color: #666;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Footer */
    .page-footer {
        text-align: center;
        margin-top: 60px;
        padding-top: 40px;
        border-top: 1px solid var(--border-color);
        color: #999;
        font-size: 13px;
    }

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-section select {
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-family: 'Poppins';
        cursor: pointer;
        background-color: white;
    }

    .filter-section select:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 20px 15px;
        }

        .page-title {
            font-size: 28px;
        }

        .drone-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .page-header {
            margin-bottom: 30px;
        }
    }
</style>

<!-- Main Content -->
<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">📋 Drone - Perencanaan Pengendalian Gulma</h1>
        <p class="page-description">
            Dokumen perencanaan penggunaan drone untuk pengendalian gulma di berbagai wilayah. Download dan pelajari strategi perencanaan terbaru.
        </p>
    </div>

    <!-- Filter Section -->
    @if ($drones->count() > 0)
        <div class="filter-section">
            <select id="locationFilter" onchange="filterByLocation()">
                <option value="">📍 Semua Lokasi</option>
                @foreach ($drones->groupBy('lokasi') as $lokasi => $items)
                    <option value="{{ $lokasi }}">{{ $lokasi }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <!-- Cards Grid -->
    @if ($drones->count() > 0)
        <div class="drone-grid" id="droneGrid">
            @foreach ($drones as $drone)
                <div class="drone-card" data-location="{{ $drone->lokasi }}">
                    <div class="card-year">{{ $drone->tanggal_perencanaan->year }}</div>
                    
                    <div class="card-header">
                        <div class="card-icon">🛸</div>
                        <h2 class="card-title">{{ Str::limit($drone->judul, 30) }}</h2>
                        <div class="card-badges">
                            <span class="badge badge-location">{{ $drone->lokasi }}</span>
                            @if ($drone->persen_gulma !== null)
                                <span class="badge badge-gulma">{{ number_format($drone->persen_gulma, 1) }}% Gulma</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="drone-info">
                            <div class="info-label">📅 Tanggal Perencanaan</div>
                            <div class="info-value">{{ $drone->tanggal_perencanaan->translatedFormat('d MMMM Y') }}</div>
                        </div>

                        <div class="drone-info">
                            <div class="info-label">⏰ Tanggal Pembuatan</div>
                            <div class="info-value">{{ $drone->created_at->translatedFormat('d MMMM Y') }}</div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('drone.download', $drone->id) }}" class="btn btn-download">
                            📥 Downloas PDF
                        </a>
                        <button class="btn btn-view" onclick="alert('Detail view coming soon')">Detail</button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h2 class="empty-state-title">Belum ada dokumen drone</h2>
            <p class="empty-state-text">
                Dokumen perencanaan penggunaan drone akan ditampilkan di sini. Silakan kembali lagi nanti untuk melihat dokumen terbaru.
            </p>
        </div>
    @endif

    <!-- Footer -->
    <div class="page-footer">
        <p>© 2025 GULMATRACK - Sistem Informasi Pengendalian Gulma</p>
    </div>
</div>

<script>
    function filterByLocation() {
        const selectedLocation = document.getElementById('locationFilter').value;
        const cards = document.querySelectorAll('.drone-card');

        cards.forEach(card => {
            if (selectedLocation === '' || card.dataset.location === selectedLocation) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

@endsection
