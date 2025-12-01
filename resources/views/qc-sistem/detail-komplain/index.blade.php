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
                    <h3>Detail Komplain</h3>
                    <p class="text-subtitle text-muted">Kelola detail komplain produk</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Komplain</li>
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
                    <h5 class="card-title mb-0">Daftar Detail Komplain</h5>
                    <a href="{{ route('detail-komplain.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Komplain
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table1" style="white-space:nowrap;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Supplier</th>
                                    <th>Tanggal</th>
                                    <th>No. PO</th>
                                    <th>Produk</th>
                                    <th>Dokumentasi</th>
                                    <th>Upload Supplier</th>
                                    <th>Plan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($komplains as $index => $komplain)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $komplain->nama_supplier }}</strong></td>
                                        <td>{{ $komplain->tanggal_kedatangan->format('d-m-Y') }}</td>
                                        <td>{{ $komplain->no_po }}</td>
                                        <td>{{ $komplain->nama_produk }}</td>
                                        <td>
                                            @if($komplain->dokumentasi)
                                                <a href="{{ asset('storage/' . $komplain->dokumentasi) }}" 
                                                   target="_blank" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Lihat
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($komplain->upload_suplier)
                                                <a href="{{ asset('storage/' . $komplain->upload_suplier) }}" 
                                                   target="_blank" class="btn btn-sm btn-success">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                                        data-bs-target="#uploadModal{{ $komplain->uuid }}">
                                                    <i class="bi bi-upload"></i> Upload
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if($komplain->user->plant)
                                                <span class="badge bg-primary">{{ $komplain->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-vertical">
                                                <a href="{{ route('detail-komplain.show', $komplain->uuid) }}" 
                                                   class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('detail-komplain.edit', $komplain->uuid) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('detail-komplain.export-pdf', $komplain->uuid) }}" 
                                                   class="btn btn-sm btn-secondary" title="Export PDF" target="_blank">
                                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                                </a>
                                                <form action="{{ route('detail-komplain.destroy', $komplain->uuid) }}" 
                                                      method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                                            onclick="return confirm('Yakin ingin menghapus?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Upload Supplier -->
                                    <div class="modal fade" id="uploadModal{{ $komplain->uuid }}" tabindex="-1" aria-labelledby="uploadLabel{{ $komplain->uuid }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="uploadLabel{{ $komplain->uuid }}">
                                                        Upload File Supplier - {{ $komplain->nama_supplier }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('detail-komplain.upload-suplier', $komplain->uuid) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="upload_suplier{{ $komplain->uuid }}" class="form-label">
                                                                File Supplier <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="file" class="form-control" 
                                                                   id="upload_suplier{{ $komplain->uuid }}" 
                                                                   name="upload_suplier" 
                                                                   accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                                                            <small class="text-muted">Format: PDF, Word (.doc, .docx), Excel (.xls, .xlsx) | Max 5MB</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Upload</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1"></i>
                                            <p class="mt-2">Belum ada data komplain</p>
                                            <a href="{{ route('detail-komplain.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle"></i> Tambah Komplain Pertama
                                            </a>
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
