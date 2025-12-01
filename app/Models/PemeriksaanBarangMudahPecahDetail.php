<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanBarangMudahPecahDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_pemeriksaan',
        'id_barang',
        'nama_barang_manual',
        'id_input_area_locations',
        'jumlah_barang',
        'awal',
        'akhir',
        'temuan_ketidaksesuaian',
        'tindakan_koreksi',
        'nama_karyawan',
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
    public function pemeriksaan()
    {
        return $this->belongsTo(PemeriksaanBarangMudahPecah::class, 'id_pemeriksaan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function areaLocation()
    {
        return $this->belongsTo(InputAreaLocation::class, 'id_input_area_locations');
    }
}