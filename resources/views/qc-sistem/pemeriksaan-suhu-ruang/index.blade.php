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
                    <h3>Data Pemeriksaan Suhu Ruang</h3>
                    <p class="text-subtitle text-muted">Kelola data pemeriksaan suhu ruang</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Pemeriksaan Suhu Ruang</li>
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
                    <h5 class="card-title mb-0">Daftar Pemeriksaan Suhu Ruang</h5>
                    <a href="{{ route('pemeriksaan-suhu-ruang.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Buat Pemeriksaan
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table1" style="white-space:nowrap;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Area</th>
                                    <th>Shift</th>
                                    <th>Plant</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemeriksaans as $index => $pemeriksaan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pemeriksaan->tanggal->format('d/m/Y') }}</td>
                                        <td>
                                            <strong>{{ $pemeriksaan->produk->nama_bahan }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $pemeriksaan->area->nama_area }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $pemeriksaan->shift->shift }}</span>
                                        </td>
                                        <td>
                                            @if($pemeriksaan->user->plant)
                                                <span class="badge bg-primary">{{ $pemeriksaan->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-vertical">
                                                <a href="{{ route('pemeriksaan-suhu-ruang.show', $pemeriksaan->uuid) }}" 
                                                   class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('pemeriksaan-suhu-ruang.edit', $pemeriksaan->uuid) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit Data">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('pemeriksaan-suhu-ruang.edit', $pemeriksaan->uuid) }}?edit_per_2jam=1" 
                                                   class="btn btn-sm btn-success" title="Edit Per 2 Jam">
                                                    <i class="bi bi-hourglass-bottom"></i>
                                                </a>
                                                <a href="{{ route('pemeriksaan-suhu-ruang.history', $pemeriksaan->uuid) }}" 
                                                   class="btn btn-sm btn-secondary" title="Lihat History">
                                                    <i class="bi bi-clock-history"></i>
                                                </a>
                                                <form action="{{ route('pemeriksaan-suhu-ruang.destroy', $pemeriksaan->uuid) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Yakin ingin menghapus pemeriksaan ini?')">
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
                                                <p class="text-muted mt-2 mb-3">Belum ada data pemeriksaan</p>
                                                <a href="{{ route('pemeriksaan-suhu-ruang.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> Buat Pemeriksaan Pertama
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
