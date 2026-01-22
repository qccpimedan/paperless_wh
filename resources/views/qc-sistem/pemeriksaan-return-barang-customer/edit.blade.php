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
                    <h3>Edit Pemeriksaan Return Barang</h3>
                    <p class="text-subtitle text-muted">Edit pemeriksaan return barang</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('return-barang.index') }}">Pemeriksaan Return Barang</a></li>
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
                            <h4 class="card-title">Form Edit Pemeriksaan Return Barang</h4>
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

                                <form class="form form-horizontal" action="{{ route('return-barang.update', $pemeriksaanReturnBarangCustomer->uuid) }}" method="POST" novalidate>
                                    @csrf
                                    @method('PUT')
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
                                                    name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::parse($pemeriksaanReturnBarangCustomer->tanggal)->format('Y-m-d')) }}" required>
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
                                                        <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanReturnBarangCustomer->id_shift) == $shift->id ? 'selected' : '' }}>
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
                                                    @foreach($ekspedisis as $ekspedisi)
                                                        <option value="{{ $ekspedisi->id }}" {{ old('id_ekspedisi', $pemeriksaanReturnBarangCustomer->id_ekspedisi) == $ekspedisi->id ? 'selected' : '' }}>
                                                            {{ $ekspedisi->nama_ekspedisi }}
                                                        </option>
                                                    @endforeach
                                                    <option value="other" {{ old('id_ekspedisi') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                </select>
                                                @error('id_ekspedisi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                
                                                <!-- Input manual yang awalnya disembunyikan -->
                                                <div id="manual_ekspedisi_input" class="mt-2" style="display: none;">
                                                    <label for="nama_ekspedisi_manual">Nama Ekspedisi <span class="text-danger">*</span></label>
                                                    <input type="text" id="nama_ekspedisi_manual" class="choices form-control @error('nama_ekspedisi_manual') is-invalid @enderror" 
                                                        name="nama_ekspedisi_manual" value="{{ old('nama_ekspedisi_manual') }}" placeholder="Masukkan nama ekspedisi" required>
                                                    @error('nama_ekspedisi_manual')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- No. Polisi -->
                                            <div class="col-md-6">
                                                <label for="no_polisi">No. Polisi <span class="text-danger">*</span></label>
                                                <input type="text" id="no_polisi" class="form-control @error('no_polisi') is-invalid @enderror"
                                                    name="no_polisi" value="{{ old('no_polisi', $pemeriksaanReturnBarangCustomer->no_polisi) }}" required>
                                                @error('no_polisi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Nama Supir -->
                                            <div class="col-md-6">
                                                <label for="nama_supir">Nama Supir <span class="text-danger">*</span></label>
                                                <input type="text" id="nama_supir" class="form-control @error('nama_supir') is-invalid @enderror"
                                                    name="nama_supir" value="{{ old('nama_supir', $pemeriksaanReturnBarangCustomer->nama_supir) }}" required>
                                                @error('nama_supir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Waktu Kedatangan -->
                                            <div class="col-md-6">
                                                <label for="waktu_kedatangan">Waktu Kedatangan <span class="text-danger">*</span></label>
                                                <input type="time" id="waktu_kedatangan" class="form-control @error('waktu_kedatangan') is-invalid @enderror"
                                                    name="waktu_kedatangan" value="{{ old('waktu_kedatangan', $pemeriksaanReturnBarangCustomer->waktu_kedatangan) }}" required>
                                                @error('waktu_kedatangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Suhu Mobil -->
                                            <div class="col-md-6">
                                                <label for="suhu_mobil">Suhu Mobil <span class="text-danger">*</span></label>
                                                <input type="text" id="suhu_mobil" class="form-control @error('suhu_mobil') is-invalid @enderror"
                                                    name="suhu_mobil" placeholder="Contoh: -18°C" value="{{ old('suhu_mobil', $pemeriksaanReturnBarangCustomer->suhu_mobil) }}" required>
                                                @error('suhu_mobil')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- DATA PRODUK MULTIPLE -->
                                            <h5 class="text-primary mb-3 mt-4">Data Produk <span class="text-danger">*</span></h5>
                                            <div id="produk-container">
                                                @if($pemeriksaanReturnBarangCustomer->produk_data && count($pemeriksaanReturnBarangCustomer->produk_data) > 0)
                                                    @foreach($pemeriksaanReturnBarangCustomer->produk_data as $index => $produk)
                                                        <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;"
                                                            data-row-index="{{ $index }}"
                                                            data-old-produk-id="{{ old('produk_data.' . $index . '.id_produk', $produk['id_produk'] ?? '') }}">
                                                            <h6 class="text-secondary mb-3">Produk #{{ $index + 1 }}</h6>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label>Customer <span class="text-danger">*</span></label>
                                                                    <select class="form-select @error('produk_data.' . $index . '.id_customer') is-invalid @enderror" name="produk_data[{{ $index }}][id_customer]" required>
                                                                        <option value="">-- Pilih Customer --</option>
                                                                        @foreach($customers as $customer)
                                                                            <option value="{{ $customer->id }}" {{ old('produk_data.' . $index . '.id_customer', $produk['id_customer'] ?? '') == $customer->id ? 'selected' : '' }}>
                                                                                {{ $customer->nama_cust }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('produk_data.' . $index . '.id_customer')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label>Alasan Return <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control @error('produk_data.' . $index . '.alasan_return') is-invalid @enderror" name="produk_data[{{ $index }}][alasan_return]" value="{{ old('produk_data.' . $index . '.alasan_return', $produk['alasan_return'] ?? '') }}" required>
                                                                    @error('produk_data.' . $index . '.alasan_return')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label>Kondisi Produk <span class="text-danger">*</span></label>
                                                                    <select class="form-select" name="produk_data[{{ $index }}][kondisi_produk]" required>
                                                                        <option value="">-- Pilih Kondisi --</option>
                                                                        <option value="Frozen" {{ $produk['kondisi_produk'] == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                                        <option value="Fresh" {{ $produk['kondisi_produk'] == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                                        <option value="Dry" {{ $produk['kondisi_produk'] == 'Dry' ? 'selected' : '' }}>Dry</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Kategori <span class="text-danger">*</span></label>
                                                                    <select class="choices form-select kategori-produk-select"
                                                                        name="produk_data[{{ $index }}][kategori_code]" required>
                                                                        <option value="">Pilih Kategori</option>
                                                                        @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                            <option value="{{ $kategori }}"
                                                                                {{ ($produk['kategori_code'] ?? '') == $kategori ? 'selected' : '' }}>
                                                                                {{ $kategori }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label>Nama Produk <span class="text-danger">*</span></label>
                                                                    <select class="form-select produk-select"
                                                                        name="produk_data[{{ $index }}][id_produk]" required>
                                                                        <option value="">Pilih Produk</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mt-3">
                                                                    <label>Suhu Produk</label>
                                                                    <input type="text" class="form-control" name="produk_data[{{ $index }}][suhu_produk]" placeholder="Contoh: -18°C" value="{{ $produk['suhu_produk'] ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 mt-3">
                                                                    <label>Kode Produksi <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="produk_data[{{ $index }}][kode_produksi]" value="{{ $produk['kode_produksi'] ?? '' }}" required>
                                                                </div>
                                                                <div class="col-md-6 mt-3">
                                                                    <label>Expired Date <span class="text-danger">*</span></label>
                                                                    <input type="date" class="form-control" name="produk_data[{{ $index }}][expired_date]" value="{{ isset($produk['expired_date']) ? \Carbon\Carbon::parse($produk['expired_date'])->format('Y-m-d') : '' }}" required>
                                                                </div>
                                                                <div class="col-md-6 mt-3">
                                                                    <label>Jumlah Barang <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="produk_data[{{ $index }}][jumlah_barang]" placeholder="Contoh: 10 Karung" value="{{ $produk['jumlah_barang'] ?? '' }}" required>
                                                                </div>
                                                                <div class="col-md-12 mb-3 mt-3">
                                                                    <h6 class="text-primary"><strong>Kondisi & Inspeksi</strong></h6>
                                                                    <hr>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label><strong>Kondisi Kemasan <span class="text-danger">*</span></strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="produk_data[{{ $index }}][kondisi_kemasan]" value="1" {{ ($produk['kondisi_kemasan'] ?? false) ? 'checked' : '' }} required>
                                                                        <label class="form-check-label">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="produk_data[{{ $index }}][kondisi_kemasan]" value="0" {{ !($produk['kondisi_kemasan'] ?? false) ? 'checked' : '' }} required>
                                                                        <label class="form-check-label">Tidak ✗</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label><strong>Kondisi Produk <span class="text-danger">*</span></strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="produk_data[{{ $index }}][kondisi_produk_check]" value="1" {{ ($produk['kondisi_produk_check'] ?? false) ? 'checked' : '' }} required>
                                                                        <label class="form-check-label">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="produk_data[{{ $index }}][kondisi_produk_check]" value="0" {{ !($produk['kondisi_produk_check'] ?? false) ? 'checked' : '' }} required>
                                                                        <label class="form-check-label">Tidak ✗</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 mt-3">
                                                                    <label>Rekomendasi <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="produk_data[{{ $index }}][rekomendasi]" value="{{ $produk['rekomendasi'] ?? '' }}" required>
                                                                </div>
                                                                <div class="col-md-12 mt-3">
                                                                    <label>Keterangan</label>
                                                                    <textarea class="form-control" name="produk_data[{{ $index }}][keterangan]" rows="2" placeholder="Masukkan keterangan tambahan">{{ $produk['keterangan'] ?? '' }}</textarea>
                                                                </div>
                                                                <div class="col-md-12 mt-3">
                                                                    <button type="button" class="btn btn-sm btn-danger remove-produk" {{ count($pemeriksaanReturnBarangCustomer->produk_data) > 1 ? '' : 'style=display:none;' }}>Hapus Produk</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;"
                                                        data-row-index="0"
                                                        data-old-produk-id="{{ old('produk_data.0.id_produk', '') }}">
                                                        <h6 class="text-secondary mb-3">Produk #1</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Customer <span class="text-danger">*</span></label>
                                                                <select class="form-select @error('produk_data.0.id_customer') is-invalid @enderror" name="produk_data[0][id_customer]" required>
                                                                    <option value="">-- Pilih Customer --</option>
                                                                    @foreach($customers as $customer)
                                                                        <option value="{{ $customer->id }}" {{ old('produk_data.0.id_customer') == $customer->id ? 'selected' : '' }}>
                                                                            {{ $customer->nama_cust }}
                                                                        </option>
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

                                                            <div class="col-md-6">
                                                                <label>Kondisi Produk <span class="text-danger">*</span></label>
                                                                <select class="form-select" name="produk_data[0][kondisi_produk]" required>
                                                                    <option value="">-- Pilih Kondisi --</option>
                                                                    <option value="Frozen">Frozen</option>
                                                                    <option value="Fresh">Fresh</option>
                                                                    <option value="Dry">Dry</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Kategori <span class="text-danger">*</span></label>
                                                                <select class="choices form-select kategori-produk-select"
                                                                    name="produk_data[0][kategori_code]" required>
                                                                    <option value="">Pilih Kategori</option>
                                                                    @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                        <option value="{{ $kategori }}"
                                                                            {{ old('produk_data.0.kategori_code') == $kategori ? 'selected' : '' }}>
                                                                            {{ $kategori }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label>Nama Produk <span class="text-danger">*</span></label>
                                                                <select class="form-select produk-select"
                                                                    name="produk_data[0][id_produk]" required>
                                                                    <option value="">Pilih Produk</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mt-3">
                                                                <label>Suhu Produk</label>
                                                                <input type="text" class="form-control" name="produk_data[0][suhu_produk]" placeholder="Contoh: -18°C">
                                                            </div>
                                                            <div class="col-md-6 mt-3">
                                                                <label>Kode Produksi <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="produk_data[0][kode_produksi]" required>
                                                            </div>
                                                            <div class="col-md-6 mt-3">
                                                                <label>Expired Date <span class="text-danger">*</span></label>
                                                                <input type="date" class="form-control" name="produk_data[0][expired_date]" required>
                                                            </div>
                                                            <div class="col-md-6 mt-3">
                                                                <label>Jumlah Barang <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="produk_data[0][jumlah_barang]" placeholder="Contoh: 10 Karung" required>
                                                            </div>
                                                            <div class="col-md-12 mb-3 mt-3">
                                                                <h6 class="text-primary"><strong>Kondisi & Inspeksi</strong></h6>
                                                                <hr>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label><strong>Kondisi Kemasan <span class="text-danger">*</span></strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="produk_data[0][kondisi_kemasan]" value="1" required>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="produk_data[0][kondisi_kemasan]" value="0" required>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label><strong>Kondisi Produk <span class="text-danger">*</span></strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="produk_data[0][kondisi_produk_check]" value="1" required>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="produk_data[0][kondisi_produk_check]" value="0" required>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <label>Rekomendasi <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="produk_data[0][rekomendasi]" required>
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <label>Keterangan</label>
                                                                <textarea class="form-control" name="produk_data[0][keterangan]" rows="2" placeholder="Masukkan keterangan tambahan"></textarea>
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <button type="button" class="btn btn-sm btn-danger remove-produk" style="display: none;">Hapus Produk</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- <button type="button" class="btn btn-sm btn-primary mt-2" id="add-produk">+ Tambah Produk</button> -->

                                            <!-- Buttons -->
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Update Return Barang</button>
                                                <a href="{{ route('return-barang.index') }}" class="btn btn-secondary mb-1">Kembali</a>
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
    const ekspedisiSelect = document.getElementById('id_ekspedisi');
    const manualInput = document.getElementById('manual_ekspedisi_input');

    if (ekspedisiSelect && manualInput) {
        if (ekspedisiSelect.value === 'other') manualInput.style.display = 'block';
        ekspedisiSelect.addEventListener('change', function() {
            manualInput.style.display = (this.value === 'other') ? 'block' : 'none';
        });
    }

    const produkByKategori = @json($produkByKategori ?? []);
    const produkKategoriById = @json($produkKategoriById ?? []);

    function reindexProdukRows() {
        const rows = document.querySelectorAll('#produk-container .produk-row');
        rows.forEach((row, newIndex) => {
            row.dataset.rowIndex = String(newIndex);

            const title = row.querySelector('h6.text-secondary');
            if (title) {
                title.textContent = `Produk #${newIndex + 1}`;
            }

            // Update name attributes inside the row: produk_data[<n>][field]
            row.querySelectorAll('input[name], select[name], textarea[name]').forEach((el) => {
                const name = el.getAttribute('name');
                if (!name) return;
                const nextName = name.replace(/produk_data\[\d+\]\[/g, `produk_data[${newIndex}][`);
                if (nextName !== name) el.setAttribute('name', nextName);
            });

            // Keep oldProdukId updated for populate init
            const produkSelect = row.querySelector('select.produk-select');
            if (produkSelect) {
                row.dataset.oldProdukId = produkSelect.value || row.dataset.oldProdukId || '';
            }
        });
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#produk-container .produk-row');
        rows.forEach((row) => {
            const removeBtn = row.querySelector('.remove-produk');
            if (removeBtn) {
                removeBtn.style.display = rows.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    const produkContainer = document.getElementById('produk-container');
    if (produkContainer) {
        produkContainer.addEventListener('click', function(e) {
            if (e.target && e.target.closest('.remove-produk')) {
                const ok = confirm('Yakin ingin menghapus produk ini?');
                if (!ok) return;

                const rows = document.querySelectorAll('#produk-container .produk-row');
                if (rows.length > 1) {
                    e.target.closest('.produk-row').remove();
                    reindexProdukRows();
                    updateRemoveButtons();
                } else {
                    alert('Minimal harus ada 1 produk!');
                }
            }
        });
    }

    function populateProdukOptionsForRow(rowEl) {
        const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
        const produkSelect = rowEl.querySelector('select.produk-select');
        if (!kategoriSelect || !produkSelect) return;

        const kategori = kategoriSelect.value;
        const rawOptions = (produkByKategori && produkByKategori[kategori]) ? produkByKategori[kategori] : [];
        const options = Array.isArray(rawOptions) ? rawOptions : Object.values(rawOptions || {});

        const choiceItems = [{ value: '', label: 'Pilih Produk', selected: true }].concat(
            options.map((opt) => ({ value: String(opt.id), label: opt.nama }))
        );

        const desiredProdukId = rowEl.dataset.oldProdukId ? String(rowEl.dataset.oldProdukId) : '';

        if (rowEl._populateProdukTimer) clearTimeout(rowEl._populateProdukTimer);

        rowEl._populateProdukTimer = setTimeout(() => {
            const currentProdukSelect = rowEl.querySelector('select.produk-select');
            if (!currentProdukSelect) return;

            if (rowEl._produkChoicesInstance) {
                try { rowEl._produkChoicesInstance.destroy(); } catch (e) {}
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
                searchEnabled: true,
                searchPlaceholderValue: 'Cari...',
                itemSelectText: 'Tekan untuk memilih',
                noResultsText: 'Tidak ada hasil ditemukan',
                noChoicesText: 'Tidak ada pilihan tersedia',
                placeholder: true,
                placeholderValue: 'Pilih...'
            });

            if (desiredProdukId) instance.setChoiceByValue(desiredProdukId);
            rowEl._produkChoicesInstance = instance;
        }, 50);
    }

    document.addEventListener('change', function(e) {
        const target = e.target;
        if (target && target.matches('select.kategori-produk-select')) {
            const row = target.closest('.produk-row');
            if (row) populateProdukOptionsForRow(row);
        }
    });

    document.querySelectorAll('#produk-container .produk-row').forEach((row, idx) => {
        row.dataset.rowIndex = String(idx);

        const kategoriSelect = row.querySelector('select.kategori-produk-select');
        if (kategoriSelect && !kategoriSelect.value) {
            const desiredProdukId = row.dataset.oldProdukId;
            if (desiredProdukId && produkKategoriById && produkKategoriById[desiredProdukId]) {
                kategoriSelect.value = String(produkKategoriById[desiredProdukId]);
            }
        }

        populateProdukOptionsForRow(row);
    });

    reindexProdukRows();
    updateRemoveButtons();
});
</script>
@endsection