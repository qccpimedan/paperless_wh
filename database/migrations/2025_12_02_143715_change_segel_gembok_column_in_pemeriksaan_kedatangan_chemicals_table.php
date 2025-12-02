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
        Schema::table('pemeriksaan_kedatangan_chemicals', function (Blueprint $table) {
            // Change segel_gembok from boolean to varchar
            $table->string('segel_gembok', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_kedatangan_chemicals', function (Blueprint $table) {
            // Change back to boolean
            $table->boolean('segel_gembok')->default(false)->change();
        });
    }
};