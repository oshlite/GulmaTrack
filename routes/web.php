<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\GulmaController;
use App\Http\Controllers\ExcelDataController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\DroneController;

/*
|--------------------------------------------------------------------------
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/
Route::view('/', 'pages.home')->name('home');
Route::view('/statistik', 'pages.statistik')->name('statistik');
Route::view('/tentang', 'pages.about')->name('about');

Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah');
Route::get('/drone', [DroneController::class, 'userIndex'])->name('drone');

/*
|--------------------------------------------------------------------------
| PUBLIC DATA / API
|--------------------------------------------------------------------------
*/
Route::get('/data/excel', [ExcelDataController::class, 'getExcelData'])
    ->name('data.excel');

/*
|--------------------------------------------------------------------------
| API routes have been moved to routes/api.php
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| API - ADMIN DASHBOARD AUTOREFRESH
|--------------------------------------------------------------------------
*/
Route::prefix('api/admin')
    ->name('api.admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/publikasi-refresh', [AdminController::class, 'getPublikasiRefresh'])
            ->name('publikasi-refresh');

        Route::get('/riwayat-upload-refresh', [AdminController::class, 'getRiwayatUploadRefresh'])
            ->name('riwayat-upload-refresh');
    });

/*
|--------------------------------------------------------------------------
| API - STATISTIK 
|--------------------------------------------------------------------------
*/
Route::prefix('api/statistik')
    ->name('api.statistik.')
    ->group(function () {
        Route::get('/summary', [GulmaController::class, 'getStatistikSummary'])->name('summary');
        Route::get('/ranking', [GulmaController::class, 'getStatistikRanking'])->name('ranking');
        Route::get('/productivity', [GulmaController::class, 'getStatistikProductivity'])->name('productivity');
        Route::get('/yearly-comparison', [GulmaController::class, 'getYearlyComparison'])->name('yearly');
        Route::get('/wilayah/{wilayah_id}', [GulmaController::class, 'getStatistikWilayahDetail'])->name('wilayah');
        Route::get('/comparison', [GulmaController::class, 'getStatistikComparison'])->name('comparison');
    });

/*
|--------------------------------------------------------------------------
| API - GALLERY (PUBLIC) - untuk popup peta
|--------------------------------------------------------------------------
*/
Route::prefix('api/gallery')
    ->name('api.gallery.')
    ->group(function () {
        Route::get('/kategori/{kategori}', [GalleryController::class, 'getByCategory'])->name('by-category');
        Route::get('/image/{id}', [GalleryController::class, 'serveImage'])->name('image');
    });

/*
|--------------------------------------------------------------------------
| API - MAP PUBLICATIONS (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('api/map-publications')
    ->name('api.map-publications.')
    ->group(function () {
        Route::get('/latest-published', [AdminController::class, 'getLatestPublished'])->name('latest-published');
    });

/*
|--------------------------------------------------------------------------
| API - PUBLICATION STATUS (ADMIN)
|--------------------------------------------------------------------------
*/
Route::get('/api/publication-status', [AdminController::class, 'getPublicationStatus'])->middleware(['auth', 'admin']);

/*
|--------------------------------------------------------------------------
| API - DATA GULMA (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('api/data-gulma')
    ->name('api.data-gulma.')
    ->group(function () {
        Route::get('/by-import/{importId}', [AdminController::class, 'getDataByImport'])->name('by-import');
    });

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Download drone PDF (accessible to everyone)
Route::get('/drone/download/{id}', [DroneController::class, 'download'])->name('drone.download');

// View drone PDF inline (accessible to everyone)
Route::get('/drone/view/{id}', [DroneController::class, 'view'])->name('drone.view');

// Thumbnail drone (cached, super ringan)
Route::get('/drone/thumbnail/{id}', [DroneController::class, 'thumbnail'])->name('drone.thumbnail');

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        /* Dashboard & Data */
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/upload-csv', [AdminController::class, 'uploadCsv'])->name('upload-csv');
        Route::post('/publish-map', [AdminController::class, 'publishMap'])->name('publish-map');
        Route::get('/publication-status', [AdminController::class, 'getPublicationStatus'])->name('publication-status');
        Route::get('/statistics', [AdminController::class, 'getStatistics'])->name('statistics');

        /*
        |--------------------------------------------------------------------------
        | GALLERY (ADMIN)
        |--------------------------------------------------------------------------
        */
        Route::prefix('gallery')->name('gallery.')->group(function () {
            Route::get('/', [GalleryController::class, 'index'])->name('index');
            Route::post('/upload', [GalleryController::class, 'upload'])->name('upload');
            Route::get('/photos', [GalleryController::class, 'getPhotos'])->name('photos');
            Route::get('/stats', [GalleryController::class, 'getStats'])->name('stats');
            Route::get('/{id}', [GalleryController::class, 'show'])->name('show');
            Route::put('/{id}', [GalleryController::class, 'update'])->name('update');
            Route::delete('/{id}', [GalleryController::class, 'destroy'])->name('destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | DRONE (ADMIN)
        |--------------------------------------------------------------------------
        */
        Route::prefix('drone')->name('drone.')->group(function () {
            Route::get('/', [DroneController::class, 'adminIndex'])->name('index');
            Route::post('/store', [DroneController::class, 'store'])->name('store');
            Route::delete('/{id}', [DroneController::class, 'destroy'])->name('destroy');
            Route::get('/api/list', [DroneController::class, 'getDronesPaginated'])->name('api.list');
        });

        /*
        |--------------------------------------------------------------------------
        | ADMIN API
        |--------------------------------------------------------------------------
        */
        Route::prefix('api')->name('api.')->group(function () {

            /* Gulma & Map */
            Route::get('/geojson/{wilayah}', [GulmaController::class, 'getGeoJSONWithData'])->name('geojson');
            Route::get('/data-gulma', [GulmaController::class, 'getDataGulma'])->name('data-gulma');
            Route::get('/statistics', [GulmaController::class, 'getStatistics'])->name('statistics');
            Route::get('/kategori-colors', [AdminController::class, 'getKategoriColors'])->name('kategori-colors');
        });
    });

// Debug routes
require __DIR__ . '/debug.php';