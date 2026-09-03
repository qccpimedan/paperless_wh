<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Pemeriksaan Suhu Ruang</title>
    <style>
        @page { size: A4 landscape; margin: 12mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 9px; line-height: 1.4; color: #1a1a1a; }

        .header { display: table; width: 100%; margin-bottom: 10px; border-bottom: 3px solid #c41e3a; padding-bottom: 8px; }
        .header-left { display: table-cell; width: 60%; vertical-align: middle; }
        .header-right { display: table-cell; width: 40%; vertical-align: middle; text-align: right; }
        .logo-company { display: table; width: 100%; }
        .header-logo { display: table-cell; width: 55px; vertical-align: middle; }
        .header-logo img { width: 50px; height: 50px; object-fit: contain; }
        .header-company { display: table-cell; vertical-align: middle; padding-left: 12px; }
        .header-company h2 { font-size: 12px; font-weight: bold; color: #c41e3a; margin-bottom: 2px; letter-spacing: 0.5px; }
        .header-company p { font-size: 8px; color: #444; margin-bottom: 1px; }
        .header-title h1 { font-size: 13px; font-weight: bold; color: #1a1a1a; background: #f1f3f5; padding: 8px 15px; border-radius: 4px; border-left: 4px solid #c41e3a; display: inline-block; }

        .subheader { width: 100%; border: 1px solid #dee2e6; border-radius: 6px; margin-bottom: 8px; background: #f8f9fa; }
        .subheader-table { width: 100%; border-collapse: collapse; }
        .subheader-table td { padding: 5px 10px; font-size: 8px; border-bottom: 1px solid #e9ecef; vertical-align: top; }
        .subheader-table tr:last-child td { border-bottom: none; }
        .subheader-label { font-weight: 600; color: #495057; width: 110px; }

        .section-title { font-weight: bold; font-size: 9px; color: #c41e3a; margin: 6px 0 4px; }

        /* ===== Tabel rekap data ===== */
        table.data-simple { width: 100%; border-collapse: collapse; border: 1px solid #333; }
        table.data-simple th { background: #f1f3f5; font-weight: 700; font-size: 8px; padding: 5px 6px; border: 1px solid #333; text-align: center; }
        table.data-simple td { font-size: 8px; padding: 4.5px 6px; border: 1px solid #333; }
        table.data-simple td.center { text-align: center; }
        table.data-simple tbody tr:nth-child(even) { background: #fafafa; }

        .empty-state { text-align: center; padding: 40px; color: #6c757d; font-style: italic; }

        /* ===== Signature TTD tiap halaman ===== */
        .signature { width: 100%; margin-top: 14px; border-top: 1px solid #dee2e6; padding-top: 8px; page-break-inside: avoid; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-cell { width: 33.33%; text-align: center; font-size: 8px; padding: 4px 2px; vertical-align: top; }
        .signature-header-item { font-weight: 600; margin-bottom: 4px; }
        .signature-space { height: 55px; margin: 0 auto; text-align: center; }
        .signature-line-empty { border-bottom: 2px solid #1a1a1a; height: 28px; width: 80%; margin: 0 auto; }
        .qr-code-img { max-height: 50px; max-width: 50px; display: inline-block; }
        .signature-name { font-weight: 700; text-transform: uppercase; margin-top: 4px; }

        .page-break { page-break-after: always; }
    </style>
</head>
<body>
<div class="container">

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

    $formatJam = function ($time) {
        if (empty($time) || $time === '-') return '-';
        try {
            return \Carbon\Carbon::parse($time)->format('H:i');
        } catch (\Throwable $e) {
            return substr((string) $time, 0, 5);
        }
    };

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
            $initialTime = $formatJam($firstHistory->pukul_lama);
        } elseif ($firstHistory && $firstHistory->created_at) {
            $initialTime = $firstHistory->created_at->format('H:i');
        } elseif (!empty($p->pukul)) {
            $initialTime = $formatJam($p->pukul);
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
                $hJam = $formatJam($history->pukul_baru);
            } elseif (!empty($history->pukul_lama)) {
                $hJam = $formatJam($history->pukul_lama);
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

    $uniqueProduk = collect($rows)->pluck('produk')->unique()->filter(fn($v) => $v !== '-')->implode(', ');
    $displayProduk = !empty($uniqueProduk) ? $uniqueProduk : '-';

    $uniqueSuhuProduk = collect($rows)->pluck('suhu_produk')->unique()->filter(fn($v) => $v !== '-')->implode(', ');
    $displaySuhuProduk = !empty($uniqueSuhuProduk) ? $uniqueSuhuProduk : '-';

    // Chunking baris data (misal 20 baris per halaman) seperti versi v2
    $rowsPerChunk = 20;
    $rowChunks = collect($rows)->chunk($rowsPerChunk);
@endphp

@if($rowChunks->isEmpty())
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
            <div class="header-title"><h1>PEMERIKSAAN SUHU PRODUK DAN SUHU RUANG PENYIMPANAN</h1></div>
        </div>
    </div>
    <div class="empty-state">Tidak ada data untuk periode / filter yang dipilih.</div>
@else
    @foreach($rowChunks as $chunkIndex => $chunkRows)
        {{-- Header KOP --}}
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
                <div class="header-title"><h1>PEMERIKSAAN SUHU PRODUK DAN SUHU RUANG PENYIMPANAN</h1></div>
            </div>
        </div>

        {{-- Subheader --}}
        <div class="subheader">
            <table class="subheader-table">
                <tr>
                    <td class="subheader-label">Plant</td>
                    <td>{{ $plantName }}</td>
                    <td class="subheader-label">Periode</td>
                    <td>{{ $periodeStr }}</td>
                </tr>
                <tr>
                    <td class="subheader-label">Total Baris</td>
                    <td>{{ count($rows) }}</td>
                    <td class="subheader-label">Halaman</td>
                    <td>{{ $chunkIndex + 1 }} / {{ $rowChunks->count() }}</td>
                </tr>
            </table>
        </div>

        <div class="section-title">Data Pemeriksaan Suhu Ruang</div>

        {{-- Tabel Data --}}
        <table class="data-simple">
            <thead>
                <tr>
                    <th style="width:3%">No</th>
                    <th style="width:7%">Tanggal</th>
                    <th style="width:4%">Shift</th>
                    <th style="width:5%">Time</th>
                    <th style="width:10%">QC</th>
                    <th style="width:14%">Produk</th>
                    <th style="width:7%">Suhu Produk</th>
                    <th style="width:14%">Area</th>
                    <th style="width:12%">Setting Suhu Ruang (&deg;C)</th>
                    <th style="width:12%">Aktual Suhu Ruang (&deg;C)</th>
                    <th style="width:12%">Display Suhu Ruang (&deg;C)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chunkRows as $row)
                    <tr>
                        <td class="center">{{ $row['no'] }}</td>
                        <td class="center">{{ $row['tanggal'] }}</td>
                        <td class="center">{{ $row['shift'] }}</td>
                        <td class="center">{{ $row['time'] }}</td>
                        <td>{{ $row['qc'] }}</td>
                        <td>{{ $row['produk'] }}</td>
                        <td class="center">{{ $row['suhu_produk'] }}</td>
                        <td>{{ $row['area'] }}</td>
                        <td class="center">{{ $row['setting'] }}</td>
                        <td class="center">{{ $row['aktual'] }}</td>
                        <td class="center">{{ $row['display'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 8.5px; color: #666; margin-top: 4px;">
            QW 06/00
        </div>

        {{-- Blok TTD di setiap halaman --}}
        @if($lastRecord)
            <div class="signature">
                <table class="signature-table">
                    <tr>
                        <td class="signature-cell">
                            <div class="signature-header-item">Dibuat Oleh</div>
                            <div class="signature-space">
                                @if($lastRecord->verifiedByQc || $lastRecord->user)
                                    @php
                                        $qcNameStr = $lastRecord->verifiedByQc->name ?? $lastRecord->user->name;
                                        $qcQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate("Diverifikasi oleh {$qcNameStr} (QC)");
                                    @endphp
                                    <img src="data:image/svg+xml;base64,{{ base64_encode($qcQrCodeSvg) }}" class="qr-code-img" alt="QR Code QC">
                                @else
                                    <div class="signature-line-empty"></div>
                                @endif
                            </div>
                            <div class="signature-name">{{ $lastRecord->verifiedByQc->name ?? $lastRecord->user->name ?? '-' }}</div>
                        </td>
                        <td class="signature-cell">
                            <div class="signature-header-item">Diketahui Oleh</div>
                            <div class="signature-space">
                                @if($lastRecord->verifiedByProduksi)
                                    @php
                                        $prodQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate("Diverifikasi oleh {$lastRecord->verifiedByProduksi->name} (Warehouse)");
                                    @endphp
                                    <img src="data:image/svg+xml;base64,{{ base64_encode($prodQrCodeSvg) }}" class="qr-code-img" alt="QR Code Warehouse">
                                @else
                                    <div class="signature-line-empty"></div>
                                @endif
                            </div>
                            <div class="signature-name">{{ $lastRecord->verifiedByProduksi->name ?? '-' }}</div>
                        </td>
                        <td class="signature-cell">
                            <div class="signature-header-item">Disetujui Oleh</div>
                            <div class="signature-space">
                                @if($lastRecord->verifiedBySpv)
                                    @php
                                        $spvQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate("Diverifikasi oleh {$lastRecord->verifiedBySpv->name} (SPV QC)");
                                    @endphp
                                    <img src="data:image/svg+xml;base64,{{ base64_encode($spvQrCodeSvg) }}" class="qr-code-img" alt="QR Code SPV">
                                @else
                                    <div class="signature-line-empty"></div>
                                @endif
                            </div>
                            <div class="signature-name">{{ $lastRecord->verifiedBySpv->name ?? '-' }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        {{-- Break ke halaman baru jika bukan halaman terakhir --}}
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
@endif

</div>
</body>
</html>