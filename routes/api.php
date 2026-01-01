<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\ExcelDataController;
use App\Http\Controllers\ImportLogController;

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

Route::get('/wilayah/data', [WilayahController::class, 'getData'])
    ->name('api.wilayah.data')
    ->withoutMiddleware('api'); // Bypass API middleware if needed

Route::get('/wilayah/periods', [WilayahController::class, 'getPeriods'])
    ->name('api.wilayah.periods')
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
    ->withoutMiddleware('api');

// Map publications - get latest published
Route::get('/map-publications/latest-published', [\App\Http\Controllers\AdminController::class, 'getLatestPublished'])
    ->name('api.map-publications.latest')
    ->withoutMiddleware('api');

// Data gulma by import ID
Route::get('/data-gulma/by-import/{importId}', [\App\Http\Controllers\AdminController::class, 'getDataByImport'])
    ->name('api.data-gulma.by-import')
    ->withoutMiddleware('api');

// Import logs - get list with filters
Route::get('/import-logs', [\App\Http\Controllers\AdminController::class, 'getImportLogs'])
    ->name('api.import-logs')
    ->withoutMiddleware('api');

// Debug endpoint - check data in database
Route::get('/debug/import/{importId}', [\App\Http\Controllers\AdminController::class, 'debugImport'])
    ->name('api.debug.import')
    ->withoutMiddleware('api');

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




