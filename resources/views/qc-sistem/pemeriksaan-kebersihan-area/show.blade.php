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
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Tanggal</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanKebersihanArea->tanggal->format('d-m-Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Shift</strong></label>
                                            <p class="form-control-plaintext">
                                                <span class="badge bg-info">{{ $pemeriksaanKebersihanArea->shift->shift }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Dibuat Pada</strong></label>
                                            <p class="text-muted">{{ $pemeriksaanKebersihanArea->created_at->format('d M Y H:i:s')  }}</p>
                                        </div>
                                    </div>

                                </div>

                                @php
                                    $areaData = is_string($pemeriksaanKebersihanArea->area_data) ? json_decode($pemeriksaanKebersihanArea->area_data, true) : $pemeriksaanKebersihanArea->area_data;
                                    $areaData = $areaData ?? [];
                                @endphp

                                @forelse($areaData as $index => $item)
                                    @php
                                        $selectedArea = $areas->firstWhere('id', $item['id_area'] ?? null);
                                        $selectedForm = $masterForms->firstWhere('id', $item['id_master_form'] ?? null);
                                    @endphp
                                    <div class="card mt-4 shadow-sm border border-primary">
                                        <div class="card-header bg-primary text-white pb-2 pt-3">
                                            <h5 class="card-title text-white mb-0">Area: {{ $selectedArea ? $selectedArea->nama_area : '-' }}</h5>
                                            <p class="mb-0 text-white-50"><small>Master Form: {{ $selectedForm ? $selectedForm->nama_form : '-' }}</small></p>
                                        </div>
                                        <div class="card-body pt-3">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-0">Jam Sebelum Proses</label>
                                                    <p class="form-control-plaintext fw-bold">{{ $item['jam_sebelum_proses'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-0">Jam Saat Proses</label>
                                                    <p class="form-control-plaintext fw-bold">{{ $item['jam_saat_proses'] ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <h6 class="mb-3 border-bottom pb-2"><strong>Aspek Yang Dinilai</strong></h6>
                                            @php
                                                $fields = $selectedForm ? $selectedForm->fields : [];
                                                $itemFields = collect($item['fields'] ?? []);
                                            @endphp

                                            @foreach($fields as $fIdx => $field)
                                                @php
                                                    $detail = $itemFields->firstWhere('id_master_form_field', $field->id);
                                                @endphp
                                                <div class="mb-3 p-3 border rounded bg-light">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-2">
                                                            <label class="form-label"><strong>{{ $fIdx + 1 }}. {{ $field->field_name }}</strong></label>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="mb-2">
                                                                <label class="form-label text-muted small mb-0">Status Verifikasi</label>
                                                                @if(isset($detail['verifikasi_hasil']) && $detail['verifikasi_hasil'] === 1)
                                                                    <p class="mb-0"><span class="badge bg-success">✓ OK</span></p>
                                                                @elseif(isset($detail['verifikasi_hasil']) && $detail['verifikasi_hasil'] === 0)
                                                                    <p class="mb-0"><span class="badge bg-danger">✗ Tidak OK</span></p>
                                                                @else
                                                                    <p class="mb-0"><span class="badge bg-secondary">Belum Diisi</span></p>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="mb-2">
                                                                <label class="form-label text-muted small mb-0">Sebelum Proses</label>
                                                                @if(isset($detail['status_sebelum_proses']) && $detail['status_sebelum_proses'] === 1)
                                                                    <p class="mb-0"><span class="badge bg-success">✓ OK</span></p>
                                                                @elseif(isset($detail['status_sebelum_proses']) && $detail['status_sebelum_proses'] === 0)
                                                                    <p class="mb-0"><span class="badge bg-danger">✗ Tidak OK</span></p>
                                                                @else
                                                                    <p class="mb-0"><span class="badge bg-secondary">-</span></p>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="mb-2">
                                                                <label class="form-label text-muted small mb-0">Saat Proses</label>
                                                                @if(isset($detail['status_saat_proses']) && $detail['status_saat_proses'] === 1)
                                                                    <p class="mb-0"><span class="badge bg-success">✓ OK</span></p>
                                                                @elseif(isset($detail['status_saat_proses']) && $detail['status_saat_proses'] === 0)
                                                                    <p class="mb-0"><span class="badge bg-danger">✗ Tidak OK</span></p>
                                                                @else
                                                                    <p class="mb-0"><span class="badge bg-secondary">-</span></p>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mt-2">
                                                            <div class="mb-2">
                                                                <label class="form-label text-muted small mb-0">Keterangan</label>
                                                                <p class="form-control-plaintext form-control-sm">{{ $detail['keterangan'] ?? '-' }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mt-2">
                                                            <div class="mb-2">
                                                                <label class="form-label text-muted small mb-0">Tindakan Koreksi</label>
                                                                <p class="form-control-plaintext form-control-sm">{{ $detail['tindakan_koreksi'] ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-warning mt-4">Belum ada data area.</div>
                                @endforelse

                                <div class="col-md-12 d-flex justify-content-end mt-4">



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