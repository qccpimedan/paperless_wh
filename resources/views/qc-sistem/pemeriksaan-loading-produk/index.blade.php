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
                    <h3>Data Pemeriksaan Loading Produk</h3>
                    <p class="text-subtitle text-muted">Kelola data pemeriksaan loading produk</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pemeriksaan Loading Produk</li>
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
                    <h5 class="card-title mb-0">Daftar Pemeriksaan Loading Produk</h5>
                    <a href="{{ route('pemeriksaan-loading-produk.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Data
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table1" style="white-space: nowrap;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Tujuan Pengiriman</th>
                                    <th>Kendaraan</th>
                                    <th>Supir</th>
                                    <th>Produk</th>
                                    <th>Kondisi</th>
                                    <th>Plant</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemeriksaans as $index => $pemeriksaan)
                                    <tr>
                                        <td>{{ $pemeriksaans->firstItem() + $index }}</td>
                                        <td>{{ $pemeriksaan->tanggal->format('d/m/Y') }}</td>
                                        <td>
                                            @if($pemeriksaan->tujuanPengiriman)
                                                <span class="badge bg-info">{{ $pemeriksaan->tujuanPengiriman->nama_tujuan }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($pemeriksaan->kendaraan)
                                                {{ $pemeriksaan->kendaraan->jenis_kendaraan }} - {{ $pemeriksaan->kendaraan->no_kendaraan }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($pemeriksaan->supir)
                                                {{ $pemeriksaan->supir->nama_supir }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($pemeriksaan->produk_data && count($pemeriksaan->produk_data) > 0)
                                                @php
                                                    $firstProduk = $pemeriksaan->produk_data[0];
                                                    $produkName = \App\Models\Produk::find($firstProduk['id_produk'])?->nama_produk ?? 'Produk tidak ditemukan';
                                                    $totalProduk = count($pemeriksaan->produk_data);
                                                @endphp
                                                {{ $produkName }} <span class="badge bg-info">{{ $totalProduk }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($pemeriksaan->kondisi_produk)
                                                <span class="badge bg-secondary">{{ $pemeriksaan->kondisi_produk }}</span>
                                            @else
                                                -
                                            @endif
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
                                                <a href="{{ route('pemeriksaan-loading-produk.show', $pemeriksaan->uuid) }}" 
                                                   class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('pemeriksaan-loading-produk.edit', $pemeriksaan->uuid) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('pemeriksaan-loading-produk.destroy', $pemeriksaan->uuid) }}" 
                                                      method="POST" style="display: inline-block;"
                                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                                        <td colspan="9" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                                <p class="text-muted mt-2 mb-3">Belum ada data pemeriksaan loading produk</p>
                                                <a href="{{ route('pemeriksaan-loading-produk.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> Tambah Data Pertama
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