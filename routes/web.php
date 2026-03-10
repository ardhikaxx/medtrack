<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ScannerController;

Route::get('/api/wilayah/provinces', [WilayahController::class, 'provinces']);
Route::get('/api/wilayah/regencies/{provinceId}', [WilayahController::class, 'regencies']);
Route::get('/api/wilayah/districts/{regencyId}', [WilayahController::class, 'districts']);
Route::get('/api/wilayah/villages/{districtId}', [WilayahController::class, 'villages']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('pasien')->name('pasien.')->group(function () {
        Route::get('/', [PasienController::class, 'index'])->name('index');
        Route::get('/create', [PasienController::class, 'create'])->name('create');
        Route::post('/', [PasienController::class, 'store'])->name('store');
        Route::get('/{pasien}', [PasienController::class, 'show'])->name('show');
        Route::get('/{pasien}/edit', [PasienController::class, 'edit'])->name('edit');
        Route::put('/{pasien}', [PasienController::class, 'update'])->name('update');
        Route::delete('/{pasien}', [PasienController::class, 'destroy'])->name('destroy');
        Route::get('/search/select2', [PasienController::class, 'select2'])->name('select2');
    });

    Route::prefix('rekam-medis')->name('rekam-medis.')->group(function () {
        Route::get('/', [RekamMedisController::class, 'index'])->name('index');
        Route::get('/create', [RekamMedisController::class, 'create'])->name('create');
        Route::post('/', [RekamMedisController::class, 'store'])->name('store');
        Route::get('/{rekamMedis}', [RekamMedisController::class, 'show'])->name('show');
        Route::get('/{rekamMedis}/edit', [RekamMedisController::class, 'edit'])->name('edit');
        Route::put('/{rekamMedis}', [RekamMedisController::class, 'update'])->name('update');
        Route::delete('/{rekamMedis}', [RekamMedisController::class, 'destroy'])->name('destroy');
        Route::get('/by-pasien/{pasien_id}', [RekamMedisController::class, 'byPasien'])->name('by-pasien');
    });

    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [PeminjamanController::class, 'index'])->name('index');
        Route::get('/create', [PeminjamanController::class, 'create'])->name('create');
        Route::post('/', [PeminjamanController::class, 'store'])->name('store');
        Route::get('/menunggu', [PeminjamanController::class, 'menunggu'])->name('menunggu');
        Route::get('/terlambat', [PeminjamanController::class, 'terlambat'])->name('terlambat');
        Route::get('/{peminjaman}', [PeminjamanController::class, 'show'])->name('show');
        Route::post('/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('setujui');
        Route::post('/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])->name('tolak');
        Route::post('/{peminjaman}/proses', [PeminjamanController::class, 'proses'])->name('proses');
        Route::post('/{peminjaman}/batalkan', [PeminjamanController::class, 'batalkan'])->name('batalkan');
    });

    Route::prefix('pengembalian')->name('pengembalian.')->group(function () {
        Route::get('/', [PengembalianController::class, 'index'])->name('index');
        Route::get('/create/{peminjaman}', [PengembalianController::class, 'create'])->name('create');
        Route::post('/create/{peminjaman}', [PengembalianController::class, 'store'])->name('store');
        Route::get('/{pengembalian}', [PengembalianController::class, 'show'])->name('show');
    });

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/peminjaman', [LaporanController::class, 'peminjaman'])->name('peminjaman');
        Route::get('/pengembalian', [LaporanController::class, 'pengembalian'])->name('pengembalian');
        Route::get('/terlambat', [LaporanController::class, 'terlambat'])->name('terlambat');
        Route::get('/statistik-dokumen', [LaporanController::class, 'statistikDokumen'])->name('statistik-dokumen');
        Route::get('/rekap-bulanan', [LaporanController::class, 'rekapBulanan'])->name('rekap-bulanan');
    });

    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/peminjaman/excel', [ExportController::class, 'peminjamanExcel'])->name('peminjaman.excel');
        Route::get('/peminjaman/pdf', [ExportController::class, 'peminjamanPdf'])->name('peminjaman.pdf');
        Route::get('/pengembalian/excel', [ExportController::class, 'pengembalianExcel'])->name('pengembalian.excel');
        Route::get('/pengembalian/pdf', [ExportController::class, 'pengembalianPdf'])->name('pengembalian.pdf');
        Route::get('/rekap-bulanan/pdf', [ExportController::class, 'rekapBulanan'])->name('rekap-bulanan.pdf');
    });

    Route::prefix('pengguna')->name('pengguna.')->group(function () {
        Route::get('/', [PenggunaController::class, 'index'])->name('index');
        Route::get('/create', [PenggunaController::class, 'create'])->name('create');
        Route::post('/', [PenggunaController::class, 'store'])->name('store');
        Route::get('/{user}', [PenggunaController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [PenggunaController::class, 'edit'])->name('edit');
        Route::put('/{user}', [PenggunaController::class, 'update'])->name('update');
        Route::delete('/{user}', [PenggunaController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle-aktif', [PenggunaController::class, 'toggleAktif'])->name('toggle-aktif');
        Route::post('/{user}/reset-password', [PenggunaController::class, 'resetPassword'])->name('reset-password');
    });

    Route::resource('unit', UnitController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ProfilController::class, 'index'])->name('index');
        Route::put('/update', [ProfilController::class, 'update'])->name('update');
        Route::put('/password', [ProfilController::class, 'updatePassword'])->name('password');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllRead');
    });

    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/results', [SearchController::class, 'results'])->name('search.results');

    Route::get('/calendar', [DashboardController::class, 'calendar'])->name('calendar');

    Route::get('/scanner', [ScannerController::class, 'index'])->name('scanner');
    Route::get('/scanner/lookup', [ScannerController::class, 'lookup'])->name('scanner.lookup');
});
