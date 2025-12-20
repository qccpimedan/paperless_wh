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
            // Tambahkan kolom array untuk suhu mobil per row
            $table->json('suhu_mobil_array')->nullable()->after('spesifikasi_array');
            $table->json('suhu_mobil_type_array')->nullable()->after('suhu_mobil_array');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_kedatangan_bahan_baku_penunjangs', function (Blueprint $table) {
            $table->dropColumn(['suhu_mobil_array', 'suhu_mobil_type_array']);
        });
    }
};
