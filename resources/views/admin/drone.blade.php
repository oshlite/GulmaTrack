@extends('layouts.admin')

@section('title', 'Drone - Admin')

@section('content')

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

    .main-content {
        margin-top: 90px;
        padding: 30px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 10px;
    }

    .page-subtitle {
        font-size: 14px;
        color: #666;
    }

    /* Alert Styling */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid;
    }

    .alert-success {
        background-color: #d4edda;
        border-left-color: #28a745;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-left-color: #dc3545;
        color: #721c24;
    }

    /* Form Section */
    .form-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
    }

    .form-section-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--primary-color);
        border-bottom: 2px solid var(--secondary-color);
        padding-bottom: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-color);
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-family: 'Poppins';
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(18, 130, 65, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-family: 'Poppins';
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: #0f6334;
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .file-input-label {
        display: block;
        padding: 12px 15px;
        background-color: #f0f0f0;
        border: 2px dashed var(--primary-color);
        border-radius: 6px;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s;
    }

    .file-input-label:hover {
        background-color: rgba(18, 130, 65, 0.05);
        border-color: #0f6334;
    }

    .file-input-label input {
        display: none;
    }

    .file-name {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }

    /* Table Styling */
    .table-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: var(--shadow);
    }

    .table-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--primary-color);
        border-bottom: 2px solid var(--secondary-color);
        padding-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table thead {
        background-color: var(--light-bg);
        border-bottom: 2px solid var(--border-color);
    }

    table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--text-color);
        font-size: 14px;
    }

    table td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
    }

    table tbody tr:hover {
        background-color: var(--light-bg);
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-sm {
        padding: 8px 12px;
        font-size: 12px;
    }

    .btn-download {
        background-color: #0066cc;
        color: white;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 4px;
        display: inline-block;
        transition: background-color 0.3s;
    }

    .btn-download:hover {
        background-color: #0052a3;
        text-decoration: none;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .main-content {
            padding: 15px;
        }

        .page-title {
            font-size: 24px;
        }

        table {
            font-size: 12px;
        }

        table th,
        table td {
            padding: 10px 5px;
        }
    }
</style>

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">Manajemen Drone</h1>
        <p class="page-subtitle">Upload dan kelola dokumen perencanaan drone</p>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi Kesalahan!</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Upload Section -->
    <div class="form-section">
        <h2 class="form-section-title">📤 Upload Drone PDF</h2>

        <form action="{{ route('admin.drone.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="judul">Judul <span style="color: red;">*</span></label>
                    <input type="text" id="judul" name="judul" placeholder="Contoh: Perencanaan Pengendalian Gulma Wilayah A" value="{{ old('judul') }}" required>
                </div>

                <div class="form-group">
                    <label for="lokasi">Lokasi <span style="color: red;">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: Wilayah Jakarta Timur" value="{{ old('lokasi') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_perencanaan">Tanggal Perencanaan <span style="color: red;">*</span></label>
                    <input type="date" id="tanggal_perencanaan" name="tanggal_perencanaan" value="{{ old('tanggal_perencanaan') }}" required>
                </div>

                <div class="form-group">
                    <label for="persen_gulma">Persen Gulma (%)</label>
                    <input type="number" id="persen_gulma" name="persen_gulma" placeholder="Contoh: 45.5" step="0.01" min="0" max="100" value="{{ old('persen_gulma') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="pdf_file">File PDF <span style="color: red;">*</span></label>
                    <div class="file-input-wrapper">
                        <label class="file-input-label">
                            <span id="file-label">📁 Pilih File PDF (Max 10MB)</span>
                            <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" required onchange="updateFileName(this)">
                        </label>
                        <div class="file-name" id="file-name-display"></div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                ✅ Upload Drone
            </button>
        </form>
    </div>

    <!-- Data Table Section -->
    <div class="table-section">
        <h2 class="table-title">📋 Daftar Drone</h2>

        @if ($drones->count() > 0)
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Lokasi</th>
                            <th>Tanggal Perencanaan</th>
                            <th>File</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($drones as $index => $drone)
                            <tr>
                                <td>{{ ($drones->currentPage() - 1) * $drones->perPage() + $index + 1 }}</td>
                                <td><strong>{{ $drone->judul }}</strong></td>
                                <td>{{ $drone->lokasi }}</td>
                                <td>{{ $drone->tanggal_perencanaan->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('drone.download', $drone->id) }}" class="btn-download">
                                        📥 Download
                                    </a>
                                </td>
                                <td>{{ $drone->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <form action="{{ route('admin.drone.destroy', $drone->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus drone ini?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $drones->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>Tidak ada data drone</h3>
                <p>Mulai upload drone PDF dengan form di atas</p>
            </div>
        @endif
    </div>
</div>

<script>
    function updateFileName(input) {
        const fileName = input.files[0]?.name || '';
        const fileNameDisplay = document.getElementById('file-name-display');
        const fileLabel = document.getElementById('file-label');

        if (fileName) {
            fileNameDisplay.textContent = '✓ File terpilih: ' + fileName;
            fileLabel.textContent = '✓ File siap diupload';
        } else {
            fileNameDisplay.textContent = '';
            fileLabel.textContent = '📁 Pilih File PDF (Max 10MB)';
        }
    }
</script>

@endsection
