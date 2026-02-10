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
                    <h5 class="text-primary mb-3">Informasi Dasar</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td width="40%"><strong>Tanggal:</strong></td><td>{{ $pemeriksaanChemical->tanggal->format('d/m/Y') }}</td></tr>
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
                                <tr><td width="40%"><strong>Nama Supir:</strong></td><td>{{ $pemeriksaanChemical->nama_supir ?? '-' }}</td></tr>
                                <tr><td><strong>Jenis Mobil:</strong></td><td>{{ $pemeriksaanChemical->jenis_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>No. Mobil:</strong></td><td>{{ $pemeriksaanChemical->no_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>Segel/Gembok:</strong></td><td>
                                    @if($pemeriksaanChemical->segel_gembok)
                                        <span class="badge bg-info">{{ ucfirst($pemeriksaanChemical->segel_gembok) }}</span>
                                        @if($pemeriksaanChemical->segel_gembok === 'segel' && $pemeriksaanChemical->no_segel)
                                            - {{ $pemeriksaanChemical->no_segel }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                    </div>

                    <!-- Kondisi Mobil -->
                    <h5 class="text-primary mb-3">Kondisi Mobil Pengangkut</h5>
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
                                    'tidak_ada_mikroba' => 'Tidak ada mikroba', 
                                    'lampu_cover_utuh' => 'Lampu Cover utuh',
                                    'pallet_utuh' => 'Pallet utuh', 
                                    'tertutup_rapat' => 'Tertutup rapat',
                                    'bebas_kontaminan' => 'Bebas kontaminan'
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
                    @endif

                    <!-- Detail Chemicals (Dynamic Rows) -->
                    <h5 class="text-primary mb-3">Detail Chemicals</h5>
                    @php
                        $detailChemicals = $pemeriksaanChemical->detail_chemicals ?? [];
                        $rowCount = count($detailChemicals);

                        $groupedDetailIdx = [];
                        foreach ((array) $detailChemicals as $i => $detail) {
                            $existingChemicalId = $detail['id_chemical'] ?? null;
                            $mappedProdukId = $existingChemicalId ? ($produkByChemicalId[$existingChemicalId]['id_produk'] ?? null) : null;
                            $key = $mappedProdukId ? (string) $mappedProdukId : ('unknown-' . $i);
                            if (!isset($groupedDetailIdx[$key])) $groupedDetailIdx[$key] = [];
                            $groupedDetailIdx[$key][] = $i;
                        }

                        $produkNamaById = [];
                        foreach ($groupedDetailIdx as $produkKey => $idxList) {
                            if (str_starts_with((string) $produkKey, 'unknown-')) continue;
                            $pid = (int) $produkKey;
                            if (!isset($produkNamaById[$produkKey])) {
                                $p = \App\Models\Produk::find($pid);
                                $produkNamaById[$produkKey] = $p ? ($p->nama ?? '-') : '-';
                            }
                        }
                    @endphp
                    
                    @if($rowCount > 0)
                        @foreach($groupedDetailIdx as $produkKey => $detailIdxList)
                            @php
                                $firstIdx = $detailIdxList[0] ?? null;
                                $firstDetail = $firstIdx !== null ? ($detailChemicals[$firstIdx] ?? []) : [];
                                $existingChemicalId = $firstDetail['id_chemical'] ?? null;
                                $mappedProdukId = $existingChemicalId ? ($produkByChemicalId[$existingChemicalId]['id_produk'] ?? null) : null;

                                $prodVal = $mappedProdukId ? ($produkMeta[$mappedProdukId]['produsen_names'] ?? []) : [];
                                $prodText = is_array($prodVal)
                                    ? implode(', ', array_values(array_filter($prodVal, fn ($v) => $v !== null && $v !== '')))
                                    : trim((string) $prodVal);

                                $distVal = $mappedProdukId ? ($produkMeta[$mappedProdukId]['distributor_names'] ?? []) : [];
                                $distText = is_array($distVal)
                                    ? implode(', ', array_values(array_filter($distVal, fn ($v) => $v !== null && $v !== '')))
                                    : trim((string) $distVal);

                                $produkTitle = $mappedProdukId ? ($produkNamaById[(string) $mappedProdukId] ?? '-') : 'Produk (Tidak diketahui)';
                            @endphp

                            <div class="card mb-3" style="border-left: 4px solid #435ebe;">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Produk: {{ $produkTitle }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless table-sm">
                                                <tr><td width="40%"><strong>Produsen:</strong></td><td>{{ $prodText !== '' ? $prodText : '-' }}</td></tr>
                                                <tr><td><strong>Distributor:</strong></td><td>{{ $distText !== '' ? $distText : '-' }}</td></tr>
                                            </table>
                                        </div>
                                    </div>

                                    @foreach($detailIdxList as $detailNo => $i)
                                        @php
                                            $detail = $detailChemicals[$i] ?? [];
                                            $imgPath = $detail['image_chemical'] ?? null;
                                        @endphp

                                        <div class="border rounded p-3 mb-3" style="background: #fff;">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-bold">Detail #{{ $detailNo + 1 }}</span>
                                                @if(isset($detail['status']))
                                                    @if($detail['status'] === 'Release')
                                                        <span class="badge bg-success">Release</span>
                                                    @else
                                                        <span class="badge bg-warning">Hold</span>
                                                    @endif
                                                @endif
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless table-sm mb-0">
                                                        <tr><td width="40%"><strong>Nama Chemical:</strong></td><td>
                                                            @if(isset($detail['id_chemical']) && $detail['id_chemical'])
                                                                @php $chemical = \App\Models\Chemical::find($detail['id_chemical']); @endphp
                                                                <span class="badge bg-info">{{ $chemical->nama_chemical ?? '-' }}</span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td></tr>
                                                        <tr><td><strong>Kondisi Chemical:</strong></td><td>{{ $detail['kondisi_chemical'] ?? '-' }}</td></tr>
                                                        <tr><td><strong>Negara Produsen:</strong></td><td>{{ $detail['negara_produsen'] ?? '-' }}</td></tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-borderless table-sm mb-0">
                                                        <tr><td width="40%"><strong>Kode Produksi:</strong></td><td>{{ $detail['kode_produksi'] ?? '-' }}</td></tr>
                                                        <tr><td><strong>Expire Date:</strong></td><td>
                                                            @if(isset($detail['expire_date']) && $detail['expire_date'])
                                                                {{ \Carbon\Carbon::parse($detail['expire_date'])->format('d/m/Y') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td></tr>
                                                        <tr><td><strong>Jumlah Datang:</strong></td><td>{{ $detail['jumlah_datang'] ?? '-' }}</td></tr>
                                                        <tr><td><strong>Jumlah Sampling:</strong></td><td>{{ $detail['jumlah_sampling'] ?? '-' }}</td></tr>
                                                    </table>
                                                </div>
                                            </div>

                                            @if(isset($detail['kondisi_fisik']) || isset($detail['persyaratan_dokumen_halal']) || isset($detail['coa']))
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <h6 class="text-primary small mb-2">Kondisi Fisik & Dokumentasi</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong class="small d-block mb-2">Kondisi Fisik:</strong>
                                                                @php
                                                                    $kondisiFisikLabels = [
                                                                        'kemasan' => 'Kemasan',
                                                                        'warna' => 'Warna'
                                                                    ];
                                                                @endphp
                                                                @foreach($kondisiFisikLabels as $key => $label)
                                                                    <div class="d-flex align-items-center small mb-1">
                                                                        @if(isset($detail['kondisi_fisik'][$key]) && $detail['kondisi_fisik'][$key])
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
                                                                    @if(isset($detail['persyaratan_dokumen_halal']) && $detail['persyaratan_dokumen_halal'])
                                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                    @else
                                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                    @endif
                                                                    <span>Persyaratan Dokumen Halal</span>
                                                                </div>
                                                                <div class="d-flex align-items-center small mb-1">
                                                                    @if(isset($detail['coa']) && $detail['coa'])
                                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                    @else
                                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                    @endif
                                                                    <span>COA</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($imgPath)
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <h6 class="text-primary small mb-2">Foto Chemical</h6>
                                                        <div class="p-2 bg-white rounded">
                                                            <a href="{{ asset('storage/' . $imgPath) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $imgPath) }}" alt="Foto Chemical" style="max-width: 260px; height: auto; border: 1px solid #ddd; padding: 4px;">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if(isset($detail['keterangan']) && $detail['keterangan'])
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <strong>Keterangan:</strong>
                                                        <p class="mt-1 p-2 bg-light rounded small">{{ $detail['keterangan'] }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Data tidak tersedia</p>
                    @endif

                    <!-- Info Audit -->
                    <!-- <div class="row mt-4">
                        <div class="col-12">
                            <small class="text-muted">
                                <strong>Dibuat:</strong> {{ $pemeriksaanChemical->created_at->format('d/m/Y H:i') }} | 
                                <strong>Diupdate:</strong> {{ $pemeriksaanChemical->updated_at->format('d/m/Y H:i') }}
                                @if($pemeriksaanChemical->user)
                                    | <strong>Oleh:</strong> {{ $pemeriksaanChemical->user->name }}
                                @endif
                            </small>
                        </div>
                    </div> -->
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
