<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPlantTimezoneTimestamps;

class PemeriksaanKedatanganKemasan extends Model
{
    use HasFactory, HasPlantTimezoneTimestamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'tanggal',
        'jenis_mobil',
        'no_mobil',
        'nama_supir',
        'segel_gembok',
        'no_segel',
        'jenis_pemeriksaan',
        'kondisi_mobil',
        'no_po',
        'spesifikasi',
        'produsen',
        'distributor',
        'kode_produksi',
        'jumlah_datang',
        'jumlah_sampling',
        'kondisi_fisik',
        'ketebalan_micron',
        'dimensi',
        'logo_halal',
        'dokumen_halal',
        'coa',
        'status',
        'keterangan',
        'id_user',
        'id_shift',
        'id_bahan',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'verification_notes',
        'verified_by_qc',
        'verified_by_produksi',
        'verified_by_spv',
        'id_bahan_array',
        'produsen_array',
        'distributor_array',
        'kode_produksi_array',
        'jumlah_datang_array',
        'jumlah_sampling_array',
        'spesifikasi_array',
        'penampakan_array',
        'sealing_array',
        'cetakan_array',
        'ketebalan_micron_array',
        'dimensi_array',
        'status_array',
        'logo_halal_array',
        'dokumen_halal_array',
        'coa_array',
        'keterangan_array',
        'image_kemasan_array',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'kondisi_mobil' => 'array',
        'kondisi_fisik' => 'array',
        'ketebalan_micron' => 'decimal:2',
        'logo_halal' => 'boolean',
        'dokumen_halal' => 'boolean',
        'coa' => 'boolean',
        'image_kemasan_array' => 'array',
    ];

    /**
     * Boot the model and auto-generate UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relationship to Shift
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }

    /**
     * Relationship to Bahan
     */
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'id_bahan');
    }

    /**
     * Relationship to verified user
     */
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
     * Default kondisi mobil checklist
     */
    public static function getDefaultKondisiMobil()
    {
        return [
            'bersih' => false,
            'bebas_hama' => false,
            'tidak_kondensasi' => false,
            'bebas_produk_halal' => false,
            'tidak_berbau' => false,
            'tidak_ada_sampah' => false,
            'tidak_ada_mikroba' => false,
            'lampu_cover_utuh' => false,
            'pallet_utuh' => false,
            'tertutup_rapat' => false,
            'bebas_kontaminan' => false,
        ];
    }

    /**
     * Default kondisi fisik checklist
     */
    public static function getDefaultKondisiFisik()
    {
        return [
            'penampakan' => false,
            'sealing' => false,
            'cetakan' => false,
        ];
    }
}
