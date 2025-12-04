<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanLoadingKendaraan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_loading_kendaraans';

    protected $fillable = [
        'uuid',
        'tanggal',
        'id_ekspedisi',
        'id_kendaraan',
        'id_tujuan_pengiriman',
        'id_std_precooling',
        'id_shift',
        'id_user',
        'kondisi_kebersihan_mobil',
        'kondisi_mobil',
        'jam_mulai',
        'jam_selesai',
        'suhu_precooling',
        'keterangan',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'kondisi_kebersihan_mobil' => 'json',
        'kondisi_mobil' => 'json',
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

    public function ekspedisi()
    {
        return $this->belongsTo(Ekspedisi::class, 'id_ekspedisi');
    }

    public function kendaraan()
    {
        return $this->belongsTo(JenisKendaraan::class, 'id_kendaraan');
    }

    public function tujuanPengiriman()
    {
        return $this->belongsTo(TujuanPengiriman::class, 'id_tujuan_pengiriman');
    }

    public function stdPrecooling()
    {
        return $this->belongsTo(StdPrecooling::class, 'id_std_precooling');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
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