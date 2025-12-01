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
                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jenis_pemeriksaan">Jenis Pemeriksaan</label>
                                                    <input type="text" id="jenis_pemeriksaan" class="form-control @error('jenis_pemeriksaan') is-invalid @enderror"
                                                        name="jenis_pemeriksaan" value="{{ old('jenis_pemeriksaan', $pemeriksaanKedatanganKemasan->jenis_pemeriksaan) }}" placeholder="Jenis Pemeriksaan">
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
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="segel_gembok" name="segel_gembok" value="1" 
                                                            {{ old('segel_gembok', $pemeriksaanKedatanganKemasan->segel_gembok) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="segel_gembok">
                                                            Segel/Gembok
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="no_segel">No. Segel</label>
                                                    <input type="text" id="no_segel" class="form-control @error('no_segel') is-invalid @enderror"
                                                        name="no_segel" value="{{ old('no_segel', $pemeriksaanKedatanganKemasan->no_segel) }}" placeholder="No. Segel">
                                                    @error('no_segel')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Informasi Kemasan -->
                                    <div class="form-section mb-4">
                                        <h5 class="text-primary mb-3">Informasi Kemasan</h5>
                                        <div class="row">
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
                                            <div class="col-md-6">
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
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nama_bahan_kemasan">Nama Bahan Kemasan</label>
                                                    <input type="text" id="nama_bahan_kemasan" class="form-control @error('nama_bahan_kemasan') is-invalid @enderror"
                                                        name="nama_bahan_kemasan" value="{{ old('nama_bahan_kemasan', $pemeriksaanKedatanganKemasan->nama_bahan_kemasan) }}" placeholder="Nama Bahan Kemasan">
                                                    @error('nama_bahan_kemasan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div> -->
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
                                                </div>
                                            </div>
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

                                    <!-- Kondisi Fisik -->
                                    <div class="form-section mb-4">
                                        <h5 class="text-primary mb-3">Kondisi Fisik</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Penampakan</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_fisik[penampakan]" id="penampakan_ya" value="1" 
                                                            {{ old('kondisi_fisik.penampakan', ($pemeriksaanKedatanganKemasan->kondisi_fisik['penampakan'] ?? null) ? '1' : null) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="penampakan_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_fisik[penampakan]" id="penampakan_tidak" value="0" 
                                                            {{ old('kondisi_fisik.penampakan', ($pemeriksaanKedatanganKemasan->kondisi_fisik['penampakan'] ?? null) ? '1' : '0') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="penampakan_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Sealing</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_fisik[sealing]" id="sealing_ya" value="1" 
                                                            {{ old('kondisi_fisik.sealing', ($pemeriksaanKedatanganKemasan->kondisi_fisik['sealing'] ?? null) ? '1' : null) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="sealing_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_fisik[sealing]" id="sealing_tidak" value="0" 
                                                            {{ old('kondisi_fisik.sealing', ($pemeriksaanKedatanganKemasan->kondisi_fisik['sealing'] ?? null) ? '1' : '0') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="sealing_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Cetakan</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_fisik[cetakan]" id="cetakan_ya" value="1" 
                                                            {{ old('kondisi_fisik.cetakan', ($pemeriksaanKedatanganKemasan->kondisi_fisik['cetakan'] ?? null) ? '1' : null) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="cetakan_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_fisik[cetakan]" id="cetakan_tidak" value="0" 
                                                            {{ old('kondisi_fisik.cetakan', ($pemeriksaanKedatanganKemasan->kondisi_fisik['cetakan'] ?? null) ? '1' : '0') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="cetakan_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Status & Shift -->
                                    <div class="form-section mb-4">
                                        <h5 class="text-primary mb-3">Status & Shift</h5>
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
                                                        <option value="">Pilih Shift (Opsional)</option>
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
                                        <!-- Field Tambahan yang Hilang di Edit -->
                                        <div class="form-section mb-4">
                                            <h5 class="text-primary mb-3">Detail Tambahan</h5>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="ketebalan_micron">Ketebalan (Micron)</label>
                                                        <input type="number" step="0.01" id="ketebalan_micron" class="form-control @error('ketebalan_micron') is-invalid @enderror"
                                                            name="ketebalan_micron" value="{{ old('ketebalan_micron', $pemeriksaanKedatanganKemasan->ketebalan_micron) }}" placeholder="Ketebalan dalam micron">
                                                        @error('ketebalan_micron')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Radio Button untuk Dokumen -->
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label"><strong>Logo Halal</strong></label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="logo_halal" id="logo_halal_ya" value="1" 
                                                                {{ old('logo_halal', $pemeriksaanKedatanganKemasan->logo_halal ? '1' : null) == '1' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="logo_halal_ya">Ya ✓</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="logo_halal" id="logo_halal_tidak" value="0" 
                                                                {{ old('logo_halal', $pemeriksaanKedatanganKemasan->logo_halal ? '1' : '0') == '0' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="logo_halal_tidak">Tidak ✗</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="dokumen_halal" id="dokumen_halal_ya" value="1" 
                                                                {{ old('dokumen_halal', $pemeriksaanKedatanganKemasan->dokumen_halal ? '1' : null) == '1' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="dokumen_halal_ya">Ya ✓</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="dokumen_halal" id="dokumen_halal_tidak" value="0" 
                                                                {{ old('dokumen_halal', $pemeriksaanKedatanganKemasan->dokumen_halal ? '1' : '0') == '0' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="dokumen_halal_tidak">Tidak ✗</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label"><strong>COA</strong></label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="coa" id="coa_ya" value="1" 
                                                                {{ old('coa', $pemeriksaanKedatanganKemasan->coa ? '1' : null) == '1' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="coa_ya">Ya ✓</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="coa" id="coa_tidak" value="0" 
                                                                {{ old('coa', $pemeriksaanKedatanganKemasan->coa ? '1' : '0') == '0' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="coa_tidak">Tidak ✗</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="keterangan">Keterangan</label>
                                            <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                                name="keterangan" rows="3" placeholder="Keterangan">{{ old('keterangan', $pemeriksaanKedatanganKemasan->keterangan) }}</textarea>
                                            @error('keterangan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 d-flex justify-content-end mt-3">
                                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
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