<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

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

    public function getDetailBarang(Request $request, $id)
    {
        try {
            $barang = Barang::find($id);

            if (!$barang) {
                return response()->json(['error' => 'Data barang tidak ditemukan.'], 404);
            }

            // Mengambil track user terbaru berdasarkan tanggal_awal (penyerahan)
            // dan pastikan hanya track yang masih aktif (tanggal_ahir masih null atau di masa depan)
            // atau ambil saja yang paling terakhir berdasarkan tanggal_awal
            $latestTrack = Track::where('no_asset', $barang->no_asset)
                                ->orderBy('tanggal_awal', 'desc') // Urutkan berdasarkan tanggal awal terbaru
                                ->first();

            return response()->json([
                'success' => true,
                'barang' => $barang,
                'track' => $latestTrack
            ]);

        } catch (\Exception $e) {
            Log::error("Error fetching detail barang: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data.'], 500);
        }
    }
}