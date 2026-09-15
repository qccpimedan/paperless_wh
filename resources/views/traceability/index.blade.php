@extends('layouts.app')

@section('container')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><i class="bi bi-search"></i> Traceability</h3>
                    <p class="text-subtitle text-muted">Penelusuran nama produk & kode produksi / batch lintas modul QC</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Traceability</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <form action="{{ route('traceability.search') }}" method="GET" class="mb-4">
                                <div class="input-group input-group-lg">
                                    <!-- <span class="input-group-text bg-primary text-white">
                                        <i class="bi bi-search"></i>
                                    </span> -->
                                    <input 
                                        type="text" 
                                        name="production_code" 
                                        class="form-control" 
                                        placeholder="Masukkan nama produk atau kode produksi ..." 
                                        value="{{ $production_code ?? '' }}"
                                        autofocus
                                        required
                                        minlength="2"
                                    >
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-1"></i> Telusuri
                                    </button>
                                    <button type="button" id="resetBtn" class="btn btn-secondary">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                    </button>
                                </div>
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Sistem akan mencari nama produk & kode produksi di seluruh modul
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(isset($results))
                        @if($results->isEmpty())
                            <div class="alert text-center py-5" role="alert">
                                <i class="bi bi-exclamation-circle" style="font-size: 3rem;"></i>
                                <h4 class="mt-3">Tidak Ada Data Ditemukan</h4>
                                <p class="mb-0">Tidak ditemukan data dengan kode produksi "<strong>{{ $production_code }}</strong>" di seluruh modul QC.</p>
                            </div>
                        @else
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        Ditemukan <strong>{{ $results->sum(function($group) { return $group['forms']->count(); }) }} hasil</strong> untuk kode produksi "<strong>{{ $production_code }}</strong>"
                                    </div>
                                </div>
                            </div>

                            @foreach($results as $group)
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <h5 class="mb-0 me-2">
                                            <i class="bi bi-folder2-open text-primary"></i>
                                            {{ $group['label'] }}
                                        </h5>
                                        <span class="badge bg-primary rounded-pill">
                                            {{ $group['forms']->count() }} data
                                        </span>
                                    </div>

                                    <div class="row g-3">
                                        @foreach($group['forms'] as $form)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card h-100 shadow-sm border-0" style="border-left: 4px solid #435ebe !important;">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                                            <div>
                                                                <h6 class="mb-1 text-primary">
                                                                    <i class="bi bi-calendar-date me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($form['date'])->translatedFormat('d F Y') }}
                                                                </h6>
                                                                @if($form['shift'])
                                                                    <small class="text-muted">
                                                                        <i class="bi bi-clock me-1"></i>Shift {{ $form['shift'] }}
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <a href="{{ $form['pdf_url'] }}" 
                                                               class="btn btn-sm btn-success" 
                                                               title="Lihat Detail"
                                                               target="_blank"> Detail
                                                                <!-- <i class="bi bi-arrow-up-right-square-fill"></i> -->
                                                            </a> 
                                                        </div>

                                                        <div class="border-top pt-3">
                                                            @foreach($form['fields'] as $label => $value)
                                                                <div class="d-flex justify-content-between mb-2">
                                                                    <small class="text-muted">{{ $label }}</small>
                                                                    <small class="fw-bold text-end" style="max-width: 60%;">
                                                                        @if(stripos($label, 'kode produksi') !== false)
                                                                            <mark class="bg-warning">{{ $value }}</mark>
                                                                        @else
                                                                            {{ $value }}
                                                                        @endif
                                                                    </small>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    mark {
        padding: 2px 4px;
        border-radius: 3px;
    }
    
    .input-group-lg .form-control {
        font-size: 1.1rem;
    }
    
    .input-group-lg .input-group-text {
        font-size: 1.2rem;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resetBtn = document.getElementById('resetBtn');
        const searchInput = document.querySelector('input[name="production_code"]');
        
        // Reset button handler
        resetBtn.addEventListener('click', function() {
            // Clear input
            searchInput.value = '';
            searchInput.focus();
            
            // Reload page to clear results
            window.location.href = '{{ route("traceability.index") }}';
        });
        
        // Optional: Clear on ESC key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
            }
        });
    });
</script>
@endpush
@endsection
