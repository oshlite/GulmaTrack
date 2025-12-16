@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> Kategori perkebunan</h1>
    <p>Pantau Kategori-Kategori penting yang mempengaruhi performa perkebunan</p>
</div>

<div class="container">
    <style>
        .indicator-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .indicator-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: var(--shadow);
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .indicator-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .indicator-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .indicator-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .indicator-emoji {
            font-size: 28px;
        }

        .indicator-value {
            font-size: 36px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .indicator-unit {
            color: #999;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .progress-bar {
            background-color: var(--light-color);
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: width 0.3s ease;
        }

        .indicator-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #999;
        }

        .trend {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        .trend-up {
            background-color: #d4edda;
            color: #155724;
        }

        .trend-down {
            background-color: #f8d7da;
            color: #721c24;
        }

        .trend-neutral {
            background-color: #fff3cd;
            color: #856404;
        }

        .detailed-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .detailed-section h3 {
            color: var(--title-color);
            margin-bottom: 20px;
            font-size: 20px;
            border-bottom: 3px solid var(--title-color);
            padding-bottom: 15px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table th {
            background-color: var(--light-color);
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
        }

        .detail-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .detail-table tr:hover {
            background-color: var(--light-color);
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-section select,
        .filter-section input {
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }

        .filter-section button {
            padding: 10px 25px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-section button:hover {
            background-color: var(--secondary-color);
        }
    </style>

    <!-- Filter -->
    <div class="filter-section">
        <select id="filterWilayah">
            <option value="">Semua Wilayah</option>
            <option value="pelalawan">Kabupaten Pelalawan</option>
            <option value="bengkalis">Kabupaten Bengkalis</option>
            <option value="rokan">Kabupaten Rokan Hilir</option>
            <option value="siak">Kabupaten Siak</option>
        </select>
        <select id="filterTahun">
            <option value="2025">Tahun 2025</option>
            <option value="2024">Tahun 2024</option>
            <option value="2023">Tahun 2023</option>
        </select>
        <button onclick="updateIndicators()"><i class="fas fa-search"></i> Update</button>
    </div>

    <!-- Kategori Utama -->
    <h2 style="font-size: 24px; margin: 30px 0 20px; color: var(--title-color);">Kategori Kunci perkebunan</h2>
    <div class="indicator-grid">
        <div class="indicator-card">
            <div class="indicator-header">
                <div class="indicator-title">Produktivitas Nanas</div>
                <div class="indicator-emoji"><i class="fas fa-apple-alt"></i></div>
            </div>
            <div class="indicator-value" id="prodNanas">8.5</div>
            <div class="indicator-unit">Ton/Hektar</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 85%;"></div>
            </div>
            <div class="indicator-meta">
                <span>Target: 10 T/Ha</span>
                <span>Capaian: 85%</span>
            </div>
            <span class="trend trend-up">📈 +2.3% vs tahun lalu</span>
        </div>

        <div class="indicator-card">
            <div class="indicator-header">
                <div class="indicator-title">Produktivitas Pisang</div>
                <div class="indicator-emoji"><i class="fas fa-leaf"></i></div>
            </div>
            <div class="indicator-value" id="prodPisang">8.0</div>
            <div class="indicator-unit">Ton/Hektar</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 80%;"></div>
            </div>
            <div class="indicator-meta">
                <span>Target: 10 T/Ha</span>
                <span>Capaian: 80%</span>
            </div>
            <span class="trend trend-neutral">➡️ Stabil vs tahun lalu</span>
        </div>

        <div class="indicator-card">
            <div class="indicator-header">
                <div class="indicator-title">Luas Lahan Efektif</div>
                <div class="indicator-emoji"><i class="fas fa-ruler"></i></div>
            </div>
            <div class="indicator-value" id="luasLahan">3,690</div>
            <div class="indicator-unit">Hektar</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 75%;"></div>
            </div>
            <div class="indicator-meta">
                <span>Target: 5,000 Ha</span>
                <span>Capaian: 75%</span>
            </div>
            <span class="trend trend-up">📈 +3.5% vs tahun lalu</span>
        </div>

        <div class="indicator-card">
            <div class="indicator-header">
                <div class="indicator-title">Jumlah Petani Aktif</div>
                <div class="indicator-emoji"><i class="fas fa-users"></i></div>
            </div>
            <div class="indicator-value" id="jumlahPetani">1,348</div>
            <div class="indicator-unit">Orang</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 90%;"></div>
            </div>
            <div class="indicator-meta">
                <span>Target: 1,500</span>
                <span>Capaian: 90%</span>
            </div>
            <span class="trend trend-up">📈 +5.2% vs tahun lalu</span>
        </div>

        <div class="indicator-card">
            <div class="indicator-header">
                <div class="indicator-title">Total Produksi</div>
                <div class="indicator-emoji"><i class="fas fa-chart-bar"></i></div>
            </div>
            <div class="indicator-value" id="totalProduksi">30.7</div>
            <div class="indicator-unit">Ribu Ton</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 80%;"></div>
            </div>
            <div class="indicator-meta">
                <span>Target: 38 Ribu Ton</span>
                <span>Capaian: 80%</span>
            </div>
            <span class="trend trend-up">📈 +1.8% vs tahun lalu</span>
        </div>

        <div class="indicator-card">
            <div class="indicator-header">
                <div class="indicator-title">Efisiensi Lahan</div>
                <div class="indicator-emoji"><i class="fas fa-bolt"></i></div>
            </div>
            <div class="indicator-value" id="efisiensi">8.3</div>
            <div class="indicator-unit">T/Ha rata-rata</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 83%;"></div>
            </div>
            <div class="indicator-meta">
                <span>Target: 10 T/Ha</span>
                <span>Capaian: 83%</span>
            </div>
            <span class="trend trend-neutral">➡️ Stabil vs tahun lalu</span>
        </div>
    </div>

    <!-- Detail Kategori Wilayah -->
    <div class="detailed-section">
        <h3><i class="fas fa-map-pin"></i> Kategori Berdasarkan Wilayah</h3>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Wilayah</th>
                    <th>Prod. Nanas (T/Ha)</th>
                    <th>Prod. Pisang (T/Ha)</th>
                    <th>Luas Lahan (Ha)</th>
                    <th>Jumlah Petani</th>
                    <th>Total Produksi (Ton)</th>
                </tr>
            </thead>
            <tbody id="wilayahKategoriTable">
                <!-- Data akan dimuat via JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Detail Kategori Komoditas -->
    <div class="detailed-section">
        <h3><i class="fas fa-apple-alt"></i> Kategori Perbandingan Komoditas</h3>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Komoditas</th>
                    <th>Produktivitas (T/Ha)</th>
                    <th>Luas Tanam (Ha)</th>
                    <th>Total Produksi (Ton)</th>
                    <th>Jumlah Petani</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Nanas</strong></td>
                    <td>8.5</td>
                    <td>2,450</td>
                    <td>20,825</td>
                    <td>892</td>
                    <td><span class="trend trend-up">✓ Baik</span></td>
                </tr>
                <tr>
                    <td><strong>Pisang</strong></td>
                    <td>8.0</td>
                    <td>1,240</td>
                    <td>9,920</td>
                    <td>456</td>
                    <td><span class="trend trend-neutral">➡️ Stabil</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    const wilayahIndicatorData = [
        { wilayah: 'Kabupaten Pelalawan', prodNanas: 8.75, prodPisang: '-', luas: 320, petani: 125, produksi: 2800 },
        { wilayah: 'Kabupaten Rokan Hulu', prodNanas: '-', prodPisang: 8.0, luas: 180, petani: 65, produksi: 1440 },
        { wilayah: 'Kabupaten Rokan Hilir', prodNanas: 8.5, prodPisang: '-', luas: 250, petani: 95, produksi: 2125 },
        { wilayah: 'Kabupaten Bengkalis', prodNanas: 9.0, prodPisang: 8.0, luas: 420, petani: 160, produksi: 3780 },
        { wilayah: 'Kabupaten Siak', prodNanas: '-', prodPisang: 8.0, luas: 160, petani: 58, produksi: 1280 },
        { wilayah: 'Kabupaten Meranti', prodNanas: 8.5, prodPisang: '-', luas: 280, petani: 102, produksi: 2380 },
    ];

    function renderWilayahIndicator() {
        const tbody = document.getElementById('wilayahKategoriTable');
        tbody.innerHTML = '';

        wilayahIndicatorData.forEach(item => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td><strong>${item.wilayah}</strong></td>
                <td>${item.prodNanas}</td>
                <td>${item.prodPisang}</td>
                <td>${item.luas}</td>
                <td>${item.petani}</td>
                <td>${item.produksi.toLocaleString('id-ID')}</td>
            `;
        });
    }

    function updateIndicators() {
        const wilayah = document.getElementById('filterWilayah').value;
        const tahun = document.getElementById('filterTahun').value;
        alert(`Update Kategori untuk wilayah: ${wilayah || 'Semua'}, tahun: ${tahun}`);
        renderWilayahIndicator();
    }

    // Initial render
    renderWilayahIndicator();
</script>
@endsection
