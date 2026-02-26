<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPlantTimezoneTimestamps;

class PemeriksaanLoadingProduk extends Model
{
    use HasFactory, HasPlantTimezoneTimestamps;

    protected $fillable = [
        'uuid',
        'id_user',
        'id_shift',
        'tanggal',
        'id_tujuan_pengiriman',
        'id_kendaraan',
        'id_supir',
        'star_loading',
        'selesai_loading',
        'temperature_mobil',
        'temperature_produk',
        'kondisi_produk',
        'segel_gembok',
        'no_segel',
        'produk_data',
        'keterangan',
        'status_verifikasi',
        'verified_by',
        'verified_by_qc',
        'verified_by_produksi',
        'verified_by_spv',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'temperature_produk' => 'array',
        'produk_data' => 'array',
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

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }

    public function tujuanPengiriman()
    {
        return $this->belongsTo(TujuanPengiriman::class, 'id_tujuan_pengiriman');
    }

    public function kendaraan()
    {
        return $this->belongsTo(JenisKendaraan::class, 'id_kendaraan');
    }

    public function supir()
    {
        return $this->belongsTo(Supir::class, 'id_supir');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
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