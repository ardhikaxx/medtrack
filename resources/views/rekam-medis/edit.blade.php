@extends('layouts.app')
@section('title', 'Edit Rekam Medis')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('rekam-medis.index') }}">Rekam Medis</a></li>
<li class="breadcrumb-item"><a href="{{ route('rekam-medis.show', $rekamMedis->id) }}">{{ $rekamMedis->kode_dokumen }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Rekam Medis</h1>
        <p class="page-subtitle">Kode: {{ $rekamMedis->kode_dokumen }}</p>
    </div>
</div>

<form action="{{ route('rekam-medis.update', $rekamMedis->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header-custom"><div class="card-header-title">Data Kunjungan</div></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">No. Rekam Medis</label>
                            <input type="text" class="form-control" value="{{ $rekamMedis->pasien->no_rekam_medis ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Pasien</label>
                            <input type="text" class="form-control" value="{{ $rekamMedis->pasien->nama_lengkap ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Kunjungan <span class="required">*</span></label>
                            <input type="date" name="tanggal_kunjungan" class="form-control" value="{{ $rekamMedis->tanggal_kunjungan->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Poli</label>
                            <select name="poli_id" class="form-select">
                                <option value="">Pilih Poli</option>
                                @foreach($polis as $poli)
                                <option value="{{ $poli->id }}" {{ $rekamMedis->poli_id == $poli->id ? 'selected' : '' }}>{{ $poli->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dokter</label>
                            <select name="dokter_id" class="form-select">
                                <option value="">Pilih Dokter</option>
                                @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id }}" {{ $rekamMedis->dokter_id == $dokter->id ? 'selected' : '' }}>{{ $dokter->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kunjungan <span class="required">*</span></label>
                            <select name="jenis_kunjungan" class="form-select" required>
                                <option value="rawat_jalan" {{ $rekamMedis->jenis_kunjungan == 'rawat_jalan' ? 'selected' : '' }}>Rawat Jalan</option>
                                <option value="rawat_inap" {{ $rekamMedis->jenis_kunjungan == 'rawat_inap' ? 'selected' : '' }}>Rawat Inap</option>
                                <option value="ugd" {{ $rekamMedis->jenis_kunjungan == 'ugd' ? 'selected' : '' }}>UGD</option>
                                <option value="konsultasi" {{ $rekamMedis->jenis_kunjungan == 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kondisi Dokumen</label>
                            <select name="kondisi_dokumen" class="form-select">
                                <option value="baik" {{ $rekamMedis->kondisi_dokumen == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="cukup" {{ $rekamMedis->kondisi_dokumen == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                <option value="rusak_ringan" {{ $rekamMedis->kondisi_dokumen == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ $rekamMedis->kondisi_dokumen == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Halaman</label>
                            <input type="number" name="jumlah_halaman" class="form-control" value="{{ $rekamMedis->jumlah_halaman }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Diagnosa Utama</label>
                            <input type="text" name="diagnosa_utama" class="form-control" value="{{ $rekamMedis->diagnosa_utama }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode ICD-10</label>
                            <input type="text" name="kode_icd10" class="form-control" value="{{ $rekamMedis->kode_icd10 }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan_dokumen" class="form-control" rows="3">{{ $rekamMedis->catatan_dokumen }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header-custom"><div class="card-header-title">Lokasi Penyimpanan</div></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Rak</label>
                            <input type="text" name="rak" class="form-control" value="{{ $rekamMedis->rak }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Laci</label>
                            <input type="text" name="laci" class="form-control" value="{{ $rekamMedis->laci }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Map/Folder</label>
                            <input type="text" name="map_folder" class="form-control" value="{{ $rekamMedis->map_folder }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header-custom"><div class="card-header-title">Aksi</div></div>
                <div class="card-body">
                    <button type="submit" class="btn-primary-custom w-100 mb-2"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('rekam-medis.show', $rekamMedis->id) }}" class="btn btn-outline-secondary w-100">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
