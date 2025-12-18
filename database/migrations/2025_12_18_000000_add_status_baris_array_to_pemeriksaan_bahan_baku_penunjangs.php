<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pemeriksaan_kedatangan_bahan_baku_penunjangs', function (Blueprint $table) {
            $table->json('status_baris_array')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('pemeriksaan_kedatangan_bahan_baku_penunjangs', function (Blueprint $table) {
            $table->dropColumn('status_baris_array');
        });
    }
};
