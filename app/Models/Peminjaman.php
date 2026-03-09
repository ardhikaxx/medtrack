<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peminjaman extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_peminjaman',
        'peminjam_id',
        'nama_peminjam_luar',
        'institusi_peminjam',
        'jenis_peminjam',
        'tujuan_peminjaman',
        'keperluan_detail',
        'no_surat_permohonan',
        'file_surat_permohonan',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status_peminjaman',
        'disetujui_oleh',
        'tanggal_disetujui',
        'catatan_persetujuan',
        'alasan_penolakan',
        'petugas_peminjaman_id',
        'petugas_pengembalian_id',
        'catatan_peminjaman',
        'catatan_pengembalian',
        'is_pengadilan',
        'allow_fotokopi',
        'no_surat_pengadilan',
        'dokter_yang_merawat_id',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
        'tanggal_disetujui' => 'datetime',
        'is_pengadilan' => 'boolean',
        'allow_fotokopi' => 'boolean',
    ];

    public function peminjam(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peminjam_id');
    }

    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function petugasPeminjaman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_peminjaman_id');
    }

    public function petugasPengembalian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_pengembalian_id');
    }

    public function dokterYangMerawat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dokter_yang_merawat_id');
    }

    public function rekamMedis(): BelongsToMany
    {
        return $this->belongsToMany(RekamMedis::class, 'detail_peminjamans')
                    ->withPivot(['status_detail', 'tanggal_dikembalikan', 'kondisi_kembali', 'catatan_detail', 'dikembalikan_oleh'])
                    ->withTimestamps();
    }

    public function detailPeminjamans(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function pengembalians(): HasMany
    {
        return $this->hasMany(Pengembalian::class);
    }

    public function scopeTerlambat($query)
    {
        return $query->where('tanggal_kembali_rencana', '<', now()->toDateString())
                     ->whereNotIn('status_peminjaman', ['selesai', 'dikembalikan_sebagian']);
    }

    public function scopeMenungguPersetujuan($query)
    {
        return $query->where('status_peminjaman', 'menunggu_persetujuan');
    }

    public function isTerlambat(): bool
    {
        return $this->tanggal_kembali_rencana < now()->toDateString()
            && !in_array($this->status_peminjaman, ['selesai']);
    }

    public function getHariTerlambatAttribute(): int
    {
        if (!$this->isTerlambat()) return 0;
        return now()->diffInDays($this->tanggal_kembali_rencana);
    }

    public function getJumlahDokumenAttribute(): int
    {
        return $this->detailPeminjamans()->count();
    }
}
