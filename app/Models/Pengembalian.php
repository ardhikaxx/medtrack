<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    protected $fillable = [
        'no_pengembalian',
        'peminjaman_id',
        'tanggal_pengembalian',
        'petugas_id',
        'jumlah_dokumen_kembali',
        'jumlah_dokumen_hilang',
        'jumlah_dokumen_rusak',
        'catatan_pengembalian',
        'is_terlambat',
        'hari_terlambat',
    ];

    protected $casts = [
        'tanggal_pengembalian' => 'date',
        'is_terlambat' => 'boolean',
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
