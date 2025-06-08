<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Penting untuk validasi

class BarangController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perusahaan' => 'required|string|in:SCO,SCT,SCP,Migen',
            'jenis_barang' => 'required|string|in:Laptop,HP,PC/AIO,Printer,Proyektor,Others',
            'no_asset' => 'required|string|max:255|unique:barang,no_asset',
            'merek' => 'required|string|max:255',
            'tgl_pengadaan' => 'required|date',
            'serial_number' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Kirim error validasi
        }

        try {
            Barang::create([
                'perusahaan' => $request->perusahaan,
                'jenis_barang' => $request->jenis_barang,
                'no_asset' => $request->no_asset,
                'merek' => $request->merek,
                'tgl_pengadaan' => $request->tgl_pengadaan,
                'serial_number' => $request->serial_number,
            ]);

            return response()->json(['success' => 'Aset berhasil ditambahkan!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan aset. Silakan coba lagi.'], 500);
        }
    }
}