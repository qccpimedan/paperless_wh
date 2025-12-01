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
                    <h3>Edit Area</h3>
                    <p class="text-subtitle text-muted">Edit area yang sudah ada</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('input-areas.index') }}">Area</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Area</li>
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
                            <h4 class="card-title">Form Edit Area</h4>
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

                                <form class="form form-horizontal" action="{{ route('input-areas.update', $inputArea->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nama_area">Nama Area <span class="text-danger">*</span></label>
                                                <input type="text" id="nama_area" class="form-control @error('nama_area') is-invalid @enderror"
                                                    name="nama_area" placeholder="Nama Area" 
                                                    value="{{ old('nama_area', $inputArea->nama_area) }}" required>
                                                @error('nama_area')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mt-4">
                                                <label for="lokasi_area">Lokasi Area <span class="text-muted">(Opsional)</span></label>
                                                <div id="dynamic-lokasi-fields">
                                                    @forelse($inputArea->locations as $location)
                                                        <div class="input-group mb-2">
                                                            <input type="text" class="form-control"
                                                                name="lokasi_area[]" placeholder="Lokasi Area" 
                                                                value="{{ old('lokasi_area', $location->lokasi_area) }}">
                                                            <button type="button" class="btn btn-danger remove-lokasi-field">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    @empty
                                                        <div class="input-group mb-2">
                                                            <input type="text" class="form-control"
                                                                name="lokasi_area[]" placeholder="Lokasi Area">
                                                            <button type="button" class="btn btn-success" id="add-lokasi-field">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    @endforelse
                                                </div>
                                                @error('lokasi_area')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <a href="{{ route('input-areas.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                                <button type="button" class="btn btn-light-secondary me-1 mb-1">Reset</button>
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
    // Add new lokasi_area field
    const addLokasiBtn = document.getElementById('add-lokasi-field');
    if (addLokasiBtn) {
        addLokasiBtn.addEventListener('click', function() {
            const dynamicLokasiFields = document.getElementById('dynamic-lokasi-fields');
            const newField = document.createElement('div');
            newField.className = 'input-group mb-2';
            newField.innerHTML = `
                <input type="text" class="form-control" name="lokasi_area[]" placeholder="Lokasi Area">
                <button type="button" class="btn btn-danger remove-lokasi-field">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            dynamicLokasiFields.appendChild(newField);
        });
    }
    
    // Remove lokasi_area field (using event delegation)
    document.getElementById('dynamic-lokasi-fields').addEventListener('click', function(e) {
        if (e.target.closest('.remove-lokasi-field')) {
            const fieldCount = document.querySelectorAll('#dynamic-lokasi-fields .input-group').length;
            if (fieldCount > 1) {
                e.target.closest('.input-group').remove();
            } else {
                alert('Minimal harus ada satu field lokasi atau kosongkan semua!');
            }
        }
    });
});
</script>
@endsection
