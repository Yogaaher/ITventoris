<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Perusahaan;
use App\Models\JenisBarang;
use App\Models\AssetCounter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'jenis_barang_id' => 'required|exists:jenis_barangs,id',
            'merek' => 'required|string|max:255',
            'kuantitas' => 'required|integer|min:1',
            'tgl_pengadaan' => 'required|date',
            'serial_number' => [
                'required',
                'string',
                'max:255',
                'unique:barang,serial_number',
                'regex:/^[a-zA-Z0-9\-\/ ]+$/'
            ],
            'lokasi' => 'nullable|string|max:255', 
        ], [
            'serial_number.unique' => 'Serial number ini sudah terdaftar di sistem.',
            'serial_number.regex' => 'Serial number hanya boleh berisi huruf, angka, spasi, strip (-), dan garis miring (/).'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        $sanitizedMerek = strip_tags($validatedData['merek']);
        $sanitizedSerialNumber = strip_tags($validatedData['serial_number']);
        if (empty($sanitizedSerialNumber)) {
            return response()->json([
                'errors' => [
                    'serial_number' => ['Serial number tidak boleh hanya berisi karakter yang tidak valid.']
                ]
            ], 422);
        }

        try {
            $newAssetNumber = DB::transaction(function () use ($validatedData, $sanitizedMerek, $sanitizedSerialNumber) {
                $perusahaan = Perusahaan::find($validatedData['perusahaan_id']);
                $jenisBarang = JenisBarang::find($validatedData['jenis_barang_id']);

                $counter = AssetCounter::lockForUpdate()
                    ->firstOrCreate(
                        ['perusahaan_id' => $validatedData['perusahaan_id']],
                        ['nomor_terakhir' => 0]
                    );

                $nomorBaru = $counter->nomor_terakhir + 1;
                $counter->nomor_terakhir = $nomorBaru;
                $counter->save();
                $eeee = str_pad($nomorBaru, 4, '0', STR_PAD_LEFT);
                $aaa = $perusahaan->singkatan;
                $bbb = $jenisBarang->singkatan;
                $cccc = date('Y', strtotime($validatedData['tgl_pengadaan']));
                $dd = date('m', strtotime($validatedData['tgl_pengadaan']));
                $noAssetFinal = "{$aaa}/{$bbb}/{$cccc}/{$dd}/{$eeee}";

                Barang::create([
                    'perusahaan_id' => $validatedData['perusahaan_id'],
                    'jenis_barang_id' => $validatedData['jenis_barang_id'],
                    'no_asset' => $noAssetFinal,
                    'merek' => $sanitizedMerek,
                    'kuantitas' => $validatedData['kuantitas'], 
                    'tgl_pengadaan' => $validatedData['tgl_pengadaan'],
                    'serial_number' => $sanitizedSerialNumber,
                    'lokasi' => $validatedData['lokasi'],
                ]); 

                return $noAssetFinal;
            });
            return response()->json(['success' => "Aset berhasil ditambahkan dengan No: {$newAssetNumber}!"], 201);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan aset: ' . $e->getMessage());
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
