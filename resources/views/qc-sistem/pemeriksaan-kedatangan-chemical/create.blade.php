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
                                                <select id="id_shift" class="choices form-control @error('id_shift') is-invalid @enderror" name="id_shift">
                                                    <option value="">Pilih Shift</option>
                                                    @foreach ($shifts as $shift)
                                                        <option value="{{ $shift->id }}" {{ old('id_shift') == $shift->id ? 'selected' : '' }}>
                                                            {{ $shift->shift }}
                                                            @if ($shift->user && $shift->user->plant)
                                                                - {{ $shift->user->plant->plant }}
                                                            @endif
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
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Kondisi Mobil Pengangkut</h5>
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

                                <!-- Dynamic Rows Section -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Detail Chemical (Baris Dinamis)</h5>
                                    <div id="unified-container">
                                        <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                            <h6 class="text-primary mb-3">Baris 1</h6>
                                            
                                            <!-- Informasi Chemical -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Nama Chemical</label>
                                                        <select class="choices form-control" name="id_chemical[]">
                                                            <option value="">Pilih Chemical</option>
                                                            @foreach($chemicals as $chemical)
                                                                <option value="{{ $chemical->id }}">{{ $chemical->nama_chemical }}</option>
                                                            @endforeach
                                                        </select>
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
                                                    <div class="form-group">
                                                        <label class="form-label">Produsen</label>
                                                        <select class="choices form-control" name="id_produsen[]">
                                                            <option value="">Pilih Produsen</option>
                                                            @foreach($produsens as $produsen)
                                                                <option value="{{ $produsen->id }}">{{ $produsen->nama_produsen }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
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
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Distributor</label>
                                                        <select class="choices form-control" name="id_distributor[]">
                                                            <option value="">Pilih Distributor</option>
                                                            @foreach($distributors as $distributor)
                                                                <option value="{{ $distributor->id }}">{{ $distributor->nama_distributor }}</option>
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
                                            
                                            <!-- Buttons -->
                                            <div class="row mt-3 pt-3 border-top">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-success btn-sm add-unified-btn"><i class="bi bi-plus"></i> Tambah Baris</button>
                                                    <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Baris</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        Simpan
                                    </button>
                                    <a href="{{ route('pemeriksaan-chemical.index') }}" class="btn btn-secondary">
                                        Kembali
                                    </a>
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

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Choices.js for all select elements
    try {
        initializeAllChoices();
    } catch(err) {
        console.error('Error initializing Choices.js:', err);
    }
    
    // Cache options SETELAH Choices.js dengan delay untuk memastikan data sudah load
    setTimeout(function() {
        try {
            cacheSelectOptionsFromChoices();
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
    
    // Setup radio listeners for first row
    setupRadioListenersForRow1();
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
    
    // Generate unique ID for radio buttons in this row
    const uniqueId = Date.now();
    
    // Debug: Check if cache has data
    console.log('Cache data:', selectOptionsCache);
    
    // Set the HTML content - TEMPLATE LENGKAP
    newRow.innerHTML = `
        <h6 class="text-primary mb-3">Baris ${rowCount}</h6>
        
        <!-- Informasi Chemical -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Nama Chemical</label>
                    <select class="choices form-control" name="id_chemical[]">
                        <option value="">Pilih Chemical</option>
                        @foreach($chemicals as $chemical)
                            <option value="{{ $chemical->id }}">{{ $chemical->nama_chemical }}</option>
                        @endforeach
                    </select>
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
                <div class="form-group">
                    <label class="form-label">Produsen</label>
                    <select class="choices form-control" name="id_produsen[]">
                        <option value="">Pilih Produsen</option>
                        @foreach($produsens as $produsen)
                            <option value="{{ $produsen->id }}">{{ $produsen->nama_produsen }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
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
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Distributor</label>
                    <select class="choices form-control" name="id_distributor[]">
                        <option value="">Pilih Distributor</option>
                        @foreach($distributors as $distributor)
                            <option value="{{ $distributor->id }}">{{ $distributor->nama_distributor }}</option>
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
                            <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="kondisi_fisik_kemasan[]" class="radio-value-kemasan-${uniqueId}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Warna</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_warna_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_warna_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="kondisi_fisik_warna[]" class="radio-value-warna-${uniqueId}">
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
                            <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="persyaratan_dokumen_halal[]" class="radio-value-halal-${uniqueId}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>COA</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="coa_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="coa_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="coa[]" class="radio-value-coa-${uniqueId}">
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
        
        <!-- Buttons -->
        <div class="row mt-3 pt-3 border-top">
            <div class="col-md-12">
                <button type="button" class="btn btn-success btn-sm add-unified-btn"><i class="bi bi-plus"></i> Tambah Baris</button>
                <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Baris</button>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);
    
    // Setup radio button listeners for the new row
    setupRadioListeners(newRow, uniqueId);
    
    // Initialize Choices.js ONLY for selects in the new row
    const newSelects = newRow.querySelectorAll('select.choices');
    
    newSelects.forEach(select => {
        try {
            new Choices(select, {
                searchEnabled: true,
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Pilih opsi',
                noResultsText: 'Tidak ada hasil',
                noChoicesText: 'Tidak ada pilihan',
                searchPlaceholderValue: 'Cari...',
                itemSelectText: 'Tekan untuk memilih'
            });
            select.dataset.choicesInitialized = 'true';
        } catch(err) {
            console.error('Error initializing new select:', err);
        }
    });
    
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
    rows.forEach((row, index) => {
        const title = row.querySelector('h6');
        if (title) {
            title.textContent = `Baris ${index + 1}`;
        }
    });
}

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