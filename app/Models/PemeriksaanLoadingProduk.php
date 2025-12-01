<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanLoadingProduk extends Model
{
    use HasFactory;

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
        'no_po',
        'id_produk',
        'kode_produksi',
        'best_before',
        'jumlah_kemasan',
        'jumlah_sampling',
        'kondisi_kemasan',
        'keterangan',
    ];

    protected $casts = [
        'temperature_produk' => 'array',
        'segel_gembok' => 'boolean',
        'kondisi_kemasan' => 'boolean',
        'tanggal' => 'date',
        'best_before' => 'date',
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