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
                    <h3>Edit Golden Sample Report</h3>
                    <p class="text-subtitle text-muted">Edit Golden Sample Report</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('golden-sample-reports.index') }}">Golden Sample Report</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                            <h4 class="card-title">Form Edit Golden Sample Report</h4>
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

                                <form class="form form-horizontal" action="{{ route('golden-sample-reports.update', $goldenSampleReport->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <!-- BAGIAN HEADER -->
                                        <h5 class="mb-3"><strong>Informasi Dasar</strong></h5>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="id_plant">Plant <span class="text-danger">*</span></label>
                                                    <select id="id_plant" class="choices form-select @error('id_plant') is-invalid @enderror" name="id_plant">
                                                        <option value="">-- Pilih Plant --</option>
                                                        @foreach($plants as $plant)
                                                            <option value="{{ $plant->id }}" {{ old('id_plant', $goldenSampleReport->id_plant) == $plant->id ? 'selected' : '' }}>
                                                                {{ $plant->plant }}
                                                            </option>
                                                        @endforeach
                                                        <option value="other" {{ old('id_plant', $goldenSampleReport->id_plant) == 'other' || $goldenSampleReport->plant_manual ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                    </select>
                                                    @error('id_plant')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    
                                                    <!-- Input manual yang awalnya disembunyikan -->
                                                    <div id="manual_plant_input" class="mt-2" style="display: {{ $goldenSampleReport->plant_manual ? 'block' : 'none' }};">
                                                        <div class="form-group">
                                                            <label for="plant_manual">Nama Plant</label>
                                                            <input type="text" id="plant_manual" class="form-control" 
                                                                name="plant_manual" value="{{ old('plant_manual', $goldenSampleReport->plant_manual) }}" placeholder="Masukkan nama plant">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="sample_type">Sample Type <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('sample_type') is-invalid @enderror" name="sample_type" id="sample_type" placeholder="Contoh: Bahan Baku" value="{{ old('sample_type', $goldenSampleReport->sample_type) }}" required>
                                                @error('sample_type')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="collection_date_from">Dari Bulan <span class="text-danger">*</span></label>
                                                        <input type="month" class="form-control @error('collection_date_from') is-invalid @enderror" name="collection_date_from" id="collection_date_from" value="{{ old('collection_date_from', $goldenSampleReport->collection_date_from) }}" required>
                                                        @error('collection_date_from')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="collection_date_to">Ke Bulan <span class="text-danger">*</span></label>
                                                        <input type="month" class="form-control @error('collection_date_to') is-invalid @enderror" name="collection_date_to" id="collection_date_to" value="{{ old('collection_date_to', $goldenSampleReport->collection_date_to) }}" required>
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
                                                    <input class="form-check-input" type="checkbox" name="sample_storage[]" value="Frozen" id="frozen" {{ in_array('Frozen', old('sample_storage', $goldenSampleReport->sample_storage)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="frozen">
                                                        Frozen (≤ -18°C)
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sample_storage[]" value="Chilled" id="chilled" {{ in_array('Chilled', old('sample_storage', $goldenSampleReport->sample_storage)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="chilled">
                                                        Chilled (0 - 5°C)
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sample_storage[]" value="Ambient" id="ambient" {{ in_array('Ambient', old('sample_storage', $goldenSampleReport->sample_storage)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="ambient">
                                                        Ambient
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
                                            @foreach($goldenSampleReport->samples as $index => $sample)
                                                <div class="sample-item card mb-3 p-3" data-index="{{ $index }}">
                                                    <div class="row mb-2">
                                                        <div class="col-md-12">
                                                            <h6><strong>Sampel #<span class="sample-number">{{ $index + 1 }}</span></strong></h6>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-2">
                                                        <div class="col-md-6">
                                                            <label>Deskripsi <span class="text-danger">*</span>(dapat dipilih lebih dari 1)</label>
                                                            <select class="choices form-select deskripsi-select" name="samples[{{ $index }}][id_deskripsi][]" multiple required>
                                                                @foreach($deskripsis as $deskripsi)
                                                                    <option value="{{ $deskripsi->uuid }}" {{ in_array($deskripsi->uuid, $sample['id_deskripsi'] ?? []) ? 'selected' : '' }}>{{ $deskripsi->nama_deskripsi }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Supplier <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="samples[{{ $index }}][id_supplier]" placeholder="Nama supplier" value="{{ $sample['id_supplier'] ?? '' }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-2">
                                                        <div class="col-md-6">
                                                            <label>Kode Produksi <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="samples[{{ $index }}][kode_produksi]" placeholder="Kode produksi" value="{{ $sample['kode_produksi'] ?? '' }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Best Before <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" name="samples[{{ $index }}][best_before]" value="{{ $sample['best_before'] ?? '' }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-2">
                                                        <div class="col-md-4">
                                                            <label>QTY (gram) <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="samples[{{ $index }}][qty]" placeholder="Jumlah" value="{{ $sample['qty'] ?? '' }}" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Diserahkan <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="samples[{{ $index }}][diserahkan]" placeholder="Nama" value="{{ $sample['diserahkan'] ?? '' }}" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Diterima <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="samples[{{ $index }}][diterima]" placeholder="Nama" value="{{ $sample['diterima'] ?? '' }}" required>
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
                                            @endforeach
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-success" id="add-sample">
                                                    <i class="bi bi-plus-circle"></i> Tambah Sampel
                                                </button>
                                            </div>
                                        </div>

                                        <hr class="my-4">
                                        <div class="row">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Update Report</button>
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
    let sampleIndex = {{ count($goldenSampleReport->samples) }};

    // Add new sample
    document.getElementById('add-sample').addEventListener('click', function() {
        const container = document.getElementById('samples-container');
        const firstSample = document.querySelector('.sample-item');
        const newSample = firstSample.cloneNode(true);
        
        newSample.setAttribute('data-index', sampleIndex);
        newSample.querySelector('.sample-number').textContent = sampleIndex + 1;
        
        // Hapus class 'choices' dan data-attributes dari Choices
        newSample.querySelectorAll('select').forEach(select => {
            select.classList.remove('choices');
            select.removeAttribute('data-type');
            select.removeAttribute('aria-hidden');
            select.innerHTML = '';
        });
        
        // Clear input values
        newSample.querySelectorAll('input').forEach(input => {
            input.value = '';
        });
        
        // Copy options dari first sample ke new sample
        const firstSelects = firstSample.querySelectorAll('select');
        const newSelects = newSample.querySelectorAll('select');
        
        firstSelects.forEach((firstSelect, index) => {
            if (newSelects[index]) {
                newSelects[index].innerHTML = firstSelect.innerHTML;
                newSelects[index].classList.add('choices');
            }
        });
        
        // Update input names
        newSample.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${sampleIndex}]`));
            }
        });
        
        // Show remove button
        newSample.querySelector('.remove-sample').style.display = 'inline-block';
        
        container.appendChild(newSample);
        
        // Reinisialisasi Choices SETELAH append ke DOM
        const newChoicesSelects = newSample.querySelectorAll('select.choices');
        newChoicesSelects.forEach(select => {
            if (!select || select.tagName !== 'SELECT') return;
            
            try {
                new Choices(select, {
                    removeItemButton: true,
                    placeholder: true,
                    searchEnabled: true
                });
            } catch(e) {
                console.warn('Error initializing Choices:', e);
            }
        });
        
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
