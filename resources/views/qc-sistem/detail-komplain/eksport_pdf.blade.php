<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ketidaksesuaian Kedatangan Produk Dari Supplier</title>
    @php
        // Normalisasi: bisa dipanggil dengan $detailKomplain (1 data) atau $pemeriksaans (koleksi)
        if (isset($detailKomplain)) {
            $pemeriksaans = collect([$detailKomplain]);
        }
        $firstRecord = $pemeriksaans->first();
        $plantName = 'MEDAN';
        if ($firstRecord && $firstRecord->user && $firstRecord->user->plant) {
            $plantName = $firstRecord->user->plant->plant;
        }
    @endphp
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 11px;
        }
        .page-break {
            page-break-after: always;
        }
        .header {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        .header-left {
            width: 70%;
            display: inline-block;
            vertical-align: top;
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
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 10px;
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
    @foreach($pemeriksaans as $detailKomplain)
    <div class="{{ !$loop->last ? 'page-break' : '' }}">
        <!-- HEADER -->
        <div class="header">
            <div class="header-left">
                <div class="header-logo">
                    <img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo CPI">
                </div>
                <div class="header-company">
                    <h2>PT. CHAROEN POKPHAND INDONESIA</h2>
                    @php
                        $plant = $detailKomplain->user && $detailKomplain->user->plant ? $detailKomplain->user->plant->plant : 'MEDAN';
                    @endphp
                    <p>FOOD DIVISION {{ strtoupper($plant) }}</p>
                    <p>{{ strtoupper($plant) }} - INDONESIA</p>
                </div>
            </div>
        </div>

        <!-- TITLE -->
        <div class="title">KETIDAKSESUAIAN KEDATANGAN PRODUK DARI SUPPLIER</div>

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
                <td>{{ \Carbon\Carbon::parse($detailKomplain->tanggal_kedatangan)->format('d-m-Y') }}</td>
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
            $idProdukArr = is_array($detailKomplain->id_produk_array) ? $detailKomplain->id_produk_array : [];
            $kategoriArr = is_array($detailKomplain->kategori_code_array) ? $detailKomplain->kategori_code_array : [];
            $namaProdukArr = is_array($detailKomplain->nama_produk_array) ? $detailKomplain->nama_produk_array : [];
            $kodeProduksiArr = is_array($detailKomplain->kode_produksi_array) ? $detailKomplain->kode_produksi_array : [];
            $expiredDateArr = is_array($detailKomplain->expired_date_array) ? $detailKomplain->expired_date_array : [];
            $jumlahDatangArr = is_array($detailKomplain->jumlah_datang_array) ? $detailKomplain->jumlah_datang_array : [];
            $jumlahDitolakArr = is_array($detailKomplain->jumlah_di_tolak_array) ? $detailKomplain->jumlah_di_tolak_array : [];
            $dokumentasiArr = is_array($detailKomplain->dokumentasi_array) ? $detailKomplain->dokumentasi_array : [];
            $keteranganArr = is_array($detailKomplain->keterangan_array) ? $detailKomplain->keterangan_array : [];

            $rowCount = max(count($idProdukArr), count($namaProdukArr), 1);
        @endphp

        <div class="section-title">INFORMASI PRODUK</div>
        <table>
            <tr>
                <th style="width: 4%; text-align: center;">No</th>
                <th style="width: 13%;">Kategori</th>
                <th style="width: 20%;">Nama Produk</th>
                <th style="width: 13%;">Kode Produksi</th>
                <th style="width: 12%;">Expired</th>
                <th style="width: 12%;">Jml Datang</th>
                <th style="width: 12%;">Jml Tolak</th>
                <th style="width: 14%;">Uraian / Ket</th>
            </tr>
            @for($i = 0; $i < $rowCount; $i++)
                @php
                    $expiredRaw = $expiredDateArr[$i] ?? null;
                    $expiredText = $expiredRaw ? \Carbon\Carbon::parse($expiredRaw)->format('d-m-Y') : '-';
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td>{{ $kategoriArr[$i] ?? '-' }}</td>
                    <td>{{ $namaProdukArr[$i] ?? $detailKomplain->nama_produk }}</td>
                    <td>{{ $kodeProduksiArr[$i] ?? $detailKomplain->kode_produksi }}</td>
                    <td>{{ $expiredText }}</td>
                    <td>{{ $jumlahDatangArr[$i] ?? $detailKomplain->jumlah_datang }}</td>
                    <td>{{ $jumlahDitolakArr[$i] ?? $detailKomplain->jumlah_di_tolak }}</td>
                    <td>{{ $keteranganArr[$i] ?? $detailKomplain->keterangan }}</td>
                </tr>
                @php
                    $dokPath = $dokumentasiArr[$i] ?? null;
                    $dokFullPath = $dokPath ? public_path('storage/' . $dokPath) : null;
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">Lamp.</td>
                    <td colspan="7">
                        @if($dokFullPath && file_exists($dokFullPath))
                            <div class="image-container"><img src="{{ $dokFullPath }}" alt="Dokumentasi"></div>
                        @else <span>-</span> @endif
                    </td>
                </tr>
            @endfor
        </table>

        <div class="section-title">DI ISI OLEH SUPPLIER :</div>
        <table>
            <tr><td style="width: 35%; font-weight: bold;">Analisa Penyebab:</td><td><div class="form-box"></div></td></tr>
            <tr><td style="font-weight: bold;">Tindakan Perbaikan :</td><td><div class="form-box"></div></td></tr>
            <tr><td style="font-weight: bold;">Di Isi Oleh :</td><td><div class="form-box-small"></div></td></tr>
            <tr><td style="font-weight: bold;">TTD :</td><td><div class="signature-box"></div></td></tr>
            <tr><td style="font-weight: bold;">Jabatan :</td><td><div class="form-box-small"></div></td></tr>
        </table>
        <div class="footer">QW 04/00</div>
    </div>
    @endforeach
</body>
</html>
