@extends('layouts.app')
@section('title', 'Rekam Medis')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Rekam Medis</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Rekam Medis</h1>
    </div>
</div>

<form action="{{ route('rekam-medis.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header-custom"><div class="card-header-title">Data Kunjungan</div></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pasien <span class="required">*</span></label>
                            <select name="pasien_id" class="form-select select2" required>
                                <option value="">Pilih Pasien</option>
                                @foreach($pasiens as $pasien)
                                <option value="{{ $pasien->id }}">{{ $pasien->no_rekam_medis }} - {{ $pasien->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Kunjungan <span class="required">*</span></label>
                            <input type="date" name="tanggal_kunjungan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Poli</label>
                            <select name="poli_id" class="form-select">
                                <option value="">Pilih Poli</option>
                                @foreach($polis as $poli)
                                <option value="{{ $poli->id }}">{{ $poli->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dokter</label>
                            <select name="dokter_id" class="form-select">
                                <option value="">Pilih Dokter</option>
                                @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id }}">{{ $dokter->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kunjungan <span class="required">*</span></label>
                            <select name="jenis_kunjungan" class="form-select" required>
                                <option value="rawat_jalan">Rawat Jalan</option>
                                <option value="rawat_inap">Rawat Inap</option>
                                <option value="ugd">UGD</option>
                                <option value="konsultasi">Konsultasi</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kondisi Dokumen</label>
                            <select name="kondisi_dokumen" class="form-select">
                                <option value="baik">Baik</option>
                                <option value="cukup">Cukup</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header-custom"><div class="card-header-title">Aksi</div></div>
                <div class="card-body">
                    <button type="submit" class="btn-primary-custom w-100 mb-2"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('rekam-medis.index') }}" class="btn btn-outline-secondary w-100">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
