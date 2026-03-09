@extends('layouts.app')
@section('title', 'Detail Pasien')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('pasien.index') }}">Data Pasien</a></li>
<li class="breadcrumb-item active">Detail Pasien</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Pasien</h1>
        <p class="page-subtitle">{{ $pasien->no_rekam_medis }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pasien.edit', $pasien) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('pasien.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header-custom">
                <div class="card-header-title">Data Pribadi</div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 140px; color: var(--text-secondary);">Nama Lengkap</td>
                        <td class="fw-semibold">{{ $pasien->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">No. RM</td>
                        <td><span class="badge bg-primary">{{ $pasien->no_rekam_medis }}</span></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">NIK</td>
                        <td>{{ $pasien->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">Jenis Kelamin</td>
                        <td>{{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">Tempat, Tanggal Lahir</td>
                        <td>{{ $pasien->tempat_lahir }}, {{ $pasien->tanggal_lahir->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">Umur</td>
                        <td>{{ $pasien->umur }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">Golongan Darah</td>
                        <td>{{ $pasien->golongan_darah }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">Agama</td>
                        <td>{{ ucfirst($pasien->agama) }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">Status</td>
                        <td><span class="status-badge status-{{ $pasien->status_pasien }}">{{ ucfirst($pasien->status_pasien) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header-custom">
                <div class="card-header-title">Alamat</div>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $pasien->alamat_lengkap }}</p>
                <p class="mb-0">RT {{ $pasien->rt }}/RW {{ $pasien->rw }}</p>
                <p class="mb-0">{{ $pasien->kelurahan }}, {{ $pasien->kecamatan }}</p>
                <p class="mb-0">{{ $pasien->kota_kabupaten }}, {{ $pasien->provinsi }} {{ $pasien->kode_pos }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Kontak</div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 100px; color: var(--text-secondary);">No. HP</td>
                        <td>{{ $pasien->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">No. Telp</td>
                        <td>{{ $pasien->no_telp ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header-custom">
                <div class="card-header-title">Jaminan Kesehatan</div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 120px; color: var(--text-secondary);">Jenis</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $pasien->jenis_jaminan)) }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">No. Jaminan</td>
                        <td>{{ $pasien->no_jaminan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">Kelas</td>
                        <td>{{ $pasien->kelas_jaminan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Riwayat Kunjungan</div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 120px; color: var(--text-secondary);">Registrasi</td>
                        <td>{{ $pasien->tanggal_registrasi->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-secondary);">Kunjungan Terakhir</td>
                        <td>{{ $pasien->kunjungan_terakhir?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header-custom">
        <div class="card-header-title"><i class="fas fa-file-medical"></i> Riwayat Rekam Medis</div>
    </div>
    <div class="card-body">
        @forelse($pasien->rekamMedis as $rm)
        <div class="d-flex align-items-center gap-3 p-3 border-bottom">
            <div class="stat-icon primary"><i class="fas fa-file-alt"></i></div>
            <div class="flex-grow-1">
                <div class="fw-semibold">{{ $rm->kode_dokumen }}</div>
                <div style="font-size: 12px; color: var(--text-secondary)">
                    {{ $rm->tanggal_kunjungan->format('d/m/Y') }} - {{ $rm->poli->nama_unit ?? 'Umum' }}
                </div>
            </div>
            <span class="status-badge dok-{{ $rm->status_dokumen }}">{{ $rm->status_dokumen }}</span>
        </div>
        @empty
        <div class="text-center py-4 text-muted">Belum ada riwayat rekam medis</div>
        @endforelse
    </div>
</div>
@endsection
