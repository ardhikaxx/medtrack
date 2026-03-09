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
                    <div class="avatar-placeholder flex-shrink-0">
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
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                    <div>Semua permohonan sudah diproses</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

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
