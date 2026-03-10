<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function index()
    {
        return view('scanner.index');
    }

    public function lookup(Request $request)
    {
        $code = $request->get('code');

        $peminjaman = Peminjaman::where('no_peminjaman', 'like', "%{$code}%")
            ->with(['peminjam', 'rekamMedis', 'detailPeminjamans'])
            ->first();

        if ($peminjaman) {
            return response()->json([
                'type' => 'peminjaman',
                'data' => [
                    'id' => $peminjaman->id,
                    'no_peminjaman' => $peminjaman->no_peminjaman,
                    'status' => $peminjaman->status_peminjaman,
                    'tanggal_pinjam' => $peminjaman->tanggal_pinjam?->format('d/m/Y'),
                    'tanggal_kembali_rencana' => $peminjaman->tanggal_kembali_rencana?->format('d/m/Y'),
                    'is_terlambat' => $peminjaman->isTerlambat(),
                    'peminjam' => $peminjaman->peminjam?->nama_lengkap ?? $peminjaman->nama_peminjam_luar,
                    'dokumen' => $peminjaman->rekamMedis->map(fn($rm) => [
                        'no_rekam_medis' => $rm->no_rekam_medis,
                        'nama_pasien' => $rm->nama_pasien,
                    ]),
                ]
            ]);
        }

        $rekamMedis = RekamMedis::where('no_rekam_medis', 'like', "%{$code}%")
            ->orWhere('kode_dokumen', 'like', "%{$code}%")
            ->orWhere('nama_pasien', 'like', "%{$code}%")
            ->first();

        if ($rekamMedis) {
            return response()->json([
                'type' => 'rekam_medis',
                'data' => [
                    'id' => $rekamMedis->id,
                    'no_rekam_medis' => $rekamMedis->no_rekam_medis,
                    'nama_pasien' => $rekamMedis->nama_pasien,
                    'status_dokumen' => $rekamMedis->status_dokumen,
                ]
            ]);
        }

        return response()->json([
            'type' => 'not_found',
            'message' => 'Dokumen atau peminjaman tidak ditemukan'
        ], 404);
    }
}
