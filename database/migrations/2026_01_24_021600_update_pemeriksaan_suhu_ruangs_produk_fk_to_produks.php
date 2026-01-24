<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $database = DB::getDatabaseName();

        $rows = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'pemeriksaan_suhu_ruangs' AND COLUMN_NAME = 'id_produk' AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$database]
        );

        foreach ($rows as $row) {
            $name = $row->CONSTRAINT_NAME ?? null;
            if ($name) {
                DB::statement("ALTER TABLE pemeriksaan_suhu_ruangs DROP FOREIGN KEY `{$name}`");
            }
        }

        Schema::table('pemeriksaan_suhu_ruangs', function (Blueprint $table) {
            $table->foreign('id_produk')->references('id')->on('produks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        $database = DB::getDatabaseName();

        $rows = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'pemeriksaan_suhu_ruangs' AND COLUMN_NAME = 'id_produk' AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$database]
        );

        foreach ($rows as $row) {
            $name = $row->CONSTRAINT_NAME ?? null;
            if ($name) {
                DB::statement("ALTER TABLE pemeriksaan_suhu_ruangs DROP FOREIGN KEY `{$name}`");
            }
        }

        Schema::table('pemeriksaan_suhu_ruangs', function (Blueprint $table) {
            $table->foreign('id_produk')->references('id')->on('bahans')->onDelete('cascade');
        });
    }
};
