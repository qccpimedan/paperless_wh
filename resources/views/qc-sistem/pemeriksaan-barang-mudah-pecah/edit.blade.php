@extends('layouts.app')
@section('container')
<div id="main">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* ============================================================
       Semua style di-scope ke .fp-page, jadi tidak bergantung pada
       Tailwind/Bootstrap dan tidak mengubah tampilan halaman lain.
       ============================================================ */
    .fp-page {
        --brand-50: #eef6ff; --brand-100: #d9ebff; --brand-200: #bcd9fb;
        --brand-500: #1d6cdb; --brand-600: #1456b8; --brand-700: #0f4494;
        --ink: #0f172a; --text: #334155; --muted: #64748b; --faint: #94a3b8;
        --line: #e2e8f0; --line-soft: #f1f5f9; --rose: #e11d48;

        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
        font-size: 13px; color: var(--text);
        max-width: 1120px; margin: 0 auto;
        -webkit-font-smoothing: antialiased;
    }
    .fp-page *, .fp-page *::before, .fp-page *::after { box-sizing: border-box; }
    .fp-page .fp-hide { display: none !important; }

    /* ---------- Header ---------- */
    .fp-crumbs { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; font-size: 12px; color: var(--muted); margin-bottom: 8px; }
    .fp-crumbs a { color: var(--muted); text-decoration: none; }
    .fp-crumbs a:hover { color: var(--brand-600); }
    .fp-crumbs .sep { color: #cbd5e1; }
    .fp-crumbs .now { color: var(--brand-700); background: var(--brand-50); padding: 2px 10px; border-radius: 6px; font-weight: 600; }
    .fp-title { font-size: 26px; line-height: 1.2; font-weight: 700; color: var(--ink); letter-spacing: -.02em; margin: 0; }
    .fp-sub { font-size: 12.5px; color: var(--muted); margin: 4px 0 24px; }

    /* ---------- Alert error ---------- */
    .fp-alert { display: flex; gap: 12px; padding: 14px 16px; margin-bottom: 20px; border: 1px solid #fecdd3; background: #fff1f2; color: #9f1239; border-radius: 12px; }
    .fp-alert svg { flex: none; width: 18px; height: 18px; margin-top: 1px; }
    .fp-alert strong { display: block; margin-bottom: 4px; font-size: 13px; }
    .fp-alert ul { margin: 0; padding-left: 16px; font-size: 12.5px; }

    /* ---------- Card ---------- */
    .fp-card { background: #fff; border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 1px 2px rgba(15,23,42,.04); padding: 24px; margin-bottom: 20px; }
    .fp-card.is-flush { padding: 0; overflow: hidden; }
    .fp-card-head { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; padding-bottom: 16px; margin-bottom: 20px; border-bottom: 1px solid var(--line-soft); }
    .fp-card.is-flush .fp-card-head { padding: 20px 24px; margin: 0; }
    .fp-card-title { display: flex; align-items: center; gap: 12px; }
    .fp-card-title h2 { margin: 0; font-size: 15px; font-weight: 700; color: var(--ink); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .fp-card-title p { margin: 2px 0 0; font-size: 11.5px; color: var(--faint); }
    .fp-ico { flex: none; width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center; }
    .fp-ico svg { width: 18px; height: 18px; }
    .fp-ico.t-brand  { background: #eef6ff; color: #1456b8; }
    .fp-ico.t-amber  { background: #fffbeb; color: #d97706; }
    .fp-count { padding: 2px 10px; border-radius: 999px; background: var(--brand-50); color: var(--brand-700); font-size: 11px; font-weight: 600; }

    /* ---------- Grid & field ---------- */
    .fp-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px 20px; }
    @media (max-width: 991px) { .fp-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 575px) { .fp-grid { grid-template-columns: 1fr; } .fp-card { padding: 18px; } .fp-card.is-flush .fp-card-head { padding: 16px 18px; } }

    .fp-label { display: flex; align-items: center; justify-content: space-between; gap: 4px; margin: 0 0 6px; font-size: 12px; font-weight: 600; color: #334155; }
    .fp-label .fp-req { color: var(--rose); font-weight: 700; }

    .fp-page .fp-input {
        width: 100%; height: 40px; padding: 0 12px;
        font: inherit; font-size: 13px; color: var(--ink);
        background-color: #fff; border: 1px solid var(--line); border-radius: 10px;
        box-shadow: 0 1px 1px rgba(15,23,42,.03);
        transition: border-color .15s, box-shadow .15s, background-color .15s;
    }
    .fp-page select.fp-input {
        -webkit-appearance: none; appearance: none; padding-right: 34px; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6 8l4 4 4-4' fill='none' stroke='%2394a3b8' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 10px center; background-size: 16px;
    }
    .fp-page .fp-input::placeholder { color: #a8b3c4; }
    .fp-page .fp-input:hover { border-color: #cbd5e1; }
    .fp-page .fp-input:focus { outline: 0; border-color: var(--brand-500); box-shadow: 0 0 0 3px rgba(29,108,219,.15); }
    .fp-page .fp-input.is-invalid { border-color: #fb7185; background-image: none; }
    .fp-page select.fp-input.is-invalid { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6 8l4 4 4-4' fill='none' stroke='%2394a3b8' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); }
    .fp-page .fp-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(225,29,72,.15); }
    .fp-error { margin-top: 5px; font-size: 11.5px; color: var(--rose); }

    /* input kecil di dalam tabel */
    .fp-page .fp-input.fp-sm { height: 34px; padding: 0 10px; font-size: 12px; border-radius: 8px; box-shadow: none; }
    .fp-page select.fp-input.fp-sm { padding-right: 30px; background-position: right 8px center; background-size: 14px; }
    .fp-page textarea.fp-input.fp-sm { height: 34px; min-height: 34px; padding: 7px 10px; line-height: 1.4; resize: vertical; }
    .fp-page .fp-input.fp-num { width: 72px; text-align: center; font-weight: 600; padding: 0 6px; }
    .fp-page .fp-input.fp-name { font-weight: 600; border-color: #fcd34d; }
    .fp-page .fp-input.fp-custom { border-color: #fcd34d; }

    /* ---------- Tabel ---------- */
    .fp-table-wrap { overflow-x: auto; }
    .fp-table { width: 100%; min-width: 980px; border-collapse: separate; border-spacing: 0; font-size: 12.5px; }
    .fp-table th { padding: 11px 14px; text-align: left; white-space: nowrap; font-size: 10.5px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--muted); background: #f8fafc; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
    .fp-table td { padding: 11px 14px; vertical-align: middle; color: var(--text); border-bottom: 1px solid var(--line-soft); }
    .fp-table .t-center { text-align: center; }
    .fp-table tbody tr.barang-row:hover td { background: #f8fafc; }
    .fp-table tbody tr.barang-row.is-custom td { background: #fffbeb; }
    .fp-table tbody tr.barang-row:has(input[value="tidak-baik"]:checked) td { background: #fff7f8; }
    .fp-table .fp-idx { color: var(--faint); font-weight: 600; font-variant-numeric: tabular-nums; }
    .fp-table .fp-idx svg { width: 15px; height: 15px; color: #d97706; vertical-align: middle; }
    .fp-group-row td { padding: 9px 16px; background: #f1f5f9; font-size: 12px; font-weight: 700; color: var(--ink); border-bottom: 1px solid var(--line); }
    .fp-group-row svg { width: 13px; height: 13px; margin-right: 6px; color: var(--brand-600); vertical-align: -1px; }
    .fp-item { display: flex; align-items: center; gap: 10px; }
    .fp-item-ico { flex: none; width: 28px; height: 28px; border-radius: 8px; display: grid; place-items: center; background: var(--brand-50); color: var(--brand-600); }
    .fp-item-ico svg { width: 14px; height: 14px; }
    .fp-item-name { font-weight: 600; color: var(--ink); }
    .fp-empty-row td { padding: 28px 16px; text-align: center; color: var(--muted); }
    .fp-empty-row svg { width: 15px; height: 15px; margin-right: 6px; color: #d97706; vertical-align: -3px; }
    @keyframes fp-spin { to { transform: rotate(360deg); } }
    .fp-spinner { display: inline-block; width: 16px; height: 16px; margin-right: 8px; border: 2px solid var(--brand-100); border-top-color: var(--brand-600); border-radius: 50%; animation: fp-spin .8s linear infinite; vertical-align: -3px; }

    /* Toggle OK / NG (radio, nilai tetap "baik" / "tidak-baik") */
    .fp-seg { display: inline-flex; padding: 2px; border-radius: 9px; background: var(--line-soft); border: 1px solid var(--line); }
    .fp-seg label { position: relative; margin: 0; cursor: pointer; }
    .fp-seg input { position: absolute; inset: 0; width: 100%; height: 100%; margin: 0; opacity: 0; cursor: pointer; }
    .fp-seg span { display: inline-flex; align-items: center; gap: 4px; padding: 5px 11px; border-radius: 7px; font-size: 11px; font-weight: 500; color: var(--muted); transition: background .15s, color .15s; }
    .fp-seg span svg { display: none; width: 12px; height: 12px; }
    .fp-seg label:hover span { color: var(--ink); }
    .fp-seg label:has(input:checked) span { color: #fff; font-weight: 700; box-shadow: 0 1px 2px rgba(15,23,42,.18); }
    .fp-seg label:has(input:checked) span svg { display: block; }
    .fp-seg .opt-ok:has(input:checked) span { background: #059669; }
    .fp-seg .opt-ng:has(input:checked) span { background: #e11d48; }
    .fp-seg label:has(input:focus-visible) span { outline: 2px solid var(--brand-500); outline-offset: 1px; }

    .fp-icon-btn { width: 32px; height: 32px; display: inline-grid; place-items: center; border: 0; border-radius: 8px; background: transparent; color: #f43f5e; cursor: pointer; transition: all .15s; }
    .fp-icon-btn:hover { background: #fff1f2; color: #be123c; }
    .fp-icon-btn svg { width: 16px; height: 16px; }

    /* Ringkasan di bawah tabel */
    .fp-metrics { display: flex; align-items: center; flex-wrap: wrap; gap: 10px 28px; padding: 14px 24px; background: rgba(248,250,252,.8); border-top: 1px solid var(--line); font-size: 12px; color: var(--muted); }
    .fp-metric { display: flex; align-items: center; gap: 8px; }
    .fp-metric i { width: 9px; height: 9px; border-radius: 50%; background: #10b981; }
    .fp-metric i.is-blue { background: #3b82f6; }
    .fp-metric b { color: var(--ink); font-weight: 600; }
    .fp-metric b.is-ok { color: #047857; }
    .fp-metric b.is-ng { color: #be123c; }

    /* Tombol */
    .fp-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 38px; padding: 0 18px; font: inherit; font-size: 12.5px; font-weight: 600; text-decoration: none; border: 1px solid transparent; border-radius: 10px; cursor: pointer; transition: all .15s; }
    .fp-btn svg { width: 16px; height: 16px; }
    .fp-btn-ghost { color: var(--muted); background: transparent; }
    .fp-btn-ghost:hover { color: var(--ink); background: var(--line-soft); }
    .fp-btn-primary { color: #fff; background: var(--brand-600); box-shadow: 0 2px 8px rgba(20,86,184,.3); }
    .fp-btn-primary:hover { background: var(--brand-700); color: #fff; }
    .fp-btn-primary:active { transform: translateY(1px); }
    .fp-btn-soft { color: var(--brand-700); background: var(--brand-50); border-color: var(--brand-200); }
    .fp-btn-soft:hover { background: var(--brand-100); }
    .fp-btn:focus-visible { outline: 2px solid var(--brand-500); outline-offset: 2px; }

    /* ---------- Bar aksi bawah ---------- */
    .fp-bar { position: sticky; bottom: 12px; z-index: 20; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding: 12px 16px; margin-top: 4px; background: rgba(255,255,255,.92); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 -2px 6px rgba(15,23,42,.03), 0 10px 28px rgba(15,23,42,.1); }
    .fp-bar-status { display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--muted); }
    .fp-bar-status b { color: var(--text); font-weight: 600; }
    .fp-pulse { position: relative; flex: none; width: 10px; height: 10px; border-radius: 50%; background: #cbd5e1; }
    .fp-pulse.is-on { background: #10b981; }
    .fp-pulse.is-warn { background: #f59e0b; }
    .fp-pulse.is-on::after, .fp-pulse.is-warn::after { content: ''; position: absolute; inset: 0; border-radius: 50%; background: inherit; opacity: .6; animation: fp-ping 1.6s cubic-bezier(0,0,.2,1) infinite; }
    @keyframes fp-ping { 75%, 100% { transform: scale(2.4); opacity: 0; } }
    .fp-bar-actions { display: flex; align-items: center; gap: 10px; }

    @media (prefers-reduced-motion: reduce) {
        .fp-spinner, .fp-pulse.is-on::after, .fp-pulse.is-warn::after { animation: none; }
        .fp-page * { transition: none !important; }
    }
</style>

@php
    $icoCheck = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>';
    $icoX = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>';
    $icoTrash = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
    $icoEdit = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
    $icoBox = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>';
    $icoInfo = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4m0-4h.01"/></svg>';
@endphp

<div class="fp-page">
    <header>
        <nav class="fp-crumbs" aria-label="breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
            <a href="{{ route('pemeriksaan-barang-mudah-pecah.index') }}">Pemeriksaan Barang Mudah Pecah</a><span class="sep">/</span>
            <span class="now" aria-current="page">Edit</span>
        </nav>
        <h1 class="fp-title">Edit Pemeriksaan Barang Mudah Pecah</h1>
        <p class="fp-sub">Perbarui data pemeriksaan barang mudah pecah &amp; tajam.</p>
    </header>

    @if ($errors->any())
        <div class="fp-alert" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4m0 4h.01"/></svg>
            <div>
                <strong>Data belum bisa disimpan. Periksa kembali isian berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('pemeriksaan-barang-mudah-pecah.update', $pemeriksaanBarangMudahPecah->uuid) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        {{-- ============ 1. INFORMASI UMUM ============ --}}
        <section class="fp-card">
            <div class="fp-card-head">
                <div class="fp-card-title">
                    <span class="fp-ico t-brand"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4m0-4h.01"/></svg></span>
                    <div>
                        <h2>1. Informasi Umum &amp; Parameter Lokasi</h2>
                        <p>Mengubah area akan mereset tabel detail pemeriksaan di bawah.</p>
                    </div>
                </div>
            </div>

            <div class="fp-grid">
                <div>
                    <label class="fp-label" for="tanggal"><span>Tanggal Pemeriksaan <span class="fp-req">*</span></span></label>
                    <input type="date" class="form-control fp-input @error('tanggal') is-invalid @enderror" name="tanggal" id="tanggal" value="{{ $pemeriksaanBarangMudahPecah->tanggal }}" required>
                    @error('tanggal')
                        <div class="fp-error">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="fp-label" for="id_shift"><span>Shift Kerja <span class="fp-req">*</span></span></label>
                    <select class="form-select fp-input @error('id_shift') is-invalid @enderror" name="id_shift" id="id_shift" required>
                        <option value="">-- Pilih Shift --</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ $pemeriksaanBarangMudahPecah->id_shift == $shift->id ? 'selected' : '' }}>
                                {{ $shift->shift }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_shift')
                        <div class="fp-error">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="fp-label" for="id_area"><span>Area Inspeksi <span class="fp-req">*</span></span></label>
                    <select class="form-select fp-input @error('id_area') is-invalid @enderror" name="id_area" id="id_area" required>
                        <option value="">-- Pilih Area --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ $pemeriksaanBarangMudahPecah->id_area == $area->id ? 'selected' : '' }}>
                                {{ $area->nama_area }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_area')
                        <div class="fp-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </section>

        {{-- ============ 2. DETAIL BARANG ============ --}}
        <section class="fp-card is-flush">
            <div class="fp-card-head">
                <div class="fp-card-title">
                    <span class="fp-ico t-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></span>
                    <div>
                        <h2>2. Detail Benda Mudah Pecah &amp; Tajam <span class="fp-count" id="fpItemCount">{{ $pemeriksaanBarangMudahPecah->details->count() }} Item Terdaftar</span></h2>
                        <p>Periksa kondisi fisik, jumlah, dan integritas sebelum dan sesudah kegiatan operasional.</p>
                    </div>
                </div>
                <button type="button" class="fp-btn fp-btn-soft" id="addCustomRow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v16m8-8H4"/></svg>
                    Tambah Baris Kustom
                </button>
            </div>

            <div class="fp-table-wrap">
                <table class="fp-table" id="barangTable">
                    <thead>
                        <tr>
                            <th class="t-center" style="width: 5%">#</th>
                            <th style="width: 25%">Nama Barang <span style="color:var(--rose)">*</span></th>
                            <th style="width: 20%">Sub Area</th>
                            <th class="t-center" style="width: 8%">Jumlah</th>
                            <th class="t-center" style="width: 12%">Verifikasi Pra-Op</th>
                            <th class="t-center" style="width: 12%">Verifikasi Post-Op</th>
                            <th style="width: 15%">Catatan</th>
                            <th class="t-center" style="width: 3%">Hapus</th>
                        </tr>
                    </thead>
                    <tbody id="barangTableBody">
                        @php
                            $currentAreaLocations = \App\Models\InputAreaLocation::where('id_input_area', $pemeriksaanBarangMudahPecah->id_area)->get();
                        @endphp
                        @forelse($pemeriksaanBarangMudahPecah->details as $index => $detail)
                            @php
                                $isManual = !empty($detail->nama_barang_manual);
                            @endphp
                            <tr class="barang-row {{ $isManual ? 'is-custom' : '' }}" data-index="{{ $index }}">
                                <td class="t-center fp-idx">
                                    @if($isManual)
                                        <span title="Baris Kustom">{!! $icoEdit !!}</span>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </td>
                                <td>
                                    @if($isManual)
                                        <input type="text" class="form-control fp-input fp-sm fp-name" name="details[{{ $index }}][nama_barang_manual]" value="{{ $detail->nama_barang_manual }}" placeholder="Nama barang kustom *" required>
                                    @else
                                        <div class="fp-item">
                                            <span class="fp-item-ico">{!! $icoBox !!}</span>
                                            <span class="fp-item-name">{{ $detail->barang->nama_barang ?? 'Barang tidak ditemukan' }}</span>
                                        </div>
                                        <input type="hidden" name="details[{{ $index }}][id_barang]" value="{{ $detail->id_barang }}">
                                    @endif
                                </td>
                                <td>
                                    <select class="form-select form-select-sm fp-input fp-sm {{ $isManual ? 'fp-custom' : '' }}" name="details[{{ $index }}][id_input_area_locations]">
                                        <option value="">-- Pilih Sub Area --</option>
                                        @foreach($currentAreaLocations as $loc)
                                            <option value="{{ $loc->id }}" {{ $detail->id_input_area_locations == $loc->id ? 'selected' : '' }}>
                                                {{ $loc->lokasi_area }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="t-center">
                                    @if($isManual)
                                        <input type="number" class="form-control fp-input fp-sm fp-num" name="details[{{ $index }}][jumlah_manual]" value="{{ $detail->jumlah_barang }}" required min="1">
                                    @else
                                        <input type="number" class="form-control fp-input fp-sm fp-num" name="details[{{ $index }}][jumlah_barang]" value="{{ $detail->jumlah_barang }}" required min="0">
                                    @endif
                                </td>
                                <td class="t-center">
                                    <div class="fp-seg" role="radiogroup">
                                        <label class="opt-ok"><input type="radio" name="details[{{ $index }}][awal]" value="baik" {{ $detail->awal === 'tidak-baik' ? '' : 'checked' }}><span>{!! $icoCheck !!} OK</span></label>
                                        <label class="opt-ng"><input type="radio" name="details[{{ $index }}][awal]" value="tidak-baik" {{ $detail->awal === 'tidak-baik' ? 'checked' : '' }}><span>{!! $icoX !!} NG</span></label>
                                    </div>
                                </td>
                                <td class="t-center">
                                    <div class="fp-seg" role="radiogroup">
                                        <label class="opt-ok"><input type="radio" name="details[{{ $index }}][akhir]" value="baik" {{ $detail->akhir === 'tidak-baik' ? '' : 'checked' }}><span>{!! $icoCheck !!} OK</span></label>
                                        <label class="opt-ng"><input type="radio" name="details[{{ $index }}][akhir]" value="tidak-baik" {{ $detail->akhir === 'tidak-baik' ? 'checked' : '' }}><span>{!! $icoX !!} NG</span></label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="form-control fp-input fp-sm" name="details[{{ $index }}][temuan_ketidaksesuaian]" rows="1" placeholder="catatan...">{{ $detail->temuan_ketidaksesuaian }}</textarea>
                                </td>
                                <td class="t-center">
                                    <button type="button" class="fp-icon-btn remove-row-btn" title="Hapus Baris">{!! $icoTrash !!}</button>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyTableAlert" class="fp-empty-row">
                                <td colspan="8">{!! $icoInfo !!} Tidak ada baris terdaftar. Anda dapat menambahkan baris kustom secara manual.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="fp-metrics fp-hide" id="fpMetrics">
                <div class="fp-metric"><i></i><span>Total Unit Diaudit: <b id="fpMetricTotal">0 Unit</b></span></div>
                <div class="fp-metric"><i class="is-blue"></i><span>Status Pra-Op: <b id="fpMetricPra">-</b></span></div>
                <div class="fp-metric"><i></i><span>Status Post-Op: <b id="fpMetricPost">-</b></span></div>
            </div>
        </section>

        {{-- ============ BAR AKSI ============ --}}
        <div class="fp-bar">
            <div class="fp-bar-status" aria-live="polite">
                <span class="fp-pulse" id="fpPulse"></span>
                <span id="fpStatusText">Belum ada item.</span>
            </div>
            <div class="fp-bar-actions">
                <a href="{{ route('pemeriksaan-barang-mudah-pecah.index') }}" class="fp-btn fp-btn-ghost btn-kembali-confirm">Kembali</a>
                <button type="submit" class="fp-btn fp-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                    Update Pemeriksaan
                </button>
            </div>
        </div>
    </form>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirm back navigation
    document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
        el.addEventListener('click', function(e) {
            const ok = confirm('Perubahan belum disimpan. Yakin ingin kembali?');
            if (!ok) e.preventDefault();
        });
    });

    const idAreaSelect = document.getElementById('id_area');
    const tableEl = document.getElementById('barangTable');
    const tableBody = document.getElementById('barangTableBody');
    const addCustomRowBtn = document.getElementById('addCustomRow');

    const allBarangs = @json($barangs);
    // Pre-populate cachedSubAreas with initial locations from backend
    let cachedSubAreas = @json($currentAreaLocations);
    let customRowIndex = 1000; // start custom rows with high index to avoid conflicts
    let currentAreaId = idAreaSelect ? idAreaSelect.value : '';

    /* ---------- Potongan markup (khusus tampilan) ---------- */
    const ICON_CHECK = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>';
    const ICON_X = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>';
    const ICON_TRASH = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
    const ICON_EDIT = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
    const ICON_BOX = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>';
    const ICON_TAG = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.707 9.293l-5-5A1 1 0 0012 4H5a1 1 0 00-1 1v7a1 1 0 00.293.707l5 5a1 1 0 001.414 0l7-7a1 1 0 000-1.414zM7.5 9A1.5 1.5 0 119 7.5 1.5 1.5 0 017.5 9z"/></svg>';
    const ICON_INFO = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4m0-4h.01"/></svg>';

    // Toggle OK / NG. Tetap memakai name & value yang sama seperti select sebelumnya.
    function segHtml(idx, field) {
        return `
            <div class="fp-seg" role="radiogroup">
                <label class="opt-ok"><input type="radio" name="details[${idx}][${field}]" value="baik" checked><span>${ICON_CHECK} OK</span></label>
                <label class="opt-ng"><input type="radio" name="details[${idx}][${field}]" value="tidak-baik"><span>${ICON_X} NG</span></label>
            </div>
        `;
    }

    /* ---------- Ringkasan (hanya tampilan) ---------- */
    const itemCountEl = document.getElementById('fpItemCount');
    const metricsEl = document.getElementById('fpMetrics');
    const metricTotalEl = document.getElementById('fpMetricTotal');
    const metricPraEl = document.getElementById('fpMetricPra');
    const metricPostEl = document.getElementById('fpMetricPost');
    const pulseEl = document.getElementById('fpPulse');
    const statusTextEl = document.getElementById('fpStatusText');

    function setMetric(el, ok, total) {
        const ng = total - ok;
        el.textContent = ng === 0 ? `${ok} / ${total} OK (Semua OK)` : `${ok} / ${total} OK (${ng} NG)`;
        el.classList.toggle('is-ok', ng === 0);
        el.classList.toggle('is-ng', ng > 0);
    }

    function updateSummary() {
        const rows = [...tableBody.querySelectorAll('.barang-row')];
        const n = rows.length;

        itemCountEl.textContent = `${n} Item Terdaftar`;
        metricsEl.classList.toggle('fp-hide', n === 0);

        if (n === 0) {
            pulseEl.classList.remove('is-on', 'is-warn');
            statusTextEl.textContent = 'Belum ada item.';
            return;
        }

        const totalUnit = rows.reduce((sum, r) => {
            const num = r.querySelector('input[type="number"]');
            return sum + (parseInt(num ? num.value : 0, 10) || 0);
        }, 0);
        const praOk = rows.filter(r => { const c = r.querySelector('input[name$="[awal]"]:checked'); return c && c.value === 'baik'; }).length;
        const postOk = rows.filter(r => { const c = r.querySelector('input[name$="[akhir]"]:checked'); return c && c.value === 'baik'; }).length;
        const ngCount = (n - praOk) + (n - postOk);

        metricTotalEl.textContent = `${totalUnit} Unit`;
        setMetric(metricPraEl, praOk, n);
        setMetric(metricPostEl, postOk, n);

        pulseEl.classList.toggle('is-on', ngCount === 0);
        pulseEl.classList.toggle('is-warn', ngCount > 0);
        statusTextEl.innerHTML = ngCount === 0
            ? `Semua item OK <b>(${n} baris)</b>`
            : `Terdapat <b>${ngCount} temuan NG</b> pada <b>${n} baris</b>`;
    }

    tableBody.addEventListener('change', updateSummary);
    tableBody.addEventListener('input', updateSummary);

    // Function to render table (triggered only when area is changed by user)
    function renderBarangTable(areaId, areaName, subAreas) {
        tableBody.innerHTML = '';

        // Filter barangs by selected id_area (exact database match)
        const filteredBarangs = allBarangs.filter(barang => barang.id_area == areaId);

        if (filteredBarangs.length === 0) {
            tableBody.innerHTML = `
                <tr id="emptyTableAlert" class="fp-empty-row">
                    <td colspan="8">
                        ${ICON_INFO} Tidak ada barang terdaftar untuk area "${areaName}". Anda dapat menambahkan baris kustom secara manual.
                    </td>
                </tr>
            `;
            updateSummary();
            return;
        }

        // Group barangs by Sub Area
        const grouped = { 'null': [] };
        subAreas.forEach(loc => {
            grouped[loc.id] = [];
        });

        filteredBarangs.forEach(barang => {
            let matchedLocId = 'null';
            for (const loc of subAreas) {
                if (barang.nama_barang.toLowerCase().includes(loc.lokasi_area.toLowerCase())) {
                    matchedLocId = loc.id;
                    break;
                }
            }
            grouped[matchedLocId].push(barang);
        });

        // Render grouped table rows
        let rowIndex = 0;
        for (const locId in grouped) {
            const items = grouped[locId];
            if (items.length === 0) continue;

            const locName = locId === 'null' ? 'Tanpa Sub Area' : subAreas.find(s => s.id == locId).lokasi_area;

            // Sub-area header row
            if (locId !== 'null') {
                const groupHeader = document.createElement('tr');
                groupHeader.className = 'fp-group-row';
                groupHeader.innerHTML = `<td colspan="8">${ICON_TAG}${locName}</td>`;
                tableBody.appendChild(groupHeader);
            }

            // Item rows
            items.forEach(barang => {
                const idx = rowIndex++;

                // Generate sub area options
                let subAreaOptions = `<option value="">-- Pilih Sub Area --</option>`;
                subAreas.forEach(loc => {
                    const isSelected = loc.id == locId ? 'selected' : '';
                    subAreaOptions += `<option value="${loc.id}" ${isSelected}>${loc.lokasi_area}</option>`;
                });

                const row = document.createElement('tr');
                row.className = 'barang-row';
                row.setAttribute('data-index', idx);
                row.innerHTML = `
                    <td class="t-center fp-idx">${idx + 1}</td>
                    <td>
                        <div class="fp-item">
                            <span class="fp-item-ico">${ICON_BOX}</span>
                            <span class="fp-item-name">${barang.nama_barang}</span>
                        </div>
                        <input type="hidden" name="details[${idx}][id_barang]" value="${barang.id}">
                    </td>
                    <td>
                        <select class="form-select form-select-sm fp-input fp-sm" name="details[${idx}][id_input_area_locations]">
                            ${subAreaOptions}
                        </select>
                    </td>
                    <td class="t-center">
                        <input type="number" class="form-control fp-input fp-sm fp-num" name="details[${idx}][jumlah_barang]" value="${barang.jumlah_barang || 0}" required min="0">
                    </td>
                    <td class="t-center">${segHtml(idx, 'awal')}</td>
                    <td class="t-center">${segHtml(idx, 'akhir')}</td>
                    <td>
                        <textarea class="form-control fp-input fp-sm" name="details[${idx}][temuan_ketidaksesuaian]" rows="1" placeholder="catatan..."></textarea>
                    </td>
                    <td class="t-center">
                        <button type="button" class="fp-icon-btn remove-row-btn" title="Hapus Baris">${ICON_TRASH}</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
        updateSummary();
    }

    // Handle Area select change
    if (idAreaSelect) {
        idAreaSelect.addEventListener('change', function() {
            if (tableBody.querySelectorAll('.barang-row').length > 0) {
                const ok = confirm('Mengubah Area akan mereset tabel detail pemeriksaan. Yakin ingin melanjutkan?');
                if (!ok) {
                    idAreaSelect.value = currentAreaId;
                    return;
                }
            }

            currentAreaId = this.value;
            const areaId = this.value;
            const areaName = this.options[this.selectedIndex].text.trim();

            if (!areaId) {
                tableBody.innerHTML = `
                    <tr id="emptyTableAlert" class="fp-empty-row">
                        <td colspan="8">
                            ${ICON_INFO} Silakan pilih Area terlebih dahulu.
                        </td>
                    </tr>
                `;
                cachedSubAreas = [];
                updateSummary();
                return;
            }

            // Show loading placeholder
            tableBody.innerHTML = `
                <tr class="fp-empty-row">
                    <td colspan="8">
                        <span class="fp-spinner" role="status"></span>
                        Memuat data sub-area...
                    </td>
                </tr>
            `;
            updateSummary();

            // Fetch sub-areas
            fetch(`{{ url('/qc-sistem/api/area-locations') }}/${areaId}`)
                .then(r => r.ok ? r.json() : Promise.reject(`HTTP ${r.status}`))
                .then(data => {
                    cachedSubAreas = data;
                    renderBarangTable(areaId, areaName, data);
                })
                .catch(err => {
                    console.error('Error loading locations:', err);
                    cachedSubAreas = [];
                    renderBarangTable(areaId, areaName, []);
                });
        });
    }

    // Add custom manually input row
    if (addCustomRowBtn) {
        addCustomRowBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove empty table row if it exists
            const emptyAlert = document.getElementById('emptyTableAlert');
            if (emptyAlert) emptyAlert.remove();

            const idx = customRowIndex++;
            const row = document.createElement('tr');
            row.className = 'barang-row is-custom';
            row.setAttribute('data-index', idx);

            // Generate sub area options
            let subAreaOptions = `<option value="">-- Pilih Sub Area --</option>`;
            cachedSubAreas.forEach(loc => {
                subAreaOptions += `<option value="${loc.id}">${loc.lokasi_area}</option>`;
            });

            row.innerHTML = `
                <td class="t-center fp-idx" title="Baris Kustom">${ICON_EDIT}</td>
                <td>
                    <input type="text" class="form-control fp-input fp-sm fp-name" name="details[${idx}][nama_barang_manual]" placeholder="Nama barang kustom *" required>
                </td>
                <td>
                    <select class="form-select fp-input fp-sm fp-custom" name="details[${idx}][id_input_area_locations]">
                        ${subAreaOptions}
                    </select>
                </td>
                <td class="t-center">
                    <input type="number" class="form-control fp-input fp-sm fp-num" name="details[${idx}][jumlah_manual]" value="1" required min="1">
                </td>
                <td class="t-center">${segHtml(idx, 'awal')}</td>
                <td class="t-center">${segHtml(idx, 'akhir')}</td>
                <td>
                    <textarea class="form-control fp-input fp-sm" name="details[${idx}][temuan_ketidaksesuaian]" rows="1" placeholder="catatan..."></textarea>
                </td>
                <td class="t-center">
                    <button type="button" class="fp-icon-btn remove-row-btn" title="Hapus Baris">${ICON_TRASH}</button>
                </td>
            `;
            tableBody.appendChild(row);
            updateSummary();
        });
    }

    // Remove row event delegator
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row-btn')) {
            e.preventDefault();
            const row = e.target.closest('tr');
            if (row) {
                row.remove();

                // If table is now empty, show empty table message
                const remainingRows = tableBody.querySelectorAll('.barang-row');
                if (remainingRows.length === 0) {
                    tableBody.innerHTML = `
                        <tr id="emptyTableAlert" class="fp-empty-row">
                            <td colspan="8">
                                ${ICON_INFO} Tidak ada baris terdaftar. Anda dapat menambahkan baris kustom secara manual.
                            </td>
                        </tr>
                    `;
                }
                updateSummary();
            }
        }
    });

    // Hitung ringkasan awal dari baris yang sudah ada
    updateSummary();
});
</script>
@endsection