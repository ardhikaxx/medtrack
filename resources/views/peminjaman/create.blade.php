@extends('layouts.app')
@section('title', 'Buat Permohonan Peminjaman')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('peminjaman.index') }}">Peminjaman</a></li>
<li class="breadcrumb-item active">Buat Permohonan</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Buat Permohonan Peminjaman</h1>
        <p class="page-subtitle">Isi formulir permohonan peminjaman dokumen rekam medis</p>
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('peminjaman.store') }}" method="POST" id="form-peminjaman">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header-custom">
                    <div class="card-header-title"><i class="fas fa-clipboard-list"></i> Detail Permohonan</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tujuan Peminjaman <span class="required">*</span></label>
                            <select class="form-select" name="tujuan_peminjaman" id="tujuan_peminjaman" required>
                                <option value="">-- Pilih Tujuan --</option>
                                <option value="pelayanan">Pelayanan Pasien</option>
                                <option value="penelitian">Penelitian / Studi</option>
                                <option value="audit">Audit Medis / Kualitas</option>
                                <option value="pengadilan">Keperluan Pengadilan</option>
                                <option value="pendidikan">Pendidikan / Pelatihan</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan Keperluan Lengkap <span class="required">*</span></label>
                            <textarea class="form-control" name="keperluan_detail" rows="3" required placeholder="Jelaskan secara detail keperluan peminjaman..."></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Pinjam <span class="required">*</span></label>
                            <input type="date" class="form-control" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rencana Pengembalian <span class="required">*</span></label>
                            <input type="date" class="form-control" name="tanggal_kembali_rencana" min="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header-custom">
                    <div class="card-header-title"><i class="fas fa-search"></i> Pilih Dokumen Rekam Medis</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Cari Pasien <span class="required">*</span></label>
                        <select class="form-select select2-pasien" id="select_pasien" style="width:100%">
                            <option value="">Ketik nama, No. RM, atau NIK pasien...</option>
                        </select>
                    </div>
                    <div id="daftar-dokumen-pasien" style="display:none">
                        <label class="form-label">Dokumen Tersedia</label>
                        <div id="list-dokumen-pasien" class="row g-2"></div>
                    </div>
                    <div class="mt-4">
                        <label class="form-label">Dokumen yang Dipilih</label>
                        <div class="dokumen-terpilih" id="dokumen-terpilih" style="background: var(--primary-light); border: 2px dashed var(--primary); border-radius: var(--radius); padding: 16px; min-height: 100px;">
                            <div class="text-center text-muted py-3" id="empty-dokumen">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <div style="font-size:13px">Pilih pasien terlebih dahulu</div>
                            </div>
                        </div>
                        <div id="hidden-inputs"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 80px">
                <div class="card-header-custom">
                    <div class="card-header-title"><i class="fas fa-clipboard-check"></i> Ringkasan</div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="text-muted" style="font-size:13px">Jumlah Dokumen</span>
                        <span class="fw-semibold" style="font-size:13px" id="summary-jumlah">0 dokumen</span>
                    </div>
                    <button type="submit" class="btn-primary-custom w-100 justify-content-center" id="btn-submit">
                        <i class="fas fa-paper-plane"></i> Kirim Permohonan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
let dokumenTerpilih = [];

$(document).ready(function() {
    $('#select_pasien').select2({
        theme: 'bootstrap-5',
        placeholder: 'Ketik nama, No. RM, atau NIK pasien...',
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("pasien.select2") }}',
            dataType: 'json',
            delay: 300,
            data: (params) => ({ q: params.term }),
            processResults: (data) => ({
                results: data.map(p => ({
                    id: p.id,
                    text: p.no_rekam_medis + ' — ' + p.nama_lengkap
                }))
            })
        }
    }).on('select2:select', function(e) {
        loadDokumenPasien(e.params.data.id);
    });

    function loadDokumenPasien(pasienId) {
        $.get('/rekam-medis/by-pasien/' + pasienId, function(data) {
            let html = '';
            if (data.length === 0) {
                html = '<div class="col-12 text-muted text-center py-3">Tidak ada dokumen tersedia</div>';
            }
            data.forEach(rm => {
                html += '<div class="col-md-6"><div class="card border p-2" style="cursor:pointer;font-size:12px" data-rm=\'' + JSON.stringify(rm) + '\' data-id="' + rm.id + '"><div class="fw-semibold">' + rm.kode_dokumen + '</div><div class="text-muted">' + rm.tanggal_kunjungan + '</div></div></div>';
            });
            $('#list-dokumen-pasien').html(html);
            $('#daftar-dokumen-pasien').show();
        });
    }

    $(document).on('click', '.card[data-rm]', function() {
        const rm = $(this).data('rm');
        const idx = dokumenTerpilih.findIndex(d => d.id == rm.id);
        if (idx === -1) {
            dokumenTerpilih.push(rm);
            $(this).addClass('border-primary bg-light');
        } else {
            dokumenTerpilih.splice(idx, 1);
            $(this).removeClass('border-primary bg-light');
        }
        renderDokumenTerpilih();
    });

    function renderDokumenTerpilih() {
        const container = $('#dokumen-terpilih');
        const hiddenInputs = $('#hidden-inputs');
        
        if (dokumenTerpilih.length === 0) {
            container.html('<div class="text-center text-muted py-3" id="empty-dokumen"><i class="fas fa-inbox fa-2x mb-2"></div>');
        } else {
            let html = '';
            let inputs = '';
            dokumenTerpilih.forEach((rm, i) => {
                html += '<div class="d-flex justify-content-between align-items-center p-2 bg-white rounded mb-2"><div><div class="fw-semibold" style="font-size:13px">' + rm.kode_dokumen + '</div></div><span class="remove-dokumen text-danger" data-id="' + rm.id + '" style="cursor:pointer"><i class="fas fa-times"></i></span></div>';
                inputs += '<input type="hidden" name="rekam_medis_ids[]" value="' + rm.id + '">';
            });
            container.html(html);
            hiddenInputs.html(inputs);
        }
        $('#summary-jumlah').text(dokumenTerpilih.length + ' dokumen');
    }

    $(document).on('click', '.remove-dokumen', function() {
        const id = $(this).data('id');
        dokumenTerpilih = dokumenTerpilih.filter(d => d.id != id);
        $('.card[data-id="' + id + '"]').removeClass('border-primary bg-light');
        renderDokumenTerpilih();
    });

    $('#form-peminjaman').on('submit', function(e) {
        if (dokumenTerpilih.length === 0) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Pilih minimal 1 dokumen!' });
            return;
        }
    });
});
</script>
@endpush
@endsection
