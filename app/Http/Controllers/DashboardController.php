<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; 

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard (yang juga bisa menampilkan login awal).
     * Method ini akan mengambil data barang dan mengirimkannya ke view.
     */
    public function index(Request $request)
    {
        // Memulai query builder
        $query = Barang::query();

        // Filter berdasarkan perusahaan
        $filterPerusahaan = $request->input('filter_perusahaan');
        if ($filterPerusahaan) {
            $query->where('perusahaan', $filterPerusahaan);
        }

        // Filter berdasarkan jenis barang
        $filterJenisBarang = $request->input('filter_jenis_barang');
        if ($filterJenisBarang) {
            $query->where('jenis_barang', $filterJenisBarang);
        }

        // Filter berdasarkan No Asset (dari search bar)
        $searchNoAsset = $request->input('search_no_asset');
        if ($searchNoAsset) {
            $query->where('no_asset', 'like', '%' . $searchNoAsset . '%');
        }

        // Ambil data barang yang sudah difilter, diurutkan, dan dipaginasi
        // Anda bisa menyesuaikan jumlah item per halaman (misal: 10 atau 15)
        $barangs = $query->orderBy('id', 'asc')->paginate(10); // Menggunakan paginate

        // (Opsional tapi direkomendasikan) Ambil opsi filter dinamis dari database
        $perusahaanOptions = Barang::select('perusahaan')->distinct()->orderBy('perusahaan')->pluck('perusahaan');
        $jenisBarangOptions = Barang::select('jenis_barang')->distinct()->orderBy('jenis_barang')->pluck('jenis_barang');

        // Kirim data ke view
        return view('DasboardPage', compact('barangs', 'perusahaanOptions', 'jenisBarangOptions', 'filterPerusahaan', 'filterJenisBarang', 'searchNoAsset'));
    }
}