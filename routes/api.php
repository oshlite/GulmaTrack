<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\ExcelDataController;

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

// Excel data endpoint
Route::get('/excel-data', [ExcelDataController::class, 'getExcelData'])
    ->name('api.excel.data')
    ->withoutMiddleware('api');

// Get kategori color mapping
Route::get('/kategori-colors', [\App\Http\Controllers\AdminController::class, 'getKategoriColors'])
    ->name('api.kategori.colors')
    ->withoutMiddleware('api');
