@extends('layouts.app')

@section('title', 'Tambah Pemeriksaan Kedatangan Bahan Baku Penunjang')

@section('container')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Tambah Pemeriksaan Kedatangan Bahan Baku Penunjang</h3>
                        <p class="text-subtitle text-muted">Form untuk menambah data pemeriksaan kedatangan bahan baku penunjang</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-bahan-baku.index') }}">Pemeriksaan Kedatangan Bahan Baku Penunjang</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="page-content">
            <section class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Form Pemeriksaan Kedatangan Bahan Baku Penunjang</h4>
                        </div>
                        <div class="card-body">
                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h4 class="alert-heading">Error Validasi!</h4>
                                    <hr>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form id="form-pemeriksaan-bahan-baku-penunjang" data-autosave="true" action="{{ route('pemeriksaan-bahan-baku.store') }}" method="POST" enctype="multipart/form-data">
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
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="jenis_mobil">Jenis Mobil</label>
                                                <input type="text" id="jenis_mobil" class="form-control @error('jenis_mobil') is-invalid @enderror"
                                                    name="jenis_mobil" value="{{ old('jenis_mobil') }}" placeholder="Jenis Mobil">
                                                @error('jenis_mobil')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_mobil">No. Mobil</label>
                                                <input type="text" id="no_mobil" class="form-control @error('no_mobil') is-invalid @enderror"
                                                    name="no_mobil" value="{{ old('no_mobil') }}" placeholder="No. Mobil">
                                                @error('no_mobil')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nama_supir">Nama Supir</label>
                                                <input type="text" id="nama_supir" class="form-control @error('nama_supir') is-invalid @enderror"
                                                    name="nama_supir" value="{{ old('nama_supir') }}" placeholder="Nama Supir">
                                                @error('nama_supir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_po">No. PO</label>
                                                <input type="text" id="no_po" class="form-control @error('no_po') is-invalid @enderror"
                                                    name="no_po" value="{{ old('no_po') }}" placeholder="No. PO">
                                                @error('no_po')
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
        
                                            <!-- 4. Bebas dari Produk Non Halal -->
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
                                <!-- Dynamic Rows Section -->
                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Detail Produk (Baris Dinamis)</h5>
                                    <div id="unified-container">
                                        <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa; border-left: 4px solid #435ebe;" data-row-index="0">
                                            <h5 class="text-primary mb-3">Produk 1</h5>
                                            
                                            <!-- Informasi Produk -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Kategori</label>
                                                        <select class="choices form-control kategori-produk-select" name="kategori_code[]">
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
                                                        <select class="form-control produk-select @error('id_produk.0') is-invalid @enderror" name="id_produk[]">
                                                            <option value="">Pilih Produk</option>
                                                        </select>
                                                        @error('id_bahan.0')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body p-3">
                                                            <div class="fw-semibold">Distributor</div>
                                                            <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                            <div class="distributor-badges d-flex flex-wrap gap-1">
                                                                @php
                                                                    $oldDistributor0 = old('distributor.0', []);
                                                                    $oldDistributor0 = is_array($oldDistributor0) ? $oldDistributor0 : [$oldDistributor0];
                                                                    $oldDistributor0 = array_values(array_filter($oldDistributor0, fn ($v) => $v !== null && $v !== ''));
                                                                @endphp
                                                                @forelse ($oldDistributor0 as $d)
                                                                    <span class="badge bg-light-info text-info badge-removable" data-value="{{ $d }}">{{ $d }} <button type="button" class="badge-remove-btn" onclick="removeBadgeItem(this, 'distributor')">&times;</button></span>
                                                                @empty
                                                                    <span class="text-muted small badge-empty">-</span>
                                                                @endforelse
                                                            </div>
                                                            <div class="distributor-hidden-inputs">
                                                                @foreach ($oldDistributor0 as $d)
                                                                    <input type="hidden" name="distributor[0][]" value="{{ $d }}">
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body p-3">
                                                            <div class="fw-semibold">Produsen</div>
                                                            <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                            <div class="produsen-badges d-flex flex-wrap gap-1">
                                                                @php
                                                                    $oldProdusen0 = old('produsen.0', []);
                                                                    $oldProdusen0 = is_array($oldProdusen0) ? $oldProdusen0 : [$oldProdusen0];
                                                                    $oldProdusen0 = array_values(array_filter($oldProdusen0, fn ($v) => $v !== null && $v !== ''));
                                                                @endphp
                                                                @forelse ($oldProdusen0 as $p)
                                                                    <span class="badge bg-light-primary text-primary badge-removable" data-value="{{ $p }}">{{ $p }} <button type="button" class="badge-remove-btn" onclick="removeBadgeItem(this, 'produsen')">&times;</button></span>
                                                                @empty
                                                                    <span class="text-muted small badge-empty">-</span>
                                                                @endforelse
                                                            </div>
                                                            <div class="produsen-hidden-inputs">
                                                                @foreach ($oldProdusen0 as $p)
                                                                    <input type="hidden" name="produsen[0][]" value="{{ $p }}">
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Negara Produsen</label>
                                                        <select class="choices form-control" name="negara_produsen[]">
                                                            <option value="">Pilih Negara</option>
                                                            @foreach ($countries as $code => $name)
                                                                <option value="{{ $name }}">{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- SUHU PRODUK -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Suhu Produk</label>
                                                        <select class="form-control suhu-produk-type" name="suhu_produk_type[]" id="suhu_produk_type_1">
                                                            <option value="">Pilih Jenis Suhu Produk</option>
                                                            <option value="Fresh">Fresh</option>
                                                            <option value="Frozen">Frozen</option>
                                                            <option value="Tidak Ada">Tidak Ada</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group suhu-produk-input" id="suhu_produk_input_1" style="display: none;">
                                                        <label class="form-label">Nilai Suhu Produk (°C)</label>
                                                        <input type="text" class="form-control" name="suhu_produk[]" id="suhu_produk_val_1" placeholder="Contoh: -18°C atau 4°C">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- SUHU MOBIL -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Suhu Mobil</label>
                                                        <select class="form-control suhu-mobil-type" name="suhu_mobil_type[]" id="suhu_mobil_type_1">
                                                            <option value="">Pilih Jenis Suhu Mobil</option>
                                                            <option value="Fresh">Fresh</option>
                                                            <option value="Frozen">Frozen</option>
                                                            <option value="Tidak Ada">Tidak Ada</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group suhu-mobil-input" id="suhu_mobil_input_1" style="display: none;">
                                                        <label class="form-label">Nilai Suhu Mobil (°C)</label>
                                                        <input type="text" class="form-control" name="suhu_mobil[]" id="suhu_mobil_val_1" placeholder="Contoh: -18°C atau 4°C">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- KONDISI PRODUK -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Kondisi Produk</label>
                                                        <select class="form-control kondisi-produk" name="kondisi_produk[]" id="kondisi_produk_1">
                                                            <option value="">Pilih Kondisi Produk</option>
                                                            <option value="Fresh">Fresh</option>
                                                            <option value="Frozen">Frozen</option>
                                                            <option value="Dry">Dry</option>
                                                            <option value="Minyak">Minyak</option>
                                                            <option value="Liquid">Liquid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group kondisi-produk-suhu" id="kondisi_produk_suhu_1" style="display: none;">
                                                        <label class="form-label">Suhu Kondisi Produk (°C)</label>
                                                        <input type="text" class="form-control" name="kondisi_produk_suhu[]" id="kondisi_produk_suhu_val_1" placeholder="Suhu Produk">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="detail-items">
                                                <div class="detail-item border rounded p-3 mb-3" style="background: #fff;" data-detail-suffix="1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <strong class="detail-title">Detail 1</strong>
                                                        <button type="button" class="btn btn-danger btn-sm remove-detail-btn" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Kode Produksi</label>
                                                                <input type="text" class="form-control" name="kode_produksi[]" placeholder="Kode Produksi">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Expire Date</label>
                                                                <input type="date" class="form-control" name="expire_date[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Jumlah Datang</label>
                                                                <div class="input-group" style="max-width: 100%;">
                                                                    <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah" min="0" step="any">
                                                                    <select class="form-select" name="unit_datang[]" style="max-width: 120px;">
                                                                        <option value="">Pilih Parameter</option>
                                                                        @foreach(\App\Models\PemeriksaanKedatanganBahanBakuPenunjang::unitParameters() as $key => $label)
                                                                            <option value="{{ $key }}">{{ $label }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Jumlah Sampling</label>
                                                                <div class="input-group" style="max-width: 100%;">
                                                                    <input type="text" class="form-control" name="jumlah_sampling[]" placeholder="Jumlah" min="0" step="any">
                                                                    <select class="form-select" name="unit_sampling[]" style="max-width: 120px;">
                                                                        <option value="">Pilih Parameter</option>
                                                                        @foreach(\App\Models\PemeriksaanKedatanganBahanBakuPenunjang::unitParameters() as $key => $label)
                                                                            <option value="{{ $key }}">{{ $label }}</option>
                                                                        @endforeach
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
                                                    
                                                    <!-- Kondisi Fisik -->
                                                    <div class="form-section mb-3">
                                                        <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label"><strong>Kemasan</strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_1" id="kemasan_ya_1" value="1">
                                                                        <label class="form-check-label" for="kemasan_ya_1">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_1" id="kemasan_tidak_1" value="0">
                                                                        <label class="form-check-label" for="kemasan_tidak_1">Tidak ✗</label>
                                                                    </div>
                                                                    <input type="hidden" name="kondisi_fisik_kemasan[]" class="radio-value-kemasan-1">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label"><strong>Warna</strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_warna_1" id="warna_ya_1" value="1">
                                                                        <label class="form-check-label" for="warna_ya_1">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_warna_1" id="warna_tidak_1" value="0">
                                                                        <label class="form-check-label" for="warna_tidak_1">Tidak ✗</label>
                                                                    </div>
                                                                    <input type="hidden" name="kondisi_fisik_warna[]" class="radio-value-warna-1">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label"><strong>Benda Asing</strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_1" id="benda_ya_1" value="1">
                                                                        <label class="form-check-label" for="benda_ya_1">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_1" id="benda_tidak_1" value="0">
                                                                        <label class="form-check-label" for="benda_tidak_1">Tidak ✗</label>
                                                                    </div>
                                                                    <input type="hidden" name="kondisi_fisik_benda_asing[]" class="radio-value-benda-1">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label"><strong>Aroma</strong></label>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_1" id="aroma_ya_1" value="1">
                                                                        <label class="form-check-label" for="aroma_ya_1">Ya ✓</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_1" id="aroma_tidak_1" value="0">
                                                                        <label class="form-check-label" for="aroma_tidak_1">Tidak ✗</label>
                                                                    </div>
                                                                    <input type="hidden" name="kondisi_fisik_aroma[]" class="radio-value-aroma-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-section mb-3 coa-upload-section">
                                                        <h6 class="text-primary mb-2">Upload COA</h6>
                                                        <div class="row mb-2">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label d-block">Tipe File</label>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input coa-type-pdf" type="radio" name="coa_type[]" value="pdf" checked>
                                                                        <label class="form-check-label">PDF</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input coa-type-img" type="radio" name="coa_type[]" value="gambar">
                                                                        <label class="form-check-label">Gambar</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row coa-pdf-input">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">File COA (PDF)</label>
                                                                    <input type="file" name="file_coa[]" class="form-control" accept="application/pdf">
                                                                    <small class="form-text text-muted">Format: PDF. Maksimal 3MB.</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row coa-img-input" style="display:none;">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Foto COA</label>
                                                                    <input type="file" name="file_coa_img[]" class="form-control" accept="image/*" capture="environment">
                                                                    <small class="form-text text-muted">Format: JPG, PNG, WEBP, GIF. Gambar &gt; 3MB akan dikompres otomatis.</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-section mb-3">
                                                        <h6 class="text-primary mb-2">Upload Gambar</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Foto Bahan Baku</label>
                                                                    <input type="file" name="image_bahan_baku[]" class="form-control image-bahan-baku-input" accept="image/*" capture="camera">
                                                                    @error('image_bahan_baku.0')
                                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Hasil Pemeriksaan -->
                                                    <div class="form-section mb-3">
                                                        <h6 class="text-primary mb-2">Hasil Pemeriksaan</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Hasil Uji FFA</label>
                                                                    <input type="text" class="form-control" name="hasil_uji_ffa[]" placeholder="Hasil Uji FFA">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="status">Status <span class="text-danger">*</span></label>
                                                                    <select id="status" class="form-control @error('status_baris') is-invalid @enderror" name="status_baris[]" required>
                                                                        <option value="">Pilih Status</option>
                                                                        <option value="Hold">Hold</option>
                                                                        <option value="Release">Release</option>
                                                                        <option value="Retur">Retur</option>
                                                                    </select>
                                                                    @error('status_baris')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Keterangan Hasil</label>
                                                                    <textarea class="form-control" name="keterangan_hasil[]" rows="2" placeholder="Keterangan hasil pemeriksaan"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Dokumen -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Dokumen</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Logo Halal</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="logo_halal_1" id="logo_ya_1" value="1">
                                                                <label class="form-check-label" for="logo_ya_1">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="logo_halal_1" id="logo_tidak_1" value="0">
                                                                <label class="form-check-label" for="logo_tidak_1">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="logo_halal[]" class="radio-value-logo-1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal_1" id="dokumen_ya_1" value="1">
                                                                <label class="form-check-label" for="dokumen_ya_1">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal_1" id="dokumen_tidak_1" value="0">
                                                                <label class="form-check-label" for="dokumen_tidak_1">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="dokumen_halal[]" class="radio-value-dokumen-1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>COA</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="coa_1" id="coa_ya_1" value="1">
                                                                <label class="form-check-label" for="coa_ya_1">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="coa_1" id="coa_tidak_1" value="0">
                                                                <label class="form-check-label" for="coa_tidak_1">Tidak ✗</label>
                                                            </div>
                                                            <input type="hidden" name="coa[]" class="radio-value-coa-1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-primary btn-sm add-detail-btn"><i class="bi bi-plus"></i> Tambah Detail</button>
                                                </div>
                                            </div>
                                            
                                            <!-- Buttons -->
                                            <div class="row mt-3 pt-3 border-top">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Produk</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3 pt-3 border-top">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-success btn-sm add-unified-btn"><i class="bi bi-plus"></i> Tambah Produk</button>
                                        </div>
                                    </div>
                                </div>

                                
                                
                                <div class="col-md-12 d-flex justify-content-end mt-3">
                                    <a href="{{ route('pemeriksaan-bahan-baku.index') }}" class="btn btn-light-secondary me-1 mb-1 btn-kembali-confirm">Kembali</a>
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Simpan Data</button>
                                    <!-- <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button> -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </header>
</div>

<style>
    .collapse-toggle-btn {
        width: auto;
        display: inline-flex;
        align-items: center;
        text-align: left;
    }
    .collapse-chevron { transition: transform .2s ease; }
    .collapse-toggle-btn[aria-expanded="true"] .collapse-chevron { transform: rotate(180deg); }

    /* Style untuk badge produsen & distributor removable */
    .badge-removable {
        display: inline-flex !important;
        align-items: center;
        gap: 6px;
        padding-right: 6px !important;
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

@push('scripts')
<script>
const produkByKategori = @json($produkByKategori ?? []);
const produkMeta = @json($produkMeta ?? []);
const oldKategoriCodes = @json(old('kategori_code', []));
const oldProdukIds = @json(old('id_bahan', []));

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
        rowEl.dataset.produkCollapseId = uniqueDomId('produk_bbp');
    }
    const collapseId = rowEl.dataset.produkCollapseId;

    const headerTitle = rowEl.querySelector(':scope > h6');
    if (!headerTitle) return;

    if (!headerTitle.querySelector('button[data-bs-toggle="collapse"]')) {
        const existingText = (headerTitle.textContent || '').trim();
        headerTitle.textContent = '';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-primary btn-sm d-inline-flex align-items-center gap-2 collapse-toggle-btn';
        btn.setAttribute('data-bs-toggle', 'collapse');
        btn.setAttribute('data-bs-target', `#${collapseId}`);
        btn.setAttribute('aria-expanded', 'true');
        btn.setAttribute('aria-controls', collapseId);

        const span = document.createElement('span');
        span.className = 'mb-0 produk-collapse-label text-white';
        span.textContent = existingText || `Produk ${rowIdx + 1}`;

        const icon = document.createElement('i');
        icon.className = 'bi bi-chevron-down collapse-chevron text-white';

        btn.appendChild(span);
        btn.appendChild(icon);
        headerTitle.appendChild(btn);
    }

    let body = rowEl.querySelector(':scope > .produk-collapse.collapse');
    if (body) {
        body.id = collapseId;
    } else {
        body = document.createElement('div');
        body.className = 'produk-collapse collapse show';
        body.id = collapseId;

        const nodesToMove = [];
        let node = headerTitle.nextSibling;
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

    const hasDuplicateId = (id) => {
        if (!id) return true;
        const el = document.getElementById(id);
        if (!el) return false;
        return !detailEl.contains(el);
    };

    if (hasDuplicateId(collapseId)) {
        collapseId = uniqueDomId('detail_bbp');
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
    if (existingBtn) {
        existingBtn.setAttribute('data-bs-target', `#${collapseId}`);
        existingBtn.setAttribute('aria-controls', collapseId);
        if (!existingBtn.querySelector('.collapse-chevron')) {
            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down collapse-chevron text-white';
            existingBtn.appendChild(icon);
        }
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
};

const updateProdukLabel = (rowEl, rowIdx) => {
    if (!rowEl) return;
    const labelEl = rowEl.querySelector('.produk-collapse-label');
    if (!labelEl) return;
    const produkSelect = rowEl.querySelector('select.produk-select');
    const selectedText = produkSelect && produkSelect.selectedOptions && produkSelect.selectedOptions[0]
        ? String(produkSelect.selectedOptions[0].textContent || '').trim()
        : '';
    labelEl.textContent = selectedText || `Produk ${rowIdx + 1}`;
};

const updateDetailLabel = (detailEl, idxWithinRow) => {
    if (!detailEl) return;
    const labelEl = detailEl.querySelector('.detail-collapse-label');
    if (!labelEl) return;
    const kodeInp = detailEl.querySelector('input[name="kode_produksi[]"]');
    const kodeVal = kodeInp ? String(kodeInp.value || '').trim() : '';
    labelEl.textContent = kodeVal || `Detail #${idxWithinRow + 1}`;
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
        if (detailEl === activeDetailEl) body.classList.add('show');
        else body.classList.remove('show');
    });
};

function initBbpCollapses() {
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
}

// Global variable to store select options
let selectOptionsCache = {
    bahan: [],
    produsen: [],
    negara: [],
    distributor: []
};

const choicesInstances = new WeakMap();

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
        el.addEventListener('click', function(e) {
            const ok = confirm('Data belum disimpan. Yakin ingin kembali ke halaman index?');
            if (!ok) e.preventDefault();
        });
    });

    // Pengecekan ukuran file (Frontend) agar sinkron dengan batasan max:3072 di Controller
    document.addEventListener('change', function(e) {
        if (e.target.type === 'file') {
            const file = e.target.files[0];
            if (file) {
                const maxSize = 3 * 1024 * 1024; // 3 MB Maksimal
                if (file.size > maxSize) {
                    alert('Ukuran file ' + file.name + ' terlalu besar (' + (file.size / 1024 / 1024).toFixed(2) + ' MB). Maksimal ukuran file yang diizinkan adalah 3 MB.');
                    e.target.value = ''; // Reset input agar file dibatalkan
                }
            }
        }
    });
    
    // Conditional logic (event delegation) for Suhu Produk / Suhu Mobil / Kondisi Produk
    document.addEventListener('change', function(e) {
        const rowEl = e.target.closest('.unified-row');
        if (!rowEl) return;

        const suhuProdukType = e.target.closest('select.suhu-produk-type');
        if (suhuProdukType) {
            const inputWrap = rowEl.querySelector('.suhu-produk-input');
            const inputVal = rowEl.querySelector('input[name="suhu_produk[]"]');
            if (inputWrap) {
                if (suhuProdukType.value === 'Fresh' || suhuProdukType.value === 'Frozen') {
                    inputWrap.style.display = 'block';
                } else {
                    inputWrap.style.display = 'none';
                    if (inputVal) inputVal.value = '';
                }
            }
            return;
        }

        const suhuMobilType = e.target.closest('select.suhu-mobil-type');
        if (suhuMobilType) {
            const inputWrap = rowEl.querySelector('.suhu-mobil-input');
            const inputVal = rowEl.querySelector('input[name="suhu_mobil[]"]');
            if (inputWrap) {
                if (suhuMobilType.value === 'Fresh' || suhuMobilType.value === 'Frozen') {
                    inputWrap.style.display = 'block';
                } else {
                    inputWrap.style.display = 'none';
                    if (inputVal) inputVal.value = '';
                }
            }
            return;
        }

        const kondisiProduk = e.target.closest('select.kondisi-produk');
        if (kondisiProduk) {
            const inputWrap = rowEl.querySelector('.kondisi-produk-suhu');
            const inputVal = rowEl.querySelector('input[name="kondisi_produk_suhu[]"]');
            if (inputWrap) {
                if (kondisiProduk.value === 'Fresh' || kondisiProduk.value === 'Frozen' || kondisiProduk.value === 'Dry' || kondisiProduk.value === 'Minyak' || kondisiProduk.value === 'Liquid') {
                    inputWrap.style.display = 'block';
                } else {
                    inputWrap.style.display = 'none';
                    if (inputVal) inputVal.value = '';
                }
            }
        }
    });

    // Trigger once on load for existing rows
    document.querySelectorAll('#unified-container .unified-row').forEach((rowEl) => {
        const sp = rowEl.querySelector('select.suhu-produk-type');
        if (sp) sp.dispatchEvent(new Event('change', { bubbles: true }));
        const sm = rowEl.querySelector('select.suhu-mobil-type');
        if (sm) sm.dispatchEvent(new Event('change', { bubbles: true }));
        const kp = rowEl.querySelector('select.kondisi-produk');
        if (kp) kp.dispatchEvent(new Event('change', { bubbles: true }));
    });

    // Initialize Choices.js for all select elements
    try {
        initializeAllChoices();
        
        // Cache select options AFTER Choices.js initialization
        setTimeout(function() {
            cacheSelectOptions();
        }, 100);
    } catch(err) {
        console.error('Error initializing Choices.js:', err);
    }
    
    // Update delete button status
    try {
        updateRemoveButtons();
    } catch(err) {
        console.error('Error updating remove buttons:', err);
    }
    
    // Setup event listeners for dynamic form
    try {
        setupDynamicFormListeners();
    } catch(err) {
        console.error('Error setting up dynamic form listeners:', err);
    }

    try {
        initializeProdukFlow();
    } catch(err) {
        console.error('Error initializing produk flow:', err);
    }

    try {
        initializeDetailFlow();
    } catch(err) {
        console.error('Error initializing detail flow:', err);
    }

    try {
        initBbpCollapses();
    } catch(err) {
        console.error('Error initializing collapse:', err);
    }

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

    // Flatten header-per-produk fields into per-detail indices so multiple detail items are persisted
    try {
        const formEl = document.querySelector('form[action*="pemeriksaan-bahan-baku"]');
        if (formEl && !formEl.__bbpFlattenBound) {
            formEl.__bbpFlattenBound = true;
            formEl.addEventListener('submit', function() {
                // remove previous generated container
                const existing = formEl.querySelector('#__bbp_flatten_container');
                if (existing) existing.remove();

                const gen = document.createElement('div');
                gen.id = '__bbp_flatten_container';
                gen.style.display = 'none';
                formEl.appendChild(gen);

                let globalIdx = 0;

                const rows = Array.from(document.querySelectorAll('#unified-container .unified-row'));
                rows.forEach((rowEl) => {
                    const headerIdBahan = rowEl.querySelector('select.produk-select, select[name="id_bahan[]"]');
                    const headerNegara = rowEl.querySelector('select[name="negara_produsen[]"]');
                    const headerSuhuProdukType = rowEl.querySelector('select.suhu-produk-type');
                    const headerSuhuProduk = rowEl.querySelector('input[name="suhu_produk[]"]');
                    const headerSuhuMobilType = rowEl.querySelector('select.suhu-mobil-type');
                    const headerSuhuMobil = rowEl.querySelector('input[name="suhu_mobil[]"]');
                    const headerKondisiProduk = rowEl.querySelector('select.kondisi-produk');
                    const headerKondisiProdukSuhu = rowEl.querySelector('input[name="kondisi_produk_suhu[]"]');

                    const produsenVals = Array.from(rowEl.querySelectorAll('.produsen-hidden-inputs input[type="hidden"]')).map(i => i.value);
                    const distributorVals = Array.from(rowEl.querySelectorAll('.distributor-hidden-inputs input[type="hidden"]')).map(i => i.value);

                    const headerLogoHalal = rowEl.querySelector('input[name="logo_halal[]"]');
                    const headerDokumenHalal = rowEl.querySelector('input[name="dokumen_halal[]"]');
                    const headerCoa = rowEl.querySelector('input[name="coa[]"]');

                    const header = {
                        id_bahan: headerIdBahan ? String(headerIdBahan.value || '') : '',
                        negara_produsen: headerNegara ? String(headerNegara.value || '') : '',
                        suhu_produk_type: headerSuhuProdukType ? String(headerSuhuProdukType.value || '') : '',
                        suhu_produk: headerSuhuProduk ? String(headerSuhuProduk.value || '') : '',
                        suhu_mobil_type: headerSuhuMobilType ? String(headerSuhuMobilType.value || '') : '',
                        suhu_mobil: headerSuhuMobil ? String(headerSuhuMobil.value || '') : '',
                        kondisi_produk: headerKondisiProduk ? String(headerKondisiProduk.value || '') : '',
                        kondisi_produk_suhu: headerKondisiProdukSuhu ? String(headerKondisiProdukSuhu.value || '') : '',
                        logo_halal: headerLogoHalal ? String(headerLogoHalal.value || '') : '',
                        dokumen_halal: headerDokumenHalal ? String(headerDokumenHalal.value || '') : '',
                        coa: headerCoa ? String(headerCoa.value || '') : '',
                    };

                    const details = Array.from(rowEl.querySelectorAll('.detail-item'));
                    if (!details.length) {
                        // still create 1 index so backend arrays align
                        details.push(null);
                    }

                    details.forEach(() => {
                        const appendHidden = (name, value) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = name;
                            input.value = value;
                            gen.appendChild(input);
                        };

                        appendHidden('id_bahan[]', header.id_bahan);
                        appendHidden('negara_produsen[]', header.negara_produsen);
                        appendHidden('suhu_produk_type[]', header.suhu_produk_type);
                        appendHidden('suhu_produk[]', header.suhu_produk);
                        appendHidden('suhu_mobil_type[]', header.suhu_mobil_type);
                        appendHidden('suhu_mobil[]', header.suhu_mobil);
                        appendHidden('kondisi_produk[]', header.kondisi_produk);
                        appendHidden('kondisi_produk_suhu[]', header.kondisi_produk_suhu);
                        appendHidden('logo_halal[]', header.logo_halal);
                        appendHidden('dokumen_halal[]', header.dokumen_halal);
                        appendHidden('coa[]', header.coa);

                        (produsenVals || []).forEach((v) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `produsen[${globalIdx}][]`;
                            input.value = String(v);
                            gen.appendChild(input);
                        });

                        (distributorVals || []).forEach((v) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `distributor[${globalIdx}][]`;
                            input.value = String(v);
                            gen.appendChild(input);
                        });

                        globalIdx++;
                    });

                    // Disable original header inputs so they won't submit duplicates/misaligned arrays
                    [
                        headerIdBahan,
                        headerNegara,
                        headerSuhuProdukType,
                        headerSuhuProduk,
                        headerSuhuMobilType,
                        headerSuhuMobil,
                        headerKondisiProduk,
                        headerKondisiProdukSuhu,
                        headerLogoHalal,
                        headerDokumenHalal,
                        headerCoa,
                    ].forEach((el) => {
                        if (el) el.disabled = true;
                    });

                    rowEl.querySelectorAll('.produsen-hidden-inputs input[type="hidden"], .distributor-hidden-inputs input[type="hidden"]').forEach((el) => {
                        el.disabled = true;
                    });
                });
            });
        }
    } catch (err) {
        console.error('Error flattening BBP form on submit:', err);
    }
});

