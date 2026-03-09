@extends('layouts.app')
@section('title', 'Tambah Pasien')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('pasien.index') }}">Data Pasien</a></li>
<li class="breadcrumb-item active">Tambah Pasien</li>
@endsection

@push('scripts')
<script>
console.log('Script loading...');
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM ready');
    
    const provinceSelect = $('#provinsi');
    const regencySelect = $('#kota_kabupaten');
    const districtSelect = $('#kecamatan');
    const villageSelect = $('#kelurahan');

    if (typeof $ === 'undefined') {
        console.error('jQuery not loaded!');
        return;
    }
    
    if (typeof $.fn.select2 === 'undefined') {
        console.error('Select2 not loaded!');
        return;
    }
    
    console.log('Initializing Select2...');
    
    provinceSelect.select2({ placeholder: 'Pilih Provinsi', allowClear: true });
    regencySelect.select2({ placeholder: 'Pilih Kota/Kabupaten', allowClear: true, disabled: true });
    districtSelect.select2({ placeholder: 'Pilih Kecamatan', allowClear: true, disabled: true });
    villageSelect.select2({ placeholder: 'Pilih Kelurahan', allowClear: true, disabled: true });

    console.log('Fetching provinces from /api/wilayah/provinces...');
    
    fetch('/api/wilayah/provinces')
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.json();
        })
        .then(data => {
            console.log('Provinces data:', data.length, 'items');
            if (!data || data.length === 0) {
                console.warn('No provinces data received');
                return;
            }
            data.forEach(province => {
                const option = new Option(province.name, province.name, false, false);
                provinceSelect.append(option);
            });
            provinceSelect.trigger('change');
            console.log('Provinces populated');
        })
        .catch(error => {
            console.error('Error fetching provinces:', error);
            alert('Gagal memuat data provinsi. Silakan refresh halaman.');
        });

    // Province change - load regencies
    provinceSelect.on('change', function() {
        const provinceName = $(this).val();
        
        regencySelect.empty().append(new Option('Pilih Kota/Kabupaten', '', false, false)).trigger('change');
        districtSelect.empty().append(new Option('Pilih Kecamatan', '', false, false)).trigger('change');
        districtSelect.prop('disabled', true).trigger('change');
        villageSelect.empty().append(new Option('Pilih Kelurahan', '', false, false)).trigger('change');
        villageSelect.prop('disabled', true).trigger('change');
        
        $('#provinsi_input').val(provinceName);
        
        if (provinceName) {
            fetch('/api/wilayah/provinces')
                .then(res => res.json())
                .then(data => {
                    const province = data.find(p => p.name === provinceName);
                    if (province) {
                        fetch(`/api/wilayah/regencies/${province.id}`)
                            .then(res => res.json())
                            .then(data => {
                                data.forEach(regency => {
                                    const option = new Option(regency.name, regency.name, false, false);
                                    regencySelect.append(option);
                                });
                                regencySelect.prop('disabled', false).trigger('change');
                            })
                            .catch(err => console.error('Error loading regencies:', err));
                    }
                })
                .catch(err => console.error('Error finding province:', err));
        }
    });

    // Regency change - load districts
    regencySelect.on('change', function() {
        const regencyName = $(this).val();
        
        districtSelect.empty().append(new Option('Pilih Kecamatan', '', false, false)).trigger('change');
        villageSelect.empty().append(new Option('Pilih Kelurahan', '', false, false)).trigger('change');
        villageSelect.prop('disabled', true).trigger('change');
        
        $('#kota_kabupaten_input').val(regencyName);
        
        if (regencyName) {
            fetch('/api/wilayah/provinces')
                .then(res => res.json())
                .then(data => {
                    const province = data.find(p => p.name === provinceSelect.val());
                    if (province) {
                        fetch(`/api/wilayah/regencies/${province.id}`)
                            .then(res => res.json())
                            .then(data => {
                                const regency = data.find(r => r.name === regencyName);
                                if (regency) {
                                    fetch(`/api/wilayah/districts/${regency.id}`)
                                        .then(res => res.json())
                                        .then(data => {
                                            data.forEach(district => {
                                                const option = new Option(district.name, district.name, false, false);
                                                districtSelect.append(option);
                                            });
                                            districtSelect.prop('disabled', false).trigger('change');
                                        })
                                        .catch(err => console.error('Error loading districts:', err));
                                }
                            })
                            .catch(err => console.error('Error loading regencies:', err));
                    }
                })
                .catch(err => console.error('Error finding province:', err));
        }
    });

    // District change - load villages
    districtSelect.on('change', function() {
        const districtName = $(this).val();
        
        villageSelect.empty().append(new Option('Pilih Kelurahan', '', false, false)).trigger('change');
        
        $('#kecamatan_input').val(districtName);
        
        if (districtName) {
            fetch('/api/wilayah/provinces')
                .then(res => res.json())
                .then(data => {
                    const province = data.find(p => p.name === provinceSelect.val());
                    if (province) {
                        fetch(`/api/wilayah/regencies/${province.id}`)
                            .then(res => res.json())
                            .then(data => {
                                const regency = data.find(r => r.name === regencySelect.val());
                                if (regency) {
                                    fetch(`/api/wilayah/districts/${regency.id}`)
                                        .then(res => res.json())
                                        .then(data => {
                                            const district = data.find(d => d.name === districtName);
                                            if (district) {
                                                fetch(`/api/wilayah/villages/${district.id}`)
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        data.forEach(village => {
                                                            const option = new Option(village.name, village.name, false, false);
                                                            villageSelect.append(option);
                                                        });
                                                        villageSelect.prop('disabled', false).trigger('change');
                                                    })
                                                    .catch(err => console.error('Error loading villages:', err));
                                            }
                                        })
                                        .catch(err => console.error('Error loading districts:', err));
                                }
                            })
                            .catch(err => console.error('Error loading regencies:', err));
                    }
                })
                .catch(err => console.error('Error finding province:', err));
        }
    });

    // Village change
    villageSelect.on('change', function() {
        const villageName = $(this).val();
        $('#kelurahan_input').val(villageName);
    });
});
</script>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Pasien</h1>
        <p class="page-subtitle">Tambah data pasien baru</p>
    </div>
