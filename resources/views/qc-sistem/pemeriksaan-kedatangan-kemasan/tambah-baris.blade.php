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
                    <h3>Tambah Batch Pemeriksaan Kemasan</h3>
                    <p class="text-subtitle text-muted">Tambahkan baris pemeriksaan pada data yang sudah ada</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}">Pemeriksaan Kedatangan Kemasan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Batch</li>
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
                            <h4 class="card-title">Form Tambah Batch</h4>
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

                                <form class="form form-horizontal" action="{{ route('pemeriksaan-kedatangan-kemasan.tambah-baris.store', $pemeriksaanKedatanganKemasan->uuid) }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="unified-row mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
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
                                                        <input type="text" class="form-control @error('kode_produksi.0') is-invalid @enderror" name="kode_produksi[]" value="{{ old('kode_produksi.0') }}" placeholder="Kode Produksi">
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
                                                        <input type="text" class="form-control @error('jumlah_datang.0') is-invalid @enderror" name="jumlah_datang[]" value="{{ old('jumlah_datang.0') }}" placeholder="Jumlah Datang">
                                                        @error('jumlah_datang.0')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Jumlah Sampling (pcs/kg/roll)</label>
                                                        <input type="text" class="form-control @error('jumlah_sampling.0') is-invalid @enderror" name="jumlah_sampling[]" value="{{ old('jumlah_sampling.0') }}" placeholder="Jumlah Sampling">
                                                        @error('jumlah_sampling.0')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Spesifikasi</label>
                                                        <textarea class="form-control @error('spesifikasi.0') is-invalid @enderror" name="spesifikasi[]" rows="2" placeholder="Spesifikasi">{{ old('spesifikasi.0') }}</textarea>
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
                                                        <label class="form-label">Gambar Kemasan (Max 1MB)</label>
                                                        <input type="file" name="image_kemasan[]" class="form-control image-kemasan-input" accept="image/*" capture="camera">
                                                        @error('image_kemasan.0')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 d-flex justify-content-end mt-3">
                                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" class="btn btn-light-secondary me-1 mb-1">Kembali</a>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan Batch</button>
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
    const produkByKategori = @json($produkByKategori ?? []);
    const produkMeta = @json($produkMeta ?? []);
    const oldKategoriCodes = @json(old('kategori_code', []));
    const oldProdukIds = @json(old('id_produk', []));

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

    initializeAllChoices();

    function populateProdukOptionsForRow(rowEl) {
        const kategoriSelect = rowEl.querySelector('select.kategori-produk-select');
        const produkSelect = rowEl.querySelector('select.produk-select');

        if (!kategoriSelect || !produkSelect) return;

        const kategori = (kategoriSelect.value || '').toString();
        const raw = produkByKategori ? produkByKategori[kategori] : null;
        const items = Array.isArray(raw) ? raw : (raw ? Object.values(raw) : []);

        const desiredProdukId = (produkSelect.dataset && produkSelect.dataset.desiredValue) ? String(produkSelect.dataset.desiredValue) : '';

        const existingChoices = choicesInstances.get(produkSelect);
        if (existingChoices) {
            try {
                existingChoices.destroy();
            } catch (e) {
            }
            choicesInstances.delete(produkSelect);
            delete produkSelect.dataset.choicesInitialized;
        }

        produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
        items.forEach(p => {
            const opt = document.createElement('option');
            opt.value = String(p.id);
            opt.textContent = String(p.nama);
            if (desiredProdukId && String(p.id) === desiredProdukId) {
                opt.selected = true;
            }
            produkSelect.appendChild(opt);
        });

        if (!produkSelect.dataset.choicesInitialized) {
            const instance = new Choices(produkSelect, {
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
            choicesInstances.set(produkSelect, instance);
            produkSelect.dataset.choicesInitialized = 'true';
        }

        setTimeout(() => {
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

        const rowIndex = '0';

        const produkId = (produkSelect.value || '').toString();
        const meta = produkMeta ? produkMeta[produkId] : null;
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

    document.querySelectorAll('.unified-row').forEach((row, idx) => {
        const kategoriSelect = row.querySelector('select.kategori-produk-select');
        const produkSelect = row.querySelector('select.produk-select');

        if (kategoriSelect) {
            const desiredKategori = (oldKategoriCodes && oldKategoriCodes[idx]) ? String(oldKategoriCodes[idx]) : '';
            if (desiredKategori && !kategoriSelect.value) {
                kategoriSelect.value = desiredKategori;
            }
        }

        if (produkSelect) {
            const desiredProduk = (oldProdukIds && oldProdukIds[idx]) ? String(oldProdukIds[idx]) : '';
            if (desiredProduk) {
                produkSelect.dataset.desiredValue = desiredProduk;
            }
        }

        if (kategoriSelect && kategoriSelect.value) {
            populateProdukOptionsForRow(row);
        } else if (produkSelect && produkSelect.value) {
            applyProdukMetaForRow(row);
        }
    });

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
