@extends('layouts.app')
@section('container')
@php
    $m = $pemeriksaanBahanBaku;

    $kondisiMobil = [
        'bersih' => 'Bersih', 'bebas_hama' => 'Bebas dari hama',
        'tidak_kondensasi' => 'Tidak Kondensasi', 'bebas_produk_halal' => 'Bebas dari Produk Non Halal',
        'tidak_berbau' => 'Tidak Berbau', 'tidak_ada_sampah' => 'Tidak ada sampah',
        'tidak_ada_mikroba' => 'Tidak ada mikroba', 'lampu_cover_utuh' => 'Lampu Cover utuh',
        'pallet_utuh' => 'Pallet utuh', 'tertutup_rapat' => 'Tertutup rapat',
        'bebas_kontaminan' => 'Bebas kontaminan',
    ];
    $kondisiLolos = 0;
    foreach ($kondisiMobil as $k => $l) {
        if (isset($m->kondisi_mobil[$k]) && $m->kondisi_mobil[$k]) $kondisiLolos++;
    }
    $kondisiTotal = count($kondisiMobil);

    /* ===== Data dinamis (logika tidak diubah) ===== */
    $idBahanArray = json_decode($m->id_bahan_array, true) ?? [];
    $produsenArray = json_decode($m->produsen_array, true) ?? [];
    $negaraProdusenArray = json_decode($m->negara_produsen_array, true) ?? [];
    $distributorArray = json_decode($m->distributor_array, true) ?? [];
    $kodeProduksiArray = json_decode($m->kode_produksi_array, true) ?? [];
    $expireDateArray = json_decode($m->expire_date_array, true) ?? [];
    $jumlahDatangArray = json_decode($m->jumlah_datang_array, true) ?? [];
    $jumlahSamplingArray = json_decode($m->jumlah_sampling_array, true) ?? [];
    $unitDatangArray = json_decode($m->unit_datang_array, true) ?? [];
    $unitSamplingArray = json_decode($m->unit_sampling_array, true) ?? [];
    $formatSampling = function ($values, $units) {
        $values = is_array($values) ? $values : [$values];
        $units = is_array($units) ? $units : [$units];
        $parts = [];
        foreach ($values as $i => $value) {
            if ($value === null || $value === '') continue;
            $parts[] = trim((string) $value . ' ' . ($units[$i] ?? ''));
        }
        return implode(', ', $parts);
    };
    $spesifikasiArray = json_decode($m->spesifikasi_array, true) ?? [];
    $kondisiProdukArray = json_decode($m->kondisi_produk, true) ?? [];
    $suhuProdukArray = json_decode($m->suhu_produk, true) ?? [];
    $suhuProdukTypeArray = json_decode($m->suhu_produk_type, true) ?? [];
    $suhuMobilArray = json_decode($m->suhu_mobil_array, true) ?? [];
    $suhuMobilTypeArray = json_decode($m->suhu_mobil_type_array, true) ?? [];
    $kondisiProdukSuhuArray = json_decode($m->kondisi_produk_suhu, true) ?? [];
    $kondisiFisikArray = json_decode($m->kondisi_fisik_array, true) ?? [];
    $logoHalalArray = json_decode($m->logo_halal_array, true) ?? [];
    $dokumenHalalArray = json_decode($m->dokumen_halal_array, true) ?? [];
    $coaArray = json_decode($m->coa_array, true) ?? [];
    $fileCoaArray = json_decode($m->file_coa_array ?? '[]', true) ?? [];
    $imageBahanBakuArray = json_decode($m->image_bahan_baku_array ?? '[]', true) ?? [];
    $hasilUjiFfaArray = json_decode($m->hasil_uji_ffa_array, true) ?? [];
    $statusBarisArray = json_decode($m->status_baris_array, true) ?? [];
    $keteranganArray = json_decode($m->keterangan_array, true) ?? [];

    // Jumlah baris detail = panjang array terbesar (data lama bisa lebih pendek di id_bahan_array)
    $rowCount = max(
        count($idBahanArray), count($kodeProduksiArray), count($expireDateArray), count($jumlahDatangArray),
        count($jumlahSamplingArray), count($spesifikasiArray), count($kondisiFisikArray), count($logoHalalArray),
        count($dokumenHalalArray), count($coaArray), count($fileCoaArray), count($imageBahanBakuArray),
        count($hasilUjiFfaArray), count($statusBarisArray), count($keteranganArray)
    );

    $firstBahanId = $idBahanArray[0] ?? null;
    if (is_array($firstBahanId)) $firstBahanId = $firstBahanId[0] ?? null;
    $firstBahanId = $firstBahanId === null ? '' : trim((string) $firstBahanId);
    if ($firstBahanId !== '' && ctype_digit($firstBahanId)) $firstBahanId = (string) ((int) $firstBahanId);

    // Kelompokkan per bahan (urutan kemunculan pertama dipertahankan)
    $groupedDetailIdx = [];
    $orderedBahanIds = [];
    $lastSeenBahanId = $firstBahanId;

    for ($i = 0; $i < $rowCount; $i++) {
        $rawBahanId = $idBahanArray[$i] ?? '';
        if (is_array($rawBahanId)) $rawBahanId = $rawBahanId[0] ?? null;
        $bahanId = $rawBahanId === null ? '' : trim((string) $rawBahanId);
        if ($bahanId !== '' && ctype_digit($bahanId)) $bahanId = (string) ((int) $bahanId);

        if ($bahanId === '') {
            $bahanId = $lastSeenBahanId !== '' ? trim((string) $lastSeenBahanId) : $firstBahanId;
            if ($bahanId !== '' && ctype_digit($bahanId)) $bahanId = (string) ((int) $bahanId);
        }
        if ($bahanId === '') $bahanId = '__unknown__';

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
            $bahanNamaById = \App\Models\Bahan::query()->whereIn('id', $bahanIdsForMap)->pluck('nama_bahan', 'id')->toArray();
        }
    } catch (\Throwable $e) {
        $bahanNamaById = [];
    }

    $toList = function ($val) {
        if (is_array($val)) return array_values(array_filter($val, fn ($v) => $v !== null && $v !== ''));
        $raw = trim((string) $val);
        return $raw !== '' ? array_values(array_filter(array_map('trim', explode(',', $raw)))) : [];
    };
    $show = fn ($v) => ($v !== null && $v !== '') ? e($v) : '<span class="fp-dash">-</span>';
    $statusClass = fn ($s) => match ($s) { 'Release' => 'ok', 'Hold' => 'warn', 'Retur' => 'info', default => 'mute' };
