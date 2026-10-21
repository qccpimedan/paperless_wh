@extends('layouts.app')
@section('container')
@php
    $m = $pemeriksaanSuhuRuangV3;

    $suhuSectionsV3 = [
        'premix'          => ['label' => 'Suhu Premix',           'field' => 'suhu_premix'],
        'seasoning'       => ['label' => 'Suhu Seasoning',        'field' => 'suhu_seasoning'],
        'dry'             => ['label' => 'Suhu Dry',              'field' => 'suhu_dry'],
        'cassing'         => ['label' => 'Suhu Cassing',          'field' => 'suhu_cassing'],
        'beef'            => ['label' => 'Suhu Beef',             'field' => 'suhu_beef'],
        'packaging'       => ['label' => 'Suhu Packaging',        'field' => 'suhu_packaging'],
        'ruang_chemical'  => ['label' => 'Suhu Ruang Chemical',   'field' => 'suhu_ruang_chemical'],
        'ruang_seasoning' => ['label' => 'Suhu Ruang Seasoning',  'field' => 'suhu_ruang_seasoning'],
    ];

    /* ===== Kartu monitoring ===== */
    $areaCards = [];
    foreach ($suhuSectionsV3 as $secKey => $conf) {
        $data = $m->{$conf['field']};
        if (empty($data)) continue;

        $group = ['key' => $secKey, 'label' => $conf['label'], 'items' => []];
        foreach ($data as $unit => $item) {
            $group['items'][] = [
                'chip'    => 'Unit ' . str_replace('unit_', '', (string) $unit),
                'setting' => $item['setting'] ?? null,
                'display' => $item['display'] ?? null,
                'actual'  => $item['actual'] ?? null,
            ];
        }
        $areaCards[] = $group;
    }

    /* ===== Timeline riwayat ===== */
    $historiesV3 = $m->histories->sortBy('id');
    $firstHistory = $historiesV3->first();
    $timelineRows = [];
    $noCounterV3 = 1;

    $initWaktuV3 = $firstHistory && $firstHistory->pukul_lama ? $firstHistory->pukul_lama : ($m->pukul ?? '-');

    foreach ($suhuSectionsV3 as $secKey => $secConf) {
        $colLama = 'suhu_' . $secKey . '_lama';
        $fieldVal = ($firstHistory && $firstHistory->{$colLama} !== null)
            ? (is_array($firstHistory->{$colLama}) ? $firstHistory->{$colLama} : (json_decode($firstHistory->{$colLama}, true) ?: []))
            : $m->{$secConf['field']};

        if (!empty($fieldVal) && is_array($fieldVal)) {
            foreach ($fieldVal as $unitKey => $itemData) {
                if (!is_array($itemData)) continue;
                $unitNum = str_replace('unit_', '', (string) $unitKey);
                $timelineRows[] = ['no' => $noCounterV3++, 'waktu' => $initWaktuV3, 'area' => $secConf['label'] . ' ' . $unitNum,
                    'setting' => $itemData['setting'] ?? '-', 'aktual' => $itemData['actual'] ?? '-', 'display' => $itemData['display'] ?? '-', 'tipe' => 'awal'];
            }
        }
    }

    $displayedKeysV3 = []; // cegah duplikasi jam + area + unit
    foreach ($historiesV3 as $hItem) {
        $waktuRow = $hItem->pukul_baru ?? $hItem->pukul_lama ?? $hItem->created_at->format('H:i');

        foreach ($suhuSectionsV3 as $secKey => $secConf) {
            $valBaru = $hItem->{'suhu_' . $secKey . '_baru'};
            if (empty($valBaru)) continue;

            $arrayBaru = is_array($valBaru) ? $valBaru : (json_decode($valBaru ?? '[]', true) ?: []);
            if (!is_array($arrayBaru)) continue;

            foreach ($arrayBaru as $unitKey => $itemData) {
                if (!is_array($itemData)) continue;
                $unitNum = str_replace('unit_', '', (string) $unitKey);

                $uniqueKeyV3 = $waktuRow . '_' . $secConf['label'] . '_' . $unitNum;
                if (isset($displayedKeysV3[$uniqueKeyV3])) continue;
                $displayedKeysV3[$uniqueKeyV3] = true;

                $timelineRows[] = ['no' => $noCounterV3++, 'waktu' => $waktuRow, 'area' => $secConf['label'] . ' ' . $unitNum,
                    'setting' => $itemData['setting'] ?? '-', 'aktual' => $itemData['actual'] ?? '-', 'display' => $itemData['display'] ?? '-', 'tipe' => 'update'];
            }
        }
    }
@endphp

