<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemeriksaanKebersihanAreaDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_pemeriksaan',
        'id_master_form_field',
        'status',
        'status_sebelum_proses',
        'status_saat_proses',
        'verifikasi_hasil',
        'keterangan',
        'tindakan_koreksi',
    ];

    protected $casts = [
        'status' => 'boolean',
        'status_sebelum_proses' => 'boolean',
        'status_saat_proses' => 'boolean',
        'verifikasi_hasil' => 'boolean',
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

    public function pemeriksaan()
    {
        return $this->belongsTo(PemeriksaanKebersihanArea::class, 'id_pemeriksaan');
    }

    public function field()
    {
        return $this->belongsTo(InputMasterFormField::class, 'id_master_form_field');
    }
}