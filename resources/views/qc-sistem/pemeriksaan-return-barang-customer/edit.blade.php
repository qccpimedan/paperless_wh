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

                                <form class="form form-horizontal" action="{{ route('return-barang.update', $pemeriksaanReturnBarangCustomer->uuid) }}" method="POST">
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

                                            <!-- SECTION: DATA CUSTOMER & ALASAN -->
                                            <div class="col-md-12 mb-3 mt-3">
                                                <h5 class="text-primary"><strong>Data Customer & Alasan Return</strong></h5>
                                                <hr>
                                            </div>

                                            <!-- Customer -->
                                            <div class="col-md-6">
                                                <label for="id_customer">Customer <span class="text-danger">*</span></label>
                                                <select id="id_customer" class="form-select @error('id_customer') is-invalid @enderror"
                                                    name="id_customer" required>
                                                    <option value="">-- Pilih Customer --</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{ $customer->id }}" {{ old('id_customer', $pemeriksaanReturnBarangCustomer->id_customer) == $customer->id ? 'selected' : '' }}>
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
                                                    name="alasan_return" value="{{ old('alasan_return', $pemeriksaanReturnBarangCustomer->alasan_return) }}" required>
                                                @error('alasan_return')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- DATA PRODUK MULTIPLE -->
                                            <h5 class="text-primary mb-3 mt-4">Data Produk <span class="text-danger">*</span></h5>
                                            <div id="produk-container">
                                                @if($pemeriksaanReturnBarangCustomer->produk_data && count($pemeriksaanReturnBarangCustomer->produk_data) > 0)
                                                    @foreach($pemeriksaanReturnBarangCustomer->produk_data as $index => $produk)
                                                        <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                                            <h6 class="text-secondary mb-3">Produk #{{ $index + 1 }}</h6>
                                                            <div class="row">
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
                                                                    <label>Nama Produk <span class="text-danger">*</span></label>
                                                                    <select class="choices form-select produk-select" name="produk_data[{{ $index }}][id_produk]" required>
                                                                        <option value="">-- Pilih Produk --</option>
                                                                        @foreach($produks as $p)
                                                                            <option value="{{ $p->id }}" {{ $produk['id_produk'] == $p->id ? 'selected' : '' }}>{{ $p->nama_produk }}</option>
                                                                        @endforeach
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
                                                    <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                                        <h6 class="text-secondary mb-3">Produk #1</h6>
                                                        <div class="row">
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
                                                                <label>Nama Produk <span class="text-danger">*</span></label>
                                                                <select class="choices form-select produk-select" name="produk_data[0][id_produk]" required>
                                                                    <option value="">-- Pilih Produk --</option>
                                                                    @foreach($produks as $p)
                                                                        <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                                                                    @endforeach
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
});
</script>
@endsection