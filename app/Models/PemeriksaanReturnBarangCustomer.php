<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanReturnBarangCustomer extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_return_barang_customers';

    protected $fillable = [
        'uuid',
        'id_user',
        'id_shift',
        'tanggal',
        'id_ekspedisi',
        'no_polisi',
        'nama_supir',
        'waktu_kedatangan',
        'suhu_mobil',
        'id_customer',
        'alasan_return',
        'produk_data',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'produk_data' => 'array',
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

    public function ekspedisi()
    {
        return $this->belongsTo(Ekspedisi::class, 'id_ekspedisi');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getWaktuKedatanganDisplayAttribute()
    {
        if (!$this->waktu_kedatangan) {
            return '-';
        }
        // Handle both time format (H:i) and datetime format
        if (strlen($this->waktu_kedatangan) > 5) {
            return date('H:i', strtotime($this->waktu_kedatangan));
        }
        return $this->waktu_kedatangan;
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