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
        <div class="stat-box">
            <div class="stat-label">
                <i class="fas fa-leaf"></i> Plot Bersih
            </div>
            <div class="stat-number" id="totalBersih">-</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">
                <i class="fas fa-flask"></i> Plot Berat Gulma
            </div>
            <div class="stat-number" id="totalBerat">-</div>
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

    <div class="container">
    <style>
        #mapContainer {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            position: relative;
            z-index: 1;
            display: block;
            margin-bottom: 30px;
        }

        #map {
            width: 100% !important;
            height: 600px !important;
            border-radius: 8px;
            display: block;
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

        .map-container {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            position: relative;
            margin-bottom: 30px;
        }

        .wilayah-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        @media (max-width: 1400px) {
            .wilayah-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .wilayah-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .wilayah-grid {
                grid-template-columns: 1fr;
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
            box-shadow: 0 12px 24px rgba(25, 123, 64, 0.15);
            border-color: var(--primary-color);
        }

        .wilayah-card-header {
            background: linear-gradient(135deg, #1a8c4a, #155A31);
            color: white;
            padding: 18px 15px;
            text-align: center;
            position: relative;
        }

        .wilayah-card-header::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 8px solid #155A31;
        }

        .wilayah-card-header h3 {
            font-size: 18px;
            margin: 0;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .wilayah-card-body {
            padding: 18px 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 8px 12px;
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
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-label i {
            color: var(--primary-color);
            font-size: 14px;
        }

        .info-value {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 13px;
            background: white;
            padding: 4px 10px;
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
    </style>

    <!-- Map Container -->
    <div class="map-container">
        <div id="map"></div>
        <div class="map-legend">
            <h4><i class="fas fa-info-circle"></i> Status Gulma</h4>
            <div class="legend-item">
                <div class="legend-color" style="background: #3498db;"></div>
                <span><strong>Bersih</strong></span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #27ae60;"></div>
                <span><strong>Ringan</strong></span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #f1c40f;"></div>
                <span><strong>Sedang</strong></span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #e74c3c;"></div>
                <span><strong>Berat</strong></span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #ecf0f1; border-color: #8b8b8b;"></div>
                <span><strong>Tidak ada Data</strong></span>
            </div>
        </div>
    </div>
    </div>

    <!-- Daftar Wilayah -->
    <div style="margin: 30px 0 20px;">
        <h2 style="font-size: 24px; font-weight: 700; color: #2c3e50; margin-bottom: 8px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-map-marked-alt" style="color: var(--primary-color);"></i>
            Daftar Wilayah Produksi
        </h2>
        <p style="color: #7f8c8d; font-size: 13px; margin: 0;">Klik kartu wilayah untuk melihat detail peta</p>
    </div>
    
    <!-- Kontrol Wilayah -->
    <div class="wilayah-controls" style="margin-bottom: 25px;">
        <div style="flex: 1; position: relative;">
            <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999;"></i>
            <input type="text" id="searchWilayah" placeholder="Cari wilayah..." style="padding-left: 45px;">
        </div>
        <select id="filterKomoditas" style="min-width: 180px;">
            <option value="">🔍 Semua Kategori</option>
            <option value="ringan">🟢 Ringan</option>
            <option value="sedang">🟡 Sedang</option>
            <option value="berat">🔴 Berat</option>
            <option value="bersih">🔵 Bersih</option>
        </select>
        <button onclick="filterWilayah()" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); box-shadow: 0 4px 8px rgba(25, 123, 64, 0.2);">
            <i class="fas fa-filter"></i> Filter
        </button>
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
    // ===================
    // MAP INITIALIZATION
    // ===================
    let map;
    let geoJsonLayers = {};
    let allWilayahData = [];
    let wilayahData = [];

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
            attribution: '© OpenStreetMap contributors',
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
                    // Check if error is about unpublished data
                    if (data.error.includes('belum dipublikasikan')) {
                        alert('⚠️ Peta belum tersedia.\n\nData peta belum dipublikasikan oleh administrator. Silakan hubungi admin untuk memperbarui peta.');
                        return;
                    }
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
                            layer.bindPopup(createPopupContent(feature.properties), {
                                maxWidth: 350,
                                minWidth: 320,
                                maxHeight: 600,
                                autoPan: true
                            });
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
                                    layer.bindPopup(createPopupContent(feature.properties), {
                                        maxWidth: 350,
                                        minWidth: 320,
                                        maxHeight: 600,
                                        autoPan: true
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
        let fillColor = '#ecf0f1'; // default putih/abu untuk tidak ada data
        let borderColor = '#8b8b8b'; // default hitam untuk tidak ada data
        let weight = 2;
        
        // Check multiple status fields: Kelas_weed, gulma_KATEGORI, atau Status
        const status = (props.Kelas_weed || props.gulma_KATEGORI || props.Status || '').toLowerCase();
        
        if (status) {
            if (status.includes('bersih')) {
                fillColor = '#3498db'; // Biru
                borderColor = '#3498db';
            } else if (status.includes('ringan')) {
                fillColor = '#27ae60'; // Hijau
                borderColor = '#27ae60';
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

    // Create popup content
    function createPopupContent(props) {
        let html = '<div style="width: 320px; padding: 0;">';
        
        // Foto
        html += '<div style="width: 100%; height: 200px; border-radius: 8px 8px 0 0; overflow: hidden; margin-bottom: 10px;">';
        html += '<img src="/image/roblox.png" alt="Foto Lokasi" style="width: 100%; height: 100%; object-fit: cover;">';
        html += '</div>';
        
        html += '<div style="padding: 10px;">';
        
        // Header dengan Lokasi
        if (props.Lokasi || props.LOKASI) {
            html += `<h3 style="margin: 0 0 10px 0; color: #197B40; font-size: 16px;">📍 ${props.Lokasi || props.LOKASI}</h3>`;
        }

        // Status Gulma dengan warna
        const statusGulma = props.Kelas_weed || props.gulma_KATEGORI || props.Status || 'Tidak Ada Data';
        let statusColor = '#ecf0f1';
        let textColor = '#333333';
        
        if (statusGulma.toLowerCase().includes('bersih')) {
            statusColor = '#3498db';
            textColor = 'white';
        } else if (statusGulma.toLowerCase().includes('ringan')) {
            statusColor = '#27ae60';
            textColor = 'white';
        } else if (statusGulma.toLowerCase().includes('sedang')) {
            statusColor = '#f1c40f';
            textColor = '#333333';
        } else if (statusGulma.toLowerCase().includes('berat')) {
            statusColor = '#e74c3c';
            textColor = 'white';
        }
        
        html += `<div style="background: ${statusColor}; color: ${textColor}; padding: 8px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-weight: bold;">
            ${statusGulma}
        </div>`;

        // Area information
        html += '<table style="width: 100%; font-size: 13px; margin-bottom: 10px;">';
        
        // Wilayah
        if (props.Wilayah || props.gulma_Wilayah) {
            html += `<tr><td style="padding: 5px 0;"><strong>Wilayah:</strong></td><td style="padding: 5px 0; text-align: right;">${props.Wilayah || props.gulma_Wilayah}</td></tr>`;
        }
        
        // Luas Bruto
        const bruto = props.Luas_Bruto || props.Bruto || props.bruto;
        if (bruto) {
            const brutoValue = typeof bruto === 'string' ? bruto.replace(',', '.') : bruto;
            html += `<tr><td style="padding: 5px 0;"><strong>Luas Bruto:</strong></td><td style="padding: 5px 0; text-align: right;">${brutoValue} Ha</td></tr>`;
        }

        // Luas Netto
        const netto = props.Luas_Netto || props.Netto || props.netto || props.gulma_Neto;
        if (netto) {
            const nettoValue = typeof netto === 'string' ? netto.replace(',', '.') : netto;
            html += `<tr><td style="padding: 5px 0;"><strong>Luas Netto:</strong></td><td style="padding: 5px 0; text-align: right;">${nettoValue} Ha</td></tr>`;
        }

        // TK/HA
        if (props.tk_ha || props['gulma_TK/HA']) {
            html += `<tr><td style="padding: 5px 0;"><strong>TK/Ha:</strong></td><td style="padding: 5px 0; text-align: right;">${props.tk_ha || props['gulma_TK/HA']}</td></tr>`;
        }

        html += '</table>';
        
        // Status/Komoditas
        if (props.Status) {
            html += `<div style="margin-top: 10px; padding: 8px; background: #ecf0f1; border-radius: 4px; font-size: 12px;">
                <strong>🌾 Komoditas:</strong> ${props.Status}
            </div>`;
        }

        html += '</div></div>';
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
                let totalBersih = 0;
                let totalBerat = 0;

                data.data.forEach(w => {
                    totalArea += w.total_area;
                    totalPlot += w.feature_count;
                });

                document.getElementById('totalArea').textContent = totalArea.toFixed(2);
                document.getElementById('totalPlot').textContent = totalPlot;

                // Load all geojson to calculate gulma stats
                const wilayahNumbers = data.data.map(w => w.wilayah);
                const promises = wilayahNumbers.map(num => 
                    fetch(`/api/wilayah/geojson/${num}`)
                        .then(r => r.json())
                        .then(geojson => ({ wilayah: num, geojson }))
                );

                return Promise.all(promises).then(results => {
                    results.forEach(({ geojson }) => {
                        if (geojson.features) {
                            geojson.features.forEach(feature => {
                                const status = (feature.properties.Kelas_weed || feature.properties.gulma_KATEGORI || '').toLowerCase();
                                if (status.includes('bersih')) totalBersih++;
                                else if (status.includes('berat')) totalBerat++;
                            });
                        }
                    });

                    document.getElementById('totalBersih').textContent = totalBersih;
                    document.getElementById('totalBerat').textContent = totalBerat;

                    // Render wilayah grid
                    renderWilayah(data.data);

                    console.log('Wilayah data loaded:', data);
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

            card.innerHTML = `
                <div class="wilayah-card-header">
                    <h3>Wilayah ${wilayah.wilayah}</h3>
                    <div style="margin-top: 5px; font-size: 11px; opacity: 0.9;">
                        <i class="fas fa-map-pin"></i> Lampung Tengah
                    </div>
                </div>
                <div class="wilayah-card-body">
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-ruler-combined"></i>
                            <span>Luas</span>
                        </span>
                        <span class="info-value">
                            ${parseFloat(wilayah.total_area ?? 0).toFixed(2)} Ha
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-th-large"></i>
                            <span>Plot</span>
                        </span>
                        <span class="info-value">
                            ${wilayah.feature_count ?? 0}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-seedling"></i>
                            <span>Status</span>
                        </span>
                        <span class="info-value" style="background: #e8f5e9; color: #2e7d32;">
                            Aktif
                        </span>
                    </div>
                    <div style="margin-top: 15px; padding-top: 12px; border-top: 2px dashed #e0e0e0; text-align: center;">
                        <button onclick="event.stopPropagation(); document.getElementById('wilayahSelect').value = ${wilayah.wilayah}; loadWilayahMap(); document.getElementById('map').scrollIntoView({ behavior: 'smooth' });" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 12px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(25, 123, 64, 0.2);">
                            <i class="fas fa-map"></i> Lihat Peta
                        </button>
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
            const matchKomoditas = !komoditas || (w.kategori && w.kategori.toLowerCase().includes(komoditas));
            return matchSearch && matchKomoditas;
        });

        renderWilayah(filtered);
    }

    // Real-time search
    document.getElementById('searchWilayah').addEventListener('keyup', filterWilayah);
    document.getElementById('filterKomoditas').addEventListener('change', filterWilayah);

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing wilayah page...');
        initMap();
        loadWilayahDataAndStats();
    });
    // Load statistics and populate dropdown
        function loadStats() {
            fetch('/api/wilayah/data')
                .then(r => r.json())
                .then(data => {
                    // Update stats
                    document.getElementById('totalWilayah').textContent = data.total_wilayah;
                    
                    let totalArea = 0;
                    let totalPlot = 0;
                    data.data.forEach(w => {
                        totalArea += w.total_area;
                        totalPlot += w.feature_count;
                    });
                    
                    document.getElementById('totalArea').textContent = totalArea.toFixed(2);
                    document.getElementById('totalPlot').textContent = totalPlot;

                    // Populate dropdown
                    const select = document.getElementById('wilayahSelect');
                    data.data.forEach(wilayah => {
                        const option = document.createElement('option');
                        option.value = wilayah.wilayah;
                        option.textContent = `Wilayah ${wilayah.wilayah} (${wilayah.feature_count} plot, ${wilayah.total_area} Ha)`;
                        select.appendChild(option);
                    });

                    console.log('✓ Stats loaded');
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                });
        }

        // Initialize on page load
        window.addEventListener('DOMContentLoaded', () => {
            console.log('Initializing GulmaTrack Map...');
            initMap();
            loadStats();
            // Auto-load all wilayah on start
            setTimeout(() => loadAllWilayah(), 500);
        });
    </script>

@endsection
