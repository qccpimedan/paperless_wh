<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Kedatangan Bahan Baku Penunjang</title>
    @php
        $firstRecord = $pemeriksaans->first();
        $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
    @endphp
    <style>
        @page {
            size: A4;
            margin: 8mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 8.5px;
            line-height: 1.35;
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
            margin-bottom: 8px;
            border-bottom: 3px solid #c41e3a;
            padding-bottom: 6px;
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
            width: 45px;
            vertical-align: middle;
        }
        
        .header-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        
        .header-company {
            display: table-cell;
            vertical-align: middle;
            padding-left: 10px;
        }
        
        .header-company h2 {
            font-size: 11px;
            font-weight: bold;
            color: #c41e3a;
            margin-bottom: 1px;
            letter-spacing: 0.5px;
        }
        
        .header-company p {
            font-size: 7.5px;
            color: #444;
            margin-bottom: 0px;
        }
        
        .header-title h1 {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 6px 12px;
            border-radius: 4px;
            border-left: 4px solid #c41e3a;
            display: inline-block;
        }

        /* SUBHEADER - Grid Layout Fixed */
        .subheader {
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 8px;
            background: #f8f9fa;
            page-break-inside: avoid;
            padding: 0;
        }
        
        .subheader-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .subheader-table td {
            padding: 4px 8px;
            font-size: 7.5px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }
        
        .subheader-table tr:last-child td {
            border-bottom: none;
        }
        
        .subheader-label {
            font-weight: 600;
            color: #495057;
            width: 80px;
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
            margin-bottom: 8px;
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
            padding: 6px;
            vertical-align: top;
            font-size: 7.5px;
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
            font-size: 8px;
            color: #8b1428;
            background: linear-gradient(135deg, #8b1428 0%, #5c0e1a 100%);
            padding: 5px 6px;
            margin: -6px -6px 6px -6px;
            text-align: center;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 7.5px;
            color: #c41e3a;
            border-bottom: 1.5px solid #c41e3a;
            margin-top: 6px;
            margin-bottom: 4px;
            padding-bottom: 2px;
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
            margin-bottom: 2px;
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
            margin-top: 8px;
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: #f8f9fa;
            page-break-inside: avoid;
        }
        
        .signature-note {
            font-size: 6.5px;
            color: #495057;
            padding: 4px 8px;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            margin-bottom: 8px;
            line-height: 1.4;
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
            padding: 0 10px;
            vertical-align: top;
        }
        
        .signature-header-item {
            font-size: 7.5px;
            font-weight: 600;
            color: #495057;
            padding-bottom: 15px;
        }
        
        .signature-space {
            height: 45px;
            margin: 0 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signature-line-empty {
            border-bottom: 1.5px solid #1a1a1a;
            height: 30px;
            width: 100%;
        }
        
        .qr-code-img {
            max-height: 45px;
            max-width: 45px;
        }
        
        .signature-name {
            font-size: 7.5px;
            font-weight: bold;
            color: #1a1a1a;
            padding-top: 6px;
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

                $allBahanIds = [];
                foreach ($pemeriksaans as $p) {
                    $idBahansTmp = json_decode($p->id_bahan_array ?? '[]', true) ?? [];
                    if (!empty($idBahansTmp)) {
                        foreach ($idBahansTmp as $tmpId) {
                            if ($tmpId) {
                                $allBahanIds[] = $tmpId;
                            }
                        }
                    }
                }

                $bahanMap = [];
                if (!empty($allBahanIds)) {
                    $bahanMap = \App\Models\Bahan::whereIn('id', array_values(array_unique($allBahanIds)))
                        ->pluck('nama_bahan', 'id')
                        ->toArray();
                }

                // Chunking logic per record so tables don't mix different shifts/dates
                $recordChunks = collect();
                foreach ($pemeriksaans as $p) {
                    $idBahansTmp = json_decode($p->id_bahan_array ?? '[]', true) ?? [];
                    $produsensTmp = json_decode($p->produsen_array ?? '[]', true) ?? [];
                    $negaraProdusensTmp = json_decode($p->negara_produsen_array ?? '[]', true) ?? [];
                    $distributorsTmp = json_decode($p->distributor_array ?? '[]', true) ?? [];
                    $kodeProduksisTmp = json_decode($p->kode_produksi_array ?? '[]', true) ?? [];
                    $expireDatesTmp = json_decode($p->expire_date_array ?? '[]', true) ?? [];
                    $jumlahDatangsTmp = json_decode($p->jumlah_datang_array ?? '[]', true) ?? [];
                    $jumlahSamplingsTmp = json_decode($p->jumlah_sampling_array ?? '[]', true) ?? [];
                    $spesifikasisTmp = json_decode($p->spesifikasi_array ?? '[]', true) ?? [];
                    $kondisiProduksTmp = json_decode($p->kondisi_produk ?? '[]', true) ?? [];
                    $suhuProduksTmp = json_decode($p->suhu_produk ?? '[]', true) ?? [];
                    $suhuProdukTypesTmp = json_decode($p->suhu_produk_type ?? '[]', true) ?? [];
                    $suhuMobilsTmp = json_decode($p->suhu_mobil_array ?? '[]', true) ?? [];
                    $suhuMobilTypesTmp = json_decode($p->suhu_mobil_type_array ?? '[]', true) ?? [];
                    $kondisiProdukSuhusTmp = json_decode($p->kondisi_produk_suhu ?? '[]', true) ?? [];
                    $hasilUjiFfasTmp = json_decode($p->hasil_uji_ffa_array ?? '[]', true) ?? [];
                    $keterangansTmp = json_decode($p->keterangan_array ?? '[]', true) ?? [];
                    $logoHalalsTmp = json_decode($p->logo_halal_array ?? '[]', true) ?? [];
                    $dokumenHalalsTmp = json_decode($p->dokumen_halal_array ?? '[]', true) ?? [];
                    $coasTmp = json_decode($p->coa_array ?? '[]', true) ?? [];
                    $fileCoasTmp = json_decode($p->file_coa_array ?? '[]', true) ?? [];
                    $imageBahanBakusTmp = json_decode($p->image_bahan_baku_array ?? '[]', true) ?? [];
                    $statusBarisesTmp = json_decode($p->status_baris_array ?? '[]', true) ?? [];

                    $rowCount = max(
                        1,
                        count($idBahansTmp),
                        count($produsensTmp),
                        count($negaraProdusensTmp),
                        count($distributorsTmp),
                        count($kodeProduksisTmp),
                        count($expireDatesTmp),
                        count($jumlahDatangsTmp),
                        count($jumlahSamplingsTmp),
                        count($spesifikasisTmp),
                        count($kondisiProduksTmp),
                        count($suhuProduksTmp),
                        count($suhuProdukTypesTmp),
                        count($suhuMobilsTmp),
                        count($suhuMobilTypesTmp),
                        count($kondisiProdukSuhusTmp),
                        count($hasilUjiFfasTmp),
                        count($keterangansTmp),
                        count($logoHalalsTmp),
                        count($dokumenHalalsTmp),
                        count($coasTmp),
                        count($fileCoasTmp),
                        count($imageBahanBakusTmp),
                        count($statusBarisesTmp)
                    );

                    $validRows = collect();
                    for ($i = 0; $i < $rowCount; $i++) {
                        $id_bahan = $idBahansTmp[$i] ?? null;
                        
                        // Periksa array jika filterBahanIds ada
                        if (isset($filterBahanIds) && is_array($filterBahanIds)) {
                            // Jika array filterBahanIds tidak kosong, bahan harus ada di dalamnya
                            if (!empty($filterBahanIds) && !in_array($id_bahan, $filterBahanIds)) {
                                continue;
                            }
                        }

                        $validRows->push([
                            'record' => $p,
                            'rowIndex' => $i,
                        ]);
                    }
                    
                    if ($validRows->isNotEmpty()) {
                        $chunks = $validRows->chunk($columnsPerPage);
                        foreach ($chunks as $chunk) {
                            $recordChunks->push($chunk);
                        }
                    }
                }
            @endphp
            
            @foreach($recordChunks as $pageIndex => $pageRecords)
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
                            <h1>PEMERIKSAAN BAHAN BAKU PENUNJANG</h1>
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

                                    {{-- BAHAN BAKU PENUNJANG (Single Row) --}}
                                    @php
                                        $id_bahans = json_decode($pemeriksaan->id_bahan_array ?? '[]', true) ?? [];
                                        $produsens_arr = json_decode($pemeriksaan->produsen_array ?? '[]', true) ?? [];
                                        $negara_produsens_arr = json_decode($pemeriksaan->negara_produsen_array ?? '[]', true) ?? [];
                                        $distributors_arr = json_decode($pemeriksaan->distributor_array ?? '[]', true) ?? [];
                                        $kode_produksis = json_decode($pemeriksaan->kode_produksi_array ?? '[]', true) ?? [];
                                        $expire_dates = json_decode($pemeriksaan->expire_date_array ?? '[]', true) ?? [];
                                        $jumlah_datangs = json_decode($pemeriksaan->jumlah_datang_array ?? '[]', true) ?? [];
                                        $jumlah_samplings = json_decode($pemeriksaan->jumlah_sampling_array ?? '[]', true) ?? [];
                                        $spesifikasis = json_decode($pemeriksaan->spesifikasi_array ?? '[]', true) ?? [];
                                        $id_bahan = $id_bahans[$rowIndex] ?? null;
                                        $produsen_val = $produsens_arr[$rowIndex] ?? null;
                                        $negara_produsen_val = $negara_produsens_arr[$rowIndex] ?? null;
                                        $distributor_val = $distributors_arr[$rowIndex] ?? null;
                                        $kode_produksi_val = $kode_produksis[$rowIndex] ?? null;
                                        $expire_date_val = $expire_dates[$rowIndex] ?? null;
                                        $jumlah_datang_val = $jumlah_datangs[$rowIndex] ?? null;
                                        $jumlah_sampling_val = $jumlah_samplings[$rowIndex] ?? null;
                                        $spesifikasi_val = $spesifikasis[$rowIndex] ?? null;
                                        // Normalisasi: jika nilai masih array (data lama), ubah ke string
                                        if (is_array($produsen_val)) { $produsen_val = implode(', ', array_filter($produsen_val)); }
                                        if (is_array($distributor_val)) { $distributor_val = implode(', ', array_filter($distributor_val)); }
                                        if (is_array($negara_produsen_val)) { $negara_produsen_val = implode(', ', array_filter($negara_produsen_val)); }
                                        if (is_array($kode_produksi_val)) { $kode_produksi_val = implode(', ', array_filter($kode_produksi_val)); }
                                        if (is_array($jumlah_datang_val)) { $jumlah_datang_val = implode(', ', array_filter($jumlah_datang_val)); }
                                        if (is_array($jumlah_sampling_val)) { $jumlah_sampling_val = implode(', ', array_filter($jumlah_sampling_val)); }
                                        if (is_array($spesifikasi_val)) { $spesifikasi_val = implode('; ', array_filter($spesifikasi_val)); }
                                    @endphp
                                    @if($id_bahan || $produsen_val || $negara_produsen_val || $distributor_val || $kode_produksi_val || $expire_date_val || $jumlah_datang_val || $jumlah_sampling_val || $spesifikasi_val)
                                        <div class="section-title">Bahan Baku Penunjang</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            @if($id_bahan)
                                                <div class="field-row">
                                                    <span class="field-label">Nama:</span>
                                                    <span class="field-value">{{ $bahanMap[$id_bahan] ?? 'N/A' }}</span>
                                                </div>
                                            @endif
                                            @if($produsen_val)
                                                <div class="field-row">
                                                    <span class="field-label">Prod:</span>
                                                    <span class="field-value">{{ $produsen_val }}</span>
                                                </div>
                                            @endif
                                            @if($negara_produsen_val)
                                                <div class="field-row">
                                                    <span class="field-label">Neg:</span>
                                                    <span class="field-value">{{ $negara_produsen_val }}</span>
                                                </div>
                                            @endif
                                            @if($distributor_val)
                                                <div class="field-row">
                                                    <span class="field-label">Dist:</span>
                                                    <span class="field-value">{{ $distributor_val }}</span>
                                                </div>
                                            @endif
                                            @if($kode_produksi_val)
                                                <div class="field-row">
                                                    <span class="field-label">Kode:</span>
                                                    <span class="field-value">{{ $kode_produksi_val }}</span>
                                                </div>
                                            @endif
                                            @if($expire_date_val)
                                                <div class="field-row">
                                                    <span class="field-label">Exp:</span>
                                                    <span class="field-value">{{ \Carbon\Carbon::parse($expire_date_val)->format('d/m/Y') }}</span>
                                                </div>
                                            @endif
                                            @if($jumlah_datang_val)
                                                <div class="field-row">
                                                    <span class="field-label">Jumlah Datang:</span>
                                                    <span class="field-value">{{ $jumlah_datang_val }}</span>
                                                </div>
                                            @endif
                                            @if($jumlah_sampling_val)
                                                <div class="field-row">
                                                    <span class="field-label">Jumlah Sampling:</span>
                                                    <span class="field-value">{{ $jumlah_sampling_val }}</span>
                                                </div>
                                            @endif
                                            @if($spesifikasi_val)
                                                <div class="field-row">
                                                    <span class="field-label">Spes:</span>
                                                    <span class="field-value">{{ substr($spesifikasi_val, 0, 30) }}{{ strlen($spesifikasi_val) > 30 ? '...' : '' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- KONDISI SUHU (Single Row) --}}
                                    @php
                                        $kondisi_produks = json_decode($pemeriksaan->kondisi_produk ?? '[]', true) ?? [];
                                        $suhu_produks = json_decode($pemeriksaan->suhu_produk ?? '[]', true) ?? [];
                                        $suhu_produk_types = json_decode($pemeriksaan->suhu_produk_type ?? '[]', true) ?? [];
                                        $suhu_mobils = json_decode($pemeriksaan->suhu_mobil_array ?? '[]', true) ?? [];
                                        $suhu_mobil_types = json_decode($pemeriksaan->suhu_mobil_type_array ?? '[]', true) ?? [];
                                        $kondisi_produk_suhus = json_decode($pemeriksaan->kondisi_produk_suhu ?? '[]', true) ?? [];

                                        $kondisi_produk_val = $kondisi_produks[$rowIndex] ?? null;
                                        $suhu_produk_val = $suhu_produks[$rowIndex] ?? null;
                                        $suhu_produk_type_val = $suhu_produk_types[$rowIndex] ?? null;
                                        $suhu_mobil_val = $suhu_mobils[$rowIndex] ?? null;
                                        $suhu_mobil_type_val = $suhu_mobil_types[$rowIndex] ?? null;
                                        $kondisi_produk_suhu_val = $kondisi_produk_suhus[$rowIndex] ?? null;
                                    @endphp
                                    @if($kondisi_produk_val || $suhu_produk_val !== null || $suhu_produk_type_val || $suhu_mobil_val !== null || $suhu_mobil_type_val || $kondisi_produk_suhu_val)
                                        <div class="section-title">Kondisi Suhu</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            @if($suhu_produk_type_val)
                                                <div class="field-row">
                                                    <span class="field-label" style="width: 95px;">Suhu Produk:</span>
                                                    <span class="field-value">{{ $suhu_produk_type_val }}</span>
                                                </div>
                                            @endif
                                            @if($suhu_produk_val !== null && $suhu_produk_val !== '')
                                                <div class="field-row">
                                                    <span class="field-label" style="width: 95px;">Nilai Suhu Produk:</span>
                                                    <span class="field-value">{{ $suhu_produk_val }}°C</span>
                                                </div>
                                            @endif
                                            @if($suhu_mobil_type_val)
                                                <div class="field-row">
                                                    <span class="field-label" style="width: 95px;">Suhu Mobil:</span>
                                                    <span class="field-value">{{ $suhu_mobil_type_val }}</span>
                                                </div>
                                            @endif
                                            @if($suhu_mobil_val !== null && $suhu_mobil_val !== '')
                                                <div class="field-row">
                                                    <span class="field-label" style="width: 95px;">Nilai Suhu Mobil:</span>
                                                    <span class="field-value">{{ $suhu_mobil_val }}°C</span>
                                                </div>
                                            @endif
                                            @if($kondisi_produk_val)
                                                <div class="field-row">
                                                    <span class="field-label" style="width: 95px;">Kondisi Produk:</span>
                                                    <span class="field-value">{{ $kondisi_produk_val }}</span>
                                                </div>
                                            @endif
                                            @if($kondisi_produk_suhu_val !== null && $kondisi_produk_suhu_val !== '')
                                                <div class="field-row">
                                                    <span class="field-label" style="width: 95px;">Suhu Kondisi Produk:</span>
                                                    <span class="field-value">{{ $kondisi_produk_suhu_val }}°C</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- ANALISIS (Single Row) --}}
                                    @php
                                        $hasil_uji_ffas = json_decode($pemeriksaan->hasil_uji_ffa_array ?? '[]', true) ?? [];
                                        $keterangans = json_decode($pemeriksaan->keterangan_array ?? '[]', true) ?? [];

                                        $hasil_uji_ffa_val = $hasil_uji_ffas[$rowIndex] ?? null;
                                        $keterangan_val = $keterangans[$rowIndex] ?? null;
                                    @endphp
                                    @if($hasil_uji_ffa_val || $keterangan_val)
                                        <div class="section-title">Analisis</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            @if($hasil_uji_ffa_val)
                                                <div class="field-row">
                                                    <span class="field-label">FFA:</span>
                                                    <span class="field-value">{{ $hasil_uji_ffa_val }}</span>
                                                </div>
                                            @endif
                                            @if($keterangan_val)
                                                <div class="field-row">
                                                    <span class="field-label">Ket:</span>
                                                    <span class="field-value">{{ $keterangan_val }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- KONDISI FISIK (Single Row) --}}
                                    @php
                                        $kondisi_fisiks = json_decode($pemeriksaan->kondisi_fisik_array ?? '[]', true) ?? [];
                                        $kondisi_fisik_val = $kondisi_fisiks[$rowIndex] ?? [];
                                    @endphp
                                    @if(!empty($kondisi_fisik_val))
                                        <div class="section-title">Kondisi Fisik</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            @if(isset($kondisi_fisik_val['kemasan']))
                                                <div class="field-row">
                                                    <span class="field-label">Kemasan:</span>
                                                    <span class="field-value">{{ ($kondisi_fisik_val['kemasan'] ?? false) ? 'V' : 'X' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($kondisi_fisik_val['warna']))
                                                <div class="field-row">
                                                    <span class="field-label">Warna:</span>
                                                    <span class="field-value">{{ ($kondisi_fisik_val['warna'] ?? false) ? 'V' : 'X' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($kondisi_fisik_val['benda_asing']))
                                                <div class="field-row">
                                                    <span class="field-label">B.Asing:</span>
                                                    <span class="field-value">{{ ($kondisi_fisik_val['benda_asing'] ?? false) ? 'V' : 'X' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($kondisi_fisik_val['aroma']))
                                                <div class="field-row">
                                                    <span class="field-label">Aroma:</span>
                                                    <span class="field-value">{{ ($kondisi_fisik_val['aroma'] ?? false) ? 'V' : 'X' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- DOKUMENTASI (Single Row) --}}
                                    @php
                                        $logo_halals = json_decode($pemeriksaan->logo_halal_array ?? '[]', true) ?? [];
                                        $dokumen_halals = json_decode($pemeriksaan->dokumen_halal_array ?? '[]', true) ?? [];
                                        $coas = json_decode($pemeriksaan->coa_array ?? '[]', true) ?? [];
                                        $file_coas = json_decode($pemeriksaan->file_coa_array ?? '[]', true) ?? [];

                                        $logo_halal_val = $logo_halals[$rowIndex] ?? null;
                                        $dokumen_halal_val = $dokumen_halals[$rowIndex] ?? null;
                                        $coa_val = $coas[$rowIndex] ?? null;
                                        $file_coa_val = $file_coas[$rowIndex] ?? null;
                                    @endphp
                                    @if($logo_halal_val !== null || $dokumen_halal_val !== null || $coa_val !== null || $file_coa_val)
                                        <div class="section-title">Dokumentasi</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            @if($logo_halal_val !== null)
                                                <div class="field-row">
                                                    <span class="field-label" style="width: 55px;">Logo Halal:</span>
                                                    <span class="field-value">{{ $logo_halal_val ? 'Ya' : 'Tidak' }}</span>
                                                </div>
                                            @endif
                                            @if($dokumen_halal_val !== null)
                                                <div class="field-row">
                                                    <span class="field-label" style="width: 55px;">Dok. Halal:</span>
                                                    <span class="field-value">{{ $dokumen_halal_val ? 'Ya' : 'Tidak' }}</span>
                                                </div>
                                            @endif
                                            @if($coa_val !== null)
                                                <div class="field-row">
                                                    <span class="field-label" style="width: 55px;">COA:</span>
                                                    <span class="field-value">{{ $coa_val ? 'Ya' : 'Tidak' }}</span>
                                                </div>
                                            @endif
                                            @if($file_coa_val)
                                                <div class="field-row">
                                                    <span class="field-label" style="width: 55px;">File COA:</span>
                                                    <span class="field-value" style="color: #2b6cb0; font-weight: bold;">Ada (Dilampirkan)</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- STATUS RELEASE (Single Row) --}}
                                    @php
                                        $status_barises = json_decode($pemeriksaan->status_baris_array ?? '[]', true) ?? [];
                                        $status_baris_val = $status_barises[$rowIndex] ?? null;
                                    @endphp
                                    @if($status_baris_val)
                                        <div class="section-title">Status Release</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            <div class="field-row">
                                                <span class="field-label" style="width: 55px;">Status:</span>
                                                <span class="field-value">
                                                    @if($status_baris_val === 'Release')
                                                        <span style="color: #2f855a; font-weight: bold; background: #c6f6d5; padding: 1px 4px; border-radius: 2px;">RELEASE</span>
                                                    @elseif($status_baris_val === 'Hold')
                                                        <span style="color: #9c4221; font-weight: bold; background: #feebc8; padding: 1px 4px; border-radius: 2px;">HOLD</span>
                                                    @else
                                                        <span style="color: #4a5568; font-weight: bold; background: #edf2f7; padding: 1px 4px; border-radius: 2px;">{{ strtoupper($status_baris_val) }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- FOTO BAHAN BAKU (Single Row) --}}
                                    @php
                                        $image_bahan_bakus = json_decode($pemeriksaan->image_bahan_baku_array ?? '[]', true) ?? [];
                                        $image_path_val = $image_bahan_bakus[$rowIndex] ?? null;
                                        $imgFullPath = null;
                                        if ($image_path_val) {
                                            $imgFullPath = public_path('storage/' . $image_path_val);
                                        }
                                    @endphp
                                    @if($imgFullPath && file_exists($imgFullPath))
                                        <div class="section-title">Foto Bahan Baku</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px; text-align: center;">
                                            <img src="{{ $imgFullPath }}" alt="Foto Bahan Baku" style="max-width: 150px; max-height: 120px; border: 1px solid #dee2e6; border-radius: 4px; padding: 2px;">
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
                        <span class="ok-text">V</span> : OK (Kondisi Mobil, Kemasan, Warna, Benda Asing, Aroma: Sesuai Standar, Logo Halal, Halal Berlaku, COA: Tersedia)<br>
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

                            <!-- 3. Diketahui Oleh (SPV VERIFIER) -->
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
                    QW 01/00
                </div>

                <!-- {{-- FOOTER (Setiap halaman) --}}
                <div class="footer">
                    <p>PT. CHAROEN POKPHAND INDONESIA</p>
                    <p>FOOD DIVISION MEDAN</p>
                    <p>MEDAN - INDONESIA</p>
                    <p class="footer-main">QW 02/01</p>
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