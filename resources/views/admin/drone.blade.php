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

    /* PDF Upload Area Styling */
    .pdf-upload-area {
        border: 3px dashed #128241;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        background-color: #f8fdf9;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .pdf-upload-area:hover {
        border-color: #D6DF20;
        background-color: #f0faff;
        box-shadow: 0 4px 12px rgba(18, 130, 65, 0.1);
    }

    .pdf-upload-area.drag-over {
        border-color: #D6DF20;
        background-color: #fffacd;
        box-shadow: 0 4px 16px rgba(214, 223, 32, 0.2);
    }

    .upload-icon {
        font-size: 48px;
        color: #FBA919;
        margin-bottom: 15px;
    }

    .upload-text {
        font-size: 16px;
        font-weight: 600;
        color: #128241;
        margin-bottom: 8px;
    }

    .upload-hint {
        font-size: 13px;
        color: #212121;
        margin-top: 8px;
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
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="pdf_file">File PDF <span style="color: red;">*</span></label>
                    <div class="pdf-upload-area" id="pdfUploadArea">
                        <div class="upload-icon"><i class="fas fa-file-pdf"></i></div>
                        <div class="upload-text">Klik atau Drag & Drop PDF di Sini</div>
                        <div class="upload-hint">Format: PDF | Maksimal 10MB</div>
                        <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" required hidden onchange="updateFileName(this); extractPdfData(this);">
                    </div>
                    <div class="file-name" id="file-name-display" style="margin-top: 15px; text-align: center;"></div>
                    <div id="pdf-extract-status" style="font-size: 12px; color: #666; margin-top: 8px; text-align: center;"></div>
                    @error('pdf_file')
                        <small style="color: red; display: block; margin-top: 8px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

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

            <button type="submit" id="submitBtn" class="btn btn-primary" style="margin-top: 20px; width: 100%; display: flex; align-items: center; justify-content: center;">
                Upload File Drone
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

        // Drag & Drop functionality for PDF
        const uploadArea = document.getElementById('pdfUploadArea');
        const fileInput = document.getElementById('pdf_file');

        uploadArea.addEventListener('click', () => fileInput.click());

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('drag-over');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const pdfFile = files[0];
                // Check if it's a PDF
                if (pdfFile.type === 'application/pdf' || pdfFile.name.endsWith('.pdf')) {
                    // Create a DataTransfer to set the file input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(pdfFile);
                    fileInput.files = dataTransfer.files;
                    
                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                } else {
                    alert('❌ Hanya file PDF yang diizinkan!');
                }
            }
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // Set PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    function updateFileName(input) {
        const fileName = input.files[0]?.name || '';
        const fileNameDisplay = document.getElementById('file-name-display');
        const uploadArea = document.getElementById('pdfUploadArea');

        if (fileName) {
            fileNameDisplay.innerHTML = '<strong style="color: #128241;">File terpilih:</strong> ' + fileName;
            uploadArea.style.borderColor = '#28a745';
            uploadArea.style.backgroundColor = '#f0fff0';
        } else {
            fileNameDisplay.textContent = '';
            uploadArea.style.borderColor = '#128241';
            uploadArea.style.backgroundColor = '#f8fdf9';
        }
    }

    // Extract PDF data and auto-fill form
    async function extractPdfData(input) {
        const file = input.files[0];
        if (!file) return;

        const statusDiv = document.getElementById('pdf-extract-status');
        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membaca PDF...';

        try {
            const arrayBuffer = await file.arrayBuffer();
            const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
            
            let fullText = '';
            
            // Extract text from first 3 pages (most info is usually there)
            const pagesToRead = Math.min(3, pdf.numPages);
            for (let i = 1; i <= pagesToRead; i++) {
                const page = await pdf.getPage(i);
                const textContent = await page.getTextContent();
                fullText += textContent.items.map(item => item.str).join(' ') + ' ';
            }

            // Parse extracted text
            const extractedData = parsePdfData(fullText);
            
            // Auto-fill form fields
            if (extractedData.lokasi) {
                document.getElementById('lokasi').value = extractedData.lokasi;
            }
            if (extractedData.tanggal) {
                document.getElementById('tanggal_perencanaan').value = extractedData.tanggal;
            }
            if (extractedData.persenGulma) {
                // Use toFixed to ensure dot (.) not comma (,) as decimal separator
                document.getElementById('persen_gulma').value = extractedData.persenGulma.toFixed(2);
            }
            if (extractedData.judul) {
                document.getElementById('judul').value = extractedData.judul;
            }

            // Show status
            const filledFields = [
                extractedData.judul ? '✓ Judul' : '',
                extractedData.lokasi ? '✓ Lokasi' : '',
                extractedData.tanggal ? '✓ Tgl Penerbangan' : '',
                extractedData.persenGulma ? '✓ Persen Gulma' : ''
            ].filter(x => x).join(', ');

            if (filledFields) {
                statusDiv.innerHTML = `<i class="fas fa-check" style="color: #28a745;"></i> Data terekstrak: ${filledFields}`;
            } else {
                statusDiv.innerHTML = '<i class="fas fa-info-circle" style="color: #ffc107;"></i> PDF dibaca tetapi data tidak ditemukan';
            }

        } catch (error) {
            statusDiv.innerHTML = `<i class="fas fa-exclamation-circle" style="color: #dc3545;"></i> Error membaca PDF`;
            console.error('PDF extraction error:', error);
        }
    }

    function parsePdfData(text) {
        const data = {
            judul: '',
            lokasi: '',
            tanggal: '',
            persenGulma: '',
            wilayah: ''
        };

        console.log('=== PDF EXTRACTION DEBUG ===');
        console.log('Full text:', text.substring(0, 2000));

        // Extract Wilayah and Lokasi - pattern: ": 19 : 546A2 :" (wilayah and lokasi separated by colons)
        let wilayahLokasiMatch = text.match(/:\s*(\d{1,2})\s*:\s*([A-Z0-9]{4,6})\s*:/);
        if (wilayahLokasiMatch) {
            data.wilayah = wilayahLokasiMatch[1].trim();
            data.lokasi = wilayahLokasiMatch[2].trim();
            console.log('✓ Wilayah matched:', data.wilayah);
            console.log('✓ Lokasi matched:', data.lokasi);
        } else {
            console.log('✗ Wilayah/Lokasi not found');
        }

        // Extract Tanggal - pattern: "06 - 12 - 2025" (with spaces around dashes)
        let tanggalMatch = text.match(/Tgl\s+Penerbangan[^:]*:\s*(\d{2})\s*-\s*(\d{2})\s*-\s*(\d{4})/i);
        if (!tanggalMatch) {
            // Alternative: look for date with spaces around dashes
            tanggalMatch = text.match(/:\s*(\d{2})\s*-\s*(\d{2})\s*-\s*(\d{4})/);
        }
        if (tanggalMatch) {
            const day = String(tanggalMatch[1]).padStart(2, '0');
            const month = String(tanggalMatch[2]).padStart(2, '0');
            const year = tanggalMatch[3];
            data.tanggal = `${year}-${month}-${day}`;
            console.log('✓ Tanggal matched:', data.tanggal);
        } else {
            console.log('✗ Tanggal not found');
        }

        // Extract Persen Gulma - pattern: ": X.XX %"
        let persenMatch = text.match(/Persen\s+Gulma[^%]*:\s*([\d.,]+)\s*%/i);
        if (!persenMatch) {
            // Alternative: look for ":" followed by number with dot/comma and "%"
            persenMatch = text.match(/:\s*([\d.,]+)\s*%/);
        }
        if (persenMatch) {
            const numStr = persenMatch[1].replace(',', '.');
            data.persenGulma = parseFloat(numStr);
            console.log('✓ Persen matched:', data.persenGulma);
        } else {
            console.log('✗ Persen not found');
        }

        // Extract Judul - use Wilayah number
        if (data.wilayah) {
            data.judul = `Perencanaan Gulma Wilayah ${data.wilayah}`;
            console.log('✓ Judul constructed:', data.judul);
        } else {
            console.log('✗ Judul not found (Wilayah not available)');
        }

        console.log('=== FINAL RESULT ===');
        console.log('Data:', data);
        return data;
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

@endsection
