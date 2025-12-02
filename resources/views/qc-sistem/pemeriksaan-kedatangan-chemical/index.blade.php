@extends('layouts.app')

@section('title', 'Pemeriksaan Kedatangan Chemical')

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
                    <h3>Pemeriksaan Kedatangan Chemical</h3>
                    <p class="text-subtitle text-muted">Daftar data pemeriksaan kedatangan chemical</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pemeriksaan Kedatangan Chemical</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Data Pemeriksaan Kedatangan Chemical</h4>
                            <a href="{{ route('pemeriksaan-chemical.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped text-center" id="table1" style="white-space: nowrap;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Chemical</th>
                                        <th>Kondisi Chemical</th>
                                        <th>Produsen</th>
                                        <th>Kode Produksi</th>
                                        <th>Status</th>
                                        <th>Plant</th>
                                        <th>Shift</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pemeriksaans as $index => $pemeriksaan)
                                        <tr>
                                            <td>{{ $pemeriksaans->firstItem() + $index }}</td>
                                            <td>{{ $pemeriksaan->tanggal ? $pemeriksaan->tanggal->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($pemeriksaan->chemical)
                                                    <span class="badge bg-info">{{ $pemeriksaan->chemical->nama_chemical }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pemeriksaan->kondisi_chemical)
                                                    <span class="badge bg-secondary">{{ $pemeriksaan->kondisi_chemical }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pemeriksaan->produsen)
                                                    {{ $pemeriksaan->produsen->nama_produsen }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $pemeriksaan->kode_produksi ?? '-' }}
                                            </td>
                                            <td>
                                                @if($pemeriksaan->status === 'Release')
                                                    <span class="badge bg-success">{{ $pemeriksaan->status }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $pemeriksaan->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pemeriksaan->user && $pemeriksaan->user->plant)
                                                    <span class="badge bg-primary">{{ $pemeriksaan->user->plant->plant }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pemeriksaan->shift)
                                                    <span class="badge bg-warning">{{ $pemeriksaan->shift->shift }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-vertical">
                                                    <a href="{{ route('pemeriksaan-chemical.show', $pemeriksaan->uuid) }}" class="btn btn-sm btn-info" title="Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('pemeriksaan-chemical.edit', $pemeriksaan->uuid) }}" class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('pemeriksaan-chemical.destroy', $pemeriksaan->uuid) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection