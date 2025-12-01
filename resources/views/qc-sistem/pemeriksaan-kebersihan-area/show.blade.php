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
                    <h3>Pemeriksaan Kebersihan Area</h3>
                    <p class="text-subtitle text-muted">Detail pemeriksaan kebersihan area</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kebersihan-area.index') }}">Pemeriksaan</a></li>
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
                            <h4 class="card-title">Detail Pemeriksaan Kebersihan Area</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Tanggal</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanKebersihanArea->tanggal->format('d-m-Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Shift</strong></label>
                                            <p class="form-control-plaintext">
                                                <span class="badge bg-info">{{ $pemeriksaanKebersihanArea->shift->shift }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Area</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanKebersihanArea->area->nama_area }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Master Form</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanKebersihanArea->masterForm->nama_form }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Jam Sebelum Proses</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanKebersihanArea->jam_sebelum_proses ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Jam Saat Proses</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanKebersihanArea->jam_saat_proses ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hasil Pemeriksaan -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h5 class="mb-3"><strong>Hasil Pemeriksaan</strong></h5>
                                    </div>

                                    @foreach($pemeriksaanKebersihanArea->details as $detail)
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="form-label"><strong>{{ $loop->iteration }}. {{ $detail->field->field_name }}</strong></label>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-2">
                                                        <label class="form-label"><strong>Status</strong></label>
                                                        @if($detail->status == 1)
                                                            <p><span class="badge bg-success">✓ Baik</span></p>
                                                        @elseif($detail->status == 0)
                                                            <p><span class="badge bg-danger">✗ Tidak Baik</span></p>
                                                        @else
                                                            <p><span class="badge bg-secondary">Belum Diisi</span></p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label class="form-label"><strong>Keterangan</strong></label>
                                                        <p class="form-control-plaintext">{{ $detail->keterangan ?? '-' }}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label class="form-label"><strong>Tindakan Koreksi</strong></label>
                                                        <p class="form-control-plaintext">{{ $detail->tindakan_koreksi ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="col-md-12 d-flex justify-content-end mt-4">
                                    <a href="{{ route('pemeriksaan-kebersihan-area.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                    <a href="{{ route('pemeriksaan-kebersihan-area.edit', $pemeriksaanKebersihanArea->uuid) }}" class="btn btn-primary me-1 mb-1">Edit</a>
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