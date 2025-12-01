<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan_kebersihan_area_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->unsignedBigInteger('id_master_form_field');
            $table->boolean('status')->nullable(); // true = ✓, false = ✗
            $table->string('keterangan')->nullable();
            $table->string('tindakan_koreksi')->nullable();
            $table->timestamps();

            $table->foreign('id_pemeriksaan')->references('id')->on('pemeriksaan_kebersihan_areas')->onDelete('cascade');
            $table->foreign('id_master_form_field')->references('id')->on('input_master_form_fields')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_kebersihan_area_details');
    }
};