function initializeDetailFlow() {
    document.querySelectorAll('#unified-container .unified-row').forEach((rowEl) => {
        reindexDetails(rowEl);
    });

    document.addEventListener('click', function(e) {
        const addBtn = e.target.closest('.add-detail-btn');
        if (addBtn) {
            const rowEl = addBtn.closest('.unified-row');
            if (!rowEl) return;
            addDetail(rowEl);
            return;
        }

        const removeBtn = e.target.closest('.remove-detail-btn');
        if (removeBtn) {
            const rowEl = removeBtn.closest('.unified-row');
            const detailEl = removeBtn.closest('.detail-item');
            if (!rowEl || !detailEl) return;
            detailEl.remove();
            reindexDetails(rowEl);
        }
    });

    document.addEventListener('change', function(e) {
        const radio = e.target.closest('input[type="radio"]');
        if (!radio) return;

        const name = radio.getAttribute('name') || '';
        const base = name.replace(/_\d+$/, '');

        const detailMap = {
            kondisi_fisik_kemasan: 'kondisi_fisik_kemasan[]',
            kondisi_fisik_warna: 'kondisi_fisik_warna[]',
            kondisi_fisik_benda_asing: 'kondisi_fisik_benda_asing[]',
            kondisi_fisik_aroma: 'kondisi_fisik_aroma[]',
        };

        const rowMap = {
            logo_halal: 'logo_halal[]',
            dokumen_halal: 'dokumen_halal[]',
            coa: 'coa[]',
        };

        if (detailMap[base]) {
            const detailEl = radio.closest('.detail-item');
            if (detailEl) {
                const hiddenName = detailMap[base];
                const hidden = detailEl.querySelector(`input[type="hidden"][name="${hiddenName}"]`);
                if (hidden) hidden.value = radio.value;
            }
        } else if (rowMap[base]) {
            const rowEl = radio.closest('.unified-row');
            if (rowEl) {
                const hiddenName = rowMap[base];
                const hidden = rowEl.querySelector(`input[type="hidden"][name="${hiddenName}"]`);
                if (hidden) hidden.value = radio.value;
            }
        }
    });
}

