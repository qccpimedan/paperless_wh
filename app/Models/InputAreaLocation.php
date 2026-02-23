<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InputAreaLocation extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'id_input_area', 'lokasi_area'];

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

    public function inputArea()
    {
        return $this->belongsTo(InputArea::class, 'id_input_area');
    }
}