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

    $sectionDefsV3 = [
        'suhu_premix'          => 'Premix',
        'suhu_seasoning'       => 'Seasoning',
        'suhu_dry'             => 'Dry',
        'suhu_cassing'         => 'Cassing',
        'suhu_beef'            => 'Beef',
        'suhu_packaging'       => 'Packaging',
        'suhu_ruang_chemical'  => 'Ruang Chemical',
        'suhu_ruang_seasoning' => 'Ruang Seasoning',
    ];

    $pickValue = function ($val, $key) {
        if ($val === null || $val === '' || $val === []) return '-';
        if (is_array($val)) return $val[$key] ?? '-';
        return (string) $val;
    };

    $rows = [];
    $no = 1;

    foreach ($allRecords as $p) {
        $tanggalStr = $p->tanggal ? $p->tanggal->format('d/m/Y') : '-';
        $shiftStr = $p->shift->shift ?? '-';
        $qcStr = $p->verifiedByQc->name ?? ($p->user->name ?? '-');
        $groupStr = optional(optional($p->user)->group)->name ?? '-';

        $defaultJam = null;
        if (!empty($p->pukul)) {
            try {
                $defaultJam = \Carbon\Carbon::parse($p->pukul)->format('H:i');
            } catch (\Throwable $e) {
                $defaultJam = (string) $p->pukul;
            }
        } elseif ($p->created_at) {
            $defaultJam = $p->created_at->format('H:i');
        }

        foreach ($sectionDefsV3 as $fieldKey => $secLabel) {
            $valData = $p->$fieldKey ?? null;
            if (is_string($valData) && !empty($valData)) {
                $valData = json_decode($valData, true);
            }
            if (empty($valData) || !is_array($valData)) continue;

            foreach ($valData as $uKey => $item) {
                if (!is_array($item)) continue;
                $isFilledTemp = !empty($item['setting']) || !empty($item['display']) || !empty($item['actual'])
                    || $item['setting'] === '0' || $item['display'] === '0' || $item['actual'] === '0';
                if (!$isFilledTemp) continue;

                $unitName = str_replace('unit_', '', (string) $uKey);
                $jam = $item['jam'] ?? $defaultJam ?? '-';

                $rows[] = [
                    'no'      => $no++,
                    'tanggal' => $tanggalStr,
                    'shift'   => $shiftStr,
                    'time'    => $jam,
                    'qc'      => $qcStr,
                    'group'   => $groupStr,
                    'area'    => trim($secLabel . ' ' . $unitName),
                    'setting' => $pickValue($item, 'setting'),
                    'aktual'  => $pickValue($item, 'actual'),
                    'display' => $pickValue($item, 'display'),
                ];
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
            <td colspan="{{ $logoExists ? 4 : 5 }}" style="vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:10px 14px;">
                <span style="font-size:12pt; font-weight:bold; color:#c41e3a; letter-spacing:0.5px;">PT. CHAROEN POKPHAND INDONESIA</span><br>
                <span style="font-size:9pt; color:#555555;">FOOD DIVISION {{ strtoupper($plantName) }}</span><br>
                <span style="font-size:9pt; color:#555555;">{{ strtoupper($plantName) }} - INDONESIA</span>
            </td>
            <td colspan="4" style="text-align:center; vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:10px 14px;">
                <span style="font-size:12pt; font-weight:bold; color:#1a1a1a; letter-spacing:0.5px;">PEMERIKSAAN SUHU PRODUK DAN SUHU RUANG PENYIMPANAN</span>
            </td>
        </tr>
    </table>

    {{-- ======== SUBHEADER ======== --}}
    <table style="width:100%; border-collapse:collapse; border:1px solid #adb5bd; margin-bottom:12px;">
        <tbody>
            <tr>
                <td colspan="2" style="font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Plant</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ $plantName }}</td>
                <td colspan="2" style="font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Periode</td>
                <td colspan="2" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ $periodeStr }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Total Baris</td>
                <td colspan="7" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ count($rows) }}</td>
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
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:left;">Area</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:center;">Setting Suhu Ruang (&deg;C)</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:center;">Aktual Suhu Ruang (&deg;C)</th>
                <th style="background-color:#f1f3f5; color:#1a1a1a; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #333333; text-align:center;">Display Suhu Ruang (&deg;C)</th>
            </tr>
        </thead>
        <tbody>
            @if(empty($rows))
                <tr>
                    <td colspan="9" style="text-align:center; padding:20px; color:#6c757d; font-style:italic;">Tidak ada data untuk periode / filter yang dipilih.</td>
                </tr>
            @else
                @foreach($rows as $row)
                    <tr>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['no'] }}</td>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['tanggal'] }}</td>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['shift'] }}</td>
                        <td style="text-align:center; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['time'] }}</td>
                        <td style="text-align:left; padding:5px 8px; border:1px solid #333333; font-size:9pt;">{{ $row['qc'] }}</td>
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
                        <div style="font-weight:bold; text-transform:uppercase;">{{ $lastRecord->verifiedByQc->name ?? $lastRecord->user->name ?? '-' }}</div>
                    </td>
                    <td colspan="3" style="text-align:center; vertical-align:bottom; padding:35px 8px 8px; border:1px solid #adb5bd; font-size:9pt;">
                        <div style="font-weight:bold; text-transform:uppercase;">{{ $lastRecord->verifiedByProduksi->name ?? '-' }}</div>
                    </td>
                    <td colspan="3" style="text-align:center; vertical-align:bottom; padding:35px 8px 8px; border:1px solid #adb5bd; font-size:9pt;">
                        <div style="font-weight:bold; text-transform:uppercase;">{{ $lastRecord->verifiedBySpv->name ?? '-' }}</div>
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
