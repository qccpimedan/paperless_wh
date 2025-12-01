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

                                            <!-- SECTION: INFORMASI PRODUK -->
                                            <div class="col-md-12 mb-3 mt-3">
                                                <h5 class="text-primary"><strong>Informasi Produk</strong></h5>
                                                <hr>
                                            </div>

                                            <!-- Kondisi Produk -->
                                            <div class="col-md-6">
                                                <label for="kondisi_produk">Kondisi Produk <span class="text-danger">*</span></label>
                                                <select id="kondisi_produk" class="form-select @error('kondisi_produk') is-invalid @enderror"
                                                    name="kondisi_produk" required>
                                                    <option value="">-- Pilih Kondisi --</option>
                                                    <option value="Frozen" {{ old('kondisi_produk', $pemeriksaanReturnBarangCustomer->kondisi_produk) == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                    <option value="Fresh" {{ old('kondisi_produk', $pemeriksaanReturnBarangCustomer->kondisi_produk) == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                    <option value="Dry" {{ old('kondisi_produk', $pemeriksaanReturnBarangCustomer->kondisi_produk) == 'Dry' ? 'selected' : '' }}>Dry</option>
                                                </select>
                                                @error('kondisi_produk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Produk -->
                                            <div class="col-md-6">
                                                <label for="id_produk">Nama Produk <span class="text-danger">*</span></label>
                                                <select id="id_produk" class="form-select @error('id_produk') is-invalid @enderror"
                                                    name="id_produk" required>
                                                    <option value="">-- Pilih Produk --</option>
                                                    @foreach($produks as $produk)
                                                        <option value="{{ $produk->id }}" {{ old('id_produk', $pemeriksaanReturnBarangCustomer->id_produk) == $produk->id ? 'selected' : '' }}>
                                                            {{ $produk->nama_produk }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_produk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Suhu Produk -->
                                            <div class="col-md-6">
                                                <label for="suhu_produk">Suhu Produk</label>
                                                <input type="text" id="suhu_produk" class="form-control @error('suhu_produk') is-invalid @enderror"
                                                    name="suhu_produk" placeholder="Contoh: -18°C" value="{{ old('suhu_produk', $pemeriksaanReturnBarangCustomer->suhu_produk) }}">
                                                @error('suhu_produk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Kode Produksi -->
                                            <div class="col-md-6">
                                                <label for="kode_produksi">Kode Produksi <span class="text-danger">*</span></label>
                                                <input type="text" id="kode_produksi" class="form-control @error('kode_produksi') is-invalid @enderror"
                                                    name="kode_produksi" value="{{ old('kode_produksi', $pemeriksaanReturnBarangCustomer->kode_produksi) }}" required>
                                                @error('kode_produksi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Expired Date -->
                                            <div class="col-md-6">
                                                <label for="expired_date">Expired Date <span class="text-danger">*</span></label>
                                                <input type="date" id="expired_date" class="form-control @error('expired_date') is-invalid @enderror"
                                                    name="expired_date" value="{{ old('expired_date', \Carbon\Carbon::parse($pemeriksaanReturnBarangCustomer->expired_date)->format('Y-m-d')) }}" required>
                                                @error('expired_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Jumlah Barang -->
                                            <div class="col-md-6">
                                                <label for="jumlah_barang">Jumlah (Karung/Box/Pack) <span class="text-danger">*</span></label>
                                                <input type="text" id="jumlah_barang" class="form-control @error('jumlah_barang') is-invalid @enderror"
                                                    name="jumlah_barang" placeholder="Contoh: 10 Karung" value="{{ old('jumlah_barang', $pemeriksaanReturnBarangCustomer->jumlah_barang) }}" required>
                                                @error('jumlah_barang')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- SECTION: KONDISI & INSPEKSI -->
                                            <div class="col-md-12 mb-3 mt-3">
                                                <h5 class="text-primary"><strong>Kondisi & Inspeksi</strong></h5>
                                                <hr>
                                            </div>

                                            <!-- Kondisi Kemasan -->
                                            <div class="col-md-6">
                                                <label><strong>Kondisi Kemasan <span class="text-danger">*</span></strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_kemasan" id="kemasan_ya" value="1" {{ old('kondisi_kemasan', $pemeriksaanReturnBarangCustomer->kondisi_kemasan) == 1 ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="kemasan_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_kemasan" id="kemasan_tidak" value="0" {{ old('kondisi_kemasan', $pemeriksaanReturnBarangCustomer->kondisi_kemasan) == 0 ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="kemasan_tidak">Tidak ✗</label>
                                                </div>
                                                @error('kondisi_kemasan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Kondisi Produk Check -->
                                            <div class="col-md-6">
                                                <label><strong>Kondisi Produk <span class="text-danger">*</span></strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_produk_check" id="produk_ya" value="1" {{ old('kondisi_produk_check', $pemeriksaanReturnBarangCustomer->kondisi_produk_check) == 1 ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="produk_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_produk_check" id="produk_tidak" value="0" {{ old('kondisi_produk_check', $pemeriksaanReturnBarangCustomer->kondisi_produk_check) == 0 ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="produk_tidak">Tidak ✗</label>
                                                </div>
                                                @error('kondisi_produk_check')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Rekomendasi -->
                                            <div class="col-md-12">
                                                <label for="rekomendasi">Rekomendasi <span class="text-danger">*</span></label>
                                                <input type="text" id="rekomendasi" class="form-control @error('rekomendasi') is-invalid @enderror"
                                                    name="rekomendasi" value="{{ old('rekomendasi', $pemeriksaanReturnBarangCustomer->rekomendasi) }}" required>
                                                @error('rekomendasi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Keterangan -->
                                            <div class="col-md-12">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                                    name="keterangan" rows="3" placeholder="Masukkan keterangan tambahan">{{ old('keterangan', $pemeriksaanReturnBarangCustomer->keterangan) }}</textarea>
                                                @error('keterangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Buttons -->
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                                <a href="{{ route('return-barang.index') }}" class="btn btn-secondary mb-1">Batal</a>
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