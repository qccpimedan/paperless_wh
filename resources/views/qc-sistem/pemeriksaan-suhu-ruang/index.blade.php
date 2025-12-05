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
                    <h3>Data Pemeriksaan Suhu Ruang</h3>
                    <p class="text-subtitle text-muted">Kelola data pemeriksaan suhu ruang</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Pemeriksaan Suhu Ruang</li>
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
                    <h5 class="card-title mb-0">Daftar Pemeriksaan Suhu Ruang</h5>
                    @can('create_pemeriksaan_suhu_ruang')
                        <a href="{{ route('pemeriksaan-suhu-ruang.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Buat Pemeriksaan
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table1" style="white-space:nowrap;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Shift</th>
                                    <th>Plant</th>
                                    <th>Produk</th>
                                    <th>Area</th>
                                    <th>Verifikasi</th>
                                    <th>Catatan Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemeriksaans as $index => $pemeriksaan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pemeriksaan->tanggal->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $pemeriksaan->shift->shift }}</span>
                                        </td>
                                        <td>
                                            @if($pemeriksaan->user->plant)
                                                <span class="badge bg-primary">{{ $pemeriksaan->user->plant->plant }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plant</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $pemeriksaan->produk->nama_bahan }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $pemeriksaan->area->nama_area }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $userRole = auth()->user()->role ? strtolower(auth()->user()->role->role) : null;
                                                $status = $pemeriksaan->status_verifikasi ?? 'pending';
                                            @endphp
                                            
                                            @if($status === 'pending' || $status === null)
                                                @if($userRole === 'qc inspector')
                                                    <form action="{{ route('pemeriksaan-suhu-ruang.send-to-produksi', $pemeriksaan->uuid) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" title="Kirim ke Produksi">
                                                            <i class="bi bi-send"></i> Kirim
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-secondary">Pending</span>
                                                @endif
                                            @elseif($status === 'sent_to_produksi')
                                                <span class="badge bg-warning">Menunggu Produksi</span>
                                                @if($userRole === 'produksi')
                                                    <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#approveProduksiModal{{ $pemeriksaan->id }}" title="Approve">
                                                        <i class="bi bi-check-circle"></i> Approve
                                                    </button>
                                                    <button class="btn btn-sm btn-danger mt-1" data-bs-toggle="modal" data-bs-target="#rejectProduksiModal{{ $pemeriksaan->id }}" title="Reject">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </button>
                                                @endif
                                            @elseif($status === 'approved_produksi')
                                                <span class="badge bg-info">Disetujui Produksi</span>
                                                @if($userRole === 'spv qc')
                                                    <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#approveSPVModal{{ $pemeriksaan->id }}" title="Verifikasi">
                                                        <i class="bi bi-check-circle"></i> Verifikasi
                                                    </button>
                                                    <button class="btn btn-sm btn-danger mt-1" data-bs-toggle="modal" data-bs-target="#rejectSPVModal{{ $pemeriksaan->id }}" title="Reject">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </button>
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
                                                @can('view_pemeriksaan_suhu_ruang')
                                                    <a href="{{ route('pemeriksaan-suhu-ruang.show', $pemeriksaan->uuid) }}" 
                                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endcan
                                                @can('edit_pemeriksaan_suhu_ruang')
                                                    <a href="{{ route('pemeriksaan-suhu-ruang.edit', $pemeriksaan->uuid) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit Data">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ route('pemeriksaan-suhu-ruang.edit', $pemeriksaan->uuid) }}?edit_per_2jam=1" 
                                                       class="btn btn-sm btn-success" title="Edit Per 2 Jam">
                                                        <i class="bi bi-hourglass-bottom"></i>
                                                    </a>
                                                @endcan
                                                @can('view_pemeriksaan_suhu_ruang')
                                                    <a href="{{ route('pemeriksaan-suhu-ruang.history', $pemeriksaan->uuid) }}" 
                                                       class="btn btn-sm btn-secondary" title="Lihat History">
                                                        <i class="bi bi-clock-history"></i>
                                                    </a>
                                                @endcan
                                                @can('delete_pemeriksaan_suhu_ruang')
                                                    <form action="{{ route('pemeriksaan-suhu-ruang.destroy', $pemeriksaan->uuid) }}" 
                                                          method="POST" 
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('Yakin ingin menghapus pemeriksaan ini?')">
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
                                                <form action="{{ route('pemeriksaan-suhu-ruang.approve-produksi', $pemeriksaan->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($pemeriksaan->verification_notes)
                                                            <div class="alert alert-info mb-3">
                                                                <strong>Catatan Sebelumnya:</strong><br>
                                                                {{ $pemeriksaan->verification_notes }}
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
                                    <div class="modal fade" id="rejectProduksiModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Pemeriksaan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pemeriksaan-suhu-ruang.reject-produksi', $pemeriksaan->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($pemeriksaan->verification_notes)
                                                            <div class="alert alert-info mb-3">
                                                                <strong>Catatan Sebelumnya:</strong><br>
                                                                {{ $pemeriksaan->verification_notes }}
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
                                    <div class="modal fade" id="approveSPVModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Verifikasi Pemeriksaan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pemeriksaan-suhu-ruang.approve-spv', $pemeriksaan->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($pemeriksaan->verification_notes)
                                                            <div class="alert alert-info mb-3">
                                                                <strong>Catatan Sebelumnya:</strong><br>
                                                                {{ $pemeriksaan->verification_notes }}
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
                                    <div class="modal fade" id="rejectSPVModal{{ $pemeriksaan->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Pemeriksaan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pemeriksaan-suhu-ruang.reject-spv', $pemeriksaan->uuid) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($pemeriksaan->verification_notes)
                                                            <div class="alert alert-info mb-3">
                                                                <strong>Catatan Sebelumnya:</strong><br>
                                                                {{ $pemeriksaan->verification_notes }}
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
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                                <p class="text-muted mt-2 mb-3">Belum ada data pemeriksaan</p>
                                                <a href="{{ route('pemeriksaan-suhu-ruang.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> Buat Pemeriksaan Pertama
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
