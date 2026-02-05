<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_kedatangan_bahan_baku_penunjangs', function (Blueprint $table) {
            if (!Schema::hasColumn('pemeriksaan_kedatangan_bahan_baku_penunjangs', 'image_bahan_baku_array')) {
                $table->json('image_bahan_baku_array')->nullable()->after('file_coa_array');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_kedatangan_bahan_baku_penunjangs', function (Blueprint $table) {
            if (Schema::hasColumn('pemeriksaan_kedatangan_bahan_baku_penunjangs', 'image_bahan_baku_array')) {
                $table->dropColumn('image_bahan_baku_array');
            }
        });
    }
};
