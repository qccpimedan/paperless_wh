<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_produk_finish_goods', function (Blueprint $table) {
            if (!Schema::hasColumn('pemeriksaan_produk_finish_goods', 'unit_datang_array')) {
                $table->json('unit_datang_array')->nullable()->after('jumlah_datang_array');
            }
            if (!Schema::hasColumn('pemeriksaan_produk_finish_goods', 'unit_sampling_array')) {
                $table->json('unit_sampling_array')->nullable()->after('jumlah_sampling_array');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_produk_finish_goods', function (Blueprint $table) {
            if (Schema::hasColumn('pemeriksaan_produk_finish_goods', 'unit_datang_array')) {
                $table->dropColumn('unit_datang_array');
            }
            if (Schema::hasColumn('pemeriksaan_produk_finish_goods', 'unit_sampling_array')) {
                $table->dropColumn('unit_sampling_array');
            }
        });
    }
};
