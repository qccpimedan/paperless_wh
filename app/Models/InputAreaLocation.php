<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected function serializeDate($date)
    {
        return $date->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')->setTimezone('Asia/Jakarta');
        }
        return $value;
    }

    public function getUpdatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')->setTimezone('Asia/Jakarta');
        }
        return $value;
    }
}