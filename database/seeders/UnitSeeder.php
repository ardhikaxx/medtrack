<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            // === POLI / KLINIK SPESIALIS ===
            ['kode_unit' => 'POLI-UMUM', 'nama_unit' => 'Poli Umum', 'jenis_unit' => 'poli', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234567', 'keterangan' => 'Pelayanan Poli Umum'],
            ['kode_unit' => 'POLI-KIA', 'nama_unit' => 'Poli KIA, KB dan Imunisasi', 'jenis_unit' => 'poli', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234568', 'keterangan' => 'Kesehatan Ibu dan Anak'],
            ['kode_unit' => 'POLI-GIGI', 'nama_unit' => 'Poli Gigi dan Mulut', 'jenis_unit' => 'poli', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234569', 'keterangan' => 'Spesialis Gigi'],
            ['kode_unit' => 'POLI-THT', 'nama_unit' => 'Poli THT', 'jenis_unit' => 'poli', 'lantai' => '2', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234570', 'keterangan' => 'Spesialis THT'],
            ['kode_unit' => 'POLI-KULIT', 'nama_unit' => 'Poli Kulit dan Kelamin', 'jenis_unit' => 'poli', 'lantai' => '2', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234571', 'keterangan' => 'Spesialis Kulit'],
            ['kode_unit' => 'POLI-PD', 'nama_unit' => 'Poli Penyakit Dalam', 'jenis_unit' => 'poli', 'lantai' => '2', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234572', 'keterangan' => 'Spesialis Penyakit Dalam'],
            ['kode_unit' => 'POLI-JANTUNG', 'nama_unit' => 'Poli Jantung', 'jenis_unit' => 'poli', 'lantai' => '2', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234573', 'keterangan' => 'Spesialis Jantung'],
            ['kode_unit' => 'POLI-PARU', 'nama_unit' => 'Poli Paru', 'jenis_unit' => 'poli', 'lantai' => '2', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234574', 'keterangan' => 'Spesialis Pulmonologi'],
            ['kode_unit' => 'POLI-SARAF', 'nama_unit' => 'Poli Saraf', 'jenis_unit' => 'poli', 'lantai' => '3', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234575', 'keterangan' => 'Spesialis Neurologi'],
            ['kode_unit' => 'POLI-OBG', 'nama_unit' => 'Poli Kandungan', 'jenis_unit' => 'poli', 'lantai' => '2', 'gedung' => 'Gedung Bersama', 'no_telp_unit' => '021-1234576', 'keterangan' => 'Spesialis Kandungan'],
            ['kode_unit' => 'POLI-ANAK', 'nama_unit' => 'Poli Anak', 'jenis_unit' => 'poli', 'lantai' => '1', 'gedung' => 'Gedung Bersama', 'no_telp_unit' => '021-1234577', 'keterangan' => 'Spesialis Anak'],
            ['kode_unit' => 'POLI-MATA', 'nama_unit' => 'Poli Mata', 'jenis_unit' => 'poli', 'lantai' => '2', 'gedung' => 'Gedung Bersama', 'no_telp_unit' => '021-1234578', 'keterangan' => 'Spesialis Mata'],
            ['kode_unit' => 'POLI-BEDAH', 'nama_unit' => 'Poli Bedah', 'jenis_unit' => 'poli', 'lantai' => '3', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234579', 'keterangan' => 'Spesialis Bedah'],
            ['kode_unit' => 'POLI-ORTHO', 'nama_unit' => 'Poli Ortopedi', 'jenis_unit' => 'poli', 'lantai' => '3', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234580', 'keterangan' => 'Spesialis Ortopedi'],
            ['kode_unit' => 'POLI-JIWA', 'nama_unit' => 'Poli Kesehatan Jiwa', 'jenis_unit' => 'poli', 'lantai' => '3', 'gedung' => 'Gedung Bersama', 'no_telp_unit' => '021-1234581', 'keterangan' => 'Konsultasi Psikologi'],
            ['kode_unit' => 'POLI-GIZI', 'nama_unit' => 'Poli Gizi', 'jenis_unit' => 'poli', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234582', 'keterangan' => 'Konsultasi Gizi'],
            ['kode_unit' => 'POLI-GASTRO', 'nama_unit' => 'Poli Pencernaan', 'jenis_unit' => 'poli', 'lantai' => '3', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234583', 'keterangan' => 'Spesialis Gastro'],
            ['kode_unit' => 'POLI-DIABET', 'nama_unit' => 'Poli Diabetes', 'jenis_unit' => 'poli', 'lantai' => '3', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234584', 'keterangan' => 'Klinik Diabetes'],
            ['kode_unit' => 'POLI-GINJAL', 'nama_unit' => 'Poli Ginjal', 'jenis_unit' => 'poli', 'lantai' => '3', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234585', 'keterangan' => 'Spesialis Ginjal'],

            // === UNIT GAWAT DARURAT ===
            ['kode_unit' => 'UGD', 'nama_unit' => 'Unit Gawat Darurat', 'jenis_unit' => 'ugd', 'lantai' => '1', 'gedung' => 'Gedung Darurat', 'no_telp_unit' => '021-119', 'keterangan' => 'Layanan 24 Jam'],
            ['kode_unit' => 'IGD', 'nama_unit' => 'Instalasi Gawat Darurat', 'jenis_unit' => 'ugd', 'lantai' => '1', 'gedung' => 'Gedung Darurat', 'no_telp_unit' => '021-119', 'keterangan' => 'IGD 24 Jam'],
            ['kode_unit' => 'ICU', 'nama_unit' => 'Intensive Care Unit', 'jenis_unit' => 'ugd', 'lantai' => '2', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234586', 'keterangan' => 'Unit Perawatan Intensif'],
            ['kode_unit' => 'NICU', 'nama_unit' => 'NICU', 'jenis_unit' => 'ugd', 'lantai' => '2', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234587', 'keterangan' => 'Perawatan Intensif Bayi'],
            ['kode_unit' => 'PICU', 'nama_unit' => 'PICU', 'jenis_unit' => 'ugd', 'lantai' => '2', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234588', 'keterangan' => 'Perawatan Intensif Anak'],
            ['kode_unit' => 'KAMAR-OK', 'nama_unit' => 'Kamar Operasi', 'jenis_unit' => 'ugd', 'lantai' => '3', 'gedung' => 'Gedung Operasi', 'no_telp_unit' => '021-1234589', 'keterangan' => 'Ruang Bedah/Operasi'],
            ['kode_unit' => 'VK', 'nama_unit' => 'Ruang Persalinan', 'jenis_unit' => 'ugd', 'lantai' => '2', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234590', 'keterangan' => 'Ruang VK/Kelahiran'],

            // === RAWAT INAP ===
            ['kode_unit' => 'RAWAT-INAP', 'nama_unit' => 'Rawat Inap Umum', 'jenis_unit' => 'rawat_inap', 'lantai' => '4', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234591', 'keterangan' => 'Rawat Inap Kelas Standar'],
            ['kode_unit' => 'RAWAT-VIP', 'nama_unit' => 'Rawat Inap VIP', 'jenis_unit' => 'rawat_inap', 'lantai' => '5', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234592', 'keterangan' => 'Rawat Inap Kamar VIP'],
            ['kode_unit' => 'RAWAT-VVIP', 'nama_unit' => 'Rawat Inap VVIP', 'jenis_unit' => 'rawat_inap', 'lantai' => '5', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234593', 'keterangan' => 'Rawat Inap Kamar VVIP'],
            ['kode_unit' => 'RAWAT-KLS1', 'nama_unit' => 'Rawat Inap Kelas 1', 'jenis_unit' => 'rawat_inap', 'lantai' => '4', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234594', 'keterangan' => 'Kamar Kelas 1'],
            ['kode_unit' => 'RAWAT-KLS2', 'nama_unit' => 'Rawat Inap Kelas 2', 'jenis_unit' => 'rawat_inap', 'lantai' => '4', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234595', 'keterangan' => 'Kamar Kelas 2'],
            ['kode_unit' => 'RAWAT-KLS3', 'nama_unit' => 'Rawat Inap Kelas 3', 'jenis_unit' => 'rawat_inap', 'lantai' => '4', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234596', 'keterangan' => 'Kamar Kelas 3'],
            ['kode_unit' => 'RUANG-ISO', 'nama_unit' => 'Ruang Isolasi', 'jenis_unit' => 'rawat_inap', 'lantai' => '4', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234597', 'keterangan' => 'Ruang Isolasi Pasien'],
            ['kode_unit' => 'NURSERY', 'nama_unit' => 'Ruang Nursery', 'jenis_unit' => 'rawat_inap', 'lantai' => '2', 'gedung' => 'Gedung Rawat Inap', 'no_telp_unit' => '021-1234598', 'keterangan' => 'Perawatan Bayi'],

            // === UNIT PENUNJANG MEDIS ===
            ['kode_unit' => 'LAB', 'nama_unit' => 'Laboratorium Klinik', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234599', 'keterangan' => 'Pemeriksaan Lab'],
            ['kode_unit' => 'LAB-PAT', 'nama_unit' => 'Lab Patologi Anatomi', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234600', 'keterangan' => 'Patologi Anatomi'],
            ['kode_unit' => 'RADIOLOGI', 'nama_unit' => 'Instalasi Radiologi', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234601', 'keterangan' => 'Rontgen, USG, CT'],
            ['kode_unit' => 'USG', 'nama_unit' => 'Ruang USG', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234602', 'keterangan' => 'Pemeriksaan USG'],
            ['kode_unit' => 'CT-SCAN', 'nama_unit' => 'Instalasi CT-Scan', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234603', 'keterangan' => 'Pemeriksaan CT-Scan'],
            ['kode_unit' => 'MRI', 'nama_unit' => 'Instalasi MRI', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234604', 'keterangan' => 'Pemeriksaan MRI'],
            ['kode_unit' => 'FARMASI', 'nama_unit' => 'Instalasi Farmasi', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234605', 'keterangan' => 'Pelayanan Farmasi'],
            ['kode_unit' => 'GIZI', 'nama_unit' => 'Instalasi Gizi', 'jenis_unit' => 'penunjang', 'lantai' => '2', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234606', 'keterangan' => 'Pelayanan Gizi'],
            ['kode_unit' => 'BANK-DARAH', 'nama_unit' => 'Unit Donor Darah', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234607', 'keterangan' => 'Bank Darah'],
            ['kode_unit' => 'FISIO', 'nama_unit' => 'Instalasi Fisioterapi', 'jenis_unit' => 'penunjang', 'lantai' => '2', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234608', 'keterangan' => 'Pelayanan Fisioterapi'],
            ['kode_unit' => 'OKUPASI', 'nama_unit' => 'Okupasi Terapi', 'jenis_unit' => 'penunjang', 'lantai' => '2', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234609', 'keterangan' => 'Terapi Okupasi'],
            ['kode_unit' => 'TERAPI-WC', 'nama_unit' => 'Terapi Wicara', 'jenis_unit' => 'penunjang', 'lantai' => '2', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234610', 'keterangan' => 'Terapi Wicara'],
            ['kode_unit' => 'HEMODIAL', 'nama_unit' => 'Unit Hemodialisa', 'jenis_unit' => 'penunjang', 'lantai' => '3', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234611', 'keterangan' => 'Cuci Darah'],
            ['kode_unit' => 'AMBULANS', 'nama_unit' => 'Unit Ambulans', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-118', 'keterangan' => 'Layanan Ambulans'],
            ['kode_unit' => 'CSSD', 'nama_unit' => 'CSSD', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234612', 'keterangan' => 'Sterilisasi Alat'],
            ['kode_unit' => 'LAUNDRY', 'nama_unit' => 'Instalasi Laundry', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234613', 'keterangan' => 'Layanan Laundry'],
            ['kode_unit' => 'EMC', 'nama_unit' => 'Emergency Medical Comm', 'jenis_unit' => 'penunjang', 'lantai' => '1', 'gedung' => 'Gedung Darurat', 'no_telp_unit' => '021-119', 'keterangan' => 'Komunikasi Darurat'],

            // === UNIT ADMINISTRASI ===
            ['kode_unit' => 'PENDAFTARAN', 'nama_unit' => 'Ruang Pendaftaran', 'jenis_unit' => 'administrasi', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234614', 'keterangan' => 'Pendaftaran Pasien'],
            ['kode_unit' => 'REKAM-MEDIS', 'nama_unit' => 'Unit Rekam Medis', 'jenis_unit' => 'administrasi', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234615', 'keterangan' => 'Pengelolaan RM'],
            ['kode_unit' => 'KEUANGAN', 'nama_unit' => 'Bagian Keuangan', 'jenis_unit' => 'administrasi', 'lantai' => '2', 'gedung' => 'Gedung Administrasi', 'no_telp_unit' => '021-1234616', 'keterangan' => 'Pelayanan Keuangan'],
            ['kode_unit' => 'KEPERAWATAN', 'nama_unit' => 'Bidang Keperawatan', 'jenis_unit' => 'administrasi', 'lantai' => '2', 'gedung' => 'Gedung Administrasi', 'no_telp_unit' => '021-1234617', 'keterangan' => 'Manajemen Keperawatan'],
            ['kode_unit' => 'FILING-RM', 'nama_unit' => 'Ruang Filing RM', 'jenis_unit' => 'administrasi', 'lantai' => '1', 'gedung' => 'Gedung Arsip', 'no_telp_unit' => '021-1234618', 'keterangan' => 'Penyimpanan RM'],
            ['kode_unit' => 'INFORMASI', 'nama_unit' => 'Unit Informasi', 'jenis_unit' => 'administrasi', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234619', 'keterangan' => 'Layanan Informasi'],
            ['kode_unit' => 'HUMAS', 'nama_unit' => 'Hubungan Masyarakat', 'jenis_unit' => 'administrasi', 'lantai' => '2', 'gedung' => 'Gedung Administrasi', 'no_telp_unit' => '021-1234620', 'keterangan' => 'Humas dan Marketing'],
            ['kode_unit' => 'KEPEG', 'nama_unit' => 'Bagian Kepegawaian', 'jenis_unit' => 'administrasi', 'lantai' => '2', 'gedung' => 'Gedung Administrasi', 'no_telp_unit' => '021-1234621', 'keterangan' => 'Manajemen Kepegawaian'],
            ['kode_unit' => 'LOGISTIK', 'nama_unit' => 'Unit Logistik', 'jenis_unit' => 'administrasi', 'lantai' => '1', 'gedung' => 'Gedung Administrasi', 'no_telp_unit' => '021-1234622', 'keterangan' => 'Pengelolaan Logistik'],
            ['kode_unit' => 'IT', 'nama_unit' => 'Unit IT', 'jenis_unit' => 'administrasi', 'lantai' => '2', 'gedung' => 'Gedung Administrasi', 'no_telp_unit' => '021-1234623', 'keterangan' => 'IT Support'],

            // === UNIT LAINNYA ===
            ['kode_unit' => 'POLI-VCT', 'nama_unit' => 'Poli VCT HIV/AIDS', 'jenis_unit' => 'lainnya', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234624', 'keterangan' => 'Konseling HIV/AIDS'],
            ['kode_unit' => 'POLI-TB', 'nama_unit' => 'Poli TB', 'jenis_unit' => 'lainnya', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234625', 'keterangan' => 'Pelayanan TB Paru'],
            ['kode_unit' => 'POLI-LANSIA', 'nama_unit' => 'Poli Lansia', 'jenis_unit' => 'lainnya', 'lantai' => '3', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234626', 'keterangan' => 'Kesehatan Lanjut Usia'],
            ['kode_unit' => 'POLI-REHAB', 'nama_unit' => 'Poli Rehabilitasi Medik', 'jenis_unit' => 'lainnya', 'lantai' => '2', 'gedung' => 'Gedung Penunjang', 'no_telp_unit' => '021-1234627', 'keterangan' => 'Rehabilitasi Medik'],
            ['kode_unit' => 'MCU', 'nama_unit' => 'Klinik Medical Check-up', 'jenis_unit' => 'lainnya', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234628', 'keterangan' => 'Medical Check-up'],
            ['kode_unit' => 'ASESMEN', 'nama_unit' => 'Ruang Asesmen Medis', 'jenis_unit' => 'lainnya', 'lantai' => '1', 'gedung' => 'Gedung Utama', 'no_telp_unit' => '021-1234629', 'keterangan' => 'Asesmen Awal Pasien'],
        ];

        foreach ($units as $unit) {
            Unit::create([
                'kode_unit' => $unit['kode_unit'],
                'nama_unit' => $unit['nama_unit'],
                'jenis_unit' => $unit['jenis_unit'],
                'lantai' => $unit['lantai'],
                'gedung' => $unit['gedung'],
                'no_telp_unit' => $unit['no_telp_unit'] ?? null,
                'keterangan' => $unit['keterangan'] ?? null,
                'is_active' => true,
            ]);
        }
    }
}
