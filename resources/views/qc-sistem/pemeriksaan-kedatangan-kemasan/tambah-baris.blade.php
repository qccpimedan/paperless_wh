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
                    <h3>Tambah Baris Pemeriksaan Kemasan</h3>
                    <p class="text-subtitle text-muted">Tambahkan baris pemeriksaan pada data yang sudah ada</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}">Pemeriksaan Kedatangan Kemasan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Baris</li>
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
                            <h4 class="card-title">Form Tambah Baris</h4>
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
                                                        <label class="form-label">Bahan Kemasan</label>
                                                        <select class="choices form-control bahan-kemasan-select @error('id_bahan.0') is-invalid @enderror" name="id_bahan[]">
                                                            <option value="">Pilih Bahan</option>
                                                            @foreach($bahanKemasans as $bahanKemasan)
                                                                <option value="{{ $bahanKemasan->id }}" {{ old('id_bahan.0') == $bahanKemasan->id ? 'selected' : '' }}>
                                                                    {{ $bahanKemasan->nama_kemasan }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_bahan.0')
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
                                                    <div class="form-group">
                                                        <label class="form-label">Produsen</label>
                                                        <select class="choices form-control produsen-select @error('produsen.0') is-invalid @enderror" name="produsen[]">
                                                            <option value="">Pilih Produsen</option>
                                                            @foreach ($produsens as $produsen)
                                                                <option value="{{ $produsen->nama_produsen }}" {{ old('produsen.0') == $produsen->nama_produsen ? 'selected' : '' }}>
                                                                    {{ $produsen->nama_produsen }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('produsen.0')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Distributor</label>
                                                        <select class="choices form-control distributor-select @error('distributor.0') is-invalid @enderror" name="distributor[]">
                                                            <option value="">Pilih Distributor</option>
                                                            @foreach ($distributors as $distributor)
                                                                <option value="{{ $distributor->nama_distributor }}" {{ old('distributor.0') == $distributor->nama_distributor ? 'selected' : '' }}>
                                                                    {{ $distributor->nama_distributor }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('distributor.0')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
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
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan Baris</button>
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
    const bahanKemasanMeta = @json($bahanKemasanMeta ?? []);

    function applyBahanKemasanMetaForRow(rowEl) {
        const bahanSelect = rowEl.querySelector('select.bahan-kemasan-select');
        const produsenSelect = rowEl.querySelector('select.produsen-select');
        const distributorSelect = rowEl.querySelector('select.distributor-select');

        if (!bahanSelect || !produsenSelect || !distributorSelect) return;

        const bahanId = bahanSelect.value;
        const meta = bahanKemasanMeta[bahanId];
        if (!meta) return;

        const produsenChoices = choicesInstances.get(produsenSelect);
        if (produsenChoices) {
            produsenChoices.setChoiceByValue(meta.produsen || '');
        } else {
            produsenSelect.value = meta.produsen || '';
        }

        const distributorChoices = choicesInstances.get(distributorSelect);
        if (distributorChoices) {
            distributorChoices.setChoiceByValue(meta.distributor || '');
        } else {
            distributorSelect.value = meta.distributor || '';
        }
    }

    document.addEventListener('change', function(e) {
        const target = e.target;
        if (target && target.matches('select.bahan-kemasan-select')) {
            const row = target.closest('.unified-row');
            if (row) {
                applyBahanKemasanMetaForRow(row);
            }
        }
    });

    const selects = document.querySelectorAll('select.choices');
    selects.forEach(select => {
        if (!select.dataset.choicesInitialized) {
            const instance = new Choices(select, {
                searchEnabled: true,
                searchPlaceholderValue: 'Cari...',
                itemSelectText: 'Tekan untuk memilih',
                noResultsText: 'Tidak ada hasil ditemukan',
                noChoicesText: 'Tidak ada pilihan tersedia',
                placeholder: true,
                placeholderValue: 'Pilih...'
            });
            choicesInstances.set(select, instance);
            select.dataset.choicesInitialized = 'true';
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
