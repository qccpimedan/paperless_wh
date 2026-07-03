<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Loading Produk</title>
    @php
        $firstRecord = $pemeriksaans->first();
        $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
    @endphp
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #1a1a1a;
            background: #fff;
        }

        .container {
            width: 100%;
            max-width: 100%;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 3px solid #c41e3a;
            padding-bottom: 12px;
            page-break-inside: avoid;
        }

        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
            width: 40%;
            vertical-align: middle;
            text-align: right;
        }

        .logo-company {
            display: table;
            width: 100%;
        }

        .header-logo {
            display: table-cell;
            width: 55px;
            vertical-align: middle;
        }

        .header-logo img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .header-company {
            display: table-cell;
            vertical-align: middle;
            padding-left: 12px;
        }

        .header-company h2 {
            font-size: 12px;
            font-weight: bold;
            color: #c41e3a;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }

        .header-company p {
            font-size: 8px;
            color: #444;
            margin-bottom: 1px;
        }

        .header-title h1 {
            font-size: 13px;
            font-weight: bold;
            color: #1a1a1a;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 8px 15px;
            border-radius: 4px;
            border-left: 4px solid #c41e3a;
            display: inline-block;
        }

        .subheader {
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 15px;
            background: #f8f9fa;
            page-break-inside: avoid;
            padding: 0;
        }

        .subheader-table {
            width: 100%;
            border-collapse: collapse;
        }

        .subheader-table td {
            padding: 8px 12px;
            font-size: 9px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }

        .subheader-table tr:last-child td {
            border-bottom: none;
        }

        .subheader-label {
            font-weight: 600;
            color: #495057;
            width: 100px;
        }

        .subheader-value {
            color: #1a1a1a;
        }

        .subheader-divider {
            width: 1px;
            background: #dee2e6;
            padding: 0;
        }

        .page-break {
            page-break-after: avoid;
            margin-bottom: 15px;
        }

        .page-break:last-child {
            page-break-after: avoid;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            overflow: hidden;
        }

        .data-column {
            width: 25%;
            border: 1px solid #dee2e6;
            padding: 12px;
            vertical-align: top;
            font-size: 9px;
            background: #fff;
        }

        .column-header {
            font-weight: bold;
            font-size: 9px;
            color: #8b1428;
            background: linear-gradient(135deg, #8b1428 0%, #5c0e1a 100%);
            padding: 8px 10px;
            margin: -10px -10px 10px -10px;
            text-align: center;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .column-header span {
            color: #fff;
        }

        .section-title {
            font-weight: bold;
            font-size: 9px;
            color: #c41e3a;
            border-bottom: 2px solid #c41e3a;
            margin-top: 10px;
            margin-bottom: 6px;
            padding-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .field-row {
            margin-bottom: 4px;
            display: table;
            width: 100%;
        }

        .field-label {
            display: table-cell;
            font-weight: 600;
            color: #495057;
            width: 80px;
            padding-right: 6px;
        }

        .field-value {
            display: table-cell;
            color: #1a1a1a;
            word-wrap: break-word;
        }

        .signature-section {
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: #f8f9fa;
            page-break-inside: avoid;
        }

        .signature-note {
            font-size: 8px;
            color: #495057;
            padding: 10px 12px;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .footer {
            margin-top: 15px;
            padding: 10px 15px;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            border-radius: 4px;
            text-align: center;
            page-break-inside: avoid;
        }

        .footer p {
            color: #fff;
            font-size: 7px;
            margin: 2px 0;
        }

        .footer .footer-main {
            font-weight: 600;
            font-size: 8px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-cell {
            width: 33.33%;
            text-align: center;
            padding: 0 15px;
            vertical-align: top;
        }

        .signature-header-item {
            font-size: 8px;
            font-weight: 600;
            color: #495057;
            padding-bottom: 25px;
        }

        .signature-space {
            height: 60px;
            margin: 0 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signature-line-empty {
            border-bottom: 2px solid #1a1a1a;
            height: 55px;
            width: 100%;
        }
        
        .qr-code-img {
            max-height: 55px;
            max-width: 55px;
        }

        .signature-name {
            font-size: 8px;
            font-weight: bold;
            color: #1a1a1a;
            padding-top: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .empty-message {
            text-align: center;
            padding: 40px 20px;
            font-style: italic;
            color: #6c757d;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px dashed #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($pemeriksaans->count() > 0)
            @php
                $formatBool = function ($val) {
                    if ($val === null || $val === '') return '-';
                    if ($val === true || $val === 1 || $val === '1' || $val === 'Ya' || $val === 'ya') return 'Ya';
                    if ($val === false || $val === 0 || $val === '0' || $val === 'Tidak' || $val === 'tidak') return 'Tidak';
                    return (string) $val;
                };
                $formatTemp = function ($val) {
                    if ($val === null || $val === '') return null;
                    $s = (string) $val;
                    return str_contains($s, '°') ? $s : ($s . '°C');
                };

                $items = collect();
                foreach ($pemeriksaans as $p) {
                    $rows = is_array($p->produk_data) ? $p->produk_data : [];
                    if (count($rows) === 0) {
                        $items->push(['record' => $p, 'rowIndex' => null]);
                        continue;
                    }
                    foreach (array_values($rows) as $i => $row) {
                        $items->push(['record' => $p, 'rowIndex' => $i]);
                    }
                }

                $columnsPerPage = 4;
                $chunks = $items->chunk($columnsPerPage);
            @endphp

            @foreach($chunks as $pageIndex => $pageRecords)
                <div class="header">
                    <div class="header-left">
                        <div class="logo-company">
                            <div class="header-logo">
                                <img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo CPI">
                            </div>
                            <div class="header-company">
                                <h2>PT. CHAROEN POKPHAND INDONESIA</h2>
                                @php
                                    $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
                                @endphp
                                <p>FOOD DIVISION {{ strtoupper($plantName) }}</p>
                                <p>{{ strtoupper($plantName) }} - INDONESIA</p>
                            </div>
                        </div>
                    </div>
                    <div class="header-right">
                        <div class="header-title">
                            <h1>PEMERIKSAAN LOADING PRODUK</h1>
                        </div>
                    </div>
                </div>

                @php
                    $firstColumn = $pageRecords->first();
                    $firstRecord = $firstColumn ? $firstColumn['record'] : null;
                @endphp

                <div class="subheader">
                    <table class="subheader-table">
                        <tr>
                            <td>
                                <span class="subheader-label">Hari/Tanggal:</span>
                                <span class="subheader-value">{{ $firstRecord && $firstRecord->tanggal ? (is_string($firstRecord->tanggal) ? $firstRecord->tanggal : $firstRecord->tanggal->format('d/m/Y')) : '-' }}</span>
                            </td>
                            <td class="subheader-divider"></td>
                            <td>
                                <span class="subheader-label">Shift:</span>
                                <span class="subheader-value">{{ $firstRecord->shift->shift ?? '-' }}</span>
                            </td>
                            <td class="subheader-divider"></td>
                            <td>
                                <span class="subheader-label">Kendaraan:</span>
                                <span class="subheader-value">{{ $firstRecord->kendaraan ? (($firstRecord->kendaraan->jenis_kendaraan ?? '-') . ' - ' . ($firstRecord->kendaraan->no_kendaraan ?? '-')) : '-' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="subheader-label">Supir:</span>
                                <span class="subheader-value">{{ $firstRecord->supir->nama_supir ?? '-' }}</span>
                            </td>
                            <td class="subheader-divider"></td>
                            <td>
                                <span class="subheader-label">Tujuan:</span>
                                <span class="subheader-value">{{ $firstRecord->tujuanPengiriman ? (($firstRecord->tujuanPengiriman->customer->nama_cust ?? '') . (($firstRecord->tujuanPengiriman->customer ? ' - ' : '') . ($firstRecord->tujuanPengiriman->nama_tujuan ?? ''))) : '-' }}</span>
                            </td>
                            <td class="subheader-divider"></td>
                            <td>
                                <span class="subheader-label">Segel/Gembok:</span>
                                <span class="subheader-value">
                                    @if(!$firstRecord || $firstRecord->segel_gembok === null)
                                        -
                                    @elseif($firstRecord->segel_gembok)
                                        Segel{{ $firstRecord->no_segel ? ' (No: ' . $firstRecord->no_segel . ')' : '' }}
                                    @else
                                        Gembok
                                    @endif
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="page-break">
                    <table class="data-table">
                        <tr>
                            @foreach($pageRecords as $colIndex => $column)
                                @php
                                    $pemeriksaan = $column['record'];
                                    $rowIndex = $column['rowIndex'];
                                    $columnNumber = ($pageIndex * $columnsPerPage) + $loop->iteration;

                                    $rows = is_array($pemeriksaan->produk_data) ? $pemeriksaan->produk_data : [];
                                    $row = ($rowIndex !== null) ? ($rows[$rowIndex] ?? []) : [];

                                    $idProduk = $row['id_produk'] ?? null;
                                    $kodeProduksi = $row['kode_produksi'] ?? null;
                                    $bestBefore = $row['best_before'] ?? null;
                                    $jumlahKemasan = $row['jumlah_kemasan'] ?? null;
                                    $jumlahSampling = $row['jumlah_sampling'] ?? null;
                                    $beratPerKarung = $row['berat_perkarung'] ?? null;
                                    $kondisiKemasan = $row['kondisi_kemasan'] ?? null;
                                    $keterangan = $row['keterangan'] ?? null;

                                    $temperatureMobil = $pemeriksaan->temperature_mobil;
                                    $temperatureProduk = is_array($pemeriksaan->temperature_produk) ? $pemeriksaan->temperature_produk : [];
                                    $tempProdukStr = count($temperatureProduk) ? implode(', ', array_map(fn($t) => $formatTemp($t), $temperatureProduk)) : null;
                                @endphp

                                <td class="data-column">
                                    <div class="column-header">PEMERIKSAAN #{{ $columnNumber }}</div>

                                    <div class="section-title">Produk</div>
                                    <div class="field-row">
                                        <span class="field-label">Nama:</span>
                                        <span class="field-value">{{ $idProduk ? ($produkMap[$idProduk] ?? 'N/A') : '-' }}</span>
                                    </div>

                                    <div class="section-title">Detail</div>
                                    <div class="field-row">
                                        <span class="field-label">Kode Produksi:</span>
                                        <span class="field-value">{{ $kodeProduksi ?? '-' }}</span>
                                    </div>
                                    <div class="field-row">
                                        <span class="field-label">Best Before:</span>
                                        <span class="field-value">
                                            @if($bestBefore)
                                                @php
                                                    try {
                                                        $bestBeforeFormatted = \Carbon\Carbon::parse($bestBefore)->format('d/m/Y');
                                                    } catch (\Exception $e) {
                                                        $bestBeforeFormatted = $bestBefore;
                                                    }
                                                @endphp
                                                {{ $bestBeforeFormatted }}
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                    <div class="field-row">
                                        <span class="field-label">Jumlah Kemasan:</span>
                                        <span class="field-value">{{ $jumlahKemasan ?? '-' }}</span>
                                    </div>
                                    <div class="field-row">
                                        <span class="field-label">Jumlah Sampling:</span>
                                        <span class="field-value">{{ $jumlahSampling ?? '-' }}</span>
                                    </div>
                                    <div class="field-row">
                                        <span class="field-label">Berat per Karung & Box:</span>
                                        <span class="field-value">{{ $beratPerKarung ?? '-' }}</span>
                                    </div>

                                    <div class="section-title">Suhu</div>
                                    <div class="field-row">
                                        <span class="field-label">Suhu Mobil:</span>
                                        <span class="field-value">{{ $formatTemp($temperatureMobil) ?? '-' }}</span>
                                    </div>
                                    <div class="field-row">
                                        <span class="field-label">Suhu Produk:</span>
                                        <span class="field-value">{{ $tempProdukStr ?? '-' }}</span>
                                    </div>
                                    <div class="field-row">
                                        <span class="field-label">Kondisi Produk:</span>
                                        <span class="field-value">{{ $pemeriksaan->kondisi_produk ?? '-' }}</span>
                                    </div>

                                    <div class="section-title">Pemeriksaan</div>
                                    <div class="field-row">
                                        <span class="field-label">Kond. Kemasan:</span>
                                        <span class="field-value">{{ $formatBool($kondisiKemasan) }}</span>
                                    </div>
                                    <div class="field-row">
                                        <span class="field-label">Keterangan:</span>
                                        <span class="field-value">{{ $keterangan ?? '-' }}</span>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                </div>
                <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
                    QW 10/00
                </div>


                <div class="signature-section">
                    <!-- <div class="signature-note">
                        Total Kolom Halaman Ini: {{ $pageRecords->count() }} | Dihasilkan: {{ now()->format('d/m/Y H:i:s') }}
                    </div> -->
                    <table class="signature-table">
                        <tr>
                            <td class="signature-cell">
                                <div class="signature-header-item">Dibuat Oleh</div>
                                <div class="signature-space">
                                    @if($qcUser)
                                        @php
                                            $qcQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$qcUser} (Tim QC)";
                                            $qcQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($qcQrData);
                                            $base64QcSvg = "data:image/svg+xml;base64," . base64_encode($qcQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64QcSvg }}" class="qr-code-img" alt="QR Code QC">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $qcUser ?: '-' }}</div>
                            </td>
                            <td class="signature-cell">
                                <div class="signature-header-item">Diketahui Oleh</div>
                                <div class="signature-space">
                                    @if($produksiUser)
                                        @php
                                            $prodQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$produksiUser} (Tim Warehouse)";
                                            $prodQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($prodQrData);
                                            $base64ProdSvg = "data:image/svg+xml;base64," . base64_encode($prodQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64ProdSvg }}" class="qr-code-img" alt="QR Code Warehouse">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $produksiUser ?: '-' }}</div>
                            </td>
                            <td class="signature-cell">
                                <div class="signature-header-item">Disetujui Oleh</div>
                                <div class="signature-space">
                                    @if($spvQcUser)
                                        @php
                                            $spvQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$spvQcUser} (Tim Supervisor QC)";
                                            $spvQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($spvQrData);
                                            $base64SpvSvg = "data:image/svg+xml;base64," . base64_encode($spvQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64SpvSvg }}" class="qr-code-img" alt="QR Code SPV">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $spvQcUser ?: '-' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>

                @if(!$loop->last)
                    <div style="page-break-after: always;"></div>
                @endif
            @endforeach
        @else
            <div class="empty-message">Tidak ada data untuk dicetak.</div>
        @endif
    </div>
</body>
</html>
