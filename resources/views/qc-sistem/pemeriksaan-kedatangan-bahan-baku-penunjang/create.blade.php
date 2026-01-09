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

                            <form action="{{ route('pemeriksaan-bahan-baku.store') }}" method="POST" enctype="multipart/form-data">
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
                                                <label for="no_po">No. PO</label>
                                                <input type="text" id="no_po" class="form-control @error('no_po') is-invalid @enderror"
                                                    name="no_po" value="{{ old('no_po') }}" placeholder="No. PO">
                                                @error('no_po')
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
                                    <h5 class="text-primary mb-3">Detail Produk (Baris Dinamis)</h5>
                                    <div id="unified-container">
                                        <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                            <h6 class="text-primary mb-3">Baris 1</h6>
                                            
                                            <!-- Informasi Produk -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Nama Bahan</label>
                                                        <select class="choices form-control" name="id_bahan[]">
                                                            <option value="">Pilih Bahan</option>
                                                            @foreach($bahans as $bahan)
                                                                <option value="{{ $bahan->id }}">{{ $bahan->nama_bahan }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Produsen</label>
                                                        <select class="choices form-control" name="produsen[]">
                                                            <option value="">Pilih Produsen</option>
                                                            @foreach ($produsens as $produsen)
                                                                <option value="{{ $produsen->nama_produsen }}">{{ $produsen->nama_produsen }}</option>
                                                            @endforeach
                                                        </select>
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
                                                                <option value="{{ $name }}">{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Distributor</label>
                                                        <select class="choices form-control" name="distributor[]">
                                                            <option value="">Pilih Distributor</option>
                                                            @foreach ($distributors as $distributor)
                                                                <option value="{{ $distributor->nama_distributor }}">{{ $distributor->nama_distributor }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Kode Produksi</label>
                                                        <input type="text" class="form-control" name="kode_produksi[]" placeholder="Kode Produksi">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Expire Date</label>
                                                        <input type="date" class="form-control" name="expire_date[]">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Jumlah Datang (kg)</label>
                                                        <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah Datang">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Jumlah Sampling</label>
                                                        <input type="text" class="form-control" name="jumlah_sampling[]" placeholder="Jumlah Sampling">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Spesifikasi</label>
                                                        <textarea class="form-control" name="spesifikasi[]" rows="2" placeholder="Spesifikasi"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- SUHU PRODUK -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Suhu Produk</label>
                                                        <select class="form-control suhu-produk-type" name="suhu_produk_type[]" id="suhu_produk_type_1">
                                                            <option value="">Pilih Jenis Suhu Produk</option>
                                                            <option value="Fresh">Fresh</option>
                                                            <option value="Frozen">Frozen</option>
                                                            <option value="Tidak Ada">Tidak Ada</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group suhu-produk-input" id="suhu_produk_input_1" style="display: none;">
                                                        <label class="form-label">Nilai Suhu Produk (°C)</label>
                                                        <input type="text" class="form-control" name="suhu_produk[]" id="suhu_produk_val_1" placeholder="Contoh: -18°C atau 4°C">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- SUHU MOBIL -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Suhu Mobil</label>
                                                        <select class="form-control suhu-mobil-type" name="suhu_mobil_type[]" id="suhu_mobil_type_1">
                                                            <option value="">Pilih Jenis Suhu Mobil</option>
                                                            <option value="Fresh">Fresh</option>
                                                            <option value="Frozen">Frozen</option>
                                                            <option value="Tidak Ada">Tidak Ada</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group suhu-mobil-input" id="suhu_mobil_input_1" style="display: none;">
                                                        <label class="form-label">Nilai Suhu Mobil (°C)</label>
                                                        <input type="text" class="form-control" name="suhu_mobil[]" id="suhu_mobil_val_1" placeholder="Contoh: -18°C atau 4°C">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- KONDISI PRODUK -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Kondisi Produk</label>
                                                        <select class="form-control kondisi-produk" name="kondisi_produk[]" id="kondisi_produk_1">
                                                            <option value="">Pilih Kondisi Produk</option>
                                                            <option value="Fresh">Fresh</option>
                                                            <option value="Frozen">Frozen</option>
                                                            <option value="Dry">Dry</option>
                                                            <option value="Minyak">Minyak</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group kondisi-produk-suhu" id="kondisi_produk_suhu_1" style="display: none;">
                                                        <label class="form-label">Suhu Kondisi Produk (°C)</label>
                                                        <input type="text" class="form-control" name="kondisi_produk_suhu[]" id="kondisi_produk_suhu_val_1" placeholder="Suhu Produk">
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
                                                    <div class="col-md-3">
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
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Benda Asing</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_1" id="benda_ya_1" value="1">
                                                                <label class="form-check-label" for="benda_ya_1">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_1" id="benda_tidak_1" value="0">
                                                                <label class="form-check-label" for="benda_tidak_1">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="kondisi_fisik_benda_asing[]" class="radio-value-benda-1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Aroma</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_1" id="aroma_ya_1" value="1">
                                                                <label class="form-check-label" for="aroma_ya_1">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_1" id="aroma_tidak_1" value="0">
                                                                <label class="form-check-label" for="aroma_tidak_1">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="kondisi_fisik_aroma[]" class="radio-value-aroma-1">
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
                                                                <input class="form-check-input" type="radio" name="logo_halal_1" id="logo_ya_1" value="1">
                                                                <label class="form-check-label" for="logo_ya_1">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="logo_halal_1" id="logo_tidak_1" value="0">
                                                                <label class="form-check-label" for="logo_tidak_1">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="logo_halal[]" class="radio-value-logo-1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal_1" id="dokumen_ya_1" value="1">
                                                                <label class="form-check-label" for="dokumen_ya_1">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal_1" id="dokumen_tidak_1" value="0">
                                                                <label class="form-check-label" for="dokumen_tidak_1">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="dokumen_halal[]" class="radio-value-dokumen-1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
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

                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Upload COA (PDF)</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">File COA (PDF)</label>
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
                                                            <input type="text" class="form-control" name="hasil_uji_ffa[]" placeholder="Hasil Uji FFA">
                                                        </div>
                                                    </div>
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
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Keterangan Hasil</label>
                                                            <textarea class="form-control" name="keterangan_hasil[]" rows="2" placeholder="Keterangan hasil pemeriksaan"></textarea>
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

                                <script>
                                // Setup conditional logic and radio listeners for first row
                                document.addEventListener('DOMContentLoaded', function() {
                                    
                                    // Suhu Produk - Row 1
                                    const suhuProdukType1 = document.getElementById('suhu_produk_type_1');
                                    const suhuProdukInput1 = document.getElementById('suhu_produk_input_1');
                                    
                                    if (suhuProdukType1 && suhuProdukInput1) {
                                        suhuProdukType1.addEventListener('change', function() {
                                            if (this.value === 'Fresh' || this.value === 'Frozen') {
                                                suhuProdukInput1.style.display = 'block';
                                            } else {
                                                suhuProdukInput1.style.display = 'none';
                                                document.getElementById('suhu_produk_val_1').value = '';
                                            }
                                        });
                                    }
                                    
                                    // Kondisi Produk - Row 1
                                    const kondisiProduk1 = document.getElementById('kondisi_produk_1');
                                    const kondisiProdukSuhu1 = document.getElementById('kondisi_produk_suhu_1');
                                    
                                    if (kondisiProduk1 && kondisiProdukSuhu1) {
                                        kondisiProduk1.addEventListener('change', function() {
                                            if (this.value === 'Fresh' || this.value === 'Frozen' || this.value === 'Dry' || this.value === 'Minyak') {
                                                kondisiProdukSuhu1.style.display = 'block';
                                            } else {
                                                kondisiProdukSuhu1.style.display = 'none';
                                                document.getElementById('kondisi_produk_suhu_val_1').value = '';
                                            }
                                        });
                                    }
                                    // Suhu Mobil - Row 1
                                    const suhuMobilType1 = document.getElementById('suhu_mobil_type_1');
                                    const suhuMobilInput1 = document.getElementById('suhu_mobil_input_1');
                                    
                                    if (suhuMobilType1 && suhuMobilInput1) {
                                        suhuMobilType1.addEventListener('change', function() {
                                            if (this.value === 'Fresh' || this.value === 'Frozen') {
                                                suhuMobilInput1.style.display = 'block';
                                            } else {
                                                suhuMobilInput1.style.display = 'none';
                                                document.getElementById('suhu_mobil_val_1').value = '';
                                            }
                                        });
                                    }

                                    
                                    // Radio listeners for Row 1
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
                                    
                                    // Kondisi Fisik - Benda Asing
                                    document.querySelectorAll('input[name="kondisi_fisik_benda_asing_1"]').forEach(radio => {
                                        radio.addEventListener('change', function() {
                                            if (this.checked) {
                                                document.querySelector('.radio-value-benda-1').value = this.value;
                                            }
                                        });
                                    });
                                    
                                    // Kondisi Fisik - Aroma
                                    document.querySelectorAll('input[name="kondisi_fisik_aroma_1"]').forEach(radio => {
                                        radio.addEventListener('change', function() {
                                            if (this.checked) {
                                                document.querySelector('.radio-value-aroma-1').value = this.value;
                                            }
                                        });
                                    });
                                    
                                    // Dokumen - Logo Halal
                                    document.querySelectorAll('input[name="logo_halal_1"]').forEach(radio => {
                                        radio.addEventListener('change', function() {
                                            if (this.checked) {
                                                document.querySelector('.radio-value-logo-1').value = this.value;
                                            }
                                        });
                                    });
                                    
                                    // Dokumen - Dokumen Halal
                                    document.querySelectorAll('input[name="dokumen_halal_1"]').forEach(radio => {
                                        radio.addEventListener('change', function() {
                                            if (this.checked) {
                                                document.querySelector('.radio-value-dokumen-1').value = this.value;
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
                                });
                                </script>
                                
                                <div class="col-md-12 d-flex justify-content-end mt-3">
                                    <a href="{{ route('pemeriksaan-bahan-baku.index') }}" class="btn btn-light-secondary me-1 mb-1 btn-kembali-confirm">Kembali</a>
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Simpan Data</button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
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
// Global variable to store select options
let selectOptionsCache = {
    bahan: [],
    produsen: [],
    negara: [],
    distributor: []
};

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
        el.addEventListener('click', function(e) {
            const ok = confirm('Data belum disimpan. Yakin ingin kembali ke halaman index?');
            if (!ok) e.preventDefault();
        });
    });
    
    // 1. Suhu Mobil Conditional Logic
    const suhuMobilTypeEl = document.getElementById('suhu_mobil_type');
    if (suhuMobilTypeEl) {
        suhuMobilTypeEl.addEventListener('change', function() {
            const suhuMobilType = this.value;
            const inputField = document.getElementById('suhu_mobil_input_field');
            
            if (suhuMobilType === 'Fresh' || suhuMobilType === 'Frozen') {
                inputField.style.display = 'block';
            } else {
                inputField.style.display = 'none';
                document.getElementById('suhu_mobil').value = '';
            }
        });
        
        // Trigger on load if value exists
        if (suhuMobilTypeEl.value) {
            suhuMobilTypeEl.dispatchEvent(new Event('change'));
        }
    }

    // 2. Suhu Produk Conditional Logic
    const suhuProdukTypeEl = document.getElementById('suhu_produk_type');
    if (suhuProdukTypeEl) {
        suhuProdukTypeEl.addEventListener('change', function() {
            const suhuProdukType = this.value;
            const inputField = document.getElementById('suhu_produk_input_field');
            
            if (suhuProdukType === 'Fresh' || suhuProdukType === 'Frozen') {
                inputField.style.display = 'block';
            } else {
                inputField.style.display = 'none';
                document.getElementById('suhu_produk').value = '';
            }
        });
        
        // Trigger on load if value exists
        if (suhuProdukTypeEl.value) {
            suhuProdukTypeEl.dispatchEvent(new Event('change'));
        }
    }

    // 3. Kondisi Produk Conditional Logic
    const kondisiProdukEl = document.getElementById('kondisi_produk');
    if (kondisiProdukEl) {
        kondisiProdukEl.addEventListener('change', function() {
            const kondisiProduk = this.value;
            
            const kondisiProdukSuhuField = document.getElementById('kondisi_produk_suhu_field');
            if (kondisiProdukSuhuField) {
                kondisiProdukSuhuField.style.display = 'none';
            }
            
            const kondisiProdukSuhu = document.getElementById('kondisi_produk_suhu');
            if (kondisiProdukSuhu) {
                kondisiProdukSuhu.value = '';
            }
            
            if (kondisiProduk === 'Fresh' || kondisiProduk === 'Frozen' || kondisiProduk === 'Dry') {
                if (kondisiProdukSuhuField) kondisiProdukSuhuField.style.display = 'block';
            } else if (kondisiProduk === 'Minyak') {
                if (kondisiProdukSuhuField) kondisiProdukSuhuField.style.display = 'block';
            }
        });
        
        // Trigger on load if value exists
        if (kondisiProdukEl.value) {
            kondisiProdukEl.dispatchEvent(new Event('change'));
        }
    }

    // Initialize Choices.js for all select elements
    try {
        initializeAllChoices();
        
        // Cache select options AFTER Choices.js initialization
        setTimeout(function() {
            cacheSelectOptions();
        }, 100);
    } catch(err) {
        console.error('Error initializing Choices.js:', err);
    }
    
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
            console.error('Error initializing Choices:', err);
        }
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
    
    // Set the HTML content - TEMPLATE LENGKAP DENGAN SUHU & KONDISI
    newRow.innerHTML = `
        <h6 class="text-primary mb-3">Baris ${rowCount}</h6>
        
        <!-- Informasi Produk -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Nama Bahan</label>
                    <select class="choices form-control" name="id_bahan[]">
                        <option value="">Pilih Bahan</option>
                        @foreach($bahans as $bahan)
                            <option value="{{ $bahan->id }}">{{ $bahan->nama_bahan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Produsen</label>
                    <select class="choices form-control" name="produsen[]">
                        <option value="">Pilih Produsen</option>
                        @foreach ($produsens as $produsen)
                            <option value="{{ $produsen->nama_produsen }}">{{ $produsen->nama_produsen }}</option>
                        @endforeach
                    </select>
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
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Distributor</label>
                    <select class="choices form-control" name="distributor[]">
                        <option value="">Pilih Distributor</option>
                        @foreach ($distributors as $distributor)
                            <option value="{{ $distributor->nama_distributor }}">{{ $distributor->nama_distributor }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Kode Produksi</label>
                    <input type="text" class="form-control" name="kode_produksi[]" placeholder="Kode Produksi">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Expire Date</label>
                    <input type="date" class="form-control" name="expire_date[]">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Jumlah Datang (kg)</label>
                    <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah Datang">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Jumlah Sampling</label>
                    <input type="text" class="form-control" name="jumlah_sampling[]" placeholder="Jumlah Sampling">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Spesifikasi</label>
                    <textarea class="form-control" name="spesifikasi[]" rows="2" placeholder="Spesifikasi"></textarea>
                </div>
            </div>
        </div>
        
        <!-- SUHU PRODUK -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Suhu Produk</label>
                    <select class="form-control suhu-produk-type" name="suhu_produk_type[]" data-row-id="${uniqueId}">
                        <option value="">Pilih Jenis Suhu Produk</option>
                        <option value="Fresh">Fresh</option>
                        <option value="Frozen">Frozen</option>
                        <option value="Tidak Ada">Tidak Ada</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group suhu-produk-input" id="suhu_produk_input_${uniqueId}" style="display: none;">
                    <label class="form-label">Nilai Suhu Produk (°C)</label>
                    <input type="text" class="form-control" name="suhu_produk[]" placeholder="Contoh: -18°C atau 4°C">
                </div>
            </div>
        </div>

        <!-- SUHU MOBIL -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Suhu Mobil</label>
                    <select class="form-control suhu-mobil-type" name="suhu_mobil_type[]" data-row-id="${uniqueId}">
                        <option value="">Pilih Jenis Suhu Mobil</option>
                        <option value="Fresh">Fresh</option>
                        <option value="Frozen">Frozen</option>
                        <option value="Tidak Ada">Tidak Ada</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group suhu-mobil-input" id="suhu_mobil_input_${uniqueId}" style="display: none;">
                    <label class="form-label">Nilai Suhu Mobil (°C)</label>
                    <input type="text" class="form-control" name="suhu_mobil[]" placeholder="Contoh: -18°C atau 4°C">
                </div>
            </div>
        </div>
        
        <!-- KONDISI PRODUK -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Kondisi Produk</label>
                    <select class="form-control kondisi-produk" name="kondisi_produk[]" data-row-id="${uniqueId}">
                        <option value="">Pilih Kondisi Produk</option>
                        <option value="Fresh">Fresh</option>
                        <option value="Frozen">Frozen</option>
                        <option value="Dry">Dry</option>
                        <option value="Minyak">Minyak</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group kondisi-produk-suhu" id="kondisi_produk_suhu_${uniqueId}" style="display: none;">
                    <label class="form-label">Suhu Kondisi Produk (°C)</label>
                    <input type="text" class="form-control" name="kondisi_produk_suhu[]" placeholder="Suhu Produk">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><strong>Benda Asing</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="kondisi_fisik_benda_asing[]" class="radio-value-benda-${uniqueId}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><strong>Aroma</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="kondisi_fisik_aroma[]" class="radio-value-aroma-${uniqueId}">
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
                            <input class="form-check-input" type="radio" name="logo_halal_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="logo_halal_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="logo_halal[]" class="radio-value-logo-${uniqueId}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label"><strong>Dokumen Halal</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dokumen_halal_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dokumen_halal_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="dokumen_halal[]" class="radio-value-dokumen-${uniqueId}">
                    </div>
                </div>
                <div class="col-md-4">
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

        <div class="form-section mb-3">
            <h6 class="text-primary mb-2">Upload COA (PDF)</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">File COA (PDF)</label>
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
                        <input type="text" class="form-control" name="hasil_uji_ffa[]" placeholder="Hasil Uji FFA">
                    </div>
                </div>
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
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Keterangan Hasil</label>
                        <textarea class="form-control" name="keterangan_hasil[]" rows="2" placeholder="Keterangan hasil pemeriksaan"></textarea>
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
    
    // Setup conditional logic for Suhu Produk in new row
    setupSuhuProdukLogic(newRow, uniqueId);
    
    // Setup conditional logic for Suhu Mobil in new row
    setupSuhuMobilLogic(newRow, uniqueId);
    
    // Setup conditional logic for Kondisi Produk in new row
    setupKondisiProdukLogic(newRow, uniqueId);
    
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

// Setup Suhu Produk conditional logic for dynamic rows
function setupSuhuProdukLogic(row, uniqueId) {
    const suhuProdukSelect = row.querySelector('.suhu-produk-type');
    const suhuProdukInput = row.querySelector(`#suhu_produk_input_${uniqueId}`);
    
    if (suhuProdukSelect && suhuProdukInput) {
        suhuProdukSelect.addEventListener('change', function() {
            const value = this.value;
            if (value === 'Fresh' || value === 'Frozen') {
                suhuProdukInput.style.display = 'block';
            } else {
                suhuProdukInput.style.display = 'none';
                const input = suhuProdukInput.querySelector('input');
                if (input) input.value = '';
            }
        });
    }
}

// Setup Suhu Mobil conditional logic for dynamic rows
function setupSuhuMobilLogic(row, uniqueId) {
    const suhuMobilSelect = row.querySelector('.suhu-mobil-type');
    const suhuMobilInput = row.querySelector(`#suhu_mobil_input_${uniqueId}`);
    
    if (suhuMobilSelect && suhuMobilInput) {
        suhuMobilSelect.addEventListener('change', function() {
            const value = this.value;
            if (value === 'Fresh' || value === 'Frozen') {
                suhuMobilInput.style.display = 'block';
            } else {
                suhuMobilInput.style.display = 'none';
                const input = suhuMobilInput.querySelector('input');
                if (input) input.value = '';
            }
        });
    }
}

function setupKondisiProdukLogic(row, uniqueId) {
    const kondisiProdukSelect = row.querySelector('.kondisi-produk');
    const kondisiProdukSuhu = row.querySelector(`#kondisi_produk_suhu_${uniqueId}`);
    
    if (kondisiProdukSelect && kondisiProdukSuhu) {
        kondisiProdukSelect.addEventListener('change', function() {
            const value = this.value;
            if (value === 'Fresh' || value === 'Frozen' || value === 'Dry' || value === 'Minyak') {
                kondisiProdukSuhu.style.display = 'block';
            } else {
                kondisiProdukSuhu.style.display = 'none';
                const input = kondisiProdukSuhu.querySelector('input');
                if (input) input.value = '';
            }
        });
    }
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
    
    // Kondisi Fisik - Benda Asing
    row.querySelectorAll(`input[name="kondisi_fisik_benda_asing_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-benda-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Kondisi Fisik - Aroma
    row.querySelectorAll(`input[name="kondisi_fisik_aroma_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-aroma-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Dokumen - Logo Halal
    row.querySelectorAll(`input[name="logo_halal_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-logo-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Dokumen - Dokumen Halal
    row.querySelectorAll(`input[name="dokumen_halal_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-dokumen-${uniqueId}`).value = this.value;
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

// Cache select options from first row using Choices instance
function cacheSelectOptions() {
    const container = document.getElementById('unified-container');
    const firstRow = container.querySelector('.unified-row');
    
    if (!firstRow) return;
    
    // Cache Bahan options from Choices instance
    const bahanSelect = firstRow.querySelector('select[name="id_bahan[]"]');
    if (bahanSelect && bahanSelect.choicesInstance) {
        selectOptionsCache.bahan = bahanSelect.choicesInstance.config.choices
            .map(opt => ({ value: opt.value, text: opt.label }));
    }
    
    // Cache Produsen options from Choices instance
    const produsenSelect = firstRow.querySelector('select[name="produsen[]"]');
    if (produsenSelect && produsenSelect.choicesInstance) {
        selectOptionsCache.produsen = produsenSelect.choicesInstance.config.choices
            .map(opt => ({ value: opt.value, text: opt.label }));
    }
    
    // Cache Negara options from Choices instance
    const negaraSelect = firstRow.querySelector('select[name="negara_produsen[]"]');
    if (negaraSelect && negaraSelect.choicesInstance) {
        selectOptionsCache.negara = negaraSelect.choicesInstance.config.choices
            .map(opt => ({ value: opt.value, text: opt.label }));
    }
    
    // Cache Distributor options from Choices instance
    const distributorSelect = firstRow.querySelector('select[name="distributor[]"]');
    if (distributorSelect && distributorSelect.choicesInstance) {
        selectOptionsCache.distributor = distributorSelect.choicesInstance.config.choices
            .map(opt => ({ value: opt.value, text: opt.label }));
    }
}

// Helper functions to get options from cache
function getBahanOptions() {
    return selectOptionsCache.bahan
        .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
        .join('');
}

function getProdusenOptions() {
    return selectOptionsCache.produsen
        .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
        .join('');
}

function getCountryOptions() {
    return selectOptionsCache.negara
        .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
        .join('');
}

function getDistributorOptions() {
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
@endsection
