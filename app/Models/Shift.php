<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'shift',
        'id_user'
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
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relationship to PemeriksaanKedatanganKemasan
     */
    public function pemeriksaanKedatanganKemasans()
    {
        return $this->hasMany(PemeriksaanKedatanganKemasan::class, 'id_shift');
    }
}
