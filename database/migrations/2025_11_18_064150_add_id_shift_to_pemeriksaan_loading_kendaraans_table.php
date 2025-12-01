<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_loading_kendaraans', function (Blueprint $table) {
            $table->unsignedBigInteger('id_shift')->nullable()->after('id_std_precooling');
            $table->foreign('id_shift')->references('id')->on('shifts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_loading_kendaraans', function (Blueprint $table) {
            $table->dropForeign(['id_shift']);
            $table->dropColumn('id_shift');
        });
    }
};