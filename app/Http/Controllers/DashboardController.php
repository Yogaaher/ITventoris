<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Track;
use App\Models\Perusahaan;
use App\Models\JenisBarang;
use App\Models\AssetCounter;
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
        $queryBuilder = Barang::query()->with(['perusahaan', 'jenisBarang']);

        // Filter berdasarkan perusahaan
        if ($request->filled('filter_perusahaan')) {
            $queryBuilder->where('perusahaan_id', $request->input('filter_perusahaan'));
        }

        // Filter berdasarkan jenis barang
        if ($request->filled('filter_jenis_barang')) {
            $queryBuilder->where('jenis_barang_id', $request->input('filter_jenis_barang'));
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
        $allJenisBarang = JenisBarang::pluck('nama_jenis', 'id');
        $inventorySummary = array_fill_keys($allJenisBarang->values()->all(), 0);
        $summaryQuery = clone $baseQueryBuilder;
        $itemCounts = $summaryQuery
            ->select('jenis_barang_id', DB::raw('count(*) as total'))
            ->groupBy('jenis_barang_id')
            ->pluck('total', 'jenis_barang_id');

        foreach ($itemCounts as $jenisId => $count) {
            $namaJenis = $allJenisBarang->get($jenisId);
            if ($namaJenis) {
                $inventorySummary[$namaJenis] = $count;
            }
        }

        if ($filterJenisBarangInput) {
            $filteredNamaJenis = $allJenisBarang->get($filterJenisBarangInput);
            if ($filteredNamaJenis) {
                foreach ($inventorySummary as $nama => &$nilai) {
                    if ($nama !== $filteredNamaJenis) {
                        $nilai = 0;
                    }
                }
            }
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
        $perPage = $request->input('per_page', 10);

        $searchKeyword = $request->input('search_no_asset');
        $filterPerusahaan = $request->input('filter_perusahaan');
        $filterJenisBarang = $request->input('filter_jenis_barang');

        // Clone query untuk tabel utama sebelum paginasi
        $tableQuery = clone $queryBuilder;
        $barangs = $tableQuery->orderBy('id', 'asc')->paginate($perPage);
        // Pastikan paginasi mempertahankan query string filter
        $barangs->appends($request->query());

        // Ambil opsi untuk dropdown filter (tidak terpengaruh filter aktif)
        $perusahaanOptions = Perusahaan::orderBy('nama_perusahaan')->get();
        $jenisBarangOptions = JenisBarang::orderBy('nama_jenis')->get();


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
        $perPage = $request->input('per_page', 10);

        $tableQuery = clone $queryBuilder;
        $barangs = $tableQuery->with(['perusahaan', 'jenisBarang'])->orderBy('id', 'asc')->paginate($perPage);
        $barangs->appends($request->except('page'));

        // Jika Anda ingin summary juga diupdate via AJAX, hitung di sini:
        $filterJenisBarangAjax = $request->input('filter_jenis_barang');
        $inventorySummaryAjax = $this->calculateInventorySummary($queryBuilder, $filterJenisBarangAjax);
        // Kemudian kirim 'inventorySummaryAjax' ke view parsial atau sebagai bagian dari JSON.

        if ($request->ajax()) {
            // Transformasi data untuk menyertakan nama dari relasi
            $transformedData = $barangs->getCollection()->map(function ($barang) {
                return [
                    'id' => $barang->id,
                    'perusahaan_nama' => $barang->perusahaan->nama_perusahaan ?? 'N/A',
                    'perusahaan_singkatan' => $barang->perusahaan->singkatan ?? 'N/A',
                    'jenis_barang' => $barang->jenisBarang->nama_jenis ?? 'N/A',
                    'no_asset' => $barang->no_asset,
                    'merek' => $barang->merek,
                    'tgl_pengadaan' => $barang->tgl_pengadaan,
                    'serial_number' => $barang->serial_number,
                ];
            });

            return response()->json([
                'data' => $transformedData,
                'pagination' => [
                    'current_page' => $barangs->currentPage(),
                    'first_item' => $barangs->firstItem(),
                    'last_item' => $barangs->lastItem(),
                    'total' => $barangs->total(),
                    'per_page' => $barangs->perPage(),
                    'last_page' => $barangs->lastPage(),
                ],
                'inventorySummary' => $inventorySummaryAjax,
                'searchKeyword' => $request->input('search_no_asset'),
                'filterPerusahaan' => $request->input('filter_perusahaan'),
                'filterJenisBarang' => $request->input('filter_jenis_barang'),
            ]);
        }

        return response('This endpoint is intended for AJAX requests only.', 400);
    }


    // ... (method getDetailBarang, getUserHistoryBySerialNumber, storeSerahTerimaAset tetap sama) ...
    public function getDetailBarang(Request $request, $id)
    {
        try {
            $barang = Barang::with(['perusahaan', 'jenisBarang'])->find($id);

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
            'perusahaan_tujuan' => 'required_if:status,dipindah|nullable|integer|exists:perusahaans,id',
        ];

        $messages = [
            'perusahaan_tujuan.required_if' => 'Perusahaan tujuan wajib diisi jika status aset adalah Dipindah.',
        ];

        // PASTIKAN VARIABEL YANG DIGUNAKAN DI SINI ADALAH $rules (DENGAN 's')
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $barang = Barang::find($request->input('asset_id'));
        if (!$barang) {
            return response()->json(['success' => false, 'message' => 'Data barang tidak ditemukan.'], 404);
        }

        if ($barang->status === 'non aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Aset yang berstatus "non aktif" tidak dapat diupdate lagi.'
            ], 403);
        }

        if ($request->input('status') === 'dipindah') {
            // Cek jika perusahaan tujuan sama dengan perusahaan saat ini
            if ($request->input('perusahaan_tujuan') == $barang->perusahaan_id) {
                // Kirim kembali dengan pesan error spesifik
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'perusahaan_tujuan' => ['Tidak bisa memindahkan aset ke perusahaan yang sama.']
                    ]
                ], 422);
            }
        }

        $newStatus = $request->input('status');
        // $currentTime = null; // Tidak perlu diinisialisasi null jika akan di-assign Carbon::now()

        DB::beginTransaction();
        try {
            $currentTime = Carbon::now();
            $barang = Barang::with('jenisBarang')->find($request->input('asset_id'));

            if (!$barang) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Data barang tidak ditemukan.'], 404);
            }

            // --- LOGIKA UTAMA PERPINDAHAN ASET ---
            if ($request->input('status') === 'dipindah') {
                $perusahaanTujuanId = $request->input('perusahaan_tujuan');
                $perusahaanTujuan = Perusahaan::find($perusahaanTujuanId);

                // --- AWAL LOGIKA BARU YANG ANDAL UNTUK PERUSAHAAN TUJUAN ---

                // 1. Ambil atau buat counter untuk perusahaan TUJUAN. Kunci barisnya!
                $counterTujuan = AssetCounter::lockForUpdate()
                    ->firstOrCreate(
                        ['perusahaan_id' => $perusahaanTujuanId],
                        ['nomor_terakhir' => 0]
                    );

                // 2. Increment nomor
                $nomorBaru = $counterTujuan->nomor_terakhir + 1;

                // 3. Simpan nomor baru kembali ke database
                $counterTujuan->nomor_terakhir = $nomorBaru;
                $counterTujuan->save();

                // 4. Format nomor (padding)
                $eeee = str_pad($nomorBaru, 4, '0', STR_PAD_LEFT);

                // --- AKHIR LOGIKA BARU ---

                // Bagian ini tetap sama
                $aaa = $perusahaanTujuan->singkatan;
                $bbb = $barang->jenisBarang->singkatan;
                $cccc = date('Y');
                $dd = date('m');
                $noAssetBaru = "{$aaa}/{$bbb}/{$cccc}/{$dd}/{$eeee}";

                // Update data barang
                $barang->perusahaan_id = $perusahaanTujuanId;
                $barang->no_asset = $noAssetBaru;
            }

            // Update status di tabel barang (ini tetap berjalan untuk semua status)
            $barang->status = $request->input('status');
            $barang->save();
            // --- AKHIR LOGIKA UTAMA ---

            // Proses update tabel 'track' (tetap sama)
            $previousTrack = Track::where('serial_number', $barang->serial_number)
                ->whereNull('tanggal_ahir')
                ->orderBy('tanggal_awal', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            if ($previousTrack) {
                // --- TAMBAHKAN VALIDASI DI SINI ---
                $tanggalAwalPrevious = Carbon::parse($previousTrack->tanggal_awal);

                // Cek apakah tanggal sekarang ($currentTime) lebih dulu dari tanggal terima sebelumnya.
                // Ini jarang terjadi, tapi bisa karena kesalahan setting jam server.
                if ($currentTime->lessThan($tanggalAwalPrevious)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'keterangan' => ['Tanggal serah terima tidak bisa lebih awal dari tanggal penerimaan sebelumnya.']
                        ]
                    ], 422);
                }
                // --- AKHIR VALIDASI ---

                $previousTrack->tanggal_ahir = $currentTime->toDateString();
                $previousTrack->save();
            }

            Track::create([
                'serial_number' => $barang->serial_number,
                'username' => $request->input('username'),
                'status' => $request->input('status'),
                'keterangan' => $request->input('keterangan'),
                'tanggal_awal' => $currentTime->toDateString(),
                'tanggal_ahir' => null,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data serah terima aset berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error storing serah terima aset: " . $e->getMessage() . " Stack: " . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }
}
