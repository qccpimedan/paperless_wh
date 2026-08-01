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

                                <form id="form-pemeriksaan-kebersihan-area" data-autosave="true" class="form form-horizontal" action="{{ route('pemeriksaan-kebersihan-area.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <!-- Global Fields -->
                                        <div class="row mb-4 p-3 bg-light rounded">
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
                                                <select id="id_shift" class="form-select @error('id_shift') is-invalid @enderror"
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
                                        </div>

                                        <!-- Repeater Container -->
                                        <div id="repeater-container">
                                            <!-- Item Pertama (Default) -->
                                            <div class="repeater-item border p-4 mb-4 rounded position-relative shadow-sm bg-white" data-index="0">
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 btn-remove-item" style="display: none;">
                                                    <i class="bi bi-trash"></i> Hapus Area
                                                </button>
                                                <div class="row">
                                                    <div class="col-md-6 mt-3">
                                                        <label>Area <span class="text-danger">*</span></label>
                                                        <select class="form-select area-select" name="items[0][id_area]" required>
                                                            <option value="">-- Pilih Area --</option>
                                                            @foreach($areas as $area)
                                                                <option value="{{ $area->id }}">{{ $area->nama_area }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mt-3">
                                                        <label>Master Form <span class="text-danger">*</span></label>
                                                        <select class="form-select master-form-select" name="items[0][id_master_form]" required>
                                                            <option value="">-- Pilih Master Form --</option>
                                                            @foreach($masterForms as $form)
                                                                <option value="{{ $form->id }}">{{ $form->nama_form }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mt-3">
                                                        <label>Jam Sebelum Proses</label>
                                                        <input type="time" class="form-control" name="items[0][jam_sebelum_proses]">
                                                    </div>

                                                    <div class="col-md-6 mt-3">
                                                        <label>Jam Saat Proses</label>
                                                        <input type="time" class="form-control" name="items[0][jam_saat_proses]">
                                                    </div>
                                                </div>

                                                <!-- Container untuk Render Aspek/Fields per Form -->
                                                <div class="form-fields-container mt-4" style="display: none;">
                                                    <h5 class="mb-3 text-primary"><strong><i class="bi bi-list-check"></i> Aspek Yang Dinilai</strong></h5>
                                                    <div class="editable-fields"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tombol Tambah Form -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary btn-sm" id="btn-add-item" style="border-style: dashed; border-width: 2px;">
                                                    Tambah Area
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-12 d-flex justify-content-end mt-3 border-top pt-3">
                                            <button type="submit" class="btn btn-primary me-2">Buat Semua Pemeriksaan</button>
                                            <a href="{{ route('pemeriksaan-kebersihan-area.index') }}" class="btn btn-light-secondary btn-kembali-confirm">Kembali</a>
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
    // Konfirmasi Kembali
    document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
        el.addEventListener('click', function(e) {
            const ok = confirm('Data belum disimpan. Yakin ingin kembali ke halaman index?');
            if (!ok) e.preventDefault();
        });
    });

    // Data Master Forms & Fields
    const masterFormsData = {
        @foreach($masterForms as $form)
            {{ $form->id }}: {
                nama: `{!! addslashes($form->nama_form) !!}`,
                fields: [
                    @foreach($form->fields as $field)
                        { id: {{ $field->id }}, nama: `{!! addslashes($field->field_name) !!}` },
                    @endforeach
                ]
            },
        @endforeach
    };

    let itemIndex = 1; // Mulai dari 1 (index ke-1 karena defaultnya index 0)
    
    // Logic untuk menambahkan Repeater Item
    document.getElementById('btn-add-item').addEventListener('click', function() {
        const container = document.getElementById('repeater-container');
        const defaultItem = container.querySelector('.repeater-item'); // Selalu clone dari yang pertama
        const newItem = defaultItem.cloneNode(true);
        
        // Update index
        newItem.setAttribute('data-index', itemIndex);
        
        // Bersihkan area input, atur ulang name array
        newItem.querySelectorAll('input, select, textarea').forEach(el => {
            if (el.name) {
                // Ubah items[0][id_area] -> items[1][id_area]
                el.name = el.name.replace(/items\[\d+\]/, `items[${itemIndex}]`);
            }
            if (el.type === 'radio' || el.type === 'checkbox') {
                el.checked = false;
            } else if (el.type !== 'button') {
                el.value = '';
            }
        });
        
        // Hapus child fields dan tutup container spesifik dari row yg dikloning
        const editableFields = newItem.querySelector('.editable-fields');
        editableFields.innerHTML = '';
        newItem.querySelector('.form-fields-container').style.display = 'none';

        container.appendChild(newItem);
        itemIndex++;
        updateRemoveButtons();
    });

    // Logic Hapus Item Menggunakan Event Delegation
    document.getElementById('repeater-container').addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-item')) {
            const itemToRemove = e.target.closest('.repeater-item');
            if (document.querySelectorAll('.repeater-item').length > 1) {
                itemToRemove.remove();
                updateRemoveButtons();
            }
        }
    });

    // Fungsi mengatur kapan tombol hapus boleh muncul (minimal sisa 1)
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.repeater-item');
        items.forEach(item => {
            const btn = item.querySelector('.btn-remove-item');
            btn.style.display = items.length === 1 ? 'none' : 'block';
        });
    }

    // Logic Men-generate Form Fields Jika Pilih Master Form (Event Delegation)
    document.getElementById('repeater-container').addEventListener('change', function(e) {
        if (e.target.classList.contains('master-form-select')) {
            const select = e.target;
            const formId = select.value;
            const parentItem = select.closest('.repeater-item');
            const currentIndex = parentItem.getAttribute('data-index');
            
            const fieldsContainer = parentItem.querySelector('.form-fields-container');
            const editableFields = parentItem.querySelector('.editable-fields');

            if (formId && masterFormsData[formId]) {
                const formData = masterFormsData[formId];
                const fields = formData.fields;

                let html = '';
                if (fields.length > 0) {
                    fields.forEach((field, fIdx) => {
                        html += `
                            <div class="mb-3 p-3 border rounded bg-light">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label text-dark"><strong>${fIdx + 1}. ${field.nama}</strong></label>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label text-muted small mb-1">Sebelum Proses</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                    name="items[${currentIndex}][field_status_sebelum_${field.id}]" 
                                                    id="field_${currentIndex}_sebelum_ok_${field.id}" value="1" required>
                                                <label class="form-check-label" for="field_${currentIndex}_sebelum_ok_${field.id}">✓ Ok</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                    name="items[${currentIndex}][field_status_sebelum_${field.id}]" 
                                                    id="field_${currentIndex}_sebelum_no_${field.id}" value="0" required>
                                                <label class="form-check-label" for="field_${currentIndex}_sebelum_no_${field.id}">✗ Tidak Ok</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label text-muted small mb-1">Saat Proses</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                    name="items[${currentIndex}][field_status_saat_${field.id}]" 
                                                    id="field_${currentIndex}_saat_ok_${field.id}" value="1">
                                                <label class="form-check-label" for="field_${currentIndex}_saat_ok_${field.id}">✓ Ok</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                    name="items[${currentIndex}][field_status_saat_${field.id}]" 
                                                    id="field_${currentIndex}_saat_no_${field.id}" value="0">
                                                <label class="form-check-label" for="field_${currentIndex}_saat_no_${field.id}">✗ Tidak Ok</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label text-muted small">Keterangan</label>
                                        <textarea class="form-control form-control-sm" name="items[${currentIndex}][field_keterangan_${field.id}]" placeholder="Keterangan..." rows="2"></textarea>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label text-muted small">Tindakan Koreksi</label>
                                        <textarea class="form-control form-control-sm" name="items[${currentIndex}][field_tindakan_${field.id}]" placeholder="Tindakan koreksi jika tidak Ok..." rows="2"></textarea>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label text-muted small">Verifikasi <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" name="items[${currentIndex}][field_verifikasi_${field.id}]" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="1">OK</option>
                                            <option value="0">Tidak OK</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<p class="text-danger"><i class="bi bi-exclamation-triangle"></i> Form ini belum memiliki item pemeriksaan, silakan atur di Master Form.</p>';
                }

                editableFields.innerHTML = html;
                fieldsContainer.style.display = 'block';
            } else {
                fieldsContainer.style.display = 'none';
                editableFields.innerHTML = '';
            }
        }
    });
});
</script>

@endsection