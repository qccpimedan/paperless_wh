<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Suhu Ruang V3</title>
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
        .subheader-label { font-weight: 600; color: #495057; width: 110px; }

        .section-title { font-weight: bold; font-size: 9px; color: #c41e3a; margin: 10px 0 6px; }
        table.data { width: 100%; border-collapse: collapse; border: 1px solid #dee2e6; }
        table.data th { background: #f1f3f5; font-weight: 700; font-size: 8px; padding: 6px; border: 1px solid #dee2e6; text-align: left; }
        table.data td { font-size: 8px; padding: 6px; border: 1px solid #dee2e6; vertical-align: top; }
        .muted { color: #6c757d; }

        .signature { width: 100%; margin-top: 16px; border-top: 1px solid #dee2e6; padding-top: 10px; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-cell { width: 33.33%; text-align: center; font-size: 8px; padding: 6px 2px; vertical-align: top; }
        .signature-header-item { font-weight: 600; }
        .signature-space { height: 60px; margin: 0 10px; display: flex; align-items: center; justify-content: center; }
        .signature-line-empty { border-bottom: 2px solid #1a1a1a; height: 28px; width: 100%; }
        .qr-code-img { max-height: 55px; max-width: 55px; }
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
                    <h1>PEMERIKSAAN SUHU RUANG GUDANG DRY</h1>
                </div>
            </div>
        </div>

        @foreach(($pemeriksaans ?? []) as $idx => $p)
            @php
                $plantName = $p->user && $p->user->plant ? ($p->user->plant->plant ?? '-') : '-';
                $shiftName = $p->shift ? ($p->shift->shift ?? '-') : '-';

                $jamPukul = null;
                if (!empty($p->pukul)) {
                    try {
                        $jamPukul = \Carbon\Carbon::parse($p->pukul)->format('H:i');
                    } catch (\Throwable $e) {
                        $jamPukul = (string) $p->pukul;
                    }
                }

                $defaultJam = $p->created_at ? $p->created_at->format('H:i') : null;

                $unitJamPremix = [];
                $unitJamSeasoning = [];
                $unitJamDry = [];
                $unitJamCassing = [];
                $unitJamBeef = [];
                $unitJamPackaging = [];
                $unitJamRuangChemical = [];
                $unitJamRuangSeasoning = [];

                $histories = [];
                if ($p->relationLoaded('histories') && $p->histories) {
                    $histories = $p->histories;
                }

                $isSame = function ($a, $b) {
                    return json_encode($a) === json_encode($b);
                };

                if ($histories instanceof \Illuminate\Support\Collection) {
                    $histories = $histories->sortByDesc('created_at');
                }

                foreach ($histories as $h) {
                    $hJam = $h->created_at ? $h->created_at->format('H:i') : null;
                    if (!$hJam) {
                        continue;
                    }

                    $mapUnitJam = function ($field, &$targetMap) use ($h, $hJam, $isSame) {
                        $lama = $h->{$field . '_lama'} ?? [];
                        $baru = $h->{$field . '_baru'} ?? [];
                        $keys = array_unique(array_merge(array_keys((array) $lama), array_keys((array) $baru)));

                        foreach ($keys as $k) {
                            $kk = (string) $k;
                            if ($kk === '') {
                                continue;
                            }
                            if (array_key_exists($kk, $targetMap)) {
                                continue;
                            }
                            $a = is_array($lama) ? ($lama[$k] ?? null) : null;
                            $b = is_array($baru) ? ($baru[$k] ?? null) : null;
                            if (!$isSame($a, $b)) {
                                $targetMap[$kk] = $hJam;
                            }
                        }
                    };

                    $mapUnitJam('suhu_premix', $unitJamPremix);
                    $mapUnitJam('suhu_seasoning', $unitJamSeasoning);
                    $mapUnitJam('suhu_dry', $unitJamDry);
                    $mapUnitJam('suhu_cassing', $unitJamCassing);
                    $mapUnitJam('suhu_beef', $unitJamBeef);
                    $mapUnitJam('suhu_packaging', $unitJamPackaging);
                    $mapUnitJam('suhu_ruang_chemical', $unitJamRuangChemical);
                    $mapUnitJam('suhu_ruang_seasoning', $unitJamRuangSeasoning);
                }

                $hasAny = false;

                $renderUnitMap = function ($title, $data, $defaultJam = null, $unitJamMap = []) use (&$hasAny) {
                    if (!is_array($data) || count($data) === 0) return '';

                    $rows = [];
                    $hasJam = false;
                    $hasSetting = false;
                    $hasDisplay = false;
                    $hasActual = false;

                    foreach ($data as $unitKey => $vals) {
                        if (!is_array($vals)) continue;

                        $setting = $vals['setting'] ?? null;
                        $display = $vals['display'] ?? null;
                        $actual = $vals['actual'] ?? null;

                        // Jam tidak boleh memicu tampilnya row/section.
                        $isFilledTemp = !empty($setting) || !empty($display) || !empty($actual) || $setting === '0' || $display === '0' || $actual === '0';
                        if (!$isFilledTemp) continue;

                        $jam = $vals['jam'] ?? null;
                        if (($jam === null || $jam === '') && is_array($unitJamMap) && array_key_exists((string) $unitKey, $unitJamMap)) {
                            $jam = $unitJamMap[(string) $unitKey];
                        }
                        if (($jam === null || $jam === '') && ($defaultJam !== null && $defaultJam !== '')) {
                            $jam = $defaultJam;
                        }

                        $rows[] = [
                            'unit' => str_replace('unit_', '', (string) $unitKey),
                            'jam' => $jam,
                            'setting' => $setting,
                            'display' => $display,
                            'actual' => $actual,
                        ];

                        $hasJam = $hasJam || ($jam !== null && $jam !== '');
                        $hasSetting = $hasSetting || ($setting !== null && $setting !== '');
                        $hasDisplay = $hasDisplay || ($display !== null && $display !== '');
                        $hasActual = $hasActual || ($actual !== null && $actual !== '');
                    }

                    if (count($rows) === 0) return '';
                    $hasAny = true;

                    ob_start();
                    echo '<div class="section-title">' . e($title) . '</div>';
                    echo '<table class="data">';
                    echo '<thead><tr>';
                    echo '<th style="width: 18%">Unit</th>';
                    if ($hasJam) { echo '<th style="width: 16%">Jam</th>'; }
                    if ($hasSetting) { echo '<th>Setting</th>'; }
                    if ($hasDisplay) { echo '<th>Display</th>'; }
                    if ($hasActual) { echo '<th>Actual</th>'; }
                    echo '</tr></thead>';
                    echo '<tbody>';
                    foreach ($rows as $r) {
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
                        <td class="subheader-label">Pukul</td>
                        <td>{{ $jamPukul ?? '-' }}</td>
                        <td class="subheader-label">Status</td>
                        <td>{{ $p->status_verifikasi ?? 'pending' }}</td>
                    </tr>
                </table>
            </div>

            {!! $renderUnitMap('Suhu Premix', $p->suhu_premix ?? [], $defaultJam, $unitJamPremix) !!}
            {!! $renderUnitMap('Suhu Seasoning', $p->suhu_seasoning ?? [], $defaultJam, $unitJamSeasoning) !!}
            {!! $renderUnitMap('Suhu Dry', $p->suhu_dry ?? [], $defaultJam, $unitJamDry) !!}
            {!! $renderUnitMap('Suhu Cassing', $p->suhu_cassing ?? [], $defaultJam, $unitJamCassing) !!}
            {!! $renderUnitMap('Suhu Beef', $p->suhu_beef ?? [], $defaultJam, $unitJamBeef) !!}
            {!! $renderUnitMap('Suhu Packaging', $p->suhu_packaging ?? [], $defaultJam, $unitJamPackaging) !!}
            {!! $renderUnitMap('Suhu Ruang Chemical', $p->suhu_ruang_chemical ?? [], $defaultJam, $unitJamRuangChemical) !!}
            {!! $renderUnitMap('Suhu Ruang Seasoning', $p->suhu_ruang_seasoning ?? [], $defaultJam, $unitJamRuangSeasoning) !!}

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
                            <div class="signature-space">
                                @if($p->verifiedByQc || $p->user)
                                    @php
                                        $qcNameStr = $p->verifiedByQc->name ?? $p->user->name;
                                        $qcQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$qcNameStr} (Tim QC)";
                                        $qcQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($qcQrData);
                                        $base64QcSvg = "data:image/svg+xml;base64," . base64_encode($qcQrCodeSvg);
                                    @endphp
                                    <img src="{{ $base64QcSvg }}" class="qr-code-img" alt="QR Code QC">
                                @else
                                    <div class="signature-line-empty"></div>
                                @endif
                            </div>
                            <div class="signature-name">{{ $p->verifiedByQc->name ?? $p->user->name ?? '-' }}</div>
                        </td>
                        <td class="signature-cell">
                            <div class="signature-header-item">Disetujui Oleh (Tim Warehouse)</div>
                            <div class="signature-space">
                                @if($p->verifiedByProduksi)
                                    @php
                                        $prodQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$p->verifiedByProduksi->name} (Tim Warehouse)";
                                        $prodQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($prodQrData);
                                        $base64ProdSvg = "data:image/svg+xml;base64," . base64_encode($prodQrCodeSvg);
                                    @endphp
                                    <img src="{{ $base64ProdSvg }}" class="qr-code-img" alt="QR Code Warehouse">
                                @else
                                    <div class="signature-line-empty"></div>
                                @endif
                            </div>
                            <div class="signature-name">{{ $p->verifiedByProduksi->name ?? '-' }}</div>
                        </td>
                        <td class="signature-cell">
                            <div class="signature-header-item">Diverifikasi Oleh (SPV QC)</div>
                            <div class="signature-space">
                                @if($p->verifiedBySpv)
                                    @php
                                        $spvQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$p->verifiedBySpv->name} (Tim Supervisor QC)";
                                        $spvQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($spvQrData);
                                        $base64SpvSvg = "data:image/svg+xml;base64," . base64_encode($spvQrCodeSvg);
                                    @endphp
                                    <img src="{{ $base64SpvSvg }}" class="qr-code-img" alt="QR Code SPV">
                                @else
                                    <div class="signature-line-empty"></div>
                                @endif
                            </div>
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