</div>

<form action="{{ route('pasien.store') }}" method="POST">
    @csrf
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
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Panggilan</label>
                            <input type="text" name="nama_panggilan" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" maxlength="16">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">No. KK</label>
                            <input type="text" name="no_kk" class="form-control" maxlength="16">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tempat Lahir <span class="required">*</span></label>
                            <input type="text" name="tempat_lahir" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Lahir <span class="required">*</span></label>
                            <input type="date" name="tanggal_lahir" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Golongan Darah</label>
                            <select name="golongan_darah" class="form-select">
                                <option value="">Pilih</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Agama <span class="required">*</span></label>
                            <select name="agama" class="form-select" required>
                                <option value="islam">Islam</option>
                                <option value="kristen">Kristen</option>
                                <option value="katolik">Katolik</option>
                                <option value="hindu">Hindu</option>
                                <option value="buddha">Buddha</option>
                                <option value="konghucu">Konghucu</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Pernikahan</label>
                            <select name="status_pernikahan" class="form-select">
                                <option value="belum_menikah">Belum Menikah</option>
                                <option value="menikah">Menikah</option>
                                <option value="cerai">Cerai</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header-custom">
                    <div class="card-header-title"><i class="fas fa-map-marker-alt"></i> Alamat</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                            <textarea name="alamat_lengkap" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">RT</label>
                            <input type="text" name="rt" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">RW</label>
                            <input type="text" name="rw" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Provinsi <span class="required">*</span></label>
                            <select id="provinsi" class="form-select" required>
                                <option value="">Pilih Provinsi</option>
                            </select>
                            <input type="hidden" name="provinsi" id="provinsi_input">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kota/Kabupaten <span class="required">*</span></label>
                            <select id="kota_kabupaten" class="form-select" required disabled>
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                            <input type="hidden" name="kota_kabupaten" id="kota_kabupaten_input">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kecamatan <span class="required">*</span></label>
                            <select id="kecamatan" class="form-select" required disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            <input type="hidden" name="kecamatan" id="kecamatan_input">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kelurahan <span class="required">*</span></label>
                            <select id="kelurahan" class="form-select" required disabled>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                            <input type="hidden" name="kelurahan" id="kelurahan_input">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header-custom">
                    <div class="card-header-title"><i class="fas fa-phone"></i> Kontak & Jaminan</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Jaminan <span class="required">*</span></label>
                            <select name="jenis_jaminan" class="form-select" required>
                                <option value="umum">Umum</option>
                                <option value="bpjs_kesehatan">BPJS Kesehatan</option>
                                <option value="bpjs_ketenagakerjaan">BPJS Ketenagakerjaan</option>
                                <option value="asuransi_swasta">Asuransi Swasta</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Jaminan</label>
                            <input type="text" name="no_jaminan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kelas Jaminan</label>
                            <input type="text" name="kelas_jaminan" class="form-control">
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
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('pasien.index') }}" class="btn btn-outline-secondary w-100">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
