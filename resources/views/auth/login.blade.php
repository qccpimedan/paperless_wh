@extends('layouts.auth')
@section('container')
<style>
    /* ===== Enhanced Modern Login Design ===== */

    /* ===== QC Warehouse Themed Background ===== */
    #auth-right {
        position: relative;
        background:
            linear-gradient(135deg, rgba(66,133,244,0.95), rgba(90,103,216,0.95)),
            repeating-linear-gradient(
                90deg,
                rgba(255,255,255,0.05) 0,
                rgba(255,255,255,0.05) 1px,
                transparent 1px,
                transparent 80px
            ),
            repeating-linear-gradient(
                0deg,
                rgba(255,255,255,0.05) 0,
                rgba(255,255,255,0.05) 1px,
                transparent 1px,
                transparent 80px
            );
    }

    /* Enhanced Auth Left Section */
    #auth-left {
        padding: 3rem 2.5rem;
        animation: fadeInLeft 0.8s ease-out;
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Logo Animation */
    .logo-container {
        animation: scaleIn 0.6s ease-out;
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .logo-container img {
        filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.1));
        transition: transform 0.3s ease;
    }

    .logo-container img:hover {
        transform: scale(1.05);
    }

    /* Title Styling */
    .main-title {
        font-weight: 700;
        background: linear-gradient(135deg, #4285f4 0%, #8e7ba3 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        animation: fadeIn 0.8s ease-out 0.2s both;
    }

    .sub-title {
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 2rem;
        animation: fadeIn 0.8s ease-out 0.4s both;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Enhanced Form Styling */
    .form-group {
        margin-bottom: 1.5rem;
        animation: fadeIn 0.8s ease-out 0.6s both;
    }

    .form-control-xl {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 0.875rem 1rem 0.875rem 3.5rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }

    .form-control-xl:focus {
        border-color: #4285f4;
        background-color: #fff;
        box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.15);
        transform: translateY(-2px);
    }

    .form-control-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        z-index: 3;
    }

    .form-control-icon i {
        font-size: 1.25rem;
        color: #6c757d;
        transition: color 0.3s ease;
    }

    .form-control:focus ~ .form-control-icon i {
        color: #4285f4;
    }

    /* Enhanced Button Styling */
    .btn-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border: none;
        border-radius: 12px;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
        animation: fadeIn 0.8s ease-out 0.8s both;
    }

    .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
    }

    .btn-primary {
        background: linear-gradient(135deg, #4285f4 0%, #5a67d8 100%);
        border: none;
        border-radius: 12px;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(66, 133, 244, 0.3);
        animation: fadeIn 0.8s ease-out 1s both;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(66, 133, 244, 0.4);
        background: linear-gradient(135deg, #5a67d8 0%, #4c51bf 100%);
    }

    .btn-primary:active,
    .btn-info:active {
        transform: translateY(0);
    }

    /* Alert Enhancement */
    .alert {
        border-radius: 12px;
        border: none;
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Version Text Enhancement */
    .version-text {
        font-size: 0.75rem;
        line-height: 1.4;
        color: #6c757d;
        animation: fadeIn 0.8s ease-out 1.2s both;
    }

    .version-text strong {
        color: #495057;
    }

    /* Modal Enhancement */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: linear-gradient(135deg, #4285f4 0%, #5a67d8 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        border: none;
        padding: 1.5rem;
    }

    .modal-title {
        font-weight: 600;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-body h6 {
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .alert-light {
        background-color: #f8f9fa;
        border-left: 4px solid #4285f4 !important;
        border-radius: 8px;
    }

    /* Icon Enhancement */
    .bi {
        transition: transform 0.3s ease;
    }

    .btn:hover .bi {
        transform: scale(1.1);
    }

    /* Responsive Design */
    @media (max-width: 991.98px) {
        #auth-left {
            padding: 2rem 1.5rem;
        }
        
        .main-title {
            font-size: 1.75rem;
        }
        
        .sub-title {
            font-size: 1.1rem;
        }
    }

    /* Loading Animation untuk Form */
    .form-group.loading .form-control {
        opacity: 0.7;
        pointer-events: none;
    }

    /* Hover Effect untuk List Items dalam Modal */
    .list-unstyled li {
        padding: 0.5rem;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .list-unstyled li:hover {
        background-color: rgba(66, 133, 244, 0.05);
    }

    /* Enhanced Role Cards dalam Modal */
    .alert-light > div {
        padding: 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .alert-light > div:hover {
        background-color: rgba(66, 133, 244, 0.05);
        transform: translateX(5px);
    }
</style>

<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <!-- Logo dengan animasi -->
            <div class="text-center logo-container">
                <img src="{{ asset('dist/images/logo/cpi-logo.png') }}" alt="CPI Logo" class="img-fluid" style="max-width: 110px; height: auto;">
            </div>
            
            <!-- Title dengan gradient -->
            <h1 class="main-title fs-2 text-center">Paperless</h1>
            <h4 class="sub-title fs-4 text-center">QC Warehouse</h4>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group position-relative has-icon-left">
                    <input type="text" class="form-control form-control-xl @error('username') is-invalid @enderror" 
                           placeholder="Username" name="username" value="{{ old('username') }}" required autofocus>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group position-relative has-icon-left">
                    <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" 
                           placeholder="Password" name="password" required>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Buttons -->
                <button type="button" class="btn btn-info btn-block btn-lg w-100 shadow-sm" data-bs-toggle="modal" data-bs-target="#loginGuideModal">
                    <i class="bi bi-info-circle me-2"></i>Petunjuk Login
                </button>
                
                <button type="submit" class="btn btn-primary btn-block btn-lg w-100 mt-3 shadow">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Log in
                </button>

                <!-- Version Info -->
                <div class="text-center mt-4 version-text">
                    <div><strong>Version 1.0</strong></div>
                    <div class="mt-1">Copyright © {{ date('Y') }} PT. Charoen Pokphand Indonesia</div>
                    <div class="mt-1">All rights reserved by Tim Industry 4.0</div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Right Side dengan Enhanced Background -->
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right"
            style="
                background-image:url('{{ asset('dist/images/logo/qc-warehouse.png') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            ">
        </div>
    </div>


</div>

<!-- Enhanced Modal -->
<div class="modal fade" id="loginGuideModal" tabindex="-1" aria-labelledby="loginGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginGuideModalLabel">
                    <i class="bi bi-info-circle me-2"></i>Petunjuk Login
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="mb-4 text-muted">Sebelum melakukan login, perhatikan hal-hal berikut:</h6>
                
                <!-- Role dan Peran Sistem -->
                <div class="mb-4">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-people-fill me-2"></i>Role dan Peran Sistem
                    </h6>
                    <div class="alert alert-light border-start border-4">
                        <div class="mb-3 pb-3 border-bottom">
                            <strong class="text-info d-block mb-2">
                                <i class="bi bi-person-badge-fill me-2"></i>QC
                            </strong>
                            <p class="mb-0 ms-4 text-muted">Bertugas untuk mengisi form yang telah disediakan</p>
                        </div>
                        <div class="mb-3 pb-3 border-bottom">
                            <strong class="text-warning d-block mb-2">
                                <i class="bi bi-person-badge-fill me-2"></i>Tim Warehouse
                            </strong>
                            <p class="mb-0 ms-4 text-muted">Bertugas untuk verifikasi awal dari form yang telah diisi QC</p>
                        </div>
                        <div>
                            <strong class="text-success d-block mb-2">
                                <i class="bi bi-person-badge-fill me-2"></i>SPV QC
                            </strong>
                            <p class="mb-0 ms-4 text-muted">Bertugas untuk melakukan verifikasi lanjutan dari form yang telah diisi oleh QC dan disetujui oleh Tim Produksi</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection