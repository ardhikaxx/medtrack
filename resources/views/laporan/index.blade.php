@extends('layouts.app')
@section('title', 'Laporan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan & Statistik</h1>
        <p class="page-subtitle">Pantau statistik peminjaman dan pengembalian</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('pasien.index') }}" class="stat-card stat-card-pasien" style="text-decoration: none;">
            <div class="stat-card-decor-circle stat-card-decor-circle-1"></div>
            <div class="stat-card-decor-circle stat-card-decor-circle-2"></div>
            <div class="stat-card-inner">
                <div class="stat-card-icon-main">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ number_format($stats['total_pasien']) }}</div>
                    <div class="stat-card-title">Total Pasien</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-user-injured"></i>
                        <span>Pasien terdaftar</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('rekam-medis.index') }}" class="stat-card stat-card-dokumen" style="text-decoration: none;">
            <div class="stat-card-decor-circle stat-card-decor-circle-1"></div>
            <div class="stat-card-decor-circle stat-card-decor-circle-2"></div>
            <div class="stat-card-inner">
                <div class="stat-card-icon-main">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ number_format($stats['total_dokumen']) }}</div>
                    <div class="stat-card-title">Total Dokumen</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-folder-open"></i>
                        <span>Dokumen RM</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('peminjaman.index') }}" class="stat-card stat-card-peminjaman" style="text-decoration: none;">
            <div class="stat-card-decor-circle stat-card-decor-circle-1"></div>
            <div class="stat-card-decor-circle stat-card-decor-circle-2"></div>
            <div class="stat-card-inner">
                <div class="stat-card-icon-main">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ $stats['total_peminjaman'] }}</div>
                    <div class="stat-card-title">Total Peminjaman</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-exchange-alt"></i>
                        <span>Semua transaksi</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('peminjaman.terlambat') }}" class="stat-card stat-card-terlambat" style="text-decoration: none;">
            <div class="stat-card-decor-circle stat-card-decor-circle-1"></div>
            <div class="stat-card-decor-circle stat-card-decor-circle-2"></div>
            <div class="stat-card-inner">
                <div class="stat-card-icon-main">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ $stats['terlambat'] }}</div>
                    <div class="stat-card-title">Terlambat</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-calendar-times"></i>
                        <span>Perlu perhatian</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <a href="{{ route('laporan.peminjaman') }}" class="menu-card menu-card-peminjaman" style="text-decoration: none;">
            <div class="menu-card-decoration">
                <div class="menu-card-decor-1"></div>
                <div class="menu-card-decor-2"></div>
            </div>
            <div class="menu-card-icon">
                <i class="fas fa-file-signature"></i>
            </div>
            <div class="menu-card-content">
                <h3 class="menu-card-title">Laporan Peminjaman</h3>
                <p class="menu-card-desc">Lihat data lengkap peminjaman dokumen rekam medis</p>
            </div>
            <div class="menu-card-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('laporan.pengembalian') }}" class="menu-card menu-card-pengembalian" style="text-decoration: none;">
            <div class="menu-card-decoration">
                <div class="menu-card-decor-1"></div>
                <div class="menu-card-decor-2"></div>
            </div>
            <div class="menu-card-icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="menu-card-content">
                <h3 class="menu-card-title">Laporan Pengembalian</h3>
                <p class="menu-card-desc">Riwayat pengembalian dokumen RM</p>
            </div>
            <div class="menu-card-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('laporan.terlambat') }}" class="menu-card menu-card-terlambat" style="text-decoration: none;">
            <div class="menu-card-decoration">
                <div class="menu-card-decor-1"></div>
                <div class="menu-card-decor-2"></div>
            </div>
            <div class="menu-card-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="menu-card-content">
                <h3 class="menu-card-title">Dokumen Terlambat</h3>
                <p class="menu-card-desc">Daftar dokumen yang melebihi batas waktu</p>
            </div>
            <div class="menu-card-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('laporan.rekap-bulanan') }}" class="menu-card menu-card-rekap" style="text-decoration: none;">
            <div class="menu-card-decoration">
                <div class="menu-card-decor-1"></div>
                <div class="menu-card-decor-2"></div>
            </div>
            <div class="menu-card-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="menu-card-content">
                <h3 class="menu-card-title">Rekap Bulanan</h3>
                <p class="menu-card-desc">Ringkasan aktivitas bulanan</p>
            </div>
            <div class="menu-card-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>
</div>
@endsection
