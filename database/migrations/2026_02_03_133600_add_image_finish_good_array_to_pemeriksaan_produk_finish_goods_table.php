<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_produk_finish_goods', function (Blueprint $table) {
            if (!Schema::hasColumn('pemeriksaan_produk_finish_goods', 'image_finish_good_array')) {
                $table->json('image_finish_good_array')->nullable()->after('keterangan_array');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_produk_finish_goods', function (Blueprint $table) {
            if (Schema::hasColumn('pemeriksaan_produk_finish_goods', 'image_finish_good_array')) {
                $table->dropColumn('image_finish_good_array');
            }
        });
    }
};
