@extends('layouts.app')
@section('title', 'Peminjaman Menunggu Persetujuan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('peminjaman.index') }}">Peminjaman</a></li>
<li class="breadcrumb-item active">Menunggu Persetujuan</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Peminjaman Menunggu Persetujuan</h1>
        <p class="page-subtitle">Daftar permohonan peminjaman yang menunggu persetujuan</p>
    </div>
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
                        <td colspan="7" class="text-center py-4">Tidak ada peminjaman yang menunggu persetujuan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $peminjamans->links() }}
    </div>
</div>
@endsection
