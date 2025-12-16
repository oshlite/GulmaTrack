@extends('layouts.app')

@section('title', 'Tenaga Kerja')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-users"></i> Manajemen Tenaga Kerja</h1>
    <p>Kelola dan pantau data tenaga kerja perkebunan di berbagai wilayah</p>
</div>

<div class="container">
    <style>
        .workforce-controls {
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

        .workforce-controls input,
        .workforce-controls select {
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }

        .workforce-controls button {
            padding: 10px 25px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .workforce-controls button:hover {
            background-color: var(--secondary-color);
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

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: var(--shadow);
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark-color);
        }

        .card-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-info:last-child {
            border-bottom: none;
            margin-bottom: 15px;
        }

        .info-label {
            color: #666;
            font-size: 13px;
        }

        .info-value {
            font-weight: 600;
            color: var(--primary-color);
        }

        .card-actions {
            display: flex;
            gap: 8px;
        }

        .card-actions button {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .card-actions button:hover {
            background-color: var(--light-color);
            border-color: var(--primary-color);
        }

        .skills-list {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .skill-badge {
            background-color: var(--light-color);
            color: var(--text-color);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            border: 1px solid var(--border-color);
        }

        .detail-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .detail-section h3 {
            color: var(--title-color);
            margin-bottom: 20px;
            font-size: 20px;
            border-bottom: 3px solid var(--title-color);
            padding-bottom: 15px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table th {
            background-color: var(--light-color);
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
        }

        .detail-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .detail-table tr:hover {
            background-color: var(--light-color);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
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

    <!-- Ringkasan -->
    <div class="summary-boxes">
        <div class="summary-box">
            <h3><i class="fas fa-users"></i> Total Tenaga Kerja</h3>
            <div class="value">1,348</div>
            <div class="subtitle">Orang</div>
        </div>
        <div class="summary-box">
            <h3><i class="fas fa-map-pin"></i> Wilayah Terjangkau</h3>
            <div class="value">32</div>
            <div class="subtitle">Kabupaten/Kota</div>
        </div>
        <div class="summary-box">
            <h3><i class="fas fa-graduation-cap"></i> Rata-rata Keahlian</h3>
            <div class="value">3.5</div>
            <div class="subtitle">Skill/Orang</div>
        </div>
        <div class="summary-box">
            <h3><i class="fas fa-fire"></i> Tenaga Aktif</h3>
            <div class="value">1,242</div>
            <div class="subtitle">92.1%</div>
        </div>
    </div>

    <!-- Kontrol -->
    <div class="workforce-controls">
        <input type="text" id="searchTenaga" placeholder="Cari nama atau wilayah..." style="flex: 1; min-width: 200px;">
        <select id="filterWilayah" style="min-width: 150px;">
            <option value="">Semua Wilayah</option>
            <option value="pelalawan">Kabupaten Pelalawan</option>
            <option value="bengkalis">Kabupaten Bengkalis</option>
            <option value="rokan">Kabupaten Rokan</option>
            <option value="siak">Kabupaten Siak</option>
        </select>
        <select id="filterSkill" style="min-width: 150px;">
            <option value="">Semua Keahlian</option>
            <option value="tanam">Penanaman</option>
            <option value="panen">Pemanenan</option>
            <option value="post">Post Harvest</option>
            <option value="mesin">Mesin perkebunan</option>
        </select>
        <button onclick="filterTenaga()"><i class="fas fa-search"></i> Filter</button>
        <button class="add-btn" onclick="tambahTenagaKerja()"><i class="fas fa-plus"></i> Tambah Tenaga</button>
    </div>

    <!-- Daftar Tenaga Kerja -->
    <h2 style="font-size: 24px; margin: 30px 0 20px; color: var(--title-color);">Daftar Tenaga Kerja</h2>
    <div class="card-grid" id="tenagaGrid">
        <!-- Cards akan dimuat via JavaScript -->
    </div>

    <!-- Detail per Wilayah -->
    <div class="detail-section">
        <h3><i class="fas fa-chart-bar"></i> Detail Tenaga Kerja per Wilayah</h3>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Wilayah</th>
                    <th>Total Tenaga Kerja</th>
                    <th>Tenaga Aktif</th>
                    <th>Rata-rata Pengalaman</th>
                    <th>Keahlian Utama</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="wilayahTenagaTable">
                <!-- Data akan dimuat -->
            </tbody>
        </table>
    </div>

    <!-- Distribusi Keahlian -->
    <div class="detail-section">
        <h3><i class="fas fa-graduation-cap"></i> Distribusi Keahlian Tenaga Kerja</h3>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Jenis Keahlian</th>
                    <th>Jumlah Pekerja</th>
                    <th>Persentase</th>
                    <th>Pengalaman Rata-rata</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Penanaman</strong></td>
                    <td>425</td>
                    <td>31.6%</td>
                    <td>4.5 Tahun</td>
                </tr>
                <tr>
                    <td><strong>Pemanenan</strong></td>
                    <td>380</td>
                    <td>28.2%</td>
                    <td>3.8 Tahun</td>
                </tr>
                <tr>
                    <td><strong>Post Harvest</strong></td>
                    <td>325</td>
                    <td>24.1%</td>
                    <td>3.2 Tahun</td>
                </tr>
                <tr>
                    <td><strong>Mesin perkebunan</strong></td>
                    <td>218</td>
                    <td>16.2%</td>
                    <td>5.1 Tahun</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    const tenagaKerjaData = [
        { 
            id: 1, 
            nama: 'Budi Santoso', 
            wilayah: 'Pelalawan',
            jabatan: 'Mandor Tanam',
            pengalaman: '6 tahun',
            skills: ['Penanaman', 'Pupuk', 'Irigasi'],
            kontak: '08123456789',
            status: 'Aktif'
        },
        { 
            id: 2, 
            nama: 'Siti Rahmawati', 
            wilayah: 'Bengkalis',
            jabatan: 'Tenaga Panen',
            pengalaman: '3 tahun',
            skills: ['Pemanenan', 'Sortir', 'Handling'],
            kontak: '08234567891',
            status: 'Aktif'
        },
        { 
            id: 3, 
            nama: 'Ahmad Yusuf', 
            wilayah: 'Rokan Hilir',
            jabatan: 'Operator Mesin',
            pengalaman: '8 tahun',
            skills: ['Mesin perkebunan', 'Traktor', 'Perawatan'],
            kontak: '08345678912',
            status: 'Aktif'
        },
        { 
            id: 4, 
            nama: 'Dewi Lestari', 
            wilayah: 'Siak',
            jabatan: 'Tenaga Post Harvest',
            pengalaman: '4 tahun',
            skills: ['Post Harvest', 'Packaging', 'QC'],
            kontak: '08456789123',
            status: 'Aktif'
        },
        { 
            id: 5, 
            nama: 'Hendra Wijaya', 
            wilayah: 'Pelalawan',
            jabatan: 'Mandor Kebun',
            pengalaman: '10 tahun',
            skills: ['Penanaman', 'Pemeliharaan', 'Pest Control'],
            kontak: '08567891234',
            status: 'Aktif'
        },
        { 
            id: 6, 
            nama: 'Rina Kusuma', 
            wilayah: 'Bengkalis',
            jabatan: 'Tenaga Panen',
            pengalaman: '2 tahun',
            skills: ['Pemanenan', 'Handling'],
            kontak: '08678912345',
            status: 'Cuti'
        },
    ];

    function renderTenagaKerja(data) {
        const grid = document.getElementById('tenagaGrid');
        grid.innerHTML = '';

        data.forEach(item => {
            const card = document.createElement('div');
            card.className = 'card';
            card.innerHTML = `
                <div class="card-header">${item.nama}</div>
                <div class="card-info">
                    <span class="info-label"><i class="fas fa-briefcase"></i> Jabatan:</span>
                    <span class="info-value">${item.jabatan}</span>
                </div>
                <div class="card-info">
                    <span class="info-label"><i class="fas fa-map-pin"></i> Wilayah:</span>
                    <span class="info-value">${item.wilayah}</span>
                </div>
                <div class="card-info">
                    <span class="info-label"><i class="fas fa-hourglass"></i> Pengalaman:</span>
                    <span class="info-value">${item.pengalaman}</span>
                </div>
                <div class="card-info">
                    <span class="info-label"><i class="fas fa-phone"></i> Kontak:</span>
                    <span class="info-value">${item.kontak}</span>
                </div>
                <div class="card-info">
                    <span class="info-label">Status:</span>
                    <span class="status-badge ${item.status === 'Aktif' ? 'status-active' : 'status-inactive'}">${item.status}</span>
                </div>
                <div style="margin-bottom: 15px;">
                    <div class="info-label" style="margin-bottom: 8px;"><i class="fas fa-graduation-cap"></i> Keahlian:</div>
                    <div class="skills-list">
                        ${item.skills.map(skill => `<span class="skill-badge">${skill}</span>`).join('')}
                    </div>
                </div>
                <div class="card-actions">
                    <button onclick="viewDetail(${item.id})"><i class="fas fa-eye"></i></button>
                    <button onclick="editTenaga(${item.id})"><i class="fas fa-edit"></i></button>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    function renderWilayahTenaga() {
        const tbody = document.getElementById('wilayahTenagaTable');
        tbody.innerHTML = `
            <tr>
                <td><strong>Kabupaten Pelalawan</strong></td>
                <td>425</td>
                <td>392</td>
                <td>5.2 tahun</td>
                <td>Penanaman & Pemeliharaan</td>
                <td><span class="status-badge status-active">✓ Aktif</span></td>
            </tr>
            <tr>
                <td><strong>Kabupaten Bengkalis</strong></td>
                <td>380</td>
                <td>350</td>
                <td>4.1 tahun</td>
                <td>Panen & Post Harvest</td>
                <td><span class="status-badge status-active">✓ Aktif</span></td>
            </tr>
            <tr>
                <td><strong>Kabupaten Rokan Hilir</strong></td>
                <td>285</td>
                <td>268</td>
                <td>4.5 tahun</td>
                <td>Mesin & Operasional</td>
                <td><span class="status-badge status-active">✓ Aktif</span></td>
            </tr>
            <tr>
                <td><strong>Kabupaten Siak</strong></td>
                <td>258</td>
                <td>232</td>
                <td>3.8 tahun</td>
                <td>Umum</td>
                <td><span class="status-badge status-active">✓ Aktif</span></td>
            </tr>
        `;
    }

    function filterTenaga() {
        const search = document.getElementById('searchTenaga').value.toLowerCase();
        const wilayah = document.getElementById('filterWilayah').value;
        const skill = document.getElementById('filterSkill').value;

        const filtered = tenagaKerjaData.filter(item => {
            const matchSearch = item.nama.toLowerCase().includes(search) || 
                              item.wilayah.toLowerCase().includes(search);
            const matchWilayah = !wilayah || item.wilayah.toLowerCase().includes(wilayah);
            const matchSkill = !skill || item.skills.some(s => s.toLowerCase().includes(skill));
            return matchSearch && matchWilayah && matchSkill;
        });

        renderTenagaKerja(filtered);
    }

    function tambahTenagaKerja() {
        alert('Form tambah tenaga kerja akan ditampilkan');
    }

    function viewDetail(id) {
        alert(`Membuka detail tenaga kerja ID: ${id}`);
    }

    function editTenaga(id) {
        alert(`Mengedit tenaga kerja ID: ${id}`);
    }

    // Initial render
    renderTenagaKerja(tenagaKerjaData);
    renderWilayahTenaga();

    // Real-time search
    document.getElementById('searchTenaga').addEventListener('keyup', filterTenaga);
    document.getElementById('filterWilayah').addEventListener('change', filterTenaga);
    document.getElementById('filterSkill').addEventListener('change', filterTenaga);
</script>
@endsection
