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
                    <h3>Golden Sample Report</h3>
                    <p class="text-subtitle text-muted">Kelola Golden Sample Report</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Golden Sample Report</li>
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
                    <h5 class="card-title mb-0">Daftar Golden Sample Report</h5>
                    @can('create_golden_sample_retort')
                        <a href="{{ route('golden-sample-reports.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Report
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="row mb-4 p-3 bg-light rounded">
                        <div class="col-md-12 mb-3">
                            <h6 class="mb-3"><i class="bi bi-funnel"></i> Filter & Cetak PDF</h6>
                        </div>
                        <form action="{{ route('golden-sample-reports.export-pdf') }}" method="GET" class="row g-3" id="pdfFilterForm">
                            <div class="col-md-3">
                                <label class="form-label">Shift</label>
                                <select name="id_shift" class="form-select" id="shiftSelect" required>
                                    <option value="">-- Pilih Shift --</option>
                                    @foreach($shifts ?? [] as $shift)
                                        <option value="{{ $shift->id }}" data-shift-name="{{ $shift->shift }}">
                                            {{ $shift->shift }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3" id="tanggalDariWrapper">
                                <label class="form-label">Tanggal Dari</label>
                                <input type="date" name="tanggal_dari" class="form-control" id="tanggalDari">
                            </div>
                            <div class="col-md-3" id="tanggalSampaiWrapper">
                                <label class="form-label">Tanggal Sampai</label>
                                <input type="date" name="tanggal_sampai" class="form-control" id="tanggalSampai">
                            </div>
                            <div class="col-md-3" id="tanggalSingleWrapper" style="display:none;">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" id="tanggalSingle">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-file-pdf"></i> Cetak PDF
                                </button>
                            </div>
                        </form>
                    </div>

                    <form action="{{ route('golden-sample-reports.index') }}" method="GET" class="row g-3 mb-3">
                        <div class="col-md-9">
                            <input type="text" name="search" class="form-control" placeholder="Cari tanggal/shift/plant/sample type/status..." value="{{ request('search') }}">
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
                                    <th>Sample Type</th>
                                    <th>Masa Penyimpanan</th>
                                    <th>Jumlah Sampel</th>
                                    <th>Verifikasi</th>
                                    <th>Catatan Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $index => $report)
                                    <tr>
                                        <td>{{ ($reports->firstItem() ?? 1) + $index }}</td>
                                        <td>
                                            {{ $report->tanggal }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $report->shift ? $report->shift->shift : 'Shift tidak ditemukan' }}
                                            </span>
                                        </td>
                                        </td>
                                        <td>
                                            @if($report->id_plant && $report->plant)
                                                <span class="badge bg-primary">{{ $report->plant->plant }}</span>
                                            @elseif($report->plant_manual)
                                                <span class="badge bg-warning">{{ $report->plant_manual }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $report->sample_type }}</strong>
                                        </td>
                                        <td>
                                            {{ $report->masa_penyimpanan ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ count($report->samples) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $userRole = auth()->user()->role ? strtolower(auth()->user()->role->role) : null;
                                                $status = $report->status_verifikasi ?? 'pending';
                                            @endphp
                                            
                                            @if($status === 'pending' || $status === null)
                                                @if($userRole === 'qc inspector')
                                                    <form action="{{ route('golden-sample-reports.send-to-produksi', $report->uuid) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" title="Kirim ke Produksi">
                                                            <i class="bi bi-send"></i> Kirim
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-secondary">Pending</span>
                                                @endif
                                            @elseif($status === 'sent_to_produksi')
                                                <span class="badge bg-warning">Menunggu Tim Warehouse</span>
                                                @if($userRole === 'produksi')
                                                    <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#approveProduksiModal{{ $report->id }}" title="Approve">
                                                        <i class="bi bi-check-circle"></i> Approve
                                                    </button>
                                                    <button class="btn btn-sm btn-danger mt-1" data-bs-toggle="modal" data-bs-target="#rejectProduksiModal{{ $report->id }}" title="Reject">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </button>
                                                @endif
                                            @elseif($status === 'approved_produksi')
                                                <span class="badge bg-info">Disetujui Tim Warehouse</span>
                                                @if($userRole === 'spv qc')
                                                    <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#approveSPVModal{{ $report->id }}" title="Verifikasi">
                                                        <i class="bi bi-check-circle"></i> Verifikasi
                                                    </button>
                                                    <button class="btn btn-sm btn-danger mt-1" data-bs-toggle="modal" data-bs-target="#rejectSPVModal{{ $report->id }}" title="Reject">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </button>
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
                                            @if($report->verification_notes)
                                                <small class="text-muted">{{ Str::limit($report->verification_notes, 50) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-vertical">
                                                @can('view_golden_sample_retort')
                                                    <a href="{{ route('golden-sample-reports.show', $report->uuid) }}" 
                                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endcan
                                                @can('edit_golden_sample_retort')
                                                    <a href="{{ route('golden-sample-reports.edit', $report->uuid) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit Data">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endcan
                                                @can('delete_golden_sample_retort')
                                                    <form action="{{ route('golden-sample-reports.destroy', $report->uuid) }}" 
                                                          method="POST" 
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('Yakin ingin menghapus report ini?')">
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
                                    <div class="modal fade" id="approveProduksiModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Approve Report</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('golden-sample-reports.approve-produksi', $report->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($report->verification_notes)
                                                            <div class="alert alert-info mb-3">
                                                                <strong>Catatan Sebelumnya:</strong><br>
                                                                {{ $report->verification_notes }}
                                                            </div>
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
                                    <div class="modal fade" id="rejectProduksiModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Report</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('golden-sample-reports.reject-produksi', $report->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($report->verification_notes)
                                                            <div class="alert alert-info mb-3">
                                                                <strong>Catatan Sebelumnya:</strong><br>
                                                                {{ $report->verification_notes }}
                                                            </div>
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
                                    <div class="modal fade" id="approveSPVModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Verifikasi Report</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('golden-sample-reports.approve-spv', $report->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($report->verification_notes)
                                                            <div class="alert alert-info mb-3">
                                                                <strong>Catatan Sebelumnya:</strong><br>
                                                                {{ $report->verification_notes }}
                                                            </div>
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
                                    <div class="modal fade" id="rejectSPVModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Report</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('golden-sample-reports.reject-spv', $report->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($report->verification_notes)
                                                            <div class="alert alert-info mb-3">
                                                                <strong>Catatan Sebelumnya:</strong><br>
                                                                {{ $report->verification_notes }}
                                                            </div>
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
                                        <td colspan="10" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                                <p class="text-muted mt-2 mb-3">Belum ada Golden Sample Report</p>
                                                <a href="{{ route('golden-sample-reports.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> Tambah Report Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $reports->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
