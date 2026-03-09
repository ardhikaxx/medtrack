<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'unit']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('username', 'like', "%{$request->search}%");
            });
        }

        if ($request->role_id) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->is_active !== null) {
            $query->where('is_active', $request->is_active);
        }

        $users = $query->latest()->paginate(20);
        $roles = Role::where('is_active', true)->get();

        return view('pengguna.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();

        return view('pengguna.create', compact('roles', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'unit_id' => 'nullable|exists:units,id',
            'jabatan' => 'nullable|string|max:100',
            'spesialisasi' => 'nullable|string|max:100',
            'str_number' => 'nullable|string|max:50',
            'institusi_asal' => 'nullable|string|max:200',
            'jenis_pengguna' => 'required|in:internal,eksternal',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $validated['is_active'] ?? true;

        $user = User::create($validated);

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'create',
            'modul' => 'pengguna',
            'model_id' => $user->id,
            'data_baru' => $user->toArray(),
            'keterangan' => "Menambah pengguna {$user->nama_lengkap}",
            'created_at' => now(),
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load(['role', 'unit', 'peminjamans', 'auditLogs']);

        return view('pengguna.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();

        return view('pengguna.edit', compact('user', 'roles', 'units'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'unit_id' => 'nullable|exists:units,id',
            'jabatan' => 'nullable|string|max:100',
            'spesialisasi' => 'nullable|string|max:100',
            'str_number' => 'nullable|string|max:50',
            'institusi_asal' => 'nullable|string|max:200',
            'jenis_pengguna' => 'required|in:internal,eksternal',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'update',
            'modul' => 'pengguna',
            'model_id' => $user->id,
            'keterangan' => "Mengubah pengguna {$user->nama_lengkap}",
            'created_at' => now(),
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'delete',
            'modul' => 'pengguna',
            'model_id' => $user->id,
            'keterangan' => "Menghapus pengguna {$user->nama_lengkap}",
            'created_at' => now(),
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleAktif(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', 'Status pengguna berhasil diubah.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $user->update(['password' => Hash::make('password123')]);

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'reset_password',
            'modul' => 'pengguna',
            'model_id' => $user->id,
            'keterangan' => "Mereset password pengguna {$user->nama_lengkap}",
            'created_at' => now(),
        ]);

        return back()->with('success', 'Password direset ke "password123".');
    }
}
