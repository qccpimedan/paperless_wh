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
                    <h3>Edit Jenis Kendaraan</h3>
                    <p class="text-subtitle text-muted">Edit jenis kendaraan yang sudah ada</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('jenis-kendaraans.index') }}">Jenis Kendaraan</a></li>
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
                            <h4 class="card-title">Form Edit Jenis Kendaraan</h4>
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

                                <form class="form form-horizontal" action="{{ route('jenis-kendaraans.update', $jenisKendaraan->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="jenis_kendaraan">Jenis Kendaraan <span class="text-danger">*</span></label>
                                                <input type="text" id="jenis_kendaraan" class="form-control @error('jenis_kendaraan') is-invalid @enderror"
                                                    name="jenis_kendaraan" placeholder="Jenis Kendaraan" 
                                                    value="{{ old('jenis_kendaraan', $jenisKendaraan->jenis_kendaraan) }}" required>
                                                @error('jenis_kendaraan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="no_kendaraan">No Kendaraan <span class="text-danger">*</span></label>
                                                <input type="text" id="no_kendaraan" class="form-control @error('no_kendaraan') is-invalid @enderror"
                                                    name="no_kendaraan" placeholder="No Kendaraan" 
                                                    value="{{ old('no_kendaraan', $jenisKendaraan->no_kendaraan) }}" required>
                                                @error('no_kendaraan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <a href="{{ route('jenis-kendaraans.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
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
@endsection