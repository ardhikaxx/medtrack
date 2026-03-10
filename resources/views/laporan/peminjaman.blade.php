@extends('layouts.app')
@section('title', 'Laporan Peminjaman')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Peminjaman</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Peminjaman</h1>
        <p class="page-subtitle">Data peminjaman dokumen rekam medis</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('export.peminjaman.excel', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('export.peminjaman.pdf', request()->query()) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card stat-card-total">
            <div class="stat-card-decor-circle stat-card-decor-circle-1"></div>
            <div class="stat-card-decor-circle stat-card-decor-circle-2"></div>
            <div class="stat-card-inner">
                <div class="stat-card-icon-main">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ $statistik['total'] }}</div>
                    <div class="stat-card-title">Total Peminjaman</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-archive"></i>
                        <span>Semua transaksi</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card stat-card-menunggu">
            <div class="stat-card-decor-circle stat-card-decor-circle-1"></div>
            <div class="stat-card-decor-circle stat-card-decor-circle-2"></div>
            <div class="stat-card-inner">
                <div class="stat-card-icon-main">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ $statistik['menunggu'] }}</div>
                    <div class="stat-card-title">Menunggu</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-hourglass-half"></i>
                        <span>Perlu approval</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card stat-card-aktif">
            <div class="stat-card-decor-circle stat-card-decor-circle-1"></div>
            <div class="stat-card-decor-circle stat-card-decor-circle-2"></div>
            <div class="stat-card-inner">
                <div class="stat-card-icon-main">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-number">{{ $statistik['disetujui'] + $statistik['dipinjam'] }}</div>
                    <div class="stat-card-title">Aktif</div>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-info-item">
                        <i class="fas fa-bookmark"></i>
                        <span>Sedang dipinjam</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </div>
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
                    <div class="stat-card-number">{{ $statistik['selesai'] }}</div>
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
    <div class="card-body">
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-2">
                    <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}" placeholder="Dari tanggal">
                </div>
                <div class="col-md-2">
                    <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}" placeholder="Sampai tanggal">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu_persetujuan" {{ request('status') == 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="jenis_peminjam" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="internal" {{ request('jenis_peminjam') == 'internal' ? 'selected' : '' }}>Internal</option>
                        <option value="eksternal" {{ request('jenis_peminjam') == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('laporan.peminjaman') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. Peminjaman</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Jenis</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $pm)
                    <tr>
                        <td><strong>{{ $pm->no_peminjaman }}</strong></td>
                        <td>
                            @if($pm->jenis_peminjam === 'internal')
                                {{ $pm->peminjam->nama_lengkap ?? '-' }}
                            @else
                                {{ $pm->nama_peminjam_luar }} ({{ $pm->institusi_peminjam }})
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($pm->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($pm->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $pm->jenis_peminjam === 'internal' ? 'primary' : 'info' }}">
                                {{ ucfirst($pm->jenis_peminjam) }}
                            </span>
                        </td>
                        <td>{{ $pm->tujuan_peminjaman }}</td>
                        <td>
                            @php
                                $statusClass = match($pm->status_peminjaman) {
                                    'menunggu_persetujuan' => 'warning',
                                    'disetujui' => 'info',
                                    'dipinjam' => 'primary',
                                    'selesai' => 'success',
                                    'ditolak' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($pm->status_peminjaman)) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada data peminjaman</td>
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
