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
        font-family: 'Poppins';
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
        color: #ffffff;
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
        font-family: 'Poppins';
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
        font-family: 'Poppins';
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
        font-family: 'Poppins';
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
        font-family: 'Poppins';
    }

    .page-header h1 i {
        color: var(--title-color);
    }

    .page-header p {
        font-size: 16px;
        color: #666;
        font-family: 'Poppins';
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
        display: none;
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
        font-family: 'Poppins';
    }

    .stat-value {
        font-size: 36px;
        font-weight: 700;
        color: white;
        font-family: 'Poppins';
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
        display: none;
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
        font-family: 'Poppins';
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
        font-family: 'Poppins';
    }

    .upload-hint {
        font-size: 13px;
        color: #999;
        font-family: 'Poppins';
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
        font-family: 'Poppins';
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
        font-family: 'Poppins';
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
        font-family: 'Poppins';
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
        font-family: 'Poppins';
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
        font-family: 'Poppins';
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
        display: flex;
        flex-direction: column;
    }

    .map-container #map {
        width: 100% !important;
        height: 100% !important;
        flex: 1;
        background: #fff;
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
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
        color: #FBA919;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Poppins';
    }

    .legend-item {
        display: flex;
        align-items: center;
        font-size: 13px;
        cursor: pointer;
        padding: 3px 5px;
        margin-bottom: 2px;
        border-radius: 4px;
        transition: all 0.3s ease;
        font-family: 'Poppins';
    }

    .legend-item:hover {
        background: rgba(18, 130, 65, 0.1);
        transform: translateX(5px);
    }

    .legend-item:last-child {
        margin-bottom: 0px;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        margin-right: 8px;
        border-radius: 3px;
        border: 1px solid #ccc;
        flex-shrink: 0;
    }

    /* Wilayah filter items */
    .wilayah-item {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        background: white;
        color: #333;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Poppins';
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 2px solid #e0e0e0;
    }

    .wilayah-item:hover {
        background: #f5f5f5;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(251, 169, 25, 0.5);
    }

    .wilayah-item.active {
        background: #128241;
        color: white;
        border-color: #0a4a26;
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.5);
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
        font-family: 'Poppins';
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
        font-family: 'Poppins';
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

        /* Publication Management Styles */
        .btn-small {
            padding: 8px 14px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .btn-small:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-small:active {
            transform: translateY(0);
        }

        .file-option {
            padding: 12px;
            margin-bottom: 8px;
            border: 2px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            background: #fff;
            transition: all 0.2s ease;
        }

        .file-option:hover {
            border-color: #128241;
            background: #f0f8f5;
        }

        .file-option input[type="radio"] {
            cursor: pointer;
        }

        #publicationModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        #publicationModal > div {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        #publicationModal h3 {
            margin-top: 0;
            color: #128241;
            font-weight: 700;
            margin-bottom: 10px;
        }

        #filesList {
            margin-bottom: 20px;
            max-height: 300px;
            overflow-y: auto;
        }

        #filesList label {
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }
    }
</style>

<!-- Main Content -->
<div class="admin-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> Dashboard Admin</h1>
        <p>Kelola dan pantau data persebaran gulma secara real-time dengan visualisasi peta interaktif</p>
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
            <div class="stat-value" id="statUploadTerbaru">{{ $importTerbaru->count() ?? 0 }}</div>
        </div>
    </div>

