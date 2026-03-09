<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Services\NomorRekamMedisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasienController extends Controller
{
    public function index(Request $request)
    {
        $query = Pasien::query();

        if ($request->search) {
            $query->search($request->search);
        }

        if ($request->status) {
            $query->where('status_pasien', $request->status);
        }

        $pasiens = $query->latest()->paginate(20);

        return view('pasien.index', compact('pasiens'));
    }

    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nik' => 'nullable|string|max:16|unique:pasiens,nik',
            'no_kk' => 'nullable|string|max:16',
            'nama_panggilan' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'golongan_darah' => 'nullable',
            'agama' => 'required',
            'status_pernikahan' => 'required',
            'pendidikan' => 'nullable|string|max:50',
            'pekerjaan' => 'nullable|string|max:100',
            'nama_ibu_kandung' => 'nullable|string|max:150',
            'alamat_lengkap' => 'required|text',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kota_kabupaten' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'no_telp' => 'nullable|string|max:15',
            'no_hp' => 'nullable|string|max:15',
            'jenis_jaminan' => 'required',
            'no_jaminan' => 'nullable|string|max:30',
            'kelas_jaminan' => 'nullable|string|max:10',
            'nama_kontak_darurat' => 'nullable|string|max:150',
            'hubungan_kontak_darurat' => 'nullable|string|max:50',
            'no_telp_kontak_darurat' => 'nullable|string|max:15',
        ]);

        $validated['no_rekam_medis'] = app(NomorRekamMedisService::class)->generateNomorRM();
        $validated['tanggal_registrasi'] = now()->toDateString();

        $pasien = Pasien::create($validated);

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'create',
            'modul' => 'pasien',
            'model_id' => $pasien->id,
            'data_baru' => $pasien->toArray(),
            'keterangan' => "Menambah data pasien {$pasien->nama_lengkap}",
            'created_at' => now(),
        ]);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function show(Pasien $pasien)
    {
        $pasien->load(['rekamMedis' => function ($query) {
            $query->latest();
        }]);

        return view('pasien.show', compact('pasien'));
    }

    public function edit(Pasien $pasien)
    {
        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nik' => 'nullable|string|max:16|unique:pasiens,nik,' . $pasien->id,
            'no_kk' => 'nullable|string|max:16',
            'nama_panggilan' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'golongan_darah' => 'nullable',
            'agama' => 'required',
            'status_pernikahan' => 'required',
            'alamat_lengkap' => 'required|text',
            'no_hp' => 'nullable|string|max:15',
            'jenis_jaminan' => 'required',
            'status_pasien' => 'required|in:aktif,nonaktif,meninggal',
        ]);

        $pasien->update($validated);

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'update',
            'modul' => 'pasien',
            'model_id' => $pasien->id,
            'keterangan' => "Mengubah data pasien {$pasien->nama_lengkap}",
            'created_at' => now(),
        ]);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Pasien $pasien)
    {
        $pasien->delete();

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'delete',
            'modul' => 'pasien',
            'model_id' => $pasien->id,
            'keterangan' => "Menghapus data pasien {$pasien->nama_lengkap}",
            'created_at' => now(),
        ]);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dihapus.');
    }

    public function select2(Request $request)
    {
        $search = $request->q ?? '';
        $pasiens = Pasien::aktif()
            ->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_rekam_medis', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            })
            ->select('id', 'no_rekam_medis', 'nama_lengkap', 'nik', 'tanggal_lahir')
            ->limit(20)
            ->get();

        return response()->json($pasiens);
    }
}
