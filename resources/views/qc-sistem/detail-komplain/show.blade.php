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
                            @php
                                $idProdukArr = is_array($detailKomplain->id_produk_array ?? null) ? $detailKomplain->id_produk_array : [];
                                $kategoriArr = is_array($detailKomplain->kategori_code_array ?? null) ? $detailKomplain->kategori_code_array : [];
                                $namaProdukArr = is_array($detailKomplain->nama_produk_array ?? null) ? $detailKomplain->nama_produk_array : [];
                                $kodeProduksiArr = is_array($detailKomplain->kode_produksi_array ?? null) ? $detailKomplain->kode_produksi_array : [];
                                $expiredDateArr = is_array($detailKomplain->expired_date_array ?? null) ? $detailKomplain->expired_date_array : [];
                                $jumlahDatangArr = is_array($detailKomplain->jumlah_datang_array ?? null) ? $detailKomplain->jumlah_datang_array : [];
                                $jumlahDitolakArr = is_array($detailKomplain->jumlah_di_tolak_array ?? null) ? $detailKomplain->jumlah_di_tolak_array : [];
                                $dokumentasiArr = is_array($detailKomplain->dokumentasi_array ?? null) ? $detailKomplain->dokumentasi_array : [];
                                $keteranganArr = is_array($detailKomplain->keterangan_array ?? null) ? $detailKomplain->keterangan_array : [];
                                $dibuatArr = is_array($detailKomplain->di_buat_oleh_array ?? null) ? $detailKomplain->di_buat_oleh_array : [];
                                $setujuiArr = is_array($detailKomplain->setujui_oleh_array ?? null) ? $detailKomplain->setujui_oleh_array : [];

                                $rowCount = max(
                                    count($idProdukArr),
                                    count($kategoriArr),
                                    count($namaProdukArr),
                                    count($kodeProduksiArr),
                                    count($expiredDateArr),
                                    count($jumlahDatangArr),
                                    count($jumlahDitolakArr),
                                    count($dokumentasiArr),
                                    count($keteranganArr),
                                    count($dibuatArr),
                                    count($setujuiArr)
                                );

                                if ($rowCount < 1) {
                                    $rowCount = 1;
                                }
                            @endphp

                            @for($i = 0; $i < $rowCount; $i++)
                                @php
                                    $produkId = $idProdukArr[$i] ?? null;
                                    $namaProduk = null;
                                    if ($produkId !== null && isset($produkNamaById) && is_array($produkNamaById)) {
                                        $namaProduk = $produkNamaById[(string) $produkId] ?? null;
                                    }
                                    if ($namaProduk === null) {
                                        $namaProduk = $namaProdukArr[$i] ?? $detailKomplain->nama_produk;
                                    }
                                    $kodeProduksi = $kodeProduksiArr[$i] ?? $detailKomplain->kode_produksi;
                                    $expiredRaw = $expiredDateArr[$i] ?? ($detailKomplain->expired_date ? $detailKomplain->expired_date->format('Y-m-d') : null);
                                    $expiredDateText = '-';
                                    if ($expiredRaw) {
                                        try {
                                            $expiredDateText = \Carbon\Carbon::parse($expiredRaw)->format('d-m-Y');
                                        } catch (\Exception $e) {
                                            $expiredDateText = (string) $expiredRaw;
                                        }
                                    }
                                    $jumlahDatang = $jumlahDatangArr[$i] ?? $detailKomplain->jumlah_datang;
                                    $jumlahDitolak = $jumlahDitolakArr[$i] ?? $detailKomplain->jumlah_di_tolak;
                                    $dokumentasi = $dokumentasiArr[$i] ?? $detailKomplain->dokumentasi;
                                    $keterangan = $keteranganArr[$i] ?? $detailKomplain->keterangan;
                                    $dibuat = $dibuatArr[$i] ?? $detailKomplain->di_buat_oleh;
                                    $setujui = $setujuiArr[$i] ?? $detailKomplain->setujui_oleh;
                                    $kategori = $kategoriArr[$i] ?? null;
                                @endphp

                                <div class="border rounded p-3 mb-3" style="background: #fff;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold">Produk #{{ $i + 1 }}</span>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Kategori</label>
                                            <p class="text-muted">{{ $kategori !== null && $kategori !== '' ? $kategori : '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Nama Produk</label>
                                            <p class="text-muted">{{ $namaProduk ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Kode Produksi</label>
                                            <p class="text-muted">{{ $kodeProduksi ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Expired Date</label>
                                            <p class="text-muted">{{ $expiredDateText }}</p>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Jumlah Datang (Kg/Bal/Zak)</label>
                                            <p class="text-muted">{{ $jumlahDatang ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Jumlah Di Tolak (Kg/Bal/Zak)</label>
                                            <p class="text-muted">{{ $jumlahDitolak ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Dokumentasi Komplain</label>
                                            @if($dokumentasi)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/' . $dokumentasi) }}" alt="Dokumentasi Komplain" style="max-width: 260px; height: auto; border: 1px solid #ddd; padding: 4px; background: #fff;">
                                                </div>
                                                <div>
                                                    <a href="{{ asset('storage/' . $dokumentasi) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i> Lihat
                                                    </a>
                                                </div>
                                            @else
                                                <p class="text-muted">-</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Keterangan</label>
                                            <p class="text-muted">{{ $keterangan !== null && $keterangan !== '' ? $keterangan : '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Di Buat Oleh</label>
                                            <p class="text-muted">{{ $dibuat !== null && $dibuat !== '' ? $dibuat : '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Setujui Oleh</label>
                                            <p class="text-muted">{{ $setujui !== null && $setujui !== '' ? $setujui : '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- SECTION 3: DOKUMENTASI (UPLOAD SUPPLIER) -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Dokumentasi (Supplier)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Upload Supplier</label>
                                    @if($detailKomplain->upload_suplier)
                                        <div>
                                            <a href="{{ asset('storage/' . $detailKomplain->upload_suplier) }}" target="_blank" class="btn btn-sm btn-success">
                                                <i class="bi bi-eye"></i> Lihat
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
                                @php
                                    $dokArr = is_array($detailKomplain->dokumentasi_array ?? null) ? $detailKomplain->dokumentasi_array : [];
                                    $hasDok = !empty($detailKomplain->dokumentasi) || collect($dokArr)->filter(function ($v) {
                                        return $v !== null && $v !== '';
                                    })->isNotEmpty();
                                @endphp
                                @if($hasDok)
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
                <form action="{{ route('detail-komplain.upload-supplier', $detailKomplain->uuid) }}" method="POST" enctype="multipart/form-data">
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
