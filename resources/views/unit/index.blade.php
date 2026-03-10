@extends('layouts.app')
@section('title', 'Unit & Poli')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Unit & Poli</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Unit & Poli</h1>
        <p class="page-subtitle">Kelola unit dan poli klinik</p>
    </div>
    <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus-circle"></i> Tambah Unit
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Unit</th>
                        <th>Jenis</th>
                        <th>Lantai</th>
                        <th>Gedung</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr>
                        <td><strong>{{ $unit->kode_unit }}</strong></td>
                        <td class="fw-semibold">{{ $unit->nama_unit }}</td>
                        <td>
                            @if($unit->jenis_unit == 'poli')
                            <span class="badge bg-info">Poli</span>
                            @elseif($unit->jenis_unit == 'ugd')
                            <span class="badge bg-danger">UGD</span>
                            @elseif($unit->jenis_unit == 'rawat_inap')
                            <span class="badge bg-primary">Rawat Inap</span>
                            @elseif($unit->jenis_unit == 'penunjang')
                            <span class="badge bg-warning">Penunjang</span>
                            @elseif($unit->jenis_unit == 'administrasi')
                            <span class="badge bg-secondary">Administrasi</span>
                            @else
                            <span class="badge bg-light text-dark">Lainnya</span>
                            @endif
                        </td>
                        <td>{{ $unit->lantai ?? '-' }}</td>
                        <td>{{ $unit->gedung ?? '-' }}</td>
                        <td>
                            @if($unit->is_active)
                            <span class="status-badge status-selesai">Aktif</span>
                            @else
                            <span class="status-badge status-ditolak">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-icon btn-outline-primary" title="Edit" 
                                    data-bs-toggle="modal" data-bs-target="#modalEdit{{ $unit->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('unit.destroy', $unit) }}" method="POST" class="d-inline" id="delete-unit-{{ $unit->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger" title="Hapus"
                                        onclick="confirmDeleteMsg('unit {{ $unit->nama_unit }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit{{ $unit->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('unit.update', $unit) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Unit</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Kode Unit <span class="required">*</span></label>
                                            <input type="text" name="kode_unit" class="form-control" value="{{ $unit->kode_unit }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Unit <span class="required">*</span></label>
                                            <input type="text" name="nama_unit" class="form-control" value="{{ $unit->nama_unit }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jenis Unit <span class="required">*</span></label>
                                            <select name="jenis_unit" class="form-select" required>
                                                <option value="poli" {{ $unit->jenis_unit == 'poli' ? 'selected' : '' }}>Poli</option>
                                                <option value="ugd" {{ $unit->jenis_unit == 'ugd' ? 'selected' : '' }}>UGD</option>
                                                <option value="rawat_inap" {{ $unit->jenis_unit == 'rawat_inap' ? 'selected' : '' }}>Rawat Inap</option>
                                                <option value="penunjang" {{ $unit->jenis_unit == 'penunjang' ? 'selected' : '' }}>Penunjang</option>
                                                <option value="administrasi" {{ $unit->jenis_unit == 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                                                <option value="lainnya" {{ $unit->jenis_unit == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Lantai</label>
                                                <input type="text" name="lantai" class="form-control" value="{{ $unit->lantai }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Gedung</label>
                                                <input type="text" name="gedung" class="form-control" value="{{ $unit->gedung }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">No. Telepon</label>
                                            <input type="text" name="no_telp_unit" class="form-control" value="{{ $unit->no_telp_unit }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <textarea name="keterangan" class="form-control" rows="2">{{ $unit->keterangan }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="status{{ $unit->id }}" {{ $unit->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status{{ $unit->id }}">Unit Aktif</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn-primary-custom">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state py-5">
                                <div class="empty-state-icon"><i class="fas fa-hospital"></i></div>
                                <div class="empty-state-title">Tidak ada unit</div>
                                <div class="empty-state-text">Silakan tambah unit baru untuk memulai</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($units->hasPages())
        {{ $units->links('vendor.pagination.medtrack') }}
        @endif
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('unit.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Unit Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Unit <span class="required">*</span></label>
                        <input type="text" name="kode_unit" class="form-control" placeholder="Contoh: POLI-UMUM" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Unit <span class="required">*</span></label>
                        <input type="text" name="nama_unit" class="form-control" placeholder="Contoh: Poli Umum" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Unit <span class="required">*</span></label>
                        <select name="jenis_unit" class="form-select" required>
                            <option value="">Pilih Jenis Unit</option>
                            <option value="poli">Poli</option>
                            <option value="ugd">UGD</option>
                            <option value="rawat_inap">Rawat Inap</option>
                            <option value="penunjang">Penunjang</option>
                            <option value="administrasi">Administrasi</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lantai</label>
                            <input type="text" name="lantai" class="form-control" placeholder="Contoh: 2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gedung</label>
                            <input type="text" name="gedung" class="form-control" placeholder="Contoh: Gedung A">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp_unit" class="form-control" placeholder="Contoh: 021-1234567">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom">Tambah Unit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
