@extends('layouts.app')

<style>
    /* Fix Choices.js dropdown z-index overlap issue */
    .choices__list--dropdown {
        z-index: 9999 !important;
    }
    
    .choices__list--dropdown.is-active {
        z-index: 9999 !important;
    }
    
    /* Ensure dropdown container has proper positioning */
    .choices {
        position: relative;
        z-index: 1;
    }
    
    .choices.is-open {
        z-index: 1000 !important;
    }
    
    /* Produk row positioning */
    .produk-row {
        position: relative;
        z-index: 1;
    }
    
    .produk-row.dropdown-active {
        z-index: 1001 !important;
    }
    
    /* Ensure parent containers don't clip dropdown */
    .card-body {
        overflow: visible !important;
    }
    
    .form-body {
        overflow: visible !important;
    }
</style>

@section('container')
<div id="main">


    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Input Pemeriksaan Return Barang</h3>
                    <p class="text-subtitle text-muted">Tambah pemeriksaan return barang baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('return-barang.index') }}">Pemeriksaan Return Barang</a></li>
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
                            <h4 class="card-title">Form Input Pemeriksaan Return Barang</h4>
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

                                <form id="form-pemeriksaan-return-barang-customer" data-autosave="true" class="form form-horizontal" action="{{ route('return-barang.store') }}" method="POST" novalidate>
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <!-- SECTION: INFORMASI DASAR -->
                                            <div class="col-md-12 mb-3">
                                                <h5 class="text-primary"><strong>Informasi Dasar</strong></h5>
                                                <hr>
                                            </div>

                                            <!-- Tanggal -->
                                            <div class="col-md-6">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                    name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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

                                            <!-- Ekspedisi -->
                                            <div class="col-md-6">
                                                <label for="id_ekspedisi">Ekspedisi</label>
                                                <select id="id_ekspedisi" class="form-select @error('id_ekspedisi') is-invalid @enderror"
                                                    name="id_ekspedisi">
                                                    <option value="">-- Pilih Ekspedisi --</option>
                                                    <option value="other" {{ old('id_ekspedisi') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                    @foreach($ekspedisis as $ekspedisi)
                                                        <option value="{{ $ekspedisi->id }}" {{ old('id_ekspedisi') == $ekspedisi->id ? 'selected' : '' }}>{{ $ekspedisi->nama_ekspedisi }}</option>
                                                    @endforeach
                                                </select>
                                                @error('id_ekspedisi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                
                                                <!-- Input manual yang awalnya disembunyikan -->
                                                <div id="manual_ekspedisi_input" class="mt-2" style="display: none;">
                                                    <label for="nama_ekspedisi_manual">Nama Ekspedisi <span class="text-danger">*</span></label>
                                                    <input type="text" id="nama_ekspedisi_manual" class="form-control @error('nama_ekspedisi_manual') is-invalid @enderror" 
                                                        name="nama_ekspedisi_manual" value="{{ old('nama_ekspedisi_manual') }}" placeholder="Masukkan nama ekspedisi">
                                                    @error('nama_ekspedisi_manual')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- No. Polisi -->
                                            <div class="col-md-6">
                                                <label for="no_polisi">No. Polisi <span class="text-danger">*</span></label>
                                                <input type="text" id="no_polisi" class="form-control @error('no_polisi') is-invalid @enderror"
                                                    name="no_polisi" value="{{ old('no_polisi') }}" required>
                                                @error('no_polisi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Nama Supir -->
                                            <div class="col-md-6">
                                                <label for="nama_supir">Nama Supir <span class="text-danger">*</span></label>
                                                <input type="text" id="nama_supir" class="form-control @error('nama_supir') is-invalid @enderror"
                                                    name="nama_supir" value="{{ old('nama_supir') }}" required>
                                                @error('nama_supir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Waktu Kedatangan -->
                                            <div class="col-md-6">
                                                <label for="waktu_kedatangan">Waktu Kedatangan <span class="text-danger">*</span></label>
                                                <input type="time" id="waktu_kedatangan" class="form-control @error('waktu_kedatangan') is-invalid @enderror"
                                                    name="waktu_kedatangan" value="{{ old('waktu_kedatangan') }}" required>
                                                @error('waktu_kedatangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Suhu Mobil -->
                                            <div class="col-md-6">
                                                <label for="suhu_mobil">Suhu Mobil <span class="text-danger">*</span></label>
                                                <input type="text" id="suhu_mobil" class="form-control @error('suhu_mobil') is-invalid @enderror"
                                                    name="suhu_mobil" placeholder="Contoh: -18°C" value="{{ old('suhu_mobil') }}" required>
                                                @error('suhu_mobil')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Universal Import Section -->
                                            <div class="alert border-primary mt-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-2 text-white"><i class="bi bi-lightning-charge-fill text-warning"></i> <strong>CARA CEPAT - Import Multiple Produk Return Sekaligus</strong></h6>
                                                        <p class="mb-2 text-white" style="font-size: 0.95rem;">Untuk return dengan <strong>banyak produk berbeda</strong>, gunakan fitur ini untuk menghemat waktu:</p>
                                                        <ol class="mb-0 ps-3 text-white" style="font-size: 0.90rem;">
                                                            <li>Download <strong>Template Universal</strong> (berisi semua produk)</li>
                                                            <li>Isi customer, alasan return, kode produksi, ED, jumlah untuk produk yang dibutuhkan</li>
                                                            <li>Hapus baris produk yang tidak digunakan</li>
                                                            <li>Upload file Excel</li>
                                                        </ol>
                                                    </div>
                                                    <div class="text-md-end mt-3 mt-md-0 ms-md-3 flex-shrink-0">
                                                        <a href="{{ route('return-barang.download-template-universal') }}" 
                                                        class="btn btn-light btn-sm mb-2 d-block" style="font-weight: 600;">
                                                            <i class="bi bi-download"></i> Download Template Universal
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-warning d-block" id="btn-import-universal-return" style="font-weight: 600; color: #000;">
                                                            <i class="bi bi-file-earmark-excel"></i> Import Universal
                                                        </button>
                                                        <input type="file" id="file-import-universal-return" accept=".xlsx,.xls" style="display:none;">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- DATA PRODUK MULTIPLE -->
                                            <h5 class="text-primary mb-3 mt-4">Data Produk <span class="text-danger">*</span></h5>
                                            <div id="produk-container">
                                                <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;" data-row-index="0" data-old-produk-id="{{ old('produk_data.0.id_produk') }}">
                                                    <h6 class="text-secondary mb-3">Produk #1</h6>
                                                    <div class="row">
                                                        <!-- Kategori -->
                                                        <div class="col-md-6">
                                                            <label>Kategori <span class="text-danger">*</span></label>
                                                            <select class="choices form-select kategori-produk-select @error('produk_data.0.kategori_code') is-invalid @enderror"
                                                                name="produk_data[0][kategori_code]" required>
                                                                <option value="">Pilih Kategori</option>
                                                                @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                    <option value="{{ $kategori }}" {{ old('produk_data.0.kategori_code') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('produk_data.0.kategori_code')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Nama Produk -->
                                                        <div class="col-md-6">
                                                            <label>Nama Produk <span class="text-danger">*</span></label>
                                                            <select class="form-select produk-select @error('produk_data.0.id_produk') is-invalid @enderror"
                                                                name="produk_data[0][id_produk]" required>
                                                                <option value="">Pilih Produk</option>
                                                            </select>
                                                            @error('produk_data.0.id_produk')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Kondisi Produk -->
                                                        <div class="col-md-6">
                                                            <label>Kondisi Produk <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('produk_data.0.kondisi_produk') is-invalid @enderror"
                                                                name="produk_data[0][kondisi_produk]" required>
                                                                <option value="">-- Pilih Kondisi --</option>
                                                                <option value="Frozen" {{ old('produk_data.0.kondisi_produk') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                                <option value="Fresh" {{ old('produk_data.0.kondisi_produk') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                                <option value="Dry" {{ old('produk_data.0.kondisi_produk') == 'Dry' ? 'selected' : '' }}>Dry</option>
                                                            </select>
                                                            @error('produk_data.0.kondisi_produk')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Suhu Produk -->
                                                        <div class="col-md-6">
                                                            <label>Suhu Produk</label>
                                                            <input type="text" class="form-control @error('produk_data.0.suhu_produk') is-invalid @enderror"
                                                                name="produk_data[0][suhu_produk]" placeholder="Contoh: -18°C" value="{{ old('produk_data.0.suhu_produk') }}">
                                                            @error('produk_data.0.suhu_produk')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Customer <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('produk_data.0.id_customer') is-invalid @enderror" name="produk_data[0][id_customer]" required>
                                                                <option value="">-- Pilih Customer --</option>
                                                                @foreach($customers as $customer)
                                                                    <option value="{{ $customer->id }}" {{ old('produk_data.0.id_customer') == $customer->id ? 'selected' : '' }}>{{ $customer->nama_cust }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('produk_data.0.id_customer')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Alasan Return <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control @error('produk_data.0.alasan_return') is-invalid @enderror" name="produk_data[0][alasan_return]" value="{{ old('produk_data.0.alasan_return') }}" required>
                                                            @error('produk_data.0.alasan_return')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <!-- Kode Produksi -->
                                                        <div class="col-md-6">
                                                            <label>Kode Produksi <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control @error('produk_data.0.kode_produksi') is-invalid @enderror"
                                                                name="produk_data[0][kode_produksi]" value="{{ old('produk_data.0.kode_produksi') }}" required>
                                                            @error('produk_data.0.kode_produksi')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Expired Date -->
                                                        <div class="col-md-6">
                                                            <label>Expired Date <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control @error('produk_data.0.expired_date') is-invalid @enderror"
                                                                name="produk_data[0][expired_date]" value="{{ old('produk_data.0.expired_date') }}" required>
                                                            @error('produk_data.0.expired_date')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Jumlah Barang -->
                                                        <div class="col-md-6">
                                                            <label>Jumlah (Karung/Box/Pack) <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control @error('produk_data.0.jumlah_barang') is-invalid @enderror"
                                                                name="produk_data[0][jumlah_barang]" placeholder="Contoh: 10 Karung" value="{{ old('produk_data.0.jumlah_barang') }}" required>
                                                            @error('produk_data.0.jumlah_barang')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- SECTION: KONDISI & INSPEKSI -->
                                                        <div class="col-md-12 mb-3 mt-3">
                                                            <h6 class="text-primary"><strong>Kondisi & Inspeksi</strong></h6>
                                                            <hr>
                                                        </div>

                                                        <!-- Kondisi Kemasan -->
                                                        <div class="col-md-6">
                                                            <label><strong>Kondisi Kemasan <span class="text-danger">*</span></strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="produk_data[0][kondisi_kemasan]" value="1" {{ old('produk_data.0.kondisi_kemasan') == '1' ? 'checked' : '' }} required>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="produk_data[0][kondisi_kemasan]" value="0" {{ old('produk_data.0.kondisi_kemasan') == '0' ? 'checked' : '' }} required>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                            @error('produk_data.0.kondisi_kemasan')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Kondisi Produk Check -->
                                                        <div class="col-md-6">
                                                            <label><strong>Kondisi Produk <span class="text-danger">*</span></strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="produk_data[0][kondisi_produk_check]" value="1" {{ old('produk_data.0.kondisi_produk_check') == '1' ? 'checked' : '' }} required>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="produk_data[0][kondisi_produk_check]" value="0" {{ old('produk_data.0.kondisi_produk_check') == '0' ? 'checked' : '' }} required>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                            @error('produk_data.0.kondisi_produk_check')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Rekomendasi -->
                                                        <div class="col-md-12">
                                                            <label>Rekomendasi <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control @error('produk_data.0.rekomendasi') is-invalid @enderror"
                                                                name="produk_data[0][rekomendasi]" value="{{ old('produk_data.0.rekomendasi') }}" required>
                                                            @error('produk_data.0.rekomendasi')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Keterangan -->
                                                        <div class="col-md-12">
                                                            <label>Keterangan</label>
                                                            <textarea class="form-control @error('produk_data.0.keterangan') is-invalid @enderror"
                                                                name="produk_data[0][keterangan]" rows="2" placeholder="Masukkan keterangan tambahan">{{ old('produk_data.0.keterangan') }}</textarea>
                                                            @error('produk_data.0.keterangan')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <!-- Remove Button -->
                                                        <div class="col-md-12 mt-3">
                                                            <button type="button" class="btn btn-sm btn-danger remove-produk" style="display: none;">Hapus Produk</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary" id="add-produk">+ Tambah Produk</button>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end align-items-center mt-3" style="position: relative; z-index: 9999;">
                                        <div>
                                            <button id="btn-submit-return" type="submit" class="btn btn-primary me-1 mb-1" style="position: relative; z-index: 10000;">Simpan Return Barang</button>
                                            <a href="{{ route('return-barang.index') }}" class="btn btn-light-secondary me-1 mb-1 btn-kembali-confirm">Kembali</a>
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

<style>
    .choices,
    .choices__inner,
    .choices__list,
    .choices__list--single {
        z-index: 1;
    }

    .choices__list--dropdown {
        z-index: 100;
        pointer-events: none;
    }

    .choices.is-open .choices__list--dropdown {
        pointer-events: auto;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== FIX Z-INDEX DROPDOWN OVERLAP =====
    // Monitor all Choices.js dropdowns and adjust parent z-index
    const observeChoicesDropdown = () => {
        document.querySelectorAll('.choices').forEach(choicesEl => {
            choicesEl.addEventListener('showDropdown', () => {
                const produkRow = choicesEl.closest('.produk-row');
                if (produkRow) {
                    produkRow.classList.add('dropdown-active');
                }
            });
            
            choicesEl.addEventListener('hideDropdown', () => {
                const produkRow = choicesEl.closest('.produk-row');
                if (produkRow) {
                    produkRow.classList.remove('dropdown-active');
                }
            });
        });
        
        // Alternative: Use MutationObserver to detect is-open class
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const target = mutation.target;
                    if (target.classList.contains('choices')) {
                        const produkRow = target.closest('.produk-row');
                        if (produkRow) {
                            if (target.classList.contains('is-open')) {
                                produkRow.classList.add('dropdown-active');
                            } else {
                                produkRow.classList.remove('dropdown-active');
                            }
                        }
                    }
                }
            });
        });
        
        document.querySelectorAll('.choices').forEach(choicesEl => {
            observer.observe(choicesEl, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    };
    
    // Initialize observer after a short delay
    setTimeout(observeChoicesDropdown, 500);
    
    // ===== ORIGINAL CODE =====
    const form = document.getElementById('form-pemeriksaan-return-barang-customer');
    const submitBtn = document.getElementById('btn-submit-return');

    function initChoicesOnce(selectEl) {
        if (!selectEl) return null;
        if (selectEl.dataset && selectEl.dataset.choicesInitialized) return null;

        // Trim whitespace pada option text (safety net)
        Array.from(selectEl.options).forEach(function(opt) {
            opt.text = opt.text.trim();
        });

        const instance = new Choices(selectEl, {
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
            fuseOptions: {
                includeScore: true,
                threshold: 0.4,
                distance: 1000,
                tokenize: true,
                matchAllTokens: false
            }
        });
        if (selectEl.dataset) {
            selectEl.dataset.choicesInitialized = 'true';
        }
        selectEl._choicesInstance = instance;
        return instance;
    }

    // Inisialisasi Choices.js manual untuk dropdown yang tidak pakai class 'choices'
    initChoicesOnce(document.getElementById('id_ekspedisi'));

    // Inisialisasi semua select customer di row produk yang ada
    document.querySelectorAll('#produk-container select[name$="[id_customer]"]').forEach((selectEl) => {
        initChoicesOnce(selectEl);
    });

    // Inisialisasi kategori selects (masih pakai class choices, akan diproses di bawah)
    document.querySelectorAll('select.choices').forEach((selectEl) => {
        if (selectEl.classList && selectEl.classList.contains('produk-select')) {
            return;
        }
        initChoicesOnce(selectEl);
    });

    if (submitBtn && form) {
        submitBtn.addEventListener('click', function(e) {
            if (typeof form.requestSubmit === 'function') {
                e.preventDefault();
                form.requestSubmit();
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add('was-validated');
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid && typeof firstInvalid.scrollIntoView === 'function') {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    if (typeof firstInvalid.focus === 'function') {
                        firstInvalid.focus();
                    }
                }
            }
        });
    }

    document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
        el.addEventListener('click', function(e) {
            const ok = confirm('Data belum disimpan. Yakin ingin kembali ke halaman index?');
            if (!ok) e.preventDefault();
        });
    });

    const ekspedisiSelect = document.getElementById('id_ekspedisi');
    const manualInput = document.getElementById('manual_ekspedisi_input');
    const manualEkspedisiField = document.getElementById('nama_ekspedisi_manual');

    // Tampilkan/sembunyikan input manual saat halaman dimuat
    if (ekspedisiSelect) {
        if (ekspedisiSelect.value === 'other') {
            if (manualInput) manualInput.style.display = 'block';
            if (manualEkspedisiField) manualEkspedisiField.required = true;
        } else {
            if (manualEkspedisiField) manualEkspedisiField.required = false;
        }

        // Tampilkan/sembunyikan input manual saat dropdown berubah
        ekspedisiSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                if (manualInput) manualInput.style.display = 'block';
                if (manualEkspedisiField) manualEkspedisiField.required = true;
            } else {
                if (manualInput) manualInput.style.display = 'none';
                if (manualEkspedisiField) {
                    manualEkspedisiField.required = false;
                    manualEkspedisiField.value = '';
                }
            }
        });
    }

    // Update remove button visibility
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#produk-container .produk-row');
        rows.forEach((row, index) => {
            const removeBtn = row.querySelector('.remove-produk');
            if (removeBtn) {
                removeBtn.style.display = rows.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    // Add produk field function
    window.addProdukRow = function() {
        const container = document.getElementById('produk-container');
        const rows = container.querySelectorAll('.produk-row');
        const produkIndex = rows.length;
        const newRow = document.createElement('div');
        newRow.className = 'produk-row mb-4 p-3 border rounded';
        newRow.style.backgroundColor = '#f8f9fa';
        newRow.dataset.rowIndex = String(produkIndex);
        newRow.innerHTML = `
            <h6 class="text-secondary mb-3">Produk #${produkIndex + 1}</h6>
            <div class="row">
                <!-- Kategori -->
                <div class="col-md-6">
                    <label>Kategori <span class="text-danger">*</span></label>
                    <select class="choices form-select kategori-produk-select" name="produk_data[${produkIndex}][kategori_code]" required>
                        <option value="">Pilih Kategori</option>
                        @foreach(($produkKategoriOptions ?? []) as $kategori)
                            <option value="{{ $kategori }}">{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Produk -->
                <div class="col-md-6">
                    <label>Nama Produk <span class="text-danger">*</span></label>
                    <select class="form-select produk-select" name="produk_data[${produkIndex}][id_produk]" required>
                        <option value="">Pilih Produk</option>
                    </select>
                </div>

                <!-- Kondisi Produk -->
                <div class="col-md-6">
                    <label>Kondisi Produk <span class="text-danger">*</span></label>
                    <select class="form-select" name="produk_data[${produkIndex}][kondisi_produk]" required>
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Frozen">Frozen</option>
                        <option value="Fresh">Fresh</option>
                        <option value="Dry">Dry</option>
                    </select>
                </div>

                <!-- Suhu Produk -->
                <div class="col-md-6">
                    <label>Suhu Produk</label>
                    <input type="text" class="form-control" name="produk_data[${produkIndex}][suhu_produk]" placeholder="Contoh: -18°C">
                </div>
                <div class="col-md-6">
                    <label>Customer <span class="text-danger">*</span></label>
                    <select class="choices form-select" name="produk_data[${produkIndex}][id_customer]" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->nama_cust }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Alasan Return <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="produk_data[${produkIndex}][alasan_return]" required>
                </div>

                <!-- Kode Produksi -->
                <div class="col-md-6">
                    <label>Kode Produksi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="produk_data[${produkIndex}][kode_produksi]" required>
                </div>

                <!-- Expired Date -->
                <div class="col-md-6">
                    <label>Expired Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="produk_data[${produkIndex}][expired_date]" required>
                </div>

                <!-- Jumlah Barang -->
                <div class="col-md-6">
                    <label>Jumlah (Karung/Box/Pack) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="produk_data[${produkIndex}][jumlah_barang]" placeholder="Contoh: 10 Karung" required>
                </div>

                <!-- SECTION: KONDISI & INSPEKSI -->
                <div class="col-md-12 mb-3 mt-3">
                    <h6 class="text-primary"><strong>Kondisi & Inspeksi</strong></h6>
                    <hr>
                </div>

                <!-- Kondisi Kemasan -->
                <div class="col-md-6">
                    <label><strong>Kondisi Kemasan <span class="text-danger">*</span></strong></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="produk_data[${produkIndex}][kondisi_kemasan]" value="1" required>
                        <label class="form-check-label">Ya ✓</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="produk_data[${produkIndex}][kondisi_kemasan]" value="0" required>
                        <label class="form-check-label">Tidak ✗</label>
                    </div>
                </div>

                <!-- Kondisi Produk Check -->
                <div class="col-md-6">
                    <label><strong>Kondisi Produk <span class="text-danger">*</span></strong></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="produk_data[${produkIndex}][kondisi_produk_check]" value="1" required>
                        <label class="form-check-label">Ya ✓</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="produk_data[${produkIndex}][kondisi_produk_check]" value="0" required>
                        <label class="form-check-label">Tidak ✗</label>
                    </div>
                </div>

                <!-- Rekomendasi -->
                <div class="col-md-12">
                    <label>Rekomendasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="produk_data[${produkIndex}][rekomendasi]" required>
                </div>

                <!-- Keterangan -->
                <div class="col-md-12">
                    <label>Keterangan</label>
                    <textarea class="form-control" name="produk_data[${produkIndex}][keterangan]" rows="2" placeholder="Masukkan keterangan tambahan"></textarea>
                </div>

                <!-- Remove Button -->
                <div class="col-md-12 mt-3">
                    <button type="button" class="btn btn-sm btn-danger remove-produk">Hapus Produk</button>
                </div>
            </div>
        `;
        container.appendChild(newRow);

        const newCustomerSelect = newRow.querySelector(`select[name="produk_data[${produkIndex}][id_customer]"]`);
        if (newCustomerSelect) {
            initChoicesOnce(newCustomerSelect);
        }

        // Re-initialize Choices.js for new kategori select only
        const newKategoriSelect = newRow.querySelector('select.kategori-produk-select');
        if (newKategoriSelect) {
            initChoicesOnce(newKategoriSelect);
        }

        populateProdukOptionsForRow(newRow);
        updateRemoveButtons();
        return newRow;
    };

    const addBtn = document.getElementById('add-produk');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            window.addProdukRow();
        });
    }
    
    // Remove produk field
    document.getElementById('produk-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-produk')) {
            const ok = confirm('Yakin ingin menghapus produk ini?');
            if (!ok) return;

            const rows = document.querySelectorAll('#produk-container .produk-row');
            if (rows.length > 1) {
                e.target.closest('.produk-row').remove();

                const remainingRows = document.querySelectorAll('#produk-container .produk-row');
                remainingRows.forEach((row, idx) => {
                    row.dataset.rowIndex = String(idx);

                    const title = row.querySelector('h6.text-secondary');
                    if (title) {
                        title.textContent = `Produk #${idx + 1}`;
                    }

                    row.querySelectorAll('input[name], select[name], textarea[name]').forEach((el) => {
                        const name = el.getAttribute('name');
                        if (!name) return;
                        const nextName = name.replace(/produk_data\[\d+\]\[/g, `produk_data[${idx}][`);
                        if (nextName !== name) el.setAttribute('name', nextName);
                    });
                });

                updateRemoveButtons();
            } else {
                alert('Minimal harus ada 1 produk!');
            }
        }
    });

    const produkByKategori = @json($produkByKategori ?? []);
    const produkKategoriById = @json($produkKategoriById ?? []);

    window.populateProdukOptionsForRow = function populateProdukOptionsForRow(rowEl) {
        const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
        const produkSelect = rowEl.querySelector('select.produk-select');

        if (!kategoriSelect || !produkSelect) return;

        const kategori = kategoriSelect.value;
        const rawOptions = (produkByKategori && produkByKategori[kategori]) ? produkByKategori[kategori] : [];
        const options = Array.isArray(rawOptions) ? rawOptions : Object.values(rawOptions || {});

        const choiceItems = [{ value: '', label: 'Pilih Produk', selected: true }].concat(
            options.map((opt) => ({
                value: String(opt.id),
                label: opt.nama,
            }))
        );

        const desiredProdukId = rowEl.dataset.oldProdukId ? String(rowEl.dataset.oldProdukId) : '';

        if (rowEl._populateProdukTimer) {
            clearTimeout(rowEl._populateProdukTimer);
        }

        rowEl._populateProdukTimer = setTimeout(() => {
            // Always resolve current select from DOM to avoid stale references
            const currentProdukSelect = rowEl.querySelector('select.produk-select');
            if (!currentProdukSelect) return;

            // Destroy previous Choices instance (if any) to prevent DOM corruption
            if (rowEl._produkChoicesInstance) {
                try {
                    rowEl._produkChoicesInstance.destroy();
                } catch (e) {
                    // ignore
                }
                rowEl._produkChoicesInstance = null;
            }

            const freshProdukSelect = currentProdukSelect.cloneNode(false);
            freshProdukSelect.innerHTML = '';

            choiceItems.forEach((it) => {
                const o = document.createElement('option');
                o.value = String(it.value);
                o.textContent = it.label;
                if (it.selected) o.selected = true;
                freshProdukSelect.appendChild(o);
            });

            const wrapper = currentProdukSelect.closest('.choices');
            if (wrapper && wrapper.parentNode) {
                wrapper.parentNode.replaceChild(freshProdukSelect, wrapper);
            } else if (currentProdukSelect.parentNode) {
                currentProdukSelect.parentNode.replaceChild(freshProdukSelect, currentProdukSelect);
            }

            const instance = new Choices(freshProdukSelect, {
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

            if (desiredProdukId) {
                instance.setChoiceByValue(desiredProdukId);
            }

            rowEl._produkChoicesInstance = instance;
        }, 50);
    }

    document.addEventListener('change', function(e) {
        const target = e.target;
        if (target && target.matches('select.kategori-produk-select')) {
            const row = target.closest('.produk-row');
            if (row) {
                populateProdukOptionsForRow(row);
            }
        }
    });

    document.querySelectorAll('#produk-container .produk-row').forEach((row, idx) => {
        row.dataset.rowIndex = String(idx);

        const produkSelect = row.querySelector('select.produk-select');
        if (produkSelect) {
            if (!row.dataset.oldProdukId) {
                const oldVal = produkSelect.value;
                row.dataset.oldProdukId = oldVal || '';
            }
        }

        const kategoriSelect = row.querySelector('select.kategori-produk-select');
        if (kategoriSelect) {
            if (!kategoriSelect.value) {
                const desiredProdukId = row.dataset.oldProdukId;
                if (desiredProdukId && produkKategoriById && produkKategoriById[desiredProdukId]) {
                    kategoriSelect.value = String(produkKategoriById[desiredProdukId]);
                }
            }
        }

        populateProdukOptionsForRow(row);
    });

    // no-op: customer & alasan sekarang per-produk (produk_data[index][...])
});

// ===== UNIVERSAL IMPORT HANDLER =====
const btnImportUniversal = document.getElementById('btn-import-universal-return');
const fileImportUniversal = document.getElementById('file-import-universal-return');

if (btnImportUniversal && fileImportUniversal) {
    // Click button to trigger file input
    btnImportUniversal.addEventListener('click', function() {
        fileImportUniversal.click();
    });

    // Handle file selection
    fileImportUniversal.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file type
        const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
        if (!validTypes.includes(file.type)) {
            alert('File harus berformat Excel (.xlsx atau .xls)');
            fileImportUniversal.value = '';
            return;
        }

        // Show loading
        btnImportUniversal.disabled = true;
        btnImportUniversal.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...';

        try {
            // Create FormData
            const formData = new FormData();
            formData.append('file', file);

            // Get CSRF token safely
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfInput = document.querySelector('input[name="_token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : (csrfInput ? csrfInput.value : '');

            // Send to server
            const response = await fetch('{{ route("return-barang.import-universal") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            });

            const result = await response.json();

            if (result.success && result.data && result.data.length > 0) {
                // Clear existing rows
                document.getElementById('produk-container').innerHTML = '';
                
                // Populate form synchronously with imported data
                result.data.forEach((produkData) => {
                    if (typeof window.addProdukRow === 'function') {
                        const newRow = window.addProdukRow();
                        if (newRow) {
                            populateProdukRow(newRow, produkData);
                        }
                    }
                });

                // Show success message
                let message = `✅ Import berhasil! ${result.stats.valid} produk diproses.`;
                if (result.stats.invalid > 0) {
                    message += `\n⚠️ ${result.stats.invalid} baris diabaikan karena error:\n`;
                    message += result.errors.slice(0, 5).join('\n');
                    if (result.errors.length > 5) {
                        message += `\n... dan ${result.errors.length - 5} error lainnya`;
                    }
                }
                alert(message);

            } else {
                // Show errors
                let errorMsg = result.message || 'Import gagal!';
                if (result.errors && result.errors.length > 0) {
                    errorMsg += '\n\nError:\n' + result.errors.slice(0, 10).join('\n');
                    if (result.errors.length > 10) {
                        errorMsg += `\n... dan ${result.errors.length - 10} error lainnya`;
                    }
                }
                alert(errorMsg);
            }

        } catch (error) {
            console.error('Import error:', error);
            alert('Terjadi kesalahan saat import: ' + error.message);
        } finally {
            // Reset button
            btnImportUniversal.disabled = false;
            btnImportUniversal.innerHTML = '<i class="bi bi-file-earmark-excel"></i> Import Universal';
            fileImportUniversal.value = '';
        }
    });
}

/**
 * Helper to set Choices.js value safely with retries for DOM readiness
 */
function setChoicesValue(selectEl, value) {
    if (!selectEl || value === undefined || value === null) return;
    const strVal = String(value);
    selectEl.value = strVal;
    
    const trySet = () => {
        const inst = selectEl._choicesInstance || selectEl.choicesInstance;
        if (inst && typeof inst.setChoiceByValue === 'function') {
            try {
                inst.setChoiceByValue(strVal);
            } catch (e) {
                try {
                    if (!isNaN(value)) {
                        inst.setChoiceByValue(Number(value));
                    }
                } catch (err) {}
            }
        }
    };
    
    trySet();
    setTimeout(trySet, 50);
    setTimeout(trySet, 200);
}

/**
 * Populate produk row element with imported data
 */
function populateProdukRow(rowEl, data) {
    if (!rowEl || !data) return;

    // Helper for input setting
    const setInputValue = (selector, val) => {
        const input = rowEl.querySelector(selector);
        if (input && val !== undefined && val !== null) {
            input.value = val;
        }
    };

    // 1. Set text & date & textarea & simple select inputs
    setInputValue('input[name$="[alasan_return]"]', data.alasan_return);
    setInputValue('select[name$="[kondisi_produk]"]', data.kondisi_produk);
    setInputValue('input[name$="[suhu_produk]"]', data.suhu_produk);
    setInputValue('input[name$="[kode_produksi]"]', data.kode_produksi);
    setInputValue('input[name$="[expired_date]"]', data.expired_date);
    setInputValue('input[name$="[jumlah_barang]"]', data.jumlah_barang);
    setInputValue('input[name$="[rekomendasi]"]', data.rekomendasi);
    setInputValue('textarea[name$="[keterangan]"]', data.keterangan);

    // 2. Set Radio buttons for kondisi_kemasan & kondisi_produk_check
    const kemasanVal = (data.kondisi_kemasan == 1 || data.kondisi_kemasan === '1' || data.kondisi_kemasan === 'ya') ? '1' : '0';
    const kemasanRadio = rowEl.querySelector(`input[name$="[kondisi_kemasan]"][value="${kemasanVal}"]`);
    if (kemasanRadio) kemasanRadio.checked = true;

    const produkCheckVal = (data.kondisi_produk_check == 1 || data.kondisi_produk_check === '1' || data.kondisi_produk_check === 'ya') ? '1' : '0';
    const produkCheckRadio = rowEl.querySelector(`input[name$="[kondisi_produk_check]"][value="${produkCheckVal}"]`);
    if (produkCheckRadio) produkCheckRadio.checked = true;

    // 3. Set Kategori Select (Choices)
    const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
    if (kategoriSelect && data.kategori_code) {
        setChoicesValue(kategoriSelect, data.kategori_code);
    }

    // 4. Set Produk Select (Choices with Category dependency)
    if (data.id_produk) {
        rowEl.dataset.oldProdukId = String(data.id_produk);
    }
    if (typeof window.populateProdukOptionsForRow === 'function') {
        window.populateProdukOptionsForRow(rowEl);
    }

    // 5. Set Customer Select (Choices)
    const customerSelect = rowEl.querySelector('select[name$="[id_customer]"]');
    if (customerSelect && data.id_customer) {
        setChoicesValue(customerSelect, data.id_customer);
    }
}

</script>
@endsection