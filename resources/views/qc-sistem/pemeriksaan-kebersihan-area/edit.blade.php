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
                    <p class="text-subtitle text-muted">Edit pemeriksaan kebersihan area</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kebersihan-area.index') }}">Pemeriksaan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Pemeriksaan</li>
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
                            <h4 class="card-title">Form Pemeriksaan Kebersihan Area</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-kebersihan-area.update', $pemeriksaanKebersihanArea->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                    name="tanggal" value="{{ old('tanggal', $pemeriksaanKebersihanArea->tanggal->format('Y-m-d')) }}" required>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="shift">Shift</label>
                                                <input type="text" id="shift" class="form-control" value="{{ $pemeriksaanKebersihanArea->shift->shift }}" disabled>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="area">Area</label>
                                                <input type="text" id="area" class="form-control" value="{{ $pemeriksaanKebersihanArea->area->nama_area }}" disabled>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="master_form">Master Form</label>
                                                <input type="text" id="master_form" class="form-control" value="{{ $pemeriksaanKebersihanArea->masterForm->nama_form }}" disabled>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="jam_sebelum_proses">Jam Sebelum Proses</label>
                                                <input type="time" id="jam_sebelum_proses" class="form-control @error('jam_sebelum_proses') is-invalid @enderror"
                                                    name="jam_sebelum_proses" value="{{ old('jam_sebelum_proses', $pemeriksaanKebersihanArea->jam_sebelum_proses) }}">
                                                @error('jam_sebelum_proses')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="jam_saat_proses">Jam Saat Proses</label>
                                                <input type="time" id="jam_saat_proses" class="form-control @error('jam_saat_proses') is-invalid @enderror"
                                                    name="jam_saat_proses" value="{{ old('jam_saat_proses', $pemeriksaanKebersihanArea->jam_saat_proses) }}">
                                                @error('jam_saat_proses')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Hasil Pemeriksaan</strong></h5>
                                            </div>

                                            @foreach($pemeriksaanKebersihanArea->details as $detail)
                                                <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label"><strong>{{ $loop->iteration }}. {{ $detail->field->field_name }}</strong></label>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="status_{{ $detail->id }}" 
                                                                    id="status_yes_{{ $detail->id }}" value="1"
                                                                    {{ old('status_' . $detail->id, $detail->status) == 1 ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="status_yes_{{ $detail->id }}">
                                                                    ✓ Baik
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="status_{{ $detail->id }}" 
                                                                    id="status_no_{{ $detail->id }}" value="0"
                                                                    {{ old('status_' . $detail->id, $detail->status) == 0 ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="status_no_{{ $detail->id }}">
                                                                    ✗ Tidak Baik
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mt-2">
                                                            <label for="keterangan_{{ $detail->id }}" class="form-label">Keterangan</label>
                                                            <textarea id="keterangan_{{ $detail->id }}" 
                                                                class="form-control form-control-sm"
                                                                name="keterangan_{{ $detail->id }}"
                                                                placeholder="Keterangan" rows="3">{{ old('keterangan_' . $detail->id, $detail->keterangan) }}</textarea>
                                                        </div>

                                                        <div class="col-md-6 mt-2">
                                                            <label for="tindakan_{{ $detail->id }}" class="form-label">Tindakan Koreksi</label>
                                                            <textarea id="tindakan_{{ $detail->id }}" 
                                                                class="form-control form-control-sm"
                                                                name="tindakan_koreksi_{{ $detail->id }}"
                                                                placeholder="Tindakan Koreksi" rows="3">{{ old('tindakan_koreksi_' . $detail->id, $detail->tindakan_koreksi) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            <div class="col-md-12 d-flex justify-content-end mt-4">
                                                <a href="{{ route('pemeriksaan-kebersihan-area.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Update Pemeriksaan</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection