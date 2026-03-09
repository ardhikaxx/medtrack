<?php

namespace App\Services;

use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\RekamMedis;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class PeminjamanService
{
    public function __construct(
        private NomorRekamMedisService $nomorService
    ) {}

    public function buatPeminjaman(array $data, User $peminjam): Peminjaman
    {
        return DB::transaction(function () use ($data, $peminjam) {
            foreach ($data['rekam_medis_ids'] as $rmId) {
                $rm = RekamMedis::lockForUpdate()->findOrFail($rmId);
                if (!$rm->isTersedia()) {
                    throw new \Exception("Dokumen {$rm->kode_dokumen} sedang tidak tersedia (status: {$rm->status_dokumen})");
                }
            }

            $peminjaman = Peminjaman::create([
                'no_peminjaman' => $this->nomorService->generateNomorPeminjaman(),
                'peminjam_id' => $peminjam->id,
                'jenis_peminjam' => $peminjam->jenis_pengguna,
                'tujuan_peminjaman' => $data['tujuan_peminjaman'],
                'keperluan_detail' => $data['keperluan_detail'],
                'tanggal_pinjam' => $data['tanggal_pinjam'],
                'tanggal_kembali_rencana' => $data['tanggal_kembali_rencana'],
                'no_surat_permohonan' => $data['no_surat_permohonan'] ?? null,
                'is_pengadilan' => $data['is_pengadilan'] ?? false,
                'status_peminjaman' => 'menunggu_persetujuan',
            ]);

            foreach ($data['rekam_medis_ids'] as $rmId) {
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'rekam_medis_id' => $rmId,
                    'status_detail' => 'dipinjam',
                ]);
            }

            $approvers = User::whereHas('role', function($q) {
                $q->whereIn('nama_role', ['kepala_rekam_medis', 'admin']);
            })->get();

            foreach ($approvers as $approver) {
                \App\Models\Notifikasi::create([
                    'user_id' => $approver->id,
                    'judul' => 'Permohonan Peminjaman Baru',
                    'pesan' => "Permohonan peminjaman #{$peminjaman->no_peminjaman} dari {$peminjam->nama_lengkap} menunggu persetujuan.",
                    'tipe' => 'info',
                    'url_tujuan' => route('peminjaman.show', $peminjaman),
                    'notifiable_type' => Peminjaman::class,
                    'notifiable_id' => $peminjaman->id,
                ]);
            }

            AuditLog::create([
                'user_id' => $peminjam->id,
                'aksi' => 'create',
                'modul' => 'peminjaman',
                'model_type' => Peminjaman::class,
                'model_id' => $peminjaman->id,
                'data_baru' => $peminjaman->toArray(),
                'keterangan' => "Buat permohonan peminjaman #{$peminjaman->no_peminjaman}",
                'created_at' => now(),
            ]);

            return $peminjaman;
        });
    }

    public function setujuiPeminjaman(Peminjaman $peminjaman, User $approver, ?string $catatan = null): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $approver, $catatan) {
            $peminjaman->update([
                'status_peminjaman' => 'disetujui',
                'disetujui_oleh' => $approver->id,
                'tanggal_disetujui' => now(),
                'catatan_persetujuan' => $catatan,
            ]);

            \App\Models\Notifikasi::create([
                'user_id' => $peminjaman->peminjam_id,
                'judul' => 'Permohonan Disetujui',
                'pesan' => "Permohonan peminjaman #{$peminjaman->no_peminjaman} telah disetujui.",
                'tipe' => 'success',
                'url_tujuan' => route('peminjaman.show', $peminjaman),
                'notifiable_type' => Peminjaman::class,
                'notifiable_id' => $peminjaman->id,
            ]);

            AuditLog::create([
                'user_id' => $approver->id,
                'aksi' => 'approve',
                'modul' => 'peminjaman',
                'model_id' => $peminjaman->id,
                'keterangan' => "Menyetujui peminjaman #{$peminjaman->no_peminjaman}",
                'created_at' => now(),
            ]);

            return $peminjaman;
        });
    }

    public function tolakPeminjaman(Peminjaman $peminjaman, User $approver, string $alasan): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $approver, $alasan) {
            $peminjaman->update([
                'status_peminjaman' => 'ditolak',
                'disetujui_oleh' => $approver->id,
                'tanggal_disetujui' => now(),
                'alasan_penolakan' => $alasan,
            ]);

            foreach ($peminjaman->detailPeminjamans as $detail) {
                $detail->rekamMedis->update(['status_dokumen' => 'tersedia']);
            }

            \App\Models\Notifikasi::create([
                'user_id' => $peminjaman->peminjam_id,
                'judul' => 'Permohonan Ditolak',
                'pesan' => "Permohonan peminjaman #{$peminjaman->no_peminjaman} ditolak. Alasan: {$alasan}",
                'tipe' => 'danger',
                'url_tujuan' => route('peminjaman.show', $peminjaman),
                'notifiable_type' => Peminjaman::class,
                'notifiable_id' => $peminjaman->id,
            ]);

            AuditLog::create([
                'user_id' => $approver->id,
                'aksi' => 'reject',
                'modul' => 'peminjaman',
                'model_id' => $peminjaman->id,
                'keterangan' => "Menolak peminjaman #{$peminjaman->no_peminjaman}. Alasan: {$alasan}",
                'created_at' => now(),
            ]);

            return $peminjaman;
        });
    }

    public function prosesPeminjaman(Peminjaman $peminjaman, User $petugas): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $petugas) {
            $peminjaman->update([
                'status_peminjaman' => 'dipinjam',
                'petugas_peminjaman_id' => $petugas->id,
            ]);

            foreach ($peminjaman->detailPeminjamans as $detail) {
                $detail->rekamMedis->update(['status_dokumen' => 'dipinjam']);
            }

            AuditLog::create([
                'user_id' => $petugas->id,
                'aksi' => 'process',
                'modul' => 'peminjaman',
                'model_id' => $peminjaman->id,
                'keterangan' => "Memproses pengeluaran dokumen peminjaman #{$peminjaman->no_peminjaman}",
                'created_at' => now(),
            ]);

            return $peminjaman;
        });
    }

    public function prosesPengembalian(Peminjaman $peminjaman, array $data, User $petugas): Pengembalian
    {
        return DB::transaction(function () use ($peminjaman, $data, $petugas) {
            $isTerlambat = now()->toDateString() > $peminjaman->tanggal_kembali_rencana;
            $hariTerlambat = $isTerlambat ? now()->diffInDays($peminjaman->tanggal_kembali_rencana) : 0;

            $pengembalian = Pengembalian::create([
                'no_pengembalian' => $this->nomorService->generateNomorPengembalian(),
                'peminjaman_id' => $peminjaman->id,
                'tanggal_pengembalian' => $data['tanggal_pengembalian'],
                'petugas_id' => $petugas->id,
                'jumlah_dokumen_kembali' => count($data['detail_kembali']),
                'catatan_pengembalian' => $data['catatan'] ?? null,
                'is_terlambat' => $isTerlambat,
                'hari_terlambat' => $hariTerlambat,
            ]);

            $allSelesai = true;
            $jumlahHilang = 0;
            $jumlahRusak = 0;

            foreach ($data['detail_kembali'] as $detailData) {
                $detail = DetailPeminjaman::where('peminjaman_id', $peminjaman->id)
                                          ->where('rekam_medis_id', $detailData['rekam_medis_id'])
                                          ->first();

                $detail->update([
                    'status_detail' => $detailData['status'],
                    'tanggal_dikembalikan' => now(),
                    'kondisi_kembali' => $detailData['kondisi'],
                    'catatan_detail' => $detailData['catatan'] ?? null,
                    'dikembalikan_oleh' => $petugas->id,
                ]);

                $statusRM = match($detailData['status']) {
                    'dikembalikan' => 'tersedia',
                    'hilang' => 'hilang',
                    'rusak' => 'rusak',
                    default => 'tersedia',
                };

                $detail->rekamMedis->update(['status_dokumen' => $statusRM]);

                if ($detailData['status'] === 'hilang') {
                    $jumlahHilang++;
                    $allSelesai = false;
                } elseif ($detailData['status'] === 'rusak') {
                    $jumlahRusak++;
                    $allSelesai = false;
                } elseif ($detailData['status'] !== 'dikembalikan') {
                    $allSelesai = false;
                }
            }

            $pengembalian->update([
                'jumlah_dokumen_hilang' => $jumlahHilang,
                'jumlah_dokumen_rusak' => $jumlahRusak,
            ]);

            $totalDokumen = $peminjaman->detailPeminjamans()->count();
            $dikembalikan = $peminjaman->detailPeminjamans()->where('status_detail', 'dikembalikan')->count();

            if ($dikembalikan >= $totalDokumen) {
                $peminjaman->update([
                    'status_peminjaman' => 'selesai',
                    'tanggal_kembali_aktual' => $data['tanggal_pengembalian'],
                    'petugas_pengembalian_id' => $petugas->id,
                ]);
            } else {
                $peminjaman->update(['status_peminjaman' => 'dikembalikan_sebagian']);
            }

            AuditLog::create([
                'user_id' => $petugas->id,
                'aksi' => 'return',
                'modul' => 'pengembalian',
                'model_id' => $pengembalian->id,
                'keterangan' => "Memproses pengembalian peminjaman #{$peminjaman->no_peminjaman}",
                'created_at' => now(),
            ]);

            return $pengembalian;
        });
    }
}
