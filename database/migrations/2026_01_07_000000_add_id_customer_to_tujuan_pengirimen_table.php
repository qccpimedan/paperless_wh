<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tujuan_pengirimen', function (Blueprint $table) {
            $table->unsignedBigInteger('id_customer')->nullable()->after('id_user');
            $table->foreign('id_customer')->references('id')->on('customers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tujuan_pengirimen', function (Blueprint $table) {
            $table->dropForeign(['id_customer']);
            $table->dropColumn('id_customer');
        });
    }
};
