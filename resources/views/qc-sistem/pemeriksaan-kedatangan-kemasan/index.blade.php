@extends('layouts.app')
@section('container')
@php
    // Get shifts for filter dropdown
    $user = \Illuminate\Support\Facades\Auth::user();
    if ($user && $user->role && strtolower($user->role->role) === 'superadmin') {
        $shifts = \App\Models\Shift::all();
    } else {
        $shifts = \App\Models\Shift::query()
            ->when($user && $user->id_plant, function ($q) use ($user) {
                $q->whereHas('user', function ($qu) use ($user) {
                    $qu->where('id_plant', $user->id_plant);
                });
            })
            ->get();
    }
    $userRole = auth()->user()->role ? strtolower(auth()->user()->role->role) : null;
    $canVerify = in_array($userRole, ['qc inspector', 'warehouse', 'produksi', 'spv qc', 'superadmin']);
@endphp
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
                    <h3>Pemeriksaan Kedatangan Kemasan</h3>
                    <p class="text-subtitle text-muted">Kelola data pemeriksaan kedatangan kemasan</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pemeriksaan Kedatangan Kemasan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Pemeriksaan Kedatangan Kemasan</h5>
                    <div class="d-flex gap-2">
                        @if($canVerify)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#multiApprovalModal">
                                <i class="bi bi-patch-check"></i> Multi Approval
                            </button>
                        @endif
                        @can('create_pemeriksaan_kedatangan_kemasan')
                            <a href="{{ route('pemeriksaan-kedatangan-kemasan.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Pemeriksaan
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4 p-3 bg-light rounded">
                        <div class="col-md-12 mb-3"><h6><i class="bi bi-funnel"></i> Filter & Verifikasi Massal</h6></div>
                        <form action="{{ route('pemeriksaan-kedatangan-kemasan.export-pdf') }}" method="GET" class="row g-3" id="filterForm">
                            <div class="col-md-3">
                                <label class="form-label">Shift</label>
                                <select name="id_shift" class="form-select" id="shiftSelect">
                                    <option value="">-- Pilih Shift --</option>
                                    <option value="all" {{ request('id_shift') == 'all' ? 'selected' : '' }}>📋 Semua Shift</option>
                                    @foreach($shifts ?? [] as $shift)
                                        <option value="{{ $shift->id }}" data-shift-name="{{ $shift->shift }}" data-is-date-range="{{ $shift->is_date_range }}" {{ request('id_shift') == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->shift }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3" id="tanggalDariWrapper">
                                <label class="form-label">Tanggal Dari</label>
                                <input type="date" name="tanggal_dari" class="form-control" id="tanggalDari" value="{{ request('tanggal_dari') }}">
                            </div>
                            <div class="col-md-3" id="tanggalSampaiWrapper">
                                <label class="form-label">Tanggal Sampai</label>
                                <input type="date" name="tanggal_sampai" class="form-control" id="tanggalSampai" value="{{ request('tanggal_sampai') }}">
                            </div>
                            <div class="col-md-3" id="tanggalSingleWrapper" style="display: none;">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" id="tanggalSingle" value="{{ request('tanggal') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_code" class="form-select kategori-produk-select" id="filterKategori">
                                    <option value="">-- Semua Kategori --</option>
                                    @if(isset($produkKategoriOptions))
                                        @foreach($produkKategoriOptions as $kategori)
                                            <option value="{{ $kategori }}" {{ request("kategori_code") == $kategori ? "selected" : "" }}>{{ $kategori }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nama Produk</label>
                                <select name="id_produk" class="form-select produk-select" id="filterProduk" data-selected="{{ request('id_produk', '') }}">
                                    <option value="">-- Semua Produk --</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100"><i class="bi bi-file-pdf"></i> PDF</button>
                            </div>
                        </form>
                    </div>

                    <form action="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" method="GET" class="row g-3 mb-3">
                        <div class="col-md-9"><input type="text" name="search" class="form-control" placeholder="Cari Bahan atau Kode Produksi..." value="{{ request('search') }}"></div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">Cari Data</button>
                            <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </form>

                    <form id="batchActionForm" action="{{ route('pemeriksaan-kedatangan-kemasan.batch-verify') }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped text-center" style="white-space: nowrap;">
                                <thead>
                                    <tr>
                                        <th>
                                            @if($canVerify)
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                            @else
                                                <i class="bi bi-check-square"></i>
                                            @endif
                                        </th>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Shift</th>
                                        <th>Plant</th>
                                        <th>Bahan Kemasan</th>
                                        <th>Kode Produksi</th>
                                        <th>Verifikasi</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pemeriksaans as $index => $pemeriksaan)
                                        @php
                                            $statusRow = $pemeriksaan->status_verifikasi ?? 'pending';
                                            $canThisRowBeVerified = false;
                                            if ($userRole === 'qc inspector' && ($statusRow === 'pending' || $statusRow === null)) { $canThisRowBeVerified = true; }
                                            elseif ($userRole === 'produksi' && $statusRow === 'sent_to_produksi') { $canThisRowBeVerified = true; }
                                            elseif (($userRole === 'spv qc' || $userRole === 'superadmin') && $statusRow === 'approved_produksi') { $canThisRowBeVerified = true; }
                                        @endphp
                                        <tr>
                                            <td>
                                                @if($canThisRowBeVerified)
                                                    <input type="checkbox" name="selected_uuids[]" value="{{ $pemeriksaan->uuid }}" class="form-check-input row-checkbox">
                                                @else
                                                    <i class="bi bi-dash text-muted"></i>
                                                @endif
                                            </td>
                                            <td>{{ ($pemeriksaans->firstItem() ?? 1) + $index }}</td>
                                            <td><strong>{{ $pemeriksaan->tanggal->format('d/m/Y') }}</strong></td>
                                            <td><span class="badge bg-primary">{{ $pemeriksaan->shift->shift ?? 'No Shift' }}</span></td>
                                            <td><span class="badge bg-primary">{{ $pemeriksaan->user->plant->plant ?? 'No Plant' }}</span></td>
                                            <td>
                                                @if($pemeriksaan->bahan) <span class="badge bg-info">{{ $pemeriksaan->bahan->nama_bahan }}</span>
                                                @else
                                                    @php
                                                        $idBahanArray = json_decode($pemeriksaan->id_bahan_array ?? '[]', true);
                                                        $namaBahanArray = array_values(array_filter(array_map(function ($id) use ($produkNamaById) {
                                                            return $produkNamaById[$id] ?? null;
                                                        }, is_array($idBahanArray) ? $idBahanArray : [])));
                                                    @endphp
                                                    @if(count($namaBahanArray) > 0)
                                                        <span class="badge bg-info">{{ $namaBahanArray[0] }}</span>
                                                        @if(count($namaBahanArray) > 1) <br><small>+{{ count($namaBahanArray)-1 }} lainnya</small> @endif
                                                    @else - @endif
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $kodeArray = json_decode($pemeriksaan->kode_produksi_array ?? '[]', true);
                                                    $kodeProduksi = is_array($kodeArray) && count($kodeArray) > 0 ? $kodeArray[0] : ($pemeriksaan->kode_produksi ?? '-');
                                                @endphp
                                                {{ $kodeProduksi }}
                                                @if(is_array($kodeArray) && count($kodeArray) > 1) <br><small>+{{ count($kodeArray)-1 }} lainnya</small> @endif
                                            </td>
                                            <td>
                                                @if($statusRow === 'pending' || $statusRow === null)
                                                    @if($userRole === 'qc inspector')
                                                        <button type="submit" class="btn btn-sm btn-primary" formaction="{{ route('pemeriksaan-kedatangan-kemasan.send-to-produksi', $pemeriksaan->uuid) }}"><i class="bi bi-send"></i> Kirim</button>
                                                    @else <span class="badge bg-secondary">Pending</span> @endif
                                                @elseif($statusRow === 'sent_to_produksi')
                                                    <span class="badge bg-warning">Menunggu Warehouse</span>
                                                    @if($userRole === 'produksi')
                                                        <div class="mt-1">
                                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveProduksiModal{{ $pemeriksaan->id }}"><i class="bi bi-check-circle"></i> Verifikasi</button>
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectProduksiModal{{ $pemeriksaan->id }}"><i class="bi bi-x-circle"></i> Tolak</button>
                                                        </div>
                                                    @endif
                                                @elseif($statusRow === 'approved_produksi')
                                                    <span class="badge bg-info">Disetujui Warehouse</span>
                                                    @if($userRole === 'spv qc' || $userRole === 'superadmin')
                                                        <div class="mt-1">
                                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveSPVModal{{ $pemeriksaan->id }}"><i class="bi bi-check-circle"></i> Verifikasi</button>
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectSPVModal{{ $pemeriksaan->id }}"><i class="bi bi-x-circle"></i> Tolak</button>
                                                        </div>
                                                    @endif
                                                @elseif($statusRow === 'approved_spv') <span class="badge bg-success">Disetujui SPV QC</span>
                                                @else <span class="badge bg-danger">{{ str_replace('_', ' ', $statusRow) }}</span> @endif
                                            </td>
                                            <td><small class="text-muted">{{ Str::limit($pemeriksaan->verification_notes ?? '-', 20) }}</small></td>
                                            <td>
                                                <div class="btn-vertical">
                                                    @can('view_pemeriksaan_kedatangan_kemasan') <a href="{{ route('pemeriksaan-kedatangan-kemasan.show', $pemeriksaan->uuid) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a> @endcan
                                                    @can('edit_pemeriksaan_kedatangan_kemasan') <a href="{{ route('pemeriksaan-kedatangan-kemasan.edit', $pemeriksaan->uuid) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a> @endcan
                                                    @can('delete_pemeriksaan_kedatangan_kemasan')
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="if(confirm('Yakin ingin menghapus data ini?')) document.getElementById('del-{{$pemeriksaan->uuid}}').submit()"><i class="bi bi-trash"></i></button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="10" class="text-center py-4">Belum ada data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                @if($canVerify)
                                    <button type="submit" class="btn btn-primary" id="btnVerifySelected" disabled onclick="return confirm('Verifikasi terpilih?')">
                                        <i class="bi bi-patch-check"></i> Verifikasi Terpilih (<span id="selectedCount">0</span>)
                                    </button>
                                @endif
                            </div>
                            <div>{{ $pemeriksaans->appends(request()->query())->links() }}</div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        {{-- MODALS --}}
        @if($canVerify)
            <div class="modal fade" id="multiApprovalModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Multi Approval (Verifikasi Massal)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('pemeriksaan-kedatangan-kemasan.batch-verify') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle-fill me-2"></i> Verifikasi semua data berdasarkan rentang tanggal yang dipilih.
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Dari</label>
                                        <input type="date" name="tanggal_dari" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Sampai</label>
                                        <input type="date" name="tanggal_sampai" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Proses verifikasi massal?')">Proses Verifikasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @foreach($pemeriksaans as $pemeriksaan)
            {{-- Modal Produksi --}}
            <div class="modal fade" id="approveProduksiModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Approve Warehouse</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('pemeriksaan-kedatangan-kemasan.approve-produksi', $pemeriksaan->uuid) }}" method="POST">@csrf<div class="modal-body"><div class="mb-3"><label class="form-label">Catatan</label><textarea class="form-control" name="notes" rows="3"></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success">Approve</button></div></form></div></div></div>
            <div class="modal fade" id="rejectProduksiModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Reject Warehouse</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('pemeriksaan-kedatangan-kemasan.reject-produksi', $pemeriksaan->uuid) }}" method="POST">@csrf<div class="modal-body"><div class="mb-3"><label class="form-label text-danger">Alasan *</label><textarea class="form-control" name="notes" rows="3" required></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Reject</button></div></form></div></div></div>
            {{-- Modal SPV --}}
            <div class="modal fade" id="approveSPVModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Verifikasi SPV QC</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('pemeriksaan-kedatangan-kemasan.approve-spv', $pemeriksaan->uuid) }}" method="POST">@csrf<div class="modal-body"><div class="mb-3"><label class="form-label">Catatan</label><textarea class="form-control" name="notes" rows="3"></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success">Verifikasi</button></div></form></div></div></div>
            <div class="modal fade" id="rejectSPVModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Reject SPV QC</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('pemeriksaan-kedatangan-kemasan.reject-spv', $pemeriksaan->uuid) }}" method="POST">@csrf<div class="modal-body"><div class="mb-3"><label class="form-label text-danger">Alasan *</label><textarea class="form-control" name="notes" rows="3" required></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Reject</button></div></form></div></div></div>
        @endforeach
    </div>

    {{-- Forms Hapus di luar agar tidak nested --}}
    @foreach($pemeriksaans as $pemeriksaan)
        @can('delete_pemeriksaan_kedatangan_kemasan')
            <form id="del-{{$pemeriksaan->uuid}}" action="{{ route('pemeriksaan-kedatangan-kemasan.destroy', $pemeriksaan->uuid) }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        @endcan
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const produkByKategori = @json($produkByKategori ?? []);
    const kSelect = document.getElementById('filterKategori');
    const pSelect = document.getElementById('filterProduk');

    if (kSelect && pSelect) {
        // Init Choices.js
        const initChoices = (el) => {
            if (typeof Choices === 'undefined') return null;
            return new Choices(el, {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                removeItemButton: true,
                placeholderValue: 'Pilih...'
            });
        };

        const kChoices = initChoices(kSelect);
        const pChoices = initChoices(pSelect);

        const populate = (val) => {
            const sel = pSelect.getAttribute('data-selected');
            let opts = [];
            if (!val) Object.values(produkByKategori).forEach(a => opts = opts.concat(a));
            else opts = produkByKategori[val] || [];
            
            if (pChoices) {
                const choiceItems = [{ value: '', label: '-- Semua Produk --', selected: !sel }].concat(
                    opts.map(o => ({
                        value: String(o.id),
                        label: o.name || o.nama,
                        selected: String(o.id) === String(sel)
                    }))
                );
                pChoices.clearChoices();
                pChoices.setChoices(choiceItems, 'value', 'label', true);
            } else {
                pSelect.innerHTML = '<option value="">-- Semua Produk --</option>';
                opts.forEach(o => {
                    const opt = new Option(o.nama || o.name, o.id);
                    if (o.id == sel) opt.selected = true;
                    pSelect.add(opt);
                });
            }
        };

        kSelect.addEventListener('change', (e) => populate(e.target.value));
        populate(kSelect.value);
    }

    const shiftS = document.getElementById('shiftSelect');
    const tD = document.getElementById('tanggalDariWrapper'), tSm = document.getElementById('tanggalSampaiWrapper'), tSi = document.getElementById('tanggalSingleWrapper');
    if (shiftS) {
        const upDate = () => {
            const o = shiftS.options[shiftS.selectedIndex];
            const val = shiftS.value;
            const isAll = val === 'all';
            const isR = o.getAttribute('data-is-date-range') == '1';
            const sN = o.getAttribute('data-shift-name');

            if (isAll) {
                // "Semua Shift" → tampilkan date range
                tD.style.display = 'block';
                tSm.style.display = 'block';
                tSi.style.display = 'none';
            } else {
                tD.style.display = isR ? 'block' : 'none';
                tSm.style.display = isR ? 'block' : 'none';
                tSi.style.display = (!isR && sN) ? 'block' : 'none';
            }
        };
        shiftS.onchange = upDate; upDate();
    }

    const sAll = document.getElementById('selectAll'), rowCs = document.querySelectorAll('.row-checkbox'), btnB = document.getElementById('btnVerifySelected'), cSp = document.getElementById('selectedCount');
    if (sAll) sAll.onclick = () => { rowCs.forEach(c => c.checked = sAll.checked); upCnt(); };
    rowCs.forEach(c => c.onclick = upCnt);
    function upCnt() {
        const n = document.querySelectorAll('.row-checkbox:checked').length;
        if (btnB) { btnB.disabled = n === 0; cSp.innerText = n; }
    }
});
</script>
@endsection