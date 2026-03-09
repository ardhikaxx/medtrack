# MedTrack — Medical Record Borrowing & Return System
## Sistem Informasi Peminjaman dan Pengembalian Rekam Medis Rawat Jalan
### Klinik Pratama Rawat Inap Husada

---

## 📋 OVERVIEW SISTEM

MedTrack adalah sistem informasi berbasis web untuk mengelola peminjaman dan pengembalian dokumen rekam medis rawat jalan, sesuai SOP Peminjaman Dokumen Rekam Medis berdasarkan:
- **Permenkes No. 11 Tahun 2017** tentang Keselamatan Pasien
- **Permenkes No. 269 Tahun 2008** tentang Rekam Medis

**Stack Teknologi:**
- **Framework:** Laravel 12
- **Database:** MySQL 8.x
- **CSS Framework:** Bootstrap 5.3 (CDN)
- **Icons:** Font Awesome 6 (CDN)
- **Alert:** SweetAlert2 (CDN)
- **Table:** DataTables 1.13+ (CDN)
- **Search/Dropdown:** Select2 4.1 (CDN)
- **Custom Styling:** CSS variables + custom stylesheet

---

## 🏗️ ARSITEKTUR SISTEM

```
medtrack/
├── app/
│   ├── Console/Commands/
│   │   └── SendReturnReminder.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── PasienController.php
│   │   │   ├── RekamMedisController.php
│   │   │   ├── PeminjamanController.php
│   │   │   ├── PengembalianController.php
│   │   │   ├── PenggunaController.php
│   │   │   ├── UnitController.php
│   │   │   ├── LaporanController.php
│   │   │   ├── AuditLogController.php
│   │   │   └── SettingController.php
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php
│   │   │   └── CheckPermission.php
│   │   └── Requests/
│   │       ├── PasienRequest.php
│   │       ├── PeminjamanRequest.php
│   │       └── PengembalianRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Permission.php
│   │   ├── Pasien.php
│   │   ├── RekamMedis.php
│   │   ├── Peminjaman.php
│   │   ├── DetailPeminjaman.php
│   │   ├── Pengembalian.php
│   │   ├── Unit.php
│   │   ├── Poli.php
│   │   ├── Dokter.php
│   │   ├── RuangPenyimpanan.php
│   │   ├── AuditLog.php
│   │   └── Setting.php
│   ├── Notifications/
│   │   ├── PeminjamanDisetujui.php
│   │   ├── PeminjamanDitolak.php
│   │   └── ReminderPengembalian.php
│   └── Services/
│       ├── NomorRekamMedisService.php
│       └── PeminjamanService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   ├── auth.blade.php
│   │   │   └── sidebar.blade.php
│   │   ├── auth/
│   │   │   └── login.blade.php
│   │   ├── dashboard/
│   │   │   └── index.blade.php
│   │   ├── pasien/
│   │   ├── rekam-medis/
│   │   ├── peminjaman/
│   │   ├── pengembalian/
│   │   ├── laporan/
│   │   ├── pengguna/
│   │   ├── unit/
│   │   └── settings/
│   └── css/
│       └── medtrack.css
└── routes/
    └── web.php
```

---

## 🗄️ DATABASE SCHEMA & MIGRATIONS

