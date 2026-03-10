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

        $pengembalianPerBulan = Pengembalian::selectRaw('MONTH(tanggal_pengembalian) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_pengembalian', $tahun)
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

    public function calendar()
    {
        $events = [];

        $peminjamans = Peminjaman::whereNotNull('tanggal_kembali_rencana')
            ->whereIn('status_peminjaman', ['dipinjam', 'disetujui', 'menunggu_persetujuan'])
            ->get();

        foreach ($peminjamans as $peminjaman) {
            $color = '#dc2626';
            if ($peminjaman->isTerlambat()) {
                $color = '#dc2626';
            } elseif ($peminjaman->tanggal_kembali_rencana <= now()->addDays(3)) {
                $color = '#f59e0b';
            } else {
                $color = '#3b82f6';
            }

            $events[] = [
                'id' => $peminjaman->id,
                'title' => $peminjaman->no_peminjaman,
                'start' => $peminjaman->tanggal_kembali_rencana->format('Y-m-d'),
                'color' => $color,
                'url' => route('peminjaman.show', $peminjaman->id),
            ];
        }

        return view('dashboard.calendar', compact('events'));
    }
}
