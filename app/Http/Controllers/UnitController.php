<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_unit', 'like', "%{$request->search}%")
                  ->orWhere('kode_unit', 'like', "%{$request->search}%");
            });
        }

        if ($request->jenis_unit) {
            $query->where('jenis_unit', $request->jenis_unit);
        }

        if ($request->is_active !== null) {
            $query->where('is_active', $request->is_active);
        }

        $units = $query->latest()->paginate(20);

        return view('unit.index', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_unit' => 'required|string|unique:units,kode_unit',
            'nama_unit' => 'required|string|max:150',
            'jenis_unit' => 'required|in:poli,ugd,rawat_inap,penunjang,administrasi,lainnya',
            'lantai' => 'nullable|string|max:10',
            'gedung' => 'nullable|string|max:50',
            'no_telp_unit' => 'nullable|string|max:15',
            'keterangan' => 'nullable|string',
        ]);

        Unit::create($validated);

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'create',
            'modul' => 'unit',
            'keterangan' => "Menambah unit {$validated['nama_unit']}",
            'created_at' => now(),
        ]);

        return back()->with('success', 'Unit berhasil ditambahkan.');
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'kode_unit' => 'required|string|unique:units,kode_unit,' . $unit->id,
            'nama_unit' => 'required|string|max:150',
            'jenis_unit' => 'required|in:poli,ugd,rawat_inap,penunjang,administrasi,lainnya',
            'lantai' => 'nullable|string|max:10',
            'gedung' => 'nullable|string|max:50',
            'no_telp_unit' => 'nullable|string|max:15',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $unit->update($validated);

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'update',
            'modul' => 'unit',
            'model_id' => $unit->id,
            'keterangan' => "Mengubah unit {$unit->nama_unit}",
            'created_at' => now(),
        ]);

        return back()->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->users()->count() > 0) {
            return back()->with('error', 'Unit tidak dapat dihapus karena masih memiliki pengguna.');
        }

        $unit->delete();

        return back()->with('success', 'Unit berhasil dihapus.');
    }
}
