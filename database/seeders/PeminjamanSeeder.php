<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PeminjamanSeeder extends Seeder
{
    private $tujuans = ['Rawat Jalan', 'Rawat Inap', 'UGD', 'Konsultasi', 'Klaim Asuransi', 'Surat Keterangan', 'Pengadilan', 'Penelitian'];
    private $keperluans = [
        'Untuk penanganan pasien lebih lanjut',
        'Untuk kepentingan klaim asuransi',
        'Untuk keperluan pengobatan',
        'Untuk dimutasikan ke rumah sakit lain',
        'Untuk kebutuhan operasi',
        'Untuk konsultasi dokter spesialis',
        'Untuk keperluan pengadilan',
        'Untuk penelitian akademik',
        'Untuk membuat surat rujukan',
        'Untuk verifikasi kelayakan pengobatan'
    ];
    private $statusPeminjamans = ['menunggu_persetujuan', 'disetujui', 'dipinjam', 'selesai', 'ditolak'];
    private $institusis = [
        'Klinik Pratama Husada', 'RSUD Kota Bandung', 'RS Hasan Sadikin',
        'BPJS Kesehatan', 'PT Asuransi Prudential', 'PT Asuransi AXA',
        'Kantor Pengadilan Negeri', 'Universitas Indonesia', 'Universitas Padjadjaran',
        'RS Premagati', 'Klinik annisa', 'RS Permata'
    ];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('detail_peminjamans')->truncate();
        DB::table('peminjamans')->truncate();
        Schema::enableForeignKeyConstraints();

        $users = DB::table('users')->pluck('id');
        $rekamMedis = DB::table('rekam_medis')->where('status_dokumen', 'tersedia')->pluck('id', 'kode_dokumen');

        if ($users->isEmpty() || $rekamMedis->isEmpty()) {
            $this->command->error('Data users atau rekam medis kosong. Jalankan seeder lain terlebih dahulu.');
            return;
        }

        $data = [];
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now();

        $peminjamanCounter = 1;

        for ($i = 0; $i < 150; $i++) {
            $tanggalPinjam = Carbon::instance($startDate->copy()->addDays(rand(0, $startDate->diffInDays($endDate))));
            $tanggalKembaliRencana = $tanggalPinjam->copy()->addDays(rand(3, 14));
            $isApproved = rand(0, 10) > 2;
            $isReturned = rand(0, 10) > 4;
            $isLate = $isReturned && $tanggalKembaliRencana->isBefore(Carbon::now());

            $status = 'menunggu_persetujuan';
            if ($isApproved && $isReturned) {
                $status = 'selesai';
            } elseif ($isApproved && !$isReturned) {
                $status = 'dipinjam';
            } elseif ($isApproved) {
                $status = 'disetujui';
            } elseif (rand(0, 10) > 8) {
                $status = 'ditolak';
            }

            $peminjamId = $users->random();
            $disetujuiId = $isApproved ? $users->random() : null;
            $petugasPinjamId = $isApproved ? $users->random() : null;

            $selectedRm = $rekamMedis->random(rand(1, min(3, $rekamMedis->count())));

            $data[] = [
                'no_peminjaman' => sprintf('PMJ%06d', $peminjamanCounter++),
                'peminjam_id' => $peminjamId,
                'nama_peminjam_luar' => rand(0, 1) ? null : $this->getRandomName(),
                'institusi_peminjam' => $this->institusis[array_rand($this->institusis)],
                'jenis_peminjam' => rand(0, 1) ? 'internal' : 'eksternal',
                'tujuan_peminjaman' => $this->tujuans[array_rand($this->tujuans)],
                'keperluan_detail' => $this->keperluans[array_rand($this->keperluans)],
                'no_surat_permohonan' => rand(0, 1) ? sprintf('%s/PP/%s', rand(100, 999), Carbon::now()->year) : null,
                'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
                'tanggal_kembali_rencana' => $tanggalKembaliRencana->format('Y-m-d'),
                'tanggal_kembali_aktual' => $isReturned ? $tanggalKembaliRencana->copy()->addDays(rand(-2, 5))->format('Y-m-d') : null,
                'status_peminjaman' => $status,
                'disetujui_oleh' => $disetujuiId,
                'tanggal_disetujui' => $isApproved ? $tanggalPinjam->copy()->addDay()->format('Y-m-d H:i:s') : null,
                'catatan_persetujuan' => $isApproved ? 'Permohonan disetujui' : null,
                'alasan_penolakan' => $status === 'ditolak' ? 'Dokumen sedang dalam proses' : null,
                'petugas_peminjaman_id' => $petugasPinjamId,
                'petugas_pengembalian_id' => $isReturned ? $users->random() : null,
                'catatan_peminjaman' => rand(0, 1) ? 'Peminjaman berjalan dengan baik' : null,
                'catatan_pengembalian' => $isReturned ? 'Dokumen dikembalikan dalam kondisi baik' : null,
                'is_pengadilan' => rand(0, 10) > 8,
                'allow_fotokopi' => rand(0, 10) > 5,
                'no_surat_pengadilan' => rand(0, 1) && rand(0, 10) > 8 ? sprintf('PK/%s/%s', rand(100, 999), Carbon::now()->year) : null,
                'dokter_yang_merawat_id' => rand(0, 1) ? $users->random() : null,
                'created_at' => $tanggalPinjam,
                'updated_at' => now(),
            ];

            if (count($data) >= 30) {
                DB::table('peminjamans')->insert($data);
                
                $peminjamanIds = DB::table('peminjamans')->orderBy('id', 'desc')->take(count($data))->pluck('id');
                
                foreach ($peminjamanIds as $index => $pmjId) {
                    $jumlahDokumen = rand(1, 3);
                    $selectedRmForThisPm = $rekamMedis->random($jumlahDokumen);
                    
                    foreach ($selectedRmForThisPm as $kodeRm => $rmId) {
                        $isThisReturned = rand(0, 10) > 4;
                        DB::table('detail_peminjamans')->insert([
                            'peminjaman_id' => $pmjId,
                            'rekam_medis_id' => $rmId,
                            'status_detail' => $isThisReturned ? 'dikembalikan' : 'dipinjam',
                            'tanggal_dikembalikan' => $isThisReturned ? Carbon::now()->subDays(rand(1, 30))->format('Y-m-d H:i:s') : null,
                            'kondisi_kembali' => $isThisReturned ? ['baik', 'cukup'][rand(0, 1)] : null,
                            'catatan_detail' => $isThisReturned ? 'Dikembalikan dalam kondisi baik' : null,
                            'dikembalikan_oleh' => $isThisReturned ? $users->random() : null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                
                $data = [];
            }
        }

        if (count($data) > 0) {
            DB::table('peminjamans')->insert($data);
            
            $peminjamanIds = DB::table('peminjamans')->orderBy('id', 'desc')->take(count($data))->pluck('id');
            
            foreach ($peminjamanIds as $pmjId) {
                $jumlahDokumen = rand(1, 3);
                $selectedRmForThisPm = $rekamMedis->random($jumlahDokumen);
                
                foreach ($selectedRmForThisPm as $kodeRm => $rmId) {
                    $isReturned = rand(0, 10) > 4;
                    DB::table('detail_peminjamans')->insert([
                        'peminjaman_id' => $pmjId,
                        'rekam_medis_id' => $rmId,
                        'status_detail' => $isReturned ? 'dikembalikan' : 'dipinjam',
                        'tanggal_dikembalikan' => $isReturned ? Carbon::now()->subDays(rand(1, 30))->format('Y-m-d H:i:s') : null,
                        'kondisi_kembali' => $isReturned ? ['baik', 'cukup'][rand(0, 1)] : null,
                        'catatan_detail' => $isReturned ? 'Dikembalikan dalam kondisi baik' : null,
                        'dikembalikan_oleh' => $isReturned ? $users->random() : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $totalPeminjaman = DB::table('peminjamans')->count();
        $totalDetail = DB::table('detail_peminjamans')->count();
        $this->command->info("Berhasil seeding $totalPeminjaman peminjaman dan $totalDetail detail peminjaman.");
    }

    private function getRandomName(): string
    {
        $namaDepans = ['Ahmad', 'Budi', 'Dedi', 'Eko', 'Fandi', 'Hadi', 'Indra', 'Joko', 'Kurnia', 'Lukman', 'Ani', 'Bella', 'Citra', 'Dewi', 'Eka', 'Fitri', 'Gita', 'Hana', 'Ika', 'Jasmine'];
        $namaBelakangs = ['Santoso', 'Susanto', 'Saputra', 'Nugroho', 'Pratama', 'Rahman', 'Setiawan', 'Wibowo', 'Wijaya', 'Aprilianti', 'Astuti', 'Cahyani', 'Farida', 'Kartika', 'Lestari'];
        return $namaDepans[array_rand($namaDepans)] . ' ' . $namaBelakangs[array_rand($namaBelakangs)];
    }
}
