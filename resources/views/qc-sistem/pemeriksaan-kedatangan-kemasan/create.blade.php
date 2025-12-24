@extends('layouts.app')
@section('container')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tambah Pemeriksaan Kedatangan Kemasan</h3>
                    <p class="text-subtitle text-muted">Input data pemeriksaan kedatangan kemasan baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}">Pemeriksaan Kedatangan Kemasan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Form Input Pemeriksaan Kedatangan Kemasan</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-kedatangan-kemasan.store') }}" method="POST" enctype="multipart/form-data">
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="jenis_mobil">Jenis Mobil</label>
                                                    <input type="text" id="jenis_mobil" class="form-control @error('jenis_mobil') is-invalid @enderror"
                                                        name="jenis_mobil" value="{{ old('jenis_mobil') }}" placeholder="Jenis Mobil">
                                                    @error('jenis_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="no_mobil">No. Mobil</label>
                                                    <input type="text" id="no_mobil" class="form-control @error('no_mobil') is-invalid @enderror"
                                                        name="no_mobil" value="{{ old('no_mobil') }}" placeholder="No. Mobil">
                                                    @error('no_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nama_supir">Nama Supir</label>
                                                    <input type="text" id="nama_supir" class="form-control @error('nama_supir') is-invalid @enderror"
                                                        name="nama_supir" value="{{ old('nama_supir') }}" placeholder="Nama Supir">
                                                    @error('nama_supir')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="no_po">No. PO</label>
                                                    <input type="text" id="no_po" class="form-control @error('no_po') is-invalid @enderror"
                                                        name="no_po" value="{{ old('no_po') }}" placeholder="No. PO">
                                                    @error('no_po')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
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

                                                <!-- 4. Bebas dari Produk Halal -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>4. Bebas dari Produk Halal</strong></label>
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

                                    <!-- UNIFIED DYNAMIC FORM - Bahan Kemasan, Informasi Kemasan, Kondisi Fisik, Detail Tambahan, Dokumen -->
                                    <!-- DYNAMIC ROWS CONTAINER -->
                                    <div id="unified-container">
                                        <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                            <!-- Bahan Kemasan -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Bahan Kemasan</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Bahan Kemasan</label>
                                                            <select class="choices form-control @error('id_bahan.0') is-invalid @enderror" name="id_bahan[]">
                                                                <option value="">Pilih Bahan</option>
                                                                @foreach($bahans as $bahan)
                                                                    <option value="{{ $bahan->id }}" {{ old('id_bahan.0') == $bahan->id ? 'selected' : '' }}>
                                                                        {{ $bahan->nama_bahan }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('id_bahan.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Informasi Kemasan & Supplier -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Informasi Kemasan & Supplier</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Produsen</label>
                                                            <select class="choices form-control @error('produsen.0') is-invalid @enderror" name="produsen[]">
                                                                <option value="">Pilih Produsen</option>
                                                                @foreach ($produsens as $produsen)
                                                                    <option value="{{ $produsen->nama_produsen }}" {{ old('produsen.0') == $produsen->nama_produsen ? 'selected' : '' }}>
                                                                        {{ $produsen->nama_produsen }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('produsen.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Distributor</label>
                                                            <select class="choices form-control @error('distributor.0') is-invalid @enderror" name="distributor[]">
                                                                <option value="">Pilih Distributor</option>
                                                                @foreach ($distributors as $distributor)
                                                                    <option value="{{ $distributor->nama_distributor }}" {{ old('distributor.0') == $distributor->nama_distributor ? 'selected' : '' }}>
                                                                        {{ $distributor->nama_distributor }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('distributor.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Kode Produksi</label>
                                                            <input type="text" class="form-control @error('kode_produksi.0') is-invalid @enderror"
                                                                name="kode_produksi[]" value="{{ old('kode_produksi.0') }}" placeholder="Kode Produksi">
                                                            @error('kode_produksi.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Jumlah Datang (Kg/pcs/roll)</label>
                                                            <input type="text" class="form-control @error('jumlah_datang.0') is-invalid @enderror"
                                                                name="jumlah_datang[]" value="{{ old('jumlah_datang.0') }}" placeholder="Jumlah Datang">
                                                            @error('jumlah_datang.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Jumlah Sampling (pcs/kg/roll)</label>
                                                            <input type="text" class="form-control @error('jumlah_sampling.0') is-invalid @enderror"
                                                                name="jumlah_sampling[]" value="{{ old('jumlah_sampling.0') }}" placeholder="Jumlah Sampling">
                                                            @error('jumlah_sampling.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Spesifikasi</label>
                                                            <textarea class="form-control @error('spesifikasi.0') is-invalid @enderror"
                                                                name="spesifikasi[]" rows="2" placeholder="Spesifikasi">{{ old('spesifikasi.0') }}</textarea>
                                                            @error('spesifikasi.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kondisi Fisik -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Penampakan</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="penampakan[]" value="1" {{ old('penampakan.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="penampakan[]" value="0" {{ old('penampakan.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Sealing</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sealing[]" value="1" {{ old('sealing.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sealing[]" value="0" {{ old('sealing.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Cetakan</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="cetakan[]" value="1" {{ old('cetakan.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="cetakan[]" value="0" {{ old('cetakan.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detail Tambahan -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Detail Tambahan</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Ketebalan (Micron)</label>
                                                            <input type="number" step="0.01" class="form-control @error('ketebalan_micron.0') is-invalid @enderror"
                                                                name="ketebalan_micron[]" value="{{ old('ketebalan_micron.0') }}" placeholder="Ketebalan">
                                                            @error('ketebalan_micron.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Dimensi</label>
                                                            <input type="text" class="form-control @error('dimensi.0') is-invalid @enderror"
                                                                name="dimensi[]" value="{{ old('dimensi.0') }}" placeholder="Dimensi">
                                                            @error('dimensi.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Status</label>
                                                            <select class="form-control @error('status.0') is-invalid @enderror" name="status[]">
                                                                <option value="">Pilih Status</option>
                                                                <option value="Hold" {{ old('status.0') == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                                <option value="Release" {{ old('status.0') == 'Release' ? 'selected' : '' }}>Release</option>
                                                            </select>
                                                            @error('status.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
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
                                                                <input class="form-check-input" type="radio" name="logo_halal[]" value="1" {{ old('logo_halal.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="logo_halal[]" value="0" {{ old('logo_halal.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal[]" value="1" {{ old('dokumen_halal.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal[]" value="0" {{ old('dokumen_halal.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>COA</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="coa[]" value="1" {{ old('coa.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="coa[]" value="0" {{ old('coa.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Keterangan</label>
                                                            <textarea class="form-control @error('keterangan.0') is-invalid @enderror"
                                                                name="keterangan[]" rows="2" placeholder="Keterangan tambahan">{{ old('keterangan.0') }}</textarea>
                                                            @error('keterangan.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Upload Gambar</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Gambar Kemasan (Max 1MB)</label>
                                                            <input type="file" name="image_kemasan[]" class="form-control image-kemasan-input" accept="image/*" capture="camera">
                                                            @error('image_kemasan.0')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Unified Buttons -->
                                            <div class="row mt-3 pt-3 border-top">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-success btn-sm add-unified-btn"><i class="bi bi-plus"></i> Tambah Baris</button>
                                                    <button type="button" class="btn btn-danger btn-sm remove-unified-btn" style="display: none;"><i class="bi bi-trash"></i> Hapus Baris</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end mt-3">
                                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize Choices.js for all existing selects
    function initializeAllChoices() {
        const selects = document.querySelectorAll('select.choices');
        selects.forEach(select => {
            if (!select.dataset.choicesInitialized) {
                new Choices(select, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Cari...',
                    itemSelectText: 'Tekan untuk memilih',
                    noResultsText: 'Tidak ada hasil ditemukan',
                    noChoicesText: 'Tidak ada pilihan tersedia',
                    placeholder: true,
                    placeholderValue: 'Pilih...'
                });
                select.dataset.choicesInitialized = 'true';
            }
        });
    }
    
    // Initialize on page load
    initializeAllChoices();
    
    // Add unified row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-unified-btn')) {
            const container = document.getElementById('unified-container');
            const newRow = document.createElement('div');
            newRow.className = 'unified-row mb-4 p-3 border rounded';
            newRow.style.backgroundColor = '#f8f9fa';
            
            const timestamp = Date.now();
            newRow.innerHTML = `
                <!-- Bahan Kemasan -->
                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Bahan Kemasan</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Bahan Kemasan</label>
                                <select class="choices form-control" name="id_bahan[]">
                                    <option value="">Pilih Bahan</option>
                                    @foreach($bahans as $bahan)
                                        <option value="{{ $bahan->id }}">{{ $bahan->nama_bahan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Kemasan & Supplier -->
                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Informasi Kemasan & Supplier</h6>
                    <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Kode Produksi</label>
                                <input type="text" class="form-control" name="kode_produksi[]" placeholder="Kode Produksi">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Jumlah Datang (Kg/pcs/roll)</label>
                                <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah Datang">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Jumlah Sampling (pcs/kg/roll)</label>
                                <input type="text" class="form-control" name="jumlah_sampling[]" placeholder="Jumlah Sampling">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Spesifikasi</label>
                                <textarea class="form-control" name="spesifikasi[]" rows="2" placeholder="Spesifikasi"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kondisi Fisik -->
                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>Penampakan</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="penampakan[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="penampakan[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>Sealing</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sealing[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sealing[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>Cetakan</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cetakan[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cetakan[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Tambahan -->
                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Detail Tambahan</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Ketebalan (Micron)</label>
                                <input type="number" step="0.01" class="form-control" name="ketebalan_micron[]" placeholder="Ketebalan">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Dimensi</label>
                                <input type="text" class="form-control" name="dimensi[]" placeholder="Dimensi">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status[]">
                                    <option value="">Pilih Status</option>
                                    <option value="Hold">Hold</option>
                                    <option value="Release">Release</option>
                                </select>
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
                                    <input class="form-check-input" type="radio" name="logo_halal[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="logo_halal[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>Dokumen Halal</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dokumen_halal[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dokumen_halal[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>COA</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coa[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coa[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan[]" rows="2" placeholder="Keterangan tambahan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Upload Gambar</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Gambar Kemasan (Max 1MB)</label>
                                <input type="file" name="image_kemasan[]" class="form-control image-kemasan-input" accept="image/*" capture="camera">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unified Buttons -->
                <div class="row mt-3 pt-3 border-top">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success btn-sm add-unified-btn"><i class="bi bi-plus"></i> Tambah Baris</button>
                        <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Baris</button>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            
            // Initialize Choices.js for new selects ONLY in the new row
            const newSelects = newRow.querySelectorAll('select.choices');
            newSelects.forEach(select => {
                new Choices(select, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Cari...',
                    itemSelectText: 'Tekan untuk memilih',
                    noResultsText: 'Tidak ada hasil ditemukan',
                    noChoicesText: 'Tidak ada pilihan tersedia',
                    placeholder: true,
                    placeholderValue: 'Pilih...'
                });
            });
            
            updateRemoveButtons();
        }
    });
    
    // Remove unified row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-unified-btn')) {
            const rowCount = document.querySelectorAll('#unified-container .unified-row').length;
            if (rowCount > 1) {
                e.target.closest('.unified-row').remove();
                updateRemoveButtons();
            } else {
                alert('Minimal harus ada satu baris data!');
            }
        }
    });
    
    // Update remove buttons visibility
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#unified-container .unified-row');
        rows.forEach((row) => {
            const removeBtn = row.querySelector('.remove-unified-btn');
            if (rows.length > 1) {
                removeBtn.style.display = 'inline-block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }
    
    // Initialize on page load
    updateRemoveButtons();

    const MAX_SIZE = 1024 * 1024;

    function fileToDataURL(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    function loadImage(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = src;
        });
    }

    async function compressImage(file) {
        const dataUrl = await fileToDataURL(file);
        const img = await loadImage(dataUrl);

        const maxDimension = 1920;
        let { width, height } = img;
        if (width > height && width > maxDimension) {
            height = Math.round((height * maxDimension) / width);
            width = maxDimension;
        } else if (height >= width && height > maxDimension) {
            width = Math.round((width * maxDimension) / height);
            height = maxDimension;
        }

        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, width, height);

        let quality = 0.85;
        let blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));
        while (blob && blob.size > MAX_SIZE && quality > 0.4) {
            quality -= 0.1;
            blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));
        }

        const newName = (file.name || 'image')
            .replace(/\.[^/.]+$/, '') + '.jpg';
        return new File([blob], newName, { type: 'image/jpeg', lastModified: Date.now() });
    }

    async function handleImageInputChange(input) {
        const file = input.files && input.files[0] ? input.files[0] : null;
        if (!file) return;

        if (file.size <= MAX_SIZE) return;

        try {
            const compressedFile = await compressImage(file);
            const dt = new DataTransfer();
            dt.items.add(compressedFile);
            input.files = dt.files;
        } catch (e) {
            input.value = '';
            alert('Gagal mengkompres gambar. Silakan coba lagi.');
        }
    }

    document.addEventListener('change', function(e) {
        const input = e.target;
        if (input && input.classList && input.classList.contains('image-kemasan-input')) {
            handleImageInputChange(input);
        }
    });
});
</script>
@endsection