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
                                <!-- <tr><td><strong>Jenis Pemeriksaan:</strong></td><td>{{ $pemeriksaanBahanBaku->jenis_pemeriksaan ?? '-' }}</td></tr> -->
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
                        $unitDatangArray = json_decode($pemeriksaanBahanBaku->unit_datang_array, true) ?? [];
                        $unitSamplingArray = json_decode($pemeriksaanBahanBaku->unit_sampling_array, true) ?? [];
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
                        $imageBahanBakuArray = json_decode($pemeriksaanBahanBaku->image_bahan_baku_array ?? '[]', true) ?? [];
                        $hasilUjiFfaArray = json_decode($pemeriksaanBahanBaku->hasil_uji_ffa_array, true) ?? [];
                        $statusBarisArray = json_decode($pemeriksaanBahanBaku->status_baris_array, true) ?? [];
                        $keteranganArray = json_decode($pemeriksaanBahanBaku->keterangan_array, true) ?? [];

                        // Determine how many detail indexes exist by taking the max length across arrays.
                        // This prevents missing Detail blocks when id_bahan_array is shorter (legacy data).
                        $rowCount = max(
                            count($idBahanArray),
                            count($kodeProduksiArray),
                            count($expireDateArray),
                            count($jumlahDatangArray),
                            count($jumlahSamplingArray),
                            count($spesifikasiArray),
                            count($kondisiFisikArray),
                            count($logoHalalArray),
                            count($dokumenHalalArray),
                            count($coaArray),
                            count($fileCoaArray),
                            count($imageBahanBakuArray),
                            count($hasilUjiFfaArray),
                            count($statusBarisArray),
                            count($keteranganArray)
                        );

                        $firstBahanId = $idBahanArray[0] ?? null;
                        if (is_array($firstBahanId)) {
                            $firstBahanId = $firstBahanId[0] ?? null;
                        }
                        $firstBahanId = $firstBahanId === null ? '' : trim((string) $firstBahanId);
                        if ($firstBahanId !== '' && ctype_digit($firstBahanId)) {
                            $firstBahanId = (string) ((int) $firstBahanId);
                        }

                        // Group by bahanId (like Kemasan show) but keep the first-seen order.
                        // This ensures one product card per bahan, even if the same bahan appears again later.
                        $groupedDetailIdx = [];
                        $orderedBahanIds = [];
                        $lastSeenBahanId = $firstBahanId;

                        for ($i = 0; $i < $rowCount; $i++) {
                            $rawBahanId = $idBahanArray[$i] ?? '';
                            if (is_array($rawBahanId)) {
                                $rawBahanId = $rawBahanId[0] ?? null;
                            }
                            $bahanId = $rawBahanId === null ? '' : trim((string) $rawBahanId);
                            if ($bahanId !== '' && ctype_digit($bahanId)) {
                                $bahanId = (string) ((int) $bahanId);
                            }

                            // If id_bahan at this index is empty, attach the detail to the last seen bahan.
                            if ($bahanId === '') {
                                $bahanId = $lastSeenBahanId !== '' ? trim((string) $lastSeenBahanId) : $firstBahanId;
                                if ($bahanId !== '' && ctype_digit($bahanId)) {
                                    $bahanId = (string) ((int) $bahanId);
                                }
                            }
                            if ($bahanId === '') {
                                $bahanId = '__unknown__';
                            }

                            $lastSeenBahanId = ($bahanId !== '__unknown__') ? $bahanId : $lastSeenBahanId;

                            if (!isset($groupedDetailIdx[$bahanId])) {
                                $groupedDetailIdx[$bahanId] = [];
                                $orderedBahanIds[] = $bahanId;
                            }
                            $groupedDetailIdx[$bahanId][] = $i;
                        }

                        $bahanNamaById = [];
                        try {
                            $bahanIdsForMap = array_values(array_filter(array_unique($orderedBahanIds), fn ($v) => $v !== '__unknown__'));
                            if (!empty($bahanIdsForMap)) {
                                $bahanNamaById = \App\Models\Bahan::query()
                                    ->whereIn('id', $bahanIdsForMap)
                                    ->pluck('nama_bahan', 'id')
                                    ->toArray();
                            }
                        } catch (\Throwable $e) {
                            $bahanNamaById = [];
                        }
                    @endphp
                    
                    @forelse($orderedBahanIds as $bahanId)
                        @php
                            $detailIdxList = $groupedDetailIdx[$bahanId] ?? [];
                            $firstIdx = $detailIdxList[0] ?? null;
                            $prodVal = $firstIdx !== null ? ($produsenArray[$firstIdx] ?? null) : null;
                            if (is_array($prodVal)) {
                                $prodList = array_values(array_filter($prodVal, fn ($v) => $v !== null && $v !== ''));
                                $prodText = implode(', ', $prodList);
                            } else {
                                $raw = trim((string) $prodVal);
                                $prodList = $raw !== '' ? array_values(array_filter(array_map('trim', explode(',', $raw)))) : [];
                                $prodText = $raw;
                            }

                            $distVal = $firstIdx !== null ? ($distributorArray[$firstIdx] ?? null) : null;
                            if (is_array($distVal)) {
                                $distList = array_values(array_filter($distVal, fn ($v) => $v !== null && $v !== ''));
                                $distText = implode(', ', $distList);
                            } else {
                                $raw = trim((string) $distVal);
                                $distList = $raw !== '' ? array_values(array_filter(array_map('trim', explode(',', $raw)))) : [];
                                $distText = $raw;
                            }

                            $negText = $firstIdx !== null ? (string) ($negaraProdusenArray[$firstIdx] ?? '') : '';
                            $negText = trim($negText);

                            $kondisiVal = $firstIdx !== null ? (string) ($kondisiProdukArray[$firstIdx] ?? '') : '';
                            $jenisSuhuProdukVal = $firstIdx !== null ? (string) ($suhuProdukTypeArray[$firstIdx] ?? '') : '';
                            $nilaiSuhuProdukVal = $firstIdx !== null ? (string) ($suhuProdukArray[$firstIdx] ?? '') : '';
                            $suhuKondisiProdukVal = $firstIdx !== null ? (string) ($kondisiProdukSuhuArray[$firstIdx] ?? '') : '';

                            $kondisiProdukHeader = trim($kondisiVal);
                            $suhuProdukHeader = '';
                            if (trim($jenisSuhuProdukVal) !== '' || trim($nilaiSuhuProdukVal) !== '') {
                                $parts = [];
                                if (trim($jenisSuhuProdukVal) !== '') $parts[] = trim($jenisSuhuProdukVal);
                                if (trim($nilaiSuhuProdukVal) !== '') $parts[] = trim($nilaiSuhuProdukVal);
                                $suhuProdukHeader = implode(' - ', $parts);
                            }
                            $suhuKondisiProdukHeader = trim($suhuKondisiProdukVal);

                            $jenisSuhuMobilVal = $firstIdx !== null ? (string) ($suhuMobilTypeArray[$firstIdx] ?? '') : '';
                            $nilaiSuhuMobilVal = $firstIdx !== null ? (string) ($suhuMobilArray[$firstIdx] ?? '') : '';
                            $partsMobil = [];
                            if (trim($jenisSuhuMobilVal) !== '') $partsMobil[] = trim($jenisSuhuMobilVal);
                            if (trim($nilaiSuhuMobilVal) !== '') $partsMobil[] = trim($nilaiSuhuMobilVal);
                            $suhuMobilSummary = count($partsMobil) ? implode(' - ', $partsMobil) : '';
                        @endphp

                        <div class="card mb-3" style="border-left: 4px solid #435ebe;">
                            <div class="card-header bg-light">
                                <h6 class="mb-1">Produk {{ $loop->iteration }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex flex-wrap gap-2">
                                        @if($bahanNamaById[$bahanId] ?? null)
                                            <span class="badge bg-info">{{ $bahanNamaById[$bahanId] }}</span>
                                        @endif

                                        @if($negText !== '')
                                            <span class="badge bg-secondary">Negara: {{ $negText }}</span>
                                        @endif

                                        @if(count($prodList) > 0)
                                            <span class="badge bg-light text-dark border d-inline-block text-start p-2">
                                                <strong>Produsen:</strong>
                                                @if(count($prodList) > 1)
                                                    <ol class="mb-0 ps-3 mt-1 text-start">
                                                        @foreach($prodList as $item)<li>{{ $item }}</li>@endforeach
                                                    </ol>
                                                @else
                                                    <span class="ms-1">{{ $prodList[0] }}</span>
                                                @endif
                                            </span>
                                        @endif

                                        @if(count($distList) > 0)
                                            <span class="badge bg-light text-dark border d-inline-block text-start p-2">
                                                <strong>Distributor:</strong>
                                                @if(count($distList) > 1)
                                                    <ol class="mb-0 ps-3 mt-1 text-start">
                                                        @foreach($distList as $item)<li>{{ $item }}</li>@endforeach
                                                    </ol>
                                                @else
                                                    <span class="ms-1">{{ $distList[0] }}</span>
                                                @endif
                                            </span>
                                        @endif


                                        @if($suhuProdukHeader !== '')
                                            <span class="badge bg-light text-dark border d-inline-block text-start p-2">
                                                <strong>Suhu Produk:</strong>
                                                <span class="ms-1">{{ $suhuProdukHeader }}</span>
                                            </span>
                                        @endif

                                        @if($kondisiProdukHeader !== '' || $suhuKondisiProdukHeader !== '')
                                            <span class="badge bg-light text-dark border d-inline-block text-start p-2">
                                                @if($kondisiProdukHeader !== '')
                                                    <strong>Kondisi:</strong>
                                                    <span class="ms-1 me-2">{{ $kondisiProdukHeader }}</span>
                                                @endif
                                                @if($suhuKondisiProdukHeader !== '')
                                                    <strong>Suhu Kondisi:</strong>
                                                    <span class="ms-1">{{ $suhuKondisiProdukHeader }}</span>
                                                @endif
                                            </span>
                                        @endif

                                        @if($suhuMobilSummary !== '')
                                            <span class="badge bg-light text-dark border d-inline-block text-start p-2">
                                                <strong>Suhu Mobil:</strong>
                                                <span class="ms-1">{{ $suhuMobilSummary }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-2 small">
                                        <span class="me-2"><strong>Dokumen:</strong></span>
                                        @php
                                            $firstIdx = $detailIdxList[0] ?? null;
                                            $halalVal = $firstIdx !== null ? ($logoHalalArray[$firstIdx] ?? null) : null;
                                            $dokHalalVal = $firstIdx !== null ? ($dokumenHalalArray[$firstIdx] ?? null) : null;
                                            $coaVal = $firstIdx !== null ? ($coaArray[$firstIdx] ?? null) : null;
                                        @endphp

                                        @if($halalVal)
                                            <span class="badge bg-success me-1">Logo Halal ✓</span>
                                        @else
                                            <span class="badge bg-danger me-1">Logo Halal ✗</span>
                                        @endif

                                        @if($dokHalalVal)
                                            <span class="badge bg-success me-1">Dokumen Halal ✓</span>
                                        @else
                                            <span class="badge bg-danger me-1">Dokumen Halal ✗</span>
                                        @endif

                                        @if($coaVal)
                                            <span class="badge bg-success me-1">COA ✓</span>
                                        @else
                                            <span class="badge bg-danger me-1">COA ✗</span>
                                        @endif
                                    </div>
                                </div>

                                @foreach($detailIdxList as $detailNo => $index)
                                    <div class="border rounded p-3 mb-3" style="background: #fff;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold">Detail #{{ $loop->iteration }}</span>
                                            @if(isset($statusBarisArray[$index]))
                                                @if($statusBarisArray[$index] === 'Release')
                                                    <span class="badge bg-success">Release</span>
                                                @elseif($statusBarisArray[$index] === 'Hold')
                                                    <span class="badge bg-warning">Hold</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $statusBarisArray[$index] ?? '-' }}</span>
                                                @endif
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless table-sm mb-0">
                                                    <tr><td width="40%"><strong>Kode Produksi:</strong></td><td>{{ $kodeProduksiArray[$index] ?? '-' }}</td></tr>
                                                    <tr><td><strong>Expire Date:</strong></td><td>
                                                        @if(isset($expireDateArray[$index]) && $expireDateArray[$index])
                                                            {{ \Carbon\Carbon::parse($expireDateArray[$index])->format('d/m/Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td></tr>
                                                    <tr><td><strong>Jumlah Datang:</strong></td><td>{{ $jumlahDatangArray[$index] ?? '-' }} @if($unitDatangArray[$index] ?? null)<strong>{{ $unitDatangArray[$index] }}</strong>@endif</td></tr>
                                                    <tr><td><strong>Jumlah Sampling:</strong></td><td>{{ $jumlahSamplingArray[$index] ?? '-' }} @if($unitSamplingArray[$index] ?? null)<strong>{{ $unitSamplingArray[$index] }}</strong>@endif</td></tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless table-sm mb-0">
                                                    <tr><td width="40%"><strong>Hasil Uji FFA:</strong></td><td>{{ $hasilUjiFfaArray[$index] ?? '-' }}</td></tr>
                                                    <tr>
                                                        <td><strong>Kondisi Fisik:</strong></td>
                                                        <td>
                                                            <div class="d-flex flex-column gap-1">
                                                                <div class="d-flex align-items-center small">
                                                                    @if($kondisiFisikArray[$index]['kemasan'] ?? null)
                                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                    @else
                                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                    @endif
                                                                    <span>Kemasan</span>
                                                                </div>
                                                                <div class="d-flex align-items-center small">
                                                                    @if($kondisiFisikArray[$index]['warna'] ?? null)
                                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                    @else
                                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                    @endif
                                                                    <span>Warna</span>
                                                                </div>
                                                                <div class="d-flex align-items-center small">
                                                                    @if($kondisiFisikArray[$index]['benda_asing'] ?? null)
                                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                    @else
                                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                    @endif
                                                                    <span>Benda Asing</span>
                                                                </div>
                                                                <div class="d-flex align-items-center small">
                                                                    @if($kondisiFisikArray[$index]['aroma'] ?? null)
                                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                    @else
                                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                    @endif
                                                                    <span>Aroma</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6 offset-md-6">
                                                @php 
                                                    $coaFilePath = $fileCoaArray[$index] ?? null; 
                                                    $imgPath = $imageBahanBakuArray[$index] ?? null;
                                                @endphp
                                                <div class="d-flex justify-content-end gap-2 mt-2">
                                                    @if($coaFilePath)
                                                        <a href="{{ asset('storage/' . $coaFilePath) }}" target="_blank" class="btn btn-sm btn-info">
                                                           <i class="bi bi-file-earmark-text"></i> Lihat File COA 
                                                        </a>
                                                    @endif
                                                    @if($imgPath)
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $index }}">
                                                            <i class="bi bi-image"></i> Lihat Foto Produk
                                                        </button>

                                                        <!-- Modal Foto -->
                                                        <div class="modal fade" id="modalFoto{{ $index }}" tabindex="-1" aria-labelledby="modalFotoLabel{{ $index }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="modalFotoLabel{{ $index }}">Foto Produk - Detail #{{ $loop->iteration }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body text-center bg-light">
                                                                        <img src="{{ asset('storage/' . $imgPath) }}" alt="Foto Produk" class="img-fluid rounded shadow-sm border p-1" style="max-height: 80vh;">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a href="{{ asset('storage/' . $imgPath) }}" target="_blank" class="btn btn-info btn-sm">Buka di Tab Baru</a>
                                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if($spesifikasiArray[$index] ?? null)
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <strong>Spesifikasi:</strong>
                                                    <p class="mt-1 p-2 bg-light rounded small">{{ $spesifikasiArray[$index] }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($keteranganArray[$index] ?? null)
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <strong>Keterangan:</strong>
                                                    <p class="mt-1 p-2 bg-light rounded small">{{ $keteranganArray[$index] }}</p>
                                                </div>
                                            </div>
                                        @endif



                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">Tidak ada data dynamic form</div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
