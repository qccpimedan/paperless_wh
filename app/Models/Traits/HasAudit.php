<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use App\Models\MasterStandarAudit;

trait HasAudit
{
    /**
     * Override di model jika ingin approval tetap terbawa saat copy to audit
     * Default: approval fields akan direset
     */
    protected function keepApprovalsOnAudit(): bool
    {
        return false;
    }

    /**
     * Relasi ke versi audit dari record operasional ini
     * Digunakan untuk cek apakah data sudah pernah di-copy ke audit
     */
    public function auditVersion()
    {
        return $this->hasOne(static::class, 'source_uuid', 'uuid')
            ->where('is_audit', true);
    }

    /**
     * Relasi ke versi operasional asli dari record audit ini
     */
    public function originalVersion()
    {
        return $this->belongsTo(static::class, 'source_uuid', 'uuid')
            ->where('is_audit', false);
    }

    /**
     * Menyalin data operasional ke versi audit + menerapkan Auto-Correction
     * 
     * @return self Objek salinan audit yang baru dibuat
     */
    public function copyToAudit(): self
    {
        // Replicate record (duplikasi tanpa save)
        $clone = $this->replicate();
        
        // Generate UUID baru untuk salinan audit
        $clone->uuid = (string) Str::uuid();
        $clone->is_audit = true;
        $clone->source_uuid = $this->uuid;

        // Reset approval fields jika keepApprovalsOnAudit = false
        if (!$this->keepApprovalsOnAudit()) {
            $this->resetApprovalFields($clone);
        }

        // Terapkan Auto-Correction berdasarkan Master Standar Audit
        $this->applyAuditAutoCorrections($clone);

        // Save salinan audit ke database
        $clone->save();

        return $clone;
    }

    /**
     * Reset semua approval fields ke nilai default
     */
    protected function resetApprovalFields($clone): void
    {
        // Boolean approval fields → reset ke false
        $booleanFields = [
            'approved_by_qc',
            'approved_by_produksi',
            'approved_by_spv',
            'qc_approved',
            'produksi_approved',
            'spv_approved',
        ];

        // Nullable approval/verification fields → reset ke null
        $nullableFields = [
            'qc_approved_by', 'qc_approved_at',
            'produksi_approved_by', 'produksi_approved_at',
            'spv_approved_by', 'spv_approved_at',
            'verified_by_qc', 'verified_by_produksi', 'verified_by_spv',
            'verified_by', 'verified_at',
            'verification_notes',
        ];

        // Fields yang perlu di-reset ke nilai default string (bukan null)
        $defaultStringFields = [
            'status_verifikasi' => 'pending',
        ];

        foreach ($booleanFields as $field) {
            if (array_key_exists($field, $clone->getAttributes())) {
                $clone->{$field} = false;
            }
        }

        foreach ($nullableFields as $field) {
            if (array_key_exists($field, $clone->getAttributes())) {
                $clone->{$field} = null;
            }
        }

        foreach ($defaultStringFields as $field => $defaultValue) {
            if (array_key_exists($field, $clone->getAttributes())) {
                $clone->{$field} = $defaultValue;
            }
        }
    }

