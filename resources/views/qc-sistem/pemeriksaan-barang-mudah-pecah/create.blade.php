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
                    <h3>Pemeriksaan Barang Mudah Pecah</h3>
                    <p class="text-subtitle text-muted">Tambah Pemeriksaan Baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-barang-mudah-pecah.index') }}">Pemeriksaan Barang Mudah Pecah</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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
                            <h4 class="card-title">Form Pemeriksaan Barang Mudah Pecah</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <form action="{{ route('pemeriksaan-barang-mudah-pecah.store') }}" method="POST" novalidate>
                                    @csrf

                                    <!-- Informasi Dasar -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="id_shift" class="form-label">Shift <span class="text-danger">*</span></label>
                                            <select class="form-select @error('id_shift') is-invalid @enderror" name="id_shift" id="id_shift" required>
                                                <option value="">-- Pilih Shift --</option>
                                                @foreach($shifts as $shift)
                                                    <option value="{{ $shift->id }}" {{ old('id_shift') == $shift->id ? 'selected' : '' }}>
                                                        {{ $shift->shift }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_shift')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                            @error('tanggal')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Area -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="id_area" class="form-label">Area <span class="text-danger">*</span></label>
                                            <select class="form-select @error('id_area') is-invalid @enderror" name="id_area" id="id_area" required>
                                                <option value="">-- Pilih Area --</option>
                                                @foreach($areas as $area)
                                                    <option value="{{ $area->id }}" {{ old('id_area') == $area->id ? 'selected' : '' }}>
                                                        {{ $area->nama_area }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_area')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Detail Barang (Dynamic Rows) -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <h5 class="mb-3">Detail Barang</h5>
                                            <div id="barangContainer">
                                                <div class="barang-row card p-3 mb-3">
                                                    <div class="row mb-2">
                                                        <div class="col-md-12">
                                                            <h6><strong>Barang #<span class="barang-number">1</span></strong></h6>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <div class="form-check">
                                                                <input class="form-check-input input-manual-toggle" type="checkbox" id="manual_0" data-index="0">
                                                                <label class="form-check-label" for="manual_0">
                                                                    Input Manual Barang
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Pilih dari Database -->
                                                    <div class="row mb-3 barang-select-row" data-index="0">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Barang <span class="text-danger">*</span></label>
                                                            <select class="choices form-select barang-select" name="details[0][id_barang]" required>
                                                                <option value="">-- Pilih Barang --</option>
                                                                @foreach($barangs as $barang)
                                                                    <option value="{{ $barang->id }}" data-jumlah="{{ $barang->jumlah_barang ?? 0 }}">
                                                                        {{ $barang->nama_barang }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Jumlah</label>
                                                            <input type="text" class="form-control jumlah-barang" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Lokasi Area <span class="text-danger">*</span></label>
                                                            <select class="form-select lokasi-area-select lokasi-area-select-db" name="details[0][id_input_area_locations]" required>
                                                                <option value="">-- Pilih Lokasi Area --</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="button" class="btn btn-danger btn-sm mt-4 remove-barang" style="display: none;">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Input Manual -->
                                                    <div class="row mb-3 barang-manual-row" data-index="0" style="display: none;">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control nama-barang-manual" name="details[0][nama_barang_manual]" placeholder="Masukkan nama barang">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control jumlah-manual" name="details[0][jumlah_manual]" placeholder="Jumlah" min="0">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Lokasi Area <span class="text-danger">*</span></label>
                                                            <select class="form-select lokasi-area-select lokasi-area-select-manual" name="details[0][id_input_area_locations_disabled]">
                                                                <option value="">-- Pilih Lokasi Area --</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="button" class="btn btn-danger btn-sm mt-4 remove-barang" style="display: none;">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Nama Karyawan (Opsional) -->
                                                    <div class="row mb-3 nama_karyawan_row" data-index="0" style="display: none;">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Nama Karyawan</label>
                                                            <input type="text" class="form-control" name="details[0][nama_karyawan]" placeholder="Nama karyawan (opsional)">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Awal</label>
                                                            <div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="details[0][awal]" id="awal_baik_0" value="baik">
                                                                    <label class="form-check-label" for="awal_baik_0"> baik</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="details[0][awal]" id="awal_tidak-baik_0" value="tidak-baik">
                                                                    <label class="form-check-label" for="awal_tidak-baik_0"> tidak-baik</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Akhir</label>
                                                            <div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="details[0][akhir]" id="akhir_baik_0" value="baik">
                                                                    <label class="form-check-label" for="akhir_baik_0"> baik</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="details[0][akhir]" id="akhir_tidak-baik_0" value="tidak-baik">
                                                                    <label class="form-check-label" for="akhir_tidak-baik_0"> tidak-baik</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Temuan Ketidaksesuaian</label>
                                                            <textarea class="form-control" name="details[0][temuan_ketidaksesuaian]" rows="3"></textarea>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Tindakan Koreksi</label>
                                                            <textarea class="form-control" name="details[0][tindakan_koreksi]" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-success" id="addBarang">
                                                <i class="bi bi-plus-circle"></i> Tambah Barang
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">
                                                Simpan Pemeriksaan
                                            </button>
                                            <a href="{{ route('pemeriksaan-barang-mudah-pecah.index') }}" class="btn btn-light-secondary mb-1">
                                                Batal
                                            </a>
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
    let barangIndex = 1;
    const idAreaSelect = document.getElementById('id_area');
    
    // Load lokasi area
    function loadAreaLocations() {
        if (!idAreaSelect || !idAreaSelect.value) return;
        
        fetch(`/qc-sistem/api/area-locations/${idAreaSelect.value}`)
            .then(r => r.ok ? r.json() : Promise.reject(`HTTP ${r.status}`))
            .then(data => {
                document.querySelectorAll('.lokasi-area-select-db, .lokasi-area-select-manual').forEach(select => {
                    const currentValue = select.value;
                    select.innerHTML = '<option value="">-- Pilih Lokasi Area --</option>';
                    data.forEach(location => {
                        const option = document.createElement('option');
                        option.value = location.id;
                        option.textContent = location.lokasi_area;
                        select.appendChild(option);
                    });
                    if (currentValue) select.value = currentValue;
                });
            })
            .catch(err => console.error('Error loading locations:', err));
    }
    
    if (idAreaSelect) {
        idAreaSelect.addEventListener('change', loadAreaLocations);
        loadAreaLocations();
    }
    
    // Toggle manual - SOLUSI: UBAH NAME ATTRIBUTE
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('input-manual-toggle')) {
            const idx = e.target.getAttribute('data-index');
            const selectRow = document.querySelector(`.barang-select-row[data-index="${idx}"]`);
            const manualRow = document.querySelector(`.barang-manual-row[data-index="${idx}"]`);
            const karyawanRow = document.querySelector(`.nama_karyawan_row[data-index="${idx}"]`);
            
            const barangSelect = selectRow.querySelector('select[name*="[id_barang]"]');
            const lokasiDbSelect = selectRow.querySelector('.lokasi-area-select-db');
            const lokasiManualSelect = manualRow.querySelector('.lokasi-area-select-manual');
            const namaBarangManual = manualRow.querySelector('input[name*="[nama_barang_manual]"]');
            const jumlahManual = manualRow.querySelector('input[name*="[jumlah_manual]"]');
            
            if (e.target.checked) {
                // === MODE MANUAL ===
                selectRow.style.display = 'none';
                manualRow.style.display = 'flex';
                if (karyawanRow) karyawanRow.style.display = 'flex';
                
                // Disable database fields dan UBAH NAME (tambah _disabled)
                barangSelect.value = '';
                barangSelect.required = false;
                barangSelect.name = barangSelect.name.replace('[id_barang]', '[id_barang_disabled]');
                
                lokasiDbSelect.value = '';
                lokasiDbSelect.required = false;
                lokasiDbSelect.name = lokasiDbSelect.name.replace('[id_input_area_locations]', '[id_input_area_locations_disabled]');
                
                // Enable manual fields dan PASTIKAN NAME BENAR
                namaBarangManual.required = true;
                jumlahManual.required = true;
                lokasiManualSelect.required = true;
                // Pastikan name manual select benar
                if (!lokasiManualSelect.name.includes('[id_input_area_locations]') || 
                    lokasiManualSelect.name.includes('_disabled')) {
                    lokasiManualSelect.name = `details[${idx}][id_input_area_locations]`;
                }
                
                console.log(`Mode MANUAL - Lokasi Manual name: ${lokasiManualSelect.name}`);
                
            } else {
                // === MODE DATABASE ===
                selectRow.style.display = 'flex';
                manualRow.style.display = 'none';
                if (karyawanRow) karyawanRow.style.display = 'none';
                
                // Disable manual fields dan UBAH NAME (tambah _disabled)
                namaBarangManual.value = '';
                namaBarangManual.required = false;
                jumlahManual.value = '';
                jumlahManual.required = false;
                
                lokasiManualSelect.value = '';
                lokasiManualSelect.required = false;
                lokasiManualSelect.name = lokasiManualSelect.name.replace('[id_input_area_locations]', '[id_input_area_locations_disabled]');
                
                // Enable database fields dan PASTIKAN NAME BENAR
                barangSelect.required = true;
                barangSelect.name = barangSelect.name.replace('[id_barang_disabled]', '[id_barang]');
                
                lokasiDbSelect.required = true;
                // Pastikan name DB select benar
                if (!lokasiDbSelect.name.includes('[id_input_area_locations]') || 
                    lokasiDbSelect.name.includes('_disabled')) {
                    lokasiDbSelect.name = `details[${idx}][id_input_area_locations]`;
                }
                
                console.log(`Mode DATABASE - Lokasi DB name: ${lokasiDbSelect.name}`);
            }
        }
    });
    
    // Load barang quantity
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('barang-select') && e.target.value) {
            const row = e.target.closest('.barang-row');
            fetch(`/qc-sistem/api/barang-details/${e.target.value}`)
                .then(r => r.ok ? r.json() : Promise.reject(`HTTP ${r.status}`))
                .then(data => row.querySelector('.jumlah-barang').value = data.jumlah_barang || 0)
                .catch(err => console.error('Error loading barang:', err));
        }
    });
    
    // Initialize Choices
    document.querySelectorAll('select.choices:not(.choices-initialized)').forEach(select => {
        new Choices(select, { removeItemButton: true, placeholder: true, searchEnabled: true });
        select.classList.add('choices-initialized');
    });
    
    // Add barang
    document.getElementById('addBarang').addEventListener('click', function(e) {
        e.preventDefault();
        const idx = barangIndex;
        const html = `
            <div class="barang-row card p-3 mb-3">
                <div class="row mb-2"><div class="col-md-12"><h6><strong>Barang #<span class="barang-number">${idx + 1}</span></strong></h6></div></div>
                <div class="row mb-3"><div class="col-md-12"><div class="form-check"><input class="form-check-input input-manual-toggle" type="checkbox" id="manual_${idx}" data-index="${idx}"><label class="form-check-label" for="manual_${idx}">Input Manual Barang</label></div></div></div>
                <div class="row mb-3 barang-select-row" data-index="${idx}">
                    <div class="col-md-3"><label class="form-label">Barang <span class="text-danger">*</span></label><select class="choices form-select barang-select" name="details[${idx}][id_barang]" required><option value="">-- Pilih Barang --</option>@foreach($barangs as $b)<option value="{{ $b->id }}">{{ $b->nama_barang }}</option>@endforeach</select></div>
                    <div class="col-md-2"><label class="form-label">Jumlah</label><input type="text" class="form-control jumlah-barang" readonly></div>
                    <div class="col-md-4"><label class="form-label">Lokasi Area <span class="text-danger">*</span></label><select class="form-select lokasi-area-select lokasi-area-select-db" name="details[${idx}][id_input_area_locations]" required><option value="">-- Pilih Lokasi Area --</option></select></div>
                    <div class="col-md-3"><button type="button" class="btn btn-danger btn-sm mt-4 remove-barang"><i class="bi bi-trash"></i> Hapus</button></div>
                </div>
                <div class="row mb-3 barang-manual-row" data-index="${idx}" style="display:none;">
                    <div class="col-md-3"><label class="form-label">Nama Barang <span class="text-danger">*</span></label><input type="text" class="form-control nama-barang-manual" name="details[${idx}][nama_barang_manual]" placeholder="Masukkan nama barang"></div>
                    <div class="col-md-2"><label class="form-label">Jumlah <span class="text-danger">*</span></label><input type="number" class="form-control" name="details[${idx}][jumlah_manual]" placeholder="Jumlah" min="0"></div>
                    <div class="col-md-4"><label class="form-label">Lokasi Area <span class="text-danger">*</span></label><select class="form-select lokasi-area-select lokasi-area-select-manual" name="details[${idx}][id_input_area_locations_disabled]"><option value="">-- Pilih Lokasi Area --</option></select></div>
                    <div class="col-md-3"><button type="button" class="btn btn-danger btn-sm mt-4 remove-barang"><i class="bi bi-trash"></i> Hapus</button></div>
                </div>
                <div class="row mb-3 nama_karyawan_row" data-index="${idx}" style="display:none;"><div class="col-md-6"><label class="form-label">Nama Karyawan</label><input type="text" class="form-control" name="details[${idx}][nama_karyawan]" placeholder="Nama karyawan (opsional)"></div></div>
                <div class="row mb-3">
                    <div class="col-md-3"><label class="form-label">Awal <span class="text-danger">*</span></label><div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="details[${idx}][awal]" id="awal_baik_${idx}" value="baik" required><label class="form-check-label" for="awal_baik_${idx}">baik</label></div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="details[${idx}][awal]" id="awal_tidak-baik_${idx}" value="tidak-baik"><label class="form-check-label" for="awal_tidak-baik_${idx}">tidak-baik</label></div></div></div>
                    <div class="col-md-3"><label class="form-label">Akhir <span class="text-danger">*</span></label><div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="details[${idx}][akhir]" id="akhir_baik_${idx}" value="baik" required><label class="form-check-label" for="akhir_baik_${idx}">baik</label></div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="details[${idx}][akhir]" id="akhir_tidak-baik_${idx}" value="tidak-baik"><label class="form-check-label" for="akhir_tidak-baik_${idx}">tidak-baik</label></div></div></div>
                </div>
                <div class="row mb-3"><div class="col-md-6"><label class="form-label">Temuan Ketidaksesuaian</label><textarea class="form-control" name="details[${idx}][temuan_ketidaksesuaian]" rows="3"></textarea></div><div class="col-md-6"><label class="form-label">Tindakan Koreksi</label><textarea class="form-control" name="details[${idx}][tindakan_koreksi]" rows="3"></textarea></div></div>
            </div>
        `;
        
        const div = document.createElement('div');
        div.innerHTML = html;
        document.getElementById('barangContainer').appendChild(div.firstElementChild);
        
        const newSelect = document.querySelector(`.barang-row:last-child select.choices`);
        if (newSelect) {
            new Choices(newSelect, { removeItemButton: true, placeholder: true, searchEnabled: true });
            newSelect.classList.add('choices-initialized');
        }
        
        loadAreaLocations();
        barangIndex++;
        updateUI();
    });
    
    // Remove barang
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-barang')) {
            e.preventDefault();
            e.target.closest('.barang-row').remove();
            updateUI();
        }
    });
    
    function updateUI() {
        const rows = document.querySelectorAll('.barang-row');
        rows.forEach((row, i) => {
            row.querySelector('.barang-number').textContent = i + 1;
            row.querySelectorAll('.remove-barang').forEach(btn => {
                btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
            });
        });
    }
    
    updateUI();
    
    // Debug sebelum submit
    document.addEventListener('submit', function(e) {
        if (e.target.tagName === 'FORM') {
            console.log('\n=== FORM SUBMISSION ===');
            const formData = new FormData(e.target);
            for (let [key, value] of formData.entries()) {
                if (key.includes('id_input_area_locations')) {
                    console.log(`>>> ${key} = ${value}`);
                }
            }
        }
    }, true);
});
</script>
@endsection