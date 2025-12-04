<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanBarangMudahPecah extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_user',
        'id_shift',
        'tanggal',
        'id_area',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'verification_notes',
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

    // Relationships
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

    public function details()
    {
        return $this->hasMany(PemeriksaanBarangMudahPecahDetail::class, 'id_pemeriksaan');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}