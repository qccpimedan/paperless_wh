<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DetailKomplain extends Model
{
    protected $table = 'detail_komplains';

    protected $fillable = [
        'uuid',
        'nama_supplier',
        'tanggal_kedatangan',
        'no_po',
        'nama_produk',
        'kode_produksi',
        'expired_date',
        'jumlah_datang',
        'jumlah_di_tolak',
        'dokumentasi',
        'upload_suplier',
        'keterangan',
        'di_buat_oleh',
        'setujui_oleh',
        'id_user',
    ];

    protected $casts = [
        'tanggal_kedatangan' => 'date',
        'expired_date' => 'date',
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
}