@extends('layouts.app')
@section('title', 'Laporan Terlambat')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Terlambat</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dokumen Terlambat</h1>
        <p class="page-subtitle">Daftar peminjaman yang melampaui tanggal rencana kembali</p>
    </div>
    <a href="{{ route('laporan.cetak-terlambat') }}" class="btn-primary-custom" target="_blank">
        <i class="fas fa-print"></i> Cetak
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. Peminjaman</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Terlambat</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                                {{ $pm->nama_peminjam_luar }}
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($pm->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($pm->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-danger">{{ $pm->hari_terlambat }} hari</span>
                        </td>
                        <td>
                            <span class="badge bg-warning">Terlambat</span>
                        </td>
                        <td>
                            <a href="{{ route('peminjaman.show', $pm->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada peminjaman terlambat</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $peminjamans->links() }}
    </div>
</div>
@endsection
