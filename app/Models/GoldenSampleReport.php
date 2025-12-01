<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GoldenSampleReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_user',
        'id_plant',
        'plant_manual',
        'sample_type',
        'collection_date_from',
        'collection_date_to',
        'tanggal',
        'sample_storage',
        'samples',
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

    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone('Asia/Jakarta');
        }
        return $value;
    }

    public function getUpdatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone('Asia/Jakarta');
        }
        return $value;
    }
}