<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table { border-collapse: collapse; width: 100%; font-size: 10px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f1f3f5; font-weight: bold; text-align: left; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #c41e3a; padding-bottom: 10px; }
        .header h1 { font-size: 14px; margin: 0; color: #c41e3a; }
        .header h2 { font-size: 13px; margin: 5px 0; color: #1a1a1a; background: #f1f3f5; padding: 8px; display: inline-block; border-left: 4px solid #c41e3a; }
        .header p { font-size: 9px; margin: 2px 0; }
        .subheader { margin-bottom: 10px; border: 1px solid #dee2e6; background: #f8f9fa; }
        .subheader-table { width: 100%; }
        .subheader-table th { width: 25%; background: #f8f9fa; font-weight: 600; color: #495057; }
        .subheader-table td { background: #fff; }
        .section-title { font-weight: bold; font-size: 10px; color: #c41e3a; margin: 10px 0 5px 0; padding: 3px 0; }
        .data-table { width: 100%; }
        .data-table th { background: #f1f3f5; font-weight: bold; font-size: 9px; text-align: left; }
        .data-table td { font-size: 9px; }
        .signature-table { width: 100%; margin-top: 15px; border-top: 1px solid #dee2e6; padding-top: 10px; }
        .signature-table th { text-align: center; font-size: 9px; }
        .signature-table td { text-align: center; font-size: 9px; padding: 5px; }
        .page-break { page-break-after: always; }
        .bg-warning { background-color: #fff3cd; }
        .bg-success { background-color: #d4edda; }
        .bg-light { background-color: #f8f9fa; }
        .text-center { text-align: center; }
        .font-weight-bold { font-weight: bold; }
    </style>
</head>
<body>
    @foreach(($pemeriksaans ?? []) as $idx => $p)
        @if($idx > 0)
            <div class="page-break"></div>
        @endif

        <div class="header">
            <h1>PT. CHAROEN POKPHAND INDONESIA</h1>
            <p>FOOD DIVISION {{ strtoupper($p->user->plant->plant ?? 'MEDAN') }}</p>
            <p>{{ strtoupper($p->user->plant->plant ?? 'MEDAN') }} - INDONESIA</p>
            <h2>PEMERIKSAAN SUHU RUANG CS MEAT</h2>
        </div>

        @php
            $shiftName = $p->shift ? ($p->shift->shift ?? '-') : '-';
            $produkName = $p->produk ? ($p->produk->nama_produk ?? '-') : '-';
            $kategori = $p->produk ? ($p->produk->kategori_code ?? null) : null;

            $cold = is_array($p->suhu_cold_storage) ? $p->suhu_cold_storage : (json_decode($p->suhu_cold_storage ?? '[]', true) ?: []);
            $anteroom = is_array($p->suhu_anteroom_loading) ? $p->suhu_anteroom_loading : (json_decode($p->suhu_anteroom_loading ?? '[]', true) ?: []);
            $pre = is_array($p->suhu_pre_loading) ? $p->suhu_pre_loading : (json_decode($p->suhu_pre_loading ?? '[]', true) ?: []);
            $prestaging = is_array($p->suhu_prestaging) ? $p->suhu_prestaging : (json_decode($p->suhu_prestaging ?? '[]', true) ?: []);
            $abf = is_array($p->suhu_anteroom_ekspansi_abf) ? $p->suhu_anteroom_ekspansi_abf : (json_decode($p->suhu_anteroom_ekspansi_abf ?? '[]', true) ?: []);
            $rm = is_array($p->suhu_chillroom_rm) ? $p->suhu_chillroom_rm : (json_decode($p->suhu_chillroom_rm ?? '[]', true) ?: []);
            $dom = is_array($p->suhu_chillroom_domestik) ? $p->suhu_chillroom_domestik : (json_decode($p->suhu_chillroom_domestik ?? '[]', true) ?: []);
        @endphp

        <div class="subheader">
            <table class="subheader-table">
                <tbody>
                    <tr><th>Tanggal</th><td>{{ $p->tanggal ? $p->tanggal->format('d/m/Y') : '-' }}</td></tr>
                    <tr><th>Shift</th><td>{{ $shiftName }}</td></tr>
                    <tr><th>Suhu Produk</th><td style="font-weight: bold;">{{ $p->suhu_produk ?? '-' }}</td></tr>
                    <tr><th>Produk</th><td>{{ $kategori ? $kategori.' - '.$produkName : $produkName }}</td></tr>
                </tbody>
            </table>
        </div>

        {{-- Tabel per ruangan ditiadakan karena format riwayat di bawah sudah melengkapi data initial (input pertama) --}}

        <div class="section-title">Data &amp; Riwayat Pemeriksaan</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 25%">Lokasi</th>
                    <th style="width: 35%">Sebelumnya</th>
                    <th style="width: 35%">Sesudahnya</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $histNo = 1;
                    $suhuFieldsConfig = [
                        'suhu_cold_storage' => 'Cold Storage',
                        'suhu_anteroom_loading' => 'Anteroom Loading',
                        'suhu_pre_loading' => 'Pre Loading',
                        'suhu_prestaging' => 'Prestaging',
                        'suhu_anteroom_ekspansi_abf' => 'Anteroom Ekspansi ABF',
                        'suhu_chillroom_rm' => 'Chillroom RM',
                        'suhu_chillroom_domestik' => 'Chillroom Domestik',
                    ];

                    $renderVal = function($val) {
                        if ($val === null || $val === '' || $val === []) return '-';
                        if (is_array($val)) {
                            $parts = [];
                            if (isset($val['setting'])) $parts[] = 'setting: ' . $val['setting'];
                            if (isset($val['display'])) $parts[] = 'display: ' . $val['display'];
                            if (isset($val['actual'])) $parts[] = 'actual: ' . $val['actual'];
                            return !empty($parts) ? implode('; ', $parts) : '-';
                        }
                        return (string) $val;
                    };

                    $firstHistory = $p->histories->sortBy('created_at')->first();
                    $getInitialVal = function($field) use ($firstHistory, $p) {
                        $oldField = $field . '_lama';
                        if ($firstHistory) {
                            $val = $firstHistory->$oldField;
                            if (is_string($val) && !empty($val)) {
                                $decoded = json_decode($val, true);
                                return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $val;
                            }
                            return $val;
                        }
                        return $p->$field;
                    };
                @endphp

                <tr>
                    <td colspan="4" class="bg-light font-weight-bold text-center">--- INPUT DATA PERTAMA ---</td>
                </tr>
                @foreach($suhuFieldsConfig as $field => $label)
                    @php $secData = $getInitialVal($field); @endphp
                    @if(!empty($secData))
                        @if(in_array($field, ['suhu_cold_storage', 'suhu_anteroom_loading']))
                            @foreach($secData as $uKey => $item)
                                <tr>
                                    <td class="text-center">{{ $histNo++ }}</td>
                                    <td>{{ $label }} {{ $uKey }}</td>
                                    <td class="bg-warning text-center">-</td>
                                    <td class="bg-success">{{ $renderVal($item) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center">{{ $histNo++ }}</td>
                                <td>{{ $label }}</td>
                                <td class="bg-warning text-center">-</td>
                                <td class="bg-success">{{ $renderVal($secData) }}</td>
                            </tr>
                        @endif
                    @endif
                @endforeach

                @if($p->relationLoaded('histories') && $p->histories && $p->histories->count() > 0)
                    <tr>
                        <td colspan="4" class="bg-light font-weight-bold text-center" style="color: #c41e3a; border-top: 2px solid #dee2e6;">
                            --- RIWAYAT PERUBAHAN / UPDATE ---
                        </td>
                    </tr>
                    @foreach($p->histories->sortBy('created_at') as $history)
                        @php
                            $changes = [];

                            if (($history->pukul_lama ?? null) != ($history->pukul_baru ?? null)) {
                                $changes[] = ['lokasi' => 'Pukul', 'lama' => $history->pukul_lama ?? '(Kosong)', 'baru' => $history->pukul_baru ?? '(Kosong)'];
                            }

                            if (($history->keterangan_lama ?? null) != ($history->keterangan_baru ?? null)) {
                                $changes[] = ['lokasi' => 'Keterangan', 'lama' => $history->keterangan_lama ?? '(Kosong)', 'baru' => $history->keterangan_baru ?? '(Kosong)'];
                            }

                            if (($history->tindakan_koreksi_lama ?? null) != ($history->tindakan_koreksi_baru ?? null)) {
                                $changes[] = ['lokasi' => 'Tindakan Koreksi', 'lama' => $history->tindakan_koreksi_lama ?? '(Kosong)', 'baru' => $history->tindakan_koreksi_baru ?? '(Kosong)'];
                            }

                            foreach ($suhuFieldsConfig as $field => $label) {
                                $oldField = $field . '_lama';
                                $newField = $field . '_baru';
                                $oldValue = $history->$oldField ?? null;
                                $newValue = $history->$newField ?? null;

                                if (is_string($oldValue) && !empty($oldValue)) {
                                    $decoded = json_decode($oldValue, true);
                                    $oldValue = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
                                }
                                if (is_string($newValue) && !empty($newValue)) {
                                    $decoded = json_decode($newValue, true);
                                    $newValue = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
                                }
                                
                                if (is_object($oldValue)) { $oldValue = json_decode(json_encode($oldValue), true); }
                                if (is_object($newValue)) { $newValue = json_decode(json_encode($newValue), true); }

                                $oldArray = is_array($oldValue) ? $oldValue : [];
                                $newArray = is_array($newValue) ? $newValue : [];

                                if (empty($oldArray) && empty($newArray)) continue;

                                if (in_array($field, ['suhu_cold_storage', 'suhu_anteroom_loading'])) {
                                    $allUnits = array_unique(array_merge(array_keys($oldArray), array_keys($newArray)));
                                    foreach ($allUnits as $uKey) {
                                        $oldItem = $oldArray[$uKey] ?? null;
                                        $newItem = $newArray[$uKey] ?? null;
                                        if (json_encode($oldItem) === json_encode($newItem)) continue;

                                        $changes[] = ['lokasi' => $label . ' ' . $uKey, 'lama' => $renderVal($oldItem), 'baru' => $renderVal($newItem)];
                                    }
                                } else {
                                    if (json_encode($oldArray) !== json_encode($newArray)) {
                                        $changes[] = ['lokasi' => $label, 'lama' => $renderVal($oldArray), 'baru' => $renderVal($newArray)];
                                    }
                                }
                            }
                        @endphp

                        @foreach($changes as $change)
                            @php
                                $isPukul = (strcasecmp($change['lokasi'] ?? '', 'pukul') === 0);
                            @endphp
                            <tr>
                                <td class="text-center">{{ $isPukul ? '' : $histNo++ }}</td>
                                <td>{{ $change['lokasi'] }}</td>
                                <td class="bg-warning">{{ $change['lama'] }}</td>
                                <td class="bg-success">{{ $change['baru'] }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>

        @if(!empty($p->keterangan) || !empty($p->tindakan_koreksi))
            <div class="section-title">Keterangan / Tindakan Koreksi</div>
            <table class="data-table">
                <tbody>
                    @if(!empty($p->keterangan))
                        <tr>
                            <th style="width: 25%">Keterangan</th>
                            <td>{{ $p->keterangan }}</td>
                        </tr>
                    @endif
                    @if(!empty($p->tindakan_koreksi))
                        <tr>
                            <th style="width: 25%">Tindakan Koreksi</th>
                            <td>{{ $p->tindakan_koreksi }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif

        <div style="text-align: right; font-style: italic; font-size: 9px; color: #666; margin-top: 5px;">
            QW 11/00
        </div>

        <table class="signature-table">
            <thead>
                <tr>
                    <th>Dibuat Oleh</th>
                    <th>Diketahui Oleh</th>
                    <th>Disetujui Oleh</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        @if($p->qcVerifier)
                            <div style="margin-top: 10px; font-size: 8px; color: #666;">
                                
                            </div>
                        @endif
                        <div style="margin-top: 5px; font-weight: bold;">{{ $p->qcVerifier->name ?? '-' }}</div>
                    </td>
                    <td>
                        @if($p->produksiVerifier)
                            <div style="margin-top: 10px; font-size: 8px; color: #666;">
                                
                            </div>
                        @endif
                        <div style="margin-top: 5px; font-weight: bold;">{{ $p->produksiVerifier->name ?? '-' }}</div>
                    </td>
                    <td>
                        @if($p->spvVerifier)
                            <div style="margin-top: 10px; font-size: 8px; color: #666;">
                                
                            </div>
                        @endif
                        <div style="margin-top: 5px; font-weight: bold;">{{ $p->spvVerifier->name ?? '-' }}</div>
                    </td>
                </tr>
            </tbody>
        </table>
    @endforeach
</body>
</html>
