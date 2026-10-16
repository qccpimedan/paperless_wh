<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="font-family: Arial, sans-serif; font-size: 10pt; color: #1a1a1a;">

@php
    $isAllShift = $isAllShift ?? false;

    // Samakan struktur data: Semua Shift = banyak grup, Shift Tunggal = 1 grup
    if ($isAllShift) {
        $groups = collect($dataPerShift ?? [])->map(function ($g) {
            return ['shift' => $g['shift'], 'pemeriksaans' => $g['pemeriksaans']];
        });
    } else {
        $groups = collect([['shift' => $shift ?? null, 'pemeriksaans' => $pemeriksaans]]);
    }

    // Format tanggal periode
    $fmt = function ($v) {
        try { return $v ? \Carbon\Carbon::parse($v)->format('d/m/Y') : null; }
        catch (\Exception $e) { return $v; }
    };
    if (!empty($tanggal_dari) || !empty($tanggal_sampai)) {
        $periode = ($fmt($tanggal_dari ?? null) ?? '-') . ' s/d ' . ($fmt($tanggal_sampai ?? null) ?? '-');
    } elseif (!empty($tanggal)) {
        $periode = $fmt($tanggal);
    } else {
        $periode = '-';
    }

    $statusText = function ($v) {
        if ($v === null || $v === '') return '-';
        return $v == 1 ? 'OK' : 'NO';
    };
    $statusStyle = function ($t) {
        if ($t === 'OK') return 'text-align:center; color:#1f7a1f; font-weight:bold;';
        if ($t === 'NO') return 'text-align:center; color:#c41e3a; font-weight:bold; background-color:#f8d7da;';
        return 'text-align:center;';
    };
@endphp

@if($groups->isEmpty())
    <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
        <tr><td style="text-align:center; font-style:italic; padding:20px;">Tidak ada data</td></tr>
    </table>
@endif

