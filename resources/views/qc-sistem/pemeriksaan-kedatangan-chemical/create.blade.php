@extends('layouts.app')

@section('title', 'Tambah Pemeriksaan Kedatangan Chemical')

@section('container')
<div id="main">
    <div class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Tambah Pemeriksaan Kedatangan Chemical</h3>
                        <p class="text-subtitle text-muted">Form untuk menambah data pemeriksaan kedatangan chemical</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-chemical.index') }}">Pemeriksaan Kedatangan Chemical</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="page-content">
            <section class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Form Pemeriksaan Kedatangan Chemical</h4>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <div class="fw-semibold mb-1">Data tidak bisa disimpan, periksa error berikut:</div>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('pemeriksaan-chemical.store') }}" method="POST">
                                @csrf
                                
                                <!-- SECTION 1: Informasi Dasar -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Informasi Dasar</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                    name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_shift">Shift</label>
                                                <select id="id_shift" class="form-control @error('id_shift') is-invalid @enderror" name="id_shift">
                                                    <option value="">Pilih Shift</option>
                                                     @foreach($shifts as $shift)
                                                        <option value="{{ $shift->id }}" {{ old('id_shift') == $shift->id ? 'selected' : '' }}>
                                                            {{ $shift->shift }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_shift')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="jenis_mobil">Jenis Mobil</label>
                                                <input type="text" id="jenis_mobil" class="form-control @error('jenis_mobil') is-invalid @enderror"
                                                    name="jenis_mobil" value="{{ old('jenis_mobil') }}" placeholder="Jenis Mobil">
                                                @error('jenis_mobil')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_mobil">No. Mobil</label>
                                                <input type="text" id="no_mobil" class="form-control @error('no_mobil') is-invalid @enderror"
                                                    name="no_mobil" value="{{ old('no_mobil') }}" placeholder="No. Mobil">
                                                @error('no_mobil')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nama_supir">Nama Supir</label>
                                                <input type="text" id="nama_supir" class="form-control @error('nama_supir') is-invalid @enderror"
                                                    name="nama_supir" value="{{ old('nama_supir') }}" placeholder="Nama Supir">
                                                @error('nama_supir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Segel/Gembok</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="segel_option" name="segel_gembok" value="segel" {{ old('segel_gembok') == 'segel' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="segel_option">
                                                        Segel
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="gembok_option" name="segel_gembok" value="gembok" {{ old('segel_gembok') == 'gembok' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gembok_option">
                                                        Gembok
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6" id="no_segel_container" style="display: {{ old('segel_gembok') == 'segel' ? 'block' : 'none' }};">
                                            <div class="form-group">
                                                <label for="no_segel">No. Segel</label>
                                                <input type="text" id="no_segel" class="form-control @error('no_segel') is-invalid @enderror"
                                                    name="no_segel" value="{{ old('no_segel') }}" placeholder="No. Segel">
                                                @error('no_segel')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <script>
                                        document.querySelectorAll('input[name="segel_gembok"]').forEach(function(radio) {
                                            radio.addEventListener('change', function() {
                                                const container = document.getElementById('no_segel_container');
                                                if (this.value === 'segel') {
                                                    container.style.display = 'block';
                                                } else {
                                                    container.style.display = 'none';
                                                    document.getElementById('no_segel').value = '';
                                                }
                                            });
                                        });
                                    </script>
                                </div>

                                <!-- SECTION 2: Kondisi Mobil Pengangkut (11 items) -->
                                <div class="form-section mb-4" id="kondisi-mobil-section">
                                    <h5 class="text-primary mb-3">Kondisi Mobil Pengangkut</h5>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="kondisi_mobil_check_all">
                                            <label class="form-check-label" for="kondisi_mobil_check_all">Centang semua (Ya)</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <!-- 1. Bersih -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>1. Bersih</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_ya" value="1" {{ old('kondisi_mobil.bersih') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bersih_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_tidak" value="0" {{ old('kondisi_mobil.bersih') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bersih_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 2. Bebas dari hama -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>2. Bebas dari hama</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_ya" value="1" {{ old('kondisi_mobil.bebas_hama') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_hama_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_tidak" value="0" {{ old('kondisi_mobil.bebas_hama') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_hama_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 3. Tidak Kondensasi -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>3. Tidak Kondensasi</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_ya" value="1" {{ old('kondisi_mobil.tidak_kondensasi') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_kondensasi_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_tidak" value="0" {{ old('kondisi_mobil.tidak_kondensasi') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_kondensasi_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 4. Bebas dari Produk Non Halal -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>4. Bebas dari Produk Non Halal</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_ya" value="1" {{ old('kondisi_mobil.bebas_produk_halal') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_produk_halal_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_tidak" value="0" {{ old('kondisi_mobil.bebas_produk_halal') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_produk_halal_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <!-- 5. Tidak Berbau Menyimpang -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>5. Tidak Berbau Menyimpang</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_ya" value="1" {{ old('kondisi_mobil.tidak_berbau') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_berbau_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_tidak" value="0" {{ old('kondisi_mobil.tidak_berbau') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_berbau_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 6. Tidak ada sampah -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>6. Tidak ada sampah</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_ya" value="1" {{ old('kondisi_mobil.tidak_ada_sampah') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_sampah_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_tidak" value="0" {{ old('kondisi_mobil.tidak_ada_sampah') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_sampah_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 7. Tidak ada pertumbuhan mikroba -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>7. Tidak ada pertumbuhan mikroba</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_ya" value="1" {{ old('kondisi_mobil.tidak_ada_mikroba') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_mikroba_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_tidak" value="0" {{ old('kondisi_mobil.tidak_ada_mikroba') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_mikroba_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 8. Lampu dan Cover tidak pecah -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>8. Lampu dan Cover tidak pecah</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_ya" value="1" {{ old('kondisi_mobil.lampu_cover_utuh') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="lampu_cover_utuh_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_tidak" value="0" {{ old('kondisi_mobil.lampu_cover_utuh') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="lampu_cover_utuh_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <!-- 9. Pallet / Alas Utuh -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>9. Pallet / Alas Utuh</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_ya" value="1" {{ old('kondisi_mobil.pallet_utuh') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="pallet_utuh_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_tidak" value="0" {{ old('kondisi_mobil.pallet_utuh') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="pallet_utuh_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 10. Tertutup rapat/tidak bocor -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>10. Tertutup rapat/tidak bocor</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_ya" value="1" {{ old('kondisi_mobil.tertutup_rapat') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tertutup_rapat_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_tidak" value="0" {{ old('kondisi_mobil.tertutup_rapat') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tertutup_rapat_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 11. Bebas dari Kontaminan -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>11. Bebas dari Kontaminan</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_ya" value="1" {{ old('kondisi_mobil.bebas_kontaminan') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_kontaminan_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_tidak" value="0" {{ old('kondisi_mobil.bebas_kontaminan') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_kontaminan_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const section = document.getElementById('kondisi-mobil-section');
                                        const checkAll = document.getElementById('kondisi_mobil_check_all');
                                        if (!section || !checkAll) return;

                                        checkAll.addEventListener('change', function() {
                                            const radios = section.querySelectorAll('input[type="radio"][name^="kondisi_mobil["]');
                                            if (this.checked) {
                                                radios.forEach((radio) => {
                                                    if (radio.value === '1') radio.checked = true;
                                                });
                                            } else {
                                                const names = new Set();
                                                radios.forEach((radio) => names.add(radio.name));
                                                names.forEach((name) => {
                                                    section.querySelectorAll(`input[type="radio"][name="${name}"]`).forEach((r) => {
                                                        r.checked = false;
                                                    });
                                                });
                                            }
                                        });
                                    });
                                </script>

                                <!-- Dynamic Rows Section -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Detail Chemical (Produk Dinamis)</h5>
                                    <div id="unified-container">
                                        <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                            <h6 class="text-primary mb-3">Produk 1</h6>
                                            
                                            <!-- Informasi Chemical -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Kategori</label>
                                                        <select class="choices form-control kategori-produk-select" name="kategori_code[]">
                                                            <option value="">Pilih Kategori</option>
                                                            @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                <option value="{{ $kategori }}" {{ old('kategori_code.0') == $kategori ? 'selected' : '' }}>
                                                                    {{ $kategori }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Produk</label>
                                                        <select class="form-control produk-select" name="id_produk[]">
                                                            <option value="">Pilih Produk</option>
                                                        </select>
                                                        <input type="hidden" name="id_chemical[]" class="id-chemical-hidden" value="{{ old('id_chemical.0') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Kondisi Chemical</label>
                                                        <select class="form-control" name="kondisi_chemical[]">
                                                            <option value="">Pilih Kondisi</option>
                                                            <option value="Cair">Cair</option>
                                                            <option value="Serbuk">Serbuk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Detail Pemeriksaan -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body p-3">
                                                            <div class="fw-semibold">Produsen</div>
                                                            <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                            <div class="produsen-badges d-flex flex-wrap gap-1">
                                                                <span class="text-muted small">-</span>
                                                            </div>
                                                            <input type="hidden" name="id_produsen[]" class="id-produsen-hidden" value="{{ old('id_produsen.0') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body p-3">
                                                            <div class="fw-semibold">Distributor</div>
                                                            <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                            <div class="distributor-badges d-flex flex-wrap gap-1">
                                                                <span class="text-muted small">-</span>
                                                            </div>
                                                            <input type="hidden" name="id_distributor[]" class="id-distributor-hidden" value="{{ old('id_distributor.0') }}">
                                                        </div>
                                                    </div>   
                                                </div>   
                                            </div>

                                            <div class="detail-items">
                                                <div class="detail-item border rounded p-3 mb-3" style="background: #fff;" data-detail-index="0" data-detail-global-index="0">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <strong>Detail 1</strong>
                                                        <button type="button" class="btn btn-danger btn-sm remove-detail-btn" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Negara Produsen</label>
                                                                <select class="choices form-control" name="negara_produsen[]">
                                                                    <option value="">Pilih Negara</option>
                                                                    @foreach($countries as $code => $name)
                                                                        <option value="{{ $name }}">{{ $name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Kode Produksi</label>
                                                                <input type="text" class="form-control" name="kode_produksi[]" placeholder="Kode Produksi">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Expire Date</label>
                                                                <input type="date" class="form-control" name="expire_date[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Jumlah Datang (kg/liter/pail)</label>
                                                                <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah Datang (kg/liter/pail)">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Jumlah Sampling</label>
                                                                <input type="text" class="form-control" name="jumlah_sampling[]" placeholder="Jumlah Sampling">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Kondisi Fisik -->
                                                    <div class="form-section mb-3">
                                                        <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label"><strong>Kemasan</strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_1" id="kemasan_ya_1" value="1">
                                                                        <label class="form-check-label" for="kemasan_ya_1">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_1" id="kemasan_tidak_1" value="0">
                                                                        <label class="form-check-label" for="kemasan_tidak_1">Tidak ✗</label>
                                                                    </div>
                                                                    <input type="hidden" name="kondisi_fisik_kemasan[]" class="radio-value-kemasan-1">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label"><strong>Warna</strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_warna_1" id="warna_ya_1" value="1">
                                                                        <label class="form-check-label" for="warna_ya_1">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_warna_1" id="warna_tidak_1" value="0">
                                                                        <label class="form-check-label" for="warna_tidak_1">Tidak ✗</label>
                                                                    </div>
                                                                    <input type="hidden" name="kondisi_fisik_warna[]" class="radio-value-warna-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-section mb-3">
                                                        <h6 class="text-primary mb-2">Upload Gambar</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Foto Chemical (Max 1MB)</label>
                                                                    <input type="file" name="image_chemical[]" class="form-control" accept="image/*" capture="camera">
                                                                    @error('image_chemical.0')
                                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Hasil Pemeriksaan -->
                                                    <div class="form-section mb-3">
                                                        <h6 class="text-primary mb-2">Hasil Pemeriksaan</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="status">Status <span class="text-danger">*</span></label>
                                                                    <select id="status" class="form-control @error('status_baris') is-invalid @enderror" name="status_baris[]" required>
                                                                        <option value="">Pilih Status</option>
                                                                        <option value="Hold">Hold</option>
                                                                        <option value="Release">Release</option>
                                                                    </select>
                                                                    @error('status_baris')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Keterangan</label>
                                                                    <textarea class="form-control" name="keterangan[]" rows="3" placeholder="Keterangan"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Dokumen -->
                                                    <div class="form-section mb-3">
                                                        <h6 class="text-primary mb-2">Dokumen & Sertifikasi</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label"><strong>Halal (berlaku)</strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal_1" id="halal_ya_1" value="1">
                                                                        <label class="form-check-label" for="halal_ya_1">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal_1" id="halal_tidak_1" value="0">
                                                                        <label class="form-check-label" for="halal_tidak_1">Tidak ✗</label>
                                                                    </div>
                                                                    <input type="hidden" name="persyaratan_dokumen_halal[]" class="radio-value-halal-1">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label"><strong>COA</strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="coa_1" id="coa_ya_1" value="1">
                                                                        <label class="form-check-label" for="coa_ya_1">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="coa_1" id="coa_tidak_1" value="0">
                                                                        <label class="form-check-label" for="coa_tidak_1">Tidak ✗</label>
                                                                    </div>
                                                                    <input type="hidden" name="coa[]" class="radio-value-coa-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-primary btn-sm add-detail-btn"><i class="bi bi-plus"></i> Tambah Detail</button>
                                                </div>
                                            </div>
                                            <!-- Buttons -->
                                            <div class="row mt-3 pt-3 border-top">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Produk</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3 pt-3 border-top">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-success btn-sm add-unified-btn"><i class="bi bi-plus"></i> Tambah Produk</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="form-group d-flex justify-content-end">
                                    <a href="{{ route('pemeriksaan-chemical.index') }}" class="me-1 btn btn-secondary btn-md btn-kembali-confirm">
                                        Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-md">
                                        Simpan Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Global variable to store select options
let selectOptionsCache = {
    chemical: [],
    produsen: [],
    negara: [],
    distributor: []
};

const produkByKategori = @json($produkByKategori ?? []);
const produkMeta = @json($produkMeta ?? []);
const chemicalByName = @json($chemicalByName ?? []);
const chemicalByProdukId = @json($chemicalByProdukId ?? []);

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
        el.addEventListener('click', function(e) {
            const ok = confirm('Data belum disimpan. Yakin ingin kembali ke halaman index?');
            if (!ok) e.preventDefault();
        });
    });

    // Initialize Choices.js for all select elements
    try {
        initializeAllChoices();
    } catch(err) {
        console.error('Error initializing Choices.js:', err);
    }
    
    // Cache options SETELAH Choices.js dengan delay untuk memastikan data sudah load
    setTimeout(function() {
        try {
            cacheSelectOptions();
        } catch(err) {
            console.error('Error caching options:', err);
        }
    }, 1000); // Delay 1 detik untuk memastikan Choices.js sudah render sempurna
    
    // Update delete button status
    try {
        updateRemoveButtons();
    } catch(err) {
        console.error('Error updating remove buttons:', err);
    }
    
    // Setup event listeners for dynamic form
    try {
        setupDynamicFormListeners();
    } catch(err) {
        console.error('Error setting up dynamic form listeners:', err);
    }
    
    updateRowNumbers();
    updateDetailButtons();

    function populateProdukOptionsForRow(rowEl) {
        const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
        const produkSelect = rowEl.querySelector('select.produk-select');
        if (!kategoriSelect || !produkSelect) return;

        const kategori = (kategoriSelect.value || '').toString();
        const raw = produkByKategori ? produkByKategori[kategori] : null;
        const items = Array.isArray(raw) ? raw : (raw ? Object.values(raw) : []);

        const desiredProdukId = (produkSelect.dataset && produkSelect.dataset.desiredValue) ? String(produkSelect.dataset.desiredValue) : '';

        // If produkSelect already has a Choices instance, destroy it before rebuilding options
        if (produkSelect.choicesInstance) {
            try {
                produkSelect.choicesInstance.destroy();
            } catch (e) {
            }
            produkSelect.choicesInstance = null;
            delete produkSelect.dataset.choicesInitialized;
        }

        produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
        items.forEach((p) => {
            const opt = document.createElement('option');
            opt.value = String(p.id);
            opt.textContent = String(p.nama);
            if (desiredProdukId && String(p.id) === desiredProdukId) {
                opt.selected = true;
            }
            produkSelect.appendChild(opt);
        });

        // Initialize Choices for produk-select (searchable) like kemasan page
        if (typeof window.Choices !== 'undefined') {
            try {
                const instance = new Choices(produkSelect, {
                    searchResultLimit: 100,
                    searchFuzziness: 0.000001,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
                    searchEnabled: true,
                    searchPlaceholderValue: 'Cari...',
                    itemSelectText: 'Tekan untuk memilih',
                    noResultsText: 'Tidak ada hasil ditemukan',
                    noChoicesText: 'Tidak ada pilihan tersedia',
                    placeholder: true,
                    placeholderValue: 'Pilih...'
                });
                produkSelect.choicesInstance = instance;
                produkSelect.dataset.choicesInitialized = 'true';
            } catch (e) {
            }
        }
    }

    function applyProdukForRow(rowEl) {
        const produkSelect = rowEl.querySelector('select.produk-select');
        const idChemicalHidden = rowEl.querySelector('.id-chemical-hidden');
        const idProdusenHidden = rowEl.querySelector('.id-produsen-hidden');
        const idDistributorHidden = rowEl.querySelector('.id-distributor-hidden');
        const produsenBadges = rowEl.querySelector('.produsen-badges');
        const distributorBadges = rowEl.querySelector('.distributor-badges');

        if (!produkSelect || !idChemicalHidden || !idProdusenHidden || !idDistributorHidden || !produsenBadges || !distributorBadges) return;

        const produkId = (produkSelect.value || '').toString();
        const selectedName = (produkSelect.selectedOptions && produkSelect.selectedOptions[0])
            ? String(produkSelect.selectedOptions[0].textContent || '')
            : '';

        const mappedByProduk = chemicalByProdukId ? chemicalByProdukId[produkId] : null;
        if (mappedByProduk) {
            idChemicalHidden.value = String(mappedByProduk);
        } else {
            const chemicalKey = selectedName ? selectedName.trim().toLowerCase() : '';
            const mappedChemicalId = (chemicalKey && chemicalByName) ? chemicalByName[chemicalKey] : null;
            idChemicalHidden.value = mappedChemicalId ? String(mappedChemicalId) : '';
        }

        const meta = produkMeta ? produkMeta[produkId] : null;
        const produsenIds = meta && meta.produsen_ids ? meta.produsen_ids : [];
        const distributorIds = meta && meta.distributor_ids ? meta.distributor_ids : [];
        const produsenNames = meta && meta.produsen_names ? meta.produsen_names : [];
        const distributorNames = meta && meta.distributor_names ? meta.distributor_names : [];

        idProdusenHidden.value = (Array.isArray(produsenIds) && produsenIds.length > 0) ? String(produsenIds[0]) : '';
        idDistributorHidden.value = (Array.isArray(distributorIds) && distributorIds.length > 0) ? String(distributorIds[0]) : '';

        const renderBadges = (containerEl, values, badgeClass) => {
            if (!Array.isArray(values) || values.length === 0) {
                containerEl.innerHTML = '<span class="text-muted small">-</span>';
                return;
            }
            containerEl.innerHTML = '';
            values.forEach((v) => {
                const span = document.createElement('span');
                span.className = badgeClass;
                span.textContent = String(v);
                containerEl.appendChild(span);
            });
        };

        renderBadges(produsenBadges, produsenNames, 'badge bg-light-primary text-primary');
        renderBadges(distributorBadges, distributorNames, 'badge bg-light-info text-info');
    }

    document.addEventListener('change', function(e) {
        const target = e.target;
        if (target && target.matches('select.kategori-produk-select')) {
            const row = target.closest('.unified-row');
            if (row) {
                populateProdukOptionsForRow(row);
                applyProdukForRow(row);
            }
        }
        if (target && target.matches('select.produk-select')) {
            const row = target.closest('.unified-row');
            if (row) {
                applyProdukForRow(row);
            }
        }
    });

    const oldProdukIds = @json(old('id_produk', []));
    document.querySelectorAll('#unified-container .unified-row').forEach((row, idx) => {
        const kategoriSelect = row.querySelector('select.kategori-produk-select');
        const produkSelect = row.querySelector('select.produk-select');
        if (produkSelect) {
            const desiredProduk = (oldProdukIds && oldProdukIds[idx]) ? String(oldProdukIds[idx]) : '';
            if (desiredProduk) {
                produkSelect.dataset.desiredValue = desiredProduk;
            }
        }
        if (kategoriSelect && kategoriSelect.value) {
            populateProdukOptionsForRow(row);
            applyProdukForRow(row);
        }
    });
});

function setupDynamicFormListeners() {
    // Add new row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-unified-btn')) {
            addNewRow();
        }
    });

    // Remove unified row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-unified-btn')) {
            let row = e.target.closest('.unified-row');
            
            const rowCount = document.querySelectorAll('#unified-container .unified-row').length;
            
            if (rowCount > 1 && row) {
                row.remove();
                updateRowNumbers();
                updateRemoveButtons();
            } else if (!row) {
                console.error('Error: Row element not found');
            } else {
                alert('Minimal harus ada satu baris data!');
            }
        }
    });

    // Add detail within row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-detail-btn')) {
            const rowEl = e.target.closest('.unified-row');
            if (!rowEl) return;
            const container = rowEl.querySelector('.detail-items');
            if (!container) return;
            const items = container.querySelectorAll('.detail-item');
            const last = items.length ? items[items.length - 1] : null;
            if (!last) return;

            const newItem = last.cloneNode(true);
            newItem.querySelectorAll('input, textarea, select').forEach((el) => {
                if (el.type === 'file') {
                    el.value = '';
                } else if (el.type === 'radio') {
                    el.checked = false;
                } else {
                    el.value = '';
                }
            });
            newItem.querySelectorAll('input[type="hidden"][name="kondisi_fisik_kemasan[]"], input[type="hidden"][name="kondisi_fisik_warna[]"], input[type="hidden"][name="persyaratan_dokumen_halal[]"], input[type="hidden"][name="coa[]"]').forEach((el) => {
                el.value = '';
            });

            container.appendChild(newItem);
            updateRowNumbers();
            updateDetailButtons();
            initializeAllChoices();
        }
    });

    // Remove detail within row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-detail-btn')) {
            const rowEl = e.target.closest('.unified-row');
            const detailEl = e.target.closest('.detail-item');
            if (!rowEl || !detailEl) return;
            const items = rowEl.querySelectorAll('.detail-items .detail-item');
            if (items.length > 1) {
                detailEl.remove();
                updateRowNumbers();
                updateDetailButtons();
            }
        }
    });
}

function setupRowRadios(detailEl) {
    const globalIndex = Number(detailEl.dataset.detailGlobalIndex || 0) + 1;
    const mappings = [
        { key: 'kondisi_fisik_kemasan', hiddenPrefix: 'kemasan' },
        { key: 'kondisi_fisik_warna', hiddenPrefix: 'warna' },
        { key: 'persyaratan_dokumen_halal', hiddenPrefix: 'halal' },
        { key: 'coa', hiddenPrefix: 'coa' },
    ];

    mappings.forEach(({ key, hiddenPrefix }) => {
        const radioName = `${key}_${globalIndex}`;
        detailEl.querySelectorAll(`input[type="radio"][name^="${key}_"]`).forEach((radio, idx) => {
            radio.name = radioName;
            const uniqueRadioId = `${key}_${globalIndex}_${idx}`;
            radio.id = uniqueRadioId;
            const formCheck = radio.closest('.form-check');
            const lbl = formCheck ? formCheck.querySelector('label.form-check-label') : null;
            if (lbl) lbl.setAttribute('for', uniqueRadioId);
        });

        const hidden = detailEl.querySelector(`input[type="hidden"].radio-value-${hiddenPrefix}-${globalIndex}`)
            || detailEl.querySelector(`input[type="hidden"][name="${key}[]"]`);

        detailEl.querySelectorAll(`input[type="radio"][name="${radioName}"]`).forEach((radio) => {
            radio.addEventListener('change', function () {
                if (hidden) hidden.value = this.value;
            });
        });

        const checked = detailEl.querySelector(`input[type="radio"][name="${radioName}"]:checked`);
        if (checked && hidden && (hidden.value === '' || hidden.value === null)) {
            hidden.value = checked.value;
        }
    });
}

// Initialize Choices.js for select elements
function initializeAllChoices() {
    const selectElements = document.querySelectorAll('select.choices');
    
    selectElements.forEach(function(select) {
        // Skip if already initialized
        if (select.dataset.choicesInitialized === 'true') {
            return;
        }
        
        // Skip if it's inside a Choices wrapper (already processed)
        if (select.classList.contains('choices__input')) {
            return;
        }
        
        try {
            const choicesInstance = new Choices(select, {
                searchResultLimit: 100,
                    searchFuzziness: 0.000001,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
                    searchEnabled: true,
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Pilih opsi',
                noResultsText: 'Tidak ada hasil',
                noChoicesText: 'Tidak ada pilihan',
                searchPlaceholderValue: 'Cari...',
                itemSelectText: 'Tekan untuk memilih'
            });
            
            // PENTING: Simpan instance ke element untuk akses nanti
            select.choicesInstance = choicesInstance;
            select.dataset.choicesInitialized = 'true';
            
            console.log('Choices.js initialized for:', select.name);
        } catch(err) {
            console.error('Error initializing Choices:', err);
        }
    });
}

// Setup radio listeners for first row
function setupRadioListenersForRow1() {
    // Kondisi Fisik - Kemasan
    document.querySelectorAll('input[name="kondisi_fisik_kemasan_1"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('.radio-value-kemasan-1').value = this.value;
            }
        });
    });
    
    // Kondisi Fisik - Warna
    document.querySelectorAll('input[name="kondisi_fisik_warna_1"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('.radio-value-warna-1').value = this.value;
            }
        });
    });
    
    // Dokumen - Halal
    document.querySelectorAll('input[name="persyaratan_dokumen_halal_1"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('.radio-value-halal-1').value = this.value;
            }
        });
    });
    
    // Dokumen - COA
    document.querySelectorAll('input[name="coa_1"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('.radio-value-coa-1').value = this.value;
            }
        });
    });
}

