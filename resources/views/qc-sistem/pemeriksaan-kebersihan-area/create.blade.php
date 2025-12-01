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
                    <p class="text-subtitle text-muted">Buat pemeriksaan kebersihan area baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kebersihan-area.index') }}">Pemeriksaan</a></li>
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

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-kebersihan-area.store') }}" method="POST">
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
                                                <label for="id_master_form">Master Form <span class="text-danger">*</span></label>
                                                <select id="id_master_form" class="form-control @error('id_master_form') is-invalid @enderror"
                                                    name="id_master_form" required>
                                                    <option value="">-- Pilih Master Form --</option>
                                                    @foreach($masterForms as $form)
                                                        <option value="{{ $form->id }}" {{ old('id_master_form') == $form->id ? 'selected' : '' }}>
                                                            {{ $form->nama_form }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_master_form')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="jam_sebelum_proses">Jam Sebelum Proses</label>
                                                <input type="time" id="jam_sebelum_proses" class="form-control @error('jam_sebelum_proses') is-invalid @enderror"
                                                    name="jam_sebelum_proses" value="{{ old('jam_sebelum_proses') }}">
                                                @error('jam_sebelum_proses')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="jam_saat_proses">Jam Saat Proses</label>
                                                <input type="time" id="jam_saat_proses" class="form-control @error('jam_saat_proses') is-invalid @enderror"
                                                    name="jam_saat_proses" value="{{ old('jam_saat_proses') }}">
                                                @error('jam_saat_proses')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <!-- Preview Form Fields -->
                                            <div id="preview-container" class="col-md-12 mt-4" style="display: none;">
                                                <h5 class="mb-3"><strong>Preview Form Fields</strong></h5>
                                                <div id="fields-list" class="border rounded p-3"></div>
                                            </div>
                                            
                                            <!-- Editable Form Fields -->
                                            <div id="form-fields-container" class="col-md-12 mt-4" style="display: none;">
                                                <h5 class="mb-3"><strong>Form Fields</strong></h5>
                                                <div id="editable-fields" class="border rounded p-3"></div>
                                            </div>
                                            
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Buat Pemeriksaan</button>
                                                <a href="{{ route('pemeriksaan-kebersihan-area.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
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
    // Data master forms dengan fields
    const masterFormsData = {
        @foreach($masterForms as $form)
            {{ $form->id }}: {
                nama: '{{ $form->nama_form }}',
                fields: [
                    @foreach($form->fields as $field)
                        { id: {{ $field->id }}, nama: '{{ $field->field_name }}' },
                    @endforeach
                ]
            },
        @endforeach
    };

    // Event listener untuk perubahan master form
    document.getElementById('id_master_form').addEventListener('change', function() {
        const formId = this.value;
        const fieldsContainer = document.getElementById('form-fields-container');
        const editableFields = document.getElementById('editable-fields');

        if (formId && masterFormsData[formId]) {
            const formData = masterFormsData[formId];
            const fields = formData.fields;

            // Buat HTML untuk editable fields
            let html = '';
            if (fields.length > 0) {
                fields.forEach((field, index) => {
                    html += `
                        <div class="mb-3 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label"><strong>${index + 1}. ${field.nama}</strong></label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="field_status_${field.id}" 
                                            id="field_yes_${field.id}" value="1">
                                        <label class="form-check-label" for="field_yes_${field.id}">
                                            ✓ Baik
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="field_status_${field.id}" 
                                            id="field_no_${field.id}" value="0">
                                        <label class="form-check-label" for="field_no_${field.id}">
                                            ✗ Tidak Baik
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label class="form-label">Keterangan</label>
                                    <textarea class="form-control form-control-sm" 
                                        name="field_keterangan_${field.id}" placeholder="Keterangan" rows="3"></textarea>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label class="form-label">Tindakan Koreksi</label>
                                    <textarea class="form-control form-control-sm" 
                                        name="field_tindakan_${field.id}" placeholder="Tindakan Koreksi" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                html = '<p class="text-muted">Tidak ada field dalam form ini</p>';
            }

            editableFields.innerHTML = html;
            fieldsContainer.style.display = 'block';
        } else {
            fieldsContainer.style.display = 'none';
            editableFields.innerHTML = '';
        }
    });

    // Trigger preview jika ada nilai old
    window.addEventListener('load', function() {
        const formSelect = document.getElementById('id_master_form');
        if (formSelect.value) {
            formSelect.dispatchEvent(new Event('change'));
        }
    });
</script>

@endsection