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

                                    <!-- Detail Barang (Dynamic Table Rows) -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="card border shadow-sm">
                                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-3 border-bottom">
                                                    <h5 class="card-title mb-0 text-primary fw-bold">
                                                        <i class="bi bi-list-check me-2"></i>Detail Benda Mudah Pecah & Tajam
                                                    </h5>
                                                    <button type="button" class="btn btn-sm btn-outline-primary d-none" id="addCustomRow">
                                                        <i class="bi bi-plus-circle me-1"></i>Tambah Baris Kustom
                                                    </button>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover align-middle mb-0" id="barangTable" style="display: none;">
                                                            <thead class="text-center">
                                                                <tr>
                                                                    <th style="width: 5%">#</th>
                                                                    <th style="width: 25%">Nama Barang</th>
                                                                    <th style="width: 20%">Sub Area (Opsional)</th>
                                                                    <th style="width: 8%">Jumlah</th>
                                                                    <th style="width: 12%">Verifikasi Pra-Op</th>
                                                                    <th style="width: 12%">Verifikasi Post-Op</th>
                                                                    <th style="width: 15%">Catatan</th>
                                                                    <th style="width: 3%">Hapus</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="barangTableBody">
                                                                <!-- Dynamic populated rows -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    
                                                    <!-- Alert/Placeholder when no area selected -->
                                                    <div id="noAreaAlert" class="text-center py-5">
                                                        <div class="text-muted mb-3">
                                                            <i class="bi bi-card-list fs-1 text-secondary"></i>
                                                        </div>
                                                        <h6 class="text-secondary fw-semibold">Silakan pilih Area terlebih dahulu</h6>
                                                        <p class="text-muted small px-3">Daftar pemeriksaan barang akan otomatis muncul dalam bentuk tabel yang ter-pre-populate berdasarkan area yang dipilih.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">
                                                Simpan Pemeriksaan
                                            </button>
                                            <a href="{{ route('pemeriksaan-barang-mudah-pecah.index') }}" class="btn btn-light-secondary mb-1 btn-kembali-confirm">
                                                Kembali
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
    // Confirm back navigation
    document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
        el.addEventListener('click', function(e) {
            const ok = confirm('Perubahan belum disimpan. Yakin ingin kembali?');
            if (!ok) e.preventDefault();
        });
    });

    const idAreaSelect = document.getElementById('id_area');
    const tableEl = document.getElementById('barangTable');
    const tableBody = document.getElementById('barangTableBody');
    const noAreaAlert = document.getElementById('noAreaAlert');
    const addCustomRowBtn = document.getElementById('addCustomRow');
    
    const allBarangs = @json($barangs);
    let cachedSubAreas = [];
    let customRowIndex = 1000; // start custom rows with high index to avoid conflicts

    // Function to render table
    function renderBarangTable(areaId, areaName, subAreas) {
        tableBody.innerHTML = '';
        
        // Filter barangs by selected id_area (exact database match)
        const filteredBarangs = allBarangs.filter(barang => barang.id_area == areaId);
        
        if (filteredBarangs.length === 0) {
            tableEl.style.display = 'table';
            noAreaAlert.style.display = 'none';
            addCustomRowBtn.classList.remove('d-none');
            
            tableBody.innerHTML = `
                <tr id="emptyTableAlert">
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="bi bi-info-circle me-1 text-warning"></i> Tidak ada barang terdaftar untuk area "${areaName}". Anda dapat menambahkan baris kustom secara manual.
                    </td>
                </tr>
            `;
            return;
        }

        // Show table, hide placeholder, show add custom button
        tableEl.style.display = 'table';
        noAreaAlert.style.display = 'none';
        addCustomRowBtn.classList.remove('d-none');

        // Group barangs by Sub Area
        const grouped = { 'null': [] };
        subAreas.forEach(loc => {
            grouped[loc.id] = [];
        });

        filteredBarangs.forEach(barang => {
            let matchedLocId = 'null';
            for (const loc of subAreas) {
                if (barang.nama_barang.toLowerCase().includes(loc.lokasi_area.toLowerCase())) {
                    matchedLocId = loc.id;
                    break;
                }
            }
            grouped[matchedLocId].push(barang);
        });

        // Render grouped table rows
        let rowIndex = 0;
        for (const locId in grouped) {
            const items = grouped[locId];
            if (items.length === 0) continue;

            const locName = locId === 'null' ? 'Tanpa Sub Area' : subAreas.find(s => s.id == locId).lokasi_area;
            
            // Sub-area header row
            if (locId !== 'null') {
                const groupHeader = document.createElement('tr');
                groupHeader.className = 'table-secondary fw-bold text-dark';
                groupHeader.innerHTML = `<td colspan="8" class="text-start px-3 py-2"><i class="bi bi-tag-fill me-2 text-primary"></i>${locName}</td>`;
                tableBody.appendChild(groupHeader);
            }

            // Item rows
            items.forEach(barang => {
                const idx = rowIndex++;
                
                // Generate sub area options (optional, not required)
                let subAreaOptions = `<option value="">-- Pilih Sub Area --</option>`;
                subAreas.forEach(loc => {
                    const isSelected = loc.id == locId ? 'selected' : '';
                    subAreaOptions += `<option value="${loc.id}" ${isSelected}>${loc.lokasi_area}</option>`;
                });
                
                const row = document.createElement('tr');
                row.className = 'barang-row';
                row.setAttribute('data-index', idx);
                row.innerHTML = `
                    <td class="text-center text-muted fw-semibold font-monospace">${idx + 1}</td>
                    <td class="text-start">
                        <span class="fw-semibold text-dark">${barang.nama_barang}</span>
                        <input type="hidden" name="details[${idx}][id_barang]" value="${barang.id}">
                    </td>
                    <td>
                        <select class="form-select form-select-sm" name="details[${idx}][id_input_area_locations]">
                            ${subAreaOptions}
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control text-center mx-auto" name="details[${idx}][jumlah_barang]" value="${barang.jumlah_barang || 0}" style="max-width: 90px;" required min="0">
                    </td>
                    <td>
                        <select class="form-select text-center" name="details[${idx}][awal]" required>
                            <option value="baik" selected>OK</option>
                            <option value="tidak-baik">Not OK</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-select text-center" name="details[${idx}][akhir]" required>
                            <option value="baik" selected>OK</option>
                            <option value="tidak-baik">Not OK</option>
                        </select>
                    </td>
                    <td>
                        <textarea class="form-control" name="details[${idx}][temuan_ketidaksesuaian]" rows="1" placeholder="catatan..."></textarea>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" title="Hapus Baris">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
    }

    // Handle Area select change
    if (idAreaSelect) {
        idAreaSelect.addEventListener('change', function() {
            const areaId = this.value;
            const areaName = this.options[this.selectedIndex].text.trim();

            if (!areaId) {
                tableEl.style.display = 'none';
                noAreaAlert.style.display = 'block';
                addCustomRowBtn.classList.add('d-none');
                tableBody.innerHTML = '';
                cachedSubAreas = [];
                return;
            }

            // Show loading placeholder
            tableEl.style.display = 'none';
            noAreaAlert.innerHTML = `
                <div class="py-5">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <h6 class="text-secondary fw-semibold">Memuat data sub-area & barang...</h6>
                </div>
            `;
            noAreaAlert.style.display = 'block';

            // Fetch sub-areas of the selected Area using dynamic API
            fetch(`{{ url('/qc-sistem/api/area-locations') }}/${areaId}`)
                .then(r => r.ok ? r.json() : Promise.reject(`HTTP ${r.status}`))
                .then(data => {
                    cachedSubAreas = data;
                    
                    // Reset alert html back to default
                    noAreaAlert.innerHTML = `
                        <div class="text-muted mb-3">
                            <i class="bi bi-card-list fs-1 text-secondary"></i>
                        </div>
                        <h6 class="text-secondary fw-semibold">Silakan pilih Area terlebih dahulu</h6>
                        <p class="text-muted small px-3">Daftar pemeriksaan barang akan otomatis muncul dalam bentuk tabel yang ter-pre-populate berdasarkan area yang dipilih.</p>
                    `;
                    
                    renderBarangTable(areaId, areaName, data);
                })
                .catch(err => {
                    console.error('Error loading locations:', err);
                    cachedSubAreas = [];
                    noAreaAlert.innerHTML = `
                        <div class="text-muted mb-3">
                            <i class="bi bi-card-list fs-1 text-secondary"></i>
                        </div>
                        <h6 class="text-secondary fw-semibold">Silakan pilih Area terlebih dahulu</h6>
                        <p class="text-muted small px-3">Daftar pemeriksaan barang akan otomatis muncul dalam bentuk tabel yang ter-pre-populate berdasarkan area yang dipilih.</p>
                    `;
                    renderBarangTable(areaId, areaName, []);
                });
        });
    }

    // Add custom manually input row
    if (addCustomRowBtn) {
        addCustomRowBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove empty table row if it exists
            const emptyAlert = document.getElementById('emptyTableAlert');
            if (emptyAlert) emptyAlert.remove();

            const idx = customRowIndex++;
            const row = document.createElement('tr');
            row.className = 'barang-row';
            row.setAttribute('data-index', idx);
            
            // Generate sub area options (optional, not required)
            let subAreaOptions = `<option value="">-- Pilih Sub Area --</option>`;
            cachedSubAreas.forEach(loc => {
                subAreaOptions += `<option value="${loc.id}">${loc.lokasi_area}</option>`;
            });

            row.innerHTML = `
                <td class="text-center text-primary fw-bold"><i class="bi bi-pencil-square" title="Baris Kustom"></i></td>
                <td class="text-start">
                    <input type="text" class="form-control mb-1 fw-semibold border-primary" name="details[${idx}][nama_barang_manual]" placeholder="Nama barang kustom *" required>
                </td>
                <td>
                    <select class="form-select border-primary" name="details[${idx}][id_input_area_locations]">
                        ${subAreaOptions}
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control text-center mx-auto" name="details[${idx}][jumlah_manual]" value="1" style="max-width: 90px;" required min="1">
                </td>
                <td>
                    <select class="form-select text-center" name="details[${idx}][awal]" required>
                        <option value="baik" selected>OK</option>
                        <option value="tidak-baik">Not OK</option>
                    </select>
                </td>
                <td>
                    <select class="form-select text-center" name="details[${idx}][akhir]" required>
                        <option value="baik" selected>OK</option>
                        <option value="tidak-baik">Not OK</option>
                    </select>
                </td>
                <td>
                    <textarea class="form-control" name="details[${idx}][temuan_ketidaksesuaian]" rows="1" placeholder="catatan..."></textarea>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-row-btn" title="Hapus Baris">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Remove row event delegator
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row-btn')) {
            e.preventDefault();
            const row = e.target.closest('tr');
            if (row) {
                row.remove();
                
                // If table is now empty, show empty table message
                const remainingRows = tableBody.querySelectorAll('.barang-row');
                if (remainingRows.length === 0) {
                    tableBody.innerHTML = `
                        <tr id="emptyTableAlert">
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle me-1 text-warning"></i> Tidak ada baris terdaftar. Anda dapat menambahkan baris kustom secara manual.
                            </td>
                        </tr>
                    `;
                }
            }
        }
    });
});
</script>
@endsection