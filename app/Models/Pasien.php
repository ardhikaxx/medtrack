<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_rekam_medis',
        'nik',
        'no_kk',
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'golongan_darah',
        'agama',
        'status_pernikahan',
        'pendidikan',
        'pekerjaan',
        'nama_ibu_kandung',
        'alamat_lengkap',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota_kabupaten',
        'provinsi',
        'kode_pos',
        'no_telp',
        'no_hp',
        'jenis_jaminan',
        'no_jaminan',
        'kelas_jaminan',
        'nama_kontak_darurat',
        'hubungan_kontak_darurat',
        'no_telp_kontak_darurat',
        'status_pasien',
        'tanggal_registrasi',
        'kunjungan_terakhir',
        'catatan',
        'foto_pasien',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_registrasi' => 'date',
        'kunjungan_terakhir' => 'date',
    ];

    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class);
    }

    public function rekamMedisAktif(): HasMany
    {
        return $this->hasMany(RekamMedis::class)->where('status_dokumen', 'tersedia');
    }

    public function scopeAktif($query)
    {
        return $query->where('status_pasien', 'aktif');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_lengkap', 'like', "%{$keyword}%")
              ->orWhere('no_rekam_medis', 'like', "%{$keyword}%")
              ->orWhere('nik', 'like', "%{$keyword}%")
              ->orWhere('no_hp', 'like', "%{$keyword}%");
        });
    }

    public function getUmurAttribute(): string
    {
        return $this->tanggal_lahir->diffInYears(now()) . ' tahun';
    }

    public function getNamaJenisKelaminAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getAlamatLengkapFormatAttribute(): string
    {
        return "{$this->alamat_lengkap}, RT {$this->rt}/RW {$this->rw}, {$this->kelurahan}, {$this->kecamatan}, {$this->kota_kabupaten}, {$this->provinsi} {$this->kode_pos}";
    }
}
