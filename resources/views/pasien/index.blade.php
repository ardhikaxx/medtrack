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
        <i class="fas fa-plus"></i> Tambah Pasien
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, No. RM, NIK..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Cari</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-custom datatable">
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
                        <td>{{ $pasien->nama_lengkap }}</td>
                        <td>{{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $pasien->tanggal_lahir->format('d/m/Y') }}</td>
                        <td>{{ $pasien->no_hp }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $pasien->jenis_jaminan)) }}</td>
                        <td>
                            <span class="status-badge status-{{ $pasien->status_pasien }}">
                                {{ ucfirst($pasien->status_pasien) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('pasien.show', $pasien) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('pasien.edit', $pasien) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">Tidak ada data pasien</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pasiens->links() }}
    </div>
</div>
@endsection
