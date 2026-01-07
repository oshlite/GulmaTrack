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
                <i class="fas fa-ruler-combined"></i> Total Luas Lokasi
            </div>
            <div class="stat-number" id="totalArea">-</div>
        </div>
    </div>

    <!-- Kontrol Peta -->
    <div class="wilayah-controls">
        <div class="controls-wrapper">
            <!-- Baris 1: Filter Fields -->
            <div class="controls-row">
                <div class="control-item compact">
                    <label class="control-label">
                        <i class="fas fa-calendar"></i> Tahun <span style="font-size: 11px; font-weight: 400; color: #e74c3c;">*</span>
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
                        <i class="fas fa-calendar-alt"></i> Bulan <span style="font-size: 11px; font-weight: 400; color: #e74c3c;">*</span>
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
                        <i class="fas fa-calendar-week"></i> Minggu <span style="font-size: 11px; font-weight: 400; color: #e74c3c;">*</span>
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
                        <i class="fas fa-map-pin"></i> Wilayah <span style="font-size: 11px; font-weight: 400; color: #999;">(Opsional)</span>
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
                <button onclick="loadAllWilayah()" class="btn-primary" title="Tampilkan peta untuk semua wilayah berdasarkan periode yang dipilih">
                    <i class="fas fa-globe"></i> Semua Wilayah
                </button>
                <button onclick="loadWilayahMap()" class="btn-secondary" title="Tampilkan peta berdasarkan wilayah dan periode yang dipilih (wilayah opsional)">
                    <i class="fas fa-search"></i> Tampilkan Peta
                </button>
            </div>
        </div>
    </div>

    <!-- Help Text untuk pengguna -->
    <div style="background: #ecf0f1; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #3498db; display: none;" id="helpText">
        <i class="fas fa-lightbulb" style="color: #3498db; margin-right: 8px;"></i>
        <span style="font-size: 12px; color: #555;">
            💡 <strong>Cara menggunakan:</strong> Pilih <strong>Tahun, Bulan, dan Minggu</strong> (wajib), lalu pilih wilayah jika ingin (opsional). Klik "Tampilkan Peta" untuk melihat data.
        </span>
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
            position: relative;
        }

        /* Disabled state untuk buttons yang tidak ada data */
        .grid-btn[disabled] {
            opacity: 0.4;
            cursor: not-allowed;
            background: #f5f5f5;
            color: #999;
            border-color: #ddd;
        }

        .grid-btn[disabled]:hover {
            border-color: #ddd;
            background: #f5f5f5;
            color: #999;
            transform: none;
            box-shadow: none;
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

        .map-error-container {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 15px;
            display: none;
        }

        .map-error-container .error-message {
            color: #856404;
            font-weight: 500;
            font-size: 14px;
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
            font-family: 'Poppins' !important;
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

        .reset-icon {
            color: #A6CE39; 
            cursor: pointer;
            font-size: 20px;
            margin-left: 10px;
            transition: all 0.25s ease;
        }

        .reset-icon:hover {
            color: #128241;
            text-shadow: 0 0 6px rgba(46, 204, 113, 0.9);
            transform: rotate(-12deg) scale(1.1);
        }

        
        .status-bersih { background: #3498db; color: white; }
        .status-ringan { background: #57ce39ff; color: white; }
        .status-sedang { background: #f1c40f; color: white; }
        .status-berat { background: #e74c3c; color: white; }
        .status-unknown { background: #ecf0f1; color: #333; }
    </style>

    <!-- Map Container -->
    <div class="map-container">
        <div class="map-error-container"></div>
        <div id="map"></div>
        <div class="map-legend">
            <h4 onclick="filterByStatus('')" style="cursor:pointer;" title="Semua Wilayah">
    <i class="fas fa-info-circle"></i>  Status Gulma
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
                <span><strong>Tidak Ada Data</strong></span>
            </div>
        </div>
    </div>

    <!-- Daftar Wilayah -->
    <div style="margin: 30px 0 20px;">
        <h2 style="font-size:35px;font-weight:700;color:#2c3e50;margin-bottom:8px;display:flex;align-items:center;gap:10px;cursor:pointer"
    onclick="filterByStatus('')" title="Reset filter wilayah">
    <i class="fas fa-map-marked-alt" style="color:var(--primary-color)"></i>
    Daftar Wilayah
            <div style="display: flex; justify-content: space-between; align-items: center;">
            <button
onclick="event.preventDefault(); event.stopPropagation(); toggleLocationDetails();"
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
            <i class="fas fa-undo reset-icon"
   onclick="filterByStatus('')"
   title="Tampilkan Semua Wilayah"></i>

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
                    <tr style="background-color: #128241;">
                        <th onclick="sortTable('no')" style="cursor: pointer; user-select: none; color: white; padding: 12px 8px;"><i class="fas fa-hashtag"></i> No <span id="sortIndicator_no" style="color: #D6DF20; font-size: 20px; margin-left: 5px;"><i class="fa-solid fa-sort"></i></span></th>
                        <th onclick="sortTable('wilayah')" style="cursor: pointer; user-select: none; color: white; padding: 12px 8px;"><i class="fas fa-map-marker-alt"></i> Wilayah <span id="sortIndicator_wilayah" style="color: #D6DF20; font-size: 20px; margin-left: 5px;"><i class="fa-solid fa-sort"></i></span></th>
                        <th onclick="sortTable('lokasi')" style="cursor: pointer; user-select: none; color: white; padding: 12px 8px;"><i class="fas fa-map-pin"></i> Kode Lokasi <span id="sortIndicator_lokasi" style="color: #D6DF20; font-size: 20px; margin-left: 5px;"><i class="fa-solid fa-sort"></i></span></th>
                        <th onclick="sortTable('status')" style="cursor: pointer; user-select: none; color: white; padding: 12px 8px;"><i class="fas fa-seedling"></i> Status Gulma <span id="sortIndicator_status" style="color: #D6DF20; font-size: 20px; margin-left: 5px;"><i class="fa-solid fa-sort"></i></span></th>
                        <th onclick="sortTable('tk')" style="cursor: pointer; user-select: none; color: white; padding: 12px 8px;"><i class="fas fa-users"></i> Butuh TK <span id="sortIndicator_tk" style="color: #D6DF20; font-size: 20px; margin-left: 5px;"><i class="fa-solid fa-sort"></i></span></th>
                        <th onclick="sortTable('aktivitas')" style="cursor: pointer; user-select: none; color: white; padding: 12px 8px;"><i class="fas fa-tasks"></i> Aktivitas <span id="sortIndicator_aktivitas" style="color: #D6DF20; font-size: 20px; margin-left: 5px;"><i class="fa-solid fa-sort"></i></span></th>
                        <th onclick="sortTable('luas')" style="cursor: pointer; user-select: none; color: white; padding: 12px 8px;"><i class="fas fa-ruler-combined"></i> Luas Netto <span id="sortIndicator_luas" style="color: #D6DF20; font-size: 20px; margin-left: 5px;"><i class="fa-solid fa-sort"></i></span></th>
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
                <button class="grid-btn" data-value="ringan" data-color="#57ce39ff" onclick="selectStatusGulma('ringan', 'Ringan', '#57ce39ff')" style="padding: 8px 6px; border: 2px solid #e0e0e0; border-radius: 8px; background: white; cursor: pointer; font-family: 'Poppins'; font-size: 12px; font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; justify-content: center;">
                    <div style="width: 18px; height: 18px; border-radius: 3px; border: 2px solid #57ce39ff; background: #57ce39ff; flex-shrink: 0;"></div>
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
                <button class="grid-btn" data-value="belum_dimonitoring" data-color="#ecf0f1" onclick="selectStatusGulma('belum_dimonitoring', 'Tidak Ada Data', '#ecf0f1')" style="padding: 8px 6px; border: 2px solid #e0e0e0; border-radius: 8px; background: white; cursor: pointer; font-family: 'Poppins'; font-size: 12px; font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; justify-content: center;">
                    <div style="width: 18px; height: 18px; border-radius: 3px; border: 2px solid #c4c4c4; background: #ecf0f1; flex-shrink: 0;"></div>
                    <span>Tidak Ada Data</span>
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
            <p style="margin-top: 20px; color: #c4c4c4ff;">Memuat data wilayah...</p>
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
        
        // CUSTOM LOGIC: Handle period-related dropdown updates
        if (type === 'tahun') {
            // Reset bulan & minggu selections
            document.getElementById('bulanSelect').value = '';
            document.getElementById('mingguSelect').value = '';
            document.getElementById('bulanSelected').textContent = 'Pilih Bulan...';
            document.getElementById('mingguSelected').textContent = 'Pilih Minggu ke-...';
            
            // Update bulan options
            updateBulanDropdown(value);
            
            console.log(`✅ Tahun dipilih: ${value}`);
        } else if (type === 'bulan') {
            const tahun = document.getElementById('tahunSelect').value;
            
            // Reset minggu selection
            document.getElementById('mingguSelect').value = '';
            document.getElementById('mingguSelected').textContent = 'Pilih Minggu ke-...';
            
            // Update minggu options
            updateMingguDropdown(tahun, value);
            
            console.log(`✅ Bulan dipilih: ${value}, tahun: ${tahun}`);
        } else if (type === 'minggu') {
            console.log(`✅ Minggu dipilih: ${value}`);
        }
        
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
        console.log('🔄 toggleLocationDetails() called');
        const table = document.getElementById('locationDetailsTable');
        const icon = document.getElementById('toggleIcon');
        const text = document.getElementById('toggleText');
        
        // Debug: pastikan elemen ada
        console.log('Table element found:', !!table);
        console.log('Icon element found:', !!icon);
        console.log('Text element found:', !!text);
        
        if (!table || !icon || !text) {
            console.error('❌ Error: Tidak dapat menemukan elemen yang diperlukan');
            return;
        }
        
        console.log('Current active state:', table.classList.contains('active'));
        
        if (table.classList.contains('active')) {
            console.log('✓ Removing active class - hiding table');
            table.classList.remove('active');
            icon.className = 'fas fa-table';
            text.textContent = 'Tampilkan Tabel Lokasi';
            console.log('✓ Active class removed, new state:', table.classList.contains('active'));
        } else {
            console.log('✓ Adding active class - showing table');
            table.classList.add('active');
            icon.className = 'fas fa-times';
            text.textContent = 'Sembunyikan Tabel';
            console.log('✓ Active class added, new state:', table.classList.contains('active'));
        }
    }
    
    // Filter by status (from legend click)
    function filterByStatus(status) {
        console.log(`🔍 filterByStatus() called with status: '${status}'`);
        currentStatusFilter = status;
        
        // Set filter dropdown to match
        document.getElementById('filterKomoditas').value = status;
        
        // SELALU TAMPILKAN TABEL LOKASI (baik saat filter aktif maupun reset)
        console.log(`✓ Selalu tampilkan tabel lokasi`);
        document.getElementById('locationDetailsTable').classList.add('active');
        document.getElementById('toggleIcon').className = 'fas fa-times';
        document.getElementById('toggleText').textContent = 'Sembunyikan Tabel';
        
        // Update labels visibility based on filter
        if (status) {
            console.log(`✓ Filter activated: ${status}`);
            showLocationLabels = true;
        } else {
            // Reset: no labels, but KEEP table visible
            console.log('✓ Filter reset - showing all data with table');
            showLocationLabels = false;
            currentStatusFilter = '';
        }
        
        // Reload map with filter applied
        console.log(`ℹ️ Current geoJsonLayers count: ${Object.keys(geoJsonLayers).length}`);
        if (Object.keys(geoJsonLayers).length > 0) {
            console.log(`📍 Reloading map with filter...`);
            loadAllWilayah();
        } else {
            console.log(`⚠️ No geoJsonLayers found, initializing...`);
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
    // Initialize map
function initMap() {
    console.log('🗺️  [WILAYAH] Starting initMap...');
    // Check if map already exists
    if (map) {
        console.log('🔄 [WILAYAH] Removing existing map...');
        map.remove();
    }

    // Create map centered on Lampung Tengah
    map = L.map('map', {
        center: [-4.85, 105.0],
        zoom: 12,
        zoomControl: true,
        attributionControl: true
    });

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

    // ===== PENTING: Event listener untuk load foto di popup TANPA JEDA =====
    map.on('popupopen', function (e) {
        console.log('🖼️  Popup opened, loading image...');
        const img = e.popup._contentNode.querySelector('img[data-kategori]');
        if (!img) {
            console.log('⚠️  No image with data-kategori found in popup');
            return;
        }

        const kategori = img.dataset.kategori;
        console.log('📸 Fetching foto for kategori:', kategori);

        // Jika belum_dimonitoring atau tidak ada kategori, gunakan placeholder
        if (!kategori || kategori === 'belum_dimonitoring') {
            console.log('ℹ️  Status belum_dimonitoring, using placeholder');
            img.src = '/image/foto.jpg';
            return;
        }

        // Fetch photos dari API untuk kategori yang valid
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 5000);
        
        fetch(`/api/gallery/kategori/${kategori}`, {
            signal: controller.signal
        })
            .then(r => {
                clearTimeout(timeoutId);
                if (!r.ok) throw new Error(`HTTP ${r.status}`);
                return r.json();
            })
            .then(r => {
                if (!r.success || !r.data || r.data.length === 0) {
                    console.log('ℹ️  No photos found for kategori:', kategori);
                    img.src = '/image/foto.jpg';
                    return;
                }

                // Get primary photo or first photo
                const primary = r.data.find(p => p.is_primary) || r.data[0];
                console.log('✅ Loaded image:', primary.foto_url);
                // Preload image before showing to avoid flicker
                const tempImg = new Image();
                tempImg.onload = () => {
                    img.src = primary.foto_url;
                };
                tempImg.onerror = () => {
                    console.warn('⚠️  Image load failed, using placeholder');
                    img.src = '/image/foto.jpg';
                };
                tempImg.src = primary.foto_url;
            })
            .catch(err => {
                clearTimeout(timeoutId);
                if (err.name !== 'AbortError') {
                    console.error('❌ Failed to load foto:', err.message);
                }
                img.src = '/image/foto.jpg'; // Fallback ke placeholder jika error
            });
    });

    console.log('✅ [WILAYAH] Map initialized successfully with popup photo loader');
}

    // Error handling functions
    function showMapError(message) {
        console.error('❌ Map Error:', message);
        // For wilayah.blade.php, display in alert or console
        const errorContainer = document.querySelector('.map-error-container');
        if (errorContainer) {
            errorContainer.innerHTML = `<div class="error-message">❌ ${message}</div>`;
            errorContainer.style.display = 'block';
        }
    }

    // Sort state tracking
    let currentSortColumn = null;
    let sortDirection = 'asc'; // 'asc' or 'desc'

    function sortTable(column) {
        const tbody = document.getElementById('locationTableBody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        // Check if we're already sorting by this column
        if (currentSortColumn === column) {
            // Toggle direction
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // New column, start with asc
            currentSortColumn = column;
            sortDirection = 'asc';
        }
        
        // Hide all sort icon indicators (reset to neutral)
        document.getElementById('sortIndicator_no').querySelector('i').className = 'fa-solid fa-sort';
        document.getElementById('sortIndicator_wilayah').querySelector('i').className = 'fa-solid fa-sort';
        document.getElementById('sortIndicator_lokasi').querySelector('i').className = 'fa-solid fa-sort';
        document.getElementById('sortIndicator_status').querySelector('i').className = 'fa-solid fa-sort';
        document.getElementById('sortIndicator_tk').querySelector('i').className = 'fa-solid fa-sort';
        document.getElementById('sortIndicator_aktivitas').querySelector('i').className = 'fa-solid fa-sort';
        document.getElementById('sortIndicator_luas').querySelector('i').className = 'fa-solid fa-sort';
        
        // Show sort indicator for current column
        const indicatorId = `sortIndicator_${column}`;
        const indicator = document.getElementById(indicatorId);
        if (indicator) {
            // Change arrow icon based on sort direction
            const icon = indicator.querySelector('i');
            if (sortDirection === 'asc') {
                icon.className = 'fa-solid fa-sort-up';
            } else {
                icon.className = 'fa-solid fa-sort-down';
            }
        }
        
        // Sort the rows
        rows.sort((a, b) => {
            let aVal, bVal;
            
            if (column === 'no') {
                aVal = parseInt(a.cells[0]?.textContent.trim()) || 0;
                bVal = parseInt(b.cells[0]?.textContent.trim()) || 0;
                return sortDirection === 'asc' ? aVal - bVal : bVal - aVal;
            } else if (column === 'wilayah') {
                aVal = a.cells[1]?.textContent.trim().toLowerCase() || '';
                bVal = b.cells[1]?.textContent.trim().toLowerCase() || '';
            } else if (column === 'lokasi') {
                aVal = a.cells[2]?.textContent.trim().toLowerCase() || '';
                bVal = b.cells[2]?.textContent.trim().toLowerCase() || '';
            } else if (column === 'status') {
                aVal = a.cells[3]?.textContent.trim().toLowerCase() || '';
                bVal = b.cells[3]?.textContent.trim().toLowerCase() || '';
            } else if (column === 'tk') {
                aVal = a.cells[4]?.textContent.trim().toLowerCase() || 'null';
                bVal = b.cells[4]?.textContent.trim().toLowerCase() || 'null';
                // Handle numeric sorting for TK
                const aNum = aVal === 'null' ? -1 : parseFloat(aVal);
                const bNum = bVal === 'null' ? -1 : parseFloat(bVal);
                return sortDirection === 'asc' ? aNum - bNum : bNum - aNum;
            } else if (column === 'aktivitas') {
                aVal = a.cells[5]?.textContent.trim().toLowerCase() || '';
                bVal = b.cells[5]?.textContent.trim().toLowerCase() || '';
            } else if (column === 'luas') {
                aVal = a.cells[6]?.textContent.trim().toLowerCase() || 'null';
                bVal = b.cells[6]?.textContent.trim().toLowerCase() || 'null';
                // Handle numeric sorting for Luas Netto
                const aNum = aVal === 'null' ? -1 : parseFloat(aVal);
                const bNum = bVal === 'null' ? -1 : parseFloat(bVal);
                return sortDirection === 'asc' ? aNum - bNum : bNum - aNum;
            }
            
            // String comparison
            if (sortDirection === 'asc') {
                return aVal.localeCompare(bVal);
            } else {
                return bVal.localeCompare(aVal);
            }
        });
        
        // Re-render rows with new order
        rows.forEach(row => {
            tbody.appendChild(row);
        });
    }

    function hideMapError() {
        const errorContainer = document.querySelector('.map-error-container');
        if (errorContainer) {
            errorContainer.style.display = 'none';
        }
    }

    // Load single wilayah
    async function loadWilayahMap() {
        // Check period first (Tahun, Bulan, Minggu WAJIB)
        const periodOk = await checkPeriodAndLoadData();
        if (!periodOk) return;
        
        const wilayahNumber = document.getElementById('wilayahSelect').value;
        
        // Wilayah OPSIONAL - jika tidak dipilih, tampilkan semua wilayah
        if (!wilayahNumber) {
            console.log('⚠️  Wilayah tidak dipilih, menampilkan Semua Wilayah');
            // Load all wilayah instead
            await loadAllWilayah();
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

        // Show loading spinner
        console.log(`Loading Wilayah ${wilayahNumber}...`);
        showMapLoading(true);
        const startTime = performance.now();

        const cacheBust = new Date().getTime();
        
        // Load GeoJSON (for map display), stats (for summary), and records (for table)
        const geojsonPromise = fetch(`/api/wilayah/geojson/${wilayahNumber}?_t=${cacheBust}`, {
            headers: {
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            }
        }).then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        });
        
        const statsPromise = fetch(`/api/wilayah/stats/${wilayahNumber}?_t=${cacheBust}`, {
            headers: {
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            }
        }).then(response => {
            if (!response.ok) return null;
            return response.json();
        });
        
        const recordsPromise = fetch(`/api/wilayah/records/${wilayahNumber}?_t=${cacheBust}`, {
            headers: {
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            }
        }).then(response => {
            if (!response.ok) return null;
            return response.json();
        });
        
        Promise.all([geojsonPromise, statsPromise, recordsPromise])
            .then(([data, stats, records]) => {
                const response = data;
                const loadTime = (performance.now() - startTime).toFixed(2);
                console.log(`✅ Wilayah ${wilayahNumber} loaded in ${loadTime}ms`);
                showMapLoading(false);
                
                if (response.error) {
                    // Check if error is about unpublished data
                    if (response.error.includes('belum dipublikasikan')) {
                        alert('⚠️ Peta belum tersedia.\n\nData peta belum dipublikasikan oleh administrator. Silakan hubungi admin untuk memperbarui peta.');
                        return;
                    }
                    throw new Error(response.error);
                }

                if (!response.features || response.features.length === 0) {
                    alert('Tidak ada data untuk wilayah ini');
                    return;
                }

                // Add GeoJSON layer with styling
                const layer = L.geoJSON(response, {
                    style: function(feature) {
                        return getFeatureStyle(feature);
                    },
                    onEachFeature: function(feature, layer) {
                        if (feature.properties) {
                            layer.bindPopup(createPopupContent(feature.properties), {
                                maxWidth: 300,
                                minWidth: 150,
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

                console.log(`Wilayah ${wilayahNumber} loaded: ${response.features.length} features (${stats ? stats.bersih_count + ' bersih, ' + stats.ringan_count + ' ringan' : 'stats pending'})`);
                
                // Use database records for location table (not GeoJSON features)
                // This ensures ALL records are shown, not just those that match GeoJSON
                if (records && records.features) {
                    populateLocationTable(records.features, wilayahNumber);
                } else {
                    populateLocationTable(response.features, wilayahNumber);
                }
            })
            .catch(error => {
                showMapLoading(false);
                console.error('Error loading wilayah:', error);
                alert('Gagal memuat data wilayah: ' + error.message);
            });
    }

    // Show/hide loading indicator
    function showMapLoading(show) {
        let loader = document.getElementById('mapLoader');
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'mapLoader';
            loader.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
                z-index: 10000;
                display: none;
                text-align: center;
                min-width: 200px;
            `;
            loader.innerHTML = `
                <div style="margin-bottom: 15px;">
                    <div class="loading" style="margin: 0 auto;"></div>
                </div>
                <p style="margin: 0; color: #128241; font-weight: 600; font-size: 14px;">Memuat Peta...</p>
            `;
            document.body.appendChild(loader);
        }
        loader.style.display = show ? 'block' : 'none';
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

        // Show loading spinner
        console.log('Loading all wilayah...');
        showMapLoading(true);
        const startTime = performance.now();

        try {
            // Get list of all wilayah from API
            console.log('🔍 [wilayah.blade] loadAllWilayah() dimulai...');
            const summaryResponse = await fetch('/api/wilayah/data');
            console.log('🌐 [wilayah.blade] API /wilayah/data response status:', summaryResponse.status);
            console.log('🌐 [wilayah.blade] Response headers:', {
                'content-type': summaryResponse.headers.get('content-type'),
                'content-length': summaryResponse.headers.get('content-length')
            });
            
            if (!summaryResponse.ok) {
                throw new Error(`API /wilayah/data returned status ${summaryResponse.status}`);
            }
            
            const summary = await summaryResponse.json();
            console.log('📊 [wilayah.blade] API /wilayah/data response:', summary);
            
            if (summary.error) {
                throw new Error('API error: ' + summary.error);
            }
            
            if (!summary.data || summary.data.length === 0) {
                console.warn('⚠️  [wilayah.blade] No wilayah data available');
                console.warn('⚠️  Response object:', summary);
                showMapLoading(false);
                showMapError('Tidak ada data wilayah yang tersedia. Periksa console untuk detail.');
                return;
            }
            
            const wilayahNumbers = summary.data.map(w => w.wilayah);
            console.log('📍 [wilayah.blade] Will load wilayah:', wilayahNumbers);
            
            // Load each wilayah
            const promises = wilayahNumbers.map(num => {
                console.log(`🌍 [wilayah.blade] Fetching wilayah ${num}...`);
                const cacheBust = new Date().getTime();
                return fetch(`/api/wilayah/geojson/${num}?_t=${cacheBust}`, {
                    headers: {
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    }
                })
                    .then(r => {
                        console.log(`📥 [wilayah.blade] Wilayah ${num} response: status ${r.status}`);
                        if (!r.ok) {
                            console.error(`Failed to fetch wilayah ${num}: status ${r.status}`);
                            return null;
                        }
                        return r.json();
                    })
                    .then(data => {
                        if (data) {
                            console.log(`✅ [wilayah.blade] Wilayah ${num}: ${data.features?.length || 0} features loaded`);
                            const withKategori = data.features?.filter(f => f.properties?.kategori || f.properties?.Kelas_weed).length || 0;
                            console.log(`   └─ Features with kategori/status: ${withKategori}`);
                        }
                        return data ? ({ wilayah: num, data }) : null;
                    })
                    .catch(err => {
                        console.error(`Error loading wilayah ${num}:`, err);
                        return null;
                    });
            });

            const results = await Promise.all(promises);
            const validResults = results.filter(r => r !== null);
            const allBounds = [];

            validResults.forEach(({ wilayah, data }) => {
                if (data.features && data.features.length > 0) {
                    // Filter features by status if filter is active
                    let features = data.features;
                    if (currentStatusFilter) {
                        features = features.filter(f => {
                            const kategori = f.properties.kategori || f.properties.Kelas_weed || f.properties.gulma_KATEGORI || f.properties.Status || '';
                            const status = kategori.toLowerCase();
                            
                            if (currentStatusFilter === 'belum_dimonitoring') {
                                // Filter untuk Tidak Ada Data
                                // Return true jika status kosong ATAU tidak termasuk salah satu dari 4 kategori
                                const hasValidStatus = status.includes('bersih') || status.includes('ringan') || status.includes('sedang') || status.includes('berat');
                                return !hasValidStatus; // Show if NO valid status
                            }
                            
                            // Untuk filter lainnya (bersih, ringan, sedang, berat)
                            return status !== '' && status.includes(currentStatusFilter);
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
                                    maxWidth: 300,
                                    minWidth: 150,
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
            } else if (currentStatusFilter) {
                // Filter active but no results found
                console.warn(`⚠️ Filter '${currentStatusFilter}' returned no results`);
                // Keep map at current view, let user see empty map clearly
            } else {
                // No filter, but somehow no bounds - fit to Indonesia view
                console.warn('⚠️ No bounds found, fitting to default view');
                map.fitBounds([[-10.3, 95.3], [5.9, 141.0]], { padding: [80, 80] });
            }

            console.log(`Loaded ${validResults.length} wilayah successfully`);
            
            // Hide loading spinner
            const loadTime = (performance.now() - startTime).toFixed(2);
            console.log(`✅ All wilayah loaded in ${loadTime}ms`);
            showMapLoading(false);
            
            // Populate location table with ALL data (unfiltered)
            // This ensures allLocationData is always populated with complete dataset
            const allFeatures = validResults.flatMap(r => {
                if (!r.data.features) return [];
                return r.data.features;
            });
            populateLocationTable(allFeatures);
            
            // Apply status filter to TABLE if active
            // This filters the displayed rows in the table without affecting map layers
            if (currentStatusFilter) {
                filterLocationsByStatus(currentStatusFilter);
            }
        } catch (error) {
            showMapLoading(false);
            console.error('❌ Error loading all wilayah:', error);
            console.error('Error stack:', error.stack);
            const errorMsg = error.message || 'Gagal memuat data wilayah';
            showMapError(`Error: ${errorMsg}`);
        }
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
        let borderColor = '#c4c4c4'; // default hitam untuk tidak ada data
        let weight = 2;
        
        // Check multiple status fields: kategori (from API), Kelas_weed, gulma_KATEGORI, atau Status
        const status = (props.kategori || props.Kelas_weed || props.gulma_KATEGORI || props.Status || '').toLowerCase();
        
        if (status) {
            if (status.includes('bersih')) {
                fillColor = '#3498db'; // Biru
                borderColor = '#3498db';
            } else if (status.includes('ringan')) {
                fillColor = '#57ce39ff'; // Hijau
                borderColor = '#57ce39ff';
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
        let statusGulma = props.kategori || props.Kelas_weed || props.gulma_KATEGORI || props.Status || '';
        const lokasi = props.Lokasi || props.LOKASI || props.seksi || props.id_feature || 'N/A';
        
        // Normalize status - check if it's a valid category
        if (!statusGulma || statusGulma === 'belum_dimonitoring' || statusGulma.trim() === '') {
            statusGulma = 'Tidak Ada Data';
        } else {
            // Check if status is one of the 4 valid categories
            const statusLower = statusGulma.toLowerCase();
            const isValidStatus = statusLower.includes('bersih') || statusLower.includes('ringan') || 
                                 statusLower.includes('sedang') || statusLower.includes('berat');
            if (!isValidStatus) {
                // Invalid status (like "Nanas", etc.) - convert to "Tidak Ada Data"
                statusGulma = 'Tidak Ada Data';
            }
        }
        
        // Get color for status
        let statusColor = '#9ca3af';
        if (statusGulma === 'Tidak Ada Data') {
            statusColor = '#95a5a6';
        } else if (statusGulma.toLowerCase().includes('bersih')) {
            statusColor = '#3498db';
        } else if (statusGulma.toLowerCase().includes('ringan')) {
            statusColor = '#57ce39ff';
        } else if (statusGulma.toLowerCase().includes('sedang')) {
            statusColor = '#f1c40f';
        } else if (statusGulma.toLowerCase().includes('berat')) {
            statusColor = '#e74c3c';
        }
        
        return `<div style="font-family: 'Poppins'; font-size: 13px; padding: 5px;">
            📍 Kode Lokasi:<strong> ${lokasi} </strong><br>
            Status Gulma: <span style="color: ${statusColor}; font-weight: bold;"> <strong> ${statusGulma}</strong></span>
        </div>`;
    }

    function createPopupContent(props) {
        let html = '<div style="width: 250px; padding: 0;">';
        
        // Foto - mulai dengan placeholder langsung, fetch foto asli di event listener
        let statusGulma = props.kategori || 'belum_dimonitoring';
        html += '<div style="width: 100%; height: 140px; border-radius: 6px 6px 0 0; overflow: hidden; margin-bottom: 0; background: #f0f0f0;">';
        html += `<img src="/image/foto.jpg" data-kategori="${statusGulma}" alt="Foto Lokasi" style="width: 100%; height: 100%; object-fit: cover; display: block;">`;
        html += '</div>';
        
        html += '<div style="padding: 10px;">';
        
        // Header dengan Lokasi dan Wilayah (dari CSV atau GeoJSON)
        const lokasi = props.Lokasi || props.LOKASI || props.lokasi || 'N/A';
        const wilayah = props.Wilayah || props.gulma_Wilayah || props.wilayah || 'N/A';
        html += `<div style="margin-bottom: 8px;">`;
        html += `<p style="margin: 0; color: #128241; font-size: 16px; font-weight: 700;">📍 wil. ${wilayah} - ${lokasi}</p>`;
        html += `</div>`;

        // Normalize status display - check if it's a valid category
        let displayStatus;
        if (!statusGulma || statusGulma === 'belum_dimonitoring' || statusGulma.trim() === '') {
            displayStatus = 'Tidak Ada Data';
        } else {
            const statusLower = statusGulma.toLowerCase();
            const isValidStatus = statusLower.includes('bersih') || statusLower.includes('ringan') || 
                                 statusLower.includes('sedang') || statusLower.includes('berat');
            displayStatus = isValidStatus ? statusGulma : 'Tidak Ada Data';
        }
        
        let statusColor = '#ecf0f1';
        let textColor = '#333333';
        
        if (displayStatus === 'Tidak Ada Data') {
            statusColor = '#ecf0f1';
            textColor = '#666666';
        } else if (displayStatus.toLowerCase().includes('bersih')) {
            statusColor = '#3498db';
            textColor = 'white';
        } else if (displayStatus.toLowerCase().includes('ringan')) {
            statusColor = '#57ce39ff';
            textColor = 'white';
        } else if (displayStatus.toLowerCase().includes('sedang')) {
            statusColor = '#f1c40f';
            //textColor = 'white';
        } else if (displayStatus.toLowerCase().includes('berat')) {
            statusColor = '#e74c3c'; 
            textColor = 'white';
        }
        
        html += `<div style="background: ${statusColor}; color: ${textColor}; padding: 6px; margin-bottom: 8px; border-radius: 4px; text-align: center; font-weight: 600; font-size: 11px;">`;
        html += `${displayStatus}`;
        html += `</div>`;

        // Data information - dari CSV dan GeoJSON
        html += '<div style="background: #f9f9f9; padding: 8px; border-radius: 4px;">';
        html += '<div style="font-size: 10px; line-height: 1.6;">';
        
        // Butuh TK - dari CSV tk_ha atau tk/ha
        const tkHa = props.tk_ha || props.tkha || props['tk/ha'] || null;
        const tkValue = tkHa ? (typeof tkHa === 'string' ? tkHa.replace(/\.00$/, '').trim() : tkHa) : '<span style="color: #999; font-style: italic;">null</span>';
        const tkDisplay = tkHa ? `${tkValue} TK/Ha` : `${tkValue}`;
        html += `<div style="display: flex; justify-content: space-between; border-bottom: 1px solid #e0e0e0; padding: 4px 0;"><span style="color: #555;"><strong>Butuh Tenaga Kerja:</strong></span><span style="color: #128241; font-weight: 600; font-size: 13px;">${tkDisplay}</span></div>`;

        // Aktivitas - dari CSV activitas (note: column name is "activitas" with 'c', not 'k')
        const aktivitas = props.activitas || props.Aktivitas || props.activity || props.Activity || props.aktivitas || null;
        const aktivitasDisplay = aktivitas ? aktivitas : '<span style="color: #999; font-style: italic;">null</span>';
        html += `<div style="display: flex; justify-content: space-between; border-bottom: 1px solid #e0e0e0; padding: 4px 0;"><span style="color: #555;"><strong>Aktivitas:</strong></span><span style="color: #128241; font-weight: 600;">${aktivitasDisplay}</span></div>`;

        // Luas Netto Gulma - dari CSV neto
        const neto = props.neto || props.Netto || props.netto || props.Luas_Netto || props.luas_netto || null;
        const netoValue = neto ? (typeof neto === 'string' ? neto.replace(',', '.') : neto) : '<span style="color: #999; font-style: italic;">null</span>';
        const netoDisplay = neto ? `${netoValue} Ha` : `${netoValue}`;
        html += `<div style="display: flex; justify-content: space-between; border-bottom: 1px solid #e0e0e0; padding: 4px 0;"><span style="color: #555;"><strong>Luas Netto:</strong></span><span style="color: #128241; font-weight: 600;">${netoDisplay}</span></div>`;

        // Luas Bruto Lokasi - HANYA dari GeoJSON
        const bruto = props.Luas_Bruto || props.luas_bruto || props.Bruto || props.bruto || null;
        const brutoValue = bruto ? (typeof bruto === 'string' ? bruto.replace(',', '.') : bruto) : '<span style="color: #999; font-style: italic;">null</span>';
        const brutoDisplay = bruto ? `${brutoValue} Ha` : `${brutoValue}`;
        html += `<div style="display: flex; justify-content: space-between; padding: 4px 0;"><span style="color: #555;"><strong>Luas Bruto Lokasi:</strong></span><span style="color: #128241; font-weight: 600;">${brutoDisplay}</span></div>`;

        html += '</div>';
        html += '</div>';

        return html;
    }

    // ===== helper =====
    function normalizeKategori(raw) {
        if (!raw) return null;
        raw = raw.toLowerCase();
        if (raw.includes('bersih')) return 'bersih';
        if (raw.includes('ringan')) return 'ringan';
        if (raw.includes('sedang')) return 'sedang';
        if (raw.includes('berat')) return 'berat';
        return null;
    }



    // Load wilayah data and populate select
    function loadWilayahDataAndStats() {
        fetch('/api/wilayah/data')
            .then(response => response.json())
            .then(data => {
                // Enrich wilayah data with additional statistics
                const enrichedData = data.data.map(wilayah => {
                    return {
                        ...wilayah,
                        total_luas_netto: 0,
                        total_tk: 0,
                        status_counts: {
                            bersih: 0,
                            ringan: 0,
                            sedang: 0,
                            berat: 0,
                            belum_dimonitoring: 0
                        }
                    };
                });

                // We need to fetch all data to calculate statistics
                // For now, store the data and fetch details later
                allWilayahData = enrichedData;
                wilayahData = enrichedData;

                // Populate wilayah button grid (for filter)
                const wilayahButtonGrid = document.getElementById('wilayahButtonGrid');
                wilayahButtonGrid.innerHTML = '';
                
                enrichedData.forEach(wilayah => {
                    const btn = document.createElement('button');
                    btn.className = 'grid-btn';
                    btn.setAttribute('data-value', wilayah.wilayah);
                    btn.textContent = `Wil. ${wilayah.wilayah}`;
                    btn.title = `Wilayah ${wilayah.wilayah}`;
                    btn.onclick = () => selectGridOption('wilayah', wilayah.wilayah, `Wilayah ${wilayah.wilayah}`);
                    wilayahButtonGrid.appendChild(btn);
                });

                // Update statistics
                document.getElementById('totalWilayah').textContent = data.total_wilayah + ' Wilayah';
                let totalArea = 0;

                enrichedData.forEach(w => {
                    totalArea += w.total_area;
                });

                document.getElementById('totalArea').textContent = totalArea.toFixed(2) + ' Ha';

                // Fetch all wilayah data: both GeoJSON (for map) and stats (for accurate counts)
                const promises = enrichedData.map(wilayah => {
                    const cacheBust = new Date().getTime();
                    
                    // Fetch both GeoJSON and stats in parallel
                    const geojsonPromise = fetch(`/api/wilayah/geojson/${wilayah.wilayah}?_t=${cacheBust}`, {
                        headers: {
                            'Cache-Control': 'no-cache, no-store, must-revalidate',
                            'Pragma': 'no-cache',
                            'Expires': '0'
                        }
                    }).then(r => r.ok ? r.json() : null);
                    
                    const statsPromise = fetch(`/api/wilayah/stats/${wilayah.wilayah}?_t=${cacheBust}`, {
                        headers: {
                            'Cache-Control': 'no-cache, no-store, must-revalidate',
                            'Pragma': 'no-cache',
                            'Expires': '0'
                        }
                    }).then(r => r.ok ? r.json() : null);
                    
                    return Promise.all([geojsonPromise, statsPromise])
                        .then(([geojson, stats]) => {
                            if (!geojson || !geojson.features) return wilayah;
                            
                            // Use stats endpoint for accurate counts
                            if (stats && stats.bersih_count !== undefined) {
                                const statusCounts = {
                                    bersih: stats.bersih_count || 0,
                                    ringan: stats.ringan_count || 0,
                                    sedang: stats.sedang_count || 0,
                                    berat: stats.berat_count || 0,
                                    belum_dimonitoring: 0 // will be calculated if needed
                                };
                                
                                return {
                                    ...wilayah,
                                    total_luas_netto: stats.total_neto || 0,
                                    total_tk: stats.total_tk || 0,
                                    status_counts: statusCounts
                                };
                            }
                            
                            // Fallback: Calculate totals from features (for backward compatibility)
                            let totalLuasNetto = 0;
                            let totalTk = 0;
                            const statusCounts = {
                                bersih: 0,
                                ringan: 0,
                                sedang: 0,
                                berat: 0,
                                belum_dimonitoring: 0
                            };

                            geojson.features.forEach(feature => {
                                const props = feature.properties;
                                
                                // Sum Luas Netto (from CSV neto field)
                                const neto = props.neto || props.Netto || props.netto || props.Luas_Netto || 0;
                                totalLuasNetto += parseFloat(neto) || 0;

                                // Sum TK (from CSV tk_ha field)
                                const tkHa = props.tk_ha || props.tkha || props['tk/ha'] || 0;
                                totalTk += parseFloat(tkHa) || 0;

                                // Count status
                                const status = (props.kategori || props.Kelas_weed || props.gulma_KATEGORI || props.Status || '').toLowerCase();
                                if (!status || (!status.includes('bersih') && !status.includes('ringan') && !status.includes('sedang') && !status.includes('berat'))) {
                                    statusCounts.belum_dimonitoring++;
                                } else if (status.includes('bersih')) {
                                    statusCounts.bersih++;
                                } else if (status.includes('ringan')) {
                                    statusCounts.ringan++;
                                } else if (status.includes('sedang')) {
                                    statusCounts.sedang++;
                                } else if (status.includes('berat')) {
                                    statusCounts.berat++;
                                }
                            });

                            // DEBUG: Log data untuk wilayah 16
                            if (wilayah.wilayah == 16) {
                                console.log('🔍 [DEBUG WILAYAH 16 - GeoJSON Only]', {
                                    total_features: geojson.features.length,
                                    with_kategori: geojson.features.filter(f => f.properties.kategori).length,
                                    totalTk,
                                    totalLuasNetto,
                                    statusCounts,
                                    stats_endpoint_available: !!stats,
                                    stats: stats
                                });
                            }

                            return {
                                ...wilayah,
                                total_luas_netto: totalLuasNetto,
                                total_tk: totalTk,
                                status_counts: statusCounts
                            };
                        })
                        .catch(err => {
                            console.error(`Error fetching wilayah ${wilayah.wilayah}:`, err);
                            return wilayah;
                        });
                });

                Promise.all(promises).then(enrichedWilayahData => {
                    wilayahData = enrichedWilayahData;
                    renderWilayah(enrichedWilayahData);
                    console.log('Wilayah data loaded with stats:', enrichedWilayahData);
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

            // Create status grid HTML
            const statusColors = {
                bersih: '#3498db',
                ringan: '#57ce39',
                sedang: '#f1c40f',
                berat: '#e74c3c',
                belum_dimonitoring: '#ecf0f1'
            };
            
            const statusNames = {
                bersih: 'Bersih',
                ringan: 'Ringan',
                sedang: 'Sedang',
                berat: 'Berat',
                belum_dimonitoring: 'Tidak Ada Data'
            };

            const statusCounts = wilayah.status_counts || {
                bersih: 0,
                ringan: 0,
                sedang: 0,
                berat: 0,
                belum_dimonitoring: 0
            };

            let statusGridHTML = '<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-top: 10px;">';
            
            // First 4 statuses (bersih, ringan, sedang, berat)
            ['bersih', 'ringan', 'sedang', 'berat'].forEach(status => {
                const count = statusCounts[status];
                const color = statusColors[status];
                statusGridHTML += `
                    <div style="text-align: center; font-size: 11px; font-weight: 700;">
                        <div style="font-size: 13px; font-weight: 700; color: ${color}; line-height: 1;">${count}</div>
                        <div style="font-size: 10px; color: ${color}; line-height: 1;">${statusNames[status]}</div>
                    </div>
                `;
            });
            statusGridHTML += '</div>';
            
            // Tidak Ada Data on separate row
            // const belumCount = statusCounts['belum_dimonitoring'];
            // statusGridHTML += `<div style="display: grid; grid-template-columns: 1fr; gap: 8px; margin-top: 8px;">
            //     <div style="padding: 8px; border-radius: 4px; text-align: center; font-size: 11px; font-weight: 600; color: #999;">
            //         Tidak Ada Data : ${belumCount}
            //     </div>
            // </div>`;

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
                            <span>Luas Netto Wilayah</span>
                        </span>
                        <span class="info-value">
                            ${(wilayah.total_luas_netto ? parseFloat(wilayah.total_luas_netto).toFixed(2) : '0.00')} Ha
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-users"></i>
                            <span>Total Kebutuhan Tenaga Kerja</span>
                        </span>
                        <span class="info-value">
                            ${(wilayah.total_tk ? Math.round(parseFloat(wilayah.total_tk)) : '0')} TK
                        </span>
                    </div>
                    <div>
                        <span style="font-size: 11px; font-weight: 600; color: #555;">Status Gulma</span>
                        ${statusGridHTML}
                    </div>
                    <div style="margin-top: 10px; padding-top: 10px; text-align: center; display: flex; gap: 8px; justify-content: center;">
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
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px; color: #999;">Tidak ada data lokasi</td></tr>';
            return;
        }
        
        features.forEach((feature, index) => {
            const props = feature.properties;
            const lokasi = props.Lokasi || props.LOKASI || props.seksi || props.id_feature || 'N/A';
            const status = (props.kategori || props.Kelas_weed || props.gulma_KATEGORI || props.Status || 'Tidak Ada Data').toLowerCase();
            const wilayah = wilayahNumber || props.Wilayah || props.gulma_Wilayah || '-';
            const luasNetto = props.neto || props.Netto || props.netto || props.Luas_Netto || '-';
            const tkRaw = props.tk_ha || props.tkha || props['tk/ha'] || '-';
            const tkHa = tkRaw === '-' ? '-' : (typeof tkRaw === 'string' ? tkRaw.replace(/\.00$/, '').trim() : tkRaw);
            const aktivitas = props.activitas || props.Aktivitas || props.activity || props.Activity || '-';
            
            // Store for filtering
            allLocationData.push({
                wilayah,
                lokasi,
                status,
                luasNetto,
                tkHa,
                aktivitas,
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
                <td><strong>${tkHa === '-' ? '<span style="color: #999; font-style: italic;">null</span>' : tkHa + ' TK'}</strong></td>
                <td>${aktivitas === '-' ? '<span style="color: #999; font-style: italic;">null</span>' : aktivitas}</td>
                <td>${luasNetto === '-' ? '<span style="color: #999; font-style: italic;">null</span>' : luasNetto + ' Ha'}</td>
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
                displayStatus = 'Tidak Ada Data';
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
                // Filter untuk Tidak Ada Data - yang tidak memiliki status valid
                filteredData = allLocationData.filter(loc => {
                    const status = (loc.status || '').toLowerCase();
                    const hasValidStatus = status.includes('bersih') || status.includes('ringan') || status.includes('sedang') || status.includes('berat');
                    return !hasValidStatus;
                });
            } else {
                // Filter untuk status tertentu (bersih, ringan, sedang, berat)
                filteredData = allLocationData.filter(loc => {
                    const status = (loc.status || '').toLowerCase();
                    return status !== '' && status.includes(statusFilter);
                });
            }
        } else {
            filteredData = allLocationData;
        }
        
        if (filteredData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px; color: #999;">Tidak ada data dengan status ini</td></tr>';
            return;
        }
        
        filteredData.forEach((loc, index) => {
            const status = (loc.status || '').toLowerCase();
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
                <td>${loc.wilayah}</td>
                <td><strong>${loc.lokasi}</strong></td>
                <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                <td><strong>${loc.tkHa === '-' ? '<span style="color: #999; font-style: italic;">null</span>' : loc.tkHa + ' TK'}</strong></td>
                <td>${loc.aktivitas === '-' ? '<span style="color: #999; font-style: italic;">null</span>' : loc.aktivitas}</td>
                <td>${loc.luasNetto === '-' ? '<span style="color: #999; font-style: italic;">null</span>' : loc.luasNetto + ' Ha'}</td>
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

    // Update period info display with smart messaging
    function updatePeriodInfoDisplay(period, isLatest = false) {
        const bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const bulanName = bulanNames[parseInt(period.bulan)];
        
        let statusIcon = '⭐';
        let statusMsg = 'Data Terpilih';
        
        if (isLatest) {
            statusIcon = '⭐';
            statusMsg = 'Data Terbaru (Published)';
        }
        
        // Also show count of records available for this period (if we can)
        let countText = '';
        const matchingPeriods = availablePeriods.periods.filter(p => 
            p.tahun == period.tahun && p.bulan == period.bulan && p.minggu == period.minggu
        );
        
        if (matchingPeriods.length > 0) {
            countText = ` • ${matchingPeriods[0].total_records || ''} records tersedia`;
        }
        
        document.getElementById('periodInfoText').innerHTML = 
            `${statusIcon} ${statusMsg} - <strong>Tahun ${period.tahun}, ${bulanName}, Minggu ke-${period.minggu}</strong>${countText}`;
    }

    // Store available periods data for smart filtering
    let availablePeriods = {
        periods: [],
        tahun_list: [],
        latest_period: null
    };

    // Helper: Get available bulan for selected tahun
    function getAvailableBulanForTahun(tahun) {
        const bulanSet = new Set();
        availablePeriods.periods.forEach(period => {
            if (period.tahun == tahun) {
                bulanSet.add(parseInt(period.bulan));
            }
        });
        return Array.from(bulanSet).sort((a, b) => b - a);
    }

    // Helper: Get available minggu for selected tahun & bulan
    function getAvailableMingguForTahunBulan(tahun, bulan) {
        const mingguSet = new Set();
        availablePeriods.periods.forEach(period => {
            if (period.tahun == tahun && period.bulan == bulan) {
                mingguSet.add(parseInt(period.minggu));
            }
        });
        return Array.from(mingguSet).sort((a, b) => b - a);
    }

    // Update bulan dropdown based on selected tahun
    function updateBulanDropdown(tahun) {
        const bulanGrid = document.getElementById('bulanGrid');
        const availableBulan = getAvailableBulanForTahun(tahun);
        const bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        // Mark buttons based on availability
        const buttons = bulanGrid.querySelectorAll('.grid-btn');
        buttons.forEach(btn => {
            const bulanValue = parseInt(btn.getAttribute('data-value'));
            if (availableBulan.includes(bulanValue)) {
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
                btn.disabled = false;
                btn.title = `${bulanNames[bulanValue]} tersedia`;
            } else {
                btn.style.opacity = '0.4';
                btn.style.cursor = 'not-allowed';
                btn.disabled = true;
                btn.title = `${bulanNames[bulanValue]} tidak ada data`;
            }
        });
        
        console.log(`📊 Bulan tersedia untuk tahun ${tahun}:`, availableBulan);
    }

    // Update minggu dropdown based on selected tahun & bulan
    function updateMingguDropdown(tahun, bulan) {
        const mingguGrid = document.getElementById('mingguGrid');
        const availableMinggu = getAvailableMingguForTahunBulan(tahun, bulan);
        
        // Mark buttons based on availability
        const buttons = mingguGrid.querySelectorAll('.grid-btn');
        buttons.forEach(btn => {
            const mingguValue = parseInt(btn.getAttribute('data-value'));
            if (availableMinggu.includes(mingguValue)) {
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
                btn.disabled = false;
                btn.title = `Minggu ke-${mingguValue} tersedia`;
            } else {
                btn.style.opacity = '0.4';
                btn.style.cursor = 'not-allowed';
                btn.disabled = true;
                btn.title = `Minggu ke-${mingguValue} tidak ada data`;
            }
        });
        
        console.log(`📊 Minggu tersedia untuk ${tahun}/${bulan}:`, availableMinggu);
    }

    // Modified selectGridOption to handle period changes
    const originalSelectGridOption = window.selectGridOption;
    window.selectGridOption = function(type, value, label) {
        originalSelectGridOption(type, value, label);
        
        // Update dependent dropdowns
        if (type === 'tahun') {
            // Reset bulan & minggu selections
            document.getElementById('bulanSelect').value = '';
            document.getElementById('mingguSelect').value = '';
            document.getElementById('bulanSelected').textContent = 'Pilih Bulan...';
            document.getElementById('mingguSelected').textContent = 'Pilih Minggu ke-...';
            
            // Update bulan options
            updateBulanDropdown(value);
            
            // Close bulan grid
            toggleButtonGrid('bulan');
            
            console.log(`✅ Tahun dipilih: ${value}`);
        } else if (type === 'bulan') {
            const tahun = document.getElementById('tahunSelect').value;
            
            // Reset minggu selection
            document.getElementById('mingguSelect').value = '';
            document.getElementById('mingguSelected').textContent = 'Pilih Minggu ke-...';
            
            // Update minggu options
            updateMingguDropdown(tahun, value);
            
            // Close minggu grid
            toggleButtonGrid('minggu');
            
            console.log(`✅ Bulan dipilih: ${value}, tahun: ${tahun}`);
        } else if (type === 'minggu') {
            // Close minggu grid
            toggleButtonGrid('minggu');
            
            console.log(`✅ Minggu dipilih: ${value}`);
        }
    };

    // Load available periods (tahun, bulan, minggu) dengan SMART FILTERING
    async function loadAvailablePeriods() {
        try {
            // PENTING: Load latest published data, bukan latest upload
            console.log('📥 [WILAYAH PUBLIC] Loading available publication periods...');
            
            const response = await fetch('/api/wilayah/periods');
            const data = await response.json();
            
            if (data.success) {
                console.log('✅ Loaded periods:', data.periods.length, 'unique periods');
                
                // Store periods data for smart filtering
                availablePeriods = {
                    periods: data.periods,
                    tahun_list: data.tahun_list,
                    latest_period: data.latest_period
                };
                
                // Populate tahun button grid with ALL years
                const tahunGrid = document.getElementById('tahunGrid');
                tahunGrid.innerHTML = '';
                
                data.tahun_list.forEach(tahunItem => {
                    const btn = document.createElement('button');
                    btn.className = 'grid-btn';
                    btn.setAttribute('data-value', tahunItem);
                    btn.textContent = tahunItem;
                    btn.title = `Data tersedia untuk tahun ${tahunItem}`;
                    btn.onclick = () => selectGridOption('tahun', tahunItem, tahunItem);
                    tahunGrid.appendChild(btn);
                });
                
                console.log(`📅 Tahun buttons populated: ${data.tahun_list.join(', ')}`);
                
                // Set default to latest period if available
                if (data.latest_period) {
                    const tahun = data.latest_period.tahun;
                    const bulan = data.latest_period.bulan;
                    const minggu = data.latest_period.minggu;
                    
                    console.log('🎯 Setting default to latest period:', {tahun, bulan, minggu});
                    
                    // Set selectors
                    document.getElementById('tahunSelect').value = tahun;
                    document.getElementById('bulanSelect').value = bulan;
                    document.getElementById('mingguSelect').value = minggu;
                    
                    // Update display
                    document.getElementById('tahunSelected').textContent = tahun;
                    const bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    document.getElementById('bulanSelected').textContent = bulanNames[parseInt(bulan)];
                    document.getElementById('mingguSelected').textContent = 'Minggu ke-' + minggu;
                    
                    // Mark selected buttons
                    tahunGrid.querySelector(`[data-value="${tahun}"]`)?.classList.add('selected');
                    document.getElementById('bulanGrid').querySelector(`[data-value="${bulan}"]`)?.classList.add('selected');
                    document.getElementById('mingguGrid').querySelector(`[data-value="${minggu}"]`)?.classList.add('selected');
                    
                    // Initialize dependent dropdowns
                    updateBulanDropdown(tahun);
                    updateMingguDropdown(tahun, bulan);
                    
                    currentPeriod = data.latest_period;
                    latestPeriod = data.latest_period;
                    
                    updatePeriodInfoDisplay(latestPeriod);
                    
                    console.log('📍 Period defaults set to latest:', data.latest_period);
                } else {
                    console.warn('⚠️  No latest period available, user will need to select manually');
                }
            } else {
                console.error('❌ Failed to load periods:', data.error);
            }
            
            // Load wilayah data and stats
            loadWilayahDataAndStats();
            
            // TAMPILKAN TABEL LOKASI secara otomatis saat awal load
            document.getElementById('locationDetailsTable').classList.add('active');
            document.getElementById('toggleIcon').className = 'fas fa-times';
            document.getElementById('toggleText').textContent = 'Sembunyikan Tabel';
            
            console.log('🗺️  Auto-loading map dengan data terbaru (published)...');
            if (map) {
                loadAllWilayah();
            } else {
                console.log('Map belum ready, tunggu sebentar...');
                setTimeout(() => {
                    if (map) {
                        loadAllWilayah();
                    } else {
                        console.error('Map masih belum ready setelah delay!');
                    }
                }, 200);
            }
        } catch (error) {
            console.error('Error loading periods:', error);
            // Still try to load data even if periods fail
            loadWilayahDataAndStats();
            // CRITICAL: Auto-load map as fallback
            console.warn('⚠️  Periods gagal, auto-loading map anyway...');
            setTimeout(() => {
                if (map) {
                    loadAllWilayah();
                } else {
                    initMap();
                    setTimeout(() => loadAllWilayah(), 200);
                }
            }, 300);
        }
    }

    // Check and load data for selected period dengan SMART VALIDATION
    async function checkPeriodAndLoadData() {
        const tahun = document.getElementById('tahunSelect').value;
        const bulan = document.getElementById('bulanSelect').value;
        const minggu = document.getElementById('mingguSelect').value;
        
        // VALIDASI: HANYA Tahun, Bulan, Minggu yang WAJIB (Wilayah OPSIONAL)
        if (!tahun || !bulan || !minggu) {
            const missing = [];
            if (!tahun) missing.push('Tahun');
            if (!bulan) missing.push('Bulan');
            if (!minggu) missing.push('Minggu');
            
            const alertMsg = `⚠️  ${missing.join(', ')} belum dipilih!\n\nSilakan pilih ${missing.join(', ')} untuk melanjutkan.\n\n💡 Catatan: Wilayah bersifat opsional. Jika tidak dipilih, peta akan menampilkan semua wilayah.`;
            alert(alertMsg);
            return false;
        }
        
        // Check if user is selecting the same as latest period
        const isLatestPeriod = latestPeriod && 
            latestPeriod.tahun == tahun && 
            latestPeriod.bulan == bulan && 
            latestPeriod.minggu == minggu;
        
        try {
            console.log(`🔍 Checking data availability for ${tahun}/${bulan}/W${minggu}...`);
            
            const response = await fetch(`/api/wilayah/data-by-period?tahun=${tahun}&bulan=${bulan}&minggu=${minggu}`);
            const data = await response.json();
            
            if (!data.data_available) {
                console.log('⚠️  Data tidak tersedia untuk periode ini, fallback ke latest');
                
                // Show notification dengan info yang jelas
                const notification = document.createElement('div');
                notification.style.cssText = `
                    position: fixed;
                    top: 80px;
                    right: 20px;
                    background: #e74c3c;
                    color: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                    z-index: 10000;
                    max-width: 500px;
                    font-size: 13px;
                    line-height: 1.5;
                `;
                notification.innerHTML = `
                    <strong style="display: block; margin-bottom: 5px;">📂 Data Tidak Ditemukan</strong>
                    ${data.message}<br>
                    <small style="opacity: 0.9;">Kami akan menampilkan data terbaru yang tersedia...</small>
                `;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 6000);
                
                // Auto-redirect to latest period
                if (data.showing_latest && data.period) {
                    console.log('🔄 Auto-redirecting to latest available period:', data.period);
                    
                    document.getElementById('tahunSelect').value = data.period.tahun;
                    document.getElementById('bulanSelect').value = data.period.bulan;
                    document.getElementById('mingguSelect').value = data.period.minggu;
                    
                    // Update display labels
                    document.getElementById('tahunSelected').textContent = data.period.tahun;
                    const bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    document.getElementById('bulanSelected').textContent = bulanNames[parseInt(data.period.bulan)];
                    document.getElementById('mingguSelected').textContent = 'Minggu ke-' + data.period.minggu;
                    
                    // Update selected buttons
                    document.getElementById('tahunGrid').querySelectorAll('.grid-btn').forEach(btn => {
                        if (btn.getAttribute('data-value') == data.period.tahun) {
                            btn.classList.add('selected');
                        } else {
                            btn.classList.remove('selected');
                        }
                    });
                    document.getElementById('bulanGrid').querySelectorAll('.grid-btn').forEach(btn => {
                        if (btn.getAttribute('data-value') == data.period.bulan) {
                            btn.classList.add('selected');
                        } else {
                            btn.classList.remove('selected');
                        }
                    });
                    document.getElementById('mingguGrid').querySelectorAll('.grid-btn').forEach(btn => {
                        if (btn.getAttribute('data-value') == data.period.minggu) {
                            btn.classList.add('selected');
                        } else {
                            btn.classList.remove('selected');
                        }
                    });
                    
                    currentPeriod = data.period;
                    updatePeriodInfoDisplay(data.period, true);
                }
            } else {
                currentPeriod = { tahun, bulan, minggu };
                // Only update display if user manually selected different period
                if (!isLatestPeriod) {
                    updatePeriodInfoDisplay(currentPeriod);
                    console.log('✅ Periode data tersedia:', currentPeriod);
                } else {
                    console.log('✅ Using latest published period');
                }
            }
            
            return true;
        } catch (error) {
            console.error('Error checking period:', error);
            
            // Show error notification
            const errorNotif = document.createElement('div');
            errorNotif.style.cssText = `
                position: fixed;
                top: 80px;
                right: 20px;
                background: #c0392b;
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                z-index: 10000;
                max-width: 400px;
            `;
            errorNotif.innerHTML = `
                <strong>❌ Error Validasi</strong><br>
                <small style="opacity: 0.9;">Lanjutkan dengan periode yang dipilih.</small>
            `;
            document.body.appendChild(errorNotif);
            
            setTimeout(() => {
                errorNotif.remove();
            }, 5000);
            
            return true; // Continue anyway
        }
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing GulmaTrack Wilayah Map...');
        
        // Ensure Leaflet is loaded
        if (typeof L === 'undefined') {
            console.error('⚠️  Leaflet library not yet loaded, waiting...');
            // Wait for Leaflet to load
            let retries = 0;
            const checkLeaflet = setInterval(() => {
                if (typeof L !== 'undefined') {
                    clearInterval(checkLeaflet);
                    initMap();
                    loadAvailablePeriods();
                    console.log('✅ Leaflet loaded, map initialized');
                } else if (retries++ > 30) {
                    clearInterval(checkLeaflet);
                    console.error('❌ Leaflet failed to load!');
                }
            }, 100);
        } else {
            initMap();
            loadAvailablePeriods(); // Load periods and then auto-load map
        }
    });
    </script>
</div>

@endsection
