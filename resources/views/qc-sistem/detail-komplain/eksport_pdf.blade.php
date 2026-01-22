<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ketidaksesuaian Kedatangan Produk Dari Supplier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 11px;
        }
        .header {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        .header-left,
        .header-right {
            display: inline-block;
            vertical-align: top;
        }
        .header-left {
            width: 70%;
        }
        .header-right {
            width: 29%;
            text-align: right;
        }
        .logo-company {
            width: 100%;
        }
        .header-logo {
            display: inline-block;
            vertical-align: top;
            width: 60px;
        }
        .header-logo img {
            width: 55px;
            height: auto;
        }
        .header-company {
            display: inline-block;
            vertical-align: top;
            width: calc(100% - 70px);
            padding-left: 8px;
        }
        .header-company h2 {
            margin: 0;
            font-size: 12px;
            font-weight: bold;
        }
        .header-company p {
            margin: 0;
            font-size: 10px;
        }
        .header-title h1 {
            margin: 0;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .date-info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .section-title {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 8px;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .form-box {
            border: 1px solid #000;
            min-height: 60px;
            padding: 5px;
        }
        .form-box-small {
            border: 1px solid #000;
            min-height: 30px;
            padding: 5px;
        }
        .signature-box {
            border: 1px solid #000;
            min-height: 80px;
            padding: 5px;
        }
        .image-container {
            text-align: center;
            padding: 10px;
        }
        .image-container img {
            max-width: 200px;
            max-height: 150px;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
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
        <!-- <div class="header-right">
            <div class="header-title">
                <h1>DETAIL KOMPLAIN</h1>
            </div>
        </div> -->
    </div>

    <!-- TITLE -->
    <div class="title">Ketidaksesuaian Kedatangan Produk Dari Supplier</div>

    <!-- DATE -->
    <!-- <div class="date-info">
        Hari/tanggal : {{ $detailKomplain->tanggal_kedatangan->format('d M Y') }}
    </div> -->

    <!-- DETAIL KOMPLAIN TABLE -->
    <table>
        <tr>
            <td colspan="2" style="background-color: #f0f0f0; font-weight: bold; text-align: center;">DETAIL KOMPLAIN</td>
        </tr>
        <tr>
            <td style="width: 35%; font-weight: bold;">Nama Supplier</td>
            <td>{{ $detailKomplain->nama_supplier }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tanggal Kedatangan</td>
            <td>{{ $detailKomplain->tanggal_kedatangan->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">No. PO</td>
            <td>{{ $detailKomplain->no_po }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Nama Produk</td>
            <td>{{ $detailKomplain->nama_produk }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Kode Produksi</td>
            <td>{{ $detailKomplain->kode_produksi }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Expired Date</td>
            <td>{{ $detailKomplain->expired_date->format('d m Y') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Jumlah Datang (Kg/Bal/Zak)</td>
            <td>{{ $detailKomplain->jumlah_datang }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Jumlah Di Tolak (Kg/Bal/Zak)</td>
            <td>{{ $detailKomplain->jumlah_di_tolak }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Uraian Komplain</td>
            <td>{{ $detailKomplain->keterangan ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Dokumentasi (Lampiran)</td>
            <td>
                @php
                    $dokumentasiFullPath = null;
                    if ($detailKomplain->dokumentasi) {
                        $dokumentasiFullPath = public_path('storage/' . $detailKomplain->dokumentasi);
                    }
                @endphp
                @if($dokumentasiFullPath && file_exists($dokumentasiFullPath))
                    <div class="image-container">
                        <img src="{{ $dokumentasiFullPath }}" alt="Dokumentasi">
                    </div>
                @else
                    <span>-</span>
                @endif
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Keterangan</td>
            <td>{{ $detailKomplain->keterangan ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Di Buat Oleh</td>
            <td>{{ $detailKomplain->di_buat_oleh ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Di Setujui Oleh</td>
            <td>{{ $detailKomplain->setujui_oleh ?? '-' }}</td>
        </tr>
    </table>

    <!-- SUPPLIER SECTION -->
    <div class="section-title">DI ISI OLEH SUPPLIER :</div>

    <table>
        <tr>
            <td style="width: 35%; font-weight: bold; vertical-align: top;">Analisa Penyebab:</td>
            <td>
                <div class="form-box"></div>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; vertical-align: top;">Tindakan Perbaikan :</td>
            <td>
                <div class="form-box"></div>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Di Isi Oleh :</td>
            <td>
                <div class="form-box-small"></div>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; vertical-align: top;">TTD :</td>
            <td>
                <div class="signature-box"></div>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Jabatan :</td>
            <td>
                <div class="form-box-small"></div>
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        QW 04/02
    </div>
</body>
</html>
