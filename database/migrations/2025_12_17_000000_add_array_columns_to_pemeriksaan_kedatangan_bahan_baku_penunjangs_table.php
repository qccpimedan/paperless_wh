<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pemeriksaan_kedatangan_bahan_baku_penunjangs', function (Blueprint $table) {
            // Tambahkan kolom array untuk dynamic rows
            $table->json('id_bahan_array')->nullable()->after('id_bahan');
            $table->json('produsen_array')->nullable()->after('produsen');
            $table->json('negara_produsen_array')->nullable()->after('negara_produsen');
            $table->json('distributor_array')->nullable()->after('distributor');
            $table->json('kode_produksi_array')->nullable()->after('kode_produksi');
            $table->json('expire_date_array')->nullable()->after('expire_date');
            $table->json('jumlah_datang_array')->nullable()->after('jumlah_datang');
            $table->json('jumlah_sampling_array')->nullable()->after('jumlah_sampling');
            $table->json('spesifikasi_array')->nullable()->after('spesifikasi');
            $table->json('kondisi_fisik_array')->nullable()->after('kondisi_fisik');
            $table->json('logo_halal_array')->nullable()->after('logo_halal');
            $table->json('hasil_uji_ffa_array')->nullable()->after('hasil_uji_ffa');
            $table->json('dokumen_halal_array')->nullable()->after('dokumen_halal');
            $table->json('coa_array')->nullable()->after('coa');
            $table->json('keterangan_array')->nullable()->after('keterangan');
        });
    }

    public function down()
    {
        Schema::table('pemeriksaan_kedatangan_bahan_baku_penunjangs', function (Blueprint $table) {
            $table->dropColumn([
                'id_bahan_array',
                'produsen_array',
                'negara_produsen_array',
                'distributor_array',
                'kode_produksi_array',
                'expire_date_array',
                'jumlah_datang_array',
                'jumlah_sampling_array',
                'spesifikasi_array',
                'kondisi_fisik_array',
                'logo_halal_array',
                'hasil_uji_ffa_array',
                'dokumen_halal_array',
                'coa_array',
                'keterangan_array',
            ]);
        });
    }
};
