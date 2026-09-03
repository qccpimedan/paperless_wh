<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="font-family: Arial, sans-serif; font-size: 10pt; color: #1a1a1a;">

@php
    $isAllShift = $isAllShift ?? false;

    if ($isAllShift && !empty($dataPerShift)) {
        $allRecords = collect();
        foreach ($dataPerShift as $group) {
            $allRecords = $allRecords->merge($group['pemeriksaans'] ?? []);
        }
    } else {
        $allRecords = collect($pemeriksaans ?? []);
    }

    $firstRecord = $allRecords->first();
    $plantName = ($firstRecord && $firstRecord->user && $firstRecord->user->plant)
        ? ($firstRecord->user->plant->plant ?? 'MEDAN')
        : 'MEDAN';

    $tanggalMin = $allRecords->min(fn ($p) => $p->tanggal);
    $tanggalMax = $allRecords->max(fn ($p) => $p->tanggal);
    $periodeStr = $tanggalMin && $tanggalMax
        ? ($tanggalMin->format('d/m/Y') . ' - ' . $tanggalMax->format('d/m/Y'))
        : '-';

    $sectionDefsV2 = [
        'suhu_cold_storage'          => ['label' => 'Cold Storage', 'type' => 'multi'],
        'suhu_anteroom_loading'      => ['label' => 'Anteroom Loading', 'type' => 'multi'],
        'suhu_pre_loading'           => ['label' => 'Pre Loading', 'type' => 'single'],
        'suhu_prestaging'            => ['label' => 'Prestaging', 'type' => 'single'],
        'suhu_anteroom_ekspansi_abf' => ['label' => 'Anteroom Ekspansi ABF', 'type' => 'single'],
        'suhu_chillroom_rm'          => ['label' => 'Chillroom RM', 'type' => 'single'],
        'suhu_chillroom_domestik'    => ['label' => 'Chillroom Domestik', 'type' => 'single'],
    ];

    $pickValue = function ($val, $key) {
        if ($val === null || $val === '' || $val === []) return '-';
        if (is_array($val)) return $val[$key] ?? '-';
        return (string) $val;
    };

    $rows = [];
    $no = 1;

    $isSameV2 = fn ($a, $b) => json_encode($a) === json_encode($b);

    $findUnitRowV2 = function ($rowsData, $unit) {
        if (!is_array($rowsData)) return null;
        foreach ($rowsData as $uKey => $r) {
            if (is_array($r) && ((string)($r['unit'] ?? '') === (string)$unit || (string)$uKey === (string)$unit)) {
                return $r;
            }
        }
        return null;
    };

    foreach ($allRecords as $p) {
        $tanggalStr = $p->tanggal ? $p->tanggal->format('d/m/Y') : '-';
        $shiftStr = $p->shift->shift ?? '-';
        $qcStr = $p->qcVerifier->name ?? ($p->user->name ?? '-');
        $groupStr = optional(optional($p->user)->group)->name ?? '-';

        $produkName = $p->produk ? ($p->produk->nama_produk ?? '-') : '-';
        $kategori = $p->produk ? ($p->produk->kategori_code ?? null) : null;
        $produkStr = $kategori ? "{$kategori} - {$produkName}" : $produkName;
        $suhuProdukStr = $p->suhu_produk ?? '-';

        $histories = ($p->relationLoaded('histories') && $p->histories) ? $p->histories->sortBy('created_at') : collect();
        $firstHistory = $histories->first();

        // 1. Ambil Data Input Pertama (Initial State)
        $initialSuhuProduk = ($firstHistory && !empty($firstHistory->suhu_produk_lama)) 
            ? $firstHistory->suhu_produk_lama 
            : $suhuProdukStr;

        $initialTime = '-';
        if ($firstHistory && isset($firstHistory->pukul_lama) && !empty($firstHistory->pukul_lama)) {
            $initialTime = $firstHistory->pukul_lama;
        } elseif ($firstHistory && $firstHistory->created_at) {
            $initialTime = $firstHistory->created_at->format('H:i');
        } elseif (!empty($p->pukul)) {
            try {
                $initialTime = \Carbon\Carbon::parse($p->pukul)->format('H:i');
            } catch (\Throwable $e) {
                $initialTime = (string) $p->pukul;
            }
        } elseif ($p->created_at) {
            $initialTime = $p->created_at->format('H:i');
        }

        foreach ($sectionDefsV2 as $fieldKey => $secDef) {
            $initialFieldLamaKey = $fieldKey . '_lama';
            $valData = ($firstHistory && isset($firstHistory->$initialFieldLamaKey)) 
                ? $firstHistory->$initialFieldLamaKey 
                : ($p->$fieldKey ?? null);

            if (is_string($valData) && !empty($valData)) {
                $valData = json_decode($valData, true);
            }
            if (empty($valData)) continue;

            if ($secDef['type'] === 'multi' && is_array($valData)) {
                foreach ($valData as $uKey => $item) {
                    if (!is_array($item)) continue;
                    $unitName = $item['unit'] ?? (is_string($uKey) ? $uKey : '');
                    $rows[] = [
                        'no' => $no++,
                        'tanggal' => $tanggalStr,
                        'shift' => $shiftStr,
                        'time' => $initialTime,
                        'qc' => $qcStr,
                        'group' => $groupStr,
                        'produk' => $produkStr,
                        'suhu_produk' => $initialSuhuProduk,
                        'area' => trim($secDef['label'] . ' ' . $unitName),
                        'setting' => $pickValue($item, 'setting'),
                        'aktual' => $pickValue($item, 'actual'),
                        'display' => $pickValue($item, 'display'),
                    ];
                }
            } else {
                $rows[] = [
                    'no' => $no++,
                    'tanggal' => $tanggalStr,
                    'shift' => $shiftStr,
                    'time' => $initialTime,
                    'qc' => $qcStr,
                    'group' => $groupStr,
                    'produk' => $produkStr,
                    'suhu_produk' => $initialSuhuProduk,
                    'area' => $secDef['label'],
                    'setting' => $pickValue($valData, 'setting'),
                    'aktual' => $pickValue($valData, 'actual'),
                    'display' => $pickValue($valData, 'display'),
                ];
            }
        }

        // 2. Render baris untuk Riwayat Perubahan (Edit Per 2 Jam) jika ada
        foreach ($histories as $history) {
            $hJam = '-';
            if (!empty($history->pukul_baru)) {
                $hJam = $history->pukul_baru;
            } elseif (!empty($history->pukul_lama)) {
                $hJam = $history->pukul_lama;
            } elseif ($history->created_at) {
                $hJam = $history->created_at->format('H:i');
            }

            $hSuhuProduk = !empty($history->suhu_produk_baru) ? $history->suhu_produk_baru : $suhuProdukStr;
            $userQcName = $history->user ? $history->user->name : $qcStr;

            foreach ($sectionDefsV2 as $fieldKey => $secDef) {
                $lamaKey = $fieldKey . '_lama';
                $baruKey = $fieldKey . '_baru';

                $lama = $history->$lamaKey ?? [];
                $baru = $history->$baruKey ?? [];

                if (is_string($lama)) $lama = json_decode($lama, true) ?: [];
                if (is_string($baru)) $baru = json_decode($baru, true) ?: [];

                if ($secDef['type'] === 'multi') {
                    $allUnits = [];
                    foreach ((array) $lama as $uKey => $r) {
                        if (is_array($r)) $allUnits[] = (string) ($r['unit'] ?? $uKey);
                    }
                    foreach ((array) $baru as $uKey => $r) {
                        if (is_array($r)) $allUnits[] = (string) ($r['unit'] ?? $uKey);
                    }
                    $allUnits = array_unique($allUnits);

                    foreach ($allUnits as $u) {
                        $oldItem = $findUnitRowV2($lama, $u);
                        $newItem = $findUnitRowV2($baru, $u);

                        if (!$isSameV2($oldItem, $newItem) && !empty($newItem)) {
                            $rows[] = [
                                'no' => $no++,
                                'tanggal' => $tanggalStr,
                                'shift' => $shiftStr,
                                'time' => $hJam,
                                'qc' => $userQcName,
                                'group' => $groupStr,
                                'produk' => $produkStr,
                                'suhu_produk' => $hSuhuProduk,
                                'area' => trim($secDef['label'] . ' ' . $u),
                                'setting' => $pickValue($newItem, 'setting'),
                                'aktual' => $pickValue($newItem, 'actual'),
                                'display' => $pickValue($newItem, 'display'),
                            ];
                        }
                    }
                } else {
                    if (!$isSameV2($lama, $baru) && !empty($baru)) {
                        $rows[] = [
                            'no' => $no++,
                            'tanggal' => $tanggalStr,
                            'shift' => $shiftStr,
                            'time' => $hJam,
                            'qc' => $userQcName,
                            'group' => $groupStr,
                            'produk' => $produkStr,
                            'suhu_produk' => $hSuhuProduk,
                            'area' => $secDef['label'],
                            'setting' => $pickValue($baru, 'setting'),
                            'aktual' => $pickValue($baru, 'actual'),
                            'display' => $pickValue($baru, 'display'),
                        ];
                    }
                }
            }
        }
    }

    $lastRecord = $allRecords->last();
    $logoPath = public_path('dist/images/logo/cpi-logo.png');
    $logoExists = file_exists($logoPath);
