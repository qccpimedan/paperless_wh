@extends('layouts.app')
@section('container')
@php
    // Paginate tabel Detail Tidak OK (5 baris per halaman)
    $ncPerPage = 5;
    $ncAll     = collect($sanitationNcRows);
    $ncPage    = max(1, (int) request('nc_page', 1));
    $ncRows    = new \Illuminate\Pagination\LengthAwarePaginator(
        $ncAll->forPage($ncPage, $ncPerPage)->values(),
        $ncAll->count(),
        $ncPerPage,
        $ncPage,
        ['path' => request()->url(), 'pageName' => 'nc_page']
    );
    $ncRows->appends(request()->except('nc_page'))->fragment('sanitation-summary');

    $role = Auth::user()->role->role ?? null;
    $roleColor = match($role) {
        default => 'secondary',
    };

    // Pisahkan panel biasa (ringkasan) dan panel trend (id >= 7)
    $summaryPanelIds = collect($panelIds)->filter(fn ($id) => $id < 7)->values();
    $trendPanelIds   = collect($panelIds)->filter(fn ($id) => $id >= 7)->values();
@endphp

<style>
    /* ---------- Grafana panel ---------- */
    .qc-grafana-panel {
        position: relative;
        height: 430px;
        overflow: hidden;
        background: #17191d;
    }
    .qc-grafana-panel.trend-panel { height: 480px; }
    .qc-grafana-panel iframe {
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
        background: #17191d;
    }
    .qc-grafana-loader,
    .qc-grafana-error {
        position: absolute;
        inset: 0;
        z-index: 3;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 12px;
        color: #667085;
        background: #fff;
        text-align: center;
        padding: 16px;
    }
    .qc-grafana-loader img {
        width: 64px;
        max-height: 64px;
        object-fit: contain;
        animation: qc-logo-pulse 1.4s ease-in-out infinite;
    }
    .qc-grafana-loader .spinner-border { width: 1.4rem; height: 1.4rem; }
    .qc-grafana-error { display: none; }
    .qc-grafana-error.is-visible { display: flex; }

    /* Grafana berjalan di origin lain; tutup badge branding hanya secara visual. */
    .qc-grafana-brand-mask {
        position: absolute;
        z-index: 2;
        top: 0;
        right: 0;
        width: 142px;
        height: 34px;
        background: #17191d;
        pointer-events: none;
    }
    @keyframes qc-logo-pulse {
        0%, 100% { transform: scale(.94); opacity: .75; }
        50% { transform: scale(1.06); opacity: 1; }
    }
    @media (prefers-reduced-motion: reduce) {
        .qc-grafana-loader img { animation: none; }
    }
    @media (max-width: 767.98px) {
        .qc-grafana-panel { height: 360px; }
        .qc-grafana-panel.trend-panel { height: 400px; }
    }

    /* ---------- Layout umum ---------- */
    .dash-section-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: .15rem;
    }
    .dash-card { border: 1px solid #e6e9ef; border-radius: .6rem; }
    .dash-card > .card-header {
        background: #fff;
        border-bottom: 1px solid #e6e9ef;
        padding: .85rem 1.1rem;
    }
    .dash-card > .card-header .card-title { font-size: 1rem; margin: 0; }
    /* Beri jarak antara garis header dan isi card (override padding-top: 0 dari template) */
    .dash-card > .card-header + .card-body:not(.p-0) {
        padding: 1.25rem 1.1rem 1.25rem !important;
    }

    /* ---------- Profil user ---------- */
    .user-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 10px 24px; }
    .user-meta .meta-item { display: flex; align-items: center; gap: 8px; }
    .user-meta .meta-label { color: #667085; font-size: .85rem; }

    /* ---------- Sanitation summary ---------- */
    .sanitation-summary .stat-tile {
        height: 100%;
        padding: .9rem 1.1rem;
        border: 1px solid #e6e9ef;
        border-left-width: 4px;
        border-radius: .5rem;
        background: #fff;
    }
    .sanitation-summary .stat-tile.is-total { border-left-color: #435ebe; }
    .sanitation-summary .stat-tile.is-nc    { border-left-color: #dc3545; }
    .sanitation-summary .stat-tile.is-ok    { border-left-color: #198754; }
    .sanitation-summary .stat-label { color: #667085; font-size: .8rem; }
    .sanitation-summary .summary-number { font-size: 1.6rem; font-weight: 700; line-height: 1.2; }
    .sanitation-summary .summary-ok { color: #198754; }
    .sanitation-summary .summary-nc { color: #dc3545; }

    .sanitation-summary .top-box {
        height: 100%;
        border: 1px solid #e6e9ef;
        border-radius: .5rem;
        padding: .85rem 1rem;
        background: #fafbfc;
    }
    .sanitation-summary .top-box h6 { font-size: .85rem; font-weight: 700; margin-bottom: .5rem; }
    .sanitation-summary .top-list { min-height: 118px; }
    .sanitation-summary .top-list .list-group-item {
        background: transparent;
        padding: .45rem 0;
        border-color: #eceff4;
    }
    .sanitation-summary .top-name { word-break: break-word; padding-right: .5rem; }
    .sanitation-summary .table th,
    .sanitation-summary .table td { vertical-align: middle; }
    .sanitation-summary .table thead th { background: #f4f6fa; white-space: nowrap; }

    /* ---------- Non-Conformity & Top Issue (tab) ---------- */
    .issue-card > .card-header { padding-bottom: 0; border-bottom: 0; }
    .issue-tabs {
        margin: .85rem 0 0;
        border-bottom: 1px solid #e6e9ef;
        flex-wrap: wrap;
    }
    .issue-tabs .nav-link {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: .55rem .95rem;
        font-weight: 600;
        font-size: .9rem;
        color: #667085;
        background: transparent;
    }
    .issue-tabs .nav-link:hover { color: #435ebe; }
    .issue-tabs .nav-link.active { color: #435ebe; background: #fff; }
    .issue-scroll { max-height: 360px; overflow: auto; }
    .issue-scroll .table { margin-bottom: 0; }
    .issue-scroll thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background: #f4f6fa;
        white-space: nowrap;
    }

    /* ---------- Card gabungan panel trend ---------- */
    /* Panel trend: 1 card, 3 kolom sejajar dipisah garis tipis */
    .trend-card { border-color: #cfe8f7; overflow: hidden; }
    .trend-card > .card-header {
        background: #eef7fd;
        border-bottom-color: #cfe8f7;
    }
    .trend-card > .card-header .card-title { color: #1f6fa5; }
    .trend-wrap {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        background: #fff;
    }
    .trend-wrap .qc-grafana-panel.trend-panel { height: 400px; }
    .trend-wrap .qc-grafana-panel + .qc-grafana-panel { border-left: 1px solid #d9ecf8; }
    @media (max-width: 991.98px) {
        .trend-wrap { grid-template-columns: 1fr; }
        .trend-wrap .qc-grafana-panel + .qc-grafana-panel {
            border-left: 0;
            border-top: 1px solid #d9ecf8;
        }
    }
</style>

<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dashboard</h3>
                    <p class="text-subtitle text-muted">Selamat datang di SmartWH</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">

            {{-- 1. Profil user --}}
            <div class="card dash-card mb-4">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 12px;">
                        <h5 class="mb-0">Selamat Datang, {{ Auth::user()->name }}!</h5>
                        <div class="user-meta">
                            <div class="meta-item">
                                <span class="meta-label">Role</span>
                                <span class="badge bg-{{ $roleColor }}">{{ $role ?? 'Tidak ada role' }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Plant</span>
                                <span class="badge bg-info text-dark">{{ Auth::user()->plant->plant ?? 'Tidak ada plant' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Judul dashboard + filter --}}
            <!-- <div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 12px;">
                <div>
                    <div class="dash-section-title">Dashboard QC</div>
                    <span class="text-muted small">Grafana • {{ count($panelIds) }} panel</span>
                </div>
                <a href="{{ $grafanaDashboardUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Grafana
                </a>
            </div> -->

            <form method="GET" action="{{ route('dashboard') }}" class="card dash-card mb-4">
                <div class="card-body">
                    <div class="row align-items-end g-3">
                        @if($canChoosePlant)
                            <div class="col-lg-3 col-md-6">
                                <label for="plantFilter" class="form-label fw-bold">Pilih Plant</label>
                                <select id="plantFilter" name="plant" class="form-control">
                                    @if($isSuperAdmin)
                                        <option value="$__all" {{ $selectedPlant === '$__all' ? 'selected' : '' }}>All Plant</option>
                                    @endif
                                    @foreach($plants as $plant)
                                        <option value="{{ $plant->uuid }}" {{ (string) $plant->id === $selectedPlant ? 'selected' : '' }}>
                                            {{ $plant->plant }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="plant" value="{{ $selectedPlantUuid }}">
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-bold">Plant</label>
                                <div class="form-control bg-light">
                                    {{ $plants->first()?->plant ?? 'Plant belum ditentukan' }}
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-3 col-md-6">
                            <label for="fromFilter" class="form-label fw-bold">Dari Tanggal</label>
                            <input id="fromFilter" type="datetime-local" name="from" class="form-control" value="{{ $fromInput }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="toFilter" class="form-label fw-bold">Sampai Tanggal</label>
                            <input id="toFilter" type="datetime-local" name="to" class="form-control" value="{{ $toInput }}">
                        </div>
                        <div class="col-lg-3 col-md-6 d-flex" style="gap: 8px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel me-1"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            </form>

            {{-- 3. Sanitation summary --}}
            <div class="card dash-card mb-4 sanitation-summary" id="sanitation-summary">
                <div class="card-header">
                    <h5 class="card-title"><i class="bi bi-shield-check text-primary me-1"></i>Ringkasan Pemeriksaan Kebersihan Area</h5>
                    <small class="text-muted">
                        Ringkasan pemeriksaan kebersihan area | Periode {{ \Illuminate\Support\Carbon::parse($sanitationFromDate)->format('d/m/Y') }} - {{ \Illuminate\Support\Carbon::parse($sanitationToDate)->format('d/m/Y') }}
                        @if($fromInput === '' && $toInput === '') (24 jam terakhir) @endif
                    </small>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="stat-tile is-total">
                                <div class="stat-label">Total Pemeriksaan</div>
                                <div class="summary-number">{{ number_format($sanitationTotals['inspection']) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-tile is-nc">
                                <div class="stat-label">Total NC</div>
                                <div class="summary-number summary-nc">{{ number_format($sanitationTotals['nc']) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-tile is-ok">
                                <div class="stat-label">Total OK</div>
                                <div class="summary-number summary-ok">{{ number_format($sanitationTotals['ok']) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        @foreach([
                            'Top 3 Area'               => $sanitationTopAreas,
                            'Top 3 Master Form'        => $sanitationTopForms,
                            'Top 3 Aspek yang Dinilai' => $sanitationTopFields,
                        ] as $heading => $rows)
                            <div class="col-lg-4">
                                <div class="top-box">
                                    <h6>{{ $heading }}</h6>
                                    <div class="list-group list-group-flush top-list">
                                        @forelse($rows as $row)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="top-name">{{ $loop->iteration }}. {{ $row['name'] }}</span>
                                                <span class="text-nowrap small">
                                                    <span class="text-danger fw-bold">NC {{ number_format($row['nc']) }}</span>
                                                    <span class="text-muted">/ {{ number_format($row['total']) }}</span>
                                                </span>
                                            </div>
                                        @empty
                                            <div class="text-muted small py-2">Belum ada data pada periode ini.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <h6 class="fw-bold mb-2">Detail Tidak OK Teratas</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Area</th>
                                    <th>Master Form</th>
                                    <th>Aspek yang Dinilai</th>
                                    <th class="text-center">NC</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ncRows as $row)
                                    <tr>
                                        <td>{{ $row['area'] }}</td>
                                        <td>{{ $row['form'] }}</td>
                                        <td>{{ $row['field'] }}</td>
                                        <td class="text-center text-danger fw-bold">{{ number_format($row['nc']) }}</td>
                                        <td class="text-center">{{ number_format($row['total']) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada temuan NC pada periode ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($ncRows->total() > 0)
                        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3" style="gap: 8px;">
                            <small class="text-muted">
                                Menampilkan {{ $ncRows->firstItem() }}–{{ $ncRows->lastItem() }} dari {{ number_format($ncRows->total()) }} data
                            </small>
                            @if($ncRows->hasPages())
                                {{ $ncRows->links('pagination::bootstrap-5') }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- 4. Non-Conformity & Top Issue : 3 tab dalam 1 card --}}
            <div class="card dash-card issue-card mb-4">
                <div class="card-header">
                    <h5 class="card-title"><i class="bi bi-exclamation-triangle text-danger me-1"></i>Non-Conformity &amp; Top Issue</h5>
                    <small class="text-muted">Mengikuti filter plant dan periode dashboard</small>

                    <ul class="nav nav-tabs issue-tabs" id="issueTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-komplain-btn" data-bs-toggle="tab" data-bs-target="#tab-komplain" type="button" role="tab" aria-controls="tab-komplain" aria-selected="true">
                                Komplain Suplier
                                <span class="badge {{ count($topComplaintRows) ? 'bg-danger' : 'bg-secondary' }}">{{ count($topComplaintRows) }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-precooling-btn" data-bs-toggle="tab" data-bs-target="#tab-precooling" type="button" role="tab" aria-controls="tab-precooling" aria-selected="false">
                                Suhu Precooling
                                <span class="badge {{ count($topPrecoolingRows) ? 'bg-danger' : 'bg-secondary' }}">{{ count($topPrecoolingRows) }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-suhuruang-btn" data-bs-toggle="tab" data-bs-target="#tab-suhuruang" type="button" role="tab" aria-controls="tab-suhuruang" aria-selected="false">
                                Suhu Ruang
                                <span class="badge {{ count($topRoomTemperatureRows) ? 'bg-danger' : 'bg-secondary' }}">{{ count($topRoomTemperatureRows) }}</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body tab-content">

                    {{-- Tab: Detail Komplain --}}
                    <div class="tab-pane fade show active" id="tab-komplain" role="tabpanel" aria-labelledby="tab-komplain-btn" tabindex="0">
                        <p class="text-muted small mb-3">Data komplain per produk.</p>
                        @if(count($topComplaintRows))
                            <div class="table-responsive issue-scroll">
                                <table class="table table-sm table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah Ditolak</th>
                                            <th>Kode Produksi</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topComplaintRows as $issue)
                                            <tr>
                                                <td class="text-nowrap">{{ \Illuminate\Support\Carbon::parse($issue['date'])->format('d/m/Y') }}</td>
                                                <td>{{ $issue['product'] }}</td>
                                                <td class="text-nowrap">{{ $issue['rejected'] }}</td>
                                                <td class="text-nowrap">{{ $issue['production_code'] }}</td>
                                                <td>{{ $issue['description'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-light border mb-0 text-muted">
                                Tidak ada detail komplain dengan keterangan pada plant dan periode yang dipilih.
                            </div>
                        @endif
                    </div>

                    {{-- Tab: Suhu Precooling --}}
                    <div class="tab-pane fade" id="tab-precooling" role="tabpanel" aria-labelledby="tab-precooling-btn" tabindex="0">
                        <p class="text-muted small mb-3">Suhu di atas 0°C tidak sesuai standar (standar: ≤ 0°C).</p>
                        @if(count($topPrecoolingRows))
                            <div class="table-responsive issue-scroll">
                                <table class="table table-sm table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Suhu Precooling</th>
                                            <th>Jenis Kendaraan</th>
                                            <th>Nomor Kendaraan</th>
                                            <th>Ekspedisi</th>
                                            <th>Tujuan</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topPrecoolingRows as $issue)
                                            <tr>
                                                <td class="text-nowrap">{{ \Illuminate\Support\Carbon::parse($issue['date'])->format('d/m/Y') }}</td>
                                                <td class="text-nowrap"><span class="badge bg-danger">{{ $issue['temperature'] }}</span></td>
                                                <td>{{ $issue['vehicle_type'] }}</td>
                                                <td class="text-nowrap">{{ $issue['vehicle_number'] }}</td>
                                                <td>{{ $issue['expedition'] }}</td>
                                                <td>{{ $issue['destination'] }}</td>
                                                <td>{{ $issue['description'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-light border mb-0 text-muted">
                                Tidak ada suhu precooling di atas 0°C pada plant dan periode yang dipilih.
                            </div>
                        @endif
                    </div>

                    {{-- Tab: Suhu Ruang --}}
                    <div class="tab-pane fade" id="tab-suhuruang" role="tabpanel" aria-labelledby="tab-suhuruang-btn" tabindex="0">
                        <p class="text-muted small mb-3">Hanya pembacaan awal form, bukan input per jam.</p>
                        @if(count($topRoomTemperatureRows))
                            <div class="table-responsive issue-scroll">
                                <table class="table table-sm table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Form</th>
                                            <th>Area</th>
                                            <th>Suhu Aktual</th>
                                            <th>Standar</th>
                                            <th>Jam Input Awal</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topRoomTemperatureRows as $issue)
                                            <tr>
                                                <td class="text-nowrap">{{ \Illuminate\Support\Carbon::parse($issue['date'])->format('d/m/Y') }}</td>
                                                <td class="text-nowrap">{{ $issue['form'] }}</td>
                                                <td>{{ $issue['area'] }}</td>
                                                <td class="text-nowrap"><span class="badge bg-danger">{{ $issue['actual'] }}{{ str_contains($issue['actual'], '°') ? '' : '°C' }}</span></td>
                                                <td class="text-nowrap">{{ $issue['standard'] }}</td>
                                                <td class="text-nowrap">{{ $issue['time'] }}</td>
                                                <td>{{ $issue['note'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-light border mb-0 text-muted">
                                Tidak ada suhu awal V1/V2/V3 di luar standar pada plant dan periode yang dipilih.
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- 5. Panel Grafana (ringkasan) : 2 kolom --}}
            <div class="row g-3 mb-4">
                @foreach($summaryPanelIds as $panelId)
                    <div class="col-12 col-md-6">
                        <div class="card dash-card h-100">
                            <div class="card-header">
                                <h6 class="card-title">
                                    <i class="bi bi-bar-chart-line me-1 text-primary"></i>{{ $panelNames[$panelId] }}
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="qc-grafana-panel" data-panel-name="{{ $panelNames[$panelId] }}">
                                    @include('grafana-panel-inner', ['panelId' => $panelId])
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 6. Panel Trend : digabung dalam 1 card, 3 kolom --}}
            @if($trendPanelIds->isNotEmpty())
                <div class="card dash-card trend-card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bi bi-graph-up-arrow text-primary me-1"></i>Trend Suhu Ruangan Perjam
                        </h5>
                        <!-- <small class="text-muted">{{ $trendPanelIds->count() }} panel trend sesuai filter plant dan periode</small> -->
                    </div>
                    <div class="card-body p-0">
                        <div class="trend-wrap">
                            @foreach($trendPanelIds as $panelId)
                                <div class="qc-grafana-panel trend-panel" data-panel-name="{{ $panelNames[$panelId] }}">
                                    @include('grafana-panel-inner', ['panelId' => $panelId])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </section>
    </div>

    @include('partials.footer')
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const panels = document.querySelectorAll('.qc-grafana-panel');
    const timeoutMs = 20000;
    function loadPanel(panel, retry) {
        const frame = panel.querySelector('iframe');
        const loader = panel.querySelector('.qc-grafana-loader');
        const error = panel.querySelector('.qc-grafana-error');
        if (!frame) return;

        if (panel._loadTimer) window.clearTimeout(panel._loadTimer);
        frame.onload = null;
        if (retry) {
            error.classList.remove('is-visible');
            loader.style.display = 'flex';
            frame.removeAttribute('src');
        }
        if (frame.dataset.started === '1' && !retry) return;
        frame.dataset.started = '1';

        let settled = false;
        panel._loadTimer = window.setTimeout(function () {
            if (settled) return;
            settled = true;
            loader.style.display = 'none';
            error.classList.add('is-visible');
        }, timeoutMs);

        frame.onload = function () {
            if (settled) return;
            settled = true;
            window.clearTimeout(panel._loadTimer);
            panel._loadTimer = null;
            loader.style.display = 'none';
        };

        frame.src = frame.dataset.src;
    }

    panels.forEach(function (panel) {
        panel.querySelector('.qc-grafana-retry').addEventListener('click', function () {
            loadPanel(panel, true);
        });
    });

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                loadPanel(entry.target, false);
                observer.unobserve(entry.target);
            });
        }, { rootMargin: '250px 0px' });
        panels.forEach(function (panel) { observer.observe(panel); });
    } else {
        panels.forEach(function (panel, index) {
            window.setTimeout(function () { loadPanel(panel, false); }, index * 350);
        });
    }
});
</script>
@endpush
