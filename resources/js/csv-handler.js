/**
 * CSV Data Handler untuk Wilayah & Statistik View
 * Menampilkan data CSV yang sudah di-import ke dalam table
 */

// Global variable untuk simpan data
let allCsvData = [];
let currentPage = 1;
let perPage = 20;
let totalRecords = 0;

/**
 * Load data dari API CSV
 */
async function loadCsvData(filters = {}) {
    try {
        showLoading('#locationTableBody', 'Memuat data...');
        
        // Build query string
        const params = new URLSearchParams();
        
        if (filters.wilayah_id) params.append('wilayah_id', filters.wilayah_id);
        if (filters.kategori) params.append('kategori', filters.kategori);
        if (filters.activitas) params.append('activitas', filters.activitas);
        if (filters.from_date) params.append('from_date', filters.from_date);
        if (filters.to_date) params.append('to_date', filters.to_date);
        
        params.append('per_page', perPage);
        params.append('page', currentPage);
        
        const response = await fetch(`/api/csv/data?${params.toString()}`);
        const result = await response.json();
        
        if (!result.success) {
            showError('Tidak ada data CSV', 'Silakan upload file CSV terlebih dahulu');
            return;
        }
        
        allCsvData = result.data;
        totalRecords = result.pagination.total;
        
        renderTable(allCsvData);
        updatePaginationInfo(result.pagination);
        
    } catch (error) {
        console.error('Error loading CSV data:', error);
        showError('Error', 'Gagal memuat data CSV: ' + error.message);
    }
}

/**
 * Render data ke table
 */
