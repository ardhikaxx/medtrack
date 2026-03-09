<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->string('no_pengembalian', 30)->unique();
            $table->foreignId('peminjaman_id')->constrained('peminjamans');
            $table->date('tanggal_pengembalian');
            $table->foreignId('petugas_id')->constrained('users');
            $table->integer('jumlah_dokumen_kembali');
            $table->integer('jumlah_dokumen_hilang')->default(0);
            $table->integer('jumlah_dokumen_rusak')->default(0);
            $table->text('catatan_pengembalian')->nullable();
            $table->boolean('is_terlambat')->default(false);
            $table->integer('hari_terlambat')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};
