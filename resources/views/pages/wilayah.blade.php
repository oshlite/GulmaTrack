@extends('layouts.app')

@section('title', 'Wilayah')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-map-marked-alt"></i> Peta Wilayah Produksi</h1>
    <p>Jelajahi distribusi dan informasi geografis area produksi hortikultura</p>
</div>

<div class="container">
    <style>
        .wilayah-controls {
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

        .wilayah-controls input,
        .wilayah-controls select {
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
            flex: 1;
            min-width: 200px;
        }

        .wilayah-controls button {
            padding: 10px 25px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .wilayah-controls button:hover {
            background-color: var(--secondary-color);
        }

        .wilayah-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .wilayah-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 5px solid var(--primary-color);
        }

        .wilayah-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .wilayah-card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            text-align: center;
        }

        .wilayah-card-header h3 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .wilayah-card-body {
            padding: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #555;
        }

        .info-value {
            color: var(--primary-color);
            font-weight: 600;
        }

        .wilayah-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .wilayah-actions button {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .wilayah-actions button:hover {
            background-color: var(--light-color);
            border-color: var(--primary-color);
        }

        .map-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            text-align: center;
            margin: 30px 0;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .map-placeholder {
            color: #999;
            font-size: 18px;
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
    </style>

    <!-- Statistik -->
    <div class="stats-row" style="display: flex; gap: 50px; margin-bottom: 10px; margin-right: 70px; margin-left: 250px;">
    <div class="stat-box" style="
        padding: 10px;
        border-radius: 8px;
        background: linear-gradient(100deg, var(--secondary-color), #2980b9);
        color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        width: 500px;
        height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;">
        <div class="stat-label" style="font-weight: bold;">
            <i class="fas fa-ruler-combined"></i> Total Wilayah
        </div>
        <div class="stat-number" style="font-size: 30px;">30</div>
    </div>

    <div class="stat-box" style="
        padding: 10px;
        border-radius: 8px;
        background: linear-gradient(100deg, var(--secondary-color), #2980b9);
        color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        width: 500px;
        height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;">
        <div class="stat-label" style="font-weight: bold;">
            <i class="fas fa-ruler-combined"></i> Total Luas Area
        </div>
        <div class="stat-number" style="font-size: 30px;">2.450 Ha</div>
    </div>
</div>


    <!-- Peta Interaktif -->
    <div class="map-container">
        <div class="map-placeholder">
            <i class="fas fa-map fa-2x" style="margin-bottom: 10px; color: var(--primary-color);"></i><br>
            Peta Interaktif akan ditampilkan di sini<br>
            <small>(Integrasi dengan Leaflet.js atau Mapbox)</small>
        </div>
    </div>

    <!-- Daftar Wilayah -->
    <h2 style="font-size: 24px; margin: 30px 0 20px; color: var(--title-color);">Daftar Wilayah Produksi</h2>
    
    <!-- Kontrol Wilayah -->
    <div class="wilayah-controls">
        <input type="text" id="searchWilayah" placeholder="Cari wilayah...">
        <select id="filterKomoditas">
            <option value="">Semua Kategori</option>
            <option value="ringan">Ringan</option>
            <option value="sedang">Sedang</option>
            <option value="berat">Berat</option>
            <option value="bersih">Bersih</option>
        </select>
        <button onclick="filterWilayah()">Filter</button>
    </div>
    
    <div class="wilayah-grid" id="wilayahGrid">
        <!-- Wilayah cards akan dimuat di sini -->
    </div>
</div>

<script>
    // Data wilayah dummy
    const wilayahData = [
        { id: 1, nama: 'Wilayah 16', luas: 320, kategori: 'Ringan', statuswilayah: 'Aktif', tenagakerja: 45 },
        { id: 2, nama: 'Wilayah 17', luas: 180, kategori: 'Berat', statuswilayah: 'Aktif', tenagakerja: 32 },
        { id: 3, nama: 'Wilayah 18', luas: 250, kategori: 'Sedang', statuswilayah: 'Aktif', tenagakerja: 28 },
        { id: 4, nama: 'Wilayah 19', luas: 420, kategori: 'Bersih', statuswilayah: 'Aktif', tenagakerja: 50 },
        { id: 5, nama: 'Wilayah 20', luas: 160, kategori: 'Ringan', statuswilayah: 'Aktif', tenagakerja: 22 },
        { id: 6, nama: 'Wilayah 21', luas: 280, kategori: 'Berat', statuswilayah: 'Aktif', tenagakerja: 35 },
        { id: 7, nama: 'Wilayah 22', luas: 350, kategori: 'Sedang', statuswilayah: 'Aktif', tenagakerja: 40 },
        { id: 8, nama: 'Wilayah 23', luas: 190, kategori: 'Ringan', statuswilayah: 'Aktif', tenagakerja: 25 },
    ];

    function renderWilayah(data) {
        const grid = document.getElementById('wilayahGrid');
        grid.innerHTML = '';

        data.forEach(wilayah => {
            const card = document.createElement('div');
            card.className = 'wilayah-card';
            card.innerHTML = `
                <div class="wilayah-card-header">
                    <h3>${wilayah.nama}</h3>
                </div>
                <div class="wilayah-card-body">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-ruler"></i> Luas Lahan:</span>
                        <span class="info-value">${wilayah.luas} Ha</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-chart-bar"></i> Kategori:</span>
                        <span class="info-value">${wilayah.kategori}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-chart-line"></i> Status Wilayah:</span>
                        <span class="info-value">${wilayah.statuswilayah}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-chart-line"></i> Total Tenaga Kerja:</span>
                        <span class="info-value">${wilayah.tenagakerja} Orang</span>
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
            const matchSearch = w.nama.toLowerCase().includes(search);
            const matchKomoditas = !komoditas || w.kategori.toLowerCase().includes(komoditas);
            return matchSearch && matchKomoditas;
        });

        renderWilayah(filtered);
    }

    // Initial render
    renderWilayah(wilayahData);

    // Real-time search
    document.getElementById('searchWilayah').addEventListener('keyup', filterWilayah);
    document.getElementById('filterKomoditas').addEventListener('change', filterWilayah);
</script>
@endsection
