<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PerusahaanPageController extends Controller
{
    /**
     * Menampilkan halaman manajemen perusahaan dan menangani request AJAX.
     */
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


    public function store(Request $request)
    {
        $rules = [
            'nama_perusahaan' => [
                'required',
                'string',
                'max:255',
                'uppercase',
                'regex:/^[A-Z0-9 .]+$/',
                'unique:perusahaans,nama_perusahaan'
            ],
            'singkatan' => [
                'required',
                'string',
                'max:3',
                'uppercase',
                'alpha_num',
                'unique:perusahaans,singkatan'
            ],
        ];

        // Pesan error kustom
        $messages = [
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

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        $validatedData['nama_perusahaan'] = strtoupper($validatedData['nama_perusahaan']);
        $validatedData['singkatan'] = strtoupper($validatedData['singkatan']);

        Perusahaan::create($validatedData);

        return response()->json(['success' => 'Perusahaan berhasil ditambahkan.']);
    }
}
