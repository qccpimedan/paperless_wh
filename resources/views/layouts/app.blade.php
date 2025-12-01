<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paperless QC-WH</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('dist/css/bootstrap.css')}}">

    <link rel="stylesheet" href="{{asset('dist/vendors/perfect-scrollbar/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{asset('dist/vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('dist/vendors/simple-datatables/style.css')}}">
    <!-- Choices.js CSS -->
    <link rel="stylesheet" href="{{asset('dist/vendors/choices.js/choices.min.css')}}">
    <link rel="icon" href="{{asset('dist/images/logo/logo5.png')}}" type="image/x-icon">
    
    <style>
        /* ===== Navbar & Sidebar Z-Index ===== */
        .navbar.sticky-top {
            z-index: 1050 !important;
            position: sticky !important;
        }
        
        #sidebar {
            z-index: 1000 !important;
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
                font-size: 0.8rem;
            }
            
            .notification-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
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
                    <!-- Notifications -->
                        <div class="position-relative">
                            <button class="btn btn-link position-relative" id="notification-bell" style="color: #333; font-size: 1.5rem; border: none; padding: 0;">
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
                        <button class="btn btn-link text-dark text-decoration-none d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0;">
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
    
    
<script>
// Initialize DataTable for any table with id="table1"
document.addEventListener('DOMContentLoaded', function() {
    let table1 = document.querySelector('#table1');
    if (table1) {
        let dataTable = new simpleDatatables.DataTable(table1);
    }

    // Initialize Choices.js for all select with class 'choices'
    const choicesElements = document.querySelectorAll('.choices');
    choicesElements.forEach(function(element) {
        new Choices(element, {
            searchEnabled: true,
            searchPlaceholderValue: 'Cari...',
            itemSelectText: 'Tekan untuk memilih',
            noResultsText: 'Tidak ada hasil ditemukan',
            noChoicesText: 'Tidak ada pilihan tersedia',
        });
    });
});
// Auto-check untuk notifikasi edit per 2 jam dengan list UUID dari V1 dan V2
function checkEditableRecords() {
    Promise.all([
        fetch('{{ route("api.editable-records") }}').then(r => r.json()),
        fetch('{{ route("api.editable-records-v2") }}').then(r => r.json())
    ])
    .then(([dataV1, dataV2]) => {
        const notification = document.getElementById('edit-reminder-notification');
        const recordsList = document.getElementById('editable-records-list');
        
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
        fetch('{{ route("api.editable-records") }}').then(r => r.json()),
        fetch('{{ route("api.editable-records-v2") }}').then(r => r.json())
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
</script>
@stack('scripts')
</body>
</html>