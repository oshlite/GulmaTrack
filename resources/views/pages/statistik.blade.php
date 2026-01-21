@extends('layouts.app')

@section('title', 'Statistik')

@section('content')

<div class="page-header">
    <h1><i class="fas fa-chart-bar"></i> Statistik perkebunan</h1>
    <p>Analisis mendalam tentang tren produksi dan perbandingan data antar wilayah</p>
</div>

<div class="container">
    <style>
        .stats-controls {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .stats-controls input,
        .stats-controls select {
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }

        .stats-controls button {
            padding: 10px 25px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .stats-controls button:hover {
            background-color: var(--secondary-color);
        }

        .stat-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .stat-section h3 {
            color: var(--title-color);
            margin-bottom: 25px;
            font-size: 20px;
            border-bottom: 3px solid var(--title-color);
            padding-bottom: 15px;
        }

        .stat-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .stat-table th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .stat-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .stat-table tbody tr {
            transition: all 0.3s ease;
        }

        .stat-table tbody tr:hover {
            background-color: var(--light-color);
        }

        .stat-value {
            font-weight: 600;
            color: var(--primary-color);
        }

        .bar-chart {
            margin: 20px 0;
        }

        .bar-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .bar-label {
            width: 180px;
            font-weight: 600;
            color: var(--text-color);
        }

        .bar-container {
            flex: 1;
            background-color: var(--light-color);
            height: 30px;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
            margin: 0 15px;
        }

        .bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-weight: 600;
            font-size: 12px;
        }

        .bar-value {
            width: 80px;
            text-align: right;
            font-weight: 600;
            color: var(--primary-color);
        }

        .comparison-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .comparison-card {
            background: linear-gradient(135deg, var(--light-color), #fff);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .comparison-card:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow);
        }

        .comparison-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 15px;
            font-size: 15px;
        }

        .comparison-stat {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .comparison-stat:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .comparison-label {
            color: #666;
            font-size: 13px;
        }

        .comparison-value {
            font-weight: 600;
            color: var(--primary-color);
        }

        .trend-indicator {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }

        .trend-up {
            background-color: #d4edda;
            color: #155724;
        }

        .trend-down {
            background-color: #f8d7da;
            color: #721c24;
        }

        .export-btn {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .export-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        .year-comparison {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .year-item {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid var(--primary-color);
            text-align: center;
        }

        .year-item .year {
            font-size: 12px;
            color: #999;
            margin-bottom: 5px;
        }

        .year-item .value {
            font-size: 28px;
            font-weight: bold;
            color: var(--primary-color);
        }

        .year-item .label {
            font-size: 12px;
            color: #666;
            margin-top: 8px;
        }

        .wilayah-controls {
        background: linear-gradient(135deg, #ffffff 0%, #f7faf8 100%);
        padding: 32px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(18, 130, 65, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(18, 130, 65, 0.1);
        border-left: 4px solid #128241;
        font-family: 'Poppins', sans-serif;
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
        font-family: 'Poppins', sans-serif;
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
        font-family: 'Poppins', sans-serif;
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
        justify-content: flex-end;
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
        font-family: 'Poppins', sans-serif;
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
        font-family: 'Poppins', sans-serif;
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
        font-family: 'Poppins', sans-serif;
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

    /* Action Button */
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
        font-family: 'Poppins', sans-serif;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
        transition: all 0.2s ease;
        background: white;
        border: 2px solid #d8e1dd;
        color: #FBA919;
        box-shadow: none;
    }

    .btn-secondary:hover {
        background: #FBA919;
        border-color: #FBA919;
        color: white;
        box-shadow: none;
        outline: none;
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

    <!-- Filter & Kontrol -->
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
            </div>

            <!-- Baris 2: Action Button -->
            <div class="controls-buttons-row">
                <button onclick="updateStats()" class="btn-secondary" title="Tampilkan statistik berdasarkan periode yang dipilih">
                    <i class="fas fa-chart-bar"></i> Tampilkan Statistik
                </button>
            </div>
        </div>
    </div>

    <!-- Status Info (Data Terpilih) -->
    <div id="periodInfo" style="background: #f4f6f8; padding: 12px 20px; border-radius: 8px; margin-bottom: 18px; border-left: 4px solid #128241; display: none; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="display: flex; align-items: center; font-size: 18px; color: #e67e22; flex-shrink: 0; gap: 6px;">
                <i class="fas fa-info-circle"></i>
                <span style="font-size: 18px;">⭐</span>
            </div>
            <div>
                <span id="periodInfoText" style="font-size: 14px; color: #e67e22; font-weight: 600;">
                    Data Terpilih - <strong>Memuat...</strong> • records tersedia
                </span>
            </div>
        </div>
    </div>

     <!-- Perbandingan Produksi -->
    <div class="stat-section">
        <h3><i class="fas fa-chart-bar"></i> Perbandingan Produksi Komoditas</h3>
        <div class="comparison-grid" id="comparisonGrid">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>


    <!-- Ranking Wilayah -->
    <div class="stat-section">
        <h3><i class="fas fa-trophy"></i> Ranking Wilayah Berdasarkan Gulma</h3>
        <div class="bar-chart">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>


    <!-- Tabel Data CSV Detail -->
    <div class="stat-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0;"><i class="fas fa-table"></i> Data Rekam Jelajah Detail</h3>
            <button onclick="exportStatistikToCsv()" style="padding: 10px 20px; background-color: #128241; color: white; border: none; border-radius: 6px; cursor: pointer; font-family: 'Poppins'; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#0d5e31'" onmouseout="this.style.backgroundColor='#128241'">
                <i class="fas fa-download"></i> Export CSV
            </button>
        </div>
        <div style="overflow-x: auto;">
            <table class="stat-table" id="csvDataTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>PG</th>
                        <th>FM</th>
                        <th>Wilayah</th>
                        <th>Lokasi</th>
                        <th>Neto</th>
                        <th>Hasil</th>
                        <th>Umur (bulan)</th>
                        <th>TNM STS</th>
                        <th>Aktivitas</th>
                        <th>Status Gulma</th>
                        <th>Tanggal</th>
                        <th>Total TK</th>
                        <th>TK/HA</th>
                    </tr>
                </thead>
                <tbody id="csvDataTableBody">
                    <tr>
                        <td colspan="14" style="text-align: center; padding: 40px; color: #999;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 10px;"></i><br>
                            Memuat data CSV...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Statistik Detail -->
    <div class="stat-section">
        <h3><i class="fas fa-list"></i> Tabel Statistik Detail Per Wilayah</h3>
        <table class="stat-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Wilayah</th>
                    <th>Luas Rencana Wilayah (Ha)</th>
                    <th>Total Neto</th>
                    <th>Total Tenaga Kerja (Orang)</th>
                    <th>Tahun</th>
                </tr>
            </thead>
            <tbody id="detailStatsTable">
                <!-- Will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<script>
/* ===============================
   STATISTIK PAGE - WITH SMART DYNAMIC FILTERS
================================ */

let currentPeriod = {
    tahun: null,
    bulan: null,
    minggu: null
};

let availablePeriods = {
    periods: [],
    tahun_list: [],
    latest_period: null
};

const monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

/* ===============================
   INIT
================================ */
document.addEventListener('DOMContentLoaded', async () => {
    console.log('📊 Statistik page loaded');
    await loadAvailablePeriods();
});

/* ===============================
   LOAD AVAILABLE PERIODS FROM API
================================ */
async function loadAvailablePeriods() {
    try {
        console.log('🔍 Loading available periods...');
        
        const response = await fetch('/api/wilayah/periods');
        
        if (!response.ok) {
            throw new Error(`Failed to fetch periods: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('📅 Periods API response:', data);
        
        if (data.success) {
            availablePeriods = {
                periods: data.periods || [],
                tahun_list: data.tahun_list || [],
                latest_period: data.latest_period
            };
            
            console.log('✅ Available years:', availablePeriods.tahun_list);
            console.log('✅ Latest period:', availablePeriods.latest_period);
            
            populateTahunDropdown();
            
            if (availablePeriods.latest_period) {
                const latest = availablePeriods.latest_period;
                currentPeriod = { tahun: latest.tahun, bulan: latest.bulan, minggu: latest.minggu };
                
                // Set grid button selections
                selectGridOption('tahun', latest.tahun, latest.tahun);
                selectGridOption('bulan', latest.bulan, monthNames[latest.bulan] || `Bulan ${latest.bulan}`);
                selectGridOption('minggu', latest.minggu, `Minggu ke-${latest.minggu}`);
                
                updatePeriodInfoDisplay();
                console.log('✅ Default period set:', latest);
            }
            
            await loadAllStatistics();
            
        } else {
            throw new Error('API returned success: false');
        }
        
    } catch (error) {
        console.error('❌ Error loading periods:', error);
        
        const currentYear = new Date().getFullYear();
        availablePeriods.tahun_list = [currentYear];
        populateTahunDropdown();
        
        await loadAllStatistics();
    }
}

/* ===============================
   POPULATE FILTERS
================================ */
/* ===============================
   POPULATE FILTERS
================================ */
function populateTahunDropdown() {
    const grid = document.getElementById('tahunGrid');
    if (!grid) return;
    
    grid.innerHTML = '';
    
    // Get all years from 2020 to current year
    const currentYear = new Date().getFullYear();
    const allYears = [];
    for (let i = 2020; i <= currentYear; i++) {
        allYears.push(i);
    }
    
    const activeYears = availablePeriods.tahun_list || [];
    
    allYears.forEach(year => {
        const btn = document.createElement('button');
        btn.className = 'grid-btn';
        btn.setAttribute('data-value', year);
        btn.textContent = year;
        
        const isActive = activeYears.includes(year);
        
        if (!isActive) {
            btn.setAttribute('disabled', 'disabled');
        } else {
            btn.onclick = (e) => {
                e.preventDefault();
                selectGridOption('tahun', year, year);
            };
        }
        
        grid.appendChild(btn);
    });
    
    console.log('✅ Tahun grid populated. Active years:', activeYears.length);
}

/* ===============================
   UPDATE PERIOD INFO DISPLAY
================================ */
function updatePeriodInfoDisplay() {
    const display = document.getElementById('periodInfo');
    const textEl = document.getElementById('periodInfoText');
    
    if (!display || !textEl) return;
    
    if (!currentPeriod.tahun) {
        display.style.display = 'none';
        return;
    }
    
    const tahun = currentPeriod.tahun;
    const bulan = monthNames[currentPeriod.bulan] || 'Bulan ' + currentPeriod.bulan;
    const minggu = currentPeriod.minggu ? `Minggu ke-${currentPeriod.minggu}` : 'Minggu';
    const recordCount = document.getElementById('recordCount')?.textContent || '0';
    
    textEl.innerHTML = `Data Terpilih - <strong>Tahun ${tahun}, ${bulan}, ${minggu}</strong> • records tersedia`;
    display.style.display = 'block';
    
    console.log('📍 Period display updated:', currentPeriod);
}

/* ===============================
   UPDATE RECORD COUNT
================================ */
function updateRecordCount(count) {
    const recordCountEl = document.getElementById('recordCount');
    if (recordCountEl) {
        recordCountEl.textContent = count;
        updatePeriodInfoDisplay();
    }
}

/* ===============================
   LOAD ALL STATISTICS
================================ */
async function loadAllStatistics() {
    showLoading();

    try {
        console.log('🚀 Loading statistics with latest published data...');
        
        const tahun = document.getElementById('tahunSelect')?.value;
        const bulan = document.getElementById('bulanSelect')?.value;
        const minggu = document.getElementById('mingguSelect')?.value;
        
        let queryParams = new URLSearchParams();
        if (tahun) queryParams.append('tahun', tahun);
        if (bulan) queryParams.append('bulan', bulan);
        if (minggu) queryParams.append('minggu', minggu);
        
        const queryString = queryParams.toString() ? '?' + queryParams.toString() : '';
        
        console.log('📡 Query:', queryString || '(using latest published)');
        
        const [summaryRes, rankingRes] = await Promise.all([
            fetch(`/api/statistik/summary${queryString}`),
            fetch(`/api/statistik/ranking${queryString}`)
        ]);

        if (!summaryRes.ok) throw new Error(`Summary API: ${summaryRes.status}`);
        if (!rankingRes.ok) throw new Error(`Ranking API: ${rankingRes.status}`);

        const summary = await summaryRes.json();
        const ranking = await rankingRes.json();

        console.log('📊 Summary response:', summary);
        console.log('🏆 Ranking response:', ranking);

        if (summary.success) {
            renderDetailStats(summary.data);
            renderComparison(summary.data);
            
            if (summary.import_log_id) {
                console.log(`✅ Using import_log_id: ${summary.import_log_id}`);
            }
        } else {
            showEmptyState('detailStatsTable', summary.message || 'Tidak ada data statistik');
        }
        
        if (ranking.success) {
            renderRanking(ranking.data);
        } else {
            showEmptyState('bar-chart', ranking.message || 'Tidak ada data ranking');
        }

        hideLoading();
        console.log('✅ Statistics loaded successfully!');

    } catch (err) {
        console.error('❌ Error loading statistics:', err);
        alert('Gagal memuat statistik: ' + err.message);
        hideLoading();
    }
}

/* ===============================
   UPDATE STATS
================================ */
async function updateStats() {
    const tahun = document.getElementById('filterTahun')?.value;
    const bulan = document.getElementById('filterBulan')?.value;
    const minggu = document.getElementById('filterMinggu')?.value;

    console.log('🔍 Filter:', { tahun, bulan, minggu });
    currentPeriod = { tahun, bulan, minggu };
    
    updatePeriodInfoDisplay();
    await loadAllStatistics();
}

/* ===============================
   RENDER FUNCTIONS
================================ */
function renderDetailStats(data) {
    const tbody = document.getElementById('detailStatsTable');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    updateRecordCount(data.length);

    if (!data || data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align:center; padding: 40px;">
                    <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                    <strong>Tidak ada data</strong><br>
                    <small>Pilih periode lain atau upload CSV di Admin</small>
                </td>
            </tr>
        `;
        return;
    }

    data.forEach((item, i) => {
        const row = tbody.insertRow();
        
        // IMPORTANT: 
        // - total_hasil = Luas Rencana (from CSV "hasil" column)
        // - total_neto = Total Neto (from CSV "neto" column)
        // - total_tenaga_kerja = Total TK (from CSV "total_tk" column)
        
        const luasRencana = parseFloat(item.total_hasil || 0).toFixed(2); // Luas Rencana
        const totalNeto = parseFloat(item.total_neto || 0).toFixed(2); // Total Neto
        const totalTk = Math.round(parseFloat(item.total_tenaga_kerja || 0)); // Total TK (dibulatkan)
        
        // DEBUG: Log first 3 items
        if (i < 3 || item.wilayah_id == 16) {
            console.log(`🔍 WILAYAH ${item.wilayah_id} - BEFORE FORMAT`, {
                total_tenaga_kerja_raw: item.total_tenaga_kerja,
                parseFloat_result: parseFloat(item.total_tenaga_kerja || 0),
                afterRound: totalTk
            });
        }
        
        row.innerHTML = `
            <td><strong>${i + 1}</strong></td>
            <td><strong>Wilayah ${item.wilayah_id}</strong></td>
            <td class="stat-value">${luasRencana} Ha</td>
            <td class="stat-value">${totalNeto} Ha</td>
            <td class="stat-value"><strong>${totalTk.toLocaleString('id-ID')}</strong> Orang</td>
            <td><strong>${currentPeriod.tahun || new Date().getFullYear()}</strong></td>
        `;
        
        // Debug info
        if (item.raw_count) {
            row.cells[0].title = `Raw: ${item.raw_count} → Dedup: ${item.total_features}`;
        }
    });
    
    console.log('✅ Detail stats rendered:', data.length, 'wilayah');
}

function renderComparison(data) {
    const container = document.getElementById('comparisonGrid');
    if (!container) return;
    
    container.innerHTML = '';

    if (!data || data.length === 0) {
        container.innerHTML = `
            <div style="text-align:center; padding: 40px; width: 100%; color: #999;">
                <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i><br>
                <strong>Tidak ada data</strong>
            </div>
        `;
        return;
    }

    // IMPORTANT: total_hasil = Luas Rencana (from CSV "hasil" column)
    const totalLuasRencana = data.reduce((sum, item) => sum + (parseFloat(item.total_hasil) || 0), 0);
    const totalTenagaKerja = data.reduce((sum, item) => sum + (parseFloat(item.total_tenaga_kerja) || 0), 0);
    
    // DEBUG: Log raw values
    console.log('🔍 RENDER COMPARISON - Raw Values:', {
        dataLength: data.length,
        firstItem: data[0],
        totalTenagaKerjaRaw: totalTenagaKerja,
        totalTenagaKerjaRounded: Math.round(totalTenagaKerja)
    });

    const card = document.createElement('div');
    card.className = 'comparison-card';
    card.innerHTML = `
        <div class="comparison-title"><i class="fa-solid fa-jar-wheat" style="color: #FBA919;"></i> Nanas</div>
        
        <div class="comparison-stat">
            <span class="comparison-label">Luas Rencana Wilayah:</span>
            <span class="comparison-value">${totalLuasRencana.toFixed(2)} Ha</span>
        </div>
        <div class="comparison-stat">
            <span class="comparison-label">Tenaga Kerja:</span>
            <span class="comparison-value">${Math.round(totalTenagaKerja).toLocaleString('id-ID')} Orang</span>
        </div>
    `;
    container.appendChild(card);
    
    console.log('✅ Comparison rendered:', {
        totalLuasRencana: totalLuasRencana.toFixed(2),
        totalTenagaKerja: totalTenagaKerja
    });
}

function renderRanking(data) {
    const container = document.querySelector('.bar-chart');
    if (!container) return;
    
    container.innerHTML = '';

    if (!data || data.length === 0) {
        container.innerHTML = `
            <p style="text-align:center; padding: 40px;">
                <i class="fas fa-chart-bar" style="font-size: 48px; opacity: 0.3;"></i><br>
                <strong>Tidak ada data ranking</strong>
            </p>
        `;
        return;
    }

    const max = Math.max(...data.map(d => parseFloat(d.total_hasil) || 0));

    data.forEach((item, index) => {
        const hasil = parseFloat(item.total_hasil) || 0;
        const percent = max > 0 ? (hasil / max) * 100 : 0;
        
        // Medal icons for top 3
        let medalIcon = '';
        if (index === 0) medalIcon = '🥇';
        else if (index === 1) medalIcon = '🥈';
        else if (index === 2) medalIcon = '🥉';

        const barItem = document.createElement('div');
        barItem.className = 'bar-item';
        barItem.innerHTML = `
            <div class="bar-label">${medalIcon} Wilayah ${item.wilayah_id}</div>
            <div class="bar-container">
                <div class="bar-fill" style="width:${percent}%">${hasil.toFixed(2)} Ha</div>
            </div>
            <div class="bar-value">${hasil.toFixed(2)} Ha</div>
        `;
        container.appendChild(barItem);
    });
    
    console.log('✅ Ranking rendered:', data.length, 'wilayah');
}

function showEmptyState(elementId, message) {
    const element = document.getElementById(elementId) || document.querySelector(`.${elementId}`);
    if (!element) return;
    
    element.innerHTML = `
        <div style="text-align:center; padding: 40px; color: #999;">
            <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i><br>
            <strong>${message}</strong>
        </div>
    `;
}

/* ===============================
   LOADING HELPERS
================================ */
function showLoading() {
    document.body.style.cursor = 'wait';
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.remove();
    
    const div = document.createElement('div');
    div.id = 'loadingOverlay';
    div.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.9); z-index: 9999; display: flex; align-items: center; justify-content: center;';
    div.innerHTML = `
        <div style="text-align: center;">
            <div style="border: 4px solid #f3f3f3; border-top: 4px solid var(--primary-color); border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
            <p style="color: var(--primary-color); font-weight: 600;">Memuat statistik...</p>
        </div>
        <style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>
    `;
    document.body.appendChild(div);
}

function hideLoading() {
    document.body.style.cursor = 'default';
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.remove();
}

/* ===============================
   CSV DATA LOADING
================================ */
async function loadCsvDataForStatistik() {
    try {
        const response = await fetch('/api/csv/data?per_page=100');
        const result = await response.json();
        
        if (!result.success || !result.data) {
            showEmptyState('csvDataTableBody', 'Tidak ada data CSV');
            return;
        }
        
        const tbody = document.getElementById('csvDataTableBody');
        let html = '';
        let no = 1;
        
        result.data.forEach(row => {
            const kategoriColor = getKategoriColorStatistik(row.kategori);
            html += `
                <tr>
                    <td style="text-align: center; font-weight: 600; color: #666;">${no++}</td>
                    <td style="text-align: center; font-size: 13px;">${row.pg || '-'}</td>
                    <td style="text-align: center; font-size: 13px;">${row.fm || '-'}</td>
                    <td style="text-align: center; font-weight: 500; color: #128241;">${row.wilayah_id || '-'}</td>
                    <td style="text-align: left; font-size: 13px;">${row.seksi || '-'}</td>
                    <td style="text-align: right; font-size: 13px;">${row.neto ? parseFloat(row.neto).toFixed(2) : '-'}</td>
                    <td style="text-align: right; font-size: 13px;">${row.hasil ? parseFloat(row.hasil).toFixed(2) : '-'}</td>
                    <td style="text-align: center; font-size: 13px;">${row.umur ? parseInt(row.umur) : '-'}</td>
                    <td style="text-align: center; font-size: 12px; color: #666;">${row.tnm_sts || '-'}</td>
                    <td style="text-align: center; font-size: 12px;">${row.activitas || '-'}</td>
                    <td style="text-align: center;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; color: white; background-color: ${kategoriColor};">
                            ${row.kategori || 'N/A'}
                        </span>
                    </td>
                    <td style="text-align: center; font-size: 12px;">${row.tanggal ? new Date(row.tanggal).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' }) : '-'}</td>
                    <td style="text-align: right; font-size: 13px;">${row.total_tk ? parseInt(row.total_tk) : '-'}</td>
                    <td style="text-align: right; font-size: 12px;">${row.tk_ha ? parseFloat(row.tk_ha).toFixed(2) : '-'}</td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        console.log('✅ CSV data loaded:', result.data.length, 'records');
        
    } catch (error) {
        console.error('Error loading CSV data:', error);
        showEmptyState('csvDataTableBody', 'Gagal memuat data CSV');
    }
}

function getKategoriColorStatistik(kategori) {
    const colors = {
        'Bersih': '#3498db',
        'Ringan': '#27ae60',
        'Sedang': '#f39c12',
        'Berat': '#e74c3c',
        'Weeding': '#9b59b6'
    };
    return colors[kategori] || '#95a5a6';
}

function exportStatistikToCsv() {
    window.location.href = '/api/csv/export';
}

// Load CSV data after page load
window.addEventListener('load', function() {
    setTimeout(loadCsvDataForStatistik, 1000);
});

</script>

@endsection