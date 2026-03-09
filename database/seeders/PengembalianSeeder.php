<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PengembalianSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('pengembalians')->truncate();
        Schema::enableForeignKeyConstraints();

        $peminjamans = DB::table('peminjamans')
            ->whereIn('status_peminjaman', ['dipinjam', 'selesai', 'dikembalikan_sebagian'])
            ->whereNotNull('tanggal_kembali_aktual')
            ->get();

        $users = DB::table('users')->pluck('id');

        if ($peminjamans->isEmpty() || $users->isEmpty()) {
            $this->command->error('Data peminjaman atau users kosong. Jalankan seeder PeminjamanSeeder terlebih dahulu.');
            return;
        }

        $data = [];
        $pengembalianCounter = 1;

        foreach ($peminjamans as $pmj) {
            $tanggalPengembalian = Carbon::parse($pmj->tanggal_kembali_aktual);
            $tanggalRencana = Carbon::parse($pmj->tanggal_kembali_rencana);
            $isTerlambat = $tanggalPengembalian->isAfter($tanggalRencana);

            $detailPeminjaman = DB::table('detail_peminjamans')
                ->where('peminjaman_id', $pmj->id)
                ->get();

            $jumlahKembali = $detailPeminjaman->where('status_detail', 'dikembalikan')->count();
            $jumlahHilang = $detailPeminjaman->where('status_detail', 'hilang')->count();
            $jumlahRusak = $detailPeminjaman->where('status_detail', 'rusak')->count();

            if ($jumlahKembali > 0) {
                $data[] = [
                    'no_pengembalian' => sprintf('KMB%06d', $pengembalianCounter++),
                    'peminjaman_id' => $pmj->id,
                    'tanggal_pengembalian' => $tanggalPengembalian->format('Y-m-d'),
                    'petugas_id' => $users->random(),
                    'jumlah_dokumen_kembali' => $jumlahKembali,
                    'jumlah_dokumen_hilang' => $jumlahHilang,
                    'jumlah_dokumen_rusak' => $jumlahRusak,
                    'catatan_pengembalian' => $jumlahHilang > 0 || $jumlahRusak > 0 
                        ? 'Terdapat ' . ($jumlahHilang > 0 ? $jumlahHilang . ' dokumen hilang' : '') . ($jumlahRusak > 0 ? ', ' . $jumlahRusak . ' dokumen rusak' : '')
                        : 'Dokumen dikembalikan dalam kondisi baik',
                    'is_terlambat' => $isTerlambat,
                    'hari_terlambat' => $isTerlambat ? $tanggalPengembalian->diffInDays($tanggalRencana) : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (count($data) >= 30) {
                DB::table('pengembalians')->insert($data);
                $data = [];
            }
        }

        if (count($data) > 0) {
            DB::table('pengembalians')->insert($data);
        }

        $totalPengembalian = DB::table('pengembalians')->count();
        $this->command->info("Berhasil seeding $totalPengembalian pengembalian.");
    }
}
