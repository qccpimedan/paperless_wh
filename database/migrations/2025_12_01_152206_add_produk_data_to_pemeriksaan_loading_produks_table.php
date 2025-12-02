<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pemeriksaan_loading_produks', function (Blueprint $table) {
            $table->json('produk_data')->nullable()->after('no_po');
        });
    }

    public function down(): void {
        Schema::table('pemeriksaan_loading_produks', function (Blueprint $table) {
            $table->dropColumn('produk_data');
        });
    }
};