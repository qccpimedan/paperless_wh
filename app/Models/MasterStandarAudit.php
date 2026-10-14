<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class MasterStandarAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'module_name',
        'table_name',
        'column_name',
        'area_id',
        'parameter_type',
        'min_value',
        'max_value',
        'standar_value',
        'auto_fix_status',
    ];

    protected $casts = [
        'min_value' => 'decimal:2',
        'max_value' => 'decimal:2',
        'auto_fix_status' => 'boolean',
    ];

    /**
     * Boot method untuk generate UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi ke InputArea (untuk aturan spesifik area)
     */
    public function area()
    {
        return $this->belongsTo(InputArea::class, 'area_id');
    }

    /**
     * Scope untuk filter berdasarkan modul
     */
    public function scopeForModule($query, string $moduleName)
    {
        return $query->where('module_name', $moduleName);
    }

    /**
     * Scope untuk filter berdasarkan area
     */
    public function scopeForArea($query, ?int $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    /**
     * Scope untuk aturan global (tanpa area spesifik)
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('area_id');
    }

    /**
     * Get label untuk parameter type
     */
    public function getParameterTypeLabel(): string
    {
        return match($this->parameter_type) {
            'suhu' => 'Suhu Produk',
            'suhu_ruang' => 'Suhu Ruangan',
            'suhu_kondisi' => 'Suhu Kondisi Khusus',
            'suhu_pusat' => 'Suhu Pusat Produk',
            'suhu_pemasakan' => 'Suhu Pemasakan',
            'status_checklist' => 'Status Checklist (OK/NG)',
            default => ucfirst($this->parameter_type),
        };
    }

    /**
     * Check apakah nilai numerik berada dalam range yang diperbolehkan
     */
    public function isInRange($value): bool
    {
        if (!is_numeric($value)) {
            return true; // Non-numeric values tidak dicek range
        }

        $numericValue = (float) $value;

        if ($this->min_value !== null && $numericValue < (float) $this->min_value) {
            return false;
        }

        if ($this->max_value !== null && $numericValue > (float) $this->max_value) {
            return false;
        }

        return true;
    }

    /**
     * Get corrected value berdasarkan aturan ini
     */
    public function getCorrectedValue($currentValue)
    {
        // Untuk parameter numerik (suhu)
        if (in_array($this->parameter_type, ['suhu', 'suhu_ruang', 'suhu_kondisi', 'suhu_pusat', 'suhu_pemasakan'])) {
            if (is_numeric($currentValue) && !$this->isInRange($currentValue)) {
                // Gunakan standar_value jika ada
                if ($this->standar_value !== null && $this->standar_value !== '') {
                    return is_numeric($this->standar_value) ? (float) $this->standar_value : $this->standar_value;
                }

                // Generate nilai acak dalam range min-max
                $min = $this->min_value ?? 0;
                $max = $this->max_value ?? 30;
                return round($min + (mt_rand() / mt_getrandmax()) * ($max - $min), 2);
            }
        }

        // Untuk status checklist
        if ($this->parameter_type === 'status_checklist') {
            $targetValue = $this->standar_value ?? 'OK';
            
            if (is_string($currentValue)) {
                $lowerVal = strtolower(trim($currentValue));
                
                // Konversi nilai NOT OK menjadi OK
                if (in_array($lowerVal, ['ng', 'not ok', 'tidak ok', 'reject', 'kotor', 'rusak', 'x', '✗', '✘'])) {
                    return $targetValue;
                }
            }
        }

        return $currentValue;
    }
}
