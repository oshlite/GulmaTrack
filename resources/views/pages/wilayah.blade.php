@extends('layouts.app')

@section('title', 'Wilayah')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<!-- Poppins Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="page-header">
    <h1><i class="fas fa-map-marked-alt"></i> Peta Wilayah Produksi</h1>
    <p>Jelajahi distribusi dan informasi geografis area produksi hortikultura</p>
</div>

<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
    <!-- Statistik -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-label">
                <i class="fas fa-map"></i> Total Wilayah
            </div>
            <div class="stat-number" id="totalWilayah">-</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">
                <i class="fas fa-ruler-combined"></i> Total Area
            </div>
            <div class="stat-number" id="totalArea">-</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">
                <i class="fas fa-th"></i> Total Plot
            </div>
            <div class="stat-number" id="totalPlot">-</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">
                <i class="fas fa-leaf"></i> Plot Bersih
            </div>
            <div class="stat-number" id="totalBersih">-</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">
                <i class="fas fa-flask"></i> Plot Berat Gulma
            </div>
            <div class="stat-number" id="totalBerat">-</div>
        </div>
    </div>

    <!-- Kontrol Peta -->
    <div class="wilayah-controls">
        <div class="controls-wrapper">
            <!-- Baris 1: Filter Fields -->
            <div class="controls-row">
                <div class="control-item compact">
                    <label class="control-label">
                        <i class="fas fa-calendar-alt"></i> Tahun
                    </label>
                    <input type="hidden" id="tahunSelect" value="">
                    <div class="button-grid-trigger" onclick="toggleButtonGrid('tahun')">
                        <span id="tahunSelected" class="grid-selected-text">Pilih Tahun...</span>
                        <i class="fas fa-chevron-down grid-arrow"></i>
                    </div>
                    <div id="tahunGrid" class="button-grid" style="display: none;">
                        <!-- Will be populated by JS -->
                    </div>
                </div>

                <div class="control-item compact">
                    <label class="control-label">
                        <i class="fas fa-calendar"></i> Bulan
                    </label>
                    <input type="hidden" id="bulanSelect" value="">
                    <div class="button-grid-trigger" onclick="toggleButtonGrid('bulan')">
                        <span id="bulanSelected" class="grid-selected-text">Pilih Bulan...</span>
                        <i class="fas fa-chevron-down grid-arrow"></i>
                    </div>
                    <div id="bulanGrid" class="button-grid" style="display: none;">
                        <button class="grid-btn" data-value="1" onclick="selectGridOption('bulan', '1', 'Januari')">Januari</button>
                        <button class="grid-btn" data-value="2" onclick="selectGridOption('bulan', '2', 'Februari')">Februari</button>
                        <button class="grid-btn" data-value="3" onclick="selectGridOption('bulan', '3', 'Maret')">Maret</button>
                        <button class="grid-btn" data-value="4" onclick="selectGridOption('bulan', '4', 'April')">April</button>
                        <button class="grid-btn" data-value="5" onclick="selectGridOption('bulan', '5', 'Mei')">Mei</button>
                        <button class="grid-btn" data-value="6" onclick="selectGridOption('bulan', '6', 'Juni')">Juni</button>
                        <button class="grid-btn" data-value="7" onclick="selectGridOption('bulan', '7', 'Juli')">Juli</button>
                        <button class="grid-btn" data-value="8" onclick="selectGridOption('bulan', '8', 'Agustus')">Agustus</button>
                        <button class="grid-btn" data-value="9" onclick="selectGridOption('bulan', '9', 'September')">September</button>
                        <button class="grid-btn" data-value="10" onclick="selectGridOption('bulan', '10', 'Oktober')">Oktober</button>
                        <button class="grid-btn" data-value="11" onclick="selectGridOption('bulan', '11', 'November')">November</button>
                        <button class="grid-btn" data-value="12" onclick="selectGridOption('bulan', '12', 'Desember')">Desember</button>
                    </div>
                </div>

                <div class="control-item compact">
                    <label class="control-label">
                        <i class="fas fa-calendar-week"></i> Minggu
                    </label>
                    <input type="hidden" id="mingguSelect" value="">
                    <div class="button-grid-trigger" onclick="toggleButtonGrid('minggu')">
                        <span id="mingguSelected" class="grid-selected-text">Pilih Minggu ke-...</span>
                        <i class="fas fa-chevron-down grid-arrow"></i>
                    </div>
                    <div id="mingguGrid" class="button-grid" style="display: none;">
                        <button class="grid-btn" data-value="1" onclick="selectGridOption('minggu', '1', 'Minggu ke-1')">Ke-1</button>
                        <button class="grid-btn" data-value="2" onclick="selectGridOption('minggu', '2', 'Minggu ke-2')">Ke-2</button>
                        <button class="grid-btn" data-value="3" onclick="selectGridOption('minggu', '3', 'Minggu ke-3')">Ke-3</button>
                        <button class="grid-btn" data-value="4" onclick="selectGridOption('minggu', '4', 'Minggu ke-4')">Ke-4</button>
                    </div>
                </div>

                <div class="control-item compact">
                    <label class="control-label">
                        <i class="fas fa-map-pin"></i> Pilih Wilayah
                    </label>
                    <input type="hidden" id="wilayahSelect" value="">
                    <div class="button-grid-trigger" onclick="toggleButtonGrid('wilayah')">
                        <span id="wilayahSelected" class="grid-selected-text">Pilih Wilayah...</span>
                        <i class="fas fa-chevron-down grid-arrow"></i>
                    </div>
                    <div id="wilayahButtonGrid" class="button-grid" style="display: none;">
                        <!-- Will be populated by JS -->
                    </div>
                </div>
            </div>

            <!-- Baris 2: Action Buttons -->
            <div class="controls-buttons-row">
                <button onclick="loadAllWilayah()" class="btn-secondary">
                    <i class="fas fa-globe"></i> Semua Wilayah
                </button>
                <button onclick="loadWilayahMap()" class="btn-primary">
                    <i class="fas fa-search"></i> Tampilkan Peta
                </button>
            </div>
        </div>
    </div>

        <!-- Status Info -->
    <div id="periodInfo" style="background: #ecf0f1; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid var(--primary-color);">
        <i class="fas fa-info-circle" style="color: var(--primary-color); margin-right: 8px;"></i>
        <span id="periodInfoText" style="font-size: 13px; color: #2c3e50;">
            <strong>Memuat data...</strong>
        </span>
    </div>

    <style>
        .wilayah-controls {
            background: linear-gradient(135deg, #ffffff 0%, #f7faf8 100%);
            padding: 32px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(18, 130, 65, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(18, 130, 65, 0.1);
            font-family: 'Poppins';
            position: relative;
            overflow: visible;
        }

        .wilayah-controls::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #128241 0%, #0d5c2e 50%, #128241 100%);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 0% 0%; }
            50% { background-position: 100% 0%; }
        }

        .controls-wrapper {
            max-width: 100%;
            font-family: 'Poppins';
            overflow: visible;
            position: relative;
            z-index: 1;
        }

        .controls-row {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: nowrap;
            margin-bottom: 16px;
            overflow: visible;
            position: relative;
        }

        .control-item {
            display: flex;
            flex-direction: column;
            font-family: 'Poppins';
            position: relative;
            flex: 1;
            min-width: 0;
            z-index: 100;
            overflow: visible;
        }

        .control-item.compact {
            flex: 1;
            min-width: 0;
        }

        .controls-buttons-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            z-index: 1;
        }

        .control-label {
            font-size: 11px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-family: 'Poppins';
            transition: color 0.3s ease;
            white-space: nowrap;
        }

        .control-item:hover .control-label {
            color: #128241;
        }

        .control-label i {
            font-size: 13px;
            color: #128241;
            width: 16px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .control-item:hover .control-label i {
            transform: scale(1.15);
        }

        /* Button Grid Trigger */
        .button-grid-trigger {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 14px;
            border: 2px solid #e3eae8;
            border-radius: 10px;
            background-color: white;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: 'Poppins';
            font-size: 13px;
            font-weight: 500;
            color: #2c3e50;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            user-select: none;
            white-space: nowrap;
        }

        .button-grid-trigger:hover {
            border-color: #128241;
            box-shadow: 0 6px 16px rgba(18, 130, 65, 0.12), 0 2px 6px rgba(0, 0, 0, 0.05);
            background-color: #fafdfb;
        }

        .button-grid-trigger.active {
            border-color: #128241;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
            background-color: #fafdfb;
        }

        .grid-selected-text {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .grid-arrow {
            font-size: 11px;
            color: #128241;
            transition: transform 0.3s ease;
            margin-left: 8px;
            flex-shrink: 0;
        }

        .button-grid-trigger.active .grid-arrow {
            transform: rotate(180deg);
        }

        /* Button Grid */
        .button-grid {
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #128241;
            border-radius: 10px;
            padding: 14px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            z-index: 999999;
            box-shadow: 0 10px 30px rgba(18, 130, 65, 0.2);
            animation: slideDown 0.3s ease;
            min-width: max-content;
            max-height: 400px;
            overflow-y: auto;
        }

        /* Khusus untuk Bulan - 6 kolom */
        #bulanGrid.button-grid {
            grid-template-columns: repeat(6, 1fr);
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

        .grid-btn {
            padding: 13px 12px;
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
            white-space: normal;
            min-width: 80px;
            line-height: 1.3;
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
            color: white;
            border-color: #128241;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(18, 130, 65, 0.3);
        }

        .grid-btn.selected:hover {
            background: linear-gradient(135deg, #0d5c2e 0%, #0a4a26 100%);
            transform: translateY(-2px);
        }

        .control-select {
            display: none;
        }

        .btn-primary,
        .btn-secondary {
            flex: 1;
            padding: 13px 24px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.6px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
            font-family: 'Poppins';
            position: relative;
            overflow: hidden;
            white-space: nowrap;
        }

        .btn-primary::before,
        .btn-secondary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::before,
        .btn-secondary:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            box-shadow: 0 4px 14px rgba(18, 130, 65, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(18, 130, 65, 0.35);
            background: linear-gradient(135deg, #0d5c2e 0%, #0a4a26 100%);
        }

        .btn-primary:active {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(18, 130, 65, 0.3);
        }

        .btn-primary i {
            font-size: 14px;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #ffffff 0%, #f5f7f6 100%);
            color: #2c3e50;
            border: 2px solid #d8e1dd;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #f5f9f7 0%, #edf4f0 100%);
            border-color: #128241;
            color: #128241;
            box-shadow: 0 10px 25px rgba(18, 130, 65, 0.18);
        }

        .btn-secondary:active {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(18, 130, 65, 0.12);
        }

        .btn-secondary i {
            font-size: 14px;
        }

        @media (max-width: 1024px) {
            .controls-row {
                flex-wrap: wrap;
            }

            .control-item.compact {
                flex: 1 1 calc(50% - 6px);
                min-width: 0;
            }

            .wilayah-controls {
                padding: 24px;
            }

            .button-grid {
                grid-template-columns: repeat(4, 1fr);
                left: -20px;
                right: -20px;
                min-width: 400px;
            }

            #bulanGrid.button-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        @media (max-width: 768px) {
            .wilayah-controls {
                padding: 20px;
            }

            .controls-row {
                flex-direction: column;
                gap: 16px;
            }

            .control-item.compact {
                flex: 1 1 100%;
            }

            .controls-buttons-row {
                flex-direction: column;
            }

            .control-label {
                font-size: 10px;
            }

            .button-grid-trigger {
                padding: 10px 12px;
                font-size: 12px;
            }

            .button-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                padding: 12px;
                left: -10px;
                right: -10px;
                min-width: 320px;
            }

            #bulanGrid.button-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .grid-btn {
                padding: 11px 8px;
                font-size: 12px;
            }
        }
    </style>

    <style>
        #mapContainer {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: visible;
            box-shadow: var(--shadow);
            position: relative;
            display: block;
            margin-bottom: 30px;
        }

        #map {
            width: 100% !important;
            height: 600px !important;
            border-radius: 8px;
            display: block;
            position: relative;
            z-index: 1;
        }

        .leaflet-popup-content {
            margin: 0 !important;
            padding: 0 !important;
        }

        .leaflet-popup-content h4 {
            margin: 0 0 10px 0;
            color: var(--primary-color);
            font-size: 14px;
            font-weight: 600;
        }

        .leaflet-popup-content .popup-section {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .leaflet-popup-content .popup-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .popup-label {
            font-weight: 600;
            color: #555;
            font-size: 12px;
        }

        .popup-value {
            color: var(--primary-color);
            font-size: 13px;
            margin-left: 5px;
        }

        .map-legend {
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: absolute;
            bottom: 20px;
            right: 5px;
            z-index: 10;
            min-width: 100px;
        }

        .map-legend h4 {
            margin: 0 0 12px 0;
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 13px;
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
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .map-container {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: visible;
            box-shadow: var(--shadow);
            position: relative;
            margin-bottom: 30px;
            z-index: 1;
        }

        /* Ensure Leaflet controls don't overlap navbar */
        .leaflet-control-zoom,
        .leaflet-control-attribution {
            z-index: 10 !important;
        }

        .wilayah-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 40px;
        }

        @media (max-width: 1400px) {
            .wilayah-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 992px) {
            .wilayah-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 576px) {
            .wilayah-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .wilayah-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border: 1px solid #e0e0e0;
            position: relative;
        }

        .wilayah-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .wilayah-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 12px 24px rgba(18, 130, 65, 0.15);
            border-color: var(--primary-color);
        }

        .wilayah-card-header {
            background: linear-gradient(135deg, #128241, #128241);
            color: white;
            padding: 12px 10px;
            text-align: center;
            position: relative;
        }

        .wilayah-card-header h3 {
            font-size: 14px;
            margin: 0;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .wilayah-card-body {
            padding: 12px 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            padding: 6px 10px;
            background: #f8f9fa;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .info-row:hover {
            background: #e8f5e9;
            transform: translateX(5px);
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 11px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .info-label i {
            color: var(--primary-color);
            font-size: 12px;
        }

        .info-value {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 11px;
            background: white;
            padding: 3px 8px;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <!-- Map Container -->
    <div class="map-container">
        <div id="map"></div>
        <div class="map-legend">
            <h4><i class="fas fa-info-circle"></i> Status Gulma</h4>
            <div class="legend-item">
                <div class="legend-color" style="background: #3498db;"></div>
                <span><strong>Bersih</strong></span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #128241;"></div>
                <span><strong>Ringan</strong></span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #f1c40f;"></div>
                <span><strong>Sedang</strong></span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #e74c3c;"></div>
                <span><strong>Berat</strong></span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #ecf0f1; border-color: #8b8b8b;"></div>
                <span><strong>Tidak ada Data</strong></span>
            </div>
        </div>
    </div>

    <!-- Daftar Wilayah -->
    <div style="margin: 30px 0 20px;">
        <h2 style="font-size: 24px; font-weight: 700; color: #2c3e50; margin-bottom: 8px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-map-marked-alt" style="color: var(--primary-color);"></i>
            Daftar Wilayah Produksi
        </h2>
        <p style="color: #7f8c8d; font-size: 13px; margin: 0;">Klik kartu wilayah untuk melihat detail peta</p>
    </div>
    
    <!-- Kontrol Wilayah -->
    <div class="wilayah-controls" style="margin-bottom: 25px;">
        <div style="flex: 1; position: relative;">
            <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999;"></i>
            <input type="text" id="searchWilayah" placeholder="Cari wilayah..." style="padding-left: 45px;">
        </div>
        <select id="filterKomoditas" style="min-width: 180px;">
            <option value="">🔍 Semua Kategori</option>
            <option value="ringan">🟢 Ringan</option>
            <option value="sedang">🟡 Sedang</option>
            <option value="berat">🔴 Berat</option>
            <option value="bersih">🔵 Bersih</option>
        </select>
        <button onclick="filterWilayah()" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); box-shadow: 0 4px 8px rgba(18, 130, 65, 0.2);">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>
    
    <div class="wilayah-grid" id="wilayahGrid">
        <div style="text-align: center; width: 100%; padding: 40px;">
            <div class="loading" style="margin: 0 auto;"></div>
            <p style="margin-top: 20px; color: #999;">Memuat data wilayah...</p>
        </div>
    </div>
</div>


<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // ==================
    // ==================
    // DEPRECATED - OLD CUSTOM SELECT (Now using Button Grid)
    // ==================
    function initCustomSelect(selectId, placeholder = 'Pilih...', emoji = '📅') {
        // Deprecated - no longer needed with button grid system
        console.log('initCustomSelect is deprecated - using button grid system');
    }

    // ===================
    // BUTTON GRID FUNCTIONS
    // ===================
    function toggleButtonGrid(type) {
        let gridId = type + 'Grid';
        // Special handling for wilayah button grid
        if (type === 'wilayah') {
            gridId = 'wilayahButtonGrid';
        }
        
        const grid = document.getElementById(gridId);
        const trigger = grid.previousElementSibling;
        
        // Toggle current grid
        if (grid.style.display === 'none' || grid.style.display === '') {
            // Close all other grids
            document.querySelectorAll('.button-grid').forEach(g => {
                g.style.display = 'none';
            });
            document.querySelectorAll('.button-grid-trigger').forEach(t => {
                t.classList.remove('active');
            });
            
            // Open this grid
            grid.style.display = 'grid';
            trigger.classList.add('active');
        } else {
            // Close this grid
            grid.style.display = 'none';
            trigger.classList.remove('active');
        }
    }

    function selectGridOption(type, value, label) {
        // Update hidden input
        document.getElementById(type + 'Select').value = value;
        
        // Update display text
        document.getElementById(type + 'Selected').textContent = label;
        
        // Update selected button
        // Special handling for wilayah button grid
        let gridId = type + 'Grid';
        if (type === 'wilayah') {
            gridId = 'wilayahButtonGrid';
        }
        const grid = document.getElementById(gridId);
        
        grid.querySelectorAll('.grid-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        event.target.classList.add('selected');
        
        // Close grid
        grid.style.display = 'none';
        grid.previousElementSibling.classList.remove('active');
    }

    // Close grids when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.control-item')) {
            document.querySelectorAll('.button-grid').forEach(grid => {
                grid.style.display = 'none';
            });
            document.querySelectorAll('.button-grid-trigger').forEach(trigger => {
                trigger.classList.remove('active');
            });
        }
    });

    function toggleDropdown(element) {
        // This function is deprecated, keeping for compatibility
        console.log('toggleDropdown called - using new button grid system');
    }

    // ===================
    // MAP INITIALIZATION
    // ===================
    let map;
    let geoJsonLayers = {};
    let allWilayahData = [];
    let wilayahData = [];
    let currentPeriod = null; // Store current selected period
    let latestPeriod = null; // Store latest available period

    // Initialize map
    function initMap() {
        // Check if map already exists
        if (map) {
            map.remove();
        }

        // Create map centered on Lampung Tengah
        map = L.map('map', {
            center: [-4.85, 105.0],
            zoom: 11,
            zoomControl: true,
            attributionControl: true
        });

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        console.log('Map initialized successfully');
    }

    // Load single wilayah
    async function loadWilayahMap() {
        // Check period first
        const periodOk = await checkPeriodAndLoadData();
        if (!periodOk) return;
        
        const wilayahNumber = document.getElementById('wilayahSelect').value;
        
        if (!wilayahNumber) {
            alert('Silakan pilih wilayah terlebih dahulu');
            return;
        }

        // Initialize map if not exists
        if (!map) {
            initMap();
        }

        // Clear existing layers
        clearAllLayers();

        // Show loading
        console.log(`Loading Wilayah ${wilayahNumber}...`);

        fetch(`/api/wilayah/geojson/${wilayahNumber}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    // Check if error is about unpublished data
                    if (data.error.includes('belum dipublikasikan')) {
                        alert('⚠️ Peta belum tersedia.\n\nData peta belum dipublikasikan oleh administrator. Silakan hubungi admin untuk memperbarui peta.');
                        return;
                    }
                    throw new Error(data.error);
                }

                if (!data.features || data.features.length === 0) {
                    alert('Tidak ada data untuk wilayah ini');
                    return;
                }

                // Add GeoJSON layer with styling
                const layer = L.geoJSON(data, {
                    style: function(feature) {
                        return getFeatureStyle(feature);
                    },
                    onEachFeature: function(feature, layer) {
                        if (feature.properties) {
                            layer.bindPopup(createPopupContent(feature.properties), {
                                maxWidth: 350,
                                minWidth: 320,
                                maxHeight: 600,
                                autoPan: true
                            });
                        }
                    }
                }).addTo(map);

                geoJsonLayers[wilayahNumber] = layer;

                // Fit map to bounds
                const bounds = layer.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }

                console.log(`Wilayah ${wilayahNumber} loaded: ${data.features.length} features`);
            })
            .catch(error => {
                console.error('Error loading wilayah:', error);
                alert('Gagal memuat data wilayah: ' + error.message);
            });
    }

    // Load all wilayah
    async function loadAllWilayah() {
        // Check period first
        const periodOk = await checkPeriodAndLoadData();
        if (!periodOk) return;
        
        // Initialize map if not exists
        if (!map) {
            initMap();
        }

        // Clear existing layers
        clearAllLayers();

        console.log('Loading all wilayah...');

        // Get list of all wilayah from API
        fetch('/api/wilayah/data')
            .then(response => response.json())
            .then(summary => {
                const wilayahNumbers = summary.data.map(w => w.wilayah);
                
                // Load each wilayah
                const promises = wilayahNumbers.map(num => 
                    fetch(`/api/wilayah/geojson/${num}`)
                        .then(r => r.json())
                        .then(data => ({ wilayah: num, data }))
                );

                return Promise.all(promises);
            })
            .then(results => {
                const allBounds = [];

                results.forEach(({ wilayah, data }) => {
                    if (data.features && data.features.length > 0) {
                        const layer = L.geoJSON(data, {
                            style: function(feature) {
                                return getFeatureStyle(feature);
                            },
                            onEachFeature: function(feature, layer) {
                                if (feature.properties) {
                                    layer.bindPopup(createPopupContent(feature.properties), {
                                        maxWidth: 350,
                                        minWidth: 320,
                                        maxHeight: 600,
                                        autoPan: true
                                    });
                                }
                            }
                        }).addTo(map);

                        geoJsonLayers[wilayah] = layer;
                        
                        const bounds = layer.getBounds();
                        if (bounds.isValid()) {
                            allBounds.push(bounds);
                        }
                    }
                });

                // Fit map to show all wilayah
                if (allBounds.length > 0) {
                    const combinedBounds = allBounds.reduce((acc, bounds) => {
                        return acc.extend(bounds);
                    }, L.latLngBounds(allBounds[0]));
                    
                    map.fitBounds(combinedBounds, { padding: [50, 50] });
                }

                console.log(`Loaded ${results.length} wilayah successfully`);
            })
            .catch(error => {
                console.error('Error loading all wilayah:', error);
                alert('Gagal memuat data wilayah: ' + error.message);
            });
    }

    // Clear all layers from map
    function clearAllLayers() {
        Object.values(geoJsonLayers).forEach(layer => {
            if (map.hasLayer(layer)) {
                map.removeLayer(layer);
            }
        });
        geoJsonLayers = {};
    }

    // Get feature style based on properties
    function getFeatureStyle(feature) {
        const props = feature.properties || {};
        let fillColor = '#ecf0f1'; // default putih/abu untuk tidak ada data
        let borderColor = '#8b8b8b'; // default hitam untuk tidak ada data
        let weight = 2;
        
        // Check multiple status fields: Kelas_weed, gulma_KATEGORI, atau Status
        const status = (props.Kelas_weed || props.gulma_KATEGORI || props.Status || '').toLowerCase();
        
        if (status) {
            if (status.includes('bersih')) {
                fillColor = '#3498db'; // Biru
                borderColor = '#3498db';
            } else if (status.includes('ringan')) {
                fillColor = '#128241'; // Hijau
                borderColor = '#128241';
            } else if (status.includes('sedang')) {
                fillColor = '#f1c40f'; // Kuning
                borderColor = '#f1c40f';
            } else if (status.includes('berat')) {
                fillColor = '#e74c3c'; // Merah
                borderColor = '#e74c3c';
            }
        }

        return {
            color: borderColor,
            weight: weight,
            opacity: 0.9,
            fillColor: fillColor,
            fillOpacity: 0.6
        };
    }

    // Create popup content
    function createPopupContent(props) {
        let html = '<div style="width: 320px; padding: 0;">';
        
        // Foto
        html += '<div style="width: 100%; height: 200px; border-radius: 8px 8px 0 0; overflow: hidden; margin-bottom: 10px;">';
        html += '<img src="/image/roblox.png" alt="Foto Lokasi" style="width: 100%; height: 100%; object-fit: cover;">';
        html += '</div>';
        
        html += '<div style="padding: 10px;">';
        
        // Header dengan Lokasi
        if (props.Lokasi || props.LOKASI) {
            html += `<h3 style="margin: 0 0 10px 0; color: #128241; font-size: 16px;">📍 ${props.Lokasi || props.LOKASI}</h3>`;
        }

        // Status Gulma dengan warna
        const statusGulma = props.Kelas_weed || props.gulma_KATEGORI || props.Status || 'Tidak Ada Data';
        let statusColor = '#ecf0f1';
        let textColor = '#333333';
        
        if (statusGulma.toLowerCase().includes('bersih')) {
            statusColor = '#3498db';
            textColor = 'white';
        } else if (statusGulma.toLowerCase().includes('ringan')) {
            statusColor = '#128241';
            textColor = 'white';
        } else if (statusGulma.toLowerCase().includes('sedang')) {
            statusColor = '#f1c40f';
            textColor = '#333333';
        } else if (statusGulma.toLowerCase().includes('berat')) {
            statusColor = '#e74c3c';
            textColor = 'white';
        }
        
        html += `<div style="background: ${statusColor}; color: ${textColor}; padding: 8px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-weight: bold;">
            ${statusGulma}
        </div>`;

        // Area information
        html += '<table style="width: 100%; font-size: 13px; margin-bottom: 10px;">';
        
        // Wilayah
        if (props.Wilayah || props.gulma_Wilayah) {
            html += `<tr><td style="padding: 5px 0;"><strong>Wilayah:</strong></td><td style="padding: 5px 0; text-align: right;">${props.Wilayah || props.gulma_Wilayah}</td></tr>`;
        }
        
        // Luas Bruto
        const bruto = props.Luas_Bruto || props.Bruto || props.bruto;
        if (bruto) {
            const brutoValue = typeof bruto === 'string' ? bruto.replace(',', '.') : bruto;
            html += `<tr><td style="padding: 5px 0;"><strong>Luas Bruto:</strong></td><td style="padding: 5px 0; text-align: right;">${brutoValue} Ha</td></tr>`;
        }

        // Luas Netto
        const netto = props.Luas_Netto || props.Netto || props.netto || props.gulma_Neto;
        if (netto) {
            const nettoValue = typeof netto === 'string' ? netto.replace(',', '.') : netto;
            html += `<tr><td style="padding: 5px 0;"><strong>Luas Netto:</strong></td><td style="padding: 5px 0; text-align: right;">${nettoValue} Ha</td></tr>`;
        }

        // TK/HA
        if (props.tk_ha || props['gulma_TK/HA']) {
            html += `<tr><td style="padding: 5px 0;"><strong>TK/Ha:</strong></td><td style="padding: 5px 0; text-align: right;">${props.tk_ha || props['gulma_TK/HA']}</td></tr>`;
        }

        html += '</table>';
        
        // Status/Komoditas
        if (props.Status) {
            html += `<div style="margin-top: 10px; padding: 8px; background: #ecf0f1; border-radius: 4px; font-size: 12px;">
                <strong>🌾 Komoditas:</strong> ${props.Status}
            </div>`;
        }

        html += '</div></div>';
        return html;
    }

    // Load wilayah data and populate select
    function loadWilayahDataAndStats() {
        fetch('/api/wilayah/data')
            .then(response => response.json())
            .then(data => {
                allWilayahData = data.data;
                wilayahData = data.data; // Store untuk filtering

                // Populate wilayah button grid (for filter)
                const wilayahButtonGrid = document.getElementById('wilayahButtonGrid');
                wilayahButtonGrid.innerHTML = '';
                
                data.data.forEach(wilayah => {
                    const btn = document.createElement('button');
                    btn.className = 'grid-btn';
                    btn.setAttribute('data-value', wilayah.wilayah);
                    btn.textContent = wilayah.wilayah;
                    btn.title = `Wilayah ${wilayah.wilayah} (${wilayah.feature_count} plot, ${wilayah.total_area} Ha)`;
                    btn.onclick = () => selectGridOption('wilayah', wilayah.wilayah, `Wilayah ${wilayah.wilayah}`);
                    wilayahButtonGrid.appendChild(btn);
                });

                // Update statistics
                document.getElementById('totalWilayah').textContent = data.total_wilayah;
                let totalArea = 0;
                let totalPlot = 0;
                let totalBersih = 0;
                let totalBerat = 0;

                data.data.forEach(w => {
                    totalArea += w.total_area;
                    totalPlot += w.feature_count;
                });

                document.getElementById('totalArea').textContent = totalArea.toFixed(2);
                document.getElementById('totalPlot').textContent = totalPlot;

                // Load all geojson to calculate gulma stats
                const wilayahNumbers = data.data.map(w => w.wilayah);
                const promises = wilayahNumbers.map(num => 
                    fetch(`/api/wilayah/geojson/${num}`)
                        .then(r => r.json())
                        .then(geojson => ({ wilayah: num, geojson }))
                );

                return Promise.all(promises).then(results => {
                    results.forEach(({ geojson }) => {
                        if (geojson.features) {
                            geojson.features.forEach(feature => {
                                const status = (feature.properties.Kelas_weed || feature.properties.gulma_KATEGORI || '').toLowerCase();
                                if (status.includes('bersih')) totalBersih++;
                                else if (status.includes('berat')) totalBerat++;
                            });
                        }
                    });

                    document.getElementById('totalBersih').textContent = totalBersih;
                    document.getElementById('totalBerat').textContent = totalBerat;

                    // Render wilayah grid
                    renderWilayah(data.data);

                    console.log('Wilayah data loaded:', data);
                });
            })
            .catch(error => {
                console.error('Error loading wilayah data:', error);
                alert('Gagal memuat data: ' + error.message);
            });
    }

    // Render wilayah cards
    function renderWilayah(data) {
        const grid = document.getElementById('wilayahGrid');
        grid.innerHTML = '';

        if (!data || data.length === 0) {
            grid.innerHTML = '<div style="text-align: center; width: 100%; padding: 40px; color: #999;">Tidak ada data wilayah</div>';
            return;
        }

        data.forEach(wilayah => {
            const card = document.createElement('div');
            card.className = 'wilayah-card';
            card.style.cursor = 'pointer';
            card.onclick = () => {
                document.getElementById('wilayahSelect').value = wilayah.wilayah;
                loadWilayahMap();
                document.getElementById('map').scrollIntoView({ behavior: 'smooth' });
            };

            card.innerHTML = `
                <div class="wilayah-card-header">
                    <h3>Wilayah ${wilayah.wilayah}</h3>
                    <div style="margin-top: 3px; font-size: 9px; opacity: 0.9;">
                        <i class="fas fa-map-pin"></i> Lampung Tengah
                    </div>
                </div>
                <div class="wilayah-card-body">
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-ruler-combined"></i>
                            <span>Luas</span>
                        </span>
                        <span class="info-value">
                            ${parseFloat(wilayah.total_area ?? 0).toFixed(2)} Ha
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-th-large"></i>
                            <span>Plot</span>
                        </span>
                        <span class="info-value">
                            ${wilayah.feature_count ?? 0}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-seedling"></i>
                            <span>Status</span>
                        </span>
                        <span class="info-value" style="background: #e8f5e9; color: #128241;">
                            Aktif
                        </span>
                    </div>
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 2px dashed #e0e0e0; text-align: center;">
                        <button onclick="event.stopPropagation(); document.getElementById('wilayahSelect').value = ${wilayah.wilayah}; loadWilayahMap(); document.getElementById('map').scrollIntoView({ behavior: 'smooth' });" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 10px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(18, 130, 65, 0.2);">
                            <i class="fas fa-map"></i> Lihat Peta
                        </button>
                    </div>
                </div>
            `;

            grid.appendChild(card);
        });
    }

    function filterWilayah() {
        const search = document.getElementById('searchWilayah').value.toLowerCase();
        const komoditas = document.getElementById('filterKomoditas').value;

        const filtered = wilayahData.filter(w => {
            const wilayahName = `wilayah ${w.wilayah}`.toLowerCase();
            const matchSearch = wilayahName.includes(search) || w.wilayah.toString().includes(search);
            const matchKomoditas = !komoditas || (w.kategori && w.kategori.toLowerCase().includes(komoditas));
            return matchSearch && matchKomoditas;
        });

        renderWilayah(filtered);
    }

    // Real-time search
    document.getElementById('searchWilayah').addEventListener('keyup', filterWilayah);
    document.getElementById('filterKomoditas').addEventListener('change', filterWilayah);

    // Update period info display
    function updatePeriodInfoDisplay(period) {
        const bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const bulanName = bulanNames[parseInt(period.bulan)];
        
        document.getElementById('periodInfoText').innerHTML = 
            `<strong>Menampilkan Data Terbaru</strong> - Tahun ${period.tahun}, ${bulanName}, Minggu ke-${period.minggu}`;
    }

    // Load available periods (tahun, bulan, minggu)
    async function loadAvailablePeriods() {
        try {
            const response = await fetch('/api/wilayah/periods');
            const data = await response.json();
            
            if (data.success) {
                // Populate tahun button grid
                const tahunGrid = document.getElementById('tahunGrid');
                tahunGrid.innerHTML = '';
                
                data.tahun_list.forEach(tahun => {
                    const btn = document.createElement('button');
                    btn.className = 'grid-btn';
                    btn.setAttribute('data-value', tahun);
                    btn.textContent = tahun;
                    btn.onclick = () => selectGridOption('tahun', tahun, tahun);
                    tahunGrid.appendChild(btn);
                });
                
                // Store latest period
                latestPeriod = data.latest_period;
                
                // Set default to latest period if available
                if (latestPeriod) {
                    document.getElementById('tahunSelect').value = latestPeriod.tahun;
                    document.getElementById('bulanSelect').value = latestPeriod.bulan;
                    document.getElementById('mingguSelect').value = latestPeriod.minggu;
                    
                    // Update display
                    document.getElementById('tahunSelected').textContent = latestPeriod.tahun;
                    const bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    document.getElementById('bulanSelected').textContent = bulanNames[parseInt(latestPeriod.bulan)];
                    document.getElementById('mingguSelected').textContent = 'Minggu ke-' + latestPeriod.minggu;
                    
                    // Mark selected buttons
                    tahunGrid.querySelector(`[data-value="${latestPeriod.tahun}"]`)?.classList.add('selected');
                    document.getElementById('bulanGrid').querySelector(`[data-value="${latestPeriod.bulan}"]`)?.classList.add('selected');
                    document.getElementById('mingguGrid').querySelector(`[data-value="${latestPeriod.minggu}"]`)?.classList.add('selected');
                    
                    currentPeriod = latestPeriod;
                    
                    // Update period info display (fixed, won't change unless manually selected)
                    updatePeriodInfoDisplay(latestPeriod);
                    
                    console.log('Loading data for latest period:', latestPeriod);
                }
                
                // Load wilayah data and stats
                loadWilayahDataAndStats();
                
                // Auto-load all wilayah map with latest data
                if (latestPeriod) {
                    setTimeout(() => {
                        loadAllWilayah();
                    }, 500);
                }
            }
        } catch (error) {
            console.error('Error loading periods:', error);
            // Still try to load data even if periods fail
            loadWilayahDataAndStats();
        }
    }

    // Check and load data for selected period
    async function checkPeriodAndLoadData() {
        const tahun = document.getElementById('tahunSelect').value;
        const bulan = document.getElementById('bulanSelect').value;
        const minggu = document.getElementById('mingguSelect').value;
        
        if (!tahun || !bulan || !minggu) {
            alert('Silakan pilih Tahun, Bulan, dan Minggu terlebih dahulu');
            return false;
        }
        
        // Check if user is selecting the same as latest period
        const isLatestPeriod = latestPeriod && 
            latestPeriod.tahun == tahun && 
            latestPeriod.bulan == bulan && 
            latestPeriod.minggu == minggu;
        
        try {
            const response = await fetch(`/api/wilayah/data-by-period?tahun=${tahun}&bulan=${bulan}&minggu=${minggu}`);
            const data = await response.json();
            
            if (!data.data_available) {
                // Show notification
                const notification = document.createElement('div');
                notification.style.cssText = `
                    position: fixed;
                    top: 80px;
                    right: 20px;
                    background: #f39c12;
                    color: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                    z-index: 10000;
                    max-width: 400px;
                `;
                notification.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i> ${data.message}
                `;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 5000);
                
                // Update to latest period
                if (data.showing_latest && data.period) {
                    document.getElementById('tahunSelect').value = data.period.tahun;
                    document.getElementById('bulanSelect').value = data.period.bulan;
                    document.getElementById('mingguSelect').value = data.period.minggu;
                    currentPeriod = data.period;
                    // Don't update display - keep showing "Data Terbaru"
                }
            } else {
                currentPeriod = { tahun, bulan, minggu };
                // Only update display if user manually selected different period
                if (!isLatestPeriod) {
                    updatePeriodInfoDisplay(currentPeriod);
                }
            }
            
            return true;
        } catch (error) {
            console.error('Error checking period:', error);
            return true; // Continue anyway
        }
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing GulmaTrack Wilayah Map...');
        initMap();
        loadAvailablePeriods(); // Load periods and then auto-load map
    });
    </script>
</div>

@endsection