<!-- Content Grid -->
    <div class="content-grid">
        <!-- Upload CSV Card -->
        <div class="card1">
            <h2 style="color: #FBA919;"><i class="fas fa-cloud-upload-alt" style="color: #FBA919;"></i> Upload Data CSV</h2>
            
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf

                <!-- Periode Selection -->
                <div class="form-group" style="margin-top: 15px; margin-bottom: 20px; position: relative; z-index: 100;">
                    <label for="tahun" style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50; font-family: 'Poppins'; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px;">
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
                        style="width: 100%; padding: 12px 14px; border: 2px solid #e3eae8; border-radius: 10px; font-size: 14px; background-color: white; font-family: 'Poppins'; font-weight: 500; color: #2c3e50; transition: all 0.3s ease; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);">
                    <small style="display: block; margin-top: 6px; font-size: 11px; color: #7f8c8d; font-family: 'Poppins';">
                        <i class="fas fa-info-circle"></i> Masukkan tahun 4 digit angka
                    </small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 12px; font-weight: 600; color: #2c3e50; font-family: 'Poppins'; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px;">
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
                    <label style="display: block; margin-bottom: 12px; font-weight: 600; color: #2c3e50; font-family: 'Poppins'; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px;">
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
                <div id="fileStatus" style="display: none; margin-top: 15px; padding: 15px 18px; background: #e8f5e9; border-left: 4px solid #128241; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); font-family: 'Poppins';">
                    <i class="fas fa-check-circle" style="color: #128241;"></i>
                    <span id="fileName" style="font-weight: 600; color: #128241; margin-left: 8px;"></span>
                    <button type="button" onclick="removeFile()" style="float: right; background: linear-gradient(135deg, #ffebee, #ffcdd2); border: 1px solid #ef5350; padding: 4px 12px; border-radius: 8px; color: #d32f2f; cursor: pointer; font-size: 12px; font-weight: 600; font-family: 'Poppins'; transition: all 0.3s ease;">
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
            
            <div style="padding: 10px 0; color: #666; line-height: 1.8; font-family: 'Poppins';">
                <p style="font-weight: 600; color: #2c3e50;"><strong>Selamat datang di Dashboard Admin!</strong></p>
                <p style="margin-top: 15px; font-size: 14px;">
                    Gunakan form di sebelah kiri untuk:
                </p>
                <ul style="margin-left: 20px; margin-top: 10px; font-size: 14px;">
                    <li style="margin-bottom: 8px;">📤 Upload file CSV dengan data persebaran gulma</li>
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
                <button type="button" id="publishMapBtn" onclick="publishMapToPublic()" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none; padding: 13px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(18, 130, 65, 0.2); transition: all 0.3s ease; font-family: 'Poppins'; letter-spacing: 0.6px; font-size: 14px;">
                    <i class="fas fa-globe"></i>
                    <span>Perbarui Peta Publik</span>
                </button>
                <div id="publishStatus" style="margin-top: 8px; font-size: 12px; color: #666; text-align: right; font-family: 'Poppins';"></div>
            </div>
        </div>
        
        <!-- Data Period Display -->
        <div id="dataPeriodDisplay" style="background: #ecf0f1; padding: 12px 20px; border-radius: 10px; margin-bottom: 15px; border-left: 4px solid #128241; display: none;">
            <div style="display: flex; align-items: center; gap: 10px; font-family: 'Poppins';">
                <i class="fas fa-calendar-check" style="color: #FBA919; font-size: 18px;"></i>
                <span style="font-weight: 600; color: #FBA919; font-size: 14px;" id="periodText">Menampilkan Semua Data</span>
            </div>
        </div>
        
        <div class="map-container">
            <div id="map"></div>
            <div id="mapLoadingIndicator" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: none; z-index: 999; font-family: 'Poppins'; text-align: center;">
                <div style="font-size: 16px; color: #128241; margin-bottom: 10px;">
                    <i class="fas fa-spinner fa-spin"></i> Memuat Peta...
                </div>
                <div style="font-size: 12px; color: #666;">Silakan tunggu sebentar</div>
            </div>
            <div id="mapErrorIndicator" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: none; z-index: 999; font-family: 'Poppins'; text-align: center; max-width: 300px;">
                <div style="font-size: 16px; color: #e74c3c; margin-bottom: 10px;">
                    <i class="fas fa-exclamation-circle"></i> Error
                </div>
                <div style="font-size: 12px; color: #666;" id="mapErrorText">Peta gagal dimuat</div>
            </div>
            <div class="map-legend">
                <h4 onclick="filterByStatus('')" style="cursor:pointer;">
                    <i class="fas fa-info-circle"></i> Status Gulma
                </h4>
                <div class="legend-item" onclick="filterByStatus('bersih')" title="Klik untuk filter">
                    <div class="legend-color" style="background: #3498db;"></div>
                    <span><strong>Bersih</strong></span>
                </div>
                <div class="legend-item" onclick="filterByStatus('ringan')" title="Klik untuk filter">
                    <div class="legend-color" style="background: #57ce39ff;"></div>
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
                    <div class="legend-color" style="background: #ecf0f1; border-color: #c4c4c4;"></div>
                    <span><strong>Belum Dimonitoring</strong></span>
                </div>
                
                <!-- Wilayah Filter -->
                <div style="margin-top: 8px; padding-top: 8px;">
                    <h4 onclick="filterByWilayah('all')" style="cursor:pointer; color: #FBA919; margin-bottom: 8px;">
                        <i class="fas fa-map-marked-alt"></i> Wilayah
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px;">
                        <div class="wilayah-item" onclick="toggleWilayah(16)" data-wilayah="16" title="Klik untuk filter Wilayah 16">
                            <span style="font-weight: 700; font-size: 14px;">16</span>
                        </div>
                        <div class="wilayah-item" onclick="toggleWilayah(17)" data-wilayah="17" title="Klik untuk filter Wilayah 17">
                            <span style="font-weight: 700; font-size: 14px;">17</span>
                        </div>
                        <div class="wilayah-item" onclick="toggleWilayah(18)" data-wilayah="18" title="Klik untuk filter Wilayah 18">
                            <span style="font-weight: 700; font-size: 14px;">18</span>
                        </div>
                        <div class="wilayah-item" onclick="toggleWilayah(19)" data-wilayah="19" title="Klik untuk filter Wilayah 19">
                            <span style="font-weight: 700; font-size: 14px;">19</span>
                        </div>
                        <div class="wilayah-item" onclick="toggleWilayah(20)" data-wilayah="20" title="Klik untuk filter Wilayah 20">
                            <span style="font-weight: 700; font-size: 14px;">20</span>
                        </div>
                        <div class="wilayah-item" onclick="toggleWilayah(21)" data-wilayah="21" title="Klik untuk filter Wilayah 21">
                            <span style="font-weight: 700; font-size: 14px;">21</span>
                        </div>
                        <div class="wilayah-item" onclick="toggleWilayah(22)" data-wilayah="22" title="Klik untuk filter Wilayah 22">
                            <span style="font-weight: 700; font-size: 14px;">22</span>
                        </div>
                        <div class="wilayah-item" onclick="toggleWilayah(23)" data-wilayah="23" title="Klik untuk filter Wilayah 23">
                            <span style="font-weight: 700; font-size: 14px;">23</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><br><br>

    <!-- Tabel Manajemen Publikasi Peta -->
    <div class="card full-width" id="publikasiTableSection">
        <h2><i class="fas fa-globe"></i> Tampilkan Tabel Peta Publikasi</h2>
        <p style="color: #666; font-size: 13px; margin-bottom: 20px;">
            📋 Kelola file CSV mana yang dipublikasi untuk setiap periode. Setiap periode hanya bisa memiliki 1 file yang dipublikasi.
        </p>
        
        @php
            // Get all import logs with their publication status
            $publications = \App\Models\MapPublication::where('status', 'published')
                ->with('importLog')
                ->get()
                ->groupBy(function($pub) {
                    return $pub->importLog->tahun . '-' . $pub->importLog->bulan . '-' . $pub->importLog->minggu;
                });
            
            $allImports = \App\Models\ImportLog::where('status', 'success')
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->orderBy('minggu', 'desc')
                ->get()
                ->groupBy(function($import) {
                    return $import->tahun . '-' . $import->bulan . '-' . $import->minggu;
                });
        @endphp
        
        @if($allImports->count() > 0)
            <div class="table-wrapper">
                <table id="publikasiTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;"><i class="fas fa-calendar"></i> Periode</th>
                            <th><i class="fas fa-file"></i> File Yang Dipublikasi</th>
                            <th style="width: 100px;"><i class="fas fa-database"></i> Records</th>
                            <th style="width: 150px;"><i class="fas fa-clock"></i> Upload</th>
                            <th style="width: 150px;"><i class="fas fa-check-circle"></i> Publikasi</th>
                            <th style="width: 100px;"><i class="fas fa-cog"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allImports as $periodKey => $imports)
                            @php
                                [$tahun, $bulan, $minggu] = explode('-', $periodKey);
                                $published = $publications->get($periodKey)?->first();
                                $monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                            @endphp
                            <tr>
                                <td style="font-weight: 600;">
                                    {{ $tahun }}<br>
                                    <small style="color: #666;">{{ $monthNames[$bulan] }} W{{ $minggu }}</small>
                                </td>
                                <td>
                                    @if($published)
                                        <div style="background: #e8f5e9; padding: 10px; border-radius: 6px; border-left: 4px solid #128241;">
                                            <strong style="color: #128241;">{{ $published->importLog->nama_file }}</strong>
                                            <br>
                                            <small style="color: #666;">ID #{{ $published->importLog->id }}</small>
                                        </div>
                                    @else
                                        <div style="background: #fff8e1; padding: 10px; border-radius: 6px; border-left: 4px solid #FBA919;">
                                            <strong style="color: #FBA919;">⚠️ Belum ada publikasi</strong>
                                            <br>
                                            <small style="color: #666;">Pilih salah satu file di bawah</small>
                                        </div>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    {{ $published?->importLog->jumlah_berhasil ?? '-' }}
                                </td>
                                <td style="font-size: 12px;">
                                    {{ $published?->importLog->created_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td style="font-size: 12px;">
                                    {{ $published?->published_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td style="text-align: center;">
                                    <button onclick="openPublicationModal('{{ $periodKey }}', '{{ $tahun }}', '{{ $bulan }}', '{{ $minggu }}')" 
                                        class="btn-small" style="padding: 6px 12px; font-size: 11px; background: #128241; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                        <i class="fas fa-pencil"></i> Kelola
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data yang berhasil di-upload</p>
            </div>
        @endif
    </div><br><br>

    <!-- Modal untuk Kelola Publikasi -->
    <div id="publicationModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 2000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
            <h3 id="modalTitle">Kelola Publikasi</h3>
            <p id="modalPeriod" style="color: #666; margin-bottom: 20px;"></p>
            
            <div style="margin-bottom: 20px; max-height: 300px; overflow-y: auto;">
                <label style="font-weight: 600; margin-bottom: 10px; display: block;">Pilih File untuk Dipublikasi:</label>
                <div id="filesList"></div>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="closePublicationModal()" style="padding: 10px 20px; background: #E74C3C; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    Batal
                </button>
                <button onclick="updatePublication()" style="padding: 10px 20px; background: #128241; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Uploads -->
    <div class="card full-width" id="riwayatUploadSection">
        <h2><i class="fas fa-history"></i> Riwayat Upload</h2>
        
        
        
        @if (isset($importTerbaru) && $importTerbaru->count() > 0)
            <div class="table-wrapper">
                <!-- Display Selector & Search Controls -->
                <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <select id="recordsPerPage" onchange="changeRecordsPerPage()" style="padding: 10px 14px; border: 2px solid #ecf0f1; border-radius: 8px; font-size: 13px; font-family: 'Poppins'; font-weight: 600; background: white; cursor: pointer; color: #128241; box-shadow: 0 2px 6px rgba(18, 130, 65, 0.1); transition: all 0.3s ease;">
                            <option value="10">10 baris</option>    
                            <option value="25">25 baris</option>
                            <option value="50">50 baris</option>
                            <option value="100">100 baris</option>
                            <option value="all">Semua</option>
                        </select>
                    </div>
                    
                    <div style="flex: 1; min-width: 250px; position: relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #FBA919; pointer-events: none;"></i>
                        <input type="text" id="tableSearchInput" placeholder="Cari berdasarkan ID (#5), Nama File, atau data lainnya..." 
                            style="width: 100%; padding: 12px 16px 12px 40px; border: 2px solid #ecf0f1; border-radius: 10px; font-size: 13px; font-family: 'Poppins'; font-weight: 600; background: white; color: #2c3e50; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03); transition: all 0.3s ease;">
                    </div>
                    
                    <!-- Search and Filters Form -->
        <form method="GET" action="{{ route('admin.dashboard') }}" id="filterForm">
            <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; align-items: center;">
                
                <!-- Filter Tahun -->
                <div style="min-width: 140px; position: relative;">
                    <label style="display: block; font-size: 10px; font-weight: 600; color: #666; margin-bottom: 4px; font-family: 'Poppins'; text-transform: uppercase;">
                        <i class="fas fa-calendar-alt" style="color: var(--accent-color);"></i> Tahun
                    </label>
                    <select name="tahun" id="filterTahun" 
                        style="width: 100%; padding: 10px 14px; border: 2px solid #ecf0f1; border-radius: 10px; font-size: 13px; font-family: 'Poppins'; font-weight: 600; background: white; cursor: pointer; color: #128241; box-shadow: 0 2px 6px rgba(18, 130, 65, 0.1); transition: all 0.3s ease;">
                        <option value="">Semua Tahun</option>
                        @php
                            $years = \App\Models\ImportLog::distinct()->pluck('tahun')->filter()->sort()->reverse();
                        @endphp
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Filter Bulan -->
                <div style="min-width: 140px; position: relative;">
                    <label style="display: block; font-size: 10px; font-weight: 600; color: #666; margin-bottom: 4px; font-family: 'Poppins'; text-transform: uppercase;">
                        <i class="fas fa-calendar" style="color: var(--accent-color);"></i> Bulan
                    </label>
                    <select name="bulan" id="filterBulan" 
                        style="width: 100%; padding: 10px 14px; border: 2px solid #ecf0f1; border-radius: 10px; font-size: 13px; font-family: 'Poppins'; font-weight: 600; background: white; cursor: pointer; color: #128241; box-shadow: 0 2px 6px rgba(18, 130, 65, 0.1); transition: all 0.3s ease;">
                        <option value="">Semua Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            @php
                                $monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            @endphp
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ $monthNames[$i] }}</option>
                        @endfor
                    </select>
                </div>
                
                <!-- Filter Minggu -->
                <div style="min-width: 140px; position: relative;">
                    <label style="display: block; font-size: 10px; font-weight: 600; color: #666; margin-bottom: 4px; font-family: 'Poppins'; text-transform: uppercase;">
                        <i class="fas fa-calendar-week" style="color: var(--accent-color);"></i> Minggu
                    </label>
                    <select name="minggu" id="filterMinggu" 
                        style="width: 100%; padding: 10px 14px; border: 2px solid #ecf0f1; border-radius: 10px; font-size: 13px; font-family: 'Poppins'; font-weight: 600; background: white; cursor: pointer; color: #128241; box-shadow: 0 2px 6px rgba(18, 130, 65, 0.1); transition: all 0.3s ease;">
                        <option value="">Semua Minggu</option>
                        @for($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}" {{ request('minggu') == $i ? 'selected' : '' }}>Minggu {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                
                <!-- Search Button -->
                <button type="submit" id="searchFilterBtn"
                    style="padding: 12px 20px; background: linear-gradient(135deg, #128241, #2ecc71); color: white; border: none; border-radius: 10px; font-size: 13px; font-weight: 600; font-family: 'Poppins'; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 6px rgba(18, 130, 65, 0.3); margin-top: 18px;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(18, 130, 65, 0.4)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(18, 130, 65, 0.3)'">
                    <i class="fas fa-search"></i> Cari
                </button>
                
                <!-- Reset Button -->
                <a href="{{ route('admin.dashboard') }}" 
                    style="padding: 12px 20px; background: linear-gradient(135deg, #E74C3C, #c0392b); color: white; border: none; border-radius: 10px; font-size: 13px; font-weight: 600; font-family: 'Poppins'; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 6px rgba(231, 76, 60, 0.3); margin-top: 18px; text-decoration: none; display: inline-block;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(231, 76, 60, 0.4)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(231, 76, 60, 0.3)'">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
                </div>

                <!-- Table -->
                <div style="overflow-x: auto;">
                    <table id="importTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-file"></i> Nama File</th>
                                <th><i class="fas fa-calendar"></i> Periode</th>
                                <th><i class="fas fa-database"></i> Data</th>
                                <th><i class="fas fa-info-circle"></i> Status</th>
                                <th><i class="fas fa-clock"></i> Waktu Upload</th>
                                <th><i class="fas fa-cog"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Info & Controls -->
                <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div style="font-size: 12px; color: #666; font-family: 'Poppins'; font-weight: 500;">
                        Menampilkan <span id="recordsStart">1</span> hingga <span id="recordsEnd">25</span> dari <span id="recordsTotal">0</span> data
                    </div>
                    <div id="paginationControls" style="display: flex; justify-content: center; align-items: center; gap: 8px; font-family: 'Poppins';">
                    </div>
                </div>

                <script>
                    const monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                    let allTableData = <?php echo json_encode($importTerbaru->toArray()); ?>;
                    let currentPage = 1;
                    let recordsPerPage = 10;
                    let filteredData = [...allTableData];

                    function changeRecordsPerPage() {
                        const value = document.getElementById('recordsPerPage').value;
                        recordsPerPage = value === 'all' ? filteredData.length : parseInt(value);
                        currentPage = 1;
                        renderTable();
                    }

                    function renderTable() {
                        const tbody = document.getElementById('tableBody');
                        tbody.innerHTML = '';

                        const startIdx = (currentPage - 1) * recordsPerPage;
                        const endIdx = startIdx + recordsPerPage;
                        const pageData = filteredData.slice(startIdx, endIdx);

                        pageData.forEach(log => {
                            const periodText = log.tahun && log.bulan && log.minggu 
                                ? `<span style="display: inline-block; padding: 6px 12px; background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: 12px; font-size: 11px; font-weight: 600; color: #1976d2; font-family: 'Poppins'; border: 1px solid #90caf9;"><i class="fas fa-calendar"></i> ${log.tahun} / ${monthNames[log.bulan]} - Minggu ${log.minggu}</span>`
                                : '<span style="color: #999;">-</span>';
                            
                            const statusBadge = log.status === 'success' 
                                ? '<span class="status-badge status-success"><i class="fas fa-check"></i> Berhasil</span>'
                                : log.status === 'pending'
                                ? '<span class="status-badge status-pending"><i class="fas fa-hourglass-half"></i> Pending</span>'
                                : '<span class="status-badge status-failed"><i class="fas fa-times"></i> Gagal</span>';

                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td><strong>#${log.id}</strong></td>
                                <td>${log.nama_file}</td>
                                <td>${periodText}</td>
                                <td>${log.jumlah_records}</td>
                                <td>${statusBadge}</td>
                                <td>${new Date(log.created_at).toLocaleDateString('id-ID', {year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</td>
                                <td>
                                    <button onclick="loadImportDataOnMap(${log.id}, '${log.wilayah_id}', ${log.tahun || null}, ${log.bulan || null}, ${log.minggu || null})" 
                                            style="padding: 8px 14px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 11px; font-weight: 600; font-family: 'Poppins'; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;"
                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(18, 130, 65, 0.3)'"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <i class="fas fa-map"></i> Lihat Peta
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });

                        updatePaginationInfo();
                        renderPaginationControls();
                    }

                    function updatePaginationInfo() {
                        const totalRecords = filteredData.length;
                        const startIdx = (currentPage - 1) * recordsPerPage + 1;
                        let endIdx = currentPage * recordsPerPage;
                        if (endIdx > totalRecords) endIdx = totalRecords;

                        document.getElementById('recordsStart').textContent = totalRecords === 0 ? 0 : startIdx;
                        document.getElementById('recordsEnd').textContent = endIdx;
                        document.getElementById('recordsTotal').textContent = totalRecords;
                    }

                    function renderPaginationControls() {
                        const paginationDiv = document.getElementById('paginationControls');
                        paginationDiv.innerHTML = '';

                        if (filteredData.length === 0) return;

                        const totalPages = Math.ceil(filteredData.length / recordsPerPage);

                        // Previous button
                        if (currentPage > 1) {
                            const prevBtn = document.createElement('button');
                            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
                            prevBtn.onclick = () => { currentPage--; renderTable(); };
                            prevBtn.style.cssText = 'padding: 10px 12px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;';
                            paginationDiv.appendChild(prevBtn);
                        }

                        // Page buttons
                        for (let i = 1; i <= totalPages; i++) {
                            const pageBtn = document.createElement('button');
                            pageBtn.textContent = i;
                            pageBtn.onclick = () => { currentPage = i; renderTable(); };
                            pageBtn.style.cssText = i === currentPage 
                                ? 'padding: 10px 12px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;'
                                : 'padding: 10px 12px; background: white; color: var(--primary-color); border: 2px solid #e3eae8; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;';
                            paginationDiv.appendChild(pageBtn);
                        }

                        // Next button
                        if (currentPage < totalPages) {
                            const nextBtn = document.createElement('button');
                            nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
                            nextBtn.onclick = () => { currentPage++; renderTable(); };
                            nextBtn.style.cssText = 'padding: 10px 12px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;';
                            paginationDiv.appendChild(nextBtn);
                        }
                    }

                    // COMMENTED OUT: Real-time search functionality
                    // Now search only works when clicking the "Cari" button via form submission
                    // document.getElementById('tableSearchInput').addEventListener('keyup', function() {
                    //     const searchTerm = this.value.toLowerCase();
                    //     filteredData = allTableData.filter(item => {
                    //         const searchStr = `${item.id} ${item.nama_file} ${item.tahun} ${monthNames[item.bulan]} ${item.minggu} ${item.jumlah_records} ${item.status} ${item.created_at}`.toLowerCase();
                    //         return searchStr.includes(searchTerm);
                    //     });
                    //     currentPage = 1;
                    //     renderTable();
                    // });

                    // Initial render
                    renderTable();
                    
                    // Function to apply client-side search filter
                    function applySearchFilter() {
                        const searchTerm = document.getElementById('tableSearchInput')?.value?.toLowerCase() || '';
                        
                        if (!searchTerm) {
                            // If no search term, use all data
                            filteredData = allTableData;
                        } else {
                            // Filter data based on search term
                            filteredData = allTableData.filter(item => {
                                const searchStr = `${item.id} ${item.nama_file} ${item.tahun} ${monthNames[item.bulan]} ${item.minggu} ${item.jumlah_records} ${item.status} ${item.created_at}`.toLowerCase();
                                return searchStr.includes(searchTerm);
                            });
                        }
                        
                        currentPage = 1;
                        renderTable();
                    }
                    
                    // Handle filter form submission - Apply search and scroll to riwayat section
                    const filterForm = document.getElementById('filterForm');
                    if (filterForm) {
                        filterForm.addEventListener('submit', function(e) {
                            // e.preventDefault(); // Don't prevent default - let form submit for server-side filtering
                            
                            // Apply client-side search BEFORE form submission
                            applySearchFilter();
                            
                            // Schedule scroll after form reload
                            setTimeout(() => {
                                const riwayatSection = document.getElementById('riwayatUploadSection');
                                if (riwayatSection) {
                                    riwayatSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                }
                            }, 100);
                        });
                    }
                </script>
            </div>
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
        'Ringan': '#57ce39ff',
        'Sedang': '#f1c40f',
        'Berat': '#e74c3c'
    };

    // Warna berdasarkan kategori dari CSV
    function getColorByKategori(kategori) {
        if (!kategori) return '#ffffff'; // Putih - Tidak ada data
        
        const kat = kategori.toLowerCase().trim();
        if (kat === 'bersih') return '#3498db'; // Biru
        if (kat === 'ringan') return '#57ce39ff'; // Hijau
        if (kat === 'sedang') return '#f1c40f'; // Kuning
        if (kat === 'berat') return '#e74c3c'; // Merah
        
        return '#ffffff'; // Putih - Tidak dikenali
    }

    let map;
    let geoJsonLayers = {};

    // Helper to show map error
    function showMapError(message) {
        const errorDiv = document.getElementById('mapErrorIndicator');
        const loadingDiv = document.getElementById('mapLoadingIndicator');
        if (loadingDiv) loadingDiv.style.display = 'none';
        if (errorDiv) {
            document.getElementById('mapErrorText').textContent = message;
            errorDiv.style.display = 'block';
        }
    }

    // Helper to hide error
    function hideMapError() {
        const errorDiv = document.getElementById('mapErrorIndicator');
        if (errorDiv) errorDiv.style.display = 'none';
    }

    // Initialize map
    function initMap() {
        console.log('🗺️  [DASHBOARD] Starting initMap...');
        const mapContainer = document.getElementById('map');
        
        if (!mapContainer) {
            console.error('❌ [DASHBOARD] Map container not found!');
            return;
        }
        
        console.log('✅ [DASHBOARD] Map container found, dimensions:', mapContainer.offsetWidth, 'x', mapContainer.offsetHeight);
        
        if (map) {
            console.log('🔄 [DASHBOARD] Removing existing map...');
            map.remove();
        }
        
        try {
            console.log('🆕 [DASHBOARD] Creating new map instance...');
            map = L.map('map').setView([-7.5, 107], 11);
            
            console.log('🗺️  [DASHBOARD] Adding tile layers...');
            // Define base layers
            var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            });

            var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles © Esri — Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
                maxZoom: 19
            });

            // Add default layer (OpenStreetMap)
            osmLayer.addTo(map);

            // Layer control
            var baseLayers = {
                "🗺️ Peta": osmLayer,
                "🛰️ Satelit": satelliteLayer
            };

            L.control.layers(baseLayers).addTo(map);
            
            console.log('✅ [DASHBOARD] Map initialized successfully with layer control!');
            
            // Show loading indicator
            const loadingDiv = document.getElementById('mapLoadingIndicator');
            if (loadingDiv) {
                loadingDiv.style.display = 'block';
            }
            
            // PENTING: LOAD DATA DARI PUBLISHED MAP TERLEBIH DAHULU
            // Jika ada published map, tampilkan itu. Jika tidak, tampilkan latest upload
            console.log('📥 [DASHBOARD] MEMUAT DATA DARI PUBLISHED MAP...');
            loadPublishedMapOrLatestUpload();
        } catch (error) {
            console.error('❌ [DASHBOARD] Error initializing map:', error);
        }
    }

    // Load published map or fallback to latest uploaded data
    async function loadPublishedMapOrLatestUpload() {
    console.log('🗺️  === [DASHBOARD] Loading latest published map ===');

    try {
        // ✅ SIMPLIFIED: Always get latest published
        const cacheBust = new Date().getTime();
        const pubResponse = await fetch(`/api/publication-status?_t=${cacheBust}`, {
            headers: {
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            }
        });
        if (pubResponse.ok) {
            const pubData = await pubResponse.json();
            if (pubData.is_published && pubData.import_log && pubData.import_log.id) {
                console.log('✅ [DASHBOARD] Found published map with import_id:', pubData.import_log.id);
                await loadDataForImport(
                    pubData.import_log.id,
                    pubData.import_log.tahun,
                    pubData.import_log.bulan,
                    pubData.import_log.minggu
                );
                return;
            }
        }
        
        // Fallback jika belum ada publikasi
        console.log('⚠️  [DASHBOARD] No published map found, showing empty map');
        const loadingDiv = document.getElementById('mapLoadingIndicator');
        if (loadingDiv) loadingDiv.style.display = 'none';
        
        showMapError('Belum ada data yang dipublikasikan. Silakan upload CSV dan klik "Perbarui Peta Publik".');
    } catch (error) {
        console.error('❌ [DASHBOARD] Error loading published map:', error);
        showMapError('Gagal memuat data: ' + error.message);
    }
}

