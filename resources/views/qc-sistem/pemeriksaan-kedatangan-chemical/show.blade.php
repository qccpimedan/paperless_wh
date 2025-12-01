@extends('layouts.app')
@section('container')
<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h3>Detail Pemeriksaan Kedatangan Chemical</h3>
                </div>
                <div class="col-12 col-md-6">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-chemical.index') }}">Pemeriksaan Chemical</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Detail Pemeriksaan Chemical</h4>
                    <div>
                        <a href="{{ route('pemeriksaan-chemical.edit', $pemeriksaanChemical->uuid) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('pemeriksaan-chemical.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informasi Dasar -->
                    <h5 class="text-primary">Informasi Dasar</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Tanggal:</strong></td><td>{{ $pemeriksaanChemical->tanggal->format('d/m/Y') }}</td></tr>
                                <tr><td><strong>Status:</strong></td><td>
                                    @if($pemeriksaanChemical->status === 'Release')
                                        <span class="badge bg-success">Release</span>
                                    @else
                                        <span class="badge bg-danger">Hold</span>
                                    @endif
                                </td></tr>
                                <tr><td><strong>Segel/Gembok:</strong></td><td>
                                    @if($pemeriksaanChemical->segel_gembok)
                                        <span class="badge bg-success">Ya</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </td></tr>
                                <tr><td><strong>No. Segel:</strong></td><td>
                                    @if($pemeriksaanChemical->no_segel)
                                        {{ $pemeriksaanChemical->no_segel }}
                                    @else
                                        -
                                    @endif
                                </td></tr>
                                <tr><td><strong>Shift:</strong></td><td>
                                    @if($pemeriksaanChemical->shift)
                                        <span class="badge bg-primary">{{ $pemeriksaanChemical->shift->shift }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Nama Supir:</strong></td><td>{{ $pemeriksaanChemical->nama_supir ?? '-' }}</td></tr>
                                <tr><td><strong>Jenis Mobil:</strong></td><td>{{ $pemeriksaanChemical->jenis_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>No. Mobil:</strong></td><td>{{ $pemeriksaanChemical->no_mobil ?? '-' }}</td></tr>
                                <!-- <tr><td><strong>Plant:</strong></td><td>
                                    @if($pemeriksaanChemical->user && $pemeriksaanChemical->user->plant)
                                        <span class="badge bg-info">{{ $pemeriksaanChemical->user->plant->plant }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr> -->
                            </table>
                        </div>
                    </div>

                    <!-- Kondisi Mobil -->
                    <h5 class="text-primary">Kondisi Mobil Pengangkut</h5>
                    @if($pemeriksaanChemical->kondisi_mobil)
                        <div class="row mb-4">
                            @php
                                $kondisiMobil = [
                                    'bersih' => 'Bersih', 
                                    'bebas_hama' => 'Bebas dari hama',
                                    'tidak_kondensasi' => 'Tidak Kondensasi', 
                                    'bebas_produk_halal' => 'Bebas dari Produk Non Halal',
                                    'tidak_berbau' => 'Tidak Berbau', 
                                    'tidak_ada_sampah' => 'Tidak ada sampah',
                                    'tidak_ada_mikroba' => 'Tidak ada pertumbuhan mikroba',
                                    'lampu_cover_utuh' => 'Lampu dan Cover tidak pecah',
                                    'pallet_utuh' => 'Pallet / Alas Utuh',
                                    'tertutup_rapat' => 'Tertutup rapat/tidak bocor',
                                    'bebas_kontaminan' => 'Bebas dari Kontaminan'
                                ];
                            @endphp
                            @foreach($kondisiMobil as $key => $label)
                                <div class="col-md-4 mb-2">
                                    @if(isset($pemeriksaanChemical->kondisi_mobil[$key]) && $pemeriksaanChemical->kondisi_mobil[$key])
                                        <span class="badge bg-success">✓</span>
                                    @else
                                        <span class="badge bg-danger">✗</span>
                                    @endif
                                    {{ $label }}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Data tidak tersedia</p>
                    @endif

                    <!-- Informasi Produk -->
                    <h5 class="text-primary">Informasi Produk</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Nama Chemical:</strong></td><td>
                                    @if($pemeriksaanChemical->chemical)
                                        <span class="badge bg-info">{{ $pemeriksaanChemical->chemical->nama_chemical }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                                <tr><td><strong>Kondisi Chemical:</strong></td><td>
                                    @if($pemeriksaanChemical->kondisi_chemical)
                                        <span class="badge bg-secondary">{{ $pemeriksaanChemical->kondisi_chemical }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                    </div>

                    <!-- Detail Pemeriksaan -->
                    <h5 class="text-primary">Detail Pemeriksaan</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Produsen:</strong></td><td>
                                    @if($pemeriksaanChemical->produsen)
                                        {{ $pemeriksaanChemical->produsen->nama_produsen }}
                                    @else
                                        -
                                    @endif
                                </td></tr>
                                <tr><td><strong>Negara Produsen:</strong></td><td>{{ $pemeriksaanChemical->negara_produsen ?? '-' }}</td></tr>
                                <tr><td><strong>Distributor:</strong></td><td>
                                    @if($pemeriksaanChemical->distributor)
                                        {{ $pemeriksaanChemical->distributor->nama_distributor }}
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Kode Produksi:</strong></td><td>{{ $pemeriksaanChemical->kode_produksi ?? '-' }}</td></tr>
                                <tr><td><strong>Expire Date:</strong></td><td>{{ $pemeriksaanChemical->expire_date ? $pemeriksaanChemical->expire_date->format('d/m/Y') : '-' }}</td></tr>
                                <tr><td><strong>Jumlah Datang:</strong></td><td>{{ $pemeriksaanChemical->jumlah_datang ?? '-' }} kg/liter</td></tr>
                                <tr><td><strong>Jumlah Sampling:</strong></td><td>{{ $pemeriksaanChemical->jumlah_sampling ?? '-' }}</td></tr>
                            </table>
                        </div>
                    </div>

                    <!-- Kondisi Fisik -->
                    <h5 class="text-primary">Kondisi Fisik</h5>
                    @if($pemeriksaanChemical->kondisi_fisik)
                        <div class="row mb-4">
                            @php
                                $kondisiFisik = [
                                    'kemasan' => 'Kemasan',
                                    'warna' => 'Warna'
                                ];
                            @endphp
                            @foreach($kondisiFisik as $key => $label)
                                <div class="col-md-6 mb-2">
                                    @if(isset($pemeriksaanChemical->kondisi_fisik[$key]) && $pemeriksaanChemical->kondisi_fisik[$key])
                                        <span class="badge bg-success me-2">✓</span>
                                    @else
                                        <span class="badge bg-danger me-2">✗</span>
                                    @endif
                                    {{ $label }}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Data tidak tersedia</p>
                    @endif

                    <!-- Dokumen & Sertifikasi -->
                    <h5 class="text-primary">Dokumen & Sertifikasi</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-2">
                                @if($pemeriksaanChemical->persyaratan_dokumen_halal)
                                    <span class="badge bg-success me-2">✓</span>
                                @else
                                    <span class="badge bg-danger me-2">✗</span>
                                @endif
                                Persyaratan Dokumen - Halal (berlaku)
                            </div>
                            <div class="mb-2">
                                @if($pemeriksaanChemical->coa)
                                    <span class="badge bg-success me-2">✓</span>
                                @else
                                    <span class="badge bg-danger me-2">✗</span>
                                @endif
                                COA (Certificate of Analysis)
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    @if($pemeriksaanChemical->keterangan)
                        <h5 class="text-primary">Keterangan</h5>
                        <div class="alert alert-light">
                            {{ $pemeriksaanChemical->keterangan }}
                        </div>
                    @endif

                    <!-- Info Audit -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <small class="text-muted">
                                <strong>Dibuat:</strong> {{ $pemeriksaanChemical->created_at->format('d/m/Y H:i') }} | 
                                <strong>Diupdate:</strong> {{ $pemeriksaanChemical->updated_at->format('d/m/Y H:i') }}
                                @if($pemeriksaanChemical->user)
                                    | <strong>Oleh:</strong> {{ $pemeriksaanChemical->user->name }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection