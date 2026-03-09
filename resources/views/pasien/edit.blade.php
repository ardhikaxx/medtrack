@extends('layouts.app')
@section('title', 'Edit Pasien')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('pasien.index') }}">Data Pasien</a></li>
<li class="breadcrumb-item active">Edit Pasien</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Pasien</h1>
        <p class="page-subtitle">{{ $pasien->no_rekam_medis }}</p>
    </div>
</div>

<form action="{{ route('pasien.update', $pasien) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header-custom">
                    <div class="card-header-title"><i class="fas fa-user"></i> Data Pribadi</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{ $pasien->nama_lengkap }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" value="{{ $pasien->nik }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="L" {{ $pasien->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $pasien->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tempat Lahir <span class="required">*</span></label>
                            <input type="text" name="tempat_lahir" class="form-control" value="{{ $pasien->tempat_lahir }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Lahir <span class="required">*</span></label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ $pasien->tanggal_lahir }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Agama</label>
                            <select name="agama" class="form-select">
                                @foreach(['islam','kristen','katolik','hindu','buddha','konghucu'] as $agama)
                                <option value="{{ $agama }}" {{ $pasien->agama == $agama ? 'selected' : '' }}>{{ ucfirst($agama) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ $pasien->no_hp }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Pasien</label>
                            <select name="status_pasien" class="form-select">
                                <option value="aktif" {{ $pasien->status_pasien == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ $pasien->status_pasien == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header-custom">
                    <div class="card-header-title">Aksi</div>
                </div>
                <div class="card-body">
                    <button type="submit" class="btn-primary-custom w-100 mb-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('pasien.index') }}" class="btn btn-outline-secondary w-100">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
