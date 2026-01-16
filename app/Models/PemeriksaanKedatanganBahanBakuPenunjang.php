<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanKedatanganBahanBakuPenunjang extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_user',
        'id_shift',
        'id_bahan',
        'tanggal',
        'jenis_mobil',
        'no_mobil',
        'nama_supir',
        'segel_gembok',
        'no_segel',
        'jenis_pemeriksaan',
        'kondisi_mobil',
        'suhu_mobil',
        'no_po',
        'kondisi_produk',
        'suhu_produk',
        'spesifikasi',
        'produsen',
        'negara_produsen',
        'distributor',
        'kode_produksi',
        'expire_date',
        'jumlah_datang',
        'jumlah_sampling',
        'kondisi_fisik',
        'logo_halal',
        'hasil_uji_ffa',
        'dokumen_halal',
        'coa',
        'suhu_mobil_type',
        'suhu_produk_type', 
        'kondisi_produk_suhu',
        'status',
        'status_baris_array',
        'keterangan',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'verification_notes',
        'verified_by_qc',
        'verified_by_produksi',
        'verified_by_spv',
        // Array columns untuk dynamic rows
        'id_bahan_array',
        'produsen_array',
        'negara_produsen_array',
        'distributor_array',
        'kode_produksi_array',
        'expire_date_array',
        'jumlah_datang_array',
        'jumlah_sampling_array',
        'spesifikasi_array',
        'suhu_mobil_array',
        'suhu_mobil_type_array',
        'kondisi_fisik_array',
        'logo_halal_array',
        'hasil_uji_ffa_array',
        'dokumen_halal_array',
        'coa_array',
        'file_coa_array',
        'keterangan_array',
    ];

    protected $casts = [
        'kondisi_mobil' => 'array',
        'kondisi_fisik' => 'array',
        'logo_halal' => 'boolean',
        'dokumen_halal' => 'boolean',
        'coa' => 'boolean',
        'file_coa_array' => 'array',
        'tanggal' => 'date',
        'expire_date' => 'date',
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

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'id_bahan');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Relationship to QC verifier
     */
    public function qcVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_qc');
    }

    /**
     * Relationship to Produksi verifier
     */
    public function produksiVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_produksi');
    }

    /**
     * Relationship to SPV verifier
     */
    public function spvVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_spv');
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
            return Carbon::createFromFormat('Y-m-d H:i:s', $value,)
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
            return Carbon::createFromFormat('Y-m-d H:i:s', $value,)
                ->setTimezone('Asia/Jakarta');
        }
        return $value;
    }
}