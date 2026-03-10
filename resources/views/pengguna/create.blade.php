@extends('layouts.app')
@section('title', 'Tambah Pengguna')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('pengguna.index') }}">Pengguna</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Pengguna</h1></div>
</div>

<form method="POST" action="{{ route('pengguna.store') }}">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header-custom"><div class="card-header-title">Data Pengguna</div></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="required">*</span></label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="required">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', 'password-icon')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password Konfirmasi <span class="required">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation', 'password-confirm-icon')">
                                    <i class="fas fa-eye" id="password-confirm-icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="required">*</span></label>
                            <select name="role_id" class="form-select" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" class="form-select">
                                <option value="">Pilih Unit</option>
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Pengguna</label>
                            <select name="jenis_pengguna" class="form-select">
                                <option value="internal">Internal</option>
                                <option value="eksternal">Eksternal</option>
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
                    <a href="{{ route('pengguna.index') }}" class="btn btn-outline-secondary w-100">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
