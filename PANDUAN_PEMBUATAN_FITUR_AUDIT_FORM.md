# Panduan Lengkap Pembuatan Fitur Audit pada Form QC System

Dokumen ini berisi panduan *step-by-step* komprehensif untuk merancang dan mengimplementasikan **Fitur Audit (`is_audit`)** pada seluruh modul/form QC System (seperti modul `Chillroom`, `Seasoning`, `Rebox`, `Pemeriksaan Bahan Kemas`, `Pemeriksaan Rice Bites`, `Wizard Proses`, dll) dengan standar **Lazy-Copy Audit (On-Demand Copy saat Edit)**, **Audit-First Smart Filter**, serta **Dynamic Auto-Correction** berbasis **Master Standar Audit**.

---

## 📋 1. Konsep & Arsitektur Fitur Audit

Fitur audit dirancang agar data operasional harian disalin (*cloned*) menjadi **Salinan Data Audit** terpisah secara hemat database, aman, dan efisien:

1. **Data Operasional (`is_audit = false`)**: Data transaksi normal yang diinput oleh staff operasional / QC Inspector.
2. **Data Audit (`is_audit = true`)**: Salinan data operasional khusus yang ditinjau atau disesuaikan oleh Auditor / QC.
3. **Penyambung (`source_uuid`)**: Menyimpan UUID dari data operasional asli yang disalin.
4. **Lazy-Copy On-Demand saat Klik Edit Audit**:
   - Saat data operasional baru disimpan, **TIDAK ADA duplikasi yang dibuat ke database** (Database tetap 100% hemat).
   - Ketika user mengeklik **"Edit Data Audit"**:
     - Jika data belum pernah di-copy ke audit, sistem secara otomatis menggandakan data tersebut ke versi audit (`is_audit = 1`) di background, menerapkan **Auto-Correction** nilai standar, lalu langsung membuka form edit versi audit tersebut.
     - Jika data sudah pernah di-copy sebelumnya, sistem **langsung membuka form edit versi audit yang sudah ada** tanpa membuat duplikat baru (Mencegah Double Data!).
5. **Audit-First Smart Filter pada Halaman Audit**:
   - Halaman Audit secara cerdas menampilkan **semua data audit (`is_audit = 1`)** DIPADUKAN DENGAN **data operasional biasa (`is_audit = 0`) yang belum pernah di-copy ke audit**.
   - Ketika suatu data operasional di-copy/edit ke audit, data aslinya otomatis disembunyikan dari halaman audit dan digantikan oleh versi auditnya (Halaman Audit selalu 100% lengkap tanpa double data).
   - Menggunakan query `pluck('source_uuid')` yang **bebas error collation MySQL** (`Illegal mix of collations`).
6. **Master Standar Audit & Auto-Correction**:
   - Konfigurasi modul dan field diawasi oleh `config/master_audit.php` dan tabel `master_standar_audits` (`MasterStandarAudit`).
   - Nilai data operasional (seperti suhu atau status OK/NG) yang belum di-copy **tetap menampilkan nilai aktual asli** pada halaman audit.
   - Saat proses `copyToAudit()` dipanggil, fungsi `applyAuditAutoCorrections($clone)` secara otomatis menyesuaikan nilai non-standar menjadi nilai standar acak/pas yang valid sesuai batasan Master Standar Audit.
7. **Pengarahan Otomatis Role Auditor (`id_role == 6`)**:
   - Jika pengguna bertindak sebagai Auditor (`id_role == 6`), ketika mengakses route index operasional biasa, sistem secara otomatis meredirect pengguna ke halaman index audit modul tersebut.

---

## 🗄️ 2. Langkah 1: Migrasi Database

Tambahkan kolom `is_audit` dan `source_uuid` pada tabel transaksi modul yang bersangkutan (contoh: `seasonings`).

```bash
php artisan make:migration add_audit_fields_to_seasonings_table --table=seasonings
```

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seasonings', function (Blueprint $table) {
            $table->boolean('is_audit')->default(false)->after('uuid')->index();
            $table->string('source_uuid')->nullable()->after('is_audit')->index();
        });
    }

    public function down()
    {
        Schema::table('seasonings', function (Blueprint $table) {
            $table->dropIndex(['is_audit']);
            $table->dropIndex(['source_uuid']);
            $table->dropColumn(['is_audit', 'source_uuid']);
        });
    }
};
```

---

## ⚙️ 3. Langkah 2: Konfigurasi & Form Index Master Standar Audit

Master Standar Audit merupakan pusat kendali aturan koreksi otomatis (*auto-correction*) data audit. Terdiri dari konfigurasi sistem (`config/master_audit.php`) dan **Halaman Pengelolaan Master Data Audit** (`MasterStandarAuditController` & `resources/views/master-standar-audit/index.blade.php`).

---

### A. Konfigurasi (`config/master_audit.php`)

File `config/master_audit.php` berfungsi sebagai *Single Source of Truth* untuk mendaftarkan modul, tabel utama, tabel relasi, dan daftar kolom yang mendukung auto-koreksi audit.

```php
<?php

