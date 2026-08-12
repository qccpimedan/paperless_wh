<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; color: #1a1a1a;">
    {{--
        CATATAN:
        1. Maatwebsite Excel / PhpSpreadsheet HANYA membaca inline style="..."
           saat convert HTML ke sheet. Warna lewat <style> blok / class TIDAK
           terbaca sama sekali. Jadi semua warna di bawah ditulis inline.
        2. Tidak ada <table> di dalam <td> (nested table) sama sekali, supaya
           logo & judul tidak terpotong.
    --}}
    @foreach(($pemeriksaans ?? []) as $idx => $p)
        @php
            $shiftName   = $p->shift ? ($p->shift->shift ?? '-') : '-';
            $plantName   = $p->user && $p->user->plant ? ($p->user->plant->plant ?? 'MEDAN') : 'MEDAN';
            $kendaraan   = $p->kendaraan ? (($p->kendaraan->jenis_kendaraan ?? '-') . ' - ' . ($p->kendaraan->no_kendaraan ?? '-')) : '-';
            $supirName   = $p->supir ? ($p->supir->nama_supir ?? '-') : '-';
            $tujuanLabel = '-';
            if ($p->tujuanPengiriman) {
                $tujuanLabel = $p->tujuanPengiriman->customer
                    ? ($p->tujuanPengiriman->customer->nama_cust ?? '') . ($p->tujuanPengiriman->nama_tujuan && $p->tujuanPengiriman->nama_tujuan !== '-' ? ' - ' . $p->tujuanPengiriman->nama_tujuan : '')
                    : ($p->tujuanPengiriman->nama_tujuan ?? '-');
            }
            $segelLabel = '-';
            if ($p->segel_gembok === null) $segelLabel = '-';
            elseif ($p->segel_gembok) $segelLabel = 'Segel' . ($p->no_segel ? ' (No: ' . $p->no_segel . ')' : '');
            else $segelLabel = 'Gembok';

            $tempProdukStr = '-';
            if (is_array($p->temperature_produk) && count($p->temperature_produk)) {
                $tempProdukStr = implode(', ', array_map(fn($t) => $t . '°C', $p->temperature_produk));
            }
            $tempMobilStr = $p->temperature_mobil ? ($p->temperature_mobil . '°C') : '-';
            $kondisiProduk = $p->kondisi_produk ?? '-';

            $logoPath = public_path('dist/images/logo/cpi-logo.png');
            $logoExists = file_exists($logoPath);
        @endphp

        {{-- ========== HEADER PERUSAHAAN + LOGO (flat, tanpa nested table) ========== --}}
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                @if($logoExists)
                <td style="width:55px; text-align:center; vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:6px;">
                    <img src="{{ $logoPath }}" width="42" height="42" alt="Logo CPI">
                </td>
                @endif
                <td colspan="{{ $logoExists ? 4 : 5 }}" style="vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:8px 12px;">
                    <span style="font-size:12pt; font-weight:bold; color:#c41e3a;">PT. CHAROEN POKPHAND INDONESIA</span><br>
                    <span style="font-size:9pt; color:#555555;">FOOD DIVISION {{ strtoupper($plantName) }}</span><br>
                    <span style="font-size:9pt; color:#555555;">{{ strtoupper($plantName) }} - INDONESIA</span>
                </td>
                <td colspan="4" style="text-align:center; vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:8px 12px;">
                    <span style="font-size:13pt; font-weight:bold; color:#1a1a1a;">PEMERIKSAAN LOADING PRODUK</span>
                </td>
            </tr>
        </table>

        {{-- ========== INFORMASI DASAR ========== --}}
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                <td colspan="9" style="font-size:9pt; font-weight:bold; color:#8b1428; background-color:#f8d7da; text-align:center; padding:6px; border:1px solid #adb5bd;">INFORMASI DASAR</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Hari/Tanggal</td>
                <td colspan="2" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $p->tanggal ? $p->tanggal->format('d/m/Y') : '-' }}</td>
                <td colspan="2" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Shift</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $shiftName }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Kendaraan</td>
                <td colspan="2" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $kendaraan }}</td>
                <td colspan="2" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Supir</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $supirName }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Tujuan</td>
                <td colspan="7" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $tujuanLabel }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Segel / Gembok</td>
                <td colspan="7" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $segelLabel }}</td>
            </tr>
        </table>

        {{-- ========== WAKTU LOADING & TEMPERATURE ========== --}}
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                <td colspan="9" style="font-size:9pt; font-weight:bold; color:#8b1428; background-color:#f8d7da; text-align:center; padding:6px; border:1px solid #adb5bd;">WAKTU LOADING &amp; TEMPERATURE</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Mulai Loading</td>
                <td colspan="2" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ $p->star_loading ?? '-' }}</td>
                <td colspan="2" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Selesai Loading</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ $p->selesai_loading ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Temperature Mobil</td>
                <td colspan="2" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ $tempMobilStr }}</td>
                <td colspan="2" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Kondisi Produk</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ $kondisiProduk }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Temperature Produk</td>
                <td colspan="7" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">{{ $tempProdukStr }}</td>
            </tr>
        </table>

        {{-- ========== DETAIL PRODUK (pewarnaan per kolom, inline) ========== --}}
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                <td colspan="9" style="font-weight:bold; font-size:10pt; color:#ffffff; background-color:#2c3e50; text-align:center; padding:6px; border:1px solid #1a252f;">DETAIL PRODUK</td>
            </tr>
            <tr>
                <td style="background-color:#5b9bd5; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #3a6ea5; text-align:center;">No</td>
                <td style="background-color:#70ad47; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #4e7f31; text-align:center;">Nama Produk</td>
                <td style="background-color:#ed7d31; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #b85c1f; text-align:center;">Customer / Tujuan</td>
                <td style="background-color:#7030a0; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #4a1f6e; text-align:center;">Kode Produksi</td>
                <td style="background-color:#2f5597; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #1f3a69; text-align:center;">Best Before</td>
                <td style="background-color:#17a2b8; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #0f6f80; text-align:center;">Jml Kemasan</td>
                <td style="background-color:#d64550; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #962e37; text-align:center;">Jml Sampling</td>
                <td style="background-color:#8a9a5b; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #5f6d3e; text-align:center;">Berat/Karung</td>
                <td style="background-color:#6c757d; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #495057; text-align:center;">Kondisi</td>
            </tr>
            @php $produkRows = is_array($p->produk_data) ? $p->produk_data : []; @endphp
            @forelse($produkRows as $i => $data)
                @php
                    $idTujuanItem = $data['id_tujuan_pengiriman'] ?? null;
                    $tujuanItemLabel = '-';
                    if ($idTujuanItem) {
                        $tujuanObj = \App\Models\TujuanPengiriman::with('customer')->find($idTujuanItem);
                        if ($tujuanObj) {
                            $tujuanItemLabel = $tujuanObj->customer
                                ? ($tujuanObj->customer->nama_cust ?? '') . ($tujuanObj->nama_tujuan && $tujuanObj->nama_tujuan !== '-' ? ' - ' . $tujuanObj->nama_tujuan : '')
                                : ($tujuanObj->nama_tujuan ?? '-');
                        }
                    }
                    $bb = $data['best_before'] ?? null;
                    $bbLabel = '-';
                    if ($bb) {
                        try { $bbLabel = \Carbon\Carbon::parse($bb)->format('d/m/Y'); }
                        catch (\Exception $e) { $bbLabel = $bb; }
                    }
                    $kondisiKemasan = isset($data['kondisi_kemasan']) ? ((bool)$data['kondisi_kemasan'] ? 'Baik' : 'Tidak Baik') : '-';
                @endphp
                <tr>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#dbe9f7; text-align:center;">{{ $i + 1 }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#e2f0d9;"><strong>{{ $produkNamaById[$data['id_produk'] ?? 0] ?? 'Tidak ditemukan' }}</strong></td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#fde9d9;">{{ $tujuanItemLabel }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#e6e0ec;">{{ $data['kode_produksi'] ?? '-' }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#dce6f1; text-align:center;">{{ $bbLabel }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#d0f0f0; text-align:center;">{{ $data['jumlah_kemasan'] ?? '-' }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#fadfe3; text-align:center;">{{ $data['jumlah_sampling'] ?? '-' }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#eef0da; text-align:center;">{{ $data['berat_perkarung'] ?? '-' }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#e9ecef; text-align:center;">{{ $kondisiKemasan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; text-align:center;">Tidak ada data produk</td>
                </tr>
            @endforelse
            @if(!empty($produkRows))
                <tr>
                    <td colspan="2" style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#e9ecef; font-weight:bold;">Keterangan</td>
                    <td colspan="7" style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#fff8e1;">
                        {{ collect($produkRows)->pluck('keterangan')->filter()->implode('; ') ?: '-' }}
                    </td>
                </tr>
            @endif
        </table>

        {{-- ========== TANDA TANGAN ========== --}}
        <table style="width:100%; border-collapse:collapse; margin-top:8px;">
            <tr>
                <td colspan="3" style="font-weight:bold; font-size:9pt; color:#ffffff; background-color:#2c3e50; text-align:center; padding:6px; border:1px solid #1a252f;">Dibuat Oleh</td>
                <td colspan="3" style="font-weight:bold; font-size:9pt; color:#ffffff; background-color:#2c3e50; text-align:center; padding:6px; border:1px solid #1a252f;">Diketahui Oleh</td>
                <td colspan="3" style="font-weight:bold; font-size:9pt; color:#ffffff; background-color:#2c3e50; text-align:center; padding:6px; border:1px solid #1a252f;">Disetujui Oleh</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:center; vertical-align:bottom; font-size:9pt; padding:20px 8px 6px; border:1px solid #adb5bd;">
                    <div style="font-weight:bold;">{{ $qcUser ?? '-' }}</div>
                    <div style="font-size:8pt; color:#555555;">(Tim QC)</div>
                </td>
                <td colspan="3" style="text-align:center; vertical-align:bottom; font-size:9pt; padding:20px 8px 6px; border:1px solid #adb5bd;">
                    <div style="font-weight:bold;">{{ $produksiUser ?? '-' }}</div>
                    <div style="font-size:8pt; color:#555555;">(Tim Warehouse)</div>
                </td>
                <td colspan="3" style="text-align:center; vertical-align:bottom; font-size:9pt; padding:20px 8px 6px; border:1px solid #adb5bd;">
                    <div style="font-weight:bold;">{{ $spvQcUser ?? '-' }}</div>
                    <div style="font-size:8pt; color:#555555;">(Tim SPV QC)</div>
                </td>
            </tr>
            <tr>
                <td colspan="9" style="text-align:right; font-style:italic; font-size:8pt; color:#888888; padding-top:4px;">QW 10/00</td>
            </tr>
        </table>

        @if(!$loop->last)
            <table style="width:100%;"><tr><td colspan="9">&nbsp;</td></tr><tr><td colspan="9">&nbsp;</td></tr></table>
        @endif
    @endforeach
</body>
</html>