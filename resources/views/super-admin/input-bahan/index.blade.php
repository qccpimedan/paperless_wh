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
                    <h3>Data Bahan</h3>
                    <p class="text-subtitle text-muted">Kelola data bahan sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Bahan</li>
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
                    <h5 class="card-title mb-0">Daftar Bahan</h5>
                    <a href="{{ route('bahans.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Bahan
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Bahan</th>
                                    <!-- <th>Dibuat Ole</th> -->
                                    <th>Plant</th>
                                    <!-- <th>Tanggal Dibuat</th> -->
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bahans as $index => $bahan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $bahan->nama_bahan }}</strong>
                                        </td>
                                        <!-- <td>
                                            <strong>{{ $bahan->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $bahan->user->username }}</small>
                                        </td> -->
                                        <td>
                                            @if($bahan->user->plant)
                                                <span class="badge bg-info">{{ $bahan->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </td>
                                        <!-- <td>
                                            {{ $bahan->created_at->format('d/m/Y H:i') }}
                                        </td> -->
                                        <td>
                                            <div class="btn-vertical">
                                                <a href="{{ route('bahans.edit', $bahan->uuid) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit Data">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('bahans.destroy', $bahan->uuid) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Yakin ingin menghapus bahan {{ $bahan->nama_bahan }}?')">
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
                                        <td colspan="6" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                                <p class="text-muted mt-2 mb-3">Belum ada data bahan</p>
                                                <a href="{{ route('bahans.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> Tambah Bahan Pertama
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable if available
    if (typeof $('#table1').DataTable === 'function') {
        $('#table1').DataTable({
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    }
});
</script>
@endsection