function addDetail(rowEl) {
    const container = rowEl.querySelector('.detail-items');
    const template = container ? container.querySelector('.detail-item') : null;
    if (!container || !template) return;

    const clone = template.cloneNode(true);
    resetDetailInputs(clone);
    container.appendChild(clone);
    reindexDetails(rowEl);

    try {
        ensureDetailCollapsible(clone);
        collapseOtherDetailsInRow(rowEl, clone);
    } catch (e) {
    }
}

function resetDetailInputs(detailEl) {
    detailEl.querySelectorAll('input[type="text"], input[type="date"], textarea').forEach((el) => {
        el.value = '';
    });
    detailEl.querySelectorAll('input[type="radio"]').forEach((el) => {
        el.checked = false;
    });
    detailEl.querySelectorAll('input[type="hidden"]').forEach((el) => {
        if ((el.getAttribute('name') || '').endsWith('[]')) el.value = '';
    });
    detailEl.querySelectorAll('input[type="file"]').forEach((el) => {
        el.value = '';
    });
}

function reindexDetails(rowEl) {
    const details = Array.from(rowEl.querySelectorAll('.detail-item'));
    details.forEach((detailEl, idx) => {
        const suffix = String(idx + 1);
        detailEl.dataset.detailSuffix = suffix;

        const label = detailEl.querySelector('.detail-collapse-label');
        if (label) {
            updateDetailLabel(detailEl, idx);
        } else {
            const title = detailEl.querySelector('.detail-title');
            if (title) {
                title.textContent = `Detail ${suffix}`;
            }
        }

        detailEl.querySelectorAll('input[type="radio"]').forEach((radio) => {
            if (!radio.name) return;
            const baseName = radio.name.replace(/_\d+$/, '');
            radio.name = `${baseName}_${suffix}`;

            if (radio.id) {
                const baseId = radio.id.replace(/_\d+$/, '');
                radio.id = `${baseId}_${suffix}`;
            }
        });

        detailEl.querySelectorAll('label[for]').forEach((label) => {
            const currentFor = label.getAttribute('for');
            if (!currentFor) return;
            const baseFor = currentFor.replace(/_\d+$/, '');
            label.setAttribute('for', `${baseFor}_${suffix}`);
        });

        detailEl.querySelectorAll('.radio-value-kemasan-1, .radio-value-warna-1, .radio-value-benda-1, .radio-value-aroma-1').forEach((el) => {
            el.className = el.className.replace(/-\d+$/, `-${suffix}`);
        });

        const removeBtn = detailEl.querySelector('.remove-detail-btn');
        if (removeBtn) {
            removeBtn.style.display = (details.length > 1) ? 'inline-block' : 'none';
        }
    });
}

