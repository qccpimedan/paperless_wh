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

        @if(session('import_errors'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Import selesai dengan beberapa error:</strong>
                <ul class="mb-0">
                    @foreach(session('import_errors') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Barang</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bi bi-file-earmark-excel"></i> Import Excel
                        </button>
                        <a href="{{ route('barangs.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Barang
                        </a>
                    </div>
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

<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="bi bi-file-earmark-excel"></i> Import Data Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('barangs.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Panduan Import:</strong>
                        <ol class="mb-0 mt-2">
                            <li>Download template Excel terlebih dahulu</li>
                            <li>Isi data sesuai format template</li>
                            <li>Upload file Excel yang sudah diisi</li>
                            <li>Format file yang diterima: .xlsx, .xls, .csv</li>
                        </ol>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Download Template</label>
                        <div>
                            <a href="{{ route('barangs.template') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-download"></i> Download Template Excel
                            </a>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">File Excel</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Kolom wajib: nama_barang, jumlah_barang</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection