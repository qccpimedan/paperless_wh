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
                                                                @if($shift->user && $shift->user->plant)
                                                                    ({{ $shift->user->plant->plant }})
                                                                @endif
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
                                                    <label for="id_tujuan_pengiriman">Tujuan Pengiriman</label>
                                                    <select id="id_tujuan_pengiriman" class="choices form-select @error('id_tujuan_pengiriman') is-invalid @enderror" name="id_tujuan_pengiriman">
                                                        <option value="">-- Pilih Tujuan --</option>
                                                        @foreach($tujuanPengirimans as $tujuan)
                                                            <option value="{{ $tujuan->id }}" {{ old('id_tujuan_pengiriman') == $tujuan->id ? 'selected' : '' }}>
                                                                {{ $tujuan->nama_tujuan }}
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
                                                    <select id="id_supir" class="choices form-select @error('id_supir') is-invalid @enderror" name="id_supir">
                                                        <option value="">-- Pilih Supir --</option>
                                                        @foreach($supirs as $supir)
                                                            <option value="{{ $supir->id }}" {{ old('id_supir') == $supir->id ? 'selected' : '' }}>
                                                                {{ $supir->nama_supir }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_supir')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="no_po">No. PO</label>
                                                    <input type="text" id="no_po" class="form-control @error('no_po') is-invalid @enderror"
                                                        name="no_po" value="{{ old('no_po') }}" placeholder="Nomor PO">
                                                    @error('no_po')
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
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="segel_gembok" 
                                                            name="segel_gembok" value="1" {{ old('segel_gembok') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="segel_gembok">
                                                            Segel/Gembok
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
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

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_produk">Nama Produk</label>
                                                    <select id="id_produk" class="choices form-select @error('id_produk') is-invalid @enderror" name="id_produk">
                                                        <option value="">-- Pilih Produk --</option>
                                                        @foreach($produks as $produk)
                                                            <option value="{{ $produk->id }}" {{ old('id_produk') == $produk->id ? 'selected' : '' }}>
                                                                {{ $produk->nama_produk }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_produk')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kode_produksi">Kode Produksi</label>
                                                    <input type="text" id="kode_produksi" class="form-control @error('kode_produksi') is-invalid @enderror"
                                                        name="kode_produksi" value="{{ old('kode_produksi') }}" placeholder="Kode Produksi">
                                                    @error('kode_produksi')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="best_before">Best Before</label>
                                                    <input type="date" id="best_before" class="form-control @error('best_before') is-invalid @enderror"
                                                        name="best_before" value="{{ old('best_before') }}">
                                                    @error('best_before')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-check mt-4">
                                                        <input class="form-check-input" type="checkbox" id="kondisi_kemasan" 
                                                            name="kondisi_kemasan" value="1" {{ old('kondisi_kemasan', true) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="kondisi_kemasan">
                                                            Kondisi Kemasan Baik
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- JUMLAH -->
                                        <h5 class="text-primary mb-3 mt-4">Jumlah</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jumlah_kemasan">Jumlah Kemasan</label>
                                                    <input type="text" id="jumlah_kemasan" class="form-control @error('jumlah_kemasan') is-invalid @enderror"
                                                        name="jumlah_kemasan" value="{{ old('jumlah_kemasan') }}" placeholder="Contoh: 100 Karton">
                                                    @error('jumlah_kemasan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jumlah_sampling">Jumlah Sampling</label>
                                                    <input type="text" id="jumlah_sampling" class="form-control @error('jumlah_sampling') is-invalid @enderror"
                                                        name="jumlah_sampling" value="{{ old('jumlah_sampling') }}" placeholder="Contoh: 10 Karton">
                                                    @error('jumlah_sampling')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- KETERANGAN -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="keterangan">Keterangan</label>
                                                    <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                                        name="keterangan" rows="3" placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>
                                                    @error('keterangan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 d-flex justify-content-end mt-3">
                                            <a href="{{ route('pemeriksaan-loading-produk.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
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
});
</script>
@endsection