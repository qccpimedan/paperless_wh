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
            // Add single row unit columns
            $table->string('unit_datang')->nullable()->after('jumlah_datang');
            $table->string('unit_sampling')->nullable()->after('jumlah_sampling');

            // Add array unit columns for dynamic rows
            $table->json('unit_datang_array')->nullable()->after('jumlah_datang_array');
            $table->json('unit_sampling_array')->nullable()->after('jumlah_sampling_array');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_kedatangan_bahan_baku_penunjangs', function (Blueprint $table) {
            $table->dropColumn(['unit_datang', 'unit_sampling', 'unit_datang_array', 'unit_sampling_array']);
        });
    }
};
