<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\RekamMedis;
use App\Services\PeminjamanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function __construct(
        private PeminjamanService $peminjamanService
    ) {}

    public function index(Request $request)
    {
        $query = Peminjaman::with(['peminjam', 'rekamMedis']);

        if (!Auth::user()->hasPermission('peminjaman.view_all')) {
            $query->where('peminjam_id', Auth::id());
        }

        if ($request->status) {
            $query->where('status_peminjaman', $request->status);
        }

        if ($request->tanggal_dari) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->tanggal_sampai) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        $peminjamans = $query->latest()->paginate(20);

        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $dokters = \App\Models\User::whereHas('role', function ($q) {
            $q->where('nama_role', 'tenaga_kesehatan_internal');
        })->get();

        return view('peminjaman.create', compact('dokters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tujuan_peminjaman' => 'required|in:pelayanan,penelitian,audit,pengadilan,pendidikan,asuransi',
            'keperluan_detail' => 'required|string',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'rekam_medis_ids' => 'required|array|min:1',
            'rekam_medis_ids.*' => 'required|exists:rekam_medis,id',
        ]);

        try {
            $peminjaman = $this->peminjamanService->buatPeminjaman($validated, Auth::user());
            return redirect()->route('peminjaman.index')->with('success', 'Permohonan peminjaman berhasil dikirim.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load([
            'peminjam',
            'disetujuiOleh',
            'petugasPeminjaman',
            'petugasPengembalian',
            'dokterYangMerawat',
            'detailPeminjamans.rekamMedis.pasien',
            'pengembalians',
        ]);

        return view('peminjaman.show', compact('peminjaman'));
    }

    public function setujui(Request $request, Peminjaman $peminjaman)
    {
        try {
            $this->peminjamanService->setujuiPeminjaman($peminjaman, Auth::user(), $request->catatan);
            return back()->with('success', 'Permohonan peminjaman disetujui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function tolak(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        try {
            $this->peminjamanService->tolakPeminjaman($peminjaman, Auth::user(), $request->alasan_penolakan);
            return back()->with('success', 'Permohonan peminjaman ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function proses(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman !== 'disetujui') {
            return back()->with('error', 'Peminjaman harus disetujui terlebih dahulu.');
        }

        try {
            $this->peminjamanService->prosesPeminjaman($peminjaman, Auth::user());
            return back()->with('success', 'Peminjaman diproses. Dokumen telah diserahkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function batalkan(Request $request, Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status_peminjaman, ['menunggu_persetujuan', 'disetujui'])) {
            return back()->with('error', 'Peminjaman tidak dapat dibatalkan.');
        }

        $peminjaman->update([
            'status_peminjaman' => 'ditolak',
            'alasan_penolakan' => 'Dibatalkan oleh pemohon',
        ]);

        foreach ($peminjaman->detailPeminjamans as $detail) {
            $detail->rekamMedis->update(['status_dokumen' => 'tersedia']);
        }

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => 'cancel',
            'modul' => 'peminjaman',
            'model_id' => $peminjaman->id,
            'keterangan' => "Membatalkan peminjaman #{$peminjaman->no_peminjaman}",
            'created_at' => now(),
        ]);

        return back()->with('success', 'Peminjaman dibatalkan.');
    }

    public function menunggu()
    {
        $peminjamans = Peminjaman::menungguPersetujuan()
            ->with(['peminjam', 'rekamMedis'])
            ->latest()
            ->paginate(20);

        return view('peminjaman.menunggu', compact('peminjamans'));
    }

    public function terlambat()
    {
        $peminjamans = Peminjaman::terlambat()
            ->with(['peminjam', 'rekamMedis'])
            ->latest()
            ->paginate(20);

        return view('peminjaman.terlambat', compact('peminjamans'));
    }
}
