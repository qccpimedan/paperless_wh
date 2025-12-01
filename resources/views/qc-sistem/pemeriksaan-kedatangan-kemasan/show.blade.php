@extends('layouts.app')
@section('container')
<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h3>Detail Pemeriksaan Kedatangan Kemasan</h3>
                </div>
                <div class="col-12 col-md-6">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}">Pemeriksaan</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Detail Pemeriksaan</h4>
                    <div>
                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.edit', $pemeriksaanKedatanganKemasan->uuid) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informasi Dasar -->
                    <h5 class="text-primary">Informasi Dasar</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Tanggal:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->tanggal->format('d/m/Y') }}</td></tr>
                                <!-- <tr><td><strong>Jenis Pemeriksaan:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->jenis_pemeriksaan ?? '-' }}</td></tr> -->
                                <tr><td><strong>No. PO:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->no_po ?? '-' }}</td></tr>
                                <tr><td><strong>Status:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->status === 'Release')
                                        <span class="badge bg-success">Release</span>
                                    @else
                                        <span class="badge bg-warning">Hold</span>
                                    @endif
                                </td></tr>
                                <tr><td><strong>Segel/Gembok:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->segel_gembok)
                                        <span class="badge bg-success">Ya</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </td></tr>
                                <tr><td><strong>No. Segel:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->no_segel)
                                        {{ $pemeriksaanKedatanganKemasan->no_segel }}
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Nama Supir:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->nama_supir ?? '-' }}</td></tr>
                                <tr><td><strong>Jenis Mobil:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->jenis_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>No. Mobil:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->no_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>Shift:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->shift)
                                        <span class="badge bg-primary">{{ $pemeriksaanKedatanganKemasan->shift->shift }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                    </div>

                    <!-- Kondisi Mobil -->
                    <h5 class="text-primary">Kondisi Mobil Pengangkut</h5>
                    @if($pemeriksaanKedatanganKemasan->kondisi_mobil)
                        <div class="row mb-4">
                            @php
                                $kondisiMobil = [
                                    'bersih' => 'Bersih', 'bebas_hama' => 'Bebas dari hama',
                                    'tidak_kondensasi' => 'Tidak Kondensasi', 'bebas_produk_halal' => 'Bebas dari Produk Halal',
                                    'tidak_berbau' => 'Tidak Berbau', 'tidak_ada_sampah' => 'Tidak ada sampah',
                                    'tidak_ada_mikroba' => 'Tidak ada mikroba', 'lampu_cover_utuh' => 'Lampu Cover utuh',
                                    'pallet_utuh' => 'Pallet utuh', 'tertutup_rapat' => 'Tertutup rapat',
                                    'bebas_kontaminan' => 'Bebas kontaminan'
                                ];
                            @endphp
                            @foreach($kondisiMobil as $key => $label)
                                <div class="col-md-4 mb-2">
                                    @if(isset($pemeriksaanKedatanganKemasan->kondisi_mobil[$key]) && $pemeriksaanKedatanganKemasan->kondisi_mobil[$key])
                                        <span class="badge bg-success">✓</span>
                                    @else
                                        <span class="badge bg-danger">✗</span>
                                    @endif
                                    {{ $label }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Informasi Kemasan & Supplier -->
                    <h5 class="text-primary">Informasi Kemasan & Supplier</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <!-- <tr><td width="40%"><strong>Nama Bahan Kemasan:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->nama_bahan_kemasan ?? '-' }}</td></tr>
                                <tr><td><strong>Bahan Terkait:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->bahan)
                                        <span class="badge bg-info">{{ $pemeriksaanKedatanganKemasan->bahan->nama_bahan }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr> -->
                                <tr><td><strong>Produsen:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->produsen ?? '-' }}</td></tr>
                                <tr><td><strong>Distributor:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->distributor ?? '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td width="40%"><strong>Kode Produksi:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->kode_produksi ?? '-' }}</td></tr>
                                <tr><td><strong>Jumlah Datang:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->jumlah_datang ?? '-' }}</td></tr>
                                <tr><td><strong>Jumlah Sampling:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->jumlah_sampling ?? '-' }}</td></tr>
                            </table>
                        </div>
                        @if($pemeriksaanKedatanganKemasan->spesifikasi)
                            <div class="col-12 mt-3">
                                <strong>Spesifikasi:</strong>
                                <p class="mt-2 p-3 bg-light rounded">{{ $pemeriksaanKedatanganKemasan->spesifikasi }}</p>
                            </div>
                        @endif
                        
                    </div>

                    <!-- Kondisi Fisik & Dokumentasi -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-primary">Kondisi Fisik</h5>
                            @if($pemeriksaanKedatanganKemasan->kondisi_fisik)
                                @php
                                    $kondisiFisik = [
                                        'penampakan' => 'Penampakan',
                                        'sealing' => 'Sealing',
                                        'cetakan' => 'Cetakan'
                                    ];
                                @endphp
                                @foreach($kondisiFisik as $key => $label)
                                    <div class="mb-2">
                                        @if(isset($pemeriksaanKedatanganKemasan->kondisi_fisik[$key]) && $pemeriksaanKedatanganKemasan->kondisi_fisik[$key])
                                            <span class="badge bg-success me-2">✓</span>
                                        @else
                                            <span class="badge bg-danger me-2">✗</span>
                                        @endif
                                        {{ $label }}
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Tidak ada data kondisi fisik</p>
                            @endif
                            
                            @if($pemeriksaanKedatanganKemasan->ketebalan_micron)
                                <div class="mt-3 p-2 bg-light rounded">
                                    <strong>Ketebalan:</strong> {{ $pemeriksaanKedatanganKemasan->ketebalan_micron }} Micron
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="text-primary">Dokumentasi</h5>
                            <div class="mb-2">
                                @if($pemeriksaanKedatanganKemasan->logo_halal)
                                    <span class="badge bg-success me-2">✓</span>
                                @else
                                    <span class="badge bg-danger me-2">✗</span>
                                @endif
                                Logo Halal
                            </div>
                            <div class="mb-2">
                                @if($pemeriksaanKedatanganKemasan->dokumen_halal)
                                    <span class="badge bg-success me-2">✓</span>
                                @else
                                    <span class="badge bg-danger me-2">✗</span>
                                @endif
                                Persyaratan Dokumen: Halal (berlaku)
                            </div>
                            <div class="mb-2">
                                @if($pemeriksaanKedatanganKemasan->coa)
                                    <span class="badge bg-success me-2">✓</span>
                                @else
                                    <span class="badge bg-danger me-2">✗</span>
                                @endif
                                COA (Certificate of Analysis)
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary">Informasi Tambahan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr><td width="40%"><strong>Dibuat Oleh:</strong></td><td>
                                            <strong>{{ $pemeriksaanKedatanganKemasan->user->name }}</strong>
                                            <br><small class="text-muted">{{ $pemeriksaanKedatanganKemasan->user->username }}</small>
                                        </td></tr>
                                        <!-- <tr><td><strong>Plant:</strong></td><td>
                                            @if($pemeriksaanKedatanganKemasan->user->plant)
                                                <span class="badge bg-info">{{ $pemeriksaanKedatanganKemasan->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </td></tr> -->
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr><td width="40%"><strong>Dibuat Pada:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->created_at->format('d/m/Y H:i:s') }}</td></tr>
                                        <tr><td><strong>Diupdate Pada:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->updated_at->format('d/m/Y H:i:s') }}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection