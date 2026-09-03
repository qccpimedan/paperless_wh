<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative" style="padding: 1rem;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <span style="font-weight: 700; font-size: 1.1rem; color: #435ebe;">Menu</span>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu pt-2" data-accordion="true">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @php
                $userRole = auth()->user()->role ? strtolower(auth()->user()->role->role) : null;
                $isManagerUser = auth()->user() && auth()->user()->isManager();
                $activePlantInfo = ($isManagerUser) ? auth()->user()->getEffectivePlant() : null;
                @endphp
                
                {{-- Plant Info for Manager --}}
                @if($isManagerUser)
                <li class="sidebar-item" style="pointer-events: none; margin-bottom: 0.25rem;">
                    <div class="sidebar-link" style="background: linear-gradient(135deg, rgba(111,66,193,0.15) 0%, rgba(111,66,193,0.08) 100%); border-left: 3px solid #6f42c1; cursor: default; border-radius: 8px; margin: 0 6px;">
                        <i class="bi bi-building" style="color: #6f42c1;"></i>
                        <div style="display:flex; flex-direction:column; line-height:1.2;">
                            <span style="font-size:0.7rem; color:#6f42c1; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Plant Aktif</span>
                            <span style="font-size:0.85rem; font-weight:700; color:#2c3e50;">{{ $activePlantInfo?->plant ?? auth()->user()->plant?->plant ?? '-' }}</span>
                        </div>
                        @if(auth()->user()->active_plant_id && auth()->user()->active_plant_id !== auth()->user()->id_plant)
                            <span class="badge" style="font-size:0.6rem; background:#6f42c1; color:#fff; border-radius:10px; margin-left:auto;">Switched</span>
                        @endif
                    </div>
                </li>
                @endif
                
                {{-- Access Control - Only for Superadmin --}}
                @if($userRole === 'superadmin')
                <li class="sidebar-item {{ request()->routeIs('access-control.*') ? 'active' : '' }}">
                    <a href="{{ route('access-control.index') }}" class='sidebar-link'>
                        <i class="bi bi-shield-lock"></i>
                        <span>Access Control</span>
                    </a>
                </li>
                @endif
                
                {{-- Data Master - For Superadmin, Admin, SPV QC --}}
                @if($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'spv qc')
                <li class="sidebar-item has-sub {{ request()->routeIs('roles.*') || request()->routeIs('plants.*') || request()->routeIs('users.*') || request()->routeIs('barangs.*') 
                    || request()->routeIs('bahans.*') || request()->routeIs('customers.*') || request()->routeIs('shifts.*') || request()->routeIs('distributors.*') 
                    || request()->routeIs('produsens.*') || request()->routeIs('chemicals.*') || request()->routeIs('jenis-kendaraans.*') 
                    || request()->routeIs('tujuan-pengirimans.*') || request()->routeIs('supirs.*') 
                    || request()->routeIs('produks.*') || request()->routeIs('bahan-kemasans.*') 
                    || request()->routeIs('ekspedisis.*') || request()->routeIs('std-precoolings.*') || request()->routeIs('input-areas.*') 
                    || request()->routeIs('input-master-forms.*') || request()->routeIs('input-deskripsis.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-collection-fill"></i>
                        <span>Data Master</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('roles.*') || request()->routeIs('plants.*') || request()->routeIs('users.*') || request()->routeIs('barangs.*') || request()->routeIs('bahans.*') 
                    || request()->routeIs('customers.*') || request()->routeIs('shifts.*') || request()->routeIs('distributors.*') 
                    || request()->routeIs('produsens.*') || request()->routeIs('chemicals.*') || request()->routeIs('jenis-kendaraans.*') || request()->routeIs('tujuan-pengirimans.*') 
                    || request()->routeIs('supirs.*') || request()->routeIs('produks.*') || request()->routeIs('ekspedisis.*') || request()->routeIs('std-precoolings.*') 
                    || request()->routeIs('input-areas.*') || request()->routeIs('bahan-kemasans.*')
                    || request()->routeIs('input-master-forms.*') || request()->routeIs('input-deskripsis.*') ? 'active' : '' }}">
                        {{-- Input Role, Plant, User - Only for Superadmin --}}
                        @if($userRole === 'superadmin')
                            <li class="submenu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <a href="{{ route('roles.index') }}">Input Role</a>
                            </li>
                        @endif

                        @if(in_array($userRole, ['superadmin', 'admin']))
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
                            <a href="{{ route('barangs.index')}}">Input Barang Mudah Pecah</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <a href="{{ route('customers.index')}}">Input Customer</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('distributors.*') ? 'active' : '' }}">
                            <a href="{{ route('distributors.index')}}">Input Distributor</a>
                            <li class="submenu-item {{ request()->routeIs('tujuan-pengirimans.*') ? 'active' : '' }}">
                                <a href="{{ route('tujuan-pengirimans.index')}}">Input Tujuan Pengiriman</a>
                            </li>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('produsens.*') ? 'active' : '' }}">
                            <a href="{{ route('produsens.index')}}">Input Produsen</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('produks.*') ? 'active' : '' }}">
                            <a href="{{ route('produks.index')}}">Input Produk</a>
                        </li>
                        <!-- <li class="submenu-item {{ request()->routeIs('bahans.*') ? 'active' : '' }}">
                            <a href="{{ route('bahans.index')}}">Input Bahan Baku</a>
                        </li> -->
                        <!-- <li class="submenu-item {{ request()->routeIs('bahan-kemasans.*') ? 'active' : '' }}">
                            <a href="{{ route('bahan-kemasans.index')}}">Input Bahan Kemasan</a>
                        </li> -->
                        <!-- <li class="submenu-item {{ request()->routeIs('chemicals.*') ? 'active' : '' }}">
                            <a href="{{ route('chemicals.index')}}">Input Chemical</a>
                        </li> -->
                        <li class="submenu-item {{ request()->routeIs('supirs.*') ? 'active' : '' }}">
                            <a href="{{ route('supirs.index')}}">Input Supir</a>
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
                        <li class="submenu-item {{ request()->routeIs('input-master-forms.*') ? 'active' : '' }}">
                            <a href="{{ route('input-master-forms.index')}}">Input Master Form</a>
                        </li>

                    </ul>
                </li>
                @endif

                <!-- <li class="sidebar-title">Form QC SYSTEM</li> -->

                {{-- Forms QC - For Superadmin, Admin, SPV QC, QC Inspector, Produksi, Manager --}}
                @if($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'spv qc' || $userRole === 'qc inspector' || $userRole === 'produksi' || $isManagerUser)
                {{-- Pemeriksaan Kedatangan --}}
                <li class="sidebar-item has-sub {{ request()->routeIs('pemeriksaan-kedatangan-kemasan.*') || 
                request()->routeIs('pemeriksaan-bahan-baku.*') || request()->routeIs('pemeriksaan-chemical.*') || 
                request()->routeIs('pemeriksaan-produk-finish-good.*') 
                ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-hexagon-fill"></i>
                        <span>Pemeriksaan Kedatangan</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('pemeriksaan-kedatangan-kemasan.*') || request()->routeIs('pemeriksaan-bahan-baku.*') || request()->routeIs('pemeriksaan-chemical.*') || request()->routeIs('pemeriksaan-produk-finish-good.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-kedatangan-kemasan.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}">Kemasan</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-bahan-baku.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-bahan-baku.index') }}">Bahan Baku Penunjang</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-chemical.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-chemical.index') }}">Chemical</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('pemeriksaan-produk-finish-good.*') ? 'active' : '' }}">
                            <a href="{{ route('pemeriksaan-produk-finish-good.index') }}">Produk Finish Good</a>
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
                            <a href="{{ route('pemeriksaan-suhu-ruang.index') }}">Food Processing</a>
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
                        <span>Komplain Suplier</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('detail-komplain.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->routeIs('detail-komplain.*') ? 'active' : '' }}">
                            <a href="{{ route('detail-komplain.index') }}">Sample Produk</a>
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