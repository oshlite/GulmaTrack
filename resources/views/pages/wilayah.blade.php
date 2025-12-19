@extends('layouts.app')

@section('title', 'Wilayah')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<div class="page-header">
    <h1><i class="fas fa-map-marked-alt"></i> Peta Wilayah Produksi</h1>
    <p>Jelajahi distribusi dan informasi geografis area produksi hortikultura</p>
</div>

<div class="container">
    <style>
        #mapContainer {
            width: 100%;
            height: 600px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
            display: block;
        }

        #map {
            width: 100% !important;
            height: 100% !important;
            border-radius: 8px;
            display: block;
        }

        .leaflet-popup-content {
            max-height: 300px;
            overflow-y: auto;
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
            padding: 15px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            position: absolute;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 200px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .legend-item:last-child {
            margin-bottom: 0;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

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
            min-width: 150px;
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
            font-size: 13px;
        }

        .info-value {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 13px;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

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
    </div>

    <!-- Status Info -->
    <div style="background: #ecf0f1; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid var(--primary-color);">
        <i class="fas fa-info-circle" style="color: var(--primary-color); margin-right: 8px;"></i>
        <span style="font-size: 13px; color: #2c3e50;">
            <strong>Peta Interaktif QGIS</strong> - Data dikonversi dari UTM Zone 48S (EPSG:32748) ke WGS84 (EPSG:4326)
        </span>
    </div>

    <!-- Kontrol Peta -->
    <div class="wilayah-controls">
        <select id="wilayahSelect" style="flex: 1;">
            <option value="">-- Pilih Wilayah --</option>
        </select>
        <button onclick="loadWilayahMap()">
            <i class="fas fa-search"></i> Tampilkan Peta
        </button>
        <button onclick="loadAllWilayah()" style="background-color: #27ae60;">
            <i class="fas fa-globe"></i> Semua Wilayah
        </button>
    </div>

    <!-- Peta Interaktif -->
    <div id="mapContainer">
        <div id="map" style="border-radius: 8px;"></div>
        <div class="map-legend">
            <div style="font-weight: 600; margin-bottom: 15px; color: var(--primary-color);">
                <i class="fas fa-info-circle"></i> Legenda
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #3498db;"></div>
                <span>Area Produksi</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #27ae60;"></div>
                <span>Bersih</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #f39c12;"></div>
                <span>Ringan</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #e74c3c;"></div>
                <span>Tanpa Data</span>
            </div>
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
        <div style="text-align: center; width: 100%; padding: 40px;">
            <div class="loading" style="margin: 0 auto;"></div>
            <p style="margin-top: 20px; color: #999;">Memuat data wilayah...</p>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
            card.style.cursor = 'pointer';
            card.onclick = () => {
                document.getElementById('wilayahSelect').value = wilayah.wilayah;
                loadWilayahMap();
                document.getElementById('mapContainer').scrollIntoView({ behavior: 'smooth' });
            };

            card.innerHTML = `
                <div class="wilayah-card-header">
                    <h3>Wilayah ${wilayah.wilayah}</h3>
                </div>
                <div class="wilayah-card-body">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-tag"></i> Komoditas:</span>
                        <span class="info-value">${wilayah.komoditas}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-ruler"></i> Total Area:</span>
                        <span class="info-value">${wilayah.total_area} Ha</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-chart-bar"></i> Produksi:</span>
                        <span class="info-value">${wilayah.produksi} Ton</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-chart-line"></i> Produktivitas:</span>
                        <span class="info-value">${wilayah.produktivitas} T/Ha</span>
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

    // Initial render
    renderWilayah(wilayahData);

    // Real-time search
    document.getElementById('searchWilayah').addEventListener('keyup', filterWilayah);
    document.getElementById('filterKomoditas').addEventListener('change', filterWilayah);
</script>
@endsection