function initializeProdukFlow() {
    document.querySelectorAll('#unified-container .unified-row').forEach((row, idx) => {
        if (!row.dataset.rowIndex) row.dataset.rowIndex = String(idx);
    });

    document.querySelectorAll('#unified-container .unified-row').forEach((row) => {
        setupProdukRowListeners(row);
    });

    document.querySelectorAll('#unified-container .unified-row').forEach((row, idx) => {
        const kategoriSelect = row.querySelector('select.kategori-produk-select');
        const produkSelect = row.querySelector('select.produk-select');

        const desiredKategori = (oldKategoriCodes && oldKategoriCodes[idx]) ? String(oldKategoriCodes[idx]) : '';
        if (kategoriSelect && desiredKategori) {
            kategoriSelect.value = desiredKategori;
        }

        const desiredProduk = (oldProdukIds && oldProdukIds[idx]) ? String(oldProdukIds[idx]) : '';
        if (desiredProduk) {
            row.dataset.oldProdukId = desiredProduk;
        }

        if (kategoriSelect && kategoriSelect.value) {
            populateProdukOptionsForRow(row);
        } else if (produkSelect && produkSelect.value) {
            applyProdukMetaForRow(row);
        }
    });
}

function setupProdukRowListeners(rowEl) {
    const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
    const produkSelect = rowEl.querySelector('select.produk-select');

    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', function() {
            populateProdukOptionsForRow(rowEl);
        });
        kategoriSelect.addEventListener('addItem', function() {
            setTimeout(() => populateProdukOptionsForRow(rowEl), 0);
        });
    }

    if (produkSelect) {
        produkSelect.addEventListener('change', function() {
            applyProdukMetaForRow(rowEl);
            const idx = Array.from(document.querySelectorAll('#unified-container .unified-row')).indexOf(rowEl);
            updateProdukLabel(rowEl, idx >= 0 ? idx : 0);
        });
        produkSelect.addEventListener('addItem', function() {
            applyProdukMetaForRow(rowEl);
            const idx = Array.from(document.querySelectorAll('#unified-container .unified-row')).indexOf(rowEl);
            updateProdukLabel(rowEl, idx >= 0 ? idx : 0);
        });
    }
}

