<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Return Barang Customer</title>
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
            font-size: 9px;
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
            font-size: 8px;
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
            padding: 10px;
            vertical-align: top;
            font-size: 8px;
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

        .section-title {
            font-weight: bold;
            font-size: 8px;
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
            width: 45px;
            padding-right: 5px;
        }

        .field-value {
            display: table-cell;
            color: #1a1a1a;
            word-wrap: break-word;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 600;
            color: #fff;
        }

        .badge-info { background: #0dcaf0; }
        .badge-warning { background: #ffc107; color: #1a1a1a; }
        .badge-success { background: #198754; }
        .badge-danger { background: #dc3545; }
        .badge-secondary { background: #6c757d; }

        .signature-section {
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: #f8f9fa;
            page-break-inside: avoid;
        }

        .signature-note {
            font-size: 7px;
            color: #495057;
            padding: 8px 12px;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .signature-note .ok-text {
            color: #28a745;
            font-weight: 600;
        }

        .signature-note .not-ok-text {
            color: #dc3545;
            font-weight: 600;
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
            height: 40px;
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
        <div class="header">
            <div class="header-left">
                <div class="logo-company">
                    <div class="header-logo">
                        <img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo">
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
                    <h1>Pemeriksaan Return Barang Customer</h1>
                </div>
            </div>
        </div>

        @if($pemeriksaans->count() > 0)
            @php
                $columnsPerPage = 4;

                $pdfColumns = collect();
                foreach ($pemeriksaans as $p) {
                    $rowsTmp = is_array($p->produk_data) ? $p->produk_data : [];
                    $rowCount = max(1, count($rowsTmp));

                    for ($i = 0; $i < $rowCount; $i++) {
                        $pdfColumns->push([
                            'record' => $p,
                            'produkIndex' => $i,
                        ]);
                    }
                }

                $chunks = $pdfColumns->chunk($columnsPerPage);

                $allCustomerIds = [];
                foreach ($pemeriksaans as $p) {
                    $rowsTmp = is_array($p->produk_data) ? $p->produk_data : [];
                    foreach ($rowsTmp as $r) {
                        $cid = $r['id_customer'] ?? null;
                        if ($cid) {
                            $allCustomerIds[] = $cid;
                        }
                    }
                }

                $customerMap = [];
                if (!empty($allCustomerIds)) {
                    $customerMap = \App\Models\Customer::whereIn('id', array_values(array_unique($allCustomerIds)))
                        ->pluck('nama_cust', 'id')
                        ->toArray();
                }
            @endphp

            @foreach($chunks as $pageIndex => $pageRecords)
                {{-- SUBHEADER (Setiap halaman) --}}
                <div class="subheader">
                    <table class="subheader-table">
                        <tr>
                            <td>
                                <span class="subheader-label">Shift</span>
                                <span class="subheader-value">: {{ $shift ? ($shift->shift ?? '-') : '-' }}</span>
                            </td>
                            <td class="subheader-divider"></td>
                            <td>
                                <span class="subheader-label">Tanggal</span>
                                <span class="subheader-value">:
                                    @if(!empty($tanggal_dari) || !empty($tanggal_sampai))
                                        {{ $tanggal_dari ?? '-' }} s/d {{ $tanggal_sampai ?? '-' }}
                                    @elseif(!empty($tanggal))
                                        {{ $tanggal }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td>
                                <span class="subheader-label">Total Data</span>
                                <span class="subheader-value">: {{ $pemeriksaans ? $pemeriksaans->count() : 0 }}</span>
                            </td>
                            <td class="subheader-divider"></td>
                            <td>
                                <span class="subheader-label">Dicetak</span>
                                <span class="subheader-value">: {{ now()->format('d/m/Y H:i') }}</span>
                            </td>
                        </tr> -->
                    </table>
                </div>

                <div class="page-break">
                    <table class="data-table">
                        <tr>
                            @foreach($pageRecords as $colIndex => $column)
                                @php
                                    $pemeriksaan = $column['record'];
                                    $produkIndex = $column['produkIndex'];
                                    $columnNumber = ($pageIndex * $columnsPerPage) + $loop->iteration;
                                    $rows = is_array($pemeriksaan->produk_data) ? $pemeriksaan->produk_data : [];
                                    $row = $rows[$produkIndex] ?? null;
                                @endphp
                                <td class="data-column" data-numbered="true">
                                    <div class="column-header">PEMERIKSAAN #{{ $columnNumber }}</div>

                                    <div class="section-title">Data Umum</div>
                                    <div class="field-row"><span class="field-label">No</span><span class="field-value">{{ $columnNumber }}</span></div>
                                    <div class="field-row"><span class="field-label">Tanggal</span><span class="field-value">{{ $pemeriksaan->tanggal ? $pemeriksaan->tanggal->format('d/m/Y') : '-' }}</span></div>
                                    <!-- <div class="field-row"><span class="field-label">Shift</span><span class="field-value">{{ $pemeriksaan->shift->shift ?? '-' }}</span></div> -->
                                    <!-- <div class="field-row"><span class="field-label">Plant</span><span class="field-value">{{ $pemeriksaan->user->plant->plant ?? '-' }}</span></div> -->
                                    <div class="field-row"><span class="field-label">Customer</span><span class="field-value">{{ $row ? ($customerMap[$row['id_customer'] ?? null] ?? '-') : '-' }}</span></div>
                                    <div class="field-row"><span class="field-label">Ekspedisi</span><span class="field-value">{{ $pemeriksaan->ekspedisi->nama_ekspedisi ?? '-' }}</span></div>

                                    <div class="section-title">Kendaraan</div>
                                    <div class="field-row"><span class="field-label">Nopol</span><span class="field-value">{{ $pemeriksaan->no_polisi ?? '-' }}</span></div>
                                    <div class="field-row"><span class="field-label">Supir</span><span class="field-value">{{ $pemeriksaan->nama_supir ?? '-' }}</span></div>
                                    <div class="field-row"><span class="field-label">Jml Datang</span><span class="field-value">{{ $pemeriksaan->waktu_kedatangan_display ?? ($pemeriksaan->waktu_kedatangan ?? '-') }}</span></div>
                                    <div class="field-row"><span class="field-label">Suhu Produk</span><span class="field-value">{{ $pemeriksaan->suhu_mobil ?? '-' }}</span></div>

                                    <div class="section-title">Alasan Return</div>
                                    <div class="field-row"><span class="field-value">{{ $row['alasan_return'] ?? '-' }}</span></div>

                                    <div class="section-title">Data Produk</div>
                                    @if($row)
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            <!-- <div class="field-row"><span class="field-label">Produk</span><span class="field-value">#{{ $produkIndex + 1 }}</span></div> -->
                                            <div class="field-row"><span class="field-label">Nama Produk</span><span class="field-value">{{ $produkNamaById[$row['id_produk']] ?? '-' }}</span></div>
                                            <div class="field-row"><span class="field-label">Kondisi Produk</span><span class="field-value">{{ $row['kondisi_produk'] ?? '-' }}</span></div>
                                            <div class="field-row"><span class="field-label">Suhu Produk</span><span class="field-value">{{ $row['suhu_produk'] ?? '-' }}</span></div>
                                            <div class="field-row"><span class="field-label">Kode Produksi</span><span class="field-value">{{ $row['kode_produksi'] ?? '-' }}</span></div>
                                            <div class="field-row"><span class="field-label">Expired Date</span><span class="field-value">{{ !empty($row['expired_date']) ? \Carbon\Carbon::parse($row['expired_date'])->format('d/m/Y') : '-' }}</span></div>
                                            <div class="field-row"><span class="field-label">Jml Produk</span><span class="field-value">{{ $row['jumlah_barang'] ?? '-' }}</span></div>
                                            <div class="field-row"><span class="field-label">Kondisi Kemasan</span><span class="field-value">@if(isset($row['kondisi_kemasan'])){{ $row['kondisi_kemasan'] ? 'Baik' : 'Rusak' }}@else-@endif</span></div>
                                            <div class="field-row"><span class="field-label">Kondisi Produk</span><span class="field-value">@if(isset($row['kondisi_produk_check'])){{ $row['kondisi_produk_check'] ? 'Baik' : 'Rusak' }}@else-@endif</span></div>
                                            <div class="field-row"><span class="field-label">Rekomendasi</span><span class="field-value">{{ $row['rekomendasi'] ?? '-' }}</span></div>
                                            @if(!empty($row['keterangan']))
                                                <div class="field-row"><span class="field-label">Keterangan</span><span class="field-value">{{ $row['keterangan'] }}</span></div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="field-row"><span class="field-value">Tidak ada data produk</span></div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </table>
                </div>

                <div class="signature-section">
                    @php
                        $firstColumn = $pageRecords->first();
                        $firstRecord = is_array($firstColumn) ? ($firstColumn['record'] ?? null) : null;
                    @endphp
                    <table class="signature-table">
                        <tr>
                            <td class="signature-cell">
                                <div class="signature-header-item">Dibuat Oleh (QC)</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->qcVerifier)
                                        @php
                                            $qcQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$firstRecord->qcVerifier->name} (Tim QC)";
                                            $qcQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($qcQrData);
                                            $base64QcSvg = "data:image/svg+xml;base64," . base64_encode($qcQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64QcSvg }}" class="qr-code-img" alt="QR Code QC">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $firstRecord->qcVerifier->name ?? '-' }}</div>
                            </td>
                            <td class="signature-cell">
                                <div class="signature-header-item">Disetujui Oleh (Tim Warehouse)</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->produksiVerifier)
                                        @php
                                            $prodQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$firstRecord->produksiVerifier->name} (Tim Warehouse)";
                                            $prodQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($prodQrData);
                                            $base64ProdSvg = "data:image/svg+xml;base64," . base64_encode($prodQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64ProdSvg }}" class="qr-code-img" alt="QR Code Warehouse">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $firstRecord->produksiVerifier->name ?? '-' }}</div>
                            </td>
                            <td class="signature-cell">
                                <div class="signature-header-item">Diverifikasi Oleh (SPV QC)</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->spvVerifier)
                                        @php
                                            $spvQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$firstRecord->spvVerifier->name} (Tim Supervisor QC)";
                                            $spvQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($spvQrData);
                                            $base64SpvSvg = "data:image/svg+xml;base64," . base64_encode($spvQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64SpvSvg }}" class="qr-code-img" alt="QR Code SPV">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $firstRecord->spvVerifier->name ?? '-' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
                    QW 11/00
                </div>

                @if(!$loop->last)
                    <div style="page-break-after: always;"></div>
                @endif
            @endforeach
        @else
            <div class="empty-message">
                <p>Tidak ada data untuk filter yang dipilih.</p>
            </div>
        @endif
    </div>
</body>
</html>