return [
    'modules' => [
        'chillroom' => [
            'label'      => '[QF-01/00] Chillroom',
            'main_table' => 'chillroom',
            'fields'     => [
                'suhu'    => ['label' => 'Suhu Chillroom (°C)', 'type' => 'suhu_ruang'],
                'sensori' => ['label' => 'Sensori (OK/NG)', 'type' => 'status_checklist'],
                'kemasan' => ['label' => 'Kondisi Kemasan (OK/NG)', 'type' => 'status_checklist'],
            ],
        ],
        'seasoning' => [
            'label'      => '[QF-01/00] Seasoning',
            'main_table' => 'seasoning',
            'fields'     => [
                'sensori' => ['label' => 'Sensori (OK/NG)', 'type' => 'status_checklist'],
                'kemasan' => ['label' => 'Kondisi Kemasan (OK/NG)', 'type' => 'status_checklist'],
            ],
        ],
        'rebox' => [
            'label'      => '[QF-34/00] Rebox',
            'main_table' => 'rebox',
            'fields'     => [
                'labelisasi' => ['label' => 'Kesesuaian Labelisasi (OK/NG)', 'type' => 'status_checklist'],
                'isi_jumlah' => ['label' => 'Kesesuaian Isi & Jumlah', 'type' => 'status_checklist'],
            ],
        ],
        // Modul-modul QC lainnya...
    ],
];
```

**Tipe Parameter yang Didukung:**
- `suhu`: Suhu produk (penyesuaian nilai acak min s/d max atau `standar_value`).
- `suhu_ruang`: Suhu ruangan.
- `suhu_kondisi`: Suhu ruangan/peralatan kondisi khusus (IQF, dll).
- `suhu_pusat`: Suhu pusat produk.
- `suhu_pemasakan`: Suhu pemasakan/cooking mixer.
- `status_checklist`: Teks status (mengubah `'NG'`, `'tidak ok'`, `'kotor'`, `'rusak'`, `'X'` menjadi `'OK'`).

---

### B. Controller Halaman Master Data Audit (`MasterStandarAuditController.php`)

Controller ini menangani pengambilan kolom auditable secara dinamis via AJAX, penyimpanan batch rules, serta pengelolaan modal edit aturan:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterStandarAudit;
use App\Models\InputArea;
use Illuminate\Support\Facades\Auth;

class MasterStandarAuditController extends Controller
{
    /**
     * Tampilkan Halaman Index Master Standar Audit (Form Batch & Table Aturan)
     */
    public function index(Request $request)
    {
        $selectedModule = $request->get('module');
        $perPage        = $request->get('per_page', 5);
        $modules        = $this->getQcModules();

        $standars = MasterStandarAudit::with('area')
            ->when($selectedModule, function ($q) use ($selectedModule) {
                $q->where('module_name', $selectedModule);
            })
            ->orderBy('module_name', 'asc')
            ->paginate($perPage)
            ->appends($request->all());

        $user = Auth::user();
        $areas = InputArea::when($user->role !== 'superadmin', function ($q) use ($user) {
                $q->where('id_plan', $user->id_plan);
            })
            ->orderBy('area', 'asc')
            ->get();

        return view('master-standar-audit.index', compact('modules', 'standars', 'areas', 'selectedModule', 'perPage'));
    }

    /**
     * Endpoint AJAX untuk Mengambil Kolom Auditable Berdasarkan Modul yang Dipilih
     */
    public function getColumns(Request $request)
    {
        $moduleKey = $request->get('module');
        $modulesConfig = config('master_audit.modules', []);

        if (!isset($modulesConfig[$moduleKey])) {
            return response()->json([]);
        }

        $moduleConfig = $modulesConfig[$moduleKey];
        $columns = [];

        if (isset($moduleConfig['fields'])) {
            foreach ($moduleConfig['fields'] as $colName => $fieldConfig) {
                $columns[] = [
                    'name'       => $colName,
                    'label'      => $fieldConfig['label'] ?? $colName,
                    'type'       => $fieldConfig['type'] ?? 'status_checklist',
                    'table_name' => $moduleConfig['main_table'] ?? $moduleKey,
                ];
            }
        }

        return response()->json($columns);
    }

    /**
     * Simpan / Update Aturan (Multi Batch Item Form Input)
     */
    public function store(Request $request)
    {
        if ($request->has('items') && is_array($request->items)) {
            $request->validate([
                'module_name'            => 'required|string',
                'items'                  => 'required|array|min:1',
                'items.*.column_name'    => 'required|string',
                'items.*.parameter_type' => 'required|in:suhu,suhu_ruang,suhu_kondisi,suhu_pusat,suhu_pemasakan,status_checklist',
            ]);

            $moduleName = $request->module_name;
            $savedCount = 0;

            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $moduleName, &$savedCount) {
                foreach ($request->items as $item) {
                    if (empty($item['enabled'])) continue;

                    $tableName = !empty($item['table_name']) ? $item['table_name'] : $moduleName;

                    MasterStandarAudit::updateOrCreate(
                        [
                            'module_name' => $moduleName,
                            'area_id'     => null,
                            'column_name' => $item['column_name'],
                        ],
                        [
                            'table_name'      => $tableName,
                            'parameter_type'  => $item['parameter_type'],
                            'min_value'       => isset($item['min_value']) && $item['min_value'] !== '' ? $item['min_value'] : null,
                            'max_value'       => isset($item['max_value']) && $item['max_value'] !== '' ? $item['max_value'] : null,
                            'auto_fix_status' => !empty($item['auto_fix_status']),
                        ]
                    );
                    $savedCount++;
                }
            });

            return redirect()->back()->with('success', "Berhasil menyimpan {$savedCount} aturan audit!");
        }
    }

    /**
     * Update Aturan via Modal Edit
     */
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'parameter_type' => 'required|in:suhu,suhu_ruang,suhu_kondisi,suhu_pusat,suhu_pemasakan,status_checklist',
            'min_value'      => 'nullable|numeric',
            'max_value'      => 'nullable|numeric',
        ]);

        $rule = MasterStandarAudit::where('uuid', $uuid)->firstOrFail();
        $rule->update([
            'area_id'         => $request->area_id ?: null,
            'parameter_type'  => $request->parameter_type,
            'min_value'       => $request->min_value,
            'max_value'       => $request->max_value,
            'auto_fix_status' => $request->has('auto_fix_status') ? true : false,
        ]);

        return redirect()->back()->with('success', 'Aturan master audit berhasil diperbarui!');
    }

    /**
     * Hapus Aturan Standar Audit
     */
    public function destroy($uuid)
    {
        $rule = MasterStandarAudit::where('uuid', $uuid)->firstOrFail();
        $rule->delete();

        return redirect()->back()->with('success', 'Aturan standar audit berhasil dihapus!');
    }
}
```

