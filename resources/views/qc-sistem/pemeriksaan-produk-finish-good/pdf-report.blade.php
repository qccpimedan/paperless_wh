<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Produk Finish Good</title>
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
            font-size: 8px;
            line-height: 1.25;
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

        .page-break {
            margin-bottom: 15px;
        }

        .cards {
            width: 100%;
        }

        .card {
            width: 48.5%;
            float: left;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
            vertical-align: top;
            font-size: 7px;
            background: #fff;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }

        .card-right {
            float: right;
        }

        .clear {
            clear: both;
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
            margin-top: 8px;
            margin-bottom: 4px;
            padding-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .field-row {
            margin-bottom: 3px;
            display: table;
            width: 100%;
        }

        .field-label {
            display: table-cell;
            font-weight: 600;
            color: #495057;
            width: 55px;
            padding-right: 5px;
        }

        .field-value {
            display: table-cell;
            color: #1a1a1a;
            word-wrap: break-word;
        }

        .signature-section {
            margin-top: 10px;
            padding: 10px;
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
            padding: 0 10px;
            vertical-align: top;
        }

        .signature-header-item {
            font-size: 7px;
            font-weight: 600;
            color: #495057;
            padding-bottom: 14px;
        }

        .signature-space {
            border-bottom: 2px solid #1a1a1a;
            height: 26px;
            margin: 0 10px;
        }

        .signature-name {
            font-size: 7px;
            font-weight: bold;
            color: #1a1a1a;
            padding-top: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .footer {
            margin-top: 10px;
            padding: 8px 12px;
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
                    <h1>PEMERIKSAAN PRODUK FINISH GOOD</h1>
                </div>
            </div>
        </div>

        @if($pemeriksaans->count() > 0)
            @php
                $columnsPerPage = 4;
                $pdfColumns = collect();
                $formatBool = function ($val) {
                    if ($val === null || $val === '') return '-';
                    if ($val === true || $val === 1 || $val === '1' || $val === 'Ya' || $val === 'ya') return 'Ya';
                    if ($val === false || $val === 0 || $val === '0' || $val === 'Tidak' || $val === 'tidak') return 'Tidak';
                    return (string) $val;
                };
                $formatTemp = function ($val) {
                    if ($val === null || $val === '') return null;
                    $s = (string) $val;
                    return str_contains($s, '°') ? $s : ($s . '°C');
                };
                $formatDate = function ($val) {
                    if ($val === null || $val === '') return null;
                    try {
                        return \Carbon\Carbon::parse($val)->format('d/m/Y');
                    } catch (\Throwable $e) {
                        return (string) $val;
                    }
                };
                $normalizeList = function ($val) {
                    if ($val === null || $val === '') return null;
                    if (is_array($val)) {
                        $arr = array_values(array_filter($val, fn ($v) => $v !== null && $v !== ''));
                        return count($arr) ? implode(', ', $arr) : null;
                    }
                    if (is_string($val)) {
                        $decoded = json_decode($val, true);
                        if (is_array($decoded)) {
                            $arr = array_values(array_filter($decoded, fn ($v) => $v !== null && $v !== ''));
                            return count($arr) ? implode(', ', $arr) : null;
                        }
                    }
                    return (string) $val;
                };
                $kondisiMobilLabels = [
                    'bersih' => 'Bersih',
                    'bebas_hama' => 'Bebas dari hama',
                    'tidak_kondensasi' => 'Tidak Kondensasi',
                    'bebas_produk_halal' => 'Bebas dari Produk Non Halal',
                    'tidak_berbau' => 'Tidak Berbau Menyimpang',
                    'tidak_ada_sampah' => 'Tidak ada sampah',
                    'tidak_ada_mikroba' => 'Tidak ada pertumbuhan mikroba',
                    'lampu_cover_utuh' => 'Lampu dan Cover tidak pecah',
                    'pallet_utuh' => 'Pallet / Alas Utuh',
                    'tertutup_rapat' => 'Tertutup rapat/tidak bocor',
                    'bebas_kontaminan' => 'Bebas dari Kontaminan',
                ];
                foreach ($pemeriksaans as $p) {
                    $idProduks = is_array($p->id_produk_array) ? $p->id_produk_array : [];
                    $kodeProduksis = is_array($p->kode_produksi_array) ? $p->kode_produksi_array : [];
                    $expDates = is_array($p->expire_date_array) ? $p->expire_date_array : [];
                    $jmlDatangs = is_array($p->jumlah_datang_array) ? $p->jumlah_datang_array : [];
                    $jmlSamplings = is_array($p->jumlah_sampling_array) ? $p->jumlah_sampling_array : [];
                    $kategoriArr = is_array($p->kategori_code_array) ? $p->kategori_code_array : [];
                    $negaraArr = is_array($p->negara_produsen_array) ? $p->negara_produsen_array : [];
                    $kondisiProdukArr = is_array($p->kondisi_produk_array) ? $p->kondisi_produk_array : [];
                    $kondisiProdukSuhuArr = is_array($p->kondisi_produk_suhu_value_array) ? $p->kondisi_produk_suhu_value_array : [];
                    $suhuMobilTypeArr = is_array($p->suhu_mobil_type_array) ? $p->suhu_mobil_type_array : [];
                    $suhuMobilValArr = is_array($p->suhu_mobil_value_array) ? $p->suhu_mobil_value_array : [];
                    $suhuProdukTypeArr = is_array($p->suhu_produk_type_array) ? $p->suhu_produk_type_array : [];
                    $suhuProdukValArr = is_array($p->suhu_produk_value_array) ? $p->suhu_produk_value_array : [];

                    $kemasanArr = is_array($p->kondisi_kemasan_array) ? $p->kondisi_kemasan_array : [];
                    $warnaArr = is_array($p->kondisi_warna_array) ? $p->kondisi_warna_array : [];
                    $aromaArr = is_array($p->kondisi_aroma_array) ? $p->kondisi_aroma_array : [];
                    $logoHalalArr = is_array($p->logo_halal_array) ? $p->logo_halal_array : [];
                    $dokumenHalalArr = is_array($p->dokumen_halal_array) ? $p->dokumen_halal_array : [];
                    $coaArr = is_array($p->coa_array) ? $p->coa_array : [];
                    $statusArr = is_array($p->status_array) ? $p->status_array : [];
                    $ketArr = is_array($p->keterangan_array) ? $p->keterangan_array : [];

                    $rowCount = max(
                        1,
                        count($idProduks),
                        count($kodeProduksis),
                        count($expDates),
                        count($jmlDatangs),
                        count($jmlSamplings),
                        count($kategoriArr),
                        count($negaraArr),
                        count($kondisiProdukArr),
                        count($kondisiProdukSuhuArr),
                        count($suhuMobilTypeArr),
                        count($suhuMobilValArr),
                        count($suhuProdukTypeArr),
                        count($suhuProdukValArr),
                        count($kemasanArr),
                        count($warnaArr),
                        count($aromaArr),
                        count($logoHalalArr),
                        count($dokumenHalalArr),
                        count($coaArr),
                        count($statusArr),
                        count($ketArr)
                    );

                    for ($i = 0; $i < $rowCount; $i++) {
                        $pdfColumns->push([
                            'record' => $p,
                            'rowIndex' => $i,
                        ]);
                    }
                }

                $chunks = $pdfColumns->chunk($columnsPerPage);
            @endphp

            @foreach($chunks as $pageIndex => $pageRecords)
                @php
                    $firstColumn = $pageRecords->first();
                    $firstRecord = $firstColumn ? $firstColumn['record'] : null;
                @endphp

                <div class="subheader">
                    <table class="subheader-table">
                        <tr>
                            <td>
                                <span class="subheader-label">Hari/Tanggal:</span>
                                <span class="subheader-value">{{ $firstRecord && $firstRecord->tanggal ? (is_string($firstRecord->tanggal) ? $firstRecord->tanggal : $firstRecord->tanggal->format('d/m/Y')) : '-' }}</span>
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
                                <span class="subheader-value">{{ $firstRecord && $firstRecord->segel_gembok ? ucfirst($firstRecord->segel_gembok) : '-' }}</span>
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

                <div class="page-break">
                    <div class="cards">
                        @foreach($pageRecords as $colIndex => $column)
                            @php
                                $pemeriksaan = $column['record'];
                                $rowIndex = $column['rowIndex'];
                                $columnNumber = ($pageIndex * $columnsPerPage) + $loop->iteration;

                                $idProduk = (is_array($pemeriksaan->id_produk_array) ? ($pemeriksaan->id_produk_array[$rowIndex] ?? null) : null);
                                $kategori = (is_array($pemeriksaan->kategori_code_array) ? ($pemeriksaan->kategori_code_array[$rowIndex] ?? null) : null);
                                $kodeProduksi = (is_array($pemeriksaan->kode_produksi_array) ? ($pemeriksaan->kode_produksi_array[$rowIndex] ?? null) : null);
                                $expireDate = (is_array($pemeriksaan->expire_date_array) ? ($pemeriksaan->expire_date_array[$rowIndex] ?? null) : null);
                                $jumlahDatang = (is_array($pemeriksaan->jumlah_datang_array) ? ($pemeriksaan->jumlah_datang_array[$rowIndex] ?? null) : null);
                                $jumlahSampling = (is_array($pemeriksaan->jumlah_sampling_array) ? ($pemeriksaan->jumlah_sampling_array[$rowIndex] ?? null) : null);
                                $negara = (is_array($pemeriksaan->negara_produsen_array) ? ($pemeriksaan->negara_produsen_array[$rowIndex] ?? null) : null);
                                $kondisiProduk = (is_array($pemeriksaan->kondisi_produk_array) ? ($pemeriksaan->kondisi_produk_array[$rowIndex] ?? null) : null);
                                $kondisiProdukSuhu = (is_array($pemeriksaan->kondisi_produk_suhu_value_array) ? ($pemeriksaan->kondisi_produk_suhu_value_array[$rowIndex] ?? null) : null);

                                $suhuMobilType = (is_array($pemeriksaan->suhu_mobil_type_array) ? ($pemeriksaan->suhu_mobil_type_array[$rowIndex] ?? null) : null);
                                $suhuMobilValue = (is_array($pemeriksaan->suhu_mobil_value_array) ? ($pemeriksaan->suhu_mobil_value_array[$rowIndex] ?? null) : null);
                                $suhuProdukType = (is_array($pemeriksaan->suhu_produk_type_array) ? ($pemeriksaan->suhu_produk_type_array[$rowIndex] ?? null) : null);
                                $suhuProdukValue = (is_array($pemeriksaan->suhu_produk_value_array) ? ($pemeriksaan->suhu_produk_value_array[$rowIndex] ?? null) : null);

                                $kondisiKemasan = (is_array($pemeriksaan->kondisi_kemasan_array) ? ($pemeriksaan->kondisi_kemasan_array[$rowIndex] ?? null) : null);
                                $kondisiWarna = (is_array($pemeriksaan->kondisi_warna_array) ? ($pemeriksaan->kondisi_warna_array[$rowIndex] ?? null) : null);
                                $kondisiAroma = (is_array($pemeriksaan->kondisi_aroma_array) ? ($pemeriksaan->kondisi_aroma_array[$rowIndex] ?? null) : null);
                                $logoHalal = (is_array($pemeriksaan->logo_halal_array) ? ($pemeriksaan->logo_halal_array[$rowIndex] ?? null) : null);
                                $dokumenHalal = (is_array($pemeriksaan->dokumen_halal_array) ? ($pemeriksaan->dokumen_halal_array[$rowIndex] ?? null) : null);
                                $coa = (is_array($pemeriksaan->coa_array) ? ($pemeriksaan->coa_array[$rowIndex] ?? null) : null);
                                $statusBaris = (is_array($pemeriksaan->status_array) ? ($pemeriksaan->status_array[$rowIndex] ?? null) : null);
                                $keterangan = (is_array($pemeriksaan->keterangan_array) ? ($pemeriksaan->keterangan_array[$rowIndex] ?? null) : null);

                                $kondisiMobilRaw = $pemeriksaan->kondisi_mobil;
                                $kondisiMobil = is_array($kondisiMobilRaw) ? $kondisiMobilRaw : [];

                                $produsenVal = (is_array($pemeriksaan->produsen_array) ? ($pemeriksaan->produsen_array[$rowIndex] ?? null) : null);
                                $distributorVal = (is_array($pemeriksaan->distributor_array) ? ($pemeriksaan->distributor_array[$rowIndex] ?? null) : null);
                                $produsenVal = $normalizeList($produsenVal);
                                $distributorVal = $normalizeList($distributorVal);

                                $isRight = ($loop->iteration % 2 === 0);
                            @endphp

                            <div class="card {{ $isRight ? 'card-right' : '' }}">
                                <div class="column-header">PEMERIKSAAN #{{ $columnNumber }}</div>

                                <div class="section-title">Produk</div>
                                <div class="field-row">
                                    <span class="field-label">Kategori:</span>
                                    <span class="field-value">{{ $kategori ?? '-' }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Nama:</span>
                                    <span class="field-value">{{ $idProduk ? ($produkMap[$idProduk] ?? 'N/A') : '-' }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Negara:</span>
                                    <span class="field-value">{{ $negara ?? '-' }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Produsen:</span>
                                    <span class="field-value">{{ $produsenVal ?: '-' }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Distributor:</span>
                                    <span class="field-value">{{ $distributorVal ?: '-' }}</span>
                                </div>

                                <div class="section-title">Batch</div>
                                <div class="field-row">
                                    <span class="field-label">Kode Produksi:</span>
                                    <span class="field-value">{{ $kodeProduksi ?? '-' }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Exp:</span>
                                    <span class="field-value">{{ $expireDate ? ($formatDate($expireDate) ?? '-') : '-' }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Jumlah Datang:</span>
                                    <span class="field-value">{{ $jumlahDatang ?? '-' }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Jumlah Sampling:</span>
                                    <span class="field-value">{{ $jumlahSampling ?? '-' }}</span>
                                </div>
                                
                                <div class="section-title">Suhu</div>
                                <div class="field-row">
                                    <span class="field-label">Kondisi Produk:</span>
                                    <span class="field-value">{{ $kondisiProduk ? ($kondisiProduk . (($kondisiProdukSuhu !== null && $kondisiProdukSuhu !== '') ? (' ' . $formatTemp($kondisiProdukSuhu)) : '')) : '-' }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Suhu Mobil:</span>
                                    <span class="field-value">{{ $suhuMobilType ? ($suhuMobilType . ' ' . ($formatTemp($suhuMobilValue) ?? '')) : '-' }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Suhu Produk:</span>
                                    <span class="field-value">{{ $suhuProdukType ? ($suhuProdukType . ' ' . ($formatTemp($suhuProdukValue) ?? '')) : '-' }}</span>
                                </div>

                                <div class="section-title">Kondisi Mobil</div>
                                @foreach($kondisiMobilLabels as $key => $label)
                                    @php $v = data_get($kondisiMobil, $key); @endphp
                                    <div class="field-row">
                                        <span class="field-label">{{ $label }}:</span>
                                        <span class="field-value">{{ $formatBool($v) }}</span>
                                    </div>
                                @endforeach

                                <div class="section-title">Pemeriksaan</div>
                                <div class="field-row">
                                    <span class="field-label">Kemasan:</span>
                                    <span class="field-value">{{ $formatBool($kondisiKemasan) }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Warna:</span>
                                    <span class="field-value">{{ $formatBool($kondisiWarna) }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Aroma:</span>
                                    <span class="field-value">{{ $formatBool($kondisiAroma) }}</span>
                                </div>

                                <div class="section-title">Dokumen</div>
                                <div class="field-row">
                                    <span class="field-label">Logo Halal:</span>
                                    <span class="field-value">{{ $formatBool($logoHalal) }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Dokumen Halal:</span>
                                    <span class="field-value">{{ $formatBool($dokumenHalal) }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">COA:</span>
                                    <span class="field-value">{{ $formatBool($coa) }}</span>
                                </div>

                                <div class="section-title">Status</div>
                                <div class="field-row">
                                    <span class="field-label">Status:</span>
                                    <span class="field-value">{{ $statusBaris ?? '-' }}</span>
                                </div>
                                <div class="field-row">
                                    <span class="field-label">Ket:</span>
                                    <span class="field-value">{{ $keterangan ?? '-' }}</span>
                                </div>
                            </div>

                            @if($loop->iteration % 2 === 0)
                                <div class="clear"></div>
                            @endif
                        @endforeach

                        <div class="clear"></div>
                    </div>
                </div>

                <div class="signature-section">
                    <table class="signature-table">
                        <tr>
                            <td class="signature-cell">
                                <div class="signature-header-item">Dibuat Oleh (QC)</div>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $qcUser ?: '-' }}</div>
                            </td>
                            <td class="signature-cell">
                                <div class="signature-header-item">Disetujui Oleh (Produksi)</div>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $produksiUser ?: '-' }}</div>
                            </td>
                            <td class="signature-cell">
                                <div class="signature-header-item">Diverifikasi Oleh (SPV QC)</div>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $spvQcUser ?: '-' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="text-align: right; padding-right: 10px; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
                    QW 12/00
                </div>

                <!-- <div class="footer">
                    <p class="footer-main">Sistem QC - Pemeriksaan Produk Finish Good</p>
                    <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
                </div> -->

                @if(!$loop->last)
                    <div style="page-break-after: always;"></div>
                @endif
            @endforeach
        @else
            <div class="empty-message">Tidak ada data untuk dicetak.</div>
        @endif
    </div>
</body>
</html>
