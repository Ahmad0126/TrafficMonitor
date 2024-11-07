<?php

use App\Http\Controllers\Home;
use App\Http\Controllers\Jalan;
use App\Http\Controllers\Kendaraan;
use App\Http\Controllers\Traffic;
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

Route::get('/', [Home::class, 'index'])->name('base');
Route::get('/traffic', [Traffic::class, 'index'])->name('traffic');
Route::get('/traffic/filter', [Traffic::class, 'filter'])->name('filter_traffic');
Route::get('/kendaraan', [Kendaraan::class, 'index'])->name('kendaraan');
Route::post('/kendaraan/tambah', [Kendaraan::class, 'tambah_kendaraan'])->name('tambah_kendaraan');
Route::get('/jalan', [Jalan::class, 'index'])->name('jalan');
Route::post('/jalan/tambah', [Jalan::class, 'tambah_jalan'])->name('tambah_jalan');