// ✅ Upload Form Handler - Setelah upload, auto-reload map
document.getElementById('uploadForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const tahun = document.getElementById('tahun').value;
    const bulan = document.getElementById('bulan').value;
    const minggu = document.getElementById('minggu').value;
    const file = document.getElementById('csvFile').files[0];
    const messageDiv = document.getElementById('uploadMessage');

    if (!tahun || !bulan || !minggu) {
        messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Pilih tahun, bulan, dan minggu terlebih dahulu';
        messageDiv.className = 'message show error';
        setTimeout(() => messageDiv.className = 'message', 4000);
        return;
    }

    if (!file) {
        messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Pilih file CSV terlebih dahulu';
        messageDiv.className = 'message show error';
        setTimeout(() => messageDiv.className = 'message', 4000);
        return;
    }

    const formData = new FormData();
    formData.append('file', file);
    formData.append('tahun', tahun);
    formData.append('bulan', bulan);
    formData.append('minggu', minggu);
    formData.append('_token', document.querySelector('[name="_token"]').value);

    document.getElementById('uploadBtn').disabled = true;
    document.getElementById('uploadBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses file...';
    messageDiv.innerHTML = '<div class="loading-spinner"></div> Memproses file...';
    messageDiv.className = 'message show';

    try {
        const res = await fetch('{{ route("admin.upload-csv") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
            }
        });

        if (res.ok) {
            const data = await res.json();
            messageDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message}`;
            messageDiv.className = 'message show success';
            
            console.log('✅ Upload berhasil dan AUTO-PUBLISHED');
            console.log('📊 Import Log ID:', data.import_log_id);
            
            // Clear form
            document.getElementById('csvFile').value = '';
            document.getElementById('fileStatus').style.display = 'none';
            document.getElementById('uploadBtn').innerHTML = '<i class="fas fa-upload"></i> Upload File';
            document.getElementById('uploadBtn').disabled = true;
            
            // ✅ RELOAD MAP dengan data terbaru yang sudah auto-published
            console.log('🔄 Reloading map with latest published data...');
            
            // Clear existing layers
            Object.keys(geoJsonLayers).forEach(key => {
                if (geoJsonLayers[key]) {
                    map.removeLayer(geoJsonLayers[key]);
                }
            });
            geoJsonLayers = {};
            
            // Show loading
            const loadingDiv = document.getElementById('mapLoadingIndicator');
            if (loadingDiv) loadingDiv.style.display = 'block';
            
            // ✅ Load data yang baru di-publish (import_log_id dari response)
            await loadDataForImport(data.import_log_id, tahun, bulan, minggu);
            
            if (loadingDiv) loadingDiv.style.display = 'none';
            
            // Refresh table history
            fetchImportHistory();
            
            // Update statistics
            document.getElementById('statTotalData').textContent = data.berhasil;
            
            // Scroll to map
            setTimeout(() => {
                document.getElementById('map').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 500);
            
        } else {
            const errorData = await res.json();
            messageDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${errorData.message || 'Terjadi kesalahan'}`;
            messageDiv.className = 'message show error';
        }
    } catch (error) {
        messageDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> Error: ${error.message}`;
        messageDiv.className = 'message show error';
    } finally {
        document.getElementById('uploadBtn').disabled = false;
        document.getElementById('uploadBtn').innerHTML = '<i class="fas fa-upload"></i> Upload File';
    }
});

    // Load latest uploaded data on map (CSV terakhir diupload, BUKAN publikasi)
    async function loadLatestUploadedData() {
        console.log('🗺️  === [DASHBOARD] Loading latest uploaded data ===');

        try {
            // Get latest import log - coba endpoint yang berbeda
            const cacheBust = new Date().getTime();
            let response = await fetch(`/api/import-logs?_t=${cacheBust}`, {
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            });
            console.log('📡 [DASHBOARD] API response status:', response.status);
            
            if (!response.ok) {
                console.log(`⚠️  [DASHBOARD] API returned status ${response.status}, loading all wilayah`);
                loadAllWilayah();
                return;
            }

            // Check if response is actually JSON (not HTML error page)
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.warn('⚠️  [DASHBOARD] API returned non-JSON response:', contentType);
                console.log('🔄 [DASHBOARD] Fallback: loading all wilayah...');
                loadAllWilayah();
                return;
            }

            const data = await response.json();
            console.log('📊 [DASHBOARD] Latest import log response:', data);

            // Handle both array and paginated response
            let latestImport = null;
            if (Array.isArray(data)) {
                latestImport = data[0]; // First element if array
            } else if (data.data && Array.isArray(data.data)) {
                latestImport = data.data[0]; // First element of data property
            } else {
                console.warn('⚠️  [DASHBOARD] Unexpected response format:', typeof data);
                loadAllWilayah();
                return;
            }

            if (!latestImport || !latestImport.id) {
                console.log('⚠️  [DASHBOARD] No uploaded data found, loading all wilayah');
                loadAllWilayah();
                return;
            }

            // Load data from latest uploaded import
            console.log('✅ [DASHBOARD] Loading latest uploaded data with import_id:', latestImport.id);
            await loadDataFromImportForDashboard(latestImport);
        } catch (error) {
            console.error('❌ [DASHBOARD] Error loading latest uploaded data:', error);
            console.error('Error details:', error.message);
            showMapError('Gagal memuat data terakhir. Loading all wilayah instead...');
            // Fallback to load all wilayah
            console.log('🔄 [DASHBOARD] Fallback: loading all wilayah...');
            setTimeout(() => loadAllWilayah(), 1000);
        }
    }

    // Load data from specific import for dashboard
    async function loadDataFromImportForDashboard(importLog) {
        await loadDataForImport(importLog.id, importLog.tahun, importLog.bulan, importLog.minggu);
    }

    // Generic function to load data for any import ID
    async function loadDataForImport(importId, tahun, bulan, minggu) {
        const monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        console.log(`🚀 Loading data for import ${importId}: ${tahun}/${bulan}/w${minggu}`);
        
        try {
            const cacheBust = new Date().getTime();
            const response = await fetch(`/api/data-gulma/by-import/${importId}?_t=${cacheBust}`, {
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            });
            if (!response.ok) {
                throw new Error(`API returned status ${response.status}`);
            }
            const data = await response.json();
            
            // Handle array or object response
            const dataArray = Array.isArray(data) ? data : (data.data || []);
            console.log(`📊 Loaded ${dataArray.length} records from import ${importId}`);
            
            if (dataArray.length === 0) {
                console.log('⚠️  No data found in import');
                loadAllWilayah();
                return;
            }

            // Get unique wilayah from data
            const wilayahSet = new Set(dataArray.map(r => r.wilayah_id || r.wilayah));
            const wilayahNumbers = Array.from(wilayahSet);

            // Group data by wilayah
            const byWilayah = {};
            dataArray.forEach(record => {
                const wilayahId = record.wilayah_id || record.wilayah;
                if (!byWilayah[wilayahId]) byWilayah[wilayahId] = [];
                byWilayah[wilayahId].push(record);
            });

            // Clear existing layers
            Object.keys(geoJsonLayers).forEach(key => {
                if (geoJsonLayers[key]) {
                    map.removeLayer(geoJsonLayers[key]);
                }
            });
            geoJsonLayers = {};

            // Load each wilayah's geojson and merge with data
            const allBounds = [];
            let featuresAdded = 0;

            for (const wilayahNum of wilayahNumbers) {
                try {
                    const cacheBust = new Date().getTime();
                    const url = `/api/wilayah/geojson/${wilayahNum}?admin=1&import_id=${importId}&_t=${cacheBust}`;
                    console.log(`📥 Fetching wilayah ${wilayahNum} with import_id=${importId} - URL: ${url}`);
                    const geoResponse = await fetch(url, {
                        headers: {
                            'Cache-Control': 'no-cache, no-store, must-revalidate',
                            'Pragma': 'no-cache',
                            'Expires': '0'
                        }
                    });
                    console.log(`🗺️  Wilayah ${wilayahNum}: ${geoResponse.status} HTTP status, ${geoResponse.ok ? 'OK' : 'FAILED'}`);
                    if (!geoResponse.ok) continue;
                    const geojson = await geoResponse.json();
                    console.log(`🗺️  Wilayah ${wilayahNum}: ${geojson.features ? geojson.features.length : 0} features from import ${importId}`);
                    
                    // Merge data dengan geojson
                    const dataRecords = byWilayah[wilayahNum];
                    geojson.features.forEach(feature => {
                        const seksi = feature.properties.seksi || feature.properties.Seksi || feature.properties.SEKSI;
                        const matchingRecord = dataRecords.find(r => r.seksi === seksi);
                        if (matchingRecord) {
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

            // Update period display
            const display = document.getElementById('dataPeriodDisplay');
            const periodText = document.getElementById('periodText');
            if (tahun && bulan && minggu) {
                periodText.textContent = `Menampilkan Data - Tahun ${tahun}, ${monthNames[bulan]}, Minggu ke-${minggu} [#${importId}]`;
            } else {
                periodText.textContent = `Data [Import #${importId}]`;
            }
            display.style.display = 'block';
            
            console.log(`✓ Loaded ${featuresAdded} features from ${wilayahNumbers.length} wilayah`);
            
            // Hide loading indicator
            const loadingDiv = document.getElementById('mapLoadingIndicator');
            if (loadingDiv) loadingDiv.style.display = 'none';
            hideMapError();
        } catch (error) {
            console.error('Error loading data:', error);
            showMapError('Error: ' + error.message);
            const loadingDiv = document.getElementById('mapLoadingIndicator');
            if (loadingDiv) loadingDiv.style.display = 'none';
            loadAllWilayah();
        }
    }


    // Load all wilayah with data from database
    function loadAllWilayah() {
        console.log('🌍 === [DASHBOARD] Loading all wilayah with data ===');
        hideMapError();

        // Load semua wilayah 16-23 langsung
        const wilayahNumbers = [16, 17, 18, 19, 20, 21, 22, 23];
        console.log('✅ [DASHBOARD] Loading wilayah:', wilayahNumbers);
        
        // Load each wilayah with data merged
        const promises = wilayahNumbers.map(num => {
            console.log(`📡 Fetching wilayah ${num} with admin=1 header`);
            return fetch(`/api/wilayah/geojson/${num}?admin=1`, {
                headers: {
                    'X-Admin-Request': '1',
                    'Accept': 'application/json'
                }
            })
                .then(r => {
                    console.log(`📥 Response for wilayah ${num}: status ${r.status}`);
                    if (!r.ok) {
                        console.warn(`HTTP ${r.status} for wilayah ${num}`);
                        return { wilayah: num, data: { type: 'FeatureCollection', features: [] } };
                    }
                    return r.json().then(data => {
                        console.log(`✅ Wilayah ${num} received ${data.features?.length || 0} features`);
                        return { wilayah: num, data };
                    });
                })
                .catch(err => {
                    console.error(`Error loading wilayah ${num}:`, err);
                    return { wilayah: num, data: { type: 'FeatureCollection', features: [] } };
                });
        });

        Promise.all(promises)
            .then(results => {
                console.log(`Processing ${results.length} wilayah results...`);
                const allBounds = [];
                let featuresAdded = 0;
                let wilayahWithData = 0;

                results.forEach(result => {
                    if (!result || !result.data || !result.data.features) {
                        console.warn(`Wilayah ${result?.wilayah}: no features`);
                        return;
                    }
                    
                    const { wilayah, data } = result;
                    const featureCount = data.features.length;
                    
                    if (featureCount === 0) {
                        console.log(`Wilayah ${wilayah}: 0 features (empty GeoJSON)`);
                        return;
                    }

                    console.log(`🗺️ Adding wilayah ${wilayah} to map with ${featureCount} features`);
                    
                    // Count how many features have kategori data
                    let withKategori = 0;
                    data.features.forEach(f => {
                        if (f.properties && f.properties.kategori) withKategori++;
                    });
                    console.log(`  - ${withKategori} features have kategori data`);

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
                    featuresAdded += featureCount;
                    wilayahWithData++;
                    
                    const bounds = layer.getBounds();
                    if (bounds && bounds.isValid && bounds.isValid()) {
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
                    console.log(`✓ Map bounds adjusted`);
                }

                const layerCount = Object.keys(geoJsonLayers).length;
                console.log(`✅ [DASHBOARD] SUCCESS: Loaded ${layerCount} wilayah with ${featuresAdded} total features`);
                console.log(`🎉 [DASHBOARD] PETA GULMA BERHASIL DIMUAT!`);
                
                // Hide loading indicator and error
                hideMapError();
                const loadingDiv = document.getElementById('mapLoadingIndicator');
                if (loadingDiv) {
                    loadingDiv.style.display = 'none';
                }
                
                // If no data found
                if (featuresAdded === 0) {
                    console.warn('⚠️ Peta berhasil dimuat tapi tanpa data. Silakan upload CSV terlebih dahulu.');
                    showMapError('Peta siap, tapi belum ada data. Silakan upload CSV dari menu di atas.');
                }
            })
            .catch(error => {
                console.error('❌ [DASHBOARD] ERROR loading wilayah:', error);
                showMapError('Gagal memuat data: ' + error.message);
                const loadingDiv = document.getElementById('mapLoadingIndicator');
                if (loadingDiv) loadingDiv.style.display = 'none';
            });
    }

    // Get feature style based on data from CSV
    function getFeatureStyle(feature) {
        const props = feature.properties || {};
        let fillColor = '#ecf0f1'; // default light gray untuk tidak ada data
        let borderColor = '#bdc3c7';
        let opacity = 0.9;
        let fillOpacity = 0.6;
        
        // Log untuk debug
        if (props.kategori) {
            console.log(`Feature ${props.seksi || props.SEKSI || 'unknown'} - kategori="${props.kategori}"`);
        }
        
        // Prioritas: kategori > status_gulma > activitas
        if (props.kategori) {
            const kat = (props.kategori || '').toLowerCase().trim();
            fillColor = getColorByKategori(props.kategori);
            borderColor = fillColor;
            opacity = 1.0;
            fillOpacity = 0.7;
        } else if (props.status_gulma) {
            fillColor = statusColors[props.status_gulma] || fillColor;
            borderColor = fillColor;
        } else if (props.activitas) {
            const act = (props.activitas || '').toLowerCase();
            if (act.includes('pemupukan')) {
                fillColor = '#128241';
                borderColor = '#128241';
            } else if (act.includes('penyemprotan')) {
                fillColor = '#f1c40f';
                borderColor = '#f1c40f';
            } else if (act.includes('pembersihan')) {
                fillColor = '#3498db';
                borderColor = '#3498db';
            }
        }

        return {
            color: borderColor,
            weight: 2,
            opacity: opacity,
            fillColor: fillColor,
            fillOpacity: fillOpacity
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

    // Fetch latest import history without page reload
    function fetchImportHistory() {
        console.log('Fetching latest import history...');
        
        // Get filter values
        const tahun = document.getElementById('filterTahun')?.value;
        const bulan = document.getElementById('filterBulan')?.value;
        const minggu = document.getElementById('filterMinggu')?.value;
        
        // Build API URL with filters
        let apiUrl = '/api/import-logs';
        const params = new URLSearchParams();
        if (tahun) params.append('tahun', tahun);
        if (bulan) params.append('bulan', bulan);
        if (minggu) params.append('minggu', minggu);
        
        if (params.toString()) {
            apiUrl += '?' + params.toString();
        }
        
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                console.log('✓ Fetched import history from API:', data);
                
                // Update allTableData dengan response
                if (Array.isArray(data)) {
                    allTableData = data;
                } else if (data.data && Array.isArray(data.data)) {
                    allTableData = data.data;
                } else {
                    console.warn('Unexpected data format from API');
                    return;
                }
                
                console.log('✓ Updated import history with', allTableData.length, 'records');
                
                // Re-render table
                currentPage = 1;
                renderTable();
                
                // Update stats
                document.getElementById('statUploadTerbaru').textContent = allTableData.length;
            })
            .catch(error => {
                console.error('Error fetching import history:', error);
                // Don't reload page, just show warning in console
                console.warn('⚠️ Failed to update table automatically, you may need to refresh');
            });
    }

    // Form upload
    document.getElementById('uploadForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const tahun = document.getElementById('tahun').value;
        const bulan = document.getElementById('bulan').value;
        const minggu = document.getElementById('minggu').value;
        const file = document.getElementById('csvFile').files[0];
        const messageDiv = document.getElementById('uploadMessage');

        if (!tahun || !bulan || !minggu) {
            messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Pilih tahun, bulan, dan minggu terlebih dahulu';
            messageDiv.className = 'message show error';
            setTimeout(() => {
                messageDiv.className = 'message';
            }, 4000);
            return;
        }

        if (!file) {
            messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Pilih file CSV terlebih dahulu';
            messageDiv.className = 'message show error';
            setTimeout(() => {
                messageDiv.className = 'message';
            }, 4000);
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('tahun', tahun);
        formData.append('bulan', bulan);
        formData.append('minggu', minggu);
        formData.append('_token', document.querySelector('[name="_token"]').value);

        document.getElementById('uploadBtn').disabled = true;
        document.getElementById('uploadBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses file...';
        messageDiv.innerHTML = '<div class="loading-spinner"></div> Memproses file...';
        messageDiv.className = 'message show';

        try {
            const res = await fetch('{{ route("admin.upload-csv") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                }
            });

            if (res.ok) {
                const data = await res.json();
                const fileName = file.name;
                messageDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message}`;
                messageDiv.className = 'message show success';
                
                console.log('✓ Upload berhasil');
                console.log('📊 Import Log ID:', data.import_log_id);
                
                // Clear form
                document.getElementById('csvFile').value = '';
                document.getElementById('fileStatus').style.display = 'none';
                document.getElementById('uploadBtn').innerHTML = '<i class="fas fa-upload"></i> Upload File';
                document.getElementById('uploadBtn').disabled = true;
                document.getElementById('uploadBtn').style.opacity = '0.5';
                document.getElementById('uploadBtn').style.cursor = 'not-allowed';
                
                // PENTING: Update statistics LANGSUNG dari data upload (temporary)
                console.log('✓ Update statistics dari upload baru...');
                document.getElementById('statTotalData').textContent = data.berhasil;
                
                // PENTING: Load peta dengan data dari import_log_id baru
                console.log('✓ Load peta dengan data dari upload baru...');
                if (map && data.import_log_id) {
                    // Clear existing layers
                    Object.keys(geoJsonLayers).forEach(key => {
                        if (geoJsonLayers[key]) {
                            map.removeLayer(geoJsonLayers[key]);
                        }
                    });
                    geoJsonLayers = {};
                    
                    // Show loading indicator
                    const loadingDiv = document.getElementById('mapLoadingIndicator');
                    if (loadingDiv) {
                        loadingDiv.style.display = 'block';
                    }
                    
                    // Load data dari import_log_id baru (TEMP DATA)
                    console.log('✓ Loading temp data dari import_log_id:', data.import_log_id);
                    await loadImportDataOnMap(data.import_log_id, data.wilayah_id, tahun, bulan, minggu);
                    
                    // Hide loading indicator
                    if (loadingDiv) {
                        loadingDiv.style.display = 'none';
                    }
                } else {
                    console.error('❌ Map tidak ditemukan atau import_log_id tidak ada!');
                }
                
                // Refresh table history
                console.log('✓ Refresh import history table...');
                fetchImportHistory();
                
                // Scroll to map untuk lihat hasil
                setTimeout(() => {
                    document.getElementById('map').scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 500);
                
            } else {
                const errorData = await res.json();
                messageDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${errorData.message || 'Terjadi kesalahan'}`;
                messageDiv.className = 'message show error';
            }
        } catch (error) {
            messageDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> Error: ${error.message}`;
            messageDiv.className = 'message show error';
        } finally {
            document.getElementById('uploadBtn').disabled = false;
            document.getElementById('uploadBtn').innerHTML = '<i class="fas fa-upload"></i> Upload File';
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

    // Initialize - Delay a bit to ensure DOM and Leaflet are ready
    setTimeout(() => {
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded!');
            showMapError('Library Leaflet gagal dimuat. Cek koneksi internet.');
            return;
        }
        console.log('🗺️ DOM ready, Leaflet loaded, initializing map...');
        
        // Step 1: Initialize map
        initMap();
        
        // Step 2: Load publication status
        loadPublicationStatus();
        
        // Step 3: Map data loading langsung dari initMap()
        // loadLatestUploadedData() dipanggil dari dalam initMap() dan menampilkan CSV terbaru
        console.log('✅ Map initialization complete - data will load automatically from latest upload');
    }, 200);

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
            const cacheBust = new Date().getTime();
            const res = await fetch(`{{ route("admin.publication-status") }}?_t=${cacheBust}`, {
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            });
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
        console.log('📤 Publishing map to public...');

        const res = await fetch('{{ route("admin.publish-map") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
            },
            body: JSON.stringify({})
        });

        const data = await res.json();

        if (data.success) {
            console.log('✅ Map published successfully');
            
            // ✅ RELOAD MAP dengan data yang baru dipublish (dengan cache-busting)
            Object.keys(geoJsonLayers).forEach(key => {
                if (geoJsonLayers[key]) {
                    map.removeLayer(geoJsonLayers[key]);
                }
            });
            geoJsonLayers = {};
            
            // Load dengan import_id yang baru dipublish + timestamp untuk bust cache
            const timestamp = new Date().getTime();
            await loadDataForImport(data.import_id, data.tahun, data.bulan, data.minggu, timestamp);
            
            // Update status
            const statusDiv = document.getElementById('publishStatus');
            statusDiv.innerHTML = `<i class="fas fa-check-circle" style="color: #128241;"></i> ✅ ${data.message}`;
            
            btn.innerHTML = '<i class="fas fa-check"></i> Berhasil Dipublikasi!';
            btn.style.background = 'linear-gradient(135deg, #128241 0%, #2ecc71 100%)';
            
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.style.background = 'linear-gradient(135deg, var(--primary-color), var(--secondary-color))';
                btn.disabled = false;
                loadPublicationStatus();
            }, 3000);
        } else {
            alert('❌ ' + data.message);
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    } catch (error) {
        console.error('❌ Error:', error);
        alert('⚠️  Terjadi kesalahan: ' + error.message);
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}

// Initialize on page load
setTimeout(() => {
    if (typeof L === 'undefined') {
        console.error('Leaflet library not loaded!');
        return;
    }
    console.log('🗺️ Initializing map...');
    
    initMap();
    loadPublicationStatus();
    
    // ✅ Load latest published data
    console.log('📥 Loading latest published data...');
    loadPublishedMapOrLatestUpload();
}, 200);

    // Load all wilayah with specific import_id
    function loadAllWilayahWithImportId(importId) {
        console.log('🌍 === [DASHBOARD] Loading all wilayah with import_id:', importId);
        hideMapError();

        // Load semua wilayah 16-23 dengan import_id spesifik
        const wilayahNumbers = [16, 17, 18, 19, 20, 21, 22, 23];
        console.log('✅ [DASHBOARD] Loading wilayah:', wilayahNumbers);
        
        // Load each wilayah dengan import_id
        const promises = wilayahNumbers.map(num => {
            // Add cache-busting parameter to ensure fresh data
            const timestamp = new Date().getTime();
            const url = `/api/wilayah/geojson/${num}?admin=1&import_id=${importId}&_t=${timestamp}`;
            console.log(`📡 Fetching wilayah ${num} with import_id ${importId} (cache-bust: ${timestamp})`);
            return fetch(url, {
                headers: {
                    'X-Admin-Request': '1',
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            })
                .then(r => {
                    console.log(`📥 Response for wilayah ${num}: status ${r.status}`);
                    if (!r.ok) {
                        console.warn(`HTTP ${r.status} for wilayah ${num}`);
                        return { wilayah: num, data: { type: 'FeatureCollection', features: [] } };
                    }
                    return r.json().then(data => {
                        console.log(`✅ Wilayah ${num} received ${data.features?.length || 0} features`);
                        return { wilayah: num, data };
                    });
                })
                .catch(err => {
                    console.error(`Error loading wilayah ${num}:`, err);
                    return { wilayah: num, data: { type: 'FeatureCollection', features: [] } };
                });
        });

        Promise.all(promises)
            .then(results => {
                console.log(`Processing ${results.length} wilayah results...`);
                const allBounds = [];
                let featuresAdded = 0;
                let wilayahWithData = 0;

                results.forEach(result => {
                    if (!result || !result.data || !result.data.features) {
                        console.warn(`Wilayah ${result?.wilayah}: no features`);
                        return;
                    }
                    
                    const { wilayah, data } = result;
                    const featureCount = data.features.length;
                    
                    if (featureCount === 0) {
                        console.log(`Wilayah ${wilayah}: 0 features (empty GeoJSON)`);
                        return;
                    }

                    console.log(`🗺️ Adding wilayah ${wilayah} to map with ${featureCount} features`);
                    
                    // Count how many features have kategori data
                    let withKategori = 0;
                    data.features.forEach(f => {
                        if (f.properties && f.properties.kategori) withKategori++;
                    });
                    console.log(`  - ${withKategori} features have kategori data`);

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
                    featuresAdded += featureCount;
                    wilayahWithData++;

                    // Get bounds from layer
                    if (layer.getBounds) {
                        allBounds.push(layer.getBounds());
                    }
                });

                console.log(`✅ Total features added: ${featuresAdded} from ${wilayahWithData} wilayah`);
                
                // Fit bounds if we have data
                if (allBounds.length > 0) {
                    const allBoundsTogether = allBounds.reduce((acc, bounds) => {
                        return acc.extend(bounds);
                    });
                    map.fitBounds(allBoundsTogether, { padding: [50, 50] });
                    console.log('✅ Map fitted to bounds');
                }
            })
            .catch(error => {
                console.error('Error loading wilayah:', error);
                showMapError('Gagal memuat data wilayah: ' + error.message);
            });
    }

    // Filter by status - Consistent with wilayah.blade (reload with filter)
    let currentStatusFilter = '';
    let currentLoadedImportId = null;
    let currentLoadedPeriod = { tahun: null, bulan: null, minggu: null };
    
    function filterByStatus(status) {
        console.log('🎯 [FILTER] Filter by status:', status);
        currentStatusFilter = status;
        
        // Highlight active legend item
        document.querySelectorAll('.legend-item').forEach(item => {
            item.style.opacity = '0.5';
        });
        
        if (status) {
            const activeItem = document.querySelector(`.legend-item[onclick="filterByStatus('${status}')"]`);
            if (activeItem) {
                activeItem.style.opacity = '1';
                activeItem.style.transform = 'scale(1.05)';
            }
        } else {
            document.querySelectorAll('.legend-item').forEach(item => {
                item.style.opacity = '1';
                item.style.transform = 'scale(1)';
            });
        }
        
        // Reload map with filter
        if (currentLoadedImportId) {
            // Reload the same import data with filter
            const row = document.querySelector(`tr[data-id="${currentLoadedImportId}"]`);
            if (row) {
                const wilayahIds = row.getAttribute('data-wilayah');
                const tahun = row.getAttribute('data-tahun');
                const bulan = row.getAttribute('data-bulan');
                const minggu = row.getAttribute('data-minggu');
                loadImportDataOnMap(currentLoadedImportId, wilayahIds, tahun, bulan, minggu);
            } else {
                // Row not found, just filter current layers
                console.log('⚠️ [FILTER] Row not found for import', currentLoadedImportId, 'filtering current layers instead');
                filterCurrentMapLayers(status);
            }
        } else {
            // If no specific import loaded, filter the current map layers
            console.log('🗺️  [FILTER] Filtering current map layers...');
            filterCurrentMapLayers(status);
        }
        
        // Scroll to map
        document.getElementById('map').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // Filter current map layers without reloading
    function filterCurrentMapLayers(status) {
        console.log('🔍 [FILTER] Applying filter to current layers:', status);
        
        Object.keys(geoJsonLayers).forEach(wilayahKey => {
            const layer = geoJsonLayers[wilayahKey];
            if (!layer) return;
            
            layer.eachLayer(featureLayer => {
                const props = featureLayer.feature.properties;
                
                if (!status) {
                    // Show all
                    featureLayer.setStyle({ fillOpacity: 0.6, opacity: 0.9 });
                    return;
                }
                
                // Check if feature matches filter
                let matches = false;
                
                if (status === 'belum_dimonitoring') {
                    // Belum dimonitoring = tidak punya kategori dan status_gulma
                    matches = !props.kategori && !props.status_gulma;
                } else {
                    // Check multiple possible status field names (exact match, case-insensitive)
                    const kategori = (props.kategori || '').toLowerCase().trim();
                    const statusGulma = (props.status_gulma || '').toLowerCase().trim();
                    const activitas = (props.activitas || '').toLowerCase().trim();
                    const filterLower = status.toLowerCase();
                    
                    matches = kategori === filterLower || 
                             statusGulma === filterLower ||
                             activitas === filterLower;
                }
                
                if (matches) {
                    // Show matching features
                    featureLayer.setStyle({ fillOpacity: 0.8, opacity: 1 });
                } else {
                    // Hide non-matching features
                    featureLayer.setStyle({ fillOpacity: 0.1, opacity: 0.2 });
                }
            });
        });
        
        console.log('✅ [FILTER] Filter applied to', Object.keys(geoJsonLayers).length, 'wilayah layers');
    }

    // Load import data on map
    async function loadImportDataOnMap(importId, wilayahIds, tahun, bulan, minggu) {
        console.log(`🗺️ Loading import data: ID=${importId}, Wilayah=${wilayahIds}, Period=${tahun}/${bulan}/${minggu}`);
        
        // Show loading indicator
        const loadingDiv = document.getElementById('mapLoadingIndicator');
        if (loadingDiv) {
            loadingDiv.style.display = 'block';
        }
        hideMapError();
        
        // Store current state for filter reloads
        currentLoadedImportId = importId;
        currentLoadedPeriod = { tahun, bulan, minggu };
        
        // Reset legend highlight when loading new data (unless we're reloading for filter)
        if (!currentStatusFilter) {
            document.querySelectorAll('.legend-item').forEach(item => {
                item.style.opacity = '1';
                item.style.transform = 'scale(1)';
            });
        }
        
        // Update period display with import ID
        updatePeriodDisplay(tahun, bulan, minggu, importId);
        
        // Clear existing layers
        Object.keys(geoJsonLayers).forEach(key => {
            if (geoJsonLayers[key]) {
                map.removeLayer(geoJsonLayers[key]);
            }
        });
        geoJsonLayers = {};
        
        try {
            // Fetch data from import to get actual wilayah IDs
            console.log(`📥 Fetching data from import ${importId}...`);
            const cacheBust = new Date().getTime();
            const dataResponse = await fetch(`/api/data-gulma/by-import/${importId}?_t=${cacheBust}`, {
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            });
            
            if (!dataResponse.ok) {
                throw new Error(`Failed to fetch data from import: ${dataResponse.status}`);
            }
            
            const responseData = await dataResponse.json();
            console.log('📥 API Response full:', responseData);
            console.log('📥 Response type:', typeof responseData);
            console.log('📥 Is array:', Array.isArray(responseData));
            console.log('📥 Has data key:', responseData.data ? 'YES' : 'NO');
            
            // Handle both array response and object response with 'data' key
            let dataList = [];
            if (Array.isArray(responseData)) {
                console.log('✅ Response is array directly');
                dataList = responseData;
            } else if (responseData && responseData.data) {
                if (Array.isArray(responseData.data)) {
                    console.log('✅ Response has .data array');
                    dataList = responseData.data;
                } else {
                    console.warn('⚠️ Response.data exists but not an array:', typeof responseData.data);
                    dataList = [];
                }
            } else if (responseData && typeof responseData === 'object') {
                console.warn('⚠️ Response is object but no .data key. Keys:', Object.keys(responseData));
                dataList = [];
            }
            
            console.log(`📊 Got ${dataList.length} records from import`);
            
            if (dataList.length === 0) {
                showMapError('Data import kosong, tidak ada yang ditampilkan');
                if (loadingDiv) loadingDiv.style.display = 'none';
                return;
            }
            
            // Get unique wilayah IDs dari data
            const wilayahSet = new Set(dataList.map(r => r.wilayah_id || r.wilayah));
            const wilayahArray = Array.from(wilayahSet).sort();
            
            console.log('📦 Loading wilayah:', wilayahArray);
            
            // Group data by wilayah untuk merge nanti
            const dataByWilayah = {};
            dataList.forEach(record => {
                const wId = record.wilayah_id || record.wilayah;
                if (!dataByWilayah[wId]) {
                    dataByWilayah[wId] = [];
                }
                dataByWilayah[wId].push(record);
            });
            
            // Load each wilayah - Pass importId to API
            const promises = wilayahArray.map(wilayahNum => {
                const cacheBust = new Date().getTime();
                const url = `/api/wilayah/geojson/${wilayahNum}?admin=1&import_id=${importId}&_t=${cacheBust}`;
                console.log(`🌐 Fetching: ${url}`);
                
                return fetch(url, {
                    headers: {
                        'X-Admin-Request': '1',
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    }
                })
                    .then(r => {
                        if (!r.ok) {
                            throw new Error(`HTTP ${r.status} for wilayah ${wilayahNum}`);
                        }
                        return r.json();
                    })
                    .then(geojson => {
                        // Merge data dengan geojson
                        const records = dataByWilayah[wilayahNum] || [];
                        geojson.features.forEach(feature => {
                            const seksi = feature.properties.seksi || feature.properties.Seksi || feature.properties.SEKSI;
                            const matchingRecord = records.find(r => r.seksi === seksi);
                            if (matchingRecord) {
                                feature.properties = {
                                    ...feature.properties,
                                    ...matchingRecord,
                                    kategori: matchingRecord.kategori,
                                    status_gulma: matchingRecord.status_gulma
                                };
                            }
                        });
                        return { wilayah: wilayahNum, data: geojson };
                    })
                    .catch(err => {
                        console.error(`Error loading wilayah ${wilayahNum}:`, err);
                        return null;
                    });
            });
            
            const results = await Promise.all(promises);
            const allBounds = [];
            let featuresAdded = 0;
            let wilayahWithData = 0;
            
            results.forEach(result => {
                if (!result || !result.data || !result.data.features || result.data.features.length === 0) {
                    console.warn(`Wilayah ${result?.wilayah}: no features`);
                    return;
                }
                
                const { wilayah, data } = result;
                console.log(`📍 Wilayah ${wilayah}: ${data.features.length} features`);
                
                // Apply status filter if active
                let features = data.features;
                if (currentStatusFilter) {
                    console.log(`🔍 Filtering with status: "${currentStatusFilter}"`);
                    features = features.filter(feature => {
                        const props = feature.properties;
                        
                        if (currentStatusFilter === 'belum_dimonitoring') {
                            // Belum dimonitoring = tidak punya kategori dan status_gulma
                            return !props.kategori && !props.status_gulma;
                        }
                        
                        // For other statuses, check exact match (case-insensitive)
                        const kategori = (props.kategori || '').toLowerCase().trim();
                        const statusGulma = (props.status_gulma || '').toLowerCase().trim();
                        const activitas = (props.activitas || '').toLowerCase().trim();
                        const filterLower = currentStatusFilter.toLowerCase();
                        
                        // Debug log
                        const match = kategori === filterLower || 
                                     statusGulma === filterLower ||
                                     activitas === filterLower;
                        
                        if (match) {
                            console.log(`✅ Match: seksi=${props.seksi}, kategori="${kategori}", statusGulma="${statusGulma}", activitas="${activitas}"`);
                        }
                        
                        return match;
                    });
                    console.log(`📊 After filter: ${features.length} features`);
                }
                
                if (features.length === 0) {
                    console.warn(`Wilayah ${wilayah}: 0 features after filter`);
                    return;
                }
                
                const layer = L.geoJSON({ type: 'FeatureCollection', features }, {
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
                featuresAdded += features.length;
                wilayahWithData++;
                
                const bounds = layer.getBounds();
                if (bounds && bounds.isValid && bounds.isValid()) {
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
            
            // Hide loading indicator
            if (loadingDiv) {
                loadingDiv.style.display = 'none';
            }
            
            if (featuresAdded === 0) {
                console.warn(`⚠️ Tidak ada data untuk import ${importId}`);
                showMapError('Tidak ada data gulma untuk ditampilkan. Mungkin belum ada yang dimonitoring.');
            } else {
                console.log(`✅ Loaded import ${importId}: ${featuresAdded} features from ${wilayahWithData} wilayah`);
                hideMapError();
            }
            
            // Scroll to map
            document.getElementById('map').scrollIntoView({ behavior: 'smooth', block: 'center' });
        } catch (error) {
            console.error('❌ Error in loadImportDataOnMap:', error);
            console.error('Error details:', error.message);
            console.error('Stack:', error.stack);
            showMapError('Error: ' + error.message + '. Check browser console (F12) for details.');
            if (loadingDiv) {
                loadingDiv.style.display = 'none';
            }
        }
    }

    // Update period display
    function updatePeriodDisplay(tahun, bulan, minggu, importId) {
        const monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const display = document.getElementById('dataPeriodDisplay');
        const periodText = document.getElementById('periodText');
        
        if (tahun && bulan && minggu) {
            const idText = importId ? ` #${importId}` : '';
            periodText.textContent = `Menampilkan Data Terbaru - Tahun ${tahun}, ${monthNames[bulan]}, Minggu ke-${minggu}${idText}`;
            display.style.display = 'block';
        } else {
            periodText.textContent = 'Menampilkan Data Publikasi Terakhir';
            display.style.display = 'block';
        }
    }

    // Wilayah filter functionality
    let selectedWilayah = [];
    
    function toggleWilayah(wilayahNum) {
        const wilayahItem = document.querySelector(`.wilayah-item[data-wilayah="${wilayahNum}"]`);
        
        if (selectedWilayah.includes(wilayahNum)) {
            // Remove from selection
            selectedWilayah = selectedWilayah.filter(w => w !== wilayahNum);
            wilayahItem.classList.remove('active');
        } else {
            // Add to selection
            selectedWilayah.push(wilayahNum);
            wilayahItem.classList.add('active');
        }
        
        // Apply filter - only show/hide layers, don't reload
        filterByWilayah();
    }
    
    function filterByWilayah(reset = false) {
        if (reset === 'all') {
            // Reset all selections
            selectedWilayah = [];
            document.querySelectorAll('.wilayah-item').forEach(item => {
                item.classList.remove('active');
            });
        }
        
        Object.keys(geoJsonLayers).forEach(wilayahKey => {
            const layer = geoJsonLayers[wilayahKey];
            if (!layer) return;
            
            const wilayahNum = parseInt(wilayahKey);
            
            if (selectedWilayah.length === 0) {
                // Show all if nothing selected
                map.addLayer(layer);
                layer.eachLayer(featureLayer => {
                    featureLayer.setStyle({ fillOpacity: 0.6, opacity: 0.9 });
                });
            } else {
                // Show only selected wilayah
                if (selectedWilayah.includes(wilayahNum)) {
                    map.addLayer(layer);
                    layer.eachLayer(featureLayer => {
                        featureLayer.setStyle({ fillOpacity: 0.6, opacity: 0.9 });
                    });
                } else {
                    map.removeLayer(layer);
                }
            }
        });
        
        console.log('Filtered wilayah:', selectedWilayah.length === 0 ? 'All' : selectedWilayah.join(', '));
    }

    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded');
        
        // Show all data by default
        updatePeriodDisplay(null, null, null);
        
        // Check if filters were applied (URL has query parameters)
        const urlParams = new URLSearchParams(window.location.search);
        const hasTahun = urlParams.has('tahun') && urlParams.get('tahun') !== '';
        const hasBulan = urlParams.has('bulan') && urlParams.get('bulan') !== '';
        const hasMinggu = urlParams.has('minggu') && urlParams.get('minggu') !== '';
        
        if (hasTahun || hasBulan || hasMinggu) {
            // If filters were applied, scroll to riwayat section
            setTimeout(() => {
                const riwayatSection = document.getElementById('riwayatUploadSection');
                if (riwayatSection) {
                    console.log('📍 Scrolling to riwayat section after filter...');
                    riwayatSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        }
        
        // Setup table search - Only when user types and clicks search or presses Enter
        const searchInput = document.getElementById('tableSearchInput');
        if (searchInput) {
            // Remove keyup listener - now only searches when button clicked
            // Instead, add keypress to support Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    // Trigger form submission
                    document.getElementById('filterForm').submit();
                }
            });
        }
        
        // REMOVED: Real-time search on keyup
        // Search now only happens when "Cari" button is clicked via form submission
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

        // ===== PUBLICATION MANAGEMENT =====
        window.currentPeriod = null;
        window.selectedPublicationId = null;

        // Fetch all available files for a period
        async function fetchFilesForPeriod(tahun, bulan, minggu) {
            try {
                const response = await fetch(`/api/admin/files-by-period?tahun=${tahun}&bulan=${bulan}&minggu=${minggu}`);
                const data = await response.json();
                return data.files || [];
            } catch (error) {
                console.error('Error fetching files:', error);
                return [];
            }
        }

        window.openPublicationModal = async function(periodKey, tahun, bulan, minggu) {
            window.currentPeriod = { key: periodKey, tahun, bulan, minggu };
            
            const monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            document.getElementById('modalTitle').textContent = 'Kelola Publikasi Periode';
            document.getElementById('modalPeriod').textContent = `Tahun ${tahun} • ${monthNames[parseInt(bulan)]} • Minggu ${minggu}`;
            
            // Fetch files for this period
            const files = await fetchFilesForPeriod(tahun, bulan, minggu);
            
            let filesList = document.getElementById('filesList');
            filesList.innerHTML = '';
            
            if (files.length === 0) {
                filesList.innerHTML = '<p style="color: #E74C3C; text-align: center;">❌ Belum ada file yang berhasil untuk periode ini</p>';
                document.getElementById('publicationModal').style.display = 'flex';
                return;
            }
            
            files.forEach(file => {
                const isCurrentlyPublished = file.is_published;
                const fileOption = document.createElement('div');
                fileOption.style.cssText = `
                    padding: 12px;
                    margin-bottom: 8px;
                    border: 2px solid ${isCurrentlyPublished ? '#128241' : '#ddd'};
                    border-radius: 6px;
                    cursor: pointer;
                    background: ${isCurrentlyPublished ? '#e8f5e9' : '#fff'};
                    transition: all 0.2s;
                `;
                fileOption.className = 'file-option';
                
                fileOption.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="radio" name="publication_file" value="${file.id}" 
                            ${isCurrentlyPublished ? 'checked' : ''} 
                            style="width: 18px; height: 18px; cursor: pointer;">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: ${isCurrentlyPublished ? '#128241' : '#333'};">
                                ${file.name} 
                                ${isCurrentlyPublished ? '<span style="background: #128241; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: bold;">● AKTIF</span>' : ''}
                            </div>
                            <small style="color: #666;">
                                📊 ${file.records} records • 📅 ${file.uploaded_at}
                            </small>
                        </div>
                    </div>
                `;
                
                fileOption.addEventListener('click', function() {
                    document.querySelectorAll('.file-option').forEach(opt => {
                        opt.style.borderColor = '#ddd';
                        opt.style.background = '#fff';
                    });
                    fileOption.style.borderColor = '#128241';
                    fileOption.style.background = '#e8f5e9';
                    document.querySelector(`input[value="${file.id}"]`).checked = true;
                    window.selectedPublicationId = file.id;
                });
                
                if (isCurrentlyPublished) {
                    window.selectedPublicationId = file.id;
                }
                
                filesList.appendChild(fileOption);
            });
            
            document.getElementById('publicationModal').style.display = 'flex';
        };

        window.closePublicationModal = function() {
            document.getElementById('publicationModal').style.display = 'none';
            window.currentPeriod = null;
            window.selectedPublicationId = null;
        };

        window.updatePublication = async function() {
            if (!window.selectedPublicationId) {
                alert('❌ Silakan pilih file untuk dipublikasi');
                return;
            }
            
            if (!window.currentPeriod) {
                alert('❌ Periode tidak valid');
                return;
            }
            
            try {
                const response = await fetch('/api/admin/set-publication', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                    },
                    body: JSON.stringify({
                        import_log_id: window.selectedPublicationId,
                        tahun: window.currentPeriod.tahun,
                        bulan: window.currentPeriod.bulan,
                        minggu: window.currentPeriod.minggu
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    console.log('✅ Publikasi berhasil diperbarui');
                    console.log('   File:', result.nama_file);
                    console.log('   Period:', result.tahun + '/' + result.bulan + '/W' + result.minggu);
                    console.log('   Import ID:', result.import_id);
                    
                    alert('✅ Publikasi berhasil diperbarui untuk ' + result.nama_file);
                    window.closePublicationModal();
                    
                    // Reload map dengan data yang baru dipublikasi (tanpa reload halaman)
                    console.log('🔄 Reloading map with new publication data...');
                    
                    // Clear all existing layers
                    Object.keys(geoJsonLayers).forEach(key => {
                        if (geoJsonLayers[key]) {
                            map.removeLayer(geoJsonLayers[key]);
                        }
                    });
                    geoJsonLayers = {};
                    
                    // Load dengan import_id yang baru dipublikasi
                    loadAllWilayahWithImportId(result.import_id);
                    
                    // Update publikasi table jika terlihat
                    location.reload();
                } else {
                    alert('❌ Error: ' + (result.message || 'Gagal memperbarui publikasi'));
                }
            } catch (error) {
                console.error('Error updating publication:', error);
                alert('❌ Terjadi kesalahan: ' + error.message);
            }
        };

        // Close modal saat klik di luar modal
        document.getElementById('publicationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                window.closePublicationModal();
            }
        });
    });
</script>

@endsection