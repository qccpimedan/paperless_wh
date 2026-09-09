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
                    <h3>Pemeriksaan Suhu Ruang V3</h3>
                    <p class="text-subtitle text-muted">Detail pemeriksaan suhu ruang V3</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}">Pemeriksaan V3</a></li>
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
                            <h4 class="card-title">Detail Pemeriksaan Suhu Ruang V3</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Tanggal</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV3->tanggal->format('d-m-Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Shift</strong></label>
                                            <p class="form-control-plaintext"><span class="badge bg-info">{{ $pemeriksaanSuhuRuangV3->shift->shift ?? '-' }}</span></p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Pukul</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV3->pukul }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>User</strong></label>
                                            <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV3->user->name ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Plant</strong></label>
                                            <p class="form-control-plaintext"><span class="badge bg-info">{{ $pemeriksaanSuhuRuangV3->user->plant->plant ?? '-' }}</span></p>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $suhuFields = [
                                        'suhu_premix' => 'Suhu Premix',
                                        'suhu_seasoning' => 'Suhu Seasoning',
                                        'suhu_dry' => 'Suhu Dry',
                                        'suhu_cassing' => 'Suhu Cassing',
                                        'suhu_beef' => 'Suhu Beef',
                                        'suhu_packaging' => 'Suhu Packaging',
                                        'suhu_ruang_chemical' => 'Suhu Ruang Chemical',
                                        'suhu_ruang_seasoning' => 'Suhu Ruang Seasoning'
                                    ];
                                @endphp

                                @foreach($suhuFields as $fieldKey => $fieldLabel)
                                    @if(!empty($pemeriksaanSuhuRuangV3->$fieldKey))
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h5 class="mb-3"><strong>{{ $fieldLabel }}</strong></h5>
                                            </div>
                                            @foreach($pemeriksaanSuhuRuangV3->$fieldKey as $unit => $data)
                                                <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label"><strong>{{ $fieldLabel }} {{ str_replace('unit_', '', (string) $unit) }}</strong></label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Setting (°C)</label>
                                                            <p class="form-control-plaintext">{{ $data['setting'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Display (°C)</label>
                                                            <p class="form-control-plaintext">{{ $data['display'] ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Actual (°C)</label>
                                                            <p class="form-control-plaintext">{{ $data['actual'] ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Catatan Section -->
                                @if($pemeriksaanSuhuRuangV3->keterangan || $pemeriksaanSuhuRuangV3->tindakan_koreksi)
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"><strong>Catatan</strong></h5>
                                        </div>
                                        @if($pemeriksaanSuhuRuangV3->keterangan)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Keterangan</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV3->keterangan }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($pemeriksaanSuhuRuangV3->tindakan_koreksi)
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Tindakan Koreksi</strong></label>
                                                    <p class="form-control-plaintext">{{ $pemeriksaanSuhuRuangV3->tindakan_koreksi }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Riwayat Data Per Jam -->
                                @php
                                    $historiesV3 = $pemeriksaanSuhuRuangV3->histories->sortBy('id');
                                    $timelineRowsV3 = [];
                                    $noCounterV3 = 1;

                                    $suhuSectionsV3 = [
                                        'premix'          => ['label' => 'Suhu Premix', 'field' => 'suhu_premix'],
                                        'seasoning'       => ['label' => 'Suhu Seasoning', 'field' => 'suhu_seasoning'],
                                        'dry'             => ['label' => 'Suhu Dry', 'field' => 'suhu_dry'],
                                        'cassing'         => ['label' => 'Suhu Cassing', 'field' => 'suhu_cassing'],
                                        'beef'            => ['label' => 'Suhu Beef', 'field' => 'suhu_beef'],
                                        'packaging'       => ['label' => 'Suhu Packaging', 'field' => 'suhu_packaging'],
                                        'ruang_chemical'  => ['label' => 'Suhu Ruang Chemical', 'field' => 'suhu_ruang_chemical'],
                                        'ruang_seasoning' => ['label' => 'Suhu Ruang Seasoning', 'field' => 'suhu_ruang_seasoning'],
                                    ];

                                    foreach ($suhuSectionsV3 as $secKey => $secConf) {
                                        $fieldVal = $pemeriksaanSuhuRuangV3->{$secConf['field']};
                                        if (!empty($fieldVal) && is_array($fieldVal)) {
                                            foreach ($fieldVal as $unitKey => $itemData) {
                                                if (is_array($itemData)) {
                                                    $unitNum = str_replace('unit_', '', (string)$unitKey);
                                                    $timelineRowsV3[] = [
                                                        'no'           => $noCounterV3++,
                                                        'waktu'        => $pemeriksaanSuhuRuangV3->pukul ?? '-',
                                                        'area'         => $secConf['label'] . ' ' . $unitNum,
                                                        'setting'      => $itemData['setting'] ?? '-',
                                                        'aktual'       => $itemData['actual'] ?? '-',
                                                        'display'      => $itemData['display'] ?? '-',
                                                        'tipe'         => 'awal',
                                                    ];
                                                }
                                            }
                                        }
                                    }

                                    foreach ($historiesV3 as $hItem) {
                                        $waktuRow = $hItem->pukul_baru ?? $hItem->pukul_lama ?? $hItem->created_at->format('H:i');

                                        foreach ($suhuSectionsV3 as $secKey => $secConf) {
                                            $columnBaru = 'suhu_' . $secKey . '_baru';
                                            $valBaru    = $hItem->{$columnBaru};

                                            if (!empty($valBaru)) {
                                                $arrayBaru = is_array($valBaru)
                                                    ? $valBaru
                                                    : (json_decode($valBaru ?? '[]', true) ?: []);

                                                if (is_array($arrayBaru)) {
                                                    foreach ($arrayBaru as $unitKey => $itemData) {
                                                        if (is_array($itemData)) {
                                                            $unitNum = str_replace('unit_', '', (string)$unitKey);
                                                            $timelineRowsV3[] = [
                                                                'no'           => $noCounterV3++,
                                                                'waktu'        => $waktuRow,
                                                                'area'         => $secConf['label'] . ' ' . $unitNum,
                                                                'setting'      => $itemData['setting'] ?? '-',
                                                                'aktual'       => $itemData['actual'] ?? '-',
                                                                'display'      => $itemData['display'] ?? '-',
                                                                'tipe'         => 'update',
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                @endphp

                                @if(!empty($timelineRowsV3))
                                <div class="row mt-5 pt-3 border-top">
                                    <div class="col-md-12">
                                        <h5 class="mb-3 d-flex align-items-center gap-2">
                                            <i class="bi bi-clock-history text-primary"></i>
                                            <strong>Riwayat Data Per Jam</strong>
                                            <span class="badge bg-primary ms-1">{{ count($timelineRowsV3) }} entri</span>
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
                                                    @foreach($timelineRowsV3 as $tRow)
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
                                    <a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                    <a href="{{ route('pemeriksaan-suhu-ruang-v3.edit', $pemeriksaanSuhuRuangV3) }}" class="btn btn-primary me-1 mb-1">Edit</a>
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