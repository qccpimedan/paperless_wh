@extends('layouts.app')

@section('container')
<div id="main">
    {{-- =========================================================
        HEADER
    ========================================================== --}}


    {{-- =========================================================
        PAGE HEADING
    ========================================================== --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Traceability</h3>
                    <p class="text-subtitle text-muted">
                        Penelusuran nama produk & kode produksi / batch lintas modul QC
                    </p>
                </div>

                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Traceability
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- =====================================================
            ERROR
        ====================================================== --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- =====================================================
            MAIN SECTION
        ====================================================== --}}
        <section class="section">
            <div class="card traceability-card">
                <div class="card-body">

                    {{-- =================================================
                        SEARCH
                    ================================================== --}}
                    <div class="trace-search-wrapper">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <form action="{{ route('traceability.search') }}" method="GET" id="traceabilitySearchForm">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input
                                            type="text"
                                            name="production_code"
                                            id="productionCodeInput"
                                            class="form-control"
                                            placeholder="Masukkan nama produk atau kode produksi..."
                                            value="{{ $production_code ?? '' }}"
                                            autofocus
                                            required
                                            minlength="2"
                                            autocomplete="off"
                                        >
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search me-1"></i> Telusuri
                                        </button>
                                        <button type="button" id="resetBtn" class="btn btn-secondary">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                        </button>
                                    </div>

                                    {{-- Search hint --}}
                                    <div class="search-hint">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <strong>Tips pencarian:</strong>
                                        <br>
                                        • <strong>Kode produksi:</strong> <code>QE19601AA0</code>
                                        <br>
                                        • <strong>Nama produk:</strong> <code>Fiesta Tepung Bumbu Bakwan Renceng</code> (lengkap/sebagian)
                                        <br>
                                        • <strong>Gabungan nama + kode:</strong> <code>Fiesta Tepung Bumbu Bakwan Renceng QE19601AA0</code>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- =================================================
                        HASIL PENCARIAN
                    ================================================== --}}
                    @if(isset($results))
                        {{-- =============================================
                            EMPTY
                        ============================================== --}}
                        @if($results->isEmpty())
                            <div class="trace-empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-search"></i>
                                </div>
                                <h4>Tidak Ada Data Ditemukan</h4>
                                <p>
                                    Tidak ditemukan data dengan kata pencarian <strong>"{{ $production_code }}"</strong> di seluruh modul QC.
                                </p>
                                <button type="button" class="btn btn-primary" id="emptyResetBtn">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Coba Pencarian Lain
                                </button>
                            </div>
                        @else
                            {{-- =========================================
                                CALCULATE SUMMARY
                            ========================================== --}}
                            @php
                                $totalForms = $results->sum(function ($group) {
                                    return $group['forms']->count();
                                });
                                $totalModules = $results->count();
                                $allDates = collect();

                                foreach ($results as $group) {
                                    foreach ($group['forms'] as $form) {
                                        if (!empty($form['date'])) {
                                            try {
                                                $allDates->push(\Carbon\Carbon::parse($form['date'])->format('Y-m-d'));
                                            } catch (\Exception $e) {
                                                // Ignore invalid date
                                            }
                                        }
                                    }
                                }

                                $totalDates = $allDates->unique()->count();
                            @endphp

                            {{-- =========================================
                                RESULT SUMMARY
                            ========================================== --}}
                            <div class="trace-summary">
                                <div class="summary-header">
                                    <div>
                                        <div class="summary-title">
                                            <i class="bi bi-search me-1"></i> Hasil Traceability
                                        </div>
                                        <div class="summary-subtitle">
                                            Pencarian untuk: <strong>"{{ $production_code }}"</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="summary-grid">
                                    {{-- TOTAL DATA --}}
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="bi bi-search"></i>
                                        </div>
                                        <div>
                                            <div class="summary-number">{{ $totalForms }}</div>
                                            <div class="summary-label">Data Ditemukan</div>
                                        </div>
                                    </div>

                                    {{-- TOTAL MODULE --}}
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="bi bi-folder2-open"></i>
                                        </div>
                                        <div>
                                            <div class="summary-number">{{ $totalModules }}</div>
                                            <div class="summary-label">Modul</div>
                                        </div>
                                    </div>

                                    {{-- TOTAL DATE --}}
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="bi bi-calendar3"></i>
                                        </div>
                                        <div>
                                            <div class="summary-number">{{ $totalDates }}</div>
                                            <div class="summary-label">Tanggal</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- =========================================
                                FILTER
                            ========================================== --}}
                            <div class="trace-filter-card">
                                <div class="filter-header">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="bi bi-funnel me-1"></i> Filter Hasil
                                        </h6>
                                        <small class="text-muted">Gunakan filter untuk mempersempit hasil traceability.</small>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    {{-- MODULE --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Modul</label>
                                        <select id="moduleFilter" class="form-select">
                                            <option value="all">Semua Modul</option>
                                            @foreach($results as $index => $group)
                                                <option value="module-{{ $index }}">{{ $group['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- SHIFT --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Shift</label>
                                        <select id="shiftFilter" class="form-select">
                                            <option value="all">Semua Shift</option>
                                        </select>
                                    </div>

                                    {{-- SORT --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Urutan</label>
                                        <select id="sortFilter" class="form-select">
                                            <option value="desc">Terbaru → Terlama</option>
                                            <option value="asc">Terlama → Terbaru</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- RESULT COUNTER --}}
                                <div class="filter-result mt-3">
                                    <i class="bi bi-filter-circle me-1"></i> Menampilkan <strong id="visibleCount">{{ $totalForms }}</strong> dari <strong>{{ $totalForms }}</strong> data
                                </div>
                            </div>

                            {{-- =========================================
                                GLOBAL ACTION
                            ========================================== --}}
                            <div class="trace-global-action">
                                <div class="text-muted">
                                    <i class="bi bi-layout-text-sidebar-reverse me-1"></i> Hasil dikelompokkan berdasarkan modul QC
                                </div>
                            </div>

                            {{-- =========================================
                                MODULE RESULTS
                            ========================================== --}}
                            <div id="traceResults">
                                @foreach($results as $groupIndex => $group)
                                    @php
                                        $moduleId = 'module-' . $groupIndex;
                                    @endphp

                                    <div class="trace-module mb-4" data-module="{{ $moduleId }}">
                                        {{-- MODULE HEADER --}}
                                        <div class="module-header">
                                            <div class="module-title-wrapper">
                                                <i class="bi bi-folder2-open"></i>
                                                <h5 class="mb-0">{{ $group['label'] }}</h5>
                                                <span class="module-count">{{ $group['forms']->count() }} data</span>
                                            </div>
                                        </div>

                                        {{-- CARDS --}}
                                        <div class="row g-3 trace-card-container">
                                            @foreach($group['forms'] as $formIndex => $form)
                                                @php
                                                    $dateValue = '';
                                                    try {
                                                        if (!empty($form['date'])) {
                                                            $dateValue = \Carbon\Carbon::parse($form['date'])->format('Y-m-d');
                                                        }
                                                    } catch (\Exception $e) {
                                                        $dateValue = '';
                                                    }

                                                    $shiftValue = $form['shift'] ?? '';
                                                    $statusValue = '';

                                                    foreach ($form['fields'] as $label => $value) {
                                                        if (stripos($label, 'status') !== false && !empty($value)) {
                                                            $statusValue = trim((string) $value);
                                                            break;
                                                        }
                                                    }

                                                    $collapseId = 'trace-collapse-' . $groupIndex . '-' . $formIndex;
                                                    
                                                    $fieldsForCard = collect($form['fields']);
                                                    $visibleFieldsLimit = 4;
                                                    $visibleFieldsForCard = $fieldsForCard->take($visibleFieldsLimit);
                                                    $hiddenFieldsForCard = $fieldsForCard->slice($visibleFieldsLimit);
                                                @endphp

                                                <div class="col-md-6 col-lg-4 trace-result-item lazy-card" data-module="{{ $moduleId }}" data-shift="{{ $shiftValue }}" data-date="{{ $dateValue }}" data-index="{{ $formIndex }}">
                                                    <div class="trace-result-card" style="min-height: 200px;">
                                                        {{-- Content will be loaded by lazy loading --}}
                                                        <div class="lazy-card-content" style="display: none;">
                                                        {{-- CARD HEADER --}}
                                                        <div class="trace-card-header">
                                                            <div class="trace-card-title">
                                                                <div class="trace-date">
                                                                    <i class="bi bi-calendar3"></i>
                                                                    @if(!empty($form['date']))
                                                                        {{ \Carbon\Carbon::parse($form['date'])->translatedFormat('d F Y') }}
                                                                    @else
                                                                        Tanggal tidak tersedia
                                                                    @endif
                                                                </div>

                                                                @if($shiftValue)
                                                                    <div class="trace-shift">
                                                                        <i class="bi bi-clock"></i> Shift {{ $shiftValue }}
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="trace-card-actions">
                                                                {{-- PDF EXPORT BUTTON --}}
                                                                @if(!empty($form['pdf_export_url']))
                                                                    <a href="{{ $form['pdf_export_url'] }}" class="btn btn-sm btn-danger trace-pdf-btn" target="_blank" title="Cetak PDF">
                                                                        <i class="bi bi-file-earmark-break"></i>
                                                                    </a>
                                                                @endif
                                                                
                                                                {{-- DETAIL BUTTON --}}
                                                                @if(!empty($form['pdf_url']))
                                                                    <a href="{{ $form['pdf_url'] }}" class="btn btn-sm btn-primary trace-detail-btn" target="_blank" title="Buka detail">
                                                                        <i class="bi bi-box-arrow-up-right"></i>
                                                                    </a>
                                                                @endif

                                                                {{-- LIHAT DATA LAINNYA BUTTON --}}
                                                                @if($hiddenFieldsForCard->isNotEmpty())
                                                                    <button type="button" class="btn btn-sm btn-outline-primary trace-show-more-btn" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}" title="Lihat data lainnya">
                                                                        <i class="bi bi-chevron-down"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="trace-quick-info">
                                                            @foreach($visibleFieldsForCard as $label => $value)
                                                                @php
                                                                    $isProductionCode = stripos($label, 'kode produksi') !== false;
                                                                    $isStatus = stripos($label, 'status') !== false;
                                                                @endphp

                                                                <div class="trace-field">
                                                                    <span class="trace-label">{{ $label }}</span>
                                                                    <span class="trace-value">
                                                                        {{-- KODE PRODUKSI --}}
                                                                        @if($isProductionCode)
                                                                            <mark class="match-value">{{ is_array($value) ? implode(', ', $value) : $value }}</mark>
                                                                        {{-- STATUS --}}
                                                                        @elseif($isStatus && !empty($value))
                                                                            @php
                                                                                $statusValue = is_array($value) ? implode(', ', $value) : $value;
                                                                                $statusLower = strtolower(trim((string) $statusValue));
                                                                                $statusClass = 'status-default';

                                                                                if (str_contains($statusLower, 'release') || str_contains($statusLower, 'approve') || str_contains($statusLower, 'pass')) {
                                                                                    $statusClass = 'status-success';
                                                                                } elseif (str_contains($statusLower, 'hold') || str_contains($statusLower, 'pending')) {
                                                                                    $statusClass = 'status-warning';
                                                                                } elseif (str_contains($statusLower, 'reject') || str_contains($statusLower, 'fail')) {
                                                                                    $statusClass = 'status-danger';
                                                                                }
                                                                            @endphp

                                                                            <span class="status-badge {{ $statusClass }}">{{ $statusValue }}</span>
                                                                        @else
                                                                            {{ is_array($value) ? implode(', ', $value) : $value }}
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        {{-- QUICK INFO (SISANYA - BISA DI-COLLAPSE) --}}
                                                        @if($hiddenFieldsForCard->isNotEmpty())
                                                            <div class="collapse" id="{{ $collapseId }}">
                                                                <div class="trace-quick-info trace-quick-info-extra">
                                                                @foreach($hiddenFieldsForCard as $label => $value)
                                                                    @php
                                                                        $isProductionCode = stripos($label, 'kode produksi') !== false;
                                                                        $isStatus = stripos($label, 'status') !== false;
                                                                    @endphp

                                                                    <div class="trace-field">
                                                                        <span class="trace-label">{{ $label }}</span>
                                                                        <span class="trace-value">
                                                                            {{-- KODE PRODUKSI --}}
                                                                            @if($isProductionCode)
                                                                                <mark class="match-value">{{ is_array($value) ? implode(', ', $value) : $value }}</mark>
                                                                            {{-- STATUS --}}
                                                                            @elseif($isStatus && !empty($value))
                                                                                @php
                                                                                    $statusValue = is_array($value) ? implode(', ', $value) : $value;
                                                                                    $statusLower = strtolower(trim((string) $statusValue));
                                                                                    $statusClass = 'status-default';

                                                                                    if (str_contains($statusLower, 'release') || str_contains($statusLower, 'approve') || str_contains($statusLower, 'pass')) {
                                                                                        $statusClass = 'status-success';
                                                                                    } elseif (str_contains($statusLower, 'hold') || str_contains($statusLower, 'pending')) {
                                                                                        $statusClass = 'status-warning';
                                                                                    } elseif (str_contains($statusLower, 'reject') || str_contains($statusLower, 'fail')) {
                                                                                        $statusClass = 'status-danger';
                                                                                    }
                                                                                @endphp

                                                                                <span class="status-badge {{ $statusClass }}">{{ $statusValue }}</span>
                                                                            @else
                                                                                {{ is_array($value) ? implode(', ', $value) : $value }}
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                        </div>{{-- END lazy-card-content --}}
                                                        
                                                        {{-- LOADING PLACEHOLDER / SKELETON --}}
                                                        <div class="lazy-card-placeholder">
                                                            <div class="skeleton-wrapper">
                                                                <div class="skeleton skeleton-header"></div>
                                                                <div class="skeleton skeleton-line"></div>
                                                                <div class="skeleton skeleton-line short"></div>
                                                                <div class="skeleton skeleton-line"></div>
                                                                <div class="skeleton skeleton-button"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- MODULE EMPTY --}}
                                        <div class="module-empty d-none">
                                            <i class="bi bi-funnel"></i>
                                            <span>Tidak ada data yang sesuai dengan filter.</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- =========================================
                                NO FILTER RESULT
                            ========================================== --}}
                            <div id="noFilterResult" class="no-filter-result d-none">
                                <i class="bi bi-search"></i>
                                <h5>Tidak ada hasil yang sesuai</h5>
                                <p>Coba ubah filter modul atau shift.</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>

