<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemeriksaan_return_barang_customers', function (Blueprint $table) {
            // Drop foreign key first if exists
            try {
                $table->dropForeign('pemeriksaan_return_barang_customers_id_produk_foreign');
            } catch (\Exception $e) {
                // Foreign key doesn't exist, continue
            }
        });
        
        Schema::table('pemeriksaan_return_barang_customers', function (Blueprint $table) {
            $columns = [
                'kondisi_produk',
                'id_produk',
                'suhu_produk',
                'kode_produksi',
                'expired_date',
                'jumlah_barang',
                'kondisi_kemasan',
                'kondisi_produk_check',
                'rekomendasi',
                'keterangan'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('pemeriksaan_return_barang_customers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_return_barang_customers', function (Blueprint $table) {
            $table->string('kondisi_produk')->nullable();
            $table->unsignedBigInteger('id_produk')->nullable();
            $table->string('suhu_produk')->nullable();
            $table->string('kode_produksi')->nullable();
            $table->date('expired_date')->nullable();
            $table->string('jumlah_barang')->nullable();
            $table->boolean('kondisi_kemasan')->default(false);
            $table->boolean('kondisi_produk_check')->default(false);
            $table->string('rekomendasi')->nullable();
            $table->text('keterangan')->nullable();
        });
    }
};
