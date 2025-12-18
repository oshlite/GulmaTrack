@extends('layouts.app')

@section('title', 'Data Pisang')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-leaf"></i> Data Pisang</h1>
    <p>Informasi lengkap produksi, luas lahan, dan analisis pisang di berbagai wilayah</p>
</div>

<div class="container">
    <style>
        .data-controls {
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

        .data-controls input,
        .data-controls select {
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }

        .data-controls button {
            padding: 10px 25px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .data-controls button:hover {
            background-color: var(--secondary-color);
        }

        .table-responsive {
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow);
            overflow-x: auto;
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #f39c12, #d68910);
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background-color: var(--light-color);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }

        .status-good {
            background-color: #d4edda;
            color: #155724;
        }

        .status-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-critical {
            background-color: #f8d7da;
            color: #721c24;
        }

        .row-actions {
            display: flex;
            gap: 8px;
        }

        .row-actions button {
            padding: 5px 10px;
            border: 1px solid var(--border-color);
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .row-actions button:hover {
            background-color: var(--light-color);
            border-color: var(--primary-color);
        }

        .summary-boxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            border-left: 5px solid #f39c12;
        }

        .summary-box h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-box .value {
            font-size: 32px;
            font-weight: bold;
            color: #f39c12;
        }

        .summary-box .subtitle {
            color: #999;
            font-size: 12px;
            margin-top: 5px;
        }

        .add-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .add-btn:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }
    </style>

    <!-- Ringkasan Data -->
    <div class="summary-boxes">
        <div class="summary-box">
            <h3>📌 Total Luas Tanam</h3>
            <div class="value" id="totalLuas">1,240</div>
            <div class="subtitle">Hektar</div>
        </div>
    </div>

    <!-- Filter & Kontrol -->
    <div class="data-controls">
        <input type="text" id="searchPisang" placeholder="Cari wilayah..." style="flex: 1; min-width: 200px;">
        <select id="filterTahun" style="min-width: 120px;">
            <option value="2025">Tahun 2025</option>
            <option value="2024">Tahun 2024</option>
            <option value="2023">Tahun 2023</option>
            <option value="2022">Tahun 2022</option>
        </select>
        <select id="filterVarietas" style="min-width: 120px;">
            <option value="">Semua Varietas</option>
            <option value="cavendish">Cavendish</option>
            <option value="ambon">Ambon</option>
            <option value="mas">Mas</option>
            <option value="lainnya">Lainnya</option>
        </select>
        <button onclick="filterPisang()"><i class="fas fa-search"></i> Filter</button>
        <button class="add-btn" onclick="tambahDataPisang()"><i class="fas fa-plus"></i> Tambah Data</button>
    </div>

    <!-- Tabel Data Pisang -->
    <div class="table-responsive">
        <table id="pisangTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Wilayah</th>
                    <th>Varietas</th>
                    <th>Luas Tanam (Ha)</th>
                    <th>Produksi (Ton)</th>
                    <th>Produktivitas (T/Ha)</th>
                    <th>Status Kesehatan</th>
                    <th>Petani Terdaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="pisangBody">
                <!-- Data akan diisi via JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<script>
    const pisangData = [
        { id: 1, wilayah: 'Kabupaten Rokan Hulu', varietas: 'Cavendish', luas: 180, produksi: 1440, produktivitas: 8.0, status: 'good', petani: 65 },
        { id: 2, wilayah: 'Kabupaten Bengkalis', varietas: 'Ambon', luas: 200, produksi: 1600, produktivitas: 8.0, status: 'good', petani: 72 },
        { id: 3, wilayah: 'Kabupaten Siak', varietas: 'Mas', luas: 160, produksi: 1280, produktivitas: 8.0, status: 'good', petani: 58 },
        { id: 4, wilayah: 'Kabupaten Indragiri Hulu', varietas: 'Cavendish', luas: 150, produksi: 1200, produktivitas: 8.0, status: 'warning', petani: 54 },
        { id: 5, wilayah: 'Kabupaten Indragiri Hilir', varietas: 'Ambon', luas: 190, produksi: 1520, produktivitas: 8.0, status: 'good', petani: 68 },
        { id: 6, wilayah: 'Kabupaten Kuantan Singingi', varietas: 'Mas', luas: 160, produksi: 1280, produktivitas: 8.0, status: 'critical', petani: 58 },
    ];

    function getStatusBadge(status) {
        const badges = {
            'good': '<span class="status-badge status-good">✓ Baik</span>',
            'warning': '<span class="status-badge status-warning">⚠ Waspada</span>',
            'critical': '<span class="status-badge status-critical">✕ Kritis</span>'
        };
        return badges[status] || '';
    }

    function renderPisangTable(data) {
        const tbody = document.getElementById('pisangBody');
        tbody.innerHTML = '';

        data.forEach((item, index) => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td>${index + 1}</td>
                <td><strong>${item.wilayah}</strong></td>
                <td>${item.varietas}</td>
                <td>${item.luas.toLocaleString('id-ID')}</td>
                <td>${item.produksi.toLocaleString('id-ID')}</td>
                <td>${item.produktivitas.toFixed(2)}</td>
                <td>${getStatusBadge(item.status)}</td>
                <td>${item.petani}</td>
                <td>
                    <div class="row-actions">
                        <button onclick="viewDetailPisang(${item.id})"><i class="fas fa-eye"></i></button>
                        <button onclick="editPisang(${item.id})"><i class="fas fa-edit"></i></button>
                        <button onclick="deletePisang(${item.id})"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            `;
        });
    }

    function filterPisang() {
        const search = document.getElementById('searchPisang').value.toLowerCase();
        const tahun = document.getElementById('filterTahun').value;
        const varietas = document.getElementById('filterVarietas').value;

        const filtered = pisangData.filter(item => {
            const matchSearch = item.wilayah.toLowerCase().includes(search);
            const matchVarietas = !varietas || item.varietas.toLowerCase().includes(varietas);
            return matchSearch && matchVarietas;
        });

        renderPisangTable(filtered);
    }

    function tambahDataPisang() {
        alert('Form tambah data pisang akan ditampilkan');
    }

    function viewDetailPisang(id) {
        alert(`Membuka detail data pisang ID: ${id}`);
    }

    function editPisang(id) {
        alert(`Mengedit data pisang ID: ${id}`);
    }

    function deletePisang(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            alert(`Data pisang ID: ${id} telah dihapus`);
        }
    }

    // Initial render
    renderPisangTable(pisangData);

    // Real-time search
    document.getElementById('searchPisang').addEventListener('keyup', filterPisang);
    document.getElementById('filterTahun').addEventListener('change', filterPisang);
    document.getElementById('filterVarietas').addEventListener('change', filterPisang);
</script>
@endsection
