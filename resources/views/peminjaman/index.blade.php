@extends('layouts.app')
@section('title', 'Peminjaman Dokumen')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Peminjaman</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Peminjaman Dokumen</h1>
        <p class="page-subtitle">Kelola peminjaman rekam medis</p>
    </div>
    <a href="{{ route('peminjaman.create') }}" class="btn-primary-custom">
        <i class="fas fa-plus-circle"></i> Buat Permohonan
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('peminjaman.menunggu') }}" class="stat-card stat-card-menunggu" style="text-decoration: none;">
            <div class="stat-card-decor-circle stat-card-decor-circle-1"></div>
            <div class="stat-card-decor-circle stat-card-decor-circle-2"></div>
            <div class="stat-card-inner">
                <div class="stat-card-icon-main">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ \App\Models\Peminjaman::menungguPersetujuan()->count() }}</div>
                    <div class="stat-card-title">Menunggu Persetujuan</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-hourglass-half"></i>
                        <span>Perlu approval</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card stat-card-dipinjam">
            <div class="stat-card-decor-circle stat-card-decor-circle-1"></div>
            <div class="stat-card-decor-circle stat-card-decor-circle-2"></div>
            <div class="stat-card-inner">
                <div class="stat-card-icon-main">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ \App\Models\Peminjaman::where('status_peminjaman', 'dipinjam')->count() }}</div>
                    <div class="stat-card-title">Sedang Dipinjam</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-bookmark"></i>
                        <span>Aktif dipinjam</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('peminjaman.terlambat') }}" class="stat-card stat-card-terlambat" style="text-decoration: none;">
            <div class="stat-card-decor-circle stat-card-decor-circle-1"></div>
            <div class="stat-card-decor-circle stat-card-decor-circle-2"></div>
            <div class="stat-card-inner">
                <div class="stat-card-icon-main">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ \App\Models\Peminjaman::terlambat()->count() }}</div>
                    <div class="stat-card-title">Terlambat</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-calendar-times"></i>
                        <span>Lewati deadline</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card stat-card-selesai">
            <div class="stat-card-decor-circle stat-card-decor-circle-1"></div>
            <div class="stat-card-decor-circle stat-card-decor-circle-2"></div>
            <div class="stat-card-inner">
                <div class="stat-card-icon-main">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ \App\Models\Peminjaman::where('status_peminjaman', 'selesai')->count() }}</div>
                    <div class="stat-card-title">Selesai</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-archive"></i>
                        <span>Sudah dikembalikan</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. Peminjaman</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Jml Dokumen</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $pm)
                    <tr>
                        <td><strong>{{ $pm->no_peminjaman }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm avatar-primary">{{ strtoupper(substr($pm->peminjam->nama_lengkap, 0, 1)) }}</div>
                                {{ $pm->peminjam->nama_lengkap }}
                            </div>
                        </td>
                        <td>{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $pm->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                        <td><span class="badge bg-light text-dark">{{ $pm->rekamMedis->count() }} dokumen</span></td>
                        <td><span class="status-badge status-{{ $pm->status_peminjaman }}">{{ str_replace('_', ' ', ucfirst($pm->status_peminjaman)) }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('peminjaman.show', $pm) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state py-5">
                                <div class="empty-state-icon"><i class="fas fa-hand-holding-medical"></i></div>
                                <div class="empty-state-title">Tidak ada data peminjaman</div>
                                <div class="empty-state-text">Silakan buat permohonan peminjaman terlebih dahulu</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($peminjamans->hasPages())
        {{ $peminjamans->links('vendor.pagination.medtrack') }}
        @endif
    </div>
</div>
@endsection
