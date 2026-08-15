<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Kedatangan Bahan Kimia</title>
    @php
        $firstRecord = $pemeriksaans->first();
        $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
    @endphp
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            line-height: 1.2;
            color: #1a1a1a;
            background: #fff;
        }
        
        .container {
            width: 100%;
        }
        
        /* HEADER - Menggunakan Tabel Standar DomPDF */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #c41e3a;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .header-logo-td {
            width: 55px;
            vertical-align: middle;
        }
        
        .header-logo-td img {
            width: 48px;
            height: auto;
        }
        
        .header-company-td {
            vertical-align: middle;
            padding-left: 5px;
        }
        
        .header-company-td h2 {
            font-size: 11px;
            font-weight: bold;
            color: #c41e3a;
        }
        
        .header-company-td p {
            font-size: 7.5px;
            color: #444;
        }
        
        .header-title-td {
            vertical-align: middle;
            text-align: right;
            width: 45%;
        }
        
        .header-title-box {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            background: #e9ecef;
            padding: 6px 10px;
            border-left: 4px solid #c41e3a;
            display: inline-block;
            text-align: right;
        }

        /* SUBHEADER */
        .subheader {
            width: 100%;
            border: 1px solid #dee2e6;
            margin-bottom: 10px;
            background: #f8f9fa;
        }
        
        .subheader-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .subheader-table td {
            padding: 4px 6px;
            font-size: 7.5px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }
        
        .subheader-label {
            font-weight: bold;
            color: #495057;
        }
        
        .subheader-value {
            color: #1a1a1a;
        }
        
        .subheader-divider {
            width: 1px;
            background: #dee2e6;
            padding: 0;
        }

        /* DATA TABLE - 4 Kolom Menggunakan Tabel Murni */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
        }
        
        .data-column {
            width: 25%;
            border: 1px solid #dee2e6;
            padding: 5px;
            vertical-align: top;
            font-size: 7.5px;
            background: #fff;
        }
        
        .column-header {
            font-weight: bold;
            font-size: 8.5px;
            color: #ffffff;
            background: #8b1428;
            padding: 4px;
            margin: -5px -5px 6px -5px;
            text-align: center;
            text-transform: uppercase;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 7.5px;
            color: #c41e3a;
            border-bottom: 1px solid #c41e3a;
            margin-top: 6px;
            margin-bottom: 4px;
            padding-bottom: 2px;
            text-transform: uppercase;
        }
        
        .field-row {
            margin-bottom: 2px;
            width: 100%;
        }
        
        .field-label {
            font-weight: bold;
            color: #495057;
            display: inline-block;
            width: 50px;
        }
        
        .field-value {
            color: #1a1a1a;
            word-wrap: break-word;
        }
        
        .check-item {
            color: #28a745;
            font-weight: bold;
        }

        /* SIGNATURE SECTION */
        .signature-section {
            margin-top: 8px;
            padding: 8px;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
        }
        
        .signature-note {
            font-size: 7px;
            color: #495057;
            padding: 4px 6px;
            background: #fff;
            border: 1px solid #e9ecef;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        
        .signature-note .ok-text {
            color: #28a745;
            font-weight: bold;
        }
        
        .signature-note .not-ok-text {
            color: #dc3545;
            font-weight: bold;
        }
        
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .signature-cell {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 5px;
        }
        
        .signature-header-item {
            font-size: 7.5px;
            font-weight: bold;
            color: #495057;
            padding-bottom: 5px;
        }
        
        .signature-space {
            height: 50px;
            text-align: center;
        }

        .signature-line-empty {
            border-bottom: 1px solid #1a1a1a;
            height: 35px;
            width: 80%;
            margin: 0 auto;
        }
        
        .qr-code-img {
            max-height: 50px;
            max-width: 50px;
        }
        
        .signature-name {
            font-size: 7.5px;
            font-weight: bold;
            color: #1a1a1a;
            padding-top: 4px;
            text-transform: uppercase;
        }

        /* STATUS BADGES */
        .status-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
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

        .empty-message {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #6c757d;
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        @php
            $isAllShift = $isAllShift ?? false;
            $dataPerShift = $dataPerShift ?? [['pemeriksaans' => $pemeriksaans ?? collect()]];
            $isFirstPage = true;
        @endphp

        @if(empty($dataPerShift))
            <div class="empty-message">
                <p>Tidak ada data pemeriksaan untuk semua shift pada periode yang dipilih.</p>
            </div>
        @else
            @foreach($dataPerShift as $shiftGroupIndex => $shiftGroup)
                @php
                    $pemeriksaans   = $shiftGroup['pemeriksaans'];
                    $qcUser         = $shiftGroup['qcUser'] ?? null;
                    $produksiUser   = $shiftGroup['produksiUser'] ?? null;
                    $spvQcUser      = $shiftGroup['spvQcUser'] ?? null;
                @endphp

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

                @if(!$isFirstPage)
                    <div style="page-break-before: always;"></div>
                @endif
                @php $isFirstPage = false; @endphp

                {{-- HEADER - DICETAK DI SETIAP HALAMAN --}}
                <table class="header-table">
                    <tr>
                        <td class="header-logo-td">
                            <img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo CPI">
                        </td>
                        <td class="header-company-td">
                            <h2>PT. CHAROEN POKPHAND INDONESIA</h2>
                            @php
                                $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
                            @endphp
                            <p>FOOD DIVISION {{ strtoupper($plantName) }}</p>
                            <p>{{ strtoupper($plantName) }} - INDONESIA</p>
                        </td>
                        <td class="header-title-td">
                            <div class="header-title-box">
                                PEMERIKSAAN KEDATANGAN BAHAN KIMIA
                            </div>
                        </td>
                    </tr>
                </table>

                {{-- SUBHEADER --}}
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
                                <span class="subheader-value">{{ $firstRecord && $firstRecord->segel_gembok ? ucfirst($firstRecord->segel_gembok) : '-' }}</span>
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

                {{-- DATA UTAMA (4 Kolom Tabel Murni) --}}
                <table class="grid-table">
                    <tr>
                        @foreach($pageRecords as $index => $column)
                            @php
                                $pemeriksaan = $column['record'];
                                $rowIndex = $column['rowIndex'];
                                $columnNumber = ($pageIndex * $columnsPerPage) + $loop->iteration;
                            @endphp
                            <td class="data-column">
                                <div class="column-header">
                                    PEMERIKSAAN #{{ $columnNumber }}
                                </div>

                                {{-- KONDISI MOBIL --}}
                                @php
                                    $kondisiMobil = $pemeriksaan->kondisi_mobil ?? [];
                                    $checkedItems = array_filter($kondisiMobil);
                                @endphp
                                @if(count($checkedItems) > 0)
                                    <div class="section-title">1. Kondisi Mobil</div>
                                    @foreach($checkedItems as $key => $value)
                                        @if($value)
                                            <div class="field-row">
                                                <span class="check-item">V {{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                                {{-- DETAIL CHEMICALS --}}
                                @php
                                    $detailChemicals = $pemeriksaan->detail_chemicals ?? [];
                                    $chemicalDetail = $detailChemicals[$rowIndex] ?? [];
                                @endphp
                                @if(!empty($chemicalDetail))
                                    <div class="section-title">2. Chemical</div>
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
                                            <span class="field-label">Kode Prod:</span>
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
                                            <span class="field-label">Jml Datang:</span>
                                            <span class="field-value">{{ $chemicalDetail['jumlah_datang'] ?? '-' }} @if(isset($chemicalDetail['unit_datang']) && is_array($chemicalDetail['unit_datang']) && isset($chemicalDetail['unit_datang'][0]))<strong>{{ $chemicalDetail['unit_datang'][0] }}</strong>@endif</span>
                                        </div>
                                    @endif
                                    @if(isset($chemicalDetail['jumlah_sampling']))
                                        <div class="field-row">
                                            <span class="field-label">Jml Sampling:</span>
                                            <span class="field-value">{{ $chemicalDetail['jumlah_sampling'] ?? '-' }} @if(isset($chemicalDetail['unit_sampling']) && is_array($chemicalDetail['unit_sampling']) && isset($chemicalDetail['unit_sampling'][0]))<strong>{{ $chemicalDetail['unit_sampling'][0] }}</strong>@endif</span>
                                        </div>
                                    @endif

                                    {{-- KONDISI FISIK --}}
                                    @php
                                        $kondisiFisik = $chemicalDetail['kondisi_fisik'] ?? [];
                                    @endphp
                                    @if(!empty($kondisiFisik))
                                        <div class="section-title">3. Kondisi Fisik</div>
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
                                    @endif

                                    {{-- DOKUMEN --}}
                                    <div class="section-title">4. Dokumen</div>
                                    <div class="field-row">
                                        <span class="field-label">Halal:</span>
                                        <span class="field-value">{{ ($chemicalDetail['persyaratan_dokumen_halal'] ?? false) ? 'V' : 'X' }}</span>
                                    </div>
                                    <div class="field-row">
                                        <span class="field-label">COA:</span>
                                        <span class="field-value">{{ ($chemicalDetail['coa'] ?? false) ? 'V' : 'X' }}</span>
                                    </div>

                                    {{-- STATUS --}}
                                    <div class="section-title">5. Status</div>
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
                                @endif
                            </td>
                        @endforeach
                        
                        {{-- Isi kolom kosong jika data kurang dari 4 --}}
                        @for($i = $pageRecords->count(); $i < $columnsPerPage; $i++)
                            <td class="data-column" style="background: #f8f9fa;"></td>
                        @endfor
                    </tr>
                </table>

                <div style="text-align: right; font-style: italic; font-size: 8px; color: #666; margin-bottom: 5px;">
                    QW 03/00
                </div>

                {{-- SIGNATURE SECTION --}}
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
                            <!-- 1. Dibuat Oleh (QC VERIFIER) -->
                            <td class="signature-cell">
                                <div class="signature-header-item">Dibuat Oleh:</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->qcVerifier)
                                        @php
                                            $qcQrData = "Dokumen #{$firstRecord->id} telah diverifikasi secara sistem oleh {$firstRecord->qcVerifier->name} (Tim QC)";
                                            $qcQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(45)->generate($qcQrData);
                                            $base64QcSvg = "data:image/svg+xml;base64," . base64_encode($qcQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64QcSvg }}" class="qr-code-img" alt="QR Code QC">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $firstRecord && $firstRecord->qcVerifier ? $firstRecord->qcVerifier->name : '-' }}</div>
                            </td>

                            <!-- 2. Diketahui Oleh (PRODUKSI/WAREHOUSE VERIFIER) -->
                            <td class="signature-cell">
                                <div class="signature-header-item">Diketahui Oleh:</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->produksiVerifier)
                                        @php
                                            $prodQrData = "Dokumen #{$firstRecord->id} telah diverifikasi secara sistem oleh {$firstRecord->produksiVerifier->name} (Tim Warehouse)";
                                            $prodQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(45)->generate($prodQrData);
                                            $base64ProdSvg = "data:image/svg+xml;base64," . base64_encode($prodQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64ProdSvg }}" class="qr-code-img" alt="QR Code Warehouse">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $firstRecord && $firstRecord->produksiVerifier ? $firstRecord->produksiVerifier->name : '-' }}</div>
                            </td>

                            <!-- 3. Disetujui Oleh (SPV VERIFIER) -->
                            <td class="signature-cell">
                                <div class="signature-header-item">Disetujui Oleh:</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->spvVerifier)
                                        @php
                                            $spvQrData = "Dokumen #{$firstRecord->id} telah diverifikasi secara sistem oleh {$firstRecord->spvVerifier->name} (Tim Supervisor QC)";
                                            $spvQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(45)->generate($spvQrData);
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

            @endforeach
        @else
            <div class="empty-message">
                <p>Tidak ada data pemeriksaan yang sesuai dengan filter yang dipilih.</p>
            </div>
        @endif
            @endforeach
        @endif
    </div>
</body>
</html>