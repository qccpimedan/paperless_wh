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
                                <!-- <tr><td><strong>Status:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->status === 'Release')
                                        <span class="badge bg-success">Release</span>
                                    @else
                                        <span class="badge bg-warning">Hold</span>
                                    @endif
                                </td></tr> -->
                                <tr><td><strong>Segel/Gembok:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->segel_gembok)
                                        @if($pemeriksaanKedatanganKemasan->segel_gembok === 'segel')
                                            <span class="badge bg-info">Segel</span>
                                        @elseif($pemeriksaanKedatanganKemasan->segel_gembok === 'gembok')
                                            <span class="badge bg-warning">Gembok</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $pemeriksaanKedatanganKemasan->segel_gembok }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td></tr>
                                <tr><td><strong>No. Segel:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->segel_gembok === 'segel' && $pemeriksaanKedatanganKemasan->no_segel)
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
                                    'tidak_kondensasi' => 'Tidak Kondensasi', 'bebas_produk_halal' => 'Bebas dari Produk Non Halal',
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

                    <!-- Informasi Kemasan & Supplier (Dynamic Rows) -->
                    <h5 class="text-primary">Informasi Kemasan & Supplier</h5>
                    @php
                        $id_bahans = json_decode($pemeriksaanKedatanganKemasan->id_bahan_array ?? '[]', true) ?? [];
                        $produsens = json_decode($pemeriksaanKedatanganKemasan->produsen_array ?? '[]', true) ?? [];
                        $distributors = json_decode($pemeriksaanKedatanganKemasan->distributor_array ?? '[]', true) ?? [];
                        $kode_produksis = json_decode($pemeriksaanKedatanganKemasan->kode_produksi_array ?? '[]', true) ?? [];
                        $jumlah_datangs = json_decode($pemeriksaanKedatanganKemasan->jumlah_datang_array ?? '[]', true) ?? [];
                        $jumlah_samplings = json_decode($pemeriksaanKedatanganKemasan->jumlah_sampling_array ?? '[]', true) ?? [];
                        $spesifikasis = json_decode($pemeriksaanKedatanganKemasan->spesifikasi_array ?? '[]', true) ?? [];
                        $penampakans = json_decode($pemeriksaanKedatanganKemasan->penampakan_array ?? '[]', true) ?? [];
                        $sealings = json_decode($pemeriksaanKedatanganKemasan->sealing_array ?? '[]', true) ?? [];
                        $cetakans = json_decode($pemeriksaanKedatanganKemasan->cetakan_array ?? '[]', true) ?? [];
                        $ketebalan_microns = json_decode($pemeriksaanKedatanganKemasan->ketebalan_micron_array ?? '[]', true) ?? [];
                        $dimensis = json_decode($pemeriksaanKedatanganKemasan->dimensi_array ?? '[]', true) ?? [];
                        $statuses = json_decode($pemeriksaanKedatanganKemasan->status_array ?? '[]', true) ?? [];
                        $logo_halals = json_decode($pemeriksaanKedatanganKemasan->logo_halal_array ?? '[]', true) ?? [];
                        $dokumen_halals = json_decode($pemeriksaanKedatanganKemasan->dokumen_halal_array ?? '[]', true) ?? [];
                        $coas = json_decode($pemeriksaanKedatanganKemasan->coa_array ?? '[]', true) ?? [];
                        $keterangans = json_decode($pemeriksaanKedatanganKemasan->keterangan_array ?? '[]', true) ?? [];
                        $image_kemasans = json_decode($pemeriksaanKedatanganKemasan->image_kemasan_array ?? '[]', true) ?? [];
                        $rowCount = max(count($id_bahans), count($produsens), count($distributors));
                    @endphp

                    @forelse($id_bahans as $index => $id_bahan)
                        <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                            <h5 class="text-primary mb-3">Baris {{ $index + 1 }}</h5>
                            
                            <!-- Bahan Kemasan -->
                            <div class="form-section mb-3">
                                <h6 class="text-primary mb-2">Bahan Kemasan</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="p-2 bg-white rounded">
                                            <strong>Bahan:</strong> {{ $bahanNamaById[$id_bahan] ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Kemasan & Supplier -->
                            <div class="form-section mb-3">
                                <h6 class="text-primary mb-2">Informasi Kemasan & Supplier</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Produsen:</strong> {{ $produsens[$index] ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Distributor:</strong> {{ $distributors[$index] ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Kode Produksi:</strong> {{ $kode_produksis[$index] ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Jumlah Datang:</strong> {{ $jumlah_datangs[$index] ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Jumlah Sampling:</strong> {{ $jumlah_samplings[$index] ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Spesifikasi:</strong> {{ $spesifikasis[$index] ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kondisi Fisik -->
                            <div class="form-section mb-3">
                                <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Penampakan:</strong>
                                            @if($penampakans[$index] ?? null)
                                                <span class="badge bg-success ms-2">✓ Ya</span>
                                            @else
                                                <span class="badge bg-danger ms-2">✗ Tidak</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Sealing:</strong>
                                            @if($sealings[$index] ?? null)
                                                <span class="badge bg-success ms-2">✓ Ya</span>
                                            @else
                                                <span class="badge bg-danger ms-2">✗ Tidak</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Cetakan:</strong>
                                            @if($cetakans[$index] ?? null)
                                                <span class="badge bg-success ms-2">✓ Ya</span>
                                            @else
                                                <span class="badge bg-danger ms-2">✗ Tidak</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Tambahan -->
                            <div class="form-section mb-3">
                                <h6 class="text-primary mb-2">Detail Tambahan</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Ketebalan (Micron):</strong> {{ $ketebalan_microns[$index] ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Dimensi:</strong> {{ $dimensis[$index] ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Status:</strong> 
                                            @if($statuses[$index] === 'Release')
                                                <span class="badge bg-success ms-2">Release</span>
                                            @elseif($statuses[$index] === 'Hold')
                                                <span class="badge bg-warning ms-2">Hold</span>
                                            @else
                                                <span class="badge bg-secondary ms-2">{{ $statuses[$index] ?? '-' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dokumen -->
                            <div class="form-section mb-3">
                                <h6 class="text-primary mb-2">Dokumen</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Logo Halal:</strong>
                                            @if($logo_halals[$index] ?? null)
                                                <span class="badge bg-success ms-2">✓ Ya</span>
                                            @else
                                                <span class="badge bg-danger ms-2">✗ Tidak</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>Dokumen Halal:</strong>
                                            @if($dokumen_halals[$index] ?? null)
                                                <span class="badge bg-success ms-2">✓ Ya</span>
                                            @else
                                                <span class="badge bg-danger ms-2">✗ Tidak</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-white rounded mb-2">
                                            <strong>COA:</strong>
                                            @if($coas[$index] ?? null)
                                                <span class="badge bg-success ms-2">✓ Ya</span>
                                            @else
                                                <span class="badge bg-danger ms-2">✗ Tidak</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($keterangans[$index] ?? null)
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="p-2 bg-white rounded">
                                                <strong>Keterangan:</strong>
                                                <p class="mt-2 mb-0">{{ $keterangans[$index] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @php
                                $imgPath = $image_kemasans[$index] ?? null;
                            @endphp
                            @if($imgPath)
                                <div class="form-section mb-3">
                                    <h6 class="text-primary mb-2">Gambar Kemasan</h6>
                                    <div class="p-2 bg-white rounded">
                                        <img src="{{ asset('storage/' . $imgPath) }}" alt="Gambar Kemasan" style="max-width: 260px; height: auto; border: 1px solid #ddd; padding: 4px;">
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="alert alert-info">Tidak ada data dynamic form</div>
                    @endforelse

                    <!-- Kondisi Fisik & Dokumentasi -->
                    <!-- <div class="row mb-4">
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
                            @if($pemeriksaanKedatanganKemasan->dimensi)
                                <div class="mt-2 p-2 bg-light rounded">
                                    <strong>Dimensi:</strong> {{ $pemeriksaanKedatanganKemasan->dimensi }}
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
                    </div> -->

                    <!-- Informasi Tambahan -->
                    <!-- <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary">Informasi Tambahan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr><td width="40%"><strong>Dibuat Oleh:</strong></td><td>
                                            <strong>{{ $pemeriksaanKedatanganKemasan->user->name }}</strong>
                                            <br><small class="text-muted">{{ $pemeriksaanKedatanganKemasan->user->username }}</small>
                                        </td></tr>
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
                    </div> -->
                </div>
            </div>
        </section>
    </div>
</div>
@endsection