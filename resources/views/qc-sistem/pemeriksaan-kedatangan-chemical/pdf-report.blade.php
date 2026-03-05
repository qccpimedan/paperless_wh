<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Kedatangan Bahan Kimia</title>
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
        
        /* HEADER */
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
        
        /* SUBHEADER */
        .subheader {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .subheader-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        
        .subheader-table tr {
            border-bottom: 1px solid #dee2e6;
        }
        
        .subheader-table td {
            padding: 4px 6px;
            vertical-align: middle;
        }
        
        .subheader-label {
            font-weight: 600;
            color: #495057;
            min-width: 70px;
            display: inline-block;
        }
        
        .subheader-value {
            color: #1a1a1a;
        }
        
        .subheader-divider {
            width: 1px;
            background: #dee2e6;
        }
        
        /* DATA TABLE */
        .page-break {
            page-break-inside: avoid;
            margin-bottom: 15px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .data-table tr {
            border: 1px solid #dee2e6;
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
        
        .check-item {
            color: #28a745;
            font-weight: 500;
        }
        
        .check-item::before {
            content: "V ";
            color: #28a745;
            font-weight: bold;
        }

        /* SIGNATURE SECTION */
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
            max-height: 60px;
            max-width: 60px;
        }
        
        .signature-name {
            font-size: 8px;
            font-weight: bold;
            color: #1a1a1a;
            padding-top: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* FOOTER */
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

        .empty-message {
            text-align: center;
            padding: 40px 20px;
            font-style: italic;
            color: #6c757d;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px dashed #dee2e6;
        }
        
        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-release {
            background: #d4edda;
            color: #155724;
        }
        
        .status-hold {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-reject {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($pemeriksaans->count() > 0)
            @php
                $columnsPerPage = 4;

                $allChemicalIds = [];
                $allProdusenIds = [];
                $allDistributorIds = [];

                $pdfColumns = collect();
                foreach ($pemeriksaans as $p) {
                    $detailChemicals = $p->detail_chemicals ?? [];

                    if (!empty($detailChemicals)) {
                        foreach ($detailChemicals as $d) {
                            if (!empty($d['id_chemical'])) {
                                $allChemicalIds[] = $d['id_chemical'];
                            }
                            if (!empty($d['id_produsen'])) {
                                $allProdusenIds[] = $d['id_produsen'];
                            }
                            if (!empty($d['id_distributor'])) {
                                $allDistributorIds[] = $d['id_distributor'];
                            }
                        }
                    }

                    $rowCount = count($detailChemicals);

                    for ($i = 0; $i < $rowCount; $i++) {
                        $pdfColumns->push([
                            'record' => $p,
                            'rowIndex' => $i,
                        ]);
                    }
                }

                $chunks = $pdfColumns->chunk($columnsPerPage);

                $chemicalMap = [];
                if (!empty($allChemicalIds)) {
                    $chemicalMap = \App\Models\Chemical::whereIn('id', array_values(array_unique($allChemicalIds)))
                        ->pluck('nama_chemical', 'id')
                        ->toArray();
                }

                $produsenMap = [];
                if (!empty($allProdusenIds)) {
                    $produsenMap = \App\Models\Produsen::whereIn('id', array_values(array_unique($allProdusenIds)))
                        ->pluck('nama_produsen', 'id')
                        ->toArray();
                }

                $distributorMap = [];
                if (!empty($allDistributorIds)) {
                    $distributorMap = \App\Models\Distributor::whereIn('id', array_values(array_unique($allDistributorIds)))
                        ->pluck('nama_distributor', 'id')
                        ->toArray();
                }
            @endphp
            
            @foreach($chunks as $pageIndex => $pageRecords)
                @php
                    $firstColumn = $pageRecords->first();
                    $firstRecord = $firstColumn ? $firstColumn['record'] : null;
                @endphp
                {{-- HEADER (Setiap halaman) --}}
                <div class="header">
                    <div class="header-left">
                        <div class="logo-company">
                            <div class="header-logo">
                                <img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo CPI">
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
                            <h1>PEMERIKSAAN CHEMICAL</h1>
                        </div>
                    </div>
                </div>

                {{-- SUBHEADER (Setiap halaman) --}}
                <div class="subheader">
                    <table class="subheader-table">
                        <tr>
                            <td>
                                <span class="subheader-label">Hari/Tanggal:</span>
                                <span class="subheader-value">{{ $firstRecord->tanggal ? (is_string($firstRecord->tanggal) ? $firstRecord->tanggal : $firstRecord->tanggal->format('d/m/Y')) : '-' }}</span>
                            </td>
                            <td class="subheader-divider"></td>
                            <td>
                                <span class="subheader-label">Shift:</span>
                                <span class="subheader-value">{{ $firstRecord->shift->shift ?? '-' }}</span>
                            </td>
                            <td class="subheader-divider"></td>
                            <td>
                                <span class="subheader-label">Jenis Mobil:</span>
                                <span class="subheader-value">{{ $firstRecord->jenis_mobil ?? '-' }}</span>
                            </td>
                            <td class="subheader-divider"></td>
                            <td>
                                <span class="subheader-label">No. Mobil:</span>
                                <span class="subheader-value">{{ $firstRecord->no_mobil ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="subheader-label">Segel/Gembok:</span>
                                <span class="subheader-value">{{ $firstRecord->segel_gembok ? ucfirst($firstRecord->segel_gembok) : '-' }}</span>
                            </td>
                            <td class="subheader-divider"></td>
                            <td>
                                <span class="subheader-label">No. Segel:</span>
                                <span class="subheader-value">{{ $firstRecord->no_segel ?? '-' }}</span>
                            </td>
                            <td class="subheader-divider"></td>
                            <td colspan="3">
                                <span class="subheader-label">Nama Supir:</span>
                                <span class="subheader-value">{{ $firstRecord->nama_supir ?? '-' }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- DATA TABLE --}}
                <div class="page-break">
                    <table class="data-table">
                        <tr>
                            @foreach($pageRecords as $index => $column)
                                @php
                                    $pemeriksaan = $column['record'];
                                    $rowIndex = $column['rowIndex'];
                                    $columnNumber = ($pageIndex * $columnsPerPage) + $loop->iteration;
                                @endphp
                                <td class="data-column" data-numbered="true">
                                    <div class="column-header">
                                        PEMERIKSAAN #{{ $columnNumber }}
                                    </div>

                                    {{-- KONDISI MOBIL --}}
                                    @php
                                        $kondisiMobil = $pemeriksaan->kondisi_mobil ?? [];
                                        $checkedItems = array_filter($kondisiMobil);
                                    @endphp
                                    @if(count($checkedItems) > 0)
                                        <div class="section-title">Kondisi Mobil</div>
                                        @foreach($checkedItems as $key => $value)
                                            @if($value)
                                                <div class="field-row">
                                                    <span class="check-item">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif

                                    {{-- DETAIL CHEMICALS (Single Row) --}}
                                    @php
                                        $detailChemicals = $pemeriksaan->detail_chemicals ?? [];
                                        $chemicalDetail = $detailChemicals[$rowIndex] ?? [];
                                    @endphp
                                    @if(!empty($chemicalDetail))
                                        {{-- CHEMICAL INFO --}}
                                        <div class="section-title">Chemical</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            @if(!empty($chemicalDetail['id_chemical']))
                                                <div class="field-row">
                                                    <span class="field-label">Nama:</span>
                                                    <span class="field-value">{{ $chemicalMap[$chemicalDetail['id_chemical']] ?? 'N/A' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($chemicalDetail['kondisi_chemical']))
                                                <div class="field-row">
                                                    <span class="field-label">Kondisi:</span>
                                                    <span class="field-value">{{ $chemicalDetail['kondisi_chemical'] ?? '-' }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($chemicalDetail['id_produsen']))
                                                <div class="field-row">
                                                    <span class="field-label">Produsen:</span>
                                                    <span class="field-value">{{ $produsenMap[$chemicalDetail['id_produsen']] ?? 'N/A' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($chemicalDetail['negara_produsen']))
                                                <div class="field-row">
                                                    <span class="field-label">Negara:</span>
                                                    <span class="field-value">{{ $chemicalDetail['negara_produsen'] ?? '-' }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($chemicalDetail['id_distributor']))
                                                <div class="field-row">
                                                    <span class="field-label">Dist:</span>
                                                    <span class="field-value">{{ $distributorMap[$chemicalDetail['id_distributor']] ?? 'N/A' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($chemicalDetail['kode_produksi']))
                                                <div class="field-row">
                                                    <span class="field-label">Kode:</span>
                                                    <span class="field-value">{{ $chemicalDetail['kode_produksi'] ?? '-' }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($chemicalDetail['expire_date']))
                                                <div class="field-row">
                                                    <span class="field-label">Expire:</span>
                                                    <span class="field-value">{{ \Carbon\Carbon::parse($chemicalDetail['expire_date'])->format('d/m/Y') }}</span>
                                                </div>
                                            @endif
                                            @if(isset($chemicalDetail['jumlah_datang']))
                                                <div class="field-row">
                                                    <span class="field-label">Jumlah:</span>
                                                    <span class="field-value">{{ $chemicalDetail['jumlah_datang'] ?? '-' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($chemicalDetail['jumlah_sampling']))
                                                <div class="field-row">
                                                    <span class="field-label">Samp:</span>
                                                    <span class="field-value">{{ $chemicalDetail['jumlah_sampling'] ?? '-' }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- KONDISI FISIK (Single Row) --}}
                                        @php
                                            $kondisiFisik = $chemicalDetail['kondisi_fisik'] ?? [];
                                        @endphp
                                        @if(!empty($kondisiFisik))
                                            <div class="section-title">Kondisi Fisik</div>
                                            <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                                @if(isset($kondisiFisik['kemasan']))
                                                    <div class="field-row">
                                                        <span class="field-label">Kemasan:</span>
                                                        <span class="field-value">{{ ($kondisiFisik['kemasan'] ?? false) ? 'V' : 'X' }}</span>
                                                    </div>
                                                @endif
                                                @if(isset($kondisiFisik['warna']))
                                                    <div class="field-row">
                                                        <span class="field-label">Warna:</span>
                                                        <span class="field-value">{{ ($kondisiFisik['warna'] ?? false) ? 'V' : 'X' }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- DOKUMEN (Single Row) --}}
                                        <div class="section-title">Dokumen</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            <div class="field-row">
                                                <span class="field-label">Halal:</span>
                                                <span class="field-value">{{ ($chemicalDetail['persyaratan_dokumen_halal'] ?? false) ? 'V' : 'X' }}</span>
                                            </div>
                                            <div class="field-row">
                                                <span class="field-label">COA:</span>
                                                <span class="field-value">{{ ($chemicalDetail['coa'] ?? false) ? 'V' : 'X' }}</span>
                                            </div>
                                        </div>

                                        {{-- STATUS (Single Row) --}}
                                        <div class="section-title">Status</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            <div class="field-row">
                                                <span class="field-label">Status:</span>
                                                <span class="field-value">
                                                    @php
                                                        $status = $chemicalDetail['status'] ?? null;
                                                    @endphp
                                                    @if(strtolower($status ?? '') == 'release')
                                                        <span class="status-badge status-release">{{ $status ?? '-' }}</span>
                                                    @elseif(strtolower($status ?? '') == 'hold')
                                                        <span class="status-badge status-hold">{{ $status ?? '-' }}</span>
                                                    @else
                                                        {{ $status ?? '-' }}
                                                    @endif
                                                </span>
                                            </div>
                                            @if(isset($chemicalDetail['keterangan']))
                                                <div class="field-row">
                                                    <span class="field-label">Ket:</span>
                                                    <span class="field-value">{{ $chemicalDetail['keterangan'] ?? '-' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                </td>
                            @endforeach
                        </tr>
                    </table>
                </div>

                {{-- SIGNATURE (Setiap halaman) --}}
                <div class="signature-section">
                    <div class="signature-note">
                        <span class="ok-text">V OK</span> (Kondisi Mobil, Kemasan, Warna, Benda Asing, Aroma: Sesuai Standar, Halal, COA: Tersedia)<br>
                        <span class="not-ok-text">X</span> : Parameter Tidak Sesuai
                    </div>
                    
                    @php
                        $firstRecord = $pemeriksaans->first();
                    @endphp
                    <table class="signature-table">
                        <tr>
                            <td class="signature-cell">
                                <div class="signature-header-item">Dibuat Oleh:</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->qcVerifier)
                                        @php
                                            $qcQrData = "Dokumen #{$firstRecord->id} telah diverifikasi secara sistem oleh {$firstRecord->qcVerifier->name} (Tim QC)";
                                            $qcQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($qcQrData);
                                            $base64QcSvg = "data:image/svg+xml;base64," . base64_encode($qcQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64QcSvg }}" class="qr-code-img" alt="QR Code QC">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $firstRecord && $firstRecord->qcVerifier ? $firstRecord->qcVerifier->name : '-' }}</div>
                            </td>
                            <td class="signature-cell">
                                <div class="signature-header-item">Diperiksa Oleh:</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->produksiVerifier)
                                        @php
                                            $prodQrData = "Dokumen #{$firstRecord->id} telah diverifikasi secara sistem oleh {$firstRecord->produksiVerifier->name} (Tim Warehouse)";
                                            $prodQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($prodQrData);
                                            $base64ProdSvg = "data:image/svg+xml;base64," . base64_encode($prodQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64ProdSvg }}" class="qr-code-img" alt="QR Code Warehouse">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $firstRecord && $firstRecord->produksiVerifier ? $firstRecord->produksiVerifier->name : '-' }}</div>
                            </td>
                            <td class="signature-cell">
                                <div class="signature-header-item">Diketahui Oleh:</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->spvVerifier)
                                        @php
                                            $spvQrData = "Dokumen #{$firstRecord->id} telah diverifikasi secara sistem oleh {$firstRecord->spvVerifier->name} (Tim Supervisor QC)";
                                            $spvQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($spvQrData);
                                            $base64SpvSvg = "data:image/svg+xml;base64," . base64_encode($spvQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64SpvSvg }}" class="qr-code-img" alt="QR Code SPV">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $firstRecord && $firstRecord->spvVerifier ? $firstRecord->spvVerifier->name : '-' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
                    QW 03/00
                </div>

                {{-- PAGE BREAK (Hanya jika bukan halaman terakhir) --}}
                @if(!$loop->last)
                    <div style="page-break-after: always;"></div>
                @endif
            @endforeach
        @else
            <div class="empty-message">
                <p>Tidak ada data pemeriksaan yang sesuai dengan filter yang dipilih.</p>
            </div>
        @endif
    </div>
</body>
</html>
