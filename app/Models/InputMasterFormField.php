<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InputMasterFormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_master_form',
        'field_name',
        'field_order',
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

    public function masterForm()
    {
        return $this->belongsTo(InputMasterForm::class, 'id_master_form');
    }

    public function details()
    {
        return $this->hasMany(PemeriksaanKebersianAreaDetail::class, 'id_master_form_field');
    }
}