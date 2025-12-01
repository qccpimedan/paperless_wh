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
                    <h3>Detail Pemeriksaan Loading Produk</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap pemeriksaan loading produk</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-loading-produk.index') }}">Pemeriksaan Loading Produk</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Pemeriksaan Loading Produk</h4>
                    <div>
                        <a href="{{ route('pemeriksaan-loading-produk.edit', $pemeriksaanLoading->uuid) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('pemeriksaan-loading-produk.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- INFORMASI DASAR -->
                    <h5 class="text-primary mb-3">Informasi Dasar</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Tanggal:</strong></td>
                                    <td>{{ $pemeriksaanLoading->tanggal->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Shift:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->shift)
                                            <span class="badge bg-primary">{{ $pemeriksaanLoading->shift->shift }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tujuan Pengiriman:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->tujuanPengiriman)
                                            <span class="badge bg-info">{{ $pemeriksaanLoading->tujuanPengiriman->nama_tujuan }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis dan No. Kendaraan:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->kendaraan)
                                            {{ $pemeriksaanLoading->kendaraan->jenis_kendaraan }} - {{ $pemeriksaanLoading->kendaraan->no_kendaraan }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Supir:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->supir)
                                            {{ $pemeriksaanLoading->supir->nama_supir }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>No. PO:</strong></td>
                                    <td>{{ $pemeriksaanLoading->no_po ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Plant:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->user->plant)
                                            <span class="badge bg-success">{{ $pemeriksaanLoading->user->plant->plant }}</span>
                                        @else
                                            <span class="badge bg-secondary">No Plant</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat Oleh:</strong></td>
                                    <td>{{ $pemeriksaanLoading->user->name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- WAKTU LOADING -->
                    <h5 class="text-primary mb-3">Waktu Loading</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Mulai Loading:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->star_loading)
                                            <span class="badge bg-info">{{ $pemeriksaanLoading->star_loading }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Selesai Loading:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->selesai_loading)
                                            <span class="badge bg-success">{{ $pemeriksaanLoading->selesai_loading }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- TEMPERATURE -->
                    <h5 class="text-primary mb-3">Temperature</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Temperature Mobil:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->temperature_mobil)
                                            <span class="badge bg-primary">{{ $pemeriksaanLoading->temperature_mobil }}°C</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Kondisi Produk:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->kondisi_produk)
                                            @if($pemeriksaanLoading->kondisi_produk == 'Frozen')
                                                <span class="badge bg-info">{{ $pemeriksaanLoading->kondisi_produk }}</span>
                                            @elseif($pemeriksaanLoading->kondisi_produk == 'Fresh')
                                                <span class="badge bg-success">{{ $pemeriksaanLoading->kondisi_produk }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $pemeriksaanLoading->kondisi_produk }}</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Temperature Produk:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->temperature_produk && count($pemeriksaanLoading->temperature_produk) > 0)
                                            @foreach($pemeriksaanLoading->temperature_produk as $index => $temp)
                                                <span class="badge bg-warning text-dark me-1 mb-1">
                                                    #{{ $index + 1 }}: {{ $temp }}°C
                                                </span>
                                            @endforeach
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- SEGEL & INFORMASI PRODUK -->
                    <h5 class="text-primary mb-3">Segel & Informasi Produk</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Segel/Gembok:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->segel_gembok)
                                            <span class="badge bg-success">✓ Ya</span>
                                        @else
                                            <span class="badge bg-secondary">✗ Tidak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>No. Segel:</strong></td>
                                    <td>{{ $pemeriksaanLoading->no_segel ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Produk:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->produk)
                                            <strong>{{ $pemeriksaanLoading->produk->nama_produk }}</strong>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Kode Produksi:</strong></td>
                                    <td>{{ $pemeriksaanLoading->kode_produksi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Best Before:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->best_before)
                                            {{ $pemeriksaanLoading->best_before->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Kondisi Kemasan:</strong></td>
                                    <td>
                                        @if($pemeriksaanLoading->kondisi_kemasan)
                                            <span class="badge bg-success">✓ Baik</span>
                                        @else
                                            <span class="badge bg-danger">✗ Tidak Baik</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- JUMLAH -->
                    <h5 class="text-primary mb-3">Jumlah</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Jumlah Kemasan:</strong></td>
                                    <td>{{ $pemeriksaanLoading->jumlah_kemasan ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Jumlah Sampling:</strong></td>
                                    <td>{{ $pemeriksaanLoading->jumlah_sampling ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- KETERANGAN -->
                    <h5 class="text-primary mb-3">Keterangan</h5>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-light">
                                {{ $pemeriksaanLoading->keterangan ?? 'Tidak ada keterangan' }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- INFORMASI SISTEM -->
                    <h5 class="text-primary mb-3">Informasi Sistem</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Dibuat Pada:</strong></td>
                                    <td>{{ $pemeriksaanLoading->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Terakhir Diupdate:</strong></td>
                                    <td>{{ $pemeriksaanLoading->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection