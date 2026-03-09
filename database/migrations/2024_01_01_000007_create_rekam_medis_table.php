<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->string('kode_dokumen', 30)->unique();
            $table->string('no_rekam_medis', 20);
            $table->date('tanggal_kunjungan');
            $table->foreignId('poli_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('dokter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('jenis_kunjungan', ['rawat_jalan', 'rawat_inap', 'ugd', 'konsultasi']);
            $table->enum('status_dokumen', ['tersedia', 'dipinjam', 'dalam_proses', 'hilang', 'rusak', 'dimusnahkan'])->default('tersedia');
            $table->foreignId('ruang_penyimpanan_id')->nullable()->constrained('ruang_penyimpanans')->nullOnDelete();
            $table->string('rak', 20)->nullable();
            $table->string('laci', 20)->nullable();
            $table->string('map_folder', 20)->nullable();
            $table->integer('jumlah_halaman')->nullable();
            $table->integer('ketebalan_cm')->nullable();
            $table->date('tanggal_retensi')->nullable();
            $table->enum('kondisi_dokumen', ['baik', 'cukup', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->text('diagnosa_utama')->nullable();
            $table->string('kode_icd10', 20)->nullable();
            $table->text('catatan_dokumen')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['pasien_id', 'tanggal_kunjungan']);
            $table->index(['status_dokumen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
