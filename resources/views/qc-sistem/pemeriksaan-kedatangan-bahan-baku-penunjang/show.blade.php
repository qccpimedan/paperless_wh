@extends('layouts.app')
@section('container')
<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h3>Detail Pemeriksaan Kedatangan Bahan Baku Penunjang</h3>
                </div>
                <div class="col-12 col-md-6">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-bahan-baku.index') }}">Pemeriksaan</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Detail Pemeriksaan Bahan Baku Penunjang</h4>
                    <div>
                        <a href="{{ route('pemeriksaan-bahan-baku.edit', $pemeriksaanBahanBaku->uuid) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('pemeriksaan-bahan-baku.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informasi Dasar -->
                    <h5 class="text-primary">Informasi Dasar</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Tanggal:</strong></td><td>{{ $pemeriksaanBahanBaku->tanggal->format('d/m/Y') }}</td></tr>
                                <!-- <tr><td><strong>Jenis Pemeriksaan:</strong></td><td>{{ $pemeriksaanBahanBaku->jenis_pemeriksaan ?? '-' }}</td></tr> -->
                                <tr><td><strong>No. PO:</strong></td><td>{{ $pemeriksaanBahanBaku->no_po ?? '-' }}</td></tr>
                                <tr><td><strong>Status:</strong></td><td>
                                    @if($pemeriksaanBahanBaku->status === 'Release')
                                        <span class="badge bg-success">Release</span>
                                    @else
                                        <span class="badge bg-warning">Hold</span>
                                    @endif
                                </td></tr>
                                <tr><td><strong>Segel/Gembok:</strong></td><td>
                                    @if($pemeriksaanBahanBaku->segel_gembok)
                                        @if($pemeriksaanBahanBaku->segel_gembok === 'segel')
                                            <span class="badge bg-info">Segel</span>
                                        @elseif($pemeriksaanBahanBaku->segel_gembok === 'gembok')
                                            <span class="badge bg-warning">Gembok</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $pemeriksaanBahanBaku->segel_gembok }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td></tr>
                                <tr><td><strong>No. Segel:</strong></td><td>
                                    @if($pemeriksaanBahanBaku->segel_gembok === 'segel' && $pemeriksaanBahanBaku->no_segel)
                                        {{ $pemeriksaanBahanBaku->no_segel }}
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Nama Supir:</strong></td><td>{{ $pemeriksaanBahanBaku->nama_supir ?? '-' }}</td></tr>
                                <tr><td><strong>Jenis Mobil:</strong></td><td>{{ $pemeriksaanBahanBaku->jenis_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>No. Mobil:</strong></td><td>{{ $pemeriksaanBahanBaku->no_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>Shift:</strong></td><td>
                                    @if($pemeriksaanBahanBaku->shift)
                                        <span class="badge bg-primary">{{ $pemeriksaanBahanBaku->shift->shift }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                    </div>

                    <!-- Kondisi Mobil -->
                    <h5 class="text-primary">Kondisi Mobil Pengangkut</h5>
                    @if($pemeriksaanBahanBaku->kondisi_mobil)
                        <div class="row mb-4">
                            @php
                                $kondisiMobil = [
                                    'bersih' => 'Bersih', 'bebas_hama' => 'Bebas dari hama',
                                    'tidak_kondensasi' => 'Tidak Kondensasi', 'bebas_produk_halal' => 'Bebas dari Produk Non Halal',
                                    'tidak_berbau' => 'Tidak Berbau', 'tidak_ada_sampah' => 'Tidak ada sampah',
                                    'tidak_ada_mikroba' => 'Tidak ada mikroba', 'lampu_cover_utuh' => 'Lampu Cover utuh',
                                    'pallet_utuh' => 'Pallet utuh', 'tertutup_rapat' => 'Tertutup rapat',
                                    'bebas_kontaminan' => 'Bebas kontaminan'
                                ];
                            @endphp
                            @foreach($kondisiMobil as $key => $label)
                                <div class="col-md-4 mb-2">
                                    @if(isset($pemeriksaanBahanBaku->kondisi_mobil[$key]) && $pemeriksaanBahanBaku->kondisi_mobil[$key])
                                        <span class="badge bg-success">✓</span>
                                    @else
                                        <span class="badge bg-danger">✗</span>
                                    @endif
                                    {{ $label }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Informasi Produk & Supplier -->
                    <h5 class="text-primary">Informasi Produk & Supplier</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Nama Bahan:</strong></td><td>
                                    @if($pemeriksaanBahanBaku->bahan)
                                        <span class="badge bg-info">{{ $pemeriksaanBahanBaku->bahan->nama_bahan }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                                <tr><td><strong>Kondisi Produk:</strong></td><td>
                                    @if($pemeriksaanBahanBaku->kondisi_produk)
                                        <span class="badge bg-secondary">{{ $pemeriksaanBahanBaku->kondisi_produk }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                                <tr><td><strong>Produsen:</strong></td><td>{{ $pemeriksaanBahanBaku->produsen ?? '-' }}</td></tr>
                                <tr><td><strong>Distributor:</strong></td><td>{{ $pemeriksaanBahanBaku->distributor ?? '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Kode Produksi:</strong></td><td>{{ $pemeriksaanBahanBaku->kode_produksi ?? '-' }}</td></tr>
                                <tr><td><strong>Expire Date:</strong></td><td>{{ $pemeriksaanBahanBaku->expire_date ? $pemeriksaanBahanBaku->expire_date->format('d/m/Y') : '-' }}</td></tr>
                                <tr><td><strong>Jumlah Datang:</strong></td><td>{{ $pemeriksaanBahanBaku->jumlah_datang ?? '-' }}</td></tr>
                                <tr><td><strong>Jumlah Sampling:</strong></td><td>{{ $pemeriksaanBahanBaku->jumlah_sampling ?? '-' }}</td></tr>
                            </table>
                        </div>
                                                <!-- New Conditional Fields Display -->
                                                <div class="col-12 mt-3">
                            <h6 class="text-primary">Informasi Suhu & Kondisi</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <table class="table table-borderless table-sm">
                                        <tr><td><strong>Jenis Suhu Mobil:</strong></td><td>
                                            @if($pemeriksaanBahanBaku->suhu_mobil_type)
                                                <span class="badge bg-info">{{ $pemeriksaanBahanBaku->suhu_mobil_type }}</span>
                                            @else
                                                -
                                            @endif
                                        </td></tr>
                                        <tr><td><strong>Nilai Suhu Mobil:</strong></td><td>{{ $pemeriksaanBahanBaku->suhu_mobil ?? '-' }}</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-borderless table-sm">
                                        <tr><td><strong>Jenis Suhu Produk:</strong></td><td>
                                            @if($pemeriksaanBahanBaku->suhu_produk_type)
                                                <span class="badge bg-info">{{ $pemeriksaanBahanBaku->suhu_produk_type }}</span>
                                            @else
                                                -
                                            @endif
                                        </td></tr>
                                        <tr><td><strong>Nilai Suhu Produk:</strong></td><td>{{ $pemeriksaanBahanBaku->suhu_produk ?? '-' }}</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-borderless table-sm">
                                        <tr><td><strong>Suhu Kondisi Produk:</strong></td><td>{{ $pemeriksaanBahanBaku->kondisi_produk_suhu ?? '-' }}</td></tr>
                                        @if($pemeriksaanBahanBaku->kondisi_produk === 'Minyak')
                                            <tr><td><strong>Hasil Uji FFA:</strong></td><td>{{ $pemeriksaanBahanBaku->hasil_uji_ffa ?? '-' }}</td></tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Conditional Fields Display -->
                        @if(in_array($pemeriksaanBahanBaku->kondisi_produk, ['Fresh', 'Frozen']))
                            <div class="col-12 mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Suhu Mobil:</strong> {{ $pemeriksaanBahanBaku->suhu_mobil ?? '-' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Suhu Produk:</strong> {{ $pemeriksaanBahanBaku->suhu_produk ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if($pemeriksaanBahanBaku->kondisi_produk === 'Minyak')
                            <div class="col-12 mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Hasil Uji FFA:</strong> {{ $pemeriksaanBahanBaku->hasil_uji_ffa ?? '-' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Bukti Kebersihan Tanki:</strong> {{ $pemeriksaanBahanBaku->bukti_kebersihan_tanki ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Kondisi Fisik & Dokumentasi -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-primary">Kondisi Fisik</h5>
                            @if($pemeriksaanBahanBaku->kondisi_fisik)
                                @php
                                    $kondisiFisik = [
                                        'kemasan' => 'Kemasan',
                                        'warna' => 'Warna',
                                        'benda_asing' => 'Benda Asing/Kotoran',
                                        'aroma' => 'Aroma'
                                    ];
                                @endphp
                                @foreach($kondisiFisik as $key => $label)
                                    <div class="mb-2">
                                        @if(isset($pemeriksaanBahanBaku->kondisi_fisik[$key]) && $pemeriksaanBahanBaku->kondisi_fisik[$key])
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
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="text-primary">Dokumentasi</h5>
                            <div class="mb-2">
                                @if($pemeriksaanBahanBaku->logo_halal)
                                    <span class="badge bg-success me-2">✓</span>
                                @else
                                    <span class="badge bg-danger me-2">✗</span>
                                @endif
                                Logo Halal
                            </div>
                            <div class="mb-2">
                                @if($pemeriksaanBahanBaku->dokumen_halal)
                                    <span class="badge bg-success me-2">✓</span>
                                @else
                                    <span class="badge bg-danger me-2">✗</span>
                                @endif
                                Persyaratan Dokumen: Halal (berlaku)
                            </div>
                            <div class="mb-2">
                                @if($pemeriksaanBahanBaku->coa)
                                    <span class="badge bg-success me-2">✓</span>
                                @else
                                    <span class="badge bg-danger me-2">✗</span>
                                @endif
                                COA (Certificate of Analysis)
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Tambahan -->
                    @if($pemeriksaanBahanBaku->spesifikasi)
                        <div class="col-12 mt-3">
                            <strong>Spesifikasi:</strong>
                            <p class="mt-2 p-3 bg-light rounded">{{ $pemeriksaanBahanBaku->spesifikasi }}</p>
                        </div>
                    @endif
                    
                    @if($pemeriksaanBahanBaku->keterangan)
                        <div class="col-12 mt-3">
                            <strong>Keterangan:</strong>
                            <p class="mt-2 p-3 bg-light rounded">{{ $pemeriksaanBahanBaku->keterangan }}</p>
                        </div>
                    @endif
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary">Informasi Tambahan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr><td width="40%"><strong>Dibuat Oleh:</strong></td><td>
                                            <strong>{{ $pemeriksaanBahanBaku->user->name }}</strong>
                                            <br><small class="text-muted">{{ $pemeriksaanBahanBaku->user->username }}</small>
                                        </td></tr>
                                        <!-- <tr><td><strong>Plant:</strong></td><td>
                                            @if($pemeriksaanBahanBaku->user->plant)
                                                <span class="badge bg-info">{{ $pemeriksaanBahanBaku->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </td></tr> -->
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr><td width="40%"><strong>Dibuat Pada:</strong></td><td>{{ $pemeriksaanBahanBaku->created_at->format('d/m/Y H:i:s') }}</td></tr>
                                        <tr><td><strong>Diupdate Pada:</strong></td><td>{{ $pemeriksaanBahanBaku->updated_at->format('d/m/Y H:i:s') }}</td></tr>
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