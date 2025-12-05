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
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table1" style="white-space: nowrap;">
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
                                    <th>Status</th>
                                    <th>Verifikasi</th>
                                    <th>Catatan Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemeriksaans as $index => $pemeriksaan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
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
                                                <span class="badge bg-info">{{ $pemeriksaan->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </td>
                                        <!-- <td>
                                            {{ $pemeriksaan->no_po ?? '-' }}
                                        </td> -->
                                        <td>
                                            @if($pemeriksaan->bahan)
                                                {{ $pemeriksaan->bahan->nama_bahan }}
                                            @endif
                                        </td>
                                        <!-- <td>
                                            {{ $pemeriksaan->produsen ?? '-' }}
                                        </td> -->
                                        <td>
                                            {{ $pemeriksaan->kode_produksi ?? '-' }}
                                        </td>
                                        <td>
                                            @if($pemeriksaan->status === 'Release')
                                                <span class="badge bg-success">{{ $pemeriksaan->status }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ $pemeriksaan->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $userRole = auth()->user()->role ? strtolower(auth()->user()->role->role) : null;
                                                $status = $pemeriksaan->status_verifikasi ?? 'pending';
                                            @endphp
                                            @if($status === 'pending' || $status === null)
                                                @if($userRole === 'qc inspector')
                                                    <form action="{{ route('pemeriksaan-kedatangan-kemasan.send-to-produksi', $pemeriksaan->uuid) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" title="Kirim ke Produksi"><i class="bi bi-send"></i> Kirim</button>
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
                                            <div class="btn-vertical" role="group">
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
                </div>
            </div>
        </section>
    </div>
</div>
@endsection