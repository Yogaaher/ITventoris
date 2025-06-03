<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
Route::get('/barang/detail/{id}', [DashboardController::class, 'getDetailBarang'])->name('barang.detail');
Route::get('/history/user/{serial_number}', [App\Http\Controllers\DashboardController::class, 'getUserHistoryBySerialNumber'])->name('history.user.serial_number');
Route::post('/aset/serah-terima/store', [DashboardController::class, 'storeSerahTerimaAset'])->name('aset.serahterima.store');