---

### C. Komponen UI Form Index Master Standar Audit (`resources/views/master-standar-audit/index.blade.php`)

Halaman Master Data Audit didesain dengan tata letak 2-Kolom interaktif:
1. **Sisi Kiri (Sticky Smart Input Form)**:
   - User memilih **Target Modul QC** dari *dropdown*.
   - JavaScript secara otomatis memanggil `/master-standar-audit/get-columns` via AJAX dan menampilkan seluruh kartu kolom (*Column Cards*) yang auditable.
   - User dapat mengatur range Min/Max (°C), memilih jenis parameter, serta mengaktifkan/nonaktifkan tombol **"Auto fix OK / Ceklis"**.
   - Dilengkapi tombol **"Centang Semua"** dan **"Batal Centang"**.
2. **Sisi Kanan (Daftar Aturan & Priority Rules)**:
   - Menampilkan tabel seluruh aturan aktif dengan filter modul & pagination.
   - Dilengkapi tombol **Edit Modal** (`#editAuditModal`) dan tombol **Hapus**.
   - Menerangkan **Urutan Prioritas Penerapan Koreksi Otomatis**:
     1. **Priority 1 (Khusus Area)**: Diaplikasikan jika `area_id` data aktual cocok dengan aturan.
     2. **Priority 2 (Global Tabel)**: Diaplikasikan jika tidak ada aturan khusus area yang cocok.
     3. **Priority 3 (Fallback Koreksi Umum)**: Jika kolom tidak terdaftar, otomatis mengubah status NOT OK / NG menjadi OK / Ceklis.

---

## 🧬 4. Langkah 3: Model & Trait `HasAudit`

### A. Kode Trait Model (`app/Models/Traits/HasAudit.php`)

