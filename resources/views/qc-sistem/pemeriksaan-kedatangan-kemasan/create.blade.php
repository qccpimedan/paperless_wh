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
                    <h3>Tambah Pemeriksaan Kedatangan Kemasan</h3>
                    <p class="text-subtitle text-muted">Input data pemeriksaan kedatangan kemasan baru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}">Pemeriksaan Kedatangan Kemasan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Form Input Pemeriksaan Kedatangan Kemasan</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form id="form-pemeriksaan-kedatangan-kemasan" data-autosave="true" class="form form-horizontal" action="{{ route('pemeriksaan-kedatangan-kemasan.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <!-- Informasi Dasar -->
                                    <div class="form-section mb-4">
                                        <h5 class="text-primary mb-3">Informasi Dasar</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                    <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                        name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                                    @error('tanggal')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_shift">Shift</label>
                                                    <select id="id_shift" class="form-control @error('id_shift') is-invalid @enderror" name="id_shift">
                                                        <option value="">Pilih Shift</option>
                                                          @foreach($shifts as $shift)
                                                            <option value="{{ $shift->id }}" {{ old('id_shift') == $shift->id ? 'selected' : '' }}>
                                                                {{ $shift->shift }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_shift')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jenis_pemeriksaan">Jenis Pemeriksaan</label>
                                                    <input type="text" id="jenis_pemeriksaan" class="form-control @error('jenis_pemeriksaan') is-invalid @enderror"
                                                        name="jenis_pemeriksaan" value="{{ old('jenis_pemeriksaan') }}" placeholder="Jenis Pemeriksaan">
                                                    @error('jenis_pemeriksaan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div> -->
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="jenis_mobil">Jenis Mobil</label>
                                                    <input type="text" id="jenis_mobil" class="form-control @error('jenis_mobil') is-invalid @enderror"
                                                        name="jenis_mobil" value="{{ old('jenis_mobil') }}" placeholder="Jenis Mobil">
                                                    @error('jenis_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="no_mobil">No. Mobil</label>
                                                    <input type="text" id="no_mobil" class="form-control @error('no_mobil') is-invalid @enderror"
                                                        name="no_mobil" value="{{ old('no_mobil') }}" placeholder="No. Mobil">
                                                    @error('no_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nama_supir">Nama Supir</label>
                                                    <input type="text" id="nama_supir" class="form-control @error('nama_supir') is-invalid @enderror"
                                                        name="nama_supir" value="{{ old('nama_supir') }}" placeholder="Nama Supir">
                                                    @error('nama_supir')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="no_po">No. PO</label>
                                                    <input type="text" id="no_po" class="form-control @error('no_po') is-invalid @enderror"
                                                        name="no_po" value="{{ old('no_po') }}" placeholder="No. PO">
                                                    @error('no_po')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>Segel/Gembok</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="segel_option" name="segel_gembok" value="segel" onchange="toggleNoSegelField(this)" {{ old('segel_gembok') == 'segel' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="segel_option">
                                                            Segel
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="gembok_option" name="segel_gembok" value="gembok" onchange="toggleNoSegelField(this)" {{ old('segel_gembok') == 'gembok' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="gembok_option">
                                                            Gembok
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="no_segel_container" style="display: {{ old('segel_gembok') == 'segel' ? 'block' : 'none' }};">
                                                <div class="form-group">
                                                    <label for="no_segel">No. Segel</label>
                                                    <input type="text" id="no_segel" class="form-control @error('no_segel') is-invalid @enderror"
                                                        name="no_segel" value="{{ old('no_segel') }}" placeholder="No. Segel">
                                                    @error('no_segel')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <script>
                                            // Function untuk toggle No. Segel field
                                            function toggleNoSegelField(radio) {
                                                const container = document.getElementById('no_segel_container');
                                                const noSegelInput = document.getElementById('no_segel');
                                                
                                                if (!container || !noSegelInput) {
                                                    console.warn('No segel container or input not found');
                                                    return;
                                                }
                                                
                                                if (radio.value === 'segel') {
                                                    container.style.display = 'block';
                                                    console.log('Showing No. Segel field');
                                                } else {
                                                    container.style.display = 'none';
                                                    noSegelInput.value = '';
                                                    console.log('Hiding No. Segel field');
                                                }
                                            }
                                        </script>
                                    </div>
                                    
                                    <!-- Kondisi Mobil Pengangkut -->
                                    <div class="form-section mb-4" id="kondisi-mobil-section">
                                        <h5 class="text-primary mb-3">Kondisi Mobil Pengangkut</h5>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="kondisi_mobil_check_all" onchange="toggleAllKondisiMobil(this)">
                                                <label class="form-check-label" for="kondisi_mobil_check_all">Centang semua (Ya)</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <!-- 1. Bersih -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>1. Bersih</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_ya" value="1" {{ old('kondisi_mobil.bersih') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bersih_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_tidak" value="0" {{ old('kondisi_mobil.bersih') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bersih_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>

                                                <!-- 2. Bebas dari hama -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>2. Bebas dari hama</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_ya" value="1" {{ old('kondisi_mobil.bebas_hama') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_hama_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_tidak" value="0" {{ old('kondisi_mobil.bebas_hama') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_hama_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>

                                                <!-- 3. Tidak Kondensasi -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>3. Tidak Kondensasi</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_ya" value="1" {{ old('kondisi_mobil.tidak_kondensasi') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_kondensasi_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_tidak" value="0" {{ old('kondisi_mobil.tidak_kondensasi') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_kondensasi_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>

                                                <!-- 4. Bebas dari Produk Halal -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>4. Bebas dari Produk Non Halal</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_ya" value="1" {{ old('kondisi_mobil.bebas_produk_halal') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_produk_halal_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_tidak" value="0" {{ old('kondisi_mobil.bebas_produk_halal') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_produk_halal_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <!-- 5. Tidak Berbau Menyimpang -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>5. Tidak Berbau Menyimpang</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_ya" value="1" {{ old('kondisi_mobil.tidak_berbau') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_berbau_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_tidak" value="0" {{ old('kondisi_mobil.tidak_berbau') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_berbau_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>

                                                <!-- 6. Tidak ada sampah -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>6. Tidak ada sampah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_ya" value="1" {{ old('kondisi_mobil.tidak_ada_sampah') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_ada_sampah_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_tidak" value="0" {{ old('kondisi_mobil.tidak_ada_sampah') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_ada_sampah_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>

                                                <!-- 7. Tidak ada pertumbuhan mikroba -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>7. Tidak ada pertumbuhan mikroba</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_ya" value="1" {{ old('kondisi_mobil.tidak_ada_mikroba') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_ada_mikroba_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_tidak" value="0" {{ old('kondisi_mobil.tidak_ada_mikroba') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tidak_ada_mikroba_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>

                                                <!-- 8. Lampu dan Cover tidak pecah -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>8. Lampu dan Cover tidak pecah</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_ya" value="1" {{ old('kondisi_mobil.lampu_cover_utuh') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="lampu_cover_utuh_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_tidak" value="0" {{ old('kondisi_mobil.lampu_cover_utuh') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="lampu_cover_utuh_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <!-- 9. Pallet / Alas Utuh -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>9. Pallet / Alas Utuh</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_ya" value="1" {{ old('kondisi_mobil.pallet_utuh') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pallet_utuh_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_tidak" value="0" {{ old('kondisi_mobil.pallet_utuh') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pallet_utuh_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>

                                                <!-- 10. Tertutup rapat/tidak bocor -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>10. Tertutup rapat/tidak bocor</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_ya" value="1" {{ old('kondisi_mobil.tertutup_rapat') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tertutup_rapat_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_tidak" value="0" {{ old('kondisi_mobil.tertutup_rapat') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tertutup_rapat_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>

                                                <!-- 11. Bebas dari Kontaminan -->
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>11. Bebas dari Kontaminan</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_ya" value="1" {{ old('kondisi_mobil.bebas_kontaminan') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_kontaminan_ya">Ya ✓</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_tidak" value="0" {{ old('kondisi_mobil.bebas_kontaminan') == '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="bebas_kontaminan_tidak">Tidak ✗</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        // Function untuk toggle semua radio button kondisi mobil
                                        function toggleAllKondisiMobil(checkbox) {
                                            const section = document.getElementById('kondisi-mobil-section');
                                            if (!section) {
                                                alert('Section kondisi mobil tidak ditemukan!');
                                                return;
                                            }

                                            // Cari semua radio button dalam section kondisi mobil
                                            const allRadios = section.querySelectorAll('input[type="radio"][name^="kondisi_mobil["]');
                                            console.log('Total radio buttons found:', allRadios.length);
                                            
                                            if (checkbox.checked) {
                                                // Centang semua radio button dengan value="1" (Ya)
                                                let checkedCount = 0;
                                                allRadios.forEach(function(radio) {
                                                    if (radio.value === '1') {
                                                        radio.checked = true;
                                                        checkedCount++;
                                                    }
                                                });
                                                console.log('Checked', checkedCount, 'radio buttons');
                                            } else {
                                                // Uncheck semua radio buttons
                                                allRadios.forEach(function(radio) {
                                                    radio.checked = false;
                                                });
                                                console.log('Unchecked all radio buttons');
                                            }
                                        }
                                    </script>

                                    <!-- UNIFIED DYNAMIC FORM - Bahan Kemasan, Informasi Kemasan, Kondisi Fisik, Detail Tambahan, Dokumen -->
                                    <!-- DYNAMIC ROWS CONTAINER -->
                                    <div id="unified-container">
                                        <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;" data-row-index="0">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="mb-0 bahan-title">Bahan #1</h5>
                                            </div>
                                            <!-- Bahan Kemasan -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Bahan Kemasan</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Kategori</label>
                                                            <select class="form-control kategori-produk-select" data-role="kategori_code">
                                                                <option value="">Pilih Kategori</option>
                                                                @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                    <option value="{{ $kategori }}" {{ old('kategori_code.0') == $kategori ? 'selected' : '' }}>
                                                                        {{ $kategori }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Produk</label>
                                                            <select class="form-control produk-select" data-role="id_produk">
                                                                <option value="">Pilih Produk</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Informasi Kemasan & Supplier -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Informasi Kemasan & Supplier</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-body p-3">
                                                                <div class="fw-semibold">Produsen</div>
                                                                <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>

                                                                @php
                                                                    $oldProdusen0 = old('produsen.0', []);
                                                                    $oldProdusen0 = is_array($oldProdusen0) ? $oldProdusen0 : [$oldProdusen0];
                                                                    $oldProdusen0 = array_values(array_filter($oldProdusen0, fn ($v) => $v !== null && $v !== ''));
                                                                @endphp

                                                                <div class="produsen-badges d-flex flex-wrap gap-1">
                                                                    @forelse ($oldProdusen0 as $p)
                                                                        <span class="badge bg-light-primary text-primary badge-removable" data-value="{{ $p }}">
                                                                            {{ $p }}
                                                                            <button type="button" class="badge-remove-btn" title="Hapus produsen ini dari form" onclick="removeBadgeItem(this, 'produsen')">×</button>
                                                                        </span>
                                                                    @empty
                                                                        <span class="text-muted small badge-empty">-</span>
                                                                    @endforelse
                                                                </div>

                                                                <div class="produsen-hidden-inputs">
                                                                    @foreach ($oldProdusen0 as $p)
                                                                        <input type="hidden" class="produsen-hidden-item" value="{{ $p }}">
                                                                    @endforeach
                                                                </div>

                                                                @error('produsen.0')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-body p-3">
                                                                <div class="fw-semibold">Distributor</div>
                                                                <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>

                                                                @php
                                                                    $oldDistributor0 = old('distributor.0', []);
                                                                    $oldDistributor0 = is_array($oldDistributor0) ? $oldDistributor0 : [$oldDistributor0];
                                                                    $oldDistributor0 = array_values(array_filter($oldDistributor0, fn ($v) => $v !== null && $v !== ''));
                                                                @endphp

                                                                <div class="distributor-badges d-flex flex-wrap gap-1">
                                                                    @forelse ($oldDistributor0 as $d)
                                                                        <span class="badge bg-light-info text-info badge-removable" data-value="{{ $d }}">
                                                                            {{ $d }}
                                                                            <button type="button" class="badge-remove-btn" title="Hapus distributor ini dari form" onclick="removeBadgeItem(this, 'distributor')">×</button>
                                                                        </span>
                                                                    @empty
                                                                        <span class="text-muted small badge-empty">-</span>
                                                                    @endforelse
                                                                </div>

                                                                <div class="distributor-hidden-inputs">
                                                                    @foreach ($oldDistributor0 as $d)
                                                                        <input type="hidden" class="distributor-hidden-item" value="{{ $d }}">
                                                                    @endforeach
                                                                </div>

                                                                @error('distributor.0')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="detail-items">
                                                <div class="detail-item mb-3 p-3 border rounded" data-detail-index="0" style="background-color: #ffffff;">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="fw-bold detail-title">Detail #1</span>
                                                        <button type="button" class="btn btn-danger btn-sm remove-detail-btn" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                                                    </div>
                                                    <input type="hidden" name="kategori_code[]" class="kategori-code-hidden" value="{{ old('kategori_code.0') }}">
                                                    <input type="hidden" name="id_produk[]" class="produk-id-hidden" value="{{ old('id_produk.0') }}">

                                                    <div class="produsen-hidden-inputs"></div>
                                                    <div class="distributor-hidden-inputs"></div>

                                                    <input type="hidden" name="penampakan[]" class="penampakan-hidden" value="{{ old('penampakan.0') }}">
                                                    <input type="hidden" name="sealing[]" class="sealing-hidden" value="{{ old('sealing.0') }}">
                                                    <input type="hidden" name="cetakan[]" class="cetakan-hidden" value="{{ old('cetakan.0') }}">
                                                    <input type="hidden" name="logo_halal[]" class="logo-halal-hidden" value="{{ old('logo_halal.0') }}">
                                                    <input type="hidden" name="dokumen_halal[]" class="dokumen-halal-hidden" value="{{ old('dokumen_halal.0') }}">
                                                    <input type="hidden" name="coa[]" class="coa-hidden" value="{{ old('coa.0') }}">

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Kode Produksi</label>
                                                                <input type="text" class="form-control @error('kode_produksi.0') is-invalid @enderror" name="kode_produksi[]" value="{{ old('kode_produksi.0') }}" placeholder="Kode Produksi">
                                                                @error('kode_produksi.0')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Jumlah Datang</label>
                                                                <div class="input-group" style="max-width: 100%;">
                                                                    <input type="text" class="form-control @error('jumlah_datang.0') is-invalid @enderror" name="jumlah_datang[]" value="{{ old('jumlah_datang.0') }}" placeholder="Jumlah" min="0" step="any">
                                                                    <select class="form-select @error('unit_datang.0') is-invalid @enderror" name="unit_datang[]" style="max-width: 120px;">
                                                                        <option value="">Pilih Parameter</option>
                                                                        @foreach(\App\Models\PemeriksaanKedatanganKemasan::unitParameters() as $key => $label)
                                                                            <option value="{{ $key }}" {{ old('unit_datang.0') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @error('jumlah_datang.0')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                                @error('unit_datang.0')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Jumlah Sampling</label>
                                                                <div class="input-group" style="max-width: 100%;">
                                                                    <input type="text" class="form-control @error('jumlah_sampling.0') is-invalid @enderror" name="jumlah_sampling[]" value="{{ old('jumlah_sampling.0') }}" placeholder="Jumlah" min="0" step="any">
                                                                    <select class="form-select @error('unit_sampling.0') is-invalid @enderror" name="unit_sampling[]" style="max-width: 120px;">
                                                                        <option value="">Pilih Parameter</option>
                                                                        @foreach(\App\Models\PemeriksaanKedatanganKemasan::unitParameters() as $key => $label)
                                                                            <option value="{{ $key }}" {{ old('unit_sampling.0') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @error('jumlah_sampling.0')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                                @error('unit_sampling.0')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Spesifikasi</label>
                                                                <textarea class="form-control @error('spesifikasi.0') is-invalid @enderror" name="spesifikasi[]" rows="2" placeholder="Spesifikasi">{{ old('spesifikasi.0') }}</textarea>
                                                                @error('spesifikasi.0')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-section mb-3 mt-3">
                                                        <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label"><strong>Penampakan</strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="penampakan_master_0" value="1" {{ old('penampakan.0') == '1' ? 'checked' : '' }}>
                                                                        <label class="form-check-label">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="penampakan_master_0" value="0" {{ old('penampakan.0') == '0' ? 'checked' : '' }}>
                                                                        <label class="form-check-label">Tidak ✗</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label"><strong>Sealing</strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="sealing_master_0" value="1" {{ old('sealing.0') == '1' ? 'checked' : '' }}>
                                                                        <label class="form-check-label">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="sealing_master_0" value="0" {{ old('sealing.0') == '0' ? 'checked' : '' }}>
                                                                        <label class="form-check-label">Tidak ✗</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label"><strong>Cetakan</strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="cetakan_master_0" value="1" {{ old('cetakan.0') == '1' ? 'checked' : '' }}>
                                                                        <label class="form-check-label">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="cetakan_master_0" value="0" {{ old('cetakan.0') == '0' ? 'checked' : '' }}>
                                                                        <label class="form-check-label">Tidak ✗</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-section mb-3">
                                                        <h6 class="text-primary mb-2">Detail Tambahan</h6>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="form-label">Ketebalan (Micron)</label>
                                                                    <input type="number" step="0.01" class="form-control @error('ketebalan_micron.0') is-invalid @enderror" name="ketebalan_micron[]" value="{{ old('ketebalan_micron.0') }}" placeholder="Ketebalan">
                                                                    @error('ketebalan_micron.0')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="form-label">Dimensi</label>
                                                                    <input type="text" class="form-control @error('dimensi.0') is-invalid @enderror" name="dimensi[]" value="{{ old('dimensi.0') }}" placeholder="Dimensi">
                                                                    @error('dimensi.0')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="form-label">Status</label>
                                                                    <select class="form-control @error('status.0') is-invalid @enderror" name="status[]">
                                                                        <option value="">Pilih Status</option>
                                                                        <option value="Hold" {{ old('status.0') == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                                        <option value="Release" {{ old('status.0') == 'Release' ? 'selected' : '' }}>Release</option>
                                                                    </select>
                                                                    @error('status.0')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Keterangan</label>
                                                                    <textarea class="form-control @error('keterangan.0') is-invalid @enderror" name="keterangan[]" rows="2" placeholder="Keterangan tambahan">{{ old('keterangan.0') }}</textarea>
                                                                    @error('keterangan.0')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-section mb-3">
                                                        <h6 class="text-primary mb-2">Upload Gambar</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Gambar Kemasan</label>
                                                                    <input type="file" name="image_kemasan[]" class="form-control image-kemasan-input" accept="image/*" capture="camera">
                                                                    @error('image_kemasan.0')
                                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Dokumen</h6>
                                                <input type="hidden" class="doc-master-logo" value="{{ old('logo_halal.0') }}">
                                                <input type="hidden" class="doc-master-dokumen" value="{{ old('dokumen_halal.0') }}">
                                                <input type="hidden" class="doc-master-coa" value="{{ old('coa.0') }}">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Logo Halal</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="logo_halal_master_1" value="1" {{ old('logo_halal.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="logo_halal_master_1" value="0" {{ old('logo_halal.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal_master_1" value="1" {{ old('dokumen_halal.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal_master_1" value="0" {{ old('dokumen_halal.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>COA</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="coa_master_1" value="1" {{ old('coa.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="coa_master_1" value="0" {{ old('coa.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-primary btn-sm add-detail-btn"><i class="bi bi-plus"></i> Tambah Detail</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Unified Buttons -->
                                            <div class="row mt-3 pt-3 border-top">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-danger btn-sm remove-unified-btn" style="display: none;"><i class="bi bi-trash"></i> Hapus Produk</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3 pt-3 border-top">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-success btn-sm add-unified-btn"><i class="bi bi-plus"></i> Tambah Produk</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end mt-3">
                                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" id="btn-kembali" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan Data</button>
                                        <!-- <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>

<style>
    .collapse-toggle-btn {
        width: auto;
        display: inline-flex;
        align-items: center;
        text-align: left;
    }
    .collapse-toggle-btn.full-width {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    .collapse-chevron { transition: transform .2s ease; }
    .collapse-toggle-btn[aria-expanded="true"] .collapse-chevron { transform: rotate(180deg); }

    /* Badge hapus tombol */
    .badge-removable {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding-right: 4px;
    }
    .badge-remove-btn {
        background: none;
        border: none;
        padding: 0 2px;
        font-size: 13px;
        font-weight: bold;
        line-height: 1;
        cursor: pointer;
        color: #dc3545;
        border-radius: 50%;
        transition: color .15s, background .15s;
    }
    .badge-remove-btn:hover {
        color: #fff;
        background: #dc3545;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const choicesInstances = new WeakMap();
    
    const btnKembali = document.getElementById('btn-kembali');
    if (btnKembali) {
        btnKembali.addEventListener('click', function(e) {
            const ok = confirm('Data belum disimpan. Yakin ingin kembali ke halaman index?');
            if (!ok) {
                e.preventDefault();
            }
        });
    }
    
    // Initialize Choices.js for all existing selects
    function initializeAllChoices() {
        const selects = document.querySelectorAll('select.choices');
        selects.forEach(select => {
            if (select.classList && select.classList.contains('produk-select')) {
                return;
            }
            if (!select.dataset.choicesInitialized) {
                const instance = new Choices(select, {
                    searchResultLimit: 100,
                    searchFuzziness: 0.000001,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
                    searchEnabled: true,
                    searchPlaceholderValue: 'Cari...',
                    itemSelectText: 'Tekan untuk memilih',
                    noResultsText: 'Tidak ada hasil ditemukan',
                    noChoicesText: 'Tidak ada pilihan tersedia',
                    removeItemButton: true,
                    shouldSort: false,
                    placeholder: true,
                    placeholderValue: 'Pilih...'
                });
                choicesInstances.set(select, instance);
                select.dataset.choicesInitialized = 'true';
            }
        });
    }
    
    // Initialize on page load
    initializeAllChoices();
    
    const produkByKategori = @json($produkByKategori ?? []);
    const produkMeta = @json($produkMeta ?? []);
    const oldKategoriCodes = @json(old('kategori_code', []));
    const oldProdukIds = @json(old('id_produk', []));

    window.produkByKategori = produkByKategori;
    window.produkMeta = produkMeta;

    const bsCollapse = (el) => {
        try {
            if (!el || !window.bootstrap || !window.bootstrap.Collapse) return null;
            return window.bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
        } catch (e) {
            return null;
        }
    };

    const uniqueDomId = (prefix) => {
        let id;
        do {
            id = `${prefix}_${Date.now()}_${Math.random().toString(16).slice(2)}`;
        } while (document.getElementById(id));
        return id;
    };

    const ensureProdukCollapsible = (rowEl, rowIdx) => {
        if (!rowEl) return;
        if (!rowEl.dataset.produkCollapseId) {
            rowEl.dataset.produkCollapseId = `produk_c_${Date.now()}_${Math.random().toString(16).slice(2)}`;
        }
        const collapseId = rowEl.dataset.produkCollapseId;

        const header = rowEl.firstElementChild;
        if (!header) return;

        const titleEl = header.querySelector('.bahan-title');
        if (titleEl && !titleEl.querySelector('button[data-bs-toggle="collapse"]')) {
            const existingText = (titleEl.textContent || '').trim();
            titleEl.textContent = '';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-primary btn-sm d-flex align-items-center gap-2 collapse-toggle-btn full-width';
            btn.setAttribute('data-bs-toggle', 'collapse');
            btn.setAttribute('data-bs-target', `#${collapseId}`);
            btn.setAttribute('aria-expanded', 'true');
            btn.setAttribute('aria-controls', collapseId);

            const span = document.createElement('span');
            span.className = 'mb-0 bahan-title produk-collapse-label text-white';
            span.textContent = existingText || `Bahan #${rowIdx + 1}`;

            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down collapse-chevron text-white';

            btn.appendChild(span);
            btn.appendChild(icon);
            titleEl.appendChild(btn);
        }
        const existingBtn = titleEl ? titleEl.querySelector('button[data-bs-toggle="collapse"]') : null;
        if (existingBtn && !existingBtn.querySelector('.collapse-chevron')) {
            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down collapse-chevron text-white';
            existingBtn.appendChild(icon);
        }

        let body = rowEl.querySelector(`:scope > .produk-collapse.collapse#${collapseId}`);
        if (!body) {
            body = document.createElement('div');
            body.className = 'produk-collapse collapse show';
            body.id = collapseId;

            const nodesToMove = [];
            let node = header.nextSibling;
            while (node) {
                const next = node.nextSibling;
                nodesToMove.push(node);
                node = next;
            }
            nodesToMove.forEach((n) => body.appendChild(n));
            rowEl.appendChild(body);
        }
    };

    const ensureDetailCollapsible = (detailEl) => {
        if (!detailEl) return;
        let collapseId = detailEl.dataset.detailCollapseId || '';

        // If this element was cloned, it may carry duplicate ids/targets.
        // Regenerate when the id is missing OR already used elsewhere.
        const hasDuplicateId = (id) => {
            if (!id) return true;
            const el = document.getElementById(id);
            if (!el) return false;
            // If the element with this id is not inside this detail, it's a duplicate for this detail.
            return !detailEl.contains(el);
        };

        if (hasDuplicateId(collapseId)) {
            collapseId = uniqueDomId('detail_c');
            detailEl.dataset.detailCollapseId = collapseId;
        }

        const header = detailEl.firstElementChild;
        if (!header) return;

        const titleEl = header.querySelector('.detail-title');
        if (titleEl && titleEl.tagName.toLowerCase() !== 'button') {
            const existingText = (titleEl.textContent || '').trim();

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-primary btn-sm fw-bold d-inline-flex align-items-center gap-2 collapse-toggle-btn detail-title';
            btn.setAttribute('data-bs-toggle', 'collapse');
            btn.setAttribute('data-bs-target', `#${collapseId}`);
            btn.setAttribute('aria-expanded', 'true');
            btn.setAttribute('aria-controls', collapseId);

            const span = document.createElement('span');
            span.className = 'detail-collapse-label';
            span.textContent = existingText || 'Detail';

            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down collapse-chevron text-white';

            btn.appendChild(span);
            btn.appendChild(icon);
            titleEl.replaceWith(btn);
        }

        const existingBtn = header.querySelector('button.detail-title[data-bs-toggle="collapse"]');
        if (existingBtn && !existingBtn.querySelector('.collapse-chevron')) {
            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down collapse-chevron text-white';
            existingBtn.appendChild(icon);
        }

        let body = detailEl.querySelector(':scope > .detail-collapse.collapse');
        if (body) {
            body.id = collapseId;
        } else {
            body = document.createElement('div');
            body.className = 'detail-collapse collapse show';
            body.id = collapseId;

            const nodesToMove = [];
            let node = header.nextSibling;
            while (node) {
                const next = node.nextSibling;
                nodesToMove.push(node);
                node = next;
            }
            nodesToMove.forEach((n) => body.appendChild(n));
            detailEl.appendChild(body);
        }

        // Ensure button target points to this unique id
        const btn = detailEl.querySelector(':scope > .d-flex button.detail-title[data-bs-toggle="collapse"], :scope > .d-flex button[data-bs-toggle="collapse"].detail-title');
        if (btn) {
            btn.setAttribute('data-bs-target', `#${collapseId}`);
            btn.setAttribute('aria-controls', collapseId);
        }
    };

    const updateProdukLabel = (rowEl, rowIdx) => {
        if (!rowEl) return;
        const labelEl = rowEl.querySelector('.produk-collapse-label');
        if (!labelEl) return;

        const produkSelect = rowEl.querySelector('select.produk-select');
        const selectedText = produkSelect && produkSelect.selectedOptions && produkSelect.selectedOptions[0]
            ? String(produkSelect.selectedOptions[0].textContent || '').trim()
            : '';
        labelEl.textContent = selectedText || `Bahan #${rowIdx + 1}`;
    };

    const updateDetailLabel = (detailEl, detailIdxWithinRow) => {
        if (!detailEl) return;
        const labelEl = detailEl.querySelector('.detail-collapse-label');
        if (!labelEl) return;

        const kodeInp = detailEl.querySelector('input[name="kode_produksi[]"]');
        const kodeVal = kodeInp ? String(kodeInp.value || '').trim() : '';
        labelEl.textContent = kodeVal || `Detail #${detailIdxWithinRow + 1}`;
    };

    const collapseAllProdukExcept = (activeRowEl) => {
        document.querySelectorAll('#unified-container .unified-row').forEach((rowEl) => {
            const body = rowEl.querySelector(':scope > .produk-collapse.collapse');
            if (!body) return;
            const inst = bsCollapse(body);
            if (inst) {
                if (rowEl === activeRowEl) inst.show();
                else inst.hide();
                return;
            }
            if (rowEl === activeRowEl) body.classList.add('show');
            else body.classList.remove('show');
        });
    };

    const collapseOtherDetailsInRow = (rowEl, activeDetailEl) => {
        if (!rowEl) return;
        rowEl.querySelectorAll('.detail-item').forEach((detailEl) => {
            const body = detailEl.querySelector(':scope > .detail-collapse.collapse');
            if (!body) return;
            const inst = bsCollapse(body);
            if (inst) {
                if (detailEl === activeDetailEl) inst.show();
                else inst.hide();
                return;
            }

            // Fallback if Bootstrap Collapse is not available
            if (detailEl === activeDetailEl) body.classList.add('show');
            else body.classList.remove('show');
        });
    };

    const initKemasanCollapses = () => {
        const rows = Array.from(document.querySelectorAll('#unified-container .unified-row'));
        rows.forEach((rowEl, rowIdx) => {
            ensureProdukCollapsible(rowEl, rowIdx);
            updateProdukLabel(rowEl, rowIdx);

            const details = Array.from(rowEl.querySelectorAll('.detail-item'));
            details.forEach((d, di) => {
                ensureDetailCollapsible(d);
                updateDetailLabel(d, di);
            });
        });
    };

    initKemasanCollapses();

    // Add unified row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-unified-btn')) {
            const container = document.getElementById('unified-container');
            const newRow = document.createElement('div');
            newRow.className = 'unified-row mb-4 p-3 border rounded';
            newRow.style.backgroundColor = '#f8f9fa';
            
            const timestamp = Date.now();
            const rowIndex = container.querySelectorAll('.unified-row').length;
            newRow.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 bahan-title">Bahan #${rowIndex + 1}</h5>
                </div>
                <!-- Bahan Kemasan -->
                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Bahan Kemasan</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select class="choices form-control kategori-produk-select" data-role="kategori_code">
                                    <option value="">Pilih Kategori</option>
                                    @foreach(($produkKategoriOptions ?? []) as $kategori)
                                        <option value="{{ $kategori }}">{{ $kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Produk</label>
                                <select class="choices form-control produk-select" data-role="id_produk">
                                    <option value="">Pilih Produk</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Kemasan & Supplier -->
                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Informasi Kemasan & Supplier</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="fw-semibold">Produsen</div>
                                    <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                    <div class="produsen-badges d-flex flex-wrap gap-1"><span class="text-muted small">-</span></div>
                                    <div class="produsen-hidden-inputs"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="fw-semibold">Distributor</div>
                                    <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                    <div class="distributor-badges d-flex flex-wrap gap-1"><span class="text-muted small">-</span></div>
                                    <div class="distributor-hidden-inputs"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-items">
                    <div class="detail-item mb-3 p-3 border rounded" data-detail-index="0" style="background-color: #ffffff;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold detail-title">Detail #1</span>
                            <button type="button" class="btn btn-danger btn-sm remove-detail-btn" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                        </div>
                        <input type="hidden" name="kategori_code[]" class="kategori-code-hidden" value="">
                        <input type="hidden" name="id_produk[]" class="produk-id-hidden" value="">
                        <div class="produsen-hidden-inputs"></div>
                        <div class="distributor-hidden-inputs"></div>

                        <input type="hidden" name="penampakan[]" class="penampakan-hidden" value="">
                        <input type="hidden" name="sealing[]" class="sealing-hidden" value="">
                        <input type="hidden" name="cetakan[]" class="cetakan-hidden" value="">
                        <input type="hidden" name="logo_halal[]" class="logo-halal-hidden" value="">
                        <input type="hidden" name="dokumen_halal[]" class="dokumen-halal-hidden" value="">
                        <input type="hidden" name="coa[]" class="coa-hidden" value="">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Kode Produksi</label>
                                    <input type="text" class="form-control" name="kode_produksi[]" placeholder="Kode Produksi">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Jumlah Datang</label>
                                    <div class="input-group" style="max-width: 100%;">
                                        <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah" min="0" step="any">
                                        <select class="form-select" name="unit_datang[]" style="max-width: 120px;">
                                            <option value="">Pilih Parameter</option>
                                            <option value="kg">kg</option>
                                            <option value="gram">gram</option>
                                            <option value="pcs">pcs</option>
                                            <option value="roll">roll</option>
                                            <option value="karung">karung</option>
                                            <option value="box">box</option>
                                            <option value="lembar">lembar</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Jumlah Sampling</label>
                                    <div class="input-group" style="max-width: 100%;">
                                        <input type="text" class="form-control" name="jumlah_sampling[]" placeholder="Jumlah" min="0" step="any">
                                        <select class="form-select" name="unit_sampling[]" style="max-width: 120px;">
                                            <option value="">Pilih Parameter</option>
                                            <option value="kg">kg</option>
                                            <option value="gram">gram</option>
                                            <option value="pcs">pcs</option>
                                            <option value="roll">roll</option>
                                            <option value="karung">karung</option>
                                            <option value="box">box</option>
                                            <option value="lembar">lembar</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Spesifikasi</label>
                                    <textarea class="form-control" name="spesifikasi[]" rows="2" placeholder="Spesifikasi"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3 mt-3">
                            <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Penampakan</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="penampakan_master_0" value="1">
                                            <label class="form-check-label">Ya ✓</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="penampakan_master_0" value="0">
                                            <label class="form-check-label">Tidak ✗</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Sealing</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sealing_master_0" value="1">
                                            <label class="form-check-label">Ya ✓</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sealing_master_0" value="0">
                                            <label class="form-check-label">Tidak ✗</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Cetakan</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="cetakan_master_0" value="1">
                                            <label class="form-check-label">Ya ✓</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="cetakan_master_0" value="0">
                                            <label class="form-check-label">Tidak ✗</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3">
                            <h6 class="text-primary mb-2">Detail Tambahan</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Ketebalan (Micron)</label>
                                        <input type="number" step="0.01" class="form-control" name="ketebalan_micron[]" placeholder="Ketebalan">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Dimensi</label>
                                        <input type="text" class="form-control" name="dimensi[]" placeholder="Dimensi">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <select class="form-control" name="status[]">
                                            <option value="">Pilih Status</option>
                                            <option value="Hold">Hold</option>
                                            <option value="Release">Release</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Keterangan</label>
                                        <textarea class="form-control" name="keterangan[]" rows="2" placeholder="Keterangan tambahan"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3">
                            <h6 class="text-primary mb-2">Upload Gambar</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Gambar Kemasan</label>
                                        <input type="file" name="image_kemasan[]" class="form-control image-kemasan-input" accept="image/*" capture="camera">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Dokumen</h6>
                    <input type="hidden" class="doc-master-logo" value="">
                    <input type="hidden" class="doc-master-dokumen" value="">
                    <input type="hidden" class="doc-master-coa" value="">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>Logo Halal</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="logo_halal_master_1" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="logo_halal_master_1" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>Dokumen Halal</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dokumen_halal_master_1" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dokumen_halal_master_1" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>COA</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coa_master_1" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coa_master_1" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-sm add-detail-btn"><i class="bi bi-plus"></i> Tambah Detail</button>
                    </div>
                </div>

                <!-- Unified Buttons -->
                <div class="row mt-3 pt-3 border-top">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Produk</button>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            newRow.dataset.rowIndex = String(rowIndex);

            initKemasanCollapses();
            collapseAllProdukExcept(newRow);
            
            // Initialize Choices.js for new selects ONLY in the new row
            const newSelects = newRow.querySelectorAll('select.choices');
            newSelects.forEach(select => {
                if (select.classList && select.classList.contains('produk-select')) {
                    return;
                }
                const instance = new Choices(select, {
                    searchResultLimit: 100,
                    searchFuzziness: 0.000001,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
                    searchEnabled: true,
                    searchPlaceholderValue: 'Cari...',
                    itemSelectText: 'Tekan untuk memilih',
                    noResultsText: 'Tidak ada hasil ditemukan',
                    noChoicesText: 'Tidak ada pilihan tersedia',
                    removeItemButton: true,
                    shouldSort: false,
                    placeholder: true,
                    placeholderValue: 'Pilih...'
                });
                choicesInstances.set(select, instance);
            });

            const kategoriSelect = newRow.querySelector('select.kategori-produk-select');
            if (kategoriSelect) {
                kategoriSelect.addEventListener('change', function() {
                    populateProdukOptionsForRow(newRow);
                });
                kategoriSelect.addEventListener('addItem', function() {
                    setTimeout(() => populateProdukOptionsForRow(newRow), 0);
                });

                if (kategoriSelect.value) {
                    populateProdukOptionsForRow(newRow);
                }
            }

            const produkSelect = newRow.querySelector('select.produk-select');
            if (produkSelect) {
                produkSelect.addEventListener('change', function() {
                    applyProdukMetaForRow(newRow);
                });
                produkSelect.addEventListener('addItem', function() {
                    applyProdukMetaForRow(newRow);
                });
            }
        }
    });

    function bindProdukSelectEvents(rowEl) {
        const produkSelect = rowEl.querySelector('select.produk-select');
        if (!produkSelect) return;

        produkSelect.addEventListener('change', function() {
            applyProdukMetaForRow(rowEl);
        });
        produkSelect.addEventListener('addItem', function() {
            applyProdukMetaForRow(rowEl);
        });
    }

    function populateProdukOptionsForRow(rowEl) {
        const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
        const produkSelect = rowEl.querySelector('select.produk-select');

        if (!kategoriSelect || !produkSelect) return;

        syncHeaderToDetails(rowEl);

        const kategori = kategoriSelect.value;
        const rawOptions = (produkByKategori && produkByKategori[kategori]) ? produkByKategori[kategori] : [];
        const options = Array.isArray(rawOptions) ? rawOptions : Object.values(rawOptions || {});

        if (!kategori) {
            console.warn('[QC] populateProdukOptionsForRow: kategori kosong');
        } else if (!options || options.length === 0) {
            console.warn('[QC] Tidak ada produk untuk kategori:', kategori);
        } else {
            console.debug('[QC] populateProdukOptionsForRow kategori:', kategori, 'jumlah produk:', options.length);
        }

        const choiceItems = [{ value: '', label: 'Pilih Produk', selected: true }].concat(
            options.map((opt) => ({
                value: String(opt.id),
                label: opt.nama,
            }))
        );

        const desiredProdukId = rowEl.dataset.oldProdukId ? String(rowEl.dataset.oldProdukId) : '';

        if (rowEl._populateProdukTimer) {
            clearTimeout(rowEl._populateProdukTimer);
        }

        rowEl._populateProdukTimer = setTimeout(() => {
            const existing = choicesInstances.get(produkSelect);
            if (existing) {
                try {
                    existing.destroy();
                } catch (e) {
                    // ignore
                }
                choicesInstances.delete(produkSelect);
            }

            const freshProdukSelect = produkSelect.cloneNode(false);
            freshProdukSelect.innerHTML = '';
            delete freshProdukSelect.dataset.choicesInitialized;
            freshProdukSelect.removeAttribute('data-choices-initialized');

            choiceItems.forEach((it) => {
                const o = document.createElement('option');
                o.value = String(it.value);
                o.textContent = it.label;
                if (it.selected) o.selected = true;
                freshProdukSelect.appendChild(o);
            });

            const wrapper = produkSelect.closest('.choices');
            if (wrapper && wrapper.parentNode) {
                wrapper.parentNode.replaceChild(freshProdukSelect, wrapper);
            } else if (produkSelect.parentNode) {
                produkSelect.parentNode.replaceChild(freshProdukSelect, produkSelect);
            }

            const instance = new Choices(freshProdukSelect, {
                searchResultLimit: 100,
                    searchFuzziness: 0.000001,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
                    searchEnabled: true,
                searchPlaceholderValue: 'Cari...',
                itemSelectText: 'Tekan untuk memilih',
                noResultsText: 'Tidak ada hasil ditemukan',
                noChoicesText: 'Tidak ada pilihan tersedia',
                placeholder: true,
                placeholderValue: 'Pilih...'
            });
            choicesInstances.set(freshProdukSelect, instance);
            freshProdukSelect.dataset.choicesInitialized = 'true';

            if (desiredProdukId) {
                instance.setChoiceByValue(desiredProdukId);
            }

            applyProdukMetaForRow(rowEl);
        }, 50);
    }

    function applyProdukMetaForRow(rowEl) {
        const produkSelect = rowEl.querySelector('select.produk-select');
        const produsenBadges = rowEl.querySelector('.produsen-badges');
        const distributorBadges = rowEl.querySelector('.distributor-badges');
        const produsenHidden = rowEl.querySelector('.produsen-hidden-inputs');
        const distributorHidden = rowEl.querySelector('.distributor-hidden-inputs');

        if (!produkSelect || !produsenBadges || !distributorBadges || !produsenHidden || !distributorHidden) return;

        const rowIndex = rowEl.dataset.rowIndex ? String(rowEl.dataset.rowIndex) : '0';

        const produkId = produkSelect.value;
        const meta = produkMeta[produkId];
        if (!meta) {
            produsenBadges.innerHTML = '<span class="text-muted small">-</span>';
            distributorBadges.innerHTML = '<span class="text-muted small">-</span>';
            produsenHidden.innerHTML = '';
            distributorHidden.innerHTML = '';
            return;
        }

        const normalizeMulti = (v) => {
            if (Array.isArray(v)) return v.map(x => String(x));
            if (v === null || v === undefined) return [];
            const s = String(v);
            return s ? [s] : [];
        };

        const prodVals = normalizeMulti(meta.produsen);
        const distVals = normalizeMulti(meta.distributor);

        const renderBadges = (containerEl, values, badgeClass, type) => {
            if (!values || values.length === 0) {
                containerEl.innerHTML = '<span class="text-muted small badge-empty">-</span>';
                return;
            }
            containerEl.innerHTML = '';
            values.forEach((v) => {
                const span = document.createElement('span');
                span.className = badgeClass + ' badge-removable';
                span.dataset.value = String(v);

                const textNode = document.createTextNode(String(v) + ' ');
                span.appendChild(textNode);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'badge-remove-btn';
                btn.title = 'Hapus ' + (type === 'produsen' ? 'produsen' : 'distributor') + ' ini dari form';
                btn.textContent = '×';
                btn.setAttribute('onclick', `removeBadgeItem(this, '${type}')`);
                span.appendChild(btn);

                containerEl.appendChild(span);
            });
        };

        const renderHiddenInputs = (containerEl, className, values) => {
            containerEl.innerHTML = '';
            (values || []).forEach((v) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.className = className;
                input.value = String(v);
                containerEl.appendChild(input);
            });
        };

        renderBadges(produsenBadges, prodVals, 'badge bg-light-primary text-primary', 'produsen');
        renderBadges(distributorBadges, distVals, 'badge bg-light-info text-info', 'distributor');
        renderHiddenInputs(produsenHidden, 'produsen-hidden-item', prodVals);
        renderHiddenInputs(distributorHidden, 'distributor-hidden-item', distVals);

        syncHeaderToDetails(rowEl);
    }

    function syncHeaderToDetails(rowEl) {
        window.syncHeaderToDetailsGlobal = syncHeaderToDetails; // expose ke global untuk removeBadgeItem
        const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
        const produkSelect = rowEl.querySelector('select.produk-select');

        const kategoriVal = kategoriSelect ? String(kategoriSelect.value || '') : '';
        const produkVal = produkSelect ? String(produkSelect.value || '') : '';

        // Baca nilai produsen/distributor dari badge yang MASIH ADA di UI (setelah user hapus)
        // Bukan dari hidden input header, agar badge yang sudah dihapus user tidak ikut sync
        const headerProdusenBadges = rowEl.querySelector(':scope > .produk-collapse .produsen-badges, .produsen-badges');
        const headerDistributorBadges = rowEl.querySelector(':scope > .produk-collapse .distributor-badges, .distributor-badges');

        const getValuesFromBadges = (containerEl) => {
            if (!containerEl) return [];
            return Array.from(containerEl.querySelectorAll('.badge-removable[data-value]'))
                       .map(el => String(el.dataset.value || '').trim())
                       .filter(v => v !== '');
        };

        const produsenVals = getValuesFromBadges(headerProdusenBadges);
        const distributorVals = getValuesFromBadges(headerDistributorBadges);

        // Update juga hidden inputs di header agar konsisten
        const headerProdusenHidden = rowEl.querySelector('.produsen-hidden-inputs');
        const headerDistributorHidden = rowEl.querySelector('.distributor-hidden-inputs');
        if (headerProdusenHidden) {
            headerProdusenHidden.innerHTML = '';
            produsenVals.forEach(v => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.className = 'produsen-hidden-item';
                inp.value = v;
                headerProdusenHidden.appendChild(inp);
            });
        }
        if (headerDistributorHidden) {
            headerDistributorHidden.innerHTML = '';
            distributorVals.forEach(v => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.className = 'distributor-hidden-item';
                inp.value = v;
                headerDistributorHidden.appendChild(inp);
            });
        }

        rowEl.querySelectorAll('.detail-item').forEach((detailEl) => {
            const kategoriHidden = detailEl.querySelector('input.kategori-code-hidden');
            const produkHidden = detailEl.querySelector('input.produk-id-hidden');
            if (kategoriHidden) kategoriHidden.value = kategoriVal;
            if (produkHidden) produkHidden.value = produkVal;

            const prodWrap = detailEl.querySelector('.produsen-hidden-inputs');
            const distWrap = detailEl.querySelector('.distributor-hidden-inputs');

            if (prodWrap) {
                prodWrap.innerHTML = '';
                produsenVals.forEach((v) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.value = v;
                    prodWrap.appendChild(input);
                });
            }

            if (distWrap) {
                distWrap.innerHTML = '';
                distributorVals.forEach((v) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.value = v;
                    distWrap.appendChild(input);
                });
            }
        });

        updateDetailIndices();
    }

    function updateDetailIndices() {
        const allDetails = Array.from(document.querySelectorAll('#unified-container .detail-item'));
        allDetails.forEach((detailEl, idx) => {
            detailEl.dataset.detailIndex = String(idx);

            const setArrayName = (el, base) => {
                if (!el) return;
                el.name = `${base}[]`;
            };

            setArrayName(detailEl.querySelector('input[name^="kode_produksi"], input[name="kode_produksi[]"]'), 'kode_produksi');
            setArrayName(detailEl.querySelector('input[name^="jumlah_datang"], input[name="jumlah_datang[]"]'), 'jumlah_datang');
            setArrayName(detailEl.querySelector('input[name^="jumlah_sampling"], input[name="jumlah_sampling[]"]'), 'jumlah_sampling');
            setArrayName(detailEl.querySelector('textarea[name^="spesifikasi"], textarea[name="spesifikasi[]"]'), 'spesifikasi');
            setArrayName(detailEl.querySelector('input[name^="ketebalan_micron"], input[name="ketebalan_micron[]"]'), 'ketebalan_micron');
            setArrayName(detailEl.querySelector('input[name^="dimensi"], input[name="dimensi[]"]'), 'dimensi');
            setArrayName(detailEl.querySelector('select[name^="status"], select[name="status[]"]'), 'status');
            setArrayName(detailEl.querySelector('textarea[name^="keterangan"], textarea[name="keterangan[]"]'), 'keterangan');
            setArrayName(detailEl.querySelector('input[type="file"][name^="image_kemasan"], input[type="file"][name="image_kemasan[]"]'), 'image_kemasan');

            const masterRadioGroups = ['penampakan', 'sealing', 'cetakan', 'logo_halal', 'dokumen_halal', 'coa'];
            masterRadioGroups.forEach((g) => {
                detailEl.querySelectorAll(`input[type="radio"][name^="${g}_master_"]`).forEach((r) => {
                    r.name = `${g}_master_${idx}`;
                });
            });

            const prodWrap = detailEl.querySelector('.produsen-hidden-inputs');
            const distWrap = detailEl.querySelector('.distributor-hidden-inputs');
            if (prodWrap) {
                prodWrap.querySelectorAll('input[type="hidden"]').forEach((inp) => {
                    inp.name = `produsen[${idx}][]`;
                });
            }
            if (distWrap) {
                distWrap.querySelectorAll('input[type="hidden"]').forEach((inp) => {
                    inp.name = `distributor[${idx}][]`;
                });
            }
        });

        updateSectionLabels();

        document.querySelectorAll('#unified-container .unified-row').forEach((rowEl) => {
            const details = rowEl.querySelectorAll('.detail-item');
            details.forEach((d) => {
                const rm = d.querySelector('.remove-detail-btn');
                if (rm) {
                    rm.style.display = details.length > 1 ? '' : 'none';
                }
            });
        });
    }

    document.addEventListener('click', function(e) {
        const addBtn = e.target.closest('.add-detail-btn');
        if (addBtn) {
            const rowEl = addBtn.closest('.unified-row');
            if (!rowEl) return;
            const container = rowEl.querySelector('.detail-items');
            const first = container ? container.querySelector('.detail-item') : null;
            if (!container || !first) return;

            const clone = first.cloneNode(true);
            clone.querySelectorAll('input, textarea').forEach((el) => {
                if (el.type === 'radio' || el.type === 'checkbox') {
                    el.checked = false;
                } else if (el.type === 'file') {
                    el.value = '';
                } else {
                    el.value = '';
                }
            });
            clone.querySelectorAll('select').forEach((el) => {
                el.value = '';
            });
            clone.querySelectorAll('.produsen-hidden-inputs, .distributor-hidden-inputs').forEach((wrap) => {
                wrap.innerHTML = '';
            });

            clone.querySelectorAll('input.penampakan-hidden, input.sealing-hidden, input.cetakan-hidden, input.logo-halal-hidden, input.dokumen-halal-hidden, input.coa-hidden').forEach((h) => {
                h.value = '';
            });

            // Ensure unit selects have all options available
            const unitDatangSelect = clone.querySelector('select[name="unit_datang[]"]');
            const unitSamplingSelect = clone.querySelector('select[name="unit_sampling[]"]');
            
            if (unitDatangSelect) {
                // Ensure all options from first row are present
                const firstUnitDatang = container.querySelector('select[name="unit_datang[]"]');
                if (firstUnitDatang && unitDatangSelect.options.length <= 1) {
                    // Clone options from first row
                    Array.from(firstUnitDatang.options).forEach((opt) => {
                        const newOpt = opt.cloneNode(true);
                        unitDatangSelect.appendChild(newOpt);
                    });
                }
            }
            
            if (unitSamplingSelect) {
                // Ensure all options from first row are present
                const firstUnitSampling = container.querySelector('select[name="unit_sampling[]"]');
                if (firstUnitSampling && unitSamplingSelect.options.length <= 1) {
                    // Clone options from first row
                    Array.from(firstUnitSampling.options).forEach((opt) => {
                        const newOpt = opt.cloneNode(true);
                        unitSamplingSelect.appendChild(newOpt);
                    });
                }
            }

            container.appendChild(clone);
            updateSectionLabels();

            ensureDetailCollapsible(clone);
            collapseOtherDetailsInRow(rowEl, clone);

            syncHeaderToDetails(rowEl);
            syncDokumenToDetails(rowEl);
            updateDetailIndices();
            return;
        }

        const rmBtn = e.target.closest('.remove-detail-btn');
        if (rmBtn) {
            const rowEl = rmBtn.closest('.unified-row');
            const detailEl = rmBtn.closest('.detail-item');
            if (!rowEl || !detailEl) return;
            const details = rowEl.querySelectorAll('.detail-item');
            if (details.length <= 1) return;
            detailEl.remove();
            syncHeaderToDetails(rowEl);
            syncDokumenToDetails(rowEl);
            updateDetailIndices();
        }
    });

    function updateSectionLabels() {
        const rows = Array.from(document.querySelectorAll('#unified-container .unified-row'));
        rows.forEach((rowEl, rowIdx) => {
            const produkLabel = rowEl.querySelector('.produk-collapse-label');
            if (produkLabel) {
                updateProdukLabel(rowEl, rowIdx);
            } else {
                const title = rowEl.querySelector('.bahan-title');
                if (title) {
                    title.textContent = `Bahan #${rowIdx + 1}`;
                }
            }

            const details = Array.from(rowEl.querySelectorAll('.detail-item'));
            details.forEach((detailEl, detailIdx) => {
                const detailLabel = detailEl.querySelector('.detail-collapse-label');
                if (detailLabel) {
                    updateDetailLabel(detailEl, detailIdx);
                } else {
                    const detailTitle = detailEl.querySelector('.detail-title');
                    if (detailTitle) {
                        detailTitle.textContent = `Detail #${detailIdx + 1}`;
                    }
                }
            });
        });

        // Update master dokumen radio names per row to avoid cross-row interference
        rows.forEach((rowEl, rowIdx) => {
            const rowNum = rowIdx + 1;
            rowEl.querySelectorAll('input[type="radio"][name^="logo_halal_master_"]').forEach((el) => { el.name = `logo_halal_master_${rowNum}`; });
            rowEl.querySelectorAll('input[type="radio"][name^="dokumen_halal_master_"]').forEach((el) => { el.name = `dokumen_halal_master_${rowNum}`; });
            rowEl.querySelectorAll('input[type="radio"][name^="coa_master_"]').forEach((el) => { el.name = `coa_master_${rowNum}`; });
        });
    }

    function syncDokumenToDetails(rowEl) {
        if (!rowEl) return;
        const logo = rowEl.querySelector('input.doc-master-logo')?.value ?? '';
        const dok = rowEl.querySelector('input.doc-master-dokumen')?.value ?? '';
        const coa = rowEl.querySelector('input.doc-master-coa')?.value ?? '';

        rowEl.querySelectorAll('.detail-item').forEach((detailEl) => {
            const l = detailEl.querySelector('input.logo-halal-hidden');
            const d = detailEl.querySelector('input.dokumen-halal-hidden');
            const c = detailEl.querySelector('input.coa-hidden');
            if (l) l.value = String(logo);
            if (d) d.value = String(dok);
            if (c) c.value = String(coa);
        });
    }

    document.addEventListener('change', function(e) {
        const target = e.target;
        if (target && target.matches('select.kategori-produk-select')) {
            const row = target.closest('.unified-row');
            if (row) {
                populateProdukOptionsForRow(row);
            }
        }

        if (target && target.matches('select.produk-select')) {
            const row = target.closest('.unified-row');
            if (row) {
                applyProdukMetaForRow(row);
                const idx = Array.from(document.querySelectorAll('#unified-container .unified-row')).indexOf(row);
                updateProdukLabel(row, idx >= 0 ? idx : 0);
            }
        }

        if (target && target.matches('input[type="radio"][name^="logo_halal_master_"]')) {
            const rowEl = target.closest('.unified-row');
            if (rowEl) {
                const master = rowEl.querySelector('input.doc-master-logo');
                if (master) master.value = String(target.value);
                syncDokumenToDetails(rowEl);
            }
        }
        if (target && target.matches('input[type="radio"][name^="dokumen_halal_master_"]')) {
            const rowEl = target.closest('.unified-row');
            if (rowEl) {
                const master = rowEl.querySelector('input.doc-master-dokumen');
                if (master) master.value = String(target.value);
                syncDokumenToDetails(rowEl);
            }
        }
        if (target && target.matches('input[type="radio"][name^="coa_master_"]')) {
            const rowEl = target.closest('.unified-row');
            if (rowEl) {
                const master = rowEl.querySelector('input.doc-master-coa');
                if (master) master.value = String(target.value);
                syncDokumenToDetails(rowEl);
            }
        }

        if (target && target.matches('input[type="radio"][name^="penampakan_master_"]')) {
            const detail = target.closest('.detail-item');
            if (detail) {
                const hidden = detail.querySelector('input.penampakan-hidden');
                if (hidden) hidden.value = String(target.value);
            }
        }
        if (target && target.matches('input[type="radio"][name^="sealing_master_"]')) {
            const detail = target.closest('.detail-item');
            if (detail) {
                const hidden = detail.querySelector('input.sealing-hidden');
                if (hidden) hidden.value = String(target.value);
            }
        }
        if (target && target.matches('input[type="radio"][name^="cetakan_master_"]')) {
            const detail = target.closest('.detail-item');
            if (detail) {
                const hidden = detail.querySelector('input.cetakan-hidden');
                if (hidden) hidden.value = String(target.value);
            }
        }
        if (target && target.matches('input[type="radio"][name^="logo_halal_master_"]')) {
            const detail = target.closest('.detail-item');
            if (detail) {
                const hidden = detail.querySelector('input.logo-halal-hidden');
                if (hidden) hidden.value = String(target.value);
            }
        }
        if (target && target.matches('input[type="radio"][name^="dokumen_halal_master_"]')) {
            const detail = target.closest('.detail-item');
            if (detail) {
                const hidden = detail.querySelector('input.dokumen-halal-hidden');
                if (hidden) hidden.value = String(target.value);
            }
        }
        if (target && target.matches('input[type="radio"][name^="coa_master_"]')) {
            const detail = target.closest('.detail-item');
            if (detail) {
                const hidden = detail.querySelector('input.coa-hidden');
                if (hidden) hidden.value = String(target.value);
            }
        }
    });

    document.addEventListener('input', function(e) {
        const target = e.target;
        if (target && target.matches('input[name="kode_produksi[]"]')) {
            const detailEl = target.closest('.detail-item');
            const rowEl = target.closest('.unified-row');
            if (!detailEl || !rowEl) return;
            const idx = Array.from(rowEl.querySelectorAll('.detail-item')).indexOf(detailEl);
            updateDetailLabel(detailEl, idx >= 0 ? idx : 0);
        }
    });

    // Bind directly for initial rows (Choices.js can swallow delegated change in some cases)
    document.querySelectorAll('#unified-container .unified-row').forEach((row, idx) => {
        const kategoriSelect = row.querySelector('select.kategori-produk-select');
        if (kategoriSelect) {

            const desiredKategori = (oldKategoriCodes && oldKategoriCodes[idx]) ? String(oldKategoriCodes[idx]) : '';
            if (desiredKategori) {
                const kategoriChoices = choicesInstances.get(kategoriSelect);
                if (kategoriChoices) {
                    kategoriChoices.setChoiceByValue(desiredKategori);
                } else {
                    kategoriSelect.value = desiredKategori;
                }
            }

            kategoriSelect.addEventListener('change', function() {
                populateProdukOptionsForRow(row);
            });
            kategoriSelect.addEventListener('addItem', function() {
                setTimeout(() => populateProdukOptionsForRow(row), 0);
            });
        }

        const produkSelect = row.querySelector('select.produk-select');
        if (produkSelect) {

            const desiredProduk = (oldProdukIds && oldProdukIds[idx]) ? String(oldProdukIds[idx]) : '';
            row.dataset.oldProdukId = desiredProduk;

            bindProdukSelectEvents(row);
        }

        if (kategoriSelect && kategoriSelect.value) {
            populateProdukOptionsForRow(row);
        } else if (produkSelect && produkSelect.value) {
            applyProdukMetaForRow(row);
        }

        syncDokumenToDetails(row);
    });

    updateDetailIndices();

    // Remove unified row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-unified-btn')) {
            const rowCount = document.querySelectorAll('#unified-container .unified-row').length;
            if (rowCount > 1) {
                e.target.closest('.unified-row').remove();
                updateRemoveButtons();
            } else {
                alert('Minimal harus ada satu baris data!');
            }
        }
    });
    
    // Update remove buttons visibility
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#unified-container .unified-row');
        rows.forEach((row) => {
            const removeBtn = row.querySelector('.remove-unified-btn');
            if (rows.length > 1) {
                removeBtn.style.display = 'inline-block';
            } else {
                removeBtn.style.display = 'none';
            }
        });

        updateSectionLabels();
    }
    
    // Initialize on page load
    updateRemoveButtons();
    updateSectionLabels();

    const MAX_SIZE = 1024 * 1024;

    function fileToDataURL(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    function loadImage(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = src;
        });
    }

    async function compressImage(file) {
        const dataUrl = await fileToDataURL(file);
        const img = await loadImage(dataUrl);

        const maxDimension = 1920;
        let { width, height } = img;
        if (width > height && width > maxDimension) {
            height = Math.round((height * maxDimension) / width);
            width = maxDimension;
        } else if (height >= width && height > maxDimension) {
            width = Math.round((width * maxDimension) / height);
            height = maxDimension;
        }

        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, width, height);

        let quality = 0.85;
        let blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));
        while (blob && blob.size > MAX_SIZE && quality > 0.4) {
            quality -= 0.1;
            blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));
        }

        const newName = (file.name || 'image')
            .replace(/\.[^/.]+$/, '') + '.jpg';
        return new File([blob], newName, { type: 'image/jpeg', lastModified: Date.now() });
    }

    async function handleImageInputChange(input) {
        const file = input.files && input.files[0] ? input.files[0] : null;
        if (!file) return;

        if (file.size <= MAX_SIZE) return;

        try {
            const compressedFile = await compressImage(file);
            const dt = new DataTransfer();
            dt.items.add(compressedFile);
            input.files = dt.files;
        } catch (e) {
            input.value = '';
            alert('Gagal mengkompres gambar. Silakan coba lagi.');
        }
    }

    document.addEventListener('change', function(e) {
        const input = e.target;
        if (input && input.classList && input.classList.contains('image-kemasan-input')) {
            handleImageInputChange(input);
        }
    });
});

/**
 * Hapus satu badge Produsen/Distributor dari tampilan form.
 * Data master TIDAK berubah — hanya berlaku untuk pengiriman form ini.
 *
 * @param {HTMLButtonElement} btn  — tombol × yang diklik
 * @param {string} type            — 'produsen' | 'distributor'
 */
function removeBadgeItem(btn, type) {
    const badge = btn.closest('.badge-removable');
    if (!badge) return;

    const rowEl = badge.closest('.unified-row');
    const badgesContainer = badge.closest('.produsen-badges, .distributor-badges');

    // Hapus badge dari tampilan
    badge.remove();

    // Jika tidak ada badge tersisa, tampilkan placeholder "-"
    if (badgesContainer) {
        const remaining = badgesContainer.querySelectorAll('.badge-removable');
        if (remaining.length === 0) {
            const placeholder = document.createElement('span');
            placeholder.className = 'text-muted small badge-empty';
            placeholder.textContent = '-';
            badgesContainer.appendChild(placeholder);
        }
    }

    // Rebuild hidden inputs di header & sync ke semua detail dalam row ini
    if (rowEl && window.syncHeaderToDetailsGlobal) {
        window.syncHeaderToDetailsGlobal(rowEl);
    } else if (rowEl) {
        // Fallback: trigger event agar applyProdukMetaForRow / syncHeaderToDetails terpanggil
        // Kita cukup rebuild hidden inputs dari badge yang tersisa
        const produsenBadgesEl = rowEl.querySelector('.produsen-badges');
        const distributorBadgesEl = rowEl.querySelector('.distributor-badges');

        const getValsFromBadges = (el) => {
            if (!el) return [];
            return Array.from(el.querySelectorAll('.badge-removable[data-value]'))
                       .map(b => String(b.dataset.value || '').trim())
                       .filter(v => v !== '');
        };

        const produsenVals = getValsFromBadges(produsenBadgesEl);
        const distributorVals = getValsFromBadges(distributorBadgesEl);

        // Update hidden inputs di header (bagian .form-section, bukan .detail-item)
        const headerProdusenHidden = rowEl.querySelector('.form-section .produsen-hidden-inputs');
        const headerDistributorHidden = rowEl.querySelector('.form-section .distributor-hidden-inputs');

        const rebuildHidden = (wrap, vals, cls) => {
            if (!wrap) return;
            wrap.innerHTML = '';
            vals.forEach(v => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.className = cls;
                inp.value = v;
                wrap.appendChild(inp);
            });
        };

        rebuildHidden(headerProdusenHidden, produsenVals, 'produsen-hidden-item');
        rebuildHidden(headerDistributorHidden, distributorVals, 'distributor-hidden-item');

        // Update hidden inputs di setiap detail-item dalam row ini
        rowEl.querySelectorAll('.detail-item').forEach((detailEl, idx) => {
            const prodWrap = detailEl.querySelector('.produsen-hidden-inputs');
            const distWrap = detailEl.querySelector('.distributor-hidden-inputs');
            rebuildHidden(prodWrap, produsenVals, '');
            rebuildHidden(distWrap, distributorVals, '');
        });
    }
}
</script>

<!-- Validasi Front-End Form -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-pemeriksaan-kedatangan-kemasan');
    
    if (!form) return;

    /**
     * Validasi Form - Cek field wajib:
     * 1. Tanggal (wajib)
     * 2. Status (minimal satu item harus "Hold" atau "Release")
     */
    function validateForm() {
        const errors = [];
        
        // 1. Tanggal (wajib)
        const tanggal = document.getElementById('tanggal');
        if (!tanggal || !tanggal.value || tanggal.value.trim() === '') {
            errors.push('Tanggal harus diisi');
            highlightField(tanggal);
        } else {
            removeHighlight(tanggal);
        }

        // 2. Status (minimal satu item harus "Hold" atau "Release")
        const statusSelects = document.querySelectorAll('select[name="status[]"]');
        let hasValidStatus = false;
        let firstStatusField = null;
        
        statusSelects.forEach((select, index) => {
            if (!firstStatusField) firstStatusField = select;
            
            const value = select.value ? select.value.trim() : '';
            if (value === 'Hold' || value === 'Release') {
                hasValidStatus = true;
                removeHighlight(select);
            } else {
                highlightField(select);
            }
        });
        
        if (!hasValidStatus) {
            errors.push('Minimal satu item harus memilih Status "Hold" atau "Release"');
            if (firstStatusField) {
                highlightField(firstStatusField);
            }
        }

        return errors;
    }

    /**
     * Highlight field yang error
     */
    function highlightField(field) {
        if (field) {
            field.classList.add('is-invalid');
            field.style.borderColor = '#dc3545';
            field.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
        }
    }

    /**
     * Remove highlight dari field
     */
    function removeHighlight(field) {
        if (field) {
            field.classList.remove('is-invalid');
            field.style.borderColor = '';
            field.style.boxShadow = '';
        }
    }

    /**
     * Handle form submit
     */
    form.addEventListener('submit', function(e) {
        const errors = validateForm();
        
        if (errors.length > 0) {
            e.preventDefault();
            
            // Tampilkan error messages
            let errorMessage = '❌ Data Tidak Lengkap:\n\n';
            errors.forEach((error, index) => {
                errorMessage += `${index + 1}. ${error}\n`;
            });
            
            // Gunakan SweetAlert jika tersedia, jika tidak gunakan alert biasa
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: `<div style="text-align: left; line-height: 1.8;">
                        <strong>Berikut field yang belum diisi:</strong><br><br>
                        ${errors.map((err, idx) => `<span style="display: block; margin-bottom: 8px;">${idx + 1}. ${err}</span>`).join('')}
                    </div>`,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            } else {
                alert(errorMessage);
            }
            
            // Scroll ke field pertama yang error
            const allFields = document.querySelectorAll('.is-invalid');
            if (allFields.length > 0) {
                allFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                allFields[0].focus();
            }
        }
        // Jika validasi berhasil, form akan submit normalmente
    });
});
</script>
@endsection