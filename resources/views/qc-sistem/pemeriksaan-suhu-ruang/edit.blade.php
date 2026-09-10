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
                    <h3>Food Prosesing</h3>
                    @if(request()->query('edit_per_2jam'))
                        <p class="text-subtitle text-muted">Edit Food Prosesing (Per 1 Jam)</p>
                    @else
                        <p class="text-subtitle text-muted">Edit Food Prosesing</p>
                    @endif
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang.index') }}">Pemeriksaan</a></li>
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
                                <h4 class="card-title">Form Edit Food Prosesing (Per 1 Jam)</h4>
                            @else
                                <h4 class="card-title">Form Edit Food Prosesing</h4>
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
                                            <h4 class="alert-heading">✅ Edit Per 1 Jam Tersedia</h4>
                                            <p class="mb-0">
                                                Anda dapat melakukan edit data sekarang. Data lama akan disimpan di history.
                                            </p>
                                        </div>
                                    @endif
                                @endif

                                <form class="form form-horizontal" id="edit-form" action="{{ route('pemeriksaan-suhu-ruang.update', $pemeriksaanSuhuRuang->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                    name="tanggal" value="{{ old('tanggal', $pemeriksaanSuhuRuang->tanggal->format('Y-m-d')) }}" required>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="shift">Shift</label>
                                                <input type="text" id="shift" class="form-control" value="{{ $pemeriksaanSuhuRuang->shift->shift }}" disabled>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="produk">Produk</label>
                                                <input type="text" id="produk" class="form-control" value="{{ $pemeriksaanSuhuRuang->produk->nama_produk ?? '-' }}" disabled>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="kategori_produk">Kategori Produk</label>
                                                <input type="text" id="kategori_produk" class="form-control" value="{{ $pemeriksaanSuhuRuang->produk->kategori_code ?? '-' }}" disabled>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="suhu_produk">Suhu Produk</label>
                                                <input type="text" inputmode="text" id="suhu_produk" class="form-control @error('suhu_produk') is-invalid @enderror"
                                                    name="suhu_produk" value="{{ old('suhu_produk', $pemeriksaanSuhuRuang->suhu_produk) }}">
                                                @error('suhu_produk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="pukul">Pukul</label>
                                                <input type="time" id="pukul" class="form-control @error('pukul') is-invalid @enderror"
                                                    name="pukul" value="{{ old('pukul', $pemeriksaanSuhuRuang->pukul ? \Carbon\Carbon::parse($pemeriksaanSuhuRuang->pukul)->format('H:i') : '') }}">
                                                @error('pukul')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Cold Storage Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Cold Storage (1-4) <small>(Isi sesuai dengan unit yang digunakan)</small></strong></h5>
                                            </div>
                                            @php
                                                $coldStorageData = $pemeriksaanSuhuRuang->suhu_data['cold_storage'] ?? [];
                                                $coldStorageByUnit = [];
                                                foreach ($coldStorageData as $item) {
                                                    $coldStorageByUnit[$item['unit']] = $item;
                                                }
                                            @endphp
                                            <!-- ✅ TAMBAH INI: Checkbox Section -->
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

                                            <!-- Anteroom Loading Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Anteroom Loading (1-4) <small>(Isi sesuai dengan unit yang digunakan)</small></strong></h5>
                                            </div>
                                            @php
                                                $anteroomLoadingData = $pemeriksaanSuhuRuang->suhu_data['anteroom_loading'] ?? [];
                                                $anteroomLoadingByUnit = [];
                                                foreach ($anteroomLoadingData as $item) {
                                                    $anteroomLoadingByUnit[$item['unit']] = $item;
                                                }
                                            @endphp
                                            <!-- ✅ TAMBAH INI: Checkbox Section -->
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

                                            <!-- Pre Loading Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Pre Loading <small>(Opsional)</small></strong></h5>
                                            </div>
                                            @php $preLoading = $pemeriksaanSuhuRuang->suhu_data['pre_loading'] ?? []; @endphp
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="pre_loading_setting">Setting (°C)</label>
                                                        <input type="text" inputmode="text" id="pre_loading_setting" 
                                                            class="form-control form-control-sm"
                                                            name="pre_loading_setting"
                                                            value="{{ old('pre_loading_setting', $preLoading['setting'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="pre_loading_display">Display (°C)</label>
                                                        <input type="text" inputmode="text" id="pre_loading_display" 
                                                            class="form-control form-control-sm"
                                                            name="pre_loading_display"
                                                            value="{{ old('pre_loading_display', $preLoading['display'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="pre_loading_actual">Actual (°C)</label>
                                                        <input type="text" inputmode="text" id="pre_loading_actual" 
                                                            class="form-control form-control-sm"
                                                            name="pre_loading_actual"
                                                            value="{{ old('pre_loading_actual', $preLoading['actual'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Prestaging Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Prestaging <small>(Opsional)</small></strong></h5>
                                            </div>
                                            @php $prestaging = $pemeriksaanSuhuRuang->suhu_data['prestaging'] ?? []; @endphp
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="prestaging_setting">Setting (°C)</label>
                                                        <input type="text" inputmode="text" id="prestaging_setting" 
                                                            class="form-control form-control-sm"
                                                            name="prestaging_setting"
                                                            value="{{ old('prestaging_setting', $prestaging['setting'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="prestaging_display">Display (°C)</label>
                                                        <input type="text" inputmode="text" id="prestaging_display" 
                                                            class="form-control form-control-sm"
                                                            name="prestaging_display"
                                                            value="{{ old('prestaging_display', $prestaging['display'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="prestaging_actual">Actual (°C)</label>
                                                        <input type="text" inputmode="text" id="prestaging_actual" 
                                                            class="form-control form-control-sm"
                                                            name="prestaging_actual"
                                                            value="{{ old('prestaging_actual', $prestaging['actual'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Anteroom Ekspansi Further Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Anteroom Ekspansi Further <small>(Opsional)</small></strong></h5>
                                            </div>
                                            @php $anteroomEkspansiFurther = $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_further'] ?? []; @endphp
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_further_setting">Setting (°C)</label>
                                                        <input type="text" inputmode="text" id="anteroom_ekspansi_further_setting" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_further_setting"
                                                            value="{{ old('anteroom_ekspansi_further_setting', $anteroomEkspansiFurther['setting'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_further_display">Display (°C)</label>
                                                        <input type="text" inputmode="text" id="anteroom_ekspansi_further_display" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_further_display"
                                                            value="{{ old('anteroom_ekspansi_further_display', $anteroomEkspansiFurther['display'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_further_actual">Actual (°C)</label>
                                                        <input type="text" inputmode="text" id="anteroom_ekspansi_further_actual" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_further_actual"
                                                            value="{{ old('anteroom_ekspansi_further_actual', $anteroomEkspansiFurther['actual'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Anteroom Ekspansi Sausage Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Anteroom Ekspansi Sausage <small>(Opsional)</small></strong></h5>
                                            </div>
                                            @php $anteroomEkspansiSausage = $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_sausage'] ?? []; @endphp
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_sausage_setting">Setting (°C)</label>
                                                        <input type="text" inputmode="text" id="anteroom_ekspansi_sausage_setting" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_sausage_setting"
                                                            value="{{ old('anteroom_ekspansi_sausage_setting', $anteroomEkspansiSausage['setting'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_sausage_display">Display (°C)</label>
                                                        <input type="text" inputmode="text" id="anteroom_ekspansi_sausage_display" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_sausage_display"
                                                            value="{{ old('anteroom_ekspansi_sausage_display', $anteroomEkspansiSausage['display'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_sausage_actual">Actual (°C)</label>
                                                        <input type="text" inputmode="text" id="anteroom_ekspansi_sausage_actual" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_sausage_actual"
                                                            value="{{ old('anteroom_ekspansi_sausage_actual', $anteroomEkspansiSausage['actual'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Catatan Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Catatan</strong></h5>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea id="keterangan" class="form-control form-control-sm"
                                                    name="keterangan" placeholder="Keterangan" rows="3">{{ old('keterangan', $pemeriksaanSuhuRuang->keterangan) }}</textarea>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="tindakan_koreksi">Tindakan Koreksi</label>
                                                <textarea id="tindakan_koreksi" class="form-control form-control-sm"
                                                    name="tindakan_koreksi" placeholder="Tindakan Koreksi" rows="3">{{ old('tindakan_koreksi', $pemeriksaanSuhuRuang->tindakan_koreksi) }}</textarea>
                                            </div>

                                            <div class="col-md-12 d-flex justify-content-end mt-4">
                                                <a href="{{ route('pemeriksaan-suhu-ruang.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Update Pemeriksaan</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                {{-- ===== RIWAYAT DATA PER JAM (di luar form) ===== --}}
                                @php
                                    $sectionLabelsEdit = [
                                        'cold_storage'               => 'Cold Storage',
                                        'anteroom_loading'           => 'Anteroom Loading',
                                        'pre_loading'                => 'Pre Loading',
                                        'prestaging'                 => 'Prestaging',
                                        'anteroom_ekspansi_further'  => 'Anteroom Ekspansi Further',
                                        'anteroom_ekspansi_sausage'  => 'Anteroom Ekspansi Sausage',
                                    ];

                                    $currentSuhuEdit = is_array($pemeriksaanSuhuRuang->suhu_data)
                                        ? $pemeriksaanSuhuRuang->suhu_data
                                        : (json_decode($pemeriksaanSuhuRuang->suhu_data ?? '[]', true) ?: []);

                                    $historiesEdit = $pemeriksaanSuhuRuang->relationLoaded('histories') && $pemeriksaanSuhuRuang->histories
                                        ? $pemeriksaanSuhuRuang->histories->sortBy('created_at')
                                        : collect();

                                    $timelineRowsEdit = [];
                                    $noEdit = 1;

                                    $firstHistoryEdit = $historiesEdit->first();
                                    $initSuhuEdit = $firstHistoryEdit
                                        ? (is_array($firstHistoryEdit->suhu_data_lama) ? $firstHistoryEdit->suhu_data_lama : (json_decode($firstHistoryEdit->suhu_data_lama ?? '[]', true) ?: []))
                                        : $currentSuhuEdit;
                                    $initPukulEdit = $firstHistoryEdit ? ($firstHistoryEdit->pukul_lama ?? $pemeriksaanSuhuRuang->pukul) : $pemeriksaanSuhuRuang->pukul;

                                    foreach ($sectionLabelsEdit as $secKey => $secLabel) {
                                        $secData = $initSuhuEdit[$secKey] ?? [];
                                        if (empty($secData)) continue;

                                        if (in_array($secKey, ['cold_storage', 'anteroom_loading'])) {
                                            foreach ((array) $secData as $item) {
                                                if (!is_array($item) || (empty($item['setting']) && empty($item['display']) && empty($item['actual']))) continue;
                                                $timelineRowsEdit[] = [
                                                    'no'     => $noEdit++,
                                                    'waktu'  => $initPukulEdit ? \Carbon\Carbon::parse($initPukulEdit)->format('H:i') : '-',
                                                    'edited' => $pemeriksaanSuhuRuang->created_at ? $pemeriksaanSuhuRuang->created_at->format('d/m/Y H:i') : '-',
                                                    'area'   => $secLabel . ' ' . ($item['unit'] ?? ''),
                                                    'setting'=> $item['setting'] ?? '-',
                                                    'aktual' => $item['actual'] ?? '-',
                                                    'display'=> $item['display'] ?? '-',
                                                    'tipe'   => 'awal',
                                                ];
                                            }
                                        } else {
                                            if (empty($secData['setting']) && empty($secData['display']) && empty($secData['actual'])) continue;
                                            $timelineRowsEdit[] = [
                                                'no'     => $noEdit++,
                                                'waktu'  => $initPukulEdit ? \Carbon\Carbon::parse($initPukulEdit)->format('H:i') : '-',
                                                'edited' => $pemeriksaanSuhuRuang->created_at ? $pemeriksaanSuhuRuang->created_at->format('d/m/Y H:i') : '-',
                                                'area'   => $secLabel,
                                                'setting'=> $secData['setting'] ?? '-',
                                                'aktual' => $secData['actual'] ?? '-',
                                                'display'=> $secData['display'] ?? '-',
                                                'tipe'   => 'awal',
                                            ];
                                        }
                                    }

                                    // === Suhu Produk - Input Awal ===
                                    $initSuhuProdukE = $firstHistoryEdit ? ($firstHistoryEdit->suhu_produk_lama ?? $pemeriksaanSuhuRuang->suhu_produk) : $pemeriksaanSuhuRuang->suhu_produk;
                                    if (!empty($initSuhuProdukE)) {
                                        $timelineRowsEdit[] = [
                                            'no'     => $noEdit++,
                                            'waktu'  => $initPukulEdit ? \Carbon\Carbon::parse($initPukulEdit)->format('H:i') : '-',
                                            'edited' => $pemeriksaanSuhuRuang->created_at ? $pemeriksaanSuhuRuang->created_at->format('d/m/Y H:i') : '-',
                                            'area'   => 'Suhu Produk',
                                            'setting'=> '-',
                                            'aktual' => $initSuhuProdukE,
                                            'display'=> '-',
                                            'tipe'   => 'awal',
                                        ];
                                    }

                                    foreach ($historiesEdit as $h) {
                                        $lamaSuhuE = is_array($h->suhu_data_lama) ? $h->suhu_data_lama : (json_decode($h->suhu_data_lama ?? '[]', true) ?: []);
                                        $baruSuhuE = is_array($h->suhu_data_baru) ? $h->suhu_data_baru : (json_decode($h->suhu_data_baru ?? '[]', true) ?: []);
                                        $editedAtE = $h->created_at ? $h->created_at->format('d/m/Y H:i') : '-';
                                        $pukulE = $h->pukul_baru ?? $h->pukul_lama ?? null;
                                        $jamE = $pukulE ? \Carbon\Carbon::parse($pukulE)->format('H:i') : '-';

                                        foreach ($sectionLabelsEdit as $secKey => $secLabel) {
                                            $lamaDataE = $lamaSuhuE[$secKey] ?? [];
                                            $baruDataE = $baruSuhuE[$secKey] ?? [];
                                            if (json_encode($lamaDataE) === json_encode($baruDataE)) continue;

                                            if (in_array($secKey, ['cold_storage', 'anteroom_loading'])) {
                                                $allItemsE = array_unique(array_merge(
                                                    array_map(fn($r) => $r['unit'] ?? '', (array) $lamaDataE),
                                                    array_map(fn($r) => $r['unit'] ?? '', (array) $baruDataE)
                                                ));
                                                foreach ($allItemsE as $unitId) {
                                                    $bItemE = collect((array) $baruDataE)->firstWhere('unit', $unitId) ?? [];
                                                    $lItemE = collect((array) $lamaDataE)->firstWhere('unit', $unitId) ?? [];
                                                    if (json_encode($lItemE) === json_encode($bItemE)) continue;
                                                    if (empty($bItemE['setting']) && empty($bItemE['display']) && empty($bItemE['actual'])) continue;
                                                    $timelineRowsEdit[] = [
                                                        'no'          => $noEdit++,
                                                        'waktu'       => $jamE,
                                                        'edited'      => $editedAtE,
                                                        'area'        => $secLabel . ' ' . $unitId,
                                                        'setting'     => $bItemE['setting'] ?? '-',
                                                        'aktual'      => $bItemE['actual'] ?? '-',
                                                        'display'     => $bItemE['display'] ?? '-',
                                                        'tipe'        => 'update',
                                                        'history_uuid'=> $h->uuid,
                                                        'field_type'  => 'suhu_data',
                                                        'section_key' => $secKey,
                                                        'unit_id'     => $unitId,
                                                        'pukul_raw'   => $pukulE ? \Carbon\Carbon::parse($pukulE)->format('H:i') : '',
                                                    ];
                                                }
                                            } else {
                                                if (empty($baruDataE['setting']) && empty($baruDataE['display']) && empty($baruDataE['actual'])) continue;
                                                $timelineRowsEdit[] = [
                                                    'no'          => $noEdit++,
                                                    'waktu'       => $jamE,
                                                    'edited'      => $editedAtE,
                                                    'area'        => $secLabel,
                                                    'setting'     => $baruDataE['setting'] ?? '-',
                                                    'aktual'      => $baruDataE['actual'] ?? '-',
                                                    'display'     => $baruDataE['display'] ?? '-',
                                                    'tipe'        => 'update',
                                                    'history_uuid'=> $h->uuid,
                                                    'field_type'  => 'suhu_data',
                                                    'section_key' => $secKey,
                                                    'unit_id'     => null,
                                                    'pukul_raw'   => $pukulE ? \Carbon\Carbon::parse($pukulE)->format('H:i') : '',
                                                ];
                                            }
                                        }

                                        // === Suhu Produk - Update ===
                                        if (($h->suhu_produk_lama ?? null) !== ($h->suhu_produk_baru ?? null) && !empty($h->suhu_produk_baru)) {
                                            $timelineRowsEdit[] = [
                                                'no'          => $noEdit++,
                                                'waktu'       => $jamE,
                                                'edited'      => $editedAtE,
                                                'area'        => 'Suhu Produk',
                                                'setting'     => '-',
                                                'aktual'      => $h->suhu_produk_baru,
                                                'display'     => '-',
                                                'tipe'        => 'update',
                                                'history_uuid'=> $h->uuid,
                                                'field_type'  => 'suhu_produk',
                                                'section_key' => null,
                                                'unit_id'     => null,
                                                'pukul_raw'   => $pukulE ? \Carbon\Carbon::parse($pukulE)->format('H:i') : '',
                                            ];
                                        }
                                    }
                                @endphp

                                @if(!empty($timelineRowsEdit))
                                <div class="row mt-4 mb-2">
                                    <div class="col-md-12">
                                        <h5 class="mb-3 d-flex align-items-center gap-2">
                                            <i class="bi bi-clock-history text-primary"></i>
                                            <strong>Riwayat Data Per Jam</strong>
                                            <span class="badge bg-primary ms-1">{{ count($timelineRowsEdit) }} entri</span>
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
                                                        <!-- <th class="text-center" style="width:13%">Diedit Pada</th> -->
                                                        <th style="width:20%">Area</th>
                                                        <th class="text-center">Setting (°C)</th>
                                                        <th class="text-center">Aktual (°C)</th>
                                                        <th class="text-center">Display (°C)</th>
                                                        <th class="text-center" style="width:8%">Status</th>
                                                        <th class="text-center" style="width:7%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($timelineRowsEdit as $tRow)
                                                    <tr class="{{ $tRow['tipe'] === 'update' ? 'table-warning' : '' }}">
                                                        <td class="text-center">{{ $tRow['no'] }}</td>
                                                        <td class="text-center fw-semibold">{{ $tRow['waktu'] }}</td>
                                                        <!-- <td class="text-center text-muted" style="font-size:0.8rem">{{ $tRow['edited'] }}</td> -->
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
                                                                <button type="button" class="btn btn-sm btn-primary btn-edit-riwayat"
                                                                    data-history-uuid="{{ $tRow['history_uuid'] }}"
                                                                    data-field-type="{{ $tRow['field_type'] }}"
                                                                    data-section-key="{{ $tRow['section_key'] ?? '' }}"
                                                                    data-unit-id="{{ $tRow['unit_id'] ?? '' }}"
                                                                    data-area="{{ $tRow['area'] }}"
                                                                    data-pukul="{{ $tRow['pukul_raw'] ?? $tRow['waktu'] }}"
                                                                    data-setting="{{ $tRow['setting'] }}"
                                                                    data-aktual="{{ $tRow['aktual'] }}"
                                                                    data-display="{{ $tRow['display'] }}"
                                                                    data-pemeriksaan-uuid="{{ $pemeriksaanSuhuRuang->uuid }}">
                                                                     Edit
                                                                </button>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Edit Riwayat --}}
                                <div class="modal fade" id="modalEditRiwayat" tabindex="-1" aria-labelledby="modalEditRiwayatLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="modalEditRiwayatLabel">
                                                    <i class="bi bi-pencil-square me-2"></i>Edit Riwayat Per Jam
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form id="formEditRiwayat" method="POST" action="">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="field_type" id="modal_field_type">
                                                <input type="hidden" name="section_key" id="modal_section_key">
                                                <input type="hidden" name="unit_id" id="modal_unit_id">
                                                <div class="modal-body">
                                                    <div class="alert alert-warning py-2 mb-3" style="font-size:0.85rem">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                                        Mengedit data: <strong id="modal_area_label"></strong>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Pukul</label>
                                                        <input type="time" name="pukul" id="modal_pukul" class="form-control form-control-sm">
                                                    </div>
                                                    <div id="modal_suhu_produk_section">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Suhu Produk</label>
                                                            <input type="text" name="suhu_produk" id="modal_suhu_produk" class="form-control form-control-sm" placeholder="Nilai suhu produk">
                                                        </div>
                                                    </div>
                                                    <div id="modal_suhu_data_section">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Setting (°C)</label>
                                                            <input type="text" name="setting" id="modal_setting" class="form-control form-control-sm" placeholder="-">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Aktual (°C)</label>
                                                            <input type="text" name="aktual" id="modal_aktual" class="form-control form-control-sm" placeholder="-">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Display (°C)</label>
                                                            <input type="text" name="display" id="modal_display" class="form-control form-control-sm" placeholder="-">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle toggle for manual input
    function setupManualToggle(prefix, count, isAnterooomLoading = false) {
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
                        } else {
                            manualEl.style.display = 'none';
                            manualEl.name = '';
                        }
                    });
                    
                    // Trigger on page load if manual was selected
                    if (selectEl.value === 'manual') {
                        manualEl.style.display = 'block';
                        manualEl.name = selectId;
                    }
                }
            });
        }
    }
    
    // Cold Storage toggle
    setupManualToggle('cold_storage', 4, false);
    
    // Anteroom Loading toggle
    setupManualToggle('anteroom_loading', 4, true);
    
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
    
    // Handle form submission for Anteroom Loading
    const form = document.getElementById('edit-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // For Anteroom Loading, ensure values are submitted correctly
            for (let i = 1; i <= 4; i++) {
                ['setting', 'display', 'actual'].forEach(field => {
                    const selectId = `anteroom_loading_${i}_${field}`;
                    const manualId = `anteroom_loading_${i}_${field}_manual`;
                    const selectEl = document.getElementById(selectId);
                    const manualEl = document.getElementById(manualId);
                    
                    if (selectEl && manualEl) {
                        // If select has value 0 (standard), make sure it's submitted
                        if (selectEl.value === '0') {
                            selectEl.name = selectId;
                        } else if (selectEl.value === 'manual') {
                            // If manual, submit the manual input value
                            selectEl.name = '';
                            manualEl.name = selectId;
                        }
                    }
                });
            }
            // Notifikasi akan otomatis refresh saat page load di halaman berikutnya
            // karena checkEditableRecords() dipanggil di DOMContentLoaded di navbar.blade.php
        });
    }

    // Handler for Edit Riwayat Per Jam Modal
    const editRiwayatButtons = document.querySelectorAll('.btn-edit-riwayat');
    const modalEditRiwayat = document.getElementById('modalEditRiwayat');
    if (editRiwayatButtons.length > 0 && modalEditRiwayat) {
        const bsModal = new bootstrap.Modal(modalEditRiwayat);
        const formEditRiwayat = document.getElementById('formEditRiwayat');
        const modalAreaLabel = document.getElementById('modal_area_label');
        const modalFieldType = document.getElementById('modal_field_type');
        const modalSectionKey = document.getElementById('modal_section_key');
        const modalUnitId = document.getElementById('modal_unit_id');
        const modalPukul = document.getElementById('modal_pukul');
        const modalSuhuProduk = document.getElementById('modal_suhu_produk');
        const modalSetting = document.getElementById('modal_setting');
        const modalAktual = document.getElementById('modal_aktual');
        const modalDisplay = document.getElementById('modal_display');
        const sectionSuhuProduk = document.getElementById('modal_suhu_produk_section');
        const sectionSuhuData = document.getElementById('modal_suhu_data_section');

        editRiwayatButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const historyUuid = this.dataset.historyUuid;
                const pemeriksaanUuid = this.dataset.pemeriksaanUuid;
                const fieldType = this.dataset.fieldType;
                const sectionKey = this.dataset.sectionKey;
                const unitId = this.dataset.unitId;
                const area = this.dataset.area;
                const pukul = this.dataset.pukul;
                const setting = this.dataset.setting;
                const aktual = this.dataset.aktual;
                const display = this.dataset.display;

                // Build Form Action URL
                formEditRiwayat.action = `/qc-sistem/pemeriksaan-suhu-ruang/${pemeriksaanUuid}/history/${historyUuid}`;

                modalAreaLabel.textContent = area;
                modalFieldType.value = fieldType;
                modalSectionKey.value = sectionKey;
                modalUnitId.value = unitId;
                modalPukul.value = pukul !== '-' ? pukul : '';

                if (fieldType === 'suhu_produk') {
                    sectionSuhuProduk.style.display = 'block';
                    sectionSuhuData.style.display = 'none';
                    modalSuhuProduk.value = aktual !== '-' ? aktual : '';
                } else {
                    sectionSuhuProduk.style.display = 'none';
                    sectionSuhuData.style.display = 'block';
                    modalSetting.value = setting !== '-' ? setting : '';
                    modalAktual.value = aktual !== '-' ? aktual : '';
                    modalDisplay.value = display !== '-' ? display : '';
                }

                bsModal.show();
            });
        });
    }
});
</script>

@endsection
