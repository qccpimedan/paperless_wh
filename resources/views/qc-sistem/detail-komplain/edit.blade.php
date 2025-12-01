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
                    <h3>Edit Detail Komplain</h3>
                    <p class="text-subtitle text-muted">Form edit detail komplain produk</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('detail-komplain.index') }}">Detail Komplain</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Form Edit Detail Komplain</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('detail-komplain.update', $detailKomplain->uuid) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- SECTION 1: INFORMASI SUPPLIER & PENGIRIMAN -->
                        <h6 class="mb-3 mt-4"><strong>Informasi Supplier & Pengiriman</strong></h6>
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_supplier" class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" 
                                           id="nama_supplier" name="nama_supplier" value="{{ old('nama_supplier', $detailKomplain->nama_supplier) }}" required>
                                    @error('nama_supplier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_kedatangan" class="form-label">Tanggal Kedatangan <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_kedatangan') is-invalid @enderror" 
                                           id="tanggal_kedatangan" name="tanggal_kedatangan" value="{{ old('tanggal_kedatangan', $detailKomplain->tanggal_kedatangan->format('Y-m-d')) }}" required>
                                    @error('tanggal_kedatangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_po" class="form-label">No. PO <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_po') is-invalid @enderror" 
                                           id="no_po" name="no_po" value="{{ old('no_po', $detailKomplain->no_po) }}" required>
                                    @error('no_po')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: INFORMASI PRODUK -->
                        <h6 class="mb-3 mt-4"><strong>Informasi Produk</strong></h6>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                    <select class="form-select choices @error('nama_produk') is-invalid @enderror" 
                                            id="nama_produk" name="nama_produk" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($produks as $produk)
                                            <option value="{{ $produk->nama_produk }}" 
                                                    {{ old('nama_produk', $detailKomplain->nama_produk) == $produk->nama_produk ? 'selected' : '' }}>
                                                {{ $produk->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('nama_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode_produksi" class="form-label">Kode Produksi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode_produksi') is-invalid @enderror" 
                                           id="kode_produksi" name="kode_produksi" value="{{ old('kode_produksi', $detailKomplain->kode_produksi) }}" required>
                                    @error('kode_produksi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expired_date" class="form-label">Expired Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('expired_date') is-invalid @enderror" 
                                           id="expired_date" name="expired_date" value="{{ old('expired_date', $detailKomplain->expired_date->format('Y-m-d')) }}" required>
                                    @error('expired_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: JUMLAH BARANG -->
                        <h6 class="mb-3 mt-4"><strong>Jumlah Barang</strong></h6>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah_datang" class="form-label">Jumlah Datang (Kg/Bal/Zak) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('jumlah_datang') is-invalid @enderror" 
                                           id="jumlah_datang" name="jumlah_datang" value="{{ old('jumlah_datang', $detailKomplain->jumlah_datang) }}" required>
                                    @error('jumlah_datang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah_di_tolak" class="form-label">Jumlah Di Tolak (Kg/Bal/Zak) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('jumlah_di_tolak') is-invalid @enderror" 
                                           id="jumlah_di_tolak" name="jumlah_di_tolak" value="{{ old('jumlah_di_tolak', $detailKomplain->jumlah_di_tolak) }}" required>
                                    @error('jumlah_di_tolak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: DOKUMENTASI -->
                        <h6 class="mb-3 mt-4"><strong>Dokumentasi</strong></h6>
                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="dokumentasi" class="form-label">Dokumentasi Komplain <span class="text-muted">(PDF/JPG/PNG, Max 5MB)</span></label>
                                    @if($detailKomplain->dokumentasi)
                                        <div class="alert alert-info mb-2">
                                            <strong>File saat ini:</strong> 
                                            <a href="{{ asset('storage/' . $detailKomplain->dokumentasi) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('dokumentasi') is-invalid @enderror" 
                                           id="dokumentasi" name="dokumentasi" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah file</small>
                                    @error('dokumentasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 5: CATATAN & APPROVAL -->
                        <h6 class="mb-3 mt-4"><strong>Catatan & Approval</strong></h6>
                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                              id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $detailKomplain->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="di_buat_oleh" class="form-label">Di Buat Oleh</label>
                                    <input type="text" class="form-control @error('di_buat_oleh') is-invalid @enderror" 
                                           id="di_buat_oleh" name="di_buat_oleh" placeholder="Nama/Inisial" value="{{ old('di_buat_oleh', $detailKomplain->di_buat_oleh) }}">
                                    @error('di_buat_oleh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="setujui_oleh" class="form-label">Setujui Oleh</label>
                                    <input type="text" class="form-control @error('setujui_oleh') is-invalid @enderror" 
                                           id="setujui_oleh" name="setujui_oleh" placeholder="Nama/Inisial" value="{{ old('setujui_oleh', $detailKomplain->setujui_oleh) }}">
                                    @error('setujui_oleh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <a href="{{ route('detail-komplain.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Update Komplain</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection
