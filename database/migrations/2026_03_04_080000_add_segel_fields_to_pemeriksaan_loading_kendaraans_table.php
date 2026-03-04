<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_loading_kendaraans', function (Blueprint $table) {
            $table->boolean('segel_gembok')->nullable()->after('suhu_precooling');
            $table->string('no_segel')->nullable()->after('segel_gembok');
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_loading_kendaraans', function (Blueprint $table) {
            $table->dropColumn(['segel_gembok', 'no_segel']);
        });
    }
};
