<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Barang Mudah Pecah</title>
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
            vertical-align: top;
            border-bottom: 1px solid #e9ecef;
        }

        .subheader-table tr:last-child td {
            border-bottom: none;
        }

        .subheader-label {
            font-weight: bold;
            color: #495057;
            width: 18%;
        }

        .subheader-value {
            color: #212529;
            width: 32%;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table th {
            background: #c41e3a;
            color: #fff;
            font-weight: bold;
            font-size: 8px;
            padding: 6px 6px;
            border: 1px solid #b91b35;
            text-align: center;
            vertical-align: middle;
        }

        .table td {
            border: 1px solid #dee2e6;
            padding: 6px 6px;
            font-size: 8px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            color: #fff;
        }

        .badge-success { background: #198754; }
        .badge-danger { background: #dc3545; }
        .badge-warning { background: #ffc107; color: #212529; }
        .badge-info { background: #0dcaf0; color: #212529; }
        .badge-secondary { background: #6c757d; }

        .signature-section {
            width: 100%;
            margin-top: 18px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-cell {
            width: 33.3333%;
            border: 1px solid #dee2e6;
            padding: 10px 10px;
            vertical-align: top;
            text-align: center;
        }

        .signature-header-item {
            font-size: 9px;
            font-weight: bold;
            color: #495057;
        }

        .signature-space {
            height: 45px;
        }

        .signature-name {
            font-size: 9px;
            font-weight: bold;
            border-top: 1px solid #adb5bd;
            padding-top: 6px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
<div class="container">
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
                <h1>PEMERIKSAAN BARANG MUDAH PECAH</h1>
            </div>
        </div>
    </div>

    @php
        $first = $pemeriksaans->first();
        $plantName = $first && $first->user && $first->user->plant ? $first->user->plant->plant : '-';
        $areaName = $first && $first->area ? $first->area->nama_area : '-';
        $shiftName = $shift ? $shift->shift : ($first && $first->shift ? $first->shift->shift : '-');

        $dateInfo = '-';
        if (!empty($tanggal)) {
            $dateInfo = \Carbon\Carbon::parse($tanggal)->format('d/m/Y');
        } elseif (!empty($tanggal_dari) || !empty($tanggal_sampai)) {
            $dari = !empty($tanggal_dari) ? \Carbon\Carbon::parse($tanggal_dari)->format('d/m/Y') : '-';
            $sampai = !empty($tanggal_sampai) ? \Carbon\Carbon::parse($tanggal_sampai)->format('d/m/Y') : '-';
            $dateInfo = $dari . ' s/d ' . $sampai;
        }
    @endphp

    <div class="subheader">
        <table class="subheader-table">
            <tr>
                <td class="subheader-label">Plant</td>
                <td class="subheader-value">{{ $plantName }}</td>
                <td class="subheader-label">Shift</td>
                <td class="subheader-value">{{ $shiftName }}</td>
            </tr>
            <tr>
                <td class="subheader-label">Area</td>
                <td class="subheader-value">{{ $areaName }}</td>
                <td class="subheader-label">Tanggal</td>
                <td class="subheader-value">{{ $dateInfo }}</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th style="width:4%">No</th>
            <!-- <th style="width:10%">Tanggal</th> -->
            <!-- <th style="width:8%">Shift</th> -->
            <th style="width:16%">Area</th>
            <th style="width:12%">Sub Area</th>
            <th style="width:18%">Nama Barang</th>
            <th style="width:6%">Jml</th>
            <th style="width:7%">Awal</th>
            <th style="width:7%">Akhir</th>
            <th style="width:14%">Temuan</th>
            <th style="width:14%">Tindakan</th>
        </tr>
        </thead>
        <tbody>
        @php $rowNo = 1; @endphp
        @forelse($pemeriksaans as $pemeriksaan)
            @foreach($pemeriksaan->details as $detail)
                @php
                    $subArea = $detail->areaLocation ? $detail->areaLocation->lokasi_area : '-';
                    $namaBarang = $detail->barang ? $detail->barang->nama_barang : ($detail->nama_barang_manual ?? '-');

                    $awal = $detail->awal ?? '-';
                    $akhir = $detail->akhir ?? '-';

                    $awalBadge = $awal === 'baik' ? 'badge-success' : ($awal === 'tidak-baik' ? 'badge-danger' : 'badge-secondary');
                    $akhirBadge = $akhir === 'baik' ? 'badge-success' : ($akhir === 'tidak-baik' ? 'badge-danger' : 'badge-secondary');

                    $temuan = $detail->temuan_ketidaksesuaian;
                    $tindakan = $detail->tindakan_koreksi;
                @endphp
                <tr>
                    <td class="text-center">{{ $rowNo++ }}</td>
                    <td class="text-left">{{ $pemeriksaan->area ? $pemeriksaan->area->nama_area : '-' }}</td>
                    <td class="text-left">{{ $subArea }}</td>
                    <td class="text-left">{{ $namaBarang }}</td>
                    <td class="text-center">{{ $detail->jumlah_barang ?? '-' }}</td>
                    <td class="text-center"><span class="badge {{ $awalBadge }}">{{ $awal }}</span></td>
                    <td class="text-center"><span class="badge {{ $akhirBadge }}">{{ $akhir }}</span></td>
                    <td class="text-left">{{ $temuan ?: '-' }}</td>
                    <td class="text-left">{{ $tindakan ?: '-' }}</td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    @php
        $qcName = null;
        $produksiName = null;
        $spvName = null;

        if ($pemeriksaans->count() > 0) {
            $qcName = optional(optional($pemeriksaans->first())->qcVerifier)->name;
            $produksiName = optional(optional($pemeriksaans->first())->produksiVerifier)->name;
            $spvName = optional(optional($pemeriksaans->first())->spvVerifier)->name;
        }
    @endphp

    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td class="signature-cell">
                    <div class="signature-header-item">Dibuat Oleh (QC)</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ $qcName ?: '-' }}</div>
                </td>
                <td class="signature-cell">
                    <div class="signature-header-item">Disetujui Oleh (Tim Warehouse)</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ $produksiName ?: '-' }}</div>
                </td>
                <td class="signature-cell">
                    <div class="signature-header-item">Diverifikasi Oleh (SPV QC)</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ $spvName ?: '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
        QW 08/00
    </div>
</div>
</body>
</html>
