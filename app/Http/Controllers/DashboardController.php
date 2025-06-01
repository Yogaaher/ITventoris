<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

                // +++ AWAL LOGIKA BARU UNTUK COUNT ITEM +++
        $itemTypesToCount = ['Laptop', 'HP', 'PC/AIO', 'Printer', 'Proyektor', 'Others'];
        
        $itemCounts = Barang::select('jenis_barang', DB::raw('count(*) as total'))
                            ->groupBy('jenis_barang')
                            ->pluck('total', 'jenis_barang'); // Hasilnya: ['Laptop' => 5, 'HP' => 3, ...]

        $inventorySummary = [];
        $totalSpecificItems = 0;

        foreach ($itemTypesToCount as $type) {
            $count = $itemCounts->get($type, 0); // Ambil count, atau 0 jika tidak ada
            $inventorySummary[$type] = $count;
            $totalSpecificItems += $count;
        }

        // Hitung 'Others'
        // Cara 1: Total semua barang dikurangi total barang spesifik
        // $totalAllBarang = Barang::count();
        // $inventorySummary['Others'] = $totalAllBarang - $totalSpecificItems;
        
        // Cara 2 (lebih akurat jika ada jenis barang lain yang tidak masuk daftar):
        $othersCount = Barang::whereNotIn('jenis_barang', $itemTypesToCount)->count();
        $inventorySummary['Others'] = $othersCount;

        // +++ AKHIR LOGIKA BARU UNTUK COUNT ITEM +++

        // Kirim data ke view
        return view('DasboardPage', compact('barangs', 'perusahaanOptions', 'jenisBarangOptions', 'filterPerusahaan', 'filterJenisBarang', 'searchNoAsset', 'inventorySummary'));
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
            $latestTrack = Track::where('serial_number', $barang->serial_number)
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

    public function getUserHistoryBySerialNumber(Request $request, $serial_number)
    {
        try {
            // Cari semua entri track berdasarkan serial_number, urutkan berdasarkan tanggal_awal (ASC: dari terlama ke terbaru)
            $history = Track::where('serial_number', $serial_number)
                            ->orderBy('tanggal_awal', 'asc') // Mengurutkan dari yang paling lama
                            ->get(); // Ambil semua data yang cocok

            if ($history->isEmpty()) {
                // Jika tidak ada history, kembalikan array kosong untuk history
                return response()->json([
                    'success' => true, // Operasi berhasil, tapi tidak ada data
                    'history' => []
                ]);
            }

            return response()->json([
                'success' => true,
                'history' => $history
            ]);

        } catch (\Exception $e) {
            Log::error("Error fetching user history for SN {$serial_number}: " . $e->getMessage());
            // Kembalikan response error yang jelas
            return response()->json(['success' => false, 'error' => 'Terjadi kesalahan server saat mengambil riwayat pengguna.'], 500);
        }
    }
}