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
            $table->dropColumn(['area_finish_good', 'dibuat_oleh', 'disetujui_oleh']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_suhu_ruang_v2s', function (Blueprint $table) {
            $table->string('area_finish_good')->nullable();
            $table->string('dibuat_oleh')->nullable();
            $table->string('disetujui_oleh')->nullable();
        });
    }
};
