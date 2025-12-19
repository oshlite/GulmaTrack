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
    let map;
    let allLayers = {};
    let currentWilayah = null;
    
    const colorMap = {
        'Bersih': '#27ae60',
        'Ringan': '#f39c12',
        'Sedang': '#e67e22',
        'Berat': '#e74c3c',
        'Tidak Ada': '#95a5a6'
    };

    // Utility: Show toast notification
    function showNotification(message, type = 'info') {
        const bgColor = {
            'success': '#27ae60',
            'error': '#e74c3c',
            'warning': '#f39c12',
            'info': '#3498db'
        }[type] || '#3498db';

        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${bgColor};
            color: white;
            padding: 15px 20px;
            border-radius: 4px;
            z-index: 9999;
            max-width: 400px;
            font-size: 13px;
            animation: slideIn 0.3s ease;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Inisialisasi peta
    function initMap() {
        console.log('Initializing map...');
        const mapElement = document.getElementById('map');
        console.log('Map element:', mapElement);
        console.log('Map container:', document.getElementById('mapContainer'));
        
        if (!mapElement) {
            console.error('Map element #map not found!');
            return;
        }
        
        // Default center untuk Sumatera (daerah Lampung/Bengkulu)
        map = L.map('map').setView([-4.7, 108.2], 11);
        console.log('Map instance created:', map);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        console.log('Map initialized successfully at center: [-4.7, 108.2]');
    }

    // Get color based on kelas weed
    function getColor(kelasWeed) {
        return colorMap[kelasWeed] || '#3498db';
    }

    // Style untuk feature
    function featureStyle(feature) {
        const props = feature.properties;
        const kelasWeed = props.Kelas_weed || 'Tidak Ada';
        
        return {
            fillColor: getColor(kelasWeed),
            weight: 2,
            opacity: 0.8,
            color: '#2c3e50',
            fillOpacity: 0.7
        };
    }

    // Buat popup content
    function popupContent(props) {
        let html = `
            <div style="font-size: 12px; width: 280px;">
                <h4>
                    <i class="fas fa-map-pin"></i> ${props.Lokasi || 'Unknown'}
                </h4>
                
                <div class="popup-section">
                    <div><span class="popup-label">Wilayah:</span> <span class="popup-value">${props.Wilayah || '-'}</span></div>
                    <div><span class="popup-label">Area:</span> <span class="popup-value">${props.Area || '-'}</span></div>
                    <div><span class="popup-label">Status:</span> <span class="popup-value">${props.Status || '-'}</span></div>
                </div>

                <div class="popup-section">
                    <div><span class="popup-label">Luas Bruto:</span> <span class="popup-value">${props.Luas_Bruto || props.Bruto || '-'} Ha</span></div>
                    <div><span class="popup-label">Luas Netto:</span> <span class="popup-value">${props.Luas_Netto || props.Netto || props.netto || '-'} Ha</span></div>
                </div>
        `;

        if (props.Kelas_weed) {
            html += `
                <div class="popup-section">
                    <div><span class="popup-label">Kelas Gulma:</span> <span class="popup-value">${props.Kelas_weed}</span></div>
                    <div><span class="popup-label">TK/HA:</span> <span class="popup-value">${props['gulma_TK/HA'] || '-'}</span></div>
                    <div><span class="popup-label">Total TK:</span> <span class="popup-value">${props['gulma_TOTAL TK'] || '-'}</span></div>
                </div>
            `;
        }

        if (props.gulma_Penanggungjawab) {
            html += `
                <div class="popup-section">
                    <div><span class="popup-label">PJ:</span> <span class="popup-value">${props.gulma_Penanggungjawab}</span></div>
                    <div><span class="popup-label">Aktivitas:</span> <span class="popup-value">${props.gulma_ACTIVITAS || '-'}</span></div>
                </div>
            `;
        }

        html += '</div>';
        return html;
    }

    // Tampilkan peta wilayah terpilih
    function loadWilayahMap() {
        const wilayah = document.getElementById('wilayahSelect').value;
        if (!wilayah) {
            showNotification('Silakan pilih wilayah terlebih dahulu', 'warning');
            return;
        }

        // Clear existing layers
        Object.values(allLayers).forEach(layer => {
            try {
                map.removeLayer(layer);
            } catch (e) {}
        });

        showNotification('Memuat peta wilayah ' + wilayah + '...', 'info');
        
        const url = `/api/wilayah/geojson/${wilayah}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('GeoJSON loaded for wilayah', wilayah, data);
                
                if (!data.features || data.features.length === 0) {
                    showNotification('Tidak ada data untuk wilayah ini', 'warning');
                    return;
                }

                const geoJsonLayer = L.geoJSON(data, {
                    style: featureStyle,
                    onEachFeature: (feature, layer) => {
                        layer.bindPopup(popupContent(feature.properties));
                    }
                });
                
                geoJsonLayer.addTo(map);
                map.fitBounds(geoJsonLayer.getBounds(), { padding: [50, 50] });
                
                showNotification(`Wilayah ${wilayah} berhasil dimuat (${data.features.length} plot)`, 'success');
            })
            .catch(error => {
                console.error('Error loading wilayah:', error);
                showNotification('Error memuat peta: ' + error.message, 'error');
            });
    }

    // Load semua wilayah
    function loadAllWilayah() {
        Object.values(allLayers).forEach(layer => {
            try {
                map.removeLayer(layer);
            } catch (e) {}
        });
        allLayers = {};

        showNotification('Memuat semua wilayah...', 'info');
        console.log('Starting to load all wilayah...');

        const wilayahNumbers = [16, 17, 18, 19, 20, 21, 22, 23];
        let loadedCount = 0;
        let totalFeatures = 0;

        wilayahNumbers.forEach(num => {
            console.log(`Fetching wilayah ${num}...`);
            fetch(`/api/wilayah/geojson/${num}`)
                .then(response => {
                    console.log(`Response for wilayah ${num}:`, response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(`Wilayah ${num} loaded:`, data.features ? data.features.length : 0, 'features');
                    
                    if (data.features && data.features.length > 0) {
                        const geoJsonLayer = L.geoJSON(data, {
                            style: featureStyle,
                            onEachFeature: (feature, layer) => {
                                layer.bindPopup(popupContent(feature.properties));
                            }
                        });
                        
                        geoJsonLayer.addTo(map);
                        allLayers[num] = geoJsonLayer;
                        totalFeatures += data.features.length;
                    }
                    
                    loadedCount++;

                    if (loadedCount === wilayahNumbers.length) {
                        let bounds = L.latLngBounds();
                        let validBounds = false;
                        
                        Object.values(allLayers).forEach(layer => {
                            try {
                                const layerBounds = layer.getBounds();
                                if (layerBounds && layerBounds.isValid()) {
                                    bounds.extend(layerBounds);
                                    validBounds = true;
                                }
                            } catch (e) {
                                console.error('Error getting bounds:', e);
                            }
                        });
                        
                        if (validBounds) {
                            console.log('Fitting bounds:', bounds);
                            map.fitBounds(bounds, { padding: [50, 50] });
                        } else {
                            console.warn('No valid bounds found');
                        }
                        showNotification(`Semua wilayah berhasil dimuat (${totalFeatures} plot)`, 'success');
                    }
                })
                .catch(error => {
                    console.error(`Error loading wilayah ${num}:`, error);
                    loadedCount++;
                });
        });
    }

    // Load data summary
    function loadWilayahData() {
        console.log('Loading wilayah data...');
        fetch('/api/wilayah/data')
            .then(response => {
                console.log('Data API response:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Wilayah data loaded:', data);
                
                const wilayahData = data.data;
                document.getElementById('totalWilayah').textContent = wilayahData.length;

                let totalArea = 0;
                let totalFeatures = 0;
                
                wilayahData.forEach(w => {
                    totalArea += w.total_area;
                    totalFeatures += w.feature_count;
                });

                document.getElementById('totalArea').textContent = totalArea.toFixed(0) + ' Ha';
                document.getElementById('totalPlot').textContent = totalFeatures;

                // Populate select
                const select = document.getElementById('wilayahSelect');
                wilayahData.forEach(w => {
                    const option = document.createElement('option');
                    option.value = w.wilayah;
                    option.textContent = `Wilayah ${w.wilayah} (${w.feature_count} plot)`;
                    select.appendChild(option);
                });

                renderWilayahCards(wilayahData);
            })
            .catch(error => {
                console.error('Error loading data:', error);
                showNotification('Error memuat data: ' + error.message, 'error');
            });
    }

    // Render kartu wilayah
    function renderWilayahCards(data) {
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
                        <span class="info-label"><i class="fas fa-layer-group"></i> Plot:</span>
                        <span class="info-value">${wilayah.feature_count}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-ruler"></i> Total Area:</span>
                        <span class="info-value">${wilayah.total_area} Ha</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-cube"></i> Netto:</span>
                        <span class="info-value">${wilayah.total_netto_area} Ha</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-tag"></i> Status:</span>
                        <span class="info-value">${wilayah.status_types.join(', ')}</span>
                    </div>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    // Add CSS animations
    const styleElement = document.createElement('style');
    styleElement.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(styleElement);

    // Initialize on page load
    function initialize() {
        console.log('Page loaded, initializing...');
        console.log('Leaflet available?', typeof L !== 'undefined');
        
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded!');
            setTimeout(initialize, 100);
            return;
        }
        
        initMap();
        loadWilayahData();
        loadAllWilayah();
    }
    
    document.addEventListener('DOMContentLoaded', initialize);
</script>
@endsection
