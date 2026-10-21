@extends('layouts.app')
@section('container')
@php
    $m = $pemeriksaanProdukFinishGood;

    /* ===== Kondisi mobil (nilai: '1' = ya, '0' = tidak, lainnya = belum diisi) ===== */
    $kondisiMobil = is_array($m->kondisi_mobil) ? $m->kondisi_mobil : [];
    $kondisiMobilLabels = [
        'bersih' => 'Bersih',
        'bebas_hama' => 'Bebas dari hama',
        'tidak_kondensasi' => 'Tidak Kondensasi',
        'bebas_produk_halal' => 'Bebas dari Produk Non Halal',
        'tidak_berbau' => 'Tidak Berbau Menyimpang',
        'tidak_ada_sampah' => 'Tidak ada sampah',
        'tidak_ada_mikroba' => 'Tidak ada pertumbuhan mikroba',
        'lampu_cover_utuh' => 'Lampu dan Cover tidak pecah',
        'pallet_utuh' => 'Pallet / Alas Utuh',
        'tertutup_rapat' => 'Tertutup rapat/tidak bocor',
        'bebas_kontaminan' => 'Bebas dari Kontaminan',
    ];
    $kondisiLolos = 0; $kondisiGagal = 0;
    foreach ($kondisiMobilLabels as $k => $l) {
        $v = (string) data_get($kondisiMobil, $k);
        if ($v === '1') $kondisiLolos++;
        elseif ($v === '0') $kondisiGagal++;
    }
    $kondisiTotal = count($kondisiMobilLabels);

    /* ===== Data array (logika tidak diubah) ===== */
    $produkNamaById = $produkNamaById ?? [];
    $kategoriArr = is_array($m->kategori_code_array) ? $m->kategori_code_array : [];
    $idProdukArr = is_array($m->id_produk_array) ? $m->id_produk_array : [];
    $suhuMobilTypeArr = is_array($m->suhu_mobil_type_array) ? $m->suhu_mobil_type_array : [];
    $suhuMobilValueArr = is_array($m->suhu_mobil_value_array) ? $m->suhu_mobil_value_array : [];
    $suhuProdukTypeArr = is_array($m->suhu_produk_type_array) ? $m->suhu_produk_type_array : [];
    $suhuProdukValueArr = is_array($m->suhu_produk_value_array) ? $m->suhu_produk_value_array : [];
    $kondisiProdukArr = is_array($m->kondisi_produk_array) ? $m->kondisi_produk_array : [];
    $kondisiProdukSuhuValueArr = is_array($m->kondisi_produk_suhu_value_array) ? $m->kondisi_produk_suhu_value_array : [];
    $produsenArr = is_array($m->produsen_array) ? $m->produsen_array : [];
    $negaraArr = is_array($m->negara_produsen_array) ? $m->negara_produsen_array : [];
    $distributorArr = is_array($m->distributor_array) ? $m->distributor_array : [];
    $kodeArr = is_array($m->kode_produksi_array) ? $m->kode_produksi_array : [];
    $expireArr = is_array($m->expire_date_array) ? $m->expire_date_array : [];
    $jmlDatangArr = is_array($m->jumlah_datang_array) ? $m->jumlah_datang_array : [];
    $unitDatangArr = is_array($m->unit_datang_array) ? $m->unit_datang_array : [];
    $jmlSamplingArr = is_array($m->jumlah_sampling_array) ? $m->jumlah_sampling_array : [];
    $unitSamplingArr = is_array($m->unit_sampling_array) ? $m->unit_sampling_array : [];
    $kemasanArr = is_array($m->kondisi_kemasan_array) ? $m->kondisi_kemasan_array : [];
    $warnaArr = is_array($m->kondisi_warna_array) ? $m->kondisi_warna_array : [];
    $aromaArr = is_array($m->kondisi_aroma_array) ? $m->kondisi_aroma_array : [];
    $logoArr = is_array($m->logo_halal_array) ? $m->logo_halal_array : [];
    $dokArr = is_array($m->dokumen_halal_array) ? $m->dokumen_halal_array : [];
    $coaArr = is_array($m->coa_array) ? $m->coa_array : [];
    $statusArr = is_array($m->status_array) ? $m->status_array : [];
    $ketArr = is_array($m->keterangan_array) ? $m->keterangan_array : [];
    $imgArr = is_array($m->image_finish_good_array) ? $m->image_finish_good_array : [];
    $coaFileArr = is_array($m->upload_coa_array) ? $m->upload_coa_array : [];
    $rowCount = max(count($idProdukArr), count($kategoriArr), count($kodeArr));

    $fmtTemp = function ($v) {
        if ($v === null || $v === '') return null;
        $s = (string) $v;
        return str_contains($s, '°') ? $s : ($s . '°C');
    };
    $fmtDate = function ($v) {
        if ($v === null || $v === '') return '-';
        try { return \Carbon\Carbon::parse($v)->format('d/m/Y'); } catch (\Throwable $e) { return (string) $v; }
    };
    $normalizeToArray = function ($val) {
        if ($val === null || $val === '') return [];
        if (is_array($val)) return array_values(array_filter($val, fn ($v) => $v !== null && $v !== ''));
        if (is_string($val)) {
            $decoded = json_decode($val, true);
            if (is_array($decoded)) return array_values(array_filter($decoded, fn ($v) => $v !== null && $v !== ''));
            $raw = trim($val);
            return $raw !== '' ? array_values(array_filter(array_map('trim', explode(',', $raw)))) : [];
        }
        return [];
    };

    // Kelompokkan per produk + kategori + negara (data disimpan flat di *_array)
    $grouped = [];
    for ($i = 0; $i < $rowCount; $i++) {
        $pid = $idProdukArr[$i] ?? null;
        $kategori = $kategoriArr[$i] ?? null;
        $negara = $negaraArr[$i] ?? null;
        if ($pid === null || $pid === '') continue;

        $key = (string) $pid . '|' . (string) $kategori . '|' . (string) $negara;
        if (!isset($grouped[$key])) {
            $grouped[$key] = [
                'id_produk' => $pid, 'kategori' => $kategori, 'negara' => $negara,
                'produsen' => $produsenArr[$i] ?? null, 'distributor' => $distributorArr[$i] ?? null,
                'logo_halal' => $logoArr[$i] ?? null, 'dokumen_halal' => $dokArr[$i] ?? null, 'coa' => $coaArr[$i] ?? null,
                'items' => [],
            ];
        }
        $grouped[$key]['items'][] = [
            'i' => $i,
            'kode' => $kodeArr[$i] ?? null, 'expire' => $expireArr[$i] ?? null,
            'jumlah_datang' => $jmlDatangArr[$i] ?? null, 'unit_datang' => $unitDatangArr[$i] ?? null,
            'jumlah_sampling' => $jmlSamplingArr[$i] ?? null, 'unit_sampling' => $unitSamplingArr[$i] ?? null,
            'kemasan' => $kemasanArr[$i] ?? null, 'warna' => $warnaArr[$i] ?? null, 'aroma' => $aromaArr[$i] ?? null,
            'status' => $statusArr[$i] ?? null, 'keterangan' => $ketArr[$i] ?? null,
            'suhu_mobil_type' => $suhuMobilTypeArr[$i] ?? $m->suhu_mobil,
            'suhu_mobil_value' => $suhuMobilValueArr[$i] ?? $m->suhu_mobil_value,
            'suhu_produk_type' => $suhuProdukTypeArr[$i] ?? $m->suhu_produk,
            'suhu_produk_value' => $suhuProdukValueArr[$i] ?? $m->suhu_produk_value,
            'kondisi_produk' => $kondisiProdukArr[$i] ?? $m->kondisi_produk,
            'kondisi_produk_suhu_value' => $kondisiProdukSuhuValueArr[$i] ?? null,
        ];
    }
    $groupList = array_values($grouped);

    $show = fn ($v) => ($v !== null && $v !== '') ? e($v) : '<span class="fp-dash">-</span>';
    $statusClass = fn ($s) => match ($s) { 'Release' => 'ok', 'Hold' => 'warn', 'Retur' => 'info', default => 'mute' };
    // Nilai tri-state: '1' => ok, '0' => bad, lainnya => mute
    $tri = fn ($v) => (string) ($v ?? '') === '1' ? 'ok' : ((string) ($v ?? '') === '0' ? 'bad' : 'mute');
    $triIcon = ['ok' => 'bi-check-circle-fill', 'bad' => 'bi-x-circle-fill', 'mute' => 'bi-dash-circle'];
    $triDocIcon = ['ok' => 'bi-check', 'bad' => 'bi-x', 'mute' => 'bi-dash'];
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
                <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-produk-finish-good.index') }}">Pemeriksaan Produk Finish Good</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>

        <div class="fp-head">
            <div>
                <h3>Detail Pemeriksaan Produk Finish Good</h3>
                <p>Detail pemeriksaan dan log verifikasi mutu produk finish good.</p>
            </div>
            <div class="fp-actions">
                <a href="{{ route('pemeriksaan-produk-finish-good.index') }}" class="fp-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <!-- <button type="button" class="fp-btn" onclick="window.print()"><i class="bi bi-printer"></i> Cetak PDF</button> -->
                <a href="{{ route('pemeriksaan-produk-finish-good.edit', $m->uuid) }}" class="fp-btn fp-btn-warn"><i class="bi bi-pencil-square"></i> Edit Data</a>
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
                    <div><div class="fp-field-label">Tanggal pemeriksaan</div><div class="fp-field-value">{{ optional($m->tanggal)->format('d/m/Y') ?: '-' }}</div></div>
                    <div><div class="fp-field-label">Shift</div>
                        <div class="fp-field-value">@if($m->shift)<span class="fp-tag fp-tag-info">{{ $m->shift->shift }}</span>@else<span class="fp-dash">-</span>@endif</div></div>
                    <div><div class="fp-field-label">Segel / Gembok</div>
                        <div class="fp-field-value">
                            @if($m->segel_gembok)
                                @if($m->segel_gembok === 'segel')<span class="fp-tag fp-tag-info">Segel</span>
                                @elseif($m->segel_gembok === 'gembok')<span class="fp-tag fp-tag-warn">Gembok</span>
                                @else<span class="fp-tag fp-tag-mute">{{ ucfirst($m->segel_gembok) }}</span>@endif
                            @else<span class="fp-dash">-</span>@endif
                        </div></div>
                    <div><div class="fp-field-label">No. Segel</div>
                        <div class="fp-field-value">@if($m->segel_gembok === 'segel' && $m->no_segel){{ $m->no_segel }}@else<span class="fp-dash">-</span>@endif</div></div>

                    <div><div class="fp-field-label">Nama supir</div><div class="fp-field-value">{!! $show($m->nama_supir) !!}</div></div>
                    <div><div class="fp-field-label">Jenis mobil</div><div class="fp-field-value">{!! $show($m->jenis_mobil) !!}</div></div>
                    <div><div class="fp-field-label">No. polisi kendaraan</div>
                        <div class="fp-field-value">@if($m->no_mobil)<span class="fp-plate fp-mono">{{ $m->no_mobil }}</span>@else<span class="fp-dash">-</span>@endif</div></div>
                    <!-- <div><div class="fp-field-label">Plant</div>
                        <div class="fp-field-value">{!! $show($m->user && $m->user->plant ? $m->user->plant->plant : null) !!}</div></div> -->
                </div>
            </div>
        </div>

        {{-- Kondisi mobil --}}
        <div class="fp-panel">
            <div class="fp-panel-head">
                <h5><i class="bar green"></i> Kondisi Mobil Pengangkut</h5>
                @php $semuaLolos = $kondisiLolos === $kondisiTotal; @endphp
                <span class="fp-pill {{ $semuaLolos ? 'fp-pill-ok' : ($kondisiGagal > 0 ? 'fp-pill-bad' : 'fp-pill-mute') }}">
                    <i class="bi {{ $semuaLolos ? 'bi-check-circle-fill' : ($kondisiGagal > 0 ? 'bi-exclamation-circle-fill' : 'bi-dash-circle') }}"></i>
                    {{ $kondisiLolos }}/{{ $kondisiTotal }} parameter lolos
                </span>
            </div>
            <div class="fp-panel-body">
                <div class="fp-checks">
                    @foreach($kondisiMobilLabels as $key => $label)
                        @php $st = $tri(data_get($kondisiMobil, $key)); @endphp
                        <div class="fp-check {{ $st === 'bad' ? 'is-bad' : ($st === 'mute' ? 'is-null' : '') }}">
                            <i class="bi {{ $triDocIcon[$st] }}"></i> {{ $label }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Detail produk --}}
        <div class="fp-sec">
            <h5><i class="bar"></i> Detail Produk</h5>
            <small>Total {{ count($groupList) }} item produk finish good</small>
        </div>

        @forelse($groupList as $gIndex => $group)
            @php
                $pid = $group['id_produk'] ?? null;
                $namaProduk = $pid && isset($produkNamaById[$pid]) ? $produkNamaById[$pid] : '-';
                $kategori = $group['kategori'] ?? null;
                $negara = $group['negara'] ?? null;
                $produsen = $normalizeToArray($group['produsen'] ?? null);
                $distributor = $normalizeToArray($group['distributor'] ?? null);
                $stLogo = $tri($group['logo_halal'] ?? null);
                $stDok = $tri($group['dokumen_halal'] ?? null);
                $stCoa = $tri($group['coa'] ?? null);
                $items = is_array($group['items'] ?? null) ? $group['items'] : [];
            @endphp

            <div class="fp-panel">
                <div class="fp-item-head">
                    <div>
                        <div class="fp-item-title">
                            <span class="fp-item-no">Item #{{ $gIndex + 1 }}</span>
                            <span class="fp-tag fp-tag-cyan">{{ $namaProduk }}</span>
                            @if($kategori)<span class="fp-tag fp-tag-info">{{ $kategori }}</span>@endif
                            @if($negara)<span class="fp-tag fp-tag-mute">Negara: {{ $negara }}</span>@endif
                        </div>

                        @if(count($produsen) > 0 || count($distributor) > 0)
                            <div class="fp-party">
                                @if(count($produsen) > 0)
                                    <div><b>Produsen:</b>
                                        @if(count($produsen) > 1)<ol>@foreach($produsen as $item)<li>{{ $item }}</li>@endforeach</ol>
                                        @else <span style="color:var(--fp-ink);font-weight:600">{{ $produsen[0] }}</span>@endif
                                    </div>
                                @endif
                                @if(count($distributor) > 0)
                                    <div><b>Distributor:</b>
                                        @if(count($distributor) > 1)<ol>@foreach($distributor as $item)<li>{{ $item }}</li>@endforeach</ol>
                                        @else <span style="color:var(--fp-ink);font-weight:600">{{ $distributor[0] }}</span>@endif
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="fp-docs">
                        <span>Status dokumen:</span>
                        <span class="fp-doc {{ $stLogo === 'bad' ? 'bad' : $stLogo }}"><i class="bi {{ $triDocIcon[$stLogo] }}"></i> Logo Halal</span>
                        <span class="fp-doc {{ $stDok === 'bad' ? 'bad' : $stDok }}"><i class="bi {{ $triDocIcon[$stDok] }}"></i> Dokumen Halal</span>
                        <span class="fp-doc {{ $stCoa === 'bad' ? 'bad' : $stCoa }}"><i class="bi {{ $triDocIcon[$stCoa] }}"></i> COA</span>
                    </div>
                </div>

                <div class="fp-panel-body">
                    @foreach($items as $dIndex => $it)
                        @php
                            $status = $it['status'] ?? null;
                            $keterangan = $it['keterangan'] ?? null;
                            $imgPath = $imgArr[$it['i'] ?? 0] ?? null;
                            $coaPath = $coaFileArr[$it['i'] ?? 0] ?? null;
                            $tMobil = $fmtTemp($it['suhu_mobil_value'] ?? null);
                            $tProduk = $fmtTemp($it['suhu_produk_value'] ?? null);
                            $tKondisi = $fmtTemp($it['kondisi_produk_suhu_value'] ?? null);
                        @endphp

                        <div class="fp-detail">
                            <div class="fp-detail-head">
                                <span>Detail #{{ $dIndex + 1 }} – Spesifikasi &amp; Fisik</span>
                                @if($status)
                                    <span class="fp-tag fp-tag-{{ $statusClass($status) }}">{{ strtoupper($status) }}</span>
                                @else
                                    <span class="fp-tag fp-tag-mute">-</span>
                                @endif
                            </div>
                            <div class="fp-detail-body">
                                <div class="fp-kv">
                                    <div>
                                        <div class="fp-kv-row"><span>Kode produksi</span><span><span class="fp-code fp-mono">{{ $it['kode'] ?? '-' }}</span></span></div>
                                        <div class="fp-kv-row"><span>Expire date</span><span>{{ $fmtDate($it['expire'] ?? null) }}</span></div>
                                        <div class="fp-kv-row"><span>Jumlah datang</span><span>{{ $it['jumlah_datang'] ?? '-' }} @if(!empty($it['unit_datang'])){{ $it['unit_datang'] }}@endif</span></div>
                                        <div class="fp-kv-row"><span>Jumlah sampling</span><span>{{ $it['jumlah_sampling'] ?? '-' }} @if(!empty($it['unit_sampling'])){{ $it['unit_sampling'] }}@endif</span></div>
                                    </div>
                                    <div>
                                        <div class="fp-kv-row"><span>Suhu mobil</span>
                                            <span>@if(!empty($it['suhu_mobil_type']))<span class="fp-tag fp-tag-info">{{ $it['suhu_mobil_type'] }}</span>@if($tMobil) {{ $tMobil }}@endif @else <span class="fp-dash">-</span> @endif</span></div>
                                        <div class="fp-kv-row"><span>Suhu produk</span>
                                            <span>@if(!empty($it['suhu_produk_type']))<span class="fp-tag fp-tag-info">{{ $it['suhu_produk_type'] }}</span>@if($tProduk) {{ $tProduk }}@endif @else <span class="fp-dash">-</span> @endif</span></div>
                                        <div class="fp-kv-row"><span>Kondisi produk</span>
                                            <span>@if(!empty($it['kondisi_produk']))<span class="fp-tag fp-tag-mute">{{ $it['kondisi_produk'] }}</span>@if($tKondisi) {{ $tKondisi }}@endif @else <span class="fp-dash">-</span> @endif</span></div>
                                    </div>
                                </div>

                                <div class="fp-phys">
                                    <span>Kondisi fisik:</span>
                                    @foreach(['Kemasan' => $it['kemasan'] ?? null, 'Warna' => $it['warna'] ?? null, 'Aroma' => $it['aroma'] ?? null] as $physLabel => $physVal)
                                        @php $st = $tri($physVal); @endphp
                                        <span class="fp-pill fp-pill-{{ $st }}"><i class="bi {{ $triIcon[$st] }}"></i> {{ $physLabel }}</span>
                                    @endforeach
                                </div>

                                @if($keterangan)
                                    <div class="fp-note"><b>Keterangan</b>{{ $keterangan }}</div>
                                @endif

                                @if($imgPath || $coaPath)
                                    <div class="fp-files">
                                        @if($coaPath)<a href="{{ asset('storage/' . $coaPath) }}" target="_blank" class="fp-btn fp-btn-blue fp-btn-sm"><i class="bi bi-file-earmark-text"></i> Lihat File COA</a>@endif
                                        @if($imgPath)<button type="button" class="fp-btn fp-btn-blue fp-btn-sm" data-bs-toggle="modal" data-bs-target="#modalFotoFG{{ $gIndex }}_{{ $dIndex }}"><i class="bi bi-image"></i> Lihat Foto Produk</button>@endif
                                    </div>
                                @endif

                                @if($imgPath)
                                    <div class="modal fade" id="modalFotoFG{{ $gIndex }}_{{ $dIndex }}" tabindex="-1" aria-labelledby="modalFotoFGLabel{{ $gIndex }}_{{ $dIndex }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalFotoFGLabel{{ $gIndex }}_{{ $dIndex }}">Foto Produk - Produk {{ $gIndex + 1 }} Detail #{{ $dIndex + 1 }}</h5>
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
            <div class="fp-panel"><div class="fp-panel-body text-center text-muted">Tidak ada detail produk.</div></div>
        @endforelse

    </div>
</div>
@endsection