function populateProdukOptionsForRow(rowEl) {
    const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
    const produkSelect = rowEl.querySelector('select.produk-select');

    if (!kategoriSelect || !produkSelect) return;

    const kategori = (kategoriSelect.value || '').toString();
    const rawOptions = (produkByKategori && produkByKategori[kategori]) ? produkByKategori[kategori] : [];
    const options = Array.isArray(rawOptions) ? rawOptions : Object.values(rawOptions || {});

    const desiredProdukId = rowEl.dataset.oldProdukId ? String(rowEl.dataset.oldProdukId) : '';

    const choiceItems = [{ value: '', label: 'Pilih Produk', selected: true }].concat(
        options.map((opt) => {
            const v = String(opt.id);
            return {
                value: v,
                label: String(opt.nama),
                selected: desiredProdukId ? (v === desiredProdukId) : false,
            };
        })
    );

    if (rowEl._populateProdukTimer) {
        clearTimeout(rowEl._populateProdukTimer);
    }

    rowEl._populateProdukTimer = setTimeout(() => {
        const existing = choicesInstances.get(produkSelect);
        if (existing) {
            try {
                existing.destroy();
            } catch (e) {
            }
            try {
                choicesInstances.delete(produkSelect);
            } catch (e) {
            }
        }

        try {
            const instance = new Choices(produkSelect, {
                searchResultLimit: 100,
                    searchFuzziness: 0.000001,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
                    searchEnabled: true,
                    searchPlaceholderValue: 'Cari...',
                    itemSelectText: 'Tekan untuk memilih',
                    noResultsText: 'Tidak ada hasil ditemukan',
                    noChoicesText: 'Tidak ada pilihan tersedia',
                    removeItemButton: false,
                    shouldSort: false,
                    placeholder: true,
                placeholderValue: 'Pilih...'
            });
            instance.setChoices(choiceItems, 'value', 'label', true);
            choicesInstances.set(produkSelect, instance);
            produkSelect.dataset.choicesInitialized = 'true';
        } catch (e) {
            // fallback without choices
            produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
            options.forEach((opt) => {
                const o = document.createElement('option');
                o.value = String(opt.id);
                o.textContent = String(opt.nama);
                if (desiredProdukId && String(opt.id) === desiredProdukId) {
                    o.selected = true;
                }
                produkSelect.appendChild(o);
            });
        }

        applyProdukMetaForRow(rowEl);
    }, 0);
}

function applyProdukMetaForRow(rowEl) {
    const produkSelect = rowEl.querySelector('select.produk-select');
    const produsenBadges = rowEl.querySelector('.produsen-badges');
    const distributorBadges = rowEl.querySelector('.distributor-badges');
    const produsenHidden = rowEl.querySelector('.produsen-hidden-inputs');
    const distributorHidden = rowEl.querySelector('.distributor-hidden-inputs');

    if (!produkSelect || !produsenBadges || !distributorBadges || !produsenHidden || !distributorHidden) return;

    const rowIndex = rowEl.dataset.rowIndex ? String(rowEl.dataset.rowIndex) : '0';

    const produkId = String(produkSelect.value || '');
    const meta = produkId && produkMeta ? produkMeta[produkId] : null;

    const normalizeMulti = (v) => {
        if (Array.isArray(v)) return v.map(x => String(x));
        if (v === null || v === undefined) return [];
        const s = String(v);
        return s ? [s] : [];
    };

    const prodVals = meta ? normalizeMulti(meta.produsen) : [];
    const distVals = meta ? normalizeMulti(meta.distributor) : [];

    const renderBadges = (containerEl, values, badgeClass, type) => {
        if (!values || values.length === 0) {
            containerEl.innerHTML = '<span class="text-muted small badge-empty">-</span>';
            return;
        }
        containerEl.innerHTML = '';
        values.forEach((v) => {
            const span = document.createElement('span');
            span.className = badgeClass + ' badge-removable';
            span.setAttribute('data-value', String(v).trim());
            span.innerHTML = `${String(v)} <button type="button" class="badge-remove-btn" onclick="removeBadgeItem(this, '${type}')">&times;</button>`;
            containerEl.appendChild(span);
        });
    };

    const renderHiddenInputs = (containerEl, namePrefix, values) => {
        containerEl.innerHTML = '';
        (values || []).forEach((v) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `${namePrefix}[${rowIndex}][]`;
            input.value = String(v);
            containerEl.appendChild(input);
        });
    };

    renderBadges(produsenBadges, prodVals, 'badge bg-light-primary text-primary', 'produsen');
    renderBadges(distributorBadges, distVals, 'badge bg-light-info text-info', 'distributor');
    renderHiddenInputs(produsenHidden, 'produsen', prodVals);
    renderHiddenInputs(distributorHidden, 'distributor', distVals);
}

