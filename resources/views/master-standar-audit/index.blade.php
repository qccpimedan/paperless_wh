@extends('layouts.app')

@section('title', 'Master Standar Audit')

@section('container')
<div id="main">
    <div class="page-heading">

        {{-- Header --}}
        <div class="msa-header">
            <div>
                <h3 class="msa-title">Master Standar Audit</h3>
                <p class="text-muted mb-0">Atur aturan koreksi otomatis untuk data audit QC System.</p>
            </div>
            <div class="msa-header-side">
                <div class="msa-stat">
                    <span class="msa-stat-num">{{ $standars->total() }}</span>
                    <span class="msa-stat-label">aturan{{ $selectedModule ? ' di modul ini' : ' tersimpan' }}</span>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Master Standar Audit</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show msa-alert" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show msa-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show msa-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Periksa kembali isian Anda:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        <div class="row g-4">

            {{-- LEFT: batch form --}}
            <div class="col-xl-5">
                <div class="msa-panel msa-sticky">
                    <div class="msa-panel-head">
                        <div>
                            <h5>Tambah aturan</h5>
                            <p>Pilih modul, centang field, lalu isi standarnya sekaligus.</p>
                        </div>
                    </div>

                    <form id="batchAuditForm" action="{{ route('master-standar-audit.store') }}" method="POST">
                        @csrf

                        <div class="msa-panel-body">
                            <label for="moduleSelect" class="form-label fw-semibold">
                                Modul QC <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="moduleSelect" name="module_name" required>
                                <option value="">Pilih modul</option>
                                @foreach($modules as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>

                            {{-- Empty --}}
                            <div id="emptyState" class="msa-empty">
                                <i class="bi bi-ui-checks-grid"></i>
                                <p>Field yang dapat diaudit akan muncul di sini setelah Anda memilih modul.</p>
                            </div>

                            {{-- Loading --}}
                            <div id="loadingState" class="msa-empty" style="display:none;">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Memuat</span>
                                </div>
                                <p>Memuat field…</p>
                            </div>

                            {{-- Columns --}}
                            <div id="columnCardsContainer" style="display:none;">
                                <div class="msa-toolbar">
                                    <div class="msa-search">
                                        <i class="bi bi-search"></i>
                                        <input type="search" id="columnSearch" class="form-control form-control-sm" placeholder="Cari field" aria-label="Cari field">
                                    </div>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Pilih semua">
                                        <button type="button" class="btn btn-outline-primary" id="checkAllBtn">Pilih semua</button>
                                        <button type="button" class="btn btn-outline-secondary" id="uncheckAllBtn">Kosongkan</button>
                                    </div>
                                </div>

                                <div id="columnCards" class="column-cards-wrapper"></div>
                                <p id="noMatch" class="text-muted small text-center mt-2 mb-0" style="display:none;">Tidak ada field yang cocok.</p>
                            </div>
                        </div>

                        <div class="msa-panel-foot">
                            <span class="msa-count" id="selectedCount">Belum ada field dipilih</span>
                            <button type="submit" class="btn btn-primary" id="submitBatchBtn" disabled>
                                <i class="bi bi-save me-1"></i>Simpan aturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RIGHT: priority + table --}}
            <div class="col-xl-7">

                {{-- Priority ladder --}}
                <div class="msa-panel mb-4">
                    <div class="msa-panel-head">
                        <div>
                            <h5>Urutan prioritas koreksi otomatis</h5>
                            <p>Sistem mencoba aturan dari atas ke bawah dan berhenti di aturan pertama yang cocok.</p>
                        </div>
                    </div>
                    <div class="msa-ladder">
                        <div class="msa-step msa-step-1">
                            <span class="msa-step-no">1</span>
                            <div>
                                <strong>Khusus area</strong>
                                <p>Aturan dengan <code>area_id</code> yang sama dengan data aktual.</p>
                            </div>
                        </div>
                        <div class="msa-step msa-step-2">
                            <span class="msa-step-no">2</span>
                            <div>
                                <strong>Global tabel</strong>
                                <p>Aturan tanpa <code>area_id</code>, berlaku untuk semua area.</p>
                            </div>
                        </div>
                        <div class="msa-step msa-step-3">
                            <span class="msa-step-no">3</span>
                            <div>
                                <strong>Koreksi umum</strong>
                                <p>Kolom belum terdaftar: status NOT OK / NG diubah menjadi OK.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Rules table --}}
                <div class="msa-panel">
                    <div class="msa-panel-head">
                        <div>
                            <h5>Daftar aturan audit</h5>
                            <p>Edit atau hapus aturan yang sudah tersimpan.</p>
                        </div>
                    </div>

                    <div class="msa-panel-body pb-0">
                        <form method="GET" action="{{ route('master-standar-audit.index') }}" class="row g-2 align-items-end mb-3" id="filterForm">
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Modul</label>
                                <select name="module" class="form-select form-select-sm" id="filterModuleSelect">
                                    <option value="">Semua modul</option>
                                    @foreach($modules as $key => $label)
                                        <option value="{{ $key }}" {{ $selectedModule == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3 col-6">
                                <label class="form-label small text-muted mb-1">Baris per halaman</label>
                                <select name="per_page" class="form-select form-select-sm" id="filterPerPageSelect">
                                    @foreach([5, 10, 25, 50] as $n)
                                        <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3 col-6">
                                @if($selectedModule)
                                    <a href="{{ route('master-standar-audit.index') }}" class="btn btn-sm btn-light border w-100">
                                        <i class="bi bi-x-lg me-1"></i>Hapus filter
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table msa-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width:5%">#</th>
                                    <th>Field</th>
                                    <th>Tipe</th>
                                    <th>Area</th>
                                    <th>Batas / target</th>
                                    <th class="text-end pe-4" style="width:1%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($standars as $index => $standar)
                                    <tr>
                                        <td class="ps-4 text-muted">{{ $standars->firstItem() + $index }}</td>
                                        <td>
                                            <code class="msa-col">{{ $standar->column_name }}</code>
                                            <div class="msa-sub">{{ $standar->module_name }}</div>
                                        </td>
                                        <td>
                                            <span class="msa-type msa-type-{{ $standar->parameter_type }}">{{ $standar->getParameterTypeLabel() }}</span>
                                        </td>
                                        <td>
                                            @if($standar->area)
                                                <span class="msa-area"><i class="bi bi-geo-alt-fill"></i>{{ $standar->area->nama_area }}</span>
                                            @else
                                                <span class="msa-area msa-area-global"><i class="bi bi-globe2"></i>Global</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!is_null($standar->min_value) || !is_null($standar->max_value))
                                                <span class="msa-range">{{ $standar->min_value ?? '–' }}<span class="msa-range-sep">sampai</span>{{ $standar->max_value ?? '–' }}</span>
                                            @endif
                                            @if(!is_null($standar->standar_value) && $standar->standar_value !== '')
                                                <div class="msa-target">Target: {{ $standar->standar_value }}</div>
                                            @endif
                                            @if(is_null($standar->min_value) && is_null($standar->max_value) && (is_null($standar->standar_value) || $standar->standar_value === ''))
                                                <span class="text-muted">–</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4 text-nowrap">
                                            <button type="button" class="btn btn-sm btn-icon btn-light border"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $standar->id }}"
                                                    title="Edit aturan" aria-label="Edit aturan {{ $standar->column_name }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('master-standar-audit.destroy', $standar->uuid) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Hapus aturan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon btn-light border text-danger"
                                                        title="Hapus aturan" aria-label="Hapus aturan {{ $standar->column_name }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="msa-empty py-5">
                                                <i class="bi bi-inbox"></i>
                                                <p>Belum ada aturan audit. Pilih modul di panel kiri untuk menambahkannya.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($standars->hasPages())
                        <div class="msa-panel-body">
                            {{ $standars->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit modals (di luar tabel agar HTML valid) --}}
