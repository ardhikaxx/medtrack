@extends('layouts.app')
@section('title', 'Data Pasien')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Data Pasien</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Data Pasien</h1>
        <p class="page-subtitle">Kelola data pasien klinik</p>
    </div>
    <a href="{{ route('pasien.create') }}" class="btn-primary-custom">
        <i class="fas fa-plus-circle"></i> Tambah Pasien
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="p-4 pb-0">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama, No. RM, NIK..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn-primary-custom w-100">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. RM</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>No. HP</th>
                        <th>Jenis Jaminan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pasiens as $pasien)
                    <tr>
                        <td><strong>{{ $pasien->no_rekam_medis }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm avatar-primary">{{ strtoupper(substr($pasien->nama_lengkap, 0, 1)) }}</div>
                                {{ $pasien->nama_lengkap }}
                            </div>
                        </td>
                        <td>{{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $pasien->tanggal_lahir->format('d/m/Y') }}</td>
                        <td>{{ $pasien->no_hp ?? '-' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $pasien->jenis_jaminan)) }}</td>
                        <td>
                            @if($pasien->status_pasien == 'aktif')
                            <span class="status-badge" style="background: linear-gradient(135deg, #e8f8f0 0%, #d4efdf 100%); color: #27ae60;">
                                Aktif
                            </span>
                            @else
                            <span class="status-badge" style="background: linear-gradient(135deg, #f4f4f4 0%, #e8e8e8 100%); color: #7f8c8d;">
                                Nonaktif
                            </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('pasien.show', $pasien) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pasien.edit', $pasien) }}" class="btn btn-sm btn-icon btn-outline-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state py-5">
                                <div class="empty-state-icon"><i class="fas fa-user-injured"></i></div>
                                <div class="empty-state-title">Tidak ada data pasien</div>
                                <div class="empty-state-text">Silakan tambah pasien baru untuk memulai</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pasiens->hasPages())
        {{ $pasiens->links('vendor.pagination.medtrack') }}
        @endif
    </div>
</div>
@endsection
