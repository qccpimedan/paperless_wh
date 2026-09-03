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
        Schema::table('pemeriksaan_suhu_ruang_v3_histories', function (Blueprint $table) {
            $table->string('pukul_lama')->nullable()->after('id_user');
            $table->string('pukul_baru')->nullable()->after('pukul_lama');
            $table->string('suhu_produk_lama')->nullable()->after('pukul_baru');
            $table->string('suhu_produk_baru')->nullable()->after('suhu_produk_lama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_suhu_ruang_v3_histories', function (Blueprint $table) {
            $table->dropColumn(['pukul_lama', 'pukul_baru', 'suhu_produk_lama', 'suhu_produk_baru']);
        });
    }
};
