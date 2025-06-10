<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ManagePageController; 

Route::get('/', function () {
    return redirect()->route('login');
});

// === GRUP 1: RUTE UNTUK TAMU (Guest) ===
// Hanya bisa diakses jika user BELUM login.
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});


// === GRUP 2: RUTE UNTUK SEMUA USER YANG SUDAH LOGIN ===
// Memerlukan autentikasi (harus sudah login).
Route::middleware('auth')->group(function () {
    
    // Rute Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // --- Rute yang bisa diakses oleh 'admin' dan 'user' ---
    // Dashboard adalah halaman utama yang bisa dilihat semua orang.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/search-realtime', [DashboardController::class, 'searchRealtime'])->name('dashboard.search.realtime');
    
    // Semua user boleh melihat detail barang dan riwayatnya.
    Route::get('/barang/detail/{id}', [DashboardController::class, 'getDetailBarang'])->name('barang.detail');
    Route::get('/history/user/{serial_number}', [DashboardController::class, 'getUserHistoryBySerialNumber'])->name('history.user.serial_number');


    // === GRUP 3: RUTE KHUSUS UNTUK ADMIN ===
    // Memerlukan middleware 'is_admin' yang sudah kita buat.
    Route::middleware('is_admin')->group(function () {
        
        // --- Manajemen User (Hanya Admin) ---
        Route::get('/users', [ManagePageController::class, 'index'])->name('users.index');
        // Rute untuk search real-time user juga harus dilindungi
        // Route::get('/users/search', [ManagePageController::class, 'search'])->name('users.search'); // Dihapus jika pakai metode index()
        Route::post('/users/validate-field', [ManagePageController::class, 'validateField'])->name('users.validate.field');
        Route::post('/users', [ManagePageController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [ManagePageController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [ManagePageController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [ManagePageController::class, 'destroy'])->name('users.destroy');

        // --- Manajemen Aset (Hanya Admin) ---
        // Aksi seperti membuat, menyerahkan, dan mendapatkan nomor seri adalah tugas admin.
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::post('/aset/serah-terima/store', [DashboardController::class, 'storeSerahTerimaAset'])->name('aset.serahterima.store');
        Route::get('/aset/nomor-seri-berikutnya/{perusahaan_id}', [BarangController::class, 'getNomorSeriBerikutnya'])->name('aset.get_nomor_seri');
        
    });
});