    /**
     * Terapkan penyesuaian nilai otomatis pada objek salinan audit
     * Berdasarkan Master Standar Audit & Aturan Umum QC
     */
    protected function applyAuditAutoCorrections($clone): void
    {
        $tableName = $clone->getTable();

        // Deteksi area_id dari record jika ada
        $recordAreaId = $this->detectAreaId($clone);

        // Ambil aturan terkait dari Master Standar Audit
        $allRules = MasterStandarAudit::all()->filter(function ($rule) use ($tableName) {
            // Match berdasarkan table_name atau module_name
            if ($rule->table_name === $tableName || $rule->module_name === $tableName) {
                return true;
            }
            
            // Partial match untuk fleksibilitas
            if (!empty($rule->module_name) && str_contains($tableName, $rule->module_name)) {
                return true;
            }
            
            if (!empty($rule->table_name) && str_contains($tableName, $rule->table_name)) {
                return true;
            }
            
            return false;
        });

        // Susun aturan efektif dengan prioritas: Area-Spesifik > Global
        $effectiveRules = $this->buildEffectiveRules($allRules, $recordAreaId);

        // Apply correction untuk setiap field yang terdaftar
        foreach ($effectiveRules as $columnName => $rule) {
            $this->applyRuleToField($clone, $columnName, $rule);
        }

        // Apply JSON field corrections (untuk field yang ada di dalam JSON)
        $this->applyJsonFieldCorrections($clone, $effectiveRules);

        // Fallback: konversi nilai NOT OK pada kolom yang TIDAK terdaftar
        $this->applyFallbackCorrections($clone, $effectiveRules->keys()->toArray());
    }

    /**
     * Deteksi area_id dari berbagai variasi nama kolom
     */
    protected function detectAreaId($clone): ?int
    {
        $areaColumns = ['area_id', 'id_area', 'id_input_area', 'input_area_id'];
        
        foreach ($areaColumns as $col) {
            if (array_key_exists($col, $clone->getAttributes()) && !empty($clone->{$col})) {
                return (int) $clone->{$col};
            }
        }
        
        return null;
    }

    /**
     * Build aturan efektif dengan prioritas Area-Spesifik menimpa Global
     */
    protected function buildEffectiveRules($allRules, ?int $recordAreaId)
    {
        $effectiveRules = collect();

        foreach ($allRules as $rule) {
            $colName = $rule->column_name;

            // Priority 1: Area-Spesifik (jika area_id match)
            if ($recordAreaId && $rule->area_id && (int)$rule->area_id === $recordAreaId) {
                $effectiveRules->put($colName, $rule);
            } 
            // Priority 2: Global (jika belum ada aturan untuk kolom ini)
            elseif (!$rule->area_id && !$effectiveRules->has($colName)) {
                $effectiveRules->put($colName, $rule);
            }
        }

        return $effectiveRules;
    }

    /**
     * Apply aturan ke field tertentu
     */
    protected function applyRuleToField($clone, string $columnName, MasterStandarAudit $rule): void
    {
        // Skip jika kolom tidak ada di model
        if (!array_key_exists($columnName, $clone->getAttributes())) {
            return;
        }

        $currentValue = $clone->getRawOriginal($columnName) ?? ($clone->getAttributes()[$columnName] ?? null);

        // Parameter Jenis Suhu (Numerik)
        if (in_array($rule->parameter_type, ['suhu', 'suhu_ruang', 'suhu_kondisi', 'suhu_pusat', 'suhu_pemasakan'])) {
            if (is_numeric($currentValue)) {
                $numericValue = (float) $currentValue;
                $isOutOfRange = !$rule->isInRange($numericValue);

                if ($isOutOfRange) {
                    $correctedValue = $rule->getCorrectedValue($currentValue);
                    $clone->setAttribute($columnName, $correctedValue);
                }
            }
        }
        // Parameter Jenis Status/Text
        elseif ($rule->parameter_type === 'status_checklist' && $rule->auto_fix_status) {
            $correctedValue = $rule->getCorrectedValue($currentValue);
            
            if ($correctedValue !== $currentValue) {
                $clone->setAttribute($columnName, $correctedValue);
            }
        }
    }

