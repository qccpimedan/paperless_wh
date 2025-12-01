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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('shift');
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            
            // Index for better performance
            $table->index('id_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
