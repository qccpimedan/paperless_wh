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
                    <h3>Edit Pemeriksaan Loading Kendaraan</h3>
                    <p class="text-subtitle text-muted">Edit pemeriksaan loading kendaraan yang sudah ada</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-loading-kendaraan.index') }}">Pemeriksaan Loading Kendaraan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Pemeriksaan</li>
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
                            <h4 class="card-title">Form Edit Pemeriksaan Loading Kendaraan</h4>
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

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-loading-kendaraan.update', $pemeriksaanLoadingKendaraan->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                                                                        <!-- Kondisi Kebersihan Mobil -->
                                            <div class="col-md-6">
                                                <label><strong>Kondisi Kebersihan Mobil <span class="text-danger">*</span></strong></label>
                                                @php
                                                    $kebersihanMobil = json_decode($pemeriksaanLoadingKendaraan->kondisi_kebersihan_mobil, true) ?? [];
                                                @endphp
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>1. Berdebu, Kondensasi</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[berdebu]" id="berdebu_ya" value="1" {{ ($kebersihanMobil['berdebu'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="berdebu_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[berdebu]" id="berdebu_tidak" value="0" {{ ($kebersihanMobil['berdebu'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="berdebu_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>2. Noda (Karat, cat, tinta, oli, Asap Kendaraan), Sampah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[noda]" id="noda_ya" value="1" {{ ($kebersihanMobil['noda'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="noda_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[noda]" id="noda_tidak" value="0" {{ ($kebersihanMobil['noda'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="noda_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>3. Terdapat Pertumbuhan Mikroorganisme (Jamur, Bau Busuk, Bau Menyimpang)</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[mikroorganisme]" id="mikroorganisme_ya" value="1" {{ ($kebersihanMobil['mikroorganisme'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="mikroorganisme_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[mikroorganisme]" id="mikroorganisme_tidak" value="0" {{ ($kebersihanMobil['mikroorganisme'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="mikroorganisme_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>4. Pallet, Pintu, Langit-langit, Dinding Kotor</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[pallet_kotor]" id="pallet_ya" value="1" {{ ($kebersihanMobil['pallet_kotor'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pallet_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[pallet_kotor]" id="pallet_tidak" value="0" {{ ($kebersihanMobil['pallet_kotor'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pallet_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>5. Terdapat Aktivitas Binatang (Tikus, Kecoa, Lalat, Belatung, Hama)</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[aktivitas_binatang]" id="binatang_ya" value="1" {{ ($kebersihanMobil['aktivitas_binatang'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="binatang_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[aktivitas_binatang]" id="binatang_tidak" value="0" {{ ($kebersihanMobil['aktivitas_binatang'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="binatang_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kondisi Mobil -->
                                            <div class="col-md-6">
                                                <label><strong>Kondisi Mobil <span class="text-danger">*</span></strong></label>
                                                @php
                                                    $kondisiMobil = json_decode($pemeriksaanLoadingKendaraan->kondisi_mobil, true) ?? [];
                                                @endphp
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>1. Kaca Mobil Pecah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[kaca_pecah]" id="kaca_ya" value="1" {{ ($kondisiMobil['kaca_pecah'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="kaca_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[kaca_pecah]" id="kaca_tidak" value="0" {{ ($kondisiMobil['kaca_pecah'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="kaca_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>2. Dinding Mobil Rusak (Pecah)/Langit-langit Rusak/Pintu Rusak</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[dinding_rusak]" id="dinding_ya" value="1" {{ ($kondisiMobil['dinding_rusak'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="dinding_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[dinding_rusak]" id="dinding_tidak" value="0" {{ ($kondisiMobil['dinding_rusak'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="dinding_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>3. Lampu Dalam Box Pecah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_pecah]" id="lampu_ya" value="1" {{ ($kondisiMobil['lampu_pecah'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="lampu_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_pecah]" id="lampu_tidak" value="0" {{ ($kondisiMobil['lampu_pecah'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="lampu_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>4. Karet Pintu Rusak</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[karet_pintu_rusak]" id="karet_ya" value="1" {{ ($kondisiMobil['karet_pintu_rusak'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="karet_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[karet_pintu_rusak]" id="karet_tidak" value="0" {{ ($kondisiMobil['karet_pintu_rusak'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="karet_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>5. Pintu Rusak</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[pintu_rusak]" id="pintu_rusak_ya" value="1" {{ ($kondisiMobil['pintu_rusak'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pintu_rusak_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[pintu_rusak]" id="pintu_rusak_tidak" value="0" {{ ($kondisiMobil['pintu_rusak'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pintu_rusak_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>6. Seal Tidak Utuh</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[seal_tidak_utuh]" id="seal_ya" value="1" {{ ($kondisiMobil['seal_tidak_utuh'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="seal_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[seal_tidak_utuh]" id="seal_tidak" value="0" {{ ($kondisiMobil['seal_tidak_utuh'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="seal_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>7. Terdapat Celah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[terdapat_celah]" id="celah_ya" value="1" {{ ($kondisiMobil['terdapat_celah'] ?? null) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="celah_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[terdapat_celah]" id="celah_tidak" value="0" {{ ($kondisiMobil['terdapat_celah'] ?? null) == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="celah_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tanggal -->
                                            <div class="col-md-6">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                    name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::parse($pemeriksaanLoadingKendaraan->tanggal)->format('Y-m-d')) }}" required>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Ekspedisi -->
                                            <div class="col-md-6">
                                                <label for="id_ekspedisi">Ekspedisi <span class="text-danger">*</span></label>
                                                <select id="id_ekspedisi" class="form-select @error('id_ekspedisi') is-invalid @enderror"
                                                    name="id_ekspedisi" required>
                                                    <option value="">-- Pilih Ekspedisi --</option>
                                                    @foreach($ekspedisis as $ekspedisi)
                                                        <option value="{{ $ekspedisi->id }}" {{ old('id_ekspedisi', $pemeriksaanLoadingKendaraan->id_ekspedisi) == $ekspedisi->id ? 'selected' : '' }}>
                                                            {{ $ekspedisi->nama_ekspedisi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_ekspedisi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Kendaraan -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_kendaraan">Jenis &amp; No Kendaraan</label>
                                                    @php
                                                        $kendaraanSelected = old('id_kendaraan', $pemeriksaanLoadingKendaraan->id_kendaraan);
                                                        $isKendaraanManual = (!$kendaraanSelected && ($pemeriksaanLoadingKendaraan->jenis_kendaraan_manual || $pemeriksaanLoadingKendaraan->no_kendaraan_manual)) || $kendaraanSelected == 'other';
                                                    @endphp
                                                    <select id="id_kendaraan" class="form-select @error('id_kendaraan') is-invalid @enderror" name="id_kendaraan">
                                                        <option value="">-- Pilih Kendaraan --</option>
                                                        <option value="other" {{ $isKendaraanManual ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                        @foreach($kendaraans as $kendaraan)
                                                            <option value="{{ $kendaraan->id }}" {{ !$isKendaraanManual && $kendaraanSelected == $kendaraan->id ? 'selected' : '' }}>
                                                                {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->no_kendaraan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_kendaraan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror

                                                    <!-- Input manual yang awalnya disembunyikan -->
                                                    <div id="manual_kendaraan_input" class="mt-2" style="display: {{ $isKendaraanManual ? 'block' : 'none' }};">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="jenis_kendaraan_manual">Jenis Kendaraan</label>
                                                                    <input type="text" id="jenis_kendaraan_manual" class="form-control @error('jenis_kendaraan_manual') is-invalid @enderror" 
                                                                        name="jenis_kendaraan_manual" value="{{ old('jenis_kendaraan_manual', $pemeriksaanLoadingKendaraan->jenis_kendaraan_manual) }}" placeholder="Masukkan jenis kendaraan">
                                                                    @error('jenis_kendaraan_manual')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="no_kendaraan_manual">No Kendaraan</label>
                                                                    <input type="text" id="no_kendaraan_manual" class="form-control @error('no_kendaraan_manual') is-invalid @enderror" 
                                                                        name="no_kendaraan_manual" value="{{ old('no_kendaraan_manual', $pemeriksaanLoadingKendaraan->no_kendaraan_manual) }}" placeholder="Masukkan nomor kendaraan">
                                                                    @error('no_kendaraan_manual')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tujuan Pengiriman -->
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="id_tujuan_pengiriman">Tujuan Pengiriman <span class="text-danger">*</span></label>
                                                    @php
                                                        $tujuanSelected = old('id_tujuan_pengiriman', $pemeriksaanLoadingKendaraan->id_tujuan_pengiriman);
                                                        $isTujuanManual = (!$tujuanSelected && $pemeriksaanLoadingKendaraan->nama_tujuan_manual) || $tujuanSelected == 'lainnya';
                                                    @endphp
                                                    <select id="id_tujuan_pengiriman" class="form-select @error('id_tujuan_pengiriman') is-invalid @enderror"
                                                        name="id_tujuan_pengiriman" required>
                                                        <option value="">-- Pilih Tujuan --</option>
                                                        <option value="lainnya" {{ $isTujuanManual ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                        @foreach($tujuanPengirimens as $tujuan)
                                                            <option value="{{ $tujuan->id }}" {{ $tujuanSelected == $tujuan->id ? 'selected' : '' }}>
                                                                {{ $tujuan->nama_tujuan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_tujuan_pengiriman')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div id="manual_tujuan_container" class="mt-2" style="display: {{ $isTujuanManual ? 'block' : 'none' }};">
                                                    <div class="form-group">
                                                        <label for="nama_tujuan_manual">Nama Tujuan Manual <span class="text-danger">*</span></label>
                                                        <input type="text" id="nama_tujuan_manual" name="nama_tujuan_manual" 
                                                            class="form-control @error('nama_tujuan_manual') is-invalid @enderror"
                                                            value="{{ old('nama_tujuan_manual', $pemeriksaanLoadingKendaraan->nama_tujuan_manual) }}" 
                                                            placeholder="Masukkan Nama Tujuan">
                                                        @error('nama_tujuan_manual')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Shift -->
                                            <div class="col-md-6">
                                                <label for="id_shift">Shift</label>
                                                <select id="id_shift" class="form-select @error('id_shift') is-invalid @enderror"
                                                    name="id_shift">
                                                    <option value="">-- Pilih Shift --</option>
                                                    @foreach($shifts as $shift)
                                                        <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanLoadingKendaraan->id_shift) == $shift->id ? 'selected' : '' }}>
                                                            {{ $shift->shift }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_shift')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <!-- Std Precooling -->
                                            <div class="col-md-6">
                                                <label for="id_std_precooling">Std Precooling <span class="text-danger">*</span></label>
                                                <select id="id_std_precooling" class="form-select @error('id_std_precooling') is-invalid @enderror"
                                                    name="id_std_precooling" required>
                                                    <option value="">-- Pilih Std Precooling --</option>
                                                    @foreach($stdPrecoolings as $std)
                                                        <option value="{{ $std->id }}" {{ old('id_std_precooling', $pemeriksaanLoadingKendaraan->id_std_precooling) == $std->id ? 'selected' : '' }}>
                                                            {{ $std->nama_std_precooling }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_std_precooling')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Jam Mulai -->
                                            <div class="col-md-6">
                                                <label for="jam_mulai">Jam Mulai <span class="text-danger">*</span></label>
                                                <input type="time" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror"
                                                    name="jam_mulai" value="{{ old('jam_mulai', $pemeriksaanLoadingKendaraan->jam_mulai) }}" required>
                                                @error('jam_mulai')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Jam Selesai -->
                                            <div class="col-md-6">
                                                <label for="jam_selesai">Jam Selesai <span class="text-danger">*</span></label>
                                                <input type="time" id="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror"
                                                    name="jam_selesai" value="{{ old('jam_selesai', $pemeriksaanLoadingKendaraan->jam_selesai) }}" required>
                                                @error('jam_selesai')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Suhu Precooling -->
                                            <div class="col-md-6">
                                                <label for="suhu_precooling">Suhu Precooling (°C) <span class="text-danger">*</span></label>
                                                <input type="text" id="suhu_precooling" class="form-control @error('suhu_precooling') is-invalid @enderror"
                                                    name="suhu_precooling" placeholder="Contoh: -18°C" value="{{ old('suhu_precooling', $pemeriksaanLoadingKendaraan->suhu_precooling) }}" required>
                                                @error('suhu_precooling')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Segel / Gembok -->
                                            @php
                                                // segel_gembok disimpan sebagai boolean: true=segel, false=gembok
                                                $segelGembokValue = old('segel_gembok',
                                                    $pemeriksaanLoadingKendaraan->segel_gembok === true ? 'segel' :
                                                    ($pemeriksaanLoadingKendaraan->segel_gembok === false && $pemeriksaanLoadingKendaraan->getOriginal('segel_gembok') !== null ? 'gembok' : null)
                                                );
                                            @endphp
                                            <div class="col-md-12 mt-2">
                                                <h5 class="text-primary mb-3 mt-2">Segel & Informasi Kendaraan</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>Segel/Gembok</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="segel_option" name="segel_gembok" value="segel" {{ $segelGembokValue == 'segel' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="segel_option">Segel</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="gembok_option" name="segel_gembok" value="gembok" {{ $segelGembokValue == 'gembok' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="gembok_option">Gembok</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="no_segel_container" style="display: {{ $segelGembokValue == 'segel' ? 'block' : 'none' }};">
                                                <div class="form-group">
                                                    <label for="no_segel">No. Segel</label>
                                                    <input type="text" id="no_segel" class="form-control @error('no_segel') is-invalid @enderror"
                                                        name="no_segel" value="{{ old('no_segel', $pemeriksaanLoadingKendaraan->no_segel) }}" placeholder="Nomor Segel">
                                                    @error('no_segel')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Keterangan -->
                                            <div class="col-md-12">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                                    name="keterangan" rows="3" placeholder="Masukkan keterangan tambahan">{{ old('keterangan', $pemeriksaanLoadingKendaraan->keterangan) }}</textarea>
                                                @error('keterangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Buttons -->
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <a href="{{ route('pemeriksaan-loading-kendaraan.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                            </div>
                                        </div>
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
    // Helper: inisialisasi Choices.js dengan konfigurasi search yang baik
    function initChoicesSelect(selectEl, placeholderText) {
        if (!selectEl || typeof Choices === 'undefined') return null;

        Array.from(selectEl.options).forEach(function(opt) {
            opt.text = opt.text.trim();
        });

        return new Choices(selectEl, {
            searchEnabled: true,
            searchPlaceholderValue: 'Cari...',
            searchFields: ['label', 'value'],
            itemSelectText: '',
            noResultsText: 'Tidak ada hasil ditemukan',
            noChoicesText: 'Tidak ada pilihan tersedia',
            shouldSort: true,
            sorter: function(a, b) {
                if (a.value === 'lainnya') return -1;
                if (b.value === 'lainnya') return 1;
                return 0;
            },
            placeholder: true,
            placeholderValue: placeholderText || 'Pilih...',
            searchResultLimit: 100,
            fuseOptions: {
                includeScore: true,
                threshold: 0.3,
                distance: 100,
                ignoreLocation: true,
                matchAllTokens: false
            }
        });
    }

    function toggleManualTujuan(value) {
        const manualTujuanContainer = document.getElementById('manual_tujuan_container');
        if (!manualTujuanContainer) return;
        manualTujuanContainer.style.display = (value === 'lainnya') ? 'block' : 'none';
        
        if (value !== 'lainnya') {
            const inputManual = document.getElementById('nama_tujuan_manual');
            if (inputManual) inputManual.value = '';
        }
    }

    function toggleManualKendaraan(value) {
        const manualKendaraanInput = document.getElementById('manual_kendaraan_input');
        if (!manualKendaraanInput) return;
        manualKendaraanInput.style.display = (value === 'other') ? 'block' : 'none';
        
        if (value !== 'other') {
            const jenisInput = document.getElementById('jenis_kendaraan_manual');
            const noInput = document.getElementById('no_kendaraan_manual');
            if (jenisInput) jenisInput.value = '';
            if (noInput) noInput.value = '';
        }
    }

    // Inisialisasi dropdown
    initChoicesSelect(document.getElementById('id_ekspedisi'), '-- Pilih Ekspedisi --');
    initChoicesSelect(document.getElementById('id_shift'), '-- Pilih Shift --');
    
    const kendaraanSelectEl = document.getElementById('id_kendaraan');
    if (kendaraanSelectEl) {
        toggleManualKendaraan(kendaraanSelectEl.value);
        initChoicesSelect(kendaraanSelectEl, '-- Pilih Kendaraan --');
        kendaraanSelectEl.addEventListener('change', function() {
            toggleManualKendaraan(this.value);
        });
    }

    const tujuanSelectEl = document.getElementById('id_tujuan_pengiriman');
    if (tujuanSelectEl) {
        toggleManualTujuan(tujuanSelectEl.value);
        initChoicesSelect(tujuanSelectEl, '-- Pilih Tujuan --');
        tujuanSelectEl.addEventListener('change', function() {
            toggleManualTujuan(this.value);
        });
    }

    initChoicesSelect(document.getElementById('id_std_precooling'), '-- Pilih Std Precooling --');

    // Segel/Gembok Toggle
    document.querySelectorAll('input[name="segel_gembok"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const container = document.getElementById('no_segel_container');
            if (this.value === 'segel') {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
                const noSegelInput = document.getElementById('no_segel');
                if (noSegelInput) noSegelInput.value = '';
            }
        });
    });
});
</script>
@endsection