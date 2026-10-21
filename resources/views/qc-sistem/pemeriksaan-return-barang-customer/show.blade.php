@extends('layouts.app')
@section('container')
@php
    $m = $pemeriksaanReturnBarangCustomer;
    $produkList = $m->produk_data ?? [];
    $produkCount = ($produkList && count($produkList) > 0) ? count($produkList) : 0;
    $show = fn ($v) => ($v !== null && $v !== '') ? e($v) : '<span class="fp-dash">-</span>';
@endphp

<style>
    /* ===== Detail Kedatangan Kemasan (scoped .fp-) ===== */
    .fp { --fp-ink:#0f172a; --fp-muted:#64748b; --fp-line:#e5e9f0; --fp-soft:#f8fafc;
          --fp-blue:#2563eb; --fp-blue-soft:#eff6ff; --fp-blue-line:#bfdbfe;
          --fp-green:#16a34a; --fp-green-soft:#ecfdf3; --fp-green-line:#bbf7d0;
          --fp-red:#dc2626; --fp-red-soft:#fef2f2; --fp-red-line:#fecaca;
          --fp-amber:#f59e0b; --fp-navy:#0b1220; }
    .fp-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }

    /* Header */
    .fp-head { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .fp-head h3 { margin:0; font-weight:700; letter-spacing:-.02em; color:var(--fp-ink); }
    .fp-head p  { margin:.25rem 0 0; color:var(--fp-muted); font-size:.85rem; }
    .fp-actions { display:flex; gap:.5rem; flex-wrap:wrap; }
    .fp-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.5rem .9rem; border-radius:.5rem; font-size:.82rem; font-weight:600;
              border:1px solid var(--fp-line); background:#fff; color:var(--fp-ink); text-decoration:none; cursor:pointer; transition:background .15s; }
    .fp-btn:hover { background:var(--fp-soft); color:var(--fp-ink); }
    .fp-btn-warn { background:var(--fp-amber); border-color:var(--fp-amber); color:#fff; }
    .fp-btn-warn:hover { background:#d97706; border-color:#d97706; color:#fff; }
    .fp-btn-blue { background:var(--fp-blue-soft); border-color:var(--fp-blue-line); color:var(--fp-blue); }
    .fp-btn-blue:hover { background:#dbeafe; color:var(--fp-blue); }
    .fp-btn-sm { padding:.35rem .7rem; font-size:.76rem; }

    /* Panel */
    .fp-panel { background:#fff; border:1px solid var(--fp-line); border-radius:.9rem; margin-bottom:1.5rem; overflow:hidden; }
    .fp-panel-head { display:flex; justify-content:space-between; align-items:center; gap:.75rem; flex-wrap:wrap; padding:.9rem 1.25rem; border-bottom:1px solid var(--fp-line); }
    .fp-panel-head h5 { margin:0; font-size:.88rem; font-weight:700; letter-spacing:.02em; text-transform:uppercase; color:var(--fp-ink); display:flex; align-items:center; gap:.6rem; }
    .fp-panel-head h5 i.bar { width:.28rem; height:1rem; border-radius:.2rem; background:var(--fp-blue); display:inline-block; }
    .fp-panel-head h5 i.bar.green { background:var(--fp-green); }
    .fp-panel-body { padding:1.25rem; }
    .fp-id { font-size:.72rem; color:#94a3b8; }
    .fp-pill { display:inline-flex; align-items:center; gap:.35rem; padding:.2rem .7rem; border-radius:999px; font-size:.74rem; font-weight:600; }
    .fp-pill-ok { background:var(--fp-green-soft); color:var(--fp-green); border:1px solid var(--fp-green-line); }
    .fp-pill-bad { background:var(--fp-red-soft); color:var(--fp-red); border:1px solid var(--fp-red-line); }

    /* Field grid */
    .fp-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:1.25rem 1.5rem; }
    .fp-field-label { font-size:.7rem; font-weight:600; letter-spacing:.04em; text-transform:uppercase; color:var(--fp-muted); margin-bottom:.35rem; }
    .fp-field-value { font-weight:600; color:var(--fp-ink); }
    .fp-plate { display:inline-block; background:#f1f5f9; border:1px solid var(--fp-line); border-radius:.3rem; padding:.1rem .5rem; font-size:.8rem; }
    .fp-tag { display:inline-flex; align-items:center; gap:.3rem; font-size:.72rem; font-weight:700; padding:.2rem .65rem; border-radius:999px; }
    .fp-tag-ok   { background:var(--fp-green); color:#fff; }
    .fp-tag-warn { background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
    .fp-tag-info { background:var(--fp-blue); color:#fff; }
    .fp-tag-mute { background:#e2e8f0; color:#475569; }
    .fp-tag-cyan { background:#06b6d4; color:#fff; font-size:.78rem; }
    .fp-dash { color:#94a3b8; font-weight:400; }

    /* Kondisi mobil */
    .fp-checks { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:.7rem; }
    .fp-check { display:flex; align-items:center; gap:.7rem; padding:.7rem .9rem; border-radius:.6rem; font-size:.85rem; font-weight:500; border:1px solid var(--fp-green-line); background:var(--fp-green-soft); color:var(--fp-ink); }
    .fp-check i { width:1.35rem; height:1.35rem; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; background:var(--fp-green); color:#fff; font-size:1.15rem; line-height:1; flex:none; }
    .fp-check.is-bad { border-color:var(--fp-red-line); background:var(--fp-red-soft); }
    .fp-check.is-bad i { background:var(--fp-red); }

    /* Section title */
    .fp-sec { display:flex; justify-content:space-between; align-items:center; margin:0 0 .75rem; flex-wrap:wrap; gap:.5rem; }
    .fp-sec h5 { margin:0; font-weight:700; color:var(--fp-ink); display:flex; align-items:center; gap:.6rem; }
    .fp-sec h5 i.bar { width:.28rem; height:1.1rem; border-radius:.2rem; background:#6366f1; display:inline-block; }
    .fp-sec small { color:var(--fp-muted); }

    /* Item produk */
    .fp-item-head { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap; padding:1.1rem 1.25rem; border-bottom:1px solid var(--fp-line); }
    .fp-item-title { display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; }
    .fp-item-no { background:var(--fp-navy); color:#fff; font-size:.72rem; font-weight:700; padding:.2rem .55rem; border-radius:.3rem; }
    .fp-party { display:flex; gap:1.25rem; flex-wrap:wrap; margin-top:.6rem; font-size:.8rem; color:var(--fp-muted); }
    .fp-party b { color:var(--fp-ink); }
    .fp-party ol { margin:.2rem 0 0; padding-left:1.1rem; color:var(--fp-ink); font-weight:600; }
    .fp-docs { display:flex; align-items:center; gap:.4rem; flex-wrap:wrap; font-size:.78rem; color:var(--fp-muted); }
    .fp-doc { display:inline-flex; align-items:center; gap:.3rem; padding:.3rem .75rem; border-radius:.35rem; font-size:.74rem; font-weight:700; color:#fff; }
    .fp-doc i { font-size:1.05rem; line-height:1; }
    .fp-doc.ok { background:var(--fp-green); } .fp-doc.bad { background:var(--fp-red); }

    /* Detail card */
    .fp-detail { border:1px solid var(--fp-line); border-radius:.7rem; overflow:hidden; background:#fff; }
    .fp-detail + .fp-detail { margin-top:1rem; }
    .fp-detail-head { display:flex; justify-content:space-between; align-items:center; padding:.75rem 1.1rem; background:var(--fp-soft); border-bottom:1px solid var(--fp-line); font-size:.85rem; font-weight:700; color:var(--fp-ink); }
    .fp-detail-body { padding:1.1rem; }
    .fp-kv { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:0 2rem; }
    .fp-kv-row { display:flex; justify-content:space-between; gap:1rem; padding:.6rem 0; border-bottom:1px dashed var(--fp-line); font-size:.84rem; }
    .fp-kv-row span:first-child { color:var(--fp-muted); }
    .fp-kv-row span:last-child { font-weight:600; color:var(--fp-ink); text-align:right; }
    .fp-code { background:#f1f5f9; padding:.1rem .5rem; border-radius:.3rem; font-size:.78rem; }
    .fp-phys { display:flex; gap:.6rem; flex-wrap:wrap; margin-top:1rem; align-items:center; }
    .fp-phys > span:first-child { font-size:.78rem; color:var(--fp-muted); margin-right:.25rem; }
    .fp-phys .fp-pill { font-size:.72rem; }
    .fp-note { margin-top:1rem; padding:.7rem .9rem; border-left:3px solid var(--fp-amber); background:#fffbeb; border-radius:.4rem; font-size:.83rem; }
    .fp-note b { display:block; font-size:.7rem; text-transform:uppercase; letter-spacing:.04em; color:var(--fp-muted); margin-bottom:.2rem; }
    .fp-files { display:flex; gap:.5rem; flex-wrap:wrap; margin-top:1rem; }

    @media (max-width:1199px){ .fp-grid{ grid-template-columns:repeat(2,minmax(0,1fr)); } .fp-checks{ grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media (max-width:767px){
        .fp-grid,.fp-checks,.fp-kv{ grid-template-columns:1fr; }
        .fp-actions{ width:100%; } .fp-actions .fp-btn{ flex:1; justify-content:center; }
    }
    @media print { .fp-actions,.fp-files{ display:none !important; } }

    /* tambahan untuk Chemical / Bahan Baku / Finish Good */
    .fp-check.is-null { border-color:var(--fp-line); background:var(--fp-soft); color:var(--fp-muted); }
    .fp-check.is-null i { background:#94a3b8; }
    .fp-pill-mute { background:#f1f5f9; color:var(--fp-muted); border:1px solid var(--fp-line); }
    .fp-doc.mute { background:#94a3b8; }
    .fp-meta { display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.7rem; }
    .fp-meta-item { border:1px solid var(--fp-line); background:var(--fp-soft); border-radius:.45rem; padding:.3rem .65rem; font-size:.78rem; color:var(--fp-muted); }
    .fp-meta-item b { color:var(--fp-ink); font-weight:600; }
</style>

<div id="main">
    <div class="fp">

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('return-barang.index') }}">Pemeriksaan Return Barang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>

        <div class="fp-head">
            <div>
                <h3>Detail Pemeriksaan Return Barang</h3>
                <p>Detail pemeriksaan dan log verifikasi barang return dari customer.</p>
            </div>
            <div class="fp-actions">
                <a href="{{ route('return-barang.index') }}" class="fp-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <!-- <button type="button" class="fp-btn" onclick="window.print()"><i class="bi bi-printer"></i> Cetak PDF</button> -->
                <a href="{{ route('return-barang.edit', $m->uuid) }}" class="fp-btn fp-btn-warn"><i class="bi bi-pencil-square"></i> Edit Data</a>
            </div>
        </div>

        {{-- Informasi dasar --}}
        <div class="fp-panel">
            <div class="fp-panel-head">
                <h5><i class="bar"></i> Informasi Dasar Kendaraan &amp; Jadwal</h5>
                <!-- <span class="fp-id fp-mono">ID Inspeksi: #{{ strtoupper(substr($m->uuid, 0, 8)) }}</span> -->
            </div>
            <div class="fp-panel-body">
                <div class="fp-grid">
                    <div><div class="fp-field-label">Tanggal pemeriksaan</div>
                        <div class="fp-field-value">{{ \Carbon\Carbon::parse($m->tanggal)->format('d/m/Y') }}</div></div>
                    <div><div class="fp-field-label">Shift</div>
                        <div class="fp-field-value">@if($m->shift)<span class="fp-tag fp-tag-info">{{ $m->shift->shift }}</span>@else<span class="fp-dash">-</span>@endif</div></div>
                    <div><div class="fp-field-label">Ekspedisi</div>
                        <div class="fp-field-value">{!! $show($m->ekspedisi->nama_ekspedisi ?? null) !!}</div></div>
                    <div><div class="fp-field-label">No. polisi kendaraan</div>
                        <div class="fp-field-value">@if($m->no_polisi)<span class="fp-plate fp-mono">{{ $m->no_polisi }}</span>@else<span class="fp-dash">-</span>@endif</div></div>

                    <div><div class="fp-field-label">Nama supir</div><div class="fp-field-value">{!! $show($m->nama_supir) !!}</div></div>
                    <div><div class="fp-field-label">Waktu kedatangan</div>
                        <div class="fp-field-value">@if($m->waktu_kedatangan)<i class="bi bi-clock text-warning"></i> <span class="fp-mono">{{ $m->waktu_kedatangan }}</span>@else<span class="fp-dash">-</span>@endif</div></div>
                    <div><div class="fp-field-label">Suhu mobil</div><div class="fp-field-value">{!! $show($m->suhu_mobil) !!}</div></div>
                </div>
            </div>
        </div>

        {{-- Data produk --}}
        <div class="fp-sec">
            <h5><i class="bar"></i> Data Produk</h5>
            <small>Total {{ $produkCount }} item produk return</small>
        </div>

        @if($produkCount > 0)
            @foreach($produkList as $index => $produk)
                @php
                    $custModel = \App\Models\Customer::find($produk['id_customer'] ?? null);
                    $produkModel = \App\Models\Produk::find($produk['id_produk'] ?? null);
                    $kemasanOk = (bool) ($produk['kondisi_kemasan'] ?? false);
                    $produkOk = (bool) ($produk['kondisi_produk_check'] ?? false);
                    $expired = $produk['expired_date'] ?? null;
                @endphp

                <div class="fp-panel">
                    <div class="fp-item-head">
                        <div>
                            <div class="fp-item-title">
                                <span class="fp-item-no">Produk #{{ $index + 1 }}</span>
                                <span class="fp-tag fp-tag-cyan">{{ $produkModel ? $produkModel->nama_produk : 'Unknown' }}</span>
                            </div>
                            <div class="fp-party">
                                <div><b>Customer:</b> <span style="color:var(--fp-ink);font-weight:600">{{ $custModel ? $custModel->nama_cust : '-' }}</span></div>
                            </div>
                        </div>
                        <div class="fp-docs">
                            <span>Rekomendasi:</span>
                            <span class="fp-tag fp-tag-warn">{{ $produk['rekomendasi'] ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="fp-panel-body">
                        <div class="fp-detail">
                            <div class="fp-detail-head">
                                <span>Detail Return &amp; Inspeksi</span>
                                <span class="fp-tag fp-tag-info">{{ $produk['kondisi_produk'] ?? '-' }}</span>
                            </div>
                            <div class="fp-detail-body">
                                <div class="fp-kv">
                                    <div>
                                        <div class="fp-kv-row"><span>Alasan return</span><span>{{ $produk['alasan_return'] ?? '-' }}</span></div>
                                        <div class="fp-kv-row"><span>Kondisi produk</span><span><span class="fp-tag fp-tag-mute">{{ $produk['kondisi_produk'] ?? '-' }}</span></span></div>
                                        <div class="fp-kv-row"><span>Suhu produk</span><span>{{ $produk['suhu_produk'] ?? '-' }}</span></div>
                                    </div>
                                    <div>
                                        <div class="fp-kv-row"><span>Kode produksi</span><span><span class="fp-code fp-mono">{{ $produk['kode_produksi'] ?? '-' }}</span></span></div>
                                        <div class="fp-kv-row"><span>Expired date</span><span>{{ !empty($expired) ? \Carbon\Carbon::parse($expired)->format('d/m/Y') : '-' }}</span></div>
                                        <div class="fp-kv-row"><span>Jumlah barang</span><span>{{ $produk['jumlah_barang'] ?? '-' }}</span></div>
                                    </div>
                                </div>

                                <div class="fp-phys">
                                    <span>Kondisi &amp; inspeksi:</span>
                                    <span class="fp-pill {{ $kemasanOk ? 'fp-pill-ok' : 'fp-pill-bad' }}">
                                        <i class="bi {{ $kemasanOk ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i> Kondisi Kemasan: {{ $kemasanOk ? 'Ok' : 'Tidak Ok' }}
                                    </span>
                                    <span class="fp-pill {{ $produkOk ? 'fp-pill-ok' : 'fp-pill-bad' }}">
                                        <i class="bi {{ $produkOk ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i> Kondisi Produk: {{ $produkOk ? 'Ok' : 'Tidak Ok' }}
                                    </span>
                                </div>

                                @if(isset($produk['keterangan']) && $produk['keterangan'])
                                    <div class="fp-note"><b>Keterangan</b>{{ $produk['keterangan'] }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="fp-panel"><div class="fp-panel-body text-center text-muted">Tidak ada data produk</div></div>
        @endif

    </div>
</div>
@endsection