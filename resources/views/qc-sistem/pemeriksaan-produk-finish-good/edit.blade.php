@extends('layouts.app')

@section('title', 'Edit Pemeriksaan Produk Finish Good')

@section('container')
@php
    $kategoriArr = is_array($pemeriksaanProdukFinishGood->kategori_code_array) ? $pemeriksaanProdukFinishGood->kategori_code_array : [];
    $idProdukArr = is_array($pemeriksaanProdukFinishGood->id_produk_array) ? $pemeriksaanProdukFinishGood->id_produk_array : [];
    $suhuMobilTypeArr = is_array($pemeriksaanProdukFinishGood->suhu_mobil_type_array) ? $pemeriksaanProdukFinishGood->suhu_mobil_type_array : [];
    $suhuMobilValueArr = is_array($pemeriksaanProdukFinishGood->suhu_mobil_value_array) ? $pemeriksaanProdukFinishGood->suhu_mobil_value_array : [];
    $suhuProdukTypeArr = is_array($pemeriksaanProdukFinishGood->suhu_produk_type_array) ? $pemeriksaanProdukFinishGood->suhu_produk_type_array : [];
    $suhuProdukValueArr = is_array($pemeriksaanProdukFinishGood->suhu_produk_value_array) ? $pemeriksaanProdukFinishGood->suhu_produk_value_array : [];
    $kondisiProdukArr = is_array($pemeriksaanProdukFinishGood->kondisi_produk_array) ? $pemeriksaanProdukFinishGood->kondisi_produk_array : [];
    $kondisiProdukSuhuValueArr = is_array($pemeriksaanProdukFinishGood->kondisi_produk_suhu_value_array) ? $pemeriksaanProdukFinishGood->kondisi_produk_suhu_value_array : [];
    $negaraArr = is_array($pemeriksaanProdukFinishGood->negara_produsen_array) ? $pemeriksaanProdukFinishGood->negara_produsen_array : [];
    $kodeArr = is_array($pemeriksaanProdukFinishGood->kode_produksi_array) ? $pemeriksaanProdukFinishGood->kode_produksi_array : [];
    $expireArr = is_array($pemeriksaanProdukFinishGood->expire_date_array) ? $pemeriksaanProdukFinishGood->expire_date_array : [];
    $jmlDatangArr = is_array($pemeriksaanProdukFinishGood->jumlah_datang_array) ? $pemeriksaanProdukFinishGood->jumlah_datang_array : [];
    $jmlSamplingArr = is_array($pemeriksaanProdukFinishGood->jumlah_sampling_array) ? $pemeriksaanProdukFinishGood->jumlah_sampling_array : [];
    $kemasanArr = is_array($pemeriksaanProdukFinishGood->kondisi_kemasan_array) ? $pemeriksaanProdukFinishGood->kondisi_kemasan_array : [];
    $warnaArr = is_array($pemeriksaanProdukFinishGood->kondisi_warna_array) ? $pemeriksaanProdukFinishGood->kondisi_warna_array : [];
    $aromaArr = is_array($pemeriksaanProdukFinishGood->kondisi_aroma_array) ? $pemeriksaanProdukFinishGood->kondisi_aroma_array : [];
    $logoArr = is_array($pemeriksaanProdukFinishGood->logo_halal_array) ? $pemeriksaanProdukFinishGood->logo_halal_array : [];
    $dokArr = is_array($pemeriksaanProdukFinishGood->dokumen_halal_array) ? $pemeriksaanProdukFinishGood->dokumen_halal_array : [];
    $coaArr = is_array($pemeriksaanProdukFinishGood->coa_array) ? $pemeriksaanProdukFinishGood->coa_array : [];
    $statusArr = is_array($pemeriksaanProdukFinishGood->status_array) ? $pemeriksaanProdukFinishGood->status_array : [];
    $ketArr = is_array($pemeriksaanProdukFinishGood->keterangan_array) ? $pemeriksaanProdukFinishGood->keterangan_array : [];

    $rowCount = max(count($idProdukArr), count($kategoriArr), count($kodeArr), 1);

    // Group flat arrays by Produk (Model A): key includes kategori + negara.
    $grouped = [];
    for ($i = 0; $i < $rowCount; $i++) {
        $pid = $idProdukArr[$i] ?? null;
        $kategori = $kategoriArr[$i] ?? null;
        $negara = $negaraArr[$i] ?? null;
        if ($pid === null || $pid === '') continue;

        $key = (string)$pid . '|' . (string)$kategori . '|' . (string)$negara;
        if (!isset($grouped[$key])) {
            $grouped[$key] = [
                'id_produk' => $pid,
                'kategori' => $kategori,
                'negara' => $negara,
                'items' => [],
            ];
        }

        $grouped[$key]['items'][] = [
            'i' => $i,
            'kode' => $kodeArr[$i] ?? null,
            'expire' => $expireArr[$i] ?? null,
            'jumlah_datang' => $jmlDatangArr[$i] ?? null,
            'jumlah_sampling' => $jmlSamplingArr[$i] ?? null,
            'kemasan' => $kemasanArr[$i] ?? null,
            'warna' => $warnaArr[$i] ?? null,
            'aroma' => $aromaArr[$i] ?? null,
            'status' => $statusArr[$i] ?? null,
            'keterangan' => $ketArr[$i] ?? null,
            'suhu_mobil_type' => $suhuMobilTypeArr[$i] ?? null,
            'suhu_mobil_value' => $suhuMobilValueArr[$i] ?? null,
            'suhu_produk_type' => $suhuProdukTypeArr[$i] ?? null,
            'suhu_produk_value' => $suhuProdukValueArr[$i] ?? null,
            'kondisi_produk' => $kondisiProdukArr[$i] ?? null,
            'kondisi_produk_suhu_value' => $kondisiProdukSuhuValueArr[$i] ?? null,
            'logo_halal' => $logoArr[$i] ?? null,
            'dokumen_halal' => $dokArr[$i] ?? null,
            'coa' => $coaArr[$i] ?? null,
        ];
    }

    $groupList = array_values($grouped);
    if (count($groupList) < 1) {
        $groupList = [[
            'id_produk' => null,
            'kategori' => null,
            'negara' => null,
            'items' => [[
                'i' => 0,
                'kode' => null,
                'expire' => null,
                'jumlah_datang' => null,
                'jumlah_sampling' => null,
                'kemasan' => null,
                'warna' => null,
                'aroma' => null,
                'status' => null,
                'keterangan' => null,
                'suhu_mobil_type' => null,
                'suhu_mobil_value' => null,
                'suhu_produk_type' => null,
                'suhu_produk_value' => null,
                'kondisi_produk' => null,
                'kondisi_produk_suhu_value' => null,
                'logo_halal' => null,
                'dokumen_halal' => null,
                'coa' => null,
            ]],
        ]];
    }

    $headerKategoriCodes = array_map(fn($g) => $g['kategori'] ?? null, $groupList);
    $headerProdukIds = array_map(fn($g) => $g['id_produk'] ?? null, $groupList);
