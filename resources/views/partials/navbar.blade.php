<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between align-items-center">
                <!-- <div class="logo">
                    <img src="{{ asset('dist/images/logo/logo8.png') }}" alt="Logo" style="width: 130px; height: auto;">
                </div> -->
                <div class="d-flex align-items-center gap-2">
                    <!-- <div class="position-relative">
                        <button class="btn btn-link position-relative" id="notification-bell" style="color: #333; font-size: 1.5rem; border: none; padding: 0;">
                            <i class="bi bi-bell"></i>
                            <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                                <span id="notification-count">0</span>
                            </span>
                        </button>
                        <div id="notification-dropdown" class="position-absolute top-100 end-0 mt-2 bg-white rounded shadow-lg" style="display: none; width: 350px; max-height: 400px; overflow-y: auto; z-index: 1000;">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0">⏰ Edit Per 2 Jam Tersedia</h6>
                            </div>
                            <div id="notification-dropdown-list" class="p-3">
                            </div>
                        </div>
                    </div> -->
                    <div class="toggler">
                        <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu pt-3">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @php
                $userRole = auth()->user()->role ? strtolower(auth()->user()->role->role) : null;
                @endphp
                <li class="sidebar-item {{ request()->routeIs('access-control.*') ? 'active' : '' }}">
                    <a href="{{ route('access-control.index') }}" class='sidebar-link'>
                        <i class="bi bi-shield-lock"></i>
                        <span>Access Control</span>
                    </a>
                </li>
                <li class="sidebar-item has-sub {{ request()->routeIs('roles.*') || request()->routeIs('plants.*') || request()->routeIs('users.*') || request()->routeIs('barangs.*') 
                    || request()->routeIs('bahans.*') || request()->routeIs('customers.*') || request()->routeIs('shifts.*') || request()->routeIs('distributors.*') 
                    || request()->routeIs('produsens.*') || request()->routeIs('chemicals.*') || request()->routeIs('jenis-kendaraans.*') 
                    || request()->routeIs('tujuan-pengirimans.*') || request()->routeIs('supirs.*') 
                    || request()->routeIs('produks.*') || request()->routeIs('ekspedisis.*') || request()->routeIs('std-precoolings.*') || request()->routeIs('input-areas.*') 
                    || request()->routeIs('input-master-forms.*') || request()->routeIs('input-deskripsis.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-collection-fill"></i>
                        <span>Data Master</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('roles.*') || request()->routeIs('plants.*') || request()->routeIs('users.*') || request()->routeIs('barangs.*') || request()->routeIs('bahans.*') 
                    || request()->routeIs('customers.*') || request()->routeIs('shifts.*') || request()->routeIs('distributors.*') 
                    || request()->routeIs('produsens.*') || request()->routeIs('chemicals.*') || request()->routeIs('jenis-kendaraans.*') || request()->routeIs('tujuan-pengirimans.*') 
                    || request()->routeIs('supirs.*') || request()->routeIs('produks.*') || request()->routeIs('ekspedisis.*') || request()->routeIs('std-precoolings.*') 
                    || request()->routeIs('input-areas.*') || request()->routeIs('input-master-forms.*') || request()->routeIs('input-deskripsis.*') ? 'active' : '' }}">
                        @if($userRole === 'superadmin')
                        <li class="submenu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <a href="{{ route('roles.index') }}">Input Role</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('plants.*') ? 'active' : '' }}">
                            <a href="{{ route('plants.index') }}">Input Plant</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}">Input User</a>
                        </li>
                        @endif
                        <li class="submenu-item {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                            <a href="{{ route('shifts.index') }}">Input Shift</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('input-areas.*') ? 'active' : '' }}">
                            <a href="{{ route('input-areas.index') }}">Input Area</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('barangs.*') ? 'active' : '' }}">
                            <a href="{{ route('barangs.index')}}">Input Barang</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('bahans.*') ? 'active' : '' }}">
                            <a href="{{ route('bahans.index')}}">Input Bahan</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <a href="{{ route('customers.index')}}">Input Customer</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('distributors.*') ? 'active' : '' }}">
                            <a href="{{ route('distributors.index')}}">Input Distributor</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('produsens.*') ? 'active' : '' }}">
                            <a href="{{ route('produsens.index')}}">Input Produsen</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('chemicals.*') ? 'active' : '' }}">
                            <a href="{{ route('chemicals.index')}}">Input Chemical</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('supirs.*') ? 'active' : '' }}">
                            <a href="{{ route('supirs.index')}}">Input Supir</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('produks.*') ? 'active' : '' }}">
                            <a href="{{ route('produks.index')}}">Input Produk</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('ekspedisis.*') ? 'active' : '' }}">
                            <a href="{{ route('ekspedisis.index')}}">Input Ekspedisi</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('input-deskripsis.*') ? 'active' : '' }}">
                            <a href="{{ route('input-deskripsis.index')}}">Input Deskripsi</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('std-precoolings.*') ? 'active' : '' }}">
                            <a href="{{ route('std-precoolings.index')}}">Input Std Precooling</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('jenis-kendaraans.*') ? 'active' : '' }}">
                            <a href="{{ route('jenis-kendaraans.index')}}">Input Jenis Kendaraan</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('tujuan-pengirimans.*') ? 'active' : '' }}">
                            <a href="{{ route('tujuan-pengirimans.index')}}">Input Tujuan Pengiriman</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('input-master-forms.*') ? 'active' : '' }}">
                            <a href="{{ route('input-master-forms.index')}}">Input Master Form</a>
                        </li>

                    </ul>
                </li>

                <li class="sidebar-title">Forms QC SISTEM</li>

                @if($userRole === 'superadmin' || $userRole === 'qc inspector' || $userRole === 'produksi' || $userRole === 'admin' || $userRole === 'spv qc')
                <li class="sidebar-item has-sub {{ request()->routeIs('pemeriksaan-kedatangan-kemasan.*') || 
                request()->routeIs('pemeriksaan-bahan-baku.*') || request()->routeIs('pemeriksaan-chemical.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-hexagon-fill"></i>
                        <span>Pemeriksaan Kedatangan</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('pemeriksaan-kedatangan-kemasan.*') || request()->routeIs('pemeriksaan-bahan-baku.*') || request()->routeIs('pemeriksaan-chemical.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-kedatangan-kemasan.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}">Kemasan</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-bahan-baku.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-bahan-baku.index') }}">Bahan Baku Penunjang</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-chemical.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-chemical.index') }}">Chemical</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub {{ request()->routeIs('pemeriksaan-loading-produk.*') || request()->routeIs('pemeriksaan-loading-kendaraan.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-file-earmark-medical-fill"></i>
                        <span>Pemeriksaan Loading</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('pemeriksaan-loading-produk.*') || request()->routeIs('pemeriksaan-loading-kendaraan.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-loading-produk.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-loading-produk.index') }}">Loading Produk</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-loading-kendaraan.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-loading-kendaraan.index') }}">Loading Kendaraan</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub {{ request()->routeIs('return-barang.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-pen-fill"></i>
                        <span>Pemeriksaan Return Barang</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('return-barang.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->routeIs('return-barang.*') ? 'active' : '' }}">
                            <a href="{{ route('return-barang.index') }}">Return Barang Customer</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub {{ request()->routeIs('pemeriksaan-kebersihan-area.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Pemeriksaan Kebersihan Area</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('pemeriksaan-kebersihan-area.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-kebersihan-area.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-kebersihan-area.index') }}">Kebersihan Area</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub {{ request()->routeIs('pemeriksaan-suhu-ruang.*') || request()->routeIs('pemeriksaan-suhu-ruang-v2.*') || request()->routeIs('pemeriksaan-suhu-ruang-v3.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                        <span>Pemeriksaan Suhu Ruang</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('pemeriksaan-suhu-ruang.*') || request()->routeIs('pemeriksaan-suhu-ruang-v2.*') || request()->routeIs('pemeriksaan-suhu-ruang-v3.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-suhu-ruang.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-suhu-ruang.index') }}">Food Prosesing</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-suhu-ruang-v2.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-suhu-ruang-v2.index') }}">CS Meat <span id="v2-badge" class="badge bg-danger" style="display: none;"></span></a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-suhu-ruang-v3.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}">Gudang Dry <span id="v2-badge" class="badge bg-danger" style="display: none;"></span></a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub {{ request()->routeIs('golden-sample-reports.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-file-earmark-check-fill"></i>
                        <span>Golden Sample</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('golden-sample-reports.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->routeIs('golden-sample-reports.*') ? 'active' : '' }}">
                            <a href="{{ route('golden-sample-reports.index') }}">Report List</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub {{ request()->routeIs('detail-komplain.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span>Detail Komplain</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('detail-komplain.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->routeIs('detail-komplain.*') ? 'active' : '' }}">
                            <a href="{{ route('detail-komplain.index') }}">Komplain Produk</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub {{ request()->routeIs('pemeriksaan-barang-mudah-pecah.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-archive-fill"></i>
                        <span>Pemeriksaan Barang Mudah Pecah</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('pemeriksaan-barang-mudah-pecah.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-barang-mudah-pecah.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-barang-mudah-pecah.index') }}">Daftar Pemeriksaan</a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>