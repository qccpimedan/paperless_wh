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
                    @if(request()->query('edit_per_2jam'))
                        <p class="text-subtitle text-muted">Edit pemeriksaan suhu ruang V2 (Per 2 Jam)</p>
                    @else
                        <p class="text-subtitle text-muted">Edit pemeriksaan suhu ruang V2</p>
                    @endif
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang-v2.index') }}">Pemeriksaan</a></li>
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
                                <h4 class="card-title">Form Edit Pemeriksaan Suhu Ruang V2 (Per 2 Jam)</h4>
                            @else
                                <h4 class="card-title">Form Edit Pemeriksaan Suhu Ruang V2</h4>
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

                                <form class="form form-horizontal" id="edit-form" action="{{ route('pemeriksaan-suhu-ruang-v2.update', $pemeriksaanSuhuRuangV2->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                    name="tanggal" value="{{ old('tanggal', $pemeriksaanSuhuRuangV2->tanggal->format('Y-m-d')) }}" required>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="id_shift">Shift</label>
                                                <input type="text" id="id_shift" class="form-control" value="{{ $pemeriksaanSuhuRuangV2->shift->shift }}" disabled>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="id_area">Area</label>
                                                <input type="text" id="id_area" class="form-control" value="{{ $pemeriksaanSuhuRuangV2->area->nama_area }}" disabled>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="id_produk">Produk</label>
                                                <input type="text" id="id_produk" class="form-control" value="{{ $pemeriksaanSuhuRuangV2->produk->nama_bahan }}" disabled>
                                            </div>

                                            <!-- Suhu Cold Storage -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Suhu Cold Storage (1-4) <small>(Isi sesuai dengan unit yang digunakan)</small></strong></h5>
                                            </div>
                                            @php
                                                $coldStorageData = $pemeriksaanSuhuRuangV2->suhu_cold_storage ?? [];
                                                $coldStorageByUnit = [];
                                                if (is_array($coldStorageData)) {
                                                    foreach ($coldStorageData as $unit => $item) {
                                                        $coldStorageByUnit[$unit] = $item;
                                                    }
                                                }
                                            @endphp
                                            <!-- ✅ CHECKBOX SECTION -->
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    @for($i = 1; $i <= 4; $i++)
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input cold-storage-checkbox" type="checkbox" id="cold_storage_{{ $i }}_check" data-unit="{{ $i }}" {{ isset($coldStorageByUnit[$i]) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="cold_storage_{{ $i }}_check">
                                                                    Unit {{ $i }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                            @for($i = 1; $i <= 4; $i++)
                                                @php $data = $coldStorageByUnit[$i] ?? []; @endphp
                                                <div class="col-md-12 mt-3 p-3 border rounded bg-light cold-storage-unit" id="cold_storage_{{ $i }}_form" style="display: {{ isset($coldStorageByUnit[$i]) ? 'block' : 'none' }};">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label"><strong>Unit {{ $i }}</strong></label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="cold_storage_{{ $i }}_setting">Setting (°C)</label>
                                                            <select id="cold_storage_{{ $i }}_setting" class="form-select form-select-sm"
                                                                name="cold_storage_{{ $i }}_setting">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="-18" {{ old('cold_storage_' . $i . '_setting', $data['setting'] ?? '') == '-18' ? 'selected' : '' }}>Std ≤ (-18)°C</option>
                                                                <option value="manual" {{ old('cold_storage_' . $i . '_setting', $data['setting'] ?? '') && old('cold_storage_' . $i . '_setting', $data['setting'] ?? '') != '-18' ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="cold_storage_{{ $i }}_setting_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('cold_storage_' . $i . '_setting', $data['setting'] ?? '') && old('cold_storage_' . $i . '_setting', $data['setting'] ?? '') != '-18' ? old('cold_storage_' . $i . '_setting', $data['setting'] ?? '') : '' }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="cold_storage_{{ $i }}_display">Display (°C)</label>
                                                            <select id="cold_storage_{{ $i }}_display" class="form-select form-select-sm"
                                                                name="cold_storage_{{ $i }}_display">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="-18" {{ old('cold_storage_' . $i . '_display', $data['display'] ?? '') == '-18' ? 'selected' : '' }}>Std ≤ (-18)°C</option>
                                                                <option value="manual" {{ old('cold_storage_' . $i . '_display', $data['display'] ?? '') && old('cold_storage_' . $i . '_display', $data['display'] ?? '') != '-18' ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="cold_storage_{{ $i }}_display_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('cold_storage_' . $i . '_display', $data['display'] ?? '') && old('cold_storage_' . $i . '_display', $data['display'] ?? '') != '-18' ? old('cold_storage_' . $i . '_display', $data['display'] ?? '') : '' }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="cold_storage_{{ $i }}_actual">Actual (°C)</label>
                                                            <select id="cold_storage_{{ $i }}_actual" class="form-select form-select-sm"
                                                                name="cold_storage_{{ $i }}_actual">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="-18" {{ old('cold_storage_' . $i . '_actual', $data['actual'] ?? '') == '-18' ? 'selected' : '' }}>Std ≤ (-18)°C</option>
                                                                <option value="manual" {{ old('cold_storage_' . $i . '_actual', $data['actual'] ?? '') && old('cold_storage_' . $i . '_actual', $data['actual'] ?? '') != '-18' ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="cold_storage_{{ $i }}_actual_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('cold_storage_' . $i . '_actual', $data['actual'] ?? '') && old('cold_storage_' . $i . '_actual', $data['actual'] ?? '') != '-18' ? old('cold_storage_' . $i . '_actual', $data['actual'] ?? '') : '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor

                                            <!-- Suhu Anteroom Loading -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Suhu Anteroom Loading (1-4) <small>(Isi sesuai dengan unit yang digunakan)</small></strong></h5>
                                            </div>
                                            @php
                                                $anteroomLoadingData = $pemeriksaanSuhuRuangV2->suhu_anteroom_loading ?? [];
                                                $anteroomLoadingByUnit = [];
                                                if (is_array($anteroomLoadingData)) {
                                                    foreach ($anteroomLoadingData as $unit => $item) {
                                                        $anteroomLoadingByUnit[$unit] = $item;
                                                    }
                                                }
                                            @endphp
                                            <!-- ✅ CHECKBOX SECTION -->
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    @for($i = 1; $i <= 4; $i++)
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input anteroom-loading-checkbox" type="checkbox" id="anteroom_loading_{{ $i }}_check" data-unit="{{ $i }}" {{ isset($anteroomLoadingByUnit[$i]) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="anteroom_loading_{{ $i }}_check">
                                                                    Unit {{ $i }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                            @for($i = 1; $i <= 4; $i++)
                                                @php $data = $anteroomLoadingByUnit[$i] ?? []; @endphp
                                                <div class="col-md-12 mt-3 p-3 border rounded bg-light anteroom-loading-unit" id="anteroom_loading_{{ $i }}_form" style="display: {{ isset($anteroomLoadingByUnit[$i]) ? 'block' : 'none' }};">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label"><strong>Unit {{ $i }}</strong></label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="anteroom_loading_{{ $i }}_setting">Setting (°C)</label>
                                                            <select id="anteroom_loading_{{ $i }}_setting" class="form-select form-select-sm"
                                                                name="anteroom_loading_{{ $i }}_setting">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="(0±5°C)" {{ old('anteroom_loading_' . $i . '_setting', $data['setting'] ?? '') == '(0±5°C)' ? 'selected' : '' }}>Std 0 ± 5°C</option>
                                                                <option value="manual" {{ old('anteroom_loading_' . $i . '_setting', $data['setting'] ?? '') && old('anteroom_loading_' . $i . '_setting', $data['setting'] ?? '') != '(0±5°C)' && old('anteroom_loading_' . $i . '_setting', $data['setting'] ?? '') ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="anteroom_loading_{{ $i }}_setting_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('anteroom_loading_' . $i . '_setting', $data['setting'] ?? '') && old('anteroom_loading_' . $i . '_setting', $data['setting'] ?? '') != '0' ? old('anteroom_loading_' . $i . '_setting', $data['setting'] ?? '') : '' }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="anteroom_loading_{{ $i }}_display">Display (°C)</label>
                                                            <select id="anteroom_loading_{{ $i }}_display" class="form-select form-select-sm"
                                                                name="anteroom_loading_{{ $i }}_display">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="(0±5°C)" {{ old('anteroom_loading_' . $i . '_display', $data['display'] ?? '') == '(0±5°C)' ? 'selected' : '' }}>Std 0 ± 5°C</option>
                                                                <option value="manual" {{ old('anteroom_loading_' . $i . '_display', $data['display'] ?? '') && old('anteroom_loading_' . $i . '_display', $data['display'] ?? '') != '(0±5°C)' && old('anteroom_loading_' . $i . '_display', $data['display'] ?? '') ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="anteroom_loading_{{ $i }}_display_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('anteroom_loading_' . $i . '_display', $data['display'] ?? '') && old('anteroom_loading_' . $i . '_display', $data['display'] ?? '') != '0' ? old('anteroom_loading_' . $i . '_display', $data['display'] ?? '') : '' }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="anteroom_loading_{{ $i }}_actual">Actual (°C)</label>
                                                            <select id="anteroom_loading_{{ $i }}_actual" class="form-select form-select-sm"
                                                                name="anteroom_loading_{{ $i }}_actual">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="(0±5°C)" {{ old('anteroom_loading_' . $i . '_actual', $data['actual'] ?? '') == '(0±5°C)' ? 'selected' : '' }}>Std 0 ± 5°C</option>
                                                                <option value="manual" {{ old('anteroom_loading_' . $i . '_actual', $data['actual'] ?? '') && old('anteroom_loading_' . $i . '_actual', $data['actual'] ?? '') != '(0±5°C)' && old('anteroom_loading_' . $i . '_actual', $data['actual'] ?? '') ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="anteroom_loading_{{ $i }}_actual_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('anteroom_loading_' . $i . '_actual', $data['actual'] ?? '') && old('anteroom_loading_' . $i . '_actual', $data['actual'] ?? '') != '0' ? old('anteroom_loading_' . $i . '_actual', $data['actual'] ?? '') : '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor

                                            <!-- Suhu Pre Loading -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Suhu Pre Loading <small>(Opsional)</small></strong></h5>
                                            </div>
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="pre_loading_setting">Setting (°C)</label>
                                                        <input type="number" step="0.1" id="pre_loading_setting" 
                                                            class="form-control form-control-sm"
                                                            name="pre_loading_setting"
                                                            placeholder="Setting"
                                                            value="{{ old('pre_loading_setting', $pemeriksaanSuhuRuangV2->suhu_pre_loading['setting'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="pre_loading_display">Display (°C)</label>
                                                        <input type="number" step="0.1" id="pre_loading_display" 
                                                            class="form-control form-control-sm"
                                                            name="pre_loading_display"
                                                            placeholder="Display"
                                                            value="{{ old('pre_loading_display', $pemeriksaanSuhuRuangV2->suhu_pre_loading['display'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="pre_loading_actual">Actual (°C)</label>
                                                        <input type="number" step="0.1" id="pre_loading_actual" 
                                                            class="form-control form-control-sm"
                                                            name="pre_loading_actual"
                                                            placeholder="Actual"
                                                            value="{{ old('pre_loading_actual', $pemeriksaanSuhuRuangV2->suhu_pre_loading['actual'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Suhu Prestaging -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Suhu Prestaging <small>(Opsional)</small></strong></h5>
                                            </div>
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="prestaging_setting">Setting (°C)</label>
                                                        <input type="number" step="0.1" id="prestaging_setting" 
                                                            class="form-control form-control-sm"
                                                            name="prestaging_setting"
                                                            placeholder="Setting"
                                                            value="{{ old('prestaging_setting', $pemeriksaanSuhuRuangV2->suhu_prestaging['setting'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="prestaging_display">Display (°C)</label>
                                                        <input type="number" step="0.1" id="prestaging_display" 
                                                            class="form-control form-control-sm"
                                                            name="prestaging_display"
                                                            placeholder="Display"
                                                            value="{{ old('prestaging_display', $pemeriksaanSuhuRuangV2->suhu_prestaging['display'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="prestaging_actual">Actual (°C)</label>
                                                        <input type="number" step="0.1" id="prestaging_actual" 
                                                            class="form-control form-control-sm"
                                                            name="prestaging_actual"
                                                            placeholder="Actual"
                                                            value="{{ old('prestaging_actual', $pemeriksaanSuhuRuangV2->suhu_prestaging['actual'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Suhu Anteroom Ekspansi ABF -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Suhu Anteroom Ekspansi ABF <small>(Opsional)</small></strong></h5>
                                            </div>
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_abf_setting">Setting (°C)</label>
                                                        <input type="number" step="0.1" id="anteroom_ekspansi_abf_setting" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_abf_setting"
                                                            placeholder="Setting"
                                                            value="{{ old('anteroom_ekspansi_abf_setting', $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['setting'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_abf_display">Display (°C)</label>
                                                        <input type="number" step="0.1" id="anteroom_ekspansi_abf_display" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_abf_display"
                                                            placeholder="Display"
                                                            value="{{ old('anteroom_ekspansi_abf_display', $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['display'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_abf_actual">Actual (°C)</label>
                                                        <input type="number" step="0.1" id="anteroom_ekspansi_abf_actual" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_abf_actual"
                                                            placeholder="Actual"
                                                            value="{{ old('anteroom_ekspansi_abf_actual', $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['actual'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Suhu Chillroom RM -->
                                                <h5 class="mb-3"><strong>Suhu Chillroom RM (0 - 4°C) <small>(Opsional)</small></strong></h5>
                                            </div>
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="chillroom_rm_setting">Setting (°C)</label>
                                                        <select id="chillroom_rm_setting" class="form-select form-select-sm"
                                                            name="chillroom_rm_setting">
                                                            <option value="">-- Pilih atau Isi Manual --</option>
                                                            <option value="(0-4°C)" {{ old('chillroom_rm_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['setting'] ?? '') == '(0-4°C)' ? 'selected' : '' }}>Std 0 - 4°C</option>
                                                            <option value="manual" {{ old('chillroom_rm_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['setting'] ?? '') && old('chillroom_rm_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['setting'] ?? '') != '(0-4°C)' ? 'selected' : '' }}>Input Manual</option>
                                                        </select>
                                                        <input type="number" step="0.1" id="chillroom_rm_setting_manual" 
                                                            class="form-control form-control-sm mt-2" style="display: none;"
                                                            placeholder="Masukkan nilai"
                                                            value="{{ old('chillroom_rm_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['setting'] ?? '') && old('chillroom_rm_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['setting'] ?? '') != '(0-4°C)' ? old('chillroom_rm_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['setting'] ?? '') : '' }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="chillroom_rm_display">Display (°C)</label>
                                                        <select id="chillroom_rm_display" class="form-select form-select-sm"
                                                            name="chillroom_rm_display">
                                                            <option value="">-- Pilih atau Isi Manual --</option>
                                                            <option value="(0-4°C)" {{ old('chillroom_rm_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['display'] ?? '') == '(0-4°C)' ? 'selected' : '' }}>Std 0 - 4°C</option>
                                                            <option value="manual" {{ old('chillroom_rm_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['display'] ?? '') && old('chillroom_rm_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['display'] ?? '') != '(0-4°C)' ? 'selected' : '' }}>Input Manual</option>
                                                        </select>
                                                        <input type="number" step="0.1" id="chillroom_rm_display_manual" 
                                                            class="form-control form-control-sm mt-2" style="display: none;"
                                                            placeholder="Masukkan nilai"
                                                            value="{{ old('chillroom_rm_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['display'] ?? '') && old('chillroom_rm_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['display'] ?? '') != '(0-4°C)' ? old('chillroom_rm_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['display'] ?? '') : '' }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="chillroom_rm_actual">Actual (°C)</label>
                                                        <select id="chillroom_rm_actual" class="form-select form-select-sm"
                                                            name="chillroom_rm_actual">
                                                            <option value="">-- Pilih atau Isi Manual --</option>
                                                            <option value="(0-4°C)" {{ old('chillroom_rm_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['actual'] ?? '') == '(0-4°C)' ? 'selected' : '' }}>Std 0 - 4°C</option>
                                                            <option value="manual" {{ old('chillroom_rm_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['actual'] ?? '') && old('chillroom_rm_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['actual'] ?? '') != '(0-4°C)' ? 'selected' : '' }}>Input Manual</option>
                                                        </select>
                                                        <input type="number" step="0.1" id="chillroom_rm_actual_manual" 
                                                            class="form-control form-control-sm mt-2" style="display: none;"
                                                            placeholder="Masukkan nilai"
                                                            value="{{ old('chillroom_rm_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['actual'] ?? '') && old('chillroom_rm_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['actual'] ?? '') != '(0-4°C)' ? old('chillroom_rm_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['actual'] ?? '') : '' }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Suhu Chillroom Domestik -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Suhu Chillroom Domestik (0 - 4°C) <small>(Opsional)</small></strong></h5>
                                            </div>
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="chillroom_domestik_setting">Setting (°C)</label>
                                                        <select id="chillroom_domestik_setting" class="form-select form-select-sm"
                                                            name="chillroom_domestik_setting">
                                                            <option value="">-- Pilih atau Isi Manual --</option>
                                                            <option value="(0-4°C)" {{ old('chillroom_domestik_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['setting'] ?? '') == '(0-4°C)' ? 'selected' : '' }}>Std 0 - 4°C</option>
                                                            <option value="manual" {{ old('chillroom_domestik_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['setting'] ?? '') && old('chillroom_domestik_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['setting'] ?? '') != '(0-4°C)' ? 'selected' : '' }}>Input Manual</option>
                                                        </select>
                                                        <input type="number" step="0.1" id="chillroom_domestik_setting_manual" 
                                                            class="form-control form-control-sm mt-2" style="display: none;"
                                                            placeholder="Masukkan nilai"
                                                            value="{{ old('chillroom_domestik_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['setting'] ?? '') && old('chillroom_domestik_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['setting'] ?? '') != '(0-4°C)' ? old('chillroom_domestik_setting', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['setting'] ?? '') : '' }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="chillroom_domestik_display">Display (°C)</label>
                                                        <select id="chillroom_domestik_display" class="form-select form-select-sm"
                                                            name="chillroom_domestik_display">
                                                            <option value="">-- Pilih atau Isi Manual --</option>
                                                            <option value="(0-4°C)" {{ old('chillroom_domestik_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['display'] ?? '') == '(0-4°C)' ? 'selected' : '' }}>Std 0 - 4°C</option>
                                                            <option value="manual" {{ old('chillroom_domestik_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['display'] ?? '') && old('chillroom_domestik_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['display'] ?? '') != '(0-4°C)' ? 'selected' : '' }}>Input Manual</option>
                                                        </select>
                                                        <input type="number" step="0.1" id="chillroom_domestik_display_manual" 
                                                            class="form-control form-control-sm mt-2" style="display: none;"
                                                            placeholder="Masukkan nilai"
                                                            value="{{ old('chillroom_domestik_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['display'] ?? '') && old('chillroom_domestik_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['display'] ?? '') != '(0-4°C)' ? old('chillroom_domestik_display', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['display'] ?? '') : '' }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="chillroom_domestik_actual">Actual (°C)</label>
                                                        <select id="chillroom_domestik_actual" class="form-select form-select-sm"
                                                            name="chillroom_domestik_actual">
                                                            <option value="">-- Pilih atau Isi Manual --</option>
                                                            <option value="(0-4°C)" {{ old('chillroom_domestik_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['actual'] ?? '') == '(0-4°C)' ? 'selected' : '' }}>Std 0 - 4°C</option>
                                                            <option value="manual" {{ old('chillroom_domestik_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['actual'] ?? '') && old('chillroom_domestik_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['actual'] ?? '') != '(0-4°C)' ? 'selected' : '' }}>Input Manual</option>
                                                        </select>
                                                        <input type="number" step="0.1" id="chillroom_domestik_actual_manual" 
                                                            class="form-control form-control-sm mt-2" style="display: none;"
                                                            placeholder="Masukkan nilai"
                                                            value="{{ old('chillroom_domestik_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['actual'] ?? '') && old('chillroom_domestik_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['actual'] ?? '') != '(0-4°C)' ? old('chillroom_domestik_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['actual'] ?? '') : '' }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Catatan Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Catatan</strong></h5>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea id="keterangan" class="form-control form-control-sm" name="keterangan" placeholder="Keterangan" rows="3">{{ old('keterangan', $pemeriksaanSuhuRuangV2->keterangan) }}</textarea>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="tindakan_koreksi">Tindakan Koreksi</label>
                                                <textarea id="tindakan_koreksi" class="form-control form-control-sm" name="tindakan_koreksi" placeholder="Tindakan Koreksi" rows="3">{{ old('tindakan_koreksi', $pemeriksaanSuhuRuangV2->tindakan_koreksi) }}</textarea>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end mt-4">
                                                <a href="{{ route('pemeriksaan-suhu-ruang-v2.index') }}" class="btn btn-light-secondary me-1 mb-1">
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
    // Function to handle toggle for manual input with checkbox
    function setupManualToggleWithCheckbox(prefix, count) {
        for (let i = 1; i <= count; i++) {
            ['setting', 'display', 'actual'].forEach(field => {
                const selectId = `${prefix}_${i}_${field}`;
                const manualId = `${prefix}_${i}_${field}_manual`;
                const selectEl = document.getElementById(selectId);
                const manualEl = document.getElementById(manualId);
                
                if (selectEl && manualEl) {
                    selectEl.addEventListener('change', function() {
                        if (this.value === 'manual') {
                            manualEl.style.display = 'block';
                            manualEl.name = selectId;
                            selectEl.name = '';
                        } else {
                            manualEl.style.display = 'none';
                            manualEl.name = '';
                            selectEl.name = selectId;
                        }
                    });
                    
                    // Trigger on page load if manual was selected
                    if (selectEl.value === 'manual') {
                        manualEl.style.display = 'block';
                        manualEl.name = selectId;
                        selectEl.name = '';
                    } else {
                        selectEl.name = selectId;
                    }
                }
            });
        }
    }
    
    // Function to handle toggle for manual input (select with manual option)
    function setupManualToggleSimple(prefixes) {
        prefixes.forEach(prefix => {
            ['setting', 'display', 'actual'].forEach(field => {
                const selectId = `${prefix}_${field}`;
                const manualId = `${prefix}_${field}_manual`;
                const selectEl = document.getElementById(selectId);
                const manualEl = document.getElementById(manualId);
                
                if (selectEl && manualEl) {
                    selectEl.addEventListener('change', function() {
                        if (this.value === 'manual') {
                            manualEl.style.display = 'block';
                            manualEl.name = selectId;
                            selectEl.name = '';
                        } else {
                            manualEl.style.display = 'none';
                            manualEl.name = '';
                            selectEl.name = selectId;
                        }
                    });
                    
                    // Trigger on page load if manual was selected
                    if (selectEl.value === 'manual') {
                        manualEl.style.display = 'block';
                        manualEl.name = selectId;
                        selectEl.name = '';
                    } else {
                        selectEl.name = selectId;
                    }
                }
            });
        });
    }
    
    // Cold Storage toggle (with checkbox)
    setupManualToggleWithCheckbox('cold_storage', 4);
    
    // Anteroom Loading toggle (with checkbox)
    setupManualToggleWithCheckbox('anteroom_loading', 4);
    
    // Fields with manual toggle (select with manual option)
    setupManualToggleSimple([
        'chillroom_rm',
        'chillroom_domestik'
    ]);
    
    // Handle Cold Storage checkbox show/hide
    document.querySelectorAll('.cold-storage-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const unit = this.dataset.unit;
            const form = document.getElementById(`cold_storage_${unit}_form`);
            if (form) {
                form.style.display = this.checked ? 'block' : 'none';
            }
        });
    });
    
    // Handle Anteroom Loading checkbox show/hide
    document.querySelectorAll('.anteroom-loading-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const unit = this.dataset.unit;
            const form = document.getElementById(`anteroom_loading_${unit}_form`);
            if (form) {
                form.style.display = this.checked ? 'block' : 'none';
            }
        });
    });
    
    // Refresh notifikasi navbar setelah form submit berhasil
    const editForm = document.getElementById('edit-form');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            // Setelah form submit dan server merespons dengan redirect,
            // notifikasi akan otomatis refresh saat page load di halaman berikutnya
            // karena checkEditableRecords() dipanggil di DOMContentLoaded di navbar.blade.php
            // Jadi tidak perlu tambahan logic di sini
        });
    }
});
</script>

@endsection