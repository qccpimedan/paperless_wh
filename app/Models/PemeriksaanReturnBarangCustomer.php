<?php

namespace App\Models;

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
        'verified_by_qc',
        'verified_by_produksi',
        'verified_by_spv',
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
}