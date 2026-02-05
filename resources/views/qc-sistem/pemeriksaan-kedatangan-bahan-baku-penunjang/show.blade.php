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
                                $prodText = implode(', ', array_values(array_filter($prodVal, fn ($v) => $v !== null && $v !== '')));
                            } else {
                                $prodText = trim((string) $prodVal);
                            }

                            $distVal = $firstIdx !== null ? ($distributorArray[$firstIdx] ?? null) : null;
                            if (is_array($distVal)) {
                                $distText = implode(', ', array_values(array_filter($distVal, fn ($v) => $v !== null && $v !== '')));
                            } else {
                                $distText = trim((string) $distVal);
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
                                <h6 class="mb-0">Produk {{ $loop->iteration }} - Bahan: {{ $bahanNamaById[$bahanId] ?? '-' }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="40%"><strong>Produsen:</strong></td>
                                                <td>{{ $prodText !== '' ? $prodText : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>Distributor:</strong></td>
                                                <td>{{ $distText !== '' ? $distText : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>Negara Produsen:</strong></td>
                                                <td>{{ $negText !== '' ? $negText : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>Kondisi Produk:</strong></td>
                                                <td>
                                                    @if($kondisiProdukHeader !== '')
                                                        <span class="badge bg-secondary">{{ $kondisiProdukHeader }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>Suhu Produk:</strong></td>
                                                <td>{{ $suhuProdukHeader !== '' ? $suhuProdukHeader : '' }}</td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>Suhu Kondisi Produk:</strong></td>
                                                <td>{{ $suhuKondisiProdukHeader !== '' ? $suhuKondisiProdukHeader : '' }}</td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>Suhu Mobil:</strong></td>
                                                <td>{{ $suhuMobilSummary !== '' ? $suhuMobilSummary : '' }}</td>
                                            </tr>
                                        </table>
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
                                                <table class="table table-borderless table-sm">
                                                    <tr><td width="40%"><strong>Kode Produksi:</strong></td><td>{{ $kodeProduksiArray[$index] ?? '-' }}</td></tr>
                                                    <tr><td><strong>Expire Date:</strong></td><td>
                                                        @if(isset($expireDateArray[$index]) && $expireDateArray[$index])
                                                            {{ \Carbon\Carbon::parse($expireDateArray[$index])->format('d/m/Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td></tr>
                                                    <tr><td><strong>Jumlah Datang:</strong></td><td>{{ $jumlahDatangArray[$index] ?? '-' }}</td></tr>
                                                    <tr><td><strong>Jumlah Sampling:</strong></td><td>{{ $jumlahSamplingArray[$index] ?? '-' }}</td></tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless table-sm">
                                                    <tr><td><strong>Hasil Uji FFA:</strong></td><td>{{ $hasilUjiFfaArray[$index] ?? '-' }}</td></tr>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
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
                                                        @if(isset($kondisiFisikArray[$index][$key]) && $kondisiFisikArray[$index][$key])
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
                                                    @if($logoHalalArray[$index] ?? null)
                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                    @else
                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                    @endif
                                                    <span>Logo Halal</span>
                                                </div>
                                                <div class="d-flex align-items-center small mb-1">
                                                    @if($dokumenHalalArray[$index] ?? null)
                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                    @else
                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                    @endif
                                                    <span>Dokumen Halal</span>
                                                </div>
                                                <div class="d-flex align-items-center small mb-1">
                                                    @if($coaArray[$index] ?? null)
                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                    @else
                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                    @endif
                                                    <span>COA</span>
                                                </div>

                                                @php
                                                    $coaFilePath = $fileCoaArray[$index] ?? null;
                                                @endphp
                                                @if($coaFilePath)
                                                    <div class="d-flex align-items-center small mb-1">
                                                        <span class="badge bg-info me-2" style="min-width: 24px;">i</span>
                                                        <a href="{{ asset('storage/' . $coaFilePath) }}" target="_blank">Lihat File COA</a>
                                                    </div>
                                                @endif
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

                                        @php
                                            $imgPath = $imageBahanBakuArray[$index] ?? null;
                                        @endphp
                                        @if($imgPath)
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <h6 class="text-primary small mb-2">Foto Bahan Baku</h6>
                                                    <div class="p-2 bg-white rounded">
                                                        <img src="{{ asset('storage/' . $imgPath) }}" alt="Foto Bahan Baku" style="max-width: 260px; height: auto; border: 1px solid #ddd; padding: 4px;">
                                                    </div>
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
