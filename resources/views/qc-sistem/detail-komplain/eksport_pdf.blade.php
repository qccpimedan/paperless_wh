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
            text-align: left;
            margin-bottom: 10px;
            font-weight: bold;
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
        <div>PT. Charoen Pokphand Indonesia</div>
        <div>Food Division</div>
    </div>

    <!-- TITLE -->
    <div class="title">Ketidaksesuaian Kedatangan Produk Dari Supplier</div>

    <!-- DATE -->
    <div class="date-info">
        Hari/tanggal : {{ $detailKomplain->tanggal_kedatangan->format('d M Y') }}
    </div>

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
            <td>{{ $detailKomplain->tanggal_kedatangan->format('d m Y') }}</td>
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
                @if($detailKomplain->dokumentasi)
                    <div class="image-container">
                        <img src="{{ public_path('storage/' . $detailKomplain->dokumentasi) }}" alt="Dokumentasi">
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
