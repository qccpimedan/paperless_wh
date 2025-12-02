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
                    <h3>Detail Pemeriksaan Return Barang</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('return-barang.index') }}">Pemeriksaan Return Barang</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title">Detail Pemeriksaan</h5>
                    <div>
                        <a href="{{ route('return-barang.edit', $pemeriksaanReturnBarangCustomer->uuid) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('return-barang.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pemeriksaanReturnBarangCustomer->tanggal)->format('d/m/Y') }}</p>
                            <p><strong>Shift:</strong> {{ $pemeriksaanReturnBarangCustomer->shift->shift ?? '-' }}</p>
                            <p><strong>Ekspedisi:</strong> {{ $pemeriksaanReturnBarangCustomer->ekspedisi->nama_ekspedisi ?? '-' }}</p>
                            <p><strong>No. Polisi:</strong> {{ $pemeriksaanReturnBarangCustomer->no_polisi }}</p>
                            <p><strong>Nama Supir:</strong> {{ $pemeriksaanReturnBarangCustomer->nama_supir }}</p>
                            <p><strong>Waktu Kedatangan:</strong> {{ $pemeriksaanReturnBarangCustomer->waktu_kedatangan }}</p>
                            <p><strong>Suhu Mobil:</strong> {{ $pemeriksaanReturnBarangCustomer->suhu_mobil }}</p>
                        </div>

                        <div class="col-md-12">
                            <p><strong>Customer:</strong> {{ $pemeriksaanReturnBarangCustomer->customer->nama_cust ?? '-' }}</p>
                            <p><strong>Alasan Return:</strong> {{ $pemeriksaanReturnBarangCustomer->alasan_return }}</p>
                        </div>

                        <!-- DATA PRODUK MULTIPLE -->
                        <div class="col-md-12 mt-4">
                            <h5 class="text-primary mb-3">Data Produk</h5>
                            @if($pemeriksaanReturnBarangCustomer->produk_data && count($pemeriksaanReturnBarangCustomer->produk_data) > 0)
                                @foreach($pemeriksaanReturnBarangCustomer->produk_data as $index => $produk)
                                    <div class="card mb-3" style="background-color: #f8f9fa;">
                                        <div class="card-body">
                                            <h6 class="text-secondary mb-3">Produk #{{ $index + 1 }}</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @php
                                                        $produkModel = \App\Models\Produk::find($produk['id_produk'] ?? null);
                                                    @endphp
                                                    <p><strong>Nama Produk:</strong> {{ $produkModel ? $produkModel->nama_produk : 'Unknown' }}</p>
                                                    <p><strong>Kondisi Produk:</strong> <span class="badge bg-info">{{ $produk['kondisi_produk'] ?? '-' }}</span></p>
                                                    <p><strong>Suhu Produk:</strong> {{ $produk['suhu_produk'] ?? '-' }}</p>
                                                    <p><strong>Kode Produksi:</strong> {{ $produk['kode_produksi'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Expired Date:</strong> {{ isset($produk['expired_date']) ? \Carbon\Carbon::parse($produk['expired_date'])->format('d/m/Y') : '-' }}</p>
                                                    <p><strong>Jumlah Barang:</strong> {{ $produk['jumlah_barang'] ?? '-' }}</p>
                                                    <p><strong>Kondisi Kemasan:</strong> <span class="badge {{ ($produk['kondisi_kemasan'] ?? false) ? 'bg-success' : 'bg-danger' }}">{{ ($produk['kondisi_kemasan'] ?? false) ? '✓ Baik' : '✗ Rusak' }}</span></p>
                                                    <p><strong>Kondisi Produk Check:</strong> <span class="badge {{ ($produk['kondisi_produk_check'] ?? false) ? 'bg-success' : 'bg-danger' }}">{{ ($produk['kondisi_produk_check'] ?? false) ? '✓ Baik' : '✗ Rusak' }}</span></p>
                                                </div>
                                                <div class="col-md-12 mt-2">
                                                    <p><strong>Rekomendasi:</strong> <span class="badge bg-warning">{{ $produk['rekomendasi'] ?? '-' }}</span></p>
                                                </div>
                                                @if(isset($produk['keterangan']) && $produk['keterangan'])
                                                    <div class="col-md-12 mt-2">
                                                        <p><strong>Keterangan:</strong></p>
                                                        <p class="text-muted">{{ $produk['keterangan'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Tidak ada data produk</p>
                            @endif
                        </div>

                        <div class="col-md-12 mt-4">
                            <h5 class="text-primary mb-3">Informasi Lainnya</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Plant:</strong> {{ $pemeriksaanReturnBarangCustomer->user->plant->plant ?? '-' }}</p>
                                    <p><strong>User:</strong> {{ $pemeriksaanReturnBarangCustomer->user->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Dibuat:</strong> {{ \Carbon\Carbon::parse($pemeriksaanReturnBarangCustomer->created_at)->format('d/m/Y H:i') }}</p>
                                    <p><strong>Diupdate:</strong> {{ \Carbon\Carbon::parse($pemeriksaanReturnBarangCustomer->updated_at)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection