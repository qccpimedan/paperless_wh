@extends('layouts.app')
@section('container')
<div id="main">
    <div class="page-heading">

        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Pemeriksaan Kebersihan Area</h3>
                    <p class="text-subtitle text-muted">Buat pemeriksaan kebersihan untuk satu atau beberapa area sekaligus.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kebersihan-area.index') }}">Pemeriksaan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Buat Pemeriksaan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section id="basic-horizontal-layouts">
            @if ($errors->any())
                <div class="alert alert-danger kba-alert" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Periksa kembali isian Anda:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form id="form-pemeriksaan-kebersihan-area" data-autosave="true" class="form form-horizontal"
                  action="{{ route('pemeriksaan-kebersihan-area.store') }}" method="POST">
                @csrf

                {{-- Informasi umum --}}
                <div class="card kba-card">
                    <div class="card-body">
                        <div class="kba-section-title"><i class="bi bi-calendar2-check"></i> Informasi umum</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                       name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="id_shift" class="form-label">Shift <span class="text-danger">*</span></label>
                                <select id="id_shift" class="form-select @error('id_shift') is-invalid @enderror" name="id_shift" required>
                                    <option value="">Pilih shift</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ old('id_shift') == $shift->id ? 'selected' : '' }}>{{ $shift->shift }}</option>
                                    @endforeach
                                </select>
                                @error('id_shift')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Toolbar area --}}
                <div class="kba-toolbar">
                    <div>
                        <div class="kba-section-title mb-0"><i class="bi bi-geo-alt"></i> Area yang diperiksa</div>
                        <small class="text-muted">Pilih area dan master form, lalu nilai tiap aspek.</small>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-success" id="btn-ok-all-areas">
                            <i class="bi bi-check2-all me-1"></i>Ceklis OK semua area
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-item">
                            <i class="bi bi-plus-lg me-1"></i>Tambah area
                        </button>
                    </div>
                </div>

                {{-- Repeater --}}
                <div id="repeater-container">
                    <div class="repeater-item kba-item" data-index="0">
                        <div class="kba-item-head">
                            <div class="kba-item-id">
                                <span class="kba-item-no">1</span>
                                <div class="min-w-0">
                                    <div class="kba-item-title">Area baru</div>
                                    <div class="kba-progress-wrap">
                                        <div class="kba-bar"><span class="kba-bar-fill"></span></div>
                                        <span class="kba-progress-text">Pilih master form</span>
                                    </div>
                                </div>
                            </div>
                            <div class="kba-item-actions">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-success kba-auto-btn" data-scope="all" disabled>
                                        <i class="bi bi-check2-all me-1"></i>Ceklis OK semua
                                    </button>
                                    <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split kba-auto-btn"
                                            data-bs-toggle="dropdown" aria-expanded="false" aria-label="Opsi ceklis" disabled>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button type="button" class="dropdown-item kba-auto-opt" data-scope="sebelum">Sebelum proses saja</button></li>
                                        <li><button type="button" class="dropdown-item kba-auto-opt" data-scope="saat">Saat proses saja</button></li>
                                        <li><button type="button" class="dropdown-item kba-auto-opt" data-scope="verif">Verifikasi saja</button></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><button type="button" class="dropdown-item text-danger kba-auto-opt" data-scope="clear">Kosongkan semua ceklis</button></li>
                                    </ul>
                                </div>
                                <button type="button" class="btn btn-sm btn-light border text-danger btn-remove-item d-none" title="Hapus area ini">
                                    <i class="bi bi-trash"></i><span class="d-none d-md-inline ms-1">Hapus</span>
                                </button>
                            </div>
                        </div>

                        <div class="kba-item-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Area <span class="text-danger">*</span></label>
                                    <select class="form-select area-select" name="items[0][id_area]" required>
                                        <option value="">Pilih area</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->nama_area }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Master form <span class="text-danger">*</span></label>
                                    <select class="form-select master-form-select" name="items[0][id_master_form]" required>
                                        <option value="">Pilih master form</option>
                                        @foreach($masterForms as $form)
                                            <option value="{{ $form->id }}">{{ $form->nama_form }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jam sebelum proses</label>
                                    <input type="time" class="form-control" name="items[0][jam_sebelum_proses]">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jam saat proses</label>
                                    <input type="time" class="form-control" name="items[0][jam_saat_proses]">
                                </div>
                            </div>

                            <div class="form-fields-container mt-4" style="display: none;">
                                <div class="kba-section-title"><i class="bi bi-list-check"></i> Aspek yang dinilai</div>
                                <div class="editable-fields"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aksi (menempel di bawah layar) --}}
                <div class="kba-actionbar">
                    <div class="kba-summary" id="kba-summary">1 area</div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pemeriksaan-kebersihan-area.index') }}" class="btn btn-light border btn-kembali-confirm">Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Buat semua pemeriksaan</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>

