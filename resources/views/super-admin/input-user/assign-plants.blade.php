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
                    <h3>Assign Plant Akses</h3>
                    <p class="text-subtitle text-muted">Kelola plant yang dapat diakses oleh Manager</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Assign Plant</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <section class="section">
            <div class="row">
                {{-- ===== Info Card Manager ===== --}}
                <div class="col-md-4 col-12 mb-4">
                    <div class="card h-100" style="border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(111,66,193,0.12); overflow: hidden;">
                        <div style="background: linear-gradient(135deg, #6f42c1 0%, #5a289e 100%); padding: 32px 24px; text-align: center;">
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                                style="width:72px; height:72px; background: rgba(255,255,255,0.2); font-size:1.8rem; font-weight:700; color:#fff;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <h5 class="mb-1 fw-bold text-white">{{ $user->name }}</h5>
                            <span class="badge" style="background: rgba(255,255,255,0.25); color:#fff; font-size:0.8rem; padding: 0.3rem 0.8rem; border-radius:20px;">
                                <i class="bi bi-person-badge me-1"></i>{{ $user->role?->role ?? 'Manager' }}
                            </span>
                        </div>
                        <div class="card-body py-3 px-4">
                            <div class="d-flex align-items-center gap-3 mb-3 py-2" style="border-bottom: 1px solid #f0f0f0;">
                                <i class="bi bi-envelope" style="color:#6f42c1; font-size:1rem; width:20px;"></i>
                                <div>
                                    <div style="font-size:0.72rem; color:#999; text-transform:uppercase; letter-spacing:0.05em;">Email</div>
                                    <div style="font-size:0.88rem; font-weight:500; color:#2c3e50;">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3 py-2" style="border-bottom: 1px solid #f0f0f0;">
                                <i class="bi bi-person-circle" style="color:#6f42c1; font-size:1rem; width:20px;"></i>
                                <div>
                                    <div style="font-size:0.72rem; color:#999; text-transform:uppercase; letter-spacing:0.05em;">Username</div>
                                    <div style="font-size:0.88rem; font-weight:500; color:#2c3e50;">{{ $user->username }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 py-2">
                                <i class="bi bi-building" style="color:#6f42c1; font-size:1rem; width:20px;"></i>
                                <div>
                                    <div style="font-size:0.72rem; color:#999; text-transform:uppercase; letter-spacing:0.05em;">Plant Utama/Asal</div>
                                    <div style="font-size:0.88rem; font-weight:500; color:#2c3e50;">{{ $user->plant?->plant ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <span style="font-size:0.82rem; color:#868e96;">Plant dipilih:</span>
                                <span id="selected-count-badge" class="badge"
                                    style="background: linear-gradient(135deg, #6f42c1, #5a289e); color:#fff; font-size:0.85rem; padding: 0.35rem 0.9rem; border-radius:20px;">
                                    {{ count($selectedPlantIds) }} plant
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== Assign Plants Card ===== --}}
                <div class="col-md-8 col-12 mb-4">
                    <div class="card" style="border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.07);">
                        <div class="card-header d-flex align-items-center justify-content-between py-3 px-4" style="border-bottom: 1px solid #f0f0f0; background: transparent;">
                            <div>
                                <h5 class="mb-0 fw-bold" style="color:#2c3e50;">
                                    <i class="bi bi-building me-2" style="color:#6f42c1;"></i>
                                    Plant yang Dapat Diakses
                                </h5>
                                <p class="mb-0 mt-1 text-muted small">Centang plant yang boleh di-switch oleh manager ini</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" id="btn-select-all" class="btn btn-sm" style="background: rgba(111,66,193,0.1); color:#6f42c1; border:none; border-radius:8px; font-weight:600; font-size:0.82rem; padding: 0.4rem 1rem;">
                                    <i class="bi bi-check-all me-1"></i>Pilih Semua
                                </button>
                                <button type="button" id="btn-clear-all" class="btn btn-sm" style="background: rgba(220,53,69,0.08); color:#dc3545; border:none; border-radius:8px; font-weight:600; font-size:0.82rem; padding: 0.4rem 1rem;">
                                    <i class="bi bi-x-lg me-1"></i>Hapus Semua
                                </button>
                            </div>
                        </div>
                        <div class="card-body px-4 py-4">
                            <form method="POST" action="{{ route('users.save-assign-plants', $user->uuid) }}" id="assign-plants-form">
                                @csrf
                                <div class="row g-3" id="plants-grid">
                                    @foreach($plants as $plant)
                                    @php $isSelected = in_array($plant->id, $selectedPlantIds); @endphp
                                    <div class="col-md-6 col-lg-4">
                                        <div class="plant-card {{ $isSelected ? 'selected' : '' }}"
                                            data-plant-id="{{ $plant->id }}"
                                            onclick="togglePlant(this)"
                                            style="
                                                border: 2px solid {{ $isSelected ? '#6f42c1' : '#eef0f5' }};
                                                border-radius: 12px;
                                                padding: 16px;
                                                background: {{ $isSelected ? 'linear-gradient(135deg, rgba(111,66,193,0.08), rgba(111,66,193,0.03))' : '#fafafa' }};
                                                cursor: pointer;
                                                transition: all 0.2s ease;
                                                position: relative;
                                                user-select: none;
                                            ">
                                            {{-- Hidden checkbox --}}
                                            <input type="checkbox"
                                                name="allowed_plants[]"
                                                value="{{ $plant->id }}"
                                                class="plant-checkbox"
                                                style="display:none;"
                                                {{ $isSelected ? 'checked' : '' }}>

                                            {{-- Checkmark icon --}}
                                            <div class="plant-check-icon" style="
                                                position: absolute;
                                                top: 10px;
                                                right: 10px;
                                                width: 22px;
                                                height: 22px;
                                                border-radius: 50%;
                                                background: {{ $isSelected ? '#6f42c1' : '#e0e0e0' }};
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                transition: all 0.2s;
                                            ">
                                                <i class="bi bi-check" style="color:#fff; font-size:0.75rem; font-weight:800;"></i>
                                            </div>

                                            {{-- Plant Info --}}
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="plant-avatar d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                                    style="
                                                        width: 44px;
                                                        height: 44px;
                                                        background: {{ $isSelected ? 'linear-gradient(135deg, #6f42c1, #a78bfa)' : 'linear-gradient(135deg, #adb5bd, #ced4da)' }};
                                                        color: #fff;
                                                        font-size: 1rem;
                                                        font-weight: 700;
                                                        transition: all 0.2s;
                                                    ">
                                                    {{ strtoupper(substr($plant->plant, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div style="font-size:0.92rem; font-weight:600; color:{{ $isSelected ? '#6f42c1' : '#2c3e50' }}; line-height:1.2;">
                                                        {{ $plant->plant }}
                                                    </div>
                                                    <div style="font-size:0.75rem; color:#adb5bd; margin-top:2px;">
                                                        <i class="bi bi-geo-alt me-1"></i>{{ $plant->timezone ?? 'Asia/Jakarta' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 1px solid #f0f0f0;">
                                    <a href="{{ route('users.index') }}" class="btn btn-light" style="border-radius:10px; padding:0.55rem 1.4rem; font-weight:500;">
                                        <i class="bi bi-arrow-left me-1"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary" style="border-radius:10px; padding:0.55rem 1.8rem; font-weight:600; background: linear-gradient(135deg, #6f42c1, #5a289e); border:none; box-shadow: 0 4px 14px rgba(111,66,193,0.35);">
                                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
.plant-card:hover {
    border-color: #6f42c1 !important;
    box-shadow: 0 4px 16px rgba(111, 66, 193, 0.18);
    transform: translateY(-2px);
}
.plant-card.selected {
    border-color: #6f42c1 !important;
}
</style>

<script>
function togglePlant(card) {
    const checkbox = card.querySelector('.plant-checkbox');
    const isSelected = card.classList.contains('selected');
    const checkIcon = card.querySelector('.plant-check-icon');
    const avatar = card.querySelector('.plant-avatar');
    const nameEl = card.querySelector('div[style*="font-size:0.92rem"]');

    if (isSelected) {
        // Unselect
        card.classList.remove('selected');
        checkbox.checked = false;
        card.style.borderColor = '#eef0f5';
        card.style.background = '#fafafa';
        checkIcon.style.background = '#e0e0e0';
        avatar.style.background = 'linear-gradient(135deg, #adb5bd, #ced4da)';
        if (nameEl) nameEl.style.color = '#2c3e50';
    } else {
        // Select
        card.classList.add('selected');
        checkbox.checked = true;
        card.style.borderColor = '#6f42c1';
        card.style.background = 'linear-gradient(135deg, rgba(111,66,193,0.08), rgba(111,66,193,0.03))';
        checkIcon.style.background = '#6f42c1';
        avatar.style.background = 'linear-gradient(135deg, #6f42c1, #a78bfa)';
        if (nameEl) nameEl.style.color = '#6f42c1';
    }

    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.plant-checkbox:checked').length;
    const badge = document.getElementById('selected-count-badge');
    badge.textContent = count + ' plant';
}

// Pilih Semua
document.getElementById('btn-select-all').addEventListener('click', function () {
    document.querySelectorAll('.plant-card:not(.selected)').forEach(card => togglePlant(card));
});

// Hapus Semua
document.getElementById('btn-clear-all').addEventListener('click', function () {
    document.querySelectorAll('.plant-card.selected').forEach(card => togglePlant(card));
});
</script>
@endsection
