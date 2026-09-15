<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TraceabilityService
{
    /**
     * Trace batch code across all configured modules
     *
     * @param string $batchCode
     * @return Collection
     */
    public function traceByBatch(string $batchCode): Collection
    {
        $user = Auth::user();
        $plantUuid = $user->getEffectivePlantId();
        $isSuperAdmin = $user->role && strtolower($user->role->role) === 'superadmin';

        return collect(config('traceability.modules'))
            ->map(function (array $cfg, string $key) use ($batchCode, $plantUuid, $isSuperAdmin) {
                $model = $cfg['model'];
                $routeKey = $cfg['route_key'] ?? 'uuid';

                // Build query based on column type (direct or JSON or multiple columns)
                $query = $model::query();

                // Support multiple search columns (for tables with both VARCHAR and array columns)
                $searchColumns = $cfg['search_columns'] ?? [$cfg['column']];
                
                $query->where(function($q) use ($searchColumns, $batchCode, $cfg, $model) {
                    foreach ($searchColumns as $column) {
                        $columnCfg = is_array($column) ? $column : ['name' => $column, 'is_json' => $cfg['is_json'] ?? false];
                        
                        $q->orWhere(function($subQ) use ($columnCfg, $batchCode, $cfg) {
                            $colName = $columnCfg['name'];
                            $isJson = $columnCfg['is_json'] ?? false;
                            
                            if ($isJson) {
                                // For JSON columns, use JSON search
                                $jsonIsArray = $columnCfg['json_is_array'] ?? ($cfg['json_is_array'] ?? false);
                                
                                if ($jsonIsArray) {
                                    // Search in JSON array (e.g., kode_produksi_array)
                                    $subQ->whereRaw("JSON_SEARCH({$colName}, 'one', ?) IS NOT NULL", ["%{$batchCode}%"]);
                                } else {
                                    // Search in nested JSON structure (e.g., produk_data[*].kode_produksi)
                                    $jsonPath = $columnCfg['json_path'] ?? ($cfg['json_path'] ?? 'kode_produksi');
                                    $subQ->whereRaw("JSON_SEARCH({$colName}, 'one', ?, NULL, '$[*].{$jsonPath}') IS NOT NULL", ["%{$batchCode}%"]);
                                }
                            } else {
                                // Direct column search
                                $subQ->where($colName, 'like', "%{$batchCode}%");
                            }
                        });
                    }
                    
                    // FEATURE: Search by nama produk via relation
                    if ($cfg['enable_nama_search'] ?? false) {
                        $namaConfig = $cfg['nama_search_config'] ?? [];
                        $relation = $namaConfig['relation'] ?? null;
                        $nameField = $namaConfig['name_field'] ?? 'nama_produk';
                        $idFieldArray = $namaConfig['id_field_array'] ?? null;
                        $jsonField = $namaConfig['json_field'] ?? null;
                        $jsonIdKey = $namaConfig['json_id_key'] ?? null;
                        
                        if ($relation) {
                            // Get matching IDs from related table
                            $relatedModel = $model::query()->first()->{$relation}();
                            $relatedClass = get_class($relatedModel->getRelated());
                            
                            $matchingIds = $relatedClass::where($nameField, 'like', "%{$batchCode}%")
                                ->pluck('id')
                                ->toArray();
                            
                            if (!empty($matchingIds)) {
                                // Search by single ID field or JSON array field
                                if ($idFieldArray) {
                                    // Search in JSON array (e.g., id_bahan_array, id_produk_array)
                                    $q->orWhere(function($arrQ) use ($idFieldArray, $matchingIds) {
                                        foreach ($matchingIds as $matchId) {
                                            $arrQ->orWhereRaw("JSON_SEARCH({$idFieldArray}, 'one', ?) IS NOT NULL", [(string)$matchId]);
                                        }
                                    });
                                }
                                
                                // Also search single ID field if exists
                                if ($namaConfig['id_field_single'] ?? null) {
                                    $q->orWhereIn($namaConfig['id_field_single'], $matchingIds);
                                }
                            }
                        } elseif ($idFieldArray && !$jsonField) {
                            // For cases where we don't have direct relation but need to search by nama
                            // Example: Finish Good with id_produk_array
                            $relatedClass = null;
                            
                            // Determine related class based on field name
                            if (str_contains($idFieldArray, 'id_produk')) {
                                $relatedClass = \App\Models\Produk::class;
                            } elseif (str_contains($idFieldArray, 'id_bahan')) {
                                $relatedClass = \App\Models\Bahan::class;
                            }
                            
                            if ($relatedClass) {
                                $matchingIds = $relatedClass::where($nameField, 'like', "%{$batchCode}%")
                                    ->pluck('id')
                                    ->toArray();
                                
                                if (!empty($matchingIds)) {
                                    $q->orWhere(function($arrQ) use ($idFieldArray, $matchingIds) {
                                        foreach ($matchingIds as $matchId) {
                                            $arrQ->orWhereRaw("JSON_SEARCH({$idFieldArray}, 'one', ?) IS NOT NULL", [(string)$matchId]);
                                        }
                                    });
                                }
                            }
                        } elseif ($jsonField && $jsonIdKey) {
                            // For JSON fields like detail_chemicals with id_chemical
                            // We need to get related model class differently
                            $modelClass = $cfg['model'];
                            
                            // Determine related model based on json_id_key name
                            if ($jsonIdKey === 'id_chemical') {
                                $relatedClass = \App\Models\Chemical::class;
                            } elseif ($jsonIdKey === 'id_produk') {
                                $relatedClass = \App\Models\Produk::class;
                            } else {
                                $relatedClass = null;
                            }
                            
                            if ($relatedClass) {
                                $matchingIds = $relatedClass::where($nameField, 'like', "%{$batchCode}%")
                                    ->pluck('id')
                                    ->toArray();
                                
                                if (!empty($matchingIds)) {
                                    // Search in JSON structure using JSON_SEARCH for id_chemical
                                    $q->orWhere(function($jsonQ) use ($jsonField, $jsonIdKey, $matchingIds) {
                                        foreach ($matchingIds as $matchId) {
                                            $jsonQ->orWhereRaw(
                                                "JSON_SEARCH({$jsonField}, 'one', ?, NULL, '$[*].{$jsonIdKey}') IS NOT NULL",
                                                [(string)$matchId]
                                            );
                                        }
                                    });
                                }
                            }
                        }
                    }
                });

                // Apply plant filter if not superadmin
                if (!$isSuperAdmin && $cfg['has_plant_filter'] ?? true) {
                    $query->whereHas('user', function ($q) use ($plantUuid) {
                        $q->where('id_plant', $plantUuid);
                    });
                }

                // Get matching records with relations
                $with = $cfg['with'] ?? [];
                if (!empty($with)) {
                    $query->with($with);
                }

                $records = $query->orderByDesc($cfg['date_column'] ?? 'tanggal')
                    ->get();

                if ($records->isEmpty()) {
                    return null;
                }

                // Map records to result format
                $forms = $records->map(function ($record) use ($cfg, $batchCode, $routeKey) {
                    $dateColumn = $cfg['date_column'] ?? 'tanggal';
                    
                    // Extract fields based on configuration
                    $fields = [];
                    if (is_callable($cfg['display_fields'])) {
                        $fields = $cfg['display_fields']($record, $batchCode);
                    }

                    return [
                        'record_key' => $record->{$routeKey},
                        'date' => $record->{$dateColumn},
                        'shift' => $record->shift ? $record->shift->shift : null,
                        'pdf_url' => route($cfg['pdf_route'], $record->{$routeKey}),
                        'fields' => $fields,
                    ];
                });

                return [
                    'module' => $key,
                    'label' => $cfg['label'],
                    'forms' => $forms
                ];
            })
            ->filter()
            ->values();
    }

    /**
     * Helper to extract matching items from JSON array
     *
     * @param array|null $jsonArray
     * @param string $batchCode
     * @param string $key
     * @return Collection
     */
    public function extractMatchingFromJsonArray(?array $jsonArray, string $batchCode, string $key = 'kode_produksi'): Collection
    {
        if (!$jsonArray) {
            return collect();
        }

        return collect($jsonArray)
            ->filter(function ($item) use ($batchCode, $key) {
                $value = is_array($item) ? ($item[$key] ?? null) : $item;
                return $value && str_contains(strtolower($value), strtolower($batchCode));
            });
    }

    /**
     * Helper to check if array contains batch code
     *
     * @param array|null $array
     * @param string $batchCode
     * @return bool
     */
    public function arrayContainsBatchCode(?array $array, string $batchCode): bool
    {
        if (!$array) {
            return false;
        }

        foreach ($array as $value) {
            if (str_contains(strtolower($value ?? ''), strtolower($batchCode))) {
                return true;
            }
        }

        return false;
    }
}