<style>
#main {
    --kba-line: var(--bs-border-color, #e3e6ec);
    --kba-soft: var(--bs-tertiary-bg, #f5f7fa);
    --kba-muted: var(--bs-secondary-color, #6c757d);
    --kba-ok: #198754;
    --kba-no: #dc3545;
}
.min-w-0 { min-width: 0; }

.kba-alert { display: flex; gap: .75rem; align-items: flex-start; border: 0; border-left: 4px solid currentColor; }
.kba-card { border: 1px solid var(--kba-line); border-radius: 10px; box-shadow: none; }
.kba-section-title { font-weight: 600; font-size: .95rem; margin-bottom: .85rem; display: flex; align-items: center; gap: .5rem; }
.kba-section-title i { color: var(--bs-primary); }
.kba-toolbar { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; margin: 1.5rem 0 1rem; }

/* Area card */
.kba-item { border: 1px solid var(--kba-line); border-radius: 10px; background: var(--bs-body-bg, #fff); margin-bottom: 1rem; overflow: hidden; }
.kba-item-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; padding: .85rem 1.1rem; background: var(--kba-soft); border-bottom: 1px solid var(--kba-line); }
.kba-item-id { display: flex; align-items: center; gap: .75rem; min-width: 0; }
.kba-item-no { flex: none; width: 2rem; height: 2rem; border-radius: 50%; background: var(--bs-primary); color: #fff; display: grid; place-items: center; font-weight: 700; font-size: .9rem; }
.kba-item-title { font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 38ch; }
.kba-item-actions { display: flex; gap: .5rem; align-items: center; }
.kba-item-body { padding: 1.1rem; }
.kba-item.is-complete { border-color: var(--kba-ok); }
.kba-item.is-complete .kba-item-no { background: var(--kba-ok); }

.kba-progress-wrap { display: flex; align-items: center; gap: .5rem; margin-top: .2rem; }
.kba-bar { width: 90px; height: 5px; border-radius: 99px; background: var(--kba-line); overflow: hidden; }
.kba-bar-fill { display: block; height: 100%; width: 0; background: var(--kba-ok); transition: width .2s; }
.kba-progress-text { font-size: .75rem; color: var(--kba-muted); white-space: nowrap; }
.kba-progress-text .no { color: var(--kba-no); font-weight: 600; }

/* Aspek */
.kba-aspek { border: 1px solid var(--kba-line); border-radius: 8px; padding: .85rem 1rem; margin-bottom: .75rem; background: var(--bs-body-bg, #fff); transition: border-color .15s, background-color .15s; }
.kba-aspek.has-no { border-color: color-mix(in srgb, var(--kba-no) 55%, transparent); background: color-mix(in srgb, var(--kba-no) 4%, var(--bs-body-bg, #fff)); }
.kba-aspek.is-missing { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, .2); }
.kba-aspek-title { display: flex; gap: .6rem; font-weight: 600; margin-bottom: .7rem; }
.kba-aspek-no { flex: none; width: 1.5rem; height: 1.5rem; border-radius: 6px; background: var(--kba-soft); color: var(--kba-muted); display: grid; place-items: center; font-size: .75rem; }
.kba-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; }
.kba-cap { display: block; font-size: .72rem; color: var(--kba-muted); margin-bottom: .25rem; }
.kba-seg { display: inline-flex; }
.kba-seg .btn { min-width: 4.4rem; }
.kba-seg .btn:first-of-type { border-top-right-radius: 0; border-bottom-right-radius: 0; }
.kba-seg .btn:last-of-type { border-top-left-radius: 0; border-bottom-left-radius: 0; margin-left: -1px; }
.kba-note-toggle { padding: 0; font-size: .8rem; text-decoration: none; margin-top: .6rem; }
.kba-notes { display: none; margin-top: .6rem; }
.kba-aspek.notes-open .kba-notes { display: flex; gap: .75rem; }
.kba-notes > div { flex: 1; }

/* Action bar */
.kba-actionbar { position: sticky; bottom: 0; z-index: 5; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; padding: .85rem 1.1rem; margin-top: 1.25rem; background: var(--bs-body-bg, #fff); border: 1px solid var(--kba-line); border-radius: 10px; box-shadow: 0 -4px 16px rgba(0,0,0,.06); }
.kba-summary { font-size: .875rem; color: var(--kba-muted); }
.kba-summary strong { color: var(--bs-body-color); }

@media (max-width: 767px) {
    .kba-grid { grid-template-columns: 1fr; }
    .kba-aspek.notes-open .kba-notes { flex-direction: column; }
    .kba-item-actions { width: 100%; }
}
@media (prefers-reduced-motion: reduce) { .kba-bar-fill, .kba-aspek { transition: none; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-pemeriksaan-kebersihan-area');
    const container = document.getElementById('repeater-container');
    const summary = document.getElementById('kba-summary');

    const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));

    // Data master form & aspek (di-encode aman dari server)
    @php
        $masterFormsData = $masterForms->mapWithKeys(fn ($f) => [$f->id => [
            'nama'   => $f->nama_form,
            'fields' => $f->fields->map(fn ($x) => ['id' => $x->id, 'nama' => $x->field_name])->values(),
        ]]);
    @endphp
    const masterFormsData = {{ Illuminate\Support\Js::from($masterFormsData) }};

    let itemIndex = 1;

    /* ---------- Konfirmasi kembali ---------- */
    document.querySelectorAll('.btn-kembali-confirm').forEach((el) => {
        el.addEventListener('click', (e) => {
            if (!confirm('Data belum disimpan. Yakin ingin kembali ke halaman index?')) e.preventDefault();
        });
    });

    /* ---------- Render aspek ---------- */
    function seg(name, id, col, required) {
        return `
            <div class="kba-seg" role="group">
                <input type="radio" class="btn-check" name="${name}" id="${id}_ok" value="1" data-col="${col}" ${required ? 'required' : ''}>
                <label class="btn btn-sm btn-outline-success" for="${id}_ok">OK</label>
                <input type="radio" class="btn-check" name="${name}" id="${id}_no" value="0" data-col="${col}" ${required ? 'required' : ''}>
                <label class="btn btn-sm btn-outline-danger" for="${id}_no">Tidak OK</label>
            </div>`;
    }

    function renderFields(item, formId) {
        const idx = item.dataset.index;
        const box = item.querySelector('.form-fields-container');
        const target = item.querySelector('.editable-fields');
        const data = masterFormsData[formId];

        if (!data) {
            box.style.display = 'none';
            target.innerHTML = '';
            return;
        }

        if (!data.fields.length) {
            target.innerHTML = '<div class="alert alert-warning mb-0"><i class="bi bi-exclamation-triangle me-1"></i>Form ini belum memiliki item pemeriksaan. Atur di Master Form.</div>';
        } else {
            target.innerHTML = data.fields.map((f, n) => {
                const base = `items[${idx}]`;
                const dom = `f_${idx}_${f.id}`;
                return `
                <div class="kba-aspek" data-field="${f.id}">
                    <div class="kba-aspek-title"><span class="kba-aspek-no">${n + 1}</span><span>${esc(f.nama)}</span></div>
                    <div class="kba-grid">
                        <div><span class="kba-cap">Sebelum proses</span>${seg(`${base}[field_status_sebelum_${f.id}]`, `${dom}_sebelum`, 'sebelum', true)}</div>
                        <div><span class="kba-cap">Saat proses</span>${seg(`${base}[field_status_saat_${f.id}]`, `${dom}_saat`, 'saat', false)}</div>
                        <div><span class="kba-cap">Verifikasi <span class="text-danger">*</span></span>${seg(`${base}[field_verifikasi_${f.id}]`, `${dom}_verif`, 'verif', true)}</div>
                    </div>
                    <button type="button" class="btn btn-link kba-note-toggle"><i class="bi bi-chat-left-text me-1"></i>Catatan &amp; tindakan koreksi (Klik)</button>
                    <div class="kba-notes">
                        <div>
                            <label class="form-label small text-muted mb-1">Keterangan</label>
                            <textarea class="form-control form-control-sm" name="${base}[field_keterangan_${f.id}]" rows="2" placeholder="Keterangan..."></textarea>
                        </div>
                        <div>
                            <label class="form-label small text-muted mb-1">Tindakan koreksi</label>
                            <textarea class="form-control form-control-sm" name="${base}[field_tindakan_${f.id}]" rows="2" placeholder="Tindakan koreksi jika tidak OK..."></textarea>
                        </div>
                    </div>
                </div>`;
            }).join('');
        }

        box.style.display = 'block';
    }

    /* ---------- Status & progres ---------- */
    function refreshItem(item) {
        const aspeks = item.querySelectorAll('.kba-aspek');
        const total = aspeks.length;
        let done = 0, noCount = 0;

        aspeks.forEach((a) => {
            const sebelum = a.querySelector('input[data-col="sebelum"]:checked');
            const verif = a.querySelector('input[data-col="verif"]:checked');
            if (sebelum && verif) done++;

            const hasNo = a.querySelector('input[data-col]:checked[value="0"]') !== null;
            if (hasNo) noCount++;
            a.classList.toggle('has-no', hasNo);
            if (hasNo) a.classList.add('notes-open');
        });

        const fill = item.querySelector('.kba-bar-fill');
        const text = item.querySelector('.kba-progress-text');
        fill.style.width = total ? `${(done / total) * 100}%` : '0';

        if (!total) {
            text.textContent = 'Pilih master form';
        } else {
            text.innerHTML = `${done}/${total} aspek lengkap` + (noCount ? ` · <span class="no">${noCount} tidak OK</span>` : '');
        }

        item.classList.toggle('is-complete', total > 0 && done === total);
        item.querySelectorAll('.kba-auto-btn').forEach((b) => { b.disabled = total === 0; });
        refreshSummary();
    }

    function refreshSummary() {
        const items = container.querySelectorAll('.repeater-item');
        let total = 0, done = 0;
        items.forEach((item) => {
            item.querySelectorAll('.kba-aspek').forEach((a) => {
                total++;
                if (a.querySelector('input[data-col="sebelum"]:checked') && a.querySelector('input[data-col="verif"]:checked')) done++;
            });
        });
        summary.innerHTML = `<strong>${items.length}</strong> area` + (total ? ` · <strong>${done}/${total}</strong> aspek lengkap` : '');
    }

    /* ---------- Ceklis OK otomatis ---------- */
    function applyOk(item, scope) {
        const cols = scope === 'all' ? ['sebelum', 'saat', 'verif'] : [scope];

        // Konfirmasi bila ada nilai "Tidak OK" yang akan tertimpa
        let overwrite = 0;
        cols.forEach((c) => { overwrite += item.querySelectorAll(`input[data-col="${c}"]:checked[value="0"]`).length; });
        if (overwrite && !confirm(`${overwrite} penilaian "Tidak OK" akan diganti menjadi OK. Lanjutkan?`)) return false;

        cols.forEach((c) => {
            item.querySelectorAll(`input[data-col="${c}"][value="1"]`).forEach((r) => { r.checked = true; });
        });
        item.querySelectorAll('.kba-aspek').forEach((a) => a.classList.remove('is-missing'));
        refreshItem(item);
        return true;
    }

    function clearAll(item) {
        if (!confirm('Kosongkan semua ceklis di area ini?')) return;
        item.querySelectorAll('input[data-col]').forEach((r) => { r.checked = false; });
        item.querySelectorAll('.kba-aspek').forEach((a) => a.classList.remove('has-no'));
        refreshItem(item);
    }

    container.addEventListener('click', function (e) {
        const item = e.target.closest('.repeater-item');
        if (!item) return;

        const autoBtn = e.target.closest('.kba-auto-btn[data-scope], .kba-auto-opt');
        if (autoBtn) {
            const scope = autoBtn.dataset.scope;
            scope === 'clear' ? clearAll(item) : applyOk(item, scope);
            return;
        }

        const toggle = e.target.closest('.kba-note-toggle');
        if (toggle) {
            toggle.closest('.kba-aspek').classList.toggle('notes-open');
            return;
        }

        if (e.target.closest('.btn-remove-item') && container.querySelectorAll('.repeater-item').length > 1) {
            if (!confirm('Hapus area ini beserta isiannya?')) return;
            item.remove();
            renumber();
            refreshSummary();
        }
    });

    document.getElementById('btn-ok-all-areas').addEventListener('click', function () {
        const items = [...container.querySelectorAll('.repeater-item')]
            .filter((i) => i.querySelector('.kba-aspek'));
        if (!items.length) {
            alert('Pilih master form di minimal satu area terlebih dahulu.');
            return;
        }
        if (!confirm(`Ceklis OK untuk semua aspek di ${items.length} area?`)) return;
        items.forEach((i) => applyOk(i, 'all'));
    });

    /* ---------- Perubahan input ---------- */
    container.addEventListener('change', function (e) {
        const item = e.target.closest('.repeater-item');
        if (!item) return;

        if (e.target.classList.contains('master-form-select')) {
            renderFields(item, e.target.value);
            refreshItem(item);
        }

        if (e.target.classList.contains('area-select')) {
            const opt = e.target.selectedOptions[0];
            item.querySelector('.kba-item-title').textContent = e.target.value ? opt.textContent.trim() : 'Area baru';
        }

        if (e.target.matches('input[data-col]')) {
            e.target.closest('.kba-aspek').classList.remove('is-missing');
            refreshItem(item);
        }
    });

    // Tandai aspek yang belum diisi saat validasi browser gagal
    form.addEventListener('invalid', function (e) {
        const a = e.target.closest && e.target.closest('.kba-aspek');
        if (a) a.classList.add('is-missing');
    }, true);

    /* ---------- Tambah / hapus area ---------- */
    function renumber() {
        const items = container.querySelectorAll('.repeater-item');
        items.forEach((item, i) => {
            item.querySelector('.kba-item-no').textContent = i + 1;
            item.querySelector('.btn-remove-item').classList.toggle('d-none', items.length === 1);
        });
    }

    document.getElementById('btn-add-item').addEventListener('click', function () {
        const source = container.querySelector('.repeater-item');
        const clone = source.cloneNode(true);
        clone.dataset.index = itemIndex;

        clone.querySelectorAll('[name]').forEach((el) => {
            el.name = el.name.replace(/items\[\d+\]/, `items[${itemIndex}]`);
            if (el.type === 'radio' || el.type === 'checkbox') el.checked = false;
            else el.value = '';
        });

        clone.querySelector('.editable-fields').innerHTML = '';
        clone.querySelector('.form-fields-container').style.display = 'none';
        clone.querySelector('.kba-item-title').textContent = 'Area baru';
        clone.classList.remove('is-complete');

        container.appendChild(clone);
        itemIndex++;
        renumber();
        refreshItem(clone);
        clone.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    renumber();
    refreshSummary();
});
</script>
@endsection