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
                    <h3>Detail Komplain</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap komplain produk</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('detail-komplain.index') }}">Detail Komplain</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- SECTION 1: INFORMASI SUPPLIER & PENGIRIMAN -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Informasi Supplier & Pengiriman</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Supplier</label>
                                    <p class="text-muted">{{ $detailKomplain->nama_supplier }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tanggal Kedatangan</label>
                                    <p class="text-muted">{{ $detailKomplain->tanggal_kedatangan->format('d-m-Y') }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Shift</label>
                                    <p class="text-muted">{{ $detailKomplain->shift->shift ?? 'Shift tidak ditemukan' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Plant</label>
                                    <p class="text-muted">
                                        @if($detailKomplain->user->plant)
                                            {{ $detailKomplain->user->plant->plant }}
                                        @else
                                            No Plant
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">No. PO</label>
                                    <p class="text-muted">{{ $detailKomplain->no_po }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: INFORMASI PRODUK -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Informasi Produk</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Produk</label>
                                    <p class="text-muted">{{ $detailKomplain->nama_produk }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kode Produksi</label>
                                    <p class="text-muted">{{ $detailKomplain->kode_produksi }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Expired Date</label>
                                    <p class="text-muted">{{ $detailKomplain->expired_date->format('d-m-Y') }}</p>
                                </div>
                                <!-- <div class="col-md-6">
                                    <label class="form-label fw-bold">Dibuat Pada</label>
                                    <p class="text-muted">{{ $detailKomplain->created_at->format('d M Y H:i:s')  }}</p>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: JUMLAH BARANG -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Jumlah Barang</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Jumlah Datang (Kg/Bal/Zak)</label>
                                    <p class="text-muted">{{ $detailKomplain->jumlah_datang }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Jumlah Di Tolak (Kg/Bal/Zak)</label>
                                    <p class="text-muted">{{ $detailKomplain->jumlah_di_tolak }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: DOKUMENTASI -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Dokumentasi</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Dokumentasi Komplain</label>
                                    @if($detailKomplain->dokumentasi)
                                        <div>
                                            <a href="{{ asset('storage/' . $detailKomplain->dokumentasi) }}" 
                                               target="_blank" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-muted">-</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Upload Supplier</label>
                                    @if($detailKomplain->upload_suplier)
                                        <div>
                                            <a href="{{ asset('storage/' . $detailKomplain->upload_suplier) }}" 
                                               target="_blank" class="btn btn-sm btn-success">
                                                <i class="bi bi-eye"></i> LIhat
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-muted">-</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sidebar Actions -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Aksi</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <!-- <a href="{{ route('detail-komplain.edit', $detailKomplain->uuid) }}" 
                                   class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit Komplain
                                </a> -->
                                <!-- @if(!$detailKomplain->upload_suplier)
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                                            data-bs-target="#uploadModal">
                                        <i class="bi bi-upload"></i> Upload Supplier
                                    </button>
                                @endif -->
                                <a href="{{ route('detail-komplain.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <!-- <form action="{{ route('detail-komplain.destroy', $detailKomplain->uuid) }}" 
                                      method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100" 
                                            onclick="return confirm('Yakin ingin menghapus komplain ini?')">
                                        <i class="bi bi-trash"></i> Hapus Komplain
                                    </button>
                                </form> -->
                            </div>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dokumentasi</label>
                                @if($detailKomplain->dokumentasi)
                                    <span class="badge bg-success">✓ Ada</span>
                                @else
                                    <span class="badge bg-warning">✗ Belum Diupload</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Upload Supplier</label>
                                @if($detailKomplain->upload_suplier)
                                    <span class="badge bg-success">✓ Ada</span>
                                @else
                                    <span class="badge bg-warning">✗ Belum Diupload</span>
                                @endif
                            </div>
                            <div>
                                <label class="form-label fw-bold">Approval</label>
                                <div>
                                    @if($detailKomplain->di_buat_oleh)
                                        <span class="badge bg-info">Di Buat: {{ $detailKomplain->di_buat_oleh }}</span>
                                    @endif
                                    @if($detailKomplain->setujui_oleh)
                                        <span class="badge bg-success">Disetujui: {{ $detailKomplain->setujui_oleh }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal Upload Supplier -->
@if(!$detailKomplain->upload_suplier)
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadLabel">
                        Upload File Supplier
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('detail-komplain.upload-suplier', $detailKomplain->uuid) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="upload_suplier" class="form-label">
                                File Supplier <span class="text-danger">*</span>
                            </label>
                            <input type="file" class="form-control" 
                                   id="upload_suplier" 
                                   name="upload_suplier" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                            <small class="text-muted">Format: PDF, Word (.doc, .docx), Excel (.xls, .xlsx) | Max 5MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@endsection
