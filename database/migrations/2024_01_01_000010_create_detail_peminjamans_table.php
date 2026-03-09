<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->onDelete('cascade');
            $table->foreignId('rekam_medis_id')->constrained('rekam_medis')->onDelete('cascade');
            $table->enum('status_detail', ['dipinjam', 'dikembalikan', 'hilang', 'rusak'])->default('dipinjam');
            $table->timestamp('tanggal_dikembalikan')->nullable();
            $table->enum('kondisi_kembali', ['baik', 'cukup', 'rusak_ringan', 'rusak_berat'])->nullable();
            $table->text('catatan_detail')->nullable();
            $table->foreignId('dikembalikan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['peminjaman_id', 'rekam_medis_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjamans');
    }
};
