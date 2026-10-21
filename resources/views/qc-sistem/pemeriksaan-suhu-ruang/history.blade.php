@extends('layouts.app')
@section('container')

@php
    /*
     * Helper: ubah suhu_data_lama / suhu_data_baru menjadi baris pivot
     * (1 baris per lokasi, kolom Setting/Display/Actual sebelum & sesudah).
     */
    $normalize = function ($v) {
        if (is_string($v) && $v !== '') {
            $decoded = json_decode($v, true);
            return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : $v;
        }
        return is_object($v) ? json_decode(json_encode($v), true) : $v;
    };

    $paramList = ['setting', 'display', 'actual'];

    $buildRows = function ($old, $new) use ($normalize, $paramList) {
        $old = $normalize($old);
        $new = $normalize($new);
        $old = is_array($old) ? $old : [];
        $new = is_array($new) ? $new : [];

        $rows = [];
        $pick = function ($item) use ($paramList) {
            $out = [];
            foreach ($paramList as $p) {
                $out[$p] = is_array($item) ? ($item[$p] ?? null) : null;
            }
            return $out;
        };

        foreach (array_unique(array_merge(array_keys($old), array_keys($new))) as $key) {
            $o = $normalize($old[$key] ?? null);
            $n = $normalize($new[$key] ?? null);
            $base = ucfirst(str_replace('_', ' ', $key));
            $type = $key === 'cold_storage'
                ? 'CS'
                : (preg_match('/loading|anteroom|staging/i', $key) ? 'Loading' : 'Area');

            // Nilai tunggal (bukan array) -> ditaruh di kolom Actual
            if (!is_array($o) && !is_array($n)) {
                $rows[] = [
                    'label' => $base, 'type' => $type,
                    'old' => ['setting' => null, 'display' => null, 'actual' => $o],
                    'new' => ['setting' => null, 'display' => null, 'actual' => $n],
                ];
                continue;
            }

            $oa = is_array($o) ? $o : [];
            $na = is_array($n) ? $n : [];
            $probe = !empty($oa) ? $oa : $na;
            $isFlat = empty($probe)
                || count(array_intersect(array_keys($probe), $paramList)) > 0
                || !is_array(reset($probe));

            if ($isFlat) {
                $rows[] = ['label' => $base, 'type' => $type, 'old' => $pick($oa), 'new' => $pick($na)];
                continue;
            }

            foreach (array_unique(array_merge(array_keys($oa), array_keys($na))) as $itemKey) {
                $oi = $normalize($oa[$itemKey] ?? []);
                $ni = $normalize($na[$itemKey] ?? []);
                $oi = is_array($oi) ? $oi : [];
                $ni = is_array($ni) ? $ni : [];
                $rawUnit = $ni['unit'] ?? ($oi['unit'] ?? $itemKey);
                $unit = (is_numeric($rawUnit) && (int) $rawUnit === 0) ? 1 : $rawUnit;
                $label = $key === 'cold_storage' ? "Cold Storage CS {$unit}" : trim("{$base} {$unit}");
                $rows[] = ['label' => $label, 'type' => $type, 'old' => $pick($oi), 'new' => $pick($ni)];
            }
        }

        // Tandai parameter yang berubah per baris
        foreach ($rows as &$r) {
            $r['changed'] = [];
            foreach ($paramList as $p) {
                if ((string) $r['old'][$p] !== (string) $r['new'][$p]) {
                    $r['changed'][] = $p;
                }
            }
        }
        unset($r);

        return $rows;
    };
@endphp

