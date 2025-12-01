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
                    <h3>Data Deskripsi</h3>
                    <p class="text-subtitle text-muted">Kelola data deskripsi sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Deskripsi</li>
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
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Deskripsi</h5>
                    <a href="{{ route('input-deskripsis.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Deskripsi
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Deskripsi</th>
                                    <th>Plant</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deskripsis as $index => $deskripsi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $deskripsi->nama_deskripsi }}</strong>
                                        </td>
                                        <td>
                                            @if($deskripsi->user->plant)
                                                <span class="badge bg-info">{{ $deskripsi->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-vertical">
                                                <a href="{{ route('input-deskripsis.edit', $deskripsi->uuid) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit Data">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('input-deskripsis.destroy', $deskripsi->uuid) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Yakin ingin menghapus deskripsi {{ $deskripsi->nama_deskripsi }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus Data">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                                <p class="text-muted mt-2 mb-3">Belum ada data deskripsi</p>
                                                <a href="{{ route('input-deskripsis.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> Tambah Deskripsi Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
