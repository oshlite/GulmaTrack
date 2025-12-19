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
    <div class="stats-row" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
        <div class="stat-box" style="flex: 1; padding: 10px; border-radius: 8px; background: #f9f9f9; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 100px; height: 100px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <div class="stat-label" style="font-weight: bold;"><i class="fas fa-globe"></i> Total Wilayah</div>
            <div class="stat-number" id="totalWilayah" style="font-size: 20px; color: #333;">30</div>
        </div>
        <div class="stat-box" style="flex: 1; padding: 10px; border-radius: 8px; background: linear-gradient(100deg, var(--secondary-color), #2980b9); color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 100px; height: 100px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <div class="stat-label" style="font-weight: bold;"><i class="fas fa-ruler-combined"></i> Total Luas Area</div>
            <div class="stat-number" style="font-size: 20px;">2.450 Ha</div>
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

    <!-- Filter -->
    <div class="wilayah-controls">
        <input type="text" id="searchWilayah" placeholder="Cari wilayah..." style="flex: 2;">
        <select id="filterKomoditas" style="flex: 1;">
            <option value="">Semua Komoditas</option>
            <option value="nanas">Nanas</option>
            <option value="pisang">Pisang</option>
            <option value="keduanya">Keduanya</option>
        </select>
        <button onclick="filterWilayah()"><i class="fas fa-search"></i> Terapkan Filter</button>
    </div>

    <!-- Daftar Wilayah -->
    <h2 style="font-size: 24px; margin: 30px 0 20px; color: var(--title-color);">Daftar Wilayah Produksi</h2>
    <div class="wilayah-grid" id="wilayahGrid">
        <!-- Wilayah cards akan dimuat di sini -->
    </div>
</div>

<script>
    // Data wilayah dummy
    const wilayahData = [
        { id: 1, nama: 'Wilayah 16', komoditas: 'Nanas', luas: 320, produksi: 2800, produktivitas: 8.75 },
        { id: 2, nama: 'Wilayah 17', komoditas: 'Nanas', luas: 180, produksi: 1440, produktivitas: 8.0 },
        { id: 3, nama: 'Wilayah 18', komoditas: 'Nanas', luas: 250, produksi: 2125, produktivitas: 8.5 },
        { id: 4, nama: 'Wilayah 19', komoditas: 'Nanas', luas: 420, produksi: 3780, produktivitas: 9.0 },
        { id: 5, nama: 'Wilayah 20', komoditas: 'Nanas', luas: 160, produksi: 1280, produktivitas: 8.0 },
        { id: 6, nama: 'Wilayah 21', komoditas: 'Nanas', luas: 280, produksi: 2380, produktivitas: 8.5 },
        { id: 7, nama: 'Wilayah 22', komoditas: 'Nanas', luas: 350, produksi: 3150, produktivitas: 9.0 },
        { id: 8, nama: 'Wilayah 23', komoditas: 'Nanas', luas: 190, produksi: 1520, produktivitas: 8.0 },
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
                        <span class="info-label"><i class="fas fa-tag"></i> Komoditas:</span>
                        <span class="info-value">${wilayah.komoditas}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-ruler"></i> Luas Lahan:</span>
                        <span class="info-value">${wilayah.luas} Ha</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-chart-bar"></i> Produksi:</span>
                        <span class="info-value">${wilayah.produksi} Ton</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-chart-line"></i> Produktivitas:</span>
                        <span class="info-value">${wilayah.produktivitas} T/Ha</span>
                    </div>
                    <div class="wilayah-actions">
                        <button onclick="viewDetail(${wilayah.id})"><i class="fas fa-eye"></i> Detail</button>
                        <button onclick="editWilayah(${wilayah.id})"><i class="fas fa-edit"></i> Edit</button>
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
            const matchKomoditas = !komoditas || w.komoditas.toLowerCase().includes(komoditas);
            return matchSearch && matchKomoditas;
        });

        renderWilayah(filtered);
    }

    function viewDetail(id) {
        alert(`Membuka detail wilayah ${id}`);
    }

    function editWilayah(id) {
        alert(`Mengedit wilayah ${id}`);
    }

    // Initial render
    renderWilayah(wilayahData);

    // Real-time search
    document.getElementById('searchWilayah').addEventListener('keyup', filterWilayah);
    document.getElementById('filterKomoditas').addEventListener('change', filterWilayah);
</script>
@endsection
