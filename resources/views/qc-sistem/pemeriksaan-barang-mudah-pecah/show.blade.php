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
                    <div class="card border shadow-sm">
                        <div class="card-header bg-light py-3 border-bottom">
                            <h4 class="card-title mb-0 text-primary fw-bold">
                                <i class="bi bi-list-check me-2"></i>Detail Pemeriksaan ({{ count($pemeriksaanBarangMudahPecah->details) }} item terdaftar)
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-primary text-center">
                                            <tr>
                                                <th style="width: 5%">#</th>
                                                <th style="width: 20%">Nama Barang</th>
                                                <th style="width: 20%">Sub Area</th>
                                                <th style="width: 5%">Jumlah</th>
                                                <th style="width: 15%">Verifikasi Pra-Op</th>
                                                <th style="width: 15%">Verifikasi Post-Op</th>
                                                <th style="width: 25%">Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($pemeriksaanBarangMudahPecah->details as $index => $detail)
                                                @php
                                                    $isManual = !empty($detail->nama_barang_manual);
                                                @endphp
                                                <tr>
                                                    <td class="text-center text-muted fw-semibold font-monospace">
                                                        @if($isManual)
                                                            <i class="bi bi-pencil-square text-warning" title="Baris Kustom"></i>
                                                        @else
                                                            {{ $index + 1 }}
                                                        @endif
                                                    </td>
                                                    <td class="text-start">
                                                        @if($isManual)
                                                            <span class="fw-semibold text-warning"><i class="bi bi-asterisk small me-1"></i>{{ $detail->nama_barang_manual }}</span>
                                                        @else
                                                            <span class="fw-semibold text-dark">{{ $detail->barang->nama_barang ?? 'Barang tidak ditemukan' }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light-secondary text-dark">{{ $detail->areaLocation->lokasi_area ?? '-' }}</span>
                                                    </td>
                                                    <td class="text-center fw-bold">{{ $detail->jumlah_barang }}</td>
                                                    <td class="text-center">
                                                        @if($detail->awal === 'baik')
                                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>OK</span>
                                                        @elseif($detail->awal === 'tidak-baik')
                                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Not OK</span>
                                                        @else
                                                            <span class="badge bg-secondary">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($detail->akhir === 'baik')
                                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>OK</span>
                                                        @elseif($detail->akhir === 'tidak-baik')
                                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Not OK</span>
                                                        @else
                                                            <span class="badge bg-secondary">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-muted small">{{ $detail->temuan_ketidaksesuaian ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">Tidak ada data barang</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
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