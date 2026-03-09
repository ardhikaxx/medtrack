<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unit', 20)->unique();
            $table->string('nama_unit', 150);
            $table->enum('jenis_unit', ['poli', 'ugd', 'rawat_inap', 'penunjang', 'administrasi', 'lainnya']);
            $table->string('lantai', 10)->nullable();
            $table->string('gedung', 50)->nullable();
            $table->string('no_telp_unit', 15)->nullable();
            $table->unsignedBigInteger('kepala_unit_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
