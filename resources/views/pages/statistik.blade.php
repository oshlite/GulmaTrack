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
            <option value="2025">Minggu Pertama</option>
            <option value="2024">Minggu Kedua</option>
            <option value="2023">Minggu Ketiga</option>
            <option value="2022">Minggu Keempat</option>
        </select>
         <select id="filterBulan" style="min-width: 150px;">
            <option value="2025">Januari</option>
            <option value="2024">Februari</option>
            <option value="2023">Maret</option>
            <option value="2022">April</option>
            <option value="2025">Mei</option>
            <option value="2024">Juni</option>
            <option value="2023">Juli</option>
            <option value="2022">Agustus</option>
            <option value="2025">September</option>
            <option value="2024">Oktober</option>
            <option value="2023">November</option>
            <option value="2022">Desember</option>

        </select>

        <button onclick="updateStats()"><i class="fas fa-search"></i> Update Statistik</button>
    </div>

    <!-- Perbandingan Produksi -->
    <div class="stat-section">
        <h3><i class="fas fa-chart-bar"></i> Perbandingan Produksi Komoditas</h3>
        <div class="comparison-grid">
            <div class="comparison-card">
                <div class="comparison-title"><i class="fa-solid fa-jar-wheat" style="color: #FBA919;"></i> Nanas</div>
                <div class="comparison-stat">
                    <span class="comparison-label">Luas Perkebunan:</span>
                    <span class="comparison-value">20,825 Ha</span>
                </div>
                <div class="comparison-stat">
                    <span class="comparison-label">Total Neto:</span>
                    <span class="comparison-value">2,450 Ha</span>
                </div>
                <div class="comparison-stat">
                    <span class="comparison-label">Total Gulma:</span>
                    <span class="comparison-value">8.5 T/Ha</span>
                </div>
                <div class="comparison-stat">
                    <span class="comparison-label">Tenaga Kerja:</span>
                    <span class="comparison-value">892 Orang</span>
                </div>
                <div class="comparison-stat">
                    <span class="comparison-label">Perubahan:</span>
                    <span class="comparison-value"><span class="trend-indicator trend-up">↑ +2.3%</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Ranking Wilayah -->
    <div class="stat-section">
        <h3><i class="fas fa-trophy"></i> Ranking Wilayah Berdasarkan Gulma</h3>
        <div class="bar-chart">
            <div class="bar-item">
                <div class="bar-label">Wilayah 23</div>
                <div class="bar-container">
                    <div class="bar-fill" style="width: 100%;">3,780 Ton</div>
                </div>
                <div class="bar-value">3,780 T</div>
            </div>
            <div class="bar-item">
                <div class="bar-label">Wilayah 22</div>
                <div class="bar-container">
                    <div class="bar-fill" style="width: 74%;">2,800 Ton</div>
                </div>
                <div class="bar-value">2,800 T</div>
            </div>
            <div class="bar-item">
                <div class="bar-label">Wilayah 21</div>
                <div class="bar-container">
                    <div class="bar-fill" style="width: 63%;">2,380 Ton</div>
                </div>
                <div class="bar-value">2,380 T</div>
            </div>
            <div class="bar-item">
                <div class="bar-label">Wilayah 20</div>
                <div class="bar-container">
                    <div class="bar-fill" style="width: 56%;">2,125 Ton</div>
                </div>
                <div class="bar-value">2,125 T</div>
            </div>
            <div class="bar-item">
                <div class="bar-label">Wilayah 19</div>
                <div class="bar-container">
                    <div class="bar-fill" style="width: 46%;">1,700 Ton</div>
                </div>
                <div class="bar-value">1,700 T</div>
            </div>
            <div class="bar-item">
                <div class="bar-label">Wilayah 18</div>
                <div class="bar-container">
                    <div class="bar-fill" style="width: 38%;">1,440 Ton</div>
                </div>
                <div class="bar-value">1,440 T</div>
            </div>
             <div class="bar-item">
                <div class="bar-label">Wilayah 17</div>
                <div class="bar-container">
                    <div class="bar-fill" style="width: 38%;">1,440 Ton</div>
                </div>
                <div class="bar-value">1,440 T</div>
            </div>
             <div class="bar-item">
                <div class="bar-label">Wilayah 16</div>
                <div class="bar-container">
                    <div class="bar-fill" style="width: 38%;">1,440 Ton</div>
                </div>
                <div class="bar-value">1,440 T</div>
            </div>
        </div>
    </div>

    <!-- Perbandingan Tahun -->
    <div class="stat-section">
        <h3><i class="fas fa-calendar"></i> Perbandingan Produksi Tahunan</h3>
        <div class="year-comparison">
            <div class="year-item">
                <div class="year">2022</div>
                <div class="value">28,450</div>
                <div class="label">Ton</div>
            </div>
            <div class="year-item">
                <div class="year">2023</div>
                <div class="value">29,380</div>
                <div class="label">Ton</div>
            </div>
            <div class="year-item">
                <div class="year">2024</div>
                <div class="value">30,380</div>
                <div class="label">Ton</div>
            </div>
            <div class="year-item" style="border-left-color: var(--secondary-color);">
                <div class="year">2025</div>
                <div class="value" style="color: var(--secondary-color);">30,745</div>
                <div class="label">Ton (Current)</div>
            </div>
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
                    <th>Rata Rata Umur Tanaman</th>
                    <th>Total Tenaga Kerja</th>
                    <th>Tahun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="detailStatsTable">
                <!-- Data akan dimuat -->
            </tbody>
        </table>
    </div>

    <!-- Analisis Produktivitas -->
    <div class="stat-section">
        <h3><i class="fas fa-bolt"></i> Analisis Produktivitas</h3>
        <table class="stat-table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Jumlah Wilayah</th>
                    <th>Rata-rata Produktivitas</th>
                    <th>Potensi Peningkatan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Produktivitas Tinggi (>9 T/Ha)</strong></td>
                    <td>8</td>
                    <td class="stat-value">9.2 T/Ha</td>
                    <td>0.8 T/Ha</td>
                    <td><span class="trend-indicator trend-up">✓ Optimal</span></td>
                </tr>
                <tr>
                    <td><strong>Produktivitas Sedang (8-9 T/Ha)</strong></td>
                    <td>18</td>
                    <td class="stat-value">8.4 T/Ha</td>
                    <td>1.6 T/Ha</td>
                    <td><span class="trend-indicator trend-up">⚠ Dapat Ditingkatkan</span></td>
                </tr>
                <tr>
                    <td><strong>Produktivitas Rendah (<8 T/Ha)</strong></td>
                    <td>6</td>
                    <td class="stat-value">7.5 T/Ha</td>
                    <td>2.5 T/Ha</td>
                    <td><span class="trend-indicator trend-down">✕ Perlu Intervensi</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- <script>
    const wilayahStatsData = [
        { id: 1, wilayah: 'Wilayah 16', luas: 320, neto: 2800, gulma: 8.75, umur: '3.5 tahun', tenagaKerja: 125, tahun: 2025 },
        { id: 2, wilayah: 'Wilayah 17', luas: 250, neto: 2125, gulma: 8.5, umur: '3.2 tahun', tenagaKerja: 95, tahun: 2025 },
        { id: 3, wilayah: 'Wilayah 18', luas: 220, neto: 1980, gulma: 9.0, umur: '4.1 tahun', tenagaKerja: 88, tahun: 2025 },
        { id: 4, wilayah: 'Wilayah 19', luas: 280, neto: 2380, gulma: 8.5, umur: '3.8 tahun', tenagaKerja: 102, tahun: 2025 },
        { id: 5, wilayah: 'Wilayah 20', luas: 200, neto: 1700, gulma: 8.5, umur: '3.0 tahun', tenagaKerja: 78, tahun: 2025 },
        { id: 6, wilayah: 'Wilayah 21', luas: 180, neto: 1530, gulma: 8.5, umur: '2.8 tahun', tenagaKerja: 68, tahun: 2025 },
        { id: 7, wilayah: 'Wilayah 22', luas: 200, neto: 1700, gulma: 8.5, umur: '3.3 tahun', tenagaKerja: 78, tahun: 2025 },
        { id: 8, wilayah: 'Wilayah 23', luas: 190, neto: 1615, gulma: 8.5, umur: '3.1 tahun', tenagaKerja: 72, tahun: 2025 },
    ];

    function renderDetailStats() {
        const tbody = document.getElementById('detailStatsTable');
        tbody.innerHTML = '';

        wilayahStatsData.forEach((item, index) => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td><strong>${index + 1}</strong></td>
                <td><strong>${item.wilayah}</strong></td>
                <td class="stat-value">${item.luas.toLocaleString('id-ID')} Ha</td>
                <td class="stat-value">${item.neto.toLocaleString('id-ID')} Ha</td>
                <td class="stat-value">${item.gulma} T/Ha</td>
                <td>${item.umur}</td>
                <td>${item.tenagaKerja} Orang</td>
                <td><strong>${item.tahun}</strong></td>
                <td>
                    <button class="export-btn" onclick="viewDetail(${item.id})">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                </td>
            `;
        });
    }

    function viewDetail(id) {
        const item = wilayahStatsData.find(w => w.id === id);
        alert(`Detail ${item.wilayah}:\n\nLuas: ${item.luas} Ha\nTotal Neto: ${item.neto} Ha\nGulma: ${item.gulma} T/Ha\nUmur Tanaman: ${item.umur}\nTenaga Kerja: ${item.tenagaKerja} Orang`);
    }

    function updateStats() {
        const tahun = document.getElementById('filterTahun').value;
        const bulan = document.getElementById('filterBulan').value;
        alert(`Update statistik untuk filter yang dipilih`);
        renderDetailStats();
    }

    function printStats() {
        window.print();
    }

    // Initial render ketika halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        renderDetailStats();
    });

    // Atau langsung render jika DOM sudah siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', renderDetailStats);
    } else {
        renderDetailStats();
    }
</script> -->

<script>
// Variables
let currentPeriod = {
    tahun: null,
    bulan: null,
    minggu: null
};

// Load statistik data on page load
async function loadStatistikData() {
    try {
        // Show loading
        showLoading();
        
        // Load all data in parallel
        const [summaryRes, rankingRes, productivityRes, yearlyRes] = await Promise.all([
            fetch('/api/statistik/summary'),
            fetch('/api/statistik/ranking'),
            fetch('/api/statistik/productivity'),
            fetch('/api/statistik/yearly-comparison')
        ]);
        
        const summaryData = await summaryRes.json();
        const rankingData = await rankingRes.json();
        const productivityData = await productivityRes.json();
        const yearlyData = await yearlyRes.json();
        
        // Check if data loaded successfully
        if (summaryData.success) renderDetailStats(summaryData.data);
        if (rankingData.success) renderRanking(rankingData.data);
        if (productivityData.success) renderProductivity(productivityData.data);
        if (yearlyData.success) renderYearlyComparison(yearlyData.data);
        
        hideLoading();
        
    } catch (error) {
        console.error('Error loading statistik:', error);
        alert('Gagal memuat data statistik: ' + error.message);
        hideLoading();
    }
}

// Render detail stats table
function renderDetailStats(data) {
    const tbody = document.getElementById('detailStatsTable');
    tbody.innerHTML = '';
    
    if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 20px; color: #999;"><i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i><br>Tidak ada data tersedia</td></tr>';
        return;
    }
    
    data.forEach((item, index) => {
        const row = tbody.insertRow();
        row.innerHTML = `
            <td><strong>${index + 1}</strong></td>
            <td><strong>Wilayah ${item.wilayah_id}</strong></td>
            <td class="stat-value">${parseFloat(item.total_luas || 0).toFixed(2)} Ha</td>
            <td class="stat-value">${parseFloat(item.total_luas || 0).toFixed(2)} Ha</td>
            <td class="stat-value">${parseFloat(item.avg_hasil || 0).toFixed(2)} T/Ha</td>
            <td>${parseFloat(item.avg_umur || 0).toFixed(1)} tahun</td>
            <td>${parseInt(item.total_tenaga_kerja || 0)} Orang</td>
            <td><strong>2025</strong></td>
            <td>
                <button class="export-btn" onclick="viewDetail(${item.wilayah_id})">
                    <i class="fas fa-eye"></i> Detail
                </button>
            </td>
        `;
    });
}

// Render ranking bar chart
function renderRanking(data) {
    const container = document.querySelector('.bar-chart');
    container.innerHTML = '';
    
    if (!data || data.length === 0) {
        container.innerHTML = '<p style="text-align: center; padding: 40px; color: #999;"><i class="fas fa-chart-bar" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i><br>Tidak ada data ranking</p>';
        return;
    }
    
    // Find max value for percentage calculation
    const maxValue = Math.max(...data.map(d => parseFloat(d.total_hasil) || 0));
    
    data.forEach(item => {
        const hasilValue = parseFloat(item.total_hasil) || 0;
        const percentage = maxValue > 0 ? (hasilValue / maxValue) * 100 : 0;
        
        const barItem = document.createElement('div');
        barItem.className = 'bar-item';
        barItem.innerHTML = `
            <div class="bar-label">Wilayah ${item.wilayah_id}</div>
            <div class="bar-container">
                <div class="bar-fill" style="width: ${percentage}%;">${hasilValue.toFixed(2)} Ton</div>
            </div>
            <div class="bar-value">${hasilValue.toFixed(2)} T</div>
        `;
        container.appendChild(barItem);
    });
}

// Render productivity analysis
function renderProductivity(data) {
    const tbody = document.querySelector('.stat-section:nth-last-of-type(1) tbody');
    
    if (!tbody) {
        console.error('Productivity table body not found');
        return;
    }
    
    tbody.innerHTML = `
        <tr>
            <td><strong>Produktivitas Tinggi (>9 T/Ha)</strong></td>
            <td>${data.tinggi?.count || 0}</td>
            <td class="stat-value">${data.tinggi?.avg || 0} T/Ha</td>
            <td>${Math.max(0, (10 - (data.tinggi?.avg || 0))).toFixed(1)} T/Ha</td>
            <td><span class="trend-indicator trend-up">✓ Optimal</span></td>
        </tr>
        <tr>
            <td><strong>Produktivitas Sedang (8-9 T/Ha)</strong></td>
            <td>${data.sedang?.count || 0}</td>
            <td class="stat-value">${data.sedang?.avg || 0} T/Ha</td>
            <td>${Math.max(0, (9 - (data.sedang?.avg || 0))).toFixed(1)} T/Ha</td>
            <td><span class="trend-indicator trend-up">⚠ Dapat Ditingkatkan</span></td>
        </tr>
        <tr>
            <td><strong>Produktivitas Rendah (<8 T/Ha)</strong></td>
            <td>${data.rendah?.count || 0}</td>
            <td class="stat-value">${data.rendah?.avg || 0} T/Ha</td>
            <td>${Math.max(0, (8 - (data.rendah?.avg || 0))).toFixed(1)} T/Ha</td>
            <td><span class="trend-indicator trend-down">✕ Perlu Intervensi</span></td>
        </tr>
    `;
}

// Render yearly comparison
function renderYearlyComparison(data) {
    const container = document.querySelector('.year-comparison');
    container.innerHTML = '';
    
    if (!data || data.length === 0) {
        container.innerHTML = '<p style="text-align: center; padding: 40px; color: #999;"><i class="fas fa-calendar" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i><br>Tidak ada data tahunan</p>';
        return;
    }
    
    data.forEach((item, index) => {
        const isLatest = index === data.length - 1;
        const yearItem = document.createElement('div');
        yearItem.className = 'year-item';
        if (isLatest) {
            yearItem.style.borderLeftColor = 'var(--secondary-color)';
        }
        
        yearItem.innerHTML = `
            <div class="year">${item.tahun}</div>
            <div class="value" ${isLatest ? 'style="color: var(--secondary-color);"' : ''}>${parseFloat(item.total_hasil || 0).toLocaleString('id-ID', {maximumFractionDigits: 2})}</div>
            <div class="label">Ton ${isLatest ? '(Current)' : ''}</div>
        `;
        container.appendChild(yearItem);
    });
}

// View detail (redirect to wilayah page)
function viewDetail(wilayahId) {
    window.location.href = `/wilayah?wilayah=${wilayahId}`;
}

// Update stats with filter
async function updateStats() {
    const tahun = document.getElementById('filterTahun').value;
    const bulan = document.getElementById('filterBulan').value;
    
    if (!tahun || !bulan) {
        alert('Pilih tahun dan bulan terlebih dahulu');
        return;
    }
    
    try {
        showLoading();
        
        // Load with filters (minggu default = 1)
        const [summaryRes, rankingRes] = await Promise.all([
            fetch(`/api/statistik/summary?tahun=${tahun}&bulan=${bulan}&minggu=1`),
            fetch(`/api/statistik/ranking?tahun=${tahun}&bulan=${bulan}&minggu=1`)
        ]);
        
        const summaryData = await summaryRes.json();
        const rankingData = await rankingRes.json();
        
        if (summaryData.success) renderDetailStats(summaryData.data);
        if (rankingData.success) renderRanking(rankingData.data);
        
        hideLoading();
    } catch (error) {
        console.error('Error updating stats:', error);
        alert('Gagal memuat data: ' + error.message);
        hideLoading();
    }
}

// Loading helpers
function showLoading() {
    document.body.style.cursor = 'wait';
    // Optional: show loading overlay
    const overlay = document.createElement('div');
    overlay.id = 'loadingOverlay';
    overlay.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;';
    overlay.innerHTML = '<div style="text-align: center;"><div class="loading" style="margin: 0 auto 20px;"></div><p style="color: var(--primary-color); font-weight: 600;">Memuat data...</p></div>';
    document.body.appendChild(overlay);
}

function hideLoading() {
    document.body.style.cursor = 'default';
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.remove();
}

// Load on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Loading statistik data...');
    loadStatistikData();
});

// Backup: load if DOM already ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadStatistikData);
} else {
    loadStatistikData();
}
</script>