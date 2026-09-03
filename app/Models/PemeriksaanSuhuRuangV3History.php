<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPlantTimezoneTimestamps;

class PemeriksaanSuhuRuangV3History extends Model
{
    use HasFactory, HasPlantTimezoneTimestamps;

    protected $fillable = [
        'uuid',
        'id_pemeriksaan_suhu_ruang_v3',
        'id_user',
        'pukul_lama',
        'pukul_baru',
        'suhu_produk_lama',
        'suhu_produk_baru',
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
        return $this->belongsTo(User::class, 'edited_by');
    }
}
