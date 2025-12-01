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
                    <h3>Pemeriksaan Suhu Ruang</h3>
                    <p class="text-subtitle text-muted">Detail pemeriksaan suhu ruang</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang.index') }}">Pemeriksaan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Pemeriksaan</li>
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
                            <h4 class="card-title">Detail Pemeriksaan Suhu Ruang</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Tanggal</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->tanggal->format('d-m-Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Shift</strong></label>
                                            <p class="form-control-plaintext"><span class="badge bg-info">{{ $pemeriksaanSuhuRuang->shift->shift }}</span></p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Area</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->area->nama_area }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Produk</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->produk->nama_bahan }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cold Storage Section -->
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['cold_storage'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Cold Storage</strong></h5>
                                        </div>
                                        @foreach($pemeriksaanSuhuRuang->suhu_data['cold_storage'] as $item)
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label"><strong>Unit {{ $item['unit'] }}</strong></label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Setting (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['setting'] ?? '-' }}
                                                            @if($item['setting'] == '-18')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['setting'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Display (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['display'] ?? '-' }}
                                                            @if($item['display'] == '-18')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['display'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Actual (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['actual'] ?? '-' }}
                                                            @if($item['actual'] == '-18')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['actual'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Anteroom Loading Section -->
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['anteroom_loading'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Anteroom Loading</strong></h5>
                                        </div>
                                        @foreach($pemeriksaanSuhuRuang->suhu_data['anteroom_loading'] as $item)
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label"><strong>Unit {{ $item['unit'] }}</strong></label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Setting (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['setting'] ?? '-' }}
                                                            @if(strpos($item['setting'] ?? '', '(0±5°C)') !== false || $item['setting'] == '(0±5°C)')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['setting'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Display (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['display'] ?? '-' }}
                                                            @if(strpos($item['display'] ?? '', '(0±5°C)') !== false || $item['display'] == '(0±5°C)')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['display'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Actual (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['actual'] ?? '-' }}
                                                            @if(strpos($item['actual'] ?? '', '(0±5°C)') !== false || $item['actual'] == '(0±5°C)')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['actual'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Pre Loading Section -->
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['pre_loading'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Pre Loading</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['pre_loading']['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['pre_loading']['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['pre_loading']['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Prestaging Section -->
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['prestaging'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Prestaging</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['prestaging']['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['prestaging']['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['prestaging']['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Anteroom Ekspansi Further Section -->
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_further'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Anteroom Ekspansi Further</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_further']['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_further']['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_further']['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Anteroom Ekspansi Sausage Section -->
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_sausage'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Anteroom Ekspansi Sausage</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_sausage']['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_sausage']['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_sausage']['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Catatan Section -->
                                @if($pemeriksaanSuhuRuang->keterangan || $pemeriksaanSuhuRuang->tindakan_koreksi)
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Catatan</strong></h5>
                                        </div>
                                        @if($pemeriksaanSuhuRuang->keterangan)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Keterangan</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->keterangan }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($pemeriksaanSuhuRuang->tindakan_koreksi)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Tindakan Koreksi</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->tindakan_koreksi }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="col-md-12 d-flex justify-content-end mt-4">
                                    <a href="{{ route('pemeriksaan-suhu-ruang.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                    <a href="{{ route('pemeriksaan-suhu-ruang.edit', $pemeriksaanSuhuRuang->uuid) }}" class="btn btn-primary me-1 mb-1">Edit</a>
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
