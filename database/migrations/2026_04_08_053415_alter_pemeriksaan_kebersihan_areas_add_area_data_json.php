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
            $table->dropForeign(['id_area']);
            $table->dropForeign(['id_master_form']);
            $table->dropColumn(['id_area', 'id_master_form', 'jam_sebelum_proses', 'jam_saat_proses']);
            $table->json('area_data')->nullable()->after('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_kebersihan_areas', function (Blueprint $table) {
            $table->dropColumn('area_data');
            $table->unsignedBigInteger('id_area')->nullable();
            $table->unsignedBigInteger('id_master_form')->nullable();
            $table->time('jam_sebelum_proses')->nullable();
            $table->time('jam_saat_proses')->nullable();

            $table->foreign('id_area')->references('id')->on('input_areas')->onDelete('cascade');
            $table->foreign('id_master_form')->references('id')->on('input_master_forms')->onDelete('cascade');
        });
    }
};
