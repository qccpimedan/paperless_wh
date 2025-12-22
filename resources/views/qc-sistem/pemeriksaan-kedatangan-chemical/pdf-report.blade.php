<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Kedatangan Chemical</title>
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
            border-bottom: 2px solid #1a1a1a;
            height: 40px;
            margin: 0 10px;
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
                $recordsPerPage = 4;
                $chunks = $pemeriksaans->chunk($recordsPerPage);
                $firstRecord = $pemeriksaans->first();
            @endphp
            
            @foreach($chunks as $pageIndex => $pageRecords)
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
                            @foreach($pageRecords as $index => $pemeriksaan)
                                @php
                                    $columnNumber = ($pageIndex * $recordsPerPage) + $loop->iteration;
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

                                    {{-- DETAIL CHEMICALS (dari JSON) --}}
                                    @php
                                        $detailChemicals = $pemeriksaan->detail_chemicals ?? [];
                                    @endphp
                                    @if(count($detailChemicals) > 0)
                                        @php
                                            $rowCount = count($detailChemicals);
                                            $chemicalIds = [];
                                            $produsenIds = [];
                                            $distributorIds = [];
                                            foreach ($detailChemicals as $d) {
                                                if (!empty($d['id_chemical'])) $chemicalIds[] = $d['id_chemical'];
                                                if (!empty($d['id_produsen'])) $produsenIds[] = $d['id_produsen'];
                                                if (!empty($d['id_distributor'])) $distributorIds[] = $d['id_distributor'];
                                            }
                                            $chemicalNameById = [];
                                            $produsenNameById = [];
                                            $distributorNameById = [];
                                            if (count($chemicalIds) > 0) {
                                                $models = \App\Models\Chemical::whereIn('id', array_unique($chemicalIds))->get(['id', 'nama_chemical']);
                                                foreach ($models as $m) $chemicalNameById[$m->id] = $m->nama_chemical;
                                            }
                                            if (count($produsenIds) > 0) {
                                                $models = \App\Models\Produsen::whereIn('id', array_unique($produsenIds))->get(['id', 'nama_produsen']);
                                                foreach ($models as $m) $produsenNameById[$m->id] = $m->nama_produsen;
                                            }
                                            if (count($distributorIds) > 0) {
                                                $models = \App\Models\Distributor::whereIn('id', array_unique($distributorIds))->get(['id', 'nama_distributor']);
                                                foreach ($models as $m) $distributorNameById[$m->id] = $m->nama_distributor;
                                            }
                                        @endphp

                                        {{-- CHEMICAL INFO --}}
                                        <div class="section-title">Chemical</div>
                                        <div class="field-row">
                                            <span class="field-label">Nama:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    @php
                                                        $cid = $detailChemicals[$i]['id_chemical'] ?? null;
                                                        $cname = $cid && isset($chemicalNameById[$cid]) ? $chemicalNameById[$cid] : '-';
                                                    @endphp
                                                    <div>Row {{ $i + 1 }}: {{ $cname }}</div>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Kondisi:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    <div>Row {{ $i + 1 }}: {{ $detailChemicals[$i]['kondisi_chemical'] ?? '-' }}</div>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Produsen:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    @php
                                                        $pid = $detailChemicals[$i]['id_produsen'] ?? null;
                                                        $pname = $pid && isset($produsenNameById[$pid]) ? $produsenNameById[$pid] : '-';
                                                    @endphp
                                                    <div>Row {{ $i + 1 }}: {{ $pname }}</div>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Negara:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    <div>Row {{ $i + 1 }}: {{ $detailChemicals[$i]['negara_produsen'] ?? '-' }}</div>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Dist:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    @php
                                                        $did = $detailChemicals[$i]['id_distributor'] ?? null;
                                                        $dname = $did && isset($distributorNameById[$did]) ? $distributorNameById[$did] : '-';
                                                    @endphp
                                                    <div>Row {{ $i + 1 }}: {{ $dname }}</div>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Kode:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    <div>Row {{ $i + 1 }}: {{ $detailChemicals[$i]['kode_produksi'] ?? '-' }}</div>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Expire:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    @php
                                                        $exp = $detailChemicals[$i]['expire_date'] ?? null;
                                                        $expFormatted = $exp ? \Carbon\Carbon::parse($exp)->format('d/m/Y') : '-';
                                                    @endphp
                                                    <div>Row {{ $i + 1 }}: {{ $expFormatted }}</div>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Jumlah:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    <div>Row {{ $i + 1 }}: {{ $detailChemicals[$i]['jumlah_datang'] ?? '-' }}</div>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Samp:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    <div>Row {{ $i + 1 }}: {{ $detailChemicals[$i]['jumlah_sampling'] ?? '-' }}</div>
                                                @endfor
                                            </span>
                                        </div>

                                        {{-- KONDISI FISIK --}}
                                        <div class="section-title" style="font-size: 7px; margin-top: 6px;">Kondisi Fisik</div>
                                        <div class="field-row">
                                            <span class="field-label">Kemasan:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    @php
                                                        $kf = $detailChemicals[$i]['kondisi_fisik']['kemasan'] ?? null;
                                                        $kfDisplay = $kf === null ? '-' : ($kf ? 'V' : 'X');
                                                    @endphp
                                                    <div>Row {{ $i + 1 }}: {{ $kfDisplay }}</div>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Warna:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    @php
                                                        $kw = $detailChemicals[$i]['kondisi_fisik']['warna'] ?? null;
                                                        $kwDisplay = $kw === null ? '-' : ($kw ? 'V' : 'X');
                                                    @endphp
                                                    <div>Row {{ $i + 1 }}: {{ $kwDisplay }}</div>
                                                @endfor
                                            </span>
                                        </div>

                                        {{-- DOKUMEN --}}
                                        <div class="section-title" style="font-size: 7px; margin-top: 6px;">Dokumen</div>
                                        <div class="field-row">
                                            <span class="field-label">Halal:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    <div>Row {{ $i + 1 }}: {{ ($detailChemicals[$i]['persyaratan_dokumen_halal'] ?? false) ? 'V' : 'X' }}</div>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">COA:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    <div>Row {{ $i + 1 }}: {{ ($detailChemicals[$i]['coa'] ?? false) ? 'V' : 'X' }}</div>
                                                @endfor
                                            </span>
                                        </div>

                                        {{-- STATUS --}}
                                        <div class="section-title" style="font-size: 7px; margin-top: 6px;">Status</div>
                                        <div class="field-row">
                                            <span class="field-label">Status:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    <div>
                                                        Row {{ $i + 1 }}:
                                                        @php
                                                            $st = $detailChemicals[$i]['status'] ?? null;
                                                        @endphp
                                                        @if(strtolower($st ?? '') == 'release')
                                                            <span class="status-badge status-release">{{ $st ?? '-' }}</span>
                                                        @elseif(strtolower($st ?? '') == 'hold')
                                                            <span class="status-badge status-hold">{{ $st ?? '-' }}</span>
                                                        @else
                                                            {{ $st ?? '-' }}
                                                        @endif
                                                    </div>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Ket:</span>
                                            <span class="field-value">
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    <div>Row {{ $i + 1 }}: {{ $detailChemicals[$i]['keterangan'] ?? '-' }}</div>
                                                @endfor
                                            </span>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                            
                            {{-- Fill empty columns if less than 4 records --}}
                            @for($i = $pageRecords->count(); $i < $recordsPerPage; $i++)
                                <td class="data-column" style="background: #f8f9fa;"></td>
                            @endfor
                        </tr>
                    </table>
                    <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
                        QW 03/00
                    </div>
                </div>

                {{-- SIGNATURE (Setiap halaman) --}}
                <div class="signature-section">
                    <div class="signature-note">
                        <span class="ok-text">V OK</span> (Kondisi Mobil, Kemasan, Warna, Benda Asing, Aroma: Sesuai Standar, Halal, COA: Tersedia)<br>
                        <span class="not-ok-text">X</span> : Parameter Tidak Sesuai
                    </div>
                    
                    <table class="signature-table">
                        <tr>
                            <td class="signature-cell">
                                <div class="signature-header-item">Dibuat Oleh:</div>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $qcUser ?? 'QC' }}</div>
                            </td>
                            <td class="signature-cell">
                                <div class="signature-header-item">Diperiksa Oleh:</div>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $produksiUser ?? 'Produksi' }}</div>
                            </td>
                            <td class="signature-cell">
                                <div class="signature-header-item">Diketahui Oleh:</div>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $spvQcUser ?? 'SPV QC' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
<!-- 
                {{-- FOOTER (Setiap halaman) --}}
                <div class="footer">
                    <p>PT. CHAROEN POKPHAND INDONESIA</p>
                    <p>FOOD DIVISION MEDAN</p>
                    <p>MEDAN - INDONESIA</p>
                    <p class="footer-main">QW 02/02</p>
                </div> -->

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
