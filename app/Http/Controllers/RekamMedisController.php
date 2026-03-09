<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\Unit;
use App\Models\User;
use App\Services\NomorRekamMedisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $query = RekamMedis::with(['pasien', 'poli', 'dokter']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_dokumen', 'like', "%{$request->search}%")
                  ->orWhereHas('pasien', function ($pq) use ($request) {
                      $pq->where('nama_lengkap', 'like', "%{$request->search}%")
                         ->orWhere('no_rekam_medis', 'like', "%{$request->search}%");
                  });
            });
        }

        if ($request->status) {
            $query->where('status_dokumen', $request->status);
        }

        if ($request->poli_id) {
            $query->where('poli_id', $request->poli_id);
        }

        $rekamMedis = $query->latest()->paginate(20);
        $polis = Unit::where('jenis_unit', 'poli')->where('is_active', true)->get();

        return view('rekam-medis.index', compact('rekamMedis', 'polis'));
    }

    public function create()
    {
        $pasiens = Pasien::aktif()->get();
        $polis = Unit::where('jenis_unit', 'poli')->where('is_active', true)->get();
        $dokters = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'tenaga_kesehatan_internal');
        })->get();

        return view('rekam-medis.create', compact('pasiens', 'polis', 'dokters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pasien_id' => 'required|exists:pasiens,id',
            'tanggal_kunjungan' => 'required|date',
            'poli_id' => 'nullable|exists:units,id',
            'dokter_id' => 'nullable|exists:users,id',
            'jenis_kunjungan' => 'required|in:rawat_jalan,rawat_inap,ugd,konsultasi',
            'diagnosa_utama' => 'nullable|string',
            'kode_icd10' => 'nullable|string|max:20',
            'jumlah_halaman' => 'nullable|integer',
            'kondisi_dokumen' => 'required|in:baik,cukup,rusak_ringan,rusak_berat',
            'catatan_dokumen' => 'nullable|string',
        ]);

        $pasien = Pasien::find($request->pasien_id);
        $validated['no_rekam_medis'] = $pasien->no_rekam_medis;
        $validated['kode_dokumen'] = app(NomorRekamMedisService::class)->generateKodeDokumen();
        $validated['dibuat_oleh'] = Auth::id();

        $rekamMedis = RekamMedis::create($validated);

        $pasien->update(['kunjungan_terakhir' => $request->tanggal_kunjungan]);

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'create',
            'modul' => 'rekam_medis',
            'model_id' => $rekamMedis->id,
            'data_baru' => $rekamMedis->toArray(),
            'keterangan' => "Menambah dokumen rekam medis {$rekamMedis->kode_dokumen}",
            'created_at' => now(),
        ]);

        return redirect()->route('rekam-medis.index')->with('success', 'Dokumen rekam medis berhasil ditambahkan.');
    }

    public function show(RekamMedis $rekamMedis)
    {
        $rekamMedis->load(['pasien', 'poli', 'dokter', 'dibuatOleh', 'peminjamans.peminjam']);

        return view('rekam-medis.show', compact('rekamMedis'));
    }

    public function edit(RekamMedis $rekamMedis)
    {
        $pasiens = Pasien::aktif()->get();
        $polis = Unit::where('jenis_unit', 'poli')->where('is_active', true)->get();
        $dokters = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'tenaga_kesehatan_internal');
        })->get();

        return view('rekam-medis.edit', compact('rekamMedis', 'pasiens', 'polis', 'dokters'));
    }

    public function update(Request $request, RekamMedis $rekamMedis)
    {
        $validated = $request->validate([
            'tanggal_kunjungan' => 'required|date',
            'poli_id' => 'nullable|exists:units,id',
            'dokter_id' => 'nullable|exists:users,id',
            'jenis_kunjungan' => 'required|in:rawat_jalan,rawat_inap,ugd,konsultasi',
            'diagnosa_utama' => 'nullable|string',
            'kode_icd10' => 'nullable|string|max:20',
            'kondisi_dokumen' => 'required|in:baik,cukup,rusak_ringan,rusak_berat',
            'catatan_dokumen' => 'nullable|string',
        ]);

        $rekamMedis->update($validated);

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'update',
            'modul' => 'rekam_medis',
            'model_id' => $rekamMedis->id,
            'keterangan' => "Mengubah dokumen rekam medis {$rekamMedis->kode_dokumen}",
            'created_at' => now(),
        ]);

        return redirect()->route('rekam-medis.index')->with('success', 'Dokumen rekam medis berhasil diperbarui.');
    }

    public function destroy(RekamMedis $rekamMedis)
    {
        if ($rekamMedis->status_dokumen !== 'tersedia') {
            return back()->with('error', 'Dokumen tidak dapat dihapus karena sedang dipinjam.');
        }

        $rekamMedis->delete();

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'delete',
            'modul' => 'rekam_medis',
            'model_id' => $rekamMedis->id,
            'keterangan' => "Menghapus dokumen rekam medis {$rekamMedis->kode_dokumen}",
            'created_at' => now(),
        ]);

        return redirect()->route('rekam-medis.index')->with('success', 'Dokumen rekam medis berhasil dihapus.');
    }

    public function byPasien(Request $request)
    {
        $pasienId = $request->pasien_id;

        $rekamMedis = RekamMedis::with('poli')
            ->where('pasien_id', $pasienId)
            ->where('status_dokumen', 'tersedia')
            ->get()
            ->map(function ($rm) {
                return [
                    'id' => $rm->id,
                    'kode_dokumen' => $rm->kode_dokumen,
                    'tanggal_kunjungan' => $rm->tanggal_kunjungan->format('Y-m-d'),
                    'poli' => $rm->poli->nama_unit ?? 'Umum',
                    'status_dokumen' => $rm->status_dokumen,
                    'pasien_nama' => $rm->pasien->nama_lengkap,
                ];
            });

        return response()->json($rekamMedis);
    }
}
