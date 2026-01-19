@extends('layouts.app')

@section('title', 'Pemeriksaan Produk Finish Good')

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
                    <h3>Pemeriksaan Produk Finish Good</h3>
                    <p class="text-subtitle text-muted">Daftar data pemeriksaan produk finish good</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pemeriksaan Produk Finish Good</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Data Pemeriksaan Produk Finish Good</h4>
                            @can('create_pemeriksaan_produk_finish_good')
                                <a href="{{ route('pemeriksaan-produk-finish-good.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Data
                                </a>
                            @endcan
                            @cannot('create_pemeriksaan_produk_finish_good')
                                <a href="{{ route('pemeriksaan-produk-finish-good.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Data
                                </a>
                            @endcannot
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped text-center" id="table1" style="white-space: nowrap;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Shift</th>
                                        <th>Plant</th>
                                        <th>Nama Produk</th>
                                        <th>Kode Produksi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pemeriksaans as $index => $p)
                                        <tr>
                                            <td>{{ $pemeriksaans->firstItem() + $index }}</td>
                                            <td>{{ optional($p->tanggal)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($p->shift)
                                                    <span class="badge bg-primary">{{ $p->shift->shift }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $p->user && $p->user->plant ? $p->user->plant->plant : '-' }}</td>
                                            <td>
                                                @php
                                                    $idProdukArray = is_array($p->id_produk_array) ? array_values(array_filter($p->id_produk_array, function ($v) {
                                                        return $v !== null && $v !== '';
                                                    })) : [];
                                                    $namaProdukArray = array_values(array_filter(array_map(function ($id) use ($produkNamaById) {
                                                        $id = $id ? (int) $id : null;
                                                        return $id && isset($produkNamaById[$id]) ? $produkNamaById[$id] : null;
                                                    }, $idProdukArray)));
                                                @endphp
                                                @if(count($namaProdukArray) > 0)
                                                    @foreach($namaProdukArray as $name)
                                                        <span class="badge bg-info">{{ $name }}</span><br>
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $kodeProduksiArray = is_array($p->kode_produksi_array) ? array_values(array_filter($p->kode_produksi_array, function ($v) {
                                                        return $v !== null && $v !== '';
                                                    })) : [];
                                                @endphp
                                                @if(count($kodeProduksiArray) > 0)
                                                    {{ implode(', ', $kodeProduksiArray) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('pemeriksaan-produk-finish-good.show', $p->uuid) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                                <a href="{{ route('pemeriksaan-produk-finish-good.edit', $p->uuid) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                                <form action="{{ route('pemeriksaan-produk-finish-good.destroy', $p->uuid) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
