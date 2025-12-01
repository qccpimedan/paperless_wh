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
                    <h3>Edit Pemeriksaan Kedatangan Bahan Baku Penunjang</h3>
                    <p class="text-subtitle text-muted">Edit data pemeriksaan kedatangan bahan baku penunjang</p>
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
        
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Form Edit Pemeriksaan Kedatangan Bahan Baku Penunjang</h4>
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

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-bahan-baku.update', $pemeriksaanBahanBaku->uuid) }}" method="POST">
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="jenis_mobil">Jenis & No. Mobil</label>
                                                    <input type="text" id="jenis_mobil" class="form-control @error('jenis_mobil') is-invalid @enderror"
                                                        name="jenis_mobil" value="{{ old('jenis_mobil', $pemeriksaanBahanBaku->jenis_mobil) }}" placeholder="Jenis & No. Mobil">
                                                    @error('jenis_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="no_mobil">No. Mobil</label>
                                                    <input type="text" id="no_mobil" class="form-control @error('no_mobil') is-invalid @enderror"
                                                        name="no_mobil" value="{{ old('no_mobil', $pemeriksaanBahanBaku->no_mobil) }}" placeholder="No. Mobil">
                                                    @error('no_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nama_supir">Nama Supir</label>
                                                    <input type="text" id="nama_supir" class="form-control @error('nama_supir') is-invalid @enderror"
                                                        name="nama_supir" value="{{ old('nama_supir', $pemeriksaanBahanBaku->nama_supir) }}" placeholder="Nama Supir">
                                                    @error('nama_supir')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jenis_pemeriksaan">Jenis Pemeriksaan</label>
                                                    <input type="text" id="jenis_pemeriksaan" class="form-control @error('jenis_pemeriksaan') is-invalid @enderror"
                                                        name="jenis_pemeriksaan" value="{{ old('jenis_pemeriksaan', $pemeriksaanBahanBaku->jenis_pemeriksaan) }}" placeholder="Jenis Pemeriksaan">
                                                    @error('jenis_pemeriksaan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div> -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="segel_gembok" name="segel_gembok" value="1" 
                                                            {{ old('segel_gembok', $pemeriksaanBahanBaku->segel_gembok) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="segel_gembok">
                                                            Segel/Gembok
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
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
                                    </div>

                                    <!-- Informasi Produk -->
                                    <div class="form-section mb-4">
                                        <h5 class="text-primary mb-3">Informasi Produk</h5>
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
                                                    <label for="id_bahan">Nama Bahan</label>
                                                    <select id="id_bahan" class="form-control @error('id_bahan') is-invalid @enderror" name="id_bahan">
                                                        <option value="">Pilih Bahan</option>
                                                        @foreach($bahans as $bahan)
                                                            <option value="{{ $bahan->id }}" {{ old('id_bahan', $pemeriksaanBahanBaku->id_bahan) == $bahan->id ? 'selected' : '' }}>
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

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kondisi_produk">Kondisi Produk</label>
                                                    <select id="kondisi_produk" class="form-control @error('kondisi_produk') is-invalid @enderror" name="kondisi_produk">
                                                        <option value="">Pilih Kondisi Produk</option>
                                                        <option value="Fresh" {{ old('kondisi_produk', $pemeriksaanBahanBaku->kondisi_produk) == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                        <option value="Frozen" {{ old('kondisi_produk', $pemeriksaanBahanBaku->kondisi_produk) == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                        <option value="Dry" {{ old('kondisi_produk', $pemeriksaanBahanBaku->kondisi_produk) == 'Dry' ? 'selected' : '' }}>Dry</option>
                                                        <option value="Minyak" {{ old('kondisi_produk', $pemeriksaanBahanBaku->kondisi_produk) == 'Minyak' ? 'selected' : '' }}>Minyak</option>
                                                    </select>
                                                    @error('kondisi_produk')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- Conditional Fields -->
                                                <div id="conditional_fields">
                                                    <!-- Suhu Mobil - untuk Fresh/Frozen -->
                                                    <div class="form-group" id="suhu_mobil_field" style="display: {{ in_array($pemeriksaanBahanBaku->kondisi_produk, ['Fresh', 'Frozen']) ? 'block' : 'none' }};">
                                                        <label for="suhu_mobil">Suhu Mobil</label>
                                                        <input type="text" id="suhu_mobil" class="form-control @error('suhu_mobil') is-invalid @enderror"
                                                            name="suhu_mobil" value="{{ old('suhu_mobil', $pemeriksaanBahanBaku->suhu_mobil) }}" placeholder="Suhu Mobil">
                                                        @error('suhu_mobil')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Hasil Uji FFA - untuk Minyak -->
                                                    <div class="form-group" id="hasil_uji_ffa_field" style="display: {{ $pemeriksaanBahanBaku->kondisi_produk == 'Minyak' ? 'block' : 'none' }};">
                                                        <label for="hasil_uji_ffa">Hasil Uji FFA</label>
                                                        <input type="text" id="hasil_uji_ffa" class="form-control @error('hasil_uji_ffa') is-invalid @enderror"
                                                            name="hasil_uji_ffa" value="{{ old('hasil_uji_ffa', $pemeriksaanBahanBaku->hasil_uji_ffa) }}" placeholder="Hasil Uji FFA">
                                                        @error('hasil_uji_ffa')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6" id="suhu_produk_field" style="display: {{ in_array($pemeriksaanBahanBaku->kondisi_produk, ['Fresh', 'Frozen']) ? 'block' : 'none' }};">
                                                <div class="form-group">
                                                    <label for="suhu_produk">Suhu Produk</label>
                                                    <input type="text" id="suhu_produk" class="form-control @error('suhu_produk') is-invalid @enderror"
                                                        name="suhu_produk" value="{{ old('suhu_produk', $pemeriksaanBahanBaku->suhu_produk) }}" placeholder="Suhu Produk">
                                                    @error('suhu_produk')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
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
                                                    <option value="Fresh" {{ old('suhu_mobil_type', $pemeriksaanBahanBaku->suhu_mobil_type) == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                    <option value="Frozen" {{ old('suhu_mobil_type', $pemeriksaanBahanBaku->suhu_mobil_type) == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                </select>
                                                @error('suhu_mobil_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="suhu_mobil_input_field" style="display: none;">
                                                <label for="suhu_mobil">Nilai Suhu Mobil (°C)</label>
                                                <input type="text" id="suhu_mobil" class="form-control @error('suhu_mobil') is-invalid @enderror"
                                                    name="suhu_mobil" value="{{ old('suhu_mobil', $pemeriksaanBahanBaku->suhu_mobil) }}" placeholder="Contoh: -18°C atau 4°C">
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
                                                    <option value="Fresh" {{ old('suhu_produk_type', $pemeriksaanBahanBaku->suhu_produk_type) == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                    <option value="Frozen" {{ old('suhu_produk_type', $pemeriksaanBahanBaku->suhu_produk_type) == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                </select>
                                                @error('suhu_produk_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="suhu_produk_input_field" style="display: none;">
                                                <label for="suhu_produk">Nilai Suhu Produk (°C)</label>
                                                <input type="text" id="suhu_produk" class="form-control @error('suhu_produk') is-invalid @enderror"
                                                    name="suhu_produk" value="{{ old('suhu_produk', $pemeriksaanBahanBaku->suhu_produk) }}" placeholder="Contoh: -18°C atau 4°C">
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
                                                    <option value="Fresh" {{ old('kondisi_produk', $pemeriksaanBahanBaku->kondisi_produk) == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                    <option value="Frozen" {{ old('kondisi_produk', $pemeriksaanBahanBaku->kondisi_produk) == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                    <option value="Dry" {{ old('kondisi_produk', $pemeriksaanBahanBaku->kondisi_produk) == 'Dry' ? 'selected' : '' }}>Dry</option>
                                                    <option value="Minyak" {{ old('kondisi_produk', $pemeriksaanBahanBaku->kondisi_produk) == 'Minyak' ? 'selected' : '' }}>Minyak</option>
                                                </select>
                                                @error('kondisi_produk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="kondisi_produk_suhu_field" style="display: none;">
                                                <label for="kondisi_produk_suhu">Suhu Kondisi Produk (°C) <small class="text-muted">(Opsional)</small></label>
                                                <input type="text" id="kondisi_produk_suhu" class="form-control @error('kondisi_produk_suhu') is-invalid @enderror"
                                                    name="kondisi_produk_suhu" value="{{ old('kondisi_produk_suhu', $pemeriksaanBahanBaku->kondisi_produk_suhu) }}" placeholder="Contoh: -18°C, 4°C, 25°C">
                                                @error('kondisi_produk_suhu')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group" id="hasil_uji_ffa_field" style="display: none;">
                                                <label for="hasil_uji_ffa">Hasil Uji FFA <small class="text-muted">(Khusus Minyak)</small></label>
                                                <input type="text" id="hasil_uji_ffa" class="form-control @error('hasil_uji_ffa') is-invalid @enderror"
                                                    name="hasil_uji_ffa" value="{{ old('hasil_uji_ffa', $pemeriksaanBahanBaku->hasil_uji_ffa) }}" placeholder="Hasil Uji FFA">
                                                @error('hasil_uji_ffa')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Kondisi Mobil Pengangkut - 11 Items -->
                                    <div class="form-section mb-4">
                                        <h5 class="text-primary mb-3">Kondisi Mobil Pengangkut</h5>
                                        <div class="row">
                                            @php
                                                $kondisiMobilItems = [
                                                    'bersih' => 'Bersih',
                                                    'bebas_hama' => 'Bebas dari hama', 
                                                    'tidak_kondensasi' => 'Tidak Kondensasi',
                                                    'bebas_produk_halal' => 'Bebas dari Produk Non Halal',
                                                    'tidak_berbau' => 'Tidak Berbau Menyimpang',
                                                    'tidak_ada_sampah' => 'Tidak ada sampah',
                                                    'tidak_ada_mikroba' => 'Tidak ada pertumbuhan mikroba',
                                                    'lampu_cover_utuh' => 'Lampu dan Cover tidak pecah',
                                                    'pallet_utuh' => 'Pallet / Alas Utuh',
                                                    'tertutup_rapat' => 'Tertutup rapat/tidak bocor',
                                                    'bebas_kontaminan' => 'Bebas dari Kontaminan'
                                                ];
                                            @endphp
                                            @foreach($kondisiMobilItems as $key => $label)
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label"><strong>{{ $label }}</strong></label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="kondisi_mobil[{{ $key }}]" id="{{ $key }}_ya" value="1" 
                                                                {{ (old('kondisi_mobil.'.$key, $pemeriksaanBahanBaku->kondisi_mobil[$key] ?? false)) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="{{ $key }}_ya">Ya ✓</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="kondisi_mobil[{{ $key }}]" id="{{ $key }}_tidak" value="0" 
                                                                {{ !(old('kondisi_mobil.'.$key, $pemeriksaanBahanBaku->kondisi_mobil[$key] ?? false)) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="{{ $key }}_tidak">Tidak ✗</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
                                                        name="spesifikasi" rows="3" placeholder="Spesifikasi">{{ old('spesifikasi', $pemeriksaanBahanBaku->spesifikasi) }}</textarea>
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
                                                        @foreach($produsens as $produsen)
                                                            <option value="{{ $produsen->nama_produsen }}" {{ old('produsen', $pemeriksaanBahanBaku->produsen) == $produsen->nama_produsen ? 'selected' : '' }}>
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
                                                        @foreach($countries as $code => $name)
                                                            <option value="{{ $name }}" {{ old('negara_produsen', $pemeriksaanBahanBaku->negara_produsen) == $name ? 'selected' : '' }}>
                                                                {{ $name }}
                                                            </option>
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
                                                        @foreach($distributors as $distributor)
                                                            <option value="{{ $distributor->nama_distributor }}" {{ old('distributor', $pemeriksaanBahanBaku->distributor) == $distributor->nama_distributor ? 'selected' : '' }}>
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
                                                        name="kode_produksi" value="{{ old('kode_produksi', $pemeriksaanBahanBaku->kode_produksi) }}" placeholder="Kode Produksi">
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
                                                        name="expire_date" value="{{ old('expire_date', $pemeriksaanBahanBaku->expire_date ? $pemeriksaanBahanBaku->expire_date->format('Y-m-d') : '') }}">
                                                    @error('expire_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jumlah_datang">Jumlah Barang Datang (kg)</label>
                                                    <input type="text" id="jumlah_datang" class="form-control @error('jumlah_datang') is-invalid @enderror"
                                                        name="jumlah_datang" value="{{ old('jumlah_datang', $pemeriksaanBahanBaku->jumlah_datang) }}" placeholder="Jumlah Barang Datang">
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
                                                        name="jumlah_sampling" value="{{ old('jumlah_sampling', $pemeriksaanBahanBaku->jumlah_sampling) }}" placeholder="Jumlah Sampling">
                                                    @error('jumlah_sampling')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kondisi Fisik - 4 Items -->
                                    <div class="form-section mb-4">
                                        <h5 class="text-primary mb-3">Kondisi Fisik</h5>
                                        <div class="row">
                                            @php
                                                $kondisiFisikItems = [
                                                    'kemasan' => 'Kemasan',
                                                    'warna' => 'Warna',
                                                    'benda_asing' => 'Benda Asing/Kotoran',
                                                    'aroma' => 'Aroma'
                                                ];
                                            @endphp
                                            @foreach($kondisiFisikItems as $key => $label)
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label"><strong>{{ $label }}</strong></label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="kondisi_fisik[{{ $key }}]" id="{{ $key }}_ya" value="1" 
                                                                {{ old('kondisi_fisik.'.$key, ($pemeriksaanBahanBaku->kondisi_fisik[$key] ?? null) ? '1' : null) == '1' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="{{ $key }}_ya">Ya ✓</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="kondisi_fisik[{{ $key }}]" id="{{ $key }}_tidak" value="0" 
                                                                {{ old('kondisi_fisik.'.$key, ($pemeriksaanBahanBaku->kondisi_fisik[$key] ?? null) ? '1' : '0') == '0' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="{{ $key }}_tidak">Tidak ✗</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
                                                        <input class="form-check-input" type="radio" name="logo_halal" id="logo_halal_ya" value="1" 
                                                            {{ old('logo_halal', ($pemeriksaanBahanBaku->logo_halal ?? null) ? '1' : null) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="logo_halal_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="logo_halal" id="logo_halal_tidak" value="0" 
                                                            {{ old('logo_halal', ($pemeriksaanBahanBaku->logo_halal ?? null) ? '1' : '0') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="logo_halal_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="dokumen_halal" id="dokumen_halal_ya" value="1" 
                                                            {{ old('dokumen_halal', ($pemeriksaanBahanBaku->dokumen_halal ?? null) ? '1' : null) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="dokumen_halal_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="dokumen_halal" id="dokumen_halal_tidak" value="0" 
                                                            {{ old('dokumen_halal', ($pemeriksaanBahanBaku->dokumen_halal ?? null) ? '1' : '0') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="dokumen_halal_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>COA</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="coa" id="coa_ya" value="1" 
                                                            {{ old('coa', ($pemeriksaanBahanBaku->coa ?? null) ? '1' : null) == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="coa_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="coa" id="coa_tidak" value="0" 
                                                            {{ old('coa', ($pemeriksaanBahanBaku->coa ?? null) ? '1' : '0') == '0' ? 'checked' : '' }}>
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
                                                        <option value="Hold" {{ old('status', $pemeriksaanBahanBaku->status) == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                        <option value="Release" {{ old('status', $pemeriksaanBahanBaku->status) == 'Release' ? 'selected' : '' }}>Release</option>
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
                                                        name="keterangan" rows="3" placeholder="Keterangan">{{ old('keterangan', $pemeriksaanBahanBaku->keterangan) }}</textarea>
                                                    @error('keterangan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group text-end">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('pemeriksaan-bahan-baku.index') }}" class="btn btn-secondary">Batal</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
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
        document.getElementById('suhu_mobil').value = '';
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
        document.getElementById('suhu_produk').value = '';
    }
});

// 3. Kondisi Produk Conditional Logic
document.getElementById('kondisi_produk').addEventListener('change', function() {
    const kondisiProduk = this.value;
    
    // Hide all conditional fields first
    document.getElementById('kondisi_produk_suhu_field').style.display = 'none';
    // document.getElementById('hasil_uji_ffa_field').style.display = 'none';
    // document.getElementById('bukti_kebersihan_tanki_field').style.display = 'none';
    
    // Clear all inputs
    document.getElementById('kondisi_produk_suhu').value = '';
    // document.getElementById('hasil_uji_ffa').value = '';
    // document.getElementById('bukti_kebersihan_tanki').value = '';
    
    // Show relevant fields based on selection
    if (kondisiProduk === 'Fresh' || kondisiProduk === 'Frozen' || kondisiProduk === 'Dry') {
        document.getElementById('kondisi_produk_suhu_field').style.display = 'block';
    } else if (kondisiProduk === 'Minyak') {
        document.getElementById('kondisi_produk_suhu_field').style.display = 'block';
        // document.getElementById('hasil_uji_ffa_field').style.display = 'block';
        // document.getElementById('bukti_kebersihan_tanki_field').style.display = 'block';
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
                                     