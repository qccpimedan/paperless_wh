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
                    <h3>Input Bahan</h3>
                    <p class="text-subtitle text-muted">Tambah bahan baru untuk sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('bahans.index') }}">Bahan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Bahan</li>
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
                            <h4 class="card-title">Form Input Bahan</h4>
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

                                <form class="form form-horizontal" action="{{ route('bahans.store') }}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nama_bahan">Nama Bahan <span class="text-danger">*</span></label>
                                                <input type="text" id="nama_bahan" class="form-control @error('nama_bahan') is-invalid @enderror"
                                                    name="nama_bahan" placeholder="Nama Bahan" value="{{ old('nama_bahan') }}" required>
                                                @error('nama_bahan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mt-2">
                                                <label for="kategori_code">Kategori <span class="text-danger">*</span></label>
                                                <select name="kategori_code" id="kategori_code" class="form-select @error('kategori_code') is-invalid @enderror" required>
                                                    <option value="">-- Pilih Kategori --</option>
                                                    @foreach(['WHSE','RT01','CR01','CR02','SHTS','SHCS & OTRM'] as $kategori)
                                                        <option value="{{ $kategori }}" {{ old('kategori_code') === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                                    @endforeach
                                                </select>
                                                @error('kategori_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mt-2">
                                                <label for="id_produsen">Produsen (Bisa lebih dari 1)</label>
                                                <select name="id_produsen[]" id="id_produsen" class="form-select select2-multiple @error('id_produsen') is-invalid @enderror" multiple>
                                                    @foreach($produsens as $produsen)
                                                        <option value="{{ $produsen->id }}" {{ in_array($produsen->id, old('id_produsen', [])) ? 'selected' : '' }}>
                                                            {{ $produsen->nama_produsen }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_produsen')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mt-2">
                                                <label for="id_distributor">Distributor (Bisa lebih dari 1)</label>
                                                <select name="id_distributor[]" id="id_distributor" class="form-select select2-multiple @error('id_distributor') is-invalid @enderror" multiple>
                                                    @foreach($distributors as $distributor)
                                                        <option value="{{ $distributor->id }}" {{ in_array($distributor->id, old('id_distributor', [])) ? 'selected' : '' }}>
                                                            {{ $distributor->nama_distributor }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_distributor')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                <a href="{{ route('bahans.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
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