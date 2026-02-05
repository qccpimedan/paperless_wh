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
                    <h3>Tambah Batch - Pemeriksaan Kedatangan Chemical</h3>
                    <p class="text-subtitle text-muted">Tambahkan 1 baris chemical ke data yang sudah ada</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-chemical.index') }}">Pemeriksaan Kedatangan Chemical</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Batch</li>
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
                    <form action="{{ route('pemeriksaan-chemical.store-baris', $pemeriksaanChemical->uuid) }}" method="POST" enctype="multipart/form-data">
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

                        <div class="form-section mb-3">
                            <h6 class="text-primary mb-2">Upload Gambar</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Foto Chemical (Max 1MB)</label>
                                        <input type="file" name="image_chemical" class="form-control" accept="image/*" capture="camera">
                                        @error('image_chemical')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="choices form-control kategori-produk-select" name="kategori_code" data-desired-produk="{{ old('id_produk') }}" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach(($produkKategoriOptions ?? []) as $kategori)
                                            <option value="{{ $kategori }}" {{ old('kategori_code') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Produk <span class="text-danger">*</span></label>
                                    <select class="form-control produk-select" name="id_produk">
                                        <option value="">Pilih Produk</option>
                                    </select>
                                    <input type="hidden" name="id_chemical" class="id-chemical-hidden" value="{{ old('id_chemical') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
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
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-3">
                                        <div class="fw-semibold">Produsen</div>
                                        <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                        <div class="produsen-badges d-flex flex-wrap gap-1">
                                            <span class="text-muted small">-</span>
                                        </div>
                                        <input type="hidden" name="id_produsen" class="id-produsen-hidden" value="{{ old('id_produsen') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-3">
                                        <div class="fw-semibold">Distributor</div>
                                        <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                        <div class="distributor-badges d-flex flex-wrap gap-1">
                                            <span class="text-muted small">-</span>
                                        </div>
                                        <input type="hidden" name="id_distributor" class="id-distributor-hidden" value="{{ old('id_distributor') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
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
                            <button type="submit" class="btn btn-primary">Simpan Batch</button>
                        </div>

                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const produkByKategori = @json($produkByKategori ?? []);
        const produkMeta = @json($produkMeta ?? []);
        const chemicalByName = @json($chemicalByName ?? []);
        const chemicalByProdukId = @json($chemicalByProdukId ?? []);
        const oldProdukId = @json(old('id_produk'));

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

        function initProdukChoices(selectEl) {
            if (!selectEl) return;
            if (typeof window.Choices === 'undefined') {
                return;
            }
            if (selectEl.choicesInstance) {
                try { selectEl.choicesInstance.destroy(); } catch (e) {}
                selectEl.choicesInstance = null;
            }
            try {
                const instance = new Choices(selectEl, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Cari...',
                    itemSelectText: 'Tekan untuk memilih',
                    noResultsText: 'Tidak ada hasil ditemukan',
                    noChoicesText: 'Tidak ada pilihan tersedia',
                    placeholder: true,
                    placeholderValue: 'Pilih...'
                });
                selectEl.choicesInstance = instance;
            } catch (e) {}
        }

        function populateProdukOptions() {
            const kategoriSelect = document.querySelector('select.kategori-produk-select');
            const produkSelect = document.querySelector('select.produk-select');
            if (!kategoriSelect || !produkSelect) return;

            const kategori = (kategoriSelect.value || '').toString();
            const raw = produkByKategori ? produkByKategori[kategori] : null;
            const items = Array.isArray(raw) ? raw : (raw ? Object.values(raw) : []);

            const desiredProdukId = (kategoriSelect.dataset && kategoriSelect.dataset.desiredProduk)
                ? String(kategoriSelect.dataset.desiredProduk)
                : (produkSelect.value ? String(produkSelect.value) : (oldProdukId ? String(oldProdukId) : ''));

            if (produkSelect.choicesInstance) {
                try { produkSelect.choicesInstance.destroy(); } catch (e) {}
                produkSelect.choicesInstance = null;
            }

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
        }

        function applyProdukMeta() {
            const produkSelect = document.querySelector('select.produk-select');
            const idChemicalHidden = document.querySelector('.id-chemical-hidden');
            const idProdusenHidden = document.querySelector('.id-produsen-hidden');
            const idDistributorHidden = document.querySelector('.id-distributor-hidden');
            const produsenBadges = document.querySelector('.produsen-badges');
            const distributorBadges = document.querySelector('.distributor-badges');
            if (!produkSelect || !idChemicalHidden || !idProdusenHidden || !idDistributorHidden || !produsenBadges || !distributorBadges) return;

            const produkId = (produkSelect.value || '').toString();
            const selectedName = (produkSelect.selectedOptions && produkSelect.selectedOptions[0])
                ? String(produkSelect.selectedOptions[0].textContent || '')
                : '';

            const mappedByProduk = chemicalByProdukId ? chemicalByProdukId[produkId] : null;
            if (mappedByProduk) {
                idChemicalHidden.value = String(mappedByProduk);
            } else {
                const chemicalKey = selectedName ? selectedName.trim().toLowerCase() : '';
                const mappedChemicalId = (chemicalKey && chemicalByName) ? chemicalByName[chemicalKey] : null;
                idChemicalHidden.value = mappedChemicalId ? String(mappedChemicalId) : '';
            }

            const meta = produkMeta ? produkMeta[produkId] : null;
            const produsenIds = meta && meta.produsen_ids ? meta.produsen_ids : [];
            const distributorIds = meta && meta.distributor_ids ? meta.distributor_ids : [];
            const produsenNames = meta && meta.produsen_names ? meta.produsen_names : [];
            const distributorNames = meta && meta.distributor_names ? meta.distributor_names : [];

            idProdusenHidden.value = (Array.isArray(produsenIds) && produsenIds.length > 0) ? String(produsenIds[0]) : '';
            idDistributorHidden.value = (Array.isArray(distributorIds) && distributorIds.length > 0) ? String(distributorIds[0]) : '';

            const renderBadges = (containerEl, values, badgeClass) => {
                if (!Array.isArray(values) || values.length === 0) {
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

            renderBadges(produsenBadges, produsenNames, 'badge bg-light-primary text-primary');
            renderBadges(distributorBadges, distributorNames, 'badge bg-light-info text-info');
        }

        document.addEventListener('change', function(e) {
            const target = e.target;
            if (target && target.matches('select.kategori-produk-select')) {
                if (target.dataset) {
                    target.dataset.desiredProduk = '';
                }
                const produkSelect = document.querySelector('select.produk-select');
                if (produkSelect) {
                    if (produkSelect.choicesInstance) {
                        try { produkSelect.choicesInstance.destroy(); } catch (e) {}
                        produkSelect.choicesInstance = null;
                    }
                    produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
                }
                populateProdukOptions();
                applyProdukMeta();
            }
            if (target && target.matches('select.produk-select')) {
                const kategoriSelect = document.querySelector('select.kategori-produk-select');
                if (kategoriSelect && kategoriSelect.dataset) {
                    kategoriSelect.dataset.desiredProduk = (target.value || '').toString();
                }
                applyProdukMeta();
            }
        });

        populateProdukOptions();
        applyProdukMeta();
    });
</script>
@endsection
