<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_produk_finish_goods', function (Blueprint $table) {
            $table->string('suhu_mobil_value')->nullable()->after('suhu_mobil');
            $table->string('suhu_produk_value')->nullable()->after('suhu_produk');
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_produk_finish_goods', function (Blueprint $table) {
            $table->dropColumn(['suhu_mobil_value', 'suhu_produk_value']);
        });
    }
};
