@extends('layouts.app')

@section('title', 'Edit Pemeriksaan Kedatangan Bahan Baku Penunjang')

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
                        <h3>Edit Pemeriksaan Kedatangan Bahan Baku Penunjang</h3>
                        <p class="text-subtitle text-muted">Form untuk mengedit data pemeriksaan kedatangan bahan baku penunjang</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-bahan-baku.index') }}">Pemeriksaan Kedatangan Bahan Baku Penunjang</a></li>
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
                            <h4 class="card-title">Form Pemeriksaan Kedatangan Bahan Baku Penunjang</h4>
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

                            <form action="{{ route('pemeriksaan-bahan-baku.update', $pemeriksaanBahanBaku->uuid) }}" method="POST" enctype="multipart/form-data">
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
                                                    name="tanggal" value="{{ old('tanggal', $pemeriksaanBahanBaku->tanggal->format('Y-m-d')) }}" required>
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
                                                        <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanBahanBaku->id_shift) == $shift->id ? 'selected' : '' }}>
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
                                                    name="jenis_mobil" value="{{ old('jenis_mobil', $pemeriksaanBahanBaku->jenis_mobil) }}" placeholder="Jenis Mobil">
                                                @error('jenis_mobil')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_mobil">No. Mobil</label>
                                                <input type="text" id="no_mobil" class="form-control @error('no_mobil') is-invalid @enderror"
                                                    name="no_mobil" value="{{ old('no_mobil', $pemeriksaanBahanBaku->no_mobil) }}" placeholder="No. Mobil">
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
                                                    name="nama_supir" value="{{ old('nama_supir', $pemeriksaanBahanBaku->nama_supir) }}" placeholder="Nama Supir">
                                                @error('nama_supir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="jenis_pemeriksaan">Jenis Pemeriksaan</label>
                                                <input type="text" id="jenis_pemeriksaan" class="form-control @error('jenis_pemeriksaan') is-invalid @enderror"
                                                    name="jenis_pemeriksaan" value="{{ old('jenis_pemeriksaan') }}" placeholder="Jenis Pemeriksaan">
                                                @error('jenis_pemeriksaan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div> -->
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_po">No. PO</label>
                                                <input type="text" id="no_po" class="form-control @error('no_po') is-invalid @enderror"
                                                    name="no_po" value="{{ old('no_po', $pemeriksaanBahanBaku->no_po) }}" placeholder="No. PO">
                                                @error('no_po')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Segel/Gembok</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="segel_option" name="segel_gembok" value="segel" {{ old('segel_gembok', $pemeriksaanBahanBaku->segel_gembok) == 'segel' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="segel_option">
                                                        Segel
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="gembok_option" name="segel_gembok" value="gembok" {{ old('segel_gembok', $pemeriksaanBahanBaku->segel_gembok) == 'gembok' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gembok_option">
                                                        Gembok
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="no_segel_container" style="display: {{ old('segel_gembok', $pemeriksaanBahanBaku->segel_gembok) == 'segel' ? 'block' : 'none' }};">
                                            <div class="form-group">
                                                <label for="no_segel">No. Segel</label>
                                                <input type="text" id="no_segel" class="form-control @error('no_segel') is-invalid @enderror"
                                                    name="no_segel" value="{{ old('no_segel', $pemeriksaanBahanBaku->no_segel) }}" placeholder="No. Segel">
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
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_ya" value="1" {{ old('kondisi_mobil.bersih', $pemeriksaanBahanBaku->kondisi_mobil['bersih'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bersih_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_tidak" value="0" {{ !old('kondisi_mobil.bersih', $pemeriksaanBahanBaku->kondisi_mobil['bersih'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bersih_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 2. Bebas dari hama -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>2. Bebas dari hama</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_ya" value="1" {{ old('kondisi_mobil.bebas_hama', $pemeriksaanBahanBaku->kondisi_mobil['bebas_hama'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_hama_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_tidak" value="0" {{ !old('kondisi_mobil.bebas_hama', $pemeriksaanBahanBaku->kondisi_mobil['bebas_hama'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_hama_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 3. Tidak Kondensasi -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>3. Tidak Kondensasi</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_ya" value="1" {{ old('kondisi_mobil.tidak_kondensasi', $pemeriksaanBahanBaku->kondisi_mobil['tidak_kondensasi'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_kondensasi_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_tidak" value="0" {{ !old('kondisi_mobil.tidak_kondensasi', $pemeriksaanBahanBaku->kondisi_mobil['tidak_kondensasi'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_kondensasi_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 4. Bebas dari Produk Non Halal -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>4. Bebas dari Produk Non Halal</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_ya" value="1" {{ old('kondisi_mobil.bebas_produk_halal', $pemeriksaanBahanBaku->kondisi_mobil['bebas_produk_halal'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_produk_halal_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_tidak" value="0" {{ !old('kondisi_mobil.bebas_produk_halal', $pemeriksaanBahanBaku->kondisi_mobil['bebas_produk_halal'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_produk_halal_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-4">
                                            <!-- 5. Tidak Berbau Menyimpang -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>5. Tidak Berbau Menyimpang</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_ya" value="1" {{ old('kondisi_mobil.tidak_berbau', $pemeriksaanBahanBaku->kondisi_mobil['tidak_berbau'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_berbau_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_tidak" value="0" {{ !old('kondisi_mobil.tidak_berbau', $pemeriksaanBahanBaku->kondisi_mobil['tidak_berbau'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_berbau_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 6. Tidak ada sampah -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>6. Tidak ada sampah</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_ya" value="1" {{ old('kondisi_mobil.tidak_ada_sampah', $pemeriksaanBahanBaku->kondisi_mobil['tidak_ada_sampah'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_sampah_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_tidak" value="0" {{ !old('kondisi_mobil.tidak_ada_sampah', $pemeriksaanBahanBaku->kondisi_mobil['tidak_ada_sampah'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_sampah_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 7. Tidak ada pertumbuhan mikroba -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>7. Tidak ada pertumbuhan mikroba</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_ya" value="1" {{ old('kondisi_mobil.tidak_ada_mikroba', $pemeriksaanBahanBaku->kondisi_mobil['tidak_ada_mikroba'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_mikroba_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_tidak" value="0" {{ !old('kondisi_mobil.tidak_ada_mikroba', $pemeriksaanBahanBaku->kondisi_mobil['tidak_ada_mikroba'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_mikroba_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 8. Lampu dan Cover tidak pecah -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>8. Lampu dan Cover tidak pecah</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_ya" value="1" {{ old('kondisi_mobil.lampu_cover_utuh', $pemeriksaanBahanBaku->kondisi_mobil['lampu_cover_utuh'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="lampu_cover_utuh_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_tidak" value="0" {{ !old('kondisi_mobil.lampu_cover_utuh', $pemeriksaanBahanBaku->kondisi_mobil['lampu_cover_utuh'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="lampu_cover_utuh_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-4">
                                            <!-- 9. Pallet / Alas Utuh -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>9. Pallet / Alas Utuh</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_ya" value="1" {{ old('kondisi_mobil.pallet_utuh', $pemeriksaanBahanBaku->kondisi_mobil['pallet_utuh'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="pallet_utuh_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_tidak" value="0" {{ !old('kondisi_mobil.pallet_utuh', $pemeriksaanBahanBaku->kondisi_mobil['pallet_utuh'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="pallet_utuh_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 10. Tertutup rapat/tidak bocor -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>10. Tertutup rapat/tidak bocor</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_ya" value="1" {{ old('kondisi_mobil.tertutup_rapat', $pemeriksaanBahanBaku->kondisi_mobil['tertutup_rapat'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tertutup_rapat_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_tidak" value="0" {{ !old('kondisi_mobil.tertutup_rapat', $pemeriksaanBahanBaku->kondisi_mobil['tertutup_rapat'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tertutup_rapat_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
        
                                            <!-- 11. Bebas dari Kontaminan -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>11. Bebas dari Kontaminan</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_ya" value="1" {{ old('kondisi_mobil.bebas_kontaminan', $pemeriksaanBahanBaku->kondisi_mobil['bebas_kontaminan'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_kontaminan_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_tidak" value="0" {{ !old('kondisi_mobil.bebas_kontaminan', $pemeriksaanBahanBaku->kondisi_mobil['bebas_kontaminan'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_kontaminan_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Static Rows Section (Edit Mode) -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Detail Produk</h5>
                                    
                                    @php
                                        // Decode JSON arrays from database
                                        $idBahanArray = json_decode($pemeriksaanBahanBaku->id_bahan_array, true) ?? [];
                                        $produsenArray = json_decode($pemeriksaanBahanBaku->produsen_array, true) ?? [];
                                        $negaraProdusenArray = json_decode($pemeriksaanBahanBaku->negara_produsen_array, true) ?? [];
                                        $distributorArray = json_decode($pemeriksaanBahanBaku->distributor_array, true) ?? [];
                                        $kodeProduksiArray = json_decode($pemeriksaanBahanBaku->kode_produksi_array, true) ?? [];
                                        $expireDateArray = json_decode($pemeriksaanBahanBaku->expire_date_array, true) ?? [];
                                        $jumlahDatangArray = json_decode($pemeriksaanBahanBaku->jumlah_datang_array, true) ?? [];
                                        $jumlahSamplingArray = json_decode($pemeriksaanBahanBaku->jumlah_sampling_array, true) ?? [];
                                        $spesifikasiArray = json_decode($pemeriksaanBahanBaku->spesifikasi_array, true) ?? [];
                                        $kondisiProdukArray = json_decode($pemeriksaanBahanBaku->kondisi_produk, true) ?? [];
                                        $suhuProdukArray = json_decode($pemeriksaanBahanBaku->suhu_produk, true) ?? [];
                                        $suhuProdukTypeArray = json_decode($pemeriksaanBahanBaku->suhu_produk_type, true) ?? [];
                                        $suhuMobilArray = json_decode($pemeriksaanBahanBaku->suhu_mobil_array, true) ?? [];
                                        $suhuMobilTypeArray = json_decode($pemeriksaanBahanBaku->suhu_mobil_type_array, true) ?? [];
                                        $kondisiProdukSuhuArray = json_decode($pemeriksaanBahanBaku->kondisi_produk_suhu, true) ?? [];
                                        $kondisiFisikArray = json_decode($pemeriksaanBahanBaku->kondisi_fisik_array, true) ?? [];
                                        $logoHalalArray = json_decode($pemeriksaanBahanBaku->logo_halal_array, true) ?? [];
                                        $dokumenHalalArray = json_decode($pemeriksaanBahanBaku->dokumen_halal_array, true) ?? [];
                                        $coaArray = json_decode($pemeriksaanBahanBaku->coa_array, true) ?? [];
                                        $fileCoaArray = json_decode($pemeriksaanBahanBaku->file_coa_array ?? '[]', true) ?? [];
                                        $hasilUjiFfaArray = json_decode($pemeriksaanBahanBaku->hasil_uji_ffa_array, true) ?? [];
                                        $statusBarisArray = json_decode($pemeriksaanBahanBaku->status_baris_array, true) ?? [];
                                        $keteranganArray = json_decode($pemeriksaanBahanBaku->keterangan_array, true) ?? [];
                                        
                                        $rowCount = max(count($idBahanArray), 1);
                                    @endphp
                                    
                                    <div id="unified-container">
                                        @for($i = 0; $i < $rowCount; $i++)
                                        <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;" data-row-index="{{ $i }}">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="text-primary mb-0">Baris {{ $i + 1 }}</h6>
                                                @if($rowCount > 1)
                                                <button type="button" class="btn btn-danger btn-sm remove-row-btn">
                                                    <i class="bi bi-trash"></i> Hapus Baris
                                                </button>
                                                @endif
                                            </div>
                                            
                                            <!-- Informasi Produk -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Kategori</label>
                                                        @php
                                                            $selectedProdukId = $idBahanArray[$i] ?? '';
                                                            $selectedKategori = old('kategori_code.' . $i);
                                                            if ($selectedKategori === null || $selectedKategori === '') {
                                                                $selectedKategori = $selectedProdukId ? ($produkKategoriById[$selectedProdukId] ?? '') : '';
                                                            }
                                                        @endphp
                                                        <select class="choices form-control kategori-produk-select" name="kategori_code[]" data-row-index="{{ $i }}">
                                                            <option value="">Pilih Kategori</option>
                                                            @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                <option value="{{ $kategori }}" {{ $selectedKategori == $kategori ? 'selected' : '' }}>
                                                                    {{ $kategori }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Produk</label>
                                                        <select class="form-control produk-select" name="id_bahan[]" data-row-index="{{ $i }}" data-selected="{{ old('id_bahan.' . $i, $idBahanArray[$i] ?? '') }}">
                                                            <option value="">Pilih Produk</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body p-3">
                                                            <div class="fw-semibold">Distributor</div>
                                                            <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                            <div class="distributor-badges d-flex flex-wrap gap-1">
                                                                @php
                                                                    $existingDistributor = $distributorArray[$i] ?? '';
                                                                    $existingDistributorItems = array_values(array_filter(array_map('trim', explode(',', (string) $existingDistributor)), fn ($v) => $v !== ''));
                                                                @endphp
                                                                @forelse ($existingDistributorItems as $d)
                                                                    <span class="badge bg-light-info text-info">{{ $d }}</span>
                                                                @empty
                                                                    <span class="text-muted small">-</span>
                                                                @endforelse
                                                            </div>
                                                            <div class="distributor-hidden-inputs">
                                                                @foreach ($existingDistributorItems as $d)
                                                                    <input type="hidden" name="distributor[{{ $i }}][]" value="{{ $d }}">
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body p-3">
                                                            <div class="fw-semibold">Produsen</div>
                                                            <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                            <div class="produsen-badges d-flex flex-wrap gap-1">
                                                                @php
                                                                    $existingProdusen = $produsenArray[$i] ?? '';
                                                                    $existingProdusenItems = array_values(array_filter(array_map('trim', explode(',', (string) $existingProdusen)), fn ($v) => $v !== ''));
                                                                @endphp
                                                                @forelse ($existingProdusenItems as $p)
                                                                    <span class="badge bg-light-primary text-primary">{{ $p }}</span>
                                                                @empty
                                                                    <span class="text-muted small">-</span>
                                                                @endforelse
                                                            </div>
                                                            <div class="produsen-hidden-inputs">
                                                                @foreach ($existingProdusenItems as $p)
                                                                    <input type="hidden" name="produsen[{{ $i }}][]" value="{{ $p }}">
                                                                @endforeach
                                                            </div>
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
                                                            @foreach ($countries as $code => $name)
                                                                <option value="{{ $name }}" {{ ($negaraProdusenArray[$i] ?? '') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Kode Produksi</label>
                                                        <input type="text" class="form-control" name="kode_produksi[]" value="{{ $kodeProduksiArray[$i] ?? '' }}" placeholder="Kode Produksi">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Expire Date</label>
                                                        <input type="date" class="form-control" name="expire_date[]" value="{{ $expireDateArray[$i] ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Jumlah Datang (kg)</label>
                                                        <input type="text" class="form-control" name="jumlah_datang[]" value="{{ $jumlahDatangArray[$i] ?? '' }}" placeholder="Jumlah Datang">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Jumlah Sampling</label>
                                                        <input type="text" class="form-control" name="jumlah_sampling[]" value="{{ $jumlahSamplingArray[$i] ?? '' }}" placeholder="Jumlah Sampling">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- SUHU PRODUK -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Suhu Produk</label>
                                                        <select class="form-control suhu-produk-type" name="suhu_produk_type[]" data-row-index="{{ $i }}">
                                                            <option value="">Pilih Jenis Suhu Produk</option>
                                                            <option value="Fresh" {{ ($suhuProdukTypeArray[$i] ?? '') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                            <option value="Frozen" {{ ($suhuProdukTypeArray[$i] ?? '') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                            <option value="Tidak Ada" {{ ($suhuProdukTypeArray[$i] ?? '') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group suhu-produk-input" style="display: {{ ($suhuProdukTypeArray[$i] ?? '') == 'Fresh' || ($suhuProdukTypeArray[$i] ?? '') == 'Frozen' ? 'block' : 'none' }};">
                                                        <label class="form-label">Nilai Suhu Produk (°C)</label>
                                                        <input type="text" class="form-control" name="suhu_produk[]" value="{{ $suhuProdukArray[$i] ?? '' }}" placeholder="Contoh: -18°C atau 4°C">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- SUHU MOBIL -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Suhu Mobil</label>
                                                        <select class="form-control suhu-mobil-type" name="suhu_mobil_type[]" data-row-index="{{ $i }}">
                                                            <option value="">Pilih Jenis Suhu Mobil</option>
                                                            <option value="Fresh" {{ ($suhuMobilTypeArray[$i] ?? '') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                            <option value="Frozen" {{ ($suhuMobilTypeArray[$i] ?? '') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                            <option value="Tidak Ada" {{ ($suhuMobilTypeArray[$i] ?? '') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group suhu-mobil-input" style="display: {{ ($suhuMobilTypeArray[$i] ?? '') == 'Fresh' || ($suhuMobilTypeArray[$i] ?? '') == 'Frozen' ? 'block' : 'none' }};">
                                                        <label class="form-label">Nilai Suhu Mobil (°C)</label>
                                                        <input type="text" class="form-control" name="suhu_mobil[]" value="{{ $suhuMobilArray[$i] ?? '' }}" placeholder="Contoh: -18°C atau 4°C">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- KONDISI PRODUK -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Kondisi Produk</label>
                                                        <select class="form-control kondisi-produk" name="kondisi_produk[]" data-row-index="{{ $i }}">
                                                            <option value="">Pilih Kondisi Produk</option>
                                                            <option value="Fresh" {{ ($kondisiProdukArray[$i] ?? '') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                            <option value="Frozen" {{ ($kondisiProdukArray[$i] ?? '') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                            <option value="Dry" {{ ($kondisiProdukArray[$i] ?? '') == 'Dry' ? 'selected' : '' }}>Dry</option>
                                                            <option value="Minyak" {{ ($kondisiProdukArray[$i] ?? '') == 'Minyak' ? 'selected' : '' }}>Minyak</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group kondisi-produk-suhu" style="display: {{ in_array(($kondisiProdukArray[$i] ?? ''), ['Fresh', 'Frozen', 'Dry', 'Minyak']) ? 'block' : 'none' }};">
                                                        <label class="form-label">Suhu Kondisi Produk (°C)</label>
                                                        <input type="text" class="form-control" name="kondisi_produk_suhu[]" value="{{ $kondisiProdukSuhuArray[$i] ?? '' }}" placeholder="Suhu Produk">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Kondisi Fisik -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Kemasan</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_{{ $i }}" id="kemasan_ya_{{ $i }}" value="1" {{ (($kondisiFisikArray[$i]['kemasan'] ?? false) == true) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="kemasan_ya_{{ $i }}">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_{{ $i }}" id="kemasan_tidak_{{ $i }}" value="0" {{ (($kondisiFisikArray[$i]['kemasan'] ?? false) == false) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="kemasan_tidak_{{ $i }}">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="kondisi_fisik_kemasan[]" class="radio-value-kemasan-{{ $i }}" value="{{ ($kondisiFisikArray[$i]['kemasan'] ?? false) ? '1' : '0' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Warna</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_warna_{{ $i }}" id="warna_ya_{{ $i }}" value="1" {{ (($kondisiFisikArray[$i]['warna'] ?? false) == true) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="warna_ya_{{ $i }}">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_warna_{{ $i }}" id="warna_tidak_{{ $i }}" value="0" {{ (($kondisiFisikArray[$i]['warna'] ?? false) == false) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="warna_tidak_{{ $i }}">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="kondisi_fisik_warna[]" class="radio-value-warna-{{ $i }}" value="{{ ($kondisiFisikArray[$i]['warna'] ?? false) ? '1' : '0' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Benda Asing</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_{{ $i }}" id="benda_ya_{{ $i }}" value="1" {{ (($kondisiFisikArray[$i]['benda_asing'] ?? false) == true) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="benda_ya_{{ $i }}">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_{{ $i }}" id="benda_tidak_{{ $i }}" value="0" {{ (($kondisiFisikArray[$i]['benda_asing'] ?? false) == false) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="benda_tidak_{{ $i }}">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="kondisi_fisik_benda_asing[]" class="radio-value-benda-{{ $i }}" value="{{ ($kondisiFisikArray[$i]['benda_asing'] ?? false) ? '1' : '0' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Aroma</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_{{ $i }}" id="aroma_ya_{{ $i }}" value="1" {{ (($kondisiFisikArray[$i]['aroma'] ?? false) == true) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="aroma_ya_{{ $i }}">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_{{ $i }}" id="aroma_tidak_{{ $i }}" value="0" {{ (($kondisiFisikArray[$i]['aroma'] ?? false) == false) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="aroma_tidak_{{ $i }}">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="kondisi_fisik_aroma[]" class="radio-value-aroma-{{ $i }}" value="{{ ($kondisiFisikArray[$i]['aroma'] ?? false) ? '1' : '0' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Dokumen -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Dokumen</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Logo Halal</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="logo_halal_{{ $i }}" id="logo_ya_{{ $i }}" value="1" {{ (($logoHalalArray[$i] ?? false) == true) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="logo_ya_{{ $i }}">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="logo_halal_{{ $i }}" id="logo_tidak_{{ $i }}" value="0" {{ (($logoHalalArray[$i] ?? false) == false) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="logo_tidak_{{ $i }}">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="logo_halal[]" class="radio-value-logo-{{ $i }}" value="{{ ($logoHalalArray[$i] ?? false) ? '1' : '0' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal_{{ $i }}" id="dokumen_ya_{{ $i }}" value="1" {{ (($dokumenHalalArray[$i] ?? false) == true) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="dokumen_ya_{{ $i }}">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal_{{ $i }}" id="dokumen_tidak_{{ $i }}" value="0" {{ (($dokumenHalalArray[$i] ?? false) == false) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="dokumen_tidak_{{ $i }}">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="dokumen_halal[]" class="radio-value-dokumen-{{ $i }}" value="{{ ($dokumenHalalArray[$i] ?? false) ? '1' : '0' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>COA</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="coa_{{ $i }}" id="coa_ya_{{ $i }}" value="1" {{ (($coaArray[$i] ?? false) == true) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="coa_ya_{{ $i }}">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="coa_{{ $i }}" id="coa_tidak_{{ $i }}" value="0" {{ (($coaArray[$i] ?? false) == false) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="coa_tidak_{{ $i }}">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="coa[]" class="radio-value-coa-{{ $i }}" value="{{ ($coaArray[$i] ?? false) ? '1' : '0' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Upload COA (PDF)</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        @php
                                                            $coaFilePath = $fileCoaArray[$i] ?? null;
                                                        @endphp
                                                        @if($coaFilePath)
                                                            <div class="mb-2">
                                                                <a href="{{ asset('storage/' . $coaFilePath) }}" target="_blank" class="btn btn-sm btn-info">
                                                                    Lihat COA
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <div class="form-group">
                                                            <label class="form-label">Ganti File COA (PDF)</label>
                                                            <input type="file" name="file_coa[]" class="form-control" accept="application/pdf">
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
                                                            <label class="form-label">Hasil Uji FFA</label>
                                                            <input type="text" class="form-control" name="hasil_uji_ffa[]" value="{{ $hasilUjiFfaArray[$i] ?? '' }}" placeholder="Hasil Uji FFA">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Status</label>
                                                            <select class="form-control" name="status_baris[]">
                                                                <option value="">Pilih Status</option>
                                                                <option value="Hold" {{ ($statusBarisArray[$i] ?? '') == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                                <option value="Release" {{ ($statusBarisArray[$i] ?? '') == 'Release' ? 'selected' : '' }}>Release</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Spesifikasi</label>
                                                            <textarea class="form-control" name="spesifikasi[]" rows="2" placeholder="Spesifikasi">{{ $spesifikasiArray[$i] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Keterangan Hasil</label>
                                                            <textarea class="form-control" name="keterangan_hasil[]" rows="2" placeholder="Keterangan hasil pemeriksaan">{{ $keteranganArray[$i] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="col-md-12 d-flex justify-content-end mt-3">
                                    <a href="{{ route('pemeriksaan-bahan-baku.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Update Data</button>
                                    <!-- <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button> -->
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
<script>
document.addEventListener('DOMContentLoaded', function() {

    const produkByKategori = @json($produkByKategori ?? []);
    const produkMeta = @json($produkMeta ?? []);

    const isFreshOrFrozen = (val) => val === 'Fresh' || val === 'Frozen';

    const populateProdukOptionsForRow = function(unifiedRow, kategoriCode) {
        if (!unifiedRow) return;
        const produkSelect = unifiedRow.querySelector('select.produk-select');
        if (!produkSelect) return;

        const selectedFromAttr = produkSelect.getAttribute('data-selected') || '';

        while (produkSelect.options.length > 0) {
            produkSelect.remove(0);
        }
        produkSelect.add(new Option('Pilih Produk', ''));

        if (kategoriCode && produkByKategori && produkByKategori[kategoriCode]) {
            (produkByKategori[kategoriCode] || []).forEach(function(p) {
                const opt = new Option(p.nama, p.id);
                produkSelect.add(opt);
            });
        }

        if (selectedFromAttr) {
            produkSelect.value = selectedFromAttr;
        }
    };

    const renderBadgesAndHiddenInputs = function(containerBadges, containerInputs, values, badgeClass) {
        if (!containerBadges || !containerInputs) return;
        const safeValues = Array.isArray(values) ? values.filter(v => v !== null && v !== undefined && String(v).trim() !== '').map(v => String(v)) : [];

        containerBadges.innerHTML = '';
        containerInputs.innerHTML = '';

        if (!safeValues.length) {
            const span = document.createElement('span');
            span.className = 'text-muted small';
            span.textContent = '-';
            containerBadges.appendChild(span);
            return;
        }

        safeValues.forEach(function(val) {
            const badge = document.createElement('span');
            badge.className = badgeClass;
            badge.textContent = val;
            containerBadges.appendChild(badge);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.value = val;
            containerInputs.appendChild(input);
        });
    };

    const applyProdukMetaForRow = function(unifiedRow) {
        if (!unifiedRow) return;
        const rowIndex = unifiedRow.getAttribute('data-row-index');
        const produkSelect = unifiedRow.querySelector('select.produk-select');
        if (!produkSelect) return;

        const produkId = produkSelect.value;
        const meta = (produkId && produkMeta) ? (produkMeta[produkId] || null) : null;
        const produsenVals = meta && Array.isArray(meta.produsen) ? meta.produsen : [];
        const distributorVals = meta && Array.isArray(meta.distributor) ? meta.distributor : [];

        const produsenBadges = unifiedRow.querySelector('.produsen-badges');
        const produsenInputs = unifiedRow.querySelector('.produsen-hidden-inputs');
        const distributorBadges = unifiedRow.querySelector('.distributor-badges');
        const distributorInputs = unifiedRow.querySelector('.distributor-hidden-inputs');

        if (produsenInputs) {
            // update hidden input names
            produsenInputs.querySelectorAll('input[type="hidden"]').forEach(i => i.remove());
        }
        if (distributorInputs) {
            distributorInputs.querySelectorAll('input[type="hidden"]').forEach(i => i.remove());
        }

        // Render badges first
        if (produsenBadges) produsenBadges.innerHTML = '';
        if (distributorBadges) distributorBadges.innerHTML = '';

        if (produsenBadges && produsenInputs) {
            produsenBadges.innerHTML = '';
            produsenInputs.innerHTML = '';
            const safeProdusen = Array.isArray(produsenVals) ? produsenVals.filter(v => v && String(v).trim() !== '') : [];
            if (!safeProdusen.length) {
                const span = document.createElement('span');
                span.className = 'text-muted small';
                span.textContent = '-';
                produsenBadges.appendChild(span);
            } else {
                safeProdusen.forEach(function(v) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-light-primary text-primary';
                    badge.textContent = v;
                    produsenBadges.appendChild(badge);

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `produsen[${rowIndex}][]`;
                    input.value = v;
                    produsenInputs.appendChild(input);
                });
            }
        }

        if (distributorBadges && distributorInputs) {
            distributorBadges.innerHTML = '';
            distributorInputs.innerHTML = '';
            const safeDistributor = Array.isArray(distributorVals) ? distributorVals.filter(v => v && String(v).trim() !== '') : [];
            if (!safeDistributor.length) {
                const span = document.createElement('span');
                span.className = 'text-muted small';
                span.textContent = '-';
                distributorBadges.appendChild(span);
            } else {
                safeDistributor.forEach(function(v) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-light-info text-info';
                    badge.textContent = v;
                    distributorBadges.appendChild(badge);

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `distributor[${rowIndex}][]`;
                    input.value = v;
                    distributorInputs.appendChild(input);
                });
            }
        }
    };

    const initChoices = function() {
        const selectElements = document.querySelectorAll('select.choices');
        selectElements.forEach(function(select) {
            if (select.dataset.choicesInitialized === 'true') return;
            if (select.classList.contains('choices__input')) return;

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
            } catch (err) {
                console.error('Error initializing Choices:', err);
            }
        });
    };

    const updateConditionalForRow = function(unifiedRow) {
        if (!unifiedRow) return;

        const suhuProdukSelect = unifiedRow.querySelector('select.suhu-produk-type');
        const suhuProdukInputField = unifiedRow.querySelector('.suhu-produk-input');
        const suhuProdukInput = suhuProdukInputField ? suhuProdukInputField.querySelector('input') : null;
        if (suhuProdukSelect && suhuProdukInputField) {
            if (isFreshOrFrozen(suhuProdukSelect.value)) {
                suhuProdukInputField.style.display = 'block';
            } else {
                suhuProdukInputField.style.display = 'none';
                if (suhuProdukInput) suhuProdukInput.value = '';
            }
        }

        const suhuMobilSelect = unifiedRow.querySelector('select.suhu-mobil-type');
        const suhuMobilInputField = unifiedRow.querySelector('.suhu-mobil-input');
        const suhuMobilInput = suhuMobilInputField ? suhuMobilInputField.querySelector('input') : null;
        if (suhuMobilSelect && suhuMobilInputField) {
            if (isFreshOrFrozen(suhuMobilSelect.value)) {
                suhuMobilInputField.style.display = 'block';
            } else {
                suhuMobilInputField.style.display = 'none';
                if (suhuMobilInput) suhuMobilInput.value = '';
            }
        }

        const kondisiProdukSelect = unifiedRow.querySelector('select.kondisi-produk');
        const kondisiProdukSuhuField = unifiedRow.querySelector('.kondisi-produk-suhu');
        const kondisiProdukSuhuInput = kondisiProdukSuhuField ? kondisiProdukSuhuField.querySelector('input') : null;
        if (kondisiProdukSelect && kondisiProdukSuhuField) {
            const val = kondisiProdukSelect.value;
            if (val === 'Fresh' || val === 'Frozen' || val === 'Dry' || val === 'Minyak') {
                kondisiProdukSuhuField.style.display = 'block';
            } else {
                kondisiProdukSuhuField.style.display = 'none';
                if (kondisiProdukSuhuInput) kondisiProdukSuhuInput.value = '';
            }
        }
    };

    const initAllRows = function() {
        document.querySelectorAll('#unified-container .unified-row').forEach(function(row) {
            updateConditionalForRow(row);
        });
    };

    // 1) Init plugins
    initChoices();

    // 2) Init conditional fields based on existing values (pre-filled edit)
    initAllRows();

    // 3) Event delegation: update conditional fields reliably even after refresh / Choices rendering
    document.addEventListener('change', function(e) {
        const target = e.target;
        if (!target) return;

        if (target.matches('select.kategori-produk-select')) {
            const unifiedRow = target.closest('.unified-row');
            populateProdukOptionsForRow(unifiedRow, target.value);
            applyProdukMetaForRow(unifiedRow);
        }

        if (target.matches('select.produk-select')) {
            const unifiedRow = target.closest('.unified-row');
            applyProdukMetaForRow(unifiedRow);
        }

        if (target.matches('select.suhu-produk-type, select.suhu-mobil-type, select.kondisi-produk')) {
            const unifiedRow = target.closest('.unified-row');
            updateConditionalForRow(unifiedRow);
        }

        // Kondisi Fisik - Kemasan
        if (target.name && target.name.startsWith('kondisi_fisik_kemasan_')) {
            const rowIndex = target.name.split('_').pop();
            const hiddenInput = document.querySelector('.radio-value-kemasan-' + rowIndex);
            if (hiddenInput) hiddenInput.value = target.value;
        }

        // Kondisi Fisik - Warna
        if (target.name && target.name.startsWith('kondisi_fisik_warna_')) {
            const rowIndex = target.name.split('_').pop();
            const hiddenInput = document.querySelector('.radio-value-warna-' + rowIndex);
            if (hiddenInput) hiddenInput.value = target.value;
        }

        // Kondisi Fisik - Benda Asing
        if (target.name && target.name.startsWith('kondisi_fisik_benda_asing_')) {
            const rowIndex = target.name.split('_').pop();
            const hiddenInput = document.querySelector('.radio-value-benda-' + rowIndex);
            if (hiddenInput) hiddenInput.value = target.value;
        }

        // Kondisi Fisik - Aroma
        if (target.name && target.name.startsWith('kondisi_fisik_aroma_')) {
            const rowIndex = target.name.split('_').pop();
            const hiddenInput = document.querySelector('.radio-value-aroma-' + rowIndex);
            if (hiddenInput) hiddenInput.value = target.value;
        }

        // Dokumentasi - Logo Halal
        if (target.name && target.name.startsWith('logo_halal_')) {
            const rowIndex = target.name.split('_').pop();
            const hiddenInput = document.querySelector('.radio-value-logo-' + rowIndex);
            if (hiddenInput) hiddenInput.value = target.value;
        }

        // Dokumentasi - Dokumen Halal
        if (target.name && target.name.startsWith('dokumen_halal_')) {
            const rowIndex = target.name.split('_').pop();
            const hiddenInput = document.querySelector('.radio-value-dokumen-' + rowIndex);
            if (hiddenInput) hiddenInput.value = target.value;
        }

        // Dokumentasi - COA
        if (target.name && target.name.startsWith('coa_')) {
            const rowIndex = target.name.split('_').pop();
            const hiddenInput = document.querySelector('.radio-value-coa-' + rowIndex);
            if (hiddenInput) hiddenInput.value = target.value;
        }
    });

    // Handle hapus baris
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row-btn')) {
            const row = e.target.closest('.unified-row');
            const rowCount = document.querySelectorAll('#unified-container .unified-row').length;

            if (rowCount > 1 && row) {
                if (confirm('Apakah Anda yakin ingin menghapus baris ini?')) {
                    row.remove();
                    updateRowNumbers();
                }
            } else {
                alert('Minimal harus ada satu baris data!');
            }
        }
    });

    // Initial populate for existing rows
    document.querySelectorAll('#unified-container .unified-row').forEach(function(unifiedRow) {
        const kategoriSelect = unifiedRow.querySelector('select.kategori-produk-select');
        if (kategoriSelect) {
            populateProdukOptionsForRow(unifiedRow, kategoriSelect.value);
            applyProdukMetaForRow(unifiedRow);
        }
    });
});

// Update row numbers after delete
function updateRowNumbers() {
    const rows = document.querySelectorAll('#unified-container .unified-row');
    rows.forEach((row, index) => {
        const title = row.querySelector('h6');
        if (title) {
            title.textContent = `Baris ${index + 1}`;
        }
    });
}
</script>
@endpush
@endsection
