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
                    <h3>Pemeriksaan Suhu Ruang</h3>
                    <p class="text-subtitle text-muted">Buat pemeriksaan suhu ruang baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang.index') }}">Pemeriksaan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Buat Pemeriksaan</li>
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
                            <h4 class="card-title">Form Pemeriksaan Suhu Ruang</h4>
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

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-suhu-ruang.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                    name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="id_shift">Shift <span class="text-danger">*</span></label>
                                                <select id="id_shift" class="form-control @error('id_shift') is-invalid @enderror"
                                                    name="id_shift" required>
                                                    <option value="">-- Pilih Shift --</option>
                                                    @foreach($shifts as $shift)
                                                        <option value="{{ $shift->id }}" {{ old('id_shift') == $shift->id ? 'selected' : '' }}>
                                                            {{ $shift->shift }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_shift')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="id_area">Area <span class="text-danger">*</span></label>
                                                <select id="id_area" class="form-control @error('id_area') is-invalid @enderror"
                                                    name="id_area" required>
                                                    <option value="">-- Pilih Area --</option>
                                                    @foreach($areas as $area)
                                                        <option value="{{ $area->id }}" {{ old('id_area') == $area->id ? 'selected' : '' }}>
                                                            {{ $area->nama_area }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_area')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="id_produk">Produk <span class="text-danger">*</span></label>
                                                <select id="id_produk" class="form-control @error('id_produk') is-invalid @enderror"
                                                    name="id_produk" required>
                                                    <option value="">-- Pilih Produk --</option>
                                                    @foreach($produks as $produk)
                                                        <option value="{{ $produk->id }}" {{ old('id_produk') == $produk->id ? 'selected' : '' }}>
                                                            {{ $produk->nama_bahan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_produk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Cold Storage Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Cold Storage (1-4) <small>(Isi sesuai dengan unit yang digunakan)</small></strong></h5>
                                            </div>
                                            <!-- ✅ CHECKBOX SECTION -->
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    @for($i = 1; $i <= 4; $i++)
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input cold-storage-checkbox" type="checkbox" id="cold_storage_{{ $i }}_check" data-unit="{{ $i }}">
                                                                <label class="form-check-label" for="cold_storage_{{ $i }}_check">
                                                                    Unit {{ $i }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                            @for($i = 1; $i <= 4; $i++)
                                                <div class="col-md-12 mt-3 p-3 border rounded bg-light cold-storage-unit" id="cold_storage_{{ $i }}_form" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label"><strong>Unit {{ $i }}</strong></label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="cold_storage_{{ $i }}_setting">Setting (°C)</label>
                                                            <select id="cold_storage_{{ $i }}_setting" class="form-select form-select-sm"
                                                                name="cold_storage_{{ $i }}_setting">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="-18" {{ old('cold_storage_' . $i . '_setting') == '-18' ? 'selected' : '' }}>Std ≤ (-18)°C</option>
                                                                <option value="manual" {{ old('cold_storage_' . $i . '_setting') && old('cold_storage_' . $i . '_setting') != '-18' ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="cold_storage_{{ $i }}_setting_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('cold_storage_' . $i . '_setting') && old('cold_storage_' . $i . '_setting') != '-18' ? old('cold_storage_' . $i . '_setting') : '' }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="cold_storage_{{ $i }}_display">Display (°C)</label>
                                                            <select id="cold_storage_{{ $i }}_display" class="form-select form-select-sm"
                                                                name="cold_storage_{{ $i }}_display">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="-18" {{ old('cold_storage_' . $i . '_display') == '-18' ? 'selected' : '' }}>Std ≤ (-18)°C</option>
                                                                <option value="manual" {{ old('cold_storage_' . $i . '_display') && old('cold_storage_' . $i . '_display') != '-18' ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="cold_storage_{{ $i }}_display_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('cold_storage_' . $i . '_display') && old('cold_storage_' . $i . '_display') != '-18' ? old('cold_storage_' . $i . '_display') : '' }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="cold_storage_{{ $i }}_actual">Actual (°C)</label>
                                                            <select id="cold_storage_{{ $i }}_actual" class="form-select form-select-sm"
                                                                name="cold_storage_{{ $i }}_actual">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="-18" {{ old('cold_storage_' . $i . '_actual') == '-18' ? 'selected' : '' }}>Std ≤ (-18)°C</option>
                                                                <option value="manual" {{ old('cold_storage_' . $i . '_actual') && old('cold_storage_' . $i . '_actual') != '-18' ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="cold_storage_{{ $i }}_actual_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('cold_storage_' . $i . '_actual') && old('cold_storage_' . $i . '_actual') != '-18' ? old('cold_storage_' . $i . '_actual') : '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor

                                            <!-- Anteroom Loading Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Anteroom Loading (1-4) <small>(Isi sesuai dengan unit yang digunakan)</small></strong></h5>
                                            </div>
                                            <!-- ✅ CHECKBOX SECTION -->
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    @for($i = 1; $i <= 4; $i++)
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input anteroom-loading-checkbox" type="checkbox" id="anteroom_loading_{{ $i }}_check" data-unit="{{ $i }}">
                                                                <label class="form-check-label" for="anteroom_loading_{{ $i }}_check">
                                                                    Unit {{ $i }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                            @for($i = 1; $i <= 4; $i++)
                                                <div class="col-md-12 mt-3 p-3 border rounded bg-light anteroom-loading-unit" id="anteroom_loading_{{ $i }}_form" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label"><strong>Unit {{ $i }}</strong></label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="anteroom_loading_{{ $i }}_setting">Setting (°C)</label>
                                                            <select id="anteroom_loading_{{ $i }}_setting" class="form-select form-select-sm"
                                                                name="anteroom_loading_{{ $i }}_setting">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="(0±5°C)" {{ old('anteroom_loading_' . $i . '_setting') == '(0±5°C)' ? 'selected' : '' }}>Std 0 ± 5°C</option>
                                                                <option value="manual" {{ old('anteroom_loading_' . $i . '_setting') && old('anteroom_loading_' . $i . '_setting') != '(0±5°C)' && old('anteroom_loading_' . $i . '_setting') ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="anteroom_loading_{{ $i }}_setting_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('anteroom_loading_' . $i . '_setting') && old('anteroom_loading_' . $i . '_setting') != '0' ? old('anteroom_loading_' . $i . '_setting') : '' }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="anteroom_loading_{{ $i }}_display">Display (°C)</label>
                                                            <select id="anteroom_loading_{{ $i }}_display" class="form-select form-select-sm"
                                                                name="anteroom_loading_{{ $i }}_display">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="(0±5°C)" {{ old('anteroom_loading_' . $i . '_display') == '(0±5°C)' ? 'selected' : '' }}>Std 0 ± 5°C</option>
                                                                <option value="manual" {{ old('anteroom_loading_' . $i . '_display') && old('anteroom_loading_' . $i . '_display') != '(0±5°C)' && old('anteroom_loading_' . $i . '_display') ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="anteroom_loading_{{ $i }}_display_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('anteroom_loading_' . $i . '_display') && old('anteroom_loading_' . $i . '_display') != '0' ? old('anteroom_loading_' . $i . '_display') : '' }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="anteroom_loading_{{ $i }}_actual">Actual (°C)</label>
                                                            <select id="anteroom_loading_{{ $i }}_actual" class="form-select form-select-sm"
                                                                name="anteroom_loading_{{ $i }}_actual">
                                                                <option value="">-- Pilih atau Isi Manual --</option>
                                                                <option value="(0±5°C)" {{ old('anteroom_loading_' . $i . '_actual') == '(0±5°C)' ? 'selected' : '' }}>Std 0 ± 5°C</option>
                                                                <option value="manual" {{ old('anteroom_loading_' . $i . '_actual') && old('anteroom_loading_' . $i . '_actual') != '(0±5°C)' && old('anteroom_loading_' . $i . '_actual') ? 'selected' : '' }}>Input Manual</option>
                                                            </select>
                                                            <input type="number" step="0.1" id="anteroom_loading_{{ $i }}_actual_manual" 
                                                                class="form-control form-control-sm mt-2" style="display: none;"
                                                                placeholder="Masukkan nilai"
                                                                value="{{ old('anteroom_loading_' . $i . '_actual') && old('anteroom_loading_' . $i . '_actual') != '0' ? old('anteroom_loading_' . $i . '_actual') : '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor

                                            <!-- Pre Loading Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Pre Loading <small>(Opsional)</small></strong></h5>
                                            </div>
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="pre_loading_setting">Setting (°C)</label>
                                                        <input type="number" step="0.1" id="pre_loading_setting" 
                                                            class="form-control form-control-sm"
                                                            name="pre_loading_setting"
                                                            placeholder="Setting"
                                                            value="{{ old('pre_loading_setting') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="pre_loading_display">Display (°C)</label>
                                                        <input type="number" step="0.1" id="pre_loading_display" 
                                                            class="form-control form-control-sm"
                                                            name="pre_loading_display"
                                                            placeholder="Display"
                                                            value="{{ old('pre_loading_display') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="pre_loading_actual">Actual (°C)</label>
                                                        <input type="number" step="0.1" id="pre_loading_actual" 
                                                            class="form-control form-control-sm"
                                                            name="pre_loading_actual"
                                                            placeholder="Actual"
                                                            value="{{ old('pre_loading_actual') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Prestaging Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Prestaging <small>(Opsional)</small></strong></h5>
                                            </div>
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="prestaging_setting">Setting (°C)</label>
                                                        <input type="number" step="0.1" id="prestaging_setting" 
                                                            class="form-control form-control-sm"
                                                            name="prestaging_setting"
                                                            placeholder="Setting"
                                                            value="{{ old('prestaging_setting') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="prestaging_display">Display (°C)</label>
                                                        <input type="number" step="0.1" id="prestaging_display" 
                                                            class="form-control form-control-sm"
                                                            name="prestaging_display"
                                                            placeholder="Display"
                                                            value="{{ old('prestaging_display') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="prestaging_actual">Actual (°C)</label>
                                                        <input type="number" step="0.1" id="prestaging_actual" 
                                                            class="form-control form-control-sm"
                                                            name="prestaging_actual"
                                                            placeholder="Actual"
                                                            value="{{ old('prestaging_actual') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Anteroom Ekspansi Further Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Anteroom Ekspansi Further <small>(Opsional)</small></strong></h5>
                                            </div>
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_further_setting">Setting (°C)</label>
                                                        <input type="number" step="0.1" id="anteroom_ekspansi_further_setting" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_further_setting"
                                                            placeholder="Setting"
                                                            value="{{ old('anteroom_ekspansi_further_setting') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_further_display">Display (°C)</label>
                                                        <input type="number" step="0.1" id="anteroom_ekspansi_further_display" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_further_display"
                                                            placeholder="Display"
                                                            value="{{ old('anteroom_ekspansi_further_display') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_further_actual">Actual (°C)</label>
                                                        <input type="number" step="0.1" id="anteroom_ekspansi_further_actual" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_further_actual"
                                                            placeholder="Actual"
                                                            value="{{ old('anteroom_ekspansi_further_actual') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Anteroom Ekspansi Sausage Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Anteroom Ekspansi Sausage <small>(Opsional)</small></strong></h5>
                                            </div>
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_sausage_setting">Setting (°C)</label>
                                                        <input type="number" step="0.1" id="anteroom_ekspansi_sausage_setting" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_sausage_setting"
                                                            placeholder="Setting"
                                                            value="{{ old('anteroom_ekspansi_sausage_setting') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_sausage_display">Display (°C)</label>
                                                        <input type="number" step="0.1" id="anteroom_ekspansi_sausage_display" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_sausage_display"
                                                            placeholder="Display"
                                                            value="{{ old('anteroom_ekspansi_sausage_display') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="anteroom_ekspansi_sausage_actual">Actual (°C)</label>
                                                        <input type="number" step="0.1" id="anteroom_ekspansi_sausage_actual" 
                                                            class="form-control form-control-sm"
                                                            name="anteroom_ekspansi_sausage_actual"
                                                            placeholder="Actual"
                                                            value="{{ old('anteroom_ekspansi_sausage_actual') }}">
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
                                                    name="keterangan" placeholder="Keterangan" rows="3">{{ old('keterangan') }}</textarea>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="tindakan_koreksi">Tindakan Koreksi</label>
                                                <textarea id="tindakan_koreksi" class="form-control form-control-sm"
                                                    name="tindakan_koreksi" placeholder="Tindakan Koreksi" rows="3">{{ old('tindakan_koreksi') }}</textarea>
                                            </div>

                                            <div class="col-md-12 d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Buat Pemeriksaan</button>
                                                <a href="{{ route('pemeriksaan-suhu-ruang.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
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
    const form = document.querySelector('form');
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
        });
    }
});
</script>
@endsection
