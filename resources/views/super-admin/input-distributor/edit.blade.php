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
                    <h3>Edit Distributor</h3>
                    <p class="text-subtitle text-muted">Edit distributor yang sudah ada</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('distributors.index') }}">Distributor</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Distributor</li>
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
                        <h4 class="card-title">Form Edit Distributor</h4>
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

                            <form class="form form-horizontal" action="{{ route('distributors.update', $distributor->uuid) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="nama_distributor">Nama Distributor <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <input type="text" id="nama_distributor" class="form-control @error('nama_distributor') is-invalid @enderror"
                                                name="nama_distributor" placeholder="Nama Distributor" value="{{ old('nama_distributor', $distributor->nama_distributor) }}" required>
                                            @error('nama_distributor')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <a href="{{ route('distributors.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
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
@endsection