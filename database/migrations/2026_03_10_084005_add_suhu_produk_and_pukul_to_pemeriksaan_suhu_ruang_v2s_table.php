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
        Schema::table('pemeriksaan_suhu_ruang_v2s', function (Blueprint $table) {
            $table->string('suhu_produk')->nullable()->after('tanggal');
            $table->string('pukul')->nullable()->after('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_suhu_ruang_v2s', function (Blueprint $table) {
            $table->dropColumn(['suhu_produk', 'pukul']);
        });
    }
};
