@extends('layouts.app')
@section('container')
@php
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
    $canVerify = in_array($userRole, ['qc inspector', 'produksi', 'warehouse', 'spv qc', 'superadmin']);
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
                    <h3>Pemeriksaan Produk Finish Good</h3>
                    <p class="text-subtitle text-muted">Kelola data pemeriksaan produk finish good</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Finish Good</li>
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
                    <h5 class="card-title mb-0">Daftar Pemeriksaan Finish Good</h5>
                    @can('create_pemeriksaan_produk_finish_good')
                        <a href="{{ route('pemeriksaan-produk-finish-good.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Pemeriksaan
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="row mb-4 p-3 bg-light rounded">
                        <div class="col-md-12 mb-3"><h6><i class="bi bi-funnel"></i> Filter & Verifikasi Massal</h6></div>
                        <form action="{{ route('pemeriksaan-produk-finish-good.export-pdf') }}" method="GET" class="row g-3" id="filterForm">
                            <div class="col-md-3">
                                <label class="form-label">Shift</label>
                                <select name="id_shift" class="form-select" id="shiftSelect">
                                    <option value="">-- Pilih Shift --</option>
                                    @foreach($shifts ?? [] as $shift)
                                        <option value="{{ $shift->id }}" data-shift-name="{{ $shift->shift }}" data-is-date-range="{{ $shift->is_date_range }}" {{ request('id_shift') == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->shift }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3" id="tanggalDariWrapper">
                                <label class="form-label">Tanggal Dari</label>
                                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                            </div>
                            <div class="col-md-3" id="tanggalSampaiWrapper">
                                <label class="form-label">Tanggal Sampai</label>
                                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                            </div>
                            <div class="col-md-3" id="tanggalSingleWrapper" style="display: none;">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_code" class="form-select" id="filterKategori">
                                    <option value="">-- Semua Kategori --</option>
                                    @foreach($produkKategoriOptions ?? [] as $k)
                                        <option value="{{ $k }}" {{ request('kategori_code') == $k ? 'selected' : '' }}>{{ $k }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Produk</label>
                                <select name="id_produk" class="form-select" id="filterProduk" data-selected="{{ request('id_produk') }}">
                                    <option value="">-- Semua Produk --</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-success flex-grow-1"><i class="bi bi-file-pdf"></i> PDF</button>
                                @if($canVerify)
                                    <button type="submit" class="btn btn-primary flex-grow-1" formaction="{{ route('pemeriksaan-produk-finish-good.batch-verify') }}" formmethod="POST" onclick="return confirm('Verifikasi semua data pada filter ini?')">
                                        @csrf <i class="bi bi-patch-check"></i> Verifikasi
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <form id="batchActionForm" action="{{ route('pemeriksaan-produk-finish-good.batch-verify') }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped text-center" style="white-space: nowrap;">
                                <thead>
                                    <tr>
                                        <th>
                                            @if($canVerify) <input type="checkbox" id="selectAll" class="form-check-input">
                                            @else <i class="bi bi-check-square"></i> @endif
                                        </th>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Shift</th>
                                        <th>Plant</th>
                                        <th>Nama Produk</th>
                                        <th>Kode Produksi</th>
                                        <th>Status Verifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pemeriksaans as $index => $item)
                                        @php
                                            $st = $item->status_verifikasi ?? 'pending';
                                            $canRowV = false;
                                            if ($userRole === 'qc inspector' && ($st === 'pending' || $st === null)) $canRowV = true;
                                            elseif (($userRole === 'produksi' || $userRole === 'warehouse') && $st === 'sent_to_produksi') $canRowV = true;
                                            elseif (($userRole === 'spv qc' || $userRole === 'superadmin') && $st === 'approved_produksi') $canRowV = true;
                                        @endphp
                                        <tr>
                                            <td>
                                                @if($canRowV) <input type="checkbox" name="selected_uuids[]" value="{{ $item->uuid }}" class="form-check-input row-checkbox">
                                                @else <i class="bi bi-dash text-muted"></i> @endif
                                            </td>
                                            <td>{{ ($pemeriksaans->firstItem() ?? 1) + $index }}</td>
                                            <td><strong>{{ $item->tanggal->format('d/m/Y') }}</strong></td>
                                            <td><span class="badge bg-primary">{{ $item->shift->shift ?? '-' }}</span></td>
                                            <td><span class="badge bg-secondary">{{ $item->user->plant->plant ?? '-' }}</span></td>
                                            <td>
                                                @php
                                                    $pArr = $item->id_produk_array;
                                                    $namaProdukArray = array_values(array_filter(array_map(function ($id) use ($produkNamaById) {
                                                        return $produkNamaById[$id] ?? null;
                                                    }, is_array($pArr) ? $pArr : [])));
                                                @endphp
                                                @if(count($namaProdukArray) > 0)
                                                    <span class="badge bg-info">{{ $namaProdukArray[0] }}</span>
                                                    @if(count($namaProdukArray) > 1) <br><small>+{{ count($namaProdukArray)-1 }} lainnya</small> @endif
                                                @else - @endif
                                            </td>
                                            <td>
                                                @php
                                                    $kArr = $item->kode_produksi_array;
                                                    $kArr = is_array($kArr) ? $kArr : [];
                                                @endphp
                                                @if(count($kArr) > 0)
                                                    {{ $kArr[0] }}
                                                    @if(count($kArr) > 1) <br><small>+{{ count($kArr)-1 }} lainnya</small> @endif
                                                @else - @endif
                                            </td>
                                            <td>
                                                @if($st === 'pending' || $st === null)
                                                    @if($userRole === 'qc inspector')
                                                        <button type="submit" class="btn btn-sm btn-primary" formaction="{{ route('pemeriksaan-produk-finish-good.send-to-produksi', $item->uuid) }}"><i class="bi bi-send"></i> Kirim</button>
                                                    @else <span class="badge bg-secondary">Pending</span> @endif
                                                @elseif($st === 'sent_to_produksi')
                                                    <span class="badge bg-warning text-dark">Menunggu Warehouse</span>
                                                    @if($userRole === 'produksi' || $userRole === 'warehouse')
                                                        <div class="mt-1">
                                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#appProduksi{{ $item->id }}"><i class="bi bi-check-circle"></i> Verifikasi</button>
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejProduksi{{ $item->id }}"><i class="bi bi-x-circle"></i> Tolak</button>
                                                        </div>
                                                    @endif
                                                @elseif($st === 'approved_produksi')
                                                    <span class="badge bg-info text-white">Disetujui Warehouse</span>
                                                    @if($userRole === 'spv qc' || $userRole === 'superadmin')
                                                        <div class="mt-1">
                                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#appSPV{{ $item->id }}"><i class="bi bi-check-circle"></i> Verifikasi</button>
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejSPV{{ $item->id }}"><i class="bi bi-x-circle"></i> Tolak</button>
                                                        </div>
                                                    @endif
                                                @elseif($st === 'approved_spv') <span class="badge bg-success">Disetujui SPV QC</span>
                                                @else <span class="badge bg-danger">{{ str_replace('_', ' ', $st) }}</span> @endif
                                            </td>
                                            <td>
                                                <div class="btn-vertical">
                                                    @can('view_pemeriksaan_produk_finish_good') <a href="{{ route('pemeriksaan-produk-finish-good.show', $item->uuid) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a> @endcan
                                                    @can('edit_pemeriksaan_produk_finish_good') <a href="{{ route('pemeriksaan-produk-finish-good.edit', $item->uuid) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a> @endcan
                                                    @can('delete_pemeriksaan_produk_finish_good')
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="if(confirm('Yakin ingin menghapus data ini?')) document.getElementById('del-{{$item->uuid}}').submit()"><i class="bi bi-trash"></i></button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="9" class="text-center py-4">Belum ada data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                @if($canVerify)
                                    <button type="submit" class="btn btn-primary" id="btnBatch" disabled onclick="return confirm('Verifikasi terpilih?')">
                                        <i class="bi bi-patch-check"></i> Verifikasi Terpilih (<span id="countSelected">0</span>)
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
        @foreach($pemeriksaans as $item)
            <div class="modal fade" id="appProduksi{{ $item->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Approve Warehouse</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('pemeriksaan-produk-finish-good.approve-produksi', $item->uuid) }}" method="POST">@csrf<div class="modal-body"><textarea class="form-control" name="notes" placeholder="Catatan (Opsional)"></textarea></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success">Approve</button></div></form></div></div></div>
            <div class="modal fade" id="rejProduksi{{ $item->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Reject Warehouse</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('pemeriksaan-produk-finish-good.reject-produksi', $item->uuid) }}" method="POST">@csrf<div class="modal-body"><textarea class="form-control" name="notes" placeholder="Alasan penolakan" required></textarea></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Reject</button></div></form></div></div></div>
            <div class="modal fade" id="appSPV{{ $item->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Verifikasi SPV QC</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('pemeriksaan-produk-finish-good.approve-spv', $item->uuid) }}" method="POST">@csrf<div class="modal-body"><textarea class="form-control" name="notes" placeholder="Catatan (Opsional)"></textarea></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success">Verifikasi</button></div></form></div></div></div>
            <div class="modal fade" id="rejSPV{{ $item->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Reject SPV QC</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('pemeriksaan-produk-finish-good.reject-spv', $item->uuid) }}" method="POST">@csrf<div class="modal-body"><textarea class="form-control" name="notes" placeholder="Alasan penolakan" required></textarea></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Reject</button></div></form></div></div></div>
        @endforeach
    </div>

    {{-- Forms Hapus di luar agar tidak nested --}}
    @foreach($pemeriksaans as $item)
        @can('delete_pemeriksaan_produk_finish_good')
            <form id="del-{{$item->uuid}}" action="{{ route('pemeriksaan-produk-finish-good.destroy', $item->uuid) }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        @endcan
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pByCat = @json($produkByKategori ?? []);
    const kRel = document.getElementById('filterKategori'), pRel = document.getElementById('filterProduk');
    
    if (kRel && pRel) {
        const initC = (el) => (typeof Choices !== 'undefined') ? new Choices(el, { searchEnabled: true, itemSelectText: '', removeItemButton: true }) : null;
        const kC = initC(kRel), pC = initC(pRel);
        const pop = (v) => {
            const sel = pRel.getAttribute('data-selected');
            let os = [];
            if (!v) Object.values(pByCat).forEach(a => os = os.concat(a));
            else os = pByCat[v] || [];
            if (pC) {
                pC.clearChoices();
                pC.setChoices([{value:'', label:'-- Semua Produk --', selected:!sel}].concat(os.map(o=>({value:String(o.id), label:o.nama, selected:o.id==sel}))), 'value', 'label', true);
            }
        };
        kRel.onchange = (e) => pop(e.target.value);
        pop(kRel.value);
    }

    const sAll = document.getElementById('selectAll'), rows = document.querySelectorAll('.row-checkbox'), btnB = document.getElementById('btnBatch'), cSp = document.getElementById('countSelected');
    if (sAll) sAll.onclick = () => { rows.forEach(r => r.checked = sAll.checked); u(); };
    rows.forEach(r => r.onclick = u);
    function u() { const n = document.querySelectorAll('.row-checkbox:checked').length; if(btnB){ btnB.disabled = n===0; cSp.innerText = n; } }

    const shiftS = document.getElementById('shiftSelect');
    const tD = document.getElementById('tanggalDariWrapper'), tSm = document.getElementById('tanggalSampaiWrapper'), tSi = document.getElementById('tanggalSingleWrapper');
    if(shiftS){
        const upD = () => {
            const o = shiftS.options[shiftS.selectedIndex];
            const isR = o.getAttribute('data-is-date-range') == '1';
            const sN = o.getAttribute('data-shift-name');
            tD.style.display = isR ? 'block' : 'none'; tSm.style.display = isR ? 'block' : 'none';
            tSi.style.display = (!isR && sN) ? 'block' : 'none';
        };
        shiftS.onchange = upD; upD();
    }
});
</script>
@endsection
