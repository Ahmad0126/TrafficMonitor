<?php

use App\Http\Controllers\Home;
use App\Http\Controllers\Jalan;
use App\Http\Controllers\Kendaraan;
use App\Http\Controllers\Traffic;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
Route::get('/traffic/graph', [Traffic::class, 'graph'])->name('graph_traffic');
Route::get('/traffic2', [Traffic::class, 'show'])->name('traffic2');
Route::get('/traffic2/filter', [Traffic::class, 'filter2'])->name('filter_traffic2');

Route::get('/kendaraan', [Kendaraan::class, 'index'])->name('kendaraan');
Route::post('/kendaraan/tambah', [Kendaraan::class, 'tambah_kendaraan'])->name('tambah_kendaraan');
Route::post('/kendaraan/edit', [Kendaraan::class, 'edit_kendaraan'])->name('edit_kendaraan');
Route::get('/kendaraan2', [Kendaraan::class, 'show'])->name('kendaraan2');

Route::get('/jalan', [Jalan::class, 'index'])->name('jalan');
Route::post('/jalan/tambah', [Jalan::class, 'tambah_jalan'])->name('tambah_jalan');
Route::post('/jalan/edit', [Jalan::class, 'edit_jalan'])->name('edit_jalan');
Route::get('/jalan2', [Jalan::class, 'show'])->name('jalan2');

Route::get('/tes', [Home::class, 'tes_grafik']);