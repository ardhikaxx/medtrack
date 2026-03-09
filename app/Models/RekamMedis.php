<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekamMedis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pasien_id',
        'kode_dokumen',
        'no_rekam_medis',
        'tanggal_kunjungan',
        'poli_id',
        'dokter_id',
        'jenis_kunjungan',
        'status_dokumen',
        'ruang_penyimpanan_id',
        'rak',
        'laci',
        'map_folder',
        'jumlah_halaman',
        'ketebalan_cm',
        'tanggal_retensi',
        'kondisi_dokumen',
        'diagnosa_utama',
        'kode_icd10',
        'catatan_dokumen',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'tanggal_retensi' => 'date',
    ];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'poli_id');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function ruangPenyimpanan(): BelongsTo
    {
        return $this->belongsTo(RuangPenyimpanan::class);
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function peminjamans(): BelongsToMany
    {
        return $this->belongsToMany(Peminjaman::class, 'detail_peminjamans')
                    ->withPivot(['status_detail', 'tanggal_dikembalikan', 'kondisi_kembali', 'catatan_detail'])
                    ->withTimestamps();
    }

    public function peminjamansAktif(): BelongsToMany
    {
        return $this->peminjamans()->wherePivot('status_detail', 'dipinjam');
    }

    public function scopeTersedia($query)
    {
        return $query->where('status_dokumen', 'tersedia');
    }

    public function scopeDipinjam($query)
    {
        return $query->where('status_dokumen', 'dipinjam');
    }

    public function isTersedia(): bool
    {
        return $this->status_dokumen === 'tersedia';
    }
}
