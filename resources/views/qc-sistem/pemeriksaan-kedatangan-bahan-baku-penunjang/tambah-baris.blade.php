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
                    <h3>Tambah Baris - Pemeriksaan Bahan Baku Penunjang</h3>
                    <p class="text-subtitle text-muted">Tambahkan 1 baris detail produk ke data yang sudah ada</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-bahan-baku.index') }}">Pemeriksaan Bahan Baku Penunjang</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Baris</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                    <form action="{{ route('pemeriksaan-bahan-baku.store-baris', $pemeriksaanBahanBaku->uuid) }}" method="POST" id="tambahBarisForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
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
                                    <label class="form-label">Nama Bahan</label>
                                    <select class="choices form-control" name="id_bahan">
                                        <option value="">Pilih Bahan</option>
                                        @foreach($bahans as $bahan)
                                            <option value="{{ $bahan->id }}" {{ old('id_bahan') == $bahan->id ? 'selected' : '' }}>{{ $bahan->nama_bahan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Produsen</label>
                                    <select class="choices form-control" name="produsen">
                                        <option value="">Pilih Produsen</option>
                                        @foreach ($produsens as $produsen)
                                            <option value="{{ $produsen->nama_produsen }}" {{ old('produsen') == $produsen->nama_produsen ? 'selected' : '' }}>{{ $produsen->nama_produsen }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Negara Produsen</label>
                                    <select class="choices form-control" name="negara_produsen">
                                        <option value="">Pilih Negara</option>
                                        @foreach ($countries as $code => $name)
                                            <option value="{{ $name }}" {{ old('negara_produsen') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Distributor</label>
                                    <select class="choices form-control" name="distributor">
                                        <option value="">Pilih Distributor</option>
                                        @foreach ($distributors as $distributor)
                                            <option value="{{ $distributor->nama_distributor }}" {{ old('distributor') == $distributor->nama_distributor ? 'selected' : '' }}>{{ $distributor->nama_distributor }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Kode Produksi</label>
                                    <input type="text" class="form-control" name="kode_produksi" value="{{ old('kode_produksi') }}" placeholder="Kode Produksi">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Expire Date</label>
                                    <input type="date" class="form-control" name="expire_date" value="{{ old('expire_date') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Jumlah Datang (kg)</label>
                                    <input type="text" class="form-control" name="jumlah_datang" value="{{ old('jumlah_datang') }}" placeholder="Jumlah Datang">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Jumlah Sampling</label>
                                    <input type="text" class="form-control" name="jumlah_sampling" value="{{ old('jumlah_sampling') }}" placeholder="Jumlah Sampling">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Spesifikasi</label>
                                    <textarea class="form-control" name="spesifikasi" rows="2" placeholder="Spesifikasi">{{ old('spesifikasi') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Suhu Produk</label>
                                    <select class="form-control" name="suhu_produk_type" id="suhuProdukType">
                                        <option value="">Pilih Jenis Suhu Produk</option>
                                        <option value="Fresh" {{ old('suhu_produk_type') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                        <option value="Frozen" {{ old('suhu_produk_type') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                        <option value="Tidak Ada" {{ old('suhu_produk_type') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3" id="suhuProdukInputWrapper" style="display: none;">
                                    <label class="form-label">Nilai Suhu Produk (°C)</label>
                                    <input type="text" class="form-control" name="suhu_produk" id="suhuProdukVal" value="{{ old('suhu_produk') }}" placeholder="Contoh: -18°C atau 4°C">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Suhu Mobil</label>
                                    <select class="form-control" name="suhu_mobil_type" id="suhuMobilType">
                                        <option value="">Pilih Jenis Suhu Mobil</option>
                                        <option value="Fresh" {{ old('suhu_mobil_type') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                        <option value="Frozen" {{ old('suhu_mobil_type') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                        <option value="Tidak Ada" {{ old('suhu_mobil_type') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3" id="suhuMobilInputWrapper" style="display: none;">
                                    <label class="form-label">Nilai Suhu Mobil (°C)</label>
                                    <input type="text" class="form-control" name="suhu_mobil" id="suhuMobilVal" value="{{ old('suhu_mobil') }}" placeholder="Contoh: -18°C atau 4°C">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Kondisi Produk</label>
                                    <select class="form-control" name="kondisi_produk" id="kondisiProduk">
                                        <option value="">Pilih Kondisi Produk</option>
                                        <option value="Fresh" {{ old('kondisi_produk') == 'Fresh' ? 'selected' : '' }}>Fresh</option>
                                        <option value="Frozen" {{ old('kondisi_produk') == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                                        <option value="Dry" {{ old('kondisi_produk') == 'Dry' ? 'selected' : '' }}>Dry</option>
                                        <option value="Minyak" {{ old('kondisi_produk') == 'Minyak' ? 'selected' : '' }}>Minyak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3" id="kondisiProdukSuhuWrapper" style="display: none;">
                                    <label class="form-label">Suhu Kondisi Produk (°C)</label>
                                    <input type="text" class="form-control" name="kondisi_produk_suhu" id="kondisiProdukSuhuVal" value="{{ old('kondisi_produk_suhu') }}" placeholder="Suhu Kondisi Produk">
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3">
                            <h6 class="text-primary mb-2">Kondisi Fisik</h6>
                            <div class="row">
                                <div class="col-md-3">
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
                                <div class="col-md-3">
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
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Benda Asing</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing" value="1" {{ old('kondisi_fisik_benda_asing') === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label">Ya ✓</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_fisik_benda_asing" value="0" {{ old('kondisi_fisik_benda_asing') === '0' ? 'checked' : '' }}>
                                            <label class="form-check-label">Tidak ✗</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Aroma</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_fisik_aroma" value="1" {{ old('kondisi_fisik_aroma') === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label">Ya ✓</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_fisik_aroma" value="0" {{ old('kondisi_fisik_aroma') === '0' ? 'checked' : '' }}>
                                            <label class="form-check-label">Tidak ✗</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3">
                            <h6 class="text-primary mb-2">Upload COA (PDF)</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">File COA (PDF)</label>
                                        <input type="file" name="file_coa" class="form-control" accept="application/pdf">
                                        @error('file_coa')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3">
                            <h6 class="text-primary mb-2">Dokumen</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Logo Halal</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="logo_halal" value="1" {{ old('logo_halal') === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label">Ya ✓</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="logo_halal" value="0" {{ old('logo_halal') === '0' ? 'checked' : '' }}>
                                            <label class="form-check-label">Tidak ✗</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Dokumen Halal</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="dokumen_halal" value="1" {{ old('dokumen_halal') === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label">Ya ✓</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="dokumen_halal" value="0" {{ old('dokumen_halal') === '0' ? 'checked' : '' }}>
                                            <label class="form-check-label">Tidak ✗</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Hasil Uji FFA</label>
                                    <input type="text" class="form-control" name="hasil_uji_ffa" value="{{ old('hasil_uji_ffa') }}" placeholder="Hasil Uji FFA">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" name="keterangan" value="{{ old('keterangan') }}" placeholder="Keterangan">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('pemeriksaan-bahan-baku.index') }}" class="btn btn-light-secondary">Kembali</a>
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

        const suhuProdukType = document.getElementById('suhuProdukType');
        const suhuProdukInputWrapper = document.getElementById('suhuProdukInputWrapper');
        const suhuProdukVal = document.getElementById('suhuProdukVal');

        const suhuMobilType = document.getElementById('suhuMobilType');
        const suhuMobilInputWrapper = document.getElementById('suhuMobilInputWrapper');
        const suhuMobilVal = document.getElementById('suhuMobilVal');

        const kondisiProduk = document.getElementById('kondisiProduk');
        const kondisiProdukSuhuWrapper = document.getElementById('kondisiProdukSuhuWrapper');
        const kondisiProdukSuhuVal = document.getElementById('kondisiProdukSuhuVal');

        function toggleSuhuProduk() {
            const val = (suhuProdukType?.value || '').toLowerCase();
            if (val === 'fresh' || val === 'frozen') {
                suhuProdukInputWrapper.style.display = 'block';
            } else {
                suhuProdukInputWrapper.style.display = 'none';
                if (suhuProdukVal) suhuProdukVal.value = '';
            }
        }

        function toggleSuhuMobil() {
            const val = (suhuMobilType?.value || '').toLowerCase();
            if (val === 'fresh' || val === 'frozen') {
                suhuMobilInputWrapper.style.display = 'block';
            } else {
                suhuMobilInputWrapper.style.display = 'none';
                if (suhuMobilVal) suhuMobilVal.value = '';
            }
        }

        function toggleKondisiProdukSuhu() {
            const val = (kondisiProduk?.value || '').toLowerCase();
            if (val === 'fresh' || val === 'frozen' || val === 'dry' || val === 'minyak') {
                kondisiProdukSuhuWrapper.style.display = 'block';
            } else {
                kondisiProdukSuhuWrapper.style.display = 'none';
                if (kondisiProdukSuhuVal) kondisiProdukSuhuVal.value = '';
            }
        }

        suhuProdukType?.addEventListener('change', toggleSuhuProduk);
        suhuMobilType?.addEventListener('change', toggleSuhuMobil);
        kondisiProduk?.addEventListener('change', toggleKondisiProdukSuhu);

        toggleSuhuProduk();
        toggleSuhuMobil();
        toggleKondisiProdukSuhu();
    });
</script>
@endsection
