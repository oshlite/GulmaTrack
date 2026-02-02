<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | GulmaTrack</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=' . time()) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('image/logo3.png?v=' . time()) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/logo3.png?v=' . time()) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('image/logo3.png?v=' . time()) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins'
        }

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

        html, body {
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins';
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
            padding-top: 70px;
        }

        .admin-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .admin-content {
            flex: 1;
        }

        /* Navbar Styles - Using navbar.blade.php component */

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease;
        }

        /* ===== PAGE HEADER STYLING (Shared for all admin pages) ===== */
        :root {
            --title-color: #A6CE39;
        }

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 30px;
            font-family: 'Poppins';
        }

        /* Page Header */
        .page-header {
            position: relative;
            width: 100vw;
            margin: 0 calc(50% - 50vw) 40px;
            padding: 40px 20px 30px;
            border-bottom: none;
            animation: slideInDown 0.5s ease;
        }

        .page-header::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            border-bottom: 3px solid var(--primary-color);
        }

        .page-header h1 {
            font-size: 42px;
            color: var(--title-color);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-header h1 i {
            color: var(--title-color);
        }

        .page-header p {
            font-size: 16px;
            color: #666;
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

        /* ===== STATS GRID & CARDS (Shared styling) ===== */
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

        /* ===== FORM & TABLE SECTIONS (Shared styling) ===== */
        .form-section, .table-section {
            background: linear-gradient(135deg, #ffffff 0%, #f7faf8 100%);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(18, 130, 65, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(18, 130, 65, 0.1);
            border-left: 4px solid var(--primary-color);
            margin-bottom: 30px;
        }

        .form-section-title, .table-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section-title i, .table-title i {
            color: var(--accent-color);
            font-size: 22px;
        }

        /* ===== FORM STYLING ===== */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-family: 'Poppins';
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group label i {
            color: var(--accent-color);
            font-size: 13px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e3eae8;
            border-radius: 10px;
            font-family: 'Poppins';
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(18, 130, 65, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* ===== BUTTONS (Shared styling) ===== */
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-family: 'Poppins';
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 4px 12px rgba(18, 130, 65, 0.2);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(18, 130, 65, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        /* ===== TABLE STYLING ===== */
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

        /* ===== UPLOAD AREA ===== */
        .file-input-label {
            display: block;
            padding: 12px 15px;
            background-color: white;
            border: 2px dashed var(--primary-color);
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s;
            font-weight: 500;
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

        /* Responsive */
        @media (max-width: 1024px) {
            body {
                font-size: 14px;
            }

            .admin-container {
                padding: 0 15px 25px;
            }

           .page-header {
                padding: 30px 15px 20px;
                margin-bottom: 25px;
            }

            .page-header h1 {
                font-size: 32px;
            }

            .page-header p {
                font-size: 14px;
            }

            .navbar-container {
                padding: 0 12px;
            }

            .navbar-brand {
                font-size: 18px;
            }

            .navbar-brand img {
                width: 50px;
                height: 40px;
            }

            .navbar-brand span {
                font-size: 16px;
            }

            .nav-link {
                padding: 8px 12px;
                font-size: 11px;
            }

            .admin-info {
                padding: 6px 10px;
                font-size: 11px;
                gap: 8px;
            }

            .admin-avatar {
                width: 28px;
                height: 28px;
            }

            .logout-btn {
                padding: 6px 10px;
                font-size: 11px;
            }
        }

        @media (max-width: 768px) {
            .mobile-toggle-admin {
                display: block !important;
            }

            .admin-container {
                padding: 0 12px 20px;
            }

            .page-header {
                padding: 25px 12px 15px;
                margin-bottom: 20px;
            }

            .page-header h1 {
                font-size: 24px;
                margin-bottom: 8px;
                gap: 8px;
            }

            .page-header p {
                font-size: 13px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .navbar-container {
                padding: 0 10px;
            }

            .navbar-brand {
                font-size: 16px;
            }

            .navbar-brand span {
                display: none;
            }

            .navbar-brand img {
                width: 45px;
                height: 35px;
            }

            .nav-menu {
                display: none !important;
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                width: 100vw;
                max-width: 100%;
                flex-direction: column;
                gap: 0;
                margin-top: 0;
                background: #128241;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                z-index: 999;
            }

            .nav-menu.active {
                display: flex !important;
            }

            .nav-item {
                width: 100%;
            }

            .nav-link {
                text-align: left;
                border-radius: 0;
                padding: 12px 14px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                font-size: 12px;
            }

            .nav-link::after {
                display: none;
            }

            .nav-link.active {
                background: rgba(214, 223, 32, 0.1);
                border-left: 4px solid var(--secondary-color);
                padding-left: 10px;
            }

            .navbar-right {
                gap: 8px;
                position: absolute;
                right: 50px;
                top: 10px;
            }

            .admin-info {
                display: none;
            }

            .logout-btn {
                padding: 6px 10px;
                font-size: 11px;
                white-space: nowrap;
            }

            body {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .admin-container {
                padding: 0 10px 15px;
            }

            .page-header {
                padding: 20px 10px 12px;
                margin-bottom: 15px;
            }

            .page-header h1 {
                font-size: 20px;
                gap: 6px;
            }

            .page-header h1 i {
                font-size: 18px;
            }

            .page-header p {
                font-size: 12px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .form-section, .table-section {
                padding: 20px;
            }

            table {
                font-size: 12px;
            }

            table th, table td {
                padding: 10px 5px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 12px;
            }
        }
        @media (max-width: 480px) {
            .mobile-toggle-admin {
                font-size: 20px;
            }

            .navbar-container {
                padding: 0 8px;
            }

            .navbar-brand {
                font-size: 14px;
            }

            .navbar-brand img {
                width: 40px;
                height: 30px;
            }

            .nav-link {
                padding: 10px 12px;
                font-size: 11px;
            }

            .navbar-right {
                right: 45px;
                top: 12px;
            }

            .logout-btn {
                padding: 5px 8px;
                font-size: 10px;
            }

            body {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        {{-- Include Admin Navbar Component --}}
        @include('admin.navbar')

        <!-- Admin Content -->
        <div class="admin-content fade-in">
            @yield('content')
        </div>
    </div>

    <script>
        // Smooth scroll for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>