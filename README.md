<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

##  GULMATRACK APPLICATION

**GulmaTrack** adalah aplikasi geospasial untuk tracking dan monitoring gulma (tanaman pengganggu) di berbagai wilayah produksi.

### 📋 Dependency & Requirement
**Minimal requirement untuk setup:**
- PHP 8.1+
- PostgreSQL 12+
- Composer
- Node.js 18+

**⚠️ Catatan penting: Tidak perlu install Ghostscript atau ImageMagick!**
- Thumbnail PDF menggunakan lightweight placeholder (built-in di PHP, tanpa external dependency)
- Download PDF langsung support tanpa perlu convert

###  Dokumentasi Lengkap

**Untuk Pemula:**
- **[QUICKSTART.md](QUICKSTART.md)** - Copy-paste commands (5 menit!)
- **[INSTALL.md](INSTALL.md)** - Quick installation guide

**Untuk Developer:**
- **[SETUP_SIMPLE.md](SETUP_SIMPLE.md)** - Detailed setup & troubleshooting
- **[dokumentasi1.md](dokumentasi1.md)** - Full documentation (fitur, API, database)
- **[CHANGES.md](CHANGES.md)** - Technical changelog
- **[PERFORMANCE.md](PERFORMANCE.md)** - Performance test & benchmarks

**Untuk Guru/Mentor:**
- **[TEACH.md](TEACH.md)** - Panduan mengajarkan GulmaTrack ke pemula

**Solusi Detail:**
- **[SOLUTION_FINAL.md](SOLUTION_FINAL.md)** - ⭐ BACA INI! Real PDF thumbnails + no install!
- **[README_SOLUSI.md](README_SOLUSI.md)** - Penjelasan removal Ghostscript dependency
- **[THUMBNAIL_GUIDE.md](THUMBNAIL_GUIDE.md)** - PDF Thumbnail Preview guide
- **[OPTIMIZATION.md](OPTIMIZATION.md)** - Complete optimization guide
- **[PERFORMANCE.md](PERFORMANCE.md)** - Performance test & benchmarks
- **[FINAL_SUMMARY.md](FINAL_SUMMARY.md)** - Quick summary
- **[testing/README.md](testing/README.md)** - Testing & debugging guide

###  Struktur Folder Penting
\\\
 app/                 - Kode aplikasi Laravel (Controllers, Models)
 resources/           - Views (Blade templates), CSS, JavaScript
 routes/              - API & web routes
 database/            - Migrations, seeders, factories
 public/              - Assets publik (images, compiled CSS/JS)
 testing/             - Testing & development files (organized by purpose)
 data/ & datala/      - GeoJSON polygon data untuk mapping
 storage/             - Logs, uploads, cache
\\\

###  Quick Start
\\\ash
# Setup
php artisan migrate
php artisan db:seed

# Development
php artisan serve
npm run dev

# Testing
php testing/api-testing/test_api.php
php testing/database-check/check_data_count.php
\\\

###  Testing Folder Structure
Semua testing & debug files sudah diorganisir dalam folder **testing/**:
- **api-testing/** - API endpoint testing (10 files)
- **database-check/** - Database validation scripts (15 files)
- **data-migration/** - Data import/export tools (4 files)
- **sample-data/** - Sample CSV & test files (4 files)
- **web-testing/** - Browser-based tests (7 files)
- **deprecated/** - Old/obsolete files (9 files)
- **data/ & datafix/** - GeoJSON data (16 files)

Lihat [testing/README.md](testing/README.md) untuk detail lengkap tentang setiap folder.
