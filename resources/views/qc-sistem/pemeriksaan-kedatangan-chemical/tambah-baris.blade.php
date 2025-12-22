@extends('layouts.app')

@section('title', 'Tambah Baris Pemeriksaan Kedatangan Chemical')

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
                    <h3>Tambah Baris - Pemeriksaan Kedatangan Chemical</h3>
                    <p class="text-subtitle text-muted">Tambahkan 1 baris chemical ke data yang sudah ada</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-chemical.index') }}">Pemeriksaan Kedatangan Chemical</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Baris</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('pemeriksaan-chemical.store-baris', $pemeriksaanChemical->uuid) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status Baris <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status_baris" required>
                                        <option value="">Pilih Status</option>
                                        <option value="Release" {{ old('status_baris') == 'Release' ? 'selected' : '' }}>Release</option>
                                        <option value="Hold" {{ old('status_baris') == 'Hold' ? 'selected' : '' }}>Hold</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Nama Chemical <span class="text-danger">*</span></label>
                                    <select class="choices form-control" name="id_chemical" required>
                                        <option value="">Pilih Chemical</option>
                                        @foreach($chemicals as $chemical)
                                            <option value="{{ $chemical->id }}" {{ old('id_chemical') == $chemical->id ? 'selected' : '' }}>{{ $chemical->nama_chemical }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Kondisi Chemical</label>
                                    <select class="form-control" name="kondisi_chemical">
                                        <option value="">Pilih Kondisi</option>
                                        <option value="Cair" {{ old('kondisi_chemical') == 'Cair' ? 'selected' : '' }}>Cair</option>
                                        <option value="Serbuk" {{ old('kondisi_chemical') == 'Serbuk' ? 'selected' : '' }}>Serbuk</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Produsen</label>
                                    <select class="choices form-control" name="id_produsen">
                                        <option value="">Pilih Produsen</option>
                                        @foreach($produsens as $produsen)
                                            <option value="{{ $produsen->id }}" {{ old('id_produsen') == $produsen->id ? 'selected' : '' }}>{{ $produsen->nama_produsen }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Negara Produsen</label>
                                    <select class="choices form-control" name="negara_produsen">
                                        <option value="">Pilih Negara</option>
                                        @foreach($countries as $code => $name)
                                            <option value="{{ $name }}" {{ old('negara_produsen') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Distributor</label>
                                    <select class="choices form-control" name="id_distributor">
                                        <option value="">Pilih Distributor</option>
                                        @foreach($distributors as $distributor)
                                            <option value="{{ $distributor->id }}" {{ old('id_distributor') == $distributor->id ? 'selected' : '' }}>{{ $distributor->nama_distributor }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Kode Produksi</label>
                                    <input type="text" class="form-control" name="kode_produksi" value="{{ old('kode_produksi') }}" placeholder="Kode Produksi">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Expire Date</label>
                                    <input type="date" class="form-control" name="expire_date" value="{{ old('expire_date') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Jumlah Datang (kg/liter/pail)</label>
                                    <input type="text" class="form-control" name="jumlah_datang" value="{{ old('jumlah_datang') }}" placeholder="Jumlah Datang">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Jumlah Sampling</label>
                                    <input type="text" class="form-control" name="jumlah_sampling" value="{{ old('jumlah_sampling') }}" placeholder="Jumlah Sampling">
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3">
                            <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Kemasan</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan" value="1" {{ old('kondisi_fisik_kemasan') === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label">Ya ✓</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_fisik_kemasan" value="0" {{ old('kondisi_fisik_kemasan') === '0' ? 'checked' : '' }}>
                                            <label class="form-check-label">Tidak ✗</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Warna</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_fisik_warna" value="1" {{ old('kondisi_fisik_warna') === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label">Ya ✓</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_fisik_warna" value="0" {{ old('kondisi_fisik_warna') === '0' ? 'checked' : '' }}>
                                            <label class="form-check-label">Tidak ✗</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3">
                            <h6 class="text-primary mb-2">Dokumen & Sertifikasi</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Persyaratan Dokumen Halal</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal" value="1" {{ old('persyaratan_dokumen_halal') === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label">Ya ✓</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="persyaratan_dokumen_halal" value="0" {{ old('persyaratan_dokumen_halal') === '0' ? 'checked' : '' }}>
                                            <label class="form-check-label">Tidak ✗</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>COA</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="coa" value="1" {{ old('coa') === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label">Ya ✓</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="coa" value="0" {{ old('coa') === '0' ? 'checked' : '' }}>
                                            <label class="form-check-label">Tidak ✗</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" name="keterangan" value="{{ old('keterangan') }}" placeholder="Keterangan">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('pemeriksaan-chemical.index') }}" class="btn btn-light-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Baris</button>
                        </div>

                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        try {
            if (typeof window.Choices !== 'undefined') {
                document.querySelectorAll('.choices').forEach(function (element) {
                    new Choices(element, {
                        searchEnabled: true,
                        itemSelectText: '',
                        shouldSort: false,
                    });
                });
            }
        } catch (e) {
            // do nothing
        }
    });
</script>
@endsection
