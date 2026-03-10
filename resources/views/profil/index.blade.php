@extends('layouts.app')
@section('title', 'Profil Saya')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Profil Saya</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Profil Saya</h1>
        <p class="page-subtitle">Kelola data profil dan password Anda</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="avatar avatar-lg avatar-primary mx-auto mb-4" style="width:100px;height:100px;font-size:40px;">
                    {{ strtoupper(substr($user->nama_lengkap, 0, 2)) }}
                </div>
                <h4 class="mb-1">{{ $user->nama_lengkap }}</h4>
                <p class="text-muted mb-2">{{ $user->role->label ?? 'Pengguna' }}</p>
                <span class="status-badge" style="background: linear-gradient(135deg, #e8f8f0 0%, #d4efdf 100%); color: #27ae60;">
                    <i class="fas fa-check-circle me-1"></i> Aktif
                </span>
            </div>
            <div class="card-body border-top">
                <div class="d-flex align-items-center justify-content-between py-2">
                    <span class="text-muted">Email</span>
                    <span class="fw-semibold">{{ $user->email }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-2">
                    <span class="text-muted">Username</span>
                    <span class="fw-semibold">{{ $user->username }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-2">
                    <span class="text-muted">NIK</span>
                    <span class="fw-semibold">{{ $user->nik ?? '-' }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-2">
                    <span class="text-muted">NIP</span>
                    <span class="fw-semibold">{{ $user->nip ?? '-' }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-2">
                    <span class="text-muted">Unit</span>
                    <span class="fw-semibold">{{ $user->unit->nama_unit ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header-custom">
                <div class="card-header-title">
                    <i class="fas fa-user-edit"></i>
                    Edit Profil
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{ $user->nama_lengkap }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" value="{{ $user->nik }}" maxlength="16">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control" value="{{ $user->nip }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control" value="{{ $user->no_telp }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ $user->jabatan }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <small class="text-muted">Email tidak dapat diubah</small>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header-custom">
                <div class="card-header-title">
                    <i class="fas fa-lock"></i>
                    Ubah Password
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('profil.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Password Saat Ini <span class="required">*</span></label>
                            <div class="input-group">
                                <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Masukkan password saat ini" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password', 'current-password-icon')">
                                    <i class="fas fa-eye" id="current-password-icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password Baru <span class="required">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="password_baru" class="form-control" placeholder="Masukkan password baru (min 8 karakter)" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_baru', 'password-icon')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Konfirmasi password baru" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation', 'password-confirm-icon')">
                                    <i class="fas fa-eye" id="password-confirm-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn-primary-custom" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
