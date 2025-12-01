<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('input_master_form_fields', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_master_form');
            $table->string('field_name');
            $table->integer('field_order')->default(0);
            $table->timestamps();

            $table->foreign('id_master_form')->references('id')->on('input_master_forms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('input_master_form_fields');
    }
};