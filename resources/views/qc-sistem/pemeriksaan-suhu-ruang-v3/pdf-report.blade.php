<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Pemeriksaan Suhu Ruang V3</title>
    @php
        $firstRecord = $pemeriksaans->first();
        $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
    @endphp
    <style>
        @page { size: A4; margin: 12mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif; font-size: 9px; line-height: 1.4; color: #1a1a1a; }
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

        .temp-value { font-family: 'DejaVu Sans', sans-serif; }

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
        @php $isAllShift = $isAllShift ?? false; @endphp

        @if($isAllShift)
        {{-- ======= MODE: SEMUA SHIFT ======= --}}
        @if(empty($dataPerShift))
            <div style="text-align:center;padding:40px;color:#6c757d;font-style:italic;">Tidak ada data untuk semua shift.</div>
        @else
            @foreach($dataPerShift as $shiftGroup)
                @php $pemeriksaans = $shiftGroup['pemeriksaans']; $currentShift = $shiftGroup['shift']; @endphp
                <div style="background:#c41e3a;color:#fff;padding:6px 12px;border-radius:4px;margin-bottom:14px;page-break-inside:avoid;">
                    <strong style="font-size:11px;">{{ strtoupper($currentShift->shift) }}</strong>
                    @if(!empty($tanggal_dari) && !empty($tanggal_sampai))<span style="font-size:9px;margin-left:10px;">Periode: {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}</span>@endif
                    <span style="font-size:9px;margin-left:10px;">Total: {{ $pemeriksaans->count() }} data</span>
                </div>
                @foreach($pemeriksaans as $idx => $p)
                    @php $plantName = $p->user && $p->user->plant ? $p->user->plant->plant : '-'; @endphp
                    {{-- Header CPI --}}
                    <div class="header">
                        <div class="header-left"><div class="logo-company"><div class="header-logo"><img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo"></div><div class="header-company"><h2>PT. CHAROEN POKPHAND INDONESIA</h2><p>FOOD DIVISION {{ strtoupper($plantName) }}</p><p>{{ strtoupper($plantName) }} - INDONESIA</p></div></div></div>
                        <div class="header-right"><div class="header-title"><h1>PEMERIKSAAN SUHU RUANG (GUDANG DRY)</h1></div></div>
                    </div>
                    {{-- The body content for each record (same as single mode) --}}
                    @php
                        $plantName = $p->user && $p->user->plant ? ($p->user->plant->plant ?? '-') : '-';
                        $shiftNameAS = $p->shift ? $p->shift->shift : $currentShift->shift;
                        $jamPukulAS = null;
                        if (!empty($p->pukul)) { try { $jamPukulAS = \Carbon\Carbon::parse($p->pukul)->format('H:i'); } catch (\Throwable $e) { $jamPukulAS = (string)$p->pukul; } }
                        $suhuAS = is_array($p->suhu_data) ? $p->suhu_data : (json_decode($p->suhu_data ?? '[]', true) ?: []);
                        $historiesAS = $p->relationLoaded('histories') && $p->histories ? $p->histories : collect();
                        $firstHistoryAS = $historiesAS->sortBy('created_at')->first();
                        $initialDataAS = $firstHistoryAS ? (is_array($firstHistoryAS->suhu_data_lama) ? $firstHistoryAS->suhu_data_lama : (json_decode($firstHistoryAS->suhu_data_lama ?? '[]', true) ?: [])) : $suhuAS;
                        $initialPukulAS = '-';
                        if ($firstHistoryAS && isset($firstHistoryAS->pukul_lama)) { $initialPukulAS = $firstHistoryAS->pukul_lama; } elseif ($p->pukul) { $initialPukulAS = $p->pukul; }
                        $histNoAS = 1;
                        $sectionLabelsAS = ['suhu_premix'=>'Premix','suhu_seasoning'=>'Seasoning','suhu_dry'=>'Dry','suhu_cassing'=>'Cassing','suhu_beef'=>'Beef','suhu_packaging'=>'Packaging','suhu_ruang_chemical'=>'Ruang Chemical','suhu_ruang_seasoning'=>'Ruang Seasoning'];
                        $renderValAS = function($val) { 
                            if ($val===null||$val===''||$val===[]) return '-'; 
                            if (is_array($val)) { 
                                $parts=[]; 
                                if(isset($val['setting']) && ($val['setting'] !== '' && $val['setting'] !== null)) { 
                                    $setting = (string)$val['setting'];
                                    // Replace special characters - make sure we don't double replace
                                    $setting = str_replace(['≤', '<='], '≤', $setting);
                                    $setting = str_replace(['≥', '>='], '≥', $setting);
                                    $parts[]='Setting: '.$setting; 
                                } 
                                if(isset($val['display']) && ($val['display'] !== '' && $val['display'] !== null)) { 
                                    $display = (string)$val['display'];
                                    $display = str_replace(['≤', '<='], '≤', $display);
                                    $display = str_replace(['≥', '>='], '≥', $display);
                                    $parts[]='Display: '.$display; 
                                } 
                                if(isset($val['actual']) && ($val['actual'] !== '' && $val['actual'] !== null)) { 
                                    $actual = (string)$val['actual'];
                                    $actual = str_replace(['≤', '<='], '≤', $actual);
                                    $actual = str_replace(['≥', '>='], '≥', $actual);
                                    $parts[]='Actual: '.$actual; 
                                } 
                                return !empty($parts)?implode('; ',$parts):'-'; 
                            } 
                            return (string)$val; 
                        };
                        $findUnitAS = function($rows,$unit) { if(!is_array($rows)) return null; foreach($rows as $r) { if(!is_array($r)) continue; if((string)($r['unit']??'')===(string)$unit) return $r; } return null; };
                    @endphp
                    <div class="subheader"><table class="subheader-table">
                        <tr><td class="subheader-label">Tanggal</td><td>{{ $p->tanggal ? $p->tanggal->format('d/m/Y') : '-' }}</td><td class="subheader-label">Shift</td><td>{{ $shiftNameAS }}</td></tr>
                        <tr><td class="subheader-label">Plant</td><td>{{ $plantName }}</td><td class="subheader-label">Pukul</td><td>{{ $jamPukulAS ?? '-' }}</td></tr>
                    </table></div>
                    <div class="section-title">Data & Riwayat Pemeriksaan</div>
                    <table class="data"><thead><tr><th style="width:5%">No</th><th style="width:25%">Lokasi</th><th style="width:35%">Sebelumnya</th><th style="width:35%">Sesudahnya</th></tr></thead><tbody>
                        <tr><td colspan="4" style="background:#f8f9fa;font-weight:bold;font-size:9px;color:#555;text-align:center;">--- INPUT DATA PERTAMA ---</td></tr>
                        @if($initialPukulAS && $initialPukulAS!=='-')<tr><td></td><td>Pukul</td><td style="background:#fff3cd;text-align:center;">-</td><td style="background:#d4edda;">{{ $initialPukulAS }}</td></tr>@endif
                        @foreach($sectionLabelsAS as $fieldBase => $secLabel)
                            @php
                                // V3: data stored in separate columns like $p->suhu_premix
                                $colData = $p->$fieldBase ?? null;
                                if (is_string($colData)) { $colData = json_decode($colData, true); }
                            @endphp
                            @if(!empty($colData) && is_array($colData))
                                @foreach($colData as $uKey => $item)
                                    @if(!empty($item['setting']) || !empty($item['display']) || !empty($item['actual']))
                                        <tr><td style="text-align:center;">{{ $histNoAS++ }}</td><td>{{ $secLabel }} {{ str_replace('unit_', '', (string)$uKey) }}</td><td style="background:#fff3cd;text-align:center;">-</td><td style="background:#d4edda;" class="temp-value">{{ $renderValAS($item) }}</td></tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        @if($historiesAS->count()>0)
                            <tr><td colspan="4" style="background:#f8f9fa;font-weight:bold;font-size:9px;color:#c41e3a;text-align:center;border-top:2px solid #dee2e6;">--- RIWAYAT PERUBAHAN ---</td></tr>
                            @foreach($historiesAS->sortBy('created_at') as $history)
                                @php
                                    $lama=is_array($history->suhu_data_lama)?$history->suhu_data_lama:(json_decode($history->suhu_data_lama??'[]',true)?:[]);
                                    $baru=is_array($history->suhu_data_baru)?$history->suhu_data_baru:(json_decode($history->suhu_data_baru??'[]',true)?:[]);
                                    $changesAS=[];
                                    if(($history->keterangan_lama??null)!=($history->keterangan_baru??null)) $changesAS[]=['lokasi'=>'Keterangan','lama'=>$history->keterangan_lama??'(Kosong)','baru'=>$history->keterangan_baru??'(Kosong)'];
                                    if(($history->tindakan_koreksi_lama??null)!=($history->tindakan_koreksi_baru??null)) $changesAS[]=['lokasi'=>'Tindakan Koreksi','lama'=>$history->tindakan_koreksi_lama??'(Kosong)','baru'=>$history->tindakan_koreksi_baru??'(Kosong)'];
                                    foreach($sectionLabelsAS as $secKey=>$secLabel){
                                        $os=$lama[$secKey]??[]; $ns=$baru[$secKey]??[];
                                        if(in_array($secKey,['cold_storage','anteroom_loading'])){
                                            $aus=array_unique(array_merge(array_map(function($r){return is_array($r)?(string)($r['unit']??''):'';}, (array)$os),array_map(function($r){return is_array($r)?(string)($r['unit']??''):'';}, (array)$ns)));
                                            foreach($aus as $u){$oi=$findUnitAS($os,$u);$ni=$findUnitAS($ns,$u);if(json_encode($oi)!==json_encode($ni))$changesAS[]=['lokasi'=>$secLabel.' '.$u,'lama'=>$renderValAS($oi),'baru'=>$renderValAS($ni)];}
                                        } else { if(json_encode($os)!==json_encode($ns)) $changesAS[]=['lokasi'=>$secLabel,'lama'=>$renderValAS($os),'baru'=>$renderValAS($ns)]; }
                                    }
                                @endphp
                                @foreach($changesAS as $change)<tr><td style="text-align:center;">{{ strcasecmp($change['lokasi'],'pukul')===0?'':$histNoAS++ }}</td><td>{{ $change['lokasi'] }}</td><td style="background:#fff3cd;" class="temp-value">{{ $change['lama'] }}</td><td style="background:#d4edda;" class="temp-value">{{ $change['baru'] }}</td></tr>@endforeach
                            @endforeach
                        @endif
                    </tbody></table>
                    @if(!empty($p->keterangan)||!empty($p->tindakan_koreksi))
                        <div class="section-title">Catatan</div>
                        <table class="data"><tbody>
                            @if(!empty($p->keterangan))<tr><td style="width:25%"><strong>Keterangan</strong></td><td>{{ $p->keterangan }}</td></tr>@endif
                            @if(!empty($p->tindakan_koreksi))<tr><td style="width:25%"><strong>Tindakan Koreksi</strong></td><td>{{ $p->tindakan_koreksi }}</td></tr>@endif
                        </tbody></table>
                    @endif
                    <div style="text-align:right;font-style:italic;font-size:9px;color:#666;margin-top:5px;">QW 11/00</div>
                    <div class="signature"><table class="signature-table"><tr>
                        <td class="signature-cell"><div class="signature-header-item">Dibuat Oleh</div><div class="signature-space">@if($p->verifiedByQc||$p->user) @php $q=\SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate("Diverifikasi ".($p->verifiedByQc->name??$p->user->name??'')); @endphp <img src="data:image/svg+xml;base64,{{ base64_encode($q) }}" class="qr-code-img">@else<div class="signature-line-empty"></div>@endif</div><div class="signature-name">{{ $p->verifiedByQc->name??$p->user->name??'-' }}</div></td>
                        <td class="signature-cell"><div class="signature-header-item">Diketahui Oleh</div><div class="signature-space">@if($p->verifiedByProduksi) @php $q=\SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate("Diverifikasi ".$p->verifiedByProduksi->name); @endphp <img src="data:image/svg+xml;base64,{{ base64_encode($q) }}" class="qr-code-img">@else<div class="signature-line-empty"></div>@endif</div><div class="signature-name">{{ $p->verifiedByProduksi->name??'-' }}</div></td>
                        <td class="signature-cell"><div class="signature-header-item">Disetujui Oleh</div><div class="signature-space">@if($p->verifiedBySpv) @php $q=\SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate("Diverifikasi ".$p->verifiedBySpv->name); @endphp <img src="data:image/svg+xml;base64,{{ base64_encode($q) }}" class="qr-code-img">@else<div class="signature-line-empty"></div>@endif</div><div class="signature-name">{{ $p->verifiedBySpv->name??'-' }}</div></td>
                    </tr></table></div>
                    @if(!$loop->last || !$loop->parent->last)<div class="page-break"></div>@endif
                @endforeach
                @if(!$loop->last)<div class="page-break"></div>@endif
            @endforeach
        @endif

        @else
        {{-- ======= MODE: SHIFT TUNGGAL ======= --}}
        @foreach(($pemeriksaans ?? []) as $idx => $p)
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
                        <h1>PEMERIKSAAN SUHU RUANG GUDANG DRY</h1>
                    </div>
                </div>
            </div>
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
                        if ($hasSetting) { echo '<td class="temp-value">' . ((string) ($r['setting'] ?? '')) . '</td>'; }
                        if ($hasDisplay) { echo '<td class="temp-value">' . ((string) ($r['display'] ?? '')) . '</td>'; }
                        if ($hasActual) { echo '<td class="temp-value">' . ((string) ($r['actual'] ?? '')) . '</td>'; }
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
                            'suhu_premix' => 'Premix',
                            'suhu_seasoning' => 'Seasoning',
                            'suhu_dry' => 'Dry',
                            'suhu_cassing' => 'Cassing',
                            'suhu_beef' => 'Beef',
                            'suhu_packaging' => 'Packaging',
                            'suhu_ruang_chemical' => 'Ruang Chemical',
                            'suhu_ruang_seasoning' => 'Ruang Seasoning',
                        ];

                        $renderVal = function($val) {
                            if ($val === null || $val === '' || $val === []) return '-';
                            if (is_array($val)) {
                                $parts = [];
                                if (isset($val['setting']) && ($val['setting'] !== '' && $val['setting'] !== null)) {
                                    $setting = (string)$val['setting'];
                                    $setting = str_replace(['≤', '<='], '≤', $setting);
                                    $setting = str_replace(['≥', '>='], '≥', $setting);
                                    $parts[] = 'Setting: ' . $setting;
                                }
                                if (isset($val['display']) && ($val['display'] !== '' && $val['display'] !== null)) {
                                    $display = (string)$val['display'];
                                    $display = str_replace(['≤', '<='], '≤', $display);
                                    $display = str_replace(['≥', '>='], '≥', $display);
                                    $parts[] = 'Display: ' . $display;
                                }
                                if (isset($val['actual']) && ($val['actual'] !== '' && $val['actual'] !== null)) {
                                    $actual = (string)$val['actual'];
                                    $actual = str_replace(['≤', '<='], '≤', $actual);
                                    $actual = str_replace(['≥', '>='], '≥', $actual);
                                    $parts[] = 'Actual: ' . $actual;
                                }
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

                        // Ambil nilai Pukul dari history pertama
                        $initialPukul = '-';
                        if ($firstHistory && isset($firstHistory->pukul_lama)) {
                            $initialPukul = $firstHistory->pukul_lama;
                        } elseif ($p->pukul) {
                            $initialPukul = $p->pukul;
                        }
                    @endphp

                    {{-- 1. Tampilkan Data Input Pertama (Initial State) --}}
                    <tr>
                        <td colspan="4" style="background: #f8f9fa; font-weight: bold; font-size: 9px; color: #555; text-align: center; border-bottom: 1px solid #dee2e6;">
                            --- INPUT DATA PERTAMA ---
                        </td>
                    </tr>
                    {{-- Baris Pukul untuk Input Pertama --}}
                    @if($initialPukul && $initialPukul !== '-')
                        <tr>
                            <td style="text-align: center;"></td>
                            <td>Pukul</td>
                            <td style="background: #fff3cd; text-align: center;">-</td>
                            <td style="background: #d4edda;">{{ $initialPukul }}</td>
                        </tr>
                    @endif

                    @foreach($suhuFieldsConfig as $field => $label)
                        @php $secData = $getInitialVal($field); @endphp
                        @if(!empty($secData))
                            @foreach($secData as $uKey => $item)
                                <tr>
                                    <td style="text-align: center;">{{ $histNo++ }}</td>
                                    <td>{{ $label }} {{ str_replace('unit_', '', (string) $uKey) }}</td>
                                    <td style="background: #fff3cd; text-align: center;">-</td>
                                    <td style="background: #d4edda;" class="temp-value">{{ $renderVal($item) }}</td>
                                </tr>
                            @endforeach
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

                                    if (!is_array($oldValue) && !is_array($newValue)) continue;

                                    $oldArray = is_array($oldValue) ? $oldValue : [];
                                    $newArray = is_array($newValue) ? $newValue : [];

                                    $allKeys = array_unique(array_merge(array_keys($oldArray), array_keys($newArray)));
                                    foreach ($allKeys as $key) {
                                        $oldItem = $oldArray[$key] ?? null;
                                        $newItem = $newArray[$key] ?? null;
                                        if (json_encode($oldItem) === json_encode($newItem)) continue;

                                        $unitDisplay = str_replace('unit_', '', (string) $key);
                                        $changes[] = ['lokasi' => $label . ' ' . $unitDisplay, 'lama' => $renderVal($oldItem), 'baru' => $renderVal($newItem)];
                                    }
                                }
                            @endphp

                            @foreach($changes as $cIdx => $change)
                                @php
                                    $isPukul = (strcasecmp($change['lokasi'] ?? '', 'pukul') === 0);
                                @endphp
                                <tr>
                                    <td style="text-align: center;">{{ $isPukul ? '' : $histNo++ }}</td>
                                    <td>{{ $change['lokasi'] }}</td>
                                    <td style="background: #fff3cd;" class="temp-value">{{ $change['lama'] }}</td>
                                    <td style="background: #d4edda;" class="temp-value">{{ $change['baru'] }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
                QW 11/00
            </div>
            
            <div class="signature">
                <table class="signature-table">
                    <tr>
                        <td class="signature-cell">
                            <div class="signature-header-item">Dibuat Oleh</div>
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
                            <div class="signature-header-item">Diketahui Oleh</div>
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
                            <div class="signature-header-item">Disetujui Oleh</div>
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

            @if($idx < (count($pemeriksaans ?? []) - 1))
                <div class="page-break"></div>
            @endif
        @endforeach
        @endif {{-- end isAllShift --}}
    </div>
</body>
</html>
