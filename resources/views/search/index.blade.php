@extends('layouts.app')

@section('title', 'Pencarian Global')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Pencarian</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pencarian Global</h1>
        <p class="page-subtitle">Cari data pasien, rekam medis, peminjaman, dan pengguna</p>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="global-search" class="form-control" placeholder="Ketik untuk mencari..." autofocus>
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="search-type" class="form-select">
                        <option value="all">Semua Kategori</option>
                        <option value="pasien">Pasien</option>
                        <option value="rekam_medis">Rekam Medis</option>
                        <option value="peminjaman">Peminjaman</option>
                        <option value="pengguna">Pengguna</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="search-results" class="p-4 pt-0">
            <div class="text-center text-muted py-5">
                <i class="fas fa-search fa-3x mb-3" style="opacity: 0.3;"></i>
                <p class="mb-0">Masukkan kata kunci untuk memulai pencarian</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let debounceTimer;
    
    function performSearch(query, type) {
        if (query.length < 2) {
            $('#search-results').html(`
                <div class="text-center text-muted py-5">
                    <i class="fas fa-search fa-3x mb-3" style="opacity: 0.3;"></i>
                    <p class="mb-0">Masukkan kata kunci untuk memulai pencarian</p>
                </div>
            `);
            return;
        }

        $('#search-results').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Mencari...</p></div>');

        $.get('{{ route("search.results") }}', { q: query, type: type })
            .done(function(data) {
                let html = '';
                
                if (data.pasien.length === 0 && data.rekam_medis.length === 0 && 
                    data.peminjaman.length === 0 && data.pengguna.length === 0) {
                    html = `
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-folder-open fa-3x mb-3" style="opacity: 0.3;"></i>
                            <p class="mb-0">Tidak ada hasil yang ditemukan</p>
                        </div>
                    `;
                } else {
                    if (data.pasien.length > 0) {
                        html += '<h6 class="text-uppercase text-muted mb-3" style="font-size: 12px; font-weight: 600;"><i class="fas fa-users me-2"></i>Pasien (' + data.pasien.length + ')</h6>';
                        html += '<div class="list-group list-group-flush mb-4">';
                        data.pasien.forEach(function(item) {
                            html += `
                                <a href="/pasien/${item.id}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary me-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">${item.nama}</div>
                                            <small class="text-muted">No. RM: ${item.no_rm}</small>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            `;
                        });
                        html += '</div>';
                    }

                    if (data.rekam_medis.length > 0) {
                        html += '<h6 class="text-uppercase text-muted mb-3" style="font-size: 12px; font-weight: 600;"><i class="fas fa-file-medical me-2"></i>Rekam Medis (' + data.rekam_medis.length + ')</h6>';
                        html += '<div class="list-group list-group-flush mb-4">';
                        data.rekam_medis.forEach(function(item) {
                            html += `
                                <a href="/rekam-medis/${item.id}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-info me-3">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">${item.no_rekam_medis}</div>
                                            <small class="text-muted">${item.nama_pasien || '-'}</small>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            `;
                        });
                        html += '</div>';
                    }

                    if (data.peminjaman.length > 0) {
                        html += '<h6 class="text-uppercase text-muted mb-3" style="font-size: 12px; font-weight: 600;"><i class="fas fa-handshake me-2"></i>Peminjaman (' + data.peminjaman.length + ')</h6>';
                        html += '<div class="list-group list-group-flush mb-4">';
                        data.peminjaman.forEach(function(item) {
                            html += `
                                <a href="/peminjaman/${item.id}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-warning me-3">
                                            <i class="fas fa-exchange-alt"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">${item.no_peminjaman}</div>
                                            <small class="text-muted">${item.nama_peminjam_luar || '-'} - ${item.institusi_peminjam || '-'}</small>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            `;
                        });
                        html += '</div>';
                    }

                    if (data.pengguna.length > 0) {
                        html += '<h6 class="text-uppercase text-muted mb-3" style="font-size: 12px; font-weight: 600;"><i class="fas fa-user-cog me-2"></i>Pengguna (' + data.pengguna.length + ')</h6>';
                        html += '<div class="list-group list-group-flush mb-4">';
                        data.pengguna.forEach(function(item) {
                            html += `
                                <a href="/pengguna/${item.id}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-success me-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">${item.nama_lengkap}</div>
                                            <small class="text-muted">${item.email}</small>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            `;
                        });
                        html += '</div>';
                    }
                }
                
                $('#search-results').html(html);
            })
            .fail(function() {
                $('#search-results').html(`
                    <div class="text-center text-danger py-5">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <p class="mb-0">Terjadi kesalahan saat mencari</p>
                    </div>
                `);
            });
    }

    $('#global-search').on('input', function() {
        clearTimeout(debounceTimer);
        const query = $(this).val();
        const type = $('#search-type').val();
        debounceTimer = setTimeout(function() {
            performSearch(query, type);
        }, 300);
    });

    $('#search-type').change(function() {
        const query = $('#global-search').val();
        performSearch(query, $(this).val());
    });
});
</script>
@endpush