function renderTable(data) {
    const tbody = document.getElementById('locationTableBody');
    
    if (!data || data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="13" style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 10px;"></i><br>
                    Tidak ada data untuk ditampilkan
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    let no = (currentPage - 1) * perPage + 1;
    
    data.forEach((row, index) => {
        const kategoriColor = getKategoriColor(row.kategori);
        
        html += `
            <tr>
                <td style="text-align: center; font-weight: 600; color: #666;">${no++}</td>
                <td style="text-align: center; font-size: 13px;">${row.pg || '-'}</td>
                <td style="text-align: center; font-size: 13px;">${row.fm || '-'}</td>
                <td style="text-align: center; font-weight: 500; color: #128241;">${row.wilayah_id || '-'}</td>
                <td style="text-align: left; font-size: 13px;">${row.seksi || '-'}</td>
                <td style="text-align: right; font-size: 13px;">${row.hasil ? parseFloat(row.hasil).toFixed(2) : '-'}</td>
                <td style="text-align: center; font-size: 13px;">${row.umur ? parseInt(row.umur) : '-'}</td>
                <td style="text-align: center; font-size: 12px; color: #666;">${row.tnm_sts || '-'}</td>
                <td style="text-align: center; font-size: 12px;">${row.activitas || '-'}</td>
                <td style="text-align: center;">
                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; color: white; background-color: ${kategoriColor};">
                        ${row.kategori || 'N/A'}
                    </span>
                </td>
                <td style="text-align: center; font-size: 12px;">${row.tanggal ? new Date(row.tanggal).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' }) : '-'}</td>
                <td style="text-align: right; font-size: 13px;">${row.total_tk ? parseInt(row.total_tk) : '-'}</td>
                <td style="text-align: right; font-size: 12px; color: #666;">${row.tk_ha ? parseFloat(row.tk_ha).toFixed(2) : '-'}</td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

/**
 * Update pagination info
 */
function updatePaginationInfo(pagination) {
    const infoText = document.getElementById('tableInfoText');
    if (infoText) {
        const from = pagination.from || 0;
        const to = pagination.to || 0;
        const total = pagination.total || 0;
        infoText.textContent = `Menampilkan ${from} - ${to} dari ${total} data`;
    }
}

/**
 * Get kategori color
 */
function getKategoriColor(kategori) {
    const colors = {
        'Bersih': '#3498db',
        'Ringan': '#27ae60',
        'Sedang': '#f39c12',
        'Berat': '#e74c3c',
        'Weeding': '#9b59b6'
    };
    return colors[kategori] || '#95a5a6';
}

/**
 * Export data ke CSV
 */
async function exportToCsv(filters = {}) {
    try {
        // Build query string
        const params = new URLSearchParams();
        
        if (filters.wilayah_id) params.append('wilayah_id', filters.wilayah_id);
        if (filters.kategori) params.append('kategori', filters.kategori);
        if (filters.activitas) params.append('activitas', filters.activitas);
        
        // Download file
        const url = `/api/csv/export?${params.toString()}`;
        window.location.href = url;
        
    } catch (error) {
        console.error('Error exporting CSV:', error);
        showError('Error', 'Gagal mengexport data: ' + error.message);
    }
}

/**
 * Load kategori list untuk filter
 */
async function loadKategoriList() {
    try {
        const response = await fetch('/api/csv/kategori-list');
        const result = await response.json();
        
        if (result.success && result.kategori) {
            populateKategoriButtons(result.kategori);
        }
    } catch (error) {
        console.error('Error loading kategori list:', error);
    }
}

/**
 * Load aktivitas list untuk filter
 */
async function loadActivitasList() {
    try {
        const response = await fetch('/api/csv/activitas-list');
        const result = await response.json();
        
        if (result.success && result.activitas) {
            populateActivitasButtons(result.activitas);
        }
    } catch (error) {
        console.error('Error loading aktivitas list:', error);
    }
}

/**
 * Populate kategori buttons (jika ada custom filter UI)
 */
function populateKategoriButtons(kategoriList) {
    // Implementasi jika ada custom kategori filter
    console.log('Available kategori:', kategoriList);
}

/**
 * Populate aktivitas buttons (jika ada custom filter UI)
 */
function populateActivitasButtons(aktivitasList) {
    // Implementasi jika ada custom aktivitas filter
    console.log('Available aktivitas:', aktivitasList);
}

/**
 * Show loading state
 */
function showLoading(selector, message = 'Loading...') {
    const elem = document.querySelector(selector);
    if (elem) {
        elem.innerHTML = `
            <tr>
                <td colspan="13" style="text-align: center; padding: 40px;">
                    <div style="display: inline-block;">
                        <div class="loading" style="margin: 0 auto;"></div>
                        <p style="margin-top: 15px; color: #999;">${message}</p>
                    </div>
                </td>
            </tr>
        `;
    }
}

/**
 * Show error notification
 */
function showError(title, message) {
    const tbody = document.getElementById('locationTableBody');
    if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="13" style="text-align: center; padding: 40px; color: #e74c3c;">
                    <i class="fas fa-exclamation-circle" style="font-size: 32px; margin-bottom: 10px;"></i><br>
                    <strong>${title}</strong><br>
                    <small>${message}</small>
                </td>
            </tr>
        `;
    }
}

/**
 * Initialize CSV data handler
 */
function initCsvHandler() {
    // Load data on initialization
    loadCsvData();
    loadKategoriList();
    loadActivitasList();
    
    // Set up event listeners jika ada filter controls
    const statusGulmaButtons = document.querySelectorAll('#statusGulmaGrid .grid-btn');
    statusGulmaButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const kategori = this.getAttribute('data-value');
            currentPage = 1;
            if (kategori && kategori !== 'belum_dimonitoring') {
                loadCsvData({ kategori: kategori });
            } else {
                loadCsvData();
            }
        });
    });
    
    // Search wilayah
    const searchInput = document.getElementById('searchWilayah');
    if (searchInput) {
        searchInput.addEventListener('change', function() {
            currentPage = 1;
            if (this.value) {
                loadCsvData({ wilayah_id: this.value });
            } else {
                loadCsvData();
            }
        });
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Delay slightly to ensure other scripts are loaded
    setTimeout(initCsvHandler, 500);
});
