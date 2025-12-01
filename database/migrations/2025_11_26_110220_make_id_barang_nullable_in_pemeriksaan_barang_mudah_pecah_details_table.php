<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_barang_mudah_pecah_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id_barang')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_barang_mudah_pecah_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id_barang')->nullable(false)->change();
        });
    }
};