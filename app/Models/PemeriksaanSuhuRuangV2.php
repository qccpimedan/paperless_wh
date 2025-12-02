<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanSuhuRuangV2 extends Model
{
    use HasFactory;
    protected $table = 'pemeriksaan_suhu_ruang_v2s';

    protected $fillable = [
        'uuid',
        'id_user',
        'id_shift',
        'id_produk',
        'id_area',
        'tanggal',
        'suhu_cold_storage',
        'suhu_anteroom_loading',
        'suhu_pre_loading',
        'suhu_prestaging',
        'suhu_anteroom_ekspansi_abf',
        'suhu_chillroom_rm',
        'suhu_chillroom_domestik',
        'keterangan',
        'tindakan_koreksi',
    ];

    protected $casts = [
        'suhu_cold_storage' => 'json',
        'suhu_anteroom_loading' => 'json',
        'suhu_pre_loading' => 'json',
        'suhu_prestaging' => 'json',
        'suhu_anteroom_ekspansi_abf' => 'json',
        'suhu_chillroom_rm' => 'json',
        'suhu_chillroom_domestik' => 'json',
        'tanggal' => 'date',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }

    public function produk()
    {
        return $this->belongsTo(Bahan::class, 'id_produk');
    }

    public function area()
    {
        return $this->belongsTo(InputArea::class, 'id_area');
    }

    public function histories()
    {
        return $this->hasMany(PemeriksaanSuhuRuangV2History::class, 'id_pemeriksaan_suhu_ruang_v2');
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