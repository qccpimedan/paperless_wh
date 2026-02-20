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
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Pemeriksaan Kedatangan Kemasan</h5>
                    @can('create_pemeriksaan_kedatangan_kemasan')
                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Pemeriksaan
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    {{-- Filter Form untuk PDF Export --}}
                    <div class="row mb-4 p-3 bg-light rounded">
                        <div class="col-md-12 mb-3">
                            <h6 class="mb-3"><i class="bi bi-funnel"></i> Filter & Cetak PDF</h6>
                        </div>
                        <form action="{{ route('pemeriksaan-kedatangan-kemasan.export-pdf') }}" method="GET" class="row g-3" id="pdfFilterForm">
                            <div class="col-md-3">
                                <label class="form-label">Shift</label>
                                <select name="id_shift" class="form-select" id="shiftSelect" required>
                                    <option value="">-- Pilih Shift --</option>
                                    @foreach($shifts ?? [] as $shift)
                                        <option value="{{ $shift->id }}" data-shift-name="{{ $shift->shift }}" {{ request('id_shift') == $shift->id ? 'selected' : '' }}>
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
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-file-pdf"></i> Cetak PDF
                                </button>
                            </div>
                        </form>
                    </div>
                    <form action="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" method="GET" class="row g-3 mb-3">
                        <div class="col-md-9">
                            <input type="text" name="search" class="form-control" placeholder="Cari tanggal/status/shift/bahan/kode produksi..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                    </form>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const shiftSelect = document.getElementById('shiftSelect');
                            const tanggalDariWrapper = document.getElementById('tanggalDariWrapper');
                            const tanggalSampaiWrapper = document.getElementById('tanggalSampaiWrapper');
                            const tanggalSingleWrapper = document.getElementById('tanggalSingleWrapper');
                            const tanggalDari = document.getElementById('tanggalDari');
                            const tanggalSampai = document.getElementById('tanggalSampai');
                            const tanggalSingle = document.getElementById('tanggalSingle');

                            function updateDateFields() {
                                const selectedOption = shiftSelect.options[shiftSelect.selectedIndex];
                                const shiftName = selectedOption.getAttribute('data-shift-name');

                                const isShift1 = shiftName === '1' || shiftName === 'Shift 1' || shiftName === 'shift 1';
                                const isShift2or3 = shiftName === '2' || shiftName === 'Shift 2' || shiftName === 'shift 2' ||
                                                    shiftName === '3' || shiftName === 'Shift 3' || shiftName === 'shift 3';

                                if (isShift1) {
                                    tanggalDariWrapper.style.display = 'block';
                                    tanggalSampaiWrapper.style.display = 'block';
                                    tanggalSingleWrapper.style.display = 'none';

                                    tanggalDari.required = true;
                                    tanggalSampai.required = true;
                                    tanggalSingle.required = false;
                                    tanggalSingle.value = '';
                                } else if (isShift2or3) {
                                    tanggalDariWrapper.style.display = 'none';
                                    tanggalSampaiWrapper.style.display = 'none';
                                    tanggalSingleWrapper.style.display = 'block';

                                    tanggalDari.required = false;
                                    tanggalSampai.required = false;
                                    tanggalSingle.required = true;
                                    tanggalDari.value = '';
                                    tanggalSampai.value = '';
                                } else {
                                    tanggalDariWrapper.style.display = 'none';
                                    tanggalSampaiWrapper.style.display = 'none';
                                    tanggalSingleWrapper.style.display = 'none';

                                    tanggalDari.required = false;
                                    tanggalSampai.required = false;
                                    tanggalSingle.required = false;
                                }
                            }

                            shiftSelect.addEventListener('change', updateDateFields);
                            updateDateFields();
                        });
                    </script>
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table1" data-disable-datatable="1" style="white-space: nowrap;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Shift</th>
                                    <th>Plant</th>
                                    <!-- <th>No. PO</th> -->
                                    <th>Bahan Kemasan</th>
                                    <!-- <th>Produsen</th> -->
                                    <th>Kode Produksi</th>
                                    <!-- <th>Status</th> -->
                                    <th>Verifikasi</th>
                                    <th>Catatan Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemeriksaans as $index => $pemeriksaan)
                                    <tr>
                                        <td>{{ ($pemeriksaans->firstItem() ?? 1) + $index }}</td>
                                        <td>
                                            <strong>{{ $pemeriksaan->tanggal->format('d/m/Y') }}</strong>
                                        </td>
                                        <td>
                                            @if($pemeriksaan->shift)
                                                <span class="badge bg-primary">{{ $pemeriksaan->shift->shift }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Shift</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pemeriksaan->user->plant)
                                                <span class="badge bg-primary">{{ $pemeriksaan->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </td>
                                        <!-- <td>
                                            {{ $pemeriksaan->no_po ?? '-' }}
                                        </td> -->
                                        <td>
                                            @if($pemeriksaan->bahan)
                                                <span class="badge bg-info">{{ $pemeriksaan->bahan->nama_bahan }}</span>
                                            @else
                                                @php
                                                    $idBahanArray = json_decode($pemeriksaan->id_bahan_array ?? '[]', true);
                                                    $idBahanArray = is_array($idBahanArray) ? array_values(array_filter($idBahanArray, function ($v) {
                                                        return $v !== null && $v !== '';
                                                    })) : [];
                                                    $namaBahanArray = array_values(array_filter(array_map(function ($id) use ($produkNamaById) {
                                                        return $produkNamaById[$id] ?? null;
                                                    }, $idBahanArray)));

                                                    $namaBahanPreview = [];
                                                    if (count($namaBahanArray) === 1) {
                                                        $namaBahanPreview = [$namaBahanArray[0]];
                                                    } elseif (count($namaBahanArray) === 2) {
                                                        $namaBahanPreview = [$namaBahanArray[0], $namaBahanArray[1]];
                                                    } elseif (count($namaBahanArray) > 2) {
                                                        $namaBahanPreview = [$namaBahanArray[0], $namaBahanArray[count($namaBahanArray) - 1]];
                                                    }
                                                @endphp
                                                @if(count($namaBahanPreview) > 0)
                                                    <span class="badge bg-info">{{ $namaBahanPreview[0] }}</span>
                                                    @if(count($namaBahanArray) > 2)
                                                        <br>
                                                        <span class="text-muted">...</span>
                                                    @endif
                                                    @if(count($namaBahanPreview) === 2)
                                                        <br>
                                                        <span class="badge bg-info">{{ $namaBahanPreview[1] }}</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $kodeProduksiArray = json_decode($pemeriksaan->kode_produksi_array ?? '[]', true);
                                                $kodeProduksiArray = is_array($kodeProduksiArray) ? array_values(array_filter($kodeProduksiArray, function ($v) {
                                                    return $v !== null && $v !== '';
                                                })) : [];

                                                $kodeProduksiPreview = [];
                                                if (count($kodeProduksiArray) === 1) {
                                                    $kodeProduksiPreview = [$kodeProduksiArray[0]];
                                                } elseif (count($kodeProduksiArray) === 2) {
                                                    $kodeProduksiPreview = [$kodeProduksiArray[0], $kodeProduksiArray[1]];
                                                } elseif (count($kodeProduksiArray) > 2) {
                                                    $kodeProduksiPreview = [$kodeProduksiArray[0], $kodeProduksiArray[count($kodeProduksiArray) - 1]];
                                                }
                                            @endphp
                                            @if(count($kodeProduksiPreview) > 0)
                                                {{ $kodeProduksiPreview[0] }}
                                                @if(count($kodeProduksiArray) > 2)
                                                    <br>
                                                    <span class="text-muted">...</span>
                                                @endif
                                                @if(count($kodeProduksiPreview) === 2)
                                                    <br>
                                                    {{ $kodeProduksiPreview[1] }}
                                                @endif
                                            @else
                                                {{ $pemeriksaan->kode_produksi ?? '-' }}
                                            @endif
                                        </td>
                                        <!-- <td>
                                            @if($pemeriksaan->status === 'Release')
                                                <span class="badge bg-success">{{ $pemeriksaan->status }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ $pemeriksaan->status }}</span>
                                            @endif
                                        </td> -->
                                        <td>
                                            @php
                                                $userRole = auth()->user()->role ? strtolower(auth()->user()->role->role) : null;
                                                $status = $pemeriksaan->status_verifikasi ?? 'pending';
                                            @endphp
                                            @if($status === 'pending' || $status === null)
                                                @if($userRole === 'qc inspector')
                                                    <form action="{{ route('pemeriksaan-kedatangan-kemasan.send-to-produksi', $pemeriksaan->uuid) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" title="Kirim ke Tim Warehouse"><i class="bi bi-send"></i> Kirim</button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-secondary">Pending</span>
                                                @endif
                                            @elseif($status === 'sent_to_produksi')
                                                <span class="badge bg-warning">Menunggu Tim Warehouse</span>
                                                @if($userRole === 'produksi')
                                                    <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#approveProduksiModal{{ $pemeriksaan->id }}"><i class="bi bi-check-circle"></i> Approve</button>
                                                    <button class="btn btn-sm btn-danger mt-1" data-bs-toggle="modal" data-bs-target="#rejectProduksiModal{{ $pemeriksaan->id }}"><i class="bi bi-x-circle"></i> Reject</button>
                                                @endif
                                            @elseif($status === 'approved_produksi')
                                                <span class="badge bg-info">Disetujui Tim Warehouse</span>
                                                @if($userRole === 'spv qc')
                                                    <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#approveSPVModal{{ $pemeriksaan->id }}"><i class="bi bi-check-circle"></i> Verifikasi</button>
                                                    <button class="btn btn-sm btn-danger mt-1" data-bs-toggle="modal" data-bs-target="#rejectSPVModal{{ $pemeriksaan->id }}"><i class="bi bi-x-circle"></i> Reject</button>
                                                @endif
                                            @elseif($status === 'approved_spv')
                                                <span class="badge bg-success">Disetujui SPV QC</span>
                                            @elseif($status === 'rejected_produksi')
                                                <span class="badge bg-danger">Ditolak Tim Warehouse</span>
                                            @elseif($status === 'rejected_spv')
                                                <span class="badge bg-danger">Ditolak SPV QC</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pemeriksaan->verification_notes)
                                                <small class="text-muted">{{ Str::limit($pemeriksaan->verification_notes, 50) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-vertical" role="group">
                                                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.tambah-baris', $pemeriksaan->uuid) }}"
                                                        class="btn btn-sm btn-success" title="Tambah Baris">
                                                            <i class="bi bi-plus-circle"></i>
                                                        </a>
                                                    @can('view_pemeriksaan_kedatangan_kemasan')
                                                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.show', $pemeriksaan->uuid) }}" 
                                                        class="btn btn-sm btn-info" title="Lihat Detail">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endcan
                                                    @can('edit_pemeriksaan_kedatangan_kemasan')
                                                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.edit', $pemeriksaan->uuid) }}" 
                                                        class="btn btn-sm btn-warning" title="Edit Data">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>

                                                    @endcan
                                                    @can('delete_pemeriksaan_kedatangan_kemasan')
                                                        <form action="{{ route('pemeriksaan-kedatangan-kemasan.destroy', $pemeriksaan->uuid) }}" 
                                                            method="POST" 
                                                            style="display: inline-block;"
                                                            onsubmit="return confirm('Yakin ingin menghapus data pemeriksaan ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Data">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>

                                    <!-- Modal Approve Produksi -->
                                    <div class="modal fade" id="approveProduksiModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Approve Pemeriksaan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pemeriksaan-kedatangan-kemasan.approve-produksi', $pemeriksaan->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($pemeriksaan->verification_notes)
                                                            <div class="alert alert-info mb-3"><strong>Catatan Sebelumnya:</strong><br>{{ $pemeriksaan->verification_notes }}</div>
                                                        @endif
                                                        <div class="mb-3">
                                                            <label class="form-label">Catatan (Opsional)</label>
                                                            <textarea class="form-control" name="notes" rows="3" placeholder="Masukkan catatan jika ada"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Approve</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Reject Produksi -->
                                    <div class="modal fade" id="rejectProduksiModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Pemeriksaan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pemeriksaan-kedatangan-kemasan.reject-produksi', $pemeriksaan->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($pemeriksaan->verification_notes)
                                                            <div class="alert alert-info mb-3"><strong>Catatan Sebelumnya:</strong><br>{{ $pemeriksaan->verification_notes }}</div>
                                                        @endif
                                                        <div class="mb-3">
                                                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                            <textarea class="form-control" name="notes" rows="3" placeholder="Masukkan alasan penolakan" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Reject</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Approve SPV QC -->
                                    <div class="modal fade" id="approveSPVModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Verifikasi Pemeriksaan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pemeriksaan-kedatangan-kemasan.approve-spv', $pemeriksaan->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($pemeriksaan->verification_notes)
                                                            <div class="alert alert-info mb-3"><strong>Catatan Sebelumnya:</strong><br>{{ $pemeriksaan->verification_notes }}</div>
                                                        @endif
                                                        <div class="mb-3">
                                                            <label class="form-label">Catatan (Opsional)</label>
                                                            <textarea class="form-control" name="notes" rows="3" placeholder="Masukkan catatan jika ada"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Verifikasi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Reject SPV QC -->
                                    <div class="modal fade" id="rejectSPVModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Pemeriksaan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pemeriksaan-kedatangan-kemasan.reject-spv', $pemeriksaan->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($pemeriksaan->verification_notes)
                                                            <div class="alert alert-info mb-3"><strong>Catatan Sebelumnya:</strong><br>{{ $pemeriksaan->verification_notes }}</div>
                                                        @endif
                                                        <div class="mb-3">
                                                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                            <textarea class="form-control" name="notes" rows="3" placeholder="Masukkan alasan penolakan" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Reject</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                                <p class="text-muted mt-2 mb-3">Belum ada data pemeriksaan kedatangan kemasan</p>
                                                <a href="{{ route('pemeriksaan-kedatangan-kemasan.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> Tambah Pemeriksaan Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $pemeriksaans->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection