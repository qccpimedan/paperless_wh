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
                    <h3>Detail Pemeriksaan Barang Mudah Pecah</h3>
                    <p class="text-subtitle text-muted">Lihat detail pemeriksaan</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-barang-mudah-pecah.index') }}">Pemeriksaan Barang Mudah Pecah</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
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
                            <h4 class="card-title">Informasi Dasar</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Tanggal</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        <p>{{ \Carbon\Carbon::parse($pemeriksaanBarangMudahPecah->tanggal)->format('d M Y') }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Shift</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="badge bg-info">{{ $pemeriksaanBarangMudahPecah->shift->shift }}</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Area</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        <p>{{ $pemeriksaanBarangMudahPecah->area->nama_area }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Dibuat Oleh</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        <p>{{ $pemeriksaanBarangMudahPecah->user->name }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Dibuat Pada</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        <p>{{ $pemeriksaanBarangMudahPecah->created_at->format('d M Y H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row match-height mt-3">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Detail Barang ({{ count($pemeriksaanBarangMudahPecah->details) }} barang)</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @forelse($pemeriksaanBarangMudahPecah->details as $index => $detail)
                                    <div class="card mb-3 p-3" style="border-left: 4px solid #0d6efd;">
                                        <!-- Judul Barang -->
                                        @if($detail->nama_barang_manual)
                                            <h6 class="mb-3"><strong>Barang #{{ $index + 1 }}: {{ $detail->nama_barang_manual }} <span class="badge bg-warning">Manual</span></strong></h6>
                                        @else
                                            <h6 class="mb-3"><strong>Barang #{{ $index + 1 }}: {{ $detail->barang->nama_barang }}</strong></h6>
                                        @endif
                                        
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <label class="form-label"><strong>Jumlah Barang</strong></label>
                                                <p>{{ $detail->jumlah_barang }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label"><strong>Lokasi Area</strong></label>
                                                <p>{{ $detail->areaLocation->lokasi_area ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label"><strong>Awal</strong></label>
                                                <p>
                                                    @if($detail->awal === 'baik')
                                                        <span class="badge bg-success">✓ baik</span>
                                                    @else
                                                        <span class="badge bg-danger">✗ tidak baik</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label"><strong>Akhir</strong></label>
                                                <p>
                                                    @if($detail->akhir === 'baik')
                                                        <span class="badge bg-success">✓ baik</span>
                                                    @else
                                                        <span class="badge bg-danger">✗ tidak baik</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Nama Karyawan (jika ada) -->
                                        @if($detail->nama_karyawan)
                                            <div class="row mb-2">
                                                <div class="col-md-6">
                                                    <label class="form-label"><strong>Nama Karyawan</strong></label>
                                                    <p>{{ $detail->nama_karyawan }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Temuan Ketidaksesuaian</strong></label>
                                                <p>{{ $detail->temuan_ketidaksesuaian ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Tindakan Koreksi</strong></label>
                                                <p>{{ $detail->tindakan_koreksi ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Tidak ada data barang</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <a href="{{ route('pemeriksaan-barang-mudah-pecah.edit', $pemeriksaanBarangMudahPecah->uuid) }}" class="btn btn-warning me-1">Edit</a>
                    <a href="{{ route('pemeriksaan-barang-mudah-pecah.index') }}" class="btn btn-light-secondary">Kembali</a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection