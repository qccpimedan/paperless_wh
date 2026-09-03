<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paperless QC-WH</title>

    <link href="{{asset('dist/css/fonts.css')}}" rel="stylesheet">
   
    <link rel="stylesheet" href="{{asset('dist/css/bootstrap.css')}}">

    <link rel="stylesheet" href="{{asset('dist/vendors/perfect-scrollbar/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{asset('dist/vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('dist/vendors/simple-datatables/style.css')}}">
    <!-- Choices.js CSS -->
    <link rel="stylesheet" href="{{ asset('dist/vendors/choices.js/choices.min.css') }}">
    <link rel="stylesheet" href="{{asset('dist/vendors/select2/css/select2.min.css')}}">
    <!-- DataTables Bootstrap5 CSS -->
    <link rel="stylesheet" href="{{asset('dist/vendors/datatables/dataTables.bootstrap5.min.css')}}">
    <link rel="icon" href="{{asset('dist/images/logo/logo5.png')}}" type="image/x-icon">
    
    <!-- Suppress browser extension console errors -->
    <!-- <script>
        // Suppress noisy browser extension warnings in console (Production Mode)
        if (!window.location.hostname.includes('localhost') || true) {
            (function() {
                const originalError = console.error;
                const originalWarn = console.warn;
                
                console.error = function(...args) {
                    const message = JSON.stringify(args);
                    // Filter out browser extension errors
                    if (message.includes('contentscript') || 
                        message.includes('chrome-extension') ||
                        message.includes('moz-extension') ||
                        message.includes('ObjectMultiplex') || 
                        message.includes('orphaned data') ||
                        message.includes('app-init-liveness') ||
                        message.includes('background-liveness')) {
                        return; // Suppress these errors
                    }
                    originalError.apply(console, args);
                };
                
                console.warn = function(...args) {
                    const message = JSON.stringify(args);
                    // Filter out MaxListeners and extension warnings
                    if (message.includes('MaxListenersExceededWarning') ||
                        message.includes('Possible EventEmitter memory leak') ||
                        message.includes('contentscript') ||
                        message.includes('chrome-extension') ||
                        message.includes('moz-extension')) {
                        return; // Suppress these warnings
                    }
                    originalWarn.apply(console, args);
                };

                // Global error handler untuk suppress extension errors
                window.addEventListener('error', function(e) {
                    const message = e.message || '';
                    const filename = e.filename || '';
                    if (filename.includes('contentscript') || 
                        filename.includes('chrome-extension') ||
                        filename.includes('moz-extension') ||
                        message.includes('ObjectMultiplex')) {
                        e.stopImmediatePropagation();
                        e.preventDefault();
                        return false;
                    }
                }, true);

                // Unhandled promise rejection handler
                window.addEventListener('unhandledrejection', function(e) {
                    const message = e.reason?.message || '';
                    if (message.includes('ObjectMultiplex') ||
                        message.includes('contentscript') ||
                        message.includes('orphaned data')) {
                        e.stopImmediatePropagation();
                        e.preventDefault();
                        return false;
                    }
                }, true);
            })();
        }
    </script> -->
    
    <style>
        /* ===== Navbar & Sidebar Z-Index ===== */
        .navbar.sticky-top {
            z-index: 1050 !important;
            position: sticky !important;
        }
        
        #sidebar {
            z-index: 1000 !important;
        }

        /* ===== SIDEBAR CLOSE BUTTON (Mobile/Tablet) ===== */
        /* Pastikan tombol close terlihat di mobile/tablet */
        #sidebar .sidebar-hide {
            transition: all 0.3s ease !important;
        }
        
        #sidebar .sidebar-hide:hover {
            background: #c82333 !important;
            transform: scale(1.1) !important;
            box-shadow: 0 6px 16px rgba(220, 53, 69, 0.7) !important;
        }
        
        #sidebar .sidebar-hide:active {
            transform: scale(0.95) !important;
        }

        /* ===== FIX SIDEBAR SCROLL TABLET & PC ===== */
        .sidebar-wrapper {
            overflow-y: auto !important;
            padding-bottom: 80px !important;
            scrollbar-width: thin; /* Firefox: scrollbar tipis */
            scrollbar-color: rgba(0,0,0,0.2) transparent; /* Firefox: warna scrollbar */
        }
        
        /* Chrome/Safari/Edge: scrollbar tipis dan rapi */
        .sidebar-wrapper::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-wrapper::-webkit-scrollbar-thumb {
            background-color: rgba(0,0,0,0.2);
            border-radius: 10px;
        }
        .sidebar-wrapper::-webkit-scrollbar-thumb:hover {
            background-color: rgba(0,0,0,0.35);
        }

        /* Sembunyikan Perfect Scrollbar rail agar tidak dobel */
        .sidebar-wrapper > .ps__rail-y,
        .sidebar-wrapper > .ps__rail-x {
            display: none !important;
        }

        /* ===== Table Styles ===== */
        table thead th {
            text-align: center !important;
        }


        /* ===== Notification Styles ===== */
        .notification-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid #0d6efd;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0.02) 100%);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            animation: slideIn 0.3s ease-out;
        }

        .notification-item:hover {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 110, 253, 0.05) 100%);
            border-left-color: #0b5ed7;
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .notification-header {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header strong {
            color: #1a252f;
            font-weight: 700;
        }

        .notification-meta {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin: 0;
            line-height: 1.4;
        }

        .notification-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border: none;
            border-radius: 0.375rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
        }

        .notification-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.3s ease;
            z-index: 0;
        }

        .notification-btn:hover {
            background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
            transform: translateY(-2px);
            color: #fff;
        }

        .notification-btn:hover::before {
            left: 100%;
        }

        .notification-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
        }

        #notification-dropdown {
            border: 1px solid #e8e8e8;
            border-radius: 0.5rem;
            background: #fff;
        }

        #notification-dropdown .p-3 {
            padding: 1rem !important;
        }

        #notification-dropdown .border-bottom {
            background: linear-gradient(135deg, #f8f9fa 0%, #f0f0f0 100%);
            border-bottom: 1px solid #e8e8e8 !important;
        }

        #notification-dropdown .border-bottom h6 {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #notification-dropdown-list .text-muted {
            text-align: center;
            padding: 2rem 1rem;
            color: #95a5a6 !important;
            font-size: 0.9rem;
        }

        #notification-dropdown::-webkit-scrollbar {
            width: 6px;
        }

        #notification-dropdown::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #notification-dropdown::-webkit-scrollbar-thumb {
            background: #0d6efd;
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        #notification-dropdown::-webkit-scrollbar-thumb:hover {
            background: #0b5ed7;
        }

        @media (max-width: 576px) {
            #notification-dropdown {
                width: 280px !important;
            }
            
            .notification-item {
                padding: 0.75rem;
            }
            
            .notification-header {
                font-size: 0.9rem;
            }
            
            .notification-meta {
                font-size: 0.85rem;
            }
            
            .notification-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }

        /* ===== Switch Plant Styles (Manager Only) ===== */
        .switch-plant-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.9rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #6f42c1 0%, #5a289e 100%);
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(111, 66, 193, 0.35);
            text-decoration: none;
            white-space: nowrap;
        }
        .switch-plant-btn:hover {
            background: linear-gradient(135deg, #5a289e 0%, #491f87 100%);
            box-shadow: 0 4px 14px rgba(111, 66, 193, 0.5);
            transform: translateY(-1px);
            color: #fff;
        }
        .switch-plant-btn .active-plant-label {
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .switch-plant-dropdown {
            min-width: 280px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            padding: 0.5rem 0;
            overflow: hidden;
        }
        .switch-plant-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .switch-plant-list::-webkit-scrollbar {
            width: 5px;
        }
        .switch-plant-list::-webkit-scrollbar-thumb {
            background: #c4b5fd;
            border-radius: 10px;
        }
        .switch-plant-search {
            padding: 0.5rem 0.75rem;
            border-bottom: 1px solid #eee;
        }
        .switch-plant-search input {
            width: 100%;
            padding: 0.35rem 0.6rem;
            font-size: 0.82rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.2s;
        }
        .switch-plant-search input:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 0 2px rgba(111, 66, 193, 0.15);
        }
        .switch-plant-dropdown .dropdown-header {
            background: linear-gradient(135deg, #6f42c1 0%, #5a289e 100%);
            color: #fff;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 0.6rem 1rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .switch-plant-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-size: 0.88rem;
            font-weight: 500;
            color: #333;
            transition: background 0.2s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .switch-plant-item:hover {
            background: rgba(111, 66, 193, 0.09);
            color: #6f42c1;
        }
        .switch-plant-item.current {
            background: rgba(111, 66, 193, 0.12);
            color: #6f42c1;
            font-weight: 700;
        }
        .switch-plant-item .plant-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6f42c1 0%, #a78bfa 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .switch-plant-item.current .plant-icon {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        .switch-plant-reset-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.82rem;
            color: #868e96;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
        }
        .switch-plant-reset-btn:hover {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.07);
        }

        /* ===== Sidebar Active Contrast ===== */
        .sidebar-item.active > .sidebar-link {
            background-color: #435ebe !important;
            margin-top: 6px;
            margin-bottom: 6px;
            /* box-shadow: 0 4px 10px rgba(67, 94, 190, 0.35) !important; */
        }

        .sidebar-item.active > .sidebar-link i,
        .sidebar-item.active > .sidebar-link span {
            color: #fff !important;
            font-weight: 700 !important;
        }

        .submenu-item.active > a {
            color: #435ebe !important;
            font-weight: 800 !important;
            background-color: rgba(67, 94, 190, 0.1) !important;
            border-radius: 7px;
            padding-left: 1.5rem !important;
            transition: all 0.3s ease;
        }

        .submenu-item.active > a::before {
            content: "➜";
            margin-right: 8px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white bg-body-tertiary shadow-sm sticky-top" style="border-bottom: 1px solid #e3e6f0;">
            <div class="container-fluid">
                <!-- Breadcrumb -->
                <div class="d-flex align-items-center flex-grow-1">
                        <div class="logo">
                    <img src="{{ asset('dist/images/logo/logo7.png') }}" alt="Logo" style="width: 135px; height: auto;">
                </div>
                    <!-- <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        </ol>
                    </nav> -->
                </div>

                <!-- Right Side Items -->
                <div class="d-flex align-items-center gap-3">

                    @php
                        $authUser = auth()->user();
                        $isManager = $authUser && $authUser->isManager();
                        $effectivePlant = $authUser ? $authUser->getEffectivePlant() : null;
                        $originalPlant = $authUser ? $authUser->plant : null;
                        // ✅ Hanya tampilkan plant yang diizinkan oleh Superadmin
                        $allPlants = $isManager
                            ? $authUser->allowedPlants()->orderBy('plant')->get()
                            : collect();
                    @endphp

                    {{-- ===== SWITCH PLANT (Manager Only) ===== --}}
                    @if($isManager)
                    <div class="dropdown" id="switchPlantDropdown">
                        <button class="switch-plant-btn dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                title="Klik untuk ganti plant aktif">
                            <i class="bi bi-building"></i>
                            <span class="active-plant-label">
                                {{ $effectivePlant?->plant ?? 'Pilih Plant' }}
                            </span>
                            @if($authUser->active_plant_id && $authUser->active_plant_id !== $authUser->id_plant)
                                <span class="badge bg-warning text-dark" style="font-size:0.65rem; padding:0.2rem 0.45rem; border-radius:10px;">Switched</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end switch-plant-dropdown" aria-labelledby="switchPlantDropdown">
                            <li><h6 class="dropdown-header"><i class="bi bi-building me-1"></i>Switch Plant</h6></li>
                            <li class="switch-plant-search">
                                <input type="text" id="searchPlantInput" placeholder="Cari plant..." autocomplete="off">
                            </li>
                            <div class="switch-plant-list">
                            @forelse($allPlants as $plant)
                            <li class="plant-list-item" data-plant-name="{{ strtolower($plant->plant) }}">
                                <form method="POST" action="{{ route('manager.switch-plant') }}">
                                    @csrf
                                    <input type="hidden" name="plant_id" value="{{ $plant->id }}">
                                    <button type="submit" class="switch-plant-item {{ $effectivePlant?->id === $plant->id ? 'current' : '' }}">
                                        <span class="plant-icon">{{ strtoupper(substr($plant->plant, 0, 1)) }}</span>
                                        <span>{{ $plant->plant }}</span>
                                        @if($effectivePlant?->id === $plant->id)
                                            <i class="bi bi-check-circle-fill ms-auto text-success"></i>
                                        @endif
                                    </button>
                                </form>
                            </li>
                            @empty
                            <li>
                                <div class="px-3 py-2 text-center">
                                    <i class="bi bi-exclamation-circle text-warning d-block mb-1" style="font-size:1.3rem;"></i>
                                    <span style="font-size:0.8rem; color:#6c757d;">Belum ada plant yang<br>di-assign oleh SuperAdmin</span>
                                </div>
                            </li>
                            @endforelse
                            </div>
                            @if($authUser->active_plant_id)
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="POST" action="{{ route('manager.reset-plant') }}">
                                    @csrf
                                    <button type="submit" class="switch-plant-reset-btn">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        Kembali ke Plant Asal ({{ $originalPlant?->plant ?? '-' }})
                                    </button>
                                </form>
                            </li>
                            @endif
                        </ul>
                    </div>
                    @endif

                    <!-- Notifications -->
                        <div class="position-relative">
                            <button class="btn btn-link position-relative" id="notification-bell" style="color: #333; font-size: 1.5rem; border: none; padding: 0; outline: none; box-shadow: none;">
                                <i class="bi bi-bell"></i>
                                <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.55rem; padding: 0.25rem 0.4rem;">
                                    <span id="notification-count">0</span>
                                </span>
                            </button>
                            <div id="notification-dropdown" class="position-absolute top-100 end-0 mt-2 bg-white rounded shadow-lg" style="display: none; width: 250px; max-height: 400px; overflow-y: auto; z-index: 1000;">
                                <div class="p-3 border-bottom">
                                    <h6 class="mb-0">⏰ Edit Per 2 Jam Tersedia</h6>
                                </div>
                                <div id="notification-dropdown-list" class="p-3">
                                </div>
                            </div>
                        </div>

                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-link text-dark text-decoration-none d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0; outline: none; box-shadow: none;">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.9rem; font-weight: bold;">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="d-none d-md-inline small">{{ Auth::user()->name ?? 'User' }}</span>
                            <i class="bi bi-chevron-down small"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><h6 class="dropdown-header">{{ Auth::user()->name ?? 'User' }}</h6></li>
                            <li><small class="dropdown-header text-muted">{{ Auth::user()->email ?? 'email@example.com' }}</small></li>
                            <!-- <li><hr class="dropdown-divider"></li> -->
                            <!-- <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li> -->
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="border: none; background: none; cursor: pointer;">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        @include('partials.navbar')

        <!-- TOMBOL CLOSE SIDEBAR MOBILE (OUTSIDE SIDEBAR) -->
        <button type="button" id="sidebar-close-mobile" class="d-xl-none" style="position: fixed; top: 120px; right: 20px; z-index: 10000; display: none; align-items: center; justify-content: center; width: 40px; height: 40px; background: #dc3545; border: none; border-radius: 50%; color: #fff; box-shadow: 0 4px 16px rgba(220, 53, 69, 0.7); font-size: 28px; font-weight: bold; line-height: 1; cursor: pointer; padding: 0; outline: none;">
            ✕
        </button>

        <style>
        /* Remove focus outline dari semua button */
        button:focus,
        button:active,
        .btn:focus,
        .btn:active,
        .btn-link:focus,
        .btn-link:active {
            outline: none !important;
            box-shadow: none !important;
        }
        
        /* Khusus untuk button navigation */
        #notification-bell:focus,
        #notification-bell:active,
        #userDropdown:focus,
        #userDropdown:active,
        #sidebar-close-mobile:focus,
        #sidebar-close-mobile:active {
            outline: none !important;
            box-shadow: none !important;
        }
        </style>

        @yield('container')
    </div>

    <!-- Script JS -->
    <script src="{{asset('dist/vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dist/js/main.js')}}"></script>
    <!-- DataTable Script -->
    <script src="{{asset('dist/vendors/simple-datatables/simple-datatables.js')}}"></script>
    <!-- Choices.js Script -->
    <script src="{{asset('dist/vendors/choices.js/choices.min.js')}}"></script>
    <script src="{{asset('dist/vendors/jquery/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('dist/vendors/select2/js/select2.min.js')}}"></script>
    <!-- DataTables JS -->
    <script src="{{asset('dist/vendors/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('dist/vendors/datatables/dataTables.bootstrap5.min.js')}}"></script>
    <!-- CSRF Token Auto-Refresh Script -->
    <script src="{{ asset('js/csrf-refresh.js') }}"></script>
    
<script>
// Initialize DataTable for any table with id="table1"
document.addEventListener('DOMContentLoaded', function() {
    let table1 = document.querySelector('#table1');
    if (table1 && !(window.disableSimpleDatatables === true) && table1.getAttribute('data-disable-datatable') !== '1') {
        let dataTable = new simpleDatatables.DataTable(table1);
    }

    // Initialize Choices.js for all select with class 'choices'
    if (!(window.disableGlobalChoicesInit === true)) {
        const choicesElements = document.querySelectorAll('.choices');
        choicesElements.forEach(function(element) {
            // Skip if already initialized (check multiple flags)
            if (element && element.dataset && element.dataset.choicesInitialized === 'true') {
                return;
            }
            if (element && element._choices) {
                if (element.dataset) element.dataset.choicesInitialized = 'true';
                return;
            }
            if (element && element._choicesInstance) {
                if (element.dataset) element.dataset.choicesInitialized = 'true';
                return;
            }
            // Check if element has Choices internal class (already initialized)
            if (element && element.classList && element.classList.contains('choices__input')) {
                return;
            }
            if (element && element.parentElement && element.parentElement.classList && 
                element.parentElement.classList.contains('choices')) {
                return;
            }
            
            // Only initialize on SELECT, INPUT[type=text], or INPUT[type=hidden] elements
            if (!element || (element.tagName !== 'SELECT' && 
                (element.tagName !== 'INPUT' || (element.type !== 'text' && element.type !== 'hidden')))) {
                return;
            }
            
            try {
                element._choices = new Choices(element, {
                    searchResultLimit: 100,
                    fuseOptions: { 
                        ignoreLocation: true, 
                        threshold: 0.2, 
                        matchAllTokens: false,
                        distance: 1000
                    },
                    searchEnabled: true,
                    searchPlaceholderValue: 'Cari...',
                    itemSelectText: 'Tekan untuk memilih',
                    noResultsText: 'Tidak ada hasil ditemukan',
                    noChoicesText: 'Tidak ada pilihan tersedia',
                });
                if (element.dataset) element.dataset.choicesInitialized = 'true';
            } catch (e) {
                // Silent fail - element may already be initialized
            }
        });
    }

    if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.select2 === 'function') {
        window.jQuery('.select2-multiple').select2({
            width: '100%',
            placeholder: 'Pilih...'
        });
    }
});
// Fetch helper to send proper headers so Laravel knows it's an AJAX request (prevents redirect to API on session expire)
function fetchApi(url) {
    return fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    }).then(r => {
        if (!r.ok) throw new Error('Network response was not ok');
        return r.json();
    }).catch(err => {
        return { records: [] }; // Return empty array on error/timeout
    });
}

// Auto-check untuk notifikasi edit per 2 jam dengan list UUID dari V1 dan V2
function checkEditableRecords() {
    Promise.all([
        fetchApi('{{ route("api.editable-records") }}'),
        fetchApi('{{ route("api.editable-records-v2") }}')
    ])
    .then(([dataV1, dataV2]) => {
        const notification = document.getElementById('edit-reminder-notification');
        const recordsList = document.getElementById('editable-records-list');
        
        if (!notification || !recordsList) return;

        // Combine records dari V1 dan V2
        let allRecords = [];
        if (dataV1.records) allRecords = allRecords.concat(dataV1.records);
        if (dataV2.records) allRecords = allRecords.concat(dataV2.records);
        
        // Sort by updated_at descending
        allRecords.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
        
        if (allRecords.length > 0) {
            let html = '<div style="font-size: 0.9rem;">';
            allRecords.forEach(record => {
                html += `<div class="mb-2 pb-2" style="border-bottom: 1px solid rgba(255,255,255,0.1);">`;
                html += `<div><strong>${record.tanggal}</strong> - ${record.area}</div>`;
                html += `<div class="small text-muted mb-1">Shift: ${record.shift} | Sisa: ${record.time_formatted}</div>`;
                html += `<a href="${record.edit_url}" class="btn btn-xs btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Edit Sekarang</a>`;
                html += `</div>`;
            });
            html += '</div>';
            recordsList.innerHTML = html;
            notification.style.display = 'block';
        } else {
            notification.style.display = 'none';
        }
    })
    .catch(error => console.error('Error checking editable records:', error));
}

// Check saat page load
document.addEventListener('DOMContentLoaded', function() {
    checkEditableRecords();
    
    // Auto-check setiap 1 menit (60000 ms)
    setInterval(checkEditableRecords, 60000);
    
    // ===== SIDEBAR CLOSE BUTTON HANDLER (MOBILE/TABLET) =====
    const closeBtn = document.getElementById('sidebar-close-mobile');
    const sidebar = document.getElementById('sidebar');
    const burgerBtn = document.querySelector('.burger-btn');
    
    if (closeBtn && sidebar && burgerBtn) {
        // Show/hide close button based on sidebar state
        function updateCloseBtnVisibility() {
            if (window.innerWidth < 1200) {
                if (sidebar.classList.contains('active')) {
                    closeBtn.style.display = 'flex';
                } else {
                    closeBtn.style.display = 'none';
                }
            } else {
                closeBtn.style.display = 'none';
            }
        }
        
        // Initial check
        updateCloseBtnVisibility();
        
        // Watch for sidebar toggle
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    updateCloseBtnVisibility();
                }
            });
        });
        
        observer.observe(sidebar, { attributes: true });
        
        // Handle window resize
        window.addEventListener('resize', updateCloseBtnVisibility);
        
        // Handle close button click
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            sidebar.classList.remove('active');
            closeBtn.style.display = 'none';
        });
        
        // Hover effects
        closeBtn.addEventListener('mouseenter', function() {
            this.style.background = '#c82333';
            this.style.transform = 'scale(1.1)';
        });
        
        closeBtn.addEventListener('mouseleave', function() {
            this.style.background = '#dc3545';
            this.style.transform = 'scale(1)';
        });
    }
});
    // Handle notification bell dropdown
    const notificationBell = document.getElementById('notification-bell');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const notificationDropdownList = document.getElementById('notification-dropdown-list');
    const notificationBadge = document.getElementById('notification-badge');
    const notificationCount = document.getElementById('notification-count');

    if (notificationBell) {
        notificationBell.addEventListener('click', function(e) {
            e.preventDefault();
            notificationDropdown.style.display = notificationDropdown.style.display === 'none' ? 'block' : 'none';
        });
    }

// Update notification bell dengan data dari API V1 dan V2
function updateNotificationBell() {
    Promise.all([
        fetchApi('{{ route("api.editable-records") }}'),
        fetchApi('{{ route("api.editable-records-v2") }}')
    ])
    .then(([dataV1, dataV2]) => {
        // Combine records dari V1 dan V2
        let allRecords = [];
        if (dataV1.records) allRecords = allRecords.concat(dataV1.records);
        if (dataV2.records) allRecords = allRecords.concat(dataV2.records);
        
        // Sort by updated_at descending
        allRecords.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
        
        if (allRecords.length > 0) {
            notificationCount.textContent = allRecords.length;
            notificationBadge.style.display = 'block';
            
            let html = '';
            allRecords.forEach(record => {
                html += `<div class="notification-item">`;
                html += `<div class="notification-header"><strong>${record.tanggal}</strong> - ${record.area}</div>`;
                html += `<div class="notification-meta">Shift: ${record.shift} | Sisa: ${record.time_formatted}</div>`;
                html += `<a href="${record.edit_url}" class="notification-btn">Update Now</a>`;
                html += `</div>`;
            });
            notificationDropdownList.innerHTML = html;
        } else {
            notificationBadge.style.display = 'none';
            notificationDropdownList.innerHTML = '<p class="text-muted">Tidak ada data yang perlu diedit</p>';
        }
    })
    .catch(error => console.error('Error:', error));
}

