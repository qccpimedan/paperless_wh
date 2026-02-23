<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanKedatanganChemical extends Model
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
        'detail_chemicals', // JSON untuk multiple rows
        'status_verifikasi',
        'verified_by',
        'verified_by_qc',
        'verified_by_produksi',
        'verified_by_spv',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'kondisi_mobil' => 'array',
        'detail_chemicals' => 'array', // Cast JSON ke array
        'tanggal' => 'date',
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

    // Relasi chemical, produsen, distributor sudah tidak digunakan
    // karena data sekarang disimpan dalam JSON detail_chemicals

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