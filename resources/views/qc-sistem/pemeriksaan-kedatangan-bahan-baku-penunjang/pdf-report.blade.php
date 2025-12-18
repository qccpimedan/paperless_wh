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
                $recordsPerPage = 4;
                $chunks = $pemeriksaans->chunk($recordsPerPage);
                $firstRecord = $pemeriksaans->first();
            @endphp
            
            @foreach($chunks as $pageIndex => $pageRecords)
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
                            @foreach($pageRecords as $index => $pemeriksaan)
                                @php
                                    $columnNumber = ($pageIndex * $recordsPerPage) + $loop->iteration;
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

                                    {{-- BAHAN BAKU PENUNJANG --}}
                                    @php
                                        $idBahanArray = json_decode($pemeriksaan->id_bahan_array ?? '[]', true);
                                        $produsenArray = json_decode($pemeriksaan->produsen_array ?? '[]', true);
                                        $negaraProdusenArray = json_decode($pemeriksaan->negara_produsen_array ?? '[]', true);
                                        $distributorArray = json_decode($pemeriksaan->distributor_array ?? '[]', true);
                                        $kodeProduksiArray = json_decode($pemeriksaan->kode_produksi_array ?? '[]', true);
                                        $expireDateArray = json_decode($pemeriksaan->expire_date_array ?? '[]', true);
                                        $jumlahDatangArray = json_decode($pemeriksaan->jumlah_datang_array ?? '[]', true);
                                        $jumlahSamplingArray = json_decode($pemeriksaan->jumlah_sampling_array ?? '[]', true);
                                        $spesifikasiArray = json_decode($pemeriksaan->spesifikasi_array ?? '[]', true);
                                        $kondisiProdukArray = json_decode($pemeriksaan->kondisi_produk ?? '[]', true);
                                        $suhuProdukArray = json_decode($pemeriksaan->suhu_produk ?? '[]', true);
                                        $suhuProdukTypeArray = json_decode($pemeriksaan->suhu_produk_type ?? '[]', true);
                                        $kondisiProdukSuhuArray = json_decode($pemeriksaan->kondisi_produk_suhu ?? '[]', true);
                                        $hasilUjiFfaArray = json_decode($pemeriksaan->hasil_uji_ffa_array ?? '[]', true);
                                        
                                        $rowCount = max(
                                            count($idBahanArray),
                                            count($produsenArray),
                                            count($negaraProdusenArray),
                                            count($distributorArray),
                                            count($kodeProduksiArray),
                                            count($expireDateArray),
                                            count($jumlahDatangArray),
                                            count($jumlahSamplingArray),
                                            count($spesifikasiArray)
                                        );
                                    @endphp
                                    
                                    @if($rowCount > 0)
                                        @for($i = 0; $i < $rowCount; $i++)
                                            <div class="section-title">Bahan Baku {{ $i + 1 }}</div>
                                            @if(isset($idBahanArray[$i]) && $idBahanArray[$i])
                                                @php
                                                    $bahan = \App\Models\Bahan::find($idBahanArray[$i]);
                                                @endphp
                                                @if($bahan)
                                                    <div class="field-row">
                                                        <span class="field-label">Nama:</span>
                                                        <span class="field-value">{{ $bahan->nama_bahan }}</span>
                                                    </div>
                                                @endif
                                            @endif
                                            @if(isset($produsenArray[$i]) && $produsenArray[$i])
                                                <div class="field-row">
                                                    <span class="field-label">Produsen:</span>
                                                    <span class="field-value">{{ $produsenArray[$i] }}</span>
                                                </div>
                                            @endif
                                            @if(isset($negaraProdusenArray[$i]) && $negaraProdusenArray[$i])
                                                <div class="field-row">
                                                    <span class="field-label">Negara:</span>
                                                    <span class="field-value">{{ $negaraProdusenArray[$i] }}</span>
                                                </div>
                                            @endif
                                            @if(isset($distributorArray[$i]) && $distributorArray[$i])
                                                <div class="field-row">
                                                    <span class="field-label">Distributor:</span>
                                                    <span class="field-value">{{ $distributorArray[$i] }}</span>
                                                </div>
                                            @endif
                                            @if(isset($kodeProduksiArray[$i]) && $kodeProduksiArray[$i])
                                                <div class="field-row">
                                                    <span class="field-label">Kode:</span>
                                                    <span class="field-value">{{ $kodeProduksiArray[$i] }}</span>
                                                </div>
                                            @endif
                                            @if(isset($expireDateArray[$i]) && $expireDateArray[$i])
                                                <div class="field-row">
                                                    <span class="field-label">Exp Date:</span>
                                                    <span class="field-value">{{ \Carbon\Carbon::parse($expireDateArray[$i])->format('d/m/Y') }}</span>
                                                </div>
                                            @endif
                                            @if(isset($jumlahDatangArray[$i]) && $jumlahDatangArray[$i])
                                                <div class="field-row">
                                                    <span class="field-label">Jml Datang:</span>
                                                    <span class="field-value">{{ $jumlahDatangArray[$i] }}</span>
                                                </div>
                                            @endif
                                            @if(isset($jumlahSamplingArray[$i]) && $jumlahSamplingArray[$i])
                                                <div class="field-row">
                                                    <span class="field-label">Jml Sampling:</span>
                                                    <span class="field-value">{{ $jumlahSamplingArray[$i] }}</span>
                                                </div>
                                            @endif
                                            @if(isset($spesifikasiArray[$i]) && $spesifikasiArray[$i])
                                                <div class="field-row">
                                                    <span class="field-label">Spesifikasi:</span>
                                                    <span class="field-value">{{ $spesifikasiArray[$i] }}</span>
                                                </div>
                                            @endif
                                            
                                            {{-- Suhu & Kondisi --}}
                                            @if(isset($kondisiProdukArray[$i]) || isset($suhuProdukTypeArray[$i]) || isset($suhuProdukArray[$i]) || isset($kondisiProdukSuhuArray[$i]) || isset($hasilUjiFfaArray[$i]))
                                                <div style="margin-top: 6px; padding-top: 6px; border-top: 1px dashed #dee2e6;"></div>
                                                @if(isset($kondisiProdukArray[$i]) && $kondisiProdukArray[$i])
                                                    <div class="field-row">
                                                        <span class="field-label">Kondisi:</span>
                                                        <span class="field-value">{{ $kondisiProdukArray[$i] }}</span>
                                                    </div>
                                                @endif
                                                @if(isset($suhuProdukTypeArray[$i]) && $suhuProdukTypeArray[$i])
                                                    <div class="field-row">
                                                        <span class="field-label">Jenis Suhu:</span>
                                                        <span class="field-value">{{ $suhuProdukTypeArray[$i] }}</span>
                                                    </div>
                                                @endif
                                                @if(isset($suhuProdukArray[$i]) && $suhuProdukArray[$i])
                                                    <div class="field-row">
                                                        <span class="field-label">Suhu:</span>
                                                        <span class="field-value">{{ $suhuProdukArray[$i] }}°C</span>
                                                    </div>
                                                @endif
                                                @if(isset($kondisiProdukSuhuArray[$i]) && $kondisiProdukSuhuArray[$i])
                                                    <div class="field-row">
                                                        <span class="field-label">Suhu Kondisi:</span>
                                                        <span class="field-value">{{ $kondisiProdukSuhuArray[$i] }}</span>
                                                    </div>
                                                @endif
                                                @if(isset($hasilUjiFfaArray[$i]) && $hasilUjiFfaArray[$i])
                                                    <div class="field-row">
                                                        <span class="field-label">Hasil FFA:</span>
                                                        <span class="field-value">{{ $hasilUjiFfaArray[$i] }}</span>
                                                    </div>
                                                @endif
                                            @endif
                                        @endfor
                                    @endif

                                    {{-- KONDISI FISIK --}}
                                    @php
                                        $kondisiFisikArray = json_decode($pemeriksaan->kondisi_fisik_array ?? '[]', true);
                                    @endphp
                                    @if(count($kondisiFisikArray) > 0)
                                        @foreach($kondisiFisikArray as $idx => $kondisiFisik)
                                            <div class="section-title">Kondisi Fisik {{ $idx + 1 }}</div>
                                            @if(isset($kondisiFisik['kemasan']))
                                                <div class="field-row">
                                                    <span class="field-label">Kemasan:</span>
                                                    <span class="field-value">{{ $kondisiFisik['kemasan'] ? 'V' : 'X' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($kondisiFisik['warna']))
                                                <div class="field-row">
                                                    <span class="field-label">Warna:</span>
                                                    <span class="field-value">{{ $kondisiFisik['warna'] ? 'V' : 'X' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($kondisiFisik['benda_asing']))
                                                <div class="field-row">
                                                    <span class="field-label">Benda Asing:</span>
                                                    <span class="field-value">{{ $kondisiFisik['benda_asing'] ? 'V' : 'X' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($kondisiFisik['aroma']))
                                                <div class="field-row">
                                                    <span class="field-label">Aroma:</span>
                                                    <span class="field-value">{{ $kondisiFisik['aroma'] ? 'V' : 'X' }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif

                                    {{-- DOKUMEN --}}
                                    @php
                                        $logoHalalArray = json_decode($pemeriksaan->logo_halal_array ?? '[]', true);
                                        $dokumenHalalArray = json_decode($pemeriksaan->dokumen_halal_array ?? '[]', true);
                                        $coaArray = json_decode($pemeriksaan->coa_array ?? '[]', true);
                                        $maxDokumen = max(count($logoHalalArray), count($dokumenHalalArray), count($coaArray));
                                    @endphp
                                    @if($maxDokumen > 0)
                                        @for($idx = 0; $idx < $maxDokumen; $idx++)
                                            <div class="section-title">Dokumen {{ $idx + 1 }}</div>
                                            <div class="field-row">
                                                <span class="field-label">Logo Halal:</span>
                                                <span class="field-value">{{ ($logoHalalArray[$idx] ?? false) ? 'V' : 'X' }}</span>
                                            </div>
                                            <div class="field-row">
                                                <span class="field-label">Halal Berlaku:</span>
                                                <span class="field-value">{{ ($dokumenHalalArray[$idx] ?? false) ? 'V' : 'X' }}</span>
                                            </div>
                                            <div class="field-row">
                                                <span class="field-label">COA:</span>
                                                <span class="field-value">{{ ($coaArray[$idx] ?? false) ? 'V' : 'X' }}</span>
                                            </div>
                                        @endfor
                                    @endif

                                    {{-- STATUS --}}
                                    @php
                                        $statusBarisArray = json_decode($pemeriksaan->status_baris_array ?? '[]', true);
                                        $keteranganArray = json_decode($pemeriksaan->keterangan_array ?? '[]', true);
                                        $maxStatus = max(count($statusBarisArray), count($keteranganArray));
                                    @endphp
                                    @if($maxStatus > 0)
                                        @for($idx = 0; $idx < $maxStatus; $idx++)
                                            <div class="section-title">Status {{ $idx + 1 }}</div>
                                            @if(isset($statusBarisArray[$idx]))
                                                <div class="field-row">
                                                    <span class="field-label">Status:</span>
                                                    <span class="field-value">
                                                        @if(strtolower($statusBarisArray[$idx]) == 'release')
                                                            <span class="status-badge status-release">{{ $statusBarisArray[$idx] }}</span>
                                                        @elseif(strtolower($statusBarisArray[$idx]) == 'hold')
                                                            <span class="status-badge status-hold">{{ $statusBarisArray[$idx] }}</span>
                                                        @else
                                                            {{ $statusBarisArray[$idx] }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                            @if(isset($keteranganArray[$idx]) && $keteranganArray[$idx])
                                                <div class="field-row">
                                                    <span class="field-label">Ket:</span>
                                                    <span class="field-value">{{ $keteranganArray[$idx] }}</span>
                                                </div>
                                            @endif
                                        @endfor
                                    @endif
                                </td>
                            @endforeach
                            
                            {{-- Fill empty columns if less than 4 records --}}
                            @for($i = $pageRecords->count(); $i < $recordsPerPage; $i++)
                                <td class="data-column" style="background: #f8f9fa;"></td>
                            @endfor
                        </tr>
                    </table>
                    <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
                        QW 01/00
                    </div>
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