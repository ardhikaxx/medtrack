<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        $chartData = $this->getChartData();

        return view('dashboard.index', compact('stats', 'menungguPersetujuan', 'terlambat', 'aktivitasTerkini', 'chartData'));
    }

    private function getChartData()
    {
        $tahun = now()->year;
        
        $bulanIndonesia = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        $peminjamanPerBulan = Peminjaman::selectRaw('MONTH(tanggal_pinjam) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_pinjam', $tahun)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $pengembalianPerBulan = Pengembalian::selectRaw('MONTH(tanggal_kembali) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_kembali', $tahun)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $labels = [];
        $peminjamanData = [];
        $pengembalianData = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $bulanIndonesia[$i];
            $peminjamanData[] = $peminjamanPerBulan[$i] ?? 0;
            $pengembalianData[] = $pengembalianPerBulan[$i] ?? 0;
        }

        $statusPeminjaman = Peminjaman::selectRaw('status_peminjaman, COUNT(*) as total')
            ->groupBy('status_peminjaman')
            ->pluck('total', 'status_peminjaman')
            ->toArray();

        return [
            'labels' => $labels,
            'peminjaman' => $peminjamanData,
            'pengembalian' => $pengembalianData,
            'status_peminjaman' => $statusPeminjaman,
        ];
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
