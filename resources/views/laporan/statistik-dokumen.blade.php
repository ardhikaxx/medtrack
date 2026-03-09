@extends('layouts.app')
@section('title', 'Statistik Dokumen')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Statistik Dokumen</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Statistik Dokumen</h1>
        <p class="page-subtitle">Statistik dokumen rekam medis berdasarkan status</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Status Dokumen</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Jumlah</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = $statusDokumen->sum('total');
                            @endphp
                            @forelse($statusDokumen as $status)
                            <tr>
                                <td>
                                    @php
                                        $badgeClass = match($status->status_dokumen) {
                                            'tersedia' => 'success',
                                            'dipinjam' => 'primary',
                                            'dalam_proses' => 'warning',
                                            'hilang' => 'danger',
                                            'rusak' => 'secondary',
                                            'dimusnahkan' => 'dark',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($status->status_dokumen) }}</span>
                                </td>
                                <td><strong>{{ $status->total }}</strong></td>
                                <td>{{ $total > 0 ? round(($status->total / $total) * 100, 1) : 0 }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td>Total</td>
                                <td>{{ $total }}</td>
                                <td>100%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Dokumen per Poli</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Poli</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perPoli as $poli)
                            <tr>
                                <td>{{ $poli->poli->nama_unit ?? 'Poli #' . $poli->poli_id }}</td>
                                <td><strong>{{ $poli->total }}</strong></td>
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
