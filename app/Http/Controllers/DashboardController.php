<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\Peminjaman;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pasien' => Pasien::count(),
            'pasien_baru_bulan_ini' => Pasien::whereMonth('tanggal_registrasi', now()->month)->count(),
            'total_dokumen' => RekamMedis::count(),
            'dokumen_tersedia' => RekamMedis::where('status_dokumen', 'tersedia')->count(),
            'peminjaman_aktif' => Peminjaman::whereIn('status_peminjaman', ['dipinjam', 'disetujui'])->count(),
            'menunggu_persetujuan' => Peminjaman::menungguPersetujuan()->count(),
            'terlambat' => Peminjaman::terlambat()->count(),
        ];

        $menungguPersetujuan = Peminjaman::menungguPersetujuan()
            ->with(['peminjam', 'rekamMedis'])
            ->latest()
            ->take(5)
            ->get();

        $terlambat = Peminjaman::terlambat()
            ->with(['peminjam'])
            ->latest()
            ->take(5)
            ->get();

        $aktivitasTerkini = AuditLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact('stats', 'menungguPersetujuan', 'terlambat', 'aktivitas Terkini'));
    }

    public function statistik(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $peminjamanPerBulan = Peminjaman::selectRaw('MONTH(tanggal_pinjam) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_pinjam', $tahun)
            ->groupBy('bulan')
            ->get();

        return response()->json($peminjamanPerBulan);
    }
}
