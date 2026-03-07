@extends('layouts.app')

@section('title', 'Pemeriksaan Kedatangan Bahan Baku Penunjang')

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
                    <h3>Pemeriksaan Kedatangan Bahan Baku Penunjang</h3>
                    <p class="text-subtitle text-muted">Daftar data pemeriksaan kedatangan bahan baku penunjang</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pemeriksaan Kedatangan Bahan Baku Penunjang</li>
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
                            <h4 class="card-title">Data Pemeriksaan Kedatangan Bahan Baku Penunjang</h4>
                            @can('create_pemeriksaan_kedatangan_bahan_baku_penunjang')
                                <a href="{{ route('pemeriksaan-bahan-baku.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Bahan Baku
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Filter Form untuk PDF Export --}}
                        <div class="row mb-4 p-3 bg-light rounded">
                            <div class="col-md-12 mb-3">
                                <h6 class="mb-3"><i class="bi bi-funnel"></i> Filter & Cetak PDF</h6>
                            </div>
                            <form action="{{ route('pemeriksaan-bahan-baku.export-pdf') }}" method="GET" class="row g-3" id="pdfFilterForm">
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

                        <form action="{{ route('pemeriksaan-bahan-baku.index') }}" method="GET" class="row g-3 mb-3">
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
                                    const shiftName = selectedOption.getAttribute('data-shift-name');

                                    // Cek apakah shift mengandung "1" atau sama dengan "1" atau "Shift 1"
                                    const isShift1 = shiftName && (shiftName.toLowerCase().includes('1') || shiftName.toLowerCase().includes('pagi'));
                                    // Cek apakah shift mengandung "2" atau "3"
                                    const isShift2or3 = shiftName && (shiftName.toLowerCase().includes('2') || shiftName.toLowerCase().includes('sore') || shiftName.toLowerCase().includes('3') || shiftName.toLowerCase().includes('malam'));

                                    if (isShift1) {
                                        // Shift 1: Tampilkan date range
                                        tanggalDariWrapper.style.display = 'block';
                                        tanggalSampaiWrapper.style.display = 'block';
                                        tanggalSingleWrapper.style.display = 'none';
                                        
                                        tanggalDari.required = true;
                                        tanggalSampai.required = true;
                                        tanggalSingle.required = false;
                                        tanggalSingle.value = '';
                                    } else if (isShift2or3) {
                                        // Shift 2 & 3: Tampilkan single date
                                        tanggalDariWrapper.style.display = 'none';
                                        tanggalSampaiWrapper.style.display = 'none';
                                        tanggalSingleWrapper.style.display = 'block';
                                        
                                        tanggalDari.required = false;
                                        tanggalSampai.required = false;
                                        tanggalSingle.required = true;
                                        tanggalDari.value = '';
                                        tanggalSampai.value = '';
                                    } else {
                                        // Belum pilih shift: sembunyikan semua
                                        tanggalDariWrapper.style.display = 'none';
                                        tanggalSampaiWrapper.style.display = 'none';
                                        tanggalSingleWrapper.style.display = 'none';
                                        
                                        tanggalDari.required = false;
                                        tanggalSampai.required = false;
                                        tanggalSingle.required = false;
                                    }
                                }

                                // Trigger saat shift berubah
                                shiftSelect.addEventListener('change', updateDateFields);

                                // Trigger saat halaman load (untuk maintain state setelah submit)
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
                                        <th>Nama Produk</th>
                                        <!-- <th>Kondisi Produk</th> -->
                                        <!-- <th>Produsen</th> -->
                                        <th>Kode Produksi</th>
                                        <!-- <th>Status</th> -->
                                        <th>Verifikasi</th>
                                        <!-- <th>Verifikasi QC</th>
                                        <th>Verifikasi Produksi</th>
                                        <th>Verifikasi SPV</th> -->
                                        <th>Catatan Verifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pemeriksaans as $index => $pemeriksaan)
                                        <tr>
                                            <td>{{ $pemeriksaans->firstItem() + $index }}</td>
                                            <td>{{ $pemeriksaan->tanggal ? $pemeriksaan->tanggal->format('d/m/Y') : '-' }}</td>
                                            
                                            <!-- <td>{{ $pemeriksaan->no_po ?? '-' }}</td> -->
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
                                                    $idBahanArr = json_decode($pemeriksaan->id_bahan_array ?? '[]', true);
                                                    $idBahanArr = is_array($idBahanArr) ? array_values(array_filter($idBahanArr, function ($v) {
                                                        return $v !== null && $v !== '';
                                                    })) : [];
                                                    $bahanNames = [];
                                                    foreach ($idBahanArr as $bid) {
                                                        $bahan = \App\Models\Bahan::find($bid);
                                                        if ($bahan) {
                                                            $bahanNames[] = $bahan->nama_bahan;
                                                        }
                                                    }

                                                    $bahanPreview = [];
                                                    if (count($bahanNames) === 1) {
                                                        $bahanPreview = [$bahanNames[0]];
                                                    } elseif (count($bahanNames) === 2) {
                                                        $bahanPreview = [$bahanNames[0], $bahanNames[1]];
                                                    } elseif (count($bahanNames) > 2) {
                                                        $bahanPreview = [$bahanNames[0], $bahanNames[count($bahanNames) - 1]];
                                                    }
                                                @endphp
                                                @if(count($bahanPreview) > 0)
                                                    <span class="badge bg-info">{{ $bahanPreview[0] }}</span>
                                                    @if(count($bahanNames) > 2)
                                                        <br>
                                                        <span class="text-muted">...</span>
                                                    @endif
                                                    @if(count($bahanPreview) === 2)
                                                        <br>
                                                        <span class="badge bg-info">{{ $bahanPreview[1] }}</span>
                                                    @endif
                                                @elseif($pemeriksaan->bahan)
                                                    <span class="badge bg-info">{{ $pemeriksaan->bahan->nama_bahan }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
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
                                                    <span class="badge bg-danger">{{ $pemeriksaan->status }}</span>
                                                @endif
                                            </td> -->
                                            <td>
                                                @php
                                                    $userRole = auth()->user()->role ? strtolower(auth()->user()->role->role) : null;
                                                    $status = $pemeriksaan->status_verifikasi ?? 'pending';
                                                @endphp
                                                @if($status === 'pending' || $status === null)
                                                    @if($userRole === 'qc inspector')
                                                        <form action="{{ route('pemeriksaan-bahan-baku.send-to-produksi', $pemeriksaan->uuid) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-send"></i> Kirim</button>
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
                                                    <a href="{{ route('pemeriksaan-bahan-baku.tambah-baris', $pemeriksaan->uuid) }}" 
                                                    class="btn btn-sm btn-success" title="Tambah Baris">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </a>
                                                    @can('view_pemeriksaan_kedatangan_bahan_baku_penunjang')
                                                        <a href="{{ route('pemeriksaan-bahan-baku.show', $pemeriksaan->uuid) }}" 
                                                        class="btn btn-sm btn-info" title="Lihat Detail">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endcan
                                                    @can('edit_pemeriksaan_kedatangan_bahan_baku_penunjang')
                                                        <a href="{{ route('pemeriksaan-bahan-baku.edit', $pemeriksaan->uuid) }}" 
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete_pemeriksaan_kedatangan_bahan_baku_penunjang')
                                                        <form action="{{ route('pemeriksaan-bahan-baku.destroy', $pemeriksaan->uuid) }}" 
                                                            method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" 
                                                                    title="Hapus">
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
                                                    <form action="{{ route('pemeriksaan-bahan-baku.approve-produksi', $pemeriksaan->uuid) }}" method="POST">
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
                                                    <form action="{{ route('pemeriksaan-bahan-baku.reject-produksi', $pemeriksaan->uuid) }}" method="POST">
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
                                                    <form action="{{ route('pemeriksaan-bahan-baku.approve-spv', $pemeriksaan->uuid) }}" method="POST">
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
                                                    <form action="{{ route('pemeriksaan-bahan-baku.reject-spv', $pemeriksaan->uuid) }}" method="POST">
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
                                            <td colspan="16" class="text-center">Tidak ada data</td>
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