// Add new row - METODE LENGKAP
function addNewRow() {
    const container = document.getElementById('unified-container');
    const rowCount = container.querySelectorAll('.unified-row').length + 1;
    
    // Create new row element
    const newRow = document.createElement('div');
    newRow.className = 'unified-row mb-4 p-3 border rounded';
    newRow.style.backgroundColor = '#f8f9fa';
    
    // Debug: Check if cache has data
    console.log('Cache data:', selectOptionsCache);
    
    // Set the HTML content - TEMPLATE LENGKAP
    newRow.innerHTML = `
        <h6 class="text-primary mb-3">Produk ${rowCount}</h6>
        
        <!-- Informasi Chemical -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select class="choices form-control kategori-produk-select" name="kategori_code[]">
                        <option value="">Pilih Kategori</option>
                        @foreach(($produkKategoriOptions ?? []) as $kategori)
                            <option value="{{ $kategori }}">{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Produk</label>
                    <select class="form-control produk-select" name="id_produk[]">
                        <option value="">Pilih Produk</option>
                    </select>
                    <input type="hidden" name="id_chemical[]" class="id-chemical-hidden">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Kondisi Chemical</label>
                    <select class="form-control" name="kondisi_chemical[]">
                        <option value="">Pilih Kondisi</option>
                        <option value="Cair">Cair</option>
                        <option value="Serbuk">Serbuk</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Detail Pemeriksaan -->
        <div class="row">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="fw-semibold">Produsen</div>
                        <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                        <div class="produsen-badges d-flex flex-wrap gap-1">
                            <span class="text-muted small">-</span>
                        </div>
                        <input type="hidden" name="id_produsen[]" class="id-produsen-hidden">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="fw-semibold">Distributor</div>
                        <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                        <div class="distributor-badges d-flex flex-wrap gap-1">
                            <span class="text-muted small">-</span>
                        </div>
                        <input type="hidden" name="id_distributor[]" class="id-distributor-hidden">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Negara Produsen</label>
                    <select class="choices form-control" name="negara_produsen[]">
                        <option value="">Pilih Negara</option>
                        @foreach($countries as $code => $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Kode Produksi</label>
                    <input type="text" class="form-control" name="kode_produksi[]" placeholder="Kode Produksi">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Expire Date</label>
                    <input type="date" class="form-control" name="expire_date[]">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Jumlah Datang (kg/liter/pail)</label>
                    <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah Datang (kg/liter/pail)">
                </div>
            </div>
        </div>

        <div class="detail-items">
            <div class="detail-item border rounded p-3 mb-3" style="background: #fff;" data-detail-index="0" data-detail-global-index="0">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Detail 1</strong>
                    <button type="button" class="btn btn-danger btn-sm remove-detail-btn" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Negara Produsen</label>
                            <select class="choices form-control" name="negara_produsen[]">
                                <option value="">Pilih Negara</option>
                                @foreach($countries as $code => $name)
                                    <option value="{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Kode Produksi</label>
                            <input type="text" class="form-control" name="kode_produksi[]" placeholder="Kode Produksi">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Expire Date</label>
                            <input type="date" class="form-control" name="expire_date[]">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Jumlah Datang (kg/liter/pail)</label>
                            <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah Datang (kg/liter/pail)">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Jumlah Sampling</label>
                            <input type="text" class="form-control" name="jumlah_sampling[]" placeholder="Jumlah Sampling">
                        </div>
                    </div>
                </div>

                <!-- Kondisi Fisik -->
                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><strong>Kemasan</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_1" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_1" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                                <input type="hidden" name="kondisi_fisik_kemasan[]" class="radio-value-kemasan-1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><strong>Warna</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi_fisik_warna_1" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi_fisik_warna_1" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                                <input type="hidden" name="kondisi_fisik_warna[]" class="radio-value-warna-1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Upload Gambar</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Foto Chemical (Max 1MB)</label>
                                <input type="file" name="image_chemical[]" class="form-control" accept="image/*" capture="camera">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hasil Pemeriksaan -->
                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Hasil Pemeriksaan</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="status_baris[]" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Hold">Hold</option>
                                    <option value="Release">Release</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan[]" rows="3" placeholder="Keterangan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen -->
                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Dokumen & Sertifikasi</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><strong>Halal (berlaku)</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal_1" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal_1" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                                <input type="hidden" name="persyaratan_dokumen_halal[]" class="radio-value-halal-1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><strong>COA</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coa_1" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coa_1" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                                <input type="hidden" name="coa[]" class="radio-value-coa-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary btn-sm add-detail-btn"><i class="bi bi-plus"></i> Tambah Detail</button>
            </div>
        </div>
        
        <!-- Buttons -->
        <div class="row mt-3 pt-3 border-top">
            <div class="col-md-12">
                <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Produk</button>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);

    initializeAllChoices();
    updateRowNumbers();
    updateDetailButtons();
    
    const kategoriSelect = newRow.querySelector('select.kategori-produk-select');
    if (kategoriSelect && kategoriSelect.value) {
        const produkSelect = newRow.querySelector('select.produk-select');
        if (produkSelect) {
            produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
        }
    }
    
    updateRemoveButtons();
}

// Setup radio button listeners
function setupRadioListeners(row, uniqueId) {
    // Kondisi Fisik - Kemasan
    row.querySelectorAll(`input[name="kondisi_fisik_kemasan_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-kemasan-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Kondisi Fisik - Warna
    row.querySelectorAll(`input[name="kondisi_fisik_warna_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-warna-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Dokumen - Halal
    row.querySelectorAll(`input[name="persyaratan_dokumen_halal_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-halal-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Dokumen - COA
    row.querySelectorAll(`input[name="coa_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-coa-${uniqueId}`).value = this.value;
            }
        });
    });
}

