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
                    <h3>Input Pemeriksaan Loading Kendaraan</h3>
                    <p class="text-subtitle text-muted">Tambah pemeriksaan loading kendaraan baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-loading-kendaraan.index') }}">Pemeriksaan Loading Kendaraan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Pemeriksaan</li>
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
                            <h4 class="card-title">Form Input Pemeriksaan Loading Kendaraan</h4>
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

                                <form id="form-pemeriksaan-loading-kendaraan" data-autosave="true" class="form form-horizontal" action="{{ route('pemeriksaan-loading-kendaraan.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                        <!-- Kondisi Kebersihan Mobil -->
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <label class="mb-0"><strong>Kondisi Kebersihan Mobil <span class="text-danger">*</span></strong></label>
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="checkSemua('kondisi_kebersihan_mobil', 1)">Centang Ya</button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="checkSemua('kondisi_kebersihan_mobil', 0)">Centang Tidak</button>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>1. Berdebu, Kondensasi</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[berdebu]" id="berdebu_ya" value="1" {{ old('kondisi_kebersihan_mobil.berdebu') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="berdebu_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[berdebu]" id="berdebu_tidak" value="0" {{ old('kondisi_kebersihan_mobil.berdebu') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="berdebu_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>2. Noda (Karat, cat, tinta, oli, Asap Kendaraan), Sampah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[noda]" id="noda_ya" value="1" {{ old('kondisi_kebersihan_mobil.noda') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="noda_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[noda]" id="noda_tidak" value="0" {{ old('kondisi_kebersihan_mobil.noda') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="noda_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>3. Terdapat Pertumbuhan Mikroorganisme (Jamur, Bau Busuk, Bau Menyimpang)</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[mikroorganisme]" id="mikroorganisme_ya" value="1" {{ old('kondisi_kebersihan_mobil.mikroorganisme') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="mikroorganisme_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[mikroorganisme]" id="mikroorganisme_tidak" value="0" {{ old('kondisi_kebersihan_mobil.mikroorganisme') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="mikroorganisme_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>4. Pallet, Pintu, Langit-langit, Dinding Kotor</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[pallet_kotor]" id="pallet_ya" value="1" {{ old('kondisi_kebersihan_mobil.pallet_kotor') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pallet_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[pallet_kotor]" id="pallet_tidak" value="0" {{ old('kondisi_kebersihan_mobil.pallet_kotor') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pallet_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>5. Terdapat Aktivitas Binatang (Tikus, Kecoa, Lalat, Belatung, Hama)</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[aktivitas_binatang]" id="binatang_ya" value="1" {{ old('kondisi_kebersihan_mobil.aktivitas_binatang') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="binatang_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_kebersihan_mobil[aktivitas_binatang]" id="binatang_tidak" value="0" {{ old('kondisi_kebersihan_mobil.aktivitas_binatang') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="binatang_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kondisi Mobil -->
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <label class="mb-0"><strong>Kondisi Mobil <span class="text-danger">*</span></strong></label>
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="checkSemua('kondisi_mobil', 1)">Centang Ya</button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="checkSemua('kondisi_mobil', 0)">Centang Tidak</button>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>1. Kaca Mobil Pecah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[kaca_pecah]" id="kaca_ya" value="1" {{ old('kondisi_mobil.kaca_pecah') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="kaca_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[kaca_pecah]" id="kaca_tidak" value="0" {{ old('kondisi_mobil.kaca_pecah') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="kaca_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>2. Dinding Mobil Rusak (Pecah)/Langit-langit Rusak/Pintu Rusak</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[dinding_rusak]" id="dinding_ya" value="1" {{ old('kondisi_mobil.dinding_rusak') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="dinding_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[dinding_rusak]" id="dinding_tidak" value="0" {{ old('kondisi_mobil.dinding_rusak') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="dinding_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>3. Lampu Dalam Box Pecah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_pecah]" id="lampu_ya" value="1" {{ old('kondisi_mobil.lampu_pecah') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="lampu_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_pecah]" id="lampu_tidak" value="0" {{ old('kondisi_mobil.lampu_pecah') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="lampu_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>4. Karet Pintu Rusak</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[karet_pintu_rusak]" id="karet_ya" value="1" {{ old('kondisi_mobil.karet_pintu_rusak') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="karet_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[karet_pintu_rusak]" id="karet_tidak" value="0" {{ old('kondisi_mobil.karet_pintu_rusak') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="karet_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>5. Pintu Rusak</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[pintu_rusak]" id="pintu_rusak_ya" value="1" {{ old('kondisi_mobil.pintu_rusak') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pintu_rusak_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[pintu_rusak]" id="pintu_rusak_tidak" value="0" {{ old('kondisi_mobil.pintu_rusak') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pintu_rusak_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>6. Seal Tidak Utuh</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[seal_tidak_utuh]" id="seal_ya" value="1" {{ old('kondisi_mobil.seal_tidak_utuh') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="seal_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[seal_tidak_utuh]" id="seal_tidak" value="0" {{ old('kondisi_mobil.seal_tidak_utuh') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="seal_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>7. Terdapat Celah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[terdapat_celah]" id="celah_ya" value="1" {{ old('kondisi_mobil.terdapat_celah') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="celah_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[terdapat_celah]" id="celah_tidak" value="0" {{ old('kondisi_mobil.terdapat_celah') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="celah_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tanggal -->
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                    <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                        name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                                    @error('tanggal')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Shift -->
                                            <div class="col-md-6">
                                                <label for="id_shift">Shift</label>
                                                <select id="id_shift" class="form-select @error('id_shift') is-invalid @enderror"
                                                    name="id_shift">
                                                    <option value="">-- Pilih Shift --</option>
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
                                            {{-- ===== DYNAMIC KENDARAAN ENTRIES ===== --}}
                                            <div class="col-md-12 mt-3 mb-2">
                                                <h5 class="text-primary"><i class="bi bi-truck me-2"></i>Data Kendaraan</h5>
                                                <hr class="mt-2 mb-3">
                                            </div>

                                            <div id="kendaraan-entries-container" class="col-md-12">
                                                {{-- Entry pertama (index 0) --}}
                                                <div class="kendaraan-entry card border mb-3" data-index="0">
                                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                                        <span class="fw-bold text-primary entry-label"><i class="bi bi-truck me-1"></i> Kendaraan #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-entry" style="display:none;" title="Hapus entry ini">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-3">
                                                            {{-- Ekspedisi --}}
                                                            <div class="col-md-6">
                                                                <label>Ekspedisi <span class="text-danger">*</span></label>
                                                                <select class="form-select entry-ekspedisi" name="entries[0][id_ekspedisi]" required>
                                                                    <option value="">-- Pilih Ekspedisi --</option>
                                                                    <option value="other">-- Lainnya (Input Manual) --</option>
                                                                    @foreach($ekspedisis as $ekspedisi)
                                                                        <option value="{{ $ekspedisi->id }}" {{ old('entries.0.id_ekspedisi') == $ekspedisi->id ? 'selected' : '' }}>{{ $ekspedisi->nama_ekspedisi }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="manual-ekspedisi-input mt-2" style="display:none;">
                                                                    <input type="text" class="form-control" name="entries[0][nama_ekspedisi_manual]" placeholder="Masukkan nama ekspedisi">
                                                                </div>
                                                            </div>

                                                            {{-- Kendaraan --}}
                                                            <div class="col-md-6">
                                                                <label>Jenis &amp; No Kendaraan</label>
                                                                <select class="form-select entry-kendaraan" name="entries[0][id_kendaraan]">
                                                                    <option value="">-- Pilih Kendaraan --</option>
                                                                    <option value="other">-- Lainnya (Input Manual) --</option>
                                                                    @foreach($kendaraans as $kendaraan)
                                                                        <option value="{{ $kendaraan->id }}">{{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->no_kendaraan }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="manual-kendaraan-input mt-2" style="display:none;">
                                                                    <div class="row g-2">
                                                                        <div class="col-6">
                                                                            <input type="text" class="form-control" name="entries[0][jenis_kendaraan_manual]" placeholder="Jenis Kendaraan">
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <input type="text" class="form-control" name="entries[0][no_kendaraan_manual]" placeholder="No Kendaraan">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- Tujuan Pengiriman --}}
                                                            <div class="col-md-6">
                                                                <label>Tujuan Pengiriman <span class="text-danger">*</span></label>
                                                                <select class="form-select entry-tujuan" name="entries[0][id_tujuan_pengiriman]" required>
                                                                    <option value="">-- Pilih Tujuan --</option>
                                                                    <option value="lainnya">-- Lainnya (Input Manual) --</option>
                                                                    @foreach($tujuanPengirimens as $tujuan)
                                                                        <option value="{{ $tujuan->id }}">{{ $tujuan->nama_tujuan }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="manual-tujuan-input mt-2" style="display:none;">
                                                                    <input type="text" class="form-control" name="entries[0][nama_tujuan_manual]" placeholder="Masukkan tujuan pengiriman">
                                                                </div>
                                                            </div>

                                                            {{-- Std Precooling --}}
                                                            <div class="col-md-6">
                                                                <label>Std Precooling <span class="text-danger">*</span></label>
                                                                <select class="form-select entry-std-precooling" name="entries[0][id_std_precooling]" required>
                                                                    <option value="">-- Pilih Std Precooling --</option>
                                                                    @foreach($stdPrecoolings as $std)
                                                                        <option value="{{ $std->id }}">{{ $std->nama_std_precooling }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            {{-- Jam Mulai --}}
                                                            <div class="col-md-4">
                                                                <label>Jam Mulai <span class="text-danger">*</span></label>
                                                                <input type="time" class="form-control" name="entries[0][jam_mulai]" required>
                                                            </div>

                                                            {{-- Jam Selesai --}}
                                                            <div class="col-md-4">
                                                                <label>Jam Selesai <span class="text-danger">*</span></label>
                                                                <input type="time" class="form-control" name="entries[0][jam_selesai]" required>
                                                            </div>

                                                            {{-- Suhu Precooling --}}
                                                            <div class="col-md-4">
                                                                <label>Suhu Precooling (°C) <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="entries[0][suhu_precooling]" placeholder="Contoh: -18°C" required>
                                                            </div>

                                                            {{-- Segel / Gembok --}}
                                                            <div class="col-md-12">
                                                                <label class="fw-bold text-primary">Segel &amp; Informasi Kendaraan</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="fw-semibold">Segel/Gembok</label>
                                                                <div class="d-flex gap-3 mt-1">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input entry-segel" type="radio" name="entries[0][segel_gembok]" value="segel" id="segel_0">
                                                                        <label class="form-check-label" for="segel_0">Segel</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input entry-segel" type="radio" name="entries[0][segel_gembok]" value="gembok" id="gembok_0">
                                                                        <label class="form-check-label" for="gembok_0">Gembok</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 no-segel-container" style="display:none;">
                                                                <label>No. Segel</label>
                                                                <input type="text" class="form-control" name="entries[0][no_segel]" placeholder="Nomor Segel">
                                                            </div>

                                                            {{-- Keterangan --}}
                                                            <div class="col-md-12">
                                                                <label>Keterangan</label>
                                                                <textarea class="form-control" name="entries[0][keterangan]" rows="2" placeholder="Keterangan tambahan"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Buttons & Add Entry --}}
                                            <div class="col-md-12 d-flex justify-content-between mt-3 mb-2">
                                                <button type="button" id="btn-add-entry" class="btn btn-success btn-sm align-self-start">
                                                    <i class="bi bi-plus-circle me-1"></i> Tambah Kendaraan
                                                </button>
                                                <div>
                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Simpan Data</button>
                                                    <a href="{{ route('pemeriksaan-loading-kendaraan.index') }}" class="btn btn-light-secondary me-1 mb-1 btn-kembali-confirm">Kembali</a>
                                                </div>
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

{{-- Template tersembunyi untuk clone entry baru --}}
<template id="entry-template">
    <div class="kendaraan-entry card border mb-3" data-index="__IDX__">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
            <span class="fw-bold text-primary entry-label"><i class="bi bi-truck me-1"></i> Kendaraan #__NUM__</span>
            <button type="button" class="btn btn-danger btn-sm btn-remove-entry" title="Hapus entry ini">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Ekspedisi <span class="text-danger">*</span></label>
                    <select class="form-select entry-ekspedisi" name="entries[__IDX__][id_ekspedisi]" required>
                        <option value="">-- Pilih Ekspedisi --</option>
                        <option value="other">-- Lainnya (Input Manual) --</option>
                        @foreach($ekspedisis as $ekspedisi)
                            <option value="{{ $ekspedisi->id }}">{{ $ekspedisi->nama_ekspedisi }}</option>
                        @endforeach
                    </select>
                    <div class="manual-ekspedisi-input mt-2" style="display:none;">
                        <input type="text" class="form-control" name="entries[__IDX__][nama_ekspedisi_manual]" placeholder="Masukkan nama ekspedisi">
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Jenis &amp; No Kendaraan</label>
                    <select class="form-select entry-kendaraan" name="entries[__IDX__][id_kendaraan]">
                        <option value="">-- Pilih Kendaraan --</option>
                        <option value="other">-- Lainnya (Input Manual) --</option>
                        @foreach($kendaraans as $kendaraan)
                            <option value="{{ $kendaraan->id }}">{{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->no_kendaraan }}</option>
                        @endforeach
                    </select>
                    <div class="manual-kendaraan-input mt-2" style="display:none;">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="text" class="form-control" name="entries[__IDX__][jenis_kendaraan_manual]" placeholder="Jenis Kendaraan">
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control" name="entries[__IDX__][no_kendaraan_manual]" placeholder="No Kendaraan">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Tujuan Pengiriman <span class="text-danger">*</span></label>
                    <select class="form-select entry-tujuan" name="entries[__IDX__][id_tujuan_pengiriman]" required>
                        <option value="">-- Pilih Tujuan --</option>
                        <option value="lainnya">-- Lainnya (Input Manual) --</option>
                        @foreach($tujuanPengirimens as $tujuan)
                            <option value="{{ $tujuan->id }}">{{ $tujuan->nama_tujuan }}</option>
                        @endforeach
                    </select>
                    <div class="manual-tujuan-input mt-2" style="display:none;">
                        <input type="text" class="form-control" name="entries[__IDX__][nama_tujuan_manual]" placeholder="Masukkan tujuan pengiriman">
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Std Precooling <span class="text-danger">*</span></label>
                    <select class="form-select entry-std-precooling" name="entries[__IDX__][id_std_precooling]" required>
                        <option value="">-- Pilih Std Precooling --</option>
                        @foreach($stdPrecoolings as $std)
                            <option value="{{ $std->id }}">{{ $std->nama_std_precooling }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Jam Mulai <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="entries[__IDX__][jam_mulai]" required>
                </div>
                <div class="col-md-4">
                    <label>Jam Selesai <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="entries[__IDX__][jam_selesai]" required>
                </div>
                <div class="col-md-4">
                    <label>Suhu Precooling (°C) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="entries[__IDX__][suhu_precooling]" placeholder="Contoh: -18°C" required>
                </div>
                <div class="col-md-12">
                    <label class="fw-bold text-primary">Segel &amp; Informasi Kendaraan</label>
                </div>
                <div class="col-md-6">
                    <label class="fw-semibold">Segel/Gembok</label>
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input entry-segel" type="radio" name="entries[__IDX__][segel_gembok]" value="segel" id="segel___IDX__">
                            <label class="form-check-label" for="segel___IDX__">Segel</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input entry-segel" type="radio" name="entries[__IDX__][segel_gembok]" value="gembok" id="gembok___IDX__">
                            <label class="form-check-label" for="gembok___IDX__">Gembok</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 no-segel-container" style="display:none;">
                    <label>No. Segel</label>
                    <input type="text" class="form-control" name="entries[__IDX__][no_segel]" placeholder="Nomor Segel">
                </div>
                <div class="col-md-12">
                    <label>Keterangan</label>
                    <textarea class="form-control" name="entries[__IDX__][keterangan]" rows="2" placeholder="Keterangan tambahan"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
function checkSemua(groupName, value) {
    const selector = `input[type="radio"][name^="${groupName}"][value="${value}"]`;
    document.querySelectorAll(selector).forEach(radio => radio.checked = true);
}

document.addEventListener('DOMContentLoaded', function () {

    /* ── helpers ── */
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
                if (a.value === 'other' || a.value === 'lainnya') return -1;
                if (b.value === 'other' || b.value === 'lainnya') return 1;
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

    function initEntryEvents(entryEl) {
        // Init Choices
        const ekspedisiSel = entryEl.querySelector('.entry-ekspedisi');
        if (ekspedisiSel) initChoicesSelect(ekspedisiSel, '-- Pilih Ekspedisi --');

        // Toggle manual ekspedisi
        const manualEkspedisi = entryEl.querySelector('.manual-ekspedisi-input');
        if (ekspedisiSel && manualEkspedisi) {
            ekspedisiSel.addEventListener('change', function () {
                manualEkspedisi.style.display = this.value === 'other' ? 'block' : 'none';
                if (this.value !== 'other') {
                    const inp = manualEkspedisi.querySelector('input');
                    if (inp) inp.value = '';
                }
            });
        }

        const kendaraanSel = entryEl.querySelector('.entry-kendaraan');
        if (kendaraanSel) initChoicesSelect(kendaraanSel, '-- Pilih Kendaraan --');

        const tujuanSel = entryEl.querySelector('.entry-tujuan');
        if (tujuanSel) initChoicesSelect(tujuanSel, '-- Pilih Tujuan --');

        const stdPrecoolingSel = entryEl.querySelector('.entry-std-precooling');
        if (stdPrecoolingSel) initChoicesSelect(stdPrecoolingSel, '-- Pilih Std Precooling --');

        // Toggle manual kendaraan
        const manualKendaraan = entryEl.querySelector('.manual-kendaraan-input');
        if (kendaraanSel && manualKendaraan) {
            kendaraanSel.addEventListener('change', function () {
                manualKendaraan.style.display = this.value === 'other' ? 'block' : 'none';
                if (this.value !== 'other') {
                    manualKendaraan.querySelectorAll('input').forEach(i => i.value = '');
                }
            });
        }

        // Toggle manual tujuan
        const manualTujuan = entryEl.querySelector('.manual-tujuan-input');
        if (tujuanSel && manualTujuan) {
            tujuanSel.addEventListener('change', function () {
                manualTujuan.style.display = this.value === 'lainnya' ? 'block' : 'none';
                if (this.value !== 'lainnya') {
                    const inp = manualTujuan.querySelector('input');
                    if (inp) inp.value = '';
                }
            });
        }

        // Toggle no segel
        const segelRadios = entryEl.querySelectorAll('.entry-segel');
        const noSegelContainer = entryEl.querySelector('.no-segel-container');
        segelRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (noSegelContainer) {
                    noSegelContainer.style.display = this.value === 'segel' ? 'block' : 'none';
                    if (this.value !== 'segel') {
                        const inp = noSegelContainer.querySelector('input');
                        if (inp) inp.value = '';
                    }
                }
            });
        });

        // Remove button
        const removeBtn = entryEl.querySelector('.btn-remove-entry');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                entryEl.remove();
                reindexEntries();
            });
        }
    }

    function reindexEntries() {
        const container = document.getElementById('kendaraan-entries-container');
        const entries = container.querySelectorAll('.kendaraan-entry');
        entries.forEach((entry, idx) => {
            entry.dataset.index = idx;
            entry.querySelector('.entry-label').innerHTML = '<i class="bi bi-truck me-1"></i> Kendaraan #' + (idx + 1);

            // Rename all fields
            entry.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/entries\[\d+\]/, 'entries[' + idx + ']');
            });
            // Rename for/id radio labels
            entry.querySelectorAll('[id]').forEach(el => {
                el.id = el.id.replace(/_(segel|gembok)_\d+/, '_$1_' + idx);
            });
            entry.querySelectorAll('[for]').forEach(el => {
                el.htmlFor = el.htmlFor.replace(/_(segel|gembok)_\d+/, '_$1_' + idx);
            });

            // Show/hide remove button — always show if more than 1
            const removeBtn = entry.querySelector('.btn-remove-entry');
            if (removeBtn) removeBtn.style.display = entries.length > 1 ? 'inline-flex' : 'none';
        });

        entryCount = entries.length;
    }

    /* ── init first entry ── */
    let entryCount = 1;
    const firstEntry = document.querySelector('.kendaraan-entry');
    if (firstEntry) initEntryEvents(firstEntry);

    /* ── add entry ── */
    document.getElementById('btn-add-entry').addEventListener('click', function () {
        const template = document.getElementById('entry-template');
        const idx = entryCount;
        const num = idx + 1;

        let html = template.innerHTML
            .replace(/__IDX__/g, idx)
            .replace(/__NUM__/g, num);

        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;
        const newEntry = wrapper.firstElementChild;

        document.getElementById('kendaraan-entries-container').appendChild(newEntry);
        initEntryEvents(newEntry);
        entryCount++;
        reindexEntries();

        // Scroll ke entry baru
        newEntry.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    /* ── confirm back ── */
    document.querySelectorAll('.btn-kembali-confirm').forEach(el => {
        el.addEventListener('click', function (e) {
            if (!confirm('Data belum disimpan. Yakin ingin kembali?')) e.preventDefault();
        });
    });
});
</script>

