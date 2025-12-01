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
                    <h3>Pemeriksaan Suhu Ruang V2</h3>
                    <p class="text-subtitle text-muted">Detail pemeriksaan suhu ruang V2</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang-v2.index') }}">Pemeriksaan</a></li>
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
                            <h4 class="card-title">Detail Pemeriksaan Suhu Ruang V2</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Tanggal</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->tanggal->format('d-m-Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Shift</strong></label>
                                            <p class="form-control-plaintext"><span class="badge bg-info">{{ $pemeriksaanSuhuRuangV2->shift->shift }}</span></p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Area</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->area->nama_area }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Produk</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->produk->nama_bahan }}</p>
                                        </div>
                                    </div>

                                </div>

                                <!-- Cold Storage Section -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_cold_storage))
                                    @php
                                        $coldStorageData = $pemeriksaanSuhuRuangV2->suhu_cold_storage;
                                    @endphp
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Cold Storage</strong></h5>
                                        </div>
                                        @foreach($coldStorageData as $unit => $item)
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label"><strong>Unit {{ $unit }}</strong></label>
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
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_anteroom_loading))
                                    @php
                                        $anteroomLoadingData = $pemeriksaanSuhuRuangV2->suhu_anteroom_loading;
                                    @endphp
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Anteroom Loading</strong></h5>
                                        </div>
                                        @foreach($anteroomLoadingData as $unit => $item)
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label"><strong>Unit {{ $unit }}</strong></label>
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

                                <!-- Suhu Pre Loading -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_pre_loading))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Suhu Pre Loading</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_pre_loading['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_pre_loading['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_pre_loading['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Suhu Prestaging -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_prestaging))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Suhu Prestaging</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_prestaging['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_prestaging['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_prestaging['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Suhu Anteroom Ekspansi ABF -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Suhu Anteroom Ekspansi ABF</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Suhu Chillroom RM -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_chillroom_rm))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Suhu Chillroom RM</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Suhu Chillroom Domestik -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_chillroom_domestik))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Suhu Chillroom Domestik</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Catatan Section -->
                                @if($pemeriksaanSuhuRuangV2->keterangan || $pemeriksaanSuhuRuangV2->tindakan_koreksi)
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Catatan</strong></h5>
                                        </div>
                                        @if($pemeriksaanSuhuRuangV2->keterangan)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Keterangan</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->keterangan }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($pemeriksaanSuhuRuangV2->tindakan_koreksi)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Tindakan Koreksi</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->tindakan_koreksi }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="col-md-12 d-flex justify-content-end mt-4">
                                    <a href="{{ route('pemeriksaan-suhu-ruang-v2.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                    <a href="{{ route('pemeriksaan-suhu-ruang-v2.edit', $pemeriksaanSuhuRuangV2->uuid) }}" class="btn btn-primary me-1 mb-1">Edit</a>
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