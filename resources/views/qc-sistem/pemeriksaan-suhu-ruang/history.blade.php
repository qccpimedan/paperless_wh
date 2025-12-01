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
                    <h3>History Pemeriksaan Suhu Ruang</h3>
                    <p class="text-subtitle text-muted">Riwayat perubahan data pemeriksaan suhu ruang</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang.index') }}">Pemeriksaan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">History</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">History Perubahan Data</h5>
                        <small class="text-muted">
                            Tanggal: {{ $pemeriksaanSuhuRuang->tanggal->format('d/m/Y') }} | 
                            Produk: {{ $pemeriksaanSuhuRuang->produk->nama_bahan }} | 
                            Area: {{ $pemeriksaanSuhuRuang->area->nama_area }}
                        </small>
                    </div>
                    <a href="{{ route('pemeriksaan-suhu-ruang.show', $pemeriksaanSuhuRuang->uuid) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    @forelse($histories as $history)
                        <div class="card mb-3 border-left-warning">
                            <div class="card-header bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="mb-0">
                                            <i class="bi bi-clock-history"></i>
                                            {{ $history->created_at->format('d/m/Y H:i:s') }}
                                        </h6>
                                        <small class="text-muted">Diubah oleh: <strong>{{ $history->user->name }}</strong></small>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="badge bg-info">Edit #{{ $loop->count - $loop->index }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Keterangan Changes -->
                                @if($history->keterangan_lama !== $history->keterangan_baru)
                                    <div class="mb-3">
                                        <h6 class="text-warning"><i class="bi bi-exclamation-circle"></i> Perubahan Keterangan</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Sebelumnya:</strong></label>
                                                <div class="p-2 bg-danger-light rounded" style="background-color: #ffe5e5;">
                                                    <small>{{ $history->keterangan_lama ?? '(Kosong)' }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Sesudahnya:</strong></label>
                                                <div class="p-2 bg-success-light rounded" style="background-color: #e5ffe5;">
                                                    <small>{{ $history->keterangan_baru ?? '(Kosong)' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Tindakan Koreksi Changes -->
                                @if($history->tindakan_koreksi_lama !== $history->tindakan_koreksi_baru)
                                    <div class="mb-3">
                                        <h6 class="text-warning"><i class="bi bi-exclamation-circle"></i> Perubahan Tindakan Koreksi</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Sebelumnya:</strong></label>
                                                <div class="p-2 bg-danger-light rounded" style="background-color: #ffe5e5;">
                                                    <small>{{ $history->tindakan_koreksi_lama ?? '(Kosong)' }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Sesudahnya:</strong></label>
                                                <div class="p-2 bg-success-light rounded" style="background-color: #e5ffe5;">
                                                    <small>{{ $history->tindakan_koreksi_baru ?? '(Kosong)' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Suhu Data Changes -->
                                @php
                                    $oldData = $history->suhu_data_lama ?? [];
                                    $newData = $history->suhu_data_baru ?? [];
                                    $allKeys = array_unique(array_merge(array_keys($oldData ?? []), array_keys($newData ?? [])));
                                @endphp
                                
                                @if(!empty($allKeys))
                                    <div class="mb-3">
                                        <h6 class="text-warning"><i class="bi bi-exclamation-circle"></i> Perubahan Data Suhu</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Lokasi</th>
                                                        <th>Parameter</th>
                                                        <th style="background-color: #ffe5e5;">Sebelumnya</th>
                                                        <th style="background-color: #e5ffe5;">Sesudahnya</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($allKeys as $key)
                                                        @php
                                                            $oldValue = $oldData[$key] ?? null;
                                                            $newValue = $newData[$key] ?? null;
                                                            
                                                            // Convert object to array jika perlu
                                                            if (is_object($oldValue)) {
                                                                $oldValue = json_decode(json_encode($oldValue), true);
                                                            }
                                                            if (is_object($newValue)) {
                                                                $newValue = json_decode(json_encode($newValue), true);
                                                            }
                                                        @endphp
                                                        
                                                        @if(is_array($oldValue) || is_array($newValue))
                                                            @php
                                                                $oldArray = is_array($oldValue) ? $oldValue : [];
                                                                $newArray = is_array($newValue) ? $newValue : [];
                                                                
                                                                $isSimpleObject = false;
                                                                $checkArray = !empty($oldArray) ? $oldArray : $newArray;
                                                                
                                                                if (!empty($checkArray)) {
                                                                    $firstKey = array_key_first($checkArray);
                                                                    // If first key is 'setting', 'display', or 'actual', it's a simple object
                                                                    if (in_array($firstKey, ['setting', 'display', 'actual'])) {
                                                                        $isSimpleObject = true;
                                                                    } else {
                                                                        // Otherwise check if value is array (array of objects)
                                                                        $isSimpleObject = !is_array($checkArray[$firstKey] ?? null);
                                                                    }
                                                                }
                                                            @endphp
                                                            
                                                            @if($isSimpleObject)
                                                                <!-- Handle simple object with setting, display, actual -->
                                                                @foreach(['setting', 'display', 'actual'] as $param)
                                                                    @php
                                                                        $oldVal = $oldArray[$param] ?? null;
                                                                        $newVal = $newArray[$param] ?? null;
                                                                    @endphp
                                                                    <tr>
                                                                        <td><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong></td>
                                                                        <td>{{ ucfirst($param) }}</td>
                                                                        <td style="background-color: #ffe5e5;">{{ $oldVal ?? '-' }}</td>
                                                                        <td style="background-color: #e5ffe5;">{{ $newVal ?? '-' }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <!-- Handle array of objects with numeric keys -->
                                                                @php
                                                                    $allItemKeys = array_unique(array_merge(array_keys($oldArray), array_keys($newArray)));
                                                                @endphp
                                                                @foreach($allItemKeys as $itemKey)
                                                                    @php
                                                                        $oldItem = $oldArray[$itemKey] ?? [];
                                                                        $newItem = $newArray[$itemKey] ?? [];
                                                                        $unitNumber = (int)$itemKey + 1; // Convert 0-based index to 1-based unit number
                                                                    @endphp
                                                                    @if(is_array($oldItem) || is_array($newItem))
                                                                        @foreach(['setting', 'display', 'actual'] as $param)
                                                                            @php
                                                                                $oldVal = $oldItem[$param] ?? null;
                                                                                $newVal = $newItem[$param] ?? null;
                                                                            @endphp
                                                                            <tr>
                                                                                <td><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong> Unit {{ $unitNumber }}</td>
                                                                                <td>{{ ucfirst($param) }}</td>
                                                                                <td style="background-color: #ffe5e5;">{{ $oldVal ?? '-' }}</td>
                                                                                <td style="background-color: #e5ffe5;">{{ $newVal ?? '-' }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @else
                                                            <tr>
                                                                <td colspan="2"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong></td>
                                                                <td style="background-color: #ffe5e5;">{{ $oldValue ?? '-' }}</td>
                                                                <td style="background-color: #e5ffe5;">{{ $newValue ?? '-' }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                @if($history->keterangan_lama === $history->keterangan_baru && 
                                    $history->tindakan_koreksi_lama === $history->tindakan_koreksi_baru && 
                                    $history->suhu_data_lama === $history->suhu_data_baru)
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i> Tidak ada perubahan data pada edit ini
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            <i class="bi bi-inbox"></i> Belum ada history perubahan data
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>

@endsection