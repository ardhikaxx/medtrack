@extends('layouts.app')

@section('title', 'Notifikasi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
<li class="breadcrumb-item active">Notifikasi</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Daftar Notifikasi</h5>
                    @php $unreadCount = auth()->user()->notifikasis()->where('is_read', false)->count(); @endphp
                    @if($unreadCount > 0)
                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-check-double me-1"></i>Tandai Semua Dibaca
                        </button>
                    </form>
                    @endif
                </div>
                <div class="card-body p-0">
                    @forelse($notifications as $notif)
                    <div class="notif-list-item {{ $notif->is_read ? '' : 'bg-light' }}">
                        <div class="d-flex p-3 align-items-start">
                            <div class="notif-icon bg-{{ $notif->tipe === 'success' ? 'success' : ($notif->tipe === 'danger' ? 'danger' : ($notif->tipe === 'warning' ? 'warning' : 'primary')) }}">
                                <i class="fas fa-{{ $notif->tipe === 'success' ? 'check' : ($notif->tipe === 'danger' ? 'times' : ($notif->tipe === 'warning' ? 'exclamation' : 'info')) }}"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">{{ $notif->judul }}</h6>
                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-muted" style="font-size: 14px;">{{ $notif->pesan }}</p>
                                <div class="d-flex gap-2">
                                    @if($notif->url_tujuan)
                                    <a href="{{ $notif->url_tujuan }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </a>
                                    @endif
                                    @if(!$notif->is_read)
                                    <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-check me-1"></i>Tandai Dibaca
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!$loop->last)
                    <hr class="my-0">
                    @endif
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada notifikasi</p>
                    </div>
                    @endforelse
                </div>
                @if($notifications->hasPages())
                <div class="card-footer">
                    {{ $notifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.notif-list-item {
    transition: background-color 0.2s;
}
.notif-list-item:hover {
    background-color: #f8f9fa;
}
</style>
