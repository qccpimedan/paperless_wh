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
                    <h3>Input Tujuan Pengiriman</h3>
                    <p class="text-subtitle text-muted">Tambah tujuan pengiriman baru untuk sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('tujuan-pengirimans.index') }}">Tujuan Pengiriman</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Tujuan Pengiriman</li>
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
                            <h4 class="card-title">Form Input Tujuan Pengiriman</h4>
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
                                    <strong>Catatan:</strong> Halaman ini terhubung dengan master <strong>Customer</strong> dan <strong>Produsen</strong>.
                                    Jika data Customer belum ada, silakan input terlebih dahulu pada menu <strong>Input Customer</strong>, lalu kembali ke halaman ini.
                                </div>
                                <form class="form form-horizontal" action="{{ route('tujuan-pengirimans.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nama_tujuan">Nama Customer <span class="text-danger">*</span></label>
                                                <div id="dynamic-fields">
                                                    <div class="input-group mb-2">
                                                        <select name="id_customer[]" class="form-select @error('id_customer.0') is-invalid @enderror select2">
                                                            <option value="">-- Pilih Customer (Opsional) --</option>
                                                            @foreach($customers as $customer)
                                                                <option value="{{ $customer->id }}" {{ old('id_customer.0') == $customer->id ? 'selected' : '' }}>
                                                                    {{ $customer->nama_cust }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="text" class="form-control @error('nama_tujuan.0') is-invalid @enderror"
                                                            name="nama_tujuan[]" placeholder="Tujuan Pengiriman" value="{{ old('nama_tujuan.0') }}" required>
                                                        <button type="button" class="btn btn-success" id="add-field">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @error('id_customer')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                                @error('nama_tujuan')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                <a href="{{ route('tujuan-pengirimans.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
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

<style>
    /* Styling agar Select2 dapat berfungsi dengan baik di dalam Bootstrap Input Group */
    .input-group > .select2-container {
        flex: 1 1 auto;
        width: 1% !important;
        margin-right: -1px;
    }
    .input-group > .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem 0 0 0.25rem !important;
        display: flex;
        align-items: center;
    }
    .input-group > .select2-container .select2-selection__rendered {
        padding-left: 12px !important;
        color: #495057 !important;
    }
    .input-group > .select2-container .select2-selection__arrow {
        height: 36px !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let fieldIndex = 1;
    
    // Helper function to initialize Select2
    function initSelect2(selector) {
        if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.select2 === 'function') {
            window.jQuery(selector).select2({
                width: 'resolve',
                placeholder: '-- Pilih Customer (Opsional) --',
                allowClear: true
            });
        }
    }

    // Initialize Select2 on the initial select element
    initSelect2('.select2');
    
    document.getElementById('add-field').addEventListener('click', function() {
        const dynamicFields = document.getElementById('dynamic-fields');
        const newField = document.createElement('div');
        newField.className = 'input-group mb-2';
        newField.innerHTML = `
            <select name="id_customer[]" class="form-select select2">
                <option value="">-- Pilih Customer (Opsional) --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->nama_cust }}</option>
                @endforeach
            </select>
            <input type="text" class="form-control" name="nama_tujuan[]" placeholder="Tujuan Pengiriman" required>
            <button type="button" class="btn btn-danger remove-field">
                <i class="bi bi-trash"></i>
            </button>
        `;
        dynamicFields.appendChild(newField);
        
        // Initialize Select2 on the newly created select element
        initSelect2(newField.querySelector('.select2'));
        
        fieldIndex++;
    });
    
    document.getElementById('dynamic-fields').addEventListener('click', function(e) {
        if (e.target.closest('.remove-field')) {
            const fieldCount = document.querySelectorAll('#dynamic-fields .input-group').length;
            if (fieldCount > 1) {
                const group = e.target.closest('.input-group');
                // Destroy Select2 before removing to prevent memory leaks
                if (window.jQuery) {
                    const select = group.querySelector('.select2');
                    if (select && window.jQuery(select).data('select2')) {
                        window.jQuery(select).select2('destroy');
                    }
                }
                group.remove();
            } else {
                alert('Minimal harus ada satu field tujuan pengiriman!');
            }
        }
    });
});
</script>
@endsection