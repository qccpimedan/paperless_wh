@extends('layouts.app')
@section('container')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a>
    </header>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Pemeriksaan Suhu Ruang V3</h3>
                    <p class="text-subtitle text-muted">Buat pemeriksaan suhu ruang V3 baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}">Pemeriksaan V3</a></li>
                            <li class="breadcrumb-item active">Buat Pemeriksaan</li>
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
                            <h4 class="card-title">Form Pemeriksaan Suhu Ruang V3</h4>
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
                                <form class="form form-horizontal" action="{{ route('pemeriksaan-suhu-ruang-v3.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="pukul">Pukul <span class="text-danger">*</span></label>
                                                <input type="time" id="pukul" class="form-control @error('pukul') is-invalid @enderror" name="pukul" value="{{ old('pukul') }}" required>
                                                @error('pukul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="id_shift">Shift <span class="text-danger">*</span></label>
                                                <select id="id_shift" class="form-control @error('id_shift') is-invalid @enderror" name="id_shift" required>
                                                    <option value="">-- Pilih Shift --</option>
                                                    @foreach($shifts as $shift)
                                                        <option value="{{ $shift->id }}" {{ old('id_shift') == $shift->id ? 'selected' : '' }}>{{ $shift->shift }}</option>
                                                    @endforeach
                                                </select>
                                                @error('id_shift')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="id_area">Area <span class="text-danger">*</span></label>
                                                <select id="id_area" class="form-control @error('id_area') is-invalid @enderror" name="id_area" required>
                                                    <option value="">-- Pilih Area --</option>
                                                    @foreach($areas as $area)
                                                        <option value="{{ $area->id }}" {{ old('id_area') == $area->id ? 'selected' : '' }}>{{ $area->nama_area }}</option>
                                                    @endforeach
                                                </select>
                                                @error('id_area')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                                <div class="col-md-12 mt-4">
                                                    <h5 class="mb-3"><strong>{{ $fieldLabel }} (1-4) <small>(Isi sesuai dengan unit yang digunakan)</small></strong></h5>
                                                </div>
                                                <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                    <div class="row">
                                                        @for($i = 1; $i <= 4; $i++)
                                                            <div class="col-md-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input {{ $fieldKey }}-checkbox" type="checkbox" id="{{ $fieldKey }}_{{ $i }}_check" data-unit="{{ $i }}">
                                                                    <label class="form-check-label" for="{{ $fieldKey }}_{{ $i }}_check">Unit {{ $i }}</label>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                                @for($i = 1; $i <= 4; $i++)
                                                    <div class="col-md-12 mt-3 p-3 border rounded bg-light {{ $fieldKey }}-unit" id="{{ $fieldKey }}_{{ $i }}_form" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="form-label"><strong>Unit {{ $i }}</strong></label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="{{ $fieldKey }}_{{ $i }}_setting">Setting (°C)</label>
                                                                <input type="text" id="{{ $fieldKey }}_{{ $i }}_setting" class="form-control form-control-sm" name="{{ $fieldKey }}_{{ $i }}_setting" placeholder="Masukkan nilai" value="{{ old($fieldKey . '_' . $i . '_setting') }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="{{ $fieldKey }}_{{ $i }}_display">Display (°C)</label>
                                                                <input type="text" id="{{ $fieldKey }}_{{ $i }}_display" class="form-control form-control-sm" name="{{ $fieldKey }}_{{ $i }}_display" placeholder="Masukkan nilai" value="{{ old($fieldKey . '_' . $i . '_display') }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="{{ $fieldKey }}_{{ $i }}_actual">Actual (°C)</label>
                                                                <input type="text" id="{{ $fieldKey }}_{{ $i }}_actual" class="form-control form-control-sm" name="{{ $fieldKey }}_{{ $i }}_actual" placeholder="Masukkan nilai" value="{{ old($fieldKey . '_' . $i . '_actual') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor
                                            @endforeach

                                            <div class="col-md-12 mt-4">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea id="keterangan" class="form-control" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="tindakan_koreksi">Tindakan Koreksi</label>
                                                <textarea id="tindakan_koreksi" class="form-control" name="tindakan_koreksi" rows="3">{{ old('tindakan_koreksi') }}</textarea>
                                            </div>
                                            <div class="col-12 d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">
                                                    Buat Pemeriksaan
                                                </button>
                                                <a href="{{ route('pemeriksaan-suhu-ruang-v2.index') }}" class="btn btn-light-secondary me-1 mb-1">
                                                    Kembali
                                                </a>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fields = ['suhu_premix', 'suhu_seasoning', 'suhu_dry', 'suhu_cassing', 'suhu_beef', 'suhu_packaging', 'suhu_ruang_chemical', 'suhu_ruang_seasoning'];
        fields.forEach(field => {
            document.querySelectorAll(`.${field}-checkbox`).forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const unit = this.dataset.unit;
                    const form = document.getElementById(`${field}_${unit}_form`);
                    form.style.display = this.checked ? 'block' : 'none';
                });
            });
        });
    });
</script>
@endsection
