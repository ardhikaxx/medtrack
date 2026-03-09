<?php

namespace App\Services;

use App\Models\Pasien;
use App\Models\Peminjaman;
use App\Models\Pengembalian;

class NomorRekamMedisService
{
    public function generateNomorRM(): string
    {
        $tahun = now()->year;
        $lastRM = Pasien::whereYear('created_at', $tahun)
                        ->orderByDesc('no_rekam_medis')
                        ->lockForUpdate()
                        ->first();

        if ($lastRM) {
            $lastNum = (int) substr($lastRM->no_rekam_medis, -6);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return 'RM-' . $tahun . '-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
    }

    public function generateKodeDokumen(): string
    {
        $tahun = now()->year;
        $lastDok = \App\Models\RekamMedis::whereYear('created_at', $tahun)
                        ->orderByDesc('kode_dokumen')
                        ->lockForUpdate()
                        ->first();

        if ($lastDok) {
            $lastNum = (int) substr($lastDok->kode_dokumen, -6);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return 'DOK-' . $tahun . '-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
    }

    public function generateNomorPeminjaman(): string
    {
        $today = now()->format('Ymd');
        $lastPJM = Peminjaman::where('no_peminjaman', 'like', "PJM-{$today}-%")
                              ->orderByDesc('no_peminjaman')
                              ->lockForUpdate()
                              ->first();

        $nextNum = $lastPJM
            ? (int) substr($lastPJM->no_peminjaman, -4) + 1
            : 1;

        return 'PJM-' . $today . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    public function generateNomorPengembalian(): string
    {
        $today = now()->format('Ymd');
        $last = Pengembalian::where('no_pengembalian', 'like', "KBL-{$today}-%")
                             ->orderByDesc('no_pengembalian')
                             ->lockForUpdate()
                             ->first();

        $nextNum = $last
            ? (int) substr($last->no_pengembalian, -4) + 1
            : 1;

        return 'KBL-' . $today . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }
}
