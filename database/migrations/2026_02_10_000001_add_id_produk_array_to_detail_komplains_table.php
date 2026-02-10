<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_komplains', function (Blueprint $table) {
            if (!Schema::hasColumn('detail_komplains', 'id_produk_array')) {
                $table->json('id_produk_array')->nullable()->after('no_po');
            }
        });
    }

    public function down(): void
    {
        Schema::table('detail_komplains', function (Blueprint $table) {
            if (Schema::hasColumn('detail_komplains', 'id_produk_array')) {
                $table->dropColumn('id_produk_array');
            }
        });
    }
};
