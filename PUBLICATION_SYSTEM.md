# Sistem Publikasi Peta - Flow Control

## Overview
Sistem ini menambahkan kontrol publikasi untuk peta guest/publik. Admin harus klik tombol "Perbarui Peta Publik" sebelum data ditampilkan ke pengunjung.

## Komponen yang Dibuat

### 1. Database
- **Table**: `map_publications`
  - `id`: Primary key
  - `status`: draft/published
  - `published_at`: Timestamp publikasi
  - `published_by`: User ID admin yang publish
  - `notes`: Catatan publikasi
  - `created_at`, `updated_at`

### 2. Model
- **MapPublication.php**
  - Method `getLatestPublished()`: Ambil publikasi terakhir
  - Method `isDataPublished()`: Check apakah data sudah dipublikasi

### 3. Controller Methods (AdminController)
- **publishMap()**: Handle publish map action
- **getPublicationStatus()**: Get status publikasi terkini

### 4. Routes
- POST `/admin/publish-map`: Publish peta
- GET `/admin/publication-status`: Cek status publikasi

### 5. UI Changes

#### Admin Dashboard
- **Tombol "Perbarui Peta Publik"** di atas peta
- Status publikasi (terakhir dipublikasi kapan)
- Konfirmasi sebelum publish
- Feedback visual (loading, success)

#### Guest View (wilayah.blade.php)
- Alert jika data belum dipublikasi
- Pesan yang informatif

## Flow Kerja

### Untuk Admin:
1. Login ke dashboard admin
2. Upload CSV dengan data terbaru
3. Lihat preview peta di admin dashboard (realtime)
4. Jika sudah yakin, klik tombol **"Perbarui Peta Publik"**
5. Konfirmasi publikasi
6. Data sekarang visible untuk guest/publik

### Untuk Guest/Publik:
1. Buka halaman peta wilayah
2. Jika admin belum publish → Muncul alert "Peta belum tersedia"
3. Jika sudah publish → Peta tampil dengan data terbaru

## Security
- Hanya admin yang bisa publish
- Guest tidak bisa akses data unpublished
- WilayahController check authentication dan role

## Testing
```bash
# Check publication status
php test_publication.php

# Simulate publish (via UI)
1. Login sebagai admin
2. Klik "Perbarui Peta Publik"
3. Check guest view

# Reset untuk testing
# Hapus semua records di map_publications table
```

## Files Modified
1. `database/migrations/2025_12_24_025417_create_map_publications_table.php`
2. `app/Models/MapPublication.php`
3. `app/Http/Controllers/AdminController.php`
4. `app/Http/Controllers/WilayahController.php`
5. `routes/web.php`
6. `resources/views/admin/dashboard.blade.php`
7. `resources/views/pages/wilayah.blade.php`
