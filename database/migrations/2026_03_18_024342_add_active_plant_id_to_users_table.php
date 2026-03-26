<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tambahkan kolom active_plant_id ke tabel users.
     * Kolom ini digunakan oleh role Manager untuk menyimpan plant yang sedang aktif (switch plant).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('active_plant_id')->nullable()->after('id_plant');
            $table->foreign('active_plant_id')->references('id')->on('plants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['active_plant_id']);
            $table->dropColumn('active_plant_id');
        });
    }
};
