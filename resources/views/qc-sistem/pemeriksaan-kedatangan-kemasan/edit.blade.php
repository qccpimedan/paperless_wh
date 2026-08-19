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

<style>
    .collapse-toggle-btn {
        width: auto;
        display: inline-flex;
        align-items: center;
        text-align: left;
    }
    .collapse-toggle-btn.full-width {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    .collapse-chevron { transition: transform .2s ease; }
    .collapse-toggle-btn[aria-expanded="true"] .collapse-chevron { transform: rotate(180deg); }
</style>
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

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-kedatangan-kemasan.update', $pemeriksaanKedatanganKemasan->uuid) }}" method="POST" enctype="multipart/form-data">
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
                                                    <label class="form-label"><strong>Bebas dari Produk Non Halal</strong></label>
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
                                            
                                            {{-- <div class="col-md-6">
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
                                            </div> --}}
                                        </div>

                                        {{-- <div class="row">
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
                                        </div> --}}
                                    </div>

                                    <!-- Dynamic Rows Data -->
                                    @php
                                        $id_bahans = json_decode($pemeriksaanKedatanganKemasan->id_bahan_array ?? '[]', true) ?? [];
                                        $produsens_arr = json_decode($pemeriksaanKedatanganKemasan->produsen_array ?? '[]', true) ?? [];
                                        $distributors_arr = json_decode($pemeriksaanKedatanganKemasan->distributor_array ?? '[]', true) ?? [];
                                        $kode_produksis = json_decode($pemeriksaanKedatanganKemasan->kode_produksi_array ?? '[]', true) ?? [];
                                        $jumlah_datangs = json_decode($pemeriksaanKedatanganKemasan->jumlah_datang_array ?? '[]', true) ?? [];
                                        $unit_datangs = json_decode($pemeriksaanKedatanganKemasan->unit_datang_array ?? '[]', true) ?? [];
                                        $jumlah_samplings = json_decode($pemeriksaanKedatanganKemasan->jumlah_sampling_array ?? '[]', true) ?? [];
                                        $unit_samplings = json_decode($pemeriksaanKedatanganKemasan->unit_sampling_array ?? '[]', true) ?? [];
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
                                        $image_kemasans = json_decode($pemeriksaanKedatanganKemasan->image_kemasan_array ?? '[]', true) ?? [];

                                        $groupedDetailIdx = [];
                                        foreach ((array) $id_bahans as $i => $pid) {
                                            $pid = $pid === null ? '' : (string) $pid;
                                            if ($pid === '') continue;
                                            if (!isset($groupedDetailIdx[$pid])) $groupedDetailIdx[$pid] = [];
                                            $groupedDetailIdx[$pid][] = $i;
                                        }

                                        $globalDetailSeq = 0;
                                    @endphp
                                    <div id="unified-container">

                                        @forelse($groupedDetailIdx as $produkId => $detailIdxList)
                                            @php
                                                $firstIdx = $detailIdxList[0] ?? null;
                                                $existingKategori = $produkId ? ($existingKategoriByProdukId[$produkId] ?? '') : '';

                                                $docLogo = $firstIdx !== null ? ($logo_halals[$firstIdx] ?? '') : '';
                                                $docDokumen = $firstIdx !== null ? ($dokumen_halals[$firstIdx] ?? '') : '';
                                                $docCoa = $firstIdx !== null ? ($coas[$firstIdx] ?? '') : '';
                                            @endphp
                                            <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;" data-old-produk-id="{{ $produkId }}" data-first-index="{{ $firstIdx }}">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="mb-0 bahan-title text-white">Bahan #{{ $loop->iteration }}</h6>
                                                </div>

                                                <!-- Bahan Kemasan -->
                                                <div class="form-section mb-3">
                                                    <h6 class="text-primary mb-2">Bahan Kemasan</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Kategori</label>
                                                                <select class="form-control kategori-produk-select" data-role="kategori_code">
                                                                    <option value="">Pilih Kategori</option>
                                                                    @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                        <option value="{{ $kategori }}" {{ $existingKategori == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Produk</label>
                                                                <select class="form-control produk-select" data-role="id_produk">
                                                                    <option value="">Pilih Produk</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Informasi Kemasan & Supplier -->
                                                <div class="form-section mb-3">
                                                    <h6 class="text-primary mb-2">Informasi Kemasan & Supplier</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card border-0 shadow-sm">
                                                                <div class="card-body p-3">
                                                                    <div class="fw-semibold">Produsen</div>
                                                                    <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                                    <div class="produsen-badges d-flex flex-wrap gap-1">
                                                                        @php
                                                                            $existingProdusen = $firstIdx !== null ? ($produsens_arr[$firstIdx] ?? '') : '';
                                                                            if (is_array($existingProdusen)) {
                                                                                $existingProdusen = implode(', ', array_values(array_filter(array_map('strval', $existingProdusen), fn ($v) => $v !== '')));
                                                                            }
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
                                                                            <input type="hidden" class="produsen-hidden-item" value="{{ $p }}">
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card border-0 shadow-sm">
                                                                <div class="card-body p-3">
                                                                    <div class="fw-semibold">Distributor</div>
                                                                    <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                                    <div class="distributor-badges d-flex flex-wrap gap-1">
                                                                        @php
                                                                            $existingDistributor = $firstIdx !== null ? ($distributors_arr[$firstIdx] ?? '') : '';
                                                                            if (is_array($existingDistributor)) {
                                                                                $existingDistributor = implode(', ', array_values(array_filter(array_map('strval', $existingDistributor), fn ($v) => $v !== '')));
                                                                            }
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
                                                                            <input type="hidden" class="distributor-hidden-item" value="{{ $d }}">
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="detail-items">
                                                    @foreach($detailIdxList as $detailNo => $srcIndex)
                                                        @php
                                                            $seq = $globalDetailSeq;
                                                            $globalDetailSeq++;
                                                        @endphp
                                                        <div class="detail-item mb-3 p-3 border rounded" data-detail-index="{{ $seq }}" style="background-color: #ffffff;">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="fw-bold detail-title">Detail #{{ $detailNo + 1 }}</span>
                                                                <button type="button" class="btn btn-danger btn-sm remove-detail-btn" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                                                            </div>

                                                            <input type="hidden" name="kategori_code[]" class="kategori-code-hidden" value="{{ $existingKategori }}">
                                                            <input type="hidden" name="id_produk[]" class="produk-id-hidden" value="{{ $produkId }}">
                                                            <div class="produsen-hidden-inputs"></div>
                                                            <div class="distributor-hidden-inputs"></div>

                                                            <input type="hidden" name="penampakan[]" class="penampakan-hidden" value="{{ $penampakans[$srcIndex] ?? '' }}">
                                                            <input type="hidden" name="sealing[]" class="sealing-hidden" value="{{ $sealings[$srcIndex] ?? '' }}">
                                                            <input type="hidden" name="cetakan[]" class="cetakan-hidden" value="{{ $cetakans[$srcIndex] ?? '' }}">
                                                            <input type="hidden" name="logo_halal[]" class="logo-halal-hidden" value="{{ $logo_halals[$srcIndex] ?? '' }}">
                                                            <input type="hidden" name="dokumen_halal[]" class="dokumen-halal-hidden" value="{{ $dokumen_halals[$srcIndex] ?? '' }}">
                                                            <input type="hidden" name="coa[]" class="coa-hidden" value="{{ $coas[$srcIndex] ?? '' }}">

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Kode Produksi</label>
                                                                        <input type="text" class="form-control" name="kode_produksi[]" value="{{ $kode_produksis[$srcIndex] ?? '' }}" placeholder="Kode Produksi">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Jumlah Datang</label>
                                                                        <div class="input-group" style="max-width: 100%;">
                                                                            <input type="text" class="form-control" name="jumlah_datang[]" value="{{ $jumlah_datangs[$srcIndex] ?? '' }}" placeholder="Jumlah" min="0" step="any">
                                                                            <select class="form-select" name="unit_datang[]" style="max-width: 120px;">
                                                                                <option value="">Pilih Parameter</option>
                                                                                @foreach(\App\Models\PemeriksaanKedatanganKemasan::unitParameters() as $key => $label)
                                                                                    <option value="{{ $key }}" {{ ($unit_datangs[$srcIndex] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Jumlah Sampling</label>
                                                                        <div class="input-group" style="max-width: 100%;">
                                                                            <input type="text" class="form-control" name="jumlah_sampling[]" value="{{ $jumlah_samplings[$srcIndex] ?? '' }}" placeholder="Jumlah" min="0" step="any">
                                                                            <select class="form-select" name="unit_sampling[]" style="max-width: 120px;">
                                                                                <option value="">Pilih Parameter</option>
                                                                                @foreach(\App\Models\PemeriksaanKedatanganKemasan::unitParameters() as $key => $label)
                                                                                    <option value="{{ $key }}" {{ ($unit_samplings[$srcIndex] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Spesifikasi</label>
                                                                        <textarea class="form-control" name="spesifikasi[]" rows="2" placeholder="Spesifikasi">{{ $spesifikasis[$srcIndex] ?? '' }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-section mb-3 mt-3">
                                                                <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label class="form-label"><strong>Penampakan</strong></label>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="penampakan_master_{{ $seq }}" value="1" {{ (string)($penampakans[$srcIndex] ?? '') === '1' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Ya ✓</label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="penampakan_master_{{ $seq }}" value="0" {{ (string)($penampakans[$srcIndex] ?? '') === '0' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Tidak ✗</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label class="form-label"><strong>Sealing</strong></label>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="sealing_master_{{ $seq }}" value="1" {{ (string)($sealings[$srcIndex] ?? '') === '1' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Ya ✓</label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="sealing_master_{{ $seq }}" value="0" {{ (string)($sealings[$srcIndex] ?? '') === '0' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Tidak ✗</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label class="form-label"><strong>Cetakan</strong></label>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="cetakan_master_{{ $seq }}" value="1" {{ (string)($cetakans[$srcIndex] ?? '') === '1' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Ya ✓</label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="cetakan_master_{{ $seq }}" value="0" {{ (string)($cetakans[$srcIndex] ?? '') === '0' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Tidak ✗</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-section mb-3">
                                                                <h6 class="text-primary mb-2">Detail Tambahan</h6>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Ketebalan (Micron)</label>
                                                                            <input type="number" step="0.01" class="form-control" name="ketebalan_micron[]" value="{{ $ketebalan_microns[$srcIndex] ?? '' }}" placeholder="Ketebalan">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Dimensi</label>
                                                                            <input type="text" class="form-control" name="dimensi[]" value="{{ $dimensis[$srcIndex] ?? '' }}" placeholder="Dimensi">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Status</label>
                                                                            <select class="form-control" name="status[]">
                                                                                <option value="">Pilih Status</option>
                                                                                <option value="Hold" {{ ($statuses[$srcIndex] ?? '') == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                                                <option value="Release" {{ ($statuses[$srcIndex] ?? '') == 'Release' ? 'selected' : '' }}>Release</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Keterangan</label>
                                                                            <textarea class="form-control" name="keterangan[]" rows="2" placeholder="Keterangan tambahan">{{ $keterangans[$srcIndex] ?? '' }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-section mb-3">
                                                                <h6 class="text-primary mb-2">Upload Gambar</h6>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        @php
                                                                            $imgPath = $image_kemasans[$srcIndex] ?? null;
                                                                        @endphp
                                                                        @if($imgPath)
                                                                            <div class="mb-2">
                                                                                <img src="{{ asset('storage/' . $imgPath) }}" alt="Gambar Kemasan" style="max-width: 220px; height: auto; border: 1px solid #ddd; padding: 4px; background: #fff;">
                                                                            </div>
                                                                        @endif
                                                                        <div class="form-group">
                                                                            <label class="form-label">Ganti Gambar</label>
                                                                            <input type="file" name="image_kemasan[]" class="form-control image-kemasan-input" accept="image/*" capture="camera">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <!-- Dokumen (Master per Bahan) -->
                                                <div class="form-section mb-3">
                                                    <h6 class="text-primary mb-2">Dokumen</h6>
                                                    <input type="hidden" class="doc-master-logo" value="{{ $docLogo }}">
                                                    <input type="hidden" class="doc-master-dokumen" value="{{ $docDokumen }}">
                                                    <input type="hidden" class="doc-master-coa" value="{{ $docCoa }}">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>Logo Halal</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="logo_halal_master_{{ $loop->index }}" value="1" {{ (string)$docLogo === '1' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="logo_halal_master_{{ $loop->index }}" value="0" {{ (string)$docLogo === '0' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="dokumen_halal_master_{{ $loop->index }}" value="1" {{ (string)$docDokumen === '1' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="dokumen_halal_master_{{ $loop->index }}" value="0" {{ (string)$docDokumen === '0' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>COA</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="coa_master_{{ $loop->index }}" value="1" {{ (string)$docCoa === '1' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="coa_master_{{ $loop->index }}" value="0" {{ (string)$docCoa === '0' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-primary btn-sm add-detail-btn"><i class="bi bi-plus"></i> Tambah Detail</button>
                                                    </div>
                                                </div>

                                                <div class="row mt-3 pt-3 border-top">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Produk</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                                <h6 class="text-primary mb-3">Baris 1</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Kategori</label>
                                                            <select class="choices form-control kategori-produk-select" name="kategori_code[]">
                                                                <option value="">Pilih Kategori</option>
                                                                @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                    <option value="{{ $kategori }}">{{ $kategori }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Produk</label>
                                                            <select class="form-control produk-select" name="id_produk[]">
                                                                <option value="">Pilih Produk</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-body p-3">
                                                                <div class="fw-semibold">Produsen</div>
                                                                <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                                <div class="produsen-badges d-flex flex-wrap gap-1">
                                                                    <span class="text-muted small">-</span>
                                                                </div>
                                                                <div class="produsen-hidden-inputs">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-body p-3">
                                                                <div class="fw-semibold">Distributor</div>
                                                                <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                                <div class="distributor-badges d-flex flex-wrap gap-1">
                                                                    <span class="text-muted small">-</span>
                                                                </div>
                                                                <div class="distributor-hidden-inputs">
                                                                </div>
                                                            </div>
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
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Jumlah Datang</label>
                                                            <div class="input-group" style="max-width: 100%;">
                                                                <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah" min="0" step="any">
                                                                <select class="form-select" name="unit_datang[]" style="max-width: 120px;">
                                                                    <option value="">Pilih Parameter</option>
                                                                    @foreach(\App\Models\PemeriksaanKedatanganKemasan::unitParameters() as $key => $label)
                                                                        <option value="{{ $key }}">{{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Jumlah Sampling</label>
                                                            <div class="input-group" style="max-width: 100%;">
                                                                <input type="text" class="form-control" name="jumlah_sampling[]" placeholder="Jumlah" min="0" step="any">
                                                                <select class="form-select" name="unit_sampling[]" style="max-width: 120px;">
                                                                    <option value="">Pilih Parameter</option>
                                                                    @foreach(\App\Models\PemeriksaanKedatanganKemasan::unitParameters() as $key => $label)
                                                                        <option value="{{ $key }}">{{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Spesifikasi</label>
                                                            <textarea class="form-control" name="spesifikasi[]" rows="2" placeholder="Spesifikasi"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-section mb-3">
                                                    <h6 class="text-primary mb-2">Upload Gambar</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Ganti Gambar</label>
                                                                <input type="file" name="image_kemasan[]" class="form-control image-kemasan-input" accept="image/*" capture="camera">
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
                                        @endforelse
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const choicesInstances = new WeakMap();
                                            const produkByKategori = @json($produkByKategori ?? []);
                                            const produkMeta = @json($produkMeta ?? []);
                                            const oldKategoriCodes = @json(old('kategori_code', []));
                                            const oldProdukIds = @json(old('id_produk', []));

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
                                                    rowEl.dataset.produkCollapseId = uniqueDomId('produk_e');
                                                }
                                                const collapseId = rowEl.dataset.produkCollapseId;

                                                const header = rowEl.firstElementChild;
                                                if (!header) return;

                                                const titleEl = header.querySelector('.bahan-title');
                                                if (titleEl && !titleEl.querySelector('button[data-bs-toggle="collapse"]')) {
                                                    const existingText = (titleEl.textContent || '').trim();
                                                    titleEl.textContent = '';

                                                    const btn = document.createElement('button');
                                                    btn.type = 'button';
                                                    btn.className = 'btn btn-primary btn-sm d-inline-flex align-items-center gap-2 collapse-toggle-btn';
                                                    btn.setAttribute('data-bs-toggle', 'collapse');
                                                    btn.setAttribute('data-bs-target', `#${collapseId}`);
                                                    btn.setAttribute('aria-expanded', 'true');
                                                    btn.setAttribute('aria-controls', collapseId);

                                                    const span = document.createElement('span');
                                                    span.className = 'text-white mb-0 bahan-title produk-collapse-label';
                                                    span.textContent = existingText || `Bahan #${rowIdx + 1}`;

                                                    const icon = document.createElement('i');
                                                    icon.className = 'bi bi-chevron-down collapse-chevron';

                                                    btn.appendChild(span);
                                                    btn.appendChild(icon);
                                                    titleEl.appendChild(btn);
                                                }

                                                let body = rowEl.querySelector(':scope > .produk-collapse.collapse');
                                                if (body) {
                                                    body.id = collapseId;
                                                } else {
                                                    body = document.createElement('div');
                                                    body.className = 'produk-collapse collapse show';
                                                    body.id = collapseId;

                                                    const nodesToMove = [];
                                                    let node = header.nextSibling;
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
                                                    collapseId = uniqueDomId('detail_e');
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
                                                labelEl.textContent = selectedText ? `${rowIdx + 1}. ${selectedText}` : `Bahan #${rowIdx + 1}`;
                                            };

                                            const updateDetailLabel = (detailEl, detailIdxWithinRow) => {
                                                if (!detailEl) return;
                                                const labelEl = detailEl.querySelector('.detail-collapse-label');
                                                if (!labelEl) return;
                                                const kodeInp = detailEl.querySelector('input[name="kode_produksi[]"]');
                                                const kodeVal = kodeInp ? String(kodeInp.value || '').trim() : '';
                                                labelEl.textContent = kodeVal || 'Kode Produksi';
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

                                            const initKemasanCollapses = () => {
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

                                            function updateSectionLabels() {
                                                const rows = Array.from(document.querySelectorAll('#unified-container .unified-row'));
                                                rows.forEach((rowEl, rowIdx) => {
                                                    const produkLabel = rowEl.querySelector('.produk-collapse-label');
                                                    if (produkLabel) {
                                                        updateProdukLabel(rowEl, rowIdx);
                                                    } else {
                                                        const title = rowEl.querySelector('.bahan-title');
                                                        if (title) title.textContent = `Bahan #${rowIdx + 1}`;
                                                    }
                                                });
                                            }

                                            function updateDetailIndices() {
                                                const allDetails = Array.from(document.querySelectorAll('#unified-container .detail-item'));
                                                allDetails.forEach((detailEl, idx) => {
                                                    detailEl.dataset.detailIndex = String(idx);

                                                    const setArrayName = (el, base) => {
                                                        if (!el) return;
                                                        el.name = `${base}[]`;
                                                    };

                                                    setArrayName(detailEl.querySelector('input[name^="kode_produksi"], input[name="kode_produksi[]"]'), 'kode_produksi');
                                                    setArrayName(detailEl.querySelector('input[name^="jumlah_datang"], input[name="jumlah_datang[]"]'), 'jumlah_datang');
                                                    setArrayName(detailEl.querySelector('select[name^="unit_datang"], select[name="unit_datang[]"]'), 'unit_datang');
                                                    setArrayName(detailEl.querySelector('input[name^="jumlah_sampling"], input[name="jumlah_sampling[]"]'), 'jumlah_sampling');
                                                    setArrayName(detailEl.querySelector('select[name^="unit_sampling"], select[name="unit_sampling[]"]'), 'unit_sampling');
                                                    setArrayName(detailEl.querySelector('textarea[name^="spesifikasi"], textarea[name="spesifikasi[]"]'), 'spesifikasi');
                                                    setArrayName(detailEl.querySelector('input[name^="ketebalan_micron"], input[name="ketebalan_micron[]"]'), 'ketebalan_micron');
                                                    setArrayName(detailEl.querySelector('input[name^="dimensi"], input[name="dimensi[]"]'), 'dimensi');
                                                    setArrayName(detailEl.querySelector('select[name^="status"], select[name="status[]"]'), 'status');
                                                    setArrayName(detailEl.querySelector('textarea[name^="keterangan"], textarea[name="keterangan[]"]'), 'keterangan');
                                                    setArrayName(detailEl.querySelector('input[type="file"][name^="image_kemasan"], input[type="file"][name="image_kemasan[]"]'), 'image_kemasan');

                                                    const masterRadioGroups = ['penampakan', 'sealing', 'cetakan', 'logo_halal', 'dokumen_halal', 'coa'];
                                                    masterRadioGroups.forEach((g) => {
                                                        detailEl.querySelectorAll(`input[type="radio"][name^="${g}_master_"]`).forEach((r) => {
                                                            r.name = `${g}_master_${idx}`;
                                                        });
                                                    });

                                                    const prodWrap = detailEl.querySelector('.produsen-hidden-inputs');
                                                    const distWrap = detailEl.querySelector('.distributor-hidden-inputs');
                                                    if (prodWrap) {
                                                        prodWrap.querySelectorAll('input[type="hidden"]').forEach((inp) => {
                                                            inp.name = `produsen[${idx}][]`;
                                                        });
                                                    }
                                                    if (distWrap) {
                                                        distWrap.querySelectorAll('input[type="hidden"]').forEach((inp) => {
                                                            inp.name = `distributor[${idx}][]`;
                                                        });
                                                    }
                                                });

                                                document.querySelectorAll('#unified-container .unified-row').forEach((rowEl) => {
                                                    const details = rowEl.querySelectorAll('.detail-item');
                                                    details.forEach((d, di) => {
                                                        const detailLabel = d.querySelector('.detail-collapse-label');
                                                        if (detailLabel) {
                                                            updateDetailLabel(d, di);
                                                        } else {
                                                            const title = d.querySelector('.detail-title');
                                                            if (title) title.textContent = `Detail #${di + 1}`;
                                                        }
                                                        const rm = d.querySelector('.remove-detail-btn');
                                                        if (rm) rm.style.display = details.length > 1 ? '' : 'none';
                                                    });
                                                });

                                                updateSectionLabels();
                                            }

                                            function syncHeaderToDetails(rowEl) {
                                                const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
                                                const produkSelect = rowEl.querySelector('select.produk-select');
                                                const produsenTemplate = rowEl.querySelectorAll('.produsen-hidden-inputs .produsen-hidden-item');
                                                const distributorTemplate = rowEl.querySelectorAll('.distributor-hidden-inputs .distributor-hidden-item');

                                                const kategoriVal = kategoriSelect ? String(kategoriSelect.value || '') : '';
                                                const produkVal = produkSelect ? String(produkSelect.value || '') : '';

                                                rowEl.querySelectorAll('.detail-item').forEach((detailEl) => {
                                                    const kategoriHidden = detailEl.querySelector('input.kategori-code-hidden');
                                                    const produkHidden = detailEl.querySelector('input.produk-id-hidden');
                                                    if (kategoriHidden) kategoriHidden.value = kategoriVal;
                                                    if (produkHidden) produkHidden.value = produkVal;

                                                    const prodWrap = detailEl.querySelector('.produsen-hidden-inputs');
                                                    const distWrap = detailEl.querySelector('.distributor-hidden-inputs');
                                                    if (prodWrap) {
                                                        prodWrap.innerHTML = '';
                                                        produsenTemplate.forEach((t) => {
                                                            const input = document.createElement('input');
                                                            input.type = 'hidden';
                                                            input.value = String(t.value || '');
                                                            prodWrap.appendChild(input);
                                                        });
                                                    }
                                                    if (distWrap) {
                                                        distWrap.innerHTML = '';
                                                        distributorTemplate.forEach((t) => {
                                                            const input = document.createElement('input');
                                                            input.type = 'hidden';
                                                            input.value = String(t.value || '');
                                                            distWrap.appendChild(input);
                                                        });
                                                    }
                                                });
                                                updateDetailIndices();
                                            }

                                            function syncDokumenToDetails(rowEl) {
                                                const getCheckedVal = (namePrefix) => {
                                                    const inp = rowEl.querySelector(`input[type="radio"][name^="${namePrefix}_master_"]:checked`);
                                                    return inp ? String(inp.value) : '';
                                                };

                                                const logoVal = getCheckedVal('logo_halal');
                                                const docVal = getCheckedVal('dokumen_halal');
                                                const coaVal = getCheckedVal('coa');

                                                rowEl.querySelectorAll('.detail-item').forEach((detailEl) => {
                                                    const l = detailEl.querySelector('input.logo-halal-hidden');
                                                    const d = detailEl.querySelector('input.dokumen-halal-hidden');
                                                    const c = detailEl.querySelector('input.coa-hidden');
                                                    if (l) l.value = logoVal;
                                                    if (d) d.value = docVal;
                                                    if (c) c.value = coaVal;
                                                });
                                            }

                                            function initGenericChoices(selectEl) {
                                                if (!selectEl || typeof window.Choices === 'undefined') return;
                                                if (choicesInstances.get(selectEl)) return;
                                                const instance = new Choices(selectEl, {
                                                    searchResultLimit: 100,
                                                    searchFuzziness: 0.000001,
                                                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
                                                    searchEnabled: true,
                                                    searchPlaceholderValue: 'Cari...',
                                                    itemSelectText: 'Tekan untuk memilih',
                                                    noResultsText: 'Tidak ada hasil ditemukan',
                                                    noChoicesText: 'Tidak ada pilihan tersedia',
                                                    placeholder: true,
                                                    placeholderValue: 'Pilih...'
                                                });
                                                choicesInstances.set(selectEl, instance);
                                            }

                                            function initProdukChoices(selectEl) {
                                                if (!selectEl || typeof window.Choices === 'undefined') return;
                                                if (selectEl.choicesInstance) {
                                                    try { selectEl.choicesInstance.destroy(); } catch (e) {}
                                                    selectEl.choicesInstance = null;
                                                }
                                                try {
                                                    selectEl.choicesInstance = new Choices(selectEl, {
                                                        searchResultLimit: 100,
                                                        searchFuzziness: 0.000001,
                                                        fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
                                                        searchEnabled: true,
                                                        searchPlaceholderValue: 'Cari...',
                                                        itemSelectText: 'Tekan untuk memilih',
                                                        noResultsText: 'Tidak ada hasil ditemukan',
                                                        noChoicesText: 'Tidak ada pilihan tersedia',
                                                        placeholder: true,
                                                        placeholderValue: 'Pilih...'
                                                    });
                                                } catch (e) {}
                                            }

                                            function applyBadges(rowEl, prodVals, distVals) {
                                                const setBadges = (badgesEl, hiddenInputsEl, values, badgeClass, inputName) => {
                                                    if (!badgesEl || !hiddenInputsEl) return;
                                                    badgesEl.innerHTML = '';
                                                    hiddenInputsEl.innerHTML = '';
                                                    if (!values || values.length === 0) {
                                                        const span = document.createElement('span');
                                                        span.className = 'text-muted small';
                                                        span.textContent = '-';
                                                        badgesEl.appendChild(span);
                                                        return;
                                                    }
                                                    values.forEach(v => {
                                                        const s = String(v);
                                                        if (!s) return;
                                                        const badge = document.createElement('span');
                                                        badge.className = badgeClass;
                                                        badge.textContent = s;
                                                        badgesEl.appendChild(badge);
                                                        const input = document.createElement('input');
                                                        input.type = 'hidden';
                                                        input.className = inputName;
                                                        input.value = s;
                                                        hiddenInputsEl.appendChild(input);
                                                    });
                                                };

                                                setBadges(
                                                    rowEl.querySelector('.produsen-badges'),
                                                    rowEl.querySelector('.produsen-hidden-inputs'),
                                                    prodVals,
                                                    'badge bg-light-primary text-primary',
                                                    'produsen-hidden-item'
                                                );
                                                setBadges(
                                                    rowEl.querySelector('.distributor-badges'),
                                                    rowEl.querySelector('.distributor-hidden-inputs'),
                                                    distVals,
                                                    'badge bg-light-info text-info',
                                                    'distributor-hidden-item'
                                                );

                                                syncHeaderToDetails(rowEl);
                                            }

                                            function applyProdukMetaForRow(rowEl, forceOverride) {
                                                const produkSelect = rowEl.querySelector('select.produk-select');
                                                if (!produkSelect) return;
                                                if (!forceOverride) {
                                                    const produsenBadgesEl = rowEl.querySelector('.produsen-badges');
                                                    if (produsenBadgesEl && produsenBadgesEl.querySelector('.badge')) {
                                                        return;
                                                    }
                                                }
                                                const produkId = (produkSelect.value || '').toString();
                                                const meta = produkMeta ? produkMeta[produkId] : null;
                                                if (!meta) return;

                                                const normalizeMulti = (v) => {
                                                    if (Array.isArray(v)) return v.map(x => String(x));
                                                    if (v === null || v === undefined) return [];
                                                    const s = String(v);
                                                    return s ? [s] : [];
                                                };
                                                applyBadges(rowEl, normalizeMulti(meta.produsen), normalizeMulti(meta.distributor));
                                            }

                                            function populateProdukOptionsForRow(rowEl, forceOverride) {
                                                const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
                                                const produkSelect = rowEl.querySelector('select.produk-select');
                                                if (!kategoriSelect || !produkSelect) return;

                                                const kategori = (kategoriSelect.value || '').toString();
                                                const raw = produkByKategori ? produkByKategori[kategori] : null;
                                                const items = Array.isArray(raw) ? raw : (raw ? Object.values(raw) : []);

                                                const desiredProdukId = (kategoriSelect.dataset && kategoriSelect.dataset.desiredProduk)
                                                    ? String(kategoriSelect.dataset.desiredProduk)
                                                    : ((produkSelect.dataset && produkSelect.dataset.desiredValue)
                                                        ? String(produkSelect.dataset.desiredValue)
                                                        : ((oldProdukIds && oldProdukIds.length) ? String(oldProdukIds[rowEl.dataset.rowIndex] || '') : ''));

                                                produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
                                                items.forEach(p => {
                                                    const opt = document.createElement('option');
                                                    opt.value = String(p.id);
                                                    opt.textContent = String(p.nama);
                                                    if (desiredProdukId && String(p.id) === String(desiredProdukId)) {
                                                        opt.selected = true;
                                                    }
                                                    produkSelect.appendChild(opt);
                                                });

                                                initProdukChoices(produkSelect);
                                                setTimeout(() => applyProdukMetaForRow(rowEl, forceOverride), 0);

                                                syncHeaderToDetails(rowEl);
                                            }

                                            document.addEventListener('change', function(e) {
                                                const target = e.target;
                                                if (target && target.matches('select.kategori-produk-select')) {
                                                    const row = target.closest('.unified-row');
                                                    if (row && target.dataset) {
                                                        target.dataset.desiredProduk = '';
                                                        populateProdukOptionsForRow(row, true);
                                                    }
                                                }

                                                if (target && target.matches('select.produk-select')) {
                                                    const row = target.closest('.unified-row');
                                                    if (row) {
                                                        const kategoriSelect = row.querySelector('select.kategori-produk-select');
                                                        if (kategoriSelect && kategoriSelect.dataset) {
                                                            kategoriSelect.dataset.desiredProduk = (target.value || '').toString();
                                                        }
                                                        applyProdukMetaForRow(row, true);
                                                        const idx = Array.from(document.querySelectorAll('#unified-container .unified-row')).indexOf(row);
                                                        updateProdukLabel(row, idx >= 0 ? idx : 0);
                                                    }
                                                }

                                                if (target && target.matches('input[type="radio"][name^="logo_halal_master_"], input[type="radio"][name^="dokumen_halal_master_"], input[type="radio"][name^="coa_master_"]')) {
                                                    const row = target.closest('.unified-row');
                                                    if (row) {
                                                        syncDokumenToDetails(row);
                                                    }
                                                }
                                            });

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

                                            function updateDeleteButtons() {
                                                const rows = document.querySelectorAll('.unified-row');
                                                const deleteButtons = document.querySelectorAll('.remove-unified-btn');
                                                deleteButtons.forEach(button => {
                                                    if (rows.length > 1) {
                                                        button.style.display = 'inline-block';
                                                    } else {
                                                        button.style.display = 'none';
                                                    }
                                                });
                                            }

                                            document.querySelectorAll('#unified-container .unified-row').forEach((row, idx) => {
                                                row.dataset.rowIndex = String(idx);
                                                const kategoriSelect = row.querySelector('select.kategori-produk-select');
                                                const produkSelect = row.querySelector('select.produk-select');
                                                const firstIdxAttr = row.getAttribute('data-first-index');
                                                const firstIdx = firstIdxAttr !== null ? parseInt(firstIdxAttr) : idx;

                                                const oldProdukId = row.getAttribute('data-old-produk-id');
                                                if (oldProdukId && produkSelect) {
                                                    produkSelect.dataset.desiredValue = String(oldProdukId);
                                                }

                                                if (kategoriSelect) {
                                                    const desiredKategori = (oldKategoriCodes && oldKategoriCodes[firstIdx]) ? String(oldKategoriCodes[firstIdx]) : '';
                                                    if (desiredKategori && !kategoriSelect.value) {
                                                        kategoriSelect.value = desiredKategori;
                                                    }
                                                    // Manually initialize Choices for category
                                                    initGenericChoices(kategoriSelect);
                                                }

                                                if (produkSelect) {
                                                    const desiredProduk = (oldProdukIds && oldProdukIds[firstIdx]) ? String(oldProdukIds[firstIdx]) : '';
                                                    if (desiredProduk) {
                                                        produkSelect.dataset.desiredValue = desiredProduk;
                                                    }
                                                }

                                                if (kategoriSelect && kategoriSelect.value) {
                                                    populateProdukOptionsForRow(row);
                                                } else if (produkSelect && (produkSelect.value || produkSelect.dataset.desiredValue)) {
                                                    applyProdukMetaForRow(row);
                                                }

                                                syncHeaderToDetails(row);
                                                syncDokumenToDetails(row);
                                            });

                                            document.addEventListener('click', function(e) {
                                                const addBtn = e.target.closest('.add-detail-btn');
                                                if (addBtn) {
                                                    const rowEl = addBtn.closest('.unified-row');
                                                    if (!rowEl) return;
                                                    const container = rowEl.querySelector('.detail-items');
                                                    const first = container ? container.querySelector('.detail-item') : null;
                                                    if (!container || !first) return;

                                                    const clone = first.cloneNode(true);
                                                    clone.querySelectorAll('input, textarea').forEach((el) => {
                                                        if (el.type === 'radio' || el.type === 'checkbox') {
                                                            el.checked = false;
                                                        } else if (el.type === 'file') {
                                                            el.value = '';
                                                        } else {
                                                            el.value = '';
                                                        }
                                                    });
                                                    clone.querySelectorAll('select').forEach((el) => {
                                                        el.value = '';
                                                    });
                                                    clone.querySelectorAll('.produsen-hidden-inputs, .distributor-hidden-inputs').forEach((wrap) => {
                                                        wrap.innerHTML = '';
                                                    });
                                                    clone.querySelectorAll('input.penampakan-hidden, input.sealing-hidden, input.cetakan-hidden, input.logo-halal-hidden, input.dokumen-halal-hidden, input.coa-hidden').forEach((h) => {
                                                        h.value = '';
                                                    });

                                                    container.appendChild(clone);
                                                    syncHeaderToDetails(rowEl);
                                                    syncDokumenToDetails(rowEl);
                                                    updateDetailIndices();

                                                    ensureDetailCollapsible(clone);
                                                    collapseOtherDetailsInRow(rowEl, clone);
                                                }

                                                const rmBtn = e.target.closest('.remove-detail-btn');
                                                if (rmBtn) {
                                                    const detailEl = rmBtn.closest('.detail-item');
                                                    const rowEl = rmBtn.closest('.unified-row');
                                                    if (!detailEl || !rowEl) return;
                                                    const details = rowEl.querySelectorAll('.detail-item');
                                                    if (details.length <= 1) return;
                                                    detailEl.remove();
                                                    syncHeaderToDetails(rowEl);
                                                    syncDokumenToDetails(rowEl);
                                                    updateDetailIndices();
                                                }
                                            });

                                            // Event listener untuk tombol hapus
                                            document.addEventListener('click', function(e) {
                                                if (e.target.closest('.remove-unified-btn')) {
                                                    const row = e.target.closest('.unified-row');
                                                    if (row && !e.target.closest('.remove-unified-btn').disabled) {
                                                        row.remove();
                                                        updateDeleteButtons();
                                                        updateDetailIndices();
                                                    }
                                                }
                                            });

                                            // Initial check
                                            updateDeleteButtons();
                                            updateDetailIndices();

                                            initKemasanCollapses();
                                        });
                                    </script>
                                    
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
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
                                                let width = img.width;
                                                let height = img.height;
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

                                                const newName = (file.name || 'image').replace(/\.[^/.]+$/, '') + '.jpg';
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
                                    
                                    <div class="col-md-12 d-flex justify-content-end mt-3">
                                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Update Data</button>
                                        <!-- <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button> -->
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