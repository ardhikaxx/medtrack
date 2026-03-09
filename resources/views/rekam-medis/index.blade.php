@extends('layouts.app')
@section('title', 'Rekam Medis')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Rekam Medis</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dokumen Rekam Medis</h1>
        <p class="page-subtitle">Kelola dokumen rekam medis</p>
    </div>
    <a href="{{ route('rekam-medis.create') }}" class="btn-primary-custom">
        <i class="fas fa-plus"></i> Tambah Dokumen
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-custom datatable">
            <thead>
                <tr>
                    <th>Kode Dokumen</th>
                    <th>No. RM</th>
                    <th>Tanggal Kunjungan</th>
                    <th>Poli</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekamMedis as $rm)
                <tr>
                    <td><strong>{{ $rm->kode_dokumen }}</strong></td>
                    <td>{{ $rm->no_rekam_medis }}</td>
                    <td>{{ $rm->tanggal_kunjungan->format('d/m/Y') }}</td>
                    <td>{{ $rm->poli->nama_unit ?? '-' }}</td>
                    <td><span class="status-badge dok-{{ $rm->status_dokumen }}">{{ $rm->status_dokumen }}</span></td>
                    <td>
                        <a href="{{ route('rekam-medis.show', $rm) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $rekamMedis->links() }}
    </div>
</div>
@endsection
