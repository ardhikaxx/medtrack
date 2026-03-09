<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roleAdmin = Role::where('nama_role', 'admin')->first();
        $roleDirektur = Role::where('nama_role', 'direktur')->first();
        $roleKepalaRm = Role::where('nama_role', 'kepala_rekam_medis')->first();
        $rolePetugasArsip = Role::where('nama_role', 'petugas_arsip')->first();
        $roleTkInternal = Role::where('nama_role', 'tenaga_kesehatan_internal')->first();
        $roleTkEksternal = Role::where('nama_role', 'tenaga_kesehatan_eksternal')->first();

        $unitRekamMedis = Unit::where('kode_unit', 'REKAM-MEDIS')->first();
        $unitPoliUmum = Unit::where('kode_unit', 'POLI-UMUM')->first();
        $unitPoliObg = Unit::where('kode_unit', 'POLI-OBG')->first();

        $users = [
            [
                'nama_lengkap' => 'Admin Sistem',
                'nik' => '3578050101000001',
                'nip' => '197001011990011001',
                'email' => 'admin@husada-clinic.id',
                'username' => 'admin',
                'role_id' => $roleAdmin->id,
                'unit_id' => $unitRekamMedis->id,
                'jabatan' => 'Administrator Sistem',
                'jenis_pengguna' => 'internal',
                'is_active' => true,
            ],
            [
                'nama_lengkap' => 'dr. Hj. Nur Indarti, M.Kes',
                'nik' => '3578054508650003',
                'nip' => '196508451990032001',
                'email' => 'direktur@husada-clinic.id',
                'username' => 'direktur',
                'role_id' => $roleDirektur->id,
                'jabatan' => 'Direktur Klinik',
                'jenis_pengguna' => 'internal',
                'is_active' => true,
            ],
            [
                'nama_lengkap' => 'Dewi Ratnasari, A.Md.RMIK',
                'nik' => '3578026210890012',
                'nip' => '198910222015032002',
                'email' => 'kepala.rm@husada-clinic.id',
                'username' => 'kepala_rm',
                'role_id' => $roleKepalaRm->id,
                'unit_id' => $unitRekamMedis->id,
                'jabatan' => 'Kepala Rekam Medis',
                'jenis_pengguna' => 'internal',
                'is_active' => true,
            ],
            [
                'nama_lengkap' => 'Agus Setiawan',
                'nik' => '3578031501930024',
                'nip' => '199301152016031003',
                'email' => 'petugas1.rm@husada-clinic.id',
                'username' => 'petugas_arsip1',
                'role_id' => $rolePetugasArsip->id,
                'unit_id' => $unitRekamMedis->id,
                'jabatan' => 'Petugas Arsip',
                'jenis_pengguna' => 'internal',
                'is_active' => true,
            ],
            [
                'nama_lengkap' => 'Siti Rahayu, A.Md.Keb',
                'nik' => '3578044507930028',
                'nip' => '199307152016032004',
                'email' => 'petugas2.rm@husada-clinic.id',
                'username' => 'petugas_arsip2',
                'role_id' => $rolePetugasArsip->id,
                'unit_id' => $unitRekamMedis->id,
                'jabatan' => 'Petugas Arsip',
                'jenis_pengguna' => 'internal',
                'is_active' => true,
            ],
            [
                'nama_lengkap' => 'dr. Budi Santoso, Sp.PD',
                'nik' => '3578052807780008',
                'nip' => '197807282005011002',
                'email' => 'dr.budi@husada-clinic.id',
                'username' => 'dr_budi',
                'role_id' => $roleTkInternal->id,
                'unit_id' => $unitPoliUmum->id,
                'jabatan' => 'Dokter Spesialis Penyakit Dalam',
                'spesialisasi' => 'Penyakit Dalam',
                'str_number' => 'STR-1234/PD/2023',
                'jenis_pengguna' => 'internal',
                'is_active' => true,
            ],
            [
                'nama_lengkap' => 'dr. Siti Aminah, Sp.OG',
                'nik' => '3578046905850015',
                'nip' => '198505292010022004',
                'email' => 'dr.siti@husada-clinic.id',
                'username' => 'dr_siti',
                'role_id' => $roleTkInternal->id,
                'unit_id' => $unitPoliObg->id,
                'jabatan' => 'Dokter Spesialis Obstetri & Ginekologi',
                'spesialisasi' => 'Obstetri dan Ginekologi',
                'str_number' => 'STR-5678/OG/2023',
                'jenis_pengguna' => 'internal',
                'is_active' => true,
            ],
            [
                'nama_lengkap' => 'Ns. Ratna Dewi, S.Kep',
                'nik' => '3578056212920031',
                'nip' => '199212222016032005',
                'email' => 'ratna.ns@husada-clinic.id',
                'username' => 'ns_ratna',
                'role_id' => $roleTkInternal->id,
                'unit_id' => $unitPoliUmum->id,
                'jabatan' => 'Perawat Ruang Poli Umum',
                'jenis_pengguna' => 'internal',
                'is_active' => true,
            ],
            [
                'nama_lengkap' => 'dr. Ahmad Fauzi, M.Kes',
                'nik' => '3578071505800012',
                'email' => 'ahmad.fauzi@fkub.ac.id',
                'username' => 'dr_ahmad_ext',
                'role_id' => $roleTkEksternal->id,
                'jabatan' => 'Peneliti',
                'institusi_asal' => 'Fakultas Kedokteran Universitas Brawijaya',
                'str_number' => 'STR-9012/UMUM/2022',
                'jenis_pengguna' => 'eksternal',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                ...$userData,
                'password' => Hash::make('password123'),
            ]);
        }
    }
}
