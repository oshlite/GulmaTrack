<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\GulmaController;
use App\Http\Controllers\ExcelDataController;
use App\Http\Controllers\GalleryController;

/*
|--------------------------------------------------------------------------
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/
Route::view('/', 'pages.home')->name('home');
Route::view('/statistik', 'pages.statistik')->name('statistik');
Route::view('/tentang', 'pages.about')->name('about');

Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah');

/*
|--------------------------------------------------------------------------
| PUBLIC DATA / API
|--------------------------------------------------------------------------
*/
Route::get('/data/excel', [ExcelDataController::class, 'getExcelData'])
    ->name('data.excel');

/*
|--------------------------------------------------------------------------
| API - WILAYAH (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('api/wilayah')
    ->name('api.wilayah.')
    ->group(function () {
        Route::get('/data', [WilayahController::class, 'getData'])->name('data');
        Route::get('/geojson/{wilayah}', [WilayahController::class, 'getGeojson'])->name('geojson');
        Route::get('/periods', [WilayahController::class, 'getPeriods'])->name('periods');
        Route::get('/data-by-period', [WilayahController::class, 'getDataByPeriod'])->name('data-by-period');
    });

/*
|--------------------------------------------------------------------------
| API - STATISTIK (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('api/statistik')
    ->name('api.statistik.')
    ->group(function () {
        Route::get('/summary', [GulmaController::class, 'getStatistikSummary'])->name('summary');
        Route::get('/ranking', [GulmaController::class, 'getStatistikRanking'])->name('ranking');
        Route::get('/productivity', [GulmaController::class, 'getStatistikProductivity'])->name('productivity');
        Route::get('/yearly-comparison', [GulmaController::class, 'getYearlyComparison'])->name('yearly');
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
        | ADMIN API
        |--------------------------------------------------------------------------
        */
        Route::prefix('api')->name('api.')->group(function () {

            /* Gulma & Map */
            Route::get('/geojson/{wilayah}', [GulmaController::class, 'getGeoJSONWithData'])->name('geojson');
            Route::get('/data-gulma', [GulmaController::class, 'getDataGulma'])->name('data-gulma');
            Route::get('/statistics', [GulmaController::class, 'getStatistics'])->name('statistics');
            Route::get('/kategori-colors', [AdminController::class, 'getKategoriColors'])->name('kategori-colors');

            /* Gallery API */
            Route::prefix('gallery')->name('gallery.')->group(function () {
                Route::get('/location/{wilayah}/{lokasi}', [GalleryController::class, 'getByLocation'])
                    ->name('by-location');
                Route::get('/photos', [GalleryController::class, 'getPhotos'])
                    ->name('photos');
            });
        });
    });
