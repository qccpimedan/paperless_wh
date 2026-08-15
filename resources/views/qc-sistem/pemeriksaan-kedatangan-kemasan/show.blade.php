@extends('layouts.app')
@section('container')
<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h3>Detail Pemeriksaan Kedatangan Kemasan</h3>
                </div>
                <div class="col-12 col-md-6">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}">Pemeriksaan</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Detail Pemeriksaan</h4>
                    <div>
                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.edit', $pemeriksaanKedatanganKemasan->uuid) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('pemeriksaan-kedatangan-kemasan.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informasi Dasar -->
                    <h5 class="text-primary">Informasi Dasar</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Tanggal:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->tanggal->format('d/m/Y') }}</td></tr>
                                <!-- <tr><td><strong>Jenis Pemeriksaan:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->jenis_pemeriksaan ?? '-' }}</td></tr> -->
                                <tr><td><strong>No. PO:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->no_po ?? '-' }}</td></tr>
                                <!-- <tr><td><strong>Status:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->status === 'Release')
                                        <span class="badge bg-success">Release</span>
                                    @else
                                        <span class="badge bg-warning">Hold</span>
                                    @endif
                                </td></tr> -->
                                <tr><td><strong>Segel/Gembok:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->segel_gembok)
                                        @if($pemeriksaanKedatanganKemasan->segel_gembok === 'segel')
                                            <span class="badge bg-info">Segel</span>
                                        @elseif($pemeriksaanKedatanganKemasan->segel_gembok === 'gembok')
                                            <span class="badge bg-warning">Gembok</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $pemeriksaanKedatanganKemasan->segel_gembok }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td></tr>
                                <tr><td><strong>No. Segel:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->segel_gembok === 'segel' && $pemeriksaanKedatanganKemasan->no_segel)
                                        {{ $pemeriksaanKedatanganKemasan->no_segel }}
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td><strong>Nama Supir:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->nama_supir ?? '-' }}</td></tr>
                                <tr><td><strong>Jenis Mobil:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->jenis_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>No. Mobil:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->no_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>Shift:</strong></td><td>
                                    @if($pemeriksaanKedatanganKemasan->shift)
                                        <span class="badge bg-primary">{{ $pemeriksaanKedatanganKemasan->shift->shift }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            </table>
                        </div>
                    </div>

                    <!-- Kondisi Mobil -->
                    <h5 class="text-primary">Kondisi Mobil Pengangkut</h5>
                    @if($pemeriksaanKedatanganKemasan->kondisi_mobil)
                        <div class="row mb-4">
                            @php
                                $kondisiMobil = [
                                    'bersih' => 'Bersih', 'bebas_hama' => 'Bebas dari hama',
                                    'tidak_kondensasi' => 'Tidak Kondensasi', 'bebas_produk_halal' => 'Bebas dari Produk Non Halal',
                                    'tidak_berbau' => 'Tidak Berbau', 'tidak_ada_sampah' => 'Tidak ada sampah',
                                    'tidak_ada_mikroba' => 'Tidak ada mikroba', 'lampu_cover_utuh' => 'Lampu Cover utuh',
                                    'pallet_utuh' => 'Pallet utuh', 'tertutup_rapat' => 'Tertutup rapat',
                                    'bebas_kontaminan' => 'Bebas kontaminan'
                                ];
                            @endphp
                            @foreach($kondisiMobil as $key => $label)
                                <div class="col-md-4 mb-2">
                                    @if(isset($pemeriksaanKedatanganKemasan->kondisi_mobil[$key]) && $pemeriksaanKedatanganKemasan->kondisi_mobil[$key])
                                        <span class="badge bg-success">✓</span>
                                    @else
                                        <span class="badge bg-danger">✗</span>
                                    @endif
                                    {{ $label }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Informasi Kemasan & Supplier (Dynamic Rows) -->
                    <h5 class="text-primary">Informasi Kemasan & Supplier</h5>
                    @php
                        $id_bahans = json_decode($pemeriksaanKedatanganKemasan->id_bahan_array ?? '[]', true) ?? [];
                        $produsens = json_decode($pemeriksaanKedatanganKemasan->produsen_array ?? '[]', true) ?? [];
                        $distributors = json_decode($pemeriksaanKedatanganKemasan->distributor_array ?? '[]', true) ?? [];
                        $kode_produksis = json_decode($pemeriksaanKedatanganKemasan->kode_produksi_array ?? '[]', true) ?? [];
                        $jumlah_datangs = json_decode($pemeriksaanKedatanganKemasan->jumlah_datang_array ?? '[]', true) ?? [];
                        $unit_datangs = json_decode($pemeriksaanKedatanganKemasan->unit_datang_array ?? '[]', true) ?? [];
                        $jumlah_samplings = json_decode($pemeriksaanKedatanganKemasan->jumlah_sampling_array ?? '[]', true) ?? [];
                        $unit_samplings = json_decode($pemeriksaanKedatanganKemasan->unit_sampling_array ?? '[]', true) ?? [];
                        $spesifikasis = json_decode($pemeriksaanKedatanganKemasan->spesifikasi_array ?? '[]', true) ?? [];
                        $penampakans = json_decode($pemeriksaanKedatanganKemasan->penampakan_array ?? '[]', true) ?? [];
                        $sealings = json_decode($pemeriksaanKedatanganKemasan->sealing_array ?? '[]', true) ?? [];
                        $cetakans = json_decode($pemeriksaanKedatanganKemasan->cetakan_array ?? '[]', true) ?? [];
                        $ketebalan_microns = json_decode($pemeriksaanKedatanganKemasan->ketebalan_micron_array ?? '[]', true) ?? [];
                        $dimensis = json_decode($pemeriksaanKedatanganKemasan->dimensi_array ?? '[]', true) ?? [];
                        $statuses = json_decode($pemeriksaanKedatanganKemasan->status_array ?? '[]', true) ?? [];
                        $logo_halals = json_decode($pemeriksaanKedatanganKemasan->logo_halal_array ?? '[]', true) ?? [];
                        $dokumen_halals = json_decode($pemeriksaanKedatanganKemasan->dokumen_halal_array ?? '[]', true) ?? [];
                        $coas = json_decode($pemeriksaanKedatanganKemasan->coa_array ?? '[]', true) ?? [];
                        $keterangans = json_decode($pemeriksaanKedatanganKemasan->keterangan_array ?? '[]', true) ?? [];
                        $image_kemasans = json_decode($pemeriksaanKedatanganKemasan->image_kemasan_array ?? '[]', true) ?? [];

                        $groupedDetailIdx = [];
                        foreach ((array) $id_bahans as $i => $pid) {
                            $pid = $pid === null ? '' : (string) $pid;
                            if ($pid === '') continue;
                            if (!isset($groupedDetailIdx[$pid])) $groupedDetailIdx[$pid] = [];
                            $groupedDetailIdx[$pid][] = $i;
                        }
                    @endphp

                    @forelse($groupedDetailIdx as $produkId => $detailIdxList)
                        @php
                            $firstIdx = $detailIdxList[0] ?? null;
                            $prodVal = $firstIdx !== null ? ($produsens[$firstIdx] ?? null) : null;
                            if (is_array($prodVal)) {
                                $prodList = array_values(array_filter($prodVal, fn ($v) => $v !== null && $v !== ''));
                                $prodText = implode(', ', $prodList);
                            } else {
                                $raw = trim((string) $prodVal);
                                $prodList = $raw !== '' ? array_values(array_filter(array_map('trim', explode(',', $raw)))) : [];
                                $prodText = $raw;
                            }

                            $distVal = $firstIdx !== null ? ($distributors[$firstIdx] ?? null) : null;
                            if (is_array($distVal)) {
                                $distList = array_values(array_filter($distVal, fn ($v) => $v !== null && $v !== ''));
                                $distText = implode(', ', $distList);
                            } else {
                                $raw = trim((string) $distVal);
                                $distList = $raw !== '' ? array_values(array_filter(array_map('trim', explode(',', $raw)))) : [];
                                $distText = $raw;
                            }
                        @endphp

                        <div class="card mb-3" style="border-left: 4px solid #435ebe;">
                            <div class="card-header bg-light">
                                @php
                                    $pNama = $produkNamaById[$produkId] ?? '-';
                                @endphp
                                <h6 class="mb-1">Produk {{ $loop->iteration }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex flex-wrap gap-2">
                                        @if($pNama)
                                            <span class="badge bg-info">{{ $pNama }}</span>
                                        @endif

                                        @if(count($prodList) > 0)
                                            <span class="badge bg-light text-dark border d-inline-block text-start p-2">
                                                <strong>Produsen:</strong>
                                                @if(count($prodList) > 1)
                                                    <ol class="mb-0 ps-3 mt-1 text-start">
                                                        @foreach($prodList as $item)<li>{{ $item }}</li>@endforeach
                                                    </ol>
                                                @else
                                                    <span class="ms-1">{{ $prodList[0] }}</span>
                                                @endif
                                            </span>
                                        @endif

                                        @if(count($distList) > 0)
                                            <span class="badge bg-light text-dark border d-inline-block text-start p-2">
                                                <strong>Distributor:</strong>
                                                @if(count($distList) > 1)
                                                    <ol class="mb-0 ps-3 mt-1 text-start">
                                                        @foreach($distList as $item)<li>{{ $item }}</li>@endforeach
                                                    </ol>
                                                @else
                                                    <span class="ms-1">{{ $distList[0] }}</span>
                                                @endif
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-2 small">
                                        <span class="me-2"><strong>Dokumen:</strong></span>
                                        @php
                                            $firstIdx = $detailIdxList[0] ?? null;
                                            $logoVal = $firstIdx !== null ? ($logo_halals[$firstIdx] ?? null) : null;
                                            $dokVal = $firstIdx !== null ? ($dokumen_halals[$firstIdx] ?? null) : null;
                                            $coaVal = $firstIdx !== null ? ($coas[$firstIdx] ?? null) : null;
                                        @endphp
                                        
                                        @if($logoVal)
                                            <span class="badge bg-success me-1">Logo Halal ✓</span>
                                        @else
                                            <span class="badge bg-danger me-1">Logo Halal ✗</span>
                                        @endif

                                        @if($dokVal)
                                            <span class="badge bg-success me-1">Dokumen Halal ✓</span>
                                        @else
                                            <span class="badge bg-danger me-1">Dokumen Halal ✗</span>
                                        @endif

                                        @if($coaVal)
                                            <span class="badge bg-success me-1">COA ✓</span>
                                        @else
                                            <span class="badge bg-danger me-1">COA ✗</span>
                                        @endif
                                    </div>
                                </div>

                                @foreach($detailIdxList as $detailNo => $index)
                                    <div class="border rounded p-3 mb-3" style="background: #fff;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold">Detail #{{ $detailNo + 1 }}</span>
                                            @if(isset($statuses[$index]))
                                                @if($statuses[$index] === 'Release')
                                                    <span class="badge bg-success">Release</span>
                                                @elseif($statuses[$index] === 'Hold')
                                                    <span class="badge bg-warning">Hold</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $statuses[$index] ?? '-' }}</span>
                                                @endif
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless table-sm">
                                                    <tr><td width="40%"><strong>Kode Produksi:</strong></td><td>{{ $kode_produksis[$index] ?? '-' }}</td></tr>
                                                    <tr><td><strong>Jumlah Datang:</strong></td><td>{{ $jumlah_datangs[$index] ?? '-' }} @if($unit_datangs[$index] ?? null)<strong>{{ $unit_datangs[$index] }}</strong>@endif</td></tr>
                                                    <tr><td><strong>Jumlah Sampling:</strong></td><td>{{ $jumlah_samplings[$index] ?? '-' }} @if($unit_samplings[$index] ?? null)<strong>{{ $unit_samplings[$index] }}</strong>@endif</td></tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless table-sm">
                                                    <tr><td width="40%"><strong>Spesifikasi:</strong></td><td>{{ $spesifikasis[$index] ?? '-' }}</td></tr>
                                                    <tr><td><strong>Ketebalan (Micron):</strong></td><td>{{ $ketebalan_microns[$index] ?? '-' }}</td></tr>
                                                    <tr><td><strong>Dimensi:</strong></td><td>{{ $dimensis[$index] ?? '-' }}</td></tr>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6 offset-md-6">
                                                <table class="table table-borderless table-sm mb-0">
                                                    <tr>
                                                        <td width="40%"><strong>Kondisi Fisik:</strong></td>
                                                        <td>
                                                            <div class="d-flex flex-column gap-1">
                                                                <div class="d-flex align-items-center small">
                                                                    @if($penampakans[$index] ?? null)
                                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                    @else
                                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                    @endif
                                                                    <span>Penampakan</span>
                                                                </div>
                                                                <div class="d-flex align-items-center small">
                                                                    @if($sealings[$index] ?? null)
                                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                    @else
                                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                    @endif
                                                                    <span>Sealing</span>
                                                                </div>
                                                                <div class="d-flex align-items-center small">
                                                                    @if($cetakans[$index] ?? null)
                                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                    @else
                                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                    @endif
                                                                    <span>Cetakan</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        @if($keterangans[$index] ?? null)
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <strong>Keterangan:</strong>
                                                    <p class="mt-1 p-2 bg-light rounded small">{{ $keterangans[$index] }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @php
                                            $imgPath = $image_kemasans[$index] ?? null;
                                        @endphp
                                        @if($imgPath)
                                            <div class="mt-3">
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $index }}">
                                                    <i class="bi bi-image"></i> Lihat Foto Produk
                                                </button>

                                                <!-- Modal Foto -->
                                                <div class="modal fade" id="modalFoto{{ $index }}" tabindex="-1" aria-labelledby="modalFotoLabel{{ $index }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalFotoLabel{{ $index }}">Foto Produk - Detail #{{ $detailNo + 1 }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center bg-light">
                                                                <img src="{{ asset('storage/' . $imgPath) }}" alt="Foto Produk" class="img-fluid rounded shadow-sm border p-1" style="max-height: 80vh;">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <a href="{{ asset('storage/' . $imgPath) }}" target="_blank" class="btn btn-info btn-sm">Buka di Tab Baru</a>
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">Tidak ada data dynamic form</div>
                    @endforelse

                    <!-- Kondisi Fisik & Dokumentasi -->
                    <!-- <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-primary">Kondisi Fisik</h5>
                            @if($pemeriksaanKedatanganKemasan->kondisi_fisik)
                                @php
                                    $kondisiFisik = [
                                        'penampakan' => 'Penampakan',
                                        'sealing' => 'Sealing',
                                        'cetakan' => 'Cetakan'
                                    ];
                                @endphp
                                @foreach($kondisiFisik as $key => $label)
                                    <div class="mb-2">
                                        @if(isset($pemeriksaanKedatanganKemasan->kondisi_fisik[$key]) && $pemeriksaanKedatanganKemasan->kondisi_fisik[$key])
                                            <span class="badge bg-success me-2">✓</span>
                                        @else
                                            <span class="badge bg-danger me-2">✗</span>
                                        @endif
                                        {{ $label }}
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Tidak ada data kondisi fisik</p>
                            @endif
                            
                            @if($pemeriksaanKedatanganKemasan->ketebalan_micron)
                                <div class="mt-3 p-2 bg-light rounded">
                                    <strong>Ketebalan:</strong> {{ $pemeriksaanKedatanganKemasan->ketebalan_micron }} Micron
                                </div>
                            @endif
                            @if($pemeriksaanKedatanganKemasan->dimensi)
                                <div class="mt-2 p-2 bg-light rounded">
                                    <strong>Dimensi:</strong> {{ $pemeriksaanKedatanganKemasan->dimensi }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="text-primary">Dokumentasi</h5>
                            <div class="mb-2">
                                @if($pemeriksaanKedatanganKemasan->logo_halal)
                                    <span class="badge bg-success me-2">✓</span>
                                @else
                                    <span class="badge bg-danger me-2">✗</span>
                                @endif
                                Logo Halal
                            </div>
                            <div class="mb-2">
                                @if($pemeriksaanKedatanganKemasan->dokumen_halal)
                                    <span class="badge bg-success me-2">✓</span>
                                @else
                                    <span class="badge bg-danger me-2">✗</span>
                                @endif
                                Persyaratan Dokumen: Halal (berlaku)
                            </div>
                            <div class="mb-2">
                                @if($pemeriksaanKedatanganKemasan->coa)
                                    <span class="badge bg-success me-2">✓</span>
                                @else
                                    <span class="badge bg-danger me-2">✗</span>
                                @endif
                                COA (Certificate of Analysis)
                            </div>
                        </div>
                    </div> -->

                    <!-- Informasi Tambahan -->
                    <!-- <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary">Informasi Tambahan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr><td width="40%"><strong>Dibuat Oleh:</strong></td><td>
                                            <strong>{{ $pemeriksaanKedatanganKemasan->user->name }}</strong>
                                            <br><small class="text-muted">{{ $pemeriksaanKedatanganKemasan->user->username }}</small>
                                        </td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr><td width="40%"><strong>Dibuat Pada:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->created_at->format('d/m/Y H:i:s') }}</td></tr>
                                        <tr><td><strong>Diupdate Pada:</strong></td><td>{{ $pemeriksaanKedatanganKemasan->updated_at->format('d/m/Y H:i:s') }}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </section>
    </div>
</div>
@endsection