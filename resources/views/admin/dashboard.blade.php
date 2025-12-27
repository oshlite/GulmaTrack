@extends('layouts.admin')

@section('title', 'Dashboard Admin')

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
        font-family: 'Poppins', sans-serif;
        padding-top: 0;
    }

    /* Navbar Styling - Same as main navbar */
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
        white-space: nowrap;
    }

    .navbar-brand:hover {
        transform: scale(1.05);
        color: #FBA919;
    }

    .navbar-brand img {
        width: 85px;
        height: 60px;
        object-fit: contain;
        transition: all 0.3s ease;
    }

    .navbar-brand:hover img {
        transform: rotate(1deg) scale(1);
    }

    .navbar-brand span {
        color: #FBA919 !important;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 2px;
        flex-wrap: nowrap;
        margin: 0;
    }

    .nav-item {
        position: relative;
        white-space: nowrap;
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
        letter-spacing: 0.3px;
        position: relative;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background-color: var(--secondary-color);
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 80%;
    }

    .nav-link:hover {
        color: var(--light-color);
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
        font-family: 'Poppins', sans-serif;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
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
        font-family: 'Poppins', sans-serif;
        letter-spacing: 0.3px;
        box-shadow: 0 2px 6px rgba(231, 76, 60, 0.3);
    }

    .logout-btn:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(231, 76, 60, 0.4);
    }

    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 100px 20px 30px;
        font-family: 'Poppins', sans-serif;
    }

    :root {
        --title-color: #A6CE39;
    }

    .page-header {
        margin-bottom: 40px;
        padding: 40px 20px 30px;
        border-bottom: 3px solid var(--primary-color);
        animation: slideInDown 0.5s ease;
    }

    .page-header h1 {
        font-size: 42px;
        color: var(--title-color);
        margin-bottom: 10px;
        font-family: 'Poppins', sans-serif;
    }

    .page-header h1 i {
        color: var(--title-color);
    }

    .page-header p {
        font-size: 16px;
        color: #666;
        font-family: 'Poppins', sans-serif;
        margin: 0;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: white;
        opacity: 0.3;
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 24px rgba(18, 130, 65, 0.25);
    }

    .stat-icon {
        font-size: 32px;
        color: white;
        margin-bottom: 12px;
        opacity: 0.9;
    }

    .stat-label {
        font-size: 14px;
        opacity: 0.95;
        margin-bottom: 8px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 700;
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    .content-grid {
        grid-template-columns: 1fr 1fr;
        margin-bottom: 40px;
        display: flex;
    gap: 24px; /* jarak antar card */
    width: 100%;
    }

    .card {
        background: linear-gradient(135deg, #ffffff 0%, #f7faf8 100%);
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(18, 130, 65, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(18, 130, 65, 0.1);
        border-left: 4px solid #128241;
        font-family: 'Poppins';
        position: relative;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card1 {
        background: linear-gradient(135deg, #ffffff 0%, #f7faf8 100%);
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(18, 130, 65, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(18, 130, 65, 0.1);
        border-left: 4px solid #128241;
        font-family: 'Poppins';
        position: relative;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card { flex: 2; }     /* 25% */
.card1 { flex: 3; }   /* 75% */


    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, rgba(18, 130, 65, 0.3), transparent);
        background-size: 200% 100%;
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0%, 100% { background-position: 0% 0%; }
        50% { background-position: 100% 0%; }
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(18, 130, 65, 0.15);
    }

    .card h2 {
        font-size: 20px;
        color: var(--primary-color);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
    }

    .card h2 i {
        color: var(--accent-color);
        font-size: 22px;
    }

    .wilayah-selector {
        margin-bottom: 20px;
    }

    .wilayah-selector label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-family: 'Poppins';
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .wilayah-selector label i {
        color: var(--accent-color);
        font-size: 13px;
    }

    .wilayah-selector select {
        width: 100%;
        padding: 12px 14px;
        font-size: 14px;
        border: 2px solid #e3eae8;
        border-radius: 10px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Poppins';
        font-weight: 500;
        color: #2c3e50;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .wilayah-selector select:hover {
        border-color: var(--primary-color);
        box-shadow: 0 6px 16px rgba(18, 130, 65, 0.12);
        background-color: #fafdfb;
    }

    .wilayah-selector select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(18, 130, 65, 0.1);
    }

    .upload-area {
        border: 2px dashed #e3eae8;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 15px;
        background: white;
    }

    .upload-area:hover {
        background: #fafdfb;
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.1);
    }

    .upload-icon {
        font-size: 48px;
        color: var(--accent-color);
        margin-bottom: 15px;
    }

    .upload-text {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 5px;
        font-family: 'Poppins', sans-serif;
    }

    .upload-hint {
        font-size: 13px;
        color: #999;
        font-family: 'Poppins', sans-serif;
    }

    .upload-input {
        display: none;
    }

    .upload-btn {
        width: 100%;
        padding: 13px 24px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
        letter-spacing: 0.6px;
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.2);
    }

    .upload-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(18, 130, 65, 0.3);
    }

    .upload-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
    }

    table thead {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
    }

    table th {
        padding: 15px;
        text-align: left;
        font-weight: 700;
        color: white;
        border: none;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    table td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--border-color);
        color: #2c3e50;
    }

    table tbody tr {
        transition: all 0.3s ease;
    }

    table tbody tr:hover {
        background: #f0f8f5;
        transform: translateX(4px);
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 11px;
        font-family: 'Poppins', sans-serif;
    }

    .status-success {
        background: #e8f5e9;
        color: #128241;
        border: 1px solid #128241;
    }

    .status-pending {
        background: #fff8e1;
        color: var(--accent-color);
        border: 1px solid var(--accent-color);
    }

    .status-failed {
        background: #ffebee;
        color: #E74C3C;
        border: 1px solid #E74C3C;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
        font-family: 'Poppins', sans-serif;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
        color: var(--primary-color);
    }

    .empty-state p {
        font-size: 14px;
        font-weight: 500;
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

    .map-container {
        height: 600px;
        border: 1px solid rgba(18, 130, 65, 0.1);
        border-radius: 12px;
        margin-top: 15px;
        background: #f0f0f0;
        position: relative;
        z-index: 1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .map-legend {
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        position: absolute;
        bottom: 20px;
        right: 10px;
        z-index: 500;
        min-width: 180px;
    }

    .map-legend h4 {
        margin: 0 0 10px 0;
        font-size: 16px;
        font-weight: 600;
        color: #FBA919;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Poppins', sans-serif;
    }

    .legend-item {
        display: flex;
        align-items: center;
        font-size: 13px;
        cursor: pointer;
        padding: 8px;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .legend-item:hover {
        background: rgba(18, 130, 65, 0.1);
        transform: translateX(5px);
    }

    .legend-item:last-child {
        margin-bottom: 3px;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        border-radius: 3px;
        border: 1px solid #ccc;
        flex-shrink: 0;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(18, 130, 65, 0.1);
        border-top: 3px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message {
        padding: 15px 18px;
        margin-top: 15px;
        border-radius: 10px;
        font-size: 13px;
        display: none;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
    }

    .message.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    .message.success {
        background: #e8f5e9;
        color: #128241;
        border-left: 4px solid #128241;
    }

    .message.error {
        background: #ffebee;
        color: #c62828;
        border-left: 4px solid #c62828;
    }

    /* Button Grid Styles */
    .button-grid-trigger:hover {
        border-color: #128241 !important;
        box-shadow: 0 6px 16px rgba(18, 130, 65, 0.12), 0 2px 6px rgba(0, 0, 0, 0.05) !important;
        background-color: #fafdfb !important;
    }

    .button-grid-trigger.active {
        border-color: #128241 !important;
        background-color: #fafdfb !important;
    }

    .button-grid-trigger.active .fas {
        transform: rotate(180deg);
    }

    .button-grid {
        animation: slideDown 0.3s ease;
    }

    .grid-btn {
        padding: 12px 16px;
        border: 2px solid #e3eae8;
        border-radius: 8px;
        background: white;
        color: #2c3e50;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        white-space: nowrap;
    }

    .grid-btn:hover {
        border-color: #128241;
        background: #f0f8f5;
        color: #128241;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.15);
    }

    .grid-btn.selected {
        background: linear-gradient(135deg, #128241 0%, #0d5c2e 100%);
        color: white !important;
        border-color: #128241;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.3);
    }

    .grid-btn.selected:hover {
        background: linear-gradient(135deg, #0d5c2e 0%, #0a4a26 100%);
        transform: translateY(-2px);
    }

    .full-width {
        grid-column: 1 / -1;
    }

    @media (max-width: 768px) {
        .navbar-right {
            gap: 10px;
        }

        .admin-info span {
            display: none;
        }

        .nav-menu {
            display: none;
        }

        .navbar-container {
            padding: 0 15px;
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

        .map-legend {
            right: 5px;
            bottom: 10px;
            min-width: 150px;
        }

        .card {
            padding: 20px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-value {
            font-size: 28px;
        }

        table {
            font-size: 12px;
        }

        table th, table td {
            padding: 8px 10px;
        }
    }

    @media (max-width: 480px) {
        .page-header h1 {
            font-size: 20px;
        }

        .navbar-brand {
            font-size: 16px;
        }

        .navbar-brand img {
            height: 35px;
        }

        .stat-label {
            font-size: 12px;
        }

        .stat-value {
            font-size: 24px;
        }

        .map-legend {
            font-size: 11px;
            padding: 10px;
        }

        .legend-color {
            width: 16px;
            height: 16px;
        }
    }
</style>

<!-- Navbar Admin -->
<nav class="admin-navbar">
    <div class="navbar-container">
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
            <img src="{{ asset('image/logo.png') }}" alt="GulmaTrack Logo" class="logo-img">
            <span>GulmaTrack</span>
        </a>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
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

<!-- Main Content -->
<div class="admin-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> Dashboard Admin</h1>
        <p>Kelola dan pantau data penyebaran gulma secara real-time dengan visualisasi peta interaktif</p>
    </div>

    <!-- Success/Error Message -->
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
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="stat-label"><i class="fas fa-database"></i> Total Data Gulma</div>
            <div class="stat-value" id="statTotalData">{{ $totalDataGulma ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="stat-label"><i class="fas fa-map-marker-alt"></i> Wilayah Aktif</div>
            <div class="stat-value" id="statWilayahAktif">{{ $wilayahAktif ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-th"></i>
            </div>
            <div class="stat-label"><i class="fas fa-th"></i> Total Features</div>
            <div class="stat-value" id="statTotalTanaman">{{ $totalTanaman ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-file-upload"></i>
            </div>
            <div class="stat-label"><i class="fas fa-file-upload"></i> Upload Terbaru</div>
            <div class="stat-value" id="statUploadTerbaru">{{ $importTerbaru->total() ?? 0 }}</div>
        </div>
    </div>

<!-- Content Grid -->
    <div class="content-grid">
        <!-- Upload CSV Card -->
        <div class="card1">
            <h2><i class="fas fa-cloud-upload-alt"></i> Upload Data CSV</h2>
            
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf

                <!-- Periode Selection -->
                <div class="form-group" style="margin-bottom: 20px; position: relative; z-index: 100;">
                    <label for="tahun" style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50; font-family: 'Poppins', sans-serif; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px;">
                        <i class="fas fa-calendar-alt" style="color: var(--accent-color);"></i> Pilih Tahun:
                    </label>
                    <input 
                        type="text" 
                        id="tahun" 
                        name="tahun" 
                        required 
                        pattern="[0-9]{4}" 
                        min="1900" 
                        placeholder="Masukkan tahun (misal: 2025)"
                        style="width: 100%; padding: 12px 14px; border: 2px solid #e3eae8; border-radius: 10px; font-size: 14px; background-color: white; font-family: 'Poppins', sans-serif; font-weight: 500; color: #2c3e50; transition: all 0.3s ease; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);">
                    <small style="display: block; margin-top: 6px; font-size: 11px; color: #7f8c8d; font-family: 'Poppins', sans-serif;">
                        <i class="fas fa-info-circle"></i> Masukkan tahun 4 digit angka
                    </small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 12px; font-weight: 600; color: #2c3e50; font-family: 'Poppins', sans-serif; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px;">
                        <i class="fas fa-calendar" style="color: var(--accent-color);"></i> Pilih Bulan:
                    </label>
                    <input type="hidden" id="bulan" name="bulan" required>
                    <div id="bulanGrid" style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px;">
                        <button type="button" class="grid-btn" data-value="1" onclick="selectMonth('1', 'Januari', event)">Januari</button>
                        <button type="button" class="grid-btn" data-value="2" onclick="selectMonth('2', 'Februari', event)">Februari</button>
                        <button type="button" class="grid-btn" data-value="3" onclick="selectMonth('3', 'Maret', event)">Maret</button>
                        <button type="button" class="grid-btn" data-value="4" onclick="selectMonth('4', 'April', event)">April</button>
                        <button type="button" class="grid-btn" data-value="5" onclick="selectMonth('5', 'Mei', event)">Mei</button>
                        <button type="button" class="grid-btn" data-value="6" onclick="selectMonth('6', 'Juni', event)">Juni</button>
                        <button type="button" class="grid-btn" data-value="7" onclick="selectMonth('7', 'Juli', event)">Juli</button>
                        <button type="button" class="grid-btn" data-value="8" onclick="selectMonth('8', 'Agustus', event)">Agustus</button>
                        <button type="button" class="grid-btn" data-value="9" onclick="selectMonth('9', 'September', event)">September</button>
                        <button type="button" class="grid-btn" data-value="10" onclick="selectMonth('10', 'Oktober', event)">Oktober</button>
                        <button type="button" class="grid-btn" data-value="11" onclick="selectMonth('11', 'November', event)">November</button>
                        <button type="button" class="grid-btn" data-value="12" onclick="selectMonth('12', 'Desember', event)">Desember</button>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 12px; font-weight: 600; color: #2c3e50; font-family: 'Poppins', sans-serif; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px;">
                        <i class="fas fa-calendar-week" style="color: var(--accent-color);"></i> Pilih Minggu:
                    </label>
                    <input type="hidden" id="minggu" name="minggu" required>
                    <div id="mingguGrid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                        <button type="button" class="grid-btn" data-value="1" onclick="selectWeek('1', event)">Minggu 1</button>
                        <button type="button" class="grid-btn" data-value="2" onclick="selectWeek('2', event)">Minggu 2</button>
                        <button type="button" class="grid-btn" data-value="3" onclick="selectWeek('3', event)">Minggu 3</button>
                        <button type="button" class="grid-btn" data-value="4" onclick="selectWeek('4', event)">Minggu 4</button>
                    </div>
                </div>

                <!-- File Upload Area -->
                <div class="upload-area" id="uploadArea" onclick="checkPeriodeBeforeUpload()">
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
                        style="display: none;"
                    >
                </div>

                <!-- File Upload Status -->
                <div id="fileStatus" style="display: none; margin-top: 15px; padding: 15px 18px; background: #e8f5e9; border-left: 4px solid #128241; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); font-family: 'Poppins', sans-serif;">
                    <i class="fas fa-check-circle" style="color: #128241;"></i>
                    <span id="fileName" style="font-weight: 600; color: #128241; margin-left: 8px;"></span>
                    <button type="button" onclick="removeFile()" style="float: right; background: linear-gradient(135deg, #ffebee, #ffcdd2); border: 1px solid #ef5350; padding: 4px 12px; border-radius: 8px; color: #d32f2f; cursor: pointer; font-size: 12px; font-weight: 600; font-family: 'Poppins', sans-serif; transition: all 0.3s ease;">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                </div><br>

                <button type="submit" class="upload-btn" id="uploadBtn" disabled style="opacity: 0.5; cursor: not-allowed;">
                    <i class="fas fa-upload"></i> Upload File
                </button>

                <div id="uploadMessage" class="message"></div>

                @error('file')
                    <div class="alert alert-error" style="margin-top: 10px;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </form>
        </div>
        
        <!-- Info Card -->
        <div class="card">
            <h2><i class="fas fa-info-circle"></i> Informasi</h2>
            
            <div style="padding: 10px 0; color: #666; line-height: 1.8; font-family: 'Poppins', sans-serif;">
                <p style="font-weight: 600; color: #2c3e50;"><strong>Selamat datang di Dashboard Admin!</strong></p>
                <p style="margin-top: 15px; font-size: 14px;">
                    Gunakan form di sebelah kiri untuk:
                </p>
                <ul style="margin-left: 20px; margin-top: 10px; font-size: 14px;">
                    <li style="margin-bottom: 8px;">📤 Upload file CSV dengan data penyebaran gulma</li>
                    <li style="margin-bottom: 8px;">📊 Melihat statistik data yang telah diupload</li>
                    <li style="margin-bottom: 8px;">🗺️ Mengelola informasi wilayah dan tanaman</li>
                </ul>
                <p style="margin-top: 20px; padding: 15px; background: #fff8e1; border-left: 4px solid var(--accent-color); border-radius: 8px; font-size: 13px; color: #666;">
                    <strong style="color: var(--accent-color);">📋 Format CSV yang valid:</strong><br>
                    <span style="font-size: 12px; line-height: 1.6; display: block; margin-top: 8px;">
                        PG, FM, Wilayah, SEKSI, Neto, Hasil, Umur Tanaman, Penanggungjawab, Kode Aktf, ACTIVITAS, KATEGORI, TK/HA, TOTAL TK
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Map Card -->
    <div class="card full-width">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h2 style="margin: 0;"><i class="fas fa-map"></i> Peta Wilayah - Status Gulma</h2>
            
            <!-- Publish Map Button -->
            <div>
                <button type="button" id="publishMapBtn" onclick="publishMapToPublic()" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none; padding: 13px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(18, 130, 65, 0.2); transition: all 0.3s ease; font-family: 'Poppins', sans-serif; letter-spacing: 0.6px; font-size: 14px;">
                    <i class="fas fa-globe"></i>
                    <span>Perbarui Peta Publik</span>
                </button>
                <div id="publishStatus" style="margin-top: 8px; font-size: 12px; color: #666; text-align: right; font-family: 'Poppins', sans-serif;"></div>
            </div>
        </div>
        
        <div id="map" class="map-container"></div>
        
        <div class="map-legend">
            <h4 onclick="filterByStatus('')" style="cursor:pointer;">
                <i class="fas fa-info-circle"></i> Status Gulma
            </h4>
            <div class="legend-item" onclick="filterByStatus('bersih')" title="Klik untuk filter">
                <div class="legend-color" style="background: #3498db;"></div>
                <span><strong>Bersih</strong></span>
            </div>
            <div class="legend-item" onclick="filterByStatus('ringan')" title="Klik untuk filter">
                <div class="legend-color" style="background: #128241;"></div>
                <span><strong>Ringan</strong></span>
            </div>
            <div class="legend-item" onclick="filterByStatus('sedang')" title="Klik untuk filter">
                <div class="legend-color" style="background: #f1c40f;"></div>
                <span><strong>Sedang</strong></span>
            </div>
            <div class="legend-item" onclick="filterByStatus('berat')" title="Klik untuk filter">
                <div class="legend-color" style="background: #e74c3c;"></div>
                <span><strong>Berat</strong></span>
            </div>
            <div class="legend-item" onclick="filterByStatus('belum_dimonitoring')" title="Klik untuk filter">
                <div class="legend-color" style="background: #ecf0f1; border-color: #8b8b8b;"></div>
                <span><strong>Belum Dimonitoring</strong></span>
            </div>
        </div>
    </div><br><br>

    <!-- Recent Uploads -->
    <div class="card full-width">
        <h2><i class="fas fa-history"></i> Riwayat Upload</h2>
        
        @if (isset($importTerbaru) && $importTerbaru->total() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-file"></i> Nama File</th>
                            <th><i class="fas fa-map-marker-alt"></i> Wilayah</th>
                            <th><i class="fas fa-calendar"></i> Periode</th>
                            <th><i class="fas fa-database"></i> Total Records</th>
                            <th><i class="fas fa-info-circle"></i> Status</th>
                            <th><i class="fas fa-clock"></i> Waktu Upload</th>
                            <th><i class="fas fa-cog"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($importTerbaru as $log)
                            <tr>
                                <td><strong>#{{ $log->id }}</strong></td>
                                <td>{{ $log->nama_file }}</td>
                                <td>
                                    @php
                                        $wilayahIds = explode(',', $log->wilayah_id);
                                        $wilayahCount = count($wilayahIds);
                                    @endphp
                                    
                                    @if ($wilayahCount > 1)
                                        <span style="display: inline-block; padding: 6px 12px; background: linear-gradient(135deg, #f3e5f5, #e1bee7); border-radius: 12px; font-size: 11px; font-weight: 600; color: #7b1fa2; font-family: 'Poppins', sans-serif; border: 1px solid #ce93d8;">
                                            <i class="fas fa-map-marked-alt"></i>
                                            {{ $wilayahCount }} Wilayah ({{ $log->wilayah_id }})
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 6px 12px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 12px; font-size: 11px; font-weight: 600; color: #128241; font-family: 'Poppins', sans-serif; border: 1px solid #a5d6a7;">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Wilayah {{ $log->wilayah_id }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($log->tahun && $log->bulan && $log->minggu)
                                        <span style="display: inline-block; padding: 6px 12px; background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: 12px; font-size: 11px; font-weight: 600; color: #1976d2; font-family: 'Poppins', sans-serif; border: 1px solid #90caf9;">
                                            <i class="fas fa-calendar"></i>
                                            {{ $log->tahun }} / {{ DateTime::createFromFormat('!m', $log->bulan)->format('M') }} - Minggu {{ $log->minggu }}
                                        </span>
                                    @else
                                        <span style="color: #999;">-</span>
                                    @endif
                                </td>
                                <td>{{ $log->jumlah_records }}</td>
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
                                    @if ($log->status === 'failed' || $log->status === 'error')
                                        <div style="font-size: 11px; color: #E74C3C; margin-top: 4px; font-style: italic;">
                                            {{ $log->error_message ?? 'Format CSV tidak valid. Periksa kolom: PG, FM, Wilayah, SEKSI. Upload ulang dengan format yang benar.' }}
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <button onclick="loadImportDataOnMap({{ $log->id }}, '{{ $log->wilayah_id }}', {{ $log->tahun ?? 'null' }}, {{ $log->bulan ?? 'null' }}, {{ $log->minggu ?? 'null' }})" 
                                            style="padding: 8px 14px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 11px; font-weight: 600; font-family: 'Poppins', sans-serif; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;"
                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(18, 130, 65, 0.3)'"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <i class="fas fa-map"></i>
                                        <span>Tampilkan di Peta</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if ($importTerbaru->hasPages())
                <div style="margin-top: 25px; display: flex; justify-content: center; align-items: center; gap: 8px; font-family: 'Poppins', sans-serif;">
                    @if ($importTerbaru->onFirstPage())
                        <span style="padding: 10px 16px; background: #e3eae8; color: #999; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: not-allowed;">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $importTerbaru->previousPageUrl() }}" style="padding: 10px 16px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.3s ease;">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach ($importTerbaru->getUrlRange(1, $importTerbaru->lastPage()) as $page => $url)
                        @if ($page == $importTerbaru->currentPage())
                            <span style="padding: 10px 16px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border-radius: 8px; font-size: 13px; font-weight: 600; box-shadow: 0 4px 12px rgba(18, 130, 65, 0.3);">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" style="padding: 10px 16px; background: white; color: var(--primary-color); border: 2px solid #e3eae8; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.3s ease;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if ($importTerbaru->hasMorePages())
                        <a href="{{ $importTerbaru->nextPageUrl() }}" style="padding: 10px 16px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.3s ease;">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span style="padding: 10px 16px; background: #e3eae8; color: #999; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: not-allowed;">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada riwayat upload</p>
            </div>
        @endif
    </div>

<!-- Leaflet Library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
    const statusColors = {
        'Bersih': '#3498db',
        'Ringan': '#128241',
        'Sedang': '#f1c40f',
        'Berat': '#e74c3c'
    };

    // Warna berdasarkan kategori dari CSV
    function getColorByKategori(kategori) {
        if (!kategori) return '#ffffff'; // Putih - Tidak ada data
        
        const kat = kategori.toLowerCase().trim();
        if (kat === 'bersih') return '#3498db'; // Biru
        if (kat === 'ringan') return '#128241'; // Hijau
        if (kat === 'sedang') return '#f1c40f'; // Kuning
        if (kat === 'berat') return '#e74c3c'; // Merah
        
        return '#ffffff'; // Putih - Tidak dikenali
    }

    let map;
    let geoJsonLayers = {};

    // Initialize map
    function initMap() {
        console.log('Starting initMap...');
        const mapContainer = document.getElementById('map');
        
        if (!mapContainer) {
            console.error('Map container not found!');
            return;
        }
        
        console.log('Map container found, dimensions:', mapContainer.offsetWidth, 'x', mapContainer.offsetHeight);
        
        if (map) {
            console.log('Removing existing map...');
            map.remove();
        }
        
        try {
            console.log('Creating new map instance...');
            map = L.map('map').setView([-7.5, 107], 11);
            
            console.log('Adding tile layer...');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
            
            console.log('Map initialized successfully!');
            
            // Load all wilayah after map is initialized
            setTimeout(() => {
                console.log('Loading data...');
                loadLatestPublishedData();
            }, 500);
        } catch (error) {
            console.error('Error initializing map:', error);
        }
    }

    // Load latest published data on map
    async function loadLatestPublishedData() {
        console.log('=== Loading latest published data ===');

        try {
            // Get latest published import log
            const response = await fetch('/api/map-publications/latest-published');
            
            if (!response.ok) {
                console.log('No published data found, loading all wilayah');
                loadAllWilayah();
                return;
            }

            const publication = await response.json();
            console.log('Latest publication:', publication);

            if (publication.import_id) {
                // Load data from specific import
                await loadDataFromImport(publication.import_id, publication.tahun, publication.bulan, publication.minggu);
            } else {
                // Fallback to load all wilayah
                loadAllWilayah();
            }
        } catch (error) {
            console.error('Error loading latest published data:', error);
            // Fallback to load all wilayah
            loadAllWilayah();
        }
    }

    // Load data from specific import
    async function loadDataFromImport(importId, tahun, bulan, minggu) {
        console.log(`Loading data from import ${importId}: ${tahun}/${bulan}/w${minggu}`);
        
        try {
            const response = await fetch(`/api/data-gulma/by-import/${importId}`);
            const data = await response.json();
            
            console.log(`Loaded ${data.length} records from import ${importId}`);
            
            if (data.length === 0) {
                console.log('No data found, loading all wilayah');
                loadAllWilayah();
                return;
            }

            // Group by wilayah
            const byWilayah = {};
            data.forEach(record => {
                if (!byWilayah[record.wilayah]) {
                    byWilayah[record.wilayah] = [];
                }
                byWilayah[record.wilayah].push(record);
            });

            // Load each wilayah's geojson and merge with data
            const wilayahNumbers = Object.keys(byWilayah);
            const allBounds = [];
            let featuresAdded = 0;

            for (const wilayahNum of wilayahNumbers) {
                try {
                    const geoResponse = await fetch(`/api/wilayah/geojson/${wilayahNum}`);
                    const geojson = await geoResponse.json();
                    
                    // Merge data dengan geojson
                    const dataRecords = byWilayah[wilayahNum];
                    geojson.features.forEach(feature => {
                        const seksi = feature.properties.seksi || feature.properties.Seksi || feature.properties.SEKSI;
                        const matchingRecord = dataRecords.find(r => r.seksi === seksi);
                        
                        if (matchingRecord) {
                            // Merge data from CSV
                            feature.properties = {
                                ...feature.properties,
                                ...matchingRecord,
                                kategori: matchingRecord.kategori,
                                status_gulma: matchingRecord.status_gulma
                            };
                        }
                    });

                    const layer = L.geoJSON(geojson, {
                        style: function(feature) {
                            return getFeatureStyle(feature);
                        },
                        onEachFeature: function(feature, layer) {
                            if (feature.properties) {
                                layer.bindPopup(createPopupContent(feature.properties), {
                                    maxWidth: 300
                                });
                                
                                layer.on('mouseover', function() {
                                    this.setStyle({ weight: 3, opacity: 1 });
                                });
                                
                                layer.on('mouseout', function() {
                                    this.setStyle({ weight: 2, opacity: 0.9 });
                                });
                            }
                        }
                    }).addTo(map);

                    geoJsonLayers[wilayahNum] = layer;
                    featuresAdded += geojson.features.length;
                    
                    const bounds = layer.getBounds();
                    if (bounds.isValid()) {
                        allBounds.push(bounds);
                    }
                } catch (error) {
                    console.error(`Error loading wilayah ${wilayahNum}:`, error);
                }
            }

            // Fit map to show all wilayah
            if (allBounds.length > 0) {
                const combinedBounds = allBounds[0];
                allBounds.forEach(bounds => {
                    combinedBounds.extend(bounds);
                });
                map.fitBounds(combinedBounds, { padding: [50, 50] });
            }

            console.log(`✓ Loaded published data: ${featuresAdded} features from ${wilayahNumbers.length} wilayah`);
        } catch (error) {
            console.error('Error loading data from import:', error);
            loadAllWilayah();
        }
    }

    // Load all wilayah with data from database
    function loadAllWilayah() {
        console.log('=== Loading all wilayah with data ===');

        // Get all wilayah that have data
        fetch('/api/wilayah/data')
            .then(response => {
                console.log('Response from /api/wilayah/data:', response.status);
                return response.json();
            })
            .then(summary => {
                console.log('Summary data:', summary);
                const wilayahNumbers = summary.data.map(w => w.wilayah);
                console.log('Wilayah numbers to load:', wilayahNumbers);
                
                if (wilayahNumbers.length === 0) {
                    console.log('No wilayah data found');
                    return;
                }

                // Load each wilayah
                const promises = wilayahNumbers.map(num => 
                    fetch(`/api/wilayah/geojson/${num}`)
                        .then(r => {
                            console.log(`Loaded wilayah ${num}, status: ${r.status}`);
                            return r.json();
                        })
                        .then(data => {
                            console.log(`Wilayah ${num} has ${data.features ? data.features.length : 0} features`);
                            return { wilayah: num, data };
                        })
                        .catch(err => {
                            console.error(`Error loading wilayah ${num}:`, err);
                            return null;
                        })
                );

                return Promise.all(promises);
            })
            .then(results => {
                if (!results) {
                    console.log('No results to process');
                    return;
                }
                
                console.log(`Processing ${results.length} wilayah results...`);
                const allBounds = [];
                let featuresAdded = 0;

                results.forEach(result => {
                    if (!result || !result.data || !result.data.features || result.data.features.length === 0) {
                        console.log('Skipping empty result');
                        return;
                    }

                    const { wilayah, data } = result;
                    console.log(`Adding wilayah ${wilayah} to map with ${data.features.length} features`);

                    const layer = L.geoJSON(data, {
                        style: function(feature) {
                            const style = getFeatureStyle(feature);
                            return style;
                        },
                        onEachFeature: function(feature, layer) {
                            if (feature.properties) {
                                layer.bindPopup(createPopupContent(feature.properties), {
                                    maxWidth: 300
                                });
                                
                                layer.on('mouseover', function() {
                                    this.setStyle({ weight: 3, opacity: 1 });
                                });
                                
                                layer.on('mouseout', function() {
                                    this.setStyle({ weight: 2, opacity: 0.9 });
                                });
                            }
                        }
                    }).addTo(map);

                    geoJsonLayers[wilayah] = layer;
                    featuresAdded += data.features.length;
                    
                    const bounds = layer.getBounds();
                    if (bounds.isValid()) {
                        allBounds.push(bounds);
                    }
                });

                // Fit map to show all wilayah
                if (allBounds.length > 0) {
                    const combinedBounds = allBounds[0];
                    allBounds.forEach(bounds => {
                        combinedBounds.extend(bounds);
                    });
                    map.fitBounds(combinedBounds, { padding: [50, 50] });
                    console.log(`✓ Map bounds adjusted to show all wilayah`);
                }

                console.log(`✓ SUCCESS: Loaded ${Object.keys(geoJsonLayers).length} wilayah with ${featuresAdded} total features`);
            })
            .catch(error => {
                console.error('ERROR loading wilayah:', error);
            });
    }

    // Get feature style based on data from CSV
    function getFeatureStyle(feature) {
        const props = feature.properties || {};
        let fillColor = '#ffffff'; // default putih untuk tidak ada data
        let borderColor = '#cccccc';
        
        // Prioritas: kategori > status_gulma > activitas
        if (props.kategori) {
            fillColor = getColorByKategori(props.kategori);
            borderColor = fillColor;
            console.log(`Feature ${props.seksi || props.id_feature} - Kategori: ${props.kategori} - Color: ${fillColor}`);
        } else if (props.status_gulma) {
            fillColor = statusColors[props.status_gulma] || fillColor;
            borderColor = fillColor;
        } else if (props.activitas) {
            const act = props.activitas.toLowerCase();
            if (act.includes('pemupukan')) fillColor = '#128241';
            else if (act.includes('penyemprotan')) fillColor = '#f1c40f';
            else if (act.includes('pembersihan')) fillColor = '#3498db';
        }

        return {
            color: borderColor,
            weight: 2,
            opacity: 0.9,
            fillColor: fillColor,
            fillOpacity: 0.6
        };
    }

    // Create popup content
    function createPopupContent(props) {
        let html = '<div style="padding: 10px; font-family: \'Poppins\'; font-size: 13px;">';
        
        // Feature ID / Lokasi / Seksi
        const locationId = props.seksi || props.id_feature || props.Lokasi || props.SEKSI || props.Seksi || props.id;
        if (locationId) {
            html += `<h4 style="margin: 0 0 10px 0; color: #128241; font-size: 14px;">`;
            html += `<i class="fas fa-map-marker-alt"></i> ${locationId}`;
            html += `</h4>`;
        }

        // PG dan FM
        if (props.pg) {
            html += `<div style="margin-bottom: 5px;"><span style="font-weight: 600;">PG:</span> ${props.pg}</div>`;
        }
        if (props.fm) {
            html += `<div style="margin-bottom: 5px;"><span style="font-weight: 600;">FM:</span> ${props.fm}</div>`;
        }

        // Aktivitas dan Kategori
        if (props.activitas) {
            html += `<div style="margin-bottom: 5px;"><span style="font-weight: 600;">Aktivitas:</span> ${props.activitas}</div>`;
        }
        if (props.kategori) {
            const color = getColorByKategori(props.kategori);
            html += `<div style="margin-bottom: 5px;"><span style="font-weight: 600;">Kategori:</span> <span style="color: ${color}; font-weight: 700;">${props.kategori}</span></div>`;
        }

        // Neto dan Hasil
        if (props.neto) {
            html += `<div style="margin-bottom: 5px;"><span style="font-weight: 600;">Neto:</span> ${props.neto}</div>`;
        }
        if (props.hasil) {
            html += `<div style="margin-bottom: 5px;"><span style="font-weight: 600;">Hasil:</span> ${props.hasil}</div>`;
        }

        // Umur Tanaman
        if (props.umur_tanaman) {
            html += `<div style="margin-bottom: 5px;"><span style="font-weight: 600;">Umur Tanaman:</span> ${props.umur_tanaman} hari</div>`;
        }

        // Penanggungjawab
        if (props.penanggungjawab) {
            html += `<div style="margin-bottom: 5px;"><span style="font-weight: 600;">Penanggungjawab:</span> ${props.penanggungjawab}</div>`;
        }

        // TK/HA dan Total TK
        if (props.tk_ha) {
            html += `<div style="margin-bottom: 5px;"><span style="font-weight: 600;">TK/HA:</span> ${props.tk_ha}</div>`;
        }
        if (props.total_tk) {
            html += `<div style="margin-bottom: 5px;"><span style="font-weight: 600;">Total TK:</span> ${props.total_tk}</div>`;
        }

        // Tanggal
        if (props.tanggal) {
            html += `<div style="margin-bottom: 5px;">`;
            html += `<span style="font-weight: 600;">Tanggal:</span> ${props.tanggal}`;
            html += `</div>`;
        }

        // Status Gulma (old data)
        if (props.status_gulma) {
            const statusColor = statusColors[props.status_gulma] || '#95a5a6';
            html += `<div style="margin-bottom: 5px;">`;
            html += `<span style="font-weight: 600;">Status:</span> `;
            html += `<span style="color: ${statusColor}; font-weight: 700;">${props.status_gulma}</span>`;
            html += `</div>`;
        }

        // Persentase (old data)
        if (props.persentase !== undefined) {
            html += `<div style="margin-bottom: 5px;">`;
            html += `<span style="font-weight: 600;">Persentase:</span> ${props.persentase}%`;
            html += `</div>`;
        }

        // Wilayah
        if (props.wilayah || props.Wilayah) {
            html += `<div style="color: #7f8c8d; font-size: 12px; margin-top: 8px;">`;
            html += `<i class="fas fa-location-arrow"></i> Wilayah ${props.wilayah || props.Wilayah}`;
            html += `</div>`;
        }

        html += '</div>';
        return html;
    }

    // Form upload
    document.getElementById('uploadForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const bulan = document.getElementById('bulan').value;
        const minggu = document.getElementById('minggu').value;
        const file = document.getElementById('csvFile').files[0];
        const messageDiv = document.getElementById('uploadMessage');

        const tahun = document.getElementById('tahun').value;
        const bulan = document.getElementById('bulan').value;
        const minggu = document.getElementById('minggu').value;

        if (!tahun || !bulan || !minggu) {
            messageDiv.innerHTML = '✗ Pilih tahun, bulan, dan minggu terlebih dahulu';
            messageDiv.className = 'message show error';
            return;
        }

        if (!file) {
            messageDiv.innerHTML = '✗ Pilih file CSV terlebih dahulu';
            messageDiv.className = 'message show error';
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('tahun', tahun);
        formData.append('bulan', bulan);
        formData.append('minggu', minggu);
        formData.append('_token', document.querySelector('[name="_token"]').value);

        document.getElementById('uploadBtn').disabled = true;
        messageDiv.innerHTML = '<div class="loading-spinner"></div> Memproses file...';
        messageDiv.className = 'message show';

        try {
            const res = await fetch('{{ route("admin.upload-csv") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (res.ok) {
                const data = await res.json();
                const fileName = file.name;
                messageDiv.innerHTML = `✓ ${data.message}<br><small style="font-size: 12px; opacity: 0.9;">File: ${fileName}</small>`;
                messageDiv.className = 'message show success';
                
                console.log('Upload berhasil, mulai reload peta...');
                
                // Clear form
                document.getElementById('csvFile').value = '';
                document.getElementById('fileStatus').style.display = 'none';
                document.getElementById('uploadBtn').disabled = true;
                document.getElementById('uploadBtn').style.opacity = '0.5';
                document.getElementById('uploadBtn').style.cursor = 'not-allowed';
                
                // Update statistics
                console.log('Update statistics...');
                updateStatistics();
                
                // Reload map with new data
                if (map) {
                    console.log('Clear existing layers...');
                    // Clear existing layers
                    Object.keys(geoJsonLayers).forEach(key => {
                        if (geoJsonLayers[key]) {
                            map.removeLayer(geoJsonLayers[key]);
                        }
                    });
                    geoJsonLayers = {};
                    
                    // Reload all wilayah with new data immediately
                    console.log('Reload all wilayah...');
                    setTimeout(() => {
                        loadAllWilayah();
                    }, 500);
                } else {
                    console.error('Map tidak ditemukan!');
                }
                
                // Message tetap tampil (tidak dihilangkan)
            } else {
                const errorData = await res.json();
                messageDiv.innerHTML = `✗ ${errorData.message || 'Terjadi kesalahan'}`;
                messageDiv.className = 'message show error';
            }
        } catch (error) {
            messageDiv.innerHTML = `✗ Error: ${error.message}`;
            messageDiv.className = 'message show error';
        } finally {
            document.getElementById('uploadBtn').disabled = false;
            document.getElementById('uploadBtn').style.opacity = '1';
            document.getElementById('uploadBtn').style.cursor = 'pointer';
        }
    });

    // Grid Selection Functions (Direct Click)
    function selectMonth(value, label, event) {
        event.preventDefault();
        document.getElementById('bulan').value = value;
        
        // Remove selected class from all month buttons
        document.querySelectorAll('#bulanGrid .grid-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        
        // Add selected class to clicked button
        event.target.classList.add('selected');
        
        // Enable upload button when all fields are filled
        checkFormComplete();
    }

    function selectWeek(value, event) {
        event.preventDefault();
        document.getElementById('minggu').value = value;
        
        // Remove selected class from all week buttons
        document.querySelectorAll('#mingguGrid .grid-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        
        // Add selected class to clicked button
        event.target.classList.add('selected');
        
        // Enable upload button when all fields are filled
        checkFormComplete();
    }

    function checkFormComplete() {
        const tahun = document.getElementById('tahun').value;
        const bulan = document.getElementById('bulan').value;
        const minggu = document.getElementById('minggu').value;
        const uploadBtn = document.getElementById('uploadBtn');
        
        if (tahun && bulan && minggu) {
            uploadBtn.disabled = false;
            uploadBtn.style.opacity = '1';
            uploadBtn.style.cursor = 'pointer';
        }
    }

    // Check periode before upload
    function checkPeriodeBeforeUpload() {
        const tahun = document.getElementById('tahun').value;
        const bulan = document.getElementById('bulan').value;
        const minggu = document.getElementById('minggu').value;
        const messageDiv = document.getElementById('uploadMessage');
        
        if (!tahun || !bulan || !minggu) {
            messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Pilih tahun, bulan, dan minggu terlebih dahulu';
            messageDiv.className = 'message show error';
            setTimeout(() => {
                messageDiv.className = 'message';
            }, 3000);
            return;
        }

        // Validasi tahun
        const tahunNum = parseInt(tahun);
        if (isNaN(tahunNum) || tahunNum < 1900 || tahun.length !== 4) {
            messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Tahun harus 4 digit dan minimal 1900';
            messageDiv.className = 'message show error';
            setTimeout(() => {
                messageDiv.className = 'message';
            }, 3000);
            return;
        }
        
        document.getElementById('csvFile').click();
    }

    // Add event listener for tahun input
    document.getElementById('tahun').addEventListener('input', function() {
        checkFormComplete();
    });

    // File selection handler
    document.getElementById('csvFile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileStatus').style.display = 'block';
            document.getElementById('uploadBtn').disabled = false;
            document.getElementById('uploadBtn').style.opacity = '1';
            document.getElementById('uploadBtn').style.cursor = 'pointer';
        }
    });

    // Remove file function
    function removeFile() {
        document.getElementById('csvFile').value = '';
        document.getElementById('fileStatus').style.display = 'none';
        document.getElementById('uploadBtn').disabled = true;
        document.getElementById('uploadBtn').style.opacity = '0.5';
        document.getElementById('uploadBtn').style.cursor = 'not-allowed';
    }

    // Drag and drop
    const uploadArea = document.querySelector('.upload-area');
    const fileInput = document.getElementById('csvFile');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => {
        uploadArea.addEventListener(evt, e => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    ['dragenter', 'dragover'].forEach(evt => {
        uploadArea.addEventListener(evt, () => {
            uploadArea.style.borderColor = '#128241';
            uploadArea.style.background = '#f0f0f0';
        });
    });

    ['dragleave', 'drop'].forEach(evt => {
        uploadArea.addEventListener(evt, () => {
            uploadArea.style.borderColor = '#D6DF20';
            uploadArea.style.background = 'transparent';
        });
    });

    uploadArea.addEventListener('drop', (e) => {
        const bulan = document.getElementById('bulan').value;
        const minggu = document.getElementById('minggu').value;
        
        if (!bulan || !minggu) {
            const messageDiv = document.getElementById('uploadMessage');
            messageDiv.innerHTML = '✗ Pilih bulan dan minggu terlebih dahulu';
            messageDiv.className = 'message show error';
            setTimeout(() => {
                messageDiv.className = 'message';
            }, 3000);
            return;
        }
        
        fileInput.files = e.dataTransfer.files;
        if (fileInput.files[0]) {
            document.getElementById('fileName').textContent = fileInput.files[0].name;
            document.getElementById('fileStatus').style.display = 'block';
            document.getElementById('uploadBtn').disabled = false;
            document.getElementById('uploadBtn').style.opacity = '1';
            document.getElementById('uploadBtn').style.cursor = 'pointer';
        }
    });

    // Initialize
    initMap();
    loadPublicationStatus();

    // Update statistics
    async function updateStatistics() {
        try {
            const res = await fetch('{{ route("admin.statistics") }}');
            const result = await res.json();
            
            if (result.success) {
                const stats = result.data;
                document.getElementById('statTotalData').textContent = stats.totalDataGulma;
                document.getElementById('statWilayahAktif').textContent = stats.wilayahAktif;
                document.getElementById('statTotalTanaman').textContent = stats.totalTanaman;
                document.getElementById('statUploadTerbaru').textContent = stats.uploadTerbaru;
            }
        } catch (error) {
            console.error('Error updating statistics:', error);
        }
    }

    // Load publication status
    async function loadPublicationStatus() {
        try {
            const res = await fetch('{{ route("admin.publication-status") }}');
            const data = await res.json();
            
            if (data.success && data.is_published) {
                document.getElementById('publishStatus').innerHTML = 
                    `<i class="fas fa-check-circle" style="color: #128241;"></i> Terakhir dipublikasi: ${data.published_at}`;
            } else {
                document.getElementById('publishStatus').innerHTML = 
                    `<i class="fas fa-exclamation-circle" style="color: #f39c12;"></i> Belum dipublikasi`;
            }
        } catch (error) {
            console.error('Error loading publication status:', error);
        }
    }

    // Publish map to public
    async function publishMapToPublic() {
        const btn = document.getElementById('publishMapBtn');
        const originalHtml = btn.innerHTML;
        
        if (!confirm('Apakah Anda yakin ingin memperbarui peta publik dengan data terbaru?')) {
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

        try {
            const res = await fetch('{{ route("admin.publish-map") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                }
            });

            const data = await res.json();

            if (data.success) {
                // Show success message
                const statusDiv = document.getElementById('publishStatus');
                statusDiv.innerHTML = `<i class="fas fa-check-circle" style="color: #128241;"></i> ${data.message}`;
                statusDiv.style.color = '#128241';
                
                // Update button
                btn.innerHTML = '<i class="fas fa-check"></i> Berhasil Dipublikasi!';
                btn.style.background = 'linear-gradient(135deg, #128241 0%, #2ecc71 100%)';
                
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.style.background = 'linear-gradient(135deg, var(--primary-color), var(--secondary-color))';
                    loadPublicationStatus();
                }, 3000);
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            alert('Terjadi kesalahan: ' + error.message);
        } finally {
            btn.disabled = false;
        }
    }

    // Filter by status function
    let currentFilter = '';
    function filterByStatus(status) {
        console.log('Filter by status:', status);
        currentFilter = status;
        
        Object.keys(geoJsonLayers).forEach(wilayahKey => {
            const layer = geoJsonLayers[wilayahKey];
            if (!layer) return;
            
            layer.eachLayer(function(featureLayer) {
                const props = featureLayer.feature.properties;
                
                if (!status) {
                    // Show all
                    featureLayer.setStyle({ fillOpacity: 0.6, opacity: 0.9 });
                } else {
                    // Filter based on kategori
                    const kategori = (props.kategori || '').toLowerCase().trim();
                    const statusGulma = (props.status_gulma || '').toLowerCase().trim();
                    
                    let match = false;
                    if (status === 'bersih' && (kategori === 'bersih' || statusGulma === 'bersih')) {
                        match = true;
                    } else if (status === 'ringan' && (kategori === 'ringan' || statusGulma === 'ringan')) {
                        match = true;
                    } else if (status === 'sedang' && (kategori === 'sedang' || statusGulma === 'sedang')) {
                        match = true;
                    } else if (status === 'berat' && (kategori === 'berat' || statusGulma === 'berat')) {
                        match = true;
                    } else if (status === 'belum_dimonitoring' && !kategori && !statusGulma) {
                        match = true;
                    }
                    
                    if (match) {
                        featureLayer.setStyle({ fillOpacity: 0.6, opacity: 0.9 });
                    } else {
                        featureLayer.setStyle({ fillOpacity: 0.1, opacity: 0.2 });
                    }
                }
            });
        });
    }

    // Load import data on map
    async function loadImportDataOnMap(importId, wilayahIds, tahun, bulan, minggu) {
        console.log(`Loading import data: ID=${importId}, Wilayah=${wilayahIds}, Period=${tahun}/${bulan}/${minggu}`);
        
        // Clear existing layers
        Object.keys(geoJsonLayers).forEach(key => {
            if (geoJsonLayers[key]) {
                map.removeLayer(geoJsonLayers[key]);
            }
        });
        geoJsonLayers = {};
        
        // Parse wilayah IDs
        const wilayahArray = wilayahIds.split(',').map(id => id.trim());
        
        // Load each wilayah with specific period filter
        const promises = wilayahArray.map(wilayahNum => {
            let url = `/api/wilayah/geojson/${wilayahNum}`;
            if (tahun && bulan && minggu) {
                url += `?tahun=${tahun}&bulan=${bulan}&minggu=${minggu}`;
            }
            
            return fetch(url)
                .then(r => r.json())
                .then(data => ({ wilayah: wilayahNum, data }))
                .catch(err => {
                    console.error(`Error loading wilayah ${wilayahNum}:`, err);
                    return null;
                });
        });
        
        Promise.all(promises).then(results => {
            const allBounds = [];
            
            results.forEach(result => {
                if (!result || !result.data || !result.data.features || result.data.features.length === 0) {
                    return;
                }
                
                const { wilayah, data } = result;
                
                const layer = L.geoJSON(data, {
                    style: function(feature) {
                        return getFeatureStyle(feature);
                    },
                    onEachFeature: function(feature, layer) {
                        if (feature.properties) {
                            layer.bindPopup(createPopupContent(feature.properties), {
                                maxWidth: 300
                            });
                            
                            layer.on('mouseover', function() {
                                this.setStyle({ weight: 3, opacity: 1 });
                            });
                            
                            layer.on('mouseout', function() {
                                this.setStyle({ weight: 2, opacity: 0.9 });
                            });
                        }
                    }
                }).addTo(map);
                
                geoJsonLayers[wilayah] = layer;
                
                const bounds = layer.getBounds();
                if (bounds.isValid()) {
                    allBounds.push(bounds);
                }
            });
            
            // Fit map to show loaded data
            if (allBounds.length > 0) {
                const combinedBounds = allBounds[0];
                allBounds.forEach(bounds => {
                    combinedBounds.extend(bounds);
                });
                map.fitBounds(combinedBounds, { padding: [50, 50] });
            }
            
            // Scroll to map
            document.getElementById('map').scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    }

    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing map...');
        initMap();
    });
</script>

<script>
    // Add hover effects to select elements
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('mouseenter', function() {
                this.style.borderColor = 'var(--primary-color)';
                this.style.boxShadow = '0 6px 16px rgba(18, 130, 65, 0.12)';
                this.style.backgroundColor = '#fafdfb';
            });
            
            select.addEventListener('mouseleave', function() {
                if (this !== document.activeElement) {
                    this.style.borderColor = '#e3eae8';
                    this.style.boxShadow = '0 2px 6px rgba(0, 0, 0, 0.03)';
                    this.style.backgroundColor = 'white';
                }
            });
            
            select.addEventListener('focus', function() {
                this.style.borderColor = 'var(--primary-color)';
                this.style.boxShadow = '0 0 0 4px rgba(18, 130, 65, 0.1)';
            });
            
            select.addEventListener('blur', function() {
                this.style.borderColor = '#e3eae8';
                this.style.boxShadow = '0 2px 6px rgba(0, 0, 0, 0.03)';
                this.style.backgroundColor = 'white';
            });
        });
        
        // Add hover effect to publish button
        const publishBtn = document.getElementById('publishMapBtn');
        if (publishBtn) {
            publishBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 6px 16px rgba(18, 130, 65, 0.3)';
            });
            
            publishBtn.addEventListener('mouseleave', function() {
                if (!this.disabled) {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 4px 12px rgba(18, 130, 65, 0.2)';
                }
            });
        }
    });
</script>

@endsection