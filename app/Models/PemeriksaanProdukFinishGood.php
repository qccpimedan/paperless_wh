<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanProdukFinishGood extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_user',
        'id_shift',
        'tanggal',
        'jenis_mobil',
        'no_mobil',
        'nama_supir',
        'segel_gembok',
        'no_segel',
        'kondisi_mobil',
        'suhu_mobil',
        'suhu_mobil_value',
        'suhu_produk',
        'suhu_produk_value',
        'kondisi_produk',
        'suhu_mobil_type_array',
        'suhu_mobil_value_array',
        'suhu_produk_type_array',
        'suhu_produk_value_array',
        'kondisi_produk_array',
        'kondisi_produk_suhu_value_array',
        'kategori_code_array',
        'id_produk_array',
        'produsen_array',
        'negara_produsen_array',
        'distributor_array',
        'kode_produksi_array',
        'expire_date_array',
        'jumlah_datang_array',
        'jumlah_sampling_array',
        'kondisi_kemasan_array',
        'kondisi_warna_array',
        'kondisi_aroma_array',
        'logo_halal_array',
        'dokumen_halal_array',
        'coa_array',
        'status_array',
        'keterangan_array',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'kondisi_mobil' => 'array',
        'suhu_mobil_type_array' => 'array',
        'suhu_mobil_value_array' => 'array',
        'suhu_produk_type_array' => 'array',
        'suhu_produk_value_array' => 'array',
        'kondisi_produk_array' => 'array',
        'kondisi_produk_suhu_value_array' => 'array',
        'kategori_code_array' => 'array',
        'id_produk_array' => 'array',
        'produsen_array' => 'array',
        'negara_produsen_array' => 'array',
        'distributor_array' => 'array',
        'kode_produksi_array' => 'array',
        'expire_date_array' => 'array',
        'jumlah_datang_array' => 'array',
        'jumlah_sampling_array' => 'array',
        'kondisi_kemasan_array' => 'array',
        'kondisi_warna_array' => 'array',
        'kondisi_aroma_array' => 'array',
        'logo_halal_array' => 'array',
        'dokumen_halal_array' => 'array',
        'coa_array' => 'array',
        'status_array' => 'array',
        'keterangan_array' => 'array',
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

    protected function serializeDate($date)
    {
        return $date->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')
                ->setTimezone('Asia/Jakarta');
        }
        return $value;
    }

    public function getUpdatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')
                ->setTimezone('Asia/Jakarta');
        }
        return $value;
    }
}
