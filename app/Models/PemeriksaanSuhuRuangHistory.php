<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPlantTimezoneTimestamps;

class PemeriksaanSuhuRuangHistory extends Model
{
    use HasFactory, HasPlantTimezoneTimestamps;

    protected $fillable = [
        'uuid',
        'id_pemeriksaan_suhu_ruang',
        'id_user',
        'suhu_data_lama',
        'suhu_data_baru',
        'keterangan_lama',
        'keterangan_baru',
        'tindakan_koreksi_lama',
        'tindakan_koreksi_baru',
    ];

    protected $casts = [
        'suhu_data_lama' => 'json',
        'suhu_data_baru' => 'json',
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

    public function pemeriksaanSuhuRuang()
    {
        return $this->belongsTo(PemeriksaanSuhuRuang::class, 'id_pemeriksaan_suhu_ruang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}