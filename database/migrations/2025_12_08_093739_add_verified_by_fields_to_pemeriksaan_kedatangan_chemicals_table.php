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
        Schema::table('pemeriksaan_kedatangan_chemicals', function (Blueprint $table) {
            $table->bigInteger('verified_by_qc')->nullable()->after('verified_by');
            $table->bigInteger('verified_by_produksi')->nullable()->after('verified_by_qc');
            $table->bigInteger('verified_by_spv')->nullable()->after('verified_by_produksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_kedatangan_chemicals', function (Blueprint $table) {
            $table->dropColumn(['verified_by_qc', 'verified_by_produksi', 'verified_by_spv']);
        });
    }
};
