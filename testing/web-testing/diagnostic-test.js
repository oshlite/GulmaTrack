// QUICK DIAGNOSTICS TEST - COPY & PASTE THIS INTO BROWSER CONSOLE (F12)
// ===================================================================

console.clear();
console.log('%c🔍 MAP LOADING DIAGNOSTICS TEST', 'color: blue; font-size: 16px; font-weight: bold;');
console.log('%c=' .repeat(50), 'color: blue;');

// Test 1: Leaflet Library
console.group('1️⃣ LEAFLET LIBRARY CHECK');
try {
  const leafletStatus = typeof L === 'undefined' ? '❌ NOT LOADED' : '✅ LOADED';
  console.log('Leaflet status:', leafletStatus);
  if (typeof L !== 'undefined') {
    console.log('Leaflet version:', L.version);
    console.log('L.map available:', typeof L.map === 'function' ? '✅' : '❌');
  }
} catch(e) {
  console.error('Leaflet check failed:', e.message);
}
console.groupEnd();

// Test 2: Check Map Container
console.group('2️⃣ MAP CONTAINER CHECK');
try {
  const mapDiv = document.getElementById('map');
  if (!mapDiv) {
    console.error('❌ Map div not found with id="map"');
  } else {
    console.log('✅ Map div found');
    console.log('Map div dimensions:', {
      offsetWidth: mapDiv.offsetWidth,
      offsetHeight: mapDiv.offsetHeight,
      display: window.getComputedStyle(mapDiv).display,
      visibility: window.getComputedStyle(mapDiv).visibility
    });
    if (mapDiv.offsetWidth === 0 || mapDiv.offsetHeight === 0) {
      console.warn('⚠️ Map div has zero dimensions!');
    }
  }
} catch(e) {
  console.error('Map container check failed:', e.message);
}
console.groupEnd();

// Test 3: API Test
console.group('3️⃣ API /api/wilayah/data TEST');
(async () => {
  try {
    console.log('Fetching /api/wilayah/data...');
    const response = await fetch('/api/wilayah/data');
    console.log('Response status:', response.status);
    console.log('Response status text:', response.statusText);
    console.log('Response headers:', {
      'content-type': response.headers.get('content-type'),
      'content-length': response.headers.get('content-length')
    });
    
    if (!response.ok) {
      console.error(`❌ API returned ${response.status} ${response.statusText}`);
      return;
    }
    
    const data = await response.json();
    console.log('✅ API Response received');
    console.log('Response structure:', {
      has_data: !!data.data,
      data_is_array: Array.isArray(data.data),
      data_length: Array.isArray(data.data) ? data.data.length : 'N/A',
      total_wilayah: data.total_wilayah,
      crs: data.crs
    });
    
    if (Array.isArray(data.data) && data.data.length > 0) {
      console.log('First wilayah item:', data.data[0]);
    }
  } catch(error) {
    console.error('❌ API test failed:', error.message);
    console.error('Error:', error);
  }
})();
console.groupEnd();

// Test 4: Check Global Variables
console.group('4️⃣ GLOBAL VARIABLES CHECK');
try {
  console.log('map variable:', typeof map !== 'undefined' ? '✅ exists' : '❌ undefined');
  console.log('geoJsonLayers variable:', typeof geoJsonLayers !== 'undefined' ? '✅ exists' : '❌ undefined');
  console.log('loadAllWilayah function:', typeof loadAllWilayah === 'function' ? '✅ exists' : '❌ undefined');
  console.log('initMap function:', typeof initMap === 'function' ? '✅ exists' : '❌ undefined');
} catch(e) {
  console.error('Global variables check failed:', e.message);
}
console.groupEnd();

console.log('%c=' .repeat(50), 'color: blue;');
console.log('%c✅ DIAGNOSTICS COMPLETE - Check results above', 'color: green; font-size: 14px; font-weight: bold;');
