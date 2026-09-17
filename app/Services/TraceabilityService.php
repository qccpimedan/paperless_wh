<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TraceabilityService
{
    /**
     * Trace batch code across all configured modules
     * Support multi-keyword search: "fiesta 2026" will search both nama produk and kode produksi
     *
     * @param string $batchCode
     * @return Collection
     */
    public function traceByBatch(string $batchCode): Collection
    {
        $user = Auth::user();
        $plantUuid = $user->getEffectivePlantId();
        $isSuperAdmin = $user->role && strtolower($user->role->role) === 'superadmin';

        // Split search keywords by space or comma
        $keywords = $this->parseSearchKeywords($batchCode);

        return collect(config('traceability.modules'))
            ->map(function (array $cfg, string $key) use ($batchCode, $keywords, $plantUuid, $isSuperAdmin) {
                $model = $cfg['model'];
                $routeKey = $cfg['route_key'] ?? 'uuid';

                // Build query based on column type (direct or JSON or multiple columns)
                $query = $model::query();

                // Support multiple search columns (for tables with both VARCHAR and array columns)
                $searchColumns = $cfg['search_columns'] ?? [$cfg['column']];
                
                // Use keywords for main query if multi-keyword, otherwise use original batchCode
                $searchTerms = count($keywords) > 1 ? $keywords : [$batchCode];
                
                $query->where(function($q) use ($searchColumns, $batchCode, $searchTerms, $cfg, $model) {
                    // Search across all search terms with OR logic
                    foreach ($searchTerms as $searchTerm) {
                        foreach ($searchColumns as $column) {
                            $columnCfg = is_array($column) ? $column : ['name' => $column, 'is_json' => $cfg['is_json'] ?? false];
                            
                            $q->orWhere(function($subQ) use ($columnCfg, $searchTerm, $cfg) {
                                $colName = $columnCfg['name'];
                                $isJson = $columnCfg['is_json'] ?? false;
                                
                                if ($isJson) {
                                    // For JSON columns, use JSON search
                                    $jsonIsArray = $columnCfg['json_is_array'] ?? ($cfg['json_is_array'] ?? false);
                                    
                                    if ($jsonIsArray) {
                                        // Search in JSON array (e.g., kode_produksi_array)
                                        $subQ->whereRaw("JSON_SEARCH({$colName}, 'one', ?) IS NOT NULL", ["%{$searchTerm}%"]);
                                    } else {
                                        // Search in nested JSON structure (e.g., produk_data[*].kode_produksi)
                                        $jsonPath = $columnCfg['json_path'] ?? ($cfg['json_path'] ?? 'kode_produksi');
                                        $subQ->whereRaw("JSON_SEARCH({$colName}, 'one', ?, NULL, '$[*].{$jsonPath}') IS NOT NULL", ["%{$searchTerm}%"]);
                                    }
                                } else {
                                    // Direct column search
                                    $subQ->where($colName, 'like', "%{$searchTerm}%");
                                }
                            });
                        }
                    }
                    
                    // FEATURE: Search by nama produk via relation (using all search terms)
                    if ($cfg['enable_nama_search'] ?? false) {
                        $namaConfig = $cfg['nama_search_config'] ?? [];
                        $relation = $namaConfig['relation'] ?? null;
                        $nameField = $namaConfig['name_field'] ?? 'nama_produk';
                        $idFieldArray = $namaConfig['id_field_array'] ?? null;
                        $jsonField = $namaConfig['json_field'] ?? null;
                        $jsonIdKey = $namaConfig['json_id_key'] ?? null;
                        
                        // Search by nama using all search terms
                        foreach ($searchTerms as $searchTerm) {
                            if ($relation) {
                                // Get matching IDs from related table
                                $relatedModel = $model::query()->first()->{$relation}();
                                $relatedClass = get_class($relatedModel->getRelated());
                                
                                $matchingIds = $relatedClass::where($nameField, 'like', "%{$searchTerm}%")
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
                                    $matchingIds = $relatedClass::where($nameField, 'like', "%{$searchTerm}%")
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
                                    $matchingIds = $relatedClass::where($nameField, 'like', "%{$searchTerm}%")
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

                // KEYWORD FILTERING
                // Filter records to ensure they actually contain the searched keywords
                // This is necessary because main query uses broad OR logic
                $records = $records->filter(function($record) use ($keywords, $cfg) {
                    return $this->recordMatchesAllKeywords($record, $keywords, $cfg);
                });

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

                    // Generate PDF export URL (for exporting single record PDF)
                    $pdfExportUrl = null;
                    if (isset($cfg['pdf_export_route'])) {
                        // Modules that accept UUID parameter in route
                        $uuidBasedExport = ['detail-komplain.export-pdf', 'pemeriksaan-kebersihan-area.export-pdf'];
                        
                        if (in_array($cfg['pdf_export_route'], $uuidBasedExport)) {
                            // Pass UUID as route parameter
                            $pdfExportUrl = route($cfg['pdf_export_route'], $record->{$routeKey});
                        } else {
                            // For filter-based exports, pass UUID + date and shift as query params
                            $dateColumn = $cfg['date_column'] ?? 'tanggal';
                            $shiftId = $record->id_shift ?? null;
                            $recordDate = $record->{$dateColumn};
                            
                            // Always include UUID for single record export
                            $params = ['uuid' => $record->{$routeKey}];
                            
                            // Add shift and date params
                            if ($shiftId) {
                                $params['id_shift'] = $shiftId;
                                $shift = \App\Models\Shift::find($shiftId);
                                if ($shift && $shift->is_date_range) {
                                    // Shift 1: use date range (same date for both)
                                    $params['tanggal_dari'] = $recordDate instanceof \Carbon\Carbon ? $recordDate->format('Y-m-d') : $recordDate;
                                    $params['tanggal_sampai'] = $recordDate instanceof \Carbon\Carbon ? $recordDate->format('Y-m-d') : $recordDate;
                                } else {
                                    // Shift 2/3: use single date
                                    $params['tanggal'] = $recordDate instanceof \Carbon\Carbon ? $recordDate->format('Y-m-d') : $recordDate;
                                }
                            } else {
                                // No shift info, use single date
                                $params['tanggal'] = $recordDate instanceof \Carbon\Carbon ? $recordDate->format('Y-m-d') : $recordDate;
                            }
                            
                            $pdfExportUrl = route($cfg['pdf_export_route'], $params);
                        }
                    }
                    
                    return [
                        'record_key' => $record->{$routeKey},
                        'date' => $record->{$dateColumn},
                        'shift' => $record->shift ? $record->shift->shift : null,
                        'pdf_url' => route($cfg['pdf_route'], $record->{$routeKey}),
                        'pdf_export_url' => $pdfExportUrl,
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

    /**
     * Parse search keywords from input string
     * Examples:
     *  - "fiesta 2026" => ['fiesta', '2026'] (2 keywords, AND logic)
     *  - "Fiesta Tepung Bumbu Bakwan Renceng" => ['Fiesta Tepung Bumbu Bakwan Renceng'] (1 phrase)
     *  - '"Fiesta Tepung" 2026' => ['Fiesta Tepung', '2026'] (phrase + keyword)
     *  - "ADA lombok" => ['ADA', 'lombok']
     *
     * SMART PARSING RULES:
     * - If input has 4+ words WITHOUT comma/special chars → treat as SINGLE phrase (product name)
     * - If input has comma or explicit quotes → split properly
     * - If input has 1-3 words → split for multi-keyword search
     *
     * @param string $searchString
     * @return array
     */
    protected function parseSearchKeywords(string $searchString): array
    {
        $searchString = trim($searchString);
        
        // Rule 1: Check for quotes (explicit phrases)
        if (preg_match_all('/"([^"]+)"/', $searchString, $matches)) {
            // Has quoted phrases, extract them
            $phrases = $matches[1];
            
            // Remove quoted parts from string
            $remaining = preg_replace('/"[^"]+"/', '', $searchString);
            
            // Get remaining keywords
            $remainingKeywords = array_values(array_filter(array_map('trim', preg_split('/[\s,]+/', $remaining))));
            
            // Merge phrases and keywords
            return array_merge($phrases, $remainingKeywords);
        }
        
        // Rule 2: Check for comma (explicit multi-keyword)
        if (str_contains($searchString, ',')) {
            $keywords = preg_split('/[\s,]+/', $searchString);
            return array_values(array_filter(array_map('trim', $keywords)));
        }
        
        // Rule 3: Smart detection - check if last word is likely a production code
        $words = preg_split('/\s+/', $searchString);
        $wordCount = count($words);
        
        if ($wordCount >= 3) {
            $lastWord = end($words);
            
            // Check if last word looks like production code (contains number or is SHORT alphanumeric <=4 chars)
            // Reduced from 6 to 4 to avoid matching words like "Nugget" (6 chars)
            $lastWordIsCode = preg_match('/\d/', $lastWord) || (strlen($lastWord) <= 4 && ctype_alnum($lastWord));
            
            if ($lastWordIsCode) {
                // Likely: "Product Name" + "Code"
                // Split into name (all words except last) + code (last word)
                $productName = implode(' ', array_slice($words, 0, -1));
                $productionCode = $lastWord;
                return [$productName, $productionCode];
            }
            
            // No code-like ending, treat as single phrase (nama produk partial/lengkap)
            return [$searchString];
        }
        
        // If 1-2 words → split for multi-keyword search (nama pendek + kode)
        return array_values(array_filter($words));
    }

    /**
     * Check if record matches ALL keywords (AND logic)
     * Used for multi-keyword filtering
     * 
     * IMPORTANT: For multi-keyword search (nama + kode), we want:
     * - Keyword 1 to match in nama_produk field
     * - Keyword 2 to match in kode_produksi field
     * - Both in SAME record (not requiring both in same field)
     *
     * @param mixed $record
     * @param array $keywords
     * @param array $cfg Module config
     * @return bool
     */
    protected function recordMatchesAllKeywords($record, array $keywords, array $cfg): bool
    {
        // Check if ALL keywords exist somewhere in this record's searchable fields
        // Each keyword can match in different fields (nama OR kode)
        foreach ($keywords as $keyword) {
            $matched = $this->recordMatchesKeyword($record, $keyword, $cfg);
            
            // DEBUG: Log if not matched
            if (!$matched) {
                \Log::debug('Record does not match keyword', [
                    'keyword' => $keyword,
                    'record_id' => $record->uuid ?? $record->id ?? 'unknown',
                    'module' => $cfg['label'] ?? 'unknown',
                ]);
            }
            
            if (!$matched) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if record matches a single keyword
     * For long keywords (product names), use partial word matching
     *
     * @param mixed $record
     * @param string $keyword
     * @param array $cfg
     * @return bool
     */
    protected function recordMatchesKeyword($record, string $keyword, array $cfg): bool
    {
        $keywordLower = strtolower($keyword);
        
        // For long keywords (likely product names), split into words and check if most words match
        $keywordWords = preg_split('/\s+/', $keyword);
        $isLongKeyword = count($keywordWords) >= 3;
        
        // Check in search columns (kode_produksi, etc)
        $searchColumns = $cfg['search_columns'] ?? [$cfg['column']];
        
        foreach ($searchColumns as $column) {
            $columnCfg = is_array($column) ? $column : ['name' => $column, 'is_json' => $cfg['is_json'] ?? false];
            $colName = $columnCfg['name'];
            $isJson = $columnCfg['is_json'] ?? false;
            
            if ($isJson) {
                // Check in JSON field
                $jsonData = is_array($record->{$colName}) ? $record->{$colName} : json_decode($record->{$colName} ?? '[]', true);
                
                if ($this->jsonContainsKeyword($jsonData, $keyword, $columnCfg, $cfg)) {
                    return true;
                }
            } else {
                // Check in direct column
                $value = $record->{$colName} ?? '';
                $valueLower = strtolower($value);
                
                // Simple match for short keywords or exact contains
                if (str_contains($valueLower, $keywordLower)) {
                    return true;
                }
            }
        }
        
        // Check in nama produk/bahan/chemical (if enabled)
        if ($cfg['enable_nama_search'] ?? false) {
            $namaConfig = $cfg['nama_search_config'] ?? [];
            $relation = $namaConfig['relation'] ?? null;
            $nameField = $namaConfig['name_field'] ?? 'nama_produk';
            $jsonField = $namaConfig['json_field'] ?? null;
            $jsonIdKey = $namaConfig['json_id_key'] ?? null;
            $idFieldArray = $namaConfig['id_field_array'] ?? null;
            $idFieldSingle = $namaConfig['id_field_single'] ?? null;
            
            // Strategy 1: Check loaded relation (for single relation records)
            if ($relation && isset($record->{$relation})) {
                // Single relation
                if (is_object($record->{$relation})) {
                    $nama = $record->{$relation}->{$nameField} ?? '';
                    
                    if ($this->nameMatchesKeyword($nama, $keyword, $isLongKeyword, $keywordWords)) {
                        return true;
                    }
                }
                // Collection relation
                elseif ($record->{$relation} instanceof \Illuminate\Support\Collection) {
                    foreach ($record->{$relation} as $relatedItem) {
                        $nama = $relatedItem->{$nameField} ?? '';
                        
                        if ($this->nameMatchesKeyword($nama, $keyword, $isLongKeyword, $keywordWords)) {
                            return true;
                        }
                    }
                }
            }
            
            // Strategy 2: Check JSON field with product/chemical IDs (for JSON structure records)
            if ($jsonField && $jsonIdKey && isset($record->{$jsonField})) {
                $jsonData = is_array($record->{$jsonField}) ? $record->{$jsonField} : json_decode($record->{$jsonField} ?? '[]', true);
                
                if (!empty($jsonData)) {
                    // Determine model class
                    $modelClass = null;
                    $nameField = 'nama_produk';
                    if ($jsonIdKey === 'id_chemical') {
                        $modelClass = \App\Models\Chemical::class;
                        $nameField = 'nama_chemical';
                    } elseif ($jsonIdKey === 'id_produk') {
                        $modelClass = \App\Models\Produk::class;
                        $nameField = 'nama_produk';
                    }
                    
                    if ($modelClass) {
                        // Extract IDs from JSON
                        $ids = collect($jsonData)->pluck($jsonIdKey)->filter()->unique()->toArray();
                        
                        if (!empty($ids)) {
                            // Load names and check
                            $names = $modelClass::whereIn('id', $ids)->pluck($nameField, 'id')->toArray();
                            
                            foreach ($names as $nama) {
                                if ($this->nameMatchesKeyword($nama, $keyword, $isLongKeyword, $keywordWords)) {
                                    return true;
                                }
                            }
                        }
                        
                        // ALSO check nama_produk in JSON directly (for modules that store it)
                        foreach ($jsonData as $item) {
                            if (is_array($item) && isset($item[$nameField])) {
                                $namaInJson = $item[$nameField];
                                if ($this->nameMatchesKeyword($namaInJson, $keyword, $isLongKeyword, $keywordWords)) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
            
            // Strategy 3: Check ID arrays (for array-based records)
            if ($idFieldArray && isset($record->{$idFieldArray})) {
                $idArray = is_array($record->{$idFieldArray}) ? $record->{$idFieldArray} : json_decode($record->{$idFieldArray} ?? '[]', true);
                
                if (!empty($idArray)) {
                    // Determine model class
                    $modelClass = null;
                    $nameField = 'nama_produk';
                    if (str_contains($idFieldArray, 'id_produk')) {
                        $modelClass = \App\Models\Produk::class;
                        $nameField = 'nama_produk';
                    } elseif (str_contains($idFieldArray, 'id_bahan')) {
                        $modelClass = \App\Models\Bahan::class;
                        $nameField = 'nama_bahan';
                    }
                    
                    if ($modelClass) {
                        $names = $modelClass::whereIn('id', array_filter($idArray))->pluck($nameField)->toArray();
                        
                        foreach ($names as $nama) {
                            if ($this->nameMatchesKeyword($nama, $keyword, $isLongKeyword, $keywordWords)) {
                                return true;
                            }
                        }
                    }
                }
            }
            
            // Strategy 4: Check single ID field (for single record with ID)
            if ($idFieldSingle && isset($record->{$idFieldSingle})) {
                $id = $record->{$idFieldSingle};
                
                if ($id) {
                    // Determine model class
                    $modelClass = null;
                    $nameField = 'nama_produk';
                    if (str_contains($idFieldSingle, 'id_produk')) {
                        $modelClass = \App\Models\Produk::class;
                        $nameField = 'nama_produk';
                    } elseif (str_contains($idFieldSingle, 'id_bahan')) {
                        $modelClass = \App\Models\Bahan::class;
                        $nameField = 'nama_bahan';
                    }
                    
                    if ($modelClass) {
                        $model = $modelClass::find($id);
                        if ($model) {
                            $nama = $model->{$nameField} ?? '';
                            if ($this->nameMatchesKeyword($nama, $keyword, $isLongKeyword, $keywordWords)) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Check if name matches keyword (with fuzzy matching for long names)
     *
     * @param string $nama
     * @param string $keyword
     * @param bool $isLongKeyword
     * @param array $keywordWords
     * @return bool
     */
    protected function nameMatchesKeyword(string $nama, string $keyword, bool $isLongKeyword, array $keywordWords): bool
    {
        $namaLower = strtolower($nama);
        $keywordLower = strtolower($keyword);
        
        // ONLY exact contains check - NO fuzzy matching
        // This prevents false positives like "Fiesta Chicken Nugget" matching "Fiesta French F"
        return str_contains($namaLower, $keywordLower);
    }

    /**
     * Check if JSON data contains keyword
     *
     * @param array|null $jsonData
     * @param string $keyword
     * @param array $columnCfg
     * @param array $cfg
     * @return bool
     */
    protected function jsonContainsKeyword(?array $jsonData, string $keyword, array $columnCfg, array $cfg): bool
    {
        if (!$jsonData) {
            return false;
        }

        $keywordLower = strtolower($keyword);
        $jsonIsArray = $columnCfg['json_is_array'] ?? ($cfg['json_is_array'] ?? false);
        
        if ($jsonIsArray) {
            // Simple array of values
            foreach ($jsonData as $value) {
                if (str_contains(strtolower($value ?? ''), $keywordLower)) {
                    return true;
                }
            }
        } else {
            // Array of objects with nested keys
            $jsonPath = $columnCfg['json_path'] ?? ($cfg['json_path'] ?? 'kode_produksi');
            
            foreach ($jsonData as $item) {
                if (is_array($item) && isset($item[$jsonPath])) {
                    if (str_contains(strtolower($item[$jsonPath] ?? ''), $keywordLower)) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}