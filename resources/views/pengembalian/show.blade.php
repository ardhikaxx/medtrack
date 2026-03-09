@extends('layouts.app')
@section('title', 'Detail Pengembalian')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('pengembalian.index') }}">Pengembalian</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Pengembalian</h1>
        <p class="page-subtitle">No. Pengembalian: {{ $pengembalian->no_pengembalian }}</p>
    </div>
    <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Informasi Pengembalian</div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>No. Pengembalian</strong></td>
                        <td>{{ $pengembalian->no_pengembalian }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Peminjaman</strong></td>
                        <td>{{ $pengembalian->peminjaman->no_peminjaman ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pengembalian</strong></td>
                        <td>{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Petugas</strong></td>
                        <td>{{ $pengembalian->petugas->nama_lengkap ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status Keterlambatan</strong></td>
                        <td>
                            @if($pengembalian->is_terlambat)
                                <span class="badge bg-danger">Terlambat {{ $pengembalian->hari_terlambat }} hari</span>
                            @else
                                <span class="badge bg-success">Tepat Waktu</span>
                            @endif
                        </td>
                    </tr>
                    @if($pengembalian->catatan_pengembalian)
                    <tr>
                        <td><strong>Catatan</strong></td>
                        <td>{{ $pengembalian->catatan_pengembalian }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Ringkasan Dokumen</div>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="stat-value text-success">{{ $pengembalian->jumlah_dokumen_kembali }}</div>
                        <div class="stat-label">Dikembalikan</div>
                    </div>
                    <div class="col-4">
                        <div class="stat-value text-danger">{{ $pengembalian->jumlah_dokumen_hilang }}</div>
                        <div class="stat-label">Hilang</div>
                    </div>
                    <div class="col-4">
                        <div class="stat-value text-warning">{{ $pengembalian->jumlah_dokumen_rusak }}</div>
                        <div class="stat-label">Rusak</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header-custom">
        <div class="card-header-title">Detail Dokumen</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Kode Dokumen</th>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Status</th>
                        <th>Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalian->peminjaman->detailPeminjamans as $detail)
                    <tr>
                        <td><strong>{{ $detail->rekamMedis->kode_dokumen }}</strong></td>
                        <td>{{ $detail->rekamMedis->no_rekam_medis }}</td>
                        <td>{{ $detail->rekamMedis->pasien->nama_lengkap ?? '-' }}</td>
                        <td>
                            @php
                                $statusClass = match($detail->status_detail) {
                                    'dikembalikan' => 'success',
                                    'hilang' => 'danger',
                                    'rusak' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ ucfirst($detail->status_detail) }}</span>
                        </td>
                        <td>{{ $detail->kondisi_kembali ? ucfirst(str_replace('_', ' ', $detail->kondisi_kembali)) : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Tidak ada dokumen</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
