<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ManagePageController;
use App\Http\Controllers\PerusahaanPageController;
use App\Http\Controllers\SuratController;

Route::get('/', function () {
    return redirect()->route('login');
});

// === GRUP UNTUK TAMU (Guest) ===
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// === GRUP UNTUK SEMUA USER YANG SUDAH LOGIN ===
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // --- Rute Dashboard & Detail (Bisa diakses semua user terotentikasi) ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/search-realtime', [DashboardController::class, 'searchRealtime'])->name('dashboard.search.realtime');
    Route::get('/dashboard/export', [DashboardController::class, 'exportExcel'])->name('dashboard.export');
    Route::get('/barang/detail/{id}', [DashboardController::class, 'getDetailBarang'])->name('barang.detail');
    Route::get('/history/user/{serial_number}', [DashboardController::class, 'getUserHistoryBySerialNumber'])->name('history.user.serial_number');

    // === GRUP KHUSUS UNTUK ADMIN & SUPER ADMIN ===
    Route::middleware('is_admin')->group(function () {

        // --- Manajemen Aset ---
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::post('/aset/serah-terima/store', [DashboardController::class, 'storeSerahTerimaAset'])->name('aset.serahterima.store');
        Route::get('/aset/nomor-seri-berikutnya/{perusahaan_id}', [BarangController::class, 'getNomorSeriBerikutnya'])->name('aset.get_nomor_seri');

        // --- Manajemen Perusahaan ---
        Route::resource('companies', PerusahaanPageController::class)->except(['create', 'show'])->middleware('is_admin');

        // --- Manajemen Surat ---
        Route::get('/serah-terima', [SuratController::class, 'index'])->name('surat.index');
        Route::get('/surat/get-next-nomor', [SuratController::class, 'getProspectiveNomor'])->name('surat.getProspectiveNomor');
        Route::get('/surat/search', [SuratController::class, 'searchRealtime'])->name('surat.search');
        Route::get('/surat/find-barang', [SuratController::class, 'findBarang'])->name('surat.find-barang');
        Route::get('/surat/download/{id}', [SuratController::class, 'downloadPdf'])->name('surat.download.pdf');
        Route::resource('surat', SuratController::class)->except(['index'])->middleware('is_admin'); // Menggunakan resource controller

        // --- Manajemen User ---
        Route::get('/users/{user}/edit', [ManagePageController::class, 'edit'])->name('users.edit');
        Route::post('/users/validate-field', [ManagePageController::class, 'validateField'])->name('users.validate.field');
        Route::resource('users', ManagePageController::class)->except(['edit'])->middleware('is_admin'); // Menggunakan resource controller
    });
});
