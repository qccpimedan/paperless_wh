<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPlantTimezoneTimestamps;

class DetailKomplain extends Model
{
    use HasPlantTimezoneTimestamps;
    protected $table = 'detail_komplains';

    protected $fillable = [
        'uuid',
        'nama_supplier',
        'tanggal_kedatangan',
        'no_po',
        'id_produk_array',
        'kategori_code_array',
        'nama_produk',
        'nama_produk_array',
        'kode_produksi',
        'kode_produksi_array',
        'expired_date',
        'expired_date_array',
        'jumlah_datang',
        'jumlah_datang_array',
        'jumlah_di_tolak',
        'jumlah_di_tolak_array',
        'dokumentasi',
        'dokumentasi_array',
        'upload_suplier',
        'keterangan',
        'keterangan_array',
        'di_buat_oleh',
        'di_buat_oleh_array',
        'setujui_oleh',
        'setujui_oleh_array',
        'id_user',
        'id_shift',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'tanggal_kedatangan' => 'date',
        'expired_date' => 'date',
        'id_produk_array' => 'array',
        'kategori_code_array' => 'array',
        'nama_produk_array' => 'array',
        'kode_produksi_array' => 'array',
        'expired_date_array' => 'array',
        'jumlah_datang_array' => 'array',
        'jumlah_di_tolak_array' => 'array',
        'dokumentasi_array' => 'array',
        'keterangan_array' => 'array',
        'di_buat_oleh_array' => 'array',
        'setujui_oleh_array' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
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

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}