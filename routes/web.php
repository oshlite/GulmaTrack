<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\GulmaController;

use App\Http\Controllers\ExcelDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ==================== PUBLIC ROUTES (GUEST) ====================
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



// ==================== DATA ENDPOINTS ====================
// Excel data endpoint for popups
Route::get('/data/excel', [ExcelDataController::class, 'getExcelData'])->name('data.excel');

// ==================== AUTHENTICATION ROUTES ====================
// Login page bisa diakses oleh guest dan user yang sudah login (untuk re-login)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');

// POST login hanya bisa diakses oleh guest
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');

// Logout hanya bisa diakses oleh user yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    // CSV Upload
    Route::post('/admin/upload-csv', [AdminController::class, 'uploadCsv'])
        ->name('admin.upload-csv');

    // Publish Map
    Route::post('/admin/publish-map', [AdminController::class, 'publishMap'])
        ->name('admin.publish-map');
    
    Route::get('/admin/publication-status', [AdminController::class, 'getPublicationStatus'])
        ->name('admin.publication-status');
    
    Route::get('/admin/statistics', [AdminController::class, 'getStatistics'])
        ->name('admin.statistics');

    // API Endpoints
    Route::prefix('admin/api')->group(function () {
        Route::get('/geojson/{wilayah}', [GulmaController::class, 'getGeoJSONWithData'])
            ->name('admin.get-geojson')
            ->where('wilayah', '[0-9]+');
        
        Route::get('/data-gulma', [GulmaController::class, 'getDataGulma'])
            ->name('admin.get-data-gulma');
        
        Route::get('/statistics', [GulmaController::class, 'getStatistics'])
            ->name('admin.get-statistics');
    });
});