    /**
     * Apply corrections untuk field yang ada di dalam JSON columns
     * Mendukung nested structure seperti:
     * - cold_storage[0].actual → cold_storage_1_actual
     * - anteroom_loading[0].display → anteroom_loading_1_display
     */
    protected function applyJsonFieldCorrections($clone, $effectiveRules): void
    {
        $tableName = $clone->getTable();
        
        // Mapping JSON field patterns per modul
        $jsonFieldMappings = $this->getJsonFieldMappings($tableName);
        
        if (empty($jsonFieldMappings)) {
            return; // Tidak ada JSON mapping untuk modul ini
        }

        foreach ($jsonFieldMappings as $jsonColumn => $mapping) {
            // Skip jika JSON column tidak ada di model
            if (!array_key_exists($jsonColumn, $clone->getAttributes())) {
                continue;
            }

            $jsonData = $clone->{$jsonColumn};
            
            // Skip jika data JSON kosong/null
            if (empty($jsonData) || !is_array($jsonData)) {
                continue;
            }

            $modified = false;

            // Parse dan koreksi berdasarkan mapping pattern
            foreach ($mapping['patterns'] as $pattern) {
                $result = $this->applyJsonPattern($jsonData, $pattern, $effectiveRules, $modified);
                if ($result !== null) {
                    $jsonData = $result;
                }
            }

            // Save kembali jika ada perubahan
            if ($modified) {
                \Log::info("HasAudit: JSON field '{$jsonColumn}' modified, saving changes");
                $clone->{$jsonColumn} = $jsonData;
            }
        }
    }

    /**
     * Apply correction pattern ke JSON data
     */
    protected function applyJsonPattern(array &$jsonData, array $pattern, $effectiveRules, &$modified): ?array
    {
        $arrayKey = $pattern['array_key'] ?? null;
        $objectKey = $pattern['object_key'] ?? null;
        $fieldPattern = $pattern['field_pattern'];

        // Handle array pattern: cold_storage[1-4]
        if ($arrayKey && isset($jsonData[$arrayKey]) && is_array($jsonData[$arrayKey])) {
            foreach ($jsonData[$arrayKey] as $index => &$item) {
                if (!is_array($item)) {
                    continue;
                }

                $unitNumber = $item['unit'] ?? ($index + 1);
                
                // Iterate fields dalam item (setting, display, actual)
                foreach ($item as $fieldKey => &$fieldValue) {
                    if ($fieldKey === 'unit') {
                        continue;
                    }

                    // Build virtual column name: cold_storage_1_actual
                    $virtualColumnName = str_replace(
                        ['{unit}', '{field}'],
                        [$unitNumber, $fieldKey],
                        $fieldPattern
                    );

                    // Check apakah ada rule untuk virtual column ini
                    if ($effectiveRules->has($virtualColumnName)) {
                        $rule = $effectiveRules->get($virtualColumnName);
                        $corrected = $this->correctJsonValue($fieldValue, $rule, $virtualColumnName);
                        
                        if ($corrected !== $fieldValue) {
                            $fieldValue = $corrected;
                            $modified = true;
                        }
                    }
                }
            }
            
            return $jsonData;
        }

        // Handle object pattern: pre_loading.actual
        if ($objectKey && isset($jsonData[$objectKey]) && is_array($jsonData[$objectKey])) {
            $obj = &$jsonData[$objectKey];
            
            foreach ($obj as $fieldKey => &$fieldValue) {
                // Build virtual column name: pre_loading_actual
                $virtualColumnName = str_replace(
                    ['{key}', '{field}'],
                    [$objectKey, $fieldKey],
                    $fieldPattern
                );

                if ($effectiveRules->has($virtualColumnName)) {
                    $rule = $effectiveRules->get($virtualColumnName);
                    $corrected = $this->correctJsonValue($fieldValue, $rule, $virtualColumnName);
                    
                    if ($corrected !== $fieldValue) {
                        $fieldValue = $corrected;
                        $modified = true;
                    }
                }
            }
            
            return $jsonData;
        }

        return null;
    }

