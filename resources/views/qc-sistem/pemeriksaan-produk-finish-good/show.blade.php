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
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-produk-finish-good.index') }}">Pemeriksaan</a></li>
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
                        $jmlSamplingArr = is_array($pemeriksaanProdukFinishGood->jumlah_sampling_array) ? $pemeriksaanProdukFinishGood->jumlah_sampling_array : [];
                        $kemasanArr = is_array($pemeriksaanProdukFinishGood->kondisi_kemasan_array) ? $pemeriksaanProdukFinishGood->kondisi_kemasan_array : [];
                        $warnaArr = is_array($pemeriksaanProdukFinishGood->kondisi_warna_array) ? $pemeriksaanProdukFinishGood->kondisi_warna_array : [];
                        $aromaArr = is_array($pemeriksaanProdukFinishGood->kondisi_aroma_array) ? $pemeriksaanProdukFinishGood->kondisi_aroma_array : [];
                        $logoArr = is_array($pemeriksaanProdukFinishGood->logo_halal_array) ? $pemeriksaanProdukFinishGood->logo_halal_array : [];
                        $dokArr = is_array($pemeriksaanProdukFinishGood->dokumen_halal_array) ? $pemeriksaanProdukFinishGood->dokumen_halal_array : [];
                        $coaArr = is_array($pemeriksaanProdukFinishGood->coa_array) ? $pemeriksaanProdukFinishGood->coa_array : [];
                        $statusArr = is_array($pemeriksaanProdukFinishGood->status_array) ? $pemeriksaanProdukFinishGood->status_array : [];
                        $ketArr = is_array($pemeriksaanProdukFinishGood->keterangan_array) ? $pemeriksaanProdukFinishGood->keterangan_array : [];
                        $rowCount = max(count($idProdukArr), count($kategoriArr), count($kodeArr));
                    @endphp

                    @if($rowCount < 1)
                        <div class="alert alert-light">Tidak ada detail produk.</div>
                    @else
                        @for($i = 0; $i < $rowCount; $i++)
                            @php
                                $pid = $idProdukArr[$i] ?? null;
                                $namaProduk = $pid && isset($produkNamaById[$pid]) ? $produkNamaById[$pid] : '-';
                                $suhuMobilType = $suhuMobilTypeArr[$i] ?? $pemeriksaanProdukFinishGood->suhu_mobil;
                                $suhuMobilValue = $suhuMobilValueArr[$i] ?? $pemeriksaanProdukFinishGood->suhu_mobil_value;
                                $suhuProdukType = $suhuProdukTypeArr[$i] ?? $pemeriksaanProdukFinishGood->suhu_produk;
                                $suhuProdukValue = $suhuProdukValueArr[$i] ?? $pemeriksaanProdukFinishGood->suhu_produk_value;
                                $kondisiProduk = $kondisiProdukArr[$i] ?? $pemeriksaanProdukFinishGood->kondisi_produk;
                                $kondisiProdukSuhuValue = $kondisiProdukSuhuValueArr[$i] ?? null;
                                $produsenList = $produsenArr[$i] ?? [];
                                $produsenList = is_array($produsenList) ? $produsenList : [];
                                $distributorList = $distributorArr[$i] ?? [];
                                $distributorList = is_array($distributorList) ? $distributorList : [];
                            @endphp
                            <div class="card mb-3" style="border-left: 4px solid #435ebe;">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Baris {{ $i + 1 }}</h6>
                                        @if(($statusArr[$i] ?? null) === 'Release')
                                            <span class="badge bg-success">Release</span>
                                        @elseif(($statusArr[$i] ?? null) === 'Hold')
                                            <span class="badge bg-warning">Hold</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless table-sm">
                                                <tr>
                                                    <td width="40%"><strong>Kategori:</strong></td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $kategoriArr[$i] ?? '-' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Produk:</strong></td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $namaProduk }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Suhu Mobil:</strong></td>
                                                    <td>
                                                        @php
                                                            $fmtTemp = function ($v) {
                                                                if ($v === null || $v === '') return null;
                                                                $s = (string) $v;
                                                                return str_contains($s, '°') ? $s : ($s . '°C');
                                                            };
                                                        @endphp
                                                        @if($suhuMobilType)
                                                            @if($suhuMobilType === 'Frozen')
                                                                <span class="badge bg-info">{{ $suhuMobilType }}</span>
                                                            @elseif($suhuMobilType === 'Fresh')
                                                                <span class="badge bg-success">{{ $suhuMobilType }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ $suhuMobilType }}</span>
                                                            @endif
                                                            @if($suhuMobilValue)
                                                                <span class="badge bg-primary">{{ $fmtTemp($suhuMobilValue) }}</span>
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Suhu Produk:</strong></td>
                                                    <td>
                                                        @if($suhuProdukType)
                                                            @if($suhuProdukType === 'Frozen')
                                                                <span class="badge bg-info">{{ $suhuProdukType }}</span>
                                                            @elseif($suhuProdukType === 'Fresh')
                                                                <span class="badge bg-success">{{ $suhuProdukType }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ $suhuProdukType }}</span>
                                                            @endif
                                                            @if($suhuProdukValue)
                                                                <span class="badge bg-primary">{{ $fmtTemp($suhuProdukValue) }}</span>
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Kondisi Produk:</strong></td>
                                                    <td>
                                                        @if($kondisiProduk)
                                                            @if($kondisiProduk === 'Frozen')
                                                                <span class="badge bg-info">{{ $kondisiProduk }}</span>
                                                            @elseif($kondisiProduk === 'Fresh')
                                                                <span class="badge bg-success">{{ $kondisiProduk }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ $kondisiProduk }}</span>
                                                            @endif
                                                            @if($kondisiProdukSuhuValue)
                                                                <span class="badge bg-primary">{{ $fmtTemp($kondisiProdukSuhuValue) }}</span>
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Produsen:</strong></td>
                                                    <td>
                                                        @if(count($produsenList) > 0)
                                                            @foreach($produsenList as $pr)
                                                                <span class="badge bg-primary">{{ $pr }}</span>
                                                            @endforeach
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Distributor:</strong></td>
                                                    <td>
                                                        @if(count($distributorList) > 0)
                                                        @foreach($distributorList as $ds)
                                                        <span class="badge bg-primary">{{ $ds }}</span>
                                                        @endforeach
                                                        @else
                                                        -
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless table-sm">
                                                <tr><td width="40%"><strong>Expire Date:</strong></td><td>{{ $expireArr[$i] ?? '-' }}</td></tr>
                                                <tr><td><strong>Jumlah Datang:</strong></td><td>{{ $jmlDatangArr[$i] ?? '-' }}</td></tr>
                                                <tr><td><strong>Jumlah Sampling:</strong></td><td>{{ $jmlSamplingArr[$i] ?? '-' }}</td></tr>
                                                <tr><td><strong>Negara Produsen:</strong></td><td>{{ $negaraArr[$i] ?? '-' }}</td></tr>
                                                <tr><td><strong>Kode Produksi:</strong></td><td>{{ $kodeArr[$i] ?? '-' }}</td></tr>
                                            </table>
                                        </div>
                                    </div>

                                    @php
                                        $kemasanVal = $kemasanArr[$i] ?? null;
                                        $warnaVal = $warnaArr[$i] ?? null;
                                        $aromaVal = $aromaArr[$i] ?? null;
                                        $logoVal = $logoArr[$i] ?? null;
                                        $dokVal = $dokArr[$i] ?? null;
                                        $coaVal = $coaArr[$i] ?? null;

                                        $hasKondisiOrDok = (
                                            $kemasanVal !== null || $warnaVal !== null || $aromaVal !== null
                                            || $logoVal !== null || $dokVal !== null || $coaVal !== null
                                        );
                                    @endphp
                                    @if($hasKondisiOrDok)
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h6 class="text-primary small mb-2">Kondisi Fisik & Dokumentasi</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong class="small d-block mb-2">Kondisi Fisik:</strong>
                                                        @php
                                                            $kondisiFisikLabels = [
                                                                'kemasan' => ['label' => 'Kemasan', 'value' => $kemasanVal],
                                                                'warna' => ['label' => 'Warna', 'value' => $warnaVal],
                                                                'aroma' => ['label' => 'Aroma', 'value' => $aromaVal],
                                                            ];
                                                        @endphp
                                                        @foreach($kondisiFisikLabels as $it)
                                                            <div class="d-flex align-items-center small mb-1">
                                                                @if((string)($it['value'] ?? '') === '1')
                                                                    <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                @elseif((string)($it['value'] ?? '') === '0')
                                                                    <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                @else
                                                                    <span class="badge bg-secondary me-2" style="min-width: 24px;">-</span>
                                                                @endif
                                                                <span>{{ $it['label'] }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong class="small d-block mb-2">Dokumentasi:</strong>
                                                        @php
                                                            $dokLabels = [
                                                                'logo' => ['label' => 'Logo Halal', 'value' => $logoVal],
                                                                'dokumen' => ['label' => 'Dokumen Halal', 'value' => $dokVal],
                                                                'coa' => ['label' => 'COA', 'value' => $coaVal],
                                                            ];
                                                        @endphp
                                                        @foreach($dokLabels as $it)
                                                            <div class="d-flex align-items-center small mb-1">
                                                                @if((string)($it['value'] ?? '') === '1')
                                                                    <span class="badge bg-success me-2" style="min-width: 24px;">✓</span>
                                                                @elseif((string)($it['value'] ?? '') === '0')
                                                                    <span class="badge bg-danger me-2" style="min-width: 24px;">✗</span>
                                                                @else
                                                                    <span class="badge bg-secondary me-2" style="min-width: 24px;">-</span>
                                                                @endif
                                                                <span>{{ $it['label'] }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if(($ketArr[$i] ?? null))
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <strong>Keterangan:</strong>
                                                <p class="mt-1 p-2 bg-light rounded small">{{ $ketArr[$i] }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