// Update bell saat page load dan setiap 1 menit
updateNotificationBell();
setInterval(updateNotificationBell, 60000);

// Close dropdown saat klik di luar
document.addEventListener('click', function(e) {
    if (!e.target.closest('#notification-bell') && !e.target.closest('#notification-dropdown')) {
        notificationDropdown.style.display = 'none';
    }
});

/**
 * Sidebar Accordion Logic (Mazer Force Reset)
 * Memastikan hanya satu menu utama yang terbuka dalam satu waktu
 */
document.addEventListener('DOMContentLoaded', function() {
    const menuUl = document.querySelector('ul.menu[data-accordion="true"]');
    if (!menuUl) return;

    // Ambil semua link utama (Data Master, Pemeriksaan Kedatangan, dsb)
    const topLevelLinks = menuUl.querySelectorAll('.sidebar-item.has-sub > .sidebar-link');

    topLevelLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const currentItem = this.parentElement;

            // Jika menu yang diklik sudah aktif, biarkan template Mazer menangani toggle-nya sendiri
            // Tapi kita bersihkan SEMUA menu lain terlebih dahulu
            const allHasSub = menuUl.querySelectorAll('.sidebar-item.has-sub');
            
            allHasSub.forEach(item => {
                if (item !== currentItem) {
                    // Hapus class active agar menu lain tertutup
                    item.classList.remove('active');
                    
                    // Sembunyikan submenunya secara paksa
                    const submenu = item.querySelector('.submenu');
                    if (submenu) {
                        submenu.classList.remove('active');
                        // Gunakan !important lewat style attribute jika perlu, atau cukup display none
                        submenu.style.display = 'none';
                    }
                }
            });
        }, true); // UseCapture: true agar dieksekusi sebelum script Mazer
    });
});

