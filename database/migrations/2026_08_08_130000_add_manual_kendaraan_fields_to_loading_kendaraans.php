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
        Schema::table('pemeriksaan_loading_kendaraans', function (Blueprint $table) {
            // Make id_kendaraan nullable so manual input doesn't require master record
            $table->unsignedBigInteger('id_kendaraan')->nullable()->change();
            // Make id_tujuan_pengiriman nullable so manual input doesn't require master record
            $table->unsignedBigInteger('id_tujuan_pengiriman')->nullable()->change();
            // Add manual text columns for kendaraan (tujuan manual already exists)
            $table->string('jenis_kendaraan_manual')->nullable()->after('id_kendaraan');
            $table->string('no_kendaraan_manual')->nullable()->after('jenis_kendaraan_manual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_loading_kendaraans', function (Blueprint $table) {
            $table->dropColumn(['jenis_kendaraan_manual', 'no_kendaraan_manual']);
            $table->unsignedBigInteger('id_kendaraan')->nullable(false)->change();
            $table->unsignedBigInteger('id_tujuan_pengiriman')->nullable(false)->change();
        });
    }
};
