@extends('layouts.app')
@section('title', 'Proses Pengembalian')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('peminjaman.index') }}">Peminjaman</a></li>
<li class="breadcrumb-item"><a href="{{ route('peminjaman.show', $peminjaman->id) }}">{{ $peminjaman->no_peminjaman }}</a></li>
<li class="breadcrumb-item active">Pengembalian</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Proses Pengembalian</h1>
        <p class="page-subtitle">Peminjaman: {{ $peminjaman->no_peminjaman }}</p>
    </div>
</div>

<div class="card">
    <div class="card-header-custom">
        <div class="card-header-title">Informasi Peminjaman</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="35%"><strong>No. Peminjaman</strong></td>
                        <td>{{ $peminjaman->no_peminjaman }}</td>
                    </tr>
                    <tr>
                        <td><strong>Peminjam</strong></td>
                        <td>
                            @if($peminjaman->jenis_peminjam === 'internal')
                                {{ $peminjaman->peminjam->nama_lengkap ?? '-' }}
                            @else
                                {{ $peminjaman->nama_peminjam_luar }} ({{ $peminjaman->institusi_peminjam }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pinjam</strong></td>
                        <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="35%"><strong>Rencana Kembali</strong></td>
                        <td>
                            @php
                                $rencana = \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana);
                                $isTerlambat = $rencana->isBefore(now());
                            @endphp
                            <span class="{{ $isTerlambat ? 'text-danger' : '' }}">
                                {{ $rencana->format('d/m/Y') }}
                                @if($isTerlambat)
                                    <span class="badge bg-danger">Terlambat {{ now()->diffInDays($rencana) }} hari</span>
                                @endif
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Tujuan</strong></td>
                        <td>{{ $peminjaman->tujuan_peminjaman }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            <span class="badge bg-primary">{{ str_replace('_', ' ', ucfirst($peminjaman->status_peminjaman)) }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('pengembalian.store', $peminjaman->id) }}" method="POST">
    @csrf
    
    <div class="card mt-3">
        <div class="card-header-custom">
            <div class="card-header-title">Dokumen yang Dikembalikan</div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Tanggal Pengembalian</label>
                <input type="date" name="tanggal_pengembalian" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>

            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th width="50">Pilih</th>
                            <th>Kode Dokumen</th>
                            <th>No. RM</th>
                            <th>Nama Pasien</th>
                            <th>Status Saat Ini</th>
                            <th>Kondisi Kembali</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman->detailPeminjamans as $detail)
                        @if($detail->status_detail === 'dipinjam')
                        <tr>
                            <td>
                                <input type="checkbox" name="detail_kembali[{{ $loop->index }}][selected]" value="1" checked class="form-check-input">
                                <input type="hidden" name="detail_kembali[{{ $loop->index }}][rekam_medis_id]" value="{{ $detail->rekam_medis_id }}">
                            </td>
                            <td><strong>{{ $detail->rekamMedis->kode_dokumen }}</strong></td>
                            <td>{{ $detail->rekamMedis->no_rekam_medis }}</td>
                            <td>{{ $detail->rekamMedis->pasien->nama_lengkap ?? '-' }}</td>
                            <td><span class="badge bg-warning">Dipinjam</span></td>
                            <td>
                                <select name="detail_kembali[{{ $loop->index }}][status]" class="form-select form-select-sm" required>
                                    <option value="dikembalikan">Dikembalikan</option>
                                    <option value="hilang">Hilang</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                                <select name="detail_kembali[{{ $loop->index }}][kondisi]" class="form-select form-select-sm mt-1">
                                    <option value="baik">Baik</option>
                                    <option value="cukup">Cukup</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="rusak_berat">Rusak Berat</option>
                                </select>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan pengembalian (opsional)"></textarea>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Pengembalian
        </button>
        <a href="{{ route('peminjaman.show', $peminjaman->id) }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
