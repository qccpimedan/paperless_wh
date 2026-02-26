<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPlantTimezoneTimestamps;

class PemeriksaanSuhuRuangV3 extends Model
{
    use HasFactory, HasPlantTimezoneTimestamps;
    protected $table = 'pemeriksaan_suhu_ruang_v3s';
    protected $fillable = [
        'uuid',
        'id_user',
        'id_shift',
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
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'verification_notes',
        'verified_by_qc',
        'verified_by_produksi',
        'verified_by_spv',
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

    public function histories()
    {
        return $this->hasMany(PemeriksaanSuhuRuangV3History::class, 'id_pemeriksaan_suhu_ruang_v3');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function verifiedByQc()
    {
        return $this->belongsTo(User::class, 'verified_by_qc');
    }

    public function verifiedByProduksi()
    {
        return $this->belongsTo(User::class, 'verified_by_produksi');
    }

    public function verifiedBySpv()
    {
        return $this->belongsTo(User::class, 'verified_by_spv');
    }
}