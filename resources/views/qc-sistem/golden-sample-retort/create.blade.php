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
                    <h3>Golden Sample Report</h3>
                    <p class="text-subtitle text-muted">Tambah Golden Sample Report baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('golden-sample-reports.index') }}">Golden Sample Report</a></li>
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
                            <h4 class="card-title">Form Golden Sample Report</h4>
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

                                <form class="form form-horizontal" action="{{ route('golden-sample-reports.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <!-- BAGIAN HEADER -->
                                        <h5 class="mb-3"><strong>Informasi Dasar</strong></h5>
                                        <div class="row mb-3">
                                            <!-- Tambahkan di bagian form, sesudah input tanggal -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_shift">Shift <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('id_shift') is-invalid @enderror" id="id_shift" name="id_shift" required>
                                                        <option value="">Pilih Shift</option>
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

                                            <!-- Pastikan input tanggal menggunakan name yang sesuai dengan database -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                                        id="tanggal" name="tanggal" 
                                                        value="{{ old('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                                                    @error('tanggal')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="id_plant">Plant <span class="text-danger">*</span></label>
                                                    <select id="id_plant" class="choices form-select @error('id_plant') is-invalid @enderror" name="id_plant">
                                                        <option value="">-- Pilih Plant --</option>
                                                        @foreach($plants as $plant)
                                                            <option value="{{ $plant->id }}" {{ old('id_plant') == $plant->id ? 'selected' : '' }}>
                                                                {{ $plant->plant }}
                                                            </option>
                                                        @endforeach
                                                        <option value="other" {{ old('id_plant') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                    </select>
                                                    @error('id_plant')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    
                                                    <!-- Input manual yang awalnya disembunyikan -->
                                                    <div id="manual_plant_input" class="mt-2" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="plant_manual">Nama Plant</label>
                                                            <input type="text" id="plant_manual" class="form-control" 
                                                                name="plant_manual" value="{{ old('plant_manual') }}" placeholder="Masukkan nama plant">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="sample_type">Sample Type <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('sample_type') is-invalid @enderror" name="sample_type" id="sample_type" placeholder="Contoh: Bahan Baku" value="{{ old('sample_type') }}" required>
                                                @error('sample_type')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="collection_date_from">Dari Bulan <span class="text-danger">*</span></label>
                                                        <input type="month" class="form-control @error('collection_date_from') is-invalid @enderror" name="collection_date_from" id="collection_date_from" value="{{ old('collection_date_from') }}" required>
                                                        @error('collection_date_from')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="collection_date_to">Ke Bulan <span class="text-danger">*</span></label>
                                                        <input type="month" class="form-control @error('collection_date_to') is-invalid @enderror" name="collection_date_to" id="collection_date_to" value="{{ old('collection_date_to') }}" required>
                                                        @error('collection_date_to')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label>Sample Storage <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sample_storage[]" value="Frozen" id="frozen" {{ in_array('Frozen', old('sample_storage', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="frozen">
                                                        Frozen (≤ -18°C)
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sample_storage[]" value="Chilled" id="chilled" {{ in_array('Chilled', old('sample_storage', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="chilled">
                                                        Chilled (0 - 5°C)
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sample_storage[]" value="Ambient" id="ambient" {{ in_array('Ambient', old('sample_storage', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="ambient">
                                                        Ambient
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sample_storage[]" value="Other" id="other" {{ in_array('Other', old('sample_storage', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="other">
                                                        Other
                                                    </label>
                                                </div>
                                                @error('sample_storage')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <hr class="my-4">
                                        <h5 class="mb-3"><strong>Data Sampel</strong></h5>

                                        <div id="samples-container">
                                            <div class="sample-item card mb-3 p-3" data-index="0">
                                                <div class="row mb-2">
                                                    <div class="col-md-12">
                                                        <h6><strong>Sampel #<span class="sample-number">1</span></strong></h6>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <label>Deskripsi <span class="text-danger">*</span> (dapat dipilih lebih dari 1)</label>
                                                        <select class="choices form-select deskripsi-select" name="samples[0][id_deskripsi][]" multiple required>
                                                            @foreach($deskripsis as $deskripsi)
                                                                <option value="{{ $deskripsi->uuid }}">{{ $deskripsi->nama_deskripsi }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Supplier <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="samples[0][id_supplier]" placeholder="Nama supplier" required>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <label>Kode Produksi <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="samples[0][kode_produksi]" placeholder="Kode produksi" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Best Before <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" name="samples[0][best_before]" required>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col-md-4">
                                                        <label>QTY (gram) <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="samples[0][qty]" placeholder="Jumlah" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Diserahkan <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="samples[0][diserahkan]" placeholder="Nama" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Diterima <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="samples[0][diterima]" placeholder="Nama" required>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-danger btn-sm remove-sample" style="display:none;">
                                                            <i class="bi bi-trash"></i> Hapus Sampel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-primary btn-sm" id="add-sample">
                                                    <i class="bi bi-plus-circle"></i> Tambah Sampel
                                                </button>
                                            </div>
                                        </div>

                                        <hr class="my-4">
                                        <div class="row">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Simpan Report</button>
                                                <a href="{{ route('golden-sample-reports.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
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
// Handle Plant manual input
document.getElementById('id_plant').addEventListener('change', function() {
    const manualInput = document.getElementById('manual_plant_input');
    if (this.value === 'other') {
        manualInput.style.display = 'block';
        document.getElementById('plant_manual').required = true;
    } else {
        manualInput.style.display = 'none';
        document.getElementById('plant_manual').required = false;
    }
});

// Check on page load
window.addEventListener('load', function() {
    const plantSelect = document.getElementById('id_plant');
    if (plantSelect.value === 'other') {
        document.getElementById('manual_plant_input').style.display = 'block';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    let sampleIndex = 1;

    // Template untuk sampel baru (HTML string)
    const sampleTemplate = `
        <div class="sample-item card mb-3 p-3" data-index="__INDEX__">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h6><strong>Sampel #<span class="sample-number">__NUMBER__</span></strong></h6>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <label>Deskripsi <span class="text-danger">*</span>(dapat dipilih lebih dari 1)</label>
                    <select class="choices form-select deskripsi-select" name="samples[__INDEX__][id_deskripsi][]" multiple required>
                        @foreach($deskripsis as $deskripsi)
                            <option value="{{ $deskripsi->uuid }}">{{ $deskripsi->nama_deskripsi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Supplier <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="samples[__INDEX__][id_supplier]" placeholder="Nama supplier" required>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <label>Kode Produksi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="samples[__INDEX__][kode_produksi]" placeholder="Kode produksi" required>
                </div>
                <div class="col-md-6">
                    <label>Best Before <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="samples[__INDEX__][best_before]" required>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <label>QTY (gram) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="samples[__INDEX__][qty]" placeholder="Jumlah" required>
                </div>
                <div class="col-md-4">
                    <label>Diserahkan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="samples[__INDEX__][diserahkan]" placeholder="Nama" required>
                </div>
                <div class="col-md-4">
                    <label>Diterima <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="samples[__INDEX__][diterima]" placeholder="Nama" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-danger btn-sm remove-sample">
                        <i class="bi bi-trash"></i> Hapus Sampel
                    </button>
                </div>
            </div>
        </div>
    `;

    // Add new sample
    document.getElementById('add-sample').addEventListener('click', function() {
        const container = document.getElementById('samples-container');
        
        // Ganti placeholder dengan nilai aktual
        const newSampleHTML = sampleTemplate
            .replace(/__INDEX__/g, sampleIndex)
            .replace(/__NUMBER__/g, sampleIndex + 1);
        
        // Buat element dari HTML string
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = newSampleHTML.trim();
        const newSample = tempDiv.firstChild;
        
        container.appendChild(newSample);
        
        // Inisialisasi Choices untuk select di sampel baru
        const newChoicesSelect = newSample.querySelector('select.choices');
        if (newChoicesSelect) {
            new Choices(newChoicesSelect, {
                removeItemButton: true,
                placeholder: true,
                searchEnabled: true
            });
        }
        
        sampleIndex++;
        updateRemoveButtons();
    });

    // Remove sample
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-sample')) {
            e.target.closest('.sample-item').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const samples = document.querySelectorAll('.sample-item');
        samples.forEach((sample, index) => {
            const removeBtn = sample.querySelector('.remove-sample');
            if (samples.length > 1) {
                removeBtn.style.display = 'inline-block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    updateRemoveButtons();
});
</script>
@endsection