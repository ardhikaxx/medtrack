<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['kode_unit' => 'POLI-UMUM', 'nama_unit' => 'Poli Umum', 'jenis_unit' => 'poli', 'lantai' => '1', 'gedung' => 'Gedung A'],
            ['kode_unit' => 'POLI-KIA', 'nama_unit' => 'Poli KIA, KB dan Imunisasi', 'jenis_unit' => 'poli', 'lantai' => '1', 'gedung' => 'Gedung A'],
            ['kode_unit' => 'POLI-GIGI', 'nama_unit' => 'Poli Gigi dan Mulut', 'jenis_unit' => 'poli', 'lantai' => '1', 'gedung' => 'Gedung A'],
            ['kode_unit' => 'POLI-PENY-DALAM', 'nama_unit' => 'Poli Penyakit Dalam', 'jenis_unit' => 'poli', 'lantai' => '2', 'gedung' => 'Gedung A'],
            ['kode_unit' => 'POLI-BEDAH', 'nama_unit' => 'Poli Bedah', 'jenis_unit' => 'poli', 'lantai' => '2', 'gedung' => 'Gedung A'],
            ['kode_unit' => 'POLI-OBG', 'nama_unit' => 'Poli Obstetri & Ginekologi', 'jenis_unit' => 'poli', 'lantai' => '2', 'gedung' => 'Gedung B'],
            ['kode_unit' => 'POLI-ANAK', 'nama_unit' => 'Poli Anak dan Tumbuh Kembang', 'jenis_unit' => 'poli', 'lantai' => '1', 'gedung' => 'Gedung B'],
            ['kode_unit' => 'POLI-MATA', 'nama_unit' => 'Poli Mata', 'jenis_unit' => 'poli', 'lantai' => '2', 'gedung' => 'Gedung B'],
            ['kode_unit' => 'UGD', 'nama_unit' => 'Unit Gawat Darurat', 'jenis_unit' => 'ugd', 'lantai' => '1', 'gedung' => 'Gedung C'],
            ['kode_unit' => 'RAWAT-INAP', 'nama_unit' => 'Rawat Inap', 'jenis_unit' => 'rawat_inap', 'lantai' => '3', 'gedung' => 'Gedung A'],
            ['kode_unit' => 'LAB', 'nama_unit' => 'Laboratorium', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung C'],
            ['kode_unit' => 'RADIOLOGI', 'nama_unit' => 'Radiologi dan Pencitraan', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung C'],
            ['kode_unit' => 'FARMASI', 'nama_unit' => 'Instalasi Farmasi', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung A'],
            ['kode_unit' => 'PENDAFTARAN', 'nama_unit' => 'Ruang Pendaftaran', 'jenis_unit' => 'administrasi', 'lantai' => '1', 'gedung' => 'Gedung A'],
            ['kode_unit' => 'REKAM-MEDIS', 'nama_unit' => 'Unit Rekam Medis dan Informasi', 'jenis_unit' => 'administrasi', 'lantai' => '1', 'gedung' => 'Gedung A'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
