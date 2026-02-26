<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPlantTimezoneTimestamps;

class PemeriksaanSuhuRuang extends Model
{
    use HasFactory, HasPlantTimezoneTimestamps;

    protected $fillable = [
        'uuid',
        'id_user',
        'id_shift',
        'id_produk',
        'tanggal',
        'suhu_produk',
        'pukul',
        'suhu_data',
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
        'suhu_data' => 'json',
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
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function histories()
    {
        return $this->hasMany(PemeriksaanSuhuRuangHistory::class, 'id_pemeriksaan_suhu_ruang');
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
