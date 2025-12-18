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
                    <h3>Edit Pemeriksaan Kedatangan Kemasan</h3>
                    <p class="text-subtitle text-muted">Edit data pemeriksaan kedatangan kemasan</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}">Pemeriksaan Kedatangan Kemasan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                            <h4 class="card-title">Form Edit Pemeriksaan Kedatangan Kemasan</h4>
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

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-kedatangan-kemasan.update', $pemeriksaanKedatanganKemasan->uuid) }}" method="POST">
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
                                                        name="tanggal" value="{{ old('tanggal', $pemeriksaanKedatanganKemasan->tanggal->format('Y-m-d')) }}" required>
                                                    @error('tanggal')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="no_po">No. PO</label>
                                                    <input type="text" id="no_po" class="form-control @error('no_po') is-invalid @enderror"
                                                        name="no_po" value="{{ old('no_po', $pemeriksaanKedatanganKemasan->no_po) }}" placeholder="No. PO">
                                                    @error('no_po')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status">Status <span class="text-danger">*</span></label>
                                                    <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                                                        <option value="">Pilih Status</option>
                                                        <option value="Hold" {{ old('status', $pemeriksaanKedatanganKemasan->status) == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                        <option value="Release" {{ old('status', $pemeriksaanKedatanganKemasan->status) == 'Release' ? 'selected' : '' }}>Release</option>
                                                    </select>
                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_shift">Shift</label>
                                                    <select id="id_shift" class="form-control @error('id_shift') is-invalid @enderror" name="id_shift">
                                                        <option value="">Pilih Shift </option>
                                                        @foreach($shifts as $shift)
                                                            <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanKedatanganKemasan->id_shift) == $shift->id ? 'selected' : '' }}>
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="jenis_mobil">Jenis Mobil</label>
                                                    <input type="text" id="jenis_mobil" class="form-control @error('jenis_mobil') is-invalid @enderror"
                                                        name="jenis_mobil" value="{{ old('jenis_mobil', $pemeriksaanKedatanganKemasan->jenis_mobil) }}" placeholder="Jenis Mobil">
                                                    @error('jenis_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="no_mobil">No. Mobil</label>
                                                    <input type="text" id="no_mobil" class="form-control @error('no_mobil') is-invalid @enderror"
                                                        name="no_mobil" value="{{ old('no_mobil', $pemeriksaanKedatanganKemasan->no_mobil) }}" placeholder="No. Mobil">
                                                    @error('no_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nama_supir">Nama Supir</label>
                                                    <input type="text" id="nama_supir" class="form-control @error('nama_supir') is-invalid @enderror"
                                                        name="nama_supir" value="{{ old('nama_supir', $pemeriksaanKedatanganKemasan->nama_supir) }}" placeholder="Nama Supir">
                                                    @error('nama_supir')
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
                                                        <input class="form-check-input" type="radio" id="segel_option" name="segel_gembok" value="segel" {{ old('segel_gembok', $pemeriksaanKedatanganKemasan->segel_gembok) == 'segel' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="segel_option">
                                                            Segel
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="gembok_option" name="segel_gembok" value="gembok" {{ old('segel_gembok', $pemeriksaanKedatanganKemasan->segel_gembok) == 'gembok' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="gembok_option">
                                                            Gembok
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="no_segel_container" style="display: {{ old('segel_gembok', $pemeriksaanKedatanganKemasan->segel_gembok) == 'segel' ? 'block' : 'none' }};">
                                                <div class="form-group">
                                                    <label for="no_segel">No. Segel</label>
                                                    <input type="text" id="no_segel" class="form-control @error('no_segel') is-invalid @enderror"
                                                        name="no_segel" value="{{ old('no_segel', $pemeriksaanKedatanganKemasan->no_segel) }}" placeholder="No. Segel">
                                                    @error('no_segel')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                    </div>

                                    <!-- Kondisi Mobil Pengangkut -->
                                    <div class="form-section mb-4">
                                        <h5 class="text-primary mb-3">Kondisi Mobil Pengangkut</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Bersih</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_ya" value="1" {{ (old('kondisi_mobil.bersih', $pemeriksaanKedatanganKemasan->kondisi_mobil['bersih'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bersih_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_tidak" value="0" {{ !(old('kondisi_mobil.bersih', $pemeriksaanKedatanganKemasan->kondisi_mobil['bersih'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bersih_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Bebas dari Hama</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_ya" value="1" {{ (old('kondisi_mobil.bebas_hama', $pemeriksaanKedatanganKemasan->kondisi_mobil['bebas_hama'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_hama_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_tidak" value="0" {{ !(old('kondisi_mobil.bebas_hama', $pemeriksaanKedatanganKemasan->kondisi_mobil['bebas_hama'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_hama_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Tidak Kondensasi</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_ya" value="1" {{ (old('kondisi_mobil.tidak_kondensasi', $pemeriksaanKedatanganKemasan->kondisi_mobil['tidak_kondensasi'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_kondensasi_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_tidak" value="0" {{ !(old('kondisi_mobil.tidak_kondensasi', $pemeriksaanKedatanganKemasan->kondisi_mobil['tidak_kondensasi'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_kondensasi_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Bebas dari Produk Halal</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_ya" value="1" {{ (old('kondisi_mobil.bebas_produk_halal', $pemeriksaanKedatanganKemasan->kondisi_mobil['bebas_produk_halal'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_produk_halal_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_tidak" value="0" {{ !(old('kondisi_mobil.bebas_produk_halal', $pemeriksaanKedatanganKemasan->kondisi_mobil['bebas_produk_halal'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_produk_halal_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Tidak Berbau</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_ya" value="1" {{ (old('kondisi_mobil.tidak_berbau', $pemeriksaanKedatanganKemasan->kondisi_mobil['tidak_berbau'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_berbau_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_tidak" value="0" {{ !(old('kondisi_mobil.tidak_berbau', $pemeriksaanKedatanganKemasan->kondisi_mobil['tidak_berbau'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_berbau_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Tidak ada Sampah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_ya" value="1" {{ (old('kondisi_mobil.tidak_ada_sampah', $pemeriksaanKedatanganKemasan->kondisi_mobil['tidak_ada_sampah'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_ada_sampah_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_tidak" value="0" {{ !(old('kondisi_mobil.tidak_ada_sampah', $pemeriksaanKedatanganKemasan->kondisi_mobil['tidak_ada_sampah'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_ada_sampah_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Tidak ada Mikroba</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_ya" value="1" {{ (old('kondisi_mobil.tidak_ada_mikroba', $pemeriksaanKedatanganKemasan->kondisi_mobil['tidak_ada_mikroba'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_ada_mikroba_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_tidak" value="0" {{ !(old('kondisi_mobil.tidak_ada_mikroba', $pemeriksaanKedatanganKemasan->kondisi_mobil['tidak_ada_mikroba'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_ada_mikroba_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Lampu Cover utuh</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_ya" value="1" {{ (old('kondisi_mobil.lampu_cover_utuh', $pemeriksaanKedatanganKemasan->kondisi_mobil['lampu_cover_utuh'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="lampu_cover_utuh_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_tidak" value="0" {{ !(old('kondisi_mobil.lampu_cover_utuh', $pemeriksaanKedatanganKemasan->kondisi_mobil['lampu_cover_utuh'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="lampu_cover_utuh_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Pallet utuh</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_ya" value="1" {{ (old('kondisi_mobil.pallet_utuh', $pemeriksaanKedatanganKemasan->kondisi_mobil['pallet_utuh'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pallet_utuh_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_tidak" value="0" {{ !(old('kondisi_mobil.pallet_utuh', $pemeriksaanKedatanganKemasan->kondisi_mobil['pallet_utuh'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pallet_utuh_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Tertutup rapat</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_ya" value="1" {{ (old('kondisi_mobil.tertutup_rapat', $pemeriksaanKedatanganKemasan->kondisi_mobil['tertutup_rapat'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tertutup_rapat_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_tidak" value="0" {{ !(old('kondisi_mobil.tertutup_rapat', $pemeriksaanKedatanganKemasan->kondisi_mobil['tertutup_rapat'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tertutup_rapat_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Bebas kontaminan</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_ya" value="1" {{ (old('kondisi_mobil.bebas_kontaminan', $pemeriksaanKedatanganKemasan->kondisi_mobil['bebas_kontaminan'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_kontaminan_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_tidak" value="0" {{ !(old('kondisi_mobil.bebas_kontaminan', $pemeriksaanKedatanganKemasan->kondisi_mobil['bebas_kontaminan'] ?? false)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_kontaminan_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Informasi Kemasan -->
                                    <div class="form-section mb-4">
                                        <h5 class="text-primary mb-3">Informasi Kemasan</h5>
                                        <div class="row">
                                            
                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_bahan">Bahan Terkait</label>
                                                    <select id="id_bahan" class="choices form-control @error('id_bahan') is-invalid @enderror" name="id_bahan">
                                                        <option value="">Pilih Bahan (Opsional)</option>
                                                        @foreach($bahans as $bahan)
                                                            <option value="{{ $bahan->id }}" {{ old('id_bahan', $pemeriksaanKedatanganKemasan->id_bahan) == $bahan->id ? 'selected' : '' }}>
                                                                {{ $bahan->nama_bahan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_bahan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div> -->
                                        </div>

                                        <!-- <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="produsen">Produsen</label>
                                                    <select id="produsen" class="choices form-control @error('produsen') is-invalid @enderror" name="produsen">
                                                        <option value="">Pilih Produsen</option>
                                                        @foreach ($produsens as $produsen)
                                                            <option value="{{ $produsen->nama_produsen }}" {{ old('produsen', $pemeriksaanKedatanganKemasan->produsen) == $produsen->nama_produsen ? 'selected' : '' }}>
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
                                                    <label for="distributor">Distributor</label>
                                                    <select id="distributor" class="choices form-control @error('distributor') is-invalid @enderror" name="distributor">
                                                        <option value="">Pilih Distributor</option>
                                                        @foreach ($distributors as $distributor)
                                                            <option value="{{ $distributor->nama_distributor }}" {{ old('distributor', $pemeriksaanKedatanganKemasan->distributor) == $distributor->nama_distributor ? 'selected' : '' }}>
                                                                {{ $distributor->nama_distributor }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('distributor')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                        </div> -->
                                    </div>

                                    <!-- Dynamic Rows Data -->
                                    @php
                                        $id_bahans = json_decode($pemeriksaanKedatanganKemasan->id_bahan_array ?? '[]', true) ?? [];
                                        $produsens_arr = json_decode($pemeriksaanKedatanganKemasan->produsen_array ?? '[]', true) ?? [];
                                        $distributors_arr = json_decode($pemeriksaanKedatanganKemasan->distributor_array ?? '[]', true) ?? [];
                                        $kode_produksis = json_decode($pemeriksaanKedatanganKemasan->kode_produksi_array ?? '[]', true) ?? [];
                                        $jumlah_datangs = json_decode($pemeriksaanKedatanganKemasan->jumlah_datang_array ?? '[]', true) ?? [];
                                        $jumlah_samplings = json_decode($pemeriksaanKedatanganKemasan->jumlah_sampling_array ?? '[]', true) ?? [];
                                        $spesifikasis = json_decode($pemeriksaanKedatanganKemasan->spesifikasi_array ?? '[]', true) ?? [];
                                        $penampakans = json_decode($pemeriksaanKedatanganKemasan->penampakan_array ?? '[]', true) ?? [];
                                        $sealings = json_decode($pemeriksaanKedatanganKemasan->sealing_array ?? '[]', true) ?? [];
                                        $cetakans = json_decode($pemeriksaanKedatanganKemasan->cetakan_array ?? '[]', true) ?? [];
                                        $ketebalan_microns = json_decode($pemeriksaanKedatanganKemasan->ketebalan_micron_array ?? '[]', true) ?? [];
                                        $dimensis = json_decode($pemeriksaanKedatanganKemasan->dimensi_array ?? '[]', true) ?? [];
                                        $statuses = json_decode($pemeriksaanKedatanganKemasan->status_array ?? '[]', true) ?? [];
                                        $logo_halals = json_decode($pemeriksaanKedatanganKemasan->logo_halal_array ?? '[]', true) ?? [];
                                        $dokumen_halals = json_decode($pemeriksaanKedatanganKemasan->dokumen_halal_array ?? '[]', true) ?? [];
                                        $coas = json_decode($pemeriksaanKedatanganKemasan->coa_array ?? '[]', true) ?? [];
                                        $keterangans = json_decode($pemeriksaanKedatanganKemasan->keterangan_array ?? '[]', true) ?? [];
                                        $rowCount = max(count($id_bahans), count($produsens_arr), count($distributors_arr));
                                    @endphp
                                    <div id="unified-container">
                                        @forelse($id_bahans as $index => $id_bahan)
                                            <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                                <h6 class="text-primary mb-3">Baris {{ $index + 1 }}</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Bahan Kemasan</label>
                                                            <select class="choices form-control" name="id_bahan[]">
                                                                <option value="">Pilih Bahan</option>
                                                                @foreach($bahans as $bahan)
                                                                    <option value="{{ $bahan->id }}" {{ $id_bahan == $bahan->id ? 'selected' : '' }}>{{ $bahan->nama_bahan }}</option>
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
                                                                    <option value="{{ $produsen->nama_produsen }}" {{ ($produsens_arr[$index] ?? '') == $produsen->nama_produsen ? 'selected' : '' }}>{{ $produsen->nama_produsen }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Distributor</label>
                                                            <select class="choices form-control" name="distributor[]">
                                                                <option value="">Pilih Distributor</option>
                                                                @foreach ($distributors as $distributor)
                                                                    <option value="{{ $distributor->nama_distributor }}" {{ ($distributors_arr[$index] ?? '') == $distributor->nama_distributor ? 'selected' : '' }}>{{ $distributor->nama_distributor }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Kode Produksi</label>
                                                            <input type="text" class="form-control" name="kode_produksi[]" value="{{ $kode_produksis[$index] ?? '' }}" placeholder="Kode Produksi">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Jumlah Datang</label>
                                                            <input type="text" class="form-control" name="jumlah_datang[]" value="{{ $jumlah_datangs[$index] ?? '' }}" placeholder="Jumlah Datang">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Jumlah Sampling</label>
                                                            <input type="text" class="form-control" name="jumlah_sampling[]" value="{{ $jumlah_samplings[$index] ?? '' }}" placeholder="Jumlah Sampling">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Spesifikasi</label>
                                                            <textarea class="form-control" name="spesifikasi[]" rows="2" placeholder="Spesifikasi">{{ $spesifikasis[$index] ?? '' }}</textarea>
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
                                                                    <input class="form-check-input" type="radio" name="penampakan[]" value="1" {{ ($penampakans[$index] ?? null) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="penampakan[]" value="0" {{ !($penampakans[$index] ?? null) && isset($penampakans[$index]) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>Sealing</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="sealing[]" value="1" {{ ($sealings[$index] ?? null) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="sealing[]" value="0" {{ !($sealings[$index] ?? null) && isset($sealings[$index]) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>Cetakan</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="cetakan[]" value="1" {{ ($cetakans[$index] ?? null) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="cetakan[]" value="0" {{ !($cetakans[$index] ?? null) && isset($cetakans[$index]) ? 'checked' : '' }}>
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
                                                                <input type="number" step="0.01" class="form-control" name="ketebalan_micron[]" value="{{ $ketebalan_microns[$index] ?? '' }}" placeholder="Ketebalan">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Dimensi</label>
                                                                <input type="text" class="form-control" name="dimensi[]" value="{{ $dimensis[$index] ?? '' }}" placeholder="Dimensi">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Status</label>
                                                                <select class="form-control" name="status[]">
                                                                    <option value="">Pilih Status</option>
                                                                    <option value="Hold" {{ ($statuses[$index] ?? '') == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                                    <option value="Release" {{ ($statuses[$index] ?? '') == 'Release' ? 'selected' : '' }}>Release</option>
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
                                                                    <input class="form-check-input" type="radio" name="logo_halal[]" value="1" {{ ($logo_halals[$index] ?? null) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="logo_halal[]" value="0" {{ !($logo_halals[$index] ?? null) && isset($logo_halals[$index]) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="dokumen_halal[]" value="1" {{ ($dokumen_halals[$index] ?? null) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="dokumen_halal[]" value="0" {{ !($dokumen_halals[$index] ?? null) && isset($dokumen_halals[$index]) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>COA</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="coa[]" value="1" {{ ($coas[$index] ?? null) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="coa[]" value="0" {{ !($coas[$index] ?? null) && isset($coas[$index]) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Keterangan</label>
                                                                <textarea class="form-control" name="keterangan[]" rows="2" placeholder="Keterangan tambahan">{{ $keterangans[$index] ?? '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-3 pt-3 border-top">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Baris</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                                <h6 class="text-primary mb-3">Baris 1</h6>
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
                                                            <label class="form-label">Distributor</label>
                                                            <select class="choices form-control" name="distributor[]">
                                                                <option value="">Pilih Distributor</option>
                                                                @foreach ($distributors as $distributor)
                                                                    <option value="{{ $distributor->nama_distributor }}">{{ $distributor->nama_distributor }}</option>
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
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Jumlah Datang</label>
                                                            <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah Datang">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Jumlah Sampling</label>
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
                                                <div class="row mt-3 pt-3 border-top">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Baris</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            function updateDeleteButtons() {
                                                const rows = document.querySelectorAll('.unified-row');
                                                const deleteButtons = document.querySelectorAll('.remove-unified-btn');
                                                
                                                // Jika hanya 1 baris, disable tombol hapus
                                                if (rows.length === 1) {
                                                    deleteButtons.forEach(btn => {
                                                        btn.disabled = true;
                                                        btn.style.opacity = '0.5';
                                                        btn.style.cursor = 'not-allowed';
                                                    });
                                                } else {
                                                    deleteButtons.forEach(btn => {
                                                        btn.disabled = false;
                                                        btn.style.opacity = '1';
                                                        btn.style.cursor = 'pointer';
                                                    });
                                                }
                                            }

                                            // Event listener untuk tombol hapus
                                            document.addEventListener('click', function(e) {
                                                if (e.target.closest('.remove-unified-btn')) {
                                                    const row = e.target.closest('.unified-row');
                                                    if (row && !e.target.closest('.remove-unified-btn').disabled) {
                                                        row.remove();
                                                        updateDeleteButtons();
                                                    }
                                                }
                                            });

                                            // Initial check
                                            updateDeleteButtons();
                                        });
                                    </script>
                                    
                                    <div class="col-md-12 d-flex justify-content-end mt-3">
                                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Update Data</button>
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
@endsection