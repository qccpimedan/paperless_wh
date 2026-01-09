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
                    <h3>Input Chemical</h3>
                    <p class="text-subtitle text-muted">Tambah chemical baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('chemicals.index') }}">Chemical</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Input Chemical</li>
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
                            <h4 class="card-title">Form Input Chemical</h4>
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

                                <div class="alert alert-info">
                                    <strong>Catatan:</strong> Halaman ini terhubung dengan master <strong>Distributor</strong> dan <strong>Produsen</strong>.
                                    Jika data Distributor/Produsen belum ada, silakan input terlebih dahulu pada menu <strong>Input Distributor</strong> dan <strong>Input Produsen</strong>, lalu kembali ke halaman ini.
                                </div>

                                <form class="form form-horizontal" action="{{ route('chemicals.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nama_chemical">Nama Chemical <span class="text-danger">*</span></label>
                                                <div id="dynamic-fields">
                                                    <div class="input-group mb-2">
                                                        <select name="id_distributor[]" class="form-select @error('id_distributor.0') is-invalid @enderror">
                                                            <option value="">-- Pilih Distributor (Opsional) --</option>
                                                            @foreach($distributors as $distributor)
                                                                <option value="{{ $distributor->id }}" {{ old('id_distributor.0') == $distributor->id ? 'selected' : '' }}>
                                                                    {{ $distributor->nama_distributor }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <select name="id_produsen[]" class="form-select @error('id_produsen.0') is-invalid @enderror">
                                                            <option value="">-- Pilih Produsen (Opsional) --</option>
                                                            @foreach($produsens as $produsen)
                                                                <option value="{{ $produsen->id }}" {{ old('id_produsen.0') == $produsen->id ? 'selected' : '' }}>
                                                                    {{ $produsen->nama_produsen }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="text" class="form-control @error('nama_chemical.0') is-invalid @enderror"
                                                            name="nama_chemical[]" placeholder="Nama Chemical" value="{{ old('nama_chemical.0') }}" required>
                                                        <button type="button" class="btn btn-success" id="add-field">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @error('id_distributor')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                                @error('id_produsen')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                                @error('nama_chemical')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <a href="{{ route('chemicals.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
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
            <select name="id_distributor[]" class="form-select">
                <option value="">-- Pilih Distributor (Opsional) --</option>
                @foreach($distributors as $distributor)
                    <option value="{{ $distributor->id }}">{{ $distributor->nama_distributor }}</option>
                @endforeach
            </select>
            <select name="id_produsen[]" class="form-select">
                <option value="">-- Pilih Produsen (Opsional) --</option>
                @foreach($produsens as $produsen)
                    <option value="{{ $produsen->id }}">{{ $produsen->nama_produsen }}</option>
                @endforeach
            </select>
            <input type="text" class="form-control" name="nama_chemical[]" placeholder="Nama Chemical" required>
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
                alert('Minimal harus ada satu field nama chemical!');
            }
        }
    });
});
</script>
@endsection