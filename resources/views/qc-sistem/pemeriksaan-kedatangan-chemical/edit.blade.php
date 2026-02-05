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
                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h4 class="alert-heading">Error Validasi!</h4>
                                    <hr>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('pemeriksaan-chemical.update', $pemeriksaanChemical->uuid) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <!-- Informasi Dasar -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Informasi Dasar</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                    name="tanggal" value="{{ old('tanggal', $pemeriksaanChemical->tanggal->format('Y-m-d')) }}" required>
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
                                                        <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanChemical->id_shift) == $shift->id ? 'selected' : '' }}>
                                                            {{ $shift->shift }}
                                                            @if($shift->user && $shift->user->plant)
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
                                
                                <!-- Kondisi Mobil Pengangkut -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Kondisi Mobil Pengangkut</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <!-- 1. Bersih -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>1. Bersih</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_ya" value="1" {{ old('kondisi_mobil.bersih', $pemeriksaanChemical->kondisi_mobil['bersih'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bersih_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_tidak" value="0" {{ !old('kondisi_mobil.bersih', $pemeriksaanChemical->kondisi_mobil['bersih'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bersih_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 2. Bebas dari hama -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>2. Bebas dari hama</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_ya" value="1" {{ old('kondisi_mobil.bebas_hama', $pemeriksaanChemical->kondisi_mobil['bebas_hama'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_hama_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_tidak" value="0" {{ !old('kondisi_mobil.bebas_hama', $pemeriksaanChemical->kondisi_mobil['bebas_hama'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_hama_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 3. Tidak Kondensasi -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>3. Tidak Kondensasi</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_ya" value="1" {{ old('kondisi_mobil.tidak_kondensasi', $pemeriksaanChemical->kondisi_mobil['tidak_kondensasi'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_kondensasi_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_tidak" value="0" {{ !old('kondisi_mobil.tidak_kondensasi', $pemeriksaanChemical->kondisi_mobil['tidak_kondensasi'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_kondensasi_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 4. Bebas dari Produk Non Halal -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>4. Bebas dari Produk Non Halal</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_ya" value="1" {{ old('kondisi_mobil.bebas_produk_halal', $pemeriksaanChemical->kondisi_mobil['bebas_produk_halal'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_produk_halal_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_tidak" value="0" {{ !old('kondisi_mobil.bebas_produk_halal', $pemeriksaanChemical->kondisi_mobil['bebas_produk_halal'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_produk_halal_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-4">
                                            <!-- 5. Tidak Berbau Menyimpang -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>5. Tidak Berbau Menyimpang</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_ya" value="1" {{ old('kondisi_mobil.tidak_berbau', $pemeriksaanChemical->kondisi_mobil['tidak_berbau'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_berbau_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_tidak" value="0" {{ !old('kondisi_mobil.tidak_berbau', $pemeriksaanChemical->kondisi_mobil['tidak_berbau'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_berbau_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 6. Tidak ada sampah -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>6. Tidak ada sampah</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_ya" value="1" {{ old('kondisi_mobil.tidak_ada_sampah', $pemeriksaanChemical->kondisi_mobil['tidak_ada_sampah'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_sampah_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_tidak" value="0" {{ !old('kondisi_mobil.tidak_ada_sampah', $pemeriksaanChemical->kondisi_mobil['tidak_ada_sampah'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_sampah_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 7. Tidak ada pertumbuhan mikroba -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>7. Tidak ada pertumbuhan mikroba</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_ya" value="1" {{ old('kondisi_mobil.tidak_ada_mikroba', $pemeriksaanChemical->kondisi_mobil['tidak_ada_mikroba'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_mikroba_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_tidak" value="0" {{ !old('kondisi_mobil.tidak_ada_mikroba', $pemeriksaanChemical->kondisi_mobil['tidak_ada_mikroba'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_mikroba_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 8. Lampu dan Cover tidak pecah -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>8. Lampu dan Cover tidak pecah</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_ya" value="1" {{ old('kondisi_mobil.lampu_cover_utuh', $pemeriksaanChemical->kondisi_mobil['lampu_cover_utuh'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="lampu_cover_utuh_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_tidak" value="0" {{ !old('kondisi_mobil.lampu_cover_utuh', $pemeriksaanChemical->kondisi_mobil['lampu_cover_utuh'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="lampu_cover_utuh_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-4">
                                            <!-- 9. Pallet / Alas Utuh -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>9. Pallet / Alas Utuh</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_ya" value="1" {{ old('kondisi_mobil.pallet_utuh', $pemeriksaanChemical->kondisi_mobil['pallet_utuh'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="pallet_utuh_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_tidak" value="0" {{ !old('kondisi_mobil.pallet_utuh', $pemeriksaanChemical->kondisi_mobil['pallet_utuh'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="pallet_utuh_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 10. Tertutup rapat/tidak bocor -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>10. Tertutup rapat/tidak bocor</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_ya" value="1" {{ old('kondisi_mobil.tertutup_rapat', $pemeriksaanChemical->kondisi_mobil['tertutup_rapat'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tertutup_rapat_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_tidak" value="0" {{ !old('kondisi_mobil.tertutup_rapat', $pemeriksaanChemical->kondisi_mobil['tertutup_rapat'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tertutup_rapat_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 11. Bebas dari Kontaminan -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>11. Bebas dari Kontaminan</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_ya" value="1" {{ old('kondisi_mobil.bebas_kontaminan', $pemeriksaanChemical->kondisi_mobil['bebas_kontaminan'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_kontaminan_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_tidak" value="0" {{ !old('kondisi_mobil.bebas_kontaminan', $pemeriksaanChemical->kondisi_mobil['bebas_kontaminan'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_kontaminan_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Static Rows Section (Edit Mode) -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Detail Chemicals</h5>
                                    <p class="text-muted small">Catatan: Untuk menambah atau menghapus baris chemical, silakan buat data baru.</p>
                                    
                                    @php
                                        $detailChemicals = $pemeriksaanChemical->detail_chemicals ?? [];
                                        $rowCount = max(count($detailChemicals), 1);
                                    @endphp
                                    
                                    @for($i = 0; $i < $rowCount; $i++)
                                    @php
                                        $detail = $detailChemicals[$i] ?? [];
                                    @endphp
                                    <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                        <h6 class="text-primary mb-3">Chemical {{ $i + 1 }}</h6>
                                        
                                        <!-- Informasi Chemical -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Kategori</label>
                                                    @php
                                                        $existingChemicalId = $detail['id_chemical'] ?? null;
                                                        $mappedKategori = $existingChemicalId ? ($produkByChemicalId[$existingChemicalId]['kategori_code'] ?? null) : null;
                                                        $mappedProdukId = $existingChemicalId ? ($produkByChemicalId[$existingChemicalId]['id_produk'] ?? null) : null;
                                                    @endphp
                                                    <select class="choices form-control kategori-produk-select" name="kategori_code[]" data-row-index="{{ $i }}" data-desired-produk="{{ old('id_produk.' . $i, $mappedProdukId) }}">
                                                        <option value="">Pilih Kategori</option>
                                                        @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                            <option value="{{ $kategori }}" {{ old('kategori_code.' . $i, $mappedKategori) == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Produk</label>
                                                    <select class="form-control produk-select" name="id_produk[]" data-row-index="{{ $i }}">
                                                        <option value="">Pilih Produk</option>
                                                    </select>
                                                    <input type="hidden" name="id_chemical[]" class="id-chemical-hidden" value="{{ old('id_chemical.' . $i, $detail['id_chemical'] ?? '') }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Kondisi Chemical</label>
                                                    <select class="form-control" name="kondisi_chemical[]">
                                                        <option value="">Pilih Kondisi</option>
                                                        <option value="Cair" {{ ($detail['kondisi_chemical'] ?? '') == 'Cair' ? 'selected' : '' }}>Cair</option>
                                                        <option value="Serbuk" {{ ($detail['kondisi_chemical'] ?? '') == 'Serbuk' ? 'selected' : '' }}>Serbuk</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Detail Pemeriksaan -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card border-0 shadow-sm mb-2">
                                                    <div class="card-body p-3">
                                                        <div class="fw-semibold">Produsen</div>
                                                        <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                        <div class="produsen-badges d-flex flex-wrap gap-1">
                                                            @php
                                                                $prodNames = $mappedProdukId ? ($produkMeta[$mappedProdukId]['produsen_names'] ?? []) : [];
                                                                $prodNames = is_array($prodNames) ? array_values(array_filter($prodNames, fn ($v) => $v !== null && $v !== '')) : [];
                                                            @endphp
                                                            @if(!empty($prodNames))
                                                                @foreach($prodNames as $name)
                                                                    <span class="badge bg-primary">{{ $name }}</span>
                                                                @endforeach
                                                            @else
                                                                <span class="text-muted small">-</span>
                                                            @endif
                                                        </div>
                                                        <input type="hidden" name="id_produsen[]" class="id-produsen-hidden" value="{{ old('id_produsen.' . $i, $detail['id_produsen'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-0 shadow-sm mb-2">
                                                    <div class="card-body p-3">
                                                        <div class="fw-semibold">Distributor</div>
                                                        <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                        <div class="distributor-badges d-flex flex-wrap gap-1">
                                                            @php
                                                                $distNames = $mappedProdukId ? ($produkMeta[$mappedProdukId]['distributor_names'] ?? []) : [];
                                                                $distNames = is_array($distNames) ? array_values(array_filter($distNames, fn ($v) => $v !== null && $v !== '')) : [];
                                                            @endphp
                                                            @if(!empty($distNames))
                                                                @foreach($distNames as $name)
                                                                    <span class="badge bg-primary">{{ $name }}</span>
                                                                @endforeach
                                                            @else
                                                                <span class="text-muted small">-</span>
                                                            @endif
                                                        </div>
                                                        <input type="hidden" name="id_distributor[]" class="id-distributor-hidden" value="{{ old('id_distributor.' . $i, $detail['id_distributor'] ?? '') }}">
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
                                                            <option value="{{ $name }}" {{ ($detail['negara_produsen'] ?? '') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Kode Produksi</label>
                                                    <input type="text" class="form-control" name="kode_produksi[]" value="{{ $detail['kode_produksi'] ?? '' }}" placeholder="Kode Produksi">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Expire Date</label>
                                                    <input type="date" class="form-control" name="expire_date[]" value="{{ $detail['expire_date'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Jumlah Datang (kg/liter/pail)</label>
                                                    <input type="text" class="form-control" name="jumlah_datang[]" value="{{ $detail['jumlah_datang'] ?? '' }}" placeholder="Jumlah Datang (kg/liter/pail)">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Jumlah Sampling</label>
                                                    <input type="text" class="form-control" name="jumlah_sampling[]" value="{{ $detail['jumlah_sampling'] ?? '' }}" placeholder="Jumlah Sampling">
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
                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_{{ $i }}" value="1" {{ (($detail['kondisi_fisik']['kemasan'] ?? false) == true) ? 'checked' : '' }}>
                                                            <label class="form-check-label">Ya ✓</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_{{ $i }}" value="0" {{ (($detail['kondisi_fisik']['kemasan'] ?? false) == false) ? 'checked' : '' }}>
                                                            <label class="form-check-label">Tidak ✗</label>
                                                        </div>
                                                        <input type="hidden" name="kondisi_fisik_kemasan[]" value="{{ ($detail['kondisi_fisik']['kemasan'] ?? false) ? '1' : '0' }}" class="radio-value-kemasan-{{ $i }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label"><strong>Warna</strong></label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_warna_{{ $i }}" value="1" {{ (($detail['kondisi_fisik']['warna'] ?? false) == true) ? 'checked' : '' }}>
                                                            <label class="form-check-label">Ya ✓</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_warna_{{ $i }}" value="0" {{ (($detail['kondisi_fisik']['warna'] ?? false) == false) ? 'checked' : '' }}>
                                                            <label class="form-check-label">Tidak ✗</label>
                                                        </div>
                                                        <input type="hidden" name="kondisi_fisik_warna[]" value="{{ ($detail['kondisi_fisik']['warna'] ?? false) ? '1' : '0' }}" class="radio-value-warna-{{ $i }}">
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
                                                            <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal_{{ $i }}" value="1" {{ (($detail['persyaratan_dokumen_halal'] ?? false) == true) ? 'checked' : '' }}>
                                                            <label class="form-check-label">Ya ✓</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal_{{ $i }}" value="0" {{ (($detail['persyaratan_dokumen_halal'] ?? false) == false) ? 'checked' : '' }}>
                                                            <label class="form-check-label">Tidak ✗</label>
                                                        </div>
                                                        <input type="hidden" name="persyaratan_dokumen_halal[]" value="{{ ($detail['persyaratan_dokumen_halal'] ?? false) ? '1' : '0' }}" class="radio-value-halal-{{ $i }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label"><strong>COA</strong></label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="coa_{{ $i }}" value="1" {{ (($detail['coa'] ?? false) == true) ? 'checked' : '' }}>
                                                            <label class="form-check-label">Ya ✓</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="coa_{{ $i }}" value="0" {{ (($detail['coa'] ?? false) == false) ? 'checked' : '' }}>
                                                            <label class="form-check-label">Tidak ✗</label>
                                                        </div>
                                                        <input type="hidden" name="coa[]" value="{{ ($detail['coa'] ?? false) ? '1' : '0' }}" class="radio-value-coa-{{ $i }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-section mb-3">
                                            <h6 class="text-primary mb-2">Upload Gambar</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @php
                                                        $imgPath = $detail['image_chemical'] ?? null;
                                                    @endphp
                                                    @if($imgPath)
                                                        <div class="mb-2">
                                                            <a href="{{ asset('storage/' . $imgPath) }}" target="_blank" class="btn btn-sm btn-info">Lihat Foto</a>
                                                        </div>
                                                    @endif
                                                    <div class="form-group">
                                                        <label class="form-label">Ganti Foto Chemical (Max 1MB)</label>
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
                                                            <option value="Hold" {{ ($detail['status'] ?? '') == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                            <option value="Release" {{ ($detail['status'] ?? '') == 'Release' ? 'selected' : '' }}>Release</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Keterangan</label>
                                                        <textarea class="form-control" name="keterangan[]" rows="3" placeholder="Keterangan">{{ $detail['keterangan'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                </div>

                                <!-- Submit Buttons -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        Update
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

<script>
// Radio button synchronization for edit mode
document.addEventListener('DOMContentLoaded', function() {
    const produkByKategori = @json($produkByKategori ?? []);
    const produkMeta = @json($produkMeta ?? []);
    const chemicalByName = @json($chemicalByName ?? []);
    const chemicalByProdukId = @json($chemicalByProdukId ?? []);

    function initProdukChoices(selectEl) {
        if (!selectEl) return;
        if (typeof window.Choices === 'undefined') {
            return;
        }
        if (selectEl.choicesInstance) {
            try { selectEl.choicesInstance.destroy(); } catch (e) {}
            selectEl.choicesInstance = null;
        }
        try {
            const instance = new Choices(selectEl, {
                searchEnabled: true,
                searchPlaceholderValue: 'Cari...',
                itemSelectText: 'Tekan untuk memilih',
                noResultsText: 'Tidak ada hasil ditemukan',
                noChoicesText: 'Tidak ada pilihan tersedia',
                placeholder: true,
                placeholderValue: 'Pilih...'
            });
            selectEl.choicesInstance = instance;
        } catch (e) {}
    }

    function populateProdukOptionsForRow(rowEl) {
        const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
        const produkSelect = rowEl.querySelector('select.produk-select');
        if (!kategoriSelect || !produkSelect) return;

        const kategori = (kategoriSelect.value || '').toString();
        const raw = produkByKategori ? produkByKategori[kategori] : null;
        const items = Array.isArray(raw) ? raw : (raw ? Object.values(raw) : []);

        const desiredProdukId = (kategoriSelect.dataset && kategoriSelect.dataset.desiredProduk)
            ? String(kategoriSelect.dataset.desiredProduk)
            : (produkSelect.value ? String(produkSelect.value) : '');

        if (produkSelect.choicesInstance) {
            try { produkSelect.choicesInstance.destroy(); } catch (e) {}
            produkSelect.choicesInstance = null;
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

        initProdukChoices(produkSelect);
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
            if (mappedChemicalId) {
                idChemicalHidden.value = String(mappedChemicalId);
            }
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
                // User changed category: drop the initial desired product so it won't keep reverting
                if (target.dataset) {
                    target.dataset.desiredProduk = '';
                }
                const produkSelect = row.querySelector('select.produk-select');
                if (produkSelect) {
                    if (produkSelect.choicesInstance) {
                        try { produkSelect.choicesInstance.destroy(); } catch (e) {}
                        produkSelect.choicesInstance = null;
                    }
                    produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
                }
                populateProdukOptionsForRow(row);
                applyProdukForRow(row);
            }
        }
        if (target && target.matches('select.produk-select')) {
            const row = target.closest('.unified-row');
            if (row) {
                const kategoriSelect = row.querySelector('select.kategori-produk-select');
                if (kategoriSelect && kategoriSelect.dataset) {
                    kategoriSelect.dataset.desiredProduk = (target.value || '').toString();
                }
                applyProdukForRow(row);
            }
        }
    });

    document.querySelectorAll('.unified-row').forEach((row) => {
        const kategoriSelect = row.querySelector('select.kategori-produk-select');
        if (kategoriSelect && kategoriSelect.value) {
            populateProdukOptionsForRow(row);
            applyProdukForRow(row);
        }
    });

    @for($i = 0; $i < $rowCount; $i++)
    // Kondisi Fisik - Kemasan
    document.querySelectorAll('input[name="kondisi_fisik_kemasan_{{ $i }}"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('.radio-value-kemasan-{{ $i }}').value = this.value;
            }
        });
    });
    
    // Kondisi Fisik - Warna
    document.querySelectorAll('input[name="kondisi_fisik_warna_{{ $i }}"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('.radio-value-warna-{{ $i }}').value = this.value;
            }
        });
    });
    
    // Dokumen - Halal
    document.querySelectorAll('input[name="persyaratan_dokumen_halal_{{ $i }}"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('.radio-value-halal-{{ $i }}').value = this.value;
            }
        });
    });
    
    // Dokumen - COA
    document.querySelectorAll('input[name="coa_{{ $i }}"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('.radio-value-coa-{{ $i }}').value = this.value;
            }
        });
    });
    @endfor
});
</script>
@endsection
