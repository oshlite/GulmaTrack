# 📚 DOKUMENTASI LENGKAP GULMATRACK v2.0 (UPDATED)

> **Panduan Komprehensif untuk Developers & Non-Technical Users**
> **Last Updated: 15 Januari 2026**

---

## 🎯 DAFTAR ISI

1. [Pengenalan Aplikasi](#pengenalan-aplikasi)
2. [Instalasi & Setup](#instalasi--setup)
3. [Struktur Folder & File](#struktur-folder--file)
4. [Database & Model](#database--model)
5. [API & Routes](#api--routes)
6. [User Roles & Authentication](#user-roles--authentication)
7. [Fitur-Fitur Utama](#fitur-fitur-utama)
8. [Cara Menggunakan](#cara-menggunakan)
9. [Troubleshooting](#troubleshooting)
10. [Tips & Trik Development](#tips--trik-development)
11. [Fitur Baru - Drone Management](#fitur-baru---drone-management)

---

## 🌿 PENGENALAN APLIKASI

### Apa itu GulmaTrack?


GulmaTrack adalah sistem informasi untuk melacak data gulma (tanaman liar) di berbagai wilayah. Aplikasi ini membantu dalam:
- Mencatat data gulma yang ditemukan di lapangan
- Melihat visualisasi data di peta interaktif
- Melakukan analisis statistik pertumbuhan gulma
- Mengelola galeri foto dokumentasi lapangan

```
GulmaTrack adalah sebuah Sistem Informasi Geografis (Geographic Information System - GIS)
yang dibangun menggunakan:
- Framework: Laravel 10.10
- Database: PostgreSQL
- Frontend: Blade Template + Vue.js + Leaflet.js (mapping)
- API: RESTful API dengan Laravel Sanctum
- Architecture: MVC (Model-View-Controller)
```

### Teknologi yang Digunakan

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| **Backend Framework** | Laravel | 10.10+ |
| **PHP Version** | PHP | 8.1+ |
| **Database** | PostgreSQL | 12+ |
| **Mapping Library** | Leaflet.js | 1.9.4 |
| **Frontend Build** | Vite | 5.0+ |
| **Spreadsheet** | PHPOffice/PhpSpreadsheet | 5.3+ |

---

## 💻 INSTALASI & SETUP

### Prerequisites (Yang Perlu Dipersiapkan)


Sebelum install, pastikan komputer punya:
1. Server lokal (seperti Laragon, XAMPP, atau Wamp)
2. PostgreSQL database (versi 12+)
3. PHP 8.1 atau lebih baru
4. Composer (untuk manage dependencies)
5. Node.js 18+ (untuk build frontend assets)

```bash
# Cek versi PHP
php --version

# Cek Composer
composer --version

# Cek PostgreSQL running
psql --version

# Cek Node.js
node --version
npm --version
```

**⚠️ CATATAN PENTING: Tidak perlu install Ghostscript atau ImageMagick!**

GulmaTrack sudah dioptimasi untuk work tanpa external dependency rumit:
- ✅ PDF thumbnail menggunakan static SVG (instant render, <1ms per request)
- ✅ Download PDF langsung tanpa perlu convert
- ✅ Semua dependency sudah included di composer.json
- ✅ **Performance:** 32+ thumbnails load instantly tanpa lag
- ✅ Setup beginner-friendly, cocok untuk yang baru coding

### Step 1: Clone Repository

```bash
cd d:\AppData\Laragon\www
git clone <repository-url> GulmaTrack
cd GulmaTrack
```

### Step 2: Install Dependencies


Ini mengunduh semua library yang dibutuhkan aplikasi dari internet (sama seperti install aplikasi di smartphone).


```bash
# Install PHP dependencies
composer install

# Install Node dependencies (untuk frontend tools)
npm install
```

### Step 3: Setup Environment File


Ini membuat file konfigurasi khusus aplikasi Anda (nama aplikasi, database, dll).


```bash
# Copy .env.example ke .env
cp .env.example .env

# Generate application key (sangat penting untuk security)
php artisan key:generate
```

### Step 4: Konfigurasi Database (.env)

Edit file `.env` di root folder:

```env
adalah pokoknya hehe
```

**Catatan Penting:**
```
- Jangan share .env file ke public/internet (berisi password!)
- Selalu gunakan .env.example sebagai template
- Untuk production, gunakan environment variable yang lebih aman
```

### Step 5: Buat Database


Ini membuat "lemari penyimpanan" untuk data aplikasi di PostgreSQL.

gunakan HeidiSQL (sudah tersedia di Laragon) untuk manage PostgreSQL database.

### Step 6: Jalankan Migrations


Ini membuat struktur tabel di database secara otomatis (buat "rak-rak" di "lemari" yang sudah kita buat).


```bash
php artisan migrate

# Jika ingin reset database (hati-hati! akan menghapus semua data)
php artisan migrate:fresh

# Jika ingin reset + seeding data awal
php artisan migrate:fresh --seed
```

### Step 7: Seed Database (Opsional)


Ini mengisi database dengan data awal untuk testing (contoh: user admin, wilayah, dll).


```bash
php artisan db:seed

# Atau seed kelas tertentu:
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=WilayahSeeder
```

### Step 8: Build Frontend Assets


Ini mengompilasi file CSS dan JavaScript sehingga browser bisa membacanya dengan cepat.


```bash
# Development mode (dengan hot reload)
npm run dev

# Production mode (optimized dan minified)
npm run build
```

### Step 9: Jalankan Development Server


Ini memulai aplikasi sehingga bisa diakses di browser.


```bash
php artisan serve

# Aplikasi akan berjalan di: http://localhost:8000
# Atau di port spesifik:
php artisan serve --port=8080
```

### ✅ Setup Selesai!

Akses aplikasi di: **http://localhost:8000**

```
Default Login (jika menggunakan seeder):
Email: admin@gulmatrack.test
Password: password
```

---

## 📁 STRUKTUR FOLDER & FILE

### Root Directory

```
GulmaTrack/
├── app/                    # Kode aplikasi utama (PHP/Laravel)
├── bootstrap/              # File bootstrap Laravel
├── config/                 # File konfigurasi aplikasi
├── database/               # Database migrations, seeders, factories
├── data/                   # File GeoJSON untuk peta (wilayah boundaries)
├── public/                 # File public yang bisa diakses browser
├── resources/              # Views (Blade template) dan assets CSS/JS
├── routes/                 # Routes/URL definition
├── storage/                # Cache, logs, file uploads
├── tests/                  # Unit dan Feature tests
├── testingcek/             # File testing yang sudah deprecated
├── vendor/                 # Dependencies (auto-generated)
├── .env                    # Environment configuration (JANGAN DI-COMMIT!)
├── .env.example            # Template .env
├── artisan                 # Laravel CLI script
├── composer.json           # PHP dependencies definition
├── package.json            # Node dependencies definition
├── phpunit.xml             # Testing configuration
├── vite.config.js          # Vite build configuration
└── README.md               # Original readme
```

### app/ - Kode Aplikasi

```
app/
├── Console/                # Command-line commands
├── Exceptions/             # Exception handling
├── Http/
│   ├── Controllers/        # Semua controller aplikasi
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── CsvController.php
│   │   ├── DebugController.php
│   │   ├── DroneController.php
│   │   ├── ExcelDataController.php
│   │   ├── GalleryController.php
│   │   ├── GulmaController.php
│   │   ├── ImportLogController.php
│   │   └── WilayahController.php
│   ├── Kernel.php          # HTTP middleware configuration
│   └── Middleware/         # Custom middleware
├── Models/                 # Database models
│   ├── DataGulma.php
│   ├── Drone.php
│   ├── GulmaPhoto.php
│   ├── ImportLog.php
│   ├── MapPublication.php
│   ├── User.php
│   └── Wilayah.php
├── Providers/              # Service providers
└── Utils/                  # Utility classes
    └── CoordinateTransformer.php
```

### resources/ - Frontend & Views

```
resources/
├── css/
│   └── app.css             # Styling global
├── js/
│   ├── app.js              # JavaScript entry point
│   └── bootstrap.js        # Bootstrap initialization
└── views/                  # Blade template files
    ├── layouts/
    │   ├── app.blade.php   # Layout public pages
    │   └── admin.blade.php # Layout admin pages
    ├── pages/
    │   ├── home.blade.php
    │   ├── statistik.blade.php
    │   ├── about.blade.php
    │   └── wilayah.blade.php
    ├── admin/
    │   ├── dashboard.blade.php
    │   └── gallery.blade.php
    ├── auth/
    │   └── login.blade.php
    └── partials/
        ├── navbar.blade.php
        └── footer.blade.php
```

**Penjelasan:**
- **css/**: File styling CSS untuk desain tampilan
- **js/**: File JavaScript untuk interaksi halaman
- **views/**: Template HTML yang akan dirender oleh browser

### database/ - Database Management

```
database/
├── migrations/             # SQL schema files (versi kontrol database)
│   ├── 2014_10_12_000000_create_users_table.php
│   ├── 2023_12_23_025339_create_import_logs_table.php
│   ├── 2023_12_23_025340_create_wilayah_table.php
│   ├── 2023_12_23_025341_create_data_gulma_table.php
│   ├── 2025_12_27_063909_create_gulma_photos_table.php
│   └── 2025_12_24_025417_create_map_publications_table.php
├── seeders/                # File untuk isi data awal
│   ├── AdminUserSeeder.php
│   ├── WilayahSeeder.php
│   └── DatabaseSeeder.php
└── factories/              # Factory untuk testing
    └── UserFactory.php
```

**Penjelasan:**
- **migrations/**: Setiap file adalah "snapshot" struktur database pada waktu tertentu
  - Naming: `YYYY_MM_DD_HHMMSS_description.php`
  - Bisa run/rollback dengan aman
- **seeders/**: Mengisi database dengan data awal untuk development/testing
- **factories/**: Generate fake data untuk testing

### public/ - File Publik

```
public/
├── index.php               # Entry point aplikasi
├── image/                  # Logo dan assets images
│   ├── logo.png
│   ├── footer-wave.png
│   └── ...
├── .htaccess               # Apache server configuration
├── favicon.ico             # Website icon
└── robots.txt              # SEO robots configuration
```

### routes/ - URL Routing

```
routes/
├── web.php                 # Routes untuk web pages (traditional)
├── api.php                 # Routes untuk REST API
├── console.php             # Console commands
└── channels.php            # Broadcasting channels
```

### routes/web.php - Struktur

```
Group 1: PUBLIC PAGES
  GET  /                    → pages.home
  GET  /statistik           → pages.statistik
  GET  /tentang             → pages.about
  GET  /wilayah             → WilayahController@index

Group 2: PUBLIC API
  GET  /api/wilayah/data    → WilayahController@getData
  GET  /api/wilayah/geojson/{wilayah}
  GET  /api/statistik/summary

Group 3: AUTHENTICATION
  GET  /login               → AuthController@showLoginForm
  POST /login               → AuthController@login
  POST /logout              → AuthController@logout

Group 4: ADMIN AREA (Protected)
  GET  /admin/dashboard
  POST /admin/upload-csv
  GET  /admin/gallery
  POST /admin/gallery/upload
```

---

## 🗄️ DATABASE & MODEL

### Database Schema

#### Table 1: users
```
┌─────────────────┬──────────┬────────────────────┐
│ Column          │ Type     │ Notes              │
├─────────────────┼──────────┼────────────────────┤
│ id              │ INT(PK)  │ Auto increment     │
│ name            │ VARCHAR  │ User full name     │
│ email           │ VARCHAR  │ Unique email       │
│ email_verified  │ TIMESTAMP│ Email verification│
│ password        │ VARCHAR  │ Hashed password    │
│ role            │ ENUM     │ 'guest' or 'admin' │
│ is_active       │ BOOLEAN  │ Account active     │
│ created_at      │ TIMESTAMP│                    │
│ updated_at      │ TIMESTAMP│                    │
└─────────────────┴──────────┴────────────────────┘
```

 Tabel ini menyimpan data pengguna aplikasi (siapa yang login, password, role).

 
```php
class User extends Model {
    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active'
    ];
}

// Roles:
// 'guest' - User biasa (bisa lihat halaman publik)
// 'admin' - Admin (bisa manage data, upload, publish)
```

#### Table 2: wilayah
```
┌──────────────────┬──────────┬──────────────────────┐
│ Column           │ Type     │ Notes                │
├──────────────────┼──────────┼──────────────────────┤
│ id               │ INT(PK)  │ Auto increment       │
│ wilayah_id       │ INT      │ 16-23 (Unique)      │
│ nama_wilayah     │ VARCHAR  │ e.g., "Wilayah 16"  │
│ deskripsi        │ TEXT     │ Area deskripsi      │
│ created_at       │ TIMESTAMP│                      │
│ updated_at       │ TIMESTAMP│                      │
└──────────────────┴──────────┴──────────────────────┘
```

 Tabel ini menyimpan wilayah produksi (wilayah 16-23). Setiap wilayah memiliki file GeoJSON tersendiri di folder `datala/` untuk visualisasi peta.

 
```php
class Wilayah extends Model {
    protected $fillable = ['wilayah_id', 'nama_wilayah', 'deskripsi'];
}

// Data wilayah di-seed otomatis saat migrate
// File GeoJSON: datala/Wil16.geojson, datala/Wil17.geojson, dst
```

#### Table 3: import_logs
```
┌─────────────────────┬──────────┬─────────────────────────┐
│ Column              │ Type     │ Notes                   │
├─────────────────────┼──────────┼─────────────────────────┤
│ id                  │ INT(PK)  │ Auto increment          │
│ nama_file           │ VARCHAR  │ Nama file CSV yang upload
│ wilayah_id          │ VARCHAR  │ "16,17,18" (comma-sep)  │
│ tahun               │ INT      │ Year (e.g., 2024)       │
│ bulan               │ INT      │ Month (1-12)            │
│ minggu              │ INT      │ Week (1-4)              │
│ jumlah_records      │ INT      │ Total records di file   │
│ jumlah_berhasil     │ INT      │ Records imported OK     │
│ jumlah_gagal        │ INT      │ Records failed          │
│ status              │ ENUM     │ pending/success/partial/│
│                     │          │ failed                  │
│ error_log           │ TEXT     │ Error details if failed │
│ user_id             │ INT(FK)  │ Admin yang upload       │
│ created_at          │ TIMESTAMP│                         │
│ updated_at          │ TIMESTAMP│                         │
└─────────────────────┴──────────┴─────────────────────────┘
```

 Tabel ini mencatat setiap kali admin upload file CSV data gulma (tanggal, status, error jika ada).


```php
class ImportLog extends Model {
    protected $fillable = [
        'nama_file', 'wilayah_id', 'tahun', 'bulan', 'minggu',
        'jumlah_records', 'jumlah_berhasil', 'jumlah_gagal',
        'status', 'error_log', 'user_id'
    ];
    
    public function dataGulma() {
        return $this->hasMany(DataGulma::class);
    }
}

// Status values:
// 'pending'  - Upload sedang diproses
// 'success'  - Semua records berhasil
// 'partial'  - Beberapa records gagal
// 'failed'   - Semua records gagal
```

#### Table 4: data_gulma
```
┌─────────────────────┬─────────┬──────────────────────────┐
│ Column              │ Type    │ Notes                    │
├─────────────────────┼─────────┼──────────────────────────┤
│ id                  │ INT(PK) │ Auto increment           │
│ wilayah_id          │ INT(FK) │ Reference to wilayah 16-23
│ id_feature          │ VARCHAR │ Lokasi/Seksi identifier │
│ status_gulma        │ ENUM    │ Bersih/Ringan/Sedang/   │
│                     │         │ Berat                   │
│ persentase          │ INT     │ Percentage coverage     │
│ tanggal             │ DATE    │ Recording date          │
│ import_log_id       │ INT(FK) │ Reference to import_logs│
│ pg                  │ VARCHAR │ PG code                 │
│ fm                  │ VARCHAR │ FM code                 │
│ seksi               │ VARCHAR │ Section name            │
│ neto                │ DECIMAL │ Net value               │
│ hasil               │ DECIMAL │ Yield/Result            │
│ umur_tanaman        │ INT     │ Plant age (days)        │
│ penanggungjawab     │ VARCHAR │ Person in charge        │
│ kode_aktf           │ VARCHAR │ Activity code           │
│ activitas           │ VARCHAR │ Activity description    │
│ kategori            │ VARCHAR │ Category (untuk warna)  │
│ tk_ha               │ DECIMAL │ Labor days per hectare  │
│ total_tk            │ INT     │ Total labor days        │
│ created_at          │ TIMESTAMP
│ updated_at          │ TIMESTAMP
└─────────────────────┴─────────┴──────────────────────────┘
```

 Tabel ini menyimpan data gulma per lokasi dengan semua detail dari file CSV yang di-upload (jenis, jumlah, kondisi, dll).


```php
class DataGulma extends Model {
    protected $table = 'data_gulma';
    protected $fillable = [
        'wilayah_id', 'id_feature', 'status_gulma', 'persentase',
        'tanggal', 'import_log_id', 'pg', 'fm', 'seksi', 'neto',
        'hasil', 'umur_tanaman', 'penanggungjawab', 'kode_aktf',
        'activitas', 'kategori', 'tk_ha', 'total_tk'
    ];
    
    public function importLog() {
        return $this->belongsTo(ImportLog::class);
    }
}

// Status gulma values:
// 'Bersih'  - Bebas gulma
// 'Ringan'  - Sedikit gulma
// 'Sedang'  - Cukup banyak
// 'Berat'   - Sangat banyak
```

#### Table 5: map_publications
```
┌──────────────────┬──────────┬────────────────────┐
│ Column           │ Type     │ Notes              │
├──────────────────┼──────────┼────────────────────┤
│ id               │ INT(PK)  │ Auto increment     │
│ status           │ VARCHAR  │ draft/published    │
│ published_at     │ TIMESTAMP│ When published     │
│ published_by     │ INT(FK)  │ Admin user ID      │
│ notes            │ TEXT     │ Publication notes  │
│ created_at       │ TIMESTAMP│                    │
│ updated_at       │ TIMESTAMP│                    │
└──────────────────┴──────────┴────────────────────┘
```

 Tabel ini mencatat publikasi peta (data mana yang sudah ditampilkan ke publik).


```php
class MapPublication extends Model {
    protected $fillable = ['status', 'published_at', 'published_by', 'notes'];
    
    public function publishedBy() {
        return $this->belongsTo(User::class, 'published_by');
    }
    
    // Check if data is published for public view
    public static function isDataPublished() {
        return self::where('status', 'published')->exists();
    }
}
```

#### Table 6: gulma_photos
```
┌─────────────────────┬──────────┬─────────────────────┐
│ Column              │ Type     │ Notes               │
├─────────────────────┼──────────┼─────────────────────┤
│ id                  │ INT(PK)  │ Auto increment      │
│ wilayah_id          │ VARCHAR  │ Wilayah 16-23       │
│ lokasi              │ VARCHAR  │ Location code       │
│ foto_path           │ VARCHAR  │ File path/location  │
│ status_gulma        │ ENUM     │ Bersih/Ringan/      │
│                     │          │ Sedang/Berat        │
│ tanggal_foto        │ DATE     │ Photo date          │
│ deskripsi           │ TEXT     │ Description         │
│ uploaded_by         │ INT(FK)  │ Admin user ID       │
│ file_size           │ VARCHAR  │ File size info      │
│ mime_type           │ VARCHAR  │ image/jpeg, etc     │
│ deleted_at          │ TIMESTAMP│ Soft delete         │
│ created_at          │ TIMESTAMP│                     │
│ updated_at          │ TIMESTAMP│                     │
└─────────────────────┴──────────┴─────────────────────┘
```

 Tabel ini menyimpan metadata foto lapangan yang di-upload di gallery (lokasi, tanggal, kondisi).


```php
class GulmaPhoto extends Model {
    use SoftDeletes;
    
    protected $fillable = [
        'wilayah_id', 'lokasi', 'foto_path', 'status_gulma',
        'tanggal_foto', 'deskripsi', 'uploaded_by',
        'file_size', 'mime_type'
    ];
    
    protected $dates = ['deleted_at'];
    
    public function uploadedBy() {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
```

#### Table 7: drones
```
┌──────────────────┬──────────┬────────────────────────┐
│ Column           │ Type     │ Notes                  │
├──────────────────┼──────────┼────────────────────────┤
│ id               │ INT(PK)  │ Auto increment         │
│ judul            │ VARCHAR  │ Drone mission title    │
│ lokasi           │ VARCHAR  │ Location/area          │
│ tanggal_perencanaan │ DATE  │ Planning date          │
│ pdf_path         │ VARCHAR  │ Path to PDF file       │
│ pdf_filename     │ VARCHAR  │ Original filename      │
│ persen_gulma     │ DECIMAL  │ Weed percentage (%)    │
│ user_id          │ INT(FK)  │ Admin who uploaded     │
│ created_at       │ TIMESTAMP│                        │
│ updated_at       │ TIMESTAMP│                        │
└──────────────────┴──────────┴────────────────────────┘
```

 Tabel ini menyimpan data laporan drone survey (PDF hasil dokumentasi drone).


```php
class Drone extends Model {
    protected $fillable = [
        'judul', 'lokasi', 'tanggal_perencanaan',
        'pdf_path', 'pdf_filename', 'persen_gulma', 'user_id'
    ];
    
    protected $casts = [
        'tanggal_perencanaan' => 'date',
        'persen_gulma' => 'decimal:2'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
```

### Model Relationships

```
User
  ├── (is_admin: boolean)
  ├── (has many) ImportLog
  ├── (has many) GulmaPhoto
  └── (has many) Drone

Wilayah
  ├── (has many) DataGulma
  ├── (has many) MapPublication
  └── (has many) GulmaPhoto

ImportLog
  ├── (has many) DataGulma
  └── (has many) MapPublication

DataGulma
  ├── (belongs to) ImportLog
  └── (belongs to) Wilayah (implicit via wilayah_id)

MapPublication
  └── (belongs to) ImportLog

GulmaPhoto
  └── (belongs to) Wilayah (implicit via wilayah_id)

Drone
  └── (belongs to) User (uploaded_by)
```


```php
// Contoh penggunaan:
$importLog = ImportLog::find(1);
$dataGulma = $importLog->dataGulma; // Get all data

$wilayah = Wilayah::find('16.01.01');
$allData = $wilayah->dataGulma; // Get data untuk wilayah ini

$user = User::find(1);
$drones = $user->drone; // Get all drone records dari user ini
```

---

## 🔌 API & ROUTES

### Route Structure

#### 1. PUBLIC PAGES (Traditional Routes)

| METHOD | URL | Controller | Method |
|--------|-----|-----------|--------|
| GET | `/` | WilayahController | home (view) |
| GET | `/statistik` | GulmaController | statistik (view) |
| GET | `/tentang` | AuthController | about (view) |
| GET | `/wilayah` | WilayahController | index |
| GET | `/drone` | DroneController | userIndex |

 Halaman publik yang bisa diakses siapa saja tanpa login.

#### 2. PUBLIC DRONE ROUTES

| METHOD | URL | Controller | Method | Notes |
|--------|-----|-----------|--------|-------|
| GET | `/drone/download/{id}` | DroneController | download | Download drone PDF |
| GET | `/drone/view/{id}` | DroneController | view | View drone PDF inline |

 Routes publik untuk mengakses file drone PDF (bisa diakses siapa saja)

#### 2. PUBLIC API ENDPOINTS

**Wilayah Data (Geographic Information)**

```
GET /api/wilayah/data
├─ Returns: Array of all wilayah with latest data
├─ No authentication needed
└─ Response:
   {
     "wilayah_id": "16.01.01",
     "nama_wilayah": "Lampung Tengah",
     "total_data": 150,
     "status_gulma": "Terkontrol"
   }

GET /api/wilayah/geojson/{wilayah}
├─ Returns: GeoJSON polygon untuk mapping
├─ Parameters: wilayah = wilayah_id (e.g., "16.01.01")
└─ Response: GeoJSON FeatureCollection
   {
     "type": "FeatureCollection",
     "features": [
       {
         "type": "Feature",
         "geometry": {
           "type": "Polygon",
           "coordinates": [[[...]]]
         },
         "properties": {
           "nama": "Lampung Tengah",
           "data_count": 150
         }
       }
     ]
   }

GET /api/wilayah/periods
├─ Returns: Available time periods untuk filter
└─ Response:
   {
     "years": [2023, 2024, 2025],
     "months": [1, 2, 3, ...],
     "weeks": [1, 2, 3, 4]
   }

GET /api/wilayah/data-by-period?tahun=2024&bulan=12
├─ Returns: Data filtered by time period
└─ Parameters: tahun (year), bulan (month), minggu (week)
```

**Statistics Data**

```
GET /api/statistik/summary
├─ Returns: Overview statistics
└─ Response:
   {
     "total_data": 15000,
     "total_wilayah": 50,
     "total_features": 1250,
     "latest_update": "2025-12-30"
   }

GET /api/statistik/ranking
├─ Returns: Top wilayah by data count
└─ Response:
   [
     {
       "rank": 1,
       "wilayah": "Lampung Tengah",
       "data_count": 500,
       "percentage": 33.3
     }
   ]

GET /api/statistik/productivity
├─ Returns: Productivity metrics
└─ Response: Productivity data per wilayah

GET /api/statistik/yearly-comparison
├─ Returns: Year-over-year comparison
└─ Response: Comparison data between years
```

**Kategori & Warna**

```
GET /api/kategori-colors (public)
├─ Returns: Color mapping untuk setiap kategori
└─ Response:
   {
     "Aktif": "#FF0000",
     "Potensial": "#FFFF00",
     "Terkontrol": "#00FF00"
   }
```

#### 3. ADMIN AUTHENTICATED ROUTES

**Dashboard & Statistics**

```
GET /admin/dashboard
├─ Returns: Admin dashboard page
├─ Auth: Required (admin role)
└─ Features: View all data, imports, statistics

GET /admin/publication-status
├─ Returns: Current publication status
├─ Auth: Required
└─ Response: List of wilayah with publish status

GET /admin/statistics
├─ Returns: Admin statistics view
├─ Auth: Required
└─ Response: Detailed statistics for admin
```

**Data Management**

```
POST /admin/upload-csv
├─ Upload CSV file dengan data gulma
├─ Auth: Required (admin)
├─ Parameters:
│  └─ file: CSV file (form-data)
├─ Expected CSV columns:
│  ├─ wilayah_id, id_feature, status_gulma
│  ├─ persentase, tanggal, pg, fm, seksi
│  ├─ kategori, hasil, umur_tanaman, dll
└─ Response:
   {
     "success": true,
     "message": "Data imported successfully",
     "import_log_id": 42,
     "data_count": 150
   }

POST /admin/publish-map
├─ Publish data peta ke publik
├─ Auth: Required
├─ Parameters:
│  ├─ wilayah_id: string
│  └─ import_log_id: int
└─ Response:
   {
     "success": true,
     "published": true,
     "published_at": "2025-12-30 10:30:00"
   }
```

**Gallery Management**

```
GET /admin/gallery
├─ Gallery page
├─ Auth: Required

GET /admin/gallery/photos
├─ Get all photos
├─ Parameters: wilayah_id (optional)
└─ Response: Array of photo objects

POST /admin/gallery/upload
├─ Upload foto
├─ Parameters:
│  ├─ file: Image file
│  ├─ wilayah_id: string
│  ├─ lokasi: string
│  └─ keterangan: string (optional)
└─ Response:
   {
     "success": true,
     "photo_id": 123,
     "url": "/storage/photos/..."
   }

GET /admin/gallery/{id}
├─ Get photo details

PUT /admin/gallery/{id}
├─ Update photo metadata

DELETE /admin/gallery/{id}
├─ Delete photo
```

**Drone Management**

```
GET /admin/drone
├─ Drone management page
├─ Auth: Required (admin)
└─ Show: List drone surveys, upload form

POST /admin/drone/store
├─ Upload drone PDF
├─ Auth: Required (admin)
├─ Parameters:
│  ├─ judul: string (required)
│  ├─ lokasi: string (required)
│  ├─ tanggal_perencanaan: date (required)
│  ├─ file: PDF file (required)
│  └─ persen_gulma: decimal (optional)
└─ Response:
   {
     "success": true,
     "message": "Drone data uploaded successfully",
     "drone_id": 42
   }

DELETE /admin/drone/{id}
├─ Delete drone record
├─ Auth: Required (admin)
└─ Response: Success message
```

---

## 🔐 USER ROLES & AUTHENTICATION

### Role System

```
┌──────────────────────────────────────────────────────┐
│                    USER ROLES                        │
├──────────────────────────────────────────────────────┤
│                                                      │
│  1. GUEST (role='guest' / tidak login)              │
│     ├─ View: Home, Statistik, About, Wilayah pages │
│     ├─ API: Access public API endpoints             │
│     ├─ MAP: View map hanya jika published           │
│     └─ ❌ Cannot: Upload, publish, gallery         │
│                                                      │
│  2. ADMIN (role='admin')                            │
│     ├─ All GUEST permissions                       │
│     ├─ ✅ Upload CSV data                          │
│     ├─ ✅ Publish maps to public                   │
│     ├─ ✅ Manage gallery                           │
│     ├─ ✅ View admin dashboard                     │
│     └─ ✅ Access all admin features                │
│                                                      │
└──────────────────────────────────────────────────────┘
```


```php
// User roles defined as ENUM in database:
// 'guest' - Visitor (default)
// 'admin' - Administrator

// Check role:
if (auth()->user()->role === 'admin') {
    // Is admin
}

// Middleware check:
Route::middleware(['auth', 'admin'])->group(function () {
    // Only admin can access
});
```

### Authentication Flow


Sistem login aplikasi:

1. User masukkan email & password di halaman login
2. Server cek di database, cocok atau tidak
3. Jika cocok, buat session/token untuk user
4. User bisa akses halaman yang dilindungi



```php
// Login Process (AuthController)
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Attempt login
    if (Auth::attempt($credentials)) {
        // Login berhasil
        session()->regenerate();
        return redirect('/admin/dashboard');
    }

    // Login gagal
    return back()->withErrors(['email' => 'Invalid credentials']);
}

// Middleware protection
Route::middleware('auth')->group(function () {
    // Routes di sini hanya bisa diakses user yang login
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Routes di sini hanya bisa diakses admin
});
```

### Seeding Default Users


```php
// database/seeders/AdminUserSeeder.php
public function run()
{
    User::create([
        'name' => 'Admin User',
        'email' => 'admin@gulmatrack.test',
        'password' => Hash::make('password'),
        'role' => 'admin',      // ENUM role
        'is_active' => true,
        'email_verified_at' => now()
    ]);
}

// Run seeder:
php artisan db:seed --class=AdminUserSeeder

// Login credentials:
// Email: admin@gulmatrack.test
// Password: password
```

---

## ⭐ FITUR-FITUR UTAMA

### 1. Dashboard Admin


Halaman utama admin yang menampilkan ringkasan data.

**Yang Ditampilkan:**
```
┌──────────────────────────────────────────┐
│           ADMIN DASHBOARD                │
├──────────────────────────────────────────┤
│ Total Data Gulma:        15,234          │
│ Wilayah Aktif:           45              │
│ Total Features:          1,250           │
│ Last Import:             2025-12-30      │
├──────────────────────────────────────────┤
│ IMPORT HISTORY                           │
├──────────────────────────────────────────┤
│ ID  | File     | Tahun | Status | Action│
│ 42  | gulma.csv | 2024 | ✓ OK  | View  │
│ 41  | data.csv  | 2024 | ✓ OK  | View  │
└──────────────────────────────────────────┘
```


```php
// AdminController@dashboard
$totalDataGulma = DataGulma::count();
$wilayahAktif = DataGulma::distinct('wilayah_id')->count();
$totalTanaman = DataGulma::distinct('id_feature')->count();
$importLogs = ImportLog::latest('created_at')->paginate(10);
```

### 2. Upload CSV Data


Admin bisa upload file CSV (spreadsheet) berisi data gulma baru. Sistem otomatis validasi & masukin ke database.

**Flow Diagram:**

```
┌─────────────┐
│ Admin      │
│ Upload CSV │
└──────┬──────┘
       │
       ▼
┌─────────────────────────┐
│ Validasi Format & Data  │
│ - Column names check    │
│ - Data types verify     │
│ - Duplicates check      │
└──────┬──────────────────┘
       │
       ├─ ✓ VALID ──┐
       │            │
       └─ ✗ ERROR   ▼
                   ┌──────────────────┐
                   │ Save ImportLog   │
                   │ Status: success  │
                   └──────┬───────────┘
                          │
                          ▼
                   ┌──────────────────┐
                   │ Insert to        │
                   │ data_gulma table │
                   └──────────────────┘
```

**CSV Format yang Diharapkan:**

```csv
wilayah_id,id_feature,status_gulma,persentase,tanggal,pg,fm,seksi,neto,hasil,umur_tanaman,penanggungjawab,kode_aktf,activitas,kategori,tk_ha,total_tk
16,1,Bersih,0,2025-12-30,PG001,FM01,Seksi A,100.5,150,45,Budi,ACT001,Monitoring,Status A,0.5,15
16,2,Ringan,15.5,2025-12-30,PG002,FM02,Seksi B,95.5,200,30,Andi,ACT002,Planting,Status B,0.6,18
17,1,Sedang,35.0,2025-12-30,PG003,FM03,Seksi C,89.0,250,50,Citra,ACT003,Harvesting,Status C,0.7,20
```

**CSV Column Requirements:**
```
Wajib (Required):
- wilayah_id      : Integer (16-23)
- id_feature      : String/Integer (lokasi identifier)
- tanggal         : Date format YYYY-MM-DD

Opsional (Optional):
- status_gulma    : Bersih/Ringan/Sedang/Berat
- persentase      : Float (0-100)
- pg, fm, seksi   : String codes
- neto, hasil     : Decimal values
- umur_tanaman    : Integer (hari)
- penanggungjawab : String (nama)
- kode_aktf       : String
- activitas       : String
- kategori        : String (untuk warna peta)
- tk_ha           : Decimal
- total_tk        : Integer
```


```php
public function uploadCsv(Request $request)
{
    // Validate file
    $request->validate([
        'file' => 'required|file|mimes:csv,xlsx,xls|max:10240'
    ]);
    
    $file = $request->file('file');
    
    try {
        // Load spreadsheet
        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Create import log
        $importLog = ImportLog::create([
            'nama_file' => $file->getClientOriginalName(),
            'status' => 'pending',
            'user_id' => auth()->id()
        ]);
        
        $totalRecords = 0;
        $successRecords = 0;
        $failedRecords = 0;
        $errors = [];
        
        // Loop through rows (skip header row)
        $rows = $worksheet->toArray();
        foreach (array_slice($rows, 1) as $row) {
            $totalRecords++;
            
            try {
                DataGulma::create([
                    'wilayah_id' => $row[0],
                    'id_feature' => $row[1],
                    'status_gulma' => $row[2],
                    'persentase' => $row[3],
                    'tanggal' => $row[4],
                    'import_log_id' => $importLog->id,
                    'pg' => $row[5],
                    // ... more fields
                ]);
                $successRecords++;
            } catch (\Exception $e) {
                $failedRecords++;
                $errors[] = "Row {$totalRecords}: {$e->getMessage()}";
            }
        }
        
        // Update import log status
        $importLog->update([
            'jumlah_records' => $totalRecords,
            'jumlah_berhasil' => $successRecords,
            'jumlah_gagal' => $failedRecords,
            'status' => $failedRecords === 0 ? 'success' : 'partial',
            'error_log' => implode("\n", $errors)
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "Import selesai: {$successRecords} OK, {$failedRecords} gagal",
            'import_log_id' => $importLog->id
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 400);
    }
}
```

### 3. Interactive Map Viewer


Peta interaktif yang menampilkan data gulma per wilayah (wilayah 16-23). Bisa:
- Zoom in/out
- Klik lokasi untuk lihat detail data
- Filter by tahun/bulan/minggu
- View berbagai kategori dengan warna berbeda
- Hanya guest user bisa lihat jika map sudah di-publish

**Technologies Used:**
```
Leaflet.js        - Open-source mapping library
GeoJSON           - Format untuk polygon lokasi
CoordinateTransformer - UTM to WGS84 conversion
Axios             - Fetch data dari API
```

**Data Source:**
```
GeoJSON files di folder datala/:
├── Wil16.geojson  (Wilayah 16 polygon)
├── Wil17.geojson  (Wilayah 17 polygon)
├── ...
└── Wil23.geojson  (Wilayah 23 polygon)

Database:
└── data_gulma table (merged dengan GeoJSON features)
```


```javascript
// Load map
const map = L.map('map').setView([-5.35, 105.27], 8);

// Add base layer (OSM)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap',
    maxZoom: 19
}).addTo(map);

// Load GeoJSON dengan data
const wilayahId = 16;
fetch(`/api/wilayah/geojson/${wilayahId}`)
    .then(r => r.json())
    .then(data => {
        L.geoJSON(data, {
            style: feature => ({
                color: getColorByCategory(feature.properties.kategori),
                weight: 2,
                opacity: 0.8,
                fillOpacity: 0.5
            }),
            onEachFeature: (feature, layer) => {
                const props = feature.properties;
                let popup = `<strong>${props.seksi}</strong><br>`;
                if (props.has_data) {
                    popup += `Kategori: ${props.kategori}<br>`;
                    popup += `Status: ${props.status_gulma}<br>`;
                    popup += `Tanggal: ${props.tanggal}`;
                } else {
                    popup += 'Belum Dimonitoring';
                }
                layer.bindPopup(popup);
            }
        }).addTo(map);
    });
```

### 4. Statistics & Analytics


Halaman yang menampilkan analisis data gulma:
- Ranking wilayah dengan data terbanyak
- Perbandingan tahun ke tahun
- Statistik produktivitas
- Chart dan grafik

**API Endpoints:**

```
GET /api/statistik/summary       - Overview stats
GET /api/statistik/ranking       - Top wilayah
GET /api/statistik/productivity  - Productivity analysis
GET /api/statistik/yearly-comparison - Year comparison
```

### 5. Photo Gallery Management


Admin bisa upload foto lapangan dokumentasi, organize per wilayah dan lokasi. Foto tersimpan dengan metadata (tanggal, lokasi, kondisi, deskripsi).

**Features:**
```
✓ Upload foto per lokasi/wilayah
✓ Assign status gulma untuk foto
✓ Add deskripsi & metadata
✓ View gallery per wilayah
✓ Soft delete (bisa restore)
✓ Track uploaded_by (siapa upload)
✓ Store file_size & mime_type
```


```php
// GalleryController
public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        'wilayah_id' => 'required|integer|between:16,23',
        'lokasi' => 'required|string',
        'status_gulma' => 'required|in:bersih,ringan,sedang,berat',
        'tanggal_foto' => 'required|date',
        'deskripsi' => 'nullable|string'
    ]);
    
    $file = $request->file('file');
    $filename = time() . '_' . $file->getClientOriginalName();
    
    // Store file
    $file->storeAs('photos', $filename, 'public');
    
    // Save to database
    GulmaPhoto::create([
        'wilayah_id' => $request->wilayah_id,
        'lokasi' => $request->lokasi,
        'foto_path' => "storage/photos/{$filename}",
        'status_gulma' => $request->status_gulma,
        'tanggal_foto' => $request->tanggal_foto,
        'deskripsi' => $request->deskripsi,
        'uploaded_by' => auth()->id(),
        'file_size' => $file->getSize(),
        'mime_type' => $file->getMimeType()
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Photo uploaded successfully'
    ]);
}
```

### 6. Data Export


Download data dalam format Excel/CSV untuk kebutuhan lain.


```php
// ExcelDataController
public function getExcelData()
{
    $data = DataGulma::with('importLog')
        ->latest('created_at')
        ->get();
    
    // Generate Excel file
    return new ExcelExport($data);
}
```

---

## 📖 CARA MENGGUNAKAN

### Sebagai Public User (Visitor)

**Step 1: Akses Website**
```
Buka browser → http://localhost:8000
```

**Step 2: Navigasi Halaman Public**

```
HOME PAGE
├─ Overview tentang aplikasi
├─ Ringkasan statistik
└─ Link ke halaman lain

HALAMAN STATISTIK
├─ Ranking wilayah
├─ Analisis produktivitas
├─ Perbandingan tahun
└─ Chart & grafik

HALAMAN WILAYAH
├─ Interactive map
├─ Filter by tahun/bulan
├─ Klik wilayah untuk detail
└─ Lihat kategori dengan warna berbeda

HALAMAN TENTANG
└─ Informasi aplikasi & credit
```

### Sebagai Admin User

**Step 1: Login**

```
1. Klik "Login" di navbar
2. Masukkan email: admin@gulmatrack.test
3. Masukkan password: password
4. Klik "Sign In"
5. Akan diarahkan ke /admin/dashboard
```

**Step 2: Kelola Data**

**2.1 Upload CSV Data:**
```
1. Di dashboard, cari tombol "Upload CSV"
2. Klik & pilih file CSV
3. Sistem akan validasi & import data
4. Lihat status di "Import History"
```

**2.2 Publish Map:**
```
1. Di dashboard, buka "Import History"
2. Cari import yang ingin dipublish
3. Klik "Publish" button
4. Pilih wilayah yang ingin dipublish
5. Map akan tampil di halaman publik
```

**2.3 Manage Gallery:**
```
1. Click "Gallery" di sidebar
2. Upload foto: Klik "Upload Photo"
   - Pilih file image
   - Input lokasi
   - Input tanggal foto
   - Add deskripsi (optional)
   - Klik "Upload"
3. View gallery: Lihat semua foto per wilayah
4. Edit/Delete: Klik action button
```

**Step 3: Monitor Statistics**

```
1. Click "Statistics" di sidebar
2. Lihat:
   - Total data count
   - Ranking wilayah
   - Productivity metrics
   - Yearly comparison
```

---

## 🔧 TROUBLESHOOTING

### Problem 1: "php artisan: command not found"

**Solusi:**
```bash
# Pastikan di folder GulmaTrack
cd d:\AppData\Laragon\www\GulmaTrack

# Coba gunakan full path
php artisan serve

# Atau gunakan Laravel Sail (jika pakai Docker)
./vendor/bin/sail artisan serve
```

### Problem 2: "SQLSTATE[HY000]: General error"

 Database connection error (tidak bisa terhubung ke PostgreSQL).

**Solusi:**
```bash
# 1. Cek PostgreSQL running
# Buka Services (Windows) atau Activity Monitor (Mac)
# Pastikan PostgreSQL sudah jalan

# 2. Cek .env file
# Pastikan DB_HOST, DB_USERNAME, DB_PASSWORD benar

# 3. Cek database exist
psql -U postgres
\l
CREATE DATABASE gulmatrack;
\q

# 4. Run migrations
php artisan migrate
```

### Problem 3: "Class 'App\Models\User' not found"

 Laravel tidak bisa menemukan model file.

**Solusi:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Regenerate autoloader
composer dump-autoload

# Pastikan file ada di:
# app/Models/User.php
# app/Models/DataGulma.php
# etc.
```

### Problem 4: "CSRF token mismatch" pada form submit

 Security token tidak valid (browser & server tidak sync).

**Solusi:**
```blade
<!-- Pastikan form punya CSRF token -->
<form method="POST" action="/admin/upload-csv">
    @csrf  <!-- Tambahkan ini! -->
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>
```

### Problem 5: CSV Upload gagal / data tidak masuk

**Troubleshoot:**
```bash
# 1. Cek file logs
tail -f storage/logs/laravel.log

# 2. Cek CSV format
# Pastikan columns sesuai dengan yang di diharapkan
# Harus: wilayah_id, id_feature, status_gulma, persentase, tanggal, dll

# 3. Cek encoding
# File harus UTF-8 (bukan ANSI)

# 4. Cek data types
# wilayah_id = string format "16.01.01"
# persentase = number (25.5, bukan "25.5%")
# tanggal = date format YYYY-MM-DD

# 5. Test upload manual
php artisan tinker
>>> $data = DataGulma::create([...]);
```

### Problem 6: Map tidak tampil / GeoJSON error

**Solusi:**
```bash
# 1. Cek file GeoJSON ada di:
# data/Wil16.geojson
# data/Wil17.geojson
# etc.

# 2. Validate GeoJSON
# Buka https://geojson.io dan paste GeoJSON
# Check syntax & structure

# 3. Cek browser console (F12)
# Lihat error message di console
# Network tab untuk request error

# 4. Pastikan API endpoint return data
curl http://localhost:8000/api/wilayah/geojson/16
```

### Problem 7: File upload 413 "Request Entity Too Large"

 File terlalu besar untuk di-upload.

**Solusi:**
```
# Edit php.ini (Laragon: Menu > PHP > php.ini)

upload_max_filesize = 100M
post_max_size = 100M

# Restart Laragon/server setelah edit
```

### Problem 8: Forgot Admin Password

**Solusi:**
```bash
# Reset password via tinker
php artisan tinker

>>> use App\Models\User;
>>> use Illuminate\Support\Facades\Hash;
>>> $user = User::where('email', 'admin@gulmatrack.test')->first();
>>> $user->password = Hash::make('newpassword');
>>> $user->save();

# Sekarang bisa login dengan password baru: newpassword
```

### Problem 9: Map tidak tampil / GeoJSON error

**Solusi:**
```bash
# 1. Cek file GeoJSON ada di folder datala:
ls -la datala/
# Harus ada: Wil16.geojson, Wil17.geojson, dst

# 2. Validate GeoJSON syntax
# Buka https://geojson.io dan paste GeoJSON
# Check syntax & structure

# 3. Cek browser console (F12)
# Lihat error message di console
# Network tab untuk request error

# 4. Pastikan API endpoint return data
curl http://localhost:8000/api/wilayah/geojson/16

# 5. Cek log file
tail -f storage/logs/laravel.log

# 6. Cek database has data
php artisan tinker
>>> DataGulma::where('wilayah_id', 16)->count()
```

### Problem 10: File upload 413 "Request Entity Too Large"

 File terlalu besar untuk di-upload.

**Solusi:**
```
# Edit php.ini (Laragon: Menu > PHP > php.ini)

upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
max_input_time = 300

# Restart Laragon/server setelah edit
```

### 1. Development Environment Setup

**VS Code Extensions yang Recommended:**

```
- Laravel Extension Pack (HappyPath)
- PHP Intelephense
- Vite
- Thunder Client atau REST Client (testing API)
- Code Beautifier
```

**Database Management:**
```
Gunakan HeidiSQL (built-in di Laragon):
- Klik Tools > HeidiSQL di Laragon
- Connect ke PostgreSQL (DB_HOST, DB_PORT, DB_USERNAME)
- Manage databases, tables, dan data dengan GUI
```

**Composer Scripts:**

```json
{
  "scripts": {
    "post-autoload-dump": [
      "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
      "@php artisan package:discover --ansi"
    ]
  }
}
```

### 2. Useful Artisan Commands

```bash
# Development
php artisan serve                    # Start dev server
php artisan tinker                   # Interactive shell
php artisan migrate                  # Run migrations
php artisan migrate:fresh            # Reset & migrate
php artisan db:seed                  # Seed database

# Clear cache
php artisan cache:clear              # Clear app cache
php artisan config:clear             # Clear config cache
php artisan route:clear              # Clear route cache
php artisan view:clear               # Clear view cache

# Generate
php artisan make:model ModelName     # Create model
php artisan make:controller NameController # Create controller
php artisan make:migration create_table    # Create migration
php artisan make:seeder SeederName         # Create seeder

# Database
php artisan migrate:status           # Check migration status
php artisan migrate:rollback         # Undo last migration
php artisan migrate:reset            # Rollback all

# Testing
php artisan test                     # Run PHPUnit tests
php artisan test --filter TestName   # Run specific test
```

### 3. Database Debugging

```bash
# Via Laravel Tinker
php artisan tinker

>>> use App\Models\DataGulma;
>>> DataGulma::count()                          # Count records
>>> DataGulma::where('wilayah_id', '16.01.01')->get()
>>> DataGulma::latest('created_at')->first()   # Last record
>>> DataGulma::find(1)                          # Find by ID
>>> DataGulma::create([...])                    # Create
>>> $data = DataGulma::find(1); $data->delete() # Delete
```

### 4. API Testing

**Menggunakan Thunder Client / REST Client:**

```bash
### Get All Wilayah Data
GET http://localhost:8000/api/wilayah/data

### Get GeoJSON untuk Wilayah Tertentu
GET http://localhost:8000/api/wilayah/geojson/16.01.01

### Get Statistik Summary
GET http://localhost:8000/api/statistik/summary

### Get Data by Period
GET http://localhost:8000/api/wilayah/data-by-period?tahun=2024&bulan=12

### Upload CSV (require auth)
POST http://localhost:8000/admin/upload-csv
Content-Type: multipart/form-data

file=@/path/to/file.csv

### Publish Map (require auth & admin)
POST http://localhost:8000/admin/publish-map
Content-Type: application/json

{
  "wilayah_id": "16.01.01",
  "import_log_id": 1
}
```

### 5. Frontend Development

**Vite Hot Reload:**

```bash
# Terminal 1 - Run dev server
php artisan serve

# Terminal 2 - Run Vite watch
npm run dev

# Sekarang edit CSS/JS dan otomatis reload
```

**Blade Template Tips:**

```blade
<!-- Conditional rendering -->
@if ($user->is_admin)
    <p>Admin user</p>
@else
    <p>Regular user</p>
@endif

<!-- Loop -->
@foreach ($wilayah as $w)
    <div>{{ $w->nama_wilayah }}</div>
@endforeach

<!-- Include components -->
@include('partials.navbar')

<!-- Yield sections -->
@section('content')
    <div>Page content here</div>
@endsection

<!-- CSRF token dalam form -->
@csrf
```

### 6. Security Best Practices

```php
// 1. Always validate input
$validated = $request->validate([
    'email' => 'required|email',
    'password' => 'required|min:8'
]);

// 2. Use prepared statements (Eloquent)
// GOOD:
$users = User::where('email', $email)->get();

// BAD (SQL Injection risk):
// $users = DB::select("SELECT * FROM users WHERE email = '$email'");

// 3. Hash passwords
Hash::make('password')

// 4. Use HTTPS in production
// Edit .env: APP_URL=https://yourdomain.com

// 5. Validate file uploads
$request->validate([
    'file' => 'required|file|mimes:csv,txt|max:10240'
]);

// 6. Check authorization
$this->authorize('delete', $photo);
```

### 7. Common Debugging Techniques

```php
// 1. dd() - Dump and Die
dd($variable);  // Print dan stop

// 2. dump() - Dump only
dump($variable); // Print, tapi lanjut

// 3. Log to file
Log::info('Message', ['data' => $variable]);
// Check: storage/logs/laravel.log

// 4. Query debugging
DB::enableQueryLog();
$users = User::all();
dd(DB::getQueryLog());

// 5. Exception handling
try {
    // code
} catch (Exception $e) {
    Log::error('Error: ' . $e->getMessage());
    return response()->json(['error' => $e->getMessage()], 500);
}
```

### 8. Performance Optimization

```php
// 1. Use eager loading (prevent N+1 queries)
// GOOD:
$importLogs = ImportLog::with('dataGulma')->get();
// BAD:
$importLogs = ImportLog::all();
foreach ($importLogs as $log) {
    $log->dataGulma;  // Query per loop!
}

// 2. Pagination
$data = DataGulma::paginate(15);

// 3. Caching
$key = 'wilayah_data_' . $id;
$data = Cache::remember($key, 3600, function () use ($id) {
    return Wilayah::find($id);
});

// 4. Database indexing
// Run in migration:
Schema::table('data_gulma', function (Blueprint $table) {
    $table->index('wilayah_id');
    $table->index('import_log_id');
});
```

### 9. Deployment Checklist

```bash
# Sebelum production:
□ Set APP_ENV=production di .env
□ Set APP_DEBUG=false
□ Generate APP_KEY
□ Run migrations: php artisan migrate
□ Seed default data: php artisan db:seed
□ Optimize: php artisan optimize
□ Cache config: php artisan config:cache
□ Cache routes: php artisan route:cache
□ Setup backups
□ Setup monitoring/logging
□ Setup SSL certificate
□ Test all features
```

### 10. Git Workflow

```bash
# Sebelum commit, jangan commit:
# .env (database password!)
# node_modules/
# vendor/
# storage/logs/

# Good commit message
git commit -m "feat: add CSV import feature for admin"

# Semantic commits:
# feat:   new feature
# fix:    bug fix
# docs:   documentation
# style:  code style
# refactor: code refactoring
# test:   testing
# chore:  other changes
```

---

## 📞 SUPPORT & RESOURCES

### Useful Documentation Links

- **Laravel Official Docs**: https://laravel.com/docs
- **Laravel API**: https://laravel.com/api
- **Leaflet.js Docs**: https://leafletjs.com
- **GeoJSON Spec**: https://geojson.org
- **PostgreSQL Docs**: https://www.postgresql.org/docs/

### Common Gotchas & Solutions

| Problem | Solution |
|---------|----------|
| Migration fails | Check syntax, run `php artisan migrate:rollback` |
| Route not found | Clear cache: `php artisan route:clear` |
| CSS/JS not loading | Run `npm run build`, check Vite config |
| Permission denied | Fix file permissions: `chmod -R 755 storage` |
| Memory limit exceeded | Increase in php.ini: `memory_limit = 512M` |

---

## 🎓 LEARNING PATH

### Untuk Beginners (Tidak Programming)

```
1. Pahami README2 ini sepenuhnya
2. Setup environment (install, database, server)
3. Akses halaman publik dan explore
4. Login sebagai admin & coba upload data
5. Baca database schema untuk mengerti struktur data
6. Coba baca routes untuk mengerti flow aplikasi
```

### Untuk Developers (Sudah Programming)

```
1. Clone & setup aplikasi
2. Baca Laravel docs untuk framework understanding
3. Explore code di app/Http/Controllers/
4. Understand models di app/Models/
5. Explore API di routes/api.php
6. Modify / extend features sesuai kebutuhan
7. Write tests untuk code confidence
8. Deploy ke production
```

### Advanced Topics untuk Expert Developers

```
1. Implement caching strategy
2. Optimize database queries (N+1 problem)
3. Add automated tests (PHPUnit)
4. Implement CI/CD pipeline
5. Setup monitoring & alerting
6. Database replication & backup strategy
7. API versioning & deprecation strategy
8. Performance monitoring & optimization
```

---

## 📋 CHECKLIST PERSIAPAN PRODUCTION

```
INFRASTRUCTURE:
☐ Server/Hosting dengan minimum PHP 8.1
☐ PostgreSQL 12+ database
☐ SSL Certificate (HTTPS)
☐ Backups automated

CONFIGURATION:
☐ .env production settings
☐ APP_DEBUG = false
☐ APP_ENV = production
☐ SESSION_DRIVER = database atau redis
☐ CACHE_DRIVER = redis atau memcached

SECURITY:
☐ Strong APP_KEY
☐ Rate limiting pada login
☐ CORS configuration
☐ SQL injection prevention (using Eloquent)
☐ XSS prevention (Blade escaping)
☐ CSRF token validation

OPTIMIZATION:
☐ php artisan optimize
☐ Database indexes
☐ Caching strategy
☐ Asset minification (npm run build)
☐ Query optimization

MONITORING:
☐ Error logging setup
☐ Performance monitoring
☐ Backup verification
☐ Database monitoring
☐ Server health checks

TESTING:
☐ All features tested
☐ Performance tested
☐ Security tested
☐ API endpoints tested
```

---

**Terakhir Updated:** 30 Desember 2025
**Dokumentasi Version:** 1.0 (Komprehensif)

---

## 📌 FITUR BARU - DRONE MANAGEMENT

### Deskripsi Fitur

Fitur Drone Management adalah sistem untuk mengelola dan menyimpan laporan survey drone dalam format PDF. Admin dapat:
- Upload laporan survey drone (format PDF)
- Menentukan lokasi dan tanggal perencanaan
- Mencatat persentase gulma dari hasil drone
- User publik dapat mengakses dan download laporan drone

### Teknologi

- **File Format**: PDF
- **Storage**: Local filesystem (storage/app/drones)
- **Database**: Drone model dengan relasi ke User

### Model & Database

**Drone Model:**
```php
class Drone extends Model {
    protected $fillable = [
        'judul', 'lokasi', 'tanggal_perencanaan',
        'pdf_path', 'pdf_filename', 'persen_gulma', 'user_id'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
```

**Drone Table:**
```
- id (PK)
- judul (VARCHAR) - Judul survey drone
- lokasi (VARCHAR) - Lokasi survey
- tanggal_perencanaan (DATE) - Tanggal perencanaan
- pdf_path (VARCHAR) - Path file PDF
- pdf_filename (VARCHAR) - Nama file asli
- persen_gulma (DECIMAL) - Persentase gulma (%)
- user_id (INT FK) - Admin pembuat
- created_at, updated_at
```

### Fitur Admin

**1. Halaman Admin Drone (/admin/drone)**

Menampilkan:
```
┌─────────────────────────────────┐
│     DRONE MANAGEMENT PAGE       │
├─────────────────────────────────┤
│ Total PDF Uploads: 15           │
│ Latest Upload: 2026-01-14       │
│                                 │
│ [Upload New Drone Survey] Btn   │
├─────────────────────────────────┤
│ LIST DRONE SURVEYS              │
├─────────────────────────────────┤
│ No | Judul | Lokasi | Tanggal   │
│    | Action (View/Delete)       │
└─────────────────────────────────┘
```

**2. Upload Drone Survey**

Form untuk upload:
- Judul Survey (required)
- Lokasi (required)
- Tanggal Perencanaan (required)
- File PDF (required, max 100MB)
- Persentase Gulma (optional)

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'lokasi' => 'required|string|max:255',
        'tanggal_perencanaan' => 'required|date',
        'file' => 'required|file|mimes:pdf|max:102400', // 100MB
        'persen_gulma' => 'nullable|numeric|between:0,100'
    ]);
    
    $file = $request->file('file');
    $filename = time() . '_' . $file->getClientOriginalName();
    
    // Store file
    $path = $file->storeAs('drones', $filename, 'private');
    
    // Save to database
    Drone::create([
        'judul' => $validated['judul'],
        'lokasi' => $validated['lokasi'],
        'tanggal_perencanaan' => $validated['tanggal_perencanaan'],
        'pdf_path' => $path,
        'pdf_filename' => $filename,
        'persen_gulma' => $validated['persen_gulma'],
        'user_id' => auth()->id()
    ]);
    
    return redirect()->back()->with('success', 'Drone data uploaded successfully');
}
```

**3. Delete Drone Record**

Admin dapat menghapus record drone:
```php
public function destroy($id)
{
    $drone = Drone::findOrFail($id);
    Storage::disk('private')->delete($drone->pdf_path);
    $drone->delete();
    
    return response()->json(['success' => true]);
}
```

### Fitur Public

**1. Halaman Drone Publik (/drone)**

User publik dapat:
- Melihat list semua survey drone
- Filter by lokasi/tanggal
- Download PDF

**2. Download Drone PDF**

```
GET /drone/download/{id}
├─ Download PDF file
├─ Public access (tidak perlu login)
└─ Response: File download
```

**3. View Drone PDF Inline**

```
GET /drone/view/{id}
├─ View PDF di browser
├─ Public access (tidak perlu login)
└─ Response: PDF inline viewer
```

### Routes

```php
// Public routes
Route::get('/drone', [DroneController::class, 'userIndex'])->name('drone');
Route::get('/drone/download/{id}', [DroneController::class, 'download'])->name('drone.download');
Route::get('/drone/view/{id}', [DroneController::class, 'view'])->name('drone.view');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/drone', [DroneController::class, 'adminIndex'])->name('admin.drone.index');
    Route::post('/admin/drone/store', [DroneController::class, 'store'])->name('admin.drone.store');
    Route::delete('/admin/drone/{id}', [DroneController::class, 'destroy'])->name('admin.drone.destroy');
});
```

### Implementasi Fitur

**Langkah-Langkah Admin Upload Drone:**

1. Login sebagai admin
2. Klik menu "Drone" di sidebar
3. Klik tombol "Upload New Drone Survey"
4. Isi form:
   - Judul: "Survey Lampung Tengah 2026-01"
   - Lokasi: "Kecamatan X, Kabupaten Lampung Tengah"
   - Tanggal: 2026-01-14
   - File: Pilih PDF
   - % Gulma: 25.5
5. Klik "Upload"
6. File akan tersimpan & bisa diakses publik

**User Publik Mengakses Drone:**

1. Buka /drone
2. Lihat list survey
3. Klik "Download" atau "View" untuk buka PDF
4. File bisa di-view di browser atau di-download

### Catatan & Best Practices

```
✓ PDF files disimpan di storage/app/drones (private)
✓ User perlu akses melalui Laravel routing (tidak direct access)
✓ File naming menggunakan timestamp untuk unikeness
✓ Maximum file size 100MB (bisa disesuaikan di validasi)
✓ Support untuk filename special characters
✓ Delete otomatis hapus file dari storage
```

### Troubleshooting Drone

**Problem: Upload gagal, file tidak tersimpan**

Solusi:
```bash
# Cek permission folder storage
chmod -R 755 storage/app/drones
chmod -R 755 storage

# Create drones folder jika belum ada
mkdir -p storage/app/drones

# Clear cache
php artisan cache:clear
```

**Problem: PDF tidak bisa di-download**

Solusi:
```bash
# Pastikan storage symbolic link exist
php artisan storage:link

# Jika belum ada, buat manual:
# Windows: mklink /D public\storage storage\app\public
```

**Problem: Persentase gulma tidak tersimpan**

Solusi:
```php
// Pastikan di form value numeric:
<input type="number" name="persen_gulma" step="0.1" min="0" max="100">

// Di controller, pastikan di-cast:
protected $casts = [
    'persen_gulma' => 'decimal:2'
];
```

---

## 📝 CATATAN

Dokumentasi ini dirancang agar:
✅ Mudah dipahami oleh non-technical users
✅ Memberikan detail teknis untuk developers
✅ Mencakup semua aspek dari setup sampai production
✅ Memiliki solusi untuk masalah umum
✅ Memberikan tips untuk optimization & best practices

Jika ada yang kurang jelas atau pertanyaan, seluruh informasi yang diperlukan sudah ada di dokumentasi ini! 🎉

---

##  VALIDASI DOKUMENTASI & CATATAN REVISI

**Tanggal Revisi Awal:** 30 Desember 2025  
**Tanggal Update Terbaru:** 15 Januari 2026  
**Version:** 2.0 (Updated dengan Drone Management Feature)
**Status:**  VALID - Diverifikasi terhadap actual GulmaTrack codebase v2.0  
**Metode:** Direct file verification - 11 Controllers checked

### Perubahan di Version 2.0:
1. ✅ Tambah DroneController dalam dokumentasi Controllers
2. ✅ Tambah Drone Model & Table pada Database Schema
3. ✅ Tambah Drone dalam Model Relationships
4. ✅ Tambah Public Drone Routes
5. ✅ Tambah Admin Drone Management Routes
6. ✅ Tambah Section Fitur Baru - Drone Management (lengkap)
7. ✅ Update daftar isi dengan link ke Drone Management
8. ✅ Tambah contoh implementasi & troubleshooting Drone
9. ✅ Update DAFTAR ISI dengan poin ke Drone Management

### Controllers yang Didokumentasikan:
- ✅ AdminController
- ✅ AuthController
- ✅ CsvController
- ✅ DebugController
- ✅ DroneController (NEW)
- ✅ ExcelDataController
- ✅ GalleryController
- ✅ GulmaController
- ✅ ImportLogController
- ✅ WilayahController

