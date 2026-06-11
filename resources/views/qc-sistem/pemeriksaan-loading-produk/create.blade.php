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
                                                    <select id="id_tujuan_pengiriman" class="form-select @error('id_tujuan_pengiriman') is-invalid @enderror" name="id_tujuan_pengiriman">
                                                        <option value="">-- Pilih Tujuan --</option>
                                                        <option value="other" {{ old('id_tujuan_pengiriman') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                        @foreach($tujuanPengirimans as $tujuan)
                                                            <option value="{{ $tujuan->id }}" {{ old('id_tujuan_pengiriman') == $tujuan->id ? 'selected' : '' }}>{{ $tujuan->customer ? $tujuan->customer->nama_cust . ' - ' . $tujuan->nama_tujuan : $tujuan->nama_tujuan }}</option>
                                                        @endforeach
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
                                                    <select id="id_kendaraan" class="form-select @error('id_kendaraan') is-invalid @enderror" name="id_kendaraan">
                                                        <option value="">-- Pilih Kendaraan --</option>
                                                        <option value="other" {{ old('id_kendaraan') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                        @foreach($kendaraans as $kendaraan)
                                                            <option value="{{ $kendaraan->id }}" {{ old('id_kendaraan') == $kendaraan->id ? 'selected' : '' }}>{{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->no_kendaraan }}</option>
                                                        @endforeach
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
                                                    <select id="id_supir" class="form-control @error('id_supir') is-invalid @enderror" name="id_supir">
                                                        <option value="">Pilih Supir</option>
                                                        <option value="other" {{ old('id_supir') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                        @foreach($supirs as $supir)
                                                            <option value="{{ $supir->id }}" {{ old('id_supir') == $supir->id ? 'selected' : '' }}>{{ $supir->nama_supir }}</option>
                                                        @endforeach
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
                                        
                                        <!-- Universal Import Section -->
                                        <div class="alert border-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-2 text-white"><i class="bi bi-lightning-charge-fill text-warning"></i> <strong>CARA CEPAT - Import Multiple Produk Sekaligus</strong></h6>
                                                    <p class="mb-2 text-white" style="font-size: 0.95rem;">Untuk loading dengan <strong>banyak produk berbeda</strong>, gunakan fitur ini untuk menghemat waktu:</p>
                                                    <ol class="mb-0 ps-3 text-white" style="font-size: 0.90rem;">
                                                        <li>Download <strong>Template Universal</strong> (berisi semua produk)</li>
                                                        <li>Isi kode item, batch, ED, jumlah untuk produk yang dibutuhkan</li>
                                                        <li>Hapus baris produk yang tidak digunakan</li>
                                                        <li>Upload file Excel</li>
                                                    </ol>
                                                </div>
                                                <div class="text-end ms-3" style="min-width: 220px;">
                                                    <a href="{{ route('pemeriksaan-loading-produk.download-template-universal') }}" 
                                                       class="btn btn-light btn-sm mb-2 d-block" style="font-weight: 600;">
                                                        <i class="bi bi-download"></i> Download Template Universal
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-warning d-block" id="btn-import-universal" style="font-weight: 600; color: #000;">
                                                        <i class="bi bi-file-earmark-excel"></i> Import Universal
                                                    </button>
                                                    <input type="file" id="file-import-universal" accept=".xlsx,.xls" style="display:none;">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="text-center my-4">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <hr class="flex-grow-1">
                                                <span class="badge bg-dark mx-3 px-4 py-2" style="font-size: 0.9rem; font-weight: 600;">ATAU</span>
                                                <hr class="flex-grow-1">
                                            </div>
                                        </div>

                                        <h6 class="text-dark mb-3" style="font-weight: 600;"><i class="bi bi-pencil-square"></i> Input Manual per Produk:</h6> -->
                                        
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
                                                        <!-- Hidden: Field ini tidak digunakan lagi karena sistem sudah pakai produk_data[] -->
                                                        <div class="form-group">
                                                            <label>Nama Produk</label>
                                                            <select class="form-select produk-select @error('id_produk') is-invalid @enderror" name="id_produk_legacy" data-selected="{{ old('id_produk', '') }}">
                                                                <option value="">-- Pilih Produk --</option>
                                                            </select>
                                                            @error('id_produk')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                                                    <h6 class="text-secondary mb-0">Detail Produk</h6>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-outline-success btn-sm download-template-btn">
                                                            <i class="bi bi-download"></i> Template
                                                        </button>
                                                        <button type="button" class="btn btn-outline-info btn-sm import-excel-btn">
                                                            <i class="bi bi-file-earmark-excel"></i> Import
                                                        </button>
                                                        <input type="file" class="import-excel-input" accept=".xlsx, .xls" style="display:none;">
                                                    </div>
                                                </div> -->
                                                <!-- Tambahkan ini tepat di bawah div d-flex yang berisi tombol Template & Import -->
                                                <!-- <div class="alert alert-light-info color-info mt-2 mb-3 py-2 px-3" style="font-size: 0.85rem;">
                                                    <i class="bi bi-info-circle-fill me-2"></i>
                                                    <strong>Cara Cepat:</strong> 1. Pilih Produk > 2. Klik <strong>Template</strong> > 3. Isi Excel > 4. Klik <strong>Import</strong>. 
                                                    <span class="text-muted">(Sistem akan mengisi baris detail secara otomatis dari file Excel Anda)</span>
                                                </div> -->

                                                <div class="produk-container">
                                                    <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h6 class="text-secondary mb-0">Detail #1</h6>
                                                            <button type="button" class="btn btn-danger btn-sm remove-detail" style="display: none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                                                        </div>
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
                                                                <label>Berat per Karung atau Box</label>
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
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary btn-sm mt-2 add-detail"><i class="bi bi-plus"></i> Tambah Detail</button>

                                                <div class="row mt-3 pt-3 border-top">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-danger btn-sm remove-produk-group" style="display:none;"><i class="bi bi-trash"></i> Hapus Produk</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-success btn-sm mt-2" id="add-produk-group"><i class="bi bi-plus"></i> Tambah Produk</button>

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

    // Helper: inisialisasi Choices.js pada select dengan konfigurasi search yang baik
    function initChoicesSelect(selectEl, placeholderText) {
        if (!selectEl || typeof Choices === 'undefined') return null;

        // Pastikan teks setiap option sudah bersih (trim whitespace)
        Array.from(selectEl.options).forEach(function(opt) {
            opt.text = opt.text.trim();
        });

        return new Choices(selectEl, {
            searchResultLimit: 100,
                    searchFuzziness: 0.000001,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
                    searchEnabled: true,
            searchPlaceholderValue: 'Cari...',
            searchFields: ['label', 'value'],
            itemSelectText: '',
            noResultsText: 'Tidak ada hasil ditemukan',
            noChoicesText: 'Tidak ada pilihan tersedia',
            shouldSort: false,
            placeholder: true,
            placeholderValue: placeholderText || 'Pilih...',
            fuseOptions: {
                includeScore: true,
                threshold: 0.4,
                distance: 1000,
                tokenize: true,
                matchAllTokens: false
            }
        });
    }

    // -------- Tujuan Pengiriman --------
    const tujuanSelectEl = document.getElementById('id_tujuan_pengiriman');
    const manualTujuanInput = document.getElementById('manual_tujuan_pengiriman_input');

    function toggleManualTujuan(value) {
        if (!manualTujuanInput) return;
        manualTujuanInput.style.display = (value === 'other') ? 'block' : 'none';
    }

    if (tujuanSelectEl) {
        // Cek nilai awal
        toggleManualTujuan(tujuanSelectEl.value);

        const tujuanChoices = initChoicesSelect(tujuanSelectEl, '-- Pilih Tujuan --');

        if (tujuanChoices) {
            tujuanSelectEl.addEventListener('change', function() {
                toggleManualTujuan(this.value);
            });
        } else {
            tujuanSelectEl.addEventListener('change', function() {
                toggleManualTujuan(this.value);
            });
        }
    }

    // -------- Kendaraan --------
    const kendaraanSelectEl = document.getElementById('id_kendaraan');
    const manualKendaraanInput = document.getElementById('manual_kendaraan_input');

    function toggleManualKendaraan(value) {
        if (!manualKendaraanInput) return;
        manualKendaraanInput.style.display = (value === 'other') ? 'block' : 'none';
    }

    if (kendaraanSelectEl) {
        // Cek nilai awal
        toggleManualKendaraan(kendaraanSelectEl.value);

        const kendaraanChoices = initChoicesSelect(kendaraanSelectEl, '-- Pilih Kendaraan --');

        if (kendaraanChoices) {
            kendaraanSelectEl.addEventListener('change', function() {
                toggleManualKendaraan(this.value);
            });
        } else {
            kendaraanSelectEl.addEventListener('change', function() {
                toggleManualKendaraan(this.value);
            });
        }
    }

    // -------- Supir --------
    const supirSelectEl = document.getElementById('id_supir');
    const manualSupirInput = document.getElementById('manual_supir_input');

    function toggleManualSupir(value) {
        if (!manualSupirInput) return;
        manualSupirInput.style.display = (value === 'other') ? 'block' : 'none';
    }

    if (supirSelectEl) {
        // Cek nilai awal
        toggleManualSupir(supirSelectEl.value);

        const supirChoices = initChoicesSelect(supirSelectEl, 'Pilih Supir');

        if (supirChoices) {
            supirSelectEl.addEventListener('change', function() {
                toggleManualSupir(this.value);
            });
        } else {
            supirSelectEl.addEventListener('change', function() {
                toggleManualSupir(this.value);
            });
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

    const bsCollapse = (el) => {
        try {
            if (!el || !window.bootstrap || !window.bootstrap.Collapse) return null;
            return window.bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
        } catch (e) {
            return null;
        }
    };

    const updateProdukLabel = (groupEl, groupIdx) => {
        if (!groupEl) return;
        const labelEl = groupEl.querySelector('.produk-collapse-label');
        if (!labelEl) return;

        const produkSelect = groupEl.querySelector('select.produk-select');
        const selectedText = produkSelect && produkSelect.selectedOptions && produkSelect.selectedOptions[0]
            ? String(produkSelect.selectedOptions[0].textContent || '').trim()
            : '';

        labelEl.textContent = selectedText || `Produk #${(groupIdx ?? 0) + 1}`;
    };

    const updateDetailLabel = (rowEl, detailIdxWithinGroup) => {
        if (!rowEl) return;
        const labelEl = rowEl.querySelector('.detail-collapse-label');
        if (!labelEl) return;

        const kodeInput = rowEl.querySelector('input[name^="produk_data"][name$="[kode_produksi]"]');
        const kodeVal = kodeInput ? String(kodeInput.value || '').trim() : '';
        labelEl.textContent = kodeVal || `Detail #${(detailIdxWithinGroup ?? 0) + 1}`;
    };

    const collapseAllProdukExcept = (activeGroupEl) => {
        const groups = Array.from(document.querySelectorAll('#produk-groups .produk-group'));
        groups.forEach((g) => {
            if (!g || g === activeGroupEl) return;
            const body = g.querySelector(':scope > .produk-collapse.collapse');
            if (!body) return;
            const inst = bsCollapse(body);
            if (inst) {
                inst.hide();
            } else {
                body.classList.remove('show');
                const icon = g.querySelector('.collapse-chevron');
                if (icon) icon.classList.add('rotated');
            }
        });
    };

    const collapseOtherDetailsInGroup = (groupEl, activeRowEl) => {
        if (!groupEl) return;
        const rows = Array.from(groupEl.querySelectorAll('.produk-container .produk-row'));
        rows.forEach((row) => {
            if (!row || row === activeRowEl) return;
            const body = row.querySelector(':scope > .detail-collapse.collapse');
            if (!body) return;
            const inst = bsCollapse(body);
            if (inst) {
                inst.hide();
            } else {
                body.classList.remove('show');
                const icon = row.querySelector('.detail-chevron');
                if (icon) icon.classList.add('rotated');
            }
        });
    };

    const ensureDetailCollapsible = (rowEl) => {
        if (!rowEl) return;
        if (!rowEl.dataset.detailCollapseId) {
            rowEl.dataset.detailCollapseId = `detail_lp_${Date.now()}_${Math.random().toString(16).slice(2)}`;
        }
        const collapseId = rowEl.dataset.detailCollapseId;

        const header = rowEl.querySelector(':scope > .d-flex');
        if (!header) return;

        const titleEl = header.querySelector('h6');
        if (titleEl && !titleEl.querySelector('.detail-toggle-btn')) {
            const existingText = (titleEl.textContent || '').trim();
            titleEl.textContent = '';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-primary btn-sm d-flex align-items-center gap-2 detail-toggle-btn';

            const span = document.createElement('span');
            span.className = 'detail-collapse-label';
            span.textContent = existingText || 'Detail';

            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down detail-chevron';

            btn.appendChild(span);
            btn.appendChild(icon);
            titleEl.appendChild(btn);
        }

        const groupEl = rowEl.closest('.produk-group');
        const idxInGroup = groupEl ? Array.from(groupEl.querySelectorAll('.produk-container .produk-row')).indexOf(rowEl) : 0;
        updateDetailLabel(rowEl, idxInGroup >= 0 ? idxInGroup : 0);

        let body = rowEl.querySelector(`:scope > .detail-collapse.collapse#${collapseId}`);
        if (!body) {
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
            rowEl.appendChild(body);
        }

        const icon = header.querySelector('.detail-chevron');
        const inst = bsCollapse(body);
        if (inst && icon && !body.dataset.collapseEventsBound) {
            body.dataset.collapseEventsBound = '1';
            body.addEventListener('shown.bs.collapse', function() {
                icon.classList.remove('rotated');
            });
            body.addEventListener('hidden.bs.collapse', function() {
                icon.classList.add('rotated');
            });
        }

        const toggleBtn = header.querySelector('.detail-toggle-btn');
        if (toggleBtn && !toggleBtn.dataset.toggleBound) {
            toggleBtn.dataset.toggleBound = '1';
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const groupEl = rowEl.closest('.produk-group');
                if (groupEl) collapseOtherDetailsInGroup(groupEl, rowEl);

                const nowShown = body.classList.contains('show');
                const collapseInst = bsCollapse(body);
                if (collapseInst) {
                    if (nowShown) collapseInst.hide();
                    else collapseInst.show();
                } else {
                    if (nowShown) {
                        body.classList.remove('show');
                        if (icon) icon.classList.add('rotated');
                    } else {
                        body.classList.add('show');
                        if (icon) icon.classList.remove('rotated');
                    }
                }
            });
        }
    };

    const ensureProdukCollapsible = (groupEl, groupIdx) => {
        if (!groupEl) return;
        if (!groupEl.dataset.produkCollapseId) {
            groupEl.dataset.produkCollapseId = `produk_lp_${Date.now()}_${Math.random().toString(16).slice(2)}`;
        }
        const collapseId = groupEl.dataset.produkCollapseId;

        const header = groupEl.querySelector(':scope > .d-flex');
        if (!header) return;

        const titleEl = header.querySelector('h6');
        if (titleEl && !titleEl.querySelector('button[data-bs-toggle="collapse"]')) {
            const existingText = (titleEl.textContent || '').trim();
            titleEl.textContent = '';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-primary btn-sm d-flex align-items-center justify-content-between collapse-toggle-btn w-100';
            btn.setAttribute('aria-expanded', 'true');
            btn.setAttribute('aria-controls', collapseId);

            const span = document.createElement('span');
            span.className = 'produk-collapse-label';
            span.textContent = existingText || `Produk #${(groupIdx ?? 0) + 1}`;

            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down collapse-chevron';

            btn.appendChild(span);
            btn.appendChild(icon);
            titleEl.appendChild(btn);
        }

        updateProdukLabel(groupEl, groupIdx);

        let body = groupEl.querySelector(`:scope > .produk-collapse.collapse#${collapseId}`);
        if (!body) {
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
            groupEl.appendChild(body);
        }

        const icon = header.querySelector('.collapse-chevron');
        const inst = bsCollapse(body);
        if (inst && icon && !body.dataset.collapseEventsBound) {
            body.dataset.collapseEventsBound = '1';
            body.addEventListener('shown.bs.collapse', function() {
                icon.classList.remove('rotated');
            });
            body.addEventListener('hidden.bs.collapse', function() {
                icon.classList.add('rotated');
            });
        }

        const toggleBtn = header.querySelector('.collapse-toggle-btn');
        if (toggleBtn && !toggleBtn.dataset.toggleBound) {
            toggleBtn.dataset.toggleBound = '1';
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();

                collapseAllProdukExcept(groupEl);

                const nowShown = body.classList.contains('show');
                const collapseInst = bsCollapse(body);
                if (collapseInst) {
                    if (nowShown) collapseInst.hide();
                    else collapseInst.show();
                } else {
                    if (nowShown) {
                        body.classList.remove('show');
                        if (icon) icon.classList.add('rotated');
                    } else {
                        body.classList.add('show');
                        if (icon) icon.classList.remove('rotated');
                    }
                }
            });
        }
    };

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
                searchResultLimit: 100,
                    searchFuzziness: 0.000001,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
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
            updateProdukLabel(g, i);
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
                updateDetailLabel(row, idxInGroup);

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

        groupEl.querySelectorAll('.produk-container .produk-row').forEach((row) => ensureDetailCollapsible(row));

        groupEl.querySelectorAll('.produk-container .produk-row').forEach((row, idx) => {
            const kodeInput = row.querySelector('input[name^="produk_data"][name$="[kode_produksi]"]');
            if (kodeInput && !kodeInput.dataset.labelBound) {
                kodeInput.dataset.labelBound = '1';
                kodeInput.addEventListener('input', function() {
                    const group = row.closest('.produk-group');
                    const di = group ? Array.from(group.querySelectorAll('.produk-container .produk-row')).indexOf(row) : idx;
                    updateDetailLabel(row, di >= 0 ? di : idx);
                });
                kodeInput.addEventListener('change', function() {
                    const group = row.closest('.produk-group');
                    const di = group ? Array.from(group.querySelectorAll('.produk-container .produk-row')).indexOf(row) : idx;
                    updateDetailLabel(row, di >= 0 ? di : idx);
                });
            }
        });

        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', function() {
                if (produkSelect) {
                    produkSelect.setAttribute('data-selected', '');
                }
                populateProdukOptions(groupEl, kategoriSelect.value);
                syncGroupHiddenProdukIds(groupEl);
                const idx = parseInt(groupEl.getAttribute('data-group-index') || '0', 10) || 0;
                updateProdukLabel(groupEl, idx);
            });
        }

        if (produkSelect) {
            produkSelect.addEventListener('change', function() {
                syncGroupHiddenProdukIds(groupEl);
                const idx = parseInt(groupEl.getAttribute('data-group-index') || '0', 10) || 0;
                updateProdukLabel(groupEl, idx);
            });
        }

        const addDetailBtn = groupEl.querySelector('.add-detail');
        if (addDetailBtn) {
            addDetailBtn.addEventListener('click', function() {
                addDetailRow(groupEl);
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

        // -------- Download Template & Import Excel --------
        const downloadBtn = groupEl.querySelector('.download-template-btn');
        const importBtn = groupEl.querySelector('.import-excel-btn');
        const importInput = groupEl.querySelector('.import-excel-input');

        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                const idProduk = produkSelect ? produkSelect.value : '';
                if (!idProduk) {
                    alert('Silakan pilih produk terlebih dahulu sebelum mendownload template.');
                    return;
                }
                
                // Ganti URL ini dengan route yang sesuai di Laravel
                const url = `{{ route('pemeriksaan-loading-produk.download-template') }}?id_produk=${idProduk}`;
                window.location.href = url;
            });
        }

        if (importBtn && importInput) {
            importBtn.addEventListener('click', () => importInput.click());

            importInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const data = new Uint8Array(e.target.result);
                        const workbook = XLSX.read(data, { type: 'array', cellDates: true });
                        const firstSheetName = workbook.SheetNames[0];
                        const worksheet = workbook.Sheets[firstSheetName];
                        const jsonData = XLSX.utils.sheet_to_json(worksheet);

                        if (jsonData.length === 0) {
                            alert('File Excel kosong atau format tidak sesuai.');
                            return;
                        }

                        // Helper function to format date to YYYY-MM-DD
                        const formatDate = (val) => {
                            if (!val) return '';
                            let d = new Date(val);
                            if (isNaN(d.getTime())) return val; // Return as is if not a valid date
                            return d.toISOString().split('T')[0];
                        };

                        // Konfirmasi import
                        if (!confirm(`Import ${jsonData.length} baris data dari Excel?`)) {
                            importInput.value = '';
                            return;
                        }

                        // Kosongkan container jika hanya ada detail default yang kosong
                        const container = groupEl.querySelector('.produk-container');
                        const existingRows = container.querySelectorAll('.produk-row');
                        if (existingRows.length === 1) {
                            const firstRow = existingRows[0];
                            const kodeVal = firstRow.querySelector('input[name$="[kode_produksi]"]').value;
                            if (!kodeVal) firstRow.remove();
                        }

                        jsonData.forEach((row) => {
                            addDetailRow(groupEl, {
                                kode_produksi: row['KODE PRODUKSI'] || row['Kode Produksi'] || '',
                                best_before: formatDate(row['BEST BEFORE'] || row['Best Before']),
                                jumlah_kemasan: row['JUMLAH KEMASAN'] || row['Jumlah Kemasan'] || '',
                                jumlah_sampling: row['JUMLAH SAMPLING'] || row['Jumlah Sampling'] || '',
                                berat_perkarung: row['BERAT PER KARUNG & BOX'] || row['Berat per Karung'] || '',
                                kondisi_kemasan: (String(row['Kondisi Kemasan Baik'] || row['Kondisi Baik'] || '').toLowerCase().trim() === 'ok' || row['kondisi_kemasan'] == '1') ? true : false,
                                keterangan: row['Keterangan'] || row['keterangan'] || ''
                            });
                        });

                        alert('Berhasil mengimport data dari Excel.');
                    } catch (err) {
                        console.error(err);
                        alert('Terjadi kesalahan saat membaca file Excel. Pastikan format benar.');
                    }
                    importInput.value = '';
                };
                reader.readAsArrayBuffer(file);
            });
        }

        if (kategoriSelect) {
            populateProdukOptions(groupEl, kategoriSelect.value);
        }
        reindexAllDetails();
    }

    // Helper untuk menambah baris detail (digunakan oleh tombol "Tambah Detail" dan "Import Excel")
    function addDetailRow(groupEl, data = null) {
        const container = groupEl.querySelector('.produk-container');
        if (!container) return;

        const newRow = document.createElement('div');
        newRow.className = 'produk-row mb-4 p-3 border rounded';
        newRow.style.backgroundColor = '#f8f9fa';
        const tempIndex = 0; // Akan di-reindex nanti
        
        const kodeVal = data ? (data.kode_produksi || '') : '';
        const bbVal = data ? (data.best_before || '') : '';
        const jkVal = data ? (data.jumlah_kemasan || '') : '';
        const jsVal = data ? (data.jumlah_sampling || '') : '';
        const bpVal = data ? (data.berat_perkarung || '') : '';
        const kkChecked = data ? (data.kondisi_kemasan === true ? 'checked' : '') : 'checked';
        const ketVal = data ? (data.keterangan || '') : '';

        newRow.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-secondary mb-0">Detail</h6>
                <button type="button" class="btn btn-danger btn-sm remove-detail"><i class="bi bi-trash"></i> Hapus Detail</button>
            </div>
            <input type="hidden" class="produk-id-hidden" name="produk_data[${tempIndex}][id_produk]" value="">
            <div class="row">
                <div class="col-md-3">
                    <label>Kode Produksi</label>
                    <input type="text" class="form-control" name="produk_data[${tempIndex}][kode_produksi]" value="${kodeVal}" placeholder="Kode Produksi">
                </div>
                <div class="col-md-3">
                    <label>Best Before</label>
                    <input type="date" class="form-control" name="produk_data[${tempIndex}][best_before]" value="${bbVal}">
                </div>
                <div class="col-md-3">
                    <label>Jumlah Kemasan</label>
                    <input type="text" class="form-control" name="produk_data[${tempIndex}][jumlah_kemasan]" value="${jkVal}" placeholder="Contoh: 100 Karton">
                </div>
                <div class="col-md-3">
                    <label>Jumlah Sampling</label>
                    <input type="text" class="form-control" name="produk_data[${tempIndex}][jumlah_sampling]" value="${jsVal}" placeholder="Contoh: 10 Karton">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <label>Berat per Karung atau Box</label>
                    <input type="text" class="form-control" name="produk_data[${tempIndex}][berat_perkarung]" value="${bpVal}" placeholder="Contoh: 25 Kg">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="produk_data[${tempIndex}][kondisi_kemasan]" value="1" ${kkChecked}>
                        <label class="form-check-label">Kondisi Kemasan Baik</label>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label>Keterangan</label>
                    <textarea class="form-control" name="produk_data[${tempIndex}][keterangan]" rows="2" placeholder="Keterangan tambahan">${ketVal}</textarea>
                </div>
            </div>
        `;
        
        container.appendChild(newRow);
        ensureDetailCollapsible(newRow);
        
        // Hanya collapse yang lain jika ini ditambah secara manual (bukan import)
        if (!data) {
            collapseOtherDetailsInGroup(groupEl, newRow);
        } else {
            // Jika import, biarkan tertutup (collapsed) agar tidak memenuhi layar
            const body = newRow.querySelector('.detail-collapse');
            if (body) {
                const inst = bsCollapse(body);
                if (inst) inst.hide();
                else body.classList.remove('show');
            }
        }

        const kodeInput = newRow.querySelector('input[name^="produk_data"][name$="[kode_produksi]"]');
        if (kodeInput) {
            kodeInput.addEventListener('input', function() {
                const di = Array.from(container.querySelectorAll('.produk-row')).indexOf(newRow);
                updateDetailLabel(newRow, di);
            });
        }
        
        reindexAllDetails();
    }

    // Init produk options on load
    document.querySelectorAll('#produk-groups .produk-group').forEach((g, idx) => {
        ensureProdukCollapsible(g, idx);
        bindGroupEvents(g);
    });
    updateGroupTitles();

    // Match kemasan behavior: keep only the first produk expanded initially
    const firstGroup = document.querySelector('#produk-groups .produk-group');
    if (firstGroup) {
        collapseAllProdukExcept(firstGroup);
    }

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
            <div class="produk-container">
                <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-secondary mb-0">Detail #1</h6>
                        <button type="button" class="btn btn-danger btn-sm remove-detail" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                    </div>
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
                            <label>Berat per Karung atau Box</label>
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
                </div>
            </div>
            <button type="button" class="btn btn-primary btn-sm mt-2 add-detail"><i class="bi bi-plus"></i> Tambah Detail</button>

            <div class="row mt-3 pt-3 border-top">
                <div class="col-md-12">
                    <button type="button" class="btn btn-danger btn-sm remove-produk-group"><i class="bi bi-trash"></i> Hapus Produk</button>
                </div>
            </div>
        `;

        groupsWrapper.appendChild(newGroup);
        ensureProdukCollapsible(newGroup, document.querySelectorAll('#produk-groups .produk-group').length - 1);
        updateGroupTitles();
        bindGroupEvents(newGroup);

        // Match kemasan behavior: open the newly added produk and collapse others
        const body = newGroup.querySelector(':scope > .produk-collapse.collapse');
        if (body) {
            const inst = bsCollapse(body);
            if (inst) inst.show();
            else body.classList.add('show');
        }
        collapseAllProdukExcept(newGroup);
    });
});
</script>

