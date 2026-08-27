<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Kedatangan Kemasan</title>
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
        
        /* HEADER - Menggunakan Tabel Standar DomPDF */
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
            width: 45%;
        }
        
        .header-title-box {
            font-size: 11px;
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

        /* DATA TABLE - 4 Kolom Menggunakan Tabel Murni */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
        }
        
        .data-column {
            width: 25%;
            border: 1px solid #dee2e6;
            padding: 5px;
            vertical-align: top;
            font-size: 7.5px;
            background: #fff;
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
            margin-top: 6px;
            margin-bottom: 4px;
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
            width: 50px;
        }
        
        .field-value {
            color: #1a1a1a;
            word-wrap: break-word;
        }
        
        .check-item {
            color: #28a745;
            font-weight: bold;
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
            height: 50px;
            text-align: center;
        }

        .signature-line-empty {
            border-bottom: 1px solid #1a1a1a;
            height: 35px;
            width: 80%;
            margin: 0 auto;
        }
        
        .qr-code-img {
            max-height: 50px;
            max-width: 50px;
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
                @endphp

        @if($pemeriksaans->count() > 0)
            @php
                $columnsPerPage = 4;
                $allBahanIds = [];

                $pdfColumns = collect();
                foreach ($pemeriksaans as $p) {
                    $idBahansTmp = json_decode($p->id_bahan_array ?? '[]', true) ?? [];

                    if (!empty($idBahansTmp)) {
                        foreach ($idBahansTmp as $tmpId) {
                            if ($tmpId) {
                                $allBahanIds[] = $tmpId;
                            }
                        }
                    }

                    $produsensTmp = json_decode($p->produsen_array ?? '[]', true) ?? [];
                    $distributorsTmp = json_decode($p->distributor_array ?? '[]', true) ?? [];
                    $kodeProduksisTmp = json_decode($p->kode_produksi_array ?? '[]', true) ?? [];
                    $jumlahDatangsTmp = json_decode($p->jumlah_datang_array ?? '[]', true) ?? [];
                    $jumlahSamplingsTmp = json_decode($p->jumlah_sampling_array ?? '[]', true) ?? [];
                    $spesifikasisTmp = json_decode($p->spesifikasi_array ?? '[]', true) ?? [];
                    $penampakansTmp = json_decode($p->penampakan_array ?? '[]', true) ?? [];
                    $sealingsTmp = json_decode($p->sealing_array ?? '[]', true) ?? [];
                    $cetakansTmp = json_decode($p->cetakan_array ?? '[]', true) ?? [];
                    $ketebalanMicronsTmp = json_decode($p->ketebalan_micron_array ?? '[]', true) ?? [];
                    $dimensisTmp = json_decode($p->dimensi_array ?? '[]', true) ?? [];
                    $statusesTmp = json_decode($p->status_array ?? '[]', true) ?? [];
                    $logoHalalsTmp = json_decode($p->logo_halal_array ?? '[]', true) ?? [];
                    $dokumenHalalsTmp = json_decode($p->dokumen_halal_array ?? '[]', true) ?? [];
                    $coasTmp = json_decode($p->coa_array ?? '[]', true) ?? [];
                    $keterangansTmp = json_decode($p->keterangan_array ?? '[]', true) ?? [];
                    $imageKemasansTmp = json_decode($p->image_kemasan_array ?? '[]', true) ?? [];

                    $rowCount = max(
                        1,
                        count($idBahansTmp),
                        count($produsensTmp),
                        count($distributorsTmp),
                        count($kodeProduksisTmp),
                        count($jumlahDatangsTmp),
                        count($jumlahSamplingsTmp),
                        count($spesifikasisTmp),
                        count($penampakansTmp),
                        count($sealingsTmp),
                        count($cetakansTmp),
                        count($ketebalanMicronsTmp),
                        count($dimensisTmp),
                        count($statusesTmp),
                        count($logoHalalsTmp),
                        count($dokumenHalalsTmp),
                        count($coasTmp),
                        count($keterangansTmp),
                        count($imageKemasansTmp)
                    );

                    for ($i = 0; $i < $rowCount; $i++) {
                        $pdfColumns->push([
                            'record' => $p,
                            'rowIndex' => $i,
                        ]);
                    }
                }

                $chunks = $pdfColumns->groupBy(fn($col) => $col['record']->id)->flatMap(fn($cols) => $cols->chunk($columnsPerPage));

                $bahanMap = [];
                if (!empty($allBahanIds)) {
                    $bahanMap = \App\Models\Produk::whereIn('id', array_values(array_unique($allBahanIds)))
                        ->pluck('nama_produk', 'id')
                        ->toArray();
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
                    // Reset numbering per-record (per kedatangan)
                    $thisRecId = $firstRecord ? $groupKeyFn($firstRecord) : null;
                    if (!isset($prevRecId_kem) || $prevRecId_kem !== $thisRecId) {
                        $prevRecId_kem = $thisRecId;
                        $recPageIdx_kem = 0;
                    } else {
                        $recPageIdx_kem++;
                    }
                @endphp

                @if(!$isFirstPage)
                    <div style="page-break-before: always;"></div>
                @endif
                @php $isFirstPage = false; @endphp

                {{-- HEADER - DICETAK DI SETIAP HALAMAN --}}
                <table class="header-table">
                    <tr>
                        <td class="header-logo-td">
                            <img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo CPI">
                        </td>
                        <td class="header-company-td">
                            <h2>PT. CHAROEN POKPHAND INDONESIA</h2>
                            @php
                                $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
                            @endphp
                            <p>FOOD DIVISION {{ strtoupper($plantName) }}</p>
                            <p>{{ strtoupper($plantName) }} - INDONESIA</p>
                        </td>
                        <td class="header-title-td">
                            <div class="header-title-box">
                                PEMERIKSAAN KEDATANGAN KEMASAN
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

                {{-- DATA UTAMA (4 Kolom dengan Table HTML Murni) --}}
                <table class="grid-table">
                    <tr>
                        @foreach($pageRecords as $index => $column)
                            @php
                                $pemeriksaan = $column['record'];
                                $rowIndex = $column['rowIndex'];
                                $columnNumber = ($recPageIdx_kem * $columnsPerPage) + $loop->iteration;
                            @endphp
                            <td class="data-column">
                                <div class="column-header">
                                    PEMERIKSAAN #{{ $columnNumber }}
                                </div>

                                {{-- KONDISI MOBIL PENGANGKUT --}}
                                @php
                                    $kondisiMobilRaw = $pemeriksaan->kondisi_mobil ?? [];
                                    if (is_string($kondisiMobilRaw)) {
                                        $decoded = json_decode($kondisiMobilRaw, true);
                                        $kondisiMobilArr = is_array($decoded) ? $decoded : [];
                                    } elseif (is_array($kondisiMobilRaw)) {
                                        $kondisiMobilArr = $kondisiMobilRaw;
                                    } else {
                                        $kondisiMobilArr = [];
                                    }

                                    $kondisiMobilLabels = [
                                        'bersih'              => 'Bersih',
                                        'bebas_hama'          => 'Bebas dari hama',
                                        'tidak_kondensasi'    => 'Tidak Kondensasi',
                                        'bebas_produk_halal'  => 'Bebas dari Produk Non Halal',
                                        'tidak_berbau'        => 'Tidak Berbau',
                                        'tidak_ada_sampah'    => 'Tidak ada sampah',
                                        'tidak_ada_mikroba'   => 'Tidak ada mikroba',
                                        'lampu_cover_utuh'    => 'Lampu Cover utuh',
                                        'pallet_utuh'         => 'Pallet utuh',
                                        'tertutup_rapat'      => 'Tertutup rapat',
                                        'bebas_kontaminan'    => 'Bebas kontaminan',
                                    ];
                                @endphp
                                @if(!empty($kondisiMobilArr))
                                    <div class="section-title">1. Kondisi Mobil Pengangkut</div>
                                    @foreach($kondisiMobilLabels as $key => $label)
                                        <div class="field-row">
                                            @if(!empty($kondisiMobilArr[$key]))
                                                <span class="check-item">V</span>
                                            @else
                                                <span style="color:#dc3545;font-weight:bold;">X</span>
                                            @endif
                                            <span class="field-value"> {{ $label }}</span>
                                        </div>
                                    @endforeach
                                @endif

                                {{-- BAHAN KEMASAN --}}
                                @php
                                    $id_bahans = json_decode($pemeriksaan->id_bahan_array ?? '[]', true) ?? [];
                                    $produsens_arr = json_decode($pemeriksaan->produsen_array ?? '[]', true) ?? [];
                                    $distributors_arr = json_decode($pemeriksaan->distributor_array ?? '[]', true) ?? [];
                                    $kode_produksis = json_decode($pemeriksaan->kode_produksi_array ?? '[]', true) ?? [];
                                    $jumlah_datangs = json_decode($pemeriksaan->jumlah_datang_array ?? '[]', true) ?? [];
                                    $unit_datangs = json_decode($pemeriksaan->unit_datang_array ?? '[]', true) ?? [];
                                    $jumlah_samplings = json_decode($pemeriksaan->jumlah_sampling_array ?? '[]', true) ?? [];
                                    $unit_samplings = json_decode($pemeriksaan->unit_sampling_array ?? '[]', true) ?? [];
                                    $spesifikasis = json_decode($pemeriksaan->spesifikasi_array ?? '[]', true) ?? [];

                                    $id_bahan = $id_bahans[$rowIndex] ?? null;
                                    $produsen_val = $produsens_arr[$rowIndex] ?? null;
                                    $distributor_val = $distributors_arr[$rowIndex] ?? null;
                                    $kode_produksi_val = $kode_produksis[$rowIndex] ?? null;
                                    $jumlah_datang_val = $jumlah_datangs[$rowIndex] ?? null;
                                    $unit_datang_val = $unit_datangs[$rowIndex] ?? null;
                                    $jumlah_sampling_val = $jumlah_samplings[$rowIndex] ?? null;
                                    $unit_sampling_val = $unit_samplings[$rowIndex] ?? null;
                                    $spesifikasi_val = $spesifikasis[$rowIndex] ?? null;

                                    if (is_array($produsen_val)) {
                                        $produsen_val = implode(', ', array_values(array_filter($produsen_val, fn ($v) => $v !== null && $v !== '')));
                                    }
                                    if (is_array($distributor_val)) {
                                        $distributor_val = implode(', ', array_values(array_filter($distributor_val, fn ($v) => $v !== null && $v !== '')));
                                    }
                                @endphp
                                @if($id_bahan || $produsen_val || $distributor_val || $kode_produksi_val || $jumlah_datang_val || $jumlah_sampling_val || $spesifikasi_val || $pemeriksaan->no_po)
                                    <div class="section-title">2. Bahan Kemasan</div>
                                    @if($pemeriksaan->no_po)
                                        <div class="field-row">
                                            <span class="field-label">No. PO:</span>
                                            <span class="field-value">{{ $pemeriksaan->no_po }}</span>
                                        </div>
                                    @endif
                                    @if($id_bahan)
                                        <div class="field-row">
                                            <span class="field-label">Bahan:</span>
                                            <span class="field-value">{{ $bahanMap[$id_bahan] ?? 'N/A' }}</span>
                                        </div>
                                    @endif
                                    @if($produsen_val)
                                        <div class="field-row">
                                            <span class="field-label">Produsen:</span>
                                            <span class="field-value">{{ $produsen_val }}</span>
                                        </div>
                                    @endif
                                    @if($distributor_val)
                                        <div class="field-row">
                                            <span class="field-label">Distributor:</span>
                                            <span class="field-value">{{ $distributor_val }}</span>
                                        </div>
                                    @endif
                                    @if($kode_produksi_val)
                                        <div class="field-row">
                                            <span class="field-label">Kode Prod:</span>
                                            <span class="field-value">{{ $kode_produksi_val }}</span>
                                        </div>
                                    @endif
                                    @if($jumlah_datang_val)
                                        <div class="field-row">
                                            <span class="field-label">Jml Datang:</span>
                                            <span class="field-value">{{ $jumlah_datang_val }} @if($unit_datang_val)<strong>{{ $unit_datang_val }}</strong>@endif</span>
                                        </div>
                                    @endif
                                    @if($jumlah_sampling_val)
                                        <div class="field-row">
                                            <span class="field-label">Jml Sampling:</span>
                                            <span class="field-value">{{ $jumlah_sampling_val }} @if($unit_sampling_val)<strong>{{ $unit_sampling_val }}</strong>@endif</span>
                                        </div>
                                    @endif
                                    @if($spesifikasi_val)
                                        <div class="field-row">
                                            <span class="field-label">Spesifikasi:</span>
                                            <span class="field-value">{{ substr($spesifikasi_val, 0, 25) }}{{ strlen($spesifikasi_val) > 25 ? '...' : '' }}</span>
                                        </div>
                                    @endif
                                @endif

                                {{-- KONDISI FISIK --}}
                                @php
                                    $penampakans = json_decode($pemeriksaan->penampakan_array ?? '[]', true) ?? [];
                                    $sealings = json_decode($pemeriksaan->sealing_array ?? '[]', true) ?? [];
                                    $cetakans = json_decode($pemeriksaan->cetakan_array ?? '[]', true) ?? [];

                                    $penampakan_val = $penampakans[$rowIndex] ?? null;
                                    $sealing_val = $sealings[$rowIndex] ?? null;
                                    $cetakan_val = $cetakans[$rowIndex] ?? null;
                                @endphp
                                @if($penampakan_val !== null || $sealing_val !== null || $cetakan_val !== null)
                                    <div class="section-title">3. Kondisi Fisik</div>
                                    @if($penampakan_val !== null)
                                        <div class="field-row">
                                            <span class="field-label">Penampakan:</span>
                                            <span class="field-value">{{ $penampakan_val ? 'V' : 'X' }}</span>
                                        </div>
                                    @endif
                                    @if($sealing_val !== null)
                                        <div class="field-row">
                                            <span class="field-label">Sealing:</span>
                                            <span class="field-value">{{ $sealing_val ? 'V' : 'X' }}</span>
                                        </div>
                                    @endif
                                    @if($cetakan_val !== null)
                                        <div class="field-row">
                                            <span class="field-label">Cetakan:</span>
                                            <span class="field-value">{{ $cetakan_val ? 'V' : 'X' }}</span>
                                        </div>
                                    @endif
                                @endif

                                {{-- DETAIL TAMBAHAN --}}
                                @php
                                    $ketebalan_microns = json_decode($pemeriksaan->ketebalan_micron_array ?? '[]', true) ?? [];
                                    $dimensis = json_decode($pemeriksaan->dimensi_array ?? '[]', true) ?? [];
                                    $statuses = json_decode($pemeriksaan->status_array ?? '[]', true) ?? [];

                                    $ketebalan_micron_val = $ketebalan_microns[$rowIndex] ?? null;
                                    $dimensi_val = $dimensis[$rowIndex] ?? null;
                                    $status_val = $statuses[$rowIndex] ?? null;
                                @endphp
                                @if($ketebalan_micron_val !== null || $dimensi_val || $status_val)
                                    <div class="section-title">4. Detail Tambahan</div>
                                    @if($ketebalan_micron_val !== null && $ketebalan_micron_val !== '')
                                        <div class="field-row">
                                            <span class="field-label">Ketebalan:</span>
                                            <span class="field-value">{{ $ketebalan_micron_val }} µm</span>
                                        </div>
                                    @endif
                                    @if($dimensi_val)
                                        <div class="field-row">
                                            <span class="field-label">Dimensi:</span>
                                            <span class="field-value">{{ $dimensi_val }}</span>
                                        </div>
                                    @endif
                                    @if($status_val)
                                        <div class="field-row">
                                            <span class="field-label">Status:</span>
                                            <span class="field-value">{{ $status_val }}</span>
                                        </div>
                                    @endif
                                @endif

                                {{-- DOKUMEN --}}
                                @php
                                    $logo_halals = json_decode($pemeriksaan->logo_halal_array ?? '[]', true) ?? [];
                                    $dokumen_halals = json_decode($pemeriksaan->dokumen_halal_array ?? '[]', true) ?? [];
                                    $coas = json_decode($pemeriksaan->coa_array ?? '[]', true) ?? [];
                                    $keterangans = json_decode($pemeriksaan->keterangan_array ?? '[]', true) ?? [];
                                    $image_kemasans = json_decode($pemeriksaan->image_kemasan_array ?? '[]', true) ?? [];

                                    $logo_halal_val = $logo_halals[$rowIndex] ?? null;
                                    $dokumen_halal_val = $dokumen_halals[$rowIndex] ?? null;
                                    $coa_val = $coas[$rowIndex] ?? null;
                                    $keterangan_val = $keterangans[$rowIndex] ?? null;
                                    $image_path_val = $image_kemasans[$rowIndex] ?? null;
                                @endphp
                                @if($logo_halal_val !== null || $dokumen_halal_val !== null || $coa_val !== null || $keterangan_val)
                                    <div class="section-title">5. Dokumen</div>
                                    <div class="field-row">
                                        <span class="field-label">Logo Halal:</span>
                                        <span class="field-value">{{ $logo_halal_val ? 'V' : 'X' }}</span>
                                    </div>
                                    <div class="field-row">
                                        <span class="field-label">Halal Berlaku:</span>
                                        <span class="field-value">{{ $dokumen_halal_val ? 'V' : 'X' }}</span>
                                    </div>
                                    <div class="field-row">
                                        <span class="field-label">COA:</span>
                                        <span class="field-value">{{ $coa_val ? 'V' : 'X' }}</span>
                                    </div>
                                    @php
                                        $fileCoas = json_decode($pemeriksaan->file_coa_array ?? '[]', true) ?? [];
                                        $fileCoaVal = $fileCoas[$rowIndex] ?? null;
                                    @endphp
                                    <div class="field-row">
                                        <span class="field-label">File COA:</span>
                                        <span class="field-value">{{ $fileCoaVal ? 'Ada' : 'Tidak Ada' }}</span>
                                    </div>
                                    @if($keterangan_val)
                                        <div class="field-row">
                                            <span class="field-label">Keterangan:</span>
                                            <span class="field-value">{{ substr($keterangan_val, 0, 20) }}{{ strlen($keterangan_val) > 20 ? '...' : '' }}</span>
                                        </div>
                                    @endif
                                @endif

                                {{-- GAMBAR KEMASAN --}}
                                @php
                                    $imgFullPath = null;
                                    if ($image_path_val) {
                                        $imgFullPath = public_path('storage/' . $image_path_val);
                                    }
                                @endphp
                                @if($imgFullPath && file_exists($imgFullPath))
                                    <div class="section-title">Gambar Kemasan</div>
                                    <div style="text-align: center; margin-top: 4px;">
                                        <img src="{{ $imgFullPath }}" alt="Gambar Kemasan" style="max-width: 100px; max-height: 80px;">
                                    </div>
                                @endif
                            </td>
                        @endforeach
                        
                        {{-- Isi kolom kosong jika data kurang dari 4 --}}
                        @for($i = $pageRecords->count(); $i < $columnsPerPage; $i++)
                            <td class="data-column" style="background: #f8f9fa;"></td>
                        @endfor
                    </tr>
                </table>

                <div style="text-align: right; font-style: italic; font-size: 8px; color: #666; margin-bottom: 5px;">
                    QW 02/00
                </div>

                {{-- SIGNATURE SECTION --}}
                <div class="signature-section">
                    <div class="signature-note">
                        <span class="ok-text">V OK</span> (Segel/Gembok: Tersedia, Penampakan, Sealing, Cetakan: Sesuai Standar, Logo Halal, Halal Berlaku, COA: Tersedia)<br>
                        <span class="not-ok-text">X</span> : Parameter Tidak Sesuai
                    </div>
                    
                    @php
                        $firstRecord = $pemeriksaans->first();
                    @endphp
                    <table class="signature-table">
                        <tr>
                            <!-- 1. Dibuat Oleh (QC VERIFIER) -->
                            <td class="signature-cell">
                                <div class="signature-header-item">Dibuat Oleh:</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->qcVerifier)
                                        @php
                                            $qcQrData = "Dokumen #{$firstRecord->id} telah diverifikasi secara sistem oleh {$firstRecord->qcVerifier->name} (Tim QC)";
                                            $qcQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(45)->generate($qcQrData);
                                            $base64QcSvg = "data:image/svg+xml;base64," . base64_encode($qcQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64QcSvg }}" class="qr-code-img" alt="QR Code QC">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $firstRecord && $firstRecord->qcVerifier ? $firstRecord->qcVerifier->name : '-' }}</div>
                            </td>

                            <!-- 2. Diketahui Oleh (PRODUKSI/WAREHOUSE VERIFIER) -->
                            <td class="signature-cell">
                                <div class="signature-header-item">Diketahui Oleh:</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->produksiVerifier)
                                        @php
                                            $prodQrData = "Dokumen #{$firstRecord->id} telah diverifikasi secara sistem oleh {$firstRecord->produksiVerifier->name} (Tim Warehouse)";
                                            $prodQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(45)->generate($prodQrData);
                                            $base64ProdSvg = "data:image/svg+xml;base64," . base64_encode($prodQrCodeSvg);
                                        @endphp
                                        <img src="{{ $base64ProdSvg }}" class="qr-code-img" alt="QR Code Warehouse">
                                    @else
                                        <div class="signature-line-empty"></div>
                                    @endif
                                </div>
                                <div class="signature-name">{{ $firstRecord && $firstRecord->produksiVerifier ? $firstRecord->produksiVerifier->name : '-' }}</div>
                            </td>

                            <!-- 3. Disetujui Oleh (SPV VERIFIER) -->
                            <td class="signature-cell">
                                <div class="signature-header-item">Disetujui Oleh:</div>
                                <div class="signature-space">
                                    @if($firstRecord && $firstRecord->spvVerifier)
                                        @php
                                            $spvQrData = "Dokumen #{$firstRecord->id} telah diverifikasi secara sistem oleh {$firstRecord->spvVerifier->name} (Tim Supervisor QC)";
                                            $spvQrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(45)->generate($spvQrData);
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

            @endforeach
        @else
            <div class="empty-message">
                <p>Tidak ada data pemeriksaan yang sesuai dengan filter yang dipilih.</p>
            </div>
        @endif
            @endforeach
        @endif
    </div>
</body>
</html>