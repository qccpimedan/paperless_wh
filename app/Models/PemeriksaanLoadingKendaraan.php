<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPlantTimezoneTimestamps;

class PemeriksaanLoadingKendaraan extends Model
{
    use HasFactory, HasPlantTimezoneTimestamps;

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
        'segel_gembok',
        'no_segel',
        'status_verifikasi',
        'verified_by',
        'verified_by_qc',
        'verified_by_produksi',
        'verified_by_spv',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'kondisi_kebersihan_mobil' => 'json',
        'kondisi_mobil' => 'json',
        'segel_gembok' => 'boolean',
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

    public function qcVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_qc');
    }

    public function produksiVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_produksi');
    }

    public function spvVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_spv');
    }
}