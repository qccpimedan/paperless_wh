@extends('layouts.app')
@section('container')
<div id="main">

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Pemeriksaan Kedatangan Kemasan</h3>
                    <p class="text-subtitle text-muted">Edit data pemeriksaan kedatangan kemasan</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}">Pemeriksaan Kedatangan Kemasan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                            <h4 class="card-title">Form Edit Pemeriksaan Kedatangan Kemasan</h4>
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

                                <form id="form-edit-kemasan" class="form form-horizontal" action="{{ route('pemeriksaan-kedatangan-kemasan.update', $pemeriksaanKedatanganKemasan->uuid) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- Informasi Dasar -->
                                    <div class="form-section mb-4">
                                        <h5 class="text-primary mb-3">Informasi Dasar</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                    <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                        name="tanggal" value="{{ old('tanggal', $pemeriksaanKedatanganKemasan->tanggal->format('Y-m-d')) }}" required>
                                                    @error('tanggal')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="no_po">No. PO</label>
                                                    <input type="text" id="no_po" class="form-control @error('no_po') is-invalid @enderror"
                                                        name="no_po" value="{{ old('no_po', $pemeriksaanKedatanganKemasan->no_po) }}" placeholder="No. PO">
                                                    @error('no_po')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status">Status <span class="text-danger">*</span></label>
                                                    <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                                                        <option value="">Pilih Status</option>
                                                        @foreach (['Hold', 'Release', 'Retur'] as $st)
                                                            <option value="{{ $st }}" {{ old('status', $pemeriksaanKedatanganKemasan->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_shift">Shift</label>
                                                    <select id="id_shift" class="form-control @error('id_shift') is-invalid @enderror" name="id_shift">
                                                        <option value="">Pilih Shift </option>
                                                        @foreach($shifts as $shift)
                                                            <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanKedatanganKemasan->id_shift) == $shift->id ? 'selected' : '' }}>
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="jenis_mobil">Jenis Mobil</label>
                                                    <input type="text" id="jenis_mobil" class="form-control @error('jenis_mobil') is-invalid @enderror"
                                                        name="jenis_mobil" value="{{ old('jenis_mobil', $pemeriksaanKedatanganKemasan->jenis_mobil) }}" placeholder="Jenis Mobil">
                                                    @error('jenis_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="no_mobil">No. Mobil</label>
                                                    <input type="text" id="no_mobil" class="form-control @error('no_mobil') is-invalid @enderror"
                                                        name="no_mobil" value="{{ old('no_mobil', $pemeriksaanKedatanganKemasan->no_mobil) }}" placeholder="No. Mobil">
                                                    @error('no_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nama_supir">Nama Supir</label>
                                                    <input type="text" id="nama_supir" class="form-control @error('nama_supir') is-invalid @enderror"
                                                        name="nama_supir" value="{{ old('nama_supir', $pemeriksaanKedatanganKemasan->nama_supir) }}" placeholder="Nama Supir">
                                                    @error('nama_supir')
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
                                                        <input class="form-check-input" type="radio" id="segel_option" name="segel_gembok" value="segel" {{ old('segel_gembok', $pemeriksaanKedatanganKemasan->segel_gembok) == 'segel' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="segel_option">Segel</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="gembok_option" name="segel_gembok" value="gembok" {{ old('segel_gembok', $pemeriksaanKedatanganKemasan->segel_gembok) == 'gembok' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="gembok_option">Gembok</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="no_segel_container" style="display: {{ old('segel_gembok', $pemeriksaanKedatanganKemasan->segel_gembok) == 'segel' ? 'block' : 'none' }};">
                                                <div class="form-group">
                                                    <label for="no_segel">No. Segel</label>
                                                    <input type="text" id="no_segel" class="form-control @error('no_segel') is-invalid @enderror"
                                                        name="no_segel" value="{{ old('no_segel', $pemeriksaanKedatanganKemasan->no_segel) }}" placeholder="No. Segel">
                                                    @error('no_segel')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                    </div>

                                    <!-- Kondisi Mobil Pengangkut -->
                                    @php
                                        $kondisiMobilItems = [
                                            ['bersih', 'Bersih'],
                                            ['bebas_hama', 'Bebas dari Hama'],
                                            ['tidak_kondensasi', 'Tidak Kondensasi'],
                                            ['bebas_produk_halal', 'Bebas dari Produk Non Halal'],
                                            ['tidak_berbau', 'Tidak Berbau'],
                                            ['tidak_ada_sampah', 'Tidak ada Sampah'],
                                            ['tidak_ada_mikroba', 'Tidak ada Mikroba'],
                                            ['lampu_cover_utuh', 'Lampu Cover utuh'],
                                            ['pallet_utuh', 'Pallet utuh'],
                                            ['tertutup_rapat', 'Tertutup rapat'],
                                            ['bebas_kontaminan', 'Bebas kontaminan'],
                                        ];
                                    @endphp
                                    <div class="form-section mb-4">
                                        <h5 class="text-primary mb-3">Kondisi Mobil Pengangkut</h5>
                                        @foreach (array_chunk($kondisiMobilItems, 3) as $barisKondisi)
                                            <div class="row">
                                                @foreach ($barisKondisi as [$key, $label])
                                                    @php
                                                        $kmVal = old('kondisi_mobil.' . $key, $pemeriksaanKedatanganKemasan->kondisi_mobil[$key] ?? false);
                                                    @endphp
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>{{ $label }}</strong></label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_mobil[{{ $key }}]" id="{{ $key }}_ya" value="1" {{ $kmVal ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="{{ $key }}_ya">Ya ✓</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_mobil[{{ $key }}]" id="{{ $key }}_tidak" value="0" {{ !$kmVal ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="{{ $key }}_tidak">Tidak ✗</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Dynamic Rows Data -->
                                    @php
                                        $dec = fn ($v) => json_decode($v ?? '[]', true) ?? [];
                                        $id_bahans = $dec($pemeriksaanKedatanganKemasan->id_bahan_array);
                                        $produsens_arr = $dec($pemeriksaanKedatanganKemasan->produsen_array);
                                        $distributors_arr = $dec($pemeriksaanKedatanganKemasan->distributor_array);
                                        $kode_produksis = $dec($pemeriksaanKedatanganKemasan->kode_produksi_array);
                                        $jumlah_datangs = $dec($pemeriksaanKedatanganKemasan->jumlah_datang_array);
                                        $unit_datangs = $dec($pemeriksaanKedatanganKemasan->unit_datang_array);
                                        $jumlah_samplings = $dec($pemeriksaanKedatanganKemasan->jumlah_sampling_array);
                                        $unit_samplings = $dec($pemeriksaanKedatanganKemasan->unit_sampling_array);
                                        $spesifikasis = $dec($pemeriksaanKedatanganKemasan->spesifikasi_array);
                                        $penampakans = $dec($pemeriksaanKedatanganKemasan->penampakan_array);
                                        $sealings = $dec($pemeriksaanKedatanganKemasan->sealing_array);
                                        $cetakans = $dec($pemeriksaanKedatanganKemasan->cetakan_array);
                                        $ketebalan_microns = $dec($pemeriksaanKedatanganKemasan->ketebalan_micron_array);
                                        $dimensis = $dec($pemeriksaanKedatanganKemasan->dimensi_array);
                                        $berats = $dec($pemeriksaanKedatanganKemasan->berat_array);
                                        $statuses = $dec($pemeriksaanKedatanganKemasan->status_array);
                                        $logo_halals = $dec($pemeriksaanKedatanganKemasan->logo_halal_array);
                                        $dokumen_halals = $dec($pemeriksaanKedatanganKemasan->dokumen_halal_array);
                                        $coas = $dec($pemeriksaanKedatanganKemasan->coa_array);
                                        $file_coas = $dec($pemeriksaanKedatanganKemasan->file_coa_array);
                                        $keterangans = $dec($pemeriksaanKedatanganKemasan->keterangan_array);
                                        $image_kemasans = $dec($pemeriksaanKedatanganKemasan->image_kemasan_array);

                                        $kondisiFisikSrc = [
                                            'penampakan' => ['Penampakan', $penampakans],
                                            'sealing' => ['Sealing', $sealings],
                                            'cetakan' => ['Cetakan', $cetakans],
                                            'logo_halal' => ['Logo Halal', $logo_halals],
                                        ];

                                        // FIX: baris dengan produk kosong TIDAK lagi dibuang (sebelumnya "continue" -> data hilang saat disimpan)
                                        $groupedDetailIdx = [];
                                        foreach ((array) $id_bahans as $i => $pid) {
                                            $pid = $pid === null ? '' : (string) $pid;
                                            if (!isset($groupedDetailIdx[$pid])) $groupedDetailIdx[$pid] = [];
                                            $groupedDetailIdx[$pid][] = $i;
                                        }

                                        $globalDetailSeq = 0;
                                    @endphp

                                    <div id="unified-container">
                                        @foreach($groupedDetailIdx as $produkId => $detailIdxList)
                                            @php
                                                $firstIdx = $detailIdxList[0] ?? null;
                                                $existingKategori = $produkId ? ($existingKategoriByProdukId[$produkId] ?? '') : '';
                                                $docDokumen = $firstIdx !== null ? ($dokumen_halals[$firstIdx] ?? '') : '';
                                                $docCoa = $firstIdx !== null ? ($coas[$firstIdx] ?? '') : '';

                                                $existingProdusen = $firstIdx !== null ? ($produsens_arr[$firstIdx] ?? '') : '';
                                                if (is_array($existingProdusen)) {
                                                    $existingProdusen = implode(', ', array_values(array_filter(array_map('strval', $existingProdusen), fn ($v) => $v !== '')));
                                                }
                                                $existingProdusenItems = array_values(array_filter(array_map('trim', explode(',', (string) $existingProdusen)), fn ($v) => $v !== ''));

                                                $existingDistributor = $firstIdx !== null ? ($distributors_arr[$firstIdx] ?? '') : '';
                                                if (is_array($existingDistributor)) {
                                                    $existingDistributor = implode(', ', array_values(array_filter(array_map('strval', $existingDistributor), fn ($v) => $v !== '')));
                                                }
                                                $existingDistributorItems = array_values(array_filter(array_map('trim', explode(',', (string) $existingDistributor)), fn ($v) => $v !== ''));
                                            @endphp
                                            <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;" data-old-produk-id="{{ $produkId }}" data-first-index="{{ $firstIdx }}">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="mb-0 bahan-title text-white">Bahan #{{ $loop->iteration }}</h6>
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
                                                                        <option value="{{ $kategori }}" {{ $existingKategori == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
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
                                                        <div class="col-md-6">
                                                            <div class="card border-0 shadow-sm">
                                                                <div class="card-body p-3">
                                                                    <div class="fw-semibold">Produsen</div>
                                                                    <div class="small text-muted mb-2">Pilih dari seluruh master Produsen Plant ini</div>
                                                                    {{-- PENTING: class js-supplier-choices (BUKAN "choices") agar tidak di-init oleh script global layout --}}
                                                                    <select class="form-select js-supplier-choices supplier-master-select produsen-master-select mb-2" multiple data-supplier-type="produsen" aria-label="Pilih produsen">
                                                                        @foreach (($produsens ?? collect()) as $produsen)
                                                                            <option value="{{ $produsen->nama_produsen }}" {{ in_array($produsen->nama_produsen, $existingProdusenItems, true) ? 'selected' : '' }}>{{ $produsen->nama_produsen }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="produsen-badges d-flex flex-wrap gap-1">
                                                                        @forelse ($existingProdusenItems as $p)
                                                                            <span class="badge bg-light-primary text-primary badge-removable" data-value="{{ $p }}">
                                                                                {{ $p }} <button type="button" class="badge-remove-btn edit-supplier-remove" data-type="produsen" title="Hapus produsen ini dari form">Hapus</button>
                                                                            </span>
                                                                        @empty
                                                                            <span class="text-muted small badge-empty">-</span>
                                                                        @endforelse
                                                                    </div>
                                                                    <div class="produsen-hidden-inputs">
                                                                        @foreach ($existingProdusenItems as $p)
                                                                            <input type="hidden" class="produsen-hidden-item" value="{{ $p }}">
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card border-0 shadow-sm">
                                                                <div class="card-body p-3">
                                                                    <div class="fw-semibold">Distributor</div>
                                                                    <div class="small text-muted mb-2">Pilih dari seluruh master Distributor Plant ini</div>
                                                                    <select class="form-select js-supplier-choices supplier-master-select distributor-master-select mb-2" multiple data-supplier-type="distributor" aria-label="Pilih distributor">
                                                                        @foreach (($distributors ?? collect()) as $distributor)
                                                                            <option value="{{ $distributor->nama_distributor }}" {{ in_array($distributor->nama_distributor, $existingDistributorItems, true) ? 'selected' : '' }}>{{ $distributor->nama_distributor }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="distributor-badges d-flex flex-wrap gap-1">
                                                                        @forelse ($existingDistributorItems as $d)
                                                                            <span class="badge bg-light-info text-info badge-removable" data-value="{{ $d }}">
                                                                                {{ $d }} <button type="button" class="badge-remove-btn edit-supplier-remove" data-type="distributor" title="Hapus distributor ini dari form">Hapus</button>
                                                                            </span>
                                                                        @empty
                                                                            <span class="text-muted small badge-empty">-</span>
                                                                        @endforelse
                                                                    </div>
                                                                    <div class="distributor-hidden-inputs">
                                                                        @foreach ($existingDistributorItems as $d)
                                                                            <input type="hidden" class="distributor-hidden-item" value="{{ $d }}">
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="detail-items">
                                                    @foreach($detailIdxList as $detailNo => $srcIndex)
                                                        @php
                                                            $seq = $globalDetailSeq;
                                                            $globalDetailSeq++;
                                                            $existingCoa = $file_coas[$srcIndex] ?? null;
                                                            $imgPath = $image_kemasans[$srcIndex] ?? null;
                                                        @endphp
                                                        <div class="detail-item mb-3 p-3 border rounded" data-detail-index="{{ $seq }}" style="background-color: #ffffff;">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="fw-bold detail-title">Detail #{{ $detailNo + 1 }}</span>
                                                                <button type="button" class="btn btn-danger btn-sm remove-detail-btn" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                                                            </div>

                                                            <input type="hidden" name="kategori_code[]" class="kategori-code-hidden" value="{{ $existingKategori }}">
                                                            <input type="hidden" name="id_produk[]" class="produk-id-hidden" value="{{ $produkId }}">
                                                            <div class="produsen-hidden-inputs"></div>
                                                            <div class="distributor-hidden-inputs"></div>

                                                            {{-- FIX: hidden Kondisi Fisik SELALU ter-submit (punya name). Radio hanya UI. --}}
                                                            <input type="hidden" name="penampakan[]" class="penampakan-hidden" value="{{ $penampakans[$srcIndex] ?? '' }}">
                                                            <input type="hidden" name="sealing[]" class="sealing-hidden" value="{{ $sealings[$srcIndex] ?? '' }}">
                                                            <input type="hidden" name="cetakan[]" class="cetakan-hidden" value="{{ $cetakans[$srcIndex] ?? '' }}">
                                                            <input type="hidden" name="logo_halal[]" class="logo_halal-hidden" value="{{ $logo_halals[$srcIndex] ?? '' }}">
                                                            <input type="hidden" name="dokumen_halal[]" class="dokumen-halal-hidden" value="{{ $dokumen_halals[$srcIndex] ?? '' }}">
                                                            <input type="hidden" name="coa[]" class="coa-hidden" value="{{ $coas[$srcIndex] ?? '' }}">

                                                            {{-- File tersimpan milik detail ini (dipakai controller agar file tidak tertukar saat urutan berubah) --}}
                                                            <input type="hidden" name="existing_image_kemasan[]" class="existing-image-hidden" value="{{ $imgPath }}">
                                                            <input type="hidden" name="existing_file_coa[]" class="existing-coa-hidden" value="{{ $existingCoa }}">

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Kode Produksi</label>
                                                                        <input type="text" class="form-control" name="kode_produksi[]" value="{{ $kode_produksis[$srcIndex] ?? '' }}" placeholder="Kode Produksi">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Jumlah Datang</label>
                                                                        <div class="input-group" style="max-width: 100%;">
                                                                            <input type="text" class="form-control" name="jumlah_datang[]" value="{{ $jumlah_datangs[$srcIndex] ?? '' }}" placeholder="Jumlah" min="0" step="any">
                                                                            <select class="form-select" name="unit_datang[]" style="max-width: 120px;">
                                                                                <option value="">Pilih Parameter</option>
                                                                                @foreach(\App\Models\PemeriksaanKedatanganKemasan::unitParameters() as $key => $label)
                                                                                    <option value="{{ $key }}" {{ ($unit_datangs[$srcIndex] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Jumlah Sampling</label>
                                                                        <div class="input-group" style="max-width: 100%;">
                                                                            <input type="text" class="form-control" name="jumlah_sampling[]" value="{{ $jumlah_samplings[$srcIndex] ?? '' }}" placeholder="Jumlah" min="0" step="any">
                                                                            <select class="form-select" name="unit_sampling[]" style="max-width: 120px;">
                                                                                <option value="">Pilih Parameter</option>
                                                                                @foreach(\App\Models\PemeriksaanKedatanganKemasan::unitParameters() as $key => $label)
                                                                                    <option value="{{ $key }}" {{ ($unit_samplings[$srcIndex] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
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
                                                                        <textarea class="form-control" name="spesifikasi[]" rows="2" placeholder="Spesifikasi">{{ $spesifikasis[$srcIndex] ?? '' }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-section mb-3 mt-3">
                                                                <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                                                                <div class="row">
                                                                    {{-- FIX: nama radio unik per detail (UI-only), TIDAK pernah di-rename oleh JS --}}
                                                                    @foreach ($kondisiFisikSrc as $grp => [$grpLabel, $grpArr])
                                                                        <div class="col-md-3">
                                                                            <div class="mb-3">
                                                                                <label class="form-label"><strong>{{ $grpLabel }}</strong></label>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="radio" name="{{ $grp }}_master_{{ $seq }}" data-group="{{ $grp }}" value="1" {{ (string)($grpArr[$srcIndex] ?? '') === '1' ? 'checked' : '' }}>
                                                                                    <label class="form-check-label">Ya ✓</label>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="radio" name="{{ $grp }}_master_{{ $seq }}" data-group="{{ $grp }}" value="0" {{ (string)($grpArr[$srcIndex] ?? '') === '0' ? 'checked' : '' }}>
                                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>

                                                            <div class="form-section mb-3">
                                                                <h6 class="text-primary mb-2">Detail Tambahan</h6>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Ketebalan (Micron)</label>
                                                                            <input type="number" step="0.01" class="form-control" name="ketebalan_micron[]" value="{{ $ketebalan_microns[$srcIndex] ?? '' }}" placeholder="Ketebalan">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Dimensi</label>
                                                                            <input type="text" class="form-control" name="dimensi[]" value="{{ $dimensis[$srcIndex] ?? '' }}" placeholder="Dimensi">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Berat</label>
                                                                            <input type="text" class="form-control" name="berat[]" value="{{ $berats[$srcIndex] ?? '' }}" placeholder="Berat (cth: 25 kg / 500 gr)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Status</label>
                                                                            <select class="form-control" name="status[]">
                                                                                <option value="">Pilih Status</option>
                                                                                @foreach (['Hold', 'Release', 'Retur'] as $st)
                                                                                    <option value="{{ $st }}" {{ ($statuses[$srcIndex] ?? '') == $st ? 'selected' : '' }}>{{ $st }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Keterangan</label>
                                                                            <textarea class="form-control" name="keterangan[]" rows="2" placeholder="Keterangan tambahan">{{ $keterangans[$srcIndex] ?? '' }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-section mb-3 coa-upload-section">
                                                                <h6 class="text-primary mb-2">Upload COA</h6>
                                                                @if($existingCoa)
                                                                    <div class="mb-2 existing-file-box">
                                                                        <span class="badge bg-success">COA Tersimpan</span>
                                                                        <a href="{{ asset('storage/' . $existingCoa) }}" target="_blank" class="btn btn-sm btn-outline-info ms-2">Lihat / Download</a>
                                                                    </div>
                                                                @endif
                                                                <div class="row mb-2">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label d-block">Tipe File</label>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input coa-type-pdf" type="radio" name="coa_type_edit{{ $seq }}" value="pdf" checked>
                                                                                <label class="form-check-label">PDF</label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input coa-type-img" type="radio" name="coa_type_edit{{ $seq }}" value="gambar">
                                                                                <label class="form-check-label">Gambar</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row coa-pdf-input">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Ganti COA (PDF)</label>
                                                                            <input type="file" name="file_coa[]" class="form-control" accept="application/pdf">
                                                                            <small class="form-text text-muted">Format: PDF. Maksimal 3MB.</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row coa-img-input" style="display:none;">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Ganti COA (Gambar)</label>
                                                                            <input type="file" name="file_coa_img[]" class="form-control" accept="image/*" capture="environment">
                                                                            <small class="form-text text-muted">Format: JPG, PNG, WEBP, GIF. Maksimal 3MB.</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-section mb-3">
                                                                <h6 class="text-primary mb-2">Upload Gambar</h6>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        @if($imgPath)
                                                                            <div class="mb-2 existing-file-box">
                                                                                <img src="{{ asset('storage/' . $imgPath) }}" alt="Gambar Kemasan" style="max-width: 220px; height: auto; border: 1px solid #ddd; padding: 4px; background: #fff;">
                                                                            </div>
                                                                        @endif
                                                                        <div class="form-group">
                                                                            <label class="form-label">Ganti Gambar</label>
                                                                            <input type="file" name="image_kemasan[]" class="form-control image-kemasan-input" accept="image/*" capture="camera">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <!-- Dokumen (Master per Bahan) -->
                                                <div class="form-section mb-3">
                                                    <h6 class="text-primary mb-2">Dokumen</h6>
                                                    <input type="hidden" class="doc-master-dokumen" value="{{ $docDokumen }}">
                                                    <input type="hidden" class="doc-master-coa" value="{{ $docCoa }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="dokumen_halal_master_{{ $loop->index }}" value="1" {{ (string)$docDokumen === '1' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="dokumen_halal_master_{{ $loop->index }}" value="0" {{ (string)$docDokumen === '0' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>COA</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="coa_master_{{ $loop->index }}" value="1" {{ (string)$docCoa === '1' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="coa_master_{{ $loop->index }}" value="0" {{ (string)$docCoa === '0' ? 'checked' : '' }}>
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

                                                <div class="row mt-3 pt-3 border-top">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Produk</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="row mt-3 pt-3 border-top">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-success btn-sm add-unified-btn"><i class="bi bi-plus"></i> Tambah Produk</button>
                                        </div>
                                    </div>

                                    <div class="col-md-12 d-flex justify-content-end mt-3">
                                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Update Data</button>
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
    .badge-removable { display: inline-flex; align-items: center; gap: 4px; padding-right: 4px; }
    .badge-remove-btn { background: none; border: none; padding: 1px 4px; font-size: 12px; font-weight: 600; line-height: 1; cursor: pointer; color: #dc3545; border-radius: 3px; }
    .badge-remove-btn:hover { color: #fff; background: #dc3545; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const choicesInstances = new WeakMap();
    const produkByKategori = @json($produkByKategori ?? []);
    const produkMeta = @json($produkMeta ?? []);
    const oldKategoriCodes = @json(old('kategori_code', []));
    const oldProdukIds = @json(old('id_produk', []));

    // Grup Kondisi Fisik (radio UI-only -> hidden bernama yang selalu ter-submit)
    const KONDISI_FISIK_GROUPS = ['penampakan', 'sealing', 'cetakan', 'logo_halal'];

    // Daftar master (untuk membedakan badge master vs badge dari meta produk)
    const supplierMasters = {
        produsen: @json(($produsens ?? collect())->pluck('nama_produsen')->map(fn ($v) => (string) $v)->values()),
        distributor: @json(($distributors ?? collect())->pluck('nama_distributor')->map(fn ($v) => (string) $v)->values()),
    };

    // Lock agar event yang dipicu kode kita sendiri tidak memicu sinkronisasi ulang
    window.__supplierSyncLock = false;
    const withSupplierLock = (fn) => {
        window.__supplierSyncLock = true;
        try { fn(); } finally {
            setTimeout(() => { window.__supplierSyncLock = false; }, 0);
        }
    };

    const CHOICES_OPTIONS = {
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
    };

    const SIMPLE_CHOICES_OPTIONS = {
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
    };

    // ---------- Choices helpers ----------
    function initSupplierChoices(select) {
        if (!select || typeof window.Choices === 'undefined') return;
        if (choicesInstances.has(select)) return;
        const instance = new Choices(select, CHOICES_OPTIONS);
        choicesInstances.set(select, instance);
        select.dataset.choicesInitialized = 'true';
    }

    function initGenericChoices(selectEl, options) {
        if (!selectEl || typeof window.Choices === 'undefined') return;
        if (choicesInstances.get(selectEl)) return;
        const instance = new Choices(selectEl, options || SIMPLE_CHOICES_OPTIONS);
        choicesInstances.set(selectEl, instance);
    }

    function initProdukChoices(selectEl) {
        if (!selectEl || typeof window.Choices === 'undefined') return;
        if (selectEl.choicesInstance) {
            try { selectEl.choicesInstance.destroy(); } catch (e) {}
            selectEl.choicesInstance = null;
        }
        try {
            selectEl.choicesInstance = new Choices(selectEl, SIMPLE_CHOICES_OPTIONS);
        } catch (e) {}
    }

    // ---------- Collapse helpers ----------
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
            rowEl.dataset.produkCollapseId = uniqueDomId('produk_e');
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
            btn.className = 'btn btn-primary btn-sm d-inline-flex align-items-center gap-2 collapse-toggle-btn';
            btn.setAttribute('data-bs-toggle', 'collapse');
            btn.setAttribute('data-bs-target', `#${collapseId}`);
            btn.setAttribute('aria-expanded', 'true');
            btn.setAttribute('aria-controls', collapseId);

            const span = document.createElement('span');
            span.className = 'text-white mb-0 bahan-title produk-collapse-label';
            span.textContent = existingText || `Bahan #${rowIdx + 1}`;

            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down collapse-chevron';

            btn.appendChild(span);
            btn.appendChild(icon);
            titleEl.appendChild(btn);
        }

        let body = rowEl.querySelector(':scope > .produk-collapse.collapse');
        if (body) {
            body.id = collapseId;
        } else {
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

        const hasDuplicateId = (id) => {
            if (!id) return true;
            const el = document.getElementById(id);
            if (!el) return false;
            return !detailEl.contains(el);
        };

        if (hasDuplicateId(collapseId)) {
            collapseId = uniqueDomId('detail_e');
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
            icon.className = 'bi bi-chevron-down collapse-chevron';

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
                icon.className = 'bi bi-chevron-down collapse-chevron';
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
        labelEl.textContent = selectedText ? `${rowIdx + 1}. ${selectedText}` : `Bahan #${rowIdx + 1}`;
    };

    const updateDetailLabel = (detailEl) => {
        if (!detailEl) return;
        const labelEl = detailEl.querySelector('.detail-collapse-label');
        if (!labelEl) return;
        const kodeInp = detailEl.querySelector('input[name="kode_produksi[]"]');
        const kodeVal = kodeInp ? String(kodeInp.value || '').trim() : '';
        labelEl.textContent = kodeVal || 'Kode Produksi';
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

    const initKemasanCollapses = () => {
        const rows = Array.from(document.querySelectorAll('#unified-container .unified-row'));
        rows.forEach((rowEl, rowIdx) => {
            ensureProdukCollapsible(rowEl, rowIdx);
            updateProdukLabel(rowEl, rowIdx);
            rowEl.querySelectorAll('.detail-item').forEach((d) => {
                ensureDetailCollapsible(d);
                updateDetailLabel(d);
            });
        });
    };

    function updateSectionLabels() {
        const rows = Array.from(document.querySelectorAll('#unified-container .unified-row'));
        rows.forEach((rowEl, rowIdx) => {
            if (rowEl.querySelector('.produk-collapse-label')) {
                updateProdukLabel(rowEl, rowIdx);
            } else {
                const title = rowEl.querySelector('.bahan-title');
                if (title) title.textContent = `Bahan #${rowIdx + 1}`;
            }
        });
    }

    function updateDetailIndices() {
        const allDetails = Array.from(document.querySelectorAll('#unified-container .detail-item'));
        allDetails.forEach((detailEl, idx) => {
            detailEl.dataset.detailIndex = String(idx);

            const setArrayName = (el, base) => {
                if (!el) return;
                el.name = `${base}[]`;
            };

            setArrayName(detailEl.querySelector('input[name^="kode_produksi"]'), 'kode_produksi');
            setArrayName(detailEl.querySelector('input[name^="jumlah_datang"]'), 'jumlah_datang');
            setArrayName(detailEl.querySelector('select[name^="unit_datang"]'), 'unit_datang');
            setArrayName(detailEl.querySelector('input[name^="jumlah_sampling"]'), 'jumlah_sampling');
            setArrayName(detailEl.querySelector('select[name^="unit_sampling"]'), 'unit_sampling');
            setArrayName(detailEl.querySelector('textarea[name^="spesifikasi"]'), 'spesifikasi');
            setArrayName(detailEl.querySelector('input[name^="ketebalan_micron"]'), 'ketebalan_micron');
            setArrayName(detailEl.querySelector('input[name^="dimensi"]'), 'dimensi');
            setArrayName(detailEl.querySelector('input[name^="berat"]'), 'berat');
            setArrayName(detailEl.querySelector('select[name^="status"]'), 'status');
            setArrayName(detailEl.querySelector('textarea[name^="keterangan"]'), 'keterangan');
            setArrayName(detailEl.querySelector('input[type="file"][name^="image_kemasan"]'), 'image_kemasan');

            // FIX: Kondisi Fisik dibawa oleh hidden input bernama x[idx] yang SELALU ter-submit.
            // Radio-nya hanya UI (nama unik per detail, TIDAK di-rename di sini),
            // sehingga pilihan yang sudah dicentang tidak hilang & array tidak bergeser.
            KONDISI_FISIK_GROUPS.forEach((g) => {
                const h = detailEl.querySelector(`input.${g}-hidden`);
                if (h) h.name = `${g}[${idx}]`;
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

        document.querySelectorAll('#unified-container .unified-row').forEach((rowEl) => {
            const details = rowEl.querySelectorAll('.detail-item');
            details.forEach((d, di) => {
                if (d.querySelector('.detail-collapse-label')) {
                    updateDetailLabel(d);
                } else {
                    const title = d.querySelector('.detail-title');
                    if (title) title.textContent = `Detail #${di + 1}`;
                }
                const rm = d.querySelector('.remove-detail-btn');
                if (rm) rm.style.display = details.length > 1 ? '' : 'none';
            });
        });

        updateSectionLabels();
    }

    function syncHeaderToDetails(rowEl) {
        const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
        const produkSelect = rowEl.querySelector('select.produk-select');
        const produsenTemplate = rowEl.querySelectorAll('.produsen-hidden-inputs .produsen-hidden-item');
        const distributorTemplate = rowEl.querySelectorAll('.distributor-hidden-inputs .distributor-hidden-item');

        const kategoriVal = kategoriSelect ? String(kategoriSelect.value || '') : '';
        const produkVal = produkSelect ? String(produkSelect.value || '') : '';

        rowEl.querySelectorAll('.detail-item').forEach((detailEl) => {
            const kategoriHidden = detailEl.querySelector('input.kategori-code-hidden');
            const produkHidden = detailEl.querySelector('input.produk-id-hidden');
            if (kategoriHidden) kategoriHidden.value = kategoriVal;
            if (produkHidden) produkHidden.value = produkVal;

            const prodWrap = detailEl.querySelector('.produsen-hidden-inputs');
            const distWrap = detailEl.querySelector('.distributor-hidden-inputs');
            if (prodWrap) {
                prodWrap.innerHTML = '';
                produsenTemplate.forEach((t) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.value = String(t.value || '');
                    prodWrap.appendChild(input);
                });
            }
            if (distWrap) {
                distWrap.innerHTML = '';
                distributorTemplate.forEach((t) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.value = String(t.value || '');
                    distWrap.appendChild(input);
                });
            }
        });
        updateDetailIndices();
    }

    function syncDokumenToDetails(rowEl) {
        const getCheckedVal = (namePrefix) => {
            const inp = rowEl.querySelector(`input[type="radio"][name^="${namePrefix}_master_"]:checked`);
            return inp ? String(inp.value) : '';
        };

        const docVal = getCheckedVal('dokumen_halal');
        const coaVal = getCheckedVal('coa');

        rowEl.querySelectorAll('.detail-item').forEach((detailEl) => {
            const d = detailEl.querySelector('input.dokumen-halal-hidden');
            const c = detailEl.querySelector('input.coa-hidden');
            if (d) d.value = docVal;
            if (c) c.value = coaVal;
        });
    }

    // ---------- Supplier (Produsen / Distributor) ----------
    const getSupplierInstance = (select) =>
        choicesInstances.get(select) || select._choices || select._choicesInstance || null;

    const getBadgeValues = (rowEl, type) =>
        Array.from(rowEl.querySelectorAll(`.${type}-badges .badge-removable[data-value]`))
            .map((badge) => String(badge.dataset.value || '').trim())
            .filter(Boolean);

    // Render badge + hidden input (tidak menyentuh kotak select)
    function renderSupplierBadges(rowEl, type, values) {
        const badgesEl = rowEl.querySelector(`.${type}-badges`);
        const hiddenEl = rowEl.querySelector(`.${type}-hidden-inputs`);
        if (!badgesEl || !hiddenEl) return;

        const unique = [...new Set((values || []).map((v) => String(v).trim()).filter(Boolean))];
        badgesEl.innerHTML = '';
        hiddenEl.innerHTML = '';

        if (unique.length === 0) {
            badgesEl.innerHTML = '<span class="text-muted small badge-empty">-</span>';
            return;
        }

        unique.forEach((v) => {
            const badge = document.createElement('span');
            badge.className = `badge ${type === 'produsen' ? 'bg-light-primary text-primary' : 'bg-light-info text-info'} badge-removable`;
            badge.dataset.value = v;
            badge.appendChild(document.createTextNode(`${v} `));

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'badge-remove-btn edit-supplier-remove';
            removeButton.dataset.type = type;
            removeButton.title = `Hapus ${type} ini dari form`;
            removeButton.textContent = 'Hapus';
            badge.appendChild(removeButton);
            badgesEl.appendChild(badge);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.className = `${type}-hidden-item`;
            input.value = v;
            hiddenEl.appendChild(input);
        });
    }

    // Set isi kotak select multiple (tanpa memicu sinkronisasi balik)
    function setSupplierSelectValues(rowEl, type, values) {
        const select = rowEl.querySelector(`select.${type}-master-select`);
        if (!select) return;
        const normalized = [...new Set((values || []).map((v) => String(v).trim()).filter(Boolean))];
        const instance = getSupplierInstance(select);

        withSupplierLock(() => {
            if (instance) {
                instance.removeActiveItems();
                normalized.forEach((v) => {
                    try { instance.setChoiceByValue(v); } catch (e) { /* abaikan */ }
                });
            } else {
                Array.from(select.options).forEach((option) => {
                    option.selected = normalized.includes(String(option.value));
                });
            }
        });
    }

    // Hapus satu nilai dari kotak select multiple
    function removeSupplierValueFromSelect(rowEl, type, value) {
        const select = rowEl.querySelector(`select.${type}-master-select`);
        if (!select) return;
        value = String(value);

        withSupplierLock(() => {
            const instance = getSupplierInstance(select);
            if (instance && typeof instance.removeActiveItemsByValue === 'function') {
                try { instance.removeActiveItemsByValue(value); } catch (e) { /* abaikan */ }
            }

            Array.from(select.options).forEach((opt) => {
                if (String(opt.value) === value) {
                    opt.selected = false;
                    opt.removeAttribute('selected');
                }
            });

            // Fallback: jika item masih tampil di kotak, klik tombol "x"-nya
            const wrapper = select.closest('.choices');
            if (wrapper) {
                wrapper.querySelectorAll('.choices__list--multiple .choices__item').forEach((item) => {
                    if (String(item.dataset.value) === value) {
                        const btn = item.querySelector('.choices__button');
                        if (btn) btn.click();
                    }
                });
            }
        });
    }

    // Sinkronkan badge dari isi kotak select (kotak = sumber kebenaran)
    function syncSupplierFromSelect(select) {
        if (window.__supplierSyncLock) return;
        const rowEl = select ? select.closest('.unified-row') : null;
        const type = select ? select.dataset.supplierType : null;
        if (!rowEl || !type) return;

        const selected = Array.from(select.selectedOptions)
            .map((o) => String(o.value).trim())
            .filter(Boolean);

        // Badge yang bukan bagian master (mis. dari meta produk) tetap dipertahankan
        const masterSet = new Set(supplierMasters[type] || []);
        const keptExtra = getBadgeValues(rowEl, type).filter((v) => !masterSet.has(v));

        renderSupplierBadges(rowEl, type, [...selected, ...keptExtra]);
        syncHeaderToDetails(rowEl);
    }

    // Isi badge + kotak select dari data produk
    function applyBadges(rowEl, prodVals, distVals) {
        renderSupplierBadges(rowEl, 'produsen', prodVals);
        renderSupplierBadges(rowEl, 'distributor', distVals);
        setSupplierSelectValues(rowEl, 'produsen', prodVals);
        setSupplierSelectValues(rowEl, 'distributor', distVals);
        syncHeaderToDetails(rowEl);
    }

    function applyProdukMetaForRow(rowEl, forceOverride) {
        const produkSelect = rowEl.querySelector('select.produk-select');
        if (!produkSelect) return;
        if (!forceOverride) {
            const produsenBadgesEl = rowEl.querySelector('.produsen-badges');
            if (produsenBadgesEl && produsenBadgesEl.querySelector('.badge')) {
                return;
            }
        }
        const produkId = (produkSelect.value || '').toString();
        const meta = produkMeta ? produkMeta[produkId] : null;
        if (!meta) return;

        const normalizeMulti = (v) => {
            if (Array.isArray(v)) return v.map((x) => String(x));
            if (v === null || v === undefined) return [];
            const s = String(v);
            return s ? [s] : [];
        };
        applyBadges(rowEl, normalizeMulti(meta.produsen), normalizeMulti(meta.distributor));
    }

    function populateProdukOptionsForRow(rowEl, forceOverride) {
        const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
        const produkSelect = rowEl.querySelector('select.produk-select');
        if (!kategoriSelect || !produkSelect) return;

        const kategori = (kategoriSelect.value || '').toString();
        const raw = produkByKategori ? produkByKategori[kategori] : null;
        const items = Array.isArray(raw) ? raw : (raw ? Object.values(raw) : []);

        const desiredProdukId = (kategoriSelect.dataset && kategoriSelect.dataset.desiredProduk)
            ? String(kategoriSelect.dataset.desiredProduk)
            : ((produkSelect.dataset && produkSelect.dataset.desiredValue)
                ? String(produkSelect.dataset.desiredValue)
                : ((oldProdukIds && oldProdukIds.length) ? String(oldProdukIds[rowEl.dataset.rowIndex] || '') : ''));

        produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
        items.forEach((p) => {
            const opt = document.createElement('option');
            opt.value = String(p.id);
            opt.textContent = String(p.nama);
            if (desiredProdukId && String(p.id) === String(desiredProdukId)) {
                opt.selected = true;
            }
            produkSelect.appendChild(opt);
        });

        initProdukChoices(produkSelect);
        setTimeout(() => {
            applyProdukMetaForRow(rowEl, forceOverride);
            const idx = Array.from(document.querySelectorAll('#unified-container .unified-row')).indexOf(rowEl);
            updateProdukLabel(rowEl, idx >= 0 ? idx : 0);
        }, 0);

        syncHeaderToDetails(rowEl);
    }

    function updateDeleteButtons() {
        const rows = document.querySelectorAll('#unified-container .unified-row');
        document.querySelectorAll('#unified-container .remove-unified-btn').forEach((button) => {
            button.style.display = rows.length > 1 ? 'inline-block' : 'none';
        });
    }

    // ---------- Event delegasi ----------
    // Event bawaan Choices.js (addItem / removeItem) -> sinkronkan setelah store ter-update
    ['addItem', 'removeItem'].forEach((evtName) => {
        document.addEventListener(evtName, function(e) {
            const t = e.target;
            if (t && t.matches && t.matches('select.supplier-master-select')) {
                setTimeout(() => syncSupplierFromSelect(t), 0);
            }
        });
    });

    document.addEventListener('change', function(e) {
        const target = e.target;

        if (target && target.matches('select.supplier-master-select')) {
            syncSupplierFromSelect(target);
            return;
        }

        if (target && target.matches('select.kategori-produk-select')) {
            const row = target.closest('.unified-row');
            if (row && target.dataset) {
                target.dataset.desiredProduk = '';
                populateProdukOptionsForRow(row, true);
            }
        }

        if (target && target.matches('select.produk-select')) {
            const row = target.closest('.unified-row');
            if (row) {
                const kategoriSelect = row.querySelector('select.kategori-produk-select');
                if (kategoriSelect && kategoriSelect.dataset) {
                    kategoriSelect.dataset.desiredProduk = (target.value || '').toString();
                }
                applyProdukMetaForRow(row, true);
                const idx = Array.from(document.querySelectorAll('#unified-container .unified-row')).indexOf(row);
                updateProdukLabel(row, idx >= 0 ? idx : 0);
            }
        }

        if (target && target.matches('input[type="radio"][name^="dokumen_halal_master_"], input[type="radio"][name^="coa_master_"]')) {
            const row = target.closest('.unified-row');
            if (row) syncDokumenToDetails(row);
        }

        // Kondisi Fisik: salin nilai radio (UI) ke hidden input bernama milik detail yang sama
        if (target && target.matches('input[type="radio"][data-group]')) {
            const field = target.dataset.group;
            const detail = target.closest('.detail-item');
            if (detail && KONDISI_FISIK_GROUPS.includes(field)) {
                const hidden = detail.querySelector(`input.${field}-hidden`);
                if (hidden) hidden.value = String(target.value);
            }
        }
    });

    // Tombol "Hapus" pada badge Produsen/Distributor
    document.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.edit-supplier-remove');
        if (!removeButton) return;

        const badge = removeButton.closest('.badge-removable');
        const row = removeButton.closest('.unified-row');
        const type = removeButton.dataset.type;
        if (!badge || !row || !type) return;

        const value = String(badge.dataset.value || '');

        // 1. Hapus dari kotak select multiple
        removeSupplierValueFromSelect(row, type, value);

        // 2. Render ulang badge dari yang tersisa
        const remaining = getBadgeValues(row, type).filter((v) => v !== value);
        renderSupplierBadges(row, type, remaining);
        syncHeaderToDetails(row);
    });

    document.addEventListener('input', function(e) {
        const target = e.target;
        if (target && target.matches('input[name="kode_produksi[]"]')) {
            const detailEl = target.closest('.detail-item');
            if (detailEl) updateDetailLabel(detailEl);
        }
    });

    // ---------- Inisialisasi baris yang sudah ada ----------
    initKemasanCollapses();

    // Init Choices untuk select produsen/distributor (nilai terpilih dari PHP langsung tampil)
    document.querySelectorAll('select.js-supplier-choices').forEach(initSupplierChoices);

    document.querySelectorAll('#unified-container .unified-row').forEach((row, idx) => {
        row.dataset.rowIndex = String(idx);
        const kategoriSelect = row.querySelector('select.kategori-produk-select');
        const produkSelect = row.querySelector('select.produk-select');
        const firstIdxAttr = row.getAttribute('data-first-index');
        const firstIdx = firstIdxAttr !== null ? parseInt(firstIdxAttr) : idx;

        const oldProdukId = row.getAttribute('data-old-produk-id');
        if (oldProdukId && produkSelect) {
            produkSelect.dataset.desiredValue = String(oldProdukId);
        }

        if (kategoriSelect) {
            const desiredKategori = (oldKategoriCodes && oldKategoriCodes[firstIdx]) ? String(oldKategoriCodes[firstIdx]) : '';
            if (desiredKategori && !kategoriSelect.value) {
                kategoriSelect.value = desiredKategori;
            }
            initGenericChoices(kategoriSelect);
        }

        if (produkSelect) {
            const desiredProduk = (oldProdukIds && oldProdukIds[firstIdx]) ? String(oldProdukIds[firstIdx]) : '';
            if (desiredProduk) {
                produkSelect.dataset.desiredValue = desiredProduk;
            }
        }

        if (kategoriSelect && kategoriSelect.value) {
            populateProdukOptionsForRow(row);
        } else if (produkSelect && (produkSelect.value || produkSelect.dataset.desiredValue)) {
            applyProdukMetaForRow(row);
        }

        syncHeaderToDetails(row);
        syncDokumenToDetails(row);
    });

    // ---------- Tambah / hapus detail ----------
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
            clone.querySelectorAll('input.penampakan-hidden, input.sealing-hidden, input.cetakan-hidden, input.logo_halal-hidden, input.dokumen-halal-hidden, input.coa-hidden, input.existing-image-hidden, input.existing-coa-hidden').forEach((h) => {
                h.value = '';
            });

            // Buang preview file/gambar tersimpan dari detail yang disalin
            clone.querySelectorAll('.existing-file-box').forEach((box) => box.remove());

            // Reset tampilan tipe COA ke PDF
            const coaPdf = clone.querySelector('.coa-pdf-input');
            const coaImg = clone.querySelector('.coa-img-input');
            if (coaPdf) coaPdf.style.display = '';
            if (coaImg) coaImg.style.display = 'none';

            // FIX: beri nama radio yang UNIK sebelum klon masuk DOM,
            // agar tidak satu grup dengan detail lain (yang akan mengosongkan pilihan sebelumnya).
            const uid = uniqueDomId('d');
            clone.querySelectorAll('input[type="radio"]').forEach((r) => {
                if (r.dataset.group) {
                    r.name = `${r.dataset.group}_master_new_${uid}`;
                } else if (r.classList.contains('coa-type-pdf') || r.classList.contains('coa-type-img')) {
                    r.name = `coa_type_new_${uid}`;
                }
            });
            const pdfRadio = clone.querySelector('.coa-type-pdf');
            if (pdfRadio) pdfRadio.checked = true;

            container.appendChild(clone);

            ensureDetailCollapsible(clone);
            collapseOtherDetailsInRow(rowEl, clone);

            syncHeaderToDetails(rowEl);
            syncDokumenToDetails(rowEl);
            updateDetailIndices();
            return;
        }

        const rmBtn = e.target.closest('.remove-detail-btn');
        if (rmBtn) {
            const detailEl = rmBtn.closest('.detail-item');
            const rowEl = rmBtn.closest('.unified-row');
            if (!detailEl || !rowEl) return;
            const details = rowEl.querySelectorAll('.detail-item');
            if (details.length <= 1) return;
            detailEl.remove();
            syncHeaderToDetails(rowEl);
            syncDokumenToDetails(rowEl);
            updateDetailIndices();
        }
    });

    // ---------- Hapus produk ----------
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-unified-btn');
        if (!btn || btn.disabled) return;
        const row = btn.closest('.unified-row');
        if (row) {
            row.remove();
            updateDeleteButtons();
            updateDetailIndices();
        }
    });

    // ---------- Tambah produk ----------
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.add-unified-btn')) return;

        const container = document.getElementById('unified-container');
        const rowIndex = container.querySelectorAll('.unified-row').length;
        const uid = `${Date.now()}_${rowIndex}`;

        const newRow = document.createElement('div');
        newRow.className = 'unified-row mb-4 p-3 border rounded';
        newRow.style.backgroundColor = '#f8f9fa';
        newRow.dataset.rowIndex = String(rowIndex);

        const unitOptions = `
            <option value="">Pilih Parameter</option>
            @foreach(\App\Models\PemeriksaanKedatanganKemasan::unitParameters() as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        `;

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
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="fw-semibold">Produsen</div>
                                <div class="small text-muted mb-2">Pilih dari seluruh master Produsen Plant ini</div>
                                <select class="form-select js-supplier-choices supplier-master-select produsen-master-select mb-2" multiple data-supplier-type="produsen" aria-label="Pilih produsen">
                                    @foreach (($produsens ?? collect()) as $produsen)
                                        <option value="{{ $produsen->nama_produsen }}">{{ $produsen->nama_produsen }}</option>
                                    @endforeach
                                </select>
                                <div class="produsen-badges d-flex flex-wrap gap-1"><span class="text-muted small badge-empty">-</span></div>
                                <div class="produsen-hidden-inputs"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="fw-semibold">Distributor</div>
                                <div class="small text-muted mb-2">Pilih dari seluruh master Distributor Plant ini</div>
                                <select class="form-select js-supplier-choices supplier-master-select distributor-master-select mb-2" multiple data-supplier-type="distributor" aria-label="Pilih distributor">
                                    @foreach (($distributors ?? collect()) as $distributor)
                                        <option value="{{ $distributor->nama_distributor }}">{{ $distributor->nama_distributor }}</option>
                                    @endforeach
                                </select>
                                <div class="distributor-badges d-flex flex-wrap gap-1"><span class="text-muted small badge-empty">-</span></div>
                                <div class="distributor-hidden-inputs"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-items">
                <div class="detail-item mb-3 p-3 border rounded" data-detail-index="0" style="background-color:#ffffff;">
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
                    <input type="hidden" name="logo_halal[]" class="logo_halal-hidden" value="">
                    <input type="hidden" name="dokumen_halal[]" class="dokumen-halal-hidden" value="">
                    <input type="hidden" name="coa[]" class="coa-hidden" value="">
                    <input type="hidden" name="existing_image_kemasan[]" class="existing-image-hidden" value="">
                    <input type="hidden" name="existing_file_coa[]" class="existing-coa-hidden" value="">

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
                                <div class="input-group" style="max-width:100%;">
                                    <input type="text" class="form-control" name="jumlah_datang[]" placeholder="Jumlah" min="0" step="any">
                                    <select class="form-select" name="unit_datang[]" style="max-width:120px;">${unitOptions}</select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Jumlah Sampling</label>
                                <div class="input-group" style="max-width:100%;">
                                    <input type="text" class="form-control" name="jumlah_sampling[]" placeholder="Jumlah" min="0" step="any">
                                    <select class="form-select" name="unit_sampling[]" style="max-width:120px;">${unitOptions}</select>
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
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Penampakan</strong></label>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="penampakan_master_new_${uid}" data-group="penampakan" value="1"><label class="form-check-label">Ya ✓</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="penampakan_master_new_${uid}" data-group="penampakan" value="0"><label class="form-check-label">Tidak ✗</label></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Sealing</strong></label>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="sealing_master_new_${uid}" data-group="sealing" value="1"><label class="form-check-label">Ya ✓</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="sealing_master_new_${uid}" data-group="sealing" value="0"><label class="form-check-label">Tidak ✗</label></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Cetakan</strong></label>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="cetakan_master_new_${uid}" data-group="cetakan" value="1"><label class="form-check-label">Ya ✓</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="cetakan_master_new_${uid}" data-group="cetakan" value="0"><label class="form-check-label">Tidak ✗</label></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Logo Halal</strong></label>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="logo_halal_master_new_${uid}" data-group="logo_halal" value="1"><label class="form-check-label">Ya ✓</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="logo_halal_master_new_${uid}" data-group="logo_halal" value="0"><label class="form-check-label">Tidak ✗</label></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section mb-3">
                        <h6 class="text-primary mb-2">Detail Tambahan</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Ketebalan (Micron)</label>
                                    <input type="number" step="0.01" class="form-control" name="ketebalan_micron[]" placeholder="Ketebalan">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Dimensi</label>
                                    <input type="text" class="form-control" name="dimensi[]" placeholder="Dimensi">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Berat</label>
                                    <input type="text" class="form-control" name="berat[]" placeholder="Berat (cth: 25 kg / 500 gr)">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status[]">
                                        <option value="">Pilih Status</option>
                                        <option value="Hold">Hold</option>
                                        <option value="Release">Release</option>
                                        <option value="Retur">Retur</option>
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

                    <div class="form-section mb-3 coa-upload-section">
                        <h6 class="text-primary mb-2">Upload COA</h6>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label d-block">Tipe File</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input coa-type-pdf" type="radio" name="coa_type_new_${uid}" value="pdf" checked>
                                        <label class="form-check-label">PDF</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input coa-type-img" type="radio" name="coa_type_new_${uid}" value="gambar">
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
                                    <label class="form-label">Foto COA (Gambar)</label>
                                    <input type="file" name="file_coa_img[]" class="form-control" accept="image/*" capture="environment">
                                    <small class="form-text text-muted">Format: JPG, PNG, WEBP, GIF. Maksimal 3MB.</small>
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
                <input type="hidden" class="doc-master-dokumen" value="">
                <input type="hidden" class="doc-master-coa" value="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>Dokumen Halal</strong></label>
                            <div class="form-check"><input class="form-check-input" type="radio" name="dokumen_halal_master_new_${uid}" value="1"><label class="form-check-label">Ya ✓</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="dokumen_halal_master_new_${uid}" value="0"><label class="form-check-label">Tidak ✗</label></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>COA</strong></label>
                            <div class="form-check"><input class="form-check-input" type="radio" name="coa_master_new_${uid}" value="1"><label class="form-check-label">Ya ✓</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="coa_master_new_${uid}" value="0"><label class="form-check-label">Tidak ✗</label></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary btn-sm add-detail-btn"><i class="bi bi-plus"></i> Tambah Detail</button>
                </div>
            </div>

            <div class="row mt-3 pt-3 border-top">
                <div class="col-md-12">
                    <button type="button" class="btn btn-danger btn-sm remove-unified-btn"><i class="bi bi-trash"></i> Hapus Produk</button>
                </div>
            </div>
        `;

        container.appendChild(newRow);

        // Init Choices untuk select di baris baru (produk-select diinit saat opsinya diisi)
        newRow.querySelectorAll('select.js-supplier-choices').forEach(initSupplierChoices);
        newRow.querySelectorAll('select.choices').forEach((select) => {
            if (select.classList.contains('produk-select')) return;
            initGenericChoices(select, CHOICES_OPTIONS);
        });

        // Event kategori & produk (Choices.js kadang menelan event delegasi)
        const kategoriSel = newRow.querySelector('select.kategori-produk-select');
        const produkSel = newRow.querySelector('select.produk-select');
        if (kategoriSel) {
            kategoriSel.addEventListener('addItem', function() {
                setTimeout(() => populateProdukOptionsForRow(newRow, true), 0);
            });
        }
        if (produkSel) {
            produkSel.addEventListener('addItem', function() {
                applyProdukMetaForRow(newRow, true);
            });
        }

        initKemasanCollapses();
        collapseAllProdukExcept(newRow);
        updateDeleteButtons();
        updateDetailIndices();
        syncHeaderToDetails(newRow);
        syncDokumenToDetails(newRow);
    });

    // Jika belum ada baris sama sekali, otomatis buat satu baris kosong
    if (document.querySelectorAll('#unified-container .unified-row').length === 0) {
        const addBtn = document.querySelector('.add-unified-btn');
        if (addBtn) addBtn.click();
    }

    updateDeleteButtons();
    updateDetailIndices();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
        let width = img.width;
        let height = img.height;
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

        const newName = (file.name || 'image').replace(/\.[^/.]+$/, '') + '.jpg';
        return new File([blob], newName, { type: 'image/jpeg', lastModified: Date.now() });
    }

    async function handleImageInputChange(input) {
        const file = input.files && input.files[0] ? input.files[0] : null;
        if (!file) return;

        // Baca file ke memori agar tidak kena ERR_UPLOAD_FILE_CHANGED di Android
        let fileToProcess = file;
        try {
            const arrayBuffer = await file.arrayBuffer();
            fileToProcess = new File([arrayBuffer], file.name, { type: file.type, lastModified: Date.now() });
        } catch (e) {
            fileToProcess = file;
        }

        if (fileToProcess.size <= MAX_SIZE) {
            try {
                const dt = new DataTransfer();
                dt.items.add(fileToProcess);
                input.files = dt.files;
            } catch (e) { /* ignore */ }
            return;
        }

        try {
            const compressedFile = await compressImage(fileToProcess);
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
        if (input && input.classList && (input.classList.contains('image-kemasan-input') || input.name === 'file_coa_img[]')) {
            const old = input.parentNode.querySelector('.compressing-label');
            if (old) old.remove();

            const lbl = document.createElement('small');
            lbl.className = 'text-warning d-block form-text compressing-label';
            lbl.innerText = 'Mengompres gambar...';
            input.parentNode.appendChild(lbl);
            handleImageInputChange(input).then(() => {
                lbl.innerText = 'Selesai ✓';
            });
        }
    });

    // Pengecekan ukuran file (Frontend) max 3MB
    document.addEventListener('change', function(e) {
        if (e.target.type === 'file') {
            const file = e.target.files[0];
            if (file) {
                const maxSize = 3 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('Ukuran file ' + file.name + ' terlalu besar (' + (file.size / 1024 / 1024).toFixed(2) + ' MB). Maksimal ukuran file yang diizinkan adalah 3 MB.');
                    e.target.value = '';
                }
            }
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

    // Pengaman terakhir saat submit: sinkronkan radio Kondisi Fisik -> hidden bernama (per detail)
    const editFormEl = document.getElementById('form-edit-kemasan');
    if (editFormEl) {
        editFormEl.addEventListener('submit', function() {
            document.querySelectorAll('#unified-container .detail-item').forEach((detail) => {
                ['penampakan', 'sealing', 'cetakan', 'logo_halal'].forEach((field) => {
                    const checked = detail.querySelector('input[type="radio"][data-group="' + field + '"]:checked');
                    const hidden = detail.querySelector('input.' + field + '-hidden');
                    if (hidden) {
                        hidden.value = checked ? String(checked.value) : '';
                    }
                });
            });
        });
    }
});
</script>
@endsection