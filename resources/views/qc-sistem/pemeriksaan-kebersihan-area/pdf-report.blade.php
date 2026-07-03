<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Kebersihan Area</title>
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

        .header {
            display: table;
            width: 100%;
            margin-bottom: 12px;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 8px;
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

        .header-title h1 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            text-align: right;
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

        .badge-ok {
            color: #1f7a1f;
            font-weight: 600;
        }

        .badge-no {
            color: #c41e3a;
            font-weight: 600;
        }

        .signature {
            margin-top: 14px;
            width: 100%;
            border-collapse: collapse;
        }

        .signature td {
            width: 33.33%;
            text-align: center;
            padding: 10px;
            border: 1px solid #dee2e6;
        }

        .signature-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 28px;
            font-size: 8px;
        }

        .signature-name {
            font-weight: 600;
            font-size: 9px;
        }

        .signature-role {
            font-size: 8px;
            color: #6c757d;
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
            height: 28px;
            width: 100%;
        }
        
        .qr-code-img {
            max-height: 55px;
            max-width: 55px;
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
                <h1>PEMERIKSAAN KEBERSIHAN AREA</h1>
            </div>
        </div>
    </div>

    <div class="subheader">
        <table class="subheader-table">
            @php
                $firstPemeriksaan = $pemeriksaans->first();
            @endphp
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
                <td class="subheader-divider"></td>
                <td class="subheader-label">Total Pemeriksaan</td>
                <td>{{ count($pemeriksaans) }} Dokumen</td>
            </tr>
        </table>
    </div>

    <table class="report">
        <tbody>
            @foreach($pemeriksaans as $p)
                @php
                    $areaData = is_string($p->area_data) ? json_decode($p->area_data, true) : $p->area_data;
                    $areaData = $areaData ?? [];
                @endphp

                @foreach($areaData as $item)
                    @php
                        $selectedArea = \App\Models\InputArea::find($item['id_area'] ?? null);
                        $selectedForm = \App\Models\InputMasterForm::find($item['id_master_form'] ?? null);
                        $fields = $selectedForm ? $selectedForm->fields : [];
                        $itemFields = collect($item['fields'] ?? []);
                    @endphp
                    <tr>
                        <td colspan="4" style="padding: 10px 0;">
                            <div style="background: #f8f9fa; padding: 8px; font-weight: bold; border-left: 4px solid #8b1428; margin-bottom: 5px; border-bottom: 1px solid #dee2e6;">
                                Area: {{ $selectedArea ? $selectedArea->nama_area : '-' }} &nbsp;|&nbsp; 
                                Form: {{ $selectedForm ? $selectedForm->nama_form : '-' }} &nbsp;|&nbsp;
                                Jam: {{ $item['jam_sebelum_proses'] ?? '-' }} / {{ $item['jam_saat_proses'] ?? '-' }}
                            </div>
                            <table class="report" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 4%;">#</th>
                                        <th style="width: 32%;">Aspek Yang Dinilai</th>
                                        <th style="width: 10%;">Sebelum</th>
                                        <th style="width: 10%;">Sesudah</th>
                                        <th style="width: 10%;">Verifikasi</th>
                                        <th style="width: 17%;">Keterangan</th>
                                        <th style="width: 17%;">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fields as $fIdx => $field)
                                        @php
                                            $d = $itemFields->firstWhere('id_master_form_field', $field->id);
                                        @endphp
                                        <tr>
                                            <td style="text-align:center;">{{ $fIdx + 1 }}</td>
                                            <td>{{ $field->field_name }}</td>
                                            <td style="text-align:center;">
                                                @if(isset($d['status_sebelum_proses']))
                                                    <span class="{{ $d['status_sebelum_proses'] == 1 ? 'badge-ok' : 'badge-no' }}">
                                                        {{ $d['status_sebelum_proses'] == 1 ? 'OK' : 'NO' }}
                                                    </span>
                                                @else - @endif
                                            </td>
                                            <td style="text-align:center;">
                                                @if(isset($d['status_saat_proses']))
                                                    <span class="{{ $d['status_saat_proses'] == 1 ? 'badge-ok' : 'badge-no' }}">
                                                        {{ $d['status_saat_proses'] == 1 ? 'OK' : 'NO' }}
                                                    </span>
                                                @else - @endif
                                            </td>
                                            <td style="text-align:center;">
                                                @if(isset($d['verifikasi_hasil']))
                                                    <span class="{{ $d['verifikasi_hasil'] == 1 ? 'badge-ok' : 'badge-no' }}">
                                                        {{ $d['verifikasi_hasil'] == 1 ? 'OK' : 'NO' }}
                                                    </span>
                                                @else - @endif
                                            </td>
                                            <td>{{ $d['keterangan'] ?? '-' }}</td>
                                            <td>{{ $d['tindakan_koreksi'] ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" style="text-align:center;">Tidak ada aspek penilaian</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
        QW 02/00
    </div>
    <div class="signature-section">
        @php
            $firstRecord = $pemeriksaans->first();
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
</body>
</html>