@foreach($groups as $group)
    @php
        $groupPemeriksaans = $group['pemeriksaans'];
        $currentShift = $group['shift'];
        $firstRecord = $groupPemeriksaans->first();
        $plantName = ($firstRecord && $firstRecord->user && $firstRecord->user->plant)
            ? $firstRecord->user->plant->plant
            : 'MEDAN';
        $qcName   = $firstRecord?->qcVerifier?->name ?? ($qcUser ?? '-');
        $prodName = $firstRecord?->produksiVerifier?->name ?? ($produksiUser ?? '-');
        $spvName  = $firstRecord?->spvVerifier?->name ?? ($spvQcUser ?? '-');
    @endphp

    <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <colgroup>
            <col width="40">
            <col width="320">
            <col width="80">
            <col width="80">
            <col width="90">
            <col width="200">
            <col width="200">
        </colgroup>

        {{-- ===== HEADER (seperti PDF) ===== --}}
        <tr>
            <td colspan="4" style="border: none; border-bottom: 2px solid #1a1a1a; vertical-align: middle;">
                <strong style="font-size: 12pt;">PT. CHAROEN POKPHAND INDONESIA</strong><br>
                FOOD DIVISION {{ strtoupper($plantName) }}<br>
                {{ strtoupper($plantName) }} - INDONESIA
            </td>
            <td colspan="3" style="border: none; border-bottom: 2px solid #1a1a1a; text-align: right; vertical-align: middle; font-size: 14pt; font-weight: bold; color: #8b1428;">
                PEMERIKSAAN KEBERSIHAN AREA
            </td>
        </tr>

        {{-- ===== SUBHEADER (Shift | Tanggal | Total) ===== --}}
        <tr style="background-color: #f8f9fa;">
            <td colspan="2" style="background-color:#f8f9fa;"><strong>Shift:</strong> {{ $currentShift->shift ?? 'Semua Shift' }}</td>
            <td colspan="3" style="background-color:#f8f9fa;"><strong>Tanggal:</strong> {{ $periode }}</td>
            <td colspan="2" style="background-color:#f8f9fa;"><strong>Total:</strong> {{ $groupPemeriksaans->count() }} Dokumen</td>
        </tr>

        <tr><td colspan="7" style="border: none; height: 8px;">&nbsp;</td></tr>

        {{-- ===== ISI: per Area/Form ===== --}}
        @forelse($groupPemeriksaans as $p)
            @php
                $areaData = is_string($p->area_data) ? json_decode($p->area_data, true) : $p->area_data;
                $areaData = $areaData ?? [];
                $tglStr = $p->tanggal ? \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') : '-';
            @endphp

            @foreach($areaData as $item)
                @php
                    $selectedArea = \App\Models\InputArea::find($item['id_area'] ?? null);
                    $selectedForm = \App\Models\InputMasterForm::with('area')->find($item['id_master_form'] ?? null);
                    $fields = $selectedForm ? $selectedForm->fields : [];
                    $itemFields = collect($item['fields'] ?? []);
                    $isAutoFilledArea = $selectedForm && $selectedForm->id_input_area && $selectedForm->id_input_area == ($item['id_area'] ?? null);
                @endphp

                {{-- Judul blok: Area | Form | Tgl | Jam --}}
                <tr>
                    <td colspan="7" style="background-color: #f8f9fa; font-weight: bold; border-left: 4px solid #8b1428; padding: 8px;">
                        Area: {{ $selectedArea ? $selectedArea->nama_area : '-' }}
                        @if($isAutoFilledArea) [✓ AUTO] @endif
                        &nbsp;|&nbsp;
                        Form: {{ $selectedForm ? $selectedForm->nama_form : '-' }}
                        @if($selectedForm && $selectedForm->area) [{{ $selectedForm->area->nama_area }}] @endif
                        &nbsp;|&nbsp;
                        Tgl: {{ $tglStr }}
                        &nbsp;|&nbsp;
                        Jam: {{ $item['jam_sebelum_proses'] ?? '-' }} / {{ $item['jam_saat_proses'] ?? '-' }}
                    </td>
                </tr>

                {{-- Header tabel --}}
                <tr>
                    <th style="background-color: #8b1428; color: #ffffff; text-align: center;">#</th>
                    <th style="background-color: #8b1428; color: #ffffff;">ASPEK YANG DINILAI</th>
                    <th style="background-color: #8b1428; color: #ffffff; text-align: center;">SEBELUM</th>
                    <th style="background-color: #8b1428; color: #ffffff; text-align: center;">SESUDAH</th>
                    <th style="background-color: #8b1428; color: #ffffff; text-align: center;">VERIFIKASI</th>
                    <th style="background-color: #8b1428; color: #ffffff;">KETERANGAN</th>
                    <th style="background-color: #8b1428; color: #ffffff;">TINDAKAN</th>
                </tr>

                @forelse($fields as $fIdx => $field)
                    @php
                        $d = $itemFields->firstWhere('id_master_form_field', (int) $field->id);
                        $d = $d ?? [];
                        $sb = $statusText($d['status_sebelum_proses'] ?? null);
                        $ss = $statusText($d['status_saat_proses'] ?? null);
                        $sv = $statusText($d['verifikasi_hasil'] ?? null);
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $fIdx + 1 }}</td>
                        <td>{{ $field->field_name }}</td>
                        <td style="{{ $statusStyle($sb) }}">{{ $sb }}</td>
                        <td style="{{ $statusStyle($ss) }}">{{ $ss }}</td>
                        <td style="{{ $statusStyle($sv) }}">{{ $sv }}</td>
                        <td>{{ $d['keterangan'] ?? '-' }}</td>
                        <td>{{ $d['tindakan_koreksi'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; font-style: italic;">Tidak ada aspek penilaian</td>
                    </tr>
                @endforelse

                {{-- Jarak antar blok --}}
                <tr><td colspan="7" style="border: none; height: 8px;">&nbsp;</td></tr>
            @endforeach
        @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; font-style: italic;">Tidak ada data</td>
            </tr>
        @endforelse

        {{-- ===== KODE DOKUMEN ===== --}}
        <tr>
            <td colspan="7" style="border: none; text-align: right; font-style: italic; font-size: 9pt; color: #666666;">QC 07/00</td>
        </tr>

        {{-- ===== TANDA TANGAN (QR diganti nama verifikator) ===== --}}
        <tr>
            <td colspan="3" style="text-align: center; font-weight: bold; color: #495057;">Dibuat Oleh</td>
            <td colspan="2" style="text-align: center; font-weight: bold; color: #495057;">Diketahui Oleh</td>
            <td colspan="2" style="text-align: center; font-weight: bold; color: #495057;">Disetujui Oleh</td>
        </tr>
        <tr>
            <td colspan="3" style="height: 60px; text-align: center; vertical-align: bottom;">
                @if($firstRecord && $firstRecord->qcVerifier)<em style="color:#1f7a1f;">Diverifikasi sistem (Tim QC)</em><br>@endif
                <strong>{{ $qcName }}</strong>
            </td>
            <td colspan="2" style="height: 60px; text-align: center; vertical-align: bottom;">
                @if($firstRecord && $firstRecord->produksiVerifier)<em style="color:#1f7a1f;">Diverifikasi sistem (Tim Warehouse)</em><br>@endif
                <strong>{{ $prodName }}</strong>
            </td>
            <td colspan="2" style="height: 60px; text-align: center; vertical-align: bottom;">
                @if($firstRecord && $firstRecord->spvVerifier)<em style="color:#1f7a1f;">Diverifikasi sistem (Tim Supervisor QC)</em><br>@endif
                <strong>{{ $spvName }}</strong>
            </td>
        </tr>
    </table>

    {{-- Pisahkan antar shift (mode Semua Shift) --}}
    @if(!$loop->last)
        <br><br>
    @endif
@endforeach

</body>
</html>