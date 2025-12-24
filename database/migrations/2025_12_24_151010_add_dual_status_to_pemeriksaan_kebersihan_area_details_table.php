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
        Schema::table('pemeriksaan_kebersihan_area_details', function (Blueprint $table) {
            $table->boolean('status_sebelum_proses')->nullable()->after('id_master_form_field');
            $table->boolean('status_saat_proses')->nullable()->after('status_sebelum_proses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_kebersihan_area_details', function (Blueprint $table) {
            $table->dropColumn(['status_sebelum_proses', 'status_saat_proses']);
        });
    }
};
