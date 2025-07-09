<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PerusahaanPageController extends Controller
{
    public function index(Request $request)
    {
        $query = Perusahaan::query();

        if ($request->filled('search')) {
            $searchKeyword = $request->input('search');
            $query->where(function ($subQuery) use ($searchKeyword) {
                $subQuery->where('nama_perusahaan', 'like', '%' . $searchKeyword . '%')
                    ->orWhere('singkatan', 'like', '%' . $searchKeyword . '%');
            });
        }

        $perPage = $request->input('per_page', 20);
        $companies = $query->oldest()->paginate($perPage)->appends($request->except('page'));

        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('partials.company_table_rows', compact('companies'))->render(),
                'pagination' => $companies->toArray()
            ]);
        }

        return view('PerusahaanPage', [
            'companies' => $companies,
            'searchKeyword' => $request->input('search', '')
        ]);
    }

    private function getValidationRules($companyId = null)
    {
        return [
            'nama_perusahaan' => [
                'required', 'string', 'max:255', 'uppercase', 'regex:/^[A-Z0-9 .]+$/',
                Rule::unique('perusahaans')->ignore($companyId)
            ],
            'singkatan' => [
                'required', 'string', 'max:3', 'uppercase', 'alpha_num',
                Rule::unique('perusahaans')->ignore($companyId)
            ],
        ];
    }

    private function getValidationMessages()
    {
        return [
            'nama_perusahaan.required'  => 'Nama perusahaan wajib diisi.',
            'nama_perusahaan.unique'    => 'Nama perusahaan ini sudah ada.',
            'nama_perusahaan.uppercase' => 'Nama perusahaan harus menggunakan huruf besar.',
            'nama_perusahaan.regex'     => 'Nama perusahaan hanya boleh berisi huruf besar, angka, spasi, dan titik.',
            'singkatan.required'  => 'Singkatan wajib diisi.',
            'singkatan.max'       => 'Singkatan maksimal 3 karakter.',
            'singkatan.uppercase' => 'Singkatan harus menggunakan huruf besar.',
            'singkatan.unique'    => 'Singkatan ini sudah digunakan.',
            'singkatan.alpha_num' => 'Singkatan hanya boleh berisi huruf dan angka.',
        ];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getValidationRules(), $this->getValidationMessages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Perusahaan::create($validator->validated());

        return response()->json(['success' => 'Perusahaan berhasil ditambahkan.']);
    }
    
    public function edit(Perusahaan $company)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }
        return response()->json($company);
    }

    public function update(Request $request, Perusahaan $company)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }
        
        $validator = Validator::make($request->all(), $this->getValidationRules($company->id), $this->getValidationMessages());
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $company->update($validator->validated());
        
        return response()->json(['success' => 'Perusahaan berhasil diperbarui.']);
    }

    public function destroy(Perusahaan $company)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }
        
        $company->delete();
        
        return response()->json(['success' => 'Perusahaan berhasil dihapus.']);
    }
}