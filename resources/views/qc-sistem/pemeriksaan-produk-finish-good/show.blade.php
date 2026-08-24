@extends('layouts.app')
@section('container')
<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h3>Detail Pemeriksaan Produk Finish Good</h3>
                </div>
                <div class="col-12 col-md-6">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-produk-finish-good.index') }}">Pemeriksaan Produk Finish Good</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Detail Pemeriksaan Finish Good</h4>
                    <div>
                        <a href="{{ route('pemeriksaan-produk-finish-good.edit', $pemeriksaanProdukFinishGood->uuid) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('pemeriksaan-produk-finish-good.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="text-primary mb-3">Informasi Dasar</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td width="40%"><strong>Tanggal:</strong></td><td>{{ optional($pemeriksaanProdukFinishGood->tanggal)->format('d/m/Y') }}</td></tr>
                                <tr><td><strong>Shift:</strong></td><td>
                                    @if($pemeriksaanProdukFinishGood->shift)
                                        <span class="badge bg-primary">{{ $pemeriksaanProdukFinishGood->shift->shift }}</span>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                                <tr><td><strong>Segel/Gembok:</strong></td><td>
                                    @if($pemeriksaanProdukFinishGood->segel_gembok)
                                        @if($pemeriksaanProdukFinishGood->segel_gembok === 'segel')
                                            <span class="badge bg-info">Segel</span>
                                        @elseif($pemeriksaanProdukFinishGood->segel_gembok === 'gembok')
                                            <span class="badge bg-warning">Gembok</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($pemeriksaanProdukFinishGood->segel_gembok) }}</span>
                                        @endif
                                        @if($pemeriksaanProdukFinishGood->segel_gembok === 'segel' && $pemeriksaanProdukFinishGood->no_segel)
                                            - {{ $pemeriksaanProdukFinishGood->no_segel }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td></tr>
                                <tr><td><strong>Plant:</strong></td><td>{{ $pemeriksaanProdukFinishGood->user && $pemeriksaanProdukFinishGood->user->plant ? $pemeriksaanProdukFinishGood->user->plant->plant : '-' }}</td></tr>
                                
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><td width="40%"><strong>Nama Supir:</strong></td><td>{{ $pemeriksaanProdukFinishGood->nama_supir ?? '-' }}</td></tr>
                                <tr><td><strong>Jenis Mobil:</strong></td><td>{{ $pemeriksaanProdukFinishGood->jenis_mobil ?? '-' }}</td></tr>
                                <tr><td><strong>No. Mobil:</strong></td><td>{{ $pemeriksaanProdukFinishGood->no_mobil ?? '-' }}</td></tr>
                            </table>
                        </div>
                    </div>

                    <h5 class="text-primary mb-3">Kondisi Mobil Pengangkut</h5>
                    @php
                        $kondisiMobil = is_array($pemeriksaanProdukFinishGood->kondisi_mobil) ? $pemeriksaanProdukFinishGood->kondisi_mobil : [];
                        $yn = function ($v) { return (string)$v === '1' ? 'Ya' : ((string)$v === '0' ? 'Tidak' : '-'); };
                    @endphp
                    <div class="row mb-4">
                        @php
                            $kondisiMobilLabels = [
                                'bersih' => 'Bersih',
                                'bebas_hama' => 'Bebas dari hama',
                                'tidak_kondensasi' => 'Tidak Kondensasi',
                                'bebas_produk_halal' => 'Bebas dari Produk Non Halal',
                                'tidak_berbau' => 'Tidak Berbau Menyimpang',
                                'tidak_ada_sampah' => 'Tidak ada sampah',
                                'tidak_ada_mikroba' => 'Tidak ada pertumbuhan mikroba',
                                'lampu_cover_utuh' => 'Lampu dan Cover tidak pecah',
                                'pallet_utuh' => 'Pallet / Alas Utuh',
                                'tertutup_rapat' => 'Tertutup rapat/tidak bocor',
                                'bebas_kontaminan' => 'Bebas dari Kontaminan',
                            ];
                        @endphp
                        @foreach($kondisiMobilLabels as $key => $label)
                            @php $v = data_get($kondisiMobil, $key); @endphp
                            <div class="col-md-4 mb-2">
                                @if((string)$v === '1')
                                    <span class="badge bg-success">✓</span>
                                @elseif((string)$v === '0')
                                    <span class="badge bg-danger">✗</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                                {{ $label }}
                            </div>
                        @endforeach
                    </div>

                    <h5 class="text-primary mb-3">Detail Produk</h5>
                    @php
                        $produkNamaById = $produkNamaById ?? [];
                        $kategoriArr = is_array($pemeriksaanProdukFinishGood->kategori_code_array) ? $pemeriksaanProdukFinishGood->kategori_code_array : [];
                        $idProdukArr = is_array($pemeriksaanProdukFinishGood->id_produk_array) ? $pemeriksaanProdukFinishGood->id_produk_array : [];
                        $suhuMobilTypeArr = is_array($pemeriksaanProdukFinishGood->suhu_mobil_type_array) ? $pemeriksaanProdukFinishGood->suhu_mobil_type_array : [];
                        $suhuMobilValueArr = is_array($pemeriksaanProdukFinishGood->suhu_mobil_value_array) ? $pemeriksaanProdukFinishGood->suhu_mobil_value_array : [];
                        $suhuProdukTypeArr = is_array($pemeriksaanProdukFinishGood->suhu_produk_type_array) ? $pemeriksaanProdukFinishGood->suhu_produk_type_array : [];
                        $suhuProdukValueArr = is_array($pemeriksaanProdukFinishGood->suhu_produk_value_array) ? $pemeriksaanProdukFinishGood->suhu_produk_value_array : [];
                        $kondisiProdukArr = is_array($pemeriksaanProdukFinishGood->kondisi_produk_array) ? $pemeriksaanProdukFinishGood->kondisi_produk_array : [];
                        $kondisiProdukSuhuValueArr = is_array($pemeriksaanProdukFinishGood->kondisi_produk_suhu_value_array) ? $pemeriksaanProdukFinishGood->kondisi_produk_suhu_value_array : [];
                        $produsenArr = is_array($pemeriksaanProdukFinishGood->produsen_array) ? $pemeriksaanProdukFinishGood->produsen_array : [];
                        $negaraArr = is_array($pemeriksaanProdukFinishGood->negara_produsen_array) ? $pemeriksaanProdukFinishGood->negara_produsen_array : [];
                        $distributorArr = is_array($pemeriksaanProdukFinishGood->distributor_array) ? $pemeriksaanProdukFinishGood->distributor_array : [];
                        $kodeArr = is_array($pemeriksaanProdukFinishGood->kode_produksi_array) ? $pemeriksaanProdukFinishGood->kode_produksi_array : [];
                        $expireArr = is_array($pemeriksaanProdukFinishGood->expire_date_array) ? $pemeriksaanProdukFinishGood->expire_date_array : [];
                        $jmlDatangArr = is_array($pemeriksaanProdukFinishGood->jumlah_datang_array) ? $pemeriksaanProdukFinishGood->jumlah_datang_array : [];
                        $unitDatangArr = is_array($pemeriksaanProdukFinishGood->unit_datang_array) ? $pemeriksaanProdukFinishGood->unit_datang_array : [];
                        $jmlSamplingArr = is_array($pemeriksaanProdukFinishGood->jumlah_sampling_array) ? $pemeriksaanProdukFinishGood->jumlah_sampling_array : [];
                        $unitSamplingArr = is_array($pemeriksaanProdukFinishGood->unit_sampling_array) ? $pemeriksaanProdukFinishGood->unit_sampling_array : [];
                        $kemasanArr = is_array($pemeriksaanProdukFinishGood->kondisi_kemasan_array) ? $pemeriksaanProdukFinishGood->kondisi_kemasan_array : [];                        $warnaArr = is_array($pemeriksaanProdukFinishGood->kondisi_warna_array) ? $pemeriksaanProdukFinishGood->kondisi_warna_array : [];
                        $aromaArr = is_array($pemeriksaanProdukFinishGood->kondisi_aroma_array) ? $pemeriksaanProdukFinishGood->kondisi_aroma_array : [];
                        $logoArr = is_array($pemeriksaanProdukFinishGood->logo_halal_array) ? $pemeriksaanProdukFinishGood->logo_halal_array : [];
                        $dokArr = is_array($pemeriksaanProdukFinishGood->dokumen_halal_array) ? $pemeriksaanProdukFinishGood->dokumen_halal_array : [];
                        $coaArr = is_array($pemeriksaanProdukFinishGood->coa_array) ? $pemeriksaanProdukFinishGood->coa_array : [];
                        $statusArr = is_array($pemeriksaanProdukFinishGood->status_array) ? $pemeriksaanProdukFinishGood->status_array : [];
                        $ketArr = is_array($pemeriksaanProdukFinishGood->keterangan_array) ? $pemeriksaanProdukFinishGood->keterangan_array : [];
                        $imgArr = is_array($pemeriksaanProdukFinishGood->image_finish_good_array) ? $pemeriksaanProdukFinishGood->image_finish_good_array : [];
                        $coaFileArr = is_array($pemeriksaanProdukFinishGood->upload_coa_array) ? $pemeriksaanProdukFinishGood->upload_coa_array : [];
                        $rowCount = max(count($idProdukArr), count($kategoriArr), count($kodeArr));

                        $fmtTemp = function ($v) {
                            if ($v === null || $v === '') return null;
                            $s = (string) $v;
                            return str_contains($s, '°') ? $s : ($s . '°C');
                        };

                        $fmtDate = function ($v) {
                            if ($v === null || $v === '') return '-';
                            try {
                                return \Carbon\Carbon::parse($v)->format('d/m/Y');
                            } catch (\Throwable $e) {
                                return (string) $v;
                            }
                        };

                        $normalizeToArray = function ($val) {
                            if ($val === null || $val === '') return [];
                            if (is_array($val)) {
                                return array_values(array_filter($val, fn ($v) => $v !== null && $v !== ''));
                            }
                            if (is_string($val)) {
                                $decoded = json_decode($val, true);
                                if (is_array($decoded)) {
                                    return array_values(array_filter($decoded, fn ($v) => $v !== null && $v !== ''));
                                }
                                $raw = trim($val);
                                return $raw !== '' ? array_values(array_filter(array_map('trim', explode(',', $raw)))) : [];
                            }
                            return [];
                        };

                        // Group by Produk (Model A) but data is stored flat in *_array.
                        // Key includes kategori + negara to avoid mixing different headers.
                        $grouped = [];
                        for ($i = 0; $i < $rowCount; $i++) {
                            $pid = $idProdukArr[$i] ?? null;
                            $kategori = $kategoriArr[$i] ?? null;
                            $negara = $negaraArr[$i] ?? null;

                            if ($pid === null || $pid === '') continue;

                            $key = (string)$pid . '|' . (string)$kategori . '|' . (string)$negara;
                            if (!isset($grouped[$key])) {
                                $grouped[$key] = [
                                    'id_produk' => $pid,
                                    'kategori' => $kategori,
                                    'negara' => $negara,
                                    'produsen' => $produsenArr[$i] ?? null,
                                    'distributor' => $distributorArr[$i] ?? null,
                                    'logo_halal' => $logoArr[$i] ?? null,
                                    'dokumen_halal' => $dokArr[$i] ?? null,
                                    'coa' => $coaArr[$i] ?? null,
                                    'items' => [],
                                ];
                            }

                            $grouped[$key]['items'][] = [
                                'i' => $i,
                                'kode' => $kodeArr[$i] ?? null,
                                'expire' => $expireArr[$i] ?? null,
                                'jumlah_datang' => $jmlDatangArr[$i] ?? null,
                                'unit_datang' => $unitDatangArr[$i] ?? null,
                                'jumlah_sampling' => $jmlSamplingArr[$i] ?? null,
                                'unit_sampling' => $unitSamplingArr[$i] ?? null,
                                'kemasan' => $kemasanArr[$i] ?? null,
                                'warna' => $warnaArr[$i] ?? null,
                                'aroma' => $aromaArr[$i] ?? null,
                                'status' => $statusArr[$i] ?? null,
                                'keterangan' => $ketArr[$i] ?? null,
                                'suhu_mobil_type' => $suhuMobilTypeArr[$i] ?? $pemeriksaanProdukFinishGood->suhu_mobil,
                                'suhu_mobil_value' => $suhuMobilValueArr[$i] ?? $pemeriksaanProdukFinishGood->suhu_mobil_value,
                                'suhu_produk_type' => $suhuProdukTypeArr[$i] ?? $pemeriksaanProdukFinishGood->suhu_produk,
                                'suhu_produk_value' => $suhuProdukValueArr[$i] ?? $pemeriksaanProdukFinishGood->suhu_produk_value,
                                'kondisi_produk' => $kondisiProdukArr[$i] ?? $pemeriksaanProdukFinishGood->kondisi_produk,
                                'kondisi_produk_suhu_value' => $kondisiProdukSuhuValueArr[$i] ?? null,
                            ];
                        }
                    @endphp

                    @if($rowCount < 1)
                        <div class="alert alert-light">Tidak ada detail produk.</div>
                    @else
                        @php
                            $groupList = array_values($grouped);
                        @endphp
                        @if(count($groupList) < 1)
                            <div class="alert alert-light">Tidak ada detail produk.</div>
                        @else
                            @foreach($groupList as $gIndex => $group)
                                @php
                                    $pid = $group['id_produk'] ?? null;
                                    $namaProduk = $pid && isset($produkNamaById[$pid]) ? $produkNamaById[$pid] : '-';
                                    $kategori = $group['kategori'] ?? null;
                                    $negara = $group['negara'] ?? null;
                                    $produsen = $normalizeToArray($group['produsen'] ?? null);
                                    $distributor = $normalizeToArray($group['distributor'] ?? null);
                                    $logoHalal = $group['logo_halal'] ?? null;
                                    $dokumenHalal = $group['dokumen_halal'] ?? null;
                                    $coa = $group['coa'] ?? null;
                                    $items = is_array($group['items'] ?? null) ? $group['items'] : [];
                                @endphp

                                <div class="card mb-3" style="border-left: 4px solid #435ebe;">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Produk {{ $gIndex + 1 }}</h6>
                                                <div class="small">
                                                    <span class="badge bg-info">{{ $namaProduk }}</span>
                                                    @if($kategori)
                                                        <span class="badge bg-info">{{ $kategori }}</span>
                                                    @endif
                                                    @if($negara)
                                                        <span class="badge bg-secondary">Negara: {{ $negara }}</span>
                                                    @endif
                                                    @if(count($produsen) > 0)
                                                        <span class="badge bg-light text-dark border d-inline-block text-start p-2">
                                                            <strong>Produsen:</strong>
                                                            @if(count($produsen) > 1)
                                                                <ol class="mb-0 ps-3 mt-1 text-start">
                                                                    @foreach($produsen as $item)<li>{{ $item }}</li>@endforeach
                                                                </ol>
                                                            @else
                                                                <span class="ms-1">{{ $produsen[0] }}</span>
                                                            @endif
                                                        </span>
                                                    @endif
                                                    @if(count($distributor) > 0)
                                                        <span class="badge bg-light text-dark border d-inline-block text-start p-2 mt-1">
                                                            <strong>Distributor:</strong>
                                                            @if(count($distributor) > 1)
                                                                <ol class="mb-0 ps-3 mt-1 text-start">
                                                                    @foreach($distributor as $item)<li>{{ $item }}</li>@endforeach
                                                                </ol>
                                                            @else
                                                                <span class="ms-1">{{ $distributor[0] }}</span>
                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mt-2 small">
                                                    <span class="me-2"><strong>Dokumen:</strong></span>
                                                    @if((string)($logoHalal ?? '') === '1')
                                                        <span class="badge bg-success me-1">Logo Halal ✓</span>
                                                    @elseif((string)($logoHalal ?? '') === '0')
                                                        <span class="badge bg-danger me-1">Logo Halal ✗</span>
                                                    @else
                                                        <span class="badge bg-secondary me-1">Logo Halal -</span>
                                                    @endif

                                                    @if((string)($dokumenHalal ?? '') === '1')
                                                        <span class="badge bg-success me-1">Dokumen Halal ✓</span>
                                                    @elseif((string)($dokumenHalal ?? '') === '0')
                                                        <span class="badge bg-danger me-1">Dokumen Halal ✗</span>
                                                    @else
                                                        <span class="badge bg-secondary me-1">Dokumen Halal -</span>
                                                    @endif

                                                    @if((string)($coa ?? '') === '1')
                                                        <span class="badge bg-success me-1">COA ✓</span>
                                                    @elseif((string)($coa ?? '') === '0')
                                                        <span class="badge bg-danger me-1">COA ✗</span>
                                                    @else
                                                        <span class="badge bg-secondary me-1">COA -</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- <span class="badge bg-primary">{{ count($items) }} Batch</span> -->
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        @foreach($items as $dIndex => $it)
                                            @php
                                                $status = $it['status'] ?? null;
                                                $kemasanVal = $it['kemasan'] ?? null;
                                                $warnaVal = $it['warna'] ?? null;
                                                $aromaVal = $it['aroma'] ?? null;
                                                $keterangan = $it['keterangan'] ?? null;
                                            @endphp

                                            <div class="border rounded p-3 mb-3" style="background: #fff;">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <strong>Detail {{ $dIndex + 1 }}</strong>
                                                    @if($status === 'Release')
                                                        <span class="badge bg-success">Release</span>
                                                    @elseif($status === 'Hold')
                                                        <span class="badge bg-warning">Hold</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless table-sm mb-0">
                                                            <tr><td width="40%"><strong>Kode Produksi:</strong></td><td>{{ $it['kode'] ?? '-' }}</td></tr>
                                                            <tr><td><strong>Expire Date:</strong></td><td>{{ $fmtDate($it['expire'] ?? null) }}</td></tr>
                                                            <tr><td><strong>Jumlah Datang:</strong></td><td>{{ $it['jumlah_datang'] ?? '-' }} @if(!empty($it['unit_datang']))<strong>{{ $it['unit_datang'] }}</strong>@endif</td></tr>
                                                            <tr><td><strong>Jumlah Sampling:</strong></td><td>{{ $it['jumlah_sampling'] ?? '-' }} @if(!empty($it['unit_sampling']))<strong>{{ $it['unit_sampling'] }}</strong>@endif</td></tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless table-sm mb-0">
                                                            <tr>
                                                                <td width="40%"><strong>Kondisi Fisik:</strong></td>
                                                                <td>
                                                                    <div class="d-flex flex-column gap-1">
                                                                        <div class="d-flex align-items-center small">
                                                                            @if((string)($kemasanVal ?? '') === '1')
                                                                                <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                            @elseif((string)($kemasanVal ?? '') === '0')
                                                                                <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                            @else
                                                                                <span class="badge bg-secondary me-2" style="min-width: 24px;">-</span>
                                                                            @endif
                                                                            <span>Kemasan</span>
                                                                        </div>
                                                                        <div class="d-flex align-items-center small">
                                                                            @if((string)($warnaVal ?? '') === '1')
                                                                                <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                            @elseif((string)($warnaVal ?? '') === '0')
                                                                                <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                            @else
                                                                                <span class="badge bg-secondary me-2" style="min-width: 24px;">-</span>
                                                                            @endif
                                                                            <span>Warna</span>
                                                                        </div>
                                                                        <div class="d-flex align-items-center small">
                                                                            @if((string)($aromaVal ?? '') === '1')
                                                                                <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                            @elseif((string)($aromaVal ?? '') === '0')
                                                                                <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                            @else
                                                                                <span class="badge bg-secondary me-2" style="min-width: 24px;">-</span>
                                                                            @endif
                                                                            <span>Aroma</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                                @if($keterangan)
                                                    <div class="mt-2">
                                                        <strong>Keterangan:</strong>
                                                        <p class="mt-1 p-2 bg-light rounded small mb-0">{{ $keterangan }}</p>
                                                    </div>
                                                @endif

                                                @php
                                                    $imgPath = $imgArr[$it['i'] ?? 0] ?? null;
                                                    $coaPath = $coaFileArr[$it['i'] ?? 0] ?? null;
                                                @endphp
                                                @if($imgPath || $coaPath)
                                                    <div class="row mt-2">
                                                        <div class="col-md-6 offset-md-6">
                                                            <div class="d-flex justify-content-end gap-2 mt-2">
                                                                @if($coaPath)
                                                                    <a href="{{ asset('storage/' . $coaPath) }}" target="_blank" class="btn btn-sm btn-info">
                                                                        <i class="bi bi-file-earmark-text"></i> Lihat File COA
                                                                    </a>
                                                                @endif
                                                                @if($imgPath)
                                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalFotoFG{{ $gIndex }}_{{ $dIndex }}">
                                                                        <i class="bi bi-image"></i> Lihat Foto Produk
                                                                    </button>

                                                                    <!-- Modal Foto -->
                                                                    <div class="modal fade" id="modalFotoFG{{ $gIndex }}_{{ $dIndex }}" tabindex="-1" aria-labelledby="modalFotoFGLabel{{ $gIndex }}_{{ $dIndex }}" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="modalFotoFGLabel{{ $gIndex }}_{{ $dIndex }}">Foto Produk - Produk {{ $gIndex + 1 }} Detail #{{ $dIndex + 1 }}</h5>
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
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
