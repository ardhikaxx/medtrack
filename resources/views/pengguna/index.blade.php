@extends('layouts.app')
@section('title', 'Pengguna')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Pengguna</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Pengguna</h1>
        <p class="page-subtitle">Kelola akun pengguna sistem</p>
    </div>
    <a href="{{ route('pengguna.create') }}" class="btn-primary-custom">
        <i class="fas fa-plus"></i> Tambah Pengguna
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Unit</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->nama_lengkap }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->label ?? '-' }}</td>
                    <td>{{ $user->unit->nama_unit ?? '-' }}</td>
                    <td>
                        @if($user->is_active)
                        <span class="status-badge status-selesai">Aktif</span>
                        @else
                        <span class="status-badge status-ditolak">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pengguna.show', $user) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
        <div class="card-footer">
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                </div>
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
