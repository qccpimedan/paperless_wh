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
                    <h3>Gudang Dry</h3>
                    @if(request()->query('edit_per_2jam'))
                        <p class="text-subtitle text-muted">Edit Gudang Dry (Per 1 jam)</p>
                    @else
                        <p class="text-subtitle text-muted">Edit Gudang Dry</p>
                    @endif
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}">Pemeriksaan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Pemeriksaan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            @if(request()->query('edit_per_2jam'))
                                <h4 class="card-title">Form Edit Gudang Dry (Per 1 jam)</h4>
                            @else
                                <h4 class="card-title">Form Edit Gudang Dry</h4>
                            @endif
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(request()->query('edit_per_2jam'))
                                    @if (!$canEdit)
                                        <div class="alert alert-warning" role="alert">
                                            <h4 class="alert-heading">⏱️ Edit Belum Tersedia</h4>
                                            <p class="mb-0">
                                                Anda hanya bisa melakukan edit setiap 1 jam sekali.<br>
                                                <strong>Edit berikutnya bisa dilakukan pada: {{ $nextEditTime->format('d/m/Y H:i') }}</strong>
                                            </p>
                                        </div>
                                    @else
                                        <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">✅ Edit Per 1 jam Tersedia</h4>
                                            <p class="mb-0">
                                                Anda dapat melakukan edit data sekarang. Data lama akan disimpan di history.
                                            </p>
                                        </div>
                                    @endif
                                @endif

                                <form class="form form-horizontal" id="edit-form" action="{{ route('pemeriksaan-suhu-ruang-v3.update', $pemeriksaanSuhuRuangV3) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal', $pemeriksaanSuhuRuangV3->tanggal->format('Y-m-d')) }}" required>
                                                @error('tanggal')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="pukul">Pukul <span class="text-danger">*</span></label>
                                                <input type="time" id="pukul" class="form-control @error('pukul') is-invalid @enderror" name="pukul" value="{{ old('pukul', $pemeriksaanSuhuRuangV3->pukul) }}" required>
                                                @error('pukul')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="id_shift">Shift <span class="text-danger">*</span></label>
                                                <select id="id_shift" class="form-control @error('id_shift') is-invalid @enderror" name="id_shift" required>
                                                    <option value="">-- Pilih Shift --</option>
                                                    @foreach($shifts as $shift)
                                                        <option value="{{ $shift->id }}" {{ old('id_shift', $pemeriksaanSuhuRuangV3->id_shift) == $shift->id ? 'selected' : '' }}>{{ $shift->shift }}</option>
                                                    @endforeach
                                                </select>
                                                @error('id_shift')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            @php
                                                $suhuFields = [
                                                    'suhu_premix' => 'Suhu Premix',
                                                    'suhu_seasoning' => 'Suhu Seasoning',
                                                    'suhu_dry' => 'Suhu Dry',
                                                    'suhu_cassing' => 'Suhu Cassing',
                                                    'suhu_beef' => 'Suhu Beef',
                                                    'suhu_packaging' => 'Suhu Packaging',
                                                    'suhu_ruang_chemical' => 'Suhu Ruang Chemical',
                                                    'suhu_ruang_seasoning' => 'Suhu Ruang Seasoning'
                                                ];
                                            @endphp

                                            @foreach($suhuFields as $fieldKey => $fieldLabel)
                                                <div class="col-md-12 mt-4">
                                                    <h5 class="mb-3"><strong>{{ $fieldLabel }} (1-4) <small>(Isi sesuai dengan unit yang digunakan)</small></strong></h5>
                                                </div>
                                                
                                                <!-- Checkbox Section -->
                                                <div class="col-md-12 mt-3 p-3 border rounded bg-light">
                                                    <div class="row">
                                                        @for($i = 1; $i <= 4; $i++)
                                                            <div class="col-md-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input {{ $fieldKey }}-checkbox" type="checkbox" id="{{ $fieldKey }}_{{ $i }}_check" data-unit="{{ $i }}" {{ $pemeriksaanSuhuRuangV3->$fieldKey && isset($pemeriksaanSuhuRuangV3->$fieldKey["unit_$i"]) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="{{ $fieldKey }}_{{ $i }}_check">
                                                                        {{ $fieldLabel }} {{ $i }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>

                                                @for($i = 1; $i <= 4; $i++)
                                                    @php
                                                        $unitData = $pemeriksaanSuhuRuangV3->$fieldKey && isset($pemeriksaanSuhuRuangV3->$fieldKey["unit_$i"]) ? $pemeriksaanSuhuRuangV3->$fieldKey["unit_$i"] : null;
                                                        $isChecked = $unitData !== null;
                                                    @endphp
                                                    <div class="col-md-12 mt-3 p-3 border rounded bg-light {{ $fieldKey }}-unit" id="{{ $fieldKey }}_{{ $i }}_form" style="display: {{ $isChecked ? 'block' : 'none' }};">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="form-label"><strong>{{ $fieldLabel }} {{ $i }}</strong></label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="{{ $fieldKey }}_{{ $i }}_setting">Setting (°C)</label>
                                                                <select id="{{ $fieldKey }}_{{ $i }}_setting" class="form-select form-select-sm" name="{{ $fieldKey }}_{{ $i }}_setting">
                                                                    <option value="">-- Pilih atau Isi Manual --</option>
                                                                    @php $curSetting = old($fieldKey . '_' . $i . '_setting', $unitData['setting'] ?? ''); @endphp
                                                                    <option value="≤30" {{ $curSetting == '≤30' ? 'selected' : '' }}>Std ≤ 30°C</option>
                                                                    <option value="manual" {{ ($curSetting && $curSetting != '≤30') ? 'selected' : '' }}>Input Manual</option>
                                                                </select>
                                                                <input type="text" inputmode="text" id="{{ $fieldKey }}_{{ $i }}_setting_manual"
                                                                    class="form-control form-control-sm mt-1" name="{{ $fieldKey }}_{{ $i }}_setting_manual"
                                                                    placeholder="Masukkan nilai manual"
                                                                    value="{{ ($curSetting && $curSetting != '≤30') ? $curSetting : '' }}"
                                                                    style="display: {{ ($curSetting && $curSetting != '≤30') ? 'block' : 'none' }};">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="{{ $fieldKey }}_{{ $i }}_display">Display (°C)</label>
                                                                <input type="text" inputmode="text" id="{{ $fieldKey }}_{{ $i }}_display" class="form-control form-control-sm" name="{{ $fieldKey }}_{{ $i }}_display" placeholder="Masukkan nilai" value="{{ old($fieldKey . '_' . $i . '_display', $unitData['display'] ?? '') }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="{{ $fieldKey }}_{{ $i }}_actual">Actual (°C)</label>
                                                                <input type="text" inputmode="text" id="{{ $fieldKey }}_{{ $i }}_actual" class="form-control form-control-sm" name="{{ $fieldKey }}_{{ $i }}_actual" placeholder="Masukkan nilai" value="{{ old($fieldKey . '_' . $i . '_actual', $unitData['actual'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor
                                            @endforeach

                                            <!-- Catatan Section -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="mb-3"><strong>Catatan</strong></h5>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea id="keterangan" class="form-control form-control-sm" name="keterangan" placeholder="Keterangan" rows="3">{{ old('keterangan', $pemeriksaanSuhuRuangV3->keterangan) }}</textarea>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="tindakan_koreksi">Tindakan Koreksi</label>
                                                <textarea id="tindakan_koreksi" class="form-control form-control-sm" name="tindakan_koreksi" placeholder="Tindakan Koreksi" rows="3">{{ old('tindakan_koreksi', $pemeriksaanSuhuRuangV3->tindakan_koreksi) }}</textarea>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end mt-4">
                                                <a href="{{ route('pemeriksaan-suhu-ruang-v3.index') }}" class="btn btn-light-secondary me-1 mb-1">
                                                    Kembali
                                                </a>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">
                                                    Update Pemeriksaan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <!-- Riwayat Data Per Jam -->
                                @php
                                    $historiesV3 = $pemeriksaanSuhuRuangV3->histories->sortBy('id');
                                    $timelineRowsV3 = [];
                                    $noCounterV3 = 1;

                                    $suhuSectionsV3 = [
                                        'premix'          => ['label' => 'Suhu Premix', 'field' => 'suhu_premix'],
                                        'seasoning'       => ['label' => 'Suhu Seasoning', 'field' => 'suhu_seasoning'],
                                        'dry'             => ['label' => 'Suhu Dry', 'field' => 'suhu_dry'],
                                        'cassing'         => ['label' => 'Suhu Cassing', 'field' => 'suhu_cassing'],
                                        'beef'            => ['label' => 'Suhu Beef', 'field' => 'suhu_beef'],
                                        'packaging'       => ['label' => 'Suhu Packaging', 'field' => 'suhu_packaging'],
                                        'ruang_chemical'  => ['label' => 'Suhu Ruang Chemical', 'field' => 'suhu_ruang_chemical'],
                                        'ruang_seasoning' => ['label' => 'Suhu Ruang Seasoning', 'field' => 'suhu_ruang_seasoning'],
                                    ];

                                    foreach ($suhuSectionsV3 as $secKey => $secConf) {
                                        $fieldVal = $pemeriksaanSuhuRuangV3->{$secConf['field']};
                                        if (!empty($fieldVal) && is_array($fieldVal)) {
                                            foreach ($fieldVal as $unitKey => $itemData) {
                                                if (is_array($itemData)) {
                                                    $unitNum = str_replace('unit_', '', (string)$unitKey);
                                                    $timelineRowsV3[] = [
                                                        'no'           => $noCounterV3++,
                                                        'waktu'        => $pemeriksaanSuhuRuangV3->pukul ?? '-',
                                                        'area'         => $secConf['label'] . ' ' . $unitNum,
                                                        'setting'      => $itemData['setting'] ?? '-',
                                                        'aktual'       => $itemData['actual'] ?? '-',
                                                        'display'      => $itemData['display'] ?? '-',
                                                        'tipe'         => 'awal',
                                                        'history_uuid' => null,
                                                        'field_type'   => 'suhu_data',
                                                        'section_key'  => $secKey,
                                                        'unit_id'      => $unitNum,
                                                    ];
                                                }
                                            }
                                        }
                                    }

                                    foreach ($historiesV3 as $hItem) {
                                        $waktuRow = $hItem->pukul_baru ?? $hItem->pukul_lama ?? $hItem->created_at->format('H:i');

                                        foreach ($suhuSectionsV3 as $secKey => $secConf) {
                                            $columnBaru = 'suhu_' . $secKey . '_baru';
                                            $valBaru    = $hItem->{$columnBaru};

                                            if (!empty($valBaru)) {
                                                $arrayBaru = is_array($valBaru)
                                                    ? $valBaru
                                                    : (json_decode($valBaru ?? '[]', true) ?: []);

                                                if (is_array($arrayBaru)) {
                                                    foreach ($arrayBaru as $unitKey => $itemData) {
                                                        if (is_array($itemData)) {
                                                            $unitNum = str_replace('unit_', '', (string)$unitKey);
                                                            $timelineRowsV3[] = [
                                                                'no'           => $noCounterV3++,
                                                                'waktu'        => $waktuRow,
                                                                'area'         => $secConf['label'] . ' ' . $unitNum,
                                                                'setting'      => $itemData['setting'] ?? '-',
                                                                'aktual'       => $itemData['actual'] ?? '-',
                                                                'display'      => $itemData['display'] ?? '-',
                                                                'tipe'         => 'update',
                                                                'history_uuid' => $hItem->uuid,
                                                                'field_type'   => 'suhu_data',
                                                                'section_key'  => $secKey,
                                                                'unit_id'      => $unitNum,
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                @endphp

                                @if(!empty($timelineRowsV3))
                                <div class="row mt-5 pt-4 border-top">
                                    <div class="col-12 px-3">
                                        <h5 class="mb-3 d-flex align-items-center gap-2">
                                            <i class="bi bi-clock-history text-primary"></i>
                                            <strong>Riwayat Data Per Jam</strong>
                                            <span class="badge bg-primary ms-1">{{ count($timelineRowsV3) }} entri</span>
                                        </h5>
                                        <div class="alert alert-info py-2 px-3 mb-3" style="font-size:0.875rem;">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Klik tombol <strong>Edit</strong> pada baris riwayat (status <span class="badge bg-warning text-dark">Update</span>) untuk mengoreksi data jam tersebut.
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-sm align-middle mb-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th class="text-center" style="width:4%">No</th>
                                                        <th class="text-center" style="width:8%">Pukul</th>
                                                        <th style="width:20%">Area</th>
                                                        <th class="text-center">Setting (°C)</th>
                                                        <th class="text-center">Actual (°C)</th>
                                                        <th class="text-center">Display (°C)</th>
                                                        <th class="text-center" style="width:8%">Status</th>
                                                        <th class="text-center" style="width:8%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($timelineRowsV3 as $tRow)
                                                    <tr class="{{ $tRow['tipe'] === 'update' ? 'table-warning' : '' }}">
                                                        <td class="text-center">{{ $tRow['no'] }}</td>
                                                        <td class="text-center fw-semibold">{{ $tRow['waktu'] }}</td>
                                                        <td>{{ $tRow['area'] }}</td>
                                                        <td class="text-center">{{ $tRow['setting'] }}</td>
                                                        <td class="text-center">{{ $tRow['aktual'] }}</td>
                                                        <td class="text-center">{{ $tRow['display'] }}</td>
                                                        <td class="text-center">
                                                            @if($tRow['tipe'] === 'awal')
                                                                <span class="badge bg-success">Input Awal</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark">Update</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if($tRow['tipe'] === 'update' && !empty($tRow['history_uuid']))
                                                                <button type="button"
                                                                    class="btn btn-sm btn-primary btn-edit-history-v3"
                                                                    data-history-uuid="{{ $tRow['history_uuid'] }}"
                                                                    data-pukul="{{ $tRow['waktu'] !== '-' ? $tRow['waktu'] : '' }}"
                                                                    data-area="{{ $tRow['area'] }}"
                                                                    data-field-type="{{ $tRow['field_type'] }}"
                                                                    data-section-key="{{ $tRow['section_key'] }}"
                                                                    data-unit-id="{{ $tRow['unit_id'] }}"
                                                                    data-setting="{{ $tRow['setting'] !== '-' ? $tRow['setting'] : '' }}"
                                                                    data-aktual="{{ $tRow['aktual'] !== '-' ? $tRow['aktual'] : '' }}"
                                                                    data-display="{{ $tRow['display'] !== '-' ? $tRow['display'] : '' }}">
                                                                     Edit
                                                                </button>
                                                            @else
                                                                <span class="text-muted" style="font-size:0.75rem">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal Edit History V3 -->
<div class="modal fade" id="editHistoryModalV3" tabindex="-1" aria-labelledby="editHistoryModalV3Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editHistoryFormV3" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editHistoryModalV3Label">
                        <i class="bi bi-pencil-square text-primary me-2"></i>Edit Data Riwayat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="field_type" id="modalV3_field_type">
                    <input type="hidden" name="section_key" id="modalV3_section_key">
                    <input type="hidden" name="unit_id" id="modalV3_unit_id">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Area / Parameter</label>
                        <input type="text" class="form-control bg-light" id="modalV3_area_label" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="modalV3_pukul" class="form-label fw-bold">Pukul <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="pukul" id="modalV3_pukul" required>
                    </div>

                    <div id="modalV3_suhu_data_group">
                        <div class="mb-3">
                            <label for="modalV3_setting" class="form-label fw-bold">Setting (°C)</label>
                            <input type="text" class="form-control" name="setting" id="modalV3_setting" placeholder="Contoh: Std ≤ 30°C">
                        </div>
                        <div class="mb-3">
                            <label for="modalV3_display" class="form-label fw-bold">Display (°C)</label>
                            <input type="text" class="form-control" name="display" id="modalV3_display" placeholder="Contoh: 28">
                        </div>
                        <div class="mb-3">
                            <label for="modalV3_aktual" class="form-label fw-bold">Actual (°C)</label>
                            <input type="text" class="form-control" name="aktual" id="modalV3_aktual" placeholder="Contoh: 27">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fields = ['suhu_premix', 'suhu_seasoning', 'suhu_dry', 'suhu_cassing', 'suhu_beef', 'suhu_packaging', 'suhu_ruang_chemical', 'suhu_ruang_seasoning'];
    
    fields.forEach(field => {
        document.querySelectorAll(`.${field}-checkbox`).forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const unit = this.dataset.unit;
                const form = document.getElementById(`${field}_${unit}_form`);
                if (form) {
                    form.style.display = this.checked ? 'block' : 'none';
                }
            });
        });

        // Toggle input manual for setting dropdown
        for (let i = 1; i <= 4; i++) {
            const settingSelect = document.getElementById(`${field}_${i}_setting`);
            const settingManual = document.getElementById(`${field}_${i}_setting_manual`);
            if (settingSelect && settingManual) {
                settingSelect.addEventListener('change', function() {
                    settingManual.style.display = (this.value === 'manual') ? 'block' : 'none';
                    if (this.value !== 'manual') settingManual.value = '';
                });
            }
        }
    });

    // JS Handler Modal Edit History V3
    const editHistoryModalElementV3 = document.getElementById('editHistoryModalV3');
    if (editHistoryModalElementV3) {
        const editHistoryModalV3 = new bootstrap.Modal(editHistoryModalElementV3);
        const editHistoryFormV3 = document.getElementById('editHistoryFormV3');
        const updateRouteTemplateV3 = "{{ route('pemeriksaan-suhu-ruang-v3.history.update', [$pemeriksaanSuhuRuangV3->uuid, ':historyUuid']) }}";

        document.querySelectorAll('.btn-edit-history-v3').forEach(button => {
            button.addEventListener('click', function() {
                const historyUuid = this.dataset.historyUuid;
                const pukul       = this.dataset.pukul;
                const area        = this.dataset.area;
                const fieldType   = this.dataset.fieldType;
                const sectionKey  = this.dataset.sectionKey;
                const unitId      = this.dataset.unitId;
                const setting     = this.dataset.setting;
                const aktual      = this.dataset.aktual;
                const display     = this.dataset.display;

                editHistoryFormV3.action = updateRouteTemplateV3.replace(':historyUuid', historyUuid);

                document.getElementById('modalV3_area_label').value = area;
                document.getElementById('modalV3_pukul').value      = pukul;
                document.getElementById('modalV3_field_type').value  = fieldType;
                document.getElementById('modalV3_section_key').value = sectionKey || '';
                document.getElementById('modalV3_unit_id').value     = unitId || '';
                document.getElementById('modalV3_setting').value    = setting || '';
                document.getElementById('modalV3_display').value    = display || '';
                document.getElementById('modalV3_aktual').value     = aktual || '';

                editHistoryModalV3.show();
            });
        });
    }
});
</script>

@endsection