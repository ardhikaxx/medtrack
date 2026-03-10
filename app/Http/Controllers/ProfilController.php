<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nik' => 'nullable|string|max:16|unique:users,nik,' . $user->id,
            'nip' => 'nullable|string|max:30|unique:users,nip,' . $user->id,
            'no_telp' => 'nullable|string|max:15',
            'jabatan' => 'nullable|string|max:100',
        ]);

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'nik' => $request->nik,
            'nip' => $request->nip,
            'no_telp' => $request->no_telp,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini tidak cocok',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profil.index')->with('success', 'Password berhasil diperbarui');
    }
}
