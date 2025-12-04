<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanKedatanganKemasan extends Model
{
    use HasFactory;

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
        // 'nama_bahan_kemasan',
        'spesifikasi',
        'produsen',
        'distributor',
        'kode_produksi',
        'jumlah_datang',
        'jumlah_sampling',
        'kondisi_fisik',
        'ketebalan_micron',
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
