@extends('layouts.app')
@section('title', 'Laporan Pengembalian')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Pengembalian</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Pengembalian</h1>
        <p class="page-subtitle">Data pengembalian dokumen rekam medis</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}" placeholder="Dari tanggal">
                </div>
                <div class="col-md-3">
                    <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}" placeholder="Sampai tanggal">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('laporan.pengembalian') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. Pengembalian</th>
                        <th>No. Peminjaman</th>
                        <th>Tanggal Kembali</th>
                        <th>Petugas</th>
                        <th>Jml Kembali</th>
                        <th>Hilang</th>
                        <th>Rusak</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalians as $pg)
                    <tr>
                        <td><strong>{{ $pg->no_pengembalian }}</strong></td>
                        <td>{{ $pg->peminjaman->no_peminjaman ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($pg->tanggal_pengembalian)->format('d/m/Y') }}</td>
                        <td>{{ $pg->petugas->nama_lengkap ?? '-' }}</td>
                        <td>{{ $pg->jumlah_dokumen_kembali }}</td>
                        <td>
                            @if($pg->jumlah_dokumen_hilang > 0)
                                <span class="badge bg-danger">{{ $pg->jumlah_dokumen_hilang }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td>
                            @if($pg->jumlah_dokumen_rusak > 0)
                                <span class="badge bg-warning">{{ $pg->jumlah_dokumen_rusak }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td>
                            @if($pg->is_terlambat)
                                <span class="badge bg-danger">Terlambat {{ $pg->hari_terlambat }} hari</span>
                            @else
                                <span class="badge bg-success">Tepat Waktu</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">Tidak ada data pengembalian</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengembalians->hasPages())
        {{ $pengembalians->links('vendor.pagination.medtrack') }}
        @endif
    </div>
</div>
@endsection