function setupDynamicFormListeners() {
    // Prevent duplicate bindings when this function is called multiple times
    if (window.__bbpUnifiedRowListenersBound) {
        return;
    }
    window.__bbpUnifiedRowListenersBound = true;

    // Add new row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-unified-btn')) {
            addNewRow();
        }
    });

    // Remove unified row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-unified-btn')) {
            let row = e.target.closest('.unified-row');
            
            const rowCount = document.querySelectorAll('#unified-container .unified-row').length;
            
            if (rowCount > 1 && row) {
                row.remove();
                updateRowNumbers();
                updateRemoveButtons();
            } else if (!row) {
                console.error('Error: Row element not found');
            } else {
                alert('Minimal harus ada satu baris data!');
            }
        }
    });
}

// Initialize Choices.js for select elements
function initializeAllChoices() {
    const selectElements = document.querySelectorAll('select.choices');
    
    selectElements.forEach(function(select) {
        if (select.classList && select.classList.contains('produk-select')) {
            return;
        }
        // Skip if already initialized
        if (select.dataset.choicesInitialized === 'true') {
            return;
        }
        
        // Skip if it's inside a Choices wrapper (already processed)
        if (select.classList.contains('choices__input')) {
            return;
        }
        
        try {
            new Choices(select, {
                searchResultLimit: 100,
                    searchFuzziness: 0.000001,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
                    searchEnabled: true,
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Pilih opsi',
                noResultsText: 'Tidak ada hasil',
                noChoicesText: 'Tidak ada pilihan',
                searchPlaceholderValue: 'Cari...',
                itemSelectText: 'Tekan untuk memilih'
            });
            if (select) {
                try {
                    choicesInstances.set(select, select.choicesInstance);
                } catch (e) {
                }
            }
            select.dataset.choicesInitialized = 'true';
        } catch(err) {
            console.error('Error initializing Choices:', err);
        }
    });
}

// Add new row - METODE LENGKAP
function addNewRow() {
    const container = document.getElementById('unified-container');
    const rowCount = container.querySelectorAll('.unified-row').length + 1;
    
    // Create new row element
    const newRow = document.createElement('div');
    newRow.className = 'unified-row mb-4 p-3 border rounded shadow-sm';
    newRow.style.backgroundColor = '#f8f9fa';
    newRow.style.borderLeft = '4px solid #435ebe';
    newRow.dataset.rowIndex = String(rowCount - 1);
    
    // Generate unique ID for radio buttons in this row
    const uniqueId = Date.now();
    
    // Set the HTML content - TEMPLATE LENGKAP DENGAN SUHU & KONDISI
    newRow.innerHTML = `
        <h5 class="text-primary mb-3">Produk ${rowCount}</h5>
        
        <!-- Informasi Produk -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select class="choices form-control kategori-produk-select" name="kategori_code[]">
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
                    <select class="form-control produk-select" name="id_bahan[]">
                        <option value="">Pilih Produk</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="fw-semibold">Produsen</div>
                        <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                        <div class="produsen-badges d-flex flex-wrap gap-1"><span class="text-muted small">-</span></div>
                        <div class="produsen-hidden-inputs"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
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

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Negara Produsen</label>
                    <select class="choices form-control" name="negara_produsen[]">
                        <option value="">Pilih Negara</option>
                        @foreach ($countries as $code => $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="detail-items">
            <div class="detail-item border rounded p-3 mb-3" style="background: #fff;" data-detail-suffix="1">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="detail-title">Detail 1</strong>
                    <button type="button" class="btn btn-danger btn-sm remove-detail-btn" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Kode Produksi</label>
                            <input type="text" class="form-control" name="kode_produksi[]" placeholder="Kode Produksi">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Expire Date</label>
                            <input type="date" class="form-control" name="expire_date[]">
                        </div>
                    </div>
                </div>
        
        <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
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
        
        <!-- SUHU PRODUK -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Suhu Produk</label>
                    <select class="form-control suhu-produk-type" name="suhu_produk_type[]" data-row-id="${uniqueId}">
                        <option value="">Pilih Jenis Suhu Produk</option>
                        <option value="Fresh">Fresh</option>
                        <option value="Frozen">Frozen</option>
                        <option value="Tidak Ada">Tidak Ada</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group suhu-produk-input" id="suhu_produk_input_${uniqueId}" style="display: none;">
                    <label class="form-label">Nilai Suhu Produk (°C)</label>
                    <input type="text" class="form-control" name="suhu_produk[]" placeholder="Contoh: -18°C atau 4°C">
                </div>
            </div>
        </div>

        <!-- SUHU MOBIL -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Suhu Mobil</label>
                    <select class="form-control suhu-mobil-type" name="suhu_mobil_type[]" data-row-id="${uniqueId}">
                        <option value="">Pilih Jenis Suhu Mobil</option>
                        <option value="Fresh">Fresh</option>
                        <option value="Frozen">Frozen</option>
                        <option value="Tidak Ada">Tidak Ada</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group suhu-mobil-input" id="suhu_mobil_input_${uniqueId}" style="display: none;">
                    <label class="form-label">Nilai Suhu Mobil (°C)</label>
                    <input type="text" class="form-control" name="suhu_mobil[]" placeholder="Contoh: -18°C atau 4°C">
                </div>
            </div>
        </div>
        
        <!-- KONDISI PRODUK -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Kondisi Produk</label>
                    <select class="form-control kondisi-produk" name="kondisi_produk[]" data-row-id="${uniqueId}">
                        <option value="">Pilih Kondisi Produk</option>
                        <option value="Fresh">Fresh</option>
                        <option value="Frozen">Frozen</option>
                        <option value="Dry">Dry</option>
                        <option value="Minyak">Minyak</option>
                        <option value="Liquid">Liquid</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group kondisi-produk-suhu" id="kondisi_produk_suhu_${uniqueId}" style="display: none;">
                    <label class="form-label">Suhu Kondisi Produk (°C)</label>
                    <input type="text" class="form-control" name="kondisi_produk_suhu[]" placeholder="Suhu Produk">
                </div>
            </div>
        </div>
        
        <!-- Kondisi Fisik -->
        <div class="form-section mb-3">
            <h6 class="text-primary mb-2">Kondisi Fisik</h6>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><strong>Kemasan</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="kondisi_fisik_kemasan[]" class="radio-value-kemasan-${uniqueId}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><strong>Warna</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_warna_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_warna_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="kondisi_fisik_warna[]" class="radio-value-warna-${uniqueId}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><strong>Benda Asing</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="kondisi_fisik_benda_asing[]" class="radio-value-benda-${uniqueId}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><strong>Aroma</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_fisik_aroma_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="kondisi_fisik_aroma[]" class="radio-value-aroma-${uniqueId}">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dokumen -->
        <div class="form-section mb-3">
            <h6 class="text-primary mb-2">Dokumen</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label"><strong>Logo Halal</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="logo_halal_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="logo_halal_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="logo_halal[]" class="radio-value-logo-${uniqueId}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label"><strong>Dokumen Halal</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dokumen_halal_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="dokumen_halal_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="dokumen_halal[]" class="radio-value-dokumen-${uniqueId}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label"><strong>COA</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="coa_${uniqueId}" value="1">
                            <label class="form-check-label">Ya ✓</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="coa_${uniqueId}" value="0">
                            <label class="form-check-label">Tidak ✗</label>
                        </div>
                        <input type="hidden" name="coa[]" class="radio-value-coa-${uniqueId}">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section mb-3 coa-upload-section">
            <h6 class="text-primary mb-2">Upload COA</h6>
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label d-block">Tipe File</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input coa-type-pdf" type="radio" name="coa_type[]" value="pdf" checked>
                            <label class="form-check-label">PDF</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input coa-type-img" type="radio" name="coa_type[]" value="gambar">
                            <label class="form-check-label">Gambar</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row coa-pdf-input">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">File COA (PDF)</label>
                        <input type="file" name="file_coa[]" class="form-control" accept="application/pdf">
                        <small class="form-text text-muted">Format: PDF. Maksimal 3MB.</small>
                    </div>
                </div>
            </div>
            <div class="row coa-img-input" style="display:none;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Foto COA</label>
                        <input type="file" name="file_coa_img[]" class="form-control" accept="image/*" capture="environment">
                        <small class="form-text text-muted">Format: JPG, PNG, WEBP, GIF. Maksimal 3MB. Gambar &gt; 3MB akan dikompres otomatis.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section mb-3">
            <h6 class="text-primary mb-2">Upload Gambar</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Foto Bahan Baku</label>
                        <input type="file" name="image_bahan_baku[]" class="form-control image-bahan-baku-input" accept="image/*" capture="camera">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hasil Pemeriksaan -->
        <div class="form-section mb-3">
            <h6 class="text-primary mb-2">Hasil Pemeriksaan</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Hasil Uji FFA</label>
                        <input type="text" class="form-control" name="hasil_uji_ffa[]" placeholder="Hasil Uji FFA">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select id="status" class="form-control @error('status_baris') is-invalid @enderror" name="status_baris[]" required>
                            <option value="">Pilih Status</option>
                            <option value="Hold">Hold</option>
                            <option value="Release">Release</option>
                            <option value="Retur">Retur</option>
                        </select>
                        @error('status_baris')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Keterangan Hasil</label>
                        <textarea class="form-control" name="keterangan_hasil[]" rows="2" placeholder="Keterangan hasil pemeriksaan"></textarea>
                    </div>
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
        
        <!-- Buttons -->
        <div class="row mt-3 pt-3 border-top">
            <div class="col-md-12">
                <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Produk</button>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);

    try {
        initBbpCollapses();
        collapseAllProdukExcept(newRow);
    } catch (e) {
    }

    // Initialize produk meta badges for new row
    try {
        setupProdukRowListeners(newRow);
        applyProdukMetaForRow(newRow);
    } catch (err) {
    }
    
    // Setup conditional logic for Suhu Produk in new row
    setupSuhuProdukLogic(newRow, uniqueId);
    
    // Setup conditional logic for Suhu Mobil in new row
    setupSuhuMobilLogic(newRow, uniqueId);
    
    // Setup conditional logic for Kondisi Produk in new row
    setupKondisiProdukLogic(newRow, uniqueId);
    
    // Setup radio button listeners for the new row
    setupRadioListeners(newRow, uniqueId);
    
    // Initialize Choices.js ONLY for selects in the new row
    const newSelects = newRow.querySelectorAll('select.choices');
    
    newSelects.forEach(select => {
        try {
            new Choices(select, {
                searchResultLimit: 100,
                    searchFuzziness: 0.000001,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
                    searchEnabled: true,
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Pilih opsi',
                noResultsText: 'Tidak ada hasil',
                noChoicesText: 'Tidak ada pilihan',
                searchPlaceholderValue: 'Cari...',
                itemSelectText: 'Tekan untuk memilih'
            });
            select.dataset.choicesInitialized = 'true';
        } catch(err) {
            console.error('Error initializing new select:', err);
        }
    });
    
    updateRemoveButtons();
}

// Setup Suhu Produk conditional logic for dynamic rows
function setupSuhuProdukLogic(row, uniqueId) {
    const suhuProdukSelect = row.querySelector('.suhu-produk-type');
    const suhuProdukInput = row.querySelector(`#suhu_produk_input_${uniqueId}`);
    
    if (suhuProdukSelect && suhuProdukInput) {
        suhuProdukSelect.addEventListener('change', function() {
            const value = this.value;
            if (value === 'Fresh' || value === 'Frozen') {
                suhuProdukInput.style.display = 'block';
            } else {
                suhuProdukInput.style.display = 'none';
                const input = suhuProdukInput.querySelector('input');
                if (input) input.value = '';
            }
        });
    }
}