### 1. Tabel: `roles`
```php
// database/migrations/2024_01_01_000001_create_roles_table.php
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('nama_role', 50)->unique(); // admin, kepala_rekam_medis, petugas_arsip, tenaga_kesehatan_internal, tenaga_kesehatan_eksternal, direktur
    $table->string('label', 100);
    $table->text('deskripsi')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 2. Tabel: `permissions`
```php
// database/migrations/2024_01_01_000002_create_permissions_table.php
Schema::create('permissions', function (Blueprint $table) {
    $table->id();
    $table->string('nama_permission', 100)->unique();
    $table->string('label', 150);
    $table->string('modul', 50); // pasien, rekam_medis, peminjaman, pengembalian, laporan, pengguna, setting
    $table->timestamps();
});
```

### 3. Tabel: `role_permissions` (pivot)
```php
Schema::create('role_permissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
    $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
    $table->unique(['role_id', 'permission_id']);
});
```

### 4. Tabel: `users`
```php
// database/migrations/2024_01_01_000003_create_users_table.php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('nama_lengkap', 150);
    $table->string('nik', 16)->unique()->nullable(); // Nomor Induk Kependudukan
    $table->string('nip', 30)->unique()->nullable(); // Nomor Induk Pegawai
    $table->string('email')->unique();
    $table->string('no_telp', 15)->nullable();
    $table->string('username', 50)->unique();
    $table->string('password');
    $table->foreignId('role_id')->constrained('roles');
    $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
    $table->string('jabatan', 100)->nullable();
    $table->string('spesialisasi', 100)->nullable(); // untuk dokter
    $table->string('str_number', 50)->nullable(); // Surat Tanda Registrasi
    $table->string('institusi_asal', 200)->nullable(); // untuk pihak luar
    $table->enum('jenis_pengguna', ['internal', 'eksternal'])->default('internal');
    $table->string('foto_profil')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamp('last_login_at')->nullable();
    $table->string('last_login_ip', 45)->nullable();
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();
});
```

### 5. Tabel: `units`
```php
// database/migrations/2024_01_01_000004_create_units_table.php
Schema::create('units', function (Blueprint $table) {
    $table->id();
    $table->string('kode_unit', 20)->unique();
    $table->string('nama_unit', 150);
    $table->enum('jenis_unit', ['poli', 'ugd', 'rawat_inap', 'penunjang', 'administrasi', 'lainnya']);
    $table->string('lantai', 10)->nullable();
    $table->string('gedung', 50)->nullable();
    $table->string('no_telp_unit', 15)->nullable();
    $table->foreignId('kepala_unit_id')->nullable()->constrained('users')->nullOnDelete();
    $table->boolean('is_active')->default(true);
    $table->text('keterangan')->nullable();
    $table->timestamps();
});
```

### 6. Tabel: `pasiens`
```php
// database/migrations/2024_01_01_000005_create_pasiens_table.php
Schema::create('pasiens', function (Blueprint $table) {
    $table->id();
    $table->string('no_rekam_medis', 20)->unique(); // Format: RM-YYYY-XXXXXX
    $table->string('nik', 16)->unique()->nullable();
    $table->string('no_kk', 16)->nullable();
    $table->string('nama_lengkap', 150);
    $table->string('nama_panggilan', 50)->nullable();
    $table->enum('jenis_kelamin', ['L', 'P']);
    $table->string('tempat_lahir', 100);
    $table->date('tanggal_lahir');
    $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'tidak_diketahui'])->default('tidak_diketahui');
    $table->enum('agama', ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu', 'lainnya']);
    $table->enum('status_pernikahan', ['belum_menikah', 'menikah', 'cerai', 'duda', 'janda'])->default('belum_menikah');
    $table->string('pendidikan', 50)->nullable(); // SD, SMP, SMA, D3, S1, S2, S3
    $table->string('pekerjaan', 100)->nullable();
    $table->string('nama_ibu_kandung', 150)->nullable(); // untuk verifikasi identitas
    // Alamat
    $table->text('alamat_lengkap');
    $table->string('rt', 5)->nullable();
    $table->string('rw', 5)->nullable();
    $table->string('kelurahan', 100);
    $table->string('kecamatan', 100);
    $table->string('kota_kabupaten', 100);
    $table->string('provinsi', 100);
    $table->string('kode_pos', 10)->nullable();
    // Kontak
    $table->string('no_telp', 15)->nullable();
    $table->string('no_hp', 15)->nullable();
    // Asuransi/Jaminan
    $table->enum('jenis_jaminan', ['umum', 'bpjs_kesehatan', 'bpjs_ketenagakerjaan', 'asuransi_swasta', 'jamkesda'])->default('umum');
    $table->string('no_jaminan', 30)->nullable(); // No. BPJS / Asuransi
    $table->string('kelas_jaminan', 10)->nullable(); // Kelas 1, 2, 3
    // Kontak darurat
    $table->string('nama_kontak_darurat', 150)->nullable();
    $table->string('hubungan_kontak_darurat', 50)->nullable();
    $table->string('no_telp_kontak_darurat', 15)->nullable();
    // Status
    $table->enum('status_pasien', ['aktif', 'nonaktif', 'meninggal'])->default('aktif');
    $table->date('tanggal_registrasi');
    $table->date('kunjungan_terakhir')->nullable();
    $table->text('catatan')->nullable();
    $table->string('foto_pasien')->nullable();
    $table->timestamps();
    $table->softDeletes();

    $table->index(['nama_lengkap']);
    $table->index(['nik']);
    $table->index(['no_rekam_medis']);
});
```

### 7. Tabel: `rekam_medis`
```php
// database/migrations/2024_01_01_000006_create_rekam_medis_table.php
Schema::create('rekam_medis', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
    $table->string('kode_dokumen', 30)->unique(); // Format: DOK-YYYY-XXXXXX
    $table->string('no_rekam_medis', 20); // Salin dari pasien untuk kemudahan query
    $table->date('tanggal_kunjungan');
    $table->foreignId('poli_id')->nullable()->constrained('units')->nullOnDelete();
    $table->foreignId('dokter_id')->nullable()->constrained('users')->nullOnDelete();
    $table->enum('jenis_kunjungan', ['rawat_jalan', 'rawat_inap', 'ugd', 'konsultasi']);
    $table->enum('status_dokumen', ['tersedia', 'dipinjam', 'dalam_proses', 'hilang', 'rusak', 'dimusnahkan'])->default('tersedia');
    // Lokasi fisik dokumen
    $table->foreignId('ruang_penyimpanan_id')->nullable()->constrained('ruang_penyimpanans')->nullOnDelete();
    $table->string('rak', 20)->nullable();
    $table->string('laci', 20)->nullable();
    $table->string('map_folder', 20)->nullable();
    $table->integer('jumlah_halaman')->nullable();
    $table->integer('ketebalan_cm')->nullable();
    $table->date('tanggal_retensi')->nullable(); // Tanggal dokumen harus dimusnahkan
    $table->enum('kondisi_dokumen', ['baik', 'cukup', 'rusak_ringan', 'rusak_berat'])->default('baik');
    $table->text('diagnosa_utama')->nullable();
    $table->string('kode_icd10', 20)->nullable();
    $table->text('catatan_dokumen')->nullable();
    $table->foreignId('dibuat_oleh')->constrained('users');
    $table->timestamps();
    $table->softDeletes();

    $table->index(['pasien_id', 'tanggal_kunjungan']);
    $table->index(['status_dokumen']);
});
```

### 8. Tabel: `ruang_penyimpanans`
```php
Schema::create('ruang_penyimpanans', function (Blueprint $table) {
    $table->id();
    $table->string('kode_ruang', 20)->unique();
    $table->string('nama_ruang', 100);
    $table->string('lantai', 10)->nullable();
    $table->string('gedung', 50)->nullable();
    $table->integer('kapasitas_rak')->nullable();
    $table->text('keterangan')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 9. Tabel: `peminjamans`
```php
// database/migrations/2024_01_01_000007_create_peminjamans_table.php
Schema::create('peminjamans', function (Blueprint $table) {
    $table->id();
    $table->string('no_peminjaman', 30)->unique(); // Format: PJM-YYYYMMDD-XXXX
    $table->foreignId('peminjam_id')->constrained('users'); // user yang meminjam
    $table->string('nama_peminjam_luar', 150)->nullable(); // jika dari luar, tidak terdaftar di sistem
    $table->string('institusi_peminjam', 200)->nullable();
    $table->enum('jenis_peminjam', ['internal', 'eksternal']);
    $table->string('tujuan_peminjaman', 50); // pelayanan, penelitian, audit, pengadilan, pendidikan
    $table->text('keperluan_detail');
    $table->string('no_surat_permohonan', 50)->nullable(); // untuk peminjam eksternal
    $table->string('file_surat_permohonan')->nullable(); // upload surat
    $table->date('tanggal_pinjam');
    $table->date('tanggal_kembali_rencana');
    $table->date('tanggal_kembali_aktual')->nullable();
    $table->enum('status_peminjaman', [
        'menunggu_persetujuan',
        'disetujui',
        'ditolak',
        'dipinjam',
        'dikembalikan_sebagian',
        'selesai',
        'terlambat'
    ])->default('menunggu_persetujuan');
    // Persetujuan
    $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('tanggal_disetujui')->nullable();
    $table->text('catatan_persetujuan')->nullable();
    // Penolakan
    $table->text('alasan_penolakan')->nullable();
    // Petugas
    $table->foreignId('petugas_peminjaman_id')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('petugas_pengembalian_id')->nullable()->constrained('users')->nullOnDelete();
    $table->text('catatan_peminjaman')->nullable();
    $table->text('catatan_pengembalian')->nullable();
    $table->boolean('is_pengadilan')->default(false); // khusus keperluan pengadilan
    $table->boolean('allow_fotokopi')->default(false); // izin fotokopi (hanya untuk pengadilan)
    $table->string('no_surat_pengadilan', 100)->nullable();
    $table->foreignId('dokter_yang_merawat_id')->nullable()->constrained('users')->nullOnDelete(); // untuk izin fotokopi
    $table->timestamps();
    $table->softDeletes();

    $table->index(['status_peminjaman']);
    $table->index(['tanggal_pinjam']);
    $table->index(['peminjam_id']);
});
```

### 10. Tabel: `detail_peminjamans`
```php
// Pivot antara peminjaman dan rekam medis (satu peminjaman bisa banyak dokumen)
Schema::create('detail_peminjamans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('peminjaman_id')->constrained('peminjamans')->onDelete('cascade');
    $table->foreignId('rekam_medis_id')->constrained('rekam_medis')->onDelete('cascade');
    $table->enum('status_detail', ['dipinjam', 'dikembalikan', 'hilang', 'rusak'])->default('dipinjam');
    $table->timestamp('tanggal_dikembalikan')->nullable();
    $table->enum('kondisi_kembali', ['baik', 'cukup', 'rusak_ringan', 'rusak_berat'])->nullable();
    $table->text('catatan_detail')->nullable();
    $table->foreignId('dikembalikan_oleh')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamps();

    $table->unique(['peminjaman_id', 'rekam_medis_id']);
});
```

### 11. Tabel: `pengembalians`
```php
Schema::create('pengembalians', function (Blueprint $table) {
    $table->id();
    $table->string('no_pengembalian', 30)->unique(); // Format: KBL-YYYYMMDD-XXXX
    $table->foreignId('peminjaman_id')->constrained('peminjamans');
    $table->date('tanggal_pengembalian');
    $table->foreignId('petugas_id')->constrained('users');
    $table->integer('jumlah_dokumen_kembali');
    $table->integer('jumlah_dokumen_hilang')->default(0);
    $table->integer('jumlah_dokumen_rusak')->default(0);
    $table->text('catatan_pengembalian')->nullable();
    $table->boolean('is_terlambat')->default(false);
    $table->integer('hari_terlambat')->default(0);
    $table->timestamps();
});
```

### 12. Tabel: `audit_logs`
```php
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
    $table->string('aksi', 50); // create, update, delete, login, logout, approve, reject, etc.
    $table->string('modul', 50); // peminjaman, rekam_medis, pasien, dll
    $table->string('model_type', 100)->nullable();
    $table->unsignedBigInteger('model_id')->nullable();
    $table->json('data_lama')->nullable();
    $table->json('data_baru')->nullable();
    $table->string('ip_address', 45)->nullable();
    $table->string('user_agent')->nullable();
    $table->text('keterangan')->nullable();
    $table->timestamp('created_at');

    $table->index(['user_id', 'created_at']);
    $table->index(['modul', 'aksi']);
});
```

### 13. Tabel: `settings`
```php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('key', 100)->unique();
    $table->text('value')->nullable();
    $table->string('label', 200)->nullable();
    $table->string('tipe', 30)->default('text'); // text, number, boolean, json, date
    $table->string('grup', 50)->default('umum'); // umum, peminjaman, notifikasi, tampilan
    $table->text('deskripsi')->nullable();
    $table->timestamps();
});
```

### 14. Tabel: `notifikasis`
```php
Schema::create('notifikasis', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->string('judul', 200);
    $table->text('pesan');
    $table->string('tipe', 50); // info, warning, success, danger
    $table->string('url_tujuan')->nullable();
    $table->boolean('is_read')->default(false);
    $table->timestamp('read_at')->nullable();
    $table->morphs('notifiable'); // polymorphic ke peminjaman, pengembalian, dll
    $table->timestamps();
});
```

---

## 🔗 MODEL RELATIONSHIPS

### User Model
```php
// app/Models/User.php
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nama_lengkap', 'nik', 'nip', 'email', 'no_telp', 'username',
        'password', 'role_id', 'unit_id', 'jabatan', 'spesialisasi',
        'str_number', 'institusi_asal', 'jenis_pengguna', 'foto_profil',
        'is_active', 'last_login_at', 'last_login_ip'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'peminjam_id');
    }

    public function peminjamansDisetujui(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'disetujui_oleh');
    }

    public function rekamMedisDibuat(): HasMany
    {
        return $this->hasMany(RekamMedis::class, 'dibuat_oleh');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Helpers
    public function hasPermission(string $permission): bool
    {
        return $this->role->permissions->contains('nama_permission', $permission);
    }

    public function isAdmin(): bool
    {
        return $this->role->nama_role === 'admin';
    }

    public function isDirektur(): bool
    {
        return $this->role->nama_role === 'direktur';
    }

    public function isKepalaRekamMedis(): bool
    {
        return $this->role->nama_role === 'kepala_rekam_medis';
    }

    public function isPetugasArsip(): bool
    {
        return $this->role->nama_role === 'petugas_arsip';
    }
}
```

### Pasien Model
```php
// app/Models/Pasien.php
class Pasien extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_rekam_medis', 'nik', 'no_kk', 'nama_lengkap', 'nama_panggilan',
        'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'golongan_darah',
        'agama', 'status_pernikahan', 'pendidikan', 'pekerjaan', 'nama_ibu_kandung',
        'alamat_lengkap', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kota_kabupaten',
        'provinsi', 'kode_pos', 'no_telp', 'no_hp',
        'jenis_jaminan', 'no_jaminan', 'kelas_jaminan',
        'nama_kontak_darurat', 'hubungan_kontak_darurat', 'no_telp_kontak_darurat',
        'status_pasien', 'tanggal_registrasi', 'kunjungan_terakhir', 'catatan', 'foto_pasien'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_registrasi' => 'date',
        'kunjungan_terakhir' => 'date',
    ];

    // Relationships
    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class);
    }

    public function rekamMedisAktif(): HasMany
    {
        return $this->hasMany(RekamMedis::class)->where('status_dokumen', 'tersedia');
    }

    // Scopes
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

    // Accessors
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
```

### RekamMedis Model
```php
// app/Models/RekamMedis.php
class RekamMedis extends Model
{
    use HasFactory, SoftDeletes;

    // Relationships
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'poli_id');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function ruangPenyimpanan(): BelongsTo
    {
        return $this->belongsTo(RuangPenyimpanan::class);
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function peminjamans(): BelongsToMany
    {
        return $this->belongsToMany(Peminjaman::class, 'detail_peminjamans')
                    ->withPivot(['status_detail', 'tanggal_dikembalikan', 'kondisi_kembali', 'catatan_detail'])
                    ->withTimestamps();
    }

    public function peminjamansAktif(): BelongsToMany
    {
        return $this->peminjamans()->wherePivot('status_detail', 'dipinjam');
    }

    // Scopes
    public function scopeTersedia($query)
    {
        return $query->where('status_dokumen', 'tersedia');
    }

    public function scopeDipinjam($query)
    {
        return $query->where('status_dokumen', 'dipinjam');
    }

    // Helpers
    public function isTersedia(): bool
    {
        return $this->status_dokumen === 'tersedia';
    }
}
```

### Peminjaman Model
```php
// app/Models/Peminjaman.php
class Peminjaman extends Model
{
    use HasFactory, SoftDeletes;

    // Relationships
    public function peminjam(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peminjam_id');
    }

    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function petugasPeminjaman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_peminjaman_id');
    }

    public function petugasPengembalian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_pengembalian_id');
    }

    public function dokterYangMerawat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dokter_yang_merawat_id');
    }

    public function rekamMedis(): BelongsToMany
    {
        return $this->belongsToMany(RekamMedis::class, 'detail_peminjamans')
                    ->withPivot(['status_detail', 'tanggal_dikembalikan', 'kondisi_kembali', 'catatan_detail', 'dikembalikan_oleh'])
                    ->withTimestamps();
    }

    public function detailPeminjamans(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function pengembalians(): HasMany
    {
        return $this->hasMany(Pengembalian::class);
    }

    // Scopes
    public function scopeTerlambat($query)
    {
        return $query->where('tanggal_kembali_rencana', '<', now())
                     ->whereNotIn('status_peminjaman', ['selesai', 'dikembalikan_sebagian']);
    }

    public function scopeMenungguPersetujuan($query)
    {
        return $query->where('status_peminjaman', 'menunggu_persetujuan');
    }

    // Helpers
    public function isTerlambat(): bool
    {
        return $this->tanggal_kembali_rencana < now()->toDateString()
            && !in_array($this->status_peminjaman, ['selesai']);
    }

    public function getHariTerlambatAttribute(): int
    {
        if (!$this->isTerlambat()) return 0;
        return now()->diffInDays($this->tanggal_kembali_rencana);
    }

    public function getJumlahDokumenAttribute(): int
    {
        return $this->detailPeminjamans()->count();
    }
}
```

---

## 👥 ROLES & PERMISSIONS

### Role Definitions:

| Role | Label | Akses |
|---|---|---|
| `admin` | Administrator Sistem | Full access semua fitur |
| `direktur` | Direktur / Pimpinan | Approve peminjaman eksternal, laporan eksekutif |
| `kepala_rekam_medis` | Kepala Rekam Medis | Approve peminjaman internal & eksternal, manajemen dokumen |
| `petugas_arsip` | Petugas Arsip | Proses peminjaman & pengembalian yang sudah disetujui |
| `tenaga_kesehatan_internal` | Tenaga Kesehatan Internal | Buat permohonan peminjaman internal |
| `tenaga_kesehatan_eksternal` | Tenaga Kesehatan Eksternal | Buat permohonan peminjaman eksternal (butuh surat) |

### Permission Matrix:

```php
// database/seeders/PermissionSeeder.php

$permissions = [
    // Pasien
    ['pasien.view', 'Lihat Data Pasien', 'pasien'],
    ['pasien.create', 'Tambah Data Pasien', 'pasien'],
    ['pasien.edit', 'Edit Data Pasien', 'pasien'],
    ['pasien.delete', 'Hapus Data Pasien', 'pasien'],
    ['pasien.export', 'Export Data Pasien', 'pasien'],

    // Rekam Medis
    ['rekam_medis.view', 'Lihat Rekam Medis', 'rekam_medis'],
    ['rekam_medis.create', 'Tambah Rekam Medis', 'rekam_medis'],
    ['rekam_medis.edit', 'Edit Rekam Medis', 'rekam_medis'],
    ['rekam_medis.delete', 'Hapus Rekam Medis', 'rekam_medis'],
    ['rekam_medis.view_detail', 'Lihat Detail Rekam Medis', 'rekam_medis'],
    ['rekam_medis.manage_storage', 'Kelola Lokasi Penyimpanan', 'rekam_medis'],

    // Peminjaman
    ['peminjaman.view', 'Lihat Daftar Peminjaman', 'peminjaman'],
    ['peminjaman.create', 'Buat Permohonan Peminjaman', 'peminjaman'],
    ['peminjaman.approve', 'Setujui/Tolak Peminjaman', 'peminjaman'],
    ['peminjaman.process', 'Proses Pengeluaran Dokumen', 'peminjaman'],
    ['peminjaman.view_all', 'Lihat Semua Peminjaman', 'peminjaman'],
    ['peminjaman.cancel', 'Batalkan Peminjaman', 'peminjaman'],

    // Pengembalian
    ['pengembalian.view', 'Lihat Pengembalian', 'pengembalian'],
    ['pengembalian.process', 'Proses Pengembalian', 'pengembalian'],

    // Laporan
    ['laporan.view', 'Lihat Laporan', 'laporan'],
    ['laporan.export', 'Export Laporan', 'laporan'],
    ['laporan.statistik', 'Lihat Statistik', 'laporan'],

    // Pengguna
    ['pengguna.view', 'Lihat Data Pengguna', 'pengguna'],
    ['pengguna.create', 'Tambah Pengguna', 'pengguna'],
    ['pengguna.edit', 'Edit Pengguna', 'pengguna'],
    ['pengguna.delete', 'Hapus Pengguna', 'pengguna'],
    ['pengguna.manage_role', 'Kelola Role', 'pengguna'],

    // Unit
    ['unit.view', 'Lihat Unit', 'unit'],
    ['unit.manage', 'Kelola Unit', 'unit'],

    // Setting
    ['setting.view', 'Lihat Pengaturan', 'setting'],
    ['setting.manage', 'Kelola Pengaturan', 'setting'],

    // Audit
    ['audit.view', 'Lihat Log Audit', 'audit'],

    // Dashboard
    ['dashboard.view', 'Lihat Dashboard', 'dashboard'],
    ['dashboard.statistik_lengkap', 'Lihat Statistik Lengkap', 'dashboard'],
];
```

---

## 🔄 ALUR SISTEM (SESUAI SOP)

### Alur 1: Peminjaman Tenaga Kesehatan Internal

```
[Tenaga Kesehatan Internal]
       │
       ▼
┌─────────────────────────────────┐
│ 1. Buat Permohonan Peminjaman   │
│    - Isi identitas dokumen:     │
│      • No. Rekam Medis         │
│      • Nama Pasien             │
│      • Tanggal Pulang Rawat    │
│      • Kunjungan Terakhir      │
│    - Isi keperluan & tujuan    │
│    - Tentukan tgl kembali      │
└───────────────┬─────────────────┘
                │ Status: menunggu_persetujuan
                ▼
┌─────────────────────────────────┐
│ 2. Notifikasi ke Kepala Rekam  │
│    Medis / Admin                │
└───────────────┬─────────────────┘
                │
                ▼
┌─────────────────────────────────┐
│ 3. Review & Persetujuan         │
│    [Kepala Rekam Medis]        │
│    - Disetujui → Lanjut        │
│    - Ditolak  → Notifikasi     │
│                  ke peminjam   │
└───────────────┬─────────────────┘
                │ Status: disetujui
                ▼
┌─────────────────────────────────┐
│ 4. Proses di Petugas Arsip     │
│    - Cari dokumen di ruang     │
│      penyimpanan               │
│    - Peminjam isi & TTD        │
│      blanko/formulir peminjaman│
│    - Dokumen diserahkan        │
└───────────────┬─────────────────┘
                │ Status: dipinjam
                ▼
┌─────────────────────────────────┐
│ 5. Penggunaan Dokumen          │
│    - Peminjam menggunakan      │
│    - Reminder otomatis H-1     │
└───────────────┬─────────────────┘
                │
                ▼
┌─────────────────────────────────┐
│ 6. Pengembalian Dokumen        │
│    [Petugas Arsip]             │
│    - Cek kondisi dokumen       │
│    - Input pengembalian        │
│    - Dokumen disimpan kembali  │
└───────────────┬─────────────────┘
                │ Status: selesai
                ▼
         [SELESAI ✓]
```

### Alur 2: Peminjaman Pihak Eksternal

```
[Pihak Luar Klinik]
       │
       ▼
┌─────────────────────────────────┐
│ 1. Buat Surat Permohonan       │
│    ke Direktur/Pimpinan        │
│    - Upload file surat         │
│    - Isi detail keperluan      │
│    - Pilih dokumen yang        │
│      dibutuhkan                │
└───────────────┬─────────────────┘
                │ Status: menunggu_persetujuan
                ▼
┌─────────────────────────────────┐
│ 2. Disposisi Direktur          │
│    - Surat diteruskan ke       │
│      Kepala Rekam Medis        │
└───────────────┬─────────────────┘
                │
                ▼
┌─────────────────────────────────┐
│ 3. Persetujuan Kepala Rekam    │
│    Medis                       │
│    - Review permohonan        │
│    - Disetujui → Lanjut       │
│    - Ditolak → Notifikasi     │
└───────────────┬─────────────────┘
                │ Status: disetujui
                ▼
┌─────────────────────────────────┐
│ 4. Proses Petugas Arsip        │
│    - Dokumen TIDAK dapat       │
│      dibawa keluar             │
│    - TIDAK dapat difotokopi    │
│      (kecuali pengadilan)      │
│    - Dibaca di tempat          │
└───────────────┬─────────────────┘
                │ Status: dipinjam
                ▼
┌─────────────────────────────────┐
│ 5. Khusus Pengadilan           │
│    - Ada izin tertulis dokter  │
│      yang merawat              │
│    - Boleh difotokopi          │
│    - Dicatat detail            │
└───────────────┬─────────────────┘
                │
                ▼
┌─────────────────────────────────┐
│ 6. Pengembalian                │
│    - Dokumen dikembalikan      │
│    - Petugas cek & simpan      │
└───────────────┴─────────────────┘
                │ Status: selesai
                ▼
         [SELESAI ✓]
```

---

## 📁 ROUTES

```php
// routes/web.php

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/statistik', [DashboardController::class, 'statistik'])->name('dashboard.statistik');
    Route::get('/dashboard/aktivitas-terkini', [DashboardController::class, 'aktivitasTerkini'])->name('dashboard.aktivitas');

    // Pasien
    Route::prefix('pasien')->name('pasien.')->middleware('permission:pasien.view')->group(function () {
        Route::get('/', [PasienController::class, 'index'])->name('index');
        Route::get('/create', [PasienController::class, 'create'])->name('create')->middleware('permission:pasien.create');
        Route::post('/', [PasienController::class, 'store'])->name('store')->middleware('permission:pasien.create');
        Route::get('/{pasien}', [PasienController::class, 'show'])->name('show');
        Route::get('/{pasien}/edit', [PasienController::class, 'edit'])->name('edit')->middleware('permission:pasien.edit');
        Route::put('/{pasien}', [PasienController::class, 'update'])->name('update')->middleware('permission:pasien.edit');
        Route::delete('/{pasien}', [PasienController::class, 'destroy'])->name('destroy')->middleware('permission:pasien.delete');
        Route::get('/search/select2', [PasienController::class, 'select2'])->name('select2');
        Route::get('/{pasien}/riwayat-peminjaman', [PasienController::class, 'riwayatPeminjaman'])->name('riwayat');
        Route::get('/export/excel', [PasienController::class, 'exportExcel'])->name('export.excel')->middleware('permission:pasien.export');
        Route::get('/export/pdf', [PasienController::class, 'exportPdf'])->name('export.pdf')->middleware('permission:pasien.export');
        Route::post('/import', [PasienController::class, 'import'])->name('import');
        Route::post('/{pasien}/restore', [PasienController::class, 'restore'])->name('restore');
    });

    // Rekam Medis
    Route::prefix('rekam-medis')->name('rekam-medis.')->middleware('permission:rekam_medis.view')->group(function () {
        Route::get('/', [RekamMedisController::class, 'index'])->name('index');
        Route::get('/create', [RekamMedisController::class, 'create'])->name('create')->middleware('permission:rekam_medis.create');
        Route::post('/', [RekamMedisController::class, 'store'])->name('store')->middleware('permission:rekam_medis.create');
        Route::get('/{rekamMedis}', [RekamMedisController::class, 'show'])->name('show');
        Route::get('/{rekamMedis}/edit', [RekamMedisController::class, 'edit'])->name('edit')->middleware('permission:rekam_medis.edit');
        Route::put('/{rekamMedis}', [RekamMedisController::class, 'update'])->name('update')->middleware('permission:rekam_medis.edit');
        Route::delete('/{rekamMedis}', [RekamMedisController::class, 'destroy'])->name('destroy')->middleware('permission:rekam_medis.delete');
        Route::get('/search/select2', [RekamMedisController::class, 'select2'])->name('select2');
        Route::get('/cek-ketersediaan/{id}', [RekamMedisController::class, 'cekKetersediaan'])->name('cek');
        Route::post('/{rekamMedis}/update-lokasi', [RekamMedisController::class, 'updateLokasi'])->name('update-lokasi')->middleware('permission:rekam_medis.manage_storage');
        Route::get('/export/excel', [RekamMedisController::class, 'exportExcel'])->name('export.excel');
        Route::get('/cetak-label/{rekamMedis}', [RekamMedisController::class, 'cetakLabel'])->name('cetak-label');
        Route::get('/retensi', [RekamMedisController::class, 'retensi'])->name('retensi');
    });

    // Peminjaman
    Route::prefix('peminjaman')->name('peminjaman.')->middleware('permission:peminjaman.view')->group(function () {
        Route::get('/', [PeminjamanController::class, 'index'])->name('index');
        Route::get('/create', [PeminjamanController::class, 'create'])->name('create')->middleware('permission:peminjaman.create');
        Route::post('/', [PeminjamanController::class, 'store'])->name('store')->middleware('permission:peminjaman.create');
        Route::get('/{peminjaman}', [PeminjamanController::class, 'show'])->name('show');
        Route::get('/{peminjaman}/edit', [PeminjamanController::class, 'edit'])->name('edit');
        Route::put('/{peminjaman}', [PeminjamanController::class, 'update'])->name('update');
        Route::post('/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('setujui')->middleware('permission:peminjaman.approve');
        Route::post('/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])->name('tolak')->middleware('permission:peminjaman.approve');
        Route::post('/{peminjaman}/proses', [PeminjamanController::class, 'proses'])->name('proses')->middleware('permission:peminjaman.process');
        Route::post('/{peminjaman}/batalkan', [PeminjamanController::class, 'batalkan'])->name('batalkan')->middleware('permission:peminjaman.cancel');
        Route::get('/{peminjaman}/cetak-formulir', [PeminjamanController::class, 'cetakFormulir'])->name('cetak-formulir');
        Route::get('/menunggu', [PeminjamanController::class, 'menunggu'])->name('menunggu');
        Route::get('/terlambat', [PeminjamanController::class, 'terlambat'])->name('terlambat');
        Route::get('/export/excel', [PeminjamanController::class, 'exportExcel'])->name('export.excel');
    });

    // Pengembalian
    Route::prefix('pengembalian')->name('pengembalian.')->middleware('permission:pengembalian.view')->group(function () {
        Route::get('/', [PengembalianController::class, 'index'])->name('index');
        Route::get('/create/{peminjaman}', [PengembalianController::class, 'create'])->name('create')->middleware('permission:pengembalian.process');
        Route::post('/', [PengembalianController::class, 'store'])->name('store')->middleware('permission:pengembalian.process');
        Route::get('/{pengembalian}', [PengembalianController::class, 'show'])->name('show');
        Route::get('/{pengembalian}/cetak-tanda-terima', [PengembalianController::class, 'cetakTandaTerima'])->name('cetak');
    });

    // Laporan
    Route::prefix('laporan')->name('laporan.')->middleware('permission:laporan.view')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/peminjaman', [LaporanController::class, 'peminjaman'])->name('peminjaman');
        Route::get('/pengembalian', [LaporanController::class, 'pengembalian'])->name('pengembalian');
        Route::get('/terlambat', [LaporanController::class, 'terlambat'])->name('terlambat');
        Route::get('/statistik-dokumen', [LaporanController::class, 'statistikDokumen'])->name('statistik-dokumen');
        Route::get('/statistik-peminjam', [LaporanController::class, 'statistikPeminjam'])->name('statistik-peminjam');
        Route::get('/rekap-bulanan', [LaporanController::class, 'rekapBulanan'])->name('rekap-bulanan');
        Route::get('/export/peminjaman/excel', [LaporanController::class, 'exportPeminjamanExcel'])->name('export.peminjaman.excel')->middleware('permission:laporan.export');
        Route::get('/export/peminjaman/pdf', [LaporanController::class, 'exportPeminjamanPdf'])->name('export.peminjaman.pdf')->middleware('permission:laporan.export');
    });

    // Pengguna
    Route::prefix('pengguna')->name('pengguna.')->middleware('permission:pengguna.view')->group(function () {
        Route::get('/', [PenggunaController::class, 'index'])->name('index');
        Route::get('/create', [PenggunaController::class, 'create'])->name('create')->middleware('permission:pengguna.create');
        Route::post('/', [PenggunaController::class, 'store'])->name('store')->middleware('permission:pengguna.create');
        Route::get('/{user}', [PenggunaController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [PenggunaController::class, 'edit'])->name('edit')->middleware('permission:pengguna.edit');
        Route::put('/{user}', [PenggunaController::class, 'update'])->name('update')->middleware('permission:pengguna.edit');
        Route::delete('/{user}', [PenggunaController::class, 'destroy'])->name('destroy')->middleware('permission:pengguna.delete');
        Route::post('/{user}/reset-password', [PenggunaController::class, 'resetPassword'])->name('reset-password');
        Route::post('/{user}/toggle-aktif', [PenggunaController::class, 'toggleAktif'])->name('toggle-aktif');
    });

    // Unit
    Route::resource('unit', UnitController::class)->middleware('permission:unit.view');

    // Ruang Penyimpanan
    Route::resource('ruang-penyimpanan', RuangPenyimpananController::class)->middleware('permission:rekam_medis.manage_storage');

    // Audit Log
    Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log.index')->middleware('permission:audit.view');

    // Setting
    Route::prefix('setting')->name('setting.')->middleware('permission:setting.view')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/update', [SettingController::class, 'update'])->name('update')->middleware('permission:setting.manage');
    });

    // Notifikasi
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('index');
        Route::post('/{notifikasi}/read', [NotifikasiController::class, 'read'])->name('read');
        Route::post('/read-all', [NotifikasiController::class, 'readAll'])->name('read-all');
    });

    // Profil
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ProfilController::class, 'index'])->name('index');
        Route::put('/update', [ProfilController::class, 'update'])->name('update');
        Route::put('/password', [ProfilController::class, 'updatePassword'])->name('password');
    });
});
```

---

## 🎨 FRONTEND LAYOUT & COMPONENTS

### Master Layout (`resources/views/layouts/app.blade.php`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MedTrack') — Sistem Rekam Medis Husada</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/medtrack.css') }}">

    @stack('styles')
</head>
<body class="medtrack-body">

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content Wrapper -->
    <div class="content-wrapper" id="content-wrapper">

        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-icon sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Notifikasi -->
                <div class="dropdown">
                    <button class="btn btn-icon position-relative" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        @if(auth()->user()->notifikasis()->where('is_read', false)->count() > 0)
                        <span class="badge-notif">{{ auth()->user()->notifikasis()->where('is_read', false)->count() }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notif-dropdown">
                        @include('components.notifikasi-dropdown')
                    </div>
                </div>

                <!-- User Menu -->
                <div class="dropdown">
                    <button class="btn user-avatar-btn d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        @if(auth()->user()->foto_profil)
                        <img src="{{ asset('storage/'.auth()->user()->foto_profil) }}" class="avatar-sm rounded-circle" alt="">
                        @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
                        </div>
                        @endif
                        <div class="d-none d-md-block text-start">
                            <div class="user-name">{{ auth()->user()->nama_lengkap }}</div>
                            <div class="user-role">{{ auth()->user()->role->label }}</div>
                        </div>
                        <i class="fas fa-chevron-down ms-1 small"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profil.index') }}"><i class="fas fa-user me-2"></i>Profil Saya</a></li>
                        <li><a class="dropdown-item" href="{{ route('setting.index') }}" @cannot('setting.view') style="display:none" @endcannot><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="main-content">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="main-footer">
            <span>© {{ date('Y') }} MedTrack — Klinik Pratama Rawat Inap Husada</span>
            <span>v1.0.0</span>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/medtrack.js') }}"></script>

    <!-- SweetAlert Flash Messages -->
    @if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session("error") }}', toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
    </script>
    @endif
    @if(session('warning'))
    <script>
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: '{{ session("warning") }}', toast: true, position: 'top-end', showConfirmButton: false, timer: 3500 });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
```

### Sidebar (`resources/views/layouts/sidebar.blade.php`)
```html
<aside class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-notes-medical"></i>
        </div>
        <div class="brand-text">
            <span class="brand-name">MedTrack</span>
            <span class="brand-subtitle">Rekam Medis</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <div class="nav-section">
            <div class="nav-section-label">Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large nav-icon"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Rekam Medis -->
        <div class="nav-section">
            <div class="nav-section-label">Rekam Medis</div>
            @can('pasien.view')
            <a href="{{ route('pasien.index') }}" class="nav-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                <i class="fas fa-user-injured nav-icon"></i>
                <span>Data Pasien</span>
            </a>
            @endcan
            @can('rekam_medis.view')
            <a href="{{ route('rekam-medis.index') }}" class="nav-link {{ request()->routeIs('rekam-medis.*') ? 'active' : '' }}">
                <i class="fas fa-file-medical nav-icon"></i>
                <span>Dokumen Rekam Medis</span>
            </a>
            @endcan
        </div>

        <!-- Peminjaman -->
        <div class="nav-section">
            <div class="nav-section-label">Peminjaman</div>
            @can('peminjaman.view')
            <a href="{{ route('peminjaman.index') }}" class="nav-link {{ request()->routeIs('peminjaman.index') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-medical nav-icon"></i>
                <span>Peminjaman</span>
                @php $menunggu = \App\Models\Peminjaman::menungguPersetujuan()->count(); @endphp
                @if($menunggu > 0 && auth()->user()->hasPermission('peminjaman.approve'))
                <span class="nav-badge">{{ $menunggu }}</span>
                @endif
            </a>
            @endcan
            @can('peminjaman.create')
            <a href="{{ route('peminjaman.create') }}" class="nav-link {{ request()->routeIs('peminjaman.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle nav-icon"></i>
                <span>Buat Permohonan</span>
            </a>
            @endcan
            @can('pengembalian.view')
            <a href="{{ route('pengembalian.index') }}" class="nav-link {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
                <i class="fas fa-undo-alt nav-icon"></i>
                <span>Pengembalian</span>
            </a>
            @endcan
        </div>

        <!-- Laporan -->
        @can('laporan.view')
        <div class="nav-section">
            <div class="nav-section-label">Laporan</div>
            <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar nav-icon"></i>
                <span>Laporan & Statistik</span>
            </a>
        </div>
        @endcan

        <!-- Administrasi -->
        <div class="nav-section">
            <div class="nav-section-label">Administrasi</div>
            @can('unit.view')
            <a href="{{ route('unit.index') }}" class="nav-link {{ request()->routeIs('unit.*') ? 'active' : '' }}">
                <i class="fas fa-hospital nav-icon"></i>
                <span>Unit & Poli</span>
            </a>
            @endcan
            @can('pengguna.view')
            <a href="{{ route('pengguna.index') }}" class="nav-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog nav-icon"></i>
                <span>Pengguna</span>
            </a>
            @endcan
            @can('audit.view')
            <a href="{{ route('audit-log.index') }}" class="nav-link {{ request()->routeIs('audit-log.*') ? 'active' : '' }}">
                <i class="fas fa-history nav-icon"></i>
                <span>Log Aktivitas</span>
            </a>
            @endcan
            @can('setting.view')
            <a href="{{ route('setting.index') }}" class="nav-link {{ request()->routeIs('setting.*') ? 'active' : '' }}">
                <i class="fas fa-cog nav-icon"></i>
                <span>Pengaturan</span>
            </a>
            @endcan
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        @php $terlambat = \App\Models\Peminjaman::terlambat()->count(); @endphp
        @if($terlambat > 0)
        <div class="alert-terlambat">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ $terlambat }} dokumen terlambat!</span>
        </div>
        @endif
    </div>
