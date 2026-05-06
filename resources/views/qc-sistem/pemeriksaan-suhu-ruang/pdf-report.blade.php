<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Suhu Ruang</title>
    @php
        $firstRecord = $pemeriksaans->first();
        $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
    @endphp
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
                        <h1>PEMERIKSAAN SUHU RUANG FOOD PROCESSING</h1>
                    </div>
                </div>
            </div>
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
            
            {{-- Data Pemeriksaan Terkini --}}
            <!-- @if(!empty($suhu['cold_storage'] ?? []))
                <div class="section-title">Cold Storage</div>
                <table class="data">
                    <thead>
                        <tr>
                            <th style="width: 25%">Unit</th>
                            <th>Setting (°C)</th>
                            <th>Display (°C)</th>
                            <th>Actual (°C)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suhu['cold_storage'] as $item)
                            <tr>
                                <td>CS {{ $item['unit'] }}</td>
                                <td>{{ $item['setting'] ?? '-' }}</td>
                                <td>{{ $item['display'] ?? '-' }}</td>
                                <td>{{ $item['actual'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if(!empty($suhu['anteroom_loading'] ?? []))
                <div class="section-title">Anteroom Loading</div>
                <table class="data">
                    <thead>
                        <tr>
                            <th style="width: 25%">Unit</th>
                            <th>Setting (°C)</th>
                            <th>Display (°C)</th>
                            <th>Actual (°C)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suhu['anteroom_loading'] as $item)
                            <tr>
                                <td>Anteroom Loading {{ $item['unit'] }}</td>
                                <td>{{ $item['setting'] ?? '-' }}</td>
                                <td>{{ $item['display'] ?? '-' }}</td>
                                <td>{{ $item['actual'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @php
                $singleAreas = [
                    'pre_loading' => 'Pre Loading',
                    'prestaging' => 'Prestaging',
                    'anteroom_ekspansi_further' => 'Anteroom Ekspansi Further',
                    'anteroom_ekspansi_sausage' => 'Anteroom Ekspansi Sausage',
                ];
            @endphp

            @foreach($singleAreas as $key => $label)
                @if(!empty($suhu[$key] ?? []))
                    <div class="section-title">{{ $label }}</div>
                    <table class="data">
                        <thead>
                            <tr>
                                <th>Setting (°C)</th>
                                <th>Display (°C)</th>
                                @if(isset($suhu[$key]['actual']))
                                    <th>Actual (°C)</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $suhu[$key]['setting'] ?? '-' }}</td>
                                <td>{{ $suhu[$key]['display'] ?? '-' }}</td>
                                @if(isset($suhu[$key]['actual']))
                                    <td>{{ $suhu[$key]['actual'] ?? '-' }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                @endif
            @endforeach -->

            {{-- Data & Riwayat Pemeriksaan Table --}}
            <div class="section-title">Data & Riwayat Pemeriksaan</div>
            <table class="data">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 14%">Waktu</th>
                        <th style="width: 21%">Lokasi</th>
                        <th style="width: 30%">Sebelumnya</th>
                        <th style="width: 30%">Sesudahnya</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $histNo = 1;
                        $sectionLabels = [
                            'cold_storage' => 'Cold Storage',
                            'anteroom_loading' => 'Anteroom Loading',
                            'pre_loading' => 'Pre Loading',
                            'prestaging' => 'Prestaging',
                            'anteroom_ekspansi_further' => 'Anteroom Ekspansi Further',
                            'anteroom_ekspansi_sausage' => 'Anteroom Ekspansi Sausage',
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

                        $findUnitRowFn = function ($rows, $unit) {
                            if (!is_array($rows)) return null;
                            foreach ($rows as $r) {
                                if (!is_array($r)) continue;
                                if ((string) ($r['unit'] ?? '') === (string) $unit) return $r;
                            }
                            return null;
                        };

                        $firstHistory = $p->histories->sortBy('created_at')->first();
                        $initialData = $firstHistory ? 
                            (is_array($firstHistory->suhu_data_lama) ? $firstHistory->suhu_data_lama : (json_decode($firstHistory->suhu_data_lama ?? '[]', true) ?: [])) : 
                            $suhu;
                        $initialTime = $p->created_at->format('d/m/Y H:i');
                    @endphp

                    {{-- 1. Tampilkan Data Input Pertama (Initial State) --}}
                    <tr>
                        <td colspan="5" style="background: #f8f9fa; font-weight: bold; font-size: 9px; color: #555; text-align: center; border-bottom: 1px solid #dee2e6;">
                            --- INPUT DATA PERTAMA ---
                        </td>
                    </tr>
                    @foreach($sectionLabels as $secKey => $secLabel)
                        @php $secData = $initialData[$secKey] ?? []; @endphp
                        @if(!empty($secData))
                            @if(in_array($secKey, ['cold_storage', 'anteroom_loading']))
                                @foreach($secData as $item)
                                    <tr>
                                        <td style="text-align: center;">{{ $histNo++ }}</td>
                                        <td>{{ $initialTime }}</td>
                                        <td>{{ $secLabel }} {{ $item['unit'] ?? '' }}</td>
                                        <td style="background: #fff3cd; text-align: center;">-</td>
                                        <td style="background: #d4edda;">{{ $renderVal($item) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td style="text-align: center;">{{ $histNo++ }}</td>
                                    <td>{{ $initialTime }}</td>
                                    <td>{{ $secLabel }}</td>
                                    <td style="background: #fff3cd; text-align: center;">-</td>
                                    <td style="background: #d4edda;">{{ $renderVal($secData) }}</td>
                                </tr>
                            @endif
                        @endif
                    @endforeach

                    {{-- 2. Tampilkan Riwayat Perubahan (History) --}}
                    @if($p->relationLoaded('histories') && $p->histories && $p->histories->count() > 0)
                        <tr>
                            <td colspan="5" style="background: #f8f9fa; font-weight: bold; font-size: 9px; color: #c41e3a; text-align: center; border-top: 2px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                                --- RIWAYAT PERUBAHAN / UPDATE ---
                            </td>
                        </tr>
                        @foreach($p->histories->sortBy('created_at') as $history)
                            @php
                                $hTime = $history->created_at ? $history->created_at->format('d/m/Y H:i') : '-';
                                $changes = [];

                                $lama = is_array($history->suhu_data_lama) ? $history->suhu_data_lama : (json_decode($history->suhu_data_lama ?? '[]', true) ?: []);
                                $baru = is_array($history->suhu_data_baru) ? $history->suhu_data_baru : (json_decode($history->suhu_data_baru ?? '[]', true) ?: []);

                                // Keterangan
                                if (($history->keterangan_lama ?? null) != ($history->keterangan_baru ?? null)) {
                                    $changes[] = ['lokasi' => 'Keterangan', 'lama' => $history->keterangan_lama ?? '(Kosong)', 'baru' => $history->keterangan_baru ?? '(Kosong)'];
                                }

                                // Tindakan Koreksi
                                if (($history->tindakan_koreksi_lama ?? null) != ($history->tindakan_koreksi_baru ?? null)) {
                                    $changes[] = ['lokasi' => 'Tindakan Koreksi', 'lama' => $history->tindakan_koreksi_lama ?? '(Kosong)', 'baru' => $history->tindakan_koreksi_baru ?? '(Kosong)'];
                                }

                                // Suhu sections
                                foreach ($sectionLabels as $secKey => $secLabel) {
                                    $oldSection = $lama[$secKey] ?? [];
                                    $newSection = $baru[$secKey] ?? [];

                                    if (in_array($secKey, ['cold_storage', 'anteroom_loading'])) {
                                        $allUnits = [];
                                        foreach ((array) $oldSection as $r) { if (is_array($r) && isset($r['unit'])) $allUnits[] = (string) $r['unit']; }
                                        foreach ((array) $newSection as $r) { if (is_array($r) && isset($r['unit'])) $allUnits[] = (string) $r['unit']; }
                                        $allUnits = array_unique($allUnits);

                                        foreach ($allUnits as $u) {
                                            $oldItem = $findUnitRowFn($oldSection, $u);
                                            $newItem = $findUnitRowFn($newSection, $u);
                                            if (json_encode($oldItem) === json_encode($newItem)) continue;

                                            $changes[] = ['lokasi' => $secLabel . ' ' . $u, 'lama' => $renderVal($oldItem), 'baru' => $renderVal($newItem)];
                                        }
                                    } else {
                                        if (json_encode($oldSection) !== json_encode($newSection)) {
                                            $changes[] = ['lokasi' => $secLabel, 'lama' => $renderVal($oldSection), 'baru' => $renderVal($newSection)];
                                        }
                                    }
                                }
                            @endphp

                            @foreach($changes as $cIdx => $change)
                                <tr>
                                    <td style="text-align: center;">{{ $histNo++ }}</td>
                                    <td>{{ $cIdx === 0 ? $hTime : '' }}</td>
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
                <div class="section-title">Catatan</div>
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
