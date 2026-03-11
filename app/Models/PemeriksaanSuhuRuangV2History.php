<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPlantTimezoneTimestamps;

class PemeriksaanSuhuRuangV2History extends Model
{
    use HasFactory, HasPlantTimezoneTimestamps;

    protected $fillable = [
        'uuid',
        'id_pemeriksaan_suhu_ruang_v2',
        'id_user',
        'suhu_produk_lama',
        'suhu_produk_baru',
        'pukul_lama',
        'pukul_baru',
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
}
