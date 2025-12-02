<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanSuhuRuangV2History extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_pemeriksaan_suhu_ruang_v2',
        'id_user',
        'suhu_cold_storage_lama',
        'suhu_cold_storage_baru',
        'suhu_anteroom_loading_lama',
        'suhu_anteroom_loading_baru',
        'suhu_pre_loading_lama',
        'suhu_pre_loading_baru',
        'suhu_prestaging_lama',
        'suhu_prestaging_baru',
        'suhu_anteroom_ekspansi_abf_lama',
        'suhu_anteroom_ekspansi_abf_baru',
        'suhu_chillroom_rm_lama',
        'suhu_chillroom_rm_baru',
        'suhu_chillroom_domestik_lama',
        'suhu_chillroom_domestik_baru',
        'keterangan_lama',
        'keterangan_baru',
        'tindakan_koreksi_lama',
        'tindakan_koreksi_baru',
    ];

    protected $casts = [
        'suhu_cold_storage_lama' => 'array',
        'suhu_cold_storage_baru' => 'array',
        'suhu_anteroom_loading_lama' => 'array',
        'suhu_anteroom_loading_baru' => 'array',
        'suhu_pre_loading_lama' => 'array',
        'suhu_pre_loading_baru' => 'array',
        'suhu_prestaging_lama' => 'array',
        'suhu_prestaging_baru' => 'array',
        'suhu_anteroom_ekspansi_abf_lama' => 'array',
        'suhu_anteroom_ekspansi_abf_baru' => 'array',
        'suhu_chillroom_rm_lama' => 'array',
        'suhu_chillroom_rm_baru' => 'array',
        'suhu_chillroom_domestik_lama' => 'array',
        'suhu_chillroom_domestik_baru' => 'array',
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

    public function pemeriksaanSuhuRuangV2()
    {
        return $this->belongsTo(PemeriksaanSuhuRuangV2::class, 'id_pemeriksaan_suhu_ruang_v2');
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
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, )
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
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, )
                ->setTimezone('Asia/Jakarta');
        }
        return $value;
    }
}
