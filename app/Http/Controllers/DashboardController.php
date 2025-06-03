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
     * Menampilkan halaman utama dashboard (yang juga bisa menampilkan login awal).
     * Method ini akan mengambil data barang dan mengirimkannya ke view.
     */
    public function index(Request $request)
    {
        // Memulai query builder
        $queryBuilder = Barang::query(); // Kita akan menggunakan ini sebagai basis untuk tabel dan summary

        // Filter berdasarkan perusahaan
        $filterPerusahaan = $request->input('filter_perusahaan');
        if ($filterPerusahaan) {
            $queryBuilder->where('perusahaan', $filterPerusahaan);
        }

        // Filter berdasarkan jenis barang
        $filterJenisBarang = $request->input('filter_jenis_barang');
        if ($filterJenisBarang) {
            $queryBuilder->where('jenis_barang', $filterJenisBarang);
        }

        // Filter berdasarkan No Asset (dari search bar)
        $searchKeyword = $request->input('search_no_asset');
        if ($searchKeyword) {
            $queryBuilder->where(function ($query) use ($searchKeyword) {
                $query->where('no_asset', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('serial_number', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('merek', 'like', '%' . $searchKeyword . '%');
            });
        }

        // Ambil data barang yang sudah difilter untuk tabel utama (dengan pagination)
        // Clone queryBuilder sebelum menambahkan orderBy dan paginate agar query dasar untuk summary tidak terpengaruh
        $tableQuery = clone $queryBuilder;
        $barangs = $tableQuery->orderBy('id', 'asc')->paginate(10);

        // (Opsional tapi direkomendasikan) Ambil opsi filter dinamis dari database (TIDAK terpengaruh filter aktif)
        $perusahaanOptions = Barang::select('perusahaan')->distinct()->orderBy('perusahaan')->pluck('perusahaan');
        $jenisBarangOptions = Barang::select('jenis_barang')->distinct()->orderBy('jenis_barang')->pluck('jenis_barang');

        // +++ AWAL LOGIKA BARU UNTUK COUNT ITEM (BERDASARKAN FILTER AKTIF) +++
        $itemTypesToCount = ['Laptop', 'HP', 'PC/AIO', 'Printer', 'Proyektor']; // 'Others' akan dihitung terpisah

        // Gunakan $queryBuilder (yang sudah berisi filter aktif) untuk menghitung item counts
        // Penting: Clone $queryBuilder agar select dan groupBy tidak memengaruhi query asli jika digunakan lagi
        $filteredItemCountsQuery = clone $queryBuilder;
        $itemCounts = $filteredItemCountsQuery
                            ->select('jenis_barang', DB::raw('count(*) as total'))
                            ->groupBy('jenis_barang')
                            ->pluck('total', 'jenis_barang');

        $inventorySummary = [];
        foreach ($itemTypesToCount as $type) {
            // Jika filter jenis_barang aktif dan BUKAN $type ini, maka countnya harus 0
            // Jika filter jenis_barang aktif dan SAMA DENGAN $type ini, ambil dari $itemCounts
            // Jika tidak ada filter jenis_barang, ambil dari $itemCounts
            if ($filterJenisBarang && $filterJenisBarang !== $type) {
                $inventorySummary[$type] = 0;
            } else {
                $inventorySummary[$type] = $itemCounts->get($type, 0);
            }
        }

        // Hitung 'Others' berdasarkan filter yang aktif
        // Jika filterJenisBarang aktif dan BUKAN salah satu dari $itemTypesToCount, maka itu adalah 'Others'
        // Jika filterJenisBarang aktif dan SALAH SATU dari $itemTypesToCount, maka 'Others' pasti 0
        // Jika tidak ada filterJenisBarang, hitung seperti biasa.
        if ($filterJenisBarang) {
            if (in_array($filterJenisBarang, $itemTypesToCount)) {
                $inventorySummary['Others'] = 0; // Karena jenis spesifik sudah difilter, 'Others' tidak mungkin ada
            } else {
                // Jika $filterJenisBarang adalah 'Others' atau jenis lain yang tidak terdaftar,
                // maka $itemCounts akan berisi count untuk $filterJenisBarang tersebut.
                // Kita perlu query ulang khusus untuk 'Others' dengan filter aktif.
                $othersQuery = clone $queryBuilder; // Sudah punya filter perusahaan, dan filter jenis_barang
                $othersCount = $othersQuery->whereNotIn('jenis_barang', $itemTypesToCount)->count();
                $inventorySummary['Others'] = $othersCount;
            }
        } else {
            // Tidak ada filter jenis barang, hitung 'Others' dari $queryBuilder
            $othersQuery = clone $queryBuilder;
            $othersCount = $othersQuery->whereNotIn('jenis_barang', $itemTypesToCount)->count();
            $inventorySummary['Others'] = $othersCount;
        }
        // +++ AKHIR LOGIKA BARU UNTUK COUNT ITEM +++

        // Kirim data ke view
        return view('DasboardPage', compact('barangs', 'perusahaanOptions', 'jenisBarangOptions', 'filterPerusahaan', 'filterJenisBarang', 'searchKeyword', 'inventorySummary'));
    }

    // ... (sisa method controller Anda tetap sama) ...
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

    public function storeSerahTerimaAset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'serial_number' => 'required|string|exists:barang,serial_number',
            'username' => 'required|string|max:255',
            'status' => 'required|string|in:digunakan,diperbaiki,dipindah,non aktif,tersedia',
            'keterangan' => 'required|string',
            'asset_id' => 'required|integer|exists:barang,id'
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed for serah terima: ', $validator->errors()->toArray());
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $serialNumber = $request->input('serial_number');
        $currentTime = null;

        DB::beginTransaction();
        try {
            $currentTime = Carbon::now();
            Log::info("Attempting serah terima for SN: {$serialNumber}. Current time: " . $currentTime->toDateTimeString());

            // 1. Update tanggal_ahir untuk track sebelumnya (jika ada yang aktif)
            Log::info("Searching for previous active track for SN: {$serialNumber}");
            $previousTrack = Track::where('serial_number', $serialNumber)
                                ->whereNull('tanggal_ahir') // Cari yang masih aktif
                                ->orderBy('tanggal_awal', 'desc') // Ambil yang paling baru
                                ->first();

            if ($previousTrack) {
                Log::info("Previous active track found. ID: {$previousTrack->id}, Username: {$previousTrack->username}, Current tanggal_ahir: {$previousTrack->tanggal_ahir}, Tanggal Awal: {$previousTrack->tanggal_awal}");
                Log::info("Setting tanggal_ahir for previous track to: " . $currentTime->toDateString());

                $previousTrack->tanggal_ahir = $currentTime->toDateString();

                // Pastikan tanggal_ahir tidak lebih awal dari tanggal_awal milik track sebelumnya itu sendiri
                if (Carbon::parse($previousTrack->tanggal_ahir)->lessThan(Carbon::parse($previousTrack->tanggal_awal))) {
                    Log::warning("Calculated tanggal_ahir ({$previousTrack->tanggal_ahir}) for previous track is earlier than its tanggal_awal ({$previousTrack->tanggal_awal}). Adjusting tanggal_ahir to be same as tanggal_awal.");
                    $previousTrack->tanggal_ahir = $previousTrack->tanggal_awal;
                }

                Log::info("Attempting to save previous track (ID: {$previousTrack->id}) with new tanggal_ahir: {$previousTrack->tanggal_ahir}");
                if ($previousTrack->save()) {
                    Log::info("Previous track (ID: {$previousTrack->id}) saved successfully with tanggal_ahir: {$previousTrack->tanggal_ahir}");
                } else {
                    Log::error("Failed to save previous track (ID: {$previousTrack->id}) after setting tanggal_ahir.");
                    // Anda mungkin ingin melempar exception di sini agar transaksi di-rollback
                    // throw new \Exception("Failed to save previous track data.");
                }
            } else {
                Log::info("No active previous track found for SN: {$serialNumber}. No update to tanggal_ahir needed for a previous record.");
            }

            // 2. Buat entri track baru dengan tanggal_awal = now()
            Log::info("Creating new track for SN: {$serialNumber}, Username: {$request->input('username')}, Tanggal Awal: " . $currentTime->toDateString());
            $newTrack = Track::create([
                'serial_number' => $serialNumber,
                'username' => $request->input('username'),
                'status' => $request->input('status'),
                'keterangan' => $request->input('keterangan'),
                'tanggal_awal' => $currentTime->toDateString(),
                'tanggal_ahir' => null,
            ]);
            Log::info("New track created with ID: {$newTrack->id}");

            // 3. Update status di tabel barang (master aset)
            $barang = Barang::find($request->input('asset_id'));
            if ($barang) {
                Log::info("Updating status for Barang ID: {$barang->id} to: {$request->input('status')}");
                $barang->status = $request->input('status');
                // $barang->user_terakhir = $request->input('username'); // Jika ada field ini
                // $barang->tgl_serah_terima_terakhir = $currentTime->toDateString(); // Jika ada field ini
                if ($barang->save()) {
                    Log::info("Barang ID: {$barang->id} status updated successfully.");
                } else {
                    Log::error("Failed to update status for Barang ID: {$barang->id}.");
                    // throw new \Exception("Failed to update barang status.");
                }
            } else {
                Log::warning("Barang with ID: {$request->input('asset_id')} not found for SN: {$serialNumber}. Cannot update status.");
            }

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