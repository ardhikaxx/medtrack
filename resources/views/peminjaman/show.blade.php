@extends('layouts.app')
@section('title', 'Detail Peminjaman')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('peminjaman.index') }}">Peminjaman</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $peminjaman->no_peminjaman }}</h1>
        <p class="page-subtitle">Status: <span class="status-badge status-{{ $peminjaman->status_peminjaman }}">{{ str_replace('_', ' ', ucfirst($peminjaman->status_peminjaman)) }}</span></p>
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header-custom">
                <div class="card-header-title">Data Peminjam</div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><td style="width:150px">Nama</td><td class="fw-semibold">{{ $peminjaman->peminjam->nama_lengkap }}</td></tr>
                    <tr><td>Unit</td><td>{{ $peminjaman->peminjam->unit->nama_unit ?? '-' }}</td></tr>
                    <tr><td>Jabatan</td><td>{{ $peminjaman->peminjam->jabatan ?? '-' }}</td></tr>
                    <tr><td>Jenis</td><td>{{ ucfirst($peminjaman->jenis_peminjam) }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header-custom">
                <div class="card-header-title">Detail Permohonan</div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><td style="width:150px">Tujuan</td><td class="fw-semibold">{{ ucfirst($peminjaman->tujuan_peminjaman) }}</td></tr>
                    <tr><td>Keperluan</td><td>{{ $peminjaman->keperluan_detail }}</td></tr>
                    <tr><td>Tanggal Pinjam</td><td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td></tr>
                    <tr><td>Rencana Kembali</td><td>{{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Dokumen Dipinjam</div>
            </div>
            <div class="card-body p-0">
                @forelse($peminjaman->detailPeminjamans as $detail)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div class="stat-icon success"><i class="fas fa-file-medical"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $detail->rekamMedis->kode_dokumen }}</div>
                        <div style="font-size:12px; color:var(--text-secondary)">{{ $detail->rekamMedis->pasien->nama_lengkap }}</div>
                    </div>
                    <span class="status-badge dok-{{ $detail->status_detail }}">{{ $detail->status_detail }}</span>
                </div>
                @empty
                <div class="text-center py-4">Tidak ada dokumen</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($peminjaman->status_peminjaman == 'menunggu_persetujuan')
        <div class="card mb-4">
            <div class="card-header-custom">
                <div class="card-header-title">Aksi</div>
            </div>
            <div class="card-body">
                <form action="{{ route('peminjaman.setujui', $peminjaman) }}" method="POST" class="mb-2" id="form-setuju-{{ $peminjaman->id }}">
                    @csrf
                    <button type="button" class="btn btn-success w-100" onclick="confirmSetuju({{ $peminjaman->id }})"><i class="fas fa-check"></i> Setuju</button>
                </form>
                <form action="{{ route('peminjaman.tolak', $peminjaman) }}" method="POST" class="mb-2">
                    @csrf
                    <input type="text" name="alasan_penolakan" class="form-control mb-2" placeholder="Alasan penolakan" required>
                    <button type="button" class="btn btn-danger w-100" onclick="confirmTolak({{ $peminjaman->id }})"><i class="fas fa-times"></i> Tolak</button>
                </form>
            </div>
        </div>
        @endif

        @if($peminjaman->status_peminjaman == 'disetujui')
        <div class="card mb-4">
            <div class="card-header-custom">
                <div class="card-header-title">Proses</div>
            </div>
            <div class="card-body">
                <form action="{{ route('peminjaman.proses', $peminjaman) }}" method="POST" id="form-proses-{{ $peminjaman->id }}">
                    @csrf
                    <button type="button" class="btn-primary-custom w-100" onclick="confirmProses({{ $peminjaman->id }})">
                        <i class="fas fa-hand-holding-medical"></i> Proses Penyerahan
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if(in_array($peminjaman->status_peminjaman, ['dipinjam', 'terlambat']))
        <div class="card mb-4">
            <div class="card-header-custom">
                <div class="card-header-title">Pengembalian</div>
            </div>
            <div class="card-body">
                <a href="{{ route('pengembalian.create', $peminjaman) }}" class="btn-primary-custom w-100">
                    <i class="fas fa-undo"></i> Proses Pengembalian
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
