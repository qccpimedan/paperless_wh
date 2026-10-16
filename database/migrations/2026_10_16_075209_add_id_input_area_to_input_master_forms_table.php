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
        Schema::table('input_master_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('id_input_area')->nullable()->after('id_user');
            $table->foreign('id_input_area')->references('id')->on('input_areas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('input_master_forms', function (Blueprint $table) {
            $table->dropForeign(['id_input_area']);
            $table->dropColumn('id_input_area');
        });
    }
};
