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
                    <h3>Dashboard</h3>
                    <p class="text-subtitle text-muted">Selamat datang di Paperless QC-WH System</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h4>
                        </div>
                        <div class="card-body">
                            <p>
                                <strong>Role:</strong>
                                @php
                                    $role = Auth::user()->role->role ?? null;
                                    $roleColor = match($role) {
                                        default     => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $roleColor }}">{{ $role ?? 'Tidak ada role' }}</span>
                            </p>
                            <p>
                                <strong>Plant:</strong>
                                <span class="badge bg-info text-dark">{{ Auth::user()->plant->plant ?? 'Tidak ada plant' }}</span>
                            </p>
                            <div class="card text-center py-5">
                                {{-- Icon --}}
                                <div class="mb-4">
                                    <i class="bi bi-tools" style="font-size: 3rem; color: #6c757d;"></i>
                                </div>

                                {{-- Judul --}}
                                <h4 class="mb-2">Dashboard sedang dalam pengembangan</h4>
                                <p class="text-muted mb-4">
                                    Fitur ini belum selesai dikerjakan. Tim sedang mempersiapkan tampilan terbaik untuk Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('partials.footer')
</div>
@endsection
