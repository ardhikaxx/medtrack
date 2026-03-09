<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'nama_klinik', 'value' => 'Klinik Pratama Rawat Inap Husada', 'label' => 'Nama Klinik', 'grup' => 'umum'],
            ['key' => 'alamat_klinik', 'value' => 'Jl. Pahlawan No. 10, Surabaya, Jawa Timur 60272', 'label' => 'Alamat Klinik', 'grup' => 'umum'],
            ['key' => 'no_telp_klinik', 'value' => '(031) 5678-9012', 'label' => 'No. Telepon Klinik', 'grup' => 'umum'],
            ['key' => 'email_klinik', 'value' => 'info@husada-clinic.id', 'label' => 'Email Klinik', 'grup' => 'umum'],
            ['key' => 'logo_klinik', 'value' => null, 'label' => 'Logo Klinik', 'tipe' => 'file', 'grup' => 'umum'],
            ['key' => 'max_durasi_pinjam_internal', 'value' => '3', 'label' => 'Maks. Hari Pinjam Internal', 'tipe' => 'number', 'grup' => 'peminjaman'],
            ['key' => 'max_durasi_pinjam_eksternal', 'value' => '1', 'label' => 'Maks. Hari Pinjam Eksternal', 'tipe' => 'number', 'grup' => 'peminjaman'],
            ['key' => 'max_dokumen_per_peminjaman', 'value' => '10', 'label' => 'Maks. Dokumen per Peminjaman', 'tipe' => 'number', 'grup' => 'peminjaman'],
            ['key' => 'auto_reminder_hari', 'value' => '1', 'label' => 'Kirim Reminder H- (hari)', 'tipe' => 'number', 'grup' => 'peminjaman'],
            ['key' => 'require_approval_internal', 'value' => '1', 'label' => 'Wajib Persetujuan (Internal)', 'tipe' => 'boolean', 'grup' => 'peminjaman'],
            ['key' => 'require_surat_eksternal', 'value' => '1', 'label' => 'Wajib Surat Permohonan (Eksternal)', 'tipe' => 'boolean', 'grup' => 'peminjaman'],
            ['key' => 'retensi_rawat_jalan_tahun', 'value' => '5', 'label' => 'Masa Retensi Rawat Jalan (Tahun)', 'tipe' => 'number', 'grup' => 'retensi'],
            ['key' => 'retensi_rawat_inap_tahun', 'value' => '10', 'label' => 'Masa Retensi Rawat Inap (Tahun)', 'tipe' => 'number', 'grup' => 'retensi'],
            ['key' => 'format_no_rekam_medis', 'value' => 'RM-{YEAR}-{SEQ6}', 'label' => 'Format No. Rekam Medis', 'grup' => 'format'],
            ['key' => 'format_no_peminjaman', 'value' => 'PJM-{YYYYMMDD}-{SEQ4}', 'label' => 'Format No. Peminjaman', 'grup' => 'format'],
            ['key' => 'format_no_pengembalian', 'value' => 'KBL-{YYYYMMDD}-{SEQ4}', 'label' => 'Format No. Pengembalian', 'grup' => 'format'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
