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
                    <h3>Tambah Detail Komplain</h3>
                    <p class="text-subtitle text-muted">Form input detail komplain produk</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('detail-komplain.index') }}">Detail Komplain</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Form Input Detail Komplain</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('detail-komplain.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- SECTION 1: INFORMASI SUPPLIER & PENGIRIMAN -->
                        <h6 class="mb-3 mt-4"><strong>Informasi Supplier & Pengiriman</strong></h6>
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_supplier" class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" 
                                           id="nama_supplier" name="nama_supplier" value="{{ old('nama_supplier') }}" required>
                                    @error('nama_supplier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_kedatangan" class="form-label">Tanggal Kedatangan <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_kedatangan') is-invalid @enderror" 
                                           id="tanggal_kedatangan" name="tanggal_kedatangan" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                    @error('tanggal_kedatangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_shift">Shift <span class="text-danger">*</span></label>
                                <select class="form-select @error('id_shift') is-invalid @enderror" 
                                        id="id_shift" 
                                        name="id_shift" 
                                        required>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_po" class="form-label">No. PO <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_po') is-invalid @enderror" 
                                           id="no_po" name="no_po" value="{{ old('no_po') }}" required>
                                    @error('no_po')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: INFORMASI PRODUK -->
                        <h6 class="mb-3 mt-4"><strong>Informasi Produk</strong></h6>
                        <hr>

                        <div id="produk-items">
                            <div class="produk-item border rounded p-3 mb-3" style="background-color: #f8f9fa;" data-index="0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold produk-item-title">Produk #1</span>
                                    <button type="button" class="btn btn-danger btn-sm remove-produk-item" style="display:none;">
                                        <i class="bi bi-trash"></i> Hapus Produk
                                    </button>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                            <select class="choices form-control kategori-produk-select" name="kategori_code[]" required>
                                                <option value="">Pilih Kategori</option>
                                                @foreach(($produkKategoriOptions ?? []) as $kategori)
                                                    <option value="{{ $kategori }}">{{ $kategori }}</option>
                                                @endforeach
                                            </select>
                                            @error('kategori_code.0')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                            <select class="form-control produk-select" name="id_produk[]" required>
                                                <option value="">Pilih Produk</option>
                                                @foreach($produks as $produk)
                                                    <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_produk.0')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Kode Produksi <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="kode_produksi[]" required>
                                            @error('kode_produksi.0')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Expired Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="expired_date[]" required>
                                            @error('expired_date.0')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <h6 class="mb-3 mt-4"><strong>Jumlah Barang</strong></h6>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah Datang (Kg/Bal/Zak) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="jumlah_datang[]" required>
                                            @error('jumlah_datang.0')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah Di Tolak (Kg/Bal/Zak) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="jumlah_di_tolak[]" required>
                                            @error('jumlah_di_tolak.0')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <h6 class="mb-3 mt-4"><strong>Dokumentasi</strong></h6>
                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Dokumentasi Komplain <span class="text-muted">(Gambar, Max 1MB)</span></label>
                                            <input type="file" class="form-control" name="dokumentasi[]" accept="image/*" capture="camera">
                                            <small class="text-muted">Bukti komplain: foto, scan dokumen, dll</small>
                                            @error('dokumentasi.0')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <h6 class="mb-3 mt-4"><strong>Catatan & Approval</strong></h6>
                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <textarea class="form-control" name="keterangan[]" rows="3"></textarea>
                                            @error('keterangan.0')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Di Buat Oleh</label>
                                            <input type="text" class="form-control" name="di_buat_oleh[]" placeholder="Nama/Inisial">
                                            @error('di_buat_oleh.0')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Setujui Oleh</label>
                                            <input type="text" class="form-control" name="setujui_oleh[]" placeholder="Nama/Inisial">
                                            @error('setujui_oleh.0')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-success btn-sm" id="add-produk-item">
                                    <i class="bi bi-plus"></i> Tambah Produk
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <a href="{{ route('detail-komplain.index') }}" class="btn btn-secondary btn-kembali-confirm">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Komplain</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
        el.addEventListener('click', function(e) {
            const ok = confirm('Data belum disimpan. Yakin ingin kembali ke halaman index?');
            if (!ok) e.preventDefault();
        });
    });

    const produkByKategori = @json($produkByKategori ?? []);

    const flattenAllProduk = () => {
        const all = [];
        Object.keys(produkByKategori || {}).forEach((k) => {
            const raw = produkByKategori[k];
            const arr = Array.isArray(raw) ? raw : Object.values(raw || {});
            arr.forEach((p) => all.push(p));
        });
        const seen = new Set();
        return all.filter((p) => {
            const id = p && p.id !== undefined ? String(p.id) : '';
            if (!id || seen.has(id)) return false;
            seen.add(id);
            return true;
        });
    };

    const ensureChoices = (selectEl) => {
        if (!selectEl) return null;

        if (typeof Choices === 'undefined') return null;
        if (selectEl.dataset && selectEl.dataset.choicesInitialized === 'true' && selectEl._choices) {
            return selectEl._choices;
        }
        if (selectEl._choices && typeof selectEl._choices.destroy === 'function') {
            try { selectEl._choices.destroy(); } catch (e) {}
        }
        try {
            selectEl._choices = new Choices(selectEl, {
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
            if (selectEl.dataset) selectEl.dataset.choicesInitialized = 'true';
        } catch (e) {
            selectEl._choices = null;
        }
        return selectEl._choices;
    };

    const populateProdukForItem = (itemEl) => {
        if (!itemEl) return;
        const kategoriSelect = itemEl.querySelector('select.kategori-produk-select, select[name="kategori_code[]"]');
        const produkSelect = itemEl.querySelector('select.produk-select, select[name="id_produk[]"]');
        if (!produkSelect) return;

        const current = String(produkSelect.value || '');
        const kategori = kategoriSelect ? String(kategoriSelect.value || '') : '';

        let options = [];
        if (kategori && produkByKategori && produkByKategori[kategori]) {
            const raw = produkByKategori[kategori];
            options = Array.isArray(raw) ? raw : Object.values(raw || {});
        } else {
            options = flattenAllProduk();
        }

        const choiceItems = [{ value: '', label: '-- Pilih Produk --', selected: true, disabled: false }].concat(
            options.map((p) => {
                const id = p && p.id !== undefined ? String(p.id) : '';
                const nama = p && p.nama !== undefined ? String(p.nama) : '';
                return { value: id, label: nama, selected: false, disabled: false };
            }).filter((it) => it.value !== '' && it.label !== '')
        );

        const produkChoices = produkSelect._choices || ensureChoices(produkSelect);
        if (produkChoices && typeof produkChoices.setChoices === 'function') {
            try {
                produkChoices.clearChoices();
                produkChoices.setChoices(choiceItems, 'value', 'label', true);
                if (current) {
                    try { produkChoices.setChoiceByValue(current); } catch (e) {}
                }
                return;
            } catch (e) {
            }
        }

        // Fallback without Choices
        while (produkSelect.options.length > 0) {
            produkSelect.remove(0);
        }
        produkSelect.add(new Option('-- Pilih Produk --', ''));
        choiceItems.slice(1).forEach((it) => {
            produkSelect.add(new Option(it.label, it.value));
        });
        if (current) {
            produkSelect.value = current;
        }
    };

    const updateProdukItemTitles = () => {
        const items = document.querySelectorAll('#produk-items .produk-item');
        items.forEach((item, idx) => {
            item.dataset.index = String(idx);
            const title = item.querySelector('.produk-item-title');
            if (title) title.textContent = 'Produk #' + (idx + 1);
            const btn = item.querySelector('.remove-produk-item');
            if (btn) btn.style.display = items.length > 1 ? '' : 'none';
        });
    };

    const initProdukItem = (itemEl) => {
        const kategoriSelect = itemEl.querySelector('select.kategori-produk-select, select[name="kategori_code[]"]');
        const produkSelect = itemEl.querySelector('select.produk-select, select[name="id_produk[]"]');

        if (kategoriSelect) {
            // Hapus event listener lama jika ada
            const oldChangeHandler = kategoriSelect._changeHandler;
            if (oldChangeHandler) {
                kategoriSelect.removeEventListener('change', oldChangeHandler);
            }
            
            // Buat handler baru dan simpan referensinya
            kategoriSelect._changeHandler = () => populateProdukForItem(itemEl);
            
            // Inisialisasi Choices.js
            ensureChoices(kategoriSelect);
            
            // Tambahkan event listener baru
            kategoriSelect.addEventListener('change', kategoriSelect._changeHandler);
        }
        
        if (produkSelect) {
            ensureChoices(produkSelect);
        }

        populateProdukForItem(itemEl);
    };

    const container = document.getElementById('produk-items');
    const firstItem = container ? container.querySelector('.produk-item') : null;
    const produkItemTemplate = firstItem ? firstItem.cloneNode(true) : null;

    document.querySelectorAll('#produk-items .produk-item').forEach((item) => initProdukItem(item));
    updateProdukItemTitles();

    const addBtn = document.getElementById('add-produk-item');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            if (!container || !produkItemTemplate) return;

            const clone = produkItemTemplate.cloneNode(true);

            // Reset semua input dan textarea
            clone.querySelectorAll('input, textarea').forEach((el) => {
                el.value = '';
            });
            
            // Bersihkan semua select dari Choices.js
            clone.querySelectorAll('select').forEach((sel) => {
                // Hapus instance Choices.js lama
                try {
                    if (sel._choices && typeof sel._choices.destroy === 'function') {
                        sel._choices.destroy();
                    }
                } catch (e) {}
                
                // Reset properti Choices
                sel._choices = null;
                if (sel.dataset) {
                    delete sel.dataset.choicesInitialized;
                }
                
                // Hapus class dan attribute Choices.js
                sel.classList.remove('choices__input', 'choices__input--cloned');
                sel.removeAttribute('data-choice');
                sel.removeAttribute('aria-activedescendant');
                sel.removeAttribute('aria-expanded');
                
                // Reset value
                sel.value = '';
            });
            
            // Hapus semua wrapper Choices.js yang mungkin tersisa
            clone.querySelectorAll('.choices').forEach((choicesWrapper) => {
                const select = choicesWrapper.querySelector('select');
                if (select && choicesWrapper.parentNode) {
                    choicesWrapper.parentNode.insertBefore(select, choicesWrapper);
                    choicesWrapper.remove();
                }
            });
            
            // Reset kategori select dengan opsi baru
            const kategoriSelect = clone.querySelector('select.kategori-produk-select, select[name="kategori_code[]"]');
            if (kategoriSelect) {
                while (kategoriSelect.options.length > 0) kategoriSelect.remove(0);
                kategoriSelect.add(new Option('Pilih Kategori', ''));
                @foreach(($produkKategoriOptions ?? []) as $kategori)
                    kategoriSelect.add(new Option('{{ $kategori }}', '{{ $kategori }}'));
                @endforeach
            }
            
            // Reset produk select
            const produkSelect = clone.querySelector('select.produk-select, select[name="id_produk[]"]');
            if (produkSelect) {
                while (produkSelect.options.length > 0) produkSelect.remove(0);
                produkSelect.add(new Option('Pilih Produk', ''));
            }

            // Tambahkan clone ke container
            container.appendChild(clone);
            
            // Inisialisasi Choices.js untuk elemen baru
            initProdukItem(clone);
            updateProdukItemTitles();
        });
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-produk-item');
        if (!btn) return;
        const item = btn.closest('.produk-item');
        const container = document.getElementById('produk-items');
        if (!item || !container) return;
        const items = container.querySelectorAll('.produk-item');
        if (items.length <= 1) return;
        item.remove();
        updateProdukItemTitles();
    });

    const MAX_SIZE = 1024 * 1024;

    const isDokumentasiInput = (el) => {
        return !!(el && el.tagName === 'INPUT' && el.type === 'file' && el.name === 'dokumentasi[]');
    };

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

    async function handleChange(inputEl) {
        const file = inputEl.files && inputEl.files[0] ? inputEl.files[0] : null;
        if (!file) return;
        if (file.size <= MAX_SIZE) return;

        try {
            const compressedFile = await compressImage(file);
            const dt = new DataTransfer();
            dt.items.add(compressedFile);
            inputEl.files = dt.files;
        } catch (e) {
            inputEl.value = '';
            alert('Gagal mengkompres gambar. Silakan coba lagi.');
        }
    }


    document.addEventListener('change', function(e) {
        const target = e.target;
        if (!isDokumentasiInput(target)) return;
        handleChange(target);
    });
});
</script>
<!-- done -->
@endsection