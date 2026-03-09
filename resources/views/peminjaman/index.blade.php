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
        <i class="fas fa-plus"></i> Buat Permohonan
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-custom datatable">
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
                        <td>{{ $pm->peminjam->nama_lengkap }}</td>
                        <td>{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $pm->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                        <td>{{ $pm->rekamMedis->count() }}</td>
                        <td><span class="status-badge status-{{ $pm->status_peminjaman }}">{{ str_replace('_', ' ', ucfirst($pm->status_peminjaman)) }}</span></td>
                        <td>
                            <a href="{{ route('peminjaman.show', $pm) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
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
        {{ $peminjamans->links() }}
    </div>
</div>
@endsection
