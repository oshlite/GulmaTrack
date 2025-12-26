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
                        <i class="fas fa-calendar"></i> Tahun
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
                        <i class="fas fa-calendar-alt"></i> Bulan
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
                        <i class="fas fa-map-pin"></i> Wilayah
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
                <button onclick="loadAllWilayah()" class="btn-primary">
                    <i class="fas fa-globe"></i> Semua Wilayah
                </button>
                <button onclick="loadWilayahMap()" class="btn-secondary">
                    <i class="fas fa-search"></i> Tampilkan Peta
                </button>
            </div>
        </div>
    </div>

        <!-- Status Info -->
    <div id="periodInfo" style="background: #ecf0f1; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid var(--primary-color);">
        <i class="fas fa-info-circle" style="color: var(--accent-color); margin-right: 8px;"></i>
        <span id="periodInfoText" style="font-size: 13px; color: #FBA919;">
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
            border-left: 4px solid #128241;
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
            color: #FBA919;
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
            padding: 12px;
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

/* BASE BUTTON */
.btn-primary,
.btn-secondary {
    flex: 1;
    padding: 13px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    letter-spacing: 0.6px;
    font-family: 'Poppins';
    position: relative;
    overflow: hidden;
    white-space: nowrap;
    transition: background 0.2s ease, color 0.2s ease, border 0.2s ease;
}

.btn-primary {
    background: white;
    border: 2px solid #d8e1dd;
    color: #128241;
    box-shadow: none;
}

.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active {
    background: #128241;
    border-color: #128241;
    color: white;
    box-shadow: none;
    outline: none;
}

.btn-primary::before {
    display: none;
}

.btn-secondary {
    background: white;
    border: 2px solid #d8e1dd;
    color: #FBA919;
    box-shadow: none;
}

.btn-secondary:hover,
.btn-secondary:focus,
.btn-secondary:active {
    background: #FBA919;
    border-color: #FBA919;
    color: white;
    box-shadow: none;
    outline: none;
}

