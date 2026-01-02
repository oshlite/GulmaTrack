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
        <select id="filterTahun" style="min-width: 150px;">
            <option value="">Semua Tahun</option>
            <option value="2024">2024</option>
            <option value="2025" selected>2025</option>
        </select>

        <select id="filterBulan" style="min-width: 150px;">
            <option value="">Semua Bulan</option>
            <option value="1">Januari</option>
            <option value="2">Februari</option>
            <option value="3">Maret</option>
            <option value="4">April</option>
            <option value="5">Mei</option>
            <option value="6">Juni</option>
            <option value="7">Juli</option>
            <option value="8">Agustus</option>
            <option value="9">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12" selected>Desember</option>
        </select>

        <select id="filterMinggu" style="min-width: 150px;">
            <option value="">Semua Minggu</option>
            <option value="1" selected>Minggu 1</option>
            <option value="2">Minggu 2</option>
            <option value="3">Minggu 3</option>
            <option value="4">Minggu 4</option>
        </select>

        <button onclick="updateStats()">
            <i class="fas fa-search"></i> Update Statistik
        </button>
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
                    <th>Luas Wilayah (Ha)</th>
                    <th>Total Neto</th>
                    <th>Total Gulma (T/Ha)</th>
                    <th>Rata-rata Umur Tanaman</th>
                    <th>Total Tenaga Kerja</th>
                    <th>Tahun</th>
                </tr>
            </thead>
            <tbody id="detailStatsTable">
                <!-- Will be populated by JavaScript -->
            </tbody>
        </table>
    </div>

    
<script>
/* ===============================
   STATISTIK PAGE - FIXED VERSION
================================ */

let currentPeriod = {
    tahun: null,
    bulan: null,
    minggu: null
};

/* ===============================
   INIT - Load data saat halaman dimuat
================================ */
document.addEventListener('DOMContentLoaded', async () => {
    console.log('📊 Statistik page loaded');
    await loadAllStatistics();
});

/* ===============================
   LOAD ALL STATISTICS
================================ */
async function loadAllStatistics() {
    showLoading();

    try {
        console.log('🚀 Loading statistics...');
        
        // Build query string dari filter
        const tahun = document.getElementById('filterTahun')?.value;
        const bulan = document.getElementById('filterBulan')?.value;
        const minggu = document.getElementById('filterMinggu')?.value;
        
        let queryParams = new URLSearchParams();
        if (tahun) queryParams.append('tahun', tahun);
        if (bulan) queryParams.append('bulan', bulan);
        if (minggu) queryParams.append('minggu', minggu);
        
        const queryString = queryParams.toString() ? '?' + queryParams.toString() : '';
        
        console.log('📡 Query:', queryString);
        
        // Load all endpoints
        const [summaryRes, rankingRes, productivityRes, yearlyRes] = await Promise.all([
            fetch(`/api/statistik/summary${queryString}`),
            fetch(`/api/statistik/ranking${queryString}`),
            fetch(`/api/statistik/productivity${queryString}`),
            fetch(`/api/statistik/yearly-comparison`)
        ]);

        // Check responses
        if (!summaryRes.ok) {
            throw new Error(`Summary API: ${summaryRes.status} ${summaryRes.statusText}`);
        }
        if (!rankingRes.ok) {
            throw new Error(`Ranking API: ${rankingRes.status} ${rankingRes.statusText}`);
        }
        if (!productivityRes.ok) {
            throw new Error(`Productivity API: ${productivityRes.status} ${productivityRes.statusText}`);
        }
        if (!yearlyRes.ok) {
            throw new Error(`Yearly API: ${yearlyRes.status} ${yearlyRes.statusText}`);
        }

        const summary = await summaryRes.json();
        const ranking = await rankingRes.json();
        const productivity = await productivityRes.json();
        const yearly = await yearlyRes.json();

        console.log('📊 Summary:', summary);
        console.log('🏆 Ranking:', ranking);
        console.log('📈 Productivity:', productivity);
        console.log('📅 Yearly:', yearly);

        // Render sections
        if (summary.success) {
            renderDetailStats(summary.data);
        } else {
            console.error('Summary error:', summary.message);
            showError('Summary', summary.message);
        }
        
        if (ranking.success) {
            renderRanking(ranking.data);
        } else {
            console.error('Ranking error:', ranking.message);
            showError('Ranking', ranking.message);
        }
        
        if (productivity.success) {
            renderProductivity(productivity.data);
        } else {
            console.error('Productivity error:', productivity.message);
            showError('Productivity', productivity.message);
        }
        
        if (yearly.success) {
            renderYearlyComparison(yearly.data);
        } else {
            console.error('Yearly error:', yearly.message);
            showError('Yearly', yearly.message);
        }

        hideLoading();
        console.log('✅ Statistics loaded!');

    } catch (err) {
        console.error('❌ Error:', err);
        alert('Gagal memuat statistik: ' + err.message);
        hideLoading();
    }
}

/* ===============================
   UPDATE STATS WITH FILTER
================================ */
async function updateStats() {
    const tahun = document.getElementById('filterTahun')?.value;
    const bulan = document.getElementById('filterBulan')?.value;
    const minggu = document.getElementById('filterMinggu')?.value;

    console.log('🔍 Filter:', { tahun, bulan, minggu });
    currentPeriod = { tahun, bulan, minggu };
    
    await loadAllStatistics();
}

