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
                    <h3>Data Barang</h3>
                    <p class="text-subtitle text-muted">Kelola data barang sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Barang</li>
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
                    <h5 class="card-title mb-0">Daftar Barang</h5>
                    <a href="{{ route('barangs.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Barang
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Barang</th>
                                    <!-- <th>Dibuat Oleh</th> -->
                                    <!-- <th>Role</th> -->
                                    <th>Plant</th>
                                    <!-- <th>Tanggal Dibuat</th> -->
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangs as $index => $barang)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $barang->nama_barang }}
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $barang->jumlah_barang ?? 0 }}</span>
                                        </td>
                                        <!-- <td>
                                            <strong>{{ $barang->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $barang->user->username }}</small>
                                        </td> -->
                                        <!-- <td>
                                            @if($barang->user->role)
                                                <span class="badge bg-success">{{ $barang->user->role->role }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Role</span>
                                            @endif
                                        </td> -->
                                        <td>
                                            @if($barang->user->plant)
                                                <span class="badge bg-info">{{ $barang->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </td>
                                        <!-- <td>
                                            {{ $barang->created_at->format('d/m/Y H:i') }}
                                        </td> -->
                                        <td>
                                            <div class="btn-vertical">
                                                <a href="{{ route('barangs.edit', $barang->uuid) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil" title="Edit Data"></i>
                                                </a>
                                                <form action="{{ route('barangs.destroy', $barang->uuid) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash" title="Hapus Data"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                                <p class="text-muted mt-2">Belum ada data barang</p>
                                                <a href="{{ route('barangs.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> Tambah Barang Pertama
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