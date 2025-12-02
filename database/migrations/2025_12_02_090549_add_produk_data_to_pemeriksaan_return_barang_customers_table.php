<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pemeriksaan_return_barang_customers', function (Blueprint $table) {
            $table->json('produk_data')->nullable()->after('alasan_return');
        });
    }

    public function down(): void {
        Schema::table('pemeriksaan_return_barang_customers', function (Blueprint $table) {
            if (Schema::hasColumn('pemeriksaan_return_barang_customers', 'produk_data')) {
                $table->dropColumn('produk_data');
            }
        });
    }
};