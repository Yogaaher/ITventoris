<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Perusahaan;
use App\Models\JenisBarang;
use App\Models\AssetCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Validator; // Penting untuk validasi

class BarangController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'jenis_barang_id' => 'required|exists:jenis_barangs,id',
            'merek' => 'required|string|max:255',
            'tgl_pengadaan' => 'required|date',
            'serial_number' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Kirim error validasi
        }

        try {
            $newAssetNumber = DB::transaction(function () use ($request) {
                $perusahaan = Perusahaan::find($request->perusahaan_id);
                $jenisBarang = JenisBarang::find($request->jenis_barang_id);

                                $counter = AssetCounter::lockForUpdate()
                    ->firstOrCreate(
                        ['perusahaan_id' => $request->perusahaan_id],
                        ['nomor_terakhir' => 0]
                    );

                // 2. Increment nomor
                $nomorBaru = $counter->nomor_terakhir + 1;

                // 3. Simpan nomor baru kembali ke database
                $counter->nomor_terakhir = $nomorBaru;
                $counter->save();

                // 4. Format nomor (padding)
                $eeee = str_pad($nomorBaru, 4, '0', STR_PAD_LEFT);

                $aaa = $perusahaan->singkatan;
                $bbb = $jenisBarang->singkatan;
                $cccc = date('Y', strtotime($request->tgl_pengadaan));
                $dd = date('m', strtotime($request->tgl_pengadaan));

                $noAssetFinal = "{$aaa}/{$bbb}/{$cccc}/{$dd}/{$eeee}";

                Barang::create([
                    'perusahaan_id' => $request->perusahaan_id,
                    'jenis_barang_id' => $request->jenis_barang_id,
                    'no_asset' => $noAssetFinal, // Simpan no asset yang baru dibuat
                    'merek' => $request->merek,
                    'tgl_pengadaan' => $request->tgl_pengadaan,
                    'serial_number' => $request->serial_number,
                ]);

                return $noAssetFinal;
            });

            return response()->json(['success' => "Aset berhasil ditambahkan dengan No: {$newAssetNumber}!"], 201);
        } catch (\Exception $e) {
            \Log::error('Gagal membuat aset: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan aset. Terjadi kesalahan pada server.'], 500);
        }
    }

    public function getNomorSeriBerikutnya($perusahaan_id)
    {
        if (!is_numeric($perusahaan_id)) {
            return response()->json(['error' => 'ID Perusahaan tidak valid'], 400);
        }

        try {
            $counter = AssetCounter::where('perusahaan_id', $perusahaan_id)->first();
            $nomorTerakhir = $counter ? $counter->nomor_terakhir : 0;
            $nomorSeriBerikutnya = $nomorTerakhir + 1;
            $nomorSeriFormatted = str_pad($nomorSeriBerikutnya, 4, '0', STR_PAD_LEFT);
            return response()->json([
                'success' => true,
                'nomor_seri' => $nomorSeriFormatted
            ]);

        } catch (\Exception $e) {
            Log::error("Gagal mengambil nomor seri: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data nomor seri.'], 500);
        }
    }
}