// Cache select options from first row - ambil langsung dari DOM dengan retry
function cacheSelectOptions(retryCount = 0) {
    const container = document.getElementById('unified-container');
    const firstRow = container.querySelector('.unified-row');
    
    if (!firstRow) {
        console.error('First row not found');
        return;
    }
    
    // Cache Chemical options dari DOM select element
    const chemicalSelect = firstRow.querySelector('select[name="id_chemical[]"]');
    if (chemicalSelect && chemicalSelect.options.length > 1) {
        selectOptionsCache.chemical = Array.from(chemicalSelect.options)
            .filter(opt => opt.value !== '')
            .map(opt => ({ value: opt.value, text: opt.text }));
    }
    
    // Cache Produsen options dari DOM select element
    const produsenSelect = firstRow.querySelector('select[name="id_produsen[]"]');
    if (produsenSelect && produsenSelect.options.length > 1) {
        selectOptionsCache.produsen = Array.from(produsenSelect.options)
            .filter(opt => opt.value !== '')
            .map(opt => ({ value: opt.value, text: opt.text }));
    }
    
    // Cache Negara options dari DOM select element
    const negaraSelect = firstRow.querySelector('select[name="negara_produsen[]"]');
    if (negaraSelect && negaraSelect.options.length > 1) {
        selectOptionsCache.negara = Array.from(negaraSelect.options)
            .filter(opt => opt.value !== '')
            .map(opt => ({ value: opt.value, text: opt.text }));
    }
    
    // Cache Distributor options dari DOM select element
    const distributorSelect = firstRow.querySelector('select[name="id_distributor[]"]');
    if (distributorSelect && distributorSelect.options.length > 1) {
        selectOptionsCache.distributor = Array.from(distributorSelect.options)
            .filter(opt => opt.value !== '')
            .map(opt => ({ value: opt.value, text: opt.text }));
    }
    
    // Check if cache is still empty and retry
    const isEmpty = selectOptionsCache.chemical.length === 0 && 
                    selectOptionsCache.produsen.length === 0 && 
                    selectOptionsCache.negara.length === 0 && 
                    selectOptionsCache.distributor.length === 0;
    
    if (isEmpty && retryCount < 5) {
        console.log('Cache empty, retrying... (attempt ' + (retryCount + 1) + ')');
        setTimeout(() => cacheSelectOptions(retryCount + 1), 500);
    } else {
        console.log('Options cached:', selectOptionsCache);
    }
}

