@extends('layouts.app')
@section('container')
@php
    /* =====================================================================
     *  DATA & LOGIKA (tidak diubah, hanya dirapikan agar bisa dipakai ulang)
     * ===================================================================== */
    $sectionLabels = [
        'cold_storage'              => 'Cold Storage',
        'anteroom_loading'          => 'Anteroom Loading',
        'pre_loading'               => 'Pre Loading',
        'prestaging'                => 'Prestaging',
        'anteroom_ekspansi_further' => 'Anteroom Ekspansi Further',
        'anteroom_ekspansi_sausage' => 'Anteroom Ekspansi Sausage',
    ];

    // Standar per area (dipakai untuk badge Std / Manual)
    $sectionStd = [
        'cold_storage'     => '-18',
        'anteroom_loading' => '(0±5°C)',
    ];

    $currentSuhu = is_array($pemeriksaanSuhuRuang->suhu_data)
        ? $pemeriksaanSuhuRuang->suhu_data
        : (json_decode($pemeriksaanSuhuRuang->suhu_data ?? '[]', true) ?: []);

    // Cek apakah nilai sesuai standar
    $isStd = function ($secKey, $val) use ($sectionStd) {
        if (!isset($sectionStd[$secKey]) || $val === null || $val === '') return false;
        return $secKey === 'cold_storage'
            ? $val == $sectionStd[$secKey]
            : (strpos((string) $val, $sectionStd[$secKey]) !== false);
    };

    // Susun kartu per area untuk bagian "monitoring"
    $areaCards = [];
    foreach ($sectionLabels as $secKey => $secLabel) {
        $secData = $currentSuhu[$secKey] ?? [];
        if (empty($secData)) continue;
        $items = in_array($secKey, ['cold_storage', 'anteroom_loading']) ? (array) $secData : [$secData];
        $group = ['key' => $secKey, 'label' => $secLabel, 'items' => []];
        foreach ($items as $item) {
            if (!is_array($item)) continue;
            $group['items'][] = [
                'unit'    => $item['unit'] ?? null,
                'setting' => $item['setting'] ?? null,
                'display' => $item['display'] ?? null,
                'actual'  => $item['actual'] ?? null,
            ];
        }
        if (!empty($group['items'])) $areaCards[] = $group;
    }

    $fieldMeta = [
        'setting' => ['label' => 'Setting (°C)', 'caption' => 'Preset termostat ruang',      'icon' => 'bi-sliders'],
        'display' => ['label' => 'Display (°C)', 'caption' => 'Indikator digital panel mesin', 'icon' => 'bi-display'],
        'actual'  => ['label' => 'Actual (°C)',  'caption' => 'Pengukuran termometer fisik QC', 'icon' => 'bi-thermometer-half'],
    ];

    // ===== Susun timeline: input awal + setiap history =====
    $histories = $pemeriksaanSuhuRuang->relationLoaded('histories') && $pemeriksaanSuhuRuang->histories
        ? $pemeriksaanSuhuRuang->histories->sortBy('created_at')
        : collect();

    $timelineRows = [];
    $no = 1;

    $firstHistory = $histories->first();
    $initSuhu = $firstHistory
        ? (is_array($firstHistory->suhu_data_lama) ? $firstHistory->suhu_data_lama : (json_decode($firstHistory->suhu_data_lama ?? '[]', true) ?: []))
        : $currentSuhu;
    $initPukul = $firstHistory ? ($firstHistory->pukul_lama ?? $pemeriksaanSuhuRuang->pukul) : $pemeriksaanSuhuRuang->pukul;
    $initJam   = $initPukul ? \Carbon\Carbon::parse($initPukul)->format('H:i') : '-';
    $initEdited = $pemeriksaanSuhuRuang->created_at ? $pemeriksaanSuhuRuang->created_at->format('d/m/Y H:i') : '-';

    foreach ($sectionLabels as $secKey => $secLabel) {
        $secData = $initSuhu[$secKey] ?? [];
        if (empty($secData)) continue;

        if (in_array($secKey, ['cold_storage', 'anteroom_loading'])) {
            foreach ((array) $secData as $item) {
                if (!is_array($item) || (empty($item['setting']) && empty($item['display']) && empty($item['actual']))) continue;
                $timelineRows[] = [
                    'no' => $no++, 'waktu' => $initJam, 'edited' => $initEdited,
                    'area' => $secLabel . ' ' . ($item['unit'] ?? ''), 'note' => null,
                    'setting' => $item['setting'] ?? '-', 'aktual' => $item['actual'] ?? '-', 'display' => $item['display'] ?? '-',
                    'tipe' => 'awal', 'dot' => 'primary',
                ];
            }
        } else {
            if (empty($secData['setting']) && empty($secData['display']) && empty($secData['actual'])) continue;
            $timelineRows[] = [
                'no' => $no++, 'waktu' => $initJam, 'edited' => $initEdited,
                'area' => $secLabel, 'note' => null,
                'setting' => $secData['setting'] ?? '-', 'aktual' => $secData['actual'] ?? '-', 'display' => $secData['display'] ?? '-',
                'tipe' => 'awal', 'dot' => 'primary',
            ];
        }
    }

    $initSuhuProduk = $firstHistory ? ($firstHistory->suhu_produk_lama ?? $pemeriksaanSuhuRuang->suhu_produk) : $pemeriksaanSuhuRuang->suhu_produk;
    if (!empty($initSuhuProduk)) {
        $timelineRows[] = [
            'no' => $no++, 'waktu' => $initJam, 'edited' => $initEdited,
            'area' => 'Suhu Produk', 'note' => 'Direct Core Check',
            'setting' => '-', 'aktual' => $initSuhuProduk, 'display' => '-',
            'tipe' => 'awal', 'dot' => 'warning',
        ];
    }

    $displayedKeys = []; // cegah duplikasi jam + area

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
                    if (empty($bItem['setting']) && empty($bItem['display']) && empty($bItem['actual'])) continue;

                    $uniqueKey = $jam . '_' . $secLabel . '_' . $unitId;
                    if (isset($displayedKeys[$uniqueKey])) continue;
                    $displayedKeys[$uniqueKey] = true;

                    $timelineRows[] = [
                        'no' => $no++, 'waktu' => $jam, 'edited' => $editedAt,
                        'area' => $secLabel . ' ' . $unitId, 'note' => null,
                        'setting' => $bItem['setting'] ?? '-', 'aktual' => $bItem['actual'] ?? '-', 'display' => $bItem['display'] ?? '-',
                        'tipe' => 'update', 'dot' => 'primary',
                    ];
                }
            } else {
                if (empty($baruData['setting']) && empty($baruData['display']) && empty($baruData['actual'])) continue;

                $uniqueKey = $jam . '_' . $secLabel;
                if (isset($displayedKeys[$uniqueKey])) continue;
                $displayedKeys[$uniqueKey] = true;

                $timelineRows[] = [
                    'no' => $no++, 'waktu' => $jam, 'edited' => $editedAt,
                    'area' => $secLabel, 'note' => null,
                    'setting' => $baruData['setting'] ?? '-', 'aktual' => $baruData['actual'] ?? '-', 'display' => $baruData['display'] ?? '-',
                    'tipe' => 'update', 'dot' => 'primary',
                ];
            }
        }

        if (($h->suhu_produk_lama ?? null) !== ($h->suhu_produk_baru ?? null) && !empty($h->suhu_produk_baru)) {
            $uniqueKey = $jam . '_Suhu Produk';
            if (!isset($displayedKeys[$uniqueKey])) {
                $displayedKeys[$uniqueKey] = true;
                $timelineRows[] = [
                    'no' => $no++, 'waktu' => $jam, 'edited' => $editedAt,
                    'area' => 'Suhu Produk', 'note' => 'Direct Core Check',
                    'setting' => '-', 'aktual' => $h->suhu_produk_baru, 'display' => '-',
                    'tipe' => 'update', 'dot' => 'warning',
                ];
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

        {{-- ===== Breadcrumb + info update ===== --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang.index') }}">Pemeriksaan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Pemeriksaan</li>
                </ol>
            </nav>
            <!-- @if($pemeriksaanSuhuRuang->updated_at)
                <span class="fp-updated"><i class="bi bi-clock"></i> Terakhir diperbarui: <b>{{ $pemeriksaanSuhuRuang->updated_at->format('d M Y, H:i') }} WIB</b></span>
            @endif -->
        </div>

        {{-- ===== Page header ===== --}}
        <div class="fp-head">
            <div>
                <h3>Food Processing</h3>
                <p>Detail log dan parameter monitoring suhu ruangan serta produk pangan basah.</p>
            </div>
            <div class="fp-actions">
                <!-- <button type="button" class="fp-btn" onclick="window.print()"><i class="bi bi-printer"></i> Cetak Form</button> -->
                <!-- <a href="{{ route('pemeriksaan-suhu-ruang.edit', $pemeriksaanSuhuRuang->uuid) }}" class="fp-btn fp-btn-primary"><i class="bi bi-pencil-square"></i> Edit Data</a> -->
            </div>
        </div>

        {{-- ===== Ringkasan ===== --}}
        <div class="fp-panel">
            <div class="fp-panel-title">
                <span><span class="dot"></span>Detail Food Processing</span>
                <!-- <span class="fp-id fp-mono">ID: {{ strtoupper(substr($pemeriksaanSuhuRuang->uuid, 0, 8)) }}</span> -->
            </div>

            <div class="fp-tiles">
                <div class="fp-tile">
                    <span class="fp-tile-label">Tanggal inspeksi</span>
                    <span class="fp-tile-value fp-mono"><i class="bi bi-calendar-event text-primary"></i> {{ $pemeriksaanSuhuRuang->tanggal->format('d-m-Y') }}</span>
                </div>

                <div class="fp-tile">
                    <span class="fp-tile-label">Shift kerja</span>
                    <span class="fp-tile-value"><span class="badge bg-info"><i class="bi bi-people-fill"></i> {{ $pemeriksaanSuhuRuang->shift->shift ?? '-' }}</span></span>
                </div>

                <div class="fp-tile">
                    <span class="fp-tile-label">Pukul pencatatan</span>
                    <span class="fp-tile-value fp-mono"><i class="bi bi-clock text-warning"></i>
                        {{ $pemeriksaanSuhuRuang->pukul ? \Carbon\Carbon::parse($pemeriksaanSuhuRuang->pukul)->format('H:i') : '-' }}
                        <small class="text-muted">WIB</small>
                    </span>
                </div>

                <div class="fp-tile fp-tile-hot">
                    <span class="fp-tile-label">Suhu produk</span>
                    <span class="fp-tile-value">
                        <span class="fp-ico"><i class="bi bi-snow2"></i></span>
                        <span class="fp-temp">{{ $pemeriksaanSuhuRuang->suhu_produk ?? '-' }}<small> °C</small></span>
                    </span>
                </div>

                <div class="fp-tile">
                    <span class="fp-tile-label">Komoditas / produk</span>
                    <div>
                        @if($pemeriksaanSuhuRuang->produk)
                            @if($pemeriksaanSuhuRuang->produk->kategori_code)
                                <span class="fp-code">{{ $pemeriksaanSuhuRuang->produk->kategori_code }}</span>
                            @endif
                            <div class="fp-prod-name" title="{{ $pemeriksaanSuhuRuang->produk->nama_produk }}">{{ $pemeriksaanSuhuRuang->produk->nama_produk ?? '-' }}</div>
                        @else
                            <div class="fp-prod-name">-</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Monitoring per area ===== --}}
        <!-- @foreach($areaCards as $group)
            <div class="mb-4">
                <div class="fp-sec">
                    <h5>
                        {{ $group['label'] }} Monitoring
                        @if(count($group['items']) === 1 && !empty($group['items'][0]['unit']))
                            <span class="fp-chip">{{ $group['items'][0]['unit'] }}</span>
                        @endif
                    </h5>
                </div>

                <div class="fp-panel mb-0">
                    @foreach($group['items'] as $item)
                        <div class="fp-unit-group">
                            @if(count($group['items']) > 1 && !empty($item['unit']))
                                <p class="fp-unit"><span class="fp-chip">{{ $group['key'] === 'cold_storage' ? 'CS' : '' }}{{ $item['unit'] }}</span></p>
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
        @endforeach -->

        {{-- ===== Catatan ===== --}}
        @if($pemeriksaanSuhuRuang->keterangan || $pemeriksaanSuhuRuang->tindakan_koreksi)
            <div class="mb-4">
                <div class="fp-sec"><h5><i class="bi bi-journal-text text-primary"></i> Catatan</h5></div>
                <div class="fp-notes">
                    @if($pemeriksaanSuhuRuang->keterangan)
                        <div class="fp-note"><b>Keterangan</b>{{ $pemeriksaanSuhuRuang->keterangan }}</div>
                    @endif
                    @if($pemeriksaanSuhuRuang->tindakan_koreksi)
                        <div class="fp-note"><b>Tindakan Koreksi</b>{{ $pemeriksaanSuhuRuang->tindakan_koreksi }}</div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ===== Riwayat data per jam ===== --}}
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
                                                <i class="d d-{{ $tRow['dot'] }}"></i>{{ $tRow['area'] }}
                                                @if($tRow['note'])<em class="fp-mono">({{ $tRow['note'] }})</em>@endif
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

        {{-- ===== Footer actions ===== --}}
        <div class="fp-footer">
            <small><i class="bi bi-info-circle-fill text-primary"></i> Perubahan terakhir tercatat pada riwayat data di atas.</small>
            <div class="fp-actions">
                <a href="{{ route('pemeriksaan-suhu-ruang.index') }}" class="fp-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <a href="{{ route('pemeriksaan-suhu-ruang.edit', $pemeriksaanSuhuRuang->uuid) }}" class="fp-btn fp-btn-primary"><i class="bi bi-pencil-square"></i> Edit Data</a>
            </div>
        </div>

    </div>
</div>
@endsection