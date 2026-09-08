<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagerController extends Controller
{
    /**
     * Daftar semua user non-pelapor (admin & operator).
     */
    public function index()
    {
        $users = User::where('role', '!=', 'pelapor')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Form buat akun baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan akun operator / admin baru.
     * Hanya admin_it yang bisa akses route ini (dijaga middleware).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'role'        => ['required', 'in:operator,admin_it'],
            'scope_level' => ['nullable', 'in:fakultas,prodi'],
            'scope_id'    => ['nullable', 'integer'],
        ]);

        // Scope hanya berlaku untuk operator
        $scopeLevel = $request->role === 'operator' ? $request->scope_level : null;
        $scopeId    = $request->role === 'operator' ? $request->scope_id    : null;

        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'scope_level' => $scopeLevel,
            'scope_id'    => $scopeId,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun berhasil dibuat.');
    }

    /**
     * Form edit akun.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data akun.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'role'        => ['required', 'in:operator,admin_it'],
            'scope_level' => ['nullable', 'in:fakultas,prodi'],
            'scope_id'    => ['nullable', 'integer'],
        ]);

        $scopeLevel = $request->role === 'operator' ? $request->scope_level : null;
        $scopeId    = $request->role === 'operator' ? $request->scope_id    : null;

        $user->update([
            'name'        => $request->name,
            'role'        => $request->role,
            'scope_level' => $scopeLevel,
            'scope_id'    => $scopeId,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Hapus akun.
     */
    public function destroy(User $user)
    {
        // Jangan hapus diri sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun berhasil dihapus.');
    }
}
