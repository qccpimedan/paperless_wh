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
                    <h3>Input Master Form</h3>
                    <p class="text-subtitle text-muted">Buat master form baru untuk pemeriksaan</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('input-master-forms.index') }}">Master Form</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Buat Master Form</li>
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
                            <h4 class="card-title">Form Input Master Form</h4>
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

                                <form class="form form-horizontal" action="{{ route('input-master-forms.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nama_form">Nama Form <span class="text-danger">*</span></label>
                                                <input type="text" id="nama_form" class="form-control @error('nama_form') is-invalid @enderror"
                                                    name="nama_form" placeholder="Nama Form" 
                                                    value="{{ old('nama_form') }}" required>
                                                @error('nama_form')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label for="field_name">Field yang Diinput <span class="text-danger">*</span></label>
                                                <div id="dynamic-fields">
                                                    <div class="input-group mb-2">
                                                        <input type="text" class="form-control @error('field_name.0') is-invalid @enderror"
                                                            name="field_name[]" placeholder="Nama Field" value="{{ old('field_name.0') }}" required>
                                                        <button type="button" class="btn btn-success" id="add-field">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @error('field_name')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                <a href="{{ route('input-master-forms.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
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
    let fieldIndex = 1;
    
    // Add new field
    document.getElementById('add-field').addEventListener('click', function() {
        const dynamicFields = document.getElementById('dynamic-fields');
        const newField = document.createElement('div');
        newField.className = 'input-group mb-2';
        newField.innerHTML = `
            <input type="text" class="form-control" name="field_name[]" placeholder="Nama Field" required>
            <button type="button" class="btn btn-danger remove-field">
                <i class="bi bi-trash"></i>
            </button>
        `;
        dynamicFields.appendChild(newField);
        fieldIndex++;
    });
    
    // Remove field (using event delegation)
    document.getElementById('dynamic-fields').addEventListener('click', function(e) {
        if (e.target.closest('.remove-field')) {
            const fieldCount = document.querySelectorAll('#dynamic-fields .input-group').length;
            if (fieldCount > 1) {
                e.target.closest('.input-group').remove();
            } else {
                alert('Minimal harus ada satu field!');
            }
        }
    });
});
</script>
@endsection