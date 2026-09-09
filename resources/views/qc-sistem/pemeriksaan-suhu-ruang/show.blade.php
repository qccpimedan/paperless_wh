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
                    <h3>Food Prosesing</h3>
                    <p class="text-subtitle text-muted">Detail Food Prosesing</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang.index') }}">Pemeriksaan</a></li>
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
                            <h4 class="card-title">Detail Food Prosesing</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Tanggal</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->tanggal->format('d-m-Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Shift</strong></label>
                                            <p class="form-control-plaintext"><span class="badge bg-info">{{ $pemeriksaanSuhuRuang->shift->shift ?? '-' }}</span></p>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Dibuat Pada</strong></label>
                                           <p class="text-muted">{{ $pemeriksaanSuhuRuang->created_at->format('d M Y H:i:s')  }}</p>
                                        </div>
                                    </div> -->

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Produk</strong></label>
                                            <p class="form-control-plaintext">
                                                @if($pemeriksaanSuhuRuang->produk)
                                                    @if($pemeriksaanSuhuRuang->produk->kategori_code)
                                                        <span class="badge bg-primary">{{ $pemeriksaanSuhuRuang->produk->kategori_code }}</span>
                                                    @endif
                                                    {{ $pemeriksaanSuhuRuang->produk->nama_produk ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Suhu Produk</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_produk ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Pukul</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->pukul ? \Carbon\Carbon::parse($pemeriksaanSuhuRuang->pukul)->format('H:i') : '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cold Storage Section -->
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['cold_storage'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Cold Storage</strong></h5>
                                        </div>
                                        @foreach($pemeriksaanSuhuRuang->suhu_data['cold_storage'] as $item)
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label"><strong>CS {{ $item['unit'] }}</strong></label>
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
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['anteroom_loading'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Anteroom Loading</strong></h5>
                                        </div>
                                        @foreach($pemeriksaanSuhuRuang->suhu_data['anteroom_loading'] as $item)
                                            <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label"><strong>Anteroom Loading {{ $item['unit'] }}</strong></label>
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

                                <!-- Pre Loading Section -->
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['pre_loading'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Pre Loading</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['pre_loading']['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['pre_loading']['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['pre_loading']['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Prestaging Section -->
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['prestaging'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Prestaging</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['prestaging']['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['prestaging']['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['prestaging']['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Anteroom Ekspansi Further Section -->
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_further'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Anteroom Ekspansi Further</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_further']['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_further']['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_further']['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Anteroom Ekspansi Sausage Section -->
                                @if(!empty($pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_sausage'] ?? []))
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Anteroom Ekspansi Sausage</strong></h5>
                                        </div>
                                        <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Setting (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_sausage']['setting'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Display (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_sausage']['display'] ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Actual (°C)</label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->suhu_data['anteroom_ekspansi_sausage']['actual'] ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Catatan Section -->
                                @if($pemeriksaanSuhuRuang->keterangan || $pemeriksaanSuhuRuang->tindakan_koreksi)
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Catatan</strong></h5>
                                        </div>
                                        @if($pemeriksaanSuhuRuang->keterangan)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Keterangan</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->keterangan }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($pemeriksaanSuhuRuang->tindakan_koreksi)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Tindakan Koreksi</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuang->tindakan_koreksi }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                {{-- ===== RIWAYAT DATA PER JAM ===== --}}
                                @php
                                    $sectionLabels = [
                                        'cold_storage'               => 'Cold Storage',
                                        'anteroom_loading'           => 'Anteroom Loading',
                                        'pre_loading'                => 'Pre Loading',
                                        'prestaging'                 => 'Prestaging',
                                        'anteroom_ekspansi_further'  => 'Anteroom Ekspansi Further',
                                        'anteroom_ekspansi_sausage'  => 'Anteroom Ekspansi Sausage',
                                    ];

                                    $pickVal = function ($arr, $key) {
                                        if (!is_array($arr)) return '-';
                                        return $arr[$key] ?? '-';
                                    };

                                    $currentSuhu = is_array($pemeriksaanSuhuRuang->suhu_data)
                                        ? $pemeriksaanSuhuRuang->suhu_data
                                        : (json_decode($pemeriksaanSuhuRuang->suhu_data ?? '[]', true) ?: []);

                                    // Susun timeline: input awal + setiap history
                                    $histories = $pemeriksaanSuhuRuang->relationLoaded('histories') && $pemeriksaanSuhuRuang->histories
                                        ? $pemeriksaanSuhuRuang->histories->sortBy('created_at')
                                        : collect();

                                    $timelineRows = [];
                                    $no = 1;

                                    // === Input Data Pertama ===
                                    $firstHistory = $histories->first();
                                    $initSuhu = $firstHistory
                                        ? (is_array($firstHistory->suhu_data_lama) ? $firstHistory->suhu_data_lama : (json_decode($firstHistory->suhu_data_lama ?? '[]', true) ?: []))
                                        : $currentSuhu;
                                    $initPukul = $firstHistory ? ($firstHistory->pukul_lama ?? $pemeriksaanSuhuRuang->pukul) : $pemeriksaanSuhuRuang->pukul;

                                    foreach ($sectionLabels as $secKey => $secLabel) {
                                        $secData = $initSuhu[$secKey] ?? [];
                                        if (empty($secData)) continue;

                                        if (in_array($secKey, ['cold_storage', 'anteroom_loading'])) {
                                            foreach ((array) $secData as $item) {
                                                if (!is_array($item) || (empty($item['setting']) && empty($item['display']) && empty($item['actual']))) continue;
                                                $timelineRows[] = [
                                                    'no'     => $no++,
                                                    'waktu'  => $initPukul ? \Carbon\Carbon::parse($initPukul)->format('H:i') : '-',
                                                    'edited' => $pemeriksaanSuhuRuang->created_at ? $pemeriksaanSuhuRuang->created_at->format('d/m/Y H:i') : '-',
                                                    'area'   => $secLabel . ' ' . ($item['unit'] ?? ''),
                                                    'setting'=> $item['setting'] ?? '-',
                                                    'aktual' => $item['actual'] ?? '-',
                                                    'display'=> $item['display'] ?? '-',
                                                    'tipe'   => 'awal',
                                                ];
                                            }
                                        } else {
                                            if (empty($secData['setting']) && empty($secData['display']) && empty($secData['actual'])) continue;
                                            $timelineRows[] = [
                                                'no'     => $no++,
                                                'waktu'  => $initPukul ? \Carbon\Carbon::parse($initPukul)->format('H:i') : '-',
                                                'edited' => $pemeriksaanSuhuRuang->created_at ? $pemeriksaanSuhuRuang->created_at->format('d/m/Y H:i') : '-',
                                                'area'   => $secLabel,
                                                'setting'=> $secData['setting'] ?? '-',
                                                'aktual' => $secData['actual'] ?? '-',
                                                'display'=> $secData['display'] ?? '-',
                                                'tipe'   => 'awal',
                                            ];
                                        }
                                    }

                                    // === Suhu Produk - Input Awal ===
                                    $initSuhuProduk = $firstHistory ? ($firstHistory->suhu_produk_lama ?? $pemeriksaanSuhuRuang->suhu_produk) : $pemeriksaanSuhuRuang->suhu_produk;
                                    if (!empty($initSuhuProduk)) {
                                        $timelineRows[] = [
                                            'no'     => $no++,
                                            'waktu'  => $initPukul ? \Carbon\Carbon::parse($initPukul)->format('H:i') : '-',
                                            'edited' => $pemeriksaanSuhuRuang->created_at ? $pemeriksaanSuhuRuang->created_at->format('d/m/Y H:i') : '-',
                                            'area'   => 'Suhu Produk',
                                            'setting'=> '-',
                                            'aktual' => $initSuhuProduk,
                                            'display'=> '-',
                                            'tipe'   => 'awal',
                                        ];
                                    }

                                    foreach ($histories as $h) {
                                        $lamaSuhu = is_array($h->suhu_data_lama) ? $h->suhu_data_lama : (json_decode($h->suhu_data_lama ?? '[]', true) ?: []);
                                        $baruSuhu = is_array($h->suhu_data_baru) ? $h->suhu_data_baru : (json_decode($h->suhu_data_baru ?? '[]', true) ?: []);
                                        $editedAt = $h->created_at ? $h->created_at->format('d/m/Y H:i') : '-';
                                        $pukul = $h->pukul_baru ?? $h->pukul_lama ?? null;
                                        $jam = $pukul ? \Carbon\Carbon::parse($pukul)->format('H:i') : '-';

                                        foreach ($sectionLabels as $secKey => $secLabel) {
                                            $lamaData = $lamaSuhu[$secKey] ?? [];
                                            $baruData = $baruSuhu[$secKey] ?? [];
                                            if (json_encode($lamaData) === json_encode($baruData)) continue;

                                            if (in_array($secKey, ['cold_storage', 'anteroom_loading'])) {
                                                $allItems = array_unique(array_merge(
                                                    array_map(fn($r) => $r['unit'] ?? '', (array) $lamaData),
                                                    array_map(fn($r) => $r['unit'] ?? '', (array) $baruData)
                                                ));
                                                foreach ($allItems as $unitId) {
                                                    $bItem = collect((array) $baruData)->firstWhere('unit', $unitId) ?? [];
                                                    $lItem = collect((array) $lamaData)->firstWhere('unit', $unitId) ?? [];
                                                    if (json_encode($lItem) === json_encode($bItem)) continue;
                                                    if (empty($bItem['setting']) && empty($bItem['display']) && empty($bItem['actual'])) continue;
                                                    $timelineRows[] = [
                                                        'no'     => $no++,
                                                        'waktu'  => $jam,
                                                        'edited' => $editedAt,
                                                        'area'   => $secLabel . ' ' . $unitId,
                                                        'setting'=> $bItem['setting'] ?? '-',
                                                        'aktual' => $bItem['actual'] ?? '-',
                                                        'display'=> $bItem['display'] ?? '-',
                                                        'tipe'   => 'update',
                                                    ];
                                                }
                                            } else {
                                                if (empty($baruData['setting']) && empty($baruData['display']) && empty($baruData['actual'])) continue;
                                                $timelineRows[] = [
                                                    'no'     => $no++,
                                                    'waktu'  => $jam,
                                                    'edited' => $editedAt,
                                                    'area'   => $secLabel,
                                                    'setting'=> $baruData['setting'] ?? '-',
                                                    'aktual' => $baruData['actual'] ?? '-',
                                                    'display'=> $baruData['display'] ?? '-',
                                                    'tipe'   => 'update',
                                                ];
                                            }
                                        }

                                        // === Suhu Produk - Update ===
                                        if (($h->suhu_produk_lama ?? null) !== ($h->suhu_produk_baru ?? null) && !empty($h->suhu_produk_baru)) {
                                            $timelineRows[] = [
                                                'no'     => $no++,
                                                'waktu'  => $jam,
                                                'edited' => $editedAt,
                                                'area'   => 'Suhu Produk',
                                                'setting'=> '-',
                                                'aktual' => $h->suhu_produk_baru,
                                                'display'=> '-',
                                                'tipe'   => 'update',
                                            ];
                                        }
                                    }
                                @endphp

                                @if(!empty($timelineRows))
                                <div class="row mt-5">
                                    <div class="col-md-12">
                                        <h5 class="mb-3 d-flex align-items-center gap-2">
                                            <i class="bi bi-clock-history text-primary"></i>
                                            <strong>Riwayat Data Per Jam</strong>
                                            <span class="badge bg-primary ms-1">{{ count($timelineRows) }} entri</span>
                                        </h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-sm align-middle">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th class="text-center" style="width:4%">No</th>
                                                        <th class="text-center" style="width:8%">Pukul</th>
                                                        <!-- <th class="text-center" style="width:14%">Diedit Pada</th> -->
                                                        <th style="width:22%">Area</th>
                                                        <th class="text-center">Setting (°C)</th>
                                                        <th class="text-center">Actual (°C)</th>
                                                        <th class="text-center">Display (°C)</th>
                                                        <th class="text-center" style="width:8%">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($timelineRows as $tRow)
                                                    <tr class="{{ $tRow['tipe'] === 'update' ? 'table-warning' : '' }}">
                                                        <td class="text-center">{{ $tRow['no'] }}</td>
                                                        <td class="text-center fw-semibold">{{ $tRow['waktu'] }}</td>
                                                        <!-- <td class="text-center text-muted" style="font-size:0.8rem">{{ $tRow['edited'] }}</td> -->
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
                                    <a href="{{ route('pemeriksaan-suhu-ruang.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                    <a href="{{ route('pemeriksaan-suhu-ruang.edit', $pemeriksaanSuhuRuang->uuid) }}" class="btn btn-primary me-1 mb-1">Edit</a>
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
