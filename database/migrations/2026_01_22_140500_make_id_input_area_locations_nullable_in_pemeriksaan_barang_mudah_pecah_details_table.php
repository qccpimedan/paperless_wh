<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $constraint = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->select('CONSTRAINT_NAME')
            ->whereRaw('TABLE_SCHEMA = DATABASE()')
            ->where('TABLE_NAME', 'pemeriksaan_barang_mudah_pecah_details')
            ->where('COLUMN_NAME', 'id_input_area_locations')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->value('CONSTRAINT_NAME');

        if (!empty($constraint)) {
            DB::statement("ALTER TABLE `pemeriksaan_barang_mudah_pecah_details` DROP FOREIGN KEY `{$constraint}`");
        }

        Schema::table('pemeriksaan_barang_mudah_pecah_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id_input_area_locations')->nullable()->change();
            $table->foreign('id_input_area_locations')
                ->references('id')
                ->on('input_area_locations')
                ->name('fk_detail_area_location')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        $constraint = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->select('CONSTRAINT_NAME')
            ->whereRaw('TABLE_SCHEMA = DATABASE()')
            ->where('TABLE_NAME', 'pemeriksaan_barang_mudah_pecah_details')
            ->where('COLUMN_NAME', 'id_input_area_locations')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->value('CONSTRAINT_NAME');

        if (!empty($constraint)) {
            DB::statement("ALTER TABLE `pemeriksaan_barang_mudah_pecah_details` DROP FOREIGN KEY `{$constraint}`");
        }

        Schema::table('pemeriksaan_barang_mudah_pecah_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id_input_area_locations')->nullable(false)->change();
            $table->foreign('id_input_area_locations')
                ->references('id')
                ->on('input_area_locations')
                ->name('fk_detail_area_location')
                ->onDelete('cascade');
        });
    }
};
