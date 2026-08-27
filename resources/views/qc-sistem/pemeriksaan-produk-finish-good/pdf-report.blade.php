<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Produk Finish Good</title>
    @php
        $firstRecord = $pemeriksaans->first();
        $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
    @endphp
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            line-height: 1.2;
            color: #1a1a1a;
            background: #fff;
        }

        .container {
            width: 100%;
        }

        /* HEADER - Table Murni untuk DomPDF */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #c41e3a;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .header-logo-td {
            width: 55px;
            vertical-align: middle;
        }

        .header-logo-td img {
            width: 48px;
            height: auto;
        }

        .header-company-td {
            vertical-align: middle;
            padding-left: 5px;
        }

        .header-company-td h2 {
            font-size: 11px;
            font-weight: bold;
            color: #c41e3a;
        }

        .header-company-td p {
            font-size: 7.5px;
            color: #444;
        }

        .header-title-td {
            vertical-align: middle;
            text-align: right;
            width: 50%;
        }

        .header-title-box {
            font-size: 10.5px;
            font-weight: bold;
            color: #1a1a1a;
            background: #e9ecef;
            padding: 6px 10px;
            border-left: 4px solid #c41e3a;
            display: inline-block;
            text-align: right;
        }

        /* SUBHEADER */
        .subheader {
            width: 100%;
            border: 1px solid #dee2e6;
            margin-bottom: 10px;
            background: #f8f9fa;
        }

        .subheader-table {
            width: 100%;
            border-collapse: collapse;
        }

        .subheader-table td {
            padding: 4px 6px;
            font-size: 7.5px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }

        .subheader-label {
            font-weight: bold;
            color: #495057;
        }

        .subheader-value {
            color: #1a1a1a;
        }

        .subheader-divider {
            width: 1px;
            background: #dee2e6;
            padding: 0;
        }

        /* DATA TABLE - 4 Kolom */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 5px;
        }

        .data-column {
            width: 25%;
            border: 1px solid #dee2e6;
            padding: 5px;
            vertical-align: top;
            font-size: 7px;
            background: #fff;
        }

        .data-column.empty-col {
            background: #f8f9fa;
        }

        .column-header {
            font-weight: bold;
            font-size: 8.5px;
            color: #ffffff;
            background: #8b1428;
            padding: 4px;
            margin: -5px -5px 6px -5px;
            text-align: center;
            text-transform: uppercase;
        }

        .section-title {
            font-weight: bold;
            font-size: 7.5px;
            color: #c41e3a;
            border-bottom: 1px solid #c41e3a;
            margin-top: 5px;
            margin-bottom: 3px;
            padding-bottom: 2px;
            text-transform: uppercase;
        }

        .field-row {
            margin-bottom: 2px;
            width: 100%;
        }

        .field-label {
            font-weight: bold;
            color: #495057;
            display: inline-block;
            width: 55px;
        }

        .field-value {
            color: #1a1a1a;
            word-wrap: break-word;
        }

        /* SIGNATURE SECTION */
        .signature-section {
            margin-top: 8px;
            padding: 8px;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
        }
        
        .signature-note {
            font-size: 7px;
            color: #495057;
            padding: 4px 6px;
            background: #fff;
            border: 1px solid #e9ecef;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        
        .signature-note .ok-text {
            color: #28a745;
            font-weight: bold;
        }
        
        .signature-note .not-ok-text {
            color: #dc3545;
            font-weight: bold;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-cell {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 5px;
        }

        .signature-header-item {
            font-size: 7.5px;
            font-weight: bold;
            color: #495057;
            padding-bottom: 5px;
        }

        .signature-space {
            height: 48px;
            text-align: center;
        }

        .signature-line-empty {
            border-bottom: 1px solid #1a1a1a;
            height: 30px;
            width: 80%;
            margin: 0 auto;
        }

        .qr-code-img {
            max-height: 48px;
            max-width: 48px;
        }

        .signature-name {
            font-size: 7.5px;
            font-weight: bold;
            color: #1a1a1a;
            padding-top: 4px;
            text-transform: uppercase;
        }

        .empty-message {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #6c757d;
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">

        @php
            $isAllShift = $isAllShift ?? false;
            $dataPerShift = $dataPerShift ?? [['pemeriksaans' => $pemeriksaans ?? collect()]];
            $isFirstPage = true;
        @endphp

        @if(empty($dataPerShift))
            <div class="empty-message">
                <p>Tidak ada data pemeriksaan untuk semua shift pada periode yang dipilih.</p>
            </div>
        @else
            @foreach($dataPerShift as $shiftGroupIndex => $shiftGroup)
                @php
                    $pemeriksaans   = $shiftGroup['pemeriksaans'];
                    $qcUser         = $shiftGroup['qcUser'] ?? null;
                    $produksiUser   = $shiftGroup['produksiUser'] ?? null;
                    $spvQcUser      = $shiftGroup['spvQcUser'] ?? null;
                    $produkMap      = $shiftGroup['produkMap'] ?? [];
                @endphp

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
                            'tidak_ada_mikroba' => 'Tidak ada mikroba',
                            'lampu_cover_utuh' => 'Lampu & Cover utuh',
                            'pallet_utuh' => 'Pallet / Alas Utuh',
                            'tertutup_rapat' => 'Tertutup rapat/tidak bocor',
                            'bebas_kontaminan' => 'Bebas dari Kontaminan',
                        ];

                        foreach ($pemeriksaans as $p) {
                            $idProduks = is_array($p->id_produk_array) ? $p->id_produk_array : [];
                            $kodeProduksis = is_array($p->kode_produksi_array) ? $p->kode_produksi_array : [];
                            $expDates = is_array($p->expire_date_array) ? $p->expire_date_array : [];
                            $jmlDatangs = is_array($p->jumlah_datang_array) ? $p->jumlah_datang_array : [];
                            $unitDatangs = is_array($p->unit_datang_array) ? $p->unit_datang_array : [];
                            $jmlSamplings = is_array($p->jumlah_sampling_array) ? $p->jumlah_sampling_array : [];
                            $unitSamplings = is_array($p->unit_sampling_array) ? $p->unit_sampling_array : [];
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

                        $groupKeyFn = function($r) {
                            if (!$r) return 'unknown';
                            $tgl = is_string($r->tanggal) ? $r->tanggal : ($r->tanggal ? $r->tanggal->format('Y-m-d') : '');
                            $shift = $r->shift->shift ?? ($r->id_shift ?? '');
                            return strtolower(trim($tgl . '_' . $shift . '_' . ($r->no_mobil ?? '')));
                        };
                        $chunks = $pdfColumns->groupBy(fn($col) => $groupKeyFn($col['record']))->flatMap(fn($cols) => $cols->chunk($columnsPerPage));
                    @endphp

                    @foreach($chunks as $pageIndex => $pageRecords)
                        @php
                            $firstColumn = $pageRecords->first();
                            $firstRecord = $firstColumn ? $firstColumn['record'] : null;
                            $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
                            // Reset numbering per-record (per kedatangan)
                            $thisRecId = $firstRecord ? $groupKeyFn($firstRecord) : null;
                            if (!isset($prevRecId_fg) || $prevRecId_fg !== $thisRecId) {
                                $prevRecId_fg = $thisRecId;
                                $recPageIdx_fg = 0;
                            } else {
                                $recPageIdx_fg++;
                            }
                        @endphp

                        @if(!$isFirstPage)
                            <div class="page-break"></div>
                        @endif
                        @php $isFirstPage = false; @endphp

                        {{-- HEADER TABEL DOMPDF --}}
                        <table class="header-table">
                            <tr>
                                <td class="header-logo-td">
                                    <img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo CPI">
                                </td>
                                <td class="header-company-td">
                                    <h2>PT. CHAROEN POKPHAND INDONESIA</h2>
                                    <p>FOOD DIVISION {{ strtoupper($plantName) }}</p>
                                    <p>{{ strtoupper($plantName) }} - INDONESIA</p>
                                </td>
                                <td class="header-title-td">
                                    <div class="header-title-box">
                                        PEMERIKSAAN KEDATANGAN FINISH GOOD
                                    </div>
                                </td>
                            </tr>
                        </table>

                        {{-- SUBHEADER --}}
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

                        {{-- DATA TABEL (4 KOLOM) --}}
                        <table class="grid-table">
                            <tr>
                                @foreach($pageRecords as $colIndex => $column)
                                    @php
                                        $pemeriksaan = $column['record'];
                                        $rowIndex = $column['rowIndex'];
                                        $columnNumber = ($recPageIdx_fg * $columnsPerPage) + $loop->iteration;

                                        $idProduk = (is_array($pemeriksaan->id_produk_array) ? ($pemeriksaan->id_produk_array[$rowIndex] ?? null) : null);
                                        $kategori = (is_array($pemeriksaan->kategori_code_array) ? ($pemeriksaan->kategori_code_array[$rowIndex] ?? null) : null);
                                        $kodeProduksi = (is_array($pemeriksaan->kode_produksi_array) ? ($pemeriksaan->kode_produksi_array[$rowIndex] ?? null) : null);
                                        $expireDate = (is_array($pemeriksaan->expire_date_array) ? ($pemeriksaan->expire_date_array[$rowIndex] ?? null) : null);
                                        $jumlahDatang = (is_array($pemeriksaan->jumlah_datang_array) ? ($pemeriksaan->jumlah_datang_array[$rowIndex] ?? null) : null);
                                        $unitDatang = (is_array($pemeriksaan->unit_datang_array) ? ($pemeriksaan->unit_datang_array[$rowIndex] ?? null) : null);
                                        $jumlahSampling = (is_array($pemeriksaan->jumlah_sampling_array) ? ($pemeriksaan->jumlah_sampling_array[$rowIndex] ?? null) : null);
                                        $unitSampling = (is_array($pemeriksaan->unit_sampling_array) ? ($pemeriksaan->unit_sampling_array[$rowIndex] ?? null) : null);
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
                                        $imagePath = (is_array($pemeriksaan->image_finish_good_array) ? ($pemeriksaan->image_finish_good_array[$rowIndex] ?? null) : null);

                                        $kondisiMobilRaw = $pemeriksaan->kondisi_mobil;
                                        $kondisiMobil = is_array($kondisiMobilRaw) ? $kondisiMobilRaw : [];

                                        $produsenVal = (is_array($pemeriksaan->produsen_array) ? ($pemeriksaan->produsen_array[$rowIndex] ?? null) : null);
                                        $distributorVal = (is_array($pemeriksaan->distributor_array) ? ($pemeriksaan->distributor_array[$rowIndex] ?? null) : null);
                                        $produsenVal = $normalizeList($produsenVal);
                                        $distributorVal = $normalizeList($distributorVal);
                                    @endphp

                                    <td class="data-column">
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
                                            <span class="field-label">Kode Prod:</span>
                                            <span class="field-value">{{ $kodeProduksi ?? '-' }}</span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Exp:</span>
                                            <span class="field-value">{{ $expireDate ? ($formatDate($expireDate) ?? '-') : '-' }}</span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Jml Datang:</span>
                                            <span class="field-value">{{ $jumlahDatang ?? '-' }}{{ $unitDatang ? ' ' . $unitDatang : '' }}</span>
                                        </div>
                                        <div class="field-row">
                                            <span class="field-label">Jml Sampling:</span>
                                            <span class="field-value">{{ $jumlahSampling ?? '-' }}{{ $unitSampling ? ' ' . $unitSampling : '' }}</span>
                                        </div>

                                        <div class="section-title">Suhu</div>
                                        <div class="field-row">
                                            <span class="field-label">Kondisi:</span>
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
                                            @php 
                                                $v = data_get($kondisiMobil, $key); 
                                                $isMobilOk = ($v === true || $v === 1 || $v === '1' || strtolower((string)$v) === 'ya' || $v === 'on');
                                            @endphp
                                            <div class="field-row">
                                                <span style="color:{{ $isMobilOk ? '#28a745' : '#dc3545' }};font-weight:bold;display:inline-block;width:10px;">{{ $isMobilOk ? 'V' : 'X' }}</span>
                                                <span class="field-value">{{ $label }}</span>
                                            </div>
                                        @endforeach

                                        <div class="section-title">Pemeriksaan</div>
                                        <div class="field-row">
                                            @php $isKemasanOk = ($kondisiKemasan === true || $kondisiKemasan === 1 || $kondisiKemasan === '1' || strtolower((string)$kondisiKemasan) === 'ya'); @endphp
                                            <span style="color:{{ $isKemasanOk ? '#28a745' : '#dc3545' }};font-weight:bold;display:inline-block;width:10px;">{{ $isKemasanOk ? 'V' : 'X' }}</span>
                                            <span class="field-value">Kemasan</span>
                                        </div>
                                        <div class="field-row">
                                            @php $isWarnaOk = ($kondisiWarna === true || $kondisiWarna === 1 || $kondisiWarna === '1' || strtolower((string)$kondisiWarna) === 'ya'); @endphp
                                            <span style="color:{{ $isWarnaOk ? '#28a745' : '#dc3545' }};font-weight:bold;display:inline-block;width:10px;">{{ $isWarnaOk ? 'V' : 'X' }}</span>
                                            <span class="field-value">Warna</span>
                                        </div>
                                        <div class="field-row">
                                            @php $isAromaOk = ($kondisiAroma === true || $kondisiAroma === 1 || $kondisiAroma === '1' || strtolower((string)$kondisiAroma) === 'ya'); @endphp
                                            <span style="color:{{ $isAromaOk ? '#28a745' : '#dc3545' }};font-weight:bold;display:inline-block;width:10px;">{{ $isAromaOk ? 'V' : 'X' }}</span>
                                            <span class="field-value">Aroma</span>
                                        </div>

                                        <div class="section-title">Dokumen</div>
                                        <div class="field-row">
                                            @php $isLogoOk = ($logoHalal === true || $logoHalal === 1 || $logoHalal === '1' || strtolower((string)$logoHalal) === 'ya'); @endphp
                                            <span style="color:{{ $isLogoOk ? '#28a745' : '#dc3545' }};font-weight:bold;display:inline-block;width:10px;">{{ $isLogoOk ? 'V' : 'X' }}</span>
                                            <span class="field-value">Logo Halal</span>
                                        </div>
                                        <div class="field-row">
                                            @php $isDokOk = ($dokumenHalal === true || $dokumenHalal === 1 || $dokumenHalal === '1' || strtolower((string)$dokumenHalal) === 'ya'); @endphp
                                            <span style="color:{{ $isDokOk ? '#28a745' : '#dc3545' }};font-weight:bold;display:inline-block;width:10px;">{{ $isDokOk ? 'V' : 'X' }}</span>
                                            <span class="field-value">Dok. Halal</span>
                                        </div>
                                        <div class="field-row">
                                            @php $isCoaOk = ($coa === true || $coa === 1 || $coa === '1' || strtolower((string)$coa) === 'ya'); @endphp
                                            <span style="color:{{ $isCoaOk ? '#28a745' : '#dc3545' }};font-weight:bold;display:inline-block;width:10px;">{{ $isCoaOk ? 'V' : 'X' }}</span>
                                            <span class="field-value">COA</span>
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
                                        <div class="field-row" style="margin-top: 5px;">
                                            <span class="field-label">Foto:</span>
                                            <div class="field-value">
                                                @if($imagePath)
                                                    @php
                                                        $fullImagePath = storage_path('app/public/' . $imagePath);
                                                    @endphp
                                                    @if(file_exists($fullImagePath))
                                                        <img src="{{ $fullImagePath }}" style="max-width: 100%; height: auto; max-height: 80px; display: block; border: 1px solid #dee2e6; padding: 2px;">
                                                    @else
                                                        (File tidak ditemukan)
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                @endforeach

                                @for($i = $pageRecords->count(); $i < $columnsPerPage; $i++)
                                    <td class="data-column empty-col"></td>
                                @endfor
                            </tr>
                        </table>

                        <div style="text-align: right; font-style: italic; font-size: 8px; color: #666; margin-bottom: 5px;">
                            QW 12/00
                        </div>

                        {{-- SIGNATURE SECTION --}}
                        <div class="signature-section">
                            <div class="signature-note">
                                <span class="ok-text">V OK</span> (Sesuai Standar, Tersedia)<br>
                                <span class="not-ok-text">X</span> : Parameter Tidak Sesuai
                            </div>
                            <table class="signature-table">
                                <tr>
                                    <td class="signature-cell">
                                        <div class="signature-header-item">Dibuat Oleh</div>
                                        <div class="signature-space">
                                            @if($qcUser)
                                                @php
                                                    $qcQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$qcUser} (Tim QC)";
                                                    $qcQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(45)->generate($qcQrData);
                                                    $base64QcSvg = "data:image/svg+xml;base64," . base64_encode($qcQrCodeSvg);
                                                @endphp
                                                <img src="{{ $base64QcSvg }}" class="qr-code-img" alt="QR Code QC">
                                            @else
                                                <div class="signature-line-empty"></div>
                                            @endif
                                        </div>
                                        <div class="signature-name">{{ $qcUser ?: '-' }}</div>
                                    </td>
                                    <td class="signature-cell">
                                        <div class="signature-header-item">Diketahui Oleh</div>
                                        <div class="signature-space">
                                            @if($produksiUser)
                                                @php
                                                    $prodQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$produksiUser} (Tim Warehouse)";
                                                    $prodQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(45)->generate($prodQrData);
                                                    $base64ProdSvg = "data:image/svg+xml;base64," . base64_encode($prodQrCodeSvg);
                                                @endphp
                                                <img src="{{ $base64ProdSvg }}" class="qr-code-img" alt="QR Code Warehouse">
                                            @else
                                                <div class="signature-line-empty"></div>
                                            @endif
                                        </div>
                                        <div class="signature-name">{{ $produksiUser ?: '-' }}</div>
                                    </td>
                                    <td class="signature-cell">
                                        <div class="signature-header-item">Disetujui Oleh</div>
                                        <div class="signature-space">
                                            @if($spvQcUser)
                                                @php
                                                    $spvQrData = "Dokumen ini telah diverifikasi secara sistem oleh {$spvQcUser} (Tim Supervisor QC)";
                                                    $spvQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(45)->generate($spvQrData);
                                                    $base64SpvSvg = "data:image/svg+xml;base64," . base64_encode($spvQrCodeSvg);
                                                @endphp
                                                <img src="{{ $base64SpvSvg }}" class="qr-code-img" alt="QR Code SPV">
                                            @else
                                                <div class="signature-line-empty"></div>
                                            @endif
                                        </div>
                                        <div class="signature-name">{{ $spvQcUser ?: '-' }}</div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    @endforeach
                @else
                    <div class="empty-message">Tidak ada data untuk dicetak.</div>
                @endif
            @endforeach
        @endif
    </div>
</body>
</html>