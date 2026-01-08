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
        Schema::table('chemicals', function (Blueprint $table) {
            $table->unsignedBigInteger('id_distributor')->nullable()->after('id_user');
            $table->unsignedBigInteger('id_produsen')->nullable()->after('id_distributor');

            $table->foreign('id_distributor')->references('id')->on('distributors')->nullOnDelete();
            $table->foreign('id_produsen')->references('id')->on('produsens')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chemicals', function (Blueprint $table) {
            $table->dropForeign(['id_distributor']);
            $table->dropForeign(['id_produsen']);
            $table->dropColumn(['id_distributor', 'id_produsen']);
        });
    }
};