@endphp

    {{-- ======== JUDUL / HEADER ======== --}}
    <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
        <tr>
            @if($logoExists)
            <td colspan="1" style="width:55px; text-align:center; vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:6px;">
                <img src="{{ $logoPath }}" width="42" height="42" style="display:block; margin:0 auto;" alt="Logo CPI">
            </td>
            @endif
            <td colspan="{{ $logoExists ? 5 : 6 }}" style="vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:10px 14px;">
                <span style="font-size:12pt; font-weight:bold; color:#c41e3a; letter-spacing:0.5px;">PT. CHAROEN POKPHAND INDONESIA</span><br>
                <span style="font-size:9pt; color:#555555;">FOOD DIVISION {{ strtoupper($plantName) }}</span><br>
                <span style="font-size:9pt; color:#555555;">{{ strtoupper($plantName) }} - INDONESIA</span>
            </td>
            <td colspan="5" style="text-align:center; vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:10px 14px;">
                <span style="font-size:12pt; font-weight:bold; color:#1a1a1a; letter-spacing:0.5px;">PEMERIKSAAN SUHU PRODUK DAN SUHU RUANG PENYIMPANAN</span>
            </td>
        </tr>
    </table>

    {{-- ======== SUBHEADER ======== --}}
    <table style="width:100%; border-collapse:collapse; border:1px solid #adb5bd; margin-bottom:12px;">
        <tbody>
            <tr>
                <td colspan="2" style="font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Plant</td>
                <td colspan="4" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ $plantName }}</td>
                <td colspan="2" style="font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Periode</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ $periodeStr }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Total Baris</td>
                <td colspan="9" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ count($rows) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- ======== TABEL DATA REKAP ======== --}}
    <p style="font-weight:bold; font-size:10pt; color:#c41e3a; margin:10px 0 5px;">Data Pemeriksaan Suhu Ruang</p>
    <table style="width:100%; border-collapse:collapse; border:1px solid #adb5bd;">
        <thead>
            <tr>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:center;">No</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:center;">Tanggal</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:center;">Shift</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:center;">Time</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:left;">QC</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:left;">Produk</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:center;">Suhu Produk (&deg;C)</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:left;">Area</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:center;">Setting Suhu Ruang (&deg;C)</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:center;">Aktual Suhu Ruang (&deg;C)</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:center;">Display Suhu Ruang (&deg;C)</th>
            </tr>
        </thead>
        <tbody>
            @if(empty($rows))
                <tr>
                    <td colspan="11" style="text-align:center; padding:20px; color:#6c757d; font-style:italic;">Tidak ada data untuk periode / filter yang dipilih.</td>
                </tr>
            @else
                @foreach($rows as $row)
                    <tr>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['no'] }}</td>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['tanggal'] }}</td>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['shift'] }}</td>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['time'] }}</td>
                        <td style="text-align:left; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['qc'] }}</td>
                        <td style="text-align:left; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['produk'] }}</td>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['suhu_produk'] }}</td>
                        <td style="text-align:left; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['area'] }}</td>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['setting'] }}</td>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['aktual'] }}</td>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['display'] }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- ======== FOOTER: TANDA TANGAN ======== --}}
    @if($lastRecord)
        <table style="width:100%; border-collapse:collapse; margin-top:16px; border-top:2px solid #adb5bd;">
            <thead>
                <tr>
                    <th colspan="3" style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:600; padding:6px 8px; border:1px solid #adb5bd; text-align:center;">Dibuat Oleh</th>
                    <th colspan="3" style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:600; padding:6px 8px; border:1px solid #adb5bd; text-align:center;">Diketahui Oleh</th>
                    <th colspan="3" style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:600; padding:6px 8px; border:1px solid #adb5bd; text-align:center;">Disetujui Oleh</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" style="text-align:center; vertical-align:bottom; padding:35px 8px 8px; border:1px solid #adb5bd; font-size:9pt;">
                        <div style="font-weight:bold; text-transform:uppercase;">{{ $lastRecord->qcVerifier->name ?? $lastRecord->user->name ?? '-' }}</div>
                    </td>
                    <td colspan="3" style="text-align:center; vertical-align:bottom; padding:35px 8px 8px; border:1px solid #adb5bd; font-size:9pt;">
                        <div style="font-weight:bold; text-transform:uppercase;">{{ $lastRecord->produksiVerifier->name ?? '-' }}</div>
                    </td>
                    <td colspan="3" style="text-align:center; vertical-align:bottom; padding:35px 8px 8px; border:1px solid #adb5bd; font-size:9pt;">
                        <div style="font-weight:bold; text-transform:uppercase;">{{ $lastRecord->spvVerifier->name ?? '-' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="text-align:right; font-style:italic; font-size:8pt; color:#888888; padding-top:4px; border:none;">QW 06/00</td>
                </tr>
            </tbody>
        </table>
    @endif

</body>
</html>