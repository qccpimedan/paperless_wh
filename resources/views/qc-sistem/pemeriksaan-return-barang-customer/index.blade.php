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
                    <h3>Pemeriksaan Return Barang Dari Customer</h3>
                    <p class="text-subtitle text-muted">Daftar pemeriksaan return barang</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('return-barang.index') }}">Pemeriksaan Return Barang</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pemeriksaan Return Barang</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Daftar Pemeriksaan Return Barang</h5>
                    <a href="{{ route('return-barang.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Pemeriksaan
                    </a>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table1" style="white-space: nowrap;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th>Produk</th>
                                    <th>Kondisi</th>
                                    <th>Rekomendasi</th>
                                    <th>Plant</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemeriksaans as $index => $pemeriksaan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($pemeriksaan->tanggal)->format('d/m/Y') }}</td>
                                        <td>
                                            <strong>{{ $pemeriksaan->customer->nama_cust ?? '-' }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $pemeriksaan->produk->nama_produk ?? '-' }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $pemeriksaan->kondisi_produk }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ $pemeriksaan->rekomendasi }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $pemeriksaan->user->plant->plant ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('return-barang.show', $pemeriksaan->uuid) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('return-barang.edit', $pemeriksaan->uuid) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('return-barang.destroy', $pemeriksaan->uuid) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Tidak ada data</td>
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