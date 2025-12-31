@extends('layouts.admin')

@section('title', 'Galeri Foto Gulma (Berdasarkan Kategori)')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-color: #128241;
        --secondary-color: #D6DF20;
        --accent-color: #FBA919;
        --light-bg: #f8f9fa;
        --text-color: #333;
        --border-color: #e0e0e0;
    }

    body {
        font-family: 'Poppins', sans-serif;
        padding-top: 0;
    }

    /* Navbar & Container - SAMA seperti sebelumnya */
    nav.admin-navbar {
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

    .navbar-brand {
        font-size: 25px;
        font-family: 'Poppins';
        font-weight: 800;
        color: #FBA919;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .navbar-brand img {
        width: 85px;
        height: 60px;
        object-fit: contain;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 2px;
        margin: 0;
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
    }

    .nav-link.active {
        color: var(--secondary-color);
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .admin-info {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: #ecf0f1;
        font-size: 14px;
        font-weight: 600;
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
    }

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
    }

    .logout-btn:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 100px 20px 30px;
        font-family: 'Poppins', sans-serif;
    }

    .page-header {
        margin-bottom: 40px;
        padding: 40px 20px 30px;
        border-bottom: 3px solid var(--primary-color);
    }

    .page-header h1 {
        font-size: 42px;
        color: #A6CE39;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-header h1 i {
        color: var(--accent-color);
    }

    .page-header p {
        font-size: 16px;
        color: #666;
        margin: 0;
    }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.15);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        font-size: 32px;
        margin-bottom: 10px;
        opacity: 0.9;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 700;
        margin: 10px 0;
    }

    .stat-label {
        font-size: 14px;
        opacity: 0.95;
        font-weight: 500;
    }

    /* Upload Section */
    .upload-section {
        background: linear-gradient(135deg, #ffffff 0%, #f7faf8 100%);
        padding: 32px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(18, 130, 65, 0.08);
        border-left: 4px solid var(--primary-color);
    }

    .upload-section h2 {
        color: var(--primary-color);
        font-size: 24px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .upload-area {
        border: 3px dashed var(--border-color);
        border-radius: 12px;
        padding: 60px 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafdfb;
    }

    .upload-area:hover, .upload-area.dragover {
        background: #f0f8f5;
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.1);
    }

    .upload-icon {
        font-size: 64px;
        color: var(--accent-color);
        margin-bottom: 20px;
    }

    .upload-text {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 8px;
    }

    .upload-hint {
        font-size: 14px;
        color: #999;
    }

    /* Photo Metadata Form */
    .photo-metadata {
        display: none;
        margin-top: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .photo-metadata.active {
        display: block;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-group label i {
        color: var(--accent-color);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(18, 130, 65, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: white;
        border-radius: 8px;
        cursor: pointer;
    }

    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    /* Preview Grid */
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .preview-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .preview-item:hover {
        transform: scale(1.05);
    }

    .preview-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .preview-remove {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #e74c3c;
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .preview-remove:hover {
        background: #c0392b;
        transform: scale(1.1);
    }

    /* Filter Section */
    .filter-section {
        background: white;
        padding: 24px 32px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(18, 130, 65, 0.08);
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-item {
        flex: 1;
        min-width: 200px;
    }

    .filter-item label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-item select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
    }

    /* Gallery Grid */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .gallery-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        cursor: pointer;
    }

    .gallery-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(18, 130, 65, 0.15);
    }

    .gallery-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        position: relative;
    }

    .primary-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: var(--accent-color);
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        z-index: 1;
    }

    .gallery-info {
        padding: 20px;
    }

    .gallery-kategori {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kategori-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .kategori-bersih { background: #3498db; color: white; }
    .kategori-ringan { background: #128241; color: white; }
    .kategori-sedang { background: #f1c40f; color: #333; }
    .kategori-berat { background: #e74c3c; color: white; }

    .gallery-details {
        font-size: 13px;
        color: #666;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .gallery-details i {
        color: var(--accent-color);
        width: 16px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .btn {
        flex: 1;
        padding: 10px;
        border: none;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.3);
    }

    .btn-warning {
        background: #f39c12;
        color: white;
    }

    .btn-warning:hover {
        background: #e67e22;
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background: #c0392b;
    }

    .btn-upload {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-upload:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(18, 130, 65, 0.3);
    }

    .btn-upload:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .alert {
        padding: 15px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .alert i {
        font-size: 18px;
    }

    .alert-success {
        background: #e8f5e9;
        color: #128241;
        border-left-color: #128241;
    }

    .alert-error {
        background: #ffebee;
        color: #c62828;
        border-left-color: #c62828;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        max-width: 900px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #e74c3c;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
        z-index: 10;
    }

    .modal-image {
        width: 100%;
        max-height: 500px;
        object-fit: contain;
        border-radius: 16px 16px 0 0;
    }

    .modal-info {
        padding: 30px;
    }

    .modal-info h2 {
        color: var(--primary-color);
        font-size: 28px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 24px;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }

        .gallery-grid {
            grid-template-columns: 1fr;
        }

        .filter-section {
            flex-direction: column;
        }

        .filter-item {
            width: 100%;
        }
    }
</style>

<!-- Navbar Admin -->
<nav class="admin-navbar">
    <div class="navbar-container">
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
            <img src="{{ asset('image/logo.png') }}" alt="GulmaTrack Logo">
            <span>GulmaTrack</span>
        </a>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.gallery.index') }}" class="nav-link active">
                    <i class="fas fa-images"></i> Galeri
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('wilayah') }}" class="nav-link">
                    <i class="fas fa-globe"></i> Publik
                </a>
            </li>
        </ul>

        <div class="navbar-right">
            <div class="admin-info">
                <div class="admin-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <span>Administrator</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="admin-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-images"></i> Galeri Foto Gulma (Berdasarkan Kategori)</h1>
        <p>Kelola foto visual untuk setiap kategori status gulma (Bersih, Ringan, Sedang, Berat)</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-images"></i></div>
            <div class="stat-value">{{ $stats['total_photos'] ?? 0 }}</div>
            <div class="stat-label">Total Foto</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value">{{ $stats['bersih_count'] ?? 0 }}</div>
            <div class="stat-label">Kategori Bersih</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-leaf"></i></div>
            <div class="stat-value">{{ $stats['ringan_count'] ?? 0 }}</div>
            <div class="stat-label">Kategori Ringan</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="stat-value">{{ $stats['sedang_count'] ?? 0 }}</div>
            <div class="stat-label">Kategori Sedang</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-value">{{ $stats['berat_count'] ?? 0 }}</div>
            <div class="stat-label">Kategori Berat</div>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="upload-section">
        <h2><i class="fas fa-cloud-upload-alt"></i> Upload Foto Berdasarkan Kategori</h2>
        
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            
            <div class="upload-area" id="uploadArea">
                <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="upload-text">Klik atau Drag & Drop Foto di Sini</div>
                <div class="upload-hint">Format: JPG, PNG | Maksimal 5MB per foto | Bisa upload multiple</div>
                <input type="file" id="fileInput" name="photos[]" accept="image/*" multiple hidden>
            </div>

            <!-- Preview Grid -->
            <div class="preview-grid" id="previewGrid"></div>

            <!-- Photo Metadata Form -->
            <div class="photo-metadata" id="metadataForm">
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-flag"></i> Kategori Gulma</label>
                        <select name="kategori" id="kategori" required>
                            <option value="">Pilih Kategori...</option>
                            <option value="bersih">🟦 Bersih</option>
                            <option value="ringan">🟩 Ringan</option>
                            <option value="sedang">🟨 Sedang</option>
                            <option value="berat">🟥 Berat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="set_as_primary" id="setPrimary" value="1">
                            <label for="setPrimary" style="margin: 0; cursor: pointer;">
                                <i class="fas fa-star"></i> Jadikan Foto Utama (akan ditampilkan pertama di popup)
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-comment"></i> Deskripsi / Catatan (Opsional)</label>
                        <textarea name="deskripsi" id="deskripsi" placeholder="Tambahkan catatan kondisi gulma kategori ini..."></textarea>
                    </div>
                </div>
                <button type="submit" class="btn-upload">
                    <i class="fas fa-upload"></i>
                    Upload Foto
                </button>
            </div>
        </form>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-item">
            <label>Filter Kategori</label>
            <select id="filterKategori" onchange="filterGallery()">
                <option value="">Semua Kategori</option>
                <option value="bersih">🟦 Bersih</option>
                <option value="ringan">🟩 Ringan</option>
                <option value="sedang">🟨 Sedang</option>
                <option value="berat">🟥 Berat</option>
            </select>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="gallery-grid" id="galleryGrid">
        @forelse($photos as $photo)
            <div class="gallery-card" data-kategori="{{ $photo->kategori }}" onclick="openModal({{ $photo->id }})">
                <div style="overflow: hidden; border-radius: 16px 16px 0 0; position: relative;">
                    @if($photo->is_primary)
                        <span class="primary-badge">⭐ FOTO UTAMA</span>
                    @endif
                    <img src="{{ $photo->foto_url }}" alt="Foto Gulma" class="gallery-image">
                </div>
                <div class="gallery-info">
                    <div class="gallery-kategori">
                        <span class="kategori-badge kategori-{{ $photo->kategori }}">
                            {{ strtoupper($photo->kategori) }}
                        </span>
                    </div>
                    <div class="gallery-details">
                        <i class="fas fa-calendar"></i>
                        {{ $photo->created_at->format('d M Y') }}
                    </div>
                    <div class="gallery-details">
                        <i class="fas fa-user"></i>
                        {{ $photo->uploader->name }}
                    </div>
                    @if($photo->deskripsi)
                        <div class="gallery-details" style="margin-top: 10px; font-size: 12px; font-style: italic;">
                            {{ Str::limit($photo->deskripsi, 60) }}
                        </div>
                    @endif
                    <div class="action-buttons">
                        @if(!$photo->is_primary)
                            <button class="btn btn-warning" onclick="event.stopPropagation(); setPrimary({{ $photo->id }})">
                                <i class="fas fa-star"></i> Set Utama
                            </button>
                        @endif
                        <button class="btn btn-danger" onclick="event.stopPropagation(); confirmDelete({{ $photo->id }})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #999;">
                <i class="fas fa-images" style="font-size: 64px; margin-bottom: 20px; opacity: 0.3; color: var(--primary-color);"></i>
                <p style="font-size: 18px; font-weight: 600;">Belum ada foto yang diupload</p>
                <p style="font-size: 14px;">Upload foto pertama untuk setiap kategori gulma</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($photos->hasPages())
        <div style="margin: 40px 0; display: flex; justify-content: center;">
            {{ $photos->links() }}
        </div>
    @endif
</div>

<!-- Modal -->
<div class="modal" id="photoModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">
            <i class="fas fa-times"></i>
        </button>
        <img src="" alt="Detail Foto" class="modal-image" id="modalImage">
        <div class="modal-info">
            <h2 id="modalKategori"></h2>
            <div style="margin: 20px 0;">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;"><strong>Deskripsi:</strong></div>
                <div id="modalDesc" style="font-size: 14px;"></div>
            </div>
            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <div style="flex: 1; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Diupload Oleh</div>
                    <div id="modalUploader" style="font-size: 14px; font-weight: 600;"></div>
                </div>
                <div style="flex: 1; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Tanggal Upload</div>
                    <div id="modalDate" style="font-size: 14px; font-weight: 600;"></div>
                </div>
                <div style="flex: 1; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Ukuran File</div>
                    <div id="modalSize" style="font-size: 14px; font-weight: 600;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedFiles = [];

// Upload Area Events
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('fileInput');
const previewGrid = document.getElementById('previewGrid');
const metadataForm = document.getElementById('metadataForm');

uploadArea.addEventListener('click', () => fileInput.click());

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    handleFiles(e.dataTransfer.files);
});

fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
});

