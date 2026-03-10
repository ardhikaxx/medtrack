@extends('layouts.app')
@section('title', 'Detail Rekam Medis')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('rekam-medis.index') }}">Rekam Medis</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Rekam Medis</h1>
        <p class="page-subtitle">Kode: {{ $rekamMedis->kode_dokumen }}</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary" onclick="printLabel()">
            <i class="fas fa-print"></i> Print Label
        </button>
        <a href="{{ route('rekam-medis.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('rekam-medis.edit', $rekamMedis->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-modern text-center">
            <div class="card-header-custom">
                <div class="card-header-title">QR Code</div>
            </div>
            <div class="card-body">
                <div class="qrcode-display mb-3">
                    <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(150)->generate($rekamMedis->getQrCodeUrl())) }}" 
                         alt="QR Code" class="img-fluid" style="max-width: 150px;">
                </div>
                <div class="text-muted" style="font-size: 11px;">Scan untuk lihat detail</div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card card-modern">
            <div class="card-header-custom">
                <div class="card-header-title">Preview Label</div>
            </div>
            <div class="card-body">
                <div id="label-preview" class="label-print-preview">
                    <div class="label-content">
                        <div class="label-qr">
                            <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(80)->generate($rekamMedis->getQrCodeUrl())) }}" 
                                 alt="QR" style="width: 80px; height: 80px;">
                        </div>
                        <div class="label-info">
                            <div class="label-title">Klinik Pratama Rawat Inap Husada</div>
                            <div class="label-code">{{ $rekamMedis->kode_dokumen }}</div>
                            <div class="label-patient">{{ $rekamMedis->pasien->nama_lengkap ?? '-' }}</div>
                            <div class="label-noRM">RM: {{ $rekamMedis->no_rekam_medis }}</div>
                            <div class="label-date">{{ $rekamMedis->tanggal_kunjungan?->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Informasi Pasien</div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>No. Rekam Medis</strong></td>
                        <td>{{ $rekamMedis->pasien->no_rekam_medis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Pasien</strong></td>
                        <td>{{ $rekamMedis->pasien->nama_lengkap ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIK</strong></td>
                        <td>{{ $rekamMedis->pasien->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Lahir</strong></td>
                        <td>{{ $rekamMedis->pasien->tanggal_lahir ? \Carbon\Carbon::parse($rekamMedis->pasien->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin</strong></td>
                        <td>{{ $rekamMedis->pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Informasi Kunjungan</div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Kode Dokumen</strong></td>
                        <td><strong>{{ $rekamMedis->kode_dokumen }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Kunjungan</strong></td>
                        <td>{{ \Carbon\Carbon::parse($rekamMedis->tanggal_kunjungan)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Poli</strong></td>
                        <td>{{ $rekamMedis->poli->nama_unit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dokter</strong></td>
                        <td>{{ $rekamMedis->dokter->nama_lengkap ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kunjungan</strong></td>
                        <td>{{ str_replace('_', ' ', ucfirst($rekamMedis->jenis_kunjungan)) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td><span class="status-badge dok-{{ $rekamMedis->status_dokumen }}">{{ str_replace('_', ' ', ucfirst($rekamMedis->status_dokumen)) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-0">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Informasi Dokumen</div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Rak</strong></td>
                        <td>{{ $rekamMedis->rak ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Laci</strong></td>
                        <td>{{ $rekamMedis->laci ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Map/Folder</strong></td>
                        <td>{{ $rekamMedis->map_folder ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Halaman</strong></td>
                        <td>{{ $rekamMedis->jumlah_halaman ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Ketebalan</strong></td>
                        <td>{{ $rekamMedis->ketebalan_cm ? $rekamMedis->ketebalan_cm . ' cm' : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kondisi Dokumen</strong></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $rekamMedis->kondisi_dokumen)) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Retensi</strong></td>
                        <td>{{ $rekamMedis->tanggal_retensi ? \Carbon\Carbon::parse($rekamMedis->tanggal_retensi)->format('d/m/Y') : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">Diagnosa</div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Diagnosa Utama</strong></td>
                        <td>{{ $rekamMedis->diagnosa_utama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kode ICD-10</strong></td>
                        <td>{{ $rekamMedis->kode_icd10 ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Catatan</strong></td>
                        <td>{{ $rekamMedis->catatan_dokumen ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat Oleh</strong></td>
                        <td>{{ $rekamMedis->pembuat->nama_lengkap ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Dibuat</strong></td>
                        <td>{{ $rekamMedis->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if($rekamMedis->detailPeminjamans && $rekamMedis->detailPeminjamans->count() > 0)
<div class="card mt-3">
    <div class="card-header-custom">
        <div class="card-header-title">Riwayat Peminjaman</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. Peminjaman</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekamMedis->detailPeminjamans as $detail)
                    <tr>
                        <td>{{ $detail->peminjaman->no_peminjaman ?? '-' }}</td>
                        <td>{{ $detail->peminjaman->peminjam->nama_lengkap ?? '-' }}</td>
                        <td>{{ $detail->peminjaman ? \Carbon\Carbon::parse($detail->peminjaman->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $detail->tanggal_dikembalikan ? \Carbon\Carbon::parse($detail->tanggal_dikembalikan)->format('d/m/Y') : '-' }}</td>
                        <td>
                            @php
                                $statusClass = match($detail->status_detail) {
                                    'dipinjam' => 'warning',
                                    'dikembalikan' => 'success',
                                    'hilang' => 'danger',
                                    'rusak' => 'secondary',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ ucfirst($detail->status_detail) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
function printLabel() {
    const printContent = document.getElementById('label-preview').innerHTML;
    const printWindow = window.open('', '_blank', 'width=400,height=300');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print Label RM</title>
            <style>
                body { margin: 0; padding: 10px; font-family: Arial, sans-serif; }
                .label-content { display: flex; gap: 15px; align-items: center; }
                .label-qr img { width: 80px; height: 80px; }
                .label-info { flex: 1; }
                .label-title { font-size: 10px; font-weight: 600; color: #1a6f8a; margin-bottom: 4px; }
                .label-code { font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 2px; }
                .label-patient { font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 2px; }
                .label-noRM { font-size: 10px; color: #6b7280; }
                .label-date { font-size: 10px; color: #9ca3af; }
                @media print {
                    body { padding: 0; }
                }
            </style>
        </head>
        <body>
            <div class="label-content">
                ${printContent}
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}
</script>
@endpush
@endsection