</aside>
```

---

## 🎨 CUSTOM CSS (`public/css/medtrack.css`)

```css
/* =============================================
   MedTrack — Custom Stylesheet
   Klinik Pratama Rawat Inap Husada
   ============================================= */

:root {
    --primary: #1a6f8a;
    --primary-dark: #145a72;
    --primary-light: #e8f4f8;
    --secondary: #2ecc71;
    --accent: #e74c3c;
    --warning: #f39c12;
    --sidebar-bg: #0d2137;
    --sidebar-text: #a8c4d4;
    --sidebar-active: #1a6f8a;
    --sidebar-width: 260px;
    --sidebar-collapsed: 70px;
    --topbar-height: 60px;
    --content-bg: #f0f4f8;
    --card-bg: #ffffff;
    --text-primary: #1a2733;
    --text-secondary: #6c7d8c;
    --border-color: #dce6ed;
    --shadow: 0 2px 12px rgba(26,111,138,0.1);
    --radius: 12px;
    --radius-sm: 8px;
    --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);

    /* Status Colors */
    --status-menunggu: #f39c12;
    --status-disetujui: #3498db;
    --status-dipinjam: #9b59b6;
    --status-selesai: #2ecc71;
    --status-terlambat: #e74c3c;
    --status-ditolak: #95a5a6;
}

