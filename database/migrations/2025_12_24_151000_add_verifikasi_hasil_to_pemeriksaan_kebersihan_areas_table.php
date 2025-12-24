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
        Schema::table('pemeriksaan_kebersihan_areas', function (Blueprint $table) {
            $table->boolean('verifikasi_hasil')->nullable()->after('jam_saat_proses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_kebersihan_areas', function (Blueprint $table) {
            $table->dropColumn('verifikasi_hasil');
        });
    }
};
