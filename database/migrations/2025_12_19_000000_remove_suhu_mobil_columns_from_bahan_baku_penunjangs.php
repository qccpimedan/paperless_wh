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
        Schema::table('pemeriksaan_kedatangan_bahan_baku_penunjangs', function (Blueprint $table) {
            // Hapus kolom suhu_mobil dan suhu_mobil_type karena sekarang ada di dalam detail_produk JSON
            $table->dropColumn(['suhu_mobil', 'suhu_mobil_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_kedatangan_bahan_baku_penunjangs', function (Blueprint $table) {
            // Kembalikan kolom jika rollback
            $table->string('suhu_mobil', 255)->nullable()->after('kondisi_produk_suhu');
            $table->string('suhu_mobil_type', 255)->nullable()->after('suhu_mobil');
        });
    }
};
