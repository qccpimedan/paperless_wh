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
    $canVerify = in_array($userRole, ['qc inspector', 'warehouse', 'produksi', 'spv qc', 'superadmin', 'produksi/warehouse']);
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
                    <h3>Golden Sample Retort</h3>
                    <p class="text-subtitle text-muted">Kelola data laporan golden sample retort</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Golden Sample</li>
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
                    <h5 class="card-title mb-0">Daftar Laporan Golden Sample</h5>
                    <div class="d-flex gap-2">
                        @if($canVerify)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#multiApprovalModal">
                                <i class="bi bi-patch-check"></i> Multi Approval
                            </button>
                        @endif
                        @can('create_golden_sample_retort')
                            <a href="{{ route('golden-sample-reports.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Laporan
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4 p-3 bg-light rounded">
                        <div class="col-md-12 mb-3"><h6><i class="bi bi-funnel"></i> Filter & Verifikasi Massal</h6></div>
                        <form action="{{ route('golden-sample-reports.export-pdf') }}" method="GET" class="row g-3" id="filterForm">
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
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100"><i class="bi bi-file-pdf"></i> PDF</button>
                            </div>
                        </form>
                    </div>
                    
                    <form action="{{ route('golden-sample-reports.index') }}" method="GET" class="row g-3 mb-3">
                        <div class="col-md-9"><input type="text" name="search" class="form-control" placeholder="Cari Sample Type..." value="{{ request('search') }}"></div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">Cari Data</button>
                            <a href="{{ route('golden-sample-reports.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </form>

                    <form id="batchActionForm" action="{{ route('golden-sample-reports.batch-verify') }}" method="POST">
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
                                        <th>Sample Type</th>
                                        <th>Masa Penyimpanan</th>
                                        <th>Jumlah Sampel</th>
                                        <th>Verifikasi</th>
                                        <th>Catatan Verifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $index => $item)
                                        @php
                                            $st = $item->status_verifikasi ?? 'pending';
                                            $canRowV = false;
                                            if ($userRole === 'qc inspector' && ($st === 'pending' || $st === null)) $canRowV = true;
                                            elseif (($userRole === 'produksi' || $userRole === 'warehouse' || $userRole === 'produksi/warehouse') && $st === 'sent_to_produksi') $canRowV = true;
                                            elseif (($userRole === 'spv qc' || $userRole === 'superadmin') && $st === 'approved_produksi') $canRowV = true;
                                        @endphp
                                        <tr>
                                            <td>
                                                @if($canRowV) <input type="checkbox" name="selected_uuids[]" value="{{ $item->uuid }}" class="form-check-input row-checkbox">
                                                @else <i class="bi bi-dash text-muted"></i> @endif
                                            </td>
                                            <td>{{ ($reports->firstItem() ?? 1) + $index }}</td>
                                            <td><strong>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</strong></td>
                                            <td><span class="badge bg-primary">{{ $item->shift->shift ?? 'Shift tidak ditemukan' }}</span></td>
                                            <td><span class="badge bg-secondary">{{ $item->plant->plant ?? $item->plant_manual ?? '-' }}</span></td>
                                            <td>{{ $item->sample_type ?? '-' }}</td>
                                            <td>{{ $item->masa_penyimpanan ?? '-' }}</td>
                                            <td>
                                                @php $jumlahSampel = is_array($item->samples) ? count($item->samples) : 0; @endphp
                                                <span class="badge bg-info text-white">{{ $jumlahSampel }}</span>
                                            </td>
                                            <td>
                                                @if($st === 'pending' || $st === null)
                                                    @if($userRole === 'qc inspector')
                                                        <button type="submit" class="btn btn-sm btn-primary" formaction="{{ route('golden-sample-reports.send-to-produksi', $item->uuid) }}"><i class="bi bi-send"></i> Kirim</button>
                                                    @else <span class="badge bg-secondary">Pending</span> @endif
                                                @elseif($st === 'sent_to_produksi')
                                                    <span class="badge bg-warning text-dark">Menunggu Produksi</span>
                                                    @if($userRole === 'produksi' || $userRole === 'warehouse' || $userRole === 'produksi/warehouse')
                                                        <div class="mt-1">
                                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#appProduksi{{ $item->id }}"><i class="bi bi-check-circle"></i> Verifikasi</button>
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejProduksi{{ $item->id }}"><i class="bi bi-x-circle"></i> Tolak</button>
                                                        </div>
                                                    @endif
                                                @elseif($st === 'approved_produksi')
                                                    <span class="badge bg-info text-white">Disetujui Produksi</span>
                                                    @if($userRole === 'spv qc' || $userRole === 'superadmin')
                                                        <div class="mt-1">
                                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#appSPV{{ $item->id }}"><i class="bi bi-check-circle"></i> Verifikasi</button>
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejSPV{{ $item->id }}"><i class="bi bi-x-circle"></i> Tolak</button>
                                                        </div>
                                                    @endif
                                                @elseif($st === 'approved_spv') <span class="badge bg-success">Disetujui SPV QC</span>
                                                @else <span class="badge bg-danger">{{ str_replace('_', ' ', $st) }}</span> @endif
                                            </td>
                                            <td>{{ $item->verification_notes ?? '-' }}</td>
                                            <td>
                                                <div class="btn-vertical">
                                                    @can('view_golden_sample_retort') <a href="{{ route('golden-sample-reports.show', $item->uuid) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a> @endcan
                                                    @can('edit_golden_sample_retort') <a href="{{ route('golden-sample-reports.edit', $item->uuid) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a> @endcan
                                                    @can('delete_golden_sample_retort')
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="if(confirm('Yakin ingin menghapus data ini?')) document.getElementById('del-{{$item->uuid}}').submit()"><i class="bi bi-trash"></i></button>
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
                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                @if($canVerify)
                                    <button type="submit" class="btn btn-primary" id="btnBatch" disabled onclick="return confirm('Verifikasi terpilih?')">
                                        <i class="bi bi-patch-check"></i> Verifikasi Terpilih (<span id="countSelected">0</span>)
                                    </button>
                                @endif
                            </div>
                            <div>{{ $reports->appends(request()->query())->links() }}</div>
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
                        <form action="{{ route('golden-sample-reports.batch-verify') }}" method="POST">
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

        @foreach($reports as $item)
            <div class="modal fade" id="appProduksi{{ $item->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Approve Produksi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('golden-sample-reports.approve-produksi', $item->uuid) }}" method="POST">@csrf<div class="modal-body"><textarea class="form-control" name="notes" placeholder="Catatan (Opsional)"></textarea></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success">Approve</button></div></form></div></div></div>
            <div class="modal fade" id="rejProduksi{{ $item->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Reject Produksi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('golden-sample-reports.reject-produksi', $item->uuid) }}" method="POST">@csrf<div class="modal-body"><textarea class="form-control" name="notes" placeholder="Alasan penolakan" required></textarea></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Reject</button></div></form></div></div></div>
            <div class="modal fade" id="appSPV{{ $item->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Verifikasi SPV QC</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('golden-sample-reports.approve-spv', $item->uuid) }}" method="POST">@csrf<div class="modal-body"><textarea class="form-control" name="notes" placeholder="Catatan (Opsional)"></textarea></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success">Verifikasi</button></div></form></div></div></div>
            <div class="modal fade" id="rejSPV{{ $item->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Reject SPV QC</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('golden-sample-reports.reject-spv', $item->uuid) }}" method="POST">@csrf<div class="modal-body"><textarea class="form-control" name="notes" placeholder="Alasan penolakan" required></textarea></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Reject</button></div></form></div></div></div>
        @endforeach
    </div>

    {{-- Forms Hapus di luar agar tidak nested --}}
    @foreach($reports as $item)
        @can('delete_golden_sample_retort')
            <form id="del-{{$item->uuid}}" action="{{ route('golden-sample-reports.destroy', $item->uuid) }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        @endcan
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
