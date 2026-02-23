<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('pemeriksaan_suhu_ruang_v2s')) {
            return;
        }

        // Use raw SQL to avoid doctrine/dbal dependency for altering columns.
        // This keeps the foreign key intact and simply allows NULL values.
        DB::statement('ALTER TABLE pemeriksaan_suhu_ruang_v2s MODIFY id_area BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('pemeriksaan_suhu_ruang_v2s')) {
            return;
        }

        DB::statement('ALTER TABLE pemeriksaan_suhu_ruang_v2s MODIFY id_area BIGINT UNSIGNED NOT NULL');
    }
};
