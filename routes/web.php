<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GulmaController;
use App\Http\Controllers\ExcelDataController;

// ==================== PUBLIC ROUTES ====================
Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah');
Route::get('/statistik', function () {
    return view('pages.statistik');
})->name('statistik');
Route::get('/tentang', function () {
    return view('pages.about');
})->name('about');

// ==================== PUBLIC API ENDPOINTS ====================
Route::get('/data/excel', [ExcelDataController::class, 'getExcelData'])->name('data.excel');

// API untuk Wilayah (Public - bisa diakses guest)
Route::prefix('api/wilayah')->name('api.wilayah.')->group(function () {
    Route::get('/data', [WilayahController::class, 'getData'])->name('data');
    Route::get('/geojson/{wilayah}', [WilayahController::class, 'getGeojson'])->name('geojson');
    Route::get('/periods', [WilayahController::class, 'getPeriods'])->name('periods');
    Route::get('/data-by-period', [WilayahController::class, 'getDataByPeriod'])->name('data-by-period');
});

// API untuk Statistik (Public)
Route::prefix('api/statistik')->name('api.statistik.')->group(function () {
    Route::get('/summary', [GulmaController::class, 'getStatistikSummary'])->name('summary');
    Route::get('/ranking', [GulmaController::class, 'getStatistikRanking'])->name('ranking');
    Route::get('/productivity', [GulmaController::class, 'getStatistikProductivity'])->name('productivity');
    Route::get('/yearly-comparison', [GulmaController::class, 'getYearlyComparison'])->name('yearly');
});

// ==================== AUTHENTICATION ====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/upload-csv', [AdminController::class, 'uploadCsv'])->name('admin.upload-csv');
    Route::post('/admin/publish-map', [AdminController::class, 'publishMap'])->name('admin.publish-map');
    Route::get('/admin/publication-status', [AdminController::class, 'getPublicationStatus'])->name('admin.publication-status');
    Route::get('/admin/statistics', [AdminController::class, 'getStatistics'])->name('admin.statistics');
    
    Route::prefix('admin/api')->group(function () {
        Route::get('/geojson/{wilayah}', [GulmaController::class, 'getGeoJSONWithData'])->name('admin.get-geojson');
        Route::get('/data-gulma', [GulmaController::class, 'getDataGulma'])->name('admin.get-data-gulma');
        Route::get('/statistics', [GulmaController::class, 'getStatistics'])->name('admin.get-statistics');
        Route::get('/kategori-colors', [AdminController::class, 'getKategoriColors'])->name('admin.get-kategori-colors');
    });
});