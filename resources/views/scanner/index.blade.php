@extends('layouts.app')

@section('title', 'QR Scanner')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">QR Scanner</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">QR Code Scanner</h1>
        <p class="page-subtitle">Scan QR code untuk melihat informasi dokumen atau peminjaman</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-qrcode me-2"></i>Scan QR Code</h5>
            </div>
            <div class="card-body text-center">
                <div id="reader" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
                <div class="mt-3">
                    <button id="start-scan" class="btn-primary-custom">
                        <i class="fas fa-camera"></i> Mulai Kamera
                    </button>
                    <button id="stop-scan" class="btn btn-secondary d-none">
                        <i class="fas fa-stop"></i> Stop Kamera
                    </button>
                </div>
                <div class="mt-3">
                    <p class="text-muted small mb-0">Arahkan kamera ke QR code pada dokumen</p>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-keyboard me-2"></i>Input Manual</h5>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <input type="text" id="manual-code" class="form-control" placeholder="Masukkan No. RM atau No. Peminjaman">
                    <button class="btn-primary-custom" id="btn-lookup">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Hasil Scan</h5>
            </div>
            <div class="card-body">
                <div id="result-content">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-qrcode fa-3x mb-3" style="opacity: 0.3;"></i>
                        <p class="mb-0">Scan QR code atau masukkan kode secara manual</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let html5QrcodeScanner = null;

$('#start-scan').on('click', function() {
    $('#start-scan').addClass('d-none');
    $('#stop-scan').removeClass('d-none');
    
    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { fps: 10, qrbox: { width: 250, height: 250 } },
        /* verbose= */ false
    );
    
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});

$('#stop-scan').on('click', function() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
    }
    $('#start-scan').removeClass('d-none');
    $('#stop-scan').addClass('d-none');
});

function onScanSuccess(decodedText, decodedResult) {
    $('#stop-scan').click();
    lookupCode(decodedText);
}

function onScanFailure(error) {
    // Handle scan failure
}

$('#btn-lookup').on('click', function() {
    const code = $('#manual-code').val();
    if (code) {
        lookupCode(code);
    }
});

function lookupCode(code) {
    $('#result-content').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Mencari...</p></div>');
    
    $.get('{{ route("scanner.lookup") }}', { code: code })
        .done(function(response) {
            if (response.type === 'peminjaman') {
                renderPeminjaman(response.data);
            } else if (response.type === 'rekam_medis') {
                renderRekamMedis(response.data);
            } else {
                $('#result-content').html(`
                    <div class="text-center text-warning py-5">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <p class="mb-0">${response.message}</p>
                    </div>
                `);
            }
        })
        .fail(function() {
            $('#result-content').html(`
                <div class="text-center text-danger py-5">
                    <i class="fas fa-times-circle fa-3x mb-3"></i>
                    <p class="mb-0">Terjadi kesalahan</p>
                </div>
            `);
        });
}

function renderPeminjaman(data) {
    const statusClass = data.status === 'selesai' ? 'success' : 
                        data.status === 'ditolak' ? 'danger' : 
                        data.status === 'menunggu_persetujuan' ? 'warning' : 'primary';
    const statusLabel = data.status === 'selesai' ? 'Selesai' : 
                        data.status === 'ditolak' ? 'Ditolak' : 
                        data.status === 'menunggu_persetujuan' ? 'Menunggu' : 
                        data.status === 'dipinjam' ? 'Dipinjam' : data.status;

    let html = `
        <div class="alert alert-${data.is_terlambat ? 'danger' : 'info'}">
            <i class="fas fa-${data.is_terlambat ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${data.is_terlambat ? 'Dokumen TERLAMBAT!' : 'Peminjaman ditemukan'}
        </div>
        <table class="table table-borderless">
            <tr>
                <td class="text-muted" style="width: 140px;">No. Peminjaman</td>
                <td class="fw-semibold">${data.no_peminjaman}</td>
            </tr>
            <tr>
                <td class="text-muted">Status</td>
                <td><span class="badge bg-${statusClass}">${statusLabel}</span></td>
            </tr>
            <tr>
                <td class="text-muted">Peminjam</td>
                <td>${data.peminjam}</td>
            </tr>
            <tr>
                <td class="text-muted">Tgl Pinjam</td>
                <td>${data.tanggal_pinjam}</td>
            </tr>
            <tr>
                <td class="text-muted">Jatuh Tempo</td>
                <td class="${data.is_terlambat ? 'text-danger fw-semibold' : ''}">${data.tanggal_kembali_rencana}</td>
            </tr>
        </table>
        ${data.dokumen.length > 0 ? `
        <h6 class="mt-4 mb-3">Dokumen:</h6>
        <ul class="list-group">
            ${data.dokumen.map(d => `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">${d.no_rekam_medis}</div>
                        <small class="text-muted">${d.nama_pasien}</small>
                    </div>
                    <a href="/rekam-medis/${d.id}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                </li>
            `).join('')}
        </ul>
        ` : ''}
        <div class="mt-4">
            <a href="/peminjaman/${data.id}" class="btn-primary-custom">
                <i class="fas fa-eye me-1"></i> Lihat Detail
            </a>
        </div>
    `;
    $('#result-content').html(html);
}

function renderRekamMedis(data) {
    const statusClass = data.status_dokumen === 'tersedia' ? 'success' : 'warning';
    const statusLabel = data.status_dokumen === 'tersedia' ? 'Tersedia' : 'Dipinjam';

    let html = `
        <div class="alert alert-${data.status_dokumen === 'tersedia' ? 'success' : 'warning'}">
            <i class="fas fa-${data.status_dokumen === 'tersedia' ? 'check-circle' : 'clock'} me-2"></i>
            Dokumen ${statusLabel}
        </div>
        <table class="table table-borderless">
            <tr>
                <td class="text-muted" style="width: 140px;">No. Rekam Medis</td>
                <td class="fw-semibold">${data.no_rekam_medis}</td>
            </tr>
            <tr>
                <td class="text-muted">Nama Pasien</td>
                <td>${data.nama_pasien}</td>
            </tr>
            <tr>
                <td class="text-muted">Status</td>
                <td><span class="badge bg-${statusClass}">${statusLabel}</span></td>
            </tr>
        </table>
        <div class="mt-4">
            <a href="/rekam-medis/${data.id}" class="btn-primary-custom">
                <i class="fas fa-eye me-1"></i> Lihat Detail
            </a>
        </div>
    `;
    $('#result-content').html(html);
}
</script>
@endpush