// Helper functions to get options from cache with fallback
function getChemicalOptions() {
    // Fallback: jika cache kosong, ambil langsung dari DOM
    if (selectOptionsCache.chemical.length === 0) {
        const firstRow = document.querySelector('#unified-container .unified-row');
        if (firstRow) {
            const select = firstRow.querySelector('select[name="id_chemical[]"]');
            if (select) {
                return Array.from(select.options)
                    .filter(opt => opt.value !== '')
                    .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
                    .join('');
            }
        }
        return '';
    }
    return selectOptionsCache.chemical
        .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
        .join('');
}

function getProdusenOptions() {
    // Fallback: jika cache kosong, ambil langsung dari DOM
    if (selectOptionsCache.produsen.length === 0) {
        const firstRow = document.querySelector('#unified-container .unified-row');
        if (firstRow) {
            const select = firstRow.querySelector('select[name="id_produsen[]"]');
            if (select) {
                return Array.from(select.options)
                    .filter(opt => opt.value !== '')
                    .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
                    .join('');
            }
        }
        return '';
    }
    return selectOptionsCache.produsen
        .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
        .join('');
}

function getCountryOptions() {
    // Fallback: jika cache kosong, ambil langsung dari DOM
    if (selectOptionsCache.negara.length === 0) {
        const firstRow = document.querySelector('#unified-container .unified-row');
        if (firstRow) {
            const select = firstRow.querySelector('select[name="negara_produsen[]"]');
            if (select) {
                return Array.from(select.options)
                    .filter(opt => opt.value !== '')
                    .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
                    .join('');
            }
        }
        return '';
    }
    return selectOptionsCache.negara
        .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
        .join('');
}

