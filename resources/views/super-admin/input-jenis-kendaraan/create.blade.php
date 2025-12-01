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
                    <h3>Input Jenis Kendaraan</h3>
                    <p class="text-subtitle text-muted">Tambah jenis kendaraan baru untuk sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('jenis-kendaraans.index') }}">Jenis Kendaraan</a></li>
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
                            <h4 class="card-title">Form Input Jenis Kendaraan</h4>
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

                                <form class="form form-horizontal" action="{{ route('jenis-kendaraans.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Jenis & No Kendaraan <span class="text-danger">*</span></label>
                                                <div id="dynamic-fields">
                                                    <div class="row mb-2 field-row">
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" name="jenis_kendaraan[]" placeholder="Jenis Kendaraan" required>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" name="no_kendaraan[]" placeholder="No Kendaraan" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-success w-100" id="add-field">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                <a href="{{ route('jenis-kendaraans.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
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
    document.getElementById('add-field').addEventListener('click', function() {
        const dynamicFields = document.getElementById('dynamic-fields');
        const newField = document.createElement('div');
        newField.className = 'row mb-2 field-row';
        newField.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control" name="jenis_kendaraan[]" placeholder="Jenis Kendaraan" required>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="no_kendaraan[]" placeholder="No Kendaraan" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 remove-field">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        dynamicFields.appendChild(newField);
    });
    
    document.getElementById('dynamic-fields').addEventListener('click', function(e) {
        if (e.target.closest('.remove-field')) {
            const fieldCount = document.querySelectorAll('#dynamic-fields .field-row').length;
            if (fieldCount > 1) {
                e.target.closest('.field-row').remove();
            } else {
                alert('Minimal harus ada satu field!');
            }
        }
    });
});
</script>
@endsection