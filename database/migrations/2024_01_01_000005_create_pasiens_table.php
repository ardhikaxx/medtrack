<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('no_rekam_medis', 20)->unique();
            $table->string('nik', 16)->unique()->nullable();
            $table->string('no_kk', 16)->nullable();
            $table->string('nama_lengkap', 150);
            $table->string('nama_panggilan', 50)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'tidak_diketahui'])->default('tidak_diketahui');
            $table->enum('agama', ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu', 'lainnya']);
            $table->enum('status_pernikahan', ['belum_menikah', 'menikah', 'cerai', 'duda', 'janda'])->default('belum_menikah');
            $table->string('pendidikan', 50)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('nama_ibu_kandung', 150)->nullable();
            $table->text('alamat_lengkap');
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('kelurahan', 100);
            $table->string('kecamatan', 100);
            $table->string('kota_kabupaten', 100);
            $table->string('provinsi', 100);
            $table->string('kode_pos', 10)->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->enum('jenis_jaminan', ['umum', 'bpjs_kesehatan', 'bpjs_ketenagakerjaan', 'asuransi_swasta', 'jamkesda'])->default('umum');
            $table->string('no_jaminan', 30)->nullable();
            $table->string('kelas_jaminan', 10)->nullable();
            $table->string('nama_kontak_darurat', 150)->nullable();
            $table->string('hubungan_kontak_darurat', 50)->nullable();
            $table->string('no_telp_kontak_darurat', 15)->nullable();
            $table->enum('status_pasien', ['aktif', 'nonaktif', 'meninggal'])->default('aktif');
            $table->date('tanggal_registrasi');
            $table->date('kunjungan_terakhir')->nullable();
            $table->text('catatan')->nullable();
            $table->string('foto_pasien')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['nama_lengkap']);
            $table->index(['nik']);
            $table->index(['no_rekam_medis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};
