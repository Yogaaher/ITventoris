<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ManagePageController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        $currentUser = Auth::user();

        if ($currentUser->isAdmin()) {
            $query->where('role', '!=', 'super_admin');
        }

        if ($request->filled('search')) {
            $searchKeyword = $request->input('search');
            $query->where(function ($subQuery) use ($searchKeyword) {
                $subQuery->where('name', 'like', '%' . $searchKeyword . '%')
                    ->orWhere('email', 'like', '%' . $searchKeyword . '%');
            });
        }

        $perPage = $request->input('per_page', 20);
        $users = $query->latest()->paginate($perPage)->appends($request->except('page'));

        if ($request->ajax()) {
            $tableHtml = view('partials.user_table_rows', compact('users', 'currentUser'))->render();
            return response()->json([
                'table_html' => $tableHtml,
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
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ];
    }

    private function getValidationRules(bool $isUpdate = false, ?int $userId = null): array
    {
        $allowedRoles = Auth::user()->isSuperAdmin()
            ? ['super_admin', 'admin', 'user']
            : ['user'];

        $rules = [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9 ]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->when($isUpdate, fn($rule) => $rule->ignore($userId))],
            'password' => ['sometimes', 'required', 'string', 'confirmed', Password::min(6)->letters()->numbers()],
            'role' => ['required', Rule::in($allowedRoles)],
        ];

        if ($isUpdate) {
            $rules['password'] = ['nullable', 'string', 'confirmed', Password::min(6)->letters()->numbers()];
        }

        return $rules;
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAdmin() && $request->role !== 'user') {
            return response()->json(['error' => 'Admin hanya bisa membuat akun dengan role User.'], 403);
        }

        $validator = Validator::make($request->all(), $this->getValidationRules(), $this->getValidationMessages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json(['success' => 'User berhasil ditambahkan.']);
    }

    public function validateField(Request $request)
    {
        $triggerField = $request->input('field_trigger');
        $allRules = $this->getValidationRules();
        $messages = $this->getValidationMessages();
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
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return response()->json(['error' => 'Anda tidak bisa menghapus akun Anda sendiri.'], 403);
        }

        if ($currentUser->isAdmin() && !$user->isUser()) {
            return response()->json(['error' => 'Anda tidak memiliki izin untuk menghapus user ini.'], 403);
        }

        $user->delete();
        return response()->json(['success' => 'User berhasil dihapus.']);
    }

    public function edit(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->isAdmin() && !$user->isUser()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->isAdmin() && $user->role !== 'user') {
            return response()->json(['error' => 'Admin hanya dapat mengedit user dengan role User.'], 403);
        }

        if ($currentUser->isSuperAdmin() && $user->id === $currentUser->id && $request->role !== 'super_admin') {
            return response()->json(['error' => 'Anda tidak dapat mengubah role akun Anda sendiri.'], 403);
        }

        $allowedRoles = [];
        if ($currentUser->isSuperAdmin()) {
            $allowedRoles = ['super_admin', 'admin', 'user'];
        } elseif ($currentUser->isAdmin()) {
            $allowedRoles = ['user'];
        }

        $rules = [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9 ]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'confirmed', Password::min(6)->letters()->numbers()],
            'role' => ['required', Rule::in($allowedRoles)],
        ];

        $validator = Validator::make($request->all(), $rules, $this->getValidationMessages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->name = $request->name;

        if ($currentUser->isSuperAdmin()) {
            $user->email = $request->email;
            $user->role = $request->role;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return response()->json(['success' => 'User berhasil diperbarui.']);
    }
}
