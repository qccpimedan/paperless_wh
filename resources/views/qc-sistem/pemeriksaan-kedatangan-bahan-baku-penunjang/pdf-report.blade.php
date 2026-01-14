<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Kedatangan Bahan Baku Penunjang</title>
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
        @if($pemeriksaans->count() > 0)
            @php
                $columnsPerPage = 4;

                $allProdukIds = [];

                $pdfColumns = collect();
                foreach ($pemeriksaans as $p) {
                    $idBahansTmp = json_decode($p->id_bahan_array ?? '[]', true) ?? [];

                    if (!empty($idBahansTmp)) {
                        foreach ($idBahansTmp as $tmpId) {
                            if ($tmpId) {
                                $allProdukIds[] = $tmpId;
                            }
                        }
                    }

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
                        count($keterangansTmp)
                    );

                    for ($i = 0; $i < $rowCount; $i++) {
                        $pdfColumns->push([
                            'record' => $p,
                            'rowIndex' => $i,
                        ]);
                    }
                }

                $chunks = $pdfColumns->chunk($columnsPerPage);

                $produkMap = [];
                if (!empty($allProdukIds)) {
                    $produkMap = \App\Models\Produk::whereIn('id', array_values(array_unique($allProdukIds)))
                        ->pluck('nama_produk', 'id')
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
                                    @endphp
                                    @if($id_bahan || $produsen_val || $negara_produsen_val || $distributor_val || $kode_produksi_val || $expire_date_val || $jumlah_datang_val || $jumlah_sampling_val || $spesifikasi_val)
                                        <div class="section-title">Bahan Baku Penunjang</div>
                                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 8px;">
                                            @if($id_bahan)
                                                <div class="field-row">
                                                    <span class="field-label">Nama:</span>
                                                    <span class="field-value">{{ $produkMap[$id_bahan] ?? 'N/A' }}</span>
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
                                                    <span class="field-label">Datang:</span>
                                                    <span class="field-value">{{ $jumlah_datang_val }}</span>
                                                </div>
                                            @endif
                                            @if($jumlah_sampling_val)
                                                <div class="field-row">
                                                    <span class="field-label">Samp:</span>
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
                                            @if($kondisi_produk_val)
                                                <div class="field-row">
                                                    <span class="field-label">Kond:</span>
                                                    <span class="field-value">{{ $kondisi_produk_val }}</span>
                                                </div>
                                            @endif
                                            @if($suhu_produk_type_val)
                                                <div class="field-row">
                                                    <span class="field-label">T.Produk:</span>
                                                    <span class="field-value">{{ $suhu_produk_type_val }}</span>
                                                </div>
                                            @endif
                                            @if($suhu_produk_val !== null && $suhu_produk_val !== '')
                                                <div class="field-row">
                                                    <span class="field-label">S.Prod:</span>
                                                    <span class="field-value">{{ $suhu_produk_val }}°C</span>
                                                </div>
                                            @endif
                                            @if($suhu_mobil_type_val)
                                                <div class="field-row">
                                                    <span class="field-label">T.Mobil:</span>
                                                    <span class="field-value">{{ $suhu_mobil_type_val }}</span>
                                                </div>
                                            @endif
                                            @if($suhu_mobil_val !== null && $suhu_mobil_val !== '')
                                                <div class="field-row">
                                                    <span class="field-label">S.Mobil:</span>
                                                    <span class="field-value">{{ $suhu_mobil_val }}°C</span>
                                                </div>
                                            @endif
                                            @if($kondisi_produk_suhu_val)
                                                <div class="field-row">
                                                    <span class="field-label">S.Kond:</span>
                                                    <span class="field-value">{{ $kondisi_produk_suhu_val }}</span>
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

                                </td>
                            @endforeach
                        </tr>
                    </table>
                </div>

                {{-- SIGNATURE (Setiap halaman) --}}
                <div class="signature-section">
                    <div class="signature-note">
                        <span class="ok-text">V OK</span> (Kondisi Mobil, Kemasan, Warna, Benda Asing, Aroma: Sesuai Standar, Logo Halal, Halal Berlaku, COA: Tersedia)<br>
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