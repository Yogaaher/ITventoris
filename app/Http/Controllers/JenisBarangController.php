<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JenisBarangController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->route('companies.index');
        }

        $query = JenisBarang::query();

        if ($request->filled('search')) {
            $searchKeyword = $request->input('search');
            $query->where(function ($subQuery) use ($searchKeyword) {
                $subQuery->where('nama_jenis', 'like', '%' . $searchKeyword . '%')
                    ->orWhere('singkatan', 'like', '%' . $searchKeyword . '%');
            });
        }

        $perPage = $request->input('per_page', 20);
        $itemTypes = $query->oldest('nama_jenis')->paginate($perPage)->appends($request->except('page'));

        return response()->json([
            'table_html' => view('partials.item_type_table_rows', ['itemTypes' => $itemTypes])->render(),
            'pagination' => $itemTypes->toArray()
        ]);
    }

    private function getValidationRules($itemTypeId = null)
    {
        return [
            'nama_jenis' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9\/ \(\)\.-]+$/',
                Rule::unique('jenis_barangs')->ignore($itemTypeId)
            ],
            'singkatan' => [
                'required',
                'string',
                'max:10',
                'alpha_num',
                Rule::unique('jenis_barangs')->ignore($itemTypeId)
            ],
            'icon' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9\- ]+$/'
            ],
        ];
    }

    private function getValidationMessages()
    {
        return [
            'nama_jenis.required' => 'Nama jenis barang wajib diisi.',
            'nama_jenis.unique'   => 'Nama jenis barang ini sudah ada.',
            'nama_jenis.regex'    => 'Nama jenis hanya boleh berisi huruf, angka, spasi, dan simbol dasar.',
            'singkatan.required'  => 'Singkatan wajib diisi.',
            'singkatan.max'       => 'Singkatan maksimal 10 karakter.',
            'singkatan.uppercase' => 'Singkatan harus menggunakan huruf besar.',
            'singkatan.unique'    => 'Singkatan ini sudah digunakan.',
            'singkatan.alpha_num' => 'Singkatan hanya boleh berisi huruf dan angka.',
            'icon.regex'          => 'Format ikon tidak valid. Contoh: fa-laptop.',
        ];
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }
        $validator = Validator::make($request->all(), $this->getValidationRules(), $this->getValidationMessages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();
        $validatedData['nama_jenis'] = strtoupper($validatedData['nama_jenis']);
        $validatedData['singkatan'] = strtoupper($validatedData['singkatan']); // <-- TAMBAH BARIS INI

        JenisBarang::create($validatedData);

        return response()->json(['success' => 'Jenis Barang berhasil ditambahkan.']);
    }

    public function update(Request $request, JenisBarang $item_type)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $validator = Validator::make($request->all(), $this->getValidationRules($item_type->id), $this->getValidationMessages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();
        $validatedData['nama_jenis'] = strtoupper($validatedData['nama_jenis']);
        $validatedData['singkatan'] = strtoupper($validatedData['singkatan']); // <-- TAMBAH BARIS INI

        $item_type->update($validatedData);

        return response()->json(['success' => 'Jenis Barang berhasil diperbarui.']);
    }

    public function edit(JenisBarang $item_type)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }
        return response()->json($item_type);
    }

    public function destroy(JenisBarang $item_type)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $item_type->delete();

        return response()->json(['success' => 'Jenis Barang berhasil dihapus.']);
    }
}
