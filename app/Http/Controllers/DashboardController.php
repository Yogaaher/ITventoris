<?php

namespace App\Http\Controllers;

use App\Models\Barang; // <-- Pastikan model Barang di-import
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; 

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard (yang juga bisa menampilkan login awal).
     * Method ini akan mengambil data barang dan mengirimkannya ke view.
     */
    public function index()
    {
        // Ambil semua data barang dari database
        // Anda bisa menambahkan orderBy atau paginate di sini jika perlu
        // Contoh: Barang::orderBy('tgl_pengadaan', 'desc')->paginate(10);
        $barangs = Barang::orderBy('id', 'asc')->get();

        // Kirim data ke view utama 'main_layout.blade.php'
        // View ini akan berisi HTML login dan dashboard Anda.
        return view('DasboardPage', compact('barangs'));
    }
}