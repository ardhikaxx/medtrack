<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 150);
            $table->string('nik', 16)->unique()->nullable();
            $table->string('nip', 30)->unique()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->string('jabatan', 100)->nullable();
            $table->string('spesialisasi', 100)->nullable();
            $table->string('str_number', 50)->nullable();
            $table->string('institusi_asal', 200)->nullable();
            $table->enum('jenis_pengguna', ['internal', 'eksternal'])->default('internal');
            $table->string('foto_profil')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
