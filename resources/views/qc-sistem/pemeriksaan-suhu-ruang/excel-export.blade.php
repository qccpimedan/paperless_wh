<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="font-family: Arial, sans-serif; font-size: 10pt; color: #1a1a1a;">

@php
    $isAllShift = $isAllShift ?? false;

    // ----- Satukan semua record (mode "semua shift" maupun "shift tunggal") jadi satu list -----
    if ($isAllShift) {
        $allRecords = collect();
        foreach (($dataPerShift ?? []) as $group) {
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

    $sectionLabels = [
        'cold_storage' => 'Cold Storage',
        'anteroom_loading' => 'Anteroom Loading',
        'pre_loading' => 'Pre Loading',
        'prestaging' => 'Prestaging',
        'anteroom_ekspansi_further' => 'Anteroom Ekspansi Further',
        'anteroom_ekspansi_sausage' => 'Anteroom Ekspansi Sausage',
    ];

    $pickValue = function ($val, $key) {
        if ($val === null || $val === '' || $val === []) return '-';
        if (is_array($val)) return $val[$key] ?? '-';
        return (string) $val;
    };

    $findUnitRow = function ($rows, $unit) {
        if (!is_array($rows)) return null;
        foreach ($rows as $r) {
            if (is_array($r) && (string) ($r['unit'] ?? '') === (string) $unit) return $r;
        }
        return null;
    };

    $isSame = fn ($a, $b) => json_encode($a) === json_encode($b);

    // ----- Bangun baris-baris tabel flat -----
    $rows = [];
    $no = 1;

    foreach ($allRecords as $p) {
        $tanggalStr = $p->tanggal ? $p->tanggal->format('d/m/Y') : '-';
        $shiftStr = $p->shift->shift ?? '-';
        $qcStr = $p->verifiedByQc->name ?? ($p->user->name ?? '-');
        $groupStr = optional(optional($p->user)->group)->name ?? '-';

        $produkName = $p->produk ? ($p->produk->nama_produk ?? '-') : '-';
        $kategori = $p->produk ? ($p->produk->kategori_code ?? null) : null;
        $produkStr = $kategori ? "{$kategori} - {$produkName}" : $produkName;
        $suhuProdukStr = $p->suhu_produk ?? '-';

        $suhu = is_array($p->suhu_data) ? $p->suhu_data : (json_decode($p->suhu_data ?? '[]', true) ?: []);
        $histories = ($p->relationLoaded('histories') && $p->histories) ? $p->histories->sortBy('created_at') : collect();

        // 1. Ambil Data Input Pertama (Initial State)
        $firstHistory = $histories->first();
        $initialData = $firstHistory 
            ? (is_array($firstHistory->suhu_data_lama) ? $firstHistory->suhu_data_lama : (json_decode($firstHistory->suhu_data_lama ?? '[]', true) ?: [])) 
            : $suhu;

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

        // Render baris untuk Data Input Pertama
        foreach ($sectionLabels as $secKey => $secLabel) {
            $secData = $initialData[$secKey] ?? [];
            if (empty($secData)) continue;

            if (in_array($secKey, ['cold_storage', 'anteroom_loading'])) {
                foreach ($secData as $item) {
                    $unit = $item['unit'] ?? '';
                    $rows[] = [
                        'no' => $no++,
                        'tanggal' => $tanggalStr,
                        'shift' => $shiftStr,
                        'time' => $initialTime,
                        'qc' => $qcStr,
                        'group' => $groupStr,
                        'produk' => $produkStr,
                        'suhu_produk' => $initialSuhuProduk,
                        'area' => trim($secLabel . ' ' . $unit),
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
                    'area' => $secLabel,
                    'setting' => $pickValue($secData, 'setting'),
                    'aktual' => $pickValue($secData, 'actual'),
                    'display' => $pickValue($secData, 'display'),
                ];
            }
        }

        // 2. Render baris untuk Riwayat Perubahan (Edit Per Jam) jika ada
        foreach ($histories as $history) {
            $hJam = '-';
            if (!empty($history->pukul_baru)) {
                $hJam = $history->pukul_baru;
            } elseif (!empty($history->pukul_lama)) {
                $hJam = $history->pukul_lama;
            } elseif ($history->created_at) {
                $hJam = $history->created_at->format('H:i');
            }

            $lama = is_array($history->suhu_data_lama) ? $history->suhu_data_lama : (json_decode($history->suhu_data_lama ?? '[]', true) ?: []);
            $baru = is_array($history->suhu_data_baru) ? $history->suhu_data_baru : (json_decode($history->suhu_data_baru ?? '[]', true) ?: []);

            $hSuhuProduk = !empty($history->suhu_produk_baru) ? $history->suhu_produk_baru : $suhuProdukStr;
            $userQcName = $history->user ? $history->user->name : $qcStr;

            foreach ($sectionLabels as $secKey => $secLabel) {
                $oldSection = $lama[$secKey] ?? [];
                $newSection = $baru[$secKey] ?? [];

                if (in_array($secKey, ['cold_storage', 'anteroom_loading'])) {
                    $allUnits = [];
                    foreach ((array) $oldSection as $r) { if (is_array($r) && isset($r['unit'])) $allUnits[] = (string) $r['unit']; }
                    foreach ((array) $newSection as $r) { if (is_array($r) && isset($r['unit'])) $allUnits[] = (string) $r['unit']; }
                    $allUnits = array_unique($allUnits);

                    foreach ($allUnits as $u) {
                        $oldItem = $findUnitRow($oldSection, $u);
                        $newItem = $findUnitRow($newSection, $u);

                        // Masukkan ke tabel jika ada perubahan data pada unit ini
                        if (!$isSame($oldItem, $newItem) && !empty($newItem)) {
                            $rows[] = [
                                'no' => $no++,
                                'tanggal' => $tanggalStr,
                                'shift' => $shiftStr,
                                'time' => $hJam,
                                'qc' => $userQcName,
                                'group' => $groupStr,
                                'produk' => $produkStr,
                                'suhu_produk' => $hSuhuProduk,
                                'area' => trim($secLabel . ' ' . $u),
                                'setting' => $pickValue($newItem, 'setting'),
                                'aktual' => $pickValue($newItem, 'actual'),
                                'display' => $pickValue($newItem, 'display'),
                            ];
                        }
                    }
                } else {
                    // Section tunggal
                    if (!$isSame($oldSection, $newSection) && !empty($newSection)) {
                        $rows[] = [
                            'no' => $no++,
                            'tanggal' => $tanggalStr,
                            'shift' => $shiftStr,
                            'time' => $hJam,
                            'qc' => $userQcName,
                            'group' => $groupStr,
                            'produk' => $produkStr,
                            'suhu_produk' => $hSuhuProduk,
                            'area' => $secLabel,
                            'setting' => $pickValue($newSection, 'setting'),
                            'aktual' => $pickValue($newSection, 'actual'),
                            'display' => $pickValue($newSection, 'display'),
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

    {{-- ======== FOOTER: TANDA TANGAN + QW ======== --}}
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
