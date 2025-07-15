<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ManagePageController;
use App\Http\Controllers\PerusahaanPageController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\JenisBarangController;
use Illuminate\Support\Facades\Request;

Route::get('/props', function (Request $request) {
    dd($request);
});

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

    Route::get('/serah-terima', [SuratController::class, 'index'])->name('surat.index');
    Route::get('/manajemen-data', [PerusahaanPageController::class, 'index'])->name('companies.index');

    // --- Rute yang berhubungan dengan pengambilan data untuk halaman view-only
    Route::get('/surat/search', [SuratController::class, 'searchRealtime'])->name('surat.search');
    Route::get('/surat/{surat}', [SuratController::class, 'show'])->name('surat.show');
    Route::get('/companies-data', [PerusahaanPageController::class, 'getCompanyData'])->name('companies.data');
    Route::get('/item-types-data', [JenisBarangController::class, 'index'])->name('item-types.data');

    // === GRUP KHUSUS UNTUK ADMIN & SUPER ADMIN ===
    Route::middleware('is_admin')->group(function () {

        // --- Manajemen Aset ---
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::post('/aset/serah-terima/store', [DashboardController::class, 'storeSerahTerimaAset'])->name('aset.serahterima.store');
        Route::get('/aset/nomor-seri-berikutnya/{perusahaan_id}', [BarangController::class, 'getNomorSeriBerikutnya'])->name('aset.get_nomor_seri');
        Route::get('/barang/{barang}/edit', [DashboardController::class, 'edit'])->name('barang.edit');
        Route::put('/barang/{barang}', [DashboardController::class, 'update'])->name('barang.update');
        Route::delete('/barang/{barang}', [DashboardController::class, 'destroy'])->name('barang.destroy');
        Route::get('/track/{track}/edit', [DashboardController::class, 'editTrack']);
        Route::put('/track/{track}', [DashboardController::class, 'updateTrack']);
        Route::delete('/track/{track}', [DashboardController::class, 'destroyTrack']);

        // --- Aksi untuk Perusahaan (semua via AJAX) ---
        Route::post('/companies', [PerusahaanPageController::class, 'store'])->name('companies.store');
        Route::get('/companies/{company}/edit', [PerusahaanPageController::class, 'edit'])->name('companies.edit');
        Route::put('/companies/{company}', [PerusahaanPageController::class, 'update'])->name('companies.update');
        Route::delete('/companies/{company}', [PerusahaanPageController::class, 'destroy'])->name('companies.destroy');

        // --- Aksi untuk Jenis Barang (semua via AJAX) ---
        Route::post('/item-types', [JenisBarangController::class, 'store'])->name('item-types.store');
        Route::get('/item-types/{item_type}/edit', [JenisBarangController::class, 'edit'])->name('item-types.edit');
        Route::put('/item-types/{item_type}', [JenisBarangController::class, 'update'])->name('item-types.update');
        Route::delete('/item-types/{item_type}', [JenisBarangController::class, 'destroy'])->name('item-types.destroy');

        // --- Manajemen Surat ---
        Route::get('/surat/get-next-nomor', [SuratController::class, 'getProspectiveNomor'])->name('surat.getProspectiveNomor');
        Route::get('/surat/find-barang', [SuratController::class, 'findBarang'])->name('surat.find-barang');
        Route::get('/surat/download/{id}', [SuratController::class, 'downloadPdf'])->name('surat.download.pdf');
        Route::resource('surat', SuratController::class)->except(['index', 'show'])->middleware('is_admin');

        // --- Manajemen User ---
        Route::get('/users/{user}/edit', [ManagePageController::class, 'edit'])->name('users.edit');
        Route::post('/users/validate-field', [ManagePageController::class, 'validateField'])->name('users.validate.field');
        Route::resource('users', ManagePageController::class)->except(['edit'])->middleware('is_admin'); // Menggunakan resource controller
    });
});
