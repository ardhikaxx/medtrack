<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nama_role' => 'admin', 'label' => 'Administrator Sistem', 'deskripsi' => 'Full access semua fitur'],
            ['nama_role' => 'direktur', 'label' => 'Direktur / Pimpinan', 'deskripsi' => 'Approve peminjaman eksternal, laporan eksekutif'],
            ['nama_role' => 'kepala_rekam_medis', 'label' => 'Kepala Rekam Medis', 'deskripsi' => 'Approve peminjaman internal & eksternal, manajemen dokumen'],
            ['nama_role' => 'petugas_arsip', 'label' => 'Petugas Arsip Rekam Medis', 'deskripsi' => 'Proses peminjaman & pengembalian yang sudah disetujui'],
            ['nama_role' => 'tenaga_kesehatan_internal', 'label' => 'Tenaga Kesehatan Internal', 'deskripsi' => 'Buat permohonan peminjaman internal'],
            ['nama_role' => 'tenaga_kesehatan_eksternal', 'label' => 'Tenaga Kesehatan Eksternal', 'deskripsi' => 'Buat permohonan peminjaman eksternal (butuh surat)'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $permissions = [
            ['nama_permission' => 'pasien.view', 'label' => 'Lihat Data Pasien', 'modul' => 'pasien'],
            ['nama_permission' => 'pasien.create', 'label' => 'Tambah Data Pasien', 'modul' => 'pasien'],
            ['nama_permission' => 'pasien.edit', 'label' => 'Edit Data Pasien', 'modul' => 'pasien'],
            ['nama_permission' => 'pasien.delete', 'label' => 'Hapus Data Pasien', 'modul' => 'pasien'],
            ['nama_permission' => 'pasien.export', 'label' => 'Export Data Pasien', 'modul' => 'pasien'],

            ['nama_permission' => 'rekam_medis.view', 'label' => 'Lihat Rekam Medis', 'modul' => 'rekam_medis'],
            ['nama_permission' => 'rekam_medis.create', 'label' => 'Tambah Rekam Medis', 'modul' => 'rekam_medis'],
            ['nama_permission' => 'rekam_medis.edit', 'label' => 'Edit Rekam Medis', 'modul' => 'rekam_medis'],
            ['nama_permission' => 'rekam_medis.delete', 'label' => 'Hapus Rekam Medis', 'modul' => 'rekam_medis'],
            ['nama_permission' => 'rekam_medis.view_detail', 'label' => 'Lihat Detail Rekam Medis', 'modul' => 'rekam_medis'],
            ['nama_permission' => 'rekam_medis.manage_storage', 'label' => 'Kelola Lokasi Penyimpanan', 'modul' => 'rekam_medis'],

            ['nama_permission' => 'peminjaman.view', 'label' => 'Lihat Daftar Peminjaman', 'modul' => 'peminjaman'],
            ['nama_permission' => 'peminjaman.create', 'label' => 'Buat Permohonan Peminjaman', 'modul' => 'peminjaman'],
            ['nama_permission' => 'peminjaman.approve', 'label' => 'Setujui/Tolak Peminjaman', 'modul' => 'peminjaman'],
            ['nama_permission' => 'peminjaman.process', 'label' => 'Proses Pengeluaran Dokumen', 'modul' => 'peminjaman'],
            ['nama_permission' => 'peminjaman.view_all', 'label' => 'Lihat Semua Peminjaman', 'modul' => 'peminjaman'],
            ['nama_permission' => 'peminjaman.cancel', 'label' => 'Batalkan Peminjaman', 'modul' => 'peminjaman'],

            ['nama_permission' => 'pengembalian.view', 'label' => 'Lihat Pengembalian', 'modul' => 'pengembalian'],
            ['nama_permission' => 'pengembalian.process', 'label' => 'Proses Pengembalian', 'modul' => 'pengembalian'],

            ['nama_permission' => 'laporan.view', 'label' => 'Lihat Laporan', 'modul' => 'laporan'],
            ['nama_permission' => 'laporan.export', 'label' => 'Export Laporan', 'modul' => 'laporan'],
            ['nama_permission' => 'laporan.statistik', 'label' => 'Lihat Statistik', 'modul' => 'laporan'],

            ['nama_permission' => 'pengguna.view', 'label' => 'Lihat Data Pengguna', 'modul' => 'pengguna'],
            ['nama_permission' => 'pengguna.create', 'label' => 'Tambah Pengguna', 'modul' => 'pengguna'],
            ['nama_permission' => 'pengguna.edit', 'label' => 'Edit Pengguna', 'modul' => 'pengguna'],
            ['nama_permission' => 'pengguna.delete', 'label' => 'Hapus Pengguna', 'modul' => 'pengguna'],
            ['nama_permission' => 'pengguna.manage_role', 'label' => 'Kelola Role', 'modul' => 'pengguna'],

            ['nama_permission' => 'unit.view', 'label' => 'Lihat Unit', 'modul' => 'unit'],
            ['nama_permission' => 'unit.manage', 'label' => 'Kelola Unit', 'modul' => 'unit'],

            ['nama_permission' => 'setting.view', 'label' => 'Lihat Pengaturan', 'modul' => 'setting'],
            ['nama_permission' => 'setting.manage', 'label' => 'Kelola Pengaturan', 'modul' => 'setting'],

            ['nama_permission' => 'audit.view', 'label' => 'Lihat Log Aktivitas', 'modul' => 'audit'],

            ['nama_permission' => 'dashboard.view', 'label' => 'Lihat Dashboard', 'modul' => 'dashboard'],
            ['nama_permission' => 'dashboard.statistik_lengkap', 'label' => 'Lihat Statistik Lengkap', 'modul' => 'dashboard'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $adminRole = Role::where('nama_role', 'admin')->first();
        $adminRole->permissions()->attach(Permission::all()->pluck('id'));

        $direkturRole = Role::where('nama_role', 'direktur')->first();
        $direkturRole->permissions()->attach(Permission::whereIn('nama_permission', [
            'dashboard.view', 'dashboard.statistik_lengkap',
            'laporan.view', 'laporan.export', 'laporan.statistik',
            'peminjaman.view', 'peminjaman.view_all', 'peminjaman.approve',
            'pengembalian.view',
            'pasien.view', 'rekam_medis.view',
        ])->pluck('id'));

        $kepalaRmRole = Role::where('nama_role', 'kepala_rekam_medis')->first();
        $kepalaRmRole->permissions()->attach(Permission::whereIn('nama_permission', [
            'dashboard.view', 'dashboard.statistik_lengkap',
            'laporan.view', 'laporan.export', 'laporan.statistik',
            'peminjaman.view', 'peminjaman.view_all', 'peminjaman.approve', 'peminjaman.cancel',
            'pengembalian.view', 'pengembalian.process',
            'pasien.view', 'pasien.create', 'pasien.edit', 'pasien.export',
            'rekam_medis.view', 'rekam_medis.create', 'rekam_medis.edit', 'rekam_medis.manage_storage',
            'unit.view',
            'audit.view',
        ])->pluck('id'));

        $petugasArsipRole = Role::where('nama_role', 'petugas_arsip')->first();
        $petugasArsipRole->permissions()->attach(Permission::whereIn('nama_permission', [
            'dashboard.view',
            'peminjaman.view', 'peminjaman.view_all', 'peminjaman.process',
            'pengembalian.view', 'pengembalian.process',
            'pasien.view', 'rekam_medis.view', 'rekam_medis.view_detail',
        ])->pluck('id'));

        $tkInternalRole = Role::where('nama_role', 'tenaga_kesehatan_internal')->first();
        $tkInternalRole->permissions()->attach(Permission::whereIn('nama_permission', [
            'dashboard.view',
            'peminjaman.view', 'peminjaman.create',
            'pasien.view', 'rekam_medis.view',
        ])->pluck('id'));

        $tkEksternalRole = Role::where('nama_role', 'tenaga_kesehatan_eksternal')->first();
        $tkEksternalRole->permissions()->attach(Permission::whereIn('nama_permission', [
            'dashboard.view',
            'peminjaman.view', 'peminjaman.create',
            'pasien.view', 'rekam_medis.view',
        ])->pluck('id'));
    }
}
