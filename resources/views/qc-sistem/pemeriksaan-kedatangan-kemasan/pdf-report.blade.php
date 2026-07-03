<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Kedatangan Kemasan</title>
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
        {{-- HEADER --}}
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
                    <h1>PEMERIKSAAN KEDATANGAN KEMASAN</h1>
                </div>
            </div>
        </div>

        @if($pemeriksaans->count() > 0)
            @php
                $columnsPerPage = 4;

                $allBahanIds = [];

                $pdfColumns = collect();
                foreach ($pemeriksaans as $p) {
                    $idBahansTmp = json_decode($p->id_bahan_array ?? '[]', true) ?? [];

                    if (!empty($idBahansTmp)) {
                        foreach ($idBahansTmp as $tmpId) {
                            if ($tmpId) {
                                $allBahanIds[] = $tmpId;
                            }
                        }
                    }

                    $produsensTmp = json_decode($p->produsen_array ?? '[]', true) ?? [];
                    $distributorsTmp = json_decode($p->distributor_array ?? '[]', true) ?? [];
                    $kodeProduksisTmp = json_decode($p->kode_produksi_array ?? '[]', true) ?? [];
                    $jumlahDatangsTmp = json_decode($p->jumlah_datang_array ?? '[]', true) ?? [];
                    $jumlahSamplingsTmp = json_decode($p->jumlah_sampling_array ?? '[]', true) ?? [];
                    $spesifikasisTmp = json_decode($p->spesifikasi_array ?? '[]', true) ?? [];
                    $penampakansTmp = json_decode($p->penampakan_array ?? '[]', true) ?? [];
                    $sealingsTmp = json_decode($p->sealing_array ?? '[]', true) ?? [];
                    $cetakansTmp = json_decode($p->cetakan_array ?? '[]', true) ?? [];
                    $ketebalanMicronsTmp = json_decode($p->ketebalan_micron_array ?? '[]', true) ?? [];
                    $dimensisTmp = json_decode($p->dimensi_array ?? '[]', true) ?? [];
                    $statusesTmp = json_decode($p->status_array ?? '[]', true) ?? [];
                    $logoHalalsTmp = json_decode($p->logo_halal_array ?? '[]', true) ?? [];
                    $dokumenHalalsTmp = json_decode($p->dokumen_halal_array ?? '[]', true) ?? [];
                    $coasTmp = json_decode($p->coa_array ?? '[]', true) ?? [];
                    $keterangansTmp = json_decode($p->keterangan_array ?? '[]', true) ?? [];
                    $imageKemasansTmp = json_decode($p->image_kemasan_array ?? '[]', true) ?? [];

                    $rowCount = max(
                        1,
                        count($idBahansTmp),
                        count($produsensTmp),
                        count($distributorsTmp),
                        count($kodeProduksisTmp),
                        count($jumlahDatangsTmp),
                        count($jumlahSamplingsTmp),
                        count($spesifikasisTmp),
                        count($penampakansTmp),
                        count($sealingsTmp),
                        count($cetakansTmp),
                        count($ketebalanMicronsTmp),
                        count($dimensisTmp),
                        count($statusesTmp),
                        count($logoHalalsTmp),
                        count($dokumenHalalsTmp),
                        count($coasTmp),
                        count($keterangansTmp),
                        count($imageKemasansTmp)
                    );

                    for ($i = 0; $i < $rowCount; $i++) {
                        $pdfColumns->push([
                            'record' => $p,
                            'rowIndex' => $i,
                        ]);
                    }
                }

                $chunks = $pdfColumns->chunk($columnsPerPage);

                $bahanMap = [];
                if (!empty($allBahanIds)) {
                    $bahanMap = \App\Models\Produk::whereIn('id', array_values(array_unique($allBahanIds)))
                        ->pluck('nama_produk', 'id')
                        ->toArray();
                }
            @endphp
            
            @foreach($chunks as $pageIndex => $pageRecords)
                @php
                    $firstColumn = $pageRecords->first();
                    $firstRecord = $firstColumn ? $firstColumn['record'] : null;
                @endphp
                {{-- SUBHEADER (Setiap halaman) --}}
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

                {{-- DATA UTAMA --}}
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

                                        $id_bahan = $id_bahans[$rowIndex] ?? null;
                                        $produsen_val = $produsens_arr[$rowIndex] ?? null;
                                        $distributor_val = $distributors_arr[$rowIndex] ?? null;
                                        $kode_produksi_val = $kode_produksis[$rowIndex] ?? null;
                                        $jumlah_datang_val = $jumlah_datangs[$rowIndex] ?? null;
                                        $jumlah_sampling_val = $jumlah_samplings[$rowIndex] ?? null;
                                        $spesifikasi_val = $spesifikasis[$rowIndex] ?? null;

                                        if (is_array($produsen_val)) {
                                            $produsen_val = implode(', ', array_values(array_filter($produsen_val, fn ($v) => $v !== null && $v !== '')));
                                        }
                                        if (is_array($distributor_val)) {
                                            $distributor_val = implode(', ', array_values(array_filter($distributor_val, fn ($v) => $v !== null && $v !== '')));
                                        }
                                    @endphp
                                    @if($id_bahan || $produsen_val || $distributor_val || $kode_produksi_val || $jumlah_datang_val || $jumlah_sampling_val || $spesifikasi_val || $pemeriksaan->no_po)
                                        <div class="section-title">Bahan Kemasan</div>
                                        @if($pemeriksaan->no_po)
                                            <div class="field-row">
                                                <span class="field-label">No. PO:</span>
                                                <span class="field-value">{{ $pemeriksaan->no_po }}</span>
                                            </div>
                                        @endif
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            <!-- <div class="field-row">
                                                <span class="field-label">Baris:</span>
                                                <span class="field-value">{{ $rowIndex + 1 }}</span>
                                            </div> -->
                                            @if($id_bahan)
                                                <div class="field-row">
                                                    <span class="field-label">Bahan:</span>
                                                    <span class="field-value">{{ $bahanMap[$id_bahan] ?? 'N/A' }}</span>
                                                </div>
                                            @endif
                                            @if($produsen_val)
                                                <div class="field-row">
                                                    <span class="field-label">Produsen:</span>
                                                    <span class="field-value">{{ $produsen_val }}</span>
                                                </div>
                                            @endif
                                            @if($distributor_val)
                                                <div class="field-row">
                                                    <span class="field-label">Distributor:</span>
                                                    <span class="field-value">{{ $distributor_val }}</span>
                                                </div>
                                            @endif
                                            @if($kode_produksi_val)
                                                <div class="field-row">
                                                    <span class="field-label">Kode Produksi:</span>
                                                    <span class="field-value">{{ $kode_produksi_val }}</span>
                                                </div>
                                            @endif
                                            @if($jumlah_datang_val)
                                                <div class="field-row">
                                                    <span class="field-label">Jml Datang:</span>
                                                    <span class="field-value">{{ $jumlah_datang_val }}</span>
                                                </div>
                                            @endif
                                            @if($jumlah_sampling_val)
                                                <div class="field-row">
                                                    <span class="field-label">Jml Sampling:</span>
                                                    <span class="field-value">{{ $jumlah_sampling_val }}</span>
                                                </div>
                                            @endif
                                            @if($spesifikasi_val)
                                                <div class="field-row">
                                                    <span class="field-label">Spesifikasi:</span>
                                                    <span class="field-value">{{ substr($spesifikasi_val, 0, 30) }}{{ strlen($spesifikasi_val) > 30 ? '...' : '' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- KONDISI FISIK (Dynamic Rows) --}}
                                    @php
                                        $penampakans = json_decode($pemeriksaan->penampakan_array ?? '[]', true) ?? [];
                                        $sealings = json_decode($pemeriksaan->sealing_array ?? '[]', true) ?? [];
                                        $cetakans = json_decode($pemeriksaan->cetakan_array ?? '[]', true) ?? [];

                                        $penampakan_val = $penampakans[$rowIndex] ?? null;
                                        $sealing_val = $sealings[$rowIndex] ?? null;
                                        $cetakan_val = $cetakans[$rowIndex] ?? null;
                                    @endphp
                                    @if($penampakan_val !== null || $sealing_val !== null || $cetakan_val !== null)
                                        <div class="section-title">Kondisi Fisik</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            <!-- <div class="field-row">
                                                <span class="field-label">Baris:</span>
                                                <span class="field-value">{{ $rowIndex + 1 }}</span>
                                            </div> -->
                                            @if($penampakan_val !== null)
                                                <div class="field-row">
                                                    <span class="field-label">Penampakan:</span>
                                                    <span class="field-value">{{ $penampakan_val ? 'V' : 'X' }}</span>
                                                </div>
                                            @endif
                                            @if($sealing_val !== null)
                                                <div class="field-row">
                                                    <span class="field-label">Sealing:</span>
                                                    <span class="field-value">{{ $sealing_val ? 'V' : 'X' }}</span>
                                                </div>
                                            @endif
                                            @if($cetakan_val !== null)
                                                <div class="field-row">
                                                    <span class="field-label">Cetakan:</span>
                                                    <span class="field-value">{{ $cetakan_val ? 'V' : 'X' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- DETAIL TAMBAHAN (Dynamic Rows) --}}
                                    @php
                                        $ketebalan_microns = json_decode($pemeriksaan->ketebalan_micron_array ?? '[]', true) ?? [];
                                        $dimensis = json_decode($pemeriksaan->dimensi_array ?? '[]', true) ?? [];
                                        $statuses = json_decode($pemeriksaan->status_array ?? '[]', true) ?? [];

                                        $ketebalan_micron_val = $ketebalan_microns[$rowIndex] ?? null;
                                        $dimensi_val = $dimensis[$rowIndex] ?? null;
                                        $status_val = $statuses[$rowIndex] ?? null;
                                    @endphp
                                    @if($ketebalan_micron_val !== null || $dimensi_val || $status_val)
                                        <div class="section-title">Detail Tambahan</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            <!-- <div class="field-row">
                                                <span class="field-label">Baris:</span>
                                                <span class="field-value">{{ $rowIndex + 1 }}</span>
                                            </div> -->
                                            @if($ketebalan_micron_val !== null && $ketebalan_micron_val !== '')
                                                <div class="field-row">
                                                    <span class="field-label">Ketebalan (Micron):</span>
                                                    <span class="field-value">{{ $ketebalan_micron_val }}</span>
                                                </div>
                                            @endif
                                            @if($dimensi_val)
                                                <div class="field-row">
                                                    <span class="field-label">Dimensi:</span>
                                                    <span class="field-value">{{ $dimensi_val }}</span>
                                                </div>
                                            @endif
                                            @if($status_val)
                                                <div class="field-row">
                                                    <span class="field-label">Status:</span>
                                                    <span class="field-value">{{ $status_val }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- DOKUMEN (Dynamic Rows) --}}
                                    @php
                                        $logo_halals = json_decode($pemeriksaan->logo_halal_array ?? '[]', true) ?? [];
                                        $dokumen_halals = json_decode($pemeriksaan->dokumen_halal_array ?? '[]', true) ?? [];
                                        $coas = json_decode($pemeriksaan->coa_array ?? '[]', true) ?? [];
                                        $keterangans = json_decode($pemeriksaan->keterangan_array ?? '[]', true) ?? [];
                                        $image_kemasans = json_decode($pemeriksaan->image_kemasan_array ?? '[]', true) ?? [];

                                        $logo_halal_val = $logo_halals[$rowIndex] ?? null;
                                        $dokumen_halal_val = $dokumen_halals[$rowIndex] ?? null;
                                        $coa_val = $coas[$rowIndex] ?? null;
                                        $keterangan_val = $keterangans[$rowIndex] ?? null;
                                        $image_path_val = $image_kemasans[$rowIndex] ?? null;
                                    @endphp
                                    @if($logo_halal_val !== null || $dokumen_halal_val !== null || $coa_val !== null || $keterangan_val)
                                        <div class="section-title">Dokumen</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            <!-- <div class="field-row">
                                                <span class="field-label">Baris:</span>
                                                <span class="field-value">{{ $rowIndex + 1 }}</span>
                                            </div> -->
                                            <div class="field-row">
                                                <span class="field-label">Logo Halal:</span>
                                                <span class="field-value">{{ $logo_halal_val ? 'V' : 'X' }}</span>
                                            </div>
                                            <div class="field-row">
                                                <span class="field-label">Halal Berlaku:</span>
                                                <span class="field-value">{{ $dokumen_halal_val ? 'V' : 'X' }}</span>
                                            </div>
                                            <div class="field-row">
                                                <span class="field-label">COA:</span>
                                                <span class="field-value">{{ $coa_val ? 'V' : 'X' }}</span>
                                            </div>
                                            @if($keterangan_val)
                                                <div class="field-row">
                                                    <span class="field-label">Keterangan:</span>
                                                    <span class="field-value">{{ substr($keterangan_val, 0, 25) }}{{ strlen($keterangan_val) > 25 ? '...' : '' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- GAMBAR KEMASAN (Dynamic Rows) --}}
                                    @php
                                        $imgFullPath = null;
                                        if ($image_path_val) {
                                            $imgFullPath = public_path('storage/' . $image_path_val);
                                        }
                                    @endphp
                                    @if($imgFullPath && file_exists($imgFullPath))
                                        <div class="section-title">Gambar Kemasan</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px; text-align: center;">
                                            <img src="{{ $imgFullPath }}" alt="Gambar Kemasan" style="max-width: 150px; max-height: 120px;">
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                            
                            {{-- Fill empty columns if less than 4 records --}}
                            @for($i = $pageRecords->count(); $i < $columnsPerPage; $i++)
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

                            <!-- 2. Diperiksa Oleh (PRODUKSI/WAREHOUSE VERIFIER) -->
                            <td class="signature-cell">
                                <div class="signature-header-item">Diketahui Oleh:</div>
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

                            <!-- 3. Diketahui Oleh (SPV VERIFIER) -->
                            <td class="signature-cell">
                                <div class="signature-header-item">Disetujui Oleh:</div>
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
