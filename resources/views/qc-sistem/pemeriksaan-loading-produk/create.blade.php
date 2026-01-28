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
                    <h3>Tambah Pemeriksaan Loading Produk</h3>
                    <p class="text-subtitle text-muted">Input data pemeriksaan loading produk baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-loading-produk.index') }}">Pemeriksaan Loading Produk</a></li>
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
                            <h4 class="card-title">Form Pemeriksaan Loading Produk</h4>
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

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-loading-produk.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <!-- INFORMASI DASAR -->
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
                                                    <select id="id_shift" class="form-select @error('id_shift') is-invalid @enderror" name="id_shift">
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
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_tujuan_pengiriman">Customer & Tujuan Pengiriman</label>
                                                    <select id="id_tujuan_pengiriman" class="choices form-select @error('id_tujuan_pengiriman') is-invalid @enderror" name="id_tujuan_pengiriman">
                                                        <option value="">-- Pilih Tujuan --</option>
                                                        @foreach($tujuanPengirimans as $tujuan)
                                                            <option value="{{ $tujuan->id }}" {{ old('id_tujuan_pengiriman') == $tujuan->id ? 'selected' : '' }}>
                                                                @if($tujuan->customer)
                                                                    {{ $tujuan->customer->nama_cust }} - {{ $tujuan->nama_tujuan }}
                                                                @else
                                                                    {{ $tujuan->nama_tujuan }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                        <option value="other" {{ old('id_tujuan_pengiriman') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                    </select>
                                                    @error('id_tujuan_pengiriman')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror

                                                    <div id="manual_tujuan_pengiriman_input" class="mt-2" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="nama_customer_manual">Customer</label>
                                                                    <input type="text" id="nama_customer_manual" class="form-control @error('nama_customer_manual') is-invalid @enderror"
                                                                        name="nama_customer_manual" value="{{ old('nama_customer_manual') }}" placeholder="Masukkan nama customer">
                                                                    @error('nama_customer_manual')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="nama_tujuan_manual">Tujuan Pengiriman</label>
                                                                    <input type="text" id="nama_tujuan_manual" class="form-control @error('nama_tujuan_manual') is-invalid @enderror"
                                                                        name="nama_tujuan_manual" value="{{ old('nama_tujuan_manual') }}" placeholder="Masukkan tujuan pengiriman">
                                                                    @error('nama_tujuan_manual')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_kendaraan">Jenis & No Kendaraan</label>
                                                    <select id="id_kendaraan" class="choices form-select @error('id_kendaraan') is-invalid @enderror" name="id_kendaraan">
                                                        <option value="">-- Pilih Kendaraan --</option>
                                                        @foreach($kendaraans as $kendaraan)
                                                            <option value="{{ $kendaraan->id }}" {{ old('id_kendaraan') == $kendaraan->id ? 'selected' : '' }}>
                                                                {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->no_kendaraan }}
                                                            </option>
                                                        @endforeach
                                                        <option value="other" {{ old('id_kendaraan') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                    </select>
                                                    @error('id_kendaraan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror

                                                    <!-- Input manual yang awalnya disembunyikan -->
                                                    <div id="manual_kendaraan_input" class="mt-2" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="jenis_kendaraan_manual">Jenis Kendaraan</label>
                                                                    <input type="text" id="jenis_kendaraan_manual" class="form-control" 
                                                                        name="jenis_kendaraan_manual" value="{{ old('jenis_kendaraan_manual') }}" placeholder="Masukkan jenis kendaraan">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="no_kendaraan_manual">No Kendaraan</label>
                                                                    <input type="text" id="no_kendaraan_manual" class="form-control" 
                                                                        name="no_kendaraan_manual" value="{{ old('no_kendaraan_manual') }}" placeholder="Masukkan nomor kendaraan">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_supir">Nama Supir</label>
                                                    <select id="id_supir" class="choices form-control @error('id_supir') is-invalid @enderror" name="id_supir">
                                                        <option value="">Pilih Supir</option>
                                                        @foreach($supirs as $supir)
                                                            <option value="{{ $supir->id }}" {{ old('id_supir') == $supir->id ? 'selected' : '' }}>
                                                                {{ $supir->nama_supir }}
                                                            </option>
                                                        @endforeach
                                                        <option value="other" {{ old('id_supir') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                    </select>
                                                    @error('id_supir')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror

                                                    <div id="manual_supir_input" class="mt-2" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="nama_supir_manual">Nama Supir</label>
                                                            <input type="text" id="nama_supir_manual" class="form-control @error('nama_supir_manual') is-invalid @enderror"
                                                                name="nama_supir_manual" value="{{ old('nama_supir_manual') }}" placeholder="Masukkan nama supir">
                                                            @error('nama_supir_manual')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- WAKTU LOADING -->
                                        <h5 class="text-primary mb-3 mt-4">Waktu Loading</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="star_loading">Mulai Loading</label>
                                                    <input type="time" id="star_loading" class="form-control @error('star_loading') is-invalid @enderror"
                                                        name="star_loading" value="{{ old('star_loading') }}">
                                                    @error('star_loading')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="selesai_loading">Selesai Loading</label>
                                                    <input type="time" id="selesai_loading" class="form-control @error('selesai_loading') is-invalid @enderror"
                                                        name="selesai_loading" value="{{ old('selesai_loading') }}">
                                                    @error('selesai_loading')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TEMPERATURE -->
                                        <h5 class="text-primary mb-3 mt-4">Temperature</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="temperature_mobil">Temperature Mobil (°C)</label>
                                                    <input type="text" id="temperature_mobil" class="form-control @error('temperature_mobil') is-invalid @enderror"
                                                        name="temperature_mobil" value="{{ old('temperature_mobil') }}" placeholder="Contoh: -18">
                                                    @error('temperature_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kondisi_produk">Kondisi Produk</label>
                                                    <select id="kondisi_produk" class="form-select @error('kondisi_produk') is-invalid @enderror" name="kondisi_produk">
                                                        <option value="">-- Pilih Kondisi --</option>
                                                        <option value="Frozen" {{ old('kondisi_produk') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                        <option value="Fresh" {{ old('kondisi_produk') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                        <option value="Dry" {{ old('kondisi_produk') == 'Dry' ? 'selected' : '' }}>Dry</option>
                                                    </select>
                                                    @error('kondisi_produk')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Temperature Produk (Multiple) (°C)</label>
                                                <div id="temperature-fields">
                                                    <div class="row mb-2 temp-row">
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="temperature_produk[]" placeholder="Contoh: -18">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-success w-100" id="add-temp">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SEGEL & PRODUK -->
                                        <h5 class="text-primary mb-3 mt-4">Segel & Informasi Produk</h5>
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
                                                        name="no_segel" value="{{ old('no_segel') }}" placeholder="Nomor Segel">
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

                                        <!-- DATA PRODUK -->
                                        <h5 class="text-primary mb-3 mt-4">Data Produk <span class="text-danger">*</span></h5>
                                        <div id="produk-groups">
                                            @php
                                                $selectedProdukId = old('id_produk', '');
                                                $selectedKategori = old('kategori_code', '');
                                                if (($selectedKategori === null || $selectedKategori === '') && $selectedProdukId) {
                                                    $selectedKategori = $produkKategoriById[$selectedProdukId] ?? '';
                                                }
                                            @endphp
                                            <div class="produk-group mb-4 p-3 border rounded" style="background-color: #ffffff;" data-group-index="0">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="text-secondary mb-0">Produk #1</h6>
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-produk-group" style="display:none;">Hapus Produk</button>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Kategori <span class="text-danger">*</span></label>
                                                            <select class="choices form-select kategori-produk-select @error('kategori_code') is-invalid @enderror" name="kategori_code" required>
                                                                <option value="">-- Pilih Kategori --</option>
                                                                @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                    <option value="{{ $kategori }}" {{ $selectedKategori == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('kategori_code')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nama Produk <span class="text-danger">*</span></label>
                                                            <select class="form-select produk-select @error('id_produk') is-invalid @enderror" name="id_produk" data-selected="{{ old('id_produk', '') }}" required>
                                                                <option value="">-- Pilih Produk --</option>
                                                            </select>
                                                            @error('id_produk')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <h6 class="text-secondary mt-3">Detail Produk</h6>
                                                <div class="produk-container">
                                                    <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                                        <h6 class="text-secondary mb-3">Detail #1</h6>
                                                        <input type="hidden" class="produk-id-hidden" name="produk_data[0][id_produk]" value="{{ old('id_produk') }}">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label>Kode Produksi</label>
                                                                <input type="text" class="form-control" name="produk_data[0][kode_produksi]" value="{{ old('produk_data.0.kode_produksi') }}" placeholder="Kode Produksi">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Best Before</label>
                                                                <input type="date" class="form-control" name="produk_data[0][best_before]" value="{{ old('produk_data.0.best_before') }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Jumlah Kemasan</label>
                                                                <input type="text" class="form-control" name="produk_data[0][jumlah_kemasan]" value="{{ old('produk_data.0.jumlah_kemasan') }}" placeholder="Contoh: 100 Karton">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Jumlah Sampling</label>
                                                                <input type="text" class="form-control" name="produk_data[0][jumlah_sampling]" value="{{ old('produk_data.0.jumlah_sampling') }}" placeholder="Contoh: 10 Karton">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-3">
                                                                <label>Berat per Karung</label>
                                                                <input type="text" class="form-control" name="produk_data[0][berat_perkarung]" value="{{ old('produk_data.0.berat_perkarung') }}" placeholder="Contoh: 25 Kg">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="produk_data[0][kondisi_kemasan]" value="1" {{ old('produk_data.0.kondisi_kemasan', 1) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Kondisi Kemasan Baik</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <label>Keterangan</label>
                                                                <textarea class="form-control" name="produk_data[0][keterangan]" rows="2" placeholder="Keterangan tambahan untuk detail ini">{{ old('produk_data.0.keterangan') }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <button type="button" class="btn btn-sm btn-danger remove-detail" style="display: none;">Hapus Detail</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-primary mt-2 add-detail">+ Tambah Detail</button>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-success mt-2" id="add-produk-group">+ Tambah Produk</button>

                                        <div class="col-md-12 d-flex justify-content-end mt-3">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan Loading Produk</button>
                                            <a href="{{ route('pemeriksaan-loading-produk.index') }}" class="btn btn-light-secondary me-1 mb-1 btn-kembali-confirm">Kembali</a>
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

    document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
        el.addEventListener('click', function(e) {
            const ok = confirm('Data belum disimpan. Yakin ingin kembali ke halaman index?');
            if (!ok) e.preventDefault();
        });
    });

    // Kendaraan manual input handling
    const kendaraanSelect = document.getElementById('id_kendaraan');
    const manualInput = document.getElementById('manual_kendaraan_input');
    
    kendaraanSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            manualInput.style.display = 'block';
        } else {
            manualInput.style.display = 'none';
        }
    });
    
    // Cek nilai awal saat halaman dimuat
    if (kendaraanSelect.value === 'other') {
        manualInput.style.display = 'block';
    }

    // Tujuan pengiriman manual input handling
    const tujuanSelect = document.getElementById('id_tujuan_pengiriman');
    const manualTujuanInput = document.getElementById('manual_tujuan_pengiriman_input');

    if (tujuanSelect && manualTujuanInput) {
        tujuanSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                manualTujuanInput.style.display = 'block';
            } else {
                manualTujuanInput.style.display = 'none';
            }
        });

        if (tujuanSelect.value === 'other') {
            manualTujuanInput.style.display = 'block';
        }
    }

    // Supir manual input handling
    const supirSelect = document.getElementById('id_supir');
    const manualSupirInput = document.getElementById('manual_supir_input');

    if (supirSelect && manualSupirInput) {
        supirSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                manualSupirInput.style.display = 'block';
            } else {
                manualSupirInput.style.display = 'none';
            }
        });

        if (supirSelect.value === 'other') {
            manualSupirInput.style.display = 'block';
        }
    }

    // Add temperature field
    document.getElementById('add-temp').addEventListener('click', function() {
        const container = document.getElementById('temperature-fields');
        const newField = document.createElement('div');
        newField.className = 'row mb-2 temp-row';
        newField.innerHTML = `
            <div class="col-md-10">
                <input type="text" class="form-control" name="temperature_produk[]" placeholder="Contoh: -18">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 remove-temp">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newField);
    });
    
    // Remove temperature field
    document.getElementById('temperature-fields').addEventListener('click', function(e) {
        if (e.target.closest('.remove-temp')) {
            e.target.closest('.temp-row').remove();
        }
    });

    const produkByKategori = @json($produkByKategori ?? []);

    const choicesInstances = new WeakMap();

    const rebuildProdukChoices = function(produkSelect, choiceItems, desiredValue) {
        if (!produkSelect) return;

        const existing = choicesInstances.get(produkSelect);
        if (existing && typeof existing.destroy === 'function') {
            try { existing.destroy(); } catch (e) {}
            try { choicesInstances.delete(produkSelect); } catch (e) {}
        }

        if (typeof Choices === 'undefined') {
            while (produkSelect.options.length > 0) {
                produkSelect.remove(0);
            }
            produkSelect.add(new Option('-- Pilih Produk --', ''));
            (choiceItems || []).forEach((it) => {
                produkSelect.add(new Option(it.label, it.value));
            });
            if (desiredValue) {
                produkSelect.value = desiredValue;
            }
            return;
        }

        try {
            const instance = new Choices(produkSelect, {
                searchEnabled: true,
                searchPlaceholderValue: 'Cari...',
                itemSelectText: 'Tekan untuk memilih',
                noResultsText: 'Tidak ada hasil ditemukan',
                noChoicesText: 'Tidak ada pilihan tersedia',
                removeItemButton: true,
                shouldSort: false,
                placeholder: true,
                placeholderValue: 'Pilih...'
            });

            instance.setChoices(choiceItems, 'value', 'label', true);

            if (desiredValue) {
                try {
                    instance.setChoiceByValue(String(desiredValue));
                } catch (e) {
                }
            }

            choicesInstances.set(produkSelect, instance);
        } catch (e) {
        }
    };

    const populateProdukOptions = function(groupEl, kategoriCode) {
        const produkSelect = groupEl ? groupEl.querySelector('select.produk-select') : null;
        if (!produkSelect) return;

        const selectedFromAttr = produkSelect.getAttribute('data-selected') || '';
        const rawOptions = (kategoriCode && produkByKategori && produkByKategori[kategoriCode]) ? produkByKategori[kategoriCode] : [];
        const options = Array.isArray(rawOptions) ? rawOptions : Object.values(rawOptions || {});

        const choiceItems = [
            { value: '', label: '-- Pilih Produk --', selected: false, disabled: false }
        ];
        options.forEach((p) => {
            choiceItems.push({
                value: String(p.id),
                label: String(p.nama),
                selected: false,
                disabled: false
            });
        });

        rebuildProdukChoices(produkSelect, choiceItems, selectedFromAttr);
    };

    function updateGroupTitles() {
        const groups = Array.from(document.querySelectorAll('#produk-groups .produk-group'));
        groups.forEach((g, i) => {
            g.setAttribute('data-group-index', String(i));
            const title = g.querySelector('h6.text-secondary');
            if (title) title.textContent = `Produk #${i + 1}`;
            const removeBtn = g.querySelector('.remove-produk-group');
            if (removeBtn) {
                removeBtn.style.display = groups.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    function syncGroupHiddenProdukIds(groupEl) {
        const produkSelect = groupEl ? groupEl.querySelector('select.produk-select') : null;
        const idProduk = produkSelect ? (produkSelect.value || '') : '';
        groupEl.querySelectorAll('.produk-id-hidden').forEach((el) => {
            el.value = idProduk;
        });
    }

    function reindexAllDetails() {
        const groups = Array.from(document.querySelectorAll('#produk-groups .produk-group'));
        let globalIndex = 0;

        groups.forEach((groupEl) => {
            const rows = Array.from(groupEl.querySelectorAll('.produk-container .produk-row'));
            rows.forEach((row, idxInGroup) => {
                const t = row.querySelector('h6');
                if (t) t.textContent = `Detail #${idxInGroup + 1}`;

                row.querySelectorAll('input, textarea').forEach((el) => {
                    const name = el.getAttribute('name');
                    if (!name) return;
                    const updated = name.replace(/produk_data\[\d+\]/g, `produk_data[${globalIndex}]`);
                    if (updated !== name) el.setAttribute('name', updated);
                });

                globalIndex += 1;
            });

            rows.forEach((row) => {
                const removeBtn = row.querySelector('.remove-detail');
                if (removeBtn) {
                    removeBtn.style.display = rows.length > 1 ? 'inline-block' : 'none';
                }
            });

            syncGroupHiddenProdukIds(groupEl);
        });
    }

    function bindGroupEvents(groupEl) {
        const kategoriSelect = groupEl.querySelector('select.kategori-produk-select');
        const produkSelect = groupEl.querySelector('select.produk-select');

        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', function() {
                if (produkSelect) {
                    produkSelect.setAttribute('data-selected', '');
                }
                populateProdukOptions(groupEl, kategoriSelect.value);
                syncGroupHiddenProdukIds(groupEl);
            });
        }

        if (produkSelect) {
            produkSelect.addEventListener('change', function() {
                syncGroupHiddenProdukIds(groupEl);
            });
        }

        const addDetailBtn = groupEl.querySelector('.add-detail');
        if (addDetailBtn) {
            addDetailBtn.addEventListener('click', function() {
                const container = groupEl.querySelector('.produk-container');
                if (!container) return;

                const newRow = document.createElement('div');
                newRow.className = 'produk-row mb-4 p-3 border rounded';
                newRow.style.backgroundColor = '#f8f9fa';
                const tempIndex = 0;
                newRow.innerHTML = `
                    <h6 class="text-secondary mb-3">Detail</h6>
                    <input type="hidden" class="produk-id-hidden" name="produk_data[${tempIndex}][id_produk]" value="">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Kode Produksi</label>
                            <input type="text" class="form-control" name="produk_data[${tempIndex}][kode_produksi]" placeholder="Kode Produksi">
                        </div>
                        <div class="col-md-3">
                            <label>Best Before</label>
                            <input type="date" class="form-control" name="produk_data[${tempIndex}][best_before]">
                        </div>
                        <div class="col-md-3">
                            <label>Jumlah Kemasan</label>
                            <input type="text" class="form-control" name="produk_data[${tempIndex}][jumlah_kemasan]" placeholder="Contoh: 100 Karton">
                        </div>
                        <div class="col-md-3">
                            <label>Jumlah Sampling</label>
                            <input type="text" class="form-control" name="produk_data[${tempIndex}][jumlah_sampling]" placeholder="Contoh: 10 Karton">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>Berat per Karung</label>
                            <input type="text" class="form-control" name="produk_data[${tempIndex}][berat_perkarung]" placeholder="Contoh: 25 Kg">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="produk_data[${tempIndex}][kondisi_kemasan]" value="1" checked>
                                <label class="form-check-label">Kondisi Kemasan Baik</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Keterangan</label>
                            <textarea class="form-control" name="produk_data[${tempIndex}][keterangan]" rows="2" placeholder="Keterangan tambahan"></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-danger remove-detail">Hapus Detail</button>
                        </div>
                    </div>
                `;
                container.appendChild(newRow);
                reindexAllDetails();
            });
        }

        const removeGroupBtn = groupEl.querySelector('.remove-produk-group');
        if (removeGroupBtn) {
            removeGroupBtn.addEventListener('click', function() {
                groupEl.remove();
                updateGroupTitles();
                reindexAllDetails();
            });
        }

        groupEl.addEventListener('click', function(e) {
            if (e.target && e.target.classList && e.target.classList.contains('remove-detail')) {
                const row = e.target.closest('.produk-row');
                if (row) row.remove();
                reindexAllDetails();
            }
        });

        if (kategoriSelect) {
            populateProdukOptions(groupEl, kategoriSelect.value);
        }
        reindexAllDetails();
    }

    // Init produk options on load
    document.querySelectorAll('#produk-groups .produk-group').forEach((g) => bindGroupEvents(g));
    updateGroupTitles();

    document.getElementById('add-produk-group')?.addEventListener('click', function() {
        const groupsWrapper = document.getElementById('produk-groups');
        if (!groupsWrapper) return;

        const newGroup = document.createElement('div');
        newGroup.className = 'produk-group mb-4 p-3 border rounded';
        newGroup.style.backgroundColor = '#ffffff';
        newGroup.setAttribute('data-group-index', String(document.querySelectorAll('#produk-groups .produk-group').length));

        newGroup.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-secondary mb-0">Produk</h6>
                <button type="button" class="btn btn-sm btn-outline-danger remove-produk-group">Hapus Produk</button>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select class="choices form-select kategori-produk-select">
                            <option value="">-- Pilih Kategori --</option>
                            ${Object.keys(produkByKategori || {}).map((k) => `<option value="${String(k)}">${String(k)}</option>`).join('')}
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Produk <span class="text-danger">*</span></label>
                        <select class="form-select produk-select" data-selected="">
                            <option value="">-- Pilih Produk --</option>
                        </select>
                    </div>
                </div>
            </div>
            <h6 class="text-secondary mt-3">Detail Produk</h6>
            <div class="produk-container">
                <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                    <h6 class="text-secondary mb-3">Detail #1</h6>
                    <input type="hidden" class="produk-id-hidden" name="produk_data[0][id_produk]" value="">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Kode Produksi</label>
                            <input type="text" class="form-control" name="produk_data[0][kode_produksi]" placeholder="Kode Produksi">
                        </div>
                        <div class="col-md-3">
                            <label>Best Before</label>
                            <input type="date" class="form-control" name="produk_data[0][best_before]">
                        </div>
                        <div class="col-md-3">
                            <label>Jumlah Kemasan</label>
                            <input type="text" class="form-control" name="produk_data[0][jumlah_kemasan]" placeholder="Contoh: 100 Karton">
                        </div>
                        <div class="col-md-3">
                            <label>Jumlah Sampling</label>
                            <input type="text" class="form-control" name="produk_data[0][jumlah_sampling]" placeholder="Contoh: 10 Karton">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>Berat per Karung</label>
                            <input type="text" class="form-control" name="produk_data[0][berat_perkarung]" placeholder="Contoh: 25 Kg">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="produk_data[0][kondisi_kemasan]" value="1" checked>
                                <label class="form-check-label">Kondisi Kemasan Baik</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Keterangan</label>
                            <textarea class="form-control" name="produk_data[0][keterangan]" rows="2" placeholder="Keterangan tambahan"></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-danger remove-detail" style="display:none;">Hapus Detail</button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-primary mt-2 add-detail">+ Tambah Detail</button>
        `;

        groupsWrapper.appendChild(newGroup);
        updateGroupTitles();
        bindGroupEvents(newGroup);
    });
});
</script>
@endsection