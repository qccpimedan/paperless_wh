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
        Schema::create('pemeriksaan_suhu_ruang_v3s', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_shift');
            $table->unsignedBigInteger('id_area');
            $table->date('tanggal');
            $table->time('pukul');
            
            // 8 Temperature fields - semua JSON
            $table->json('suhu_premix')->nullable();
            $table->json('suhu_seasoning')->nullable();
            $table->json('suhu_dry')->nullable();
            $table->json('suhu_cassing')->nullable();
            $table->json('suhu_beef')->nullable();
            $table->json('suhu_packaging')->nullable();
            $table->json('suhu_ruang_chemical')->nullable();
            $table->json('suhu_ruang_seasoning')->nullable();
            
            // Notes
            $table->text('keterangan')->nullable();
            $table->text('tindakan_koreksi')->nullable();
            
            $table->timestamps();
            
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_shift')->references('id')->on('shifts')->onDelete('cascade');
            $table->foreign('id_area')->references('id')->on('input_areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_suhu_ruang_v3s');
    }
};