.btn-secondary::before {
    display: none;
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
            margin: 0 0 2px 6px;
            font-size: 16px;
            font-weight: 600;
            color: #FBA919;
        }

        .legend-item {
            display: flex;
            align-items: center;
            font-size: 13px;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.3s ease;
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
        
        /* Tooltip styling */
        .leaflet-tooltip {
            background: white;
            border: 2px solid var(--primary-color);
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            padding: 8px 12px !important;
            font-family: 'Poppins';
        }
        .leaflet-tooltip-top:before {
            border-top-color: var(--primary-color);
        }
        
        /* Permanent location label styling */
        .location-label {
            background: rgba(18, 130, 65, 0.9) !important;
            border: 2px solid white !important;
            border-radius: 4px !important;
            color: white !important;
            font-weight: 600 !important;
            font-size: 11px !important;
            padding: 4px 8px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3) !important;
            font-family: 'Poppins', sans-serif !important;
        }
        .location-label:before {
            display: none !important;
        }
        
        /* Location Details Table */
        .location-details-container {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
            display: none;
        }
        
        .location-details-container.active {
            display: block;
            animation: slideDown 0.3s ease;
        }
        
        .location-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Poppins';
        }
        
        .location-table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .location-table th {
            padding: 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
        }
        
        .location-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 12px;
        }
        
        .location-table tbody tr:hover {
            background: #f0f8f5;
            cursor: pointer;
        }
        
        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-bersih { background: #3498db; color: white; }
        .status-ringan { background: #128241; color: white; }
        .status-sedang { background: #f1c40f; color: #333; }
        .status-berat { background: #e74c3c; color: white; }
        .status-unknown { background: #ecf0f1; color: #666; }
    </style>

    <!-- Map Container -->
    <div class="map-container">
        <div id="map"></div>
        <div class="map-legend">
            <h4 onclick="filterByStatus('')" style="cursor:pointer;">
    <i class="fas fa-info-circle"></i>  Status Gulma
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
    </div>

    <!-- Daftar Wilayah -->
    <div style="margin: 30px 0 20px;">
        <h2 style="font-size: 35px; font-weight: 700; color: #2c3e50; margin-bottom: 8px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-map-marked-alt" style="color: var(--primary-color);"></i>
            Daftar Wilayah
            <div style="display: flex; justify-content: space-between; align-items: center;">
            <button
onclick="toggleLocationDetails()"
style="
background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
color: white;
border: none;
padding: 10px 16px;
border-radius: 6px;
cursor: pointer;
font-size: 12px;
font-weight: 600;
display: flex;
align-items: center;
gap: 6px;



transition: transform 0.2s ease;
"
onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.18), 0 0 16px rgba(18,130,65,0.45)'"
onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.15), 0 0 12px rgba(18,130,65,0.35)'"
>

                <i class="fas fa-table" id="toggleIcon"></i>
                <span id="toggleText">Tampilkan Tabel Lokasi</span>
            </button>
        </div>
        </h2>
        
    </div>
    
    <!-- Location Details Table -->
    <div class="location-details-container" id="locationDetailsTable">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0; color: var(--primary-color); font-size: 18px;">
                <i class="fas fa-list-alt"></i> Detail Lokasi per Wilayah
            </h3>
            <span id="tableInfoText" style="font-size: 12px; color: #666;"></span>
        </div>
        <div style="overflow-x: auto;">
            <table class="location-table" id="locationTable">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> No</th>
                        <th><i class="fas fa-map-marker-alt"></i> Wilayah</th>
                        <th><i class="fas fa-map-pin"></i> Kode Lokasi</th>
                        <th><i class="fas fa-seedling"></i> Status Gulma</th>
                        <th><i class="fas fa-ruler-combined"></i> Luas (Ha)</th>
                    </tr>
                </thead>
                <tbody id="locationTableBody">
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                            <i class="fas fa-info-circle" style="font-size: 24px; margin-bottom: 10px;"></i><br>
                            Pilih wilayah atau filter status untuk melihat data lokasi
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Kontrol Wilayah -->
    <div class="wilayah-controls" style="margin-bottom: 25px; display: flex; gap: 15px; align-items: flex-start;">
        <div style="flex: 0 0 30%; position: relative;">
            <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #FBA919; font-size: 16px;"></i>
            <input type="text" id="searchWilayah" placeholder="Cari wilayah..." style="width: 100%; padding: 14px 15px 14px 45px; border: 2px solid #e0e0e0; border-radius: 10px; font-family: 'Poppins'; font-size: 14px; transition: all 0.3s ease; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" onfocus="this.style.borderColor='#FBA919'; this.style.boxShadow='0 0 0 4px rgba(251, 169, 25, 0.1), 0 2px 8px rgba(0,0,0,0.08)';" onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
        </div>
        <div class="control-item" style="flex: 1; position: relative;">
            <!-- Hidden input to store selected value -->
            <input type="hidden" id="filterKomoditas" value="">
            
            <!-- Button Grid Trigger -->
            <div class="button-grid-trigger" onclick="toggleButtonGrid('statusGulma')" style="width: 100%; padding: 14px 50px 14px 45px; border: 2px solid #e0e0e0; border-radius: 10px; font-family: 'Poppins'; font-size: 14px; font-weight: 500; background: white; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; align-items: center; justify-content: space-between; position: relative;">
                <i class="fas fa-filter" style="position: absolute; left: 15px; color: #FBA919; font-size: 16px;"></i>
                <span class="grid-selected-text" id="statusGulmaSelected" style="flex: 1; color: #2c3e50;">Kategori Status Gulma</span>
                <i class="fas fa-chevron-down grid-arrow" style="color: #FBA919; font-size: 14px; transition: transform 0.3s ease;"></i>
            </div>
            
            <!-- Button Grid -->
            <div class="button-grid" id="statusGulmaGrid" style="display: none; grid-template-columns: repeat(5, 1fr); gap: 5px; padding: 5px; background: white; border: 2px solid #e0e0e0; border-radius: 10px; margin-top: 0px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); position: absolute; width: 100%; z-index: 0; animation: slideDown 0.3s ease;">
                <button class="grid-btn" data-value="bersih" data-color="#3498db" onclick="selectStatusGulma('bersih', 'Bersih', '#3498db')" style="padding: 8px 6px; border: 2px solid #e0e0e0; border-radius: 8px; background: white; cursor: pointer; font-family: 'Poppins'; font-size: 12px; font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; justify-content: center;">
                    <div style="width: 18px; height: 18px; border-radius: 3px; border: 2px solid #3498db; background: #3498db; flex-shrink: 0;"></div>
                    <span>Bersih</span>
                </button>
                <button class="grid-btn" data-value="ringan" data-color="#128241" onclick="selectStatusGulma('ringan', 'Ringan', '#128241')" style="padding: 8px 6px; border: 2px solid #e0e0e0; border-radius: 8px; background: white; cursor: pointer; font-family: 'Poppins'; font-size: 12px; font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; justify-content: center;">
                    <div style="width: 18px; height: 18px; border-radius: 3px; border: 2px solid #128241; background: #128241; flex-shrink: 0;"></div>
                    <span>Ringan</span>
                </button>
                <button class="grid-btn" data-value="sedang" data-color="#f1c40f" onclick="selectStatusGulma('sedang', 'Sedang', '#f1c40f')" style="padding: 8px 6px; border: 2px solid #e0e0e0; border-radius: 8px; background: white; cursor: pointer; font-family: 'Poppins'; font-size: 12px; font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; justify-content: center;">
                    <div style="width: 18px; height: 18px; border-radius: 3px; border: 2px solid #f1c40f; background: #f1c40f; flex-shrink: 0;"></div>
                    <span>Sedang</span>
                </button>
                <button class="grid-btn" data-value="berat" data-color="#e74c3c" onclick="selectStatusGulma('berat', 'Berat', '#e74c3c')" style="padding: 8px 6px; border: 2px solid #e0e0e0; border-radius: 8px; background: white; cursor: pointer; font-family: 'Poppins'; font-size: 12px; font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; justify-content: center;">
                    <div style="width: 18px; height: 18px; border-radius: 3px; border: 2px solid #e74c3c; background: #e74c3c; flex-shrink: 0;"></div>
                    <span>Berat</span>
                </button>
                <button class="grid-btn" data-value="belum_dimonitoring" data-color="#ecf0f1" onclick="selectStatusGulma('belum_dimonitoring', 'Belum Dimonitoring', '#ecf0f1')" style="padding: 8px 6px; border: 2px solid #e0e0e0; border-radius: 8px; background: white; cursor: pointer; font-family: 'Poppins'; font-size: 12px; font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; justify-content: center;">
                    <div style="width: 18px; height: 18px; border-radius: 3px; border: 2px solid #999; background: #ecf0f1; flex-shrink: 0;"></div>
                    <span>Belum Dimonitoring</span>
                </button>
                <!-- <button class="grid-btn selected" data-value="" data-color="linear-gradient(135deg, #3498db, #e74c3c)" onclick="selectStatusGulma('', 'Kategori Status Gulma', 'linear-gradient(135deg, #3498db, #e74c3c)')" style="padding: 8px 6px; border: 2px solid #e0e0e0; border-radius: 8px; background: white; cursor: pointer; font-family: 'Poppins'; font-size: 12px; font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; justify-content: center; grid-column: 1 / -1;">
                    <div style="width: 18px; height: 18px; border-radius: 3px; border: 2px solid #ddd; background: linear-gradient(135deg, #3498db, #e74c3c); flex-shrink: 0;"></div>
                    <span style="color: #2c3e50; display: inline-block; font-weight: 500;">Semua Data</span>
                </button> -->
            </div>
        </div>
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
    function selectStatusGulma(value, label, color) {
        // Update hidden input
        document.getElementById('filterKomoditas').value = value;
        
        // Update display text
        document.getElementById('statusGulmaSelected').textContent = label;
        
        // Update selected button
        const grid = document.getElementById('statusGulmaGrid');
        grid.querySelectorAll('.grid-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        event.target.closest('.grid-btn').classList.add('selected');
        
        // Close grid
        grid.style.display = 'none';
        grid.previousElementSibling.classList.remove('active');
        
        // Trigger filter
        filterWilayah();
    }
    
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

    // Toggle location details table
    function toggleLocationDetails() {
        const table = document.getElementById('locationDetailsTable');
        const icon = document.getElementById('toggleIcon');
        const text = document.getElementById('toggleText');
        
        if (table.classList.contains('active')) {
            table.classList.remove('active');
            icon.className = 'fas fa-table';
            text.textContent = 'Tampilkan Tabel Lokasi';
        } else {
            table.classList.add('active');
            icon.className = 'fas fa-times';
            text.textContent = 'Sembunyikan Tabel';
        }
    }
    
    // Filter by status (from legend click)
    function filterByStatus(status) {
        currentStatusFilter = status;
        
        // Set filter dropdown to match
        document.getElementById('filterKomoditas').value = status;
        
        // Enable location labels when status filter is active
        if (status) {
            showLocationLabels = true;
            // Open location details table
            document.getElementById('locationDetailsTable').classList.add('active');
            document.getElementById('toggleIcon').className = 'fas fa-times';
            document.getElementById('toggleText').textContent = 'Sembunyikan Tabel';
        } else {
            // Reset: no labels, no table, reload all
            showLocationLabels = false;
            currentStatusFilter = '';
            // Hide table when clicking "Semua Data"
            document.getElementById('locationDetailsTable').classList.remove('active');
            document.getElementById('toggleIcon').className = 'fas fa-table';
            document.getElementById('toggleText').textContent = 'Tampilkan Tabel Lokasi';
        }
        
        // Reload map with filter applied
        if (Object.keys(geoJsonLayers).length > 0) {
            loadAllWilayah();
        }
        
        // Scroll to map
        document.getElementById('map').scrollIntoView({ behavior: 'smooth' });
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
    let showLocationLabels = false; // Track whether to show location code labels
    let currentStatusFilter = ''; // Track current status filter
    let allLocationData = []; // Store all location data for table

    // Initialize map
    function initMap() {
        // Check if map already exists
        if (map) {
            map.remove();
        }

        // Create map centered on Lampung Tengah
        map = L.map('map', {
            center: [-4.85, 105.0],
            zoom: 12,
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

        // Enable location labels when specific wilayah is selected
        showLocationLabels = true;

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
                            
                            // Add tooltip for hover (quick info)
                            layer.bindTooltip(createTooltipContent(feature.properties), {
                                permanent: false,
                                sticky: true,
                                direction: 'top',
                                offset: [0, -10]
                            });
                            
                            // Add permanent label if showLocationLabels is true
                            if (showLocationLabels) {
                                const lokasi = feature.properties.Lokasi || feature.properties.LOKASI || feature.properties.seksi || feature.properties.id_feature || 'N/A';
                                layer.bindTooltip(lokasi, {
                                    permanent: true,
                                    direction: 'center',
                                    className: 'location-label'
                                });
                            }
                            
                            // Add hover effect - lift on hover
                            layer.on('mouseover', function(e) {
                                const originalStyle = getFeatureStyle(feature);
                                this.setStyle({
                                    weight: 8,
                                    fillOpacity: 0.8,
                                    opacity: 1,
                                    color: originalStyle.color
                                });
                                this.bringToFront();
                            });
                            
                            layer.on('mouseout', function(e) {
                                this.setStyle(getFeatureStyle(feature));
                            });
                        }
                    }
                }).addTo(map);

                geoJsonLayers[wilayahNumber] = layer;

                // Fit map to bounds
                const bounds = layer.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [80, 80], maxZoom: 14 });
                }

                console.log(`Wilayah ${wilayahNumber} loaded: ${data.features.length} features`);
                
                // Populate location table
                populateLocationTable(data.features, wilayahNumber);
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

        // Disable location labels when showing all wilayah (keep map clean)
        showLocationLabels = false;

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
                        // Filter features by status if filter is active
                        let features = data.features;
                        if (currentStatusFilter) {
                            features = features.filter(f => {
                                const status = (f.properties.Kelas_weed || f.properties.gulma_KATEGORI || f.properties.Status || '').toLowerCase();
                                if (currentStatusFilter === 'belum_dimonitoring') {
                                    // Filter untuk belum dimonitoring (status kosong atau tidak dikenali)
                                    return !status || (!status.includes('bersih') && !status.includes('ringan') && !status.includes('sedang') && !status.includes('berat'));
                                }
                                return status.includes(currentStatusFilter);
                            });
                        }
                        
                        // Skip if no features match filter
                        if (features.length === 0) return;
                        
                        const layer = L.geoJSON({ type: 'FeatureCollection', features }, {
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
                                    
                                    // Add tooltip for hover (quick info)
                                    layer.bindTooltip(createTooltipContent(feature.properties), {
                                        permanent: false,
                                        sticky: true,
                                        direction: 'top',
                                        offset: [0, -10]
                                    });
                                    
                                    // Add permanent label if showLocationLabels is true
                                    if (showLocationLabels) {
                                        const lokasi = feature.properties.Lokasi || feature.properties.LOKASI || feature.properties.seksi || feature.properties.id_feature || 'N/A';
                                        layer.bindTooltip(lokasi, {
                                            permanent: true,
                                            direction: 'top',
                                            className: 'location-label'
                                        });
                                    }
                                    
                                    // Add hover effect - lift on hover
                                    layer.on('mouseover', function(e) {
                                        const originalStyle = getFeatureStyle(feature);
                                        this.setStyle({
                                            weight: 8,
                                            fillOpacity: 0.8,
                                            opacity: 1,
                                            color: originalStyle.color
                                        });
                                        this.bringToFront();
                                    });
                                    
                                    layer.on('mouseout', function(e) {
                                        this.setStyle(getFeatureStyle(feature));
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
                    
                    map.fitBounds(combinedBounds, { padding: [80, 80], maxZoom: 13 });
                }

                console.log(`Loaded ${results.length} wilayah successfully`);
                
                // Populate location table with all data (filtered if needed)
                const allFeatures = results.flatMap(r => {
                    if (!r.data.features) return [];
                    if (currentStatusFilter) {
                        return r.data.features.filter(f => {
                            const status = (f.properties.Kelas_weed || f.properties.gulma_KATEGORI || f.properties.Status || '').toLowerCase();
                            return status.includes(currentStatusFilter);
                        });
                    }
                    return r.data.features;
                });
                populateLocationTable(allFeatures);
                
                // Apply status filter to table if active
                if (currentStatusFilter) {
                    filterLocationsByStatus(currentStatusFilter);
                }
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

    // Create tooltip content for hover (quick info)
    function createTooltipContent(props) {
        const statusGulma = props.Kelas_weed || props.gulma_KATEGORI || props.Status || 'Tidak Ada Data';
        const lokasi = props.Lokasi || props.LOKASI || props.seksi || props.id_feature || 'N/A';
        
        // Get color for status
        let statusColor = '#9ca3af';
        if (statusGulma.toLowerCase().includes('bersih')) {
            statusColor = '#3498db';
        } else if (statusGulma.toLowerCase().includes('ringan')) {
            statusColor = '#128241';
        } else if (statusGulma.toLowerCase().includes('sedang')) {
            statusColor = '#f1c40f';
        } else if (statusGulma.toLowerCase().includes('berat')) {
            statusColor = '#e74c3c';
        }
        
        return `<div style="font-family: 'Poppins'; font-size: 13px; padding: 5px;">
            <strong>📍 Kode Lokasi:</strong> ${lokasi}<br>
            <strong>Status Gulma:</strong> <span style="color: ${statusColor}; font-weight: bold;">${statusGulma}</span>
        </div>`;
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
                    btn.textContent = `Wil. ${wilayah.wilayah}`;
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
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 2px dashed #e0e0e0; text-align: center; display: flex; gap: 8px; justify-content: center;">
                        <button onclick="event.stopPropagation(); document.getElementById('wilayahSelect').value = ${wilayah.wilayah}; loadWilayahMap(); document.getElementById('map').scrollIntoView({ behavior: 'smooth' });" style="flex: 1; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none; padding: 6px 8px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 10px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(18, 130, 65, 0.2);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(18, 130, 65, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(18, 130, 65, 0.2)';">
                            <i class="fas fa-map"></i> Lihat Peta
                        </button>
                        <button onclick="event.stopPropagation(); document.getElementById('wilayahSelect').value = ${wilayah.wilayah}; loadWilayahMap(); document.getElementById('locationDetailsTable').classList.add('active'); document.getElementById('toggleIcon').className = 'fas fa-times'; document.getElementById('toggleText').textContent = 'Sembunyikan Tabel'; document.getElementById('locationDetailsTable').scrollIntoView({ behavior: 'smooth' });" style="flex: 1; background: linear-gradient(135deg, #FBA919, #f39c12); color: white; border: none; padding: 6px 8px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 10px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(251, 169, 25, 0.2);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(251, 169, 25, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(251, 169, 25, 0.2)';">
                            <i class="fas fa-table"></i> Tabel
                        </button>
                    </div>
                </div>
            `;

            grid.appendChild(card);
        });
    }

    // Populate location table
    function populateLocationTable(features, wilayahNumber = null) {
        allLocationData = [];
        const tbody = document.getElementById('locationTableBody');
        tbody.innerHTML = '';
        
        if (!features || features.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px; color: #999;">Tidak ada data lokasi</td></tr>';
            return;
        }
        
        features.forEach((feature, index) => {
            const props = feature.properties;
            const lokasi = props.Lokasi || props.LOKASI || props.seksi || props.id_feature || 'N/A';
            const status = (props.Kelas_weed || props.gulma_KATEGORI || props.Status || 'Tidak Ada Data').toLowerCase();
            const wilayah = wilayahNumber || props.Wilayah || props.gulma_Wilayah || '-';
            const luas = props.Luas_Bruto || props.Bruto || props.bruto || '-';
            
            // Store for filtering
            allLocationData.push({
                wilayah,
                lokasi,
                status,
                luas,
                feature
            });
            
            // Determine status class
            let statusClass = 'status-unknown';
            let statusText = 'Tidak Ada Data';
            if (status.includes('bersih')) {
                statusClass = 'status-bersih';
                statusText = 'Bersih';
            } else if (status.includes('ringan')) {
                statusClass = 'status-ringan';
                statusText = 'Ringan';
            } else if (status.includes('sedang')) {
                statusClass = 'status-sedang';
                statusText = 'Sedang';
            } else if (status.includes('berat')) {
                statusClass = 'status-berat';
                statusText = 'Berat';
            }
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${wilayah}</td>
                <td><strong>${lokasi}</strong></td>
                <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                <td>${luas}</td>
            `;
            tbody.appendChild(row);
        });
        
        updateTableInfo();
    }
    
    // Update table info text
    function updateTableInfo() {
        const tbody = document.getElementById('locationTableBody');
        const count = tbody.querySelectorAll('tr').length;
        const infoText = document.getElementById('tableInfoText');
        
        if (currentStatusFilter) {
            let displayStatus = currentStatusFilter.charAt(0).toUpperCase() + currentStatusFilter.slice(1);
            if (currentStatusFilter === 'belum_dimonitoring') {
                displayStatus = 'Belum Dimonitoring';
            }
            infoText.textContent = `Menampilkan ${count} lokasi dengan status: ${displayStatus}`;
        } else {
            infoText.textContent = `Total: ${count} lokasi`;
        }
    }
    
    // Filter locations in table and map by status
    function filterLocationsByStatus(statusFilter) {
        const tbody = document.getElementById('locationTableBody');
        tbody.innerHTML = '';
        
        let filteredData;
        if (statusFilter) {
            if (statusFilter === 'belum_dimonitoring') {
                // Filter untuk belum dimonitoring
                filteredData = allLocationData.filter(loc => {
                    return !loc.status || (!loc.status.includes('bersih') && !loc.status.includes('ringan') && !loc.status.includes('sedang') && !loc.status.includes('berat'));
                });
            } else {
                filteredData = allLocationData.filter(loc => loc.status.includes(statusFilter));
            }
        } else {
            filteredData = allLocationData;
        }
        
        if (filteredData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px; color: #999;">Tidak ada data dengan status ini</td></tr>';
            return;
        }
        
        filteredData.forEach((loc, index) => {
            let statusClass = 'status-unknown';
            let statusText = 'Tidak Ada Data';
            if (loc.status.includes('bersih')) {
                statusClass = 'status-bersih';
                statusText = 'Bersih';
            } else if (loc.status.includes('ringan')) {
                statusClass = 'status-ringan';
                statusText = 'Ringan';
            } else if (loc.status.includes('sedang')) {
                statusClass = 'status-sedang';
                statusText = 'Sedang';
            } else if (loc.status.includes('berat')) {
                statusClass = 'status-berat';
                statusText = 'Berat';
            }
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${loc.wilayah}</td>
                <td><strong>${loc.lokasi}</strong></td>
                <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                <td>${loc.luas}</td>
            `;
            tbody.appendChild(row);
        });
        
        updateTableInfo();
    }

    function filterWilayah() {
        const search = document.getElementById('searchWilayah').value.toLowerCase();
        const komoditas = document.getElementById('filterKomoditas').value;

        // Filter kartu hanya berdasarkan search, bukan komoditas
        const filtered = wilayahData.filter(w => {
            const wilayahName = `wilayah ${w.wilayah}`.toLowerCase();
            const matchSearch = !search || wilayahName.includes(search) || w.wilayah.toString().includes(search);
            return matchSearch;
        });

        // Enable location labels and filter when filter is active
        if (komoditas) {
            // Filter kategori aktif
            showLocationLabels = true;
            currentStatusFilter = komoditas;
            
            // Show location table
            document.getElementById('locationDetailsTable').classList.add('active');
            document.getElementById('toggleIcon').className = 'fas fa-times';
            document.getElementById('toggleText').textContent = 'Sembunyikan Tabel';
            
            // Filter table if data exists
            if (allLocationData.length > 0) {
                filterLocationsByStatus(komoditas);
            }
            
            // Reload map with filtered data and labels
            if (Object.keys(geoJsonLayers).length > 0) {
                loadAllWilayah();
            }
        } else if (search) {
            // Hanya search tanpa filter kategori - tidak show tabel
            showLocationLabels = false;
            currentStatusFilter = '';
            document.getElementById('locationDetailsTable').classList.remove('active');
            document.getElementById('toggleIcon').className = 'fas fa-table';
            document.getElementById('toggleText').textContent = 'Tampilkan Tabel Lokasi';
        } else {
            // Reset semua
            showLocationLabels = false;
            currentStatusFilter = '';
            document.getElementById('locationDetailsTable').classList.remove('active');
            document.getElementById('toggleIcon').className = 'fas fa-table';
            document.getElementById('toggleText').textContent = 'Tampilkan Tabel Lokasi';
        }

        renderWilayah(filtered);
    }

    // Real-time search
    document.getElementById('searchWilayah').addEventListener('keyup', filterWilayah);

    // Update period info display
    function updatePeriodInfoDisplay(period) {
        const bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const bulanName = bulanNames[parseInt(period.bulan)];
        
        document.getElementById('periodInfoText').innerHTML = 
            `Menampilkan Data Terbaru - <strong>Tahun ${period.tahun}, ${bulanName}, Minggu ke-${period.minggu}</strong>`;
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