    /**
     * Correct single JSON value berdasarkan rule
     */
    protected function correctJsonValue($currentValue, MasterStandarAudit $rule, string $virtualColumnName)
    {
        \Log::debug("HasAudit: Checking JSON field '{$virtualColumnName}'", [
            'current_value' => $currentValue,
            'parameter_type' => $rule->parameter_type,
            'min_value' => $rule->min_value,
            'max_value' => $rule->max_value,
        ]);

        // Parameter Jenis Suhu (Numerik)
        if (in_array($rule->parameter_type, ['suhu', 'suhu_ruang', 'suhu_kondisi', 'suhu_pusat', 'suhu_pemasakan', 'suhu_produk'])) {
            if (is_numeric($currentValue)) {
                $numericValue = (float) $currentValue;
                $isOutOfRange = !$rule->isInRange($numericValue);

                if ($isOutOfRange) {
                    $correctedValue = $rule->getCorrectedValue($currentValue);
                    \Log::info("HasAudit: CORRECTING JSON field '{$virtualColumnName}' from {$currentValue} to {$correctedValue}");
                    return $correctedValue;
                }
            }
        }
        // Parameter Jenis Status/Text
        elseif ($rule->parameter_type === 'status_checklist' && $rule->auto_fix_status) {
            $correctedValue = $rule->getCorrectedValue($currentValue);
            
            if ($correctedValue !== $currentValue) {
                \Log::info("HasAudit: CORRECTING JSON field '{$virtualColumnName}' from {$currentValue} to {$correctedValue}");
                return $correctedValue;
            }
        }

        return $currentValue;
    }

    /**
     * Get JSON field mapping configuration per modul
     */
    protected function getJsonFieldMappings(string $tableName): array
    {
        $mappings = [
            // Pemeriksaan Suhu Ruang V1
            'pemeriksaan_suhu_ruangs' => [
                'suhu_data' => [
                    'patterns' => [
                        // cold_storage array: CS 1-4
                        [
                            'array_key' => 'cold_storage',
                            'field_pattern' => 'cold_storage_{unit}_{field}', // cold_storage_1_actual
                        ],
                        // anteroom_loading array: Anteroom Loading 1-4
                        [
                            'array_key' => 'anteroom_loading',
                            'field_pattern' => 'anteroom_loading_{unit}_{field}', // anteroom_loading_1_actual
                        ],
                        // anteroom_ekspansi_further array
                        [
                            'array_key' => 'anteroom_ekspansi_further',
                            'field_pattern' => 'anteroom_ekspansi_further_{unit}_{field}',
                        ],
                        // anteroom_ekspansi_sausage array
                        [
                            'array_key' => 'anteroom_ekspansi_sausage',
                            'field_pattern' => 'anteroom_ekspansi_sausage_{unit}_{field}',
                        ],
                        // pre_loading object
                        [
                            'object_key' => 'pre_loading',
                            'field_pattern' => 'pre_loading_{field}', // pre_loading_actual
                        ],
                        // prestaging object
                        [
                            'object_key' => 'prestaging',
                            'field_pattern' => 'prestaging_{field}',
                        ],
                    ],
                ],
            ],

            // Pemeriksaan Suhu Ruang V2 (CS Meat)
            'pemeriksaan_suhu_ruang_v2s' => [
                'suhu_data' => [
                    'patterns' => [
                        // cold_storage array
                        [
                            'array_key' => 'cold_storage',
                            'field_pattern' => 'cold_storage_{unit}_{field}',
                        ],
                        // anteroom_loading array
                        [
                            'array_key' => 'anteroom_loading',
                            'field_pattern' => 'anteroom_loading_{unit}_{field}',
                        ],
                        // anteroom_ekspansi_abf array
                        [
                            'array_key' => 'anteroom_ekspansi_abf',
                            'field_pattern' => 'anteroom_ekspansi_abf_{unit}_{field}',
                        ],
                        // pre_loading object
                        [
                            'object_key' => 'pre_loading',
                            'field_pattern' => 'pre_loading_{field}',
                        ],
                        // prestaging object
                        [
                            'object_key' => 'prestaging',
                            'field_pattern' => 'prestaging_{field}',
                        ],
                    ],
                ],
            ],

            // Pemeriksaan Suhu Ruang V3 (Multi Unit)
            'pemeriksaan_suhu_ruang_v3s' => [
                'suhu_data' => [
                    'patterns' => [
                        // premix array
                        [
                            'array_key' => 'premix',
                            'field_pattern' => 'suhu_premix_{unit}_{field}',
                        ],
                        // seasoning array
                        [
                            'array_key' => 'seasoning',
                            'field_pattern' => 'suhu_seasoning_{unit}_{field}',
                        ],
                        // dry array
                        [
                            'array_key' => 'dry',
                            'field_pattern' => 'suhu_dry_{unit}_{field}',
                        ],
                        // cassing array
                        [
                            'array_key' => 'cassing',
                            'field_pattern' => 'suhu_cassing_{unit}_{field}',
                        ],
                        // beef array
                        [
                            'array_key' => 'beef',
                            'field_pattern' => 'suhu_beef_{unit}_{field}',
                        ],
                        // packaging array
                        [
                            'array_key' => 'packaging',
                            'field_pattern' => 'suhu_packaging_{unit}_{field}',
                        ],
                        // ruang_chemical array
                        [
                            'array_key' => 'ruang_chemical',
                            'field_pattern' => 'suhu_ruang_chemical_{unit}_{field}',
                        ],
                        // ruang_seasoning array
                        [
                            'array_key' => 'ruang_seasoning',
                            'field_pattern' => 'suhu_ruang_seasoning_{unit}_{field}',
                        ],
                    ],
                ],
            ],
        ];

        return $mappings[$tableName] ?? [];
    }

