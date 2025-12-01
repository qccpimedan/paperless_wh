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
                    <h3>Input Barang</h3>
                    <p class="text-subtitle text-muted">Tambah barang baru untuk sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('barangs.index') }}">Barang</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Barang</li>
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
                            <h4 class="card-title">Form Input Barang</h4>
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

                                <form class="form form-horizontal" action="{{ route('barangs.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                                                <div id="dynamic-fields-nama">
                                                    <div class="input-group mb-2">
                                                        <input type="text" class="form-control @error('nama_barang.0') is-invalid @enderror"
                                                            name="nama_barang[]" placeholder="Nama Barang" value="{{ old('nama_barang.0') }}" required>
                                                        <button type="button" class="btn btn-success" id="add-field">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @error('nama_barang')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="jumlah_barang">Jumlah Barang <span class="text-danger">*</span></label>
                                                <div id="dynamic-fields-jumlah">
                                                    <div class="input-group mb-2">
                                                        <input type="number" class="form-control @error('jumlah_barang.0') is-invalid @enderror"
                                                            name="jumlah_barang[]" placeholder="Jumlah" value="{{ old('jumlah_barang.0', 0) }}" min="0" required>
                                                    </div>
                                                </div>
                                                @error('jumlah_barang')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                <a href="{{ route('barangs.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
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
        const dynamicFieldsNama = document.getElementById('dynamic-fields-nama');
        const dynamicFieldsJumlah = document.getElementById('dynamic-fields-jumlah');
        
        // Add nama barang field
        const newFieldNama = document.createElement('div');
        newFieldNama.className = 'input-group mb-2';
        newFieldNama.innerHTML = `
            <input type="text" class="form-control" name="nama_barang[]" placeholder="Nama Barang" required>
            <button type="button" class="btn btn-danger remove-field-nama">
                <i class="bi bi-trash"></i>
            </button>
        `;
        dynamicFieldsNama.appendChild(newFieldNama);
        
        // Add jumlah barang field
        const newFieldJumlah = document.createElement('div');
        newFieldJumlah.className = 'input-group mb-2';
        newFieldJumlah.innerHTML = `
            <input type="number" class="form-control" name="jumlah_barang[]" placeholder="Jumlah" value="0" min="0" required>
        `;
        dynamicFieldsJumlah.appendChild(newFieldJumlah);
        
        fieldIndex++;
    });
    
    // Remove field (using event delegation)
    document.getElementById('dynamic-fields-nama').addEventListener('click', function(e) {
        if (e.target.closest('.remove-field-nama')) {
            const fieldCount = document.querySelectorAll('#dynamic-fields-nama .input-group').length;
            if (fieldCount > 1) {
                const index = Array.from(document.querySelectorAll('#dynamic-fields-nama .input-group')).indexOf(e.target.closest('.input-group'));
                e.target.closest('.input-group').remove();
                
                // Remove corresponding jumlah field
                const jumlahFields = document.querySelectorAll('#dynamic-fields-jumlah .input-group');
                if (jumlahFields[index]) {
                    jumlahFields[index].remove();
                }
            } else {
                alert('Minimal harus ada satu field nama barang!');
            }
        }
    });
});
</script>
@endsection