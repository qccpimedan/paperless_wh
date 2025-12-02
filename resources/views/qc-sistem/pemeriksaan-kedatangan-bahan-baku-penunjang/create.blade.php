@extends('layouts.app')

@section('title', 'Tambah Pemeriksaan Kedatangan Bahan Baku Penunjang')

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
                        <h3>Tambah Pemeriksaan Kedatangan Bahan Baku Penunjang</h3>
                        <p class="text-subtitle text-muted">Form untuk menambah data pemeriksaan kedatangan bahan baku penunjang</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-bahan-baku.index') }}">Pemeriksaan Kedatangan Bahan Baku Penunjang</a></li>
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
                            <h4 class="card-title">Form Pemeriksaan Kedatangan Bahan Baku Penunjang</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pemeriksaan-bahan-baku.store') }}" method="POST">
                                @csrf
                                
                                <!-- Informasi Dasar -->
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
                                
                                <!-- Kondisi Mobil Pengangkut -->
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
        
                                <!-- Informasi Produk -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Informasi Produk</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_po">No. PO</label>
                                                <input type="text" id="no_po" class="form-control @error('no_po') is-invalid @enderror"
                                                    name="no_po" value="{{ old('no_po') }}" placeholder="No. PO">
                                                @error('no_po')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_bahan">Nama Bahan</label>
                                                <select id="id_bahan" class="form-control @error('id_bahan') is-invalid @enderror" name="id_bahan">
                                                    <option value="">Pilih Bahan</option>
                                                    @foreach($bahans as $bahan)
                                                        <option value="{{ $bahan->id }}" {{ old('id_bahan') == $bahan->id ? 'selected' : '' }}>
                                                            {{ $bahan->nama_bahan }}
                                                            @if($bahan->user && $bahan->user->plant)
                                                                - {{ $bahan->user->plant->plant }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_bahan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Row untuk Suhu Mobil -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="suhu_mobil_type">Suhu Mobil</label>
                                                <select id="suhu_mobil_type" class="form-control @error('suhu_mobil_type') is-invalid @enderror" name="suhu_mobil_type">
                                                    <option value="">Pilih Jenis Suhu Mobil</option>
                                                    <option value="Fresh" {{ old('suhu_mobil_type') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                    <option value="Frozen" {{ old('suhu_mobil_type') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                </select>
                                                @error('suhu_mobil_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Input Suhu Mobil - Conditional -->
                                            <div class="form-group" id="suhu_mobil_input_field" style="display: none;">
                                                <label for="suhu_mobil">Nilai Suhu Mobil (°C)</label>
                                                <input type="text" id="suhu_mobil" class="form-control @error('suhu_mobil') is-invalid @enderror"
                                                    name="suhu_mobil" value="{{ old('suhu_mobil') }}" placeholder="Contoh: -18°C atau 4°C">
                                                @error('suhu_mobil')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Row untuk Suhu Produk -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="suhu_produk_type">Suhu Produk</label>
                                                <select id="suhu_produk_type" class="form-control @error('suhu_produk_type') is-invalid @enderror" name="suhu_produk_type">
                                                    <option value="">Pilih Jenis Suhu Produk</option>
                                                    <option value="Fresh" {{ old('suhu_produk_type') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                    <option value="Frozen" {{ old('suhu_produk_type') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                </select>
                                                @error('suhu_produk_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Input Suhu Produk - Conditional -->
                                            <div class="form-group" id="suhu_produk_input_field" style="display: none;">
                                                <label for="suhu_produk">Nilai Suhu Produk (°C)</label>
                                                <input type="text" id="suhu_produk" class="form-control @error('suhu_produk') is-invalid @enderror"
                                                    name="suhu_produk" value="{{ old('suhu_produk') }}" placeholder="Contoh: -18°C atau 4°C">
                                                @error('suhu_produk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Row untuk Kondisi Produk -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kondisi_produk">Kondisi Produk</label>
                                                <select id="kondisi_produk" class="form-control @error('kondisi_produk') is-invalid @enderror" name="kondisi_produk">
                                                    <option value="">Pilih Kondisi Produk</option>
                                                    <option value="Fresh" {{ old('kondisi_produk') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                    <option value="Frozen" {{ old('kondisi_produk') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                    <option value="Dry" {{ old('kondisi_produk') == 'Dry' ? 'selected' : '' }}>Dry</option>
                                                    <option value="Minyak" {{ old('kondisi_produk') == 'Minyak' ? 'selected' : '' }}>Minyak</option>
                                                </select>
                                                @error('kondisi_produk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Input Suhu Kondisi Produk - Conditional (Opsional) -->
                                            <div class="form-group" id="kondisi_produk_suhu_field" style="display: none;">
                                                <label for="kondisi_produk_suhu">Suhu Kondisi Produk (°C) <small class="text-muted">(Opsional)</small></label>
                                                <input type="text" id="kondisi_produk_suhu" class="form-control @error('kondisi_produk_suhu') is-invalid @enderror"
                                                    name="kondisi_produk_suhu" value="{{ old('kondisi_produk_suhu') }}" placeholder="Contoh: -18°C, 4°C, 25°C">
                                                @error('kondisi_produk_suhu')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <!-- Detail Pemeriksaan -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Detail Pemeriksaan</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="spesifikasi">Spesifikasi</label>
                                                <textarea id="spesifikasi" class="form-control @error('spesifikasi') is-invalid @enderror"
                                                    name="spesifikasi" rows="3" placeholder="Spesifikasi">{{ old('spesifikasi') }}</textarea>
                                                @error('spesifikasi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="produsen">Produsen</label>
                                                <select id="produsen" class="choices form-control @error('produsen') is-invalid @enderror" name="produsen">
                                                    <option value="">Pilih Produsen</option>
                                                    @foreach ($produsens as $produsen)
                                                        <option value="{{ $produsen->nama_produsen }}" {{ old('produsen') == $produsen->nama_produsen ? 'selected' : '' }}>
                                                            {{ $produsen->nama_produsen }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('produsen')
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
                                                        <option value="{{ $name }}" {{ old('negara_produsen') == $name ? 'selected' : '' }}>{{ $name }}</option>
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
                                                <label for="distributor">Distributor</label>
                                                <select id="distributor" class="choices form-control @error('distributor') is-invalid @enderror" name="distributor">
                                                    <option value="">Pilih Distributor</option>
                                                    @foreach ($distributors as $distributor)
                                                        <option value="{{ $distributor->nama_distributor }}" {{ old('distributor') == $distributor->nama_distributor ? 'selected' : '' }}>
                                                            {{ $distributor->nama_distributor }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('distributor')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kode_produksi">Kode Produksi</label>
                                                <input type="text" id="kode_produksi" class="form-control @error('kode_produksi') is-invalid @enderror"
                                                    name="kode_produksi" value="{{ old('kode_produksi') }}" placeholder="Kode Produksi">
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
                                                    name="expire_date" value="{{ old('expire_date') }}">
                                                @error('expire_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="jumlah_datang">Jumlah Barang Datang (kg)</label>
                                                <input type="text" id="jumlah_datang" class="form-control @error('jumlah_datang') is-invalid @enderror"
                                                    name="jumlah_datang" value="{{ old('jumlah_datang') }}" placeholder="Jumlah Barang Datang">
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
                                                    name="jumlah_sampling" value="{{ old('jumlah_sampling') }}" placeholder="Jumlah Sampling">
                                                @error('jumlah_sampling')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <!-- Kondisi Fisik -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Kondisi Fisik</h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Kemasan</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[kemasan]" id="kemasan_ya" value="1" {{ old('kondisi_fisik.kemasan') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="kemasan_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[kemasan]" id="kemasan_tidak" value="0" {{ old('kondisi_fisik.kemasan') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="kemasan_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Warna</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[warna]" id="warna_ya" value="1" {{ old('kondisi_fisik.warna') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="warna_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[warna]" id="warna_tidak" value="0" {{ old('kondisi_fisik.warna') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="warna_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Benda Asing/Kotoran</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[benda_asing]" id="benda_asing_ya" value="1" {{ old('kondisi_fisik.benda_asing') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="benda_asing_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[benda_asing]" id="benda_asing_tidak" value="0" {{ old('kondisi_fisik.benda_asing') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="benda_asing_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Aroma</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[aroma]" id="aroma_ya" value="1" {{ old('kondisi_fisik.aroma') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="aroma_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_fisik[aroma]" id="aroma_tidak" value="0" {{ old('kondisi_fisik.aroma') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="aroma_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <!-- Dokumen & Sertifikasi -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Dokumen & Sertifikasi</h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Logo Halal</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="logo_halal" id="logo_halal_ya" value="1" {{ old('logo_halal') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="logo_halal_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="logo_halal" id="logo_halal_tidak" value="0" {{ old('logo_halal') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="logo_halal_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="dokumen_halal" id="dokumen_halal_ya" value="1" {{ old('dokumen_halal') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="dokumen_halal_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="dokumen_halal" id="dokumen_halal_tidak" value="0" {{ old('dokumen_halal') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="dokumen_halal_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label"><strong>COA</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="coa" id="coa_ya" value="1" {{ old('coa') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="coa_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="coa" id="coa_tidak" value="0" {{ old('coa') == '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="coa_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <!-- Hasil Pemeriksaan -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Hasil Pemeriksaan</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Status <span class="text-danger">*</span></label>
                                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                                                    <option value="">Pilih Status</option>
                                                    <option value="Hold" {{ old('status') == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                    <option value="Release" {{ old('status') == 'Release' ? 'selected' : '' }}>Release</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="hasil_uji_ffa">Hasil Uji FFA</label>
                                                <input type="text" id="hasil_uji_ffa" class="form-control @error('hasil_uji_ffa') is-invalid @enderror"
                                                    name="hasil_uji_ffa" value="{{ old('hasil_uji_ffa') }}" placeholder="Hasil Uji FFA">
                                                @error('hasil_uji_ffa')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                                    name="keterangan" rows="3" placeholder="Keterangan">{{ old('keterangan') }}</textarea>
                                                @error('keterangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('pemeriksaan-bahan-baku.index') }}" class="btn btn-secondary">Batal</a>
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
// 1. Suhu Mobil Conditional Logic
document.getElementById('suhu_mobil_type').addEventListener('change', function() {
    const suhuMobilType = this.value;
    const inputField = document.getElementById('suhu_mobil_input_field');
    
    if (suhuMobilType === 'Fresh' || suhuMobilType === 'Frozen') {
        inputField.style.display = 'block';
    } else {
        inputField.style.display = 'none';
        document.getElementById('suhu_mobil').value = ''; // Clear input
    }
});

// 2. Suhu Produk Conditional Logic
document.getElementById('suhu_produk_type').addEventListener('change', function() {
    const suhuProdukType = this.value;
    const inputField = document.getElementById('suhu_produk_input_field');
    
    if (suhuProdukType === 'Fresh' || suhuProdukType === 'Frozen') {
        inputField.style.display = 'block';
    } else {
        inputField.style.display = 'none';
        document.getElementById('suhu_produk').value = ''; // Clear input
    }
});

// 3. Kondisi Produk Conditional Logic
document.getElementById('kondisi_produk').addEventListener('change', function() {
    const kondisiProduk = this.value;
    
    // Hide all conditional fields first
    document.getElementById('kondisi_produk_suhu_field').style.display = 'none';
    // document.getElementById('hasil_uji_ffa_field').style.display = 'none';
    
    // Clear all inputs
    document.getElementById('kondisi_produk_suhu').value = '';
    // document.getElementById('hasil_uji_ffa').value = '';
    
    // Show relevant fields based on selection
    if (kondisiProduk === 'Fresh' || kondisiProduk === 'Frozen' || kondisiProduk === 'Dry') {
        document.getElementById('kondisi_produk_suhu_field').style.display = 'block';
    } else if (kondisiProduk === 'Minyak') {
        document.getElementById('kondisi_produk_suhu_field').style.display = 'block';
        // document.getElementById('hasil_uji_ffa_field').style.display = 'block';
    }
});

// Trigger on page load if old values exist
document.addEventListener('DOMContentLoaded', function() {
    // Check Suhu Mobil
    const suhuMobilType = document.getElementById('suhu_mobil_type').value;
    if (suhuMobilType) {
        document.getElementById('suhu_mobil_type').dispatchEvent(new Event('change'));
    }
    
    // Check Suhu Produk
    const suhuProdukType = document.getElementById('suhu_produk_type').value;
    if (suhuProdukType) {
        document.getElementById('suhu_produk_type').dispatchEvent(new Event('change'));
    }
    
    // Check Kondisi Produk
    const kondisiProduk = document.getElementById('kondisi_produk').value;
    if (kondisiProduk) {
        document.getElementById('kondisi_produk').dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection