<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MedTrack') — Sistem Rekam Medis Husada</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/medtrack.css') }}">

    @stack('styles')
</head>
<body class="medtrack-body">

    @auth
    @include('layouts.sidebar')

    <div class="content-wrapper" id="content-wrapper">
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-icon sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-icon position-relative" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        @php $unread = auth()->user()->notifikasis()->where('is_read', false)->count(); @endphp
                        @if($unread > 0)
                        <span class="badge-notif">{{ $unread }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notif-dropdown">
                        <div class="notif-header">
                            <span>Notifikasi</span>
                            <a href="#" class="small">Tandai semua dibaca</a>
                        </div>
                        <div class="notif-list">
                            @forelse(auth()->user()->notifikasis()->latest()->take(5)->get() as $notif)
                            <a href="{{ $notif->url_tujuan ?? '#' }}" class="notif-item {{ $notif->is_read ? '' : 'unread' }}">
                                <div class="notif-icon bg-{{ $notif->tipe === 'success' ? 'success' : ($notif->tipe === 'danger' ? 'danger' : 'primary') }}">
                                    <i class="fas fa-{{ $notif->tipe === 'success' ? 'check' : ($notif->tipe === 'danger' ? 'times' : 'info') }}"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:13px">{{ $notif->judul }}</div>
                                    <div style="font-size:11px; color:var(--text-secondary)">{{ $notif->created_at->diffForHumans() }}</div>
                                </div>
                            </a>
                            @empty
                            <div class="text-center py-4 text-muted">Tidak ada notifikasi</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn user-avatar-btn d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        @if(auth()->user()->foto_profil)
                        <img src="{{ asset('storage/'.auth()->user()->foto_profil) }}" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;" alt="">
                        @else
                        <div class="avatar avatar-sm avatar-primary">
                            {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
                        </div>
                        @endif
                        <div class="d-none d-md-block text-start">
                            <div class="fw-semibold" style="font-size:13px;line-height:1.2;">{{ auth()->user()->nama_lengkap }}</div>
                            <div style="font-size:11px;color:var(--text-secondary);line-height:1.2;">{{ auth()->user()->role->label ?? '' }}</div>
                        </div>
                        <i class="fas fa-chevron-down ms-1 small" style="font-size:10px;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profil.index') }}"><i class="fas fa-user me-2"></i>Profil Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" onclick="confirmLogout(event)"><i class="fas fa-sign-out-alt me-2"></i>Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="main-content">
            @yield('content')
        </main>

        <footer class="main-footer">
            <span>© {{ date('Y') }} MedTrack — Klinik Pratama Rawat Inap Husada</span>
            <span>v1.0.0</span>
        </footer>
    </div>
    @endauth

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/medtrack.js') }}"></script>

    <script>
        // Logout Confirmation
        function confirmLogout(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-sign-out-alt"></i> Ya, Keluar',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // Delete Confirmation
        function confirmDelete(formId, itemName = 'item ini') {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus ' + itemName + '? Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Delete with custom message
        function confirmDeleteMsg(itemName = 'item ini') {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus ' + itemName + '? Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }

        // Show success toast
        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }

        // Show error toast
        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        }

        // Confirm Peminjaman Setuju
        function confirmSetuju(id) {
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: 'Apakah Anda yakin ingin menyetujui peminjaman ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-check"></i> Ya, Setuju',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-setuju-' + id).submit();
                }
            });
        }

        // Confirm Peminjaman Tolak
        function confirmTolak(id) {
            const alasan = document.querySelector('input[name="alasan_penolakan"]').value;
            if (!alasan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Silakan isi alasan penolakan terlebih dahulu!'
                });
                return;
            }
            Swal.fire({
                title: 'Konfirmasi Penolakan',
                text: 'Apakah Anda yakin ingin menolak peminjaman ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-times"></i> Ya, Tolak',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }

        // Confirm Peminjaman Proses
        function confirmProses(id) {
            Swal.fire({
                title: 'Konfirmasi Proses Penyerahan',
                text: 'Apakah Anda yakin ingin memproses penyerahan dokumen?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1a6f8a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-check"></i> Ya, Proses',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-proses-' + id).submit();
                }
            });
        }

        // Toggle Password Visibility
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

    @if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session("error") }}', toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
