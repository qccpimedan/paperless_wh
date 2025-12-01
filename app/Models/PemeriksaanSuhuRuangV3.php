<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanSuhuRuangV3 extends Model
{
    use HasFactory;
    protected $table = 'pemeriksaan_suhu_ruang_v3s';
    protected $fillable = [
        'uuid',
        'id_user',
        'id_shift',
        'id_area',
        'tanggal',
        'pukul',
        'suhu_premix',
        'suhu_seasoning',
        'suhu_dry',
        'suhu_cassing',
        'suhu_beef',
        'suhu_packaging',
        'suhu_ruang_chemical',
        'suhu_ruang_seasoning',
        'keterangan',
        'tindakan_koreksi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'suhu_premix' => 'json',
        'suhu_seasoning' => 'json',
        'suhu_dry' => 'json',
        'suhu_cassing' => 'json',
        'suhu_beef' => 'json',
        'suhu_packaging' => 'json',
        'suhu_ruang_chemical' => 'json',
        'suhu_ruang_seasoning' => 'json',
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

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }

    public function area()
    {
        return $this->belongsTo(InputArea::class, 'id_area');
    }

    public function histories()
    {
        return $this->hasMany(PemeriksaanSuhuRuangV3History::class, 'id_pemeriksaan_suhu_ruang_v3');
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