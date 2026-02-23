<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bahan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'id_user',
        'nama_bahan',
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

    public function produsens()
    {
        return $this->belongsToMany(Produsen::class, 'bahan_produsen', 'id_bahan', 'id_produsen')
            ->withPivot('id_plant')
            ->withTimestamps();
    }

    public function distributors()
    {
        return $this->belongsToMany(Distributor::class, 'bahan_distributor', 'id_bahan', 'id_distributor')
            ->withPivot('id_plant')
            ->withTimestamps();
    }
}
