<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Services\PeminjamanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    public function __construct(
        private PeminjamanService $peminjamanService
    ) {}

    public function index(Request $request)
    {
        $query = \App\Models\Pengembalian::with(['peminjaman.peminjam', 'petugas']);

        if ($request->tanggal_dari) {
            $query->whereDate('tanggal_pengembalian', '>=', $request->tanggal_dari);
        }

        if ($request->tanggal_sampai) {
            $query->whereDate('tanggal_pengembalian', '<=', $request->tanggal_sampai);
        }

        $pengembalians = $query->latest()->paginate(20);

        return view('pengembalian.index', compact('pengembalians'));
    }

    public function create(Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status_peminjaman, ['dipinjam', 'terlambat', 'dikembalikan_sebagian'])) {
            return back()->with('error', 'Peminjaman tidak dapat diproses pengembaliannya.');
        }

        $peminjaman->load(['detailPeminjamans.rekamMedis.pasien', 'peminjam']);

        return view('pengembalian.create', compact('peminjaman'));
    }

    public function store(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'tanggal_pengembalian' => 'required|date',
            'detail_kembali' => 'required|array|min:1',
            'detail_kembali.*.rekam_medis_id' => 'required|exists:rekam_medis,id',
            'detail_kembali.*.status' => 'required|in:dikembalikan,hilang,rusak',
            'detail_kembali.*.kondisi' => 'required_if:detail_kembali.*.status,dikembalikan,rusak|nullable|in:baik,cukup,rusak_ringan,rusak_berat',
            'catatan' => 'nullable|string',
        ]);

        try {
            $this->peminjamanService->prosesPengembalian($peminjaman, $validated, Auth::user());
            return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil diproses.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(\App\Models\Pengembalian $pengembalian)
    {
        $pengembalian->load(['peminjaman.peminjam', 'peminjaman.detailPeminjamans.rekamMedis', 'petugas']);

        return view('pengembalian.show', compact('pengembalian'));
    }
}