@endphp

<style>
    /* ===== Detail Kedatangan Kemasan (scoped .fp-) ===== */
    .fp { --fp-ink:#0f172a; --fp-muted:#64748b; --fp-line:#e5e9f0; --fp-soft:#f8fafc;
          --fp-blue:#2563eb; --fp-blue-soft:#eff6ff; --fp-blue-line:#bfdbfe;
          --fp-green:#16a34a; --fp-green-soft:#ecfdf3; --fp-green-line:#bbf7d0;
          --fp-red:#dc2626; --fp-red-soft:#fef2f2; --fp-red-line:#fecaca;
          --fp-amber:#f59e0b; --fp-navy:#0b1220; }
    .fp-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }

    /* Header */
    .fp-head { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .fp-head h3 { margin:0; font-weight:700; letter-spacing:-.02em; color:var(--fp-ink); }
    .fp-head p  { margin:.25rem 0 0; color:var(--fp-muted); font-size:.85rem; }
    .fp-actions { display:flex; gap:.5rem; flex-wrap:wrap; }
    .fp-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.5rem .9rem; border-radius:.5rem; font-size:.82rem; font-weight:600;
              border:1px solid var(--fp-line); background:#fff; color:var(--fp-ink); text-decoration:none; cursor:pointer; transition:background .15s; }
    .fp-btn:hover { background:var(--fp-soft); color:var(--fp-ink); }
    .fp-btn-warn { background:var(--fp-amber); border-color:var(--fp-amber); color:#fff; }
    .fp-btn-warn:hover { background:#d97706; border-color:#d97706; color:#fff; }
    .fp-btn-blue { background:var(--fp-blue-soft); border-color:var(--fp-blue-line); color:var(--fp-blue); }
    .fp-btn-blue:hover { background:#dbeafe; color:var(--fp-blue); }
    .fp-btn-sm { padding:.35rem .7rem; font-size:.76rem; }

    /* Panel */
    .fp-panel { background:#fff; border:1px solid var(--fp-line); border-radius:.9rem; margin-bottom:1.5rem; overflow:hidden; }
    .fp-panel-head { display:flex; justify-content:space-between; align-items:center; gap:.75rem; flex-wrap:wrap; padding:.9rem 1.25rem; border-bottom:1px solid var(--fp-line); }
    .fp-panel-head h5 { margin:0; font-size:.88rem; font-weight:700; letter-spacing:.02em; text-transform:uppercase; color:var(--fp-ink); display:flex; align-items:center; gap:.6rem; }
    .fp-panel-head h5 i.bar { width:.28rem; height:1rem; border-radius:.2rem; background:var(--fp-blue); display:inline-block; }
    .fp-panel-head h5 i.bar.green { background:var(--fp-green); }
    .fp-panel-body { padding:1.25rem; }
    .fp-id { font-size:.72rem; color:#94a3b8; }
    .fp-pill { display:inline-flex; align-items:center; gap:.35rem; padding:.2rem .7rem; border-radius:999px; font-size:.74rem; font-weight:600; }
    .fp-pill-ok { background:var(--fp-green-soft); color:var(--fp-green); border:1px solid var(--fp-green-line); }
    .fp-pill-bad { background:var(--fp-red-soft); color:var(--fp-red); border:1px solid var(--fp-red-line); }

    /* Field grid */
    .fp-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:1.25rem 1.5rem; }
    .fp-field-label { font-size:.7rem; font-weight:600; letter-spacing:.04em; text-transform:uppercase; color:var(--fp-muted); margin-bottom:.35rem; }
    .fp-field-value { font-weight:600; color:var(--fp-ink); }
    .fp-plate { display:inline-block; background:#f1f5f9; border:1px solid var(--fp-line); border-radius:.3rem; padding:.1rem .5rem; font-size:.8rem; }
    .fp-tag { display:inline-flex; align-items:center; gap:.3rem; font-size:.72rem; font-weight:700; padding:.2rem .65rem; border-radius:999px; }
    .fp-tag-ok   { background:var(--fp-green); color:#fff; }
    .fp-tag-warn { background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
    .fp-tag-info { background:var(--fp-blue); color:#fff; }
    .fp-tag-mute { background:#e2e8f0; color:#475569; }
    .fp-tag-cyan { background:#06b6d4; color:#fff; font-size:.78rem; }
    .fp-dash { color:#94a3b8; font-weight:400; }

    /* Kondisi mobil */
    .fp-checks { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:.7rem; }
    .fp-check { display:flex; align-items:center; gap:.7rem; padding:.7rem .9rem; border-radius:.6rem; font-size:.85rem; font-weight:500; border:1px solid var(--fp-green-line); background:var(--fp-green-soft); color:var(--fp-ink); }
    .fp-check i { width:1.35rem; height:1.35rem; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; background:var(--fp-green); color:#fff; font-size:1.15rem; line-height:1; flex:none; }
    .fp-check.is-bad { border-color:var(--fp-red-line); background:var(--fp-red-soft); }
    .fp-check.is-bad i { background:var(--fp-red); }

    /* Section title */
    .fp-sec { display:flex; justify-content:space-between; align-items:center; margin:0 0 .75rem; flex-wrap:wrap; gap:.5rem; }
    .fp-sec h5 { margin:0; font-weight:700; color:var(--fp-ink); display:flex; align-items:center; gap:.6rem; }
    .fp-sec h5 i.bar { width:.28rem; height:1.1rem; border-radius:.2rem; background:#6366f1; display:inline-block; }
    .fp-sec small { color:var(--fp-muted); }

    /* Item produk */
    .fp-item-head { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap; padding:1.1rem 1.25rem; border-bottom:1px solid var(--fp-line); }
    .fp-item-title { display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; }
    .fp-item-no { background:var(--fp-navy); color:#fff; font-size:.72rem; font-weight:700; padding:.2rem .55rem; border-radius:.3rem; }
    .fp-party { display:flex; gap:1.25rem; flex-wrap:wrap; margin-top:.6rem; font-size:.8rem; color:var(--fp-muted); }
    .fp-party b { color:var(--fp-ink); }
    .fp-party ol { margin:.2rem 0 0; padding-left:1.1rem; color:var(--fp-ink); font-weight:600; }
    .fp-docs { display:flex; align-items:center; gap:.4rem; flex-wrap:wrap; font-size:.78rem; color:var(--fp-muted); }
    .fp-doc { display:inline-flex; align-items:center; gap:.3rem; padding:.3rem .75rem; border-radius:.35rem; font-size:.74rem; font-weight:700; color:#fff; }
    .fp-doc i { font-size:1.05rem; line-height:1; }
    .fp-doc.ok { background:var(--fp-green); } .fp-doc.bad { background:var(--fp-red); }

    /* Detail card */
    .fp-detail { border:1px solid var(--fp-line); border-radius:.7rem; overflow:hidden; background:#fff; }
    .fp-detail + .fp-detail { margin-top:1rem; }
    .fp-detail-head { display:flex; justify-content:space-between; align-items:center; padding:.75rem 1.1rem; background:var(--fp-soft); border-bottom:1px solid var(--fp-line); font-size:.85rem; font-weight:700; color:var(--fp-ink); }
    .fp-detail-body { padding:1.1rem; }
    .fp-kv { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:0 2rem; }
    .fp-kv-row { display:flex; justify-content:space-between; gap:1rem; padding:.6rem 0; border-bottom:1px dashed var(--fp-line); font-size:.84rem; }
    .fp-kv-row span:first-child { color:var(--fp-muted); }
    .fp-kv-row span:last-child { font-weight:600; color:var(--fp-ink); text-align:right; }
    .fp-code { background:#f1f5f9; padding:.1rem .5rem; border-radius:.3rem; font-size:.78rem; }
    .fp-phys { display:flex; gap:.6rem; flex-wrap:wrap; margin-top:1rem; align-items:center; }
    .fp-phys > span:first-child { font-size:.78rem; color:var(--fp-muted); margin-right:.25rem; }
    .fp-phys .fp-pill { font-size:.72rem; }
    .fp-note { margin-top:1rem; padding:.7rem .9rem; border-left:3px solid var(--fp-amber); background:#fffbeb; border-radius:.4rem; font-size:.83rem; }
    .fp-note b { display:block; font-size:.7rem; text-transform:uppercase; letter-spacing:.04em; color:var(--fp-muted); margin-bottom:.2rem; }
    .fp-files { display:flex; gap:.5rem; flex-wrap:wrap; margin-top:1rem; }

    @media (max-width:1199px){ .fp-grid{ grid-template-columns:repeat(2,minmax(0,1fr)); } .fp-checks{ grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media (max-width:767px){
        .fp-grid,.fp-checks,.fp-kv{ grid-template-columns:1fr; }
        .fp-actions{ width:100%; } .fp-actions .fp-btn{ flex:1; justify-content:center; }
    }
    @media print { .fp-actions,.fp-files{ display:none !important; } }

    /* tambahan untuk Chemical / Bahan Baku / Finish Good */
    .fp-check.is-null { border-color:var(--fp-line); background:var(--fp-soft); color:var(--fp-muted); }
    .fp-check.is-null i { background:#94a3b8; }
    .fp-pill-mute { background:#f1f5f9; color:var(--fp-muted); border:1px solid var(--fp-line); }
    .fp-doc.mute { background:#94a3b8; }
    .fp-meta { display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.7rem; }
    .fp-meta-item { border:1px solid var(--fp-line); background:var(--fp-soft); border-radius:.45rem; padding:.3rem .65rem; font-size:.78rem; color:var(--fp-muted); }
    .fp-meta-item b { color:var(--fp-ink); font-weight:600; }
</style>

<div id="main">
    <div class="fp">

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-bahan-baku.index') }}">Pemeriksaan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bahan Baku Penunjang</li>
            </ol>
        </nav>

        <div class="fp-head">
            <div>
                <h3>Detail Pemeriksaan Kedatangan Bahan Baku Penunjang</h3>
                <p>Detail pemeriksaan dan log verifikasi mutu bahan baku penunjang.</p>
            </div>
            <div class="fp-actions">
                <a href="{{ route('pemeriksaan-bahan-baku.index') }}" class="fp-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <!-- <button type="button" class="fp-btn" onclick="window.print()"><i class="bi bi-printer"></i> Cetak PDF</button> -->
                <a href="{{ route('pemeriksaan-bahan-baku.edit', $m->uuid) }}" class="fp-btn fp-btn-warn"><i class="bi bi-pencil-square"></i> Edit Data</a>
            </div>
        </div>

        {{-- Informasi dasar --}}
        <div class="fp-panel">
            <div class="fp-panel-head">
                <h5><i class="bar"></i> Informasi Dasar Kendaraan &amp; Jadwal</h5>
                <!-- <span class="fp-id fp-mono">ID Inspeksi: #{{ strtoupper(substr($m->uuid, 0, 8)) }}</span> -->
            </div>
            <div class="fp-panel-body">
                <div class="fp-grid">
                    <div><div class="fp-field-label">Tanggal kedatangan</div><div class="fp-field-value">{{ $m->tanggal->format('d/m/Y') }}</div></div>
                    <div><div class="fp-field-label">No. PO</div><div class="fp-field-value">{!! $show($m->no_po) !!}</div></div>
                    <div><div class="fp-field-label">Segel / Gembok</div>
                        <div class="fp-field-value">@if($m->segel_gembok)<span class="fp-tag fp-tag-warn">{{ ucfirst($m->segel_gembok) }}</span>@else<span class="fp-dash">-</span>@endif</div></div>
                    <div><div class="fp-field-label">No. Segel</div>
                        <div class="fp-field-value">@if($m->segel_gembok === 'segel' && $m->no_segel){{ $m->no_segel }}@else<span class="fp-dash">-</span>@endif</div></div>

                    <div><div class="fp-field-label">Nama supir</div><div class="fp-field-value">{!! $show($m->nama_supir) !!}</div></div>
                    <div><div class="fp-field-label">Jenis mobil</div><div class="fp-field-value">{!! $show($m->jenis_mobil) !!}</div></div>
                    <div><div class="fp-field-label">No. polisi kendaraan</div>
                        <div class="fp-field-value">@if($m->no_mobil)<span class="fp-plate fp-mono">{{ $m->no_mobil }}</span>@else<span class="fp-dash">-</span>@endif</div></div>
                    <div><div class="fp-field-label">Shift kedatangan</div>
                        <div class="fp-field-value">@if($m->shift)<span class="fp-tag fp-tag-info">{{ $m->shift->shift }}</span>@else<span class="fp-dash">-</span>@endif</div></div>
                </div>
            </div>
        </div>

        {{-- Suhu mobil --}}
        @if($m->suhu_mobil_type || $m->suhu_mobil)
            <div class="fp-panel">
                <div class="fp-panel-head"><h5><i class="bar"></i> Informasi Suhu Mobil</h5></div>
                <div class="fp-panel-body">
                    <div class="fp-grid">
                        <div><div class="fp-field-label">Jenis suhu mobil</div>
                            <div class="fp-field-value">@if($m->suhu_mobil_type)<span class="fp-tag fp-tag-info">{{ $m->suhu_mobil_type }}</span>@else<span class="fp-dash">-</span>@endif</div></div>
                        <div><div class="fp-field-label">Nilai suhu mobil</div><div class="fp-field-value">{!! $show($m->suhu_mobil) !!}</div></div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Kondisi mobil --}}
        <div class="fp-panel">
            <div class="fp-panel-head">
                <h5><i class="bar green"></i> Kondisi Mobil Pengangkut</h5>
                @if($m->kondisi_mobil)
                    <span class="fp-pill {{ $kondisiLolos === $kondisiTotal ? 'fp-pill-ok' : 'fp-pill-bad' }}">
                        <i class="bi {{ $kondisiLolos === $kondisiTotal ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }}"></i>
                        {{ $kondisiLolos }}/{{ $kondisiTotal }} parameter lolos
                    </span>
                @endif
            </div>
            <div class="fp-panel-body">
                @if($m->kondisi_mobil)
                    <div class="fp-checks">
                        @foreach($kondisiMobil as $key => $label)
                            @php $lolos = isset($m->kondisi_mobil[$key]) && $m->kondisi_mobil[$key]; @endphp
                            <div class="fp-check {{ $lolos ? '' : 'is-bad' }}"><i class="bi {{ $lolos ? 'bi-check' : 'bi-x' }}"></i> {{ $label }}</div>
                        @endforeach
                    </div>
                @else
                    <span class="text-muted">Tidak ada data kondisi mobil.</span>
                @endif
            </div>
        </div>

        {{-- Detail produk --}}
        <div class="fp-sec">
            <h5><i class="bar"></i> Detail Produk</h5>
            <small>Total {{ count($orderedBahanIds) }} item bahan baku</small>
        </div>

        @forelse($orderedBahanIds as $bahanId)
            @php
                $produkNo = $loop->iteration;
                $detailIdxList = $groupedDetailIdx[$bahanId] ?? [];
                $firstIdx = $detailIdxList[0] ?? null;

                $prodList = $toList($firstIdx !== null ? ($produsenArray[$firstIdx] ?? null) : null);
                $distList = $toList($firstIdx !== null ? ($distributorArray[$firstIdx] ?? null) : null);
                $negText = trim($firstIdx !== null ? (string) ($negaraProdusenArray[$firstIdx] ?? '') : '');

                $kondisiProdukHeader = trim($firstIdx !== null ? (string) ($kondisiProdukArray[$firstIdx] ?? '') : '');
                $jenisSuhuProdukVal = trim($firstIdx !== null ? (string) ($suhuProdukTypeArray[$firstIdx] ?? '') : '');
                $nilaiSuhuProdukVal = trim($firstIdx !== null ? (string) ($suhuProdukArray[$firstIdx] ?? '') : '');
                $suhuKondisiProdukHeader = trim($firstIdx !== null ? (string) ($kondisiProdukSuhuArray[$firstIdx] ?? '') : '');

                $partsProduk = array_filter([$jenisSuhuProdukVal, $nilaiSuhuProdukVal], fn ($v) => $v !== '');
                $suhuProdukHeader = implode(' - ', $partsProduk);

                $jenisSuhuMobilVal = trim($firstIdx !== null ? (string) ($suhuMobilTypeArray[$firstIdx] ?? '') : '');
                $nilaiSuhuMobilVal = trim($firstIdx !== null ? (string) ($suhuMobilArray[$firstIdx] ?? '') : '');
                $suhuMobilSummary = implode(' - ', array_filter([$jenisSuhuMobilVal, $nilaiSuhuMobilVal], fn ($v) => $v !== ''));

                $halalVal = $firstIdx !== null ? ($logoHalalArray[$firstIdx] ?? null) : null;
                $dokHalalVal = $firstIdx !== null ? ($dokumenHalalArray[$firstIdx] ?? null) : null;
                $coaVal = $firstIdx !== null ? ($coaArray[$firstIdx] ?? null) : null;
            @endphp

            <div class="fp-panel">
                <div class="fp-item-head">
                    <div>
                        <div class="fp-item-title">
                            <span class="fp-item-no">Item #{{ $produkNo }}</span>
                            @if($bahanNamaById[$bahanId] ?? null)<span class="fp-tag fp-tag-cyan">{{ $bahanNamaById[$bahanId] }}</span>@endif
                            @if($negText !== '')<span class="fp-tag fp-tag-mute">Negara: {{ $negText }}</span>@endif
                        </div>

                        @if(count($prodList) > 0 || count($distList) > 0)
                            <div class="fp-party">
                                @if(count($prodList) > 0)
                                    <div><b>Produsen:</b>
                                        @if(count($prodList) > 1)<ol>@foreach($prodList as $item)<li>{{ $item }}</li>@endforeach</ol>
                                        @else <span style="color:var(--fp-ink);font-weight:600">{{ $prodList[0] }}</span>@endif
                                    </div>
                                @endif
                                @if(count($distList) > 0)
                                    <div><b>Distributor:</b>
                                        @if(count($distList) > 1)<ol>@foreach($distList as $item)<li>{{ $item }}</li>@endforeach</ol>
                                        @else <span style="color:var(--fp-ink);font-weight:600">{{ $distList[0] }}</span>@endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($suhuProdukHeader !== '' || $kondisiProdukHeader !== '' || $suhuKondisiProdukHeader !== '' || $suhuMobilSummary !== '')
                            <div class="fp-meta">
                                @if($suhuProdukHeader !== '')<span class="fp-meta-item"><b>Suhu produk:</b> {{ $suhuProdukHeader }}</span>@endif
                                @if($kondisiProdukHeader !== '')<span class="fp-meta-item"><b>Kondisi:</b> {{ $kondisiProdukHeader }}</span>@endif
                                @if($suhuKondisiProdukHeader !== '')<span class="fp-meta-item"><b>Suhu kondisi produk:</b> {{ $suhuKondisiProdukHeader }}</span>@endif
                                @if($suhuMobilSummary !== '')<span class="fp-meta-item"><b>Suhu mobil:</b> {{ $suhuMobilSummary }}</span>@endif
                            </div>
                        @endif
                    </div>

                    <div class="fp-docs">
                        <span>Status dokumen:</span>
                        <span class="fp-doc {{ $halalVal ? 'ok' : 'bad' }}"><i class="bi {{ $halalVal ? 'bi-check' : 'bi-x' }}"></i> Logo Halal</span>
                        <span class="fp-doc {{ $dokHalalVal ? 'ok' : 'bad' }}"><i class="bi {{ $dokHalalVal ? 'bi-check' : 'bi-x' }}"></i> Dokumen Halal</span>
                        <span class="fp-doc {{ $coaVal ? 'ok' : 'bad' }}"><i class="bi {{ $coaVal ? 'bi-check' : 'bi-x' }}"></i> COA</span>
                    </div>
                </div>

                <div class="fp-panel-body">
                    @foreach($detailIdxList as $detailNo => $index)
                        @php
                            $coaFilePath = $fileCoaArray[$index] ?? null;
                            $imgPath = $imageBahanBakuArray[$index] ?? null;
                            $fisik = $kondisiFisikArray[$index] ?? [];
                        @endphp

                        <div class="fp-detail">
                            <div class="fp-detail-head">
                                <span>Detail #{{ $detailNo + 1 }} – Spesifikasi &amp; Fisik</span>
                                @if(isset($statusBarisArray[$index]))
                                    <span class="fp-tag fp-tag-{{ $statusClass($statusBarisArray[$index]) }}">{{ strtoupper($statusBarisArray[$index] ?: '-') }}</span>
                                @endif
                            </div>
                            <div class="fp-detail-body">
                                <div class="fp-kv">
                                    <div>
                                        <div class="fp-kv-row"><span>Kode produksi</span><span><span class="fp-code fp-mono">{{ $kodeProduksiArray[$index] ?? '-' }}</span></span></div>
                                        <div class="fp-kv-row"><span>Expire date</span><span>@if(isset($expireDateArray[$index]) && $expireDateArray[$index]){{ \Carbon\Carbon::parse($expireDateArray[$index])->format('d/m/Y') }}@else - @endif</span></div>
                                        <div class="fp-kv-row"><span>Jumlah datang</span><span>{{ $jumlahDatangArray[$index] ?? '-' }} @if($unitDatangArray[$index] ?? null){{ $unitDatangArray[$index] }}@endif</span></div>
                                        <div class="fp-kv-row"><span>Jumlah sampling</span><span>{{ $formatSampling($jumlahSamplingArray[$index] ?? [], $unitSamplingArray[$index] ?? []) ?: '-' }}</span></div>
                                    </div>
                                    <div>
                                        <div class="fp-kv-row"><span>Hasil uji FFA</span><span>{{ $hasilUjiFfaArray[$index] ?? '-' }}</span></div>
                                    </div>
                                </div>

                                <div class="fp-phys">
                                    <span>Kondisi fisik:</span>
                                    @foreach(['Kemasan' => 'kemasan', 'Warna' => 'warna', 'Tidak Ada Benda Asing' => 'benda_asing', 'Aroma' => 'aroma'] as $physLabel => $physKey)
                                        @php $physOk = (bool) ($fisik[$physKey] ?? null); @endphp
                                        <span class="fp-pill {{ $physOk ? 'fp-pill-ok' : 'fp-pill-bad' }}"><i class="bi {{ $physOk ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i> {{ $physLabel }}</span>
                                    @endforeach
                                    @if(isset($fisik['allergen']) && $fisik['allergen'])
                                        <span class="fp-tag {{ $fisik['allergen'] === 'Allergen' ? 'fp-tag-warn' : 'fp-tag-info' }}">{{ $fisik['allergen'] }}</span>
                                    @endif
                                </div>

                                @if($spesifikasiArray[$index] ?? null)
                                    <div class="fp-note"><b>Spesifikasi</b>{{ $spesifikasiArray[$index] }}</div>
                                @endif
                                @if($keteranganArray[$index] ?? null)
                                    <div class="fp-note"><b>Keterangan</b>{{ $keteranganArray[$index] }}</div>
                                @endif

                                @if($coaFilePath || $imgPath)
                                    <div class="fp-files">
                                        @if($coaFilePath)<a href="{{ asset('storage/' . $coaFilePath) }}" target="_blank" class="fp-btn fp-btn-blue fp-btn-sm"><i class="bi bi-file-earmark-text"></i> Lihat File COA</a>@endif
                                        @if($imgPath)<button type="button" class="fp-btn fp-btn-blue fp-btn-sm" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $index }}"><i class="bi bi-image"></i> Lihat Foto Produk</button>@endif
                                    </div>
                                @endif

                                @if($imgPath)
                                    <div class="modal fade" id="modalFoto{{ $index }}" tabindex="-1" aria-labelledby="modalFotoLabel{{ $index }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalFotoLabel{{ $index }}">Foto Produk - Detail #{{ $detailNo + 1 }}</h5>
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
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="fp-panel"><div class="fp-panel-body text-center text-muted">Tidak ada data detail produk.</div></div>
        @endforelse

    </div>
</div>
@endsection