```php
<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasAudit
{
    /**
     * Override di model jika ingin approval tetap terbawa saat copy to audit
     */
    protected function keepApprovalsOnAudit(): bool
    {
        return false;
    }

    /**
     * Relasi ke versi audit dari record operasional ini
     */
    public function auditVersion()
    {
        return $this->hasOne(static::class, 'source_uuid', 'uuid')->where('is_audit', true);
    }

    /**
     * Relasi ke versi operasional asli dari record audit ini
     */
    public function originalVersion()
    {
        return $this->belongsTo(static::class, 'source_uuid', 'uuid')->where('is_audit', false);
    }

    /**
     * Menyalin data operasional ke versi audit + menerapkan Auto-Correction
     */
    public function copyToAudit(): self
    {
        $clone = $this->replicate();
        $clone->uuid = (string) Str::uuid();
        $clone->is_audit = true;
        $clone->source_uuid = $this->uuid;

        if (!$this->keepApprovalsOnAudit()) {
            // Reset approval boolean fields ke false
            $booleanApprovalFields = [
                'approved_by_qc',
                'approved_by_produksi',
                'approved_by_spv',
            ];

            // Reset approval nullable fields ke null
            $nullableApprovalFields = [
                'qc_approved_by', 'qc_approved_at',
                'produksi_approved_by', 'produksi_approved_at',
                'spv_approved_by', 'spv_approved_at',
            ];

            foreach ($booleanApprovalFields as $field) {
                if (array_key_exists($field, $clone->getAttributes())) {
                    $clone->{$field} = false;
                }
            }

            foreach ($nullableApprovalFields as $field) {
                if (array_key_exists($field, $clone->getAttributes())) {
                    $clone->{$field} = null;
                }
            }
        }

        // Auto-Correction Data Audit berdasarkan Master Standar & Aturan Umum QC
        $this->applyAuditAutoCorrections($clone);

        $clone->save();
        return $clone;
    }

    /**
     * Terapkan penyesuaian nilai otomatis pada objek salinan audit.
     * Mengatur: Suhu, Suhu Ruang, Suhu Kondisi, Suhu Pusat, Suhu Pemasakan & Status Checklist.
     */
    protected function applyAuditAutoCorrections($clone): void
    {
        $tableName = $clone->getTable();

        // Deteksi area_id dari record jika ada
        $recordAreaId = null;
        foreach (['area_id', 'id_area', 'id_input_area'] as $areaCol) {
            if (array_key_exists($areaCol, $clone->getAttributes()) && !empty($clone->{$areaCol})) {
                $recordAreaId = $clone->{$areaCol};
                break;
            }
        }

        // Ambil aturan terkait dari Master Standar Audit
        $allRules = \App\Models\MasterStandarAudit::all()->filter(function ($rule) use ($tableName) {
            if ($rule->table_name === $tableName || $rule->module_name === $tableName) {
                return true;
            }
            if (!empty($rule->module_name) && str_contains($tableName, $rule->module_name)) {
                return true;
            }
            if (!empty($rule->table_name) && str_contains($tableName, $rule->table_name)) {
                return true;
            }
            return false;
        });

        // Susun aturan efektif (Area-Spesifik menimpa Global)
        $effectiveRules = collect();
        foreach ($allRules as $rule) {
            $colName = $rule->column_name;
            if ($recordAreaId && $rule->area_id && (int)$rule->area_id === (int)$recordAreaId) {
                $effectiveRules->put($colName, $rule);
            } elseif (!$rule->area_id && !$effectiveRules->has($colName)) {
                $effectiveRules->put($colName, $rule);
            }
        }

        foreach ($effectiveRules as $col => $rule) {
            if (!array_key_exists($col, $clone->getAttributes())) {
                continue;
            }

            $currentVal = $clone->getRawOriginal($col) ?? ($clone->getAttributes()[$col] ?? null);

            // 1. Parameter Jenis Suhu (Numerik)
            if (in_array($rule->parameter_type, ['suhu', 'suhu_ruang', 'suhu_kondisi', 'suhu_pusat', 'suhu_pemasakan']) && is_numeric($currentVal)) {
                $numericVal = (float) $currentVal;
                $isBelow    = $rule->min_value !== null && $numericVal < (float)$rule->min_value;
                $isAbove    = $rule->max_value !== null && $numericVal > (float)$rule->max_value;

                if ($isBelow || $isAbove) {
                    if ($rule->standar_value !== null && $rule->standar_value !== '') {
                        $numericValToSet = is_numeric($rule->standar_value) ? (float)$rule->standar_value : $rule->standar_value;
                    } else {
                        $min = $rule->min_value !== null ? (float)$rule->min_value : 0;
                        $max = $rule->max_value !== null ? (float)$rule->max_value : 30;
                        $seed = crc32(($clone->uuid ?? $clone->id ?? 'audit') . '_' . $col);
                        mt_srand($seed);
                        $numericValToSet = round($min + (mt_rand() / mt_getrandmax()) * ($max - $min), 2);
                        mt_srand();
                    }
                    $clone->setAttribute($col, $numericValToSet);
                }
            }
            // 2. Parameter Jenis Status / Teks (Non-Numerik)
            else if ($rule->parameter_type === 'status_checklist' || !is_numeric($currentVal)) {
                $targetVal = $rule->standar_value ?? 'OK';
                if ($targetVal === '✔' || $targetVal === '✓') { $targetVal = 'OK'; }
                if (is_string($currentVal) && (
                    str_contains(strtolower($currentVal), 'tidak') ||
                    str_contains(strtolower($currentVal), 'ng') ||
                    str_contains(strtolower($currentVal), 'rusak') ||
                    in_array(trim($currentVal), ['✘', 'X', 'x']) ||
                    strtolower(trim($currentVal)) !== strtolower(trim($targetVal))
                )) {
                    $clone->setAttribute($col, $targetVal);
                }
            }
        }

        // Fallback umum: konversi nilai tidak OK pada kolom yang TIDAK terdaftar di Master
        $coveredColumns = $effectiveRules->keys()->toArray();
        $attributes     = $clone->getAttributes();
        foreach ($attributes as $key => $val) {
            if (in_array($key, $coveredColumns)) {
                continue;
            }
            if (is_string($val)) {
                $lowerVal = strtolower(trim($val));
                if (in_array($lowerVal, ['not ok', 'ng', 'reject', 'kotor', 'tidak ok', 'tidak_sesuai', 'tidak sesuai', 'x', '✗', '✘', 'tidak baik'])) {
                    $clone->{$key} = 'OK';
                }
            }
        }
    }

    public function scopeOperasional($query)
    {
        return $query->where('is_audit', false);
    }

    public function scopeAudit($query)
    {
        return $query->where('is_audit', true);
    }

    public function scopeAuditFirstSmartFilter($query, bool $isAudit = true)
    {
        if (!$isAudit) {
            return $query->where('is_audit', false);
        }

        $auditedSourceUuids = static::where('is_audit', true)
            ->whereNotNull('source_uuid')
            ->pluck('source_uuid')
            ->toArray();

        return $query->where(function ($q) use ($auditedSourceUuids) {
            $q->where('is_audit', true)
              ->orWhere(function ($subQ) use ($auditedSourceUuids) {
                  $subQ->where('is_audit', false);
                  if (!empty($auditedSourceUuids)) {
                      $subQ->whereNotIn('uuid', $auditedSourceUuids);
                  }
              });
        });
    }
}
```

