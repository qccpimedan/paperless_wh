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
                    <h3>CS Meat</h3>
                    @if(request()->query('edit_per_2jam'))
                        <p class="text-subtitle text-muted">CS Meat (Per 1 jam)</p>
                    @else
                        <p class="text-subtitle text-muted">CS Meat</p>
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
                                <h4 class="card-title">Form CS Meat (Per 1 jam)</h4>
                            @else
                                <h4 class="card-title">Form CS Meat</h4>
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
                                                Anda hanya bisa melakukan edit setiap 1 jam sekali.<br>
                                                <strong>Edit berikutnya bisa dilakukan pada: {{ $nextEditTime->format('d/m/Y H:i') }}</strong>
                                            </p>
                                        </div>
                                    @else
                                        <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">✅ Edit Per 1 jam Tersedia</h4>
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
                                                <label for="pukul">Pukul <span class="text-danger">*</span></label>
                                                <input type="time" id="pukul" class="form-control @error('pukul') is-invalid @enderror"
                                                    name="pukul" value="{{ old('pukul', $pemeriksaanSuhuRuangV2->pukul) }}" required>
                                                @error('pukul')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="suhu_produk">Suhu Produk <span class="text-danger">*</span></label>
                                                <input type="text" inputmode="text" id="suhu_produk" class="form-control @error('suhu_produk') is-invalid @enderror"
                                                    name="suhu_produk" value="{{ old('suhu_produk', $pemeriksaanSuhuRuangV2->suhu_produk) }}" placeholder="Contoh: -18°C" required>
                                                @error('suhu_produk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="kategori_produk">Kategori Produk</label>
                                                <input type="text" id="kategori_produk" class="form-control" value="{{ $pemeriksaanSuhuRuangV2->produk->kategori_code ?? '-' }}" disabled>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="id_produk">Produk</label>
                                                <input type="text" id="id_produk" class="form-control" value="{{ $pemeriksaanSuhuRuangV2->produk->nama_produk ?? '-' }}" disabled>
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
                                                                    CS {{ $i }}
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
                                                            <label class="form-label"><strong>CS {{ $i }}</strong></label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="cold_storage_{{ $i }}_setting">Setting (°C)</label>
                                                            <select id="cold_storage_{{ $i }}_setting" class="form-select form-select-sm"
                                                                name="cold_storage_{{ $i }}_setting">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="-18" {{ old('cold_storage_' . $i . '_setting', $data['setting'] ?? '') == '-18' ? 'selected' : '' }}>Std ≤ (-18)°C</option>
                                                                <option value="manual" {{ old('cold_storage_' . $i . '_setting', $data['setting'] ?? '') && old('cold_storage_' . $i . '_setting', $data['setting'] ?? '') != '-18' ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="text" inputmode="text" id="cold_storage_{{ $i }}_setting_manual" 
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
                                                            <input type="text" inputmode="text" id="cold_storage_{{ $i }}_display_manual" 
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
                                                            <input type="text" inputmode="text" id="cold_storage_{{ $i }}_actual_manual" 
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
                                                                    Anteroom Loading {{ $i }}
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
                                                            <label class="form-label"><strong>Anteroom Loading {{ $i }}</strong></label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="anteroom_loading_{{ $i }}_setting">Setting (°C)</label>
                                                            <select id="anteroom_loading_{{ $i }}_setting" class="form-select form-select-sm"
                                                                name="anteroom_loading_{{ $i }}_setting">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="(0±5°C)" {{ old('anteroom_loading_' . $i . '_setting', $data['setting'] ?? '') == '(0±5°C)' ? 'selected' : '' }}>Std 0 ± 5°C</option>
                                                                <option value="manual" {{ old('anteroom_loading_' . $i . '_setting', $data['setting'] ?? '') && old('anteroom_loading_' . $i . '_setting', $data['setting'] ?? '') != '(0±5°C)' && old('anteroom_loading_' . $i . '_setting', $data['setting'] ?? '') ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="text" inputmode="text" id="anteroom_loading_{{ $i }}_setting_manual" 
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
                                                            <input type="text" inputmode="text" id="anteroom_loading_{{ $i }}_display_manual" 
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
                                                            <input type="text" inputmode="text" id="anteroom_loading_{{ $i }}_actual_manual" 
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
                                                        <input type="text" inputmode="text" id="pre_loading_setting" 
                                                            class="form-control form-control-sm"
                                                            name="pre_loading_setting"
                                                            placeholder="Setting"
                                                            value="{{ old('pre_loading_setting', $pemeriksaanSuhuRuangV2->suhu_pre_loading['setting'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="pre_loading_display">Display (°C)</label>
                                                        <input type="text" inputmode="text" id="pre_loading_display" 
                                                            class="form-control form-control-sm"
                                                            name="pre_loading_display"
                                                            placeholder="Display"
                                                            value="{{ old('pre_loading_display', $pemeriksaanSuhuRuangV2->suhu_pre_loading['display'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="pre_loading_actual">Actual (°C)</label>
                                                        <input type="text" inputmode="text" id="pre_loading_actual" 
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
                                                        <input type="text" inputmode="text" id="prestaging_setting" 
                                                            class="form-control form-control-sm"
                                                            name="prestaging_setting"
                                                            placeholder="Setting"
                                                            value="{{ old('prestaging_setting', $pemeriksaanSuhuRuangV2->suhu_prestaging['setting'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="prestaging_display">Display (°C)</label>
                                                        <input type="text" inputmode="text" id="prestaging_display" 
                                                            class="form-control form-control-sm"
                                                            name="prestaging_display"
                                                            placeholder="Display"
                                                            value="{{ old('prestaging_display', $pemeriksaanSuhuRuangV2->suhu_prestaging['display'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="prestaging_actual">Actual (°C)</label>
                                                        <input type="text" inputmode="text" id="prestaging_actual" 
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
                                                        <input type="text" inputmode="text" id="anteroom_ekspansi_abf_setting" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_abf_setting"
                                                            placeholder="Setting"
                                                            value="{{ old('anteroom_ekspansi_abf_setting', $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['setting'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_abf_display">Display (°C)</label>
                                                        <input type="text" inputmode="text" id="anteroom_ekspansi_abf_display" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_abf_display"
                                                            placeholder="Display"
                                                            value="{{ old('anteroom_ekspansi_abf_display', $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['display'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_abf_actual">Actual (°C)</label>
                                                        <input type="text" inputmode="text" id="anteroom_ekspansi_abf_actual" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_abf_actual"
                                                            placeholder="Actual"
                                                            value="{{ old('anteroom_ekspansi_abf_actual', $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['actual'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Suhu Chillroom RM -->
                                            <div class="col-md-12 mt-4">
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
                                                        <input type="text" inputmode="text" id="chillroom_rm_setting_manual" 
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
                                                        <input type="text" inputmode="text" id="chillroom_rm_display_manual" 
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
                                                        <input type="text" inputmode="text" id="chillroom_rm_actual_manual" 
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
                                                        <input type="text" inputmode="text" id="chillroom_domestik_setting_manual" 
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
                                                        <input type="text" inputmode="text" id="chillroom_domestik_display_manual" 
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
                                                        <input type="text" inputmode="text" id="chillroom_domestik_actual_manual" 
                                                            class="form-control form-control-sm mt-2" style="display: none;"
                                                            placeholder="Masukkan nilai"
                                                            value="{{ old('chillroom_domestik_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['actual'] ?? '') && old('chillroom_domestik_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['actual'] ?? '') != '(0-4°C)' ? old('chillroom_domestik_actual', $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['actual'] ?? '') : '' }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Catatan Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-2"><strong>Catatan</strong></h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="keterangan">Keterangan</label>
                                                        <textarea id="keterangan" class="form-control form-control-sm" name="keterangan" placeholder="Keterangan" rows="3">{{ old('keterangan', $pemeriksaanSuhuRuangV2->keterangan) }}</textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="tindakan_koreksi">Tindakan Koreksi</label>
                                                        <textarea id="tindakan_koreksi" class="form-control form-control-sm" name="tindakan_koreksi" placeholder="Tindakan Koreksi" rows="3">{{ old('tindakan_koreksi', $pemeriksaanSuhuRuangV2->tindakan_koreksi) }}</textarea>
                                                    </div>
                                                </div>
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

                                <!-- Riwayat Data Per Jam -->
                                @php
                                    $historiesV2 = $pemeriksaanSuhuRuangV2->histories->sortBy('id');
                                    $timelineRowsV2 = [];
                                    $noV2 = 1;

                                    $initJamV2 = $pemeriksaanSuhuRuangV2->pukul ? \Carbon\Carbon::parse($pemeriksaanSuhuRuangV2->pukul)->format('H:i') : '-';
                                    $initCreatedV2 = $pemeriksaanSuhuRuangV2->created_at ? $pemeriksaanSuhuRuangV2->created_at->format('d/m/Y H:i') : '-';

                                    $sectionLabelsV2 = [
                                        'cold_storage'          => 'Cold Storage',
                                        'anteroom_loading'      => 'Anteroom Loading',
                                        'pre_loading'           => 'Pre Loading',
                                        'prestaging'            => 'Prestaging',
                                        'anteroom_ekspansi_abf' => 'Anteroom Ekspansi ABF',
                                        'chillroom_rm'          => 'Chillroom RM',
                                        'chillroom_domestik'    => 'Chillroom Domestik',
                                    ];

                                    // Input Awal Cold Storage
                                    if (!empty($pemeriksaanSuhuRuangV2->suhu_cold_storage)) {
                                        foreach ((array)$pemeriksaanSuhuRuangV2->suhu_cold_storage as $uId => $uVal) {
                                            $timelineRowsV2[] = [
                                                'no'          => $noV2++,
                                                'waktu'       => $initJamV2,
                                                'edited'      => $initCreatedV2,
                                                'area'        => 'Cold Storage ' . $uId,
                                                'setting'     => $uVal['setting'] ?? '-',
                                                'aktual'      => $uVal['actual'] ?? '-',
                                                'display'     => $uVal['display'] ?? '-',
                                                'tipe'        => 'awal',
                                                'history_uuid'=> null,
                                            ];
                                        }
                                    }

                                    // Input Awal Anteroom Loading
                                    if (!empty($pemeriksaanSuhuRuangV2->suhu_anteroom_loading)) {
                                        foreach ((array)$pemeriksaanSuhuRuangV2->suhu_anteroom_loading as $uId => $uVal) {
                                            $timelineRowsV2[] = [
                                                'no'          => $noV2++,
                                                'waktu'       => $initJamV2,
                                                'edited'      => $initCreatedV2,
                                                'area'        => 'Anteroom Loading ' . $uId,
                                                'setting'     => $uVal['setting'] ?? '-',
                                                'aktual'      => $uVal['actual'] ?? '-',
                                                'display'     => $uVal['display'] ?? '-',
                                                'tipe'        => 'awal',
                                                'history_uuid'=> null,
                                            ];
                                        }
                                    }

                                    // Input Awal Single Sections
                                    foreach (['pre_loading', 'prestaging', 'anteroom_ekspansi_abf', 'chillroom_rm', 'chillroom_domestik'] as $sKey) {
                                        $attr = 'suhu_' . $sKey;
                                        $val = $pemeriksaanSuhuRuangV2->{$attr};
                                        if (!empty($val) && (!empty($val['setting']) || !empty($val['display']) || !empty($val['actual']))) {
                                            $timelineRowsV2[] = [
                                                'no'          => $noV2++,
                                                'waktu'       => $initJamV2,
                                                'edited'      => $initCreatedV2,
                                                'area'        => $sectionLabelsV2[$sKey],
                                                'setting'     => $val['setting'] ?? '-',
                                                'aktual'      => $val['actual'] ?? '-',
                                                'display'     => $val['display'] ?? '-',
                                                'tipe'        => 'awal',
                                                'history_uuid'=> null,
                                            ];
                                        }
                                    }

                                    // Input Awal Suhu Produk
                                    if (!empty($pemeriksaanSuhuRuangV2->suhu_produk)) {
                                        $timelineRowsV2[] = [
                                            'no'          => $noV2++,
                                            'waktu'       => $initJamV2,
                                            'edited'      => $initCreatedV2,
                                            'area'        => 'Suhu Produk',
                                            'setting'     => '-',
                                            'aktual'      => $pemeriksaanSuhuRuangV2->suhu_produk,
                                            'display'     => '-',
                                            'tipe'        => 'awal',
                                            'history_uuid'=> null,
                                        ];
                                    }

                                    // Iterasi History Updates
                                    foreach ($historiesV2 as $h) {
                                        $editedAtV2 = $h->created_at ? $h->created_at->format('d/m/Y H:i') : '-';
                                        $pukulH = $h->pukul_baru ?? $h->pukul_lama ?? null;
                                        $jamH = $pukulH ? \Carbon\Carbon::parse($pukulH)->format('H:i') : '-';

                                        foreach ($sectionLabelsV2 as $secKey => $secLabel) {
                                            $colLama = 'suhu_' . $secKey . '_lama';
                                            $colBaru = 'suhu_' . $secKey . '_baru';

                                            $lamaData = is_array($h->{$colLama}) ? $h->{$colLama} : (json_decode($h->{$colLama} ?? '[]', true) ?: []);
                                            $baruData = is_array($h->{$colBaru}) ? $h->{$colBaru} : (json_decode($h->{$colBaru} ?? '[]', true) ?: []);

                                            if (json_encode($lamaData) === json_encode($baruData)) continue;

                                            if (in_array($secKey, ['cold_storage', 'anteroom_loading'])) {
                                                $allUnits = array_unique(array_merge(array_keys((array)$lamaData), array_keys((array)$baruData)));
                                                foreach ($allUnits as $uId) {
                                                    $lItem = $lamaData[$uId] ?? [];
                                                    $bItem = $baruData[$uId] ?? [];
                                                    if (json_encode($lItem) === json_encode($bItem)) continue;
                                                    if (empty($bItem['setting']) && empty($bItem['display']) && empty($bItem['actual'])) continue;

                                                    $timelineRowsV2[] = [
                                                        'no'          => $noV2++,
                                                        'waktu'       => $jamH,
                                                        'edited'      => $editedAtV2,
                                                        'area'        => $secLabel . ' ' . $uId,
                                                        'setting'     => $bItem['setting'] ?? '-',
                                                        'aktual'      => $bItem['actual'] ?? '-',
                                                        'display'     => $bItem['display'] ?? '-',
                                                        'tipe'        => 'update',
                                                        'history_uuid'=> $h->uuid,
                                                        'field_type'  => 'suhu_data',
                                                        'section_key' => $secKey,
                                                        'unit_id'     => $uId,
                                                    ];
                                                }
                                            } else {
                                                if (empty($baruData['setting']) && empty($baruData['display']) && empty($baruData['actual'])) continue;
                                                $timelineRowsV2[] = [
                                                    'no'          => $noV2++,
                                                    'waktu'       => $jamH,
                                                    'edited'      => $editedAtV2,
                                                    'area'        => $secLabel,
                                                    'setting'     => $baruData['setting'] ?? '-',
                                                    'aktual'      => $baruData['actual'] ?? '-',
                                                    'display'     => $baruData['display'] ?? '-',
                                                    'tipe'        => 'update',
                                                    'history_uuid'=> $h->uuid,
                                                    'field_type'  => 'suhu_data',
                                                    'section_key' => $secKey,
                                                    'unit_id'     => null,
                                                ];
                                            }
                                        }

                                        // Update Suhu Produk
                                        if (($h->suhu_produk_lama ?? null) !== ($h->suhu_produk_baru ?? null) && !empty($h->suhu_produk_baru)) {
                                            $timelineRowsV2[] = [
                                                'no'          => $noV2++,
                                                'waktu'       => $jamH,
                                                'edited'      => $editedAtV2,
                                                'area'        => 'Suhu Produk',
                                                'setting'     => '-',
                                                'aktual'      => $h->suhu_produk_baru,
                                                'display'     => '-',
                                                'tipe'        => 'update',
                                                'history_uuid'=> $h->uuid,
                                                'field_type'  => 'suhu_produk',
                                                'section_key' => null,
                                                'unit_id'     => null,
                                            ];
                                        }
                                     }
                                @endphp

                                @if(!empty($timelineRowsV2))
                                <div class="col-12 mt-5 pt-4 border-top">
                                    <h5 class="mb-3 d-flex align-items-center gap-2">
                                        <i class="bi bi-clock-history text-primary"></i>
                                        <strong>Riwayat Data Per Jam</strong>
                                        <span class="badge bg-primary ms-1">{{ count($timelineRowsV2) }} entri</span>
                                    </h5>
                                    <div class="alert alert-info py-2 px-3 mb-3" style="font-size:0.875rem;">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Klik tombol <strong>Edit</strong> pada baris riwayat (status <span class="badge bg-warning text-dark">Update</span>) untuk mengoreksi data jam tersebut.
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm align-middle">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th class="text-center" style="width:4%">No</th>
                                                    <th class="text-center" style="width:8%">Pukul</th>
                                                    <th style="width:20%">Area</th>
                                                    <th class="text-center">Setting (°C)</th>
                                                    <th class="text-center">Actual (°C)</th>
                                                    <th class="text-center">Display (°C)</th>
                                                    <th class="text-center" style="width:8%">Status</th>
                                                    <th class="text-center" style="width:8%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($timelineRowsV2 as $tRow)
                                                <tr class="{{ $tRow['tipe'] === 'update' ? 'table-warning' : '' }}">
                                                    <td class="text-center">{{ $tRow['no'] }}</td>
                                                    <td class="text-center fw-semibold">{{ $tRow['waktu'] }}</td>
                                                    <td>{{ $tRow['area'] }}</td>
                                                    <td class="text-center">{{ $tRow['setting'] }}</td>
                                                    <td class="text-center">{{ $tRow['aktual'] }}</td>
                                                    <td class="text-center">{{ $tRow['display'] }}</td>
                                                    <td class="text-center">
                                                        @if($tRow['tipe'] === 'awal')
                                                            <span class="badge bg-success">Input Awal</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">Update</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($tRow['tipe'] === 'update' && !empty($tRow['history_uuid']))
                                                            <button type="button"
                                                                class="btn btn-sm btn-primary btn-edit-history-v2"
                                                                data-history-uuid="{{ $tRow['history_uuid'] }}"
                                                                data-pukul="{{ $tRow['waktu'] !== '-' ? $tRow['waktu'] : '' }}"
                                                                data-area="{{ $tRow['area'] }}"
                                                                data-field-type="{{ $tRow['field_type'] }}"
                                                                data-section-key="{{ $tRow['section_key'] }}"
                                                                data-unit-id="{{ $tRow['unit_id'] }}"
                                                                data-setting="{{ $tRow['setting'] !== '-' ? $tRow['setting'] : '' }}"
                                                                data-aktual="{{ $tRow['aktual'] !== '-' ? $tRow['aktual'] : '' }}"
                                                                data-display="{{ $tRow['display'] !== '-' ? $tRow['display'] : '' }}">
                                                                 Edit
                                                            </button>
                                                        @else
                                                            <span class="text-muted" style="font-size:0.75rem">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal Edit History V2 -->
<div class="modal fade" id="editHistoryModalV2" tabindex="-1" aria-labelledby="editHistoryModalV2Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editHistoryFormV2" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editHistoryModalV2Label">
                        <i class="bi bi-pencil-square me-1"></i> Edit Data Riwayat Per Jam
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="field_type" id="modalFieldTypeV2">
                    <input type="hidden" name="section_key" id="modalSectionKeyV2">
                    <input type="hidden" name="unit_id" id="modalUnitIdV2">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Area</label>
                        <input type="text" class="form-control" id="modalAreaLabelV2" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label for="modalPukulV2" class="form-label fw-bold">Pukul</label>
                        <input type="time" class="form-control" id="modalPukulV2" name="pukul" required>
                    </div>

                    <!-- Fields untuk Suhu Produk -->
                    <div id="modalSuhuProdukContainerV2" style="display:none;">
                        <div class="mb-3">
                            <label for="modalSuhuProdukV2" class="form-label fw-bold">Suhu Produk (°C)</label>
                            <input type="text" class="form-control" id="modalSuhuProdukV2" name="suhu_produk" placeholder="Contoh: -18°C">
                        </div>
                    </div>

                    <!-- Fields untuk Suhu Data Section -->
                    <div id="modalSuhuDataContainerV2" style="display:none;">
                        <div class="mb-3">
                            <label for="modalSettingV2" class="form-label fw-bold">Setting (°C)</label>
                            <input type="text" class="form-control" id="modalSettingV2" name="setting" placeholder="Nilai setting">
                        </div>
                        <div class="mb-3">
                            <label for="modalAktualV2" class="form-label fw-bold">Aktual (°C)</label>
                            <input type="text" class="form-control" id="modalAktualV2" name="aktual" placeholder="Nilai aktual">
                        </div>
                        <div class="mb-3">
                            <label for="modalDisplayV2" class="form-label fw-bold">Display (°C)</label>
                            <input type="text" class="form-control" id="modalDisplayV2" name="display" placeholder="Nilai display">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
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

    // Handle Edit History Modal V2
    const editHistoryModalV2 = new bootstrap.Modal(document.getElementById('editHistoryModalV2'));
    const editHistoryFormV2  = document.getElementById('editHistoryFormV2');
    const baseUrlV2          = "{{ url('qc-sistem/pemeriksaan-suhu-ruang-v2/' . $pemeriksaanSuhuRuangV2->uuid . '/history') }}";

    document.querySelectorAll('.btn-edit-history-v2').forEach(btn => {
        btn.addEventListener('click', function() {
            const historyUuid = this.getAttribute('data-history-uuid');
            const pukul       = this.getAttribute('data-pukul');
            const area        = this.getAttribute('data-area');
            const fieldType   = this.getAttribute('data-field-type');
            const sectionKey  = this.getAttribute('data-section-key');
            const unitId      = this.getAttribute('data-unit-id');
            const setting     = this.getAttribute('data-setting');
            const aktual      = this.getAttribute('data-aktual');
            const display     = this.getAttribute('data-display');

            editHistoryFormV2.action = `${baseUrlV2}/${historyUuid}`;

            document.getElementById('modalAreaLabelV2').value = area;
            document.getElementById('modalPukulV2').value     = pukul;
            document.getElementById('modalFieldTypeV2').value = fieldType;
            document.getElementById('modalSectionKeyV2').value = sectionKey || '';
            document.getElementById('modalUnitIdV2').value     = unitId || '';

            const spContainer = document.getElementById('modalSuhuProdukContainerV2');
            const sdContainer = document.getElementById('modalSuhuDataContainerV2');

            if (fieldType === 'suhu_produk') {
                spContainer.style.display = 'block';
                sdContainer.style.display = 'none';
                document.getElementById('modalSuhuProdukV2').value = aktual;
            } else {
                spContainer.style.display = 'none';
                sdContainer.style.display = 'block';
                document.getElementById('modalSettingV2').value = setting;
                document.getElementById('modalAktualV2').value  = aktual;
                document.getElementById('modalDisplayV2').value = display;
            }

            editHistoryModalV2.show();
        });
    });
});
</script>

@endsection