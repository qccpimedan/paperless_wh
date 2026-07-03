<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Golden Sample Report</title>
    @php
        $firstRecord = $reports->first();
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

        .header {
            display: table;
            width: 100%;
            margin-bottom: 12px;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 8px;
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
            width: auto;
        }

        .header-logo,
        .header-company {
            display: table-cell;
            vertical-align: middle;
        }

        .header-logo {
            padding-right: 10px;
        }

        .header-logo img {
            width: 40px;
            height: auto;
        }

        .header-company h2 {
            margin: 0;
            font-size: 12px;
            font-weight: 700;
        }

        .header-company p {
            margin: 0;
            font-size: 9px;
            color: #495057;
        }

        .header-title {
            font-size: 13px;
            font-weight: bold;
            color: #1a1a1a;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            border-left: 4px solid #c41e3a;
            display: inline-block;
        }

        .header-title h1 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            text-align: right;
        }

        .subheader {
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 12px;
            background: #f8f9fa;
        }

        .subheader-table {
            width: 100%;
            border-collapse: collapse;
        }

        .subheader-table td {
            padding: 7px 10px;
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
            width: 90px;
        }

        .subheader-divider {
            width: 1px;
            background: #dee2e6;
            padding: 0;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
        }

        table.report thead {
            display: table-header-group;
        }

        table.report th {
            background: #8b1428;
            color: #fff;
            font-size: 8px;
            padding: 6px;
            border: 1px solid #5c0e1a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        table.report td {
            font-size: 8px;
            padding: 6px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        .muted {
            color: #6c757d;
        }

        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 10px;
            font-size: 8px;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
            color: #1a1a1a;
            margin-right: 4px;
        }

        .signature-section {
            margin-top: 14px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-cell {
            width: 33.33%;
            text-align: center;
            padding: 10px;
            border: 1px solid #dee2e6;
        }

        .signature-header-item {
            font-weight: 600;
            color: #495057;
            font-size: 8px;
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
            height: 28px;
            width: 100%;
        }
        
        .qr-code-img {
            max-height: 55px;
            max-width: 55px;
        }

        .signature-name {
            font-weight: 600;
            font-size: 9px;
        }

        .block-title {
            font-weight: 700;
            margin: 8px 0 6px;
        }

        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
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
                <h1>GOLDEN SAMPLE REPORT</h1>
            </div>
        </div>
    </div>

    <div class="subheader">
        <table class="subheader-table">
            <tr>
                <td class="subheader-label">Shift</td>
                <td>{{ $shift ? ($shift->shift ?? '-') : '-' }}</td>
                <td class="subheader-divider"></td>
                <td class="subheader-label">Tanggal</td>
                <td>
                    @if(!empty($tanggal_dari) || !empty($tanggal_sampai))
                        {{ $tanggal_dari ?? '-' }} s/d {{ $tanggal_sampai ?? '-' }}
                    @elseif(!empty($tanggal))
                        {{ $tanggal }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @if($reports->count() === 0)
        <div class="muted" style="text-align:center; padding: 20px; border: 1px dashed #dee2e6; border-radius: 6px;">Tidak ada data</div>
    @else
        @foreach($reports as $i => $report)
            <div class="no-break">
                <div class="block-title">Report #{{ $i + 1 }}</div>

                <table class="report">
                    <tbody>
                        <tr>
                            <td style="width: 18%;" class="muted">Tanggal</td>
                            <td colspan="3">{{ $report->tanggal ? \Carbon\Carbon::parse($report->tanggal)->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="muted">Plant</td>
                            <td>
                                @if($report->id_plant && $report->plant)
                                    {{ $report->plant->plant }}
                                @elseif($report->plant_manual)
                                    {{ $report->plant_manual }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="muted">Sample Type</td>
                            <td>{{ $report->sample_type ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="muted">Masa Penyimpanan</td>
                            <td>{{ $report->masa_penyimpanan ?? '-' }}</td>
                            <td class="muted">Sample Storage</td>
                            <td>
                                @php
                                    $storages = is_array($report->sample_storage) ? $report->sample_storage : [];
                                @endphp
                                @if(count($storages) > 0)
                                    @foreach($storages as $s)
                                        <span class="badge">{{ $s }}</span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="block-title" style="margin-top:10px;">Detail Samples ({{ is_array($report->samples) ? count($report->samples) : 0 }})</div>

                <table class="report">
                    <thead>
                        <tr>
                            <th style="width: 4%;">No</th>
                            <th style="width: 24%;">Deskripsi</th>
                            <th style="width: 14%;">Supplier</th>
                            <th style="width: 14%;">Kode Produksi</th>
                            <th style="width: 12%;">Best Before</th>
                            <th style="width: 8%;">Qty</th>
                            <th style="width: 12%;">Diserahkan</th>
                            <th style="width: 12%;">Diterima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $samples = is_array($report->samples) ? $report->samples : [];
                        @endphp
                        @forelse($samples as $sIndex => $sample)
                            @php
                                $ids = (isset($sample['id_deskripsi']) && is_array($sample['id_deskripsi'])) ? $sample['id_deskripsi'] : [];
                                $descNames = collect($ids)->map(function ($uuid) use ($deskripsiMap) {
                                    return $deskripsiMap[$uuid] ?? null;
                                })->filter()->values()->implode(', ');
                            @endphp
                            <tr>
                                <td style="text-align:center;">{{ $sIndex + 1 }}</td>
                                <td>{{ $descNames !== '' ? $descNames : '-' }}</td>
                                <td>{{ $sample['id_supplier'] ?? '-' }}</td>
                                <td>{{ $sample['kode_produksi'] ?? '-' }}</td>
                                <td>
                                    @if(!empty($sample['best_before']))
                                        {{ \Carbon\Carbon::parse($sample['best_before'])->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $sample['qty'] ?? '-' }}</td>
                                <td>{{ $sample['diserahkan'] ?? '-' }}</td>
                                <td>{{ $sample['diterima'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="muted" style="text-align:center;">Tidak ada sample</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($i < $reports->count() - 1)
                <div style="height: 10px;"></div>
            @endif
        @endforeach

        <div class="signature-section">
            @php
                $firstRecord = $reports->first();
            @endphp
            <table class="signature-table">
                <tr>
                    <td class="signature-cell">
                        <div class="signature-header-item">Dibuat Oleh</div>
                        <div class="signature-space">
                            @if($firstRecord && $firstRecord->qcVerifier)
                                @php
                                    $qcQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$firstRecord->qcVerifier->name} (Tim QC)";
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
                    <td class="signature-cell">
                        <div class="signature-header-item">Diketahui Oleh</div>
                        <div class="signature-space">
                            @if($firstRecord && $firstRecord->produksiVerifier)
                                @php
                                    $prodQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$firstRecord->produksiVerifier->name} (Tim Warehouse)";
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
                    <td class="signature-cell">
                        <div class="signature-header-item">Disetujui Oleh</div>
                        <div class="signature-space">
                            @if($firstRecord && $firstRecord->spvVerifier)
                                @php
                                    $spvQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$firstRecord->spvVerifier->name} (Tim Supervisor QC)";
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
            QW 05/00
        </div>
    @endif
</body>
</html>
