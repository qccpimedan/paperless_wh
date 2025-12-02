@extends('layouts.app')

@section('title', 'Edit Pemeriksaan Kedatangan Chemical')

@section('container')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Edit Pemeriksaan Kedatangan Chemical</h3>
                        <p class="text-subtitle text-muted">Form untuk mengedit data pemeriksaan kedatangan chemical</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-chemical.index') }}">Pemeriksaan Kedatangan Chemical</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                            <h4 class="card-title">Form Edit Pemeriksaan Kedatangan Chemical</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pemeriksaan-chemical.update', $pemeriksaanChemical->uuid) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <!-- SECTION 1: Informasi Dasar -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Informasi Dasar</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                    name="tanggal" value="{{ old('tanggal', $pemeriksaanChemical->tanggal ? $pemeriksaanChemical->tanggal->format('Y-m-d') : '') }}" required>
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
                                                        <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanChemical->id_shift) == $shift->id ? 'selected' : '' }}>
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
                                                    name="jenis_mobil" value="{{ old('jenis_mobil', $pemeriksaanChemical->jenis_mobil) }}" placeholder="Jenis Mobil">
                                                @error('jenis_mobil')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_mobil">No. Mobil</label>
                                                <input type="text" id="no_mobil" class="form-control @error('no_mobil') is-invalid @enderror"
                                                    name="no_mobil" value="{{ old('no_mobil', $pemeriksaanChemical->no_mobil) }}" placeholder="No. Mobil">
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
                                                    name="nama_supir" value="{{ old('nama_supir', $pemeriksaanChemical->nama_supir) }}" placeholder="Nama Supir">
                                                @error('nama_supir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Segel/Gembok</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="segel_option" name="segel_gembok" value="segel" {{ old('segel_gembok', $pemeriksaanChemical->segel_gembok) == 'segel' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="segel_option">
                                                        Segel
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="gembok_option" name="segel_gembok" value="gembok" {{ old('segel_gembok', $pemeriksaanChemical->segel_gembok) == 'gembok' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gembok_option">
                                                        Gembok
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6" id="no_segel_container" style="display: {{ old('segel_gembok', $pemeriksaanChemical->segel_gembok) == 'segel' ? 'block' : 'none' }};">
                                            <div class="form-group">
                                                <label for="no_segel">No. Segel</label>
                                                <input type="text" id="no_segel" class="form-control @error('no_segel') is-invalid @enderror"
                                                    name="no_segel" value="{{ old('no_segel', $pemeriksaanChemical->no_segel) }}" placeholder="No. Segel">
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
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_ya" value="1" {{ old('kondisi_mobil.bersih', $pemeriksaanChemical->kondisi_mobil['bersih'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bersih_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_tidak" value="0" {{ old('kondisi_mobil.bersih', $pemeriksaanChemical->kondisi_mobil['bersih'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bersih_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 2. Bebas dari hama -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>2. Bebas dari hama</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_ya" value="1" {{ old('kondisi_mobil.bebas_hama', $pemeriksaanChemical->kondisi_mobil['bebas_hama'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_hama_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_tidak" value="0" {{ old('kondisi_mobil.bebas_hama', $pemeriksaanChemical->kondisi_mobil['bebas_hama'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_hama_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 3. Tidak Kondensasi -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>3. Tidak Kondensasi</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_ya" value="1" {{ old('kondisi_mobil.tidak_kondensasi', $pemeriksaanChemical->kondisi_mobil['tidak_kondensasi'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_kondensasi_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_tidak" value="0" {{ old('kondisi_mobil.tidak_kondensasi', $pemeriksaanChemical->kondisi_mobil['tidak_kondensasi'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_kondensasi_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 4. Bebas dari Produk Non Halal -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>4. Bebas dari Produk Non Halal</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_ya" value="1" {{ old('kondisi_mobil.bebas_produk_halal', $pemeriksaanChemical->kondisi_mobil['bebas_produk_halal'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_produk_halal_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_tidak" value="0" {{ old('kondisi_mobil.bebas_produk_halal', $pemeriksaanChemical->kondisi_mobil['bebas_produk_halal'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_produk_halal_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <!-- 5. Tidak Berbau Menyimpang -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>5. Tidak Berbau Menyimpang</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_ya" value="1" {{ old('kondisi_mobil.tidak_berbau', $pemeriksaanChemical->kondisi_mobil['tidak_berbau'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_berbau_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_tidak" value="0" {{ old('kondisi_mobil.tidak_berbau', $pemeriksaanChemical->kondisi_mobil['tidak_berbau'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_berbau_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 6. Tidak ada sampah -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>6. Tidak ada sampah</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_ya" value="1" {{ old('kondisi_mobil.tidak_ada_sampah', $pemeriksaanChemical->kondisi_mobil['tidak_ada_sampah'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_sampah_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_tidak" value="0" {{ old('kondisi_mobil.tidak_ada_sampah', $pemeriksaanChemical->kondisi_mobil['tidak_ada_sampah'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_sampah_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 7. Tidak ada pertumbuhan mikroba -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>7. Tidak ada pertumbuhan mikroba</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_ya" value="1" {{ old('kondisi_mobil.tidak_ada_mikroba', $pemeriksaanChemical->kondisi_mobil['tidak_ada_mikroba'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_mikroba_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_tidak" value="0" {{ old('kondisi_mobil.tidak_ada_mikroba', $pemeriksaanChemical->kondisi_mobil['tidak_ada_mikroba'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_mikroba_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 8. Lampu dan Cover tidak pecah -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>8. Lampu dan Cover tidak pecah</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_ya" value="1" {{ old('kondisi_mobil.lampu_cover_utuh', $pemeriksaanChemical->kondisi_mobil['lampu_cover_utuh'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="lampu_cover_utuh_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_tidak" value="0" {{ old('kondisi_mobil.lampu_cover_utuh', $pemeriksaanChemical->kondisi_mobil['lampu_cover_utuh'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="lampu_cover_utuh_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <!-- 9. Pallet / Alas Utuh -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>9. Pallet / Alas Utuh</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_ya" value="1" {{ old('kondisi_mobil.pallet_utuh', $pemeriksaanChemical->kondisi_mobil['pallet_utuh'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="pallet_utuh_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_tidak" value="0" {{ old('kondisi_mobil.pallet_utuh', $pemeriksaanChemical->kondisi_mobil['pallet_utuh'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="pallet_utuh_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 10. Tertutup rapat/tidak bocor -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>10. Tertutup rapat/tidak bocor</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_ya" value="1" {{ old('kondisi_mobil.tertutup_rapat', $pemeriksaanChemical->kondisi_mobil['tertutup_rapat'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tertutup_rapat_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_tidak" value="0" {{ old('kondisi_mobil.tertutup_rapat', $pemeriksaanChemical->kondisi_mobil['tertutup_rapat'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tertutup_rapat_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <!-- 11. Bebas dari Kontaminan -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>11. Bebas dari Kontaminan</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_ya" value="1" {{ old('kondisi_mobil.bebas_kontaminan', $pemeriksaanChemical->kondisi_mobil['bebas_kontaminan'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_kontaminan_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_tidak" value="0" {{ old('kondisi_mobil.bebas_kontaminan', $pemeriksaanChemical->kondisi_mobil['bebas_kontaminan'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_kontaminan_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- SECTION 3: Informasi Produk -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Informasi Produk</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_chemical">Nama Chemical</label>
                                                <select id="id_chemical" class="choices form-control @error('id_chemical') is-invalid @enderror" name="id_chemical">
                                                    <option value="">Pilih Chemical</option>
                                                    @foreach ($chemicals as $chemical)
                                                        <option value="{{ $chemical->id }}" {{ old('id_chemical', $pemeriksaanChemical->id_chemical) == $chemical->id ? 'selected' : '' }}>
                                                            {{ $chemical->nama_chemical }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_chemical')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kondisi_chemical">Kondisi Chemical</label>
                                                <select id="kondisi_chemical" class="form-control @error('kondisi_chemical') is-invalid @enderror" name="kondisi_chemical">
                                                    <option value="">Pilih Kondisi</option>
                                                    <option value="Cair" {{ old('kondisi_chemical', $pemeriksaanChemical->kondisi_chemical) == 'Cair' ? 'selected' : '' }}>Cair</option>
                                                    <option value="Serbuk" {{ old('kondisi_chemical', $pemeriksaanChemical->kondisi_chemical) == 'Serbuk' ? 'selected' : '' }}>Serbuk</option>
                                                </select>
                                                @error('kondisi_chemical')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 4: Detail Pemeriksaan -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Detail Pemeriksaan</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_produsen">Produsen</label>
                                                <select id="id_produsen" class="choices form-control @error('id_produsen') is-invalid @enderror" name="id_produsen">
                                                    <option value="">Pilih Produsen</option>
                                                    @foreach ($produsens as $produsen)
                                                        <option value="{{ $produsen->id }}" {{ old('id_produsen', $pemeriksaanChemical->id_produsen) == $produsen->id ? 'selected' : '' }}>
                                                            {{ $produsen->nama_produsen }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_produsen')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="negara_produsen">Negara Produsen</label>
                                                <select id="negara_produsen" class="choices form-control @error('negara_produsen') is-invalid @enderror" name="negara_produsen">
                                                    <option value="">Pilih Negara Produsen</option>
                                                    @foreach ($countries as $code => $name)
                                                        <option value="{{ $name }}" {{ old('negara_produsen', $pemeriksaanChemical->negara_produsen) == $name ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('negara_produsen')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_distributor">Distributor</label>
                                                <select id="id_distributor" class="choices form-control @error('id_distributor') is-invalid @enderror" name="id_distributor">
                                                    <option value="">Pilih Distributor</option>
                                                    @foreach ($distributors as $distributor)
                                                        <option value="{{ $distributor->id }}" {{ old('id_distributor', $pemeriksaanChemical->id_distributor) == $distributor->id ? 'selected' : '' }}>
                                                            {{ $distributor->nama_distributor }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_distributor')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kode_produksi">Kode Produksi</label>
                                                <input type="text" id="kode_produksi" class="form-control @error('kode_produksi') is-invalid @enderror"
                                                    name="kode_produksi" value="{{ old('kode_produksi', $pemeriksaanChemical->kode_produksi) }}" placeholder="Kode Produksi">
                                                @error('kode_produksi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="expire_date">Expire Date</label>
                                                <input type="date" id="expire_date" class="form-control @error('expire_date') is-invalid @enderror"
                                                    name="expire_date" value="{{ old('expire_date', $pemeriksaanChemical->expire_date ? $pemeriksaanChemical->expire_date->format('Y-m-d') : '') }}">
                                                @error('expire_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="jumlah_datang">Jumlah Barang Datang (kg/liter)</label>
                                                <input type="text" id="jumlah_datang" class="form-control @error('jumlah_datang') is-invalid @enderror"
                                                    name="jumlah_datang" value="{{ old('jumlah_datang', $pemeriksaanChemical->jumlah_datang) }}" placeholder="Jumlah Barang Datang">
                                                @error('jumlah_datang')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="jumlah_sampling">Jumlah Barang yang di sampling</label>
                                                <input type="text" id="jumlah_sampling" class="form-control @error('jumlah_sampling') is-invalid @enderror"
                                                    name="jumlah_sampling" value="{{ old('jumlah_sampling', $pemeriksaanChemical->jumlah_sampling) }}" placeholder="Jumlah Sampling">
                                                @error('jumlah_sampling')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- SECTION 5: Kondisi Fisik (2 items) -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Kondisi Fisik</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- 1. Kemasan -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>1. Kemasan</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[kemasan]" id="kemasan_ya" value="1" {{ old('kondisi_fisik.kemasan', $pemeriksaanChemical->kondisi_fisik['kemasan'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="kemasan_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[kemasan]" id="kemasan_tidak" value="0" {{ old('kondisi_fisik.kemasan', $pemeriksaanChemical->kondisi_fisik['kemasan'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="kemasan_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- 2. Warna -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>2. Warna</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[warna]" id="warna_ya" value="1" {{ old('kondisi_fisik.warna', $pemeriksaanChemical->kondisi_fisik['warna'] ?? false) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="warna_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[warna]" id="warna_tidak" value="0" {{ old('kondisi_fisik.warna', $pemeriksaanChemical->kondisi_fisik['warna'] ?? false) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="warna_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 6: Dokumen & Sertifikasi (2 items) -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Dokumen & Sertifikasi</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Persyaratan Dokumen - Halal (berlaku) -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Persyaratan Dokumen - Halal (berlaku)</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal" id="persyaratan_dokumen_halal_ya" value="1" {{ old('persyaratan_dokumen_halal', $pemeriksaanChemical->persyaratan_dokumen_halal) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="persyaratan_dokumen_halal_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal" id="persyaratan_dokumen_halal_tidak" value="0" {{ old('persyaratan_dokumen_halal', $pemeriksaanChemical->persyaratan_dokumen_halal) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="persyaratan_dokumen_halal_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- COA -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>COA</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="coa" id="coa_ya" value="1" {{ old('coa', $pemeriksaanChemical->coa) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="coa_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="coa" id="coa_tidak" value="0" {{ old('coa', $pemeriksaanChemical->coa) == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="coa_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 7: Hasil Pemeriksaan -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Hasil Pemeriksaan</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Status <span class="text-danger">*</span></label>
                                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                                                    <option value="">Pilih Status</option>
                                                    <option value="Release" {{ old('status', $pemeriksaanChemical->status) == 'Release' ? 'selected' : '' }}>Release</option>
                                                    <option value="Hold" {{ old('status', $pemeriksaanChemical->status) == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                                    name="keterangan" rows="3" placeholder="Keterangan">{{ old('keterangan', $pemeriksaanChemical->keterangan) }}</textarea>
                                                @error('keterangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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
    </header>
</div>
@push('scripts')
<script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
<script>
    // Initialize Choices.js for select elements
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('.choices');
        selects.forEach(select => {
            new Choices(select, {
                searchEnabled: true,
                itemSelectText: 'Pilih',
                noResultsText: 'Tidak ada hasil',
                noChoicesText: 'Tidak ada pilihan',
            });
        });
    });
</script>
@endpush
@endsection