@endphp

<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Edit Pemeriksaan Produk Finish Good</h3>
                        <p class="text-subtitle text-muted">Form untuk mengedit data pemeriksaan produk finish good</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-produk-finish-good.index') }}">Pemeriksaan Produk Finish Good</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                            <h4 class="card-title">Form Pemeriksaan Produk Finish Good</h4>
                        </div>
                        <div class="card-body">
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

                            <form action="{{ route('pemeriksaan-produk-finish-good.update', $pemeriksaanProdukFinishGood->uuid) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Informasi Dasar</h5>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal', optional($pemeriksaanProdukFinishGood->tanggal)->format('Y-m-d')) }}" required>
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
                                                        <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanProdukFinishGood->id_shift) == $shift->id ? 'selected' : '' }}>
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
                                                <input type="text" id="jenis_mobil" class="form-control @error('jenis_mobil') is-invalid @enderror" name="jenis_mobil" value="{{ old('jenis_mobil', $pemeriksaanProdukFinishGood->jenis_mobil) }}" placeholder="Jenis Mobil">
                                                @error('jenis_mobil')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_mobil">No. Mobil</label>
                                                <input type="text" id="no_mobil" class="form-control @error('no_mobil') is-invalid @enderror" name="no_mobil" value="{{ old('no_mobil', $pemeriksaanProdukFinishGood->no_mobil) }}" placeholder="No. Mobil">
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
                                                <input type="text" id="nama_supir" class="form-control @error('nama_supir') is-invalid @enderror" name="nama_supir" value="{{ old('nama_supir', $pemeriksaanProdukFinishGood->nama_supir) }}" placeholder="Nama Supir">
                                                @error('nama_supir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Segel/Gembok</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="segel_option" name="segel_gembok" value="segel" {{ old('segel_gembok', $pemeriksaanProdukFinishGood->segel_gembok) == 'segel' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="segel_option">Segel</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="gembok_option" name="segel_gembok" value="gembok" {{ old('segel_gembok', $pemeriksaanProdukFinishGood->segel_gembok) == 'gembok' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gembok_option">Gembok</label>
                                                </div>
                                                @error('segel_gembok')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" id="no_segel_container" style="display: {{ old('segel_gembok', $pemeriksaanProdukFinishGood->segel_gembok) === 'segel' ? 'block' : 'none' }};">
                                                <label for="no_segel">No Segel</label>
                                                <input type="text" id="no_segel" class="form-control @error('no_segel') is-invalid @enderror" name="no_segel" value="{{ old('no_segel', $pemeriksaanProdukFinishGood->no_segel) }}" placeholder="No Segel">
                                                @error('no_segel')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
                                            <div class="mb-3">
                                                <label class="form-label"><strong>1. Bersih</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_ya" value="1" {{ (string)old('kondisi_mobil.bersih', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'bersih')) === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bersih_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bersih]" id="bersih_tidak" value="0" {{ (string)old('kondisi_mobil.bersih', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'bersih')) === '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bersih_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><strong>2. Bebas dari hama</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_ya" value="1" {{ (string)old('kondisi_mobil.bebas_hama', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'bebas_hama')) === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_hama_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_hama]" id="bebas_hama_tidak" value="0" {{ (string)old('kondisi_mobil.bebas_hama', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'bebas_hama')) === '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_hama_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><strong>3. Tidak Kondensasi</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_ya" value="1" {{ (string)old('kondisi_mobil.tidak_kondensasi', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'tidak_kondensasi')) === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_kondensasi_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_kondensasi]" id="tidak_kondensasi_tidak" value="0" {{ (string)old('kondisi_mobil.tidak_kondensasi', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'tidak_kondensasi')) === '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_kondensasi_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><strong>4. Bebas dari Produk Non Halal</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_ya" value="1" {{ (string)old('kondisi_mobil.bebas_produk_halal', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'bebas_produk_halal')) === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_produk_halal_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_produk_halal]" id="bebas_produk_halal_tidak" value="0" {{ (string)old('kondisi_mobil.bebas_produk_halal', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'bebas_produk_halal')) === '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_produk_halal_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label"><strong>5. Tidak Berbau Menyimpang</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_ya" value="1" {{ (string)old('kondisi_mobil.tidak_berbau', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'tidak_berbau')) === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_berbau_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_berbau]" id="tidak_berbau_tidak" value="0" {{ (string)old('kondisi_mobil.tidak_berbau', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'tidak_berbau')) === '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_berbau_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><strong>6. Tidak ada sampah</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_ya" value="1" {{ (string)old('kondisi_mobil.tidak_ada_sampah', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'tidak_ada_sampah')) === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_sampah_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_sampah]" id="tidak_ada_sampah_tidak" value="0" {{ (string)old('kondisi_mobil.tidak_ada_sampah', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'tidak_ada_sampah')) === '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_sampah_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><strong>7. Tidak ada pertumbuhan mikroba</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_ya" value="1" {{ (string)old('kondisi_mobil.tidak_ada_mikroba', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'tidak_ada_mikroba')) === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_mikroba_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tidak_ada_mikroba]" id="tidak_ada_mikroba_tidak" value="0" {{ (string)old('kondisi_mobil.tidak_ada_mikroba', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'tidak_ada_mikroba')) === '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tidak_ada_mikroba_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><strong>8. Lampu dan Cover tidak pecah</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_ya" value="1" {{ (string)old('kondisi_mobil.lampu_cover_utuh', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'lampu_cover_utuh')) === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="lampu_cover_utuh_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[lampu_cover_utuh]" id="lampu_cover_utuh_tidak" value="0" {{ (string)old('kondisi_mobil.lampu_cover_utuh', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'lampu_cover_utuh')) === '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="lampu_cover_utuh_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label"><strong>9. Pallet / Alas Utuh</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_ya" value="1" {{ (string)old('kondisi_mobil.pallet_utuh', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'pallet_utuh')) === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="pallet_utuh_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[pallet_utuh]" id="pallet_utuh_tidak" value="0" {{ (string)old('kondisi_mobil.pallet_utuh', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'pallet_utuh')) === '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="pallet_utuh_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><strong>10. Tertutup rapat/tidak bocor</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_ya" value="1" {{ (string)old('kondisi_mobil.tertutup_rapat', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'tertutup_rapat')) === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tertutup_rapat_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[tertutup_rapat]" id="tertutup_rapat_tidak" value="0" {{ (string)old('kondisi_mobil.tertutup_rapat', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'tertutup_rapat')) === '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tertutup_rapat_tidak">Tidak ✗</label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><strong>11. Bebas dari Kontaminan</strong></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_ya" value="1" {{ (string)old('kondisi_mobil.bebas_kontaminan', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'bebas_kontaminan')) === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_kontaminan_ya">Ya ✓</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kondisi_mobil[bebas_kontaminan]" id="bebas_kontaminan_tidak" value="0" {{ (string)old('kondisi_mobil.bebas_kontaminan', data_get($pemeriksaanProdukFinishGood->kondisi_mobil, 'bebas_kontaminan')) === '0' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bebas_kontaminan_tidak">Tidak ✗</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section mb-4">
                                    <h5 class="text-primary mb-3">Detail Produk (Baris Dinamis)</h5>
                                    <div id="unified-container">
                                        @php $globalDetail = 1; @endphp
                                        @foreach($groupList as $gIndex => $group)
                                            @php
                                                $items = is_array($group['items'] ?? null) ? $group['items'] : [];
                                                if (count($items) < 1) $items = [[ 'i' => 0 ]];
                                                $docLogo = old('logo_halal.'.$gIndex, $items[0]['logo_halal'] ?? null);
                                                $docDokumen = old('dokumen_halal.'.$gIndex, $items[0]['dokumen_halal'] ?? null);
                                                $docCoa = old('coa.'.$gIndex, $items[0]['coa'] ?? null);
                                            @endphp

                                            <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;" data-row-index="{{ $gIndex }}">
                                                <h6 class="text-primary mb-3">Produk {{ $gIndex + 1 }}</h6>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Kategori</label>
                                                            <select class="form-control kategori-produk-select" data-role="kategori">
                                                                <option value="">Pilih Kategori</option>
                                                                @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                                    <option value="{{ $kategori }}" {{ old('kategori_code.'.$gIndex, $group['kategori'] ?? '') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Nama Produk</label>
                                                            <select class="form-control produk-select" data-role="produk">
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
                                                                <div class="produsen-badges d-flex flex-wrap gap-1" data-row-index="{{ $gIndex }}">
                                                                    <span class="text-muted small">-</span>
                                                                </div>
                                                                <input type="hidden" class="produsen-hidden" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-body p-3">
                                                                <div class="fw-semibold">Distributor</div>
                                                                <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                                                <div class="distributor-badges d-flex flex-wrap gap-1" data-row-index="{{ $gIndex }}">
                                                                    <span class="text-muted small">-</span>
                                                                </div>
                                                                <input type="hidden" class="distributor-hidden" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Suhu Mobil</label>
                                                            <select class="form-control suhu-mobil-type" name="suhu_mobil_type[]">
                                                                <option value="">Pilih Jenis Suhu Mobil</option>
                                                                <option value="Fresh" {{ old('suhu_mobil_type.'.$gIndex, $items[0]['suhu_mobil_type'] ?? '') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                                <option value="Frozen" {{ old('suhu_mobil_type.'.$gIndex, $items[0]['suhu_mobil_type'] ?? '') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group suhu-mobil-input" style="display: none;">
                                                            <label class="form-label">Nilai Suhu Mobil (°C)</label>
                                                            <input type="text" class="form-control suhu-mobil-val" name="suhu_mobil_value[]" value="{{ old('suhu_mobil_value.'.$gIndex, $items[0]['suhu_mobil_value'] ?? '') }}" placeholder="Contoh: -18°C atau 4°C">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Suhu Produk</label>
                                                            <select class="form-control suhu-produk-type" name="suhu_produk_type[]">
                                                                <option value="">Pilih Jenis Suhu Produk</option>
                                                                <option value="Fresh" {{ old('suhu_produk_type.'.$gIndex, $items[0]['suhu_produk_type'] ?? '') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                                <option value="Frozen" {{ old('suhu_produk_type.'.$gIndex, $items[0]['suhu_produk_type'] ?? '') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group suhu-produk-input" style="display: none;">
                                                            <label class="form-label">Nilai Suhu Produk (°C)</label>
                                                            <input type="text" class="form-control suhu-produk-val" name="suhu_produk_value[]" value="{{ old('suhu_produk_value.'.$gIndex, $items[0]['suhu_produk_value'] ?? '') }}" placeholder="Contoh: -18°C atau 4°C">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Kondisi Produk</label>
                                                            <select class="form-control kondisi-produk-select" name="kondisi_produk[]">
                                                                <option value="">Pilih Kondisi Produk</option>
                                                                <option value="Frozen" {{ old('kondisi_produk.'.$gIndex, $items[0]['kondisi_produk'] ?? '') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                                                <option value="Fresh" {{ old('kondisi_produk.'.$gIndex, $items[0]['kondisi_produk'] ?? '') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                                                <option value="Dry" {{ old('kondisi_produk.'.$gIndex, $items[0]['kondisi_produk'] ?? '') == 'Dry' ? 'selected' : '' }}>Dry</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group kondisi-suhu-input" style="display: none;">
                                                            <label class="form-label">Nilai Suhu Kondisi Produk (°C)</label>
                                                            <input type="text" class="form-control kondisi-suhu-val" name="kondisi_produk_suhu_value[]" value="{{ old('kondisi_produk_suhu_value.'.$gIndex, $items[0]['kondisi_produk_suhu_value'] ?? '') }}" placeholder="Contoh: -18°C atau 4°C">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Negara Produsen</label>
                                                            <select class="choices form-control" data-role="negara">
                                                                <option value="">Pilih Negara</option>
                                                                @foreach($countries as $code => $name)
                                                                    <option value="{{ $name }}" {{ old('negara_produsen.'.$gIndex, $group['negara'] ?? '') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="detail-items">
                                                    @foreach($items as $dIndex => $it)
                                                        @php
                                                            $idx = $it['i'] ?? 0;
                                                            $detailNo = $dIndex + 1;
                                                        @endphp
                                                        <div class="detail-item border rounded p-3 mb-3" style="background: #fff;" data-detail-index="{{ $dIndex }}" data-detail-global-index="{{ $globalDetail - 1 }}">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <strong>Detail {{ $detailNo }}</strong>
                                                                <button type="button" class="btn btn-danger btn-sm remove-detail-btn" style="display:none;"><i class="bi bi-trash"></i> Hapus Detail</button>
                                                            </div>

                                                            <input type="hidden" name="kategori_code[]" class="detail-kategori" value="{{ old('kategori_code.'.$idx, $group['kategori'] ?? '') }}">
                                                            <input type="hidden" name="id_produk[]" class="detail-produk" value="{{ old('id_produk.'.$idx, $group['id_produk'] ?? '') }}">
                                                            <input type="hidden" name="negara_produsen[]" class="detail-negara" value="{{ old('negara_produsen.'.$idx, $group['negara'] ?? '') }}">
                                                            @php
                                                                $produsenVal = old('produsen.'.$idx, $pemeriksaanProdukFinishGood->produsen_array[$idx] ?? '');
                                                                $distributorVal = old('distributor.'.$idx, $pemeriksaanProdukFinishGood->distributor_array[$idx] ?? '');
                                                                if (is_array($produsenVal)) $produsenVal = json_encode($produsenVal);
                                                                if (is_array($distributorVal)) $distributorVal = json_encode($distributorVal);
                                                            @endphp
                                                            <input type="hidden" name="produsen[]" class="detail-produsen" value="{{ $produsenVal }}">
                                                            <input type="hidden" name="distributor[]" class="detail-distributor" value="{{ $distributorVal }}">

                                                            <input type="hidden" name="logo_halal[]" class="detail-logo" value="{{ old('logo_halal.'.$idx, $it['logo_halal'] ?? '') }}">
                                                            <input type="hidden" name="dokumen_halal[]" class="detail-dokumen" value="{{ old('dokumen_halal.'.$idx, $it['dokumen_halal'] ?? '') }}">
                                                            <input type="hidden" name="coa[]" class="detail-coa" value="{{ old('coa.'.$idx, $it['coa'] ?? '') }}">

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Kode Produksi</label>
                                                                        <input type="text" class="form-control" name="kode_produksi[]" value="{{ old('kode_produksi.'.$idx, $it['kode'] ?? '') }}" placeholder="Kode Produksi">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Expire Date</label>
                                                                        <input type="date" class="form-control" name="expire_date[]" value="{{ old('expire_date.'.$idx, $it['expire'] ?? '') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Jumlah Datang</label>
                                                                        <input type="text" class="form-control" name="jumlah_datang[]" value="{{ old('jumlah_datang.'.$idx, $it['jumlah_datang'] ?? '') }}" placeholder="Jumlah Datang">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Jumlah Sampling</label>
                                                                        <input type="text" class="form-control" name="jumlah_sampling[]" value="{{ old('jumlah_sampling.'.$idx, $it['jumlah_sampling'] ?? '') }}" placeholder="Jumlah Sampling">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-section mb-3">
                                                                <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label class="form-label"><strong>Kemasan</strong></label>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="kondisi_kemasan_{{ $globalDetail }}" value="1" {{ (string)old('kondisi_kemasan.'.$idx, $it['kemasan'] ?? '') === '1' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Ya ✓</label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="kondisi_kemasan_{{ $globalDetail }}" value="0" {{ (string)old('kondisi_kemasan.'.$idx, $it['kemasan'] ?? '') === '0' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Tidak ✗</label>
                                                                            </div>
                                                                            <input type="hidden" name="kondisi_kemasan[]" class="radio-value-kemasan-{{ $globalDetail }}" value="{{ old('kondisi_kemasan.'.$idx, $it['kemasan'] ?? '') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label class="form-label"><strong>Warna</strong></label>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="kondisi_warna_{{ $globalDetail }}" value="1" {{ (string)old('kondisi_warna.'.$idx, $it['warna'] ?? '') === '1' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Ya ✓</label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="kondisi_warna_{{ $globalDetail }}" value="0" {{ (string)old('kondisi_warna.'.$idx, $it['warna'] ?? '') === '0' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Tidak ✗</label>
                                                                            </div>
                                                                            <input type="hidden" name="kondisi_warna[]" class="radio-value-warna-{{ $globalDetail }}" value="{{ old('kondisi_warna.'.$idx, $it['warna'] ?? '') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label class="form-label"><strong>Aroma</strong></label>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="kondisi_aroma_{{ $globalDetail }}" value="1" {{ (string)old('kondisi_aroma.'.$idx, $it['aroma'] ?? '') === '1' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Ya ✓</label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="kondisi_aroma_{{ $globalDetail }}" value="0" {{ (string)old('kondisi_aroma.'.$idx, $it['aroma'] ?? '') === '0' ? 'checked' : '' }}>
                                                                                <label class="form-check-label">Tidak ✗</label>
                                                                            </div>
                                                                            <input type="hidden" name="kondisi_aroma[]" class="radio-value-aroma-{{ $globalDetail }}" value="{{ old('kondisi_aroma.'.$idx, $it['aroma'] ?? '') }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-section mb-3">
                                                                <h6 class="text-primary mb-2">Hasil Pemeriksaan</h6>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Status</label>
                                                                            <select class="form-control" name="status_baris[]">
                                                                                <option value="">Pilih Status</option>
                                                                                <option value="Hold" {{ old('status_baris.'.$idx, $it['status'] ?? '') == 'Hold' ? 'selected' : '' }}>Hold</option>
                                                                                <option value="Release" {{ old('status_baris.'.$idx, $it['status'] ?? '') == 'Release' ? 'selected' : '' }}>Release</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Keterangan</label>
                                                                            <textarea class="form-control" name="keterangan[]" rows="2" placeholder="Keterangan hasil pemeriksaan">{{ old('keterangan.'.$idx, $it['keterangan'] ?? '') }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @php $globalDetail++; @endphp
                                                    @endforeach
                                                </div>

                                                <div class="form-section mb-3">
                                                    <h6 class="text-primary mb-2">Dokumen</h6>
                                                    <input type="hidden" class="doc-master-logo" value="{{ $docLogo }}">
                                                    <input type="hidden" class="doc-master-dokumen" value="{{ $docDokumen }}">
                                                    <input type="hidden" class="doc-master-coa" value="{{ $docCoa }}">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>Logo Halal</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="logo_halal_master_{{ $gIndex + 1 }}" value="1" {{ (string)$docLogo === '1' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="logo_halal_master_{{ $gIndex + 1 }}" value="0" {{ (string)$docLogo === '0' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>Dokumen Halal</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="dokumen_halal_master_{{ $gIndex + 1 }}" value="1" {{ (string)$docDokumen === '1' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="dokumen_halal_master_{{ $gIndex + 1 }}" value="0" {{ (string)$docDokumen === '0' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Tidak ✗</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label"><strong>COA</strong></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="coa_master_{{ $gIndex + 1 }}" value="1" {{ (string)$docCoa === '1' ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Ya ✓</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="coa_master_{{ $gIndex + 1 }}" value="0" {{ (string)$docCoa === '0' ? 'checked' : '' }}>
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

                                    <!-- <div class="row mt-3 pt-3 border-top">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary btn-sm add-unified-btn"><i class="bi bi-plus"></i> Tambah Produk</button>
                                        </div>
                                    </div> -->
                                </div>

                                <div class="col-md-12 d-flex justify-content-end mt-3">
                                    <a href="{{ route('pemeriksaan-produk-finish-good.index') }}" class="btn btn-light-secondary me-1 mb-1 btn-kembali-confirm">Kembali</a>
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </header>
</div>

@push('scripts')
<script>
const produkByKategori = @json($produkByKategori ?? []);
const produkMeta = @json($produkMeta ?? []);
const oldKategoriCodes = @json(old('kategori_code', $headerKategoriCodes ?? []));
const oldProdukIds = @json(old('id_produk', $headerProdukIds ?? []));
const countriesList = @json(array_values($countries ?? []));

function initChoicesForContainer(containerEl) {
    if (!containerEl) return;
    if (!window.Choices) return;

    containerEl.querySelectorAll('select.choices').forEach((selectEl) => {
        if (selectEl.dataset.choicesInitialized === '1') return;
        try {
            const instance = new Choices(selectEl, {
                searchEnabled: true,
                shouldSort: false,
                itemSelectText: '',
            });
            selectEl.__choicesInstance = instance;
            selectEl.dataset.choicesInitialized = '1';
        } catch (e) {
        }
    });
}

function refreshChoices(selectEl) {
    if (!selectEl) return;
    if (!window.Choices) return;

    try {
        if (selectEl.__choicesInstance && typeof selectEl.__choicesInstance.destroy === 'function') {
            selectEl.__choicesInstance.destroy();
        }
    } catch (e) {
    }

    delete selectEl.__choicesInstance;
    delete selectEl.dataset.choicesInitialized;

    try {
        const instance = new Choices(selectEl, {
            searchEnabled: true,
            shouldSort: false,
            itemSelectText: '',
        });
        selectEl.__choicesInstance = instance;
        selectEl.dataset.choicesInitialized = '1';
    } catch (e) {
    }
}

function rebuildNegaraProdusenOptions(selectEl) {
    if (!selectEl) return;
    if (!countriesList || !Array.isArray(countriesList)) return;

    const currentValue = selectEl.value || '';

    selectEl.innerHTML = '';
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = 'Pilih Negara';
    selectEl.appendChild(placeholder);

    countriesList.forEach((name) => {
        const opt = document.createElement('option');
        opt.value = name;
        opt.textContent = name;
        if (currentValue && currentValue === name) opt.selected = true;
        selectEl.appendChild(opt);
    });
}

function getProdukOptionsForKategori(kategoriRaw) {
    const k = (kategoriRaw || '').toString().trim();
    if (!k || !produkByKategori) return [];

    const direct = produkByKategori[k];
    if (direct) return Array.isArray(direct) ? direct : Object.values(direct || {});

    const targetKey = k.toLowerCase();
    const foundKey = Object.keys(produkByKategori).find((key) => String(key).toLowerCase() === targetKey);
    if (!foundKey) return [];

    const v = produkByKategori[foundKey];
    return Array.isArray(v) ? v : Object.values(v || {});
}

function toggleNoSegel() {
    const container = document.getElementById('no_segel_container');
    const noSegel = document.getElementById('no_segel');
    const radios = document.querySelectorAll('input[name="segel_gembok"]');
    if (!container || !noSegel || radios.length === 0) return;

    const checked = Array.from(radios).find((r) => r.checked);
    if (checked && checked.value === 'segel') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
        noSegel.value = '';
    }
}

function setupSuhuRow(rowEl) {
    if (!rowEl) return;

    const suhuMobilType = rowEl.querySelector('select.suhu-mobil-type');
    const suhuMobilWrap = rowEl.querySelector('.suhu-mobil-input');
    const suhuMobilVal = rowEl.querySelector('input.suhu-mobil-val');

    const suhuProdukType = rowEl.querySelector('select.suhu-produk-type');
    const suhuProdukWrap = rowEl.querySelector('.suhu-produk-input');
    const suhuProdukVal = rowEl.querySelector('input.suhu-produk-val');

    const apply = () => {
        if (suhuMobilType && suhuMobilWrap) {
            const show = (suhuMobilType.value || '').toString().trim() !== '';
            suhuMobilWrap.style.display = show ? '' : 'none';
            if (!show && suhuMobilVal) suhuMobilVal.value = '';
        }
        if (suhuProdukType && suhuProdukWrap) {
            const show = (suhuProdukType.value || '').toString().trim() !== '';
            suhuProdukWrap.style.display = show ? '' : 'none';
            if (!show && suhuProdukVal) suhuProdukVal.value = '';
        }
    };

    if (suhuMobilType) {
        suhuMobilType.addEventListener('change', apply);
    }
    if (suhuProdukType) {
        suhuProdukType.addEventListener('change', apply);
    }
    apply();
}

function setupAllSuhuRows() {
    document.querySelectorAll('#unified-container .unified-row').forEach((row) => {
        setupSuhuRow(row);
    });
}

function setupKondisiProdukSuhuRow(rowEl) {
    if (!rowEl) return;

    const kondisiProdukSelect = rowEl.querySelector('select.kondisi-produk-select');
    const suhuWrap = rowEl.querySelector('.kondisi-suhu-input');
    const suhuVal = rowEl.querySelector('input.kondisi-suhu-val');

    const apply = () => {
        if (!kondisiProdukSelect || !suhuWrap) return;
        const v = (kondisiProdukSelect.value || '').toString().trim();
        const show = (v === 'Frozen' || v === 'Fresh');
        suhuWrap.style.display = show ? '' : 'none';
        if (!show && suhuVal) suhuVal.value = '';
    };

    if (kondisiProdukSelect) {
        kondisiProdukSelect.addEventListener('change', apply);
    }
    apply();
}

function setupAllKondisiProdukSuhuRows() {
    document.querySelectorAll('#unified-container .unified-row').forEach((row) => {
        setupKondisiProdukSuhuRow(row);
    });
}

function setupKondisiMobilCheckAll() {
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
}

function setupRowRadios(rowEl) {
    const globalIndex = Number(rowEl.dataset.detailGlobalIndex || 0) + 1;
    const mappings = [
        { key: 'kondisi_kemasan', hiddenPrefix: 'kemasan' },
        { key: 'kondisi_warna', hiddenPrefix: 'warna' },
        { key: 'kondisi_aroma', hiddenPrefix: 'aroma' },
    ];

    mappings.forEach(({ key, hiddenPrefix }) => {
        const radioName = `${key}_${globalIndex}`;
        rowEl.querySelectorAll(`input[type="radio"][name^="${key}_"]`).forEach((radio) => {
            radio.name = radioName;
        });

        const hidden = rowEl.querySelector(`input[type="hidden"].radio-value-${hiddenPrefix}-${globalIndex}`)
            || rowEl.querySelector(`input[type="hidden"][name="${key}[]"]`);

        rowEl.querySelectorAll(`input[type="radio"][name="${radioName}"]`).forEach((radio) => {
            radio.addEventListener('change', function () {
                if (hidden) hidden.value = this.value;
            });
        });

        const checked = rowEl.querySelector(`input[type="radio"][name="${radioName}"]:checked`);
        if (checked && hidden && (hidden.value === '' || hidden.value === null)) {
            hidden.value = checked.value;
        }
    });
}

function updateRowNumbers() {
    let globalDetail = 0;
    document.querySelectorAll('#unified-container .unified-row').forEach((row, idx) => {
        row.dataset.rowIndex = String(idx);
        const title = row.querySelector('h6');
        if (title) title.textContent = `Produk ${idx + 1}`;

        // Update master dokumen radio names per row to avoid cross-row interference
        const rowNum = idx + 1;
        row.querySelectorAll('input[type="radio"][name^="logo_halal_master_"]').forEach((el) => { el.name = `logo_halal_master_${rowNum}`; });
        row.querySelectorAll('input[type="radio"][name^="dokumen_halal_master_"]').forEach((el) => { el.name = `dokumen_halal_master_${rowNum}`; });
        row.querySelectorAll('input[type="radio"][name^="coa_master_"]').forEach((el) => { el.name = `coa_master_${rowNum}`; });

        const detailItems = Array.from(row.querySelectorAll('.detail-items .detail-item'));
        detailItems.forEach((detailEl, dIdx) => {
            detailEl.dataset.detailIndex = String(dIdx);
            detailEl.dataset.detailGlobalIndex = String(globalDetail);
            const detailTitle = detailEl.querySelector('strong');
            if (detailTitle) detailTitle.textContent = `Detail ${dIdx + 1}`;

            detailEl.querySelectorAll('input[type="hidden"][name="kondisi_kemasan[]"]').forEach((el) => {
                el.className = `radio-value-kemasan-${globalDetail + 1}`;
            });
            detailEl.querySelectorAll('input[type="hidden"][name="kondisi_warna[]"]').forEach((el) => {
                el.className = `radio-value-warna-${globalDetail + 1}`;
            });
            detailEl.querySelectorAll('input[type="hidden"][name="kondisi_aroma[]"]').forEach((el) => {
                el.className = `radio-value-aroma-${globalDetail + 1}`;
            });

            setupRowRadios(detailEl);
            globalDetail += 1;
        });

        detailItems.forEach((detailEl) => {
            const btn = detailEl.querySelector('.remove-detail-btn');
            if (btn) btn.style.display = detailItems.length > 1 ? '' : 'none';
        });
    });
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('#unified-container .unified-row');
    rows.forEach((row) => {
        const btn = row.querySelector('.remove-unified-btn');
        if (btn) btn.disabled = rows.length <= 1;
    });
}

function applyProdukMetaForRow(rowEl) {
    const produkSelect = rowEl.querySelector('select.produk-select');
    const produsenBadges = rowEl.querySelector('.produsen-badges');
    const distributorBadges = rowEl.querySelector('.distributor-badges');
    const produsenHidden = rowEl.querySelector('input.produsen-hidden');
    const distributorHidden = rowEl.querySelector('input.distributor-hidden');
    if (!produkSelect) return;

    const produkId = String(produkSelect.value || '');
    const meta = produkId && produkMeta ? produkMeta[produkId] : null;

    const renderBadges = (el, list) => {
        if (!el) return;
        el.innerHTML = '';
        (list || []).forEach((t) => {
            const span = document.createElement('span');
            span.className = 'badge bg-light-secondary me-1 mb-1';
            span.textContent = t;
            el.appendChild(span);
        });
    };

    const produsen = meta && Array.isArray(meta.produsen) ? meta.produsen : [];
    const distributor = meta && Array.isArray(meta.distributor) ? meta.distributor : [];

    renderBadges(produsenBadges, produsen);
    renderBadges(distributorBadges, distributor);

    if (produsenHidden) produsenHidden.value = JSON.stringify(produsen);
    if (distributorHidden) distributorHidden.value = JSON.stringify(distributor);
}

function populateProdukOptionsForRow(rowEl) {
    const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
    const produkSelect = rowEl.querySelector('select.produk-select');
    if (!kategoriSelect || !produkSelect) return;

    const kategori = (kategoriSelect.value || '').toString();
    const options = getProdukOptionsForKategori(kategori);

    const desiredProdukId = rowEl.dataset.oldProdukId ? String(rowEl.dataset.oldProdukId) : '';

    const choiceItems = options.map((opt) => {
        const value = String(opt.id);
        const label = (opt && (opt.nama ?? opt.nama_produk ?? opt.label ?? opt.text)) ?? '';
        return {
            value,
            label: String(label),
            selected: desiredProdukId ? (value === desiredProdukId) : false,
            disabled: false,
        };
    });

    // Chemical module pattern: destroy Choices, rebuild DOM options, then re-init.
    // This avoids cases where Choices UI shows but choices list is empty.
    try {
        if (produkSelect.__choicesInstance && typeof produkSelect.__choicesInstance.destroy === 'function') {
            produkSelect.__choicesInstance.destroy();
        }
    } catch (e) {
    }
    delete produkSelect.__choicesInstance;
    delete produkSelect.dataset.choicesInitialized;

    produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
    if (kategori && options.length > 0) {
        choiceItems.forEach((it) => {
            const optionEl = document.createElement('option');
            optionEl.value = it.value;
            optionEl.textContent = it.label;
            if (it.selected) optionEl.selected = true;
            produkSelect.appendChild(optionEl);
        });
    }
    refreshChoices(produkSelect);

    applyProdukMetaForRow(rowEl);
}

function setupProdukRowListeners(rowEl) {
    const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
    const produkSelect = rowEl.querySelector('select.produk-select');
    const negaraSelect = rowEl.querySelector('select[data-role="negara"]') || rowEl.querySelector('select[name="negara_produsen[]"]');

    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', function() {
            delete rowEl.dataset.oldProdukId;
            if (produkSelect) produkSelect.value = '';
            populateProdukOptionsForRow(rowEl);
            syncHeaderToDetails(rowEl);
        });
    }
    if (produkSelect) {
        produkSelect.addEventListener('change', function() {
            applyProdukMetaForRow(rowEl);
            syncHeaderToDetails(rowEl);
        });
    }
    if (negaraSelect) {
        negaraSelect.addEventListener('change', function() {
            syncHeaderToDetails(rowEl);
        });
    }
}

function syncHeaderToDetails(rowEl) {
    if (!rowEl) return;
    const kategori = rowEl.querySelector('select.kategori-produk-select')?.value || '';
    const produk = rowEl.querySelector('select.produk-select')?.value || '';
    const negara = rowEl.querySelector('select[data-role="negara"]')?.value || '';
    const produsen = rowEl.querySelector('input.produsen-hidden')?.value || '';
    const distributor = rowEl.querySelector('input.distributor-hidden')?.value || '';

    rowEl.querySelectorAll('.detail-item').forEach((detailEl) => {
        const k = detailEl.querySelector('input.detail-kategori');
        const p = detailEl.querySelector('input.detail-produk');
        const n = detailEl.querySelector('input.detail-negara');
        const pr = detailEl.querySelector('input.detail-produsen');
        const ds = detailEl.querySelector('input.detail-distributor');
        if (k) k.value = kategori;
        if (p) p.value = produk;
        if (n) n.value = negara;
        if (pr) pr.value = produsen;
        if (ds) ds.value = distributor;
    });
}

function syncDokumenToDetails(rowEl) {
    if (!rowEl) return;
    const logo = rowEl.querySelector('input.doc-master-logo')?.value ?? '';
    const dok = rowEl.querySelector('input.doc-master-dokumen')?.value ?? '';
    const coa = rowEl.querySelector('input.doc-master-coa')?.value ?? '';

    rowEl.querySelectorAll('.detail-item').forEach((detailEl) => {
        const l = detailEl.querySelector('input.detail-logo');
        const d = detailEl.querySelector('input.detail-dokumen');
        const c = detailEl.querySelector('input.detail-coa');
        if (l) l.value = logo;
        if (d) d.value = dok;
        if (c) c.value = coa;
    });
}

function initializeProdukFlow() {
    document.querySelectorAll('#unified-container .unified-row').forEach((row, idx) => {
        row.dataset.rowIndex = String(idx);
    });

    document.querySelectorAll('#unified-container .unified-row').forEach((row, idx) => {
        const desiredKategori = (oldKategoriCodes && oldKategoriCodes[idx]) ? String(oldKategoriCodes[idx]) : '';
        const desiredProdukId = (oldProdukIds && oldProdukIds[idx]) ? String(oldProdukIds[idx]) : '';
        if (desiredProdukId) row.dataset.oldProdukId = desiredProdukId;

        const kategoriSelect = row.querySelector('select.kategori-produk-select');
        if (kategoriSelect && desiredKategori) kategoriSelect.value = desiredKategori;
        setupProdukRowListeners(row);

        if (kategoriSelect && kategoriSelect.value) {
            populateProdukOptionsForRow(row);
        }

        syncHeaderToDetails(row);
        syncDokumenToDetails(row);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
        el.addEventListener('click', function(e) {
            const ok = confirm('Data belum disimpan. Yakin ingin kembali ke halaman index?');
            if (!ok) e.preventDefault();
        });
    });

    initChoicesForContainer(document);
    initializeProdukFlow();
    updateRowNumbers();
    updateRemoveButtons();

    setupAllSuhuRows();
    setupAllKondisiProdukSuhuRows();
    setupKondisiMobilCheckAll();
    toggleNoSegel();

    document.querySelectorAll('input[name="segel_gembok"]').forEach((el) => {
        el.addEventListener('change', function() {
            toggleNoSegel();
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-unified-btn')) {
            const rowCount = document.querySelectorAll('#unified-container .unified-row').length;
            const row = e.target.closest('.unified-row');
            if (rowCount > 1 && row) {
                row.remove();
                updateRowNumbers();
                updateRemoveButtons();
            }
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-unified-btn')) {
            const container = document.getElementById('unified-container');
            const rows = container.querySelectorAll('.unified-row');
            const lastRow = rows[rows.length - 1];
            const newRow = lastRow.cloneNode(true);

            // Remove Choices DOM wrappers if cloning from a Choices-initialized row.
            newRow.querySelectorAll('.choices').forEach((el) => {
                if (el.tagName.toLowerCase() !== 'select') {
                    el.remove();
                }
            });

            // If this row was previously initialized by Choices.js, the original selects may be hidden.
            newRow.querySelectorAll('select.choices').forEach((el) => {
                el.removeAttribute('hidden');
                el.style.display = '';
                delete el.dataset.choicesInitialized;
                el.removeAttribute('tabindex');
                el.removeAttribute('aria-hidden');
                el.removeAttribute('role');
                el.removeAttribute('data-choice');
                el.removeAttribute('data-id');
                el.removeAttribute('data-select-text');
                el.removeAttribute('data-position');
            });

            newRow.querySelectorAll('input, textarea, select').forEach((el) => {
                if (el.tagName.toLowerCase() === 'select') {
                    el.value = '';
                    if (el.classList.contains('produk-select')) {
                        el.innerHTML = '<option value="">Pilih Produk</option>';
                    }
                } else if (el.type === 'radio') {
                    el.checked = false;
                } else {
                    el.value = '';
                }
            });

            newRow.querySelectorAll('input[type="hidden"]').forEach((el) => {
                if (el.classList.contains('produsen-hidden') || el.classList.contains('distributor-hidden')) {
                    el.value = '';
                }
            });

            newRow.querySelectorAll('.produsen-badges, .distributor-badges').forEach((el) => {
                el.innerHTML = '<span class="text-muted small">-</span>';
            });

            newRow.querySelectorAll('.suhu-mobil-input, .suhu-produk-input').forEach((el) => {
                el.style.display = 'none';
            });

            container.appendChild(newRow);

            // Ensure Negara Produsen keeps its options in dynamic rows
            const negaraSelect = newRow.querySelector('select[data-role="negara"]') || newRow.querySelector('select[name="negara_produsen[]"]');
            if (negaraSelect) {
                const optionCount = negaraSelect.querySelectorAll('option').length;
                if (optionCount <= 1) {
                    rebuildNegaraProdusenOptions(negaraSelect);
                }
            }

            // Ensure new product starts with exactly one detail item
            const detailItems = newRow.querySelectorAll('.detail-item');
            detailItems.forEach((el, idx) => {
                if (idx > 0) el.remove();
            });
            const firstDetail = newRow.querySelector('.detail-item');
            if (firstDetail) {
                firstDetail.querySelectorAll('input, textarea, select').forEach((el) => {
                    if (el.type === 'radio') {
                        el.checked = false;
                    } else {
                        el.value = '';
                    }
                });
            }

            // Reset dokumen master values
            const docLogo = newRow.querySelector('input.doc-master-logo');
            const docDok = newRow.querySelector('input.doc-master-dokumen');
            const docCoa = newRow.querySelector('input.doc-master-coa');
            if (docLogo) docLogo.value = '';
            if (docDok) docDok.value = '';
            if (docCoa) docCoa.value = '';

            initChoicesForContainer(newRow);
            updateRowNumbers();
            updateRemoveButtons();
            setupProdukRowListeners(newRow);
            setupSuhuRow(newRow);
            setupKondisiProdukSuhuRow(newRow);
            syncHeaderToDetails(newRow);
            syncDokumenToDetails(newRow);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-detail-btn')) {
            const rowEl = e.target.closest('.unified-row');
            if (!rowEl) return;
            const container = rowEl.querySelector('.detail-items');
            const items = container ? container.querySelectorAll('.detail-item') : [];
            const last = items.length ? items[items.length - 1] : null;
            if (!container || !last) return;

            const newItem = last.cloneNode(true);
            newItem.querySelectorAll('input, textarea, select').forEach((el) => {
                if (el.type === 'radio') {
                    el.checked = false;
                } else {
                    el.value = '';
                }
            });
            newItem.querySelectorAll('input[type="hidden"][name="kondisi_kemasan[]"], input[type="hidden"][name="kondisi_warna[]"], input[type="hidden"][name="kondisi_aroma[]"]').forEach((el) => {
                el.value = '';
            });

            container.appendChild(newItem);
            syncHeaderToDetails(rowEl);
            syncDokumenToDetails(rowEl);
            updateRowNumbers();
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-detail-btn')) {
            const rowEl = e.target.closest('.unified-row');
            const detailEl = e.target.closest('.detail-item');
            if (!rowEl || !detailEl) return;
            const items = rowEl.querySelectorAll('.detail-item');
            if (items.length > 1) {
                detailEl.remove();
                updateRowNumbers();
            }
        }
    });

    document.addEventListener('change', function(e) {
        const rowEl = e.target.closest('.unified-row');
        if (!rowEl) return;
        if (e.target.matches('input[type="radio"][name^="logo_halal_master_"]')) {
            const master = rowEl.querySelector('input.doc-master-logo');
            if (master) master.value = e.target.value;
            syncDokumenToDetails(rowEl);
        }
        if (e.target.matches('input[type="radio"][name^="dokumen_halal_master_"]')) {
            const master = rowEl.querySelector('input.doc-master-dokumen');
            if (master) master.value = e.target.value;
            syncDokumenToDetails(rowEl);
        }
        if (e.target.matches('input[type="radio"][name^="coa_master_"]')) {
            const master = rowEl.querySelector('input.doc-master-coa');
            if (master) master.value = e.target.value;
            syncDokumenToDetails(rowEl);
        }
    });
});
</script>
@endpush
@endsection
