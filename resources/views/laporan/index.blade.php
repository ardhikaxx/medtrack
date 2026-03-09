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

<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fas fa-users"></i></div>
            <div><div class="stat-value">{{ number_format($stats['total_pasien']) }}</div><div class="stat-label">Total Pasien</div></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon success"><i class="fas fa-file-medical"></i></div>
            <div><div class="stat-value">{{ number_format($stats['total_dokumen']) }}</div><div class="stat-label">Total Dokumen</div></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-hand-holding-medical"></i></div>
            <div><div class="stat-value">{{ $stats['total_peminjaman'] }}</div><div class="stat-label">Total Peminjaman</div></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon danger"><i class="fas fa-exclamation-circle"></i></div>
            <div><div class="stat-value">{{ $stats['terlambat'] }}</div><div class="stat-label">Terlambat</div></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Laporan Peminjaman</div>
            </div>
            <div class="card-body text-center py-5">
                <a href="{{ route('laporan.peminjaman') }}" class="btn-primary-custom">
                    <i class="fas fa-file-alt"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Laporan Pengembalian</div>
            </div>
            <div class="card-body text-center py-5">
                <a href="{{ route('laporan.pengembalian') }}" class="btn-primary-custom">
                    <i class="fas fa-file-alt"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Dokumen Terlambat</div>
            </div>
            <div class="card-body text-center py-5">
                <a href="{{ route('laporan.terlambat') }}" class="btn-primary-custom">
                    <i class="fas fa-exclamation-triangle"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Rekap Bulanan</div>
            </div>
            <div class="card-body text-center py-5">
                <a href="{{ route('laporan.rekap-bulanan') }}" class="btn-primary-custom">
                    <i class="fas fa-calendar"></i> Lihat Rekap
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
