<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_produk_finish_goods', function (Blueprint $table) {
            $table->json('suhu_mobil_type_array')->nullable()->after('kondisi_produk');
            $table->json('suhu_mobil_value_array')->nullable()->after('suhu_mobil_type_array');
            $table->json('suhu_produk_type_array')->nullable()->after('suhu_mobil_value_array');
            $table->json('suhu_produk_value_array')->nullable()->after('suhu_produk_type_array');
            $table->json('kondisi_produk_array')->nullable()->after('suhu_produk_value_array');
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_produk_finish_goods', function (Blueprint $table) {
            $table->dropColumn([
                'suhu_mobil_type_array',
                'suhu_mobil_value_array',
                'suhu_produk_type_array',
                'suhu_produk_value_array',
                'kondisi_produk_array',
            ]);
        });
    }
};
