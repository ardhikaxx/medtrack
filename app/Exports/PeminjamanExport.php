<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $status;
    protected $startDate;
    protected $endDate;

    public function __construct($status = null, $startDate = null, $endDate = null)
    {
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Peminjaman::with(['peminjam', 'rekamMedis']);

        if ($this->status) {
            $query->where('status_peminjaman', $this->status);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_pinjam', [$this->startDate, $this->endDate]);
        }

        return $query->orderBy('tanggal_pinjam', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No. Peminjaman',
            'Peminjam',
            'Jumlah Dokumen',
            'Tujuan',
            'Tanggal Pinjam',
            'Batas Kembali',
            'Status',
            'Tanggal Dikembalikan',
        ];
    }

    public function map($peminjaman): array
    {
        $statusMap = [
            'menunggu' => 'Menunggu Persetujuan',
            'disetujui' => 'Disetujui',
            'dipinjam' => 'Dipinjam',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai',
            'terlambat' => 'Terlambat',
            'dibatalkan' => 'Dibatalkan',
        ];

        return [
            $peminjaman->no_peminjaman,
            $peminjaman->peminjam->nama_lengkap ?? '-',
            $peminjaman->rekamMedis->count(),
            $peminjaman->tujuan_peminjaman,
            $peminjaman->tanggal_pinjam?->format('d/m/Y') ?? '-',
            $peminjaman->tanggal_kembali_rencana?->format('d/m/Y') ?? '-',
            $statusMap[$peminjaman->status_peminjaman] ?? $peminjaman->status_peminjaman,
            $peminjaman->tanggal_kembali_aktual?->format('d/m/Y') ?? '-',
        ];
    }
}
