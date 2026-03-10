<?php

namespace App\Exports;

use App\Models\Pengembalian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengembalianExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Pengembalian::with(['peminjaman.peminjam', 'peminjaman.rekamMedis', 'petugas']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_kembali', [$this->startDate, $this->endDate]);
        }

        return $query->orderBy('tanggal_kembali', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No. Pengembalian',
            'No. Peminjaman',
            'Peminjam',
            'Jumlah Dokumen',
            'Tanggal Kembali',
            'Petugas',
            'Kondisi Dokumen',
            'Keterangan',
        ];
    }

    public function map($pengembalian): array
    {
        return [
            $pengembalian->no_pengembalian,
            $pengembalian->peminjaman->no_peminjaman ?? '-',
            $pengembalian->peminjaman->peminjam->nama_lengkap ?? '-',
            $pengembalian->peminjaman->rekamMedis->count() ?? 0,
            $pengembalian->tanggal_kembali->format('d/m/Y'),
            $pengembalian->petugas->nama_lengkap ?? '-',
            $pengembalian->kondisi_dokumen ?? '-',
            $pengembalian->keterangan ?? '-',
        ];
    }
}
