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
            <td style="font-weight: bold;">Di Buat Oleh</td>
            <td>{{ $detailKomplain->di_buat_oleh ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Di Setujui Oleh</td>
            <td>{{ $detailKomplain->setujui_oleh ?? '-' }}</td>
        </tr>
    </table>

    @php
        $idProdukArr = is_array($detailKomplain->id_produk_array ?? null) ? $detailKomplain->id_produk_array : [];
        $kategoriArr = is_array($detailKomplain->kategori_code_array ?? null) ? $detailKomplain->kategori_code_array : [];
        $namaProdukArr = is_array($detailKomplain->nama_produk_array ?? null) ? $detailKomplain->nama_produk_array : [];
        $kodeProduksiArr = is_array($detailKomplain->kode_produksi_array ?? null) ? $detailKomplain->kode_produksi_array : [];
        $expiredDateArr = is_array($detailKomplain->expired_date_array ?? null) ? $detailKomplain->expired_date_array : [];
        $jumlahDatangArr = is_array($detailKomplain->jumlah_datang_array ?? null) ? $detailKomplain->jumlah_datang_array : [];
        $jumlahDitolakArr = is_array($detailKomplain->jumlah_di_tolak_array ?? null) ? $detailKomplain->jumlah_di_tolak_array : [];
        $dokumentasiArr = is_array($detailKomplain->dokumentasi_array ?? null) ? $detailKomplain->dokumentasi_array : [];
        $keteranganArr = is_array($detailKomplain->keterangan_array ?? null) ? $detailKomplain->keterangan_array : [];

        $rowCount = max(
            count($idProdukArr),
            count($kategoriArr),
            count($namaProdukArr),
            count($kodeProduksiArr),
            count($expiredDateArr),
            count($jumlahDatangArr),
            count($jumlahDitolakArr),
            count($dokumentasiArr),
            count($keteranganArr)
        );
        if ($rowCount < 1) {
            $rowCount = 1;
        }
    @endphp

    <div class="section-title">INFORMASI PRODUK</div>
    <table>
        <tr>
            <th style="width: 4%; text-align: center;">No</th>
            <th style="width: 13%;">Kategori</th>
            <th style="width: 20%;">Nama Produk</th>
            <th style="width: 13%;">Kode Produksi</th>
            <th style="width: 12%;">Expired</th>
            <th style="width: 12%;">Jml Datang (Kg/Bal/Zak)</th>
            <th style="width: 12%;">Jml Tolak (Kg/Bal/Zak)</th>
            <th style="width: 14%;">Uraian / Ket</th>
        </tr>
        @for($i = 0; $i < $rowCount; $i++)
            @php
                $namaProduk = $namaProdukArr[$i] ?? $detailKomplain->nama_produk;
                $kategori = $kategoriArr[$i] ?? null;
                $kodeProduksi = $kodeProduksiArr[$i] ?? $detailKomplain->kode_produksi;
                $expiredRaw = $expiredDateArr[$i] ?? ($detailKomplain->expired_date ? $detailKomplain->expired_date->format('Y-m-d') : null);
                $expiredText = '-';
                if ($expiredRaw) {
                    try {
                        $expiredText = \Carbon\Carbon::parse($expiredRaw)->format('d-m-Y');
                    } catch (\Exception $e) {
                        $expiredText = (string) $expiredRaw;
                    }
                }
                $jumlahDatang = $jumlahDatangArr[$i] ?? $detailKomplain->jumlah_datang;
                $jumlahDitolak = $jumlahDitolakArr[$i] ?? $detailKomplain->jumlah_di_tolak;
                $keterangan = $keteranganArr[$i] ?? $detailKomplain->keterangan;
            @endphp
            <tr>
                <td style="text-align: center;">{{ $i + 1 }}</td>
                <td>{{ $kategori !== null && $kategori !== '' ? $kategori : '-' }}</td>
                <td>{{ $namaProduk ?? '-' }}</td>
                <td>{{ $kodeProduksi ?? '-' }}</td>
                <td>{{ $expiredText }}</td>
                <td>{{ $jumlahDatang ?? '-' }}</td>
                <td>{{ $jumlahDitolak ?? '-' }}</td>
                <td>{{ $keterangan !== null && $keterangan !== '' ? $keterangan : '-' }}</td>
            </tr>
            @php
                $dokPath = $dokumentasiArr[$i] ?? null;
                if (($dokPath === null || $dokPath === '') && $i === 0) {
                    $dokPath = $detailKomplain->dokumentasi;
                }
                $dokFullPath = null;
                if ($dokPath) {
                    $dokFullPath = public_path('storage/' . $dokPath);
                }
            @endphp
            <tr>
                <td style="text-align: center; font-weight: bold;">Lamp.</td>
                <td colspan="7">
                    @if($dokFullPath && file_exists($dokFullPath))
                        <div class="image-container">
                            <img src="{{ $dokFullPath }}" alt="Dokumentasi">
                        </div>
                    @else
                        <span>-</span>
                    @endif
                </td>
            </tr>
        @endfor
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
