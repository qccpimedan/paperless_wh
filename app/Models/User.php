<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'username',
        'email_verified_at',
        'email',
        'password',
        'id_role',
        'id_plant',
        'active_plant_id',
        'force_logout_after',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'force_logout_after' => 'datetime',
        ];
    }

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
     * Relationship to Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    /**
     * Relationship to Plant (assigned plant / plant asal)
     */
    public function plant()
    {
        return $this->belongsTo(Plant::class, 'id_plant');
    }

    /**
     * Relationship to Active Plant (used by Manager for switch plant)
     */
    public function activePlant()
    {
        return $this->belongsTo(Plant::class, 'active_plant_id');
    }

    /**
     * Relationship: Plants yang diizinkan diakses oleh Manager ini.
     * Di-assign oleh Superadmin melalui halaman kelola user.
     * Hanya berlaku jika user memiliki role Manager.
     */
    public function allowedPlants()
    {
        return $this->belongsToMany(Plant::class, 'manager_plants', 'user_id', 'plant_id')
                    ->withTimestamps();
    }

    /**
     * Check if user has manager role
     */
    public function isManager(): bool
    {
        $roleSlug = $this->role ? strtolower($this->role->role) : null;
        return $roleSlug === 'manager';
    }

    /**
     * Cek apakah Manager diizinkan mengakses plant tertentu.
     * - Jika plant ada di allowedPlants → boleh
     * - Jika belum ada yang di-assign → tidak boleh switch (keamanan default)
     */
    public function canAccessPlant(int $plantId): bool
    {
        if (!$this->isManager()) return false;

        return $this->allowedPlants()->where('plants.id', $plantId)->exists();
    }

    /**
     * Get the effective plant:
     * - For Manager: returns active_plant (switched plant) if set, else falls back to assigned plant
     * - For other roles: returns the assigned plant
     */
    public function getEffectivePlant(): ?Plant
    {
        if ($this->isManager() && $this->active_plant_id) {
            return $this->activePlant;
        }
        return $this->plant;
    }

    /**
     * Get the effective plant ID (numeric)
     */
    public function getEffectivePlantId(): ?int
    {
        if ($this->isManager() && $this->active_plant_id) {
            return (int) $this->active_plant_id;
        }
        return (int) $this->id_plant;
    }

    /**
     * Get timezone from effective plant
     */
    public function getTimezoneAttribute(): string
    {
        $plant = $this->getEffectivePlant();
        if ($plant) {
            return (string) ($plant->timezone ?? 'Asia/Jakarta');
        }

        return 'Asia/Jakarta';
    }
}
