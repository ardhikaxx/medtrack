<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'nip',
        'email',
        'no_telp',
        'username',
        'password',
        'role_id',
        'unit_id',
        'jabatan',
        'spesialisasi',
        'str_number',
        'institusi_asal',
        'jenis_pengguna',
        'foto_profil',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'peminjam_id');
    }

    public function peminjamansDisetujui(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'disetujui_oleh');
    }

    public function rekamMedisDibuat(): HasMany
    {
        return $this->hasMany(RekamMedis::class, 'dibuat_oleh');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function hasPermission(string $permission): bool
    {
        if (!$this->role) return false;
        return $this->role->permissions->contains('nama_permission', $permission);
    }

    public function isAdmin(): bool
    {
        return $this->role && $this->role->nama_role === 'admin';
    }

    public function isDirektur(): bool
    {
        return $this->role && $this->role->nama_role === 'direktur';
    }

    public function isKepalaRekamMedis(): bool
    {
        return $this->role && $this->role->nama_role === 'kepala_rekam_medis';
    }

    public function isPetugasArsip(): bool
    {
        return $this->role && $this->role->nama_role === 'petugas_arsip';
    }
}
