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
                    <h3>Edit Pemeriksaan Loading Produk</h3>
                    <p class="text-subtitle text-muted">Edit data pemeriksaan loading produk</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-loading-produk.index') }}">Pemeriksaan Loading Produk</a></li>
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
                            <h4 class="card-title">Form Edit Pemeriksaan Loading Produk</h4>
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

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-loading-produk.update', $pemeriksaanLoading->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <!-- INFORMASI DASAR -->
                                        <h5 class="text-primary mb-3">Informasi Dasar</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                    <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                                        name="tanggal" value="{{ old('tanggal', $pemeriksaanLoading->tanggal->format('Y-m-d')) }}" required>
                                                    @error('tanggal')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_shift">Shift</label>
                                                    <select id="id_shift" class="form-select @error('id_shift') is-invalid @enderror" name="id_shift">
                                                        <option value="">-- Pilih Shift --</option>
                                                        @foreach($shifts as $shift)
                                                            <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanLoading->id_shift) == $shift->id ? 'selected' : '' }}>
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
                                                    <label for="id_kendaraan">Jenis & No Kendaraan</label>
                                                    <select id="id_kendaraan" class="form-select @error('id_kendaraan') is-invalid @enderror" name="id_kendaraan">
                                                        <option value="">-- Pilih Kendaraan --</option>
                                                        <option value="other" {{ old('id_kendaraan', $pemeriksaanLoading->jenis_kendaraan_manual ? 'other' : '') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                        @foreach($kendaraans as $kendaraan)
                                                            <option value="{{ $kendaraan->id }}" {{ old('id_kendaraan', $pemeriksaanLoading->id_kendaraan) == $kendaraan->id ? 'selected' : '' }}>{{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->no_kendaraan }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_kendaraan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror

                                                    <!-- Input manual yang awalnya disembunyikan -->
                                                    <div id="manual_kendaraan_input" class="mt-2" style="display: {{ old('id_kendaraan', $pemeriksaanLoading->jenis_kendaraan_manual ? 'other' : '') == 'other' ? 'block' : 'none' }};">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="jenis_kendaraan_manual">Jenis Kendaraan</label>
                                                                    <input type="text" id="jenis_kendaraan_manual" class="form-control @error('jenis_kendaraan_manual') is-invalid @enderror" 
                                                                        name="jenis_kendaraan_manual" value="{{ old('jenis_kendaraan_manual', $pemeriksaanLoading->jenis_kendaraan_manual) }}" placeholder="Masukkan jenis kendaraan">
                                                                    @error('jenis_kendaraan_manual')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="no_kendaraan_manual">No Kendaraan</label>
                                                                    <input type="text" id="no_kendaraan_manual" class="form-control @error('no_kendaraan_manual') is-invalid @enderror" 
                                                                        name="no_kendaraan_manual" value="{{ old('no_kendaraan_manual', $pemeriksaanLoading->no_kendaraan_manual) }}" placeholder="Masukkan nomor kendaraan">
                                                                    @error('no_kendaraan_manual')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_supir">Nama Supir</label>
                                                    <select id="id_supir" class="form-control @error('id_supir') is-invalid @enderror" name="id_supir">
                                                        <option value="">Pilih Supir</option>
                                                        <option valuex="other" {{ old('id_supir', $pemeriksaanLoading->nama_supir_manual ? 'other' : '') == 'other' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                                                        @foreach($supirs as $supir)
                                                            <option value="{{ $supir->id }}" {{ old('id_supir', $pemeriksaanLoading->id_supir) == $supir->id ? 'selected' : '' }}>{{ $supir->nama_supir }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_supir')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror

                                                    <div id="manual_supir_input" class="mt-2" style="display: {{ old('id_supir', $pemeriksaanLoading->nama_supir_manual ? 'other' : '') == 'other' ? 'block' : 'none' }};">
                                                        <div class="form-group">
                                                            <label for="nama_supir_manual">Nama Supir</label>
                                                            <input type="text" id="nama_supir_manual" class="form-control @error('nama_supir_manual') is-invalid @enderror"
                                                                name="nama_supir_manual" value="{{ old('nama_supir_manual', $pemeriksaanLoading->nama_supir_manual) }}" placeholder="Masukkan nama supir">
                                                            @error('nama_supir_manual')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- WAKTU LOADING -->
                                        <h5 class="text-primary mb-3 mt-4">Waktu Loading</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="star_loading">Mulai Loading</label>
                                                    <input type="time" id="star_loading" class="form-control @error('star_loading') is-invalid @enderror"
                                                        name="star_loading" value="{{ old('star_loading', $pemeriksaanLoading->star_loading ? (is_string($pemeriksaanLoading->star_loading) ? substr($pemeriksaanLoading->star_loading, 0, 5) : $pemeriksaanLoading->star_loading->format('H:i')) : '') }}">
                                                    @error('star_loading')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="selesai_loading">Selesai Loading</label>
                                                    <input type="time" id="selesai_loading" class="form-control @error('selesai_loading') is-invalid @enderror"
                                                        name="selesai_loading" value="{{ old('selesai_loading', $pemeriksaanLoading->selesai_loading ? (is_string($pemeriksaanLoading->selesai_loading) ? substr($pemeriksaanLoading->selesai_loading, 0, 5) : $pemeriksaanLoading->selesai_loading->format('H:i')) : '') }}">
                                                    @error('selesai_loading')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const kontainerBersih = document.getElementById('kontainer_bersih');
                                                const kontainerTidakBocor = document.getElementById('kontainer_tidak_bocor');
                                                const kontainerTidakBerbau = document.getElementById('kontainer_tidak_berbau');
                                                const keteranganField = document.getElementById('keterangan_kondisi_kontainer');

                                                function checkKondisi() {
                                                    if (!kontainerBersih.checked || !kontainerTidakBocor.checked || !kontainerTidakBerbau.checked) {
                                                        keteranganField.setAttribute('required', 'required');
                                                        keteranganField.parentElement.querySelector('label').innerHTML = 'Keterangan Kondisi Kontainer <span class="text-danger">*</span>';
                                                    } else {
                                                        keteranganField.removeAttribute('required');
                                                        keteranganField.parentElement.querySelector('label').innerHTML = 'Keterangan Kondisi Kontainer';
                                                    }
                                                }

                                                kontainerBersih.addEventListener('change', checkKondisi);
                                                kontainerTidakBocor.addEventListener('change', checkKondisi);
                                                kontainerTidakBerbau.addEventListener('change', checkKondisi);

                                                checkKondisi();
                                            });
                                        </script>

                                        <!-- TEMPERATURE -->
                                        <h5 class="text-primary mb-3 mt-4">Temperature</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="temperature_mobil">Temperature Mobil (°C)</label>
                                                    <input type="text" id="temperature_mobil" class="form-control @error('temperature_mobil') is-invalid @enderror"
                                                        name="temperature_mobil" value="{{ old('temperature_mobil', $pemeriksaanLoading->temperature_mobil) }}" placeholder="Contoh: -18">
                                                    @error('temperature_mobil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kondisi_produk">Kondisi Produk</label>
                                                    <select id="kondisi_produk" class="form-select @error('kondisi_produk') is-invalid @enderror" name="kondisi_produk">
                                                        <option value="">-- Pilih Kondisi --</option>
                                                        <option value="Frozen" {{ old('kondisi_produk', $pemeriksaanLoading->kondisi_produk) == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                        <option value="Fresh" {{ old('kondisi_produk', $pemeriksaanLoading->kondisi_produk) == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                        <option value="Dry" {{ old('kondisi_produk', $pemeriksaanLoading->kondisi_produk) == 'Dry' ? 'selected' : '' }}>Dry</option>
                                                    </select>
                                                    @error('kondisi_produk')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Temperature Produk (Multiple) (°C)</label>
                                                <div id="temperature-fields">
                                                    @php
                                                        $tempProduk = old('temperature_produk', $pemeriksaanLoading->temperature_produk ?? []);
                                                        if (is_string($tempProduk)) $tempProduk = json_decode($tempProduk, true) ?? [];
                                                        if (empty($tempProduk)) $tempProduk = [''];
                                                    @endphp
                                                    @foreach($tempProduk as $i => $tempVal)
                                                        <div class="row mb-2 temp-row">
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control" name="temperature_produk[]" value="{{ $tempVal }}" placeholder="Contoh: -18">
                                                            </div>
                                                            <div class="col-md-2">
                                                                @if($i === 0)
                                                                    <button type="button" class="btn btn-success w-100" id="add-temp"><i class="bi bi-plus"></i></button>
                                                                @else
                                                                    <button type="button" class="btn btn-danger w-100 remove-temp"><i class="bi bi-trash"></i></button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SEGEL & INFORMASI PRODUK -->
                                        @php
                                            // segel_gembok disimpan sebagai boolean di DB:
                                            // true = segel, false = gembok
                                            // Konversi balik untuk keperluan tampilan
                                            $segelGembokValue = old('segel_gembok',
                                                $pemeriksaanLoading->segel_gembok === true  ? 'segel' :
                                                ($pemeriksaanLoading->segel_gembok === false && $pemeriksaanLoading->getOriginal('segel_gembok') !== null ? 'gembok' : null)
                                            );
                                        @endphp
                                        <h5 class="text-primary mb-3 mt-4">Segel & Informasi Produk</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>Segel/Gembok</strong></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="segel_option" name="segel_gembok" value="segel" {{ $segelGembokValue == 'segel' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="segel_option">Segel</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="gembok_option" name="segel_gembok" value="gembok" {{ $segelGembokValue == 'gembok' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="gembok_option">Gembok</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="no_segel_container" style="display: {{ $segelGembokValue == 'segel' ? 'block' : 'none' }};">
                                                <div class="form-group">
                                                    <label for="no_segel">No. Segel</label>
                                                    <input type="text" id="no_segel" class="form-control @error('no_segel') is-invalid @enderror"
                                                        name="no_segel" value="{{ old('no_segel', $pemeriksaanLoading->no_segel) }}" placeholder="Nomor Segel">
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

                                        <!-- DATA PRODUK -->
                                        <h5 class="text-primary mb-3 mt-4">Data Produk <span class="text-danger">*</span></h5>
                                        <div id="produk-groups">
                                            @php
                                                $rows = is_array($pemeriksaanLoading->produk_data) ? $pemeriksaanLoading->produk_data : [];
                                                $rows = array_values($rows);
                                                if (count($rows) === 0) {
                                                    $rows = [[]];
                                                }

                                                $groups = collect($rows)->groupBy(function ($row) {
                                                    return $row['id_produk'] ?? '';
                                                })->values();

                                                $globalIndex = 0;
                                            @endphp

                                            @foreach($groups as $groupIndex => $detailRows)
                                                @php
                                                    $firstRow = $detailRows->first() ?? [];
                                                    $groupProdukId = $firstRow['id_produk'] ?? null;
                                                    $groupKategori = $groupProdukId ? ($produkKategoriById[$groupProdukId] ?? '') : '';
                                                @endphp
                                                <div class="produk-group mb-4 p-3 border rounded" style="background-color: #ffffff;" data-group-index="{{ $groupIndex }}">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="text-secondary mb-0">Produk #{{ $groupIndex + 1 }}</h6>
                                                        <button type="button" class="btn btn-sm btn-outline-danger remove-produk-group" style="display:{{ $groups->count() > 1 ? 'inline-block' : 'none' }};">Hapus Produk</button>
                                                    </div>

                                                    {{-- Customer & Tujuan per produk --}}
                                                    @php
                                                        $savedTujuanId = $firstRow['id_tujuan_pengiriman'] ?? null;
                                                    @endphp
                                                    <div class="row mb-3 produk-tujuan-section">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Customer & Tujuan Pengiriman</label>
                                                                <select class="form-select produk-tujuan-select" name="produk_data[{{ $groupIndex }}][id_tujuan_pengiriman]">
                                                                    <option value="">-- Pilih Tujuan --</option>
                                                                    <option value="other" {{ $savedTujuanId == 'other' ? 'selected' : '' }}>✏️ Lainnya (Input Manual)</option>
                                                                    @foreach($tujuanPengirimans as $tujuan)
                                                                        @php
                                                                            $nc = $tujuan->customer->nama_cust ?? null;
                                                                            $nt = $tujuan->nama_tujuan ?? null;
                                                                            if ($nc && $nt && $nt !== '-') $lbl = $nc . ' - ' . $nt;
                                                                            elseif ($nc) $lbl = $nc;
                                                                            elseif ($nt && $nt !== '-') $lbl = $nt;
                                                                            else $lbl = 'Tujuan #' . $tujuan->id;
                                                                        @endphp
                                                                        <option value="{{ $tujuan->id }}" {{ $savedTujuanId == $tujuan->id ? 'selected' : '' }}>{{ $lbl }}</option>
                                                                    @endforeach
                                                                    @if(!empty($customersWithoutTujuan) && $customersWithoutTujuan->count() > 0)
                                                                        @foreach($customersWithoutTujuan as $cust)
                                                                            <option value="customer_{{ $cust->id }}" {{ $savedTujuanId == ('customer_'.$cust->id) ? 'selected' : '' }}>{{ $cust->nama_cust }} (belum ada tujuan)</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <div class="produk-tujuan-manual mt-2" style="display:none;">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control produk-customer-manual" name="produk_data[{{ $groupIndex }}][nama_customer_manual]" placeholder="Nama Customer">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control produk-tujuan-manual-input" name="produk_data[{{ $groupIndex }}][nama_tujuan_manual]" placeholder="Nama Tujuan Pengiriman">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Kategori <span class="text-danger">*</span></label>
                                                                <select class="choices form-select kategori-produk-select" {{ $groupIndex === 0 ? 'name=kategori_code required' : '' }} data-kategori="{{ $groupKategori }}">
                                                                    <option value="">-- Pilih Kategori --</option>
                                                                    @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                        <option value="{{ $kategori }}" {{ $groupKategori == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nama Produk <span class="text-danger">*</span></label>
                                                                <select class="form-select produk-select" {{ $groupIndex === 0 ? 'name=id_produk required' : '' }} data-selected="{{ $groupProdukId }}">
                                                                    <option value="">-- Pilih Produk --</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h6 class="text-secondary mt-3">Detail Produk</h6>
                                                    <div class="produk-container">
                                                        @foreach($detailRows as $rowInGroupIndex => $row)
                                                            @php
                                                                $flatIndex = $globalIndex;
                                                                $globalIndex++;
                                                            @endphp
                                                            <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <h6 class="text-secondary mb-0">Detail #{{ $rowInGroupIndex + 1 }}</h6>
                                                                    <button type="button" class="btn btn-danger btn-sm remove-detail" style="display:{{ $detailRows->count() > 1 ? 'inline-block' : 'none' }};"><i class="bi bi-trash"></i> Hapus Detail</button>
                                                                </div>
                                                                <input type="hidden" class="produk-id-hidden" name="produk_data[{{ $flatIndex }}][id_produk]" value="{{ $row['id_produk'] ?? $groupProdukId }}">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <label>Kode Produksi</label>
                                                                        <input type="text" class="form-control" name="produk_data[{{ $flatIndex }}][kode_produksi]" value="{{ old('produk_data.'.$flatIndex.'.kode_produksi', $row['kode_produksi'] ?? '') }}" placeholder="Kode Produksi">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label>Best Before</label>
                                                                        <input type="date" class="form-control" name="produk_data[{{ $flatIndex }}][best_before]" value="{{ old('produk_data.'.$flatIndex.'.best_before', $row['best_before'] ?? '') }}">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label>Jumlah Kemasan</label>
                                                                        <input type="text" class="form-control" name="produk_data[{{ $flatIndex }}][jumlah_kemasan]" value="{{ old('produk_data.'.$flatIndex.'.jumlah_kemasan', $row['jumlah_kemasan'] ?? '') }}" placeholder="Contoh: 100 Karton">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label>Jumlah Sampling</label>
                                                                        <input type="text" class="form-control" name="produk_data[{{ $flatIndex }}][jumlah_sampling]" value="{{ old('produk_data.'.$flatIndex.'.jumlah_sampling', $row['jumlah_sampling'] ?? '') }}" placeholder="Contoh: 10 Karton">
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3">
                                                                    <div class="col-md-3">
                                                                        <label>Berat per Karung atau Box</label>
                                                                        <input type="text" class="form-control" name="produk_data[{{ $flatIndex }}][berat_perkarung]" value="{{ old('produk_data.'.$flatIndex.'.berat_perkarung', $row['berat_perkarung'] ?? '') }}" placeholder="Contoh: 25 Kg">
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3">
                                                                    <div class="col-md-12">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" name="produk_data[{{ $flatIndex }}][kondisi_kemasan]" value="1" {{ old('produk_data.'.$flatIndex.'.kondisi_kemasan', ($row['kondisi_kemasan'] ?? true) ? 1 : 0) ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Kondisi Kemasan Baik</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3">
                                                                    <div class="col-md-12">
                                                                        <label>Keterangan</label>
                                                                        <textarea class="form-control" name="produk_data[{{ $flatIndex }}][keterangan]" rows="2" placeholder="Keterangan tambahan">{{ old('produk_data.'.$flatIndex.'.keterangan', $row['keterangan'] ?? '') }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" class="btn btn-primary btn-sm mt-2 add-detail"><i class="bi bi-plus"></i> Tambah Detail</button>

                                                    <div class="row mt-3 pt-3 border-top">
                                                        <div class="col-md-12">
                                                            <button type="button" class="btn btn-danger btn-sm remove-produk-group" style="display:{{ $groups->count() > 1 ? 'inline-block' : 'none' }};"><i class="bi bi-trash"></i> Hapus Produk</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm mt-2 mb-4" id="add-produk-group"><i class="bi bi-plus"></i> Tambah Produk</button>

                                        <!-- HASIL PEMERIKSAAN -->
                                        <!-- <h5 class="text-primary mb-3 mt-4">Hasil Pemeriksaan</h5>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="hasil_pemeriksaan">Hasil Pemeriksaan</label>
                                                    <textarea class="form-control @error('hasil_pemeriksaan') is-invalid @enderror" 
                                                        id="hasil_pemeriksaan" name="hasil_pemeriksaan" rows="4" 
                                                        placeholder="Hasil pemeriksaan loading produk">{{ old('hasil_pemeriksaan', $pemeriksaanLoading->hasil_pemeriksaan) }}</textarea>
                                                    @error('hasil_pemeriksaan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div> -->

                                        <!-- BUTTONS -->
                                        <div class="col-sm-12 d-flex justify-content-end mt-4">
                                            <a href="{{ route('pemeriksaan-loading-produk.index') }}" class="btn btn-light-secondary me-2 mb-1">Batal</a>
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Update Data</button>
                                        </div>
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

{{-- ===== MODAL KONFIRMASI CUSTOMER ===== --}}
<div class="modal fade" id="modalSamaTujuan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="bi bi-people me-2"></i>Customer & Tujuan Pengiriman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-question-circle text-warning" style="font-size:3rem;"></i>
                </div>
                <h6 class="fw-bold mb-2">Apakah Customer & Tujuan Pengiriman sama dengan produk sebelumnya?</h6>
                <p class="text-muted small mb-0" id="modal-tujuan-prev-label">—</p>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-success" id="modal-tujuan-ya">
                    <i class="bi bi-check-circle me-1"></i> Ya, Sama
                </button>
                <button type="button" class="btn btn-outline-secondary" id="modal-tujuan-tidak">
                    <i class="bi bi-x-circle me-1"></i> Tidak, Berbeda
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.collapse-chevron, .detail-chevron {
    transition: transform 0.2s ease-in-out;
}
.collapse-chevron.rotated, .detail-chevron.rotated {
    transform: rotate(-90deg);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const me = this;
    const tujuanPengirimanOptions = [
        { value: '', label: '-- Pilih Tujuan --' },
        { value: 'other', label: '✏️ Lainnya (Input Manual)' },
        @foreach($tujuanPengirimans as $tujuan)
            @php
                $nc = $tujuan->customer->nama_cust ?? null;
                $nt = $tujuan->nama_tujuan ?? null;
                if ($nc && $nt && $nt !== '-') $lbl = $nc . ' - ' . $nt;
                elseif ($nc) $lbl = $nc;
                elseif ($nt && $nt !== '-') $lbl = $nt;
                else $lbl = 'Tujuan #' . $tujuan->id;
            @endphp
            { value: '{{ $tujuan->id }}', label: {!! json_encode($lbl) !!} },
        @endforeach
        @if(!empty($customersWithoutTujuan) && $customersWithoutTujuan->count() > 0)
            @foreach($customersWithoutTujuan as $cust)
                { value: 'customer_{{ $cust->id }}', label: {!! json_encode($cust->nama_cust . ' (belum ada tujuan)') !!} },
            @endforeach
        @endif
    ];

    function bsCollapse(el) {
        if (!el || typeof bootstrap === 'undefined') return null;
        return bootstrap.Collapse.getInstance(el) || new bootstrap.Collapse(el, { toggle: false });
    }

    function updateProdukLabel(groupEl, groupIdx) {
        const span = groupEl ? groupEl.querySelector('.produk-collapse-label') : null;
        if (!span) return;

        const produkSelect = groupEl.querySelector('select.produk-select');
        let selectedName = '';
        if (produkSelect && produkSelect.value) {
            const opt = produkSelect.options[produkSelect.selectedIndex];
            if (opt && opt.text && !opt.text.includes('-- Pilih')) {
                selectedName = opt.text.trim();
            }
        }
        span.textContent = selectedName ? `Produk #${groupIdx + 1}: ${selectedName}` : `Produk #${groupIdx + 1}`;
    }

    function updateDetailLabel(rowEl, detailIdxWithinGroup) {
        const span = rowEl ? rowEl.querySelector('.detail-collapse-label') : null;
        if (!span) return;

        const inputKode = rowEl.querySelector('input[name*="[kode_produksi]"]');
        const val = inputKode ? (inputKode.value || '').trim() : '';
        span.textContent = val ? `Detail #${detailIdxWithinGroup + 1}: ${val}` : `Detail #${detailIdxWithinGroup + 1}`;
    }

    function collapseAllProdukExcept(activeGroupEl) {
        document.querySelectorAll('#produk-groups .produk-group').forEach((g) => {
            const body = g.querySelector(':scope > .produk-collapse.collapse');
            const icon = g.querySelector('.collapse-chevron');
            if (!body) return;

            const inst = bsCollapse(body);
            if (g === activeGroupEl) {
                if (inst) inst.show();
                else body.classList.add('show');
                if (icon) icon.classList.remove('rotated');
            } else {
                if (inst) inst.hide();
                else body.classList.remove('show');
                if (icon) icon.classList.add('rotated');
            }
        });
    }

    function collapseOtherDetailsInGroup(groupEl, activeRowEl) {
        if (!groupEl) return;
        groupEl.querySelectorAll('.produk-container .produk-row').forEach((r) => {
            const body = r.querySelector(':scope > .detail-collapse.collapse');
            const icon = r.querySelector('.detail-chevron');
            if (!body) return;

            const inst = bsCollapse(body);
            if (r === activeRowEl) {
                if (inst) inst.show();
                else body.classList.add('show');
                if (icon) icon.classList.remove('rotated');
            } else {
                if (inst) inst.hide();
                else body.classList.remove('show');
                if (icon) icon.classList.add('rotated');
            }
        });
    }

    function ensureDetailCollapsible(rowEl) {
        if (!rowEl) return;
        const collapseId = rowEl.id || `detail-collapse-${Math.random().toString(36).substring(2, 9)}`;
        rowEl.id = collapseId;

        const header = rowEl.querySelector(':scope > .d-flex');
        if (!header) return;

        const titleEl = header.querySelector('h6');
        if (titleEl && !titleEl.querySelector('.detail-toggle-btn')) {
            const existingText = (titleEl.textContent || '').trim();
            titleEl.textContent = '';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-primary btn-sm d-flex align-items-center gap-2 detail-toggle-btn';

            const span = document.createElement('span');
            span.className = 'detail-collapse-label';
            span.textContent = existingText || 'Detail';

            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down detail-chevron';

            btn.appendChild(span);
            btn.appendChild(icon);
            titleEl.appendChild(btn);
        }

        const groupEl = rowEl.closest('.produk-group');
        const idxInGroup = groupEl ? Array.from(groupEl.querySelectorAll('.produk-container .produk-row')).indexOf(rowEl) : 0;
        updateDetailLabel(rowEl, idxInGroup >= 0 ? idxInGroup : 0);

        let body = rowEl.querySelector(`:scope > .detail-collapse.collapse`);
        if (!body) {
            body = document.createElement('div');
            body.className = 'detail-collapse collapse show';

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

        const icon = header.querySelector('.detail-chevron');
        const inst = bsCollapse(body);
        if (inst && icon && !body.dataset.collapseEventsBound) {
            body.dataset.collapseEventsBound = '1';
            body.addEventListener('shown.bs.collapse', function() {
                icon.classList.remove('rotated');
            });
            body.addEventListener('hidden.bs.collapse', function() {
                icon.classList.add('rotated');
            });
        }

        const toggleBtn = header.querySelector('.detail-toggle-btn');
        if (toggleBtn && !toggleBtn.dataset.toggleBound) {
            toggleBtn.dataset.toggleBound = '1';
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const isShown = body.classList.contains('show');
                if (isShown) {
                    if (inst) inst.hide();
                    else body.classList.remove('show');
                } else {
                    collapseOtherDetailsInGroup(groupEl, rowEl);
                    if (inst) inst.show();
                    else body.classList.add('show');
                }
            });
        }
    }

    function ensureProdukCollapsible(groupEl, groupIdx) {
        if (!groupEl) return;
        const collapseId = groupEl.id || `produk-collapse-${groupIdx}-${Math.random().toString(36).substring(2, 7)}`;
        groupEl.id = collapseId;

        const header = groupEl.querySelector(':scope > .d-flex');
        if (!header) return;

        const titleEl = header.querySelector('h6');
        if (titleEl && !titleEl.querySelector('.collapse-toggle-btn')) {
            const existingText = (titleEl.textContent || '').trim();
            titleEl.textContent = '';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-primary btn-sm d-flex align-items-center gap-2 collapse-toggle-btn';

            const span = document.createElement('span');
            span.className = 'produk-collapse-label';
            span.textContent = existingText || `Produk #${groupIdx + 1}`;

            const icon = document.createElement('i');
            icon.className = 'bi bi-chevron-down collapse-chevron';

            btn.appendChild(span);
            btn.appendChild(icon);
            titleEl.appendChild(btn);
        }

        updateProdukLabel(groupEl, groupIdx);

        let body = groupEl.querySelector(`:scope > .produk-collapse.collapse`);
        if (!body) {
            body = document.createElement('div');
            body.className = 'produk-collapse collapse show';

            const nodesToMove = [];
            let node = header.nextSibling;
            while (node) {
                const next = node.nextSibling;
                nodesToMove.push(node);
                node = next;
            }
            nodesToMove.forEach((n) => body.appendChild(n));
            groupEl.appendChild(body);
        }

        const icon = header.querySelector('.collapse-chevron');
        const inst = bsCollapse(body);
        if (inst && icon && !body.dataset.collapseEventsBound) {
            body.dataset.collapseEventsBound = '1';
            body.addEventListener('shown.bs.collapse', function() {
                icon.classList.remove('rotated');
            });
            body.addEventListener('hidden.bs.collapse', function() {
                icon.classList.add('rotated');
            });
        }

        const toggleBtn = header.querySelector('.collapse-toggle-btn');
        if (toggleBtn && !toggleBtn.dataset.toggleBound) {
            toggleBtn.dataset.toggleBound = '1';
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const isShown = body.classList.contains('show');
                if (isShown) {
                    if (inst) inst.hide();
                    else body.classList.remove('show');
                } else {
                    collapseAllProdukExcept(groupEl);
                }
            });
        }

        groupEl.querySelectorAll('.produk-container .produk-row').forEach((row) => {
            ensureDetailCollapsible(row);
        });
    }

    function initChoicesSelect(selectEl, placeholderText) {
        if (!selectEl || typeof Choices === 'undefined') return null;
        Array.from(selectEl.options).forEach(function(opt) {
            opt.text = opt.text.trim();
        });
        return new Choices(selectEl, {
            searchResultLimit: 100,
            fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false, distance: 1000 },
            searchEnabled: true,
            searchPlaceholderValue: 'Cari...',
            searchFields: ['label', 'value'],
            itemSelectText: '',
            noResultsText: 'Tidak ada hasil ditemukan',
            noChoicesText: 'Tidak ada pilihan tersedia',
            shouldSort: false,
            placeholder: true,
            placeholderValue: placeholderText || 'Pilih...'
        });
    }

    initChoicesSelect(document.getElementById('id_tujuan_pengiriman'), '-- Pilih Tujuan --');
    initChoicesSelect(document.getElementById('id_kendaraan'), '-- Pilih Kendaraan --');
    initChoicesSelect(document.getElementById('id_supir'), 'Pilih Supir');

    const kendaraanSelect = document.getElementById('id_kendaraan');
    const manualKendaraanDiv = document.getElementById('manual_kendaraan_input');
    if (kendaraanSelect && manualKendaraanDiv) {
        kendaraanSelect.addEventListener('change', function() {
            manualKendaraanDiv.style.display = this.value === 'other' ? 'block' : 'none';
        });
    }

    const supirSelect = document.getElementById('id_supir');
    const manualSupirDiv = document.getElementById('manual_supir_input');
    if (supirSelect && manualSupirDiv) {
        supirSelect.addEventListener('change', function() {
            manualSupirDiv.style.display = this.value === 'other' ? 'block' : 'none';
        });
    }

    document.getElementById('add-temp')?.addEventListener('click', function() {
        const container = document.getElementById('temperature-fields');
        const newField = document.createElement('div');
        newField.className = 'row mb-2 temp-row';
        newField.innerHTML = `
            <div class="col-md-10">
                <input type="text" class="form-control" name="temperature_produk[]" placeholder="Contoh: -18">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 remove-temp"><i class="bi bi-trash"></i></button>
            </div>
        `;
        container.appendChild(newField);
    });
    document.getElementById('temperature-fields')?.addEventListener('click', function(e) {
        if (e.target.closest('.remove-temp')) {
            e.target.closest('.temp-row').remove();
        }
    });

    const produkByKategori = @json($produkByKategori ?? []);
    const choicesInstances = new WeakMap();

    const rebuildProdukChoices = function(produkSelect, choiceItems, desiredValue) {
        if (!produkSelect) return;

        const existing = choicesInstances.get(produkSelect);
        if (existing && typeof existing.destroy === 'function') {
            try { existing.destroy(); } catch (e) {}
            try { choicesInstances.delete(produkSelect); } catch (e) {}
        }

        try {
            const instance = new Choices(produkSelect, {
                searchResultLimit: 100,
                fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false, distance: 1000 },
                searchEnabled: true,
                searchPlaceholderValue: 'Cari...',
                itemSelectText: '',
                noResultsText: 'Tidak ada hasil ditemukan',
                noChoicesText: 'Tidak ada pilihan tersedia',
                shouldSort: false,
                placeholder: true,
                placeholderValue: '-- Pilih Produk --'
            });

            instance.setChoices(choiceItems, 'value', 'label', true);

            if (desiredValue) {
                try {
                    instance.setChoiceByValue(String(desiredValue));
                } catch (e) {}
            }

            choicesInstances.set(produkSelect, instance);
        } catch (e) {}
    };

    const populateProdukOptions = function(groupEl, kategoriCode) {
        const produkSelect = groupEl ? groupEl.querySelector('select.produk-select') : null;
        if (!produkSelect) return;

        const selectedFromAttr = produkSelect.getAttribute('data-selected') || '';
        const rawOptions = (kategoriCode && produkByKategori && produkByKategori[kategoriCode]) ? produkByKategori[kategoriCode] : [];
        const options = Array.isArray(rawOptions) ? rawOptions : Object.values(rawOptions || {});

        const choiceItems = [{ value: '', label: '-- Pilih Produk --', selected: false, disabled: false }];
        options.forEach((p) => {
            choiceItems.push({
                value: String(p.id),
                label: String(p.nama),
                selected: false,
                disabled: false
            });
        });

        rebuildProdukChoices(produkSelect, choiceItems, selectedFromAttr);
        syncGroupHiddenProdukIds(groupEl);
    };

    function updateGroupTitles() {
        const groups = document.querySelectorAll('#produk-groups .produk-group');
        groups.forEach(function(g, idx) {
            updateProdukLabel(g, idx);

            const removeBtn = g.querySelector('.remove-produk-group');
            if (removeBtn) {
                removeBtn.style.display = groups.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    function syncGroupHiddenProdukIds(groupEl) {
        if (!groupEl) return;
        const produkSelect = groupEl.querySelector('select.produk-select');
        const selectedId = produkSelect ? (produkSelect.value || '') : '';

        groupEl.querySelectorAll('.produk-container .produk-row').forEach(function(row) {
            let hiddenInput = row.querySelector('.produk-id-hidden');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.className = 'produk-id-hidden';
                row.prepend(hiddenInput);
            }
            hiddenInput.value = selectedId;
        });
    }

    function reindexAllDetails() {
        let globalIndex = 0;
        document.querySelectorAll('#produk-groups .produk-group').forEach(function(groupEl, groupIdx) {
            const tujuanSelect = groupEl.querySelector('select.produk-tujuan-select');
            const tujuanVal = tujuanSelect ? (tujuanSelect.value || '') : '';
            const customerManualInput = groupEl.querySelector('.produk-customer-manual');
            const tujuanManualInput = groupEl.querySelector('.produk-tujuan-manual-input');
            const custManualVal = customerManualInput ? customerManualInput.value : '';
            const tujManualVal = tujuanManualInput ? tujuanManualInput.value : '';

            groupEl.querySelectorAll('.produk-container .produk-row').forEach(function(row, detailIdxInGroup) {
                row.querySelectorAll('input, select, textarea').forEach(function(field) {
                    const name = field.getAttribute('name');
                    if (name && name.startsWith('produk_data[')) {
                        const newName = name.replace(/^produk_data\[\d+\]/, 'produk_data[' + globalIndex + ']');
                        field.setAttribute('name', newName);
                    }
                });

                let hTujuan = row.querySelector('.row-tujuan-hidden');
                if (!hTujuan) {
                    hTujuan = document.createElement('input');
                    hTujuan.type = 'hidden';
                    hTujuan.className = 'row-tujuan-hidden';
                    row.appendChild(hTujuan);
                }
                hTujuan.setAttribute('name', `produk_data[${globalIndex}][id_tujuan_pengiriman]`);
                hTujuan.value = tujuanVal;

                let hCustMan = row.querySelector('.row-cust-manual-hidden');
                if (!hCustMan) {
                    hCustMan = document.createElement('input');
                    hCustMan.type = 'hidden';
                    hCustMan.className = 'row-cust-manual-hidden';
                    row.appendChild(hCustMan);
                }
                hCustMan.setAttribute('name', `produk_data[${globalIndex}][nama_customer_manual]`);
                hCustMan.value = custManualVal;

                let hTujMan = row.querySelector('.row-tuj-manual-hidden');
                if (!hTujMan) {
                    hTujMan = document.createElement('input');
                    hTujMan.type = 'hidden';
                    hTujMan.className = 'row-tuj-manual-hidden';
                    row.appendChild(hTujMan);
                }
                hTujMan.setAttribute('name', `produk_data[${globalIndex}][nama_tujuan_manual]`);
                hTujMan.value = tujManualVal;

                updateDetailLabel(row, detailIdxInGroup);

                const removeDetailBtn = row.querySelector('.remove-detail');
                if (removeDetailBtn) {
                    removeDetailBtn.style.display = groupEl.querySelectorAll('.produk-container .produk-row').length > 1 ? 'inline-block' : 'none';
                }

                globalIndex++;
            });
        });
    }

    function bindTujuanToggle(groupEl, initialValue) {
        const sel = groupEl.querySelector('select.produk-tujuan-select');
        const div = groupEl.querySelector('.produk-tujuan-manual');
        if (!sel || !div) return;

        if (typeof Choices !== 'undefined' && !sel._choicesInstance && 
            !(sel.dataset && sel.dataset.choicesInitialized === 'true')) {
            try {
                const choicesInst = new Choices(sel, {
                    searchResultLimit: 100,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false, distance: 1000 },
                    searchEnabled: true,
                    searchPlaceholderValue: 'Cari customer...',
                    itemSelectText: '',
                    noResultsText: 'Tidak ada hasil ditemukan',
                    noChoicesText: 'Tidak ada pilihan tersedia',
                    shouldSort: false,
                    placeholder: true,
                    placeholderValue: '-- Pilih Tujuan --'
                });
                sel._choicesInstance = choicesInst;
                if (sel.dataset) sel.dataset.choicesInitialized = 'true';

                if (initialValue) {
                    try { choicesInst.setChoiceByValue(String(initialValue)); } catch(e) {}
                }
            } catch (e) {}
        }

        function checkToggle() {
            div.style.display = sel.value === 'other' ? 'block' : 'none';
            reindexAllDetails();
        }

        sel.addEventListener('change', checkToggle);
        const custManInput = groupEl.querySelector('.produk-customer-manual');
        const tujManInput = groupEl.querySelector('.produk-tujuan-manual-input');
        if (custManInput) custManInput.addEventListener('input', reindexAllDetails);
        if (tujManInput) tujManInput.addEventListener('input', reindexAllDetails);

        checkToggle();
    }

    function bindGroupEvents(groupEl) {
        const kategoriSelect = groupEl.querySelector('select.kategori-produk-select');
        const produkSelect = groupEl.querySelector('select.produk-select');

        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', function() {
                if (produkSelect) {
                    produkSelect.setAttribute('data-selected', '');
                }
                populateProdukOptions(groupEl, this.value);
            });
        }

        if (produkSelect) {
            produkSelect.addEventListener('change', function() {
                syncGroupHiddenProdukIds(groupEl);
                const groupIdx = Array.from(document.querySelectorAll('#produk-groups .produk-group')).indexOf(groupEl);
                updateProdukLabel(groupEl, groupIdx >= 0 ? groupIdx : 0);
            });
        }

        const addDetailBtn = groupEl.querySelector('.add-detail');
        if (addDetailBtn) {
            addDetailBtn.addEventListener('click', function() {
                addDetailRow(groupEl);
            });
        }

        const removeGroupBtn = groupEl.querySelector('.remove-produk-group');
        if (removeGroupBtn) {
            removeGroupBtn.addEventListener('click', function() {
                groupEl.remove();
                updateGroupTitles();
                reindexAllDetails();
            });
        }

        groupEl.addEventListener('click', function(e) {
            if (e.target && e.target.closest('.remove-detail')) {
                const row = e.target.closest('.produk-row');
                if (row) row.remove();
                reindexAllDetails();
            }
        });

        groupEl.addEventListener('input', function(e) {
            if (e.target && e.target.name && e.target.name.includes('[kode_produksi]')) {
                const row = e.target.closest('.produk-row');
                if (row) {
                    const groupIdx = Array.from(groupEl.querySelectorAll('.produk-container .produk-row')).indexOf(row);
                    updateDetailLabel(row, groupIdx >= 0 ? groupIdx : 0);
                }
            }
        });

        if (kategoriSelect) {
            setTimeout(function() {
                const initialKategori = kategoriSelect.getAttribute('data-kategori') || kategoriSelect.value || '';
                if (initialKategori) {
                    if (kategoriSelect._choices && typeof kategoriSelect._choices.setChoiceByValue === 'function') {
                        try { kategoriSelect._choices.setChoiceByValue(initialKategori); } catch(e){}
                    }
                    populateProdukOptions(groupEl, initialKategori);
                }
            }, 0);
        }
        reindexAllDetails();
    }

    function addDetailRow(groupEl) {
        const container = groupEl.querySelector('.produk-container');
        if (!container) return;

        const newRow = document.createElement('div');
        newRow.className = 'produk-row mb-4 p-3 border rounded';
        newRow.style.backgroundColor = '#f8f9fa';
        const tempIndex = 0;
        newRow.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-secondary mb-0">Detail #1</h6>
                <button type="button" class="btn btn-danger btn-sm remove-detail"><i class="bi bi-trash"></i> Hapus Detail</button>
            </div>
            <input type="hidden" class="produk-id-hidden" name="produk_data[${tempIndex}][id_produk]" value="">
            <div class="row">
                <div class="col-md-3">
                    <label>Kode Produksi</label>
                    <input type="text" class="form-control" name="produk_data[${tempIndex}][kode_produksi]" placeholder="Kode Produksi">
                </div>
                <div class="col-md-3">
                    <label>Best Before</label>
                    <input type="date" class="form-control" name="produk_data[${tempIndex}][best_before]">
                </div>
                <div class="col-md-3">
                    <label>Jumlah Kemasan</label>
                    <input type="text" class="form-control" name="produk_data[${tempIndex}][jumlah_kemasan]" placeholder="Contoh: 100 Karton">
                </div>
                <div class="col-md-3">
                    <label>Jumlah Sampling</label>
                    <input type="text" class="form-control" name="produk_data[${tempIndex}][jumlah_sampling]" placeholder="Contoh: 10 Karton">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <label>Berat per Karung atau Box</label>
                    <input type="text" class="form-control" name="produk_data[${tempIndex}][berat_perkarung]" placeholder="Contoh: 25 Kg">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="produk_data[${tempIndex}][kondisi_kemasan]" value="1" checked>
                        <label class="form-check-label">Kondisi Kemasan Baik</label>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label>Keterangan</label>
                    <textarea class="form-control" name="produk_data[${tempIndex}][keterangan]" rows="2" placeholder="Keterangan tambahan"></textarea>
                </div>
            </div>
        `;
        container.appendChild(newRow);

        ensureDetailCollapsible(newRow);
        syncGroupHiddenProdukIds(groupEl);
        reindexAllDetails();
    }

    const existingGroups = document.querySelectorAll('#produk-groups .produk-group');
    existingGroups.forEach(function(g, idx) {
        ensureProdukCollapsible(g, idx);
        const sel = g.querySelector('select.produk-tujuan-select');
        const savedVal = sel ? sel.value : null;
        bindTujuanToggle(g, savedVal);
        bindGroupEvents(g);
    });

    updateGroupTitles();
    if (existingGroups.length > 0) {
        collapseAllProdukExcept(existingGroups[0]);
    }

    document.getElementById('add-produk-group')?.addEventListener('click', function() {
        const groupsWrapper = document.getElementById('produk-groups');
        if (!groupsWrapper) return;

        const currentGroups = document.querySelectorAll('#produk-groups .produk-group');
        const lastGroup = currentGroups[currentGroups.length - 1];
        let prevTujuanValue = '';
        let prevTujuanLabel = '—';

        if (lastGroup) {
            const prevSelect = lastGroup.querySelector('select.produk-tujuan-select');
            if (prevSelect && prevSelect.value) {
                prevTujuanValue = prevSelect.value;
                const selOpt = prevSelect.options[prevSelect.selectedIndex];
                prevTujuanLabel = selOpt ? selOpt.text.trim() : '—';
            }
        }

        const tujuanOptionsHtml = tujuanPengirimanOptions
            .map(opt => `<option value="${opt.value}">${opt.label}</option>`)
            .join('');

        function createNewGroup(copyTujuan) {
            const newGroup = document.createElement('div');
            newGroup.className = 'produk-group mb-4 p-3 border rounded';
            newGroup.style.backgroundColor = '#ffffff';
            newGroup.setAttribute('data-group-index', String(document.querySelectorAll('#produk-groups .produk-group').length));

            newGroup.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-secondary mb-0">Produk</h6>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori <span class="text-danger">*</span></label>
                            <select class="choices form-select kategori-produk-select">
                                <option value="">-- Pilih Kategori --</option>
                                ${Object.keys(produkByKategori || {}).map((k) => `<option value="${String(k)}">${String(k)}</option>`).join('')}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Produk <span class="text-danger">*</span></label>
                            <select class="form-select produk-select" data-selected="">
                                <option value="">-- Pilih Produk --</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row produk-tujuan-section">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Customer & Tujuan Pengiriman</label>
                            <select class="form-select produk-tujuan-select" name="produk_data[0][id_tujuan_pengiriman]">
                                ${tujuanOptionsHtml}
                            </select>
                            <div class="produk-tujuan-manual mt-2" style="display:none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control produk-customer-manual" name="produk_data[0][nama_customer_manual]" placeholder="Nama Customer">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control produk-tujuan-manual-input" name="produk_data[0][nama_tujuan_manual]" placeholder="Nama Tujuan Pengiriman">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="produk-container">
                    <div class="produk-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-secondary mb-0">Detail #1</h6>
                            <button type="button" class="btn btn-danger btn-sm remove-detail" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                        </div>
                        <input type="hidden" class="produk-id-hidden" name="produk_data[0][id_produk]" value="">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Kode Produksi</label>
                                <input type="text" class="form-control" name="produk_data[0][kode_produksi]" placeholder="Kode Produksi">
                            </div>
                            <div class="col-md-3">
                                <label>Best Before</label>
                                <input type="date" class="form-control" name="produk_data[0][best_before]">
                            </div>
                            <div class="col-md-3">
                                <label>Jumlah Kemasan</label>
                                <input type="text" class="form-control" name="produk_data[0][jumlah_kemasan]" placeholder="Contoh: 100 Karton">
                            </div>
                            <div class="col-md-3">
                                <label>Jumlah Sampling</label>
                                <input type="text" class="form-control" name="produk_data[0][jumlah_sampling]" placeholder="Contoh: 10 Karton">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Berat per Karung atau Box</label>
                                <input type="text" class="form-control" name="produk_data[0][berat_perkarung]" placeholder="Contoh: 25 Kg">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="produk_data[0][kondisi_kemasan]" value="1" checked>
                                    <label class="form-check-label">Kondisi Kemasan Baik</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Keterangan</label>
                                <textarea class="form-control" name="produk_data[0][keterangan]" rows="2" placeholder="Keterangan tambahan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm mt-2 add-detail"><i class="bi bi-plus"></i> Tambah Detail</button>

                <div class="row mt-3 pt-3 border-top">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-danger btn-sm remove-produk-group"><i class="bi bi-trash"></i> Hapus Produk</button>
                    </div>
                </div>
            `;

            groupsWrapper.appendChild(newGroup);
            bindTujuanToggle(newGroup, copyTujuan ? prevTujuanValue : null);

            const newIdx = document.querySelectorAll('#produk-groups .produk-group').length - 1;
            ensureProdukCollapsible(newGroup, newIdx);
            updateGroupTitles();
            bindGroupEvents(newGroup);

            const body = newGroup.querySelector(':scope > .produk-collapse.collapse');
            if (body) {
                const inst = bsCollapse(body);
                if (inst) inst.show();
                else body.classList.add('show');
            }
            collapseAllProdukExcept(newGroup);
        }

        if (!prevTujuanValue) {
            createNewGroup(false);
            return;
        }

        const modalLabelEl = document.getElementById('modal-tujuan-prev-label');
        if (modalLabelEl) modalLabelEl.textContent = prevTujuanLabel;

        const modalEl = document.getElementById('modalSamaTujuan');
        if (!modalEl || typeof bootstrap === 'undefined') {
            createNewGroup(false);
            return;
        }

        const modalInst = new bootstrap.Modal(modalEl);
        modalInst.show();

        const btnYa = document.getElementById('modal-tujuan-ya');
        const btnTidak = document.getElementById('modal-tujuan-tidak');

        function cleanup() {
            if (btnYa) btnYa.removeEventListener('click', onYa);
            if (btnTidak) btnTidak.removeEventListener('click', onTidak);
        }
        function onYa() { modalInst.hide(); createNewGroup(true); cleanup(); }
        function onTidak() { modalInst.hide(); createNewGroup(false); cleanup(); }

        if (btnYa) btnYa.addEventListener('click', onYa);
        if (btnTidak) btnTidak.addEventListener('click', onTidak);
    });
});
</script>
@endsection