<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; color: #1a1a1a;">
    @foreach(($pemeriksaans ?? []) as $idx => $p)
        @php
            $shiftName = $p->shift ? ($p->shift->shift ?? '-') : '-';
            $plantName = $p->user && $p->user->plant ? ($p->user->plant->plant ?? 'MEDAN') : 'MEDAN';
            $ekspedisiName = $p->ekspedisi ? ($p->ekspedisi->nama_ekspedisi ?? '-') : '-';
            
            $logoPath = public_path('dist/images/logo/cpi-logo.png');
            $logoExists = file_exists($logoPath);

            $produkRows = is_array($p->produk_data) ? $p->produk_data : [];
            $allCustomerIds = collect($produkRows)->pluck('id_customer')->filter()->unique()->values()->toArray();
            $customerMap = !empty($allCustomerIds) 
                ? \App\Models\Customer::whereIn('id', $allCustomerIds)->pluck('nama_cust', 'id')->toArray() 
                : [];
        @endphp

        {{-- ========== HEADER PERUSAHAAN + LOGO ========== --}}
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                @if($logoExists)
                <td style="width:55px; text-align:center; vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:6px;">
                    <img src="{{ $logoPath }}" width="42" height="42" alt="Logo CPI">
                </td>
                @endif
                <td colspan="{{ $logoExists ? 5 : 6 }}" style="vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:8px 12px;">
                    <span style="font-size:12pt; font-weight:bold; color:#c41e3a;">PT. CHAROEN POKPHAND INDONESIA</span><br>
                    <span style="font-size:9pt; color:#555555;">FOOD DIVISION {{ strtoupper($plantName) }}</span><br>
                    <span style="font-size:9pt; color:#555555;">{{ strtoupper($plantName) }} - INDONESIA</span>
                </td>
                <td colspan="6" style="text-align:center; vertical-align:middle; border:1px solid #adb5bd; background-color:#ffffff; padding:8px 12px;">
                    <span style="font-size:13pt; font-weight:bold; color:#1a1a1a;">PEMERIKSAAN RETURN BARANG CUSTOMER</span>
                </td>
            </tr>
        </table>

        {{-- ========== INFORMASI DASAR & KENDARAAN ========== --}}
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                <td colspan="12" style="font-size:9pt; font-weight:bold; color:#8b1428; background-color:#f8d7da; text-align:center; padding:6px; border:1px solid #adb5bd;">INFORMASI DASAR &amp; KENDARAAN</td>
            </tr>
            <tr>
                <td colspan="3" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Hari / Tanggal</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $p->tanggal ? $p->tanggal->format('d/m/Y') : '-' }}</td>
                <td colspan="3" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Shift</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $shiftName }}</td>
            </tr>
            <tr>
                <td colspan="3" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Ekspedisi</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $ekspedisiName }}</td>
                <td colspan="3" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">No. Polisi</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $p->no_polisi ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="3" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Nama Supir</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $p->nama_supir ?? '-' }}</td>
                <td colspan="3" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Waktu Kedatangan</td>
                <td colspan="3" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $p->waktu_kedatangan_display ?? ($p->waktu_kedatangan ?? '-') }}</td>
            </tr>
            <tr>
                <td colspan="3" style="font-weight:bold; background-color:#e9ecef; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt;">Suhu Mobil / Produk</td>
                <td colspan="9" style="background-color:#ffffff; border:1px solid #adb5bd; padding:5px 8px; font-size:9pt; text-align:right;">{{ $p->suhu_mobil ?? '-' }}</td>
            </tr>
        </table>

        {{-- ========== DETAIL PRODUK RETURN ========== --}}
        <table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                <td colspan="12" style="font-weight:bold; font-size:10pt; color:#ffffff; background-color:#2c3e50; text-align:center; padding:6px; border:1px solid #1a252f;">DETAIL PRODUK RETURN</td>
            </tr>
            <tr>
                <td style="background-color:#5b9bd5; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #3a6ea5; text-align:center;">No</td>
                <td style="background-color:#ed7d31; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #b85c1f; text-align:center;">Customer</td>
                <td style="background-color:#70ad47; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #4e7f31; text-align:center;">Nama Produk</td>
                <td style="background-color:#7030a0; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #4a1f6e; text-align:center;">Kondisi</td>
                <td style="background-color:#2f5597; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #1f3a69; text-align:center;">Suhu</td>
                <td style="background-color:#17a2b8; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #0f6f80; text-align:center;">Kode Produksi</td>
                <td style="background-color:#d64550; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #962e37; text-align:center;">Best Before</td>
                <td style="background-color:#8a9a5b; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #5f6d3e; text-align:center;">Jumlah</td>
                <td style="background-color:#6c757d; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #495057; text-align:center;">Kemasan</td>
                <td style="background-color:#e67e22; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #d35400; text-align:center;">Cek Produk</td>
                <td style="background-color:#27ae60; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #1e8449; text-align:center;">Alasan Return</td>
                <td style="background-color:#2980b9; color:#ffffff; font-size:9pt; font-weight:bold; padding:6px 8px; border:1px solid #1f618d; text-align:center;">Rekomendasi</td>
            </tr>
            @forelse($produkRows as $i => $data)
                @php
                    $custName = $customerMap[$data['id_customer'] ?? 0] ?? '-';
                    $prodName = $produkNamaById[$data['id_produk'] ?? 0] ?? '-';
                    $bb = $data['expired_date'] ?? null;
                    $bbLabel = '-';
                    if ($bb) {
                        try { $bbLabel = \Carbon\Carbon::parse($bb)->format('d/m/Y'); }
                        catch (\Exception $e) { $bbLabel = $bb; }
                    }
                    $kondisiKemasan = isset($data['kondisi_kemasan']) ? ($data['kondisi_kemasan'] ? 'Baik' : 'Rusak') : '-';
                    $kondisiCheck   = isset($data['kondisi_produk_check']) ? ($data['kondisi_produk_check'] ? 'Baik' : 'Rusak') : '-';
                @endphp
                <tr>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#dbe9f7; text-align:center;">{{ $i + 1 }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#fde9d9;">{{ $custName }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#e2f0d9;"><strong>{{ $prodName }}</strong></td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#e6e0ec; text-align:center;">{{ $data['kondisi_produk'] ?? '-' }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#dce6f1; text-align:center;">{{ $data['suhu_produk'] ?? '-' }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#d0f0f0; text-align:center;">{{ $data['kode_produksi'] ?? '-' }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#fadfe3; text-align:center;">{{ $bbLabel }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#eef0da; text-align:center;">{{ $data['jumlah_barang'] ?? '-' }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#e9ecef; text-align:center;">{{ $kondisiKemasan }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#fce4d6; text-align:center;">{{ $kondisiCheck }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#e8f8f5;">{{ $data['alasan_return'] ?? '-' }}</td>
                    <td style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#ebf5fb;">{{ $data['rekomendasi'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; text-align:center;">Tidak ada data produk</td>
                </tr>
            @endforelse
            @if(!empty($produkRows))
                <tr>
                    <td colspan="2" style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#e9ecef; font-weight:bold;">Keterangan</td>
                    <td colspan="10" style="font-size:9pt; padding:5px 8px; border:1px solid #adb5bd; background-color:#fff8e1;">
                        {{ collect($produkRows)->pluck('keterangan')->filter()->implode('; ') ?: '-' }}
                    </td>
                </tr>
            @endif
        </table>

        {{-- ========== TANDA TANGAN ========== --}}
        <table style="width:100%; border-collapse:collapse; margin-top:8px;">
            <tr>
                <td colspan="4" style="font-weight:bold; font-size:9pt; color:#ffffff; background-color:#2c3e50; text-align:center; padding:6px; border:1px solid #1a252f;">Dibuat Oleh</td>
                <td colspan="4" style="font-weight:bold; font-size:9pt; color:#ffffff; background-color:#2c3e50; text-align:center; padding:6px; border:1px solid #1a252f;">Diketahui Oleh</td>
                <td colspan="4" style="font-weight:bold; font-size:9pt; color:#ffffff; background-color:#2c3e50; text-align:center; padding:6px; border:1px solid #1a252f;">Disetujui Oleh</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:center; vertical-align:bottom; font-size:9pt; padding:20px 8px 6px; border:1px solid #adb5bd;">
                    <div style="font-weight:bold;">{{ $qcUser ?? '-' }}</div>
                    <div style="font-size:8pt; color:#555555;">(Tim QC)</div>
                </td>
                <td colspan="4" style="text-align:center; vertical-align:bottom; font-size:9pt; padding:20px 8px 6px; border:1px solid #adb5bd;">
                    <div style="font-weight:bold;">{{ $produksiUser ?? '-' }}</div>
                    <div style="font-size:8pt; color:#555555;">(Tim Warehouse)</div>
                </td>
                <td colspan="4" style="text-align:center; vertical-align:bottom; font-size:9pt; padding:20px 8px 6px; border:1px solid #adb5bd;">
                    <div style="font-weight:bold;">{{ $spvQcUser ?? '-' }}</div>
                    <div style="font-size:8pt; color:#555555;">(Tim SPV QC)</div>
                </td>
            </tr>
            <tr>
                <td colspan="12" style="text-align:right; font-style:italic; font-size:8pt; color:#888888; padding-top:4px;">QW 11/00</td>
            </tr>
        </table>

        @if(!$loop->last)
            <table style="width:100%;"><tr><td colspan="12">&nbsp;</td></tr><tr><td colspan="12">&nbsp;</td></tr></table>
        @endif
    @endforeach
</body>
</html>
