@extends('layouts.app')

@section('title', 'Pemeriksaan Kedatangan Chemical')

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
                    <h3>Pemeriksaan Kedatangan Chemical</h3>
                    <p class="text-subtitle text-muted">Daftar data pemeriksaan kedatangan chemical</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pemeriksaan Kedatangan Chemical</li>
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
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Data Pemeriksaan Kedatangan Chemical</h4>
                            @can('create_pemeriksaan_kedatangan_chemical')
                                <a href="{{ route('pemeriksaan-chemical.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Data
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $shifts = \App\Models\Shift::all();
                        @endphp
                        {{-- Filter Form untuk PDF Export --}}
                        <div class="row mb-4 p-3 bg-light rounded">
                            <div class="col-md-12 mb-3">
                                <h6 class="mb-3"><i class="bi bi-funnel"></i> Filter & Cetak PDF</h6>
                            </div>
                            <form action="{{ route('pemeriksaan-chemical.export-pdf') }}" method="GET" class="row g-3" id="pdfFilterForm">
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
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-file-pdf"></i> Cetak PDF
                                    </button>
                                </div>
                            </form>
                        </div>

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
                                    if (!shiftSelect) return;

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
                            <table class="table table-striped text-center" id="table1" style="white-space: nowrap;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Shift</th>
                                        <th>Plant</th>
                                        <th>Nama Chemical</th>
                                        <th>Produsen</th>
                                        <th>Kode Produksi</th>
                                        <th>Status</th>
                                        <th>Verifikasi</th>
                                        <th>Catatan Verifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pemeriksaans as $index => $pemeriksaan)
                                        <tr>
                                            <td>{{ $pemeriksaans->firstItem() + $index }}</td>
                                            <td>{{ $pemeriksaan->tanggal ? $pemeriksaan->tanggal->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($pemeriksaan->shift)
                                                    <span class="badge bg-warning">{{ $pemeriksaan->shift->shift }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pemeriksaan->user && $pemeriksaan->user->plant)
                                                    <span class="badge bg-primary">{{ $pemeriksaan->user->plant->plant }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $detailChemicals = $pemeriksaan->detail_chemicals ?? [];
                                                    $chemicalNames = [];
                                                    foreach($detailChemicals as $detail) {
                                                        if(isset($detail['id_chemical'])) {
                                                            $chemical = \App\Models\Chemical::find($detail['id_chemical']);
                                                            if($chemical) {
                                                                $chemicalNames[] = $chemical->nama_chemical;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                @if(count($chemicalNames) > 0)
                                                    @foreach($chemicalNames as $name)
                                                        <span class="badge bg-info">{{ $name }}</span><br>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $produsenNames = [];
                                                    foreach($detailChemicals as $detail) {
                                                        if(isset($detail['id_produsen'])) {
                                                            $produsen = \App\Models\Produsen::find($detail['id_produsen']);
                                                            if($produsen) {
                                                                $produsenNames[] = $produsen->nama_produsen;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                @if(count($produsenNames) > 0)
                                                    {{ implode(', ', array_unique($produsenNames)) }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $detail['kode_produksi'] ?? '-' }}
                                            </td>
                                            <td>
                                                @php
                                                    $statuses = [];
                                                    foreach($detailChemicals as $detail) {
                                                        if(isset($detail['status'])) {
                                                            $statuses[] = $detail['status'];
                                                        }
                                                    }
                                                    $uniqueStatuses = array_unique($statuses);
                                                @endphp
                                                @if(count($uniqueStatuses) > 0)
                                                    @foreach($uniqueStatuses as $status)
                                                        @if($status === 'Release')
                                                            <span class="badge bg-success">{{ $status }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ $status }}</span>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $userRole = auth()->user()->role ? strtolower(auth()->user()->role->role) : null;
                                                    $status = $pemeriksaan->status_verifikasi ?? 'pending';
                                                @endphp
                                                @if($status === 'pending' || $status === null)
                                                    @if($userRole === 'qc inspector')
                                                        <form action="{{ route('pemeriksaan-chemical.send-to-produksi', $pemeriksaan->uuid) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-send"></i> Kirim</button>
                                                        </form>
                                                    @else
                                                        <span class="badge bg-secondary">Pending</span>
                                                    @endif
                                                @elseif($status === 'sent_to_produksi')
                                                    <span class="badge bg-warning">Menunggu Produksi</span>
                                                    @if($userRole === 'produksi')
                                                        <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#approveProduksiModal{{ $pemeriksaan->id }}"><i class="bi bi-check-circle"></i> Approve</button>
                                                        <button class="btn btn-sm btn-danger mt-1" data-bs-toggle="modal" data-bs-target="#rejectProduksiModal{{ $pemeriksaan->id }}"><i class="bi bi-x-circle"></i> Reject</button>
                                                    @endif
                                                @elseif($status === 'approved_produksi')
                                                    <span class="badge bg-info">Disetujui Produksi</span>
                                                    @if($userRole === 'spv qc')
                                                        <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#approveSPVModal{{ $pemeriksaan->id }}"><i class="bi bi-check-circle"></i> Verifikasi</button>
                                                        <button class="btn btn-sm btn-danger mt-1" data-bs-toggle="modal" data-bs-target="#rejectSPVModal{{ $pemeriksaan->id }}"><i class="bi bi-x-circle"></i> Reject</button>
                                                    @endif
                                                @elseif($status === 'approved_spv')
                                                    <span class="badge bg-success">Disetujui SPV QC</span>
                                                @elseif($status === 'rejected_produksi')
                                                    <span class="badge bg-danger">Ditolak Produksi</span>
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
                                                <div class="btn-vertical">
                                                    @can('view_pemeriksaan_kedatangan_chemical')
                                                        <a href="{{ route('pemeriksaan-chemical.show', $pemeriksaan->uuid) }}" class="btn btn-sm btn-info" title="Detail">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endcan
                                                    @can('edit_pemeriksaan_kedatangan_chemical')
                                                        <a href="{{ route('pemeriksaan-chemical.edit', $pemeriksaan->uuid) }}" class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete_pemeriksaan_kedatangan_chemical')
                                                        <form action="{{ route('pemeriksaan-chemical.destroy', $pemeriksaan->uuid) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal untuk Approve/Reject Produksi dan SPV QC -->
@foreach($pemeriksaans as $pemeriksaan)
    <!-- Modal Approve Produksi -->
    <div class="modal fade" id="approveProduksiModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Pemeriksaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pemeriksaan-chemical.approve-produksi', $pemeriksaan->uuid) }}" method="POST">
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
                <form action="{{ route('pemeriksaan-chemical.reject-produksi', $pemeriksaan->uuid) }}" method="POST">
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
                <form action="{{ route('pemeriksaan-chemical.approve-spv', $pemeriksaan->uuid) }}" method="POST">
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
                <form action="{{ route('pemeriksaan-chemical.reject-spv', $pemeriksaan->uuid) }}" method="POST">
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
@endforeach
@endsection