/* ============ BASE ============ */
* { box-sizing: border-box; }

body.medtrack-body {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    background-color: var(--content-bg);
    color: var(--text-primary);
    margin: 0;
    overflow-x: hidden;
}

/* ============ SIDEBAR ============ */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: var(--sidebar-width);
    background: var(--sidebar-bg);
    z-index: 1000;
    display: flex;
    flex-direction: column;
    transition: var(--transition);
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.1) transparent;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 20px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    flex-shrink: 0;
}

.brand-icon {
    width: 42px;
    height: 42px;
    background: var(--primary);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.brand-name {
    font-size: 18px;
    font-weight: 700;
    color: white;
    letter-spacing: -0.3px;
    display: block;
}

.brand-subtitle {
    font-size: 11px;
    color: var(--sidebar-text);
    display: block;
}

.sidebar-nav {
    flex: 1;
    padding: 12px 0;
}

.nav-section {
    padding: 0 12px;
    margin-bottom: 4px;
}

.nav-section-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: rgba(168, 196, 212, 0.5);
    padding: 10px 8px 4px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: var(--radius-sm);
    color: var(--sidebar-text);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: var(--transition);
    position: relative;
    margin-bottom: 2px;
}

.nav-link:hover {
    background: rgba(255,255,255,0.07);
    color: white;
}

.nav-link.active {
    background: var(--primary);
    color: white;
    box-shadow: 0 4px 12px rgba(26,111,138,0.4);
}

.nav-icon {
    width: 20px;
    text-align: center;
    font-size: 15px;
    flex-shrink: 0;
}

.nav-badge {
    margin-left: auto;
    background: var(--accent);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 20px;
}

.alert-terlambat {
    margin: 8px 12px 12px;
    background: rgba(231,76,60,0.15);
    border: 1px solid rgba(231,76,60,0.3);
    border-radius: var(--radius-sm);
    padding: 8px 12px;
    color: #ff7675;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ============ CONTENT WRAPPER ============ */
.content-wrapper {
    margin-left: var(--sidebar-width);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    transition: var(--transition);
}

.content-wrapper.sidebar-collapsed {
    margin-left: var(--sidebar-collapsed);
}

/* ============ TOP NAVBAR ============ */
.top-navbar {
    height: var(--topbar-height);
    background: var(--card-bg);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    position: sticky;
    top: 0;
    z-index: 900;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}

.btn-icon {
    width: 38px;
    height: 38px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-color);
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    transition: var(--transition);
    position: relative;
}

.btn-icon:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
}

.badge-notif {
    position: absolute;
    top: -4px;
    right: -4px;
    background: var(--accent);
    color: white;
    font-size: 9px;
    font-weight: 700;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-sm { width: 32px; height: 32px; object-fit: cover; }

.avatar-placeholder {
    width: 32px;
    height: 32px;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
}

.user-name { font-size: 13px; font-weight: 600; color: var(--text-primary); line-height: 1.2; }
.user-role { font-size: 11px; color: var(--text-secondary); }
.user-avatar-btn { background: none; border: none; }

/* ============ MAIN CONTENT ============ */
.main-content {
    flex: 1;
    padding: 24px;
}

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}

.page-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.page-subtitle {
    font-size: 13px;
    color: var(--text-secondary);
    margin: 2px 0 0;
}

/* ============ CARDS ============ */
.card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.card-header-custom {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: white;
    border-radius: var(--radius) var(--radius) 0 0;
}

.card-header-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header-title i {
    color: var(--primary);
}

