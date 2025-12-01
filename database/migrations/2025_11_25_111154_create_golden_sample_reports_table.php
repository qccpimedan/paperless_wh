<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('golden_sample_reports', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_plant')->nullable();
            $table->string('plant_manual')->nullable();
            $table->string('sample_type');
            $table->date('collection_date');
            $table->json('sample_storage'); // ["Frozen", "Chilled", "Ambient"]
            $table->json('samples'); // Array berisi semua sampel
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_plant')->references('id')->on('plants')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('golden_sample_reports');
    }
};