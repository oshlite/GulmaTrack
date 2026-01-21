<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\ExcelDataController;
use App\Http\Controllers\ImportLogController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\DebugController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Wilayah GeoJSON endpoints - Public access
Route::get('/wilayah/geojson/{wilayah_number}', [WilayahController::class, 'getGeojson'])
    ->name('api.wilayah.geojson')
    ->withoutMiddleware('api'); // Bypass API middleware if needed

Route::get('/wilayah/stats/{wilayah_number}', [WilayahController::class, 'getWilayahStats'])
    ->name('api.wilayah.stats')
    ->withoutMiddleware('api'); // Get statistics directly from database

Route::get('/wilayah/records/{wilayah_number}', [WilayahController::class, 'getWilayahRecords'])
    ->name('api.wilayah.records')
    ->withoutMiddleware('api'); // Get all records from database for table display

Route::get('/wilayah/data', [WilayahController::class, 'getData'])
    ->name('api.wilayah.data')
    ->withoutMiddleware('api'); // Bypass API middleware if needed

Route::get('/wilayah/periods', [WilayahController::class, 'getPeriods'])
    ->name('api.wilayah.periods')
    ->withoutMiddleware('api');

Route::get('/debug/publications', [\App\Http\Controllers\DebugController::class, 'checkPublications'])
    ->name('api.debug.publications')
    ->withoutMiddleware('api');

Route::post('/debug/restore-publications', [\App\Http\Controllers\DebugController::class, 'restorePublications'])
    ->name('api.debug.restore-publications')
    ->withoutMiddleware('api');

Route::get('/wilayah/data-by-period', [WilayahController::class, 'getDataByPeriod'])
    ->name('api.wilayah.data-by-period')
    ->withoutMiddleware('api');

// Excel data endpoint
Route::get('/excel-data', [ExcelDataController::class, 'getExcelData'])
    ->name('api.excel.data')
    ->withoutMiddleware('api');

// Get kategori color mapping
Route::get('/kategori-colors', [\App\Http\Controllers\AdminController::class, 'getKategoriColors'])
    ->name('api.kategori.colors')
    ->withoutMiddleware(['api', 'auth', 'admin']);

// Map publications - get latest published
Route::get('/map-publications/latest-published', [\App\Http\Controllers\AdminController::class, 'getLatestPublished'])
    ->name('api.map-publications.latest')
    ->withoutMiddleware(['api', 'auth', 'admin']);

// Data gulma by import ID
Route::get('/data-gulma/by-import/{importId}', [\App\Http\Controllers\AdminController::class, 'getDataByImport'])
    ->name('api.data-gulma.by-import')
    ->withoutMiddleware(['api', 'auth', 'admin']);

// Import logs - get list with filters
Route::get('/import-logs', [\App\Http\Controllers\AdminController::class, 'getImportLogs'])
    ->name('api.import-logs')
    ->withoutMiddleware(['api', 'auth', 'admin']);

// Debug endpoint - check data in database
Route::get('/debug/import/{importId}', [\App\Http\Controllers\AdminController::class, 'debugImport'])
    ->name('api.debug.import')
    ->withoutMiddleware(['api', 'auth', 'admin']);

// Maintenance endpoint - fix missing import_log_id
Route::post('/maintenance/fix-import-log-ids', [\App\Http\Controllers\AdminController::class, 'fixMissingImportLogIds'])
    ->name('api.maintenance.fix-import-log-ids')
    ->middleware('auth')
    ->middleware('admin');

// Admin - Publication Management API
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/files-by-period', [\App\Http\Controllers\AdminController::class, 'getFilesForPeriod'])
        ->name('api.admin.files-by-period');
    
    Route::post('/admin/set-publication', [\App\Http\Controllers\AdminController::class, 'setPublication'])
        ->name('api.admin.set-publication');
});

// CSV Export & Data API - Public access
Route::get('/csv/export', [CsvController::class, 'export'])
    ->name('api.csv.export')
    ->withoutMiddleware('api');

Route::get('/csv/data', [CsvController::class, 'getData'])
    ->name('api.csv.data')
    ->withoutMiddleware('api');

Route::get('/csv/statistik', [CsvController::class, 'getStatistik'])
    ->name('api.csv.statistik')
    ->withoutMiddleware('api');

Route::get('/csv/kategori-list', [CsvController::class, 'getKategoriList'])
    ->name('api.csv.kategori-list')
    ->withoutMiddleware('api');

Route::get('/csv/activitas-list', [CsvController::class, 'getActivitasList'])
    ->name('api.csv.activitas-list')
    ->withoutMiddleware('api');




