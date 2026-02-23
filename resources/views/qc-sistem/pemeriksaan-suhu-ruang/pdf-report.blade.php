<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Suhu Ruang</title>
    <style>
        @page { size: A4; margin: 12mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 9px; line-height: 1.4; color: #1a1a1a; }
        .header { display: table; width: 100%; margin-bottom: 12px; border-bottom: 3px solid #c41e3a; padding-bottom: 10px; }
        .header-left { display: table-cell; width: 60%; vertical-align: middle; }
        .header-right { display: table-cell; width: 40%; vertical-align: middle; text-align: right; }
        .logo-company { display: table; width: 100%; }
        .header-logo { display: table-cell; width: 55px; vertical-align: middle; }
        .header-logo img { width: 50px; height: 50px; object-fit: contain; }
        .header-company { display: table-cell; vertical-align: middle; padding-left: 12px; }
        .header-company h2 { font-size: 12px; font-weight: bold; color: #c41e3a; margin-bottom: 2px; letter-spacing: 0.5px; }
        .header-company p { font-size: 8px; color: #444; margin-bottom: 1px; }
        .header-title h1 { font-size: 13px; font-weight: bold; color: #1a1a1a; background: #f1f3f5; padding: 8px 15px; border-radius: 4px; border-left: 4px solid #c41e3a; display: inline-block; }

        .subheader { width: 100%; border: 1px solid #dee2e6; border-radius: 6px; margin-bottom: 10px; background: #f8f9fa; }
        .subheader-table { width: 100%; border-collapse: collapse; }
        .subheader-table td { padding: 6px 10px; font-size: 8px; border-bottom: 1px solid #e9ecef; vertical-align: top; }
        .subheader-table tr:last-child td { border-bottom: none; }
        .subheader-label { font-weight: 600; color: #495057; width: 100px; }

        .section-title { font-weight: bold; font-size: 9px; color: #c41e3a; margin: 10px 0 6px; }
        table.data { width: 100%; border-collapse: collapse; border: 1px solid #dee2e6; }
        table.data th { background: #f1f3f5; font-weight: 700; font-size: 8px; padding: 6px; border: 1px solid #dee2e6; text-align: left; }
        table.data td { font-size: 8px; padding: 6px; border: 1px solid #dee2e6; vertical-align: top; }
        .muted { color: #6c757d; }

        .signature { width: 100%; margin-top: 16px; border-top: 1px solid #dee2e6; padding-top: 10px; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-cell { width: 33.33%; text-align: center; font-size: 8px; padding: 6px 2px; vertical-align: top; }
        .signature-header-item { font-weight: 600; }
        .signature-space { height: 28px; border-bottom: 2px solid #1a1a1a; margin: 0 10px; }
        .signature-name { font-weight: 700; }

        .page-break { page-break-after: always; }
        .page-break:last-child { page-break-after: auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <div class="logo-company">
                    <div class="header-logo">
                        <img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo">
                    </div>
                    <div class="header-company">
                        <h2>PT. CHAROEN POKPHAND INDONESIA</h2>
                        <p>FOOD DIVISION MEDAN</p>
                        <p>MEDAN - INDONESIA</p>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <div class="header-title">
                    <h1>PEMERIKSAAN SUHU RUANG FOOD PROCESSING</h1>
                </div>
            </div>
        </div>

        @foreach(($pemeriksaans ?? []) as $idx => $p)
            @php
                $plantName = $p->user && $p->user->plant ? ($p->user->plant->plant ?? '-') : '-';
                $shiftName = $p->shift ? ($p->shift->shift ?? '-') : '-';
                $produkName = $p->produk ? ($p->produk->nama_produk ?? '-') : '-';
                $kategori = $p->produk ? ($p->produk->kategori_code ?? null) : null;

                $suhu = is_array($p->suhu_data) ? $p->suhu_data : (json_decode($p->suhu_data ?? '[]', true) ?: []);
                $hasAny = false;

                $jamPukul = null;
                if (!empty($p->pukul)) {
                    try {
                        $jamPukul = \Carbon\Carbon::parse($p->pukul)->format('H:i');
                    } catch (\Throwable $e) {
                        $jamPukul = (string) $p->pukul;
                    }
                }

                $defaultJam = $p->created_at ? $p->created_at->format('H:i') : null;

                $unitJamColdStorage = [];
                $unitJamAnteroomLoading = [];
                $jamPreLoading = null;
                $jamPrestaging = null;
                $jamAnteroomEkspansiFurther = null;
                $jamAnteroomEkspansiSausage = null;

                $histories = [];
                if ($p->relationLoaded('histories') && $p->histories) {
                    $histories = $p->histories;
                }

                $isSame = function ($a, $b) {
                    return json_encode($a) === json_encode($b);
                };

                $findUnitRow = function ($rows, $unit) {
                    if (!is_array($rows)) {
                        return null;
                    }
                    foreach ($rows as $r) {
                        if (!is_array($r)) {
                            continue;
                        }
                        if ((string) ($r['unit'] ?? '') === (string) $unit) {
                            return $r;
                        }
                    }
                    return null;
                };

                if ($histories instanceof \Illuminate\Support\Collection) {
                    $histories = $histories->sortByDesc('created_at');
                } elseif (is_array($histories)) {
                    usort($histories, function ($a, $b) {
                        $at = $a && $a->created_at ? $a->created_at->timestamp : 0;
                        $bt = $b && $b->created_at ? $b->created_at->timestamp : 0;
                        return $bt <=> $at;
                    });
                }

                foreach ($histories as $h) {
                    $hJam = $h->created_at ? $h->created_at->format('H:i') : null;
                    if (!$hJam) {
                        continue;
                    }

                    $lama = is_array($h->suhu_data_lama) ? $h->suhu_data_lama : (json_decode($h->suhu_data_lama ?? '[]', true) ?: []);
                    $baru = is_array($h->suhu_data_baru) ? $h->suhu_data_baru : (json_decode($h->suhu_data_baru ?? '[]', true) ?: []);

                    // Per unit sections
                    $coldLama = $lama['cold_storage'] ?? [];
                    $coldBaru = $baru['cold_storage'] ?? [];
                    $coldUnits = array_unique(array_merge(
                        array_map(fn ($r) => is_array($r) ? (string) ($r['unit'] ?? '') : '', (array) $coldLama),
                        array_map(fn ($r) => is_array($r) ? (string) ($r['unit'] ?? '') : '', (array) $coldBaru)
                    ));
                    foreach ($coldUnits as $u) {
                        if ($u === '') {
                            continue;
                        }
                        if (array_key_exists($u, $unitJamColdStorage)) {
                            continue;
                        }
                        $a = $findUnitRow($coldLama, $u);
                        $b = $findUnitRow($coldBaru, $u);
                        if (!$isSame($a, $b)) {
                            $unitJamColdStorage[$u] = $hJam;
                        }
                    }

                    $antLama = $lama['anteroom_loading'] ?? [];
                    $antBaru = $baru['anteroom_loading'] ?? [];
                    $antUnits = array_unique(array_merge(
                        array_map(fn ($r) => is_array($r) ? (string) ($r['unit'] ?? '') : '', (array) $antLama),
                        array_map(fn ($r) => is_array($r) ? (string) ($r['unit'] ?? '') : '', (array) $antBaru)
                    ));
                    foreach ($antUnits as $u) {
                        if ($u === '') {
                            continue;
                        }
                        if (array_key_exists($u, $unitJamAnteroomLoading)) {
                            continue;
                        }
                        $a = $findUnitRow($antLama, $u);
                        $b = $findUnitRow($antBaru, $u);
                        if (!$isSame($a, $b)) {
                            $unitJamAnteroomLoading[$u] = $hJam;
                        }
                    }

                    // Single sections
                    if ($jamPreLoading === null && !$isSame($lama['pre_loading'] ?? null, $baru['pre_loading'] ?? null)) {
                        $jamPreLoading = $hJam;
                    }
                    if ($jamPrestaging === null && !$isSame($lama['prestaging'] ?? null, $baru['prestaging'] ?? null)) {
                        $jamPrestaging = $hJam;
                    }
                    if ($jamAnteroomEkspansiFurther === null && !$isSame($lama['anteroom_ekspansi_further'] ?? null, $baru['anteroom_ekspansi_further'] ?? null)) {
                        $jamAnteroomEkspansiFurther = $hJam;
                    }
                    if ($jamAnteroomEkspansiSausage === null && !$isSame($lama['anteroom_ekspansi_sausage'] ?? null, $baru['anteroom_ekspansi_sausage'] ?? null)) {
                        $jamAnteroomEkspansiSausage = $hJam;
                    }
                }
            @endphp

            <div class="subheader">
                <table class="subheader-table">
                    <tr>
                        <td class="subheader-label">Tanggal</td>
                        <td>{{ $p->tanggal ? $p->tanggal->format('d/m/Y') : '-' }}</td>
                        <td class="subheader-label">Shift</td>
                        <td>{{ $shiftName }}</td>
                    </tr>
                    <tr>
                        <td class="subheader-label">Plant</td>
                        <td>{{ $plantName }}</td>
                        <td class="subheader-label">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="subheader-label">Produk</td>
                        <td>
                            @if($kategori)
                                <span style="font-weight: 700;">{{ $kategori }}</span> -
                            @endif
                            {{ $produkName }}
                        </td>
                        <td class="subheader-label">Pukul</td>
                        <td>{{ $jamPukul ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="subheader-label">Suhu Produk</td>
                        <td>{{ $p->suhu_produk ?? '-' }}</td>
                        <!-- <td class="subheader-label">Status</td>
                        <td>{{ $p->status_verifikasi ?? 'pending' }}</td> -->
                    </tr>
                </table>
            </div>

            @php
                $renderUnitArray = function ($title, $rows, $defaultJam = null, $unitJamMap = []) use (&$hasAny) {
                    $norm = [];
                    $hasJam = false;
                    $hasSetting = false;
                    $hasDisplay = false;
                    $hasActual = false;

                    if (is_array($rows)) {
                        foreach ($rows as $item) {
                            if (!is_array($item)) continue;
                            $unit = $item['unit'] ?? null;
                            if ($unit === null || $unit === '') continue;

                            $setting = $item['setting'] ?? null;
                            $display = $item['display'] ?? null;
                            $actual = $item['actual'] ?? null;

                            $isFilledTemp = !empty($setting) || !empty($display) || !empty($actual) || $setting === '0' || $display === '0' || $actual === '0';
                            if (!$isFilledTemp) continue;

                            $jam = $item['jam'] ?? null;
                            if (($jam === null || $jam === '') && is_array($unitJamMap) && array_key_exists((string) $unit, $unitJamMap)) {
                                $jam = $unitJamMap[(string) $unit];
                            }
                            if (($jam === null || $jam === '') && ($defaultJam !== null && $defaultJam !== '')) {
                                $jam = $defaultJam;
                            }

                            $hasJam = $hasJam || ($jam !== null && $jam !== '');
                            $hasSetting = $hasSetting || ($setting !== null && $setting !== '');
                            $hasDisplay = $hasDisplay || ($display !== null && $display !== '');
                            $hasActual = $hasActual || ($actual !== null && $actual !== '');

                            $norm[] = [
                                'unit' => $unit,
                                'jam' => $jam,
                                'setting' => $setting,
                                'display' => $display,
                                'actual' => $actual,
                            ];
                        }
                    }

                    if (count($norm) === 0) return '';
                    $hasAny = true;

                    ob_start();
                    echo '<div class="section-title">' . e($title) . '</div>';
                    echo '<table class="data">';
                    echo '<thead><tr>';
                    echo '<th style="width: 14%">Unit</th>';
                    if ($hasJam) { echo '<th style="width: 16%">Jam</th>'; }
                    if ($hasSetting) { echo '<th>Setting</th>'; }
                    if ($hasDisplay) { echo '<th>Display</th>'; }
                    if ($hasActual) { echo '<th>Actual</th>'; }
                    echo '</tr></thead>';
                    echo '<tbody>';
                    foreach ($norm as $r) {
                        echo '<tr>';
                        echo '<td>' . e((string) $r['unit']) . '</td>';
                        if ($hasJam) { echo '<td>' . e((string) ($r['jam'] ?? '')) . '</td>'; }
                        if ($hasSetting) { echo '<td>' . e((string) ($r['setting'] ?? '')) . '</td>'; }
                        if ($hasDisplay) { echo '<td>' . e((string) ($r['display'] ?? '')) . '</td>'; }
                        if ($hasActual) { echo '<td>' . e((string) ($r['actual'] ?? '')) . '</td>'; }
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                    return ob_get_clean();
                };

                $renderCols = function ($title, $data, $defaultJam = null) use (&$hasAny) {
                    if (!is_array($data)) return '';
                    $setting = $data['setting'] ?? null;
                    $display = $data['display'] ?? null;
                    $actual = $data['actual'] ?? null;

                    $isFilledTemp = !empty($setting) || !empty($display) || !empty($actual) || $setting === '0' || $display === '0' || $actual === '0';
                    if (!$isFilledTemp) return '';

                    $jam = $data['jam'] ?? null;
                    if (($jam === null || $jam === '') && ($defaultJam !== null && $defaultJam !== '')) {
                        $jam = $defaultJam;
                    }

                    $hasAny = true;
                    $hasJam = ($jam !== null && $jam !== '');
                    $hasSetting = ($setting !== null && $setting !== '');
                    $hasDisplay = ($display !== null && $display !== '');
                    $hasActual = ($actual !== null && $actual !== '');

                    ob_start();
                    echo '<div class="section-title">' . e($title) . '</div>';
                    echo '<table class="data">';
                    echo '<thead><tr>';
                    if ($hasJam) { echo '<th style="width: 16%">Jam</th>'; }
                    if ($hasSetting) { echo '<th>Setting</th>'; }
                    if ($hasDisplay) { echo '<th>Display</th>'; }
                    if ($hasActual) { echo '<th>Actual</th>'; }
                    echo '</tr></thead>';
                    echo '<tbody><tr>';
                    if ($hasJam) { echo '<td>' . e((string) ($jam ?? '')) . '</td>'; }
                    if ($hasSetting) { echo '<td>' . e((string) ($setting ?? '')) . '</td>'; }
                    if ($hasDisplay) { echo '<td>' . e((string) ($display ?? '')) . '</td>'; }
                    if ($hasActual) { echo '<td>' . e((string) ($actual ?? '')) . '</td>'; }
                    echo '</tr></tbody></table>';
                    return ob_get_clean();
                };
            @endphp

            {!! $renderUnitArray('Suhu Cold Storage', $suhu['cold_storage'] ?? [], $defaultJam, $unitJamColdStorage) !!}
            {!! $renderUnitArray('Suhu Anteroom Loading', $suhu['anteroom_loading'] ?? [], $defaultJam, $unitJamAnteroomLoading) !!}
            {!! $renderCols('Suhu Pre Loading', $suhu['pre_loading'] ?? [], $jamPreLoading ?: $defaultJam) !!}
            {!! $renderCols('Suhu Prestaging', $suhu['prestaging'] ?? [], $jamPrestaging ?: $defaultJam) !!}
            {!! $renderCols('Suhu Anteroom Ekspansi Further', $suhu['anteroom_ekspansi_further'] ?? [], $jamAnteroomEkspansiFurther ?: $defaultJam) !!}
            {!! $renderCols('Suhu Anteroom Ekspansi Sausage', $suhu['anteroom_ekspansi_sausage'] ?? [], $jamAnteroomEkspansiSausage ?: $defaultJam) !!}

            @if(!empty($p->keterangan) || !empty($p->tindakan_koreksi))
                <div class="section-title">Keterangan / Tindakan Koreksi</div>
                <table class="data">
                    <tbody>
                        @if(!empty($p->keterangan))
                            <tr>
                                <td style="width: 25%"><strong>Keterangan</strong></td>
                                <td>{{ $p->keterangan }}</td>
                            </tr>
                        @endif
                        @if(!empty($p->tindakan_koreksi))
                            <tr>
                                <td style="width: 25%"><strong>Tindakan Koreksi</strong></td>
                                <td>{{ $p->tindakan_koreksi }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endif

            @if(!$hasAny && empty($p->keterangan) && empty($p->tindakan_koreksi))
                <div class="section-title">Data Suhu</div>
                <div class="muted">Tidak ada data yang terisi.</div>
            @endif

            <div class="signature">
                <table class="signature-table">
                    <tr>
                        <td class="signature-cell">
                            <div class="signature-header-item">Dibuat Oleh (QC)</div>
                            <div class="signature-space"></div>
                            <div class="signature-name">{{ $p->verifiedByQc->name ?? $p->user->name ?? '-' }}</div>
                        </td>
                        <td class="signature-cell">
                            <div class="signature-header-item">Disetujui Oleh (Tim Warehouse)</div>
                            <div class="signature-space"></div>
                            <div class="signature-name">{{ $p->verifiedByProduksi->name ?? '-' }}</div>
                        </td>
                        <td class="signature-cell">
                            <div class="signature-header-item">Diverifikasi Oleh (SPV QC)</div>
                            <div class="signature-space"></div>
                            <div class="signature-name">{{ $p->verifiedBySpv->name ?? '-' }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
                QW 11/00
            </div>

            @if($idx < (count($pemeriksaans ?? []) - 1))
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
</body>
</html>
