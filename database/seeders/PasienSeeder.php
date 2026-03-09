<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PasienSeeder extends Seeder
{
    private $kotaKabupatens = [
        'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Barat', 'Jakarta Utara', 'Jakarta Pusat',
        'Bandung', 'Bekasi', 'Bogor', 'Depok', 'Tangerang',
        'Surabaya', 'Malang', 'Semarang', 'Yogyakarta', 'Solo',
        'Medan', 'Palembang', 'Makassar', 'Kendari', 'Manado',
        'Denpasar', 'Mataram', 'Kupang', 'Jayapura', 'Sorong',
        'Pontianak', 'Samarinda', 'Balikpapan', 'Banjarmasin', 'Pekanbaru',
        'Jambi', 'Lampung', 'Bandar Lampung', 'Batam', 'Padang'
    ];

    private $provinsis = [
        'DKI Jakarta', 'Jawa Barat', 'Jawa Timur', 'Jawa Tengah', 'DI Yogyakarta',
        'Sumatera Utara', 'Sumatera Selatan', 'Sulawesi Selatan', 'Sulawesi Utara',
        'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Papua', 'Papua Barat',
        'Kalimantan Barat', 'Kalimantan Timur', 'Kalimantan Selatan', 'Riau',
        'Jambi', 'Lampung', 'Kepulauan Riau', 'Sumatera Barat'
    ];

    private $agamas = ['islam', 'kristen', 'katolik', 'hindu', 'buddha'];
    private $statusNikahs = ['belum_menikah', 'menikah', 'cerai'];
    private $jenisJaminans = ['umum', 'bpjs_kesehatan', 'bpjs_ketenagakerjaan', 'asuransi_swasta', 'jamkesda'];
    private $pendidikans = ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'];
    private $pekerjaans = ['Pegawai Negeri', 'Pegawai Swasta', 'Wiraswasta', 'Petani', 'Nelayan', 'Guru', 'Dokter', 'Perawat', 'Mahasiswa', 'Pelajar', 'Ibu Rumah Tangga', 'Pensiunan', 'Sopir', 'Tukang', 'Pedagang'];

    private $namaDepans = [
        'Ahmad', 'Budi', 'Dedi', 'Eko', 'Fandi', 'Galih', 'Hadi', 'Indra', 'Joko', 'Kurnia',
        'Lukman', 'Mochamad', 'Nico', 'Oki', 'Putra', 'Rizki', 'Sandi', 'Toni', 'Udin', 'Vino',
        'Wawan', 'Yusuf', 'Zainal', 'Adi', 'Bimo', 'Candra', 'Doni', 'Eri', 'Feri', 'Gede',
        'Heri', 'Ian', 'Jefri', 'Kiki', 'Lio', 'Maman', 'Naufal', 'Ozzy', 'Panji', 'Randy',
        'Sopian', 'Tedi', 'Umar', 'Vicky', 'Wahyu', 'Xavier', 'Yogi', 'Zaky',
        'Ani', 'Bella', 'Citra', 'Dewi', 'Eka', 'Fitri', 'Gita', 'Hana', 'Ika', 'Jasmine',
        'Kartika', 'Lina', 'Maya', 'Nisa', 'Octavia', 'Puspita', 'Qori', 'Rina', 'Sari', 'Tika',
        'Ulfa', 'Vina', 'Wati', 'Yuni', 'Zahra', 'Ayu', 'Brillian', 'Cahyani', 'Diah', 'Evi',
        'Farah', 'Gabriella', 'Hesti', 'Intan', 'Julaeha', 'Kamila', 'Laras', 'Mega', 'Nadia', 'Olivia'
    ];

    private $namaBelakangs = [
        'Santoso', 'Susanto', 'Saputra', 'Siregar', 'Simanjuntak', 'Sitompul', 'Sihaloho', 'Saragih',
        'Nugroho', 'Pratama', 'Permana', 'Putra', 'Purnama', 'Rahman', 'Rahim', 'Raja', 'Ramadhan',
        'Setiawan', 'Setyawan', 'Saputra', 'Sugiarto', 'Suwarno', 'Syamsuddin', 'Tanjung', 'Taufik',
        'Utomo', 'Wibowo', 'Wijaya', 'Wira', 'Yusuf', 'Zaelani',
        'Aprilianti', 'Astuti', 'Audina', 'Azizah', 'Budianti', 'Cahyani', 'Damanik', 'Farida', 'Gustriani',
        'Halim', 'Indrasari', 'Juliati', 'Kartika', 'Kusuma', 'Lestari', 'Maulina', 'Mulyani', 'Ningsih',
        'Oktaviani', 'Purnamasari', 'Rahayu', 'Rahma', 'Safitri', 'Sari', 'Sitinjak', 'Suci', 'Sukma',
        'Utami', 'Wati', 'Yulianti', 'Zahra', 'Aulia', 'Budiarti', 'Dewi', 'Fitriana'
    ];

    private $kelurahans = [
        'Cempaka Putih', 'Menteng', 'Tanah Abang', 'Senen', 'Cikini',
        'Kebon Jeruk', 'Palmerah', 'Grogol', 'Taman Sari', 'Maphar',
        'Jagakarsa', 'Tebet', 'Mampang Prapatan', 'Pancoran', 'Pesanggrahan',
        'Duren Sawit', 'Makasar', 'Kramat Jati', 'Jatinegara', 'Matraman',
        'Cengkareng', 'Kalideres', 'Kebon Jeruk', ' Palmerah', 'Taman Sari'
    ];

    private $kecamatans = [
        'Cempaka Putih', 'Menteng', 'Tanah Abang', 'Senen', 'Cempaka Putih',
        'Kebon Jeruk', 'Palmerah', 'Grogol Petamburan', 'Taman Sari', 'Sawangan',
        'Jagakarsa', 'Tebet', 'Mampang Prapatan', 'Pancoran', 'Pesanggrahan',
        'Duren Sawit', 'Makasar', 'Kramat Jati', 'Jatinegara', 'Matraman',
        'Cengkareng', 'Kalideres', 'Kebon Jeruk', ' Palmerah', 'Taman Sari'
    ];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('pasiens')->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [];
        $startDate = Carbon::now()->subYears(5);
        $endDate = Carbon::now();

        for ($i = 1; $i <= 200; $i++) {
            $tanggalLahir = Carbon::instance($startDate->copy()->addDays(rand(0, $startDate->diffInDays($endDate))));
            $tanggalRegistrasi = Carbon::instance($endDate->copy()->subDays(rand(0, 365)));

            $namaDepan = $this->namaDepans[array_rand($this->namaDepans)];
            $namaBelakang = $this->namaBelakangs[array_rand($this->namaBelakangs)];
            $jenisKelamin = rand(0, 1) ? 'L' : 'P';

            if ($jenisKelamin === 'L') {
                $nama = $namaDepan . ' ' . $namaBelakang;
            } else {
                $nama = $namaDepan . ' ' . $namaBelakang;
            }

            $kota = $this->kotaKabupatens[array_rand($this->kotaKabupatens)];

            $data[] = [
                'no_rekam_medis' => sprintf('RM%06d', $i),
                'nik' => sprintf('3%011d', $i),
                'no_kk' => sprintf('3%011d', $i + 100000),
                'nama_lengkap' => $nama,
                'nama_panggilan' => $namaDepan,
                'jenis_kelamin' => $jenisKelamin,
                'tempat_lahir' => $kota,
                'tanggal_lahir' => $tanggalLahir->format('Y-m-d'),
                'golongan_darah' => ['A', 'B', 'AB', 'O'][rand(0, 3)],
                'agama' => $this->agamas[array_rand($this->agamas)],
                'status_pernikahan' => $this->statusNikahs[array_rand($this->statusNikahs)],
                'pendidikan' => $this->pendidikans[array_rand($this->pendidikans)],
                'pekerjaan' => $this->pekerjaans[array_rand($this->pekerjaans)],
                'nama_ibu_kandung' => $this->namaDepans[array_rand($this->namaDepans)] . ' ' . $this->namaBelakangs[array_rand($this->namaBelakangs)],
                'alamat_lengkap' => 'Jl. ' . $this->getRandomStreet() . ' No. ' . rand(1, 200),
                'rt' => sprintf('%03d', rand(1, 20)),
                'rw' => sprintf('%03d', rand(1, 20)),
                'kelurahan' => $this->kelurahans[array_rand($this->kelurahans)],
                'kecamatan' => $this->kecamatans[array_rand($this->kecamatans)],
                'kota_kabupaten' => $kota,
                'provinsi' => $this->provinsis[array_rand($this->provinsis)],
                'kode_pos' => sprintf('%05d', rand(10000, 99999)),
                'no_telp' => '(021)' . rand(1000, 9999),
                'no_hp' => '08' . rand(1, 9) . rand(100000000, 999999999),
                'jenis_jaminan' => $this->jenisJaminans[array_rand($this->jenisJaminans)],
                'no_jaminan' => rand(0, 1) ? sprintf('01%011d', $i) : null,
                'kelas_jaminan' => rand(0, 1) ? ['Kelas 1', 'Kelas 2', 'Kelas 3'][rand(0, 2)] : null,
                'nama_kontak_darurat' => $this->namaDepans[array_rand($this->namaDepans)] . ' ' . $this->namaBelakangs[array_rand($this->namaBelakangs)],
                'hubungan_kontak_darurat' => ['Suami', 'Istri', 'Ayah', 'Ibu', 'Saudara', 'Anak'][rand(0, 5)],
                'no_telp_kontak_darurat' => '08' . rand(1, 9) . rand(100000000, 999999999),
                'status_pasien' => rand(0, 10) > 1 ? 'aktif' : 'nonaktif',
                'tanggal_registrasi' => $tanggalRegistrasi->format('Y-m-d'),
                'kunjungan_terakhir' => rand(0, 1) ? $tanggalRegistrasi->copy()->addDays(rand(1, 365))->format('Y-m-d') : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($data) >= 50) {
                DB::table('pasiens')->insert($data);
                $data = [];
            }
        }

        if (count($data) > 0) {
            DB::table('pasiens')->insert($data);
        }

        $this->command->info('Berhasil seeding 200 pasien dengan data Indonesia.');
    }

    private function getRandomStreet(): string
    {
        $jalan = [
            'Merdeka', 'Diponegoro', 'Sudirman', 'Thamrin', 'Asia Afrika',
            'Dago', 'Braga', 'Pelajar', 'Ahmad Yani', 'Panjaitan',
            'Cikopo', 'Sukarno', 'Hatta', 'Soekarno', 'Workers',
            'Kemang', 'Rasuna Said', 'M.Tertiary', 'K.H. Abdullah',
            'Irian', 'Jendral Sudirman', 'Kolonel Masturi', 'Cibubur',
            'Cirendeu', 'Pamulang', 'Bintaro', 'Meruya', 'Pos pengumben'
        ];
        return $jalan[array_rand($jalan)];
    }
}
