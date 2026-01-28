@extends('layouts.app')

@section('title', 'Pemeriksaan Produk Finish Good')

@section('container')
@php
    $shifts = \App\Models\Shift::all();
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
                    <p class="text-subtitle text-muted">Daftar data pemeriksaan produk finish good</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pemeriksaan Produk Finish Good</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Data Pemeriksaan Produk Finish Good</h4>
                            @can('create_pemeriksaan_produk_finish_good')
                                <a href="{{ route('pemeriksaan-produk-finish-good.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Data
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4 p-3 bg-light rounded">
                            <div class="col-md-12 mb-3">
                                <h6 class="mb-3"><i class="bi bi-funnel"></i> Filter & Cetak PDF</h6>
                            </div>
                            <form action="{{ route('pemeriksaan-produk-finish-good.export-pdf') }}" method="GET" class="row g-3" id="pdfFilterForm">
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

                        <form action="{{ route('pemeriksaan-produk-finish-good.index') }}" method="GET" class="row g-3 mb-3">
                            <div class="col-md-9">
                                <input type="text" name="search" class="form-control" placeholder="Cari tanggal/status/shift/produk/kode produksi..." value="{{ request('search') }}">
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
                                    const shiftName = selectedOption ? selectedOption.getAttribute('data-shift-name') : null;

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

                                if (shiftSelect) {
                                    shiftSelect.addEventListener('change', updateDateFields);
                                    updateDateFields();
                                }
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
                                        <th>Nama Produk</th>
                                        <th>Kode Produksi</th>
                                        <th>Verifikasi</th>
                                        <th>Catatan Verifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pemeriksaans as $index => $p)
                                        <tr>
                                            <td>{{ $pemeriksaans->firstItem() + $index }}</td>
                                            <td>{{ optional($p->tanggal)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($p->shift)
                                                    <span class="badge bg-primary">{{ $p->shift->shift }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $p->user && $p->user->plant ? $p->user->plant->plant : '-' }}</td>
                                            <td>
                                                @php
                                                    $idProdukArray = is_array($p->id_produk_array) ? array_values(array_filter($p->id_produk_array, function ($v) {
                                                        return $v !== null && $v !== '';
                                                    })) : [];
                                                    $namaProdukArray = array_values(array_filter(array_map(function ($id) use ($produkNamaById) {
                                                        $id = $id ? (int) $id : null;
                                                        return $id && isset($produkNamaById[$id]) ? $produkNamaById[$id] : null;
                                                    }, $idProdukArray)));
                                                @endphp
                                                @if(count($namaProdukArray) > 0)
                                                    @foreach($namaProdukArray as $name)
                                                        <span class="badge bg-info">{{ $name }}</span><br>
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $kodeProduksiArray = is_array($p->kode_produksi_array) ? array_values(array_filter($p->kode_produksi_array, function ($v) {
                                                        return $v !== null && $v !== '';
                                                    })) : [];
                                                @endphp
                                                @if(count($kodeProduksiArray) > 0)
                                                    {{ implode(', ', $kodeProduksiArray) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $userRole = auth()->user()->role ? strtolower(auth()->user()->role->role) : null;
                                                    $status = $p->status_verifikasi ?? 'pending';
                                                @endphp
                                                @if($status === 'pending' || $status === null)
                                                    @if($userRole === 'qc inspector')
                                                        <form action="{{ route('pemeriksaan-produk-finish-good.send-to-produksi', $p->uuid) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-primary" title="Kirim ke Tim Warehouse"><i class="bi bi-send"></i> Kirim</button>
                                                        </form>
                                                    @else
                                                        <span class="badge bg-secondary">Pending</span>
                                                    @endif
                                                @elseif($status === 'sent_to_produksi')
                                                    <span class="badge bg-warning">Menunggu Tim Warehouse</span>
                                                    @if($userRole === 'produksi')
                                                        <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#approveProduksiModal{{ $p->id }}"><i class="bi bi-check-circle"></i> Approve</button>
                                                        <button class="btn btn-sm btn-danger mt-1" data-bs-toggle="modal" data-bs-target="#rejectProduksiModal{{ $p->id }}"><i class="bi bi-x-circle"></i> Reject</button>
                                                    @endif
                                                @elseif($status === 'approved_produksi')
                                                    <span class="badge bg-info">Disetujui Tim Warehouse</span>
                                                    @if($userRole === 'spv qc')
                                                        <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#approveSPVModal{{ $p->id }}"><i class="bi bi-check-circle"></i> Verifikasi</button>
                                                        <button class="btn btn-sm btn-danger mt-1" data-bs-toggle="modal" data-bs-target="#rejectSPVModal{{ $p->id }}"><i class="bi bi-x-circle"></i> Reject</button>
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
                                                @if($p->verification_notes)
                                                    <small class="text-muted">{{ Str::limit($p->verification_notes, 50) }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @can('view_pemeriksaan_produk_finish_good')
                                                    <a href="{{ route('pemeriksaan-produk-finish-good.show', $p->uuid) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                                @endcan
                                                @can('edit_pemeriksaan_produk_finish_good')
                                                    <a href="{{ route('pemeriksaan-produk-finish-good.edit', $p->uuid) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                                @endcan
                                                @can('delete_pemeriksaan_produk_finish_good')
                                                    <form action="{{ route('pemeriksaan-produk-finish-good.destroy', $p->uuid) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="approveProduksiModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Approve Pemeriksaan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('pemeriksaan-produk-finish-good.approve-produksi', $p->uuid) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            @if($p->verification_notes)
                                                                <div class="alert alert-info mb-3"><strong>Catatan Sebelumnya:</strong><br>{{ $p->verification_notes }}</div>
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

                                        <div class="modal fade" id="rejectProduksiModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Pemeriksaan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('pemeriksaan-produk-finish-good.reject-produksi', $p->uuid) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            @if($p->verification_notes)
                                                                <div class="alert alert-info mb-3"><strong>Catatan Sebelumnya:</strong><br>{{ $p->verification_notes }}</div>
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

                                        <div class="modal fade" id="approveSPVModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Verifikasi Pemeriksaan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('pemeriksaan-produk-finish-good.approve-spv', $p->uuid) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            @if($p->verification_notes)
                                                                <div class="alert alert-info mb-3"><strong>Catatan Sebelumnya:</strong><br>{{ $p->verification_notes }}</div>
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

                                        <div class="modal fade" id="rejectSPVModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Pemeriksaan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('pemeriksaan-produk-finish-good.reject-spv', $p->uuid) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            @if($p->verification_notes)
                                                                <div class="alert alert-info mb-3"><strong>Catatan Sebelumnya:</strong><br>{{ $p->verification_notes }}</div>
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
                                            <td colspan="9" class="text-center">Belum ada data</td>
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
            </div>
        </section>
    </div>
</div>
@endsection
