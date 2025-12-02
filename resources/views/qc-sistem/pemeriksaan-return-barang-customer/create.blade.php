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

                                <form class="form form-horizontal" action="{{ route('return-barang.store') }}" method="POST">
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
                                                <select id="id_ekspedisi" class="choices form-select @error('id_ekspedisi') is-invalid @enderror"
                                                    name="id_ekspedisi">
                                                    <option value="">-- Pilih Ekspedisi --</option>
                                                    @foreach($ekspedisis as $ekspedisi)
                                                        <option value="{{ $ekspedisi->id }}" {{ old('id_ekspedisi') == $ekspedisi->id ? 'selected' : '' }}>
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
                                                    <input type="text" id="nama_ekspedisi_manual" class="form-control @error('nama_ekspedisi_manual') is-invalid @enderror" 
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

                                            <!-- SECTION: DATA CUSTOMER & ALASAN -->
                                            <div class="col-md-12 mb-3 mt-3">
                                                <h5 class="text-primary"><strong>Data Customer & Alasan Return</strong></h5>
                                                <hr>
                                            </div>

                                            <!-- Customer -->
                                            <div class="col-md-6">
                                                <label for="id_customer">Customer <span class="text-danger">*</span></label>
                                                <select id="id_customer" class="choices form-select @error('id_customer') is-invalid @enderror"
                                                    name="id_customer" required>
                                                    <option value="">-- Pilih Customer --</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{ $customer->id }}" {{ old('id_customer') == $customer->id ? 'selected' : '' }}>
                                                            {{ $customer->nama_cust }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_customer')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Alasan Return -->
                                            <div class="col-md-6">
                                                <label for="alasan_return">Alasan Return <span class="text-danger">*</span></label>
                                                <input type="text" id="alasan_return" class="form-control @error('alasan_return') is-invalid @enderror"
                                                    name="alasan_return" value="{{ old('alasan_return') }}" required>
                                                @error('alasan_return')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- DATA PRODUK MULTIPLE -->
                                            <h5 class="text-primary mb-3 mt-4">Data Produk <span class="text-danger">*</span></h5>
                                            <div id="produk-container">
                                                <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                                    <h6 class="text-secondary mb-3">Produk #1</h6>
                                                    <div class="row">
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

                                                        <!-- Nama Produk -->
                                                        <div class="col-md-6">
                                                            <label>Nama Produk <span class="text-danger">*</span></label>
                                                            <select class="choices form-select produk-select @error('produk_data.0.id_produk') is-invalid @enderror"
                                                                name="produk_data[0][id_produk]" required>
                                                                <option value="">-- Pilih Produk --</option>
                                                                @foreach($produks as $produk)
                                                                    <option value="{{ $produk->id }}" {{ old('produk_data.0.id_produk') == $produk->id ? 'selected' : '' }}>
                                                                        {{ $produk->nama_produk }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('produk_data.0.id_produk')
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
                                                        <button type="button" class="btn btn-sm btn-primary mt-2" id="add-produk">+ Tambah Produk</button>
                                                        <!-- Remove Button -->
                                                        <div class="col-md-12 mt-3">
                                                            <button type="button" class="btn btn-sm btn-danger remove-produk" style="display: none;">Hapus Produk</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end align-items-center mt-3">
                                        <div>
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan Return Barang</button>
                                            <a href="{{ route('return-barang.index') }}" class="btn btn-secondary mb-1">Kembali</a>
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

    // Tampilkan/sembunyikan input manual saat halaman dimuat
    if (ekspedisiSelect.value === 'other') {
        manualInput.style.display = 'block';
    }

    // Tampilkan/sembunyikan input manual saat dropdown berubah
    ekspedisiSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            manualInput.style.display = 'block';
        } else {
            manualInput.style.display = 'none';
        }
    });

    // Add produk field
    let produkIndex = 1;
    document.getElementById('add-produk').addEventListener('click', function() {
        const container = document.getElementById('produk-container');
        const newRow = document.createElement('div');
        newRow.className = 'produk-row mb-4 p-3 border rounded';
        newRow.style.backgroundColor = '#f8f9fa';
        newRow.innerHTML = `
            <h6 class="text-secondary mb-3">Produk #${produkIndex + 1}</h6>
            <div class="row">
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

                <!-- Nama Produk -->
                <div class="col-md-6">
                    <label>Nama Produk <span class="text-danger">*</span></label>
                    <select class="choices form-select produk-select" name="produk_data[${produkIndex}][id_produk]" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Suhu Produk -->
                <div class="col-md-6">
                    <label>Suhu Produk</label>
                    <input type="text" class="form-control" name="produk_data[${produkIndex}][suhu_produk]" placeholder="Contoh: -18°C">
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
        produkIndex++;
        
        // Re-initialize Choices.js for new select
        const newSelect = newRow.querySelector('.produk-select');
        new Choices(newSelect, {
            searchEnabled: true,
            searchPlaceholderValue: 'Cari...',
            itemSelectText: 'Tekan untuk memilih',
            noResultsText: 'Tidak ada hasil ditemukan',
            noChoicesText: 'Tidak ada pilihan tersedia',
        });
    });
    
    // Remove produk field
    document.getElementById('produk-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-produk')) {
            const rows = document.querySelectorAll('.produk-row');
            if (rows.length > 1) {
                e.target.closest('.produk-row').remove();
            } else {
                alert('Minimal harus ada 1 produk!');
            }
        }
    });
});
</script>
@endsection