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
                    <h3>Detail Bahan Kemasan</h3>
                    <p class="text-subtitle text-muted">Lihat detail bahan kemasan</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('bahan-kemasans.index') }}">Bahan Kemasan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Bahan Kemasan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Nama Kemasan:</strong> {{ $bahanKemasan->nama_kemasan }}</p>
                            <p><strong>Kategori:</strong> {{ $bahanKemasan->kategori_code ?? '-' }}</p>
                            <p><strong>Distributor:</strong>
                                @if($bahanKemasan->distributors && $bahanKemasan->distributors->count() > 0)
                                    {{ $bahanKemasan->distributors->pluck('nama_distributor')->implode(', ') }}
                                @else
                                    -
                                @endif
                            </p>
                            <p><strong>Produsen:</strong>
                                @if($bahanKemasan->produsens && $bahanKemasan->produsens->count() > 0)
                                    {{ $bahanKemasan->produsens->pluck('nama_produsen')->implode(', ') }}
                                @else
                                    -
                                @endif
                            </p>
                            <p><strong>Plant:</strong>
                                @if($bahanKemasan->user && $bahanKemasan->user->plant)
                                    <span class="badge bg-info">{{ $bahanKemasan->user->plant->plant }}</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-12 d-flex justify-content-end mt-3">
                            <a href="{{ route('bahan-kemasans.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                            <a href="{{ route('bahan-kemasans.edit', $bahanKemasan->uuid) }}" class="btn btn-warning me-1 mb-1">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