    /**
     * Fallback correction untuk kolom yang tidak terdaftar di Master Standar Audit
     * Konversi nilai NOT OK/NG menjadi OK
     */
    protected function applyFallbackCorrections($clone, array $coveredColumns): void
    {
        $attributes = $clone->getAttributes();

        foreach ($attributes as $key => $value) {
            // Skip kolom yang sudah tercakup di Master Standar
            if (in_array($key, $coveredColumns)) {
                continue;
            }

            // Skip non-string values
            if (!is_string($value)) {
                continue;
            }

            $lowerValue = strtolower(trim($value));

            // List nilai yang dianggap NOT OK
            $notOkValues = [
                'not ok', 'ng', 'reject', 'kotor', 'tidak ok', 
                'tidak_sesuai', 'tidak sesuai', 'x', '✗', '✘', 
                'tidak baik', 'rusak', 'pecah', 'tidak lengkap'
            ];

            if (in_array($lowerValue, $notOkValues)) {
                $clone->{$key} = 'OK';
            }
        }
    }

    /**
     * Scope untuk data operasional saja
     */
    public function scopeOperasional($query)
    {
        return $query->where('is_audit', false);
    }

    /**
     * Scope untuk data audit saja
     */
    public function scopeAudit($query)
    {
        return $query->where('is_audit', true);
    }

    /**
     * Scope Audit-First Smart Filter
     * Menampilkan:
     * - Semua data audit (is_audit = true)
     * - Data operasional yang belum pernah di-copy ke audit
     * 
     * @param bool $isAudit True untuk halaman audit, false untuk halaman operasional
     */
    public function scopeAuditFirstSmartFilter($query, bool $isAudit = true)
    {
        if (!$isAudit) {
            return $query->where('is_audit', false);
        }

        // Ambil daftar source_uuid yang sudah di-copy ke audit
        // Menggunakan pluck() untuk menghindari error collation MySQL
        $auditedSourceUuids = static::where('is_audit', true)
            ->whereNotNull('source_uuid')
            ->pluck('source_uuid')
            ->toArray();

        return $query->where(function ($q) use ($auditedSourceUuids) {
            // Tampilkan semua data audit
            $q->where('is_audit', true);

            // ATAU data operasional yang belum di-copy
            $q->orWhere(function ($subQ) use ($auditedSourceUuids) {
                $subQ->where('is_audit', false);
                
                if (!empty($auditedSourceUuids)) {
                    $subQ->whereNotIn('uuid', $auditedSourceUuids);
                }
            });
        });
    }
}