/* ===============================
   RENDER DETAIL STATS TABLE
================================ */
function renderDetailStats(data) {
    const tbody = document.getElementById('detailStatsTable');
    if (!tbody) return;
    
    tbody.innerHTML = '';

    if (!data || data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" style="text-align:center; padding: 40px;">
                    <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                    <strong>Tidak ada data</strong><br>
                    <small>Upload CSV di halaman Admin</small>
                </td>
            </tr>
        `;
        return;
    }

    data.forEach((item, i) => {
        const row = tbody.insertRow();
        row.innerHTML = `
            <td><strong>${i + 1}</strong></td>
            <td><strong>Wilayah ${item.wilayah_id}</strong></td>
            <td class="stat-value">${parseFloat(item.total_neto || 0).toFixed(2)} Ha</td>
            <td class="stat-value">${parseFloat(item.total_neto || 0).toFixed(2)} Ha</td>
            <td class="stat-value">${parseFloat(item.avg_hasil || 0).toFixed(2)} T/Ha</td>
            <td>${parseFloat(item.avg_umur || 0).toFixed(1)} bulan</td>
            <td>${parseInt(item.total_tenaga_kerja || 0).toLocaleString('id-ID')} Orang</td>
            <td><strong>${currentPeriod.tahun || new Date().getFullYear()}</strong></td>
        `;
    });
}

/* ===============================
   RENDER RANKING
================================ */
function renderRanking(data) {
    const container = document.querySelector('.bar-chart');
    if (!container) return;
    
    container.innerHTML = '';

    if (!data || data.length === 0) {
        container.innerHTML = '<p style="text-align:center; padding: 40px;"><i class="fas fa-chart-bar" style="font-size: 48px; opacity: 0.3;"></i><br><strong>Tidak ada data ranking</strong></p>';
        return;
    }

    const max = Math.max(...data.map(d => parseFloat(d.total_hasil) || 0));

    data.forEach(item => {
        const hasil = parseFloat(item.total_hasil) || 0;
        const percent = max > 0 ? (hasil / max) * 100 : 0;

        const barItem = document.createElement('div');
        barItem.className = 'bar-item';
        barItem.innerHTML = `
            <div class="bar-label">Wilayah ${item.wilayah_id}</div>
            <div class="bar-container">
                <div class="bar-fill" style="width:${percent}%">${hasil.toFixed(2)} Ton</div>
            </div>
            <div class="bar-value">${hasil.toFixed(2)} T</div>
        `;
        container.appendChild(barItem);
    });
}

/* ===============================
   RENDER PRODUCTIVITY
================================ */
function renderProductivity(data) {
    const tbody = document.getElementById('productivityTable');
    if (!tbody) return;

    const tinggi = data.tinggi || { count: 0, avg: 0 };
    const sedang = data.sedang || { count: 0, avg: 0 };
    const rendah = data.rendah || { count: 0, avg: 0 };

    tbody.innerHTML = `
        <tr>
            <td><strong>Produktivitas Tinggi (>9 T/Ha)</strong></td>
            <td>${tinggi.count}</td>
            <td class="stat-value">${parseFloat(tinggi.avg).toFixed(2)} T/Ha</td>
            <td>${Math.max(0, 10 - tinggi.avg).toFixed(1)} T/Ha</td>
            <td><span class="trend-indicator trend-up">✓ Optimal</span></td>
        </tr>
        <tr>
            <td><strong>Produktivitas Sedang (8-9 T/Ha)</strong></td>
            <td>${sedang.count}</td>
            <td class="stat-value">${parseFloat(sedang.avg).toFixed(2)} T/Ha</td>
            <td>${Math.max(0, 9 - sedang.avg).toFixed(1)} T/Ha</td>
            <td><span class="trend-indicator trend-up">⚠ Dapat Ditingkatkan</span></td>
        </tr>
        <tr>
            <td><strong>Produktivitas Rendah (<8 T/Ha)</strong></td>
            <td>${rendah.count}</td>
            <td class="stat-value">${parseFloat(rendah.avg).toFixed(2)} T/Ha</td>
            <td>${Math.max(0, 8 - rendah.avg).toFixed(1)} T/Ha</td>
            <td><span class="trend-indicator trend-down">✕ Perlu Intervensi</span></td>
        </tr>
    `;
}

/* ===============================
   RENDER YEARLY COMPARISON
================================ */
function renderYearlyComparison(data) {
    const container = document.querySelector('.year-comparison');
    if (!container) return;
    
    container.innerHTML = '';

    if (!data || data.length === 0) {
        container.innerHTML = '<p style="text-align:center; padding: 40px; grid-column: 1/-1;"><i class="fas fa-calendar" style="font-size: 48px; opacity: 0.3;"></i><br><strong>Tidak ada data tahunan</strong></p>';
        return;
    }

    data.forEach((item, i) => {
        const isLast = i === data.length - 1;
        const yearItem = document.createElement('div');
        yearItem.className = 'year-item';
        
        if (isLast) {
            yearItem.style.borderLeftColor = 'var(--secondary-color)';
        }
        
        const hasil = parseFloat(item.total_hasil || 0);
        
        yearItem.innerHTML = `
            <div class="year">${item.tahun}</div>
            <div class="value" ${isLast ? 'style="color: var(--secondary-color);"' : ''}>
                ${hasil.toLocaleString('id-ID')}
            </div>
            <div class="label">Ton ${isLast ? '(Current)' : ''}</div>
        `;
        container.appendChild(yearItem);
    });
}

/* ===============================
   HELPERS
================================ */
function viewDetail(wilayahId) {
    window.location.href = `/wilayah?wilayah=${wilayahId}`;
}

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

function showError(section, message) {
    console.error(`${section} error:`, message);
}
</script>

@endsection