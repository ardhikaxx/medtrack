@extends('layouts.app')
@section('title', 'Unit & Poli')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Unit & Poli</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Unit & Poli</h1>
        <p class="page-subtitle">Kelola unit dan poli klinik</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Unit</th>
                    <th>Jenis</th>
                    <th>Lantai</th>
                    <th>Gedung</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($units as $unit)
                <tr>
                    <td><strong>{{ $unit->kode_unit }}</strong></td>
                    <td>{{ $unit->nama_unit }}</td>
                    <td>{{ ucfirst($unit->jenis_unit) }}</td>
                    <td>{{ $unit->lantai ?? '-' }}</td>
                    <td>{{ $unit->gedung ?? '-' }}</td>
                    <td>
                        @if($unit->is_active)
                        <span class="status-badge status-selesai">Aktif</span>
                        @else
                        <span class="status-badge status-ditolak">Nonaktif</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($units->hasPages())
        <div class="card-footer">
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Menampilkan {{ $units->firstItem() ?? 0 }} - {{ $units->lastItem() ?? 0 }} dari {{ $units->total() }} data
                </div>
                {{ $units->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
