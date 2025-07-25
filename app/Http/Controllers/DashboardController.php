<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Track;
use App\Models\Perusahaan;
use App\Models\JenisBarang;
use App\Models\AssetCounter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelWriter;

class DashboardController extends Controller
{
    /**
     * Method privat untuk membangun query dasar barang berdasarkan filter.
     * Digunakan oleh index() dan searchRealtime().
     */
    private function getBarangQuery(Request $request)
    {
        $queryBuilder = Barang::query()
            ->select('barang.*')
            ->leftJoin('perusahaans', 'barang.perusahaan_id', '=', 'perusahaans.id')
            ->leftJoin('jenis_barangs', 'barang.jenis_barang_id', '=', 'jenis_barangs.id')
            ->with(['perusahaan', 'jenisBarang']);

        // Filter berdasarkan perusahaan
        if ($request->filled('filter_perusahaan')) {
            $queryBuilder->where('perusahaan_id', $request->input('filter_perusahaan'));
        }

        // Filter berdasarkan jenis barang
        if ($request->filled('filter_jenis_barang') && is_array($request->input('filter_jenis_barang'))) {
            $queryBuilder->whereIn('barang.jenis_barang_id', $request->input('filter_jenis_barang'));
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

    public function exportExcel(Request $request)
    {
        $queryBuilder = $this->getBarangQuery($request);
        $sortableColumns = [
            'perusahaan'     => 'perusahaans.nama_perusahaan',
            'jenis_barang'   => 'jenis_barangs.nama_jenis',
            'no_asset'       => 'barang.no_asset',
            'merek'          => 'barang.merek',
            'kuantitas'      => 'barang.kuantitas',
            'tgl_pengadaan'  => 'barang.tgl_pengadaan',
            'serial_number'  => 'barang.serial_number',
            'lokasi'         => 'barang.lokasi',
        ];

        $sortBy = $request->input('sort_by', 'no');
        $sortDirection = $request->input('sort_direction', 'asc');
        $direction = strtolower($sortDirection) === 'desc' ? 'desc' : 'asc';

        if ($sortBy === 'no') {
            $queryBuilder->orderBy('barang.id', $direction);
        } elseif (array_key_exists($sortBy, $sortableColumns)) {
            $queryBuilder->orderBy($sortableColumns[$sortBy], $direction);
        }
        
        $barangs = $queryBuilder->with(['perusahaan', 'jenisBarang', 'tracks'])->get();

        if ($barangs->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk diekspor sesuai filter yang dipilih.');
        }
        
        $fileName = 'Laporan_Aset_Scuto_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        $writer = SimpleExcelWriter::streamDownload($fileName);

        $header = [
            'No Asset', 'Perusahaan', 'Jenis Barang', 'Merek', 'Kuantitas', 'Tanggal Pengadaan',
            'Serial Number', 'Lokasi', 'Status Aset Terkini', 'Pengguna (Riwayat)', 'Status (Riwayat)',
            'Tanggal Terima', 'Tanggal Kembali', 'Keterangan (Riwayat)',
        ];
        $writer->addHeader($header);

        foreach ($barangs as $barang) {
            if ($barang->tracks->isNotEmpty()) {
                foreach ($barang->tracks as $track) {
                    $writer->addRow([
                        $barang->no_asset,
                        $barang->perusahaan->nama_perusahaan ?? 'N/A',
                        $barang->jenisBarang->nama_jenis ?? 'N/A',
                        $barang->merek,
                        $barang->kuantitas,
                        Carbon::parse($barang->tgl_pengadaan)->format('d-m-Y'),
                        $barang->serial_number,
                        $barang->lokasi ?? '-',
                        $barang->status,
                        $track->username,
                        $track->status,
                        Carbon::parse($track->tanggal_awal)->format('d-m-Y'),
                        $track->tanggal_ahir ? Carbon::parse($track->tanggal_ahir)->format('d-m-Y') : '-',
                        $track->keterangan,
                    ]);
                }
            } else {
                $writer->addRow([
                    $barang->no_asset,
                    $barang->perusahaan->nama_perusahaan ?? 'N/A',
                    $barang->jenisBarang->nama_jenis ?? 'N/A',
                    $barang->merek,
                    $barang->kuantitas,
                    Carbon::parse($barang->tgl_pengadaan)->format('d-m-Y'),
                    $barang->serial_number,
                    $barang->lokasi ?? '-',
                    $barang->status,
                    '-', '-', '-', '-', 'Belum ada riwayat serah terima',
                ]);
            }
        }

        return $writer->toBrowser();
    }
    
    private function calculateInventorySummary($baseQueryBuilder, $filterJenisBarangInput)
    {
        $allJenisBarang = JenisBarang::orderBy('nama_jenis', 'asc')->get();

        $inventorySummary = $allJenisBarang->mapWithKeys(function ($jenis) {
            return [$jenis->nama_jenis => (object)[
                'count' => 0,
                'icon' => $jenis->icon ?? 'fas fa-question-circle'
            ]];
        });

        $summaryQuery = clone $baseQueryBuilder;

        $itemCounts = $summaryQuery
            ->select('jenis_barangs.nama_jenis', DB::raw('count(barang.id) as total'))
            ->groupBy('jenis_barangs.nama_jenis')
            ->pluck('total', 'jenis_barangs.nama_jenis');

        foreach ($itemCounts as $namaJenis => $count) {
            if (isset($inventorySummary[$namaJenis])) {
                $inventorySummary[$namaJenis]->count = $count;
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

    public function searchRealtime(Request $request)
    {
        $queryBuilder = $this->getBarangQuery($request);
        $perPage = $request->input('per_page', 10);

        $sortableColumns = [
            'perusahaan'     => 'perusahaans.nama_perusahaan',
            'jenis_barang'   => 'jenis_barangs.nama_jenis',
            'no_asset'       => 'barang.no_asset',
            'merek'          => 'barang.merek',
            'tgl_pengadaan'  => 'barang.tgl_pengadaan',
            'serial_number'  => 'barang.serial_number',
            'lokasi'         => 'barang.lokasi',
        ];

        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');
        $direction = strtolower($sortDirection) === 'desc' ? 'desc' : 'asc';

        $subQuery = clone $queryBuilder;
        $subQuery->addSelect(DB::raw('ROW_NUMBER() OVER (ORDER BY barang.id ASC) as original_row_number'));

        $mainQuery = DB::query()->fromSub($subQuery, 'ranked_barang')
            ->leftJoin('barang', 'ranked_barang.id', '=', 'barang.id')
            ->leftJoin('perusahaans', 'barang.perusahaan_id', '=', 'perusahaans.id')
            ->leftJoin('jenis_barangs', 'barang.jenis_barang_id', 'jenis_barangs.id');

        if ($sortBy && $sortBy === 'no') {
            $mainQuery->orderBy('original_row_number', $direction);
        } elseif ($sortBy && array_key_exists($sortBy, $sortableColumns)) {
            $columnName = $sortableColumns[$sortBy];
            $mainQuery->orderBy($columnName, $direction);
            $mainQuery->orderBy('original_row_number', 'asc');
        } else {
            $mainQuery->orderBy('original_row_number', 'asc');
        }

        $barangsPaginator = $mainQuery->select('ranked_barang.*', 'barang.*')->paginate($perPage);
        $barangsPaginator->appends($request->except('page'));

        $summaryQueryBuilder = Barang::query()
            ->leftJoin('jenis_barangs', 'barang.jenis_barang_id', '=', 'jenis_barangs.id');

        if ($request->filled('search_no_asset')) {
            $searchKeyword = $request->input('search_no_asset');
            $summaryQueryBuilder->where(function ($query) use ($searchKeyword) {
                $query->where('barang.no_asset', 'like', '%' . $searchKeyword . '%')
                    ->orWhere('barang.serial_number', 'like', '%' . $searchKeyword . '%')
                    ->orWhere('barang.merek', 'like', '%' . $searchKeyword . '%');
            });
        } else {
            if ($request->filled('filter_perusahaan')) {
                $summaryQueryBuilder->where('barang.perusahaan_id', $request->input('filter_perusahaan'));
            }
            if ($request->filled('filter_jenis_barang') && is_array($request->input('filter_jenis_barang'))) {
                $summaryQueryBuilder->whereIn('barang.jenis_barang_id', $request->input('filter_jenis_barang'));
            }
        }

        $inventorySummaryAjax = $this->calculateInventorySummary($summaryQueryBuilder, null);

        if ($request->ajax()) {
            $transformedData = $barangsPaginator->getCollection()->map(function ($barang) {
                $barangModel = Barang::with(['perusahaan', 'jenisBarang'])->find($barang->id);
                return [
                    'id' => $barang->id,
                    'row_number' => $barang->original_row_number,
                    'perusahaan_nama' => $barangModel->perusahaan->nama_perusahaan ?? 'N/A',
                    'jenis_barang' => $barangModel->jenisBarang->nama_jenis ?? 'N/A',
                    'no_asset' => $barang->no_asset,
                    'merek' => $barang->merek,
                    'tgl_pengadaan' => $barang->tgl_pengadaan,
                    'serial_number' => $barang->serial_number,
                    'lokasi' => $barang->lokasi,
                ];
            });

            return response()->json([
                'data' => $transformedData,
                'pagination' => [
                    'current_page' => $barangsPaginator->currentPage(),
                    'first_item' => $barangsPaginator->firstItem(),
                    'last_item' => $barangsPaginator->lastItem(),
                    'total' => $barangsPaginator->total(),
                    'per_page' => $barangsPaginator->perPage(),
                    'last_page' => $barangsPaginator->lastPage(),
                ],
                'inventorySummary' => $inventorySummaryAjax,
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
                'barang' => [
                    'id' => $barang->id,
                    'perusahaan_id' => $barang->perusahaan_id,
                    'jenis_barang_id' => $barang->jenis_barang_id,
                    'no_asset' => $barang->no_asset,
                    'merek' => $barang->merek,
                    'kuantitas' => $barang->kuantitas, // REVISI: Tambahkan baris ini
                    'tgl_pengadaan' => $barang->tgl_pengadaan,
                    'serial_number' => $barang->serial_number,
                    'lokasi' => $barang->lokasi, // REVISI: Tambahkan baris ini
                    'status' => $barang->status,
                    'perusahaan' => $barang->perusahaan,
                    'jenis_barang' => $barang->jenisBarang,
                ],
                'track' => $latestTrack
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching detail barang: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data.'], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'jenis_barang_id' => 'required|exists:jenis_barangs,id',
            'merek' => 'required|string|max:255',
            'kuantitas' => 'required|integer|min:1',
            'tgl_pengadaan' => 'required|date',
            'serial_number' => 'required|string|unique:barang,serial_number|max:255',
            'lokasi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $perusahaan = Perusahaan::find($request->perusahaan_id);
            $jenisBarang = JenisBarang::find($request->jenis_barang_id);

            if (!$perusahaan || !$jenisBarang) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Perusahaan atau Jenis Barang tidak ditemukan.'], 404);
            }

            $counter = AssetCounter::lockForUpdate()
                ->firstOrCreate(
                    ['perusahaan_id' => $request->perusahaan_id],
                    ['nomor_terakhir' => 0]
                );

            $nomorTerakhir = $counter->nomor_terakhir + 1;
            $counter->nomor_terakhir = $nomorTerakhir;
            $counter->save();

            $noAsset = sprintf(
                "%s/%s/%s/%s/%s",
                $perusahaan->singkatan,
                $jenisBarang->singkatan,
                Carbon::parse($request->tgl_pengadaan)->format('Y'),
                Carbon::parse($request->tgl_pengadaan)->format('m'),
                str_pad($nomorTerakhir, 4, '0', STR_PAD_LEFT)
            );

            $barang = Barang::create([
                'perusahaan_id' => $request->perusahaan_id,
                'jenis_barang_id' => $request->jenis_barang_id,
                'no_asset' => $noAsset,
                'merek' => $request->merek,
                'kuantitas' => $request->kuantitas,
                'tgl_pengadaan' => $request->tgl_pengadaan,
                'serial_number' => $request->serial_number,
                'lokasi' => $request->lokasi,
                'status' => 'tersedia',
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Aset berhasil ditambahkan.', 'data' => $barang], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding new asset: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan aset: ' . $e->getMessage()], 500);
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

    public function editTrack(Track $track)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }
        $barang = Barang::with('perusahaan')->where('serial_number', $track->serial_number)->first();
        return response()->json(['success' => true, 'track' => $track, 'barang' => $barang]);
    }

    public function updateTrack(Request $request, Track $track)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $rules = [
            'username' => 'required|string|max:255',
            'status' => 'required|string|in:digunakan,diperbaiki,dipindah,non aktif,tersedia',
            'keterangan' => 'required|string',
            'tanggal_awal' => 'required|date',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $track->update($request->only(['username', 'status', 'keterangan', 'tanggal_awal']));
        return response()->json(['success' => true, 'message' => 'Riwayat berhasil diperbarui.']);
    }

    public function destroyTrack(Track $track)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $track->delete();
        return response()->json(['success' => true, 'message' => 'Riwayat berhasil dihapus.']);
    }

    public function edit(Barang $barang)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        return response()->json([
            'success' => true,
            'barang' => $barang
        ]);
    }

    public function update(Request $request, Barang $barang)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'jenis_barang_id' => 'required|exists:jenis_barangs,id',
            'no_asset' => 'required|string|max:255|unique:barang,no_asset,' . $barang->id,
            'merek' => 'required|string|max:255',
            'kuantitas' => 'required|integer|min:1',
            'tgl_pengadaan' => 'required|date',
            'serial_number' => 'required|string|max:255|unique:barang,serial_number,' . $barang->id,
            'lokasi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $barang->update($request->all());
            return response()->json(['success' => true, 'message' => 'Aset berhasil diperbarui.']);
        } catch (\Exception $e) {
            Log::error('Error updating asset: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui aset.'], 500);
        }
    }

    public function destroy(Barang $barang)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        DB::beginTransaction();
        try {
            Track::where('serial_number', $barang->serial_number)->delete();
            $barang->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Aset berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting asset: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus aset.'], 500);
        }
    }

