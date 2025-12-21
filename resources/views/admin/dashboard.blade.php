<!-- @extends('layouts.admin') -->

@section('title', 'Dashboard Admin')

@section('content')

<style>
    :root {
        --primary-color: #197B40;
        --secondary-color: #D6DF20;
        --accent-color: #FBA919;
        --light-bg: #f8f9fa;
        --text-color: #333;
        --border-color: #e0e0e0;
        --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .admin-navbar {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0D5C2E 100%);
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 70px;
        box-shadow: var(--shadow-lg);
        position: sticky;
        top: 0;
        z-index: 100;
        width: 100%;
    }

    .navbar-brand {
        color: var(--secondary-color);
        font-size: 24px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .navbar-brand i {
        font-size: 28px;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        color: white;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--secondary-color);
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
        padding: 30px 20px;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        font-size: 32px;
        color: var(--primary-color);
        margin-bottom: 10px;
    }

    .page-header p {
        color: #666;
        font-size: 15px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: var(--shadow);
        border-left: 5px solid var(--primary-color);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-icon {
        font-size: 32px;
        color: var(--primary-color);
        margin-bottom: 12px;
    }

    .stat-label {
        font-size: 13px;
        color: #999;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 700;
        color: var(--primary-color);
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 40px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: var(--shadow);
    }

    .card h2 {
        font-size: 20px;
        color: var(--primary-color);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card h2 i {
        color: var(--secondary-color);
    }

    .upload-area {
        border: 2px dashed var(--secondary-color);
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        background: #f9f9f9;
        border-color: var(--primary-color);
    }

    .upload-icon {
        font-size: 48px;
        color: var(--secondary-color);
        margin-bottom: 15px;
    }

    .upload-text {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 5px;
    }

    .upload-hint {
        font-size: 13px;
        color: #999;
    }

    .upload-input {
        display: none;
    }

    .upload-btn {
        width: 100%;
        padding: 12px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .upload-btn:hover {
        background: #0D5C2E;
        transform: translateY(-2px);
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table thead {
        background: var(--light-bg);
    }

    table th {
        padding: 15px;
        text-align: left;
        font-weight: 700;
        color: var(--primary-color);
        border-bottom: 2px solid var(--border-color);
    }

    table td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--border-color);
    }

    table tbody tr:hover {
        background: #f9f9f9;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }

    .status-success {
        background: rgba(39, 174, 96, 0.2);
        color: #27AE60;
    }

    .status-pending {
        background: rgba(251, 169, 25, 0.2);
        color: var(--accent-color);
    }

    .status-failed {
        background: rgba(231, 76, 60, 0.2);
        color: #E74C3C;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .alert {
        padding: 12px 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        border-left: 4px solid;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border-left-color: #2e7d32;
    }

    @media (max-width: 768px) {
        .navbar-right {
            gap: 10px;
        }

        .user-info {
            display: none;
        }

        .content-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .admin-container {
            padding: 20px 15px;
        }

        .page-header h1 {
            font-size: 24px;
        }
    }
</style>

<!-- Navbar Admin -->
<nav class="admin-navbar">
    <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
        <i class="fas fa-leaf"></i>
        GulmaTrack 
    </a>
    <div class="navbar-right">
        <div class="user-info">
            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <span>{{ Auth::user()->name }}</span>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</nav>

<!-- Main Content -->
<div class="admin-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> Dashboard Admin</h1>
        <p>Kelola data penyebaran gulma dan upload file CSV</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="stat-label">Total Data Gulma</div>
            <div class="stat-value">{{ $totalDataGulma ?? 0 }}</div>
        </div>

        <div class="stat-card" style="border-left-color: #27AE60;">
            <div class="stat-icon" style="color: #27AE60;">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="stat-label">Wilayah Aktif</div>
            <div class="stat-value">{{ $wilayahAktif ?? 0 }}</div>
        </div>

        <div class="stat-card" style="border-left-color: var(--accent-color);">
            <div class="stat-icon" style="color: var(--accent-color);">
                <i class="fas fa-seedling"></i>
            </div>
            <div class="stat-label">Total Tanaman</div>
            <div class="stat-value">{{ $totalTanaman ?? 0 }}</div>
        </div>

        <div class="stat-card" style="border-left-color: var(--secondary-color);">
            <div class="stat-icon" style="color: var(--secondary-color);">
                <i class="fas fa-file-upload"></i>
            </div>
            <div class="stat-label">Upload Terakhir</div>
            <div class="stat-value">{{ $importTerbaru->count() ?? 0 }}</div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Upload CSV Card -->
        <div class="card">
            <h2><i class="fas fa-cloud-upload-alt"></i> Upload Data CSV</h2>
            
            <form action="{{ route('admin.upload-csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="upload-area" onclick="document.getElementById('csvFile').click()">
                    <div class="upload-icon">
                        <i class="fas fa-file-csv"></i>
                    </div>
                    <div class="upload-text">Klik atau drag file CSV di sini</div>
                    <div class="upload-hint">Format: CSV | Ukuran maksimal: 10MB</div>
                    <input 
                        type="file" 
                        id="csvFile" 
                        name="file" 
                        class="upload-input" 
                        accept=".csv"
                    >
                </div>
                <button type="submit" class="upload-btn">
                    <i class="fas fa-upload"></i> Upload File
                </button>
            </form>

            @if ($errors->has('file'))
                <div style="color: #E74C3C; margin-top: 15px; font-size: 13px;">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first('file') }}
                </div>
            @endif
        </div>

        <!-- Info Card -->
        <div class="card">
            <h2><i class="fas fa-info-circle"></i> Informasi</h2>
            
            <div style="padding: 10px 0; color: #666; line-height: 1.8;">
                <p><strong>Selamat datang di Dashboard Admin!</strong></p>
                <p style="margin-top: 15px; font-size: 14px;">
                    Gunakan halaman ini untuk mengelola data penyebaran gulma. Anda dapat:
                </p>
                <ul style="margin-left: 20px; margin-top: 10px; font-size: 14px;">
                    <li>Upload file CSV dengan data penyebaran gulma</li>
                    <li>Melihat statistik data yang telah diupload</li>
                    <li>Mengelola informasi wilayah dan tanaman</li>
                </ul>
                <p style="margin-top: 15px; font-size: 13px; color: #999;">
                    Format CSV harus sesuai dengan template yang disediakan
                </p>
            </div>
        </div>
    </div>

    <!-- Recent Uploads -->
    <div class="card">
        <h2><i class="fas fa-history"></i> Riwayat Upload Terbaru</h2>
        
        @if (isset($importTerbaru) && $importTerbaru->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nama File</th>
                            <th>Total Records</th>
                            <th>Berhasil</th>
                            <th>Gagal</th>
                            <th>Status</th>
                            <th>Waktu Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($importTerbaru as $log)
                            <tr>
                                <td>{{ $log->nama_file }}</td>
                                <td>{{ $log->jumlah_records }}</td>
                                <td>{{ $log->jumlah_berhasil }}</td>
                                <td>{{ $log->jumlah_gagal }}</td>
                                <td>
                                    <span class="status-badge status-{{ $log->status }}">
                                        @if ($log->status === 'success')
                                            <i class="fas fa-check"></i> Berhasil
                                        @elseif ($log->status === 'pending')
                                            <i class="fas fa-hourglass-half"></i> Pending
                                        @else
                                            <i class="fas fa-times"></i> Gagal
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada riwayat upload</p>
            </div>
        @endif
    </div>
</div>

<script>
    // File upload drag and drop
    const uploadArea = document.querySelector('.upload-area');
    const fileInput = document.getElementById('csvFile');

    if (uploadArea && fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.style.borderColor = 'var(--primary-color)';
                uploadArea.style.background = '#f0f0f0';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.style.borderColor = 'var(--secondary-color)';
                uploadArea.style.background = 'transparent';
            });
        });

        uploadArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
        });
    }
</script>

@endsection