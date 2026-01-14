<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BahanKemasan extends Model
{
    use HasFactory;

    protected $table = 'bahan_kemasans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'id_user',
        'id_distributor',
        'id_produsen',
        'nama_kemasan',
        'kategori_code',
    ];

    /**
     * Boot the model and auto-generate UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     */
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

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'id_distributor');
    }

    public function produsen()
    {
        return $this->belongsTo(Produsen::class, 'id_produsen');
    }

    public function produsens()
    {
        return $this->belongsToMany(Produsen::class, 'bahan_kemasan_produsen', 'id_bahan_kemasan', 'id_produsen')
            ->withPivot('id_plant')
            ->withTimestamps();
    }

    public function distributors()
    {
        return $this->belongsToMany(Distributor::class, 'bahan_kemasan_distributor', 'id_bahan_kemasan', 'id_distributor')
            ->withPivot('id_plant')
            ->withTimestamps();
    }

    /**
     * Serialize timestamps to Indonesia timezone (Asia/Jakarta)
     */
    protected function serializeDate($date)
    {
        return $date->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    /**
     * Get created_at in Indonesia timezone
     */
    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')
                ->setTimezone('Asia/Jakarta');
        }
        return $value;
    }

    /**
     * Get updated_at in Indonesia timezone
     */
    public function getUpdatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')
                ->setTimezone('Asia/Jakarta');
        }
        return $value;
    }
}
