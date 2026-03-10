# MedTrack - Medical Record Borrowing & Return System

## Sistem Informasi Peminjaman dan Pengembalian Rekam Medis

**Klinik Pratama Rawat Inap Husada**

---

## 📋 Overview

MedTrack adalah sistem informasi berbasis web untuk mengelola peminjaman dan pengembalian dokumen rekam medis rawat jalan. Sistem ini dibangun sesuai SOP Peminjaman Dokumen Rekam Medis berdasarkan:
- **Permenkes No. 11 Tahun 2017** tentang Keselamatan Pasien
- **Permenkes No. 269 Tahun 2008** tentang Rekam Medis

---

## 🛠️ Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 12 |
| Database | MySQL 8.x |
| Frontend | Bootstrap 5.3, jQuery |
| Icons | Font Awesome 6 |
| Tables | DataTables 1.13+ |
| Dropdown | Select2 4.1 |
| Alerts | SweetAlert2 |
| Charts | Chart.js |
| QR Code | simple-qrcode |
| Export Excel | Maatwebsite Excel |
| Export PDF | DomPDF |

---

## 👥 Peran Pengguna (Roles)

| Role | Deskripsi |
|------|-----------|
| **Admin** | Administrator sistem dengan akses penuh ke semua fitur |
| **Direktur** | Pimpinan yang dapat menyetujui peminjaman eksternal dan melihat laporan eksekutif |
| **Kepala Rekam Medis** | Menyetujui peminjaman internal & eksternal, manajemen dokumen |
| **Petugas Arsip** | Memproses peminjaman & pengembalian yang sudah disetujui |
| **Tenaga Kesehatan Internal** | Membuat permohonan peminjaman internal |
| **Tenaga Kesehatan Eksternal** | Membuat permohonan peminjaman eksternal (memerlukan surat) |

---

## 📦 Fitur Utama

### 1. Manajemen Pasien
- Pendaftaran pasien baru
- Data lengkap pasien (identitas, alamat, kontak, jaminan)
- Riwayat peminjaman pasien
- Pencarian dengan Select2

### 2. Manajemen Rekam Medis
- Pembuatan dokumen rekam medis
- Pelacakan lokasi penyimpanan (ruang, rak, laci, map)
- Status dokumen (tersedia, dipinjam, dalam proses, hilang, rusak, dimusnahkan)
- Kode dokumen otomatis

### 3. Peminjaman Dokumen
- Pembuatan permohonan peminjaman
- Peminjaman internal dan eksternal
- Persetujuan multi-level
- Tracking status peminjaman
- Notifikasi otomatis
- Batas waktu pengembalian

### 4. Pengembalian Dokumen
- Pencatatan pengembalian
- Pemeriksaan kondisi dokumen
- Denda untuk kerusakan/kehilangan
- Laporan keterlambatan

### 5. Laporan & Statistik
- Laporan peminjaman
- Laporan pengembalian
- Laporan keterlambatan
- Statistik dokumen
- Rekap bulanan

### 6. Manajemen Pengguna
- CRUD pengguna
- Pengaturan role dan permissions
- Aktivitas logging (audit trail)

### 7. Dashboard Interaktif
- Statistik peminjaman real-time
- **Grafik Line Chart** - Peminjaman & pengembalian per bulan
- **Grafik Doughnut Chart** - Status peminjaman
- Peminjaman menunggu persetujuan
- Peminjaman terlambat
- Aktivitas terkini

### 8. Laporan & Export
- Laporan peminjaman
- Laporan pengembalian
- Laporan keterlambatan
- Statistik dokumen
- Rekap bulanan
- **Export ke Excel** (.xlsx)
- **Export ke PDF**

### 9. QR Code & Label
- **QR Code otomatis** di detail dokumen RM
- **Print Label** - Cetak label QR untuk dokumen RM
- Scan QR untuk tracking dokumen

---

## 🔄 Alur Peminjaman

```
┌─────────────────────────────────────────────────────────────┐
│                    PEMINJAMAN INTERNAL                      │
├─────────────────────────────────────────────────────────────┤
│ 1. Staff/Nakes Internal                                     │
│    → Buat Permohonan Peminjaman                            │
│    → Pilih dokumen rekam medis                             │
│    → Tentukan tanggal kembali                              │
│                              ↓ Status: Menunggu Persetujuan │
│ 2. Kepala Rekam Medis                                       │
│    → Review & Setuju / Tolak                               │
│                              ↓ Status: Disetujui            │
│ 3. Petugas Arsip                                           │
│    → Cari dokumen di penyimpanan                           │
│    → Serahkan dokumen ke peminjam                          │
│                              ↓ Status: Dipinjam            │
│ 4. Peminjam                                                │
│    → Menggunakan dokumen                                   │
│    → Kembalikan tepat waktu                                │
│                              ↓ Status: Selesai             │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                   PEMINJAMAN EKSTERNAL                      │
├─────────────────────────────────────────────────────────────┤
│ 1. Pihak Eksternal                                          │
│    → Upload surat permohonan                               │
│    → Pilih dokumen yang dibutuhkan                         │
│                              ↓ Status: Menunggu Persetujuan │
│ 2. Direktur → Disposisi ke Kepala Rekam Medis             │
│                              ↓ Status: Menunggu Persetujuan │
│ 3. Kepala Rekam Medis                                       │
│    → Review & Setuju / Tolak                               │
│                              ↓ Status: Disetujui            │
│ 4. Petugas Arsip                                           │
│    → Dokumen TIDAK dapat dibawa keluar                    │
│    → Dibaca di tempat ( viewing only )                     │
│                              ↓ Status: Dipinjam            │
│ 5. Khusus Pengadilan                                         │
│    → Ada izin tertulis dokter                              │
│    → Boleh difotokopi                                      │
│                              ↓ Status: Selesai             │
└─────────────────────────────────────────────────────────────┘
```

