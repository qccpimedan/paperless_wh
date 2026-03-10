<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPlantTimezoneTimestamps;

class PemeriksaanProdukFinishGood extends Model
{
    use HasFactory, HasPlantTimezoneTimestamps;

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
        'image_finish_good_array',
        'upload_coa_array',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'verification_notes',
        'verified_by_qc',
        'verified_by_produksi',
        'verified_by_spv',
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
        'image_finish_good_array' => 'array',
        'upload_coa_array' => 'array',
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
