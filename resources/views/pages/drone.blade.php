@extends('layouts.app')

@section('title', 'Drone')

@section('content')
<!-- Poppins Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fa-solid fa-paper-plane"></i> Drone Pengendalian Gulma</h1>
        <p>
            Dokumen perencanaan penggunaan drone untuk pengendalian gulma di berbagai wilayah. Download dan pelajari strategi perencanaan terbaru.
        </p>
    </div>

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
        padding-top: 70px;
    }

    /* Page Header */
    .page-header {
        margin-top: 0;
        padding-top: 40px;
    }

    /* Main Content */
    .main-content {
        padding: 40px 30px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }


    /* Cards Grid */
    .drone-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    .drone-card {
        background: white;
        border-radius: 12px;
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
        padding: 8px 16px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 12px;
        z-index: 10;
    }

    .card-header {
        background: white;
        padding: 30px 20px 15px;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
    }

    .card-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        margin-bottom: 15px;
        border-radius: 6px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--text-color);
    }

    .card-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 10px;
    }

    .badge {
        display: inline-block;
        background-color: var(--secondary-color);
        color: var(--text-color);
        padding: 5px 12px;
        border-radius: 16px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-location {
        background-color: var(--primary-color);
        color: white;
    }

    .badge-gulma {
        background-color: var(--primary-color);
        color: white;
    }

    .card-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .drone-info {
        margin-bottom: 12px;
    }

    .info-label {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 13px;
        color: var(--text-color);
        margin-top: 4px;
        font-weight: 500;
    }

    .card-footer {
        padding: 15px;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 8px;
    }

    .btn {
        flex: 1;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-family: 'Poppins';
        cursor: pointer;
        transition: all 0.3s;
        font-size: 12px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
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
        padding: 80px 20px;
    }

    .empty-state-icon {
        font-size: 60px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
    }

    .empty-state-text {
        font-size: 14px;
        color: #666;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-section select {
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-family: 'Poppins';
        cursor: pointer;
        background-color: white;
        font-size: 13px;
    }

    .filter-section select:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        border-radius: 8px;
        width: 95%;
        max-width: 1000px;
        height: 85vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-lg);
    }

    .modal-header {
        background-color: var(--primary-color);
        color: white;
        padding: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .modal-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        padding: 0;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .modal-close-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .modal-body {
        flex: 1;
        overflow: auto;
        padding: 15px;
    }

    .pdf-viewer {
        width: 100%;
        height: 100%;
        border: none;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .main-content {
            padding: 30px 20px;
        }

        .drone-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .card-header {
            padding: 25px 15px 12px;
        }

        .card-image {
            height: 160px;
        }

        .card-title {
            font-size: 15px;
        }

        .card-content {
            padding: 15px;
        }

        .card-footer {
            padding: 12px;
            gap: 6px;
        }

        .btn {
            padding: 9px 14px;
            font-size: 11px;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 20px 12px;
        }

        .page-header {
            padding: 20px 12px;
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 22px;
        }

        .page-header p {
            font-size: 12px;
        }

        .filter-section {
            padding: 15px;
            margin-bottom: 20px;
        }

        .filter-section select {
            padding: 8px 10px;
            font-size: 12px;
            width: 100%;
        }

        .drone-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .card-header {
            padding: 20px 15px 10px;
        }

        .card-image {
            height: 150px;
        }

        .card-year {
            left: 15px;
            padding: 6px 12px;
            font-size: 11px;
        }

        .card-title {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .badge {
            padding: 4px 10px;
            font-size: 10px;
        }

        .card-content {
            padding: 12px;
        }

        .drone-info {
            margin-bottom: 8px;
        }

        .info-label {
            font-size: 10px;
        }

        .info-value {
            font-size: 12px;
        }

        .card-footer {
            padding: 10px;
        }

        .btn {
            padding: 8px 12px;
            font-size: 11px;
        }

        .empty-state {
            padding: 50px 15px;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .empty-state-title {
            font-size: 18px;
        }

        .empty-state-text {
            font-size: 13px;
        }

        .modal-content {
            width: 95%;
            height: 80vh;
        }

        .modal-header h2 {
            font-size: 16px;
        }

        .modal-body {
            padding: 10px;
        }
    }

    @media (max-width: 480px) {
        .main-content {
            padding: 15px 10px;
        }

        .page-header {
            padding: 15px 10px;
            margin-bottom: 15px;
        }

        .page-header h1 {
            font-size: 18px;
        }

        .page-header p {
            font-size: 11px;
        }

        .filter-section {
            padding: 12px;
            margin-bottom: 15px;
            flex-direction: column;
        }

        .filter-section select {
            width: 100%;
            padding: 8px;
            font-size: 11px;
        }

        .drone-grid {
            gap: 15px;
        }

        .card-header {
            padding: 18px 12px 8px;
        }

        .card-image {
            height: 120px;
            margin-bottom: 10px;
        }

        .card-year {
            left: 12px;
            top: -10px;
            padding: 5px 10px;
            font-size: 10px;
        }

        .card-title {
            font-size: 13px;
        }

        .badge {
            padding: 3px 8px;
            font-size: 9px;
        }

        .card-content {
            padding: 10px;
        }

        .drone-info {
            margin-bottom: 6px;
        }

        .info-label {
            font-size: 9px;
        }

        .info-value {
            font-size: 11px;
        }

        .card-footer {
            padding: 8px;
            gap: 5px;
        }

        .btn {
            padding: 7px 10px;
            font-size: 10px;
        }

        .btn i {
            display: none;
        }

        .empty-state {
            padding: 40px 12px;
        }

        .empty-state-icon {
            font-size: 40px;
        }

        .empty-state-title {
            font-size: 16px;
        }

        .empty-state-text {
            font-size: 12px;
        }

        .modal-content {
            width: 100%;
            height: 90vh;
            border-radius: 4px;
        }

        .modal-header {
            padding: 12px;
        }

        .modal-header h2 {
            font-size: 14px;
        }

        .modal-close-btn {
            width: 30px;
            height: 30px;
        }

        .modal-body {
            padding: 8px;
        }
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

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        border-radius: 12px;
        width: 95%;
        max-width: 1200px;
        height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-lg);
    }

    .modal-header {
        background-color: var(--primary-color);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
    }

    .modal-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: background-color 0.3s;
    }

    .modal-close-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .modal-body {
        flex: 1;
        overflow: auto;
        padding: 20px;
    }

    .pdf-viewer {
        width: 100%;
        height: 100%;
        border: none;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
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

        .modal-content {
            width: 95%;
            height: 90vh;
        }
    }
</style>

<!-- Main Content -->
<div class="main-content">
    <!-- Filter Section -->
    @if ($drones->count() > 0)
        <div class="filter-section">
            <select id="locationFilter" onchange="filterByLocation()">
                <option value=""><i class="fas fa-map-pin"></i> Semua Lokasi</option>
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
                       <img src="{{ asset('images/drone/kp.png') }}" alt="Drone" class="card-image">
                        <h2 class="card-title">{{ Str::limit($drone->judul, 30) }}</h2>
                        <div class="card-badges">
                            <span class="badge badge-location">Lokasi {{ $drone->lokasi }}</span>
                            @if ($drone->persen_gulma !== null)
                                <span class="badge badge-gulma">{{ number_format($drone->persen_gulma, 1) }}% Gulma</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="drone-info">
                            <div class="info-label"><i class="fas fa-calendar"></i> Tanggal Upload</div>
                            <div class="info-value">{{ $drone->tanggal_perencanaan->translatedFormat('d F Y') }}</div>
                        </div>

                        <div class="drone-info">
                            <div class="info-label"><i class="fas fa-clock"></i> Tanggal Upload</div>
                            <div class="info-value">{{ $drone->created_at->translatedFormat('d F Y') }}</div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('drone.download', $drone->id) }}" class="btn btn-download">
                            <i class="fas fa-download"></i> Downloas PDF
                        </a>
                        <button class="btn btn-view" data-pdf-url="{{ route('drone.view', $drone->id) }}" data-pdf-title="{{ $drone->judul }}" onclick="openPdfModal(this)">Detail</button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
            <h2 class="empty-state-title">Belum ada dokumen drone</h2>
            <p class="empty-state-text">
                Dokumen perencanaan penggunaan drone akan ditampilkan di sini. Silakan kembali lagi nanti untuk melihat dokumen terbaru.
            </p>
        </div>
    @endif

<!-- PDF Modal -->
<div id="pdfModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="pdfTitle">Detail PDF</h2>
            <button class="modal-close-btn" onclick="closePdfModal()">&times;</button>
        </div>
        <div class="modal-body">
            <iframe id="pdfViewer" class="pdf-viewer" src=""></iframe>
        </div>
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

    function openPdfModal(button) {
        const pdfUrl = button.getAttribute('data-pdf-url');
        const title = button.getAttribute('data-pdf-title');
        document.getElementById('pdfTitle').textContent = title;
        document.getElementById('pdfViewer').src = pdfUrl;
        document.getElementById('pdfModal').classList.add('show');
    }

    function closePdfModal() {
        document.getElementById('pdfModal').classList.remove('show');
        document.getElementById('pdfViewer').src = '';
    }

    // Tutup modal saat klik di luar konten modal
    window.onclick = function(event) {
        const modal = document.getElementById('pdfModal');
        if (event.target == modal) {
            closePdfModal();
        }
    }

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePdfModal();
        }
    });
</script>

@endsection
