<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="font-family: Arial, sans-serif; font-size: 10pt; color: #1a1a1a;">

@foreach(($pemeriksaans ?? []) as $idx => $p)
    @if($idx > 0)
        <div style="page-break-after: always;"></div>
    @endif

    @php
        $shiftName  = $p->shift  ? ($p->shift->shift        ?? '-') : '-';
        $produkName = $p->produk ? ($p->produk->nama_produk  ?? '-') : '-';
        $kategori   = $p->produk ? ($p->produk->kategori_code ?? null) : null;
        $plantName  = $p->user && $p->user->plant ? ($p->user->plant->plant ?? 'MEDAN') : 'MEDAN';
    @endphp

    @php
        $logoPath = public_path('dist/images/logo/cpi-logo.png');
        $logoExists = file_exists($logoPath);
    @endphp

    {{--
        ======== JUDUL / HEADER (flat, tanpa nested table, grid 12 kolom) ========
        Logo = 1 kolom | Nama perusahaan = 5 kolom | Judul dokumen = 6 kolom -> total 12,
        satu baris sejajar, sama seperti gaya header Loading Produk.
    --}}
    <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
        <tr>
            @if($logoExists)
            <td colspan="1" style="width:55px; text-align:center; vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:6px;">
                <img src="{{ $logoPath }}" width="42" height="42" style="display:block; margin:0 auto;" alt="Logo CPI">
            </td>
            @endif
            <td colspan="{{ $logoExists ? 5 : 6 }}" style="vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:10px 14px;">
                <span style="font-size:12pt; font-weight:bold; color:#1a1a1a; letter-spacing:0.5px;">PT. CHAROEN POKPHAND INDONESIA</span><br>
                <span style="font-size:9pt; color:#555555;">FOOD DIVISION {{ strtoupper($plantName) }}</span><br>
                <span style="font-size:9pt; color:#555555;">{{ strtoupper($plantName) }} - INDONESIA</span>
            </td>
            <td colspan="6" style="text-align:center; vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:10px 14px;">
                <span style="font-size:13pt; font-weight:bold; color:#1a1a1a; letter-spacing:0.5px;">PEMERIKSAAN SUHU RUANG CS MEAT</span>
            </td>
        </tr>
    </table>

    {{-- ======== SUBHEADER (grid 12 kolom: label=2, value=4, label=2, value=4) ======== --}}
    <table style="width:100%; border-collapse:collapse; border:1px solid #adb5bd; margin-bottom:12px;">
        <tbody>
            <tr>
                <td colspan="2" style="font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Tanggal</td>
                <td colspan="4" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $p->tanggal ? $p->tanggal->format('d/m/Y') : '-' }}</td>
                <td colspan="2" style="font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Shift</td>
                <td colspan="4" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $shiftName }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Suhu Produk</td>
                <td colspan="4" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; font-weight:bold; text-align:right;">{{ $p->suhu_produk ?? '-' }}</td>
                <td colspan="2" style="font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Produk</td>
                <td colspan="4" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $kategori ? $kategori.' - '.$produkName : $produkName }}</td>
            </tr>
        </tbody>
    </table>

    {{--
        ======== DATA & RIWAYAT (grid 12 kolom) ========
        No = 1 kolom | Lokasi = 3 kolom | Sebelumnya = 4 kolom | Sesudahnya = 4 kolom -> total 12
        Dipakai konsisten di tabel ini DAN di footer tanda tangan + baris QW 11/00 di bawah,
        supaya semuanya sejajar rapi saat dibaca sebagai Excel.
    --}}
    <p style="font-weight:bold; font-size:10pt; color:#2c3e50; margin:10px 0 5px;">Data &amp; Riwayat Pemeriksaan</p>
    <table style="width:100%; border-collapse:collapse; border:1px solid #adb5bd;">
        <thead>
            <tr>
                <th colspan="1" style="background-color:#2c3e50; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #1a252f; text-align:left;">No</th>
                <th colspan="3" style="background-color:#2c3e50; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #1a252f; text-align:left;">Lokasi</th>
                <th colspan="4" style="background-color:#2c3e50; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #1a252f; text-align:left;">Sebelumnya</th>
                <th colspan="4" style="background-color:#2c3e50; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #1a252f; text-align:left;">Sesudahnya</th>
            </tr>
        </thead>
        <tbody>
            @php
                $histNo = 1;
                $suhuFieldsConfig = [
                    'suhu_cold_storage'          => 'Cold Storage',
                    'suhu_anteroom_loading'      => 'Anteroom Loading',
                    'suhu_pre_loading'           => 'Pre Loading',
                    'suhu_prestaging'            => 'Prestaging',
                    'suhu_anteroom_ekspansi_abf' => 'Anteroom Ekspansi ABF',
                    'suhu_chillroom_rm'          => 'Chillroom RM',
                    'suhu_chillroom_domestik'    => 'Chillroom Domestik',
                ];

                $renderVal = function($val) {
                    if ($val === null || $val === '' || $val === []) return '-';
                    if (is_array($val)) {
                        $parts = [];
                        if (isset($val['setting'])) $parts[] = 'setting: ' . $val['setting'];
                        if (isset($val['display'])) $parts[] = 'display: ' . $val['display'];
                        if (isset($val['actual']))  $parts[] = 'actual: '  . $val['actual'];
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

                $initialPukul = '-';
                if ($firstHistory && isset($firstHistory->pukul_lama)) {
                    $initialPukul = $firstHistory->pukul_lama;
                } elseif ($p->pukul) {
                    $initialPukul = $p->pukul;
                }
            @endphp

            {{-- Separator: INPUT DATA PERTAMA --}}
            <tr>
                <td colspan="12" style="background-color:#e9ecef; font-weight:bold; font-size:9pt; text-align:center; color:#495057; padding:5px 8px; border:1px solid #adb5bd; border-top:2px solid #adb5bd;">
                    --- INPUT DATA PERTAMA ---
                </td>
            </tr>

            {{-- Baris Pukul Input Pertama --}}
            @if($initialPukul && $initialPukul !== '-')
                <tr>
                    <td colspan="1" style="text-align:center; color:#888; background-color:#f3e8ff; padding:5px 8px; border:1px solid #d0b0f0; font-style:italic; font-size:9pt;"></td>
                    <td colspan="3" style="background-color:#f3e8ff; padding:5px 8px; border:1px solid #d0b0f0; font-style:italic; font-size:9pt;">Pukul</td>
                    <td colspan="4" style="background-color:#fff8e1; text-align:center; padding:5px 8px; border:1px solid #d0b0f0; font-size:9pt;">-</td>
                    <td colspan="4" style="background-color:#e8f5e9; padding:5px 8px; border:1px solid #d0b0f0; font-size:9pt;">{{ $initialPukul }}</td>
                </tr>
            @endif

            @foreach($suhuFieldsConfig as $field => $label)
                @php $secData = $getInitialVal($field); @endphp
                @if(!empty($secData))
                    @if(in_array($field, ['suhu_cold_storage', 'suhu_anteroom_loading']))
                        @foreach($secData as $uKey => $item)
                            <tr>
                                <td colspan="1" style="text-align:center; color:#555; background-color:#ffffff; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">{{ $histNo++ }}</td>
                                <td colspan="3" style="background-color:#ffffff; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">{{ $label }} {{ $uKey }}</td>
                                <td colspan="4" style="background-color:#fff8e1; text-align:center; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">-</td>
                                <td colspan="4" style="background-color:#e8f5e9; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">{{ $renderVal($item) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="1" style="text-align:center; color:#555; background-color:#ffffff; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">{{ $histNo++ }}</td>
                            <td colspan="3" style="background-color:#ffffff; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">{{ $label }}</td>
                            <td colspan="4" style="background-color:#fff8e1; text-align:center; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">-</td>
                            <td colspan="4" style="background-color:#e8f5e9; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">{{ $renderVal($secData) }}</td>
                        </tr>
                    @endif
                @endif
            @endforeach

            {{-- Separator: RIWAYAT PERUBAHAN --}}
            @if($p->relationLoaded('histories') && $p->histories && $p->histories->count() > 0)
                <tr>
                    <td colspan="12" style="background-color:#fdf0f0; font-weight:bold; font-size:9pt; text-align:center; color:#c41e3a; padding:5px 8px; border:1px solid #c41e3a; border-top:2px solid #c41e3a; border-bottom:2px solid #c41e3a;">
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
                        @php $isPukul = (strcasecmp($change['lokasi'] ?? '', 'pukul') === 0); @endphp
                        <tr>
                            @if($isPukul)
                                <td colspan="1" style="text-align:center; background-color:#f3e8ff; padding:5px 8px; border:1px solid #d0b0f0; font-style:italic; font-size:9pt;"></td>
                                <td colspan="3" style="background-color:#f3e8ff; padding:5px 8px; border:1px solid #d0b0f0; font-style:italic; font-size:9pt;">{{ $change['lokasi'] }}</td>
                                <td colspan="4" style="background-color:#fff8e1; padding:5px 8px; border:1px solid #d0b0f0; font-size:9pt;">{{ $change['lama'] }}</td>
                                <td colspan="4" style="background-color:#e8f5e9; padding:5px 8px; border:1px solid #d0b0f0; font-size:9pt;">{{ $change['baru'] }}</td>
                            @else
                                <td colspan="1" style="text-align:center; color:#555; background-color:#ffffff; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">{{ $histNo++ }}</td>
                                <td colspan="3" style="background-color:#ffffff; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">{{ $change['lokasi'] }}</td>
                                <td colspan="4" style="background-color:#fff8e1; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">{{ $change['lama'] }}</td>
                                <td colspan="4" style="background-color:#e8f5e9; padding:5px 8px; border:1px solid #dee2e6; font-size:9pt;">{{ $change['baru'] }}</td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- ======== KETERANGAN ======== --}}
    @if(!empty($p->keterangan) || !empty($p->tindakan_koreksi))
        <p style="font-weight:bold; font-size:10pt; color:#2c3e50; margin:10px 0 5px;">Keterangan / Tindakan Koreksi</p>
        <table style="width:100%; border-collapse:collapse;">
            <tbody>
                @if(!empty($p->keterangan))
                    <tr>
                        <td style="width:150px; font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Keterangan</td>
                        <td style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ $p->keterangan }}</td>
                    </tr>
                @endif
                @if(!empty($p->tindakan_koreksi))
                    <tr>
                        <td style="width:150px; font-weight:600; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Tindakan Koreksi</td>
                        <td style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ $p->tindakan_koreksi }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif

    {{--
        ======== FOOTER: TANDA TANGAN + QW 11/00 (grid 12 kolom, sejajar tabel di atas) ========
        3 kolom tanda tangan x 4 = 12, sama seperti total kolom tabel Data & Riwayat.
        QW 11/00 dibuat baris tabel (bukan <p> lepas) supaya ikut sejajar juga.
    --}}
    <table style="width:100%; border-collapse:collapse; margin-top:16px; border-top:2px solid #adb5bd;">
        <thead>
            <tr>
                <th colspan="4" style="background-color:#2c3e50; color:#ffffff; font-size:9pt; font-weight:600; padding:6px 8px; border:1px solid #1a252f; text-align:center;">Dibuat Oleh</th>
                <th colspan="4" style="background-color:#2c3e50; color:#ffffff; font-size:9pt; font-weight:600; padding:6px 8px; border:1px solid #1a252f; text-align:center;">Diketahui Oleh</th>
                <th colspan="4" style="background-color:#2c3e50; color:#ffffff; font-size:9pt; font-weight:600; padding:6px 8px; border:1px solid #1a252f; text-align:center;">Disetujui Oleh</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" style="text-align:center; vertical-align:bottom; padding:40px 8px 8px; border:1px solid #adb5bd; font-size:9pt;">
                    <div style="border-top:1px solid #555; padding-top:4px; font-weight:bold;">{{ $p->qcVerifier->name ?? '-' }}</div>
                </td>
                <td colspan="4" style="text-align:center; vertical-align:bottom; padding:40px 8px 8px; border:1px solid #adb5bd; font-size:9pt;">
                    <div style="border-top:1px solid #555; padding-top:4px; font-weight:bold;">{{ $p->produksiVerifier->name ?? '-' }}</div>
                </td>
                <td colspan="4" style="text-align:center; vertical-align:bottom; padding:40px 8px 8px; border:1px solid #adb5bd; font-size:9pt;">
                    <div style="border-top:1px solid #555; padding-top:4px; font-weight:bold;">{{ $p->spvVerifier->name ?? '-' }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="12" style="text-align:right; font-style:italic; font-size:8pt; color:#888888; padding-top:4px; border:none;">QW 11/00</td>
            </tr>
        </tbody>
    </table>
@endforeach

</body>
</html>