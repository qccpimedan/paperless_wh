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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Produk <span class="text-danger">*</span></label>
                                                    <select class="choices form-select @error('id_produk') is-invalid @enderror" name="id_produk" required>
                                                        <option value="">-- Pilih Produk --</option>
                                                        @foreach($produks as $produk)
                                                            <option value="{{ $produk->id }}" {{ old('id_produk') == $produk->id ? 'selected' : '' }}>{{ $produk->nama_produk }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_produk')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="text-secondary mt-3">Detail Produk</h6>
                                        <div id="produk-container">
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
                                                        <button type="button" class="btn btn-sm btn-danger remove-produk" style="display: none;">Hapus Detail</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary mt-2" id="add-produk">+ Tambah Detail</button>

                                        <div class="col-md-12 d-flex justify-content-end mt-3">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan Loading Produk</button>
                                            <a href="{{ route('pemeriksaan-loading-produk.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
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

    function syncHiddenProdukIds() {
        const idProduk = document.querySelector('select[name="id_produk"]')?.value || '';
        document.querySelectorAll('#produk-container .produk-id-hidden').forEach((el) => {
            el.value = idProduk;
        });
    }

    function updateProdukRows() {
        const rows = Array.from(document.querySelectorAll('#produk-container .produk-row'));
        rows.forEach((row, index) => {
            const title = row.querySelector('h6');
            if (title) title.textContent = `Detail #${index + 1}`;

            row.querySelectorAll('input, textarea').forEach((el) => {
                const name = el.getAttribute('name');
                if (!name) return;
                const updated = name.replace(/produk_data\[\d+\]/g, `produk_data[${index}]`);
                if (updated !== name) el.setAttribute('name', updated);
            });

            const removeBtn = row.querySelector('.remove-produk');
            if (removeBtn) {
                removeBtn.style.display = rows.length > 1 ? 'inline-block' : 'none';
            }
        });

        syncHiddenProdukIds();
    }

    document.querySelector('select[name="id_produk"]')?.addEventListener('change', function() {
        syncHiddenProdukIds();
    });

    updateProdukRows();

    document.getElementById('add-produk').addEventListener('click', function() {
        const container = document.getElementById('produk-container');
        const newRow = document.createElement('div');
        newRow.className = 'produk-row mb-4 p-3 border rounded';
        newRow.style.backgroundColor = '#f8f9fa';
        const index = document.querySelectorAll('#produk-container .produk-row').length;
        newRow.innerHTML = `
            <h6 class="text-secondary mb-3">Detail #${index + 1}</h6>
            <input type="hidden" class="produk-id-hidden" name="produk_data[${index}][id_produk]" value="">
            <div class="row">
                <div class="col-md-3">
                    <label>Kode Produksi</label>
                    <input type="text" class="form-control" name="produk_data[${index}][kode_produksi]" placeholder="Kode Produksi">
                </div>
                <div class="col-md-3">
                    <label>Best Before</label>
                    <input type="date" class="form-control" name="produk_data[${index}][best_before]">
                </div>
                <div class="col-md-3">
                    <label>Jumlah Kemasan</label>
                    <input type="text" class="form-control" name="produk_data[${index}][jumlah_kemasan]" placeholder="Contoh: 100 Karton">
                </div>
                <div class="col-md-3">
                    <label>Jumlah Sampling</label>
                    <input type="text" class="form-control" name="produk_data[${index}][jumlah_sampling]" placeholder="Contoh: 10 Karton">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="produk_data[${index}][kondisi_kemasan]" value="1" checked>
                        <label class="form-check-label">Kondisi Kemasan Baik</label>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label>Keterangan</label>
                    <textarea class="form-control" name="produk_data[${index}][keterangan]" rows="2" placeholder="Keterangan tambahan untuk detail ini"></textarea>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="button" class="btn btn-sm btn-danger remove-produk">Hapus Detail</button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        updateProdukRows();
    });

    document.getElementById('produk-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-produk')) {
            const rows = document.querySelectorAll('#produk-container .produk-row');
            if (rows.length > 1) {
                e.target.closest('.produk-row').remove();
                updateProdukRows();
            } else {
                alert('Minimal harus ada 1 detail!');
            }
        }
    });
});
</script>
@endsection