// Switch Plant Search Filter
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchPlantInput');
    if (!searchInput) return;

    searchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase().trim();
        const items = document.querySelectorAll('.plant-list-item');
        items.forEach(function(item) {
            const name = item.getAttribute('data-plant-name') || '';
            item.style.display = name.includes(keyword) ? '' : 'none';
        });
    });

    // Prevent dropdown from closing when clicking on search input
    searchInput.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Focus on search input when dropdown opens
    const dropdownBtn = document.querySelector('#switchPlantDropdown .switch-plant-btn');
    if (dropdownBtn) {
        dropdownBtn.addEventListener('click', function() {
            setTimeout(function() { searchInput.focus(); }, 150);
        });
    }
});
</script>

<!-- ===== SESSION KEEP-ALIVE & WARNING POPUP (GLOBAL + MULTI-TAB SYNC) ===== -->
<script>
(function() {
    // Konfigurasi (sinkron dengan config/session.php lifetime = 480 menit)
    const SESSION_LIFETIME_MS  = 480 * 60 * 1000; // 480 menit (8 jam) — PRODUCTION
    const WARN_BEFORE_MS       = 10  * 60 * 1000;  // Peringatkan 10 menit sebelum expired
    const KEEPALIVE_INTERVAL   = 5   * 60 * 1000;  // Ping setiap 5 menit (background)
    const KEEPALIVE_URL        = '{{ route("keep-alive") }}';

    let sessionExpiresAt = Date.now() + SESSION_LIFETIME_MS;
    let warnTimer        = null;
    let expireTimer      = null;
    let countdownInterval= null;
    let popupShown       = false;
    let activityDebounce = null;

    // Multi-tab Broadcast Channel
    const csrfChannel = typeof BroadcastChannel !== 'undefined' ? new BroadcastChannel('csrf_token_sync_channel') : null;

    // ---------- Deteksi aktivitas user ----------
    const ACTIVITY_EVENTS = ['mousedown', 'mousemove', 'keydown', 'touchstart', 'scroll', 'click', 'input'];

    function onUserActivity() {
        if (activityDebounce) return;
        activityDebounce = setTimeout(function() {
            activityDebounce = null;
        }, 10000);

        if (!popupShown) {
            sessionExpiresAt = Date.now() + SESSION_LIFETIME_MS;
            scheduleWarning();
        }
    }

    ACTIVITY_EVENTS.forEach(function(event) {
        document.addEventListener(event, onUserActivity, { passive: true });
    });

    // ---------- Buat elemen popup ----------
    const overlay = document.createElement('div');
    overlay.id = 'session-warn-overlay';
    overlay.innerHTML = `
        <div id="session-warn-modal">
            <div id="session-warn-icon">⏳</div>
            <h5 id="session-warn-title">Sesi Akan Berakhir</h5>
            <p id="session-warn-msg">Sesi Anda akan berakhir dalam <strong id="session-countdown">10:00</strong>.<br>Apakah Anda masih aktif?</p>
            <div id="session-warn-buttons">
                <button id="session-stay-btn" class="btn-stay">Ya, Saya Masih Di Sini</button>
                <button id="session-logout-btn" class="btn-logout">Keluar</button>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);

    const style = document.createElement('style');
    style.textContent = `
        #session-warn-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 99999;
            align-items: center;
            justify-content: center;
        }
        #session-warn-overlay.show { display: flex; }
        #session-warn-modal {
            background: #fff;
            border-radius: 16px;
            padding: 2rem 2.5rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: swm-in .25s ease;
        }
        @keyframes swm-in {
            from { transform: scale(.85); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }
        #session-warn-icon { font-size: 3rem; margin-bottom: .5rem; }
        #session-warn-title { font-weight: 700; font-size: 1.25rem; margin-bottom: .5rem; color: #333; }
        #session-warn-msg { color: #555; font-size: .95rem; margin-bottom: 1.5rem; }
        #session-countdown { color: #dc3545; font-size: 1.1rem; }
        #session-warn-buttons { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; }
        .btn-stay {
            background: #435ebe; color: #fff; border: none;
            padding: .6rem 1.4rem; border-radius: 8px;
            font-weight: 600; cursor: pointer; font-size: .9rem;
            transition: background .2s;
        }
        .btn-stay:hover { background: #2f4aa6; }
        .btn-logout {
            background: #fff; color: #dc3545;
            border: 2px solid #dc3545;
            padding: .6rem 1.4rem; border-radius: 8px;
            font-weight: 600; cursor: pointer; font-size: .9rem;
            transition: all .2s;
        }
        .btn-logout:hover { background: #dc3545; color: #fff; }
    `;
    document.head.appendChild(style);

    function formatTime(ms) {
        const total = Math.max(0, Math.floor(ms / 1000));
        const m = Math.floor(total / 60);
        const s = total % 60;
        return m + ':' + String(s).padStart(2, '0');
    }

    function updateCsrfTokens(newToken, broadcast = true) {
        if (!newToken) return;
        
        // Update semua input _token di halaman ini
        document.querySelectorAll('input[name="_token"]').forEach(el => el.value = newToken);
        
        // Update meta tag csrf
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) meta.setAttribute('content', newToken);

        if (broadcast) {
            // Broadcast ke tab lain via BroadcastChannel
            if (csrfChannel) {
                try { csrfChannel.postMessage({ type: 'CSRF_REFRESH', token: newToken }); } catch(e){}
            }
            // Fallback via localStorage (memicu storage event di tab lain)
            try {
                localStorage.setItem('app_latest_csrf_token', newToken);
                localStorage.setItem('app_csrf_timestamp', Date.now().toString());
            } catch(e){}
        }
    }

    // Listen sync dari tab lain (BroadcastChannel)
    if (csrfChannel) {
        csrfChannel.onmessage = function(e) {
            if (e.data && e.data.type === 'CSRF_REFRESH' && e.data.token) {
                updateCsrfTokens(e.data.token, false);
                sessionExpiresAt = Date.now() + SESSION_LIFETIME_MS;
                scheduleWarning();
            }
        };
    }

    // Listen sync dari tab lain (window storage event fallback)
    window.addEventListener('storage', function(e) {
        if (e.key === 'app_latest_csrf_token' && e.newValue) {
            updateCsrfTokens(e.newValue, false);
            sessionExpiresAt = Date.now() + SESSION_LIFETIME_MS;
            scheduleWarning();
        }
    });

    // Intercept FORM SUBMIT: pastikan token yang dipakai adalah token paling baru sebelum dikirim!
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form || (form.method && form.method.toUpperCase() === 'GET')) return;

        const latestToken = localStorage.getItem('app_latest_csrf_token');
        if (latestToken) {
            const tokenInput = form.querySelector('input[name="_token"]');
            if (tokenInput && tokenInput.value !== latestToken) {
                tokenInput.value = latestToken;
            }
        }
    }, true);

    function refreshSession() {
        fetch(KEEPALIVE_URL, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'ok' && data.csrf_token) {
                updateCsrfTokens(data.csrf_token, true);
                sessionExpiresAt = Date.now() + SESSION_LIFETIME_MS;
                scheduleWarning();
            }
        })
        .catch(() => { /* silent fail */ });
    }

    function showPopup() {
        popupShown = true;
        overlay.classList.add('show');

        countdownInterval = setInterval(function() {
            if (!popupShown) {
                clearInterval(countdownInterval);
                countdownInterval = null;
                return;
            }
            const remaining = sessionExpiresAt - Date.now();
            const cdEl = document.getElementById('session-countdown');
            if (cdEl) cdEl.textContent = formatTime(remaining);
            if (remaining <= 0) {
                clearInterval(countdownInterval);
                countdownInterval = null;
                window.location.href = '/login';
            }
        }, 1000);
    }

    function hidePopup() {
        popupShown = false;
        overlay.classList.remove('show');
        clearInterval(countdownInterval);
        countdownInterval = null;
        clearTimeout(warnTimer);
        clearTimeout(expireTimer);
        warnTimer = null;
        expireTimer = null;
    }

    function scheduleWarning() {
        clearTimeout(warnTimer);
        clearTimeout(expireTimer);

        const timeUntilWarn   = SESSION_LIFETIME_MS - WARN_BEFORE_MS;
        const timeUntilExpire = SESSION_LIFETIME_MS;

        warnTimer = setTimeout(function() {
            if (!popupShown) showPopup();
        }, timeUntilWarn);

        expireTimer = setTimeout(function() {
            if (!popupShown) window.location.href = '/login';
        }, timeUntilExpire);
    }

    document.getElementById('session-stay-btn').addEventListener('click', function() {
        sessionExpiresAt = Date.now() + SESSION_LIFETIME_MS;
        hidePopup();
        refreshSession();
    });

    document.getElementById('session-logout-btn').addEventListener('click', function() {
        const logoutForm = document.querySelector('form[action*="logout"]');
        if (logoutForm) {
            logoutForm.submit();
        } else {
            window.location.href = '/login';
        }
    });

    // Background ping setiap 5 menit
    setInterval(function() {
        if (!popupShown) {
            refreshSession();
        }
    }, KEEPALIVE_INTERVAL);

    scheduleWarning();

})();
</script>
@stack('scripts')
</body>
</html>