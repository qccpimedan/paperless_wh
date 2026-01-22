@extends('layouts.app')
@section('container')
<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h3>Detail Pemeriksaan Return Barang</h3>
                </div>
                <div class="col-12 col-md-6">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('return-barang.index') }}">Pemeriksaan Return Barang</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Detail Pemeriksaan</h4>
                    <div>
                        <a href="{{ route('return-barang.edit', $pemeriksaanReturnBarangCustomer->uuid) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('return-barang.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informasi Dasar -->
                    <h5 class="text-primary">Informasi Dasar</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td width="40%"><strong>Tanggal:</strong></td><td>{{ \Carbon\Carbon::parse($pemeriksaanReturnBarangCustomer->tanggal)->format('d/m/Y') }}</td></tr>
                                <tr><td><strong>Shift:</strong></td><td>
                                    @if($pemeriksaanReturnBarangCustomer->shift)
                                        <span class="badge bg-primary">{{ $pemeriksaanReturnBarangCustomer->shift->shift }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                                <tr><td><strong>Ekspedisi:</strong></td><td>{{ $pemeriksaanReturnBarangCustomer->ekspedisi->nama_ekspedisi ?? '-' }}</td></tr>
                                <tr><td><strong>No. Polisi:</strong></td><td>{{ $pemeriksaanReturnBarangCustomer->no_polisi }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td width="40%"><strong>Nama Supir:</strong></td><td>{{ $pemeriksaanReturnBarangCustomer->nama_supir }}</td></tr>
                                <tr><td><strong>Waktu Kedatangan:</strong></td><td>{{ $pemeriksaanReturnBarangCustomer->waktu_kedatangan }}</td></tr>
                                <tr><td><strong>Suhu Mobil:</strong></td><td>{{ $pemeriksaanReturnBarangCustomer->suhu_mobil }}</td></tr>
                            </table>
                        </div>
                    </div>

                    <!-- Data Produk -->
                    <h5 class="text-primary">Data Produk</h5>
                    @if($pemeriksaanReturnBarangCustomer->produk_data && count($pemeriksaanReturnBarangCustomer->produk_data) > 0)
                        @foreach($pemeriksaanReturnBarangCustomer->produk_data as $index => $produk)
                            <div class="card mb-3" style="border-left: 4px solid #435ebe;">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Produk #{{ $index + 1 }}</h6>
                                        <span class="badge bg-info">{{ $produk['kondisi_produk'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Informasi Produk -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless table-sm">
                                                <tr>
                                                    <td width="40%"><strong>Customer:</strong></td>
                                                    <td>
                                                        @php
                                                            $custModel = \App\Models\Customer::find($produk['id_customer'] ?? null);
                                                        @endphp
                                                        <span class="badge bg-info">{{ $custModel ? $custModel->nama_cust : '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Alasan Return:</strong></td>
                                                    <td>{{ $produk['alasan_return'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="40%"><strong>Nama Produk:</strong></td>
                                                    <td>
                                                        @php
                                                            $produkModel = \App\Models\Produk::find($produk['id_produk'] ?? null);
                                                        @endphp
                                                        <span class="badge bg-info">{{ $produkModel ? $produkModel->nama_produk : 'Unknown' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Kondisi Produk:</strong></td>
                                                    <td><span class="badge bg-info">{{ $produk['kondisi_produk'] ?? '-' }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Suhu Produk:</strong></td>
                                                    <td>{{ $produk['suhu_produk'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Kode Produksi:</strong></td>
                                                    <td>{{ $produk['kode_produksi'] ?? '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless table-sm">
                                                <tr>
                                                    <td width="40%"><strong>Expired Date:</strong></td>
                                                    <td>{{ isset($produk['expired_date']) ? \Carbon\Carbon::parse($produk['expired_date'])->format('d/m/Y') : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jumlah Barang:</strong></td>
                                                    <td>{{ $produk['jumlah_barang'] ?? '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Kondisi & Inspeksi -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="text-primary small mb-2">Kondisi & Inspeksi</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong class="small d-block mb-2">Kondisi Kemasan:</strong>
                                                    <div class="d-flex align-items-center small mb-1">
                                                        @if($produk['kondisi_kemasan'] ?? false)
                                                            <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                        @else
                                                            <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                        @endif
                                                        <span>{{ ($produk['kondisi_kemasan'] ?? false) ? 'Ok' : 'Tidak Ok' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong class="small d-block mb-2">Kondisi Produk:</strong>
                                                    <div class="d-flex align-items-center small mb-1">
                                                        @if($produk['kondisi_produk_check'] ?? false)
                                                            <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                        @else
                                                            <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                        @endif
                                                        <span>{{ ($produk['kondisi_produk_check'] ?? false) ? 'Ok' : 'Tidak Ok' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rekomendasi -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="text-primary small mb-2">Rekomendasi</h6>
                                            <div class="p-2 bg-light rounded">
                                                <span class="badge bg-warning">{{ $produk['rekomendasi'] ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Keterangan -->
                                    @if(isset($produk['keterangan']) && $produk['keterangan'])
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <strong>Keterangan:</strong>
                                                <p class="mt-1 p-2 bg-light rounded small">{{ $produk['keterangan'] }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">Tidak ada data produk</div>
                    @endif

                </div>
            </div>
        </section>
    </div>
</div>
@endsection