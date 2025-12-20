<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Kedatangan Kemasan</title>
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
        
        /* HEADER - Improved */
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

        /* SUBHEADER - Grid Layout Fixed */
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

        /* DATA TABLE - 4 Column Layout */
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
        
        .data-column[data-numbered="true"] {
            counter-reset: section-counter;
        }
        
        .data-column:not([data-numbered="true"]) .section-title::before {
            content: "";
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
        
        .data-column[data-numbered="true"] .section-title {
            counter-increment: section-counter;
        }
        
        .data-column[data-numbered="true"] .section-title::before {
            content: counter(section-counter) ". ";
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
        {{-- HEADER --}}
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
                    <h1>PEMERIKSAAN KEDATANGAN KEMASAN</h1>
                </div>
            </div>
        </div>

        @if($pemeriksaans->count() > 0)
            @php
                $recordsPerPage = 4;
                $chunks = $pemeriksaans->chunk($recordsPerPage);
                $firstRecord = $pemeriksaans->first();
            @endphp
            
            @foreach($chunks as $pageIndex => $pageRecords)
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

                {{-- DATA UTAMA --}}
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

                                    {{-- JENIS PEMERIKSAAN --}}
                                    @php
                                        $kondisiMobilRaw = $pemeriksaan->kondisi_mobil ?? [];
                                        if (is_string($kondisiMobilRaw)) {
                                            $decoded = json_decode($kondisiMobilRaw, true);
                                            $kondisiMobil = is_array($decoded) ? $decoded : [];
                                        } elseif (is_array($kondisiMobilRaw)) {
                                            $kondisiMobil = $kondisiMobilRaw;
                                        } else {
                                            $kondisiMobil = [];
                                        }

                                        $checkedItems = array_filter($kondisiMobil);
                                    @endphp
                                    @if(count($checkedItems) > 0)
                                        <div class="section-title">Jenis Pemeriksaan</div>
                                        @foreach($checkedItems as $key => $value)
                                            @if($value)
                                                <div class="field-row">
                                                    <span class="check-item">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif

                                    {{-- BAHAN KEMASAN (Dynamic Rows) --}}
                                    @php
                                        $id_bahans = json_decode($pemeriksaan->id_bahan_array ?? '[]', true) ?? [];
                                        $produsens_arr = json_decode($pemeriksaan->produsen_array ?? '[]', true) ?? [];
                                        $distributors_arr = json_decode($pemeriksaan->distributor_array ?? '[]', true) ?? [];
                                        $kode_produksis = json_decode($pemeriksaan->kode_produksi_array ?? '[]', true) ?? [];
                                        $jumlah_datangs = json_decode($pemeriksaan->jumlah_datang_array ?? '[]', true) ?? [];
                                        $jumlah_samplings = json_decode($pemeriksaan->jumlah_sampling_array ?? '[]', true) ?? [];
                                        $spesifikasis = json_decode($pemeriksaan->spesifikasi_array ?? '[]', true) ?? [];
                                    @endphp
                                    @if(count($id_bahans) > 0 || $pemeriksaan->no_po)
                                        <div class="section-title">Bahan Kemasan</div>
                                        @if($pemeriksaan->no_po)
                                            <div class="field-row">
                                                <span class="field-label">No. PO:</span>
                                                <span class="field-value">{{ $pemeriksaan->no_po }}</span>
                                            </div>
                                        @endif
                                        @forelse($id_bahans as $index => $id_bahan)
                                            <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                                <strong>Baris {{ $index + 1 }}:</strong>
                                                @if($id_bahan)
                                                    Bahan: {{ \App\Models\Bahan::find($id_bahan)->nama_bahan ?? 'N/A' }}
                                                @endif
                                                @if($produsens_arr[$index] ?? null)
                                                    | Produsen: {{ $produsens_arr[$index] }}
                                                @endif
                                                @if($distributors_arr[$index] ?? null)
                                                    | Distributor: {{ $distributors_arr[$index] }}
                                                @endif
                                                @if($kode_produksis[$index] ?? null)
                                                    | Kode Produksi: {{ $kode_produksis[$index] }}
                                                @endif
                                                @if($jumlah_datangs[$index] ?? null)
                                                    | Jml Datang: {{ $jumlah_datangs[$index] }}
                                                @endif
                                                @if($jumlah_samplings[$index] ?? null)
                                                    | Jml Sampling: {{ $jumlah_samplings[$index] }}
                                                @endif
                                                @if($spesifikasis[$index] ?? null)
                                                    | Spesifikasi: {{ substr($spesifikasis[$index], 0, 30) }}{{ strlen($spesifikasis[$index]) > 30 ? '...' : '' }}
                                                @endif
                                            </div>
                                        @empty
                                        @endforelse
                                    @endif

                                    {{-- KONDISI FISIK (Dynamic Rows) --}}
                                    @php
                                        $penampakans = json_decode($pemeriksaan->penampakan_array ?? '[]', true) ?? [];
                                        $sealings = json_decode($pemeriksaan->sealing_array ?? '[]', true) ?? [];
                                        $cetakans = json_decode($pemeriksaan->cetakan_array ?? '[]', true) ?? [];
                                        $ketebalan_microns = json_decode($pemeriksaan->ketebalan_micron_array ?? '[]', true) ?? [];
                                        $dimensis = json_decode($pemeriksaan->dimensi_array ?? '[]', true) ?? [];
                                    @endphp
                                    @if(count($penampakans) > 0 || count($sealings) > 0 || count($cetakans) > 0)
                                        <div class="section-title">Kondisi Fisik</div>
                                        @forelse($penampakans as $index => $penampakan)
                                            <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                                <strong>Baris {{ $index + 1 }}:</strong>
                                                Penampakan: {{ $penampakan ? 'V' : 'X' }}
                                                | Sealing: {{ ($sealings[$index] ?? null) ? 'V' : 'X' }}
                                                | Cetakan: {{ ($cetakans[$index] ?? null) ? 'V' : 'X' }}
                                                @if($ketebalan_microns[$index] ?? null)
                                                    | Ketebalan: {{ $ketebalan_microns[$index] }}
                                                @endif
                                                @if($dimensis[$index] ?? null)
                                                    | Dimensi: {{ $dimensis[$index] }}
                                                @endif
                                            </div>
                                        @empty
                                        @endforelse
                                    @endif

                                    {{-- DOKUMEN (Dynamic Rows) --}}
                                    @php
                                        $logo_halals = json_decode($pemeriksaan->logo_halal_array ?? '[]', true) ?? [];
                                        $dokumen_halals = json_decode($pemeriksaan->dokumen_halal_array ?? '[]', true) ?? [];
                                        $coas = json_decode($pemeriksaan->coa_array ?? '[]', true) ?? [];
                                        $keterangans = json_decode($pemeriksaan->keterangan_array ?? '[]', true) ?? [];
                                        $statuses = json_decode($pemeriksaan->status_array ?? '[]', true) ?? [];
                                    @endphp
                                    @if(count($logo_halals) > 0 || count($dokumen_halals) > 0 || count($coas) > 0)
                                        <div class="section-title">Dokumen</div>
                                        @forelse($logo_halals as $index => $logo_halal)
                                            <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                                <strong>Baris {{ $index + 1 }}:</strong>
                                                Logo Halal: {{ $logo_halal ? 'V' : 'X' }}
                                                | Halal Berlaku: {{ ($dokumen_halals[$index] ?? null) ? 'V' : 'X' }}
                                                | COA: {{ ($coas[$index] ?? null) ? 'V' : 'X' }}
                                                @if($statuses[$index] ?? null)
                                                    | Status: {{ $statuses[$index] }}
                                                @endif
                                                @if($keterangans[$index] ?? null)
                                                    | Keterangan: {{ substr($keterangans[$index], 0, 25) }}{{ strlen($keterangans[$index]) > 25 ? '...' : '' }}
                                                @endif
                                            </div>
                                        @empty
                                        @endforelse
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
                        QW 02/00
                    </div>
                </div>

                {{-- SIGNATURE (Setiap halaman) --}}
                <div class="signature-section">
                    <div class="signature-note">
                        <span class="ok-text">V OK</span> (Segel/Gembok: Tersedia, Penampakan, Sealing, Cetakan: Sesuai Standar, Logo Halal, Halal Berlaku, COA: Tersedia)<br>
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

                <!-- {{-- FOOTER (Setiap halaman) --}}
                <div class="footer">
                    <p class="footer-main">Total Data: {{ $pemeriksaans->count() }} | Dihasilkan: {{ now()->format('d/m/Y H:i:s') }}</p>
                    <p>Dokumen ini adalah laporan resmi dari PT. Charoen Pokphand Indonesia - Food Division</p>
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
