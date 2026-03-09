<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\Unit;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pasien' => Pasien::count(),
            'total_dokumen' => RekamMedis::count(),
            'dokumen_tersedia' => RekamMedis::where('status_dokumen', 'tersedia')->count(),
            'dokumen_dipinjam' => RekamMedis::where('status_dokumen', 'dipinjam')->count(),
            'total_peminjaman' => Peminjaman::count(),
            'peminjaman_selesai' => Peminjaman::where('status_peminjaman', 'selesai')->count(),
            'peminjaman_aktif' => Peminjaman::whereIn('status_peminjaman', ['dipinjam', 'disetujui'])->count(),
            'terlambat' => Peminjaman::terlambat()->count(),
        ];

        return view('laporan.index', compact('stats'));
    }

    public function peminjaman(Request $request)
    {
        $query = Peminjaman::with(['peminjam', 'rekamMedis']);

        if ($request->tanggal_dari) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->tanggal_sampai) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        if ($request->status) {
            $query->where('status_peminjaman', $request->status);
        }

        if ($request->jenis_peminjam) {
            $query->where('jenis_peminjam', $request->jenis_peminjam);
        }

        $peminjamans = $query->latest()->paginate(20);

        $statistik = [
            'total' => $query->count(),
            'menunggu' => (clone $query)->where('status_peminjaman', 'menunggu_persetujuan')->count(),
            'disetujui' => (clone $query)->where('status_peminjaman', 'disetujui')->count(),
            'dipinjam' => (clone $query)->where('status_peminjaman', 'dipinjam')->count(),
            'selesai' => (clone $query)->where('status_peminjaman', 'selesai')->count(),
            'terlambat' => (clone $query)->where('status_peminjaman', 'terlambat')->count(),
        ];

        return view('laporan.peminjaman', compact('peminjamans', 'statistik'));
    }

    public function pengembalian(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.peminjam', 'petugas']);

        if ($request->tanggal_dari) {
            $query->whereDate('tanggal_pengembalian', '>=', $request->tanggal_dari);
        }

        if ($request->tanggal_sampai) {
            $query->whereDate('tanggal_pengembalian', '<=', $request->tanggal_sampai);
        }

        $pengembalians = $query->latest()->paginate(20);

        return view('laporan.pengembalian', compact('pengembalians'));
    }

    public function terlambat()
    {
        $peminjamans = Peminjaman::terlambat()
            ->with(['peminjam', 'rekamMedis'])
            ->latest()
            ->paginate(20);

        return view('laporan.terlambat', compact('peminjamans'));
    }

    public function statistikDokumen()
    {
        $statusDokumen = RekamMedis::selectRaw('status_dokumen, COUNT(*) as total')
            ->groupBy('status_dokumen')
            ->get();

        $perPoli = RekamMedis::selectRaw('poli_id, COUNT(*) as total')
            ->whereNotNull('poli_id')
            ->groupBy('poli_id')
            ->with('poli')
            ->get();

        return view('laporan.statistik-dokumen', compact('statusDokumen', 'perPoli'));
    }

    public function rekapBulanan(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $peminjamanPerHari = Peminjaman::selectRaw('DATE(tanggal_pinjam) as tanggal, COUNT(*) as total')
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $perTujuan = Peminjaman::selectRaw('tujuan_peminjaman, COUNT(*) as total')
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->groupBy('tujuan_peminjaman')
            ->get();

        return view('laporan.rekap-bulanan', compact('peminjamanPerHari', 'perTujuan', 'bulan', 'tahun'));
    }
}