<style>
    .hx-wrap { --hx-red:#fde8e8; --hx-green:#dcfce7;
        --hx-border:#e5e9f0; --hx-muted:#6b7280; --hx-teal:#14b8a6; }
    .hx-card { background:#fff; border:1px solid var(--hx-border); border-radius:16px; box-shadow:0 1px 3px rgba(16,24,40,.05); }
    .hx-head { display:flex; flex-wrap:wrap; align-items:center; gap:16px; padding:18px 22px; }
    .hx-clock { width:52px; height:52px; border-radius:12px; background:#e6faf7; border:1px solid #99ece3; color:var(--hx-teal);
        display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
    .hx-edit { background:var(--hx-teal); color:#fff; font-size:.75rem; font-weight:700; padding:2px 9px; border-radius:6px; }
    .hx-warn { background:#fffbeb; border:1px solid #fcd34d; color:#b45309; font-weight:600; font-size:.85rem; padding:5px 12px; border-radius:6px; }
    .hx-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin:16px 0; }
    @media (max-width:991px){ .hx-stats { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:575px){ .hx-stats { grid-template-columns:1fr; } }
    .hx-stat { padding:14px 16px; }
    .hx-stat small { display:block; color:var(--hx-muted); font-weight:600; letter-spacing:.03em; text-transform:uppercase; font-size:.72rem; }
    .hx-stat .v { font-size:1.6rem; font-weight:700; line-height:1.3; }
    .hx-stat.warn { border-color:#fcd34d; background:#fffdf5; } .hx-stat.warn .v { color:#d97706; }
    .hx-stat.ok { border-color:#86efac; background:#f6fef9; } .hx-stat.ok .v { color:#15803d; font-size:1.05rem; }
    .hx-bar { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; padding:16px 20px; }
    .hx-title { font-weight:700; text-transform:uppercase; font-size:.95rem; display:flex; align-items:center; gap:10px; }
    .hx-title::before { content:""; width:10px; height:10px; border-radius:50%; background:var(--hx-teal); }
    .hx-tools { display:flex; flex-wrap:wrap; gap:8px; align-items:center; }
    .hx-search { position:relative; }
    .hx-search i { position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--hx-muted); }
    .hx-search input { padding:6px 12px 6px 32px; border:1px solid var(--hx-border); border-radius:8px; font-size:.85rem; width:190px; }
    .hx-table { width:100%; border-collapse:collapse; font-size:.9rem; }
    .hx-table th, .hx-table td { border:1px solid var(--hx-border); padding:10px 8px; text-align:center; vertical-align:middle; }
    .hx-table thead th { font-weight:700; background:#f8fafc; }
    .hx-table .grp-old { background:var(--hx-red) !important; color:#b91c1c; }
    .hx-table .grp-new { background:var(--hx-green) !important; color:#15803d; }
    .hx-table .sub-old { background:#fef2f2 !important; font-weight:600; }
    .hx-table .sub-new { background:#f0fdf4 !important; font-weight:600; }
    .hx-table td.loc { text-align:left; font-weight:600; padding-left:20px; }
    .hx-table td.old { background:var(--hx-red); }
    .hx-table td.new { background:var(--hx-green); }
    .hx-table td.old.same, .hx-table td.new.same { background:transparent; color:#9ca3af; }
    .hx-table td.old.chg, .hx-table td.new.chg { font-weight:700; }
    .hx-type { display:inline-block; margin-left:8px; font-size:.68rem; font-weight:700; padding:2px 7px; border-radius:5px; background:#e8f0fe; color:#2563eb; }
    .hx-type.alt { background:#eef0f3; color:#4b5563; }
    .hx-foot { display:flex; flex-wrap:wrap; justify-content:space-between; gap:10px; padding:12px 20px; font-size:.82rem; color:var(--hx-muted); }
    .hx-dot { display:inline-block; width:12px; height:12px; border-radius:3px; margin-right:6px; vertical-align:-1px; }
    .hx-note { background:#eff6ff; border:1px solid #bfdbfe; border-radius:14px; padding:16px 20px; font-size:.9rem; }
    .hx-note .t { font-weight:700; margin-bottom:6px; }
    .hx-diff-row { display:grid; grid-template-columns:140px 1fr 1fr; gap:10px; align-items:stretch; margin-bottom:8px; font-size:.88rem; }
    @media (max-width:575px){ .hx-diff-row { grid-template-columns:1fr; } }
    .hx-diff-row .o { background:var(--hx-red); border-radius:8px; padding:6px 10px; }
    .hx-diff-row .n { background:var(--hx-green); border-radius:8px; padding:6px 10px; }
    @media print { .hx-tools, .hx-back, #sidebar { display:none !important; } }
</style>

<div id="main">
    <div class="page-heading hx-wrap">
        {{-- Page title --}}
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <nav aria-label="breadcrumb" class="mb-1">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang.index') }}">Pemeriksaan Suhu Ruang</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Riwayat Perubahan Data</li>
                        </ol>
                    </nav>
                    <h3 class="mb-1">History Perubahan Data</h3>
                    <p class="mb-0">
                        Tanggal: <strong>{{ $pemeriksaanSuhuRuang->tanggal->format('d/m/Y') }}</strong>
                        <span class="mx-2 text-muted">|</span>
                        Produk: <strong>{{ $pemeriksaanSuhuRuang->produk->nama_produk ?? '-' }}</strong>
                    </p>
                </div>
                <div class="col-12 col-md-4 order-md-2 order-first text-md-end mb-3 mb-md-0 hx-back">
                    <a href="{{ route('pemeriksaan-suhu-ruang.show', $pemeriksaanSuhuRuang->uuid) }}" class="btn btn-dark">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <section class="section">
            @forelse($histories as $history)
                @php
                    $rows = $buildRows($history->suhu_data_lama, $history->suhu_data_baru);

                    $totalRows    = count($rows);
                    $changedRows  = count(array_filter($rows, fn ($r) => !empty($r['changed'])));
                    $changedVals  = array_sum(array_map(fn ($r) => count($r['changed']), $rows));
                    $csCount      = count(array_filter($rows, fn ($r) => $r['type'] === 'CS'));
                    $otherCount   = $totalRows - $csCount;

                    // Rentang suhu (actual) lama & baru
                    $oldActual = array_filter(array_map(fn ($r) => $r['old']['actual'], $rows), 'is_numeric');
                    $newActual = array_filter(array_map(fn ($r) => $r['new']['actual'], $rows), 'is_numeric');
                    $rangeOld  = $oldActual ? min($oldActual) . '°C ~ ' . max($oldActual) . '°C' : '-';
                    $rangeNew  = $newActual ? min($newActual) . '°C ~ ' . max($newActual) . '°C' : '-';

                    $textChanges = [
                        'Keterangan'       => [$history->keterangan_lama, $history->keterangan_baru],
                        'Tindakan Koreksi' => [$history->tindakan_koreksi_lama, $history->tindakan_koreksi_baru],
                        'Pukul'            => [$history->pukul_lama, $history->pukul_baru],
                        'Suhu Produk'      => [$history->suhu_produk_lama, $history->suhu_produk_baru],
                    ];
                    $textChanges = array_filter($textChanges, fn ($pair) => (string) $pair[0] !== (string) $pair[1]);
                    $noChange = empty($textChanges) && $changedRows === 0;
                @endphp

                <div class="hx-block mb-5" data-hx>
                    {{-- Ringkasan edit --}}
                    <div class="hx-card hx-head">
                        <div class="hx-clock"><i class="bi bi-clock-history"></i></div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <strong>{{ $history->created_at->format('d/m/Y') }}</strong>
                                <span class="hx-edit">Edit #{{ $loop->count - $loop->index }}</span>
                            </div>
                            <div class="text-muted">Diubah oleh: <span class="text-dark">{{ $history->user->name ?? '-' }}</span></div>
                        </div>
                        @if($changedRows > 0)
                            <span class="hx-warn">{{ $changedRows }} lokasi dikoreksi</span>
                        @endif
                        <span class="text-muted small border-start ps-3">Metode Pivot: 1 Baris per Lokasi (Compact Matrix)</span>
                    </div>

                    {{-- Kartu statistik --}}
                    <div class="hx-stats">
                        <div class="hx-card hx-stat">
                            <small>Total titik lokasi</small>
                            <div class="d-flex justify-content-between align-items-baseline">
                                <span class="v">{{ $totalRows }} Area</span>
                                <span class="text-muted small">{{ $csCount }} CS / {{ $otherCount }} Lainnya</span>
                            </div>
                        </div>
                        <div class="hx-card hx-stat warn">
                            <small class="text-warning">Parameter berubah</small>
                            <div class="d-flex justify-content-between align-items-baseline">
                                <span class="v">{{ $changedVals }} Nilai</span>
                                <span class="text-muted small">{{ $changedRows }} lokasi</span>
                            </div>
                        </div>
                        <!-- <div class="hx-card hx-stat">
                            <small>Rentang suhu (actual)</small>
                            <div class="fw-semibold mt-2">
                                <span class="text-danger">{{ $rangeOld }}</span>
                                <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                <span class="text-success">{{ $rangeNew }}</span>
                            </div>
                        </div> -->
                        <div class="hx-card hx-stat ok">
                            <small class="text-success">Verifikasi QC</small>
                            <div class="d-flex justify-content-between align-items-baseline">
                                <span class="v"><i class="bi bi-check-circle-fill"></i> Valid &amp; Tersimpan</span>
                                <span class="text-muted small">Revisi resmi</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel pivot --}}
                    @if($totalRows > 0)
                        <div class="hx-card mb-3">
                            <div class="hx-bar">
                                <div class="hx-title">Data perbandingan suhu (pivot matrix 1 baris / lokasi)</div>
                                <div class="hx-tools">
                                    <div class="hx-search">
                                        <i class="bi bi-search"></i>
                                        <input type="text" placeholder="Cari lokasi..." data-search>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="hx-table">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="text-align:left; padding-left:20px; min-width:200px;">Lokasi / Titik Ukur</th>
                                            <th colspan="3" class="grp-old"><i class="bi bi-arrow-counterclockwise"></i> Sebelumnya (Original)</th>
                                            <th colspan="3" class="grp-new"><i class="bi bi-check2-all"></i> Sesudahnya (Revisi)</th>
                                        </tr>
                                        <tr>
                                            <th class="sub-old">Setting (°C)</th>
                                            <th class="sub-old">Display (°C)</th>
                                            <th class="sub-old">Actual (°C)</th>
                                            <th class="sub-new">Setting (°C)</th>
                                            <th class="sub-new">Display (°C)</th>
                                            <th class="sub-new">Actual (°C)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rows as $r)
                                            <tr data-row data-name="{{ strtolower($r['label']) }}">
                                                <td class="loc">
                                                    {{ $r['label'] }}
                                                    <span class="hx-type {{ $r['type'] === 'CS' ? '' : 'alt' }}">{{ $r['type'] }}</span>
                                                </td>
                                                @foreach($paramList as $p)
                                                    @php $isChg = in_array($p, $r['changed']); @endphp
                                                    <td class="old {{ $isChg ? 'chg' : 'same' }}">{{ $r['old'][$p] ?? '-' }}</td>
                                                @endforeach
                                                @foreach($paramList as $i => $p)
                                                    @php $isChg = in_array($p, $r['changed']); @endphp
                                                    <td class="new {{ $isChg ? 'chg' : 'same' }}" @if($i === 0) style="border-left:2px solid #fca5a5;" @endif>{{ $r['new'][$p] ?? '-' }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        <tr data-empty style="display:none;">
                                            <td colspan="7" class="text-muted py-4">Tidak ada lokasi yang cocok.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="hx-foot">
                                <div>
                                    <span class="me-3"><span class="hx-dot" style="background:#fbd5d5;border:1px solid #fca5a5;"></span>Nilai Sebelumnya</span>
                                    <span class="me-3"><span class="hx-dot" style="background:#bbf7d0;border:1px solid #86efac;"></span>Nilai Sesudah Revisi</span>
                                    <span><i class="bi bi-arrows-collapse"></i> Terpangkas dari {{ $totalRows * 3 }} baris menjadi {{ $totalRows }} baris</span>
                                </div>
                                <div>Menampilkan <span data-count>{{ $totalRows }}</span> dari {{ $totalRows }} lokasi</div>
                            </div>
                        </div>
                    @endif

                    {{-- Perubahan teks / nilai lain (keterangan, koreksi, pukul, suhu produk) --}}
                    @if(!empty($textChanges))
                        <div class="hx-note">
                            <div class="t"><i class="bi bi-info-circle-fill text-primary"></i> Catatan perubahan lainnya</div>
                            @foreach($textChanges as $label => $pair)
                                <div class="hx-diff-row">
                                    <div class="fw-semibold pt-1">{{ $label }}</div>
                                    <div class="o"><small class="text-muted d-block">Sebelumnya</small>{{ ($pair[0] === null || $pair[0] === '') ? '(Kosong)' : $pair[0] }}</div>
                                    <div class="n"><small class="text-muted d-block">Sesudahnya</small>{{ ($pair[1] === null || $pair[1] === '') ? '(Kosong)' : $pair[1] }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($noChange)
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> Tidak ada perubahan data pada edit ini
                        </div>
                    @endif
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="bi bi-inbox"></i> Belum ada history perubahan data
                </div>
            @endforelse
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-hx]').forEach(function (block) {
        var rows = block.querySelectorAll('tr[data-row]');
        var searchInput = block.querySelector('[data-search]');
        if (!rows.length || !searchInput) return;

        var emptyRow = block.querySelector('tr[data-empty]');
        var counter = block.querySelector('[data-count]');

        searchInput.addEventListener('input', function () {
            var q = searchInput.value.trim().toLowerCase();
            var shown = 0;
            rows.forEach(function (tr) {
                var ok = !q || tr.dataset.name.indexOf(q) !== -1;
                tr.style.display = ok ? '' : 'none';
                if (ok) shown++;
            });
            emptyRow.style.display = shown ? 'none' : '';
            counter.textContent = shown;
        });
    });
});
</script>

@endsection