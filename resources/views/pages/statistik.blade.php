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
    </style>

    <!-- Filter & Kontrol -->
    <div class="stats-controls">
        <select id="filterTahun">
            <option value="">Memuat tahun...</option>
        </select>

        <select id="filterBulan">
            <option value="">Memuat bulan...</option>
        </select>

        <select id="filterMinggu">
            <option value="">Semua Minggu</option>
            <option value="1">Minggu 1</option>
            <option value="2">Minggu 2</option>
            <option value="3">Minggu 3</option>
            <option value="4">Minggu 4</option>
        </select>

        <button onclick="updateStats()">
            <i class="fas fa-search"></i> Update Statistik
        </button>
    </div>

    <!-- Period Info Display -->
    <div class="period-info" id="periodInfoDisplay" style="display: none;">
        <i class="fas fa-info-circle"></i> 
        <span id="periodInfoText">Menampilkan data terbaru</span>
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
            
            populateYearFilter();
            populateMonthFilter();
            
            if (availablePeriods.latest_period) {
                const latest = availablePeriods.latest_period;
                currentPeriod = latest;
                
                document.getElementById('filterTahun').value = latest.tahun;
                document.getElementById('filterBulan').value = latest.bulan;
                document.getElementById('filterMinggu').value = latest.minggu || '';
                
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
        populateYearFilter();
        populateMonthFilter();
        
        await loadAllStatistics();
    }
}

/* ===============================
   POPULATE FILTERS
================================ */
function populateYearFilter() {
    const select = document.getElementById('filterTahun');
    if (!select) return;
    
    select.innerHTML = '<option value="">Semua Tahun</option>';
    
    if (availablePeriods.tahun_list && availablePeriods.tahun_list.length > 0) {
        availablePeriods.tahun_list.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            select.appendChild(option);
        });
        console.log('✅ Year filter populated:', availablePeriods.tahun_list.length, 'years');
    }
}

function populateMonthFilter() {
    const select = document.getElementById('filterBulan');
    if (!select) return;

    select.innerHTML = '<option value="">Semua Bulan</option>';

    for (let i = 1; i <= 12; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = monthNames[i];
        select.appendChild(option);
    }

    console.log('✅ Month filter populated: all months (1–12)');
}

/* ===============================
   UPDATE PERIOD INFO DISPLAY
================================ */
function updatePeriodInfoDisplay() {
    const display = document.getElementById('periodInfoDisplay');
    const textEl = document.getElementById('periodInfoText');
    
    if (!currentPeriod.tahun) {
        display.style.display = 'none';
        return;
    }
    
    const tahun = currentPeriod.tahun;
    const bulan = currentPeriod.bulan ? monthNames[currentPeriod.bulan] : 'Semua Bulan';
    const minggu = currentPeriod.minggu ? `Minggu ke-${currentPeriod.minggu}` : 'Semua Minggu';
    
    textEl.innerHTML = `Menampilkan data: <strong>Tahun ${tahun}, ${bulan}, ${minggu}</strong>`;
    display.style.display = 'block';
    
    console.log('📍 Period display updated:', currentPeriod);
}

/* ===============================
   LOAD ALL STATISTICS
================================ */
async function loadAllStatistics() {
    showLoading();

    try {
        console.log('🚀 Loading statistics with latest published data...');
        
        const tahun = document.getElementById('filterTahun')?.value;
        const bulan = document.getElementById('filterBulan')?.value;
        const minggu = document.getElementById('filterMinggu')?.value;
        
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

</script>

@endsection