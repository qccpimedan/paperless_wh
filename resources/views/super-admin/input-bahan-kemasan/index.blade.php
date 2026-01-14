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
                    <h3>Data Bahan Kemasan</h3>
                    <p class="text-subtitle text-muted">Kelola data bahan kemasan sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Bahan Kemasan</li>
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
                    <h5 class="card-title mb-0">Daftar Bahan Kemasan</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bi bi-file-earmark-excel"></i> Import Excel
                        </button>
                        <a href="{{ route('bahan-kemasans.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Bahan Kemasan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Distributor</th>
                                    <th>Produsen</th>
                                    <th>Nama Kemasan</th>
                                    <th>Kategori</th>
                                    <th>Plant</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bahanKemasans as $index => $bahanKemasan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($bahanKemasan->distributors && $bahanKemasan->distributors->count() > 0)
                                                {{ $bahanKemasan->distributors->pluck('nama_distributor')->implode(', ') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($bahanKemasan->produsens && $bahanKemasan->produsens->count() > 0)
                                                {{ $bahanKemasan->produsens->pluck('nama_produsen')->implode(', ') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $bahanKemasan->nama_kemasan }}</strong>
                                        </td>
                                        <td>
                                            {{ $bahanKemasan->kategori_code ?? '-' }}
                                        </td>
                                        <td>
                                            @if($bahanKemasan->user && $bahanKemasan->user->plant)
                                                <span class="badge bg-info">{{ $bahanKemasan->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-vertical">
                                                <a href="{{ route('bahan-kemasans.edit', $bahanKemasan->uuid) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit Data">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('bahan-kemasans.destroy', $bahanKemasan->uuid) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Yakin ingin menghapus bahan kemasan {{ $bahanKemasan->nama_kemasan }}?')">
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
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                                <p class="text-muted mt-2 mb-3">Belum ada data bahan kemasan</p>
                                                <a href="{{ route('bahan-kemasans.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> Tambah Bahan Kemasan Pertama
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

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="bi bi-file-earmark-excel"></i> Import Data Bahan Kemasan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('bahan-kemasans.import') }}" method="POST" enctype="multipart/form-data">
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
                            <a href="{{ route('bahan-kemasans.template') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-download"></i> Download Template Excel
                            </a>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File Excel <span class="text-danger">*</span></label>
                        <input type="file" 
                               name="file" 
                               id="file" 
                               class="form-control" 
                               accept=".xlsx,.xls,.csv" 
                               required>
                        <small class="text-muted">Format: .xlsx, .xls, atau .csv (Max: 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload"></i> Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection