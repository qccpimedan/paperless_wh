@extends('layouts.app')
@section('container')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a>
    </header>

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

    /* ---------- Header ---------- */
    .fp-crumbs { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; font-size: 12px; color: var(--muted); margin-bottom: 8px; }
    .fp-crumbs a { color: var(--muted); text-decoration: none; }
    .fp-crumbs a:hover { color: var(--brand-600); }
    .fp-crumbs .sep { color: #cbd5e1; }
    .fp-crumbs .now { color: var(--brand-700); background: var(--brand-50); padding: 2px 10px; border-radius: 6px; font-weight: 600; }
    .fp-title { font-size: 26px; line-height: 1.2; font-weight: 700; color: var(--ink); letter-spacing: -.02em; margin: 0; }
    .fp-mode { display: inline-block; margin-left: 10px; padding: 3px 11px; border-radius: 999px; background: var(--brand-50); color: var(--brand-600); border: 1px solid var(--brand-100); font-size: 11px; font-weight: 600; letter-spacing: 0; vertical-align: middle; }
    .fp-sub { font-size: 12.5px; color: var(--muted); margin: 4px 0 24px; }

    /* ---------- Alert error ---------- */
    .fp-alert { display: flex; gap: 12px; padding: 14px 16px; margin-bottom: 20px; border: 1px solid #fecdd3; background: #fff1f2; color: #9f1239; border-radius: 12px; }
    .fp-alert svg { flex: none; width: 18px; height: 18px; margin-top: 1px; }
    .fp-alert strong { display: block; margin-bottom: 4px; font-size: 13px; }
    .fp-alert ul { margin: 0; padding-left: 16px; font-size: 12.5px; }

    /* ---------- Card ---------- */
    .fp-card { background: #fff; border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 1px 2px rgba(15,23,42,.04); padding: 24px; margin-bottom: 20px; }
    .fp-card-head { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; padding-bottom: 16px; margin-bottom: 20px; border-bottom: 1px solid var(--line-soft); }
    .fp-card-title { display: flex; align-items: center; gap: 12px; }
    .fp-card-title h2 { margin: 0; font-size: 15px; font-weight: 700; color: var(--ink); }
    .fp-card-title p { margin: 2px 0 0; font-size: 11.5px; color: var(--faint); }
    .fp-ico { flex: none; width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center; }
    .fp-ico svg { width: 18px; height: 18px; }
    .fp-ico.t-brand  { background: #eef6ff; color: #1456b8; }
    .fp-ico.t-sky    { background: #f0f9ff; color: #0284c7; }
    .fp-ico.t-amber  { background: #fffbeb; color: #d97706; }

    /* ---------- Grid & field ---------- */
    .fp-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px 20px; }
    .fp-grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 20px; }
    .fp-triple { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    @media (max-width: 991px) { .fp-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 767px) { .fp-triple, .fp-grid-2 { grid-template-columns: 1fr; } }
    @media (max-width: 575px) { .fp-grid { grid-template-columns: 1fr; } .fp-card { padding: 18px; } }

    .fp-label { display: flex; align-items: center; justify-content: space-between; gap: 4px; margin: 0 0 6px; font-size: 12px; font-weight: 600; color: #334155; }
    .fp-label .fp-req { color: var(--rose); font-weight: 700; }
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
    .fp-page .fp-input.is-invalid { border-color: #fb7185; background-image: none; }
    .fp-page select.fp-input.is-invalid { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M6 8l4 4 4-4' fill='none' stroke='%2394a3b8' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); }
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
    .fp-empty { margin: 0; padding: 20px 16px; text-align: center; font-size: 12.5px; color: var(--faint); border: 1.5px dashed var(--line); border-radius: 12px; background: #fafbfc; }
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

    /* ---------- Accordion area ---------- */
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
    .fp-acc-tools { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin: 4px 0 14px; }
    .fp-acc-hint { margin: 0; font-size: 11.5px; color: var(--faint); }

    /* ---------- Bar aksi bawah ---------- */
    .fp-bar { position: sticky; bottom: 12px; z-index: 20; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding: 12px 16px; margin-top: 4px; background: rgba(255,255,255,.92); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 -2px 6px rgba(15,23,42,.03), 0 10px 28px rgba(15,23,42,.1); }
    .fp-bar-status { display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--muted); }
    .fp-bar-status b { color: var(--text); font-weight: 600; }
    .fp-pulse { position: relative; width: 10px; height: 10px; border-radius: 50%; background: #cbd5e1; }
    .fp-pulse.is-on { background: #10b981; }
    .fp-pulse.is-on::after { content: ''; position: absolute; inset: 0; border-radius: 50%; background: #34d399; animation: fp-ping 1.6s cubic-bezier(0,0,.2,1) infinite; }
    @keyframes fp-ping { 75%, 100% { transform: scale(2.4); opacity: 0; } }
    .fp-bar-actions { display: flex; align-items: center; gap: 10px; }
    .fp-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 38px; padding: 0 18px; font: inherit; font-size: 12.5px; font-weight: 600; text-decoration: none; border: 1px solid transparent; border-radius: 10px; cursor: pointer; transition: all .15s; }
    .fp-btn svg { width: 16px; height: 16px; }
    .fp-btn-ghost { color: var(--muted); background: transparent; }
    .fp-btn-ghost:hover { color: var(--ink); background: var(--line-soft); }
    .fp-btn-primary { color: #fff; background: var(--brand-600); box-shadow: 0 2px 8px rgba(20,86,184,.3); }
    .fp-btn-primary:hover { background: var(--brand-700); color: #fff; }
    .fp-btn-primary:active { transform: translateY(1px); }
    .fp-btn:focus-visible { outline: 2px solid var(--brand-500); outline-offset: 2px; }

    @media (prefers-reduced-motion: reduce) {
        .fp-unit, .fp-pulse.is-on::after { animation: none; }
        .fp-page * { transition: none !important; }
    }
</style>

<div class="fp-page">
    <header>
        <nav class="fp-crumbs" aria-label="breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a><span class="sep">/</span>
            <a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}">Pemeriksaan Suhu Ruang</a><span class="sep">/</span>
            <span class="now" aria-current="page">Buat Pemeriksaan</span>
        </nav>
        <h1 class="fp-title">Formulir Pemeriksaan Suhu Ruang <span class="fp-mode">Gudang Dry</span></h1>
        <p class="fp-sub">Gudang Dry &middot; pencatatan dan verifikasi kepatuhan suhu harian.</p>
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

    <form id="form-pemeriksaan-suhu-ruang-v3" data-autosave="true" class="form form-horizontal" action="{{ route('pemeriksaan-suhu-ruang-v3.store') }}" method="POST">
        @csrf

        {{-- ============ 1. INFORMASI UMUM ============ --}}
        <section class="fp-card">
            <div class="fp-card-head">
                <div class="fp-card-title">
                    <span class="fp-ico t-brand"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4m0-4h.01"/></svg></span>
                    <div>
                        <h2>1. Informasi Umum</h2>
                        <p>Pastikan tanggal, pukul, dan shift sesuai dengan waktu pemeriksaan.</p>
                    </div>
                </div>
            </div>

            <div class="fp-grid">
                <div>
                    <label class="fp-label" for="tanggal"><span>Tanggal <span class="fp-req">*</span></span></label>
                    <input type="date" id="tanggal" class="form-control fp-input @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')<div class="fp-error">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="fp-label" for="pukul"><span>Pukul <span class="fp-req">*</span></span></label>
                    <input type="time" id="pukul" class="form-control fp-input @error('pukul') is-invalid @enderror" name="pukul" value="{{ old('pukul') }}" required>
                    @error('pukul')<div class="fp-error">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="fp-label" for="id_shift"><span>Shift <span class="fp-req">*</span></span></label>
                    <select id="id_shift" class="form-control fp-input @error('id_shift') is-invalid @enderror" name="id_shift" required>
                        <option value="">-- Pilih Shift --</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('id_shift') == $shift->id ? 'selected' : '' }}>{{ $shift->shift }}</option>
                        @endforeach
                    </select>
                    @error('id_shift')<div class="fp-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>

        {{-- ============ 2. SUHU PER AREA ============ --}}
        @php
            $suhuFields = [
                'suhu_premix' => 'Suhu Premix',
                'suhu_seasoning' => 'Suhu Seasoning',
                'suhu_dry' => 'Suhu Dry',
                'suhu_cassing' => 'Suhu Cassing',
                'suhu_beef' => 'Suhu Beef',
                'suhu_packaging' => 'Suhu Packaging',
                'suhu_ruang_chemical' => 'Suhu Ruang Chemical',
                'suhu_ruang_seasoning' => 'Suhu Ruang Seasoning'
            ];
        @endphp
        <section class="fp-card is-brand">
            <div class="fp-card-head">
                <div class="fp-card-title">
                    <span class="fp-ico t-sky"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 14.76V3.5a2.5 2.5 0 00-5 0v11.26a4.5 4.5 0 105 0z"/></svg></span>
                    <div>
                        <h2>2. Suhu Per Area</h2>
                        <p>Buka area yang diperiksa, centang unit yang digunakan (1-4), lalu isi data suhunya.</p>
                    </div>
                </div>
            </div>

            @foreach($suhuFields as $fieldKey => $fieldLabel)
                <details class="fp-acc" id="{{ $fieldKey }}_acc">
                    <summary>
                        <span class="left">
                            <span>{{ $fieldLabel }} (1-4)</span>
                            <span class="fp-badge is-muted" data-area-badge="{{ $fieldKey }}">Belum diisi</span>
                        </span>
                        <svg class="chev" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8l4 4 4-4"/></svg>
                    </summary>
                    <div class="body">
                        <div class="fp-acc-tools">
                            <p class="fp-acc-hint">Isi sesuai dengan unit yang digunakan.</p>
                            <div class="fp-chips" role="group" aria-label="Pilih unit {{ $fieldLabel }}">
                                @for($i = 1; $i <= 4; $i++)
                                    <label class="fp-chip" for="{{ $fieldKey }}_{{ $i }}_check" title="{{ $fieldLabel }} {{ $i }}">
                                        <input class="{{ $fieldKey }}-checkbox" type="checkbox" id="{{ $fieldKey }}_{{ $i }}_check" data-unit="{{ $i }}">
                                        <span>Unit {{ $i }}</span>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <p class="fp-empty" id="{{ $fieldKey }}_empty">Belum ada unit dipilih. Centang Unit 1 &ndash; 4 di atas untuk mulai mengisi.</p>

                        @for($i = 1; $i <= 4; $i++)
                            @php $skey = $fieldKey . '_' . $i . '_setting'; @endphp
                            <div class="fp-unit {{ $fieldKey }}-unit" id="{{ $fieldKey }}_{{ $i }}_form" style="display: none;">
                                <div class="fp-unit-head">
                                    <span class="fp-dot"></span>
                                    <span class="fp-unit-name">{{ $fieldLabel }} {{ $i }}</span>
                                    <span class="fp-badge">Standar &le; 30&deg;C</span>
                                </div>
                                <div class="fp-triple">
                                    <div>
                                        <label class="fp-sublabel" for="{{ $skey }}">Setting (°C)</label>
                                        <select id="{{ $skey }}" class="form-select form-select-sm fp-input" name="{{ $skey }}">
                                            <option value="">-- Pilih atau Isi Manual --</option>
                                            <option value="≤30" {{ old($skey) == '≤30' ? 'selected' : '' }}>Std ≤ 30°C</option>
                                            <option value="manual" {{ old($skey) && old($skey) != '≤30' ? 'selected' : '' }}>Input Manual</option>
                                        </select>
                                        <input type="text" inputmode="text" id="{{ $skey }}_manual"
                                            class="form-control form-control-sm fp-input fp-manual" name="{{ $skey }}_manual"
                                            placeholder="Masukkan nilai manual"
                                            value="{{ old($skey) != '≤30' ? old($skey) : '' }}"
                                            style="display: {{ old($skey) && old($skey) != '≤30' ? 'block' : 'none' }};">
                                    </div>
                                    <div>
                                        <label class="fp-sublabel" for="{{ $fieldKey }}_{{ $i }}_display">Display (°C)</label>
                                        <input type="text" inputmode="text" id="{{ $fieldKey }}_{{ $i }}_display" class="form-control form-control-sm fp-input" name="{{ $fieldKey }}_{{ $i }}_display" placeholder="Masukkan nilai" value="{{ old($fieldKey . '_' . $i . '_display') }}">
                                    </div>
                                    <div>
                                        <label class="fp-sublabel" for="{{ $fieldKey }}_{{ $i }}_actual">Actual (°C)</label>
                                        <input type="text" inputmode="text" id="{{ $fieldKey }}_{{ $i }}_actual" class="form-control form-control-sm fp-input" name="{{ $fieldKey }}_{{ $i }}_actual" placeholder="Masukkan nilai" value="{{ old($fieldKey . '_' . $i . '_actual') }}">
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </details>
            @endforeach
        </section>

        {{-- ============ 3. CATATAN ============ --}}
        <section class="fp-card">
            <div class="fp-card-head">
                <div class="fp-card-title">
                    <span class="fp-ico t-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/></svg></span>
                    <div>
                        <h2>3. Catatan</h2>
                        <p>Tuliskan kondisi khusus di lapangan dan tindakan koreksi bila ada penyimpangan.</p>
                    </div>
                </div>
            </div>
            <div class="fp-grid-2">
                <div>
                    <label class="fp-label" for="keterangan"><span>Keterangan</span></label>
                    <textarea id="keterangan" class="form-control fp-input" name="keterangan" rows="4">{{ old('keterangan') }}</textarea>
                </div>
                <div>
                    <label class="fp-label" for="tindakan_koreksi"><span>Tindakan Koreksi</span></label>
                    <textarea id="tindakan_koreksi" class="form-control fp-input" name="tindakan_koreksi" rows="4">{{ old('tindakan_koreksi') }}</textarea>
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
                <a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}" class="fp-btn fp-btn-ghost btn-kembali-confirm">Kembali</a>
                <button type="submit" class="fp-btn fp-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                    Buat Pemeriksaan
                </button>
            </div>
        </div>
    </form>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
            el.addEventListener('click', function(e) {
                const ok = confirm('Data belum disimpan. Yakin ingin kembali ke halaman index?');
                if (!ok) e.preventDefault();
            });
        });

        const fields = ['suhu_premix', 'suhu_seasoning', 'suhu_dry', 'suhu_cassing', 'suhu_beef', 'suhu_packaging', 'suhu_ruang_chemical', 'suhu_ruang_seasoning'];
        fields.forEach(field => {
            document.querySelectorAll(`.${field}-checkbox`).forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const unit = this.dataset.unit;
                    const form = document.getElementById(`${field}_${unit}_form`);
                    form.style.display = this.checked ? 'block' : 'none';
                });
            });

            // Toggle input manual for setting dropdown
            for (let i = 1; i <= 4; i++) {
                const settingSelect = document.getElementById(`${field}_${i}_setting`);
                const settingManual = document.getElementById(`${field}_${i}_setting_manual`);
                if (settingSelect && settingManual) {
                    settingSelect.addEventListener('change', function() {
                        settingManual.style.display = (this.value === 'manual') ? 'block' : 'none';
                        if (this.value !== 'manual') settingManual.value = '';
                    });
                }
            }
        });

        /* ============================================================
           Tambahan khusus tampilan (tidak menyentuh data/backend):
           - badge jumlah unit aktif di tiap area
           - empty state saat belum ada unit dipilih
           - ringkasan di bar bawah
           - unit yang sudah terisi (mis. setelah validasi gagal)
             otomatis dicentang dan areanya dibuka
           ============================================================ */
        const summaryEl = document.getElementById('fpUnitSummary');
        const pulseEl = document.getElementById('fpPulse');

        const refreshSummary = function() {
            let areas = 0, units = 0;
            fields.forEach(field => {
                const n = document.querySelectorAll(`.${field}-checkbox:checked`).length;
                if (n > 0) areas++;
                units += n;
            });
            if (summaryEl) {
                summaryEl.innerHTML = units
                    ? `Area aktif: <b>${areas} dari ${fields.length}</b> &nbsp;&middot;&nbsp; Total unit: <b>${units}</b>`
                    : 'Belum ada unit yang dipilih';
            }
            if (pulseEl) pulseEl.classList.toggle('is-on', units > 0);
        };

        fields.forEach(field => {
            const boxes = document.querySelectorAll(`.${field}-checkbox`);
            const emptyEl = document.getElementById(`${field}_empty`);
            const badgeEl = document.querySelector(`[data-area-badge="${field}"]`);
            const accEl = document.getElementById(`${field}_acc`);

            const updateArea = function() {
                const n = [...boxes].filter(b => b.checked).length;
                if (emptyEl) emptyEl.style.display = n ? 'none' : '';
                if (badgeEl) {
                    badgeEl.textContent = n ? `${n} unit aktif` : 'Belum diisi';
                    badgeEl.classList.toggle('is-ok', n > 0);
                    badgeEl.classList.toggle('is-muted', n === 0);
                }
                refreshSummary();
            };

            boxes.forEach(box => {
                const card = document.getElementById(`${field}_${box.dataset.unit}_form`);
                const hasValue = card && [...card.querySelectorAll('select, input')].some(el => el.value !== '');
                if (hasValue && !box.checked) {
                    box.checked = true;
                    box.dispatchEvent(new Event('change'));
                }
                box.addEventListener('change', updateArea);
            });

            updateArea();
            if (accEl && [...boxes].some(b => b.checked)) accEl.open = true;
        });
    });
</script>
@endsection