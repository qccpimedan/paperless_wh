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
                    <h3>Edit Master Form</h3>
                    <p class="text-subtitle text-muted">Edit master form yang sudah ada</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('input-master-forms.index') }}">Master Form</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Master Form</li>
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
                            <h4 class="card-title">Form Edit Master Form</h4>
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

                                <form class="form form-horizontal" action="{{ route('input-master-forms.update', $inputMasterForm->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nama_form">Nama Form <span class="text-danger">*</span></label>
                                                <input type="text" id="nama_form" class="form-control @error('nama_form') is-invalid @enderror"
                                                    name="nama_form" placeholder="Nama Form" 
                                                    value="{{ old('nama_form', $inputMasterForm->nama_form) }}" required>
                                                @error('nama_form')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label for="field_name">Field yang Diinput <span class="text-danger">*</span></label>
                                                <div id="dynamic-fields">
                                                    @foreach($inputMasterForm->fields as $index => $field)
                                                        <div class="input-group mb-2">
                                                            <input type="text" class="form-control"
                                                                name="field_name[]" placeholder="Nama Field" 
                                                                value="{{ old('field_name.' . $index, $field->field_name) }}" required>
                                                            @if($index === 0)
                                                                <button type="button" class="btn btn-success" id="add-field">
                                                                    <i class="bi bi-plus"></i>
                                                                </button>
                                                            @else
                                                                <button type="button" class="btn btn-danger remove-field">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @error('field_name')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <a href="{{ route('input-master-forms.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
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
    let fieldIndex = {{ count($inputMasterForm->fields) }};
    
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