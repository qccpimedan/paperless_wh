<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPlantTimezoneTimestamps;

class GoldenSampleReport extends Model
{
    use HasFactory, HasPlantTimezoneTimestamps;

    protected $fillable = [
        'uuid',
        'id_user',
        'id_plant',
        'id_shift',
        'plant_manual',
        'sample_type',
        'masa_penyimpanan',
        'tanggal',
        'sample_storage',
        'samples',
        'status_verifikasi',
        'verified_by',
        'verified_by_qc',
        'verified_by_produksi',
        'verified_by_spv',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
       
        'sample_storage' => 'array',
        'samples' => 'array',
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

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'id_plant');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }
    
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function qcVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_qc');
    }

    public function produksiVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_produksi');
    }

    public function spvVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_spv');
    }
}