# 🧪 TESTING & DEVELOPMENT FILES

Folder ini berisi file-file untuk testing, debugging, dan development GulmaTrack application.
Diorganisir per kategori untuk memudahkan pencarian dan penggunaan.

---

## 📁 Struktur Folder

### 📊 `api-testing/` (10 files)
**Tujuan:** Testing API endpoints & controller functionality  
**Isi File:**
- `test_api.php` - Basic API testing
- `test_api_admin.php` - Admin API endpoints testing
- `test_api_merge.php` - GeoJSON merge testing
- `test_api_merged.php` - Merged data testing
- `test_api_wilayah.php` - Wilayah API testing
- `test_controller_direct.php` - Direct controller testing
- `test_coordinate.php` - Coordinate transformation testing
- `test_geojson_api.php` - GeoJSON API testing
- `test_guest_api.php` - Guest API access testing
- `test_guest_vs_admin.php` - Role-based access testing

**Cara Pakai:**
```bash
php api-testing/test_api.php
php api-testing/test_coordinate.php
```

---

### 🗄️ `database-check/` (15 files)
**Tujuan:** Database structure validation & data inspection  
**Isi File:**
- `check_*.php` - Various database structure checks
  - `check_columns.php` - Column verification
  - `check_data.php` - Data integrity check
  - `check_data_count.php` - Record count validation
  - `check_error.php` - Error log checking
  - `check_geojson_props.php` - GeoJSON properties validation
  - `check_import_log.php` - Import log inspection
  - `check_kategori_colors.php` - Category color verification
  - `check_map_publications_structure.php` - Publication table check
  - `check_popup_data.php` - Popup data validation
  - `check_publications.php` - Publications check
  - `check_raw_data.php` - Raw data inspection
  - `check_wilayah.php` - Wilayah data check
  - `check_wilayah_data.php` - Wilayah data validation
- `list_tables.php` - Database tables listing
- `debug_query.php` - Query debugging tool

**Cara Pakai:**
```bash
# Check specific table
php database-check/check_columns.php

# List all tables
php database-check/list_tables.php

# Verify data count
php database-check/check_data_count.php
```

---

### 📥 `data-migration/` (4 files)
**Tujuan:** Data import, export, dan migration utilities  
**Isi File:**
- `import_gulma_csv.php` - Import CSV data ke database
- `reimport_gulma.php` - Re-import data (reset & reimport)
- `update_import_log.php` - Update import log records
- `update_tahun_import_log.php` - Update year information in logs

**Cara Pakai:**
```bash
# Import CSV file
php data-migration/import_gulma_csv.php

# Reimport dengan fresh start
php data-migration/reimport_gulma.php

# Update import logs
php data-migration/update_import_log.php
```

**⚠️ Hati-hati:** Scripts ini memodifikasi database. Backup sebelum menjalankan!

---

### 💾 `sample-data/` (4 files)
**Tujuan:** Sample data untuk testing & development  
**Isi File:**
- `contoh_data_import.csv` - Sample CSV format
- `gulma.csv` - Gulma data sample
- `sample_upload_wil16.csv` - Wilayah 16 sample data
- `roblox.png` - Sample image file

**Cara Pakai:**
```bash
# Use CSV samples untuk test upload
# Upload via admin panel atau import script

# Check format:
# Column 1: wilayah_id
# Column 2: id_feature
# Column 3: status_gulma
# ... dst
```

---

### 🌐 `web-testing/` (7 files)
**Tujuan:** Browser-based testing & frontend diagnostics  
**Isi File:**
- `test-api-fetch.html` - API fetch testing page
- `test-comprehensive.html` - Comprehensive testing
- `test-wilayah-api.html` - Wilayah API testing
- `test_fetch.html` - Basic fetch testing
- `test_map_diagnostics.html` - Map diagnostics
- `test_dashboard_fix.html` - Dashboard testing
- `diagnostic-test.js` - Diagnostic JavaScript utilities

**Cara Pakai:**
```bash
# Open di browser:
http://localhost:8000/public/web-testing/test-api-fetch.html

# Atau copy ke public folder:
cp web-testing/test-*.html public/
```

---

### 📦 `deprecated/` (7 files)
**Tujuan:** Obsolete files, backups, old test files  
**Isi File:**
- `gulmatrack_backup_2025-12-30_142818.backup` - Database backup
- `ExampleTest.php` - Old example test
- `peta-gulma.html` - Old map page
- `map-debug.html` - Old debug map
- `test_normalization.php` - Old normalization test
- `test_publication.php` - Old publication test
- `test_why_empty.php` - Old empty data test

**Status:** Archived, tidak digunakan lagi  
**Catatan:** Bisa dihapus jika sudah tidak perlu

---

### 📍 `data/` & `datafix/` (GeoJSON Data)
**Tujuan:** GeoJSON polygon data untuk mapping  
**Isi:**
- Wilayah boundary files (Wil16.geojson - Wil23.geojson)
- Backup/alternative versions

**Catatan:** Main GeoJSON data ada di `datala/` folder root

---

### ⚡ `geojson-data/` (Empty)
**Tujuan:** Reserved untuk GeoJSON testing & validation  
**Status:** Ready untuk diisi saat dibutuhkan

---

## 🚀 Quick Commands

```bash
# Navigate ke testing folder
cd testing/

# Test API endpoints
php api-testing/test_api.php

# Check database
php database-check/check_data_count.php

# Import sample data
php data-migration/import_gulma_csv.php < sample-data/contoh_data_import.csv

# Open web testing
# Buka browser → http://localhost:8000 dan browse ke testing folder
```

---

## 📋 Usage Guidelines

### Untuk Development Testing
1. Modify file di folder sesuai kebutuhan
2. Test via command line: `php testing/api-testing/test_*.php`
3. Log output untuk debugging

### Untuk Data Management
1. Use `data-migration/` untuk import/export
2. Always backup database first
3. Verify data with `database-check/` scripts

### Untuk Web/Frontend Testing
1. Open HTML files di browser
2. Check browser console (F12) untuk errors
3. Use network tab untuk inspect API calls

---

## ⚠️ Important Notes

- **Backup:** Selalu backup database sebelum menjalankan migration scripts
- **Not for Production:** Files ini hanya untuk development & testing
- **Sensitive Data:** Database backups mungkin berisi data sensitif - jangan di-commit
- **Clean Up:** Hapus files yang sudah tidak perlu untuk keep repo clean

---

**Last Updated:** 30 Desember 2025  
**Purpose:** Development, Testing & Debugging Support