{{-- =============================================================
    STYLE
============================================================= --}}
<style>
    /* GENERAL */
    .traceability-card { border: 0; border-radius: 10px; overflow: hidden; }

    /* SEARCH */
    .trace-search-wrapper .input-group { border: 1px solid #d9dee8; border-radius: 8px; overflow: hidden; background: #fff; transition: all 0.2s ease; }
    .trace-search-wrapper .input-group:focus-within { border-color: #435ebe; box-shadow: 0 0 0 0.2rem rgba(67, 94, 190, 0.12); }
    .trace-search-wrapper .input-group-text { background: #fff; border: 0; color: #435ebe; }
    .trace-search-wrapper .form-control { border: 0; box-shadow: none !important; }
    .trace-search-wrapper .form-control:focus { border: 0; box-shadow: none !important; outline: none; }
    .search-hint { margin-top: 8px; font-size: 12px; color: #7c8798; }
    .search-example { margin-left: 5px; }

    /* SUMMARY */
    .trace-summary { margin-top: 20px; margin-bottom: 20px; padding: 18px; background: #f8faff; border: 1px solid #e7ecf5; border-radius: 10px; }
    .summary-header { margin-bottom: 15px; }
    .summary-title { font-size: 15px; font-weight: 600; color: #344054; }
    .summary-subtitle { margin-top: 3px; font-size: 12px; color: #8b95a7; }
    .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .summary-item { display: flex; align-items: center; gap: 12px; padding: 14px; background: #fff; border: 1px solid #e8edf5; border-radius: 8px; }
    .summary-icon { width: 42px; height: 42px; min-width: 42px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: #eef2ff; color: #435ebe; font-size: 18px; }
    .summary-number { font-size: 20px; line-height: 1; font-weight: 700; color: #344054; }
    .summary-label { margin-top: 5px; font-size: 11px; color: #98a2b3; }

    /* FILTER */
    .trace-filter-card { margin-bottom: 20px; padding: 16px; background: #fff; border: 1px solid #e5e9f2; border-radius: 9px; }
    .filter-header { display: flex; align-items: center; justify-content: space-between; gap: 15px; }
    .filter-header h6 { font-size: 14px; font-weight: 600; }
    .filter-header small { font-size: 11px; }
    .trace-filter-card .form-label { margin-bottom: 5px; font-size: 11px; font-weight: 600; color: #667085; }
    .trace-filter-card .form-select { font-size: 13px; }
    .filter-result { padding-top: 10px; border-top: 1px solid #edf0f5; font-size: 11px; color: #667085; }

    /* GLOBAL ACTION */
    .trace-global-action { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 15px; font-size: 11px; }

    /* MODULE */
    .trace-module { transition: all .2s ease; }
    .module-header { display: flex; align-items: center; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px solid #eef1f6; }
    .module-title-wrapper { display: flex; align-items: center; gap: 7px; }
    .module-title-wrapper > i { color: #435ebe; font-size: 14px; }
    .module-title-wrapper h5 { font-size: 14px; font-weight: 600; color: #344054; }
    .module-count { display: inline-flex; align-items: center; padding: 3px 8px; border-radius: 20px; background: #435ebe; color: #fff; font-size: 11px; font-weight: 600; }

    /* RESULT CARD */
    .trace-result-card { height: 100%; overflow: hidden; background: #fff; border: 1px solid #e7ebf2; border-left: 3px solid #435ebe; border-radius: 8px; box-shadow: 0 2px 7px rgba(16, 24, 40, .04); transition: all .2s ease; display: flex; flex-direction: column; }
    .trace-result-card:hover { transform: translateY(-2px); box-shadow: 0 7px 20px rgba(16, 24, 40, .08); }

    /* TRACE RESULT ITEM */
    .trace-result-item { align-self: start; }

    /* LAZY CARD CONTENT */
    .lazy-card-content { display: flex; flex-direction: column; height: 100%; }

    /* CARD HEADER */
    .trace-card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; padding: 12px; border-bottom: 1px solid #eef1f5; flex-shrink: 0; }
    .trace-card-title { flex: 1; }
    .trace-card-actions { display: flex; align-items: center; gap: 6px; }
    .trace-date { color: #435ebe; font-size: 13px; font-weight: 600; }
    .trace-date i { margin-right: 3px; }
    .trace-shift { margin-top: 3px; color: #98a2b3; font-size: 12px; }
    .trace-detail-btn, .trace-pdf-btn { flex-shrink: 0; padding: 4px 8px; font-size: 12px; }
    .trace-show-more-btn { flex-shrink: 0; padding: 4px 8px; font-size: 12px; margin-left: auto; }


    /* QUICK INFO */
    .trace-quick-info { padding: 12px; flex-grow: 1; overflow-y: auto; }
    .trace-field { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 8px; font-size: 12.5px; }
    .trace-field:last-child { margin-bottom: 0; }
    .trace-label { flex: 0 0 42%; color: #8b95a7; }
    .trace-value { max-width: 58%; color: #475467; font-weight: 500; text-align: right; word-break: break-word; }

    /* QUICK INFO - EXTRA FIELDS (DI DALAM COLLAPSE) */
    .trace-quick-info-extra { padding-top: 4px; border-top: 1px dashed #eef1f5; }

    /* COLLAPSE STYLING */
    .collapse { transition: all 0.3s ease; }
    .trace-result-card .collapse { display: none; }
    .trace-result-card .collapse.show { display: block; }

    /* MATCH */
    .match-value { display: inline-block; padding: 2px 5px; border-radius: 3px; background: #fff3cd; color: #664d03; font-weight: 700; }

    /* STATUS */
    .status-badge { display: inline-flex; align-items: center; justify-content: center; padding: 3px 7px; border-radius: 20px; font-size: 10.5px; font-weight: 700; text-transform: uppercase; }
    .status-success { background: #ecfdf3; color: #027a48; }
    .status-warning { background: #fffaeb; color: #b54708; }
    .status-danger { background: #fef3f2; color: #b42318; }
    .status-default { background: #f2f4f7; color: #475467; }

    /* EMPTY */
    .trace-empty-state { padding: 60px 20px; text-align: center; }
    .empty-icon { width: 70px; height: 70px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f2f4f7; color: #98a2b3; font-size: 28px; }
    .trace-empty-state h4 { color: #344054; font-size: 18px; }
    .trace-empty-state p { max-width: 550px; margin: 8px auto 20px; color: #98a2b3; font-size: 12px; }
    .module-empty { padding: 25px; text-align: center; color: #98a2b3; font-size: 11px; }
    .module-empty i { margin-right: 5px; }
    .no-filter-result { padding: 50px 20px; text-align: center; color: #98a2b3; }
    .no-filter-result > i { font-size: 35px; }
    .no-filter-result h5 { margin-top: 12px; color: #475467; }
    .no-filter-result p { font-size: 11px; }

    /* MOBILE */
    @media (max-width: 767px) {
        .summary-grid { grid-template-columns: 1fr; }
        .filter-header { align-items: flex-start; flex-direction: column; }
        .trace-global-action { align-items: flex-start; flex-direction: column; }
        .trace-global-action .d-flex { width: 100%; }
        .trace-global-action button { flex: 1; }
        .trace-card-header { padding: 10px; }
        .trace-quick-info { padding: 10px; }
        .trace-detail-btn { font-size: 10px; }
        .search-example { display: block; margin-top: 4px; margin-left: 0; }
    }

    /* PRINT */
    @media print {
        .trace-search-wrapper, .trace-filter-card, .trace-global-action, .trace-detail-btn { display: none !important; }
        .trace-result-card { break-inside: avoid; box-shadow: none !important; }
        .collapse { display: block !important; height: auto !important; }
    }

    /* LAZY LOADING */
    .lazy-card-placeholder {
        display: block;
        padding: 15px;
        min-height: 180px;
    }

    .lazy-card.loaded .lazy-card-placeholder {
        display: none;
    }

    .lazy-card.loaded .lazy-card-content {
        display: block !important;
    }

    .lazy-card-content {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* SKELETON LOADING */
    .skeleton-wrapper {
        width: 100%;
    }

    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 6px;
        margin-bottom: 12px;
    }

    .skeleton-header {
        height: 24px;
        width: 70%;
        margin-bottom: 16px;
    }

    .skeleton-line {
        height: 16px;
        width: 100%;
    }

    .skeleton-line.short {
        width: 60%;
    }

    .skeleton-button {
        height: 32px;
        width: 40%;
        margin-top: 16px;
        border-radius: 8px;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* Dark mode skeleton */
    @media (prefers-color-scheme: dark) {
        .skeleton {
            background: linear-gradient(90deg, #2a2a2a 25%, #3a3a3a 50%, #2a2a2a 75%);
            background-size: 200% 100%;
        }
    }
</style>

{{-- =============================================================
    JAVASCRIPT
============================================================= --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* ========================================
       LAZY LOADING SETUP
    ======================================== */
    const lazyCards = document.querySelectorAll('.lazy-card');
    let loadedCount = 0;
    const INITIAL_LOAD = 12; // Load first 12 cards immediately
    
    // Intersection Observer untuk lazy loading
    const observerOptions = {
        root: null,
        rootMargin: '100px', // Mulai load 100px sebelum card terlihat
        threshold: 0.01
    };

    const cardObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const card = entry.target;
                loadCard(card);
                observer.unobserve(card);
            }
        });
    }, observerOptions);

    function loadCard(card) {
        // Tampilkan content yang sudah ada di HTML
        const content = card.querySelector('.lazy-card-content');
        if (content) {
            // Tambahkan sedikit delay agar skeleton terlihat (opsional, bisa dihapus untuk production)
            setTimeout(function() {
                card.classList.add('loaded');
            }, 100); // 100ms delay untuk transisi smooth
        }
    }

    // Load card awal langsung (12 pertama)
    lazyCards.forEach(function(card, index) {
        if (index < INITIAL_LOAD) {
            loadCard(card);
        } else {
            // Card sisanya diobserve untuk lazy loading
            cardObserver.observe(card);
        }
    });

    /* ELEMENTS */
    const resetBtn = document.getElementById('resetBtn');
    const emptyResetBtn = document.getElementById('emptyResetBtn');
    const clearFilterBtn = document.getElementById('clearFilterBtn');
    const clearFilterBtn2 = document.getElementById('clearFilterBtn2');
    const searchInput = document.getElementById('productionCodeInput');
    const moduleFilter = document.getElementById('moduleFilter');
    const shiftFilter = document.getElementById('shiftFilter');
    const sortFilter = document.getElementById('sortFilter');
    const visibleCount = document.getElementById('visibleCount');
    const expandAllBtn = document.getElementById('expandAllBtn');
    const collapseAllBtn = document.getElementById('collapseAllBtn');
    const noFilterResult = document.getElementById('noFilterResult');

    /* RESET PAGE */
    function resetPage() {
        if (searchInput) {
            searchInput.value = '';
            searchInput.focus();
        }
        window.location.href = '{{ route("traceability.index") }}';
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function () { resetPage(); });
    }

    if (emptyResetBtn) {
        emptyResetBtn.addEventListener('click', function () { resetPage(); });
    }

    /* RESET FILTER */
    if (clearFilterBtn) {
        clearFilterBtn.addEventListener('click', clearFilters);
    }

    if (clearFilterBtn2) {
        clearFilterBtn2.addEventListener('click', clearFilters);
    }

    /* BUILD SHIFT FILTER */
    function buildShiftFilter() {
        if (!shiftFilter) return;

        const cards = document.querySelectorAll('.trace-result-item');
        const shifts = new Set();

        cards.forEach(function (card) {
            const shift = card.dataset.shift;
            if (shift) shifts.add(shift);
        });

        shifts.forEach(function (shift) {
            const option = document.createElement('option');
            option.value = shift;
            option.textContent = 'Shift ' + shift;
            shiftFilter.appendChild(option);
        });
    }

    buildShiftFilter();

    /* GET DATE */
    function getDateValue(card) {
        const date = card.dataset.date;
        if (!date) return 0;
        const parsed = new Date(date);
        return isNaN(parsed.getTime()) ? 0 : parsed.getTime();
    }

    /* SORT CARDS */
    function sortCards() {
        const modules = document.querySelectorAll('.trace-module');

        modules.forEach(function (module) {
            const container = module.querySelector('.trace-card-container');
            if (!container) return;

            const cards = Array.from(container.querySelectorAll('.trace-result-item'));
            const direction = sortFilter ? sortFilter.value : 'desc';

            cards.sort(function (a, b) {
                const dateA = getDateValue(a);
                const dateB = getDateValue(b);
                return direction === 'asc' ? dateA - dateB : dateB - dateA;
            });

            cards.forEach(function (card) {
                container.appendChild(card);
            });
        });
    }

    /* APPLY FILTER */
    function applyFilters() {
        const selectedModule = moduleFilter ? moduleFilter.value : 'all';
        const selectedShift = shiftFilter ? shiftFilter.value : 'all';
        const cards = document.querySelectorAll('.trace-result-item');
        let totalVisible = 0;

        cards.forEach(function (card) {
            const cardModule = card.dataset.module;
            const cardShift = card.dataset.shift;
            const moduleMatch = selectedModule === 'all' || cardModule === selectedModule;
            const shiftMatch = selectedShift === 'all' || cardShift === selectedShift;
            const visible = moduleMatch && shiftMatch;

            if (visible) {
                card.style.display = '';
                totalVisible++;
            } else {
                card.style.display = 'none';
            }
        });

        sortCards();

        /* MODULE EMPTY */
        const modules = document.querySelectorAll('.trace-module');
        modules.forEach(function (module) {
            const visibleCards = module.querySelectorAll('.trace-result-item:not([style*="display: none"])');
            const empty = module.querySelector('.module-empty');

            if (visibleCards.length === 0) {
                module.style.display = 'none';
                if (empty) empty.classList.remove('d-none');
            } else {
                module.style.display = '';
                if (empty) empty.classList.add('d-none');
            }
        });

        /* COUNTER */
        if (visibleCount) {
            visibleCount.textContent = totalVisible;
        }

        /* GLOBAL EMPTY */
        if (noFilterResult) {
            if (totalVisible === 0) {
                noFilterResult.classList.remove('d-none');
            } else {
                noFilterResult.classList.add('d-none');
            }
        }
    }

    /* FILTER EVENTS */
    if (moduleFilter) {
        moduleFilter.addEventListener('change', applyFilters);
    }

    if (shiftFilter) {
        shiftFilter.addEventListener('change', applyFilters);
    }

    if (sortFilter) {
        sortFilter.addEventListener('change', function () {
            sortCards();
            applyFilters();
        });
    }

    /* UPDATE ARROW ICON */
    document.querySelectorAll('.trace-card-toggle').forEach(function (button) {
        const targetSelector = button.dataset.bsTarget;
        const target = document.querySelector(targetSelector);
        if (!target) return;

        target.addEventListener('shown.bs.collapse', function () {
            button.classList.remove('collapsed');
        });

        target.addEventListener('hidden.bs.collapse', function () {
            button.classList.add('collapsed');
        });
    });

    /* DETECT MATCHED FIELD */
    const searchKeyword = @json($production_code ?? '');

    function tokenize(text) {
        return text.toLowerCase().trim().split(/\s+/).filter(function (item) {
            return item.length > 0;
        });
    }

    const searchTokens = tokenize(searchKeyword);

    document.querySelectorAll('.trace-result-item').forEach(function (card) {
        const rows = card.querySelectorAll('.detail-field-row');
        let matchCount = 0;

        rows.forEach(function (row) {
            const text = row.dataset.searchText || '';
            const matched = searchTokens.some(function (token) {
                return text.includes(token);
            });

            if (matched) {
                matchCount++;
                const value = row.querySelector('.detail-field-value');
                if (value) {
                    value.classList.add('trace-matched-field');
                }
            }
        });

        const indicator = card.querySelector('[data-match-container]');
        if (indicator) {
            if (matchCount > 0) {
                indicator.innerHTML = '<i class="bi bi-check-circle"></i> ' + matchCount + ' field match';
            } else {
                indicator.innerHTML = '<i class="bi bi-info-circle"></i> Data terkait';
            }
        }
    });

    /* ESCAPE KEY */
    if (searchInput) {
        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                searchInput.value = '';
            }
        });
    }

    /* INITIAL FILTER */
    applyFilters();
});
</script>
@endpush
@endsection