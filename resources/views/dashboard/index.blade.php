@extends('layouts.app')
@section('title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Selamat datang, {{ auth()->user()->nama_lengkap }} — {{ now()->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
    <a href="{{ route('laporan.rekap-bulanan') }}" class="btn-primary-custom">
        <i class="fas fa-file-excel"></i> Rekap Bulanan
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('pasien.index') }}" class="stat-card stat-card-pasien" style="text-decoration: none;">
            <div class="stat-card-inner">
                <div class="stat-card-icon-bg"></div>
                <div class="stat-card-icon-main">
                    <i class="fas fa-user-injured"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ number_format($stats['total_pasien']) }}</div>
                    <div class="stat-card-title">Total Pasien</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-users"></i>
                        <span>Pasien terdaftar</span>
                    </div>
                    <div class="stat-card-trend-badge up">
                        <i class="fas fa-arrow-trend-up"></i> 12%
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('rekam-medis.index') }}" class="stat-card stat-card-dokumen" style="text-decoration: none;">
            <div class="stat-card-inner">
                <div class="stat-card-icon-bg"></div>
                <div class="stat-card-icon-main">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ number_format($stats['total_dokumen']) }}</div>
                    <div class="stat-card-title">Dokumen Rekam Medis</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-folder-open"></i>
                        <span>Berhasil diarsipkan</span>
                    </div>
                    <div class="stat-card-trend-badge up">
                        <i class="fas fa-arrow-trend-up"></i> 8%
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('peminjaman.index') }}" class="stat-card stat-card-peminjaman" style="text-decoration: none;">
            <div class="stat-card-inner">
                <div class="stat-card-icon-bg"></div>
                <div class="stat-card-icon-main">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ $stats['peminjaman_aktif'] }}</div>
                    <div class="stat-card-title">Peminjaman Aktif</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-clock"></i>
                        <span>Sedang berjalan</span>
                    </div>
                    <div class="stat-card-trend-badge neutral">
                        <i class="fas fa-minus"></i> Stabil
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('peminjaman.terlambat') }}" class="stat-card stat-card-terlambat" style="text-decoration: none;">
            <div class="stat-card-inner">
                <div class="stat-card-icon-bg"></div>
                <div class="stat-card-icon-main">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ $stats['terlambat'] }}</div>
                    <div class="stat-card-title">Dokumen Terlambat</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-warning"></i>
                        <span>Harus dikembalikan</span>
                    </div>
                    @if($stats['terlambat'] > 0)
                    <div class="stat-card-trend-badge down">
                        <i class="fas fa-arrow-trend-down"></i> Perlu action
                    </div>
                    @else
                    <div class="stat-card-trend-badge good">
                        <i class="fas fa-check-circle"></i> Aman
                    </div>
                    @endif
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-modern">
            <div class="card-header-custom">
                <div class="card-header-title">
                    <span class="header-icon"><i class="fas fa-hourglass-half"></i></span>
                    Menunggu Persetujuan
                    @if($menungguPersetujuan->count() > 0)
                    <span class="badge bg-warning text-dark ms-2">{{ $menungguPersetujuan->count() }}</span>
                    @endif
                </div>
                <a href="{{ route('peminjaman.menunggu') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($menungguPersetujuan as $pm)
                <div class="d-flex align-items-center gap-3 p-3 list-item-hover">
                    <div class="avatar avatar-md avatar-gradient flex-shrink-0">
                        {{ strtoupper(substr($pm->peminjam->nama_lengkap, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
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
                    </div>
                </div>
                @empty
                <div class="empty-state py-5">
                    <div class="empty-state-icon empty-success"><i class="fas fa-check-circle"></i></div>
                    <div class="empty-state-title">Semua permohonan sudah diproses</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-modern card-danger-glow">
            <div class="card-header-custom">
                <div class="card-header-title">
                    <span class="header-icon text-danger"><i class="fas fa-exclamation-triangle"></i></span>
                    Dokumen Terlambat
                </div>
                <a href="{{ route('laporan.terlambat') }}" class="btn btn-sm btn-outline-danger">Detail</a>
            </div>
            <div class="card-body p-0">
                @forelse($terlambat as $pm)
                <div class="p-3 list-item-hover" style="border-bottom: 1px solid #f0f4f8;">
                    <div class="fw-semibold" style="font-size:13px">{{ $pm->peminjam->nama_lengkap }}</div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <span style="font-size:12px; color:var(--text-secondary)">{{ $pm->no_peminjaman }}</span>
                        <span class="status-badge status-terlambat">
                            <i class="fas fa-clock"></i> {{ $pm->hari_terlambat }} hari
                        </span>
                    </div>
                </div>
                @empty
                <div class="empty-state py-5">
                    <div class="empty-state-icon empty-success" style="background: linear-gradient(135deg, #e8f8f0 0%, #d4efdf 100%); color: #27ae60;"><i class="fas fa-smile"></i></div>
                    <div class="empty-state-title">Tidak ada yang terlambat</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-0">
    <div class="col-12">
        <div class="card card-modern">
            <div class="card-header-custom">
                <div class="card-header-title"><span class="header-icon"><i class="fas fa-history"></i></span> Aktivitas Terkini</div>
            </div>
            <div class="card-body">
                <div class="timeline timeline-modern">
                    @foreach($aktivitasTerkini as $log)
                    <div class="timeline-item">
                        <div class="timeline-icon timeline-icon-{{ $log->aksi == 'create' ? 'create' : ($log->aksi == 'approve' ? 'approve' : 'update') }}">
                            <i class="fas {{ $log->aksi == 'create' ? 'fa-plus' : ($log->aksi == 'approve' ? 'fa-check' : 'fa-edit') }}"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="fw-semibold" style="font-size:14px">{{ $log->keterangan }}</div>
                            <div class="timeline-date">
                                <span class="timeline-user"><i class="fas fa-user me-1"></i>{{ $log->user->nama_lengkap ?? 'Sistem' }}</span>
                                <span class="timeline-separator">·</span>
                                <span class="timeline-time"><i class="fas fa-clock me-1"></i>{{ $log->created_at->diffForHumans() }}</span>
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
