<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pemeriksaan_kedatangan_chemicals', function (Blueprint $table) {
            // Drop kolom individual yang akan diganti dengan JSON
            $table->dropForeign(['id_chemical']);
            $table->dropForeign(['id_produsen']);
            $table->dropForeign(['id_distributor']);
            
            $table->dropColumn([
                'id_chemical',
                'id_produsen',
                'negara_produsen',
                'id_distributor',
                'kode_produksi',
                'expire_date',
                'kondisi_chemical',
                'jumlah_datang',
                'jumlah_sampling',
                'kondisi_fisik',
                'persyaratan_dokumen_halal',
                'coa',
                'status',
                'keterangan',
            ]);
            
            // Tambah kolom JSON untuk menyimpan multiple rows
            $table->json('detail_chemicals')->nullable()->after('kondisi_mobil');
        });
    }

    public function down()
    {
        Schema::table('pemeriksaan_kedatangan_chemicals', function (Blueprint $table) {
            // Kembalikan kolom individual
            $table->foreignId('id_chemical')->nullable()->constrained('chemicals')->onDelete('set null');
            $table->foreignId('id_produsen')->nullable()->constrained('produsens')->onDelete('set null');
            $table->string('negara_produsen')->nullable();
            $table->foreignId('id_distributor')->nullable()->constrained('distributors')->onDelete('set null');
            $table->string('kode_produksi')->nullable();
            $table->date('expire_date')->nullable();
            $table->enum('kondisi_chemical', ['Cair', 'Serbuk'])->nullable();
            $table->string('jumlah_datang')->nullable();
            $table->string('jumlah_sampling')->nullable();
            $table->json('kondisi_fisik')->nullable();
            $table->boolean('persyaratan_dokumen_halal')->default(false);
            $table->boolean('coa')->default(false);
            $table->enum('status', ['Release', 'Hold']);
            $table->text('keterangan')->nullable();
            
            // Drop kolom JSON
            $table->dropColumn('detail_chemicals');
        });
    }
};
