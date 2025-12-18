@extends('layouts.app')

@section('title', 'Data Nanas')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-apple-alt"></i> Data Nanas</h1>
    <p>Informasi lengkap produksi, luas lahan, dan analisis nanas di berbagai wilayah</p>
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
            border-left: 5px solid var(--primary-color);
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
            color: var(--primary-color);
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
            <div class="value" id="totalLuas">2,450</div>
            <div class="subtitle">Hektar</div>
        </div>
    </div>

    <!-- Filter & Kontrol -->
    <div class="data-controls">
        <input type="text" id="searchNanas" placeholder="Cari wilayah..." style="flex: 1; min-width: 200px;">
        <select id="filterTahun" style="min-width: 120px;">
            <option value="2025">Tahun 2025</option>
            <option value="2024">Tahun 2024</option>
            <option value="2023">Tahun 2023</option>
            <option value="2022">Tahun 2022</option>
        </select>
        <select id="filterSemester" style="min-width: 120px;">
            <option value="">Semua Semester</option>
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
        </select>
        <button onclick="filterNanas()"><i class="fas fa-search"></i> Filter</button>
        <button class="add-btn" onclick="tambahDataNanas()"><i class="fas fa-plus"></i> Tambah Data</button>
    </div>

    <!-- Tabel Data Nanas -->
    <div class="table-responsive">
        <table id="nanasTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Wilayah</th>
                    <th>Luas Tanam (Ha)</th>
                    <th>Produksi (Ton)</th>
                    <th>Produktivitas (T/Ha)</th>
                    <th>Status Kesehatan</th>
                    <th>Petani Terdaftar</th>
                    <th>Tahun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="nanasBody">
                <!-- Data akan diisi via JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<script>
    const nanasData = [
        { id: 1, wilayah: 'Kabupaten Pelalawan', luas: 320, produksi: 2800, produktivitas: 8.75, status: 'good', petani: 125, tahun: 2025 },
        { id: 2, wilayah: 'Kabupaten Rokan Hilir', luas: 250, produksi: 2125, produktivitas: 8.5, status: 'good', petani: 95, tahun: 2025 },
        { id: 3, wilayah: 'Kabupaten Bengkalis', luas: 220, produksi: 1980, produktivitas: 9.0, status: 'good', petani: 88, tahun: 2025 },
        { id: 4, wilayah: 'Kabupaten Meranti', luas: 280, produksi: 2380, produktivitas: 8.5, status: 'warning', petani: 102, tahun: 2025 },
        { id: 5, wilayah: 'Kabupaten Indragiri Hulu', luas: 200, produksi: 1700, produktivitas: 8.5, status: 'good', petani: 78, tahun: 2025 },
        { id: 6, wilayah: 'Kabupaten Kuantan Singingi', luas: 180, produksi: 1530, produktivitas: 8.5, status: 'critical', petani: 68, tahun: 2025 },
    ];

    function getStatusBadge(status) {
        const badges = {
            'good': '<span class="status-badge status-good">✓ Baik</span>',
            'warning': '<span class="status-badge status-warning">⚠ Waspada</span>',
            'critical': '<span class="status-badge status-critical">✕ Kritis</span>'
        };
        return badges[status] || '';
    }

    function renderNanasTable(data) {
        const tbody = document.getElementById('nanasBody');
        tbody.innerHTML = '';

        data.forEach((item, index) => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td>${index + 1}</td>
                <td><strong>${item.wilayah}</strong></td>
                <td>${item.luas.toLocaleString('id-ID')}</td>
                <td>${item.produksi.toLocaleString('id-ID')}</td>
                <td>${item.produktivitas.toFixed(2)}</td>
                <td>${getStatusBadge(item.status)}</td>
                <td>${item.petani}</td>
                <td>${item.tahun}</td>
                <td>
                    <div class="row-actions">
                        <button onclick="viewDetailNanas(${item.id})"><i class="fas fa-eye"></i></button>
                        <button onclick="editNanas(${item.id})"><i class="fas fa-edit"></i></button>
                        <button onclick="deleteNanas(${item.id})"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            `;
        });
    }

    function filterNanas() {
        const search = document.getElementById('searchNanas').value.toLowerCase();
        const tahun = document.getElementById('filterTahun').value;

        const filtered = nanasData.filter(item => {
            const matchSearch = item.wilayah.toLowerCase().includes(search);
            const matchTahun = !tahun || item.tahun.toString() === tahun;
            return matchSearch && matchTahun;
        });

        renderNanasTable(filtered);
    }

    function tambahDataNanas() {
        alert('Form tambah data nanas akan ditampilkan');
    }

    function viewDetailNanas(id) {
        alert(`Membuka detail data nanas ID: ${id}`);
    }

    function editNanas(id) {
        alert(`Mengedit data nanas ID: ${id}`);
    }

    function deleteNanas(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            alert(`Data nanas ID: ${id} telah dihapus`);
        }
    }

    // Initial render
    renderNanasTable(nanasData);

    // Real-time search
    document.getElementById('searchNanas').addEventListener('keyup', filterNanas);
    document.getElementById('filterTahun').addEventListener('change', filterNanas);
</script>
@endsection
