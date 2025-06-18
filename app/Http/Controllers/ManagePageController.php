<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ManagePageController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('search')) {
            $searchKeyword = $request->input('search');
            $query->where(function ($subQuery) use ($searchKeyword) {
                $subQuery->where('name', 'like', '%' . $searchKeyword . '%')
                    ->orWhere('email', 'like', '%' . $searchKeyword . '%');
            });
        }

        $perPage = $request->input('per_page', 10);

        $query = User::query();
        if ($request->filled('search')) {
            $searchKeyword = $request->input('search');
            $query->where(function ($subQuery) use ($searchKeyword) {
                $subQuery->where('name', 'like', '%' . $searchKeyword . '%')
                    ->orWhere('email', 'like', '%' . $searchKeyword . '%');
            });
        }

        // Gunakan variabel $perPage untuk paginasi
        $users = $query->latest()->paginate($perPage)->appends($request->except('page'));

        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('partials.user_table_rows', compact('users'))->render(),
                // INI PERUBAHAN UTAMA: Kirim seluruh objek pagination
                'pagination' => $users->toArray()
            ]);
        }

        return view('UserManagePage', [
            'users' => $users,
            'searchKeyword' => $request->input('search', '')
        ]);
    }

    private function getValidationMessages(): array
    {
        return [
            'name.required' => 'Username wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.letters' => 'Password harus mengandung setidaknya satu huruf.',
            'password.numbers' => 'Password harus mengandung setidaknya satu angka.',
        ];
    }

    private function getValidationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9 ]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::min(6)->letters()->numbers()],
        ];
    }

    public function store(Request $request)
    {
        // Panggil helper method untuk mendapatkan aturan dan pesan
        $rules = $this->getValidationRules();
        $messages = $this->getValidationMessages();
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);
        return response()->json(['success' => 'User berhasil ditambahkan.']);
    }

    public function validateField(Request $request)
    {
        $triggerField = $request->input('field_trigger');

        // Panggil helper method untuk mendapatkan aturan dan pesan
        $allRules = $this->getValidationRules();
        $messages = $this->getValidationMessages();

        // ... sisa logika validateField() sama seperti sebelumnya ...
        $rulesToValidate = [];
        if (array_key_exists($triggerField, $allRules)) {
            $rulesToValidate[$triggerField] = $allRules[$triggerField];
        }

        if ($triggerField === 'password' || $triggerField === 'password_confirmation') {
            $rulesToValidate['password'] = $allRules['password'];
        }

        $validator = Validator::make($request->all(), $rulesToValidate, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['success' => true]);
    }

    public function destroy(User $user)
    {
        if (strtolower($user->name) === 'admin' || strtolower($user->role) === 'admin') {
            return response()->json(['error' => 'User Admin tidak dapat dihapus.'], 403); // 403 = Forbidden
        }
        $user->delete();
        return response()->json(['success' => 'User berhasil dihapus.']);
    }

    public function edit(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        if (strtolower($user->role) === 'admin' && $request->has('role') && $request->role !== $user->role) {
            return response()->json(['error' => 'Role Admin tidak dapat diubah.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'confirmed', Password::min(6)->letters()->numbers()],
        ], $this->getValidationMessages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update data
        $user->name = $request->name;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['success' => 'User berhasil diperbarui.']);
    }
}