function handleFiles(files) {
    selectedFiles = Array.from(files);
    previewGrid.innerHTML = '';
    
    if (selectedFiles.length > 0) {
        metadataForm.classList.add('active');
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item';
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button class="preview-remove" onclick="removeFile(${index})" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                previewGrid.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        });
    } else {
        metadataForm.classList.remove('active');
    }
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    handleFiles(selectedFiles);
    
    if (selectedFiles.length === 0) {
        fileInput.value = '';
        metadataForm.classList.remove('active');
    }
}

// AJAX Upload Function
document.getElementById('uploadForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const uploadBtn = document.querySelector('.btn-upload');
    const originalText = uploadBtn.innerHTML;
    
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';
    uploadBtn.disabled = true;

    try {
        const response = await fetch('{{ route("admin.gallery.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
            }
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Upload gagal'));
        }
    } catch (error) {
        alert('Terjadi kesalahan: ' + error.message);
    } finally {
        uploadBtn.innerHTML = originalText;
        uploadBtn.disabled = false;
    }
});

// Set as Primary
async function setPrimary(id) {
    if (!confirm('Jadikan foto ini sebagai foto utama untuk kategori ini?')) {
        return;
    }

    try {
        const response = await fetch(`/admin/gallery/${id}/set-primary`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan: ' + error.message);
    }
}

// AJAX Delete Function
async function confirmDelete(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        return;
    }

    try {
        const response = await fetch(`/admin/gallery/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan: ' + error.message);
    }
}

// Filter Gallery
function filterGallery() {
    const kategori = document.getElementById('filterKategori').value;
    const cards = document.querySelectorAll('.gallery-card');
    
    cards.forEach(card => {
        if (!kategori || card.dataset.kategori === kategori) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Open Modal
async function openModal(id) {
    try {
        const response = await fetch(`/admin/gallery/${id}`);
        const result = await response.json();

        if (result.success) {
            const data = result.data;
            document.getElementById('modalImage').src = data.foto_url;
            document.getElementById('modalKategori').textContent = 'Kategori: ' + data.kategori.toUpperCase();
            document.getElementById('modalDesc').textContent = data.deskripsi || 'Tidak ada deskripsi';
            document.getElementById('modalUploader').textContent = data.uploader;
            document.getElementById('modalDate').textContent = data.uploaded_at;
            document.getElementById('modalSize').textContent = data.file_size;

            document.getElementById('photoModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    } catch (error) {
        alert('Error loading photo: ' + error.message);
    }
}

// Close Modal
function closeModal() {
    document.getElementById('photoModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Close modal on background click
document.getElementById('photoModal').addEventListener('click', (e) => {
    if (e.target.id === 'photoModal') {
        closeModal();
    }
});
</script>

@endsection
