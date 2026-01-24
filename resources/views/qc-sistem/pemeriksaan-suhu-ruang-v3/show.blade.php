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
                    <h3>Pemeriksaan Suhu Ruang V3</h3>
                    <p class="text-subtitle text-muted">Detail pemeriksaan suhu ruang V3</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}">Pemeriksaan V3</a></li>
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
                            <h4 class="card-title">Detail Pemeriksaan Suhu Ruang V3</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Tanggal</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV3->tanggal->format('d-m-Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Shift</strong></label>
                                            <p class="form-control-plaintext"><span class="badge bg-info">{{ $pemeriksaanSuhuRuangV3->shift->shift ?? '-' }}</span></p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Pukul</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV3->pukul }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Area</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV3->area->nama_area ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>User</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV3->user->name ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Plant</strong></label>
                                            <p class="form-control-plaintext"><span class="badge bg-info">{{ $pemeriksaanSuhuRuangV3->user->plant->plant ?? '-' }}</span></p>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $suhuFields = [
                                        'suhu_premix' => 'Suhu Premix',
                                        'suhu_seasoning' => 'Suhu Seasoning',
                                        'suhu_dry' => 'Suhu Dry',
                                        'suhu_cassing' => 'Suhu Cassing',
                                        'suhu_beef' => 'Suhu Beef',
                                        'suhu_packaging' => 'Suhu Packaging',
                                        'suhu_ruang_chemical' => 'Suhu Ruang Chemical',
                                        'suhu_ruang_seasoning' => 'Suhu Ruang Seasoning'
                                    ];
                                @endphp

                                @foreach($suhuFields as $fieldKey => $fieldLabel)
                                    @if(!empty($pemeriksaanSuhuRuangV3->$fieldKey))
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h5 class="mb-3"><strong>{{ $fieldLabel }}</strong></h5>
                                            </div>
                                            @foreach($pemeriksaanSuhuRuangV3->$fieldKey as $unit => $data)
                                                <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label"><strong>{{ ucfirst(str_replace('_', ' ', $unit)) }}</strong></label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Setting (°C)</label>
                                                            <p class="form-control-plaintext">{{ $data['setting'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Display (°C)</label>
                                                            <p class="form-control-plaintext">{{ $data['display'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Actual (°C)</label>
                                                            <p class="form-control-plaintext">{{ $data['actual'] ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Catatan Section -->
                                @if($pemeriksaanSuhuRuangV3->keterangan || $pemeriksaanSuhuRuangV3->tindakan_koreksi)
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Catatan</strong></h5>
                                        </div>
                                        @if($pemeriksaanSuhuRuangV3->keterangan)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Keterangan</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV3->keterangan }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($pemeriksaanSuhuRuangV3->tindakan_koreksi)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Tindakan Koreksi</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV3->tindakan_koreksi }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="col-md-12 d-flex justify-content-end mt-4">
                                    <a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                    <a href="{{ route('pemeriksaan-suhu-ruang-v3.edit', $pemeriksaanSuhuRuangV3) }}" class="btn btn-primary me-1 mb-1">Edit</a>
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