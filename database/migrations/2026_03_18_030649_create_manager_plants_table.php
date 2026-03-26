<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot: manager_plants
     * Menyimpan daftar plant yang diizinkan diakses oleh user dengan role Manager.
     * Superadmin yang menentukan plant mana saja yang boleh diakses Manager tertentu.
     */
    public function up(): void
    {
        Schema::create('manager_plants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  // Manager user
            $table->unsignedBigInteger('plant_id'); // Plant yang diizinkan
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plant_id')->references('id')->on('plants')->onDelete('cascade');

            // Satu manager tidak bisa punya plant duplikat
            $table->unique(['user_id', 'plant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_plants');
    }
};