<!-- Validasi Front-End Form -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-pemeriksaan-loading-kendaraan');
    
    if (!form) return;

    /**
     * Validasi Form - Cek semua field wajib
     * Required fields:
     * 1. tanggal (date input)
     * 2. Minimal satu entry dalam entries array dengan:
     *    - entries[*][id_ekspedisi]
     *    - entries[*][id_tujuan_pengiriman]
     *    - entries[*][id_std_precooling]
     *    - entries[*][jam_mulai]
     *    - entries[*][jam_selesai]
     */
    function validateForm() {
        const errors = [];
        
        // 1. Tanggal (wajib)
        const tanggal = document.getElementById('tanggal');
        if (!tanggal || !tanggal.value || tanggal.value.trim() === '') {
            errors.push('Tanggal harus diisi');
            highlightField(tanggal);
        } else {
            removeHighlight(tanggal);
        }

        // 2. Minimal ada satu entry (data kendaraan)
        const allEntries = document.querySelectorAll('.kendaraan-entry');
        if (!allEntries || allEntries.length === 0) {
            errors.push('Minimal ada satu data kendaraan yang harus diinput');
            return errors;
        }

        // 3. Validasi setiap entry
        allEntries.forEach((entry, entryIndex) => {
            const entryNum = entryIndex + 1;
            let entryValid = true;
            
            // Ekspedisi (wajib)
            const ekspedisiSelect = entry.querySelector('.entry-ekspedisi');
            if (!ekspedisiSelect || !ekspedisiSelect.value || ekspedisiSelect.value.trim() === '') {
                errors.push(`Ekspedisi harus dipilih untuk Kendaraan #${entryNum}`);
                highlightField(ekspedisiSelect);
                entryValid = false;
            } else {
                removeHighlight(ekspedisiSelect);
            }

            // Tujuan Pengiriman (wajib)
            const tujuanSelect = entry.querySelector('.entry-tujuan');
            if (!tujuanSelect || !tujuanSelect.value || tujuanSelect.value.trim() === '') {
                errors.push(`Tujuan Pengiriman harus dipilih untuk Kendaraan #${entryNum}`);
                highlightField(tujuanSelect);
                entryValid = false;
            } else if (tujuanSelect.value === 'lainnya') {
                // Jika "Lainnya (Input Manual)", cek field manual input
                const tujuanManualInput = entry.querySelector('.manual-tujuan-input input');
                if (!tujuanManualInput || !tujuanManualInput.value || tujuanManualInput.value.trim() === '') {
                    errors.push(`Nama Tujuan Pengiriman (Manual) harus diisi untuk Kendaraan #${entryNum}`);
                    highlightField(tujuanManualInput);
                    entryValid = false;
                } else {
                    removeHighlight(tujuanManualInput);
                }
            } else {
                removeHighlight(tujuanSelect);
            }

            // Std Precooling (wajib)
            const stdPrecoolingSelect = entry.querySelector('.entry-std-precooling');
            if (!stdPrecoolingSelect || !stdPrecoolingSelect.value || stdPrecoolingSelect.value.trim() === '') {
                errors.push(`Std Precooling harus dipilih untuk Kendaraan #${entryNum}`);
                highlightField(stdPrecoolingSelect);
                entryValid = false;
            } else {
                removeHighlight(stdPrecoolingSelect);
            }

            // Jam Mulai (wajib)
            const jamMulaiInput = entry.querySelector('input[name*="[jam_mulai]"]');
            if (!jamMulaiInput || !jamMulaiInput.value || jamMulaiInput.value.trim() === '') {
                errors.push(`Jam Mulai harus diisi untuk Kendaraan #${entryNum}`);
                highlightField(jamMulaiInput);
                entryValid = false;
            } else {
                removeHighlight(jamMulaiInput);
            }

            // Jam Selesai (wajib)
            const jamSelesaiInput = entry.querySelector('input[name*="[jam_selesai]"]');
            if (!jamSelesaiInput || !jamSelesaiInput.value || jamSelesaiInput.value.trim() === '') {
                errors.push(`Jam Selesai harus diisi untuk Kendaraan #${entryNum}`);
                highlightField(jamSelesaiInput);
                entryValid = false;
            } else {
                removeHighlight(jamSelesaiInput);
            }
        });

        return errors;
    }

    /**
     * Highlight field yang error
     */
    function highlightField(field) {
        if (field) {
            field.classList.add('is-invalid');
            field.style.borderColor = '#dc3545';
            field.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
        }
    }

    /**
     * Remove highlight dari field
     */
    function removeHighlight(field) {
        if (field) {
            field.classList.remove('is-invalid');
            field.style.borderColor = '';
            field.style.boxShadow = '';
        }
    }

    /**
     * Handle form submit
     */
    form.addEventListener('submit', function(e) {
        const errors = validateForm();
        
        if (errors.length > 0) {
            e.preventDefault();
            
            // Tampilkan error messages
            let errorMessage = '❌ Data Tidak Lengkap:\n\n';
            errors.forEach((error, index) => {
                errorMessage += `${index + 1}. ${error}\n`;
            });
            
            // Gunakan SweetAlert jika tersedia, jika tidak gunakan alert biasa
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: `<div style="text-align: left; line-height: 1.8;">
                        <strong>Berikut field yang belum diisi:</strong><br><br>
                        ${errors.map((err, idx) => `<span style="display: block; margin-bottom: 8px;">${idx + 1}. ${err}</span>`).join('')}
                    </div>`,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            } else {
                alert(errorMessage);
            }
            
            // Scroll ke field pertama yang error
            const allFields = document.querySelectorAll('.is-invalid');
            if (allFields.length > 0) {
                allFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                allFields[0].focus();
            }
        }
        // Jika validasi berhasil, form akan submit normalmente
    });
});
</script>
@endsection
