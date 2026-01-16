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
                    <h3>Edit Pemeriksaan Loading Produk</h3>
                    <p class="text-subtitle text-muted">Edit data pemeriksaan loading produk</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-loading-produk.index') }}">Pemeriksaan Loading Produk</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                            <h4 class="card-title">Form Edit Pemeriksaan Loading Produk</h4>
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

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-loading-produk.update', $pemeriksaanLoading->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <!-- INFORMASI DASAR -->
                                        <h5 class="text-primary mb-3">Informasi Dasar</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                    <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                        name="tanggal" value="{{ old('tanggal', $pemeriksaanLoading->tanggal->format('Y-m-d')) }}" required>
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
                                                            <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanLoading->id_shift) == $shift->id ? 'selected' : '' }}>
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
                                                    <label for="id_tujuan_pengiriman">Tujuan Pengiriman & Customer</label>
                                                    <select id="id_tujuan_pengiriman" class="choices form-select @error('id_tujuan_pengiriman') is-invalid @enderror" name="id_tujuan_pengiriman">
                                                        <option value="">-- Pilih Tujuan --</option>
                                                        @foreach($tujuanPengirimans as $tujuan)
                                                            <option value="{{ $tujuan->id }}" {{ old('id_tujuan_pengiriman', $pemeriksaanLoading->id_tujuan_pengiriman) == $tujuan->id ? 'selected' : '' }}>
                                                                @if($tujuan->customer)
                                                                    {{ $tujuan->customer->nama_cust }} - {{ $tujuan->nama_tujuan }}
                                                                @else
                                                                    {{ $tujuan->nama_tujuan }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_tujuan_pengiriman')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_kendaraan">Jenis & No Kendaraan</label>
                                                        <select id="id_kendaraan" class="choices form-select @error('id_kendaraan') is-invalid @enderror" name="id_kendaraan">
                                                            <option value="">-- Pilih Kendaraan --</option>
                                                            @foreach($kendaraans as $kendaraan)
                                                                <option value="{{ $kendaraan->id }}" {{ old('id_kendaraan', $pemeriksaanLoading->id_kendaraan) == $kendaraan->id ? 'selected' : '' }}>
                                                                    {{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->no_kendaraan }}
                                                                </option>
                                                            @endforeach
                                                            <!-- Tambahkan opsi ini di sini, setelah loop foreach -->
                                                            <option value="other" {{ old('id_kendaraan') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                        </select>
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
                                                    @error('id_kendaraan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_supir">Nama Supir</label>
                                                    <select id="id_supir" class="form-control @error('id_supir') is-invalid @enderror" name="id_supir">
                                                        <option value="">Pilih Supir</option>
                                                        @foreach($supirs as $supir)
                                                            <option value="{{ $supir->id }}" {{ old('id_supir', $pemeriksaanLoading->id_supir) == $supir->id ? 'selected' : '' }}>
                                                                {{ $supir->nama_supir }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_supir')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                                        name="star_loading" value="{{ old('star_loading', $pemeriksaanLoading->star_loading) }}">
                                                    @error('star_loading')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="selesai_loading">Selesai Loading</label>
                                                    <input type="time" id="selesai_loading" class="form-control @error('selesai_loading') is-invalid @enderror"
                                                        name="selesai_loading" value="{{ old('selesai_loading', $pemeriksaanLoading->selesai_loading) }}">
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
                                                        name="temperature_mobil" value="{{ old('temperature_mobil', $pemeriksaanLoading->temperature_mobil) }}" placeholder="Contoh: -18">
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
                                                        <option value="Frozen" {{ old('kondisi_produk', $pemeriksaanLoading->kondisi_produk) == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                        <option value="Fresh" {{ old('kondisi_produk', $pemeriksaanLoading->kondisi_produk) == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                        <option value="Dry" {{ old('kondisi_produk', $pemeriksaanLoading->kondisi_produk) == 'Dry' ? 'selected' : '' }}>Dry</option>
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
                                                    @if($pemeriksaanLoading->temperature_produk && count($pemeriksaanLoading->temperature_produk) > 0)
                                                        @foreach($pemeriksaanLoading->temperature_produk as $index => $temp)
                                                            <div class="row mb-2 temp-row">
                                                                <div class="col-md-10">
                                                                    <input type="text" class="form-control" name="temperature_produk[]" value="{{ $temp }}" placeholder="Contoh: -18">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($index == 0)
                                                                        <button type="button" class="btn btn-success w-100" id="add-temp">
                                                                            <i class="bi bi-plus"></i>
                                                                        </button>
                                                                    @else
                                                                        <button type="button" class="btn btn-danger w-100 remove-temp">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
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
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SEGEL & DATA PRODUK MULTIPLE -->
                                        <h5 class="text-primary mb-3 mt-4">Segel & Data Produk</h5>
                                        <div class="row">
                                            <div class="col-md-12">
                                                @php
                                                    $segelGembokValue = old('segel_gembok');
                                                    if ($segelGembokValue === null) {
                                                        $segelGembokValue = $pemeriksaanLoading->no_segel ? 'segel' : 'gembok';
                                                    }
                                                @endphp
                                                <div class="row">
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
                                                                name="no_segel" value="{{ old('no_segel', $pemeriksaanLoading->no_segel) }}" placeholder="Nomor Segel" style="max-width: 300px;">
                                                            @error('no_segel')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $produksCollection = collect($produks ?? []);

                                            $produkKategoriOptions = $produksCollection
                                                ->pluck('kategori_code')
                                                ->filter()
                                                ->unique()
                                                ->values()
                                                ->all();

                                            $produkByKategori = $produksCollection
                                                ->groupBy('kategori_code')
                                                ->map(function ($items) {
                                                    return $items->map(function ($p) {
                                                        return [
                                                            'id' => $p->id,
                                                            'nama' => $p->nama_produk,
                                                        ];
                                                    })->values();
                                                })
                                                ->all();

                                            $produkKategoriById = $produksCollection
                                                ->pluck('kategori_code', 'id')
                                                ->all();
                                        @endphp

                                        <!-- DATA PRODUK MULTIPLE -->
                                        <h5 class="text-primary mb-3 mt-4">Data Produk <span class="text-danger">*</span></h5>
                                        @php
                                            $selectedProdukId = old('id_produk');
                                            if ($selectedProdukId === null) {
                                                $selectedProdukId = $pemeriksaanLoading->produk_data[0]['id_produk'] ?? null;
                                            }

                                            $selectedKategori = old('kategori_code', '');
                                            if (($selectedKategori === null || $selectedKategori === '') && $selectedProdukId) {
                                                $selectedKategori = $produkKategoriById[$selectedProdukId] ?? '';
                                            }
                                        @endphp
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
                                                    <select class="form-select produk-select @error('id_produk') is-invalid @enderror" name="id_produk" data-selected="{{ $selectedProdukId }}" required>
                                                        <option value="">-- Pilih Produk --</option>
                                                    </select>
                                                    @error('id_produk')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="text-secondary mt-3">Detail Produk</h6>
                                        <div id="produk-container">
                                            @if($pemeriksaanLoading->produk_data && count($pemeriksaanLoading->produk_data) > 0)
                                                @foreach($pemeriksaanLoading->produk_data as $index => $produk)
                                                    <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                                        <h6 class="text-secondary mb-3">Detail #{{ $index + 1 }}</h6>
                                                        <input type="hidden" class="produk-id-hidden" name="produk_data[{{ $index }}][id_produk]" value="{{ $selectedProdukId }}">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label>Kode Produksi</label>
                                                                <input type="text" class="form-control" name="produk_data[{{ $index }}][kode_produksi]" value="{{ $produk['kode_produksi'] ?? '' }}" placeholder="Kode Produksi">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Best Before</label>
                                                                <input type="date" class="form-control" name="produk_data[{{ $index }}][best_before]" value="{{ $produk['best_before'] ?? '' }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Jumlah Kemasan</label>
                                                                <input type="text" class="form-control" name="produk_data[{{ $index }}][jumlah_kemasan]" value="{{ $produk['jumlah_kemasan'] ?? '' }}" placeholder="Contoh: 100 Karton">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Jumlah Sampling</label>
                                                                <input type="text" class="form-control" name="produk_data[{{ $index }}][jumlah_sampling]" value="{{ $produk['jumlah_sampling'] ?? '' }}" placeholder="Contoh: 10 Karton">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-3">
                                                                <label>Berat per Karung</label>
                                                                <input type="text" class="form-control" name="produk_data[{{ $index }}][berat_perkarung]" value="{{ $produk['berat_perkarung'] ?? '' }}" placeholder="Contoh: 25 Kg">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="produk_data[{{ $index }}][kondisi_kemasan]" value="1" {{ ($produk['kondisi_kemasan'] ?? true) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Kondisi Kemasan Baik</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <label>Keterangan</label>
                                                                <textarea class="form-control" name="produk_data[{{ $index }}][keterangan]" rows="2" placeholder="Keterangan tambahan untuk detail ini">{{ $produk['keterangan'] ?? '' }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <button type="button" class="btn btn-sm btn-danger remove-produk" style="display: {{ ($pemeriksaanLoading->produk_data && count($pemeriksaanLoading->produk_data) > 1) ? 'inline-block' : 'none' }};">Hapus Detail</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            @else
                                                <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                                    <h6 class="text-secondary mb-3">Detail #1</h6>
                                                    <input type="hidden" class="produk-id-hidden" name="produk_data[0][id_produk]" value="{{ $selectedProdukId }}">
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
                                                            <textarea class="form-control" name="produk_data[0][keterangan]" rows="2" placeholder="Keterangan tambahan untuk detail ini"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <button type="button" class="btn btn-sm btn-danger remove-produk" style="display: none;">Hapus Detail</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary mt-2" id="add-produk">+ Tambah Detail</button>

                                        <div class="col-md-12 d-flex justify-content-end mt-3">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Update Loading Produk</button>
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

    const produkByKategori = @json($produkByKategori ?? []);

    // Dependent dropdown: Kategori -> Produk
    const kategoriSelect = document.querySelector('select.kategori-produk-select[name="kategori_code"]');
    const produkSelect = document.querySelector('select.produk-select[name="id_produk"]');
    const produkChoicesInstances = new WeakMap();

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
            const fieldCount = document.querySelectorAll('#temperature-fields .temp-row').length;
            if (fieldCount > 1) {
                e.target.closest('.temp-row').remove();
            } else {
                alert('Minimal harus ada satu field temperature!');
            }
        }
    });
    
    // Segel/Gembok toggle no segel
    document.querySelectorAll('input[name="segel_gembok"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const container = document.getElementById('no_segel_container');
            if (!container) return;
            if (this.value === 'segel') {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
                const noSegel = document.getElementById('no_segel');
                if (noSegel) noSegel.value = '';
            }
        });
    });

    const rebuildProdukChoices = function(choiceItems, desiredValue) {
        if (!produkSelect) return;

        const existing = produkChoicesInstances.get(produkSelect);
        if (existing && typeof existing.destroy === 'function') {
            try { existing.destroy(); } catch (e) {}
            try { produkChoicesInstances.delete(produkSelect); } catch (e) {}
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

            produkChoicesInstances.set(produkSelect, instance);
        } catch (e) {
        }
    };

    const populateProdukOptions = function(kategoriCode) {
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

        rebuildProdukChoices(choiceItems, selectedFromAttr);
    };

    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', function() {
            if (produkSelect) {
                produkSelect.setAttribute('data-selected', '');
            }
            populateProdukOptions(kategoriSelect.value);
            syncHiddenProdukIds();
        });
    }

    function syncHiddenProdukIds() {
        const idProduk = document.querySelector('select[name="id_produk"]')?.value || '';
        document.querySelectorAll('#produk-container .produk-id-hidden').forEach((el) => {
            el.value = idProduk;
        });
    }

    // Init dependent dropdown on load (so edit page can preselect saved product)
    if (kategoriSelect) {
        populateProdukOptions(kategoriSelect.value);
        syncHiddenProdukIds();
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

    const addProdukBtn = document.getElementById('add-produk');
    if (addProdukBtn) {
        addProdukBtn.addEventListener('click', function() {
            const container = document.getElementById('produk-container');
            if (!container) return;
            const index = document.querySelectorAll('#produk-container .produk-row').length;
            const newRow = document.createElement('div');
            newRow.className = 'produk-row mb-4 p-3 border rounded';
            newRow.style.backgroundColor = '#f8f9fa';
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
                    <div class="col-md-3">
                        <label>Berat per Karung</label>
                        <input type="text" class="form-control" name="produk_data[${index}][berat_perkarung]" placeholder="Contoh: 25 Kg">
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
    }

    const produkContainer = document.getElementById('produk-container');
    if (produkContainer) {
        produkContainer.addEventListener('click', function(e) {
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
    }
});
</script>
@endsection