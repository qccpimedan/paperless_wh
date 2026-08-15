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
        Schema::table('pemeriksaan_kedatangan_kemasans', function (Blueprint $table) {
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'unit_datang')) {
                $table->string('unit_datang')->nullable()->after('jumlah_datang');
            }
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'unit_sampling')) {
                $table->string('unit_sampling')->nullable()->after('jumlah_sampling');
            }
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'unit_datang_array')) {
                $table->json('unit_datang_array')->nullable()->after('jumlah_datang_array');
            }
            if (!Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'unit_sampling_array')) {
                $table->json('unit_sampling_array')->nullable()->after('jumlah_sampling_array');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_kedatangan_kemasans', function (Blueprint $table) {
            if (Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'unit_datang')) {
                $table->dropColumn('unit_datang');
            }
            if (Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'unit_sampling')) {
                $table->dropColumn('unit_sampling');
            }
            if (Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'unit_datang_array')) {
                $table->dropColumn('unit_datang_array');
            }
            if (Schema::hasColumn('pemeriksaan_kedatangan_kemasans', 'unit_sampling_array')) {
                $table->dropColumn('unit_sampling_array');
            }
        });
    }
};
