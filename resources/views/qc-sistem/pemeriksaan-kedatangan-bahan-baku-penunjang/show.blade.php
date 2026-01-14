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
                    <h5 class="text-primary mb-3">Informasi Dasar</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td width="40%"><strong>Tanggal:</strong></td><td>{{ $pemeriksaanBahanBaku->tanggal->format('d/m/Y') }}</td></tr>
                                <tr><td><strong>No. PO:</strong></td><td>{{ $pemeriksaanBahanBaku->no_po ?? '-' }}</td></tr>
                                <tr><td><strong>Jenis Pemeriksaan:</strong></td><td>{{ $pemeriksaanBahanBaku->jenis_pemeriksaan ?? '-' }}</td></tr>
                                <!-- <tr><td><strong>Status Keseluruhan:</strong></td><td>
                                    @if($pemeriksaanBahanBaku->status === 'Release')
                                        <span class="badge bg-success">Release</span>
                                    @else
                                        <span class="badge bg-warning">Hold</span>
                                    @endif
                                </td></tr> -->
                                <tr><td><strong>Shift:</strong></td><td>
                                    @if($pemeriksaanBahanBaku->shift)
                                        <span class="badge bg-primary">{{ $pemeriksaanBahanBaku->shift->shift }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td width="40%"><strong>Nama Supir:</strong></td><td>{{ $pemeriksaanBahanBaku->nama_supir ?? '-' }}</td></tr>
                                <tr><td><strong>Jenis Mobil:</strong></td><td>{{ $pemeriksaanBahanBaku->jenis_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>No. Mobil:</strong></td><td>{{ $pemeriksaanBahanBaku->no_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>Segel/Gembok:</strong></td><td>
                                    @if($pemeriksaanBahanBaku->segel_gembok)
                                        <span class="badge bg-info">{{ ucfirst($pemeriksaanBahanBaku->segel_gembok) }}</span>
                                        @if($pemeriksaanBahanBaku->segel_gembok === 'segel' && $pemeriksaanBahanBaku->no_segel)
                                            - {{ $pemeriksaanBahanBaku->no_segel }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                    </div>

                    <!-- Suhu Mobil -->
                    @if($pemeriksaanBahanBaku->suhu_mobil_type || $pemeriksaanBahanBaku->suhu_mobil)
                        <h5 class="text-primary mb-3">Informasi Suhu Mobil</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr><td width="40%"><strong>Jenis Suhu Mobil:</strong></td><td>
                                        @if($pemeriksaanBahanBaku->suhu_mobil_type)
                                            <span class="badge bg-info">{{ $pemeriksaanBahanBaku->suhu_mobil_type }}</span>
                                        @else
                                            -
                                        @endif
                                    </td></tr>
                                    <tr><td><strong>Nilai Suhu Mobil:</strong></td><td>{{ $pemeriksaanBahanBaku->suhu_mobil ?? '-' }}</td></tr>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Kondisi Mobil -->
                    <h5 class="text-primary mb-3">Kondisi Mobil Pengangkut</h5>
                    @if($pemeriksaanBahanBaku->kondisi_mobil)
                        <div class="row mb-4">
                            @php
                                $kondisiMobil = [
                                    'bersih' => 'Bersih', 
                                    'bebas_hama' => 'Bebas dari hama',
                                    'tidak_kondensasi' => 'Tidak Kondensasi', 
                                    'bebas_produk_halal' => 'Bebas dari Produk Non Halal',
                                    'tidak_berbau' => 'Tidak Berbau', 
                                    'tidak_ada_sampah' => 'Tidak ada sampah',
                                    'tidak_ada_mikroba' => 'Tidak ada mikroba', 
                                    'lampu_cover_utuh' => 'Lampu Cover utuh',
                                    'pallet_utuh' => 'Pallet utuh', 
                                    'tertutup_rapat' => 'Tertutup rapat',
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

                    <!-- Detail Produk (Dynamic Rows) -->
                    <h5 class="text-primary mb-3">Detail Produk</h5>
                    @php
                        $idBahanArray = json_decode($pemeriksaanBahanBaku->id_bahan_array, true) ?? [];
                        $produsenArray = json_decode($pemeriksaanBahanBaku->produsen_array, true) ?? [];
                        $negaraProdusenArray = json_decode($pemeriksaanBahanBaku->negara_produsen_array, true) ?? [];
                        $distributorArray = json_decode($pemeriksaanBahanBaku->distributor_array, true) ?? [];
                        $kodeProduksiArray = json_decode($pemeriksaanBahanBaku->kode_produksi_array, true) ?? [];
                        $expireDateArray = json_decode($pemeriksaanBahanBaku->expire_date_array, true) ?? [];
                        $jumlahDatangArray = json_decode($pemeriksaanBahanBaku->jumlah_datang_array, true) ?? [];
                        $jumlahSamplingArray = json_decode($pemeriksaanBahanBaku->jumlah_sampling_array, true) ?? [];
                        $spesifikasiArray = json_decode($pemeriksaanBahanBaku->spesifikasi_array, true) ?? [];
                        $kondisiProdukArray = json_decode($pemeriksaanBahanBaku->kondisi_produk, true) ?? [];
                        $suhuProdukArray = json_decode($pemeriksaanBahanBaku->suhu_produk, true) ?? [];
                        $suhuProdukTypeArray = json_decode($pemeriksaanBahanBaku->suhu_produk_type, true) ?? [];
                        $suhuMobilArray = json_decode($pemeriksaanBahanBaku->suhu_mobil_array, true) ?? [];
                        $suhuMobilTypeArray = json_decode($pemeriksaanBahanBaku->suhu_mobil_type_array, true) ?? [];
                        $kondisiProdukSuhuArray = json_decode($pemeriksaanBahanBaku->kondisi_produk_suhu, true) ?? [];
                        $kondisiFisikArray = json_decode($pemeriksaanBahanBaku->kondisi_fisik_array, true) ?? [];
                        $logoHalalArray = json_decode($pemeriksaanBahanBaku->logo_halal_array, true) ?? [];
                        $dokumenHalalArray = json_decode($pemeriksaanBahanBaku->dokumen_halal_array, true) ?? [];
                        $coaArray = json_decode($pemeriksaanBahanBaku->coa_array, true) ?? [];
                        $fileCoaArray = json_decode($pemeriksaanBahanBaku->file_coa_array ?? '[]', true) ?? [];
                        $hasilUjiFfaArray = json_decode($pemeriksaanBahanBaku->hasil_uji_ffa_array, true) ?? [];
                        $statusBarisArray = json_decode($pemeriksaanBahanBaku->status_baris_array, true) ?? [];
                        $keteranganArray = json_decode($pemeriksaanBahanBaku->keterangan_array, true) ?? [];
                        
                        $rowCount = max(count($idBahanArray), 1);
                    @endphp
                    
                    @for($i = 0; $i < $rowCount; $i++)
                        <div class="card mb-3" style="border-left: 4px solid #435ebe;">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Bahan Baku Penunjang {{ $i + 1 }}</h6>
                                    @if(isset($statusBarisArray[$i]))
                                        @if($statusBarisArray[$i] === 'Release')
                                            <span class="badge bg-success">Release</span>
                                        @else
                                            <span class="badge bg-warning">Hold</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless table-sm">
                                            <tr><td width="40%"><strong>Nama Produk:</strong></td><td>
                                                @if(isset($idBahanArray[$i]) && $idBahanArray[$i])
                                                    @php $produk = \App\Models\Produk::find($idBahanArray[$i]); @endphp
                                                    <span class="badge bg-info">{{ $produk->nama_produk ?? '-' }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td></tr>
                                            <tr><td><strong>Produsen:</strong></td><td>
                                                @php
                                                    $produsenDisplay = $produsenArray[$i] ?? '';
                                                    $produsenItems = array_values(array_filter(array_map('trim', explode(',', (string) $produsenDisplay)), fn($v) => $v !== ''));
                                                @endphp
                                                @if(count($produsenItems))
                                                    @foreach($produsenItems as $p)
                                                        <span class="badge bg-light-primary text-primary">{{ $p }}</span>
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </td></tr>
                                            <tr><td><strong>Negara Produsen:</strong></td><td>{{ $negaraProdusenArray[$i] ?? '-' }}</td></tr>
                                            <tr><td><strong>Distributor:</strong></td><td>
                                                @php
                                                    $distributorDisplay = $distributorArray[$i] ?? '';
                                                    $distributorItems = array_values(array_filter(array_map('trim', explode(',', (string) $distributorDisplay)), fn($v) => $v !== ''));
                                                @endphp
                                                @if(count($distributorItems))
                                                    @foreach($distributorItems as $d)
                                                        <span class="badge bg-light-info text-info">{{ $d }}</span>
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </td></tr>
                                            <tr><td><strong>Kode Produksi:</strong></td><td>{{ $kodeProduksiArray[$i] ?? '-' }}</td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless table-sm">
                                            <tr><td width="40%"><strong>Expire Date:</strong></td><td>
                                                @if(isset($expireDateArray[$i]) && $expireDateArray[$i])
                                                    {{ \Carbon\Carbon::parse($expireDateArray[$i])->format('d/m/Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td></tr>
                                            <tr><td><strong>Jumlah Datang:</strong></td><td>{{ $jumlahDatangArray[$i] ?? '-' }}</td></tr>
                                            <tr><td><strong>Jumlah Sampling:</strong></td><td>{{ $jumlahSamplingArray[$i] ?? '-' }}</td></tr>
                                        </table>
                                    </div>
                                </div>
                                
                                @if(isset($kondisiProdukArray[$i]) || isset($suhuProdukTypeArray[$i]) || isset($suhuProdukArray[$i]) || isset($kondisiProdukSuhuArray[$i]) || isset($hasilUjiFfaArray[$i]))
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="text-primary small mb-2">Informasi Suhu & Kondisi</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @if(isset($kondisiProdukArray[$i]) && $kondisiProdukArray[$i])
                                                        <div class="d-flex align-items-center small mb-2">
                                                            <span style="min-width: 150px;"><strong>Kondisi Produk:</strong></span>
                                                            <span class="badge bg-secondary">{{ $kondisiProdukArray[$i] }}</span>
                                                        </div>
                                                    @endif
                                                    @if(isset($suhuProdukTypeArray[$i]) && $suhuProdukTypeArray[$i])
                                                        <div class="d-flex align-items-center small mb-2">
                                                            <span style="min-width: 150px;"><strong>Jenis Suhu Produk:</strong></span>
                                                            <span class="badge bg-info">{{ $suhuProdukTypeArray[$i] }}</span>
                                                        </div>
                                                    @endif
                                                    @if(isset($suhuMobilTypeArray[$i]) && $suhuMobilTypeArray[$i])
                                                        <div class="d-flex align-items-center small mb-2">
                                                            <span style="min-width: 150px;"><strong>Jenis Suhu Mobil:</strong></span>
                                                            <span class="badge bg-warning">{{ $suhuMobilTypeArray[$i] }}</span>
                                                        </div>
                                                    @endif
                                                    @if(isset($hasilUjiFfaArray[$i]) && $hasilUjiFfaArray[$i])
                                                        <div class="d-flex align-items-center small mb-2">
                                                            <span style="min-width: 150px;"><strong>Hasil Uji FFA:</strong></span>
                                                            <span>{{ $hasilUjiFfaArray[$i] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    @if(isset($kondisiProdukSuhuArray[$i]) && $kondisiProdukSuhuArray[$i])
                                                        <div class="d-flex align-items-center small mb-2">
                                                            <span style="min-width: 160px;"><strong>Suhu Kondisi Produk:</strong></span>
                                                            <span>{{ $kondisiProdukSuhuArray[$i] }}</span>
                                                        </div>
                                                    @endif
                                                    @if(isset($suhuProdukArray[$i]) && $suhuProdukArray[$i])
                                                        <div class="d-flex align-items-center small mb-2">
                                                            <span style="min-width: 160px;"><strong>Nilai Suhu Produk:</strong></span>
                                                            <span>{{ $suhuProdukArray[$i] }}</span>
                                                        </div>
                                                    @endif
                                                    @if(isset($suhuMobilArray[$i]) && $suhuMobilArray[$i])
                                                        <div class="d-flex align-items-center small mb-2">
                                                            <span style="min-width: 160px;"><strong>Nilai Suhu Mobil:</strong></span>
                                                            <span>{{ $suhuMobilArray[$i] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Kondisi Fisik & Dokumentasi Per Baris -->
                                @if(isset($kondisiFisikArray[$i]) || isset($logoHalalArray[$i]) || isset($dokumenHalalArray[$i]) || isset($coaArray[$i]))
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="text-primary small mb-2">Kondisi Fisik & Dokumentasi</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong class="small d-block mb-2">Kondisi Fisik:</strong>
                                                    @php
                                                        $kondisiFisikLabels = [
                                                            'kemasan' => 'Kemasan',
                                                            'warna' => 'Warna',
                                                            'benda_asing' => 'Benda Asing',
                                                            'aroma' => 'Aroma'
                                                        ];
                                                    @endphp
                                                    @foreach($kondisiFisikLabels as $key => $label)
                                                        <div class="d-flex align-items-center small mb-1">
                                                            @if(isset($kondisiFisikArray[$i][$key]) && $kondisiFisikArray[$i][$key])
                                                                <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                            @else
                                                                <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                            @endif
                                                            <span>{{ $label }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="col-md-6">
                                                    <strong class="small d-block mb-2">Dokumentasi:</strong>
                                                    <div class="d-flex align-items-center small mb-1">
                                                        @if(isset($logoHalalArray[$i]) && $logoHalalArray[$i] == '1')
                                                            <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                        @else
                                                            <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                        @endif
                                                        <span>Logo Halal</span>
                                                    </div>
                                                    <div class="d-flex align-items-center small mb-1">
                                                        @if(isset($dokumenHalalArray[$i]) && $dokumenHalalArray[$i] == '1')
                                                            <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                        @else
                                                            <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                        @endif
                                                        <span>Dokumen Halal</span>
                                                    </div>
                                                    <div class="d-flex align-items-center small mb-1">
                                                        @if(isset($coaArray[$i]) && $coaArray[$i] == '1')
                                                            <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                        @else
                                                            <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                        @endif
                                                        <span>COA</span>
                                                    </div>

                                                    @php
                                                        $coaFilePath = $fileCoaArray[$i] ?? null;
                                                    @endphp
                                                    @if($coaFilePath)
                                                        <div class="d-flex align-items-center small mb-1">
                                                            <span class="badge bg-info me-2" style="min-width: 24px;">i</span>
                                                            <a href="{{ asset('storage/' . $coaFilePath) }}" target="_blank">Lihat File COA</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($spesifikasiArray[$i]) && $spesifikasiArray[$i])
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <strong>Spesifikasi:</strong>
                                            <p class="mt-1 p-2 bg-light rounded small">{{ $spesifikasiArray[$i] }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($keteranganArray[$i]) && $keteranganArray[$i])
                                    <div class="row">
                                        <div class="col-12">
                                            <strong>Keterangan:</strong>
                                            <p class="mt-1 p-2 bg-light rounded small">{{ $keteranganArray[$i] ?? '-' }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
