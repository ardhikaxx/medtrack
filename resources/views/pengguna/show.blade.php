@extends('layouts.app')
@section('title', 'Detail Pengguna')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('pengguna.index') }}">Pengguna</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <div><h1 class="page-title">{{ $user->nama_lengkap }}</h1></div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header-custom"><div class="card-header-title">Data Pengguna</div></div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><td style="width:120px">Nama</td><td class="fw-semibold">{{ $user->nama_lengkap }}</td></tr>
                    <tr><td>Username</td><td>{{ $user->username }}</td></tr>
                    <tr><td>Email</td><td>{{ $user->email }}</td></tr>
                    <tr><td>Role</td><td>{{ $user->role->label ?? '-' }}</td></tr>
                    <tr><td>Unit</td><td>{{ $user->unit->nama_unit ?? '-' }}</td></tr>
                    <tr><td>Status</td><td>
                        @if($user->is_active)
                        <span class="status-badge status-selesai">Aktif</span>
                        @else
                        <span class="status-badge status-ditolak">Nonaktif</span>
                        @endif
                    </td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
