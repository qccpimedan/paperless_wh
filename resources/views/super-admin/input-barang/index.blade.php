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
            <!-- Header Actions Card -->
            <div class="card mb-4 shadow-sm border">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0 text-dark fw-bold">
                        <i class="bi bi-box-seam me-2 text-primary"></i>Daftar Barang
                    </h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                        </button>
                        <a href="{{ route('barangs.create') }}" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Barang
                        </a>
                    </div>
                </div>
            </div>

            @php
                // Group barangs by area name
                $groupedBarangs = $barangs->groupBy(function($barang) {
                    return $barang->area ? $barang->area->nama_area : 'Tanpa Area';
                });
            @endphp

            @forelse($groupedBarangs as $areaName => $items)
                <div class="card border shadow-sm mb-4">
                    <div class="card-header bg-light py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary fw-bold">
                            <i class="bi bi-tag-fill me-2 text-primary"></i>{{ $areaName }}
                            <span class="badge bg-light-primary text-primary ms-2 fw-semibold px-2 py-1">{{ $items->count() }} Item</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th style="width: 50%" class="text-start">Nama Barang</th>
                                        <th style="width: 15%">Jumlah Barang</th>
                                        <th style="width: 15%">Plant</th>
                                        <th style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $index => $barang)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-start fw-semibold text-dark">
                                                {{ $barang->nama_barang }}
                                            </td>
                                            <td>
                                                <span class="badge bg-primary px-3 py-1.5 fw-semibold">{{ $barang->jumlah_barang ?? 0 }}</span>
                                            </td>
                                            <td>
                                                @if($barang->user && $barang->user->plant)
                                                    <span class="badge bg-info px-2 py-1 fw-semibold">{{ $barang->user->plant->plant }}</span>
                                                @else
                                                    <span class="badge bg-secondary px-2 py-1 fw-semibold">No Plant</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('barangs.edit', $barang->uuid) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit Data">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('barangs.destroy', $barang->uuid) }}" 
                                                          method="POST" 
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Data">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Belum ada data barang</p>
                        <a href="{{ route('barangs.create') }}" class="btn btn-primary btn-sm px-4">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Barang Pertama
                        </a>
                    </div>
                </div>
            @endforelse

            <!-- Pagination Links -->
            <div class="d-flex justify-content-end mt-3">
                {{ $barangs->appends(request()->query())->links() }}
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