// Setup Suhu Mobil conditional logic for dynamic rows
function setupSuhuMobilLogic(row, uniqueId) {
    const suhuMobilSelect = row.querySelector('.suhu-mobil-type');
    const suhuMobilInput = row.querySelector(`#suhu_mobil_input_${uniqueId}`);
    
    if (suhuMobilSelect && suhuMobilInput) {
        suhuMobilSelect.addEventListener('change', function() {
            const value = this.value;
            if (value === 'Fresh' || value === 'Frozen') {
                suhuMobilInput.style.display = 'block';
            } else {
                suhuMobilInput.style.display = 'none';
                const input = suhuMobilInput.querySelector('input');
                if (input) input.value = '';
            }
        });
    }
}

function setupKondisiProdukLogic(row, uniqueId) {
    const kondisiProdukSelect = row.querySelector('.kondisi-produk');
    const kondisiProdukSuhu = row.querySelector(`#kondisi_produk_suhu_${uniqueId}`);
    
    if (kondisiProdukSelect && kondisiProdukSuhu) {
        kondisiProdukSelect.addEventListener('change', function() {
            const value = this.value;
            if (value === 'Fresh' || value === 'Frozen' || value === 'Dry' || value === 'Minyak' || value === 'Liquid') {
                kondisiProdukSuhu.style.display = 'block';
            } else {
                kondisiProdukSuhu.style.display = 'none';
                const input = kondisiProdukSuhu.querySelector('input');
                if (input) input.value = '';
            }
        });
    }
}

// Setup radio button listeners
function setupRadioListeners(row, uniqueId) {
    // Kondisi Fisik - Kemasan
    row.querySelectorAll(`input[name="kondisi_fisik_kemasan_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-kemasan-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Kondisi Fisik - Warna
    row.querySelectorAll(`input[name="kondisi_fisik_warna_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-warna-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Kondisi Fisik - Benda Asing
    row.querySelectorAll(`input[name="kondisi_fisik_benda_asing_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-benda-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Kondisi Fisik - Aroma
    row.querySelectorAll(`input[name="kondisi_fisik_aroma_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-aroma-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Dokumen - Logo Halal
    row.querySelectorAll(`input[name="logo_halal_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-logo-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Dokumen - Dokumen Halal
    row.querySelectorAll(`input[name="dokumen_halal_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-dokumen-${uniqueId}`).value = this.value;
            }
        });
    });
    
    // Dokumen - COA
    row.querySelectorAll(`input[name="coa_${uniqueId}"]`).forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                row.querySelector(`.radio-value-coa-${uniqueId}`).value = this.value;
            }
        });
    });
}

// Cache select options from first row using Choices instance
function cacheSelectOptions() {
    const container = document.getElementById('unified-container');
    const firstRow = container.querySelector('.unified-row');
    
    if (!firstRow) return;
    
    // Cache Bahan options from Choices instance
    const bahanSelect = firstRow.querySelector('select[name="id_bahan[]"]');
    if (bahanSelect && bahanSelect.choicesInstance) {
        selectOptionsCache.bahan = bahanSelect.choicesInstance.config.choices
            .map(opt => ({ value: opt.value, text: opt.label }));
    }
    
    // Cache Produsen options from Choices instance
    const produsenSelect = firstRow.querySelector('select[name="produsen[]"]');
    if (produsenSelect && produsenSelect.choicesInstance) {
        selectOptionsCache.produsen = produsenSelect.choicesInstance.config.choices
            .map(opt => ({ value: opt.value, text: opt.label }));
    }
    
    // Cache Negara options from Choices instance
    const negaraSelect = firstRow.querySelector('select[name="negara_produsen[]"]');
    if (negaraSelect && negaraSelect.choicesInstance) {
        selectOptionsCache.negara = negaraSelect.choicesInstance.config.choices
            .map(opt => ({ value: opt.value, text: opt.label }));
    }
    
    // Cache Distributor options from Choices instance
    const distributorSelect = firstRow.querySelector('select[name="distributor[]"]');
    if (distributorSelect && distributorSelect.choicesInstance) {
        selectOptionsCache.distributor = distributorSelect.choicesInstance.config.choices
            .map(opt => ({ value: opt.value, text: opt.label }));
    }
}

// Helper functions to get options from cache
function getBahanOptions() {
    return selectOptionsCache.bahan
        .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
        .join('');
}

function getProdusenOptions() {
    return selectOptionsCache.produsen
        .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
        .join('');
}

function getCountryOptions() {
    return selectOptionsCache.negara
        .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
        .join('');
}

function getDistributorOptions() {
    return selectOptionsCache.distributor
        .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
        .join('');
}

