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
                    <h3>Detail Pemeriksaan Loading Kendaraan</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap pemeriksaan loading kendaraan</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-loading-kendaraan.index') }}">Pemeriksaan Loading Kendaraan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Pemeriksaan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <!-- Informasi Umum -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informasi Umum</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Tanggal Pemeriksaan</strong></label>
                                        <p class="text-muted">{{ \Carbon\Carbon::parse($pemeriksaanLoadingKendaraan->tanggal)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Plant</strong></label>
                                        <p class="text-muted">
                                            @if($pemeriksaanLoadingKendaraan->user->plant)
                                                <span class="badge bg-success">{{ $pemeriksaanLoadingKendaraan->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Master -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Master</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Ekspedisi</strong></label>
                                        <p class="text-muted">{{ $pemeriksaanLoadingKendaraan->ekspedisi->nama_ekspedisi ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Jenis Kendaraan</strong></label>
                                        <p class="text-muted">{{ $pemeriksaanLoadingKendaraan->kendaraan->jenis_kendaraan ?? '-' }} - {{ $pemeriksaanLoadingKendaraan->kendaraan->no_kendaraan ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Tujuan Pengiriman</strong></label>
                                        <p class="text-muted">{{ $pemeriksaanLoadingKendaraan->tujuanPengiriman->nama_tujuan ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Shift</strong></label>
                                        <p class="text-muted">{{ $pemeriksaanLoadingKendaraan->shift->shift ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Std Precooling</strong></label>
                                        <p class="text-muted">{{ $pemeriksaanLoadingKendaraan->stdPrecooling->nama_std_precooling ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Waktu dan Suhu -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Waktu dan Suhu</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Jam Mulai</strong></label>
                                        <p class="text-muted">{{ $pemeriksaanLoadingKendaraan->jam_mulai }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Jam Selesai</strong></label>
                                        <p class="text-muted">{{ $pemeriksaanLoadingKendaraan->jam_selesai }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Suhu Precooling</strong></label>
                                        <p class="text-muted">
                                            <span class="badge bg-info">{{ $pemeriksaanLoadingKendaraan->suhu_precooling }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kondisi Kebersihan Mobil -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Kondisi Kebersihan Mobil</h4>
                        </div>
                        <div class="card-body">
                            @php
                                $kebersihanMobil = json_decode($pemeriksaanLoadingKendaraan->kondisi_kebersihan_mobil, true) ?? [];
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>1. Berdebu, Kondensasi</strong></label>
                                        <p class="text-muted">
                                            @if(($kebersihanMobil['berdebu'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kebersihanMobil['berdebu'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>2. Noda (Karat, cat, tinta, oli, Asap Kendaraan), Sampah</strong></label>
                                        <p class="text-muted">
                                            @if(($kebersihanMobil['noda'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kebersihanMobil['noda'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>3. Terdapat Pertumbuhan Mikroorganisme (Jamur, Bau Busuk, Bau Menyimpang)</strong></label>
                                        <p class="text-muted">
                                            @if(($kebersihanMobil['mikroorganisme'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kebersihanMobil['mikroorganisme'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>4. Pallet, Pintu, Langit-langit, Dinding Kotor</strong></label>
                                        <p class="text-muted">
                                            @if(($kebersihanMobil['pallet_kotor'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kebersihanMobil['pallet_kotor'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>5. Terdapat Aktivitas Binatang (Tikus, Kecoa, Lalat, Belatung, Hama)</strong></label>
                                        <p class="text-muted">
                                            @if(($kebersihanMobil['aktivitas_binatang'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kebersihanMobil['aktivitas_binatang'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kondisi Mobil -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Kondisi Mobil</h4>
                        </div>
                        <div class="card-body">
                            @php
                                $kondisiMobil = json_decode($pemeriksaanLoadingKendaraan->kondisi_mobil, true) ?? [];
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>1. Kaca Mobil Pecah</strong></label>
                                        <p class="text-muted">
                                            @if(($kondisiMobil['kaca_pecah'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kondisiMobil['kaca_pecah'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>2. Dinding Mobil Rusak (Pecah)/Langit-langit Rusak/Pintu Rusak</strong></label>
                                        <p class="text-muted">
                                            @if(($kondisiMobil['dinding_rusak'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kondisiMobil['dinding_rusak'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>3. Lampu Dalam Box Pecah</strong></label>
                                        <p class="text-muted">
                                            @if(($kondisiMobil['lampu_pecah'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kondisiMobil['lampu_pecah'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>4. Karet Pintu Rusak</strong></label>
                                        <p class="text-muted">
                                            @if(($kondisiMobil['karet_pintu_rusak'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kondisiMobil['karet_pintu_rusak'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>5. Pintu Rusak</strong></label>
                                        <p class="text-muted">
                                            @if(($kondisiMobil['pintu_rusak'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kondisiMobil['pintu_rusak'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>6. Seal Tidak Utuh</strong></label>
                                        <p class="text-muted">
                                            @if(($kondisiMobil['seal_tidak_utuh'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kondisiMobil['seal_tidak_utuh'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>7. Terdapat Celah</strong></label>
                                        <p class="text-muted">
                                            @if(($kondisiMobil['terdapat_celah'] ?? null) == 1)
                                                <span class="badge bg-success">Ya ✓</span>
                                            @elseif(($kondisiMobil['terdapat_celah'] ?? null) == 0)
                                                <span class="badge bg-danger">Tidak ✗</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                @if($pemeriksaanLoadingKendaraan->keterangan)
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Keterangan</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">{{ $pemeriksaanLoadingKendaraan->keterangan }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Audit Info -->
                <!-- <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informasi Audit</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Dibuat Oleh</strong></label>
                                        <p class="text-muted">{{ $pemeriksaanLoadingKendaraan->user->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Dibuat Pada</strong></label>
                                        <p class="text-muted">{{ $pemeriksaanLoadingKendaraan->created_at->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Diupdate Pada</strong></label>
                                        <p class="text-muted">{{ $pemeriksaanLoadingKendaraan->updated_at->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>UUID</strong></label>
                                        <p class="text-muted"><small>{{ $pemeriksaanLoadingKendaraan->uuid }}</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Action Buttons -->
                <div class="col-md-12">
                    <div class="d-flex gap-2">
                        <a href="{{ route('pemeriksaan-loading-kendaraan.edit', $pemeriksaanLoadingKendaraan->uuid) }}" 
                           class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('pemeriksaan-loading-kendaraan.index') }}" 
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection