<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Loading Produk</title>

    @php
        $firstRecord = $pemeriksaans->first();

        $plantName = $firstRecord &&
                     $firstRecord->user &&
                     $firstRecord->user->plant
            ? $firstRecord->user->plant->plant
            : 'MEDAN';
    @endphp

    <style>
        /*
        |--------------------------------------------------------------------------
        | PAGE
        |--------------------------------------------------------------------------
        | A4 LANDSCAPE
        |--------------------------------------------------------------------------
        */
        @page {
            size: 297mm 125mm;
            margin: 5mm;
        }

        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            line-height: 1.15;
            color: #1a1a1a;
            background: #fff;
        }

        .container {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #c41e3a;
            margin-bottom: 5px;
        }

        .header-logo-td {
            width: 48px;
            vertical-align: middle;
        }

        .header-logo-td img {
            width: 42px;
            height: auto;
            display: block;
        }

        .header-company-td {
            vertical-align: middle;
            padding-left: 4px;
        }

        .header-company-td h2 {
            font-size: 10px;
            font-weight: bold;
            color: #c41e3a;
            margin-bottom: 1px;
        }

        .header-company-td p {
            font-size: 6.8px;
            color: #444;
            line-height: 1.1;
        }

        .header-title-td {
            vertical-align: middle;
            text-align: right;
            width: 42%;
        }

        .header-title-box {
            font-size: 9.5px;
            font-weight: bold;
            color: #1a1a1a;
            background: #e9ecef;
            padding: 5px 8px;
            border-left: 3px solid #c41e3a;
            display: inline-block;
            text-align: right;
        }

        /*
        |--------------------------------------------------------------------------
        | SUBHEADER
        |--------------------------------------------------------------------------
        */

        .subheader {
            width: 100%;
            border: 1px solid #dee2e6;
            margin-bottom: 5px;
            background: #f8f9fa;
        }

        .subheader-table {
            width: 100%;
            border-collapse: collapse;
        }

        .subheader-table td {
            padding: 3px 5px;
            font-size: 6.8px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .subheader-table tr:last-child td {
            border-bottom: none;
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
            padding: 0 !important;
        }

        /*
        |--------------------------------------------------------------------------
        | DATA TABLE
        |--------------------------------------------------------------------------
        */

        .grid-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 3px;
        }

        .data-column {
            width: 25%;
            border: 1px solid #dee2e6;
            padding: 4px;
            vertical-align: top;
            font-size: 7.5px;
            background: #fff;
            min-height: 180px;
        }

        .data-column.empty-col {
            background: #f8f9fa;
        }

        /*
        |--------------------------------------------------------------------------
        | COLUMN HEADER
        |--------------------------------------------------------------------------
        */

        .column-header {
            font-weight: bold;
            font-size: 8.5px;
            color: #ffffff;
            background: #8b1428;
            padding: 3px;
            margin: -4px -4px 4px -4px;
            text-align: center;
            text-transform: uppercase;
        }

        /*
        |--------------------------------------------------------------------------
        | SECTION TITLE
        |--------------------------------------------------------------------------
        */

        .section-title {
            font-weight: bold;
            font-size: 6.8px;
            color: #c41e3a;
            border-bottom: 1px solid #c41e3a;
            margin-top: 3px;
            margin-bottom: 2px;
            padding-bottom: 1px;
            text-transform: uppercase;
        }

        /*
        |--------------------------------------------------------------------------
        | FIELD
        |--------------------------------------------------------------------------
        */

        .field-row {
            margin-bottom: 1.5px;
            width: 100%;
        }

        .field-label {
            font-weight: bold;
            color: #495057;
            display: inline-block;
            width: 66px;
            vertical-align: top;
        }

        .field-value {
            color: #1a1a1a;
            word-wrap: break-word;
        }

        /*
        |--------------------------------------------------------------------------
        | DOCUMENT CODE
        |--------------------------------------------------------------------------
        */

        .document-code {
            text-align: right;
            font-style: italic;
            font-size: 6.8px;
            color: #666;
            margin-bottom: 3px;
        }

        /*
        |--------------------------------------------------------------------------
        | SIGNATURE SECTION
        |--------------------------------------------------------------------------
        */

        .signature-section {
            margin-top: 4px;
            padding: 5px;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
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
            font-size: 6.8px;
            font-weight: bold;
            color: #495057;
            padding-bottom: 2px;
        }

        .signature-space {
            height: 50px;
            text-align: center;
        }

        .signature-line-empty {
            border-bottom: 1px solid #1a1a1a;
            height: 24px;
            width: 70%;
            margin: 0 auto;
        }

        .qr-code-img {
            max-height: 34px;
            max-width: 34px;
        }

        .signature-name {
            font-size: 6.8px;
            font-weight: bold;
            color: #1a1a1a;
            padding-top: 2px;
            text-transform: uppercase;
        }

        /*
        |--------------------------------------------------------------------------
        | EMPTY MESSAGE
        |--------------------------------------------------------------------------
        */

        .empty-message {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #6c757d;
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
        }

        /*
        |--------------------------------------------------------------------------
        | PAGE BREAK
        |--------------------------------------------------------------------------
        */

        .page-break {
            page-break-after: always;
        }

        /*
        |--------------------------------------------------------------------------
        | DOMPDF CONTROL
        |--------------------------------------------------------------------------
        */

        .grid-table,
        .signature-section,
        .header-table,
        .subheader {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

<div class="container">

    @php
        $isAllShift = $isAllShift ?? false;

        $dataPerShift = $dataPerShift ?? [
            [
                'pemeriksaans' => $pemeriksaans ?? collect()
            ]
        ];

        $isFirstPage = true;
    @endphp


    @if(empty($dataPerShift))

        <div class="empty-message">
            <p>
                Tidak ada data pemeriksaan untuk semua shift pada periode yang dipilih.
            </p>
        </div>

    @else

        @foreach($dataPerShift as $shiftGroupIndex => $shiftGroup)

            @php
                $pemeriksaans = $shiftGroup['pemeriksaans'];

                $qcUser = $shiftGroup['qcUser'] ?? null;

                $produksiUser = $shiftGroup['produksiUser'] ?? null;

                $spvQcUser = $shiftGroup['spvQcUser'] ?? null;

                $produkMap = $shiftGroup['produkMap'] ?? [];
            @endphp


            @if($pemeriksaans->count() > 0)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | FORMAT BOOLEAN
                    |--------------------------------------------------------------------------
                    */

                    $formatBool = function ($val) {

                        if ($val === null || $val === '') {
                            return '-';
                        }

                        if (
                            $val === true ||
                            $val === 1 ||
                            $val === '1' ||
                            $val === 'Ya' ||
                            $val === 'ya'
                        ) {
                            return 'Ya';
                        }

                        if (
                            $val === false ||
                            $val === 0 ||
                            $val === '0' ||
                            $val === 'Tidak' ||
                            $val === 'tidak'
                        ) {
                            return 'Tidak';
                        }

                        return (string) $val;
                    };


                    /*
                    |--------------------------------------------------------------------------
                    | FORMAT TEMPERATURE
                    |--------------------------------------------------------------------------
                    */

                    $formatTemp = function ($val) {

                        if ($val === null || $val === '') {
                            return null;
                        }

                        $s = (string) $val;

                        return str_contains($s, '°')
                            ? $s
                            : ($s . '°C');
                    };


                    /*
                    |--------------------------------------------------------------------------
                    | FLATTEN PRODUK DATA
                    |--------------------------------------------------------------------------
                    */

                    $items = collect();

                    foreach ($pemeriksaans as $p) {

                        $rows = is_array($p->produk_data)
                            ? $p->produk_data
                            : [];

                        /*
                        |--------------------------------------------------------------------------
                        | Jika tidak ada produk_data
                        |--------------------------------------------------------------------------
                        */

                        if (count($rows) === 0) {

                            $items->push([
                                'record' => $p,
                                'rowIndex' => null
                            ]);

                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Jika memiliki beberapa produk
                        |--------------------------------------------------------------------------
                        */

                        foreach (array_values($rows) as $i => $row) {

                            $items->push([
                                'record' => $p,
                                'rowIndex' => $i
                            ]);
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | MAKSIMAL 4 PEMERIKSAAN PER HALAMAN
                    |--------------------------------------------------------------------------
                    */

                    $columnsPerPage = 4;

                    $chunks = $items->chunk($columnsPerPage);

                @endphp


                @foreach($chunks as $pageIndex => $pageRecords)

                    @php

                        $firstColumn = $pageRecords->first();

                        $firstRecord = $firstColumn
                            ? $firstColumn['record']
                            : null;


                        $plantName =
                            $firstRecord &&
                            $firstRecord->user &&
                            $firstRecord->user->plant
                                ? $firstRecord->user->plant->plant
                                : 'MEDAN';

                    @endphp


                    {{-- PAGE BREAK --}}

                    @if(!$isFirstPage)
                        <div class="page-break"></div>
                    @endif

                    @php
                        $isFirstPage = false;
                    @endphp


                    <!-- ========================================================= -->
                    <!-- HEADER -->
                    <!-- ========================================================= -->

                    <table class="header-table">

                        <tr>

                            <!-- LOGO -->

                            <td class="header-logo-td">

                                <img
                                    src="{{ public_path('dist/images/logo/cpi-logo.png') }}"
                                    alt="Logo CPI"
                                >

                            </td>


                            <!-- COMPANY -->

                            <td class="header-company-td">

                                <h2>
                                    PT. CHAROEN POKPHAND INDONESIA
                                </h2>

                                <p>
                                    FOOD DIVISION {{ strtoupper($plantName) }}
                                </p>

                                <p>
                                    {{ strtoupper($plantName) }} - INDONESIA
                                </p>

                            </td>


                            <!-- TITLE -->

                            <td class="header-title-td">

                                <div class="header-title-box">

                                    PEMERIKSAAN LOADING

                                </div>

                            </td>

                        </tr>

                    </table>


                    <!-- ========================================================= -->
                    <!-- SUBHEADER -->
                    <!-- ========================================================= -->

                    <div class="subheader">

                        <table class="subheader-table">

                            <!-- ROW 1 -->

                            <tr>

                                <td>

                                    <span class="subheader-label">
                                        Hari/Tanggal:
                                    </span>

                                    <span class="subheader-value">

                                        {{
                                            $firstRecord && $firstRecord->tanggal
                                                ? (
                                                    is_string($firstRecord->tanggal)
                                                        ? $firstRecord->tanggal
                                                        : $firstRecord->tanggal->format('d/m/Y')
                                                  )
                                                : '-'
                                        }}

                                    </span>

                                </td>


                                <td class="subheader-divider"></td>


                                <td>

                                    <span class="subheader-label">
                                        Shift:
                                    </span>

                                    <span class="subheader-value">

                                        {{ $firstRecord->shift->shift ?? '-' }}

                                    </span>

                                </td>


                                <td class="subheader-divider"></td>


                                <td>

                                    <span class="subheader-label">
                                        Kendaraan:
                                    </span>

                                    <span class="subheader-value">

                                        {{
                                            $firstRecord && $firstRecord->kendaraan
                                                ? (
                                                    ($firstRecord->kendaraan->jenis_kendaraan ?? '-')
                                                    . ' - ' .
                                                    ($firstRecord->kendaraan->no_kendaraan ?? '-')
                                                  )
                                                : '-'
                                        }}

                                    </span>

                                </td>

                            </tr>


                            <!-- ROW 2 -->

                            <tr>

                                <td>

                                    <span class="subheader-label">
                                        Supir:
                                    </span>

                                    <span class="subheader-value">

                                        {{ $firstRecord->supir->nama_supir ?? '-' }}

                                    </span>

                                </td>
                                
                                <td class="subheader-divider"></td>


                                <td>

                                    <span class="subheader-label">
                                        Segel/Gembok:
                                    </span>

                                    <span class="subheader-value">

                                        @if(!$firstRecord || $firstRecord->segel_gembok === null)

                                            -

                                        @elseif($firstRecord->segel_gembok)

                                            Segel

                                            {{
                                                $firstRecord->no_segel
                                                    ? ' (No: ' . $firstRecord->no_segel . ')'
                                                    : ''
                                            }}

                                        @else

                                            Gembok

                                        @endif

                                    </span>

                                </td>

                            </tr>

                        </table>

                    </div>


                    <!-- ========================================================= -->
                    <!-- PEMERIKSAAN / DATA GRID -->
                    <!-- ========================================================= -->

                    <table class="grid-table">

                        <tr>

                            @foreach($pageRecords as $colIndex => $column)

                                @php

                                    $pemeriksaan = $column['record'];

                                    $rowIndex = $column['rowIndex'];

                                    $columnNumber =
                                        ($pageIndex * $columnsPerPage)
                                        + $loop->iteration;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | PRODUK DATA
                                    |--------------------------------------------------------------------------
                                    */

                                    $rows = is_array($pemeriksaan->produk_data)
                                        ? $pemeriksaan->produk_data
                                        : [];


                                    $row = ($rowIndex !== null)
                                        ? ($rows[$rowIndex] ?? [])
                                        : [];


                                    /*
                                    |--------------------------------------------------------------------------
                                    | DATA PRODUK
                                    |--------------------------------------------------------------------------
                                    */

                                    $idProduk =
                                        $row['id_produk'] ?? null;

                                    $idTujuanProduk =
                                        $row['id_tujuan_pengiriman'] ?? null;

                                    $kodeProduksi =
                                        $row['kode_produksi'] ?? null;

                                    $bestBefore =
                                        $row['best_before'] ?? null;

                                    $jumlahKemasan =
                                        $row['jumlah_kemasan'] ?? null;

                                    $jumlahSampling =
                                        $row['jumlah_sampling'] ?? null;

                                    $beratPerKarung =
                                        $row['berat_perkarung'] ?? null;

                                    $kondisiKemasan =
                                        $row['kondisi_kemasan'] ?? null;

                                    $keterangan =
                                        $row['keterangan'] ?? null;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | RESOLVE TUJUAN
                                    |--------------------------------------------------------------------------
                                    */

                                    $tujuanPdfLabel = '-';
                                    $tujuanMap = $shiftGroup['tujuanMap'] ?? [];

                                    if ($idTujuanProduk) {

                                        $tObj = $tujuanMap[$idTujuanProduk] ?? null;

                                        if ($tObj) {

                                            $tujuanPdfLabel =
                                                $tObj->customer

                                                    ? $tObj->customer->nama_cust
                                                        .
                                                        (
                                                            $tObj->nama_tujuan &&
                                                            $tObj->nama_tujuan !== '-'
                                                                ? ' - ' . $tObj->nama_tujuan
                                                                : ''
                                                        )

                                                    : ($tObj->nama_tujuan ?? '-');
                                        }
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | TEMPERATURE
                                    |--------------------------------------------------------------------------
                                    */

                                    $temperatureMobil =
                                        $pemeriksaan->temperature_mobil;


                                    $temperatureProduk =
                                        is_array($pemeriksaan->temperature_produk)
                                            ? $pemeriksaan->temperature_produk
                                            : [];


                                    $tempProdukStr =
                                        count($temperatureProduk)
                                            ? implode(
                                                ', ',
                                                array_map(
                                                    fn($t) => $formatTemp($t),
                                                    $temperatureProduk
                                                )
                                            )
                                            : null;

                                @endphp


                                <!-- ================================================= -->
                                <!-- COLUMN -->
                                <!-- ================================================= -->

                                <td class="data-column">


                                    <!-- COLUMN HEADER -->

                                    <div class="column-header">

                                        PEMERIKSAAN #{{ $columnNumber }}

                                    </div>


                                    <!-- ================================================= -->
                                    <!-- PRODUK -->
                                    <!-- ================================================= -->

                                    <div class="section-title">
                                        Produk
                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Nama:
                                        </span>

                                        <span class="field-value">

                                            {{
                                                $idProduk
                                                    ? ($produkMap[$idProduk] ?? 'N/A')
                                                    : '-'
                                            }}

                                        </span>

                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Customer/Tujuan:
                                        </span>

                                        <span class="field-value">

                                            {{ $tujuanPdfLabel }}

                                        </span>

                                    </div>


                                    <!-- ================================================= -->
                                    <!-- DETAIL -->
                                    <!-- ================================================= -->

                                    <div class="section-title">
                                        Detail
                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Kode Produksi:
                                        </span>

                                        <span class="field-value">

                                            {{ $kodeProduksi ?? '-' }}

                                        </span>

                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Best Before:
                                        </span>

                                        <span class="field-value">

                                            @if($bestBefore)

                                                @php

                                                    try {

                                                        $bestBeforeFormatted =
                                                            \Carbon\Carbon::parse($bestBefore)
                                                                ->format('d/m/Y');

                                                    } catch (\Exception $e) {

                                                        $bestBeforeFormatted =
                                                            $bestBefore;
                                                    }

                                                @endphp

                                                {{ $bestBeforeFormatted }}

                                            @else

                                                -

                                            @endif

                                        </span>

                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Jml Kemasan:
                                        </span>

                                        <span class="field-value">

                                            {{ $jumlahKemasan ?? '-' }}

                                        </span>

                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Jml Sampling:
                                        </span>

                                        <span class="field-value">

                                            {{ $jumlahSampling ?? '-' }}

                                        </span>

                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Berat/Karung:
                                        </span>

                                        <span class="field-value">

                                            {{ $beratPerKarung ?? '-' }}

                                        </span>

                                    </div>


                                    <!-- ================================================= -->
                                    <!-- SUHU -->
                                    <!-- ================================================= -->

                                    <div class="section-title">
                                        Suhu
                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Suhu Mobil:
                                        </span>

                                        <span class="field-value">

                                            {{
                                                $formatTemp($temperatureMobil)
                                                ?? '-'
                                            }}

                                        </span>

                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Suhu Produk:
                                        </span>

                                        <span class="field-value">

                                            {{ $tempProdukStr ?? '-' }}

                                        </span>

                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Kondisi Produk:
                                        </span>

                                        <span class="field-value">

                                            {{
                                                $pemeriksaan->kondisi_produk
                                                ?? '-'
                                            }}

                                        </span>

                                    </div>


                                    <!-- ================================================= -->
                                    <!-- PEMERIKSAAN -->
                                    <!-- ================================================= -->

                                    <div class="section-title">
                                        Pemeriksaan
                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Kond. Kemasan:
                                        </span>

                                        <span class="field-value">

                                            {{
                                                $formatBool($kondisiKemasan)
                                            }}

                                        </span>

                                    </div>


                                    <div class="field-row">

                                        <span class="field-label">
                                            Keterangan:
                                        </span>

                                        <span class="field-value">

                                            {{ $keterangan ?? '-' }}

                                        </span>

                                    </div>


                                </td>

                            @endforeach


                            <!-- ========================================================= -->
                            <!-- EMPTY COLUMN -->
                            <!-- ========================================================= -->

                            @for(
                                $i = $pageRecords->count();
                                $i < $columnsPerPage;
                                $i++
                            )

                                <td class="data-column empty-col"></td>

                            @endfor


                        </tr>

                    </table>


                    <!-- ========================================================= -->
                    <!-- DOCUMENT CODE -->
                    <!-- ========================================================= -->

                    <div class="document-code">

                        QW 10/00

                    </div>


                    <!-- ========================================================= -->
                    <!-- SIGNATURE -->
                    <!-- ========================================================= -->

                    <div class="signature-section">

                        <table class="signature-table">

                            <tr>

                                <!-- ================================================= -->
                                <!-- DIBUAT OLEH -->
                                <!-- ================================================= -->

                                <td class="signature-cell">

                                    <div class="signature-header-item">

                                        Dibuat Oleh

                                    </div>


                                    <div class="signature-space">

                                        @if($qcUser)

                                            @php

                                                $qcQrData =
                                                    "Dokumen ini telah diverifikasi secara sistem oleh {$qcUser} (Tim QC)";


                                                $qcQrCodeSvg =
                                                    \SimpleSoftwareIO\QrCode\Facades\QrCode
                                                        ::size(34)
                                                        ->generate($qcQrData);


                                                $base64QcSvg =
                                                    "data:image/svg+xml;base64,"
                                                    . base64_encode($qcQrCodeSvg);

                                            @endphp


                                            <img
                                                src="{{ $base64QcSvg }}"
                                                class="qr-code-img"
                                                alt="QR Code QC"
                                            >

                                        @else

                                            <div class="signature-line-empty"></div>

                                        @endif

                                    </div>


                                    <div class="signature-name">

                                        {{ $qcUser ?: '-' }}

                                    </div>

                                </td>


                                <!-- ================================================= -->
                                <!-- DIKETAHUI OLEH -->
                                <!-- ================================================= -->

                                <td class="signature-cell">

                                    <div class="signature-header-item">

                                        Diketahui Oleh

                                    </div>


                                    <div class="signature-space">

                                        @if($produksiUser)

                                            @php

                                                $prodQrData =
                                                    "Dokumen ini telah diverifikasi secara sistem oleh {$produksiUser} (Tim Warehouse)";


                                                $prodQrCodeSvg =
                                                    \SimpleSoftwareIO\QrCode\Facades\QrCode
                                                        ::size(34)
                                                        ->generate($prodQrData);


                                                $base64ProdSvg =
                                                    "data:image/svg+xml;base64,"
                                                    . base64_encode($prodQrCodeSvg);

                                            @endphp


                                            <img
                                                src="{{ $base64ProdSvg }}"
                                                class="qr-code-img"
                                                alt="QR Code Warehouse"
                                            >

                                        @else

                                            <div class="signature-line-empty"></div>

                                        @endif

                                    </div>


                                    <div class="signature-name">

                                        {{ $produksiUser ?: '-' }}

                                    </div>

                                </td>


                                <!-- ================================================= -->
                                <!-- DISETUJUI OLEH -->
                                <!-- ================================================= -->

                                <td class="signature-cell">

                                    <div class="signature-header-item">

                                        Disetujui Oleh

                                    </div>


                                    <div class="signature-space">

                                        @if($spvQcUser)

                                            @php

                                                $spvQrData =
                                                    "Dokumen ini telah diverifikasi secara sistem oleh {$spvQcUser} (Tim Supervisor QC)";


                                                $spvQrCodeSvg =
                                                    \SimpleSoftwareIO\QrCode\Facades\QrCode
                                                        ::size(34)
                                                        ->generate($spvQrData);


                                                $base64SpvSvg =
                                                    "data:image/svg+xml;base64,"
                                                    . base64_encode($spvQrCodeSvg);

                                            @endphp


                                            <img
                                                src="{{ $base64SpvSvg }}"
                                                class="qr-code-img"
                                                alt="QR Code SPV"
                                            >

                                        @else

                                            <div class="signature-line-empty"></div>

                                        @endif

                                    </div>


                                    <div class="signature-name">

                                        {{ $spvQcUser ?: '-' }}

                                    </div>

                                </td>

                            </tr>

                        </table>

                    </div>


                @endforeach


            @else

                <div class="empty-message">

                    Tidak ada data untuk dicetak.

                </div>

            @endif

        @endforeach

    @endif

</div>

</body>
</html>