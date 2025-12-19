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
                <i class="fas fa-info-circle"></i> Status Gulma
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #3498db;"></div>
                <span>Bersih</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #27ae60;"></div>
                <span>Ringan</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #f1c40f;"></div>
                <span>Sedang</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #e74c3c;"></div>
                <span>Berat</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #ecf0f1; border: 2px solid #bdc3c7;"></div>
                <span>Tidak Ada Data</span>
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
    // Data wilayah dari API
    let wilayahData = [];

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
                document.getElementById('mapContainer').scrollIntoView({ behavior: 'smooth' });
            };

            card.innerHTML = `
                <div class="wilayah-card-header">
                    <h3>Wilayah ${wilayah.wilayah}</h3>
                </div>
                <div class="wilayah-card-body">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-tag"></i> Komoditas:</span>
                        <span class="info-value">${wilayah.komoditas || 'Nanas'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-ruler"></i> Total Area:</span>
                        <span class="info-value">${wilayah.total_area} Ha</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-th"></i> Jumlah Plot:</span>
                        <span class="info-value">${wilayah.feature_count} Plot</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-chart-area"></i> Area Netto:</span>
                        <span class="info-value">${wilayah.total_netto_area || '-'} Ha</span>
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
            const wilayahName = `wilayah ${w.wilayah}`.toLowerCase();
            const matchSearch = wilayahName.includes(search) || w.wilayah.toString().includes(search);
            const matchKomoditas = !komoditas || (w.komoditas && w.komoditas.toLowerCase().includes(komoditas));
            return matchSearch && matchKomoditas;
        });

        renderWilayah(filtered);
    }

    // Real-time search
    document.getElementById('searchWilayah').addEventListener('keyup', filterWilayah);
    document.getElementById('filterKomoditas').addEventListener('change', filterWilayah);

    // ===================
    // MAP INITIALIZATION
    // ===================
    let map;
    let geoJsonLayers = {};
    let allWilayahData = [];

    // Initialize map
    function initMap() {
        // Check if map already exists
        if (map) {
            map.remove();
        }

        // Create map centered on Lampung Tengah
        map = L.map('map', {
            center: [-4.85, 105.0],
            zoom: 11,
            zoomControl: true,
            attributionControl: true
        });

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        console.log('Map initialized successfully');
    }

    // Load single wilayah
    function loadWilayahMap() {
        const wilayahNumber = document.getElementById('wilayahSelect').value;
        
        if (!wilayahNumber) {
            alert('Silakan pilih wilayah terlebih dahulu');
            return;
        }

        // Initialize map if not exists
        if (!map) {
            initMap();
        }

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
                            layer.bindPopup(createPopupContent(feature.properties));
                        }
                    }
                }).addTo(map);

                geoJsonLayers[wilayahNumber] = layer;

                // Fit map to bounds
                const bounds = layer.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }

                console.log(`Wilayah ${wilayahNumber} loaded: ${data.features.length} features`);
            })
            .catch(error => {
                console.error('Error loading wilayah:', error);
                alert('Gagal memuat data wilayah: ' + error.message);
            });
    }

    // Load all wilayah
    function loadAllWilayah() {
        // Initialize map if not exists
        if (!map) {
            initMap();
        }

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
                        const layer = L.geoJSON(data, {
                            style: function(feature) {
                                return getFeatureStyle(feature);
                            },
                            onEachFeature: function(feature, layer) {
                                if (feature.properties) {
                                    layer.bindPopup(createPopupContent(feature.properties));
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
                    
                    map.fitBounds(combinedBounds, { padding: [50, 50] });
                }

                console.log(`Loaded ${results.length} wilayah successfully`);
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
        let color = '#ecf0f1'; // default putih/abu untuk tidak ada data
        let weight = 2;
        
        // Check multiple status fields: Kelas_weed, gulma_KATEGORI, atau Status
        const status = (props.Kelas_weed || props.gulma_KATEGORI || props.Status || '').toLowerCase();
        
        if (status) {
            if (status.includes('bersih')) {
                color = '#3498db'; // Biru
            } else if (status.includes('ringan')) {
                color = '#27ae60'; // Hijau
            } else if (status.includes('sedang')) {
                color = '#f1c40f'; // Kuning
            } else if (status.includes('berat')) {
                color = '#e74c3c'; // Merah
            }
        }

        return {
            color: color,
            weight: weight,
            opacity: 0.9,
            fillColor: color,
            fillOpacity: 0.6
        };
    }

    // Create popup content
    function createPopupContent(props) {
        let html = '<div style="min-width: 250px; font-family: Arial, sans-serif;">';
        
        // Header dengan Lokasi
        if (props.Lokasi || props.LOKASI) {
            html += `<div style="background: var(--primary-color); color: white; padding: 10px; margin: -10px -10px 10px -10px; border-radius: 4px 4px 0 0;">
                <h4 style="margin: 0; font-size: 15px;">
                    <i class="fas fa-map-marker-alt"></i> ${props.Lokasi || props.LOKASI}
                </h4>
            </div>`;
        }

        // Status Gulma dengan warna
        const statusGulma = props.Kelas_weed || props.gulma_KATEGORI || props.Status || 'Tidak Ada Data';
        let statusColor = '#ecf0f1';
        if (statusGulma.toLowerCase().includes('bersih')) statusColor = '#3498db';
        else if (statusGulma.toLowerCase().includes('ringan')) statusColor = '#27ae60';
        else if (statusGulma.toLowerCase().includes('sedang')) statusColor = '#f1c40f';
        else if (statusGulma.toLowerCase().includes('berat')) statusColor = '#e74c3c';
        
        html += `<div style="background: ${statusColor}; color: white; padding: 8px; margin-bottom: 10px; border-radius: 4px; text-align: center; font-weight: bold;">
            <i class="fas fa-exclamation-circle"></i> ${statusGulma}
        </div>`;

        // Area information
        html += '<div style="margin-bottom: 8px;">';
        
        // Wilayah
        if (props.Wilayah || props.gulma_Wilayah) {
            html += `<div style="margin: 5px 0; display: flex; justify-content: space-between;">
                <span style="font-weight: 600; color: #555; font-size: 12px;">🗺️ Wilayah:</span>
                <span style="color: var(--primary-color); font-weight: bold; font-size: 12px;">${props.Wilayah || props.gulma_Wilayah}</span>
            </div>`;
        }
        
        // Luas Bruto
        const bruto = props.Luas_Bruto || props.Bruto || props.bruto;
        if (bruto) {
            const brutoValue = typeof bruto === 'string' ? bruto.replace(',', '.') : bruto;
            html += `<div style="margin: 5px 0; display: flex; justify-content: space-between;">
                <span style="font-weight: 600; color: #555; font-size: 12px;">📏 Luas Bruto:</span>
                <span style="color: var(--primary-color); font-weight: bold; font-size: 12px;">${brutoValue} Ha</span>
            </div>`;
        }

        // Luas Netto
        const netto = props.Luas_Netto || props.Netto || props.netto || props.gulma_Neto;
        if (netto) {
            const nettoValue = typeof netto === 'string' ? netto.replace(',', '.') : netto;
            html += `<div style="margin: 5px 0; display: flex; justify-content: space-between;">
                <span style="font-weight: 600; color: #555; font-size: 12px;">📐 Luas Netto:</span>
                <span style="color: var(--primary-color); font-weight: bold; font-size: 12px;">${nettoValue} Ha</span>
            </div>`;
        }

        // TK/HA
        if (props.tk_ha || props['gulma_TK/HA']) {
            html += `<div style="margin: 5px 0; display: flex; justify-content: space-between;">
                <span style="font-weight: 600; color: #555; font-size: 12px;">👷 TK/Ha:</span>
                <span style="color: var(--primary-color); font-weight: bold; font-size: 12px;">${props.tk_ha || props['gulma_TK/HA']}</span>
            </div>`;
        }

        html += '</div>';

        // Status/Komoditas
        if (props.Status) {
            html += `<div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #eee;">
                <span style="font-weight: 600; color: #555; font-size: 11px;">🌾 Komoditas:</span>
                <span style="color: #666; font-size: 11px; margin-left: 5px;">${props.Status}</span>
            </div>`;
        }

        html += '</div>';
        return html;
    }

    // Load wilayah data and populate select
    function loadWilayahDataAndStats() {
        fetch('/api/wilayah/data')
            .then(response => response.json())
            .then(data => {
                allWilayahData = data.data;
                wilayahData = data.data; // Store untuk filtering

                // Populate select dropdown
                const select = document.getElementById('wilayahSelect');
                select.innerHTML = '<option value="">-- Pilih Wilayah --</option>';
                
                data.data.forEach(wilayah => {
                    const option = document.createElement('option');
                    option.value = wilayah.wilayah;
                    option.textContent = `Wilayah ${wilayah.wilayah} (${wilayah.feature_count} plot, ${wilayah.total_area} Ha)`;
                    select.appendChild(option);
                });

                // Update statistics
                document.getElementById('totalWilayah').textContent = data.total_wilayah;
                
                let totalArea = 0;
                let totalPlot = 0;
                data.data.forEach(w => {
                    totalArea += w.total_area;
                    totalPlot += w.feature_count;
                });
                
                document.getElementById('totalArea').textContent = totalArea.toFixed(2) + ' Ha';
                document.getElementById('totalPlot').textContent = totalPlot;

                // Update grid with real data from API
                renderWilayah(data.data);

                console.log('Wilayah data loaded:', data);
            })
            .catch(error => {
                console.error('Error loading wilayah data:', error);
                const grid = document.getElementById('wilayahGrid');
                grid.innerHTML = '<div style="text-align: center; width: 100%; padding: 40px; color: #e74c3c;">⚠️ Gagal memuat data: ' + error.message + '</div>';
            });
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing wilayah page...');
        initMap();
        loadWilayahDataAndStats();
    });
</script>
@endsection
