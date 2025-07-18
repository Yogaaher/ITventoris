<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MutasiController extends Controller
{
    public function getData(Request $request)
    {
        $query = Mutasi::with(['barang', 'perusahaanLama', 'perusahaanBaru']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('barang', function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                    ->orWhere('merek', 'like', "%{$search}%")
                    ->orWhere('no_asset', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $mutasiData = $query->latest('tanggal_mutasi')->paginate($perPage);

        return response()->json($mutasiData);
    }

    public function show(Mutasi $mutasi)
    {
        $mutasi->load(['barang.jenisBarang', 'perusahaanLama', 'perusahaanBaru']);
        return response()->json($mutasi);
    }

    public function destroy(Mutasi $mutasi)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $mutasi->delete();

        return response()->json(['success' => 'Data mutasi berhasil dihapus.']);
    }
}