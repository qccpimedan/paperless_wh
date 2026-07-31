<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Suhu Ruang V2</title>
    @php
        $firstRecord = $pemeriksaans->first();
        $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
    @endphp
    <style>
        @page { 
            size: A4;
            margin: 12mm; 
        }
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
        .signature-table td { width: 33.33%; text-align: center; font-size: 8px; padding: 6px 2px; }
        .sig-name { margin-top: 28px; font-weight: 700; }
        .signature-cell { width: 33.33%; text-align: center; font-size: 8px; padding: 6px 2px; }
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

        @foreach(($pemeriksaans ?? []) as $idx => $p)
            {{-- Header di setiap record (otomatis juga di page baru karena page-break) --}}
            <div class="header">
                <div class="header-left">
                    <div class="logo-company">
                        <div class="header-logo">
                            <img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo">
                        </div>
                        <div class="header-company">
                            <h2>PT. CHAROEN POKPHAND INDONESIA</h2>
                            <p>FOOD DIVISION {{ strtoupper($plantName) }}</p>
                            <p>{{ strtoupper($plantName) }} - INDONESIA</p>
                        </div>
                    </div>
                </div>
                <div class="header-right">
                    <div class="header-title">
                        <h1>PEMERIKSAAN SUHU RUANG CS MEAT</h1>
                    </div>
                </div>
            </div>
            @php
                $plantName = $p->user && $p->user->plant ? $p->user->plant->plant : '-';
                $shiftName = $p->shift ? ($p->shift->shift ?? '-') : '-';
                $produkName = $p->produk ? ($p->produk->nama_produk ?? '-') : '-';
                $kategori = $p->produk ? ($p->produk->kategori_code ?? null) : null;

                $cold = is_array($p->suhu_cold_storage) ? $p->suhu_cold_storage : (json_decode($p->suhu_cold_storage ?? '[]', true) ?: []);
                $anteroom = is_array($p->suhu_anteroom_loading) ? $p->suhu_anteroom_loading : (json_decode($p->suhu_anteroom_loading ?? '[]', true) ?: []);

                $pre = is_array($p->suhu_pre_loading) ? $p->suhu_pre_loading : (json_decode($p->suhu_pre_loading ?? '[]', true) ?: []);
                $prestaging = is_array($p->suhu_prestaging) ? $p->suhu_prestaging : (json_decode($p->suhu_prestaging ?? '[]', true) ?: []);
                $abf = is_array($p->suhu_anteroom_ekspansi_abf) ? $p->suhu_anteroom_ekspansi_abf : (json_decode($p->suhu_anteroom_ekspansi_abf ?? '[]', true) ?: []);
                $rm = is_array($p->suhu_chillroom_rm) ? $p->suhu_chillroom_rm : (json_decode($p->suhu_chillroom_rm ?? '[]', true) ?: []);
                $dom = is_array($p->suhu_chillroom_domestik) ? $p->suhu_chillroom_domestik : (json_decode($p->suhu_chillroom_domestik ?? '[]', true) ?: []);

                $hasAny = false;
            @endphp

            <div class="subheader">
                <table class="subheader-table">
                    <tr>
                        <td class="subheader-label">Tanggal</td>
                        <td>{{ $p->tanggal ? $p->tanggal->format('d/m/Y') : '-' }}</td>
                        
                    </tr>
                    <tr>
                        <td class="subheader-label">Shift</td>
                        <td>{{ $shiftName }}</td>
                    </tr>
                    <!-- <tr>
                        <td class="subheader-label">Pukul</td>
                        <td>{{ $p->pukul ?? '-' }}</td>
                    </tr> -->
                    <tr>
                        <td class="subheader-label">Suhu Produk</td>
                        <td style="font-weight: bold;">{{ $p->suhu_produk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="subheader-label">Produk</td>
                        <td>
                            @if($kategori)
                                <span>{{ $kategori }} - {{ $produkName }}</span>
                            @else
                                <span>{{ $produkName }}</span>
                            @endif
                        </td>
                        <!-- <td class="subheader-label">Status</td>
                        <td>{{ $p->status_verifikasi ?? 'pending' }}</td> -->
                    </tr>
                </table>
            </div>

            @php
                $renderUnitTable = function ($title, $units, $defaultJam = null, $unitJamMap = []) use (&$hasAny) {
                    $rows = [];
                    $hasJam = false;
                    $hasSetting = false;
                    $hasDisplay = false;
                    $hasActual = false;

                    if (is_array($units)) {
                        foreach ($units as $unitKey => $item) {
                            $jam = null;
                            if (is_array($item)) {
                                $jam = $item['jam'] ?? null;
                            }
                            if (($jam === null || $jam === '') && is_array($unitJamMap) && array_key_exists((string) $unitKey, $unitJamMap)) {
                                $jam = $unitJamMap[(string) $unitKey];
                            }
                            if (($jam === null || $jam === '') && ($defaultJam !== null && $defaultJam !== '')) {
                                $jam = $defaultJam;
                            }
                            $setting = is_array($item) ? ($item['setting'] ?? null) : null;
                            $display = is_array($item) ? ($item['display'] ?? null) : null;
                            $actual = is_array($item) ? ($item['actual'] ?? null) : null;

                            // Jam tidak boleh memicu tampilnya row/section.
                            $isFilledTemp = !empty($setting) || !empty($display) || !empty($actual) || $setting === '0' || $display === '0' || $actual === '0';
                            if (!$isFilledTemp) {
                                continue;
                            }

                            $hasJam = $hasJam || ($jam !== null && $jam !== '');
                            $hasSetting = $hasSetting || ($setting !== null && $setting !== '');
                            $hasDisplay = $hasDisplay || ($display !== null && $display !== '');
                            $hasActual = $hasActual || ($actual !== null && $actual !== '');

                            $rows[] = [
                                'unit' => $unitKey,
                                'jam' => $jam,
                                'setting' => $setting,
                                'display' => $display,
                                'actual' => $actual,
                            ];
                        }
                    }

                    if (count($rows) === 0) {
                        return '';
                    }

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

                $render3Col = function ($title, $data, $defaultJam = null) use (&$hasAny) {
                    $jam = is_array($data) ? ($data['jam'] ?? null) : null;
                    if (($jam === null || $jam === '') && ($defaultJam !== null && $defaultJam !== '')) {
                        $jam = $defaultJam;
                    }
                    $setting = is_array($data) ? ($data['setting'] ?? null) : null;
                    $display = is_array($data) ? ($data['display'] ?? null) : null;
                    $actual = is_array($data) ? ($data['actual'] ?? null) : null;

                    // Jam tidak boleh memicu tampilnya section.
                    $isFilledTemp = !empty($setting) || !empty($display) || !empty($actual) || $setting === '0' || $display === '0' || $actual === '0';
                    if (!$isFilledTemp) {
                        return '';
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

            @php
                $defaultJam = $p->created_at ? $p->created_at->format('H:i') : null;

                $unitJamCold = [];
                $unitJamAnteroom = [];

                $histories = [];
                if ($p->relationLoaded('histories') && $p->histories) {
                    $histories = $p->histories;
                }

                $isSame = function ($a, $b) {
                    return json_encode($a) === json_encode($b);
                };

                foreach ($histories as $h) {
                    $hJam = $h->created_at ? $h->created_at->format('H:i') : null;

                    // Cold Storage per unit
                    $coldLama = $h->suhu_cold_storage_lama ?? [];
                    $coldBaru = $h->suhu_cold_storage_baru ?? [];
                    $coldKeys = array_unique(array_merge(array_keys((array) $coldLama), array_keys((array) $coldBaru)));
                    foreach ($coldKeys as $k) {
                        $kk = (string) $k;
                        if (array_key_exists($kk, $unitJamCold)) {
                            continue;
                        }
                        $a = is_array($coldLama) ? ($coldLama[$k] ?? null) : null;
                        $b = is_array($coldBaru) ? ($coldBaru[$k] ?? null) : null;
                        if (!$isSame($a, $b) && $hJam) {
                            $unitJamCold[$kk] = $hJam;
                        }
                    }

                    // Anteroom Loading per unit
                    $antLama = $h->suhu_anteroom_loading_lama ?? [];
                    $antBaru = $h->suhu_anteroom_loading_baru ?? [];
                    $antKeys = array_unique(array_merge(array_keys((array) $antLama), array_keys((array) $antBaru)));
                    foreach ($antKeys as $k) {
                        $kk = (string) $k;
                        if (array_key_exists($kk, $unitJamAnteroom)) {
                            continue;
                        }
                        $a = is_array($antLama) ? ($antLama[$k] ?? null) : null;
                        $b = is_array($antBaru) ? ($antBaru[$k] ?? null) : null;
                        if (!$isSame($a, $b) && $hJam) {
                            $unitJamAnteroom[$kk] = $hJam;
                        }
                    }
                }
            @endphp
            
            {{-- Tabel per ruangan ditiadakan karena format riwayat di bawah sudah melengkappi data initial (input pertama) --}}

            {{-- Data & Riwayat Pemeriksaan Table --}}
            <div class="section-title">Data & Riwayat Pemeriksaan</div>
            <table class="data">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 25%">Lokasi</th>
                        <th style="width: 35%">Sebelumnya</th>
                        <th style="width: 35%">Sesudahnya</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $histNo = 1;
                        $suhuFieldsConfig = [
                            'suhu_cold_storage' => 'Cold Storage',
                            'suhu_anteroom_loading' => 'Anteroom Loading',
                            'suhu_pre_loading' => 'Pre Loading',
                            'suhu_prestaging' => 'Prestaging',
                            'suhu_anteroom_ekspansi_abf' => 'Anteroom Ekspansi ABF',
                            'suhu_chillroom_rm' => 'Chillroom RM',
                            'suhu_chillroom_domestik' => 'Chillroom Domestik',
                        ];

                        $renderVal = function($val) {
                            if ($val === null || $val === '' || $val === []) return '-';
                            if (is_array($val)) {
                                $parts = [];
                                if (isset($val['setting'])) $parts[] = 'setting: ' . $val['setting'];
                                if (isset($val['display'])) $parts[] = 'display: ' . $val['display'];
                                if (isset($val['actual'])) $parts[] = 'actual: ' . $val['actual'];
                                return !empty($parts) ? implode('; ', $parts) : '-';
                            }
                            return (string) $val;
                        };

                        $firstHistory = $p->histories->sortBy('created_at')->first();
                        $initialTime = $p->created_at->format('d/m/Y H:i');

                        $getInitialVal = function($field) use ($firstHistory, $p) {
                            $oldField = $field . '_lama';
                            if ($firstHistory) {
                                $val = $firstHistory->$oldField;
                                if (is_string($val) && !empty($val)) {
                                    $decoded = json_decode($val, true);
                                    return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $val;
                                }
                                return $val;
                            }
                            return $p->$field;
                        };
                    @endphp

                    {{-- 1. Tampilkan Data Input Pertama (Initial State) --}}
                    <tr>
                        <td colspan="4" style="background: #f8f9fa; font-weight: bold; font-size: 9px; color: #555; text-align: center; border-bottom: 1px solid #dee2e6;">
                            --- INPUT DATA PERTAMA ---
                        </td>
                    </tr>
                    {{-- Tampilkan Pukul sebagai baris pertama --}}
                    <tr>
                        <td style="text-align: center;">{{ $histNo++ }}</td>
                        <td>Pukul</td>
                        <td style="background: #fff3cd; text-align: center;">-</td>
                        <td style="background: #d4edda;">{{ $p->created_at ? $p->created_at->format('H:i') : '-' }}</td>
                    </tr>
                    @foreach($suhuFieldsConfig as $field => $label)
                        @php $secData = $getInitialVal($field); @endphp
                        @if(!empty($secData))
                            @if(in_array($field, ['suhu_cold_storage', 'suhu_anteroom_loading']))
                                        @foreach($secData as $uKey => $item)
                                    <tr>
                                        <td style="text-align: center;">{{ $histNo++ }}</td>
                                        <td>{{ $label }} {{ $uKey }}</td>
                                        <td style="background: #fff3cd; text-align: center;">-</td>
                                        <td style="background: #d4edda;">{{ $renderVal($item) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td style="text-align: center;">{{ $histNo++ }}</td>
                                    <td>{{ $label }}</td>
                                    <td style="background: #fff3cd; text-align: center;">-</td>
                                    <td style="background: #d4edda;">{{ $renderVal($secData) }}</td>
                                </tr>
                            @endif
                        @endif
                    @endforeach

                    {{-- 2. Tampilkan Riwayat Perubahan (History) --}}
                    @if($p->relationLoaded('histories') && $p->histories && $p->histories->count() > 0)
                        <tr>
                            <td colspan="4" style="background: #f8f9fa; font-weight: bold; font-size: 9px; color: #c41e3a; text-align: center; border-top: 2px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                                --- RIWAYAT PERUBAHAN / UPDATE ---
                            </td>
                        </tr>
                        @foreach($p->histories->sortBy('created_at') as $history)
                            @php
                                $hTime = $history->created_at ? $history->created_at->format('d/m/Y H:i') : '-';
                                $changes = [];

                                // Pukul
                                if (($history->pukul_lama ?? null) != ($history->pukul_baru ?? null)) {
                                    $changes[] = ['lokasi' => 'Pukul', 'lama' => $history->pukul_lama ?? '(Kosong)', 'baru' => $history->pukul_baru ?? '(Kosong)'];
                                }

                                // Keterangan
                                if (($history->keterangan_lama ?? null) != ($history->keterangan_baru ?? null)) {
                                    $changes[] = ['lokasi' => 'Keterangan', 'lama' => $history->keterangan_lama ?? '(Kosong)', 'baru' => $history->keterangan_baru ?? '(Kosong)'];
                                }

                                // Tindakan Koreksi
                                if (($history->tindakan_koreksi_lama ?? null) != ($history->tindakan_koreksi_baru ?? null)) {
                                    $changes[] = ['lokasi' => 'Tindakan Koreksi', 'lama' => $history->tindakan_koreksi_lama ?? '(Kosong)', 'baru' => $history->tindakan_koreksi_baru ?? '(Kosong)'];
                                }

                                // Suhu Fields
                                foreach ($suhuFieldsConfig as $field => $label) {
                                    $oldField = $field . '_lama';
                                    $newField = $field . '_baru';
                                    $oldValue = $history->$oldField ?? null;
                                    $newValue = $history->$newField ?? null;

                                    if (is_string($oldValue) && !empty($oldValue)) {
                                        $decoded = json_decode($oldValue, true);
                                        $oldValue = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
                                    }
                                    if (is_string($newValue) && !empty($newValue)) {
                                        $decoded = json_decode($newValue, true);
                                        $newValue = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
                                    }
                                    
                                    if (is_object($oldValue)) { $oldValue = json_decode(json_encode($oldValue), true); }
                                    if (is_object($newValue)) { $newValue = json_decode(json_encode($newValue), true); }

                                    $oldArray = is_array($oldValue) ? $oldValue : [];
                                    $newArray = is_array($newValue) ? $newValue : [];

                                    if (empty($oldArray) && empty($newArray)) continue;

                                    if (in_array($field, ['suhu_cold_storage', 'suhu_anteroom_loading'])) {
                                        $allUnits = array_unique(array_merge(array_keys($oldArray), array_keys($newArray)));
                                        foreach ($allUnits as $uKey) {
                                            $oldItem = $oldArray[$uKey] ?? null;
                                            $newItem = $newArray[$uKey] ?? null;
                                            if (json_encode($oldItem) === json_encode($newItem)) continue;

                                            $changes[] = ['lokasi' => $label . ' ' . $uKey, 'lama' => $renderVal($oldItem), 'baru' => $renderVal($newItem)];
                                        }
                                    } else {
                                        if (json_encode($oldArray) !== json_encode($newArray)) {
                                            $changes[] = ['lokasi' => $label, 'lama' => $renderVal($oldArray), 'baru' => $renderVal($newArray)];
                                        }
                                    }
                                }
                            @endphp

                            @foreach($changes as $cIdx => $change)
                                <tr>
                                    <td style="text-align: center;">{{ $histNo++ }}</td>
                                    <td>{{ $change['lokasi'] }}</td>
                                    <td style="background: #fff3cd;">{{ $change['lama'] }}</td>
                                    <td style="background: #d4edda;">{{ $change['baru'] }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                </tbody>
            </table>

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
            
            <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
                QW 11/00
            </div>

            <div class="signature">
                <table class="signature-table">
                    <tr>
                        <td class="signature-cell">
                            <div class="signature-header-item">Dibuat Oleh</div>
                            <div class="signature-space">
                                @if($p->qcVerifier)
                                    @php
                                        $qcQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$p->qcVerifier->name} (Tim QC)";
                                        $qcQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($qcQrData);
                                        $base64QcSvg = "data:image/svg+xml;base64," . base64_encode($qcQrCodeSvg);
                                    @endphp
                                    <img src="{{ $base64QcSvg }}" class="qr-code-img" alt="QR Code QC">
                                @else
                                    <div class="signature-line-empty"></div>
                                @endif
                            </div>
                            <div class="signature-name">{{ $p->qcVerifier->name ?? '-' }}</div>
                        </td>
                        <td class="signature-cell">
                            <div class="signature-header-item">Diketahui Oleh</div>
                            <div class="signature-space">
                                @if($p->produksiVerifier)
                                    @php
                                        $prodQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$p->produksiVerifier->name} (Tim Warehouse)";
                                        $prodQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($prodQrData);
                                        $base64ProdSvg = "data:image/svg+xml;base64," . base64_encode($prodQrCodeSvg);
                                    @endphp
                                    <img src="{{ $base64ProdSvg }}" class="qr-code-img" alt="QR Code Warehouse">
                                @else
                                    <div class="signature-line-empty"></div>
                                @endif
                            </div>
                            <div class="signature-name">{{ $p->produksiVerifier->name ?? '-' }}</div>
                        </td>
                        <td class="signature-cell">
                            <div class="signature-header-item">Disetujui Oleh</div>
                            <div class="signature-space">
                                @if($p->spvVerifier)
                                    @php
                                        $spvQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$p->spvVerifier->name} (Tim Supervisor QC)";
                                        $spvQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($spvQrData);
                                        $base64SpvSvg = "data:image/svg+xml;base64," . base64_encode($spvQrCodeSvg);
                                    @endphp
                                    <img src="{{ $base64SpvSvg }}" class="qr-code-img" alt="QR Code SPV">
                                @else
                                    <div class="signature-line-empty"></div>
                                @endif
                            </div>
                            <div class="signature-name">{{ $p->spvVerifier->name ?? '-' }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            @if($idx < (count($pemeriksaans ?? []) - 1))
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
</body>
</html>
<!-- end -->