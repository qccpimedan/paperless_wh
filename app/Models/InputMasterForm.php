<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InputMasterForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_user',
        'nama_form',
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

    public function fields()
    {
        return $this->hasMany(InputMasterFormField::class, 'id_master_form')->orderBy('field_order');
    }

    public function pemeriksaans()
    {
        return $this->hasMany(PemeriksaanKebersihanArea::class, 'id_master_form');
    }
}