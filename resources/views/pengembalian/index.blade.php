@extends('layouts.app')
@section('title', 'Pengembalian')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Pengembalian</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pengembalian Dokumen</h1>
        <p class="page-subtitle">Kelola pengembalian rekam medis</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>No. Pengembalian</th>
                    <th>No. Peminjaman</th>
                    <th>Tanggal</th>
                    <th>Petugas</th>
                    <th>Jml Dokumen</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengembalians as $pengembalian)
                <tr>
                    <td><strong>{{ $pengembalian->no_pengembalian }}</strong></td>
                    <td>{{ $pengembalian->peminjaman->no_peminjaman }}</td>
                    <td>{{ $pengembalian->tanggal_pengembalian->format('d/m/Y') }}</td>
                    <td>{{ $pengembalian->petugas->nama_lengkap }}</td>
                    <td>{{ $pengembalian->jumlah_dokumen_kembali }}</td>
                    <td>
                        @if($pengembalian->is_terlambat)
                        <span class="status-badge status-terlambat">Terlambat {{ $pengembalian->hari_terlambat }} hari</span>
                        @else
                        <span class="status-badge status-selesai">Tepat Waktu</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($pengembalians->hasPages())
        <div class="card-footer">
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    <span>Menampilkan</span>
                    <span class="badge">{{ $pengembalians->firstItem() ?? 0 }} - {{ $pengembalians->lastItem() ?? 0 }}</span>
                    <span>dari</span>
                    <span class="badge">{{ $pengembalians->total() }}</span>
                    <span>data</span>
                </div>
                {{ $pengembalians->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
