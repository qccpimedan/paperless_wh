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
                        $jumlah_samplings = json_decode($pemeriksaanKedatanganKemasan->jumlah_sampling_array ?? '[]', true) ?? [];
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
                                <h6 class="mb-0">Bahan: {{ $produkNamaById[$produkId] ?? '-' }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="40%"><strong>Produsen:</strong></td>
                                                <td>
                                                    @if(count($prodList) > 1)
                                                        <ol class="mb-0 ps-3">
                                                            @foreach($prodList as $item)<li>{{ $item }}</li>@endforeach
                                                        </ol>
                                                    @elseif(count($prodList) === 1)
                                                        {{ $prodList[0] }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="40%"><strong>Distributor:</strong></td>
                                                <td>
                                                    @if(count($distList) > 1)
                                                        <ol class="mb-0 ps-3">
                                                            @foreach($distList as $item)<li>{{ $item }}</li>@endforeach
                                                        </ol>
                                                    @elseif(count($distList) === 1)
                                                        {{ $distList[0] }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
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
                                                    <tr><td><strong>Jumlah Datang:</strong></td><td>{{ $jumlah_datangs[$index] ?? '-' }}</td></tr>
                                                    <tr><td><strong>Jumlah Sampling:</strong></td><td>{{ $jumlah_samplings[$index] ?? '-' }}</td></tr>
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
                                            <div class="col-md-6">
                                                <strong class="small d-block mb-2">Kondisi Fisik:</strong>
                                                <div class="d-flex align-items-center small mb-1">
                                                    @if($penampakans[$index] ?? null)
                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                    @else
                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                    @endif
                                                    <span>Penampakan</span>
                                                </div>
                                                <div class="d-flex align-items-center small mb-1">
                                                    @if($sealings[$index] ?? null)
                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                    @else
                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                    @endif
                                                    <span>Sealing</span>
                                                </div>
                                                <div class="d-flex align-items-center small mb-1">
                                                    @if($cetakans[$index] ?? null)
                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                    @else
                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                    @endif
                                                    <span>Cetakan</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <strong class="small d-block mb-2">Dokumentasi:</strong>
                                                <div class="d-flex align-items-center small mb-1">
                                                    @if($logo_halals[$index] ?? null)
                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                    @else
                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                    @endif
                                                    <span>Logo Halal</span>
                                                </div>
                                                <div class="d-flex align-items-center small mb-1">
                                                    @if($dokumen_halals[$index] ?? null)
                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                    @else
                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                    @endif
                                                    <span>Dokumen Halal</span>
                                                </div>
                                                <div class="d-flex align-items-center small mb-1">
                                                    @if($coas[$index] ?? null)
                                                        <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                    @else
                                                        <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                    @endif
                                                    <span>COA</span>
                                                </div>
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
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <h6 class="text-primary small mb-2">Gambar Kemasan</h6>
                                                    <div class="p-2 bg-white rounded">
                                                        <img src="{{ asset('storage/' . $imgPath) }}" alt="Gambar Kemasan" style="max-width: 260px; height: auto; border: 1px solid #ddd; padding: 4px;">
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