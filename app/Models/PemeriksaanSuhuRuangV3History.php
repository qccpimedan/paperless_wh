<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanSuhuRuangV3History extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_pemeriksaan_suhu_ruang_v3',
        'id_user',
        'suhu_premix_lama',
        'suhu_premix_baru',
        'suhu_seasoning_lama',
        'suhu_seasoning_baru',
        'suhu_dry_lama',
        'suhu_dry_baru',
        'suhu_cassing_lama',
        'suhu_cassing_baru',
        'suhu_beef_lama',
        'suhu_beef_baru',
        'suhu_packaging_lama',
        'suhu_packaging_baru',
        'suhu_ruang_chemical_lama',
        'suhu_ruang_chemical_baru',
        'suhu_ruang_seasoning_lama',
        'suhu_ruang_seasoning_baru',
        'keterangan_lama',
        'keterangan_baru',
        'tindakan_koreksi_lama',
        'tindakan_koreksi_baru',
    ];

    protected $casts = [
        'suhu_premix_lama' => 'array',
        'suhu_premix_baru' => 'array',
        'suhu_seasoning_lama' => 'array',
        'suhu_seasoning_baru' => 'array',
        'suhu_dry_lama' => 'array',
        'suhu_dry_baru' => 'array',
        'suhu_cassing_lama' => 'array',
        'suhu_cassing_baru' => 'array',
        'suhu_beef_lama' => 'array',
        'suhu_beef_baru' => 'array',
        'suhu_packaging_lama' => 'array',
        'suhu_packaging_baru' => 'array',
        'suhu_ruang_chemical_lama' => 'array',
        'suhu_ruang_chemical_baru' => 'array',
        'suhu_ruang_seasoning_lama' => 'array',
        'suhu_ruang_seasoning_baru' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function pemeriksaanSuhuRuangV3()
    {
        return $this->belongsTo(PemeriksaanSuhuRuangV3::class, 'id_pemeriksaan_suhu_ruang_v3');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Serialize timestamps to Indonesia timezone (Asia/Jakarta)
     */
    protected function serializeDate($date)
    {
        return $date->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    /**
     * Get created_at in Indonesia timezone
     */
    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')
                ->setTimezone('Asia/Jakarta');
        }
        return $value;
    }

    /**
     * Get updated_at in Indonesia timezone
     */
    public function getUpdatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')
                ->setTimezone('Asia/Jakarta');
        }
        return $value;
    }
}