function getDistributorOptions() {
    // Fallback: jika cache kosong, ambil langsung dari DOM
    if (selectOptionsCache.distributor.length === 0) {
        const firstRow = document.querySelector('#unified-container .unified-row');
        if (firstRow) {
            const select = firstRow.querySelector('select[name="id_distributor[]"]');
            if (select) {
                return Array.from(select.options)
                    .filter(opt => opt.value !== '')
                    .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
                    .join('');
            }
        }
        return '';
    }
    return selectOptionsCache.distributor
        .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
        .join('');
}

// Update row numbers
function updateRowNumbers() {
    const rows = document.querySelectorAll('#unified-container .unified-row');
    let globalDetail = 0;
    rows.forEach((row, index) => {
        const title = row.querySelector('h6');
        if (title) {
            title.textContent = `Produk ${index + 1}`;
        }

        const detailItems = Array.from(row.querySelectorAll('.detail-items .detail-item'));
        detailItems.forEach((detailEl, dIdx) => {
            detailEl.dataset.detailIndex = String(dIdx);
            detailEl.dataset.detailGlobalIndex = String(globalDetail);
            const t = detailEl.querySelector('strong');
            if (t) t.textContent = `Detail ${dIdx + 1}`;

            detailEl.querySelectorAll('input[type="hidden"][name="kondisi_fisik_kemasan[]"]').forEach((el) => {
                el.className = `radio-value-kemasan-${globalDetail + 1}`;
            });
            detailEl.querySelectorAll('input[type="hidden"][name="kondisi_fisik_warna[]"]').forEach((el) => {
                el.className = `radio-value-warna-${globalDetail + 1}`;
            });
            detailEl.querySelectorAll('input[type="hidden"][name="persyaratan_dokumen_halal[]"]').forEach((el) => {
                el.className = `radio-value-halal-${globalDetail + 1}`;
            });
            detailEl.querySelectorAll('input[type="hidden"][name="coa[]"]').forEach((el) => {
                el.className = `radio-value-coa-${globalDetail + 1}`;
            });

            setupRowRadios(detailEl);
            globalDetail += 1;
        });
    });
}