<style>
    /* ===== Food Processing – detail page (scoped dengan prefix .fp-) ===== */
    .fp { --fp-ink:#0f172a; --fp-muted:#64748b; --fp-line:#e5e9f0; --fp-soft:#f8fafc;
          --fp-blue:#2563eb; --fp-blue-soft:#eff6ff; --fp-blue-line:#bfdbfe;
          --fp-green:#16a34a; --fp-green-soft:#ecfdf3; --fp-amber:#f59e0b; --fp-navy:#0b1220; }
    .fp-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }

    /* Header */
    .fp-head { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .fp-head h3 { margin:0; font-weight:700; letter-spacing:-.02em; color:var(--fp-ink); display:flex; align-items:center; gap:.75rem; flex-wrap:wrap; }
    .fp-head p  { margin:.25rem 0 0; color:var(--fp-muted); font-size:.85rem; }
    .fp-actions { display:flex; gap:.5rem; }
    .fp-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.5rem .9rem; border-radius:.5rem; font-size:.82rem; font-weight:600;
              border:1px solid var(--fp-line); background:#fff; color:var(--fp-ink); text-decoration:none; cursor:pointer; transition:background .15s, border-color .15s; }
    .fp-btn:hover { background:var(--fp-soft); color:var(--fp-ink); }
    .fp-btn-primary { background:var(--fp-blue); border-color:var(--fp-blue); color:#fff; }
    .fp-btn-primary:hover { background:#1d4ed8; border-color:#1d4ed8; color:#fff; }
    .fp-pill { display:inline-flex; align-items:center; gap:.35rem; padding:.2rem .6rem; border-radius:999px; font-size:.72rem; font-weight:600; }
    .fp-pill-green { background:var(--fp-green-soft); color:var(--fp-green); border:1px solid #bbf7d0; }
    .fp-updated { display:inline-flex; align-items:center; gap:.4rem; padding:.35rem .75rem; border:1px solid var(--fp-line); border-radius:.5rem; background:#fff; font-size:.78rem; color:var(--fp-muted); }
    .fp-updated b { color:var(--fp-ink); }

    /* Panel */
    .fp-panel { background:#fff; border:1px solid var(--fp-line); border-radius:.9rem; padding:1.25rem; margin-bottom:1.5rem; }
    .fp-panel-title { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; font-weight:600; color:var(--fp-ink); }
    .fp-panel-title .dot { width:.55rem; height:.55rem; border-radius:50%; background:var(--fp-blue); display:inline-block; margin-right:.6rem; }
    .fp-id { font-size:.7rem; color:#94a3b8; }

    /* Info tiles */
    .fp-tiles { display:grid; grid-template-columns:repeat(5,minmax(0,1fr)); gap:.9rem; }
    .fp-tile { border:1px solid var(--fp-line); border-radius:.7rem; padding:.9rem 1rem; background:var(--fp-soft); display:flex; flex-direction:column; justify-content:space-between; min-height:6.5rem; }
    .fp-tile-label { font-size:.7rem; font-weight:600; letter-spacing:.04em; color:var(--fp-muted); text-transform:uppercase; }
    .fp-tile-value { font-weight:600; color:var(--fp-ink); display:flex; align-items:center; gap:.5rem; }
    .fp-tile-hot { background:var(--fp-blue-soft); border-color:var(--fp-blue-line); }
    .fp-tile-hot .fp-temp { font-size:1.5rem; font-weight:700; color:var(--fp-blue); line-height:1; }
    .fp-tile-hot .fp-temp small { font-size:.85rem; font-weight:500; }
    .fp-ico { width:1.9rem; height:1.9rem; border-radius:.5rem; background:var(--fp-blue); color:#fff; display:inline-flex; align-items:center; justify-content:center; }
    .fp-code { display:inline-block; background:var(--fp-navy); color:#fff; font-size:.7rem; font-weight:700; padding:.1rem .45rem; border-radius:.3rem; margin:.25rem 0; }
    .fp-prod-name { font-size:.82rem; font-weight:600; color:var(--fp-ink); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }

    /* Section heading */
    .fp-sec { display:flex; justify-content:space-between; align-items:center; margin:0 0 .75rem; flex-wrap:wrap; gap:.5rem; }
    .fp-sec h5 { margin:0; font-weight:700; color:var(--fp-ink); display:flex; align-items:center; gap:.6rem; }
    .fp-chip { background:#e2e8f0; color:#334155; font-size:.7rem; font-weight:700; padding:.15rem .55rem; border-radius:999px; }
    .fp-sec small { color:var(--fp-muted); }

    /* Reading cards */
    .fp-reads { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:.9rem; }
    .fp-read { border:1px solid var(--fp-line); border-radius:.8rem; padding:1rem 1.1rem; background:#fff; position:relative; }
    .fp-read.is-actual { border-color:var(--fp-blue-line); background:linear-gradient(180deg,#fff,var(--fp-blue-soft)); }
    .fp-read-label { font-size:.75rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:var(--fp-ink); }
    .fp-read-cap { font-size:.74rem; color:#94a3b8; margin-top:.15rem; }
    .fp-read-val { font-size:2rem; font-weight:700; color:var(--fp-ink); line-height:1; margin:.9rem 0; display:flex; align-items:baseline; gap:.5rem; }
    .fp-read.is-actual .fp-read-val { color:var(--fp-blue); }
    .fp-read-val small { font-size:.85rem; font-weight:500; color:var(--fp-muted); }
    .fp-read-foot { border-top:1px solid var(--fp-line); padding-top:.6rem; font-size:.78rem; color:var(--fp-muted); display:flex; align-items:center; gap:.4rem; }
    .fp-tag { position:absolute; top:.75rem; right:.75rem; font-size:.65rem; font-weight:700; padding:.15rem .45rem; border-radius:.3rem; color:#fff; display:inline-flex; align-items:center; gap:.25rem; }
    .fp-tag-std { background:#0ea5e9; }
    .fp-tag-manual { background:var(--fp-amber); }
    .fp-unit { margin:0 0 .5rem; font-size:.8rem; font-weight:600; color:var(--fp-muted); }
    .fp-unit-group + .fp-unit-group { margin-top:1.1rem; }

    /* Catatan */
    .fp-notes { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:.9rem; }
    .fp-note { border:1px solid var(--fp-line); border-left:3px solid var(--fp-amber); border-radius:.6rem; padding:.8rem 1rem; background:#fffbeb; }
    .fp-note b { display:block; font-size:.72rem; text-transform:uppercase; letter-spacing:.04em; color:var(--fp-muted); margin-bottom:.25rem; }

    /* Timeline table */
    .fp-table-wrap { border-radius:.8rem; overflow:hidden; border:1px solid var(--fp-line); background:#fff; }
    .fp-table { width:100%; border-collapse:collapse; font-size:.85rem; }
    .fp-table thead th { background:var(--fp-navy); color:#fff; font-size:.7rem; letter-spacing:.05em; text-transform:uppercase; padding:.85rem 1rem; font-weight:700; }
    .fp-table tbody td { padding:.8rem 1rem; border-top:1px solid var(--fp-line); vertical-align:middle; }
    .fp-table tbody tr:hover { background:var(--fp-soft); }
    .fp-table tbody tr.is-update { background:#fffbeb; }
    .fp-table .c { text-align:center; }
    .fp-time { background:#f1f5f9; padding:.2rem .5rem; border-radius:.3rem; font-size:.8rem; }
    .fp-area { display:flex; align-items:center; gap:.55rem; font-weight:600; color:var(--fp-ink); }
    .fp-area i.d { width:.5rem; height:.5rem; border-radius:50%; display:inline-block; }
    .fp-area i.d-primary { background:var(--fp-blue); } .fp-area i.d-warning { background:var(--fp-amber); }
    .fp-area em { font-style:normal; font-weight:400; font-size:.7rem; color:#94a3b8; }
    .fp-actual { background:var(--fp-blue-soft); color:var(--fp-blue); font-weight:600; }
    .fp-status { display:inline-flex; align-items:center; gap:.3rem; font-size:.72rem; font-weight:700; padding:.25rem .6rem; border-radius:.35rem; color:#fff; }
    .fp-status-awal { background:var(--fp-green); } .fp-status-update { background:var(--fp-amber); color:#422006; }
    .fp-table-foot { display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap; padding:.7rem 1rem; background:var(--fp-soft); border-top:1px solid var(--fp-line); font-size:.74rem; color:var(--fp-muted); }

    /* Footer bar */
    .fp-footer { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; background:#fff; border:1px solid var(--fp-line); border-radius:.9rem; padding:.9rem 1.25rem; }
    .fp-footer small { color:var(--fp-muted); }

    @media (max-width:1199px){ .fp-tiles{ grid-template-columns:repeat(3,minmax(0,1fr)); } }
    @media (max-width:767px){
        .fp-tiles,.fp-reads,.fp-notes{ grid-template-columns:1fr; }
        .fp-actions{ width:100%; } .fp-actions .fp-btn{ flex:1; justify-content:center; }
    }
    @media print { .fp-actions,.fp-footer,.fp-updated{ display:none !important; } }
</style>


<div id="main">
    <div class="fp">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}">Pemeriksaan V3</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Pemeriksaan</li>
                </ol>
            </nav>
            <!-- @if($m->updated_at)
                <span class="fp-updated"><i class="bi bi-clock"></i> Terakhir diperbarui: <b>{{ $m->updated_at->format('d M Y, H:i') }} WIB</b></span>
            @endif -->
        </div>

        <div class="fp-head">
            <div>
                <h3>Pemeriksaan Suhu Ruang V3</h3>
                <p>Detail log dan parameter monitoring suhu ruang penyimpanan bahan.</p>
            </div>
            <div class="fp-actions">
                <!-- <button type="button" class="fp-btn" onclick="window.print()"><i class="bi bi-printer"></i> Cetak Form</button> -->
                <!-- <a href="{{ route('pemeriksaan-suhu-ruang-v3.edit', $m) }}" class="fp-btn fp-btn-primary"><i class="bi bi-pencil-square"></i> Edit Data</a> -->
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="fp-panel">
            <div class="fp-panel-title">
                <span><span class="dot"></span>Detail Pemeriksaan Suhu Ruang V3</span>
                <!-- <span class="fp-id fp-mono">ID: #{{ $m->getKey() }}</span> -->
            </div>
            <div class="fp-tiles">
                <div class="fp-tile">
                    <span class="fp-tile-label">Tanggal inspeksi</span>
                    <span class="fp-tile-value fp-mono"><i class="bi bi-calendar-event text-primary"></i> {{ $m->tanggal->format('d-m-Y') }}</span>
                </div>
                <div class="fp-tile">
                    <span class="fp-tile-label">Shift kerja</span>
                    <span class="fp-tile-value"><span class="badge bg-info"><i class="bi bi-people-fill"></i> {{ $m->shift->shift ?? '-' }}</span></span>
                </div>
                <div class="fp-tile">
                    <span class="fp-tile-label">Pukul pencatatan</span>
                    <span class="fp-tile-value fp-mono"><i class="bi bi-clock text-warning"></i> {{ $m->pukul }} <small class="text-muted">WIB</small></span>
                </div>
                <!-- <div class="fp-tile">
                    <span class="fp-tile-label">Petugas</span>
                    <span class="fp-tile-value"><i class="bi bi-person-circle text-primary"></i> <span class="fp-prod-name">{{ $m->user->name ?? '-' }}</span></span>
                </div> -->
                <div class="fp-tile">
                    <span class="fp-tile-label">Plant</span>
                    <span class="fp-tile-value"><span class="badge bg-info"><i class="bi bi-building"></i> {{ $m->user->plant->plant ?? '-' }}</span></span>
                </div>
            </div>
        </div>

        {{--
    Variabel yang dibutuhkan:
      $areaCards  : [ ['key'=>..,'label'=>..,'items'=>[ ['unit'=>..,'chip'=>..,'setting'=>..,'display'=>..,'actual'=>..], ... ]], ... ]
      $sectionStd : (opsional) ['cold_storage' => '-18', 'anteroom_loading' => '(0±5°C)']
--}}
@php
    $sectionStd = $sectionStd ?? [];
    $fieldMeta = [
        'setting' => ['label' => 'Setting (°C)', 'caption' => 'Preset termostat ruang',        'icon' => 'bi-sliders'],
        'display' => ['label' => 'Display (°C)', 'caption' => 'Indikator digital panel mesin', 'icon' => 'bi-display'],
        'actual'  => ['label' => 'Actual (°C)',  'caption' => 'Pengukuran termometer fisik QC', 'icon' => 'bi-thermometer-half'],
    ];
    $isStd = function ($secKey, $val) use ($sectionStd) {
        if (!isset($sectionStd[$secKey]) || $val === null || $val === '') return false;
        return $secKey === 'cold_storage'
            ? $val == $sectionStd[$secKey]
            : (strpos((string) $val, $sectionStd[$secKey]) !== false);
    };
@endphp

@foreach($areaCards as $group)
    <div class="mb-4">
        <div class="fp-sec">
            <h5>
                {{ $group['label'] }} Monitoring
                @if(count($group['items']) === 1 && !empty($group['items'][0]['chip']))
                    <span class="fp-chip">{{ $group['items'][0]['chip'] }}</span>
                @endif
            </h5>
        </div>

        <div class="fp-panel mb-0">
            @foreach($group['items'] as $item)
                <div class="fp-unit-group">
                    @if(count($group['items']) > 1 && !empty($item['chip']))
                        <p class="fp-unit"><span class="fp-chip">{{ $item['chip'] }}</span></p>
                    @endif

                    <div class="fp-reads">
                        @foreach($fieldMeta as $field => $meta)
                            @php $val = $item[$field] ?? null; @endphp
                            <div class="fp-read {{ $field === 'actual' ? 'is-actual' : '' }}">
                                @if($val !== null && $val !== '')
                                    @if($isStd($group['key'], $val))
                                        <span class="fp-tag fp-tag-std"><i class="bi bi-check2-circle"></i> Std</span>
                                    @elseif(isset($sectionStd[$group['key']]))
                                        <span class="fp-tag fp-tag-manual"><i class="bi bi-hand-index-thumb"></i> Manual</span>
                                    @endif
                                @endif

                                <div class="fp-read-label">{{ $meta['label'] }}</div>
                                <div class="fp-read-cap">{{ $meta['caption'] }}</div>
                                <div class="fp-read-val">{{ $val !== null && $val !== '' ? $val : '-' }} <small>°Celsius</small></div>
                                <div class="fp-read-foot"><i class="bi {{ $meta['icon'] }}"></i> {{ $meta['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach


        @if($m->keterangan || $m->tindakan_koreksi)
            <div class="mb-4">
                <div class="fp-sec"><h5><i class="bi bi-journal-text text-primary"></i> Catatan</h5></div>
                <div class="fp-notes">
                    @if($m->keterangan)<div class="fp-note"><b>Keterangan</b>{{ $m->keterangan }}</div>@endif
                    @if($m->tindakan_koreksi)<div class="fp-note"><b>Tindakan Koreksi</b>{{ $m->tindakan_koreksi }}</div>@endif
                </div>
            </div>
        @endif

        {{-- Variabel: $timelineRows (array baris riwayat) --}}
@if(!empty($timelineRows))
    <div class="mb-4">
        <div class="fp-sec">
            <h5>
                <i class="bi bi-clock-history text-primary"></i> Riwayat Data Per Jam
                <span class="fp-chip" style="background:var(--fp-blue-soft);color:var(--fp-blue)">{{ count($timelineRows) }} entri</span>
            </h5>
            <small>Pencatatan interval 60 menit</small>
        </div>

        <div class="fp-table-wrap">
            <div class="table-responsive">
                <table class="fp-table">
                    <thead>
                        <tr>
                            <th class="c" style="width:5%">No</th>
                            <th class="c" style="width:9%">Pukul</th>
                            <th style="text-align:left">Area / Titik Ukur</th>
                            <th class="c">Setting (°C)</th>
                            <th class="c">Actual (°C)</th>
                            <th class="c">Display (°C)</th>
                            <th class="c" style="width:11%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timelineRows as $tRow)
                            <tr class="{{ $tRow['tipe'] === 'update' ? 'is-update' : '' }}">
                                <td class="c text-muted">{{ $tRow['no'] }}</td>
                                <td class="c"><span class="fp-time fp-mono">{{ $tRow['waktu'] }}</span></td>
                                <td>
                                    <span class="fp-area">
                                        <i class="d d-{{ $tRow['dot'] ?? 'primary' }}"></i>{{ $tRow['area'] }}
                                        @if(!empty($tRow['note']))<em class="fp-mono">({{ $tRow['note'] }})</em>@endif
                                    </span>
                                </td>
                                <td class="c">{{ $tRow['setting'] }}</td>
                                <td class="c fp-actual">{{ $tRow['aktual'] }}</td>
                                <td class="c">{{ $tRow['display'] }}</td>
                                <td class="c">
                                    @if($tRow['tipe'] === 'awal')
                                        <span class="fp-status fp-status-awal"><i class="bi bi-check-circle-fill"></i> Input Awal</span>
                                    @else
                                        <span class="fp-status fp-status-update"><i class="bi bi-arrow-repeat"></i> Update</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="fp-table-foot">
                <span>Menampilkan {{ count($timelineRows) }} dari {{ count($timelineRows) }} riwayat entri data sesi ini</span>
                <!-- <span><i class="bi bi-shield-check text-success"></i> ISO 22000 &amp; HACCP Compliant Cold Chain Record</span> -->
            </div>
        </div>
    </div>
@endif


        <div class="fp-footer">
            <small><i class="bi bi-info-circle-fill text-primary"></i> Perubahan terakhir tercatat pada riwayat data di atas.</small>
            <div class="fp-actions">
                <a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}" class="fp-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <a href="{{ route('pemeriksaan-suhu-ruang-v3.edit', $m) }}" class="fp-btn fp-btn-primary"><i class="bi bi-pencil-square"></i> Edit Data</a>
            </div>
        </div>
    </div>
</div>
@endsection