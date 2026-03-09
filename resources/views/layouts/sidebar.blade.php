<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-notes-medical"></i>
        </div>
        <div class="brand-text">
            <span class="brand-name">MedTrack</span>
            <span class="brand-subtitle">Rekam Medis</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-label">Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large nav-icon"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-label">Rekam Medis</div>
            <a href="{{ route('pasien.index') }}" class="nav-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                <i class="fas fa-user-injured nav-icon"></i>
                <span>Data Pasien</span>
            </a>
            <a href="{{ route('rekam-medis.index') }}" class="nav-link {{ request()->routeIs('rekam-medis.*') ? 'active' : '' }}">
                <i class="fas fa-file-medical nav-icon"></i>
                <span>Dokumen Rekam Medis</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-label">Peminjaman</div>
            <a href="{{ route('peminjaman.index') }}" class="nav-link {{ request()->routeIs('peminjaman.index') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-medical nav-icon"></i>
                <span>Peminjaman</span>
                @php $menunggu = \App\Models\Peminjaman::menungguPersetujuan()->count(); @endphp
                @if($menunggu > 0)
                <span class="nav-badge">{{ $menunggu }}</span>
                @endif
            </a>
            <a href="{{ route('peminjaman.create') }}" class="nav-link {{ request()->routeIs('peminjaman.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle nav-icon"></i>
                <span>Buat Permohonan</span>
            </a>
            <a href="{{ route('pengembalian.index') }}" class="nav-link {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
                <i class="fas fa-undo-alt nav-icon"></i>
                <span>Pengembalian</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-label">Laporan</div>
            <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar nav-icon"></i>
                <span>Laporan & Statistik</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-label">Administrasi</div>
            <a href="{{ route('unit.index') }}" class="nav-link {{ request()->routeIs('unit.*') ? 'active' : '' }}">
                <i class="fas fa-hospital nav-icon"></i>
                <span>Unit & Poli</span>
            </a>
            <a href="{{ route('pengguna.index') }}" class="nav-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog nav-icon"></i>
                <span>Pengguna</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        @php $terlambat = \App\Models\Peminjaman::terlambat()->count(); @endphp
        @if($terlambat > 0)
        <div class="alert-terlambat">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ $terlambat }} dokumen terlambat!</span>
        </div>
        @endif
    </div>
</aside>
