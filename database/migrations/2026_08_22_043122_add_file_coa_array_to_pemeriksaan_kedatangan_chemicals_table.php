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
            $table->text('file_coa_array')->nullable()->after('detail_chemicals');
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_kedatangan_chemicals', function (Blueprint $table) {
            $table->dropColumn('file_coa_array');
        });
    }
};
