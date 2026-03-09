<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RuangPenyimpanan extends Model
{
    protected $fillable = [
        'kode_ruang',
        'nama_ruang',
        'lantai',
        'gedung',
        'kapasitas_rak',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class);
    }
}