@foreach($standars as $standar)
    <div class="modal fade" id="editModal{{ $standar->id }}" tabindex="-1" aria-labelledby="editTitle{{ $standar->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content msa-modal">
                <form action="{{ route('master-standar-audit.update', $standar->uuid) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title" id="editTitle{{ $standar->id }}">Edit aturan</h5>
                            <div class="msa-sub"><code class="msa-col">{{ $standar->column_name }}</code> · {{ $standar->module_name }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Area</label>
                                <select name="area_id" class="form-select">
                                    <option value="">Global (semua area)</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ $standar->area_id == $area->id ? 'selected' : '' }}>{{ $area->nama_area }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipe parameter</label>
                                <select name="parameter_type" class="form-select" required>
                                    <option value="suhu" {{ $standar->parameter_type == 'suhu' ? 'selected' : '' }}>Suhu Produk</option>
                                    <option value="suhu_ruang" {{ $standar->parameter_type == 'suhu_ruang' ? 'selected' : '' }}>Suhu Ruangan</option>
                                    <option value="suhu_kondisi" {{ $standar->parameter_type == 'suhu_kondisi' ? 'selected' : '' }}>Suhu Kondisi Khusus</option>
                                    <option value="suhu_pusat" {{ $standar->parameter_type == 'suhu_pusat' ? 'selected' : '' }}>Suhu Pusat Produk</option>
                                    <option value="suhu_pemasakan" {{ $standar->parameter_type == 'suhu_pemasakan' ? 'selected' : '' }}>Suhu Pemasakan</option>
                                    <option value="status_checklist" {{ $standar->parameter_type == 'status_checklist' ? 'selected' : '' }}>Status Checklist</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Min</label>
                                <input type="number" step="0.01" name="min_value" class="form-control" value="{{ $standar->min_value }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Max</label>
                                <input type="number" step="0.01" name="max_value" class="form-control" value="{{ $standar->max_value }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Target</label>
                                <input type="text" name="standar_value" class="form-control" value="{{ $standar->standar_value }}">
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="auto_fix_status" id="autoFix{{ $standar->id }}" {{ $standar->auto_fix_status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="autoFix{{ $standar->id }}">
                                        Ubah status NOT OK menjadi OK secara otomatis
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<style>
/* ---------- Tokens ---------- */
#main {
    --msa-radius: 10px;
    --msa-line: var(--bs-border-color, #e3e6ec);
    --msa-soft: var(--bs-tertiary-bg, #f5f7fa);
    --msa-muted: var(--bs-secondary-color, #6c757d);
    --msa-accent: #1f6feb;
    --msa-p1: #b45309;
    --msa-p2: #2563eb;
    --msa-p3: #6b7280;
}

/* ---------- Header ---------- */
.msa-header { display: flex; justify-content: space-between; align-items: flex-end; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
.msa-title { font-weight: 700; letter-spacing: -.01em; margin-bottom: .25rem; }
.msa-header-side { display: flex; flex-direction: column; align-items: flex-end; gap: .5rem; }
.msa-stat { display: flex; align-items: baseline; gap: .5rem; }
.msa-stat-num { font-size: 2rem; font-weight: 700; line-height: 1; font-variant-numeric: tabular-nums; }
.msa-stat-label { color: var(--msa-muted); font-size: .875rem; }

/* ---------- Alerts ---------- */
.msa-alert { display: flex; align-items: flex-start; gap: .75rem; border: 0; border-left: 4px solid currentColor; border-radius: var(--msa-radius); }
.msa-alert > i { margin-top: .15rem; }
.msa-alert > div { flex: 1; }

/* ---------- Panels ---------- */
.msa-panel { background: var(--bs-body-bg, #fff); border: 1px solid var(--msa-line); border-radius: var(--msa-radius); overflow: hidden; }
.msa-panel-head { padding: 1rem 1.25rem; border-bottom: 1px solid var(--msa-line); }
.msa-panel-head h5 { margin: 0; font-size: 1.05rem; font-weight: 600; }
.msa-panel-head p { margin: .15rem 0 0; font-size: .85rem; color: var(--msa-muted); }
.msa-panel-body { padding: 1.25rem; }
.msa-panel-foot { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: .85rem 1.25rem; border-top: 1px solid var(--msa-line); background: var(--msa-soft); }
.msa-count { font-size: .85rem; color: var(--msa-muted); }
.msa-count.has-items { color: var(--bs-body-color); font-weight: 600; }
.msa-sticky { position: sticky; top: 80px; }

/* ---------- Empty / loading ---------- */
.msa-empty { text-align: center; color: var(--msa-muted); padding: 2rem 1rem; }
.msa-empty i { font-size: 2rem; display: block; margin-bottom: .5rem; opacity: .6; }
.msa-empty p { margin: 0 auto; max-width: 28ch; font-size: .875rem; }
td .msa-empty p { max-width: 40ch; }

/* ---------- Column picker ---------- */
.msa-toolbar { display: flex; gap: .5rem; align-items: center; justify-content: space-between; flex-wrap: wrap; margin: 1.25rem 0 .75rem; }
.msa-search { position: relative; flex: 1 1 140px; }
.msa-search i { position: absolute; left: .65rem; top: 50%; transform: translateY(-50%); color: var(--msa-muted); font-size: .8rem; pointer-events: none; }
.msa-search input { padding-left: 2rem; }

.column-cards-wrapper { max-height: 460px; overflow-y: auto; margin: 0 -.25rem; padding: .25rem; }
.column-card { border: 1px solid var(--msa-line); border-radius: 8px; padding: .75rem .9rem; margin-bottom: .6rem; background: var(--bs-body-bg, #fff); transition: border-color .15s, background-color .15s; }
.column-card.is-on { border-color: var(--msa-accent); background: color-mix(in srgb, var(--msa-accent) 5%, var(--bs-body-bg, #fff)); }
.column-card .msa-fields { display: none; margin-top: .75rem; padding-top: .75rem; border-top: 1px dashed var(--msa-line); }
.column-card.is-on .msa-fields { display: block; }
.column-card .form-check-label { cursor: pointer; }
.column-card .msa-key { display: block; font-size: .75rem; color: var(--msa-muted); font-weight: 400; }
.column-card[hidden] { display: none; }

/* ---------- Priority ladder ---------- */
.msa-ladder { display: grid; grid-template-columns: repeat(3, 1fr); }
.msa-step { display: flex; gap: .75rem; padding: 1rem 1.25rem; position: relative; }
.msa-step + .msa-step { border-left: 1px solid var(--msa-line); }
.msa-step strong { display: block; font-size: .95rem; }
.msa-step p { margin: .2rem 0 0; font-size: .8rem; color: var(--msa-muted); line-height: 1.45; }
.msa-step-no { flex: none; width: 1.75rem; height: 1.75rem; border-radius: 50%; display: grid; place-items: center; font-size: .85rem; font-weight: 700; color: #fff; }
.msa-step-1 .msa-step-no { background: var(--msa-p1); }
.msa-step-2 .msa-step-no { background: var(--msa-p2); }
.msa-step-3 .msa-step-no { background: var(--msa-p3); }

/* ---------- Table ---------- */
.msa-table thead th { background: var(--msa-soft); font-size: .78rem; font-weight: 600; color: var(--msa-muted); border-bottom: 1px solid var(--msa-line); padding-top: .65rem; padding-bottom: .65rem; white-space: nowrap; }
.msa-table tbody td { padding-top: .8rem; padding-bottom: .8rem; border-color: var(--msa-line); }
.msa-table tbody tr:hover { background: var(--msa-soft); }
.msa-col { color: var(--bs-body-color); background: transparent; padding: 0; font-weight: 600; font-size: .85rem; }
.msa-sub { font-size: .75rem; color: var(--msa-muted); margin-top: .1rem; }

.msa-type { display: inline-block; padding: .2rem .55rem; border-radius: 999px; font-size: .75rem; font-weight: 600; white-space: nowrap; background: #eef2ff; color: #3730a3; }
.msa-type-suhu            { background: #e0f2fe; color: #075985; }
.msa-type-suhu_ruang      { background: #dcfce7; color: #166534; }
.msa-type-suhu_kondisi    { background: #fef3c7; color: #92400e; }
.msa-type-suhu_pusat      { background: #ede9fe; color: #5b21b6; }
.msa-type-suhu_pemasakan  { background: #ffe4e6; color: #9f1239; }
.msa-type-status_checklist{ background: #e5e7eb; color: #374151; }

.msa-area { display: inline-flex; align-items: center; gap: .3rem; font-size: .8rem; font-weight: 600; color: var(--msa-p1); }
.msa-area-global { color: var(--msa-muted); font-weight: 500; }

.msa-range { font-variant-numeric: tabular-nums; font-weight: 600; font-size: .85rem; }
.msa-range-sep { font-weight: 400; color: var(--msa-muted); font-size: .72rem; margin: 0 .4rem; }
.msa-target { font-size: .75rem; color: #15803d; margin-top: .1rem; }

.btn-icon { width: 2rem; height: 2rem; padding: 0; display: inline-grid; place-items: center; }

/* ---------- Modal ---------- */
.msa-modal { border: 0; border-radius: 12px; }
.msa-modal .modal-header { align-items: flex-start; border-bottom: 1px solid var(--msa-line); }

/* ---------- Responsive ---------- */
@media (max-width: 1199px) {
    .msa-sticky { position: static; }
}
@media (max-width: 767px) {
    .msa-header-side { align-items: flex-start; }
    .msa-ladder { grid-template-columns: 1fr; }
    .msa-step + .msa-step { border-left: 0; border-top: 1px solid var(--msa-line); }
}
@media (prefers-reduced-motion: reduce) {
    .column-card { transition: none; }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const moduleSelect = document.getElementById('moduleSelect');
    const container = document.getElementById('columnCardsContainer');
    const cardsWrap = document.getElementById('columnCards');
    const loadingState = document.getElementById('loadingState');
    const emptyState = document.getElementById('emptyState');
    const submitBtn = document.getElementById('submitBatchBtn');
    const checkAllBtn = document.getElementById('checkAllBtn');
    const uncheckAllBtn = document.getElementById('uncheckAllBtn');
    const searchInput = document.getElementById('columnSearch');
    const noMatch = document.getElementById('noMatch');
    const countLabel = document.getElementById('selectedCount');

    // Filter form auto-submit handlers
    const filterModuleSelect = document.getElementById('filterModuleSelect');
    const filterPerPageSelect = document.getElementById('filterPerPageSelect');
    const filterForm = document.getElementById('filterForm');

    if (filterModuleSelect && filterForm) {
        filterModuleSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }

    if (filterPerPageSelect && filterForm) {
        filterPerPageSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }

    const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));

    const types = [
        ['suhu', 'Suhu Produk'],
        ['suhu_ruang', 'Suhu Ruangan'],
        ['suhu_kondisi', 'Suhu Kondisi Khusus'],
        ['suhu_pusat', 'Suhu Pusat'],
        ['suhu_pemasakan', 'Suhu Pemasakan'],
        ['status_checklist', 'Status Checklist'],
    ];

    function updateCount() {
        const n = cardsWrap.querySelectorAll('.column-checkbox:checked').length;
        countLabel.textContent = n ? `${n} field dipilih` : 'Belum ada field dipilih';
        countLabel.classList.toggle('has-items', n > 0);
        submitBtn.disabled = n === 0;
    }

    function showEmpty() {
        container.style.display = 'none';
        emptyState.style.display = 'block';
        cardsWrap.innerHTML = '';
        updateCount();
    }

    moduleSelect.addEventListener('change', async function () {
        const selectedModule = this.value;
        searchInput.value = '';
        noMatch.style.display = 'none';

        if (!selectedModule) { showEmpty(); return; }

        emptyState.style.display = 'none';
        container.style.display = 'none';
        loadingState.style.display = 'block';

        try {
            const response = await fetch(`{{ route('master-standar-audit.get-columns') }}?module=${encodeURIComponent(selectedModule)}`);
            const data = await response.json();

            if (data.success && data.columns.length > 0) {
                renderColumnCards(data.columns);
                container.style.display = 'block';
            } else {
                alert('Tidak ada field yang dapat diaudit untuk modul ini.');
                showEmpty();
            }
        } catch (error) {
            console.error('Error fetching columns:', error);
            alert('Gagal memuat field. Silakan coba lagi.');
            showEmpty();
        } finally {
            loadingState.style.display = 'none';
        }
    });

    function setCardState(card, on) {
        card.classList.toggle('is-on', on);
        card.querySelectorAll('.msa-fields input, .msa-fields select, input[type=hidden]')
            .forEach((el) => { el.disabled = !on; });
    }

    function renderColumnCards(columns) {
        cardsWrap.innerHTML = '';

        columns.forEach((column, index) => {
            const card = document.createElement('div');
            card.className = 'column-card';
            card.dataset.search = `${column.label} ${column.name}`.toLowerCase();

            const options = types.map(([val, label]) =>
                `<option value="${val}" ${column.type === val ? 'selected' : ''}>${label}</option>`
            ).join('');

            card.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input column-checkbox" type="checkbox"
                           name="items[${index}][enabled]" id="col_${index}" value="1">
                    <label class="form-check-label fw-semibold" for="col_${index}">
                        ${esc(column.label)}
                        <span class="msa-key">${esc(column.name)}</span>
                    </label>
                </div>
                <input type="hidden" name="items[${index}][column_name]" value="${esc(column.name)}">
                <input type="hidden" name="items[${index}][table_name]" value="${esc(column.table_name)}">

                <div class="msa-fields">
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label small mb-1">Tipe parameter</label>
                            <select name="items[${index}][parameter_type]" class="form-select form-select-sm" required>${options}</select>
                        </div>
                        <div class="col-4">
                            <label class="form-label small mb-1">Min</label>
                            <input type="number" step="0.01" name="items[${index}][min_value]" class="form-control form-control-sm" placeholder="-20">
                        </div>
                        <div class="col-4">
                            <label class="form-label small mb-1">Max</label>
                            <input type="number" step="0.01" name="items[${index}][max_value]" class="form-control form-control-sm" placeholder="-15">
                        </div>
                        <div class="col-4">
                            <label class="form-label small mb-1">Target</label>
                            <input type="text" name="items[${index}][standar_value]" class="form-control form-control-sm" placeholder="OK">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" name="items[${index}][auto_fix_status]" id="auto_${index}" value="1" checked>
                                <label class="form-check-label small" for="auto_${index}">Ubah NOT OK menjadi OK / Ceklis otomatis</label>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            cardsWrap.appendChild(card);

            card.querySelector('.column-checkbox').addEventListener('change', function () {
                setCardState(card, this.checked);
                updateCount();
            });

            setCardState(card, false);
        });

        updateCount();
    }

    // Search fields
    searchInput.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        let visible = 0;
        cardsWrap.querySelectorAll('.column-card').forEach((card) => {
            const match = !q || card.dataset.search.includes(q);
            card.hidden = !match;
            if (match) visible++;
        });
        noMatch.style.display = visible ? 'none' : 'block';
    });

    // Check / uncheck (hanya field yang terlihat)
    function toggleVisible(state) {
        cardsWrap.querySelectorAll('.column-card:not([hidden]) .column-checkbox').forEach((cb) => {
            cb.checked = state;
            cb.dispatchEvent(new Event('change'));
        });
    }
    checkAllBtn.addEventListener('click', () => toggleVisible(true));
    uncheckAllBtn.addEventListener('click', () => toggleVisible(false));
});
</script>
@endpush
@endsection