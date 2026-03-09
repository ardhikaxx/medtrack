<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('no_peminjaman', 30)->unique();
            $table->foreignId('peminjam_id')->constrained('users');
            $table->string('nama_peminjam_luar', 150)->nullable();
            $table->string('institusi_peminjam', 200)->nullable();
            $table->enum('jenis_peminjam', ['internal', 'eksternal']);
            $table->string('tujuan_peminjaman', 50);
            $table->text('keperluan_detail');
            $table->string('no_surat_permohonan', 50)->nullable();
            $table->string('file_surat_permohonan')->nullable();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->enum('status_peminjaman', [
                'menunggu_persetujuan',
                'disetujui',
                'ditolak',
                'dipinjam',
                'dikembalikan_sebagian',
                'selesai',
                'terlambat'
            ])->default('menunggu_persetujuan');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tanggal_disetujui')->nullable();
            $table->text('catatan_persetujuan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->foreignId('petugas_peminjaman_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('petugas_pengembalian_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_peminjaman')->nullable();
            $table->text('catatan_pengembalian')->nullable();
            $table->boolean('is_pengadilan')->default(false);
            $table->boolean('allow_fotokopi')->default(false);
            $table->string('no_surat_pengadilan', 100)->nullable();
            $table->foreignId('dokter_yang_merawat_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status_peminjaman']);
            $table->index(['tanggal_pinjam']);
            $table->index(['peminjam_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
