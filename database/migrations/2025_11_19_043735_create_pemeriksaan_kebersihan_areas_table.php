<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan_kebersihan_areas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_shift');
            $table->unsignedBigInteger('id_area');
            $table->unsignedBigInteger('id_master_form');
            $table->date('tanggal');
            $table->time('jam_sebelum_proses')->nullable();
            $table->time('jam_saat_proses')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_shift')->references('id')->on('shifts')->onDelete('cascade');
            $table->foreign('id_area')->references('id')->on('input_areas')->onDelete('cascade');
            $table->foreign('id_master_form')->references('id')->on('input_master_forms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_kebersihan_areas');
    }
};