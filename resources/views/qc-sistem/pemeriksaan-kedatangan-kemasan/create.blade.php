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

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-kedatangan-kemasan.store') }}" method="POST" enctype="multipart/form-data">
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
                                                        <input class="form-check-input" type="radio" id="segel_option" name="segel_gembok" value="segel" {{ old('segel_gembok') == 'segel' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="segel_option">
                                                            Segel
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="gembok_option" name="segel_gembok" value="gembok" {{ old('segel_gembok') == 'gembok' ? 'checked' : '' }}>
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
                                            document.querySelectorAll('input[name="segel_gembok"]').forEach(function(radio) {
                                                radio.addEventListener('change', function() {
                                                    const container = document.getElementById('no_segel_container');
                                                    if (this.value === 'segel') {
                                                        container.style.display = 'block';
                                                    } else {
                                                        container.style.display = 'none';
                                                        document.getElementById('no_segel').value = '';
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                    
                                    <!-- Kondisi Mobil Pengangkut -->
                                    <div class="form-section mb-4" id="kondisi-mobil-section">
                                        <h5 class="text-primary mb-3">Kondisi Mobil Pengangkut</h5>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="kondisi_mobil_check_all">
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
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const section = document.getElementById('kondisi-mobil-section');
                                            const checkAll = document.getElementById('kondisi_mobil_check_all');
                                            if (!section || !checkAll) return;

                                            checkAll.addEventListener('change', function() {
                                                const radios = section.querySelectorAll('input[type="radio"][name^="kondisi_mobil["]');
                                                if (this.checked) {
                                                    radios.forEach((radio) => {
                                                        if (radio.value === '1') radio.checked = true;
                                                    });
                                                } else {
                                                    const names = new Set();
                                                    radios.forEach((radio) => names.add(radio.name));
                                                    names.forEach((name) => {
                                                        section.querySelectorAll(`input[type="radio"][name="${name}"]`).forEach((r) => {
                                                            r.checked = false;
                                                        });
                                                    });
                                                }
                                            });
                                        });
                                    </script>

                                    <!-- UNIFIED DYNAMIC FORM - Bahan Kemasan, Informasi Kemasan, Kondisi Fisik, Detail Tambahan, Dokumen -->
                                    <!-- DYNAMIC ROWS CONTAINER -->
                                    <div id="unified-container">
                                        <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;" data-row-index="0">
                                            <!-- Bahan Kemasan -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Bahan Kemasan</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Kategori</label>
                                                            <select class="choices form-control kategori-produk-select @error('kategori_code.0') is-invalid @enderror" name="kategori_code[]">
                                                                <option value="">Pilih Kategori</option>
                                                                @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                    <option value="{{ $kategori }}" {{ old('kategori_code.0') == $kategori ? 'selected' : '' }}>
                                                                        {{ $kategori }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('kategori_code.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Produk</label>
                                                            <select class="form-control produk-select @error('id_produk.0') is-invalid @enderror" name="id_produk[]">
                                                                <option value="">Pilih Produk</option>
                                                            </select>
                                                            @error('id_produk.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
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
                                                                        <span class="badge bg-light-primary text-primary">{{ $p }}</span>
                                                                    @empty
                                                                        <span class="text-muted small">-</span>
                                                                    @endforelse
                                                                </div>

                                                                <div class="produsen-hidden-inputs">
                                                                    @foreach ($oldProdusen0 as $p)
                                                                        <input type="hidden" name="produsen[0][]" value="{{ $p }}">
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
                                                                        <span class="badge bg-light-info text-info">{{ $d }}</span>
                                                                    @empty
                                                                        <span class="text-muted small">-</span>
                                                                    @endforelse
                                                                </div>

                                                                <div class="distributor-hidden-inputs">
                                                                    @foreach ($oldDistributor0 as $d)
                                                                        <input type="hidden" name="distributor[0][]" value="{{ $d }}">
                                                                    @endforeach
                                                                </div>

                                                                @error('distributor.0')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Kode Produksi</label>
                                                            <input type="text" class="form-control @error('kode_produksi.0') is-invalid @enderror"
                                                                name="kode_produksi[]" value="{{ old('kode_produksi.0') }}" placeholder="Kode Produksi">
                                                            @error('kode_produksi.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Jumlah Datang (Kg/pcs/roll)</label>
                                                            <input type="text" class="form-control @error('jumlah_datang.0') is-invalid @enderror"
                                                                name="jumlah_datang[]" value="{{ old('jumlah_datang.0') }}" placeholder="Jumlah Datang">
                                                            @error('jumlah_datang.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Jumlah Sampling (pcs/kg/roll)</label>
                                                            <input type="text" class="form-control @error('jumlah_sampling.0') is-invalid @enderror"
                                                                name="jumlah_sampling[]" value="{{ old('jumlah_sampling.0') }}" placeholder="Jumlah Sampling">
                                                            @error('jumlah_sampling.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Spesifikasi</label>
                                                            <textarea class="form-control @error('spesifikasi.0') is-invalid @enderror"
                                                                name="spesifikasi[]" rows="2" placeholder="Spesifikasi">{{ old('spesifikasi.0') }}</textarea>
                                                            @error('spesifikasi.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kondisi Fisik -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Penampakan</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="penampakan[]" value="1" {{ old('penampakan.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="penampakan[]" value="0" {{ old('penampakan.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Sealing</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sealing[]" value="1" {{ old('sealing.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sealing[]" value="0" {{ old('sealing.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Cetakan</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="cetakan[]" value="1" {{ old('cetakan.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="cetakan[]" value="0" {{ old('cetakan.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detail Tambahan -->
                                            <div class="form-section mb-3">
                                                <h6 class="text-primary mb-2">Detail Tambahan</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Ketebalan (Micron)</label>
                                                            <input type="number" step="0.01" class="form-control @error('ketebalan_micron.0') is-invalid @enderror"
                                                                name="ketebalan_micron[]" value="{{ old('ketebalan_micron.0') }}" placeholder="Ketebalan">
                                                            @error('ketebalan_micron.0')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Dimensi</label>
                                                            <input type="text" class="form-control @error('dimensi.0') is-invalid @enderror"
                                                                name="dimensi[]" value="{{ old('dimensi.0') }}" placeholder="Dimensi">
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
                                                                <input class="form-check-input" type="radio" name="logo_halal[]" value="1" {{ old('logo_halal.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="logo_halal[]" value="0" {{ old('logo_halal.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal[]" value="1" {{ old('dokumen_halal.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="dokumen_halal[]" value="0" {{ old('dokumen_halal.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>COA</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="coa[]" value="1" {{ old('coa.0') == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="coa[]" value="0" {{ old('coa.0') == '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Keterangan</label>
                                                            <textarea class="form-control @error('keterangan.0') is-invalid @enderror"
                                                                name="keterangan[]" rows="2" placeholder="Keterangan tambahan">{{ old('keterangan.0') }}</textarea>
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
                                                            <label class="form-label">Gambar Kemasan (Max 1MB)</label>
                                                            <input type="file" name="image_kemasan[]" class="form-control image-kemasan-input" accept="image/*" capture="camera">
                                                            @error('image_kemasan.0')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Unified Buttons -->
                                            <div class="row mt-3 pt-3 border-top">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-danger btn-sm remove-unified-btn" style="display: none;"><i class="bi bi-trash"></i> Hapus Baris</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3 pt-3 border-top">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary btn-sm add-unified-btn"><i class="bi bi-plus"></i> Tambah Baris</button>
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
                <!-- Bahan Kemasan -->
                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Bahan Kemasan</h6>
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
                                <select class="choices form-control produk-select" name="id_produk[]">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Kode Produksi</label>
                                <input type="text" class="form-control" name="kode_produksi[]" placeholder="Kode Produksi">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Jumlah Datang (Kg/pcs/roll)</label>
                                <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah Datang">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Jumlah Sampling (pcs/kg/roll)</label>
                                <input type="text" class="form-control" name="jumlah_sampling[]" placeholder="Jumlah Sampling">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Spesifikasi</label>
                                <textarea class="form-control" name="spesifikasi[]" rows="2" placeholder="Spesifikasi"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kondisi Fisik -->
                <div class="form-section mb-3">
                    <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>Penampakan</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="penampakan[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="penampakan[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>Sealing</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sealing[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sealing[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>Cetakan</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cetakan[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cetakan[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Tambahan -->
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
                                    <input class="form-check-input" type="radio" name="logo_halal[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="logo_halal[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>Dokumen Halal</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dokumen_halal[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dokumen_halal[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"><strong>COA</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coa[]" value="1">
                                    <label class="form-check-label">Ya ✓</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coa[]" value="0">
                                    <label class="form-check-label">Tidak ✗</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                                <label class="form-label">Gambar Kemasan (Max 1MB)</label>
                                <input type="file" name="image_kemasan[]" class="form-control image-kemasan-input" accept="image/*" capture="camera">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unified Buttons -->
                <div class="row mt-3 pt-3 border-top">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Baris</button>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            newRow.dataset.rowIndex = String(rowIndex);
            
            // Initialize Choices.js for new selects ONLY in the new row
            const newSelects = newRow.querySelectorAll('select.choices');
            newSelects.forEach(select => {
                if (select.classList && select.classList.contains('produk-select')) {
                    return;
                }
                const instance = new Choices(select, {
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

        const renderBadges = (containerEl, values, badgeClass) => {
            if (!values || values.length === 0) {
                containerEl.innerHTML = '<span class="text-muted small">-</span>';
                return;
            }
            containerEl.innerHTML = '';
            values.forEach((v) => {
                const span = document.createElement('span');
                span.className = badgeClass;
                span.textContent = String(v);
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

        renderBadges(produsenBadges, prodVals, 'badge bg-light-primary text-primary');
        renderBadges(distributorBadges, distVals, 'badge bg-light-info text-info');
        renderHiddenInputs(produsenHidden, 'produsen', prodVals);
        renderHiddenInputs(distributorHidden, 'distributor', distVals);
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
            }
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
    });

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
    }
    
    // Initialize on page load
    updateRemoveButtons();

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
</script>
@endsection