### B. Penerapan pada Model (`app/Models/Seasoning.php`)

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasAudit;

class Seasoning extends Model
{
    use HasAudit;

    protected $fillable = [
        'uuid', 'id_plan', 'created_by', 'nama_rm', 'kode_produksi', 'berat', 'sensori', 'kemasan', 'keterangan', 'shift_id', 'tanggal', 'jam', 'kode_form',
        'is_audit', 'source_uuid',
        'approved_by_qc', 'qc_approved_by', 'qc_approved_at',
        'approved_by_produksi', 'produksi_approved_by', 'produksi_approved_at',
        'approved_by_spv', 'spv_approved_by', 'spv_approved_at',
    ];

    protected $casts = [
        'is_audit' => 'boolean',
        'tanggal' => 'datetime:Y-m-d H:i:s',
        'approved_by_qc' => 'boolean',
        'approved_by_produksi' => 'boolean',
        'approved_by_spv' => 'boolean',
    ];
}
```

---

## 🕹️ 5. Langkah 4: Trait Controller `HasAuditController` & Penerapan pada Controller

### A. Kode Trait Controller (`app/Http/Controllers/Traits/HasAuditController.php`)

```php
<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait HasAuditController
{
    abstract protected function auditModel(): string;
    abstract protected function auditRoutePrefix(): string;
    abstract protected function auditViewPrefix(): string;

    protected function auditRelations(): array { return []; }
    protected function auditSearchColumns(): array { return ['nama_rm', 'kode_produksi', 'keterangan']; }
    protected function auditDateColumn(): string { return 'tanggal'; }
    protected function auditTimeColumn(): string { return 'jam_kedatangan'; }
    protected function auditShiftRelation(): string { return 'shift'; }
    protected function auditShiftColumn(): string { return 'shift_id'; }
    protected function auditPlanColumn(): string { return 'id_plan'; }

    /**
     * Smart Copy & Edit Audit: Auto copy saat edit jika belum ada versi audit
     */
    public function copyToAudit($uuid)
    {
        $model = $this->auditModel();
        $item = $model::where('uuid', $uuid)->firstOrFail();

        if (!Auth::user()->hasPermissionTo('edit-' . $this->auditRoutePrefix()) &&
            !Auth::user()->hasPermissionTo('create-' . $this->auditRoutePrefix())) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit data audit.');
        }

        if ($item->is_audit) {
            return redirect()->route("{$this->auditRoutePrefix()}.edit", [$item->uuid, 'is_audit' => 1]);
        }

        if ($item->auditVersion) {
            return redirect()->route("{$this->auditRoutePrefix()}.edit", [$item->auditVersion->uuid, 'is_audit' => 1]);
        }

        $clone = $item->copyToAudit();
        return redirect()->route("{$this->auditRoutePrefix()}.edit", [$clone->uuid, 'is_audit' => 1])
            ->with('info', 'Salinan data audit berhasil dibuat. Silakan lakukan penyesuaian.');
    }

    /**
     * Index Audit dengan Audit-First Smart Query Filter & Collation-Safe Logic
     */
    public function auditIndex(Request $request)
    {
        $model = $this->auditModel();
        $user = Auth::user();
        $search = $request->get('search');
        $perPage = $request->get('per_page', 5);

        // Ambil daftar source_uuid yang sudah di-copy ke audit (Bebas Error Collation)
        $auditedSourceUuids = $model::where('is_audit', true)
            ->whereNotNull('source_uuid')
            ->pluck('source_uuid')
            ->toArray();

        $query = $model::with($this->auditRelations())
            ->where(function ($q) use ($auditedSourceUuids) {
                $q->where('is_audit', true)
                  ->orWhere(function ($subQ) use ($auditedSourceUuids) {
                      $subQ->where('is_audit', false);
                      if (!empty($auditedSourceUuids)) {
                          $subQ->whereNotIn('uuid', $auditedSourceUuids);
                      }
                  });
            })
            ->when($user->role !== 'superadmin', function ($q) use ($user) {
                $q->where($this->auditPlanColumn(), $user->id_plan);
            });

        if ($search) {
            $query->where(function ($q) use ($search) {
                foreach ($this->auditSearchColumns() as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $search . '%');
                }
            });
        }

        $dateColumn = $this->auditDateColumn();
        $timeColumn = $this->auditTimeColumn();

        $records = $query->orderBy($dateColumn, 'desc')
            ->orderBy($timeColumn, 'desc')
            ->paginate($perPage);

        // Group by tanggal + shift
        $groupedData = $records->getCollection()->groupBy(function ($item) {
            $dateStr = $item->{$this->auditDateColumn()}
                ? (is_string($item->{$this->auditDateColumn()})
                    ? \Carbon\Carbon::parse($item->{$this->auditDateColumn()})->format('Y-m-d')
                    : $item->{$this->auditDateColumn()}->format('Y-m-d'))
                : 'no_date';
            return $dateStr . '_' . ($item->{$this->auditShiftColumn()} ?? $item->{$this->auditShiftRelation()} ?? 'no_shift');
        });

        $records->setCollection($groupedData);

        return view("{$this->auditViewPrefix()}.index_audit", compact('records', 'search', 'perPage'));
    }

    /**
     * AJAX Search untuk Halaman Audit Datatable
     */
    public function auditSearchAjax(Request $request)
    {
        try {
            $model = $this->auditModel();
            $user = Auth::user();
            $search = $request->get('search');
            $perPage = (int) $request->get('per_page', 5);
            $page = (int) $request->get('page', 1);

            $auditedSourceUuids = $model::where('is_audit', true)
                ->whereNotNull('source_uuid')
                ->pluck('source_uuid')
                ->toArray();

            $query = $model::with($this->auditRelations())
                ->where(function ($q) use ($auditedSourceUuids) {
                    $q->where('is_audit', true)
                      ->orWhere(function ($subQ) use ($auditedSourceUuids) {
                          $subQ->where('is_audit', false);
                          if (!empty($auditedSourceUuids)) {
                              $subQ->whereNotIn('uuid', $auditedSourceUuids);
                          }
                      });
                })
                ->when($user->role !== 'superadmin', function ($q) use ($user) {
                    $q->where($this->auditPlanColumn(), $user->id_plan);
                });

            if ($search) {
                $query->where(function ($q) use ($search) {
                    foreach ($this->auditSearchColumns() as $column) {
                        $q->orWhere($column, 'LIKE', '%' . $search . '%');
                    }
                });
            }

            $dateColumn = $this->auditDateColumn();
            $timeColumn = $this->auditTimeColumn();

            $records = $query->orderBy($dateColumn, 'desc')
                ->orderBy($timeColumn, 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            $total = $records->total();
            $groupedData = $records->getCollection()->groupBy(function ($item) {
                $dateStr = $item->{$this->auditDateColumn()}
                    ? (is_string($item->{$this->auditDateColumn()})
                        ? \Carbon\Carbon::parse($item->{$this->auditDateColumn()})->format('Y-m-d')
                        : $item->{$this->auditDateColumn()}->format('Y-m-d'))
                    : 'no_date';
                return $dateStr . '_' . ($item->{$this->auditShiftColumn()} ?? $item->{$this->auditShiftRelation()} ?? 'no_shift');
            });
            $records->setCollection($groupedData);

            return response()->json([
                'html' => view("{$this->auditViewPrefix()}._table", compact('records'))->render(),
                'pagination' => [
                    'current_page' => $page,
                    'last_page' => (int) ceil($total / $perPage),
                    'per_page' => $perPage,
                    'total' => $total,
                    'from' => $records->firstItem(),
                    'to' => $records->lastItem(),
                    'has_pages' => $total > $perPage,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper untuk menerapkan Audit-First Smart Query pada Query Export PDF
     */
    protected function applyAuditPdfScope($query, Request $request)
    {
        if ($request->boolean('is_audit')) {
            $model = $this->auditModel();
            $auditedSourceUuids = $model::where('is_audit', true)
                ->whereNotNull('source_uuid')
                ->pluck('source_uuid')
                ->toArray();

            return $query->where(function ($q) use ($auditedSourceUuids) {
                $q->where('is_audit', true)
                  ->orWhere(function ($subQ) use ($auditedSourceUuids) {
                      $subQ->where('is_audit', false);
                      if (!empty($auditedSourceUuids)) {
                          $subQ->whereNotIn('uuid', $auditedSourceUuids);
                      }
                  });
            });
        }

        return $query->where('is_audit', false);
    }
}
```

### B. Penerapan pada Controller Modul (`app/Http/Controllers/SeasoningController.php`)

```php
<?php

namespace App\Http\Controllers;

use App\Models\Seasoning;
use App\Http\Controllers\Traits\HasAuditController;
use Illuminate\Support\Facades\Auth;

class SeasoningController extends Controller
{
    use HasAuditController;

    protected function auditModel(): string { return Seasoning::class; }
    protected function auditRoutePrefix(): string { return 'seasoning'; }
    protected function auditViewPrefix(): string { return 'qc-sistem.seasoning'; }
    protected function auditSearchColumns(): array { return ['nama_rm', 'kode_produksi', 'keterangan']; }

    public function index()
    {
        $user = Auth::user();

        // Auditor (id_role == 6) langsung diarahkan ke halaman audit
        if ($user->id_role == 6) {
            return redirect()->route('seasoning.audit');
        }

        // Method index operasional biasa...
    }
}
```

---

## 🛣️ 6. Langkah 5: Registrasi Route (`routes/web.php`)

```php
Route::middleware(['auth'])->group(function () {
    Route::prefix('qc-sistem')->group(function () {
        // --- Audit Routes untuk Seasoning ---
        Route::get('seasoning/copy-to-audit/{uuid}', [SeasoningController::class, 'copyToAudit'])->name('seasoning.copy-to-audit');
        Route::get('seasoning/audit-data', [SeasoningController::class, 'auditIndex'])->name('seasoning.audit');
        Route::get('seasoning/audit-search', [SeasoningController::class, 'auditSearchAjax'])->name('seasoning.audit-search');

        // --- Resource Utama ---
        Route::resource('seasoning', SeasoningController::class);
    });
});
```

---

## 🎨 7. Langkah 6: Tampilan UI Blade

### A. Reusable Component Audit Dropdown (`resources/views/components/audit-dropdown.blade.php`)

Komponen ini digunakan di halaman operasional untuk memberi akses ke data audit yang bersangkutan:

```blade
<x-audit-dropdown :item="$item" route-prefix="seasoning" />
```

**Kode Komponen (`resources/views/components/audit-dropdown.blade.php`):**

```blade
@php
    $copyRoute  = $routePrefix . '.copy-to-audit';
    $showRoute  = $routePrefix . '.show';
    $editRoute  = $routePrefix . '.edit';
    $hasAuditRoutes = $hasRoute($copyRoute) && $hasRoute($showRoute);
    $itemUuid = $getItemUuid();
    
    $auditVersionUuid = null;
    if ($hasAuditVersion) {
        $auditVersionUuid = $itemUuid;
    } elseif (is_object($item) && method_exists($item, 'auditVersion')) {
        $auditVer = $item->auditVersion;
        $auditVersionUuid = $auditVer ? $auditVer->uuid : null;
    }
@endphp

@if($hasAuditRoutes && !$isAuditItem())
<div class="btn-group ml-1">
    <button type="button"
            class="btn btn-default btn-sm dropdown-toggle shadow-sm"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            title="Menu Audit">
        <i class="fas fa-cog text-purple"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right shadow border-0">
        @if($auditVersionUuid)
            <h6 class="dropdown-header text-purple">
                <i class="fas fa-shield-alt mr-1"></i> Data Audit Tersedia
            </h6>
            @if($canEditAudit() && $hasRoute($editRoute))
                <a class="dropdown-item" href="{{ route($editRoute, [$auditVersionUuid, 'is_audit' => 1]) }}">
                    <i class="fas fa-edit text-purple mr-2"></i> Edit Data Audit
                </a>
            @endif
        @else
            @if($canEditAudit() || $canCopyAudit())
                <a class="dropdown-item" href="{{ route($copyRoute, $itemUuid) }}">
                    <i class="fas fa-edit text-purple mr-2"></i> Edit Data Audit
                </a>
            @else
                <span class="dropdown-item text-muted disabled">
                    <i class="fas fa-lock mr-2"></i> Belum ada data audit
                </span>
            @endif
        @endif
    </div>
</div>
@endif
```

### B. Reusable Action Buttons (`resources/views/components/action-buttons.blade.php`)

Dipanggil di tabel dengan parameter `:is-audit-page="$isAuditView ?? false"`:

```blade
<x-action-buttons 
    :item="$item" 
    route-prefix="seasoning" 
    permission-prefix="seasoning" 
    :is-audit-page="$isAuditView ?? false" 
/>
```

Ketika berada di halaman audit (`$isAuditView = true`), tombol **Edit** secara otomatis mengarahkan ke `copy-to-audit` (auto-copy & edit), tombol **Hapus** disembunyikan/dibatasi, dan badge status waktu edit ditampilkan dengan rapi tanpa badge yang tidak perlu.

### C. Standardized Index Audit Layout (`index_audit.blade.php`)

Halaman `index_audit.blade.php` memiliki layout seragam berstandar **Chillroom**: Dropdown "Tampilkan" di sebelah kiri dan Input Pencarian dengan tombol "Cari" & "Clear" di sebelah kanan.

```blade
@extends('layouts.app')

@section('title', 'Data Audit - Seasoning')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 text-gray-800 font-weight-bold">
                <i class="fas fa-user-shield text-purple mr-2"></i>Data Audit Seasoning
            </h1>
            <p class="text-muted small mb-0">Daftar gabungan salinan data audit dan data operasional yang belum di-copy.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('seasoning.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Data Operasional
            </a>
        </div>
    </div>

    <!-- Standar Layout Search & Pagination Controls -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form id="auditSearchForm" method="GET" action="{{ route('seasoning.audit') }}">
                <div class="row align-items-center">
                    <!-- Per Page Dropdown di Kiri -->
                    <div class="col-md-3 d-flex align-items-center mb-2 mb-md-0">
                        <label class="mr-2 mb-0 small text-muted">Tampilkan:</label>
                        <select name="per_page" id="auditPerPageSelect" class="form-control form-control-sm w-auto" onchange="this.form.submit()">
                            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                        <span class="ml-2 small text-muted">data</span>
                    </div>

                    <!-- Search Input & Buttons di Kanan -->
                    <div class="col-md-9">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" id="auditSearchInput" class="form-control" 
                                   placeholder="Cari kata kunci..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search mr-1"></i> Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('seasoning.audit') }}" class="btn btn-secondary">
                                        <i class="fas fa-times mr-1"></i> Clear
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Container -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0" id="auditTableContainer">
            @include('qc-sistem.seasoning._table', ['records' => $records, 'isAuditView' => true])
        </div>
    </div>
</div>
@endsection
```
---
*Dokumen ini diperbarui secara berkala dan menjadi pedoman utama pengimplementasian fitur audit QC System.*
