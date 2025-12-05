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
                    @if(request()->query('edit_per_2jam'))
                        <p class="text-subtitle text-muted">Edit pemeriksaan suhu ruang V3 (Per 2 Jam)</p>
                    @else
                        <p class="text-subtitle text-muted">Edit pemeriksaan suhu ruang V3</p>
                    @endif
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}">Pemeriksaan V3</a></li>
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
                            @if(request()->query('edit_per_2jam'))
                                <h4 class="card-title">Form Edit Pemeriksaan Suhu Ruang V3 (Per 2 Jam)</h4>
                            @else
                                <h4 class="card-title">Form Edit Pemeriksaan Suhu Ruang V3</h4>
                            @endif
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

                                @if(request()->query('edit_per_2jam'))
                                    @if (!$canEdit)
                                        <div class="alert alert-warning" role="alert">
                                            <h4 class="alert-heading">⏱️ Edit Belum Tersedia</h4>
                                            <p class="mb-0">
                                                Anda hanya bisa melakukan edit setiap 2 jam sekali.<br>
                                                <strong>Edit berikutnya bisa dilakukan pada: {{ $nextEditTime->format('d/m/Y H:i') }}</strong>
                                            </p>
                                        </div>
                                    @else
                                        <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">✅ Edit Per 2 Jam Tersedia</h4>
                                            <p class="mb-0">
                                                Anda dapat melakukan edit data sekarang. Data lama akan disimpan di history.
                                            </p>
                                        </div>
                                    @endif
                                @endif

                                <form class="form form-horizontal" id="edit-form" action="{{ route('pemeriksaan-suhu-ruang-v3.update', $pemeriksaanSuhuRuangV3) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal', $pemeriksaanSuhuRuangV3->tanggal->format('Y-m-d')) }}" required>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="pukul">Waktu <span class="text-danger">*</span></label>
                                                <input type="time" id="pukul" class="form-control @error('pukul') is-invalid @enderror" name="pukul" value="{{ old('pukul', $pemeriksaanSuhuRuangV3->pukul) }}" required>
                                                @error('pukul')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="id_shift">Shift <span class="text-danger">*</span></label>
                                                <select id="id_shift" class="form-control @error('id_shift') is-invalid @enderror" name="id_shift" required>
                                                    <option value="">-- Pilih Shift --</option>
                                                    @foreach($shifts as $shift)
                                                        <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanSuhuRuangV3->id_shift) == $shift->id ? 'selected' : '' }}>{{ $shift->shift }}</option>
                                                    @endforeach
                                                </select>
                                                @error('id_shift')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="id_area">Area <span class="text-danger">*</span></label>
                                                <select id="id_area" class="form-control @error('id_area') is-invalid @enderror" name="id_area" required>
                                                    <option value="">-- Pilih Area --</option>
                                                    @foreach($areas as $area)
                                                        <option value="{{ $area->id }}" {{ old('id_area', $pemeriksaanSuhuRuangV3->id_area) == $area->id ? 'selected' : '' }}>{{ $area->nama_area }}</option>
                                                    @endforeach
                                                </select>
                                                @error('id_area')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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
                                                
                                                <!-- Checkbox Section -->
                                                <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                    <div class="row">
                                                        @for($i = 1; $i <= 4; $i++)
                                                            <div class="col-md-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input {{ $fieldKey }}-checkbox" type="checkbox" id="{{ $fieldKey }}_{{ $i }}_check" data-unit="{{ $i }}" {{ $pemeriksaanSuhuRuangV3->$fieldKey && isset($pemeriksaanSuhuRuangV3->$fieldKey["unit_$i"]) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="{{ $fieldKey }}_{{ $i }}_check">
                                                                        Unit {{ $i }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>

                                                @for($i = 1; $i <= 4; $i++)
                                                    @php
                                                        $unitData = $pemeriksaanSuhuRuangV3->$fieldKey && isset($pemeriksaanSuhuRuangV3->$fieldKey["unit_$i"]) ? $pemeriksaanSuhuRuangV3->$fieldKey["unit_$i"] : null;
                                                        $isChecked = $unitData !== null;
                                                    @endphp
                                                    <div class="col-md-12 mt-3 p-3 border rounded bg-light {{ $fieldKey }}-unit" id="{{ $fieldKey }}_{{ $i }}_form" style="display: {{ $isChecked ? 'block' : 'none' }};">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="form-label"><strong>Unit {{ $i }}</strong></label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="{{ $fieldKey }}_{{ $i }}_setting">Setting (°C)</label>
                                                                <input type="text" id="{{ $fieldKey }}_{{ $i }}_setting" class="form-control form-control-sm" name="{{ $fieldKey }}_{{ $i }}_setting" placeholder="Masukkan nilai" value="{{ old($fieldKey . '_' . $i . '_setting', $unitData['setting'] ?? '') }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="{{ $fieldKey }}_{{ $i }}_display">Display (°C)</label>
                                                                <input type="text" id="{{ $fieldKey }}_{{ $i }}_display" class="form-control form-control-sm" name="{{ $fieldKey }}_{{ $i }}_display" placeholder="Masukkan nilai" value="{{ old($fieldKey . '_' . $i . '_display', $unitData['display'] ?? '') }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="{{ $fieldKey }}_{{ $i }}_actual">Actual (°C)</label>
                                                                <input type="text" id="{{ $fieldKey }}_{{ $i }}_actual" class="form-control form-control-sm" name="{{ $fieldKey }}_{{ $i }}_actual" placeholder="Masukkan nilai" value="{{ old($fieldKey . '_' . $i . '_actual', $unitData['actual'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor
                                            @endforeach

                                            <!-- Catatan Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Catatan</strong></h5>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea id="keterangan" class="form-control form-control-sm" name="keterangan" placeholder="Keterangan" rows="3">{{ old('keterangan', $pemeriksaanSuhuRuangV3->keterangan) }}</textarea>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="tindakan_koreksi">Tindakan Koreksi</label>
                                                <textarea id="tindakan_koreksi" class="form-control form-control-sm" name="tindakan_koreksi" placeholder="Tindakan Koreksi" rows="3">{{ old('tindakan_koreksi', $pemeriksaanSuhuRuangV3->tindakan_koreksi) }}</textarea>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end mt-4">
                                                <a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}" class="btn btn-light-secondary me-1 mb-1">
                                                    Kembali
                                                </a>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">
                                                    Update Pemeriksaan
                                                </button>
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
                if (form) {
                    form.style.display = this.checked ? 'block' : 'none';
                }
            });
        });
    });
});
</script>

@endsection