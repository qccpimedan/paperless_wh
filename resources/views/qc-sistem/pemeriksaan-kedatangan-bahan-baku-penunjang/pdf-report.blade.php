<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pemeriksaan Kedatangan Bahan Baku Penunjang</title>
    @php
        $firstRecord = $pemeriksaans->first();
        $plantName = $firstRecord && $firstRecord->user && $firstRecord->user->plant ? $firstRecord->user->plant->plant : 'MEDAN';
    @endphp
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.3;
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
            margin-bottom: 12px;
            border-bottom: 3px solid #c41e3a;
            padding-bottom: 10px;
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
            width: 50px;
            vertical-align: middle;
        }
        
        .header-logo img {
            width: 45px;
            height: 45px;
            object-fit: contain;
        }
        
        .header-company {
            display: table-cell;
            vertical-align: middle;
            padding-left: 10px;
        }
        
        .header-company h2 {
            font-size: 11px;
            font-weight: bold;
            color: #c41e3a;
            margin-bottom: 1px;
            letter-spacing: 0.5px;
        }
        
        .header-company p {
            font-size: 7.5px;
            color: #444;
            margin-bottom: 0;
        }
        
        .header-title h1 {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a1a;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 6px 12px;
            border-radius: 4px;
            border-left: 4px solid #c41e3a;
            display: inline-block;
        }

        .subheader {
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-bottom: 10px;
            background: #f8f9fa;
            page-break-inside: avoid;
            padding: 0;
        }
        
        .subheader-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .subheader-table td {
            padding: 5px 8px;
            font-size: 7.5px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }
        
        .subheader-table tr:last-child td {
            border-bottom: none;
        }
        
        .subheader-label {
            font-weight: 600;
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

        .page-section {
            page-break-inside: avoid;
            margin-bottom: 12px;
        }
        
        .columns-wrap {
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow: hidden;
            display: table;
            table-layout: fixed;
        }
        
        .columns-wrap .col {
            width: 25%;
            display: table-cell;
            border-right: 1px solid #dee2e6;
            padding: 7px;
            font-size: 7px;
            background: #fff;
            vertical-align: top;
            page-break-inside: avoid;
        }
        
        .columns-wrap .col:last-child {
            border-right: none;
        }
        
        .columns-wrap .col.empty-col {
            background: #f8f9fa;
        }
        
        .col-header {
            font-weight: bold;
            font-size: 7.5px;
            color: #8b1428;
            background: #fff;
            padding: 5px 7px;
            margin: -7px -7px 6px -7px;
            text-align: center;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .sec-title {
            font-weight: bold;
            font-size: 7px;
            color: #c41e3a;
            border-bottom: 1.5px solid #c41e3a;
            margin-top: 5px;
            margin-bottom: 3px;
            padding-bottom: 1px;
            text-transform: uppercase;
        }
        
        .col[data-num="true"] .sec-title {
            counter-increment: sec-c;
        }
        
        .col[data-num="true"] .sec-title::before {
            content: counter(sec-c) ". ";
        }
        
        .col[data-num="true"] {
            counter-reset: sec-c;
        }
        
        .f-row {
            margin-bottom: 2px;
            display: table;
            width: 100%;
        }
        
        .f-label {
            display: table-cell;
            font-weight: 600;
            color: #495057;
            width: 48px;
            padding-right: 3px;
            white-space: nowrap;
        }
        
        .f-value {
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

        .signature-section {
            margin-top: 12px;
            padding: 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            background: #f8f9fa;
            page-break-inside: avoid;
        }
        
        .sig-note {
            font-size: 6.5px;
            color: #495057;
            padding: 5px 8px;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 3px;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .sig-note .ok {
            color: #28a745;
            font-weight: 600;
        }
        
        .sig-note .not-ok {
            color: #dc3545;
            font-weight: 600;
        }
        
        .sig-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .sig-cell {
            width: 33.33%;
            text-align: center;
            padding: 0 10px;
            vertical-align: top;
        }
        
        .sig-label {
            font-size: 7.5px;
            font-weight: 600;
            color: #495057;
            padding-bottom: 20px;
        }
        
        .sig-space {
            height: 50px;
            margin: 0 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sig-line {
            border-bottom: 2px solid #1a1a1a;
            height: 35px;
            width: 100%;
        }
        
        .qr-img {
            max-height: 50px;
            max-width: 50px;
        }
        
        .sig-name {
            font-size: 7.5px;
            font-weight: bold;
            color: #1a1a1a;
            padding-top: 5px;
            text-transform: uppercase;
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
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">

        @php $isAllShift = $isAllShift ?? false; @endphp

        @if($isAllShift)
            {{-- ======= MODE: SEMUA SHIFT ======= --}}
            @if(empty($dataPerShift))
                <div class="empty-message">
                    <p>Tidak ada data pemeriksaan untuk semua shift pada periode yang dipilih.</p>
                </div>
            @else
                @foreach($dataPerShift as $shiftGroupIndex => $shiftGroup)
                    @php
                        $pemeriksaans     = $shiftGroup['pemeriksaans'];
                        $currentShift     = $shiftGroup['shift'];
                        $qcUser           = $shiftGroup['qcUser'];
                        $produksiUser     = $shiftGroup['produksiUser'];
                        $spvQcUser        = $shiftGroup['spvQcUser'];
                        $filterBahanIds   = $shiftGroup['filterBahanIds'];
                        $columnsPerPage   = 4;
                        $allBahanIds      = [];
                        $pdfColumns       = collect();

                        foreach ($pemeriksaans as $p) {
                            $idBahansTmp = json_decode($p->id_bahan_array ?? '[]', true) ?? [];
                            foreach ($idBahansTmp as $tmpId) { if ($tmpId) $allBahanIds[] = $tmpId; }

                            $rowCount = max(1,
                                count($idBahansTmp),
                                count(json_decode($p->produsen_array ?? '[]', true) ?? []),
                                count(json_decode($p->kode_produksi_array ?? '[]', true) ?? []),
                                count(json_decode($p->expire_date_array ?? '[]', true) ?? []),
                                count(json_decode($p->jumlah_datang_array ?? '[]', true) ?? []),
                                count(json_decode($p->spesifikasi_array ?? '[]', true) ?? []),
                                count(json_decode($p->kondisi_produk ?? '[]', true) ?? []),
                                count(json_decode($p->suhu_produk ?? '[]', true) ?? []),
                                count(json_decode($p->status_baris_array ?? '[]', true) ?? [])
                            );
                            for ($i = 0; $i < $rowCount; $i++) {
                                $id_bahan = (json_decode($p->id_bahan_array ?? '[]', true) ?? [])[$i] ?? null;
                                if (isset($filterBahanIds) && is_array($filterBahanIds) && !empty($filterBahanIds) && !in_array($id_bahan, $filterBahanIds)) continue;
                                $pdfColumns->push(['record' => $p, 'rowIndex' => $i]);
                            }
                        }
                        $bahanMap = [];
                        if (!empty($allBahanIds)) {
                            $bahanMap = \App\Models\Bahan::whereIn('id', array_values(array_unique($allBahanIds)))->pluck('nama_bahan', 'id')->toArray();
                        }
                        $chunks = $pdfColumns->chunk($columnsPerPage);
                    @endphp

                    @foreach($chunks as $pageIndex => $pageRecords)
                        @php
                            $firstColumn = $pageRecords->first();
                            $fRec = $firstColumn ? $firstColumn['record'] : null;
                        @endphp
                        <div class="page-section">
                        <div class="header">
                            <div class="header-left">
                                <div class="logo-company">
                                    <div class="header-logo">
                                        <img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo CPI">
                                    </div>
                                    <div class="header-company">
                                        <h2>PT. CHAROEN POKPHAND INDONESIA</h2>
                                        @php $plantName = $fRec && $fRec->user && $fRec->user->plant ? $fRec->user->plant->plant : 'MEDAN'; @endphp
                                        <p>FOOD DIVISION {{ strtoupper($plantName) }}</p>
                                        <p>{{ strtoupper($plantName) }} - INDONESIA</p>
                                    </div>
                                </div>
                            </div>
                            <div class="header-right">
                                <div class="header-title">
                                    <h1>PEMERIKSAAN KEDATANGAN BAHAN BAKU DAN BAHAN PENUNJANG</h1>
                                    <div style="font-size:8px;color:#666;margin-top:3px;">{{ strtoupper($currentShift->shift) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="subheader">
                            <table class="subheader-table">
                                <tr>
                                    <td><span class="subheader-label">Hari/Tgl:</span> <span class="subheader-value">{{ $fRec && $fRec->tanggal ? (is_string($fRec->tanggal) ? $fRec->tanggal : $fRec->tanggal->format('d/m/Y')) : '-' }}</span></td>
                                    <td class="subheader-divider"></td>
                                    <td><span class="subheader-label">Shift:</span> <span class="subheader-value">{{ $currentShift->shift }}</span></td>
                                    <td class="subheader-divider"></td>
                                    <td><span class="subheader-label">Jenis Mobil:</span> <span class="subheader-value">{{ $fRec->jenis_mobil ?? '-' }}</span></td>
                                    <td class="subheader-divider"></td>
                                    <td><span class="subheader-label">No. Mobil:</span> <span class="subheader-value">{{ $fRec->no_mobil ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <td><span class="subheader-label">Segel:</span> <span class="subheader-value">{{ $fRec && $fRec->segel_gembok ? ucfirst($fRec->segel_gembok) : '-' }}</span></td>
                                    <td class="subheader-divider"></td>
                                    <td><span class="subheader-label">No. Segel:</span> <span class="subheader-value">{{ $fRec->no_segel ?? '-' }}</span></td>
                                    <td class="subheader-divider"></td>
                                    <td colspan="3"><span class="subheader-label">Supir:</span> <span class="subheader-value">{{ $fRec->nama_supir ?? '-' }}</span></td>
                                </tr>
                            </table>
                        </div>

                        <div class="columns-wrap clearfix">
                            @foreach($pageRecords as $index => $column)
                                @php
                                    $p  = $column['record'];
                                    $ri = $column['rowIndex'];
                                    $cn = ($pageIndex * $columnsPerPage) + $loop->iteration;
                                @endphp
                                <div class="col" data-num="true">
                                    <div class="col-header">PEMERIKSAAN #{{ $cn }}</div>
                                    @php $km = $p->kondisi_mobil ?? []; $ci = array_filter($km); @endphp
                                    @if(count($ci) > 0)
                                        <div class="sec-title">Kondisi Mobil</div>
                                        @foreach($ci as $key => $v) @if($v)<div class="f-row"><span class="check-item">{{ ucfirst(str_replace('_', ' ', $key)) }}</span></div>@endif @endforeach
                                    @endif
                                    @php
                                        $bahan_id  = (json_decode($p->id_bahan_array ?? '[]', true) ?? [])[$ri] ?? null;
                                        $negara_val = (json_decode($p->negara_produsen_array ?? '[]', true) ?? [])[$ri] ?? null;
                                        if (is_array($negara_val)) { $negara_val = implode(', ', array_filter($negara_val)); }
                                    @endphp
                                    @if($bahan_id || $negara_val)
                                        <div class="sec-title">Bahan Baku Penunjang</div>
                                        @if($bahan_id)<div class="f-row"><span class="f-label">Nama:</span><span class="f-value">{{ $bahanMap[$bahan_id] ?? 'N/A' }}</span></div>@endif
                                        @if($negara_val)<div class="f-row"><span class="f-label">Negara:</span><span class="f-value">{{ $negara_val }}</span></div>@endif
                                    @endif
                                    @php
                                        $sp_v  = (json_decode($p->suhu_produk ?? '[]', true) ?? [])[$ri] ?? null;
                                        $spt_v = (json_decode($p->suhu_produk_type ?? '[]', true) ?? [])[$ri] ?? null;
                                        $sm_v  = (json_decode($p->suhu_mobil_array ?? '[]', true) ?? [])[$ri] ?? null;
                                        $smt_v = (json_decode($p->suhu_mobil_type_array ?? '[]', true) ?? [])[$ri] ?? null;
                                        $kp_v  = (json_decode($p->kondisi_produk_suhu ?? '[]', true) ?? [])[$ri] ?? null;
                                    @endphp
                                    @if($spt_v || $sp_v !== null || $smt_v || $sm_v !== null || $kp_v)
                                        <div class="sec-title">Kondisi Suhu</div>
                                        @if($spt_v)<div class="f-row"><span class="f-label" style="width:65px;">Suhu Produk:</span><span class="f-value">{{ $spt_v }}</span></div>@endif
                                        @if($sp_v !== null && $sp_v !== '')<div class="f-row"><span class="f-label" style="width:65px;">Nilai:</span><span class="f-value">{{ $sp_v }}°C</span></div>@endif
                                        @if($smt_v)<div class="f-row"><span class="f-label" style="width:65px;">Suhu Mobil:</span><span class="f-value">{{ $smt_v }}</span></div>@endif
                                        @if($sm_v !== null && $sm_v !== '')<div class="f-row"><span class="f-label" style="width:65px;">Nilai Mobil:</span><span class="f-value">{{ $sm_v }}°C</span></div>@endif
                                        @if($kp_v !== null && $kp_v !== '')<div class="f-row"><span class="f-label" style="width:65px;">Suhu Kondisi:</span><span class="f-value">{{ $kp_v }}°C</span></div>@endif
                                    @endif
                                    @php
                                        $ffa_v = (json_decode($p->hasil_uji_ffa_array ?? '[]', true) ?? [])[$ri] ?? null;
                                        $ket_v = (json_decode($p->keterangan_array ?? '[]', true) ?? [])[$ri] ?? null;
                                    @endphp
                                    @if($ffa_v || $ket_v)
                                        <div class="sec-title">Analisis</div>
                                        @if($ffa_v)<div class="f-row"><span class="f-label">FFA:</span><span class="f-value">{{ $ffa_v }}</span></div>@endif
                                        @if($ket_v)<div class="f-row"><span class="f-label">Ket:</span><span class="f-value">{{ substr($ket_v,0,20) }}{{ strlen($ket_v)>20?'..':'' }}</span></div>@endif
                                    @endif
                                    @php $kf_v = (json_decode($p->kondisi_fisik_array ?? '[]', true) ?? [])[$ri] ?? []; @endphp
                                    @if(!empty($kf_v))
                                        <div class="sec-title">Kondisi Fisik</div>
                                        @if(isset($kf_v['kemasan']))<div class="f-row"><span class="f-label">Kemasan:</span><span class="f-value">{{ $kf_v['kemasan'] ? 'V' : 'X' }}</span></div>@endif
                                        @if(isset($kf_v['warna']))<div class="f-row"><span class="f-label">Warna:</span><span class="f-value">{{ $kf_v['warna'] ? 'V' : 'X' }}</span></div>@endif
                                        @if(isset($kf_v['benda_asing']))<div class="f-row"><span class="f-label">B.Asing:</span><span class="f-value">{{ $kf_v['benda_asing'] ? 'V' : 'X' }}</span></div>@endif
                                        @if(isset($kf_v['aroma']))<div class="f-row"><span class="f-label">Aroma:</span><span class="f-value">{{ $kf_v['aroma'] ? 'V' : 'X' }}</span></div>@endif
                                    @endif
                                    @php
                                        $lh_v = (json_decode($p->logo_halal_array ?? '[]', true) ?? [])[$ri] ?? null;
                                        $dh_v = (json_decode($p->dokumen_halal_array ?? '[]', true) ?? [])[$ri] ?? null;
                                        $ca_v = (json_decode($p->coa_array ?? '[]', true) ?? [])[$ri] ?? null;
                                        $fc_v = (json_decode($p->file_coa_array ?? '[]', true) ?? [])[$ri] ?? null;
                                    @endphp
                                    @if($lh_v !== null || $dh_v !== null || $ca_v !== null || $fc_v)
                                        <div class="sec-title">Dokumentasi</div>
                                        @if($lh_v !== null)<div class="f-row"><span class="f-label" style="width:55px;">Logo Halal:</span><span class="f-value">{{ $lh_v ? 'Ya' : 'Tidak' }}</span></div>@endif
                                        @if($dh_v !== null)<div class="f-row"><span class="f-label" style="width:55px;">Dok. Halal:</span><span class="f-value">{{ $dh_v ? 'Ya' : 'Tidak' }}</span></div>@endif
                                        @if($ca_v !== null)<div class="f-row"><span class="f-label" style="width:55px;">COA:</span><span class="f-value">{{ $ca_v ? 'Ya' : 'Tidak' }}</span></div>@endif
                                    @endif
                                    @php $sb_v = (json_decode($p->status_baris_array ?? '[]', true) ?? [])[$ri] ?? null; @endphp
                                    @if($sb_v)
                                        <div class="sec-title">Status Release</div>
                                        <div class="f-row"><span class="f-label" style="width:55px;">Status:</span><span class="f-value">
                                            @if($sb_v === 'Release')<span style="color:#2f855a;font-weight:bold;background:#c6f6d5;padding:1px 4px;border-radius:2px;">RELEASE</span>
                                            @elseif($sb_v === 'Hold')<span style="color:#9c4221;font-weight:bold;background:#feebc8;padding:1px 4px;border-radius:2px;">HOLD</span>
                                            @else<span style="color:#4a5568;font-weight:bold;background:#edf2f7;padding:1px 4px;border-radius:2px;">{{ strtoupper($sb_v) }}</span>@endif
                                        </span></div>
                                    @endif
                                    @php $ip_v = (json_decode($p->image_bahan_baku_array ?? '[]', true) ?? [])[$ri] ?? null; $ifp = $ip_v ? public_path('storage/' . $ip_v) : null; @endphp
                                    @if($ifp && file_exists($ifp))
                                        <div class="sec-title">Foto Bahan Baku</div>
                                        <div style="margin-top:4px;text-align:center;"><img src="{{ $ifp }}" alt="Foto" style="max-width:120px;max-height:80px;border:1px solid #dee2e6;border-radius:3px;padding:1px;"></div>
                                    @endif
                                </div>
                            @endforeach
                            @for($i = $pageRecords->count(); $i < $columnsPerPage; $i++)
                                <div class="col empty-col"></div>
                            @endfor
                        </div>
                        <div style="text-align:right;padding-right:10px;font-style:italic;font-size:8px;color:#666;margin-top:4px;">QW 01/00</div>
                        <div class="signature-section">
                            <div class="sig-note">
                                <span class="ok">V</span> : OK &nbsp; <span class="not-ok">X</span> : Tidak Sesuai
                            </div>
                            @php $fRecSig = $pemeriksaans->first(); @endphp
                            <table class="sig-table"><tr>
                                <td class="sig-cell">
                                    <div class="sig-label">Dibuat Oleh:</div>
                                    <div class="sig-space">
                                        @if($fRecSig && $fRecSig->qcVerifier)
                                            @php $qd="Dokumen #{$fRecSig->id} diverifikasi oleh {$fRecSig->qcVerifier->name} (QC)"; $qq=\SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate($qd); $qb="data:image/svg+xml;base64,".base64_encode($qq); @endphp
                                            <img src="{{ $qb }}" class="qr-img" alt="QR">
                                        @else <div class="sig-line"></div> @endif
                                    </div>
                                    <div class="sig-name">{{ $fRecSig && $fRecSig->qcVerifier ? $fRecSig->qcVerifier->name : '-' }}</div>
                                </td>
                                <td class="sig-cell">
                                    <div class="sig-label">Diketahui Oleh:</div>
                                    <div class="sig-space">
                                        @if($fRecSig && $fRecSig->produksiVerifier)
                                            @php $pd="Dokumen #{$fRecSig->id} diverifikasi oleh {$fRecSig->produksiVerifier->name} (Warehouse)"; $pq=\SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate($pd); $pb="data:image/svg+xml;base64,".base64_encode($pq); @endphp
                                            <img src="{{ $pb }}" class="qr-img" alt="QR">
                                        @else <div class="sig-line"></div> @endif
                                    </div>
                                    <div class="sig-name">{{ $fRecSig && $fRecSig->produksiVerifier ? $fRecSig->produksiVerifier->name : '-' }}</div>
                                </td>
                                <td class="sig-cell">
                                    <div class="sig-label">Disetujui Oleh:</div>
                                    <div class="sig-space">
                                        @if($fRecSig && $fRecSig->spvVerifier)
                                            @php $sd="Dokumen #{$fRecSig->id} diverifikasi oleh {$fRecSig->spvVerifier->name} (SPV QC)"; $sq=\SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate($sd); $sb="data:image/svg+xml;base64,".base64_encode($sq); @endphp
                                            <img src="{{ $sb }}" class="qr-img" alt="QR">
                                        @else <div class="sig-line"></div> @endif
                                    </div>
                                    <div class="sig-name">{{ $fRecSig && $fRecSig->spvVerifier ? $fRecSig->spvVerifier->name : '-' }}</div>
                                </td>
                            </tr></table>
                        </div>
                        @if(!$loop->last)<div style="page-break-after: always;"></div>@endif
                        </div>
                    @endforeach

                    {{-- Page break antar shift, kecuali shift terakhir --}}
                    @if(!$loop->last)
                        <div style="page-break-after: always;"></div>
                    @endif

                @endforeach
            @endif

        @else
            {{-- ======= MODE: SHIFT TUNGGAL (existing logic) ======= --}}
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
                    $negaraProdusensTmp = json_decode($p->negara_produsen_array ?? '[]', true) ?? [];
                    $distributorsTmp = json_decode($p->distributor_array ?? '[]', true) ?? [];
                    $kodeProduksisTmp = json_decode($p->kode_produksi_array ?? '[]', true) ?? [];
                    $expireDatesTmp = json_decode($p->expire_date_array ?? '[]', true) ?? [];
                    $jumlahDatangsTmp = json_decode($p->jumlah_datang_array ?? '[]', true) ?? [];
                    $jumlahSamplingsTmp = json_decode($p->jumlah_sampling_array ?? '[]', true) ?? [];
                    $spesifikasisTmp = json_decode($p->spesifikasi_array ?? '[]', true) ?? [];
                    $kondisiProduksTmp = json_decode($p->kondisi_produk ?? '[]', true) ?? [];
                    $suhuProduksTmp = json_decode($p->suhu_produk ?? '[]', true) ?? [];
                    $suhuProdukTypesTmp = json_decode($p->suhu_produk_type ?? '[]', true) ?? [];
                    $suhuMobilsTmp = json_decode($p->suhu_mobil_array ?? '[]', true) ?? [];
                    $suhuMobilTypesTmp = json_decode($p->suhu_mobil_type_array ?? '[]', true) ?? [];
                    $kondisiProdukSuhusTmp = json_decode($p->kondisi_produk_suhu ?? '[]', true) ?? [];
                    $hasilUjiFfasTmp = json_decode($p->hasil_uji_ffa_array ?? '[]', true) ?? [];
                    $keterangansTmp = json_decode($p->keterangan_array ?? '[]', true) ?? [];
                    $logoHalalsTmp = json_decode($p->logo_halal_array ?? '[]', true) ?? [];
                    $dokumenHalalsTmp = json_decode($p->dokumen_halal_array ?? '[]', true) ?? [];
                    $coasTmp = json_decode($p->coa_array ?? '[]', true) ?? [];
                    $fileCoasTmp = json_decode($p->file_coa_array ?? '[]', true) ?? [];
                    $imageBahanBakusTmp = json_decode($p->image_bahan_baku_array ?? '[]', true) ?? [];
                    $statusBarisesTmp = json_decode($p->status_baris_array ?? '[]', true) ?? [];
                    $kondisiFisiksTmp = json_decode($p->kondisi_fisik_array ?? '[]', true) ?? [];

                    $rowCount = max(
                        1,
                        count($idBahansTmp), count($produsensTmp), count($negaraProdusensTmp),
                        count($distributorsTmp), count($kodeProduksisTmp), count($expireDatesTmp),
                        count($jumlahDatangsTmp), count($jumlahSamplingsTmp), count($spesifikasisTmp),
                        count($kondisiProduksTmp), count($suhuProduksTmp), count($suhuProdukTypesTmp),
                        count($suhuMobilsTmp), count($suhuMobilTypesTmp), count($kondisiProdukSuhusTmp),
                        count($hasilUjiFfasTmp), count($keterangansTmp), count($logoHalalsTmp),
                        count($dokumenHalalsTmp), count($coasTmp), count($fileCoasTmp),
                        count($imageBahanBakusTmp), count($statusBarisesTmp), count($kondisiFisiksTmp)
                    );

                    for ($i = 0; $i < $rowCount; $i++) {
                        $id_bahan = $idBahansTmp[$i] ?? null;

                        if (isset($filterBahanIds) && is_array($filterBahanIds)) {
                            if (!empty($filterBahanIds) && !in_array($id_bahan, $filterBahanIds)) {
                                continue;
                            }
                        }

                        $pdfColumns->push([
                            'record' => $p,
                            'rowIndex' => $i,
                        ]);
                    }
                }

                $bahanMap = [];
                if (!empty($allBahanIds)) {
                    $bahanMap = \App\Models\Bahan::whereIn('id', array_values(array_unique($allBahanIds)))
                        ->pluck('nama_bahan', 'id')
                        ->toArray();
                }

                $chunks = $pdfColumns->chunk($columnsPerPage);
            @endphp
            
            @foreach($chunks as $pageIndex => $pageRecords)
                @php
                    $firstColumn = $pageRecords->first();
                    $fRec = $firstColumn ? $firstColumn['record'] : null;
                @endphp
                <div class="page-section">
                <div class="header">
                    <div class="header-left">
                        <div class="logo-company">
                            <div class="header-logo">
                                <img src="{{ public_path('dist/images/logo/cpi-logo.png') }}" alt="Logo CPI">
                            </div>
                            <div class="header-company">
                                <h2>PT. CHAROEN POKPHAND INDONESIA</h2>
                                @php
                                    $plantName = $fRec && $fRec->user && $fRec->user->plant ? $fRec->user->plant->plant : 'MEDAN';
                                @endphp
                                <p>FOOD DIVISION {{ strtoupper($plantName) }}</p>
                                <p>{{ strtoupper($plantName) }} - INDONESIA</p>
                            </div>
                        </div>
                    </div>
                    <div class="header-right">
                        <div class="header-title">
                            <h1>PEMERIKSAAN BAHAN BAKU PENUNJANG</h1>
                        </div>
                    </div>
                </div>
                <div class="subheader">
                    <table class="subheader-table">
                        <tr>
                            <td><span class="subheader-label">Hari/Tgl:</span> <span class="subheader-value">{{ $fRec && $fRec->tanggal ? (is_string($fRec->tanggal) ? $fRec->tanggal : $fRec->tanggal->format('d/m/Y')) : '-' }}</span></td>
                            <td class="subheader-divider"></td>
                            <td><span class="subheader-label">Shift:</span> <span class="subheader-value">{{ $fRec->shift->shift ?? '-' }}</span></td>
                            <td class="subheader-divider"></td>
                            <td><span class="subheader-label">Jenis Mobil:</span> <span class="subheader-value">{{ $fRec->jenis_mobil ?? '-' }}</span></td>
                            <td class="subheader-divider"></td>
                            <td><span class="subheader-label">No. Mobil:</span> <span class="subheader-value">{{ $fRec->no_mobil ?? '-' }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="subheader-label">Segel:</span> <span class="subheader-value">{{ $fRec && $fRec->segel_gembok ? ucfirst($fRec->segel_gembok) : '-' }}</span></td>
                            <td class="subheader-divider"></td>
                            <td><span class="subheader-label">No. Segel:</span> <span class="subheader-value">{{ $fRec->no_segel ?? '-' }}</span></td>
                            <td class="subheader-divider"></td>
                            <td colspan="3"><span class="subheader-label">Supir:</span> <span class="subheader-value">{{ $fRec->nama_supir ?? '-' }}</span></td>
                        </tr>
                    </table>
                </div>

                <div class="columns-wrap clearfix">
                    @foreach($pageRecords as $index => $column)
                        @php
                            $p = $column['record'];
                            $ri = $column['rowIndex'];
                            $cn = ($pageIndex * $columnsPerPage) + $loop->iteration;
                        @endphp
                        <div class="col" data-num="true">
                            <div class="col-header">PEMERIKSAAN #{{ $cn }}</div>

                            @php
                                $km = $p->kondisi_mobil ?? [];
                                $ci = array_filter($km);
                            @endphp
                            @if(count($ci) > 0)
                                <div class="sec-title">Kondisi Mobil</div>
                                @foreach($ci as $key => $v)
                                    @if($v)
                                        <div class="f-row"><span class="check-item">{{ ucfirst(str_replace('_', ' ', $key)) }}</span></div>
                                    @endif
                                @endforeach
                            @endif

                            @php
                                $tempId = json_decode($p->id_bahan_array ?? '[]', true) ?? [];
                                $tempArr = json_decode($p->negara_produsen_array ?? '[]', true) ?? [];
                                $bahan_id = $tempId[$ri] ?? null;
                                $negara_val = $tempArr[$ri] ?? null;
                                if (is_array($negara_val)) { $negara_val = implode(', ', array_filter($negara_val)); }
                            @endphp
                            @if($bahan_id || $negara_val)
                                <div class="sec-title">Bahan Baku Penunjang</div>
                                @if($bahan_id)
                                    <div class="f-row"><span class="f-label">Nama:</span><span class="f-value">{{ $bahanMap[$bahan_id] ?? 'N/A' }}</span></div>
                                @endif
                                @if($negara_val)
                                    <div class="f-row"><span class="f-label">Negara:</span><span class="f-value">{{ $negara_val }}</span></div>
                                @endif
                            @endif

                            @php
                                $sps = json_decode($p->suhu_produk ?? '[]', true) ?? [];
                                $spts = json_decode($p->suhu_produk_type ?? '[]', true) ?? [];
                                $sms = json_decode($p->suhu_mobil_array ?? '[]', true) ?? [];
                                $smts = json_decode($p->suhu_mobil_type_array ?? '[]', true) ?? [];
                                $kps = json_decode($p->kondisi_produk_suhu ?? '[]', true) ?? [];
                                $sp_v = $sps[$ri] ?? null;
                                $spt_v = $spts[$ri] ?? null;
                                $sm_v = $sms[$ri] ?? null;
                                $smt_v = $smts[$ri] ?? null;
                                $kp_v = $kps[$ri] ?? null;
                            @endphp
                            @if($spt_v || $sp_v !== null || $smt_v || $sm_v !== null || $kp_v)
                                <div class="sec-title">Kondisi Suhu</div>
                                @if($spt_v)<div class="f-row"><span class="f-label" style="width:65px;">Suhu Produk:</span><span class="f-value">{{ $spt_v }}</span></div>@endif
                                @if($sp_v !== null && $sp_v !== '')<div class="f-row"><span class="f-label" style="width:65px;">Nilai:</span><span class="f-value">{{ $sp_v }}°C</span></div>@endif
                                @if($smt_v)<div class="f-row"><span class="f-label" style="width:65px;">Suhu Mobil:</span><span class="f-value">{{ $smt_v }}</span></div>@endif
                                @if($sm_v !== null && $sm_v !== '')<div class="f-row"><span class="f-label" style="width:65px;">Nilai Mobil:</span><span class="f-value">{{ $sm_v }}°C</span></div>@endif
                                @if($kp_v !== null && $kp_v !== '')<div class="f-row"><span class="f-label" style="width:65px;">Suhu Kondisi:</span><span class="f-value">{{ $kp_v }}°C</span></div>@endif
                            @endif

                            @php
                                $ffas = json_decode($p->hasil_uji_ffa_array ?? '[]', true) ?? [];
                                $kets = json_decode($p->keterangan_array ?? '[]', true) ?? [];
                                $ffa_v = $ffas[$ri] ?? null;
                                $ket_v = $kets[$ri] ?? null;
                            @endphp
                            @if($ffa_v || $ket_v)
                                <div class="sec-title">Analisis</div>
                                @if($ffa_v)<div class="f-row"><span class="f-label">FFA:</span><span class="f-value">{{ $ffa_v }}</span></div>@endif
                                @if($ket_v)<div class="f-row"><span class="f-label">Ket:</span><span class="f-value">{{ substr($ket_v, 0, 20) }}{{ strlen($ket_v) > 20 ? '..' : '' }}</span></div>@endif
                            @endif

                            @php
                                $kfs = json_decode($p->kondisi_fisik_array ?? '[]', true) ?? [];
                                $kf_v = $kfs[$ri] ?? [];
                            @endphp
                            @if(!empty($kf_v))
                                <div class="sec-title">Kondisi Fisik</div>
                                @if(isset($kf_v['kemasan']))<div class="f-row"><span class="f-label">Kemasan:</span><span class="f-value">{{ $kf_v['kemasan'] ? 'V' : 'X' }}</span></div>@endif
                                @if(isset($kf_v['warna']))<div class="f-row"><span class="f-label">Warna:</span><span class="f-value">{{ $kf_v['warna'] ? 'V' : 'X' }}</span></div>@endif
                                @if(isset($kf_v['benda_asing']))<div class="f-row"><span class="f-label">B.Asing:</span><span class="f-value">{{ $kf_v['benda_asing'] ? 'V' : 'X' }}</span></div>@endif
                                @if(isset($kf_v['aroma']))<div class="f-row"><span class="f-label">Aroma:</span><span class="f-value">{{ $kf_v['aroma'] ? 'V' : 'X' }}</span></div>@endif
                            @endif

                            @php
                                $lhs = json_decode($p->logo_halal_array ?? '[]', true) ?? [];
                                $dhs = json_decode($p->dokumen_halal_array ?? '[]', true) ?? [];
                                $cas = json_decode($p->coa_array ?? '[]', true) ?? [];
                                $fcs = json_decode($p->file_coa_array ?? '[]', true) ?? [];
                                $lh_v = $lhs[$ri] ?? null;
                                $dh_v = $dhs[$ri] ?? null;
                                $ca_v = $cas[$ri] ?? null;
                                $fc_v = $fcs[$ri] ?? null;
                            @endphp
                            @if($lh_v !== null || $dh_v !== null || $ca_v !== null || $fc_v)
                                <div class="sec-title">Dokumentasi</div>
                                @if($lh_v !== null)<div class="f-row"><span class="f-label" style="width:55px;">Logo Halal:</span><span class="f-value">{{ $lh_v ? 'Ya' : 'Tidak' }}</span></div>@endif
                                @if($dh_v !== null)<div class="f-row"><span class="f-label" style="width:55px;">Dok. Halal:</span><span class="f-value">{{ $dh_v ? 'Ya' : 'Tidak' }}</span></div>@endif
                                @if($ca_v !== null)<div class="f-row"><span class="f-label" style="width:55px;">COA:</span><span class="f-value">{{ $ca_v ? 'Ya' : 'Tidak' }}</span></div>@endif
                                <!-- @if($fc_v)<div class="f-row"><span class="f-label" style="width:55px;">File COA:</span><span class="f-value">Ada</span></div>@endif -->
                            @endif

                            @php
                                $sbs = json_decode($p->status_baris_array ?? '[]', true) ?? [];
                                $sb_v = $sbs[$ri] ?? null;
                            @endphp
                            @if($sb_v)
                                <div class="sec-title">Status Release</div>
                                <div class="f-row">
                                    <span class="f-label" style="width:55px;">Status:</span>
                                    <span class="f-value">
                                        @if($sb_v === 'Release')
                                            <span style="color:#2f855a;font-weight:bold;background:#c6f6d5;padding:1px 4px;border-radius:2px;">RELEASE</span>
                                        @elseif($sb_v === 'Hold')
                                            <span style="color:#9c4221;font-weight:bold;background:#feebc8;padding:1px 4px;border-radius:2px;">HOLD</span>
                                        @else
                                            <span style="color:#4a5568;font-weight:bold;background:#edf2f7;padding:1px 4px;border-radius:2px;">{{ strtoupper($sb_v) }}</span>
                                        @endif
                                    </span>
                                </div>
                            @endif

                            @php
                                $ibs = json_decode($p->image_bahan_baku_array ?? '[]', true) ?? [];
                                $ip_v = $ibs[$ri] ?? null;
                                $ifp = null;
                                if ($ip_v) { $ifp = public_path('storage/' . $ip_v); }
                            @endphp
                            @if($ifp && file_exists($ifp))
                                <div class="sec-title">Foto Bahan Baku</div>
                                <div style="margin-top:4px;padding-top:4px;border-top:1px solid #ddd;text-align:center;">
                                    <img src="{{ $ifp }}" alt="Foto" style="max-width:120px;max-height:80px;border:1px solid #dee2e6;border-radius:3px;padding:1px;">
                                </div>
                            @endif
                        </div>
                    @endforeach

                    @for($i = $pageRecords->count(); $i < $columnsPerPage; $i++)
                        <div class="col empty-col"></div>
                    @endfor
                </div>
                <div style="text-align:right;padding-right:10px;font-style:italic;font-size:8px;color:#666;margin-top:4px;page-break-inside:avoid;">
                    QW 01/00
                </div>

                <div class="signature-section">
                    <div class="sig-note">
                        <span class="ok">V</span> : OK (Kondisi Mobil, Kemasan, Warna, Benda Asing, Aroma: Sesuai Standar, Logo Halal, Halal Berlaku, COA: Tersedia)<br>
                        <span class="not-ok">X</span> : Parameter Tidak Sesuai
                    </div>
                    
                    @php $fRec = $pemeriksaans->first(); @endphp
                    <table class="sig-table">
                        <tr>
                            <td class="sig-cell">
                                <div class="sig-label">Dibuat Oleh:</div>
                                <div class="sig-space">
                                    @if($fRec && $fRec->qcVerifier)
                                        @php
                                            $d = "Dokumen #{$fRec->id} telah diverifikasi secara sistem oleh {$fRec->qcVerifier->name} (Tim QC)";
                                            $q = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate($d);
                                            $b = "data:image/svg+xml;base64," . base64_encode($q);
                                        @endphp
                                        <img src="{{ $b }}" class="qr-img" alt="QR">
                                    @else
                                        <div class="sig-line"></div>
                                    @endif
                                </div>
                                <div class="sig-name">{{ $fRec && $fRec->qcVerifier ? $fRec->qcVerifier->name : '-' }}</div>
                            </td>
                            <td class="sig-cell">
                                <div class="sig-label">Diketahui Oleh:</div>
                                <div class="sig-space">
                                    @if($fRec && $fRec->produksiVerifier)
                                        @php
                                            $d = "Dokumen #{$fRec->id} telah diverifikasi secara sistem oleh {$fRec->produksiVerifier->name} (Tim Warehouse)";
                                            $q = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate($d);
                                            $b = "data:image/svg+xml;base64," . base64_encode($q);
                                        @endphp
                                        <img src="{{ $b }}" class="qr-img" alt="QR">
                                    @else
                                        <div class="sig-line"></div>
                                    @endif
                                </div>
                                <div class="sig-name">{{ $fRec && $fRec->produksiVerifier ? $fRec->produksiVerifier->name : '-' }}</div>
                            </td>
                            <td class="sig-cell">
                                <div class="sig-label">Disetujui Oleh:</div>
                                <div class="sig-space">
                                    @if($fRec && $fRec->spvVerifier)
                                        @php
                                            $d = "Dokumen #{$fRec->id} telah diverifikasi secara sistem oleh {$fRec->spvVerifier->name} (Tim Supervisor QC)";
                                            $q = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate($d);
                                            $b = "data:image/svg+xml;base64," . base64_encode($q);
                                        @endphp
                                        <img src="{{ $b }}" class="qr-img" alt="QR">
                                    @else
                                        <div class="sig-line"></div>
                                    @endif
                                </div>
                                <div class="sig-name">{{ $fRec && $fRec->spvVerifier ? $fRec->spvVerifier->name : '-' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>

                @if(!$loop->last)
                    <div style="page-break-after: always;"></div>
                @endif
                </div>
            @endforeach
        @else
            <div class="empty-message">
                <p>Tidak ada data pemeriksaan yang sesuai dengan filter yang dipilih.</p>
            </div>
        @endif
        @endif {{-- end isAllShift --}}
    </div>
</body>
</html>