<!-- Universal Import Handler -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnImportUniversal = document.getElementById('btn-import-universal');
    const fileImportUniversal = document.getElementById('file-import-universal');

    if (btnImportUniversal && fileImportUniversal) {
        btnImportUniversal.addEventListener('click', function() {
            fileImportUniversal.click();
        });

        fileImportUniversal.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            console.log('📁 File selected:', file.name);

            // Show loading
            const originalBtn = btnImportUniversal.innerHTML;
            btnImportUniversal.disabled = true;
            btnImportUniversal.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importing...';

            const formData = new FormData();
            formData.append('excel_file', file);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                console.log('🚀 Sending request to server...');
                
                const response = await fetch('{{ route("pemeriksaan-loading-produk.import-universal") }}', {
                    method: 'POST',
                    body: formData
                });

                console.log('📡 Response status:', response.status);
                
                const result = await response.json();
                console.log('📦 Response data:', result);

                if (result.success) {
                    console.log('✅ Success! Data count:', result.data.length);
                    
                    // Clear existing produk groups
                    const produkGroups = document.getElementById('produk-groups');
                    if (!produkGroups) {
                        console.error('❌ Element #produk-groups not found!');
                        alert('Error: Element produk-groups tidak ditemukan');
                        throw new Error('Element produk-groups tidak ditemukan');
                    }
                    
                    produkGroups.innerHTML = '';
                    console.log('🗑️ Cleared existing produk groups');

                    // Create produk groups from imported data
                    result.data.forEach((item, index) => {
                        console.log(`➕ Adding produk #${index + 1}:`, item);
                        addProdukGroupFromImport(item, index);
                    });

                    console.log('✅ All produk groups added!');

                    // Show success message
                    alert('Berhasil! ' + result.message);
                } else {
                    console.error('❌ Import failed:', result);
                    
                    let errorMsg = result.message || 'Terjadi kesalahan';
                    if (result.errors && result.errors.length > 0) {
                        errorMsg += ':\n' + result.errors.join('\n');
                    }
                    
                    alert('Import Gagal: ' + errorMsg);
                }
            } catch (error) {
                console.error('💥 Import error:', error);
                alert('Error: Terjadi kesalahan saat import file - ' + error.message);
            } finally {
                btnImportUniversal.disabled = false;
                btnImportUniversal.innerHTML = originalBtn;
                fileImportUniversal.value = '';
            }
        });
    }

    function addProdukGroupFromImport(data, index) {
        const container = document.getElementById('produk-groups');
        const groupIndex = index;
        
        const groupHtml = `
            <div class="produk-group mb-4 p-3 border rounded" style="background-color: #ffffff;" data-group-index="${groupIndex}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-secondary mb-0">Produk #${groupIndex + 1}</h6>
                    ${groupIndex > 0 ? '<button type="button" class="btn btn-sm btn-danger remove-produk-group"><i class="bi bi-trash"></i></button>' : ''}
                </div>

                <input type="hidden" name="produk_data[${groupIndex}][id_produk]" value="${data.id_produk}">
                
                <div class="mb-2">
                    <strong>Produk:</strong> <span class="produk-name-display">${data.nama_produk || 'ID: ' + data.id_produk}</span>
                </div>

                <div class="produk-container">
                    <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kode Produksi</label>
                                    <input type="text" class="form-control" name="produk_data[${groupIndex}][kode_produksi]" value="${data.kode_produksi || ''}" placeholder="Kode Produksi">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Best Before</label>
                                    <input type="date" class="form-control" name="produk_data[${groupIndex}][best_before]" value="${data.best_before || ''}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jumlah Kemasan</label>
                                    <input type="text" class="form-control" name="produk_data[${groupIndex}][jumlah_kemasan]" value="${data.jumlah_kemasan || ''}" placeholder="Contoh: 100 Karton">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jumlah Sampling</label>
                                    <input type="text" class="form-control" name="produk_data[${groupIndex}][jumlah_sampling]" value="${data.jumlah_sampling || ''}" placeholder="Contoh: 10 Karton">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Berat per Karung atau Box</label>
                                    <input type="text" class="form-control" name="produk_data[${groupIndex}][berat_perkarung]" value="${data.berat_perkarung || ''}" placeholder="Contoh: 25 Kg">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="produk_data[${groupIndex}][kondisi_kemasan]" value="1" ${data.kondisi_kemasan ? 'checked' : ''} id="kondisi_${groupIndex}">
                                    <label class="form-check-label" for="kondisi_${groupIndex}">
                                        Kondisi Kemasan Baik
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea class="form-control" name="produk_data[${groupIndex}][keterangan]" rows="2" placeholder="Keterangan tambahan untuk detail ini">${data.keterangan || ''}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', groupHtml);

        // Add remove handler
        const removeBtn = container.querySelector(`[data-group-index="${groupIndex}"] .remove-produk-group`);
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                this.closest('.produk-group').remove();
                updateProdukNumbers();
            });
        }
    }

    function updateProdukNumbers() {
        document.querySelectorAll('#produk-groups .produk-group').forEach((group, index) => {
            const title = group.querySelector('h6');
            if (title) {
                title.textContent = `Produk #${index + 1}`;
            }
        });
    }
});
</script>

<style>
    .collapse-toggle-btn { text-align: left; }
    .collapse-chevron { transition: transform .2s ease; }
    .collapse-chevron.rotated { transform: rotate(180deg); }

    .detail-chevron { transition: transform .2s ease; }
    .detail-chevron.rotated { transform: rotate(180deg); }
</style>

<!-- SheetJS for Excel Parsing -->
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
@endsection