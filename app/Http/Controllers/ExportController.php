<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Exports\PeminjamanExport;
use App\Exports\PengembalianExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function peminjamanExcel(Request $request)
    {
        $status = $request->status;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $fileName = 'laporan_peminjaman_' . now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new PeminjamanExport($status, $startDate, $endDate), $fileName);
    }

    public function peminjamanPdf(Request $request)
    {
        $status = $request->status;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Peminjaman::with(['peminjam', 'rekamMedis']);

        if ($status) {
            $query->where('status_peminjaman', $status);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_pinjam', [$startDate, $endDate]);
        }

        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->get();

        $pdf = Pdf::loadView('exports.peminjaman-pdf', [
            'peminjaman' => $peminjaman,
            'tanggal_cetak' => now()->format('d/m/Y H:i'),
            'filter' => [
                'status' => $status,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]
        ]);

        return $pdf->download('laporan_peminjaman_' . now()->format('Y-m-d') . '.pdf');
    }

    public function pengembalianExcel(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $fileName = 'laporan_pengembalian_' . now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new PengembalianExport($startDate, $endDate), $fileName);
    }

    public function pengembalianPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Pengembalian::with(['peminjaman.peminjam', 'peminjaman.rekamMedis', 'petugas']);

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_pengembalian', [$startDate, $endDate]);
        }

        $pengembalian = $query->orderBy('tanggal_pengembalian', 'desc')->get();

        $pdf = Pdf::loadView('exports.pengembalian-pdf', [
            'pengembalian' => $pengembalian,
            'tanggal_cetak' => now()->format('d/m/Y H:i'),
            'filter' => [
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]
        ]);

        return $pdf->download('laporan_pengembalian_' . now()->format('Y-m-d') . '.pdf');
    }

    public function rekapBulanan(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $peminjaman = Peminjaman::with(['peminjam', 'rekamMedis'])
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->get();

        $pengembalian = Pengembalian::with(['peminjaman.peminjam', 'petugas'])
            ->whereMonth('tanggal_pengembalian', $bulan)
            ->whereYear('tanggal_pengembalian', $tahun)
            ->get();

        $stats = [
            'total_peminjaman' => $peminjaman->count(),
            'total_pengembalian' => $pengembalian->count(),
            'peminjaman_selesai' => $peminjaman->where('status_peminjaman', 'selesai')->count(),
            'peminjaman_aktif' => $peminjaman->whereIn('status_peminjaman', ['dipinjam', 'disetujui'])->count(),
            'terlambat' => $peminjaman->where('status_peminjaman', 'terlambat')->count(),
        ];

        $pdf = Pdf::loadView('exports.rekap-bulanan-pdf', [
            'peminjaman' => $peminjaman,
            'pengembalian' => $pengembalian,
            'stats' => $stats,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tanggal_cetak' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download('rekap_bulanan_' . $tahun . '_' . $bulan . '.pdf');
    }
}
