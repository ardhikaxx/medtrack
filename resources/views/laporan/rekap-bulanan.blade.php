@extends('layouts.app')
@section('title', 'Rekap Bulanan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Rekap Bulanan</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Rekap Bulanan</h1>
        <p class="page-subtitle">Data peminjaman per bulan</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="d-flex gap-3 align-items-end">
            <div>
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    @for($i = now()->year; $i >= now()->year - 5; $i--)
                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Peminjaman per Hari</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah Peminjaman</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjamanPerHari as $data)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') }}</td>
                                <td><strong>{{ $data->total }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4">Tidak ada data peminjaman bulan ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Per Tujuan</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Tujuan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perTujuan as $tujuan)
                            <tr>
                                <td>{{ $tujuan->tujuan_peminjaman }}</td>
                                <td><strong>{{ $tujuan->total }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
