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
                    <h3>Data Produk</h3>
                    <p class="text-subtitle text-muted">Kelola data produk sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Produk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                @if(session('updated_products') && count(session('updated_products')) > 0)
                    <div class="mt-2" style="max-height: 150px; overflow-y: auto;">
                        <small><strong>Daftar Produk:</strong></small>
                        <ul class="mb-0 small">
                            @foreach(session('updated_products') as $productName)
                                <li>{{ $productName }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
                    <h5 class="card-title mb-0">Daftar Produk</h5>
                    <div class="d-flex gap-2">
                        <!-- import produsen dan distributor by kategori produk -->
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#updatePDModal">
                                <i class="bi bi-pencil-square"></i> Import Produsen & Distributor
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#infoPDModal" title="Klik untuk informasi penggunaan">
                                <i class="bi bi-question-circle"></i>
                            </button>
                        </div>
                        @can('create_produks')
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bi bi-file-earmark-excel"></i> Import Excel
                        </button>
                        <a href="{{ route('produks.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Produk
                        </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="{{ route('produks.index') }}"
                           class="btn btn-sm {{ empty($selectedKategori) ? 'btn-primary' : 'btn-outline-primary' }}">
                            Semua
                        </a>
                        @foreach(($kategoriOptions ?? []) as $kat)
                            <a href="{{ route('produks.index', ['kategori_code' => $kat]) }}"
                               class="btn btn-sm {{ ($selectedKategori ?? null) === $kat ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $kat }}
                            </a>
                        @endforeach
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-center w-100" id="table-produk">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Produsen</th>
                                    <th>Distributor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal Informasi Penggunaan (Scenario B) -->
<div class="modal fade" id="infoPDModal" tabindex="-1" aria-labelledby="infoPDModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="infoPDModalLabel">
                    <i class="bi bi-info-circle"></i> Informasi & Panduan Fitur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Apa fungsi tombol ini?</h6>
                <p>Tombol ini digunakan khusus untuk <strong>melengkapi atau memperbarui</strong> data Produsen dan Distributor pada produk yang sudah terdaftar di sistem secara massal.</p>
                
                <hr>
                
                <h6>Langkah-langkah penggunaan:</h6>
                <div class="stepper">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary rounded-pill">1</span>
                        </div>
                        <div class="ms-3">
                            <strong>Download Data:</strong> Klik tombol, pilih kategori produk (misal: WHSE), lalu download file Excel-nya. File tersebut akan berisi daftar produk yang sudah ada di kategori tersebut.
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary rounded-pill">2</span>
                        </div>
                        <div class="ms-3">
                            <strong>Isi Produsen/Distributor:</strong> Buka file Excel, isi kolom Produsen dan Distributor. Jika lebih dari satu, pisahkan dengan tanda titik koma ( <strong>;</strong> ).
                            <br><small class="text-muted">Contoh: PT. Surya; PT. Maju</small>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary rounded-pill">3</span>
                        </div>
                        <div class="ms-3">
                            <strong>Upload Kembali:</strong> Simpan file Excel, lalu upload kembali melalui tombol "Import Update Data" di dalam modal yang sama.
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-3">
                    <small><strong>Penting:</strong> Jangan mengubah kolom "ID SISTEM" pada file Excel agar sistem dapat mengenali produk dengan benar.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Produsen/Distributor (Skenario B) -->
<div class="modal fade" id="updatePDModal" tabindex="-1" aria-labelledby="updatePDModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatePDModalLabel">
                    <i class="bi bi-pencil-square"></i> Lengkapi Produsen/Distributor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> 
                    <strong>Skenario Update:</strong>
                    <ol class="mb-0 mt-2">
                        <li>Pilih Kategori lalu download data eksis.</li>
                        <li>Lengkapi kolom Produsen & Distributor (gunakan pemisah <code>;</code> untuk memilih lebih dari 1).</li>
                        <li>Upload kembali file yang sudah diedit.</li>
                    </ol>
                </div>

                <div class="mb-4">
                    <label class="form-label">1. Pilih Kategori & Download</label>
                    <form action="{{ route('produks.export-update') }}" method="GET">
                        <select name="kategori_code" class="form-select mb-2" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach(($kategoriOptions ?? []) as $kat)
                                <option value="{{ $kat }}">{{ $kat }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-download"></i> Download Data Eksis
                        </button>
                    </form>
                </div>

                <hr>

                <div class="mt-4">
                    <label class="form-label">2. Upload File yang Sudah Diedit</label>
                    <form action="{{ route('produks.import-update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control mb-2" accept=".xlsx,.xls,.csv" required>
                        <button type="submit" class="btn btn-info text-white w-100">
                            <i class="bi bi-upload"></i> Import Update Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@can('create_produks')
<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="bi bi-file-earmark-excel"></i> Import Data Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('produks.import') }}" method="POST" enctype="multipart/form-data">
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
                            <a href="{{ route('produks.template') }}" class="btn btn-outline-primary w-100">
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
@endcan

@push('scripts')
<script>
$(document).ready(function() {
    $('#table-produk').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('produks.index') }}",
            data: function (d) {
                d.kategori_code = @json(request('kategori_code'));
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'id', orderable: false, searchable: false },
            { data: 'nama_produk', name: 'nama_produk' },
            { data: 'kategori_code', name: 'kategori_code' },
            { data: 'produsen', name: 'produsens.nama_produsen', orderable: false, searchable: false },
            { data: 'distributor', name: 'distributors.nama_distributor', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
@endsection