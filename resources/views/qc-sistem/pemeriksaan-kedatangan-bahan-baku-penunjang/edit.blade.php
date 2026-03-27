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
                                        $imageBahanBakuArray = json_decode($pemeriksaanBahanBaku->image_bahan_baku_array ?? '[]', true) ?? [];
                                        $hasilUjiFfaArray = json_decode($pemeriksaanBahanBaku->hasil_uji_ffa_array, true) ?? [];
                                        $statusBarisArray = json_decode($pemeriksaanBahanBaku->status_baris_array, true) ?? [];
                                        $keteranganArray = json_decode($pemeriksaanBahanBaku->keterangan_array, true) ?? [];

                                        // Determine detail row count
                                        $rowCount = max(
                                            count($idBahanArray),
                                            count($kodeProduksiArray),
                                            count($expireDateArray),
                                            count($jumlahDatangArray),
                                            count($jumlahSamplingArray),
                                            count($spesifikasiArray),
                                            count($kondisiProdukArray),
                                            count($suhuProdukArray),
                                            count($suhuProdukTypeArray),
                                            count($suhuMobilArray),
                                            count($suhuMobilTypeArray),
                                            count($kondisiProdukSuhuArray),
                                            count($kondisiFisikArray),
                                            count($logoHalalArray),
                                            count($dokumenHalalArray),
                                            count($coaArray),
                                            count($fileCoaArray),
                                            count($imageBahanBakuArray),
                                            count($hasilUjiFfaArray),
                                            count($statusBarisArray),
                                            count($keteranganArray)
                                        );

                                        // Group indices by bahanId but keep first-seen order
                                        $firstBahanId = $idBahanArray[0] ?? null;
                                        if (is_array($firstBahanId)) {
                                            $firstBahanId = $firstBahanId[0] ?? null;
                                        }
                                        $firstBahanId = $firstBahanId === null ? '' : trim((string) $firstBahanId);
                                        if ($firstBahanId !== '' && ctype_digit($firstBahanId)) {
                                            $firstBahanId = (string) ((int) $firstBahanId);
                                        }

                                        $groupedDetailIdx = [];
                                        $orderedBahanIds = [];
                                        $lastSeenBahanId = $firstBahanId;
                                        for ($i = 0; $i < $rowCount; $i++) {
                                            $rawBahanId = $idBahanArray[$i] ?? '';
                                            if (is_array($rawBahanId)) {
                                                $rawBahanId = $rawBahanId[0] ?? null;
                                            }
                                            $bahanId = $rawBahanId === null ? '' : trim((string) $rawBahanId);
                                            if ($bahanId !== '' && ctype_digit($bahanId)) {
                                                $bahanId = (string) ((int) $bahanId);
                                            }

                                            if ($bahanId === '') {
                                                $bahanId = $lastSeenBahanId !== '' ? trim((string) $lastSeenBahanId) : $firstBahanId;
                                                if ($bahanId !== '' && ctype_digit($bahanId)) {
                                                    $bahanId = (string) ((int) $bahanId);
                                                }
                                            }
                                            if ($bahanId === '') {
                                                $bahanId = '__unknown__';
                                            }

                                            $lastSeenBahanId = ($bahanId !== '__unknown__') ? $bahanId : $lastSeenBahanId;

                                            if (!isset($groupedDetailIdx[$bahanId])) {
                                                $groupedDetailIdx[$bahanId] = [];
                                                $orderedBahanIds[] = $bahanId;
                                            }
                                            $groupedDetailIdx[$bahanId][] = $i;
                                        }
                                    @endphp
                                    
                                    <div id="unified-container">
                                        @forelse($orderedBahanIds as $produkNo => $bahanId)
                                            @php
                                                $detailIdxList = $groupedDetailIdx[$bahanId] ?? [];
                                                $firstIdx = $detailIdxList[0] ?? 0;

                                                $bahanIdForRow = $idBahanArray[$firstIdx] ?? '';
                                                if (is_array($bahanIdForRow)) {
                                                    $bahanIdForRow = $bahanIdForRow[0] ?? '';
                                                }
                                                $selectedProdukId = $bahanIdForRow ? ($produkByBahanId[$bahanIdForRow] ?? '') : '';
                                                $selectedKategori = old('kategori_code.' . $produkNo);
                                                if ($selectedKategori === null || $selectedKategori === '') {
                                                    $selectedKategori = $selectedProdukId ? ($produkKategoriById[$selectedProdukId] ?? '') : '';
                                                }

                                                $existingDistributor = $distributorArray[$firstIdx] ?? '';
                                                if (is_array($existingDistributor)) {
                                                    $existingDistributor = implode(', ', array_values(array_filter(array_map('strval', $existingDistributor), fn ($v) => $v !== '')));
                                                }
                                                $existingDistributorItems = array_values(array_filter(array_map('trim', explode(',', (string) $existingDistributor)), fn ($v) => $v !== ''));

                                                $existingProdusen = $produsenArray[$firstIdx] ?? '';
                                                if (is_array($existingProdusen)) {
                                                    $existingProdusen = implode(', ', array_values(array_filter(array_map('strval', $existingProdusen), fn ($v) => $v !== '')));
                                                }
                                                $existingProdusenItems = array_values(array_filter(array_map('trim', explode(',', (string) $existingProdusen)), fn ($v) => $v !== ''));
                                            @endphp

                                            <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;" data-row-index="{{ $produkNo }}">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="text-primary mb-0">Produk {{ $produkNo + 1 }}</h6>
                                                </div>

                                                <!-- Informasi Produk (Header) -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Kategori</label>
                                                            <select class="choices form-control kategori-produk-select" name="kategori_code[]" data-row-index="{{ $produkNo }}">
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
                                                            <select class="form-control produk-select" name="id_produk[]" data-row-index="{{ $produkNo }}" data-selected="{{ old('id_produk.' . $produkNo, $selectedProdukId) }}">
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
                                                                @forelse ($existingDistributorItems as $d)
                                                                    <span class="badge bg-light-info text-info">{{ $d }}</span>
                                                                @empty
                                                                    <span class="text-muted small">-</span>
                                                                @endforelse
                                                            </div>
                                                            <div class="distributor-hidden-inputs">
                                                                @foreach ($existingDistributorItems as $d)
                                                                    <input type="hidden" name="distributor[{{ $produkNo }}][]" value="{{ $d }}">
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
                                                                @forelse ($existingProdusenItems as $p)
                                                                    <span class="badge bg-light-primary text-primary">{{ $p }}</span>
                                                                @empty
                                                                    <span class="text-muted small">-</span>
                                                                @endforelse
                                                            </div>
                                                            <div class="produsen-hidden-inputs">
                                                                @foreach ($existingProdusenItems as $p)
                                                                    <input type="hidden" name="produsen[{{ $produkNo }}][]" value="{{ $p }}">
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
                                                                <option value="{{ $name }}" {{ ($negaraProdusenArray[$firstIdx] ?? '') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- SUHU PRODUK -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Suhu Produk</label>
                                                        <select class="form-control suhu-produk-type" name="suhu_produk_type[]" data-row-index="{{ $produkNo }}">
                                                            <option value="">Pilih Jenis Suhu Produk</option>
                                                            <option value="Fresh" {{ ($suhuProdukTypeArray[$firstIdx] ?? '') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                            <option value="Frozen" {{ ($suhuProdukTypeArray[$firstIdx] ?? '') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                            <option value="Tidak Ada" {{ ($suhuProdukTypeArray[$firstIdx] ?? '') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group suhu-produk-input" style="display: {{ ($suhuProdukTypeArray[$firstIdx] ?? '') == 'Fresh' || ($suhuProdukTypeArray[$firstIdx] ?? '') == 'Frozen' ? 'block' : 'none' }};">
                                                        <label class="form-label">Nilai Suhu Produk (°C)</label>
                                                        <input type="text" class="form-control" name="suhu_produk[]" value="{{ $suhuProdukArray[$firstIdx] ?? '' }}" placeholder="Contoh: -18°C atau 4°C">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- SUHU MOBIL -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Suhu Mobil</label>
                                                        <select class="form-control suhu-mobil-type" name="suhu_mobil_type[]" data-row-index="{{ $produkNo }}">
                                                            <option value="">Pilih Jenis Suhu Mobil</option>
                                                            <option value="Fresh" {{ ($suhuMobilTypeArray[$firstIdx] ?? '') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                            <option value="Frozen" {{ ($suhuMobilTypeArray[$firstIdx] ?? '') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                            <option value="Tidak Ada" {{ ($suhuMobilTypeArray[$firstIdx] ?? '') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group suhu-mobil-input" style="display: {{ ($suhuMobilTypeArray[$firstIdx] ?? '') == 'Fresh' || ($suhuMobilTypeArray[$firstIdx] ?? '') == 'Frozen' ? 'block' : 'none' }};">
                                                        <label class="form-label">Nilai Suhu Mobil (°C)</label>
                                                        <input type="text" class="form-control" name="suhu_mobil[]" value="{{ $suhuMobilArray[$firstIdx] ?? '' }}" placeholder="Contoh: -18°C atau 4°C">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- KONDISI PRODUK -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Kondisi Produk</label>
                                                        <select class="form-control kondisi-produk" name="kondisi_produk[]" data-row-index="{{ $produkNo }}">
                                                            <option value="">Pilih Kondisi Produk</option>
                                                            <option value="Fresh" {{ ($kondisiProdukArray[$firstIdx] ?? '') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                            <option value="Frozen" {{ ($kondisiProdukArray[$firstIdx] ?? '') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                            <option value="Dry" {{ ($kondisiProdukArray[$firstIdx] ?? '') == 'Dry' ? 'selected' : '' }}>Dry</option>
                                                            <option value="Minyak" {{ ($kondisiProdukArray[$firstIdx] ?? '') == 'Minyak' ? 'selected' : '' }}>Minyak</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group kondisi-produk-suhu" style="display: {{ in_array(($kondisiProdukArray[$firstIdx] ?? ''), ['Fresh', 'Frozen', 'Dry', 'Minyak']) ? 'block' : 'none' }};">
                                                        <label class="form-label">Suhu Kondisi Produk (°C)</label>
                                                        <input type="text" class="form-control" name="kondisi_produk_suhu[]" value="{{ $kondisiProdukSuhuArray[$firstIdx] ?? '' }}" placeholder="Suhu Produk">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="detail-items">
                                                @foreach($detailIdxList as $detailNo => $i)
                                                    @php
                                                        $kond = $kondisiFisikArray[$i] ?? [];
                                                        $coaFilePath = $fileCoaArray[$i] ?? null;
                                                        $imgPath = $imageBahanBakuArray[$i] ?? null;
                                                    @endphp
                                                    <div class="detail-item border rounded p-3 mb-3" style="background: #fff;" data-detail-suffix="{{ $detailNo + 1 }}">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="fw-bold detail-title">Detail #{{ $detailNo + 1 }}</span>
                                                            <button type="button" class="btn btn-danger btn-sm remove-detail-btn" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
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

                                                        <div class="form-section mb-3">
                                                            <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="mb-3">
                                                                        <label class="form-label"><strong>Kemasan</strong></label>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_{{ $produkNo }}_{{ $detailNo + 1 }}" value="1" {{ (($kond['kemasan'] ?? false) == true) ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Ya ✓</label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_{{ $produkNo }}_{{ $detailNo + 1 }}" value="0" {{ (($kond['kemasan'] ?? false) == false) ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Tidak ✗</label>
                                                                        </div>
                                                                        <input type="hidden" name="kondisi_fisik_kemasan[]" value="{{ ($kond['kemasan'] ?? false) ? '1' : '0' }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="mb-3">
                                                                        <label class="form-label"><strong>Warna</strong></label>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_warna_{{ $produkNo }}_{{ $detailNo + 1 }}" value="1" {{ (($kond['warna'] ?? false) == true) ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Ya ✓</label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_warna_{{ $produkNo }}_{{ $detailNo + 1 }}" value="0" {{ (($kond['warna'] ?? false) == false) ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Tidak ✗</label>
                                                                        </div>
                                                                        <input type="hidden" name="kondisi_fisik_warna[]" value="{{ ($kond['warna'] ?? false) ? '1' : '0' }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="mb-3">
                                                                        <label class="form-label"><strong>Benda Asing</strong></label>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_{{ $produkNo }}_{{ $detailNo + 1 }}" value="1" {{ (($kond['benda_asing'] ?? false) == true) ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Ya ✓</label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_{{ $produkNo }}_{{ $detailNo + 1 }}" value="0" {{ (($kond['benda_asing'] ?? false) == false) ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Tidak ✗</label>
                                                                        </div>
                                                                        <input type="hidden" name="kondisi_fisik_benda_asing[]" value="{{ ($kond['benda_asing'] ?? false) ? '1' : '0' }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="mb-3">
                                                                        <label class="form-label"><strong>Aroma</strong></label>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_{{ $produkNo }}_{{ $detailNo + 1 }}" value="1" {{ (($kond['aroma'] ?? false) == true) ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Ya ✓</label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_{{ $produkNo }}_{{ $detailNo + 1 }}" value="0" {{ (($kond['aroma'] ?? false) == false) ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Tidak ✗</label>
                                                                        </div>
                                                                        <input type="hidden" name="kondisi_fisik_aroma[]" value="{{ ($kond['aroma'] ?? false) ? '1' : '0' }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-section mb-3">
                                                            <h6 class="text-primary mb-2">Upload COA (PDF)</h6>
                                                            <div class="row">
                                                                <div class="col-md-6">
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

                                                        <div class="form-section mb-3">
                                                            <h6 class="text-primary mb-2">Upload Gambar</h6>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    @if($imgPath)
                                                                        <div class="mb-2">
                                                                            <a href="{{ asset('storage/' . $imgPath) }}" target="_blank" class="btn btn-sm btn-info">Lihat Foto</a>
                                                                        </div>
                                                                    @endif
                                                                    <div class="form-group">
                                                                        <label class="form-label">Ganti Foto Bahan Baku (Max 1MB)</label>
                                                                        <input type="file" name="image_bahan_baku[]" class="form-control" accept="image/*" capture="camera">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

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
                                                @endforeach
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-primary btn-sm add-detail-btn"><i class="bi bi-plus"></i> Tambah Detail</button>
                                                </div>
                                            </div>

                                            @if(count($orderedBahanIds) > 1)
                                                <div class="row mt-3 pt-3 border-top">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-danger btn-sm remove-row-btn"><i class="bi bi-trash"></i> Hapus Produk</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        @empty
                                            <div class="text-muted">Tidak ada data produk.</div>
                                    @endforelse
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

<style>
    .collapse-toggle-btn {
        width: auto;
        display: inline-flex;
        align-items: center;
        text-align: left;
    }
    .collapse-chevron { transition: transform .2s ease; }
    .collapse-toggle-btn[aria-expanded="true"] .collapse-chevron { transform: rotate(180deg); }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const produkByKategori = @json($produkByKategori ?? []);
    const produkMeta = @json($produkMeta ?? []);

    const bsCollapse = (el) => {
        try {
            if (!el || !window.bootstrap || !window.bootstrap.Collapse) return null;
            return window.bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
        } catch (e) {
            return null;
        }
    };

    const uniqueDomId = (prefix) => {
        let id;
        do {
            id = `${prefix}_${Date.now()}_${Math.random().toString(16).slice(2)}`;
        } while (document.getElementById(id));
        return id;
    };

    const ensureProdukCollapsible = (rowEl, rowIdx) => {
        if (!rowEl) return;
        if (!rowEl.dataset.produkCollapseId) {
            rowEl.dataset.produkCollapseId = uniqueDomId('produk_bbp_e');
        }
        const collapseId = rowEl.dataset.produkCollapseId;

        const headerTitle = rowEl.querySelector(':scope > .d-flex h6');
        if (!headerTitle) return;

        if (!headerTitle.querySelector('button[data-bs-toggle="collapse"]')) {
            const existingText = (headerTitle.textContent || '').trim();
            headerTitle.textContent = '';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-primary btn-sm d-inline-flex align-items-center gap-2 collapse-toggle-btn';
            btn.setAttribute('data-bs-toggle', 'collapse');
            btn.setAttribute('data-bs-target', `#${collapseId}`);
            btn.setAttribute('aria-expanded', 'true');
            btn.setAttribute('aria-controls', collapseId);

            const span = document.createElement('span');
            span.className = 'text-primary mb-0 produk-collapse-label';
            span.textContent = existingText || `Produk ${rowIdx + 1}`;

            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down collapse-chevron';

            btn.appendChild(span);
            btn.appendChild(icon);
            headerTitle.appendChild(btn);
        }

        let body = rowEl.querySelector(':scope > .produk-collapse.collapse');
        if (body) {
            body.id = collapseId;
        } else {
            body = document.createElement('div');
            body.className = 'produk-collapse collapse show';
            body.id = collapseId;

            const headerWrap = rowEl.querySelector(':scope > .d-flex');
            const nodesToMove = [];
            let node = headerWrap ? headerWrap.nextSibling : null;
            while (node) {
                const next = node.nextSibling;
                nodesToMove.push(node);
                node = next;
            }
            nodesToMove.forEach((n) => body.appendChild(n));
            rowEl.appendChild(body);
        }
    };

    const ensureDetailCollapsible = (detailEl) => {
        if (!detailEl) return;
        let collapseId = detailEl.dataset.detailCollapseId || '';

        const hasDuplicateId = (id) => {
            if (!id) return true;
            const el = document.getElementById(id);
            if (!el) return false;
            return !detailEl.contains(el);
        };

        if (hasDuplicateId(collapseId)) {
            collapseId = uniqueDomId('detail_bbp_e');
            detailEl.dataset.detailCollapseId = collapseId;
        }

        const header = detailEl.firstElementChild;
        if (!header) return;

        const titleEl = header.querySelector('.detail-title');
        if (titleEl && titleEl.tagName.toLowerCase() !== 'button') {
            const existingText = (titleEl.textContent || '').trim();

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-primary btn-sm fw-bold d-inline-flex align-items-center gap-2 collapse-toggle-btn detail-title';
            btn.setAttribute('data-bs-toggle', 'collapse');
            btn.setAttribute('data-bs-target', `#${collapseId}`);
            btn.setAttribute('aria-expanded', 'true');
            btn.setAttribute('aria-controls', collapseId);

            const span = document.createElement('span');
            span.className = 'detail-collapse-label';
            span.textContent = existingText || 'Detail';

            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down collapse-chevron';

            btn.appendChild(span);
            btn.appendChild(icon);
            titleEl.replaceWith(btn);
        }

        const existingBtn = header.querySelector('button.detail-title[data-bs-toggle="collapse"]');
        if (existingBtn) {
            existingBtn.setAttribute('data-bs-target', `#${collapseId}`);
            existingBtn.setAttribute('aria-controls', collapseId);
            if (!existingBtn.querySelector('.collapse-chevron')) {
                const icon = document.createElement('i');
                icon.className = 'bi bi-chevron-down collapse-chevron';
                existingBtn.appendChild(icon);
            }
        }

        let body = detailEl.querySelector(':scope > .detail-collapse.collapse');
        if (body) {
            body.id = collapseId;
        } else {
            body = document.createElement('div');
            body.className = 'detail-collapse collapse show';
            body.id = collapseId;

            const nodesToMove = [];
            let node = header.nextSibling;
            while (node) {
                const next = node.nextSibling;
                nodesToMove.push(node);
                node = next;
            }
            nodesToMove.forEach((n) => body.appendChild(n));
            detailEl.appendChild(body);
        }
    };

    const updateProdukLabel = (rowEl, rowIdx) => {
        if (!rowEl) return;
        const labelEl = rowEl.querySelector('.produk-collapse-label');
        if (!labelEl) return;
        const produkSelect = rowEl.querySelector('select.produk-select');
        const selectedText = produkSelect && produkSelect.selectedOptions && produkSelect.selectedOptions[0]
            ? String(produkSelect.selectedOptions[0].textContent || '').trim()
            : '';
        labelEl.textContent = selectedText || `Produk ${rowIdx + 1}`;
    };

    const updateDetailLabel = (detailEl, idxWithinRow) => {
        if (!detailEl) return;
        const labelEl = detailEl.querySelector('.detail-collapse-label');
        if (!labelEl) return;
        const kodeInp = detailEl.querySelector('input[name="kode_produksi[]"]');
        const kodeVal = kodeInp ? String(kodeInp.value || '').trim() : '';
        labelEl.textContent = kodeVal || `Detail #${idxWithinRow + 1}`;
    };

    const collapseOtherDetailsInRow = (rowEl, activeDetailEl) => {
        if (!rowEl) return;
        rowEl.querySelectorAll('.detail-item').forEach((detailEl) => {
            const body = detailEl.querySelector(':scope > .detail-collapse.collapse');
            if (!body) return;
            const inst = bsCollapse(body);
            if (inst) {
                if (detailEl === activeDetailEl) inst.show();
                else inst.hide();
                return;
            }
            if (detailEl === activeDetailEl) body.classList.add('show');
            else body.classList.remove('show');
        });
    };

    const initBbpEditCollapses = () => {
        const rows = Array.from(document.querySelectorAll('#unified-container .unified-row'));
        rows.forEach((rowEl, rowIdx) => {
            ensureProdukCollapsible(rowEl, rowIdx);
            updateProdukLabel(rowEl, rowIdx);
            const details = Array.from(rowEl.querySelectorAll('.detail-item'));
            details.forEach((d, di) => {
                ensureDetailCollapsible(d);
                updateDetailLabel(d, di);
            });
        });
    };

    const isFreshOrFrozen = (val) => val === 'Fresh' || val === 'Frozen';

    const populateProdukOptionsForRow = function(unifiedRow, kategoriCode) {
        if (!unifiedRow) return;
        const produkSelect = unifiedRow.querySelector('select.produk-select');
        if (!produkSelect) return;

        const selectedFromAttr = produkSelect.getAttribute('data-selected') || '';

        if (produkSelect.choicesInstance) {
            try { produkSelect.choicesInstance.destroy(); } catch (e) {}
            produkSelect.choicesInstance = null;
        }
        if (produkSelect.dataset) {
            produkSelect.dataset.choicesInitialized = 'false';
        }

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

        try {
            produkSelect.choicesInstance = new Choices(produkSelect, {
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
            produkSelect.dataset.choicesInitialized = 'true';
        } catch (err) {
            console.error('Error initializing Choices (produk):', err);
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
            const kategoriSelect = row.querySelector('select.kategori-produk-select');
            if (kategoriSelect) {
                populateProdukOptionsForRow(row, kategoriSelect.value);
                applyProdukMetaForRow(row);
            }
            updateConditionalForRow(row);
        });
    };

    // Init plugins and populate existing rows (edit mode)
    initChoices();
    initAllRows();

    try {
        initBbpEditCollapses();
    } catch (e) {
        console.error('Error initializing BBP edit collapse:', e);
    }

    // Event delegation
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
            const idx = Array.from(document.querySelectorAll('#unified-container .unified-row')).indexOf(unifiedRow);
            updateProdukLabel(unifiedRow, idx >= 0 ? idx : 0);
        }

        if (target.matches('select.suhu-produk-type, select.suhu-mobil-type, select.kondisi-produk')) {
            const unifiedRow = target.closest('.unified-row');
            updateConditionalForRow(unifiedRow);
        }

        // Kondisi Fisik radios live inside each .detail-item
        if (target.matches('.detail-item input[type="radio"]')) {
            const name = target.getAttribute('name') || '';
            const map = {
                kondisi_fisik_kemasan: 'kondisi_fisik_kemasan[]',
                kondisi_fisik_warna: 'kondisi_fisik_warna[]',
                kondisi_fisik_benda_asing: 'kondisi_fisik_benda_asing[]',
                kondisi_fisik_aroma: 'kondisi_fisik_aroma[]',
            };
            const base = name.replace(/_\d+_\d+$/, '');
            const hiddenName = map[base];
            if (hiddenName) {
                const detailEl = target.closest('.detail-item');
                if (detailEl) {
                    const hidden = detailEl.querySelector(`input[type="hidden"][name="${hiddenName}"]`);
                    if (hidden) hidden.value = target.value;
                }
            }
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

        const addBtn = e.target.closest('.add-detail-btn');
        if (addBtn) {
            const rowEl = addBtn.closest('.unified-row');
            if (!rowEl) return;
            addDetail(rowEl);
            return;
        }

        const removeDetailBtn = e.target.closest('.remove-detail-btn');
        if (removeDetailBtn) {
            const rowEl = removeDetailBtn.closest('.unified-row');
            const detailEl = removeDetailBtn.closest('.detail-item');
            if (!rowEl || !detailEl) return;
            detailEl.remove();
            reindexDetails(rowEl);
        }
    });

    // Update row numbers after delete
    function updateRowNumbers() {
        const rows = document.querySelectorAll('#unified-container .unified-row');
        rows.forEach((row, index) => {
            const label = row.querySelector('.produk-collapse-label');
            if (label) {
                updateProdukLabel(row, index);
            } else {
                const title = row.querySelector('h6');
                if (title) title.textContent = `Produk ${index + 1}`;
            }
        });
    }

    function addDetail(rowEl) {
        const container = rowEl.querySelector('.detail-items');
        const template = container ? container.querySelector('.detail-item') : null;
        if (!container || !template) return;

        const clone = template.cloneNode(true);
        resetDetailInputs(clone);
        container.appendChild(clone);
        reindexDetails(rowEl);

        try {
            ensureDetailCollapsible(clone);
            collapseOtherDetailsInRow(rowEl, clone);
        } catch (e) {
        }
    }

    function resetDetailInputs(detailEl) {
        detailEl.querySelectorAll('input[type="text"], input[type="date"], textarea').forEach((el) => {
            el.value = '';
        });
        detailEl.querySelectorAll('input[type="radio"]').forEach((el) => {
            el.checked = false;
        });
        detailEl.querySelectorAll('input[type="hidden"]').forEach((el) => {
            if ((el.getAttribute('name') || '').endsWith('[]')) el.value = '';
        });
        detailEl.querySelectorAll('input[type="file"]').forEach((el) => {
            el.value = '';
        });
        const coaLink = detailEl.querySelector('a.btn-info');
        if (coaLink && (coaLink.textContent || '').toLowerCase().includes('coa')) {
            const wrapper = coaLink.closest('.mb-2');
            if (wrapper) wrapper.remove();
        }
        const imgLink = detailEl.querySelector('a.btn-info');
        if (imgLink && (imgLink.textContent || '').toLowerCase().includes('foto')) {
            const wrapper = imgLink.closest('.mb-2');
            if (wrapper) wrapper.remove();
        }
    }

    function reindexDetails(rowEl) {
        const produkNo = String(rowEl.getAttribute('data-row-index') || '0');
        const details = Array.from(rowEl.querySelectorAll('.detail-item'));
        details.forEach((detailEl, idx) => {
            const suffix = String(idx + 1);
            detailEl.dataset.detailSuffix = suffix;

            const label = detailEl.querySelector('.detail-collapse-label');
            if (label) {
                updateDetailLabel(detailEl, idx);
            } else {
                const title = detailEl.querySelector('.detail-title');
                if (title) title.textContent = `Detail #${suffix}`;
            }

            detailEl.querySelectorAll('input[type="radio"]').forEach((radio) => {
                const name = radio.getAttribute('name') || '';
                if (name.startsWith('kondisi_fisik_kemasan_')) radio.name = `kondisi_fisik_kemasan_${produkNo}_${suffix}`;
                if (name.startsWith('kondisi_fisik_warna_')) radio.name = `kondisi_fisik_warna_${produkNo}_${suffix}`;
                if (name.startsWith('kondisi_fisik_benda_asing_')) radio.name = `kondisi_fisik_benda_asing_${produkNo}_${suffix}`;
                if (name.startsWith('kondisi_fisik_aroma_')) radio.name = `kondisi_fisik_aroma_${produkNo}_${suffix}`;
            });

            const removeBtn = detailEl.querySelector('.remove-detail-btn');
            if (removeBtn) {
                removeBtn.style.display = details.length > 1 ? '' : 'none';
            }
        });
    }

    document.addEventListener('input', function(e) {
        const target = e.target;
        if (target && target.matches('input[name="kode_produksi[]"]')) {
            const detailEl = target.closest('.detail-item');
            const rowEl = target.closest('.unified-row');
            if (!detailEl || !rowEl) return;
            const idx = Array.from(rowEl.querySelectorAll('.detail-item')).indexOf(detailEl);
            updateDetailLabel(detailEl, idx >= 0 ? idx : 0);
        }
    });

    // Initialize detail indices
    document.querySelectorAll('#unified-container .unified-row').forEach((rowEl) => {
        reindexDetails(rowEl);
    });

    // Flatten header-per-produk fields into per-detail indices so multiple detail items are persisted
    try {
        const formEl = document.querySelector('form[action*="pemeriksaan-bahan-baku"]');
        if (formEl && !formEl.__bbpFlattenBound) {
            formEl.__bbpFlattenBound = true;
            formEl.addEventListener('submit', function() {
                const existing = formEl.querySelector('#__bbp_flatten_container');
                if (existing) existing.remove();

                const gen = document.createElement('div');
                gen.id = '__bbp_flatten_container';
                gen.style.display = 'none';
                formEl.appendChild(gen);

                let globalIdx = 0;

                const rows = Array.from(document.querySelectorAll('#unified-container .unified-row'));
                rows.forEach((rowEl) => {
                    const headerIdBahan = rowEl.querySelector('select.produk-select, select[name="id_bahan[]"]');
                    const headerNegara = rowEl.querySelector('select[name="negara_produsen[]"]');
                    const headerSuhuProdukType = rowEl.querySelector('select.suhu-produk-type');
                    const headerSuhuProduk = rowEl.querySelector('input[name="suhu_produk[]"]');
                    const headerSuhuMobilType = rowEl.querySelector('select.suhu-mobil-type');
                    const headerSuhuMobil = rowEl.querySelector('input[name="suhu_mobil[]"]');
                    const headerKondisiProduk = rowEl.querySelector('select.kondisi-produk');
                    const headerKondisiProdukSuhu = rowEl.querySelector('input[name="kondisi_produk_suhu[]"]');

                    const produsenVals = Array.from(rowEl.querySelectorAll('.produsen-hidden-inputs input[type="hidden"]')).map(i => i.value);
                    const distributorVals = Array.from(rowEl.querySelectorAll('.distributor-hidden-inputs input[type="hidden"]')).map(i => i.value);

                    const headerLogoHalal = rowEl.querySelector('input[name="logo_halal[]"]');
                    const headerDokumenHalal = rowEl.querySelector('input[name="dokumen_halal[]"]');
                    const headerCoa = rowEl.querySelector('input[name="coa[]"]');

                    const header = {
                        id_bahan: headerIdBahan ? String(headerIdBahan.value || '') : '',
                        negara_produsen: headerNegara ? String(headerNegara.value || '') : '',
                        suhu_produk_type: headerSuhuProdukType ? String(headerSuhuProdukType.value || '') : '',
                        suhu_produk: headerSuhuProduk ? String(headerSuhuProduk.value || '') : '',
                        suhu_mobil_type: headerSuhuMobilType ? String(headerSuhuMobilType.value || '') : '',
                        suhu_mobil: headerSuhuMobil ? String(headerSuhuMobil.value || '') : '',
                        kondisi_produk: headerKondisiProduk ? String(headerKondisiProduk.value || '') : '',
                        kondisi_produk_suhu: headerKondisiProdukSuhu ? String(headerKondisiProdukSuhu.value || '') : '',
                        logo_halal: headerLogoHalal ? String(headerLogoHalal.value || '') : '',
                        dokumen_halal: headerDokumenHalal ? String(headerDokumenHalal.value || '') : '',
                        coa: headerCoa ? String(headerCoa.value || '') : '',
                    };

                    const details = Array.from(rowEl.querySelectorAll('.detail-item'));
                    if (!details.length) {
                        details.push(null);
                    }

                    details.forEach(() => {
                        const appendHidden = (name, value) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = name;
                            input.value = value;
                            gen.appendChild(input);
                        };

                        appendHidden('id_bahan[]', header.id_bahan);
                        appendHidden('negara_produsen[]', header.negara_produsen);
                        appendHidden('suhu_produk_type[]', header.suhu_produk_type);
                        appendHidden('suhu_produk[]', header.suhu_produk);
                        appendHidden('suhu_mobil_type[]', header.suhu_mobil_type);
                        appendHidden('suhu_mobil[]', header.suhu_mobil);
                        appendHidden('kondisi_produk[]', header.kondisi_produk);
                        appendHidden('kondisi_produk_suhu[]', header.kondisi_produk_suhu);
                        appendHidden('logo_halal[]', header.logo_halal);
                        appendHidden('dokumen_halal[]', header.dokumen_halal);
                        appendHidden('coa[]', header.coa);

                        (produsenVals || []).forEach((v) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `produsen[${globalIdx}][]`;
                            input.value = String(v);
                            gen.appendChild(input);
                        });

                        (distributorVals || []).forEach((v) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `distributor[${globalIdx}][]`;
                            input.value = String(v);
                            gen.appendChild(input);
                        });

                        globalIdx++;
                    });

                    [
                        headerIdBahan,
                        headerNegara,
                        headerSuhuProdukType,
                        headerSuhuProduk,
                        headerSuhuMobilType,
                        headerSuhuMobil,
                        headerKondisiProduk,
                        headerKondisiProdukSuhu,
                        headerLogoHalal,
                        headerDokumenHalal,
                        headerCoa,
                    ].forEach((el) => {
                        if (el) el.disabled = true;
                    });

                    rowEl.querySelectorAll('.produsen-hidden-inputs input[type="hidden"], .distributor-hidden-inputs input[type="hidden"]').forEach((el) => {
                        el.disabled = true;
                    });
                });
            });
        }
    } catch (err) {
        console.error('Error flattening BBP edit form on submit:', err);
    }
});

</script>
@endpush
@endsection