function updateDetailButtons() {
    document.querySelectorAll('#unified-container .unified-row').forEach((row) => {
        const detailItems = Array.from(row.querySelectorAll('.detail-items .detail-item'));
        detailItems.forEach((detailEl) => {
            const btn = detailEl.querySelector('.remove-detail-btn');
            if (btn) btn.style.display = detailItems.length > 1 ? '' : 'none';
        });
    });
}

// Sync header fields into extra details so arrays remain aligned (like kemasan/BBP)
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (!form || form.tagName.toLowerCase() !== 'form') return;
    if (!form.closest('#main')) return;

    // Remove previously injected hidden inputs (if any)
    form.querySelectorAll('input.__synced_header_value').forEach((el) => el.remove());

    const rows = Array.from(form.querySelectorAll('#unified-container .unified-row'));
    rows.forEach((rowEl) => {
        const details = Array.from(rowEl.querySelectorAll('.detail-items .detail-item'));
        if (details.length <= 1) return;

        const getVal = (sel) => {
            const el = rowEl.querySelector(sel);
            return el ? (el.value || '') : '';
        };

        const headerValues = {
            kategori_code: getVal('select[name="kategori_code[]"]'),
            id_produk: getVal('select[name="id_produk[]"]'),
            kondisi_chemical: getVal('select[name="kondisi_chemical[]"]'),
            id_chemical: getVal('input[name="id_chemical[]"]'),
            id_produsen: getVal('input[name="id_produsen[]"]'),
            id_distributor: getVal('input[name="id_distributor[]"]'),
        };

        // For each extra detail after the first, inject hidden values for header-based arrays
        for (let i = 1; i < details.length; i += 1) {
            Object.entries(headerValues).forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `${key}[]`;
                input.value = value;
                input.className = '__synced_header_value';
                form.appendChild(input);
            });
        }
    });
}, true);

// Update remove buttons visibility
function updateRemoveButtons() {
    const rows = document.querySelectorAll('#unified-container .unified-row');
    rows.forEach((row) => {
        const removeBtn = row.querySelector('.remove-unified-btn');
        if (removeBtn) {
            if (rows.length > 1) {
                removeBtn.style.display = 'inline-block';
            } else {
                removeBtn.style.display = 'none';
            }
        }
    });
}

// Initialize on page load
updateRemoveButtons();
</script>
@endpush