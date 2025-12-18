<?php

use Illuminate\Support\Facades\Route;

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

// GulmaTrack Pages Routes
Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/wilayah', function () {
    return view('pages.wilayah');
})->name('wilayah');

Route::get('/nanas', function () {
    return view('pages.nanas');
})->name('nanas');

Route::get('/pisang', function () {
    return view('pages.pisang');
})->name('pisang');


Route::get('/statistik', function () {
    return view('pages.statistik');
})->name('statistik');

Route::get('/tentang', function () {
    return view('pages.about');
})->name('about');

Route::get('/kontak', function () {
    return view('pages.contact');
})->name('contact');
