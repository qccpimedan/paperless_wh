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
                    <h3>Detail Golden Sample Report</h3>
                    <p class="text-subtitle text-muted">Lihat detail report</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('golden-sample-reports.index') }}">Golden Sample Report</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informasi Dasar</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Sample Type</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        <p>{{ $goldenSampleReport->sample_type }}</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Shift</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="badge bg-info">{{ $goldenSampleReport->shift->shift ?? 'Shift tidak ditemukan' }}</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Tanggal</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="badge bg-info">{{ $goldenSampleReport->tanggal }}</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Collection Date (Bulan)</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        @if($goldenSampleReport->collection_date_from && $goldenSampleReport->collection_date_to)
                                            <p>{{ $goldenSampleReport->collection_date_from }} - {{ $goldenSampleReport->collection_date_to }}</p>
                                        @else
                                            <p class="text-muted">-</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Plant</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        @if($goldenSampleReport->id_plant && $goldenSampleReport->plant)
                                            <span class="badge bg-success">{{ $goldenSampleReport->plant->plant }}</span>
                                        @elseif($goldenSampleReport->plant_manual)
                                            <span class="badge bg-warning">{{ $goldenSampleReport->plant_manual }}</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Sample Storage</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        @foreach($goldenSampleReport->sample_storage as $storage)
                                            <span class="badge bg-primary me-1">{{ $storage }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Dibuat Oleh</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        <p>{{ $goldenSampleReport->user->name }}</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Dibuat Pada</strong></label>
                                    </div>
                                    <div class="col-md-9">
                                        <p>{{ $goldenSampleReport->created_at ? $goldenSampleReport->created_at->format('d-M-Y H:i:s') : '-' }}</p>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row match-height mt-3">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Sampel ({{ count($goldenSampleReport->samples) }} sampel)</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @forelse($goldenSampleReport->samples as $index => $sample)
                                    <div class="card mb-3 p-3" style="border-left: 4px solid #0d6efd;">
                                        <h6 class="mb-3"><strong>Sampel #{{ $index + 1 }}</strong></h6>
                                        
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Deskripsi</strong></label>
                                                <div>
                                                    @foreach($sample['id_deskripsi'] as $deskripsiUuid)
                                                        @php
                                                            $deskripsi = \App\Models\InputDeskripsi::where('uuid', $deskripsiUuid)->first();
                                                        @endphp
                                                        @if($deskripsi)
                                                            <span class="badge bg-light-primary">{{ $deskripsi->nama_deskripsi }}</span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Supplier</strong></label>
                                                <p>{{ $sample['id_supplier'] }}</p>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Kode Produksi</strong></label>
                                                <p>{{ $sample['kode_produksi'] }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Best Before</strong></label>
                                                <p>{{ \Carbon\Carbon::parse($sample['best_before'])->format('d M Y') }}</p>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <label class="form-label"><strong>QTY (gram)</strong></label>
                                                <p>{{ $sample['qty'] }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label"><strong>Diserahkan</strong></label>
                                                <p>{{ $sample['diserahkan'] }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label"><strong>Diterima</strong></label>
                                                <p>{{ $sample['diterima'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Tidak ada data sampel</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <a href="{{ route('golden-sample-reports.edit', $goldenSampleReport->uuid) }}" class="btn btn-warning me-1">Edit</a>
                    <a href="{{ route('golden-sample-reports.index') }}" class="btn btn-light-secondary">Kembali</a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
