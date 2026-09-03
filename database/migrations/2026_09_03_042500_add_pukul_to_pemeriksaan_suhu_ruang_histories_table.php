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
        Schema::table('pemeriksaan_suhu_ruang_histories', function (Blueprint $table) {
            $table->string('pukul_lama')->nullable()->after('id_user');
            $table->string('pukul_baru')->nullable()->after('pukul_lama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_suhu_ruang_histories', function (Blueprint $table) {
            $table->dropColumn(['pukul_lama', 'pukul_baru']);
        });
    }
};
