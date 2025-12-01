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
        Schema::create('input_area_locations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_input_area');
            $table->string('lokasi_area')->nullable();
            $table->timestamps();

            $table->foreign('id_input_area')
                ->references('id')->on('input_areas')
                ->onDelete('cascade')
                ->name('ial_input_area_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_area_locations');
    }
};