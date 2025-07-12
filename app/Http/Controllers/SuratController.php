<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Barang;
use App\Models\Sequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SuratController extends Controller
{
    private function getSuratQuery(Request $request)
    {
        $query = Surat::with('barang', 'barang.jenisBarang');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                    ->orWhere('nama_penerima', 'like', "%{$search}%")
                    ->orWhere('nama_pemberi', 'like', "%{$search}%")
                    ->orWhereHas('barang', function ($q_barang) use ($search) {
                        $q_barang->where('no_asset', 'like', "%{$search}%")
                            ->orWhere('serial_number', 'like', "%{$search}%")
                            ->orWhere('merek', 'like', "%{$search}%");
                    });
            });
        }
        return $query;
    }

    public function index(Request $request)
    {
        // Data ini hanya untuk load awal, selanjutnya akan dihandle AJAX
         $surats = \App\Models\Surat::latest()->paginate(10);

        // Kita akan passing $surats kosong saja ke view, karena JS akan fetch data
        return view('SerahTerimaPage', [
            'surats' => [],
            'searchKeyword' => $request->input('search', ''),
        ]);
    }

    public function searchRealtime(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $queryBuilder = Surat::query()
            ->leftJoin('barang', 'surats.barang_id', '=', 'barang.id')
            ->select('surats.*', 'barang.merek', 'barang.no_asset', 'barang.serial_number');

        if ($search) {
            $queryBuilder->where(function ($q) use ($search) {
                $q->where('surats.no_surat', 'like', "%{$search}%")
                    ->orWhere('surats.nama_penerima', 'like', "%{$search}%")
                    ->orWhere('surats.nama_pemberi', 'like', "%{$search}%")
                    ->orWhere('barang.no_asset', 'like', "%{$search}%")
                    ->orWhere('barang.serial_number', 'like', "%{$search}%")
                    ->orWhere('barang.merek', 'like', "%{$search}%");
            });
        }

        $subQuery = clone $queryBuilder;
        $subQuery->addSelect(DB::raw('ROW_NUMBER() OVER (ORDER BY surats.id DESC) as original_row_number'));

        $mainQuery = DB::query()->fromSub($subQuery, 'ranked_surats');

        $sortableColumns = [
            'no_surat'        => 'ranked_surats.no_surat',
            'nama_penerima'   => 'ranked_surats.nama_penerima',
            'nama_pemberi'    => 'ranked_surats.nama_pemberi',
            'merek'           => 'ranked_surats.merek',
            'no_asset'        => 'ranked_surats.no_asset',
            'serial_number'   => 'ranked_surats.serial_number',
        ];

        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');
        $direction = strtolower($sortDirection) === 'desc' ? 'desc' : 'asc';

        if ($sortBy === 'no') {
            $mainQuery->orderBy('original_row_number', $direction);
        } elseif (array_key_exists($sortBy, $sortableColumns)) {
            $mainQuery->orderBy($sortableColumns[$sortBy], $direction)
                ->orderBy('original_row_number', 'asc');
        } else {
            $mainQuery->orderBy('original_row_number', 'asc');
        }

        $suratsPaginator = $mainQuery->select('ranked_surats.*')->paginate($perPage);

        $suratIds = $suratsPaginator->pluck('id')->toArray();
        $suratsWithRelations = Surat::whereIn('id', $suratIds)
            ->with(['barang.jenisBarang'])
            ->get()
            ->keyBy('id');

        $transformedData = $suratsPaginator->getCollection()->map(function ($item) use ($suratsWithRelations) {
            if (isset($suratsWithRelations[$item->id])) {
                $suratModel = $suratsWithRelations[$item->id];
                $item->barang = (object)[
                    'merek' => $item->merek,
                    'no_asset' => $item->no_asset,
                    'serial_number' => $item->serial_number,
                    'jenis_barang' => $suratModel->barang->jenisBarang ?? null,
                ];
            }
            return $item;
        });

        $finalPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $transformedData,
            $suratsPaginator->total(),
            $suratsPaginator->perPage(),
            $suratsPaginator->currentPage(),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return response()->json($finalPaginator);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Anda tidak memiliki wewenang untuk melakukan aksi ini.'], 403);
        }

        $messages = [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute yang dipilih tidak valid atau tidak ditemukan.',
            'barang_id.required' => 'Aset harus dipilih dari pencarian.',
        ];

        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barang,id',
            'nama_penerima' => 'required|string|max:255',
            'nik_penerima' => 'required|string|max:255',
            'jabatan_penerima' => 'required|string|max:255',
            'nama_pemberi' => 'required|string|max:255',
            'nik_pemberi' => 'required|string|max:255',
            'jabatan_pemberi' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $newNomorSurat = DB::transaction(function () {
                $sequenceKey = 'surat_nomor_' . now()->format('Y_m');
                $sequence = Sequence::lockForUpdate()->firstOrCreate(
                    ['key' => $sequenceKey],
                    ['value' => 0]
                );
                $nextNumber = $sequence->value + 1;
                $nomorSurat = 'ST/IT/' . now()->format('Y/m/') . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $sequence->value = $nextNumber;
                $sequence->save();
                return $nomorSurat;
            });

            $dataToCreate = $validator->validated();
            $dataToCreate['no_surat'] = $newNomorSurat;
            $surat = Surat::create($dataToCreate);

            return response()->json(['success' => 'Surat berhasil dibuat!', 'surat' => $surat], 201);
        } catch (\Exception $e) {
            Log::error("Gagal membuat surat: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan pada server.'], 500);
        }
    }

    public function findBarang(Request $request)
    {
        $query = $request->input('query');
        if (!$query) {
            return response()->json(null);
        }

        $barang = Barang::where('serial_number', $query)
            ->orWhere('no_asset', $query)
            ->first();

        if ($barang) {
            $data = [
                'id' => $barang->id,
                'merek' => $barang->merek,
                'no_asset' => $barang->no_asset,
                'serial_number' => $barang->serial_number
            ];
            return response()->json($data);
        }

        return response()->json(null);
    }

    public function show($id)
    {
        $surat = Surat::with('barang', 'barang.jenisBarang', 'barang.perusahaan')->find($id);
        if (!$surat) {
            return response()->json(['error' => 'Surat tidak ditemukan'], 404);
        }
        return response()->json($surat);
    }

    public function destroy($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return response()->json(['error' => 'Anda tidak memiliki wewenang untuk melakukan aksi ini.'], 403);
        }

        $surat = Surat::find($id);
        if (!$surat) {
            return response()->json(['error' => 'Surat tidak ditemukan'], 404);
        }
        $surat->delete();
        return response()->json(['success' => 'Surat berhasil dihapus.']);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return response()->json(['error' => 'Anda tidak memiliki wewenang untuk melakukan aksi ini.'], 403);
        }

        $surat = Surat::find($id);
        if (!$surat) {
            return response()->json(['error' => 'Surat tidak ditemukan'], 404);
        }

        $messages = [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute yang dipilih tidak valid atau tidak ditemukan.',
            'barang_id.required' => 'Aset harus dipilih dari pencarian.',
        ];

        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barang,id',
            'nama_penerima' => 'required|string|max:255',
            'nik_penerima' => 'required|string|max:255',
            'jabatan_penerima' => 'required|string|max:255',
            'nama_pemberi' => 'required|string|max:255',
            'nik_pemberi' => 'required|string|max:255',
            'jabatan_pemberi' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $surat->update($validator->validated());
            return response()->json(['success' => 'Surat berhasil diperbarui!', 'surat' => $surat]);
        } catch (\Exception $e) {
            Log::error("Gagal memperbarui surat: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan pada server.'], 500);
        }
    }

    public function getProspectiveNomor()
    {
        $sequenceKey = 'surat_nomor_' . now()->format('Y_m');

        $sequence = Sequence::firstOrCreate(
            ['key' => $sequenceKey],
            ['value' => 0]
        );

        $nextNumber = $sequence->value + 1;
        $nomorSurat = 'ST/IT/' . now()->format('Y/m/') . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return response()->json(['nomor_surat' => $nomorSurat]);
    }

    public function downloadPdf($id)
    {
        if (Auth::user()->isUser()) {
            abort(403, 'Anda tidak memiliki wewenang untuk melakukan aksi ini.');
        }

        $surat = Surat::with(['barang', 'barang.perusahaan'])->findOrFail($id);

        $data = [
            'nomor_surat'               => $surat->no_surat,
            'tanggal_serah_terima'      => Carbon::parse($surat->created_at)->isoFormat('D MMMM YYYY'),
            'penerima_nama'             => $surat->nama_penerima,
            'penerima_nik'              => $surat->nik_penerima,
            'penerima_jabatan'          => $surat->jabatan_penerima,
            'penyerah_nama'             => $surat->nama_pemberi,
            'penyerah_nik'              => $surat->nik_pemberi,
            'penyerah_jabatan'          => $surat->jabatan_pemberi,
            'barang_perusahaan'         => $surat->barang->perusahaan->nama_perusahaan ?? 'N/A',
            'barang_nomor_asset'        => $surat->barang->no_asset ?? 'N/A',
            'barang_merek'              => $surat->barang->merek ?? 'N/A',
            'barang_serial_number'      => $surat->barang->serial_number ?? 'N/A',
            'barang_tanggal_pengadaan'  => Carbon::parse($surat->barang->tgl_pengadaan)->isoFormat('D MMMM YYYY'),
            'barang_penanggung_jawab'   => $surat->penanggung_jawab,
            'barang_keterangan'         => $surat->keterangan ?? 'Tidak ada keterangan tambahan.',
        ];

        $pdf = PDF::loadView('serah_terima', $data);

        $fileName = 'Serah Terima ' . str_replace('/', '-', $surat->no_surat) . '.pdf';

        return $pdf->download($fileName);
    }
}
