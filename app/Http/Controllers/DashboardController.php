<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Track;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Method privat untuk membangun query dasar barang berdasarkan filter.
     * Digunakan oleh index() dan searchRealtime().
     */
    private function getBarangQuery(Request $request)
    {
        $queryBuilder = Barang::query();

        // Filter berdasarkan perusahaan
        if ($request->filled('filter_perusahaan')) {
            $queryBuilder->where('perusahaan', $request->input('filter_perusahaan'));
        }

        // Filter berdasarkan jenis barang
        if ($request->filled('filter_jenis_barang')) {
            $queryBuilder->where('jenis_barang', $request->input('filter_jenis_barang'));
        }

        // Filter berdasarkan keyword pencarian (no asset, serial number, atau merek)
        if ($request->filled('search_no_asset')) {
            $searchKeyword = $request->input('search_no_asset');
            $queryBuilder->where(function ($query) use ($searchKeyword) {
                $query->where('no_asset', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('serial_number', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('merek', 'like', '%' . $searchKeyword . '%');
            });
        }
        return $queryBuilder;
    }

    /**
     * Method privat untuk menghitung summary inventaris.
     * Digunakan oleh index() dan bisa juga oleh searchRealtime() jika summary diupdate via AJAX.
     */
    private function calculateInventorySummary($baseQueryBuilder, $filterJenisBarangInput)
    {
        $itemTypesToCount = ['Laptop', 'HP', 'PC/AIO', 'Printer', 'Proyektor'];
        $inventorySummary = [];

        // Clone query builder agar query asli tidak terpengaruh
        $summaryQuery = clone $baseQueryBuilder;
        $itemCounts = $summaryQuery
                            ->select('jenis_barang', DB::raw('count(*) as total'))
                            ->groupBy('jenis_barang')
                            ->pluck('total', 'jenis_barang');

        foreach ($itemTypesToCount as $type) {
            if ($filterJenisBarangInput && $filterJenisBarangInput !== $type) {
                $inventorySummary[$type] = 0;
            } else {
                $inventorySummary[$type] = $itemCounts->get($type, 0);
            }
        }

        // Hitung 'Others'
        // Clone lagi query builder dasar untuk 'Others'
        $othersQueryBase = clone $baseQueryBuilder;
        if ($filterJenisBarangInput) {
            if (in_array($filterJenisBarangInput, $itemTypesToCount)) {
                $inventorySummary['Others'] = 0;
            } else {
                // Jika $filterJenisBarangInput adalah 'Others' atau jenis lain yang tidak terdaftar,
                // maka $itemCounts akan berisi count untuk $filterJenisBarangInput tersebut.
                // Kita perlu query ulang khusus untuk 'Others' dengan filter aktif (perusahaan, keyword, dan jenis != itemTypesToCount).
                $othersCount = $othersQueryBase->whereNotIn('jenis_barang', $itemTypesToCount)->count();
                $inventorySummary['Others'] = $othersCount;
            }
        } else {
            // Tidak ada filter jenis barang, hitung 'Others' dari $queryBuilder
            $othersCount = $othersQueryBase->whereNotIn('jenis_barang', $itemTypesToCount)->count();
            $inventorySummary['Others'] = $othersCount;
        }
        return $inventorySummary;
    }

    /**
     * Menampilkan halaman utama dashboard (load awal).
     */
    public function index(Request $request)
    {
        // Dapatkan query builder dasar dengan semua filter dari request
        $queryBuilder = $this->getBarangQuery($request);

        // Ambil nilai filter untuk dikirim ke view
        $searchKeyword = $request->input('search_no_asset');
        $filterPerusahaan = $request->input('filter_perusahaan');
        $filterJenisBarang = $request->input('filter_jenis_barang');

        // Clone query untuk tabel utama sebelum paginasi
        $tableQuery = clone $queryBuilder;
        $barangs = $tableQuery->orderBy('id', 'asc')->paginate(10);
        // Pastikan paginasi mempertahankan query string filter
        $barangs->appends($request->query());

        // Ambil opsi untuk dropdown filter (tidak terpengaruh filter aktif)
        $perusahaanOptions = Barang::select('perusahaan')->distinct()->orderBy('perusahaan')->pluck('perusahaan');
        $jenisBarangOptions = ['Laptop', 'HP', 'PC/AIO', 'Printer', 'Proyektor', 'Others'];


        // Hitung summary inventaris berdasarkan query builder yang sudah difilter
        $inventorySummary = $this->calculateInventorySummary($queryBuilder, $filterJenisBarang);

        return view('DasboardPage', compact(
            'barangs',
            'perusahaanOptions',
            'jenisBarangOptions',
            'inventorySummary',
            'searchKeyword',
            'filterPerusahaan',
            'filterJenisBarang'
        ));
    }

    /**
     * Menangani pencarian real-time via AJAX.
     */
    public function searchRealtime(Request $request)
    {
        // Dapatkan query builder dasar dengan semua filter dari request AJAX
        $queryBuilder = $this->getBarangQuery($request);

        $tableQuery = clone $queryBuilder;
        $barangs = $queryBuilder->orderBy('id', 'asc')->paginate(10);
        $barangs->appends($request->except('page'));

        // Jika Anda ingin summary juga diupdate via AJAX, hitung di sini:
        $filterJenisBarangAjax = $request->input('filter_jenis_barang');
        $inventorySummaryAjax = $this->calculateInventorySummary($queryBuilder, $filterJenisBarangAjax);
        // Kemudian kirim 'inventorySummaryAjax' ke view parsial atau sebagai bagian dari JSON.

        if ($request->ajax()) {
            // Kita akan mengirim data yang dibutuhkan untuk membangun tabel di JS
            // dan HTML untuk paginasi
            return response()->json([
                'data' => $barangs->items(),
                'links' => $barangs->links('pagination::bootstrap-4')->toHtml(), // HTML untuk paginasi
                'current_page' => $barangs->currentPage(),
                'first_item' => $barangs->firstItem(),
                'last_item' => $barangs->lastItem(),
                'total' => $barangs->total(),
                'per_page' => $barangs->perPage(),
                'inventorySummary' => $inventorySummaryAjax,
                'searchKeyword' => $request->input('search_no_asset'),
                'filterPerusahaan' => $request->input('filter_perusahaan'),
                'filterJenisBarang' => $request->input('filter_jenis_barang'),
            ]);
        }

        // Seharusnya tidak pernah sampai sini jika dipanggil dengan benar oleh JS
        return response('This endpoint is intended for AJAX requests only.', 400);
    }


    // ... (method getDetailBarang, getUserHistoryBySerialNumber, storeSerahTerimaAset tetap sama) ...
    public function getDetailBarang(Request $request, $id)
    {
        try {
            $barang = Barang::find($id);

            if (!$barang) {
                return response()->json(['error' => 'Data barang tidak ditemukan.'], 404);
            }

            $latestTrack = Track::where('serial_number', $barang->serial_number)
                                ->orderBy('created_at', 'desc')
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
            $history = Track::where('serial_number', $serial_number)
                            ->orderBy('tanggal_awal', 'asc')
                            ->get();

            if ($history->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'history' => []
                ]);
            }

            return response()->json([
                'success' => true,
                'history' => $history
            ]);

        } catch (\Exception $e) {
            Log::error("Error fetching user history for SN {$serial_number}: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Terjadi kesalahan server saat mengambil riwayat pengguna.'], 500);
        }
    }

    public function storeSerahTerimaAset(Request $request)
    {
        // PASTIKAN $rules DIDEFINISIKAN DI SINI, SEBELUM VALIDATOR
        $rules = [
            'serial_number' => 'required|string|exists:barang,serial_number',
            'username' => 'required|string|max:255',
            'status' => 'required|string|in:digunakan,diperbaiki,dipindah,non aktif,tersedia',
            'keterangan' => 'required|string',
            'asset_id' => 'required|integer|exists:barang,id',
            'perusahaan_tujuan' => 'required_if:status,dipindah|nullable|string|in:SCO,SCT,SCP,Migen',
        ];

        $messages = [
            'perusahaan_tujuan.required_if' => 'Perusahaan tujuan wajib diisi jika status aset adalah Dipindah.',
            'perusahaan_tujuan.in' => 'Perusahaan tujuan yang dipilih tidak valid.',
        ];

        // PASTIKAN VARIABEL YANG DIGUNAKAN DI SINI ADALAH $rules (DENGAN 's')
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            Log::error('Validation failed for serah terima: ', $validator->errors()->toArray());
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $serialNumber = $request->input('serial_number');
        $newStatus = $request->input('status');
        // $currentTime = null; // Tidak perlu diinisialisasi null jika akan di-assign Carbon::now()

        DB::beginTransaction();
        try {
            $currentTime = Carbon::now();
            Log::info("Attempting serah terima for SN: {$serialNumber}. New Status: {$newStatus}. Current time: " . $currentTime->toDateTimeString());

            $barang = Barang::find($request->input('asset_id'));
            if (!$barang) {
                Log::error("Barang with ID: {$request->input('asset_id')} not found for SN: {$serialNumber}. Transaction rolled back.");
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Data barang tidak ditemukan.'], 404);
            }

            $previousTrack = Track::where('serial_number', $serialNumber)
                                ->whereNull('tanggal_ahir')
                                ->orderBy('tanggal_awal', 'desc') // Seharusnya 'desc' untuk mendapatkan yang terbaru
                                ->first();

            if ($previousTrack) {
                // Logika untuk menutup track sebelumnya
                Log::info("Previous active track found. ID: {$previousTrack->id}, Username: {$previousTrack->username}, Tanggal Awal: {$previousTrack->tanggal_awal}");
                $previousTrack->tanggal_ahir = $currentTime->toDateString(); // Gunakan toDateString() jika hanya tanggal
                // Periksa jika tanggal akhir lebih kecil dari tanggal awal (seharusnya tidak terjadi jika currentTime selalu maju)
                if (Carbon::parse($previousTrack->tanggal_ahir)->lt(Carbon::parse($previousTrack->tanggal_awal))) {
                    Log::warning("Calculated tanggal_ahir ({$previousTrack->tanggal_ahir}) for previous track is earlier than its tanggal_awal ({$previousTrack->tanggal_awal}). This should not happen. Adjusting to tanggal_awal.");
                    $previousTrack->tanggal_ahir = $previousTrack->tanggal_awal;
                }
                $previousTrack->save();
                Log::info("Previous track (ID: {$previousTrack->id}) updated with tanggal_ahir: {$previousTrack->tanggal_ahir}");
            } else {
                Log::info("No active previous track found for SN: {$serialNumber}.");
            }

            // Membuat track baru
            Log::info("Creating new track for SN: {$serialNumber}, Username: {$request->input('username')}, Status: {$newStatus}, Tanggal Awal: " . $currentTime->toDateString());
            $newTrack = Track::create([
                'serial_number' => $serialNumber,
                'username' => $request->input('username'),
                'status' => $newStatus,
                'keterangan' => $request->input('keterangan'),
                'tanggal_awal' => $currentTime->toDateString(), // Gunakan toDateString() jika hanya tanggal
                'tanggal_ahir' => null,
            ]);
            Log::info("New track created with ID: {$newTrack->id}");

            // Update status dan perusahaan (jika dipindah) di tabel 'barang'
            Log::info("Updating status for Barang ID: {$barang->id} to: {$newStatus}");
            $barang->status = $newStatus; // Selalu update status barang dengan status track terbaru

            if ($newStatus === 'dipindah') {
                $perusahaanTujuan = $request->input('perusahaan_tujuan');
                if ($perusahaanTujuan) { // Pastikan perusahaan tujuan ada sebelum di-assign
                    Log::info("Status is 'dipindah'. Updating perusahaan for Barang ID: {$barang->id} to: {$perusahaanTujuan}");
                    $barang->perusahaan = $perusahaanTujuan;
                } else {
                    // Ini seharusnya sudah ditangani oleh validasi 'required_if', tapi sebagai fallback:
                    Log::warning("Status is 'dipindah' but perusahaan_tujuan is missing. Barang ID: {$barang->id}");
                }
            }
            
            $barang->save();
            Log::info("Barang ID: {$barang->id} status (and perusahaan if 'dipindah') updated successfully.");

            DB::commit();
            Log::info("Transaction committed successfully for SN: {$serialNumber}");
            return response()->json(['success' => true, 'message' => 'Data serah terima aset berhasil disimpan.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error storing serah terima aset for SN: {$serialNumber}. Message: " . $e->getMessage() . " Stack: " . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server saat menyimpan data. ' . $e->getMessage()], 500);
        }
    }
}