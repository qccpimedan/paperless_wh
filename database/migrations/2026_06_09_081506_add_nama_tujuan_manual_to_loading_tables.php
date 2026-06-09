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
            $table->string('nama_tujuan_manual')->nullable()->after('no_segel');
        });

        Schema::table('pemeriksaan_loading_produks', function (Blueprint $table) {
            $table->string('nama_tujuan_manual')->nullable()->after('no_segel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_loading_kendaraans', function (Blueprint $table) {
            $table->dropColumn('nama_tujuan_manual');
        });

        Schema::table('pemeriksaan_loading_produks', function (Blueprint $table) {
            $table->dropColumn('nama_tujuan_manual');
        });
    }
};
