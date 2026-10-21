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

    .fp-page .is-brand { --accent: #1456b8; --accent-ink: #0f4494; --accent-line: #bcd9fb; --unit-bg: #eff6ff; --unit-line: #dbeafe; }
    .fp-page .is-teal  { --accent: #0d9488; --accent-ink: #0f766e; --accent-line: #99f6e4; --unit-bg: #f0fdfa; --unit-line: #ccfbf1; }

    /* ---------- Header ---------- */
    .fp-crumbs { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; font-size: 12px; color: var(--muted); margin-bottom: 8px; }
    .fp-crumbs a { color: var(--muted); text-decoration: none; }
    .fp-crumbs a:hover { color: var(--brand-600); }
    .fp-crumbs .sep { color: #cbd5e1; }
    .fp-crumbs .now { color: var(--brand-700); background: var(--brand-50); padding: 2px 10px; border-radius: 6px; font-weight: 600; }
    .fp-title { font-size: 26px; line-height: 1.2; font-weight: 700; color: var(--ink); letter-spacing: -.02em; margin: 0; }
    .fp-mode { display: inline-block; margin-left: 8px; padding: 3px 11px; border-radius: 999px; background: var(--brand-50); color: var(--brand-600); border: 1px solid var(--brand-100); font-size: 11px; font-weight: 600; letter-spacing: 0; vertical-align: middle; }
    .fp-sub { font-size: 12.5px; color: var(--muted); margin: 4px 0 24px; }

    /* ---------- Alert ---------- */
    .fp-alert { display: flex; gap: 12px; padding: 14px 16px; margin-bottom: 20px; border: 1px solid #fecdd3; background: #fff1f2; color: #9f1239; border-radius: 12px; }
    .fp-alert.is-warn { border-color: #fde68a; background: #fffbeb; color: #92400e; }
    .fp-alert.is-info { border-color: #bfdbfe; background: #eff6ff; color: #1e40af; }
    .fp-alert svg { flex: none; width: 18px; height: 18px; margin-top: 1px; }
    .fp-alert strong { display: block; margin-bottom: 4px; font-size: 13px; }
    .fp-alert p { margin: 0; font-size: 12.5px; line-height: 1.55; }
    .fp-alert p strong { display: inline; margin: 0; }
    .fp-alert ul { margin: 0; padding-left: 16px; font-size: 12.5px; }

    /* ---------- Card ---------- */
    .fp-card { background: #fff; border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 1px 2px rgba(15,23,42,.04); padding: 24px; margin-bottom: 20px; }
    .fp-card-head { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; padding-bottom: 16px; margin-bottom: 20px; border-bottom: 1px solid var(--line-soft); }
    .fp-card-title { display: flex; align-items: center; gap: 12px; }
    .fp-card-title h2 { margin: 0; font-size: 15px; font-weight: 700; color: var(--ink); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .fp-card-title p { margin: 2px 0 0; font-size: 11.5px; color: var(--faint); }
    .fp-ico { flex: none; width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center; }
    .fp-ico svg { width: 18px; height: 18px; }
    .fp-ico.t-brand  { background: #eef6ff; color: #1456b8; }
    .fp-ico.t-sky    { background: #f0f9ff; color: #0284c7; }
    .fp-ico.t-teal   { background: #f0fdfa; color: #0d9488; }
    .fp-ico.t-violet { background: #f5f3ff; color: #7c3aed; }
    .fp-ico.t-amber  { background: #fffbeb; color: #d97706; }
    .fp-ico.t-slate  { background: #f1f5f9; color: #475569; }

    /* ---------- Grid & field ---------- */
    .fp-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px 20px; }
    .fp-grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 20px; }
    .fp-triple { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    @media (max-width: 991px) { .fp-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 767px) { .fp-triple, .fp-grid-2 { grid-template-columns: 1fr; } }
    @media (max-width: 575px) { .fp-grid { grid-template-columns: 1fr; } .fp-card { padding: 18px; } }

    .fp-label { display: flex; align-items: center; justify-content: space-between; gap: 4px; margin: 0 0 6px; font-size: 12px; font-weight: 600; color: #334155; }
    .fp-label .fp-req { color: var(--rose); font-weight: 700; }
    .fp-label .fp-hint { display: inline-flex; align-items: center; gap: 4px; font-weight: 400; font-size: 10.5px; color: var(--faint); }
    .fp-label .fp-hint svg { width: 11px; height: 11px; }
    .fp-sublabel { display: block; margin: 0 0 6px; font-size: 12px; font-weight: 500; color: #475569; }

    .fp-page .fp-input {
        width: 100%; height: 40px; padding: 0 12px;
        font: inherit; font-size: 13px; color: var(--ink);
        background-color: #fff; border: 1px solid var(--line); border-radius: 10px;
        box-shadow: 0 1px 1px rgba(15,23,42,.03);
        transition: border-color .15s, box-shadow .15s, background-color .15s;
    }
    .fp-page textarea.fp-input { height: auto; padding: 10px 12px; line-height: 1.5; resize: vertical; }
    .fp-page select.fp-input {
        -webkit-appearance: none; appearance: none; padding-right: 34px; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6 8l4 4 4-4' fill='none' stroke='%2394a3b8' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 10px center; background-size: 16px;
    }
    .fp-page .fp-input::placeholder { color: #a8b3c4; }
    .fp-page .fp-input:hover { border-color: #cbd5e1; }
    .fp-page .fp-input:focus { outline: 0; border-color: var(--brand-500); box-shadow: 0 0 0 3px rgba(29,108,219,.15); }
    .fp-page .fp-input:disabled { background-color: #f8fafc; color: var(--muted); border-style: dashed; cursor: not-allowed; box-shadow: none; opacity: 1; -webkit-text-fill-color: var(--muted); }
    .fp-page .fp-input.is-invalid { border-color: #fb7185; background-image: none; }
    .fp-page .fp-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(225,29,72,.15); }
    .fp-error { margin-top: 5px; font-size: 11.5px; color: var(--rose); }
    .fp-manual { margin-top: 8px; }

    /* ---------- Chip unit ---------- */
    .fp-chips { display: inline-flex; flex-wrap: wrap; gap: 4px; padding: 4px; background: #f8fafc; border: 1px solid var(--line); border-radius: 12px; }
    .fp-chip { display: inline-flex; align-items: center; gap: 8px; margin: 0; padding: 7px 12px; border: 1px solid transparent; border-radius: 9px; font-size: 12px; font-weight: 500; color: var(--muted); cursor: pointer; user-select: none; transition: all .15s; }
    .fp-chip:hover { color: var(--text); background: rgba(255,255,255,.7); }
    .fp-chip:has(input:checked) { background: #fff; color: var(--accent-ink); border-color: var(--accent-line); font-weight: 600; box-shadow: 0 1px 2px rgba(15,23,42,.07); }
    .fp-chip:has(input:focus-visible) { outline: 2px solid var(--accent); outline-offset: 1px; }
    .fp-chip input {
        -webkit-appearance: none; appearance: none; flex: none; width: 16px; height: 16px; margin: 0;
        border: 1.5px solid #cbd5e1; border-radius: 5px; background: #fff center / 12px no-repeat; transition: all .15s;
    }
    .fp-chip input:checked {
        background-color: var(--accent); border-color: var(--accent);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none' stroke='white' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3.5 8.5l3 3 6-6.5'/%3E%3C/svg%3E");
    }

    /* ---------- Kartu unit ---------- */
    .fp-empty { margin: 0; padding: 22px 16px; text-align: center; font-size: 12.5px; color: var(--faint); border: 1.5px dashed var(--line); border-radius: 12px; background: #fafbfc; }
    @keyframes fp-in { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: none; } }
    .fp-unit { padding: 16px; margin-bottom: 14px; border: 1px solid var(--unit-line); border-radius: 12px; background: linear-gradient(90deg, var(--unit-bg), #fff 55%); animation: fp-in .22s ease-out; transition: border-color .15s; }
    .fp-unit:last-child { margin-bottom: 0; }
    .fp-unit:hover { border-color: var(--accent-line); }
    .fp-unit-head { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
    .fp-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 0 3px var(--unit-line); }
    .fp-unit-name { font-size: 13px; font-weight: 700; color: var(--ink); }
    .fp-badge { display: inline-block; padding: 2px 9px; border-radius: 999px; font-size: 10.5px; font-weight: 600; background: var(--unit-line, #e0e7ff); color: var(--accent-ink, #3730a3); white-space: nowrap; }
    .fp-badge.is-muted { background: var(--line-soft); color: var(--muted); }
    .fp-badge.is-ok    { background: #dcfce7; color: #166534; }
    .fp-badge.is-warn  { background: #fef3c7; color: #92400e; }

    /* ---------- Accordion area opsional ---------- */
    .fp-acc { margin-bottom: 10px; border: 1px solid var(--line); border-radius: 12px; background: #fff; transition: border-color .15s, box-shadow .15s; }
    .fp-acc:last-child { margin-bottom: 0; }
    .fp-acc[open] { border-color: var(--brand-200); box-shadow: 0 2px 8px rgba(29,108,219,.06); }
    .fp-acc summary { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 14px 16px; list-style: none; cursor: pointer; font-weight: 600; color: var(--ink); border-radius: 12px; }
    .fp-acc summary::-webkit-details-marker { display: none; }
    .fp-acc summary:hover { background: #f8fafc; }
    .fp-acc summary:focus-visible { outline: 2px solid var(--brand-500); outline-offset: -2px; }
    .fp-acc summary .left { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; }
    .fp-acc summary .chev { flex: none; width: 16px; height: 16px; color: var(--faint); transition: transform .2s; }
    .fp-acc[open] summary .chev { transform: rotate(180deg); }
    .fp-acc .body { padding: 4px 16px 18px; }

    /* ---------- Tabel riwayat ---------- */
    .fp-note { display: flex; gap: 10px; align-items: flex-start; padding: 10px 14px; margin-bottom: 16px; border: 1px solid #bfdbfe; background: #eff6ff; color: #1e40af; border-radius: 10px; font-size: 12px; line-height: 1.55; }
    .fp-note svg { flex: none; width: 16px; height: 16px; margin-top: 1px; }
    .fp-note .fp-badge { vertical-align: 1px; }
    .fp-table-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: 12px; }
    .fp-table { width: 100%; min-width: 760px; border-collapse: separate; border-spacing: 0; font-size: 12.5px; }
    .fp-table th { padding: 10px 14px; text-align: left; white-space: nowrap; font-size: 10.5px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--muted); background: #f8fafc; border-bottom: 1px solid var(--line); }
    .fp-table td { padding: 11px 14px; vertical-align: middle; color: var(--text); border-bottom: 1px solid var(--line-soft); }
    .fp-table tbody tr:last-child td { border-bottom: 0; }
    .fp-table tbody tr:hover td { background: #f8fafc; }
    .fp-table tbody tr.is-update td { background: #fffdf5; }
    .fp-table tbody tr.is-update:hover td { background: #fffaeb; }
    .fp-table .t-center { text-align: center; }
    .fp-table .fp-time { font-weight: 600; color: var(--ink); font-variant-numeric: tabular-nums; }
    .fp-table .fp-area { font-weight: 600; color: var(--ink); }
    .fp-row-actions { display: flex; align-items: center; justify-content: center; gap: 6px; }
    .fp-row-actions form { display: inline; margin: 0; }
    .fp-count { padding: 2px 10px; border-radius: 999px; background: var(--brand-50); color: var(--brand-700); font-size: 11px; font-weight: 600; }

    /* ---------- Tombol ---------- */
    .fp-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 38px; padding: 0 18px; font: inherit; font-size: 12.5px; font-weight: 600; text-decoration: none; border: 1px solid transparent; border-radius: 10px; cursor: pointer; transition: all .15s; }
    .fp-btn svg { width: 16px; height: 16px; }
    .fp-btn-sm { height: 30px; padding: 0 12px; gap: 6px; font-size: 12px; border-radius: 8px; }
    .fp-btn-sm svg { width: 13px; height: 13px; }
    .fp-btn-ghost { color: var(--muted); background: transparent; }
    .fp-btn-ghost:hover { color: var(--ink); background: var(--line-soft); }
    .fp-btn-primary { color: #fff; background: var(--brand-600); box-shadow: 0 2px 8px rgba(20,86,184,.3); }
    .fp-btn-primary:hover { background: var(--brand-700); color: #fff; }
    .fp-btn-primary:active { transform: translateY(1px); }
    .fp-btn-soft { color: var(--brand-700); background: var(--brand-50); border-color: var(--brand-100); }
    .fp-btn-soft:hover { background: var(--brand-100); }
    .fp-btn-danger { color: #be123c; background: #fff1f2; border-color: #fecdd3; }
    .fp-btn-danger:hover { background: #ffe4e6; }
    .fp-btn:focus-visible { outline: 2px solid var(--brand-500); outline-offset: 2px; }

    /* ---------- Bar aksi bawah ---------- */
    .fp-bar { position: sticky; bottom: 12px; z-index: 20; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding: 12px 16px; margin-top: 4px; background: rgba(255,255,255,.92); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 -2px 6px rgba(15,23,42,.03), 0 10px 28px rgba(15,23,42,.1); }
    .fp-bar-status { display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--muted); }
    .fp-bar-status b { color: var(--text); font-weight: 600; }
    .fp-pulse { position: relative; width: 10px; height: 10px; border-radius: 50%; background: #cbd5e1; }
    .fp-pulse.is-on { background: #10b981; }
    .fp-pulse.is-on::after { content: ''; position: absolute; inset: 0; border-radius: 50%; background: #34d399; animation: fp-ping 1.6s cubic-bezier(0,0,.2,1) infinite; }
    @keyframes fp-ping { 75%, 100% { transform: scale(2.4); opacity: 0; } }
    .fp-bar-actions { display: flex; align-items: center; gap: 10px; }

    /* ---------- Modal edit riwayat ---------- */
    .fp-page .modal-content { border: 0; border-radius: 16px; overflow: hidden; box-shadow: 0 24px 60px rgba(15,23,42,.25); font-family: inherit; }
    .fp-modal-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px 22px; border-bottom: 1px solid var(--line-soft); }
    .fp-modal-head h5 { display: flex; align-items: center; gap: 10px; margin: 0; font-size: 15px; font-weight: 700; color: var(--ink); }
    .fp-modal-body { padding: 20px 22px 8px; }
    .fp-modal-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 14px 22px; border-top: 1px solid var(--line-soft); background: #fafbfc; }
    .fp-mfield { margin-bottom: 14px; }

    @media (prefers-reduced-motion: reduce) {
        .fp-unit, .fp-pulse.is-on::after { animation: none; }
        .fp-page * { transition: none !important; }
    }
</style>

<div class="fp-page">
    <header>
        <nav class="fp-crumbs" aria-label="breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
            <a href="{{ route('pemeriksaan-suhu-ruang-v2.index') }}">Pemeriksaan Suhu Ruang</a><span class="sep">/</span>
            <span class="now" aria-current="page">Edit Pemeriksaan</span>
        </nav>
        <h1 class="fp-title">
            Edit Pemeriksaan Suhu Ruang
            <span class="fp-mode">CS Meat</span>
            @if(request()->query('edit_per_2jam'))
                <span class="fp-mode">Per 1 Jam</span>
            @endif
        </h1>
        @if(request()->query('edit_per_2jam'))
            <p class="fp-sub">CS Meat &middot; edit data pemeriksaan (per 1 jam).</p>
        @else
            <p class="fp-sub">CS Meat &middot; perbarui data pemeriksaan suhu ruang.</p>
        @endif
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

    @if(request()->query('edit_per_2jam'))
        <p>Anda dapat melakukan edit data sekarang. Data lama akan disimpan di history.</p>
    @endif

    <form class="form form-horizontal" id="edit-form" autocomplete="off" action="{{ route('pemeriksaan-suhu-ruang-v2.update', $pemeriksaanSuhuRuangV2->uuid) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- ============ 1. INFORMASI UMUM ============ --}}
        <section class="fp-card">
            <div class="fp-card-head">
                <div class="fp-card-title">
                    <span class="fp-ico t-brand"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4m0-4h.01"/></svg></span>
                    <div>
                        <h2>1. Informasi Umum &amp; Parameter Produk</h2>
                        <p>Shift, kategori, dan produk dikunci dan tidak dapat diubah.</p>
                    </div>
                </div>
            </div>

            @php
                $lockIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></svg>';
            @endphp

            <div class="fp-grid">
                <div>
                    <label class="fp-label" for="tanggal"><span>Tanggal <span class="fp-req">*</span></span></label>
                    <input type="date" id="tanggal" class="form-control fp-input @error('tanggal') is-invalid @enderror"
                        name="tanggal" value="{{ old('tanggal', $pemeriksaanSuhuRuangV2->tanggal->format('Y-m-d')) }}" required>
                    @error('tanggal')
                        <div class="fp-error">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="fp-label" for="id_shift"><span>Shift</span><span class="fp-hint">{!! $lockIcon !!} Terkunci</span></label>
                    <input type="text" id="id_shift" class="form-control fp-input" value="{{ $pemeriksaanSuhuRuangV2->shift->shift }}" disabled>
                </div>

                <div>
                    <label class="fp-label" for="pukul"><span>Pukul <span class="fp-req">*</span></span></label>
                    <input type="time" id="pukul" class="form-control fp-input @error('pukul') is-invalid @enderror"
                        name="pukul" value="{{ old('pukul', $pemeriksaanSuhuRuangV2->pukul) }}" required>
                    @error('pukul')
                        <div class="fp-error">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="fp-label" for="kategori_produk"><span>Kategori Produk</span><span class="fp-hint">{!! $lockIcon !!} Terkunci</span></label>
                    <input type="text" id="kategori_produk" class="form-control fp-input" value="{{ $pemeriksaanSuhuRuangV2->produk->kategori_code ?? '-' }}" disabled>
                </div>

                <div>
                    <label class="fp-label" for="id_produk"><span>Produk</span><span class="fp-hint">{!! $lockIcon !!} Terkunci</span></label>
                    <input type="text" id="id_produk" class="form-control fp-input" value="{{ $pemeriksaanSuhuRuangV2->produk->nama_produk ?? '-' }}" disabled>
                </div>

                <div>
                    <label class="fp-label" for="suhu_produk"><span>Suhu Produk <span class="fp-req">*</span></span><span class="fp-hint">Probe internal</span></label>
                    <input type="text" inputmode="text" id="suhu_produk" class="form-control fp-input @error('suhu_produk') is-invalid @enderror"
                        name="suhu_produk" value="{{ old('suhu_produk', $pemeriksaanSuhuRuangV2->suhu_produk) }}" placeholder="Contoh: -18°C" required>
                    @error('suhu_produk')
                        <div class="fp-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </section>

        {{-- ============ 2. SUHU COLD STORAGE ============ --}}
        @php
            $coldStorageData = $pemeriksaanSuhuRuangV2->suhu_cold_storage ?? [];
            $coldStorageByUnit = [];
            if (is_array($coldStorageData)) {
                foreach ($coldStorageData as $unit => $item) {
                    $coldStorageByUnit[$unit] = $item;
                }
            }
        @endphp
        <section class="fp-card is-brand">
            <div class="fp-card-head">
                <div class="fp-card-title">
                    <span class="fp-ico t-sky"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20M4.93 4.93l14.14 14.14M19.07 4.93L4.93 19.07"/></svg></span>
                    <div>
                        <h2>2. Suhu Cold Storage (1-4)</h2>
                        <p>Centang unit yang digunakan, lalu isi data suhunya.</p>
                    </div>
                </div>
                <div class="fp-chips" role="group" aria-label="Pilih unit Cold Storage">
                    @for($i = 1; $i <= 4; $i++)
                        <label class="fp-chip" for="cold_storage_{{ $i }}_check">
                            <input class="cold-storage-checkbox" type="checkbox" id="cold_storage_{{ $i }}_check" data-unit="{{ $i }}" {{ isset($coldStorageByUnit[$i]) ? 'checked' : '' }}>
                            <span>CS {{ $i }}</span>
                        </label>
                    @endfor
                </div>
            </div>

            <p class="fp-empty" id="cold_storage_empty" style="display: {{ count($coldStorageByUnit) ? 'none' : '' }};">Belum ada unit dipilih. Centang CS 1 &ndash; CS 4 di atas untuk mulai mengisi.</p>

            @for($i = 1; $i <= 4; $i++)
                @php $data = $coldStorageByUnit[$i] ?? []; @endphp
                <div class="fp-unit cold-storage-unit" id="cold_storage_{{ $i }}_form" style="display: {{ isset($coldStorageByUnit[$i]) ? 'block' : 'none' }};">
                    <div class="fp-unit-head">
                        <span class="fp-dot"></span>
                        <span class="fp-unit-name">Unit CS {{ $i }}</span>
                        <span class="fp-badge">Standar &le; (-18)&deg;C</span>
                    </div>
                    <div class="fp-triple">
                        @foreach(['setting' => 'Setting', 'display' => 'Display', 'actual' => 'Actual'] as $field => $fieldLabel)
                            @php
                                $key = 'cold_storage_' . $i . '_' . $field;
                                $val = old($key, $data[$field] ?? '');
                            @endphp
                            <div>
                                <label class="fp-sublabel" for="{{ $key }}">{{ $fieldLabel }} (°C)</label>
                                <select id="{{ $key }}" class="form-select form-select-sm fp-input"
                                    name="{{ $key }}">
                                    <option value="">-- Pilih atau Isi Manual --</option>
                                    <option value="-18" {{ $val == '-18' ? 'selected' : '' }}>Std ≤ (-18)°C</option>
                                    <option value="manual" {{ $val && $val != '-18' ? 'selected' : '' }}>Input Manual</option>
                                </select>
                                <input type="text" inputmode="text" id="{{ $key }}_manual"
                                    class="form-control form-control-sm fp-input fp-manual" style="display: none;"
                                    placeholder="Masukkan nilai"
                                    value="{{ $val && $val != '-18' ? $val : '' }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor
        </section>

        {{-- ============ 3. SUHU ANTEROOM LOADING ============ --}}
        @php
            $anteroomLoadingData = $pemeriksaanSuhuRuangV2->suhu_anteroom_loading ?? [];
            $anteroomLoadingByUnit = [];
            if (is_array($anteroomLoadingData)) {
                foreach ($anteroomLoadingData as $unit => $item) {
                    $anteroomLoadingByUnit[$unit] = $item;
                }
            }
        @endphp
        <section class="fp-card is-teal">
            <div class="fp-card-head">
                <div class="fp-card-title">
                    <span class="fp-ico t-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M12 3v18M9 12h.01M15 12h.01"/></svg></span>
                    <div>
                        <h2>3. Suhu Anteroom Loading (1-4)</h2>
                        <p>Centang anteroom yang digunakan, lalu isi data suhunya.</p>
                    </div>
                </div>
                <div class="fp-chips" role="group" aria-label="Pilih unit Anteroom Loading">
                    @for($i = 1; $i <= 4; $i++)
                        <label class="fp-chip" for="anteroom_loading_{{ $i }}_check">
                            <input class="anteroom-loading-checkbox" type="checkbox" id="anteroom_loading_{{ $i }}_check" data-unit="{{ $i }}" {{ isset($anteroomLoadingByUnit[$i]) ? 'checked' : '' }}>
                            <span>Anteroom {{ $i }}</span>
                        </label>
                    @endfor
                </div>
            </div>

            <p class="fp-empty" id="anteroom_loading_empty" style="display: {{ count($anteroomLoadingByUnit) ? 'none' : '' }};">Belum ada anteroom dipilih. Centang Anteroom 1 &ndash; 4 di atas untuk mulai mengisi.</p>

            @for($i = 1; $i <= 4; $i++)
                @php $data = $anteroomLoadingByUnit[$i] ?? []; @endphp
                <div class="fp-unit anteroom-loading-unit" id="anteroom_loading_{{ $i }}_form" style="display: {{ isset($anteroomLoadingByUnit[$i]) ? 'block' : 'none' }};">
                    <div class="fp-unit-head">
                        <span class="fp-dot"></span>
                        <span class="fp-unit-name">Anteroom Loading {{ $i }}</span>
                        <span class="fp-badge">Standar 0 &plusmn; 5&deg;C</span>
                    </div>
                    <div class="fp-triple">
                        @foreach(['setting' => 'Setting', 'display' => 'Display', 'actual' => 'Actual'] as $field => $fieldLabel)
                            @php
                                $key = 'anteroom_loading_' . $i . '_' . $field;
                                $val = old($key, $data[$field] ?? '');
                            @endphp
                            <div>
                                <label class="fp-sublabel" for="{{ $key }}">{{ $fieldLabel }} (°C)</label>
                                <select id="{{ $key }}" class="form-select form-select-sm fp-input"
                                    name="{{ $key }}">
                                    <option value="">-- Pilih atau Isi Manual --</option>
                                    <option value="(0±5°C)" {{ $val == '(0±5°C)' ? 'selected' : '' }}>Std 0 ± 5°C</option>
                                    <option value="manual" {{ $val && $val != '(0±5°C)' ? 'selected' : '' }}>Input Manual</option>
                                </select>
                                <input type="text" inputmode="text" id="{{ $key }}_manual"
                                    class="form-control form-control-sm fp-input fp-manual" style="display: none;"
                                    placeholder="Masukkan nilai"
                                    value="{{ $val && $val != '0' ? $val : '' }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor
        </section>

        {{-- ============ 4. AREA OPSIONAL ============ --}}
        @php
            $optionalAreas = [
                ['key' => 'pre_loading',           'label' => 'Suhu Pre Loading',                  'type' => 'text'],
                ['key' => 'prestaging',            'label' => 'Suhu Prestaging',                   'type' => 'text'],
                ['key' => 'anteroom_ekspansi_abf', 'label' => 'Suhu Anteroom Ekspansi ABF',        'type' => 'text'],
                ['key' => 'chillroom_rm',          'label' => 'Suhu Chillroom RM (0 - 4°C)',       'type' => 'select'],
                ['key' => 'chillroom_domestik',    'label' => 'Suhu Chillroom Domestik (0 - 4°C)', 'type' => 'select'],
            ];
        @endphp
        <section class="fp-card">
            <div class="fp-card-head">
                <div class="fp-card-title">
                    <span class="fp-ico t-violet"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h10"/></svg></span>
                    <div>
                        <h2>4. Area Tambahan <span style="font-weight:500;color:var(--faint)">(Opsional)</span></h2>
                        <p>Klik area untuk membuka. Badge "Terisi" menandakan area sudah memiliki data.</p>
                    </div>
                </div>
            </div>

            @foreach($optionalAreas as $area)
                @php
                    $areaKey  = $area['key'];
                    $areaData = $pemeriksaanSuhuRuangV2->{'suhu_' . $areaKey} ?? [];

                    // Normalisasi: string JSON / Collection / object -> array
                    if (is_string($areaData)) $areaData = json_decode($areaData, true) ?: [];
                    if ($areaData instanceof \Illuminate\Support\Collection) $areaData = $areaData->toArray();
                    if (is_object($areaData)) $areaData = (array) $areaData;
                    if (!is_array($areaData)) $areaData = [];

                    // Jika bentuknya list ([{"setting":..}]) ambil item pertama yang berisi
                    if (!isset($areaData['setting']) && !isset($areaData['display']) && !isset($areaData['actual'])) {
                        $areaData = collect($areaData)->first(
                            fn ($row) => is_array($row) && (filled($row['setting'] ?? null) || filled($row['display'] ?? null) || filled($row['actual'] ?? null))
                        ) ?? [];
                    }

                    $areaValues = [];
                    foreach (['setting', 'display', 'actual'] as $f) {
                        $areaValues[$f] = old($areaKey . '_' . $f, $areaData[$f] ?? '');
                    }
                    $areaFilled = collect($areaValues)->contains(fn ($v) => filled($v));
                @endphp

                <details class="fp-acc" data-area="{{ $areaKey }}">
                    <summary>
                        <span class="left">
                            <span>{{ $area['label'] }}</span>
                            <span class="fp-badge js-area-badge {{ $areaFilled ? 'is-ok' : 'is-muted' }}">{{ $areaFilled ? 'Terisi' : 'Opsional' }}</span>
                        </span>
                        <svg class="chev" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8l4 4 4-4"/></svg>
                    </summary>
                    <div class="body">
                        <div class="fp-triple">
                            @foreach(['setting' => 'Setting', 'display' => 'Display', 'actual' => 'Actual'] as $field => $fieldLabel)
                                @php
                                    $key = $areaKey . '_' . $field;
                                    $val = $areaValues[$field];
                                @endphp
                                <div>
                                    <label class="fp-sublabel" for="{{ $key }}">{{ $fieldLabel }} (°C)</label>
                                    @if($area['type'] === 'select')
                                        <select id="{{ $key }}" class="form-select form-select-sm fp-input js-area-input"
                                            name="{{ $key }}">
                                            <option value="">-- Pilih atau Isi Manual --</option>
                                            <option value="(0-4°C)" {{ $val == '(0-4°C)' ? 'selected' : '' }}>Std 0 - 4°C</option>
                                            <option value="manual" {{ $val && $val != '(0-4°C)' ? 'selected' : '' }}>Input Manual</option>
                                        </select>
                                        <input type="text" inputmode="text" id="{{ $key }}_manual"
                                            class="form-control form-control-sm fp-input fp-manual js-area-input" style="display: none;"
                                            placeholder="Masukkan nilai"
                                            value="{{ $val && $val != '(0-4°C)' ? $val : '' }}">
                                    @else
                                        <input type="text" inputmode="text" id="{{ $key }}"
                                            class="form-control form-control-sm fp-input js-area-input"
                                            name="{{ $key }}"
                                            placeholder="{{ $fieldLabel }}"
                                            value="{{ $val }}">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </details>
            @endforeach
        </section>

        {{-- ============ 5. CATATAN ============ --}}
        <section class="fp-card">
            <div class="fp-card-head">
                <div class="fp-card-title">
                    <span class="fp-ico t-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/></svg></span>
                    <div>
                        <h2>5. Catatan</h2>
                        <p>Tuliskan kondisi khusus di lapangan dan tindakan koreksi bila ada penyimpangan.</p>
                    </div>
                </div>
            </div>
            <div class="fp-grid-2">
                <div>
                    <label class="fp-label" for="keterangan"><span>Keterangan</span></label>
                    <textarea id="keterangan" class="form-control form-control-sm fp-input" name="keterangan" placeholder="Keterangan" rows="4">{{ old('keterangan', $pemeriksaanSuhuRuangV2->keterangan) }}</textarea>
                </div>
                <div>
                    <label class="fp-label" for="tindakan_koreksi"><span>Tindakan Koreksi</span></label>
                    <textarea id="tindakan_koreksi" class="form-control form-control-sm fp-input" name="tindakan_koreksi" placeholder="Tindakan Koreksi" rows="4">{{ old('tindakan_koreksi', $pemeriksaanSuhuRuangV2->tindakan_koreksi) }}</textarea>
                </div>
            </div>
        </section>

        {{-- ============ BAR AKSI ============ --}}
        <div class="fp-bar">
            <div class="fp-bar-status" aria-live="polite">
                <span class="fp-pulse" id="fpPulse"></span>
                <span id="fpUnitSummary">Belum ada unit yang dipilih</span>
            </div>
            <div class="fp-bar-actions">
                <a href="{{ route('pemeriksaan-suhu-ruang-v2.index') }}" class="fp-btn fp-btn-ghost">Kembali</a>
                <button type="submit" class="fp-btn fp-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                    Update Pemeriksaan
                </button>
            </div>
        </div>
    </form>

    {{-- ===== RIWAYAT DATA PER JAM (di luar form) ===== --}}
    @php
        $historiesV2 = $pemeriksaanSuhuRuangV2->histories->sortBy('id');
        $firstHistory = $historiesV2->first();
        $timelineRowsV2 = [];
        $noV2 = 1;

        $rawInitPukul = $firstHistory && $firstHistory->pukul_lama ? $firstHistory->pukul_lama : $pemeriksaanSuhuRuangV2->pukul;
        $initJamV2 = $rawInitPukul ? \Carbon\Carbon::parse($rawInitPukul)->format('H:i') : '-';
        $initCreatedV2 = $pemeriksaanSuhuRuangV2->created_at ? $pemeriksaanSuhuRuangV2->created_at->format('d/m/Y H:i') : '-';

        $sectionLabelsV2 = [
            'cold_storage'          => 'Cold Storage',
            'anteroom_loading'      => 'Anteroom Loading',
            'pre_loading'           => 'Pre Loading',
            'prestaging'            => 'Prestaging',
            'anteroom_ekspansi_abf' => 'Anteroom Ekspansi ABF',
            'chillroom_rm'          => 'Chillroom RM',
            'chillroom_domestik'    => 'Chillroom Domestik',
        ];

        $initPukulEditV2 = $firstHistory ? $firstHistory->pukul_lama : $pemeriksaanSuhuRuangV2->pukul;

        // Mengambil nilai data awal (jika pernah di-edit/update, gunakan *_lama dari history pertama)
        $initColdStorageData = $firstHistory ? (is_array($firstHistory->suhu_cold_storage_lama) ? $firstHistory->suhu_cold_storage_lama : (json_decode($firstHistory->suhu_cold_storage_lama, true) ?: [])) : $pemeriksaanSuhuRuangV2->suhu_cold_storage;

        $initAnteroomLoadingData = $firstHistory ? (is_array($firstHistory->suhu_anteroom_loading_lama) ? $firstHistory->suhu_anteroom_loading_lama : (json_decode($firstHistory->suhu_anteroom_loading_lama, true) ?: [])) : $pemeriksaanSuhuRuangV2->suhu_anteroom_loading;

        $initSuhuProdukVal = $firstHistory ? $firstHistory->suhu_produk_lama : $pemeriksaanSuhuRuangV2->suhu_produk;

        // Input Awal Cold Storage
        if (!empty($initColdStorageData)) {
            foreach ((array)$initColdStorageData as $uId => $uVal) {
                $timelineRowsV2[] = [
                    'no'          => $noV2++,
                    'waktu'       => $initJamV2,
                    'edited'      => $initCreatedV2,
                    'area'        => 'Cold Storage ' . $uId,
                    'setting'     => $uVal['setting'] ?? '-',
                    'aktual'      => $uVal['actual'] ?? '-',
                    'display'     => $uVal['display'] ?? '-',
                    'tipe'        => 'awal',
                    'field_type'  => 'suhu_data',
                    'section_key' => 'cold_storage',
                    'unit_id'     => $uId,
                    'pukul_raw'   => $initPukulEditV2 ? \Carbon\Carbon::parse($initPukulEditV2)->format('H:i') : '',
                    'history_uuid'=> null,
                ];
            }
        }

        // Input Awal Anteroom Loading
        if (!empty($initAnteroomLoadingData)) {
            foreach ((array)$initAnteroomLoadingData as $uId => $uVal) {
                $timelineRowsV2[] = [
                    'no'          => $noV2++,
                    'waktu'       => $initJamV2,
                    'edited'      => $initCreatedV2,
                    'area'        => 'Anteroom Loading ' . $uId,
                    'setting'     => $uVal['setting'] ?? '-',
                    'aktual'      => $uVal['actual'] ?? '-',
                    'display'     => $uVal['display'] ?? '-',
                    'tipe'        => 'awal',
                    'field_type'  => 'suhu_data',
                    'section_key' => 'anteroom_loading',
                    'unit_id'     => $uId,
                    'pukul_raw'   => $initPukulEditV2 ? \Carbon\Carbon::parse($initPukulEditV2)->format('H:i') : '',
                    'history_uuid'=> null,
                ];
            }
        }

        // Input Awal Single Sections
        foreach (['pre_loading', 'prestaging', 'anteroom_ekspansi_abf', 'chillroom_rm', 'chillroom_domestik'] as $sKey) {
            $attrLama = 'suhu_' . $sKey . '_lama';
            $attr = 'suhu_' . $sKey;
            $val = $firstHistory
                ? (is_array($firstHistory->{$attrLama}) ? $firstHistory->{$attrLama} : (json_decode($firstHistory->{$attrLama}, true) ?: []))
                : $pemeriksaanSuhuRuangV2->{$attr};

            if (!empty($val) && (!empty($val['setting']) || !empty($val['display']) || !empty($val['actual']))) {
                $timelineRowsV2[] = [
                    'no'          => $noV2++,
                    'waktu'       => $initJamV2,
                    'edited'      => $initCreatedV2,
                    'area'        => $sectionLabelsV2[$sKey],
                    'setting'     => $val['setting'] ?? '-',
                    'aktual'      => $val['actual'] ?? '-',
                    'display'     => $val['display'] ?? '-',
                    'tipe'        => 'awal',
                    'field_type'  => 'suhu_data',
                    'section_key' => $sKey,
                    'unit_id'     => null,
                    'pukul_raw'   => $initPukulEditV2 ? \Carbon\Carbon::parse($initPukulEditV2)->format('H:i') : '',
                    'history_uuid'=> null,
                ];
            }
        }

        // Input Awal Suhu Produk
        if (!empty($initSuhuProdukVal)) {
            $timelineRowsV2[] = [
                'no'          => $noV2++,
                'waktu'       => $initJamV2,
                'edited'      => $initCreatedV2,
                'area'        => 'Suhu Produk',
                'setting'     => '-',
                'aktual'      => $initSuhuProdukVal,
                'display'     => '-',
                'tipe'        => 'awal',
                'field_type'  => 'suhu_produk',
                'section_key' => null,
                'unit_id'     => null,
                'pukul_raw'   => $initPukulEditV2 ? \Carbon\Carbon::parse($initPukulEditV2)->format('H:i') : '',
                'history_uuid'=> null,
            ];
        }

        // Iterasi History Updates
        foreach ($historiesV2 as $h) {
            $editedAtV2 = $h->created_at ? $h->created_at->format('d/m/Y H:i') : '-';
            $pukulH = $h->pukul_baru ?? $h->pukul_lama ?? null;
            $jamH = $pukulH ? \Carbon\Carbon::parse($pukulH)->format('H:i') : '-';

            foreach ($sectionLabelsV2 as $secKey => $secLabel) {
                $colLama = 'suhu_' . $secKey . '_lama';
                $colBaru = 'suhu_' . $secKey . '_baru';

                $lamaData = is_array($h->{$colLama}) ? $h->{$colLama} : (json_decode($h->{$colLama} ?? '[]', true) ?: []);
                $baruData = is_array($h->{$colBaru}) ? $h->{$colBaru} : (json_decode($h->{$colBaru} ?? '[]', true) ?: []);

                if (json_encode($lamaData) === json_encode($baruData)) continue;

                if (in_array($secKey, ['cold_storage', 'anteroom_loading'])) {
                    $allUnits = array_unique(array_merge(array_keys((array)$lamaData), array_keys((array)$baruData)));
                    foreach ($allUnits as $uId) {
                        $lItem = $lamaData[$uId] ?? [];
                        $bItem = $baruData[$uId] ?? [];
                        if (json_encode($lItem) === json_encode($bItem)) continue;
                        if (empty($bItem['setting']) && empty($bItem['display']) && empty($bItem['actual'])) continue;

                        $timelineRowsV2[] = [
                            'no'          => $noV2++,
                            'waktu'       => $jamH,
                            'edited'      => $editedAtV2,
                            'area'        => $secLabel . ' ' . $uId,
                            'setting'     => $bItem['setting'] ?? '-',
                            'aktual'      => $bItem['actual'] ?? '-',
                            'display'     => $bItem['display'] ?? '-',
                            'tipe'        => 'update',
                            'history_uuid'=> $h->uuid,
                            'field_type'  => 'suhu_data',
                            'section_key' => $secKey,
                            'unit_id'     => $uId,
                        ];
                    }
                } else {
                    if (empty($baruData['setting']) && empty($baruData['display']) && empty($baruData['actual'])) continue;
                    $timelineRowsV2[] = [
                        'no'          => $noV2++,
                        'waktu'       => $jamH,
                        'edited'      => $editedAtV2,
                        'area'        => $secLabel,
                        'setting'     => $baruData['setting'] ?? '-',
                        'aktual'      => $baruData['actual'] ?? '-',
                        'display'     => $baruData['display'] ?? '-',
                        'tipe'        => 'update',
                        'history_uuid'=> $h->uuid,
                        'field_type'  => 'suhu_data',
                        'section_key' => $secKey,
                        'unit_id'     => null,
                    ];
                }
            }

            // Update Suhu Produk
            if (($h->suhu_produk_lama ?? null) !== ($h->suhu_produk_baru ?? null) && !empty($h->suhu_produk_baru)) {
                $timelineRowsV2[] = [
                    'no'          => $noV2++,
                    'waktu'       => $jamH,
                    'edited'      => $editedAtV2,
                    'area'        => 'Suhu Produk',
                    'setting'     => '-',
                    'aktual'      => $h->suhu_produk_baru,
                    'display'     => '-',
                    'tipe'        => 'update',
                    'history_uuid'=> $h->uuid,
                    'field_type'  => 'suhu_produk',
                    'section_key' => null,
                    'unit_id'     => null,
                ];
            }
         }
    @endphp

    @if(!empty($timelineRowsV2) && !request()->query('edit_per_2jam'))
        <section class="fp-card" style="margin-top: 28px;">
            <div class="fp-card-head">
                <div class="fp-card-title">
                    <span class="fp-ico t-slate"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
                    <div>
                        <h2>Riwayat Data Per Jam <span class="fp-count">{{ count($timelineRowsV2) }} entri</span></h2>
                        <p>Daftar input awal dan perubahan data per jam untuk pemeriksaan ini.</p>
                    </div>
                </div>
            </div>

            <div class="fp-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4m0-4h.01"/></svg>
                <div>
                    Klik tombol <strong>Edit</strong> atau <strong>Hapus</strong> pada baris tabel (status <span class="fp-badge is-ok">Input Awal</span> maupun <span class="fp-badge is-warn">Update</span>) untuk mengoreksi atau menghapus data area tersebut.
                </div>
            </div>

            <div class="fp-table-wrap">
                <table class="fp-table">
                    <thead>
                        <tr>
                            <th class="t-center" style="width:4%">No</th>
                            <th class="t-center" style="width:8%">Pukul</th>
                            <th style="width:20%">Area</th>
                            <th class="t-center">Setting (°C)</th>
                            <th class="t-center">Actual (°C)</th>
                            <th class="t-center">Display (°C)</th>
                            <th class="t-center" style="width:8%">Status</th>
                            <th class="t-center" style="width:14%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timelineRowsV2 as $tRow)
                        <tr class="{{ $tRow['tipe'] === 'update' ? 'is-update' : '' }}">
                            <td class="t-center">{{ $tRow['no'] }}</td>
                            <td class="t-center fp-time">{{ $tRow['waktu'] }}</td>
                            <td class="fp-area">{{ $tRow['area'] }}</td>
                            <td class="t-center">{{ $tRow['setting'] }}</td>
                            <td class="t-center">{{ $tRow['aktual'] }}</td>
                            <td class="t-center">{{ $tRow['display'] }}</td>
                            <td class="t-center">
                                @if($tRow['tipe'] === 'awal')
                                    <span class="fp-badge is-ok">Input Awal</span>
                                @else
                                    <span class="fp-badge is-warn">Update</span>
                                @endif
                            </td>
                            <td class="t-center">
                                @if($tRow['tipe'] === 'update' && !empty($tRow['history_uuid']))
                                    <div class="fp-row-actions">
                                        <button type="button"
                                            class="fp-btn fp-btn-sm fp-btn-soft btn-edit-history-v2"
                                            data-history-uuid="{{ $tRow['history_uuid'] }}"
                                            data-pukul="{{ $tRow['waktu'] !== '-' ? $tRow['waktu'] : '' }}"
                                            data-area="{{ $tRow['area'] }}"
                                            data-field-type="{{ $tRow['field_type'] }}"
                                            data-section-key="{{ $tRow['section_key'] }}"
                                            data-unit-id="{{ $tRow['unit_id'] }}"
                                            data-setting="{{ $tRow['setting'] !== '-' ? $tRow['setting'] : '' }}"
                                            data-aktual="{{ $tRow['aktual'] !== '-' ? $tRow['aktual'] : '' }}"
                                            data-display="{{ $tRow['display'] !== '-' ? $tRow['display'] : '' }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                                            Edit
                                        </button>
                                        <form action="{{ route('pemeriksaan-suhu-ruang-v2.history.destroy', [$pemeriksaanSuhuRuangV2->uuid, $tRow['history_uuid']]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus area ini dari riwayat per jam?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="field_type" value="{{ $tRow['field_type'] }}">
                                            <input type="hidden" name="section_key" value="{{ $tRow['section_key'] ?? '' }}">
                                            <input type="hidden" name="unit_id" value="{{ $tRow['unit_id'] ?? '' }}">
                                            <button type="submit" class="fp-btn fp-btn-sm fp-btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                @else
                                    <div class="fp-row-actions">
                                        <button type="button"
                                            class="fp-btn fp-btn-sm fp-btn-soft btn-edit-history-v2"
                                            data-is-initial="1"
                                            data-pukul="{{ !empty($tRow['pukul_raw']) ? $tRow['pukul_raw'] : ($tRow['waktu'] !== '-' ? $tRow['waktu'] : '') }}"
                                            data-area="{{ $tRow['area'] }}"
                                            data-field-type="{{ $tRow['field_type'] ?? '' }}"
                                            data-section-key="{{ $tRow['section_key'] ?? '' }}"
                                            data-unit-id="{{ $tRow['unit_id'] ?? '' }}"
                                            data-setting="{{ $tRow['setting'] !== '-' ? $tRow['setting'] : '' }}"
                                            data-aktual="{{ $tRow['aktual'] !== '-' ? $tRow['aktual'] : '' }}"
                                            data-display="{{ $tRow['display'] !== '-' ? $tRow['display'] : '' }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                                            Edit
                                        </button>
                                        <form action="{{ route('pemeriksaan-suhu-ruang-v2.initial.destroy', $pemeriksaanSuhuRuangV2->uuid) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus area ini dari input awal?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="field_type" value="{{ $tRow['field_type'] ?? '' }}">
                                            <input type="hidden" name="section_key" value="{{ $tRow['section_key'] ?? '' }}">
                                            <input type="hidden" name="unit_id" value="{{ $tRow['unit_id'] ?? '' }}">
                                            <button type="submit" class="fp-btn fp-btn-sm fp-btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
</div>
</div>

<!-- Modal Edit History V2 -->
<div class="fp-page">
    <div class="modal fade" id="editHistoryModalV2" tabindex="-1" aria-labelledby="editHistoryModalV2Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editHistoryFormV2" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="fp-modal-head">
                        <h5 id="editHistoryModalV2Label">
                            <span class="fp-ico t-brand" style="width:30px;height:30px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/></svg></span>
                            Edit Data Riwayat Per Jam
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="fp-modal-body">
                        <input type="hidden" name="field_type" id="modalFieldTypeV2">
                        <input type="hidden" name="section_key" id="modalSectionKeyV2">
                        <input type="hidden" name="unit_id" id="modalUnitIdV2">

                        <div class="fp-mfield">
                            <label class="fp-sublabel" for="modalAreaLabelV2">Area</label>
                            <input type="text" class="form-control fp-input" id="modalAreaLabelV2" readonly disabled>
                        </div>

                        <div class="fp-mfield">
                            <label class="fp-sublabel" for="modalPukulV2">Pukul</label>
                            <input type="time" class="form-control fp-input" id="modalPukulV2" name="pukul" required>
                        </div>

                        <!-- Fields untuk Suhu Produk -->
                        <div id="modalSuhuProdukContainerV2" style="display:none;">
                            <div class="fp-mfield">
                                <label class="fp-sublabel" for="modalSuhuProdukV2">Suhu Produk (°C)</label>
                                <input type="text" class="form-control fp-input" id="modalSuhuProdukV2" name="suhu_produk" placeholder="Contoh: -18°C">
                            </div>
                        </div>

                        <!-- Fields untuk Suhu Data Section -->
                        <div id="modalSuhuDataContainerV2" style="display:none;">
                            <div class="fp-mfield">
                                <label class="fp-sublabel" for="modalSettingV2">Setting (°C)</label>
                                <input type="text" class="form-control fp-input" id="modalSettingV2" name="setting" placeholder="Nilai setting">
                            </div>
                            <div class="fp-mfield">
                                <label class="fp-sublabel" for="modalAktualV2">Aktual (°C)</label>
                                <input type="text" class="form-control fp-input" id="modalAktualV2" name="aktual" placeholder="Nilai aktual">
                            </div>
                            <div class="fp-mfield">
                                <label class="fp-sublabel" for="modalDisplayV2">Display (°C)</label>
                                <input type="text" class="form-control fp-input" id="modalDisplayV2" name="display" placeholder="Nilai display">
                            </div>
                        </div>
                    </div>
                    <div class="fp-modal-foot">
                        <button type="button" class="fp-btn fp-btn-ghost" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="fp-btn fp-btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle toggle for manual input with checkbox
    function setupManualToggleWithCheckbox(prefix, count) {
        for (let i = 1; i <= count; i++) {
            ['setting', 'display', 'actual'].forEach(field => {
                const selectId = `${prefix}_${i}_${field}`;
                const manualId = `${prefix}_${i}_${field}_manual`;
                const selectEl = document.getElementById(selectId);
                const manualEl = document.getElementById(manualId);
                
                if (selectEl && manualEl) {
                    selectEl.addEventListener('change', function() {
                        if (this.value === 'manual') {
                            manualEl.style.display = 'block';
                            manualEl.name = selectId;
                            selectEl.name = '';
                        } else {
                            manualEl.style.display = 'none';
                            manualEl.name = '';
                            selectEl.name = selectId;
                        }
                    });
                    
                    // Trigger on page load if manual was selected
                    if (selectEl.value === 'manual') {
                        manualEl.style.display = 'block';
                        manualEl.name = selectId;
                        selectEl.name = '';
                    } else {
                        selectEl.name = selectId;
                    }
                }
            });
        }
    }
    
    // Function to handle toggle for manual input (select with manual option)
    function setupManualToggleSimple(prefixes) {
        prefixes.forEach(prefix => {
            ['setting', 'display', 'actual'].forEach(field => {
                const selectId = `${prefix}_${field}`;
                const manualId = `${prefix}_${field}_manual`;
                const selectEl = document.getElementById(selectId);
                const manualEl = document.getElementById(manualId);
                
                if (selectEl && manualEl) {
                    selectEl.addEventListener('change', function() {
                        if (this.value === 'manual') {
                            manualEl.style.display = 'block';
                            manualEl.name = selectId;
                            selectEl.name = '';
                        } else {
                            manualEl.style.display = 'none';
                            manualEl.name = '';
                            selectEl.name = selectId;
                        }
                    });
                    
                    // Trigger on page load if manual was selected
                    if (selectEl.value === 'manual') {
                        manualEl.style.display = 'block';
                        manualEl.name = selectId;
                        selectEl.name = '';
                    } else {
                        selectEl.name = selectId;
                    }
                }
            });
        });
    }
    
    // Cold Storage toggle (with checkbox)
    setupManualToggleWithCheckbox('cold_storage', 4);
    
    // Anteroom Loading toggle (with checkbox)
    setupManualToggleWithCheckbox('anteroom_loading', 4);
    
    // Fields with manual toggle (select with manual option)
    setupManualToggleSimple([
        'chillroom_rm',
        'chillroom_domestik'
    ]);
    
    // Handle Cold Storage checkbox show/hide
    document.querySelectorAll('.cold-storage-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const unit = this.dataset.unit;
            const form = document.getElementById(`cold_storage_${unit}_form`);
            if (form) {
                form.style.display = this.checked ? 'block' : 'none';
            }
        });
    });
    
    // Handle Anteroom Loading checkbox show/hide
    document.querySelectorAll('.anteroom-loading-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const unit = this.dataset.unit;
            const form = document.getElementById(`anteroom_loading_${unit}_form`);
            if (form) {
                form.style.display = this.checked ? 'block' : 'none';
            }
        });
    });

    // Handle Edit History Modal V2
    const editHistoryModalV2 = new bootstrap.Modal(document.getElementById('editHistoryModalV2'));
    const editHistoryFormV2  = document.getElementById('editHistoryFormV2');
    const baseUrlV2          = "{{ url('qc-sistem/pemeriksaan-suhu-ruang-v2/' . $pemeriksaanSuhuRuangV2->uuid . '/history') }}";

    document.querySelectorAll('.btn-edit-history-v2').forEach(btn => {
        btn.addEventListener('click', function() {
            const historyUuid = this.getAttribute('data-history-uuid');
            const isInitial   = this.getAttribute('data-is-initial') === '1';
            const pukul       = this.getAttribute('data-pukul');
            const area        = this.getAttribute('data-area');
            const fieldType   = this.getAttribute('data-field-type');
            const sectionKey  = this.getAttribute('data-section-key');
            const unitId      = this.getAttribute('data-unit-id');
            const setting     = this.getAttribute('data-setting');
            const aktual      = this.getAttribute('data-aktual');
            const display     = this.getAttribute('data-display');

            if (isInitial) {
                editHistoryFormV2.action = "{{ route('pemeriksaan-suhu-ruang-v2.initial.update', $pemeriksaanSuhuRuangV2->uuid) }}";
            } else {
                editHistoryFormV2.action = `${baseUrlV2}/${historyUuid}`;
            }

            document.getElementById('modalAreaLabelV2').value  = area;
            document.getElementById('modalPukulV2').value      = pukul;
            document.getElementById('modalFieldTypeV2').value  = fieldType;
            document.getElementById('modalSectionKeyV2').value = sectionKey || '';
            document.getElementById('modalUnitIdV2').value     = unitId || '';

            const spContainer = document.getElementById('modalSuhuProdukContainerV2');
            const sdContainer = document.getElementById('modalSuhuDataContainerV2');

            if (fieldType === 'suhu_produk') {
                spContainer.style.display = 'block';
                sdContainer.style.display = 'none';
                document.getElementById('modalSuhuProdukV2').value = aktual;
            } else {
                spContainer.style.display = 'none';
                sdContainer.style.display = 'block';
                document.getElementById('modalSettingV2').value = setting;
                document.getElementById('modalAktualV2').value  = aktual;
                document.getElementById('modalDisplayV2').value = display;
            }

            editHistoryModalV2.show();
        });
    });

    /* ============================================================
       Tambahan khusus tampilan (tidak menyentuh data/backend):
       - empty state saat belum ada unit dipilih
       - ringkasan unit aktif di bar bawah
       - unit yang sudah terisi otomatis dicentang
       ============================================================ */
    const unitGroups = [
        { prefix: 'cold_storage',     cls: 'cold-storage-checkbox',     label: 'Cold Storage' },
        { prefix: 'anteroom_loading', cls: 'anteroom-loading-checkbox', label: 'Anteroom Loading' },
    ];
    const summaryEl = document.getElementById('fpUnitSummary');
    const pulseEl = document.getElementById('fpPulse');

    const refreshSummary = function() {
        const counts = unitGroups.map(g => ({
            label: g.label,
            n: document.querySelectorAll(`.${g.cls}:checked`).length,
        }));
        const total = counts.reduce((sum, c) => sum + c.n, 0);

        if (summaryEl) {
            summaryEl.innerHTML = total
                ? counts.map(c => `${c.label}: <b>${c.n} unit</b>`).join(' &nbsp;&middot;&nbsp; ')
                : 'Belum ada unit yang dipilih';
        }
        if (pulseEl) pulseEl.classList.toggle('is-on', total > 0);
    };

    unitGroups.forEach(g => {
        const boxes = document.querySelectorAll(`.${g.cls}`);
        const emptyEl = document.getElementById(`${g.prefix}_empty`);

        const updateGroup = function() {
            const anyChecked = [...boxes].some(b => b.checked);
            if (emptyEl) emptyEl.style.display = anyChecked ? 'none' : '';
            refreshSummary();
        };

        boxes.forEach(box => {
            const card = document.getElementById(`${g.prefix}_${box.dataset.unit}_form`);
            const hasValue = card && [...card.querySelectorAll('select, input')].some(el => el.value !== '');
            if (hasValue && !box.checked) {
                box.checked = true;
                box.dispatchEvent(new Event('change'));
            }
            box.addEventListener('change', updateGroup);
        });

        updateGroup();
    });

    // ===== Area Tambahan: badge "Terisi/Opsional" + auto-buka mengikuti isi input =====
    document.querySelectorAll('details.fp-acc[data-area]').forEach(acc => {
        const badge  = acc.querySelector('.js-area-badge');
        const fields = acc.querySelectorAll('.js-area-input');

        const isFilled = function(el) {
            if (el.tagName === 'SELECT') {
                // "manual" dihitung lewat input manualnya
                return el.value !== '' && el.value !== 'manual';
            }
            // input manual yang sedang disembunyikan tidak dihitung
            if (el.classList.contains('fp-manual') && el.style.display === 'none') return false;
            return el.value.trim() !== '';
        };

        const syncAcc = function(autoOpen) {
            const filled = [...fields].some(isFilled);
            if (badge) {
                badge.textContent = filled ? 'Terisi' : 'Opsional';
                badge.classList.toggle('is-ok', filled);
                badge.classList.toggle('is-muted', !filled);
            }
        };

        fields.forEach(el => {
            el.addEventListener('input',  () => syncAcc(false));
            el.addEventListener('change', () => syncAcc(false));
        });

        // Saat halaman dimuat
        syncAcc(true);
    });
});
</script>

@endsection
