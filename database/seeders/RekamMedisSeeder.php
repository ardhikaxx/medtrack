<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class RekamMedisSeeder extends Seeder
{
    private $jenisKunjungans = ['rawat_jalan', 'rawat_inap', 'ugd', 'konsultasi'];
    private $statusDokumens = ['tersedia', 'dipinjam', 'dalam_proses', 'tersedia'];
    private $kondisiDokumens = ['baik', 'cukup', 'baik', 'baik'];
    private $diagnosas = [
        'Demam Berdarah Dengue', 'ISPA', 'Diare', 'Malaria', 'Tifoid',
        'Hipertensi', 'Diabetes Melitus', 'Asma Bronchiale', 'Pneumonia', 'Bronchitis',
        'Gastroenteritis', 'Infeksi Saluran Kemih', 'Dermatitis', 'Konjungtivitis', 'Otitis Media',
        'Faringitis', 'Tonsilitis', 'Arthritis', 'Osteoarthritis', 'Rematik',
        'Migrain', 'Sakit Kepala Tegang', 'Insomnia', 'Depresi', 'Gangguan Kecemasan',
        'Dispepsia', 'GERD', 'Gastritis', 'Hepatitis', 'Sirosis Hati',
        'Gagal Jantung', 'Aritmia', 'Angina Pectoris', 'Infark Miokard', 'Kardiomiopati',
        'Anemia', 'Leukemia', 'Limfoma', 'Trombositopenia', 'Hemofilia',
        'Glaukoma', 'Katarak', 'Retinopati', 'Strabismus', 'Miopi',
        ' Acne Vulgaris', 'Psoriasis', 'Eksim', 'Kandidiasis', 'Herpes Zoster'
    ];

    private $icd10s = [
        'A90', 'A91', 'J06.9', 'K52.9', 'B50', 'A01.0',
        'I10', 'E11', 'J45', 'J18', 'J44',
        'K29.7', 'N39.0', 'L30.9', 'H10', 'H66.9',
        'J02', 'J35.0', 'M06.9', 'M17', 'M79',
        'G43', 'G44.1', 'F51.0', 'F32', 'F41',
        'K30', 'K21.0', 'K29.5', 'B18', 'K74.6',
        'I50', 'I49', 'I20', 'I21', 'I42',
        'D64', 'C91', 'C88', 'D69', 'D68',
        'H40', 'H25', 'H36', 'H49', 'H52',
        'L70.0', 'L40', 'L30.9', 'B37.2', 'B02'
    ];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('rekam_medis')->truncate();
        Schema::enableForeignKeyConstraints();

        $pasiens = DB::table('pasiens')->pluck('id', 'no_rekam_medis');
        $units = DB::table('units')->pluck('id');
        $users = DB::table('users')->pluck('id');
        $ruangPenyimpanans = DB::table('ruang_penyimpanans')->pluck('id');

        if ($pasiens->isEmpty() || $units->isEmpty() || $users->isEmpty()) {
            $this->command->error('Data pasien, units, atau users kosong. Jalankan seeder lain terlebih dahulu.');
            return;
        }

        $data = [];
        $startDate = Carbon::now()->subYears(3);
        $endDate = Carbon::now();

        $kodeCounter = 1;

        foreach ($pasiens as $noRm => $pasienId) {
            $jumlahRekamMedis = rand(1, 5);

            for ($i = 0; $i < $jumlahRekamMedis; $i++) {
                $tanggalKunjungan = Carbon::instance($startDate->copy()->addDays(rand(0, $startDate->diffInDays($endDate))));
                $diagnosaIndex = array_rand($this->diagnosas);

                $data[] = [
                    'pasien_id' => $pasienId,
                    'kode_dokumen' => sprintf('DOC%06d', $kodeCounter++),
                    'no_rekam_medis' => $noRm,
                    'tanggal_kunjungan' => $tanggalKunjungan->format('Y-m-d'),
                    'poli_id' => $units->random(),
                    'dokter_id' => $users->random(),
                    'jenis_kunjungan' => $this->jenisKunjungans[array_rand($this->jenisKunjungans)],
                    'status_dokumen' => $this->statusDokumens[array_rand($this->statusDokumens)],
                    'ruang_penyimpanan_id' => $ruangPenyimpanans->isNotEmpty() ? $ruangPenyimpanans->random() : null,
                    'rak' => 'Rak-' . chr(65 + rand(0, 10)),
                    'laci' => 'Laci-' . rand(1, 20),
                    'map_folder' => 'Map-' . rand(1, 100),
                    'jumlah_halaman' => rand(5, 50),
                    'ketebalan_cm' => rand(1, 10),
                    'tanggal_retensi' => $tanggalKunjungan->copy()->addYears(25)->format('Y-m-d'),
                    'kondisi_dokumen' => $this->kondisiDokumens[array_rand($this->kondisiDokumens)],
                    'diagnosa_utama' => $this->diagnosas[$diagnosaIndex],
                    'kode_icd10' => $this->icd10s[$diagnosaIndex],
                    'catatan_dokumen' => rand(0, 1) ? 'Dokumen rekam medis pasien dengan keluhan ' . strtolower($this->diagnosas[$diagnosaIndex]) : null,
                    'dibuat_oleh' => $users->random(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($data) >= 50) {
                    DB::table('rekam_medis')->insert($data);
                    $data = [];
                }
            }
        }

        if (count($data) > 0) {
            DB::table('rekam_medis')->insert($data);
        }

        $totalRm = DB::table('rekam_medis')->count();
        $this->command->info("Berhasil seeding $totalRm rekam medis.");
    }
}
