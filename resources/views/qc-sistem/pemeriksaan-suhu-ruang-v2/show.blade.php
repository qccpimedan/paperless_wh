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
                    <h3>CS Meat</h3>
                    <p class="text-subtitle text-muted">Detail CS Meat</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang-v2.index') }}">Pemeriksaan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Pemeriksaan</li>
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
                            <h4 class="card-title">Detail CS Meat </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Tanggal</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->tanggal->format('d-m-Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Shift</strong></label>
                                            <p class="form-control-plaintext"><span class="badge bg-info">{{ $pemeriksaanSuhuRuangV2->shift->shift ?? '-' }}</span></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Pukul</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->pukul ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Suhu Produk</strong></label>
                                            <p class="form-control-plaintext"><span class="badge bg-secondary">{{ $pemeriksaanSuhuRuangV2->suhu_produk ?? '-' }}</span></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Produk</strong></label>
                                            <p class="form-control-plaintext">
                                                @if($pemeriksaanSuhuRuangV2->produk)
                                                    @if($pemeriksaanSuhuRuangV2->produk->kategori_code)
                                                        <span class="badge bg-primary">{{ $pemeriksaanSuhuRuangV2->produk->kategori_code }}</span>
                                                    @endif
                                                    {{ $pemeriksaanSuhuRuangV2->produk->nama_produk ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                </div>

                                <!-- Cold Storage Section -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_cold_storage))
                                    @php
                                        $coldStorageData = $pemeriksaanSuhuRuangV2->suhu_cold_storage;
                                    @endphp
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Cold Storage</strong></h5>
                                        </div>
                                        @foreach($coldStorageData as $unit => $item)
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label"><strong>CS {{ $unit }}</strong></label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Setting (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['setting'] ?? '-' }}
                                                            @if($item['setting'] == '-18')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['setting'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Display (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['display'] ?? '-' }}
                                                            @if($item['display'] == '-18')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['display'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Actual (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['actual'] ?? '-' }}
                                                            @if($item['actual'] == '-18')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['actual'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Anteroom Loading Section -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_anteroom_loading))
                                    @php
                                        $anteroomLoadingData = $pemeriksaanSuhuRuangV2->suhu_anteroom_loading;
                                    @endphp
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Anteroom Loading</strong></h5>
                                        </div>
                                        @foreach($anteroomLoadingData as $unit => $item)
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label"><strong>Anteroom Loading {{ $unit }}</strong></label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Setting (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['setting'] ?? '-' }}
                                                            @if(strpos($item['setting'] ?? '', '(0±5°C)') !== false || $item['setting'] == '(0±5°C)')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['setting'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Display (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['display'] ?? '-' }}
                                                            @if(strpos($item['display'] ?? '', '(0±5°C)') !== false || $item['display'] == '(0±5°C)')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['display'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Actual (°C)</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item['actual'] ?? '-' }}
                                                            @if(strpos($item['actual'] ?? '', '(0±5°C)') !== false || $item['actual'] == '(0±5°C)')
                                                                <span class="badge bg-info ms-2">Std</span>
                                                            @elseif($item['actual'])
                                                                <span class="badge bg-warning ms-2">Manual</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Suhu Pre Loading -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_pre_loading))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Suhu Pre Loading</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_pre_loading['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_pre_loading['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_pre_loading['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Suhu Prestaging -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_prestaging))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Suhu Prestaging</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_prestaging['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_prestaging['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_prestaging['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Suhu Anteroom Ekspansi ABF -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Suhu Anteroom Ekspansi ABF</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Suhu Chillroom RM -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_chillroom_rm))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Suhu Chillroom RM</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_rm['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Suhu Chillroom Domestik -->
                                @if(!empty($pemeriksaanSuhuRuangV2->suhu_chillroom_domestik))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Suhu Chillroom Domestik</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Catatan Section -->
                                @if($pemeriksaanSuhuRuangV2->keterangan || $pemeriksaanSuhuRuangV2->tindakan_koreksi)
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Catatan</strong></h5>
                                        </div>
                                        @if($pemeriksaanSuhuRuangV2->keterangan)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Keterangan</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->keterangan }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($pemeriksaanSuhuRuangV2->tindakan_koreksi)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Tindakan Koreksi</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV2->tindakan_koreksi }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Riwayat Data Per Jam -->
                                @php
                                    $historiesV2 = $pemeriksaanSuhuRuangV2->histories->sortBy('id');
                                    $firstHistory = $historiesV2->first();
                                    $timelineRowsV2 = [];
                                    $noV2 = 1;

                                    $rawInitPukul = $firstHistory && $firstHistory->pukul_lama ? $firstHistory->pukul_lama : $pemeriksaanSuhuRuangV2->pukul;
                                    $initJamV2 = $rawInitPukul ? \Carbon\Carbon::parse($rawInitPukul)->format('H:i') : '-';
                                    $initCreatedV2 = $pemeriksaanSuhuRuangV2->created_at ? $pemeriksaanSuhuRuangV2->created_at->format('d/m/Y H:i') : '-';

                                    $sectionLabelsV2 = [
                                        'cold_storage'          => 'Cold Storage',
                                        'anteroom_loading'      => 'Anteroom Loading',
                                        'pre_loading'           => 'Pre Loading',
                                        'prestaging'            => 'Prestaging',
                                        'anteroom_ekspansi_abf' => 'Anteroom Ekspansi ABF',
                                        'chillroom_rm'          => 'Chillroom RM',
                                        'chillroom_domestik'    => 'Chillroom Domestik',
                                    ];

                                    // Mengambil nilai data awal (jika pernah di-edit/update, gunakan *_lama dari history pertama)
                                    $initColdStorageData = $firstHistory && $firstHistory->suhu_cold_storage_lama !== null
                                        ? (is_array($firstHistory->suhu_cold_storage_lama) ? $firstHistory->suhu_cold_storage_lama : (json_decode($firstHistory->suhu_cold_storage_lama, true) ?: []))
                                        : $pemeriksaanSuhuRuangV2->suhu_cold_storage;

                                    $initAnteroomLoadingData = $firstHistory && $firstHistory->suhu_anteroom_loading_lama !== null
                                        ? (is_array($firstHistory->suhu_anteroom_loading_lama) ? $firstHistory->suhu_anteroom_loading_lama : (json_decode($firstHistory->suhu_anteroom_loading_lama, true) ?: []))
                                        : $pemeriksaanSuhuRuangV2->suhu_anteroom_loading;

                                    $initSuhuProdukVal = $firstHistory && $firstHistory->suhu_produk_lama !== null
                                        ? $firstHistory->suhu_produk_lama
                                        : $pemeriksaanSuhuRuangV2->suhu_produk;

                                    // Input Awal Cold Storage
                                    if (!empty($initColdStorageData)) {
                                        foreach ((array)$initColdStorageData as $uId => $uVal) {
                                            $timelineRowsV2[] = [
                                                'no'     => $noV2++,
                                                'waktu'  => $initJamV2,
                                                'edited' => $initCreatedV2,
                                                'area'   => 'Cold Storage ' . $uId,
                                                'setting'=> $uVal['setting'] ?? '-',
                                                'aktual' => $uVal['actual'] ?? '-',
                                                'display'=> $uVal['display'] ?? '-',
                                                'tipe'   => 'awal',
                                            ];
                                        }
                                    }

                                    // Input Awal Anteroom Loading
                                    if (!empty($initAnteroomLoadingData)) {
                                        foreach ((array)$initAnteroomLoadingData as $uId => $uVal) {
                                            $timelineRowsV2[] = [
                                                'no'     => $noV2++,
                                                'waktu'  => $initJamV2,
                                                'edited' => $initCreatedV2,
                                                'area'   => 'Anteroom Loading ' . $uId,
                                                'setting'=> $uVal['setting'] ?? '-',
                                                'aktual' => $uVal['actual'] ?? '-',
                                                'display'=> $uVal['display'] ?? '-',
                                                'tipe'   => 'awal',
                                            ];
                                        }
                                    }

                                    // Input Awal Single Sections
                                    foreach (['pre_loading', 'prestaging', 'anteroom_ekspansi_abf', 'chillroom_rm', 'chillroom_domestik'] as $sKey) {
                                        $attrLama = 'suhu_' . $sKey . '_lama';
                                        $attr = 'suhu_' . $sKey;
                                        $val = ($firstHistory && $firstHistory->{$attrLama} !== null)
                                            ? (is_array($firstHistory->{$attrLama}) ? $firstHistory->{$attrLama} : (json_decode($firstHistory->{$attrLama}, true) ?: []))
                                            : $pemeriksaanSuhuRuangV2->{$attr};

                                        if (!empty($val) && (!empty($val['setting']) || !empty($val['display']) || !empty($val['actual']))) {
                                            $timelineRowsV2[] = [
                                                'no'     => $noV2++,
                                                'waktu'  => $initJamV2,
                                                'edited' => $initCreatedV2,
                                                'area'   => $sectionLabelsV2[$sKey],
                                                'setting'=> $val['setting'] ?? '-',
                                                'aktual' => $val['actual'] ?? '-',
                                                'display'=> $val['display'] ?? '-',
                                                'tipe'   => 'awal',
                                            ];
                                        }
                                    }

                                    // Input Awal Suhu Produk
                                    if (!empty($initSuhuProdukVal)) {
                                        $timelineRowsV2[] = [
                                            'no'     => $noV2++,
                                            'waktu'  => $initJamV2,
                                            'edited' => $initCreatedV2,
                                            'area'   => 'Suhu Produk',
                                            'setting'=> '-',
                                            'aktual' => $initSuhuProdukVal,
                                            'display'=> '-',
                                            'tipe'   => 'awal',
                                        ];
                                    }

                                    // Iterasi History Updates
                                    foreach ($historiesV2 as $h) {
                                        $editedAtV2 = $h->created_at ? $h->created_at->format('d/m/Y H:i') : '-';
                                        $pukulH = $h->pukul_baru ?? $h->pukul_lama ?? null;
                                        $jamH = $pukulH ? \Carbon\Carbon::parse($pukulH)->format('H:i') : '-';

                                        foreach ($sectionLabelsV2 as $secKey => $secLabel) {
                                            $colLama = 'suhu_' . $secKey . '_lama';
                                            $colBaru = 'suhu_' . $secKey . '_baru';

                                            $lamaData = is_array($h->{$colLama}) ? $h->{$colLama} : (json_decode($h->{$colLama} ?? '[]', true) ?: []);
                                            $baruData = is_array($h->{$colBaru}) ? $h->{$colBaru} : (json_decode($h->{$colBaru} ?? '[]', true) ?: []);

                                            if (json_encode($lamaData) === json_encode($baruData)) continue;

                                            if (in_array($secKey, ['cold_storage', 'anteroom_loading'])) {
                                                $allUnits = array_unique(array_merge(array_keys((array)$lamaData), array_keys((array)$baruData)));
                                                foreach ($allUnits as $uId) {
                                                    $lItem = $lamaData[$uId] ?? [];
                                                    $bItem = $baruData[$uId] ?? [];
                                                    if (json_encode($lItem) === json_encode($bItem)) continue;
                                                    if (empty($bItem['setting']) && empty($bItem['display']) && empty($bItem['actual'])) continue;

                                                    $timelineRowsV2[] = [
                                                        'no'     => $noV2++,
                                                        'waktu'  => $jamH,
                                                        'edited' => $editedAtV2,
                                                        'area'   => $secLabel . ' ' . $uId,
                                                        'setting'=> $bItem['setting'] ?? '-',
                                                        'aktual' => $bItem['actual'] ?? '-',
                                                        'display'=> $bItem['display'] ?? '-',
                                                        'tipe'   => 'update',
                                                    ];
                                                }
                                            } else {
                                                if (empty($baruData['setting']) && empty($baruData['display']) && empty($baruData['actual'])) continue;
                                                $timelineRowsV2[] = [
                                                    'no'     => $noV2++,
                                                    'waktu'  => $jamH,
                                                    'edited' => $editedAtV2,
                                                    'area'   => $secLabel,
                                                    'setting'=> $baruData['setting'] ?? '-',
                                                    'aktual' => $baruData['actual'] ?? '-',
                                                    'display'=> $baruData['display'] ?? '-',
                                                    'tipe'   => 'update',
                                                ];
                                            }
                                        }

                                        // Update Suhu Produk
                                        if (($h->suhu_produk_lama ?? null) !== ($h->suhu_produk_baru ?? null) && !empty($h->suhu_produk_baru)) {
                                            $timelineRowsV2[] = [
                                                'no'     => $noV2++,
                                                'waktu'  => $jamH,
                                                'edited' => $editedAtV2,
                                                'area'   => 'Suhu Produk',
                                                'setting'=> '-',
                                                'aktual' => $h->suhu_produk_baru,
                                                'display'=> '-',
                                                'tipe'   => 'update',
                                            ];
                                        }
                                    }
                                @endphp

                                @if(!empty($timelineRowsV2))
                                <div class="row mt-5 pt-3 border-top">
                                    <div class="col-md-12">
                                        <h5 class="mb-3 d-flex align-items-center gap-2">
                                            <i class="bi bi-clock-history text-primary"></i>
                                            <strong>Riwayat Data Per Jam</strong>
                                            <span class="badge bg-primary ms-1">{{ count($timelineRowsV2) }} entri</span>
                                        </h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-sm align-middle">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th class="text-center" style="width:4%">No</th>
                                                        <th class="text-center" style="width:8%">Pukul</th>
                                                        <th style="width:22%">Area</th>
                                                        <th class="text-center">Setting (°C)</th>
                                                        <th class="text-center">Actual (°C)</th>
                                                        <th class="text-center">Display (°C)</th>
                                                        <th class="text-center" style="width:8%">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($timelineRowsV2 as $tRow)
                                                    <tr class="{{ $tRow['tipe'] === 'update' ? 'table-warning' : '' }}">
                                                        <td class="text-center">{{ $tRow['no'] }}</td>
                                                        <td class="text-center fw-semibold">{{ $tRow['waktu'] }}</td>
                                                        <td>{{ $tRow['area'] }}</td>
                                                        <td class="text-center">{{ $tRow['setting'] }}</td>
                                                        <td class="text-center">{{ $tRow['aktual'] }}</td>
                                                        <td class="text-center">{{ $tRow['display'] }}</td>
                                                        <td class="text-center">
                                                            @if($tRow['tipe'] === 'awal')
                                                                <span class="badge bg-success">Input Awal</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark">Update</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="col-md-12 d-flex justify-content-end mt-4">
                                    <a href="{{ route('pemeriksaan-suhu-ruang-v2.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                    <a href="{{ route('pemeriksaan-suhu-ruang-v2.edit', $pemeriksaanSuhuRuangV2->uuid) }}" class="btn btn-primary me-1 mb-1">Edit</a>
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