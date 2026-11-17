# Implementasi Array Field dalam Audit System

## Use Case: temperature_produk[] di PemeriksaanLoadingProduk

Field `temperature_produk` adalah **JSON array** yang bisa berisi 1 atau lebih nilai suhu produk.

Contoh data:
```json
{
  "temperature_mobil": "-18",
  "temperature_produk": ["-18", "-17", "-19"]
}
```

---

## 1. DETEKSI & PARSE ARRAY (Di Controller showAuditForm)

```php
// MasterStandarAuditController.php - method showAuditForm()

$originalData = $originalRecord->toArray();

// Special handling untuk array fields
if (isset($originalData['temperature_produk']) && is_array($originalData['temperature_produk'])) {
    $temperatureProdukArray = $originalData['temperature_produk'];
} else {
    $temperatureProdukArray = [];
}
```

---

## 2. RENDER FORM (Di Blade audit form)

```blade
{{-- resources/views/master-audit/partials/form-fields.blade.php --}}

@if($module === 'pemeriksaan_loading_produk')
    {{-- Temperature Mobil (single field) --}}
    <div class="form-group">
        <label>Temperature Mobil (°C)</label>
        <input type="text" 
               name="temperature_mobil" 
               value="{{ old('temperature_mobil', $originalData['temperature_mobil'] ?? '') }}"
               class="form-control">
    </div>

    {{-- Temperature Produk (array/multiple) --}}
    <div class="form-group">
        <label>Temperature Produk (Multiple) (°C)</label>
        
        @php
            $tempProduk = $originalData['temperature_produk'] ?? [];
            if (!is_array($tempProduk)) {
                $tempProduk = json_decode($tempProduk, true) ?: [];
            }
        @endphp
        
        @foreach($tempProduk as $index => $nilai)
            <div class="input-group mb-2">
                <span class="input-group-text">Produk #{{ $index + 1 }}</span>
                <input type="text" 
                       name="temperature_produk[{{ $index }}]" 
                       value="{{ old('temperature_produk.'.$index, $nilai) }}"
                       class="form-control"
                       placeholder="Contoh: -18">
            </div>
        @endforeach
    </div>
@endif
```

---

## 3. AUTO-CORRECTION LOGIC (Di Trait HasAuditLogic)

```php
// app/Traits/HasAuditLogic.php

protected function generateAutoCorrection($originalData, $fields, $module)
{
    $corrections = [];
    
    // Handle regular fields
    foreach ($fields as $fieldName => $config) {
        if ($config['type'] === 'suhu') {
            $value = $originalData[$fieldName] ?? null;
            $corrected = $this->applySuhuCorrection($value, $config);
            
            if ($value !== $corrected) {
                $corrections[$fieldName] = [
                    'original' => $value,
                    'corrected' => $corrected,
                    'reason' => 'Auto-koreksi suhu NOT OK → OK'
                ];
            }
        }
    }
    
    // SPECIAL CASE: Array fields (temperature_produk)
    if ($module === 'pemeriksaan_loading_produk') {
        if (isset($originalData['temperature_produk']) && is_array($originalData['temperature_produk'])) {
            $tempProdukArray = $originalData['temperature_produk'];
            $correctedArray = [];
            $hasChanges = false;
            
            foreach ($tempProdukArray as $index => $value) {
                $corrected = $this->applySuhuCorrection($value, [
                    'type' => 'suhu',
                    'label' => "Temperature Produk #{$index}"
                ]);
                
                $correctedArray[$index] = $corrected;
                
                if ($value !== $corrected) {
                    $hasChanges = true;
                }
            }
            
            if ($hasChanges) {
                $corrections['temperature_produk'] = [
                    'original' => $tempProdukArray,
                    'corrected' => $correctedArray,
                    'reason' => 'Auto-koreksi array suhu produk NOT OK → OK',
                    'is_array' => true // Flag khusus untuk array
                ];
            }
        }
    }
    
    return $corrections;
}

protected function applySuhuCorrection($value, $config)
{
    // Parse range dari config (misal: min=-25, max=-15)
    $min = $config['min'] ?? -25;
    $max = $config['max'] ?? -15;
    $target = $config['target'] ?? -18;
    
    $numValue = floatval($value);
    
    // Jika NOT OK (di luar range), koreksi ke target
    if ($numValue < $min || $numValue > $max) {
        return (string) $target;
    }
    
    return $value; // Sudah OK, tidak perlu koreksi
}
```

---

## 4. APPLY CORRECTION (Di Controller)

```php
// MasterStandarAuditController.php - method applyAutoCorrection()

public function applyAutoCorrection(Request $request, $module, $uuid)
{
    // ... validation ...
    
    $corrections = $request->input('corrections', []);
    $correctedData = $originalData;
    
    foreach ($corrections as $fieldName => $correction) {
        // Check if this is array field
        if (isset($correction['is_array']) && $correction['is_array'] === true) {
            // Handle array correction
            $correctedData[$fieldName] = $correction['corrected'];
        } else {
            // Handle single value correction
            $correctedData[$fieldName] = $correction['corrected'];
        }
    }
    
    // Create audit record
    $auditRecord = $originalRecord->replicate();
    $auditRecord->fill($correctedData);
    $auditRecord->is_audit = true;
    $auditRecord->source_uuid = $originalRecord->uuid;
    $auditRecord->uuid = Str::uuid();
    $auditRecord->save();
    
    return redirect()->back()->with('success', 'Auto-correction applied');
}
```

---

## 5. DISPLAY COMPARISON (Di Blade show audit)

```blade
{{-- Show comparison untuk array field --}}

@if(isset($auditData['temperature_produk']) && is_array($auditData['temperature_produk']))
    <h6>Temperature Produk (Multiple):</h6>
    
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="45%">Original</th>
                <th width="45%">Audit (Corrected)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $originalTemp = $originalData['temperature_produk'] ?? [];
                $auditTemp = $auditData['temperature_produk'] ?? [];
                $maxCount = max(count($originalTemp), count($auditTemp));
            @endphp
            
            @for($i = 0; $i < $maxCount; $i++)
                @php
                    $orig = $originalTemp[$i] ?? '-';
                    $audit = $auditTemp[$i] ?? '-';
                    $isDifferent = $orig !== $audit;
                @endphp
                <tr class="{{ $isDifferent ? 'table-warning' : '' }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $orig }}°C</td>
                    <td>
                        {{ $audit }}°C
                        @if($isDifferent)
                            <span class="badge bg-warning text-dark ms-2">CORRECTED</span>
                        @endif
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
@endif
```

---

## Summary

**3 Langkah Utama:**

1. **Config** - Tandai dengan comment bahwa field adalah array (sudah done)
2. **Controller Logic** - Deteksi array, loop & check setiap item, generate corrections
3. **Blade View** - Render multiple inputs, display comparison per-index

**Key Points:**
- Array field TIDAK masuk `fields` config (handled secara custom)
- Perlu flag `'is_array' => true` dalam corrections data
- Loop rendering di Blade untuk input & comparison
- Save sebagai JSON array (Laravel auto-handle karena cast)

