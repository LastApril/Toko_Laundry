<?php

use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanDetailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn () => redirect()->route('login'));

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::resource('produk', ProdukController::class);

    Route::get('pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
    Route::resource('pengeluaran', PengeluaranController::class);

    Route::get('penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
    Route::get('penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::get('penjualan/edit/{id}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::resource('penjualan', PenjualanController::class)->except('create', 'edit');

    Route::get('transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
    Route::get('transaksi/loadform/{total}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
    Route::resource('transaksi', PenjualanDetailController::class)->except('show');
});