    public function storeSerahTerimaAset(Request $request)
    {
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
            $barangLama = clone $barang;
            if ($request->input('perusahaan_tujuan') == $barang->perusahaan_id) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'perusahaan_tujuan' => ['Tidak bisa memindahkan aset ke perusahaan yang sama.']
                    ]
                ], 422);
            }
        }

        $newStatus = $request->input('status');

        DB::beginTransaction();
        try {
            $currentTime = Carbon::now();
            $barang = Barang::with('jenisBarang')->find($request->input('asset_id'));

            if (!$barang) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Data barang tidak ditemukan.'], 404);
            }

            if ($request->input('status') === 'dipindah') {
                $perusahaanTujuanId = $request->input('perusahaan_tujuan');
                $perusahaanTujuan = Perusahaan::find($perusahaanTujuanId);
                $counterTujuan = AssetCounter::lockForUpdate()
                    ->firstOrCreate(
                        ['perusahaan_id' => $perusahaanTujuanId],
                        ['nomor_terakhir' => 0]
                    );

                $nomorBaru = $counterTujuan->nomor_terakhir + 1;
                $counterTujuan->nomor_terakhir = $nomorBaru;
                $counterTujuan->save();
                $eeee = str_pad($nomorBaru, 4, '0', STR_PAD_LEFT);
                $aaa = $perusahaanTujuan->singkatan;
                $bbb = $barang->jenisBarang->singkatan;
                $cccc = date('Y');
                $dd = date('m');
                $noAssetBaru = "{$aaa}/{$bbb}/{$cccc}/{$dd}/{$eeee}";
                $barang->perusahaan_id = $perusahaanTujuanId;
                $barang->no_asset = $noAssetBaru;

                \App\Models\Mutasi::create([
                    'barang_id'           => $barangLama->id,
                    'no_asset_lama'       => $barangLama->no_asset,
                    'perusahaan_lama_id'  => $barangLama->perusahaan_id,
                    'pengguna_lama'       => $previousTrack->username ?? 'N/A',
                    'no_asset_baru'       => $noAssetBaru,
                    'perusahaan_baru_id'  => $perusahaanTujuanId,
                    'pengguna_baru'       => $request->input('username'),
                    'tanggal_mutasi'      => $currentTime->toDateString(),
                ]);
            }

            $barang->status = $request->input('status');
            $barang->save();
            $previousTrack = Track::where('serial_number', $barang->serial_number)
                ->whereNull('tanggal_ahir')
                ->orderBy('tanggal_awal', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            if ($previousTrack) {
                $tanggalAwalPrevious = Carbon::parse($previousTrack->tanggal_awal);
                if ($currentTime->lessThan($tanggalAwalPrevious)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'keterangan' => ['Tanggal serah terima tidak bisa lebih awal dari tanggal penerimaan sebelumnya.']
                        ]
                    ], 422);
                }

                $previousTrack->tanggal_ahir = $currentTime->toDateString();
                $previousTrack->save();
            }

            $newTrack = Track::create([
                'serial_number' => $barang->serial_number,
                'username'      => $request->input('username'),
                'status'        => $request->input('status'),
                'keterangan'    => $request->input('keterangan'),
                'tanggal_awal'  => $currentTime->toDateString(),
                'tanggal_ahir'  => null,
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