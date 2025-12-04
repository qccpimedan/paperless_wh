<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanKebersihanArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_user',
        'id_shift',
        'id_area',
        'id_master_form',
        'tanggal',
        'jam_sebelum_proses',
        'jam_saat_proses',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'tanggal' => 'date',
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

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }

    public function area()
    {
        return $this->belongsTo(InputArea::class, 'id_area');
    }

    public function masterForm()
    {
        return $this->belongsTo(InputMasterForm::class, 'id_master_form');
    }

    public function details()
    {
        return $this->hasMany(PemeriksaanKebersihanAreaDetail::class, 'id_pemeriksaan');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
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
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, )
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
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, )
                ->setTimezone('Asia/Jakarta');
        }
        return $value;
    }
}