<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Penting untuk validasi

class BarangController extends Controller
{
    // ... (method index, create, show, edit, update, destroy lainnya) ...

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perusahaan' => 'required|string|in:SCO,SCT,SCP,Migen',
            'jenis_barang' => 'required|string|in:Laptop,HP,PC/AIO,Printer,Proyektor',
            'no_asset' => 'required|string|max:255|unique:barang,no_asset',
            'merek' => 'required|string|max:255', // Jika 'merek' menggantikan 'no_barang'
            // 'no_barang' => 'required|string|max:255', // Jika Anda masih menggunakan 'no_barang'
            'tgl_pengadaan' => 'required|date',
            'serial_number' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Kirim error validasi
        }

        try {
            // Sesuaikan field jika Anda menggunakan 'no_barang' bukan 'merek'
            Barang::create([
                'perusahaan' => $request->perusahaan,
                'jenis_barang' => $request->jenis_barang,
                'no_asset' => $request->no_asset,
                'no_barang' => $request->merek, // Asumsi 'merek' disimpan ke field 'no_barang'
                                               // atau jika field di DB adalah 'merek', gunakan 'merek' => $request->merek
                'tgl_pengadaan' => $request->tgl_pengadaan,
                'serial_number' => $request->serial_number,
            ]);

            return response()->json(['success' => 'Aset berhasil ditambahkan!'], 201);
        } catch (\Exception $e) {
            // Log error jika perlu: Log::error($e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan aset. Silakan coba lagi.'], 500);
        }
    }
}