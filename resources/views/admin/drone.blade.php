@extends('layouts.admin')

@section('title', 'Drone - Admin')

@section('content')

<style>
    :root {
        --primary-color: #128241;
        --secondary-color: #D6DF20;
        --accent-color: #FBA919;
        --light-bg: #f8f9fa;
        --text-color: #333;
        --border-color: #e0e0e0;
        --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .btn-download {
        background-color: #0066cc;
        color: white;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 30px;
        display: inline-block;
        transition: background-color 0.3s;
    }

    .btn-download:hover {
        background-color: #0052a3;
        text-decoration: none;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-sm {
        padding: 8px 12px;
        font-size: 12px;
        border-radius: 30px;
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid;
    }

    .alert-success {
        background-color: #d4edda;
        border-left-color: #28a745;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-left-color: #dc3545;
        color: #721c24;
    }

    /* Pagination Styling - Compact */
    .pagination {
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        display: flex;
        gap: 4px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    .pagination li {
        margin: 0 !important;
        padding: 0 !important;
    }

    .pagination a,
    .pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 8px !important;
        font-size: 12px !important;
        border: 1px solid #ddd;
        border-radius: 3px;
        text-decoration: none;
        color: #333;
        transition: all 0.2s;
        min-width: 30px;
        height: 30px;
    }

    .pagination a:hover {
        background-color: #f0f0f0;
        border-color: #999;
    }

    .pagination .active span {
        background-color: #128241 !important;
        color: white !important;
        border-color: #128241 !important;
    }

    .pagination .disabled span {
        color: #999;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin-top: 15px !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
        flex-wrap: wrap;
    }

    .pagination-info {
        font-size: 12px;
        color: #666;
        white-space: nowrap;
    }

    /* Remove excess spacing */
    .table-section {
        margin-bottom: 20px !important;
    }

    #droneTableContainer {
        margin-bottom: 0 !important;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        table {
            font-size: 12px;
        }

        table th,
        table td {
            padding: 10px 5px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-sm {
            width: 100%;
            text-align: center;
            border-radius: 30px;
        }

        .pagination a,
        .pagination span {
            padding: 5px 6px !important;
            font-size: 11px !important;
            min-width: 26px;
            height: 26px;
        }
    }
</style>

  <!-- Manajemen Drone Section -->
    <div class="admin-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-helicopter"></i> Manajemen Drone</h1>
            <p>Kelola dan upload dokumen perencanaan drone untuk pengendalian gulma</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-label">Total PDF</div>
                <div class="stat-value" id="statTotalPdf">{{ $totalPdf ?? 0 }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-label">Upload Terbaru</div>
                <div class="stat-value" id="statDroneUploadTerbaru">
                    @if ($droneUploadTerbaru)
                        {{ $droneUploadTerbaru->created_at->format('d-m-Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi Kesalahan!</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Upload Section -->
    <div class="form-section">
        <h2 class="form-section-title">
            <i class="fas fa-cloud-upload-alt"></i> Upload Drone PDF
        </h2>

        <form action="{{ route('admin.drone.store') }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="judul">Judul <span style="color: red;">*</span></label>
                    <input type="text" id="judul" name="judul" placeholder="Contoh: Perencanaan Gulma Wilayah A" value="{{ old('judul') }}" required>
                    @error('judul')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="lokasi">Lokasi <span style="color: red;">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: 506C1" value="{{ old('lokasi') }}" required>
                    @error('lokasi')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_perencanaan">Tanggal Penerbangan <span style="color: red;">*</span></label>
                    <input type="date" id="tanggal_perencanaan" name="tanggal_perencanaan" value="{{ old('tanggal_perencanaan') }}" required>
                    @error('tanggal_perencanaan')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="persen_gulma">Persen Gulma (%)</label>
                    <input type="number" id="persen_gulma" name="persen_gulma" placeholder="Contoh: 45.5" step="0.01" min="0" max="100" value="{{ old('persen_gulma') }}">
                    @error('persen_gulma')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="pdf_file">File PDF <span style="color: red;">*</span></label>
                    <div class="file-input-wrapper">
                        <label class="file-input-label">
                            <span id="file-label"><i class="fas fa-folder"></i> Pilih File PDF (Max 10MB)</span>
                            <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" required onchange="updateFileName(this)">
                        </label>
                        <div class="file-name" id="file-name-display"></div>
                    </div>
                    @error('pdf_file')
                        <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary" style="margin-top: 20px; width: 100%;">
                <i class="fas fa-check"></i> Upload Drone
            </button>
            <div id="uploadProgress" style="margin-top: 15px; display: none;">
                <div style="background: #e9ecef; border-radius: 6px; overflow: hidden; height: 8px;">
                    <div id="progressBar" style="background: linear-gradient(90deg, #128241, #D6DF20); height: 100%; width: 0%; transition: width 0.3s;"></div>
                </div>
                <p style="text-align: center; margin-top: 10px; font-size: 13px; color: #666;">
                    <i class="fas fa-spinner fa-spin" style="color: #128241;"></i> Sedang mengupload... <span id="progressText">0%</span>
                </p>
            </div>
        </form>

        <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const progressDiv = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'not-allowed';
            progressDiv.style.display = 'block';
            
            // Simulate progress
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 30;
                if (progress > 90) progress = 90;
                progressBar.style.width = progress + '%';
                progressText.textContent = Math.floor(progress) + '%';
            }, 500);
            
            // Cleanup saat form selesai
            this.addEventListener('submit', function cleanup() {
                clearInterval(interval);
            }, { once: true });
        });
        </script>
    </div>

    <!-- Data Table Section -->
    <div class="table-section">
        <h2 class="table-title">
            <i class="fas fa-table"></i> Daftar Drone
        </h2>

        <div id="droneTableContainer">
            <!-- Content loaded via AJAX -->
        </div>
    </div>

<script>
    function updateFileName(input) {
        const fileName = input.files[0]?.name || '';
        const fileNameDisplay = document.getElementById('file-name-display');
        const fileLabel = document.getElementById('file-label');

        if (fileName) {
            fileNameDisplay.innerHTML = '<i class="fas fa-check"></i> File terpilih: ' + fileName;
            fileLabel.innerHTML = '<i class="fas fa-check"></i> File siap diupload';
        } else {
            fileNameDisplay.textContent = '';
            fileLabel.innerHTML = '<i class="fas fa-folder"></i> Pilih File PDF (Max 10MB)';
        }
    }

    // AJAX Pagination untuk Drone List
    let currentDronePage = 1;

    async function loadDroneList(page = 1) {
        try {
            currentDronePage = page;
            const response = await fetch(`/admin/drone/api/list?page=${page}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            const container = document.getElementById('droneTableContainer');
            
            if (!data.drones || data.drones.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                        <h3>Tidak ada data drone</h3>
                        <p>Mulai upload drone PDF dengan form di atas</p>
                    </div>
                `;
                return;
            }

            let html = `
                <div style="overflow-x: auto; margin-bottom: 20px;">
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 50px;">No</th>
                                <th>Judul</th>
                                <th style="width: 150px;">Lokasi</th>
                                <th style="width: 150px;">Tgl Perencanaan</th>
                                <th style="width: 120px;">File</th>
                                <th style="width: 150px;">Dibuat</th>
                                <th style="text-align: center; width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            data.drones.forEach((drone, index) => {
                const no = (data.current_page - 1) * data.per_page + index + 1;
                const tanggal = new Date(drone.tanggal_perencanaan);
                const created = new Date(drone.created_at);
                
                html += `
                    <tr>
                        <td style="text-align: center;">${no}</td>
                        <td><strong>${drone.judul}</strong></td>
                        <td>${drone.lokasi}</td>
                        <td>${tanggal.toLocaleDateString('id-ID')}</td>
                        <td>
                            <a href="/drone/download/${drone.id}" class="btn-download" target="_blank">
                                Download
                            </a>
                        </td>
                        <td>${created.toLocaleDateString('id-ID', {hour: '2-digit', minute: '2-digit'})}</td>
                        <td style="text-align: center;">
                            <div class="action-buttons">
                                <button class="btn btn-danger btn-sm" onclick="deleteDrone(${drone.id})">Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Menampilkan ${data.from} hingga ${data.to} dari ${data.total} hasil
                    </div>
                    <div id="dronePagination"></div>
                </div>
            `;

            container.innerHTML = html;
            
            // Render pagination buttons
            renderDronePagination(data);
            
        } catch (error) {
            console.error('Error loading drones:', error);
            document.getElementById('droneTableContainer').innerHTML = '<p style="color: red;">⚠️ Error: ' + error.message + '</p>';
        }
    }

    function renderDronePagination(data) {
        const paginationDiv = document.getElementById('dronePagination');
        paginationDiv.innerHTML = '';

        const ul = document.createElement('ul');
        ul.className = 'pagination';

        // Previous button
        if (data.current_page > 1) {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#';
            a.textContent = '«';
            a.onclick = (e) => { e.preventDefault(); loadDroneList(data.current_page - 1); };
            li.appendChild(a);
            ul.appendChild(li);
        } else {
            const li = document.createElement('li');
            li.className = 'disabled';
            const span = document.createElement('span');
            span.textContent = '«';
            li.appendChild(span);
            ul.appendChild(li);
        }

        // Page numbers
        for (let i = 1; i <= data.last_page; i++) {
            if (i === data.current_page) {
                const li = document.createElement('li');
                li.className = 'active';
                const span = document.createElement('span');
                span.textContent = i;
                li.appendChild(span);
                ul.appendChild(li);
            } else if (i <= 3 || i >= data.last_page - 2 || Math.abs(i - data.current_page) <= 1) {
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = '#';
                a.textContent = i;
                a.onclick = (e) => { e.preventDefault(); loadDroneList(i); };
                li.appendChild(a);
                ul.appendChild(li);
            }
        }

        // Next button
        if (data.current_page < data.last_page) {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#';
            a.textContent = '»';
            a.onclick = (e) => { e.preventDefault(); loadDroneList(data.current_page + 1); };
            li.appendChild(a);
            ul.appendChild(li);
        } else {
            const li = document.createElement('li');
            li.className = 'disabled';
            const span = document.createElement('span');
            span.textContent = '»';
            li.appendChild(span);
            ul.appendChild(li);
        }

        paginationDiv.appendChild(ul);
    }

    function deleteDrone(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus drone ini?')) return;
        
        const token = document.querySelector('meta[name="csrf-token"]').content;
        
        fetch(`/admin/drone/${id}`, { 
            method: 'DELETE', 
            headers: { 
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            } 
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                loadDroneList(currentDronePage);
            }
        })
        .catch(err => console.error('Error:', err));
    }

    // Load initial data
    loadDroneList();
</script>
