<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjamans';

    protected $fillable = [
        'peminjaman_id',
        'rekam_medis_id',
        'status_detail',
        'tanggal_dikembalikan',
        'kondisi_kembali',
        'catatan_detail',
        'dikembalikan_oleh',
    ];

    protected $casts = [
        'tanggal_dikembalikan' => 'datetime',
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function rekamMedis(): BelongsTo
    {
        return $this->belongsTo(RekamMedis::class);
    }

    public function dikembalikanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dikembalikan_oleh');
    }
}
