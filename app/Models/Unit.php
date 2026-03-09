<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'jenis_unit',
        'lantai',
        'gedung',
        'no_telp_unit',
        'kepala_unit_id',
        'is_active',
        'keterangan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function kepalaUnit(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kepala_unit_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class, 'poli_id');
    }
}
