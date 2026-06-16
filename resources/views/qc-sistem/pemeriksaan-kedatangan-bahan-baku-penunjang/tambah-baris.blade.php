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
                    <h3>Tambah Batch - Pemeriksaan Bahan Baku Penunjang</h3>
                    <p class="text-subtitle text-muted">Tambahkan 1 baris detail produk ke data yang sudah ada</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-bahan-baku.index') }}">Pemeriksaan Bahan Baku Penunjang</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Batch</li>
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
                                    <label class="form-label">Kategori</label>
                                    @php
                                        $selectedProdukId = old('id_bahan', '');
                                        $selectedKategori = old('kategori_code', '');
                                        if (($selectedKategori === null || $selectedKategori === '') && $selectedProdukId) {
                                            $selectedKategori = $produkKategoriById[$selectedProdukId] ?? '';
                                        }
                                    @endphp
                                    <select class="choices form-control kategori-produk-select" name="kategori_code">
                                        <option value="">Pilih Kategori</option>
                                        @foreach(($produkKategoriOptions ?? []) as $kategori)
                                            <option value="{{ $kategori }}" {{ $selectedKategori == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Produk</label>
                                    <select class="form-control produk-select" name="id_bahan" data-selected="{{ old('id_bahan', '') }}">
                                        <option value="">Pilih Produk</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-3">
                                        <div class="fw-semibold">Distributor</div>
                                        <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                        <div class="distributor-badges d-flex flex-wrap gap-1">
                                            @php
                                                $oldDistributor = old('distributor', []);
                                                $oldDistributor = is_array($oldDistributor) ? $oldDistributor : [$oldDistributor];
                                                $oldDistributor = array_values(array_filter($oldDistributor, fn ($v) => $v !== null && $v !== ''));
                                            @endphp
                                            @forelse ($oldDistributor as $d)
                                                <span class="badge bg-light-info text-info">{{ $d }}</span>
                                            @empty
                                                <span class="text-muted small">-</span>
                                            @endforelse
                                        </div>
                                        <div class="distributor-hidden-inputs">
                                            @foreach ($oldDistributor as $d)
                                                <input type="hidden" name="distributor[]" value="{{ $d }}">
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
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
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-3">
                                        <div class="fw-semibold">Produsen</div>
                                        <div class="small text-muted mb-2">Otomatis terisi sesuai Produk yang dipilih</div>
                                        <div class="produsen-badges d-flex flex-wrap gap-1">
                                            @php
                                                $oldProdusen = old('produsen', []);
                                                $oldProdusen = is_array($oldProdusen) ? $oldProdusen : [$oldProdusen];
                                                $oldProdusen = array_values(array_filter($oldProdusen, fn ($v) => $v !== null && $v !== ''));
                                            @endphp
                                            @forelse ($oldProdusen as $p)
                                                <span class="badge bg-light-primary text-primary">{{ $p }}</span>
                                            @empty
                                                <span class="text-muted small">-</span>
                                            @endforelse
                                        </div>
                                        <div class="produsen-hidden-inputs">
                                            @foreach ($oldProdusen as $p)
                                                <input type="hidden" name="produsen[]" value="{{ $p }}">
                                            @endforeach
                                        </div>
                                    </div>
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

                        <div class="form-section mb-3 coa-upload-section">
                            <h6 class="text-primary mb-2">Upload COA</h6>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label d-block">Tipe File</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input coa-type-pdf" type="radio" name="coa_type" value="pdf" checked>
                                            <label class="form-check-label">PDF</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input coa-type-img" type="radio" name="coa_type" value="gambar">
                                            <label class="form-check-label">Gambar</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row coa-pdf-input">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">File COA (PDF)</label>
                                        <input type="file" name="file_coa" class="form-control" accept="application/pdf">
                                        <small class="form-text text-muted">Format: PDF. Maksimal 1MB.</small>
                                        @error('file_coa')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row coa-img-input" style="display:none;">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Foto COA</label>
                                        <input type="file" name="file_coa_img" class="form-control" accept="image/*" capture="environment">
                                        <small class="form-text text-muted">Format: JPG, PNG, WEBP, GIF. Maksimal 1MB. Gambar &gt; 3MB akan dikompres otomatis.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3">
                            <h6 class="text-primary mb-2">Upload Gambar</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Foto Bahan Baku (Max 1MB)</label>
                                        <input type="file" name="image_bahan_baku" class="form-control" accept="image/*" capture="camera">
                                        @error('image_bahan_baku')
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

        const kategoriSelect = document.querySelector('select.kategori-produk-select');
        const produkSelect = document.querySelector('select.produk-select');
        const oldProdukId = @json(old('id_bahan', ''));

        try {
            if (typeof window.Choices !== 'undefined') {
                document.querySelectorAll('.choices').forEach(function (element) {
                    new Choices(element, {
                        searchResultLimit: 100,
                    searchFuzziness: 0.000001,
                    fuseOptions: { ignoreLocation: true, threshold: 0.2, matchAllTokens: false },
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
                selectEl.choicesInstance = instance;
            } catch (e) {}
        }

        const populateProdukOptions = function(kategoriCode) {
            if (!produkSelect) return;

            const kategori = (kategoriCode || '').toString();
            const raw = produkByKategori ? produkByKategori[kategori] : null;
            const items = Array.isArray(raw) ? raw : (raw ? Object.values(raw) : []);

            const desiredProdukId = (kategoriSelect && kategoriSelect.dataset && kategoriSelect.dataset.desiredProduk)
                ? String(kategoriSelect.dataset.desiredProduk)
                : (produkSelect.value ? String(produkSelect.value) : (oldProdukId ? String(oldProdukId) : (produkSelect.getAttribute('data-selected') ? String(produkSelect.getAttribute('data-selected')) : '')));

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
        };

        const applyProdukMeta = function() {
            if (!produkSelect) return;
            const produkId = produkSelect.value;
            const meta = (produkId && produkMeta) ? (produkMeta[produkId] || null) : null;
            const produsenVals = meta && Array.isArray(meta.produsen) ? meta.produsen : [];
            const distributorVals = meta && Array.isArray(meta.distributor) ? meta.distributor : [];

            const produsenBadges = document.querySelector('.produsen-badges');
            const produsenInputs = document.querySelector('.produsen-hidden-inputs');
            const distributorBadges = document.querySelector('.distributor-badges');
            const distributorInputs = document.querySelector('.distributor-hidden-inputs');

            if (produsenBadges && produsenInputs) {
                produsenBadges.innerHTML = '';
                produsenInputs.innerHTML = '';
                const safe = Array.isArray(produsenVals) ? produsenVals.filter(v => v && String(v).trim() !== '') : [];
                if (!safe.length) {
                    const span = document.createElement('span');
                    span.className = 'text-muted small';
                    span.textContent = '-';
                    produsenBadges.appendChild(span);
                } else {
                    safe.forEach(function(v) {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-light-primary text-primary';
                        badge.textContent = v;
                        produsenBadges.appendChild(badge);

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'produsen[]';
                        input.value = v;
                        produsenInputs.appendChild(input);
                    });
                }
            }

            if (distributorBadges && distributorInputs) {
                distributorBadges.innerHTML = '';
                distributorInputs.innerHTML = '';
                const safe = Array.isArray(distributorVals) ? distributorVals.filter(v => v && String(v).trim() !== '') : [];
                if (!safe.length) {
                    const span = document.createElement('span');
                    span.className = 'text-muted small';
                    span.textContent = '-';
                    distributorBadges.appendChild(span);
                } else {
                    safe.forEach(function(v) {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-light-info text-info';
                        badge.textContent = v;
                        distributorBadges.appendChild(badge);

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'distributor[]';
                        input.value = v;
                        distributorInputs.appendChild(input);
                    });
                }
            }
        };

        document.addEventListener('change', function(e) {
            const target = e.target;
            if (!target) return;

            if (target && target.matches('select.kategori-produk-select')) {
                if (target.dataset) {
                    target.dataset.desiredProduk = '';
                }
                if (produkSelect) {
                    if (produkSelect.choicesInstance) {
                        try { produkSelect.choicesInstance.destroy(); } catch (e) {}
                        produkSelect.choicesInstance = null;
                    }
                    produkSelect.innerHTML = '<option value="">Pilih Produk</option>';
                }
                populateProdukOptions(target.value);
                applyProdukMeta();
            }

            if (target && target.matches('select.produk-select')) {
                if (kategoriSelect && kategoriSelect.dataset) {
                    kategoriSelect.dataset.desiredProduk = (target.value || '').toString();
                }
                applyProdukMeta();
            }
        });

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

        // Init dependent selects + badges
        populateProdukOptions(kategoriSelect ? kategoriSelect.value : '');
        applyProdukMeta();
    });

    // COA type toggle: PDF / Gambar
    document.querySelectorAll('.coa-type-pdf').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const section = this.closest('.coa-upload-section');
            if (!section) return;
            section.querySelector('.coa-pdf-input').style.display = '';
            section.querySelector('.coa-img-input').style.display = 'none';
            section.querySelector('.coa-img-input input[type="file"]').value = '';
        });
    });
    document.querySelectorAll('.coa-type-img').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const section = this.closest('.coa-upload-section');
            if (!section) return;
            section.querySelector('.coa-pdf-input').style.display = 'none';
            section.querySelector('.coa-img-input').style.display = '';
            section.querySelector('.coa-pdf-input input[type="file"]').value = '';
        });
    });

    // Before submit: disable hidden COA file input + radio buttons
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
                    section.querySelectorAll('input[type="radio"][name^="coa_type"]').forEach(function(r) { r.disabled = true; });
                });
            }, true);
        }
    } catch(err) {}
</script>
@endsection