/* ============ STAT CARDS (Dashboard) ============ */
.stat-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    padding: 20px;
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: var(--transition);
    text-decoration: none;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(26,111,138,0.15);
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.stat-icon.primary { background: var(--primary-light); color: var(--primary); }
.stat-icon.success { background: #e8f8f0; color: #27ae60; }
.stat-icon.warning { background: #fef9e7; color: #f39c12; }
.stat-icon.danger  { background: #fdf2f2; color: #e74c3c; }
.stat-icon.purple  { background: #f3eaff; color: #8e44ad; }

.stat-value {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
}

.stat-label {
    font-size: 13px;
    color: var(--text-secondary);
    margin-top: 2px;
}

.stat-change {
    font-size: 12px;
    margin-top: 4px;
}

.stat-change.up { color: #27ae60; }
.stat-change.down { color: #e74c3c; }

/* ============ STATUS BADGES ============ */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-menunggu_persetujuan { background: #fef9e7; color: #f39c12; border: 1px solid #fdebd0; }
.status-disetujui            { background: #eaf4fd; color: #2980b9; border: 1px solid #d6eaf8; }
.status-ditolak              { background: #f9f9f9; color: #7f8c8d; border: 1px solid #ecf0f1; }
.status-dipinjam             { background: #f3eaff; color: #8e44ad; border: 1px solid #e8daef; }
.status-selesai              { background: #e8f8f0; color: #27ae60; border: 1px solid #d5f0e2; }
.status-terlambat            { background: #fdf2f2; color: #e74c3c; border: 1px solid #fadbd8; }
.status-dikembalikan_sebagian{ background: #e8f6fd; color: #1a6f8a; border: 1px solid #d6edf7; }

/* Status dokumen */
.dok-tersedia { background: #e8f8f0; color: #27ae60; }
.dok-dipinjam { background: #f3eaff; color: #8e44ad; }
.dok-hilang   { background: #fdf2f2; color: #e74c3c; }
.dok-rusak    { background: #fef9e7; color: #f39c12; }

/* ============ TABLES ============ */
.table-custom thead th {
    background: #f8fafc;
    color: var(--text-secondary);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--border-color);
    padding: 12px 16px;
    white-space: nowrap;
}

.table-custom tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    border-color: var(--border-color);
    font-size: 14px;
}

.table-custom tbody tr:hover {
    background-color: #f8faff;
}

/* ============ FORMS ============ */
.form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 6px;
}

.form-label .required {
    color: var(--accent);
    margin-left: 2px;
}

.form-control, .form-select {
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius-sm);
    padding: 9px 12px;
    font-size: 14px;
    transition: var(--transition);
    color: var(--text-primary);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26,111,138,0.12);
}

/* ============ BUTTONS ============ */
.btn-primary-custom {
    background: var(--primary);
    border: none;
    color: white;
    padding: 9px 20px;
    border-radius: var(--radius-sm);
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: var(--transition);
    cursor: pointer;
}

.btn-primary-custom:hover {
    background: var(--primary-dark);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(26,111,138,0.3);
}

/* ============ NOTIFIKASI DROPDOWN ============ */
.notif-dropdown {
    width: 360px;
    max-height: 480px;
    overflow-y: auto;
    padding: 0;
}

.notif-header {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-color);
    font-weight: 700;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.notif-item {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    gap: 12px;
    transition: var(--transition);
    cursor: pointer;
}

.notif-item:hover { background: var(--primary-light); }
.notif-item.unread { background: #f0f8fd; }

.notif-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 15px;
}

/* ============ TIMELINE (Detail Peminjaman) ============ */
.timeline {
    position: relative;
    padding: 0;
    list-style: none;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 18px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border-color);
}

.timeline-item {
    display: flex;
    gap: 16px;
    margin-bottom: 20px;
    position: relative;
}

.timeline-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 15px;
    z-index: 1;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.timeline-content {
    flex: 1;
    background: #f8fafc;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    padding: 12px 16px;
}

.timeline-date {
    font-size: 11px;
    color: var(--text-secondary);
    margin-top: 4px;
}

/* ============ FOOTER ============ */
.main-footer {
    background: var(--card-bg);
    border-top: 1px solid var(--border-color);
    padding: 14px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px;
    color: var(--text-secondary);
}

/* ============ RESPONSIVE ============ */
@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.mobile-open { transform: translateX(0); }
    .content-wrapper { margin-left: 0; }
    .main-content { padding: 16px; }
    .page-header { flex-direction: column; align-items: flex-start; }
}

/* ============ PRINT ============ */
@media print {
    .sidebar, .top-navbar, .main-footer { display: none !important; }
    .content-wrapper { margin: 0; }
    .main-content { padding: 0; }
}

/* ============ SELECT2 CUSTOM ============ */
.select2-container--bootstrap-5 .select2-selection {
    border: 1.5px solid var(--border-color) !important;
    border-radius: var(--radius-sm) !important;
    min-height: 40px !important;
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(26,111,138,0.12) !important;
}

/* ============ DATATABLES CUSTOM ============ */
.dataTables_wrapper .dataTables_filter input {
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius-sm);
    padding: 6px 12px;
    font-size: 13px;
}

.dataTables_wrapper .dataTables_length select {
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius-sm);
    padding: 5px 8px;
}

/* ============ LOADING OVERLAY ============ */
.loading-overlay {
    position: fixed;
    inset: 0;
    background: rgba(255,255,255,0.85);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.loading-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid var(--primary-light);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }
```

---

## 📊 SEEDERS - DATA REAL INDONESIA

### RolePermissionSeeder
```php
// database/seeders/RolePermissionSeeder.php
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nama_role' => 'admin', 'label' => 'Administrator Sistem'],
            ['nama_role' => 'direktur', 'label' => 'Direktur / Pimpinan'],
            ['nama_role' => 'kepala_rekam_medis', 'label' => 'Kepala Rekam Medis'],
            ['nama_role' => 'petugas_arsip', 'label' => 'Petugas Arsip Rekam Medis'],
            ['nama_role' => 'tenaga_kesehatan_internal', 'label' => 'Tenaga Kesehatan Internal'],
            ['nama_role' => 'tenaga_kesehatan_eksternal', 'label' => 'Tenaga Kesehatan Eksternal'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Admin: semua permission
        // Direktur: laporan, approve eksternal
        // Kepala Rekam Medis: semua kecuali pengguna dan setting
        // Petugas Arsip: peminjaman.process, pengembalian.process, rekam_medis.view, pasien.view
        // Tenaga Kesehatan Internal: peminjaman.create, peminjaman.view (milik sendiri)
        // Tenaga Kesehatan Eksternal: peminjaman.create, peminjaman.view (milik sendiri)
    }
}
```

### UserSeeder (Data Indonesia)
```php
// database/seeders/UserSeeder.php
public function run(): void
{
    $users = [
        [
            'nama_lengkap' => 'dr. Hj. Nur Indarti, M.Kes',
            'nik' => '3578054508650003',
            'nip' => '196508451990032001',
            'email' => 'direktur@husada-clinic.id',
            'username' => 'direktur',
            'role' => 'direktur',
            'jabatan' => 'Direktur Klinik',
            'jenis_pengguna' => 'internal',
        ],
        [
            'nama_lengkap' => 'Dewi Ratnasari, A.Md.RMIK',
            'nik' => '3578026210890012',
            'nip' => '198910222015032002',
            'email' => 'kepala.rm@husada-clinic.id',
            'username' => 'kepala_rm',
            'role' => 'kepala_rekam_medis',
            'jabatan' => 'Kepala Rekam Medis',
            'jenis_pengguna' => 'internal',
        ],
        [
            'nama_lengkap' => 'Agus Setiawan',
            'nik' => '3578031501930024',
            'nip' => '199301152016031003',
            'email' => 'petugas1.rm@husada-clinic.id',
            'username' => 'petugas_arsip1',
            'role' => 'petugas_arsip',
            'jabatan' => 'Petugas Arsip',
            'jenis_pengguna' => 'internal',
        ],
        [
            'nama_lengkap' => 'dr. Budi Santoso, Sp.PD',
            'nik' => '3578052807780008',
            'nip' => '197807282005011002',
            'email' => 'dr.budi@husada-clinic.id',
            'username' => 'dr_budi',
            'role' => 'tenaga_kesehatan_internal',
            'jabatan' => 'Dokter Spesialis Penyakit Dalam',
            'spesialisasi' => 'Penyakit Dalam',
            'str_number' => 'STR-1234/PD/2023',
            'jenis_pengguna' => 'internal',
        ],
        [
            'nama_lengkap' => 'dr. Siti Aminah, Sp.OG',
            'nik' => '3578046905850015',
            'nip' => '198505292010022004',
            'email' => 'dr.siti@husada-clinic.id',
            'username' => 'dr_siti',
            'role' => 'tenaga_kesehatan_internal',
            'jabatan' => 'Dokter Spesialis Obstetri & Ginekologi',
            'spesialisasi' => 'Obstetri dan Ginekologi',
            'str_number' => 'STR-5678/OG/2023',
            'jenis_pengguna' => 'internal',
        ],
        [
            'nama_lengkap' => 'Ns. Ratna Dewi, S.Kep',
            'nik' => '3578056212920031',
            'nip' => '199212222016032005',
            'email' => 'ratna.ns@husada-clinic.id',
            'username' => 'ns_ratna',
            'role' => 'tenaga_kesehatan_internal',
            'jabatan' => 'Perawat Ruang Poli Umum',
            'jenis_pengguna' => 'internal',
        ],
        // Eksternal
        [
            'nama_lengkap' => 'dr. Ahmad Fauzi, M.Kes',
            'email' => 'ahmad.fauzi@fkub.ac.id',
            'username' => 'dr_ahmad_ext',
            'role' => 'tenaga_kesehatan_eksternal',
            'jabatan' => 'Peneliti',
            'institusi_asal' => 'Fakultas Kedokteran Universitas Brawijaya',
            'str_number' => 'STR-9012/UMUM/2022',
            'jenis_pengguna' => 'eksternal',
        ],
    ];

    foreach ($users as $userData) {
        $role = Role::where('nama_role', $userData['role'])->first();
        User::create([
            ...$userData,
            'role_id' => $role->id,
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
    }
}
```

### PasienSeeder (Data Pasien Indonesia)
```php
// database/seeders/PasienSeeder.php
// Contoh data pasien real Indonesia
$pasiens = [
    [
        'no_rekam_medis' => 'RM-2024-000001',
        'nik' => '3578051204850003',
        'nama_lengkap' => 'Siti Nurhaliza binti Wahyudi',
        'jenis_kelamin' => 'P',
        'tempat_lahir' => 'Surabaya',
        'tanggal_lahir' => '1985-04-12',
        'golongan_darah' => 'A+',
        'agama' => 'islam',
        'status_pernikahan' => 'menikah',
        'pendidikan' => 'S1',
        'pekerjaan' => 'Guru SD',
        'nama_ibu_kandung' => 'Saminah',
        'alamat_lengkap' => 'Jl. Diponegoro No. 45',
        'rt' => '003', 'rw' => '007',
        'kelurahan' => 'Gubeng',
        'kecamatan' => 'Gubeng',
        'kota_kabupaten' => 'Surabaya',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '60281',
        'no_hp' => '081234567890',
        'jenis_jaminan' => 'bpjs_kesehatan',
        'no_jaminan' => '0001234567890',
        'kelas_jaminan' => 'Kelas 2',
        'nama_kontak_darurat' => 'Wahyudi',
        'hubungan_kontak_darurat' => 'Suami',
        'no_telp_kontak_darurat' => '082345678901',
        'tanggal_registrasi' => '2024-01-15',
    ],
    [
        'no_rekam_medis' => 'RM-2024-000002',
        'nik' => '3578072309780012',
        'nama_lengkap' => 'Bapak Hendra Kusuma',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Malang',
        'tanggal_lahir' => '1978-09-23',
        'golongan_darah' => 'B+',
        'agama' => 'islam',
        'status_pernikahan' => 'menikah',
        'pendidikan' => 'SMA',
        'pekerjaan' => 'Wiraswasta',
        'nama_ibu_kandung' => 'Supriyati',
        'alamat_lengkap' => 'Perum. Griya Asri Blok C-12',
        'rt' => '005', 'rw' => '002',
        'kelurahan' => 'Rungkut Kidul',
        'kecamatan' => 'Rungkut',
        'kota_kabupaten' => 'Surabaya',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '60293',
        'no_hp' => '085678901234',
        'jenis_jaminan' => 'umum',
        'tanggal_registrasi' => '2024-02-03',
    ],
    // ... (50+ data pasien)
];
```

### UnitSeeder
```php
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
```

### SettingSeeder
```php
$settings = [
    // Umum
    ['key' => 'nama_klinik', 'value' => 'Klinik Pratama Rawat Inap Husada', 'label' => 'Nama Klinik', 'grup' => 'umum'],
    ['key' => 'alamat_klinik', 'value' => 'Jl. Pahlawan No. 10, Surabaya, Jawa Timur 60272', 'label' => 'Alamat Klinik', 'grup' => 'umum'],
    ['key' => 'no_telp_klinik', 'value' => '(031) 5678-9012', 'label' => 'No. Telepon Klinik', 'grup' => 'umum'],
    ['key' => 'email_klinik', 'value' => 'info@husada-clinic.id', 'label' => 'Email Klinik', 'grup' => 'umum'],
    ['key' => 'logo_klinik', 'value' => null, 'label' => 'Logo Klinik', 'tipe' => 'file', 'grup' => 'umum'],

    // Peminjaman
    ['key' => 'max_durasi_pinjam_internal', 'value' => '3', 'label' => 'Maks. Hari Pinjam Internal', 'tipe' => 'number', 'grup' => 'peminjaman'],
    ['key' => 'max_durasi_pinjam_eksternal', 'value' => '1', 'label' => 'Maks. Hari Pinjam Eksternal', 'tipe' => 'number', 'grup' => 'peminjaman'],
    ['key' => 'max_dokumen_per_peminjaman', 'value' => '10', 'label' => 'Maks. Dokumen per Peminjaman', 'tipe' => 'number', 'grup' => 'peminjaman'],
    ['key' => 'auto_reminder_hari', 'value' => '1', 'label' => 'Kirim Reminder H- (hari)', 'tipe' => 'number', 'grup' => 'peminjaman'],
    ['key' => 'require_approval_internal', 'value' => '1', 'label' => 'Wajib Persetujuan (Internal)', 'tipe' => 'boolean', 'grup' => 'peminjaman'],
    ['key' => 'require_surat_eksternal', 'value' => '1', 'label' => 'Wajib Surat Permohonan (Eksternal)', 'tipe' => 'boolean', 'grup' => 'peminjaman'],

    // Retensi
    ['key' => 'retensi_rawat_jalan_tahun', 'value' => '5', 'label' => 'Masa Retensi Rawat Jalan (Tahun)', 'tipe' => 'number', 'grup' => 'retensi'],
    ['key' => 'retensi_rawat_inap_tahun', 'value' => '10', 'label' => 'Masa Retensi Rawat Inap (Tahun)', 'tipe' => 'number', 'grup' => 'retensi'],

    // Format Nomor
    ['key' => 'format_no_rekam_medis', 'value' => 'RM-{YEAR}-{SEQ6}', 'label' => 'Format No. Rekam Medis', 'grup' => 'format'],
    ['key' => 'format_no_peminjaman', 'value' => 'PJM-{YYYYMMDD}-{SEQ4}', 'label' => 'Format No. Peminjaman', 'grup' => 'format'],
    ['key' => 'format_no_pengembalian', 'value' => 'KBL-{YYYYMMDD}-{SEQ4}', 'label' => 'Format No. Pengembalian', 'grup' => 'format'],
];
```

---

## ⚙️ SERVICES

### NomorRekamMedisService
```php
// app/Services/NomorRekamMedisService.php
class NomorRekamMedisService
{
    public function generateNomorRM(): string
    {
        $tahun = now()->year;
        $lastRM = Pasien::whereYear('created_at', $tahun)
                        ->orderByDesc('no_rekam_medis')
                        ->lockForUpdate()
                        ->first();

        if ($lastRM) {
            $lastNum = (int) substr($lastRM->no_rekam_medis, -6);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return 'RM-' . $tahun . '-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
    }

    public function generateNomorPeminjaman(): string
    {
        $today = now()->format('Ymd');
        $lastPJM = Peminjaman::where('no_peminjaman', 'like', "PJM-{$today}-%")
                              ->orderByDesc('no_peminjaman')
                              ->lockForUpdate()
                              ->first();

        $nextNum = $lastPJM
            ? (int) substr($lastPJM->no_peminjaman, -4) + 1
            : 1;

        return 'PJM-' . $today . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    public function generateNomorPengembalian(): string
    {
        $today = now()->format('Ymd');
        $last = Pengembalian::where('no_pengembalian', 'like', "KBL-{$today}-%")
                             ->orderByDesc('no_pengembalian')
                             ->lockForUpdate()
                             ->first();

        $nextNum = $last
            ? (int) substr($last->no_pengembalian, -4) + 1
            : 1;

        return 'KBL-' . $today . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }
}
```

### PeminjamanService
```php
// app/Services/PeminjamanService.php
class PeminjamanService
{
    public function __construct(
        private NomorRekamMedisService $nomorService
    ) {}

    public function buatPeminjaman(array $data, User $peminjam): Peminjaman
    {
        return DB::transaction(function () use ($data, $peminjam) {
            // Cek ketersediaan semua dokumen
            foreach ($data['rekam_medis_ids'] as $rmId) {
                $rm = RekamMedis::lockForUpdate()->findOrFail($rmId);
                if (!$rm->isTersedia()) {
                    throw new \Exception("Dokumen {$rm->kode_dokumen} sedang tidak tersedia (status: {$rm->status_dokumen})");
                }
            }

            $peminjaman = Peminjaman::create([
                'no_peminjaman' => $this->nomorService->generateNomorPeminjaman(),
                'peminjam_id' => $peminjam->id,
                'jenis_peminjam' => $peminjam->jenis_pengguna,
                'tujuan_peminjaman' => $data['tujuan_peminjaman'],
                'keperluan_detail' => $data['keperluan_detail'],
                'tanggal_pinjam' => $data['tanggal_pinjam'],
                'tanggal_kembali_rencana' => $data['tanggal_kembali_rencana'],
                'no_surat_permohonan' => $data['no_surat_permohonan'] ?? null,
                'is_pengadilan' => $data['is_pengadilan'] ?? false,
                'status_peminjaman' => 'menunggu_persetujuan',
            ]);

            // Attach rekam medis ke peminjaman
            foreach ($data['rekam_medis_ids'] as $rmId) {
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'rekam_medis_id' => $rmId,
                    'status_detail' => 'dipinjam',
                ]);
            }

            // Kirim notifikasi ke approver
            $approvers = User::whereHas('role', function($q) {
                $q->whereIn('nama_role', ['kepala_rekam_medis', 'admin']);
            })->get();

            foreach ($approvers as $approver) {
                $approver->notify(new PeminjamanMenungguPersetujuan($peminjaman));
            }

            // Audit log
            AuditLog::create([
                'user_id' => $peminjam->id,
                'aksi' => 'create',
                'modul' => 'peminjaman',
                'model_type' => Peminjaman::class,
                'model_id' => $peminjaman->id,
                'data_baru' => $peminjaman->toArray(),
                'keterangan' => "Buat permohonan peminjaman #{$peminjaman->no_peminjaman}",
            ]);

            return $peminjaman;
        });
    }

    public function setujuiPeminjaman(Peminjaman $peminjaman, User $approver, ?string $catatan = null): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $approver, $catatan) {
            $peminjaman->update([
                'status_peminjaman' => 'disetujui',
                'disetujui_oleh' => $approver->id,
                'tanggal_disetujui' => now(),
                'catatan_persetujuan' => $catatan,
            ]);

            // Notifikasi ke peminjam
            $peminjaman->peminjam->notify(new PeminjamanDisetujui($peminjaman));

            // Notifikasi ke petugas arsip
            User::whereHas('role', fn($q) => $q->where('nama_role', 'petugas_arsip'))
                ->each(fn($petugas) => $petugas->notify(new PeminjamanSiapDiproses($peminjaman)));

            AuditLog::create([
                'user_id' => $approver->id,
                'aksi' => 'approve',
                'modul' => 'peminjaman',
                'model_id' => $peminjaman->id,
                'keterangan' => "Menyetujui peminjaman #{$peminjaman->no_peminjaman}",
            ]);

            return $peminjaman;
        });
    }

    public function prosesPeminjaman(Peminjaman $peminjaman, User $petugas): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $petugas) {
            $peminjaman->update([
                'status_peminjaman' => 'dipinjam',
                'petugas_peminjaman_id' => $petugas->id,
            ]);

            // Update status semua rekam medis ke 'dipinjam'
            foreach ($peminjaman->detailPeminjamans as $detail) {
                $detail->rekamMedis->update(['status_dokumen' => 'dipinjam']);
            }

            AuditLog::create([
                'user_id' => $petugas->id,
                'aksi' => 'process',
                'modul' => 'peminjaman',
                'model_id' => $peminjaman->id,
                'keterangan' => "Memproses pengeluaran dokumen peminjaman #{$peminjaman->no_peminjaman}",
            ]);

            return $peminjaman;
        });
    }

    public function prosesPengembalian(Peminjaman $peminjaman, array $data, User $petugas): Pengembalian
    {
        return DB::transaction(function () use ($peminjaman, $data, $petugas) {
            $isTerlambat = now()->toDateString() > $peminjaman->tanggal_kembali_rencana;
            $hariTerlambat = $isTerlambat ? now()->diffInDays($peminjaman->tanggal_kembali_rencana) : 0;

            $pengembalian = Pengembalian::create([
                'no_pengembalian' => app(NomorRekamMedisService::class)->generateNomorPengembalian(),
                'peminjaman_id' => $peminjaman->id,
                'tanggal_pengembalian' => $data['tanggal_pengembalian'],
                'petugas_id' => $petugas->id,
                'jumlah_dokumen_kembali' => count($data['detail_kembali']),
                'catatan_pengembalian' => $data['catatan'] ?? null,
                'is_terlambat' => $isTerlambat,
                'hari_terlambat' => $hariTerlambat,
            ]);

            // Update detail per dokumen
            $allSelesai = true;
            foreach ($data['detail_kembali'] as $detailData) {
                $detail = DetailPeminjaman::where('peminjaman_id', $peminjaman->id)
                                          ->where('rekam_medis_id', $detailData['rekam_medis_id'])
                                          ->first();

                $detail->update([
                    'status_detail' => $detailData['status'], // dikembalikan, hilang, rusak
                    'tanggal_dikembalikan' => now(),
                    'kondisi_kembali' => $detailData['kondisi'],
                    'catatan_detail' => $detailData['catatan'] ?? null,
                    'dikembalikan_oleh' => $petugas->id,
                ]);

                // Update status rekam medis
                $statusRM = match($detailData['status']) {
                    'dikembalikan' => 'tersedia',
                    'hilang' => 'hilang',
                    'rusak' => 'rusak',
                    default => 'tersedia',
                };

                $detail->rekamMedis->update(['status_dokumen' => $statusRM]);

                if ($detailData['status'] !== 'dikembalikan') {
                    $allSelesai = false;
                }
            }

            // Update status peminjaman
            $totalDokumen = $peminjaman->detailPeminjamans()->count();
            $dikembalikan = $peminjaman->detailPeminjamans()->where('status_detail', 'dikembalikan')->count();

            if ($dikembalikan >= $totalDokumen) {
                $peminjaman->update([
                    'status_peminjaman' => 'selesai',
                    'tanggal_kembali_aktual' => $data['tanggal_pengembalian'],
                    'petugas_pengembalian_id' => $petugas->id,
                ]);
            } else {
                $peminjaman->update(['status_peminjaman' => 'dikembalikan_sebagian']);
            }

            return $pengembalian;
        });
    }
}
```

---

## 🖥️ VIEWS - KEY PAGES

### Dashboard View
```blade
{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Selamat datang, {{ auth()->user()->nama_lengkap }} — {{ now()->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
    @can('laporan.export')
    <a href="{{ route('laporan.rekap-bulanan') }}" class="btn-primary-custom">
        <i class="fas fa-file-excel"></i> Rekap Bulanan
    </a>
    @endcan
</div>

<!-- Statistik Utama -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('pasien.index') }}" class="stat-card">
            <div class="stat-icon primary"><i class="fas fa-user-injured"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_pasien']) }}</div>
                <div class="stat-label">Total Pasien</div>
                <div class="stat-change up"><i class="fas fa-arrow-up me-1"></i>{{ $stats['pasien_baru_bulan_ini'] }} bulan ini</div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('rekam-medis.index') }}" class="stat-card">
            <div class="stat-icon success"><i class="fas fa-file-medical"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_dokumen']) }}</div>
                <div class="stat-label">Total Dokumen RM</div>
                <div class="stat-change">{{ $stats['dokumen_tersedia'] }} tersedia</div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('peminjaman.index') }}" class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-hand-holding-medical"></i></div>
            <div>
                <div class="stat-value">{{ $stats['peminjaman_aktif'] }}</div>
                <div class="stat-label">Peminjaman Aktif</div>
                <div class="stat-change">{{ $stats['menunggu_persetujuan'] }} menunggu</div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('peminjaman.terlambat') }}" class="stat-card">
            <div class="stat-icon danger"><i class="fas fa-exclamation-circle"></i></div>
            <div>
                <div class="stat-value">{{ $stats['terlambat'] }}</div>
                <div class="stat-label">Dokumen Terlambat</div>
                <div class="stat-change down">Perlu tindakan segera</div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Peminjaman Menunggu Persetujuan -->
    @can('peminjaman.approve')
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">
                    <i class="fas fa-clock"></i>
                    Menunggu Persetujuan
                    @if($menungguPersetujuan->count() > 0)
                    <span class="badge bg-warning text-dark ms-2">{{ $menungguPersetujuan->count() }}</span>
                    @endif
                </div>
                <a href="{{ route('peminjaman.menunggu') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($menungguPersetujuan as $pm)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div class="avatar-placeholder shrink-0
                        {{ strtoupper(substr($pm->peminjam->nama_lengkap, 0, 1)) }}
                    </div>
                    <div class="grow">
                        <div class="fw-semibold" style="font-size:14px">{{ $pm->peminjam->nama_lengkap }}</div>
                        <div style="font-size:12px; color:var(--text-secondary)">
                            {{ $pm->no_peminjaman }} · {{ $pm->rekamMedis->count() }} dokumen · 
                            {{ $pm->tujuan_peminjaman }} · {{ $pm->tanggal_pinjam->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('peminjaman.show', $pm) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn btn-sm btn-success btn-setujui" data-id="{{ $pm->id }}" title="Setujui">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-tolak" data-id="{{ $pm->id }}" title="Tolak">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                    <div>Semua permohonan sudah diproses</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endcan

    <!-- Dokumen Terlambat -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                    Dokumen Terlambat
                </div>
                <a href="{{ route('laporan.terlambat') }}" class="btn btn-sm btn-outline-danger">Detail</a>
            </div>
            <div class="card-body p-0">
                @forelse($terlambat as $pm)
                <div class="p-3 border-bottom">
                    <div class="fw-semibold" style="font-size:13px">{{ $pm->peminjam->nama_lengkap }}</div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <span style="font-size:12px; color:var(--text-secondary)">{{ $pm->no_peminjaman }}</span>
                        <span class="status-badge status-terlambat" style="font-size:11px">
                            <i class="fas fa-clock"></i> {{ $pm->hari_terlambat }} hari
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-smile fa-2x mb-2 text-success"></i>
                    <div>Tidak ada yang terlambat</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Aktivitas Terkini -->
<div class="row g-4 mt-0">
    <div class="col-12">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title"><i class="fas fa-history"></i> Aktivitas Terkini</div>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($aktivitasTerkini as $log)
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $log->aksi == 'create' ? 'bg-primary text-white' : ($log->aksi == 'approve' ? 'bg-success text-white' : 'bg-secondary text-white') }}">
                            <i class="fas {{ $log->aksi == 'create' ? 'fa-plus' : ($log->aksi == 'approve' ? 'fa-check' : 'fa-edit') }}"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="fw-semibold" style="font-size:14px">{{ $log->keterangan }}</div>
                            <div class="timeline-date">
                                <i class="fas fa-user me-1"></i>{{ $log->user->nama_lengkap ?? 'Sistem' }}
                                · <i class="fas fa-clock me-1"></i>{{ $log->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

### Form Peminjaman View
```blade
{{-- resources/views/peminjaman/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Buat Permohonan Peminjaman')

@push('styles')
<style>
.dokumen-terpilih { background: var(--primary-light); border: 2px dashed var(--primary); border-radius: var(--radius); padding: 16px; min-height: 120px; }
.dokumen-item { background: white; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 10px 14px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
.dokumen-item .remove-dokumen { cursor: pointer; color: var(--accent); }
.step-indicator { display: flex; align-items: center; gap: 0; margin-bottom: 32px; }
.step { flex: 1; text-align: center; position: relative; }
.step-circle { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 6px; font-weight: 700; font-size: 14px; }
.step.active .step-circle { background: var(--primary); color: white; }
.step.done .step-circle { background: var(--secondary); color: white; }
.step.pending .step-circle { background: var(--border-color); color: var(--text-secondary); }
.step-label { font-size: 12px; color: var(--text-secondary); }
.step-line { flex: 1; height: 2px; background: var(--border-color); margin-top: -24px; }
.step.done + .step-line { background: var(--secondary); }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Buat Permohonan Peminjaman</h1>
        <p class="page-subtitle">Isi formulir permohonan peminjaman dokumen rekam medis</p>
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<!-- Alur SOP Info -->
<div class="alert alert-info d-flex gap-3 align-items-start mb-4" style="border-radius: var(--radius);">
    <i class="fas fa-info-circle mt-1 shrink-0" style="font-size:18px"></i>
    <div>
        <strong>Informasi SOP Peminjaman:</strong>
        Permohonan peminjaman akan diverifikasi oleh Kepala Rekam Medis. 
        Setelah disetujui, petugas arsip akan memproses pengeluaran dokumen. 
        <strong>Pihak eksternal wajib melampirkan surat permohonan.</strong>
    </div>
</div>

<form id="form-peminjaman" action="{{ route('peminjaman.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <!-- Kiri: Informasi Peminjaman -->
        <div class="col-lg-8">
            <!-- Data Peminjam -->
            <div class="card mb-4">
                <div class="card-header-custom">
                    <div class="card-header-title"><i class="fas fa-user"></i> Data Peminjam</div>
                </div>
                <div class="card-body">
                    @if(auth()->user()->jenis_pengguna === 'internal')
                    <!-- Auto-filled untuk internal -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Peminjam</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->nama_lengkap }}" readonly>
                            <input type="hidden" name="peminjam_id" value="{{ auth()->id() }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit / Bagian</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->unit->nama_unit ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->jabatan }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Peminjam</label>
                            <input type="text" class="form-control" value="Internal - {{ auth()->user()->role->label }}" readonly>
                            <input type="hidden" name="jenis_peminjam" value="internal">
                        </div>
                    </div>
                    @else
                    <!-- Form untuk eksternal -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Peminjam <span class="required">*</span></label>
                            <input type="text" class="form-control @error('nama_peminjam_luar') is-invalid @enderror" 
                                   name="nama_peminjam_luar" value="{{ old('nama_peminjam_luar', auth()->user()->nama_lengkap) }}" required>
                            @error('nama_peminjam_luar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Institusi Asal <span class="required">*</span></label>
                            <input type="text" class="form-control" name="institusi_peminjam" 
                                   value="{{ old('institusi_peminjam', auth()->user()->institusi_asal) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Surat Permohonan <span class="required">*</span></label>
                            <input type="text" class="form-control" name="no_surat_permohonan" 
                                   value="{{ old('no_surat_permohonan') }}" required placeholder="Contoh: 001/FKUB/I/2024">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upload Surat Permohonan <span class="required">*</span></label>
                            <input type="file" class="form-control" name="file_surat_permohonan" 
                                   accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="form-text">Format: PDF/JPG/PNG, maks. 2MB. Ditujukan ke Direktur.</div>
                        </div>
                    </div>
                    <input type="hidden" name="jenis_peminjam" value="eksternal">
                    @endif
                </div>
            </div>

            <!-- Detail Peminjaman -->
            <div class="card mb-4">
                <div class="card-header-custom">
                    <div class="card-header-title"><i class="fas fa-clipboard-list"></i> Detail Permohonan</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tujuan Peminjaman <span class="required">*</span></label>
                            <select class="form-select" name="tujuan_peminjaman" id="tujuan_peminjaman" required>
                                <option value="">-- Pilih Tujuan --</option>
                                <option value="pelayanan" {{ old('tujuan_peminjaman') == 'pelayanan' ? 'selected' : '' }}>Pelayanan Pasien</option>
                                <option value="penelitian" {{ old('tujuan_peminjaman') == 'penelitian' ? 'selected' : '' }}>Penelitian / Studi</option>
                                <option value="audit" {{ old('tujuan_peminjaman') == 'audit' ? 'selected' : '' }}>Audit Medis / Kualitas</option>
                                <option value="pengadilan" {{ old('tujuan_peminjaman') == 'pengadilan' ? 'selected' : '' }}>Keperluan Pengadilan</option>
                                <option value="pendidikan" {{ old('tujuan_peminjaman') == 'pendidikan' ? 'selected' : '' }}>Pendidikan / Pelatihan</option>
                                <option value="asuransi" {{ old('tujuan_peminjaman') == 'asuransi' ? 'selected' : '' }}>Klaim Asuransi</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="field-pengadilan" style="display:none">
                            <label class="form-label">No. Surat Pengadilan</label>
                            <input type="text" class="form-control" name="no_surat_pengadilan" value="{{ old('no_surat_pengadilan') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan Keperluan Lengkap <span class="required">*</span></label>
                            <textarea class="form-control" name="keperluan_detail" rows="3" required 
                                      placeholder="Jelaskan secara detail keperluan peminjaman dokumen rekam medis ini...">{{ old('keperluan_detail') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Pinjam <span class="required">*</span></label>
                            <input type="date" class="form-control" name="tanggal_pinjam" 
                                   value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rencana Pengembalian <span class="required">*</span></label>
                            <input type="date" class="form-control" name="tanggal_kembali_rencana" 
                                   value="{{ old('tanggal_kembali_rencana') }}" min="{{ date('Y-m-d') }}" required id="tgl_kembali">
                            <div class="form-text" id="info-durasi"></div>
                        </div>
                        <div class="col-md-4" id="field-dokter-merawat" style="display:none">
                            <label class="form-label">Dokter yang Merawat</label>
                            <select class="form-select select2-dokter" name="dokter_yang_merawat_id">
                                <option value="">-- Pilih Dokter --</option>
                                @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id }}">{{ $dokter->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Diperlukan untuk izin fotokopi keperluan pengadilan</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pilih Dokumen RM -->
            <div class="card">
                <div class="card-header-custom">
                    <div class="card-header-title"><i class="fas fa-search"></i> Pilih Dokumen Rekam Medis</div>
                </div>
                <div class="card-body">
                    <!-- Cari Pasien -->
                    <div class="mb-3">
                        <label class="form-label">Cari Pasien <span class="required">*</span></label>
                        <select class="form-select select2-pasien" id="select_pasien" style="width:100%">
                            <option value="">Ketik nama, No. RM, atau NIK pasien...</option>
                        </select>
                    </div>

                    <!-- Daftar Dokumen Pasien -->
                    <div id="daftar-dokumen-pasien" style="display:none">
                        <label class="form-label">Dokumen Tersedia untuk Pasien Ini</label>
                        <div id="list-dokumen-pasien" class="row g-2"></div>
                    </div>

                    <!-- Dokumen Terpilih -->
                    <div class="mt-4">
                        <label class="form-label">Dokumen yang Akan Dipinjam</label>
                        <div class="dokumen-terpilih" id="dokumen-terpilih">
                            <div class="text-center text-muted py-3" id="empty-dokumen">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <div style="font-size:13px">Belum ada dokumen dipilih</div>
                            </div>
                        </div>
                        <div id="hidden-inputs"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanan: Summary & Submit -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 80px">
                <div class="card-header-custom">
                    <div class="card-header-title"><i class="fas fa-clipboard-check"></i> Ringkasan Permohonan</div>
                </div>
                <div class="card-body">
                    <div class="summary-item d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="text-muted" style="font-size:13px">Jenis Peminjam</span>
                        <span class="fw-semibold" style="font-size:13px">{{ auth()->user()->jenis_pengguna === 'internal' ? 'Internal' : 'Eksternal' }}</span>
                    </div>
                    <div class="summary-item d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="text-muted" style="font-size:13px">Jumlah Dokumen</span>
                        <span class="fw-semibold" style="font-size:13px" id="summary-jumlah">0 dokumen</span>
                    </div>
                    <div class="summary-item d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="text-muted" style="font-size:13px">Status Persetujuan</span>
                        <span class="status-badge status-menunggu_persetujuan" style="font-size:11px">
                            <i class="fas fa-clock"></i> Menunggu
                        </span>
                    </div>
                    <div class="summary-item d-flex justify-content-between border-bottom pb-2 mb-3">
                        <span class="text-muted" style="font-size:13px">Maks. Durasi</span>
                        <span class="fw-semibold" style="font-size:13px">
                            {{ auth()->user()->jenis_pengguna === 'internal' ? '3 hari' : '1 hari' }}
                        </span>
                    </div>

                    <!-- Info SOP -->
                    <div class="alert alert-warning p-2 mb-3" style="font-size:12px">
                        <i class="fas fa-info-circle me-1"></i>
                        @if(auth()->user()->jenis_pengguna === 'eksternal')
                        Dokumen <strong>tidak dapat dibawa keluar</strong> dari klinik dan <strong>tidak dapat difotokopi</strong> (kecuali keperluan pengadilan).
                        @else
                        Dokumen wajib dikembalikan tepat waktu sesuai jadwal yang disepakati.
                        @endif
                    </div>

                    <button type="submit" class="btn-primary-custom w-100 justify-content-center" id="btn-submit">
                        <i class="fas fa-paper-plane"></i> Kirim Permohonan
                    </button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let dokumenTerpilih = [];

    // Select2 Pasien (AJAX)
    $('#select_pasien').select2({
        theme: 'bootstrap-5',
        placeholder: 'Ketik nama, No. RM, atau NIK pasien...',
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("pasien.select2") }}',
            dataType: 'json',
            delay: 300,
            data: (params) => ({ q: params.term }),
            processResults: (data) => ({
                results: data.map(p => ({
                    id: p.id,
                    text: `${p.no_rekam_medis} — ${p.nama_lengkap} (${p.nik ?? '-'})`,
                    data: p
                }))
            })
        }
    }).on('select2:select', function(e) {
        loadDokumenPasien(e.params.data.data.id);
    });

    // Load dokumen pasien
    function loadDokumenPasien(pasienId) {
        $.get(`/rekam-medis/by-pasien/${pasienId}`, function(data) {
            let html = '';
            if (data.length === 0) {
                html = '<div class="col-12 text-muted text-center py-3">Tidak ada dokumen tersedia untuk pasien ini</div>';
            }
            data.forEach(rm => {
                const sudahDipilih = dokumenTerpilih.find(d => d.id == rm.id);
                html += `
                    <div class="col-md-6">
                        <div class="card border p-2 dokumen-card ${sudahDipilih ? 'border-primary' : ''}" 
                             style="cursor:pointer;font-size:12px" data-rm='${JSON.stringify(rm)}' data-id="${rm.id}">
                            <div class="fw-semibold">${rm.kode_dokumen}</div>
                            <div class="text-muted">${rm.tanggal_kunjungan} · ${rm.poli ?? 'Umum'}</div>
                            <div class="mt-1">
                                <span class="status-badge dok-${rm.status_dokumen}" style="font-size:10px">${rm.status_dokumen}</span>
                                ${rm.status_dokumen !== 'tersedia' ? '<span class="text-danger ms-1"><i class="fas fa-lock"></i></span>' : ''}
                            </div>
                        </div>
                    </div>`;
            });
            $('#list-dokumen-pasien').html(html);
            $('#daftar-dokumen-pasien').show();
        });
    }

    // Klik dokumen
    $(document).on('click', '.dokumen-card', function() {
        const rm = $(this).data('rm');
        if (rm.status_dokumen !== 'tersedia') {
            Swal.fire({ icon: 'warning', title: 'Tidak Tersedia', text: `Dokumen ${rm.kode_dokumen} sedang ${rm.status_dokumen}`, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            return;
        }
        const idx = dokumenTerpilih.findIndex(d => d.id == rm.id);
        if (idx === -1) {
            dokumenTerpilih.push(rm);
            $(this).addClass('border-primary bg-light');
        } else {
            dokumenTerpilih.splice(idx, 1);
            $(this).removeClass('border-primary bg-light');
        }
        renderDokumenTerpilih();
    });

    function renderDokumenTerpilih() {
        const container = $('#dokumen-terpilih');
        const hiddenInputs = $('#hidden-inputs');
        
        if (dokumenTerpilih.length === 0) {
            container.html('<div class="text-center text-muted py-3" id="empty-dokumen"><i class="fas fa-inbox fa-2x mb-2"></i><div style="font-size:13px">Belum ada dokumen dipilih</div></div>');
        } else {
            let html = '';
            let inputs = '';
            dokumenTerpilih.forEach((rm, i) => {
                html += `
                    <div class="dokumen-item">
                        <div>
                            <div class="fw-semibold" style="font-size:13px">${rm.kode_dokumen}</div>
                            <div class="text-muted" style="font-size:11px">${rm.pasien_nama} · ${rm.tanggal_kunjungan}</div>
                        </div>
                        <span class="remove-dokumen" data-id="${rm.id}" title="Hapus">
                            <i class="fas fa-times-circle"></i>
                        </span>
                    </div>`;
                inputs += `<input type="hidden" name="rekam_medis_ids[]" value="${rm.id}">`;
            });
            container.html(html);
            hiddenInputs.html(inputs);
        }
        $('#summary-jumlah').text(`${dokumenTerpilih.length} dokumen`);
    }

    // Remove dokumen
    $(document).on('click', '.remove-dokumen', function() {
        const id = $(this).data('id');
        dokumenTerpilih = dokumenTerpilih.filter(d => d.id != id);
        $(`.dokumen-card[data-id="${id}"]`).removeClass('border-primary bg-light');
        renderDokumenTerpilih();
    });

    // Tujuan Pengadilan
    $('#tujuan_peminjaman').on('change', function() {
        if ($(this).val() === 'pengadilan') {
            $('#field-pengadilan, #field-dokter-merawat').show();
        } else {
            $('#field-pengadilan, #field-dokter-merawat').hide();
        }
    });

    // Hitung durasi
    $('input[name="tanggal_pinjam"], #tgl_kembali').on('change', function() {
        const tglPinjam = $('input[name="tanggal_pinjam"]').val();
        const tglKembali = $('#tgl_kembali').val();
        if (tglPinjam && tglKembali) {
            const diff = Math.ceil((new Date(tglKembali) - new Date(tglPinjam)) / (1000*60*60*24));
            const maxDur = {{ auth()->user()->jenis_pengguna === 'internal' ? 3 : 1 }};
            if (diff > maxDur) {
                $('#info-durasi').html(`<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Melebihi batas maksimal ${maxDur} hari!</span>`);
            } else if (diff > 0) {
                $('#info-durasi').html(`<span class="text-success"><i class="fas fa-check"></i> Durasi ${diff} hari</span>`);
            }
        }
    });

    // Submit validation
    $('#form-peminjaman').on('submit', function(e) {
        if (dokumenTerpilih.length === 0) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Pilih minimal 1 dokumen rekam medis!' });
            return;
        }
        Swal.fire({ title: 'Mengirim Permohonan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    });
});
</script>
@endpush
```

---

## 🔔 NOTIFIKASI & COMMANDS

### Command: SendReturnReminder
```php
// app/Console/Commands/SendReturnReminder.php
class SendReturnReminder extends Command
{
    protected $signature = 'medtrack:reminder-pengembalian';
    protected $description = 'Kirim reminder pengembalian dokumen rekam medis';

    public function handle(): void
    {
        $reminderHari = (int) Setting::getValue('auto_reminder_hari', 1);

        $peminjamans = Peminjaman::where('status_peminjaman', 'dipinjam')
            ->whereDate('tanggal_kembali_rencana', now()->addDays($reminderHari)->toDateString())
            ->with(['peminjam', 'rekamMedis'])
            ->get();

        foreach ($peminjamans as $pm) {
            $pm->peminjam->notify(new ReminderPengembalian($pm));
            $this->info("Reminder terkirim ke: {$pm->peminjam->nama_lengkap} untuk {$pm->no_peminjaman}");
        }

        // Tandai peminjaman yang sudah terlambat
        Peminjaman::where('status_peminjaman', 'dipinjam')
            ->where('tanggal_kembali_rencana', '<', now()->toDateString())
            ->update(['status_peminjaman' => 'terlambat']);

        $this->info('Selesai: ' . $peminjamans->count() . ' reminder terkirim.');
    }
}

// Daftarkan di Kernel atau schedule:
// Schedule::command('medtrack:reminder-pengembalian')->dailyAt('07:00');
```

---

## 📄 LAPORAN & EXPORT

### LaporanController
```php
// app/Http/Controllers/LaporanController.php
class LaporanController extends Controller
{
    public function peminjaman(Request $request)
    {
        $query = Peminjaman::with(['peminjam.unit', 'rekamMedis.pasien', 'disetujuiOleh'])
            ->when($request->tanggal_dari, fn($q) => $q->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari))
            ->when($request->tanggal_sampai, fn($q) => $q->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai))
            ->when($request->status, fn($q) => $q->where('status_peminjaman', $request->status))
            ->when($request->jenis_peminjam, fn($q) => $q->where('jenis_peminjam', $request->jenis_peminjam))
            ->when($request->tujuan, fn($q) => $q->where('tujuan_peminjaman', $request->tujuan))
            ->orderByDesc('created_at');

        if ($request->export === 'excel') {
            return Excel::download(new PeminjamanExport($query->get()), 'laporan-peminjaman-'.now()->format('Ymd').'.xlsx');
        }

        if ($request->export === 'pdf') {
            $data = $query->get();
            $pdf = Pdf::loadView('laporan.peminjaman-pdf', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-peminjaman-'.now()->format('Ymd').'.pdf');
        }

        return view('laporan.peminjaman', [
            'peminjamans' => $query->paginate(20)->withQueryString(),
            'statistik' => [
                'total' => $query->count(),
                'disetujui' => $query->clone()->where('status_peminjaman', 'disetujui')->count(),
                'dipinjam' => $query->clone()->where('status_peminjaman', 'dipinjam')->count(),
                'terlambat' => $query->clone()->where('status_peminjaman', 'terlambat')->count(),
                'selesai' => $query->clone()->where('status_peminjaman', 'selesai')->count(),
            ]
        ]);
    }

    public function rekapBulanan(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        return view('laporan.rekap-bulanan', [
            'peminjaman_per_hari' => Peminjaman::selectRaw('DATE(tanggal_pinjam) as tanggal, COUNT(*) as total')
                ->whereMonth('tanggal_pinjam', $bulan)->whereYear('tanggal_pinjam', $tahun)
                ->groupBy('tanggal')->orderBy('tanggal')->get(),
            'top_peminjam' => User::withCount(['peminjamans' => fn($q) => $q->whereMonth('tanggal_pinjam', $bulan)->whereYear('tanggal_pinjam', $tahun)])
                ->orderByDesc('peminjamans_count')->limit(10)->get(),
            'per_tujuan' => Peminjaman::selectRaw('tujuan_peminjaman, COUNT(*) as total')
                ->whereMonth('tanggal_pinjam', $bulan)->whereYear('tanggal_pinjam', $tahun)
                ->groupBy('tujuan_peminjaman')->get(),
            'per_unit' => Unit::withCount(['users as total_peminjaman' => fn($q) => 
                $q->whereHas('peminjamans', fn($pq) => $pq->whereMonth('tanggal_pinjam', $bulan)->whereYear('tanggal_pinjam', $tahun))
            ])->orderByDesc('total_peminjaman')->limit(10)->get(),
        ]);
    }
}
```

---

## 🔧 MIDDLEWARE

```php
// app/Http/Middleware/CheckPermission.php
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check() || !auth()->user()->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki izin untuk tindakan ini.'], 403);
            }
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk tindakan ini.');
        }
        return $next($request);
    }
}
```

---

## 📦 INSTALLATION COMMANDS

```bash
# 1. Buat project baru
composer create-project laravel/laravel medtrack

cd medtrack

# 2. Install dependencies tambahan
composer require maatwebsite/excel          # Export Excel
composer require barryvdh/laravel-dompdf   # Export PDF
composer require spatie/laravel-activitylog # Alternatif audit log
composer require intervention/image        # Upload foto

# 3. Install Laravel Breeze (auth)
composer require laravel/breeze --dev
php artisan breeze:install blade

# 4. Setup database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medtrack_husada
DB_USERNAME=root
DB_PASSWORD=

# 5. Generate key
php artisan key:generate

# 6. Jalankan migration & seeder
php artisan migrate --seed

# 7. Link storage
php artisan storage:link

# 8. Setup scheduler (tambah ke crontab)
# * * * * * cd /path/to/medtrack && php artisan schedule:run >> /dev/null 2>&1

# 9. Build assets
npm install && npm run build

# 10. Jalankan server
php artisan serve
```

---

## 🗂️ DEFAULT DATA LOGIN

| Username | Password | Role |
|---|---|---|
| `direktur` | `password123` | Direktur |
| `kepala_rm` | `password123` | Kepala Rekam Medis |
| `petugas_arsip1` | `password123` | Petugas Arsip |
| `dr_budi` | `password123` | Tenaga Kesehatan Internal |
| `dr_siti` | `password123` | Tenaga Kesehatan Internal |
| `ns_ratna` | `password123` | Tenaga Kesehatan Internal |
| `dr_ahmad_ext` | `password123` | Tenaga Kesehatan Eksternal |

---

## ✅ FITUR LENGKAP CHECKLIST

### Manajemen Data
- [x] CRUD Pasien dengan soft delete
- [x] Generate No. Rekam Medis otomatis (RM-YYYY-XXXXXX)
- [x] CRUD Rekam Medis per kunjungan
- [x] Manajemen lokasi penyimpanan fisik (rak, laci, folder)
- [x] Cetak label dokumen
- [x] Import/Export data pasien (Excel)
- [x] Manajemen retensi dokumen

### Peminjaman (Sesuai SOP)
- [x] Permohonan peminjaman internal (tanpa surat)
- [x] Permohonan peminjaman eksternal (wajib surat ke direktur)
- [x] Alur persetujuan bertingkat (Kepala Rekam Medis)
- [x] Batch peminjaman (multi-dokumen)
- [x] Validasi ketersediaan dokumen real-time
- [x] Pembatasan fotokopi (hanya pengadilan dengan izin dokter)
- [x] Pembatasan bawa keluar (dokumen eksternal)
- [x] Cetak formulir blanko peminjaman
- [x] Status tracking lengkap

### Pengembalian
- [x] Pengembalian per dokumen
- [x] Input kondisi dokumen saat kembali
- [x] Pengembalian sebagian
- [x] Deteksi keterlambatan otomatis
- [x] Cetak tanda terima pengembalian
- [x] Update lokasi penyimpanan otomatis

### Notifikasi & Reminder
- [x] Notifikasi in-app (dropdown)
- [x] Reminder otomatis H-1 pengembalian (email)
- [x] Notifikasi persetujuan ke peminjam
- [x] Notifikasi ke petugas arsip setelah disetujui
- [x] Alert terlambat di dashboard

### Laporan
- [x] Laporan peminjaman (filter tanggal, status, jenis)
- [x] Laporan pengembalian
- [x] Laporan dokumen terlambat
- [x] Rekap bulanan dengan grafik
- [x] Statistik per poli/unit
- [x] Top peminjam
- [x] Export Excel dan PDF

### Keamanan & Audit
- [x] RBAC dengan 6 role
- [x] Permission granular per fitur
- [x] Audit log semua aktivitas
- [x] Soft delete (data tidak dihapus permanen)
- [x] Input validation dengan Form Request
- [x] CSRF protection
- [x] XSS prevention

### UI/UX
- [x] Responsive (mobile friendly)
- [x] Sidebar dengan badge notifikasi
- [x] SweetAlert untuk konfirmasi dan toast
- [x] DataTables dengan filter, sort, export
- [x] Select2 untuk pencarian pasien dan dokumen
- [x] Loading state
- [x] Print layout
- [x] Dark mode ready (via CSS variables)

---

*Dokumen ini dibuat sebagai panduan implementasi sistem MedTrack untuk Klinik Pratama Rawat Inap Husada. Sesuai SOP Peminjaman Dokumen Rekam Medis berdasarkan Permenkes No. 11/2017 dan Permenkes No. 269/2008.*
