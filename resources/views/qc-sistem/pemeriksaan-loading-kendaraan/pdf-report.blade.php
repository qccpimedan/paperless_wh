<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Loading Kendaraan</title>
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
            font-size: 10px;
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

        /* ===== SUBHEADER (Hari/Tanggal & Shift) — DIPERBAIKI ===== */
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
            table-layout: fixed; /* memaksa lebar kolom konsisten, mencegah kolom "melar" */
        }

        .subheader-table td {
            padding: 8px 12px;
            font-size: 9px;
            vertical-align: top;
            width: 50%;
        }

        /* Kolom kedua diberi garis tepi kiri sebagai pemisah (menggantikan .subheader-divider lama) */
        .subheader-table td.subheader-col-right {
            border-left: 1px solid #dee2e6;
        }

        .subheader-label {
            font-weight: 600;
            color: #495057;
        }

        .subheader-value {
            color: #1a1a1a;
        }

        .page-break {
            page-break-after: avoid;
            margin-bottom: 15px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            overflow: hidden;
            table-layout: fixed;
        }

        .data-column {
            width: 25%;
            border: 1px solid #dee2e6;
            padding: 12px;
            vertical-align: top;
            font-size: 9px;
            background: #fff;
        }

        .column-header {
            font-weight: bold;
            font-size: 9px;
            color: #ffffff;
            background: linear-gradient(135deg, #8b1428 0%, #5c0e1a 100%);
            padding: 8px 10px;
            margin: -12px -12px 10px -12px;
            text-align: center;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .section-title {
            font-weight: bold;
            font-size: 9px;
            color: #c41e3a;
            border-bottom: 2px solid #c41e3a;
            margin-top: 10px;
            margin-bottom: 6px;
            padding-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
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
            width: 110px;
            padding-right: 6px;
        }

        .field-value {
            display: table-cell;
            color: #1a1a1a;
            word-wrap: break-word;
        }

        .signature-section {
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: #f8f9fa;
            page-break-inside: avoid;
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
            height: 60px;
            margin: 0 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signature-line-empty {
            border-bottom: 2px solid #1a1a1a;
            height: 55px;
            width: 100%;
        }

        .qr-code-img {
            max-height: 55px;
            max-width: 55px;
        }

        .signature-name {
            font-size: 8px;
            font-weight: bold;
            color: #1a1a1a;
            padding-top: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .footer-note {
            text-align: right;
            padding-right: 10px;
            font-style: italic;
            font-size: 9px;
            color: #666;
            margin-top: 5px;
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
    </style>
</head>
<body>
    <div class="container">
        @php
            $isAllShift = $isAllShift ?? false;
            $dataPerShift = $dataPerShift ?? [['pemeriksaans' => $pemeriksaans ?? collect()]];
        @endphp

        @if(empty($dataPerShift))
            <div class="empty-message">
                <p>Tidak ada data pemeriksaan untuk semua shift pada periode yang dipilih.</p>
            </div>
        @else
            @foreach($dataPerShift as $shiftGroupIndex => $shiftGroup)
                @php
                    $pemeriksaans = $shiftGroup['pemeriksaans'];
                @endphp

                @if($pemeriksaans->count() > 0)
                    @php
                        $items = collect($pemeriksaans)->values();
                        $columnsPerPage = 4;
                        $chunks = $items->chunk($columnsPerPage);

                        $formatBool = function ($val) {
                            if ($val === null || $val === '') return '-';
                            if ($val === true || $val === 1 || $val === '1') return 'V';
                            if ($val === false || $val === 0 || $val === '0') return 'X';
                            return (string) $val;
                        };
                    @endphp

                    @foreach($chunks as $pageIndex => $pageRecords)
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
                                    <h1>PEMERIKSAAN KENDARAAN LOADING</h1>
                                </div>
                            </div>
                        </div>

                        @php
                            $firstRecord = $pageRecords->first();
                        @endphp

                        <div class="subheader">
                            <table class="subheader-table">
                                <tr>
                                    <td>
                                        <span class="subheader-label">Hari/Tanggal:</span>
                                        <span class="subheader-value">{{ $firstRecord && $firstRecord->tanggal ? (is_string($firstRecord->tanggal) ? $firstRecord->tanggal : $firstRecord->tanggal->format('d/m/Y')) : '-' }}</span>
                                    </td>
                                    <td class="subheader-col-right">
                                        <span class="subheader-label">Shift:</span>
                                        <span class="subheader-value">{{ $firstRecord->shift->shift ?? '-' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="page-break">
                            <table class="data-table">
                                <tr>
                                    @foreach($pageRecords as $colIndex => $pemeriksaan)
                                        @php
                                            $columnNumber = ($pageIndex * $columnsPerPage) + $loop->iteration;

                                            $kebersihanMobil = is_array($pemeriksaan->kondisi_kebersihan_mobil) ? $pemeriksaan->kondisi_kebersihan_mobil : (json_decode($pemeriksaan->kondisi_kebersihan_mobil ?? '[]', true) ?? []);
                                            $kondisiMobil = is_array($pemeriksaan->kondisi_mobil) ? $pemeriksaan->kondisi_mobil : (json_decode($pemeriksaan->kondisi_mobil ?? '[]', true) ?? []);

                                            $qcName = $pemeriksaan->user->name ?? '-';
                                            $produksiName = '-';
                                            $spvName = '-';

                                            if (in_array($pemeriksaan->status_verifikasi, ['approved_produksi', 'rejected_produksi'])) {
                                                $produksiName = $pemeriksaan->verifiedBy->name ?? '-';
                                            }
                                            if (in_array($pemeriksaan->status_verifikasi, ['approved_spv', 'rejected_spv'])) {
                                                $spvName = $pemeriksaan->verifiedBy->name ?? '-';
                                            }
                                        @endphp

                                        <td class="data-column">
                                            <div class="column-header">PEMERIKSAAN #{{ $columnNumber }}</div>

                                            <div class="section-title">Informasi Kendaraan</div>
                                            <div class="field-row">
                                                <span class="field-label">Ekspedisi:</span>
                                                <span class="field-value">{{ $pemeriksaan->ekspedisi->nama_ekspedisi ?? '-' }}</span>
                                            </div>
                                            <div class="field-row">
                                                <span class="field-label">Kendaraan:</span>
                                                <span class="field-value">
                                                    @if($pemeriksaan->kendaraan)
                                                        {{ $pemeriksaan->kendaraan->jenis_kendaraan ?? '-' }} - {{ $pemeriksaan->kendaraan->no_kendaraan ?? '-' }}
                                                    @elseif($pemeriksaan->jenis_kendaraan_manual)
                                                        {{ $pemeriksaan->jenis_kendaraan_manual }} - {{ $pemeriksaan->no_kendaraan_manual ?? '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="field-row">
                                                <span class="field-label">Tujuan:</span>
                                                <span class="field-value">{{ $pemeriksaan->tujuanPengiriman->nama_tujuan ?? $pemeriksaan->nama_tujuan_manual ?? '-' }}</span>
                                            </div>

                                            <div class="section-title">Waktu &amp; Suhu</div>
                                            <div class="field-row">
                                                <span class="field-label">Jam Mulai:</span>
                                                <span class="field-value">{{ $pemeriksaan->jam_mulai ?? '-' }}</span>
                                            </div>
                                            <div class="field-row">
                                                <span class="field-label">Jam Selesai:</span>
                                                <span class="field-value">{{ $pemeriksaan->jam_selesai ?? '-' }}</span>
                                            </div>
                                            <div class="field-row">
                                                <span class="field-label">Suhu Precooling:</span>
                                                <span class="field-value" style="font-family: 'DejaVu Sans', sans-serif;">{{ $pemeriksaan->suhu_precooling ?? '-' }}</span>
                                            </div>
                                            <div class="field-row">
                                                <span class="field-label">Std Precooling:</span>
                                                <span class="field-value" style="font-family: 'DejaVu Sans', sans-serif;">{{ $pemeriksaan->stdPrecooling->nama_std_precooling ?? '-' }}</span>
                                            </div>

                                            <div class="section-title">Kebersihan Mobil</div>
                                            <div class="field-row"><span class="field-label">Berdebu:</span><span class="field-value">{{ $formatBool($kebersihanMobil['berdebu'] ?? null) }}</span></div>
                                            <div class="field-row"><span class="field-label">Noda/Sampah:</span><span class="field-value">{{ $formatBool($kebersihanMobil['noda'] ?? null) }}</span></div>
                                            <div class="field-row"><span class="field-label">Mikroorganisme:</span><span class="field-value">{{ $formatBool($kebersihanMobil['mikroorganisme'] ?? null) }}</span></div>
                                            <div class="field-row"><span class="field-label">Pallet Kotor:</span><span class="field-value">{{ $formatBool($kebersihanMobil['pallet_kotor'] ?? null) }}</span></div>
                                            <div class="field-row"><span class="field-label">Aktivitas Binatang:</span><span class="field-value">{{ $formatBool($kebersihanMobil['aktivitas_binatang'] ?? null) }}</span></div>

                                            <div class="section-title">Kondisi Mobil</div>
                                            <div class="field-row"><span class="field-label">Kaca Pecah:</span><span class="field-value">{{ $formatBool($kondisiMobil['kaca_pecah'] ?? null) }}</span></div>
                                            <div class="field-row"><span class="field-label">Dinding Rusak:</span><span class="field-value">{{ $formatBool($kondisiMobil['dinding_rusak'] ?? null) }}</span></div>
                                            <div class="field-row"><span class="field-label">Lampu Pecah:</span><span class="field-value">{{ $formatBool($kondisiMobil['lampu_pecah'] ?? null) }}</span></div>
                                            <div class="field-row"><span class="field-label">Karet Pintu Rusak:</span><span class="field-value">{{ $formatBool($kondisiMobil['karet_pintu_rusak'] ?? null) }}</span></div>
                                            <div class="field-row"><span class="field-label">Pintu Rusak:</span><span class="field-value">{{ $formatBool($kondisiMobil['pintu_rusak'] ?? null) }}</span></div>
                                            <div class="field-row"><span class="field-label">Seal Tidak Utuh:</span><span class="field-value">{{ $formatBool($kondisiMobil['seal_tidak_utuh'] ?? null) }}</span></div>
                                            <div class="field-row"><span class="field-label">Terdapat Celah:</span><span class="field-value">{{ $formatBool($kondisiMobil['terdapat_celah'] ?? null) }}</span></div>

                                            <div class="section-title">Keterangan &amp; Segel</div>
                                            <div class="field-row">
                                                <span class="field-label">Segel/Gembok:</span>
                                                <span class="field-value">
                                                    @if($pemeriksaan->segel_gembok === null)
                                                        -
                                                    @elseif($pemeriksaan->segel_gembok)
                                                        Segel{{ $pemeriksaan->no_segel ? ' (No: ' . $pemeriksaan->no_segel . ')' : '' }}
                                                    @else
                                                        Gembok
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="field-row">
                                                <span class="field-label">Keterangan:</span>
                                                <span class="field-value">{{ $pemeriksaan->keterangan ?? '-' }}</span>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        </div>

                        <div class="signature-section">
                            <table class="signature-table">
                                <tr>
                                    <td class="signature-cell">
                                        <div class="signature-header-item">Dibuat Oleh</div>
                                        <div class="signature-space">
                                            @if($pageRecords->first() && $pageRecords->first()->qcVerifier)
                                                @php
                                                    $qcQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$pageRecords->first()->qcVerifier->name} (Tim QC)";
                                                    $qcQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($qcQrData);
                                                    $base64QcSvg = "data:image/svg+xml;base64," . base64_encode($qcQrCodeSvg);
                                                @endphp
                                                <img src="{{ $base64QcSvg }}" class="qr-code-img" alt="QR Code QC">
                                            @else
                                                <div class="signature-line-empty"></div>
                                            @endif
                                        </div>
                                        <div class="signature-name">{{ $pageRecords->first()->qcVerifier->name ?? '-' }}</div>
                                    </td>
                                    <td class="signature-cell">
                                        <div class="signature-header-item">Diketahui Oleh</div>
                                        <div class="signature-space">
                                            @if($pageRecords->first() && $pageRecords->first()->produksiVerifier)
                                                @php
                                                    $prodQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$pageRecords->first()->produksiVerifier->name} (Tim Warehouse)";
                                                    $prodQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($prodQrData);
                                                    $base64ProdSvg = "data:image/svg+xml;base64," . base64_encode($prodQrCodeSvg);
                                                @endphp
                                                <img src="{{ $base64ProdSvg }}" class="qr-code-img" alt="QR Code Warehouse">
                                            @else
                                                <div class="signature-line-empty"></div>
                                            @endif
                                        </div>
                                        <div class="signature-name">{{ $pageRecords->first()->produksiVerifier->name ?? '-' }}</div>
                                    </td>
                                    <td class="signature-cell">
                                        <div class="signature-header-item">Disetujui Oleh</div>
                                        <div class="signature-space">
                                            @if($pageRecords->first() && $pageRecords->first()->spvVerifier)
                                                @php
                                                    $spvQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$pageRecords->first()->spvVerifier->name} (Tim Supervisor QC)";
                                                    $spvQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->generate($spvQrData);
                                                    $base64SpvSvg = "data:image/svg+xml;base64," . base64_encode($spvQrCodeSvg);
                                                @endphp
                                                <img src="{{ $base64SpvSvg }}" class="qr-code-img" alt="QR Code SPV">
                                            @else
                                                <div class="signature-line-empty"></div>
                                            @endif
                                        </div>
                                        <div class="signature-name">{{ $pageRecords->first()->spvVerifier->name ?? '-' }}</div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="footer-note">QW 09/00</div>

                        {{-- Page break antar CHUNK KOLOM dalam shift yang sama --}}
                        @if(!$loop->last)
                            <div style="page-break-after: always;"></div>
                        @endif
                    @endforeach
                @else
                    <div class="empty-message">Tidak ada data untuk dicetak.</div>
                @endif

                {{-- PERBAIKAN: page break antar SHIFT/GROUP, supaya header shift berikutnya
                     tidak nempel di sisa halaman shift sebelumnya --}}
                @if(!$loop->last)
                    <div style="page-break-after: always;"></div>
                @endif
            @endforeach
        @endif
    </div>
</body>
</html>