---

## 📊 Status Peminjaman

| Status | Deskripsi |
|--------|-----------|
| `menunggu_persetujuan` | Menunggu persetujuan kepala rekam medis |
| `disetujui` | Peminjaman disetujui, menunggu proses pengambilan |
| `ditolak` | Peminjaman ditolak dengan alasan |
| `dipinjam` | Dokumen sedang dipinjam |
| `dikembalikan_sebagian` | Sebagian dokumen dikembalikan |
| `selesai` | Semua dokumen dikembalikan dalam kondisi baik |
| `terlambat` | Melewati tanggal rencana kembali |

---

## 📊 Status Dokumen Rekam Medis

| Status | Deskripsi |
|--------|-----------|
| `tersedia` | Dokumen tersedia di tempat penyimpanan |
| `dipinjam` | Dokumen sedang dipinjam |
| `dalam_proses` | Sedang dalam proses peminjaman |
| `hilang` | Dokumen hilang |
| `rusak` | Dokumen rusak |
| `dimusnahkan` | Dokumen sudah dimusnahkan (melewati retensi) |

---

## 📁 Struktur Folder

```
medtrack/
├── app/
│   ├── Exports/                  # Export Excel
│   │   ├── PeminjamanExport.php
│   │   └── PengembalianExport.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── PasienController.php
│   │   │   ├── RekamMedisController.php
│   │   │   ├── PeminjamanController.php
│   │   │   ├── PengembalianController.php
│   │   │   ├── PenggunaController.php
│   │   │   ├── UnitController.php
│   │   │   ├── LaporanController.php
│   │   │   ├── ExportController.php
│   │   │   ├── ProfilController.php
│   │   │   └── Api/WilayahController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Pasien.php
│   │   ├── RekamMedis.php
│   │   ├── Peminjaman.php
│   │   ├── Pengembalian.php
│   │   └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── pasien/
│   │   ├── rekam-medis/
│   │   ├── peminjaman/
│   │   ├── pengembalian/
│   │   ├── laporan/
│   │   ├── exports/            # PDF Templates
│   │   ├── pengguna/
│   │   ├── unit/
│   │   └── vendor/pagination/
│   └── css/
│       └── medtrack.css
├── routes/
│   └── web.php
└── public/
    └── css/
```

---

## 🚀 Cara Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/ardhikaxx/medtrack.git
cd medtrack
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
```

### 4. Konfigurasi Database
Edit file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medtrack
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Key & Migrate
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 6. Build Assets
```bash
npm run build
```

### 7. Jalankan Server
```bash
php artisan serve
```

---

## 🔐 Default Login

Setelah menjalankan `php artisan db:seed`, berikut akun yang tersedia:

| Username | Email | Password | Role |
|----------|-------|----------|------|
| admin | admin@husada-clinic.id | password | Admin |
| direktur | direktur@husada-clinic.id | password | Direktur |
| kepala_rm | kepala.rm@husada-clinic.id | password | Kepala Rekam Medis |
| petugas_arsip1 | petugas1.rm@husada-clinic.id | password | Petugas Arsip |
| dr_budi | dr.budi@husada-clinic.id | password | Nakes Internal (Dokter) |
| dr_siti | dr.siti@husada-clinic.id | password | Nakes Internal (Dokter) |
| ns_ratna | ratna.ns@husada-clinic.id | password | Nakes Internal (Perawat) |
| dr_ahmad_ext | ahmad.fauzi@fkub.ac.id | password | Nakes Eksternal |

**Catatan:** Semua password default adalah `password`

---

## 📝 Fitur API

### Wilayah Indonesia
| Endpoint | Deskripsi |
|----------|-----------|
| `GET //provinces`api/wilayah | Daftar Provinsi |
| `GET /api/wilayah/regencies/{id}` | Daftar Kota/Kabupaten |
| `GET /api/wilayah/districts/{id}` | Daftar Kecamatan |
| `GET /api/wilayah/villages/{id}` | Daftar Kelurahan |

### Export Laporan
| Endpoint | Deskripsi |
|----------|-----------|
| `GET /export/peminjaman/excel` | Export Peminjaman ke Excel |
| `GET /export/peminjaman/pdf` | Export Peminjaman ke PDF |
| `GET /export/pengembalian/excel` | Export Pengembalian ke Excel |
| `GET /export/pengembalian/pdf` | Export Pengembalian ke PDF |
| `GET /export/rekap-bulanan/pdf` | Export Rekap Bulanan PDF |

---

## 📄 Lisensi

Copyright © 2026 Klinik Pratama Rawat Inap Husada. All rights reserved.

---

## 👨‍💻 Developer

**MedTrack** - Medical Record Borrowing & Return System for Klinik Pratama Rawat Inap Husada

Built with Laravel 12 ❤️