// Update row numbers
function updateRowNumbers() {
    const rows = document.querySelectorAll('#unified-container .unified-row');
    rows.forEach((row, index) => {
        const title = row.querySelector('h6');
        if (title) {
            title.textContent = `Produk ${index + 1}`;
        }
    });
}

// Update remove buttons visibility
function updateRemoveButtons() {
    const rows = document.querySelectorAll('#unified-container .unified-row');
    rows.forEach((row) => {
        const removeBtn = row.querySelector('.remove-unified-btn');
        if (removeBtn) {
            if (rows.length > 1) {
                removeBtn.style.display = 'inline-block';
            } else {
                removeBtn.style.display = 'none';
            }
        }
    });
}

/* --- IMAGE COMPRESSION LOGIC --- */
const MAX_SIZE = 1024 * 1024; // 1MB target (validation allows up to 5MB)

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

    // More aggressive initial resize for tablet cameras
    let maxDimension = 1920;
    
    // If original file is very large (> 3MB), use smaller dimension
    if (file.size > 3 * 1024 * 1024) {
        maxDimension = 1280;
    }
    
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

    // Start with quality 0.8 and reduce more aggressively
    let quality = 0.8;
    let blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));
    
    // Keep compressing until under 1MB, allow going down to quality 0.3
    while (blob && blob.size > MAX_SIZE && quality > 0.3) {
        quality -= 0.05;
        blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));
    }
    
    // If still too large, reduce dimensions further
    if (blob && blob.size > MAX_SIZE) {
        width = Math.round(width * 0.8);
        height = Math.round(height * 0.8);
        canvas.width = width;
        canvas.height = height;
        ctx.drawImage(img, 0, 0, width, height);
        quality = 0.7;
        blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));
    }

    const newName = (file.name || 'image').replace(/\.[^/.]+$/, '') + '.jpg';
    return new File([blob], newName, { type: 'image/jpeg', lastModified: Date.now() });
}

async function handleImageInputChange(input) {
    const file = input.files && input.files[0] ? input.files[0] : null;
    if (!file) return;

    // Compress if file > 500KB (more aggressive to prevent upload errors)
    const COMPRESS_THRESHOLD = 512 * 1024; // 500KB
    if (file.size <= COMPRESS_THRESHOLD) return;

    // Show processing feedback
    const formGroup = input.closest('.form-group');
    const labelEl = formGroup ? formGroup.querySelector('.form-label') : null;
    const originalLabel = labelEl ? labelEl.innerHTML : 'Foto';
    
    if (labelEl) {
        labelEl.innerHTML = originalLabel + ' <span class="badge bg-primary"><i class="bi bi-hourglass-split"></i> Mengompres...</span>';
    }
    input.disabled = true;

    try {
        const compressedFile = await compressImage(file);
        const dt = new DataTransfer();
        dt.items.add(compressedFile);
        input.files = dt.files;
        
        if (labelEl) {
            labelEl.innerHTML = originalLabel + ' <span class="badge bg-success"><i class="bi bi-check-circle"></i> Selesai (Auto-Compressed)</span>';
        }
    } catch (e) {
        console.error('Compression error:', e);
        if (labelEl) {
            labelEl.innerHTML = originalLabel + ' <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Kompresi Gagal</span>';
        }
    } finally {
        input.disabled = false;
        setTimeout(() => {
            if (labelEl) labelEl.innerHTML = originalLabel;
        }, 3000);
    }
}

document.addEventListener('change', function(e) {
    const input = e.target;
    if (input && ((input.classList && input.classList.contains('image-bahan-baku-input')) || input.name === 'file_coa_img[]')) {
        handleImageInputChange(input);
    }
});

// COA type toggle: PDF / Gambar
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('coa-type-pdf') && e.target.checked) {
        const section = e.target.closest('.coa-upload-section');
        if (!section) return;
        section.querySelector('.coa-pdf-input').style.display = '';
        section.querySelector('.coa-img-input').style.display = 'none';
        section.querySelector('.coa-img-input input[type="file"]').value = '';
    }
    if (e.target.classList.contains('coa-type-img') && e.target.checked) {
        const section = e.target.closest('.coa-upload-section');
        if (!section) return;
        section.querySelector('.coa-pdf-input').style.display = 'none';
        section.querySelector('.coa-img-input').style.display = '';
        section.querySelector('.coa-pdf-input input[type="file"]').value = '';
    }
});

// Before submit: disable hidden COA file input + radio buttons so only active one is sent
try {
    const coaForm = document.querySelector('form[action*="pemeriksaan-bahan-baku"]');
    if (coaForm) {
        coaForm.addEventListener('submit', function() {
            document.querySelectorAll('.coa-upload-section').forEach(function(section) {
                var pdfDiv = section.querySelector('.coa-pdf-input');
                var imgDiv = section.querySelector('.coa-img-input');
                if (pdfDiv && imgDiv) {
                    var pdfInput = pdfDiv.querySelector('input[type="file"]');
                    var imgInput = imgDiv.querySelector('input[type="file"]');
                    if (pdfDiv.style.display === 'none' && pdfInput) { pdfInput.disabled = true; }
                    if (imgDiv.style.display === 'none' && imgInput) { imgInput.disabled = true; }
                }
                // Disable radio buttons (UI only, not needed by backend)
                section.querySelectorAll('input[type="radio"]').forEach(function(r) { r.disabled = true; });
            });
        }, true); // capture phase → runs before the flatten handler
    }
} catch(err) {}

// Initialize on page load
updateRemoveButtons();

/**
 * Hapus satu badge Produsen/Distributor dari tampilan form Bahan Baku Penunjang.
 * Data master TIDAK berubah.
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

    // Rebuild hidden inputs dari badge yang tersisa
    if (rowEl) {
        const produsenBadgesEl = rowEl.querySelector('.produsen-badges');
        const distributorBadgesEl = rowEl.querySelector('.distributor-badges');

        const getValsFromBadges = (el) => {
            if (!el) return [];
            return Array.from(el.querySelectorAll('.badge-removable[data-value]'))
                       .map(b => String(b.getAttribute('data-value') || b.dataset.value || '').trim())
                       .filter(v => v !== '');
        };

        const produsenVals = getValsFromBadges(produsenBadgesEl);
        const distributorVals = getValsFromBadges(distributorBadgesEl);

        const headerProdusenHidden = rowEl.querySelector('.produsen-hidden-inputs');
        const headerDistributorHidden = rowEl.querySelector('.distributor-hidden-inputs');
        const rowIndex = rowEl.dataset.rowIndex ? String(rowEl.dataset.rowIndex) : '0';

        const rebuildHidden = (wrap, vals, namePrefix) => {
            if (!wrap) return;
            wrap.innerHTML = '';
            vals.forEach(v => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = `${namePrefix}[${rowIndex}][]`;
                inp.value = v;
                wrap.appendChild(inp);
            });
        };

        rebuildHidden(headerProdusenHidden, produsenVals, 'produsen');
        rebuildHidden(headerDistributorHidden, distributorVals, 'distributor');
    }
}
</script>

<!-- Validasi Front-End Form -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-pemeriksaan-bahan-baku-penunjang');
    
    if (!form) return;

    /**
     * Validasi Form - Cek field wajib
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

        // 2. Status Baris (wajib) - Minimal satu item harus memilih "Hold" atau "Release"
        const statusBaris = document.querySelectorAll('select[name="status_baris[]"]');
        let hasValidStatus = false;
        const statusErrors = [];
        
        statusBaris.forEach((select, index) => {
            const value = select.value ? select.value.trim() : '';
            if (value === 'Hold' || value === 'Release' || value === 'Retur') {
                hasValidStatus = true;
                removeHighlight(select);
            } else {
                statusErrors.push(`Status Produk #${index + 1} harus dipilih (Hold atau Release)`);
                highlightField(select);
            }
        });

        if (!hasValidStatus) {
            if (statusBaris.length === 0) {
                errors.push('Minimal ada satu produk dengan Status yang harus dipilih (Hold, Release, atau Retur)');
            } else {
                errors.push(...statusErrors);
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
@endpush
@endsection
